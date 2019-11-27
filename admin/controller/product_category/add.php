<?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit
$edit = '';

if(isset($_GET['product_category_id']) && !empty($_GET['product_category_id'])){
		$product_category_id = $_GET['product_category_id'];
		$product = $obj_product_category->getProduct($product_category_id );
		//printr($product);
		//die;
		$edit = 1;
}
//Close : edit
if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		$insert_id = $obj_product_category->addProductcategory($post);
		if($insert_id==0){
			$obj_session->data['warning'] = WARNING;
		}else{
			$obj_session->data['success'] = ADD;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$product_category_id = $product['product_category_id'];
		$res=$obj_product_category->updateProductcategory($product_category_id ,$post);
		//printr($res);
		if($res==0){
			$obj_session->data['warning'] = WARNING;
		}else{
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
		}
	}
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
     
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Category Name</label>
                <div class="col-lg-12" style="width:200px">
                      <input type="text" name="product_category_name" id="product_category_name" placeholder="Category Name" value="<?php echo isset($product['product_category_name'])?$product['product_category_name']:'';?>" class="col-lg-4 form-control validate[required]">
                      </div>
                    <div class="col-lg-8" style="width:100px">
                </div>
              </div>
             
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($product['status']) && $product['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($product['status']) && $product['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<!--priya-->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<script>

	jQuery(document).ready(function(){
        jQuery("#form").validationEngine();
		CKEDITOR.replace('desc_text');
    });
//priya
/*	$('#Product_category_name').change(function () {
			if (this.value.match(/[^a-zA-Z ]/g)) {
			//this.value = this.value.replace(/[^a-zA-Z]/g, ”);
			alert("Please Enter Only Characters");
			$('#Product_category_name').val("");
			}
	});*/

</script> 

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>