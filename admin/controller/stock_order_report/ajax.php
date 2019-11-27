<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;
$json=array();

 if($_GET['fun']=='stock_data')
 { 
    $data= json_decode($_POST['post_arr']);
//	printr($data);
	if($_POST['m']=='1')
	{
	    $post = array('f_date'=>$data->f_date,
    				  't_date'=>$data->t_date,
    				  'Ship_type'=>$data->Ship_type,
    				  'Shipment_Country'=>$data->Shipment_Country);
	    
	}
	elseif($_POST['m']=='2')
	{
	    $post = array('user_name'=>$data->user_name,
    				  'order'=>$data->order);
	}
	else
	{
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
    				'Status'=>$data->Status,
    				'Overcross_Delivery'=>$Overcross_Delivery);
	}
    if(isset($_POST['m']) && $_POST['m']=='2')
	{
		$stock_order_details=$obj_stock->GetCartOrderList($post);
	}
	else
	{
	    $stock_order_details=$obj_stock->getReport($post,$_POST['m']);
	}
	
	if($_POST['m']=='1')
	    $stock_data = $obj_stock->stock_report($stock_order_details,$post);
	elseif($_POST['m']=='2')
	    $stock_data=$obj_stock->pending_order_report($stock_order_details,$post);
	else
	    $stock_data = $obj_stock->view_stock_report($stock_order_details,$post);
	
	echo $stock_data;
	
 }

?>