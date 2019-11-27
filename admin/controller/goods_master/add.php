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

include('model/product_quotation.php');
$obj_quotation = new productQuotation;

//Start : edit
$edit = '';
if(isset($_GET['goods_master_id']) && !empty($_GET['goods_master_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$goods_master_id = base64_decode($_GET['goods_master_id']);
		$goods_data = $obj_goods_master->getGoodsData($goods_master_id);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert 
	if(isset($_POST['btn_save'])){
		$name = $obj_goods_master->checkName($_POST['name']);


		if($name < 1) {
			$post = post($_POST);		
			$insert_id = $obj_goods_master->addGodds($post);
			$obj_session->data['success'] = ADD;
			page_redirect($obj_general->link($rout, '', '',1));
		} else {
			$obj_session->data['warning'] = 'The Name You Are Entered is Already Exist!!!';
		}
	}
	
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		
		if($_POST['name'] != $goods_data['name']) {
			$name = $obj_goods_master->checkName($_POST['name']);
		} else {
			$name = 0;
		}

		if($name < 1) {
			$goods_master_id = $goods_data['goods_master_id'];
			$obj_goods_master->updateGoods($goods_master_id,$post);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout,'', '',1));
		} else {
			$obj_session->data['warning'] = 'The Name You Are Entered is Already Exist!!!';
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
                <label class="col-lg-3 control-label">Rack Name</label>
                <div class="col-lg-4">
                  <input type="text" name="name" id="name" value="<?php echo isset($goods_data['name'])?$goods_data['name']:'';?>" class="form-control" />                  
                </div>
              </div>

             
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Row</label>
                <div class="col-lg-4">
                  <!--input type="text" name="row" id="row" value="<?php echo isset($goods_data['row'])?$goods_data['row']:'';?>" class="form-control validate[required,custom[number]]"-->
                  <select name="row" id="row" class="form-control">
                  <?php for($i=1;$i<=32;$i++){?>
                  	<option value="<?php echo $i; ?>" 
					<?php if(isset($goods_data['row']) && $goods_data['row']==$i) echo 'selected="selected"';?> ><?php echo $i; ?> </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Column</label>
                <div class="col-lg-4">
                  <!--input type="text" name="column" id="column" value="<?php echo isset($goods_data['column_name'])?$goods_data['column_name']:'';?>" class="form-control validate[required,custom[number]]"-->
                  <select name="column" id="column" class="form-control">
                  <?php for($i=1;$i<=32;$i++){?>
                  	<option value="<?php echo $i; ?>"
                    <?php if(isset($goods_data['column_name']) && $goods_data['column_name']==$i) echo 'selected="selected"';?> ><?php echo $i; ?> </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Company Name</label>
                <div class="col-lg-4">
                  <input type="text" name="company" id="company" value="<?php echo isset($goods_data['company'])?$goods_data['company']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Rack Capacity</label>
                <div class="col-lg-4">
                  <input type="text" name="capacity" id="capacity" value="<?php echo isset($goods_data['capacity'])?$goods_data['capacity']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Description</label>
                <div class="col-lg-8">
                  <textarea class="form-control validate[required]" row="10" col="15" name="description" id="description"><?php echo isset($goods_data['description'])?$goods_data['description']:'';?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($goods_data['status']) && $goods_data['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($goods_data['status']) && $goods_data['status'] == 0)?'selected':'';?>> Inactive</option>
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
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> </div>
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
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		//jQuery(".product-div").validationEngine();
    });
	

</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
