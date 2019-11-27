<?php
class digital_quantity extends dbclass{
	
	public function addRollQuantity($data){
		$this->query("INSERT INTO `" . DB_PREFIX . "digital_quantity` SET 	quantity = '" .(int)$data['quantity']. "', plus_minus_quantity = '".(int)$data['pm_quantity']."', status = '".(int)$data['status']. "', date_added = NOW()");
		return $this->getLastId();
	}
	
	public function updateQuantity($quantity_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "digital_quantity` SET quantity = '" .(int)$data['quantity']. "', plus_minus_quantity = '".(int)$data['pm_quantity']."', status = '".(int)$data['status']. "',  date_modify = NOW() WHERE digital_quantity_id = '" .(int)$quantity_id. "'";
		$this->query($sql);
	}
	
	public function getTotalQuantity($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "digital_quantity` WHERE 1=1";
		
		if(!empty($filter_data)){
			if($filter_data['status']!=''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}
		}
		$data = $this->query($sql); 
		return $data->row['total'];
	} 
	
	public function getQuantitys($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "digital_quantity` WHERE 1=1";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY quantity";	
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
	
	public function getQuantity($quantity_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "digital_quantity` WHERE digital_quantity_id = '" .(int)$quantity_id. "'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateStatus($status,$data){
		$this->query("UPDATE `" . DB_PREFIX . "digital_quantity` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE digital_quantity_id IN (" .implode(",",$data). ")");
	}
	
	public function setDelete($data){
		$this->query("DELETE FROM `" . DB_PREFIX . "digital_quantity` WHERE digital_quantity_id IN (" .implode(",",$data). ")");
	}
}
?>