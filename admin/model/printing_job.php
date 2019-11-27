<?php
//==>kinjal chnage by sonu
class printing_job extends dbclass{
	public function getOperator()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type=3";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getJuniorOperator()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type=3";
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
	public function getChemistName()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type='6'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function getMachine()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "machine_master` WHERE is_delete = 0 AND production_process_id LIKE '%2%'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}

	public function getRollNo()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_inward` WHERE slt_is_delete='0' AND product_category_id='3' AND is_delete = 0 AND status=1 AND roll_no!=''";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function addJob($data)
	{
	//printr($data);
	//die;

		$remark='';
		if((isset($data['remark'])) && (!empty($data['remark']))){
			$remark=$data['remark'];
		}else{
			$remark=$data['remaks_printing_job'];
		}
	

		if((isset($data['printing_id'])) && (!empty($data['printing_id']))){
			$job_id = $data['printing_id']; 

			$sql_update = "UPDATE `" . DB_PREFIX . "printing_job` SET   job_no = '" .$data['job_no']. "',job_date = '" .$data['job_date']. "',job_name_text='".addslashes($data['job_name_text'])."',job_name_id='".$data['job_id']."',job_name='".$data['job_name']."',job_type='".$data['job_type']."',chemist_id='".$data['chemist_id']."',machine_id = '" .$data['machine_id']. "',start_time = '" .$data['start_time']. "',end_time = '" .$data['end_time']. "',roll_used = '" .$data['roll_used']. "',status = '" .$data['status']. "',remaks_printing_job='".$data['remaks_printing_job']."',remark='".$remark."', date_added = NOW(),date_modify = NOW(),is_delete=0  WHERE job_id = '" .(int)$job_id. "'";
			

//printr($data);

		
				$this->query($sql_update);
		}


		else{
		
		
		
					$sql = "INSERT INTO `" . DB_PREFIX . "printing_job` SET  job_no = '" .$data['job_no']. "',job_date = '" .$data['job_date']. "',job_name_text='".$data['job_name_text']."',job_name_id='".$data['job_id']."',job_name='".$data['job_name']."',job_type='".$data['job_type']."',chemist_id='".$data['chemist_id']."',machine_id = '" .$data['machine_id']. "',start_time = '" .$data['start_time']. "',end_time = '" .$data['end_time']. "',roll_used = '" .$data['roll_used']. "',status = '" .$data['status']. "',remaks_printing_job='".$data['remaks_printing_job']."',remark='".$remark."', date_added = NOW(),date_modify = NOW(),is_delete=0";		
					$this->query($sql);
				//	echo $sql;die;
					$job_id = $this->getLastId();

				}
		


		$sql_operator = "INSERT INTO `" . DB_PREFIX . "printing_operator_details` SET  	printing_id = '" .$job_id. "',	printing_date = '" .$data['job_date']. "',operator_id = '" .$data['operator_id']. "',junior_id = '" .$data['junior_id']. "',operator_shift = '" .$data['operator_shift']. "',job_id = '" .$data['job_id']. "',plain_wastage = '" .$data['plain_wastage']. "',print_wastage = '" .$data['print_wastage']. "',total_wastage = '" .$data['total_wastage']. "',roll_used = '" .$data['roll_used']. "',wastage_per='".$data['wastage_per']."',date_added = NOW(),date_modify = NOW(),is_delete=0";		
				

				$this->query($sql_operator);
		

			$printing_operator_id = $this->getLastId();
		//	printr($printing_operator_id);
			//layers
			
	//		printr($data['roll_details']);
			if(isset($data['roll_details']) && !empty($data['roll_details'])){
			foreach($data['roll_details'] as $roll){
				
				
				
				//printr($roll);
				$this->query("INSERT INTO " . DB_PREFIX . " printing_roll_details  SET 	job_id = '".$job_id."',printing_operator_id='".$printing_operator_id."' ,roll_no_id = '" .$roll['roll_no_id']. "',roll_name_id = '" .$roll['roll_name_id']. "',film_size = '" .$roll['film_size']. "',input_qty = '" .$roll['input_qty']. "',	balance_qty = '" .$roll['balance_qty']. "',output_qty = '" .$roll['output_qty']. "',output_qty_m = '" .$roll['output_qty_m']. "',is_delete=0, date_added = NOW()");
			
				$total_qty=$this->getInputQty($roll['roll_no_id']);
				$c_qty=$total_qty['bal_qty']-$roll['input_qty'];
				$final_qty=$c_qty+$roll['balance_qty'];
				//printr($final_qty);
				$sql_update = "UPDATE `" . DB_PREFIX . "product_inward` SET  qty= '".$final_qty."', date_added = NOW() WHERE product_inward_id = '".$roll['roll_no_id']."' ";
				$this->query($sql_update);
				//die;


			}
		}
		//die;
		return	$job_id;
	}
	public function getlatestjobid()
	{
		$sql = "SELECT job_id FROM `" . DB_PREFIX . "printing_job` WHERE is_delete = 0 ORDER BY  job_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['job_id'];
		}else{
			return false;
		}
	}
		public function getUser($user_id,$user_type_id)
	{
	   // printr($user_type_id.'=='.$user_id);
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2){
			/*$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";*/
			$sql = "SELECT e.user_name, e.user_id,co.country_id, e.multi_quotation_price,ib.company_address,ib.company_name,ib.default_curr,co.country_name, e.first_name, e.last_name,ib.international_branch_id, ib.email_confirm,e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.international_branch_id as user_id,ib.user_name,co.country_id,ib.gst,ib.company_address,ib.default_curr,ib.company_name,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			
			$sql = "SELECT e.user_name, e.user_id,co.country_id, e.multi_quotation_price,ib.company_address,ib.company_name,ib.default_curr,co.country_name, e.first_name, e.last_name,ib.international_branch_id, ib.email_confirm,e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}
		$data = $this->query($sql);
		return $data->row;
	}
	public function getTotalJob($filter_data=array(),$job_date='')
	{
		$con='';
		if($job_date!='')
		{
			$con="AND pb.job_date='".$job_date."'";
		}
		
	//	$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "printing_job` as pb WHERE pb.is_delete = 0 $con";
/*	$sql = "SELECT COUNT(*) as total, pb.*,jm.job_name  FROM printing_job as pb,job_master as jm WHERE pb.is_delete = 0 AND  pb.job_name_id=jm.job_id $con ";*/
	
		$sql = "SELECT COUNT(*) as total, pb.*,po.*, om.first_name,mm.machine_name,jm.job_name as job_num_id  FROM printing_job as pb,employee as om,  printing_operator_details as po,machine_master as  mm,job_master as jm
		WHERE  po.operator_id=om.employee_id  AND  po.is_delete = 0 AND  pb.is_delete = 0 AND pb.job_id=po.printing_id  AND pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id $con " ;
		//printr($filter_data);
		if(!empty($filter_data)){
			if(!empty($filter_data['job_name'])){
				$sql .= " AND jm.job_name LIKE '%".$filter_data['job_name']."%' ";		
			}
			if(!empty($filter_data['job_no'])){
				$sql .= " AND jm.job_no LIKE '%".$filter_data['job_no']."%' ";		
			}
			/*if(!empty($filter_data['operator_id'])){
				$sql .= " AND pb.operator_id = '".$filter_data['operator_id']."' "; 	
			}*/
			if(!empty($filter_data['job_date'])){
				$sql .= " AND pb.job_date = '".$filter_data['job_date']."' "; 	
			}
		}
		if (isset($filter_data['sort'])) {
			$sql .= " ORDER BY " . $filter_data['sort'];	
		} else {
			$sql .= " ORDER BY pb.job_id";	 
		}

		if (isset($filter_data['order']) && ($filter_data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
	
	//	echo $sql;die;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getJob($option,$filter_data=array(),$job_date='')
	{
		$con='';
		if($job_date!='')
		{
			$con="AND pb.job_date='".$job_date."'";
		}
	/*	$sql = "SELECT pb.*,jm.job_name  FROM printing_job as pb,job_master as jm WHERE pb.is_delete = 0 AND  pb.job_name_id=jm.job_id $con ";
		*/
		
			$sql = "SELECT  pb.*,po.input_qty,po.output_qty,po.output_qty_m,po.film_size,po.operator_shift, om.first_name,mm.machine_name,jm.job_name as job_num_id  FROM printing_job as pb,employee as om,  printing_operator_details as po,machine_master as mm,job_master as jm
		WHERE  po.operator_id=om.employee_id  AND  po.is_delete = 0 AND  pb.is_delete = 0 AND pb.job_id=po.printing_id AND pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id $con " ;
//	printr($sql);die;
		if(!empty($filter_data)){
			if(!empty($filter_data['job_name'])){
				$sql .= " AND jm.job_name LIKE '%".$filter_data['job_name']."%' ";		
			}
			if(!empty($filter_data['job_no'])){
				$sql .= " AND jm.job_no LIKE '%".$filter_data['job_no']."%' ";		
			}
		/*	if(!empty($filter_data['operator_id'])){
				$sql .= " AND pb.operator_id = '".$filter_data['operator_id']."' "; 	
			}*/
			
		}
		if (isset($filter_data['sort'])) {
			$sql .= " ORDER BY " . $filter_data['sort'];	
		} else {
			$sql .= " ORDER BY pb.job_id";	
		}

		if (isset($filter_data['order']) && ($filter_data['order'] == 'ASC')) {
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
	    $total_data=$this->getTotalJob($filter_data=array(),$job_date='');
		$sql .= " LIMIT 5000 ";
	
	//	echo $sql;die;
		$data = $this->query($sql);
		
		return $data->rows;
		
		 
	}
	public function getJobDetail($job_id)
	{
		
		$sql = "SELECT pb.*,mm.machine_name,jm.job_name as job_num_id FROM printing_job as pb, machine_master as mm,job_master as jm WHERE pb.is_delete = 0 AND pb.job_id = '".$job_id."' AND pb.machine_id = mm.machine_id  AND pb.job_name_id=jm.job_id";
		
		$data = $this->query($sql);
	//	printr($data);
		//return $data->row;
		
		$sql_operator = "SELECT pb.*,po.*, om.first_name  FROM printing_job as pb,employee as om,  printing_operator_details as po
		WHERE  po.operator_id=om.employee_id  AND  po.is_delete = 0 AND pb.job_id=po.printing_id AND po.printing_id='".$job_id."' ";
		
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
			


		$sql2 = "SELECT * FROM  printing_roll_details as pd, product_inward as pi ,  printing_operator_details as po WHERE po.is_delete = 0 AND po.printing_operator_id=pd.printing_operator_id AND  pd.roll_no_id=pi.product_inward_id  AND    pd.job_id = '".$job_id."' AND pd.is_delete = '0'";	
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

		//printr($printing_array);
		return $printing_array;
	}
	public function updateJob($job_id,$data)
	{
	//	printr($data);die;
		if((isset($data['remark'])) && (!empty($data['remark']))){
			$remark=$data['remark'];
		}else{
			$remark=$data['remaks_printing_job'];
		}
		
		$sql = "UPDATE `" . DB_PREFIX . "printing_job` SET  job_no = '" .$data['job_no']. "',job_date = '" .$data['job_date']. "',job_name_text='".$data['job_name_text']."',job_name_id='".$data['job_id']."',job_name='".$data['job_name']."',start_time = '" .$data['start_time']. "',end_time = '" .$data['end_time']. "',job_type='".$data['job_type']."',chemist_id='".$data['chemist_id']."',operator_id = '" .$data['operator_id']. "',machine_id = '" .$data['machine_id']. "',shift = '" .$data['shift']. "',plain_wastage = '" .$data['plain_wastage']. "',print_wastage = '" .$data['print_wastage']. "',total_wastage = '" .$data['total_wastage']. "',wastage_per='".$data['wastage_per']."',roll_used = '" .$data['roll_used']. "',status = '" .$data['status']. "',remaks_printing_job='".$data['remaks_printing_job']."', remark='".$remark."',date_modify = NOW(),added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE job_id = '" .(int)$job_id. "'";
	//	echo $sql;die;
		$this->query($sql);
		
		
		
			foreach($data['roll_details'] as $roll)
			{	
			
				if(isset($roll['printing_roll_id']) && !empty($roll['printing_roll_id'])){					
					
					$this->query(" UPDATE " . DB_PREFIX . " printing_roll_details  SET 	job_id = '".$job_id."', roll_no_id = '" .$roll['roll_no_id']. "',roll_name_id = '" .$roll['roll_name_id']. "',film_size = '" .$roll['film_size']. "',input_qty = '" .$roll['input_qty']. "',	balance_qty = '" .$roll['balance_qty']. "',output_qty = '" .$roll['output_qty']. "',output_qty_m = '" .$roll['output_qty_m']. "',is_delete=0, date_added = NOW() WHERE printing_roll_id='".$roll['printing_roll_id']."'");
				}else{
					
					$this->query("INSERT INTO " . DB_PREFIX . " printing_roll_details  SET 	job_id = '".$job_id."', roll_no_id = '" .$roll['roll_no_id']. "',roll_name_id = '" .$roll['roll_name_id']. "',film_size = '" .$roll['film_size']. "',input_qty = '" .$roll['input_qty']. "',balance_qty = '" .$roll['balance_qty']. "',output_qty = '" .$roll['output_qty']. "',output_qty_m = '" .$roll['output_qty_m']. "',is_delete=0, date_added = NOW()");
				}
				
				
					$total_qty=$this->getInputQty($roll['roll_no_id']);
					$c_qty=$total_qty['bal_qty']-$roll['input_qty'];
					$final_qty=$c_qty+$roll['balance_qty'];
					//printr($final_qty);
					$sql_update = "UPDATE `" . DB_PREFIX . "product_inward` SET  qty= '".$final_qty."', date_added = NOW() WHERE product_inward_id = '".$roll['roll_no_id']."' ";
					$this->query($sql_update);
			}
	}
	public function updateRollStatus($id,$status)
	{
		$sql = "UPDATE `" . DB_PREFIX . "printing_job` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE job_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data)
	{
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "printing_job` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE job_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "printing_job` SET is_delete = '1', date_modify = NOW() WHERE job_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getDateWiseJob($filter_data,$option=array())
	{
		$sql = "SELECT job_date FROM printing_job WHERE is_delete = 0 ";
			
			if(!empty($filter_data['job_date'])){
					$sql .= " AND job_date = '".$filter_data['job_date']."' "; 	
				}
				$sql .= "GROUP BY job_date";
				
				if (isset($filter_data['sort'])) {
		        	$sql .= " ORDER BY " . $filter_data['sort'];	
        		} else {
        			$sql .= " ORDER BY job_id";	
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
	public function viewProductionReport($post)
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
															
													  </tr>";/*
													  <td style='border:groove;' align='right'>".$row['plain_wastage']."</td>
															<td style='border:groove;' align='right'>".$row['print_wastage']."</td>
															<td style='border:groove;'  align='right'>".$row['total_wastage']."</td>
															<td style='border:groove;' align='right'>".$row['wastage_per']."</td>
															<td style='border:groove;'><span style='color:#F00'><b>".$row['operator_name']."</b></span></td>*/
												
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
	
	public function viewjob_details($job_id)
	{
		
		$printing_details = $this->getJobDetail($job_id);

		$html = '';
		//printr($printing_details);			

		$total_ink=$total_mix_ink=$total_solvent=$total_mix_solvent=0;
		
		$total_ink=$this->gettotal_ink($printing_details['job_name_id']);

		$total_mix_ink=$this->gettotal_mix_ink($printing_details['job_name_id']);
		$total_solvent=$this->gettotal_solvent($printing_details['job_name_id']);
		$total_mix_solvent=$this->gettotal_mix_solvent($printing_details['job_name_id']);
			//	printr($total_ink);
			//	printr($total_mix_ink);
			//	printr($total_solvent);
			//	printr($total_mix_solvent);


/*		$html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;"><b><h4>PRINTING JOB REPORT</h4></b>';
		$html.='</div>

						<div class="div_first" style=" width: 100%;float: left;  border: 1px solid black;font-size: 18px;">
							<table style="width: 100%;" >
							<tr>
							<td style="vertical-align: top;width: 50%;">
							 	<b>Printing No : </b>  '.ucwords($printing_details['job_no']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b>JOB  No : </b>  '.ucwords($printing_details['job_name_text']).'<br> 
							
								<b>Job Name :</b>   '.ucwords($printing_details['job_name']).'

							</td>
							<td style="vertical-align: top;width: 50%;">
								<b>Start Time :</b>   '.ucwords($printing_details['start_time']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b>End Time :</b>   '.ucwords($printing_details['end_time']).'<br> 
									<b>Machine Name : </b>  '.ucwords($printing_details['machine_name']).'<br> 
								<b> Date : </b>  '.dateFormat(4,$printing_details['job_date']).'
							</td>
							</tr>
							</table>';
							$html .='<div class="div_first" style="width: 100%; float: left;  border: 1px solid black;">

							<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 14px;">
								<tbody>
								<tr>
								 
								
									<td width="7%"><div align="center"><b>Operator Details</b></div></td>	
									<td width="5%"><div align="center"><b>Date</b></div></td>								
									<td width="38%"><div align="center">
									<table cellspacing="0px" cellpadding="10px"  style="width: 100%; border-spacing: 0px;font-size: 14px;">
										<tbody>
										<tr>
											<td colspan="6"><b><center>Roll Details<center></b></td>
										<tr>
										<tr >
											<td id="data" width="23%"><b>Roll No</b></td>
											<td id="data" width="35%"><b>Roll Name</b></td>
											<td id="data" width="10%"><b>Roll Size</b></td>
											<td id="data" width="10%"><b>I/P Qty(kgs)</b></td>
											<td id="data" width="10%"><b>O/P Qty(kgs)</b></td>
											<td id="data" width="10%"><b> Balance Qty(kgs</b>)</td>
										<tr>
										<tbody>
										</table>



									</div></td>
									<td width="7%"><div align="center"><b>Total I/P(Kgs)</b></div></td>
									<td width="7%"><div align="center"><b>Total O/P(Kgs)</b></div></td>
									<td width="7%"><div align="center"><b>Plain Waste (Kgs)</b></div></td>
									<td width="7%"><div align="center"><b>Print Waste (Kgs)</b></div></td>
									<td width="7%"><div align="center"><b>Total Waste (Kgs)</b></div></td>
									<td width="5%"><div align="center"><b>Wastage (%)</b></div></td>
									</tr>
									</tbody><tbody>';*/
									
									
					$html='	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div class="panel-body font_medium" id="print_div" style="font-size: 22px;" >';
	       

		
              
                
                          
                               $html .='<div  style="font-size: 22px;"><div style="text-align:center;"><b>PRINTING  DETAILS</b>';
                				$html.='</div>
                
                						<div class="div_first" style=" width: 100%;float: left;  font-size: 18px;">
                						';
                						$html.='	<table class="table" >
                							<tbody>
                							<tr>
                							<td style="vertical-align: top;width: 50%;">
                							 	<b>Printing No : </b>  '.ucwords($printing_details['job_no']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                								<b> Date : </b>  '.dateFormat(4,$printing_details['job_date']).'<br>
                								<b> JOb No : </b>  '.$printing_details['job_name_text'].' ('.$printing_details['job_name'].')</td>
                							<td style="vertical-align: top;width: 50%;">
                								<b>Start Time :</b>   '.ucwords($printing_details['start_time']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                								<b>End Time :</b>   '.ucwords($printing_details['end_time']).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                								<b>Shift :</b>   '.ucwords($printing_details['shift']).'<br>
                								
                							</td>
                							</tr>
                							</tbody>
                							</table>';
                							$html .='<div class="div_first" style="width: 100%; float: left;  ">';
                
                					$html.='	<table class="table" style="font-size: 16px;">
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
							            	$roll_details=$this->getRollDetails($details['printing_operator_id']);	
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

				
		 $html .='<div  style="font-size: 16px;"><div style="text-align:center;"><b>INK Used</b>';
                				$html.='</div><div class="div_first" style=" width: 100%;float: left;  font-size: 14px;">';
							$html.='<table class="table" style="font-size: 14px;">
								<tbody>
								<tr>
								 
									
										<td style="vertical-align: top;width: 25%;" >							 
											<div align="center"><b>InK Details</b></div>
										</td>
										<td style="vertical-align: top;width: 25%;" >
											<div align="center"><b>Mix Ink Details</b></div>
										</td>
										<td style="vertical-align: top;width: 25%;" >
											<div align="center"><b>Solvent Used(Kgs)</b></div>
										</td>
										<td style="width: 25%;">
											<div align="center"><b>Mix Solvent Used(Kgs)</b></div>
										</td>
									</tr>		
							</tbody>
							';
							$html.='
							<tbody>

							<tr >';
								$html.='
									<td style="vertical-align: top;width: 25%;">							 
										<table class="table" style="font-size: 14px;">
										<tbody >
											<tr >
												<td style="vertical-align: top;width: 25%;" ><div align="center"><b>INK Name</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Issue</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Used</b></div></td>
											</tr>

										</tbody>
										</table>
									</td>
									<td style="vertical-align: top;width: 25%;">
										<table class="table" style="font-size: 14px;">
										<tbody >
											<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Name</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Issue</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Used</b></div></td>
											</tr>

										</tbody>
										</table>
									</td>
									<td style="vertical-align: top;width: 25%;">
									<table class="table" style="font-size: 14px;">
										<tbody >
											<tr>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Name</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Issue</b></div></td>
												<td style="vertical-align: top;width: 25%;"><div align="center"><b>INK Used</b></div></td>
											</tr>

										</tbody>
										</table>
									</td>
									<td style="vertical-align: top;width: 25%;">
										<table class="table" style="font-size: 14px;">
										<tbody >
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
									<table class="table" style="font-size: 14px;">
										<tbody >';

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
									<table class="table" style="font-size: 14px;">
										<tbody >';
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
										<table class="table" style="font-size: 14px;">
										<tbody >';
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
										<table class="table" style="font-size: 14px;">
										<tbody >';
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
						</div></form>';



			return $html;
	
		}
	
	public function job_detail($job_name)
	{	
		$sql="SELECT * FROM  job_master WHERE job_no LIKE '%".strtolower($job_name)."%' AND is_delete=0 AND status = 1";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function addroll_code($data)
	{
		
		//printr($data);die;
		$sql = "UPDATE `".DB_PREFIX."printing_job` SET roll_code='".$data['roll_code']."', roll_size='".$data['roll_size']."' , roll_code_status='1'WHERE job_id='".$data['job_id']."'";		
		//echo $sql;	die;
		$data=$this->query($sql);		
			
			
	}
	public function remove_roll($roll_id)
	{
		$sql = "UPDATE `" . DB_PREFIX . "printing_roll_details` SET is_delete = '1',  date_added = NOW() WHERE 	printing_roll_id = '".$roll_id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
	public function removeInvoice($printing_operator_id)
	{
		$sql = "UPDATE `" . DB_PREFIX . "printing_operator_details` SET is_delete = '1',  date_added = NOW() WHERE 	printing_operator_id = '".$printing_operator_id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function getchemist_id($user_id)
    	{
		$sql = "SELECT e.employee_id,e.user_name FROM employee as e WHERE e.is_delete = '0' AND e.user_type = '6' AND employee_id ='".$user_id."'";
    		$data = $this->query($sql);
    		if($data->num_rows){
    			return $data->row['user_name'];
    		}else{
    			return false;
    		}
    	}
	public function getRollDetails($printing_operator_id)
	{
		
		$sql = "SELECT * FROM  printing_roll_details as pd, product_inward as pi   WHERE pd.roll_no_id=pi.product_inward_id  AND  pd.printing_operator_id = '".$printing_operator_id."' AND pd.is_delete = '0' GROUP by pd.printing_roll_id ";	
	//	echo $sql;die;
	    $data=$this->query($sql);
	    $total_input=0;
	    $total_output=0;

	    $roll_array=array();
	// printr($data);
	    if($data->num_rows){
	    	 foreach($data->rows as $r ) {	
											 
				  $roll_no[]='<b>'.$r['roll_no'].'  </b>('.$r['roll_name_id'].') ';
				  $total_output=$total_output+$r['output_qty'];
				  $total_input=$total_input+$r['input_qty'];
				
				}

			$uni_arr = array_unique($roll_no);
			$u_arr = implode(',',$uni_arr);

	//		printr($total_output.'  '.$total_input);

 			$roll_array=array(	'total_input'=>$total_input,
 								'total_output'=>$total_output,
 								'roll_no'=>$u_arr,


 			);

			return $roll_array;
		}else{
			return false;
		}
	}
	

   
}
?>