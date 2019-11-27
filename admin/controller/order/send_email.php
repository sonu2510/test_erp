<?php
include("mode_setting.php");
//Start : send emial
$edit = '';

if(isset($_GET['quotation_id']) && !empty($_GET['quotation_id'])){
	if($obj_general->hasPermission('edit',$menuId)){
		$quotation_id = base64_decode($_GET['quotation_id']);
		$getData = ' product_quotation_id, added_by_user_id, added_by_user_type_id, customer_name, quotation_number, quotation_type, product_name, printing_option, printing_effect, height, width, gusset, quantity_type, quantity, layer, option_name, transport_name, price_per_pouch, added_by_country_id, currency, currency_price, date_added, price_per_kg, price_per_meter, price_per_piece, total_quantity_in_kg, status ';
		$data = $obj_quotation->getQuotationSelectedData($quotation_id,$getData);
		//printr($data);die;
		$addedByInfo = $obj_quotation->getUser($data['added_by_user_id'],$data['added_by_user_type_id']);
		//printr($addedByInfo);die;
		$setHtml = '';
		
		$setHtml .= '<table align="left" style="width: 48%;border:1px solid #8d8e90" cellspacing="0" class="chunk">';
			$setHtml .= '<tr>';
				$setHtml .= '<td style="padding: 5px;background:#8d8e90;color:#FFFFFF">Quotation From</td>';
			$setHtml .= '</tr>';
			$setHtml .= '<tr>';
			  	$setHtml .= '<td style="padding-left: 5px;"><p>'.$addedByInfo['name'].'</p>';
			  	$setHtml .= '</td>';
			$setHtml .= '</tr>';
			$setHtml .= '<tr>';
				$setHtml .= '<td style="padding: 5px;background:#8d8e90;color:#FFFFFF">Quotation To</td>';
			$setHtml .= '</tr>';
			$setHtml .= '<tr>';
			  	$setHtml .= '<td style="padding-left: 5px;"><p>'.ucwords($data['customer_name']).'</p>';
			  	$setHtml .= '</td>';
			$setHtml .= '</tr>';
	  	$setHtml .= '</table>';
		
		$setHtml .= '<table align="left" style="width: 1%;" class="chunk">';
			$setHtml .= '<tr>';
				$setHtml .= '<td></td>';
			$setHtml .= '</tr>';
	  	$setHtml .= '</table>';
	  
		$setHtml .= '<table align="left" style="width: 48%;border:1px solid #8d8e90" cellspacing="0" class="chunk">';
			$setHtml .= '<tr>';
				$setHtml .= '<td style="width: 24%;padding: 5px;background:#8d8e90;color:#FFFFFF;border-bottom:1px solid #ffffff">Quotation Number</td>';
				$setHtml .= '<td style="width: 24%;padding: 5px;border-bottom:1px solid #8d8e90">'.$data['quotation_number'].'</td>';
			$setHtml .= '</tr>';
			$setHtml .= '<tr>';
				$setHtml .= '<td style="width: 24%;padding: 5px;background:#8d8e90;color:#FFFFFF">Quotation Date</td>';
				$setHtml .= '<td style="width: 24%;padding: 5px;border-bottom:1px solid #8d8e90">'.date('F d, Y',strtotime($data['date_added'])).'</td>';
			$setHtml .= '</tr>';
	  	$setHtml .= '</table>';
		
		$setHtml .= '<table align="left" style="width: 100%;border:1px solid #8d8e90;margin-top:10px;" class="chunk" cellspacing="0">';
			$setHtml .= '<tr style="padding: 5px;background:#8d8e90;color:#FFFFFF">';
				$setHtml .= '<td style="padding: 5px;width:65%;border-right:1px solid #FFFFFF;">Description</td>';
				$setHtml .= '<td style="padding: 5px;width:10%;border-right:1px solid #FFFFFF;">Quantity</td>';
				$setHtml .= '<td style="padding: 5px;width:10%;border-right:1px solid #FFFFFF;">Unit Price</td>';
				$setHtml .= '<td style="padding: 5px;width:15%;">Total</td>';
			$setHtml .= '</tr>';
			$setHtml .= '<tr style="border-bottom:1px solid #8d8e90">';
				$setHtml .= '<td style="padding: 5px;border-right:1px solid #8d8e90;">';
					$setHtml .= '<table align="left" style="width: 100%;" cellspacing="0" class="chunk">';
							$setHtml .= '<tr>';
								$setHtml .= '<td style="padding:2px;" colspan="3"><b>'.$data['product_name'].'</b></td>';
							$setHtml .= '</tr>';
							$setHtml .= '<tr>';
								$setHtml .= '<td colspan="3"><b>'.$data['layer'].' Layer </b>';
									if($data['quotation_type'] == 0){
										$setHtml .= ' / '.$data['option_name'];
									}
								$setHtml .= '</td>';
							$setHtml .= '</tr>';
							$setHtml .= '<tr>';
								$setHtml .= '<td colspan="3"><b>Width :</b> '.$data['width'].' mm</td>';
							$setHtml .= '</tr>';
							$setHtml .= '<tr>';
								$setHtml .= '<td colspan="3"><b>';
									if($data['quotation_type'] == 1){
										$setHtml .= 'Repeat Length : ';
									}else{
										$setHtml .= 'Height :';
									}
								$setHtml .= '</b> '.$data['height'].' mm</td>';
							$setHtml .= '</tr>';
							if($data['quotation_type'] == 0){
								$setHtml .= '<tr>';
									$setHtml .= '<td colspan="3"><b>Gusset :</b> '.$data['gusset'].' mm</td>';
								$setHtml .= '</tr>';
							}
							
							$materialData = $obj_quotation->getQuotationMaterial($data['product_quotation_id']);
							if(isset($materialData) && !empty($materialData)){
								$setHtml .= '<tr>';
									$setHtml .= '<td style="border:1px solid #8d8e90;"></td>';
									$setHtml .= '<td style="border:1px solid #8d8e90;padding-left:5px;"><b>Material</b></td>';
									$setHtml .= '<td style="border:1px solid #8d8e90;padding-left:5px;"><b>Thickness (microns)</b></td>';
								$setHtml .= '</tr>';
								for($gi=0;$gi<count($materialData);$gi++){
									$setHtml .= '<tr>';
										$setHtml .= '<td style="border:1px solid #8d8e90;padding-left:5px;">'.($gi+1).' Layer</td>';
										$setHtml .= '<td style="border:1px solid #8d8e90;padding-left:5px;">'.$materialData[$gi]['material_name'].'</td>';
										$setHtml .= '<td style="border:1px solid #8d8e90;padding-left:5px;">'.$materialData[$gi]['material_thickness'].'</td>';
									$setHtml .= '</tr>';
								}
							}
					$setHtml .= '</table>';
				$setHtml .= '</td>';
				$setHtml .= '<td style="padding: 5px;border-right:1px solid #8d8e90">';
					if($data['quotation_type'] == 1){
						$setHtml .= $data['quantity'].' '.ucwords($data['quantity_type']);
					}else{
						$setHtml .= $data['quantity'].' nos.';
					}
				$setHtml .= '</td>';
				$setHtml .= '<td style="padding: 5px;border-right:1px solid #8d8e90">';
					if($data['quotation_type'] == 0){
						$clculatePrice = $obj_quotation->convertPrice($data['price_per_pouch'],$data['currency_price']);
						$setHtml .= $data['currency'].' '.$clculatePrice;
					}else{
						$clculatePrice = $obj_quotation->convertPrice($data['price_per_'.$data['quantity_type']],$data['currency_price']);
						$setHtml .= $data['currency'].' '.$clculatePrice;
					}
				$setHtml .= '</td>';
				$setHtml .= '<td style="padding: 5px;">';
					if($data['quotation_type'] == 1){
						$clculatePrice = $obj_quotation->convertPrice($data["total_price_".$data['quantity_type']],$data['currency_price']);
						
						$setHtml .= $data['currency'].' '.$clculatePrice;
					}else{
						$clculatePrice = $obj_quotation->convertPrice(($data['price_per_pouch'] * $data['quantity']),$data['currency_price']);
						$setHtml .= $data['currency'].' '.$clculatePrice;
					}
				$setHtml .= '</td>';
			$setHtml .= '</tr>';
			$setHtml .= '<tr style="padding: 5px;background:#8d8e90;color:#FFFFFF">';
				$setHtml .= '<td colspan="2" style="padding: 5px;text-align:right;border-right:1px solid #FFFFFF;">Total</td>';
				$setHtml .= '<td colspan="2" style="padding: 5px;">';
					if($data['quotation_type'] == 1){
						$clculatePrice = $obj_quotation->convertPrice($data["total_price_".$data['quantity_type']],$data['currency_price']);
						
						$setHtml .= $data['currency'].' '.$clculatePrice;
					}else{
						$clculatePrice = $obj_quotation->convertPrice(($data['price_per_pouch'] * $data['quantity']),$data['currency_price']);
						$setHtml .= $data['currency'].' '.$clculatePrice;
					}
				$setHtml .= '</td>';
			$setHtml .= '</tr>';
	  	$setHtml .= '</table>';
	  
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(1); 
		
		//$sendTo = $addedByInfo['email'];
		$sendTo = 'pradip.rathod111@gmail.com';
		
		$tag_val = array("{{userName}}"=>"Pradip Rathod","{{productDetail}}"=>$setHtml);
		$message = $obj_email->getEmailTemplateContent(str_replace('\r\n',' ',$rws_email_template['discription']),$tag_val,$rws_email_template['subject']); 
		//echo $message;die;  
		send_email($sendTo,$sendTo,$rws_email_template['subject'].' #'.$data['quotation_number'],$message,'');
		send_email(ADMIN_EMAIL,$sendTo,$rws_email_template['subject'].' #'.$data['quotation_number'],$message,'');
		$obj_session->data['success'] = 'Success : Email send !';
		page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($data['product_quotation_id']), '',1));
	}
}else{
	page_redirect($obj_general->link($rout, '', '',1));
}
//End : send emial