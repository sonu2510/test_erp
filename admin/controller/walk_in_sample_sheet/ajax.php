<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateStatus')
{ 
	//printr($_POST);die;
	$sample_id = $_POST['sample_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_sample_sheet->$fun($sample_id,$status_value);
}
if($fun=='sample_data')
{ 
	$sample_id = $_POST['sample_id'];
	$sample_detail = $obj_sample_sheet->getSampleView($sample_id);
	
	$get_report = $obj_sample_sheet->sample_view_detail($sample_detail);
	
	echo $get_report;
	
}
/*$json=array();

 if($_GET['fun']=='sample_data')
 { 
     $data= json_decode($_POST['post_arr']);
	
	printr($data);//die;
	$post = array('customer_name'=>$data-> customer_name,
				'customer_visit_date'=>$data->customer_visit_date,
				'customer_address'=>$data->customer_address,
				'customer_requirements' => $data->customer_requirements,
				'customer_Product' => $data->customer_Product,
				'weight' => $data->weight,
				'total_bag' => $data->total_bag,
				'sample_name' => $data->sample_name,
				'attend_customer' => $data->attend_customer,
				'result' => $data->result,
				'f1_date' => $data->f1_date,
				'f1_description' => $data->f1_description,
				'f2_date' => $data->f2_date,
				'f2_description' => $data->f2_description,
				'f3_date' => $data->f3_date,
				'f3_description' => $data->f3_description,
				'deal' => $data->deal
				);
	//printr($post);die;
	$sample_detail = $obj_sample_sheet->getSampleView($sample_view);
	
	echo $sample_detail;
	
 }*/

?>