<?php
include("mode_setting.php");
//[kinjal]:
$fun = $_GET['fun'];
//echo $fun;
$json=array();

 if($_GET['fun']=='production_data')
 { 
 	//	printr($_POST);die;
     $data= json_decode($_POST['post_arr']);
	 $post = array('job_name_text'=>$data-> job_name_text,			
				'job_id'=>$data->job_id,
				'job_name'=>$data->job_name);
			
	 
		 $html =$obj_job_report->getjob_report($post['job_id']);
	
	
	 echo $html;
 }

 if($fun=='job_detail')
{ 
	$data=$obj_job_report->$fun($_POST['job']);
	echo json_encode($data);
}

?>