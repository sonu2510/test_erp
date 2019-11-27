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
		require_once(DIR_ADMIN.'model/product_quotation.php');
		$obj_quotation = new productQuotation;
	//	echo $material_id;
		$printing_effect = $obj_quotation->getActivePrintingEffect($material_id);
		$html .="<label class='col-lg-3 control-label'>Effect</label><div class='col-lg-3'>";
                      
                        
		$html .= '<select name="printing_effect" class="form-control validate[required]" id="printing_effect">';
				  if($printing_effect){
					// printr($printing_effect);
					 foreach($printing_effect as $key=>$val){
							$html .= '<option value="'.$val['printing_effect_id'].'">'.$val['effect_name'].'</option>';
					  }
				  }
		$html .= '</select></div>';
	echo $html;
		$json = $html;
	//	echo json_encode($json);
	}
	
}else{
	echo 0;
}

?>