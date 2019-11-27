<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateInkStatus')
{ 
	$ink_id = $_POST['ink_id'];
	$status_value = $_POST['status_value'];
	$obj_ink_process->$fun($ink_id,$status_value);
}
if($fun=='remove_ink'){
    $ink_id = $_POST['ink_id'];
    $obj_ink_process->$fun($ink_id);
}

if($fun=='job_detail')
{ 
	$data=$obj_ink_process->$fun($_POST['job']);
	echo json_encode($data);
}
?>