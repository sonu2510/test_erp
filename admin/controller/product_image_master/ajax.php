<?php 
include("mode_setting.php");
if($fun == 'updateInvoice')
{	
	$invoice_no = $_POST['invoice_no'];
	$status = $_POST['status_value'];
	$obj_invoice->$fun($invoice_no,$status);
	
}
?>