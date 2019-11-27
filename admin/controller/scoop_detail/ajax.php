<?php
include("mode_setting.php");
//echo "ajax";
$ajax = $_GET['ajaxfun'];
//echo $ajax;

if($ajax=='UpdateStatus')
{
	//printr($_POST);
	$scoop_id = $_POST['scoop_id'];
	$spout_status = $_POST['spout_status'];
	
	$obj_scoop->$ajax($scoop_id,$spout_status);
	
}
?>