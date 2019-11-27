<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();


if($_GET['fun']=='updateVolumeStatus') {
	
	$volume_id = $_POST['volume_id'];
	$status_value = $_POST['status_value'];
	
	$obj_volume->$fun($volume_id,$status_value);

}

?>