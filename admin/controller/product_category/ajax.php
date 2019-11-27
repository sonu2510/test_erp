<?php

include("mode_setting.php");

$ajax = $_GET['ajax'];

if($ajax == 'UpdateProductStatus')
{
	
	$product_category_id = $_POST['product_category_id'];
	$status_value = $_POST['status_value'];
	$obj_product_category->$ajax($product_category_id,$status_value);
}

?>