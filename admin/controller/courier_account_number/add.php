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
if(isset($_GET['courier_account_number_id']) && !empty($_GET['courier_account_number_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$courier_account_number_id = base64_decode($_GET['courier_account_number_id']);
		$courier_account_details = $obj_courier_account_number->getcourier_account_detail($courier_account_number_id);
	//	printr($courier_account_details);
	
		//printr($courier_data);
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
		$insert_id = $obj_courier_account_number->addCourier($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		
		$courier_account_number_id = $courier_account_details['courier_account_number_id'];
		$obj_courier_account_number->updateCourier($courier_account_number_id,$post);
		//die; 
		$obj_session->data['success'] = UPDATE;
		if(isset($obj_session->data['page'])){
			$pageString = '&page='.$obj_session->data['page'];
			unset($obj_session->data['page']);
		}else{
			$pageString = '';
		} 
		page_redirect($obj_general->link($rout, $pageString.'&filter_edit='.$_GET['filter_edit'], '',1));
	}
	
		$courier_data = $obj_courier_account_number->getCouriersList();
		$country_data = $obj_courier_account_number->getCountry();
		$userlist = $obj_courier_account_number->getIBList();
	
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
                <label class="col-lg-3 control-label"><span class="required">*</span>Country Name </label>
                <div class="col-lg-4">
                  <select name="country_id" id="country_id" class="form-control" 
              
                  	<?php
                  	 
                  	
                  	foreach($country_data as $country_name) { ?>
                    	<?php if($courier_account_details['country_id']==$country_name['country_id']) { ?>
	                   	 	<option value="<?php echo $country_name['country_id']; ?>" selected="selected"><?php echo $country_name['country_name']; ?></option>
                         <?php } else { ?>
                         	<option value="<?php echo $country_name['country_id']; ?>"><?php echo $country_name['country_name']; ?></option>
                         <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
							<label class="col-lg-3 control-label"><span class="required">*</span>IB User</label>
							<div class="col-lg-3">
						
								<select class="form-control validate[required]" name="admin_user_id"  id="admin_user_id" >
									<option value="">Select User</option>
									<?php foreach($userlist as $user) { 								
							        	 if($courier_account_details['admin_user_id']==$user['international_branch_id']) { ?>
                    	                   	 	<option value="<?php echo $user['international_branch_id']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
                                             <?php } else { ?>
                                             <option value="<?php echo $user['international_branch_id']; ?>"><?php echo $user['user_name']; ?></option>
                    											
									<?php	}} ?>                                       
								</select>
							</div>
						  </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Courier</label>
                <div class="col-lg-4">
                  <select name="courier_id" id="courier_id" class="form-control" <?php if(isset($courier_account_details['default_courier_id'])) { echo "disabled=disabled"; }?> >
                  	<?php foreach($courier_data as $courier_name) { ?>
                    	<?php if($courier_account_details['courier_id']==$courier_name['courier_id']) { ?>
	                   	 	<option value="<?php echo $courier_name['courier_id'].'='.$courier_name['courier_name']; ?>" selected="selected"><?php echo $courier_name['courier_name']; ?></option>
                         <?php } else { ?>
                         	<option value="<?php echo $courier_name['courier_id'].'='.$courier_name['courier_name'];?>"><?php echo $courier_name['courier_name']; ?></option>
                         <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div> 
              <div class="form-group">
                <label class="col-lg-3 control-label">Account Number</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control" name="account_number" value="<?php echo isset($courier_account_details['account_number']) ? $courier_account_details['account_number'] : ''; ?> " />
                </div>
              </div> 
             <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($courier_account_details['status']) && $courier_account_details['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($courier_account_details['status']) && $courier_account_details['status'] == 0)?'selected':'';?>> Inactive</option>
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