<?php
include("mode_setting.php");
$fun = $_GET['fun'];
$json=1;
if($_GET['fun']=='updateCustomOrderStatus') {	
	$custom_order_id = $_POST['custom_order_id'];
	$status_value = $_POST['status_value'];	
	if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) {
		$obj_custom_order->$fun($custom_order_id,$status_value);		
    }else{
		if($status_value == 0){
			$obj_custom_order->$fun($custom_order_id,$status_value);	
		}else{
			$json = 0;	
		}
	}	
	echo json_encode($json);	
}else if($_GET['fun']=='deleteCustomOrder') {	
	$custom_order_id = $_POST['custom_order_id'];
	$obj_custom_order->$fun($custom_order_id);	
	echo json_encode($json);
}else if($_GET['fun']=='deleteProductCustomOrder') {
	//printr($_POST);
	$custom_order_price_id = $_POST['custom_order_price_id'];
	$obj_custom_order->$fun($custom_order_price_id);	
	echo json_encode($json);
}else if($fun == 'setCustomOrder') {
	$obj_custom_order->$fun();
	echo json_encode($json);
}else if($fun == 'getQuantity') {	
	$type = $_POST['type'];
	$quantity_type = $_POST['quantity_in'];
	$product_id = $_POST['product_id'];
	$data = $obj_custom_order->$fun($type,$quantity_type);
	$gusset_available = $obj_custom_order->checkProductGusset($product_id);	
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
	$currency_price = $obj_custom_order->$fun(base64_decode($_POST['currency_id']));
	echo $currency_price;
}elseif($fun == 'getMaterialQuantity'){
	//printr($_POST['material']);
	//printr($_GET);
	if(isset($_POST['material']) && !empty($_POST['material'])){
		$check = 0;
		$materialQuantity = array();
		foreach($_POST['material'] as $material_id){
			if($material_id){
				$getQuantity = $obj_custom_order->getMaterialQuantity($material_id,$_GET['qty']);
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
			$final = $obj_custom_order->aasort($materialQuantity);			
			asort($final);			
			if($final && !empty($final)){
				$html = '';
				foreach($final as $val){
					$html .= '<div class="checkbox" style="float: left; width: 30%;">';
						$html .= '<label>';
							$html .= '<input type="radio" name="quantity[]" value="'.encode($val).'" class="validate[minCheckbox[1]]">'.$val;
						$html .= '</label>';
					$html .= '</div>';
				}
				echo json_encode($html);
			}else{
				echo json_encode(false);
			}
			//echo json_encode("setset");
		}else{
			echo json_encode(false);
		}
	}else{
		echo false;
	}
}else if($fun == 'checkProductGusset'){
	$gusset_available = $obj_custom_order->checkProductGusset($_POST['product_id']);
	echo $gusset_available;
}else if($fun == 'checkProductZipper'){
	$zipper_available = $obj_custom_order->checkProductzipper($_POST['product_id']);
	$html ='<div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-9">';
	                        $zippers = $obj_custom_order->getActiveProductZippers();
							$ziptxt = '';
                            foreach($zippers as $zipper){
                           
							   $ziptxt .= '<div   style="float:left;width: 200px;">';
							   		$ziptxt .= '<label  style="font-weight: normal;">';
									if($zipper_available==0 )
									{
										if( $zipper['product_zipper_id']==2)
										{
										$ziptxt .= '<input type="radio" name="zipper[]" value="'.encode($zipper['product_zipper_id']).'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										}
										else
										{
										$ziptxt .= '<input type="radio" name="zipper[]" value="'.encode($zipper['product_zipper_id']).'"   disabled="disabled" class="zipper"> '.$zipper['zipper_name'];
										}
									}
									else
									{
										if( $zipper['product_zipper_id']==2)
										{
										$ziptxt .= '<input type="radio" name="zipper[]" value="'.encode($zipper['product_zipper_id']).'"   checked="checked" onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										}
										else
										{
									$ziptxt .= '<input type="radio" name="zipper[]" class="zipper" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()">';
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
	$widthsuggestion = $obj_custom_order->getGussetSuggestion($_POST['width'],$gusset,$_POST['product_id']);
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
	$gussetsuggestion = $obj_custom_order->getGussetSuggestion($_POST['width'],$_POST['gusset'],$_POST['product_id']);
	$pricesuggestion = $obj_custom_order->getToolPrice($_POST['width'],$_POST['gusset'],$_POST['product_id']);
	
	if($obj_session->data['LOGIN_USER_TYPE']==1){
		$userCurrency = $obj_custom_order->getCurrencyInfo($obj_session->data['ADMIN_LOGIN_SWISS']);
		$userCurrency['currency_code'] = "INR";
		$userCurrency['tool_rate']='';
	}else{
		$userCurrency =  $obj_custom_order->getUserWiseCurrency($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
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
			$arr .=$a.$gusset['gusset'].'mm  else tool price '.' '.$userCurrency['currency_code'].' '.$obj_custom_order->numberFormate($tool_price,"2").$b.'
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
	$tool_price = $obj_custom_order->getViewToolprice($_POST['product_id']);
	//printr($tool_price);
	//die;
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
	//printr($_POST);die;
	$update_discount = $obj_custom_order->update_discount($quantity_id,$discount);
	echo $update_discount;
}
elseif($fun == 'getQuotationData') {
	$quotation_no = $_POST['quotation_no'];
	$obj_session->data['quotation_no'] = $_POST['quotation_no'];
	if($quotation_no != '')
	{
		$quotation_id = $obj_custom_order->getQuotationId($quotation_no);		
		if($quotation_id)
		{
			$getData = " product_quotation_id,pq.added_by_user_id,pq.added_by_user_type_id,pq.date_added,customer_name,address_book_id, shipment_country_id,pq.multi_product_quotation_id, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,layer,volume,currency_code,currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,pq.status,pq.quotation_status,discount";
		$data = $obj_quotation->getQuotation($quotation_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		//printr($data);//die;
		//printr();	
		$expiredate_cust = $obj_quotation->getexpiredate_custmorder($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
		$exp_date=$expiredate_cust['Multi_Quotation_expiry_days'];
		$date_added=strtotime($data[0]['date_added']);
		$final_date=date('y-m-d', $date_added);
		$fin='';
		if($exp_date!='')
		{
			$fin=date('y-m-d',strtotime($final_date."+ {$exp_date} days"));
		}
		$today=date('y-m-d'); 
		if(!($fin >= $today))
		{
			
			$arr='';
			echo $arr;
		}
		else
		{
			$tax_type = $obj_quotation->CheckQuotationTax($quotation_id);
			$quantityData=array();
			foreach($data as $dat)
		   {
			 $quantityData[] = $obj_quotation->getQuotationQuantity($dat['product_quotation_id']);
		   }
			//printr($quantityData);
			foreach($quantityData as $k=>$qty_data)
			{
				//printr($qty_data);
				if(!empty($qty_data))
				{
				foreach($qty_data as $tag=>$qty)
				{
					foreach($qty as $q=>$arr)
					{
						$new_data[$tag][$q][]=$arr[0];
					}
				}
				}	
			}
		//	printr($new_data);//die;
			$result='<input type="hidden" id="multi_quote_id" name="multi_quote_id" value="'.$quotation_id.'"/>';
			foreach($new_data as $k=>$qty_data)
			{	//printr($new_data);
				$result.='<div class="form-group">
									<label class="col-lg-3 control-label" style="width:10%">Price (By '.$k.')</label> 
									<div class="col-lg-9" style="width:89%">
										<section class="panel">
										  <div class="table-responsive">
											<table class="table table-striped b-t text-small">
											  <thead>
												  <tr>
												   <th width="20"></th>
													<th>Product Name</th>
													<th>Quantity</th>';
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
													$result.=' <th>Cylender Price</th>
													 <th>Tool Price</th>
												  </tr>
											  </thead>
											  <tbody>';
												 $i=1;
												// printr($qty_data);
												// die;
													foreach($qty_data as $skey=>$sdata){                                                  
													   $result.=' <tr>';                                                        
															foreach($sdata as $soption){
															//printr($soption);
																$result .='<tr>';
																$result .='<td><input type="checkbox" id="'.$soption['product_quotation_price_id'].'" name="product_quotation_price_id[]" value="'.$soption['product_quotation_price_id'].'=='.(int)$soption['width'].'X'.(int)$soption['height'].'X'.$soption['gusset'].'" /></td>';	//onclick="addalert(this)"
																//if($k == 'air')
	//															{
	//																$result .='<td><input type="radio" name="product_quotation_price_id_air" value="'.$soption['product_quotation_price_id'].'" /></td>';													
	//															}
	//															else
	//															{
	//																$result .='<td><input type="radio" name="product_quotation_price_id_sea" value="'.$soption['product_quotation_price_id'].'" /></td>';													
	//															}
																$result .='<th>'.$dat['product_name'];
																//if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
																//{
																    $product_code = $obj_custom_order->getActiveProductCode();
																    $result .='<br><br><small class="text-muted" style="color:red;">If You generated product code for this item please select product code </br>otherwise you have to generate product code.</small>';
																    $result .= '<select name="product_code_id_'.$soption['product_quotation_price_id'].'" select="'.$soption['product_quotation_price_id'].'" id="product_code_id_'.$soption['product_quotation_price_id'].'" class="form-control choosen_data">
                        															<option value="">Select Product Code</option>';
                        															foreach($product_code as $cust)
                        															{
                        															     $selected=''; 
                                														if($soption['product_quotation_price_id']==$cust['product_quotation_price_id']) {
                                																	 $selected= 'selected';
                                														}
                        																$result .= '<option value="'.$cust['product_code_id'].'" '.$selected.'>'.$cust['product_code'].'</option>';
                        															}
                                									$result .= '</select>';
                                									
                                        								if($data[0]['shipment_country_id']!=42 && $data[0]['shipment_country_id']!=14  && $data[0]['shipment_country_id']!=214 && $data[0]['shipment_country_id']!=155 && $data[0]['shipment_country_id']!=251 && $data[0]['shipment_country_id']!=111 ){ 
                                        								    	$result .= '<a class="label bg-primary" href="'. $obj_general->link('product_code', 'mod=add&product_quotation_price_id='.encode($soption['product_quotation_price_id']).'&quotation_no='.encode($_POST['quotation_no']).'&status=1', '',1).'"><i class="fa fa-plus"></i> generate product code</a>';
                                        						    	}
																//}
																
																
																$result .='</th>
																<th>'.$skey.'
																<input type="hidden" id="real_qty_'.$soption['product_quotation_price_id'].'" value="'.$skey.'">
																<input type="text" id="cust_qty_'.$soption['product_quotation_price_id'].'" name="cust_qty_'.$soption['product_quotation_price_id'].'" value="" class="form-control" style="display:none;" onchange="addQty('.$soption['product_quotation_price_id'].')">
																<br/><br/>Client Order Qty<input type="text" id="added_order_qty_'.$soption['product_quotation_price_id'].'" name="added_order_qty_'.$soption['product_quotation_price_id'].'" value="" class="form-control"></th>';
																$result.=' <td>'. ucwords($soption['text']).'</td>';
															   $result.='<td>'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.$soption['gusset']; if($soption['volume']!='') $result.=' ('.$soption['volume'].')'.' ('.$soption['make'].')';
																	$result.='</td><td>';
																
																for($gi=0;$gi<count($soption['materialData']);$gi++){
												  					$result.= ($gi+1).' Layer : '.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
																} $result.='</td>';
																   if($dat['quotation_status'] == 0){
																     if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																	 {  
																	 	if($dat['currency']=='INR')
																			$result.='<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																			<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['product_quotation_quantity_id'].'" class="form-control" style="width: 100px;" ></td>';
																	  }
																  }
																				$result.='<td>';
																					 if($soption['discount'] && $soption['discount'] >0.000) {
																						$result.='<b>Total : </b>';
																						$pretot= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3"); $result.= $pretot.'<br />
																						<b>Discount ('. $soption['discount'].' %) : </b>';
																						$predis = $pretot*$soption['discount']/100; 
																						$result.= $obj_quotation->numberFormate($predis,"3").'<br />
																						<b>Final Total : </b>'. $dat['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3"); 
																					 }
																					 else 
																					 {
																					 	$pretot = $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
																					 	$result.= $dat['currency'].' '.$pretot;
																					 }
																			$result.='  <br/><br/>Client Rate<input type="text" id="added_order_rate_'.$soption['product_quotation_price_id'].'" name="added_order_rate_'.$soption['product_quotation_price_id'].'" value="" class="form-control">
																			            <input type="hidden" id="per_pouch_price_'.$soption['product_quotation_price_id'].'" value="'.$pretot.'">
																						<input type="hidden" id="curr_'.$soption['product_quotation_price_id'].'" value="'.$dat['currency'].'">
																						<input type="hidden" id="cust_total_'.$soption['product_quotation_price_id'].'" name="cust_total_'.$soption['product_quotation_price_id'].'" value="">
																						<input type="hidden" id="cust_discount_'.$soption['product_quotation_price_id'].'" value="'.$soption['discount'].'">
																			</td>
																							<td>';
																							  if($soption['discount'] && $soption['discount'] >0.000)
																							  {
																									$result.='<b>Total : </b>'; $tot= $obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3"); $result.= $tot.'<br />
																									<b>Discount ('. $soption['discount'].' %) : </b>';
																									 $dis = $tot*$soption['discount']/100; 
																									$result.= $obj_quotation->numberFormate($dis,"3").'<br />
																									<b>Final Total : </b>'. $dat['currency'].' '.($tot-$dis);
																							  }
																							  else 
																									$result.= $dat['currency'].' '.$obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");																 
																		$result.='<br><span id="new_total_'.$soption['product_quotation_price_id'].'"></span></td>';
																				  if($k=='pickup') 
																				  {
																					  if($data[0]['shipment_country_id']==111) 
																					  { 
																						$result.='<td>
																							'. $dat['currency'].' '.$obj_quotation->numberFormate((($soption['totalPriceWithTax'] / $skey) / $dat['currency_price'] ),"3").'
																						 </td>
																						 <td>
																							'. $dat['currency'].' '.$obj_quotation->numberFormate(($soption['totalPriceWithTax'] / $dat['currency_price'] ),"3").'
																						 </td>';
																						}
																				  }
																				  $result.='<td>'; 
																				  if($soption['cylinder_price']>0)
																				  {
																				  	$result.= (int)$soption['cylinder_price'];
																				  }
																				  else 
																				  	$result.='';$result.='</td>
																	 				<td>'; 
																				  if($soption['tool_price']>0) 
																				  {
																				  	$result.= (int)$soption['tool_price'];
																				  }
																				  else $result.='';$result.='</td>															 
																			</tr>';                                                           
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
					//printr($result);
					//printr('----------------------------------------------------------------'); 
				$arr['response'] = $result;	
				$arr['result'] = $data[0]['shipment_country_id'];
				$arr['customer'] = $data[0]['customer_name']; 
				$arr['address_book_id'] = $data[0]['address_book_id'];
				$arr['currency_code']= $data[0]['currency_code'];
				//printr(json_encode($arr));
				echo json_encode($arr);
				
		}
					
		}
		else
			echo 1;
	}
	else	
		echo 1;
}
else if($fun == 'getProductSize') {
	//printr($_POST);	die;
	$product_id = $_POST['product_id'];
	$zipper_id = $_POST['zipper_id'];
	$data = $obj_custom_order->getProductSize($product_id,$zipper_id);
	$response = '';	
		$response .='<select id="size" name="size" class="form-control validate[required]" onchange="customSize()"><option value="">Select Size</option>';
	if($data){	
		foreach($data as $item){
				//$response .= '<div class="checkbox " style="float: left;  width: 50%;"><label>';
					//$response .= '<input type="checkbox" name="size[]" class="test" onclick="removeValidation()" id="'.$item['size_master_id'].'" value="'.encode($item['size_master_id']).'" >'.$item['volume'].'['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']';
					$response .= '<option value="'.encode($item['size_master_id']).'">';
					if($item['volume']!=0)
					$response .=  $item['volume'];
					$response .= '['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']</option>';					
				//$response .= '</label></div>';
		}
	}
		$response .= '<option value="0">Custom</option></select>';
	
	echo $response;
}
else if($fun == 'addCustomOrder') {

	parse_str($_POST['formData'], $post);	
	$post['country_id']=$post['shipping_country_id'];
	//	printr($post);
	if(isset($post['quantity']) && !empty($post['quantity'])){
		$last_id = $obj_quotation->addQuotationFormula($post,'O');
		if($last_id == "Error"){
			$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
		}else{
			$obj_session->data['success'] = 'Your Custom Order generated!';
		$getData = " custom_order_id,mco.added_by_user_id,mco.added_by_user_type_id, shipment_country_id,mco.multi_custom_order_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,mco.status,mco.custom_order_status,discount";
		$data = $obj_custom_order->getCustomOrder($last_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		$tax_type = $obj_custom_order->CheckCustomOrderTax($last_id);
		//printr($data);
		//die;
		foreach($data as $dat)
	   {
		 $quantityData[] = $obj_custom_order->getCustomOrderQuantity($dat['custom_order_id']);
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
	//	printr($new_data);
		$result='<input type="hidden" id="multi_cust_id" name="multi_cust_id" value="'.$last_id.'"/>';
		foreach($new_data as $k=>$qty_data)
		{
			$result.='<div class="form-group">
								<label class="col-lg-3 control-label" >Price (By '.$k.')</label> 
								<div class="col-lg-9"  >
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
											  	<th>Product</th>
                                                <th>Quntity</th>';
                                              if($dat['custom_order_type'] != 1){ 
                                                $result.='<th>Option</th>';
                                                } 
                                                $result.='<th>Dimension (Make Pouch)</th><th>Layer:Material:Thickness</th>';
                                               if($dat['custom_order_status'] == 0){
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
                                                $result.=' <th>Cylender Price</th>
                                                 <th>Tool Price</th>
                                              </tr>
										  </thead>
                                          <tbody>';
                                          	 $i=1;
                                                foreach($qty_data as $skey=>$sdata){                                                  
                                                   $result.=' <tr>';                                                        
                                                        foreach($sdata as $soption){
															$result .='<tr><td>'. ucwords($soption['product_name']).'</td><th>'.$skey.'</th>';
                                                            $result.=' <td>'. ucwords($soption['text']).'</td>';
                                                           $result.=' 
                                                                <td>'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.
																$soption['gusset']; if($soption['volume']!='') $result.=' ('.$soption['volume'].')'.' ('.$soption['make'].')'
																;
																$result.='</td><td>';
																
                          				for($gi=0;$gi<count($soption['materialData']);$gi++){
											  $result.= ($gi+1).' Layer : '.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
										} $result.='</td>';
							                                   if($dat['custom_order_status'] == 0){
																	 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																{  if($dat['currency']=='INR')
																$result.='<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['custom_order_quantity_id'].'" class="form-control" style="width: 100px;" ></td>'; } }
                                                                $result.='<td>';
																	   if($soption['discount'] && $soption['discount'] >0.000) {
                                                                $result.='<b>Total : </b>';$pretot= $obj_custom_order->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3"); $result.= $pretot.'<br />
                                                                <b>Discount ('. $soption['discount'].' %) : </b>';
																$predis = $pretot*$soption['discount']/100; 
																$result.= $obj_custom_order->numberFormate($predis,"3").'<br />
                                                                <b>Final Total : </b>'. $dat['currency'].' '.$obj_custom_order->numberFormate(($pretot-$predis),"3"); 
																	 }else $result.= $dat['currency'].' '.
																	$obj_custom_order->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
                                                                $result.='</td>
                                                                <td>';
                                                                  if($soption['discount'] && $soption['discount'] >0.000) {
                                                                $result.='<b>Total : </b>'; $tot= $obj_custom_order->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3"); $result.= $tot.'<br />
                                                                <b>Discount ('. $soption['discount'].' %) : </b>';
																 $dis = $tot*$soption['discount']/100; 
																$result.= $obj_custom_order->numberFormate($dis,"3").'<br />
                                                                <b>Final Total : </b>
																'. $dat['currency'].' '.($tot-$dis);
																 } else $result.= $dat['currency'].' '.$obj_custom_order->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");																 
                                                                $result.=' </td>';
                                                                  if($k=='pickup') {
																	  if($data[0]['shipment_country_id']==111) { 
                                                  				$result.='<td>
																	'. $dat['currency'].' '.$obj_custom_order->numberFormate((($soption['totalPriceWithTax'] / $skey) / $dat['currency_price'] ),"3").'
                                                                 </td>
                                                                 <td>
																	'. $dat['currency'].' '.$obj_custom_order->numberFormate(($soption['totalPriceWithTax'] / $dat['currency_price'] ),"3").'
                                                                 </td>';
                                                }  }$result.=' <td>'; if($soption['cylinder_price']>0) {$result.= (int)$soption['cylinder_price'];}else $result.='';$result.='</td>
                                                                 <td>'; if($soption['tool_price']>0) {$result.= (int)$soption['tool_price'];}
																 else $result.='';$result.='</td>															 
                                                            </tr>';                                                           
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
	$data = $obj_custom_order->getProduct($product_id);	
	$response = '';	
	if(isset($data) && $data['printing_option']==1){	
		$printing_option_type=explode(',',$data['printing_option_type']);
		$response.='<div class="form-group">
                <label class="col-lg-3 control-label">Gusset Printing Option</label>
                <div class="col-lg-9">';
		$i=1;
		foreach($printing_option_type as $val)
		{
			$qty = $obj_custom_order->getQuantityById($data[$val.'_min_qty']);
			if($i==1) $ch='checked="checked"'; else $ch='';
			if($val=='both')
			$val='Bottom / Side';
		 	$response .='<div class="radio">
                      	<label>
                        <input type="radio" class="" name="printing_option_type[]" onclick="print_change()" id="pType'.$i.'" value="'.$qty['quantity'].'" '. $ch.'>'.ucfirst($val).' Gusset Printing </label>
                    </div>';
			$i++;
		}
		$response.='</div>
              </div>';		
	}
	else
	{
		$response ='';	
	}//printr($data);//	die;
	echo $response;
}
else if($fun == 'removeFile'){
	$upload_path = DIR_UPLOAD.'admin/dieline/';
	if(isset($_SESSION['product_die_line'])){
		foreach($_SESSION['product_die_line'] as $die_line){
			//printr($die_line);
			if($die_line['die_id']==$_POST['die_id']){				
				unset($_SESSION['product_die_line'][$die_line['die_id']]);
				if($die_line['die_ext']=='img')
				{
					if(file_exists($upload_path.'100_'.$die_line['die_name'])){
						unlink($upload_path.'100_'.$die_line['die_name']);
					}
					if(file_exists($upload_path.'500_'.$die_line['die_name'])){
						unlink($upload_path.'500_'.$die_line['die_name']);
					}
					if(file_exists($upload_path.$die_line['die_name'])){
						unlink($upload_path.$die_line['die_name']);
					}
				}
				else
				{
					if(file_exists($upload_path.$die_line['die_name'])){
						unlink($upload_path.$die_line['die_name']);
					}
				}
			}
		}		
		if(empty($_SESSION['product_die_line'])){
			unset($_SESSION['product_die_line']);	
		}
	}
	echo 1;	
}else if($fun == 'removeImage'){
	$upload_path = DIR_UPLOAD.'admin/artwork/';
	if(isset($_SESSION['product_images'])){
		foreach($_SESSION['product_images'] as $art_work){
			//printr($die_line);
			if($art_work['image_id']==$_POST['image_id']){				
				unset($_SESSION['product_images'][$art_work['image_id']]);
				if($art_work['image_ext']=='img')
				{
					if(file_exists($upload_path.'100_'.$art_work['image_name'])){
						unlink($upload_path.'100_'.$art_work['image_name']);
					}
					if(file_exists($upload_path.'500_'.$art_work['image_name'])){
						unlink($upload_path.'500_'.$art_work['image_name']);
					}
					if(file_exists($upload_path.$art_work['image_name'])){
						unlink($upload_path.$art_work['image_name']);
					}
				}
				else
				{
					if(file_exists($upload_path.$art_work['image_name'])){
						unlink($upload_path.$art_work['image_name']);
					}
				}
			}
		}		
		if(empty($_SESSION['product_images'])){
			unset($_SESSION['product_images']);	
		}
	}
	echo 1;	
}else if($fun == 'removeArt'){
	$obj_custom_order->removeArt($_POST['image_order_id']);
}
else if($fun == 'removeDie'){
	$obj_custom_order->removeDie($_POST['die_order_id']);
}
else if($fun == 'Adddetail'){
    
   // parse_str($_POST['file'], $post);
	
	//parse_str($_POST['formData'], $post);
	/*if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
         printr($_FILES['file']);printr($_POST['file']);die;*/
	if(isset($_SESSION['product_images'])){
		$product_images = $_SESSION['product_images'];
		unset($_SESSION['product_images']);
	}else{
		$product_images = '';
	}
		
	if(isset($_SESSION['product_die_line'])){
		$product_die_line = $_SESSION['product_die_line'];
		unset($_SESSION['product_die_line']);
	}else{
		$product_die_line = '';
	}
		
	$obj_custom_order->insertNote($_POST); 
		
	if(!empty($product_images)){
		$obj_custom_order->insertImages($product_images,$_POST['custom_order_id']);
	}
/*		if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1'){
//	printr($post);printr($_FILES);
//	printr($_SESSION['product_images']);
	printr($product_die_line);
	foreach($product_die_line as $p_die_line){
	    $obj_custom_order->insertDieLine($p_die_line,$post['custom_order_id']);
	}
	die;}*/
	if(!empty($product_die_line)){
		$obj_custom_order->insertDieLine($product_die_line,$_POST['custom_order_id']);
	}
	
}
else if($fun == 'getNote')
{
	 $note = $obj_custom_order->getCustomOrderPackingAndTransportDetails($_POST['custom_order_id']);
	 $array = array('note' => $note['product_note'], 'instr' =>$note['product_instruction']);
	// printr($array);
	 echo json_encode($array);
}//[kinjal] (10-9-2016)
else if($fun == 'updateAccDeclinestatus')
{
	//printr($_POST['postArray']);
	
	$value = '';
	$arr=array('user_id'=>$obj_session->data['ADMIN_LOGIN_SWISS'],
					'user_type_id'=>$obj_session->data['LOGIN_USER_TYPE'],
					'currdate'=>$_POST['postArray']['currdate']
				);
	$value .= "process_by='".json_encode($arr)."',";
	if(isset($_POST['postArray']['review']))
	{
		$value .= "review='".$_POST['postArray']['review']."'";
	}
	if(isset($_POST['postArray']['due_date']))
	{
		$value .= ",expected_ddate='".$_POST['postArray']['due_date']."'";
	}
	if(!empty($_POST['postArray']['status']) && $_POST['postArray']['status']!='')
	{
		$cond = "multi_custom_order_id = '" .(int)$_POST['postArray']['multi_custom_order_id']. "' AND custom_order_id = '" .(int)$_POST['postArray']['custom_order_id']. "'";
			
		$result = $obj_custom_order->updateAccDeclinestatus($value,$cond,$_POST['postArray']['status']);
	}
	if($_POST['postArray']['status']!=1)
	{
		$post=array($_POST['postArray']['custom_order_id'].'=='.$_POST['postArray']['multi_custom_order_id']);
		$rel = $obj_template->sendOrderEmail($post,$_POST['postArray']['status'],$_POST['adminEmail']);
		//die;
	}
	
}

//sonu add 21-4-2017
if($fun == 'customer_all_detail'){
	$address_book_id = $_POST['address_book_id'];
	//printr($_POST['address_book_id']);
	$result = $obj_custom_order->getCustomerAllDetail($address_book_id);
//printr($result);die;
	echo json_encode($result);
}
if($fun == 'clone_data'){
    $result = $obj_custom_order->clone_data($_POST['multi_custom_order_id']);
    //printr($result);
    return 1;
}
if($fun == 'dispatch_order_detail'){
    $result = $obj_invoice->dispatch_order_detail($_POST['order_id']);
  	$html=''; 
  		$html.='<div class="table-responsive">
				<table class="table table-striped b-t text-small">
				  <thead>
					  <tr>
					  	<th>EXP Invoice No </th>
					  	<th>Mode of Shipment </th>
					  	<th>Product Details </th>
                        <th>Dispatch Date </th>
                        <th>Dispatch Qty </th>
                        <th></th>
                        </tr></thead><tbody>';
    if(!empty($result)){
        foreach($result as $data){ 
            $html.='<tr>';
             $html.='	<td>'.$data['invoice_no'].' </td>';
             $html.='	<td>'.ucwords(decode($data['transportation'])).' </td>';
             $html.='	<td>'.$data['product_code'].' ==> <span style="color:blue"> '.$data['buyers_o_no'].'</span> </td>';
             $html.='	<td>'.dateFormat(4,$data['invoice_date']).'  </td>';
             $html.='	<td>'.$data['qty'].' </td>';
             if( ucwords(decode($data['transportation']))=='Air'){
                   $html.='	<td> Tracking Details : <span style="color:blue">'.$data['courier_name'].' </span>=>  '.$data['tracking_no'].' </td>';
             }else{
                 $html.='	<td> </td>';
             }
            $html.='</tr>';
        }
    }
    	$html.='</tbody></table></div>';
   echo $html;
}

?>