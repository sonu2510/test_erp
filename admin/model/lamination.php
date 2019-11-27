 <?php
//==>sonu
class lamination extends dbclass{
	public function getOperator()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM   user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id  AND e.user_type='9'";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false; 
		}
	}
		public function getOperator_name($id)
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.employee_id='".$id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['operator_name'];
		}else{
			return false;
		}
	}
	public function getMachine()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "machine_master` WHERE is_delete = 0 AND production_process_id LIKE'%3%'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getRollName()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "roll_name` WHERE is_delete = 0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getRollNo()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_inward` WHERE slt_is_delete='0' AND  is_delete = 0 AND status=1 AND roll_no!=''";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function addLamination($data)
	{
		//	printr($data);//die;
	
	
		$layers_details=array();
		$total_details=array();

		if(isset($data['lamination_id'])){
			$lamination_id=$data['lamination_id'];
		}else{
			
				if((isset($data['remark'])) && (!empty($data['remark']))){
					$remark=$data['remark'];
				}else{
					$remark=$data['remark_lamination'];
				}
				
			
				$this->query("INSERT INTO `" . DB_PREFIX . "lamination` SET  lamination_no = '" .$data['lamination_no']. "',printing_status='".$data['printing_status']."',lamination_date = '" .$data['lamination_date']. "',job_no='".$data['job_name_text']."',job_id='".$data['job_id']."',job_name='".$data['job_name']."',start_time='".$data['start_time']."',end_time='".$data['end_time']."',machine_id = '" .$data['machine_id']. "',shift = '" .$data['shift']. "' ,status = '1',remark='".$remark."',remark_lamination='".$data['remark_lamination']."',pass_no = '" .$data['pass_no']. "', date_added = NOW(),date_modify = NOW(),is_delete=0,added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'");
				$lamination_id = $this->getLastId();
			}
		$layers_details=$data['layers_details'];
			//printr($layers_details);die;
			if(!isset($data['printing_details']))				
				$printing_details='';
			else 
				$printing_details=$data['printing_details'];
				
				
				
				
				if($data['printing_status']=='1'){
					if(!isset($data['lamination_id'])){
				
								if(!empty($printing_details)){  
									
									$this->query("UPDATE printing_job SET lamination_status='1' WHERE job_id='".$printing_details['f_roll_code_id']."'");
									$this->query("INSERT INTO  lamination_layer SET lamination_id ='".$lamination_id."',layer_no ='1',job_id='".$data['job_id']."',roll_used='".$printing_details['f_roll_used']."',printing_status='".$printing_details['printing_status']."', total_output = '0',operator_id = '" .$printing_details['f_operator_id']. "',junior_id = '" .$printing_details['f_junior_id']. "',total_input = '" .$printing_details['f_input_qty']. "',plain_wastage = '0',layer_date='".$data['lamination_date']."',print_wastage = '0',total_wastage = '0',wastage_per='0', date_added = NOW(),date_modify = NOW(),is_delete=0");
										$lamination_layer_id = $this->getLastlamination_layerId();
							
							
									$this->query("INSERT INTO   lamination_roll_detail SET lamination_id ='".$lamination_id."',layer_no ='1',lamination_layer_id='".$lamination_layer_id."',roll_no_id = '" .$printing_details['f_roll_code_id']. "',roll_name_id = '".$printing_details['f_roll_name_id']."',film_size ='".$printing_details['f_film_size']."',input_qty = '" .$printing_details['f_input_qty']. "',output_qty = '" .$printing_details['f_total_output']. "', date_added = NOW(),date_modify = NOW(),is_delete=0");
									
								}
						}		
					
						$roll_input=0;
						$roll_output=0;
						foreach($data['roll_details'] as $layer_no)
						{
							$roll_input=$roll_input+$layer_no['input_qty'];
							$roll_output=$roll_output+$layer_no['output_qty'];
							//	printr($roll_output.'===='.$layer_no['output_qty']);
						}
						
			


						$total_details=$this->getRollDetails_total($lamination_id);
				
							if(isset($total_details['total_output'])){
								$total_details['total_output']=$total_details['total_output']+$roll_output;
							}else{
								$total_details['total_output']=$roll_output;
							}
							
								$total_details['total_input']=$roll_input;
						
						
			
						$this->query("INSERT INTO  lamination_layer SET lamination_id ='".$lamination_id."',layer_no ='".$layers_details['layers']."',job_id='".$data['job_id']."',roll_used='".$layers_details['roll_used']."',operator_id='".$layers_details['operator_id']."',junior_id='".$layers_details['junior_id']."',operator_shift='".$layers_details['operator_shift']."',layer_date='".$layers_details['layer_date']."',plain_wastage = '" .$layers_details['plain_wastage']. "',print_wastage = '" .$layers_details['print_wastage']. "',total_wastage = '" .$layers_details['total_wastage']. "',wastage_per='".$layers_details['wastage_per']."',printing_status='0',total_input='".$total_details['total_input']."',total_output='".$total_details['total_output']."' , date_added = NOW(),date_modify = NOW(),is_delete=0");
						$lamination_layer_id = $this->getLastlamination_layerId();
				
						
						foreach($data['roll_details'] as $layer_no)
						{
							
							
						
					
								$this->query("INSERT INTO   lamination_roll_detail SET lamination_id ='".$lamination_id."',layer_no ='".$layers_details['layers']."',lamination_layer_id='".$lamination_layer_id."',roll_no_id = '" .$layer_no['roll_no_id']. "',roll_name_id = '".$layer_no['roll_name_id']."',film_size ='".$layer_no['film_size']."',input_qty = '" .$layer_no['input_qty']. "',output_qty = '" .$layer_no['output_qty']. "',balance_qty = '" .$layer_no['balance_qty']. "', date_added = NOW(),date_modify = NOW(),is_delete=0");
						
						
							$total_qty=$this->getInputQty($layer_no['roll_no_id']);
								$c_qty=$total_qty['bal_qty']-$layer_no['input_qty'];
								$final_qty=$c_qty+$layer_no['balance_qty'];
								//printr($final_qty);
								$sql_update = "UPDATE `" . DB_PREFIX . "product_inward` SET  qty= '".$final_qty."', date_added = NOW() WHERE product_inward_id = '".$layer_no['roll_no_id']."' ";
								$this->query($sql_update);
						}
						
						
					}else{



					//	printr($layers_details);die;
						
						$roll_input=0;
						$roll_output=0;
						foreach($data['roll_details'] as $layer_no)
						{
							$roll_input=$roll_input+$layer_no['input_qty'];
							$roll_output=$roll_output+$layer_no['output_qty'];
							//	printr($roll_output.'===='.$layer_no['output_qty']);
						}
						
					
				
						$total_details=$this->getRollDetails_total($lamination_id);
				
							if(isset($total_details['total_output'])){
								$total_output=$total_details['total_output']+$roll_output;
									//printr($total_details['total_output'].'===='.$roll_output);
							}else{
								$total_output=$roll_output;
							}
							
								$total_input=$roll_input;
							
					
						$this->query("UPDATE job_master SET lamination_status='1' WHERE job_id='".$data['job_id']."'");
						$this->query("INSERT INTO  lamination_layer SET lamination_id ='".$lamination_id."',layer_no ='".$layers_details['layers']."',job_id='".$data['job_id']."',roll_used='".$layers_details['roll_used']."',operator_id='".$layers_details['operator_id']."',junior_id='".$layers_details['junior_id']."',operator_shift='".$layers_details['operator_shift']."',layer_date='".$layers_details['layer_date']."',plain_wastage = '" .$layers_details['plain_wastage']. "',print_wastage = '" .$layers_details['print_wastage']. "',total_wastage = '" .$layers_details['total_wastage']. "',wastage_per='".$layers_details['wastage_per']."',printing_status='0',total_input='".$total_input."',total_output='".$total_output."' , date_added = NOW(),date_modify = NOW(),is_delete=0");
						$lamination_layer_id = $this->getLastlamination_layerId();
					
						
						foreach($data['roll_details'] as $layer_no)
						{
					
							$this->query("INSERT INTO   lamination_roll_detail SET lamination_id ='".$lamination_id."',layer_no ='".$layers_details['layers']."',lamination_layer_id='".$lamination_layer_id."',roll_no_id = '" .$layer_no['roll_no_id']. "',roll_name_id = '".$layer_no['roll_name_id']."',film_size ='".$layer_no['film_size']."',input_qty = '" .$layer_no['input_qty']. "',output_qty = '" .$layer_no['output_qty']. "',balance_qty = '" .$layer_no['balance_qty']. "', date_added = NOW(),date_modify = NOW(),is_delete=0");
							
							$total_qty=$this->getInputQty($layer_no['roll_no_id']);
								$c_qty=$total_qty['bal_qty']-$layer_no['input_qty'];
								$final_qty=$c_qty+$layer_no['balance_qty'];
								//printr($final_qty);
								$sql_update = "UPDATE `" . DB_PREFIX . "product_inward` SET  qty= '".$final_qty."', date_added = NOW() WHERE product_inward_id = '".$layer_no['roll_no_id']."' ";
								$this->query($sql_update);
							
							
						}
						
					}

	
		return $lamination_id;

		}

	
	   	
			
	public function getlatestjobid()
	{
		$sql = "SELECT lamination_id FROM `" . DB_PREFIX . "lamination` WHERE is_delete = 0 ORDER BY  lamination_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['lamination_id'];
		}else{
			return false;
		}
	}
	public function getTotalLamination($filter_data=array(),$job_date='')
	{
		$con='';
		if($job_date!='')
		{
			$con="AND lamination_date='".$job_date."'";
		}
		
		$sql = "SELECT COUNT(*) as total FROM  lamination as pb,  job_master as jm WHERE pb.is_delete = 0 AND  pb.job_id=jm.job_id $con ";
		//printr($filter_data);
		if(!empty($filter_data)){
			if(!empty($filter_data['job_name'])){
				$sql .= " AND jm.job_name LIKE '%".$filter_data['job_name']."%' ";		
			}
		/*	if(!empty($filter_data['operator_id'])){
				$sql .= " AND pb.operator_id = '".$filter_data['operator_id']."' "; 	
			}*/
			if(!empty($filter_data['job_date'])){
				$sql .= " AND pb.	lamination_date = '".$filter_data['job_date']."' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY lamination_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
		
		if (isset($option['start']) || isset($option['limit'])) {
			if ($option['start'] < 0) {
				$option['start'] = 0;
			}			
			if ($option['limit'] < 1) {
				$option['limit'] = 20;
			}	
			$sql .= " LIMIT " . (int)$option['start'] . "," . (int)$option['limit'];
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getLamination($option,$filter_data=array(),$job_date='')
	{
		$con='';
		if($job_date!='')
		{
			$con="AND pb.lamination_date='".$job_date."'";
		}
		
		$sql = "SELECT pb.*,jm.job_name FROM  lamination as pb, employee as om, job_master as jm WHERE pb.is_delete = 0 AND pb.job_id=jm.job_id $con GROUP 	by lamination_id ";
		
		//printr($sql);die;
		if(!empty($filter_data)){
			if(!empty($filter_data['job_name'])){
				$sql .= " AND jm.job_name LIKE '%".$filter_data['job_name']."%' ";		
			}
			/*if(!empty($filter_data['operator_id'])){
				$sql .= " AND pb.operator_id = '".$filter_data['operator_id']."' "; 	
			}*/
			
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY lamination_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
		if (isset($option['start']) || isset($option['limit'])) {
			if ($option['start'] < 0) {
				$option['start'] = 0;
			}			
			if ($option['limit'] < 1) {
				$option['limit'] = 20;
			}	
			$sql .= " LIMIT " . (int)$option['start'] . "," . (int)$option['limit'];
		}
		//echo $sql;die;
		$data = $this->query($sql);
		
		return $data->rows;
		
		
	}
	public function getLaminationDetail($lamination_id,$cond='')
	{
		
	
		
		$sql = "SELECT l.*,mm.machine_name,jm.job_name as job_num_id FROM  lamination as l,lamination_layer as ll, employee as om,machine_master as mm,job_master as jm WHERE l.is_delete = 0 AND l.lamination_id = '".$lamination_id."' AND  l.machine_id = mm.machine_id AND l.job_id=jm.job_id AND l.lamination_id=ll.lamination_id";
		
		$data = $this->query($sql);		

			
		$sql2 = "SELECT * FROM  lamination_layer  WHERE  lamination_id = '".$lamination_id."' AND is_delete = '0'";	
		$layer_data = $this->query($sql2);
	
		$sql2 = "SELECT * FROM  lamination_roll_detail  WHERE  lamination_id = '".$lamination_id."' AND is_delete = '0'";	
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
	
		$lamination_array =array();
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

		//printr($lamination_array);
		return $lamination_array;
	
	}
	public function updateLamination($data)
	{
		//printr($data);//die;
			$lamination_id=$data['lamination_id'];
			
			
			
			
			
			if((isset($data['remark'])) && (!empty($data['remark']))){
				$remark=$data['remark'];
			}else{
				$remark=$data['remark_lamination'];
			}
		
			$this->query("UPDATE `" . DB_PREFIX . "lamination` SET  lamination_no = '" .$data['lamination_no']. "',lamination_date = '" .$data['lamination_date']. "',job_no='".$data['job_name_text']."',job_id='".$data['job_id']."',job_name='".$data['job_name']."',start_time='".$data['start_time']."',end_time='".$data['end_time']."',operator_id = '" .$data['operator_id']. "',machine_id = '" .$data['machine_id']. "',shift = '" .$data['shift']. "' ,status = '1',remark='".$remark."',remark_lamination='".$data['remark_lamination']."',pass_no = '" .$data['pass_no']. "', date_added = NOW(),date_modify = NOW(),is_delete=0,added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE  lamination_id='".$lamination_id."'");
			$lamination_layer_id=$data['lamination_layer_id'];
			$layers_details=$data['layers_details'];
			
				
			foreach($data['roll_details'] as $layer_no)
			{
				//printr($layer_no);
				if(isset($layer_no['lamination_roll_detail_id']))
				{
					$this->query("UPDATE lamination_roll_detail SET lamination_id ='".$lamination_id."',layer_no ='".$layers_details['layers']."',lamination_layer_id='".$lamination_layer_id."',roll_no_id = '" .$layer_no['roll_no_id']. "',roll_name_id = '".$layer_no['roll_name_id']."',film_size ='".$layer_no['film_size']."',input_qty = '" .$layer_no['input_qty']. "',output_qty = '" .$layer_no['output_qty']. "',balance_qty = '" .$layer_no['balance_qty']. "', date_added = NOW(),date_modify = NOW(),is_delete=0 WHERE lamination_roll_detail_id='".$layer_no['lamination_roll_detail_id']."'" );
				}else{
					$this->query("INSERT INTO lamination_roll_detail SET lamination_id ='".$lamination_id."',layer_no ='".$layers_details['layers']."',lamination_layer_id='".$lamination_layer_id."',roll_no_id = '" .$layer_no['roll_no_id']. "',roll_name_id = '".$layer_no['roll_name_id']."',film_size ='".$layer_no['film_size']."',input_qty = '" .$layer_no['input_qty']. "',output_qty = '" .$layer_no['output_qty']. "',balance_qty = '" .$layer_no['balance_qty']. "', date_added = NOW(),date_modify = NOW(),is_delete=0 " );
				}
			}
						$roll_input=0;
						$roll_output=0;
						foreach($data['roll_details'] as $layer_no)
						{
						  //  printr($layer_no);
							$roll_input=$roll_input+$layer_no['input_qty'];
							$roll_output=$roll_output+$layer_no['output_qty'];
						}
					
		
	//	printr($roll_input);
	//	printr($roll_output);
					
			$total_details=$this->getRollDetails_total($lamination_id);
	//	printr($total_details);
				if(isset($total_details['total_output'])){
					$total_details['total_output']=$total_details['total_output']+$roll_input;
				}else{
					$total_details['total_output']=$roll_output;
				}
				if(isset($total_details['total_input'])){
					$total_details['total_input']=$total_details['total_input']+$roll_input;
				}else{
					$total_details['total_input']=$roll_input;
				}
						
			
			
			//printr($layers_details);die;
			$this->query("UPDATE  lamination_layer SET lamination_id ='".$lamination_id."',layer_no ='".$layers_details['layers']."',job_id='".$data['job_id']."',roll_used='".$layers_details['roll_used']."',layer_date ='".$layers_details['layer_date']."',plain_wastage = '" .$layers_details['plain_wastage']. "',print_wastage = '" .$layers_details['print_wastage']. "',total_wastage = '" .$layers_details['total_wastage']. "',wastage_per='".$layers_details['wastage_per']."',total_output='".$total_details['total_output']."',total_input='".$total_details['total_input']."', date_added = NOW(),date_modify = NOW(),is_delete=0 WHERE lamination_layer_id='".$lamination_layer_id."'");
//	die;
	}
 
	public function updateRollStatus($id,$status)
	{
		$sql = "UPDATE `" . DB_PREFIX . "lamination` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE lamination_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data)
	{
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "lamination` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE lamination_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "lamination` SET is_delete = '1', date_modify = NOW() WHERE lamination_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getDateWiseJob($filter_data,$option=array())
	{
		$sql = "SELECT lamination_date FROM lamination WHERE is_delete = 0 ";
			 
			if(!empty($filter_data['job_date'])){
					$sql .= " AND lamination_date = '".$filter_data['job_date']."' "; 	
				}
				$sql .= "GROUP BY lamination_date";
			
				if (isset($filter_data['sort'])) {
		        	$sql .= " ORDER BY " . $filter_data['sort'];	
        		} else {
        			$sql .= " ORDER BY lamination_id";	
        		}
        
        		if (isset($filter_data['order']) && ($filter_data['order'] == 'ASC')) {
        			$sql .= " ASC";
        		} else {
        			$sql .= " DESC";
        		}
        		if (isset($option['start']) || isset($option['limit'])) {
        			if ($option['start'] < 0) {
        				$option['start'] = 0;
        			}			
        			if ($option['limit'] < 1) {
        				$option['limit'] = 20;
        			}	
        			$sql .= " LIMIT " . (int)$option['start'] . "," . (int)$option['limit'];
        		}		
				
			$data = $this->query($sql);
		//printr($data);
			return $data->rows;
		
	}
	public function getInputQty($roll_val)
	{
			
		//	$sql = "SELECT * FROM printing_job WHERE roll_no_id = '".$roll_val."' AND is_delete=0 ORDER BY job_id DESC ";
			//$data = $this->query($sql);
			
			$sql2='SELECT * FROM product_inward as pi,product_item_info as pio WHERE pi.product_inward_id="'.$roll_val.'" AND pi.product_item_id= pio.product_item_id';
			$data2 = $this->query($sql2);
			///printr($data2);
			$bal_qty=$film_nm='';
			//if($data->num_rows)
				
				
			if($data2->num_rows)
				$film_nm=$data2->row['product_name'];
				$film_size =$data2->row['inward_size'];
				$bal_qty=$data2->row['qty'];
			
			$data_return = array(
								 'film_name'=>$film_nm,
								 'film_size'=>$film_size,
								 'bal_qty'=>$bal_qty);
			//if($data->num_rows){
				return $data_return;
			//}else{
				//return false;
			//}
	}
	public function getPrintting_details($job_id)
	{
		
		$sql = "SELECT pb.*,mm.machine_name,jm.job_name as job_num_id ,om.first_name ,po.* FROM printing_job as pb,employee as om, printing_operator_details as po, machine_master as mm,job_master as jm WHERE pb.is_delete = 0 AND pb.job_id = '".$job_id."'  AND pb.job_id=po.printing_id AND pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id AND po.operator_id=om.employee_id ";
		
	

		$data = $this->query($sql);
		//printr($data);
		
		$sql2 = "SELECT *,SUM(output_qty)as total_output,SUM(input_qty)as total_input FROM  printing_roll_details as pd, product_inward as pi   WHERE pd.roll_no_id=pi.product_inward_id AND  pd.job_id = '".$job_id."' AND pd.is_delete = '0'";	
		$roll_data = $this->query($sql2);
		$total_output=$roll_data->row['total_output'];
		$roll_used=$data->row['roll_used'];
		if( $data->row['slitting_status']==1){
				
				$sql_sl = "SELECT * FROM slitting  WHERE is_delete = 0 AND roll_code_id='".$job_id."' ";
				$data_sl = $this->query($sql_sl);
				$total_output=$data_sl->row['output_qty'];
				
				$sql_sl_roll = "SELECT  COUNT(*) as total FROM slitting_process  WHERE is_delete = 0 AND roll_code_id='".$job_id."' ";
				$data_sl_r = $this->query($sql_sl_roll);
				//printr($data_sl_r);
				$roll_used=$data_sl_r->row['total'];		
		}
		
		
	//	printr($total_output);
		//printr($roll_used);
		
		
		
		
		$printing_array =array();
            $printing_array = array(             
              
                'job_name_id' => $data->row['job_name_id'],
                'job_name_text' => $data->row['job_name_text'],
                'job_name' => $data->row['job_name'],                
				'roll_used' => $roll_used,	
				'roll_code' => $data->row['roll_code'],	
				'total_output'=>$total_output,							
				'roll_name_id' => $roll_data->row['roll_name_id'],				
				'roll_size' => $data->row['roll_size'],				
				'operator_id' => $data->row['operator_id'],				
				'junior_id' => $data->row['junior_id'],
				'print_wastage' => $data->row['print_wastage'],				
				'plain_wastage' => $data->row['plain_wastage'],				
				'total_wastage' => $data->row['total_wastage'],				
				'wastage_per' => $data->row['wastage_per'],			
               				
						
               
               
            );

	//	printr($printing_array);
		return $printing_array;
	}
	
	public function getLayerMakeMaterialDetails($job_id){
		$sql ="SELECT * FROM  job_layer_details as j ,product_item_info as p WHERE j.job_id = '".$job_id."' AND j.layer_id='1' AND p.product_item_id =j.product_item_layer_id";
		$data = $this->query($sql);
			//printr($data);
			if ($data->num_rows) {
				return $data->row;
			} else {
				return false;
			}
	}
	public function getLayerJObMaterialDetails($job_id){
		$sql ="SELECT * FROM  job_layer_details as j ,product_item_info as p WHERE j.job_id = '".$job_id."' AND p.product_item_id =j.product_item_layer_id";
		$data = $this->query($sql);
			//printr($data);
			if ($data->num_rows) {
				return $data->rows;
			} else {
				return false;
			}
	}
	public function getLayerJObMaterialD($job_id,$layer_id){
		$sql ="SELECT * FROM  job_layer_details as j ,product_item_info as p WHERE j.job_id = '".$job_id."' AND p.product_item_id =j.product_item_layer_id";
		$data = $this->query($sql);
			//printr($data);
			if ($data->num_rows) {
				return $data->row['product_item_layer_id'];
			} else {
				return false;
			}
	}
	
	
	
	public function getJob_layer_details($job_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "job_master` WHERE is_delete = 0 AND job_id = '".$job_id."'";
		
		//echo $sql;die;
		$data = $this->query($sql);
		//printr($data);
	
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function viewlamination_report($lamination_id)
	{
		
		$lamination_details = $this->getLaminationDetail($lamination_id);
		$layer_details= $this->getLayerDetails($lamination_id);
		//$roll_details= $obj_lamination->getRollDetails($lamination_id,$layer_details['lamination_layer_id']);	
//printr($lamination_details);
		$html = '';
	              $html .='<div  style="font-size: 18px;" ><div style="text-align:center;border: "><b>LAMINATION DETAILS</b>';
					$html.='</div>

						<div class="div_first" style=" width: 100%;float: left; font-size: 18px;">';
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
								<br><b>Shift :</b>   '.ucwords($lamination_details['shift']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b>JOB NO :</b>   '.ucwords($lamination_details['job_no']).'('.$lamination_details['job_name'].')
							
							</td>
								</tbody>
							</tr>
							</table>';

				$html .='<div class="div_first" style="width: 100%; float: left;  ">';

					$html.='	<table class="table" style="font-size: 14px;">
								<tbody>
								<tr>
									<td width="5%"><div align="center"><b>Layer No.</b></div></td>
									<td width="10%"><div align="center"><b>Operator Details</b></div></td>
									<td width="5%"><div align="center"><b>Roll Used</b></div></td>
									<td width="20%"><div align="center"><b>Roll Detail</b></div></td>';
							
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
						
										$roll_details= $this->getRollDetails($lamination_details['lamination_id'],$layer['lamination_layer_id']);
						
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
								
											 	$uni_arr_lami = array_unique($lamination_roll);
		                                        $u_arr_lami = implode(',',$uni_arr_lami);   
											    
											}
							

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
							
								$html.='</tbody>
								</table></div></div></div>';
		
			return $html;
	
		}
	
		public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	
	 public function getLastId() {
        $sql = "SELECT lamination_id FROM  lamination ORDER BY lamination_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['lamination_id'];
        } else {
            return false;
        }
    } 
	public function getLastlamination_layerId() {
        $sql = "SELECT lamination_layer_id FROM  lamination_layer ORDER BY lamination_layer_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['lamination_layer_id'];
        } else {
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
	public function getRollDetails_total($lamination_id) {
		
	//	printr($lamination_id);die;
		/*$sql_layer = "SELECT lamination_layer_id FROM  lamination_layer WHERE  is_delete='0' AND lamination_id='".$lamination_id."'ORDER BY lamination_layer_id";
        $data_layer = $this->query($sql_layer);
		  
        $layer="";
		  if ($data_layer->num_rows) {
				foreach($data_layer->rows as $layer){
					//printr($layer);
					
						$layer_p[] = $layer['lamination_layer_id'];
				}
				if(!empty($layer_p)){
					$layer_p_array= implode(',',$layer_p);
						if(!empty($layer_p_array)){
						$layer="AND lr.lamination_layer_id IN(".$layer_p_array.")"; 
						}else{
							$layer="";
						}

				}
		  }*/
		
      //  $sql ="SELECT *,sum(lr.input_qty) as total_input,sum(lr.output_qty) as total_output FROM   lamination_roll_detail as lr ,lamination_layer as l  WHERE l.is_delete = '0' AND lr.lamination_id='".$lamination_id."' AND l.lamination_id=lr.lamination_id $layer Group by lr.lamination_roll_detail_id";
     
    $sql ="SELECT *,sum(lr.input_qty) as total_input,sum(lr.output_qty) as total_output  FROM   lamination_roll_detail as lr ,lamination as l  WHERE l.is_delete = '0' AND lr.lamination_id='".$lamination_id."' AND l.lamination_id=lr.lamination_id  ";
        
	//echo $sql;
	
		$data = $this->query($sql);
			//	printr($layer_p_array);//die;
			//	printr($data);//die;
        if ($data->num_rows) {
            return $data->row;
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
	public function getLayerDetails($lamination_id) {
        $sql ="SELECT * FROM   lamination_layer  WHERE is_delete = '0' AND lamination_id='".$lamination_id."' ";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }
	public function getLayer($lamination_layer_id) {
        $sql ="SELECT * FROM   lamination_layer  WHERE is_delete = '0' AND lamination_layer_id='".$lamination_layer_id."' ";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row;
        } else {
            return false;
        }
    }
	
	public function remove ($lamination_layer_id)
	{		//printr($lamination_layer_id);die;
			$sql = "UPDATE `" . DB_PREFIX . "lamination_layer` SET is_delete = '1',  date_modify = NOW() WHERE lamination_layer_id = '".$lamination_layer_id."' ";		
			//echo $sql;die;
	   		$this->query($sql);
		
	}
	public function getRollCode()
	{
	
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_job` WHERE is_delete = 0 AND status=1 AND roll_code!=''";
		//printr($sql);
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		
			}
	}
	public function getRollCodeName($id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_job` WHERE is_delete = 0 AND status=1 AND job_name ='".$id."' AND 	roll_code_status = '1'";
		//printr($sql);
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row['roll_code'];
		}else{
			return false;
		
			}
	}
	public function getRollNoDetails($product_item_layer_id,$layer_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_inward`WHERE slt_is_delete='0' AND is_delete = 0 AND status=1 AND roll_no!=''  ";
		//echo $sql;
		$data = $this->query($sql); 
		if($data->num_rows){
			return $data->rows;
		}else{
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
	
	public function removeInvoice($lamination_layer_id,$lamination_id)
	{
			
			$sql = "DELETE FROM `lamination_layer` WHERE  lamination_layer_id = '".$lamination_layer_id."' AND lamination_id = '".$lamination_id."' ";
			//echo $sql;die;
			$this->query($sql);
			
			$sql_roll = "DELETE FROM `lamination_roll_detail` WHERE  lamination_layer_id = '".$lamination_layer_id."'";
			   $this->query($sql_roll);
			
	}
	public function remove_roll($lamination_roll_detail_id)
	{
		$sql = "DELETE FROM `lamination_roll_detail` WHERE  lamination_roll_detail_id = '".$lamination_roll_detail_id."' ";
	//	echo $sql;die;
	    $this->query($sql);
	}
	public function addroll_code($data)
	{
		
		//printr($data);die;
		$sql = "UPDATE `".DB_PREFIX."lamination` SET roll_code='".$data['roll_code']."', roll_size='".$data['roll_size']."' , roll_code_status='1'WHERE lamination_id='".$data['lamination_id']."'";		
		//echo $sql;	die;
		$data=$this->query($sql);		
			
			
	}
	
	public function updatepass($l_id,$pass_val,$op_id,$m_id,$remark,$remark_lamination)
	{
		
		if((isset($remark)) && (!empty($remark))){
			$remark=$remark;
		}else{
			$remark=$remark_lamination;
		}
		
		$sql = "UPDATE `" . DB_PREFIX . "lamination` SET pass_no='".$pass_val."',operator_id='".$op_id."',machine_id='".$m_id."',remark_lamination='".$remark_lamination."',remark='".$remark."',  date_modify = NOW() WHERE lamination_id = '".$l_id."' ";
	//	echo $sql;die;
	   $this->query($sql);
	}

	
}
?>