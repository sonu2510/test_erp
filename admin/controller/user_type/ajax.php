<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateMachineStatus')
{ 
	//printr($_POST);die;
	$user_type_id = $_POST['user_type_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_user_type->$fun($user_type_id,$status_value);
}
?>