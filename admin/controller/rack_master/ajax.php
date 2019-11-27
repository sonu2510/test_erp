<?php
include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'updateRackStatus') {
	$rack_master_id = $_POST['rack_master_id'];
	$status_value = $_POST['status_value'];
	$obj_rack_master->$fun($rack_master_id,$status_value);
}
if($fun == 'addstock') {
	parse_str($_POST['formData'], $postdata);
	//printr($postdata);die;
	$obj_rack_master->$fun($postdata);	
}
if($fun == 'update_price')
{
	$data = array('price'=>$_POST['postArray']['price'],
	'stock_id'=>$_POST['postArray']['stock_id'],
	);
	$result1 = $obj_rack_master->updatePrice($data);
	echo $result1;
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
if($fun == 'savedispatch')
{
	parse_str($_POST['formData'], $postdata);
	$data=$obj_rack_master->$fun($postdata);
//echo $data;
}
if($fun == 'searchRecords')
{
	parse_str($_POST['formData'], $postdata);
	$data=$obj_rack_master->$fun($postdata);
	//printr($data);
	if(isset($data) && !empty($data)){
		$html='<form name="form_list" id="form_list" method="post">
				<input type="hidden" id="action" name="action" value="" />
					<div class="table-responsive">
						<table id="quotation-row" class="table b-t text-small table-hover">
							<thead>
								<tr>
									<th>Sr. No.</th> 
									<th>Product Name</th>
									<th>Company Name</th>
									<th>Invoice No</th>      
									<th>Proforma No</th>                    
									<th>Descripton</th>
									<th>Qty</th>
									<th>Date</th>
									<th>Posted By</th>
								</tr>
							</thead>
							<tbody>';
							$slNo=1;
						 	 foreach($data as $stock){ 							 
								/*$zipper_name=$obj_rack_master->getzipper($stock['zipper_id']);
							 	$spout_name=$obj_rack_master->getSpout($stock['spout_id']);
								$accessorie_name=$obj_rack_master->getAccessorie($stock['accessorie_id']);
								$make_name=$obj_rack_master->getMake($stock['make_id']);
								$color_name=$obj_rack_master->getColor($stock['color_id']);
								$size_name=$obj_rack_master->getSize($stock['size_id']);*/
								 $desc = $obj_rack_master->getProductCode($stock['product_code_id']);
									if($stock['description']==2){$des="Dispatched";$qty=$stock['dispatch_qty'];}
									elseif($stock['description']==1){$des="Store";$qty=$stock['qty'];}
									else{$des="Goods Return";$qty=$stock['qty'];}
								$user_id=$stock['user_id'];
								$user_type_id=$stock['user_type_id'];
							   	$postedByData=$obj_rack_master->getUser($user_id,$user_type_id);
								$addedByImage = $obj_general->getUserProfileImage($user_type_id,$user_id,'100_');
								$postedByInfo = '';
								$postedByInfo .= '<div class="row">';
								$postedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
								$postedByInfo .= '<div class="col-lg-9">';
									if($postedByData['city']){ $postedByInfo .= $postedByData['city'].', '; }
									if($postedByData['state']){ $postedByInfo .= $postedByData['state'].' '; }
									if(isset($postedByData['postcode'])){ $postedByInfo .= $postedByData['postcode']; }
								$postedByInfo .= '<br>Telephone : '.$postedByData['telephone'].'</div>';
								$postedByInfo .= '</div>';
								$postedByName = $postedByData['first_name'].' '.$postedByData['last_name'];
								str_replace("'","\'",$postedByName);
								 
								$html.='<tr>
								  <td width="1%">'.$slNo++.'</td>
								   <td width="20%"><b>'.$desc['product_code'].'<br><b>'.$desc['description'].'<b>
								  </td>
								  <td>'.$stock['company_name'].'</td>
								  <td>'.$stock['invoice_no'].'</td>
								  <td>'.$stock['proforma_no'].'</td>
								  <td>'.$des.'</td>
								  <td>'.$qty.'</td>
								<td>'.dateFormat(4,$stock['date_added']).'</td>
								  <td> <a href="#" class="btn btn-info btn-sm pull-right">'.$postedByData['user_name'].'</a>
								  </td>
								</tr>';
							}
						$html.=' </tbody>
						 </table>';
						}
						else
						$html='No Record Found! ';	  
	echo $html;
}
if($fun == 'product_code'){
	//echo "hi";
	$product_code = $_POST['product_code'];
	//echo $product_code;
	$result = $obj_rack_master->getProductCd($product_code);
	echo json_encode($result);
}
if($fun == 'checkInvoice'){
	//echo "hi";
	$invoice_no = $_POST['inv_val'];
	$product_code_id = $_POST['product_code_id'];
	//echo $product_code;
	$result = $obj_rack_master->showInvoice($invoice_no,$product_code_id);
	echo json_encode($result);
}
if($fun == 'checkNo'){
	//echo "hi"; 
	$invoice_no = $_POST['no_url'];
	$result = $obj_rack_master->showInvoice($invoice_no);
	echo json_encode($result);
}
// mansi 23-1-2016 (function for rack label)
if($fun == 'update_value')
{
		$goods_id = $_POST['goods_id'];
		$result1 = $obj_rack_master->update_value($goods_id,$_POST['raw'],$_POST['col'],$_POST['rack_label']);
		//echo 1;		
	
}
if($fun == 'purList') 
{
	$result1 = $obj_rack_master->getInvoice($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);

	$response = '';
	$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
		$response .= '<table class="table table-striped m-b-none text-small">';
			$response .= '<thead>';
				$response .= '<tr>'; 
					$response .= ' <th>Purchase Invoice List</th>';
				$response .= '</tr>';
			$response .= '</thead>'; 
		$response .= '<tbody>'; 
			foreach($result1 as $res)
			{		
            		/*$inv_product=$obj_rack_master->getInvoiceProduct($res['invoice_id']);
                    $count = count($inv_product);
                    $inv_r=$obj_rack_master->getInvRackStatus($res['invoice_id']);
                    $count_rack = count($inv_r);*/
                  //  if($count != $count_rack)
                   //{	
                   
                  // $res=$obj_rack_master->changeRackStatus($res['invoice_id']);
                        $inv_no = "'".$res['invoice_no']."'";
                        $response .= '<tr>';
                            $response .= '<td><a onclick="showPurProduct('.$res['invoice_id'].','.$inv_no.')" style="cursor: pointer;">'.$res['invoice_no'].'</a></td>';
                        $response .= '</tr>';
                  //  }
			}
		$response .= '</table>'; 
	$response .= '</div>';
	echo $response;
}
if($fun == 'creditList')
{
	$result1 = $obj_rack_master->getCreditInvoice($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);

	$response = '';
	$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
		$response .= '<table class="table table-striped m-b-none text-small">';
			$response .= '<thead>';
				$response .= '<tr>'; 
					$response .= ' <th>Credit Note List</th>';
					//if(!empty($cre_inv))
						//$response .= ' <th>Refund Amount</th>';
						
				$response .= '</tr>';
			$response .= '</thead>'; 
		$response .= '<tbody>'; 
			foreach($result1 as $res)
			{		$inv_no = "'".$res['invoice_no']."'";
			         					 
					$response .= '<tr>';
						$response .= '<td><a onclick="showcreditnote('.$res['invoice_id'].','.$inv_no.')" style="cursor: pointer;">'.$res['invoice_no'].'</a></td>';
					$response .= '</tr>';
			}
		$response .= '</table>'; 
	$response .= '</div>';
	echo $response;
}
if($fun == 'salesList')
{
	$result1 = $obj_rack_master->getSalesInvoice($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);

	$response = '';
	$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
		$response .= '<table class="table table-striped m-b-none text-small">';
			$response .= '<thead>';
				$response .= '<tr>'; 
					$response .= ' <th>Sales Invoice List</th>';
					$response .= ' <th>Proforma Invoice No</th>';
					$response .= ' <th>Date</th>';
					$response .= ' <th>Customer Name</th>';
					$response .= ' <th>Posted By</th>';
					//if(!empty($cre_inv))
						//$response .= ' <th>Refund Amount</th>';
						
				$response .= '</tr>';
			$response .= '</thead>'; 
		$response .= '<tbody>'; 
			foreach($result1 as $res)
			{		$inv_no = '"'.$res['invoice_no'].'"';
			         $proforma_no = '"'.$res['proforma_no'].'"';
					 $customer_name = '"'.str_replace("'","&apos;",$res['customer_name']).'"';
				    $user_detail = $obj_rack_master->getUser($res['user_id'],$res['user_type_id']);
				    $proforma_detail = $obj_rack_master->getSingleInvoice($res['proforma_no']);
				 //   printr($proforma_detail);
				if($proforma_detail['destination']=='251')
				    $buyers_order_no='<br><b>Buyers order no : </b>'.$proforma_detail['buyers_order_no'];
				else
				    $buyers_order_no=""; 
				    
					$response .= '<tr>';
						$response .= "<td><a onclick='showSalesProduct(".$res['invoice_id'].",".$inv_no.",".$proforma_no.",".$customer_name.")' style='cursor: pointer;'>".$res['invoice_no']."</a></td>";
						$response .= "<td><a onclick='showSalesProduct(".$res['invoice_id'].",".$inv_no.",".$proforma_no.",".$customer_name.")' style='cursor: pointer;'>".$res['proforma_no']." ".$buyers_order_no."</a></td>";
						$response .= "<td><a onclick='showSalesProduct(".$res['invoice_id'].",".$inv_no.",".$proforma_no.",".$customer_name.")' style='cursor: pointer;'>".dateFormat(4, $res['invoice_date'])."</a></td>";
						$response .= '<td><b>'.$res['customer_name'].'<b></td>';
						$response .= '<td><b>'.$user_detail['first_name'].'   '.$user_detail['last_name'].'<b></td>';
					$response .= '</tr>';
			}
		$response .= '</table>';  
	$response .= '</div>';
	echo $response;
}
if($fun == 'showPurProduct')
{
	$result=$obj_rack_master->getInvoiceProduct($_POST['invoice_id']);
	$data_invoice=$obj_rack_master->getInvoiceDetails($_POST['invoice_id']);
//	printr($result);
						 //   printr($data);
	$id=array();
	$i=0;
	$response = '';
	if(isset($result ) && !empty($result )){
	$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
		$response .= '<table class="table table-striped m-b-none text-small">';
			$response .= '<thead>';
				$response .= '<tr>'; 
					$response .= ' <th></th>';
					$response .= ' <th>Product Code</th>';
					$response .= ' <th>Qty</th>';
					$response .= ' <th colspan="2"></th>';
					$response .= ' <th>Qty</th>';
					$response .= ' <th></th>';
					$response .= '</tr>';
				$response .= '</thead>'; 
			$response .= '<tbody>'; 
			//printr($result);
			 
						foreach($result as $key=>$data)
						{
						    
						    
							$response .= '<tr>';
                            	if($data['rack_status'] !='0')
									$response .= '<td><input type="checkbox" name="post[]" id="check_'.$data['invoice_product_id'].'" value="'.$data['invoice_product_id'].'" onchange="getGenId('.$data['invoice_product_id'].')"></td>';
								else
                                	$response .= '<td><span style="color:red;"><b>Done</b></span> </td>' ; 
                                 
                                if($data['product_code_id']==0  && $data_invoice['order_user_id']!='19')
                                {
                                    $custom=$obj_rack_master->getCustomDigitalProduct($data['invoice_product_id']);
                                    $response .= '<td><select name="product_code_id'.$data['invoice_product_id'].'" id="product_code_id'.$data['invoice_product_id'].'" class="form-control validate[required] choosen_data">
															<option>Select Product Code</option>';
															foreach($custom as $cust)
															{
																$response .= '<option value="'.$cust['product_code_id'].'">'.$cust['product_code'].'</option>';
															}
															
									$response .= '</select>
									        <input type="hidden" id="rack_rem_purqty_'.$data['invoice_product_id'].'" value="'.$data['rack_status'].'">
									</td>';
                                }
                                else
                                    $response .= '<td><input type="hidden" id="rack_rem_purqty_'.$data['invoice_product_id'].'" value="'.$data['rack_status'].'">'.$data['product_code'].'</td>';
								
								
							if($data['rack_status'] !='0')
								{ 
									if($data['product_id']==6)
									    $response .= '<td>'.$data['net_weight'].' KGS</td><input type="hidden" id="net_roll_'.$data['invoice_product_id'].'" value="'.$data['net_weight'].'"><input type="hidden" id="net_roll_id_'.$data['invoice_product_id'].'" value="'.$data['in_gen_invoice_id'].'">';
									else
									    $response .= '<td>'.$data['rack_status'].'</td><input type="hidden" id="net_roll_id_'.$data['invoice_product_id'].'" value="">';
									//$gen_box=$obj_rack_master->getBoxForProduct($data['invoice_id'],$data['invoice_product_id'],$data['invoice_color_id']);
									//printr($gen_box);
									/*$response .= '<td><select name="box_sel'.$data['invoice_product_id'].'" id="box_sel'.$data['invoice_product_id'].'" style="display:none;width: inherit;" class="form-control">
															<option>Select Box</option>';
															foreach($gen_box as $box)
															{
																$response .= '<option value="'.$box['box_unique_number'].' == '.$box['box_unique_id'].'">'.$box['box_unique_number'].' == '.$box['qty'].'</option>';
															}
															
										$response .= '</select></td>';*/
										
										/*$response .= '<td>
														<div class="form-control scrollbar scroll-y" style="height:60px;width:260px;" id="groupbox">';
															 foreach($gen_box as $box){
																$response .= '<div class="checkbox">';
																	$response .= '<label class="checkbox-custom">';
																		$response .= '<input type="checkbox"  name="box_sel'.$data['invoice_product_id'].'[]" id="box_sel'.$data['invoice_product_id'].'" value="'.$box['box_unique_number'].' == '.$box['box_unique_id'].'"> ';
																	$response .= '<i class="fa fa-square-o"></i> '.$box['box_unique_number'].' == '.$box['qty'].'</label>';
																$response .=  '</div>
																
					';
																
															}
										$response .= '</div>
										
												</td>';*/
								}
									
								$goods_master = $obj_goods_master->getGoodsMaster('','',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
								$response .='<input type="hidden" id="product_id_pur'.$data['invoice_product_id'].'" value="'.$data['product_id'].'"><td><select name="rack_'.$data['invoice_product_id'].'" id="rack_'.$data['invoice_product_id'].'" onchange="get_pallet('.$data['invoice_product_id'].')" style="display:none;width: inherit;" class="form-control">
													<option>Select Pallet</option>';
													foreach($goods_master as $gd)
													{
														$response .= '<option value="'.$gd['row'].'='.$gd['column_name'].'='.$gd['goods_master_id'].'" selected=selected>'.$gd['name'].'</option>';
													}
								$response .='</select></td>';
								$response .='<td id="rack_number_'.$data['invoice_product_id'].'"></td>';
								$response .='<td><input type="text" name="qty_insert_'.$data['invoice_product_id'].'" id="qty_insert_'.$data['invoice_product_id'].'" value="" style="display:none;" class="form-control validate[required,custom[number]]"></td>';
								
								$response .='<td><a id="btn_done_'.$data['invoice_product_id'].'"  name="btn_done_'.$data['invoice_product_id'].'" class="btn btn-info btn-xs" onclick="add_rack_('.$data['invoice_product_id'].','.$data['invoice_id'].')" style="display:none;">Done</a></td>';
							$response .= '</tr>';
						}
					
				
			$response .= '</thead>'; 
		$response .= '<tbody>'; 
		
		$response .= '</tbody>'; 
		$response .= '</table>'; 
	$response .= '</div>
	              <script>';
	                    
	$response .= '</script>';
	}else{
		$response='No Record Found! ';	
		}
	
	echo $response;
}
if($fun == 'showcreditProduct')
{
    //getcreditnoteProduct
	//printr($_POST['invoice_id']);
	$result=$obj_rack_master->getcreditnoteProduct($_POST['invoice_id']);
	//printr($)
	$id=array();
	$i=0;
	$response = '';
	$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
	if(isset($result) && !empty($result))
	{
		$response .= '<table class="table table-striped m-b-none text-small">';
			$response .= '<thead>';
				$response .= '<tr>'; 
					$response .= ' <th></th>';
					$response .= ' <th>Credit No. -> Product Code</th>';
					//$response .= ' <th>Product Code</th>';
					$response .= ' <th>Qty</th>';
					$response .= ' <th id="cre_pallet" style="display:none;">Pallet</th>';
					$response .= ' <th id="cre_rack" style="display:none;">Rack No.</th>';
					$response .= ' <th colspan="2" id="cre_qty" style="display:none;">Add Qty</th>';
					$response .= '</tr>';
				$response .= '</thead>'; 
			$response .= '<tbody>'; 
	//printr($result);
	
	
							foreach($result as $key=>$data)
							{
								//printr($data);
								$response .= '<tr>';
									if($data['rack_remaining_qty'] !='0')
										$response .= '<td><input type="checkbox" name="post[]" id="check_'.$data['sales_credit_note_id'].'" value="'.$data['sales_credit_note_id'].'" onchange="getGen_credit_Id('.$data['sales_credit_note_id'].')"></td>';
									else
										$response .= '<td><span style="color:red;"><b>Done</b></span></td>';
									
									// $response .= '<td>'.$data['cre_no'].'</span></td>';   
									$response .= '<td><input type="hidden" id="sales_credit_note_id_'.$data['sales_credit_note_id'].'" value="'.$data['sales_credit_note_id'].'"><input type="hidden" id="sales_rem_purqty_'.$data['sales_credit_note_id'].'" value="'.$data['rack_remaining_qty'].'"><b>'.$data['cre_no'].'</b> -> '.$data['product_code'].'</td>';
									
									
								if($data['rack_remaining_qty'] !='0')
									{
										$response .= '<td>'.$data['rack_remaining_qty'].'</td>';
									
									}
										
									$goods_master = $obj_goods_master->getGoodsMaster('','',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
									$response .='<td><select name="rack_'.$data['sales_credit_note_id'].'" id="rack_'.$data['sales_credit_note_id'].'" onchange="get_pallet_credit('.$data['sales_credit_note_id'].')" style="display:none;width: inherit;" class="form-control">
														<option>Select Pallet</option>';
														foreach($goods_master as $gd)
														{
															$response .= '<option value="'.$gd['row'].'='.$gd['column_name'].'='.$gd['goods_master_id'].'">'.$gd['name'].'</option>';
														}
									$response .='</select></td>';
									$response .='<td id="rack_number_'.$data['sales_credit_note_id'].'"></td>';
									$response .='<td><input type="text" name="qty_insert_'.$data['sales_credit_note_id'].'" id="qty_insert_'.$data['sales_credit_note_id'].'" value="" style="display:none;" class="form-control validate[required,custom[number]]"></td>';
									
									$response .='<td><a id="btn_done_'.$data['sales_credit_note_id'].'"  name="btn_done_'.$data['sales_credit_note_id'].'" class="btn btn-info btn-xs" onclick="add_creditnote_recode_('.$data['sales_credit_note_id'].','.$data['invoice_id'].','.$data['product_id'].','.$data['product_code_id'].')" style="display:none;">Done</a></td>';
								$response .= '</tr>';
							}
						
					
				$response .= '</tbody>'; 
			$response .= '</table>'; 
		$response .= '</div>';
	}
	else
	{
		$response.='<b> NO Records Found !!!!!!! </b>';
	}
	echo $response;
}

if($fun == 'showSalesProduct')
{
    $addedByInfo = $obj_goods_master->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']); 
	$result=$obj_rack_master->getSalesInvoiceProduct($_POST['invoice_id'],'');
	$country = $obj_rack_master->getSalesInvoiceCountry($_POST['invoice_id'],'');
//	printr($result);
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
																	
																
															    	$r_no[]=$k.'='.$rack['goods_master_id'];	
																}
																$d++;
															}
														}
														
														//
														$dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['stock_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
													
													//  printr($rack_qty);
													    	if($addedByInfo['country_id']==42){
                            									$lable =$obj_rack_master->getRackLabelCanada($col_row,$rack['goods_master_id']);
                            								}else{
                            								   	$lable = $obj_rack_master->getLabel($col_row,$rack['goods_master_id']);
                            								}
													
													//	$lable = $obj_rack_master->getLabel($col_row,$rack['goods_master_id']);
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
    													
    													
    
    												//	printr($rack_qty_array);
    											//		printr($rack_no_array);
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
							//$response .= "<td><a onclick='showSalesProduct(".$res['invoice_id'].",".$inv_no.",".$proforma_no.",".$customer_name.")' style='cursor: pointer;'>".$res['invoice_no']."</a></td>";
							/*$response .='<td><input type="text" name="in_qty_'.$data['invoice_product_id'].'" id="in_qty_'.$data['invoice_product_id'].'" value="" class="form-control" style="display:none;width:inherit;" onchange="check_qty('.$data['invoice_product_id'].','.$data['qty'].')"></td>';*/
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
if($fun == 'addRack')
{
	$result=$obj_rack_master->addRackDetail($_POST['invoice_product_id'],$_POST['pallet_val'],$_POST['span_inv_no'],$_POST['pur_qty'],$_POST['store_qty'],$_POST['product_code_id'],$_POST['net_roll_id']);
	$res=$obj_rack_master->changeRackStatus($_POST['invoice_id']);
}
if($fun == 'add_credit_note')
{
	
	$result=$obj_rack_master->addCreditDetail($_POST['invoice_id'],$_POST['pallet_val'],$_POST['span_inv_no'],$_POST['store_qty'],$_POST['pur_qty'],$_POST['sales_credit_note_id'],$_POST['product_code_id'],$_POST['product_id']);
	$res=$obj_rack_master->changeRackStatusInCredit($_POST['invoice_id']);
}
if($fun == 'showoldstock')
{
	$goods_id=base64_decode($_POST['goods_master_id']);
	$data = $obj_rack_master->showoldstock($goods_id);
	//printr($data);
	$response = '';
	if($data!='')
	{
			
			$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
				$response .= '<table class="table table-striped m-b-none text-small">';
					$response .= '<thead>';
						$response .= '<tr>'; 
							$response .= ' <th>Product Code</th>';
							$response .= ' <th>Qty</th>';
							$response .= ' <th>Rack No</th>';
							$response .= ' <th>Stock Stored Date</th>';
							$response .= '</tr>';
						$response .= '</thead>'; 
					$response .= '<tbody>'; 
					foreach($data as $key=>$val)
					{
						$d=1;
						$rc = $val['row'].'@'.$val['column_name'];
						for($i=1;$i<=$val['g_row'];$i++)
						{
							for($r=1;$r<=$val['g_col'];$r++) 
							{
								$n = $i.'@'.$r;
								if($rc==$n)
									$k=$d;
									
								$d++;
							}
						}
						
						
						$response .= '<tr>';
						$response .= '<td>'.$val['product_code'].'</td>';
						$response .= '<td>'.$val['qty'].'</td>';
						$response .= '<td>'.$k.'</td>';
						$response .= '<td>'.dateFormat(4,$val['date_added']).'</td>';
						$response .= '</tr>';
					}
					
			$response .= '</tbody>'; 
			$response .= '</table>'; 
			$response .= '</div>';
	}
	else
	{
		echo "<center><b> There are no any oldest stock currently available</b></center>";
		
	}
	echo $response;
	
}
if($fun == 'list_old_new')
{
	$goods_id=base64_decode($_POST['goods_master_id']);
	$data = $obj_rack_master->list_old_new($goods_id);
	//printr($data);
	$response = '';
	if($data!='')
	{
			
			$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
				$response .= '<table class="table table-striped m-b-none text-small">';
					$response .= '<thead>';
						$response .= '<tr>'; 
							$response .= ' <th>Invoice No</th>';
							$response .= ' <th>Product Code</th>';
							$response .= ' <th>Store Qty</th>';
							$response .= ' <th>Rack No</th>';
							$response .= ' <th>Stock Stored Date</th>';
							$response .= '</tr>';
						$response .= '</thead>'; 
					$response .= '<tbody>'; 
					foreach($data as $key=>$val)
					{
						$d=1;
						$rc = $val['row'].'@'.$val['column_name'];
						for($i=1;$i<=$val['row'];$i++)
						{
							for($r=1;$r<=$val['column_name'];$r++) 
							{
								$n = $i.'@'.$r;
								if($rc==$n)
									$k=$d;
									
								$d++;
							}
						}
						
						
						$response .= '<tr>';
						$response .= '<td>'.$val['invoice_no'].'</td>';
						$response .= '<td>'.$val['product_code'].'</td>';
						$response .= '<td>'.$val['qty'].'</td>';
						$response .= '<td>'.$k.'</td>';
						$response .= '<td>'.dateFormat(4,$val['date_added']).'</td>';
						$response .= '</tr>';
					}
					
			$response .= '</tbody>'; 
			$response .= '</table>'; 
			$response .= '</div>';
	}
	else
	{
		echo "<center><b> There are no any oldest stock currently available</b></center>";
		
	}
	echo $response;
}
if($fun == 'user_list')
{
	$user = $_POST['user'];
	$result = $obj_rack_master->getUserList($user);
	echo json_encode($result);
}


/*if($fun == 'check_rack_qty')
{
	$result=$obj_rack_master->check_rack_qty($_POST['product_code_id'],$_POST['row'],$_POST['col'],$_POST['goods_master_id']);
	printr($result);
}*/


if($_GET['fun']=='addcourier') {
	//printr($_POST);
	$data = $obj_rack_master->addcourier($_POST['courier_name'],$_POST['aws_no'],$_POST['sent_date'],$_POST['invoice_id'],$_POST['admin_email']);
	echo  '1';
}
if($_GET['fun']=='courier_details') {
	//printr($_POST);
	$data = $obj_rack_master->courier_details($_POST['invoice_id']);
	echo json_encode($data);
}
if($_GET['fun']=='getLabel') {
    $addedByInfo = $obj_goods_master->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']); 
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
								if($addedByInfo['country_id']==42)
									$data =$obj_rack_master->getRackLabelCanada($rowcol,$_POST['goods_master_id']);
								else{
								if($_POST['goods_master_id']!='8')
								    $data = $obj_rack_master->getLabel($rowcol,$_POST['goods_master_id']);
								}
							//	$data = $obj_rack_master->getLabel($rowcol,$_POST['goods_master_id']);
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

if($_GET['fun']=='rack_qty_details'){
	//printr($_POST['product_code_id']);
	//die;
	
	
	$rack_qty_dis = $obj_rack_master->getRack_qty_dis($_POST['pallet_details_dis'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'','');
	$response ='';
							//	printr($rack_qty);
								
								
					
								$r_no=array();
								$response .=' <div class="form-group" >';
								$response .='<label class="col-lg-3 control-label">';
								$response .='<span class="required">*</span>Rack Information</label>';
								$response .='<div class="col-lg-6">';
								$response .='<section class="panel">';
								$response .='<header class="panel-heading">Rack Infomation</header>';
								$response .='<div >';
											
											
								$response .='	<table border="1" class="table table-striped m-b-none text-small">
													<th>Product Code</th>												
													<th>Qty</th>';
												if(!empty($rack_qty_dis))
												{ 
													foreach($rack_qty_dis->rows as $rack)
													{
															$desc = $obj_rack_master->getProductCode($rack['product_code_id']);
															$goods_master_detail = $obj_goods_master->getGoodsData($rack['goods_id']);
																		//	printr($goods_master_detail);	 
															 $dispatch_qty=$obj_rack_master->gettotaldispatch($rack['grouped_s_id']);
																
															if($dispatch_qty['total']!='')
															{
																$tot_dis_qty=$dispatch_qty['total'];
																$remaining_qty=$rack['tot_qty']-$tot_dis_qty;
															
															}
															else
															{
																$tot_dis_qty=0;
																$remaining_qty=0;
															}
														

											
														//printr($rack);
														$d=1;
														$rc = $rack['row'].'@'.$rack['column_name'];
														for($i=1;$i<=$goods_master_detail['row'];$i++)
														{
															for($r=1;$r<=$goods_master_detail['column_name'];$r++) 
															{
																$n = $i.'@'.$r;
																if($rc==$n)
																{
																	$k=$d;
																//	printr($rc);
																	$r_no[] = $k;

																	
																	
																}	
																$d++;
															}
														}
																
						 
														
														$dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['grouped_s_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
														//printr($dispatch_qty);
														$rm_qty=$rack['tot_qty']-$dispatch_qty['total'];
														
						
														if($rm_qty!=0){
															$response .='<tr><td>'.$desc['product_code'].'</td>																			
																			 <td>'.$rm_qty.'</td></tr>';
														
														$a[]=$k;
														$r_qty[] =$rm_qty.'='.$rack['product_code_id'];
														$product_code_arr[] = $desc['product_code'].'='.$rack['product_code_id'];
														$stock_group_id=$rack['grouped_stock_id'];
														}
													
													}
													// printr($r_qty);
													$rack_no = array_unique($a);
													$rack_no_array= implode(',',$rack_no);
													
													$rack_qty = array_unique($r_qty);
													$rack_qty_array= implode('&',$rack_qty);		
												//printr($product_code_arr);
													$product_code_data = array_unique($product_code_arr);
													$product_code_array= implode('&',$product_code_data);	
												//	printr($product_code_data);
												//	printr($product_code_array);
													

													//printr($g_no_array);
													$response.='<input type = "hidden" name = "rack_no" id = "rack_no" value="'.$rack_no_array.'"/>';
													$response.='<input type = "hidden" name = "product_code_array" id = "product_code_array" value="'.$product_code_array.'"/>';
													$response.='<input type = "hidden" name = "rack_qty" id = "rack_qty" value="'.$rack_qty_array.'"/>';
													$response.='<input type = "hidden" name = "stock_group_id" id = "stock_group_id" value="'.$stock_group_id.'"/>';
													
												}	
												
												$response .='</table>';
												$response .='</div>';
										$response .='</section>';
									$response .='</div>';
								 $response .='</div>';
							 $response .=' <div class="form-group" id="report_product_code" >';
							 $response .='<label class="col-lg-3 control-label">';
							 $response .='<span class="required">*</span>Product code</label>';
							 $response .='<div class="col-lg-6">';
							$response .='<select name="product_code_id_dis" id="product_code_id_dis"  class="form-control">';
												$response .='<option> Select Productcode</option>';		
															if(!empty($rack_qty_dis))
															{ 
															//	printr($rack_qty_dis);
																foreach($rack_qty_dis->rows as $rack)
																{
																		$desc = $obj_rack_master->getProductCode($rack['product_code_id']); 
																		
																	$response .= '<option value="'.$rack['product_code_id'].'">'.$desc['product_code'].'</option>';
																	
																}
															}
														
												$response .='</select>';
							 $response .='</div >';
							 $response .='</div >';
							 
												
			echo $response;
}
if($_GET['fun']=='getLabel_pallet') {
 $addedByInfo = $obj_goods_master->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']); 
    $sel = '';
	 $sel.= '<select name="pallet_'.$_POST['invoice_product_id'].'" id="pallet_'.$_POST['invoice_product_id'].'" style="width: inherit;" class="form-control "><option>Select Rack</option>';
				$d = 1;
					for($i=1;$i<=$_POST['row'];$i++)
					{
						for($r=1;$r<=$_POST['col'];$r++) 
						{
								$rowcol = ''.$i.'@'.$r.'';	
								$data='';
								
								if($addedByInfo['country_id']==42)
									$data =$obj_rack_master->getRackLabelCanada($rowcol,$_POST['goods_master_id']);
								else{
								if($_POST['goods_master_id']!='8')
								    $data = $obj_rack_master->getLabel($rowcol,$_POST['goods_master_id']);
								}
								//printr($data);
								$l=$d;
								if($data!='')
								   $l= $data;
								$sel.= '<option value="'.$i.'='.$r.'='.$_POST['goods_master_id'].'='.$d.'">'.$l.'</option>';
							
								$d++;
								
						}
						
					}

	  $sel.= '</select>';
	  
	echo $sel;
}
if($_GET['fun']=='getLabel_pallet_add_stock') {
    
    $sel = '';
	 $sel.= '<select name="pallet" id="pallet" style="width: inherit;" class="form-control chosen-select"><option>Select Rack</option>';
				$d = 1;
					for($i=1;$i<=$_POST['row'];$i++)
					{
						for($r=1;$r<=$_POST['col'];$r++)  
						{
								$rowcol = ''.$i.'@'.$r.'';	
								$data = $obj_rack_master->getLabel($rowcol,$_POST['goods_master_id']);
								
							//	printr($data);
								$l=$d;
								if($data!='')
								   $l= $data;
								$sel.= '<option value="'.$i.'='.$r.'='.$_POST['goods_master_id'].'='.$d.'">'.$l.'</option>';
							 
								$d++;
								
						}
						
					}

	  $sel.= '</select>';
	  
	echo $sel;
}if($_GET['fun']=='getLabel_pallet_dis_stock') {
    
    $sel = '';
	 $sel.= '<select name="pallet_dispatch"   onchange="getStockinfo()" id="pallet_dispatch" style="width: inherit;" class="form-control chosen-select"><option>Select Rack</option>';
				$d = 1;
					for($i=1;$i<=$_POST['row'];$i++)
					{
						for($r=1;$r<=$_POST['col'];$r++)  
						{
								$rowcol = ''.$i.'@'.$r.'';	
								$data = $obj_rack_master->getLabel($rowcol,$_POST['goods_master_id']);
								
							//	printr($data);
								$l=$d;
								if($data!='')
								   $l= $data;
								$sel.= '<option value="'.$i.'='.$r.'='.$_POST['goods_master_id'].'='.$d.'">'.$l.'</option>';
							
								$d++;
								
						}
						
					}

	  $sel.= '</select>';
	  
	echo $sel;
}
if($_GET['fun']=='getrackdataforindia') {
	$rack_qty = $obj_rack_master->getRackQty($_POST['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$_POST['fdate'],$_POST['tdate']);
	$goods_master = $obj_goods_master->getGoodsMaster('','',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);

	$response ='';
						//		printr($rack_qty);die;
								
								
					
		$r_no=array();
		$response .='<div class="form-group" >';
		$response .='<label class="col-lg-2 control-label"></label>';
	
		$response .='<div class="col-lg-8">';
	
		$response .='<div id="print_product_code">';
					
					
		$response .='<table border="1" class="table table-striped m-b-none text-small">';

				$response.='<tr>
							<td colspan="3"> <center><b>Rack Information</b></center></td>
						
							</tr>';
				$response.='<tr>
							<td><b>Rack Name</b></td>
							<td><b>Rack Position</b></td>
							<td><b>Qty</b></td>
							</tr>';

						if(!empty($rack_qty))
						{
							foreach($rack_qty as $rack)
							{
								//printr($rack);
								$d=1;
								$rc = $rack['row'].'@'.$rack['column_name'];
								for($i=1;$i<=$rack['g_row'];$i++)
								{
									for($r=1;$r<=$rack['g_col'];$r++) 
									{
										$n = $i.'@'.$r;
										if($rc==$n)
										{
											$k=$d;
										$label = $obj_rack_master->getLabel($n,$rack['goods_master_id']);
                						
        								if($label!='')
        								   $k= $label;
										//	printr($k);
											$r_no[] = $i.'=='.$r.'=='.$k;

											
											
										}	
										$d++;
									}
								}
									
								
						$dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['stock_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
								//printr($dispatch_qty);
								$rm_qty=$rack['store_qty']-$dispatch_qty['total'];
								$response .='<tr><td>'.$rack['name'].'</td>
												<td align="center">'.$k
												.'   </td>
												<td>'.$rm_qty.'</td></tr>';
								$a[]=$k;
								$r_qty[] =$rm_qty.'='.$k;
							//	printr($r_no);
							//	printr($r_qty);
									$rack_no = array_unique($r_no);
													$rack_no_array= implode(',',$rack_no);
													
													$rack_qty = array_unique($r_qty);
													$rack_qty_array= implode('&',$rack_qty);											
													
													

												//	printr($rack_qty);

														//printr(array_diff($rack_no, $rack_qty));
													$response.='<input type = "hidden" name = "rack_no_p" id = "rack_no_p" value="'.$rack_no_array.'"/>';
													$response.='<input type = "hidden" name = "rack_qty_p" id = "rack_qty_p" value="'.$rack_qty_array.'"/>';
								
							}
						
						}	
						
						$response .='</table>
					';


						$response .='</div>';

			
			$response .='</div>';
		 $response .='</div>';
		 	$response .='<div class="form-group"  id="product_code_hide">';
						$response .='<label class="col-lg-3 control-label">';
						$response .='<span class="required">*</span>Rack Name</label>';
				$response .='<div class="col-lg-4">';

							
								$response .='<select name="rack_sales" id="rack_sales" class="form-control">
													<option>Select Pallet</option>';
													foreach($goods_master as $gd)
													{
														$response .= '<option value="'.$gd['row'].'='.$gd['column_name'].'='.$gd['goods_master_id'].'" selected>'.$gd['name'].'</option>';
													}
													
								$response .='</select>';
						$response .='</div>';
						$response .='</div>';
						$response .='<div class="form-group" id="product_code_hide1" >';
						$response .='<label class="col-lg-3 control-label">';
						$response .='<span class="required">*</span>Box No</label>';
						$response .='<div class="col-lg-4">';
						$response .='<select name="pallet_sales" id="pallet_sales"  class="form-control">';											
										foreach ($r_no as  $r) {	
												$arr=explode('==', $r);
											foreach ($r_qty as  $qty) {	
												$q_arr=explode('=', $qty);

												if($q_arr[1]==$arr[2])
												$response .= '<option value="'.$r.'=='.$q_arr[0].'" >'.$arr[2].'</option>';
										}	}	
						$response .='</select>';
						$response .='</div>';
						$response .='</div>';
							
	//	printr($response);die;					 
												
	echo $response;
}

if($fun == 'savedispatch_racknotify_product')
{
	parse_str($_POST['formData'], $post);

//printr("hiiii");
	//printr($_POST['formData']);//die;
	//printr($post);die;
    $r_col=array(); 

	$postdata=array();
    $r_col=explode('==',$post['pallet_sales']);
    $goods_id=explode('=',$post['rack_sales']);
    $alldata=implode('=',$r_col); 
	$postdata['rack_name']=$post['rack_sales'];
	$postdata['pallet_dispatch']=$post['pallet_sales'];	
	$postdata['dispatch_qty']=$post['qty_p'];
	$postdata['proforma_no']=$post['proforma_no_p'];
	$postdata['courier_id']=$post['courier_id_p'];
	$postdata['stock_id']=$post['stock_id'];
	$postdata['alldata']=$alldata;
	$postdata['product']=$post['product_p'];
	$postdata['goods_id']=$goods_id[2];
	$postdata['row_column']=$post['row_column_p'];
	$postdata['grouped_qty']=$post['grouped_qty_p'];
	$postdata['valve_id']=$post['valve_id_p'];
	$postdata['zipper_id']=$post['zipper_id_p'];
	$postdata['spout_id']=$post['spout_id_p'];
	$postdata['make_id']=$post['make_id_p'];
	$postdata['color_id']=$post['color_id_p'];
	$postdata['size_id']=$post['size_id_p'];
	$postdata['accessorie_id']=$post['accessorie_id_p']; 
	$postdata['product_code_id']=$post['product_code_id_st'];
	$postdata['invoice_product_id']=$post['invoice_product_id_p'];
	$postdata['invoice_no']=$post['invoice_id_p'];
	$postdata['product_id']=$post['product_id_p'];
	$postdata['company_name']='na';
	$postdata['sales_qty']=$post['sales_qty_p'];

//printr($postdata);//die;
	$rack_data=$obj_rack_master->getRackQty($post['product_code_id_st'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'','',$postdata);
//printr('hii');
//printr($rack_data);die;
		$postdata['stock_id']=$rack_data[0]['grouped_stock_id'];
	
//    printr($postdata['stock_id']);
	$data=$obj_rack_master->savedispatch_racknotify($postdata);
	
	
	
	echo $data;

	
}
if($fun == 'savedispatch_stock_shift')
{
	parse_str($_POST['formData'], $post); 
    $r_col=array(); 
    $p_data=$post['shift'];
	$postdata=array();
    foreach($p_data as $postdata){
	
    	$rack_data=$obj_rack_master->getRackQty($postdata['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'','',$postdata);
    	$postdata['stock_id']=$rack_data[0]['grouped_stock_id'];
    	$postdata['pallet_detail']=$post['pallet_detail'];
	    $data=$obj_rack_master->savedispatch_stock_shift($postdata);
    }
	
	
	
	echo $data; 

	
}
if($fun == 'shift_stock_detail') 
{
   $stock_id=$_POST['stock_id'];
    $group_id=$_POST['group_id'];
    $goods_id=$_POST['goods_id'];
    $row=$_POST['row'];
    $col=$_POST['col'];

     $response='';

 
 $goods_master = $obj_goods_master->getGoodsMaster('','',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
     
			$response .= '	<div class="table-responsive">';
			$response .= '<div style="width:100%; position:initial;margin:0 auto;" >';
				$response .= '<table class="table table-striped m-b-none text-small">';
					$response .= '<thead>';
						$response .= '<tr>'; 
							$response .= ' <th>Product Code</th>';
							$response .= ' <th>Qty</th>';
							$response .= ' <th>Shift Qty</th>';
							$response .= '</tr>';
						$response .= '</thead>';  
					$response .= '<tbody>'; 
					foreach($group_id as $id){
					    	$arr=explode('==', $id);
    						        	$desc = $obj_rack_master->getProductCode($arr[3]);
    						        	//printr($desc);
    						        		$response .= '<tr>'; 
                							$response .= ' <td>'.$desc['product_code'].'</td>';
                							$response .= ' <td>'.$arr[2].'</td>';
                							$response .= ' <td><input type="text" name="shift['.$arr[0].'][shift_qty]" id="shift_qty_'.$arr[0].'" value="" onchange="check_qty_Shift('.$arr[0].','.$arr[2].')" class="form-control" required/>
                							                     <input type="hidden" name="shift['.$arr[0].'][alldata]" id="alldata_'.$arr[0].'" value="'.$row .'='.$col.'='.$goods_id.'" class="form-control"/>
                							                     <input type="hidden" name="shift['.$arr[0].'][product_code_id]" id="product_code_id_'.$arr[0].'" value="'.$arr[3] .'" class="form-control"/>
                							                     <input type="hidden" name="shift['.$arr[0].'][grouped_s_id]" id="grouped_s_id_'.$arr[0].'" value="'.$arr[1] .'" class="form-control"/>  
                							                     <input type="hidden" name="shift['.$arr[0].'][product_id]" id="product_id_'.$arr[0].'" value="'.$desc['product'] .'" class="form-control"/>
                							                  </td>';
                							$response .= '</tr>';
                						
                							
    							
    					    
    				    
					}
     	$response .= '</tbody>'; 
     	$response .= '</table>'; 
     	$response .= '</div>'; 
     	$response .= '</div>'; 
    echo $response;
    
} 

if($_GET['fun']=='getLabel_pallet_shift') {
     $addedByInfo = $obj_goods_master->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']); 
    $sel = '';
	 $sel.= '<select name="pallet_detail" id="pallet" style="width: inherit;" class="form-control"><option>Select Rack</option>';
				$d = 1;
					for($i=1;$i<=$_POST['row'];$i++)
					{
						for($r=1;$r<=$_POST['col'];$r++) 
						{
								$rowcol = ''.$i.'@'.$r.'';	
								$data=''; 
						    	if($addedByInfo['country_id']==42)
									$data =$obj_rack_master->getRackLabelCanada($rowcol,$_POST['goods_master_id']);
								else{
								if($_POST['goods_master_id']!='8')
								    $data = $obj_rack_master->getLabel($rowcol,$_POST['goods_master_id']);
								}	
							
								$l=$d;
								if($data!='')
								   $l= $data;
								$sel.= '<option value="'.$i.'='.$r.'='.$_POST['goods_master_id'].'='.$d.'">'.$l.'</option>';
							
								$d++;
								
						}
						
					}

	  $sel.= '</select>';
	  
	echo $sel;
}if($_GET['fun']=='deleterecord') {
  	$stock_id = $_POST['stock_id'];
	$result = $obj_rack_master->deleterecord($stock_id);
}

if($_GET['fun']=='getProductSize') {
  	$product_id = $_POST['product_id']; 
	$result = $obj_rack_master->getProductSize($product_id);
	$response = '';	
	$response .='<select id="size" name="size" class="form-control validate[required]" ><option value="">Select Size</option>';
	if($result){	
		foreach($result as $item){
				
					$response .= '<option value="'.$item['size_master_id'].'=='.$item['volume'].'">';
					//if($item['volume']!=0)
					$response .=  $item['volume'];
					$response .= '   ['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']</option>';					
			
		}
	}
		
	echo $response;
}

/*if($fun == 'getTask') {
	$data= $obj_rack_master->$fun($_POST['verify_option'],$_POST['stock_verify_by']);
	$html ='';
	if($data)
	{
	   $html .='<table border="1" class="table table-striped m-b-none text-small">';
        	$html.='<thead>
            	        <tr>
            	            <th>Sr. No.</th>
            	            <th>Rack Name (Pallet)</th>
            	            <th>Product Code (Description)</th>
            	            <th>Add Quntity</th>
            	        </tr>
            	    </thead>
            	    <tbody>';
        	     $i=1;
        	    foreach($data as $dt)
        	    {
        	        $rack_name = $obj_rack_master->goods_master_detail($dt['goods_id']);
        	        $pallet_details = $dt['row'].'='.$dt['column_no'].'='.$dt['goods_id'];
        	        $rack_qty_dis = $obj_rack_master->getRack_qty_dis($pallet_details,$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'','');
        	        $count = sizeof($rack_qty_dis->rows);
    				
        	        	        if($rack_qty_dis)
    							{   $k=1;
    								foreach($rack_qty_dis->rows as $rack)
    								{   $desc = $obj_rack_master->getProductCode($rack['product_code_id']);
    								    $dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['grouped_s_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
    									$rm_qty=$rack['tot_qty']-$dispatch_qty['total'];
    									
    									if($rm_qty!=0)
    									{
    										$html .='<tr>';
    										            if($k==1)
    										            {
        	                                                $html .='<td rowspan="'.$count.'">'.$i.'.</td>
        	                                                         <td rowspan="'.$count.'">'.$rack_name['name'].' <b>('.$dt['rack_label'].')</b></td>';
    										            }    
        	                                                $html .='<td>'.$desc['product_code'].'</td>																			
    												                 <td><input type="text" name="added_qty" id="added_qty" value="" class="form-control validate[required]"><input type="hidden" name="original_qty" id="original_qty" value="'.$rm_qty.'"></td>
    												 </tr>';
    									}
    									$k++;
    								}
    							}
    							$html .='<tr><td colspan="4"></td></tr>';
                    $i++;	   
        	    }
       $html .='</tbody>
            </table>';
	}
	echo $html;
}*/
if($_GET['fun']=='getSizeWiseReport') {
  	$product_id = $_POST['product_id'];
  	$volume = $_POST['volume'];
  
  	$result = $obj_rack_master->getSizeWiseReport($product_id,$volume,$_POST['fdate'],$_POST['tdate']);  

			$response ='';
			$response .='<div class="form-group" >';
			$response .='<label class="col-lg-3 control-label">';
			$response .='<span class="required">*</span>Size Wise Report</label>';
			$response .='<div class="col-lg-6">';
			$response .='<section class="panel">';
			$response .='<header class="panel-heading">Size Wise Report</header>';
			$response .='<div  id="print_table_size_Wise">';					
						
			$response .='	<table border="1" class="table table-striped m-b-none text-small">
								<th>Product Code</th>												
								<th>Description</th>												
								<th>Qty</th>';
							if(!empty($result))
							{ 
								foreach($result as $rack)
								{
									
									$desc = $obj_rack_master->getProductCode($rack['product_code_id']);
									$dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['grouped_s_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);	
									$rm_qty=$rack['tot_qty']-$dispatch_qty['total'];
									if($rm_qty!=0){
										$response .='<tr><td>'.$desc['product_code'].'</td>	
														<td>'.$desc['description'].'</td>		
														 <td>'.$rm_qty.'</td></tr>';	
								
									}													
								}												
							
							}								
							$response .='</table>';
							$response .='</div>';
					$response .='</section>';
				$response .='</div>';
			 $response .='</div>';
	echo $response;
}
if($_GET['fun']=='rack_qty_inventory'){
	
	$rack_qty_dis = $obj_rack_master->getRack_qty_dis($_POST['pallet_details_dis'],$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$_POST['fdate'],$_POST['tdate'],$_POST['rack_name_dispatch']);
    
	$response ='';
						
								$response .=' <div class="form-group">';
								$response .='<label class="col-lg-3 control-label">';
								$response .='<span class="required">*</span>Rack Information</label>';
								$response .='<div class="col-lg-6"  id="print_table" >';
								$response .='<section class="panel">';
								$response .='<header class="panel-heading">Rack Infomation</header>';
								$response .='<div >';
											
											
								$response .='	<table border="1" class="table table-striped m-b-none text-small">
													<th>Rack Label</th>
													<th>Product Code</th>												
													<th>Description</th>												
													<th>Qty</th>
													';
												if(!empty($rack_qty_dis))
												{ $label='';
													foreach($rack_qty_dis->rows as $rack)
													{
														//$desc = $obj_rack_master->getProductCode($rack['product_code_id']);		
														$dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['grouped_s_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);											
														$rm_qty=$rack['tot_qty']-$dispatch_qty['total'];
						
														if($rm_qty!=0){
															if($label!=$rack['rack_label'])
															    $response .='<tr><td colspan="4"></td></tr>'; 
															$response .='<tr>';
															            if($label!=$rack['rack_label'])
															              $response .='   <th>'.$rack['rack_label'].'</th>';
															            else
															               $response .='<th></th>'; 
															               
															           $response .='<td>'.$rack['product_code'].'</td>																			
																			 <td>'.$rack['code_product'].'</td>												
																			 <td>'.$rm_qty.'</td>
																		</tr>';												
															$label = $rack['rack_label'];												
														}
													
													}
													
											
												}	
												
												$response .='</table>';
												$response .='</div>';
										$response .='</section>';
									$response .='</div>';
								 $response .='</div>';						
												
			echo $response;
}
if($_GET['fun']=='getrackdataforinventory') {
	$rack_qty = $obj_rack_master->getRackQty($_POST['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$_POST['fdate'],$_POST['tdate']);
	//printr($rack_qty);
	$response ='';	
		$response .='<div class="form-group" >';
		$response .='<label class="col-lg-2 control-label"></label>';
	
		$response .='<div class="col-lg-8">';
	
		$response .='<div id="print_product_code">';					
					
		$response .='<table border="1" class="table table-striped m-b-none text-small">';
		$response.='<tr>
						<td colspan="3"> <center><b>Rack Information</b></center></td> 
						
					</tr>';
		$response.='<tr>
						<td><b>Rack Name</b></td>
						<td><b>Rack Position</b></td>
						<td><b>Qty</b></td>
					</tr>';

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
											$k=$d;
											$label = $obj_rack_master->getLabel($n,$rack['goods_master_id']);
                						
        								if($label!='')
        								   $k= $label;
										}	
										$d++;
									}
								}									
								
						$dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['stock_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);

								$rm_qty=$rack['store_qty']-$dispatch_qty['total'];
								$response .='<tr><td>'.$rack['name'].'</td>
												<td align="center">'.$k.'   </td>
												<td>'.$rm_qty.'</td></tr>';
							}
						
						}	
						
						$response .='</table>';
						$response .='</div>';			
			$response .='</div>';
		 $response .='</div>';		
												
	echo $response;
}
if($_GET['fun']=='submitTask') {
    parse_str($_POST['formData'], $post);
    $obj_rack_master->submitTask($post,$_POST['stock_verify_by']);
}
if($_GET['fun']=='verify_records') {
    $obj_rack_master->send_mail_to_verifyrecords($_POST['task_ids'],0);
}
if($_GET['fun']=='submit_comments') {
    parse_str($_POST['formData'], $post);
    $obj_rack_master->submit_comments($post,$_POST['stock_verify_by']);
}
if($_GET['fun']=='close_task') {
    $obj_rack_master->close_task($_POST['task_ids']);
}
if($_GET['fun']=='getProductWiseReport') {
    $rack_qty = $obj_rack_master->getProductRackQty($_POST['product'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
	$response ='';	
		$response .='<div class="form-group" >';
		    $response .='<label class="col-lg-2 control-label"></label>';
	
    		$response .='<div class="col-lg-8">';
    	
    		    $response .='<div id="print_product">';					
    					
        		$response .='<table border="1" class="table table-striped m-b-none text-small">';
                		$response.='<tr>
                						<th colspan="3"> <center><b>Stock Information</b></center></th> 
                					</tr>';
                		$response.='<tr>
                						<th>Product Code</th>
                						<th>Total Available Qty</th>
                					</tr>';
                                if(!empty($rack_qty))
        						{   $total_qty=0;
        							foreach($rack_qty as $rack)
        							{							
        								$dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['stock_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
                                        $rm_qty=$rack['store_qty']-$dispatch_qty['total'];
        								$response .='<tr>
        								                  <td><b>'.$rack['product_code'].'</b><br>'.$rack['description'].'</td>
        												  <td style="text-align: right;">'.$rm_qty.'</td>
        											 </tr>';
                                        $total_qty+=$rm_qty;
        							}
        							$response .='<tr><th colspan="2" style="text-align: right;">TOTAL AVAILABLE QTY      :        '.$total_qty.'</th></tr>';
        						}	
        		$response .='</table>';
    		    $response .='</div>';			
    	    $response .='</div>';
	    $response .='</div>';		
	echo $response;
}
if($_GET['fun']=='getOutwardData') {
  $addedByInfo = $obj_goods_master->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']); 
	$result = $obj_rack_master->getOutwardData($_POST['fdate'],$_POST['tdate'],$_POST['status'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$_POST['product_code_id']);
    $date ='';
    if($_POST['fdate']!='' && $_POST['tdate']!='')
        $date = '<span> <h4>Searching Date From: <b>'.dateFormat(4,$_POST['fdate']).'</b> To: <b>'.dateFormat(4,$_POST['tdate']).'</b><h4></span>';
		$response ='';			$response .=' <div class="form-group">';
								$response .='<section class="panel">';
								$response .='<div >'; 
								$response .='	<table border="1" class="table table-striped m-b-none text-small">
												    <tr><th colspan="10"><b><center><h3>OutWard Report Details</h3> '.$date.'</center>  </b></th></tr>
													<th>Date Of Dispatch</th>
													<th>Party</th>
													<th>Product Code</th>												
													<th>Description</th> 
													<th>Rack Label</th>
													<th>Qty</th>
													<th>Invoice Number</th>
													<th>Dispatch To</th>
													<th>Deleted Invoice</th>
													<th>Cancel Invoice</th>
													'; 
												if(!empty($result))
												{ 
												    $invoice_no='';
													foreach($result as $rack)
													{
													 $row = $rack['row'].'@'.$rack['column_name'];
													 if($addedByInfo['country_id']==42){  
													    	$label = $obj_rack_master->getRackLabelCanada($row,$rack['goods_id']);
													   }else{
													       	$label = $obj_rack_master->getLabel($row,$rack['goods_id']);
													   }
													
													      $delete=$InActive='';
													        if($rack['sales_delete']==1)
													            $delete='Deleted';
													       if($rack['s_status']==0)
													            $InActive='InActive';
														
															$response .='<tr>
															               <td>'.dateFormat(4,$rack['date_added']).'</td>					 														
															                <td>'.$rack['company_name'].'</td>																			
															                <td>'.$rack['product_code'].'</td>																			
																			 <td>'.$rack['code_product'].'</td>												
																			 <td>'.$label.' </td>												
																			 <td>'.$rack['dispatch_qty'].'</td>												
																			 <td><b>'.$rack['invoice_no'].'</b></td>												
																			 <td>'.$rack['consignee'].'</td>										
																			 <td>'.$delete.'</td>										
																			 <td>'.$InActive.'</td>										
																			 
																		</tr>';	
													}
												}
												$response .='</table>';
												$response .='</div>';
										$response .='</section>';
								 $response .='</div>';						
												
			echo $response; 
}
if($_GET['fun']=='getInwardData') {
    $addedByInfo = $obj_goods_master->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']); 
 
	$result = $obj_rack_master->getInwardData($_POST['fdate'],$_POST['tdate'],$_POST['status'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$_POST['product_code_id']);
    $date ='';
    if($_POST['fdate']!='' && $_POST['tdate']!='')
        $date = '<span> <h4>Searching Date From: <b>'.dateFormat(4,$_POST['fdate']).'</b> To: <b>'.dateFormat(4,$_POST['tdate']).'</b><h4></span>';
		$response ='';			$response .=' <div class="form-group">';
								$response .='<section class="panel">';
								$response .='<div >';
								$response .='	<table border="1" class="table table-striped m-b-none text-small">
												    <tr><th colspan="10"><b><center><h3>InWard Report Details</h3> '.$date.'</center></b></th></tr>
													<th>Date Of Dispatch</th>
													<th>Party</th>
													<th>Product Code</th>												
													<th>Description</th> 
													<th>Rack Label</th>
													<th>Qty</th>
													<th>Invoice Number</th>
													<th>Order no</th>'; 
												if(!empty($result))
												{ 
												    foreach($result as $rack)
													{
													    $row = $rack['row'].'@'.$rack['column_name'];
													 if($addedByInfo['country_id']==42){  
													    	$label = $obj_rack_master->getRackLabelCanada($row,$rack['goods_id']);
													   }else{
													       	$label = $obj_rack_master->getLabel($row,$rack['goods_id']);
													   }
													     // printr($label);
													   
															$response .='<tr>
															               <td>'.dateFormat(4,$rack['date_added']).'</td>					 														
															                <td>'.$rack['customer_name'].'</td>																			
															                <td>'.$rack['product_code'].'</td>																			
																			 <td>'.$rack['code_product'].'</td>												
																			 <td>'.$label.' </td>												
																			 <td>'.$rack['qty'].'</td>												
																			 <td><b>'.$rack['invoice_no'].'</b></td>												
																			 <td>'.$rack['order_no'].'</td>										
																		
																		</tr>';	 
													}
												}
												$response .='</table>';
												$response .='</div>';
										$response .='</section>';
								 $response .='</div>';
	echo $response;
}
?>