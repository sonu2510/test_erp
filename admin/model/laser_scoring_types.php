<?php
class laser extends dbclass{
	
	public function addLaser($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "laser_scoring_types` SET  laser_name = '" .$data['laser_name']. "',status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
		
		$this->query($sql);
	}
	
	public function updateLaser($type_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "laser_scoring_types` SET  laser_name = '" .$data['laser_name']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE type_id = '" .(int)$type_id. "'";
		$this->query($sql);		
	}
	
	public function getTotallaser(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "laser_scoring_types` WHERE is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getlasers($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "laser_scoring_types` WHERE is_delete = 0";
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY type_id";	
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
	
	public function gettype($type_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "laser_scoring_types` WHERE type_id = '" .(int)$type_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateLaserStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "laser_scoring_types` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE type_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "laser_scoring_types` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE type_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "laser_scoring_types` SET is_delete = '1', date_modify = NOW() WHERE type_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>