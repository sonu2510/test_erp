<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateRollStatus')
{ 
	//printr($_POST);die;
	$production_process_id = $_POST['roll_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_production_process->$fun($production_process_id,$status_value);
}
if($fun=='RollNoExsist')
{ 
	if(isset($_POST['no']) && $_POST['no'] != ''){
		$production_process_name = $_POST['no'];
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "production_process` WHERE production_process_name = '".$production_process_name."' AND is_delete='0'";
		$data = $obj_db->query($sql);
		if($data->row['total'] > 0){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		echo 0;
	}
}

?>