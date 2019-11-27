<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updatemixSolventStatus')
{ 
	$ink_id = $_POST['ink_id'];
	$status_value = $_POST['status_value'];
	$obj_mix_sol_process->$fun($ink_id,$status_value);
}
if($fun=='remove_mix_solvent'){
    $ink_id = $_POST['ink_id'];
	//printr($ink_id);die;
    $obj_mix_sol_process->$fun($ink_id);
}
if($fun=='job_detail')
{ 
	$data=$obj_mix_sol_process->$fun($_POST['job']);
	echo json_encode($data);
}

?>