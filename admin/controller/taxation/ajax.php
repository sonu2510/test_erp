<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();

if($_GET['fun']=='updateTaxationStatus') {
	
	$taxation_id = $_POST['taxation_id'];
	$status_value = $_POST['status_value'];
	
	$obj_taxation->$fun($taxation_id,$status_value);
	
	
}

?>