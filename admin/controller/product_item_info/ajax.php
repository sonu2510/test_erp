<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateCodeStatus')
{ 
	//printr($_POST);die;
	$code_id = $_POST['product_item_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_product_item->$fun($code_id,$status_value);
}


if($fun=='checkProductCode')
{ 
	$product_code='';
	if($_POST['edit']=='1')
	{
		$data=$obj_product_item->getProductCodeData($_POST['product_item_id']);
		$product_code = $data['product_code'];
	}
	if(strtoupper($_POST['product_code'])==strtoupper($product_code))
	{
		echo 0;
	}
	else
	{
		if(isset($_POST['product_code']) && $_POST['product_code'] != ''){
			$product_code = $_POST['product_code'];
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_item_info` WHERE product_code = '" .$product_code. "' AND is_delete=0";
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
}
if($fun=='checkproduct_name')
{ 
	
	$product_nm='';
	if($_POST['edit']=='1')
	{
		$data=$obj_product_item->getProductCodeData($_POST['product_item_id']);
		$product_nm = $data['product_name'];
	}
	if(strtoupper($_POST['product_name'])==strtoupper($product_nm))
	{
		echo 0;
		//printr($_POST['product_name'].'=='.$product_nm);
	}
	else
	{
		if(isset($_POST['product_name']) && $_POST['product_name'] != ''){
			$product_name = $_POST['product_name'];
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_item_info` WHERE product_name = '" .$product_name. "' AND is_delete=0";
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
}

?>