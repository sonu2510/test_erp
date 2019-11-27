<?php
include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'updateSourceStatus') {
	$source_id = $_POST['source_id'];
	$status_value = $_POST['status_value'];
	$obj_source->$fun($source_id,$status_value);
}

///// model

if($fun == 'generateInvoice')
{
	echo  encode($_POST['packing_order_id']);	
	
}

if($fun == 'removeInvoice'){

	$result=$obj_source->removeInvoice($_POST['proforma_packing_order_id'],$_POST['packing_order_id']);
}

if($fun == 'getbankDetails') {
	$bank = $obj_source->getbankDetails($_POST['currency_id']);
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
	$invoice = $obj_source->updateInvoice($postdata);
	$pro = $obj_source->getProforma($postdata['packing_order_id']);
	
	$invoice_detail = $obj_source->getProformaInvoice($postdata['packing_order_id']);
			
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th>Size</th>';			
			$html .= '<th>Color : Quantity</th>';
			$html .= '<th>Rate</th>';
			$html .= '<th>Option</th>';
				
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		//  $html .= '<input type="hidden" value="'.$postdata['packing_order_id'].'" name="packing_order_id">';
		  foreach($invoice_detail as $detail ) {
		  		//printr($details);
				$product_code_data = $obj_source->getProductCode($detail['product_code_id']);
				 /* //get spout details
				  $getProductSpout = $obj_source->getSpout(decode($detail['spout']));
				  //get zipper details
				  $getProductZipper = $obj_source->getZipper(decode($detail['zipper']));
				  //get accessorie details
				  $getProductAccessorie = $obj_source->getAccessorie(decode($detail['accessorie']));*/

				  $html .= '<tr id="proforma_packing_order_id_'.$detail['proforma_packing_order_id'].'">';				  
				   $html .= '<td><b>'.$product_code_data['product_code'].'</b><br>'.$detail['product_name'].'</td>';
				  
				  $html .= '<td width="75">';
						$measure = $obj_source->getMeasurementName($detail['measurement']); 
						if($detail['product_code_id']=='-1' || $detail['product_code_id']=='0')
							$html .= $detail['size'].'&nbsp;'.$measure['measurement'].'<br />';
						else
							$html .= $product_code_data['volume'].'&nbsp;'.$product_code_data['measurement'].'<br />';
					$html .= '</td>';
			
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
					
				    $html .= $clr_nm.''.$clr_text.' : '.$detail['quantity'].'<br>';				 
				  $html .= '</td>';
				 
				  $html .= '</td>';
				  $html .= '<td>';
				  //foreach($quantity as $rate_val) {
					  $html .= $detail['rate'].'<br>';
				  //}
				  $html .= '</td>';
				 // $html .= '<td>'.ucwords($getProductSpout['spout_name']).' '.$detail['valve'].'<br>'.$getProductZipper['zipper_name'].' '.ucwords($getProductAccessorie['product_accessorie_name']).'</td>';
				  $html .= '<td>'.ucwords($product_code_data['spout_name']).' '.$product_code_data['valve'].'<br>'.ucwords($product_code_data['zipper_name']).' '.ucwords($product_code_data['product_accessorie_name']).'</td>';
				  
				 
				  		  
				 // $html .= '<td>By '.ucwords(decode($pro['transportation'])).'</td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice('.$detail['proforma_packing_order_id'].','.$detail['packing_order_id'].')"><i class="fa fa-trash-o"></i></a>
				  <a href="'.$obj_general->link($rout, 'mod=add&packing_order_id='.encode($postdata['packing_order_id']).'&proforma_packing_order_id='.encode($detail['proforma_packing_order_id']).'&is_delete=0'.'','',1).'" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
				  </td>';
				  $html .= '</tr>';
				  $html .= '<div class="modal fade" id="alertbox_'.$detail['proforma_packing_order_id'].'">
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
                                    <button type="button" name="popbtnok" id="popbtnok_'.$detail['proforma_packing_order_id'].'" class="btn btn-primary">Ok</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>';
				}
			  $html .= '</tbody></table>';
		  echo $html;
}

if($fun == 'updateProforma') {
	parse_str($_POST['formData'], $postdata);
	$proforma = $obj_source->updateProforma($postdata);
	echo $proforma;
}

if($fun == 'getViewToolprice')
{
	$product_id = $_POST['product_id'];
	$tool_price = $obj_source->getViewToolprice($_POST['product_id']);
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


if($fun == 'product_code'){
	//echo "hi";
	$product_code = $_POST['product_code'];
	//echo $product_code;
	$result = $obj_source->getProductCd($product_code);
	echo json_encode($result);
}
if($fun == 'product_name')
{
	$product_name = $_POST['product_name'];
	$volume = $_POST['volume'];
	$color = $_POST['color'];
	$result = $obj_source->getProductCdAll($_POST['product_name'],$_POST['volume'],$_POST['color']);
	echo json_encode($result);
}



if($fun == 'addPackingOrder'){
	
	$html = '';
	parse_str($_POST['formData'], $post);
//	printr($post);die;
	$LastId = $obj_source->InsertPacking_Order($post);
	//printr($LastId);
	foreach($LastId as $proId) {
		$last = $proId;
		//printr($last);
	}
	
	$pro = $obj_source->getProforma($last['packing_order_id']);
	//printr($pro);
	$html .='<input type="hidden" id="packing_order_id" name="packing_order_id" value="'.$last['packing_order_id'].'"/>';
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
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  
		  
		$getInvoices = $obj_source->getInvoice($last['packing_order_id']);
			  
		
			  $sub_total = 0;
			  foreach($getInvoices as $invoice ) {
					//to save invoice total 	invoice_total
					$sub_total = $sub_total + ($invoice['quantity'] * $invoice['rate']);
					
					$product_code_data = $obj_source->getProductCode($invoice['product_code_id']);
			
				  $html .= '<tr id="proforma_invoice_id_'.$invoice['proforma_packing_order_id'].'">';				  
				  $html .= '<td><b>'.$product_code_data['product_code'].'</b><br>'.$invoice['product_name'].'</td>';
				  

				  $html .= '<td width="75">';
						$measure = $obj_source->getMeasurementName($invoice['measurement']); 
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
					
				    $html .= $clr_nm.''.$clr_text.' : '.$invoice['quantity'].'<br>';				 
				  $html .= '</td>';
				 
				  $html .= '</td>';
				  $html .= '<td>';
					  $html .= $invoice['rate'].'<br>';
				  $html .= '</td>';
				  $html .= '<td>'.ucwords($product_code_data['spout_name']).' '.$product_code_data['valve'].'<br>'.ucwords($product_code_data['zipper_name']).' '.ucwords($product_code_data['product_accessorie_name']).'</td>';
				  
				
				 // $html .= '<td>By '.ucwords(decode($pro['transportation'])).'</td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice('.$invoice['proforma_packing_order_id'].','.$invoice['packing_order_id'].')"><i class="fa fa-trash-o"></i></a><a href="'.$obj_general->link($rout, 'mod=add&packing_order_id='.encode($invoice['packing_order_id']).'&proforma_packing_order_id='.encode($invoice['proforma_packing_order_id']).'&is_delete=0','',1).'"  name="btn_edit" class="btn btn-info btn-xs">Edit</a></td>';
				  $html .= '</tr>';
				  
				  $html .= '<div class="modal fade" id="alertbox_'.$invoice['proforma_packing_order_id'].'">
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
                                    <button type="button" name="popbtnok" id="popbtnok_'.$invoice['proforma_packing_order_id'].'" class="btn btn-primary">Ok</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>';
				  
		 }
		 
		  $html .= '</tbody></table>';
		  
		 echo $html;
}

if($fun == 'invoiced_status_update') {
	$invoiced_status = $_POST['invoiced_status'];
	$packing_order_id = $_POST['packing_order_id'];
	$obj_source->$fun($packing_order_id,$invoiced_status);
}

if($fun == 'csvPacking'){
	parse_str($_POST['formData'], $post);
	$csv=$obj_source->packingArrayForCSV($post['post']);
	echo $csv;

}//added by sonu
if($fun == 'gen_sales')
{
	$proforma=$obj_source->getProformaInvoiceId($_POST['packing_order_id']);
	//printr($proforma_id);die;
	$result = $obj_pro_invoice->gen_sales_invoice($proforma['proforma_id']);
	$status = $obj_source->Sales_status_update($_POST['packing_order_id']);
	echo  encode($result);
}
if($fun == 'checkStock')
{
	$proforma_id = $_POST['proforma_id'];
	$pro_no = $_POST['pr_no'];
	$user_type_id = $_POST['user_type_id'];
	$user_id = $_POST['user_id'];
	
	
	$match = $obj_pro_invoice->getmatchdata($proforma_id,$user_type_id,$user_id,$pro_no);
	echo $match;
}
if($fun == 'product_detail')
{
	$packing_order_id= $_POST['packing_order_id'];
    $html='';
	$html .= '<table class="table table-bordered">';
	  $html .= '<thead>';
		    $html .= '<tr>'; 
			$html .= '<th>Description</th>';
			$html .= '<th>Normal Rate</th>';			
			$html .= '<th>Express Rate</th>';
			$html .= '<th>Qty</th>';
			$html .= '<th>Pedimento Mexico</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  
							 $packing_pro = $obj_source->getProformaInvoice($packing_order_id);
							
										if(isset($packing_pro) & !empty($packing_pro))
										{
    											foreach($packing_pro as $pack)
    											{
    												
    											
    													$html.='<tr>';
    													$html.='<td><b>'.$pack['product_code'].'</b><br>'.$pack['pro_dec'].'</td>';
    													$html.='<td>'. $pack['rate'].'</td>';
    													$html.='<td> '.$pack['express_rate'].'</td>';
    													$html.='<td>'. $pack['quantity'].'</td>';
    													$html.='<td>'. $pack['pedimento_mexico'].'</td>';
    											    	$html.='<tr>';
    										    }
										}
								$html.='</table>';
								
		//	printr($html);					
		 echo $html;
}
?>