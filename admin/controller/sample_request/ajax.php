<?php
include("mode_setting.php");

$fun = $_GET['fun'];

if($_GET['fun']=='checkOTP') {
	
	if(isset($_POST["otp"])&&$_POST["otp"]!=""&&$_POST["posted_otp"]==$_POST["otp"])
	{
		$obj_sample->$fun($_POST["otp"]);
		echo 1;
	}
	else
	{
		echo 0;
	}
	
	
}
if($_GET['fun']=='saveData') {
	
	parse_str($_POST['formData'], $post);
	//printr($post);die;
	$code=rand(100000,999999);
	$admin_email = $_POST['admin_email'];
	$toemail = $post['email_1'];
		$insert_id = $obj_sample->addRequest($post,$code);
//	$send_mail = $obj_sample->send_otp_mail($admin_email,$code,$toemail,'',$insert_id,'0');
	$u = explode("=", $_POST['requester']);
	$addedByinfo=$obj_sample->getUser($u[1],'2');
//	$send_mail_requester = $obj_sample->send_otp_mail($_POST['from_email'],$post,$addedByinfo['email'],'',$insert_id,$k=1);
	
	echo  encode($insert_id);
}
if($_GET['fun']=='savedispatch') {
	//printr($_POST);
	$data = $obj_sample->savedispatch($_POST['courier_name'],$_POST['aws_no'],$_POST['sent_date'],$_POST['req_id'],$_POST['no'],$_POST['admin_email']);
	echo  '1';
}
if($fun == 'customer_detail'){
	$company_nm = $_POST['company_nm'];
	$result = $obj_sample->getCustomerDetail($company_nm);
//	printr($result);die;
	echo json_encode($result);
}
if($fun == 'getdata'){
	$request_id = $_POST['request_id'];
	$result = $obj_sample->getRequest($request_id);
//	printr($result);die;
	echo json_encode($result);
}
if($fun == 'get_remark'){
	$address_book_id = $_POST['address_book_id'];
	$result = $obj_sample->get_remark($address_book_id);
      if(!empty($result['remark'])){
          echo $result['remark'] ;
     } else{
        echo 0;
     }

}

//comment by sonu 17-09-2018
/*if($fun == 'regenrate_otp'){
	$request_id = $_POST['request_id'];
	$code=rand(100000,999999);
	$admin_email = $_POST['admin_email'];
	//$toemail = $post['email_1'];
	$request_data = $obj_sample->getRequest($request_id);
	$send_mail = $obj_sample->send_otp_mail($admin_email,$code,$request_data['email_1'],1,$request_id);
	$data = $obj_sample->update_otp($request_id,$code);
//	printr($result);die;
	echo '1';
}*/

?>