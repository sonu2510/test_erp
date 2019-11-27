<?php
// Start: Building System
include_once("../../ps-config.php");


if(isset($_POST['verification_code']) && !empty($_POST['verification_code'])){
	
	$sql = "SELECT * FROM `" . DB_PREFIX . "forgot_password` WHERE verification_code = '".$_POST['verification_code']."'";
	$data = $obj_db->query($sql);
	if($data->row){
		$_SESSION['account_master_id'] = $data->row['account_master_id']; 
		echo 1;
	}else{
		echo 0;	
	}
}else{
	echo 0;
}





die;

?>