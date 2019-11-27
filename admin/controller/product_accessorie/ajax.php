<?php
include("mode_setting.php");

$fun = $_GET['fun'];

if($_GET['fun']=='updateaccessorieStatus') {
	
	$accessorie_id = $_POST['accessorie_id'];
	$status_value = $_POST['status_value'];
	
	$obj_accessorie->$fun($accessorie_id,$status_value);
	
	
}

?>