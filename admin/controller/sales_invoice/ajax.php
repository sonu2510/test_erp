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
if($fun == 'check_purchaseno')
{
	$purchaseinvoice_no = $_POST['purchase_no'];
	$no = $obj_invoice->$fun($purchaseinvoice_no);
	echo $no;
}
if($fun == 'check_proforma')
{
	$proforma_no = $_POST['proforma_no'];
	$no = $obj_invoice->$fun($proforma_no);
	echo $no;
}
if($fun == 'removeInvoice'){

	
	//printr($_POST);
	//die;
	$data=$obj_invoice->removeInvoice($_POST);
	echo $data;

}
if($fun == 'csvInvoice'){
parse_str($_POST['formData'], $post);
	//printr($post['post']);die;
	$csv=$obj_invoice->invoiceArrayForCSV($post['post']);
	//printr($csv);
	//die;
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
		//'TotalDiscount',
		'Sent',
		'Status',
	)
);
//printr($input_array);die;
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
	//printr($LastId);die;
	$invoice = $obj_invoice->getInvoiceData($LastId);
	//mansi 10-2-2016
	$final_amount = $obj_invoice->updateTotalInvoiceAmount($LastId);
	$case=$label=$prepress='';
	$invoice_detail = $obj_invoice->getInvoiceProduct($LastId); 
	$html .='<input type="hidden" id="invoice_id" name="invoice_id" value="'.$LastId.'"/>
				<input type="hidden" id="invoice_id_max_sec" name="invoice_id_max" value="'.$LastId.'"/>
				<input type="hidden" id="new_inv_amt" name="new_inv_amt" value="'.$invoice['payment_terms'].'"/>
				<input type="hidden" id="invoice_no" name="invoice_no" value="'.$invoice['invoice_no'].'">';
	$html .= '<h4><i class="fa fa-plus-circle"></i> Added Invoice</h4>';
         $html .= '<div class="line m-t-large" style="margin-top:-4px;"></div><br/>';
		
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th> Quantity </th>';
			$html .= '<th>Rate</th>';
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  //$p_code=array();
			foreach($invoice_detail as $detail) {
			//printr($detail);
			//die;
			//$p_code[]= $detail['product_code_id'];
				$html .= '<input type="hidden" id="prd_code_id" name="prd_code_id[]" value="'.$detail['product_code_id'].'"/>
				<input type="hidden" id="invoice_id_encode" value="'.encode($detail['invoice_id']).'"/>';
				$product_code= $obj_invoice->getProductCode($detail['product_code_id']);
				  $html .= '<tr id="invoice_product_id_'.$detail['invoice_product_id'].'">';
				  //$product = $obj_invoice->getActiveProductName($detail['product_id']);
				  $html .= '<td><b>';
				if($detail['product_code_id'] == '-2')
				{
					$html.='Cylinder <br>';
				}elseif($detail['product_code_id'] == '-1')
				{
					$html.='Custom <br>'.$detail['product_description'];
				}
				elseif($detail['product_code_id'] == '1194')
				{
					$html.='Sample <br>';	
				}
				elseif($detail['product_code_id'] == '0' )
				{
					if($detail['rate']!='0' )
					{
						$html.='Freight Charges : '.$detail['rate'];
						$html.='<input type="hidden" id="fright_product" value="0"  />';
					}
					if($detail['case_breaking_fees']!='0' )
					{
						$html.='<br>Case Breaking Charges : '.$detail['case_breaking_fees'];
						$html.='<input type="hidden" id="case_breaking_fees" value="0"  />';
						$case = '<br>'.$detail['case_breaking_fees'];
					}
					if($detail['label_charges']!='0' )
					{
						$html.='<br>Label Charges : '.$detail['label_charges'];
						$html.='<input type="hidden" id="label_charges" value="0"  />';
						$label = '<br>'.$detail['label_charges'];
					}
					if($detail['prepress_charges']!='0' )
					{
						$html.='<br>Prepress Charges : '.$detail['prepress_charges'];
						$html.='<input type="hidden" id="prepress_charges" value="0"  />';
						$prepress = '<br>'.$detail['prepress_charges'];
					}
					if($detail['extra_charge_name']!='0' && $detail['extra_charge']!='0' )
					{
						$html.='<br>'.$detail['extra_charge_name'].':'.$detail['extra_charge'];
						$html.='<input type="hidden" id="extra_charge" value="0"  />';
						$prepress = '<br>'.$detail['extra_charge'];
					}
			
				}
				
			
				else
				{
				 	$html.=$product_code['product_name'].'<br>'.$product_code['product_code'];
				}
				 $html .= '</b><input type="hidden" id="invoice_pcode_'.$detail['invoice_product_id'].'" value="'.$detail['product_code_id'].'"  /></td>';
				 $html .= '<td>';
				 $html.=$detail['qty'];
				  $html .= '<input type="hidden" id="invoice_qty_'.$detail['invoice_product_id'].'" value="'.$detail['qty'].'"  /></td>';		
				  $html.='<td>';	
				  if($detail['rate']!='0.0000')
						$html.=$detail['rate'];
						
				   $html.=$case;
				   $html.=$label;
				   $html.=$prepress;
				   $html.='<input type="hidden" id="invoice_rate_'.$detail['invoice_product_id'].'" value="'.$detail['rate'].'"  /></td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice('.$detail['invoice_product_id'].','.$detail['invoice_id'].')"><i class="fa fa-trash-o"></i></a>';
				  //if($post['edit'] != '')
				 // {
				   $html .= '<a href="'.$obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice['invoice_id']).'&invoice_product_id='.encode($detail['invoice_product_id']).'&is_delete=0','',1).'"  name="btn_edit" class="btn btn-info btn-xs btn_edit" id="btn_edit">Edit</a></td>';
				//  }
				 
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
	//die;
	//mansi 10-2-2016
//	$final_amount = $obj_invoice->updateTotalInvoiceAmount($invoice_no);
	echo $invoiceUpdate;
}
if($fun == 'updateInvoiceProduct') {
	//echo "hiii";
	$html = '';
	parse_str($_POST['formData'], $postdata);
	//printr($postdata);die;
	$invoice_update = $obj_invoice->updateInvoiceProduct($postdata);
	//die;
	$invoice = $obj_invoice->getInvoiceData($postdata['invoice_id']);
	//mansi 10-2-2016
	$final_amount = $obj_invoice->updateTotalInvoiceAmount($postdata['invoice_id']);
	//die;
	$invoice_detail = $obj_invoice->getInvoiceProduct($invoice['invoice_id']); 
	//printr($invoice_detail);die;
	$html .= '<div class="line m-t-large" style="margin-top:-4px;"></div><br/>';
		
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th>Quantity</th>';
			$html .= '<th>Rate</th>';
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  $payment_amt ='0';
		  $case=$label=$prepress='';
		  foreach($invoice_detail as $detail) { 
		  	
				//if($detail['product_code_id'] != '0')
				//{	
					$cal_pay_terms =$detail['qty']*  $detail['rate'];
					$payment_amt = ($payment_amt+ ($cal_pay_terms+(($cal_pay_terms)*$invoice['tax_maxico']/100)));
				//}
				//echo $payment_amt;
		  $html .= '<input type="hidden" id="prd_code_id" name="prd_code_id[]" value="'.$detail['product_code_id'].'"/>
		  <input type="hidden" id="invoice_id_encode" value="'.encode($detail['invoice_id']).'"/>';
		  		  $html .= '<tr id="invoice_product_id_'.$detail['invoice_product_id'].'">';
				  $product_code= $obj_invoice->getProductCode($detail['product_code_id']);
				  $html .= '<tr id="invoice_product_id_'.$detail['invoice_product_id'].'">';
				  //$product = $obj_invoice->getActiveProductName($detail['product_id']);
				  $html .= '<td><b>';
				 if($detail['product_code_id'] == '-2')
				{
					$html.='Cylinder <br>';
				
				}
				elseif($detail['product_code_id'] == '-1')
				{
					$html.='Custom <br>'.$detail['product_description'];
				}
				elseif($detail['product_code_id'] == '1194')
				{
					$html.='Sample <br>';
				}
				elseif($detail['product_code_id'] == '0')
				{
					if($detail['rate']!='0.0000')
					{
						$html.='Freight Charges : '.$detail['rate'];
					}
					if($detail['case_breaking_fees']!='0.0000')
					{
						$html.=' <br>Case Breaking Charges : '.$detail['case_breaking_fees'];
						$case = '<br>'.$detail['case_breaking_fees'];
					}
					if($detail['label_charges']!='0.0000')
					{
						$html.=' <br>Label Charges : '.$detail['label_charges'];
						$lablel = '<br>'.$detail['label_charges'];
					}
					if($detail['prepress_charges']!='0.0000')
					{
						$html.=' <br>Prepress Charges : '.$detail['prepress_charges'];
						$prepress = '<br>'.$detail['prepress_charges'];
					}
					if($detail['extra_charge_name']!='0' && $detail['extra_charge']!='0' )
					{
						$html.='<br>'.$detail['extra_charge_name'].':'.$detail['extra_charge'];
						$html.='<input type="hidden" id="extra_charge" value="0"  />';
						$prepress = '<br>'.$detail['extra_charge'];
					}
					
				}
				else
				{
				 	$html.=$product_code['product_name'].'<br>'.$product_code['product_code'];
				}
				// $html.=$detail['product_name'].'<br>'.$product_code['product_code'];
				  $html .= '</b><input type="hidden" id="invoice_pcode_'.$detail['invoice_product_id'].'" value="'.$detail['product_code_id'].'"  /></td>';
				  $html .= '<td>';
				 $html.=$detail['qty'];
				  $html .= '<input type="hidden" id="invoice_qty_'.$detail['invoice_product_id'].'" value="'.$detail['qty'].'"  /></td>';		
				  $html.='<td>';	
				   if($detail['rate']!='0.0000')
						$html.=$detail['rate'];
						
				   $html.=$case;
				    $html.=$label;
					 $html.=$prepress;
				   
				   	
				 //  $html.=$detail['rate'].''.$case;
				   $html.='<input type="hidden" id="invoice_rate_'.$detail['invoice_product_id'].'" value="'.$detail['rate'].'"  /></td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice('.$detail['invoice_product_id'].')"><i class="fa fa-trash-o"></i></a>
				  <a href="'.$obj_general->link($rout, 'mod=add&invoice_no='.encode($postdata['invoice_id']).'&invoice_product_id='.encode($detail['invoice_product_id']).'','',1).'"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
				  </td>';
				  $html .= '</tr>';
		  }
			  $html .= '</tbody></table>';
		//printr($payment_amt);
		if($payment_amt!='0')
		{
			$update_payment = $obj_invoice->updatePaymentTerms($invoice['invoice_id'],$payment_amt); 
		}
		//echo $html;
		$arr['payment_amt']=$payment_amt;
		$arr['response']=$html;
		echo json_encode($arr);
		 
}
if($fun == 'update_boxno')
{
	$data = array('box_no'=>$_POST['postArray']['box_no'],
	'gen_unique_id'=>$_POST['postArray']['gen_unique_id'],
	);
	$result1 = $obj_invoice->updateBoxno($data);
	echo $result1;
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
	//printr($_POST);
	$obj_invoice->$fun($_POST);
}
if($fun == 'getComb')
{
	$com='<select name="detail[]" id="detail" multiple="multiple">';
                 
						$parent_id=0;
						$str=' AND (pallet_id=0 OR pallet_id="'.$_POST['pallet_id'].'")';
						$colordetails=$obj_invoice->colordetails($_POST['invoice_id'],$parent_id,$str);
						if(isset($colordetails) && !empty($colordetails))
						{
							foreach($colordetails as $color)
							{
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
if($fun == 'getProductCode')
{
	$product_code_id = $_POST['product_code'];
	$no = $obj_invoice->$fun($product_code_id);
	$product_code=$no['description'];
	echo $product_code;
}
if($fun == 'customer_detail'){
	$customer_name = $_POST['customer_name'];
	$result = $obj_invoice->getCustomerDetail($customer_name);
	echo json_encode($result);
}

// change for stock available quantity

if($fun == 'product_code'){
	$product_code = $_POST['product_code'];
	$result = $obj_invoice->getProductCd($product_code);
	//printr($result);die;
	echo json_encode($result);
}

if($fun == 'client_name'){
	$client_name = $_POST['client_name'];
	$result = $obj_invoice->getClientName($client_name);
	echo json_encode($result);
}
if($fun == 'generateInvoice')
{
	parse_str($_POST['formData'], $postdata);
	//printr($postdata);die;
	$obj_invoice->InsertGeneratedData($postdata,$_POST['country_final']);
	
	$invoice_data = $obj_invoice->getInvoiceData($postdata['invoice_id']);
	$addedByinfo=$obj_invoice->getUser($invoice_data['user_id'],$invoice_data['user_type_id']);
	// mansi 10-2-2016
	$final_amount = $obj_invoice->updateTotalInvoiceAmount($postdata['invoice_id']);
	echo encode($invoice_data['invoice_id']);
	
}
if($fun == 'gettaxinvoice')
{
	$inv_id = $_POST['inv_id'];
	$tax = $_POST['tax'];
	$amt_tot = $obj_invoice->updatetax_invoice($inv_id,$tax);
	echo $amt_tot;
}
if($fun == 'getStockQty')
{
	$result = $obj_invoice->getStockQty($_POST['product_code_id'],$_POST['pro_no']);
	echo json_encode($result);
}
if($fun == 'state_detail')
{
	$detail=$obj_invoice->stateDetail($_POST['state_id']);
	$gst = $detail['gst'];
	$pst = $detail['rst'];
	$hst = $detail['hst'];
	echo json_encode($detail);
	
}

// credit note

if($fun == 'creditNote')
{
	$result=$obj_invoice->getInvoiceProduct($_POST['invoice_id']);
	$count = count($result);
	$cre_inv=$obj_invoice->getCredit($_POST['invoice_id']);
	$id=array();
	if(!empty($cre_inv))
	{
		foreach($cre_inv as $cre_data)
		{
			$id[$cre_data['cre_no']] = $cre_data['sales_credit_note_id'];
		}
	}
	$i=0;
	$response = '';
	$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
		$response .= '<table class="table table-striped m-b-none text-small">';
			$response .= '<thead>';
				$response .= '<tr>'; 
					$response .= ' <th></th>';
					$response .= ' <th>Product Code</th>';
					$response .= ' <th>Qty</th>';
					if(!empty($cre_inv))
						$response .= ' <th>Refund Amount</th>';
				$response .= '</tr>';
			$response .= '</thead>'; 
		$response .= '<tbody>'; 
		foreach($result as $key=>$data)
		{   $product_code= $obj_invoice->getProductCode($data['product_code_id']);
			$invoice_product_id=$obj_invoice->getCreditNoteDetail($data['invoice_product_id']);
		
			if($data['product_code_id'] == '-1')
			{
				$product='Custom ('.$data['product_description'].')';
			}
			elseif($data['product_code_id'] == '0')
			{
				$product='Freight Charges';
			}
			elseif($data['product_code_id'] == '1194')
			{
				$product.='Sample <br>';
			}
			else
			{
				$product=$product_code['product_code'].'<br>'.$product_code['product_name'];
			}
			
			$response .= '<tr>';
			if(empty($invoice_product_id))
				$response .= '<td><input type="checkbox" name="post[]" id="check_'.$data['invoice_product_id'].'" value="'.$data['invoice_product_id'].'" onchange="getGenId('.$data['invoice_product_id'].')"></td>';
			else
				$response .= '<td>'.$invoice_product_id['cre_no'].'</td>';
				
				$response .= '<td>'.$product.'</td>';
				
			if(empty($invoice_product_id))
				$qty = $data['qty'];
			else
				$qty = $invoice_product_id['qty'];
				
			
				$response .= '<td><input type="text" name="qty_change_'.$data['invoice_product_id'].'" id="qty_change_'.$data['invoice_product_id'].'" value="'.$qty.'" class="form-control validate" width="21%" readonly="readonly" onchange="check_saleqty('.$data['invoice_product_id'].')"><input type="hidden" name="qty_inv_'.$data['invoice_product_id'].'" id="qty_inv_'.$data['invoice_product_id'].'" value="'.$data['qty'].'" width="21%" ></td>';
				
				
				if(!empty($invoice_product_id))
				{	
					$i+=1;
					
					if($id[$invoice_product_id['cre_no']] == $invoice_product_id['sales_credit_note_id']) 
					{
						
						$response .= '<td><input type="text" name="refund_'.$data['invoice_product_id'].'" id="refund_'.$data['invoice_product_id'].'" value="'.$invoice_product_id['refund_amount'].'" class="form-control validate" readonly="readonly"></td>';
					}
					
					$response .= '<td><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeCredit('.$invoice_product_id['sales_credit_note_id'].')"><i class="fa fa-trash-o"></i></a>
								 <a id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs" onclick="edit_cre_qty('.$invoice_product_id['sales_credit_note_id'].','.$data['invoice_product_id'].','.$invoice_product_id['invoice_id'].','.$invoice_product_id['sr_no'].');">Edit</a></td>';
				}
				
			$response .= '</tr>';	
			//$i++;
		}
		//echo $i;
		$response .= '<input type="hidden" name="total" id="total" value="'.$i.'='.$count.'"></tbody>'; 
		$response .= '</table>'; 
	$response .= '</div>';
	
	echo $response;
}
if($fun == 'genCreditNote')
{
	parse_str($_POST['formData'], $postdata);
	//printr($postdata);die;
	$result = $obj_invoice->genCreditNote($postdata);
	echo $result;
}
if($fun == 'removeCredit')
{
	
	$result = $obj_invoice->removeCredit($_POST['sales_credit_note_id']);
	echo $result;
}
if($fun == 'edit_cre_qty')
{
	$result = $obj_invoice->edit_cre_qty($_POST['sales_credit_note_id'],$_POST['qty'],$_POST['refunt_amt'],$_POST['invoice_id'],$_POST['sr_no']);
	echo $result;
}
if($fun == 'product_name')
{
	$product_name = $_POST['product_name'];
	$volume = $_POST['volume'];
	$color = $_POST['color'];
	$result = $obj_invoice->getProductCdAll($_POST['product_name'],$_POST['volume'],$_POST['color']);
	echo json_encode($result);
}
if($fun == 'showSalesProduct')
{
	$result=$obj_rack_master->getSalesInvoiceProduct($_POST['invoice_id'],'');
	$country = $obj_rack_master->getSalesInvoiceCountry($_POST['invoice_id'],'');//printr($country);die;
	
	$id=array();
	//echo $_POST['invoice_no'];
	$inv_no = '"'.$_POST['invoice_no'].'"';
	$proforma_no = '"'.$_POST['proforma_no'].'"';
	$customer_name = '"'.str_replace("'","&apos;",$_POST['customer_name']).'"';
	$i=0;
	if(!empty($result))
	{
	$response = '';
	$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
		$response .= '<table class="table table-striped m-b-none text-small">';
			$response .= '<thead>';
				$response .= '<tr>'; 
					$response .= ' <th></th>';
					$response .= ' <th>Product Code</th>';
					$response .= ' <th>Qty</th>';
					$response .= ' <th>Rack Infomation</th>';
					//if(!empty($cre_inv))
						//$response .= ' <th>Refund Amount</th>';
					$response .= '</tr>';
				$response .= '</thead>'; 
			$response .= '<tbody>'; 
			            $a=array();
						$r_qty=array();
						foreach($result as $key=>$data)
						{
							$response .= '<tr>';
								$response .= '<td><input type="checkbox" name="post_sales[]" id="sales_'.$data['invoice_product_id'].'" value="'.$data['invoice_product_id'].'" onchange="getGenSalesId('.$data['invoice_product_id'].')"></td>';
								$response .= '<td>'.$data['product_code'].'</td>';
								$response .= '<td>'.$data['rack_remaining_qty'].'</td>';
								$rack_qty = $obj_rack_master->getRackQty($data['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'','');
								//printr($rack_qty);
								$r_no=array();
								$response .='<td>
												<table border="1">
													<th>Rack Name</th>
													<th>Rack Position</th>
													<th>Qty</th>';
												if(!empty($rack_qty))
												{
													foreach($rack_qty as $rack)
													{
														$d=1;
														$rc = $rack['row'].'@'.$rack['column_name'];
														for($i=1;$i<=$rack['g_row'];$i++)
														{
															for($r=1;$r<=$rack['g_col'];$r++) 
															{
																$n = $i.'@'.$r;
																if($rc==$n)
																{
																	$col_row = $rc;
																	$k=$d;
															    	$r_no[]=$k;	
																}
																$d++;
															}
														}
														
														//
														$dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['stock_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
													
													//  printr($rack_qty);
														$lable = $obj_rack_master->getLabel($col_row,$rack['goods_master_id']);
														$rm_qty=$rack['store_qty']-$dispatch_qty['total'];
														$l =$k;
														if($lable!='')
														    $l = $lable;
														$response .='<tr><td>'.$rack['name'].'</td>
																		<td align="center">'.$l.'</td>
																		<td>'.$rm_qty.'</td></tr>';
														$a[]=$k;
														$r_qty[] =$rm_qty.'='.$k;
														$goods_id[] = $rack['goods_master_id'];
													}
												}
    											    	$rack_no = array_unique($r_no);
    													$rack_no_array= implode(',',$rack_no);
    													
    													$rack_qty = array_unique($r_qty);
    													$rack_qty_array= implode('&',$rack_qty);											
    													
    													
    
    													//printr($g_no_array);
    													$response.='<input type = "hidden" name = "rack_no_'.$data['invoice_product_id'].'" id = "rack_no_'.$data['invoice_product_id'].'" value="'.$rack_no_array.'"/>';
    													$response.='<input type = "hidden" name = "rack_qty_'.$data['invoice_product_id'].'" id = "rack_qty_'.$data['invoice_product_id'].'" value="'.$rack_qty_array.'"/>';
												$response .='</table>
											</td>';
								
								
								$goods_master = $obj_goods_master->getGoodsMaster('','',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$goods_id);
								$response .='<td><select name="rack_sales_'.$data['invoice_product_id'].'" id="rack_sales_'.$data['invoice_product_id'].'" onchange="get_pallet_sales('.$data['invoice_product_id'].','.$data['product_code_id'].')" style="display:none;width:inherit;" class="form-control">
													<option>Select Pallet</option>';
													foreach($goods_master as $gd)
													{
														$response .= '<option value="'.$gd['row'].'='.$gd['column_name'].'='.$gd['goods_master_id'].'">'.$gd['name'].'</option>';
													}
													
								$response .='</select></td>';
							$response .='<td id="rack_number_sales_'.$data['invoice_product_id'].'"></td>';
							$response .="<td><a id='btn_done_sales_".$data['invoice_product_id']."'  name='btn_done_sales_".$data['invoice_product_id']."' class='btn btn-info btn-xs' onclick='dis_rack_sales(".$data['invoice_product_id'].",".$inv_no.",".$proforma_no.",".$customer_name.",".$data['rack_remaining_qty'].",".$data['product_id'].",".$data['product_code_id'].",".$data['invoice_id'].",".$country.")' style='display:none;'>Dispatch</a></td>";
							$response .= '</tr>';
						}
										
			$response .= '</thead>'; 
		$response .= '<tbody>'; 
		
		//echo $i;
		$response .= '</tbody>'; 
		$response .= '</table>'; 
	$response .= '</div>';
	echo $response;
	}
	else
	{
	  	echo $response='';
		
	}
}
if($_GET['fun']=='getLabel') {
    
    $rack_array=$_POST['rack_array'];
    $sel = '';
	 $sel.= '<select name="pallet_sales_'.$_POST['invoice_product_id'].'" id="pallet_sales_'.$_POST['invoice_product_id'].'" style="width: inherit;" class="form-control"><option>Select Rack</option>';
				$d = 1;
				for($t=0; $t<=$_POST['length']; $t++){
					for($i=1;$i<=$_POST['row'];$i++)
					{
						for($r=1;$r<=$_POST['col'];$r++) 
						{
							if($rack_array[$t] == $d){	
							    
								$rowcol = ''.$i.'@'.$r.'';	
								
								$data = $obj_rack_master->getLabel($rowcol,$_POST['goods_master_id']);
								$l=$d;
								if($data!='')
								   $l= $data;
								$sel.= '<option value="'.$i.'='.$r.'='.$_POST['goods_master_id'].'='.$d.'">'.$l.'</option>';
								
								$t++;
							}
								$d++;
								
						}
						
					}
				}
				
	  $sel.= '</select>';
	echo $sel;
}
if($fun == 'savedispatch_racknotify')
{
	parse_str($_POST['formData'], $postdata);
	//printr('fdfdfdg');//die;
	$rack_data=$obj_rack_master->getRackQty($postdata['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'','',$postdata);
	//printr($rack_data);die;
	$postdata['stock_id']=$rack_data[0]['grouped_stock_id'];
	//printr($postdata['stock_id']);die;
	
	$data=$obj_rack_master->$fun($postdata);
	
	$res=$obj_rack_master->changeRackStatusSales($postdata['invoice_id']);
	
	echo $data;
}
if($fun == 'show_sales_dispatch_product')
{ 
	$result=$obj_rack_master->getSalesInvoiceProductDispatch($_POST['invoice_no'],'');

//	printr($result);//die;
	$id=array();

	$i=0;
	if(!empty($result))
	{
	$response = '';
	$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
		$response .= '<table class="table table-striped m-b-none text-small">';
			$response .= '<thead>';
				$response .= '<tr>'; 
					$response .= ' <th></th>';
					$response .= ' <th>Product Code</th>';
					$response .= ' <th>Dispatch Date</th>';
					$response .= ' <th>Decription</th>';
					$response .= ' <th>Rack Infomation</th>';
					$response .= '</tr>';
				$response .= '</thead>'; 
			$response .= '<tbody>'; 
			           
							$i=1;
						foreach($result as $key=>$data)
						{
							$response .= '<tr>';
								$response .= '<td></td>';
								$response .= '<td>'.$data['product_code'].'</td>';
								$response .= '<td>'.dateFormat(4,$data['date_added']).'</td>';
								$response .= '<td>'.$data['product_description'].'</td>';
							   // printr($data);
								$goods_master = $obj_goods_master->getGoodsData($data['goods_id']);
						
						 //   printr($goods_master);
								$response .='<td>
												<table border="1">
													<th>Rack Name</th>
													<th>Rack Position</th>
													<th>Qty</th>';
											
														$d=1;
														$rc = $data['row'].'@'.$data['column_name'];
													//	printr($rc);
														for($i=1;$i<=$goods_master['row'];$i++)
														{
															for($r=1;$r<=$goods_master['column_name'];$r++) 
															{
																$n = $i.'@'.$r;
															//	printr($rc);
																if($rc==$n)
																{
																	$col_row = $rc;
																	$k=$d;
															    
																}
																$d++;
															}
														}
														
												//	printr($col_row);
													//printr($data);
														$lable = $obj_rack_master->getLabel($col_row,$data['goods_id']);
														$l =$k;
														if($lable!='')
														    $l = $lable;
														$response .='<tr><td>'.$goods_master['name'].'</td>
																		<td align="center">'.$l.'</td>
																		<td>'.$data['dispatch_qty'].'</td></tr>';
													
												
    												
    												
											$response .='</table>
											</td>';
								
								
							
						
							$response .= '</tr>';
							$i++;
						}
										
			$response .= '</thead>'; 
		$response .= '<tbody>'; 
		
		//echo $i;
		$response .= '</tbody>'; 
		$response .= '</table>'; 
	$response .= '</div>';
	echo $response;
	}
	else
	{
	  	echo $response='';
		
	}
}
?>