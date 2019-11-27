<?php
//session_start();

include_once("../ps-config.php");

date_default_timezone_set("Asia/Kolkata");

if(isset($_SESSION['ADMIN_LOGIN_SWISS'])){
	include('model/user.php');
	$obj_user = new user;
//	printr($_SESSION);
	if(isset($_SESSION['history_id']) && !empty($_SESSION['history_id'])) 
	{
		$data = $obj_user->insertDuration($_SESSION['history_id']);
	}
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
header('location:signin.php');
?>
