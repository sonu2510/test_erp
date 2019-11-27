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
					$html .= '<option value="">Select Effect</option>';
					 foreach($printing_effect as $key=>$val){
						 if($val['printing_effect_id']==3 && (int)$_POST['layer']!=3)
							$html .= '';
						else
							$html .= '<option value="'.$val['printing_effect_id'].'">'.$val['effect_name'].'</option>';
					  } 
				  }
		$html .= '</select></div>';
		 if((int)$_POST['layer']!=3)
		$html .='<div class="col-lg-3"><span class="btn btn-danger btn-xs">For Matt - Shine Effect Please Select 3 Layer</span></div>';
	echo $html;
		$json = $html;
	//	echo json_encode($json);
	}
	
}else{
	echo 0;
}

?>