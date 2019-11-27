<?php
// Start: Building System
include_once("../ps-config.php");
// End: Building System

if(is_loginAdmin()){
	
	//start : Building genreral function 
	include('general.php');
	$obj_general = new general;
	//close
	

	$route = "dashboard";
	$mod = "index";

	$include_page = 0;

	if(isset($_GET['route']) && !empty($_GET['route']) && file_exists('controller/'.$_GET['route'])){
		$route = $_GET['route'];
	}elseif(isset($_GET['route']) && !empty($_GET['route']) && !file_exists('controller/'.$_GET['route']) && file_exists('controller/'.$_GET['route'].'.php') ){
		$include_page = 1;
		$route = $_GET['route'].'.php';
		$mod = '';
	}
	//echo $route;die;
	if(isset($_GET['mod']) && !empty($_GET['mod']) && file_exists("controller/".$route."/".$_GET['mod'].".php")){
		$mod = $_GET['mod'];
	}
	

	$mod = $mod.'.php';
	//echo $rout."===".$mod;
	
	if($include_page){
		include_once(DIR_ADMIN."/".include_controller_page($route));
	}else{
		include_once(DIR_ADMIN."/".dispaly_include_page($route,$mod));
	}

}else {
	// IF user not logged in : Include Login.php from Views
	//include_once(DIR_ADMIN . "signin.php");
	page_redirect(HTTP_ADMIN.'signin.php');
}

?>
