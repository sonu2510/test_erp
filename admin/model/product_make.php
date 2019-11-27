<?php
class productMake extends dbclass{
	//ruchi
	public function addMake($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_make` SET make_name = '" . $data['make']. "',abbr='".$data['abbr']."',serial_no='".$data['serial_no']."',status = '" .$data['status']. "', date_added = NOW(), date_modify = NOW() ";
		$this->query($sql);
		return $this->getLastId();
	}
	
	
	public function getMake($make_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_make` WHERE make_id = '" .(int)$make_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateMake($make_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_make` SET make_name = '" .$data['make']. "',abbr='".$data['abbr']."',serial_no='".$data['serial_no']."',status = '" .$data['status']. "',  date_modify = NOW() WHERE make_id = '" .(int)$make_id. "'";
		$this->query($sql);
	}

	public function getTotalMake(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_make` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getMakes($data){
		$sql = "SELECT make_id,make_name,status FROM `" . DB_PREFIX . "product_make` WHERE is_delete = '0' ";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY make_id";	
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
	
	public function updateStatus($status,$data){
		$this->query("UPDATE `" . DB_PREFIX . "product_make` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE make_id IN (" .implode(",",$data). ")");
	}
	
	public function setDelete($data){
		$this->query("UPDATE `" . DB_PREFIX . "product_make` SET is_delete = '1' WHERE make_id IN (" .implode(",",$data). ")");
	}
}
?>