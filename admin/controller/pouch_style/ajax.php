<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();


if($_GET['fun']=='updateStyleStatus') {
	
	$style_id = $_POST['style_id'];
	$status_value = $_POST['status_value'];
	
	$obj_style->$fun($style_id,$status_value);

}

?>