<?php
// Start: Building System
include("mode_setting.php");
$fun = $_GET['fun'];
// End: Building System
if($_GET['fun']=='UserNameAlreadyExsist') {
if(isset($_POST['name']) && $_POST['name'] != ''){
	$user_name = $_POST['name'];
	//echo $material_name;die;
	$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "account_master` WHERE user_name = '".$user_name."'";
	$data = $obj_db->query($sql);
	//echo $data->row['total'];die;
	if($data->row['total'] > 0){
		echo 1;
	}else{
		echo 0;
	}
}else{
	echo 0;
}

}

if($fun == 'get_price') {
	parse_str($_POST['formData'], $postdata);
	//printr( $postdata);
	$data=$obj_user->get_price($postdata);	
	
	$response = '';
		$response .= '<table class="table b-t text-small table-hover">';
			$response .= '<thead>';
				if(!empty($data))
				{
					
					foreach($data as $da)
					{
						$response .= '<th>'.$da['courier_name'].'</th>';
						$fuleSurchargeForZipper = (($da['price']*$da['fuel_charge'])/100);
						$courierCrhgFuleZipper = ($da['price']+ $fuleSurchargeForZipper);
						$serviceTaxZipper = (($courierCrhgFuleZipper * $da['service_tax']) / 100);
						$courierChargeZipper = $obj_user->numberFormate(($da['price'] + $fuleSurchargeForZipper + $serviceTaxZipper + $da['handling_charge']),"3");
						$Courier[]=$courierChargeZipper;
					}
					$min=min($Courier);
					$response .= '<tr>';
						foreach($data as $da)
						{
							 
							$fuleSurchargeForZipper = (($da['price']*$da['fuel_charge'])/100);
							$courierCrhgFuleZipper = ($da['price']+ $fuleSurchargeForZipper);
							$serviceTaxZipper = (($courierCrhgFuleZipper * $da['service_tax']) / 100);
							$courierChargeZipper = $obj_user->numberFormate(($da['price'] + $fuleSurchargeForZipper + $serviceTaxZipper + $da['handling_charge']),"3");
							
							$response .= '<td> <small class="text-muted">Basic Courier Price (Per kg) : INR </small>'.$obj_user->numberFormate($da['PerKgPrice'],"3").'<br>
												<small class="text-muted">Courier Charge With Tax : INR </small>'.$obj_user->numberFormate($courierChargeZipper/$postdata['weight'],"3").'<br>';
									if($min == $courierChargeZipper)
										$response .= '<small class="text-muted">Total Courier Charge : INR </small><span style="color:red;"><b>'.$obj_user->numberFormate($courierChargeZipper,"3").'</b></span><br>';
									else
										$response .= '<small class="text-muted">Total Courier Charge : INR </small>'.$obj_user->numberFormate($courierChargeZipper,"3").'<br>';
									
										$response .= '<small class="text-muted"><strong> Zone : '.$da['courier_zone'].'</strong> </small></td>';
						}
					$response .= '</tr>';
				}
				else
				{
					$response .= '<th>No Records Found!!</th>';
				}
			$response .= '</thead>';
		$response .= '</table>'; 
	echo $response;
}


?>