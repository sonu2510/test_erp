<?php
include("mode_setting.php");

$ajax = $_GET['ajaxfunc'];
echo $ajax;

if($ajax=='UpdateSpoutStatus')
{
	$spout_id = $_POST['spout_id'];
	$spout_status = $_POST['spout_status'];
	//echo $spout_id;
	//echo $ajax;
	$obj_spout->$ajax($spout_id,$spout_status);
	
}
?>