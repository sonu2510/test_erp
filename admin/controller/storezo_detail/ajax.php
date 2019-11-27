<?php
include("mode_setting.php");
//echo "ajax";
$ajax = $_GET['ajaxfun'];
//echo $ajax;

if($ajax=='UpdateStatus')
{
	//printr($_POST);
	$storezo_id = $_POST['storezo_id'];
	$spout_status = $_POST['spout_status'];
	
	$obj_storezo->$ajax($storezo_id,$spout_status);
	
}
?>