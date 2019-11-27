<?php
//printr($_GET['invoice_no']);die;
include('PDFMerger.php');

$pdf = new PDFMerger;

/*$pdf->addPDF(DIR_UPLOAD.'admin/return_doc/'.$_GET['invoice_no'].'first.pdf', 'all')
	->addPDF(DIR_UPLOAD.'admin/return_doc/'.$_GET['invoice_no'].'second.pdf', 'all')
	->merge('file', 'inr.pdf');
	*/
	
$pdf->addPDF('1.pdf', 'all')
	->addPDF('2.pdf', 'all')
	->merge('file', 'inr.pdf');
	
?>
