<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();

if($_GET['fun']=='updateTermsStatus') {
	
	$termsandconditions_id = $_POST['termsandconditions_id'];
	$status_value = $_POST['status_value'];
	
	$obj_terms->$fun($termsandconditions_id,$status_value);
	
}

?>