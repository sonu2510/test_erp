<?php
include("mode_setting.php");
$fun = $_GET['fun'];
$json=1;

if($fun == 'updateProStatus') {
	$proforma_id = $_POST['proforma_id'];
	$status_value = $_POST['status_value'];
	//printr($proforma_id);
	$obj_pro_invoice->$fun($proforma_id,$status_value);
}
?>