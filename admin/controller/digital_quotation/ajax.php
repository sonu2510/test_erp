<?php //ruchi 30/4/2015 changes for price uk
include("mode_setting.php");

$fun = $_GET['fun'];
$json=1;
if($fun == 'client_name'){
	$client_name = $_POST['client_name'];
	$result = $obj_digital->getClientName($client_name);
	echo json_encode($result);
}
if($fun == 'getProductSize') {
	$product_id = $_POST['product_id'];
	$make_id = $_POST['make_id'];
	$size_type_mailer='';
	if($product_id=='10')
	{
		$size_type_mailer = $_POST['size_type'];
	}
	$data = $obj_digital->getProductSize($product_id,$make_id);
	$response = '';	
		$response .='<select id="size" name="size" class="form-control validate[required]" onchange="getColor()">
		                <option value="">Select Size</option>';
                        	if($data){	
                        		foreach($data as $item){
                        		    $response .= '<option value="'.$item['size_master_id'].'" weight="'.$item['weight'].'" zipper= "'.$item['product_zipper_id'].'" volume = "'.$item['volume'].'">';
                        			if($item['volume']!=0)
                        			$response .=  $item['volume'];
                        			if($size_type_mailer=='mm')
                        			{
                        				$width=round($item['width']*25.4);
                        				$height=round($item['height']*25.4);
                        				$gusset=round($item['gusset']*25.4);
                        				$mea = 'mm';
                        				$widht='(W)';
                        				$h='(H)';
                        				$flp='(Flap)';
                        			}
                        			else
                        			{
                        				$width=$item['width'];
                        				$height=$item['height'];
                        				$gusset=$item['gusset'];
                        				$mea=$widht=$h=$flp='';
                        				if($product_id=='10')
                        				{
                        					$mea = 'inch';
                        					$widht='(W)';
                        					$h='(H)';
                        					$flp='(Flap)';
                        				}
                        			}
                        			$response .= '['.$width.' '.$mea.' '.$widht.' X '.$height.' '.$mea.' '.$h.' X '.$gusset.' '.$mea.' '.$flp.'] ['.$item['zipper_name'].']</option>';					
                        		}
                        	}
	$response .= '</select>';
	echo $response;
}
if($fun == 'getColor')
{
   $data = $obj_digital->getColorName($_POST['product_id'],$_POST['zipper_id'],$_POST['volume']); 
   $response='';
   $response .='<div class="form-group option"> 
                    <label class="col-lg-3 control-label">Select Color</label>
                        <div class="col-lg-4">
                        <select id="color" name="color" class="form-control validate[required]">
                            <option value="">Select Color</option>';
                               if($data){
                                   foreach($data as $item){
                                        $colorval=json_decode($item['color']);
                                        foreach($colorval as $va)
										{
										    $color_detail=$obj_digital->getColor($va,$_POST['make_id']);
										    if(!empty($color_detail))
                                                $response .= '<option value="'.$color_detail[0]['pouch_color_id'].'">'.$color_detail[0]['color'].'</option>';
										}
                                   }    
                               }
           $response .= '</select>
                </div>
            </div>';
   echo $response;
}
if($fun == 'addQuotation')
{
    parse_str($_POST['formData'], $post);
    $product_code_data = $obj_digital->getProductCode($post['product'],$post['zipper'],$post['make'],$post['spout'],$post['accessorie'],$_POST['volume'],$post['color'],$post['valve']); 
    //print_r($product_code_data);die;
    $product_code_id = $product_code_data['product_code_id'];
	$country_id = $post['con_id'];
	$transport = $post['transpotation'];
	$stock_print = $post['stock_print'];
	$digital_print_color = $post['digital_print_color'];
	//if($stock_print=="Digital Print"){
	if($stock_print!=" "){
	    $detailslistdigital = $obj_digital->getaddProductDetailsForDigitalPrint($product_code_id,$country_id,$digital_print_color,0);
		$color_plate_price = $obj_digital->getColorPlatePrice($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$stock_print);
	}

	$detailslist = $obj_digital->getaddProductDetails($product_code_id,$country_id,$transport,$post['color']);
	//printr($detailslist);die;
    /*if($obj_session->data['LOGIN_USER_TYPE']=='4' && $obj_session->data['ADMIN_LOGIN_SWISS']==44)
    {
        printr($product_code_id);
    	printr($detailslist);
    	printr($detailslistdigital);
    	printr($color_plate_price);//die;
    }*/
 
	$response = '';
	if(isset($post['quantity']))
	{
	    //if(!empty($detailslistdigital))
	        $last_id = $obj_digital->addQuotation($post,'Q',$detailslistdigital,$color_plate_price,$detailslist,$product_code_id);
	    /*else
	        $last_id = $post['digital_quotation_id'];*/
	        
	    if($last_id == "Error"){
			$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
		}else{
			$obj_session->data['success'] = 'Your quotation generated!';
		}	
		$getData='*';
        $data = $obj_digital->getQuotation($last_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
        
       foreach($data as $dat)
	   {
	       $quantityData[] = $obj_digital->getQuotationQuantity($dat['digital_product_quotation_id']);
	   }
        if($quantityData)
        {
            $response .= '<div class="table-responsive"><input type="hidden" id="digital_quotation_id" name="digital_quotation_id" value="'.$last_id.'"/>';
        	    $response .= '<table class="table table-bordered">';
            	$response .= '<thead>';
            	    $response .= '<tr>';
            	        $response .= ' <th>Transportation By</th>';
                    	$response .= ' <th>Quntity</th>';
                    	$response .= '<th>Option</th>';
                    	$response .= '<th>Dimension (Make Pouch)</th>';
                    	$response .= '<th>Pouch Price / Per Plate Charge </th>';
                    	$response .= ' <th>Color</th>';
                    $response .= '</tr>'; 
            	$response .= '</thead>'; 
        	    $response .= '<tbody>';
            	foreach($quantityData as $detail)
            	{
            	    foreach($detail as $details)
            	    {
                	    $tot_clr=explode('==',$details['total_color']);
                	    $spout='';
                		$accessorie = '';
                		$spout_detail = $obj_digital->getSpoutName($details['spout_id']);
                		$acce_detail = $obj_digital->getAccessorieName($details['accessorie_id']);
                		$acce_sec_detail = $obj_digital->getAccessorieName($details['accessorie_sec_id']);
                		$zip_detail = $obj_digital->getZipperName($details['zipper_id']);
                		$size_detail = $obj_digital->ProductSize($details['size_id']);
                		$color_detail = $obj_digital->getColor($details['color_id']);//printr($color_detail);
                		if($spout_detail['spout_name'] != 'No Spout')
                		{
                			$spout = 'with '.$spout_detail['spout_name'];
                		}
                		if($acce_detail['product_accessorie_name'] != 'No Accessorie')
                		{
                			$accessorie = 'with '.$acce_detail['product_accessorie_name'];
                		}
                		$dataresult =  '<b>'.$details['product_name'].'</b><br> '.$zip_detail['zipper_name'].' '.$details['valve'].'<br> '.$spout.' '.$accessorie.' '.$acce_sec_detail['product_accessorie_name']; 
                		$response .= '<tr>';
                		               $response .= ' <td>'.$details['transport_type'].'</td>';
                    	               $response .= ' <td>'.$details['quantity'].'</td>';
                    	               $response .= ' <td>'.$dataresult.'</td>';
                    	               $response .= ' <td><b>'.$size_detail['volume'].'</b> <br>['.$size_detail['width'].'X'.$size_detail['height'].'X'.$size_detail['gusset'].']</td>';
                    	               $response .= ' <td>'.$details['price'].' '.$details['currency_code'].' [ '.$details['stock_print'].' ]<br>Per Plate Charge :'.$details['color_plate_price'].' '.$details['currency_code'].'<br>Total Color To Print : '.$tot_clr[1].'</td>';
                    	               $response .= ' <td>'.$color_detail[0]['color'].'</td>';
                	   $response .= '</tr>';
            	    }
            	}
            	$response .= '</tbody>';
            $response .= '</table>
                    </div>';
        	
        }
	}
	/*else
	{
	    $response .= '<div>Sorry!! There is no prices assigned.</div>';
	}*/
    echo $response;
}
if($fun == 'getProduct'){
	$make_id = $_POST['make'];
	$products = $obj_digital->getProduct($make_id);
	$response = '';
    $response .= '<label class="col-lg-3 control-label"><span class="required">*</span>Select Product</label>
                    <div class="col-lg-4">
                        <select name="product" id="product" class="form-control validate[required]" onchange="product_info()">
                            <option value="">Select Product</option>';
                                foreach($products as $product){
                                        if(isset($post['product']) && $post['product'] == $product['product_id']){
                                            $response .='<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                        }else{
                                            $response .= '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                        }
                                } 
            $response .='</select>
                    </div>';
	
	echo $response;
}
else if($fun=='getcurrency')
{
	$sec_curr=$obj_quotation->getcurrencyformail($_POST['user_id'],$_POST['user_type_id']);
	//printr($sec_curr);
	$arr['response']=$sec_curr['secondary_currency'];
	if(isset($sec_curr['currency_code']))
		$arr['result']=$sec_curr['currency_code'];
	if(isset($sec_curr['price']))
		$arr['price']=$sec_curr['price'];
	echo json_encode($arr);


}
?>