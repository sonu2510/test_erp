<?php
class productTransport extends dbclass{
	
	public function addTransport($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_transport` SET from_quantity = '" .(int)$data['from_quantity']. "', to_quantity = '" .(int)$data['to_quantity']. "', air = '" .(float)$data['buy_air']. "', sea = '" .(float)$data['buy_sea']. "', other = '" .(float)$data['other']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateTransport($transport_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_transport` SET from_quantity = '" .(int)$data['from_quantity']. "', to_quantity = '" .(int)$data['to_quantity']. "', air = '" .(float)$data['buy_air']. "', sea = '" .(float)$data['buy_sea']. "', other = '" .(float)$data['other']. "', date_modify = NOW() WHERE product_transport_id = '" .(int)$transport_id. "'";
		$this->query($sql);
	}
	
	public function getTransport($transport_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_transport` WHERE product_transport_id = '" .(int)$transport_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalTransport(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_transport`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTransports($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_transport`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_transport_id";	
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
	
	//########################### ROll TRANSPORT
	public function addRollTransport($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_roll_transport` SET from_kgs = '" .(int)$data['from_kgs']. "', to_kgs = '" .(int)$data['to_kgs']. "', price_kgs = '" .(float)$data['price_kgs']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateRollTransport($transport_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_roll_transport` SET from_kgs = '" .(int)$data['from_kgs']. "', to_kgs = '" .(int)$data['to_kgs']. "', price_kgs = '" .(float)$data['price_kgs']. "', date_modify = NOW() WHERE product_roll_transport_id = '" .(int)$transport_id. "'";
		$this->query($sql);
	}
	
	public function getRollTransport($transport_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_roll_transport` WHERE product_roll_transport_id = '" .(int)$transport_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalRollTransport(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_roll_transport`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getRollTransports($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_roll_transport`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_roll_transport_id";	
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