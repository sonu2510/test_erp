<?php
// Start: Building System
include_once("../../ps-config.php");

if(isset($_POST['user_name']) && !empty($_POST['user_name'])){
	
	$sql = "SELECT * FROM `" . DB_PREFIX . "account_master` WHERE user_name = '".$_POST['user_name']."'";
	$data = $obj_db->query($sql);
	if($data->row){ 

		$verification_code = substr(md5(rand()),0,6);
		
		$tag_val = array(
			"{{user_name}}" =>$data->row['user_name'],
			"{{code}}"	=> $verification_code
		);
		
		$obj_email = new email_template(); 
		$rws_email_template = $obj_email->get_email_template(2); 
		
		$subject = $rws_email_template['subject'];
		$message = $obj_email->getEmailTemplateContent(str_replace('\r\n',' ',$rws_email_template['discription']),$tag_val,$rws_email_template['subject']);  
		
		$response = send_email($data->row['email'],ADMIN_EMAIL_QUO,$subject,$message,'');
		
		//echo $response;die;
		
		if($response){
			$obj_session->data['success'] = 'Success : Email Sent To Your Account!';
			
			$obj_db->query("INSERT INTO `" . DB_PREFIX . "forgot_password` SET account_master_id='".$data->row['account_master_id']."',verification_code='".$verification_code."',status=1,date_added=NOW()");			
			echo 1;
		}else{
			echo 0;	
		}
	}else{
		echo 0;	
	}
}else{
	echo 0;
}

?>