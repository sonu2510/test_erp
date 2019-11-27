<?php
class label_punching extends dbclass{
	public function addLabelPunching($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "label_punching` SET from_qty = '" .(float)$data['from_qty']. "', to_qty = '" .(float)$data['to_qty']. "', price = '" .(float)$data['price']. "', date_added = NOW(), date_modify = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateLabelPunching($id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "label_punching` SET from_qty = '" .(float)$data['from_qty']. "', to_qty = '" .(float)$data['to_qty']. "', price = '" .(float)$data['price']. "', date_modify = NOW() WHERE label_punching_id = '" .(int)$id. "'";
		$this->query($sql);
	}
	
	public function getLabelPunching($id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_punching` WHERE label_punching_id = '" .(int)$id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	public function getlabelPunchings($data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_punching`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY label_punching_id";	
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