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

	$obj_slitting->$fun($roll_id,$status_value);
}
if($fun=='getInputQty')
{ 
	$data=$obj_slitting->$fun($_POST['val']);
	echo json_encode($data);
}
if($fun=='getRollCodeDetails')
{ 
	$data=$obj_slitting->$fun($_POST['val']);
	echo json_encode($data);
}
if($fun=='job_detail')
{ 
	$data=$obj_slitting->$fun($_POST['job']);
	echo json_encode($data);
}
if($fun=='remove_roll')
{	
	//printr($_POST['lamination_layer_id']);die;
	$data = $obj_slitting->$fun($_POST['slitting_material_id'],$_POST['slitting_status'],$_POST['roll_code_id']);
}
if($fun=='lamination_report')
{
	//printr($_POST['slitting_id']);die;
	$data = $obj_slitting->view_slitting_report($_POST['slitting_id']);
	echo $data;
}


?>