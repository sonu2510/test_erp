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
$image_path = HTTP_SERVER.'images/blank-user64x64.png';

$admin_login_status = 1;
if(isset($_GET['setting_id']) && !empty($_GET['setting_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$setting_id = base64_decode($_GET['setting_id']);
		$settings = $obj_general_setting->getAllSettings();
		$setting_data= unserialize($settings['setting_details']);
		$edit = 1;
		
		$upload_path = DIR_UPLOAD.'admin/slogo/';
		if(file_exists($upload_path.$setting_data['store_logo'])){
			$image_path = HTTP_UPLOAD.'admin/slogo/'.$setting_data['store_logo'];
		}
		$admin_login_status = $setting_data['options'];
		//echo $setting_data['store_logo'];die;
		//printr($setting_data);die;
	}
	
}else{
	
	$image_path = '';
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
		
		if(isset($_FILES['store_logo']) && !empty($_FILES['store_logo']) && $_FILES['store_logo']['error'] == 0){
			$logo_name = $obj_general_setting->uploadLogoImage($_FILES['store_logo']);
			$post['store_logo'] = $logo_name;	
		}else{	
			$post['store_logo'] = '';
		}
		$serialize_data = serialize($post);
		//print_r($post)."==".print_r($_FILES);die;
		$insert_id = $obj_general_setting->addSetting($serialize_data);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		
		
		$post = post($_POST);
		//printr($post);die;
		if(isset($_FILES['store_logo']) && !empty($_FILES['store_logo']) && $_FILES['store_logo']['error'] == 0){
			$logo_name = $obj_general_setting->uploadLogoImage($_FILES['store_logo']);
			$post['store_logo'] = $logo_name;	
		}else{	
			$post['store_logo'] = $setting_data['store_logo'];	
		}
		
		if(!isset($post['options'])) {
			$post['options'] = $admin_login_status;
		}
		$serialize_data = serialize($post);
		//print_r($serialize_data);die;
		$update_id = $obj_general_setting->updateSetting($serialize_data,$_GET['setting_id']);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
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
            	
                
                
                <section class="panel">
                  <header class="panel-heading">
                    <ul class="nav nav-tabs nav-justified">
                      <li class="active"><a data-toggle="tab" href="#general">General</a></li>
                      <li class=""><a data-toggle="tab" href="#store">Store</a></li>
                    </ul>
                  </header>
                  <div class="panel-body">
                    <div class="tab-content">
                      <div id="general" class="tab-pane active">
                      		<div class="form-group">
                             <label class="col-lg-4 control-label"><span class="required">*</span>Default Email Address</label>
                             <div class="col-lg-4">
                                <input type="text" name="email_address" value="<?php echo isset($setting_data['email_address']) ? $setting_data['email_address'] : ''; ?>" class="form-control validate[required]">
                             </div>
                            </div>
                            
                            <div class="form-group">
                               <label class="col-lg-4 control-label"><span class="required">*</span>Default Items Per Page</label>
                               <div class="col-lg-4">
                                   <input type="text" name="items_per_page" value="<?php echo isset($setting_data['items_per_page']) ? $setting_data['items_per_page'] : ''; ?>" " class="form-control validate[required]">
                               </div>
                            </div>
                
                         <?php /* ?>
							<div class="form-group">
                            <label class="col-lg-4 control-label"><span class="required">*</span>Show Limits</label>
                            <div class="col-lg-6">
                               <div class="pillbox clearfix m-b" id="MyPillbox1">
                                    <ul>
                                        <?php if(isset($setting_data['item_option'])) { ?>
                                            <?php foreach($setting_data['item_option'] as $display) { ?>
                                                <input type="hidden" name="item_option[]" value="<?php echo $display; ?>">
                                                <li class="label bg-info"><?php echo $display; ?></li>
                                            <?php } ?>
                                        <?php } ?>
                                       <input type="text" placeholder="add filter limit">
                                   </ul>
                               </div>                    
                            </div>
                          </div>
                          <?php */ ?>
                          
                          <div class="form-group">
                            <label class="col-lg-4 control-label"><span class="required">*</span>System Lock?</label>
                            <div class="col-lg-4">
                              <div data-toggle="buttons" class="btn-group m-t-n-mini m-r-n-mini"> 
                                
                                <label class="btn btn-sm btn-white <?php echo ($admin_login_status==1) ? 'btn-on active' : '';?>"><input type="radio" id="option1" name="options" value="1"> ON </label> 
                                <label class="btn btn-sm btn-white <?php echo ($admin_login_status==0) ? 'btn-on active' : '';?>"> <input type="radio" id="option2" name="options" value="0"> OFF </label> 
                              </div>
                            </div>
                         </div>
              			
              			 
                         <div class="form-group">
                               <label class="col-lg-4 control-label">Lock Message</label>
                               <div class="col-lg-4">
                               	   <textarea rows="3" cols="10" class="form-control" name="lock_message"><?php echo isset($setting_data['lock_message']) ? $setting_data['lock_message'] : ''; ?></textarea>                                  
                               </div>
                         </div>
                         
                         <div class="form-group">
                               <label class="col-lg-4 control-label">Backup Period</label>
                               <div class="col-lg-4">
                               	   <select class="form-control" name="backup_period">
                                   		
                                   		<option value="5" selected="selected" <?php echo isset($setting_data['backup_period']) && $setting_data['backup_period']=='5' ? 'selected=selected' : ''; ?> >5days</option>
                                        <option value="7" <?php echo $setting_data['backup_period']=='7' ? 'selected=selected' : ''; ?>>7days</option>
                                        <option value="10" <?php echo isset($setting_data['backup_period']) && $setting_data['backup_period']=='10' ? 'selected=selected' : ''; ?>>10days</option>
                                   </select>
                               </div>
                            </div>
                      
                      </div>
                      
                      <div id="store" class="tab-pane">
                              <div class="form-group">
                                 <label class="col-lg-4 control-label"><span class="required">*</span>Store Name</label>
                                 <div class="col-lg-4">
                                      <input type="text" name="store_name" value="<?php echo isset($setting_data['store_name']) ? $setting_data['store_name'] : ''; ?>" class="form-control validate[required]">
                                 </div>
                              </div>
                          
                            <div class="form-group">
                               <label class="col-lg-4 control-label">Logo</label>
                               <div class="col-lg-8 media">
                                 <div class="bg-light pull-left text-center media-large thumb-large">
                                        <?php
                                            //$pimage = $obj_general->getUserProfileImage($user_type_id,$user_id,'200x200_');
                                        ?>
                                        <img src="<?php echo $image_path; ?>" alt="">
                                        <!--<i class="fa fa-user inline fa fa-light fa fa-3x m-t-large m-b-large"></i>-->
                                 </div>
                                 <div class="media-body">
                                    <input type="file" name="store_logo" title="Change" class="btn btn-sm btn-info m-b-small">
                                    <br>
                                    <button type="button" class="btn btn-sm btn-default">Delete</button>
                                 </div>
                              </div>
                            </div>
                            
                            <div class="form-group">
                                 <label class="col-lg-4 control-label"><span class="required">*</span>Meta Title</label>
                                 <div class="col-lg-4">
                                      <input type="text" name="meta_title" value="<?php echo isset($setting_data['meta_title']) ? $setting_data['meta_title'] : ''; ?>" class="form-control validate[required]">
                                 </div>
                            </div>
                            
                            
                            <div class="form-group">
                               <label class="col-lg-4 control-label">Meta Description</label>
                               <div class="col-lg-4">
                               	   <textarea rows="3" cols="10" class="form-control" name="meta_description"><?php echo isset($setting_data['meta_description']) ? $setting_data['meta_description'] : ''; ?></textarea>                                  
                               </div>
                            </div>
                            
                             
                      </div>
                     
                    </div>
                  </div>
               </section>
  
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