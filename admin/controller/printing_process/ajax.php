<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateRollStatus')
{ 
	//printr($_POST);die;
	$roll_id = $_POST['roll_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_printing_job->$fun($roll_id,$status_value);
}
if($fun=='getInputQty')
{ 
	$data=$obj_printing_job->$fun($_POST['val']);
	echo json_encode($data);
}
if($fun=='job_detail')
{ 
	$data=$obj_printing_job->$fun($_POST['job']);
	echo json_encode($data);
}
if($fun=='remove_roll')
{ 
	$data=$obj_printing_job->$fun($_POST['roll_id']);
	echo json_encode($data);
}if($fun=='removeInvoice')
{ 
	$data=$obj_printing_job->$fun($_POST['printing_operator_id']);
	echo json_encode($data);
}
if($fun=='displaydata')
{
	//printr($_POST['lamination_id']);die;
	$data = $obj_printing_job->viewjob_details($_POST['job_id']);
	echo $data;
}


?>