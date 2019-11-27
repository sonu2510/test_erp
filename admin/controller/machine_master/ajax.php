<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateMachineStatus')
{ 
	//printr($_POST);die;
	$machine_id = $_POST['machine_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_machine->$fun($machine_id,$status_value);
}
?>