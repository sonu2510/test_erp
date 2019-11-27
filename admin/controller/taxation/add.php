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
if(isset($_GET['taxation_id']) && !empty($_GET['taxation_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$taxation_id = base64_decode($_GET['taxation_id']);
		$taxation = $obj_taxation->getTaxation($taxation_id);
		
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
		$insert_id = $obj_taxation->addTaxation($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$taxation_id = $taxation['taxation_id'];
		$obj_taxation->updateTaxation($taxation_id,$post);
		$obj_session->data['success'] = UPDATE;
		if(isset($obj_session->data['page'])){
			$pageString = '&page='.$obj_session->data['page'];
			unset($obj_session->data['page']);
		}else{
			$pageString = '';
		}
		page_redirect($obj_general->link($rout, $pageString, '',1));
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
                <label class="col-lg-3 control-label"><span class="required">*</span>Tax Form Name </label>
                <div class="col-lg-4">
                  	<input type="text" name="tax_name" value="<?php echo isset($taxation['tax_name'])?$taxation['tax_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Excies (%)</label>
                <div class="col-lg-4">
                  	<input type="text" name="excies" value="<?php echo isset($taxation['excies'])?$taxation['excies']:'';?>" class="form-control ">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">CST With Form C (%)</label>
                <div class="col-lg-4">
                  	<input type="text" name="cst_with_form" value="<?php echo isset($taxation['cst_with_form_c'])?$taxation['cst_with_form_c']:'';?>" class="form-control " >
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">CST With Out Form C (%)</label>
                <div class="col-lg-4">
                  	<input type="text" name="cst_without_form" value="<?php echo isset($taxation['cst_without_form_c'])?$taxation['cst_without_form_c']:'';?>" class="form-control " >
                </div>
              </div>
      
              <div class="form-group">
                <label class="col-lg-3 control-label">VAT (%)</label>
                <div class="col-lg-4">
                    <input type="text" name="vat" value="<?php echo isset($taxation['vat'])?$taxation['vat']:'';?>" class="form-control ">
                 </div>
              </div>
			  <div class="form-group">
                <label class="col-lg-3 control-label">CGST (%)</label>
                <div class="col-lg-4">
                    <input type="text" name="cgst" value="<?php echo isset($taxation['cgst'])?$taxation['cgst']:'';?>" class="form-control ">
                 </div>
              </div>
			  <div class="form-group">
                <label class="col-lg-3 control-label">SGST (%)</label>
                <div class="col-lg-4">
                    <input type="text" name="sgst" value="<?php echo isset($taxation['sgst'])?$taxation['sgst']:'';?>" class="form-control ">
                 </div>
              </div>
			  <div class="form-group">
                <label class="col-lg-3 control-label">IGST(%)</label>
                <div class="col-lg-4">
                    <input type="text" name="igst" value="<?php echo isset($taxation['igst'])?$taxation['igst']:'';?>" class="form-control ">
                 </div>
              </div>
     
                         
             <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($taxation['status']) && $taxation['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($taxation['status']) && $taxation['status'] == 0)?'selected':'';?>> Inactive</option>
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
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>