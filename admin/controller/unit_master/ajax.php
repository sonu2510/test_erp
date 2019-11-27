<?php

include("mode_setting.php");

$ajax = $_GET['ajax'];

if($ajax == 'UpdateProductunitStatus')
{
	
	$unit_id = $_POST['unit_id'];
	$status_value = $_POST['status_value'];
	$obj_unit->$ajax($unit_id,$status_value);
}

?>