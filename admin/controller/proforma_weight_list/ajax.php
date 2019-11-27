<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();

if($fun == 'remove'){
	$weight_id= $_POST['weight_id'];
	$obj_weight->remove_weight_record($weight_id);
}

?>