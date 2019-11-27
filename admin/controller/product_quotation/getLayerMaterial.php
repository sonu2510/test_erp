<?php 
// Include Connaction Class
session_start(); 
$session_start = 1;
// Include Connaction Class
require_once($_SESSION['SES_DIR_SERVER'].'ps-config.php');
	
if(isset($_POST['layer']) && $_POST['layer'] != '' && (int)decode($_POST['layer']) > 0){
	
	$layer = (int)decode($_POST['layer']);
	$make=$_POST['make'];
//echo $_POST['make'];
	//$makeid = (int)decode($_POST['make']);
	
	$json = '';
	if($layer){
		//material
		require_once(DIR_ADMIN.'model/product_material.php');
		$obj_material = new productMaterial;
		
		$html = '';
		$html .= '<section class="panel">';
		  $html .= '<div class="table-responsive">';
			$html .= '<table class="table table-striped b-t text-small">';
			  $html .= '<thead>';
				$html .= '<tr>';
				  $html .= '<th  width="15%"></th>';
				  $html .= '<th>Material</th>';
				  $html .= '<th width="30%">Thickness</th>';
				$html .= '</tr>';
			  $html .= '</thead>';
			  $html .= '<tbody>';
			  //echo $layer;
			  for($i=1;$i<=$layer;$i++){
				  $layer_materials = $obj_material->getLayerMakeMaterial($i,$make);
				//  echo $layer_materials;
				  if($layer_materials){
                        $html .= '<tr>';
                          $html .= '<td><b>'.$i.' Layer</b></td>';
                          $html .= '<td>';
                          	$html .= '<select name="material[]" onchange="getMaterialThickness(this.value,'.$i.','.$layer.')" id="material_'.$i.'" class="form-control validate[required]">';
											$html .= '<option value="">Select Material</option>';	
											foreach($layer_materials as $material){
												$html .= '<option value="'.$material['material_id'].'">'.$material['material_name'].'</option>';
											}
								$html .= '</select>';
                          $html .= '</td>';
                          $html .= '<td>';
						  		$html .= '<select name="thickness[]" class="form-control validate[required]" id="thickness-dropdown-'.$i.'">';
								$html .= '</select>';
						  $html .= '</td>';
                        $html .= '</tr>';
				  }
			  }
			  
			  $html .= '</tbody>';
			$html .= '</table>';
		  $html .= '</div>';
		$html .= '</section>'; 
			  
		/*for($i=1;$i<=$layer;$i++){
			//echo $i."<br>";
			$layer_materials = $obj_material->getLayerMaterial($i);
			//printr($layer_materials);die;
			if($layer_materials){
				$html .= '<div class="form-group">';
				$html .= '	<label class="col-lg-3 control-label">'.$i.' Layer</label>';
				$html .= '	<div class="col-lg-9">';
						$html .= '<div class="row">';
							$html .= '<div class="col-lg-8">';
								$html .= '<select name="material[]" id="material_'.$i.'" class="form-control validate[required]">';
											$html .= '<option value="">Select Material</option>';	
										foreach($layer_materials as $material){
											$html .= '<option value="'.$material['material_id'].'">'.$material['material_name'].'</option>';
										}
								$html .= '</select>';
							$html .= '</div>';	
							$html .= '<div class="col-lg-4">';
								$html .= '<input type="text" name="thickness[]" value="" placeholder="Thickness" class="form-control validate[required,custom[onlyNumberSp]]">';
							$html .= '</div>';
						$html .= '</div>';	
				$html .= '	</div>';
				$html .= '</div>';
				
			}
		}*/
		
		$json = $html;
		echo json_encode($json);
	}
	
}else{
	echo 0;
}

?>