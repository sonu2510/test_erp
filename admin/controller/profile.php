<?php
include('model/user.php');
$obj_user = new user;

if (!$obj_general->isLogged()) {
	$session->data['redirect'] = $obj_general->link('controller/profile', '', '',1);

	page_redirect('signin.php');
}else{
	$loginInfo = $obj_general->isLogged();
	//printr($loginInfo);die;
	$user_type_id = $loginInfo['user_type_id'];
	$user_id = $loginInfo['user_id'];
}

$user = $obj_user->getUser($user_type_id,$user_id);
//printr($user);die;
if(isset($_POST['btn_submit'])){
	$post = post($_POST);//printr($_FILES);//die;
	$chckUserName = $obj_general->uniqUserName($post['user_name'],$user_id,$user_type_id);
	
	if($chckUserName){
		//printr($_FILES['profile_image']);die;
		$obj_user->updateProfile($user_type_id,$user_id,$post);
		if(isset($_FILES['profile_image']) && !empty($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0){
			if($user_type_id == 1 && $user_id == 1) {
			//echo "1 1";
				$obj_user->uploadProfileImage($user_type_id,$user_id,$_FILES);
			}else{//echo "00";
				$obj_user->uploadLogoImage($user_type_id,$user_id,$_FILES['profile_image']);
			}
			//die;
		}
		$_SESSION['success'] = 'Your profile update successfully !';
		page_redirect($obj_general->link('profile', '', '',1));
	}else{
		$_SESSION['warning'] = 'User name exist!';
		page_redirect($obj_general->link('profile', '', '',1));
	}
}
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-user"></i> Profile</h4>
    </div>
    <div class="row">
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> Profile Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <?php if($user_type_id == 1 && $user_id == 1) {?>
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Profile Image</label>
                    <div class="col-lg-9 media">
                      <div class="bg-light pull-left text-center media-large thumb-large">
                            <?php
                            $pimage = $obj_general->getUserProfileImage($user_type_id,$user_id,'200_');
                            ?>
                            <img src="<?php echo $pimage;?>" class="img-rounded" alt="<?php echo $user['first_name'];?>">
                            
                      </div>
                      <div class="media-body">
                        <input type="file" name="profile_image" id="profile_image" title="Change" class="btn btn-sm btn-info m-b-small">
                        <br>
                        <button type="button" class="btn btn-sm btn-default">Delete</button>
                      </div>
                    </div>
                  </div>
              <?php } else{ ?>
              		<div class="form-group">
                        <label class="col-lg-3 control-label">Logo</label>
                        <div class="col-lg-9 media">
                          <div class="bg-light pull-left text-center media-large thumb-large">
                                <?php
								$upload_path = DIR_UPLOAD.'admin/logo/';
								$http_upload = HTTP_UPLOAD.'admin/logo/';
								if(isset($user['logo']) && $user['logo'] != '' && file_exists($upload_path.$user['logo'])){
									$logo = $http_upload.'200x200_'.$user['logo'];
								}else{
									$logo = HTTP_SERVER.'images/blank-user64x64.png';
								}
								?>
								<img src="<?php echo $logo;?>" alt="<?php echo $user['first_name'];?>">
                          </div>
                          <div class="media-body">
                            <input type="file" name="profile_image" title="Change" class="btn btn-sm btn-info m-b-small">
                            <br>
                            <button type="button" class="btn btn-sm btn-default">Delete</button>
                          </div>
                        </div>
                      </div>   
                <?php } ?>      
              
              <div class="form-group">
                <label class="col-lg-3 control-label">First Name</label>
                <div class="col-lg-8">
                  <input type="text" name="first_name" id="first_name" value="<?php echo $user['first_name'];?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Last Name</label>
                <div class="col-lg-8">
                  <input type="text" name="last_name" id="last_name" value="<?php echo $user['last_name'];?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Email</label>
                <div class="col-lg-8">
                  <input type="text" name="email" placeholder="test@example.com" value="<?php echo isset($user['email'])?$user['email']:'';?>" class="form-control validate[required,custom[email]]">
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
             <div class="form-group">
                <label class="col-lg-3 control-label">Username</label>
                <div class="col-lg-8">
                  <input type="text" name="user_name" placeholder="Username" value="<?php echo isset($user['user_name'])?$user['user_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Password</label>
                <div class="col-lg-8">
                  <input type="password" name="password" id="password" value="" placeholder="Password" class="form-control">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Conform Password</label>
                <div class="col-lg-8">
                  <input type="password" name="con_password" id="con_password" value="" placeholder="Conform Password" class="form-control validate[equals[password]]">
                </div>
              </div>
              
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
                <div class="col-lg-8">
                  <input type="text" name="address" value="<?php echo isset($user['address'])?$user['address']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Postcode</label>
                <div class="col-lg-8">
                  <input type="text" name="postcode" value="<?php echo isset($user['postcode'])?$user['postcode']:'';?>" class="form-control validate[required,custom[onlyNumberSp],maxSize[6]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                <div class="col-lg-8">
                  <input type="text" name="city" value="<?php echo isset($user['city'])?$user['city']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                <div class="col-lg-8">
                  <input type="text" name="state" value="<?php echo isset($user['state'])?$user['state']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                <div class="col-lg-8">
                	<?php 
					$sel_country = (isset($user['country_id']))?$user['country_id']:'';
					$countrys = $obj_general->getCountryCombo($sel_country);
					echo $countrys;
					?>
                    <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
                  <div class="form-group">
                <label class="col-lg-3 control-label">Email Signature</label>
                <div class="col-lg-8">
                  <textarea name="email_signature" class="form-control"><?php echo isset($user['email_signature'])?$user['email_signature']:'';?></textarea>
                </div>
              </div>
             	<div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                  <button type="submit" name="btn_submit" id="btn_submit" class="btn btn-primary">Update changes</button>
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
		
		$("#form").submit(function( event ) {
			if($('#profile_image').val().length > 0 ){
				var ext = $('#profile_image').val().split('.').pop().toLowerCase();
				var data = validateUploadImage($('#profile_image').val());
				if(data.length > 1){
					set_alert_message(data,"alert-warning","fa-warning");
					return false;
				}
			}
		});
		
    });
</script> 
<!-- Close : validation script -->