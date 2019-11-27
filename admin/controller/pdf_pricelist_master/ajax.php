<?php
include("mode_setting.php");
$fun = $_GET['fun'];
if($fun == 'formdetails')
{
$result =  $obj_pdf->adddata($_POST['agentname'],$_POST['countryid'],$_POST['agentadress'],$_POST['mailid'],$_POST['abnno'],$_POST['cifamount'],$_POST['fobamount'],$_POST['customduty'],$_POST['voti'],$_POST['gstonimport'],$_POST['othercharges'],$_POST['clearingcharges']);
	//printr($result);
$result .= 'data added ';

echo $result;
	
}
?>
