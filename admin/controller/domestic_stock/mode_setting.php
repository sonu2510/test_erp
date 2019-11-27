<?php
	$table = 'domestic_stock'; 
	$rout = 'domestic_stock';
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
	define('WARNING','Sorry!!! This item is not available in Invoice or Stock Order.');
	$display_name = 'Domestic Stock';
	$menuId = $obj_general->getMenuId($rout);
	$display_status = true;
	include('model/domestic_stock.php');
	$obj_domestic_stock = new domestic_stock;
	include('model/product_category_details.php');
	$obj_catalogue_category = new catalogue_category; 
?>