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
	$currency_price = $obj_quotation->$fun($_POST['currency_id']);
	echo $currency_price;
}elseif($fun == 'getMaterialQuantity'){
	//printr($_POST);//die;
	//echo ($material_id);
	if(isset($_POST['material']) && !empty($_POST['material'])){
		$check = 0;
		$materialQuantity = array();
		foreach($_POST['material'] as $material_id){
			if($material_id){
				$getQuantity = $obj_quotation->getMaterialQuantity($material_id,$_GET['qty']);
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
				$i=1;
				foreach($final as $val){
					$html .= '<div class="checkbox" style="float: left; width: 30%;" id="div_check_'.$val.'">';
						$html .= '<label id='.$val.'>';
							$html .= '<input type="checkbox" name="quantity[]" value="'.encode($val).'" class="validate[minCheckbox[1]]">'.$val;
						$html .= '</label>';
					$html .= '</div>';
					$i++;
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
	
	$a='';
	$arr='';
	if($gussetsuggestion)
	{
		$i=0;
		
		foreach($gussetsuggestion as $gusset)
		{
			if($userCurrency['tool_rate']){
				$tool_price =($gusset['price']/$userCurrency['tool_rate']);
			}
			else
				$tool_price = $gusset['price'];
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
	//printr($_POST);die;
	$product_id = $_POST['product_id'];
	$zipper_id = $_POST['zipper_id'];
	$make_id = $_POST['make_id'];
	$spout_pouch = $_POST['spout_pouch'];
	$size_type_mailer='';
	if($product_id=='10')
	{
		$size_type_mailer = $_POST['size_type'];
	}
	if($product_id=='4')
	{
		if($make_id=='3')
			$product_id = '15';
			//echo "first";
	}
	else if($product_id=='3')
	{
		if($make_id=='3')
			$product_id = '14';
	   
	   if($make_id=='4')
		    $product_id = '42';
			
		
			//echo "sec";
	
	}
	else
	{
		$product_id = $_POST['product_id'];
		//echo "third";
	}
	//echo $size_type_mailer;die;
	$data = $obj_quotation->getProductSize($product_id,$zipper_id,$make_id,$spout_pouch);
	$response = '';	
		$response .='<select id="size" name="size" class="form-control validate[required]"  onchange="customSize()"><option value="">Select Size</option>';
	if($data){	
		foreach($data as $item){
				//$response .= '<div class="checkbox " style="float: left;  width: 50%;"><label>';
					//$response .= '<input type="checkbox" name="size[]" class="test" onclick="removeValidation()" id="'.$item['size_master_id'].'" value="'.encode($item['size_master_id']).'" >'.$item['volume'].'['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']';
					$response .= '<option value="'.encode($item['size_master_id']).'">';
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
					
					
					$response .= '['.$width.' '.$mea.' '.$widht.' X '.$height.' '.$mea.' '.$h.' X '.$gusset.' '.$mea.' '.$flp.']</option>';					
				//$response .= '</label></div>';
		}
	}
		if($product_id!='10')
		{
			/*if($make_id!=5)
			{*/
			if($product_id != '14'  && $product_id != '55' && $product_id != '56' && $product_id != '57')
			{
				if($product_id != '15') 
					$response .= '<option value="0">Custom</option></select>';
			}
			/*}
			else if($_SESSION['LOGIN_USER_TYPE']=='2' && $_SESSION['ADMIN_LOGIN_SWISS']=='34')
			{*/
			
				//$response .= '<option value="0">Custom</option></select>';
			//}
		}
	
	echo $response;
}
else if($fun == 'addQuotation') {

	parse_str($_POST['formData'], $post);
	//printr($post);//die;
	if($_POST['printing_val']=='no gusset')
		$_POST['printing_val']='';
	$post['gusset_printing_option']=$_POST['printing_val'];
	//printr($_POST['printing_val']);die;
	//make_id = 7 offline and online make_id= 6
	if($post['make']=='6')
	{
		$post['material']=array($post['material'][0],'23');
		/*$post['layer']='Mw==';
		$material='12';
		$material2='16';
		//$post['material']=array($post['material'][0],$material,$post['material'][1]);
		$post['material']=array($post['material'][0],$material,$material2);
		$thickness='12.000';
		$thickness2='60.000';
		//$post['thickness']=array($post['thickness'][0],$thickness,$post['thickness'][1]);
		$post['thickness']=array($post['thickness'][0],$thickness,$thickness2);*/
	}
	//printr($post);//die;
	
	if(isset($post['quantity']) && !empty($post['quantity'])){
		$last_id = $obj_quotation->addQuotation($post);
		//echo $last_id;die;
		if($last_id == "Error"){
			$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
		}else{
			$obj_session->data['success'] = 'Your quotation generated!';
		$getData = " product_quotation_id,pq.added_by_user_id,pq.added_by_user_type_id, customer_name, shipment_country_id,pq.multi_product_quotation_id, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,pq.status,pq.quotation_status,discount";
		$data = $obj_quotation->getQuotation($last_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		$tax_type = $obj_quotation->CheckQuotationTax($last_id);

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
									  <div class="table-responsive" style="width:100%;">
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
                                                $result.='<th>Price / pouch</th>
                                                <th>Total</th>';
                                                if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){ 
                                                 $result.='<th> Price / pouch  With Tax </th>
                                                 <th>Total Price With Tax </th>';
                                                 } }
                                                $result.=' <th>Cylinder Price</th>
                                                 <th>Tool Price</th>';
												 if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){
														$result.='<th>Cylinder Price  With Tax </th>
																 <th>Tool Price With Tax </th>';	
													}
												}
                                              $result.=' </tr>
										  </thead>
                                          <tbody>';
                                          	 $i=1;
                                                foreach($qty_data as $skey=>$sdata){                                                  
                                                   $result.=' <tr>';                                                        
                                                        foreach($sdata as $soption){
															$result .='<tr><th>'.$skey.'</th>';
                                                            $result.=' <td>'. ucwords($soption['text']).' (<b>'.$soption['printing_effect'].'</b>) </td>';
                                                           $result.=' 
                                                                <td>'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.
																$soption['gusset']; if($soption['volume']!='') $result.=' ('.$soption['volume'].')'.' ('.$soption['make'].')'
																;
																$result.='</td><td>';
																
																
																/*if($soption['make_id']=='6')
																	{
																 		$j=1;
																		 for($gi=0;$gi<count($soption['materialData']);$gi++)
																		 {		
																				
																				if($soption['materialData'][$gi]['material_id']!='12' && $soption['make_id']=='6')
																				{
																					if($j=='2')
																					{
																						$soption['materialData'][$gi]['material_name'] = 'oxo-Biodegradable PE';
																						$soption['materialData'][$gi]['material_thickness'] = '80';
																					}
																						$result.= '<b>'.($j).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
																						$j++;		
																				}
											//Calculated Layer Price : '.$soption['materialData'][$gi]['layer_wise_price'].'	  
																		  }
																	}
																	else
																	{*/
																		for($gi=0;$gi<count($soption['materialData']);$gi++)
																			 {
																					$result.= '<b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
												//Calculated Layer Price : '.$soption['materialData'][$gi]['layer_wise_price'].'	  
																			  }
																	
																	//}
																	
																	
                          				/*for($gi=0;$gi<count($soption['materialData']);$gi++){
											  $result.= ($gi+1).' Layer : '.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
										}*/ $result.='</td>';
							                                   if($dat['quotation_status'] == 0){
																	 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																{  if($dat['currency']=='INR')
																$result.='<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['product_quotation_quantity_id'].'" class="form-control" style="width: 100px;" ></td>'; } }
                                                                $result.='<td>';
																	   if($soption['discount'] && $soption['discount'] >0.000) {
                                                                $result.='<b>Total : </b>';
																
																$pretot= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");										//}
																/*else
																{
																$extra_p_d= $obj_quotation->numberFormate(((($soption['totalPrice']*15/100)/ $skey) / $dat['currency_price']),"3");
																$normal_val=$pretot= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
																$pretot=$extra_p_d+$normal_val;
																
																}*/
																 $result.= $pretot.'<br />
                                                                <b>Discount ('. $soption['discount'].' %) : </b>';
																$predis = $pretot*$soption['discount']/100; 
																$result.= $obj_quotation->numberFormate($predis,"3").'<br />
                                                                <b>Final Total : </b>'. $dat['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3"); 
																	 }else 
																	 {
																	
																	/* if($soption['size_id']=='0')
																	 {
																	 $extra_profit=$obj_quotation->numberFormate(((($soption['totalPrice'] / $skey) / $dat['currency_price'])*15/100),"3");
																	 $normal_profit=$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
																	 $final_val=$extra_profit+$normal_profit;
																	 $result.= $dat['currency'].' '.$final_val;
																	 
																	}
																	else
																	{*/
																		$result.= $dat['currency'].' '.
																	$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");																//}
																	}
                                                                $result.='</td>
                                                                <td>';
                                                                  if($soption['discount'] && $soption['discount'] >0.000) {
                                                                $result.='<b>Total : </b>'; 
																/*if($soption['size_id']!='0')
																{*/
																$tot= $obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");
																/*}
																else
																{
															$norm=$obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");
															$extr=$obj_quotation->numberFormate((($soption['totalPrice']*15/100) / $dat['currency_price'] ),"3");
																$tot=$norm+$extr;
																}*/
																 $result.= $tot.'<br />
                                                                <b>Discount ('. $soption['discount'].' %) : </b>';
																 $dis = $tot*$soption['discount']/100; 
																$result.= $obj_quotation->numberFormate($dis,"3").'<br />
                                                                <b>Final Total : </b>
																'. $dat['currency'].' '.($tot-$dis);
																 } else 
																 {
																	/*if($soption['size_id']=='0')
																	{
																	$ex_p=$obj_quotation->numberFormate((($soption['totalPrice']*15/100) / $dat['currency_price'] ),"3");
																	$normal=$obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");	
																	$final_ex_profit=$ex_p+$normal;
																 $result.= $dat['currency'].' '.$final_ex_profit;
																 
																 	}
																 	else
																	{*/
																	$result.= $dat['currency'].' '.$obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");	
																	//}
																 }										 
                                                                $result.=' </td>';
                                                                  if($k=='pickup') {
																	  if($data[0]['shipment_country_id']==111) { 
                                                  				$result.='<td>
																	'. $dat['currency'].' '.$obj_quotation->numberFormate((($soption['totalPriceWithTax'] / $skey) / $dat['currency_price'] ),"3").'
                                                                 </td>
                                                                 <td>
																	'. $dat['currency'].' '.$obj_quotation->numberFormate(($soption['totalPriceWithTax'] / $dat['currency_price'] ),"3").'
                                                                 </td>';
                                                						} 
																	 }
																$result.=' <td>'; if($soption['cylinder_price']>0) {$result.= (int)$soption['cylinder_price'];}else $result.='';$result.='</td>
																	 <td>'; if($soption['tool_price']>0) {$result.= (int)$soption['tool_price'];}
																	 else $result.='';$result.='</td>';
																if($k=='pickup') {
																	  if($data[0]['shipment_country_id']==111) { 	
																		
																		$result.='<td>';
																			if($soption['cylinder_price']>0) { $result.=(int)$soption['cylinder_price_withtax'] ;} else '';
																		$result.='</td><td>';
																			if($soption['tool_price']>0) { $result.=(int)$soption['tool_price_withtax'] ;} else '';	  
																		$result.='</td>';	
																	  }
																}														 
                                                            $result.='</tr>';                                                           
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
else if($fun == 'getGussetPrinting') {
	
	$product_id = $_POST['val'];
	$data = $obj_quotation->getProduct($product_id);	
	$response = '';	
	if(isset($data) && $data['printing_option']==1){	
		$printing_option_type=explode(',',$data['printing_option_type']);
		$response.='<div class="form-group">
                <label class="col-lg-3 control-label">(9) Gusset Printing Option</label>
                <div class="col-lg-9">';
		$i=1;
		foreach($printing_option_type as $val)
		{
			$qty = $obj_quotation->getQuantityById($data[$val.'_min_qty']);
			if($i==1) $ch='checked="checked"'; else { $ch='';}
			if($product_id=='3') $ch='checked="checked"';
			if($val=='both')
			$val='Bottom / Side';
		 	$response .='<div class="radio">
                      	<label>
                        <input type="radio" class="printing_option_type" name="printing_option_type[]" onclick="print_change()" id="pType'.$i.'" data-val="Front & Back  +  '.ucfirst($val).' Gusset Printing" value="'.$qty['quantity'].'" '. $ch.'> Front & Back  +  '.ucfirst($val).' Gusset Printing </label>
                    </div>';
			$i++;
		}
		$response.='</div>
              </div>';		
	}
	else
	{
		$response ='';	
	}
	echo $response;
}
else if($fun=='checkProductTintie') 
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
//printr($zipper_available);
	//printr($tintie_available);
	
	$html ='<div class="form-group option"> <label class="col-lg-3 control-label">(12) Zipper</label>
                        <div class="col-lg-9">';
					$zippers = $obj_quotation->getActiveProductZippersByTintie($tintie);
					$ziptxt = '';
					foreach($zippers as $zipper){
                           //printr($zipper);
							   $ziptxt .= '<div   style="float:left;width: 200px;">';
							   		$ziptxt .= '<label  style="font-weight: normal;">';
									if($zipper_available== '0')
									{	
										
										if($tintie_available['tintie_available']== '0')
										{
											
											if($zipper['product_zipper_id']=='2' || $zipper['product_zipper_id']=='9')
											{
											    $ziptxt .= '<input type="radio" data-val="1" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
											}
											else
											{											
										    	$ziptxt .= '<input type="radio" data-val="2" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()" class="zipper" disabled="disabled"> '.$zipper['zipper_name'];
											}
									
										}
										else
										{	
											if($zipper['product_zipper_id']=='2' || $zipper['product_zipper_id']=='9')
											{
												$ziptxt .= '<input type="radio" data-val="3" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
											}
											else
											{	
												$ziptxt .= '<input type="radio" data-val="4" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()" class="zipper"> '.$zipper['zipper_name'];// disabled="disabled"
											}
										}									
										
									}
									else
									{ 
										if($product_id == '55')
										{
											if($zipper['product_zipper_id']=='3' || $zipper['product_zipper_id']=='2')
											{
												$ziptxt .= '<input type="radio" data-val="5" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'"   checked="checked" onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
											}
											else
											{
												$ziptxt .= '<input type="radio" data-val="6" name="zipper[]" id="'.$zipper['product_zipper_id'].'" class="zipper" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()" disabled="disabled">';
												$ziptxt .= ''.$zipper['zipper_name'];
											}
										}
										else
										{
    										if($zipper['product_zipper_id']=='1' || $zipper['product_zipper_id']=='9')
    										{
    											$ziptxt .= '<input type="radio" data-val="5" name="zipper[]" id="'.$zipper['product_zipper_id'].'" value="'.encode($zipper['product_zipper_id']).'"   checked="checked" onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
    										}
    										else
    										{
    											$ziptxt .= '<input type="radio" data-val="6" name="zipper[]" id="'.$zipper['product_zipper_id'].'" class="zipper" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()">';
    											$ziptxt .= ''.$zipper['zipper_name'];
    										}
										}
									}
									$ziptxt .= '</label>';
								$ziptxt .= '</div>';
                            }
							$html.= $ziptxt;                            
                        $html.='</div></div>';
	echo json_encode($html);

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
else if($fun == 'customer_detail'){
	
	$customer_name = $_POST['customer_name'];
	$result = $obj_quotation->getCustomerDetail($customer_name);
//	printr($result);die;
	echo json_encode($result);
}
/*else if($fun=='getMakePouch')
{
	$product_id = $_POST['product_id'];
	$make_pouch=$obj_quotation->getProduct($product_id);
	//printr($make_pouch);
	$html = '';
	if($make_pouch['make_pouch_available']!='')
	{
		$pouch=$obj_quotation->getMakePouch($make_pouch['make_pouch_available']);
		
		if(isset($pouch) && !empty($pouch))
		{
				$html.='
							<div class="col-lg-8">';
								   foreach($pouch as $make){
										 $html.='<div  style="float:left;width: 200px;">
													<label  style="font-weight: normal;">';
												if(isset($sel_make) && in_array($make['make_id'],$sel_make)){
													$html.='<input type="radio" class="make" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'"  onclick="showSize()" checked="checked"> ';
												}
												elseif($make['make_id']==1){
													$html.='<input type="radio" class="make" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'" onclick="showSize()" checked="checked" > ';
												}
												else
												{
													$html.='<input type="radio" class="make" name="make" id="'.$make['make_id'].'" onclick="showSize()" value="'.$make['make_id'].'" > ';
												}
									   $html.=' '.$make['make_name'].' </label>
										</div>';
									}
									
								$html.=' </div>';
		}
	}
	echo $html;
}*/
else if($fun == 'clone_mutli_quo')
{
	$result = $obj_quotation->clone_mutli_quo($_POST['multi_product_quotation_id']);
	echo 1;
}
else if($fun == 'getRollQty')
{   
    $html = '';
    if($_POST['qty_type']=='meter')
    {
        
		$html .= '<input type="text" name="quantity[]" value="" class="form-control validate[required]" style="float: left; width: 20%;"><label>Mtrs</label>';
    }
    else if($_POST['qty_type']=='kg')
    {
        $qty = $obj_quotation->getQuantity('',$_POST['qty_type']);
        
		if($qty){
        	foreach($qty as $item){
        			$html .= '<div class="checkbox" style="float: left; width: 30%;" id="div_check_"'.$item['quantity'].'>';
            			$html .= '<label id="'.$item['quantity'].'">';
            				$html .= '<input type="checkbox" name="quantity[]" value="'.encode($item['quantity']).'" class="validate[minCheckbox[1]]">'.$item['quantity'].' Kgs';
            			$html .= '</label>';
            		$html .= '</div>';
    		}
    	}
	
    }
    echo json_encode($html);
}
else if($fun == 'getKgs')
{
	parse_str($_POST['formData'], $post);

	if($_POST['printing_val']=='no gusset')
		$_POST['printing_val']='';
	
	$post['gusset_printing_option']=$_POST['printing_val'];
	
	//make_id = 7 offline and online make_id= 6
	if($post['make']=='6')
	{
		$post['layer']='Mw==';
		$material='12';
		$material2='16';
		$post['material']=array($post['material'][0],$material,$material2);
		$thickness='12.000';
		$thickness2='60.000';
		$post['thickness']=array($post['thickness'][0],$thickness,$thickness2);
	}
	
	$kgs = $obj_quotation->addRollQuotation($post,'meter');
	
	echo json_encode($kgs);
}
elseif($fun == 'getRollquantity'){
	parse_str($_POST['formData'], $post);
	parse_str($_POST['material_sel'], $material);
	if(isset($material) && !empty($material)){
		$check = 0;
		$materialQuantity = array();
		foreach($material['material'] as $key=>$material_id){
			if($material_id){
				$getQuantity = $obj_quotation->getRollQuantity($material['material'][$key]);
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
			asort($final);//printr($final);
			$final_qty=reset($final);
			$meter = $obj_quotation->addRollQuotation($post,'kg',$final_qty);
			$response['totalMtr']=$meter;
			$response['qty']=$final_qty;
			echo json_encode($response);
		}
	}else{
		echo false;
	}
}
?>