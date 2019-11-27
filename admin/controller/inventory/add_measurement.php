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
	'text' 	=> 'Product Unit List',
	'href' 	=> $obj_general->link($rout, 'mod=measurement', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Product Unit Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit
//Start : edit
$edit = '';

if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
		$product_id = $_GET['product_id'];
		$product = $obj_inventory->getProduct($product_id );
		$edit = 1;
}
//Close : edit
if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
		//printr($post);
		$insert_id = $obj_inventory->addProductMeasurement($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, 'mod=measurement', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$product_id = $product['product_id'];
		$obj_inventory->updateProductMeasurement($product_id ,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'mod=measurement', '',1));
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
        
      <div class="col-sm-8" style="width:100%">
        <section class="panel">
          <header class="panel-heading bg-white"> Product Unit Detail </header>
     
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="inventory-form" enctype="multipart/form-data" >
              
             
              <div class="form-group">
                <label class="col-lg-3 control-label">Product Measurement</label>
                <div class="col-lg-8" style="width:200px">
                      <input type="text" name="measurement" placeholder="measurement" value="<?php echo isset($product['measurement'])?$product['measurement']:'';?>" class="form-control validate[required]">
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
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=measurement', '',1);?>">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style>

ul {
    list-style-type:none;
    padding:0px;
    margin:0px;
}

.selected {
    background-color:#efefef;
}
</style>


<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>