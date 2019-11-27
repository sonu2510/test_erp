<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateAciveStatus')
{ 
	
	$tax_id = $_POST['tax_id'];
	$status_tax = $_POST['status_tax'];
	//echo $status_tax;die;
	$obj_tax_calender->$fun($tax_id,$status_tax);
}
if($fun=='removeAusUploadRecord')
{ 
	
	$sin_image_id = $_POST['sin_image_id'];
	$obj_tax_calender->$fun($sin_image_id);
	
}

if($fun == 'sendEmail')
{	
	$result  = $obj_tax_calender->$fun($_POST['tax_sin_calender_id'],$_POST['adminEmail']);
	echo $result;
}	
?>



