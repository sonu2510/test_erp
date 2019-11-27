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
	//printr($post);die;
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
	//printr($post);
	//die;
	$LastId = $obj_invoice->addInvoice($post);
	
	//[kinjal] :  to update freight charge rate (13-2-2016)
	$obj_invoice->changeRateByFrieght($LastId); 
	
	$invoice = $obj_invoice->getInvoiceData($LastId);
	$invoice_detail = $obj_invoice->getInvoiceProduct($LastId); 
	
	$html .='<input type="hidden" id="invoice_id" name="invoice_id" value="'.$LastId.'"/>';
	$html .= '<h4><i class="fa fa-plus-circle"></i> Added Invoice</h4>';
         $html .= '<div class="line m-t-large" style="margin-top:-4px;"></div><br/>';
		
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th> Quantity </th>';
			$html .= '<th>Rate</th>
					 <th>Rate (With Freight charge)</th>';
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  
			foreach($invoice_detail as $detail) {
			//printr($detail);
			//die;
					if($detail['product_code_id']!='-1')
					{
						$product_code= $obj_invoice->getProductCode($detail['product_code_id']);
					}
					else
					{
						$product_code['product_name'] = '';
						$product_code['product_code']='CYLINDER';
					}
					
				//$product_code= $obj_invoice->getProductCode($detail['product_code_id']);
				  $html .= '<tr id="invoice_product_id_'.$detail['invoice_product_id'].'">';
				  //$product = $obj_invoice->getActiveProductName($detail['product_id']);
				  $html .= '<td>';
				
				 $html.=$product_code['product_name'].'<br>'.$product_code['product_code'];
				  $html .= '</td>';
				  $html .= '<td>';
				 $html.=$detail['qty'];
				  $html .= '</td>';		
				  $html.='<td>';		
				   $html.=$detail['without_freight_charge_rate'];
				   $html.='</td>';
				    $html.='<td>';		
				   $html.=$detail['rate'];
				   $html.='</td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice('.$detail['invoice_product_id'].','.$invoice['invoice_id'].')"><i class="fa fa-trash-o"></i></a>';
				  //if($post['edit'] != '')
				 // {
				   $html .= '<a href="'.$obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice['invoice_id']).'&invoice_product_id='.encode($detail['invoice_product_id']).'','',1).'"  name="btn_edit" class="btn btn-info btn-xs btn_edit" id="btn_edit">Edit</a></td>';
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
	echo $invoiceUpdate;
}
if($fun == 'updateInvoiceProduct') {
	$html = '';
	parse_str($_POST['formData'], $postdata);
	$invoice_update = $obj_invoice->updateInvoiceProduct($postdata);
	
	//[kinjal] :  to update freight charge rate (13-2-2016)
	$obj_invoice->changeRateByFrieght($postdata['invoice_id']); 
	
	$invoice = $obj_invoice->getInvoiceData($postdata['invoice_id']);
	$invoice_detail = $obj_invoice->getInvoiceProduct($invoice['invoice_id']); 
	$html .= '<div class="line m-t-large" style="margin-top:-4px;"></div><br/>';
		
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			$html .= '<th>Product Name</th>';
			$html .= '<th>Quantity</th>';
			$html .= '<th>Rate</th>
					 <th>Rate (With Freight charge)</th>';
			$html .= '<th>Action</th>';
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		  
		  foreach($invoice_detail as $detail) { 
		  		  $html .= '<tr id="invoice_product_id_'.$detail['invoice_product_id'].'">';
				 	if($detail['product_code_id']!='-1')
					{
						$product_code= $obj_invoice->getProductCode($detail['product_code_id']);
					}
					else
					{
						$product_code['product_name'] = '';
						$product_code['product_code']='CYLINDER';
					}
				//  $product_code= $obj_invoice->getProductCode($detail['product_code_id']);
				  $html .= '<tr id="invoice_product_id_'.$detail['invoice_product_id'].'">';
				  //$product = $obj_invoice->getActiveProductName($detail['product_id']);
				  $html .= '<td>';
				
				 $html.=$product_code['product_name'].'<br>'.$product_code['product_code'];
				  $html .= '</td>';
				  $html .= '<td>';
				 $html.=$detail['qty'];
				  $html .= '</td>';		
				  $html.='<td>';		
				   $html.=$detail['without_freight_charge_rate'];
				   $html.='</td>';
				   $html.='<td>';		
				   $html.=$detail['rate'];
				   $html.='</td>';
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
	printr($_POST);
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
	//printr($no);
	$product_code=$no['description'];
	echo $product_code;
}
if($fun == 'product_code'){
	//echo "hi";
	$product_code = $_POST['product_code'];
	$result = $obj_invoice->getProductCd($product_code);
	echo json_encode($result);
}
if($fun == 'generateInvoice')
{
	echo  encode($_POST['invoice_id']);	
	
}
if($fun == 'product_name')
{
	$product_name = $_POST['product_name'];
	$volume = $_POST['volume'];
	$color = $_POST['color'];
	$result = $obj_invoice->getProductCdAll($_POST['product_name'],$_POST['volume'],$_POST['color']);
	echo json_encode($result);
}
?>