<?php
	$table = 'packing_order'; 
	$rout = 'packing_order';
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
	$display_name = 'Packing Order';
	$menuId = $obj_general->getMenuId($rout);
	$display_status = true;
	include('model/packing_order.php');
	$obj_source = new packing_order;
	include('model/proforma_invoice_product_code_wise.php');
	$obj_pro_invoice = new pro_invoice;
	
?>