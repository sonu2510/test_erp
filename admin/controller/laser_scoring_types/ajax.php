<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateLaserStatus')
{ 
	//printr($_POST);die;
	$type_id = $_POST['type_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_laser->$fun($type_id,$status_value);
}
?>