<?php
class productWastage extends dbclass{
	
	public function addWastage($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_wastage` SET from_quantity = '" .(int)$data['from_quantity']. "', to_quantity = '" .(int)$data['to_quantity']. "', wastage = '" .$data['wastage']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateWastage($wastage_id,$data){
		//printr($data);
		$sql = "UPDATE `" . DB_PREFIX . "product_wastage` SET from_quantity = '" .(int)$data['from_quantity']. "', to_quantity = '" .(int)$data['to_quantity']. "', wastage = '" .$data['wastage']. "', date_modify = NOW() WHERE product_wastage_id = '" .(int)$wastage_id. "'";
		//echo "UPDATE `" . DB_PREFIX . "product_wastage` SET from_quantity = '" .(int)$data['from_quantity']. "', to_quantity = '" .(int)$data['to_quantity']. "', wastage = '" .$data['wastage']. "', date_modify = NOW() WHERE product_wastage_id = '" .(int)$wastage_id. "'";die;
		$this->query($sql);
	}
	
	public function getWastage($wastage_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_wastage` WHERE product_wastage_id = '" .(int)$wastage_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getProductName($product_id){
		$sql = "SELECT product_name FROM " . DB_PREFIX . "product WHERE product_id = '" .(int)$product_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['product_name'];
		}else{
			return false;
		}
	}
	
	public function getProductList(){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE is_delete = 0 AND status = 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getTotalWastage(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_wastage`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getWastages($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_wastage`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_wastage_id";	
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
	
	///########################## ROLL WASTAGE
	public function addRollWastage($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_roll_wastage` SET from_kg = '" .(int)$data['from_kg']. "', to_kg = '" .(int)$data['to_kg']. "', wastage_kg = '" .(float)$data['wastage_kg']. "', date_added = NOW()";
		//wastage_meter = '" .(float)$data['wastage_meter']. "', wastage_kg = '" .(float)$data['wastage_kg']. "', wastage_piece = '" .(float)$data['wastage_piece']. "'";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateRollWastage($wastage_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_roll_wastage` SET from_kg = '" .(int)$data['from_kg']. "', to_kg = '" .(int)$data['to_kg']. "', wastage_kg = '" .(float)$data['wastage_kg']. "', date_modify = NOW() WHERE product_roll_wastage_id = '" .(int)$wastage_id. "'";
		//$sql = "UPDATE `" . DB_PREFIX . "product_roll_wastage` SET from_quantity = '" .(int)$data['from_quantity']. "', to_quantity = '" .(int)$data['to_quantity']. "', wastage_meter = '" .(float)$data['wastage_meter']. "', wastage_kg = '" .(float)$data['wastage_kg']. "', wastage_piece = '" .(float)$data['wastage_piece']. "', date_modify = NOW() WHERE product_roll_wastage_id = '" .(int)$wastage_id. "'";
		$this->query($sql);
	}
	
	public function getRollWastage($wastage_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_roll_wastage` WHERE product_roll_wastage_id = '" .(int)$wastage_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalRollWastage(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_roll_wastage`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getRollWastages($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_roll_wastage`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_roll_wastage_id";	
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
}
?>