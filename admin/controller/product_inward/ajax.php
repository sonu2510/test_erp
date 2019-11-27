<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateProductStatus')
{ 
	$product_inward_id = $_POST['product_inward_id'];
	$status_value = $_POST['status_value'];

	$obj_product_inward->$fun($product_inward_id,$status_value);
}
if($fun=='product_item_detail')
{ 
	$product_item = $_POST['product_item'];
	$result = $obj_product_inward->getProductDetail($product_item);
	echo json_encode($result);
}
if($fun=='user_detail')
{ 
	$user_type = $_POST['user_type'];
	$result = $obj_product_inward->getUserDetail($user_type);
	echo json_encode($result);
}



?>