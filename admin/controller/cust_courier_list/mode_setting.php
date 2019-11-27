<?php
	$table = 'cust_courier_list'; 
	$rout = 'cust_courier_list';
	define('ADD','Record has been added successfully.');
	define('UPDATE','Record has been updated successfully.');
	define('DELETE','Record has been delete successfully.');
	define('ACTIVE','Record has been activate successfully.');
	define('INACTIVE','Record has been inactivate successfully.');
	define('DELETECONFIRMATION','Are you sure you want to delete user?');
	define('ACTIVE_WARNING','Are you sure Active selected item.');
	define('INACTIVE_WARNING','Are you sure Inactive selected item.');
	define('ENABLE_WARNING','Are you sure Enable selected item.');
	define('DISABLE_WARNING','Are you sure Disable selected item.');
	define('ACTION', "index.php?rout=$rout");
	define('DELETE_WARNING','Are you sure ?');
	
	$display_name = 'Customer Courier ';
	$menuId = $obj_general->getMenuId($rout);
	$display_status = true;
	include('model/cust_courier_list.php');
	$obj_courier = new cust_courier_list;
?>