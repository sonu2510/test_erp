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
//	printr($searcharray);die;
	$response = '';
	$colorval='';
	$detailslist='';
	
	$second = $searcharray['second'];
	$third = $searcharray['third'];
	$fourth = $searcharray['fourth'];
	$fifth = $searcharray['fifth'];
	$width = $searcharray['width'];
	$height = $searcharray['height'];
	$gusset = isset($searcharray['gusset']) ? $searcharray['gusset'] : '';
	$volume = $searcharray['volume'];
	//$valve = $searcharray['valve'];
	//$accessorie = $searcharray['accessorie'];
//	$zipper = $searcharray['zipper'];
	//$spout = $searcharray['spout'];
	$color = $searcharray['color'];
	$title = $searcharray['title'];
	$product = $searcharray['product'];
	$country = json_encode($searcharray['country_id']);
	
	$user = $searcharray['user'];
	$currency = $searcharray['currency'];
//	$transport = $searcharray['transport'];
		if($searcharray['templateid']== 0)
		{
			$user_name = $obj_template->$fun($title,$product,$country,$user,$currency);
		}
		else
		{
			$user_name = $searcharray['templateid'];
		}
		$currency_code = $obj_template->getSelectedCurrency($currency);


	//	printr(json_encode($searcharray['color']));
		//echo $valve[0];
	    $multipleval = $obj_template->addProduct($user_name,$second,$third,$fourth,$fifth,$width,$height,$gusset,$volume,json_encode($color),$product);		
		$response .= '<table class="table table-bordered">';
		$response .= '<thead>';
		$response .= '<tr>';	
	 	$response .= '<th>Size</th>';
		$response .= '<th >
                    Dimension<br />
					WxLxG
                    </th>';
				
				$qty200 = ' Qty200+';
				$qty500 = ' Qty500+';;
				$qty1000 = ' Qty1000+';			
				$qty2000 = ' Qty2000+';			
	
	 	$response .= ' <th>Price ('.$currency_code['currency_code'].')<br>'.$qty200.'</th>';
		$response .= ' <th>Price ('.$currency_code['currency_code'].')<br>'.$qty500.'</th>';
	 	$response .= '<th >Price ('.$currency_code['currency_code'].')<br>'.$qty1000.'</th>';
	 	$response .= '<th >Price ('.$currency_code['currency_code'].')<br>'.$qty2000.'</th>';
		$response .= ' <th>
                   Color
                    </th>';
		$response .= '<th>Action</th>';
	 	$response .= '</tr>'; 
		$response .= '</thead>'; 
		$response .= '<tbody>'; 
	
		$detailslist = $obj_template->getaddProductDetails($user_name,$product);
		 
		 foreach($detailslist as $details){
		 
		 	
				
				$qty200 = $details['quantity200'];
				$qty500 = $details['quantity500'];
				$qty1000 = $details['quantity1000'];
				$qty2000 = $details['quantity2000'];
			
			
		
			$response .= '<tr id='.$details['digital_template_size_id'].'>';		
			$response .= '<td>'.$details['volume'].'</td>';
			$response .= '<td>'.$details['width'].'X'.$details['height'].'X'.$details['gusset'].'</td>';
			
			$response .= '<td>'.$qty200.'</td>';
			$response .= '<td>'.$qty500.'</td>';
			$response .= '<td>'.$qty1000.'</td>';		
			$response .= '<td>'.$qty2000.'</td>';		
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
			$response .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeTemplate('.$details['digital_template_size_id'].')"><i class="fa fa-trash-o"></i></a>
			 <a href="javascript:void(0);" 
                                onClick="getTemplate('.$details['digital_template_size_id'].')" class="btn btn-info btn-xs">Edit</a>
								</td>';
			$response .= '</tr>'; 
		}	
		$response .= '</tbody>'; 
		$response .= '</table>';
		$arr['response'] = $response;		
		$arr['result'] = $user_name;
		echo json_encode($arr);
}else if($fun == 'removeTemplate'){
	$obj_template->removeTemplateProduct($_POST['digital_template_size_id']);
}
else if($fun == 'getTemplate'){
	$response=$obj_template->getTempalteSize($_POST['digital_template_size_id']);
	echo json_encode($response);
}
else if($fun == 'updateTemplate'){
	parse_str($_POST['str'], $searcharray);
	
	$data=array();

	$data['quantity200'] = $searcharray['second'];
	$data['quantity500'] = $searcharray['third'];
	$data['quantity1000'] = $searcharray['fourth'];
	$data['quantity2000'] = $searcharray['fifth'];
	$data['width'] = $searcharray['width'];
	$data['height'] = $searcharray['height'];
	$data['gusset'] = isset($searcharray['gusset']) ? $searcharray['gusset'] : '';
	$data['volume'] = $searcharray['volume'];
	
	$data['color'] = $searcharray['color'];
	$data['product'] = $searcharray['product'];
	$data['digital_template_size_id'] = $searcharray['digital_template_size_id'];
	
	$response=$obj_template->updateTemplateSize($data,$data['digital_template_size_id']);
	echo json_encode($response);
}
else if($fun == 'cloneTemplate'){
	$response=$obj_template->DuplicateMySQLRecord($_POST['template_id']);
	echo $response;
}




?>