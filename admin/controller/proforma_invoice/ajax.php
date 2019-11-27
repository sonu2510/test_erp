<?php
include("mode_setting.php");
$fun = $_GET['fun'];
$json=1;
if($fun == 'checkProductZipper'){
	$zipper_available = $obj_pro_invoice->checkProductzipper($_POST['product_id']);
	//printr($zipper_available);
	$tintie_available = $obj_pro_invoice->checkProductTintie($_POST['product_id']);
	$html ='<div  class="form-group option"> 
				<label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-9">';
	                        $zippers = $obj_pro_invoice->getActiveProductZippers();
							$ziptxt = '';
                            foreach($zippers as $zipper){
                           
							   $ziptxt .= '<div   style="float:left;width: 200px;">';
							   		$ziptxt .= '<label  style="font-weight: normal;">';
									if($zipper_available==0 )
									{ //echo "1";
										if($tintie_available == 0)
										{
										
											if( $zipper['product_zipper_id']==2)
											{
											$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
											}
											else
											{
											$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'" disabled="disabled" class="zipper"> '.$zipper['zipper_name'];
											}
										}
										else
										{
											if( $zipper['product_zipper_id']==2)
											{
											$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
											}
											else
											{
											$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'" class="zipper"> '.$zipper['zipper_name'];
											}
										}
									}
									else
									{ //echo "2";
										if($zipper['product_zipper_id']==1)
										{
										$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'"   checked="checked" onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										}
										else
										{
									$ziptxt .= '<input type="radio" name="zipper" class="zipper" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()">';
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
if($fun == 'checkProductGusset'){
	$gusset_available = $obj_pro_invoice->checkProductGusset($_POST['product_id']);
	echo $gusset_available;
}
if($fun == 'getProductSize') {
	//printr($_POST);	//die;
	$product_id = $_POST['product_id'];
	//echo $_POST['zipper_id'];
	if(isset($_POST['zipper_id']))
	{
		$zipper_id = $_POST['zipper_id'];
	}
	else
	{
		$zipper_id='';
	}
	//die;
	$data = $obj_pro_invoice->getProductSize($product_id,$zipper_id);
	$response = '';	
		$response .='<select id="size" name="size" class="form-control validate[required]" onchange="customSize()"><option value="">Select Size</option>';
	if($data){	
		foreach($data as $item){
				//$response .= '<div class="checkbox " style="float: left;  width: 50%;"><label>';
					//$response .= '<input type="checkbox" name="size[]" class="test" onclick="removeValidation()" id="'.$item['size_master_id'].'" value="'.encode($item['size_master_id']).'" >'.$item['volume'].'['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']';
					
					if($product_id=='27')
						$response .= '<option value="'.encode($item['size_master_id']).'" selected="selected">';
					else
						$response .= '<option value="'.encode($item['size_master_id']).'">';
					if($item['volume']!=0)
					$response .=  $item['volume'];
					$response .= ' ['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']</option>';					
				//$response .= '</label></div>';
		}
	}
	 
	 	// sonu add condition Oxo-Degradable Bags - Brand: Bak2Earth - Stand up pouch[online product id = 22]Silica Gel / Moisture Absorbers[online product id 38]
	  	if($product_id!='28')
		{		
			$response .= '<option value="0">Custom</option></select>';
		}
	
	echo $response;
}
if($fun == 'addProformaInvoice'){
	
	$html = '';
	parse_str($_POST['formData'], $post);
	$LastId = $obj_pro_invoice->addProformaNew($post);
	//printr($post);die;
	foreach($LastId as $proId) {
		$last = $proId;
	}
	//printr($last);die;
	$proforma = $obj_pro_invoice->getProformaInvoice($last['proforma_id']);
	//printr($proforma);
	$pro = $obj_pro_invoice->getProforma($last['proforma_id']);
	$html .='<input type="hidden" id="proforma_id" name="proforma_id" value="'.$last['proforma_id'].'"/>';
	$html .= '<h4><i class="fa fa-plus-circle"></i> Added Invoice</h4>';
         $html .= '<div class="line m-t-large" style="margin-top:-4px;"></div><br/>';
		
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th>Size</th>';			
			$html .= '<th>Color : Quantity</th>';
			$html .= '<th>Rate</th>';
			$html .= '<th>Option</th>';
			$html .= '<th>Transport</th>';			
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  
		  
		$getInvoices = $obj_pro_invoice->getInvoice($last['proforma_id']);
			  
		
			  
			  foreach($getInvoices as $invoice ) {
				  //get spout details
				  $getProductSpout = $obj_pro_invoice->getSpout(decode($invoice['spout']));
				  //get zipper details
				  $getProductZipper = $obj_pro_invoice->getZipper(decode($invoice['zipper']));
				  //get accessorie details
				  $getProductAccessorie = $obj_pro_invoice->getAccessorie(decode($invoice['accessorie']));

				  $html .= '<tr id="proforma_invoice_id_'.$invoice['proforma_invoice_id'].'">';				  
				  $html .= '<td><b>'.$invoice['product_name'].'</b></td>';
				  

				  $html .= '<td>'.floatval($invoice['width']).'X'.floatval($invoice['height']);
				  if($invoice['gusset'] > 0) {
					  $html .= 'X'.floatval($invoice['gusset']);
				  }
					  if($invoice['volume'] > 0) {
						  $html .= ' ('.$invoice['volume'].')';
					  }


				$html .= '<td>';
				
				  $quantity = $obj_pro_invoice->getColorDetails($last['proforma_id'],$invoice['proforma_invoice_id']);
				  
				foreach($quantity as $quantity_val) {
					//printr($quantity_val);
					  $clr_text='';
					if($quantity_val['color']=='-1')
					{
						$clr_text = "(".$quantity_val['color_text'].")";
					}
					$clr_nm =  $quantity_val['color_name'];
				    $html .= $clr_nm.''.$clr_text.' : '.$quantity_val['quantity'].'<br>';				 
				  }
				  $html .= '</td>';
				 
				  $html .= '</td>';
				  $html .= '<td>';
				  foreach($quantity as $rate_val) {
					  $html .= $rate_val['rate'].'<br>';
				  }
				  $html .= '</td>';
				  $html .= '<td>'.ucwords($getProductSpout['spout_name']).' '.$invoice['valve'].'<br>'.$getProductZipper['zipper_name'].' '.ucwords($getProductAccessorie['product_accessorie_name']).'</td>';
				
				  $html .= '<td>By '.ucwords(decode($pro['transportation'])).'</td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice('.$invoice['proforma_invoice_id'].','.$invoice['proforma_id'].')"><i class="fa fa-trash-o"></i></a><a href="'.$obj_general->link($rout, 'mod=add&proforma_id='.encode($invoice['proforma_id']).'&proforma_in_id='.encode($invoice['proforma_invoice_id']).'&is_delete=0','',1).'"  name="btn_edit" class="btn btn-info btn-xs">Edit</a></td>';
				  $html .= '</tr>';
				  
				  $html .= '<div class="modal fade" id="alertbox_'.$invoice['proforma_invoice_id'].'">
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
                                    <button type="button" name="popbtnok" id="popbtnok_'.$invoice['proforma_invoice_id'].'" class="btn btn-primary">Ok</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>';
				  
		 }
		  $html .= '<input type="hidden" name="taxation_old" value="'.$pro['taxation'].'"><input type="hidden" name="country_id" value="'.$pro['destination'].'"></tbody></table>';
		  
		 echo $html;
}

if($fun == 'removeInvoice'){

	$result=$obj_pro_invoice->removeInvoice($_POST['proforma_invoice_id'],$_POST['proforma_id']);
//echo $result;
}
if($fun == 'updateProStatus') {
	$proforma_id = $_POST['proforma_id'];
	$status_value = $_POST['status_value'];
	$obj_pro_invoice->$fun($proforma_id,$status_value);
}
if($fun == 'getProformaInvoice'){
	$response=$obj_pro_invoice->getSingleInvoice($_POST['proforma_invoice_id']);
	echo json_encode($response);
}
if($fun == 'getbankDetails') {
	$bank = $obj_pro_invoice->getbankDetails($_POST['currency_id']);
	$html = '';
	if(isset($bank) && !empty($bank)) {
	$html .= '<select name="bank_id" id="bank_id" class="form-control validate[required]" style="width:70%" >';
		$html .= '<option value="">Select Bank</option>';
			foreach($bank as $details){
				$html .= '<option value="'.$details['bank_detail_id'].'" >'.$details['benefry_bank_name'].'</option>';
			}
		$html .= '</select>';
	}
	echo $html;
}
if($fun == 'updateInvoice') {
	$html = '';
	parse_str($_POST['formData'], $postdata);
	//printr($postdata);
	//die;
	$invoice = $obj_pro_invoice->updateInvoice($postdata);
	$pro = $obj_pro_invoice->getProforma($postdata['proforma_id']);

	$invoice_detail = $obj_pro_invoice->getProformaInvoice($postdata['invoiceno']);
			
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th>Size</th>';			
			$html .= '<th>Color : Quantity</th>';
			$html .= '<th>Rate</th>';
			$html .= '<th>Option</th>';
			$html .= '<th>Transport</th>';			
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  $html .= '<tbody>';
		  $html .= '<input type="hidden" value="'.$postdata['invoiceno'].'" name="proforma_id">';
		  foreach($invoice_detail as $detail ) {
				  //get spout details
				  $getProductSpout = $obj_pro_invoice->getSpout(decode($detail['spout']));
				  //get zipper details
				  $getProductZipper = $obj_pro_invoice->getZipper(decode($detail['zipper']));
				  //get accessorie details
				  $getProductAccessorie = $obj_pro_invoice->getAccessorie(decode($detail['accessorie']));

				  $html .= '<tr id="proforma_invoice_id_'.$detail['proforma_invoice_id'].'">';				  
				  $html .= '<td><b>'.$detail['product_name'].'</b></td>';
				  
				  $html .= '<td>'.floatval($detail['width']).'X'.floatval($detail['height']).'X'.floatval($detail['gusset']);
				  // mansi 23-1-2016 (display volume in add page)
				  if($detail['volume'] > 0) {
						  $html .= ' ('.$detail['volume'].')';
					  }
					 $html .= ' </td>';
				  $quantity = $obj_pro_invoice->getColorDetails($postdata['invoiceno'],$detail['proforma_invoice_id']);
				  $html .= '<td>';
				  foreach($quantity as $quantity_val) {
					   $clr_text='';
					if($quantity_val['color']=='-1')
					{
						$clr_text = "(".$quantity_val['color_text'].")";
					}
					$clr_nm =  $quantity_val['color_name'];
					
					 $html .= $clr_nm.''.$clr_text.' : '.$quantity_val['quantity'].'<br>';				 
				  }
				  $html .= '</td>';
				 
				  $html .= '</td>';
				  $html .= '<td>';
				  foreach($quantity as $rate_val) {
					  $html .= $rate_val['rate'].'<br>';
				  }
				  $html .= '</td>';
				  $html .= '<td>'.ucwords($getProductSpout['spout_name']).' '.$detail['valve'].'<br>'.$getProductZipper['zipper_name'].' '.ucwords($getProductAccessorie['product_accessorie_name']).'</td>';
				  
				  		  
				  $html .= '<td>By '.ucwords(decode($pro['transportation'])).'</td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice('.$detail['proforma_invoice_id'].','.$detail['proforma_id'].')"><i class="fa fa-trash-o"></i></a>
				  <a href="'.$obj_general->link($rout, 'mod=add&proforma_id='.encode($postdata['invoiceno']).'&proforma_in_id='.encode($detail['proforma_invoice_id']).'&is_delete=0'.'','',1).'" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
				  </td>';
				  $html .= '</tr>';
				  $html .= '<div class="modal fade" id="alertbox_'.$detail['proforma_invoice_id'].'">
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
                                    <button type="button" name="popbtnok" id="popbtnok_'.$detail['proforma_invoice_id'].'" class="btn btn-primary">Ok</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>';
				}
			  $html .= '<input type="hidden" name="taxation_old" value="'.$pro['taxation'].'"><input type="hidden" name="country_id" value="'.$pro['destination'].'"></tbody></table>';
		  echo $html;
}
if($fun == 'updateProforma') {
	parse_str($_POST['formData'], $postdata);
	$proforma = $obj_pro_invoice->updateProforma($postdata);
	echo $proforma;
}
if($fun == 'approveordis') {
	parse_str($_POST['formData'], $postdata);
	$proforma = $obj_pro_invoice->approveordis($postdata);
	echo $proforma;
}
if($fun == 'getViewToolprice')
{
	$product_id = $_POST['product_id'];
	$tool_price = $obj_pro_invoice->getViewToolprice($_POST['product_id']);
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
if($fun == 'getWidthSuggestion')
{
	$gusset = '';
	$widthsuggestion = $obj_pro_invoice->getGussetSuggestion($_POST['width'],$gusset,$_POST['product_id']);
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
if($fun == 'getGussetSuggestion')
{
	$gussetsuggestion = $obj_pro_invoice->getGussetSuggestion($_POST['width'],$_POST['gusset'],$_POST['product_id']);
	$pricesuggestion = $obj_pro_invoice->getToolPrice($_POST['width'],$_POST['gusset'],$_POST['product_id']);
	if($obj_session->data['LOGIN_USER_TYPE']==1){
		$userCurrency = $obj_pro_invoice->getCurrencyId($obj_session->data['ADMIN_LOGIN_SWISS']);
		$userCurrency['currency_code'] = "INR";
		$userCurrency['tool_rate']='';
	}else{
		$userCurrency =  $obj_pro_invoice->getUserWiseCurrency($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
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
			$arr .=$a.$gusset['gusset'].'mm  else tool price '.' '.$userCurrency['currency_code'].' '.$obj_pro_invoice->numberFormate($tool_price,"2").$b.'
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

if($fun == 'state_detail')
{
	$detail=$obj_pro_invoice->stateDetail($_POST['state_id']);
	$gst = $detail['gst'];
	$pst = $detail['rst'];
	$hst = $detail['hst'];
	echo json_encode($detail);
	
}
if($fun == 'customer_detail'){
	$customer_name = $_POST['customer_name'];
	$result = $obj_pro_invoice->getCustomerDetail($customer_name);
	echo json_encode($result);
}
//[sonu] onb 12/11/2016
if($fun=='getproductrate'){	
	
	$total_price = $obj_pro_invoice->getproductprice($_POST['size'],$_POST['valve'],$_POST['zipper'],$_POST['spout'],$_POST['accessorie'],$_POST['color']);
	echo $total_price;
	
}

?>