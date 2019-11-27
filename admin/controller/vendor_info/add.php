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

//edit user
$edit = '';


if(isset($_GET['vendor_info_id']) && !empty($_GET['vendor_info_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$vendor_info_id = base64_decode($_GET['vendor_info_id']);
		//printr($vendor_info_id);
		$vendor = $obj_vendor_info->getVenderValue($vendor_info_id);
	
		
		//printr($user);die;
		$edit = 1;
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}

if($display_status){
		
		if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$vendor_info_id = $vendor['vendor_info_id'];
		$obj_vendor_info->updateVender($vendor_info_id,$post);
		$obj_session->data['success'] = UPDATE;
		if(isset($obj_session->data['page'])){
			$pageString = '&page='.$obj_session->data['page'];
			unset($obj_session->data['page']);
		}else{
			$pageString = '';
		}
		page_redirect($obj_general->link($rout, $pageString.'&filter_edit='.$_GET['filter_edit'], '',1));
	}
	
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
		$insert_id = $obj_vendor_info->addVender($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	
	}

?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-user"></i> Vendor Information </h4>
    </div>
    <div class="row">
    	
        <div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white">Vendor Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Company Name</label>
                <div class="col-lg-8">
                  <input type="text" name="companyname" placeholder="Company Name" value="<?php echo isset($vendor['company_name'])?$vendor['company_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">First Name</label>
                <div class="col-lg-8">
                  <input type="text" name="firstname" placeholder="First Name" value="<?php echo isset($vendor['vender_first_name'])?$vendor['vender_first_name']:'';?>" class="form-control">
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Last Name</label>
                <div class="col-lg-8">
                  <input type="text" name="lastname" placeholder="Last Name" value="<?php echo isset($vendor['vender_last_name'])?$vendor['vender_last_name']:'';?>" class="form-control">
                   <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Email</label>
                <div class="col-lg-8">
                  <input type="text" name="email_id" placeholder="test@example.com" value="<?php echo isset($vendor['email_id'])?$vendor['email_id']:'';?>" class="form-control">
                  
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Telephone</label>
                <div class="col-lg-5">
                  <input type="text" name="contact_no" value="<?php echo isset($vendor['contact_no'])?$vendor['contact_no']:'';?>" class="form-control ">
                </div>
                
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Fax-No</label>
                <div class="col-lg-8">
                  <input type="text" name="faxno"  value="<?php echo isset($vendor['fax_no'])?$vendor['fax_no']:'';?>" class="form-control">
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Address</label>
                <div class="col-lg-8">
                  <textarea name="address"  cols="45" rows="2" value="" class="form-control"><?php echo isset($vendor['address'])?$vendor['address']:'';?></textarea>
                </div>
              </div>
              
               <?php $country= $obj_vendor_info->getCountryList(); //printr($country);
              // printr($country);  ?>
               <div class="form-group">
                 <label class="col-lg-3 control-label">Country</label>
                  <div class="col-lg-4">
                <select class="form-control" name="country_name">
                  <option>Select Country Name</option>
                  
                   <?php foreach($country as $value)
						   {	
						   if($vendor['country']==$value['country_id'])
						   {?>
                   
                                <option value="<?php echo $value['country_id']; ?>" selected="selected"><?php echo $value['country_name']; ?>
                                <?php } else { ?>
					 		<option value="<?php echo $value['country_id']; ?>"> <?php echo $value['country_name']; ?></option>
				 			  <?php }
				   			 }
				  ?>
                 
                  </select>
                </div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">State</label>
                <div class="col-lg-4">
                  <input type="text" name="state" value="<?php echo isset($vendor['state'])?$vendor['state']:'';?>" class="form-control">
                </div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">City</label>
                <div class="col-lg-4">
                  <input type="text" name="city" value="<?php echo isset($vendor['city'])?$vendor['city']:'';?>" class="form-control">
                </div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Postcode</label>
                <div class="col-lg-4">
                  <input type="text" name="postcode" value="<?php echo isset($vendor['postcode'])?$vendor['postcode']:'';?>" class="form-control">
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Remark</label>
               <div class="col-lg-8">
                  <textarea name="remark"  cols="45" rows="2" value="" class="form-control"><?php echo isset($vendor['remark'])?$vendor['remark']:'';?></textarea>
               </div> 
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Bank Detail</label>
               <div class="col-lg-8">
                <textarea name="bankdetail" cols="45" rows="3" value="" class="form-control"><?php echo isset($vendor['bank_detail'])?$vendor['bank_detail']:'';?></textarea>
                </div> 
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($vendor['status']) && $vendor['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($vendor['status']) && $vendor['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
             <!-- end of modification-->
             
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
<style type="text/css">
.btn-on.active {
    background: none repeat scroll 0 0 #3fcf7f;
}
.btn-off.active{
	background: none repeat scroll 0 0 #3fcf7f;
	border: 1px solid #767676;
	color: #fff;
}

</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>ckeditor/ckeditor.js"></script>
<style type="text/css">
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
</style>
<script>	
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		CKEDITOR.replace('email_signature');
    });
</script>
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>