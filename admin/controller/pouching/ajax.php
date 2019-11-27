<?php

include("mode_setting.php");

$fun = $_GET['fun'];

if($fun=='updateRollStatus')
{ 
	//printr($_POST);die;
	$roll_id = $_POST['roll_id'];
	$status_value = $_POST['status_value'];
	//echo $volume_id."====".$status_value;
	//die;

	$obj_pouching->$fun($roll_id,$status_value);
}

if($fun=='pouching_report')
{
	//printr($_POST['slitting_id']);die;
	$data = $obj_pouching->view_pouching_report($_POST['pouching_id']);
	echo $data;
}if($fun=='getROllDetail')
{
	//printr($_POST['slitting_id']);die;
	$data = $obj_pouching->getROllDetail($_POST['slitting_material_id']);


	//printr($data);

	echo json_encode($data);
}
if($fun=='input_roll_details')
{
	//printr($_POST['slitting_id']);die;
	$data = $obj_pouching->input_roll_details($_POST['val'],'0');
	//printr($data);
	$html='';
		$val='';

		 $val.='<div class="form-group">';
			$val.='	<label class="col-lg-2 control-label"> Roll  Details </label>'; 
             $val.='<div class="col-lg-4">';
                 $val.='<section class="panel">';
                    $val.='<div class="table-responsive">';
                     $val.='<table class="tool-row table-striped  b-t text-small" id="sl_roll"  width="100%">';
                      $val.='<thead id="first">';
                          $val.='<tr>';
                           $val.='<th  width="50%" ><span class="required">*</span>Roll No </th>';
							 $val.='<th width="15%">Input Qty (kgs)</th>';
								 $val.='<th width="15%">Output Qty(kgs)</th>';
								 $val.='<th width="10%">Balance Qty(kgs)</th>';
								 $val.='<th width="10%"></th>';
                                 $val.='</tr> ';                               
                              $val.='</thead>';
                            $val.='  <tbody> ';                                                 
                             $val.='<tr class="multiplerows-1 " id="multiplerows-1 ">';  

                               $val.='<td  width="50%">
                               <input type="hidden" id="roll_array" value='.json_encode($data['slitting']) .' />';
								 $val.='<select name="roll_details[1][roll_no]" id="roll_no_1" class="form-control validate[required] chosen_data select_choose"  onchange="getROllDetail(1)">
												<option value="0">Select Roll	</option>';
														
											if(!empty($data['slitting'])){
													foreach($data['slitting'] as $slt){
													
													 $val.='<option value="'. $slt['slitting_material_id'].'" >
														'.$slt['roll_code'].'
														</option>';
												} }
										 $val.=' </select>';
										   $val.=' <td width="15%">';
											 $val.=' <input type="text"   readonly  id="roll_input_qty_1" name="roll_details[1][roll_input_qty]" value="" class="form-control ">';
                                        $val.='  </td>';
									$val.='	<td width="15%">';
										$val.='	<input type="text"  id="roll_output_qty_1"name="roll_details[1][roll_output_qty]" value="" class="form-control ">';
                                      $val.='  </td> ';
                                       $val.='   <td width="10%">';
										$val.='	<input type="text"  id="roll_bal_qty_1"name="roll_details[1][roll_bal_qty]" value="" class="form-control ">';
                                      $val.='  </td>';   

                                       $val.='   <td width="10%">';
										
                                       $val.='   <a  onclick="add_row(1)"class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Profit" id="addmore_1>"><i class="fa fa-plus"></i></a>';
                                 
                                      $val.='  </td>';   
                                 $val.='  </tr>';
                              $val.='  </tr>';
                            $val.='  </tbody>';
                            $val.=' </table>';
                           $val.='  </table>';
                          $val.='  </div>';
                         $val.='  </section>';
                        $val.='  </div>';
             


		/*$html .= '<div class="col-lg-1"></div>';
		$html .= '<div class="col-lg-5"id="myTable">';
					$html .= '<label class="col-lg-2 control-label">Slitting Roll Details</label>'; 
					$html .= '<div class="col-lg-6">';
					$html .= '<section class="panel">';
					$html .= '<div class="table-responsive sec_div">';
					$html .= ' <table class="table table-bordered" width="100%" >';
					$html .= '<thead>';
					$html .= '<tr>';
					$html .= '<th >Roll No</th>';
					$html .= '<th >Input Qty</th>';                                   
					$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';
					//printr($data);
					if(!empty($data['slitting'])){
						foreach($data['slitting'] as $slt){
							$html .= '<tr>';
							$html.='<td>
							'.$slt['roll_code'].'</td>';
							$html.='<td>'.$slt['output_qty'].'</td>';
							$html .= '</tr>';
					
						}
					}else{
						$html .= '<tr><td colspan="2">roll not Available</tr> ';
					}
					$html .= '</tbody>';
					$html .= '</table>';
					$html .= '</div>';	
					$html .= '</section>';
					$html .= '</div>';
					
		$html .= '</div>';*/
		
		$val .= '<div class="col-lg-5"id="myTable">';
					$val .= '<label class="col-lg-2 control-label">Lamination Roll Details</label>'; 
					$val .= '<div class="col-lg-6">';
					$val .= '<section class="panel">';
					$val .= '<div class="table-responsive sec_div">';
					$val .= ' <table class="table table-bordered" width="100%" >';
					$val .= '<thead>';
					$val .= '<tr>';
					$val .= '<th >Roll Code</th>';
					$val .= '<th >Roll Size</th>';
					$val .= '</tr>';
					$val .= '</thead>';
					$val .= '<tbody>';
					if(!empty($data['lamination'])){
						foreach($data['lamination'] as $lami){
							$val .= '<tr>';
							$val.='<td>'.$lami['roll_code'].'</td>';
							$val.='<td>'.$lami['roll_size'].'</td>';
							$val .= '</tr>';
						}
					}else{
							$val .= '<tr><td colspan="2">roll not Available</tr> ';
					}
					$val .= '</tbody>';
					$val .= '</table>';
					$val .= '</div>';	
					$val .= '</section>';					
					$val .= '</div>';
					
			$val .= '</div>';
			$val.=' </div>  ';		
				
				
	
	echo $val;
	//echo json_encode($html);	
}


?>