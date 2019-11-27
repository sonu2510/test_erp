<?php
include("mode_setting.php");
$fun = $_GET['fun'];
if($fun == 'checkInvoiceNo')
{
	$invoice_no = $_POST['checknum'];
	$no = $obj_invoice->$fun($invoice_no);
	$invoiceno = $no['invoice_no'];
	echo $invoiceno;
}

if($fun == 'removeInvoice'){

	$data=$obj_invoice->removeInvoice($_POST['invoice_product_id'],$_POST['invoice_id']);
	echo $data;

}
if($fun == 'csvInvoice'){
parse_str($_POST['formData'], $post);
	$csv=$obj_invoice->invoiceArrayForCSV($post['post']);
	$input_array = Array(
    Array('*ContactName',
        'EmailAddress',
        'POAddressLine1',
		'POAddressLine2',
		'POAddressLine3',
		'POAddressLine4',
		'POCity',
		'PORegion',
		'POPostalCode',
		'POCountry',
		'*InvoiceNumber',
		'Reference',
		'*InvoiceDate',
		'*DueDate',
		'PlannedDate',
		'Total',
		'TaxTotal',
		'InvoiceAmountPaid',
		'InvoiceAmountDue',
		'InventoryItemCode',
		'*Description',
		'*Quantity',
		'*UnitAmount',
		'Discount',
		'LineAmout',
		'*AccountCode',
		'*TaxType',
		'TaxAmount',
		'TrackingName1',
		'TrackingOption1',
		'TrackingName2',
		'TrackingOption2',
		'Currency',
		'Type',
		'Sent',
		'Status'
	)
);
$delimiter= ',';
$output_file_name='report.csv';
    /** open raw memory as file, no need for temp files, be careful not to run out of memory thought */
    $f = fopen('php://memory', 'w');
    /** loop through array  */
	$d='';
    foreach ($input_array as $line) {
       $d.= fputcsv($f, $line, $delimiter);
    }
	  foreach ($csv as $line) {
        $d.=fputcsv($f, $line, $delimiter);
    }
   	fseek($f, 0);
    fpassthru($f);
}
if($fun == 'checkProductZipper'){
	
	$zipper_available = $obj_invoice->checkProductZipper($_POST['product_id']);
	$html ='<div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-9">';
	                        $zippers = $obj_invoice->getActiveProductZippers();
							$ziptxt = '';
                            foreach($zippers as $zipper){
							   $ziptxt .= '<div   style="float:left;width: 200px;">';
							   		$ziptxt .= '<label  style="font-weight: normal;">';
									if($zipper_available==0 )
									{ 
										
										$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'"';
										if($zipper['product_zipper_id']==2) { 
										$ziptxt .= ' checked="checked" ';
										 }else{ $ziptxt .= 'disabled="disabled" ';
										 }
										 $ziptxt .= 'onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										
									}
									else
									{
										$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'"';
										if( $zipper['product_zipper_id']==2)
										{
										  $ziptxt .= 'checked="checked"';
										   
										}
										$ziptxt .= 'onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										
									}
									$ziptxt .= '</label>';
								$ziptxt .= '</div>';
                            }
							$html.= $ziptxt;                            
                        $html.='</div></div>';
	echo json_encode($html);
}
if($fun == 'addInvoice'){
	$html = '';
	parse_str($_POST['formData'], $post);
	//printr($post);die;
	$LastId = $obj_invoice->addInvoice($post);
	$invoice = $obj_invoice->getInvoiceData($LastId);
	$invoice_detail = $obj_invoice->getInvoiceProduct($LastId); 
	$html .='<input type="hidden" id="invoice_id" name="invoice_id" value="'.$LastId.'"/>';
	$html .= '<h4><i class="fa fa-plus-circle"></i> Added Invoice</h4>';
         $html .= '<div class="line m-t-large" style="margin-top:-4px;"></div><br/>';
		
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th>Size</th>';			
			$html .= '<th>Color : Quantity / Net Weight</th>';
			$html .= '<th>Rate</th>';
			$html .= '<th>Option</th>';
			$html .= '<th>Transport</th>';			
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  
			foreach($invoice_detail as $detail) {
				  $getProductSpout = $obj_invoice->getSpout(decode($detail['spout']));
				  $getProductZipper = $obj_invoice->getZipper(decode($detail['zipper']));
				  $getProductAccessorie = $obj_invoice->getAccessorie(decode($detail['accessorie']));

				  $html .= '<tr id="invoice_product_id_'.$detail['invoice_product_id'].'">';
				  $product = $obj_invoice->getActiveProductName($detail['product_id']);
				  $html .= '<td><b>'.$product['product_name'].'</b></td>';
				  $color = $obj_invoice->getColorDetails($LastId , $detail['invoice_product_id']);
				$html .= '<td width="75">';
					foreach($color as $size)
					{	
						$html .= $size['size'].'&nbsp;'.$size['measurement'].'<br />';
					}
					$html .= '</td>';
				 
				  $html .= '<td>';
				  foreach($color as $colors) {
					 if($colors['color']=='Custom')
						$html .= 'Custom : '.$colors['qty'].'<br>';	  
					else
				  		$html .= $colors['color'].' : '.$colors['qty'].'<br>';}
				  $measure = $obj_invoice->getMeasurementName($detail['measurement_two']);
				  $html .= '<b>Net Weight :</b>'.$detail['net_weight'].'&nbsp;'.$measure['measurement'];
				  $html .= '</td>';
				 
				  $html .= '</td>';
				  $html .= '<td>';
				  foreach($color as $rate_val) {
					  $html .= $rate_val['rate'].'<br>';
				  }
				  $html .= '</td>';				
				   $html .= '<td>'.ucwords($getProductSpout['spout_name']).' '.$detail['valve'].'<br>'.$getProductZipper['zipper_name'].' '.ucwords($getProductAccessorie['product_accessorie_name']).'</td>';
				  
				  		  
				  $html .= '<td>By '.ucwords(decode($invoice['transportation'])).'</td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice('.$detail['invoice_product_id'].')"><i class="fa fa-trash-o"></i></a>';
				  if($post['edit'] != '')
				  {
				   $html .= '<a href="'.$obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice['invoice_id']).'&invoice_product_id='.encode($detail['invoice_product_id']).'','',1).'"  name="btn_edit" class="btn btn-info btn-xs btn_edit" id="btn_edit">Edit</a></td>';
				  }
				  $html .= '</tr>';
				  
				  $html .= '<div class="modal fade" id="alertbox_'.$detail['invoice_product_id'].'">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">Title</h4>
                                </div>
                                <div class="modal-body">
                                    <p id="setmsg">Message</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="popbtncan" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" name="popbtnok" id="popbtnok_'.$detail['invoice_product_id'].'" class="btn btn-primary">Ok</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>';
				  
			
		  }
		  $html .= '</tbody></table>';
		  echo $html;
}
if($fun == 'updateInvoiceRecord') {

	parse_str($_POST['formData'], $postdata);
	$invoiceUpdate = $obj_invoice->updateInvoiceRecord($postdata);

	if($postdata['generate_status']=='0')
			$obj_template->sendWarningMailForGenInv($postdata['country_id'],$postdata['admin_email'],$con='1');
	
	
	echo $invoiceUpdate;
}
if($fun == 'updateInvoiceProduct') {
	$html = '';
	parse_str($_POST['formData'], $postdata);
	//printr($postdata);
	//die;
	$invoice_update = $obj_invoice->updateInvoiceProduct($postdata);
	$invoice = $obj_invoice->getInvoiceData($postdata['invoice_id']);
	$invoice_detail = $obj_invoice->getInvoiceProduct($invoice['invoice_id']); 
	$html .= '<div class="line m-t-large" style="margin-top:-4px;"></div><br/>';
		
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th>Size</th>';			
			$html .= '<th>Color : Quantity / Net Weight</th>';
			$html .= '<th>Rate</th>';
			$html .= '<th>Option</th>';
			$html .= '<th>Transport</th>';			
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  
		  foreach($invoice_detail as $detail) { 
		  		 $getProductSpout = $obj_invoice->getSpout(decode($detail['spout']));
				 $getProductZipper = $obj_invoice->getZipper(decode($detail['zipper']));
				 $getProductAccessorie = $obj_invoice->getAccessorie(decode($detail['accessorie']));

				  $html .= '<tr id="invoice_product_id_'.$detail['invoice_product_id'].'">';
				  $product = $obj_invoice->getActiveProductName($detail['product_id']);
				  $html .= '<td><b>'.$product['product_name'].'</b></td>';
				  $color = $obj_invoice->getColorDetails($detail['invoice_id'],$detail['invoice_product_id']);
				
					$html .= '<td>';
					foreach($color as $size)
					{	
						$html .= $size['size'].' &nbsp;'.$size['measurement'].'<br>';
					}
					$html .= '</td>';
					
				  $html .= '<td>';
				  foreach($color as $colors) {
					   if($colors['color'] == 'Custom')
					   	$html .= 'Custom : '.$colors['color_text'].'  '.$colors['qty'].'<br>';	  
						else
				  		$html .= $colors['color'].' : '.$colors['qty'].'<br>';
				  }$measure = $obj_invoice->getMeasurementName($detail['measurement_two']);
				  $html .= '<b>Net Weight :</b>'.$detail['net_weight'].'&nbsp;'.$measure['measurement'];	
				  $html .= '</td>';
				 
				  $html .= '</td>';
				  $html .= '<td>';
				  foreach($color as $rate_val) {
					  $html .= $rate_val['rate'].'<br>';
				  }
				  $html .= '</td>';
				  $html .= '<td>'.ucwords($getProductSpout['spout_name']).' '.$detail['valve'].'<br>'.$getProductZipper['zipper_name'].' '.ucwords($getProductAccessorie['product_accessorie_name']).'</td>';
				  
				  		  
				  $html .= '<td>By '.ucwords(decode($invoice['transportation'])).'</td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice('.$detail['invoice_product_id'].')"><i class="fa fa-trash-o"></i></a>
				  <a href="'.$obj_general->link($rout, 'mod=add&invoice_no='.encode($postdata['invoice_id']).'&invoice_product_id='.encode($detail['invoice_product_id']).'','',1).'"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
				  </td>';
				  $html .= '</tr>';
		  }
			  $html .= '</tbody></table>';
		echo $html;
		 
}
if($fun == 'update_boxno')
{
	//printr($_POST['postArray']);	die;
	$data = array('box_no'=>$_POST['postArray']['box_no'],
	'gen_unique_id'=>$_POST['postArray']['gen_unique_id'],
	);
	$result1 = $obj_invoice->updateBoxno($data);
	echo $result1;
	//echo "hijjkkk";
}
if($fun == 'update_pallet_no')
{
	$data = array('pallet_no'=>$_POST['postArray']['pallet_no'],
	'pallet_id'=>$_POST['postArray']['pallet_id'],
	);
	$result1 = $obj_invoice->updatePalletNo($data);
	echo $result1;
}
if($fun == 'savelabeldetail')
{
	parse_str($_POST['formData'], $postdata);
	$obj_invoice->$fun($postdata);
}
if($fun == 'savePallet')
{
	parse_str($_POST['formData'], $postdata);
	$obj_invoice->$fun($postdata);
}
if($fun == 'savePalletdetail')
{
	parse_str($_POST['formData'], $postdata);
	$obj_invoice->$fun($postdata);
}
if($fun == 'deletePallet')
{
printr($_POST);
	//parse_str($_POST['formData'], $postdata);
	$obj_invoice->$fun($_POST);
}
if($fun == 'getComb')
{
	$com='<select name="detail[]" id="detail" multiple="multiple">';
                 
						$parent_id=0;
						$str=' AND (pallet_id=0 OR pallet_id="'.$_POST['pallet_id'].'")';
						$colordetails=$obj_invoice->colordetails($_POST['invoice_id'],$parent_id,$str);
					//	printr($colordetails);
						if(isset($colordetails) && !empty($colordetails))
						{
							foreach($colordetails as $color)
							{
								//printr($color);
								$zipper=$obj_invoice->getZipper(decode($color['zipper']));
								$zipper_name=$zipper['zipper_name'];
								$valve=$color['valve'];
								$childBox=$obj_invoice->colordetails($_POST['invoice_id'],$color['in_gen_invoice_id']);
								$c_name=$color['color'];
								if($color['dimension']!='')
									$size=$color['dimension'];
								else
									$size=$color['size'].' '.$color['measurement'];
								if(isset($childBox) && !empty($childBox))
								{
									foreach($childBox as $ch)
									{
										$c_name.=' + '.$ch['color'];
										if($ch['dimension']!='')
											$size.=' + '.$ch['dimension'];
										else
											$size.=' + '.$ch['size'].' '.$ch['measurement'];
									}
								}
								$description='Box No : '.$color['box_no'].' ('.$size.' - '.$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$color['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ) ' ;	
								if($color['pallet_id']==$_POST['pallet_id'])
									$com.='<option value="'.$color['in_gen_invoice_id'].'"  selected="selected">'.$description.'</option>';	
								else
									$com.='<option value="'.$color['in_gen_invoice_id'].'">'.$description.'</option>';			
							}
						}
                          $com.="</select>  eg:-{ Box No (Size - color Name ( Product Name with Zipper With Valve )) } <script>
    $(function() {
        $('#detail').change(function() {
            console.log($(this).val());
        }).multipleSelect({
            width: '100%',
        });
    });
</script>";     
echo $com;  
}
if($fun == 'deleteBox')
{
	$in_gen_invoice_id=$_POST['in_gen_invoice_id'];
	$result1 =$obj_invoice->$fun($in_gen_invoice_id);
	echo $result1;
}
if($fun == 'updateInvoice')
{	
	$invoice_no = $_POST['invoice_no'];
	$status = $_POST['status_value'];
	$obj_invoice->$fun($invoice_no,$status);
	
}
if($fun == 'product_code'){
	//echo "hi";
	$product_code = $_POST['product_code'];
	//echo $product_code;
	$result = $obj_invoice->getProductCd($product_code);
	echo json_encode($result);
}
/*if($fun == 'getCustomDataold') {
	$custom_order_number = $_POST['custom_order_number'];
	
	//$obj_session->data['quotation_no'] = $_POST['quotation_no'];
	if($custom_order_number != '')
	{
		$multi_custom_order_id = $obj_invoice->getCustomId($custom_order_number);
		//printr($multi_custom_order_id);	die;	
		if($multi_custom_order_id)
		{
			$getData = " custom_order_id,mco.added_by_user_id,mco.added_by_user_type_id, customer_name, shipment_country_id,mco.multi_custom_order_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,mco.status,mco.custom_order_status,discount";
		$data = $obj_invoice->getCustomOrder($multi_custom_order_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
			//printr($data);die;
		//printr();	
			foreach($data as $dat)
			 {
						  
				 $multi_custom_order_id=$dat['multi_custom_order_id'];
				 $custom_order_id=$dat['custom_order_id'];
				 $result = $obj_invoice->getCustomOrderQuantity($dat['custom_order_id']);
				 //printr($dat['product_quotation_id']);
				 if($result!='')
				 	$quantityData[] =$result;
			   	  } 
				  
				  if(!empty($quantityData))
				  {
						foreach($quantityData as $k=>$qty_data)
						{
							//printr($quantityData);
							foreach($qty_data as $tag=>$qty)
							{
								foreach($qty as $q=>$arr)
								{
									$new_data[$tag][$q][]=$arr[0];
								}
							}	
					}
					$result='<input type="hidden" id="multi_custom_order_id" name="multi_custom_order_id" value='.$multi_custom_order_id.'/>';
					foreach($new_data as $k=>$qty_data)
					{
						$result.='<div class="form-group">
									<label class="col-lg-3 control-label" style="width: 10%;">Price (By '.$k.')</label> 
									<div class="col-lg-9" style="width:89%">
										<section class="panel">
										  <div class="table-responsive">
											<table class="table table-striped b-t text-small">
											  <thead>
												  <tr>
													<th width="20"></th>
													<th>Product Name</th>
													<th>Quntity</th>';
													if($dat['custom_order_type'] != 1){ 
														$result.='<th>Option(Printing Effect )</th>';
													 } 
													$result.='<th>Dimension (Make Pouch)</th>
													<th>Layer:Material:Thickness</th>';
													if($dat['custom_order_status'] == 0){
														 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
														{ 				
															 if($dat['currency']=='INR')
															 {
																$result.='<th>Discount</th>';
															 }
														} 
													}
													$result.='<th>Price / pouch</th>
													<th>Total</th>';
													if($k=='pickup') {
														if($data[0]['shipment_country_id']==111){ 
															$result.='<th> Price / pouch  With Tax </th>
																	 <th>Total Price With Tax </th>';
													 } }
													 $result.='<th>Cylender Price</th>
																<th>Tool Price</th>';
													if($data[0]['status'] != 1){
													 		$result.='<th>Action</th>';
													 }
													 $result.='
												  </tr>
											  </thead>
											  <tbody>';
											  	$i=1;
                                                foreach($qty_data as $skey=>$sdata)
												{
													$result.='<tr>';
													foreach($sdata as $soption)
													{
														//printr($soption);
														$result.='<tr id="custom-row-'.$soption['custom_order_price_id'].'">
																		<td><input type="checkbox" name="custom_order_id[]" value="'.$multi_custom_order_id.'"/>										
																		</td>
																		<th>'.$soption['product_name'].'</th>
                                                            			<th>'.$skey.'</th>
																		<td>'.ucwords($soption['text']).' ('.$soption['printing_effect'].')'.'</td>
																		<td>'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.$soption['gusset']; 
																			if($data[0]['product_name']!=10)
																			{
																				if($soption['volume']>0) 
																					$result.=' ('.$soption['volume'].')';
																			}
																			else
																			{
																				 $result.=' (Custom) ('.$soption['make'].')</td>';
																			}
																		$result.='<td>';
                                                             				for($gi=0;$gi<count($soption['materialData']);$gi++)
																			{
											 										 $result.='<b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
																			}
                                                                 		$result.='</td>';
																		if($dat['custom_order_status'] == 0)
																		{
																	 		if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																			{ 
																				if($dat['currency']=='INR')
																		$result.='<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['custom_order_quantity_id'].'" class="form-control" style="width: 100px;" ></td>';
																			 }
																		 }
																		$result.=' <td>';
																		if($soption['discount'] && $soption['discount'] >0.000) 
																		{
                                                               				 $result.=' <b>Total : </b>';
																			 $pretot= $obj_invoice->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
																			 $result.=$pretot.'"<br />
                                                                					<b>Discount ("'.$soption['discount'].'" %) : </b>';
																 					$predis = $pretot*$soption['discount']/100; 
																					$result.=$obj_invoice->numberFormate($predis,"3").'<br />
                                                               						 <b>Final Total : </b>
																					'.$dat['currency'].' '.$obj_invoice->numberFormate(($pretot-$predis),"3");
																		}
																		else
																		{ 
																			$result.=$dat['currency'].' '.$obj_invoice->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
																		}
                                                              			$result.='</td>
																		<td>';
																			if($soption['discount'] && $soption['discount'] >0.000) 
																			{
                                                               					$result.='<b>Total : </b>';
																					$tot= $obj_invoice->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3"); 							                                                                                        $result.=$tot.'"<br />
                                                                							<b>Discount ('.$soption['discount'].' %) : </b>';
																								$dis = $tot*$soption['discount']/100; 
																								$result.=$obj_invoice->numberFormate($dis,"3").'<br />
                                                                										<b>Final Total : </b>
																											'.$dat['currency'].' '.($tot-$dis);
																			} 
																			else
																			{
																				$result.=$dat['currency'].' '.$obj_invoice->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");
																			}
                                                                 		$result.='</td>';
																		if($k=='pickup') {
																	  		if($data[0]['shipment_country_id']==111)
																			{ 
																				$result.='<td>'.$dat['currency'].' '.$obj_invoice->numberFormate((($soption['totalPriceWithTax'] / $skey) / $dat['currency_price'] ),"3").'
                                                                 						</td>
																						 <td>'.$dat['currency'].' '.$obj_invoice->numberFormate(($soption['totalPriceWithTax'] / $dat['currency_price'] ),"3").'
																						 </td>';
                                               								 }  
																		}
																		$result.='<td>';
																					if($soption['cylinder_price']>0)
																					{
																						$result.=(int)$soption['cylinder_price'];
																					}
																					else
																					{
																						$result.= '';
																					}
												 						$result.='</td>
																				  <td>';
																				  	if($soption['tool_price']>0)
																					{
																						$result.=(int)$soption['tool_price'];
																					}
																					else
																					{
																						$result.= '';
																					}
																		$result.='</td>';
																		if($data[0]['status'] != 1)
																		{
																			$result.='<td class="delete-quot">
                                                              							<a class="btn btn-danger btn-sm" id="'.$soption['custom_order_price_id'].'" href="javascript:void(0);">
															 							 <i class="fa fa-trash-o"></i></a>
                                                                					  </td>';
																		}
																		$result.='</tr>';
																}
																$result .='</tr>';
																$i++;
															}
															$result .='</tbody>
																</table>
															  </div>
															</section> 
														</div>
													  </div>';
											}
									//printr($result);
									$arr['response'] = $result;	
									echo json_encode($arr);
					
			}
		
		}
		else
		{
			echo 1;
		}
	}
	else	
	{
		echo 1;
	}
}*/
if($fun == 'getStockData') {

	$stock_order_number = $_POST['stock_order_number'];
	if($stock_order_number != '')
	{
		$stock_order_id = $obj_invoice->getStockIdold($stock_order_number);
		$client= $stock_order_id['client_id'];
		if($stock_order_id)
		{
			$orders = $obj_invoice->GetStockOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND 
									t.status = 1 AND sos.status=1',$client);
			//printr($orders);
			$result ='';
			foreach($orders as $key=>$stock_order)
			{
				
				$result.='<div class="form-group">
							<label class="col-lg-3 control-label" style="width: 10%;">Price ('.$key.')</label> 
							<div class="col-lg-9" style="width:89%">
								<section class="panel">
								  <div class="table-responsive">
									<table class="table table-striped b-t text-small">
									  <thead>
										  <tr>
											<th width="20"></th>
											<th>Product Name</th>
											<th>Quntity</th>
											<th>Option</th>
											<th>Dimention</th>
											<th>Color</th>
											<th>Price Per Unit</th>
											<th>Total Price</th>
										</tr>
									</thead>
									<tbody>';
										foreach($stock_order as $stock)
										{//printr($stock_order);
											$result.='<tr>';
											foreach($stock as $s_data)
											{
												$result.='<tr id="stock-row-'.$s_data['template_order_id'].'">
															<td><input type="checkbox" name="custom_order_id[]" value="'.$s_data['template_order_id'].'"/>										
															</td>
															<th>'.$s_data['product_name'].'</th>
															<th>'.$s_data['quantity'].'</th>
															<td>'.ucwords($s_data['zipper']).' '.ucwords($s_data['valve']).' '.ucwords($s_data['spout']).' '.ucwords($s_data['accessorie']).'</td>
															<td>'.$s_data['width'].'X'.$s_data['height'].'X'.$s_data['gusset'].'</td>
															<td>'.$s_data['color'].'</td>
															<td>'.$s_data['currency_code'].' '.$s_data['price'].'</td>
															<td>'.$s_data['currency_code'].' '.$s_data['price']*$s_data['quantity'].'</td>';
															
												$result.='</tr>';
											}
											$result.='</tr>';
										}
									
						$result.='</tbody>
								</table>
							  </div>
							</section> 
						</div>
					  </div>';
											
			}
			$arr['response'] = $result;	
			echo json_encode($arr);
		
		}
	}
	else
	{
		echo 1;
	}
	
}
if($fun == 'getCustomData') {
	$custom_order_number = $_POST['custom_order_number'];
	$stock_order_number = $_POST['stock_order_number'];
		$stock_order_number_arr = $obj_invoice->getStockId($stock_order_number);
		$multi_custom_order_id_arr = $obj_invoice->getCustomId($custom_order_number);
		
		//Stock Order
		$sodh=$sodh_client='';
		$firsttime = true;
		foreach($stock_order_number_arr as $stock_order_id)
		{	
			if($firsttime)
			{	
				$sodh .= 'so.stock_order_id = "'.$stock_order_id['stock_order_id'].'"';
				$sodh_client .= 't.client_id = "'.$stock_order_id['client_id'].'"';
				$firsttime = false;
			}
			else
			{
				$sodh .= 'OR so.stock_order_id = "'.$stock_order_id['stock_order_id'].'"';
				$sodh_client .= 'OR t.client_id = "'.$stock_order_id['client_id'].'"';
			}
			
		}
		$orders = $obj_invoice->GetStockOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND t.status = 1',$stock_order_id['client_id'],$stock_order_id['stock_order_id'],$sodh_client,$sodh);
		//printr($orders);
			$result ='';
			foreach($orders as $key=>$stock_order)
			{
				
				$result.='<div class="form-group">
							<label class="col-lg-3 control-label" style="width: 10%;">Price ('.$key.')</label> 
							<div class="col-lg-9" style="width:89%">
								<section class="panel">
								  <div class="table-responsive">
									<table class="table table-striped b-t text-small">
									  <thead>
										  <tr>
											<th width="20"></th>
											<th>Stock Order No</th>
											<th>Product Name</th>
											<th>Quntity</th>
											<th>Option</th>
											<th>Dimention</th>
											<th>Color</th>
											<th>Price Per Unit</th>
											<th>Total Price</th>
											<th>Net Weight(In kgs)</th>
										</tr>
									</thead>
									<tbody>';
										foreach($stock_order as $stock)
										{
											$result.='<tr>';
											foreach($stock as $s_data)
											{
												$dis_qty=$obj_invoice->getDispatchQty($s_data['template_order_id'],$s_data['product_template_order_id']);
												
												$result.='<tr id="stock-row-'.$s_data['template_order_id'].'">
															<td><input type="hidden" name="template_order_id[]" value="'.$s_data['template_order_id'].'"/></td>
															<td>'.$s_data['gen_order_id'].'</td>
															<td>'.$s_data['product_name'].'</td>
															<td>'.$dis_qty['total_dis_qty'].'</td>
															<td>'.ucwords($s_data['zipper']).' '.ucwords($s_data['valve']).' '.ucwords($s_data['spout']).' '.ucwords($s_data['accessorie']).'</td>
															<td>'.$s_data['width'].'X'.$s_data['height'].'X'.$s_data['gusset'].'</td>
															<td>'.$s_data['color'].'</td>
															<td>'.$s_data['currency_code'].' '.$s_data['price'].'</td>
															<td>'.$s_data['currency_code'].' '.$s_data['price']*$s_data['quantity'].'</td>
															<td><input type="text" class="form-control validate[required]" name="netweight_'.$s_data['template_order_id'].'" id="netweight" value=""></td>';
															
												$result.='</tr>';
											}
											$result.='</tr>';
										}
									
						$result.='</tbody>
								</table>
							  </div>
							</section> 
						</div>
					  </div>';
			
			}
		
		
		
		//custom order
		$cust_cond='';
		$ftime=true;
		foreach($multi_custom_order_id_arr as $multi_custom_order_id)
		{	
			if($ftime)
			{	
				$cust_cond .= 'mco.multi_custom_order_id = "'.$multi_custom_order_id['multi_custom_order_id'].'"';
				$ftime = false;
			}
			else
			{
				$cust_cond .= 'OR mco.multi_custom_order_id = "'.$multi_custom_order_id['multi_custom_order_id'].'"';
			}
			
		}
		$getData = " custom_order_id,mco.added_by_user_id,mco.added_by_user_type_id, customer_name, shipment_country_id,mco.multi_custom_order_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,mco.status,mco.custom_order_status,discount";
		$data = $obj_invoice->getCustomOrder($cust_cond,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);

		$cust_qty='';
		$fsttime=true;
		foreach($data as $custom_order_id)
		{	
			if($fsttime)
			{	
				$cust_qty .= 'mcoq.custom_order_id = "'.$custom_order_id['custom_order_id'].'"';
				$fsttime = false;
			}
			else
			{
				$cust_qty .= 'OR mcoq.custom_order_id = "'.$custom_order_id['custom_order_id'].'"';
			}
			$cust_o_id[$custom_order_id['multi_custom_order_id']]=$custom_order_id['multi_custom_order_number'];
		}
		$result_cust = $obj_invoice->getCustomOrderQuantity($cust_qty);
		  if($result_cust!='')
			$quantityData[] =$result_cust;
		  } 
		  
		  if(!empty($quantityData))
		  {
				foreach($quantityData as $k=>$qty_data)
				{
					foreach($qty_data as $tag=>$qty)
					{
						foreach($qty as $q=>$arr)
						{
							$new_data[$tag][$q]=$arr;
							//$new_data[$tag][$q][]=$arr[0];
						}
					}	
			}
			
					//$result .='<input type="hidden" id="multi_custom_order_id" name="multi_custom_order_id" value='.$multi_custom_order_id.'/>';
					foreach($new_data as $k=>$qty_data)
					{	
						foreach($qty_data as $sOne)
						{
							foreach($sOne as $sThree)
							{
								$custom_order_type = $sThree['custom_order_type'];
								$custom_order_status = $sThree['custom_order_status'];
								$currency = $sThree['currency'];
								$shipment_country_id = $sThree['shipment_country_id'];
								$status = $sThree['status'];
								$currency_price = $sThree['currency_price'];
							}
						}
						
						$result.='<div class="form-group">
									<label class="col-lg-3 control-label" style="width: 10%;">Price (By '.$k.')</label> 
									<div class="col-lg-9" style="width:89%">
										<section class="panel">
										  <div class="table-responsive">
											<table class="table table-striped b-t text-small">
											  <thead>
												  <tr>
													<th width="20"></th>
													<th>Custom Order No</th>
													<th>Product Name</th>
													<th>Quntity</th>';
													
													if($custom_order_type != 1){ 
														$result.='<th>Option(Printing Effect )</th>';
													 } 
													 
													$result.='<th>Dimension (Make Pouch)</th>
													<th>Layer:Material:Thickness</th>';
													if($custom_order_status == 0){
														 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
														{ 				
															 if($currency == 'INR')
															 {
																$result.='<th>Discount</th>';
															 }
														} 
													}
													$result.='<th>Price / pouch</th>
													<th>Total</th>';
													if($k=='pickup') {
														if($shipment_country_id==111){ 
															$result.='<th> Price / pouch  With Tax </th>
																	 <th>Total Price With Tax </th>';
													 } }
													 $result.='<th>Cylender Price</th>
																<th>Tool Price</th>';
													if($status != 1){
													 		$result.='<th>Action</th>';
													 }
													 $result.='<th>Net Weight(In Kgs)</th>
												  </tr>
											  </thead>
											  <tbody>';
											  	$i=1;
                                                foreach($qty_data as $skey=>$sdata)
												{
													$result.='<tr>';
													foreach($sdata as $soption)
													{
														$result.='<tr id="custom-row-'.$soption['custom_order_price_id'].'">
																		<td><input type="hidden" name="multi_custom_order_id[]" value="'.$soption['multi_custom_order_id'].'=='.$soption['custom_order_id'].'"/></td>
																		<td>'.$cust_o_id[$soption['multi_custom_order_id']].'</td>
																		<td>'.$soption['product_name'].'</td>
                                                            			<td>'.$skey.'</td>
																		<td>'.ucwords($soption['text']).' ('.$soption['printing_effect'].')'.'</td>
																		<td>'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.$soption['gusset']; 
																			if($data[0]['product_name']!=10)
																			{
																				if($soption['volume']>0) 
																					$result.=' ('.$soption['volume'].')';
																			}
																			else
																			{
																				 $result.=' (Custom) ('.$soption['make'].')</td>';
																			}
																		$result.='<td>';
                                                             				for($gi=0;$gi<count($soption['materialData']);$gi++)
																			{
											 										 $result.='<b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
																					 
																			}
                                                                 		$result.='</td>';
																		if($custom_order_status == 0)
																		{
																	 		if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																			{ 
																				if($currency=='INR')
																		$result.='<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['custom_order_quantity_id'].'" class="form-control" style="width: 100px;" ></td>';
																			 }
																		 }
																		$result.=' <td>';
																		if($soption['discount'] && $soption['discount'] >0.000) 
																		{
                                                               				 $result.=' <b>Total : </b>';
																			 $pretot= $obj_invoice->numberFormate((($soption['totalPrice'] / $skey) / $currency_price),"3");
																			 $result.=$pretot.'"<br />
                                                                					<b>Discount ("'.$soption['discount'].'" %) : </b>';
																 					$predis = $pretot*$soption['discount']/100; 
																					$result.=$obj_invoice->numberFormate($predis,"3").'<br />
                                                               						 <b>Final Total : </b>
																					'.$dat['currency'].' '.$obj_invoice->numberFormate(($pretot-$predis),"3");
																		}
																		else
																		{ 
																			$result.=$currency.' '.$obj_invoice->numberFormate((($soption['totalPrice'] / $skey) / $currency_price),"3");
																		}
                                                              			$result.='</td>
																		<td>';
																			if($soption['discount'] && $soption['discount'] >0.000) 
																			{
                                                               					$result.='<b>Total : </b>';
																					$tot= $obj_invoice->numberFormate(($soption['totalPrice'] / $currency_price ),"3"); 							                                                                                        $result.=$tot.'"<br />
                                                                							<b>Discount ('.$soption['discount'].' %) : </b>';
																								$dis = $tot*$soption['discount']/100; 
																								$result.=$obj_invoice->numberFormate($dis,"3").'<br />
                                                                										<b>Final Total : </b>
																											'.$currency.' '.($tot-$dis);
																			} 
																			else
																			{
																				$result.=$currency.' '.$obj_invoice->numberFormate(($soption['totalPrice'] / $currency_price ),"3");
																			}
                                                                 		$result.='</td>';
																		if($k=='pickup') {
																	  		if($shipment_country_id==111)
																			{ 
																				$result.='<td>'.$currency.' '.$obj_invoice->numberFormate((($soption['totalPriceWithTax'] / $skey) / $currency_price ),"3").'
                                                                 						</td>
																						 <td>'.$currency.' '.$obj_invoice->numberFormate(($soption['totalPriceWithTax'] / $currency_price ),"3").'
																						 </td>';
                                               								 }  
																		}
																		$result.='<td>';
																					if($soption['cylinder_price']>0)
																					{
																						$result.=(int)$soption['cylinder_price'];
																					}
																					else
																					{
																						$result.= '';
																					}
												 						$result.='</td>
																				  <td>';
																				  	if($soption['tool_price']>0)
																					{
																						$result.=(int)$soption['tool_price'];
																					}
																					else
																					{
																						$result.= '';
																					}
																		$result.='</td>';
																		if($data[0]['status'] != 1)
																		{
																			$result.='<td class="delete-quot">
                                                              							<a class="btn btn-danger btn-sm" id="'.$soption['custom_order_price_id'].'" href="javascript:void(0);">
															 							 <i class="fa fa-trash-o"></i></a>
                                                                					  </td>';
																		}
																		$result.='<td><input type="text" class="form-control validate[required]" name="netweight_cust" id="netweight_cust" value=""></td></tr>';
																}
																$result .='</tr>';
																$i++;
															}
															$result .='</tbody>
																</table>
															  </div>
															</section> 
														</div>
													  </div>';
								}
							
							$arr_result['response'] = $result;	
							echo json_encode($arr_result);
		
	
}
if($fun=='alldone')
{
	$invoice_id = decode($_POST['invoice_no']);
	$obj_invoice->alldone($invoice_id);
	
}
//[kinjal] on 19-11-2016
if($fun=='change_qty_per_kg')
{
	$obj_invoice->change_qty_per_kg($_POST['inv_color_id'],$_POST['value'],$_POST['n']);
}
if($fun == 'updateRateForCustomOrder'){
	
	$result = $obj_invoice->updateRateForCustomOrder($_POST['invoice_rate'],$_POST['invoice_color_id']);
	
}
if($fun == 'set_series'){
	
	$result = $obj_invoice->set_series($_POST['inv_id']);
	
}
?>