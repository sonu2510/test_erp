<?php
class report_quotation extends dbclass{
	
	//user
	public function getTotalQuotationUser($filter_array=array()){
		
		$data = $this->query("SELECT COUNT(*) as total,added_by_user_id,added_by_user_type_id FROM `" . DB_PREFIX . "multi_product_quotation_id` GROUP BY added_by_user_id,added_by_user_type_id ");
		
		return $data->num_rows;
	}
	
	public function getQuotationUser($option = array(),$filter_array=array()){
		
		$sql = "SELECT COUNT(if(status = '1',status,NULL)) as active,COUNT(if(status = '0' AND quotation_status = '1',status,NULL)) as inactive, COUNT(if(quotation_status = '0',quotation_status,NULL)) as not_saved,added_by_user_id,added_by_user_type_id FROM `" . DB_PREFIX . "multi_product_quotation_id` GROUP BY added_by_user_id,added_by_user_type_id";
		

		if(!empty($option)){
			$start = 0;
			$limit = 15;
			if(!empty($option['start'])){
				if($option['start']<0){
					$start = 0;
				}else{
					$start = $option['start'];
				}
			}
			if(!empty($option['limit'])){
				$limit = $option['limit'];
			}
			
			$sql .= " LIMIT $start,$limit ";
		}
		
		$data = $this->query($sql);
		
		if($data->num_rows){
			foreach($data->rows as $row){
							
				$type = '';
				
				if($row['added_by_user_type_id']==1){	
					$type = 'User';
					
					$user_name = $this->query("SELECT user_name,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "user` WHERE 
					user_id= '".$row['added_by_user_id']."'");	
				}
				
				if($row['added_by_user_type_id']==2){	
					$type = 'Employee';
					
					$user_name = $this->query("SELECT user_name,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "employee` WHERE 
					employee_id= '".$row['added_by_user_id']."'");	
				}
				
				if($row['added_by_user_type_id']==3){	
					$type = 'Client';
					
					$user_name = $this->query("SELECT user_name,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "client` WHERE 
					client_id= '".$row['added_by_user_id']."'");	
				}
				
				if($row['added_by_user_type_id']==4){	
					$type = 'Internation Branch';
					
					$user_name = $this->query("SELECT user_name,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "international_branch` WHERE 
					international_branch_id= '".$row['added_by_user_id']."'");	
				}
				
				$return[] = array(
				   'user_name' => $user_name->row['user_name'],
				   'name' => $user_name->row['name'],
				   'type' => $type,
				   'active' => $row['active'],
				   'inactive' => $row['inactive'],
				   'not_saved' => $row['not_saved']
				);
				
			}
			
			//printr($return);
			return $return;	
		}
    }
}
?>