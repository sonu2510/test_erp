<?php
// Start: Building System
include("mode_setting.php");
$fun = $_GET['fun'];
// End: Building System
if($_GET['fun']=='UserNameAlreadyExsist') {
if(isset($_POST['name']) && $_POST['name'] != ''){
	$user_name = $_POST['name'];
	//echo $material_name;die;
	$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "account_master` WHERE user_name = '".$user_name."'";
	$data = $obj_db->query($sql);
	//echo $data->row['total'];die;
	if($data->row['total'] > 0){
		echo 1;
	}else{
		echo 0;
	}
}else{
	echo 0;
}
}
?>