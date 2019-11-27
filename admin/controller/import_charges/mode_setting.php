<?php
	$table = 'import_charges'; 
	$rout = 'import_charges';
	define('ADD','Record has been added successfully.');
	define('UPDATE','Record has been updated successfully.');
	define('DELETE','Record has been delete successfully.');
	define('ACTIVE','Record has been activate successfully.');
	define('INACTIVE','Record has been inactivate successfully.');
	define('DELETECONFIRMATION','Are you sure you want to delete user?');
	define('CSVDOWNLOADCONFIRMATION','Are you sure you want to download CSV File?');
	define('ACTIVE_WARNING','Are you sure Active selected item.');
	define('INACTIVE_WARNING','Are you sure Inactive selected item.');
	define('ENABLE_WARNING','Are you sure Enable selected item.');
	define('DISABLE_WARNING','Are you sure Disable selected item.');
	define('ACTION', "index.php?rout=$rout");
	define('DELETE_WARNING','Are you sure you want to delete selected record ?');
	define('CSV_WARNING','Are you sure you want to download CSV File for selected record ?');
	$display_name = 'Import Charges';
	$menuId = $obj_general->getMenuId($rout);
	$display_status = true;
	include('model/import_charges.php');
	$obj_councharge = new councharge;
?>