<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();


if($_GET['fun']=='updateMaterialStatus') {
	
	$material_id = $_POST['material_id'];
	$status_value = $_POST['status_value'];
	
	$obj_material->$fun($material_id,$status_value);

}

?>