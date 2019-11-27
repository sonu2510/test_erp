<?php
//==>kinjal chnage by sonu
class production_report extends dbclass{
	public function getOperator()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type !='1'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false; 
		}
	}
	public function getMachine($production_process_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "machine_master` WHERE is_delete = 0 AND production_process_id LIKE'%".$production_process_id."%'";
	  // echo $sql;
		$data = $this->query($sql);
	//	printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
		public function getMachineName($machine_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "machine_master` WHERE is_delete = 0 AND machine_id ='".$machine_id."'";
	  // echo $sql;
		$data = $this->query($sql);
	//	printr($data);
		if($data->num_rows){
			return $data->row['machine_name'];
		}else{
			return false;
		}
	}
	
	public function getJobDetail($job_id)
	{
		
		$sql = "SELECT pb.*,om.first_name as operator_name,mm.machine_name,jm.job_name as job_num_id FROM printing_job as pb, employee as om,machine_master as mm,job_master as jm WHERE pb.is_delete = 0 AND pb.job_id = '".$job_id."' AND pb.operator_id=om.employee_id AND pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id";
		
		$data = $this->query($sql);
	//	printr($data);
		//return $data->row;
		
		
		$sql2 = "SELECT * FROM  printing_roll_details as pd, product_inward as pi   WHERE pd.roll_no_id=pi.product_inward_id  AND  pd.job_id = '".$job_id."' AND pd.is_delete = '0'";	
		$roll_data = $this->query($sql2);
		//printr($roll_data);die;
		
		$roll_details=array();
		foreach($roll_data->rows as $layer){
			
		$roll_details[] = array(
                    'printing_roll_id' =>$layer['printing_roll_id'],
                    'job_id' =>$layer['job_id'],
					'roll_no_id'=>$layer['roll_no_id'],
                    'roll_name_id' => $layer['roll_name_id'],
                    'film_size' => $layer['film_size'],                   
                    'input_qty' =>$layer['input_qty'],
                    'output_qty' => $layer['output_qty'],                    
                    'output_qty_m' => $layer['output_qty_m'],                    
                    'balance_qty' => $layer['balance_qty'],
                    'roll_no' => $layer['roll_no'],
                   
                );
		}
	
		$printing_array =array();
            $printing_array = array(
                'job_id' => $data->row['job_id'],
				'job_no' => $data->row['job_no'],
                'job_date' => $data->row['job_date'],
                'job_name_id' => $data->row['job_name_id'],
                'job_name_text' => $data->row['job_name_text'],
                'job_name' => $data->row['job_name'],
                'start_time' => $data->row['start_time'],
                'end_time' => $data->row['end_time'],
                'job_type' => $data->row['job_type'],
                'operator_id' => $data->row['operator_id'],
				'chemist_id' => $data->row['chemist_id'],
				'machine_id' => $data->row['machine_id'],
				'shift' => $data->row['shift'],
				'plain_wastage' => $data->row['plain_wastage'],
				'print_wastage' => $data->row['print_wastage'],
				'total_wastage' => $data->row['total_wastage'],
				'wastage_per' => $data->row['wastage_per'],
				'added_user_id' => $data->row['added_user_id'],
				'remark' => $data->row['remark'],
				'remaks_printing_job' => $data->row['remaks_printing_job'],
				'operator_name' => $data->row['operator_name'],
				'machine_name' => $data->row['machine_name'],
                'job_num_id' => $data->row['job_num_id'],    
				'roll_used' => $data->row['roll_used'],				
                'roll_details' => $roll_details,
               
            );

		//printr($printing_array);
		return $printing_array;
	}
	
	public function viewPrinting_report($post) 
	{
	    
	  //  printr($post);
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = "AND pb.job_date >= '" . $f_date . "' ";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= "AND  pb.job_date <='" . $to_date . "'";
        } 
        if ($post['machine_id'] != '') {
            $machine_id = $post['machine_id'];
            $con .= "AND  pb.machine_id ='" . $machine_id . "'";
        }
      if ($post['shift'] != '') {
            $shift = $post['shift'];
            $con .= "AND  po.operator_shift ='" . $shift . "'";
        }
        $machine_name=$this->getMachineName($post['machine_id']);
	/*	if($post['operator_id']!='')
			$con .= " AND pb.operator_id = '".$post['operator_id']."'";
			
	*/	
	  
		
	//	$sql = "SELECT pb.*,mm.machine_name,jm.job_name as job_num_id FROM printing_job as pb, machine_master as mm,job_master as jm WHERE pb.is_delete = 0  AND  pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id $con";
		$sql = "SELECT  pb.*,po.input_qty,po.output_qty,po.output_qty_m,po.balance_qty,po.operator_id,po.total_wastage,po.wastage_per,po.film_size,po.roll_used as no_of_roll, om.first_name,mm.machine_name,jm.job_name as job_num_id  FROM printing_job as pb,employee as om,  printing_operator_details as po,machine_master as mm,job_master as jm WHERE  po.operator_id=om.employee_id  AND  po.is_delete = 0 AND pb.job_id=po.printing_id AND pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id $con";
    //	printr($sql);die;
		$data = $this->query($sql);
	//	printr($data);//die;
		$html = '';
		if($data->rows)
		{
		//	$sql2 = "SELECT * FROM  printing_roll_details as pd, product_inward as pi   WHERE pd.roll_no_id=pi.product_inward_id  AND  pd.job_id = '".$data->row['job_id']."' AND pd.is_delete = '0' ";	
	        	//echo $sql2;
		    
			//$roll_data = $this->query($sql2);
			if($data->num_rows){
				
			  	$html.='<div class="panel-body">
						<table cellspacing="0px" cellpadding="1px" border="1" style=" width:100%;" class="table table-striped b-t text-small">
								<tbody >
								<tr  style="font-size: 22px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="13" ><b><center> PRINTING PRODUCTION REPORT</center></b>  </td>
								</tr><tr  style="font-size: 22px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="13" ><b>'.$post['shift'].'  Shift </b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Machine :- </b> '.$machine_name.' </td>
								</tr>';
							
				if (!empty($post['f_date']) && !empty($post['t_date'])) {
					$html .= "&nbsp;&nbsp;&nbsp;&nbsp;<span><h4>Searching Date From: <b>" . dateFormat(4, $post['f_date']) . "</b> To: <b>" . dateFormat(4, $post['t_date']) . "</b></h4></span><br></br>";
				}
				 
							$html .= '<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;" >
        									<td><b>Sr No</b> </td>
        									<td><b>Job Date</b> </td>
        									<td><b>Job Name</b> </td>
        									<td><b>Film Size</b> </td>
        									<td><b>No of Rolls</b> </td>
        									<td><b>Operator</b> </td>
        									<td><b>Input Qty(kgs)</b> </td>
        									<td><b>Output Qty(kg & meter)</b> </td>
        									<td><b>Balance Qty(kg)</b> </td>
        									<td><b>Total Wastage(Plan/Print)</b> </td>
        									<td><b>Wastage (%)</b> </td>
        									<td><b>Start time</b> </td>
        									<td><b>End time</b> </td>
        									
        								
							    	</tr>
								<tbody >';
										$i=1;$style='';
										//  $b=0;
											$total_input=$total_output=$total_output_m=$total_wastage=$total_wastage_per=0;
    										foreach($data->rows as $row)
										{ 
										  //   $style ="background-color: aliceblue;";
                                          //  if($b%2==0)
                                             //   $style ="background-color: antiquewhite;";
                                                
										    
										    	$operator=$this->getJuniorOperator_name($row['operator_id']);
        									//	  printr($row);
        									$html .= '<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;'.$style.'">
                									<td align="center">'.$i.' </td>
                									<td align="center">'. dateFormat(4, $row['job_date']).' </td>
                									<td align="center;" >'.$row['job_name'].'</td>
                									<td >'.$row['film_size'].'</td>
                									<td >'.$row['no_of_roll'].'</td>
                									<td align="center">'.$operator['first_name'].' '.$operator['last_name'].'</td>
                									<td align="center">'.$row['input_qty'].' kgs</td>
                									<td align="center">'.$row['output_qty'].' Kgs <br>'.$row['output_qty_m'].' Meter</td>
                									<td align="center">'.$row['balance_qty'].' kgs</td>
                									<td align="center">'.$row['total_wastage'].' kgs</td>
                									<td align="center">'.$row['wastage_per'].'(%)</td>
                									<td align="center">'.$row['start_time'].'</td>
                									<td align="center">'.$row['end_time'].'</td>
                								
                									
        							    	</tr>';
        							    	$total_input=$total_input+$row['input_qty'];
        							    	$total_output=$total_output+$row['output_qty'];
        							    	$total_output_m=$total_output_m+$row['output_qty_m'];
        							    	$total_wastage=$total_wastage+$row['total_wastage'];
        							    	$total_wastage_per=$total_wastage_per+$row['wastage_per'];
										$i++;$b++;
										    
										    
										}
											$html .= '<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;">
                									<td align="center" colspan="6" ><b>Total : </b> </td>
                									<td align="center">'.$total_input.' kgs</td>
                									<td align="center">'.$total_output.' Kgs <br>'.$total_output_m.' Meter</td>
                									<td align="center">-</td>
                									<td align="center">'.$total_wastage.' kgs</td>
                									<td align="center">'.$total_wastage_per.'(%)</td>
                										<td align="center" colspan="2" ></td>
                								
                									
        							    	</tr>';
							$html .= "</tbody>
									</table>
								 </div>
							</div>";
					
				
				return $html;
			}
		}else{
			return false;
		}
	}	
	public function viewPouchingreport($post) 
	{
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = "AND sl.pouching_date >= '" . $f_date . "' ";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= "AND  sl.pouching_date <='" . $to_date . "'";
        }
    /*   if ($post['machine_id'] != '') {
            $machine_id = $post['machine_id'];
            $con .= "AND  sl.machine_id ='" . $machine_id . "'";
        }
        if ($post['shift'] != '') {
            $shift = $post['shift'];
            $con .= "AND  sl.shift ='" . $shift . "'";
        }*/
        $machine_name=$this->getMachineName($post['machine_id']);
		$sql = "SELECT sl.*,CONCAT(om.first_name ,' ', om.last_name) as operator_name,m.machine_name,j.*  FROM pouching as sl,job_master as j, employee as om,machine_master as m WHERE sl.is_delete = 0 AND sl.job_id = j.job_id AND sl.operator_id=om.employee_id AND  sl.machine_id = m.machine_id  $con ";
		//printr($sql);
		$data = $this->query($sql);
       // printr($data);die;
		$html = '';
		if($data->rows)
		{
		//	$sql2 = "SELECT * FROM  printing_roll_details as pd, product_inward as pi   WHERE pd.roll_no_id=pi.product_inward_id  AND  pd.job_id = '".$data->row['job_id']."' AND pd.is_delete = '0' ";	
	        	//echo $sql2;
		    
			//$roll_data = $this->query($sql2);
			if($data->num_rows){
				
			  	$html.='<div class="panel-body">
						<table cellspacing="0px" cellpadding="1px" border="1" style=" width:100%;" class="table table-striped b-t text-small">
								<tbody >
								<tr  style="font-size: 22px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="12" ><b><center> PRINTING PRODUCTION REPORT</center></b>  </td>
								
								</tr><tr  style="font-size: 22px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="12" ><b><center> <b>Machine :- </b> '.$machine_name.' </center></b>  </td>
								</tr>';
							
				if (!empty($post['f_date']) && !empty($post['t_date'])) {
					$html .= "&nbsp;&nbsp;&nbsp;&nbsp;<span><h4>Searching Date From: <b>" . dateFormat(4, $post['f_date']) . "</b> To: <b>" . dateFormat(4, $post['t_date']) . "</b></h4></span><br></br>";
				}
				 
							$html .= '<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;" >
        									<td><b>Sr No</b> </td>
        									<td><b>Job Date</b> </td>
        									<td><b>Job No</b> </td>
        									<td><b>Job Name</b> </td>
        									<td><b>Operator</b> </td>
        									<td><b>Input Qty(kgs)</b> </td>
        									<td><b>Output Qty(kgs & meter)</b> </td>
        									<td><b>No Of pouches (Nos)</b> </td>
        									<td><b>Operator Wastage (Kgs)</b> </td>
        									<td><b>Total Wastage (Kgs)</b> </td>
        								
        								
							    	</tr>
								<tbody >';
										$i=1;$style='';
										//  $b=0;
											$total_input=$total_output=$total_output_m=$total_wastage=$total_no_of_pouch=$total_wastage_per=0;
    										foreach($data->rows as $row)
										{ 
										  //   $style ="background-color: aliceblue;";
                                          //  if($b%2==0)
                                             //   $style ="background-color: antiquewhite;";
                                                
                                                	
                                                
                                                	$product_name = $this->zipper_name($row['zipper_id']);
		                                            $job_detail = $this->input_roll_details_view($row['pouching_id']);

										    
										    	$operator=$this->getJuniorOperator_name($row['operator_id']);
        									//	 printr($job_detail);die;
        									//	 printr($row);
        									$html .= '<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;'.$style.'">
                									<td align="center">'.$i.' </td>
                									<td align="center">'. dateFormat(4, $row['pouching_date']).' </td>
                									<td align="center;" >'.$row['job_no'].'</td>
                									<td >'.$row['job_name'].'<br> <b>Roll No :</b>'.$job_detail['roll_code'].'</td>
                									<td align="center">'.$operator['first_name'].' '.$operator['last_name'].'</td>
                									<td align="center">'.$job_detail['input_qty'].' kgs</td>
                									<td align="center">'.$row['output_qty_kg'].' Kgs <br>'.$row['output_qty_meter'].' Meter</td>
                									<td align="center">'.$row['output_qty'].' </td>
                									<td align="center">'.$row['operator_wastage'].' kgs</td>
                									<td align="center">'.$row['total_wastage_c'].' kgs</td>
                								
                								
                									
        							    	</tr>';
        							    	$total_input=$total_input+$row['t_input_qty'];
        							    	$total_output=$total_output+$row['t_output_qty'];
        							    	$total_output_m=$total_output_m+$row['t_output_qty_m'];
        							    	$total_wastage=$total_wastage+$row['total_wastage'];
        							    	$total_wastage_per=$total_wastage_per+$row['wastage_per'];
        							    	$total_no_of_pouch=$total_no_of_pouch+$row['output_qty'];
										$i++;$b++;
										    
										    
										}
											$html .= '<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;">
                									<td align="center" colspan="5" ><b>Total : </b> </td>
                									<td align="center">'.$total_input.' kgs</td>
                									<td align="center">'.$total_output.' Kgs <br>'.$total_output_m.' Meter</td>
                									<td align="center">'.$total_no_of_pouch.'</td>
                									<td align="center">'.$total_wastage.' kgs</td>
                									<td align="center">'.$total_wastage_per.'(%)</td>
                								
                									
        							    	</tr>';
							$html .= "</tbody>
									</table>
								 </div>
							</div>";
					
				
				return $html;
			}
		}else{
			return false;
		}
	}
	public function viewSlittingreport($post)
	{
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = "AND sl.slitting_date >= '" . $f_date . "' ";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= "AND  sl.slitting_date <='" . $to_date . "'";
        }
	     if ($post['machine_id'] != '') {
            $machine_id = $post['machine_id'];
            $con .= "AND  sl.machine_id ='" . $machine_id . "'";
        }
         if ($post['shift'] != '') {
            $shift = $post['shift'];
            $con .= "AND  sl.shift ='" . $shift . "'";
        }
        $machine_name=$this->getMachineName($post['machine_id']);
	  
		
		$sql = "SELECT sl.*,om.first_name as operator_name,mm.machine_name FROM slitting as sl, employee as om,machine_master as mm WHERE sl.is_delete = 0 AND  sl.operator_id=om.employee_id AND sl.machine_id = mm.machine_id ".$con;
      //  printr($sql);die;
		$data = $this->query($sql);
	
		$html = '';
		  
		    	
         
			if($data->num_rows){
				
			  	$html.='<div class="panel-body">
						<table cellspacing="0px" cellpadding="1px" border="1" style=" width:100%;" class="table table-striped b-t text-small">
								<tbody >
								<tr  style="font-size: 22px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="12" ><b><center> SLITTING PRODUCTION REPORT</center></b>  </td>
								
								</tr><tr  style="font-size: 22px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="12" ><b>'.$post['shift'].'  Shift </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <b>Machine :- </b> '.$machine_name.'</td>
								</tr>';
							
				if (!empty($post['f_date']) && !empty($post['t_date'])) {
					$html .= "&nbsp;&nbsp;&nbsp;&nbsp;<span><h4>Searching Date From: <b>" . dateFormat(4, $post['f_date']) . "</b> To: <b>" . dateFormat(4, $post['t_date']) . "</b></h4></span><br></br>";
				}
				 
							$html .= '<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;" >
        									<td><b>Sr No</b> </td>
        									<td><b>Slitting Date</b> </td>
        									<td ><b>Job No /Inward no</b> </td>
        									<td ><b>Roll No </b> </td>
        									<td><b>Operator Name</b> </td>
        									<td><b>Input Qty(kgs)</b> </td>
        									<td><b>Output Qty(kgs)</b> </td>
        									<td><b>Output Qty(Roll ) kg </b> </td>
        									<td><b>Total Wastage<br>
        									       <span style="font-size: 10px;">(Setting Wastage+Top cut Wastage+Lamination Wastage+Printing Wastage+Trimming Wastage)</span></b> </td>
        									<td><b>Wastage (%)</b> </td>
        								
							    	</tr>
								<tbody >';
										$i=1;$style='';
									
											$total_input=$total_output=$total_output_m=$total_wastage=$total_wastage_per=0;
    										foreach($data->rows as $slitting_details)
									        	{ 
										            //printr($slitting_details['slitting_status']);
										      //  printr($slitting_details['machine_name']);
                                    					$roll_output=$this->getslitting_roll_details($slitting_details['slitting_id']);		
                                    				//	printr($roll_output);
                                                         if($slitting_details['slitting_status']==0){
                                                			$printing_details = $this->getPrintingDetailsSlitiing($slitting_details['roll_code_id']);
                                                			
                                                			$roll_code=$printing_details['roll_code'];
                                                			$roll_size=$printing_details['roll_size'];
                                                			$job_no=$printing_details['job_name_text'];
                                                			$label='Printing Roll No';
                                                			$th= 'JOB No';
                                                		}else if($slitting_details['slitting_status']==1){
                                                				
                                                			$lamination_details = $this->getLamination_details($slitting_details['roll_code_id']);
                                                			$roll_code=$lamination_details['roll_code'];
                                                			$roll_size=$lamination_details['roll_size'];
                                                			$label='Lamination ROll No';
                                                			$th= 'JOB No';
                                                			$job_no=$lamination_details['job_no'];
                                                		}else{
                                                			$roll = $this->getRoll_details($slitting_details['roll_code_id']);			
                                                			$roll_code= $roll['roll_no'];
                                                			$roll_size= $roll['inward_size'];
                                                			$label= 'Roll No';
                                                			$th= 'Inward  No';
                                                			$job_no=$roll['inward_no'];
                                                		}
                                                		if( $slitting_details['setting_wastage']!=0.000){
                                                				$slitting_details['setting_wastage']=$slitting_details['setting_wastage'].'(kgs)';
                                                			}else{
                                                				$slitting_details['setting_wastage']='-';
                                                			}
                                                			if( $slitting_details['top_cut_wastage']!=0.000){
                                                				$slitting_details['top_cut_wastage']=$slitting_details['top_cut_wastage'].'(kgs)';
                                                			}else{
                                                				$slitting_details['top_cut_wastage']='-';
                                                			}
                                                			if( $slitting_details['lamination_wastage']!=0.000){
                                                				$slitting_details['lamination_wastage']=$slitting_details['lamination_wastage'].'(kgs)';
                                                			}else{
                                                				$slitting_details['lamination_wastage']='-';
                                                			}
                                                			if( $slitting_details['printing_wastage']!=0.000){
                                                				$slitting_details['printing_wastage']=$slitting_details['printing_wastage'].'(kgs)';
                                                			}else{
                                                				$slitting_details['printing_wastage']='-';
                                                			}
                                                			if( $slitting_details['trimming_wastage']!=0.000){
                                                				$slitting_details['trimming_wastage']=$slitting_details['trimming_wastage'].'(kgs)';
                                                			}else{
                                                				$slitting_details['trimming_wastage']='-';
                                                			}
                                                			if( $slitting_details['total_wastage']!=0.000){
                                                				$slitting_details['total_wastage']=$slitting_details['total_wastage'];
                                                			}else{
                                                				$slitting_details['total_wastage']='-';
                                                			
                                                			}
										   
										   	$operator=$this->getJuniorOperator_name($slitting_details['operator_id']);
        								//	printr($operator);
        									$html .= '<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;'.$style.'">
                									<td align="center">'.$i.' </td>
                									<td align="center">'. dateFormat(4, $slitting_details['slitting_date']).' </td>
                									<td align="center;" >'.$job_no.' <br><b>'.$slitting_details['job_name'].'</b></td>
                									<td >'.$roll_code.'</td>
                									<td align="center">'.$operator['first_name'].' '.$operator['last_name'].'</td>
                									<td align="center">'.$slitting_details['input_qty'].' kgs</td>
                									<td align="center">'.$slitting_details['output_qty'].' Kgs </td>';
                									
                									if($slitting_details['slitting_status']==1){
                									  $html .= '<td align="center">'.$roll_output.' kgs</td>';
                									    
                									}else{
                									      $html .= '<td align="center"></td>';
                									}
                									$html .= '	<td align="center">'.$slitting_details['total_wastage'].' kgs</td>
                									<td align="center">'.$slitting_details['wastage'].'(%)</td>
                								
                									
        							    	</tr>';
        							    	$total_input=$total_input+$slitting_details['input_qty'];
        							    	$total_output=$total_output+$slitting_details['output_qty'];
        							    	$total_wastage=$total_wastage+$slitting_details['total_wastage'];
        							    	$total_wastage_per=$total_wastage_per+$slitting_details['wastage'];
										$i++;$b++;
										    
										    
										}
											$html .= '<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;">
                									<td align="center" colspan="5" ><b>Total : </b> </td>
                									<td align="center">'.$total_input.' kgs</td>
                									<td align="center">'.$total_output.' Kgs </td>
                							        	<td align="center"></td>
                									<td align="center">'.$total_wastage.' kgs</td>
                									<td align="center">'.$total_wastage_per.'(%)</td>
                								
                									
        							    	</tr>';
							$html .= "</tbody>
									</table>
								 </div>
							</div>";
					
				
				return $html;
			
		}else{
			return false;
		}
	}
	public function getLamination_details($lamination_id){
		$sql = "SELECT l.*,om.first_name as operator_name,mm.machine_name,jm.job_name as job_num_id FROM  lamination as l,lamination_layer as ll, employee as om,machine_master as mm,job_master as jm WHERE l.is_delete = 0 AND l.lamination_id = '".$lamination_id."' AND l.operator_id=om.employee_id AND l.machine_id = mm.machine_id AND l.job_id=jm.job_id AND l.lamination_id=ll.lamination_id";
	
		$data = $this->query($sql);		
		if ($data->num_rows) {
				return $data->row;
			} else {
				return false;
			}
	}
	public function getPrintingDetailsSlitiing($job_id)
	{
		
		$sql = "SELECT pb.*,om.first_name as operator_name,mm.machine_name,jm.job_name as job_num_id FROM printing_job as pb,employee as om,machine_master as mm,job_master as jm WHERE pb.is_delete = 0 AND pb.job_id = '".$job_id."' AND pb.operator_id=om.employee_id AND pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id ";
		$data = $this->query($sql);	
		//	printr($data);
		if ($data->num_rows) {
					return $data->row;
				} else {
					return false;
				}
		
	}
	
	public function getRoll_details($product_inward_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_inward` WHERE  product_inward_id = '" .(int)$product_inward_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}

	public function viewAdhesive_report($post)
	{
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = "AND pb.job_date >= '" . $f_date . "' ";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= "AND  pb.job_date <='" . $to_date . "'";
        }

		if($post['operator_id']!='')
			$con .= " AND pb.operator_id = '".$post['operator_id']."'";
			
		
	
		
		$sql = "SELECT pb.*,om.first_name as operator_name,mm.machine_name,jm.job_name as job_num_id FROM printing_job as pb, employee as om,machine_master as mm,job_master as jm WHERE pb.is_delete = 0  AND pb.operator_id=om.employee_id AND pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id $con";
		$data = $this->query($sql);
		//printr($data);
		$html = '';
		if($data->num_rows)
		{
			$sql2 = "SELECT * FROM  printing_roll_details as pd, product_inward as pi   WHERE pd.roll_no_id=pi.product_inward_id  AND  pd.job_id = '".$data->row['job_id']."' AND pd.is_delete = '0' ";	
			$roll_data = $this->query($sql2);
			if($roll_data->num_rows){
				
				
				$html .= "<div class='form-group'>
							<div class='table-responsive'>";
							$html .= "<center><h2><span><b>PRINTING JOB REPORT</b></span></h2></center><br>";
				if (!empty($post['f_date']) && !empty($post['t_date'])) {
					$html .= "&nbsp;&nbsp;&nbsp;&nbsp;<span><h4>Searching Date From: <b>" . dateFormat(4, $post['f_date']) . "</b> To: <b>" . dateFormat(4, $post['t_date']) . "</b></h4></span><br></br>";
				}
				
							$html .= "<table class='table b-t text-small table-hover' id='enquiry_report'>
										<thead>
											<tr style='border:groove;'>
													<th style='border:groove;'> Printing Job No.</th>
													<th style='border:groove;'>Date</th>
													<th style='border:groove;'> Job No.</th>
													<th style='border:groove;' colspan='2'>Job Name</th>
													<th style='border:groove;' colspan='2'>Roll Code</th>												
													<th style='border:groove;' colspan='2'>Roll Details</th> 
													<th style='border:groove;'>Plain Waste (Kgs)</th>
													<th style='border:groove;'>Print Waste (Kgs)</th>
													<th style='border:groove;'>Total Waste (Kgs)</th>
													<th style='border:groove;'>Wastage (%)</th>
													<th style='border:groove;'>Operator</th>
											</tr>
										</thead>
										<tbody style='border:groove;'>";
									
										foreach($data->rows as $row)
										{
											$html .= "<tr>
															<td style='border:groove;'>".$row['job_no']."</td>
															<td style='border:groove;'> ".$row['job_name_text']."</td>
															<td style='border:groove;'>".dateFormat(4,$row['job_date'])."</td>
															<td  style='border:groove;' colspan='2'>".$row['job_name']."</td>
															<td  style='border:groove;' colspan='2'><b><h5>".$row['roll_code']."</h5></b></td>
															<td  style='border:groove;'colspan='2'>";
															
															$html.='<table >
														<thead style="border:groove;">
														<th style="border:groove; align="center" >Roll No</th>
														<th style="border:groove; align="center">Material Name</th> 
														<th style="border:groove; align="center">Film Size</th> 												  
														<th style="border:groove; align="center">Input Qty (Kgs)</th>
														<th style="border:groove; align="center">Output Qty (Kgs)</th>												
														<th style="border:groove; align="center">Output Qty (Meter)</th>												
														<th style="border:groove; align="center">Balance Qty (Kgs)</th>									
														</thead><tbody >';
												
														foreach($roll_data->rows as $roll ) {
												
													
															$html.='
															<tr style="border:groove;">	
																	<td   style="border:groove; align="center" >'.$roll['roll_no'].'</td>
																	<td  style="border:groove;  align="center">'. $roll['roll_name_id'].'</td>	
																	<td  style="border:groove; align="center">'. $roll['film_size'].'</td>
																	<td  style="border:groove;  align="center">'. $roll['input_qty'].' </td>
																	<td style="border:groove; align="center">'. $roll['output_qty'].' </td>											
																	<td style="border:groove; align="center">'. $roll['output_qty_m'].' </td>											
																	<td style="border:groove;align="center">'. $roll['balance_qty'].'</td>	
																
															</tr>';	 
													
														}
											 
											
									
											$html.= '</tbody>														
											</table>';	
											$html.="</td>											
															<td style='border:groove;' align='right'>".$row['plain_wastage']."</td>
															<td style='border:groove;' align='right'>".$row['print_wastage']."</td>
															<td style='border:groove;'  align='right'>".$row['total_wastage']."</td>
															<td style='border:groove;' align='right'>".$row['wastage_per']."</td>
															<td style='border:groove;'><span style='color:#F00'><b>".$row['operator_name']."</b></span></td>
													  </tr>";
												
										}
										
							$html .= "</tbody>
									</table>
								 </div>
							</div>";
					
				
				return $html;
			}
		}else{
			return false;
		}
		

	}

	public function getChemistName($user_id)
	{
		$sql = "SELECT e.employee_id,e.user_name FROM employee as e WHERE e.is_delete = '0' AND e.user_type = '6'";
		$data = $this->query($sql);

		//printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	public function viewInwardreport($post)
	{
		//printr($post);
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = " AND sd.inward_date >= '" . $f_date . "'";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= " AND  sd.inward_date <='" . $to_date . "'";
        }

		if($post['operator_id']!='')
			$con .= " AND sd.operator_id = '".$post['operator_id']."'";
			
	 $sql = "SELECT sd.*,CONCAT(vi.vander_first_name,' ',vi.vander_last_name) as vender_name,pi.product_name,um.unit FROM `" . DB_PREFIX . "product_inward` as sd, vendor_info as vi,product_item_info as pi,unit_master as um WHERE  sd.slt_is_delete='0' AND sd.is_delete = 0  AND sd.vender_id = vi.vendor_info_id AND pi.product_item_id = sd.product_item_id AND um.unit_id=sd.unit_id $con";
			$data = $this->query($sql);

		//	printr($data);die;
		//die;
		
			$html='';
				$html .= "<div class='form-group'>
							<div class='table-responsive'>";
							$html .= "<h3><span style='margin-left:200px' ><b>INWARD REPORT</b></span></h3><br>";
				
				
							$html .= "<table class='table b-t text-small table-hover' style='width:50%' >
										
										<tbody style='border:groove;'>";
									$html.='<tr style="border:groove;">';
									$html.='<td style="border:groove;"><center ><h4><b>Inward Details </b></h4></center>';
										if (!empty($post['f_date']) && !empty($post['t_date'])) {
											$html .= '<span class="col-lg-6"  align="left"  ><h5> Date From: <b>' . dateFormat(4, $post['f_date']) . '</h5></b></span>';
											$html .= '<span class="col-lg-6"  align="right" ><h5>To: <b>' . dateFormat(4, $post['t_date']) . '</b></h5></span>';

										}

									$html.='</td>												
											</tr>';
									
									$html.='<tr  style="border:groove;">								
										
										 <td  style="border:groove;">';
										$html.='<table>
													<thead style="border:groove;" >
													<th style="border:groove;">Sr No </th>
													<th style="border:groove;">Inward No </th>													
													<th style="border:groove;">Vender Name</th>
													<th style="border:groove;">Raw material</th>
													<th style="border:groove;">Roll No</th>
													<th style="border:groove;">Qty/Size</th>
																									
											</thead>
										<tbody >';
										$i=1;

										if($data->num_rows){
										//printr($data);

										foreach ($data->rows as $inward) {
										//	printr($inward);die;

									

										$html.='<tr>
												<td  style="border:groove;" align="center">'.$i.'</td>
												<td  style="border:groove;"> <b>'.$inward['inward_no'].'<br>
														<b>'.dateFormat(4, $inward['inward_date']) .'	</td>
												<td  style="border:groove;">'.$inward['vender_name'].'</td>
												<td  style="border:groove;">'.$inward['product_name'].'</td>
												<td  style="border:groove;">'.$inward['roll_no'].'</td>
												<td  style="border:groove;">'.$inward['qty'].' '. $inward['unit'].'/'.$inward['inward_size'].'</td>';
											
											
												
										$i++;	} 	}
										
										$html.= '</tr></tbody>														
										</table>			

										 </td>';

										
										
									$html.= '</tbody>														
										</table>';			
							$html .= "</tbody>
									</table>
								 </div>
							</div>";
					
				
				return $html;

	}
	    
	public function viewInk_report($post)
	{
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = " AND i.date >= '" . $f_date . "'";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= " AND  i.date <='" . $to_date . "'";
        }

		if($post['operator_id']!='')
			$con .= " AND i.operator_id = '".$post['operator_id']."'";
			
	 	 $sql = "SELECT * FROM ink_process as i , job_master as j , employee as om WHERE i.is_delete = 0  AND j.job_id = i.job_id AND om.employee_id	=i.operator_id $con";
			$data = $this->query($sql);
		//die;
		
			$html='';
				$html .= "<div class='form-group'>
							<div class='table-responsive'>";
							$html .= "<h3><span style='margin-left:200px' ><b>INK PROCESS REPORT</b></span></h3><br>";
				
				
							$html .= "<table class='table b-t text-small table-hover' style='width:50%' >
										
										<tbody style='border:groove;'>";
									$html.='<tr style="border:groove;">';
									$html.='<td style="border:groove;"><center ><h4><b>Ink Details </b></h4></center>';
										if (!empty($post['f_date']) && !empty($post['t_date'])) {
											$html .= '<span class="col-lg-6"  align="left"  ><h5> Date From: <b>' . dateFormat(4, $post['f_date']) . '</h5></b></span>';
											$html .= '<span class="col-lg-6"  align="right" ><h5>To: <b>' . dateFormat(4, $post['t_date']) . '</b></h5></span>';

										}

									$html.='</td>												
											</tr>';
									
									$html.='<tr  style="border:groove;">								
										
										 <td  style="border:groove;">';
										$html.='<table>
													<thead style="border:groove;" >
													<th style="border:groove;">Sr No </th>
													<th style="border:groove;">Job Details </th>													
													<th style="border:groove;">Ink Used </th>
																									
											</thead>
										<tbody >';
										$i=1;

										if($data->num_rows){
										//printr($data);

										foreach ($data->rows as $ink) {

											if($ink['shift']== 1){ $ink['shift']='Day'; }else{$ink['shift']='Night';}

											$chemist_name=$this->getChemistName($ink['chemist_id']);
											$ink_process_detail = "SELECT * FROM ink_process_detail as ipd , product_item_info as p  WHERE ipd.ink_p_id='" . $ink['ink_p_id'] . "' AND p.product_item_id=ipd.ink_name  AND ipd.is_delete = '0' ";
           									$ink_process_data = $this->query($ink_process_detail);
											$total = "SELECT *,sum(ink_issue)as total_issue ,sum(ink_use)as total_use,sum(ink_return)as total_return FROM ink_process_detail as ipd , product_item_info as p  WHERE ipd.ink_p_id='" .  $ink['ink_p_id'] . "' AND p.product_item_id=ipd.ink_name  AND ipd.is_delete = '0' ";
											  $total_data = $this->query($total);
									

										$html.='<tr>
												<td  style="border:groove;" align="center">'.$i.'</td>
												<td  style="border:groove;"> <b>Job No  : </b>'.$ink['job_no'].'<br>
														<b>Job Name  : </b>'.$ink['job_name'].'<br>
														<b>Date :  </b>'.dateFormat(4, $ink['date']).'<br> 
														<b>Shift</b> :'.$ink['shift'].'<br>
														<b>Operator Name</b> :'.$ink['first_name'].'<br>
												  		<b>Chemist Name </b> :'.$chemist_name['user_name'].'
												  </td>
											
												<td  style="border:groove;" >';

										$html.='<table >
													<thead style="border:groove;" >
													<th style="border:groove;">INK Name </th>
													<th style="border:groove;">INK Issue </th>
													<th style="border:groove;">INK Used </th>												
													<th style="border:groove;">INK Return </th>												
													</thead>
												<tbody >';
												if(!empty($ink_process_data->num_rows)){
													foreach($ink_process_data->rows as $inkP ) {
											
												
														$html.='
														<tr style="border:groove;">	
																<td  style="border:groove;" align="center">'.$inkP['product_name'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['ink_issue'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['ink_use'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['ink_return'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;">	
																<td colspan="1" style="border:groove;" align="center" ><b>Total</b></td>
																<td  colspan="1"style="border:groove;" align="center" ><b>'. $total_data->row['total_issue'].'</b></td>
																<td  colspan="1" style="border:groove;" align="center"><b>'. $total_data->row['total_use'].'</b></td>
																<td  colspan="1" style="border:groove;" align="center"><b>'. $total_data->row['total_return'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;">	
															<td colspan="5"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table>';			

										
										$html.='</td>';
										$i++;	} 	}
										
										$html.= '</tr></tbody>														
										</table>			

										 </td>';

										
										
									$html.= '</tbody>														
										</table>';			
							$html .= "</tbody>
									</table>
								 </div>
							</div>";
					
				
				return $html;

	}
	

	public function viewMix_Ink_report($post)
	{
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = " AND i.date >= '" . $f_date . "'";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= " AND  i.date <='" . $to_date . "'";
        }

		if($post['operator_id']!='')
			$con .= " AND i.operator_id = '".$post['operator_id']."'";
			
		 $sql_mix_ink = "SELECT * FROM mix_ink_process as i ,job_master as j,employee as om WHERE i.is_delete = 0 AND j.job_id = i.job_id AND  om.employee_id	=i.operator_id $con ";
        	$data_mix_ink = $this->query($sql_mix_ink);
		//die;
		
			$html='';
				$html .= "<div class='form-group'>
							<div class='table-responsive'>";
							$html .= "<h3><span style='margin-left:200px' ><b> MIX INK PROCESS REPORT</b></span></h3><br>";
				
				
							$html .= "<table class='table b-t text-small table-hover' style='width:50%' >
										
										<tbody style='border:groove;'>";
									$html.='<tr style="border:groove;">';
									$html.='<td style="border:groove;"><center ><h4><b> Mix Ink Details </b></h4></center>';
										if (!empty($post['f_date']) && !empty($post['t_date'])) {
											$html .= '<span class="col-lg-6"  align="left"  ><h5> Date From: <b>' . dateFormat(4, $post['f_date']) . '</h5></b></span>';
											$html .= '<span class="col-lg-6"  align="right" ><h5>To: <b>' . dateFormat(4, $post['t_date']) . '</b></h5></span>';

										}

									$html.='</td>
												
											</tr>';
								
									
									$html.='<tr  style="border:groove;">								
										
										 <td  style="border:groove;">';
										$html.='<table>
													<thead style="border:groove;" >
													<th style="border:groove;">Sr No </th>
													<th style="border:groove;">Job Details </th>
													
													<th style="border:groove;">Ink Used </th>
													
												
																									
											</thead>
										<tbody >';
										$i=1;

										if($data_mix_ink->num_rows){
										//printr($data);

										foreach ($data_mix_ink->rows as $ink) {

											if($ink['shift']== 1){ $ink['shift']='Day'; }else{$ink['shift']='Night';}

											$chemist_name=$this->getChemistName($ink['chemist_id']);
											 $mix_ink_process_detail = "SELECT * FROM mix_ink_process_detail as ipd , product_item_info as p WHERE ipd.mix_ink_p_id='" .$ink['mix_ink_p_id']. "' AND p.product_item_id=ipd.ink_name    AND ipd.is_delete = '0' ";
										            $mix_ink_process_data = $this->query($mix_ink_process_detail);
												//	printr($ink_process_data);
													$mix_ink_total = "SELECT *,sum(ink_issue)as total_issue ,sum(ink_use)as total_use,sum(ink_return)as total_return FROM mix_ink_process_detail as ipd , product_item_info as p  WHERE ipd.mix_ink_p_id='" . $ink['mix_ink_p_id'] . "' AND p.product_item_id=ipd.ink_name  AND ipd.is_delete = '0' ";
												 $mix_total_data = $this->query($mix_ink_total);
									
											
										$html.='<tr>
												<td  style="border:groove;" align="center">'.$i.'</td>
												<td  style="border:groove;"> <b>Job No  : </b>'.$ink['job_no'].'<br>
														<b>Job Name  : </b>'.$ink['job_name'].'<br>
														<b>Date :  </b>'.dateFormat(4, $ink['date']).'<br> 
														<b>Shift</b> :'.$ink['shift'].'<br>
														<b>Operator Name</b> :'.$ink['first_name'].'<br>
												  		<b>Chemist Name </b> :'.$chemist_name['user_name'].'
												  </td>
											
												<td  style="border:groove;" >';

										$html.='<table >
													<thead style="border:groove;" >
													<th style="border:groove;">INK Name </th>
													<th style="border:groove;">INK Issue </th>
													<th style="border:groove;">INK Used </th>												
													<th style="border:groove;">INK Return </th>												
													</thead>
												<tbody >';
												if(!empty($mix_ink_process_data->num_rows)){
													foreach($mix_ink_process_data->rows as $inkP ) {
											
												
														$html.='
														<tr style="border:groove;">	
																<td  style="border:groove;" align="center">'.$inkP['product_name'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['ink_issue'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['ink_use'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['ink_return'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;">	
																<td colspan="1" style="border:groove;" align="center"><b>Total</b></td>
																<td  colspan="1"style="border:groove;"align="center"><b>'. $mix_total_data->row['total_issue'].'</b></td>
																<td  colspan="1" style="border:groove;"align="center" ><b>'. $mix_total_data->row['total_use'].'</b></td>
																<td  colspan="1" style="border:groove;"align="center" ><b>'. $mix_total_data->row['total_return'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;">	
															<td colspan="5"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table>';			

										
										$html.='</td>';
										$i++;	} 	}
										
										$html.= '</tr></tbody>														
										</table>			

										 </td>';
										
									$html.= '</tbody>														
										</table>';			
							$html .= "</tbody>
									</table>
								 </div>
							</div>";
					
				
				return $html;
	}
	
	public function viewSolvent_Ink_report($post)
	{
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = " AND i.date >= '" . $f_date . "'";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= " AND  i.date <='" . $to_date . "'";
        }

		if($post['operator_id']!='')
			$con .= " AND i.operator_id = '".$post['operator_id']."'";
			
		
		//die;

        	 $sql = "SELECT * FROM solvent_ink_process as i ,job_master as j, employee as om  WHERE i.is_delete = 0 AND j.job_id = i.job_id AND   om.employee_id	=i.operator_id $con ";
       			 $data = $this->query($sql);
		
			$html='';
				$html .= "<div class='form-group'>
							<div class='table-responsive'>";
							$html .= "<h3><span style='margin-left:200px' ><b> SOLVENT INK PROCESS REPORT</b></span></h3><br>";
				
				
							$html .= "<table class='table b-t text-small table-hover' style='width:50%' >
										
										<tbody style='border:groove;'>";
									$html.='<tr style="border:groove;">';
									$html.='<td style="border:groove;"><center ><h4><b> Solvent Ink Details </b></h4></center>';
										if (!empty($post['f_date']) && !empty($post['t_date'])) {
											$html .= '<span class="col-lg-6"  align="left"  ><h5> <b> Date From: ' . dateFormat(4, $post['f_date']) . '</h5></b></span>';
											$html .= '<span class="col-lg-6"  align="right" ><h5><b> To: ' . dateFormat(4, $post['t_date']) . '</b></h5></span>';

										}

									$html.='</td>
												
											</tr>';
								
									
									$html.='<tr  style="border:groove;">								
										
										 <td  style="border:groove;">';
										$html.='<table>
													<thead style="border:groove;" >
													<th style="border:groove;">Sr No </th>
													<th style="border:groove;">Job Details </th>
													
													<th style="border:groove;">Ink Used </th>
													
												
																									
											</thead>
										<tbody >';
										$i=1;

										if($data->num_rows){
										//printr($data);

										foreach ($data->rows as $ink) {

											if($ink['shift']== 1){ $ink['shift']='Day'; }else{$ink['shift']='Night';}

											$chemist_name=$this->getChemistName($ink['chemist_id']);
										
												 $ink_process_detail = "SELECT * FROM solvent_ink_process_detail as ipd,product_item_info as p  WHERE ipd.solvent_ink_p_id='" .$ink['solvent_ink_p_id']. "' AND p.product_item_id=ipd.solvent_ink_name  AND ipd.is_delete = '0' ";
									            $ink_process_data = $this->query($ink_process_detail);
											//	printr( $ink_process_data);//die;
												
												
												$total = "SELECT *,sum(solvent_ink_issue)as total_issue ,sum(solvent_ink_use)as total_use,sum(solvent_ink_return)as total_return FROM solvent_ink_process_detail as ipd , product_item_info as p  WHERE ipd.solvent_ink_p_id='" . $ink['solvent_ink_p_id'] . "' AND p.product_item_id=ipd.solvent_ink_name  AND ipd.is_delete = '0' ";
												 $total_data = $this->query($total);
											
										$html.='<tr>
												<td  style="border:groove;" align="center">'.$i.'</td>
												<td  style="border:groove;"> <b>Job No  : </b>'.$ink['job_no'].'<br>
														<b>Job Name  : </b>'.$ink['job_name'].'<br>
														<b>Date :  </b>'.dateFormat(4, $ink['date']).'<br> 
														<b>Shift</b> :'.$ink['shift'].'<br>
														<b>Operator Name</b> :'.$ink['first_name'].'<br>
												  		<b>Chemist Name </b> :'.$chemist_name['user_name'].'
												  </td>
											
												<td  style="border:groove;" >';

										$html.='<table >
													<thead style="border:groove;" >
													<th style="border:groove;">INK Name </th>
													<th style="border:groove;">INK Issue </th>
													<th style="border:groove;">INK Used </th>												
													<th style="border:groove;">INK Return </th>												
													</thead>
												<tbody >';
												if(!empty($ink_process_data->num_rows)){
													foreach($ink_process_data->rows as $inkP ) {
											
												
														$html.='
														<tr style="border:groove;">	
																<td  style="border:groove;" align="center">'.$inkP['product_name'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['solvent_ink_issue'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['solvent_ink_use'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['solvent_ink_return'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;">	
																<td colspan="1" style="border:groove;" align="center"><b>Total</b></td>
																<td  colspan="1"style="border:groove;"align="center"><b>'. $total_data->row['total_issue'].'</b></td>
																<td  colspan="1" style="border:groove;"align="center" ><b>'. $total_data->row['total_use'].'</b></td>
																<td  colspan="1" style="border:groove;"align="center" ><b>'. $total_data->row['total_return'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;">	
															<td colspan="5"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table>';			

										
										$html.='</td>';
										$i++;	} 	}
										
										$html.= '</tr></tbody>														
										</table>			

										 </td>';
										
									$html.= '</tbody>														
										</table>';			
							$html .= "</tbody>
									</table>
								 </div>
							</div>";
					
				
				return $html;
	}
	public function viewMix_Solvent_Ink_report($post)
	{
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = " AND i.date >= '" . $f_date . "'";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= " AND  i.date <='" . $to_date . "'";
        }

		if($post['operator_id']!='')
			$con .= " AND i.operator_id = '".$post['operator_id']."'";
			
		
		//die;

        	 $sql = "SELECT * FROM mix_solvent_process as i ,job_master as j, employee as om  WHERE i.is_delete = 0 AND j.job_id = i.job_id AND   om.employee_id	=i.operator_id $con ";
       			 $data = $this->query($sql);
		

       		

			$html='';
				$html .= "<div class='form-group'>
							<div class='table-responsive'>";
							$html .= "<h3><span style='margin-left:200px' ><b> SOLVENT INK PROCESS REPORT</b></span></h3><br>";
				
				
							$html .= "<table class='table b-t text-small table-hover' style='width:50%' >
										
										<tbody style='border:groove;'>";
									$html.='<tr style="border:groove;">';
									$html.='<td style="border:groove;"><center ><h4><b> Solvent Ink Details </b></h4></center>';
										if (!empty($post['f_date']) && !empty($post['t_date'])) {
											$html .= '<span class="col-lg-6"  align="left"  ><h5> <b> Date From: ' . dateFormat(4, $post['f_date']) . '</h5></b></span>';
											$html .= '<span class="col-lg-6"  align="right" ><h5><b> To: ' . dateFormat(4, $post['t_date']) . '</b></h5></span>';

										}

									$html.='</td>
												
											</tr>';
								
									
									$html.='<tr  style="border:groove;">								
										
										 <td  style="border:groove;">';
										$html.='<table>
													<thead style="border:groove;" >
													<th style="border:groove;">Sr No </th>
													<th style="border:groove;">Job Details </th>
													
													<th style="border:groove;">Ink Used </th>
													
												
																									
											</thead>
										<tbody >';
										$i=1;

										if($data->num_rows){
										//printr($data);

										foreach ($data->rows as $ink) {

											if($ink['shift']== 1){ $ink['shift']='Day'; }else{$ink['shift']='Night';}

											$chemist_name=$this->getChemistName($ink['chemist_id']);
										
												$ink_process_detail = "SELECT * FROM mix_solvent_process_detail as ipd,product_item_info as p   WHERE ipd.mix_solvent_p_id='" .$ink['mix_solvent_p_id']. "' AND ipd.is_delete = '0' AND p.product_item_id=ipd.mix_solvent_name   ";
									            $ink_process_data = $this->query($ink_process_detail);
											
												$total = "SELECT *,sum(mix_solvent_issue)as total_issue ,sum(mix_solvent_use)as total_use,sum(mix_solvent_return)as total_return FROM mix_solvent_process_detail as ipd , product_item_info as p  WHERE ipd.mix_solvent_p_id='" . $ink['mix_solvent_p_id'] . "' AND p.product_item_id=ipd.mix_solvent_name  AND ipd.is_delete = '0' ";
												 $total_data = $this->query($total);
											
										$html.='<tr>
												<td  style="border:groove;" align="center">'.$i.'</td>
												<td  style="border:groove;"> <b>Job No  : </b>'.$ink['job_no'].'<br>
														<b>Job Name  : </b>'.$ink['job_name'].'<br>
														<b>Date :  </b>'.dateFormat(4, $ink['date']).'<br> 
														<b>Shift</b> :'.$ink['shift'].'<br>
														<b>Operator Name</b> :'.$ink['first_name'].'<br>
												  		<b>Chemist Name </b> :'.$chemist_name['user_name'].'
												  </td>
											
												<td  style="border:groove;" >';

										$html.='<table >
													<thead style="border:groove;" >
													<th style="border:groove;">INK Name </th>
													<th style="border:groove;">INK Issue </th>
													<th style="border:groove;">INK Used </th>												
													<th style="border:groove;">INK Return </th>												
													</thead>
												<tbody >';
												if(!empty($ink_process_data->num_rows)){
													foreach($ink_process_data->rows as $inkP ) {
											
												//printr($inkP);
														$html.='
														<tr style="border:groove;">	
																<td  style="border:groove;" align="center">'.$inkP['product_name'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['mix_solvent_issue'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['mix_solvent_issue'].'</td>
																<td  style="border:groove;" align="center">'.$inkP['mix_solvent_return'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;">	
																<td colspan="1" style="border:groove;" align="center"><b>Total</b></td>
																<td  colspan="1"style="border:groove;"align="center"><b>'. $total_data->row['total_issue'].'</b></td>
																<td  colspan="1" style="border:groove;"align="center" ><b>'. $total_data->row['total_use'].'</b></td>
																<td  colspan="1" style="border:groove;"align="center" ><b>'. $total_data->row['total_return'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;">	
															<td colspan="5"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table>';			

										
										$html.='</td>';
										$i++;	} 	}
										
										$html.= '</tr></tbody>														
										</table>			

										 </td>';
										
									$html.= '</tbody>														
										</table>';			
							$html .= "</tbody>
									</table>
								 </div>
							</div>";
					
				
				return $html;
	}


public function AdhesiveDetails($adhesive_id,$status)
		 {

				$sql = "SELECT * FROM adhesive_material_details as ad   ,product_item_info as p  WHERE ad.is_delete = 0  AND ad.m_status='".$status."' AND ad.adhesive_id='" . $adhesive_id . "' AND p.product_item_id=ad.product_item_id";
				$data = $this->query($sql);
				if ($data->row) {
					return $data->rows;
				} else {
					return false;
				}
			}
	public function getJuniorOperator_name($id)
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.employee_id='".$id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}		

	public function AdhesiveArrayForCSV($post)
	{	
		
		$str='';
		if ($post['f_date'] != '') {
            $f_date = $post['f_date'];
            $con = " AND a.date >= '" . $f_date . "'";
        }
        if ($post['t_date'] != '') {
            $to_date = $post['t_date'];
            $con .= " AND  a.date <='" . $to_date . "'";
        }

		if($post['operator_id']!='')
			$con .= " AND a.operator_id = '".$post['operator_id']."'";



		$i=0;
		$html='';
			$html.='<div class="table-responsive">';
						$html .= "<h3><span><center> <b> ADHESIVE ,HARDNER & ETHYLE USAGE REPORT</b></center></span></h3><br>";
						$html.='<table class="table b-t text-small table-hover" style="border:groove;">';
						 $html.=' <thead style="border:groove;">';
						 $html.='<tr style="border:groove;"> ';                    			 
							$html.='<th style="border:groove;" >Sr No </th>';
							$html.='<th style="border:groove;" >Details</th>';						
							  $html.='<th style="border:groove;" >Adhesive Details(Kgs) </th>';
							   $html.='<th style="border:groove;" >Hardner Details (Kgs) </th>';
								$html.='<th style="border:groove;" >Ethyle Details(Kgs)</th>';							 
							  $html.='</tr>';
							  $html.='</thead>';
					
				
					 $sql = "SELECT * FROM adhesive_details as a, employee as e , machine_master as m   WHERE a.is_delete = 0 AND e.employee_id=a.operator_id AND a.machine_id=m.machine_id $con ";
     				   $data = $this->query($sql);

     				 //  printr($data);die;
        			if ($data->rows) {
						$i=1;
					foreach ($data->rows as $adhesive_details_all) {
					//	printr($adhesive_details_all['adhesive_id']);
							$adhesive_details= $this->AdhesiveDetails($adhesive_details_all['adhesive_id'],0);
							$hardner_details= $this->AdhesiveDetails($adhesive_details_all['adhesive_id'],1);
							$ethyle_details= $this->AdhesiveDetails($adhesive_details_all['adhesive_id'],2);
				
							if(!empty($adhesive_details_all)){
								
										$html.='<tbody style="border:groove;">';
									   $html.='<tr style="border:groove;">';
										$html.='<td style="border:groove;align="top">'. $i.'</td>';                         
										$html.='<td style="border:groove;align="top"><b>Operator Name : </b>'. ucwords($adhesive_details_all['user_name']).'
												<br><b> Shift :</b> '.$adhesive_details_all['shift'].'<br><b> Date  :</b> '.dateFormat(4,$adhesive_details_all['date']).'
										</td>';                         
										                         
										                        
										
										$html.='<td style="border:groove;" >';
										$html.='<table class="table b-t text-small table-hover"  id="lamination_report" >
													<thead style="border:groove;" id="data">
													<th style="border:groove;">Name </th>												
													<th style="border:groove;">Used </th>												
													</thead>
												<tbody >';
												if(!empty($adhesive_details)){
													foreach($adhesive_details as $ad ) {
											
												
														$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;">'.$ad['product_name'].'</td>										
																<td  style="border:groove;">'.$ad['used'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;"><b>Total</b></td>
								
																<td  style="border:groove;"><b>'.$adhesive_details_all['adhesive_used'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;" id="data">	
															<td colspan="2"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table>';							  
										$html.='</td>';
										$html.='<td style="border:groove;">';
										$html.='<table class="table b-t text-small table-hover"  id="lamination_report" >
													<thead style="border:groove;" id="data">
													<th  style="border:groove;">Name </th>												
													<th style="border:groove;"> Used </th>													
													</thead><tbody >';
												if(!empty($hardner_details)){
													foreach($hardner_details as $har ) {
											
												
														$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;">'.$har['product_name'].'</td>											
																<td  style="border:groove;">'.$har['used'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;"><b>Total</b></td>																
																<td  style="border:groove;"><b>'.$adhesive_details_all['hardner_used'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;" id="data">	
															<td colspan="2"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
								
										$html.= '</tbody>														
										</table>';							  
										$html.='</td>';
										$html.='<td style="border:groove;" >';
										 	$html.='<table  class="table b-t text-small table-hover" id="lamination_report" >
													<thead style="border:groove;" id="data">
													<th  style="border:groove;">Name </th>												
													<th style="border:groove;"> Used </th>												
													</thead>
												<tbody >';
												if(!empty($ethyle_details)){
													foreach($ethyle_details as $eth ) {
											
												
														$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;">'.$eth['product_name'].'</td>											
																<td  style="border:groove;">'.$eth['used'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;"><b>Total</b></td>													
																<td  style="border:groove;"><b>'.$adhesive_details_all['ethyle_used'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;" id="data">	
															<td colspan="2"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table>';				  
										$html.='</td>';
										
									
									   
									$html.='</tr>';
									
							  } else{ 
								  $html.='<tr><td colspan="5">No record found !</td></tr>';
							  }
						 
							 $html.=' </tbody>';
						 
					$i++;}

				}
					  $html.=' </table>';
			 $html.=' </div>';
		  return $html;
		//return $input_array;
	}
	
public function getslitting_roll_details($slitting_id)
	{
		
		$sql = "SELECT 	SUM(output_qty) as roll_output  FROM slitting_process  WHERE  is_delete=0 AND  slitting_id = '".$slitting_id."' ";
		$data = $this->query($sql);
    //	echo $sql;
		if($data->num_rows){
			return $data->row['roll_output'];
		}else{
			return false;
		}
		

	}
	 public function zipper_name($product_item_id) {
		
   
	   $sql = "SELECT * FROM product_item_info WHERE  product_item_id='".$product_item_id."' ";
	   $data = $this->query($sql); 
	     if ($data->num_rows) {
            return $data->row['product_name'];
        } else {
            return false;
        }
		
	
		
	 //die;
    }
    public function input_roll_details_view($pouching_id) {

     	$sql = "SELECT SUm(p.roll_output_qty) as input_qty,GROUP_CONCAT(s.roll_code) as roll_code FROM pouching_roll_detail as p,slitting_process as s WHERE p.is_delete=0 AND s.slitting_material_id=p.roll_no AND p.pouching_id = '".$pouching_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false; 
		}
	
    }
	
}
?>
