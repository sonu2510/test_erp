<?php
include("mode_setting.php");

$ajax = $_GET['ajaxfunc'];
echo $ajax;

if($ajax=='UpdateTintieStatus')
{
	$tintie_id = $_POST['tintie_id'];
	$tintie_status = $_POST['tintie_status'];
	//echo $spout_id;
	//echo $ajax;
	$obj_tin_tie->$ajax($tintie_id,$tintie_status);
	
}
?>