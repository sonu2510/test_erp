<?php
//==>kinjal
class production_process extends dbclass{
	
	public function addProductionName($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "production_process` SET  production_process_name = '" .$data['production_process_name']. "',status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
		
		$this->query($sql);
	}
	
	public function updateProductionName($production_process_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "production_process` SET  production_process_name = '" .$data['production_process_name']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE production_process_id = '" .(int)$production_process_id. "'";
		$this->query($sql);		
	}
	
	public function getTotalProcessDetails($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "production_process` WHERE is_delete = 0";
		//printr($filter_data);
		if(!empty($filter_data)){
			if(!empty($filter_data['production_process_name'])){
				$sql .= " AND production_process_name LIKE '%".$filter_data['production_process_name']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		//echo $sql;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getProcessDetails($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "production_process` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['production_process_name'])){
				$sql .= " AND production_process_name LIKE '%".$filter_data['production_process_name']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY production_process_id";	
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
	
	public function getProductionDetail($production_process_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "production_process` WHERE production_process_id = '" .(int)$production_process_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateRollStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "production_process` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE production_process_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "production_process` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE production_process_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "production_process` SET is_delete = '1', date_modify = NOW() WHERE production_process_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
}
?>