<?php

include("mode_setting.php");

$ajax = $_GET['ajax'];

if($ajax == 'UpdateProductMeasurementStatus')
{
	
	$product_id = $_POST['product_id'];
	$status_value = $_POST['status_value'];
	$obj_product_measurement->$ajax($product_id,$status_value);
}

?>