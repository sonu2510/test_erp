<?php
include("mode_setting.php");
//[kinjal]:
$fun = $_GET['fun'];
//echo $fun;
$json=array();

 if($_GET['fun']=='address_book_data')
 { 
     $data= json_decode($_POST['post_arr']);
	 $post = array('customer_name'=>$data-> customer_name,
				   'user_name'=>$data-> user_name,
				   'emp'=>$data-> emp,
				   'emp_name'=>$data-> emp_name,
				   'f_date'=>$data-> f_date,
				   't_date'=>$data-> t_date);
	 $html =$obj_address->viewAddressBookReport($post);
	
	 echo $html;
 }

 if($_GET['fun']=='getEmpList')
 {
	
	
	$data =$obj_address->getEmpList($_POST['ib'],$_POST['sel']);
	//printr($data);
	$html = '';
	$html .='<option value="">Select User</option>';
	if(isset($data))
	{
		foreach($data as $d)
		{
			$html.="<option value='2=".$d['employee_id']."'>".$d['user_name']."</option>";
		}
	}
	echo $html;//die;
	
 }
 if($_GET['fun']=='getCustomer')
 {
	$data= $obj_address->getCustomer($_POST['ib_user'],$_POST['emp_user']);
	$html = '';
	$html .='<option value="">Select Customer</option>';
	if(isset($data))
	{
		foreach($data as $d)
		{
			$html.="<option value='".$d['address_book_id']."'>".$d['company_name']."</option>";
		}
	}
	echo $html;
 }
 
?>