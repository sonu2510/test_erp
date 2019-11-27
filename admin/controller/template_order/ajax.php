<?php //ruchi 30/4/2015 changes for price uk
include("mode_setting.php");

$fun = $_GET['fun'];
$json=1;
if($_GET['fun']=='dd') {
	$template_id = $_POST['template_id'];
	$data = $obj_template->getTempalte($template_id);
}
else if($fun == 'GetOrderList'){
	$data=$obj_general->GetOrderList();
	echo $data;
}
if($fun == 'checktemplatelist'){
//	printr($_POST);die;
	$product_id = $_POST['product_id'];
	$country_id = $_POST['country_id'];
	$transport = $_POST['transport'];
	$valve = $_POST['valve'];
	$zipper = $_POST['zipper'];
	//$color=$volume='';
	$color = isset($_POST['color'])?$_POST['color']:'';
	$volume = isset($_POST['volume'])?$_POST['volume']:'';
	$detailslist = $obj_template->getaddProductDetails($product_id,$country_id,$transport,$valve,$zipper,$color,$volume);
	$getpricedata = $obj_template->getclientpricepermission($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
	if($detailslist)
	{
	$response = '';
	$response .= '<table class="table table-bordered">';
	$response .= '<thead>';
	$response .= '<tr>';
	$response .= ' <th>Type Of Pouch</th>';
	$response .= '<th>Size</th>';
	$response .= '<th >
                    Dimension<br />
					WxLxG
                    </th>';
	$response .= '<th>Price</th>';
	$response .= ' <th> Color</th>';
	//$response .= ' <th>Shipment Country</th>';
	$response .= ' <th style="width: 170px;">Quantity</th>';
	$response .='<th>Expected Delivery Date</th>';
	$response .= '<th>Action</th>';
	$response .= '</tr>'; 
	$response .= '</thead>'; 
	$response .= '<tbody>'; 
	//$shipmentdetails = $obj_template->getShipmentCountryName($_SESSION['ADMIN_LOGIN_SWISS']);
	$i=1;
	if($detailslist)
	{
		$response .='  <input type="hidden" name="self_shipment" id="self_shipment" value="'.$detailslist[0]['country'].'" />';
	}
	foreach($detailslist as $details)
	{
		//printr($details);die;
		$spout='';
		$accessorie = '';
		if($details['spout'] != 'No Spout')
		{
			$spout = 'with '.$details['spout'];
		}
		if($details['accessorie'] != 'No Accessorie')
		{
			$accessorie = 'with '.$details['accessorie'];
		}
		$dataresult =  strtoupper(substr(preg_replace('/(\B.|\s+)/','',$details['product_name']),0,5)).
		' '.$details['zipper'].' '.$details['valve'].'<br> '.$spout.' '.$accessorie; 
		$response .= '<tr id='.$details['product_template_size_id'].'>';
		$response .= '<td>'.$dataresult.'</td>';
		$response .= '<td>'.$details['volume'].'</td>';
		$response .= '<td>'.$details['width'].'X'.$details['height'].'X'.$details['gusset'].'</td>';
		$response .= '<td style="width:160px">Qty1000+  : '.$details['quantity1000'].'<br>
							Qty2000+  : '.$details['quantity2000'].'<br>
							Qty5000+  : '.$details['quantity5000'].'<br>
							Qty10000+ : '.$details['quantity10000'].'</td>';
		$response .= '<td>';			
		$colorval=json_decode($details['color']);
		$color_detail='';
		$response.='<select  name="color_combo'.$i.'" id="color_combo'.$i.'" class="form-control">';
		foreach($colorval as $value)
		{
			$color_detail=$obj_template->getColor($value);
			$response.='<option value="'.$color_detail[0]['pouch_color_id'].'">'.$color_detail[0]['color'].'</option>';	
		}
		$response.='</select><br>Your Client Price : <input type="text" name="client_price'.$i.'" id="client_price'.$i.'" value="" style="width:100px"';
		if($getpricedata['stock_price_compulsory']=='1') { 
			 $response .='class="form-control validate[required]"/>';
		} else { 
		  $response .='class="form-control validate"/>';
		}
		$response .='<br>Your Customer Order No : <br><input type="text" name="cust_order_no'.$i.'" class="form-control validate" id="cust_order_no'.$i.'" value="" style="width:150px"/>';
		/*$response .= '<td width="200px">';
        $response .= '<label  style="font-weight: normal;">';
        $response .='<input type="radio" name="shipment'.$i.'" id="shipment'.$i.'" value="0"  checked="checked" onclick="selfshipment('.$i.')"/>Self</label> 
                     <label  style="font-weight: normal;">
                     <input type="radio" name="shipment'.$i.'" id="shipment'.$i.'" value="1" onclick="shipmentcountry('.$i.')"/>Client</label>';
		$response .='<br><div style="display:none;" id="address'.$i.'"><span class="required">*</span>
                     <label id="addrr'.$i.'"> Address :</label><textarea name="addressval'.$i.'" id="addressval'.$i.'"  
					 class="form-control validate[required]" ></textarea></div>';*/
       $response .='</td><td> <label class="col-lg-3 control-label"><span class="required">*</span></label>
	   				<input type="hidden" name="stock_price_comp'.$i.'" id="stock_price_comp'.$i.'" value='.$getpricedata['stock_price_compulsory'].'>
                    <input type="text" name="quantity'.$i.'" id="quantity'.$i.'" value="" style="width:100px"  
					class="form-control validate[required,custom[onlyNumberSp],minSize[4]]" /> 
					<br>Note <textarea class="form-control" id="note'.$i.'" name="note'.$i.'"></textarea></td>';
					
		$response .='<td><input type="text" name="due_date'.$i.'"  value="" placeholder="Delivery Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="input_ddate'.$i.'" onClick="inputddate('.$i.')"/></td>';
		$response .= '<td class="del-product"><a href="javascript:void(0);" 
                      onClick="addtocart('.$details['product_template_id'].','.$details['product_template_size_id'].','.$i.','.$details['product_id'].','.$country_id.') " class="btn btn-info btn-xs">Add To Cart</a>
					 </td>';
		$response .='</tr>'; 
			$i++;
	}	
		$response .= '</tbody>'; 
		$response .= '</table>';
		echo $response;
	}
}
if($fun == 'removeTemplate'){
	$obj_template->removeTemplateProduct($_POST['template_size_id']);
}
if($fun == 'removeTemplateOrder'){
 $obj_template->removeTemplateOrder($_POST['template_order_id']);
	
}
//mansi 22-1-2016 (add buyers order no)
if($fun == 'addtocart'){
	$template_id = $_POST['template_id'];
	$template_size_id = $_POST['template_size_id'];
	$color_id = $_POST['color_id'];
	$quantity = $_POST['quantity'];
	$note = $_POST['note'];
	$shipmentcountry = $_POST['shipmentcountry'];
	$transport = $_POST['transport'];
	$product_id = $_POST['product_id'];
	$ship_type = $_POST['ship_type'];
	$userid = $_POST['userid'];
	$address = $_POST['address'];
	$permission = $_POST['permission'];
	$client_name=$_POST['client_name'];
	$d_date=$_POST['d_date'];
	$client_price=$_POST['client_price'];
	$cust_order_no=$_POST['cust_order_no'];
	$buyers_order_no=$_POST['buyers_order_no'];
	$order_type=$_POST['order_type'];
	$reference_no=$_POST['reference_no'];
	//printr($order);
	
	$result = $obj_template->addtocart($client_name,$template_id,$template_size_id,$color_id,$quantity,$shipmentcountry,$product_id,$ship_type,$userid,$address,$permission,$transport,$note,$d_date,$client_price,$cust_order_no,$buyers_order_no,$order_type,'',$reference_no);
}
if($fun == 'client_name'){
	$client_name = $_POST['client_name'];
	$result = $obj_template->getClientName($client_name);
	echo json_encode($result);
}
if($fun == 'update_price')
{
		$history_data = array('product_template_order_id'=>$_POST['postArray']['product_template_order_id'],
		'template_order_id'=>$_POST['postArray']['template_order_id'],
		'user_id'=>$obj_session->data['ADMIN_LOGIN_SWISS'],
		'user_type_id'=>$obj_session->data['LOGIN_USER_TYPE'],
		'price'=>$_POST['postArray']['price'],
		'status'=>$_POST['postArray']['status'],
		);
		$result1 = $obj_template->updatePrice($history_data,1);		
	echo $result1;
}
if($fun == 'updatePriceUk')
{
	$result1 = $obj_template->updatePriceUk($_POST['price'],$_POST['template_order_id']);		
	echo $result1;
}
if($fun == 'updatestockorderstatus')
{	
	//echo $_POST['adminEmail'];
	//printr($_POST['postArray']);//die;
	$value = '';
	$ddate_value = '';
	$dis_qty ='';
	$rem_qty='';
	$total_qty='';
	if(isset($_POST['postArray']['datetime']))
	{
		$value .= "date='".$_POST['postArray']['datetime']."',";
	}
	if(isset($_POST['postArray']['dis_qty']))
	{
		$dis_qty = ",dis_qty='".$_POST['postArray']['dis_qty']."'";
	}
	if(isset($_POST['postArray']['review']))
	{
		$value .= "review='".$_POST['postArray']['review']."',";
	}
	
	if(isset($_POST['postArray']['status']) && ($_POST['postArray']['status']==1 || $_POST['postArray']['status'] == 2))
	{
		$arr=array('user_id'=>$obj_session->data['ADMIN_LOGIN_SWISS'],
					'user_type_id'=>$obj_session->data['LOGIN_USER_TYPE'],
					'action'=>$_POST['postArray']['status'],
					'currdate'=>$_POST['postArray']['currdate']
				);
		$value .= "process_by='".json_encode($arr)."',";
		
	}
	if(isset($_POST['postArray']['status']) && $_POST['postArray']['status']==3)
	{
		$arr=array('user_id'=>$obj_session->data['ADMIN_LOGIN_SWISS'],
					'user_type_id'=>$obj_session->data['LOGIN_USER_TYPE'],
					'currdate'=>$_POST['postArray']['currdate']
				);
		$value .= "dispach_by='".json_encode($arr)."'";
	}
	if(isset($_POST['postArray']['due_date']))
	{	
		$con ='';
		$ddate_value .= "new_final_ddate='".$_POST['postArray']['due_date']."'";
		if(!empty($_POST['postArray']['status']))
		{
			$ddate_value .= " , ";
			$con = ",user_id = '".$obj_session->data['ADMIN_LOGIN_SWISS']."' , user_type_id = '".$obj_session->data['LOGIN_USER_TYPE']."'";
		}			
	}
	if(isset($_POST['postArray']['old_date']) && isset($_POST['postArray']['old_review']))
	{
		$ddate_value .=",old_ddate='".$_POST['postArray']['old_date']."' , old_review='".$_POST['postArray']['old_review']."',user_id='".$_POST['postArray']['u_id']."' , user_type_id='".$_POST['postArray']['u_type_id']."',";
	}
	if(isset($_POST['postArray']['reason']))
	{
		$ddate_value .= "new_final_review='".$_POST['postArray']['reason']."'";
	}
	
	if(isset($_POST['postArray']['status']) && !empty($_POST['postArray']['status']))
	{
		if($_POST['postArray']['status'] != '3')
		{
			$value .= "status='".$_POST['postArray']['status']."'";
		}
	}	
	if(isset($_POST['postArray']['total_qty']))
	{
		$total_qty = $_POST['postArray']['total_qty'];
	}
	
	if(isset($_POST['postArray']['status']) && $_POST['postArray']['status'] == 2)
	{
		$history_data = array('product_template_order_id'=>$_POST['postArray']['product_template_order_id'],
		'template_order_id'=>$_POST['postArray']['template_order_id'],
		'user_id'=>$obj_session->data['ADMIN_LOGIN_SWISS'],
		'user_type_id'=>$obj_session->data['LOGIN_USER_TYPE'],
		'currdate'=>$_POST['postArray']['currdate'],
		);
		$result1 = $obj_template->updatePrice($history_data,0);	
		$rem_qty = ",decline_qty='".$_POST['postArray']['rem_qty']."'";
	}
	if(isset($_POST['postArray']['reason']) && isset($_POST['postArray']['due_date']))
	{
	
		$cond_date = "template_order_id = '" .(int)$_POST['postArray']['template_order_id']. "' , product_template_order_id = '".(int)$_POST['postArray']['product_template_order_id']."',edited_by_user_id = '".$obj_session->data['ADMIN_LOGIN_SWISS']."',edited_by_user_type_id='".$obj_session->data['LOGIN_USER_TYPE']."' $con";
		$result = $obj_template->insertDelayHistory($ddate_value,$cond_date);
	}
		$delay='';
		
		if(!empty($_POST['postArray']['status']) && $_POST['postArray']['status']!='')
		{
			//echo $value;die;
			$cond = "template_order_id = '" .(int)$_POST['postArray']['template_order_id']. "' AND product_template_order_id = '".(int)$_POST['postArray']['product_template_order_id']."'";
			
			$result = $obj_template->updatestockorderstatus($value,$cond,$dis_qty,$rem_qty,$_POST['postArray']['status'],$_POST['postArray']['template_order_id'],$_POST['postArray']['product_template_order_id'],$total_qty);
			
			$post_status = $_POST['postArray']['status'];
		}
		
		
		$template_order_id ='';
		if(!isset($_POST['postArray']['send']))
		{
			$template_order_id = $_POST['postArray']['template_order_id'];
		}
	$post=array($template_order_id.'=='.$_POST['postArray']['product_template_order_id'].'=='.$_POST['postArray']['client_id']);
	if($_POST['postArray']['status']=='')
	{
		if(isset($_POST['postArray']['send']))
		{
			$post=array(''.'=='.$_POST['postArray']['product_template_order_id'].'=='.$_POST['postArray']['client_id']);
			$send = '1';
			$result = '';
		}		
		$rel = $obj_template->sendDelayOrderEmail($post,1,$_POST['adminEmail'],$send);
	}
	if($_POST['postArray']['status']==3)
	{	
		
		$rel = $obj_template->sendDispatchOrderEmail($post,$post_status,$_POST['adminEmail']);
	}
	if($_POST['postArray']['status']!=1 && $_POST['postArray']['status']!='' && $_POST['postArray']['status']!=3)
	{	
		
		$rel = $obj_template->sendOrderEmail($post,$post_status,$_POST['adminEmail']);
	}
	echo $result;
}
if($fun == 'displaycountry')
{
	$product_id = $_POST['template_id'];
	$user_id = $_POST['userid'];
	$result = $obj_template->gettemplatetitle($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$product_id,'','','');
	$str='';$contry_id='';
	foreach($result as $res)
	{
		$contry_id=json_decode($res['country']);
		foreach($contry_id as $id)
		{
			$str.='country_id = '.$id.' OR ';
		}
		
		
	}
	$countryval = substr($str,0,-3);
	
	$arr= $obj_template->getmultiplecountry($countryval);
	echo json_encode($arr);
}
if($fun == 'displaytranspotation')
{
	$country_id = $_POST['country_id'];
	$product_id = $_POST['product_id'];
	$user_id = $_POST['userid'];
	$result = $obj_template->gettemplatetitle($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$product_id,$country_id,'','');
	foreach($result as $res)
	{
		echo $res['transportation_type']."==";
	}
}
if($fun == 'displayzippervalve')
{
	$product_id = $_POST['product_id'];
	$country_id = $_POST['country_id'];
	$transport = $_POST['transport'];
	$valve = $obj_template->gettemplatetitle($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$product_id,$country_id,$transport,'valve');
	$zipper = $obj_template->gettemplatetitle($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$product_id,$country_id,$transport,'zipper');
	foreach($valve as $val)
		$result['valve'][]=$val['valve'];
	foreach($zipper as $zip)
		$result['zipper'][]=$zip['zipper'];
	echo json_encode($result);
}
/*if($fun == 'getTxtRecord')
{
	parse_str($_POST['formData'], $post);
	//printr($post);
	$result = $obj_template->getSelectedRecord($post);
	$i=1;
	$response = '';
	$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
		$response .= '<table class="table table-striped m-b-none text-small">';
			$response .= '<thead>';
				$response .= '<tr>'; 
					$response .= ' <th>Sr No.</th>';
					$response .= ' <th>Order No</th>';
					$response .= ' <th>Order Date</th>';
					$response .= ' <th>Product Description</th>';
					$response .= ' <th>Order Qty</th>';
					$response .= ' <th>Remaining  Qty</th>';
					$response .= ' <th>Dispatch Qty</th>';
				$response .= '</tr>';
			$response .= '</thead>'; 
		$response .= '<tbody>'; 
		foreach($result as $key=>$data)
		{	$dis_qty=$obj_template->getDispatchQty($data['template_order_id'],$data['product_template_order_id']);
			$rem_qty = $data['quantity']-$dis_qty['total_dis_qty'];
			$response .= '<tr>';
				$response .= '<td>'.$i.'</td>';
				$response .= '<td>'.$data['gen_order_id'].'</td>';
				$response .= '<td>'.$data['date_added'].'</td>';
				$response .= '<td><b style="color:#ff1a1a;">'.$data['volume'].'</b> <b>'.$data['color'].'</b> '.$data['product_name'].'</td>';
				$response .= '<td>'.$data['quantity'].'</td>';
				$response .= '<td>'.$rem_qty.'</td>';
				$response .= '<td><input type="text" name="dis_qty[]" value="" id="dis_qty_'.$key.'" class="form-control validate[required,custom[onlyNumberSp]]" onchange="dis_qty('.$key.')">
								  <input type="hidden" name="template_order_id[]" value="'.$data['template_order_id'].'" id="template_order_id">
								  <input type="hidden" name="product_template_order_id[]" value="'.$data['product_template_order_id'].'" id="product_template_order_id">
								  <input type="hidden" name="rem_qty[]" value="'.$rem_qty.'" id="rem_qty_'.$key.'">
								  <input type="hidden" name="key[]" value="'.$key.'" id="key">
								  <input type="hidden" name="tot_qty[]" value="'.$data['quantity'].'" id="tot_qty">
								  <input type="hidden" name="client_id[]" value="'.$data['client_id'].'" id="client_id">
								  <input type="hidden" name="gen_order_id[]" value="'.$data['gen_order_id'].'" id="gen_order_id">
							  </td>';
			$response .= '</tr>';	
			$i++;
		}
		$response .= '</tbody>'; 
		$response .= '</table>'; 
	$response .= '</div>';
	
	echo $response;
}*/
if($fun == 'getTxtRecord')
{
	/*parse_str($_POST['formData'], $post);
	printr($post);die;*/
	$result = $obj_template->getSelectedRecord($_POST['user_val'],$_POST['order_type']);
	//printr($result);
	if(!empty($result))
	{
		$i=1;
		$response = '';
		$response .= '<div style="width:80%; position:initial;margin:0 auto;" >';
			$response .= '<table class="table table-striped m-b-none text-small">';
				$response .= '<thead>';
					$response .= '<tr>'; 
						$response .= ' <th>Sr No.</th>';
						$response .= ' <th>Order No</th>';
						$response .= ' <th>Order Date</th>';
						$response .= ' <th>Order Type</th>';
						$response .= ' <th>Product Description</th>';
						$response .= ' <th>Transportation</th>';
						$response .= ' <th>Order Qty</th>';
						$response .= ' <th>Remaining  Qty</th>';
						$response .= ' <th>Dispatch Qty</th>';
						$response .= ' <th>Remarks</th>';
					$response .= '</tr>';
				$response .= '</thead>'; 
			$response .= '<tbody>'; 
			foreach($result as $key=>$data)
			{
			//	printr($data);die;
					$dis_qty=$obj_template->getDispatchQty($data['template_order_id'],$data['product_template_order_id']);
				$rem_qty = $data['quantity']-$dis_qty['total_dis_qty'];
				$response .= '<tr>';
					$response .= '<td>'.$i.'</td>';
					$response .= '<td>'.$data['gen_order_id'].'</td>';
					$response .= '<td>'.$data['date_added'].'</td>';
					$response .= '<td>'.ucwords($data['order_type']).'</td>';
					$response .= '<td><b style="color:#ff1a1a;">';
								if($data['volume']!='')
									$response .= $data['volume'].'</b> <b>'.$data['color'];
								else
									$response .= $data['width'].'X'.$data['height'].'X'.$data['gusset'];
									
					$response .='</b> '.$data['product_name'].'</td>';
					$response .= '<td>'.$data['transportation_type'].'</td>';
					$response .= '<td>'.$data['quantity'].'</td>';
					$response .= '<td>'.$rem_qty.'</td>';
					$response .= '<td>';
							if(isset($data['custom_order_id']))
							{
								$validate = '';
								$n='1';
							}
							else
							{
								$validate = 'validate[custom[onlyNumberSp]]';
								$n=0;
							}
								$cust_order_id = isset($data['custom_order_id']) ? $data['custom_order_id'] : '' ;
								$response .='<input type="text" name="dis_qty[]" value="" id="dis_qty_'.$key.'" class="form-control '.$validate.'" onchange="dis_qty('.$key.','.$n.')">
											  <input type="hidden" name="template_order_id[]" value="'.$data['template_order_id'].'" id="template_order_id">
											  <input type="hidden" name="product_template_order_id[]" value="'.$data['product_template_order_id'].'" id="product_template_order_id">
											  <input type="hidden" name="rem_qty[]" value="'.$rem_qty.'" id="rem_qty_'.$key.'">
											  <input type="hidden" name="key[]" value="'.$key.'" id="key">
											  <input type="hidden" name="tot_qty[]" value="'.$data['quantity'].'" id="tot_qty">
											  <input type="hidden" name="client_id[]" value="'.$data['client_id'].'" id="client_id">
											  <input type="hidden" name="gen_order_id[]" value="'.$data['gen_order_id'].'" id="gen_order_id">
											   <input type="hidden" name="stock_order_id[]" value="'.$data['stock_order_id'].'" id="stock_order_id">
											   <input type="hidden" name="custom_order_id[]" value="'.$cust_order_id.'" id="custom_order_id">
								</td>
								<td>
									<select name="remark_sel[]" id="remark_sel_'.$key.'" class="form-control">
										<option value="">Select Remarks</option>
										<option value="Not in stock">Not in stock</option>
										<option value="Not available">Not available</option>
										<option value="Remaining">Remaining</option>
									</select>
								</td>';
				$response .= '</tr>';	
				$i++;
			}
			$response .= '</tbody>'; 
			$response .= '</table>'; 
		$response .= '</div>';
		$i=1;
	}
	else
	{
		$response = '<div style="width:80%; position:initial;margin:0 auto;" >No Records Found!!!</div>';
		$i=0;
	}
		
	$arr_result['response'] = $response;
	$arr_result['records'] = $i;	
	echo json_encode($arr_result);
}
if($fun == 'get_user_dis_data')
{
	//printr($_POST['user_info']);die;
	$ex_id=explode("=",$_POST['user_info']);
	$orders_user_id = $ex_id[1];
	$orders_user_type_id = $ex_id[0];

	$user_data_of_order = $obj_invoice->userOrderData($orders_user_id,$orders_user_type_id,$_POST['order_type']);

	$stock_o_no = $user_data_of_order['stock_order_no'];
	$custom_o_no = $user_data_of_order['custom_order_no'];
	
	$order_admin_id = $user_data_of_order['order_user_id'];
	
	$stock_order_number_arr = $obj_invoice->getStockId($stock_o_no);
	$multi_custom_order_id_arr = $obj_invoice->getCustomId($custom_o_no);
	$trans ='';
	$sodh=$sodh_client='';
		$firsttime = true;
	
	$no_td = $td1 = $cust_total=$stk_total='0';
	//$order_no[]='';
	//printr($user_data_of_order);
	//printr($stock_order_number_arr);//die;
	$result ='';
	$result = '<input type="hidden" name="added_invoice_id" value="" id="added_invoice_id"/>';
	if(!empty($stock_order_number_arr))
	{
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
		$done_status=0;
		$orders = $obj_invoice->GetStockOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND t.status = 1',$stock_order_id['client_id'],$stock_order_id['stock_order_id'],$sodh_client,$sodh,'',$order_admin_id,$done_status);
		//printr($orders);//die;
		$arr_result['response']='';
		if(!empty($orders ))
	{	
			
			foreach($orders as $key=>$stock_order)
			{
				$trans = $key;
				$result.='<div class="form-group">
							<label class="col-lg-3 control-label" style="width: 10%;">Price ('.$key.')</label> 
							<div class="col-lg-9 div_len" >
								<section class="panel">
								  <div class="table-responsive">
									<table class="table table-striped b-t text-small">
									  <thead>
										  <tr>
											<th>Stock Order No</th>
											<th>Product Name</th>
											<th>Quntity</th>
											<th>Option</th>
											<th>Dimention</th>
											<th>Color</th>
											<th>Price / pouch</th>';
											if($_POST['order_type']!='sample')
												$result.='<th>Total Price</th>';
								  $result.='<th id="stk_net_th" style="display:none;">Net Weight(In kgs)</th>
											<th id="stk_box_th" style="display:none;">Box Weight(In kgs)</th>
											<th id="stk_boxqty_th" style="display:none;">Box Qty</th>
											<th id="stk_tot_box_th" style="display:none;">Total Box</th>
											<th id="stk_rem_qty_th" style="display:none;">Remaining<br>Qty</th>
										</tr>
									</thead>
									<tbody>';//<span style="color:red;font-weight:bold">('.$order['volume'].')</span>
										//$no_td = '0';
										$stk_total = count($stock_order);
										//printr();
										foreach($stock_order as $stock)
										{
											$result.='<tr>';
											foreach($stock as $s_data)
											{
												$dis_qty=$obj_invoice->getDispatchQty($s_data['template_order_id'],$s_data['product_template_order_id']);
												$zipper = $obj_template->getZipper($s_data['zipper']);
												$spout = $obj_template->getSpout($s_data['spout']);
												$accessorie = $obj_template->getAccessorie($s_data['accessorie']);
												$order_no[]=$s_data['gen_order_id'];
												$ref_no[]=$s_data['reference_no'];
												
												$dataDescArr = json_decode($s_data['description'],true);
												$make_id = '1';
												if(!empty($dataDescArr))
												{
													foreach($dataDescArr as $qty=>$val)
													{
														
														if($s_data['quantity'] < 2000)
															$make_id = $dataDescArr['1000']['make_pouch'];
														else if($s_data['quantity'] >= 2000 && $s_data['quantity'] < 5000)
															$make_id = $dataDescArr['2000']['make_pouch'];
														else if($s_data['quantity'] >= 5000 && $s_data['quantity'] < 10000)
															$make_id = $dataDescArr['5000']['make_pouch'];
														else
															$make_id = $dataDescArr['10000']['make_pouch'];	
													}	
												}
												
												if(strstr($s_data['volume'], '/'))
												{
													$slash_size = explode('/', $s_data['volume']);
													$s_data['volume'] = $slash_size[0];
												}
												
												
												$size = preg_replace('/-[^0-9]/', '', $s_data['volume']);
												$mea = preg_replace('/[^a-zA-Z]/', '', $s_data['volume']);
												$mea_id = $obj_template->getMeasurementid($mea);
												//printr($size.'=='.$mea);
												//add sonu 21-4-2017 for jerky bages volume
												if(strstr($size, '-'))
												{
													$dash_size = explode('-',$size);
													$size = $dash_size[0];
													
												}
												//end
												
												
												$ship_contry = $s_data['shipment_country'];
												$result.='
																   <input type="hidden" name="zipper_id_'.$s_data['template_order_id'].'" value="'.$zipper['product_zipper_id'].'"/>
																   <input type="hidden" name="spout_id_'.$s_data['template_order_id'].'" value="'.$spout['product_spout_id'].'"/>
																   <input type="hidden" name="accessorie_id_'.$s_data['template_order_id'].'" value="'.$accessorie['product_accessorie_id'].'"/>
																   <input type="hidden" name="valve_id_'.$s_data['template_order_id'].'" value="'.$s_data['valve'].'"/>
																   <input type="hidden" name="make_id_'.$s_data['template_order_id'].'" value="'.$make_id.'"/>
																   <input type="hidden" name="product_id_'.$s_data['template_order_id'].'" value="'.$s_data['product_id'].'"/>
																   <input type="hidden" name="pouch_color_id_'.$s_data['template_order_id'].'" value="'.$s_data['pouch_color_id'].'"/>
																   <input type="hidden" name="stock_order_no_'.$s_data['template_order_id'].'" value="'.$s_data['gen_order_id'].'"/>
																   <input type="hidden" id="dis_qty_id_'.$no_td.'" name="dis_qty_'.$s_data['template_order_id'].'" value="'.$dis_qty['total_dis_qty'].'"/>
																   <input type="hidden" name="mea_'.$s_data['template_order_id'].'" value="'.$mea_id['product_id'].'"/>
																   <input type="hidden" name="size_'.$s_data['template_order_id'].'" value="'.$size.'"/>
																   <input type="hidden" name="dimension_'.$s_data['template_order_id'].'" value="'.$s_data['width'].'X'.$s_data['height'].'X'.$s_data['gusset'].'"/>
																  															
																<input type="hidden" name="stk_invoice_id'.$s_data['template_order_id'].'" value="" id="stk_invoiceid_'.$no_td.'"/>
																<input type="hidden" name="reference_no_'.$s_data['template_order_id'].'" value="'.$s_data['reference_no'].'"/>';
												                
												$result.='<tr id="stock-row-'.$s_data['template_order_id'].'">
															<input type="hidden" name="template_order_id[]" value="'.$s_data['template_order_id'].'"/>
															<td>'.$s_data['gen_order_id'].'</td>
															<td>'.$s_data['product_name'].'</td>
															<td>'.$dis_qty['total_dis_qty'].'</td>
															<td>'.ucwords($s_data['zipper']).' '.ucwords($s_data['valve']).' '.ucwords($s_data['spout']).' '.ucwords($s_data['accessorie']).'</td>
															<td>'.$s_data['width'].'X'.$s_data['height'].'X'.$s_data['gusset'].'<span style="color:red;font-weight:bold"> ('.$s_data['volume'].')</span></td>
															<td>'.$s_data['color'].'</td>';
															if($s_data['order_type']=='sample')
															{
																$result.='<td><input type="text" name="rate_'.$s_data['template_order_id'].'" value="" class="form-control validate[required]"/></td>';
															}
															else
															{
																$result.='<input type="hidden" name="rate_'.$s_data['template_order_id'].'" value="'.$s_data['price'].'"/>
																		<td>'.$s_data['currency_code'].' '.$s_data['price'].'</td>
																		<td>'.$s_data['currency_code'].' '.$s_data['price']*$s_data['quantity'].'</td>';
															}
												$result.='	
															<td id="div_td_'.$no_td.'" style="display:none;"><input type="text" class="form-control validate[required]" name="netweight_'.$s_data['template_order_id'].'" id="netweight_'.$no_td.'" value=""></td>
															<td id="box_td_'.$no_td.'" style="display:none;"><input type="text" class="form-control" name="boxweight_'.$s_data['template_order_id'].'" id="boxweight_'.$no_td.'" value=""></td>														
															<td id="box_qty_td_'.$no_td.'" style="display:none;"><input type="text" class="form-control" name="box_qty_'.$s_data['template_order_id'].'" id="box_qty_'.$no_td.'" value=""></td>
															<td id="tot_box_td_'.$no_td.'" style="display:none;"><input type="text" class="form-control" name="total_box_'.$s_data['template_order_id'].'" id="total_box_'.$no_td.'" value=""></td>
															<td id="rem_qty_td_'.$no_td.'" style="display:none;"><input type="text" class="form-control" name="rem_qty_'.$s_data['template_order_id'].'" id="rem_qty_'.$no_td.'" value=""></td>';
															
															
												$result.='</tr>';
												$no_td++;
											}
											$result.='</tr>';
										}
										$td1 = $no_td;
										
						$result.='</tbody>
								</table>
							  </div>
							</section> 
						</div>
					  </div>';
			
			}
			}
		
	}
		//custom order
		$cust_cond='';
		$ftime=true;
		$custom_done_status = '0';
		//printr($multi_custom_order_id_arr);
		if(!empty($multi_custom_order_id_arr))
		{
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
			$getData = " custom_order_id,mco.added_by_user_id,mco.added_by_user_type_id,reference_no, customer_name, shipment_country_id,mco.multi_custom_order_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,mco.status,mco.custom_order_status,discount";
			$data = $obj_invoice->getCustomOrder($cust_cond,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$order_admin_id,$custom_done_status);
				//echo 'hi';
				
			$cust_qty='';
			$fsttime=true;
			//printr($data);
			foreach($data as $custom_order_id)
			{	
				if($fsttime)
				{	//printr('kju');
					$cust_qty .= 'mcoq.custom_order_id = "'.$custom_order_id['custom_order_id'].'"';
					//printr($cust_qty);
					$fsttime = false;
				}
				else
				{
					$cust_qty .= 'OR mcoq.custom_order_id = "'.$custom_order_id['custom_order_id'].'"';
						//printr($cust_qty);
				}
				
				$reference_no['ref_o_no_'.$custom_order_id['multi_custom_order_id']] = $custom_order_id['reference_no'];
				
				$cust_o_id[$custom_order_id['multi_custom_order_id']]=$custom_order_id['multi_custom_order_number'];
			}
			//printr($cust_qty);
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
							}
						}	
				}
			$cust_total = count($new_data);
			//printr($new_data);
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
					$trans = 'By '.$k;
					$result.='<div class="form-group">
								<label class="col-lg-3 control-label" style="width: 10%;">Price (By '.$k.')</label> 
								<div class="col-lg-9 div_len">
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
												<th>Custom Order <br> No</th>
												<th>Product Name</th>
												<th>Quntity</th>';
												
												if($custom_order_type != 1){ 
													$result.='<th>Option(Printing Effect )</th>';
												 } 
												 
												$result.='<th>Dimension (Make Pouch)</th>
												<th>Layer:<br>Material:<br>Thickness</th>';
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
												<th>Total Price</th>';
												if($k=='pickup') {
													if($shipment_country_id==111){ 
														$result.='<th> Price / pouch  With Tax </th>
																 <th>Total Price With Tax </th>';
												 } }
												 $result.='<th>Cylinder Price</th>
															<th>Tool Price</th>';
												if($status != 1){
														$result.='<th>Action</th>';
												 }
												 $result.='<th id="cust_net_th" style="display:none;">Net Weight(In Kgs)</th>
														   <th id="cust_box_th" style="display:none;">Box Weight(In Kgs)</th>
														   <th id="cust_boxqty_th" style="display:none;">Box Qty</th>
														   <th id="cust_tot_box_th" style="display:none;">Total Box</th>
														   <th id="cust_rem_qty_th" style="display:none;">Remaining<br>Qty</th>
											  </tr>
										  </thead>
										  <tbody>';
											$i=$td1;
											foreach($qty_data as $skey=>$sdata)
											{
												$result.='<tr>';
												foreach($sdata as $soption)
												{//printr($soption['shipment_country_id']);
													$ship_contry = $soption['shipment_country_id'];
													$zipper = $obj_template->getZipper($soption['zipper_txt']);
													$spout = $obj_template->getSpout($soption['spout_txt']);
													$accessorie = $obj_template->getAccessorie($soption['accessorie_txt']);
													
													$cust_size = preg_replace('/[^0-9]/', '', $soption['volume']);
													$cust_mea = preg_replace('/[^a-zA-Z]/', '', $soption['volume']);
													$cust_mea_id = $obj_template->getMeasurementid($cust_mea);
													
													$result.='
																   <input type="hidden" name="cust_zipper_id_'.$soption['custom_order_id'].'" value="'.$zipper['product_zipper_id'].'"/>
																   <input type="hidden" name="cust_spout_id_'.$soption['custom_order_id'].'" value="'.$spout['product_spout_id'].'"/>
																   <input type="hidden" name="cust_accessorie_id_'.$soption['custom_order_id'].'" value="'.$accessorie['product_accessorie_id'].'"/>
																   <input type="hidden" name="cust_valve_id_'.$soption['custom_order_id'].'" value="'.$soption['valve_txt'].'"/>
																   <input type="hidden" name="cust_make_id_'.$soption['custom_order_id'].'" value="'.$soption['make_id'].'"/>
																   <input type="hidden" name="cust_product_id_'.$soption['custom_order_id'].'" value="'.$soption['product_id'].'"/>
																   <input type="hidden" name="cust_pouch_color_id_'.$soption['custom_order_id'].'" value="-1"/>
																   <input type="hidden" id="cust_dis_qty_'.$i.'" name="cust_dis_qty_'.$soption['custom_order_id'].'" value="'.$soption['dis_qty'].'"/>
																   <input type="hidden" name="cust_mea_'.$soption['custom_order_id'].'" value="'.$cust_mea_id['product_id'].'"/>
																   <input type="hidden" name="cust_order_'.$soption['custom_order_id'].'" value="'.$cust_mea_id['product_id'].'"/>
																   <input type="hidden" name="cust_size_'.$soption['custom_order_id'].'" value="'.$cust_size.'"/>
																   <input type="hidden" name="cust_dimension_'.$soption['custom_order_id'].'" value="'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.$soption['gusset'].'"/>
																   <input type="hidden" name="multi_cust_order_no_'.$soption['custom_order_id'].'" value="'.$cust_o_id[$soption['multi_custom_order_id']].'"/>
															
																	<input type="hidden" name="cust_invoice_id'.$soption['custom_order_id'].'" value="" id="cust_invoiceid_'.$i.'"/>';
													$order_no[]=$cust_o_id[$soption['multi_custom_order_id']];
													
													$ref_no[]=$reference_no['ref_o_no_'.$soption['multi_custom_order_id']];
													
													$result.='<tr id="custom-row-'.$soption['custom_order_price_id'].'">
																	<input type="hidden" name="multi_custom_order_id[]" value="'.$soption['multi_custom_order_id'].'=='.$soption['custom_order_id'].'"/>
																	<input type="hidden" name="reference_no_'.$soption['custom_order_id'].'" value="'.$reference_no['ref_o_no_'.$soption['multi_custom_order_id']].'"/>
																	<td>'.$cust_o_id[$soption['multi_custom_order_id']].'</td>
																	<td>'.$soption['product_name'].'</td>
																	<td>'.$soption['dis_qty'].'</td>
																	<td>'.ucwords($soption['text']).' ('.$soption['printing_effect'].')'.'</td>
																	<td>'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.$soption['gusset']; 
																		if($data[0]['product_name']!=10)
																		{
																			if($soption['volume']>0) 
																				$result.='<span style="color:red;font-weight:bold"> ('.$soption['volume'].') <span>';
																		}
																		else
																		{
																			 $result.='<span style="color:red;font-weight:bold"> (Custom) ('.$soption['make'].')  <span></td>';
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
																		 $per_pouch = $pretot;
																		 $result.=$pretot.'"<br />
																				<b>Discount ("'.$soption['discount'].'" %) : </b>';
																				$predis = $pretot*$soption['discount']/100; 
																				$result.=$obj_invoice->numberFormate($predis,"3").'<br />
																				 <b>Final Total : </b>
																				'.$dat['currency'].' '.$obj_invoice->numberFormate(($pretot-$predis),"3");
																	}
																	else
																	{ 
																		 $per_pouch = $obj_invoice->numberFormate((($soption['totalPrice'] / $skey) / $currency_price),"3");
																		$result.=$currency.' '.$obj_invoice->numberFormate((($soption['totalPrice'] / $skey) / $currency_price),"3");
																	}
																	
																	$result.=' <input type="hidden" name="cust_rate_'.$soption['custom_order_id'].'" value="'.$per_pouch.'"/></td>
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
																	$result.='<td id="cust_div_td_'.$i.'" style="display:none;"><input type="text" class="form-control validate[required]" name="netweight_cust_'.$soption['custom_order_id'].'" id="netweight_'.$i.'" value=""></td>
																			  <td id="cust_box_td_'.$i.'" style="display:none;"><input type="text" class="form-control" name="boxweight_cust_'.$soption['custom_order_id'].'" id="boxweight_'.$i.'" div_attr="cust" value=""></td>   
																			  <td id="cust_box_qty_d_'.$i.'" style="display:none;"><input type="text" class="form-control" style="width:80px" name="box_qty_cust_'.$soption['custom_order_id'].'" id="box_qty_'.$i.'" value=""></td>
																			  <td id="cust_tot_box_td_'.$i.'" style="display:none;"><input type="text" class="form-control" name="total_box_cust_'.$soption['custom_order_id'].'" id="total_box_'.$i.'" value=""></td>
																			  <td id="cust_rem_qty_td_'.$i.'" style="display:none;"><input type="text" class="form-control" name="rem_qty_cust_'.$soption['custom_order_id'].'" id="rem_qty_'.$i.'" value=""></td>
																			  <td id="cust_jobcard_name_td_'.$i.'" style="display:none;"><input type="text" class="form-control"  style="width:170px"  name="job_card_name_'.$soption['custom_order_id'].'" id="job_card_name_'.$i.'" value="" placeholder="Job Card Name"></td></tr>';
															$i++;}
															$result .='</tr>';
															
														}
														$td2=$i;
														$result .='</tbody>
															</table>
														  </div>
														</section> 
													</div>
												  </div>';
							}
				}
						//echo $i;
					//	printr($order_no);
						$uni_arr = array_unique($order_no);
						$u_arr = implode(',',$uni_arr);
						//printr($u_arr);
						$uni_arr_ref_no = array_unique($ref_no);
						$u_arr_ref_no = implode(',',$uni_arr_ref_no);
						$total_row = $stk_total + $cust_total;
						
					if($result =='')
					{
						$result .='No Records found!!!';
					}
					
					$result .='<input type="hidden" id="total_td" name="total_td" value="'.$total_row.'">
								<input type="hidden" id="uni_ids" name="uni_ids" value="'.$u_arr.'">
								<input type="hidden" id="order_by" name="order_by" value="'.$_POST['user_info'].'">
								<input type="hidden" id="trans" name="trans" value="'.$trans.'">
								<input type="hidden" name="ship_country" value="'.$ship_contry.'"/>
								<input type="hidden" id="uni_ref_ids" name="uni_ref_ids" value="'.$u_arr_ref_no.'">';
				                
				
					
					$arr_result['response'] = $result;
					$arr_result['total_row'] = $total_row;	
					
		echo json_encode($arr_result);
}
if($fun == 'addInvoiceData')
{
	ini_set('max_input_vars',3000);
	//printr($_POST['formData']);die;
	parse_str($_POST['formData'], $post);
	$invoice_id = $obj_invoice->addInvoiceData($post);//die;
	$inv_id = explode("==",$invoice_id[0]);

	//use $inv_id[0] as arg in below fun.
	$data = $obj_invoice->getInvoiceProductWithBox($inv_id[0]);//$inv_id[0]
	//printr($data);die;
	/*$invoice_id = Array
	(
		0' => '159==816==1079',
		1' => '159==815==1078',
	);*/
	
	foreach($data as $key=>$d)
	{	
		$transportation=decode($d['transportation']);
		$arr[] = array('box_weight' => $d['box_weight'],
						'box_qty' => $d['quantity'],
						'cust_box_weight'  => $d['cust_box_weight'],
						'cust_quantity' => $d['cust_quantity'],
						'invoice_ids' => $invoice_id[$key],
						'net_weight' =>$d['net_weight'],
						'cust_net_weight' =>$d['cust_net_weight'],
						'added_invoice_id'=>$inv_id[0],
						//'added_invoice_id'=>'159',
						'transportation' =>$transportation);
	}
	echo json_encode($arr);
}
//custom order [sonu] 28-2-2017
if($fun == 'updateAccDeclinestatus')
{
	//printr($_POST['postArray']);die;
	
	$value = '';
	$arr=array('user_id'=>$obj_session->data['ADMIN_LOGIN_SWISS'],
					'user_type_id'=>$obj_session->data['LOGIN_USER_TYPE'],
					'currdate'=>$_POST['postArray']['currdate']
				);
	$value .= "process_by='".json_encode($arr)."',";
	if(isset($_POST['postArray']['review']))
	{
		$value .= "review='".$_POST['postArray']['review']."'";
	}
	if(isset($_POST['postArray']['due_date']))
	{
		$value .= "expected_ddate='".$_POST['postArray']['due_date']."'";
	}
	if(!empty($_POST['postArray']['status']) && $_POST['postArray']['status']!='')
	{
		$cond = "multi_custom_order_id = '" .(int)$_POST['postArray']['multi_custom_order_id']. "' AND custom_order_id = '" .(int)$_POST['postArray']['custom_order_id']. "'";
			
		$result = $obj_template->updateAccDeclinestatus($value,$cond,$_POST['postArray']['status']);
	}
	if($_POST['postArray']['status']!=1)
	{
		$post=array($_POST['postArray']['custom_order_id'].'=='.$_POST['postArray']['multi_custom_order_id']);
		$rel = $obj_template->sendOrderEmail($post,$_POST['postArray']['status'],$_POST['adminEmail']);
		//die;
	}
	
}

if($fun == 'volume_pridict')
{
	$volume = $_POST['keyword'];
	$result = $obj_template->getVolume($volume);
	echo json_encode($result);
}

//if($fun == 'displayvolume')
//{
//	$product_id = $_POST['product_id'];
//	//$user_id = $_POST['userid'];
//	//printr($product_id );die;
//	$result = $obj_template->getVolume($product_id);
//	//printr($result);die;
//	$html = '';
//	if(!empty($result))
//		{
//		$html .=' <div class="form-group" id = "volume_detail">
//                        <label class="col-lg-3 control-label"><span class="required">*</span>Volume</label>
//                        	<div class="col-lg-3">'; 
//						            
//                     $html .=' <select name="volume_details" id="volume_details" class="form-control" validate[required]">
//                                 <option value="">Select Volume</option>';
//									foreach($result as $r)
//									{    
//									  $html .='<option value="'. $r['volume'].'" >
//											 '.$r['volume'] .'</option>';
//									}
//                            $html .='</select>
//                            </div>
//                             </div> ';
//		}
//	//printr($html);
//	echo $html;
//}



?>