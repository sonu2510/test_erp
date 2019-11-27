<?php

//	print_r('New Server');//die;
// Start: Building System
include_once("../ps-config.php"); 
// End: Building System
include('model/user.php');
$obj_user = new user;

if(isset($_SESSION['history_id'])){
	//printr($_SESSION['history_id']);die;
	$obj_user->insertDuration($_SESSION['history_id']);
	unset($_SESSION['history_id']);					
}

if(is_loginAdmin()){
//	printr(is_loginAdmin);die;
	page_redirect(HTTP_ADMIN.'index.php?rout=dashboard');
}

$error = '';
if(isset($_POST['btn_submit'])){
	$user_name = $_POST['user_name'];
	$password = $_POST['password'];
	if($user_name && $password){
		
		$data = $obj_user->checkUserNamePassword($user_name,$password);
		
		if($data){
			
			
			
			if($data['status'] == 1){
				
				$getuser = $obj_user->getUser($data['user_type_id'],$data['user_id']);
				
				$obj_session->data['token'] = md5(mt_rand());
				if($data['user_type_id'] == 1){
					$obj_session->data['ADMIN_LOGIN_SWISS'] = $data['user_id'];
					$obj_session->data['DEPARTMENT'] = $data['department'];
				}elseif($data['user_type_id'] == 2){
					$obj_session->data['ADMIN_LOGIN_SWISS'] = $data['employee_id'];
				}elseif($data['user_type_id'] == 3){
					$obj_session->data['ADMIN_LOGIN_SWISS'] = $data['client_id'];
				}elseif($data['user_type_id'] == 4){
					$obj_session->data['ADMIN_LOGIN_SWISS'] = $data['international_branch_id'];
				}elseif($data['user_type_id'] == 5){
					$obj_session->data['ADMIN_LOGIN_SWISS'] = $data['associate_id'];
				}
				$obj_session->data['ADMIN_LOGIN_NAME'] = $data['first_name'].' '.$data['last_name'];
				$obj_session->data['ADMIN_LOGIN_EMAIL_SWISS']  = $data['email'];
				$obj_session->data['LOGIN_USER_TYPE'] = $data['user_type_id'];
				$obj_session->data['ADMIN_LOGIN_USER_TYPE'] = isset($data['admin_type_id'])?$data['admin_type_id']:'';
				$obj_session->data['ADMIN_LOGIN_USER'] = $data['user_name'];
				$obj_session->data['USER_COUNTRY'] = $getuser['country_id'];
				$obj_session->data['last_login_timestamp']=time();
				
//echo 'dsf';die;
				$obj_session->data['show_warning']=1;
				page_redirect(HTTP_ADMIN.'index.php?rout=dashboard');
				
			}else{
				$obj_session->data['warning'] = 'Your account is inactive!';
			}
		}else{
			$obj_session->data['warning'] = 'Wrong user name and password!';
		}
	}else{
		$obj_session->data['warning'] = 'Please enter user name and password !';
	}
}
/*?>
<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from flatfull.com/themes/first/signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 29 Jun 2014 06:17:17 GMT -->
<head>
<meta charset="utf-8">
<title>Swiss ERP</title>
<meta name="description" content="mobile first, app, web app, responsive, admin dashboard, flat, flat ui">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/font.css">
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/app.v2.css" type="text/css" />
<!--[if lt IE 9]> <script src="<?php echo HTTP_SERVER;?>js/ie/respond.min.js"></script> <script src="<?php echo HTTP_SERVER;?>js/ie/html5.js"></script> <![endif]-->
</head>
<body>
<!-- header -->
<header id="header" class="navbar bg bg-black">
	<a class="navbar-brand" href="javascript:void(0);">Swiss ERP</a>
</header>
<!-- / header -->
*/ ?>
<?php include('common/header.php');?>
<section id="content">
  <div class="main padder">
    <div class="row">
      <div class="col-lg-4 col-lg-offset-4 m-t-large">
        <section class="panel">
          <header class="panel-heading text-center"> Sign in </header>
          <?php /*if($error){ ?>	
              <div class="alert alert-danger">
                <button data-dismiss="alert" class="close" type="button"><i class="fa fa-times"></i></button>
                <strong style="font-size:14px;"><?php echo $error;?></strong>
             </div>
         <?php }*/ ?>
          
          <form name="frm_signin" id="frm_signin" class="panel-body" method="post">
            <div class="block">
              <label class="control-label">Username</label>
              <input type="text" name="user_name" id="user_name" placeholder="User Name" class="form-control validate[required]" autofocus>
            </div>
            <div class="block">
              <label class="control-label">Password</label>
              <input type="password" id="password" name="password" placeholder="Password" class="form-control validate[required]">
            </div>
            <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal" class="pull-right m-t-mini"><small>Forgot password?</small></a>
            <button type="submit" name="btn_submit" id="btn_submit" class="btn btn-info">Sign in</button>
          </form>
        </section>
      </div>
    </div>
  </div>
</section>


<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Forgot Password?</h4>
      </div>
      <div class="modal-body" style="height:70px;">        
        <div class="form-group">
           <label class="col-lg-3 control-label"><span class="required">*</span> User Name:</label>
           <div class="col-lg-4">
              <input type="text" name="forgot_user_name" id="forgot-user-name" class="form-control validate[required]" />
           </div>
        </div> 
      </div>
      <div class="modal-footer">
        <button type="button" id="continue-btn" class="btn btn-primary">Continue</button>
      </div>
    </div>
  </div>
</div>


<!-- app --> <script src="<?php echo HTTP_SERVER;?>js/app.v2.js"></script>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
        jQuery("#frm_signin").validationEngine();
		jQuery(".modal-body").validationEngine();
		
    }); 
	
	$('#continue-btn').click(function(){
		
		var user_name = $('#forgot-user-name').val();
		$('#loading').show();
		 
		$.ajax({
			url : 'ajax/forgotEmail.php',
			type: 'post',
			data: {user_name:user_name},
			success: function(data){
				
				$('#loading').remove();
				if(data==1){	
					window.location='verification.php';	
				}else{
					$('#myModal').modal('toggle');
					set_alert_message('No Matched For Username',"alert-warning","fa-warning");	
				}
			}
		});
	});
	
</script> 
<!-- Close : validation script -->

<?php include('common/footer.php');?>