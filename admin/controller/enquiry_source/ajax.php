<?php
include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'updateSourceStatus') {
	$source_id = $_POST['source_id'];
	$status_value = $_POST['status_value'];
	$obj_source->$fun($source_id,$status_value);
}
?>