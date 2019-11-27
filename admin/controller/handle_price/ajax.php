<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateHandleStatus')
{ 
	//printr($_POST);die;
	$handle_id = $_POST['handle_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_handle->$fun($handle_id,$status_value);
}
?>