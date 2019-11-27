<?php
include("mode_setting.php");
$fun = $_GET['fun'];
$json=1;
if($fun == 'leave_popup_details')
{
	$data = $obj_leave->leave_popup_detail($_POST['leave_id']);
	
//printr($data);
	$res =array(
		'leave_id'=>$data['leave_id'],
		'userid'=>$data['user_id'],
		'type_id'=>$data['user_type_id'],
		'leave_type'=>$data['leave_type_name'],
		'leave_title'=>$data['leave_title'],
		'from_date'=>$data['commencing_date'],
		'to_date'=>$data['ending_date'],
		'msg'=>htmlspecialchars_decode($data['message']),
	

		
	);
	//printr($res);
	echo json_encode($res);
	
	
}


	if($fun == 'activestatus') {
	$leave_type_id = $_POST['id'];
	
	$obj_leave->inactive($leave_type_id);
	}

	if($fun == 'inactivestatus') {
	$leave_type_id = $_POST['id'];
	
	$obj_leave->active($leave_type_id);
	}
//popup
	if($fun == 'approvalstatus') {
	$leave_id = $_POST['id'];
	$reason=$_POST['r'];
	$re_from_date = $_POST['rfd'];
	$re_to_date = $_POST['rtd'];

	$obj_leave->approve($leave_id,$reason,$re_from_date,$re_to_date);
	}

	if($fun == 'disapprovalstatus') {
	$leave_id = $_POST['id'];
	$reason=$_POST['r'];
	$re_from_date = $_POST['rfd'];
	$re_to_date = $_POST['rtd'];
	$obj_leave->disapprove($leave_id,$reason,$re_from_date,$re_to_date);
	}
	
	
	
	if($fun == 'leave_popup_reason')
	{
	$data = $obj_leave->leave_popup_detail($_POST['leave_id']);

		$res =array(
		'leave_id'=>$data['leave_id'],
		'd_reason'=>$data['reason'],
		're_f_date'=>$data['re_from_date'],
		're_t_date'=>$data['re_to_date'],
		);
	
		echo json_encode($res);
	
	
	}	


?>