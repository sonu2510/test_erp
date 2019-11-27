<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='update_lamination_status')
{ 
	$data=$obj_digital_printing->$fun($_POST['val']);
	echo $data;
}
if($fun=='job_detail')
{ 
	$data=$obj_digital_printing->$fun($_POST['job']);
	echo json_encode($data);
}

if($fun=='remove_ink_front')
{	
	//printr($_POST['lamination_layer_id']);die;
	$data = $obj_digital_printing->$fun($_POST['digital_printing_ink_id']);
}
if($fun=='remove_ink_back')
{	
	//printr($_POST['lamination_layer_id']);die;
	$data = $obj_digital_printing->$fun($_POST['digital_printing_ink_back_id']);
}
if($fun=='viewdigital_printing_report')
{
	//printr($_POST['lamination_id']);die;
	$data = $obj_digital_printing->viewdigital_printing_report($_POST['lamination_id']);
	echo $data;
}

?>