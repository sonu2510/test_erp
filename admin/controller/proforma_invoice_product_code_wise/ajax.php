<?php
include("mode_setting.php");
$fun = $_GET['fun'];
//printr($fun);die;
$json=1;
if($fun == 'checkProductZipper'){
	$zipper_available = $obj_pro_invoice->checkProductzipper($_POST['product_id']);
	$tintie_available = $obj_pro_invoice->checkProductTintie($_POST['product_id']);
	$html ='<div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-9">';
	                        $zippers = $obj_pro_invoice->getActiveProductZippers();
							$ziptxt = '';
                            foreach($zippers as $zipper){
                           
							   $ziptxt .= '<div   style="float:left;width: 200px;">';
							   		$ziptxt .= '<label  style="font-weight: normal;">';
									if($zipper_available==0 )
									{ 
										if($tintie_available == 0)
										{
										
											if( $zipper['product_zipper_id']==2)
											{
											$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
											}
											else
											{
											$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'"   disabled="disabled" class="zipper"> '.$zipper['zipper_name'];
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
											$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'"  class="zipper"> '.$zipper['zipper_name'];
											}
										}
									}
									else
									{
										if( $zipper['product_zipper_id']==2)
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
	//printr($_POST);	die;
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
	//echo $zipper_id;
	$data = $obj_pro_invoice->getProductSize($product_id,$zipper_id);
	//printr($data); die;
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
	//printr($response);
	echo $response;
}
if($fun == 'addProformaInvoice'){
	
	$html = '';
	parse_str($_POST['formData'], $post);
	//printr($post);die;
	$LastId = $obj_pro_invoice->addProformaNew($post);
	//printr($LastId);
	foreach($LastId as $proId) {
		$last = $proId;
	}
	//if($_SESSION['LOGIN_USER_TYPE']=='2' && $_SESSION['ADMIN_LOGIN_SWISS']=='227')
	    //printr($last);printr($LastId['total_amount']);
	$updateTotalPrice = $obj_pro_invoice->UpdateTotalInvoicePrice($last['proforma_id'],1);
	$pro = $obj_pro_invoice->getProforma($last['proforma_id']);
	//printr($pro);
	$html .='<input type="hidden" id="proforma_id" name="proforma_id" value="'.$last['proforma_id'].'"/>
	        <input type="hidden" id="total_amt" name="total_amt" value="'.$updateTotalPrice.'"/>
	            <input type="hidden" id="proforma_no" name="proforma_no" value="'.$pro['pro_in_no'].'"/>';
	$html .= '<h4><i class="fa fa-plus-circle"></i> Added Invoice</h4>';
         $html .= '<div class="line m-t-large" style="margin-top:-4px;"></div><br/><div class="table-responsive">';
		
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			if(!isset($post['for_freight_charge']) || $post['for_freight_charge'] =='No')
			{
    			$html .= '<th>Size</th>';			
    			$html .= '<th>Color : Quantity</th>';
    		    $html .= '<th>Rate';if($_POST['con_id']=='155') { $html .= '[Normal Delivery]<br>Rate [Express Delivery]'; }$html .='</th>';
    			$html .= '<th>Option</th>';
    			$html .= '<th>Transport</th>';			
    			$html .= '<th>Action</th>';
			}
			else
			   $html .= '<th>Charge</th>';
			   
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  
		if(!isset($post['for_freight_charge']) || $post['for_freight_charge'] =='No')
		{
    		$getInvoices = $obj_pro_invoice->getInvoice($last['proforma_id']);
    		//printr($getInvoices);	  
    		
    			  $sub_total = 0;
    			  foreach($getInvoices as $invoice ) {
    			      
    					//to save invoice total 	invoice_total
    					$sub_total = $sub_total + ($invoice['quantity'] * $invoice['rate']);
    					
    					$product_code_data = $obj_pro_invoice->getProductCode($invoice['product_code_id']);
    			
    				  $html .= '<tr id="proforma_invoice_id_'.$invoice['proforma_invoice_id'].'">';				  
    				  $html .= '<td><b>'.$product_code_data['product_code'].'</b><br>'.$invoice['product_name'].'</td>';
    				  
    
    				  $html .= '<td width="75">';
    						$measure = $obj_pro_invoice->getMeasurementName($invoice['measurement']); 
    						if($invoice['product_code_id']=='-1' || $invoice['product_code_id']=='0')
    							$html .= $invoice['size'].'&nbsp;'.$measure['measurement'].'<br />';
    						else
    							$html .= $product_code_data['volume'].'&nbsp;'.$product_code_data['measurement'].'<br />';
    					$html .= '</td>';
    
    
    				$html .= '<td>';
    					$clr_text='';
    					if($invoice['product_code_id']=='-1')
    					{
    						$clr_nm = 'Custom';
    						$clr_text = "(".$invoice['color_text'].")";
    					}
    					elseif($invoice['product_code_id']=='0')
    					{
    						$clr_nm = 'Cylinder';
    					}
    					else
    					{
    						$clr_nm = $product_code_data['color'];
    					}
    					
    				    $html .= $clr_nm.''.$clr_text.' : ';
    				    if($product_code_data['product']=='6')
    				        $html.=$invoice['quantity'];
    				    else
    				        $html.=number_format($invoice['quantity'],"0", '.', '');
    				  $html .= '<br></td>';
    				 
    				  //$html .= '</td>';
    				  $html .= '<td>';
    					  $html .= $invoice['rate'].'<br>';
    					  if($_POST['con_id']=='111')
    						  $html .= 'Net W. : '.$invoice['netweight'].' KG';
    					   if($_POST['con_id']=='155')
    							$html .= $invoice['express_rate'].'<br>';
    							
    				  $html .= '</td>';
    				  $html .= '<td>'.ucwords($product_code_data['spout_name']).' '.$product_code_data['valve'].'<br>'.ucwords($product_code_data['zipper_name']).' '.ucwords($product_code_data['product_accessorie_name']).'</td>';
    				  
    				  if($pro['destination']=='42')
    					{
    						
    						if(ucwords(decode($pro['transportation']))=='Air')
    							$html.='<td> By Rush Order </td>';
    						else
    							$html.='<td>By Normal Order </td> ';
    					}
    					else
    					{		
    						$html .= '<td>By '.ucwords(decode($pro['transportation'])).'</td>';
    					}
    				
    				 // $html .= '<td>By '.ucwords(decode($pro['transportation'])).'</td>';
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
		}
		else
		{
		    $html .= '<tr>';				  
        				  $html .= '<td><b>Frieght Charge</b></td>';
        				  $html .= '<td>'.$pro['freight_charges'].'</td>
    				  </tr>';
    		
		}
		  $html .= '<input type="hidden" name="taxation" value="'.$pro['taxation'].'"><input type="hidden" name="country_id" value="'.$pro['destination'].'">
		  <input type="hidden" name="proforma_status" id="proforma_status" value="'.$pro['proforma_status'].'">
		  </tbody></table></div>';
		  
		 echo $html;
}
//sonu 30-6-2017
if($fun == 'productrate')
{
	$product_code_id = $_POST['product_code_id'];
	//printr($product_code_id);die;
	$color=$_POST['color'];
    $result = $obj_pro_invoice->getproductrate($product_code_id,$color);
	//echo $obj_pro_invoice;
//	printr($result);die;
	echo $result;

}
if($fun == 'removeInvoice'){

	$result=$obj_pro_invoice->removeInvoice($_POST['proforma_invoice_id'],$_POST['proforma_id']);
	echo $result;
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
	$bank = $obj_pro_invoice->getbankDetails($_POST['currency_id'],$_POST['user_id']);
	$html = '';
	if(isset($bank) && !empty($bank)) {
	     if(!isset($proforma_inv['bank_id']) && $addedByInfo['user_id']=='44'){
                            $proforma_inv['bank_id']='22';
                   
         } 
         if(!isset($proforma_inv['bank_id']) && ($addedByInfo['user_id']=='24' || $addedByInfo['user_id']=='33' )){
                            $proforma_inv['bank_id']='13';
                   
         }   
	$html .= '<select name="bank_id" id="bank_id" class="form-control validate[required]" style="width:70%" >';
		$html .= '<option value="">Select Bank</option>';
			foreach($bank as $details){
				
				if($details['bank_detail_id']=='13' && ($_POST['user_id']=='24'|| $_POST['user_id']=='33'))
				{
				    $html .= '<option value="'.$details['bank_detail_id'].'" selected=selected >'.$details['benefry_bank_name'];
				} 
			
				else
				{
    				$html .= '<option value="'.$details['bank_detail_id'].'" >'.$details['benefry_bank_name'];
    				if($_POST['user_id']=='10'){ $html .=' [<b>'.$details['bank_accnt'].'</b>]';	 } 
				}
				
				$html .='</option>';
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
	$updateTotalPrice = $obj_pro_invoice->UpdateTotalInvoicePrice($postdata['proforma_id'],1);
	$invoice_detail = $obj_pro_invoice->getProformaInvoice($postdata['invoiceno']);
			
		$html .= '<div class="table-responsive"><table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th>Size</th>';			
			$html .= '<th>Color : Quantity</th>';
			$html .= '<th>Rate';if($_POST['con_id']=='155') { $html .= '[Normal Delivery]<br>Rate [Express Delivery]'; }$html .='</th>';
			$html .= '<th>Option</th>';
			$html .= '<th>Transport</th>';			
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  $html .= '<input type="hidden" value="'.$postdata['invoiceno'].'" name="proforma_id" id="proforma_id">
		        <input type="hidden" id="total_amt" name="total_amt" value="'.$updateTotalPrice.'"/>
		    <input type="hidden" id="proforma_no" name="proforma_no" value="'.$pro['pro_in_no'].'"/>';
		  foreach($invoice_detail as $detail ) {
		  		
				$product_code_data = $obj_pro_invoice->getProductCode($detail['product_code_id']);
				 /* //get spout details
				  $getProductSpout = $obj_pro_invoice->getSpout(decode($detail['spout']));
				  //get zipper details
				  $getProductZipper = $obj_pro_invoice->getZipper(decode($detail['zipper']));
				  //get accessorie details
				  $getProductAccessorie = $obj_pro_invoice->getAccessorie(decode($detail['accessorie']));*/

				  $html .= '<tr id="proforma_invoice_id_'.$detail['proforma_invoice_id'].'">';				  
				   $html .= '<td><b>'.$product_code_data['product_code'].'</b><br>'.$detail['product_name'].'</td>';
				  
				  $html .= '<td width="75">';
						$measure = $obj_pro_invoice->getMeasurementName($detail['measurement']); 
						if($detail['product_code_id']=='-1' || $detail['product_code_id']=='0')
							$html .= $detail['size'].'&nbsp;'.$measure['measurement'].'<br />';
						else
							$html .= $product_code_data['volume'].'&nbsp;'.$product_code_data['measurement'].'<br />';
					$html .= '</td>';
				  
				  //$quantity = $obj_pro_invoice->getColorDetails($postdata['invoiceno'],$detail['proforma_invoice_id']);
				  /*$html .= '<td>';
				  foreach($quantity as $quantity_val) {
					   $clr_text='';
					if($quantity_val['color']=='-1')
					{
						$clr_text = "(".$quantity_val['color_text'].")";
					}
					$clr_nm =  $quantity_val['color_name'];
					
					 $html .= $clr_nm.''.$clr_text.' : '.$quantity_val['quantity'].'<br>';				 
				  }
				  $html .= '</td>';*/
				  
				  
				  $html .= '<td>';
					$clr_text='';
					if($detail['product_code_id']=='-1')
					{
						$clr_nm = 'Custom';
						$clr_text = "(".$detail['color_text'].")";
					}
					elseif($detail['product_code_id']=='0')
					{
						$clr_nm = 'Cylinder';
					}
					else
					{
						$clr_nm = $product_code_data['color'];
					}
					
				    $html .= $clr_nm.''.$clr_text.' : ';
				    
				    if($product_code_data['product']=='6')
				        $html.=$detail['quantity'];
				    else
				        $html.=number_format($detail['quantity'],"0", '.', '');
				        
				  $html .= '<br></td>';
				 
				 // $html .= '</td>';
				  $html .= '<td>';
					  $html .= $detail['rate'].'<br>';
					  if($_POST['con_id']=='111')
						  $html .= 'Net W. : '.$detail['netweight'].' KG';
					   if($_POST['con_id']=='155')
							$html .= $detail['express_rate'].'<br>';
				  $html .= '</td>';
				 // $html .= '<td>'.ucwords($getProductSpout['spout_name']).' '.$detail['valve'].'<br>'.$getProductZipper['zipper_name'].' '.ucwords($getProductAccessorie['product_accessorie_name']).'</td>';
				  $html .= '<td>'.ucwords($product_code_data['spout_name']).' '.$product_code_data['valve'].'<br>'.ucwords($product_code_data['zipper_name']).' '.ucwords($product_code_data['product_accessorie_name']).'</td>';
				  
				  if($pro['destination']=='42')
					{
						
						if(ucwords(decode($pro['transportation']))=='Air')
							$html.='<td> By Rush Order </td>';
						else
							$html.='<td>By Normal Order </td> ';
					}
					else
					{		
						$html .= '<td>By '.ucwords(decode($pro['transportation'])).'</td>';
					}
				  		  
				 // $html .= '<td>By '.ucwords(decode($pro['transportation'])).'</td>';
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
			  $html .= '<input type="hidden" name="taxation" value="'.$pro['taxation'].'"><input type="hidden" name="country_id" value="'.$pro['destination'].'">
			  <input type="hidden" name="proforma_status"  id="proforma_status" value="'.$pro['proforma_status'].'"></tbody></table></div>';
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
if($fun == 'product_code'){
	//echo "hi";
	$product_code = $_POST['product_code'];
	//echo $product_code;
	$result = $obj_pro_invoice->getProductCd($product_code);
	echo json_encode($result);
}
if($fun == 'product_name')
{
	$product_name = $_POST['product_name'];
	$volume = $_POST['volume'];
	$color = $_POST['color'];
	$result = $obj_pro_invoice->getProductCdAll($_POST['product_name'],$_POST['volume'],$_POST['color']);
	echo json_encode($result);
}
if($fun == 'generateInvoice')
{
	$result = $obj_pro_invoice->updatefreight($_POST['freight_char'],$_POST['proforma_id']);
	echo  encode($_POST['proforma_id']);	
	
}
if($fun == 'gen_sales')
{
	
	$result = $obj_pro_invoice->gen_sales_invoice($_POST['proforma_id']);
	echo  encode($result);
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
//	printr($result);die;
	echo json_encode($result);
}
//manirul 8-4-2017
if($fun == 'checkStock')
{
	$proforma_id = $_POST['proforma_id'];
	$pro_no = $_POST['pr_no'];
	$user_type_id = $_POST['user_type_id'];
	$user_id = $_POST['user_id'];
	
	
	$match = $obj_pro_invoice->getmatchdata($proforma_id,$user_type_id,$user_id,$pro_no,$_POST['admin_user_id']);
	echo $match;
}
//manirul END

//[kinjal] on 21-6-2017
if($fun == 'clone_proforma')
{
	$data = $obj_pro_invoice->clone_proforma($_POST['pro_id']);
	echo $data;
}
if($fun == 'getStockQty')
{
	$result = $obj_pro_invoice->getStockQty($_POST['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'');
	echo json_encode($result);
}
//END [kinjal]

//sonu ADD payment 18-7-2017
if($fun == 'Payment_detail')
{
	$data = $obj_pro_invoice->Payment_detail($_POST['proforma_id']);
	//printr($data);
	$res =array();
		$res =array(
		'receive_amount'=>$data['total'],
		'receive_date'=>$data['payment_receive_date'],
	);
	echo json_encode($res);
}
if($fun=='Payment_detail_for_Customer')
{
	$data = $obj_pro_invoice->Payment_detail_for_Customer($_POST['proforma_id']);
	echo $data;
}
if($fun == 'Edit_payment_details')
{
	$data = $obj_pro_invoice->Payment_detail_edit($_POST['payment_id']);
	echo json_encode($data);
}

if($fun=='remove_payment')
{
	$data = $obj_pro_invoice->remove_payment($_POST['payment_id'],$_POST['proforma_id']);
	echo $data;
}
if($fun=='edit_payment')
{
	$data = $obj_pro_invoice->update_payment_detail($_POST['payment_id'],$_POST['payment_amount'],$_POST['payment_detail'],$_POST['payment_mode'],$_POST['payment_receive_date'],$_POST['payment_type']);
}
//end sonu

//add sonu 25-8-2017 all country price
if($fun == 'productrateAllCountry')
{
	$product_code_id = $_POST['product_code_id'];
	$transportation = $_POST['transportation'];
	$final_qty = $_POST['qty'];
	$country_id = $_POST['country_id'];
	//$t_qty=$_POST['t_qty'];
	
    $result = $obj_pro_invoice->productrateAllCountry($_POST['product_code_id'],$_POST['transportation'],$_POST['qty'],$_POST['country_id'],$_POST['ib_id']);
	//printr($result);
//die;
	if(isset($_POST['t_qty'])&& !empty($_POST['t_qty']))
	{
		$qty=$_POST['t_qty'];
	}else{
		$qty=$final_qty;
	}
	//printr($result);
	if(!empty($result)){
		
		if($_POST['country_id']=='155')
		{
    		if($transportation=='air'){
    			//	printr($transportation);
    			if( $qty < 5000)
    			{
    				$p_rate = $result['air_quantity5000'];
    			}		
    			else if($qty > 5000 && $qty < 10000)
    			{
    				$p_rate = $result['air_quantity5000'];
    			}
    			else
    			{
    				$p_rate = $result['air_quantity10000'];
    			}
    		}else{
    		    if($qty < 1000)
    			{
    			    //	printr($qty);
    				$p_rate = $result['quantity100'];
    			}
    			else if($qty >= 1000 && $qty < 2000)
    			{
    			    //	printr($qty);
    				$p_rate = $result['quantity1000'];
    			}
    			else if($qty >= 2000 && $qty < 5000)
    			{
    			    //	printr($qty);
    				$p_rate = $result['quantity2000'];
    			}
    			else if($qty >= 5000 && $qty < 10000)
    			{
    			    //	printr($qty);
    				$p_rate = $result['quantity5000'];
    			}
    			else
    			{
    				$p_rate = $result['quantity10000'];
    			}
    		}
		}
		else
		{
		    $qty_range = $obj_pro_invoice->getQtyRange($qty,$_POST['ib_id']);
		    $field =explode(',',$qty_range['field']);
		    $prefix ='';
		    if($transportation=='air')
		        $prefix = 'air_';
		    $p_rate = $result[$prefix.''.$field[0]];
		}
		if($result['valve']=='With Valve'){
			if($_POST['country_id']=='42' || $_POST['country_id']=='14')
			    $final_rate=$p_rate+0.10;
			else
			    $final_rate=$p_rate+1.20; 
		}else{
			$final_rate=$p_rate;
		}
		
	}else{
		$final_rate=0;
	}
	
	echo $final_rate;

}
if($fun == 'gotopaid')
{
     $result = $obj_pro_invoice->gotopaid($_POST['proforma_id']);
}
if($fun == 'getGussetPrinting') {
	
	//printr($_POST);die;
	$product_id = $_POST['product_id'];
	$data = $obj_pro_invoice->getProduct($product_id);	
	$response = '';	
	
	if(isset($data) && $data['printing_option']==1){	
		$printing_option_type=explode(',',$data['printing_option_type']);
		
		$response.='<div class="form-group">
                <label class="col-lg-3 control-label">Gusset Printing Option</label>
                <div class="col-lg-9">';
		$i=1;
		foreach($printing_option_type as $val)
		{
			$qty = $obj_pro_invoice->getQuantityById($data[$val.'_min_qty']);
		
			//if($i==1) $ch='checked="checked"'; else { $ch='';}
			if($product_id=='3') $ch='checked="checked"';
			if($val=='both')
				$val='Bottom / Side';
		 	
			$response .='<div class="radio">
                      	';
						$val_cust = 'Front & Back  +  '.ucfirst($val).' Gusset Printing';
						//printr($val_cust);
						if($_POST['n']==1 && $_POST['gusset_printing_option']==$val_cust)
						{
							
							
							$response .='<label><input type="radio" checked="checked"  class="printing_option_type" name="printing_option_type"  id="pType'.$i.'" value="Front & Back  +  '.ucfirst($val).' Gusset Printing"> Front & Back  +  '.ucfirst($val).' Gusset Printing </label>';
							//printr($response);
						}
						else if($i==1)
							$response .='<label><input type="radio" checked="checked" class="printing_option_type" name="printing_option_type"  id="pType'.$i.'" value="Front & Back  +  '.ucfirst($val).' Gusset Printing"> Front & Back  +  '.ucfirst($val).' Gusset Printing </label>';

						else
							$response .='<label><input type="radio" class="printing_option_type" name="printing_option_type"  id="pType'.$i.'" value="Front & Back  +  '.ucfirst($val).' Gusset Printing" > Front & Back  +  '.ucfirst($val).' Gusset Printing </label>
                    </div>';
					//printr($response);
			$i++;
		}
		$response.='</div>
              </div>';		
	}
	else
	{
		$response ='';	
	}
		$printing_effect = $obj_pro_invoice->getActivePrintingEffectEnquiry();
		$response.=' <div class="form-group" >
                        <label class="col-lg-3 control-label">Select Printing Option</label>
                        <div class="col-lg-3">
                        	<select name="printing" id="printing" class="form-control validate[required]">';
							if($printing_effect){
								$response .= '<option value="">Select Effect</option>';
								 foreach($printing_effect as $key=>$val){
									if($_POST['n']==1 && $_POST['printing_option']==$val['printing_effect_id'])
										$response .= '<option value="'.$val['printing_effect_id'].'" selected =selected>'.$val['effect_name'].'</option>';
									else
										$response .= '<option value="'.$val['printing_effect_id'].'">'.$val['effect_name'].'</option>';
								  } 
							  }
                                
								
                             $response.=' </select>
                        </div>
                      </div>';	
	
	echo $response;
}
if($fun == 'productweight')
{
     $result = $obj_pro_invoice->productweight($_POST['product_code_id']);
	 echo $result;
}
//[kinjal] made on (3-5-2018)
if($fun == 'getDigitalPrice')
{
     $result = $obj_pro_invoice->getDigitalPrice($_POST['product_code_id'],$_POST['qty'],$_POST['plate'],$_POST['rate'],$_POST['country_id']);
	 echo $result;
}
//END [kinjal]
 
if($fun == 'gen_sales_india')
{
    $result = $obj_sales_invoice->add_sales_invoice($_POST['proforma_id'],'1');
	echo  encode($result);
}
if($_GET['fun']=='getEmpList')
{
     $ib = $_POST['ib'];
     $getEmplist = $obj_pro_invoice->getEmpList($ib);
     $html = '';
     $html.= '<option value="">Select User</option>';
     foreach($getEmplist as $list){
          $html .='<option value="2='.$list['employee_id'].'">'.$list['user_name'].'</option>';
     }
     $html .='<option value="4='.$ib.'">'.$_POST['name'].'</option>';
     echo $html;
}
if($fun == 'getreport') 
{
    parse_str($_POST['formData'], $post);
    $data=$obj_pro_invoice->getreport($post,$_POST['num']);
    echo $data;
}
if($fun == 'send_mail_customer') 
{
    parse_str($_POST['formData'], $post);
    $post['url'] = HTTP_SERVER.'pdf/proformapdf.php?mod='.encode('proforma_invoice').'&token='.rawurlencode(encode($post['proforma_id_send'])).'&ext='.md5('php').'&num=0&goods_status=0';
    $data=$obj_pro_invoice->send_mail_customer($post,0);
    echo 1;
}
if($fun == 'addpaymentAndClose')
{ 
    parse_str($_POST['formData'], $post);
	$data_val= $_POST['data_val']; 
	$url='';
	$result = $obj_pro_invoice->InsertPayment_detail($post,'');
	echo  $url;
}
if($fun == 'generate_pro')
{
	$data_val= $_POST['data_val'];
	$proforma_id= $_POST['proforma_id'];
	$url='';
	$obj_pro_invoice->updatefreight($_POST['freight_char'],$_POST['proforma_id']);
	$obj_pro_invoice->saveProformaStatus($proforma_id);
	$url = $email_url = HTTP_SERVER.'pdf/proformapdf.php?mod='.encode('proforma_invoice').'&token='.rawurlencode(encode($proforma_id)).'&ext='.md5('php').'&num=0';
	$obj_pro_invoice->sendInvoiceEmail($proforma_id,'tech@swisspack.co.in',$email_url,'0');
	if($data_val=='payment')
		$url = $obj_general->link($_POST['rout'], '&mod=add_payment&proforma_id='.encode($proforma_id).'&is_delete=0', '',1);
	else if($data_val=='transfer')
		$url = $obj_general->link('transfer_invoice', '&mod=add&proforma_id='.encode($proforma_id), '',1);
	
	echo  $url;
}
if($fun == 'check_customer') {
	$result = $obj_pro_invoice->$fun($_POST['email']);
    $sales_person_name = $obj_pro_invoice->getUser($result['user_id'],$result['user_type_id']);
	if($result)
	{
	    if($sales_person_name['name']!=''){
	      echo 'This user is already registered by '.$sales_person_name['name'];
	     }else{
	        echo 'This user is already registered ';
	     }
	}
	else
	{
	    echo '';
	}
} 
if($fun == 'savedispatch_racknotify')
{
	parse_str($_POST['formData'], $postdata);
	foreach($postdata as $id=>$data)
	{	
	    if(is_numeric($id))
		{
			$stk_id=array();
			foreach($data['box'] as $box)
			{   
				$val = explode('==',$box);//printr($val);
				$stk_id = $val[1];
				$data['parent_id'] = $val[1];
				$data['alldata'] = $val[3].'='.$val[4].'='.$val[5];
				$rack_data=$obj_rack_master->getRackQty($data['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'','',$data,1);
				//printr($rack_data);die;
				$data['stock_id']=$rack_data[0]['grouped_stock_id'];
				$data_insert=$obj_rack_master->$fun($data,1);
			}
		}
	} 
	echo 1;
} 
if($fun == 'getprepaymentreport') 
{
    parse_str($_POST['formData'], $post);
    $data=$obj_pro_invoice->getprepaymentreport($post,$_POST['num']);
    echo $data;
}if($fun == 'getpaymentreport') 
{
    parse_str($_POST['formData'], $post);
    $data=$obj_pro_invoice->getpaymentreport($post,$_POST['num']);
    echo $data;
}
?>