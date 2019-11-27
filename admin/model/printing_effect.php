<?php
class printingEffect extends dbclass{
	
	public function addEffect($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "printing_effect` SET effect_name = '" .$data['name']. "', price = '" .(float)$data['price']. "', multi_by = '" .(float)$data['multi_by']. "', status = '" .(int)$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateEffect($effect_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "printing_effect` SET effect_name = '" .$data['name']. "', price = '" .(float)$data['price']. "', multi_by = '" .(float)$data['multi_by']. "', status = '" .(int)$data['status']. "', date_modify = NOW() WHERE printing_effect_id = '" .(int)$effect_id. "'";
		$this->query($sql);
	}
	
	public function getEffect($effect_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_effect` WHERE printing_effect_id = '" .(int)$effect_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalEffect(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "printing_effect`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getEffects($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_effect`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY effect_name";	
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