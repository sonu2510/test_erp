<?php
	$table = 'dashboard'; 
	$rout = 'dashboard';
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
	$display_name = 'Dashboard ';
	//$menuId = $obj_general->getMenuId($rout);
$display_status = true;
	include('model/dashboard.php');
	$obj_dashboard = new dashboard;
	include('model/slitting.php');
	$obj_slitting = new slitting;
	include('model/proforma_invoice_product_code_wise.php');
	$obj_pro_invoice = new pro_invoice;
	include('model/domestic_stock.php');
	$obj_domestic_stock = new domestic_stock;
	include('model/sales_invoice.php');
	$obj_sales_invoice = new sales_invoice;
	include('model/custom_order.php');
	$obj_custom_order = new custom_order;
	include('model/template_order_test.php');
	$obj_template = new templateorder;	
	include('model/multi_product_quotation.php');
	$obj_quotation = new multiProductQuotation;
	include('model/invoice_test.php');
	$obj_invoice = new invoice;

?>