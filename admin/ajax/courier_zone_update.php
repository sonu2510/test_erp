<?php
// Start: Building System
include_once("../../ps-config.php");
// End: Building System

if(isset($_POST['id']) && (int)$_POST['id'] > 0 ){
	$updateId = (int)$_POST['id'];
	$from_kg = (float)$_POST['fkg'];
	$to_kg = (float)$_POST['tkg'];
	$price = (float)$_POST['price'];
	$obj_db->query("UPDATE " . DB_PREFIX . "courier_zone_price SET from_kg = '".$from_kg."', to_kg = '".$to_kg."', price = '".$price."', date_modify = NOW() WHERE courier_zone_price_id = '".(int)$updateId."'");
	echo 1;
}else{
	echo 0;
}

?>