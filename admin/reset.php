<?php
// Start: Building System
include_once("../ps-config.php");
// End: Building System
include('model/user.php');

if(!isset($_SESSION['account_master_id'])){
	page_redirect('signin.php');	
}

$obj_user = new user;

$error = '';
if(isset($_POST['btn_submit'])){
	$new_password = $_POST['new_password'];
	$confirm_password = $_POST['confirm_password'];
	$salt = substr(md5(uniqid(rand(), true)), 0, 9);
	
	
	$data=$obj_user->resetpassword($new_password,$confirm_password,$salt,$_SESSION['account_master_id']);
/*	$sql = "UPDATE `" . DB_PREFIX . "account_master` SET salt = '" . $salt . "', password = '" . sha1($salt . sha1($salt . sha1($new_password))) . "' , password_text = '" .$new_password. "', date_modify = NOW() WHERE account_master_id = '".$_SESSION['account_master_id']."' ";
	printr($sql);die;

	$this->query($sql);
	printr('vjvbfwjheb');die;
	$this->query("DELETE FROM `" . DB_PREFIX . "forgot_password WHERE account_master_id='".$_SESSION['account_master_id']."'");*/
	
	
	
	$_SESSION['success'] = 'Password Updated!';
	unset($_SESSION['account_master_id']);
	page_redirect('signin.php');
}


?>
<?php include('common/header.php');?>
<section id="content">
  <div class="main padder">
    <div class="row">
      <div class="col-lg-4 col-lg-offset-4 m-t-large">
        <section class="panel">
          <header class="panel-heading text-center"> Reset Password </header>
          
          <form name="frm_reset" id="frm_reset" class="panel-body" method="post">
            <div class="block">
              <label class="control-label">New Password</label>
              <input type="password" name="new_password" placeholder="Password" class="form-control validate[required]">
            </div>
            <div class="block">
              <label class="control-label">Confirm Password</label>
              <input type="password" name="confirm_password" placeholder="Password" class="form-control validate[required]">
            </div>

            <button type="submit" name="btn_submit" class="btn btn-info">Sumbit</button>
          </form>
        </section>
      </div>
    </div>
  </div>
</section>



<!-- app --> <script src="<?php echo HTTP_SERVER;?>js/app.v2.js"></script>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
        jQuery("#frm_reset").validationEngine();
    });
</script> 
<!-- Close : validation script -->

<?php include('common/footer.php');?>