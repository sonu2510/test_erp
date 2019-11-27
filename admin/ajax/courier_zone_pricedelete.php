<?php
// Start: Building System
include_once("../../ps-config.php");
// End: Building System

if(isset($_POST['id']) && (int)$_POST['id'] > 0 ){
	$obj_db->query("DELETE FROM " . DB_PREFIX . "courier_zone_price WHERE courier_zone_price_id = '".(int)$_POST['id']."'");
	echo 1;
}else{
	echo 0;
}

?>