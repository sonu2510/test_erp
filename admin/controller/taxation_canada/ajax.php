<?php
include("mode_setting.php");

if($_GET['fun']=='TaxUpdate')
{
	 	$taxation_canada_id = $_POST['taxation_canada_id'];
		$tax_gst = $_POST['tax_gst'];
		$tax_rst = $_POST['tax_rst'];
		$tax_hst = $_POST['tax_hst'];
		$price = $_POST['price'];
		$price_e = $_POST['price_e'];
		$abb = $_POST['abb'];
		$obj_tax_canada->TaxUpdate($taxation_canada_id,$tax_gst,$tax_rst,$tax_hst,$price,$price_e,$abb);
		echo $taxation_canada_id;
		
}
	 
?>