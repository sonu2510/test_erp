<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateSampleStatus')
{ 
	//printr($_POST);die;
	$sample_id = $_POST['sample_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_sample_sheet->$fun($sample_id,$status_value);
}

if($fun=='sample_data')
{ 
	$sample_id = $_POST['sample_id'];
	$sample_detail = $obj_sample_sheet->getSampleView($sample_id);
	
	$get_report = $obj_sample_sheet->sample_view_detail($sample_detail);
	
	echo $get_report;
	
}
?>