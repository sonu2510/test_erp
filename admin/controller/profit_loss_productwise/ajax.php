<?php
// Start: Building System
include("mode_setting.php");
$fun = $_GET['fun'];

if($fun == 'get_profit_loss') {
	$data=$obj_profit_loss->get_profit_loss($_POST['f_date'],$_POST['t_date']);	
	$response = '';
		$response .= '<table class="table b-t text-small table-hover">';
			$response .= '<thead>';
								if($data!=0)
								{		if($_POST['n'] == '1')
											$response .= '<tr style="text-align:right;"><td colspan="5"><a class="label bg-success  pull-right" href="javascript:void(0);" onclick="get_report()" ><i class="fa fa-print"></i>Report</a></td></tr>';
										$response .= '<th>Sr.no</th>
													  <th>Product Code</th>
													  <th>Purchase Qty</th>
													  <th>Sales Qty</th>
													  <th><span style="color:green;">Profit<span> / <span style="color:red;">Loss<span></th>';

										$i=1;
										foreach($data as $d)
										{
											$tot = $d['purchase_qty_tot']-$d['sales_qty_tot'];
										
											if($tot > 0)
											{
												$status = '<span style="color:green;">Profit<span>';
												$color = 'green';
											}
											else if($tot == 0)
											{
												$status = '<span style="color:blue;"><span>';
												$color = 'blue';
											}
											else
											{	
												$status = '<span style="color:red;">Loss<span>';
												$color = 'red';
											}
											
											
											$response .= '<tr>
																<td>'.$i.'</td>
																<td><b>'.$d['product_code'].'</b></td>
																<td>'.$d['purchase_qty_tot'].'</td>
																<td>'.$d['sales_qty_tot'].'</td>
																<td><b><span style="color:'.$color.';">'.$tot.'</span></b></td>
														 </tr>';
											$i++;
										}
										 
								}
								else
								{
									$response .= '<th>No Record Found!!</th>';
								}
			$response .= '</thead>';
		$response .= '</table>'; 
	echo $response;
}
if($fun == 'product_code'){
	$product_code = $_POST['product_code'];
	$result = $obj_profit_loss->getProductCd($product_code);
	echo json_encode($result);
}

?>