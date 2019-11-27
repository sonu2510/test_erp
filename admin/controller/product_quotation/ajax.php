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
		//$json .= '<div class="form-control" style="min-height:10%;">';
		foreach($data as $item){
			
			/*$json .= '<div class="radio" style="float: left; width: 30%;">';
				$json .= '<label>';
					$json .= '<input type="radio" name="quantity[]" id="'.$count.'" value="'.encode($item['quantity']).'" class="validate[minCheckbox[1]]" >'.$item['quantity'];
				$json .= '</label>';
			$json .= '</div>';*/
			$response['display'] .= '<div class="checkbox" style="float: left; width: 30%;">';
				$response['display'] .= '<label>';
					$response['display'] .= '<input type="checkbox" name="quantity[]" id="'.$count.'" value="'.encode($item['quantity']).'" class="validate[minCheckbox[1]]">'.$item['quantity'];
				$response['display'] .= '</label>';
			$response['display'] .= '</div>';
			/*$json .= '<div style="float: left; width: 30%;">';
					$json .= '<input type="checkbox" name="quantity[]" id="'.$count.'" value="'.encode($item['quantity']).'" class="validate[minCheckbox[1]]"> <label for="'.$count.'" style="font-weight:normal;">'.$item['quantity'].'</label>';
			$json .= '</div>';*/		
			$count++;		
		}
		//$json .= '</div>';
	}
	echo json_encode($response);
}else if($fun == 'getCurrencyValue'){
	$currency_price = $obj_quotation->$fun(base64_decode($_POST['currency_id']));
	echo $currency_price;
}elseif($fun == 'getMaterialQuantity'){
	//printr($_POST['material']);
	if(isset($_POST['material']) && !empty($_POST['material'])){
		$check = 0;
		$materialQuantity = array();
		foreach($_POST['material'] as $material_id){
			if($material_id){
				$getQuantity = $obj_quotation->getMaterialQuantity($material_id);
				//printr($getQuantity);die;
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
			//printr($materialQuantity);die;
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
			//echo json_encode("setset");
		}else{
			echo json_encode(false);
		}
	}else{
		echo false;
	}
}else if($fun == 'checkProductGusset'){
	$gusset_available = $obj_quotation->checkProductGusset($_POST['product_id']);
	echo $gusset_available;
}else if($fun == 'checkProductZipper'){
	$zipper_available = $obj_quotation->checkProductzipper($_POST['product_id']);
	$html ='<div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-9">';
	                        $zippers = $obj_quotation->getActiveProductZippers();
							$ziptxt = '';
                            foreach($zippers as $zipper){
                           
							   $ziptxt .= '<div   style="float:left;width: 200px;">';
							   		$ziptxt .= '<label  style="font-weight: normal;">';
									if($zipper_available==0 )
									{
										if( $zipper['product_zipper_id']==2)
										{
										$ziptxt .= '<input type="radio" name="zipper[]" value="'.encode($zipper['product_zipper_id']).'" checked="checked"   > '.$zipper['zipper_name'];
										}
										else
										{
										$ziptxt .= '<input type="radio" name="zipper[]" value="'.encode($zipper['product_zipper_id']).'"   disabled="disabled"> '.$zipper['zipper_name'];
										}
									}
									else
									{
										if( $zipper['product_zipper_id']==2)
										{
										$ziptxt .= '<input type="radio" name="zipper[]" value="'.encode($zipper['product_zipper_id']).'"   checked="checked"> '.$zipper['zipper_name'];
										}
										else
										{
									$ziptxt .= '<input type="radio" name="zipper[]" value="'.encode($zipper['product_zipper_id']).'" >';
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
	/*if(isset($_POST['gusset']))
	{
		$gusset = $_POST['gusset'];
	}
	else*/
		$gusset = '';
		$widthsuggestion = $obj_quotation->getGussetSuggestion($_POST['width'],$gusset,$_POST['product_id']);
	//printr($widthsuggestion);
	//die;
	
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
	//printr($gussetsuggestion);
	//die;
	if($obj_session->data['LOGIN_USER_TYPE']==1){
					$userCurrency = $obj_quotation->getCurrencyInfo($obj_session->data['ADMIN_LOGIN_SWISS']);
					//printr($userCurrency);
						$userCurrency['currency_code'] = "INR";
						$userCurrency['tool_rate']='';
					//die;
				}else{
					$userCurrency =  $obj_quotation->getUserWiseCurrency($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
					//printr($userCurrency);
					//die;
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
			//$arr['price'][]=$pricesuggestion;
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
	$update_discount = $obj_quotation->update_discount($quantity_id,$discount);
	echo $update_discount;
}
?>