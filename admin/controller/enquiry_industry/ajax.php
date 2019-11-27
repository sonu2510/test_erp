<?php
include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'updateIndustryStatus') {
	$industry_id = $_POST['industry_id'];
	$status_value = $_POST['status_value'];
	$obj_industry->$fun($industry_id,$status_value);
}
?>