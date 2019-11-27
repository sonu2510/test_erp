<?php
require(DIR_SERVER.'pdf/WriteHTML.php');

$pdf=new PDF_HTML();

$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 15);

$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->WriteHTML('<para><h1>PHPGang Programming Blog, Tutorials, jQuery, Ajax, PHP, MySQL and Demos</h1><br>
Website: <u>www.phpgang.com</u></para><br><br>How to Convert HTML to PDF with fpdf example');

$pdf->SetFont('Arial','B',7); 
$htmlTable='<TABLE>
<TR>
<TD>Name:</TD>
<TD>test</TD>
</TR>
<TR>
<TD>Email:</TD>
<TD>test@test.com</TD>
</TR>
</TR>
</TABLE>';
$pdf->WriteHTML2("<br><br><br>$htmlTable");
$pdf->SetFont('Arial','B',6);
$pdf->Output(); 
?>