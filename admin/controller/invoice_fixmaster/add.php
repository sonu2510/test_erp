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
if(isset($_GET['fix_master_id']) && !empty($_GET['fix_master_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$fix_master_id = base64_decode($_GET['fix_master_id']);
		$fixmaster = $obj_fixmaster->getInvoicefix($fix_master_id);
		//$courier_data = $obj_country->getCouriers();
	//	printr($courier_data);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$insert_id = $obj_fixmaster->addFixmaster($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$fixmaster_id = $fixmaster['fix_master_id'];
		$obj_fixmaster->updateFixmaster($fixmaster_id,$post);
		//die;
		$obj_session->data['success'] = UPDATE;
		if(isset($obj_session->data['page'])){
			$pageString = '&page='.$obj_session->data['page'];
			unset($obj_session->data['page']);
		}else{
			$pageString = '';
		}
		page_redirect($obj_general->link($rout,'', '',1));
		//die;
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
                <label class="col-lg-4 control-label"><span class="required">*</span>Exporter </label>
                <div class="col-lg-6">
                  <!--	<input type="text" name="exporter" value="<?php echo isset($fixmaster['exporter'])?$fixmaster['exporter']:'';?>" class="form-control validate[required]">-->
                    <textarea name="exporter" class="form-control validate[required]" value=""><?php echo isset($fixmaster['exporter'])?$fixmaster['exporter']:'';?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-4 control-label"><span class="required">*</span>Country of origin of goods </label>
                <div class="col-lg-6">
                  	<input type="text" name="country_origin_goods" id="country_origin_goods" value="<?php echo isset($fixmaster['country_origin_goods'])?$fixmaster['country_origin_goods']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <!-- kinjal -->
              <div class="form-group">
                <label class="col-lg-4 control-label"><span class="required">*</span>Marks & No./Container No.</label>
                <div class="col-lg-6">
                     <textarea name="mark_num" class="form-control validate[required]" value=""><?php echo isset($fixmaster['mark_no'])?$fixmaster['mark_no']:'';?></textarea>
                   
                   
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-4 control-label"><span class="required">*</span>No. & Kind of Packages</label>
                <div class="col-lg-6">
                    <textarea name="num_packages" class="form-control validate[required]" value=""><?php echo isset($fixmaster['num_packages'])?$fixmaster['num_packages']:'';?></textarea>
                   
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-4 control-label"><span class="required">*</span>Godds Description</label>
                <div class="col-lg-6">
                    <textarea name="goods_des" class="form-control validate[required]" value=""><?php echo isset($fixmaster['googs_description'])?$fixmaster['googs_description']:'';?></textarea>
                   
                   
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-4 control-label"><span class="required">*</span>Declaration</label>
                <div class="col-lg-6">
                     <textarea name="declaration" class="form-control validate[required]" value=""><?php echo isset($fixmaster['declaration'])?$fixmaster['declaration']:'';?></textarea>
                   
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-4 control-label"><span class="required">*</span>Footer Notes For Air</label>
                <div class="col-lg-6">
                     <textarea name="footer_notes" class="form-control validate[required]" value=""><?php echo isset($fixmaster['notes'])?$fixmaster['notes']:'';?></textarea>
                   
                   
                </div>
              </div>
              
             <div class="form-group">
                <label class="col-lg-4 control-label"><span class="required">*</span>Footer Notes For Sea</label>
                <div class="col-lg-6">
                     <textarea name="sea_notes" class="form-control validate[required]" value=""><?php echo isset($fixmaster['sea_notes'])?$fixmaster['sea_notes']:'';?></textarea>
                   
                   
                </div>
              </div>
              
             <div class="form-group">
                <label class="col-lg-4 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($fixmaster['status']) && $fixmaster['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($fixmaster['status']) && $fixmaster['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-lg-10 col-lg-offset-4">
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
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
	
	$('#country_origin_goods').keyup(function() 
	{
		if (this.value.match(/[^a-zA-Z ]/g))
		{
			alert("Please Enter Only Characters");
			$('#country_origin_goods').val('');
		}
	});
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>