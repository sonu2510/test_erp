<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;
$json=array();

 if($_GET['fun']=='quotation_data')
 { 
     $data= json_decode($_POST['post_arr']);
	//printr($data-> user_name);die;
	//$user_name
	//printr($data);die;
	$Overcross_Delivery ='';
	if(isset($data->Overcross_Delivery) && !empty($data->Overcross_Delivery))
	{
		$Overcross_Delivery = $data->Overcross_Delivery;
	}
	$post = array('user_name'=>$data-> user_name,
				'f_date'=>$data->f_date,
				't_date'=>$data->t_date,
				'product' => $data->product,
				'Shipment_Country'=>$data->Shipment_Country,
				'transport_type'=>$data->transport_type,
				'Overcross_Delivery'=>$Overcross_Delivery);
	//printr($post);die;
	$multi_quotation_details=$obj_quotation->getReport($post);
	$quotation_data = $obj_quotation->view_quotation_report($multi_quotation_details,$post);
	
	//$html =$obj_stock->viewenquiryReport($stock_data);
	
	echo $quotation_data;
	/*$enquiry = $obj_enquiry->getenquiryReport($post);
	if($enquiry != 0)
	{
		return json_encode($enquiry);
	}
	else
	{
		return 0;
	}*/
	
 }

?>