<?php

include("mode_setting.php");

$ajax = $_GET['ajax'];

if($ajax == 'UpdateTemplateVolumeStatus')
{
	
	$product_id = $_POST['product_id'];
	$status_value = $_POST['status_value'];
	
	$obj_template_volume->$ajax($product_id,$status_value);
}

?>