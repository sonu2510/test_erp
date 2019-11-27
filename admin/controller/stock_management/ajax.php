<?php
include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'updatestockStatus') {
	//printr($_POST);
	$stock_id = $_POST['stock_id'];
	$status_value = $_POST['status_value'];
	$obj_stock->$fun($stock_id,$status_value);
}
if($fun == 'getRow') {
	$html = '';
	$goods_master_id = $_POST['id'];
	$html .= '<select name="row" id="row" class="form-control validate[required]" >
		<option value="">Select Row</option>';
		$row= $obj_stock->getGoodsRowColumn($goods_master_id);
		for($i=0;$i<$row['row'];$i++) {		
			$html .='<option value="'.($i+1).'">'.($i+1).'</option>';		
		}
	$html .='</select>';
	echo $html;
}
if($fun == 'getColumn') {
	$html = '';
	$goods_master_id = $_POST['id'];
	$html .= '<select name="column_name" id="column" class="form-control validate[required]" >
		<option value="">Select Row</option>';
		$row= $obj_stock->getGoodsRowColumn($goods_master_id);
		for($i=0;$i<$row['column_name'];$i++) {		
			$html .='<option value="'.($i+1).'">'.($i+1).'</option>';		
		}
	$html .='</select>';
	echo $html;
}
?>