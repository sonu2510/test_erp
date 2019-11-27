<?php
// Start: Building System
include_once("../../ps-config.php");
// End: Building System
if(isset($_POST['name']) && $_POST['name'] != ''){
	include('../model/product.php');
	$obj_product = new product();
	$material_name = $_POST['name'];
	//echo $material_name;die;
	$count =  $obj_product->chekMaterialName($material_name);
	if($count > 0){
		echo $count;
	}else{
		echo 0;
	}
}else{
	echo 0;
}

?>