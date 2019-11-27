<?php
class user_type extends dbclass{
	
	public function addMachine($data){
		$sql = "INSERT INTO ". DB_PREFIX ." user_type_production SET user_type_name = '" .$data['machine']. "',status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
		
		
		$this->query($sql);
		
	}
	
	public function updateMachine($user_type_id,$data){
		
		$sql = "UPDATE " . DB_PREFIX . "user_type_production SET   user_type_name = '" .$data['machine']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE user_type_id = '" .(int)$user_type_id. "'";
		$this->query($sql);		
	}
	
	public function getTotalMachine($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "user_type_production WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['user_type_name'])){
				$sql .= " AND user_type_name LIKE '%".$filter_data['user_type_name']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getMachines($data,$filter_data=array()){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "user_type_production WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['user_type_name'])){
				$sql .= " AND user_type_name LIKE '%".$filter_data['user_type_name']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY user_type_id";	
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
		
		$data = $this->query($sql);
	
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getMachine($user_type_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "user_type_production WHERE user_type_id = '" .(int)$user_type_id. "'";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateMachineStatus($id,$status){
		$sql = "UPDATE " . DB_PREFIX . "user_type_production SET status = '" .(int)$status. "',  date_modify = NOW() WHERE 
		user_type_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE " . DB_PREFIX . "user_type_production SET status = '" .(int)$status. "',  date_modify = NOW() WHERE user_type_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE " . DB_PREFIX . "user_type_production SET is_delete = '1', date_modify = NOW() WHERE user_type_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>