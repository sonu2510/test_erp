<?php 

public function sendQuotationEmail($quotation_id,$toEmail = '',$setQuotationCurrencyId='')
{
	$getData = ' product_quotation_id, added_by_user_id, added_by_user_type_id, customer_name, customer_gress_percentage, customer_email, shipment_country_id, quotation_number, quotation_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,quantity_type,layer,added_by_country_id, currency, currency_id, currency_price, date_added, cylinder_price,tool_price, customer_email,status ';
	$data = $this->getQuotationSelectedData($quotation_id,$getData);
	$newdata = $this->getexciestaxtype($quotation_id);
	$gressper = $this->getQuotationGresspriceForMail($quotation_id);
	$addedByInfo = $this->getUser($data['added_by_user_id'],$data['added_by_user_type_id']);
	
	if($addedByInfo)
	{
		$str=$data['product_name'];
		$gettermsandconditions = $this->gettermsandconditions($data['added_by_user_id'],$data['added_by_user_type_id']);
		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$str),0,3));
		$setHtml = '';
		if($data['gusset'] == '')
		{
			$gussetval = 'No Gusset';
		}
		else
		{
			if($data['product_id'] == '7')
			{
				$gussetval = ' { '. $data['gusset'].'mm + '.$data['gusset'].'mm + '.$data['gusset'].'mm gusset }';
			}
			elseif($data['gusset'] == '0')
			{
				$gussetval = ' ';						
			}
			else
			{
				$gussetval = ' { '. $data['gusset'].'mm + '.$data['gusset'].'mm gusset}';
			}
		}
		$setHtml .= '<div><b>Size : </b>'.'W : '.(int)$data['width'].'mm x '.'H : '.(int)$data['height'].'mm '.$gussetval.' </div> ';
		$setHtml .= '<div><b>Material : </b>';		
		$materialData = $this->getQuotationMaterial($data['product_quotation_id']);
		if(isset($materialData) && !empty($materialData))
		{
			$materialStr = '';
			for($gi=0;$gi<count($materialData);$gi++)
			{
				$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
			}
			$setHtml .= substr($materialStr,0,-3);
		}
		$setHtml .= '</div>';
		$shippingCountry = $this->getCountry($data['shipment_country_id']);
		$setHtml .= '<div><b>Shipment Country : </b>'.$shippingCountry['country_name'].'</div>';
		$quantityData = $this->getQuotationQuantityForMail($data['product_quotation_id']);
		$quantityCustomerHtml = '';
		$quantityHtml = '';
		$pqcquery = '';
		if($data['customer_email'] != '' || $toEmail != '')
		{
			$quantityCustomerHtml .= $setHtml;			
			$quantityCustomerHtml .= '<style> table, th, td </style>';
			$quantityCustomerHtml .= '<div >';
			$selCurrency = '';
			if($setQuotationCurrencyId)
			{
				$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
				if(!$selCurrency)
				{
					$selCurrency = $this->getSelectedCurrecyForQuotation($setQuotationCurrencyId);
				}
			}
			else
			{
				$selCurrency = $this->getSelectedCurrecyForQuotation($setQuotationCurrencyId);
			}
			if($selCurrency)
			{
				$pqcquery = " product_quotation_currency_id = '".$selCurrency['product_quotation_currency_id']."', ";
			}
			$i=0;
			foreach($quantityData as $quantity=>$qoption)
			{
				foreach($qoption as $zipval => $zipPrice)
				{
					if($i==0)
					{
						$quantityCustomerHtml .= '<b>Make up of pouch  : </b> Custom Printed '. $data['product_name'] .''.$zipval.'<br>';
					}
					$quantityCustomerHtml .= '<table cellpadding="0" cellspacing="0">';
					foreach($zipPrice as $key=>$value)
					{
						if($selCurrency)
						{
							$newPirce = ((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $selCurrency['currency_rate'] );
							if($i==0)
							{
								$txt = '<b>Price : </b>';
							}
							else
							{
								$txt = '&nbsp;';
							}
							$quantityCustomerHtml .='<tr valign="top"><td width="60">'.$txt.'</td><td>'.$selCurrency['currency_code'].' '.$this->numberFormate(( $newPirce / $quantity) ,"3").' per 1 bag ';
							$i++;
							if($data['quotation_type']==1)
							{
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
								$bag_txt = '';
							}
							else
							{
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
								$bag_txt = 'bags';							
							}
							$quantityCustomerHtml .=	'<b> { For '.$quantity.' '.$bag_txt.' plus or minus '.$plus_minus_quantity.' '.$bag_txt.' }</b>&nbsp;- by '.$key.'<br><br></td></tr>';
						}
						else
						{
							$priceval =$this->numberFormate(((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $quantity) ,"3"); 
							$total = $priceval + ($priceval*$newdata['excies']/100);
							$final = $total + ($total *$newdata['tax_percentage']/100);
							$taxvalue = '';
							if($data['shipment_country_id'] == '111')
							{
								$taxvalue = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
								str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
							}
							$quantityCustomerHtml .= $data['currency'].' '.$priceval.' per 1 bag '.$taxvalue;
							if($data['quotation_type']==1)
							{
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
								$quantityCustomerHtml .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
							}
							else
							{
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
								$quantityCustomerHtml .= '<b>{For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
							}
							$quantityCustomerHtml .= ' - By '.ucwords($key).'.<br><br></td></tr>';
						}
					}
					$quantityCustomerHtml .= '</table>';
				}
			}
			$quantityCustomerHtml .= '</div>';				
		}
	}
}
	
?>