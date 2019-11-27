<?php
include("mode_setting.php");
$fun = $_GET['fun'];
$json=1;
if($fun == 'update_proforma_price'){
	
	
	
	$qry = $obj_pricelist->update_proforma_price($_POST['price_list_id'],$_POST['width'],$_POST['height'],$_POST['gusset'],$_POST['quantity100'],$_POST['quantity200']	,$_POST['quantity500'],$_POST['quantity1000'],$_POST['quantity2000'],$_POST['quantity5000'],$_POST['quantity10000'],$_POST['air_quantity5000'],$_POST['air_quantity10000'],$_POST['air_quantity100'],$_POST['air_quantity200'],$_POST['air_quantity500'],$_POST['air_quantity1000'],$_POST['air_quantity2000']);
	//echo $qry;
}
if($fun == 'remove'){
	
	
	$qry = $obj_pricelist->remove_row($_POST['price_qty_id']);
}

?>