<?php
// Start: Building System
include("mode_setting.php");
$fun = $_GET['fun'];

if($fun == 'get_sheet') {
	$data=$obj_goods->get_sheet($_POST['f_date'],$_POST['t_date'],$_POST['by_option']);	
   
	$response = '';
		 $response .= '<div class="form-group">
							<div class="col-lg-9 col-lg-offset-3 tab_data">';
								if($_POST['n'] == '1')
									$response .= '<div><a class="label bg-success  pull-right" href="javascript:void(0);" onclick="get_report()" ><i class="fa fa-print"></i>Report</a></div>';
						$response .= '</div>
						</div>';
						$response .= '<table class="table b-t text-small table-hover" border="1">';
							$response .= '<thead>';
												if(!empty($data))
												{
														
														if($_POST['by_option']=='1' && $_POST['by_option']=='2')
														    $col ='4';
														else if($_POST['by_option']=='3')
														    $col ='3';
														else
														     $col ='5';
														if($_POST['n']==0)
														{
															$response .= '<tr>
															                    <th colspan="'.$col.'">
        																			<h5><b> Date From : '.dateFormat('4',$_POST['f_date']).' To: '.dateFormat('4',$_POST['t_date']).'</h5>
        																		 </th>
																		 </tr>';
														}
														$response .= '<tr>';
    														$response .= '<th>Month</th>';
    														            if($_POST['by_option']!='3')
																              $response .='<th>Product Code</th>';
																            else
																              $response .='<th>Product Name</th>';
    																	  
    																	  $response .='<th>Qty</th>';
    																	  if($_POST['by_option']!='3')
    																	    $response .='<th>Rate</th>';
    																	    
    																	  if($_POST['by_option']=='4')
    															            $response .=' <th>Size</th>';
														$response .='</tr>';
														foreach($data as $key=>$dt)
														{	$count=count($dt)+1;
															$response .= '<tr valign="top">
																				<td rowspan="'.$count.'" >'.date('F', strtotime($key)).'</td>
																		  </tr>';
															                    foreach($dt as $dta)
																				{
																				    $response .='<tr>';
																				                    if($_POST['by_option']!='3')
    																					              $response .='<td>'.$dta['product_code'].'</td>';
    																					            else
    																					              $response .='<td>'.$dta['product_name'].'</td>'; 
    																								
    																								$response .='<td>'.$dta['tot_qty'].'</td>';
    																								
    																								if($_POST['by_option']!='3')
    																								   $response .=' <td>'.$dta['rate'].'</td>';
    																								   
    																								if($_POST['by_option']=='4')
    																								  $response .=' <td>'.$dta['size'].'</td>';
																					$response .='</tr>';
																				}
														}
												}
												else
												{
													$response .= '<th>No Record Found!!</th>';
												}
							$response .= '</thead>';
						$response .= '</table>'; 
		
	
		echo  $response;
}
?>