<?php
include("mode_setting.php");

$fun = $_GET['fun'];
 if($_GET['fun']=='updateclarification')
 {
	 $invoice_id=$_POST['invoice_id'];
	 $clarification=$_POST['clarification'];
	 $result=$obj_deviation_report->updateclarification($invoice_id,$clarification);
	
 }
 if($fun== 'change')
 {
	  $invoice_id=$_POST['invoice_id'];
	  $result=$obj_deviation_report->changestatus($invoice_id);
	 
	 }
 ?>