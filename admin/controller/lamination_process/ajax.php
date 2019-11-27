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

	$obj_lamination->$fun($roll_id,$status_value);
}
if($fun=='getInputQty')
{ 
	$data=$obj_lamination->$fun($_POST['val']);
	echo json_encode($data);
}

if($fun=='update_lamination_status')
{ 
	$data=$obj_lamination->$fun($_POST['val']);
	echo $data;
}
if($fun=='job_detail')
{ 
	$data=$obj_lamination->$fun($_POST['job']);
	echo json_encode($data);
}

if($fun=='remove')
{	
	//printr($_POST['lamination_layer_id']);die;
	$data = $obj_lamination->$fun($_POST['lamination_layer_id']);
}
if($fun=='lamination_report')
{
	//printr($_POST['lamination_id']);die;
	$data = $obj_lamination->viewlamination_report($_POST['lamination_id']);
	echo $data;
}
if($fun=='getMaterialDetails')
{
	$product_item_layer_id=$_POST['product_item_layer_id'];
	$layer_id=$_POST['layer_id'];
	$data=$obj_lamination->getRollNoDetails($product_item_layer_id,$layer_id);
	$roll=
	$html='';
	$inner_count=1;
	$html .= '<label class="col-lg-2 control-label"></label>'; 
        $html .= '<div class="col-lg-9">';
           $html .= '<section class="panel">';
              $html .= ' <div class="table-responsive sec_div">';
                $html .= '<table class="tool-row table-striped  b-t text-small" id="myTable" width="100%">';
                   $html .= ' <thead>';
                      $html .= ' <tr> ';                                 		
                          $html .= '<th colspan="2"><span class="required">*</span>Roll No</th>';
                            $html .= '<th colspan="2"><span class="required">*</span>Film/Roll Name</th>';
                             $html .= '<th><span class="required">*</span>Film/Roll Size</th>';
                              $html .= '<th><span class="required">*</span>Input Qty (Kgs)</th>';
                             $html .= ' <th><span class="required">*</span>Output Qty (Kgs)</th> ';                                    
                              $html .= ' <th><span class="required">*</span>Balance Qty (Kgs)</th>';
							$html .= '<th></th>';
                               $html .= ' </tr>';                                
							$html .= ' </thead>';
                         $html .= '<tbody>';
                                 
                           $html .= '<tr class="multiplerows-'.$inner_count .'" id="multiplerows-'.$inner_count .' ">';
								
										$html .= '<input type="hidden" id="min_arr" value='.json_encode($data).' />  ';
									
									//	$html .= '<input type="hidden" name="roll_details['.$inner_count .'][printing_roll_id]" id="printing_roll_id" value="" class="form-control validate[required]" >';
										$html .= '<td colspan="2">'; 
											$html .= '<select name="roll_details['.$inner_count .'][roll_no_id]" id="roll_no_id_'.$inner_count.'" class="form-control validate[required] chosen_data select_choose" style="width:auto;" onchange="roll_detail_layer('.$inner_count .','.$inner_count .')">';   
											if(!empty($data)){
											foreach($data as $rollno){ 
												$html .= '<option value="'.$rollno['product_inward_id'].'" id="option_'.$inner_count .'" 	>  '.$rollno['roll_no'].'</option>';
											   }  }else{
											       	$html .= '<option value="0" id="option_'.$inner_count .'"  >Roll Not Available  </option>';
											   }
											$html .= '</select>';
                                       	$html .= '</td>';
                                       	$html .= '<td colspan="2">';
											$html .= '<input type="text" style="width:auto;" name="roll_details['.$inner_count .'][roll_name_id]" id="roll_name_id_'.$inner_count .'" value="" class="form-control validate[required]" readonly="readonly">';
                                     	$html .= '</td>';
                                       	$html .= '<td>';
											$html .= ' <input type="text"  name="roll_details['.$inner_count .'][film_size]" id="film_size_'.$inner_count .'" value="" class="form-control validate[required]" readonly="readonly">';
                                     	$html .= '</td>';
                                      	$html .= '<td>';
											$html .= '<input type="text" name="roll_details['.$inner_count .'][input_qty]" id="input_qty_'.$inner_count .'" value="" class="form-control validate[required,custom[number],min[0.001]]">';
										$html .= '</td>';
                                    	$html .= '<td>';
											$html .= '<input type="text" name="roll_details['.$inner_count .'][output_qty]" id="output_qty_'.$inner_count .'"  onchange="total_quantity('.$inner_count .')"value="" class="form-control validate[required,custom[number],min[0.001]]">';
										$html .= '</td>';                                      
                                        $html .= '<td>';
											$html .= '<input type="text" name="roll_details['.$inner_count .'][balance_qty]"  id="balance_qty_'.$inner_count .'" value="" class="form-control validate[required,custom[number],min[0.001]]">';
										$html .= '</td>'; 
										$html .= '<td>';
											$html .= '<a  onclick="add_row('.$inner_count .')"class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Profit" id="addmore_<?php echo $inner_count; ?>"><i class="fa fa-plus"></i></a>';
                                  	$html .= '</td>';                                   
                                  $html .= ' </tr>';                                          
                           $html .= '   </tbody>';
                           $html .= '  </table>';
                        $html .= '    </div>';
                       $html .= '    </section>';
                     $html .= '     </div>';

						  echo $html;
}
if($fun=='addLamination'){
	$html = '';
	parse_str($_POST['formData'], $post);

	$insert_id = $obj_lamination->addLamination($post);
		printr($insert_id);die;

	echo $insert_id;

}


if($fun == 'removeInvoice'){

	$result=$obj_lamination->removeInvoice($_POST['lamination_layer_id'],$_POST['lamination_id']);
}

if($fun=='remove_roll')
{ 
	$data=$obj_lamination->$fun($_POST['lamination_roll_detail_id']);
	echo json_encode($data);
}
if($fun=='updatepass')
{ 
	$data=$obj_lamination->$fun($_POST['l_id'],$_POST['passvalue'],$_POST['op_name'],$_POST['m_name'],$_POST['remark'],$_POST['remark_lamination']);
	echo json_encode($data);
}
?>