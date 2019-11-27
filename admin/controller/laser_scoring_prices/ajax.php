<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateVolumeStatus')
{ 
	//printr($_POST);die;
	$volume_id = $_POST['volume_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_volume->$fun($volume_id,$status_value);
}
if($fun=='update_scoring_price')
{ 
	$laser_prices->$fun($_POST['price'],$_POST['type_id'],$_POST['product_id']);
}
if($fun=='addScoringPrice')
{ 
	$laser_prices->$fun($_POST['price'],$_POST['type_id'],$_POST['product_id']);
}




?>