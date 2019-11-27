<?php 
include("mode_setting.php");

$fun = $_GET['fun'];
$json=1;
if($fun == 'getMaterialThickness'){
	$material_id= $_POST['material_id'];
	$layer_id= $_POST['layer_id'];
	$material_thickness=$obj_quality_report->getMaterialThickness($material_id);
		$html='';
		$html .= '<select name=" name="material['.$layer_id.'][thickness]"" class="form-control validate[required]">';
				  if($material_thickness){
					  //$html .= '<option value="">Select Thickness</option>';
					  foreach($material_thickness as $thickness_details){
							$html .= '<option value="'.$thickness_details['thickness'].'">'.$thickness_details['thickness'].'</option>';
					  }
				  }
		$html .= '</select>';
	//	$json = $html;
		echo json_encode($html);
}
if($fun == 'getSize'){
	$product_id= $_POST['product_id'];
	$zipper= $_POST['zipper'];
	$size_detail=$obj_quality_report->getSize($product_id,$zipper);
	$html='';
		$html .= ' <label class="col-lg-3 control-label">SIZE </label> ';                       
          $html .= '<div class="col-lg-4">';                         
            $html .= '<select class="form-control validate[required]"  id="size" name="size" ">';
            	  $html .= '<option value="0">Select Size</option>';
               if(!empty($size_detail)){
                     
                          
            foreach($size_detail as $size)  {
                            
                $html .= '<option value="'. $size['size_master_id'].'"  >'.$size['volume'].'('.$size['width'].' x '.$size['height'].' x '.$size['gusset'].') </option>';
            }}
               $html .= ' </select>';
             $html .= '</div>';
	echo $html;
	
}
if($fun == 'getLayerMakeMaterial'){
	$layer_id=  $_POST['layer_id'];
	$category_id = $_POST['category_id'];
	$clear = $print = $gsm = '';
	if($category_id=='1')
	{
	    $clear = 'Clear Side';
	    $print = 'Print Side';
	    $gsm = '';
	}
	else if($category_id=='4')
	{
	    $clear = $gsm = 'Clear Side';
	    $print = 'Paper Side';
	}
		$html = '';
		$html .= '<div class="col-lg-3"></div><div class="col-lg-8">';
		$html .= '<section class="panel">';
		  $html .= '<div class="table-responsive">';
			$html .= '<table class="table table-striped b-t text-small">';
			  $html .= '<thead>';
				$html .= '<tr>';
				  $html .= '<th width="15%"></th>';
				  $html .= '<th>'.$clear.' Material</th>';
				  $html .= '<th >'.$clear.'Thickness</th>';
				  if($category_id=='1' || $category_id=='4')
				  {
    				  $html .= '<th>'.$print.' Material</th>';
    				  $html .= '<th >'.$print.'Thickness</th>';
				  }
				  $html .= '<th >'.$gsm.' STD GSM</th>';
				  if($category_id=='4')
				    $html .= '<th >'.$print.' STD GSM</th>';
				  $html .= '<th >'.$gsm.' MIN GSM</th>';
				  if($category_id=='4')
				     $html .= '<th >'.$print.' MIN GSM</th>';
				  $html .= '<th >'.$gsm.' MAX GSM </th>';
				  if($category_id=='4')
				    $html .= '<th >'.$print.' MAX GSM </th>';
				  
				$html .= '</tr>';
			  $html .= '</thead>';
			  $html .= '<tbody>';
			  //echo $layer;
			  for($i=1;$i<=$layer_id;$i++){
				  $layer_materials = $obj_quality_report->getLayerMakeMaterial($i);
				//  echo $layer_materials;
				  if($layer_materials){
                        $html .= '<tr>';
                          $html .= '<td><b>'.$i.' Layer</b></td>';
                          $html .= '<td>';
                          	$html .= '<select name="material['.$i.'][material_id]" onchange="getMaterialThickness(this.value,'.$i.','.$layer_id.',0)" id="material_'.$i.'" class="form-control validate[required]">';
											$html .= '<option value="">Select Material</option>';	
											foreach($layer_materials as $material){
												$html .= '<option value="'.$material['material_id'].'">'.$material['material_name'].'</option>';
											}
								$html .= '</select>';
                          $html .= '</td>';
                          $html .= '<td>';
						  		$html .= '<select name="material['.$i.'][thickness]" class="form-control validate[required]" id="thickness-dropdown-'.$i.'">';
								$html .= '</select>';
						  $html .= '</td>';
						   if($category_id=='1' || $category_id=='4')
				          {
				              $html .= '<td>';
                              $html .= '<select name="clearprintr['.$i.'][sec_material_id]" onchange="getMaterialThickness(this.value,'.$i.','.$layer_id.',1)" id="clearprintr'.$i.'" class="form-control validate[required]">';
    											$html .= '<option value="">Select Material</option>';	
    											foreach($layer_materials as $material){
    												$html .= '<option value="'.$material['material_id'].'">'.$material['material_name'].'</option>';
    											}
    								$html .= '</select>';
                              $html .= '</td>';
                              $html .= '<td>';
    						  		$html .= '<select name="clearprintr['.$i.'][sec_thickness]" class="form-control validate[required]" id="clearprintr_thickness-dropdown-'.$i.'">';
    								$html .= '</select>';
    						  $html .= '</td>';
				          }
						  $html .= '<td>';
						  		$html .= '<input type="text" name="material['.$i.'][std_gsm]" value="" id="std_gsm" class="form-control"/>';								
						  $html .= '</td>';
						  if($category_id=='4')
				          {
				               $html .= '<td>';
						  		$html .= '<input type="text" name="clearprintr['.$i.'][sec_std_gsm]" value="" id="sec_std_gsm" class="form-control"/>';								
						       $html .= '</td>';
				          }
						    $html .= '<td>';
						  		$html .= '<input type="text" name="material['.$i.'][min_gsm]" value="" id="min_gsm" class="form-control"/>';								
						  $html .= '</td>';
						  if($category_id=='4')
				          {
				               $html .= '<td>';
						  		$html .= '<input type="text" name="clearprintr['.$i.'][sec_min_gsm]" value="" id="sec_min_gsm" class="form-control"/>';								
						       $html .= '</td>';
				          }
						    $html .= '<td>';
						  		$html .= '<input type="text" name="material['.$i.'][max_gsm]" value="" id="max_gsm" class="form-control"/>';								
						  $html .= '</td>'; 
                          if($category_id=='4')
				          {
				               $html .= '<td>';
						  		$html .= '<input type="text" name="clearprintr['.$i.'][sec_max_gsm]" value="" id="sec_max_gsm" class="form-control"/>';								
						       $html .= '</td>';
				          }
                        $html .= '</tr>';
				  }
			  }
			  $html .= '<tr>
			              <td></td>  
			              <td></td>
			              <td></td>';
			              if($category_id=='1' || $category_id=='4')
    			              $html .= '<td></td><td></td>';
			 $html .= '<td><input type="text" name="total_std_gsm" value="" id="total_std_gsm" class="form-control"/></td>';
			 if($category_id=='4')
			        $html .= '<td><input type="text" name="sec_total_std_gsm" value="" id="sec_total_std_gsm" class="form-control"/></td>';
			        
			 $html .= '<td><input type="text" name="total_min_gsm" value="" id="total_min_gsm" class="form-control"/></td>';
			 if($category_id=='4')
			        $html .= '<td><input type="text" name="sec_total_min_gsm" value="" id="sec_total_min_gsm" class="form-control"/></td>';
			        
			 $html .= '<td><input type="text" name="total_max_gsm" value="" id="total_max_gsm" class="form-control"/></td>';
			 if($category_id=='4')
			        $html .= '<td><input type="text" name="sec_total_max_gsm" value="" id="sec_total_max_gsm" class="form-control"/></td>';
			 
			  
			  $html .= '</tr>';
			  $html .= '</tbody>';
			$html .= '</table>';
		  $html .= '</div>';
		 
		$html .= '</section>'; 
		 $html .= '</div>';
		echo $html;
}
 if($fun=='checkProductZipper')
{
	$product_id = $_POST['product_id'];

	
	$zipper_available = $obj_quality_report->checkProductzipper($_POST['product_id']);
//	$tintie_available = $obj_quotation->checkProductTintie($product_id);
//printr($zipper_available);
	//printr($tintie_available);
	
	$html ='<div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-9">';
					$zippers = $obj_quality_report->getActiveProductZippersByTintie();
					$ziptxt = '';
					foreach($zippers as $zipper){
                           //printr($zipper);
							   $ziptxt .= '<div   style="float:left;width: 200px;">';
							   		$ziptxt .= '<label  style="font-weight: normal;">';
									if($zipper_available== '0')
									{	
										if($zipper['product_zipper_id']=='2')
										{
											$ziptxt .= '<input type="radio" data-val="3" name="zipper" id="'.$zipper['product_zipper_id'].'" value="'.$zipper['product_zipper_id'].'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										}
										else
										{	
											$ziptxt .= '<input type="radio" data-val="4" name="zipper" id="'.$zipper['product_zipper_id'].'" value="'.$zipper['product_zipper_id'].'" onclick="showSize()" class="zipper"> '.$zipper['zipper_name'];// disabled="disabled"
										}
																			
										
									}
									else
									{ 
										if($zipper['product_zipper_id']=='1')
										{
											$ziptxt .= '<input type="radio" data-val="5" name="zipper" id="'.$zipper['product_zipper_id'].'" value="'.$zipper['product_zipper_id'].'"   checked="checked" onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										}
										else
										{
											$ziptxt .= '<input type="radio" data-val="6" name="zipper" id="'.$zipper['product_zipper_id'].'" class="zipper" value="'.$zipper['product_zipper_id'].'" onclick="showSize()">';
											$ziptxt .= ''.$zipper['zipper_name'];
										}
									}
									$ziptxt .= '</label>';
								$ziptxt .= '</div>';
                            }
							$html.= $ziptxt;                            
                        $html.='</div></div>';
	echo json_encode($html);

}
if($fun=='getActiveColor')
{	
	$clr = $obj_quality_report->getActiveColor($_POST['category_id']);
	//printr($clr);
	$html ='
				<label class="col-lg-3 control-label">COLOR</label>
                    <div class="col-lg-3">
						<select class="form-control validate[required]"  id="color_id" name="color_id" ">
						  <option value="0">Select Color</option>';
						   if(!empty($clr)){
								 foreach($clr as $color)  {
										
										$html .= '<option value="'. $color['pouch_color_id'].'">'.$color['color'].'</option>';
									}
							}
					   $html .= ' </select>
					</div>
				';
					
	echo json_encode($html);
}

?>