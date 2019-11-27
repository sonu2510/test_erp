<?php
class productTransportSea extends dbclass{
	
	public function addTransportWidth($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_transport_sea_width` SET from_width = '" .(float)$data['from']. "', to_width = '" .(float)$data['to']. "', price = '" .(float)$data['price']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateTransportWidth($width_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_transport_sea_width` SET from_width = '" .(float)$data['from']. "', to_width = '" .(float)$data['to']. "', price = '" .(float)$data['price']. "', date_modify = NOW() WHERE product_transport_sea_width_id = '" .(int)$width_id. "'";
		$this->query($sql);
	}
	
	public function addTransportHeight($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_transport_sea_height` SET from_height = '" .(float)$data['from']. "', to_height = '" .(float)$data['to']. "', price = '" .(float)$data['price']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateTransportHeight($height_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_transport_sea_height` SET from_height = '" .(float)$data['from']. "', to_height = '" .(float)$data['to']. "', price = '" . $data['price']. "', date_modify = NOW() WHERE product_transport_sea_height_id = '" .(int)$height_id. "'";
		//echo $sql;die;
		$this->query($sql);
	}
	
	public function getWidth($width_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_transport_sea_width` WHERE product_transport_sea_width_id = '" .(int)$width_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getHeight($height_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_transport_sea_height` WHERE product_transport_sea_height_id = '" .(int)$height_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalTransportWidth(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_transport_sea_width`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTransportWidths($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_transport_sea_width`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_transport_sea_width_id";	
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
	
	public function getTotalTransportHeight(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_transport_sea_height`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTransportHeights($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_transport_sea_height`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_transport_sea_height_id";	
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
	
	//########################### ROLL PACKING
	public function addRollPacking($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_roll_packing` SET from_kgs = '" .(float)$data['from_kgs']. "', to_kgs = '" .(float)$data['to_kgs']. "', price_kgs = '" .(float)$data['price_kgs']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateRollPacking($id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_roll_packing` SET from_kgs = '" .(float)$data['from_kgs']. "', to_kgs = '" .(float)$data['to_kgs']. "', price_kgs = '" .(float)$data['price_kgs']. "', date_modify = NOW() WHERE product_roll_packing_id = '" .(int)$id. "'";
		$this->query($sql);
	}
	
	public function getRollPacking($id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_roll_packing` WHERE product_roll_packing_id = '" .(int)$id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalRollPacking(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_roll_packing`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getRollPackings($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_roll_packing`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_roll_packing_id";	
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