<?php
	$table = 'government_sales_invoice'; 
	$rout = 'government_sales_invoice';
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
	$display_name = 'Government Sales Invoice';
	$menuId = $obj_general->getMenuId($rout);
	$display_status = true;
	include('model/government_sales_invoice.php');
	$obj_invoice = new government_sales_invoice; 
	include('model/template_order_test.php');
	$obj_template = new templateorder;
	include('model/rack_master.php');
	$obj_rack_master = new rack_master;
	include('model/proforma_invoice_product_code_wise.php');
	$obj_pro_invoice = new pro_invoice;
	include('model/goods_master.php');
	$obj_goods_master = new goods_master;
?>