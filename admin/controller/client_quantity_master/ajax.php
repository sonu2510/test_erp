<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();

if($fun == 'addQtyPrice'){
	// && isset($_POST['zone_id']) && (int)$_POST['zone_id'] > 0
	if(isset($_POST['client_qty_id']) && (int)$_POST['client_qty_id'] > 0  ){
		$client_qty_id = (int)$_POST['client_qty_id'];
		//$courier_zone_id = (int)$_POST['zone_id'];
		$from_qty = (float)$_POST['fQty'];
		$to_qty = (float)$_POST['tQty'];
		$price = (float)$_POST['price'];
		
		$obj_qty -> $fun($client_qty_id,$from_qty,$to_qty,$price);
	
		$json['success'] = 'Successfully Inserted';
	
	}else{
		
		$json['warning'] = 'Error!';
	}
		
	echo json_encode($json);	
}

if($fun == 'delQty'){

	$client_qty_id = (int)$_POST['client_qty_id'];
	$obj_qty -> $fun($client_qty_id);
	//echo $client_qty_id;
}
?>