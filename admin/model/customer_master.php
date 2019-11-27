<?php
class customer extends dbclass{
	
	public function addCustomer($data){
	
		$sql = "INSERT INTO `" . DB_PREFIX . "customer_master` SET  first_name = '" .$data['first_name']. "',last_name = '".$data['last_name']."',address = '".$data['address']."',company_name = '".$data['company_name']."',company_address = '".$data['company_address']."',industry_p = '".$data['industry_p']."',email = '".$data['email']."',telephone = '".$data['telephone']."',fax = '".$data['fax']."',country_name ='".$data['country_name']."',state ='".$data['state']."',city ='".$data['city']."',postcode ='".$data['postcode']."',brand_name='".$data['brand_name']."',status = '" .$data['status']. "',date_added = NOW(),date_modify = NOW(),is_delete=0";
		
		$this->query($sql);
	}
	
	public function updateCustomer($cust_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "customer_master` SET  first_name = '" .$data['first_name']. "',last_name = '".$data['last_name']."',address = '".$data['address']."',company_name = '".$data['company_name']."',company_address = '".$data['company_address']."',industry_p = '".$data['industry_p']."',email = '".$data['email']."',telephone = '".$data['telephone']."',fax = '".$data['fax']."',country_name ='".$data['country_name']."',state ='".$data['state']."',city ='".$data['city']."',postcode ='".$data['postcode']."',brand_name='".$data['brand_name']."',status = '" .$data['status']. "',  date_modify = NOW() WHERE cust_id = '" .(int)$cust_id. "'";
		$this->query($sql);		
	}
	
	public function getTotalCustomer($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "customer_master` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			//printr($filter_data);
			if(!empty($filter_data['first_name'])){
				$sql .= " AND first_name LIKE '%".$filter_data['first_name']."%' ";		
			}
			
			if(!empty($filter_data['company_name'])){
				$sql .= " AND company_name LIKE '%".$filter_data['company_name']."%' "; 	
			}
			if(!empty($filter_data['brand_name'])){
				$sql .= " AND brand_name LIKE '%".$filter_data['brand_name']."%' "; 	
			}
			if(!empty($filter_data['email_address'])){
				$sql .= " AND email = '".$filter_data['email_address']."' "; 	
			}
		}
		
		$data = $this->query($sql);
		//printr($data->row['total']);
		return $data->row['total'];
	}
	
	public function getCustomers($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "customer_master` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			//printr($filter_data);
			if(!empty($filter_data['first_name'])){
				$sql .= " AND first_name LIKE '%".$filter_data['first_name']."%' ";		
			}
			
			if(!empty($filter_data['company_name'])){
				$sql .= " AND company_name LIKE '%".$filter_data['company_name']."%' "; 	
			}
			if(!empty($filter_data['brand_name'])){
				$sql .= " AND brand_name LIKE '%".$filter_data['brand_name']."%' "; 	
			}
			if(!empty($filter_data['email_address'])){
				$sql .= " AND email = '".$filter_data['email_address']."' "; 	
			}
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cust_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCust($cust_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "customer_master` WHERE cust_id = '" .(int)$cust_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateCustomerStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "customer_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE 
		cust_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "customer_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE cust_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "customer_master` SET is_delete = '1', date_modify = NOW() WHERE cust_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getCountryList(){
		
		$data = $this->query("SELECT country_name,country_id FROM " . DB_PREFIX . "country WHERE is_delete = 0 AND status=1");
		return $data->rows;	
		
	}
	
	/*public function getUserList()
		{
			$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
			$data = $this->query($sql);
			//printr($data);die;
			return $data->rows;
		}*/
}
?>