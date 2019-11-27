<?php
class department extends dbclass{
	
	public function addStaffGroup($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "staff_group_details` SET staff_group_name = '" .$data['name']. "',staff_group_addr = '" .$data['staff_group_addr']. "', status = '" .$data['status']. "', is_delete = '0', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function getTotalStaffGroup(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "staff_group_details` WHERE is_delete = '0'";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getStaffGroup($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "staff_group_details` WHERE is_delete = '0'";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY staff_group_id";	
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
	
	public function getGroup($group_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "staff_group_details` WHERE status='1' AND staff_group_id = '" .(int)$group_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateGroup($staff_group_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "staff_group_details` SET staff_group_name = '" .$data['name']. "',staff_group_addr = '" .$data['staff_group_addr']. "', status = '" .$data['status']. "', date_modify = NOW() WHERE staff_group_id = '" .(int)$staff_group_id. "'";
		$this->query($sql);
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "staff_group_details` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE staff_group_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "staff_group_details` SET is_delete = '1', date_modify = NOW() WHERE staff_group_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>