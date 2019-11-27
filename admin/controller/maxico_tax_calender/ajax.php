<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateMaxicotax')
{ 
	
	$tax_id = $_POST['tax_id'];
	$status_tax = $_POST['status_tax'];

	$obj_tax_calender->$fun($tax_id,$status_tax);
}
if($fun=='removeAusUploadRecord')
{ 
	
	$max_image_id = $_POST['max_image_id'];
	$obj_tax_calender->$fun($max_image_id);
	
}
if($fun == 'sendEmail')
{	
	$result  = $obj_tax_calender->$fun($_POST['tax_calender_id'],$_POST['adminEmail']);
	echo $result;
}
?>



