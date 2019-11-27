<?php
include("mode_setting.php");
$fun = $_GET['fun'];
$json=1;
if($_GET['fun']=='updateQuotationStatus') {	
	$quotation_id = $_POST['quotation_id'];
	$status_value = $_POST['status_value'];	
	if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) {
		$obj_quotation->$fun($quotation_id,$status_value);		
    }else{
		if($status_value == 0){
			$obj_quotation->$fun($quotation_id,$status_value);	
		}else{
			$json = 0;	
		}
	}	
	echo json_encode($json);	
}else if($_GET['fun']=='deleteQuotation') {	
	$quotation_id = $_POST['quotation_id'];
	$obj_quotation->$fun($quotation_id);	
	echo json_encode($json);
}else if($_GET['fun']=='deleteProductQuotation') {
	$product_quotation_price_id = $_POST['product_quotation_price_id'];
	$obj_quotation->$fun($product_quotation_price_id);	
	echo json_encode($json);
}else if($fun == 'setQuotation') {
	$obj_quotation->$fun();
	echo json_encode($json);
}else if($fun == 'getQuantity') {	
	$type = $_POST['type'];
	$quantity_type = $_POST['quantity_in'];
	$product_id = $_POST['product_id'];
	$data = $obj_quotation->$fun($type,$quantity_type);
	$gusset_available = $obj_quotation->checkProductGusset($product_id);	
	$response['gusset_available'] = $gusset_available;	
	$response['display'] = '';
	if($data){
		$count = 1;
		foreach($data as $item){
			$response['display'] .= '<div class="checkbox" style="float: left; width: 30%;">';
				$response['display'] .= '<label>';
					$response['display'] .= '<input type="checkbox" name="quantity[]" id="'.$count.'" value="'.encode($item['quantity']).'" class="validate[minCheckbox[1]]">'.$item['quantity'];
				$response['display'] .= '</label>';
			$response['display'] .= '</div>';
			$count++;		
		}
	}
	echo json_encode($response);
}else if($fun == 'getCurrencyValue'){
	$currency_price = $obj_quotation->$fun(base64_decode($_POST['currency_id']));
	echo $currency_price;
}
elseif($fun == 'getMaterialQuantity'){
	if(isset($_POST['material']) && !empty($_POST['material'])){
		$check = 0;
		$materialQuantity = array();
		foreach($_POST['material'] as $material_id){
			if($material_id){
				$getQuantity = $obj_quotation->getMaterialQuantity($material_id);

				if($getQuantity){
					$materialQuantity[$material_id] = $getQuantity;
				}else{
					$check++;
					break;
				}
			}else{
				$check++;
				break;
			}
		}
		if($check == 0){
			$final = $obj_quotation->aasort($materialQuantity);			
			asort($final);			
			if($final && !empty($final)){
				$html = '';
				foreach($final as $val){
					$html .= '<div class="checkbox" style="float: left; width: 30%;">';
						$html .= '<label>';
							$html .= '<input type="checkbox" name="quantity[]" value="'.encode($val).'" class="validate[minCheckbox[1]]">'.$val;
						$html .= '</label>';
					$html .= '</div>';
				}
				echo json_encode($html);
			}else{
				echo json_encode(false);
			}
		}else{
			echo json_encode(false);
		}
	}else{
		echo false;
	}
}else if($fun == 'checkProductGusset'){
	$gusset_available = $obj_quotation->checkProductGusset($_POST['product_id']);
	echo $gusset_available;
}else if($fun=='checkProductTintie') 
{
	$product_id = $_POST['product_id'];
	$data = $obj_quotation->checkProductTintie($product_id);
	$arr['response']=$data['tintie_available'];
	$arr['result']=$data['spout_pouch_available'];
	echo json_encode($arr);
}
else if($fun=='checkProductZipper')
{
	$product_id = $_POST['product_id'];
	$tintie = $_POST['tintie'];
	
	$zipper_available = $obj_quotation->checkProductzipper($_POST['product_id']);
	$tintie_available = $obj_quotation->checkProductTintie($product_id);
	$html ='<div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-9">';
					$zippers = $obj_quotation->getActiveProductZippersByTintie($tintie);
					$ziptxt = '';
					foreach($zippers as $zipper){
                           
							   $ziptxt .= '<div   style="float:left;width: 200px;">';
							   		$ziptxt .= '<label  style="font-weight: normal;">';
									if($zipper_available== '0')
									{	
										if($tintie_available['tintie_available'] == '0')
										{
											if($zipper['product_zipper_id']==2 || $zipper['product_zipper_id']==9)
											{
											$ziptxt .= '<input type="radio" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
											}
											else
											{											
											$ziptxt .= '<input type="radio" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()" class="zipper" disabled="disabled"> '.$zipper['zipper_name'];
											}
									
										}
										else
										{	
											if($zipper['product_zipper_id']==2 || $zipper['product_zipper_id']==9)
											{
												$ziptxt .= '<input type="radio" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
											}
											else
											{	
												$ziptxt .= '<input type="radio" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()" class="zipper" > '.$zipper['zipper_name'];
											}
										}									
										
									}
									else
									{ 
											
										if( $zipper['product_zipper_id']==2  || $zipper['product_zipper_id']==9)
										{
											$ziptxt .= '<input type="radio" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'"   checked="checked" onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										}
										else
										{
											$ziptxt .= '<input type="radio" name="zipper[]" id="'.$zipper['product_zipper_id'].'" class="zipper" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()">';
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
else if($fun == 'getWidthSuggestion')
{
	$gusset = '';
	$widthsuggestion = $obj_quotation->getGussetSuggestion($_POST['width'],$gusset,$_POST['product_id']);
	if($widthsuggestion)
	{	
		$a='';
		$i=0;
		$arr='';
		foreach($widthsuggestion as $width)
		{
			if($i>0)
			$a="mm or ";
			$arr .=$a.$width['width_to'];
			$i++;
		}
		echo json_encode($arr);
	}
	else
	{
		echo "Got";
	}
}
else if($fun == 'getGussetSuggestion')
{
	$gussetsuggestion = $obj_quotation->getGussetSuggestion($_POST['width'],$_POST['gusset'],$_POST['product_id']);
	$pricesuggestion = $obj_quotation->getToolPrice($_POST['width'],$_POST['gusset'],$_POST['product_id']);
	if($obj_session->data['LOGIN_USER_TYPE']==1){
		$userCurrency = $obj_quotation->getCurrencyInfo($obj_session->data['ADMIN_LOGIN_SWISS']);
		$userCurrency['currency_code'] = "INR";
		$userCurrency['tool_rate']='';
	}else{
		$userCurrency =  $obj_quotation->getUserWiseCurrency($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
	}
	if($userCurrency['currency_code'] == "INR")
	{
		$tool_price = $pricesuggestion;
	}
	else
	{
		if($userCurrency['tool_rate']){
			$tool_price = ($pricesuggestion / $userCurrency['tool_rate']);
		}
		else
		$tool_price = $pricesuggestion;
	}
	$a='';
	$arr='';
	if($gussetsuggestion)
	{
		$i=0;
		foreach($gussetsuggestion as $gusset)
		{
				$a="<p style='margin: 0px;padding: 0px;margin-top: 5px;'>Suggested Gusset: ";
			$b=" will be applicable";
			$arr .=$a.$gusset['gusset'].'mm  else tool price '.' '.$userCurrency['currency_code'].' '.$obj_quotation->numberFormate($tool_price,"2").$b.'
			</p>';
			$i++;
		}
		echo json_encode($arr);
	}
	else
	{
		echo "Got";
	}
}
else if($fun == 'getViewToolprice')
{
	$product_id = $_POST['product_id'];
	$tool_price = $obj_quotation->getViewToolprice($_POST['product_id']);
	if($tool_price)
	{
	$response = '';
	$response .= '<table class="table table-bordered">';
	$response .= '<thead>';
	$response .= '<tr>';
	$response .= ' <th colspan="4">Product Name : '.$tool_price[0]['product_name'].'</th>';
	$response .= '</tr>';
	$response .= '<tr>'; 
	$response .= ' <th>Width(form)</th>';
	$response .= ' <th>Width(to)</th>';
	if($tool_price[0]['gusset']>0)
	$response .= ' <th>Gusset</th>';
	//$response .= ' <th>Price</th>';
	$response .= '</tr>';
	$response .= '</thead>'; 
	$response .= '<tbody>'; 	
	foreach($tool_price as $tool)
	{		
		$response .= '<tr></tr><tr>';
		$response .= '<td>'.$tool['width_from'].'</td>';
		$response .= '<td>'.$tool['width_to'].'</td>';
		if($tool['gusset']>0)
		$response .= '<td>'.$tool['gusset'].'</td>';
		//$response .= '<td>'.$tool['price'].'</td>';
		$response .= '</tr>';					
	}	
		$response .= '</tbody>'; 
		$response .= '</table>';
		echo $response;
	}
	else
	{
		echo "No Record Found!";
	}
}
else if($fun == 'update_discount')
{
	$quantity_id = $_POST['quantity_id'];
	$discount = $_POST['discount'];
	$update_discount = $obj_quotation->update_discount($quantity_id,$discount);
	echo $update_discount;
}
else if($fun == 'getProductSize') {
	$product_id = $_POST['product_id'];
	$zipper_id = $_POST['zipper_id'];
	$make_id = $_POST['make_id'];
	//$spout_pouch = $_POST['spout_pouch'];
	//$data = $obj_quotation->getProductSize($product_id,$zipper_id,$make_id,$spout_pouch);
	$data = $obj_quotation->getProductSize($product_id,$zipper_id,$make_id);
	//printr($data);
	$response = '';	
		$response .='<select id="size" name="size" class="form-control validate[required]" onchange="customSize()"><option value="">Select Size</option>';
	if($data){	
		foreach($data as $item){
				//$response .= '<div class="checkbox " style="float: left;  width: 50%;"><label>';
					//$response .= '<input type="checkbox" name="size[]" class="test" onclick="removeValidation()" id="'.$item['size_master_id'].'" value="'.encode($item['size_master_id']).'" >'.$item['volume'].'['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']';
					$response .= '<option value="'.encode($item['size_master_id']).'">';
					if($item['volume']!=0 || (!is_numeric($item['volume'])))
				    	$response .=  $item['volume'];
				    	
					$response .= '['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']</option>';					
				//$response .= '</label></div>';
		}
	}
		if($make_id!=5)
		{
			$response .= '<option value="0">Custom</option></select>';
		}
	
	echo $response;
}
elseif($fun == 'update_price')
{
		$history_data = array(
		'product_quotation_price_id'=>$_POST['postArray']['product_quotation_price_id'],
		'price'=>(($_POST['postArray']['price']*$_POST['postArray']['qty'])*$_POST['postArray']['currency_price']),
		);
		$result1 = $obj_quotation->updatePrice($history_data);		
	echo $result1;
}
else if($fun == 'addQuotation') {
	parse_str($_POST['formData'], $post);
	///printr($post);//die;
		if(isset($post['quantity']) && !empty($post['quantity'])){
		$last_id = $obj_quotation->addQuotation($post);
	//printr($last_id);die;
		if($last_id == "Error"){
			$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
		}else{
			$obj_session->data['success'] = 'Your quotation generated!';
		$getData = " product_quotation_id,pq.added_by_user_id,pq.added_by_user_type_id, customer_name, shipment_country_id,pq.multi_product_quotation_id, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,pq.status,pq.quotation_status,discount";
		$data = $obj_quotation->getQuotation($last_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		$tax_type = $obj_quotation->CheckQuotationTax($last_id);
       // printr($data);
	   foreach($data as $dat)
	   {
		 $quantityData[] = $obj_quotation->getQuotationQuantity($dat['product_quotation_id']);
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
		$result='<input type="hidden" id="multi_quote_id" name="multi_quote_id" value="'.$last_id.'"/>';
		foreach($new_data as $k=>$qty_data)
		{
			$result.='<div class="form-group">
								<label class="col-lg-3 control-label">Price (By '.$k.')</label> 
								<div class="col-lg-9">
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
                                                <th>Quntity</th>';
                                              if($dat['quotation_type'] != 1){ 
                                                $result.='<th>Option</th>';
                                                } 
                                                $result.='<th>Dimension (Make Pouch)</th><th>Layer:Material:Thickness</th>';
                                               if($dat['quotation_status'] == 0){
													 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
													{ 				
													 if($dat['currency']=='INR')
												$result.='<th>Discount</th>';
													} }
                                                $result.='<th>Price / pouch</th>';
                                             /*  ' <th>Total</th>';
                                                if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){ 
                                                 $result.='<th> Price / pouch  With Tax </th>
                                                 <th>Total Price With Tax </th>';
                                                 } }
                                                $result.=' <th>Cylender Price</th>
                                                 <th>Tool Price</th>*/
                                              $result.= '</tr>
										  </thead>
                                          <tbody>';
                                          	 $i=1;
                                                foreach($qty_data as $skey=>$sdata){                                                  
                                                   $result.=' <tr>';                                                        
                                                        foreach($sdata as $soption){
															$result .='<tr><th>'.$skey.'</th>';
                                                            $result.=' <td>'. ucwords($soption['text']).'</td>';
                                                           $result.=' 
                                                                <td>'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.
																$soption['gusset']; if($soption['volume']!='') $result.=' ('.$soption['volume'].')'.' ('.$soption['make'].')'
																;
																$result.='</td><td>';
																
                          				for($gi=0;$gi<count($soption['materialData']);$gi++){
											  $result.= ($gi+1).' Layer : '.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
										} $result.='</td>';
							                                   if($dat['quotation_status'] == 0){
																	 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																{  if($dat['currency']=='INR')
																$result.='<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['product_quotation_quantity_id'].'" class="form-control" style="width: 100px;" ></td>'; } }
                                                                $result.='<td>';
																	   if($soption['discount'] && $soption['discount'] >0.000) {
                                                                $result.='<b>Total : </b>';$pretot= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3"); $result.= $pretot.'<br />
                                                                <b>Discount ('. $soption['discount'].' %) : </b>';
																$predis = $pretot*$soption['discount']/100; 
																$result.= $obj_quotation->numberFormate($predis,"3").'<br />
                                                                <b>Final Total : </b>'. $dat['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3"); 
																	 }else $result.= $dat['currency'].' '.
																	$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
                                                                $result.='</td>';
                                                                /*'<td>';
                                                                  if($soption['discount'] && $soption['discount'] >0.000) {
                                                                $result.='<b>Total : </b>'; $tot= $obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3"); $result.= $tot.'<br />
                                                                <b>Discount ('. $soption['discount'].' %) : </b>';
																 $dis = $tot*$soption['discount']/100; 
																$result.= $obj_quotation->numberFormate($dis,"3").'<br />
                                                                <b>Final Total : </b>
																'. $dat['currency'].' '.($tot-$dis);
																 } else $result.= $dat['currency'].' '.$obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");																 
                                                                $result.=' </td>';
                                                                  if($k=='pickup') {
																	  if($data[0]['shipment_country_id']==111) { 
                                                  				$result.='<td>
																	'. $dat['currency'].' '.$obj_quotation->numberFormate((($soption['totalPriceWithTax'] / $skey) / $dat['currency_price'] ),"3").'
                                                                 </td>
                                                                 <td>
																	'. $dat['currency'].' '.$obj_quotation->numberFormate(($soption['totalPriceWithTax'] / $dat['currency_price'] ),"3").'
                                                                 </td>';
                                                }  }$result.=' <td>'; if($soption['cylinder_price']>0) {$result.= (int)$soption['cylinder_price'];}else $result.='';$result.='</td>
                                                                 <td>'; if($soption['tool_price']>0) {$result.= (int)$soption['tool_price'];}
																 else $result.='';$result.='</td>	';*/														 
                                                          $result.= ' </tr>';                                                           
                                                        }                                                        
                                                    $result.='</tr>';
                                                     $i++;
                                                }
                                            $result.='</tbody>
										</table>
									  </div>
									</section> 
								</div>
							  </div>';
				}
		}
	}else{
		$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
	}
	 echo $result;
}
else if($fun == 'getstocktemplateper') {
	$getcurrency=$obj_quotation->getstocktemplateper($_POST['user_name']);
	$currency_name=$obj_quotation->getcurrency_name($getcurrency['default_curr']);
	$arr_val['response']=$getcurrency['default_curr'];
	$arr_val['result']=$currency_name['currency_code'];
	echo json_encode($arr_val);
	
}
else if($fun == 'data'){
    $quotation_id = $_POST['quotation_id'];
    $getData = " product_quotation_id,pq.added_by_user_id,pq.added_by_user_type_id, customer_name, shipment_country_id,pq.multi_product_quotation_id, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,pq.status,pq.quotation_status,discount";
	$data = $obj_quotation->getQuotation($quotation_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
       
      
      
    $response=$obj_quotation->getdataexcel($data);
	echo $response;
}
?>