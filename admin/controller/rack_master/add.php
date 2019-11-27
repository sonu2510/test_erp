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
if(isset($_GET['rack_master_id']) && !empty($_GET['rack_master_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$rack_master_id = base64_decode($_GET['rack_master_id']);
		$rack_data = $obj_rack_master->getRackData($rack_master_id);
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
		$post = post($_POST);		
		$insert_id = $obj_rack_master->addRack($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$rack_master_id = $rack_data['rack_master_id'];
		$obj_rack_master->updateRack($rack_master_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout,'', '',1));
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
                <label class="col-lg-3 control-label"><span class="required">*</span>Row</label>
                <div class="col-lg-4">
                  <input type="text" name="row" id="row" value="<?php echo isset($rack_data['row'])?$rack_data['row']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Column</label>
                <div class="col-lg-4">
                  <input type="text" name="column" id="column" value="<?php echo isset($rack_data['column_no'])?$rack_data['column_no']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
            
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($rack_data['status']) && $rack_data['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($rack_data['status']) && $rack_data['status'] == 0)?'selected':'';?>> Inactive</option>
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
