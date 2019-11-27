<?php
include("mode_setting.php");

include("model/product_quotation.php");
$obj_quotation = new productQuotation;

$fun = $_GET['fun'];
$json=1;
if($_GET['fun']=='updateQuotationStatus') {
	
	$template_id = $_POST['product_template_id'];
	$status_value = $_POST['status_value'];
	
	
	$data = $obj_template->updateQuotationStatus($template_id,$status_value);
	
}else if($fun == 'checkGussets'){
	
	$gusset_available = $obj_template->checkProductGusset($_POST['product_id']);
	//$gusset_available = 'yes';
	echo $gusset_available;
	
}else if($fun == 'addHistory') {
	parse_str($_POST['str'], $searcharray);
	//printr($searcharray);
	$response = '';
	$colorval='';
	$detailslist='';
	$first = $searcharray['first'];
	$second = $searcharray['second'];
	$third = $searcharray['third'];
	$fourth = $searcharray['fourth'];
	$width = $searcharray['width'];
	$height = $searcharray['height'];
	$gusset = isset($searcharray['gusset']) ? $searcharray['gusset'] : '';
	$volume = $searcharray['volume'];
	$valve = $searcharray['valve'];
	$accessorie = $searcharray['accessorie'];
	$zipper = $searcharray['zipper'];
	$spout = $searcharray['spout'];
	$color = $searcharray['color'];
	$title = $searcharray['title'];
	$product = $searcharray['product'];
	$country = json_encode($searcharray['country_id']);
	
	$user = $searcharray['user'];
	$currency = $searcharray['currency'];
	$transport = $searcharray['transport'];
		if($searcharray['templateid']== 0)
		{
			$user_name = $obj_template->$fun($title,$product,$country,$user,$currency,$transport);
		}
		else
		{
			$user_name = $searcharray['templateid'];
		}
		$currency_code = $obj_template->getSelectedCurrency($currency);
	//	printr(json_encode($searcharray['color']));
		//echo $valve[0];
	    $multipleval = $obj_template->addProduct($user_name,$first,$second,$third,$fourth,$width,$height,$gusset,$volume,$valve[0],$accessorie[0],$spout[0],$zipper[0],json_encode($color),$product);		
		$response .= '<table class="table table-bordered">';
		$response .= '<thead>';
		$response .= '<tr>';
		$response .= ' <th>Type Of Pouch</th>';
	 	$response .= '<th>Size</th>';
		$response .= '<th >
                    Dimension<br />
					WxLxG
                    </th>';
            $qty2000 = $qty3000 = 0;
			if($product == '18')
			{
				$qty100 = ' Qty100+';
				$qty200 = ' Qty200+';
				$qty500 = ' Qty500+';;
				$qty1000 = ' Qty1000+';
			}
			else if($product == '61')
			{
				$qty100 = ' Qty10000+';
				$qty200 = ' Qty15000+';
				$qty500 = ' Qty20000+';;
				$qty1000 = ' Qty30000+';
				$qty2000 = ' Qty50000+';
				$qty3000 = ' Qty100000+';
			}
			else if($product == '47' || $product == '48')
			{
				$qty100 = ' Qty1000+';
				$qty200 = ' Qty2000+';
				$qty500 = ' Qty5000+';;
				$qty1000 = ' Qty10000+';
				$qty2000 = ' Qty50000+';
				$qty3000 = ' Qty100000+';
			}
			else
			{
				$qty100 = ' Qty1000+';
				$qty200 = ' Qty2000+';
				$qty500 = ' Qty5000+';
				$qty1000 = ' Qty10000+';
			}
		$response .= '<th >Price ('.$currency_code['currency_code'].')<br>'.$qty100.'</th>';
	 	$response .= ' <th>Price ('.$currency_code['currency_code'].')<br>'.$qty200.'</th>';
		$response .= ' <th>Price ('.$currency_code['currency_code'].')<br>'.$qty500.'</th>';
	 	$response .= '<th >Price ('.$currency_code['currency_code'].')<br>'.$qty1000.'</th>';
		$response .= ' <th>
                   Color
                    </th>';
		$response .= '<th>Action</th>';
	 	$response .= '</tr>'; 
		$response .= '</thead>'; 
		$response .= '<tbody>'; 
	
		$detailslist = $obj_template->getaddProductDetails($user_name,$product);
		
		 foreach($detailslist as $details){
		    $qty2000 = $qty3000 = 0;
		 	if($product == '18')
			{
				$qty100 = $details['quantity100'];
				$qty200 = $details['quantity200'];
				$qty500 = $details['quantity500'];
				$qty1000 = $details['quantity1000'];
			}
			else if($product == '61')
    		{
    			$qty100 = $details['quantity10000'];
    			$qty200 = $details['quantity15000'];
    			$qty500 = $details['quantity20000'];
    			$qty1000 = $details['quantity30000'];
    			$qty2000 = $details['quantity50000'];
				$qty3000 = $details['quantity100000'];
    		}
    		else if($product == '47' || $product == '48')
			{
				$qty100 = $dataval['quantity1000'];
    			$qty200 = $dataval['quantity2000'];
    			$qty500 = $dataval['quantity5000'];
    			$qty1000 = $dataval['quantity10000'];
    			$qty2000 = $dataval['quantity50000'];
				$qty3000 = $dataval['quantity100000'];
			}
			else
			{
				$qty100 = $details['quantity1000'];
				$qty200 = $details['quantity2000'];
				$qty500 = $details['quantity5000'];
				$qty1000 = $details['quantity10000'];
			}
								
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
			$response .= '<td>'.$qty100.'</td>';
			$response .= '<td>'.$qty200.'</td>';
			$response .= '<td>'.$qty500.'</td>';
			$response .= '<td>'.$qty1000.'</td>';
			if($product == '61' || $product == '47' || $product == '48')
			{
			    $response .= '<td>'.$qty2000.'</td>';
			    $response .= '<td>'.$qty3000.'</td>';
			}
			$response .= '<td>';			
			$colorval=json_decode($details['color']);
		//	printr($details['color']);
			$color_detail='';
			$response.='<select  name="color_combo" id="color_combo" class="form-control">';
			//printr($color);
			foreach($colorval as $value)
			{
				//printr($value);
				$color_detail=$obj_template->getColor($value);
				$response.='<option value="'.$color_detail[0]['pouch_color_id'].'">'.$color_detail[0]['color'].'</option>';	
			}
			$response.='</select></td>';
			$response .= '<td class="del-product">';
		//	$response .= '<a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeTemplate('.$details['product_template_size_id'].')"><i class="fa fa-trash-o"></i></a>';
		$response .= '<a href="javascript:void(0);" 
                                onClick="getTemplate('.$details['product_template_size_id'].')" class="btn btn-info btn-xs">Edit</a>
								</td>';
			$response .= '</tr>'; 
		}	
		$response .= '</tbody>'; 
		$response .= '</table><input type="hidden" name="template_size_id" id="template_size_id" value="'.$details['product_template_size_id'].'" />';
		$arr['response'] = $response;		
		$arr['result'] = $user_name;
		echo json_encode($arr);
}else if($fun == 'removeTemplate'){
	$obj_template->removeTemplateProduct($_POST['template_size_id']);
}
else if($fun == 'getTemplate'){
	$response=$obj_template->getTempalteSize($_POST['template_size_id']);
	echo json_encode($response);
}
else if($fun == 'updateTemplate'){
	parse_str($_POST['str'], $searcharray);
//	printr($searcharray);die;
	
	
	$data=array();
	$data['quantity1000'] = $searcharray['first'];
	$data['quantity2000'] = $searcharray['second'];
	$data['quantity5000'] = $searcharray['third'];
	$data['quantity10000'] = $searcharray['fourth'];
	if($searcharray['product']=='61'){
    	$data['quantity20000'] = $searcharray['fifth'];
    	$data['quantity30000'] = $searcharray['sixth'];
	}
	if($searcharray['product']=='47' || $searcharray['product']=='48'){
    	$data['quantity50000'] = $searcharray['fifth'];
    	$data['quantity100000'] = $searcharray['sixth'];
	}
	$data['width'] = $searcharray['width'];
	$data['height'] = $searcharray['height'];
	$data['gusset'] = isset($searcharray['gusset']) ? $searcharray['gusset'] : '';
	$data['volume'] = $searcharray['volume'];
	$data['valve'] = $searcharray['valve'][0];
	$data['accessorie'] = $searcharray['accessorie'][0];
	$data['zipper'] = $searcharray['zipper'][0];
	$data['spout'] = $searcharray['spout'][0];
	$data['color'] = $searcharray['color'];
	$data['product'] = $searcharray['product'];
	$data['template_size_id'] = $searcharray['template_size_id'];
//	printr($data);die;
	$response=$obj_template->updateTemplateSize($data,$data['template_size_id']);
	echo json_encode($response);
}
else if($fun == 'cloneTemplate'){
	$response=$obj_template->DuplicateMySQLRecord($_POST['template_id']);
	echo $response;
}
else if($fun == 'cloneColor'){
	$response=$obj_template->DuplicateColourClone($_POST['template_size_id']);
	echo $response;
}
else if($fun == 'PasteColor'){
	$response=$obj_template->DuplicatePasteColour($_POST['template_size_id']);
	echo $response;
}
else if($fun == 'smit_data'){
	$response=$obj_template->getsmitdata();
	echo $response;
}


?>