<?php
class productPacking extends dbclass{
	
	public function addPacking($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_packing` SET from_total = '" .(float)$data['from']. "', to_total = '" .(float)$data['to']. "', price = '" .(float)$data['price']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updatePacking($packing_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_packing` SET from_total = '" .(float)$data['from']. "', to_total = '" .(float)$data['to']. "', price = '" .(float)$data['price']. "', date_modify = NOW() WHERE product_packing_id = '" .(int)$packing_id. "'";
		$this->query($sql);
	}
	
	
	public function getPacking($packing_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_packing` WHERE product_packing_id = '" .(int)$packing_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getHeight($height_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_packing_height` WHERE product_packing_height_id = '" .(int)$height_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalPackingPrice(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_packing`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getPackings($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_packing`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_packing_width_id";	
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
	
	public function getTotalPackingHeight(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_packing_height`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getPackingHeights($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_packing_height`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_packing_width_id";	
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
	//########################### END ROLL PACKING
	
	//########################### LABEL PACKING
	public function addLabelPacking($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "label_packing` SET from_total = '" .(float)$data['from_total']. "', to_total = '" .(float)$data['to_total']. "', price = '" .(float)$data['price']. "', date_added = NOW(), date_modify = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateLabelPacking($id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "label_packing` SET from_total = '" .(float)$data['from_total']. "', to_total = '" .(float)$data['to_total']. "', price = '" .(float)$data['price']. "', date_modify = NOW() WHERE label_packing_id = '" .(int)$id. "'";
		$this->query($sql);
	}
	
	public function getLabelPacking($id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_packing` WHERE label_packing_id = '" .(int)$id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	public function getlabelPackings($data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_packing`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY label_packing_id";	
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
	//########################### END LABEL PACKING
}
?>