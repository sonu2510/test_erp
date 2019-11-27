<?php

include("mode_setting.php");

$ajax = $_GET['ajaxfunc'];

if($ajax == "UpdateOrderStatus")
{
	$order_id = $_POST['orderid'];
	$order_status = $_POST['orderstatus'];
	
	$obj_order_status->$ajax($order_id,$order_status);		
}

?>