<?php
	$table = 'multi_product_quotation'; 
	$rout = 'digital_custom_order';
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
	define('DELETE_WARNING','Are you sure you want to delete selected record ?');
	$display_name = ' Digital Custom Order';
	$menuId = $obj_general->getMenuId($rout);
	$display_status = true;
	include('model/digital_custom_order.php');
	$obj_digital_custom_order = new digital_custom_order;
	
	include('model/digital_quotation.php');
	$obj_quotation = new digital_quotation;

	include('model/template_order.php');
	$obj_template = new templateorder;
	include('model/invoice_test.php');
	$obj_invoice = new invoice;
	
	
	
?>