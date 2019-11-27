<?php
// Start: Building System
include_once("../../ps-config.php");
// End: Building System

if(isset($_POST['courier_id']) && (int)$_POST['courier_id'] > 0 && isset($_POST['zone_id']) && (int)$_POST['zone_id'] > 0 ){
	$courier_id = (int)$_POST['courier_id'];
	$courier_zone_id = (int)$_POST['zone_id'];
	$from_kg = (float)$_POST['fkg'];
	$to_kg = (float)$_POST['tkg'];
	$price = (float)$_POST['price'];
	$obj_db->query("INSERT INTO " . DB_PREFIX . "courier_zone_price SET courier_id = '".$courier_id."', courier_zone_id = '".$courier_zone_id."', from_kg = '".$from_kg."', to_kg = '".$to_kg."', price = '".$price."', status = '1', date_added = NOW() ");
	echo 1;
}else{
	echo 0;
}

?>