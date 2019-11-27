<?php 
class productQuotation extends dbclass{
	
	public function addQuotationFormula($data,$type){
	//printr($data);
	//die;
		$post_height = (int)$data['height'];
		$post_width = (int)$data['width'];
		$gusset = $data['gusset'];
		$product_id = (int)$data['product'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$userInfo = $this->getUserInfo($user_type_id,$user_id);
		$test['post_data']=$data;
		$productName = getName('product','product_id',$product_id,'product_name');
		if(strtolower($productName) == "roll"){
			return "Error";
		}else{
			$formulla = $this->formulaHeightWidthGusset($post_height,$post_width,$gusset,$product_id);
			$test['formulla']=$formulla;
			$actualHeight = $formulla['formula'];
			$height = $formulla['height'];
			$width = $formulla['width'];	 
			if($formulla['intoHeight'] == 1){
				$widthHeight = $height;
			}elseif($formulla['intoWidth'] == 1){
				$widthHeight = $width;
			}else{
				$widthHeight = $width;
			}
			
			if(isset($data['material']) && !empty($data['material']) && isset($data['thickness']) && !empty($data['thickness'])){
				$total_material = count($data['material']);
				$layerPrice = array();
				$checkCppMaterial = 0;
				$setQueryData = array();
				$materialName = '';
				for($p=0;$p<$total_material;$p++){
					$setNumber = $p.'0';
					$addingActualHeight = ( $setNumber / 1000 );
					$newLayerWiseHeight = ( $actualHeight + $addingActualHeight);
					$test['addingActualHeight']=$addingActualHeight;
					$test['newLayerWiseHeight']=$newLayerWiseHeight;
					$gsm =$this->getMaterialGsm($data['material'][$p]);
					$test['gsm']=$gsm;
				//Thickness
						$checkCppMaterial = $this->checkMaterial($data['material'][$p]);
						$thicknessPrice = $this->getMaterialThickmessPrice($data['material'][$p],$data['thickness'][$p]);
						$test['thicknessPrice']=$thicknessPrice;
						$layerWiseGsmThickness[$p+1] = $this->getLayerWiseGsmThickness($newLayerWiseHeight,$widthHeight,$data['thickness'][$p],$gsm);			
						$test['layerWiseGsmThickness']=$layerWiseGsmThickness;
						$layerPrice[$p+1] = $this->getLayerPrice($layerWiseGsmThickness[$p+1],$thicknessPrice);
						$test['layerPrice']=$layerPrice;
						$materialName = getName('product_material','product_material_id',$data['material'][$p],'material_name');
						$setQueryData[] = " layer = '".(int)($p+1)."', material_id = '".(int)$data['material'][$p]."', material_gsm = '".(float)$gsm."', material_thickness = '".$data['thickness'][$p]."', material_price = '".(float)$thicknessPrice."', material_name = '".$materialName."', layer_wise_gsmthickness = '".(float)$layerWiseGsmThickness[$p+1]."', layer_wise_price = '".(float)$layerPrice[$p+1]."', date_added = NOW()";					
				}
				$totalLayer = count($data['material']);
				$layerCount = (isset($p))?$p:'';
				//total GSM THICKNESS
				$totalLayerGsmThickness = $this->sumOfNumericArray($layerWiseGsmThickness);
				$test['totalLayerGsmThickness']=$totalLayerGsmThickness;
				//Total Layer wise Price
				$totalLayerPrice = $this->sumOfNumericArray($layerPrice);
				$test['totalLayerPrice']=$totalLayerPrice;
				//printing option and printing effect || Ink Solvent 
				//change code for change function
				if(isset($data['printing']) && $data['printing'] == 1){
					$printing_option = "With Printing";
					$onlyInkPrice = $this->getInkPrice1($data['make']);
					$test['onlyInkPrice']=$onlyInkPrice;
					$inkSolventPrice = $this->getInkSolventPrice($layerWiseGsmThickness[1],1,$data['make']);
					$test['inkSolventPrice']=$inkSolventPrice;
					$printingEffectPrice = 0;
					if(isset($data['printing_effect']) && !empty($data['printing_effect']) && (int)$data['printing_effect'] > 0){
						$printingEffectPrice = $this->getPrintingEffectPrice($data['printing_effect']);
					$test['printingEffectPrice']=$printingEffectPrice;
					}
					$inkPrice = (($onlyInkPrice + $printingEffectPrice) * $layerWiseGsmThickness[1]);
						$test['inkPrice']=$inkPrice;
				}else{
					$printing_option = "Without Printing";
					$onlyInkPrice = 0;
					$printingEffectPrice = 0;
					$inkPrice = 0;
					$inkSolventPrice = 0;
				}
				
				//Adhesive and adhesive solvent
				if($checkCppMaterial == 1 ){
					$adhesivePrice = $this->getCppAdhesivePrice($layerWiseGsmThickness[1],$layerCount,1);
					$test['adhesivePrice']=$adhesivePrice;
				}else{
					$adhesivePrice = $this->getAdhesivePrice($layerWiseGsmThickness[1],$layerCount,1,$data['make']);
					$test['adhesivePrice']=$adhesivePrice;
					
				}
				$adhesiveSolventPrice = $this->getAdhesiveSolventPrice($layerWiseGsmThickness[1],$layerCount,1,$data['make']);
				$test['adhesiveSolventPrice']=$adhesiveSolventPrice;
				//Total Price : SUM of all price and calculate average price
				
				$totalPrice = $this->numberFormate(($totalLayerPrice + $inkPrice + $inkSolventPrice + $adhesivePrice + $adhesiveSolventPrice),"5") ;
				$test['totalPrice']=$totalPrice;
				//Packing price / pouch
				$packingPerPouch = $this->newPackingCharges($post_height,$post_width,$gusset,$product_id);
				    $test['packingPerPouch']=$packingPerPouch;
				$valveBasePrice = 0;
				if(isset($data['valve'][0]) && !empty($data['valve'][0])){
					$valveBasePrice = $userInfo['valve_price'];
				 $test['valveBasePrice']=$valveBasePrice;
				 }
			//	printr($data);
				//check product weight is with zipper or without zipper
				$zipperWiseData = array();
				if(isset($data['zipper'][0]) && !empty($data['zipper'][0])){
					$zipper_id = decode($data['zipper'][0]);
					$zdata = $this->getZipperInfo($zipper_id);
					$calculateZipperPrice = 0;
						if($zdata['price'] > 0 ){
							$calculateZipperPrice = $this->getCalculateZipperPrice($product_id,$post_height,$post_width,$zdata['price']);
						 $test['calculateZipperPrice']=$calculateZipperPrice;
						 }
						$zipperWiseData = array(
							'product_zipper_id'	=> $zdata['product_zipper_id'],
							'zipperText'		   => $zdata['zipper_name'],
							'zipperBasePrice'	  => $zdata['price'],
							'calculatePrice'	   => $calculateZipperPrice		
						);
						$zipperBasePrice = $zdata['price'];
						$zipperCalculatePrice =$calculateZipperPrice;
						 $test['zipperWiseData']=$zipperWiseData;
				}
			
				//Spout
				$spoutArray = array();
				if(isset($data['spout'][0]) && !empty($data['spout'][0])){
					$spout_id = decode($data['spout'][0]);
						$spoutInfo = $this->getSpout($spout_id);
						if($spoutInfo){
							$spoutArray = array(
								'product_spout_id'	=> $spoutInfo['product_spout_id'],
								'spout_name'		  => $spoutInfo['spout_name'],
								'price'	  		   => $spoutInfo['price']
							);
						}
						 $test['spoutArray']=$spoutArray;
				}
			
				//Accessorie
				$accessorieArray = array();
				if(isset($data['accessorie'][0]) && !empty($data['accessorie'][0])){
					$accessorie_id = decode($data['accessorie'][0]);
						$accessorieInfo = $this->getAccessorie($accessorie_id);
						if($accessorieInfo){
							$accessorieArray = array(
								'product_accessorie_id'	=> $accessorieInfo['product_accessorie_id'],
								'accessorie_name'	 => $accessorieInfo['accessorie_name'],
								'price'	  		   => $accessorieInfo['price']
							);
						} 
						$test['accessorieArray']=$accessorieArray;
				}
					
				//COURIER AND TRANSPORT CALCULATION
				$transportByAir = 0;
				$transportBySea = 0;
				$transportByPickup = 0;
				if(isset($data['transpotation']) && !empty($data['transpotation']) && count($data['transpotation']) > 0 ){
					if(in_array(encode('air'),$data['transpotation'])){
						$transportByAir = 1;
					}
					if(in_array(encode('sea'),$data['transpotation'])){
						$transportBySea = 1;
					}
					if(in_array(encode('pickup'),$data['transpotation'])){
						$transportByPickup = 1;
					}
				}else{
					$transportBySea = 1;
				}
				//echo $transportByPickup;
				$shilmentCountry = $this->getCountry($data['country_id']);
				if(strtolower($shilmentCountry['country_name']) == "india"){
					$transportByAir = 0;
					$transportBySea = 0;
					$transportByPickup = 1;
				}
				
				
				//courie calculation
				$courierChargeWithZipper = 0;
				$courierChargeWithoutZipper = 0;
				$fuleSurchargeWithZipper  = 0;
				$serviceTaxWithZipper = 0;
				$fuleSurchargeWithoutZipper  = 0;
				$serviceTaxWithoutZipper = 0;
				$handlingCharge = 0;
				$fual_surcharge_base_price = 0;
				$service_tax_base_price = 0;
				$handling_base_price = 0;
				//pradip stop
				if($transportByAir){
					$countryCourierData = $this->getCountryCourier($data['country_id']);
					$fual_surcharge_base_price = $countryCourierData['fuel_surcharge'];
					$service_tax_base_price = $countryCourierData['service_tax'];
					$handling_base_price = $countryCourierData['handling_charge'];
				}
			/*	$test['$countryCourierData']=$countryCourierData;
				$test['$fual_surcharge_base_price']=$fual_surcharge_base_price;
				$test['$service_tax_base_price']=$service_tax_base_price;
				$test['$handling_base_price']=$handling_base_price;*/
			//	printr($transportPerPouch);
				//user gress value
				$userGress = $userInfo['gres'];
				$customer_gress = 0;
				$customer_email = '';
				if(isset($data['customer_check']) ){
					$customer_email = (isset($data['customer_email']) && $data['customer_email'] != '')?$data['customer_email']:'';
					$customer_gress =  (isset($data['customer_gress']) && (int)$data['customer_gress'] > 0 )?(int)$data['customer_gress']:0;					
				}
				
				//new code for multipale quantity
				$quantityArray = $data['quantity'];
				//printr($quantityArray);
				//die;
				$quantityWiseData = array();
				
				foreach($quantityArray as $key=>$eQuantity){
					//Transpotation / pouch
				$transportPerPouch = 0;
				$test1['$transportPerPouch']=$transportPerPouch;
				if($transportBySea){
					$transportPerPouch = $this->getCalculateTransport($post_height,$post_width,$gusset);
				}
				$test1['$transportPerPouch1']=$transportPerPouch;

					//	printr($eQuantity);
						$quantity = decode($eQuantity);
						//die;
						//Wastage
						$wastageBase = $this->getWastage($quantity);
						$wastageBaseArray = json_decode($wastageBase);
						$wastageBase=0;
						foreach($wastageBaseArray as $key=>$val)
			  			{
							if($product_id == $key)
							{
								$wastageBase = $val;
							}
						}
						$addingWastage = 0;
						if($post_height > 500){
							$addingWastage = 10;
						}
						$totalWastage = ($wastageBase + $addingWastage);
					
						$wastage = $this->numberFormate((($totalPrice * $totalWastage) / 100),"5");
						//Final price with wastage
						$finalPrice = ($totalPrice + $wastage);
						//printr($totalPrice);	
						// price per bag
						$pricePerBag = $this->numberFormate(($finalPrice / 1000),"5");
						$optionPrice = $this->numberFormate(($pricePerBag + $packingPerPouch ),"5");
						//Profit / pouch
						$profit = $this->getcalculateProfit($quantity,$data['product'],$post_height,$post_width,$gusset);
						$finalyPerPuchPrice = $this->numberFormate(($optionPrice + $profit ),"5");
						$totalWeightWithZipper = 0;
						$totalWeightWithoutZipper=0;
						$courierChargeBaseWithZipper = 0;
						$courierChargeBaseWithoutZipper = 0;
						
						$optionPrice = $this->numberFormate(($pricePerBag + $packingPerPouch + $transportPerPouch ),"5");
						
						$pricePerPuchWithOption = $this->numberFormate(($optionPrice + $profit + $zipperCalculatePrice + $valveBasePrice ),"5");
				//total price without coutier charge
						$ftotalPrice = $this->numberFormate(($pricePerPuchWithOption * $quantity),"5");
						if(decode($data['zipper'][0])==2)
						{
							//Total Weight without zipper
							$totalWeightWithoutZipper = $this->getCalculateWeightWithoutZipper($totalLayerGsmThickness,$quantity);
							if($transportByAir){
								$courierChargeBaseWithoutZipper = $this->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'],$totalWeightWithoutZipper);
							}
						}
						else
						{
							//Total Weight with zipper
							$totalWeightWithZipper = $this->getCalculateWeightWithZipper($totalLayerGsmThickness,$quantity);
							if($transportByAir){
								$courierChargeBaseWithZipper = $this->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'], $totalWeightWithZipper);
							}
						}					
						
						$test['totalWeightWithoutZipper']=$totalWeightWithoutZipper;
						$test['totalWeightWithZipper']=$totalWeightWithZipper;
						$test['courierChargeBaseWithoutZipper']=$courierChargeBaseWithoutZipper;
						$test['courierChargeBaseWithZipper']=$courierChargeBaseWithZipper;
						//courier charge
						$courierBasePriceQuantityWise = array();
						$transportAndCoutierCharge = '';
					if($transportByAir){
								$courierBasePriceQuantityWise[$quantity] = array(
									'withZipepr'  => $courierChargeBaseWithZipper, 
									'noZipper'	=> $courierChargeBaseWithoutZipper,
								);
							
								if($fual_surcharge_base_price > 0){
									if(decode($data['zipper'][0])==2)
									{
										$fuleSurchargeWithoutZipper = (($courierChargeBaseWithoutZipper * $fual_surcharge_base_price) / 100);
									}
									else
									{
										$fuleSurchargeWithZipper = (($courierChargeBaseWithZipper * $fual_surcharge_base_price) / 100);
									}
								}
								if($service_tax_base_price > 0){
									if(decode($data['zipper'][0])==2)
									{
										$courierCrhgFuleWithoutZipper = ($courierChargeBaseWithoutZipper + $fuleSurchargeWithoutZipper);
										$serviceTaxWithoutZipper = (($courierCrhgFuleWithoutZipper * $service_tax_base_price) / 100);
									}
									else
									{
										$courierCrhgFuleWithZipper = ($courierChargeBaseWithZipper + $fuleSurchargeWithZipper);
										$serviceTaxWithZipper = (($courierCrhgFuleWithZipper * $service_tax_base_price) / 100);
									}
								}
								if($handling_base_price > 0){
									$handlingCharge = $handling_base_price;
								}
								//courier charge with zipper								
								if(decode($data['zipper'][0])==2)
								{
								//courier charge without zipper
									$courierChargeWithoutZipper = $this->numberFormate(($courierChargeBaseWithoutZipper + $fuleSurchargeWithoutZipper + $serviceTaxWithoutZipper + $handlingCharge),"3");
								}
								else
								{
									$courierChargeWithZipper = $this->numberFormate(($courierChargeBaseWithZipper + $fuleSurchargeWithZipper + $serviceTaxWithZipper + $handlingCharge),"3");
								
								}	
								if($zipperBasePrice > 0){
									$transportAndCoutierCharge = $courierChargeWithZipper;
									$ftotalPrice = $this->numberFormate(($ftotalPrice + $courierChargeWithZipper),"5");
								}else{
									$transportAndCoutierCharge = $courierChargeWithoutZipper;
									$ftotalPrice = $this->numberFormate(($ftotalPrice + $courierChargeWithoutZipper),"5");
								}							
							}
							
						$taxation='';
						$taxation_data='';
						if(isset($data['country_id']) && !empty($data['country_id']) && $data['country_id'] ==111)
						{
							$taxation= $data['taxation'];
							$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' ORDER BY taxation_id DESC LIMIT 1";
							$data_tax = $this->query($sql);
							$taxation_data=$data_tax->row;
						}
						$zipperData = array();
						$zipperValue = $zipperWiseData;
						$valve_text= 'no Valve';
						$courierCharge=$courierChargeWithoutZipper;		
						$spoutPrice=0;	
						$accessoriePrice=0;		
						$courierChargeInFormula = $courierChargeWithoutZipper;		
						$withValvePrice = 0;
						$priceWithTransport = 0;
						$bySea = '';
						$byAir = '';
						$byPickup = '';	
						if($zipperValue['zipperBasePrice'] > 0){
							$courierCharge=$courierChargeWithZipper;	
							$courierChargeInFormula = $courierChargeWithZipper;	
						}
					$test1[$quantity]['$transportPerPouch2']=$transportPerPouch;
						$test[' $transportPerPouch']= $transportPerPouch;
						if(isset($spoutArray) && $spoutArray['price'] !=  0.000){
							$spoutPrice=$spoutArray['price'];
							if($transportByAir){
								 $courierChargeInFormula =  $courierChargeInFormula * 1.4;
							}
							if($transportBySea){
								 $transportPerPouch = $transportPerPouch + 0.10;
							}
						}
						$test1[$quantity]['$transportPerPouch3']=$transportPerPouch;
						if(isset($accessorieArray) && $accessorieArray['price'] !=0.0000){
							$accessoriePrice=$accessorieArray['price'];
						}
						if(isset($data['valve']) && in_array('1',$data['valve'])){
							$valve_text= 'with Valve';
						}
						if($transportByAir){
							$withValvePrice = $this->numberFormate((($finalyPerPuchPrice + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity ) + $courierChargeInFormula);
							$byAir['totalPriceByAir'] = $withValvePrice;	
						}
						if($transportBySea){
							echo '(('.$finalyPerPuchPrice .'+'. $transportPerPouch .'+'. $zipperValue['calculatePrice'] .'+'.  $valveBasePrice .'+'. 
							$spoutPrice .'+'. $accessoriePrice.')*'.  $quantity;
							$priceWithTransport = $this->numberFormate(($finalyPerPuchPrice + $transportPerPouch + $zipperValue['calculatePrice'] +  $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity );
							$bySea['totalPriceBySea'] = $priceWithTransport;
						}
						if($transportByPickup){
							$priceWithPickup = $this->numberFormate(($finalyPerPuchPrice + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity );
							$byPickup['totalPriceByPickup'] = $priceWithPickup;
						}
						//echo $transportPerPouch;die;
						$test1['$transportPerPouch4']=$transportPerPouch;
						$zipperData[] = array(
								'zip_text'		=> $zipperValue['zipperText'],
								'valve_text'      => $valve_text,
								'spout_txt'	=> $spoutArray['spout_name'],
								'spout_price'		=> $spoutArray['price'],
								'accessorie_txt'	=> $accessorieArray['accessorie_name'],
								'accessorie_price'		=> $accessorieArray['price'],
								'transportPerPouch'	=> $transportPerPouch,									
								'courierCharge' => $courierChargeInFormula,
								'calculateZipperPrice'	=> $zipperValue['calculatePrice'],						
								'BySea'	=>  $bySea,
								'ByAir'	=> $byAir,
								'ByPickup' => $byPickup,
							);
							$valveTxt =$valve_text;
							$zipperText =$zipperValue['zipperText'];
							$zipperCalculatePrice=$zipperValue['calculatePrice'];
							$spoutTxt=$spoutArray['spout_name'];
							$spoutBasePrice=$spoutArray['price'];
							$accessorieTxt =$accessorieArray['accessorie_name'];
							$accessorieBasePrice=$accessorieArray['price'];
							$test1['$transportPerPouch5']=$transportPerPouch;
							
					//printr($zipperData);	die;
					//	echo  $courierChargeInFormula;die;
					//spout
					$spoutQuantityWiseData[] = array(
						'spout_txt'	=> $spoutArray['spout_name'],
						'price'		=> $spoutArray['price'],
					
					); 
					//Accessorie
					$accessorieQuantityWiseData[] = array(
						'accessorie_txt'	=> $accessorieArray['accessorie_name'],
						'price'		=> $accessorieArray['price'],
					);
					//gress persontage price
					//store quantity wise information
					$quantityWiseData[$quantity] = array(
						'wastageBase'	=> $wastageBase,
						'addingWastage'  => $addingWastage,
						'wastage'		=> $wastage,
						'nativePricePerBag' => $pricePerBag,
						'totalWeightWithZipper' => $totalWeightWithZipper,
						'totalWeightWithoutZipper' => $totalWeightWithoutZipper,
						'profit'		 => $profit,
						'pricePerBag'   => $pricePerBag,
						'wastageBasePrice' => $wastageBase,
						'wastageAddingPint' => $addingWastage,
						'zipperData'	=> $zipperData,
						'spoutData'     => $spoutQuantityWiseData,
						'accessorieData'	=> $accessorieQuantityWiseData,
					);
				}
					$test1['$transportPerPouch6']=$transportPerPouch;
				$test['quantityWiseData']=$quantityWiseData;
			//	printr($test);die;
				$userCountry = $this->getUserCountry($user_type_id,$user_id);
				//Extra tool Price
				$tool_price = $this->getToolPrice($post_width,$gusset,$product_id);
			//	printr($tool_price);die;
				//deep(New Currency Price)
				if($user_type_id==1){
					$userCurrency = $this->getCurrencyInfo($user_id);
					$userCurrency['tool_rate']='';
				}else{
					$userCurrency =  $this->getUserWiseCurrency($user_type_id,$user_id);
				}
				//Cylinder Price
			//	$newWidthforCylinder = $gusset + $post_width;
			//	echo $newWidthforCylinder;die;
				$cylinderPrice = $this->getCalculateCylinderPrice($post_height,$post_width,$gusset,$data['country_id'],$product_id);
				$cylinderCurrencyPrice = $cylinderPrice;
				
				if($user_type_id==1){
					$currCode ='INR'; 										
				}
				else{
					$currCode=$userCurrency['currency_code'];
				}
				if($userCurrency['tool_rate']){
						$cylinderCurrencyPrice = ($cylinderPrice / $userCurrency['cylinder_rate']);
						$tool_price = ($tool_price / $userCurrency['tool_rate']);
				}	//		printr($userCurrency['cylinder_rate']);die;
			//	echo $cylinderCurrencyPrice.'<br>';		
					//$cylinderCurrencyMinPrice = $this->getCylinderBasePrice($userCurrency['counrty_id']);
					$cylinderCurrencyMinPrice = $this->getCylinderBasePrice($currCode);
				//	echo $cylinderCurrencyMinPrice;
				if($cylinderCurrencyMinPrice && $cylinderCurrencyMinPrice >= $cylinderCurrencyPrice ){
					$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
				}else{
					$cylinderCurrencyBasePrice = $cylinderCurrencyPrice;
				} 
				//echo $cylinderCurrencyPrice.'<br>min '.$cylinderCurrencyMinPrice;
				if($cylinderCurrencyPrice <= $cylinderCurrencyMinPrice)
				{
					$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
				}
				
		//echo $cylinderCurrencyBasePrice;die;
			//	printr($userCountry);
				if($userCountry){
					$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';	
					//echo $countryCode ;
					$newQuotaionNumber = $this->generateQuotationNumber();
					$quotation_number = $countryCode.$newQuotaionNumber;
				}else{
					$newQuotaionNumber = $this->generateQuotationNumber();
					$quotation_number = 'IN'.$newQuotaionNumber;
				}
				//echo $quotation_number;die;
				$printingEffectName = getName('printing_effect','printing_effect_id',$data['printing_effect'],'effect_name');
				//printr($printingEffectName);die;
				$productName = getName('product','product_id',$data['product'],'product_name');
				//Deep Modified
				if($user_type_id!=1){
					if($userCurrency['currency_code'] && $userCurrency['product_rate']){
						$currency = $userCurrency['currency_code'];
						$currencyPrice = $userCurrency['product_rate'];
					}else{
						$currency = 'INR';
						$currencyPrice = '1';
					}
				}else{
					if( $userCurrency['cylinder_rate']){
						$currency ='INR';
						$currencyPrice = $userCurrency['cylinder_rate'];
					}else{
						$currency = 'INR';
						$currencyPrice = '1';
					}
				}
				//	printr($courierBasePriceQuantityWise);die;
		//$test1['$transportPerPouch7']=$transportPerPouch;
		//printr($test);die;
		//printr($type);die;
		
		if(isset($data['discount']))
			$data['discount']=$data['discount'];
		else
			$data['discount'] = 0.000;
					if($type=='Q')
					{
							$sql = "INSERT INTO ".DB_PREFIX."product_quotation SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect_id = '".(int)$data['printing_effect']."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."', 				packing_price = '".(float)$packingPerPouch."', valve_price = '".$valveBasePrice."', gress_percentage = '".$userGress."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."', tool_price = '".(float)$tool_price."', use_device = '".getDevice()."', status = '0', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', currency = '".$currency."', currency_price = '".$currencyPrice."', customer_name = '". $data['customer']."', customer_email = '".$customer_email."', customer_gress_percentage = '".$customer_gress."', shipment_country_id = '".$data['country_id']."', added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."', quotation_number = '".$quotation_number."'";
						//	printr($sql);
							$this->query($sql);
							$productQuatiationId = $this->getLastId();
						
							if(isset($productQuatiationId) && (int)$productQuatiationId > 0 ){
								//quotation currency
								if($customer_email && decode($data['sel_currency']) > 0 ){
									$selCurrecy = $this->getCurrencyInfoOld(decode($data['sel_currency']));
									if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
										$selCurrencyRate = $data['sel_currency_rate'];
									}else{
										$selCurrencyRate = $selCurrecy['price'];
									}									
									$this->query("INSERT INTO ".DB_PREFIX."product_quotation_currency SET product_quotation_id = '".$productQuatiationId."', currency_id = '".$selCurrecy['currency_id']."', currency_code = '".$selCurrecy['currency_code']."', 	currency_rate = '".$selCurrencyRate."', currency_base_rate = '".$selCurrecy['price']."', date_added = NOW() ");
									//printr("INSERT INTO ".DB_PREFIX."product_quotation_currency SET product_quotation_id = '".$productQuatiationId."', currency_id = '".$selCurrecy['currency_id']."', currency_code = '".$selCurrecy['currency_code']."', 	currency_rate = '".$selCurrencyRate."', currency_base_rate = '".$selCurrecy['price']."', date_added = NOW() ");
								}
								//INSERT QUOTATION QUANTITY TABLE 
								//die;
								if(isset($quantityWiseData) && !empty($quantityWiseData)){
									foreach($quantityWiseData as $quantity=>$quantityValue){
									//	printr("INSERT INTO ".DB_PREFIX."product_quotation_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_adding = '".$quantityValue['addingWastage']."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit = '".$quantityValue['profit']."', total_weight_without_zipper = '".$quantityValue['totalWeightWithoutZipper']."', total_weight_with_zipper = '".$quantityValue['totalWeightWithZipper']."', date_added = NOW(),discount='".$data['discount']."'");
										$this->query("INSERT INTO ".DB_PREFIX."product_quotation_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_adding = '".$quantityValue['addingWastage']."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit = '".$quantityValue['profit']."', total_weight_without_zipper = '".$quantityValue['totalWeightWithoutZipper']."', total_weight_with_zipper = '".$quantityValue['totalWeightWithZipper']."', date_added = NOW(),discount='".$data['discount']."'");
										$productQuatiationQuantityId = $this->getLastId();
										//zipperData
										if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
										//	printr($spoutArray);
											foreach($quantityValue['zipperData'] as $zipData){
												$pricesql = "INSERT INTO ".DB_PREFIX."product_quotation_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', zipper_txt = '".$zipData['zip_text']."', valve_txt = '".$zipData['valve_text']."', date_added = NOW(), zipper_price = '".$zipData['calculateZipperPrice']."', spout_txt = '".$zipData['spout_txt']."', spout_base_price = '".$zipData['spout_price']."', accessorie_txt = '".$zipData['accessorie_txt']."', make_pouch = '".(int)$data['make']."',accessorie_base_price = '".$zipData['accessorie_price']."', ";
												//echo $pricesql;die;
												 if(isset($zipData['BySea']) && !empty($zipData['BySea'])){
													$customerGressPrice = 0; 
													$gressPrice = 0;
													$totalPricWithExcies =0;
													$totalPriceWithTax = 0;
													$tax_type='';
													$tax_percentage=0;
													$totalPriceForTax = 0;
													$excies = 0;
													if($customer_gress > 0){
														$customerGressPrice = $this->numberFormate((($zipData['BySea']['totalPriceBySea'] * $customer_gress) / 100),"3");
													}
													if($userGress > 0){
														$gressPrice = $this->numberFormate((($zipData['BySea']['totalPriceBySea'] * $userGress) / 100),"3");
													}
													if(isset($taxation_data) && !empty($taxation_data))
													{
														$totalPriceForTax = $zipData['BySea']['totalPriceBySea']+$gressPrice+$customerGressPrice;
														if($data['discount'])
														{
														$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
														}
														$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
														$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
														$excies =$taxation_data['excies'];
														$tax_type=$taxation;
			
														$tax_percentage=$taxation_data[$taxation];																				
													}	
													$this->query(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$transportPerPouch."', total_price = '".$zipData['BySea']['totalPriceBySea']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
													//printr(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$transportPerPouch."', total_price = '".$zipData['BySea']['totalPriceBySea']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
													//die;
												 }
												 if(isset($zipData['ByAir']) && !empty($zipData['ByAir'])){
													 $customerGressPrice = 0; 
													 $gressPrice = 0;
													 $totalPricWithExcies =0;
													$totalPriceWithTax = 0;
													$tax_type='';
													$tax_percentage=0;
													$totalPriceForTax = 0;
													$excies = 0;
													 if($customer_gress > 0){
														$customerGressPrice = $this->numberFormate((($zipData['ByAir']['totalPriceByAir'] * $customer_gress) / 100),"3");
													}
													 if($userGress > 0){
														$gressPrice = $this->numberFormate((($zipData['ByAir']['totalPriceByAir'] * $userGress) / 100),"3");
													}
													if(isset($taxation_data) && !empty($taxation_data))
													{
														$totalPriceForTax = $zipData['ByAir']['totalPriceByAir']+$gressPrice+$customerGressPrice;
														if($data['discount'])
														{
														$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
														}
														$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
														$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
														$excies =$taxation_data['excies'];
														$tax_type=$taxation;
														$tax_percentage=$taxation_data[$taxation];																				
													}	
													$courierBasePriceWithZipper = 0;
													$courierBasePriceNoZipper = 0;
												
													if(isset($courierBasePriceQuantityWise[$quantity])){
														$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
														$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
													}
													$this->query(" $pricesql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$zipData['courierCharge']."', transport_price = '0', total_price = '".$zipData['ByAir']['totalPriceByAir']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
													//printr(" $pricesql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$zipData['courierCharge']."', transport_price = '0', total_price = '".$zipData['ByAir']['totalPriceByAir']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
												}
												//printr($zipData);die;
												if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup'])){
													 $customerGressPrice = 0; 
													 $gressPrice = 0;
													 $totalPricWithExcies =0;
													$totalPriceWithTax = 0;
													$tax_type='';
													$tax_percentage=0;
													 $totalPriceForTax =0;
													 $excies = 0;
													 if($customer_gress > 0){
														$customerGressPrice = $this->numberFormate((($zipData['ByPickup']['totalPriceByPickup'] * $customer_gress) / 100),"3");
													}
													 if($userGress > 0){
														$gressPrice = $this->numberFormate((($zipData['ByPickup']['totalPriceByPickup'] * $userGress) / 100),"3");
													}
													//printr($taxation_data);
													 if(isset($taxation_data) && !empty($taxation_data))
													{
														$totalPriceForTax = $zipData['ByPickup']['totalPriceByPickup']+$gressPrice+$customerGressPrice;
														if($data['discount'])
														{
														$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
														}
														$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
														$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
														$excies =$taxation_data['excies'];
														$tax_type=$taxation;
														$tax_percentage=$taxation_data[$taxation];																				
													}	
													 $this->query(" $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$zipData['ByPickup']['totalPriceByPickup']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
													//printr(" $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$zipData['ByPickup']['totalPriceByPickup']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
												}									
											}
										}
									}
								}
								
								//BASE PRICE
								$inkDefaultPrice = $this->getInkPrice1($data['make']);
								$inkSolventDefaultPrice = $this->getInkSolventPrice('',0,$data['make']);
								$printingEffectDefaultPrice = $this->getPrintingEffectPrice($data['printing_effect']);
								$adhesiveDefaultPrice = $this->getAdhesivePrice('','',0,$data['make']);
								$adhesiveSolventDefaultPrice = $this->getAdhesiveSolventPrice('','',0,$data['make']);
								$cppAdhesiveDefaultPrice = $this->getCppAdhesivePrice('','',0);
								$transportWidthDefaultPrice = $this->getDefaultTransportWidthPrice($post_width,$gusset);
								$transportHeightDefaultPrice = $this->getDefaulttransportHeightPrice($post_height);
								$cylinderVendorDefaultPrice = $this->getCylinderVendorPrice($data['country_id']);
								//inert base price at a time product quotaion add than taht time real price. use for history
								$this->query("INSERT INTO ".DB_PREFIX."product_quotation_base_price SET product_quotation_id = '".(int)$productQuatiationId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$packingPerPouch."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
							//printr("INSERT INTO ".DB_PREFIX."product_quotation_base_price SET product_quotation_id = '".(int)$productQuatiationId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$packingPerPouch."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
								//die;
								//INAERT DATA FOR LAYER WISE
								if(isset($setQueryData) && !empty($setQueryData)){
									foreach($setQueryData as $key=>$setquery){
										$setSql = "INSERT INTO ".DB_PREFIX."product_quotation_layer SET product_quotation_id = '".(int)$productQuatiationId."', ".$setquery;
										//printr($setSql);
										$this->query($setSql);
									}
								}
							//	die;
								return $productQuatiationId;
							}
					}
					elseif($type=='O')
					{
						//printr($data);die;
						//quotation currency
						if($customer_email && decode($data['sel_currency']) > 0 ){ //&& $data['sel_currency_rate'] > 0
							$selCurrecy = $obj_quotation->getCurrencyInfo(decode($data['sel_currency']));
							if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
								$selCurrencyRate = $data['sel_currency_rate'];
							}else{
								$selCurrencyRate = $selCurrecy['price'];
							}						
						}
						//INSERT QUOTATION QUANTITY TABLE 
						//die;		
						if(isset($quantityWiseData) && !empty($quantityWiseData)){
							foreach($quantityWiseData as $quantity=>$quantityValue){
								if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
								//	printr($spoutArray);
									foreach($quantityValue['zipperData'] as $zipData){									
										 if(isset($zipData['BySea']) && !empty($zipData['BySea'])){
											$customerGressPrice = 0; 
											$gressPrice = 0;
											$totalPricWithExcies =0;
											$totalPriceWithTax = 0;
											$tax_type='';
											$tax_percentage=0;
											$totalPriceForTax = 0;
											$excies = 0;
											if($customer_gress > 0){
												$customerGressPrice = $obj_quotation->numberFormate((($zipData['BySea']['totalPriceBySea'] * $customer_gress) / 100),"3");
											}
											if($userGress > 0){
												$gressPrice = $obj_quotation->numberFormate((($zipData['BySea']['totalPriceBySea'] * $userGress) / 100),"3");
											}
											if(isset($taxation_data) && !empty($taxation_data))
											{
												$totalPriceForTax = $zipData['BySea']['totalPriceBySea']+$gressPrice+$customerGressPrice;
												$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
												$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
												$excies =$taxation_data['excies'];
												$tax_type=$taxation;
												$tax_percentage=$taxation_data[$taxation];																				
											}
											 $total_order_price = $zipData['BySea']['totalPriceBySea'];
										 }
										 if(isset($zipData['ByAir']) && !empty($zipData['ByAir'])){											
											 $customerGressPrice = 0; 
											 $gressPrice = 0;
											 $totalPricWithExcies =0;
											$totalPriceWithTax = 0;
											$tax_type='';
											$tax_percentage=0;
											$totalPriceForTax = 0;
											$excies = 0;
											 if($customer_gress > 0){
												$customerGressPrice = $obj_quotation->numberFormate((($zipData['ByAir']['totalPriceByAir'] * $customer_gress) / 100),"3");
											}
											 if($userGress > 0){
												$gressPrice = $obj_quotation->numberFormate((($zipData['ByAir']['totalPriceByAir'] * $userGress) / 100),"3");
											}
											if(isset($taxation_data) && !empty($taxation_data))
											{
												$totalPriceForTax = $zipData['ByAir']['totalPriceByAir']+$gressPrice+$customerGressPrice;
												$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
												$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
												$excies =$taxation_data['excies'];
												$tax_type=$taxation;
												$tax_percentage=$taxation_data[$taxation];																				
											}	
											$courierBasePriceWithZipper = 0;
											$courierBasePriceNoZipper = 0;
										
											if(isset($courierBasePriceQuantityWise[$quantity])){
												$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
												$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
											}
											 $total_order_price = $zipData['ByAir']['totalPriceByAir'];
										}
										
										if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup'])){
											 $customerGressPrice = 0; 
											 $gressPrice = 0;
											 $totalPricWithExcies =0;
											$totalPriceWithTax = 0;
											$tax_type='';
											$tax_percentage=0;
											 $totalPriceForTax =0;
											 $excies = 0;
											 if($customer_gress > 0){
												$customerGressPrice = $obj_quotation->numberFormate((($zipData['ByPickup']['totalPriceByPickup'] * $customer_gress) / 100),"3");
											}
											 if($userGress > 0){
												$gressPrice = $obj_quotation->numberFormate((($zipData['ByPickup']['totalPriceByPickup'] * $userGress) / 100),"3");
											}
											//printr($taxation_data);
											 if(isset($taxation_data) && !empty($taxation_data))
											{
												$totalPriceForTax = $zipData['ByPickup']['totalPriceByPickup']+$gressPrice+$customerGressPrice;
												$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
												$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
												$excies =$taxation_data['excies'];
												$tax_type=$taxation;
												$tax_percentage=$taxation_data[$taxation];																				
											}											
										 $total_order_price = $zipData['ByPickup']['totalPriceByPickup'];
										}									
									}
								}
							}
						}
						$makeup_query = $this->query("SELECT make_name FROM ".DB_PREFIX."product_make WHERE make_id = '".$data['make']."' ");
				
						$this->query("INSERT INTO ".DB_PREFIX."order_product SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', quantity = '".(int)$quantity."',makeup = '".$makeup_query->row['make_name']."', layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."', packing_price = '".(float)$packingPerPouch."', valve_price = '".$valveBasePrice."', valve_txt = '".$valveTxt."', zipper_txt = '".$zipperText."', zipper_price = '".$zipperCalculatePrice."', spout_txt = '".$spoutTxt."', spout_price = '".$spoutBasePrice."', accessorie_txt = '".$accessorieTxt."', accessorie_price = '".$accessorieBasePrice."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."', native_price_per_bag = '".$pricePerBag."', wastage = '".$wastage."', profit = '".$profit."', total_weight_without_zipper = '".$totalWeightWithoutZipper."', total_weight_with_zipper = '".$totalWeightWithZipper."', transport_type = '".$data['transpotation'][0]."', transport_charge = '".$transportAndCoutierCharge."', total_price = '".$total_order_price."', product_note = '".$data['product_note']."', product_instruction = '".$data['product_special_instruction']."', date_added = NOW(), date_modify=NOW(), is_delete=0, status=1 ");
						$orderProductId = $this->getLastId();
						
						//die;
						//BASE PRICE
						$inkDefaultPrice = $this->getInkPrice1($data['make']);
						$inkSolventDefaultPrice = $this->getInkSolventPrice('',0,$data['make']);
						$printingEffectDefaultPrice = $this->getPrintingEffectPrice($data['printing_effect']);
						$adhesiveDefaultPrice = $this->getAdhesivePrice('','',0,$data['make']);
						$adhesiveSolventDefaultPrice = $this->getAdhesiveSolventPrice('','',0,$data['make']);
						$cppAdhesiveDefaultPrice = $this->getCppAdhesivePrice('','',0);
						$transportWidthDefaultPrice = $this->getDefaultTransportWidthPrice($post_width,$gusset);
						$transportHeightDefaultPrice = $this->getDefaulttransportHeightPrice($post_height);
						$cylinderVendorDefaultPrice = $this->getCylinderVendorPrice($data['shipping_country_id']);
						
						//INAERT DATA FOR LAYER WISE
						//inert base price at a time product quotaion add than taht time real price. use for history
						$this->query("INSERT INTO ".DB_PREFIX."order_product_base_price SET order_product_id = '".(int)$orderProductId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$packingPerPouch."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");	
						if(isset($setQueryData) && !empty($setQueryData)){
							foreach($setQueryData as $key=>$setquery){
								$setSql = "INSERT INTO ".DB_PREFIX."order_product_layer SET order_product_id = '".(int)$orderProductId."', ".$setquery;
								$this->query($setSql);
							}
						}
						
						$returnArray = array();
						$returnArray = array(
							'order_product_id' => $orderProductId,
							'total_price'	   => $total_order_price,
							'product_id'=>$data['product'],
						);
						return $returnArray;
					}
			}
		}
	}
	
	public function addQuotation($data){
	$result = $this->addQuotationFormula($data,'Q');
	return $result;
	}
	
	public function getCurrencyInfoOld($currency_code){
		//$data = $this->query("SELECT * FROM " . DB_PREFIX ."country WHERE country_id = '".(int)$currency_code."'");
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."currency WHERE currency_id = '".(int)$currency_code."'");
		//$data = $this->query("SELECT *,cylinder_currency_id as currency_id FROM " . DB_PREFIX ."cylinder_currency WHERE cylinder_currency_id = '".(int)$currency_id."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getToolPrice($width,$gusset,$product_id){
	$cond = '';
	$cond1 ='';
	$sql1 = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."')
	AND gusset = '".$gusset."' LIMIT 1";
	 $data1 = $this->query($sql1);
	
	 if(!$data1->num_rows)
	 {
		if(isset($gusset) && $gusset>0)
		{
		//	$cond = "AND ( gusset < '".(int)$gusset."') ORDER BY gusset,width_to  ASC";
			$cond = " ORDER BY gusset,width_to  ASC";
		}
		else
		{
			$cond1 = " ORDER BY width_to ASC LIMIT 1";
		}	
			$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND (width_to > '".(int)$width."'
	) ".$cond1."";
	//echo $sql;die;
			$data = $this->query($sql);
	
		if($data->num_rows >1)
		{
			$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to > '".(int)$width."') ".$cond." LIMIT 1";
	//	echo "SELECT * FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to > '".(int)$width."') ".$cond." LIMIT 1";
	//	echo $sql ;
			$data = $this->query($sql);
		}
		//die;
		//printr($data);die;
	//	echo  $sql;
			if($data->num_rows){
				return $data->row['price'];
			}else{
				return false;
			}
	 }
	 else
	 {
		return 0;
	 }
	}
	
	public function getWidthSuggestion($width,$product_id){
	
	
	$sql1 = "SELECT width_to FROM product_extra_tool_price WHERE product_id = '" .(int)$product_id. "' AND width_to = '".$width."'";
	$data1 = $this->query($sql1);
	
	if($data1->num_rows)
	{
		
		return false;
		
	}
	else
	{
	
	$sql = "SELECT width_to
	FROM
( ( SELECT width_to,".$width."-width_to AS diff
    FROM product_extra_tool_price
    WHERE product_id = '" .(int)$product_id. "'
      AND width_to < ".$width."
    ORDER BY width_to DESC
      
  ) 
  UNION ALL
  ( SELECT width_to,width_to-".$width." AS diff
    FROM product_extra_tool_price
    WHERE product_id = '" .(int)$product_id. "'
      AND width_to > ".$width."
    ORDER BY width_to ASC
     
  ) 
) AS tmp
ORDER BY diff
LIMIT 2" ;
	
	//echo $sql;
	//die;
	$data = $this->query($sql);
	if($data->num_rows){
		return $data->rows;
	}else{
		return false;
	}
	}
	
}
public function getGussetSuggestion($width,$gusset,$product_id){
	$result = '';
	if($gusset!='')
	{
		$result = "AND gusset = '".$gusset."'";	
	}
		$cond = '';
	$cond1 ='';
	$sql1 = "SELECT price,width_to,gusset FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."') LIMIT 1";
	 $data1 = $this->query($sql1);
	 if( $data1->num_rows > 0)
	 {
		 if($gusset!='')
		 {
		 if($data1->row['gusset']==$gusset)
		 {
			 return 0;
		 }
		
		 elseif($data1->row['width_to']==$width && $data1->row['gusset']!=$gusset)
		 {
			 //echo $gusset;
			 return $data1->rows;
		 }
		 }
		 else
		 {
			 return 0;
		 }
		 
	 }
		 else
		 {
		 
	//printr($data1);die;
	 if(!$data1->num_rows)
	 {
		if(isset($gusset) && $gusset>0)
		{
		//	$cond = "AND ( gusset < '".(int)$gusset."') ORDER BY gusset,width_to  ASC";
			$cond1 = " LIMIT 1";
		}
		else
		{
			//$cond1 = " ORDER BY width_to ASC LIMIT 1";
			$cond1 = " LIMIT 1";
		}	
		
	$sql = "SELECT price,width_to,gusset
	FROM
( ( SELECT price,width_to,gusset,".$width."-width_to AS diff
    FROM product_extra_tool_price
    WHERE product_id = '" .(int)$product_id. "' AND width_to >'".$width."'
      ".$cond1."   
  ) 
  UNION ALL
  ( SELECT price,width_to,gusset,width_to-".$width." AS diff
    FROM product_extra_tool_price
    WHERE product_id = '" .(int)$product_id. "' AND width_to <'".$width."'
     ".$cond1."  
  ) 
) AS tmp
ORDER BY diff
LIMIT 2" ;
	$data = $this->query($sql);
	if($data->num_rows){
				return $data->rows;
				//return $sql;
			}else{
				return false;
			}
	 }
		 }
	
	
}

	
	public function getActiveProduct(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	//ruchi
	public function getActiveMake(){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY make_name";	
		$sql .= " ASC";
			//	echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	//ruchi
	
	public function getActiveClient(){
		$sql = "SELECT *,CONCAT(first_name,' ',last_name) as name FROM " . DB_PREFIX . "client WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY first_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveLayer(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_layer` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY layer";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductZippers(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	//spout
	public function getActiveProductSpout(){
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	//product accessorie
	public function getActiveProductAccessorie(){
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductOption(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_option` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY option_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActivePrintingEffectEnquiry(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_effect` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY effect_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActivePrintingEffect($material_id){
		$sql = "SELECT e.* FROM `" . DB_PREFIX . "product_printing_effect` p , `" . DB_PREFIX . "printing_effect` e WHERE p.material_id ='".$material_id."'AND p.effect_id=e.printing_effect_id ";
		$sql .= " ORDER BY e.effect_name";	
		$sql .= " ASC";
		//		echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			//printr($data);
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getOption(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_option` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY option_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function deleteQuotation($quotation_id){
		$sql = "DELETE pq.*,pql.*,pqp.* FROM `" . DB_PREFIX . "product_quotation` pq LEFT JOIN `" . DB_PREFIX . "product_quotation_layer` pql ON  pq.product_quotation_id=pql.product_quotation_id LEFT JOIN `" . DB_PREFIX . "product_quotation_price` pqp ON pq.product_quotation_id=pqp.product_quotation_id LEFT JOIN `" . DB_PREFIX . "product_quotation_quantity` pqq ON pq.product_quotation_id=pqq.product_quotation_id LEFT JOIN `" . DB_PREFIX . "product_quotation_base_price` pqbp ON pq.product_quotation_id=pqbp.product_quotation_id WHERE pq.product_quotation_id='".$quotation_id."'";
		$this->query($sql);	
	}
	
	public function setQuotation(){
		if(isset($productQuatiationId) && (int)$productQuatiationId > 0 ){
					//quotation currency
					if($customer_email && decode($data['sel_currency']) > 0 ){ //&& $data['sel_currency_rate'] > 0
						$selCurrecy = $this->getCurrencyInfo(decode($data['sel_currency']));
						if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
							$selCurrencyRate = $data['sel_currency_rate'];
						}else{
							$selCurrencyRate = $selCurrecy['price'];
						}
						$this->query("INSERT INTO ".DB_PREFIX."product_quotation_currency SET product_quotation_id = '".$productQuatiationId."', currency_id = '".$selCurrecy['currency_id']."', currency_code = '".$selCurrecy['currency_code']."', 	currency_rate = '".$selCurrencyRate."', currency_base_rate = '".$selCurrecy['price']."', date_added = NOW() ");
					}
					//INSERT QUOTATION QUANTITY TABLE 
					//printr($quantityWiseData);die;
					if(isset($quantityWiseData) && !empty($quantityWiseData)){
						foreach($quantityWiseData as $quantity=>$quantityValue){
							$this->query("INSERT INTO ".DB_PREFIX."product_quotation_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_adding = '".$quantityValue['addingWastage']."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit = '".$quantityValue['profit']."', total_weight_without_zipper = '".$quantityValue['totalWeightWithoutZipper']."', total_weight_with_zipper = '".$quantityValue['totalWeightWithZipper']."', date_added = NOW()");
							$productQuatiationQuantityId = $this->getLastId();
							//zipperData
							if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
								foreach($quantityValue['zipperData'] as $zipData){
									$pricesql = "INSERT INTO ".DB_PREFIX."product_quotation_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', zipper_txt = '".$zipData['zip_text']."', valve_txt = '".$zipData['valve_text']."', date_added = NOW(), zipper_price = '".$zipData['calculateZipperPrice']."',  ";
									 if(isset($zipData['BySea']) && !empty($zipData['BySea'])){
										$customerGressPrice = 0; 
										$gressPrice = 0;
										if($customer_gress > 0){
											$customerGressPrice = $this->numberFormate((($zipData['BySea']['totalPriceBySea'] * $customer_gress) / 100),"3");
										}
										if($userGress > 0){
											$gressPrice = $this->numberFormate((($zipData['BySea']['totalPriceBySea'] * $userGress) / 100),"3");
										}
										$this->query(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$transportPerPouch."', total_price = '".$zipData['BySea']['totalPriceBySea']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
									 }
									 if(isset($zipData['ByAir']) && !empty($zipData['ByAir'])){
										 $customerGressPrice = 0; 
										 $gressPrice = 0;
										 if($customer_gress > 0){
										 	$customerGressPrice = $this->numberFormate((($zipData['ByAir']['totalPriceByAir'] * $customer_gress) / 100),"3");
										 }
										 if($userGress > 0){
											$gressPrice = $this->numberFormate((($zipData['ByAir']['totalPriceByAir'] * $userGress) / 100),"3");
										 }
										$courierBasePriceWithZipper = 0;
										$courierBasePriceNoZipper = 0;
										if(isset($courierBasePriceQuantityWise[$quantity])){
											$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
											$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
										}
										$this->query(" $pricesql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$zipData['courierCharge']."', transport_price = '0', total_price = '".$zipData['ByAir']['totalPriceByAir']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
									}
									if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup'])){
										 $customerGressPrice = 0; 
										 $gressPrice = 0;
										 if($customer_gress > 0){
										 	$customerGressPrice = $this->numberFormate((($zipData['ByPickup']['totalPriceByPickup'] * $customer_gress) / 100),"3");
										 }
										 if($userGress > 0){
											$gressPrice = $this->numberFormate((($zipData['ByPickup']['totalPriceByPickup'] * $userGress) / 100),"3");
										 }
										 $this->query(" $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$zipData['ByPickup']['totalPriceByPickup']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
									}
								}
							}
							//Spout Data
							//printr($quantityValue['spoutData']);die;
							if(isset($quantityValue['spoutData']) && !empty($quantityValue['spoutData'])){
								foreach($quantityValue['spoutData'] as $spoutData){
									$spoutPriceSql = "INSERT INTO ".DB_PREFIX."product_quotation_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', zipper_txt = '', valve_txt = '', date_added = NOW(), spout_txt = '".$spoutData['spout_txt']."', spout_base_price = '".$spoutData['price']."',  ";
									 if(isset($spoutData['BySea']) && !empty($spoutData['BySea'])){
										$customerGressPrice = 0; 
										$gressPrice = 0;
										if($customer_gress > 0){
											$customerGressPrice = $this->numberFormate((($spoutData['BySea']['totalPriceBySea'] * $customer_gress) / 100),"3");
										}
										if($userGress > 0){
											$gressPrice = $this->numberFormate((($spoutData['BySea']['totalPriceBySea'] * $userGress) / 100),"3");
										}
										$this->query(" $spoutPriceSql transport_type = 'sea',  courier_charge = '0', transport_price = '".$transportPerPouch."', total_price = '".$spoutData['BySea']['totalPriceBySea']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
									 }
									 if(isset($spoutData['ByAir']) && !empty($spoutData['ByAir'])){
										 $customerGressPrice = 0; 
										 $gressPrice = 0;
										 if($customer_gress > 0){
										 	$customerGressPrice = $this->numberFormate((($spoutData['ByAir']['totalPriceByAir'] * $customer_gress) / 100),"3");
										 }
										 if($userGress > 0){
											$gressPrice = $this->numberFormate((($spoutData['ByAir']['totalPriceByAir'] * $userGress) / 100),"3");
										 }
										$courierBasePriceWithZipper = 0;
										$courierBasePriceNoZipper = 0;
										if(isset($courierBasePriceQuantityWise[$quantity])){
											$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
											$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
										}
										
										$this->query(" $spoutPriceSql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$spoutData['courierCharge']."', transport_price = '0', total_price = '".$spoutData['ByAir']['totalPriceByAir']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
									}
									
									if(isset($spoutData['ByPickup']) && !empty($spoutData['ByPickup'])){
										 $customerGressPrice = 0; 
										 $gressPrice = 0;
										 if($customer_gress > 0){
										 	$customerGressPrice = $this->numberFormate((($spoutData['ByPickup']['totalPriceByPickup'] * $customer_gress) / 100),"3");
										 }
										 if($userGress > 0){
											$gressPrice = $this->numberFormate((($spoutData['ByPickup']['totalPriceByPickup'] * $userGress) / 100),"3");
										 }
										 $this->query(" $spoutPriceSql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$spoutData['ByPickup']['totalPriceByPickup']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
									}
								}
							}
							
							//Accessorie
							if(isset($quantityValue['accessorieData']) && !empty($quantityValue['accessorieData'])){
								foreach($quantityValue['accessorieData'] as $accessorieData){
									$spoutPriceSql = "INSERT INTO ".DB_PREFIX."product_quotation_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', zipper_txt = '', valve_txt = '', date_added = NOW(), accessorie_txt = '".$accessorieData['accessorie_txt']."', accessorie_base_price = '".$accessorieData['price']."',  ";
									 if(isset($spoutData['BySea']) && !empty($accessorieData['BySea'])){
										$customerGressPrice = 0; 
										$gressPrice = 0;
										if($customer_gress > 0){
											$customerGressPrice = $this->numberFormate((($accessorieData['BySea']['totalPriceBySea'] * $customer_gress) / 100),"3");
										}
										if($userGress > 0){
											$gressPrice = $this->numberFormate((($accessorieData['BySea']['totalPriceBySea'] * $userGress) / 100),"3");
										}
										$this->query(" $spoutPriceSql transport_type = 'sea',  courier_charge = '0', transport_price = '".$transportPerPouch."', total_price = '".$accessorieData['BySea']['totalPriceBySea']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
									 }
									 if(isset($spoutData['ByAir']) && !empty($accessorieData['ByAir'])){
										 $customerGressPrice = 0; 
										 $gressPrice = 0;
										 if($customer_gress > 0){
										 	$customerGressPrice = $this->numberFormate((($accessorieData['ByAir']['totalPriceByAir'] * $customer_gress) / 100),"3");
										 }
										 if($userGress > 0){
											$gressPrice = $this->numberFormate((($accessorieData['ByAir']['totalPriceByAir'] * $userGress) / 100),"3");
										 }
										$courierBasePriceWithZipper = 0;
										$courierBasePriceNoZipper = 0;
										if(isset($courierBasePriceQuantityWise[$quantity])){
											$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
											$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
										}
										
										$this->query(" $spoutPriceSql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$accessorieData['courierCharge']."', transport_price = '0', total_price = '".$accessorieData['ByAir']['totalPriceByAir']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
									}
									if(isset($accessorieData['ByPickup']) && !empty($accessorieData['ByPickup'])){
										 $customerGressPrice = 0; 
										 $gressPrice = 0;
										 if($customer_gress > 0){
										 	$customerGressPrice = $this->numberFormate((($accessorieData['ByPickup']['totalPriceByPickup'] * $customer_gress) / 100),"3");
										 }
										 if($userGress > 0){
											$gressPrice = $this->numberFormate((($accessorieData['ByPickup']['totalPriceByPickup'] * $userGress) / 100),"3");
										 }
										 $this->query(" $spoutPriceSql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$accessorieData['ByPickup']['totalPriceByPickup']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
									}
								}
							}
							//Accessorie Close
						}
					}
					
					//BASE PRICE
					$inkDefaultPrice = $this->getInkPrice1();
					$inkSolventDefaultPrice = $this->getInkSolventPrice('',0);
					$printingEffectDefaultPrice = $this->getPrintingEffectPrice($data['printing_effect']);
					$adhesiveDefaultPrice = $this->getAdhesivePrice('','',0);
					$adhesiveSolventDefaultPrice = $this->getAdhesiveSolventPrice('','',0);
					$cppAdhesiveDefaultPrice = $this->getCppAdhesivePrice('','',0);
					//$packingWidthDefaultPrice = $this->getDefaultPackingWidthPrice($post_width);
					//$packingHeightDefaultPrice = $this->getDefaultPackingHeightPrice($post_height);
					$transportWidthDefaultPrice = $this->getDefaultTransportWidthPrice($post_width,$gusset);
					$transportHeightDefaultPrice = $this->getDefaulttransportHeightPrice($post_height);
					$cylinderVendorDefaultPrice = $this->getCylinderVendorPrice($data['country_id']);
					//inert base price at a time product quotaion add than taht time real price. use for history
					$this->query("INSERT INTO ".DB_PREFIX."product_quotation_base_price SET product_quotation_id = '".(int)$productQuatiationId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$packingPerPouch."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
					
					//INAERT DATA FOR LAYER WISE
					if(isset($setQueryData) && !empty($setQueryData)){
						foreach($setQueryData as $key=>$setquery){
							$setSql = "INSERT INTO ".DB_PREFIX."product_quotation_layer SET product_quotation_id = '".(int)$productQuatiationId."', ".$setquery;
							$this->query($setSql);
						}
					}
					return $productQuatiationId;
				}else{
					return true;
				}
	}
	
	//for roll quotation
	public function addRollQuotation($data){
		//printr($data);die;
		//post_height = Repeat Length in roll calcualtion
		$post_height = (int)$data['height'];//Repeat Length
		$post_width = (int)$data['width'];
		//$quantity = (int)$data['quantity'];
		$product_id = (int)$data['product'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$userInfo = $this->getUserInfo($user_type_id,$user_id);
		
		$productName = getName('product','product_id',$product_id,'product_name');
		if(strtolower($productName) != "roll"){
			return "Error";
		}else
		{	
			//price per 1000 pouch add + 20 in width
			$actualWidth = $this->numberFormate((($post_width + 20) / 1000),"5");
			$actualHeight = $this->numberFormate(($post_height  / 1000),"5");
			//echo $actualWidth;die;
			
			if(isset($data['material']) && !empty($data['material']) && isset($data['thickness']) && !empty($data['thickness'])){
				
				$total_material = count($data['material']);
				$layerPrice = array();
				$materialName = '';
				$checkCppMaterial = 0;
				for($p=0;$p<$total_material;$p++){
					
					$setNumber = $p.'0';
					$addingActualWidth = ( $setNumber / 1000 );
					$newLayerWiseWidth = ( $actualWidth + $addingActualWidth);
					$checkCppMaterial = $this->checkMaterial($data['material'][$p]);
					
					$gsm[$p+1] = $this->getMaterialGsm($data['material'][$p]);
					$thicknessPrice = $this->getMaterialThickmessPrice($data['material'][$p],$data['thickness'][$p]);
					
					//$layerWiseThickness[$p+1] = $data['thickness'][$p];
					$layerWisseGsmIntoThickness[$p+1] = $this->numberFormate(($gsm[$p+1] * $data['thickness'][$p]),"5");
					//##################### PRICE PER KG/1000 POUCH START
					$layerWisePricePerKgGsmThickness[$p+1] = $this->getLayerWiseGsmThickness($this->numberFormate(($post_width / 1000),"5"),$this->numberFormate(($post_height / 1000),"5"),$data['thickness'][$p],$gsm[$p+1]);		
					//##################### PRICE PER KG/1000 POUCH CLOSE
					
					//##################### PRICE PER price /1000 POUCH START
					//echo $newLayerWiseWidth."===".$actualHeight."===".$data['thickness'][$p]."===".$gsm[$p+1];die;
					//echo $newLayerWiseWidth."===".$actualHeight."===".$data['thickness'][$p]."==".$gsm[$p+1]."==<br>";;
					$layerWisePricePer1000GsmThickness[$p+1] = $this->getLayerWiseGsmThickness($newLayerWiseWidth,$actualHeight,$data['thickness'][$p],$gsm[$p+1]);
					$layerPricePer1000[$p+1] = $this->getLayerPrice($layerWisePricePer1000GsmThickness[$p+1],$thicknessPrice);
					//##################### PRICE PER price /1000 POUCH CLOSE
					
					//####### SET SQL QUERY DATA 
					$materialName = getName('product_material','product_material_id',$data['material'][$p],'material_name');
					$setQueryData[] = " layer = '".(int)($p+1)."', material_id = '".(int)$data['material'][$p]."', material_gsm = '".(float)$gsm[$p+1]."', material_thickness = '".$data['thickness'][$p]."', material_price = '".(float)$thicknessPrice."', material_name = '".$materialName."', layer_wise_gsmthickness = '".(float)$layerWisePricePer1000GsmThickness[$p+1]."', layer_wise_price = '".(float)$layerPricePer1000[$p+1]."', date_added = NOW()";
					
				}
				//printr($layerWisePricePer1000GsmThickness);die;
				//printr($layerPricePer1000);die;
				//###### kgs / 1000 pouch
				$priceKgsPer1000 = $this->sumOfNumericArray($layerWisePricePerKgGsmThickness);
				//echo $priceKgsPer1000;die;
				//printr($layerWisePricePerKg);die;
				
				//printr($layerWiseGsmThickness);
				//printr($layerPrice);die;
				$totalLayer = count($data['material']);
				$layerCount = (isset($p))?$p:'';
				
				//printr($layerPricePer1000);die;
				//total layer price
				$totalLayerPrice = $this->sumOfNumericArray($layerPricePer1000);
				//echo $totalLayerPrice;die;
				//echo $layerWisePricePer1000GsmThickness[1];die;
				if(isset($data['printing']) && $data['printing'] == 1){
					/*$printing_option = "With Printing";
					$onlyInkPrice = $this->getInkPrice($layerWisePricePerKgGsmThickness[1],1);
					$inkSolventPrice = $this->getInkSolventPrice($layerWisePricePerKgGsmThickness[1],1);
					$printingEffectPrice = 0;
					if(isset($data['printing_effect']) && !empty($data['printing_effect']) && (int)$data['printing_effect'] > 0){
						$printingEffectPrice = $this->getPrintingEffectPrice($data['printing_effect']);
					}
					$inkPrice = ($onlyInkPrice + $printingEffectPrice);*/
					
					$printing_option = "With Printing";
					$onlyInkPrice = $this->getInkPrice1();//$this->getInkPrice($layerWiseGsmThickness[1],1);
					$inkSolventPrice = $this->getInkSolventPrice($layerWisePricePerKgGsmThickness[1],1);
					$printingEffectPrice = 0;
					if(isset($data['printing_effect']) && !empty($data['printing_effect']) && (int)$data['printing_effect'] > 0){
						$printingEffectPrice = $this->getPrintingEffectPrice($data['printing_effect']);
					}
					$inkPrice = (($onlyInkPrice + $printingEffectPrice) * $layerWisePricePerKgGsmThickness[1]);
				}else{
					$printing_option = "Without Printing";
					$onlyInkPrice = 0;
					$printingEffectPrice = 0;
					$inkPrice = 0;
					$inkSolventPrice = 0;
				}
				
				//Adhesive and adhesive solvent
				$adhesivePrice = $this->getAdhesivePrice($layerWisePricePerKgGsmThickness[1],$layerCount,1);
				$adhesiveSolventPrice = $this->getAdhesiveSolventPrice($layerWisePricePerKgGsmThickness[1],$layerCount,1);
				//echo $layerWisePricePer1000GsmThickness[1]."==".$adhesivePrice."===".$adhesiveSolventPrice;die;
				
				//Total Price : SUM of all price and calculate average price
				//echo $totalLayerPrice."===".$inkPrice."===".$inkSolventPrice."===".$adhesivePrice."===".$adhesiveSolventPrice;die;
				$totalPrice = $this->numberFormate(($totalLayerPrice + $inkPrice + $inkSolventPrice + $adhesivePrice + $adhesiveSolventPrice),"3") ;
				//echo $totalPrice;die;
				
				//START ################ POUCH / KG
					$layerWiseGsmIntoThicknessSum = $this->sumOfNumericArray($layerWisseGsmIntoThickness);
					//adding 6 by default
					$layerWiseGsmIntoThicknessSum1 = $this->numberFormate(($layerWiseGsmIntoThicknessSum + 6),"5");
					//echo $layerWiseGsmIntoThicknessSum1;die;
					//echo $post_height."===".$post_width."===".$layerWiseGsmIntoThicknessSum1;die;
					$pouchPerKgFormulla = $this->numberFormate(($post_height * $post_width * $layerWiseGsmIntoThicknessSum1),"5");
					//echo $pouchPerKgFormulla;die;
					//after get result 1000 / get result
					//price pouch / kg
					$pouchPerKgFormulla1 = $this->numberFormate(((1000 / $pouchPerKgFormulla ) * 1000000),"5");
					//echo $pouchPerKgFormulla1;die;
					
				//CLOSE ################ POUCH / KG
				
				//START ################ PRICE / MTR
					//echo $pouchPerKgFormulla1;die;
					$pricePerMtrFormulaNew = $this->numberFormate(($pouchPerKgFormulla1 * $post_height) ,"5");
					//echo $pricePerMtrFormulaNew;die;
					$pricePerMtrFormulaNew1 = $this->numberFormate(($pricePerMtrFormulaNew / 1000) ,"5");
					
				//CLOSE ################ PRICE / MTR
				
				//$quantity
				$quantityInMtr = 0;
				$quantityInKg = 0;
				$quantityInPieces = 0;
				$quantity_type = $data['quantity_type'];
				
				//COURIER AND TRANSPORT CALCULATION
				$transportByAir = 0;
				$transportBySea = 0;
				if(isset($data['transpotation']) && !empty($data['transpotation']) ){
					if(in_array('air',$data['transpotation'])){
						$transportByAir = 1;
					}else{
						$transportBySea = 1;
					}
					
					if(in_array('sea',$data['transpotation'])){
						$transportBySea = 1;
					}else{
						$transportByAir = 1;
					}
				}else{
					$transportBySea = 1;
				}
				
				$fual_surcharge_base_price = 0;
				$service_tax_base_price = 0;
				$handling_base_price = 0;
				if($transportByAir){
					$countryCourierData = $this->getCountryCourier($data['country_id']);
					$fual_surcharge_base_price = $countryCourierData['fuel_surcharge'];
					$service_tax_base_price = $countryCourierData['service_tax'];
					$handling_base_price = $countryCourierData['handling_charge'];
				}
				
				//new code for multipale quantity
				$userGress = $userInfo['gres'];
				$customer_gress = 0;
				$customer_email = '';
				if(isset($data['customer_check']) ){
					$customer_email = (isset($data['customer_email']) && $data['customer_email'] != '')?$data['customer_email']:'';
					$customer_gress =  (isset($data['customer_gress']) && (int)$data['customer_gress'] > 0 )?(int)$data['customer_gress']:0;
				}
				$quantityArray = $data['quantity'];
				$quantityWiseData = array();
				foreach($quantityArray as $key=>$eQuantity){
					$quantity = decode($eQuantity);
					
					if($quantity_type == 'kg'){
						$quantityInKg = $quantity;
					}elseif($quantity_type == 'pieces'){
						$quantityInPieces = $quantity;
					}else{
						$quantityInMtr = $quantity;
					}
					
					//START ################ TOTAL QUANTITY IN KGS
						$totalQuantityInKgs = $this->numberFormate(($quantityInMtr / $pricePerMtrFormulaNew1) ,"5");
					//CLOSE ################ TOTAL QUANTITY IN KGS
					$kgs = $totalQuantityInKgs;
					$piece = $this->numberFormate(($quantityInPieces / $pouchPerKgFormulla1),"5");
					
					//Total kgs
					$totalKgs = $this->numberFormate(($quantityInKg + $kgs + $piece),"5");
					//echo $totalKgs;die;
					//Wastage
					$wastageBasePrice = $this->getRollWastage($totalKgs);
					//echo $wastagePrice;die;
					$wastage = $this->numberFormate((($wastageBasePrice * $totalPrice) / 100 ),"5");
					
					//START ########## PRICE / 1000 POUCH
						$pricePer1000 = ($totalPrice + $wastage);
					//CLOSE ########## PRICE / 1000 POUCH
					
					//START ################ PRICE / KG
						$pricePerKgFormula = $this->numberFormate(($pricePer1000 / 1000),"5");
						$pricePerKgFormula1 = $this->numberFormate(($pouchPerKgFormulla1 * $pricePerKgFormula),"5");
					//CLOSE ################ PRICE / KG
					
					//START ################ PRICE / MTR
						$pricePerMtrFormula = $this->numberFormate((($pouchPerKgFormulla1 * $post_height) / 1000) ,"5");
						$pricePerMtrFormula1 = $this->numberFormate(($pricePerKgFormula1 / $pricePerMtrFormula),"5");
					//CLOSE ################ PRICE / MTR
					
					$mtr = $this->numberFormate(($quantityInKg * $pricePerMtrFormula1),"5");
					
					$kg1 = $this->numberFormate(($totalQuantityInKgs * $pouchPerKgFormulla1 ),"5");
					$mtr1 = $this->numberFormate(($quantityInKg * $pouchPerKgFormulla1 ),"5");
					$piece1 = $this->numberFormate(($piece * $pricePerMtrFormula ),"5");
					
					//Total mater
					$totalMtr = $this->numberFormate(($quantityInMtr + $mtr + $piece1),"5");
					//Total Pieces
					$totalPiece = $this->numberFormate(($quantityInPieces + $mtr1 + $kg1),"5");
					
					
					//START ################ TOTAL CHARGE
						$profitPrice = $this->getRollProfit($totalKgs);
						//echo $profitPrice;die;
						$profitPerKg = 0;
						$profitPerPiece = 0;
						$profitPerMtr = 0;
						$profitForKg = ( $totalKgs * $profitPrice);
						$totalProfit = $this->numberFormate($profitForKg,"5");
						
						//total packing charge
						$packingPrice = $this->getRollPackingPrice($totalKgs);
						//echo $packingPrice;die;
						$totalPackingCharge = $this->numberFormate(($totalKgs * $packingPrice),"5");
						
						$fuleSurcharge  = 0;
						$serviceTax = 0;
						$handlingCharge = 0;
						$totalTransportCharge = 0;
						$courierCharge = 0;
						$transportPrice = 0;
						$byAir = '';
						$bySea = '';
						if($transportByAir){
							$courierCharge0 = $this->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'], $totalKgs);
							if($fual_surcharge_base_price > 0){
								$fuleSurcharge = (($courierCharge0 * $fual_surcharge_base_price) / 100);
							}
							if($service_tax_base_price > 0){
								$courierCrhgFule = ($courierCharge0 + $fuleSurcharge);
								$serviceTax = (($courierCrhgFule * $service_tax_base_price) / 100);
							}
							if($handling_base_price > 0){
								$handlingCharge = $handling_base_price;
							}
							$courierCharge = $this->numberFormate(($courierCharge0 + $fuleSurcharge + $serviceTax + $handlingCharge),"3");
							$totalCharge = $this->numberFormate(($totalProfit + $totalPackingCharge),"5");
							$addingGressPrice = 0;
							$pricePerUnit = 0;
							$totalPrice = 0;
							// price in mtr / unit
							if($quantityInMtr > 0){
								//Total Price  mater
								$totalPriceMtr = $this->numberFormate(($quantityInMtr * $pricePerMtrFormula1),"5");
								$totalPrice = $this->numberFormate(($totalPriceMtr + $totalCharge + $courierCharge),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInMtr),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $userGress) / 100);
								}
								
							}
							// price in KGS / unit
							if($quantityInKg > 0){
								//Total Price kgs
								$totalPriceKgs = $this->numberFormate(($quantityInKg * $pricePerKgFormula1 ),"5");
								$totalPrice = $this->numberFormate(($totalPriceKgs + $totalCharge + $courierCharge),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInKg),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $userGress) / 100);
								}
							}
							// price in PIECE / unit
							if($quantityInPieces > 0){
								//Total PRICE Pieces
								$totalPricePiece = $this->numberFormate(($quantityInPieces * ( $pricePer1000 / 1000 )),"5");
								$totalPrice = $this->numberFormate(($totalPricePiece + $totalCharge + $courierCharge ),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInPieces),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $userGress) / 100);
								}
							}
							
							$byAir = array(
								'courierCharge'	=> $courierCharge,
								'totalCharge'		=> $totalCharge,
								'totalPrice'		 => $totalPrice,
								//'pricePerUnit'	   => $pricePerUnit,
								'addingPrice'		=> $addingGressPrice,
							);
						}
						
						if($transportBySea){
							//total transport price
							$transportPrice = $this->getRollTransportPrice($totalKgs);
							$totalTransportCharge = $this->numberFormate(($totalKgs * $transportPrice),"5");
							$totalCharge = $this->numberFormate(($totalProfit + $totalPackingCharge + $totalTransportCharge),"5");
							
							$addingGressPrice = 0;
							$pricePerUnit = 0;
							$totalPrice = 0;
							// price in mtr / unit
							if($quantityInMtr > 0){
								//Total Price  mater
								$totalPriceMtr = $this->numberFormate(($quantityInMtr * $pricePerMtrFormula1),"5");
								$totalPrice = $this->numberFormate(($totalPriceMtr + $totalCharge),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInMtr),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $userGress) / 100);
								}
							}
							// price in KGS / unit
							if($quantityInKg > 0){
								//Total Price kgs
								$totalPriceKgs = $this->numberFormate(($quantityInKg * $pricePerKgFormula1 ),"5");
								$totalPrice = $this->numberFormate(($totalPriceKgs + $totalCharge ),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInKg),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $userGress) / 100);
								}
							}
							// price in PIECE / unit
							if($quantityInPieces > 0){
								//Total PRICE Pieces
								$totalPricePiece = $this->numberFormate(($quantityInPieces * ( $pricePer1000 / 1000 )),"5");
								$totalPrice = $this->numberFormate(($totalPricePiece + $totalCharge ),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInPieces),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $userGress) / 100);
								}
							}
							
							$bySea = array(
								'transportCharge'	=> $totalTransportCharge,
								'transportBasePrice' => $transportPrice,
								'totalCharge'		=> $totalCharge,
								'totalPrice'		 => $totalPrice,
								//'pricePerUnit'	   => $pricePerUnit,
								'addingPrice'		=> $addingGressPrice,
							);
						}
						
						//echo $totalCharge;die;
					 //CLOSE ################ TOTAL CHARGE 
					
						//store quantity wise information
						$quantityWiseData[$quantity] = array(
							'wastageBase'	=> $wastageBasePrice,
							'wastage'		=> $wastage,
							'nativePricePerBag' => $pricePer1000,
							'totalKgs'	   => $totalKgs,
							'totalMtr'	   => $totalMtr,
							'totalPiece'	 => $totalPiece,
							'packingBase'	=> $packingPrice,
							'packingCharge'  => $totalPackingCharge,
							'totalQuantityInKgs'	=> $totalQuantityInKgs,
							'profitBase'	 => $profitPrice,
							'profit'		 => $totalProfit,
							'ByAir'		  => $byAir,
							'BySea'		  => $bySea,
						);
				}
				//printr($quantityWiseData);die;
				
				//user country and currency info
				$userCountry = $this->getUserCountry($user_type_id,$user_id);
				$userCurrency = $this->getCurrencyInfo($userCountry['currency_id']);
				//Cylinder Price
				$cylinderPrice = $this->getCalculateCylinderPrice($post_height,$post_width);
				
				$cylinderCurrencyPrice = $cylinderPrice;
				if($userCurrency['price']){
					$cylinderCurrencyPrice = ($cylinderPrice / $userCurrency['price']);
				}
				$cylinderCurrencyMinPrice = $this->getCylinderBasePrice($userCurrency['currency_id']);
				if($cylinderCurrencyMinPrice && $cylinderCurrencyMinPrice >= $cylinderCurrencyPrice ){
					$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
				}else{
					$cylinderCurrencyBasePrice = $cylinderCurrencyPrice;
				}
				
				$cylinderCurrencyMinPrice = $this->getCylinderBasePrice($userCurrency['currency_id']);
				
				/*$returnArray = array();
				
				$returnArray[] = array(
					'title'	=> 'layerWisePricePerKgGsmThickness',
					'value'	=> $layerWisePricePer1000GsmThickness,
				);
				
				$returnArray[] = array(
					'title'	=> 'kgs / 1000 pouch',
					'value'	=> $priceKgsPer1000,
				);
				
				$returnArray[] = array(
					'title'	=> 'Total Layer Price',
					'value'	=> $totalLayerPrice,
				);
				
				$returnArray[] = array(
					'title'	=> 'Wastage Price',
					'value'	=> $wastagePrice,
				);
				
				$returnArray[] = array(
					'title'	=> 'Wastage',
					'value'	=> $wastage,
				);
				
				$returnArray[] = array(
					'title'	=> 'profit price',
					'value'	=> $profitPrice,
				);
				
				
				$returnArray[] = array(
					'title'	=> 'profit for kg',
					'value'	=> $totalProfit ,
				);
				
				$returnArray[] = array(
					'title'	=> 'Packing charge',
					'value'	=> $totalPackingCharge ,
				);
				
				$returnArray[] = array(
					'title'	=> 'Transport Charge',
					'value'	=> $totalTransportCharge ,
				);
				
				$returnArray[] = array(
					'title'	=> 'Total Charge',
					'value'	=> $totalCharge,
				);
				
				$returnArray[] = array(
					'title'	=> 'price / 1000',
					'value'	=> $pricePer1000,
				);
				
				$returnArray[] = array(
					'title'	=> 'pouch per meter',
					'value'	=> $pricePerMtrFormula1,
				);
				
				$returnArray[] = array(
					'title'	=> 'price per kg formula',
					'value'	=> $pricePerKgFormula1,
				);
				
				$returnArray[] = array(
					'title'	=> 'Price per Meter',
					'value'	=> $pricePerMtrFormula1,
				);
				
				$returnArray[] = array(
					'title'	=> 'Total quanityt in kgs',
					'value'	=> $totalQuantityInKgs,
				);
				
				$returnArray[] = array(
					'title'	=> 'Ink Pirce',
					'value'	=> $inkPrice,
				);
				
				$returnArray[] = array(
					'title'	=> 'Ink solvent Price',
					'value'	=> $inkSolventPrice,
				);
				
				$returnArray[] = array(
					'title'	=> 'Adhesive Price ',
					'value'	=> $adhesivePrice,
				);
				
				$returnArray[] = array(
					'title'	=> 'Adhesive Solvent Price ',
					'value'	=> $adhesiveSolventPrice,
				);
				
				$returnArray[] = array(
					'title'	=> 'Price in Materes / Unit',
					'value'	=> $priceInMtrPerUnit,
				);
				$returnArray[] = array(
					'title'	=> 'Price in kgs / Unit',
					'value'	=> $priceInKgsPerUnit,
				);
				
				$returnArray[] = array(
					'title'	=> 'Price in Piece / Unit',
					'value'	=> $priceInPiecePerUnit,
				);
				
				$returnArray[] = array(
					'title'	=> 'Courier price',
					'value'	=> $courierCharge,
				);
				
				$returnArray[] = array(
					'title'	=> 'Total Price In materes',
					'value'	=> $totalPriceMtr1,
				);
				
				$returnArray[] = array(
					'title'	=> 'Total Price in Kgs',
					'value'	=> $totalPriceKgs1,
				);
				
				$returnArray[] = array(
					'title'	=> 'Total price in Piecec',
					'value'	=> $totalPricePiece1,
				);
				
				$returnArray[] = array(
					'title'	=> 'Total Kg',
					'value'	=> $totalKgs,
				);
				printr($returnArray);die;*/
				
				$inkDefaultPrice = $this->getInkPrice1();
				$inkSolventDefaultPrice = $this->getInkSolventPrice('',0);
				$printingEffectDefaultPrice = $this->getPrintingEffectPrice($data['printing_effect']);
				$adhesiveDefaultPrice = $this->getAdhesivePrice('','',0);;
				$adhesiveSolventDefaultPrice = $this->getAdhesiveSolventPrice('','',0);;
				$cppAdhesiveDefaultPrice = $this->getCppAdhesivePrice('','',0);;
				
				//$cylinderDefaultPrice = '';
			
				//quotation number
				//quotation number
				$userCountry = $this->getUserCountry($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
				if($userCountry){
					$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';
					$newQuotaionNumber = $this->generateQuotationNumber();
					$quotation_number = $countryCode.$newQuotaionNumber;
				}else{
					$newQuotaionNumber = $this->generateQuotationNumber();
					$quotation_number = 'IN'.$newQuotaionNumber;
				}
				
				$printingEffectName = getName('printing_effect','printing_effect_id',$data['printing_effect'],'effect_name');
				$productName = getName('product','product_id',$data['product'],'product_name');
				
				if($userCurrency['currency_code'] && $userCurrency['price']){
					$currency = $userCurrency['currency_code'];
					$currencyPrice = $userCurrency['price'];
				}else{
					$currency = 'INR';
					$currencyPrice = '1';
				}
				
				$sql = "INSERT INTO ".DB_PREFIX."product_quotation SET quotation_type = '1', product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect_id = '".(int)$data['printing_effect']."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', layer = '".(int)$totalLayer."', ink_price = '".(float)$onlyInkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."', use_device = '".getDevice()."', status = '0', quantity_type = '".$quantity_type."', gress_percentage = '".$userGress."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', currency = '".$currency."', currency_price = '".$currencyPrice."', customer_name = '". $data['customer']."', customer_email = '".$customer_email."', customer_gress_percentage = '".$customer_gress."', shipment_country_id = '".$data['country_id']."', added_by_user_id = '".(int)$_SESSION['ADMIN_LOGIN_SWISS']."', added_by_user_type_id = '".(int)$_SESSION['LOGIN_USER_TYPE']."', quotation_number = '".$quotation_number."'";
				$this->query($sql);
				$productQuatiationId = $this->getLastId();
				
				if(isset($productQuatiationId) && (int)$productQuatiationId > 0 ){
					
					//INSERT QUOTATION QUANTITY TABLE 
					if(isset($quantityWiseData) && !empty($quantityWiseData)){
						
						foreach($quantityWiseData as $quantity=>$quantityValue){
							//echo $quantity."===".$quantityValue."===<br>";
							$this->query("INSERT INTO ".DB_PREFIX."product_quotation_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit_base_price = '".$quantityValue['profitBase']."', profit = '".$quantityValue['profit']."', total_kgs = '".$quantityValue['totalKgs']."', total_mtr = '".$quantityValue['totalMtr']."', total_piece = '".$quantityValue['totalPiece']."', total_quantity_in_kgs = '".$quantityValue['totalQuantityInKgs']."', packing_base_price = '".$quantityValue['packingBase']."', packing_charge = '".$quantityValue['packingCharge']."', date_added = NOW()");
							$productQuatiationQuantityId = $this->getLastId();
							
							$pricesql = "INSERT INTO ".DB_PREFIX."product_quotation_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', date_added = NOW(), ";
							
							 if(isset($quantityValue['BySea']) && !empty($quantityValue['BySea'])){
								$customerGressPrice = 0; 
								if($customer_gress > 0){
									$customerGressPrice = $this->numberFormate((($zipData['BySea']['totalPriceBySea'] * $customer_gress) / 100),"3");
								}
								$this->query(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$quantityValue['BySea']['transportCharge']."', transport_base_price = '".$quantityValue['BySea']['transportBasePrice']."', total_price = '".$quantityValue['BySea']['totalPrice']."', gress_price = '".$quantityValue['BySea']['addingPrice']."', total_charge = '".$quantityValue['BySea']['totalCharge']."', customer_gress_price = '".$customerGressPrice."' ");
							 }
							 
							 if(isset($quantityValue['ByAir']) && !empty($quantityValue['ByAir'])){
								$customerGressPrice = 0; 
								if($customer_gress > 0){
									$customerGressPrice = $this->numberFormate((($zipData['BySea']['totalPriceBySea'] * $customer_gress) / 100),"3");
								}
								 $this->query(" $pricesql transport_type = 'air', courier_charge = '".$quantityValue['ByAir']['courierCharge']."', transport_price = '0', total_price = '".$quantityValue['ByAir']['totalPrice']."', gress_price = '".$quantityValue['ByAir']['addingPrice']."', total_charge = '".$quantityValue['ByAir']['totalCharge']."', customer_gress_price = '".$customerGressPrice."' ");
							}
							
						}
					}
					
					//BASE PRICE
					$inkDefaultPrice = $this->getInkPrice1();
					$inkSolventDefaultPrice = $this->getInkSolventPrice('',0);
					$printingEffectDefaultPrice = $this->getPrintingEffectPrice($data['printing_effect']);
					$adhesiveDefaultPrice = $this->getAdhesivePrice('','',0);
					$adhesiveSolventDefaultPrice = $this->getAdhesiveSolventPrice('','',0);
					$cppAdhesiveDefaultPrice = $this->getCppAdhesivePrice('','',0);
					$cylinderVendorDefaultPrice = $this->getCylinderVendorPrice();
					
					//inert base price at a time product quotaion add than taht time real price. use for history
					$this->query("INSERT INTO ".DB_PREFIX."product_quotation_base_price SET product_quotation_id = '".(int)$productQuatiationId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', 	printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."',  cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
					
					//INAERT DATA FOR LAYER WISE
					if(isset($setQueryData) && !empty($setQueryData)){
						foreach($setQueryData as $key=>$setquery){
							$setSql = "INSERT INTO ".DB_PREFIX."product_quotation_layer SET product_quotation_id = '".(int)$productQuatiationId."', ".$setquery;
							$this->query($setSql);
						}
					}
					return $productQuatiationId;
					
				}else{
					return false;
				}
			}
		}
	}
	
	
	public function sumOfNumericArray($array){
		$total = 0;
		if(is_array($array) && !empty($array)){
			foreach($array as $key=>$val){
				$total += $val; 
			}
		}
		//echo $this->numberFormate($total,"3");die;
		return $this->numberFormate($total,"5");
	}
	
	
	
	public function newPackingCharges($height,$width,$gusset,$product_id){
		//echo $height."===".$width."===".$gusset;die;
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		
		$packing_charge = 3.20;
		$total = 0;
		if(!empty($productGusset)){
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$total = ($height * $width);		
			}
			
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$total = ($height * $width);
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$total = ((($gusset*2) + $height)* $width); 
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{
				$total = ((($gusset*1)+$height)*$width);
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				$total = ((($gusset*3) + $height) * $width);
			}
			$data = $this->query("SELECT price FROM ". DB_PREFIX ."product_packing WHERE to_total >= '".$total."' AND from_total <= '".$total."'");
			if($data->row['price'] > 0){
				$packing_charge = $data->row['price'];	
			}
		}
		return $this->numberFormate(($packing_charge),"5");
		
	}
	
	
	
	public function formulaHeightWidthGusset($height,$width,$gusset,$product_id){
		
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		
		$gussetFormula1 = 0;
		$heightGuseetFormula1 = 0;
		$actualHeight = 0;
		$widthFormula = 0;
		$calWidth = $width;
		$calHeight = $height;
		$intoHeight = 0;
		$intoWidth = 0;
		if(!empty($productGusset)){
			
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				
				
					$heightGuseetFormula1 = (($height* 2) + 25);
				$gussetFormula1 = 0;
				$actualHeight = ( $heightGuseetFormula1 + $gussetFormula1);
				$calWidth = $this->numberFormate(($width /1000),"3");
				$intoWidth = 1;
				
			}
			
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$widthFormula = (($width * 2) + 50);//chnage as per excel sheet 17-10-2014 + 25 to +50
				$gussetFormula1 = 0;
				$actualHeight = ( $widthFormula + $gussetFormula1);
				$calHeight = $this->numberFormate(($height /1000),"3");
				$intoHeight = 1;
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				if($product_id==9)
				{
				$widthGuseetFormula1 = $width + 10;
				$gussetFormula1 = ( $gusset + $gusset + 10 );
				//Height Gusset Formula :  heightFormula1 + gussetFormula1 + 10
				$calWidth1 = (( $widthGuseetFormula1 + $gussetFormula1) *2 )+35;				
				$calWidth = $this->numberFormate(($calWidth /1000),"3");
				$intoWidth = 1;
				}
				else
				{
				$widthFormula = ( $width + $gusset + $gusset );
				$widthFormula1 = ( ( $widthFormula * 2) + 50 );//chnage as per excel sheet 17-10-2014 + 25 to +50
				$gussetFormula1 = 0;
				$actualHeight = ( $widthFormula1 + $gussetFormula1);
				$calHeight = $this->numberFormate(($height /1000),"3");
				$intoHeight = 1;
				}
				//echo $widthGuseetFormula1;die;
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{
				$heightGuseetFormula1 = (($height + 10) * 2);
				$gussetFormula1 = ( $gusset + $gusset + 10 );
				//Height Gusset Formula :  heightFormula1 + gussetFormula1 + 10
				$actualHeight = ( $heightGuseetFormula1 + $gussetFormula1 + 10 );
				$calWidth = $this->numberFormate(($width /1000),"3");
				$intoWidth = 1;
			}elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				
				//Flat Bottom Stand Up Pouches
				//$widthFormula = ($width + $gusset + $gusset + 10);
				$widthFormula = ($width + $gusset + $gusset + 15);
				//$widthFormula1 = ($widthFormula * 2);
				$widthFormula1 = ($widthFormula * 1);
				$calWidth = $this->numberFormate(($widthFormula1 /1000),"3");
				
				$heightFormula = (($height + $gusset) * 2);
				$actualHeight = ($heightFormula + 20 + 10);
				$calHeight = $this->numberFormate(($actualHeight / 1000),"3");
				
				$intoWidth = 1;
			}
		}
		
		//Formula : actual height devided by 1000
		$actualHeight1 = $this->numberFormate(($actualHeight / 1000),"3");
		//echo $actualHeight1;die;
		$return = array();
		$return = array(
			'formula' => $actualHeight1,
			'width'	=> $calWidth,
			'height'	=> $calHeight,
			'intoHeight' => $intoHeight,
			'intoWidth'  => $intoWidth,
		);
		return  $return;
		//formula2 : after get $heightGuseetFormula1 result multiply by 2
		/*$heightGuseetFormula2 = ( $heightGuseetFormula1 * 2 );
		
		//formula3 : add static 10 into $heightGuseetFormula2
		$heightGuseetFormula3 = ( $heightGuseetFormula2 + 10 );
		
		//Formula : return final result
		return $heightGuseetFormula3;*/
	}
	
	public function getProduct($product_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '".$product_id."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return 0;
		}
	}
	
	public function getCylinderVendorPrice($countryId = ''){
		$price = 28;
		if($countryId){
			$countryInfo = $this->getCountry($countryId);
			if($countryInfo && strtolower($countryInfo['country_name']) == 'india'){
				$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_cylinder_vendor WHERE type = '1' AND status = '1' ORDER BY product_cylinder_vendor_id DESC LIMIT 0,1");
			}else{
				$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_cylinder_vendor WHERE type = '0' AND status = '1' ORDER BY product_cylinder_vendor_id DESC LIMIT 0,1");
			}
		}else{
			$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_cylinder_vendor WHERE type = '0' AND status = '1' ORDER BY product_cylinder_vendor_id DESC LIMIT 0,1");
		}
		
		if($data->num_rows){
			$price = $this->numberFormate( $data->row['price'],"5");
		}
		return $price;
	}
	
	public function getCalculateCylinderPrice($height,$widht,$gusset,$countryId,$product_id){
		
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		if(!empty($productGusset) && in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
		{
			$widht = $widht + $gusset + 7;
			
		}
		
		//calculate height + add 100 default price
		//echo height."====".$widht;die;
		//$countryInfo = $this->getCountry($countryId);
		//if($countryInfo && strtolower($countryInfo['country_name']) == 'india'){
			$cylinderVendorPrice = $this->getCylinderVendorPrice($countryId);
		/*}else{
			$cylinderVendorPrice = $this->getCylinderVendorPrice(0);
		}*/
		
		$calculatePrice = 0;
		if($widht >= 380){
			$actualHeight = (($height + 10) * 2);
			if($actualHeight > 999 ){
				$actualHeight1 = ($actualHeight + 200 );
			}else if($actualHeight <= 999 ){
				$actualHeight1 = ($actualHeight + 100 );
			}else{
				$actualHeight1 = ($actualHeight + 100 );
			}
			
			$price = ( $actualHeight1 * $widht * $cylinderVendorPrice);
			$calculatePrice = ceil($this->numberFormate(($price / 1000),"3"));
		}else{
			$actualHeight = (($height + 10) * 2);
			$actualHeight1 = ($actualHeight + 100 );
			
			$widhtFormula = $this->recursiveWidth(2,$widht);
			//echo $widhtFormula;
			$price = ( $actualHeight1 * $widhtFormula * $cylinderVendorPrice);
			$calculatePrice = ceil($this->numberFormate(($price / 1000),"3"));
		}
		return $calculatePrice;
	}
	
	public function recursiveWidth($number,$width){
		$newWidth = 0;
		$width1 = ($width * $number);
	//	echo $width1;
		if($width1 >= 380){
			$newWidth = $width1;
		}else{
			$num = ($number+1);
			$newWidth = $this->recursiveWidth($num,$width);
		}
		return $newWidth;
	}
	
	public function getHWGMPrice($height,$width,$gsm,$thickness){
		//Formula1 : multiply all get value
		$priceFormula = ($height * $width * $gsm * $thickness);
		return $priceFormula;
	}
	
	public function getLayerWiseGsmThickness($actualHeight,$wh,$thickness,$gsm){
		//Formula1 : multiply all get value
		//echo $actualHeight."==".$wh."==".$thickness."==".$gsm;die;
		$priceFormula1 = $this->numberFormate(($actualHeight * $wh * $thickness * $gsm ),"5");
		return $priceFormula1;
	}
	public function getLayerPrice($layer_wise_price,$thickness_price){
		//echo $actualHeight."===".$width."==".$thickness."==".$gsm."==".$materialId;die;
		//Formula1 : multiply all get value
		//$priceFormula1 = $this->numberFormate(($actualHeight * $width * $thickness * $gsm ),"3");
		//echo $priceFormula1;die;
		
		//echo $materialId;die;
		//$thicknessPrice = $this->getMaterialThickmessPrice($materialId,$thickness);
		//echo $thicknessPrice;die;
		//FOrmula2 : multiply Formula1 to thikness price
		//echo $layer_wise_price."===".$thickness_price;die;
		$priceFormula2 = $this->numberFormate(($layer_wise_price * $thickness_price),"3");
		//echo $priceFormula2;die;
		return $priceFormula2;
	}
	
	public function getCalculateWeightWithoutZipper($basePrice,$quantity){
		//echo $basePrice;die;
		$addingPrice = ($basePrice + 2.5);
		//echo $addingPrice;die;
		//diveed by 1000
		$addingPrice1 = ($addingPrice / 1000);
		$addingPrice2 = $this->numberFormate(($addingPrice1 * $quantity),"3");
		return $addingPrice2;
	}
	
	public function getCalculateWeightWithZipper($basePrice,$quantity){
		$addingPrice = ($basePrice + 3.75);
		//diveed by 1000
		$addingPrice1 = ($addingPrice / 1000);
		return $this->numberFormate(($addingPrice1 * $quantity),"3");
	}
	
	public function getCountryCourier($country_id){
		$cdata = $this->query("SELECT default_courier_id FROM " . DB_PREFIX . "country WHERE country_id = '".$country_id."'");		
		if($cdata->row['default_courier_id']){
			$courier_id = $cdata->row['default_courier_id'];
		}else{
			$courier_id = 1;
		}
		
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "courier WHERE courier_id = '".$courier_id."'");		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	
	public function getCountryCombo($selected=""){
		$sql = "SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' AND is_delete = '0' AND default_courier_id > 0";
		$data = $this->query($sql);
		$html = '';
		if($data->num_rows){
			//return $data->rows;
			$html = '';
			$html .= '<select name="country_id" id="country_id" class="form-control validate[required]" style="width:70%" >';
					$html .= '<option value="">Select Country</option>';
			foreach($data->rows as $country){
				if($country['country_id'] == $selected ){
					$html .= '<option value="'.$country['country_id'].'" selected="selected">'.$country['country_name'].'</option>';
				}else{
					$html .= '<option value="'.$country['country_id'].'" >'.$country['country_name'].'</option>';
				}
			}
			$html .= '</select>';
		}
		return $html;
	}
	
	public function getQuotationPackingAndTransportDetails($quotation_id){
		
		$sql = "SELECT pqbp.packing_price,pqbp.transport_width_base_price,pqbp.transport_height_base_price FROM " . DB_PREFIX ."product_quotation pq INNER JOIN " . DB_PREFIX ."product_quotation_base_price pqbp ON (pq.product_quotation_id=pqbp.product_quotation_id) WHERE pq.product_quotation_id = '".(int)$quotation_id."'";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	
	public function getQuotationOtherDetails($quotation_id){
		
		$data = $this->query("SELECT quantity, product_quotation_quantity_id, quantity_type, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, total_weight_with_zipper FROM " . DB_PREFIX ."product_quotation_quantity WHERE product_quotation_id='".$quotation_id."'");
		
		if($data->num_rows){
			//printr($data->rows);die;
			
			$return = array();
			foreach($data->rows as $row){
				
				$data1 = $this->query("SELECT * FROM " . DB_PREFIX ."product_quotation_price WHERE product_quotation_id='".$quotation_id."' AND product_quotation_quantity_id='".$row['product_quotation_quantity_id']."'");
			//	echo "SELECT * FROM " . DB_PREFIX ."product_quotation_price WHERE product_quotation_id='".$quotation_id."' AND product_quotation_quantity_id='".$row['product_quotation_quantity_id']."'";die;
			if(isset($data1->rows[0]['excies']) && $data1->rows[0]['excies']>0)
			{
				$quantity_option[$row['quantity']] =  array(
					'Total Weight With Zipper' => $row['total_weight_with_zipper'],
					'Total Weight Without Zipper' => $row['total_weight_without_zipper'],
					'Wastage' => $row['wastage_base_price'],
					'Profit' => $row['profit'],
					'Excies' => $data1->rows[0]['excies'].' %',
					str_replace('_',' ',strtoupper($data1->rows[0]['tax_type'])) => $data1->rows[0]['tax_percentage'].' %'					
				);
			}
			else
			{
				$quantity_option[$row['quantity']] =  array(
				'Total Weight With Zipper' => $row['total_weight_with_zipper'],
				'Total Weight Without Zipper' => $row['total_weight_without_zipper'],
				'Wastage' => $row['wastage_base_price'],
				'Profit' => $row['profit'],
				);
			}
				//printr($data1->rows[0]['tax_type']);
				//$zipper_option = array();
				foreach($data1->rows as $option){
					
					$tax[$row['quantity']] =  array(
					'Excies' => $option['excies'],
							
				);
					/*if($option['spout_txt']){
						$zipper_option[$option['spout_txt']] = array(
							'spout_price' => $option['spout_base_price'],
							'courier_charge' => $option['courier_charge']
						);
					}elseif($option['accessorie_txt']){
						$zipper_option[$option['accessorie_txt']] = array(
							'accessorie_price' => $option['accessorie_base_price'],
							'courier_charge' => $option['courier_charge']
						);
					}else{*/
						$zipper_option[$option['zipper_txt'].' '.$option['valve_txt'].'<br> '.$option['spout_txt'].' '.$option['accessorie_txt']] = array(
							///'txt' => $option['zipper_txt'].' '.$option['valve_txt'],
							'zipper_price' => $option['zipper_price'],
							'valve_price' => $option['valve_price'],
							'courier_charge' => $option['courier_charge']  ,
							'spout_price' => $option['spout_base_price'],
							'accessorie_price' => $option['accessorie_base_price'],
							
						);
				//	}
					
					
					
					if($option['transport_type']=='sea'){
						
						$return['sea'][$row['quantity']] =  array(
							'quantity_option'	=> $quantity_option[$row['quantity']],
							'zipper_option' => $zipper_option,
							'transport_price' => $option['transport_price'] 
						);	
					}
					
					if($option['transport_type']=='air'){
						$return['air'][$row['quantity']] =  array(
							'quantity_option'	=> $quantity_option[$row['quantity']],
							'zipper_option' => $zipper_option,
							'courier_charge' => $option['courier_charge']
						);
					}
					
					if($option['transport_type']=='pickup'){
						$return['pickup'][$row['quantity']] =  array(
							'quantity_option'	=> $quantity_option[$row['quantity']],
							'zipper_option' => $zipper_option,
							'courier_charge' => $option['courier_charge']
						);
					}
					
					/*if($option['transport_type']=='pickup'){
						
					}*/
					
				}
			}
			
			return $return;	
		}else{
			return false;
		}	
	}
	
	/*public function getQuotationCharges($quotation_quantity_id){
		
		$sql = "SELECT transport_type,zipper_txt,valve_txt,courier_charge,zipper_price,valve_price FROM " . DB_PREFIX ."product_quotation_price pqp WHERE pqp.product_quotation_quantity_id='".$quotation_quantity_id."'";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
	}*/
	public function getCountryCourierCharge($country_id,$courier_id,$weight){
		$zdata = $this->query("SELECT courier_zone_id FROM " . DB_PREFIX . "courier_zone_country WHERE country_id = '".$country_id."' AND courier_id = '".$courier_id."'");	
		//echo "SELECT courier_zone_id FROM " . DB_PREFIX . "courier_zone_country WHERE country_id = '".$country_id."' AND courier_id = '".$courier_id."'";	
		if(isset($zdata->row['courier_zone_id']) && $zdata->row['courier_zone_id']){
			$courier_zone_id = $zdata->row['courier_zone_id'];
		}else{
			$courier_zone_id = 1;
		}
		$data = $this->query("SELECT price FROM " . DB_PREFIX . "courier_zone_price WHERE courier_id = '".$courier_id."' AND 	courier_zone_id = '".$courier_zone_id."' AND from_kg <= '".$weight."' AND 	to_kg >= '".$weight."' ");		
		if(isset($data->row['price']) && $data->row['price']){
			$price = $data->row['price'];
		}else{
			$data = $this->query("SELECT to_kg, price FROM " . DB_PREFIX . "courier_zone_price WHERE courier_id = '".$courier_id."' AND courier_zone_id = '".$courier_zone_id."' ORDER BY to_kg DESC LIMIT 0,1 ");
			$baseKg = $data->row['to_kg'];
			$basePrice = $data->row['price'];
			$perKgPrice = ($basePrice / $baseKg);
			$price = ($weight * $perKgPrice);
		}
		//echo $price;die;
		return $price;
	}
	
	public function getWastage($quantity){
		$sql = "SELECT wastage FROM " . DB_PREFIX . "product_wastage WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			$wastage = $data->row['wastage'];
			//return $this->numberFormate($wastage,"5");
			return $wastage;
		}else{
			return false;
		}
	}
	
	public function getCalculateWastage($price,$quantity,$type){
		$sql = "SELECT wastage FROM " . DB_PREFIX . "product_wastage WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			$wastage = $data->row['wastage'];
			if($type == 1){
				return $this->numberFormate((($price * $wastage) / 100),"5");
			}else{
				return $this->numberFormate($wastage,"5");
			}
		}else{
			return false;
		}
		//return $this->numberFormate((($price * 0) / 100),"3");
	}
	
	public function getRollWastage($totalKgs){
		
		$sql = "SELECT wastage_kg FROM " . DB_PREFIX . "product_roll_wastage WHERE from_kg <= '".$totalKgs."' AND 	to_kg >= '".$totalKgs."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["wastage_kg"];
		}else{
			return false;
		}
	}
	
	//Transport
	public function getDefaultTransportWidthPrice($width,$gusset){
		$newWidth = ($width + $gusset);
		$wPrice = 0;
		$wdata = $this->query("SELECT price FROM " . DB_PREFIX . "product_transport_sea_width WHERE from_width <= '".$newWidth."' AND to_width >= '".$newWidth."' ");
		//echo $wsql;die;	
		if($wdata->num_rows){
			$wPrice = $wdata->row['price'];
		}
		return $this->numberFormate($wPrice,"3");
	}
	
	public function getDefaulttransportHeightPrice($height){
		$hPrice = 0;
		$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_transport_sea_height WHERE from_height <= '".$height."' AND to_height >= '".$height."' ");
		if($data->num_rows){
			$hPrice = $data->row['price'];
		}		
		return $this->numberFormate($hPrice,"3");
	}
	
	public function getDefaultPackingWidthPrice($width){
		$wPrice = 0;
		$wsql = "SELECT price FROM " . DB_PREFIX . "product_packing_width WHERE from_width <= '".$width."' AND to_width >= '".$width."' ";
		//echo $wsql;die;		
		$wdata = $this->query($wsql);
		if($wdata->num_rows){
			$wPrice = $wdata->row['price'];
		}
		return $this->numberFormate($wPrice,"3");
	}
	
	public function getDefaultPackingHeightPrice($height){
		$hPrice = 0;
		$sql = "SELECT price FROM " . DB_PREFIX . "product_packing_height WHERE from_height <= '".$height."' AND to_height >= '".$height."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			$hPrice = $data->row['price'];
		}
		
		return $this->numberFormate($hPrice,"3");
	}
	
	public function getCalculateTransport($height,$width,$gusset){
		//echo $height."===".$width."==".$gusset;die;
		$hPrice = 0;
		$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_transport_sea_height WHERE from_height <= '".$height."' AND to_height >= '".$height."' ");		
		if($data->num_rows){
			$hPrice = $data->row['price'];
		}
		$wPrice = 0;
		$newWidth = ($width + $gusset);
		$wdata = $this->query("SELECT price FROM " . DB_PREFIX . "product_transport_sea_width WHERE from_width <= '".$newWidth."' AND to_width >= '".$newWidth."' ");		
		if($wdata->num_rows){
			$wPrice = $wdata->row['price'];
		}
	//	echo $hPrice .'+'. $wPrice
;		return $this->numberFormate(($hPrice + $wPrice),"5");
	}
	public function getCalculatePacking($height,$width){
		$hPrice = 0;
		$sql = "SELECT price FROM " . DB_PREFIX . "product_packing_height WHERE from_height <= '".$height."' AND to_height >= '".$height."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			$hPrice = $data->row['price'];
		}
		
		$wPrice = 0;
		$wsql = "SELECT price FROM " . DB_PREFIX . "product_packing_width WHERE from_width <= '".$width."' AND to_width >= '".$width."' ";		
		$wdata = $this->query($wsql);
		if($wdata->num_rows){
			$wPrice = $wdata->row['price'];
		}
		
		return $this->numberFormate(($hPrice + $wPrice),"5");
	}
	
	/*public function getCalculateTransport($quantity,$selTransport){
		$sql = "SELECT $selTransport FROM " . DB_PREFIX . "product_transport WHERE from_quantity <= '".$quantity."' AND to_quantity >= '".$quantity."' ";		
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["$selTransport"];
		}else{
			return false;
		}
	}*/
	
	/*public function getZipperPrice(){
		$sql = "SELECT price FROM " . DB_PREFIX . "product_zipper WHERE status = '1' ORDER BY product_zipper_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['price'];
		}else{
			return false;
		}
	}*/
	
	/*public function checkZipper($option_id){
		$sql = "SELECT zipper FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".(int)$option_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['zipper'];
		}else{
			return false;
		}
	}*/
	
	function getZipperInfo($zipper_id){
		$data = $this->query("SELECT zipper_name, price, product_zipper_id FROM " . DB_PREFIX . "product_zipper WHERE product_zipper_id = '".(int)$zipper_id."' ");
		if($data->num_rows){
			$return  = array();
			$return['product_zipper_id'] = $data->row['product_zipper_id'];
			$return['zipper_name'] = $data->row['zipper_name'];
			$return['price'] = $data->row['price'];
			return $return;
		}else{
			$return  = array();
			$return['product_zipper_id'] = 0;
			$return['zipper_name'] = 'No zip';
			$return['price'] = 0.00;
			return $return;
		}
	}
	
	public function getCalculateZipperPrice($product_id,$height,$weight,$zipperBasePrice){
		$data = $this->query("SELECT calculate_zipper_with FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$product_id."' ");
		//$zipperBasePrice = $this->getZipperPrice();
		if($data->row['calculate_zipper_with'] && $data->row['calculate_zipper_with'] != ''){
			if($data->row['calculate_zipper_with'] == 'h'){
				$newhh = ($height / 1000);
				$zipperPrice = ($newhh * $zipperBasePrice * 10 );
			}else{
				$newww = ($weight / 1000);
				$zipperPrice = ($newww * $zipperBasePrice * 10 );
			}
		}else{
			$newww = ($weight / 1000);
			$zipperPrice = ($newww * $zipperBasePrice * 10 );
		}
		return $zipperPrice;
	}
	
	/*public function getValvePrice($user_type_id,$user_id){
		if($user_type_id == 4){
			$query = $this->query("SELECT valve_price FROM " . DB_PREFIX . "international_branch WHERE international_branch_id = '".(int)$user_id."' ");
		}
		if($query->row['valve_price'] > 0){
			return $this->numberFormate($data->row['valve_price'],"5");
		}else{
			return 0;
		}
	}*/
	
	public function getCalculateZipper($option_id,$width,$type){
		$sql = "SELECT * FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".(int)$option_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			$optionPrice = $data->row['price'];
			if($type == 1){
				$zipperPrice = 0;
				if($data->row['zipper'] == 1){
					$zipperBasePrice = $this->getZipperPrice();
					$newww = ($width / 1000);
					$zipperPrice = ($newww * $zipperBasePrice * 10 );// * $quantity );
				}
				//echo $zipperPrice;die;
				//return $this->numberFormate(($optionPrice + $pricePerBag + $packingPerPouch + $transportPerPouch),"3");
				return $this->numberFormate(($optionPrice + $zipperPrice ),"5");
			}else{
				return $this->numberFormate($optionPrice,"5");
			}
		}else{
			return false;
		}
	}
	
	public function getPrintingEffectPrice($effect_id){
		$sql = "SELECT price FROM " . DB_PREFIX . "printing_effect WHERE printing_effect_id = '".(int)$effect_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $this->numberFormate($data->row['price'],"3");
		}else{
			return 0;
		}
	}
	
	//public function getcalculateProfit($quantity){
	public function getcalculateProfit($quantity,$product_id,$height,$width,$gusset){
		//$sql = "SELECT profit FROM " . DB_PREFIX . "product_profit WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' ";		
		//$sql = "SELECT profit FROM " . DB_PREFIX . "product_profit WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' AND product_id= '".$product_id."'";		
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		$size = 0;
		if(!empty($productGusset)){
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);		
			}
			
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ((($gusset*2) + $height)* $width); 
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*1)+$height)*$width);
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*3) + $height) * $width);
			}
		}
		
		$qunatityRow = $this->query("SELECT product_quantity_id FROM " . DB_PREFIX . "product_quantity WHERE quantity = '".$quantity."'");
		$quantity_id = $qunatityRow->row['product_quantity_id'];
		
		$data = $this->query("SELECT profit FROM " . DB_PREFIX . "product_profit WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND size_from <= '".$size."' AND 	size_to >= '".$size."'");
		if($data->num_rows){
			return $this->numberFormate($data->row['profit'],"3");
		}else{
			return false;
		}
	}
	
	
	public function getcalculatePlusMinusQuantity($quantity,$product_id,$height,$width,$gusset,$type){
		//$sql = "SELECT profit FROM " . DB_PREFIX . "product_profit WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' ";		
		//$sql = "SELECT profit FROM " . DB_PREFIX . "product_profit WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' AND product_id= '".$product_id."'";		
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		$size = 0;
		if(!empty($productGusset)){
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);		
			}
			
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ((($gusset*2) + $height)* $width); 
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*1)+$height)*$width);
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*3) + $height) * $width);
			}
		}
		
		$qunatityRow = $this->query("SELECT product_quantity_id FROM " . DB_PREFIX . "product_quantity WHERE quantity = '".$quantity."'");
		$quantity_id = $qunatityRow->row['product_quantity_id'];
		
		$data = $this->query("SELECT plus_minus_quantity	FROM " . DB_PREFIX . "product_profit WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND size_from <= '".$size."' AND 	size_to >= '".$size."'");
		if($data->num_rows){
			return $data->row['plus_minus_quantity'];
		}else{
			return false;
		}
	}
	
	
	public function getRollProfit($weight){
		/*if($quantity_type == 'kg'){
			$sql = 'profit_kg';
		}elseif($quantity_type == 'pieces'){
			$sel = 'profit_piece';
		}else{
			$sel = 'profit_meter';
		}
		$sql = "SELECT $sel FROM " . DB_PREFIX . "product_roll_profit WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' ";*/		
		$sql = "SELECT profit_kg FROM " . DB_PREFIX . "product_roll_profit WHERE from_kg <= '".$weight."' AND 	to_kg >= '".$weight."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["profit_kg"];
		}else{
			return $this->numberFormate(($weight / 15000),"3");
		}
	}
	
	public function getRollPackingPrice($kg){
		
		$sql = "SELECT price_kgs FROM " . DB_PREFIX . "product_roll_packing WHERE from_kgs <= '".$kg."' AND 	to_kgs >= '".$kg."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["price_kgs"];
		}else{
			return false;
		}
	}
	
	public function getRollTransportPrice($kg){
		
		$sql = "SELECT price_kgs FROM " . DB_PREFIX . "product_roll_transport WHERE from_kgs <= '".$kg."' AND 	to_kgs >= '".$kg."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["price_kgs"];
		}else{
			return false;
		}
	}
	
	public function getMaterialGsm($material_id){
		$sql = "SELECT gsm FROM " . DB_PREFIX . "product_material WHERE product_material_id = '".(int)$material_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['gsm'];
		}else{
			return false;
		}
	}
	
	public function checkMaterial($material_id){
		$sql = "SELECT material_name FROM " . DB_PREFIX . "product_material WHERE product_material_id = '".(int)$material_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if(strtolower($data->row['material_name']) == 'cpp'){
				return 1;
			}
		}else{
			return 0;
		}
	}
	
	public function getMaterialThickmessPrice($material_id,$thickness){
		$sql = "SELECT price FROM " . DB_PREFIX . "product_material_thickness_price WHERE product_material_id = '".(int)$material_id."' AND from_thickness <= '".$thickness."' AND to_thickness >= '".$thickness."' ";
		//echo $sql."==<br>";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['price'];
		}else{
			return false;
		}
	}
	
	//ink price
	public function getInkPrice1($makeid){
		$sql = "SELECT price FROM " . DB_PREFIX . "ink_master WHERE status = '1' AND make_id = '".$makeid."' ORDER BY ink_master_id DESC LIMIT 0,1";
		//echo "SELECT price FROM " . DB_PREFIX . "ink_master WHERE status = '1' AND make_id = '".$makeid."' ORDER BY ink_master_id DESC LIMIT 0,1";die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $this->numberFormate( $data->row['price'],"5");
		}else{
			return false;
		}
	}
	public function getInkPrice($basePrice,$type,$makeid){
		$sql = "SELECT price FROM " . DB_PREFIX . "ink_master WHERE status = '1' AND make_id = '".$makeid."' ORDER BY ink_master_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				return $this->numberFormate( ( $basePrice * $data->row['price']),"5");
			}else{
				return $this->numberFormate( $data->row['price'],"5");
			}
		}else{
			return false;
		}
	}
	
	public function getInkSolventPrice($basePrice,$type,$makeid){
		$sql = "SELECT price FROM " . DB_PREFIX . "ink_solvent WHERE status = '1' AND make_id = '".$makeid."' ORDER BY ink_solvent_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				return $this->numberFormate( ( $basePrice * $data->row['price']),"5");
			}else{
				return $this->numberFormate($data->row['price'],"5");
			}
		}else{
			return false;
		}
	}
	
	public function getAdhesivePrice($price,$layerCount,$type,$makeid){
		$sql = "SELECT price FROM " . DB_PREFIX . "adhesive WHERE status = '1' AND make_id = '".$makeid."' ORDER BY adhesive_id DESC LIMIT 0,1";
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),"5");
			}else{
				return $this->numberFormate($data->row['price'],"5");
			}
		}else{
			return false;
		}
	}
	
	public function getCppAdhesivePrice($price,$layerCount,$type){
		$sql = "SELECT price FROM " . DB_PREFIX . "cpp_adhesive WHERE status = '1' ORDER BY cpp_adhesive_id DESC LIMIT 0,1";
		
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),"3");
			}else{
				return $this->numberFormate($data->row['price'],"3");
			}
		}else{
			return false;
		}
	}
	
	public function getAdhesiveSolventPrice($price,$layerCount,$type,$makeid){
		$sql = "SELECT price FROM " . DB_PREFIX . "adhesive_solvent WHERE status = '1' AND make_id = '".$makeid."' ORDER BY adhesive_solvent_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),"5");
			}else{
				return $this->numberFormate($data->row['price'],"5");	
			}
		}else{
			return false;
		}
	}
	
	public function numberFormate($number,$decimalPoint=3){
		//return number_format($number,$decimalPoint);
		return number_format($number,$decimalPoint,".","");
	}
		
	
	public function getProductName($product_id){
		$sql = "SELECT product_name FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$product_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['product_name'];
		}else{
			return false;
		}
	}
	
	public function getUserEmployeeIds($user_type_id,$user_id){
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	
	//Start : listing
	public function getTotalQuotation($user_type_id,$user_id,$filter_array=array(),$con){
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_quotation WHERE 1=1 ".$con."";
		}else{
			
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			
			
			$str = '';
			if($userEmployee){
				$str = ' OR ( added_by_user_id IN ('.$userEmployee.') AND added_by_user_type_id = "2" )';
			}
			//$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_quotation WHERE added_by_user_id = '".(int)$set_user_id."' AND added_by_user_type_id = '".(int)$set_user_type_id."' AND quotation_status = 1   $str ";
			$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_quotation WHERE added_by_user_id = '".(int)$set_user_id."' AND added_by_user_type_id = '".(int)$set_user_type_id."' ".$con." $str ";
			
		}
		//echo  $sql;die;
		if(!empty($filter_array)) {
			if(!empty($filter_array['quotation_no'])){
				$sql .= " AND quotation_number = '".$filter_array['quotation_no']."'";
			}
			
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND customer_name LIKE '%".$filter_array['customer_name']."%'";
			}
			
			if(!empty($filter_array['date'])){
			
				$sql .= " AND date(date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
				
				//echo $sql;die;
			}
			
			if(!empty($filter_array['layer'])){
				$sql .= " AND layer = '".$filter_array['layer']."'";
			}
			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND shipment_country_id = '".$filter_array['country']."'";
			}
			
			if(!empty($filter_array['option'])){
				$sql .= " AND option_id = '".$filter_array['option']."'";
			}					
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getQuotations($user_type_id,$user_id,$data,$filter_array=array()){
	
		if($user_type_id == 1 && $user_id == 1){
			//$sql = "SELECT c.country_name,pq.status,pq.product_quotation_id,pq.quotation_number,pq.customer_name,pq.product_name, pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_status,pq.quotation_type,pq.date_added,pq.layer, pq.use_device FROM " . DB_PREFIX . "product_quotation pq LEFT JOIN " . DB_PREFIX ."country c ON c.country_id = pq.shipment_country_id WHERE  1=1 ";
			//$sql = "SELECT c.country_name,pq.status,pq.product_quotation_id,pq.quotation_number,pq.customer_name,pq.product_name, pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_status,pq.quotation_type,pq.date_added,pq.layer, pq.use_device FROM " . DB_PREFIX . "product_quotation pq LEFT JOIN " . DB_PREFIX ."country c ON c.country_id = pq.shipment_country_id WHERE    1=1 ";
		//echo $sql;
		
		//jaya
		$sql = "SELECT c.country_name,pq.status,pq.product_quotation_id,pq.quotation_number,pq.customer_name,pq.product_name, pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_status,pq.quotation_type,pq.date_added,pq.layer, pq.use_device,pqp.zipper_txt,pqp.valve_txt,
		pqp.spout_txt,pqp.accessorie_txt FROM product_quotation pq,country c,product_quotation_price pqp WHERE c.country_id = pq.shipment_country_id AND
		pq.product_quotation_id = pqp.product_quotation_id AND 1=1 ";
		
	//	echo $sql;
	//	die;
		 //product_quotation_price
		//jaya
		//die;
		}else{
			//echo $sql;die;
			
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			
			$str = '';
			if($userEmployee){
				$str = ' OR ( pq.added_by_user_id IN ('.$userEmployee.') AND pq.added_by_user_type_id = 2 )';
			}
			
			//$sql = "SELECT c.country_name,pq.* FROM " . DB_PREFIX . "product_quotation pq LEFT JOIN " . DB_PREFIX ."country c ON c.country_id = pq.shipment_country_id WHERE pq.quotation_status = '1' AND pq.added_by_user_id = '".(int)$set_user_id."' AND pq.added_by_user_type_id = '".(int)$set_user_type_id."' $str";
			
			/*$sql = "SELECT c.country_name,pq.* FROM " . DB_PREFIX . "product_quotation pq LEFT JOIN " . DB_PREFIX ."country c ON c.country_id = pq.shipment_country_id WHERE  pq.added_by_user_id = '".(int)$set_user_id."' AND pq.added_by_user_type_id = '".(int)$set_user_type_id."' $str";*/
			
			
			//jaya
			$sql = "SELECT c.country_name,pq.*,pqp.valve_txt,pqp.zipper_txt,pqp.spout_txt,pqp.accessorie_txt FROM product_quotation pq LEFT JOIN country c ON c.country_id = pq.shipment_country_id LEFT JOIN product_quotation_price pqp ON pq.product_quotation_id=pqp.product_quotation_id where pq.added_by_user_id = '".(int)$set_user_id."' AND pq.added_by_user_type_id = '".(int)$set_user_type_id."' $str ";
			
			
			//jaya
			
			//die;
			
		}
		
		//echo $sql;die;
		
		if(!empty($filter_array)) {
		//printr($filter_array);
			//die;
			if(!empty($filter_array['quotation_no'])){
				$sql .= " AND pq.quotation_number = '".$filter_array['quotation_no']."'";
			}
			
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND pq.customer_name LIKE '%".$filter_array['customer_name']."%'";
			}
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(pq.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}
			
			if(!empty($filter_array['layer'])){
				$sql .= " AND pq.layer = '".$filter_array['layer']."'";
			}
			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND pq.product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND pq.shipment_country_id = '".$filter_array['country']."'";
			}
			
			if(!empty($filter_array['option'])){
				$sql .= " AND pq.option_id = '".$filter_array['option']."'";
			}
			if(!empty($filter_array['postedby']))
			{
			$spitdata = explode("=",$filter_array['postedby']);
				//printr($spitdata);
				
				$sql .="AND pq.added_by_user_type_id = '".$spitdata[0]."' AND pq.added_by_user_id = '".$spitdata[1]."'";
				
				
			}
			
							
		}
		if(!empty($data['cond'])){
				$sql .= $data['cond'];
			}
			//	printr($data);
		//echo $sql;die;
		//if($user_type_id != 1 && $user_id != 1){
					$sql .= "GROUP BY pq.product_quotation_id";
		//		}
		if (isset($data['sort'])) {
			
				
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_quotation_id";	
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
			
				
		//echo $sql;
		//die;
		$data = $this->query($sql);
		//printr($data);
		//die;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function getBaseCylinderPrice($currency_id){
		
		//$sql = "SELECT price FROM " . DB_PREFIX . "product_cylinder_base_price WHERE currency_id='".$currency_id."' ";
		$sql = "SELECT cb.price FROM " . DB_PREFIX . "currency_setting cs, product_cylinder_base_price as cb , country as c WHERE cs.currency_id='".$currency_id."' AND cb.currency_code = c.currency_code AND c.country_id =cs.country_code";
	//	echo "SELECT price FROM " . DB_PREFIX . "product_cylinder_base_price WHERE currency_id='".$currency_id."' ";
		$data = $this->query($sql);
	//	printr($data);die;
		if($data->num_rows){
			return $data->row['price'];	
		}else{
			return false;
		}
	}
	
	public function updateQuotationStatus($quotation_id,$status_value){
		
		$sql = "UPDATE " . DB_PREFIX . "product_quotation SET status = '".$status_value."', date_modify = NOW() WHERE product_quotation_id = '" .(int)$quotation_id. "'";
		$this->query($sql);
	}
	
	public function upadteQuotation($quotation_id){
		$this->query("UPDATE " . DB_PREFIX . "product_quotation SET 	status = '1', quotation_status = '1', date_modify = NOW() WHERE product_quotation_id = '" .(int)$quotation_id. "'");
		//send emial code
		$this->sendQuotationEmail($quotation_id);
	}
	
	public function update_discount($quantity_id,$discount){
		$data=$this->query("UPDATE " . DB_PREFIX . "product_quotation_quantity SET discount = ".$discount." WHERE product_quotation_quantity_id = '" .(int)$quantity_id. "'");		
		return $data;
	}
	
	public function sendQuotationEmail($quotation_id,$toEmail = '',$setQuotationCurrencyId=''){
		
		//echo $setQuotationCurrencyId;
		$getData = ' product_quotation_id, added_by_user_id, added_by_user_type_id, customer_name, customer_gress_percentage, customer_email, shipment_country_id, quotation_number, quotation_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,quantity_type,layer,added_by_country_id, currency, currency_id, currency_price, date_added, cylinder_price,tool_price, customer_email,status,discount ';
		$data = $this->getQuotationSelectedData($quotation_id,$getData);
	
		$newdata = $this->getexciestaxtype($quotation_id);
		$gressper = $this->getQuotationGresspriceForMail($quotation_id);
		$addedByInfo = $this->getUser($data['added_by_user_id'],$data['added_by_user_type_id']);
		
		if($addedByInfo){
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
				
				$gussetval = ' { '. $data['gusset'].'mm + '.$data['gusset'].'
				mm + '.$data['gusset'].'mm gusset }';
				}
				elseif($data['gusset'] == '0')
				{
				 $gussetval = ' ';	
					
				}
				else
				{
				$gussetval = ' { '. $data['gusset'].'mm + '.
				$data['gusset'].'mm gusset}';
				}
			}
			$setHtml .= '<div><b>Size : </b>'.'W : '.(int)$data['width'].'mm x '.'H : '.(int)$data['height'].'mm '.$gussetval.' </div> ';
			$setHtml .= '<div><b>Material : </b>';		
			$materialData = $this->getQuotationMaterial($data['product_quotation_id']);
				if(isset($materialData) && !empty($materialData)){
					$materialStr = '';
					for($gi=0;$gi<count($materialData);$gi++){
						$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
					}
					$setHtml .= substr($materialStr,0,-3);
				}
			$setHtml .= '</div>';
			$shippingCountry = $this->getCountry($data['shipment_country_id']);
			$setHtml .= '<div><b>Shipment Country : </b>'.$shippingCountry['country_name'].'</div>';
			/*if($data['discount']!=0.000)
			{
				$setHtml .= '<div><b>Discount (%) : </b>'.$data['discount'].'</div>';
			}*/
			$quantityData = $this->getQuotationQuantityForMail($data['product_quotation_id']);
			$quantityCustomerHtml = '';
			$quantityCustomerHtmldis = '';
			$quantityHtml = '';
			$quantityHtmldis = '';
			$pqcquery = '';
			if($data['customer_email'] != '' || $toEmail != ''){
				$quantityCustomerHtml .= $setHtml;			
				$quantityCustomerHtml .= '<style> table, th, td </style>';
				$quantityCustomerHtml .= '<div >';
				$selCurrency = '';
				if($setQuotationCurrencyId){
					$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
					if(!$selCurrency){
						$selCurrency = $this->getSelectedCurrecyForQuotation($setQuotationCurrencyId);
					}
				}else{
					$selCurrency = $this->getSelectedCurrecyForQuotation($setQuotationCurrencyId);
				}
				if($selCurrency){
					$pqcquery = " product_quotation_currency_id = '".$selCurrency['product_quotation_currency_id']."', ";
				}
		
				$i=0;
				foreach($quantityData as $quantity=>$qoption){
					foreach($qoption as $zipval => $zipPrice){
						if($i==0)
						{
						$quantityCustomerHtml .= '<b>Make up of pouch  : </b> Custom Printed '. $data['product_name'] .''.$zipval.'<br>';
						}
						$quantityCustomerHtml .= '<table cellpadding="0" cellspacing="0">';
							foreach($zipPrice as $key=>$value){
								if($selCurrency){
									$newPirce = ((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $selCurrency['currency_rate'] );
									if($i==0)
									{
										$quantityCustomerHtml .= '<tr valign="top"><td width="60"><b>Price : </b></td>';	
										if(isset($value['discount']) && $value['discount']>0.000)
										$quantityCustomerHtmldis .= '<tr valign="top"><td width="60"></td>';	
									}
									else
									{
										$quantityCustomerHtml .= '<tr><td width="60">&nbsp;</td>';
										if(isset($value['discount']) && $value['discount']>0.000)
										$quantityCustomerHtmldis .= '<tr><td width="60">&nbsp;</td>';
									}
									$quantityCustomerHtml .= '<td>'.$selCurrency['currency_code'].' '.$this->numberFormate(( $newPirce / $quantity) ,"3").' per 1 bag ';
									if(isset($value['discount']) && $value['discount']>0.000)
									{
										$p=$this->numberFormate(( $newPirce / $quantity) ,"3");
									$quantityCustomerHtmldis .= '<td><b>Discounted Price ('.$value['discount'].' %): </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($p-($p*$value['discount']/100)),"3").' per 1 bag ';						
									}
									$i++;
										if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$quantityCustomerHtml .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$quantityCustomerHtmldis .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$quantityCustomerHtml .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$quantityCustomerHtmldis .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
								$quantityCustomerHtml .=	'- by '.$key.'.'.$br.'</td></tr>';
								if(isset($value['discount']) && $value['discount']>0.000)
								$quantityCustomerHtmldis .=	'- by '.$key.'<br><br><br><br></td></tr>';
									$quantityCustomerHtml.=$quantityCustomerHtmldis;
									$quantityCustomerHtmldis='';
								}
								else{
									
									$priceval =$this->numberFormate(((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $quantity) ,"3"); 
								$total = $priceval + ($priceval*$newdata['excies']/100);
								$final = $total + ($total *$newdata['tax_percentage']/100);
								$taxvalue = '';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
									$pricevaldis =$this->numberFormate(($priceval-($priceval*$value['discount']/100)),"3");
									$totaldis = $pricevaldis + ($pricevaldis*$newdata['excies']/100);
									$finaldis = $totaldis + ($totaldis *$newdata['tax_percentage']/100);
									$taxvaluedis = '';
								}
								if($data['shipment_country_id'] == '111')
								{
									$taxvalue = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
									if(isset($value['discount']) && $value['discount']>0.000)
									{
										$taxvaluedis = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';					
									}
								}
									
									$quantityCustomerHtml .= '<span style="margin-right: 93px;" ><b>Price : </b></span>'.$data['currency'].' '.$priceval.' per 1 bag '.$taxvalue;
									if(isset($value['discount']) && $value['discount']>0.000)
									$quantityCustomerHtmldis .='<span style="margin-left: 150px;"><b>Discount ('.$value['discount'].' %) : </b>'.$data['currency'].' '.$pricevaldis.' per 1 bag '.$taxvaluedis;
								if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$quantityCustomerHtml .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$quantityCustomerHtmldis .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$quantityCustomerHtml .= '<b>{For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$quantityCustomerHtmldis .= '<b>{For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
						
						$quantityCustomerHtml .= ' - By '.ucwords($key).'.'.$br.'</td></tr>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$quantityCustomerHtmldis .= ' - By '.ucwords($key).'.</span><br><br><br><br></td></tr>';
									$quantityCustomerHtml.=$quantityCustomerHtmldis;
									$quantityCustomerHtmldis='';
								}
							}
							
							$quantityCustomerHtml .= '</table>';
					}
				}
				$quantityCustomerHtml .= '</div>';
				if($selCurrency){
					
					$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_id']);
					
					$cylinder_currency_price = ($data['cylinder_price'] / $selCurrency['currency_rate']);
					$tool_price=0;
					if(isset($data['tool_price']) && $data['tool_price']>0.000)
					{
						$tool_price = ($data['tool_price'] / $selCurrency['currency_rate']);
					}
					
					if($cylinder_base_price){
						if($cylinder_currency_price < $cylinder_base_price){
							$cylinder_price = $cylinder_base_price;	
						}else{
							$cylinder_price = $cylinder_currency_price;	
						}
					}else{
						$cylinder_price = $cylinder_currency_price;	
					}
					
					$quantityCustomerHtml .= '<br><div><b>Cylinder price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($cylinder_price),"3").'</div>';
					if(isset($tool_price) && $tool_price>0.000)
					{
						$quantityCustomerHtml .= '<br><div><b>Extra Tool price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($tool_price),"3").'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</div>';
					}
				}else{
					$quantityCustomerHtml .= '<br><div><b>Cylinder price : </b>'.$data['currency'].' '.$data['cylinder_price'].'</div>';
					if(isset($data['tool_price']) && $data['tool_price']>0.000)
					{
						$quantityCustomerHtml .= '<br><div><b>Extra Tool price : </b>'.$data['currency'].' '.$data['tool_price'].'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</div>';
					}
				}
				$quantityCustomerHtml .= '<div><b>Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
			//printr($quantityCustomerHtml);
			//die;
			}
			
			if(1 == 1){
				$quantityHtml .= $setHtml;
				$quantityHtml .= '<style> table, th, td</style>';
				$quantityHtml .= '<div>';
				$i=0;
				foreach($quantityData as $quantity=>$qoption){
					
					$quantityHtml .= '<table cellpadding="0" cellspacing="0" border="0">';
					foreach($qoption as $zipval=>$zipPrice){
						if($i==0)
						{
						$quantityHtml .= '<tr ><td colspan="2"><b>Make up of pouch  : </b> Custom Printed '.$data['product_name'] .' '.$zipval.'</td></tr>';
						$quantityHtml .= '<tr valign="top"><td width="60px"><b>Price : </b></td>';	
						$quantityHtmldis .= '<tr valign="top"><td width="60px"></td>';	
						}
							foreach($zipPrice as $key=>$value){
								$priceval = $this->numberFormate((($value['totalPrice'] / $data['currency_price']) / $quantity) ,"3"); 
								$total = $priceval + ($priceval*$newdata['excies']/100);
								$final = $total + ($total *$newdata['tax_percentage']/100);
								$taxvalue = '';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
								$pricevaldis = $this->numberFormate((($value['totalPrice'] / $data['currency_price']) / $quantity) ,"3"); 
								$pricevaldis = $this->numberFormate(($pricevaldis-($pricevaldis*$value['discount']/100)),"3");
								$totaldis = $pricevaldis + ($pricevaldis*$newdata['excies']/100);
								$finaldis = $totaldis + ($totaldis *$newdata['tax_percentage']/100);
								$taxvaluedis = '';
								}
								if($data['shipment_country_id'] == '111')
								{
									$taxvalue = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
									if(isset($value['discount']) && $value['discount']>0.000)
									{
										$taxvaluedis = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';
									}
								}
								if($i!=0)
								{
									$quantityHtml .='<tr><td width="60px">&nbsp;</td>';
									if(isset($value['discount']) && $value['discount']>0.000)
									$quantityHtmldis .='<tr><td width="60px">&nbsp;</td>';
								}
									$quantityHtml .= '<td>'.$data['currency'].' '.$priceval.' per 1 bag '.$taxvalue;
									if(isset($value['discount']) && $value['discount']>0.000)
									$quantityHtmldis .= '<td><b>Discount ('.$value['discount'].'%) : </b>'.$data['currency'].' '.$pricevaldis.' per 1 bag '.$taxvaluedis;
									if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$quantityHtml .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$quantityHtmldis .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$quantityHtml .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$quantityHtmldis .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
					$quantityHtml .='- By '.ucwords($key).'.'.$br.'</td></tr>';
					if(isset($value['discount']) && $value['discount']>0.000)
					$quantityHtmldis .='- By '.ucwords($key).'.<br><br><br><br></td></tr>';
					$quantityHtml .=$quantityHtmldis;
					$quantityHtmldis='';
						$quantityHtml .='';	
						$i++;}
							
					}
					$quantityHtml .='</table>';
					//printr($quantityHtmldis);
				}
				$quantityHtml .= '</div>';
				$quantityHtml .= '<br><div><b>Cylinder price : </b>'.$data['currency'].' '.$data['cylinder_price'].'</div>';
				if(isset($data['tool_price']) && $data['tool_price']>0.000)
				{
					$quantityHtml .= '<br><div><b>Extra Tool price : </b>'.$data['currency'].' '.$data['tool_price'].'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.
</div>';
				}
				$quantityHtml .= '<div><b>Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
				//printr($quantityHtml);
				//die;
			}
			
			
			///admin email template
			$AdminquantityCustomerHtml = '';
			$AdminquantityHtml = '';
			$AdminquantityCustomerHtmldis = '';
			$AdminquantityHtmldis = '';
			$Adminpqcquery = '';
			if($data['customer_email'] != '' || $toEmail != ''){
				$AdminquantityCustomerHtml .= $setHtml;			
				$AdminquantityCustomerHtml .= '<style> table, th, td </style>';
				$AdminquantityCustomerHtml .= '<div>';
				$selCurrency = '';
				if($setQuotationCurrencyId){
					$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
					if(!$selCurrency){
						$selCurrency = $this->getSelectedCurrecyForQuotation($setQuotationCurrencyId);
					}
				}else{
					$selCurrency = $this->getSelectedCurrecyForQuotation($setQuotationCurrencyId);
				}
								
				if($selCurrency){
					$Adminpqcquery = " product_quotation_currency_id = '".$selCurrency['product_quotation_currency_id']."', ";
				}
				if($gressper['gress_percentage'] == '0.000')
				{
					$i = 0;
				foreach($quantityData as $quantity=>$qoption){
				
					foreach($qoption as $zipval => $zipPrice){
						if($i==0)
						{
							$AdminquantityCustomerHtml .= '<b>Make up of pouch  : </b> Custom Printed '.$data['product_name'] .' '.$zipval.'<br>';
						}
						$AdminquantityCustomerHtml .='<table cellpadding="0" cellspacing="0" border="0">';
						foreach($zipPrice as $key=>$value){
								if($selCurrency){
									$newPirce = ((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $selCurrency['currency_rate'] );
									if($i==0)
									{
										$AdminquantityCustomerHtml .= '<tr valign="top"><td width="60" ><b> Price : </b></td>';	
										if(isset($value['discount']) && $value['discount']>0.000)
										$AdminquantityCustomerHtmldis .= '<tr valign="top"><td width="60" ></td>';	
									}
									else
									{
									$AdminquantityCustomerHtml .= '<tr><td width="60" >&nbsp;</td>';
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityCustomerHtmldis .= '<tr><td width="60" >&nbsp;</td>';
									}
									
								
									$AdminquantityCustomerHtml .= '<td>'.$selCurrency['currency_code'].' '.$this->numberFormate(( $newPirce / $quantity) ,"3").' per 1 bag ';
									if(isset($value['discount']) && $value['discount']>0.000)
									{
										$p=$this->numberFormate(( $newPirce / $quantity) ,"3");
									$AdminquantityCustomerHtmldis .= '<td><b>Discount ('.$value['discount'].'%) : </b>'.$selCurrency['currency_code'].' 
									'.$this->numberFormate(($p-($p*$value['discount']/100)),"3").' per 1 bag ';					
									}
									$i++;
									if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$AdminquantityCustomerHtml .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$AdminquantityCustomerHtml .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
					$AdminquantityCustomerHtml .=	'- by '.$key.'.'.$br.'</td></tr>';
					if(isset($value['discount']) && $value['discount']>0.000)
					$AdminquantityCustomerHtmldis .=	'- by '.$key.'<br><br><br><br></td></tr>';
					$AdminquantityCustomerHtml.=$AdminquantityCustomerHtmldis;
					$AdminquantityCustomerHtmldis='';
								}else{
									
									$priceval =$this->numberFormate(((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $quantity) ,"3"); 
									$minuspriceval =$this->numberFormate(((($value['totalPrice'] - $value['customerGressPrice'] - $value['gress_price']) / 
									$data['currency_price']) / $quantity) ,"3"); 
								$total = $priceval + ($priceval*$newdata['excies']/100);
								$final = $total + ($total *$newdata['tax_percentage']/100);
								$minustotal = $minuspriceval + ($minuspriceval*$newdata['excies']/100);
								$minusfinal = $minustotal + ($minustotal *$newdata['tax_percentage']/100);
								if(isset($value['discount']) && $value['discount']>0.000)
								{
									$pricevaldis =$this->numberFormate(($priceval-($priceval*$value['discount']/100)),"3");
									$minuspricevaldis =$this->numberFormate(($minuspriceval-($minuspriceval*$value['discount']/100)),"3");
								$totaldis = $pricevaldis + ($pricevaldis*$newdata['excies']/100);
								$finaldis = $totaldis + ($totaldis *$newdata['tax_percentage']/100);
								$minustotaldis = $minuspricevaldis + ($minuspricevaldis*$newdata['excies']/100);
								$minusfinaldis = $minustotaldis + ($minustotaldis *$newdata['tax_percentage']/100);
								}
								if($data['shipment_country_id'] == '111')
								{
									$taxvalue = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
									$minustaxvalue = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinal,'3').' per 1 bag including of all taxes.';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
										$taxvaluedis = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';
									$minustaxvaluedis = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinaldis,'3').' per 1 bag including of all taxes.';
								}
								}
									
									$AdminquantityCustomerHtml .= '<span style="margin-right:90px"><b>Price : </b></span>'.$data['currency'].' '.$priceval.' per 1 bag '.$taxvalue;
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityCustomerHtmldis .= '<span style="margin-left:150px"><b>Discount ('.$value['discount'].' %) : </b></span>'.$data['currency'].' '.$pricevaldis.' per 1 bag '.$taxvaluedis;
										if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$AdminquantityCustomerHtml .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$AdminquantityCustomerHtml .= '<b>{ For  '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b>{ For  '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
						if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
					
					$AdminquantityCustomerHtml .=' - By '.ucwords($key).'.'.$br.'</td></tr>';
					if(isset($value['discount']) && $value['discount']>0.000)
					$AdminquantityCustomerHtmldis .=' - By '.ucwords($key).'.<br><br><br><br></td></tr>';
					$AdminquantityCustomerHtml .=$AdminquantityCustomerHtmldis;
					$AdminquantityCustomerHtmldis ='';
								}
							}
							$AdminquantityCustomerHtml .='</table>';
					}
				}
				$AdminquantityCustomerHtml .= '</div>';//echo 'sf';
			//printr($AdminquantityCustomerHtml);//die;
				}
				else
				{
					$i=0;
					foreach($quantityData as $quantity=>$qoption){
					
					foreach($qoption as $zipval => $zipPrice){
											
						if($i==0)
						{
							$AdminquantityCustomerHtml .= '<b>Make up of pouch  : </b> Custom Printed '.$data['product_name'] .' '.$zipval.'<br>';
						}
						$AdminquantityCustomerHtml .= '<table cellpadding="0" cellspacing="0">';
						foreach($zipPrice as $key=>$value){
								
								if($selCurrency){
									$newPirce = ((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $selCurrency['currency_rate'] );
									if($i==0)
									{
										$AdminquantityCustomerHtml .= '<tr valign="top"><td><b> Price : </b></td></tr>';
										if(isset($value['discount']) && $value['discount']>0.000)
										$AdminquantityCustomerHtmldis .= '<tr valign="top"><td></td></tr>';
									}
									else
									{
									$AdminquantityCustomerHtml .= '<tr><td width="50">&nbsp;</td>';
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityCustomerHtmldis .= '<tr><td width="50">&nbsp;</td>';
									}
									
									$AdminquantityCustomerHtml .= '<td>'.$selCurrency['currency_code'].' '.$this->numberFormate(( $newPirce / $quantity) ,"3").' per 1 bag ';
									if(isset($value['discount']) && $value['discount']>0.000)
									{
										$p=$this->numberFormate(( $newPirce / $quantity) ,"3");
									$AdminquantityCustomerHtmldis .= '<td><b>Discount ('.$value['discount'].'%) : </b>'.$selCurrency['currency_code'].' 
									'.$this->numberFormate(($p-($p*$value['discount']/100)),"3").' per 1 bag ';					
									}
									$i++;
									
									
									if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$AdminquantityCustomerHtml .= '<b>{ For '.$quantity.'  plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b>{ For '.$quantity.'  plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$AdminquantityCustomerHtml .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
						$AdminquantityCustomerHtml .='- by '.$key.'.'.$br.'</td></tr>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .='- by '.$key.'.<br><br><br><br></td></tr>';
						$AdminquantityCustomerHtml .=$AdminquantityCustomerHtmldis;
						$AdminquantityCustomerHtmldis ='';
					}else{
									
									$priceval =$this->numberFormate(((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $quantity) ,"3"); 
								
									$minuspriceval =$this->numberFormate(((($value['totalPrice'] - $value['customerGressPrice'] - $value['gress_price']) / 
									$data['currency_price']) / $quantity) ,"3"); 
								$total = $priceval + ($priceval*$newdata['excies']/100);
								$final = $total + ($total *$newdata['tax_percentage']/100);
								$minustotal = $minuspriceval + ($minuspriceval*$newdata['excies']/100);
								$minusfinal = $minustotal + ($minustotal *$newdata['tax_percentage']/100);
								$taxvalue = '';
								$minustaxvalue = '';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
								$pricevaldis =$this->numberFormate(($priceval-($priceval*$value['discount']/100)),"3");
								$minuspricevaldis =$this->numberFormate(($minuspriceval-($minuspriceval*$value['discount']/100)),"3");
								$totaldis = $pricevaldis + ($pricevaldis*$newdata['excies']/100);
								$finaldis = $totaldis + ($totaldis *$newdata['tax_percentage']/100);
								$minustotaldis = $minuspricevaldis + ($minuspricevaldis*$newdata['excies']/100);
								$minusfinaldis = $minustotaldis + ($minustotaldis *$newdata['tax_percentage']/100);
								$taxvaluedis = '';
								$minustaxvaluedis = '';
								}
								if($data['shipment_country_id'] == '111')
								{
									$taxvalue = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
									$minustaxvalue = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinal,'3').' per 1 bag including of all taxes.';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
									$taxvaluedis = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';
									$minustaxvaluedis = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinaldis,'3').' per 1 bag including of all taxes.';
								}
								}
									
									$AdminquantityCustomerHtml .= '<span style="margin-right:90px"><b>Price : </b></span>'.$data['currency'].' '.$priceval.' per 1 bag '.$taxvalue;
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityCustomerHtmldis .= '<span  style="margin-left:150px"><b>Discount ('.$value['discount'].'%) : </b></span>'.$data['currency'].' '.$pricevaldis.' per 1 bag '.$taxvaluedis;
									if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$AdminquantityCustomerHtml .= '<b>{ For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b>{ For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$AdminquantityCustomerHtml .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
						$AdminquantityCustomerHtml .=' - By '.ucwords($key).'.'.$br.'</td></tr>';
					if(isset($value['discount']) && $value['discount']>0.000)
					$AdminquantityCustomerHtmldis .=' - By '.ucwords($key).'.<br><br><br><br></td></tr>';
									$AdminquantityCustomerHtml.=$AdminquantityCustomerHtmldis;
									$AdminquantityCustomerHtmldis='';
								}
							}
							
					}
						$AdminquantityCustomerHtml .='</table>';
				}
				$AdminquantityCustomerHtml .= '</div><div></div>';
				
			//printr($AdminquantityCustomerHtml);//die;
				$AdminquantityCustomerHtml .= '<div><b>Price : </b> Less Gress Percentage : '.$gressper['gress_percentage'].' % <br>';
				
				$i=0;
				foreach($quantityData as $quantity=>$qoption){
					//printr($quantity);
					//	die;
						
						
					
					foreach($qoption as $zipval => $zipPrice){
						//printr($zipval);
						//die;
						if($i==0)
						{
						$AdminquantityCustomerHtml .= '<b>Make up of pouch  : </b> Custom Printed '. $data['product_name'] .' '.$zipval.'<br>';
						}
						//printr($quantityCustomerHtml);
							//die;
							$AdminquantityCustomerHtml .= '<table cellpadding="0" cellspacing="0">';
							foreach($zipPrice as $key=>$value){
								
								if($selCurrency){
									//$newPirce = ((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $selCurrency['currency_rate'] );
									
									$minusnewPirce = ((($value['totalPrice'] - $value['customerGressPrice'] - $value['gress_price']) / $data['currency_price']) / $selCurrency['currency_rate'] );
									if($i==0)
									{
									$AdminquantityCustomerHtml .= '<tr valign="top"><td width="60"><b>total Gress Price : </b></td>';
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityCustomerHtmldis .= '<tr valign="top"><td width="60"></td>';
									}
									else
									{
										$AdminquantityCustomerHtml .= '<tr><td width="60">&nbsp;</td>';
										if(isset($value['discount']) && $value['discount']>0.000)
										$AdminquantityCustomerHtmldis .= '<tr><td width="60">&nbsp;</td>';
									}
									$AdminquantityCustomerHtml .= '<td>'.$selCurrency['currency_code'].' '.$this->numberFormate(( $minusnewPirce / $quantity) ,"3").' per 1 bag ';
									if(isset($value['discount']) && $value['discount']>0.000)
									{
										$p=$this->numberFormate(( $minusnewPirce / $quantity) ,"3");
										$AdminquantityCustomerHtmldis .= '<td><b>Discount ('.$value['discount'].'%) : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($p-($p*$value['discount']/100)),"3").' per 1 bag ';
									}
									$i++;
								
									if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$AdminquantityCustomerHtml .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$AdminquantityCustomerHtml .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
						$AdminquantityCustomerHtml .= '- by '.$key.'.'.$br.'</td></tr>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '- by '.$key.'<br><br><br><br></td></tr>';
						$AdminquantityCustomerHtml .=$AdminquantityCustomerHtmldis;
						$AdminquantityCustomerHtmldis='';
								}else{
									
									$priceval =$this->numberFormate(((($value['totalPrice'] + $value['customerGressPrice']) / $data['currency_price']) / $quantity) ,"3"); 
									$minuspriceval =$this->numberFormate(((($value['totalPrice'] - $value['customerGressPrice'] - $value['gress_price']) / 
									$data['currency_price']) / $quantity) ,"3"); 
								$total = $priceval + ($priceval*$newdata['excies']/100);
								$final = $total + ($total *$newdata['tax_percentage']/100);
								$minustotal = $minuspriceval + ($minuspriceval*$newdata['excies']/100);
								$minusfinal = $minustotal + ($minustotal *$newdata['tax_percentage']/100);
								$minustaxvalue = ' ';
								$taxvalue = ' ';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
									$pricevaldis =$this->numberFormate(($priceval-($priceval*$value['discount']/100)),"3");
									$minuspricevaldis =$this->numberFormate(($minuspriceval-($minuspriceval*$value['discount']/100)),"3");
								$totaldis = $pricevaldis + ($pricevaldis*$newdata['excies']/100);
								$finaldis = $totaldis + ($totaldis *$newdata['tax_percentage']/100);
								$minustotaldis = $minuspricevaldis + ($minuspricevaldis*$newdata['excies']/100);
								$minusfinaldis = $minustotaldis + ($minustotaldis *$newdata['tax_percentage']/100);
								$minustaxvaluedis = ' ';
								$taxvaluedis = ' ';
								}
								if($data['shipment_country_id'] == '111')
								{
									$taxvalue = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
									$minustaxvalue = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinal,'3').' per 1 bag including of all taxes.';
									if(isset($value['discount']) && $value['discount']>0.000)
									{
										$taxvaluedis = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';
									$minustaxvaluedis = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinaldis,'3').' per 1 bag including of all taxes.';
									}
								}
									
									//$AdminquantityCustomerHtml .= '<b>With Gress Price : </b>'.' '.$data['currency'].' '.$priceval.' per 1 bag '.$taxvalue.' - By '.ucwords($key).'.</td></tr>';
									$AdminquantityCustomerHtml .= '<span style="margin-right:90px"><b>Price : </b></span>'.$data['currency'].' '.$minuspriceval.' per 1 bag '.$minustaxvalue;
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityCustomerHtmldis .= '<span style="margin-left:150px"><b>Discount ('.$value['discount'].'%) : </b></span>'.$data['currency'].' '.$minuspricevaldis.' per 1 bag '.$minustaxvaluedis;
										if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$AdminquantityCustomerHtml .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$AdminquantityCustomerHtml .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityCustomerHtmldis .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
					$AdminquantityCustomerHtml .=' - By '.ucwords($key).'.'.$br.'</td></tr>';
					if(isset($value['discount']) && $value['discount']>0.000)
					$AdminquantityCustomerHtmldis .=' - By '.ucwords($key).'.<br><br><br><br></td></tr>';
					$AdminquantityCustomerHtml .=$AdminquantityCustomerHtmldis;
					$AdminquantityCustomerHtmldis ='';
									//$AdminquantityCustomerHtml .= '<b>Gress Percentage : </b>'.$value['gress_percentage'].' % </td></tr><tr><td>&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td><td>';
									//$AdminquantityCustomerHtml .= '<b>Customer Gress Percentage : </b> '.$value['customer_gress_percentage'].' % </td></tr>';
									
								}
							}
					}
					$AdminquantityCustomerHtml .='</table>';
				}
				$AdminquantityCustomerHtml .= '</div>';
					
					//printr($AdminquantityCustomerHtml);
					//die;
					
					
				}
							
				if($selCurrency){
					
					$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_id']);
					
					$cylinder_currency_price = ($data['cylinder_price'] / $selCurrency['currency_rate']);
					$tool_price=0;
					if(isset($data['tool_price']) && $data['tool_price']>0.000)
					{
						$tool_price = ($data['tool_price'] / $selCurrency['currency_rate']);
					}
					if($cylinder_base_price){
						if($cylinder_currency_price < $cylinder_base_price){
							$cylinder_price = $cylinder_base_price;	
						}else{
							$cylinder_price = $cylinder_currency_price;	
						}
					}else{
						$cylinder_price = $cylinder_currency_price;	
					}
					
					$AdminquantityCustomerHtml .= '<br><div><b>Cylinder price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($cylinder_price),"3").'</div>';
					if(isset($tool_price) && $tool_price>0.000)
					{
					$AdminquantityCustomerHtml .= '<br><div><b>Extra Tool price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($tool_price),"3").'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</div>';
					}
				}else{
					$AdminquantityCustomerHtml .= '<br><div><b>Cylinder price : </b>'.$data['currency'].' '.$data['cylinder_price'].'</div>';
					if(isset($data['tool_price']) && $data['tool_price'] >0.000)
					{
						$AdminquantityCustomerHtml .= '<br><div><b>Extra Tool price : </b>'.$data['currency'].' '.$data['tool_price'].'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</div>';
					}
				}
				$AdminquantityCustomerHtml .= '<div><b>Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
				//printr($AdminquantityCustomerHtml);
				//die;
			}
			
			if(1 == 1){
				$AdminquantityHtml .= $setHtml;
				$AdminquantityHtml .= '<style> table, th, td </style>';
				$AdminquantityHtml .= '<div>';
				$i=0;
				if($gressper['gress_percentage'] == '0.000')
				{
				foreach($quantityData as $quantity=>$qoption){
					
					
					$AdminquantityHtml.='<table cellpadding="0" cellspacing="0" border="0">';
					foreach($qoption as $zipval=>$zipPrice){
						
						if($i==0)
						{
						$AdminquantityHtml .= '<tr><td colspan="2"><b>Make up of pouch  : </b> Custom Printed '. $data['product_name'] .' '.$zipval.'</td></tr>';
						$AdminquantityHtml .= '<tr valign="top"><td width="60px"><b>Price : </b></td>';
						$AdminquantityHtmldis .= '<tr valign="top"><td width="60px"></td>';
						}
							foreach($zipPrice as $key=>$value){
								
								$priceval = $this->numberFormate((($value['totalPrice'] / $data['currency_price']) / $quantity) ,"3"); 
								$minusprice = $value['totalPrice'] - $value['gress_price'] - $value['customerGressPrice']; 
								$minuspriceval = $this->numberFormate((($minusprice / $data['currency_price']) / $quantity) ,"3"); 
								$total = $priceval + ($priceval*$newdata['excies']/100);
								$final = $total + ($total *$newdata['tax_percentage']/100);
								$minustotal = $minuspriceval + ($minuspriceval*$newdata['excies']/100);
								$minusfinal = $minustotal + ($minustotal *$newdata['tax_percentage']/100);
								
								$taxvalue = '';
								$minustaxvalue = '';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
								$pricevaldis = $this->numberFormate(($priceval-($priceval*$value['discount']/100)),"3"); 
								$minuspricedis = ($minusprice-($minusprice*$value['discount']/100)); 
								$minuspricevaldis = $this->numberFormate((($minuspricedis / $data['currency_price']) / $quantity) ,"3"); 
								$totaldis = $pricevaldis + ($pricevaldis*$newdata['excies']/100);
								$finaldis = $totaldis + ($totaldis *$newdata['tax_percentage']/100);
								$minustotaldis = $minuspricevaldis + ($minuspricevaldis*$newdata['excies']/100);
								$minusfinaldis = $minustotaldis + ($minustotaldis *$newdata['tax_percentage']/100);
								
								$taxvaluedis = '';
								$minustaxvaluedis = '';
								}
								if($data['shipment_country_id'] == '111')
								{
									$taxvalue = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
									$minustaxvalue = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinal,'3').' per 1 bag including of all taxes.';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
									$taxvaluedis = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';
									$minustaxvaluedis = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinaldis,'3').' per 1 bag including of all taxes.';
								}
								}
									
								if($i!=0)
								{
									$AdminquantityHtml .='<tr><td width="60px">&nbsp;</td>';
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityHtmldis .='<tr><td width="60px">&nbsp;</td>';
								}
									
									$AdminquantityHtml .= '<td>'.$data['currency'].' '.$priceval.' per 1 bag '.$taxvalue;
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityHtmldis .= '<td><b>Discount ('.$value['discount'].'%) : </b>'.$data['currency'].' '.$pricevaldis.' per 1 bag '.$taxvaluedis;
								
								if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$AdminquantityHtml .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityHtmldis .= '<b> { For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$AdminquantityHtml .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityHtmldis .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
						$AdminquantityHtml .= ' - By '.ucwords($key).'.'.$br.'</td></tr>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityHtmldis .= ' - By '.ucwords($key).'.<br><br><br><br></td></tr>';
						$AdminquantityHtml.=$AdminquantityHtmldis;
						$AdminquantityHtmldis='';
								$i++;
							}
							
					}$AdminquantityHtml .= '</table>';
				}
				$AdminquantityHtml .= '</div>';
				//printr($AdminquantityHtml);
				//die;
				}
				else
				{
					foreach($quantityData as $quantity=>$qoption){
						$AdminquantityHtml .='<table cellpadding="0" cellspacing="0">';
						foreach($qoption as $zipval=>$zipPrice){
						if($i==0)
						{
							$AdminquantityHtml .= '<tr><td colspan="2"><b>Make up of pouch  : </b> Custom Printed '. $data['product_name'] .' '.
							$zipval.'</td></tr>';
							$AdminquantityHtml .= '<tr valign="top"><td width="60px"><b> Price : </b></td>';
							$AdminquantityHtmldis .= '<tr valign="top"><td width="60px"></td>';
						}
						
						
							foreach($zipPrice as $key=>$value){
								
								$priceval = $this->numberFormate((($value['totalPrice'] / $data['currency_price']) / $quantity) ,"3"); 
								$minusprice = $value['totalPrice'] - $value['gress_price'] - $value['customerGressPrice']; 
								$minuspriceval = $this->numberFormate((($minusprice / $data['currency_price']) / $quantity) ,"3"); 
								$total = $priceval + ($priceval*$newdata['excies']/100);
								$final = $total + ($total *$newdata['tax_percentage']/100);
								$minustotal = $minuspriceval + ($minuspriceval*$newdata['excies']/100);
								$minusfinal = $minustotal + ($minustotal *$newdata['tax_percentage']/100);
								$taxvalue = '';
								$minustaxvalue = '';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
								$pricevaldis = $this->numberFormate(($priceval-($priceval*$value['discount']/100)),"3"); 
								$minuspricedis = ($minusprice-($minusprice*$value['discount']/100));
								$minuspricevaldis = $this->numberFormate((($minuspricedis / $data['currency_price']) / $quantity) ,"3"); 
								$totaldis = $pricevaldis + ($pricevaldis*$newdata['excies']/100);
								$finaldis = $totaldis + ($totaldis *$newdata['tax_percentage']/100);
								$minustotaldis = $minuspricevaldis + ($minuspricevaldis*$newdata['excies']/100);
								$minusfinaldis = $minustotaldis + ($minustotaldis *$newdata['tax_percentage']/100);
								$taxvaluedis = '';
								$minustaxvaluedis = '';
								}
								if($data['shipment_country_id'] == '111')
								{
									$taxvalue = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
									$minustaxvalue = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinal,'3').' per 1 bag including of all taxes.';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
									$taxvaluedis = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';
									$minustaxvaluedis = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinaldis,'3').' per 1 bag including of all taxes.';
								}
								}
								if($i!=0)
								{
									$AdminquantityHtml .= '<tr><td width="60px">&nbsp;</td>';
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityHtmldis .= '<tr><td width="60px">&nbsp;</td>';
								}
									$AdminquantityHtml .= '<td>'.$data['currency'].' '.$priceval.' per 1 bag '.$taxvalue;
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityHtmldis .= '<td><b>Discount('.$value['discount'].' %) : </b>'.$data['currency'].' '.$pricevaldis.' per 1 bag '.$taxvaluedis;
									if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$AdminquantityHtml .= '<b>{ For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityHtmldis .= '<b>{ For '.$quantity.' plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$AdminquantityHtml .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityHtmldis .= '<b> { For '.$quantity.' bags plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
						$AdminquantityHtml .= ' - By '.ucwords($key).'.'.$br.'</td></tr>';			
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityHtmldis .= ' - By '.ucwords($key).'.<br><br><br><br></td></tr>';			
						$AdminquantityHtml .=$AdminquantityHtmldis;
						$AdminquantityHtmldis='';
						$i++;	}
					}
					$AdminquantityHtml .='</table>';
				}
				$AdminquantityHtml .= '</div><div></div>';
				
				$AdminquantityHtml .= '<div>Less Gress Percentage : '.$gressper['gress_percentage'].'% <br>';
				
				
				foreach($quantityData as $quantity=>$qoption){
				$AdminquantityHtml .='<table cellpadding="0" cellspacing="0">';
					foreach($qoption as $zipval=>$zipPrice){
						if($i==0)
						{
							$AdminquantityHtml .= '<tr><td  colspan="2"><b>Make up of pouch  : </b> Custom Printed '. $data['product_name'] .' '.
							$zipval.'</td></tr>';
							$AdminquantityHtml .= '<tr valign="top"><td width="60px"><b> Price : </b></td>';
							$AdminquantityHtmldis .= '<tr valign="top"><td width="60px"></td>';
						}
						
							foreach($zipPrice as $key=>$value){
								
								$priceval = $this->numberFormate((($value['totalPrice'] / $data['currency_price']) / $quantity) ,"3"); 
								$minusprice = $value['totalPrice'] - $value['gress_price'] - $value['customerGressPrice']; 
								$minuspriceval = $this->numberFormate((($minusprice / $data['currency_price']) / $quantity) ,"3"); 
								$total = $priceval + ($priceval*$newdata['excies']/100);
								$final = $total + ($total *$newdata['tax_percentage']/100);
								$minustotal = $minuspriceval + ($minuspriceval*$newdata['excies']/100);
								$minusfinal = $minustotal + ($minustotal *$newdata['tax_percentage']/100);
								$taxvalue = '';
								$minustaxvalue = '';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
								$pricevaldis = ($priceval-($priceval*$value['discount']/100)); 
								$minuspricedis = ($minusprice-($minusprice*$value['discount']/100)); 
								$minuspricevaldis = $this->numberFormate((($minuspricedis / $data['currency_price']) / $quantity) ,"3"); 
								$totaldis = $pricevaldis + ($pricevaldis*$newdata['excies']/100);
								$finaldis = $totaldis + ($totaldis *$newdata['tax_percentage']/100);
								$minustotaldis = $minuspricevaldis + ($minuspricevaldis*$newdata['excies']/100);
								$minusfinaldis = $minustotaldis + ($minustotaldis *$newdata['tax_percentage']/100);
								$taxvaluedis = '';
								$minustaxvaluedis = '';
								}
								if($data['shipment_country_id'] == '111')
								{
									$taxvalue = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
									$minustaxvalue = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinal,'3').' per 1 bag including of all taxes.';
								if(isset($value['discount']) && $value['discount']>0.000)
								{
									$taxvaluedis = ' + '.$newdata['excies'].'% Excise'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';
									$minustaxvaluedis = ' + '.$newdata['excies'].'% Excies'.' + '.$newdata['tax_percentage'].'% '.
									str_replace('_',' ',strtoupper($newdata['tax_type'])).' = '.$data['currency'].' '.round($minusfinaldis,'3').' per 1 bag including of all taxes.';
								}
								}
									
								if($i!=0)
								{
								$AdminquantityHtml .= '<tr><td width="60px">&nbsp;</td>';
								if(isset($value['discount']) && $value['discount']>0.000)
								$AdminquantityHtmldis .= '<tr><td width="60px">&nbsp;</td>';
								}
								
									$AdminquantityHtml .= '<td>'.$data['currency'].' '.$minuspriceval.' per 1 bag '.$minustaxvalue;
									if(isset($value['discount']) && $value['discount']>0.000)
									$AdminquantityHtmldis .= '<td><span><b>Discount ('.$value['discount'].'%) : </b></span>'.$data['currency'].' '.$minuspricevaldis.' per 1 bag '.$minustaxvaluedis;
									if($data['quotation_type']==1){
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],1);
						$AdminquantityHtml .= '<b>{ For '.$quantity.'  plus or minus'.$plus_minus_quantity.' }</b>';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityHtmldis .= '<b>{ For '.$quantity.'  plus or minus'.$plus_minus_quantity.' }</b>';
					}else{
						$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($quantity,$data['product_id'],$data['height'],$data['width'],$data['gusset'],0);
						$AdminquantityHtml .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityHtmldis .= '<b>{ For '.$quantity.' bags  plus or minus '.$plus_minus_quantity.' bags }</b>&nbsp;';
					}
					if(isset($value['discount']) && $value['discount']>0.000)
					$br='<br><br>';
					else
					$br='<br><br>';
						$AdminquantityHtml .=' - By '.ucwords($key).'.'.$br.'</td></tr>';			
						if(isset($value['discount']) && $value['discount']>0.000)
						$AdminquantityHtmldis .=' - By '.ucwords($key).'.<br><br><br><br></td></tr>';			
						$AdminquantityHtml.=$AdminquantityHtmldis;
						$AdminquantityHtmldis='';
							$i++;	}
					}
					$AdminquantityHtml .='</table>';
				}
				$AdminquantityHtml .= '</div>';
				}
				$AdminquantityHtml .= '<br><div><b>Cylinder price : </b>'.$data['currency'].' '.$data['cylinder_price'].'</div>';
				if(isset($data['tool_price']) && $data['tool_price']>0.000)
				{
					$AdminquantityHtml .= '<br><div><b>Extra Tool price : </b>'.$data['currency'].' '.$data['tool_price'].'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</div>';
				}
				$AdminquantityHtml .= '<div><b>Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
		//printr($AdminquantityHtml);die;
			}
			
			//end admin email
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(1); 
			
			$formEmail = $addedByInfo['email'];
			$firstTimeemial = 0;
			if($toEmail == ''){
				$toEmail = $formEmail;
				$firstTimeemial = 1;
			}
						
			
			if($data['gusset'] == '')
			{
				$gussetval1 = 'No Gusset';
			}
			else
			{
				if($data['product_id'] == '7')
				{
					$gussetval1 = ' { '.$data['gusset'].'mm  + '. $data['gusset']
					.'mm (Sg) + '.$data['gusset'].'mm (Bg) }';
				}
				elseif($data['gusset'] == '0')
				{
					$gussetval1 = ' ';
				}
				else
				{
					$gussetval1 = ' { '.$data['gusset'].'mm  + '.$data['gusset'].'mm (Bg) }';
				}
			}
			$subject = $data['quotation_number'] .' - '.ucwords($data['customer_name']).' - custom printed '.
				$first.' '.$newdata['zipper_txt'].' '.$newdata['valve_txt'].' W : '.(int)$data['width'].'mm  x '.'H : '.(int)$data['height'].'mm '.
				$gussetval1;
		
			//printr($subject);
			//die;	
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
			$path = HTTP_SERVER."template/product_quotation.html";
			$output = file_get_contents($path);  
			//printr($output);die;
			$search  = array('{tag:header}','{tag:details}');
			
			$message = '';
			$customerMessage = '';
			$AdmincustomerMessage = '';
			$signature = 'Thanks.';
			if($addedByInfo['email_signature']){
				$signature = nl2br($addedByInfo['email_signature']);
			}
			
			if($quantityHtml){
				$tag_val = array(
					"{{productDetail}}" =>$quantityHtml,
					"{{signature}}"	=> $signature,
				);
				if(!empty($tag_val))
				{
					$desc = $temp_desc;
					foreach($tag_val as $k=>$v)
					{
						@$desc = str_replace(trim($k),trim($v),trim($desc));
					} 
				}
				$replace = array($subject,$desc);
				$message = str_replace($search, $replace, $output);
			}
			
			if($AdminquantityHtml){
				$Admintag_val = array(
					"{{productDetail}}" =>$AdminquantityHtml,
					"{{signature}}"	=> $signature,
				);
				if(!empty($Admintag_val))
				{
					$Admindesc = $temp_desc;
					foreach($Admintag_val as $k=>$v)
					{
						@$Admindesc = str_replace(trim($k),trim($v),trim($Admindesc));
					} 
				}
				$Adminreplace = array($subject,$Admindesc);
				$Adminmessage = str_replace($search, $Adminreplace, $output);
			}
			
			if($quantityCustomerHtml){
				$customer_tag = array(
					"{{productDetail}}" =>$quantityCustomerHtml,
					"{{signature}}"	=> $signature,
				);
				
				$cdesc = $temp_desc;
				if(!empty($customer_tag))
				{	
					foreach($customer_tag as $k=>$v)
					{
						@$cdesc = str_replace(trim($k),trim($v),trim($cdesc));
					}
				}
				$customer_replace = array($subject,$cdesc);
			//	printr($quantityCustomerHtml);die;
				$customerMessage = str_replace($search, $customer_replace, $output); 
			
			}
			if($AdminquantityCustomerHtml){
				$Admincustomer_tag = array(
					"{{productDetail}}" =>$AdminquantityCustomerHtml,
					"{{signature}}"	=> $signature,
				);
				
				$Admincdesc = $temp_desc;
				if(!empty($Admincustomer_tag))
				{	
					foreach($Admincustomer_tag as $k=>$v)
					{
						@$Admincdesc = str_replace(trim($k),trim($v),trim($Admincdesc));
					}
				}
				$Admincustomer_replace = array($subject,$Admincdesc);
				
				$AdmincustomerMessage = str_replace($search, $Admincustomer_replace, $output); 
			
			}
			//printr($customerMessage);
		//	printr($Adminmessage);
		//	printr($message);
			//printr($AdmincustomerMessage);die;
			//die;
			$qstr_customer = '';
			if($data['customer_email'] != '' && $firstTimeemial == 1){
				
				send_email($data['customer_email'],$formEmail,$subject,$customerMessage,'');
				send_email($toEmail,$formEmail,$subject,$Adminmessage,'');
				
				$customer_email = $data['customer_email'];
				$qstr_customer = " sent_customer = 1, customer_email = '".$customer_email."', ";
			}elseif($data['customer_email'] != ''){
				
				send_email($toEmail,$formEmail,$subject,$customerMessage,'');
				send_email(ADMIN_EMAIL,$formEmail,$subject,$AdmincustomerMessage,'');
			}else{
				
				if($toEmail != ''){
					send_email($toEmail,$formEmail,$subject,$customerMessage,'');
					send_email(ADMIN_EMAIL,$formEmail,$subject,$AdmincustomerMessage,'');
				}else{
					
					send_email($toEmail,$formEmail,$subject,$message,'');
					send_email(ADMIN_EMAIL,$formEmail,$subject,$Adminmessage,'');
				}
			}
			
			$qstr = '';
			if($firstTimeemial){//printr($message);die;
				send_email(ADMIN_EMAIL,$formEmail,$subject,$Adminmessage,'');		
				$qstr = 'sent_admin = 1,';
				send_email($toEmail,$formEmail,$subject,$message,'');
			}
		//	echo "INSERT INTO `" . DB_PREFIX . "product_quotation_email_history` SET product_quotation_id = '".$data['product_quotation_id']."', customer_name = '".addslashes($data['customer_name'])."', user_type_id = '" .$data['added_by_user_type_id']. "', user_id = '" .$data['added_by_user_id']. "', to_email = '".$toEmail."', from_email = '" .$formEmail. "',  $qstr_customer admin_default_email='".ADMIN_EMAIL."', status = '1', $qstr $pqcquery date_added = NOW()";die;
			//store emial history
		//	echo "INSERT INTO `" . DB_PREFIX . "product_quotation_email_history` SET product_quotation_id = '".$data['product_quotation_id']."', customer_name = '".addslashes($data['customer_name'])."', user_type_id = '" .$data['added_by_user_type_id']. "', user_id = '" .$data['added_by_user_id']. "', to_email = '".$toEmail."', from_email = '" .$formEmail. "', product_quotation_currency_id='".$setQuotationCurrencyId."',  $qstr_customer admin_default_email='".ADMIN_EMAIL."', status = '1', $qstr $pqcquery date_added = NOW()";die;
			$this->query("INSERT INTO `" . DB_PREFIX . "product_quotation_email_history` SET product_quotation_id = '".$data['product_quotation_id']."', customer_name = '".addslashes($data['customer_name'])."', user_type_id = '" .$data['added_by_user_type_id']. "', user_id = '" .$data['added_by_user_id']. "', to_email = '".$toEmail."', from_email = '" .$formEmail. "',product_quotation_currency_id='".$setQuotationCurrencyId."',   $qstr_customer admin_default_email='".ADMIN_EMAIL."', status = '1', $qstr $pqcquery date_added = NOW()");
		}
	}
	
	public function getEmpAdminId($user_id)
	{
		$sql ="SELECT user_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."'";
		$data = $this->query($sql);
	//	printr($data);die;
		return $data->row['user_id'];
	}
	
	public function getUserList(){
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
		$data = $this->query($sql);
		//printr($data);die;
		return $data->rows;
	}
	
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			//$sql = "SELECT u.user_name,u.first_name,u.last_name,am.user_type_id,am.user_id FROM " . DB_PREFIX ."user u, " . DB_PREFIX ."account_master am WHERE u.user_id = '".(int)$user_id."' AND am.user_id = '".(int)$user_id."' AND am.user_type_id = '".(int)$user_type_id."'";
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, co.country_id, co.country_name, e.first_name, e.last_name, e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,ib.discount, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			/*$sql = "SELECT co.country_id, co.country_name, c.first_name, c.last_name, c.email_signature, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";*/
			return false;
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	//Close : Listing
	
	public function getQuotationSelectedData($quotation_id,$selected,$user_type_id="",$user_id=""){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT $selected FROM " . DB_PREFIX ."product_quotation WHERE product_quotation_id = '".(int)$quotation_id."'";
			}else{
				$sql = "SELECT $selected FROM " . DB_PREFIX ."product_quotation WHERE added_by_user_id = '".(int)$user_id."' AND added_by_user_type_id = '".(int)$user_type_id."' AND product_quotation_id = '".(int)$quotation_id."'";
			}
		}else{
			$sql = "SELECT $selected FROM " . DB_PREFIX ."product_quotation WHERE product_quotation_id = '".(int)$quotation_id."'";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getQuotation($quotation_id,$getData = '*',$user_type_id='',$user_id=''){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT $getData,cn.country_name FROM " . DB_PREFIX ."product_quotation pq INNER JOIN " . DB_PREFIX ."country cn ON (pq.shipment_country_id=cn.country_id) WHERE product_quotation_id = '".(int)$quotation_id."'";
			}else{
				
				if($user_type_id == 2){
					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					$set_user_id = $parentdata->row['user_id'];
					$set_user_type_id = $parentdata->row['user_type_id'];
				}else{
					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
					$set_user_id = $user_id;
					$set_user_type_id = $user_type_id;
				}
				
				$str = '';
				if($userEmployee){
					$str = ' OR ( added_by_user_id IN ('.$userEmployee.') AND added_by_user_type_id = 2 ) ';
				}
				$str .= ' ) ';
				
				$sql = "SELECT $getData,cn.country_name FROM " . DB_PREFIX ."product_quotation pq INNER JOIN " . DB_PREFIX ."country cn ON (pq.shipment_country_id=cn.country_id) WHERE (( added_by_user_id = '".(int)$set_user_id."' AND added_by_user_type_id = '".(int)$set_user_type_id."') $str AND product_quotation_id = '".(int)$quotation_id."' ";
				
			}
		}else{
			$sql = "SELECT $getData FROM " . DB_PREFIX ."product_quotation WHERE product_quotation_id = '".(int)$quotation_id."'";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function CheckQuotationTax($quotation_id)
	{
		$sql = "SELECT tax_type FROM " . DB_PREFIX ."product_quotation_price WHERE product_quotation_id = '".(int)$quotation_id."' ORDER BY product_quotation_price_id ASC LIMIT 1";
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getQuotationMaterial($quotation_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."product_quotation_layer WHERE product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getQuotationNumner($quotation_id){
		$sql = "SELECT quotation_number FROM " . DB_PREFIX ."product_quotation WHERE product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['quotation_number'];
		}else{
			return false;
		}
	}
	
	public function getUsercurrency($user_type_id,$user_id){
		$sql = "SELECT cur.* FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."currency cur ON (ad.country_id=cur.country_id)  WHERE ad.address_type_id = '0' AND ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_type_id."'";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCountryCurrency($country_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."currency WHERE country_id = '".(int)$country_id."' ";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getParentInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name, gres, valve_price FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres, valve_price FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
		}
		
		if($sql){
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function getUserInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name, gres, valve_price  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
		}elseif($user_type_id == 2){
			
			$data = $this->query("SELECT first_name, last_name, user_type_id, user_id FROM `" . DB_PREFIX . "employee` WHERE employee_id = '" .(int)$user_id. "'");
			$parentInfo = array();
			$return = array();
			if($data->num_rows){
				$parentInfo = $this->getParentInfo($data->row['user_type_id'],$data->row['user_id']);
				if($parentInfo){
					$return['company_name'] = $parentInfo['company_name'];
					$return['gres'] = $parentInfo['gres'];
					$return['valve_price'] = $parentInfo['valve_price'];
				}else{
					$return['company_name'] = '';
					$return['gres'] = '';
					$return['valve_price'] = '';
				}
			}else{
				$return['company_name'] = '';
				$return['gres'] = '';
				$return['valve_price'] = '';
			}
			$return['first_name'] = $data->row['first_name'];
			$return['last_name']  = $data->row['last_name'];
			
			return $return;
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name, gres, valve_price FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres, valve_price FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
		}
		
		if($sql){
			$data = $this->query($sql);
			if($data && $data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function getUserCountry($user_tyep_id,$user_id){
		$sql = "SELECT co.country_id, co.country_code, co.currency_id,co.currency_code FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) WHERE ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_tyep_id."' AND ad.address_type_id = '0'";
	//	echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	public function getUserWiseCurrency($user_type_id,$user_id){
		
		if($user_type_id==2){
			
			$parent_data = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX ."employee WHERE employee_id='".$user_id."'");
			if($parent_data->num_rows){
				
				if($parent_data->row['user_type_id']==4){
					$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$parent_data->row['user_id']."' ";				
				}else if($parent_data->row['user_type_id']==5){
					$sql = "SELECT as.product_rate,as.cylinder_rate,as.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."associate as INNER JOIN " . DB_PREFIX ."country cn ON (as.default_curr=cn.country_id) WHERE as.associate_id = '".$parent_data->row['user_id']."' ";	
					
				}	
			}
		}
		
		if($user_type_id==4){
			$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$user_id."' ";		
		}		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	
   
	public function getCurrencyInfo($user_id){
		//echo "SELECT * FROM " . DB_PREFIX ."user WHERE user_id = '".$user_id."' LIMIT 1";
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."user WHERE user_id = '".$user_id."' LIMIT 1");
		//$data = $this->query("SELECT * FROM " . DB_PREFIX ."country WHERE currency_code = '".$currency_code."'");
		//echo "SELECT * FROM " . DB_PREFIX ."country WHERE currency_code = '".(int)$currency_code."'";
		//$data = $this->query("SELECT * FROM " . DB_PREFIX ."currency WHERE currency_id = '".(int)$currency_id."'");
		//$data = $this->query("SELECT *,cylinder_currency_id as currency_id FROM " . DB_PREFIX ."cylinder_currency WHERE cylinder_currency_id = '".(int)$currency_id."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function generateQuotationNumber(){
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME   = 'product_quotation'");
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,8,'0',STR_PAD_LEFT);
		return $strpad;
	}
		
	public function convertPrice($price,$currencyPrice){
		if($currencyPrice > 0){
			return $this->numberFormate(($price / $currencyPrice),"3");
		}else{
			return $price;
		}
	}
	
	
	public function checkQuantity($quotation_quantity,$material=array()){		
		$a = array();		
		foreach($material as $material_id){
			$data = $this->query("SELECT material_name,minimum_quantity FROM " . DB_PREFIX ."product_material WHERE product_material_id='".$material_id."'");			
			if($data->row['minimum_quantity'] > $quotation_quantity){
				$a[] = array(
					'quantity' => $data->row['minimum_quantity'],
					'name' => $data->row['material_name']				
				);
				return $a;
			}
		}
		return $a;
	}
	
	public function getCylinderBasePrice($curreny_code){
	//	echo $curreny_code;die;
		$data = $this->query("SELECT price FROM " . DB_PREFIX ."product_cylinder_base_price WHERE currency_code = '".$curreny_code."'");
	//	echo "SELECT price FROM " . DB_PREFIX ."product_cylinder_base_price WHERE currency_id = '".$curreny_code."'";
		//echo "SELECT price FROM " . DB_PREFIX ."product_cylinder_base_price WHERE currency_code = '".$curreny_code."'";
		if(isset($data->row['price']) && $data->row['price']){
			return $data->row['price'];
		}else{
			return false;
		}
	}
	
	public function getQuantity($type,$quantity_type){
		if($type == 'p'){
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."product_quantity WHERE status = '1' AND is_delete = '0'");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}else{
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."roll_quantity WHERE quantity_type = '".$quantity_type."' AND status = '1' AND is_delete = '0'");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}
	}
	
	public function checkProductGusset($product_id){
		$data = $this->query("SELECT gusset_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['gusset_available'];	
		}else{
			return false;
		}
	}
	
	public function checkProductZipper($product_id){
		$data = $this->query("SELECT zipper_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['zipper_available'];	
		}else{
			return false;
		}
	}
	
	public function getQuotationToken(){
		$token = md5(uniqid(mt_rand(), true));
		$data = $this->query("SELECT product_quotation_id FROM " . DB_PREFIX ." product_quotation WHERE token = '".$token."'");
		if($data->num_rows){
			$token = $this->getQuotationToken();
		}
		return $token;
	}
	
	public function getOnlyQuotationQuantity($quotation_id){
		$data = $this->query("SELECT product_quotation_quantity_id, product_quotation_id, quantity FROM " . DB_PREFIX ."product_quotation_quantity WHERE product_quotation_id = '".(int)$quotation_id."'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;	
		}
	}
	
	public function getQuotationQuantity($quotation_id){
		$data = $this->query("SELECT product_quotation_quantity_id, product_quotation_id,discount, quantity FROM " . DB_PREFIX ."product_quotation_quantity WHERE product_quotation_id = '".(int)$quotation_id."'");
	//	printr($data);die;
		$return = '';
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				$zdata = $this->query("SELECT product_quotation_price_id, product_quotation_id, product_quotation_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt, total_price,total_price_with_excies,total_price_with_tax,tax_type,tax_percentage,excies, gress_price, customer_gress_price FROM " . DB_PREFIX ."product_quotation_price WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' ");	
				
			//	echo "SELECT product_quotation_price_id, product_quotation_id, product_quotation_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt, total_price, gress_price, customer_gress_price FROM " . DB_PREFIX ."product_quotation_price WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' ";
				//printr($zdata);
				if($zdata->num_rows){
					//printr($zdata->rows);
					foreach($zdata->rows as $zipData){
						$txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
						$txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
						if($zipData['transport_type'] == 'sea'){
							$return['sea'][$qunttData['quantity']][] = array(
								'text' 		=> $txt,
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   =>  $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'totalPriceWithExcies'	   =>  $this->numberFormate(($zipData['total_price_with_excies']),"3"),
								'totalPriceWithTax'	   =>  $this->numberFormate(($zipData['total_price_with_tax']),"3"),
								'tax_type'	   => $zipData['tax_type'] ,
								'tax_percentage'	   => $zipData['tax_percentage'] ,
								'excies'	   => $zipData['excies'] ,
								'customerGressPrice' => $zipData['customer_gress_price'],
								'product_quotation_quantity_id'=>$zipData['product_quotation_quantity_id'],
								'discount'=>$qunttData['discount']
							);
						}
						if($zipData['transport_type'] == 'air'){
							$return['air'][$qunttData['quantity']][] = array(
								'text' 		=> $txt,
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   => $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'totalPriceWithExcies'	   =>  $this->numberFormate(($zipData['total_price_with_excies']),"3"),
								'totalPriceWithTax'	   =>  $this->numberFormate(($zipData['total_price_with_tax']),"3"),
								'tax_type'	   => $zipData['tax_type'] ,
								'tax_percentage'	   => $zipData['tax_percentage'] ,
								'excies'	   => $zipData['excies'] ,
								'customerGressPrice' => $zipData['customer_gress_price'],
								'product_quotation_quantity_id'=>$zipData['product_quotation_quantity_id'],
								'discount'=>$qunttData['discount']
							);
						}
						if($zipData['transport_type'] == 'pickup'){
							$return['pickup'][$qunttData['quantity']][] = array(
								'text' 		=> $txt,
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   => $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'totalPriceWithExcies'	   =>  $this->numberFormate(($zipData['total_price_with_excies']),"3"),
								'totalPriceWithTax'	   =>  $this->numberFormate(($zipData['total_price_with_tax']),"3"),
								'tax_type'	   => $zipData['tax_type'] ,
								'tax_percentage'	   => $zipData['tax_percentage'] ,
								'excies'	   => $zipData['excies'] ,
								'customerGressPrice' => $zipData['customer_gress_price'],
								'product_quotation_quantity_id'=>$zipData['product_quotation_quantity_id'],
								'discount'=>$qunttData['discount']
							);
						}
					}
				}
			}
		}
		//printr($return);die;
		return $return;
	}
	
	public function getQuotationQuantityForMail($quotation_id){
		$data = $this->query("SELECT product_quotation_quantity_id, product_quotation_id, quantity,discount FROM " . DB_PREFIX ."product_quotation_quantity WHERE product_quotation_id = '".(int)$quotation_id."'");
		//printr($data);die;
		$return = '';
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				//$zdata = $this->query("SELECT product_quotation_price_id, product_quotation_id, product_quotation_quantity_id,tax_type,tax_percentage,excies,transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt, total_price, gress_price, customer_gress_price FROM " . DB_PREFIX ."product_quotation_price WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' ");	
				$zdata = $this->query("SELECT pqp.product_quotation_price_id, pqp.product_quotation_id, pqp.product_quotation_quantity_id,pqp.tax_type,pqp.tax_percentage,pqp.excies,pqp.transport_type,pqp.zipper_txt, pqp.valve_txt,pqp.spout_txt, pqp.accessorie_txt, pqp.total_price, pqp.gress_price, pqp.customer_gress_price,pq.customer_gress_percentage,pq.gress_percentage FROM " . DB_PREFIX ."product_quotation_price pqp,product_quotation pq WHERE pqp.product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND pqp.product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' AND pqp.product_quotation_id=pq.product_quotation_id");	
				//printr($zdata);
				//die;
				if($zdata->num_rows){
					foreach($zdata->rows as $zipData){
						$txt= ' '.$zipData['zipper_txt'].' '.$zipData['valve_txt'];
						if($zipData['spout_txt'] && $zipData['spout_txt']!='No Spout'){
							$txt.= ' '.$zipData['spout_txt'];
						}elseif($zipData['accessorie_txt'] && $zipData['accessorie_txt']!='No Accessorie'){
							$txt.= ' '.$zipData['accessorie_txt'];
						}
						if($zipData['transport_type'] == 'sea'){
							$return[$qunttData['quantity']][$txt]['sea'] = array(
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   =>  $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'customerGressPrice' => $zipData['customer_gress_price'],
								'gress_price'  => $zipData['gress_price'],
								'gress_percentage' => $zipData['gress_percentage'],
								'customer_gress_percentage' => $zipData['customer_gress_percentage'],
								'discount' =>$qunttData['discount'],
							);
						}
						if($zipData['transport_type'] == 'air'){
							$return[$qunttData['quantity']][$txt]['air'] = array(
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   => $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'customerGressPrice' => $zipData['customer_gress_price'],
								'gress_price'  => $zipData['gress_price'],
								'gress_percentage' => $zipData['gress_percentage'],
								'customer_gress_percentage' => $zipData['customer_gress_percentage'],
								'discount' =>$qunttData['discount'],
							);
						}
						if($zipData['transport_type'] == 'pickup'){
							$return[$qunttData['quantity']][$txt]['pickup'] = array(
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   => $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'customerGressPrice' => $zipData['customer_gress_price'],
								'gress_price'  => $zipData['gress_price'],
								'gress_percentage' => $zipData['gress_percentage'],
								'customer_gress_percentage' => $zipData['customer_gress_percentage'],
								'discount' =>$qunttData['discount'],
							);
						}
					}
				}
			}
		}
		//printr($return);die;
		return $return;
	}
	
	public function allowCurrencyStatus($user_type_id,$user_id){
		$status = false;
		if($user_type_id == 1){
			$data = $this->query("SELECT allow_currency FROM " . DB_PREFIX ."user WHERE user_id = '".(int)$user_id."'");
		}elseif($user_type_id == 2){
			$employee = $this->query("SELECT user_type_id, user_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."'");
			if($employee->num_rows){
				$status = $this->allowCurrencyStatus($employee->row['user_type_id'],$employee->row['user_id']);
			}
		}elseif($user_type_id == 4){
			$data = $this->query("SELECT allow_currency FROM " . DB_PREFIX ."international_branch WHERE international_branch_id = '".(int)$user_id."'");
		}elseif($user_type_id == 5){
			$data = $this->query("SELECT allow_currency FROM " . DB_PREFIX ."associate WHERE associate_id = '".(int)$user_id."'");
		}
		if(isset($data) && $data->num_rows){
			$status = $data->row['allow_currency'];
		}
		return $status;
	}
	
	public function getCurrencys(){
		$data = $this->query("SELECT currency_code, currency_id FROM " . DB_PREFIX ."currency WHERE status = '1' AND is_delete = '0' ");
		if($data->num_rows){
			return  $data->rows;
		}else{
			return false;
		}
	}
	
	public function getNewCurrencys(){
		$data = $this->query("SELECT cn.currency_code,cs.currency_id FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' ");
		if($data->num_rows){
			return  $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCurrencyValue($currency_id){
		$data = $this->query("SELECT price FROM " . DB_PREFIX ."currency_setting WHERE currency_id= '".$currency_id."'");
		return $data->row['price'];
	}
	
	public function getSelCurrencyInfo($currency_id){
	//	echo "SELECT cs.price, c.currency_code FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country c ON(c.country_id=cs.country_code) WHERE cs.currency_id= '".$currency_id."'";
		$data = $this->query("SELECT cs.price, c.currency_code FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country c ON(c.country_id=cs.country_code) WHERE cs.currency_id= '".$currency_id."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getUserCurrencyInfo($user_type_id,$user_id){
	//	printr($_SESSION);
		
		if($user_type_id==1)
		{
			$table = 'user';
			$colName='t.user_id';
		}
		if($user_type_id==2)
		{
			$data = $this->query("SELECT user_id,user_type_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."' ");
			$table = 'employee';
			$colName='t.employee_id';
			$user_id = $data->row['user_id'];
			$user_type_id=$data->row['user_type_id'];
		}
		if($user_type_id==3)
		{
			$table = 'client';
			$colName='t.client_id';
		}
		if($user_type_id==4)
		{
			$table = 'international_branch';
			$colName='t.international_branch_id';
		}
		
		$data = $this->query("SELECT cur.* FROM " . DB_PREFIX .$table." as t LEFT JOIN " . DB_PREFIX ."country cur ON(t.default_curr=cur.country_id)  WHERE ".$colName." = '".(int)$user_id."' ");
	//	echo "SELECT cur.* FROM " . DB_PREFIX .$table." as t LEFT JOIN " . DB_PREFIX ."currency cur ON(t.default_curr=cur.currency_id)  WHERE ".$colName." = '".(int)$user_id."' ";
		//$data = $this->query( "SELECT cur.* FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."currency cur ON(co.currency_code = cur.currency_code) WHERE ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_type_id."' AND ad.address_type_id = '0'");
		//printr($data);die;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getPlusMinusQuantity($quantity,$type){
		if($type == 1){
			$data = $this->query("SELECT plus_minus_quantity FROM " . DB_PREFIX . "roll_quantity WHERE quantity ='".$quantity."'");
		}else{
			$data = $this->query("SELECT plus_minus_quantity FROM " . DB_PREFIX . "product_quantity WHERE quantity ='".$quantity."' ");
		}
		if($data->num_rows){
			return $data->row['plus_minus_quantity'];
		}else{
			return false;
		}
	}
	
	public function getSelectedCurrecyForQuotation($selCurrencyId){
	//	$data = $this->query("SELECT * FROM " . DB_PREFIX . "product_quotation_currency WHERE product_quotation_id ='".$quotation_id."' ORDER BY product_quotation_currency_id ASC LIMIT 0,1");
		$data = $this->query("SELECT cs.currency_id,c.currency_code,cs.price,cs.date_added FROM " . DB_PREFIX . "currency_setting cs ,country as c WHERE cs.currency_id ='".$selCurrencyId."' AND c.country_id=cs.country_code ");
			//$data = $this->query("SELECT * FROM " . DB_PREFIX . "product_quotation_currency WHERE product_quotation_currency_id ='54' AND source = '".$source."' ");
		
		if($data->num_rows){
				$result =array(
							'product_quotation_currency_id' => $data->row['currency_id'],
							'currency_id' => $data->row['currency_id'],
							'currency_code' => $data->row['currency_code'],
							'currency_rate' => $data->row['price'],
							'currency_base_rate' => $data->row['price'],
							'source' => 1,
							'date_added' => $data->row['date_added'],
						);
			return $result;
		}else{
			return false;
		}
	}
	
	public function getQuotationCurrecy($selCurrencyId,$source){
		
		$data = $this->query("SELECT cs.currency_id,c.currency_code,cs.price,cs.date_added FROM " . DB_PREFIX . "currency_setting cs ,country as c WHERE cs.currency_id ='".$selCurrencyId."' AND c.country_id=cs.country_code ");
			//$data = $this->query("SELECT * FROM " . DB_PREFIX . "product_quotation_currency WHERE product_quotation_currency_id ='54' AND source = '".$source."' ");
			
		//printr($result);die;
		if($data->num_rows){
			$result =array(
							'product_quotation_currency_id' => $data->row['currency_id'],
							'currency_id' => $data->row['currency_id'],
							'currency_code' => $data->row['currency_code'],
							'currency_rate' => $data->row['price'],
							'currency_base_rate' => $data->row['price'],
							'source' => 1,
							'date_added' => $data->row['date_added'],
						);
			return $result;
		}else{
			return false;
		}
	}
	
	public function setQuotationCurrency($quotation_id,$currency_code,$currencyRate,$source){
		//$currencyInfo = $this->getCurrencyInfo(decode($ecurrencyId));
		//printr($currencyInfo);die;
		//if($currencyInfo){
			/*if($currencyRate == 'NO'){
				$currencyRate = $currencyInfo['price'];
			}*/
		//	echo "INSERT INTO " . DB_PREFIX . "product_quotation_currency SET product_quotation_id = '".$quotation_id."', currency_id = '', currency_code = '".$currency_code."', currency_rate = '".(float)$currencyRate."', currency_base_rate = '', source = '".$source."', date_added = NOW()";die;
			$this->query("INSERT INTO " . DB_PREFIX . "product_quotation_currency SET product_quotation_id = '".$quotation_id."', currency_id = '', currency_code = '".$currency_code."', currency_rate = '".(float)$currencyRate."', currency_base_rate = '', source = '".$source."', date_added = NOW()");
			return $this->getLastId();
		/*}else{
			return false;
		}*/
	}
	
	public function getEmailHistories($quotation_id){
		//echo "SELECT qc.currency_code, qc.date_added, qc.source, qc.currency_rate, qe.to_email FROM " . DB_PREFIX . "product_quotation_email_history qe RIGHT JOIN product_quotation_currency qc ON(qe.product_quotation_currency_id = qc.product_quotation_currency_id) WHERE qc.product_quotation_id ='".$quotation_id."' ORDER BY qc.date_added DESC ";
		$data = $this->query("SELECT qc.currency_code, qe.date_added, qc.source, qc.currency_rate, qe.to_email FROM " . DB_PREFIX . "product_quotation_email_history qe RIGHT JOIN product_quotation_currency qc ON(qe.product_quotation_currency_id = qc.product_quotation_currency_id) WHERE qe.product_quotation_id ='".$quotation_id."' ORDER BY qc.date_added DESC ");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCountry($country_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."country WHERE country_id = '".(int)$country_id."' ");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getSpout($spout_id){
		$data = $this->query("SELECT spout_name, price, product_spout_id FROM " . DB_PREFIX . "product_spout WHERE product_spout_id = '".(int)$spout_id."' ");
		$return  = array();
		if($data->num_rows){
			$return['product_spout_id'] = $data->row['product_spout_id'];
			$return['spout_name'] = $data->row['spout_name'];
			$return['price'] = $data->row['price'];
		}
		return $return;
	}
	
	public function getAccessorie($accessorie_id){
		$data = $this->query("SELECT product_accessorie_name, price, product_accessorie_id FROM " . DB_PREFIX . "product_accessorie WHERE 	product_accessorie_id = '".(int)$accessorie_id."' ");
		$return  = array();
		if($data->num_rows){
			$return['product_accessorie_id'] = $data->row['product_accessorie_id'];
			$return['accessorie_name'] = $data->row['product_accessorie_name'];
			$return['price'] = $data->row['price'];
		}
		return $return;
	}
	
	public function getMaterialQuantity($material_id){
		$data = $this->query("SELECT pq.quantity FROM " . DB_PREFIX . "product_material_quantity pmq INNER JOIN " . DB_PREFIX . "product_quantity pq ON(pmq.product_quantity_id=pq.product_quantity_id) WHERE pmq.product_material_id = '".(int)$material_id."' ORDER BY pq.quantity ASC ");	
		if($data->num_rows){
			$return = '';
			foreach($data->rows as $key=>$value){
				$return[] = $value['quantity'];
			}
			return $return;
		}else{
			return false;
		}
	}
	
	public function aasort ($array) {
		$newArray = array();
		$hv = array();
	   foreach ($array as $va) {
			$hv[] = max($va);
			$newArray = $this->makeOneArray($va,$newArray);
		}
		asort($hv);
		if(count($hv) > 1){
			array_pop($hv);
		}
		rsort($newArray);
		$highest = $this->getHighestValue($newArray,$hv);
		$totalCount = count($array);
		$common = $this->getCommonValue($newArray,$totalCount);
		$final = array_merge($highest,$common);
		//printr($final);
		return $final;
	}
	
	public function makeOneArray($array,$newArray){
		foreach ($array as $ii => $va) {
			$newArray[] = $va;
		}
		return $newArray;
	}
	
	public function getCommonValue($array,$totalCount){
		$common = array();
		foreach($array as $val){
			$tmp = array_count_values($array);
			$cnt = $tmp[$val];
			if($cnt == $totalCount){
				if(!in_array($val,$common)){
					$common[] = $val;
				}
			}
		}
		return $common;
	}
	
	public function getHighestValue($array,$hv){
		$highest = array();
		$max = max($hv);
		foreach($array as $val){
			if(!in_array($val,$hv) && $val > $max){
				$highest[] = $val;
			}
		}
		return $highest;
	}
	
	public function gettermsandconditions($user_id,$user_type_id){
		
		if($user_type_id == '4')
		{
		$sql = "SELECT ts.termsandconditions,ts.user_id,ts.user_type_id FROM termsandconditions ts WHERE ts.user_id = '".$user_id."' AND ts.user_type_id = '4'  AND ts.is_delete = '0' LIMIT 1";
		}
		else
		{
		//	$sql = "SELECT ts.termsandconditions,ts.user_id,ts.user_type_id,e.user_id FROM termsandconditions ts,employee e WHERE ts.user_id = '".$user_id."' AND ts.user_type_id = '4'  AND ts.is_delete = '0' LIMIT 1";
		$sql = "SELECT ts.termsandconditions,ts.user_id,ts.user_type_id,e.user_id FROM termsandconditions ts,employee e WHERE e.employee_id ='".$user_id."' AND ts.user_id = e.user_id AND ts.user_type_id = '4' AND ts.is_delete = '0' LIMIT 1";
		//echo $sql;
		//die;
			
		}
		//echo $sql;
		//die;
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	
	}
	public function getexciestaxtype($quotation_id)
	{
		$sql = "SELECT zipper_txt,valve_txt,tax_type,tax_percentage,excies FROM product_quotation_price WHERE product_quotation_id = '".$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
			
	}
	public function getQuotationGresspriceForMail($quotation_id)
	{
		$sql  = "SELECT customer_gress_percentage,gress_percentage FROM product_quotation WHERE product_quotation_id = '".$quotation_id."'"; 
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	}
	public function getViewToolprice($product_id)
	{
		$sql  = "SELECT pe.*,p.product_name FROM product_extra_tool_price as pe,product as p WHERE pe.product_id = p.product_id AND 
		pe.product_id = '".$product_id."'"; 
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	
	public function getProductSize($product_id)
	{
		$sql  = "SELECT * FROM size_master WHERE product_id = '".$product_id."'"; 
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
}
?>