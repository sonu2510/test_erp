<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();
if($_GET['fun']=='updateCourierStatus') {
	
	$courier_account_number_id = $_POST['courier_account_number_id'];
	$status_value = $_POST['status_value'];
	
	$obj_courier_account_number->$fun($courier_account_number_id,$status_value);
	
	
}

?>