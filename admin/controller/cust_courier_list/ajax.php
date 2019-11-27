<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();

if($fun == 'updateCourierZonePrice') {
	if(isset($_POST['id']) && (int)$_POST['id'] > 0 ){
		$updateId = (int)$_POST['id'];
		$from_kg = (float)$_POST['fkg'];
		$to_kg = (float)$_POST['tkg'];
		$price = (float)$_POST['price'];
		$obj_courier -> $fun($from_kg,$to_kg,$price,$updateId);
		$json['success'] = 'Successfully Updated!';
	}else{
		$json['warning'] = 'Error!';
	}
	
	echo json_encode($json);
}

if($fun == 'deleteCourierZonePrice'){
	if(isset($_POST['id']) && (int)$_POST['id'] > 0 ){
		$obj_courier -> $fun((int)$_POST['id']);
		
		$json['success'] = 'Successfully Deleted!';
	}else{
		$json['warning'] = 'Error!';
	}
	
	echo json_encode($json);
}

if($fun == 'addCourierZonePrice'){
	
	if(isset($_POST['courier_id']) && (int)$_POST['courier_id'] > 0 && isset($_POST['zone_id']) && (int)$_POST['zone_id'] > 0 ){
		$courier_id = (int)$_POST['courier_id'];
		$courier_zone_id = (int)$_POST['zone_id'];
		$from_kg = (float)$_POST['fkg'];
		$to_kg = (float)$_POST['tkg'];
		$price = (float)$_POST['price'];
		
		$obj_courier -> $fun($courier_id,$courier_zone_id,$from_kg,$to_kg,$price);
	
		$json['success'] = 'Successfully Inserted';
	
	}else{
		
		$json['warning'] = 'Error!';
	}
		
	echo json_encode($json);	
}

if($fun=='chckAddedZoneCountry'){
	$courier_id = $_GET['courier_id'];
	$courier_zone_id = $_GET['courier_zone_id'];
	//echo $courier_id."===".$courier_zone_id; die;
	
	$checkAddedZoneCountry = $obj_courier -> $fun($courier_id,$courier_zone_id);
	
	$countrys = $obj_courier->getCountry($checkAddedZoneCountry);
	
	$country_array = array();
	$filter_array = array();
	$country_name =array();

	//printr($country_name);die;
	if(isset($_GET['q']) && !empty($_GET['q'])){
		foreach($countrys as $country){					
			if(strpos($country['country_name'], ucwords($_GET['q']))===0){
				$country_array[] = array(
					'id' => $country['country_id'],
					'text' => $country['country_name']
				);
			}
		}
		//print_r($country_array);die;
	}else{
		foreach($countrys as $country){
			$country_array[] = array(
				'id' => $country['country_id'],
				'text' => $country['country_name']
			);
		}		
	}
	
	
	echo json_encode($country_array);

}
if($fun=='increment_decrement_price'){

	parse_str($_POST['formData'], $postdata);
	$incre_decre = $obj_courier -> $fun($postdata);
}

if($fun=='reset_zone_price'){

	$courier_zone_id = $_POST['courier_zone_id'];
	$courier_id = $_POST['courier_id'];
	$update_zone_price = $obj_courier -> $fun($courier_zone_id,$courier_id);
}


?>