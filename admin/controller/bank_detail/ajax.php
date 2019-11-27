<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();

if($_GET['fun']=='updateCurrencyPrice'){

	$country_id=$_POST['update_country_field'];
	$new_price = $_POST['new_price'];
	
	if(is_numeric($_POST['new_price'])){
		$obj_country->$fun($country_id,$new_price);
		$json['success'] = 'Successfully Updated!';
	}else{
		$json['error'] = 'Only Numbers Allow';
	}

	echo json_encode($json);
}

if($_GET['fun']=='updateBankStatus') {
	
	$bank_detail_id = $_POST['bank_detail_id'];
	$status_value = $_POST['status_value'];
	
	$obj_bank->$fun($bank_detail_id,$status_value);
	
	
}

?>