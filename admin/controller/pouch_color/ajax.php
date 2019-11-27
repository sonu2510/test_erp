<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();


if($fun=='updateColorStatus') {
	
	$color_id = $_POST['color_id'];
	$status_value = $_POST['status_value'];
	
	$obj_color->$fun($color_id,$status_value);

}

?>