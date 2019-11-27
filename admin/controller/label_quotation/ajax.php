<?php //ruchi 30/4/2015 changes for price uk
include("mode_setting.php");

$fun = $_GET['fun'];
$json=1;
if($fun == 'client_name'){
	$client_name = $_POST['client_name'];
	$result = $obj_label_quotation->getClientName($client_name);
	echo json_encode($result);
}
if($fun == 'getProductSize') {
	$product_id = $_POST['product_id'];
	$make_id = $_POST['make_id'];
	$size_type_mailer='';
	if($product_id=='10')
	{
		$size_type_mailer = $_POST['size_type'];
	} 
	$data = $obj_label_quotation->getProductSize($product_id,$make_id);
	$response = '';	
		$response .='<select id="size" name="size" onchange="customSize()" class="form-control validate[required]" ">
		                <option value="">Select Size</option>';
                        	if($data){	
                        		foreach($data as $item){
                        		    $response .= '<option value="'.$item['size_master_id'].'" weight="'.$item['weight'].'" zipper= "'.$item['product_zipper_id'].'"  volume = "'.$item['volume'].'">';
                        			if($item['volume']!=0)
                        			$response .=  $item['volume'];
                        			if($size_type_mailer=='mm')
                        			{
                        				$width=round($item['width']*25.4);
                        				$height=round($item['height']*25.4);
                        				$gusset=round($item['gusset']*25.4);
                        				$mea = 'mm';
                        				$widht='(W)';
                        				$h='(H)'; 
                        				$flp='(Flap)';
                        			}
                        			else
                        			{ 
                        				$width=$item['width'];
                        				$height=$item['height'];
                        				$gusset=$item['gusset'];
                        				$mea=$widht=$h=$flp='';
                        				if($product_id=='10')
                        				{
                        					$mea = 'inch';
                        					$widht='(W)';
                        					$h='(H)';
                        					$flp='(Flap)';
                        				}
                        			}
                        			$response .= '['.$width.' '.$mea.' '.$widht.' X '.$height.' '.$mea.' '.$h.' X '.$gusset.' '.$mea.' '.$flp.'] ['.$item['zipper_name'].']</option>';					
                        		}
                        		
                        	}
                      	$response .= '<option value="0">Custom</option>';	
	$response .= '</select>';
	echo $response;
}
if($fun == 'getStickerSize') {
  //  printr($_post);
	$product_id = $_POST['product_id'];
	$size_master_id = $_POST['size_master_id']; 
	$shape_id = $_POST['shape_id'];
	$sup_window = $_POST['sup_window'];
 //	printr($sup_window);
   
	$data = $obj_label_quotation->getStickerSize($product_id,$size_master_id,$shape_id,$sup_window);

	$response = '';	
		$response .='<select id="sticker_size" name="sticker_size" onchange="customStickerSize()" class="form-control validate[required]" ">
		                <option value="">Select Size</option>';
                        	if($data){ 
                        		    $response .= '<option value="'.$data['label_size_master_id'].'" max_width="'.$data['max_width'].'" max_height="'.$data['max_height'].'" min_height="'.$data['min_height'].'" min_width= "'.$data['min_width'].'">';
                        		    $response .= $data['max_width'].'X'.$data['max_height'].'</option>';
                        	    	//$response .= '<option value="0" min_height="'.$data['min_height'].'" min_width= "'.$data['min_width'].'">Custom</option>';	
                        	}
                            $response .= '<option value="0" min_height="'.$data['min_height'].'" min_width= "'.$data['min_width'].'">Custom</option>';
	$response .= '</select>';
	echo $response;
}
if($fun == 'getmaterial') {
	$make_pouch = $_POST['make_pouch']; 
	$label_materials = $obj_label_quotation->getLabelSheetmaterial($make_pouch);
//	printr($make_pouch); 
	//printr($label_materials); 
	$response = '';	 
		$response .= '<select name="material" id="material" class="form-control validate[required] material_drop">';
                                      	$response .= '<option value="">Select material</option> ';
                                             foreach($label_materials as $material){
                                                     	$response .= '<option value="'.$material['sheet_id'].'" s_id="'.$material['sheet_id'].'" height="'.$material['height'].'" width= "'.$material['width'].'" >'.$material['sheet_name'].'</option>';
                                                   
                                            }  
                                           
      	$response .= '</select>';
      	
  //    	printr($response);
	echo $response;
}
if($fun == 'getQtyandeffects') {
	$sheet_id = $_POST['sheet_id']; 
	$data = $obj_label_quotation->getQtyandeffects($sheet_id);
	$qty = $obj_label_quotation->getLabelQty($data['qty']);
	$printing_effect = $obj_label_quotation->getLabelEffect($data['printing_effect']);
     $effect_html=$qty_html='';
	if($qty){
        	foreach($qty as $item){
        	    
        			$qty_html .= '<div class="checkbox" style="float: left; width: 30%;" id="quantity"'.$item['quantity'].'>';
            			$qty_html .= '<label id="'.$item['quantity'].'">';
            				$qty_html .= '<input type="checkbox" name="quantity[]" value="'.$item['quantity'].'" class="validate[minCheckbox[1]] chk_qty">'.$item['quantity'].'  ';
            			$qty_html .= '</label>';
            		$qty_html .= '</div>'; 
    		}
    	}
    if($printing_effect){
        	foreach($printing_effect as $effect){
        	    
        			$effect_html .= '<div class="checkbox" style="float: left; width: 30%;" id="effect"'.$effect['quantity'].'>';
            			$effect_html .= '<label id="'.$effect['printing_effect_id'].'">';
            				$effect_html .= '<input type="checkbox" name="effect[]" value="'.$effect['printing_effect_id'].'" class="validate[minCheckbox[1]]">'.$effect['effect_name'].'';
            			$effect_html .= '</label>';
            		$effect_html .= '</div>';
    		}
    	}
 
   $pro_arr=array(); 
    $pro_arr['qty']=$qty_html;
	$pro_arr['effect']=$effect_html;

	 
	echo json_encode($pro_arr);	

}
 //commented by kinjal on 27-09-2019 (nandanbhai don't want show when generate quote)
if($fun == 'calculate_sheet') {
	$sheet_id = $_POST['sheet_id'];
	$make_pouch = $_POST['make_pouch'];
    $sticker_width = $_POST['sticker_width'];
    $sticker_height = $_POST['sticker_height'];
	  $formula=$sheet_id=array(); 
	$label_materials = $obj_label_quotation->getLabelSheetmaterial($make_pouch);
	//	$data = $obj_label_quotation->getQtyandeffects($sheet_id);
    $result='';
    $result.=' <div class="form-group" id="material_dropdown">
                              <label class="col-lg-3 control-label"><span class="required">*</span>No Of Sticker Per Sheet</label>
                               <div class="col-lg-4" id="no_of_sticker_div">
										<section class="panel">
										  <div class="table-responsive">
											<table class="table table-striped b-t text-small">
											  <thead>
												  <tr>
											        	<th>Sheet Name</th> 
                                                         <th>Sticker Per Sheet</th>
                                                  <tr>  
                                             </thead>';

                                $result.='<tbody'; 
                                 if($sticker_width!=0){
                                    	foreach($label_materials as $data){
                                    	   
                                               // printr($data['sheet_id']);
                                               
                                                    $sheet_width=$data['width'];
                                                    $sheet_height=$data['height'];
                                                    $sheet_left_margint=$data['left_margin'];
                                                    $sheet_right_margin=$data['right_margin'];
                                                    $sheet_header_margin=$data['header_margin'];
                                                    $sheet_footer_margin=$data['footer_margin'];
                                                    $sticker_between_stickers=$data['between_stickers']; 
                                                
                                                
                                                    $calculate_sheet_width=$sheet_width-($sheet_left_margint+$sheet_right_margin);
                                                    $calculate_sheet_height=$sheet_height-($sheet_footer_margin+$sheet_header_margin);
                                                    
                                                    $calculate_sticker_width=$sticker_width+($sticker_between_stickers);
                                                    $calculate_sticker_height=$sticker_height+($sticker_between_stickers);
                                                 
                                                    $row=intval($calculate_sheet_width/$calculate_sticker_width);
                                                    $col=intval($calculate_sheet_height/$calculate_sticker_height);
                                                  
                                                  
                                                    $no_of_sticker=$row*$col; 
                                                   
                                                    
                                                    /*$formula['$sticker_width']=$sticker_width;
                                                    $formula['$sticker_height']=$sticker_height;
                                                    $formula['$make_pouch']=$make_pouch;
                                                  //  $formula['$qty']=$qty;
                                                    $formula['$sheet_width']=$sheet_width;
                                                    $formula['$sheet_height']=$sheet_height;
                                                    $formula['$sheet_left_margint']=$sheet_left_margint;
                                                    $formula['$sheet_right_margin']=$sheet_right_margin;
                                                    $formula['$sheet_header_margin']=$sheet_header_margin;
                                                    $formula['$sheet_footer_margin']=$sheet_footer_margin;
                                                    $formula['$sticker_between_stickers']=$sticker_between_stickers;
                                                    $formula['$calculate_sheet_width']=$calculate_sheet_width;
                                                    $formula['$calculate_sheet_height']=$calculate_sheet_height;
                                                    $formula['$calculate_sticker_width']=$calculate_sticker_width;
                                                    $formula['$calculate_sticker_height']=$calculate_sticker_height;
                                                    $formula['$row']=$row;
                                                    $formula['$col']=$col;
                                                    $formula['$no_of_sticker']=$no_of_sticker;*/
                                               
                                                     /*$result.=' <tr>';
                    									    	$result.='<td>'.$data['sheet_name'].'</td>';
                                                                $result.='<td>'.$no_of_sticker.'</td>';
                                                     $result.='</tr>';*/
                                                        $sheet_id[$data['sheet_id']] = $no_of_sticker;
                              }
                         }else{
                            //$result.=' <tr>'; 	$result.='<td colspan="2">Please Select Sticker Size option </td>';     $result.='</tr>';  
                         } 
                        /*$result.='<tbody'; 
                       $result.='</table>'; 
                  $result.='</div>'; 
              $result.='</section>'; 
             $result.='</div>'; 
        $result.='</div>';*/ 
       
     $sheet_id_of_max_sticker = array_keys($sheet_id, max($sheet_id));  ///printr($sheet_id_of_max_sticker[0]);
    echo $sheet_id_of_max_sticker[0];

}

if($fun == 'addQuotation') 
{
    parse_str($_POST['formData'], $post);
    //printr($post);die;
    if(isset($post['quantity']) && !empty($post['quantity'])){ 
	    $lable_quotation_id = $obj_label_quotation->addQuotation($post);
	    $data = $obj_label_quotation->getQuotation($lable_quotation_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
	     foreach($data as $dat)
    	   {
    	       $quantityData[] = $obj_label_quotation->getQuotationQuantity($dat['label_quotation_product_id']);
    	   } 
    	   	foreach($quantityData as $k=>$qty_data)
    		{
    			foreach($qty_data as $tag=>$qty)
    			{
    				foreach($qty as $q=>$arr)
    				{
    					$new_data[$tag][$q][]=$arr[0];
    				}
    			}	
    		}
    	  if($new_data)
          { 
                $response .= '<input type="hidden" id="lable_quotation_id" name="lable_quotation_id" value="'.$lable_quotation_id.'"/>';
            	    foreach($new_data as $key=>$data1)
            	    {   //printr($key);
            	       
                        	    $response.='<div class="form-group table-responsive" >
    								<label class="col-lg-3 control-label">Price (By '.$key.')</label> 
    								<div class="col-lg-9">
    									<section class="panel">
    									  <div class="table-responsive" style="width:100%;">
    										<table class="table table-striped b-t text-small">
    										  <thead>';
                                                 $response .= '<tr>';
                                                    $response .= ' <th>Quntity</th>';
                                                    $response .= ' <th>Option</th>';
                                                    $response .= '<th>Sticker Size</th>';
                                                    $response .= '<th>Material(Sheet )</th>';
                                                    $response .= '<th>Sticker Price</th>';
                                                    $response .= '<th>Total AMT</th>';
                                                $response .= '</tr>'; 
                        	    
                                	            foreach($data1 as $key1=>$qty_data)
                    	                        {
                    	                          foreach($qty_data as $key2=>$details)
                                    	          {
                                    	                    $response .= '<tr>';
                                                    		               $response .= ' <td>'.$details['quantity'].'</td>';
                                                        	               $response .= ' <td>'.$details['product_name'].','.$details['shape_name'].'<br><br><b>('.$details['printing_effect_detail'].')</b></td>';
                                                        	               $response .= ' <td>'.$details['volume'].'['.$details['sticker_width'].'X'.$details['sticker_height'].']<b>('.$details['make_name'].')</b></td>';
                                                        	               $response .= ' <td>'.$details['sheet_name'].'['.$details['sheet_width'].'X'.$details['sheet_height'].']<br><b>No. of Stickers per sheet : </b>'.$details['no_of_sticker_per_sheet'].'</td>';
                                                        	               $response .= ' <td>'.number_format($details['price_per_label'] / $details['product_rate'],"3", '.', '').' <b>'.$details['currency_code'].'</b></td>';
                                                        	               $response .= ' <td>'.number_format($details['total_amount']/ $details['product_rate'],"3", '.', '').' <b>'.$details['currency_code'].'</b></td>';
                                                    	    $response .= '</tr>';
                                                  }  
                    	                        }
            	                  
                        	       $response.='</tbody>
    										</table>
    									  </div>
    									</section> 
    								</div>
    							  </div>';
            	     }
         }
    }
   
    echo $response;
}
if($fun == 'getProduct'){
	$make_id = $_POST['make'];
	$products = $obj_label_quotation->getProduct($make_id);
	$response = '';
    $response .= '<label class="col-lg-3 control-label"><span class="required">*</span>Select Product</label>
                    <div class="col-lg-4">
                        <select name="product" id="product" class="form-control validate[required]" onchange="product_info()">
                            <option value="">Select Product</option>';
                                foreach($products as $product){
                                        if(isset($post['product']) && $post['product'] == $product['product_id']){
                                            $response .='<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                        }else{
                                            $response .= '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                        }
                                } 
            $response .='</select>
                    </div>';
	
	echo $response;
}
else if($fun=='getcurrency')
{
	$sec_curr=$obj_quotation->getcurrencyformail($_POST['user_id'],$_POST['user_type_id']);
	//printr($sec_curr);
	$arr['response']=$sec_curr['secondary_currency'];
	if(isset($sec_curr['currency_code']))
		$arr['result']=$sec_curr['currency_code'];
	if(isset($sec_curr['price']))
		$arr['price']=$sec_curr['price'];
	echo json_encode($arr);


}
?>