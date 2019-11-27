<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();
if($_GET['fun']=='updateInvoiceFixStatus') {
	
	$fixmaster_id = $_POST['fixmaster_id'];
	$status_value = $_POST['status_value'];
	
	$obj_fixmaster->$fun($fixmaster_id,$status_value);
	
	
}

?>