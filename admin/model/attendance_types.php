<?php
//sonu
class attendance_types extends dbclass{
	
	

	public function addDepartment($data){
	
		$sql = "INSERT INTO `" . DB_PREFIX . "attendance_types` SET type_name = '" .$data['name']. "', status = '1', is_delete = '0', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function getTotalDepartment(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "attendance_types` WHERE is_delete = '0'";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getDepartments($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "attendance_types` WHERE is_delete = '0'";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY attendance_types_id";	
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
	
	public function getAttendance_type($attendance_types_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "attendance_types` WHERE  attendance_types_id = '" .(int)$attendance_types_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateDepartment($attendance_types_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "attendance_types` SET type_name = '" .$data['name']. "', date_modify = NOW() WHERE attendance_types_id = '" .(int)$attendance_types_id. "'";
		$this->query($sql);
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "attendance_types` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE attendance_types_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "attendance_types` SET is_delete = '1', date_modify = NOW() WHERE attendance_types_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>