<?php
include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'updateIndustryStatus') {
	$industry_id = $_POST['industry_id'];
	$status_value = $_POST['status_value'];
	$obj_industry->$fun($industry_id,$status_value);
}

	if($fun == 'view_Event_detail') {
	//printr($_POST);die;
	$res = $obj_industry->view_Event_detail($_POST['today']);
	//printr($res);die;
/*	$en_note=array();
	$en_date =array();
	$en_id = array();
	$en_fw_id =array();
	if(!empty($res)){
		foreach($res as $r){
			printr($r);
				$arr_result['enquiry_note'] = $r['enquiry_note'];
				$arr_result['followup_date'] = $r['followup_date'];
				$arr_result['enquiry_id'] = $r['enquiry_id'];
				$arr_result['enquiry_followup_id'] =$r['enquiry_followup_id'];

		}
	}
	*/
	
	//	printr($arr_result);			
		echo json_encode($res);

	//	echo json_encode($res);
	
	}
	
	if($fun == 'updateFollowupDetail') {
	$enquiry_followup_id = $_POST['enquiry_followup_id'];
	
	$result = $obj_industry->updateFollowup($enquiry_followup_id);
	 echo $result;
	
}
if($fun == 'insert_data') {
	$title = $_POST['title'];
	$date = $_POST['date'];
	$time = $_POST['time'];
	//print_r($title);
	$result=$obj_industry->insert_data($title,$date,$time);
	echo $result;
}
if($fun == 'update_data') {
	//$oldtitle = $_POST['oldt'];
	$newtitle = $_POST['newt'];
	$followup_id = $_POST['id'];
	//print_r($title);
	$result=$obj_industry->update_data($newtitle,$followup_id);
	echo $result;
}

if($fun == 'delete_data') {
	$followup_id = $_POST['id'];
	
	//print_r($title);
	$result=$obj_industry->delete_data($followup_id);
	echo $result;
}
?>

