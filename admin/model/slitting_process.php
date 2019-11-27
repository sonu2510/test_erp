<?php
//==> sonu
class slitting extends dbclass{
	public function getOperator()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name  FROM user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id  AND e.user_type=10";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getMachine()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "machine_master` WHERE is_delete = 0 AND production_process_id LIKE'%5%'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}

	public function getJuniorOperator()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type=10";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
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
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_inward` WHERE  slt_is_delete='0' AND is_delete = 0 AND status=1 AND roll_no!=''";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getlatestNo()
	{
		$sql = "SELECT product_inward_id FROM `" . DB_PREFIX . "product_inward` WHERE  slt_is_delete='0' AND is_delete = '0' ORDER BY product_inward_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['product_inward_id'];
		}else{
			return false;
		}	
	}
	public function addSlitting($data)
	{
	//	printr($data);
	
		if((isset($data['remark'])) && (!empty($data['remark']))){
			$remark=$data['remark'];
		}else{
			$remark=$data['remarks_slitting'];
		}
		
		
		$sql = "INSERT INTO `" . DB_PREFIX . "slitting` SET  slitting_no = '" .$data['slitting_no']. "',slitting_date = '" .$data['slitting_date']. "',job_id = '" .$data['job_id']. "',job_name = '" .$data['job_name']. "',job_no = '" .$data['job_name_text']. "',operator_id = '" .$data['operator_id']. "',junior_id = '" .$data['junior_id']. "',machine_id = '" .implode(",",$data['machine_id']). "',shift = '" .$data['shift']. "',remarks_slitting = '" .$data['remarks_slitting']. "',remark='".$remark."',roll_code_id='".$data['roll_code_id']."',slitting_status='".$data['slitting_status']."',input_qty='".$data['input_qty']."',output_qty='".$data['output_qty']."',setting_wastage='".$data['setting_wastage']."',top_cut_wastage='".$data['top_cut_wastage']."',lamination_wastage='".$data['lamination_wastage']."',printing_wastage='".$data['printing_wastage']."',trimming_wastage='".$data['trimming_wastage']."',total_wastage='".$data['total_wastage']."',no_of_roll_i='".$data['no_of_roll_i']."',machine_speed='".$data['machine_speed']."',no_of_roll='".$data['no_of_roll']."',wastage='".$data['wastage']."',date_added = NOW(),date_modify = NOW(),is_delete=0,added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
		$this->query($sql);
		$slitting_id = $this->getLastId();
		
		//update Status
		if($data['slitting_status']==1){
			
			$this->query("UPDATE  lamination SET slitting_status ='1',slitting_id='".$slitting_id."', date_added = NOW(),date_modify = NOW(),is_delete=0 WHERE lamination_id ='".$data['roll_code_id']."'");
			foreach($data['roll_details'] as $roll_no)
								{
							
							//	printr($roll_no);
                                	$this->query("INSERT INTO   slitting_process SET slitting_id ='".$slitting_id."',job_id = '" .$data['job_id']. "',roll_code_id='".$data['roll_code_id']."',roll_code = '" .$roll_no['roll_code']. "',input_qty = '".$roll_no['r_input_qty']."',output_qty = '".$roll_no['r_output_qty']."', date_added = NOW(),date_modify = NOW(),is_delete=0");
								
								}
						
			
		}else if($data['slitting_status']==0){
			$this->query("UPDATE  printing_job SET slitting_status ='1',slitting_id='".$slitting_id."', date_added = NOW(),date_modify = NOW(),is_delete=0 WHERE job_id='".$data['roll_code_id']."'");
		    foreach($data['roll_details'] as $roll_no)
								{
							
							//	printr($roll_no);
                                	$this->query("INSERT INTO   slitting_process SET slitting_id ='".$slitting_id."',job_id = '" .$data['job_id']. "',roll_code_id='".$data['roll_code_id']."',roll_code = '" .$roll_no['roll_code']. "',input_qty = '".$roll_no['r_input_qty']."',output_qty = '".$roll_no['r_output_qty']."', date_added = NOW(),date_modify = NOW(),is_delete=0");
								
								}
						
        	}
		
		
		
		
		//inward roll
		
		else if($data['slitting_status']==2){
			$roll_details = $this->getRoll_details($data['roll_code_id']);
			//printr($roll_details);
			
			
			foreach($data['roll_details'] as $roll_no)
						{
							
							$i_no=$this->getlatestNo();
							$strpad = str_pad($i_no+1,8,'0',STR_PAD_LEFT);
							$inward_no='INWD'.$strpad;
							
						//	printr($data);
							//delete form inward roll
							$sql_update = "UPDATE `" . DB_PREFIX . "product_inward` SET slt_is_delete='1' , date_modify = NOW() WHERE product_inward_id='".$data['roll_code_id']."' ";
							$this->query($sql_update);
				
							// add inward in slitting roll
							$sql_insert = "INSERT INTO `" . DB_PREFIX . "product_inward` SET  roll_no='".$roll_no['roll_code']."',inward_no = '" .$inward_no. "',vender_id = '".$roll_details['vender_id']."',qty ='".$roll_details['qty']."' ,inward_size ='".$roll_no['r_output_qty']."' , product_category_id = '".$roll_details['product_category_id']."', product_item_id = '".$roll_details['product_item_id']."' ,unit_id = '".$roll_details['unit_id']."',sec_unit_id='".$roll_details['sec_unit_id']."',date_added = NOW(),date_modify = NOW(),user_id='".$roll_details['user_id']."',user_type_id='2',is_delete=0,status=1,inward_date='".$roll_details['inward_date']."',manufacutring_date='".$roll_details['manufacutring_date']."',added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
							//echo $sql;
							$this->query($sql_insert);
							$inward_id = $this->getLastIdProductInward();
							
						//	printr($inward_id);die;
							$this->query("INSERT INTO   slitting_process SET slitting_id ='".$slitting_id."',job_id = '" .$data['job_id']. "',roll_code_id='".$inward_id."',roll_code = '" .$roll_no['roll_code']. "',input_qty = '".$roll_no['r_input_qty']."',output_qty = '".$roll_no['r_output_qty']."',p_input_qty = '".$roll_no['r_output_qty']."', roll_size = '".$roll_no['r_output_qty']."',  date_added = NOW(),date_modify = NOW(),is_delete=0");
							
							
							
							
						
						}
						
		
	
			}else{
				//printing_roll & lamination roll
				
				
		 	}
		//	die;
	}
	
	public function getlatestslittingid()
	{
		$sql = "SELECT slitting_id FROM `" . DB_PREFIX . "slitting` WHERE is_delete = 0 ORDER BY  slitting_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['slitting_id'];
		}else{
			return false;
		}
	}
	public function getTotalSlitting($filter_data=array(),$slitting_date='')
	{
		$con='';
		if($slitting_date!='')
		{
			$con="AND slitting_date='".$slitting_date."'";
		}
		
		//$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "slitting` as sl WHERE sl.is_delete = 0 $con";
		$sql = "SELECT COUNT(*) as total,sl.*,CONCAT(om.first_name ,' ', om.last_name) as operator_name,m.machine_name  FROM slitting as sl, employee as om, machine_master as m WHERE sl.is_delete = 0 AND sl.operator_id=om.employee_id AND sl.machine_id = m.machine_id  $con ";
		//printr($filter_data);
		if(!empty($filter_data)){
			
			if(!empty($filter_data['operator_id'])){
				$sql .= " AND sl.operator_id = '".$filter_data['operator_id']."' "; 	
			}
			if(!empty($filter_data['slitting_date'])){
				$sql .= " AND sl.slitting_date = '".$filter_data['slitting_date']."' "; 	
			}
			if(!empty($filter_data['machine_id'])){
				$sql .= " AND sl.machine_id = '".$filter_data['machine_id']."' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY slitting_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
		
		
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getSlitting($option,$filter_data=array(),$slitting_date='')
	{
		$con='';
		if($slitting_date!='')
		{
			$con="AND slitting_date='".$slitting_date."'";
		}
		$sql = "SELECT sl.*,CONCAT(om.first_name ,' ', om.last_name) as operator_name,m.machine_name  FROM slitting as sl, employee as om,machine_master as m WHERE sl.is_delete = 0 AND sl.operator_id=om.employee_id AND  sl.machine_id = m.machine_id  $con ";
		
		//printr($sql);die;
		if(!empty($filter_data)){
			
			if(!empty($filter_data['operator_id'])){
				$sql .= " AND sl.operator_id = '".$filter_data['operator_id']."' "; 	
			}
			if(!empty($filter_data['machine_id'])){
				$sql .= " AND sl.machine_id = '".$filter_data['machine_id']."' "; 	
			}
			
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY slitting_id";	
		} 

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
		
	/*	if (isset($option['start']) || isset($option['limit'])) {
			if ($option['start'] < 0) {
				$option['start'] = 0;
			}			
			if ($option['limit'] < 1) {
				$option['limit'] = 20;
			}	
			$sql .= " LIMIT " . (int)$option['start'] . "," . (int)$option['limit'];
		}*/
		//echo $sql;die;
		$data = $this->query($sql);
		
		return $data->rows;
		
		
	}
	public function getJobDetail($slitting_id)
	{
		
		$sql = "SELECT sl.*,om.first_name as operator_name,mm.machine_name FROM slitting as sl, employee as om,machine_master as mm WHERE sl.is_delete = 0 AND sl.slitting_id = '".$slitting_id."' AND sl.operator_id=om.employee_id AND sl.machine_id = mm.machine_id ";
		$data = $this->query($sql);
		//echo  $sql;
		//printr($data);
		
		return $data->row;
		
	//printr($slitting_array);
	
	}
	public function getslitting_roll_details($slitting_id)
	{
		
		$sql = "SELECT * FROM slitting_process  WHERE  is_delete=0 AND  slitting_id = '".$slitting_id."' ";
		$data = $this->query($sql);
//	echo $sql;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		

	}
	
	public function updateSlitting($slitting_id,$data)
	{
		
	//printr($data);die;
		if((isset($data['trimming_wastage'])) && !empty($data['trimming_wastage'])){
			$data['trimming_wastage']=$data['trimming_wastage'];
		}else{
			$data['trimming_wastage']=0.00;
		}
		if((isset($data['lamination_wastage'])) && !empty($data['lamination_wastage'])){
			$data['lamination_wastage']=$data['lamination_wastage'];
		}else{
			$data['lamination_wastage']=0.00;
		}
		if((isset($data['top_cut_wastage'])) && !empty($data['top_cut_wastage'])){
			$data['top_cut_wastage']=$data['top_cut_wastage'];
		}else{
			$data['top_cut_wastage']=0.00;
		}
		if((isset($data['printing_wastage'])) && !empty($data['printing_wastage'])){
			$data['printing_wastage']=$data['printing_wastage'];
		}else{
			$data['printing_wastage']=0.00;
		}
		if((isset($data['remark'])) && (!empty($data['remark']))){
			$remark=$data['remark'];
		}else{
			$remark=$data['remarks_slitting'];
		} 
			
		$sql = "UPDATE`" . DB_PREFIX . "slitting` SET  slitting_no = '" .$data['slitting_no']. "',slitting_date = '" .$data['slitting_date']. "',job_id = '" .$data['job_id']. "',job_name = '" .$data['job_name']. "',job_no = '" .$data['job_name_text']. "',operator_id = '" .$data['operator_id']. "',junior_id = '" .$data['junior_id']. "',machine_id = '" .implode(",",$data['machine_id']). "',shift = '" .$data['shift']. "',remarks_slitting = '" .$data['remarks_slitting']. "',remark='".$remark."',slitting_status='".$data['slitting_status']."',input_qty='".$data['input_qty']."',output_qty='".$data['output_qty']."',no_of_roll_i='".$data['no_of_roll_i']."',no_of_roll='".$data['no_of_roll']."',machine_speed='".$data['machine_speed']."',setting_wastage='".$data['setting_wastage']."',top_cut_wastage='".$data['top_cut_wastage']."',lamination_wastage='".$data['lamination_wastage']."',printing_wastage='".$data['printing_wastage']."',trimming_wastage='".$data['trimming_wastage']."',total_wastage='".$data['total_wastage']."',wastage='".$data['wastage']."',date_added = NOW(),date_modify = NOW(),is_delete=0,added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE slitting_id = '" .(int)$slitting_id."' ";
	//printr($sql);die;
		$this->query($sql);
		if($data['slitting_status']==2){
			
			
			foreach($data['roll_details'] as $roll_no)
						{
						
							if(isset($roll_no['slitting_material_id']))
								
									
											{
												//printr($roll_no);
												//update Inward recode
												$sql_update = "UPDATE `" . DB_PREFIX . "product_inward` SET  roll_no='".$roll_no['roll_code']."',inward_size ='".$roll_no['r_output_qty']."' ,added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE  product_inward_id='".$roll_no['roll_details_id']."'";
												$this->query($sql_update);
												
												
												//	echo $sql_update;die;
												
												
												//update slitting procces
												$this->query("UPDATE   slitting_process SET slitting_id ='".$slitting_id."',job_id = '" .$data['job_id']. "',roll_code = '" .$roll_no['roll_code']. "',input_qty = '".$roll_no['r_input_qty']."',output_qty = '".$roll_no['r_output_qty']."',p_input_qty = '".$roll_no['r_output_qty']."',roll_size = '".$roll_no['r_output_qty']."', date_added = NOW(),date_modify = NOW(),is_delete='0' WHERE slitting_material_id ='".$roll_no['slitting_material_id']."' ");
												
												
							
											}else{
												//printr("hii");
												$roll_details = $this->getRoll_details($data['roll_code_id']);
												//printr($roll_details);
												$i_no=$this->getlatestNo();
												$strpad = str_pad($i_no+1,8,'0',STR_PAD_LEFT);
												$inward_no='INWD'.$strpad;
												// add inward in slitting roll
											
												$sql_insert = "INSERT INTO `" . DB_PREFIX . "product_inward` SET  roll_no='".$roll_no['roll_code']."',inward_no = '" .$inward_no. "',vender_id = '".$roll_details['vender_id']."',qty ='".$roll_details['qty']."' ,inward_size ='".$roll_no['r_output_qty']."' , product_category_id = '".$roll_details['product_category_id']."', product_item_id = '".$roll_details['product_item_id']."' ,unit_id = '".$roll_details['unit_id']."',sec_unit_id='".$roll_details['sec_unit_id']."',date_added = NOW(),date_modify = NOW(),user_id='".$roll_details['user_id']."',user_type_id='2',is_delete=0,status=1,inward_date='".$roll_details['inward_date']."',manufacutring_date='".$roll_details['manufacutring_date']."',added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
												$this->query($sql_insert);
												$inward_id = $this->getLastIdProductInward();

												$this->query("INSERT INTO   slitting_process SET slitting_id ='".$slitting_id."',job_id = '" .$data['job_id']. "',roll_code_id='".$inward_id."',roll_code = '" .$roll_no['roll_code']. "', date_added = NOW(),date_modify = NOW(),is_delete=0");
											}
						}
						
		
	
			}else{
		
					foreach($data['roll_details'] as $roll_no)
								{
									
									
								//	printr($roll_no);
									if(isset($roll_no['slitting_material_id']))
											{
													$this->query("UPDATE   slitting_process SET slitting_id ='".$slitting_id."',job_id = '" .$data['job_id']. "',roll_code_id='".$data['roll_code_id']."',roll_code = '" .$roll_no['roll_code']. "',input_qty = '".$roll_no['r_input_qty']."',output_qty = '".$roll_no['r_output_qty']."', date_added = NOW(),date_modify = NOW(),is_delete='0' WHERE slitting_material_id ='".$roll_no['slitting_material_id']."' ");
											}else{
													$this->query("INSERT INTO   slitting_process SET slitting_id ='".$slitting_id."',job_id = '" .$data['job_id']. "',roll_code_id='".$data['roll_code_id']."',roll_code = '" .$roll_no['roll_code']. "',input_qty = '".$roll_no['r_input_qty']."',output_qty = '".$roll_no['r_output_qty']."', date_added = NOW(),date_modify = NOW(),is_delete=0");
											}
								
							
								
								}
			}
						
	//	die;
	}

	public function getDateWiseJob($filter_data)
	{
		$sql = "SELECT slitting_date FROM slitting WHERE is_delete = 0 ";
			
			if(!empty($filter_data['slitting_date'])){
					$sql .= " AND slitting_date = '".$filter_data['slitting_date']."' "; 	
				}
					$sql .= "GROUP BY slitting_date";
			
    	if (isset($filter_data['sort'])) {
			$sql .= " ORDER BY " . $filter_data['sort'];	
		} else {
			$sql .= " ORDER BY slitting_id";	
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
			
			return $data->rows;
		
	}
		public function getRollCodeDetails($roll_val)
	{
		
			$sql = "SELECT * FROM printing_job WHERE job_name = '".$roll_val."' AND is_delete=0 AND status = 1 ORDER BY job_id DESC ";
			$data = $this->query($sql);
            	
		
			$bal_qty=$film_nm=$film_size='';
		
			if($data->num_rows)
			
				$bal_qty=$data->row['output_qty'];
				$film_size =$data->row['inward_size'];
				$film_nm=$data->row['roll_name_id'];
				
                    
			$data_return = array('output_qty'=>$bal_qty,
								 'film_name'=>$film_nm,
								 'film_size'=>$film_size);
		
		
				return $data_return;
        	
	}

	
	
	
	
	public function getInputQty($roll_val)
	{
			
			$sql = "SELECT * FROM printing_job WHERE job_name = '".$roll_val."' AND is_delete=0 AND status = 1 ORDER BY job_id DESC ";
			$data = $this->query($sql);
          
			$bal_qty=$film_nm=$film_size='';
		
			if($data->num_rows)
			
				$bal_qty=$data->row['output_qty'];
				$film_size =$data->row['inward_size'];
				$film_nm=$data->row['roll_name_id'];
				
             
			$data_return = array('output_qty'=>$bal_qty,
								 'film_name'=>$film_nm,
								 'film_size'=>$film_size);
		
		
        			//if($data->num_rows){
        			//	printr($data_return);die;
        			
			
				return $data_return;
        			//}else{
        				//return false;
        			//}
	}
	
	public function view_slitting_report($slitting_id)
	{
		
		$slitting_details = $this->getJobDetail($slitting_id);
	 //   printr($slitting_details);
	    
		$job_details=$this->getJob_layer_details($slitting_details['job_id']);
	//	printr($job_details);
		$slitting_roll_details=$this->getslitting_roll_details($slitting_id);
		
		if(!empty($slitting_details)){
         if($slitting_details['slitting_status']==0){
			$printing_details = $this->getPrintingDetails($slitting_details['roll_code_id']);
			
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
	 $html .='<div  style="font-size: 22px;"><div style="text-align:center; "><b>SLITTING  DETAILS</b>';
		$html.='</div>
					<div class="div_first" style=" width: 100%;float: left;  font-size: 20px;">';
						$html.='	<table class="table" >
							<tbody>
							<tr>
								
							<td style="vertical-align: top;width: 50%;">
							 	
								<b>Shift :</b>   '.ucwords($slitting_details['shift']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b> Date : </b>  '.dateFormat(4,$slitting_details['slitting_date']).'<br>	<b>Job Name :</b> '.$job_details['job_name'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								

							</td>
							<td style="vertical-align: top;width: 50%;">
							<b> Slitting machine Name  : </b>   '. ucwords($slitting_details['machine_name']).'<br>
						
							<b>'.$label.':-</b>'.$roll_code.'
							</td>
							</tr>
								</tbody>
							</table>';
							
							
							$html .='<div class="div_first" style="width: 100%; float: left; ">

					<table class="table" style="font-size: 18px;">
						<tbody>
						<tr>
						 
						
								<td width="10%"><div align="center"><b>Slitting No </b></div></td>	
								<td width="10%"><div align="center"><b>Operator Name</b></div></td>	
								<td width="10%"><div align="center"><b>I/P(Kgs)</b></div></td>
								<td width="10%"><div align="center"><b>O/P(Kgs)</b></div></td>
												
								<td width="10%"><div align="center"><b>Total Wastage (Kgs)</b></div></td>							
								<td width="10%"><div align="center"><b>Waste (%)</b></div></td>	
						</tr>
						<tr>
						 
						
								<td width="10%"><div align="center">'.$slitting_details['slitting_no'].'</div></td>	
								<td width="10%"><div align="center">'.$slitting_details['operator_name'].'</div></td>
								<td width="10%"><div align="center">'.$slitting_details['input_qty'].'</div></td>
								<td width="10%"><div align="center">'.$slitting_details['output_qty'].'</div></td>	
								<td width="10%"><div align="center">'.$slitting_details['total_wastage'].'</div></td>							
								<td width="10%"><div align="center">'.$slitting_details['wastage'].' (%)</div></td>	
						</tr>
					
					
							</tbody><tbody></table>
						</div></div>';


		}

		
			return $html;
	
		}
	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	
	public function job_detail($job_name)
	{	
		//$sql="SELECT * FROM  job_master WHERE job_name LIKE '%".strtolower($job_name)."%' AND is_delete=0 AND status = 1";
		$sql ="SELECT *,pi.* FROM printing_job as pj ,product_inward as pi ,job_master as j WHERE pi.product_inward_id=pj.roll_no_id AND pj.is_delete=0 AND pj.status = 1 AND pj.roll_code_status = '1'  AND pj.job_name = j.job_id AND j.job_name  LIKE '%".strtolower($job_name)."%'";
		//$sql ="SELECT * FROM slitting as sl ,job_master as j WHERE sl.is_delete=0 AND sl.status = 1 AND sl.roll_code_status = '1' AND slitting_status ='0' AND sl.job_name = j.job_id AND j.job_name  LIKE '%".strtolower($job_name)."%'";
		//echo $sql;
		
		$data = $this->query($sql);
	//	printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function addroll_code($data)
	{
		//printr($data);die;
		$sql = "UPDATE `".DB_PREFIX."slitting` SET roll_code='".$data['roll_code']."' , roll_code_status='1'WHERE slitting_id='".$data['slitting_id']."'";		
		//echo $sql;	die;
		$data=$this->query($sql);		
			
			
	}
	
		public function getUser($user_id,$user_type_id)
	{	
		if($user_type_id == 1)
		{
			$sql = "SELECT ib.international_branch_id,ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2)
		{
			$sql = "SELECT ib.international_branch_id,e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4)
		{
			$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else
		{
			
			$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		$data = $this->query($sql);
		return $data->row;
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
	
	public function getLastId() {
        $sql = "SELECT slitting_id FROM  slitting ORDER BY slitting_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['slitting_id'];
        } else {
            return false;
        }
    }
	public function getLastIdProductInward() {
        $sql = "SELECT product_inward_id FROM  product_inward ORDER BY product_inward_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['product_inward_id'];
        } else {
            return false;
        }
    }
	
	public function getLastProcessId() {
        $sql = "SELECT process_id FROM  slitting ORDER BY slitting_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['process_id'];
        } else {
            return false;
        }
    }
	
	
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "slitting` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE slitting_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "slitting` SET   is_delete = '1', date_modify = NOW() WHERE slitting_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function remove_roll ($slitting_material_id,$slitting_status,$roll_code_id)
	{	
			$sql = "UPDATE `" . DB_PREFIX . "slitting_process` SET is_delete = '1',  date_modify = NOW() WHERE slitting_material_id = '".$slitting_material_id."' ";		
			
				$this->query($sql);
			
			if($slitting_status==2){
					
				$sql_update = "UPDATE `" . DB_PREFIX . "product_inward` SET slt_is_delete='1' , date_modify = NOW() WHERE product_inward_id='".$roll_code_id."'";
				$this->query($sql_update);
	
			}
			
			

		
	}
	public function getPrintingRollDetails($job_id)
	{
		
		$sql = "SELECT pb.*,pr.*,pw.*,mm.machine_name,jm.job_name as job_num_id FROM printing_job as pb,product_inward as pw, printing_roll_details as pr,employee as om,machine_master as mm,job_master as jm WHERE pb.is_delete = 0 AND pb.job_id = '".$job_id."'  AND pb.machine_id = mm.machine_id  AND pw.product_inward_id = pr.roll_no_id  AND pb.job_name_id=jm.job_id AND pr.job_id=pb.job_id AND pr.is_delete=0";
		$data = $this->query($sql);	
			//printr($data);
		if ($data->num_rows) {
					return $data->row;
				} else {
					return false;
				}
		
	}
	
	public function getPrintingDetails($job_id)
	{
		
		$sql = "SELECT pb.*,mm.machine_name,jm.job_name as job_num_id FROM printing_job as pb,employee as om,machine_master as mm,job_master as jm WHERE pb.is_delete = 0 AND pb.job_id = '".$job_id."' AND  pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id ";
		$data = $this->query($sql);	
		//	printr($data);
		if ($data->num_rows) {
					return $data->row;
				} else {
					return false;
				}
		
	}
	
	public function getLamination_details($lamination_id){
		$sql = "SELECT l.*,mm.machine_name,jm.job_name as job_num_id,MAX(output_qty) as total_output  FROM  lamination as l,lamination_layer as ll, machine_master as mm,job_master as jm WHERE l.is_delete = 0 AND l.lamination_id = '".$lamination_id."' AND  l.machine_id = mm.machine_id AND l.job_id=jm.job_id AND l.lamination_id=ll.lamination_id";
	   // echo $sql;
		$data = $this->query($sql);		
	 //   printr($data); 
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
	

}
?>