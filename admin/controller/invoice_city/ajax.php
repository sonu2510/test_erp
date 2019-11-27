<?php

//kinjal
include("mode_setting.php");

$ajax = $_GET['ajax'];

if($ajax == 'updateCityStatus')
{
	
	$city_id = $_POST['city_id'];
	$status = $_POST['status_value'];
	$obj_invoicecity->$ajax($city_id,$status);
	
}
/*if($ajax == 'updateTransportationStatus')
{
	$city_id = $_POST['city_id'];
	$status_value = $_POST['status_value'];
	$obj_invoicecity->$ajax($city_id,$status_value);
}*/

?>