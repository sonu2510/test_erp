<?php
include("mode_setting.php");

include("model/product_quotation.php");
$obj_quotation = new productQuotation;

$fun = $_GET['fun'];
$json=1;
if($_GET['fun']=='updateQuotationStatus') {
	
	$quotation_id = $_POST['quotation_id'];
	$status_value = $_POST['status_value'];
	
	
	if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) {
		$obj_quotation->$fun($quotation_id,$status_value);		
    }else{
		if($status_value == 0){
			$obj_quotation->$fun($quotation_id,$status_value);	
		}else{
			$json = 0;	
		}
	}
	
	echo json_encode($json);
	
}else if($_GET['fun']=='deleteQuotation') {
	
	$quotation_id = $_POST['quotation_id'];
	$obj_quotation->$fun($quotation_id);
	
	echo json_encode($json);
}else if($fun == 'getQuantity') {
	
	$type = $_POST['type'];
	$quantity_type = $_POST['quantity_in'];
	$product_id = $_POST['product_id'];
	$data = $obj_order->$fun($type,$quantity_type);
	$gusset_available = $obj_order->checkProductGusset($product_id);
	
	$response['gusset_available'] = $gusset_available;
	
	$response['display'] = '';
	if($data){
		$count = 1;
		//$json .= '<div class="form-control" style="min-height:10%;">';
		$response['display'] .= '<select class="form-control" name="quantity">';
		foreach($data as $item){
		   $response['display'] .= '<option value="'.encode($item['quantity']).'">'.$item['quantity'].'</option>';
		}
		//$json .= '</div>';
	}
	echo json_encode($response);
	
}else if($fun == 'checkGusset'){
	
	$gusset_available = $obj_order->checkProductGusset($_POST['product_id']);
	
	echo $gusset_available;
	
}elseif($fun == 'getMaterialQuantity'){
	//printr($_POST['material']);
	
	
	
	if(isset($_POST['material']) && !empty($_POST['material'])){
		$check = 0;
		$materialQuantity = array();
		foreach($_POST['material'] as $material_id){
			if($material_id){
				$getQuantity = $obj_quotation->getMaterialQuantity($material_id);
				//printr($getQuantity);die;
				if($getQuantity){
					$materialQuantity[$material_id] = $getQuantity;
				}else{
					$check++;
					break;
				}
			}else{
				$check++;
				break;
			}
		}
		if($check == 0){
			//printr($materialQuantity);die;
			$final = $obj_quotation->aasort($materialQuantity);
			
			asort($final);
			
			if($final && !empty($final)){
				$html = '';
				
				$html .= '<select class="form-control" name="quantity">';
				foreach($final as $val){
					$html .= '<option value="'.encode($val).'">'.$val.'</option>';
				}
				$html .= '</select>';
				echo json_encode($html);
			}else{
				echo json_encode(false);
			}
			//echo json_encode("setset");
		}else{
			echo json_encode(false);
		}
	}else{
		echo false;
	}
}else if($fun == 'addProduct'){
	parse_str($_POST['formData'], $post);	
	$html = '';
	if(isset($post['material']) && !empty($post['material']) && isset($post['thickness']) && !empty($post['thickness']) && !empty($post['width']) && !empty($post['height']) && isset($post['quantity']) && !empty($post['quantity'])){
		
		if(isset($_SESSION['product_images'])){
			$product_images = $_SESSION['product_images'];
			unset($_SESSION['product_images']);
		}else{
			$product_images = '';
		}
		
		if(isset($_SESSION['product_die_line'])){
			$product_die_line = $_SESSION['product_die_line'];
			unset($_SESSION['product_die_line']);
		}else{
			$product_die_line = '';
		}
		
		$retCalculation = $obj_order->addQuotationNew($post);
		//printr($retCalculation);
		
		if(!empty($product_images)){
			$obj_order->insertImages($product_images,$retCalculation['order_product_id']);
		}
		
		if(!empty($product_die_line)){
			$obj_order->insertDieLine($product_die_line,$retCalculation['order_product_id']);
		}
		
		$_SESSION['product_array'][$retCalculation['order_product_id']]=array(
			'image' => $product_images,
			'data' => $post,
			'total_price' => $retCalculation['total_price'],
			'product_id'=>$retCalculation['product_id'],
		);
		$order_products = $obj_order->getOrderProducts($retCalculation['order_id']);
		$currency_data = $obj_order->getOrderCurrency($retCalculation['order_id']);
//printr($currency_data);
		$html .= '<h4><i class="fa fa-plus-circle"></i> Added Product</h4>';
         $html .= '<div class="line m-t-large" style="margin-top:-4px;"></div><br/>';
		
		$html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			  $html .= '<th>Art Work</th>';
			  $html .= '<th>Transportation</th>';
			  $html .= '<th>Product Name</th>';			  
		      $html .= '<th>Quantity</th>';
			  $html .= '<th>Option</th>';
			  $html .= '<th>Dimension (Make Pouch</th>';
			  $html .= '<th>Layer:Material:Thickness</th>';
			  $html .= '<th>Price/Pouch</th>';
			  $html .= '<th>Total</th>';
			  if($order_products[0]['total_price_with_tax'] != 0) {
			  $html .= '<th>Price / pouch With Tax</th>';
			  $html .= '<th>Total Price With Tax</th>';
			  }
			  $html .= '<th>Cylinder Price</th>';
			  $html .= '<th>Tool Price</th>';
			  $html .= '<th>&nbsp;</th>';	
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		    //foreach($_SESSION['product_array'] as $product_key=>$order_product){
				
				
				//printr($order_products);
				foreach($order_products as $order_product) {
					
					$meterialData = $obj_order->getOrderMaterial($order_product['order_product_id']);
					$orderImage = $obj_order->getOrderProductImages($order_product['order_product_id']);
					//printr($orderImage);
//				printr($_SESSION['product_array']);
				//$option_details = $obj_order->getProductOptionDetails($order_product['data']['spout'],$order_product['product_id'],$order_product['data']['zipper'],$order_product['data']['accessorie']);
				//$order_details = $obj_order->getOrderDetail($product_key);
				$total_prices = ($order_product['total_price']+$order_product['gress_price']);
			   	$html .= '<input type="hidden" name="order_id" value="'.$order_product['order_id'].'" />';
				$html .= '<tr id="order_product_id_'.$order_product['order_product_id'].'">';
				//printr($product_images);
					if(!empty($orderImage)){
					$html .= '<td><!-- .crousel slide -->
						  <div class="carousel slide auto" id="c-slide-'.$order_product['order_product_id'].'">
							<ol class="carousel-indicators out">';
							 for($j=0;$j<count($orderImage);$j++){ 
							 	$html .='<li data-target="#c-slide-'.$order_product['order_product_id'].'" data-slide-to="'.$j.'" class=""></li>';
							 }
						$html .='</ol>';
						$html .='<div class="carousel-inner">';
							 $i=0;
							  
							foreach($orderImage as $image){ 
								if($i==0){
									$html .=' <div class="item active">';
								}else{
									$html .=' <div class="item">';
								}
									$html .='<p class="text-center"><img alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/100_'.$image['image_name'].'"></p>';
								$html .='</div>';
								$i++;
							}
						$html .='</div>
							<a class="left carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
							<a class="right carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>
						<!-- / .carousel slide --></td>';
					} else {
						$html .= '<td>&nbsp;</td>';	
					}
					$html .='<td><b>'.ucwords(decode($order_product['transport_type'])).'</b></td>';
				  $html .= '<td><b>'.$order_product['product_name'].'</b>';
				  $html .= '</td>';
				  
				  $html .= '<td class="update-quantity">';
					
					
					$html .= '<div class="input-group">';
						$html .= '<span class="form-group">'.$order_product['quantity'].'</span>';
					$html .= '</div>';
				  $html .= '</td>';
				   $html .= '<td>'.ucwords($order_product['zipper_txt']).'<br>'.ucwords($order_product['valve_txt']).'<br>'.ucwords($order_product['spout_txt']).'<br>'.ucwords($order_product['accessorie_txt']).'</td>';
				   $html .= '<td>'.$order_product['width'].'X'.$order_product['height'].'X'.$order_product['gusset'].'</td>';
				   $html .='<td>';
				   foreach($meterialData as $materials) {
						  $html .= '<b>'.$materials['layer'].' Layer:</b> '.$materials['material_name'].':'.floatval($materials['material_thickness']).'<br>';
				   }
				   $html .= '</td>';
				   $html .= '<td> '.$currency_data['currency'].' '.sprintf ("%.3f",($total_prices/$order_product['quantity'])).'</td>';
				  $html .= '<td>'.$currency_data['currency'].' '.sprintf ("%.3f",$total_prices).'</td>';
				  $PouchPrice = sprintf ("%.3f",($order_product['total_price_with_tax']/$order_product['quantity']));
				  if($PouchPrice >0) {
				  $html .= '<td>'.$currency_data['currency'].' '.sprintf ("%.3f",$PouchPrice).'</td>';
				  }
				  
				  if($order_product['total_price_with_tax'] > 0) {
				  $html .= '<td>'.$currency_data['currency'].' '.sprintf ("%.3f",$order_product['total_price_with_tax']).'</td>';
				  } 
				  $html .= '<td>'.floatval($order_product['cylinder_price']).'</td>';
				  if($order_product['tool_price'] != 0) {
					  $html .= '<td>'.$order_product['tool_price'].'</td>';
				  } else {
					  $html .= '<td></td>';
				  }
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeProduct('.$order_product['order_product_id'].')"><i class="fa fa-trash-o"></i></a></td>';
				$html .= '</tr>';
				$html .= '<div class="modal fade" id="alertbox_'.$order_product['order_product_id'].'">
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
                                    <button type="button" name="popbtnok" id="popbtnok_'.$order_product['order_product_id'].'" class="btn btn-primary">Ok</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>';
			}
		 //$html .= '<tr><td colspan="14"><input type="button" onclick="btn_order_redirect()" class="btn btn-primary" value="Add" /></div></td></tr>';
		 $html .= '<tr><td colspan="14"><input type="submit" name="btn_order_redirect" class="btn btn-primary" value="Add" />
</div></td></tr>';
		 $html .= '</tbody>';
		 
	    $html .= '</table>';
		
		echo $html;
		//insert in order
	//$order_id = $obj_order->addOrder($post);
//	unset($_SESSION['product_array']);
		//$obj_order->addProduct();
		//printr($_POST);die;	
	}else{
		echo '<div class="btn btn-danger btn-xs" id="empty_quantity">Please fill form carefully!!!</div>';
	}
	
	
	
}else if($fun == 'removeImage'){
	
	$upload_path = DIR_UPLOAD.'admin/artwork/';
	
	if(isset($_SESSION['product_images'])){
		foreach($_SESSION['product_images'] as $image){
			if($image['image_id']==$_POST['image_id']){
				
				unset($_SESSION['product_images'][$image['image_id']]);
				unlink($upload_path.'50_'.$image['image_name']);
				unlink($upload_path.'100_'.$image['image_name']);
				unlink($upload_path.'200_'.$image['image_name']);
			}
		}
	}
	echo 1;
	
}else if($fun == 'removeFile'){
	
	$upload_path = DIR_UPLOAD.'admin/dieline/';
	
	if(isset($_SESSION['product_die_line'])){
		foreach($_SESSION['product_die_line'] as $die_line){
			if($die_line['die_id']==$_POST['die_id']){
				
				unset($_SESSION['product_die_line'][$die_line['die_id']]);
				if(file_exists($upload_path.$die_line['die_name'])){
					unlink($upload_path.$die_line['die_name']);
				}
			}
		}
		
		if(empty($_SESSION['product_die_line'])){
			unset($_SESSION['product_die_line']);	
		}
	}
	echo 1;
	
}else if($fun == 'removeProduct'){
	unset($_SESSION['product_array'][$_POST['order_product_id']]);
	$order_data = $obj_order->getOrderProductDetail($_POST['order_product_id']);
	$orderID = $order_data['order_id'];
	//$obj_order->removeOrderPro($orderID);
	$obj_order->removeOrderedProduct($_POST['order_product_id']);
//	printr($_SESSION['product_array']);
	$html = '';
	if(count($_SESSION['product_array'])>0){
	   $html .= '<table class="table table-bordered">';
		  $html .= '<thead>';
		    $html .= '<tr>';
			  $html .= '<th>Art Work</th>';	
			  $html .= '<th>Product Name</th>';	
		      $html .= '<th>Quantity</th>';
			  $html .= '<th>Price/Pouch</th>';
			  $html .= '<th>Total</th>';
			  $html .= '<th>&nbsp;</th>';	
			$html .= '</tr>'; 
		  $html .= '</thead>';
		  
		  $html .= '<tbody>';
		    foreach($_SESSION['product_array'] as $product_key=>$order_product){
				
				$option_details = $obj_order->getProductOptionDetails($order_product['data']['spout'],$order_product['product_id'],$order_product['data']['zipper'],$order_product['data']['accessorie']);
//				$order_product['data']['spout'],$order_product['product_id'],$order_product['data']['zipper'],$order_product['data']['accessorie']);
				
				//printr($order_product);die;
				$html .= '<tr>';
					if(!empty($order_product['image'])){
					$html .= '<td><!-- .crousel slide -->
						  <div class="carousel slide auto" id="c-slide-'.$product_key.'">
							<ol class="carousel-indicators out">';
							 for($j=0;$j<count($order_product['image']);$j++){ 
							 	$html .='<li data-target="#c-slide-'.$product_key.'" data-slide-to="'.$j.'" class=""></li>';
							 }
						$html .='</ol>';
						$html .='<div class="carousel-inner">';
							 $i=0;
							  
							foreach($order_product['image'] as $image){ 
								if($i==0){
									$html .=' <div class="item active">';
								}else{
									$html .=' <div class="item">';
								}
									$html .='<p class="text-center"><img class="img-thumbnail" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/100_'.$image['image_name'].'"></p>';
								$html .='</div>';
								$i++;
							}
							$html .='</div>
							<a class="left carousel-control" style="width:15px;" href="#c-slide-'.$product_key.'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
							<a class="right carousel-control" style="width:15px;" href="#c-slide-'.$product_key.'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>
						<!-- / .carousel slide --></td>';
					} else {
						$html .= '<td>&nbsp;</td>';	
					}
					
				    $html .= '<td><b>'.$option_details['name'].'</b><br/>';
					//   $html .= '<small class="text-muted">-Color: '.base64_decode($order_product['data']['color']).'</small>'."<br/>";
					 //  $html .= '<small class="text-muted">-Size: '.base64_decode($order_product['data']['volume']).'</small>'."<br/>";
					   $html .= '<small class="text-muted">-No Valve No Zip</small>'."<br/>";
					   $html .= '<small class="text-muted">-Spout : '.$option_details['spout'].' </small><br/>';
					//   $html .= '<small class="text-muted">-Style : '.base64_decode($order_product['data']['style']).'</small><br/>';
				    $html .= '</td>';
					
				 
				  $html .= '<td class="update-quantity" style="width:16%;">';
					/*style="width:30%;" $html .= '<div class="row">';
					  $html .= '<div class="col-lg-8">';
						$html .= '<input type="text" value="10000" id="edit-quantity-1" name="edit_quantity" class="form-control "/>';
					  $html .= '</div>';
					  $html .= '<div class="col-lg-3">';	
						$html .= '<a class="btn btn-info btn-sm" href="javascript:void(0);" onClick="updateQuantity(1)"><i class="fa fa-refresh"></i></a>';
					  $html .= '</div>';
					$html .= '</div>';*/
					$html .= '<div class="input-group">
							<input type="text" class="form-control" value='.base64_decode($order_product['data']['quantity']).'>
							<span class="input-group-btn">
								<a class="btn btn-info" href="javascript:void(0);" onClick="updateQuantity('.$product_key.')"><i class="fa fa-refresh"></i></a>
							</span>
						</div>';
				  $html .= '</td>';
				   
				  $html .= '<td>'.$order_product['total_price']/base64_decode($order_product['data']['quantity']).'</td>';
				  $html .= '<td>'.$order_product['total_price'].'</td>';
				  $html .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeProduct('.$product_key.')"><i class="fa fa-trash-o"></i></a></td>';
				$html .= '</tr>';
			}
		  $html .= '</tbody>';	 
	    $html .= '</table>';
	}else{
	  unset($_SESSION['product_array']);
	}
	echo $html;
	
}

if($fun == 'addHistory') {
	$order_id = $_POST['order_id'];
	$order_status_id = $_POST['order_status_id'];
	$note = $_POST['new_note'];
	$email_notif = $_POST['email_notif'];
	
	if($order_status_id != '')
	{
		$user_name = $obj_order->$fun($order_id,$order_status_id,$note,$email_notif);
	
		$order_status = $obj_order->getOrderStatusName($order_status_id);
	
	
		$response = '<tr>';
	 	$response .= '<td>'.$order_status.'</td>';
	 	$response .= '<td>'.$user_name.'</td>';
	 	$response .= '<td>'.date("d-M-y").'</td>';
	 	$response .= '<td>'.$note.'</td>';
		$response .= '</tr>';  
	
		$arr['response']=$response; 
	
		//$emailval = $_POST['emailval'];
		
		if($email_notif == 1)
		{
			$to = $_POST['emailval'];
		//printr($_POST);
			$subject = "your Order Status id :".$order_status_id."Your Order Status Name : ".$order_status;
			$message = "Description ".$note;
			$headers = "swisspac@gmail.com";
			mail($to, $subject, $message, $headers);
			
			$result = "Email has been sent successfully";
			$arr['result']=$result;
			
		}
		else
		{
			$result = "Email has not been sent";
			 $arr['result']=$result;
		}
		echo json_encode($arr);
	}
	   
}
if($fun == 'getQuotionData') {
	$quotation_no = $_POST['quotation_no'];
	$obj_session->data['quotation_no'] = $_POST['quotation_no'];
	if($quotation_no != '')
	{
		$result = $obj_order->getQuotionData($quotation_no);
		if(is_array($result))
		{
			$result['encode_layer'] =encode($result['layer']);
			$re = $obj_order->getQuotionZipperData($result['product_quotation_id']);
			$arr['result']=$result;
			$arr['re']=$re;
			$arr['price'] = $obj_order->getQuotionQtyData($result['product_quotation_id'],$re['checked']);			
			
		}
		else
		$arr=1;		
	}
	//echo json_encode($arr);
	//edited by rohit 20 feb
$show ='';
$qn = $obj_order->getQuotationNumberId($quotation_no);

if($qn) {
			
		$multi_pro_quot_id = $qn['multi_product_quotation_id'];
		$PQI = $obj_order->getProductQuotId($multi_pro_quot_id);
		//printr($PQI);
		$pro_quot_id = $PQI['product_quotation_id'];
		$result2 = $obj_order->getQuotationQuantity2($pro_quot_id);
		$obj_session->data['shipment_country'] = $PQI['shipment_country_id'];
		$quantityData[] = $result2;
		if(!empty($quantityData))
		{
			foreach($quantityData as $k=>$qty_data)
			{
				foreach($qty_data as $tag=>$qty)
				{
					foreach($qty as $q=>$arr)
					{
						$new_data[$tag][$q][]=$arr[0];
					}
				}	
			}

			foreach($new_data as $k=>$qty_data)
			{
				
				//printr($result2);
				$show .= '<input type="hidden" id="quot_country" value="'.$PQI['shipment_country_id'].'" />';
				$show .='<input type="hidden" id="pro_quote_id" name="pro_quote_id" value="'.$pro_quot_id.'"/>';
				$show .='<div class="form-group">
						<label class="col-lg-2 control-label">Price (By '.$k.')</label> 
								<div class="col-lg-10">
									<section class="panel">
									  <div class="table-responsive">
										<table class="table-striped b-t text-small">
										  <thead>
											  <tr>
                                                <th>Quntity</th>';
				if($PQI['quotation_type'] != 1){
					$show .= '<th>Option(Printing Effect )</th>';
                }
                $show .='<th>Dimension (Make Pouch)</th>
                         <th>Layer:Material:Thickness</th>';
				if($PQI['quotation_status'] == 0){
					if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
					{ 				
						if($dat['currency']=='INR') {
						$show .='<th>Discount</th>';
						}
					}
				}
                $show .='<th>Price / pouch</th>
                         <th>Total</th>';
				if($k=='pickup') {
					if($PQI['shipment_country_id']==111){
				$show .='<th> Price / pouch  With Tax </th>
                         <th>Total Price With Tax </th>';
					}
				}
                $show .='<th>Cylinder Price</th>';
				if($arr[0]['tool_price'] != 0) {
					$show .='<th>Tool Price</th>';
				}
                $show .='<th>Art Work</th>
						 <th>Die Line</th>';
				
					$show .='<th></th>';
				
                $show .='</tr></thead><tbody>';
				$i=1;
                foreach($qty_data as $skey=>$sdata){

					foreach($sdata as $soption){

                    $show .='<tr id="quotation-row-'.$soption['product_quotation_price_id'].'">';
					$show .='<input type="hidden" id="pro_quote_price_id" name="pro_quote_price_id" value="'.$soption['product_quotation_price_id'].'"/>';			
					$show .='<th>'.$skey.'</th>
                            <td>'.ucwords($soption['text']).' ('.$soption['printing_effect'].')';
					$show .='</td><td>'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.
							$soption['gusset']; if($soption['volume']!='')  ' ('.$soption['volume'].')';

					$show .='</td>
                             <td>';
                             for($gi=0;$gi<count($soption['materialData']);$gi++){
							$show .='<b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
							}
					$show .= '</td>';
					if($PQI['quotation_status'] == 0){
						if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000) {
							if($PQI['currency']=='INR') {
							$show .='<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
									<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['product_quotation_quantity_id'].'" class="form-control" style="width: 100px;" ></td>'; 
							} } }
					$show .='<td>';
					if($soption['discount'] && $soption['discount'] >0.000) {
					$show .='<b>Total : </b>'.$pretot= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3").$pretot;
					$show .='<br />
                             <b>Discount ('.$soption['discount'].' %) : </b>';
					$predis = $pretot*$soption['discount']/100; 
					$obj_quotation->numberFormate($predis,"3");
					$show .='<br />
                              <b>Final Total : </b>'
					.$PQI['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3");
					} else {
					$show .= $PQI['currency'].' '.
							$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $PQI['currency_price']),"3");
                    $show .='</td>
                              <td>'; }
					if($soption['discount'] && $soption['discount'] >0.000) {
						$show .='<b>Total : </b>'. $tot= $obj_quotation->numberFormate(($soption['totalPrice'] / $PQI['currency_price'] ),"3").$tot.'<br />';
						$show .='<b>Discount ('.$soption['discount'].' %) : </b>';
						$dis = $tot*$soption['discount']/100; 
						$show .= $obj_quotation->numberFormate($dis,"3").'<br />';
						$show .='<b>Final Total : </b>'.$PQI['currency'].' '.($tot-$dis);
					} else {
					$show .=$PQI['currency'].' '.$obj_quotation->numberFormate(($soption['totalPrice'] / $PQI['currency_price'] ),"3");
					}
					$show .='</td>';
					if($k=='pickup') {
						if($PQI['shipment_country_id']==111) {
					$show .='<td>';
					$show .= $PQI['currency'].' '.$obj_quotation->numberFormate((($soption['totalPriceWithTax'] / $skey) / $PQI['currency_price'] ),"3");
					$show .='</td>
                             <td>';
					$show .=$PQI['currency'].' '.$obj_quotation->numberFormate(($soption['totalPriceWithTax'] / $PQI['currency_price'] ),"3");
					$show .='</td>';
					}  }
					$show .='<td>';
					if($soption['cylinder_price']>0) {
						$show .=(int)$soption['cylinder_price'];
						} else {
						$show .= ''; }
					$show .='</td>';
					if($soption['tool_price']>0) {
						$show .= '<td>'.(int)$soption['tool_price'].'</td>';
						} 

					$show .='<td><div class="media-body2 file-input-wrapper btn btn btn-sm btn-info m-b-small">
                                      <i class="fa fa-folder-open-o"></i>Browse
											<input type="file" onchange="add_images()" name="art_image2" id="art-image2" title="<i class='.'fa fa-folder-open-o'.'></i> Browse" class="btn btn-sm btn-info m-b-small" >
                                    </div>
								</td>';
					$show .='<td><div class="media-body2 file-input-wrapper btn btn btn-sm btn-info m-b-small">
                                      <i class="fa fa-folder-open-o"></i>Browse
											<input type="file" onchange="add_dieline_images()" name="dieline_image2" id="dieline_image2" title="<i class='.'fa fa-folder-open-o'.'></i> Browse" class="btn btn-sm btn-info m-b-small" >
                                    </div>
								</td>';
					$show .='<td class="form-group">
                        	 	<div class="col-lg-9 col-lg-offset-3">
                      	  			<button type="button" name="btn_add" onclick="quot_form('.$soption['product_quotation_price_id'].');" id="btn_add" class="btn btn-primary" style="display:inline">Add Item</button>
                        		</div>
                      		</td>';
					
                    $show .='</tr>';
					}
					$show .='</tr>';
					$i++;
                    }
                    $show .= '</tbody></table></div></section></div></div>';
			}
		
		}
		echo $show;
}
		else {
			echo '<div class="empty-item" id="empty">Please Insert correct quotation number</div>';
		}
}
if($fun == 'addQuotation') {
	$results = '';
	parse_str($_POST['postData'], $postData);

	$quotation_no = $_POST['quotation_no'];
	$qn = $obj_order->getQuotationNumberId($quotation_no);
	$priceId = $_POST['pro_quote_price_id'];
	if(isset($priceId) && !empty($priceId)) {
		$item_detail = $obj_order->getAddProDetail($priceId);
		$quotationId = $item_detail['product_quotation_id'];
		$PD = $obj_order->getMulProDetail($quotationId);
	
		$trans = $item_detail['transport_type'];
		$ziper_data = $item_detail['zipper_txt'];
		$valve_txt = $item_detail['valve_txt'];
			if($valve_txt == 'no Valve') {
				$valve_data = 0;
			}
			else {
				$valve_data = 1;
			}
	//spout id by name
	$spout_data = $obj_order->getSpoutDetail($item_detail['spout_txt']);
	$spout_id = $spout_data['product_spout_id'];
	//spout id by name
	$accessorie_data = $obj_order->getAccessorieDetail($item_detail['accessorie_txt']);
	$accessorie_id = $accessorie_data['product_accessorie_id'];
	$make_data = $item_detail['make_pouch'];

	$result = $obj_order->getQuotationQuantity2($quotationId);
	$quantityData[] = $result;
	
		if(!empty($quantityData))
		{
			foreach($quantityData as $k=>$qty_data)
			{
				foreach($qty_data as $tag=>$qty)
				{
					foreach($qty as $q=>$arr)
					{
						$new_data[$tag][$q][]=$arr[0];
					}
				}	
			}
			$results .= '<table class="table table-bordered table-color">';
			$results .= '<thead>';
		    $results .= '<tr>';
			  $results .= '<th>Transportation</th>';
			  $results .= '<th>Quntity</th>';
			  $results .= '<th>Option(Printing Effect )</th>';			  
		      $results .= '<th>Dimension (Make Pouch)</th>';
			  $results .= '<th>Layer:Material:Thickness</th>';
			  $results .= '<th>Price / pouch</th>';
			  $results .= '<th>Total</th>';
			  if($arr[0]['totalPriceWithTax'] != 0) {
			  $results .= '<th>Price / pouch With Tax</th>';
			  $results .= '<th>Total Price With Tax</th>';
			  }
			  $results .= '<th>Cylinder Price</th>';
			  if($arr[0]['tool_price'] != 0) {
			  $results .= '<th>Tool Price</th>';
			  }
			 
			  $results .= '<th>Action</th>';			  
			$results .= '</tr>'; 
		  $results .= '</thead>';
		  
		  $results .= '<tbody>';
			foreach($new_data as $k=>$qty_data)
			{
				$results .='<input type="hidden" id="pro_quote_id" name="pro_quote_id" value="'.$priceId.'"/>';
				
				$i=1;
				foreach($qty_data as $skey=>$sdata){
					foreach($sdata as $soption){
						for($gi=0;$gi<count($soption['materialData']);$gi++){
							$material_data[] = $soption['materialData'][$gi]['material_id'];
							$material_thickness[] = $soption['materialData'][$gi]['material_thickness'];
							$layer_data = encode($gi+1);
						}
						$quantt = encode($skey);						

					if($soption['product_quotation_price_id'] == $priceId) {
					//insert data order_product
					$data_value = array (						
						'taxation' => $soption['tax_type'],
						'order_currency' => $PD['currency'],
						'shipping_country_id' => $PD['shipment_country_id'] ,    
						'billing_country_id' => $PD['shipment_country_id'] ,
						'product' => $PD['product_id'] ,
						'printing_effect' => $PD['printing_effect_id'] ,
						'layer' => $layer_data ,
						'make' => $make_data ,
						'material' => $material_data ,
						'thickness' => $material_thickness ,
						'width' => $soption['width'],
						'height' => $soption['height'],
						'gusset' => $soption['gusset'],
						'quantity' => $quantt ,	
						'valve' => $valve_data ,
						'zipper' => $ziper_data ,
						'spout' => $spout_id ,
						'accessorie' => $accessorie_id ,
						'transpotation' => $trans ,
						'country_id' => $PD['added_by_country_id'],
						'first_name' => $postData['first_name'],
						'last_name' => $postData['last_name'],
						'company' => $postData['company'],
						'shipping_address_1' => $postData['shipping_address_1'],
						'shipping_address_2' => $postData['shipping_address_2'],
						'shipping_city' => $postData['shipping_city'],
						'billing_address_1' => $postData['billing_address_1'],
						'billing_address_2' => $postData['billing_address_2'],
						'billing_city' => $postData['billing_city'],
						'order_type' => $postData['order_type'],
						'website' => $postData['website'],
						'email' => $postData['email'],
						'contact_number' => $postData['contact_number'],
						'vat_number' => $postData['vat_number'],
						'order_note' => $postData['order_note'],
						'order_instruction' => $postData['order_instruction'],
						'order_id' => isset($postData['order_id'])?$postData['order_id']:''				
						 );
						//printr($postData);
						$test = $obj_order->addQuotationNew($data_value);
						
						$orderId = $test['order_product_id'];
						$order_products = $obj_order->getOrderProducts($test['order_id']);
						$currency_data = $obj_order->getOrderCurrency($test['order_id']);
						//insert in order
						if(isset($_SESSION['product_images'])){
							$product_images = $_SESSION['product_images'];
							unset($_SESSION['product_images']);
						}else{
							$product_images = '';
						}
						if(!empty($product_images)){
							$obj_order->insertImages($product_images,$orderId);
						}
						//printr($_SESSION['product_die_line']);die;
						if(isset($_SESSION['product_die_line'])){
							$product_die_line = $_SESSION['product_die_line'];
							unset($_SESSION['product_die_line']);
						}else{
							$product_die_line = '';
						}
						if(!empty($product_die_line)){
							$obj_order->insertDieLine($product_die_line,$orderId);
						}
						/*$_SESSION['product_array'][$test['order_product_id']]=array(
							'image' => $product_images,
							'data' => $data_value,
							'total_price' => $test['total_price'],
							'product_id'=>$test['product_id']
						);
						*/
                    //foreach($_SESSION['product_array'] as $product_key=>$order_product){
				foreach($order_products as $order_product) {
					$meterialData = $obj_order->getOrderMaterial($order_product['order_product_id']);
					//$option_details = $obj_order->getProductOptionDetails2($order_product['data']['spout'],$order_product['product_id'],$order_product['data']['accessorie']);
					//$order_details = $obj_order->getOrderDetail($product_key);
					$total_prices = ($order_product['total_price']+$order_product['gress_price']);
					$results .= '<input type="hidden" name="order_id" value="'.$order_product['order_id'].'" />';
					$results .= '<tr id="order_product_id_'.$order_product['order_product_id'].'">';
					
					$results .='<td><b>'.ucwords(decode($order_product['transport_type'])).'</b></td>';
					// $results .= '<td><b>'.$option_details['name'].'</b>';
					$results .= '</td>';
					
					$results .= '<td class="update-quantity">';
					
					
					$results .= '<div class="input-group">';
					$results .= '<span class="form-group">'.$order_product['quantity'].'</span>';
					$results .= '</div>';
					$results .= '</td>';
					$results .= '<td>'.ucwords($order_product['zipper_txt']).'<br>'.ucwords($order_product['valve_txt']).'<br>'.ucwords($order_product['spout_txt']).'<br>'.ucwords($order_product['accessorie_txt']).'</td>';
					$results .= '<td>'.$order_product['width'].'X'.$order_product['height'].'X'.$order_product['gusset'].'</td>';
					$results .='<td>';
						foreach($meterialData as $materials) {
							$results .= '<b>'.$materials['layer'].' Layer:</b> '.$materials['material_name'].':'.floatval($materials['material_thickness']).'<br>';
						}				   $results .= '</td>';
					$results .= '<td>'.$currency_data['currency'].' '.sprintf ("%.3f",($total_prices/$order_product['quantity'])).'</td>';
					$results .= '<td>'.$currency_data['currency'].' '.sprintf ("%.3f",$total_prices).'</td>';
					$PouchPrice = sprintf ("%.3f",($order_product['total_price_with_tax']/$order_product['quantity']));
					if($PouchPrice >0) {
						$results .= '<td>'.$currency_data['currency'].' '.sprintf ("%.3f",$PouchPrice).'</td>';
					}
				  
					if($order_product['total_price_with_tax'] > 0) {
						$results .= '<td>'.$currency_data['currency'].' '.sprintf ("%.3f",$order_product['total_price_with_tax']).'</td>';
					} 
					$results .= '<td>'.floatval($order_product['cylinder_price']).'</td>';
					if($order_product['tool_price'] != 0) {
						$results .= '<td>'.$order_product['tool_price'].'</td>';
					} 
					$results .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeProduct('.$order_product['order_product_id'].')"><i class="fa fa-trash-o"></i></a></td>';
					$results .= '</tr>';
					$results .= '<div class="modal fade" id="alertbox_'.$order_product['order_product_id'].'">
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
												<button type="button" name="popbtnok" id="popbtnok_'.$order_product['order_product_id'].'" class="btn btn-primary">Ok</button>
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div>';
				}// end of order products
				$i++;
				$results .='</tbody></table>';
					
				}
				
				}
                  
				}}
		
			}
		
		}
	echo $results;
}
if($fun == 'addProductOrder') {
	parse_str($_POST['postData'], $postData);	
		if(isset($_SESSION['product_array']) && count($_SESSION['product_array'])>0){

			$order_id = $obj_order->addOrder($postData);
			$obj_session->data['success'] = 'Product successfully Added!';
			page_redirect($obj_general->link($rout, '', '',1));
		}
		if(isset($order_id) && !empty($order_id)) {
			echo $order_id;
		}
		else {
			echo '<div class="empty-item" id="empty-item">Please Add Some Item First!!!</div>';
		}

}
if($fun == 'addProductOrder2') {
	parse_str($_POST['postData'], $postData);	
	

		if(isset($_SESSION['product_array']) && count($_SESSION['product_array'])>0){
			$order_id = $obj_order->addOrder($postData);
			$obj_session->data['success'] = 'Product successfully Added!';
			page_redirect($obj_general->link($rout, '', '',1));
		}
		if(isset($order_id) && !empty($order_id)) {
			echo $order_id;
		}
		else {
			echo '<div class="empty-item" id="empty-item">Please Add Some Item First!!!</div>';
		}
}

if($fun == 'getQuotionQTYData') {
	$product_quotation_id = $_POST['product_quotation_id'];
	$transport = $_POST['transpotation'];
	$arr = $obj_order->getQuotionQtyData($product_quotation_id,$transport);
	echo json_encode($arr);
}

if($fun == 'getQuotionMaterial') {
	$product_quotation_id = $_POST['product_quotation_id'];
	if($product_quotation_id != '')
	{
		$material = $obj_order->getQuotationMaterial($product_quotation_id);
	}
	echo json_encode($material);
}
if($fun == 'updateOrder') {
	$order_product_id = $_POST['order_product_id'];
	//insert in order
	printr($_SESSION['product_images']);
	if(isset($_SESSION['product_images'])){
		$product_images = $_SESSION['product_images'];
		unset($_SESSION['product_images']);
	}else{
		$product_images = '';
	}
	if(!empty($product_images)){
		$obj_order->UpdateImages($product_images,$order_product_id);
	}
	//printr($_SESSION['product_die_line']);die;
	if(isset($_SESSION['product_die_line'])){
		$product_die_line = $_SESSION['product_die_line'];
		unset($_SESSION['product_die_line']);
	}else{
		$product_die_line = '';
	}
	if(!empty($product_die_line)){
		$obj_order->UpdateDieLine($product_die_line,$order_product_id);
	}

}
if($fun == 'getViewToolprice')
{
	$product_id = $_POST['product_id'];
	$tool_price = $obj_order->getViewToolprice($_POST['product_id']);
	//printr($tool_price);
	//die;
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
	//$response .= ' <th>Price</th>';
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
		//$response .= '<td>'.$tool['price'].'</td>';
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
	$widthsuggestion = $obj_order->getGussetSuggestion($_POST['width'],$gusset,$_POST['product_id']);
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
	$gussetsuggestion = $obj_order->getGussetSuggestion($_POST['width'],$_POST['gusset'],$_POST['product_id']);
	$pricesuggestion = $obj_order->getToolPrice($_POST['width'],$_POST['gusset'],$_POST['product_id']);
	if($obj_session->data['LOGIN_USER_TYPE']==1){
		$userCurrency = $obj_order->getCurrencyInfo($obj_session->data['ADMIN_LOGIN_SWISS']);
		$userCurrency['currency_code'] = "INR";
		$userCurrency['tool_rate']='';
	}else{
		$userCurrency =  $obj_order->getUserWiseCurrency($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
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
			$arr .=$a.$gusset['gusset'].'mm  else tool price '.' '.$userCurrency['currency_code'].' '.$obj_quotation->numberFormate($tool_price,"2").$b.'
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
?>