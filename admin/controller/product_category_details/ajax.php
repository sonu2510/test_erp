<?php
include("mode_setting.php");
$fun = $_GET['fun'];
$json=1;

if($fun == 'getProductSize') {
//	printr($_POST);	die;
	$product_id = $_POST['product_id'];
	$response='';
	$data = $obj_catalogue_category->getProductSize($product_id);
	   	$response .=' <div class="form-group">';
             	$response .='   <label class="col-lg-3 control-label">Select Size </label>';
              	$response .='  <div class="col-lg-8">';
                	
                   
                	$response .='	<div class="form-control scrollbar scroll-y" style="height:200px" id="groupbox">';
                      
						
						 $selected = array();								  
                        if(isset($color['size_master_id']) && $color['size_master_id']){
                            $selected=explode(",",$color['size_master_id']);
                        }
						$data = $obj_catalogue_category->getProductSize($product_id);
					//	printr($selected);die;
						 
						   
                        foreach($data as $item){ 
								$response .='<div class="checkbox" >'; 
									$response .='<label class="checkbox-custom" >';
								if(in_array($item['size_master_id'],$selected)){
										$response .='<input type="checkbox" checked="checked" name="size_master_id[]" id="'.$item['size_master_id'].'" value="'.$item['size_master_id'].'" onchange="toggleCheckbox(this)"> ';
								}else{
										$response .='<input type="checkbox" name="size_master_id[]" id="'.$item['size_master_id'].'" value="'.$item['size_master_id'].'" onchange="toggleCheckbox(this)"> ';
								}
								$response .='<i class="fa fa-square-o" id="cust_checkbox_'.$item['size_master_id'].'"></i> '.$item['volume'].'['.$item['zipper_name'].']['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].'] </label>';
							$response .='</div>';
						}
					
                 	$response .='</div>';
                 	$response .=' <a class="btn btn-default btn-xs selectall mt5" href="javascript:void(0);">Select All</a>';
                  	$response .='  <a class="btn btn-default btn-xs unselectall mt5" href="javascript:void(0);">Unselect All</a>';    
                	$response .='</div>';
             	$response .=' </div>';
             	
             	echo $response;
}
/*if($fun == 'getProductSize') {
//	printr($_POST);	die;
	$product_id = $_POST['product_id'];
	$html='';
	$color = $obj_catalogue_category->getCategoryColor($product_id);
	   	      $html .='<div class="form-group">';
               $html .='      <label class="col-lg-2 control-label"><span class="required">*</span>Color </label>';
				 $html .='        <div class="col-lg-8">';
    			$html .='		<script src="http://swissonline.in/js/select2.min.js"></script>';
    			$html .='		<script src="http://swissonline.in/js/chosen.jquery.min.js"></script>';
    				
    			$html .='<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>';
    				 $machines = $obj_slitting->getMachine();
    				
    				$html .='<select data-placeholder="Begin typing a Machine name to filter..." multiple class="chosen-select form-control select2-container select2-container-multi" name="machine_id[]">';
                                	      
                            foreach($color as $c){ 
						        	$html .='<option value="'.$machine['machine_id'].'" >'.$machine['machine_name'].'</option>';
						        } 
                                
		          $html .='	</select>';
    		                
		       $html .=' 	</div>';
			   $html .=' </div>';
             	
	echo $html;
}*/ 


if($fun=='getCategoryColor') {
    	$html=''; 
    $color_catagory_id = $_POST['color_catagory_id'];
    $colors = $obj_catalogue_category->getCategoryColor($color_catagory_id);
     if($colors){
	
          	  $html .=' <div class="form-group option">';
                 $html .='    <label class="col-lg-3 control-label">Color</label>';
                 $html .='    <div class="col-lg-9">';
                     
                       $spoutsTxt = '';
					   $i=0;
                        foreach($colors as $color){
							//printr($color);
							//die;
                           $spoutsTxt .= '<div style="float:left;width: 150px;">';
                                $spoutsTxt .= '<label  style="font-weight: normal;">';
								if($color['pouch_color_id'] == 1 )
								{
                                    $spoutsTxt .= '<input type="checkbox" id="color'.$i.'" name="color[]" value="'.$color['pouch_color_id'].'" checked="checked" class="colortemp" >';
								}
								else 
								{
									$spoutsTxt .= '<input type="checkbox" id="color'.$i.'" name="color[]" value="'.$color['pouch_color_id'].'" class="colortemp" >';
								}
                                $spoutsTxt .= ''.$color['color'].'</label>';
                            $spoutsTxt .= '</div>';
							$i++;
                        }
                       $html .= $spoutsTxt;
                       
                    $html .=' </div>'; 
                      $html .=' <div class="form-group option">';
                        $html .='    <label class="col-lg-3 control-label"></label>';
                        $html .='    <div class="col-lg-9">';
                        $html .='<a  id="btn-all-check" class="label bg-success selectall1 mt5"  >Select All</a>';
                         $html .='<a id="btn-all-uncheck" class="label bg-warning unselectall1 mt5" >Unselect All</a>';
                         
               $html .='  </div>';
               $html .='  </div>';
               $html .='  </div>';
                      	  
		} 
			echo $html;

}if($fun=='updateStatus') {
	
	$color_id = $_POST['color_id'];
	$status_value = $_POST['status_value'];
	
	$obj_catalogue_category->$fun($color_id,$status_value);

}
?>