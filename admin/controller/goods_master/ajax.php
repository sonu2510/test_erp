<?php
include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'updateGoodsStatus') {
	//printr($_POST);
	$goods_master_id = $_POST['goods_master_id'];
	$status_value = $_POST['status_value'];
	$obj_goods_master->$fun($goods_master_id,$status_value);
}

?>