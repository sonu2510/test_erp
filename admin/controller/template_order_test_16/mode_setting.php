<?php
	$table = 'product_template';
	//implode(" ",$post); 
	$rout = 'template_order_test';
	define('ADD','Record has been added successfully.');
	define('CHECK_OUT','Stock Order Submitted successfully.');
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
	define('CHECK','Please check atleast one checkbox');
	$display_name = 'Stock Order Test';
	$menuId = $obj_general->getMenuId($rout);
	$display_status = true;
	include('model/template_order_test.php');
	$obj_template = new templateorder;
	
	include('model/invoice_test.php');
	$obj_invoice = new invoice;
?>