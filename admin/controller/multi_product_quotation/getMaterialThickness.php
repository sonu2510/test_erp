<?php
// Include Connaction Class
session_start(); 
$session_start = 1;
// Include Connaction Class
require_once($_SESSION['SES_DIR_SERVER'].'ps-config.php');

if(isset($_POST['material_id']) && $_POST['material_id'] != '' && (int)$_POST['material_id'] > 0){
	$material_id = (int)$_POST['material_id'];
	$json = '';
	if($material_id){
		$html = '';		
		require_once(DIR_ADMIN.'model/product_material.php');
		$obj_material = new productMaterial;
		
		$material_thickness = $obj_material->getMaterialNewThickness($material_id);
		//printr($material_thickness);die;
		$html .= '<select name="thickness[]" class="form-control validate[required]">';
				  if($material_thickness){
					  $html .= '<option value="">Select Thickness</option>';
					  foreach($material_thickness as $thickness_details){
							$html .= '<option value="'.$thickness_details['thickness'].'">'.$thickness_details['thickness'].'</option>';
					  }
				  }
		$html .= '</select>';
		
		$json = $html;
		echo json_encode($json);
	}
	
}else{
	echo 0;
}

?>