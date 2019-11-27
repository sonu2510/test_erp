<?php
include("mode_setting.php");
//[kinjal]:
$fun = $_GET['fun'];
//echo $fun;
$json=array();

 if($_GET['fun']=='att_data')
 { 
     $data= json_decode($_POST['post_arr']);
	
	
	if($_POST['p']=='1')
	{
		$post = array('employee'=>$data-> employee,
					 'f_date'=>$data->f_date,
					 't_date'=>$data->t_date);
	}
	else
	{
		$post = array('group'=>$data-> group,
					 'f_date'=>$data->f_date,
					 't_date'=>$data->t_date);
	}
	
	if($_POST['p']=='1')
		$html =$obj_attendance->getAnnualAttendanceReport($post);
	else
		$html =$obj_attendance->getAttendanceReport($post);
	
	echo $html;
	
	
 }
 
?>