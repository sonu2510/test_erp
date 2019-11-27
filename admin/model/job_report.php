<?php
//add by sonu
class job_report extends dbclass{
	public function job_detail($job_name)
	{	
		$sql="SELECT * FROM  job_master WHERE job_no LIKE '%".strtolower($job_name)."%' AND is_delete=0 AND pouching_status=1 AND status = 1";
		$sql_printing="SELECT * FROM  printing_job WHERE job_name_text LIKE '%".strtolower($job_name)."%' AND is_delete=0 AND  status = 1 ";
		//echo $sql;
		$data = $this->query($sql);
		$data_printing = $this->query($sql_printing);
		
	//	printr($data_printing);die;
		$job_array_1=$job_array_2=array();
		if($data->num_rows){
				foreach($data->rows as $data){
				$job_array[]=array(
					'job_no'=>$data['job_no'], 
					'job_id'=>$data['job_id'],
					'job_name'=>$data['job_name']
					
				);
			}	
		}
		if($data_printing->num_rows){
					foreach($data_printing->rows as $data){
					$job_array[]=array(
						'job_no'=>$data['job_name_text'],
						'job_id'=>$data['job_name_id'],
						'job_name'=>$data['job_name']
						
					);
				}
		}
		
	//	$job_array=array_unique($job_array_1,$job_array_2);
	//    printr($job_array);
		return $job_array;
		
		
	}

	public function getPrintingDetails($job_id){

		
		$sql = "SELECT pb.*,mm.machine_name,jm.job_name as job_num_id FROM printing_job as pb, machine_master as mm,job_master as jm WHERE pb.is_delete = 0 AND pb.job_name_id = '".$job_id."' AND pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id";
		
		$data = $this->query($sql);
	//	printr($data);
		//return $data->row;
		
		$sql_operator = "SELECT pb.*,po.*, om.first_name  FROM printing_job as pb,employee as om,  printing_operator_details as po
		WHERE  po.operator_id=om.employee_id  AND  po.is_delete = 0 AND pb.job_id=po.printing_id AND po.printing_id='".$data->row['job_id']."' ";
		
		$printing_operator= $this->query($sql_operator);




			$printing_operator_array=array();
				foreach($printing_operator->rows as $operator){
				//printr($operator);
					
				$printing_operator_array[] = array(
		                    'printing_operator_id' =>$operator['printing_operator_id'],
		                    'printing_id' => $operator['printing_id'],
		                    'operator_id' => $operator['operator_id'],
		                    'junior_id' => $operator['junior_id'],
		                    'printing_date' => $operator['printing_date'],
		                    'operator_shift' => $operator['operator_shift'],
							'plain_wastage' => $operator['plain_wastage'],
							'print_wastage' => $operator['print_wastage'],
							'total_wastage' => $operator['total_wastage'],
							'wastage_per' => $operator['wastage_per'],
							'operator_name' => $operator['first_name'],
							
		                   
		                );
				}
			


		$sql2 = "SELECT * FROM  printing_roll_details as pd, product_inward as pi ,  printing_operator_details as po WHERE po.is_delete = 0 AND po.printing_operator_id=pd.printing_operator_id AND  pd.roll_no_id=pi.product_inward_id  AND    pd.job_id = '".$data->row['job_id']."' AND pd.is_delete = '0'";	
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
				'chemist_id' => $data->row['chemist_id'],
				'machine_id' => $data->row['machine_id'],
				'shift' => $data->row['shift'],	
				'remark' => $data->row['remark'],
				'remaks_printing_job' => $data->row['remaks_printing_job'],
				'machine_name' => $data->row['machine_name'],
                'job_num_id' => $data->row['job_num_id'],    
				'roll_used' => $data->row['roll_used'],				
                'roll_details' => $roll_details,
                'printing_operator_array' => $printing_operator_array,

               
            );

	//	printr($printing_array);
		return $printing_array;
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
	public function getLaminationDetails($job_id){
 

		$sql = "SELECT l.*,mm.machine_name,jm.job_name as job_num_id FROM  lamination as l,lamination_layer as ll,machine_master as mm,job_master as jm WHERE l.is_delete = 0 AND l.job_id = '".$job_id."' AND  l.machine_id = mm.machine_id AND l.job_id=jm.job_id AND l.lamination_id=ll.lamination_id";
		
		$data = $this->query($sql);		
		$lamination_array =array();
		if($data->num_rows){	
					$sql2 = "SELECT * FROM  lamination_layer  WHERE  lamination_id = '".$data->row['lamination_id']."' AND is_delete = '0'";	
					$layer_data = $this->query($sql2);
				
					$sql2 = "SELECT * FROM  lamination_roll_detail  WHERE  lamination_id = '".$data->row['lamination_id']."' AND is_delete = '0'";	
					$roll_data = $this->query($sql2);
					
					$roll_array=array();
					foreach($roll_data->rows as $layer){
						
					$roll_array[] = array(
			                    'lamination_roll_detail_id' =>$layer['lamination_roll_detail_id'],
			                    'lamination_layer_id' =>$layer['lamination_layer_id'],
			                    'lamination_id' =>$layer['lamination_id'],				
			                    'roll_name_id' => $layer['roll_name_id'],
			                    'roll_no_id' => $layer['roll_no_id'],
			                    'film_size' => $layer['film_size'],
			                    'input_qty' =>$layer['input_qty'],
			                    'output_qty' => $layer['output_qty'],                  
			                    'balance_qty' => $layer['balance_qty']
			                   
			                );
					}
				
					
			            $lamination_array = array(
			                'lamination_id' => $data->row['lamination_id'],
							'lamination_no' => $data->row['lamination_no'],
			                'lamination_date' => $data->row['lamination_date'],
			                'job_name' => $data->row['job_name'],                
			                'job_no' => $data->row['job_no'],                
			                'start_time' => $data->row['start_time'],                
			                'end_time' => $data->row['end_time'],                
			                'job_id' => $data->row['job_id'],                
			                'operator_id' => $data->row['operator_id'],
			                'machine_id' => $data->row['machine_id'],				
			                'shift' => $data->row['shift'],
			                'added_user_id' => $data->row['added_user_id'],
			                'added_user_type_id' => $data->row['added_user_type_id'],
			                'status' => $data->row['status'],
			                'date_added' => $data->row['date_added'],
			                'date_modify' => $data->row['date_modify'],
			                'is_delete' => $data->row['is_delete'],
			            
			                'machine_name' => $data->row['machine_name'],               
			                'roll_code' => '',
							'pass_no' => $data->row['pass_no'],	
							'remark'=>$data->row['remark'],
							'remark_lamination'=>$data->row['remark_lamination'],
			                'roll_array' => $roll_array,
			                'layer_array' => $layer_data->row,
			               
			            );
			    }
		//printr($lamination_array);
		return $lamination_array;
	

	}


	public function getSlittingDetails($job_id){


		$sql = "SELECT sl.*,om.first_name as operator_name,mm.machine_name FROM slitting as sl, employee as om,machine_master as mm WHERE sl.is_delete = 0 AND sl.job_id = '".$job_id."' AND sl.operator_id=om.employee_id AND sl.machine_id = mm.machine_id ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
		

	}

	public function getSlittingRollDetails($job_id)
	{
		
		$sql = "SELECT * FROM slitting_process  WHERE  is_delete=0 AND  job_id = '".$job_id."' ";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		

	}
	public function getLayerDetails($lamination_id) {
        $sql ="SELECT * FROM   lamination_layer  WHERE is_delete = '0' AND lamination_id='".$lamination_id."' ";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }
    public function getRollDetails_printing($printing_operator_id)
	{
		
		$sql = "SELECT * FROM  printing_roll_details as pd, product_inward as pi   WHERE pd.roll_no_id=pi.product_inward_id  AND  pd.printing_operator_id = '".$printing_operator_id."' AND pd.is_delete = '0' GROUP by pd.printing_roll_id ";	
	//	echo $sql;die;
	    $data=$this->query($sql);
	    $total_input=0;
	    $total_output=0;
        $total_balance=0;
	    $roll_array=array();
	// printr($data);
	    if($data->num_rows){
	    	 foreach($data->rows as $r ) {	
	    	//     printr($r);
											 
				  $roll_no[]='<b>'.$r['roll_no'].' </b>('.$r['roll_name_id'].') ';
				  $total_output=$total_output+$r['output_qty'];
				  $total_input=$total_input+$r['input_qty'];
				  $total_balance=$total_balance+$r['balance_qty'];
				
				}

			$uni_arr = array_unique($roll_no);
			$u_arr = implode(',',$uni_arr);

	//		printr($total_output.'  '.$total_input);

 			$roll_array=array(	'total_input'=>$total_input,
 								'total_output'=>$total_output,
 								'total_balance'=>$total_balance,
 								'roll_no'=>$u_arr,


 			);

			return $roll_array;
		}else{
			return false;
		}
	}
    public function getRollDetails($lamination_id,$lamination_layer_id) {
        $sql ="SELECT * FROM   lamination_roll_detail  WHERE is_delete = '0' AND lamination_id='".$lamination_id."'AND lamination_layer_id='".$lamination_layer_id."' ";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }
    public function getPrintingRollDetails($lamination_layer_id) {
        $sql ="SELECT * FROM   lamination_roll_detail  as r,printing_job as p  WHERE r.is_delete = '0' AND  r.lamination_layer_id='".$lamination_layer_id."' AND r.roll_no_id=p.job_id";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['roll_code'];
        } else {
            return false;
        }
    }
    public function get_roll_no($product_inward_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_inward`WHERE  slt_is_delete='0' AND is_delete = 0 AND status=1 AND product_inward_id='".$product_inward_id."' ";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['roll_no'];
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

	public function getPouchDetail($job_id)
	{
		
		$sql = "SELECT sl.*,om.first_name as operator_name,mm.machine_name FROM pouching as sl, employee as om,machine_master as mm WHERE sl.is_delete = 0 AND sl.job_id LIKE '%".$job_id."%' AND sl.operator_id=om.employee_id AND sl.machine_id = mm.machine_id";
	
		$data = $this->query($sql);
	//	echo  $sql;
	//	printr($data);
		
		return $data->rows;
		
	//printr($slitting_array);
	
	}

	public function input_roll_details($job_id,$pouching_id) {
		
     if(!empty($job_id)){
	   $sql = "SELECT *,sp.output_qty as o_qty FROM  slitting_process as sp,slitting as s WHERE  s.slitting_status = '1' AND sp.pouching_id='".$pouching_id."' AND s.slitting_id=sp.slitting_id AND s.is_delete='0' AND sp.is_delete='0' ";
	   $data = $this->query($sql);
	//	printr($data);
	//	echo $sql;
		$sql_lami = "SELECT * FROM `" . DB_PREFIX . "lamination` WHERE is_delete = 0 AND status=1 AND job_id IN (" .implode(",",$job_id). ")";
	
		$data_l = $this->query($sql_lami);
	//	printr($data_l);
		$comb_arr=array();
		$comb_arr['slitting']=$data->rows;
		$comb_arr['lamination']=$data_l->rows;
		
		return $comb_arr;
	 }		
		
	}

	public function getLayerMakeMaterialDetails($job_id){
		$sql ="SELECT * FROM  job_layer_details as j ,product_item_info as p WHERE j.job_id = '".$job_id."' AND p.product_item_id =j.product_item_layer_id";
		$data = $this->query($sql);
			//printr($data);
			if ($data->num_rows) {
				return $data->rows;
			} else {
				return false;
			}
	}
	public function getjob_report($job_id){
		//printr($job_id);

		$printing_details=$this->getPrintingDetails($job_id);
		
		$lamination_details=$this->getLaminationDetails($job_id);
		if(!empty($lamination_details)){
		$layer_details= $this->getLayerDetails($lamination_details['lamination_id']);
		}
		$slitting_details=$this->getSlittingDetails($job_id);

		$slitting_roll_details=$this->getSlittingRollDetails($job_id);

		$pouching_details= $this->getPouchDetail($job_id);
	
		$total_ink=$total_mix_ink=$total_solvent=$total_mix_solvent=0;		
		$total_ink=$this->gettotal_ink($job_id);		
		$total_mix_ink=$this->gettotal_mix_ink($job_id);
		$total_solvent=$this->gettotal_solvent($job_id);
		$total_mix_solvent=$this->gettotal_mix_solvent($job_id);
		$country_name='';
	//	printr($total_ink);
	//	printr($total_mix_ink);
	//	printr($total_solvent);
	//	printr($total_mix_solvent);
		$job_name=$this->getJObName($job_id);
		$job_material=$this->getLayerMakeMaterialDetails($job_id);
  //printr($job_name['gusset']);
		if($job_name['country_id']!='0'){ 
		

		$country=$this->getCountryName($job_name['country_id']);
	//	printr($country);
		if($country!=''){ $country_name='<b> Country Name :-</b>  '.$country['country_name'].'';
		}else{$country_name	='';	}
		}

        	$size = $this->getSize($job_name['size_pro']);
    		
    	//	printr($job_details);
    	//	printr($job_material);
    		//printr($size);
            $width=$height=$gusset=0;
                	if(!empty($size)){
                	        $volume=''.$size['volume'].'-';
                	        $width  =$size['width'];
                	        $height=$size['height'];
                	        $gusset=$size['gusset'];
            	    }else{
            	        $volume="";
            	          $width  =$job_name['width'];
            	          $height=$job_name['height'];
            	          $gusset=$job_name['gusset'];
            	    }  


		$html='';


	$html='	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div class="panel-body font_medium" id="print_div" style="font-size: 20px;" >';
	        // $html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;"><b><h4> JOB REPORT</h4></b>'; 
	         $html .='<div  style="font-size: 14px;"><div style="text-align:center;"><b>JOB REPORT </b>';
			$html.='</div>

			<div class="div_first" style=" width: 100%;float: left; font-size: 10px;">
							<table class="table" style="font-size: 10px;">
							<tbody>
							<tr>
						
							<td style="vertical-align: top;width: 50%;"><div align="center">
								<b>Job No :</b>'.ucwords($job_name['job_no']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								 '.$country_name.'  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Pouch :</b>'.$job_name['product_name'].'&nbsp;&nbsp;&nbsp;&nbsp; '.$volume.'&nbsp;&nbsp; ('.$width.'x'.$height.'x'.$gusset.')</div>
							</td>
							<td style="vertical-align: top;width: 50%;">
							<div align="center"><b> Job Name :-</b> '.ucwords($job_name['job_name']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <b>Product Name :-</b> '.ucwords($job_name['product_name']).'</div>	
							</td>
							</tr>
							</tbody>
							</table><table class="table"  style="font-size: 10px;">
							<tbody>
							<tr>';
							if(!empty($job_material)){
							    foreach($job_material as $job_material){
							        $html.='<td><div align="center">
								<b> Layer : '.$job_material['layer_id'].' => '.$job_material['product_name'].'</b> </div>
							</td>';
							    }
							}
						
						$html.='
						
							</tr>
							</tbody>
							</table></div></div>
	                     ';

//Printing Details

if(!empty($printing_details)){

          
               $html .='<div  style="font-size: 10px;"><div style="text-align:center;"><b>PRINTING  DETAILS</b>';
				$html.='</div>

						<div class="div_first" style=" width: 100%;float: left;  font-size: 10px;">
						';
						$html.='	<table class="table" >
							<tbody>
							<tr>
							<td style="vertical-align: top;width: 50%;">
							 	<b>Printing No : </b>  '.ucwords($printing_details['job_no']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b> Date : </b>  '.dateFormat(4,$printing_details['job_date']).'

							</td>
							<td style="vertical-align: top;width: 50%;">
								<b>Start Time :</b>   '.ucwords($printing_details['start_time']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b>End Time :</b>   '.ucwords($printing_details['end_time']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b>Shift :</b>   '.ucwords($printing_details['shift']).'<br>
								
							</td>
							</tr>
							</tbody>
							</table>';
							$html .='<div class="div_first" style="width: 100%; float: left;  ">';

					$html.='	<table class="table" style="font-size: 8px;">
								<tbody>
								<tr>
								 
								
									<td width="20%"><div align="center"><b>Operator Details</b></div></td>	
									<td width="5%"><div align="center"><b>Date</b></div></td>								
									<td width="20%"><div align="center"><b>Roll No</b>	</div></td>
									<td width="5%"><div align="center"><b>Total I/P(Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Total O/P(Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Balance Roll(Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Print Waste (Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Total Waste (Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Waste (%)</b></div></td>
									</tr>
									</tbody>';
									$html.='<tbody>';
										
										$total_input=$total_output=$plain_wastage=$print_wastage=$total_wastage=$wastage_per=0;
							            foreach($printing_details['printing_operator_array'] as $details ) {
        							            	$roll_details=$this->getRollDetails_printing($details['printing_operator_id']);	
        							            		$junior_operator=$this->getJuniorOperator_name($details['junior_id']);
        								           		$junior_name='';
        								           		if(!empty($junior_operator)){
        								           			$junior_name='<b>Junior </b> : '.$junior_operator['first_name'].' '. $junior_operator['last_name'];
        
        								           		}
        								             $html.='<tr><td width="20%"><div align="center"><b>Shift :- </b>'. $details['operator_shift'].'<br><b>Main : </b>'.$details['operator_name'] .'<br>'.$junior_name.' <br>	</div>
        
        
        								              </td>';
        									          $html.='<td width="5%"><div align="center"><span style="color:#f92c09" ><b> Date : </b>  '.dateFormat(4,$details['printing_date']).'</span></div></td>	';									
        										   /*   $html.='<td width="40%"><div align="center">';
        								    	$html.='
        											<table cellspacing="0px" cellpadding="10px"  style="width: 100%; border-spacing: 0px;font-size: 8px;">
        											<tbody>';*/
        
        											$sql = "SELECT * FROM  printing_roll_details as pd, product_inward as pi   WHERE pd.roll_no_id=pi.product_inward_id  AND  pd.printing_operator_id = '".$details['printing_operator_id']."' AND pd.is_delete = '0' GROUP by pd.printing_roll_id ";	
        										 $total_roll_data = $this->query($sql);
        										
        											 if(!empty($total_roll_data)){
        												
        											$roll_array=array();
                													 $job_detail = $total_roll_data;
                												
                													foreach($job_detail->rows as $job_d)
                													{
                    								                    $roll_array[]='<b>'.$job_d['roll_no'].'</b>('. $job_d['film_size'].'(mm))';
                    								                       /*	$html.='<tr>
                    									           			<td id="data" width="15%"><b>'.$job_d['roll_no'].'</b></td>
                    														<td id="data" width="30%"><b>'. $job_d['roll_name_id'].'</b></td>
                    														<td id="data" width="15%"><b>'. $job_d['film_size'].'(mm)</b></td>
                    														<td id="data" width="15%"><b>'.ucwords($job_d['input_qty']).'(kgs)	</b></td>
                    														<td id="data" width="15%"><b>'.ucwords($job_d['output_qty']).'(kgs)</b></td>
                    														<td id="data" width="10%"><b> '.ucwords($job_d['balance_qty']).'(kgs)</b></td>
                    												    	</tr>';*/
                    
                											    	}
        										        	}
        										        //	printr($roll_array);
        										        //	printr($roll_details);
        										        		$uni_arr = array_unique($roll_array);
		                                                    	$u_arr = implode(',',$uni_arr);
        											 	    $total_input=$total_input+$roll_details['total_input'];
        													  $total_output=$total_output+$roll_details['total_output'];
        											  	      $plain_wastage=$plain_wastage+$details['plain_wastage'];
        											  	  	  $print_wastage=$print_wastage+$details['print_wastage'];
        													  $total_wastage=$total_wastage+$details['total_wastage'];
        												      $wastage_per=$wastage_per+$details['wastage_per'];
        									/*	$html.='</tbody>
        												</table>
        											</div></td>';*/
        											$print_wastage=$details['plain_wastage']+$details['print_wastage'];
        										$html.='
        												<td width="20%"><div align="center">'.$u_arr.'</div></td>
        											
        												<td width="10%"><div align="center">'. $roll_details['total_input'].'(kgs)</div></td>
        												<td width="10%"><div align="center">'. $roll_details['total_output'].'(kgs)</div></td>
        													<td width="10%"><div align="center">'. $roll_details['total_balance'].'(kgs)</div></td>
        											
        												<td width="10%"><div align="center">'. $print_wastage.'(kgs)</div></td>
        												<td width="10%"><div align="center">'. $details['total_wastage'].'(kgs)</div></td>
        												<td width="10%"><div align="center">'.$details['wastage_per'].' (%)</div></td>
        												</tr>';
										}

							/*	$html.=' <tr>	
												<td width="10%"><div align="center"><b>Total</b></div></td>	
												<td width="10%"><div align="center"><b></b></div></td>								
												<td width="40%"><div align="center"><b></b></div></td>
												<td width="7%"><div align="center"><b>'.$total_input.'(kgs)</b></div></td>
												<td width="7%"><div align="center"><b>'.$total_output.'(kgs)</b></div></td>
												<td width="7%"><div align="center"><b>'.$plain_wastage.'(kgs)</b></div></td>
												<td width="7%"><div align="center"><b>'.$print_wastage.'(kgs)</b></div></td>
												<td width="7%"><div align="center"><b>'.$total_wastage.'(kgs)</b></div></td>
												<td width="5%"><div align="center"><b>'.$wastage_per.'%</b></div></td>



							 			 </tr>';*/
								$html.='</tbody>';
								$html.='	</table>';


									$html.='</div></div></div>';

				
/*				$html.='<div  style=" width: 100%;float: left; font-size: 10px;">';
			   $html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;"><b>INK USED</b>';
					$html.='</div>';
							$html.='<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 8px;">
								<tbody>
								<tr>
								 
									
										<td style="vertical-align: top;width: 25%;" id="data">							 
											<div align="center"><b>InK Details</b></div>
										</td>
										<td style="vertical-align: top;width: 25%;" id="data">
											<div align="center"><b>Mix Ink Details</b></div>
										</td>
										<td style="vertical-align: top;width: 25%;" id="data">
											<div align="center"><b>Solvent Used(Kgs)</b></div>
										</td>
										<td style="width: 25%;"id="data">
											<div align="center"><b>Mix Solvent Used(Kgs)</b></div>
										</td>
									</tr>		
							</tbody>
							';
							$html.='
							<tbody>

							<tr id="data">';
								$html.='
									<td style="vertical-align: top;width: 25%;">							 
										<table style="width: 100%;" >
										<tbody id="data">
											<tr >
												<td style="vertical-align: top;width: 25%;" id="data"><div align="center"><b>INK Name</b></div></td>
												<td style="vertical-align: top;width: 25%;" id="data"><div align="center"><b>INK Issue</b></div></td>
												<td style="vertical-align: top;width: 25%;" id="data"><div align="center"><b>INK Used</b></div></td>
											</tr>

										</tbody>
										</table>
									</td>
									<td style="vertical-align: top;width: 25%;">
										<table style="width: 100%;" >
										<tbody id="data">
											<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Name</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Issue</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Used</b></div></td>
											</tr>

										</tbody>
										</table>
									</td>
									<td style="vertical-align: top;width: 25%;">
										<table style="width: 100%;" >
										<tbody id="data">
											<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Name</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Issue</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Used</b></div></td>
											</tr>

										</tbody>
										</table>
									</td>
									<td style="vertical-align: top;width: 25%;">
										<table style="width: 100%;" >
										<tbody id="data">
											<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Name</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Issue</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Used</b></div></td>
											</tr>

										</tbody>
										</table>
									</td>
								
							</tr>
							</tbody>';


							$html.='<tr>
									<td style="vertical-align: top;width: 25%;">							 
										<table style="width: 100%;" >
										<tbody id="data">';

										if(!empty($total_ink)){
												foreach($total_ink as $ink ) {

										$html.='<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$ink['ink_name_p'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$ink['ink_issue'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$ink['ink_use'].'</div></td>
											</tr>';
											}

										$html.='<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center">Total</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$ink['total_issue'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$ink['total_use'].'</div></td>
											</tr>';
										}else{
											$html.='
												<tr>	
													<td colspan ="3" style="vertical-align: top;width: 100%;"><div align="center">No record Found !!!</div></td>																
												</tr>';
											
										}

									$html.='</tbody>
										</table>
									</td>';
									$html.='<td style="vertical-align: top;width: 25%;">
										<table style="width: 100%;" >
										<tbody id="data">';
										if(!empty($total_mix_ink)){
													foreach($total_mix_ink as $mix ) {

										$html.='<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix['ink_name_p'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix['ink_issue'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix['ink_use'].'</div></td>
											</tr>';
											}
											$html.='<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center">Total</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix['total_issue'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix['total_use'].'</div></td>
											</tr>';
										}else{
											$html.='
												<tr>	
													<td colspan ="3" style="vertical-align: top;width: 100%;"><div align="center">No record Found !!!</div></td>																
												</tr>';
											
										}

										$html.='</tbody>
										</table>
									</td>';
								$html.='	
									<td style="vertical-align: top;width: 25%;">
										<table style="width: 100%;" >
										<tbody id="data">';
											if(!empty($total_solvent)){
											foreach($total_solvent as $sol ) {
													
										$html.='<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$sol['ink_name_p'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$sol['solvent_ink_issue'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$sol['solvent_ink_use'].'</div></td>
											</tr>';
											}
											$html.='<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center">Total</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$sol['total_issue'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$sol['total_issue'].'</div></td>
											</tr>';
										}else{
											$html.='
												<tr>	
													<td colspan ="3" style="vertical-align: top;width: 100%;"><div align="center">No record Found !!!</div></td>																
												</tr>';
											
										}
										$html.='</tbody>
										</table>
									</td>';
									$html.='
									<td style="vertical-align: top;width: 25%;">
										<table style="width: 100%;" >
										<tbody id="data">';
										if(!empty($total_mix_solvent)){
										foreach($total_mix_solvent as $mix_sol ) {
										$html.='<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix_sol['ink_name_p'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix_sol['mix_solvent_issue'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix_sol['mix_solvent_use'].'</div></td>
											</tr>';
											}
											$html.='<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center">Total</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix_sol['total_issue'].'</div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center">'.$mix_sol['total_issue'].'</div></td>
											</tr>';
										}else{
											$html.='
												<tr>	
													<td colspan ="3" style="vertical-align: top;width: 100%;"><div align="center">No record Found !!!</div></td>																
												</tr>';
											
										}
										$html.='</tbody>
										</table>
									</td>';
									$html.='</tr>';
						$html.='	</tbody>
							</table>
						</div>';
*/

    }//	printr($lamination_details);
		if(!empty($lamination_details)){

	
         //Lamination  Details page-break-before: always;
              $html .='<div  style="font-size: 10px;" ><div style="text-align:center;border: "><b>LAMINATION DETAILS</b>';
					$html.='</div>

						<div class="div_first" style=" width: 100%;float: left; font-size: 10px;">';
						$html.='	<table class="table">
							<tbody >
							<tr >
							<td style="vertical-align: top;width: 50%;">
							 
								<b>Lamination  No : </b>  '.ucwords($lamination_details['lamination_no']).'	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
									<b> Date : </b>  '.dateFormat(4,$lamination_details['lamination_date']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b>Machine name :</b>   '.ucwords($lamination_details['machine_name']).' 
								

							</td>
							<td style="vertical-align: top;width: 50%;">
								<b>Start Time :</b>   '.ucwords($lamination_details['start_time']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b>End Time :</b>   '.ucwords($lamination_details['end_time']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b>Shift :</b>   '.ucwords($lamination_details['shift']).'<br>
							
							</td>
								</tbody>
							</tr>
							</table>';

				$html .='<div class="div_first" style="width: 100%; float: left;  ">';

					$html.='	<table class="table" style="font-size: 8px;">
								<tbody>
								<tr>
									<td width="5%"><div align="center"><b>Layer No.</b></div></td>
									<td width="10%"><div align="center"><b>Operator Details</b></div></td>
									<td width="5%"><div align="center"><b>Roll Used</b></div></td>
									<td width="20%"><div align="center"><b>Roll Detail</b></div></td>';
								/*$html.='<table cellspacing="0px" cellpadding="10px"  style="width: 100%; border-spacing: 0px;font-size: 8px;">
										<tbody>
										<tr>
											<td colspan="6"><b><center>Roll Details<center></b></td>
										</tr>
										<tr >
											<td id="data" width="15%"><b>Roll No</b></td>
											<td id="data" width="30%"><b>Roll Name</b></td>
											<td id="data" width="15%"><b>Roll Size</b></td>
											<td id="data" width="15%"><b>I/P Qty</b></td>
											<td id="data" width="15%"><b>O/P Qty</td>
											<td id="data" width="10%"><b> Bal Qty</td>
										</tr>
										<tbody>
										</table>';*/
							$html.='
									
								
									<td width="10%"><div align="center"><b>Total I/P(Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Total O/P(Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Total Balance Qty(Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Print Waste (Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Total Waste (Kgs)</b></div></td>
									<td width="10%"><div align="center"><b>Waste (%)</b></div></td>
										
									</tr>
									</tbody>
									<tbody>';
								foreach($layer_details as $layer){
								    //printr($layer['layer_date']);

									$junior_name=$operator_name='';
									$junior= $this->getJuniorOperator_name($layer['junior_id']);
									$operator= $this->getJuniorOperator_name($layer['operator_id']);
									if($junior!=''){
											$junior_name=' <b>Junior : </b> '.$junior['first_name'].'  '.$junior['last_name'];
									}
									if($operator!=''){
										$operator_name='<b>Main :</b>'.$operator['first_name'].'  '. $operator['last_name'].' <br>'.$junior_name;

									}
                        			    
								  	     
									if( $layer['total_input']!=0.000){
										$layer['total_input']=$layer['total_input'].'(kgs)';
									//	$total_input_l=$total_input_l+$layer['total_input'];
									}else{
										$layer['total_input']='-';
									}
									if( $layer['total_output']!=0.000){
									  //   $total_output_l=$total_output_l+$layer['total_output'];
									$layer['total_output']=$layer['total_output'].'(kgs)';
										
									}else{
										$layer['total_output']='-';
									}
									if( $layer['plain_wastage']!=0.000){
									    // $plain_wastage_l=$plain_wastage_l+$layer['plain_wastage'];
								  	  	  
										$layer['plain_wastage']=$layer['plain_wastage'].'(kgs)';
									}else{
										$layer['plain_wastage']='-';
									}
									if( $layer['print_wastage']!=0.000){
									   // $print_wastage_l=$print_wastage_l+$layer['print_wastage'];
										
										$layer['print_wastage']=$layer['print_wastage'].'(kgs)';
									}else{
										$layer['print_wastage']='-';
									}
									if( $layer['total_wastage']!=0.000){
									    // $total_wastage_l=$total_wastage_l+$layer['total_wastage'];
									  
										$layer['total_wastage']=$layer['total_wastage'].'(kgs)';
									}else{
										$layer['total_wastage']='-';
									}
									if( $layer['wastage_per']!=0.000){
									  //  $wastage_per_l=$wastage_per_l+$layer['wastage_per'];
										$layer['wastage_per']=$layer['wastage_per'].'%';
									}else{
										$layer['wastage_per']='-';
									}
                                    

									$html.='
									<tr>
									<td width="5%"><div align="center">'.$layer['layer_no'].'<br>'.dateFormat(4,$layer['layer_date']).'</div></td>
									<td width="10%"><div align="center">'.$operator_name.'</div></td>
									<td width="5%"><div align="center">'.$layer['roll_used'].'</div></td>';
							/*	$html.='	<td width="50%"><div align="center">';*/
										$roll_details= $this->getRollDetails($lamination_details['lamination_id'],$layer['lamination_layer_id']);
								/*	$html.='<table cellspacing="0px" cellpadding="10px"  style="width: 100%; border-spacing: 0px;font-size: 8px;">
										<tbody>';*/
                                             $lamination_roll=array();
                                             $total_bal=0;
											foreach($roll_details as $roll_no ) {
															if($layer['printing_status']=='0'){															
																 $roll =$this->get_roll_no($roll_no['roll_no_id']);																
																
															}else{
																
																$roll_code=$this->getPrintingRollDetails($layer['lamination_layer_id']);	
																$roll= $roll_code.' <b>(Printing Roll)</b>';
																
																}
                                                    $lamination_roll[]='<b>'.$roll.'</b>('.$roll_no['film_size'].'(mm)';
                                                    $total_bal=$total_bal+$roll_no['balance_qty'];
									/*	$html.='<tr >
											<td id="data"width="15%" ><b>'.$roll.'</b></td>
											<td id="data" width=30%"><b>'.$roll_no['roll_name_id'].'</b></td>
											<td id="data" width="15%"><b>'.$roll_no['film_size'].'(mm)</b></td>
											<td id="data" width="15%"><b>'.$roll_no['input_qty'].'(kgs)</b></td>
											<td id="data" width="15%"><b>'.$roll_no['output_qty'].'(kgs)</b></td>
											<td id="data" width="10%"><b>'.$roll_no['balance_qty'].'(kgs)</td>
										</tr>';
											*/
											 	$uni_arr_lami = array_unique($lamination_roll);
		                                        $u_arr_lami = implode(',',$uni_arr_lami);   
											    
											}
									/*	$html.='<tbody>
										</table></div></td>';
*/

                                $print_wastage=$this->numberFormate($layer['print_wastage']+$layer['plain_wastage'],"3");
                                
                                	if( $total_bal!=0.000){
									    
										$total_bal=$total_bal.'(kgs)';
									}else{
										$total_bal='-';
									}
									if( $print_wastage!=0.000){
									    
										$print_wastage=$print_wastage.'(kgs)';
									}else{
										$print_wastage='-';
									}
									$html.='
									<td width="20%"><div align="center">'. $u_arr_lami.'(kgs)</div></td>
								
									<td width="10%"><div align="center">'. $layer['total_input'].'</div></td>
									<td width="10%"><div align="center">'. $layer['total_output'].'</div></td>
										<td width="10%"><div align="center">'.$total_bal.'</div></td> 
									
									<td width="10%"><div align="center">'. $print_wastage.' </div></td>
									<td width="10%"><div align="center">'. $layer['total_wastage'].'</div></td>
									<td width="10%"><div align="center">'. $layer['wastage_per'].' </div></td> 
									</tr>';
								}
								//	<td width="5%"><div align="center"><b>'.$total_input_l.'(kgs)</b></div></td>
//												<td width="5%"><div align="center"><b>'.$total_output_l.'(kgs)</b></div></td>
								/*	$html.=' <tr>	
												<td width="70%" colspan="6"><div align="center"><b>Total</b></div></td>	
											
											
												<td width="5%"><div align="center"><b>'.$plain_wastage_l.'(kgs)</b></div></td>
												<td width="5%"><div align="center"><b>'.$print_wastage_l.'(kgs)</b></div></td>
												<td width="5%"><div align="center"><b>'.$total_wastage_l.'(kgs)</b></div></td>
												<td width="5%"><div align="center"><b>'.$wastage_per_l.'%</b></div></td>



							 			 </tr>';*/
								$html.='</tbody>
								</table></div></div></div>';

		}
		
      //slitting Details
	if(!empty($slitting_details)){
         if($slitting_details['slitting_status']==0){
			$printing_details = $this->getPrintingDetailsSlitiing($slitting_details['roll_code_id']);
			
			$roll_code=$printing_details['roll_code'];
			$roll_size=$printing_details['roll_size'];
			$job_no=$printing_details['job_name_text'];
			$label='Printing Roll No';
			$th= 'JOB No';
		}else if($slitting_details['slitting_status']==1){
				
			$lamination_details = $this->getLamination_details($slitting_details['roll_code_id']);
		//	printr($lamination_details);
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
				$slitting_details['total_wastage']=$slitting_details['total_wastage'].'(kgs)';
			}else{
				$slitting_details['total_wastage']='-';
			
			}
        $setting_wastage=$this->numberFormate($slitting_details['top_cut_wastage']+$slitting_details['setting_wastage'],"3").'(kgs)';
//page-break-before: always;
	 $html .='<div  style="font-size: 10px;"><div style="text-align:center; "><b>SLITTING  DETAILS</b>';
		$html.='</div>
					<div class="div_first" style=" width: 100%;float: left;  font-size: 10px;">';
						$html.='	<table class="table" >
							<tbody>
							<tr>
								
							<td style="vertical-align: top;width: 50%;">
							 	
								<b>Shift :</b>   '.ucwords($slitting_details['shift']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b> Date : </b>  '.dateFormat(4,$slitting_details['slitting_date']).'
								

							</td>
							<td style="vertical-align: top;width: 50%;"><b> Slitting machine Name  : </b>   '. ucwords($slitting_details['machine_name']).'
							</td>
							</tr>
								</tbody>
							</table>';
							
							$html.='	<table class="table" >
								<tbody>
							<tr>
								<td style="vertical-align: top;width: 50%;" colspan="2"><div style="text-align:center;"><b> Slitting Roll Details  </b></td>
							
							</tr>
							<tr>
							<td style="vertical-align: top;width: 50%;" id="data"><div align="center"><b>Slitting Roll </b></div></td>
							<td style="vertical-align: top;width: 50%;" id="data"><div align="center"><b>Output Qty</b></div></td>
							</tr>
								';
							if(!empty($slitting_roll_details)){
							foreach($slitting_roll_details as $roll_no ) {
																	//	printr($roll_no);
																	
						
								$html.='<tr>
										<td style="vertical-align: top;width: 50%;" id="data"><div align="center">'.$roll_no['roll_code'].'</div></td>
										<td style="vertical-align: top;width: 50%;" id="data"><div align="center">'.$roll_no['output_qty'].'(kgs)</div></td>
										</tr>';
															
							}}else{
									$html.='<tr>
									<td style="vertical-align: top;width: 50%;" colspan="2"></td>
										</tr>';
							}
							
							$html.='</tbody></table>';
							$html .='<div class="div_first" style="width: 100%; float: left; ">

					<table class="table" style="font-size: 8px;">
						<tbody>
						<tr>
						 
						
								<td width="10%"><div align="center"><b>Slitting No </b></div></td>	
								<td width="10%"><div align="center"><b>Operator Name</b></div></td>	
								<td width="10%"><div align="center"><b>I/P(Kgs)</b></div></td>
								<td width="10%"><div align="center"><b>O/P(Kgs)</b></div></td>
								<td width="10%"><div align="center"><b>Setting  Wastage (Kgs)</b></div></td>
								<td width="10%"><div align="center"><b>Lamination Wastage(Kgs)</b></div></td>							
								<td width="10%"><div align="center"><b>Printing Wastage (Kgs)</b></div></td>								
								<td width="10%"><div align="center"><b>Trimming Wastage (Kgs)</b></div></td>							
								<td width="10%"><div align="center"><b>Total Wastage (Kgs)</b></div></td>							
								<td width="10%"><div align="center"><b>Waste (%)</b></div></td>	
						</tr>
						<tr>
						 
						
								<td width="10%"><div align="center">'.$slitting_details['slitting_no'].'</div></td>	
								<td width="10%"><div align="center">'.$slitting_details['operator_name'].'</div></td>
								<td width="10%"><div align="center">'.$slitting_details['input_qty'].'</div></td>
								<td width="10%"><div align="center">'.$slitting_details['output_qty'].'</div></td>	
								<td width="10%"><div align="center">'.$setting_wastage.'</div></td>	
							
								<td width="10%"><div align="center">'.$slitting_details['lamination_wastage'].'</div></td>							
								<td width="10%"><div align="center">'.$slitting_details['printing_wastage'].'</div></td>								
								<td width="10%"><div align="center">'.$slitting_details['trimming_wastage'].'</div></td>							
								<td width="10%"><div align="center">'.$slitting_details['total_wastage'].'</div></td>							
								<td width="10%"><div align="center">'.$slitting_details['wastage'].' (%)</div></td>	
						</tr>
					
					
							</tbody><tbody></table>
						</div></div>';


		}
//pouching details  
		if(!empty($pouching_details)){
		    //printr($pouching_details);
	        

         $html .='<div  style="font-size: 10px;"><div style="text-align:center;"><b>POUCHING  DETAILS</b>';
         
           foreach($pouching_details as $pouching){
               
               	 $product_name = $this->zipper_name($pouching['zipper_id']);
            //  printr($pouching_details);
		    	if(!empty($pouching)){
            			$job_d = explode(',',$pouching['job_id']);	
            			$job_detail_for_pouching = $this->input_roll_details($job_d,$pouching['pouching_id']);
              
            	}
            	$total_wastage=$this->numberFormate(($pouching['online_setting_wastage']+$pouching['top_cut_wastage']),"3");
	    	$html.='</div><div class="div_first" style=" width: 100%;float: left;font-size: 10px;">
							<table  class="table">
							<tr>
							<td style="vertical-align: top;width: 50%;">
							 	
								<b>Shift :</b>   '.ucwords($pouching['shift']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b> Date : </b>  '.dateFormat(4,$pouching['pouching_date']).'
								

							</td>
							<td style="vertical-align: top;width: 50%;"><b> Pouching machine Name  : </b>   '. ucwords($pouching['machine_name']).'
							</td>
							</tr>
							</table>';
							/*$html.='	<table style="width: 100%;" border="1" >
							<tr>
							<td style="vertical-align: top;width: 50%;" colspan="3"><div style="text-align:center;"><b> Input Roll Details  </b></td>
							
							</tr>
							<tr>
							<td style="vertical-align: top;width: 50%;" id="data"><div align="center"><b>JOB No </b></div></td>
							<td style="vertical-align: top;width: 25%;" id="data"><div align="center"><b>Roll No</b></div></td>
							<td style="vertical-align: top;width: 25%;" id="data"><div align="center"><b>I/P Qty</b></div></td>
							</tr>';*/
							
							if(!empty($job_detail_for_pouching['slitting'] )){
							 $pouching_arr=array();
							 $roll_weight=0;
							foreach($job_detail_for_pouching['slitting']  as $job ) {
										$roll_weight =$roll_weight+	$job['o_qty'];
									//	$pouching_arr[]='<b><u>('.$job['roll_code'].'</u> : '.$job['o_qty'].'kgs)</b>';
										$pouching_arr[]='<b>'.$job['roll_code'].'</b>';
										$uni_arr_pouch = array_unique($pouching_arr);
										
		                                  $u_arr_pouch = implode(',',$uni_arr_pouch);   		
						
							/*	$html.='<tr>
										<td style="vertical-align: top;width:50 %;" id="data"><div align="center">'.$job['job_no'].'</div></td>
										<td style="vertical-align: top;width:25%;" id="data"><div align="center">'.$job['roll_code'].'</div></td>
										<td style="vertical-align: top;width:25%;" id="data"><div align="center">'.$job['o_qty'].'(kgs)</div></td>
										</tr>';
														*/	
							}}/*else{
									$html.='<tr>
									<td style="vertical-align: top;width: 50%;" colspan="2"></td>
										</tr>';
							}*/
							
						/*	$html.='</table>';*/
						
						
						$total_wastage=$pouching['top_cut_wastage']+$pouching['online_setting_wastage']+$pouching['printing_wastage']+$pouching['lamination_wastage']+$pouching['trimming_wastage'];
							$html .='<div class="div_first" style="width: 100%; float: left; ">

					<table class="table" style="font-size: 8px;">
						<tbody>
						<tr>
						 	
						
								<td width="5%"><div align="center"><b>Pouching No </b></div></td>	
								<td width="10%"><div align="center"><b>Operator Name</b></div></td>	
								<td width="10%"><div align="center"><b>R.W(kgs)</b></div></td>	
							    <td width="10%"><div align="center"><b>zipper</b></div></td>
								<td width="10%"><div align="center"><b>Z.W(kgs)</b></div></td>
								<td width="15%"><div align="center"><b>O/P Qty </b></div></td>
								
								<td width="5%"><div align="center"><b>Printing Wastage (Kgs)</b></div></td>								
								<td width="5%"><div align="center"><b>Lamination Wastage (Kgs)</b></div></td>								
								<td width="5%"><div align="center"><b>Trimming Wastage (Kgs)</b></div></td>
								<td width="10%"><div align="center"><b>Pouch Wastage (kgs)</b></div></td>
								<td width="5%"><div align="center"><b>Operator Wastage (%)</b></div></td>	
								<td width="5%"><div align="center"><b> Wastage (%)</b></div></td>	
						</tr>
						<tr>
						 
						
								<td width="5%"><div align="center">'.$pouching['pouching_no'].'</div></td>	
								<td width="10%"><div align="center"><b>Main : </b>'.$pouching['operator_name'].'  <br>'.$junior_name.'</div></td>	
								<td width="10%"><div align="center">('.$u_arr_pouch.')  '.$roll_weight.'(kgs))</div></td>
								<td width="10%"><div align="center">'.$product_name.'</b></div></td>
								<td width="10%"><div align="center">'.$pouching['zipper_used'].'(meter)<b>'.$pouching['zipper_used_kg'].'(kgs)</b></div></td>
								<td width="15%"><div align="center">'.$pouching['output_qty'].'(no)<br>'.$pouching['output_qty_kg'].'(kgs)'.$pouching['output_qty_meter'].'(meter)</div></td>	
							
							
							
								<td width="5%"><div align="center">'.$pouching['printing_wastage'].'(kgs)</div></td>								
								<td width="5%"><div align="center">'.$pouching['lamination_wastage'].'(kgs)</div></td>								
								<td width="5%"><div align="center">'.$pouching['trimming_wastage'].'(kgs)</div></td>
								<td width="10%"><div align="center">'.$total_wastage.'(kgs)</div></td>
								<td width="5%"><div align="center">'.$pouching['operator_wastage'].' %</div></td>
								<td width="5%"><div align="center">'.$pouching['total_wastage'].' %</div></td>
															
						</tr>
							 
					



					
							</tbody><tbody></table>';
					//		$job_detail_for_pouching=array();
           }
			$html.='</div>';


                
			}
               $html.=' </form>';


//printr($html);//die;
			return $html;

	}

 public function zipper_name($product_item_id) {
		
   
	   $sql = "SELECT * FROM `product_item_info` WHERE `product_item_id` = '".$product_item_id."' ";
	   $data = $this->query($sql);
	   //printr($sql);
	   //printr($data);
		  if ($data->num_rows) {
            return $data->row['product_name'];
        } else {
            return false;
        }
		
	
		
	 //die;
    } 
	
	public function getJObName($job_id){

		$sql= "SELECT j.*,p.product_name FROM job_master as j ,product as p WHERE  j.job_id='".$job_id."'  AND p.product_id=j.product";
		//echo $sql;
		$data = $this->query($sql);
//	printr($data );
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}


		
	}
public function getCountryName($country_id){

		$sql= "SELECT * FROM country  WHERE  country_id='".$country_id."'";
		//echo $sql;
		$data = $this->query($sql);
//	printr($data );
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}


		
	}

		public function gettotal_ink($job_id){
		
		  $sql = "SELECT * FROM ink_process as i  WHERE i.is_delete = 0 AND i.job_id='" . $job_id . "' ";
			$data = $this->query($sql);
		//	printr($data);
        if ($data->row) {
            $ink_process_detail = "SELECT * FROM ink_process_detail as ipd , product_item_info as p  WHERE ipd.ink_p_id='" . $data->row['ink_p_id'] . "' AND p.product_item_id=ipd.ink_name  AND ipd.is_delete = '0' ";
            $ink_process_data = $this->query($ink_process_detail);
			$total = "SELECT *,sum(ink_issue)as total_issue ,sum(ink_use)as total_use FROM ink_process_detail as ipd , product_item_info as p  WHERE ipd.ink_p_id='" . $data->row['ink_p_id'] . "' AND p.product_item_id=ipd.ink_name  AND ipd.is_delete = '0' ";
			  $total_data = $this->query($total);

		//printr($ink_process_data);
            $ink_array = array();
            foreach ($ink_process_data->rows as $ink_data) {
                $ink_array[] = array(
                    'ink_process_id' => $data->row['ink_p_id'],
                    'ink_process_detail_id' => $ink_data['ink_p_d_id'],
                    'ink_name' => $ink_data['ink_name'],
                    'ink_issue' => $ink_data['ink_issue'],
					'ink_name_p'=>$ink_data['product_name'],
                    'ink_return' => $ink_data['ink_return'],
                    'ink_use' => $ink_data['ink_use'],
                    'total_issue' => $total_data->row['total_issue'],
                    'total_use' => $total_data->row['total_use'],
                    'remark' => $ink_data['remark'],
                    'date_added' => $ink_data['date_added'],
                    'date_modify' => $ink_data['date_modify'],
                    'is_delete' => $ink_data['is_delete'],
                );
            }
            $all_ink_array = array(
                'ink_process_id' => $data->row['ink_p_id'],
                'job_id' => $data->row['job_id'],
                'operator_id' => $data->row['operator_id'],
                'chemist_id' => $data->row['chemist_id'],
                'shift' => $data->row['shift'],
                'date' => $data->row['date'],
                'date_added' => $data->row['date_added'],
                'date_modify' => $data->row['date_modify'],
                'status' => $data->row['status'],
                'is_delete' => $data->row['is_delete'],
                'ink_detail' => $ink_array,
            );
//            printr($all_ink_array);die;
            return  $ink_array;
        } else {
            return false;
        }
		
		
	}
	public function gettotal_mix_ink($job_id){
		
		 $sql = "SELECT * FROM mix_ink_process as i  WHERE i.is_delete = 0 AND i.job_id='" .$job_id. "' ";
        $data = $this->query($sql);
	//	printr($data);
	
        if ($data->row) {
            $ink_process_detail = "SELECT * FROM mix_ink_process_detail as ipd , product_item_info as p WHERE ipd.mix_ink_p_id='" .$data->row['mix_ink_p_id']. "' AND p.product_item_id=ipd.ink_name    AND ipd.is_delete = '0' ";
            $ink_process_data = $this->query($ink_process_detail);
		//	printr($ink_process_data);
			$total = "SELECT *,sum(ink_issue)as total_issue ,sum(ink_use)as total_use FROM mix_ink_process_detail as ipd , product_item_info as p  WHERE ipd.mix_ink_p_id='" . $data->row['mix_ink_p_id'] . "' AND p.product_item_id=ipd.ink_name  AND ipd.is_delete = '0' ";
			 $total_data = $this->query($total);
		//	 printr($total_data);
            $ink_array = array();
            foreach ($ink_process_data->rows as $ink_data) {
                $ink_array[] = array(
                    'mix_ink_process_id' => $data->row['mix_ink_p_id'],
                    'mix_ink_process_detail_id' => $ink_data['mix_ink_p_d_id'],
                    'ink_name' => $ink_data['ink_name'],
                    'ink_issue' => $ink_data['ink_issue'],
					'ink_name_p'=>$ink_data['product_name'],
                    'ink_return' => $ink_data['ink_return'],
					'total_issue' => $total_data->row['total_issue'],
                    'total_use' => $total_data->row['total_use'],
                    'ink_use' => $ink_data['ink_use'],
                    'remark' => $ink_data['remark'],
                    'date_added' => $ink_data['date_added'],
                    'date_modify' => $ink_data['date_modify'],
                    'is_delete' => $ink_data['is_delete'],
                );
            }
            $all_ink_array = array(
                'mix_ink_process_id' => $data->row['mix_ink_p_id'],
                'job_id' => $data->row['job_id'],
                'operator_id' => $data->row['operator_id'],
                'chemist_id' => $data->row['chemist_id'],
                'shift' => $data->row['shift'],
                'date' => $data->row['date'],
                'date_added' => $data->row['date_added'],
                'date_modify' => $data->row['date_modify'],
                'status' => $data->row['status'],
                'is_delete' => $data->row['is_delete'],
                'mix_ink_detail' => $ink_array,
            );
//           printr($all_ink_array);die;
            return $ink_array;
        } else {
            return false;
        }
		
		
		
	}
	public function gettotal_solvent($job_id){
		 $sql = "SELECT * FROM solvent_ink_process as i  WHERE i.is_delete = 0 AND i.job_id='" .$job_id. "' ";
        $data = $this->query($sql);
        if ($data->row) {
            $ink_process_detail = "SELECT * FROM solvent_ink_process_detail as ipd,product_item_info as p  WHERE ipd.solvent_ink_p_id='" .$data->row['solvent_ink_p_id']. "' AND p.product_item_id=ipd.solvent_ink_name  AND ipd.is_delete = '0' ";
            $ink_process_data = $this->query($ink_process_detail);
		//	printr( $ink_process_data);//die;
			
			
			$total = "SELECT *,sum(solvent_ink_issue)as total_issue ,sum(solvent_ink_use)as total_use FROM solvent_ink_process_detail as ipd , product_item_info as p  WHERE ipd.solvent_ink_p_id='" . $data->row['solvent_ink_p_id'] . "' AND p.product_item_id=ipd.solvent_ink_name  AND ipd.is_delete = '0' ";
			 $total_data = $this->query($total);
            $ink_array = array();
            foreach ($ink_process_data->rows as $ink_data) {
                $ink_array[] = array(
                    'ink_process_id' => $data->row['solvent_ink_p_id'],
                    'ink_process_detail_id' => $ink_data['sol_ink_p_d_id'],
                    'solvent_ink_name' => $ink_data['solvent_ink_name'],
                    'solvent_ink_issue' => $ink_data['solvent_ink_issue'],
                    'solvent_ink_return' => $ink_data['solvent_ink_return'],					
                    'solvent_ink_use' => $ink_data['solvent_ink_use'],
					'total_issue' => $total_data->row['total_issue'],
                    'total_use' => $total_data->row['total_use'],
					'ink_name_p'=>$ink_data['product_name'],
                    'remark' => $ink_data['remark'],
                    'date_added' => $ink_data['date_added'],
                    'date_modify' => $ink_data['date_modify'],
                    'is_delete' => $ink_data['is_delete'],
                );
            }
            $all_ink_array = array(
                'ink_process_id' => $data->row['solvent_ink_p_id'],
                'job_id' => $data->row['job_id'],
                'operator_id' => $data->row['operator_id'],
                'chemist_id' => $data->row['chemist_id'],
                'shift' => $data->row['shift'],
                'date' => $data->row['date'],
                'date_added' => $data->row['date_added'],
                'date_modify' => $data->row['date_modify'],
                'status' => $data->row['status'],
                'is_delete' => $data->row['is_delete'],
                'solvent_ink_detail' => $ink_array,
            );
         // printr($ink_array);//die;
            return $ink_array;
        } else {
            return false;
        }
	}
	public function gettotal_mix_solvent($job_id){
		
		
		
		 $sql = "SELECT * FROM mix_solvent_process as i  WHERE i.is_delete = 0 AND i.job_id='" .$job_id. "' ";
        $data = $this->query($sql);
	//	printr($data);
        if ($data->row) {
            $ink_process_detail = "SELECT * FROM mix_solvent_process_detail as ipd,product_item_info as p   WHERE ipd.mix_solvent_p_id='" .$data->row['mix_solvent_p_id']. "' AND ipd.is_delete = '0' AND p.product_item_id=ipd.mix_solvent_name   ";
            $ink_process_data = $this->query($ink_process_detail);
		
			$total = "SELECT *,sum(mix_solvent_issue)as total_issue ,sum(mix_solvent_use)as total_use FROM mix_solvent_process_detail as ipd , product_item_info as p  WHERE ipd.mix_solvent_p_id='" . $data->row['mix_solvent_p_id'] . "' AND p.product_item_id=ipd.mix_solvent_name  AND ipd.is_delete = '0' ";
			 $total_data = $this->query($total);
		//	printr($ink_process_data);
		
            $ink_array = array();
            foreach ($ink_process_data->rows as $ink_data) {
                $ink_array[] = array(
                    'mix_solvent_process_id' => $data->row['mix_solvent_p_id'],
                    'mix_solvent_process_detail_id' => $ink_data['mix_sol_p_d_id'],
                    'mix_solvent_name' => $ink_data['mix_solvent_name'],
                    'mix_solvent_issue' => $ink_data['mix_solvent_issue'],
                    'mix_solvent_return' => $ink_data['mix_solvent_return'],
                    'mix_solvent_use' => $ink_data['mix_solvent_use'],
					'total_issue' => $total_data->row['total_issue'],
                    'total_use' => $total_data->row['total_use'],
					'ink_name_p'=>$ink_data['product_name'],
                    'remark' => $ink_data['remark'],
                    'date_added' => $ink_data['date_added'],
                    'date_modify' => $ink_data['date_modify'],
                    'is_delete' => $ink_data['is_delete'],
                );
            }
            $all_ink_array = array(
                'mix_solvent_process_id' => $data->row['mix_solvent_p_id'],
                'job_id' => $data->row['job_id'],
                'operator_id' => $data->row['operator_id'],
                'chemist_id' => $data->row['chemist_id'],
                'shift' => $data->row['shift'],
                'date' => $data->row['date'],
                'date_added' => $data->row['date_added'],
                'date_modify' => $data->row['date_modify'],
                'status' => $data->row['status'],
                'is_delete' => $data->row['is_delete'],
                'mix_solvent_detail' => $ink_array,
            );

		//	printr($ink_array);//die;
            return $ink_array;
        } else {
            return false;
        }
		
		
		
	}
	public function getSize($size_id){
		$data = $this->query("SELECT gusset,height,width,volume FROM " . DB_PREFIX ." size_master  WHERE size_master_id = '".(int)$size_id."' AND status=0 LIMIT 1");
	   // printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
		
	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	
	public function formulaHeightWidthGusset($height,$width,$gusset,$product_id,$sealing,$print_type){
	

	
		if($product_id=='1')
		{	//csg 
				
				
				$widthFormula = (($width * 2) +10);
				$gussetFormula1 = ($gusset * 4) ;
				$sealing=($sealing*2);
			    $actualwidth=$this->numberFormate((($widthFormula+$gussetFormula1+$sealing)/1000),"3"); 
				$actualHeight1 = $this->numberFormate(($height /1000),"3"); 
			    $actualwidth=$actualwidth*$print_type;
			
	//	echo $widthFormula.'+'.$gussetFormula1.'+'.$sealing;
    	}elseif($product_id=='3'){ 
    	    //sup
    	    
    	         $f_gusset=$gusset+$gusset+10;
	             $f_height=($height*2)+10+10;
	             $actualwidth=$this->numberFormate(($width/1000),"3");
	             $actualHeight1=$this->numberFormate((($f_height+$f_gusset)/1000),"3");
    	        $actualHeight1=$actualHeight1*$print_type;
    	 //  echo $f_gusset.'+'.$f_height;
    
    	    
    	}elseif($product_id=='4'){ 
    	    //tss
    	    
    	        
	             $f_height=($height*2)+10;
	             $actualwidth=$this->numberFormate(($width/1000),"3");
	             $actualHeight1=$this->numberFormate((($f_height)/1000),"3");
	             $actualHeight1=$actualHeight1*$print_type;
    	    
    	 //  echo $f_gusset.'+'.$f_height;
    
    	    
    	}elseif($product_id=='5'){ 
    	    //pillow bag // 
    	        $widthFormula = (($width * 2) + 20);
				$gussetFormula1 = 0 ;
				$sealing=($sealing*2);
			    $actualwidth=$this->numberFormate((($widthFormula+$gussetFormula1+$sealing)/1000),"3"); 
				$actualHeight1 = $this->numberFormate(($height /1000),"3"); 
		    	$actualwidth=$actualwidth*$print_type;
    	    
    	  //echo $widthFormula.'+hwrtj'.$sealing;
    
    	    
    	}
    	
        	$return = array();
        	$return = array(
        	
        		'width'	=> $actualwidth,
        		'height'=> $actualHeight1,
        		
        	);
        return  $return;
    }
    
     public function getLayerformula($thickness,$product_gsm,$width,$height,$no_of_pouch,$add_val,$product_id,$print_type){
         	
         //	printr($add_val.'===='.$product_id);
         	if($product_id=='1' || $product_id=='5')
        		{
        		    //csg //pillow
        		   
        		  $fwidth=$width+$add_val;
        		  $film_size=$fwidth;
        		   $fHeight=$height;
            	}elseif($product_id=='3' || $product_id=='4'){ 
            	    //sup//tss
            	     $fwidth=$width;
            	    $fHeight=$height+$add_val;
            	   $film_size=$fHeight;
            	}else{
            	    $fwidth=$width;
            	    $fHeight=$height;
            	}
    	
      // printr($film_size);
      // echo $thickness.'*'.$product_gsm.'*'.$fwidth.'*'.$fHeight.'*'.$no_of_pouch.'++'.$add_val.'+++'.$product_id.'<br>';
         $total_output=$this->numberFormate(((($thickness*$product_gsm*$fwidth*$fHeight*$no_of_pouch)/1000)/$print_type),"3");
    	$return = array();
    	$return = array(
    	
    		'total_output'	=> $total_output,
    		'film_size'=> $film_size,
    		
    	);
    //	printr($return);
           return  $return;
        
    }
    
	public function getjob_report_cal_job($job_id){
	    
    	    $job_details=$this->getJObName($job_id);
    		$job_material=$this->getLayerMakeMaterialDetails($job_id);
    		$size = $this->getSize($job_details['size_pro']);
    		$html='';
    		if($job_name['country_id']!='0'){ 
		
   // printr($job_details);
		$country=$this->getCountryName($job_details['country_id']);
    
    		if($country!=''){ $country_name='<b> Country Name :-</b>  '.$country['country_name'].'';
    		}else{$country_name	='';	}
    		}

    	
            $width=$height=$gusset=0;
                	if(!empty($size)){
                	        $width  =$size['width'];
                	        $height=$size['height'];
                	        $gusset=$size['gusset'];
            	    }else{
            	          $width  =$job_details['width'];
            	          $height=$job_details['height'];
            	          $gusset=$job_details['gusset'];
            	    }  
            	   // printr($job_details['sealing']);
            $formula=$this->formulaHeightWidthGusset($height,$width,$gusset,$job_details['product'],$job_details['sealing'],$job_details['print_type']);
          // printr($formula);
            
            
            
            if(!empty($job_material)){
                
                foreach($job_material as $material){
                    
                  //echo $material['product_thickness'].'*'.$material['product_gsm'].'*'.$formula['width'].'*'.$formula['height'].'*'.$job_details['no_of_pouch'].'<br>';
                 
                 if($material['layer_id']!='1' && $material['layer_id']!='3'){
                     $add_val=0.010;
                 }else if($material['layer_id']!='2' && $material['layer_id']!='1'){
                     $add_val=0.020;
                 }
                    $layer[$material['layer_id']]=$this->getLayerformula($material['product_thickness'],$material['product_gsm'],$formula['width'],$formula['height'],$job_details['no_of_pouch'],$add_val,$job_details['product'],$job_details['print_type']);
                    
                }
                
            }
            
               // printr($layer);
            	$html='	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div class="panel-body font_medium" id="print_div" style="font-size: 20px; page-break-before: always;" >';
	        // $html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;"><b><h4> JOB REPORT</h4></b>'; 
	         $html .='<div  style="font-size: 14px;"><div style="text-align:center;"><b>JOB REPORT </b>';
			$html.='</div>

			<div class="div_first" style=" width: 100%;float: left; font-size: 12px;">
							<table class="table" style="font-size: 12px;">
							<tbody>
							<tr>
						
							<td style="vertical-align: top;width: 50%;"><div align="center">
								<b>Job No :</b>'.ucwords($job_details['job_no']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								 '.$country_name.'  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Pouch :</b>'.$job_details['product_name'].'&nbsp;&nbsp;&nbsp;&nbsp; '.$volume.'&nbsp;&nbsp; ('.$width.'x'.$height.'x'.$gusset.')</div>
							</td>
							<td style="vertical-align: top;width: 50%;">
							<div align="center"><b> Job Name :-</b> '.ucwords($job_details['job_name']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Product Name :-</b> '.ucwords($job_details['product_name']).'</div>	
							</td>
							</tr>
							</tbody>
							</table>
							<table class="table"  style="font-size: 10px;">
							<tbody>
							<tr>';
							if(!empty($job_material)){
							    foreach($job_material as $job_material){
							        $html.='<td><div align="center">
								<b> Layer : '.$job_material['layer_id'].' => '.$job_material['product_name'].'</b> </div>
							</td>';
							    }
							}
						
						$html.='
						
							</tr>
							</tbody>
							</table></div></div>';
							
				$html .='<div  style="font-size: 12px;"><div style="text-align:center;"><b>PRINTING  DETAILS</b>';
				$html.='</div>

						<div class="div_first" style=" width: 100%;float: left;  font-size: 12px;">
						';
						$html.='<table class="table" style=" width: 100%; font-size: 12px;" >
							<tbody>
							<tr>
    							<td><b>Layer</b></td>
    							<td><b>Film Size</b></td>
    							<td><b>Input Qty (kgs)</b></td>
    							<td><b>Ink  /Adhesive Used (kgs)</b></td>
    							<td><b>Output Qty (kgs)</b></td>
    							<td><b>Print Wastage</b></td>
    							<td><b>Total O/P Qty</b></td>
							</tr>
					
    						';
    							    if(!empty($layer)){
    							         $wastage_kg=0;
    							         $total_op=0;
    							        foreach($layer as $key=>$data){
    							          //  printr($data);
    							         //   printr($key);
    							              
    							       
    							            if($key=='1'){
    							                  $ink='2';
    							                 $wastage='5';
    							                 $wastage_kg=(($data['total_output']+$ink)*$wastage/100);
    							                 $total_op=(($data['total_output']+$ink)-$wastage_kg);
                    						  // printr($wastage_kg);
                    						  $html.='	<tr>';
                    							$html.='<td>'.$key.'</td>';
                    							$html.='<td>'.($data['film_size']*1000).' mm</td>';
                    						     	$html.='<td>'.$data['total_output'].' kgs</td>';
                    						      	$html.='<td>'.$ink.' kgs</td>';
                    						       	$html.='<td>'.($data['total_output']+$ink).'(kgs)</td>';
                    						        $html.='<td>'.$wastage_kg.'</td>';
                    						        $html.='<td>'.$total_op.'</td>';
                                                $html.='	</tr>';
    							            }else{
    							                
    							                //printr($total_op);
    							                    $f_total_op=$total_op;
    							                  $wastage_kg=(($data['total_output']+$f_total_op)*$wastage/100);
    							                  $total_op=(($data['total_output']+$f_total_op)-$wastage_kg);
    							                $html.='	<tr>';
    							                	$html.='<td>'.$key.'</td>';
                    							    $html.='<td>'.($data['film_size']*1000).' mm</td>';
                    						     	$html.='<td>'.$data['total_output'].' kgs</td>';
                    						      	$html.='<td>'.$ink.' kgs</td>';
                    						       	$html.='<td>'.($data['total_output']+$f_total_op).'(kgs)</td>';
                    						        $html.='<td>'.$wastage_kg.'</td>';
                    						        $html.='<td>'.$total_op.'</td>';
                    						        $html.='	</tr>';
    							            }
    							            
    							        }
    							    }
    				    	$html.='
    							</tbody>
    							</table>';
							$html.='</form>';

	      //  printr($html);
	      return $html;
	          
	}
	
	
}
?>