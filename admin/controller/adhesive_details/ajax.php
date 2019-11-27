<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateRollStatus')
{ 
	$adhesive_id = $_POST['adhesive_id'];
	$status_value = $_POST['status_value'];
	$obj_adhesive->$fun($adhesive_id,$status_value);
}
if($fun=='remove_adhesive'){
    $adhesive_material_id = $_POST['adhesive_material_id'];
    $obj_adhesive->$fun($adhesive_material_id);
}
if($fun=='viewAdhesive_report'){
    $adhesive_id = $_POST['adhesive_id'];
    $data=$obj_adhesive->$fun($adhesive_id);
	echo $data;
}
if($fun == 'csvAdhesive'){
	parse_str($_POST['formData'], $post);
	$csv=$obj_adhesive->AdhesiveArrayForCSV($post['post']);
	echo $csv;

}


?>