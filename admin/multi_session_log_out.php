<?php
//session_start();

//print_r($_POST);die;
include_once("../ps-config.php");
include('model/employee.php');
$obj_emp = new employee;

include('model/user.php');
$obj_user = new user;

//$user_data=$obj_emp->getUserData(($_POST['user_type_id']),($_POST['user_id']));
//echo 'kinjal';
//print_r($user_data);print_r($_GET);die;
date_default_timezone_set("Asia/Kolkata");

if(isset($_SESSION['ADMIN_LOGIN_SWISS'])){
	
	$data = $obj_user->insertDuration($_SESSION['history_id']);

	unset($_SESSION['ADMIN_LOGIN_SWISS']);
	unset($_SESSION['ADMIN_LOGIN_NAME']);
	unset($_SESSION['ADMIN_LOGIN_EMAIL_SWISS']);
	unset($_SESSION['LOGIN_USER_TYPE']);
	unset($_SESSION['history_id']);
	
	if(isset($_SESSION['DEPARTMENT'])){
		unset($_SESSION['DEPARTMENT']);
	}
}
session_destroy();
//echo HTTP_ADMIN.'multi_session.php&user_name='.encode($_POST['user_name']).'&pw='.encode($_POST['password']);
page_redirect(HTTP_ADMIN.'multi_session.php?&user_name='.$_POST['user_name_multi_session'].'&pw='.$_POST['password']);

?>