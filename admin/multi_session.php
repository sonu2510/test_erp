<?php
//session_start();
//print_r($_GET);//die;
include_once("../ps-config.php");
include('model/user.php');
$obj_user = new user;
include_once("../ps-config.php");
if(isset($_SESSION['history_id'])){
	//printr($_SESSION['history_id']);die;
	$obj_user->insertDuration($_SESSION['history_id']);
	unset($_SESSION['history_id']);					
}

if(is_loginAdmin()){
	//printr(is_loginAdmin);die;
	page_redirect(HTTP_ADMIN.'index.php?rout=dashboard');
}

$error = '';
if(isset($_GET['user_name']) && isset($_GET['pw']))
{
	$user_name = decode($_GET['user_name']);
	$password = decode($_GET['pw']);
	//print_r($_POST);die;
	if($user_name && $password){
		
		$data = $obj_user->checkUserNamePassword($user_name,$password);
		//printr($data);die;
		if($data){
			//printr($data);die;
			if($data['status'] == 1){
				
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
?>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>


<script type="text/javascript">
	//alert("fgdfgdfgdgdfg");
	document.getElementById('frm_signin').submit();

	$("#btn_submit").click();
</script>
<?php include('common/footer.php');?>