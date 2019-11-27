<?php
include("mode_setting.php");
//echo "ajax";
$ajax = $_GET['ajaxfun'];
//echo $ajax;

if($ajax=='UpdateStatus')
{
	//printr($_POST);
	$spout_id = $_POST['spout_id'];
	$spout_status = $_POST['spout_status'];
	
	$obj_spout->$ajax($spout_id,$spout_status);
	
}
?>