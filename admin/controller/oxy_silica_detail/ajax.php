<?php
include("mode_setting.php");
//echo "ajax";
$ajax = $_GET['ajaxfun'];
//echo $ajax;

if($ajax=='UpdateStatus')
{
	//printr($_POST);
	$oxy_silica_id = $_POST['oxy_silica_id'];
	$oxy_silica_status = $_POST['oxy_silica_status'];
	
	$obj_oxy_silica_detail->$ajax($oxy_silica_id,$spout_status);
	
}
?>