<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateCustomerStatus')
{ 
	//printr($_POST);die;
	$cust_id = $_POST['cust_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_customer_master->$fun($cust_id,$status_value);
}
if($_GET['fun']=='checkemailaddress') {
	if(isset($_POST['email']) && $_POST['email'] != ''){
		$cust_email = $_POST['email'];
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "customer_master` WHERE email = '".$cust_email."' AND is_delete='0'";
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