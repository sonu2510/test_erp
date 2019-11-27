<?php
// Start: Building System
/*echo "<h1>Cpanel Details</h1>";
echo "UserName: coffeeru"."<br/>";
echo 'PassWord: 2.,Jvi$yfP-)';
die;*/
//printr($_GET);die;
include_once("../ps-config.php");
// End: Building System
date_default_timezone_set("Asia/Kolkata");
//Start : define variable use only in admin side
define('UPLOAD_PATH',DIR_ADMIN.'upload');
//Close : define variable use only in admin side

/*
* Destroys sessions if GET "sd" parameter is set.
*/ 
if(isset($_GET['sd'])){
	session_destroy();
	header('Location: index.php');
	exit;
}

if(is_loginAdmin()){
	
	//start : Building genreral function 
	include('general.php');
	$obj_general = new general;
	//close
	
	//Get Store Settings
	$settings = $obj_general->getAllSettings();
	$setting_data= unserialize($settings['setting_details']);
	//printr($setting_data);//die;
	//Genral Settings 
	/*define('ADMIN_EMAIL','info@gmail.com');
	define('NOTIFIY_EMAIL','');
	define('DATE_FORMATE_NUBER',2);*/
	
	define('STORE_NAME',$setting_data['store_name']);
	define('STORE_LOGO',$setting_data['store_logo']);
	define('LISTING_LIMIT',$setting_data['items_per_page']);
	define('ADMIN_EMAIL',$setting_data['email_address']);
	define('SYSTEM_LOCK',$setting_data['options']);
	define('META_TITLE',$setting_data['meta_title']);
	define('LOCK_MESSAGE',$setting_data['lock_message']);
	
	$route = "dashboard";
	$mod = "index";

	$include_page = 0;

	if(isset($_GET['route']) && !empty($_GET['route']) && file_exists('controller/'.$_GET['route'])){
		$route = $_GET['route'];
	}elseif(isset($_GET['route']) && !empty($_GET['route']) && !file_exists('controller/'.$_GET['route']) && file_exists('controller/'.$_GET['route'].'.php') ){
		$include_page = 1;
		$route = $_GET['route'].'.php';
		$mod = '';
	}elseif(isset($_GET['route']) && !empty($_GET['route']) && $_GET['route'] = 'storeadmin'){
		page_redirect(HTTP_SERVER.'storeadmin');
	}
	
	//echo $route;die;
	if(isset($_GET['mod']) && !empty($_GET['mod']) && file_exists("controller/".$route."/".$_GET['mod'].".php")){
		$mod = $_GET['mod'];
	}	

	$mod = $mod.'.php';
	//echo $rout."===".$mod;
	
	// Start: Building Theme
	include_once(DIR_ADMIN . "common/header.php");
	
	if($obj_session->data['LOGIN_USER_TYPE']!=1 && $obj_session->data['ADMIN_LOGIN_SWISS']!=1 && SYSTEM_LOCK){
		//page_redirect(HTTP_SERVER.'404.php');
		include(DIR_SERVER . "404.php");
		exit;
	}
	
	include_once(DIR_ADMIN . "common/left_menu.php");
	// Close : Building Theme
	//printr($include_page);
	if($include_page){
		include_once(DIR_ADMIN."/".include_controller_page($route));
		//echo DIR_ADMIN."/".include_controller_page($route);die;
	}else{
		include_once(DIR_ADMIN."/".dispaly_include_page($route,$mod));
		//echo DIR_ADMIN."/".dispaly_include_page($route,$mod);die;
	}
	include_once(DIR_ADMIN . "common/footer.php");
	// End: building Theme

}else {
	// IF user not logged in : Include Login.php from Views
	//include_once(DIR_ADMIN . "signin.php");
	
	//printr($_SERVER['REMOTE_ADDR']);
	include('general.php');
		$obj_general = new general;
	
	/*if(isset($_GET['arr']))
	{
		$data = $obj_general->send_store_invoice_mail($_SESSION['post_data']);
		page_redirect('http://store.pouchmakers.com');
	}*/
	//printr($_GET);die;
	if(($_POST || $_FILES) && $_GET['route']=='')
	{
		
		//include('general.php');
		//$obj_general = new general;
		$file='';
		if(isset($_FILES) && !empty($_FILES))
		{
		    include(DIR_ADMIN.'upload_resume.php');
			$file=$_FILES;
		}
//die;
		//make variable referer to save posting url [kinjal : 1_12_2015 Tue]
		$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		
		//Call Insert Query For Save Domain Enquiry Data[26_11_2015(Thurs)]
		$data = $obj_general->add_domaindata($_POST,$referer,$file);
		//printr($data);
		//die;
		
		page_redirect('http://'.$_POST['domain_name'].'/'.$_POST['thanks_url']);
	}
	
	page_redirect(HTTP_ADMIN.'signin.php');
}

/*echo "<pre>";
print_r($_SERVER);die;*/
//common
/*include('common/header.php');
include('common/left_menu.php');

//controller

if(isset($_GET['rout']) && !empty($_GET['rout']) && file_exists('controller/'.$_GET['rout'].'.php')){
	include('controller/'.$_GET['rout'].'.php');
}else{
	include('controller/dashboard.php');
}

//common
include('common/footer.php');*/
?>