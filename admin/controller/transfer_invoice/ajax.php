<?php
include("mode_setting.php");
$fun = $_GET['fun'];
if($fun == 'product_desc'){
	$product_code_id = $_POST['product_code_id'];
	$result = $obj_invoice->getProductCd($product_code_id);
//	printr($result);
	echo $result['description'];
}
if($fun == 'delQty'){

	$invoice_product_id = (int)$_POST['invoice_product_id']; 
	$obj_invoice -> $fun($invoice_product_id);
}
if($fun == 'updateInvoice')
{	
	$invoice_no = $_POST['invoice_no'];
	$status = $_POST['status_value'];
	$obj_invoice->$fun($invoice_no,$status);
}
if($fun == 'place_order')
{
	$transfer_invoice_id = $_POST['transfer_invoice_id'];
	$obj_invoice->send_mail($transfer_invoice_id,$_POST['admin_email'],'place',$_POST['trans_no']);
}
if($fun == 'showProducts')
{
	$invoice = $obj_invoice->getInvoiceData($_POST['transfer_invoice_id']);
	//printr($invoice);
	$result=$obj_invoice->getInvoiceProduct($_POST['transfer_invoice_id'],$n='0');
	$id=array();
	//echo $_POST['invoice_no'];
	$pro_inv_no = "'".$_POST['proforma_no_model']."'";
	$sales_no = "'".$_POST['sales_no']."'";
	$rack_no = "'".$invoice['rack_no']."'";
	$cust_nm = "'".addslashes($invoice['customer_name'])."'";
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
						//if(!empty($cre_inv))
							//$response .= ' <th>Refund Amount</th>';
						$response .= '</tr>';
					$response .= '</thead>'; 
				$response .= '<tbody>'; 
							$a=array();
								$r_qty=array();
							foreach($result as $key=>$data)
							{
								$product_code = $obj_invoice->getProductCd($data['product_code_id']); 
								$response .= '<tr>';
									$response .= '<td><input type="checkbox" name="post_trans[]" id="trans_'.$data['invoice_product_id'].'" value="'.$data['invoice_product_id'].'" onchange="getGenTransId('.$data['invoice_product_id'].')"></td>';
									$response .= '<td>'.$product_code['product_code'].'</td>';
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
        													
        													  //printr($dispatch_qty);
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
    													        $response.='<input type = "hidden" name = "rack_no_'.$data['invoice_product_id'].'" id = "rack_no_'.$data['invoice_product_id'].'" value="'.$rack_no_array.'"/>';
													$response .='</table>
												</td>';
									
									
									$goods_master = $obj_goods_master->getGoodsMaster('','',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$goods_id);
									$response .='<td><select name="rack_trans_'.$data['invoice_product_id'].'" id="rack_trans_'.$data['invoice_product_id'].'" onchange="get_pallet_trans('.$data['invoice_product_id'].','.$data['product_code_id'].')" style="display:none;width:inherit;" class="form-control">
														<option>Select Pallet</option>';
														foreach($goods_master as $gd)
														{
															$response .= '<option value="'.$gd['row'].'='.$gd['column_name'].'='.$gd['goods_master_id'].'">'.$gd['name'].'</option>';
														}
														
									$response .='</select></td>';
									
								$response .='<td id="rack_number_trans_'.$data['invoice_product_id'].'"></td>';
								$response .='<td><a id="btn_done_trans_'.$data['invoice_product_id'].'"  name="btn_done_trans_'.$data['invoice_product_id'].'" class="btn btn-info btn-xs" onclick="dis_rack_trans('.$data['invoice_product_id'].','.$pro_inv_no.','.$data['rack_remaining_qty'].','.$product_code['product'].','.$data['product_code_id'].','.$data['invoice_id'].','.$sales_no.','.$invoice['pallet_nm'].','.$rack_no.','.$invoice['dis_or_warehouse'].','.$cust_nm.')" style="display:none;">Transfer</a></td>';
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
	 $sel.= '<select name="pallet_trans_'.$_POST['invoice_product_id'].'" id="pallet_trans_'.$_POST['invoice_product_id'].'" style="width: inherit;" class="form-control"><option>Select Rack</option>';
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
	$rack_data=$obj_rack_master->getRackQty($postdata['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'','',$postdata);
	$postdata['stock_id']=$rack_data[0]['grouped_stock_id'];
	$data=$obj_invoice->$fun($postdata,$_POST['admin_email']);
	
	$res=$obj_invoice->changeRackStatusTrans($postdata['invoice_id']);
	
	echo $data;
}
if($fun == 'approve_disapprove_order')
{
	$obj_invoice->send_mail($_POST['transfer_invoice_id'],$_POST['admin_email'],$_POST['statement'],$_POST['trans_no']);
}
if($fun == 'check_proforma')
{
	$proforma_no = $_POST['proforma_no'];
	$no = $obj_invoice->$fun($proforma_no);
	echo $no;
}
if($fun == 'check_sales')
{
	$sale_no = $_POST['sale_no'];
	$no = $obj_invoice->$fun($sale_no);
	echo $no;
}
if($fun == 'get_disc')
{
	$disc = $obj_invoice->$fun($_POST['productCode_text']);
	echo $disc;
}
if($fun == 'getSpoutDetail'){

	
    $product_id=	$obj_invoice -> $fun((int)$_POST['product_code_id']);
	echo $product_id;
}
if($fun == 'customer_detail'){
	$customer_name = $_POST['customer_name'];
	$result = $obj_invoice->getCustomerDetail($customer_name);
//	printr($result);die;
	echo json_encode($result);
}
?>