<?php
class courier_account_number extends dbclass{
	
	// ruchi -->
	public function addCourier($data){
		$arr=explode('=',$data['courier_id']);
		$sql = "INSERT INTO `" . DB_PREFIX . "courier_account_number` SET country_id = '".$data['country_id']."', admin_user_id = '".$data['admin_user_id']."',courier_id = '".$arr[0]."',courier_name = '".$arr[1]."',  account_number = '".$data['account_number']."', status = '" .(int)$data['status']. "',is_delete=0, date_modify = NOW() ";
		$this->query($sql);
		return $this->getLastId(); 
	}
	
	public function updateCourier($courier_account_number_id,$data){ 
	//	printr($data);die;
		$arr=explode('=',$data['courier_id']);
		$sql = "UPDATE `" . DB_PREFIX . "courier_account_number` SET country_id = '".$data['country_id']."', admin_user_id = '".$data['admin_user_id']."',courier_id = '".$arr[0]."',courier_name = '".$arr[1]."',  account_number = '".$data['account_number']."', status = '" .(int)$data['status']. "', date_modify = NOW() WHERE courier_account_number_id = '" .(int)$courier_account_number_id. "'";
	   
		$this->query($sql);
	//	 echo $sql;die;
	}
	// ruchi -->
	
	public function getCountry(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE is_delete=0";
		$data = $this->query($sql);
		//printr($data->num_rows);
		//die;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	} 
	public function getIBList() {
        $sql = "SELECT international_branch_id,address_id,CONCAT(first_name,' ',last_name) as user_name FROM international_branch";
        $data = $this->query($sql);
        return $data->rows;
    }
	public function getCouriersList(){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "courier WHERE is_delete = 0 AND status=1";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
	}
	public function getcourier_account_detail(){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "courier_account_number  WHERE is_delete = 0 AND status=1";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	


	
	public function getTotalcouriers($filter_data=array()){ 
		$sql="SELECT COUNT(*) as total  FROM `courier_account_number` as ca ,country as c ,international_branch as ib WHERE ca. is_delete = '0' AND ib.international_branch_id=ca.admin_user_id  AND c.country_id=ca.country_id ";
		
/*		if(!empty($filter_data)){
			
			if(!empty($filter_data['country'])){
				$sql .= " AND c.country_name LIKE '%".$filter_data['country']."%' ";
			}
			
			if(!empty($filter_data['country_code'])){
				$sql .= " AND c.country_code = '".$filter_data['country_code']."' ";
			}
			
			if(!empty($filter_data['currency_code'])){
				$sql .= " AND crn.currency_code = '".$filter_data['currency_code']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND c.status = '".$filter_data['status']."' ";
			}	
			
			if($filter_data['filter_courier'] != ''){
				$sql .= " AND c.default_courier_id = '".$filter_data['filter_courier']."' ";
			}			
		}*/
				
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getcouriers($data,$filter_data=array()){
		$sql="SELECT *,ca.status as c_status FROM `courier_account_number` as ca ,country as c ,international_branch as ib WHERE ca. is_delete = '0' AND ib.international_branch_id=ca.admin_user_id  AND c.country_id=ca.country_id ";	
	/*	if(!empty($filter_data)){
			
			if(!empty($filter_data['country'])){
				$sql .= " AND c.country_name LIKE '%".$filter_data['country']."%' ";
			}
			
			if(!empty($filter_data['country_code'])){
				$sql .= " AND c.country_code = '".$filter_data['country_code']."' "; 
			}
			
			if(!empty($filter_data['currency_code'])){
				$sql .= " AND crn.currency_code = '".$filter_data['currency_code']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND c.status = '".$filter_data['status']."' ";
			}
			
			if($filter_data['filter_courier'] != ''){
				$sql .= " AND c.default_courier_id = '".$filter_data['filter_courier']."' ";
			}				
		}
		*/
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY c.country_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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
		
		$data = $this->query($sql);
		//printr($data);
		//die;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	  

	public function updateStatus($status,$data){
		//printr($data);
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "courier_account_number` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE courier_account_number_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "courier_account_number` SET is_delete = '1', date_modify = NOW() WHERE courier_account_number_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
		//echo $sql;die;
	}
	
	public function updateCourierStatus($courier_account_number_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "courier_account_number` SET status = '" .(int)$status_value ."' WHERE courier_account_number_id = '".$courier_account_number_id ."' ";
	 echo $sql;
	   $this->query($sql);	
	}
	
}
?>