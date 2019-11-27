<?php 
// added by  sonu 21-09-2019
class printingEffect extends dbclass{
	 
	public function addEffect($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "label_printing_effect` SET effect_name = '" .$data['name']. "', price = '" .(float)$data['price']. "', multi_by = '" .(float)$data['multi_by']. "',remarks = '" .$data['remarks']. "',make_pouch='".implode(',',$data['make_pouch'])."', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateEffect($effect_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "label_printing_effect` SET effect_name = '" .$data['name']. "', price = '" .(float)$data['price']. "', multi_by = '" .(float)$data['multi_by']. "',remarks = '" .$data['remarks']. "', make_pouch='".implode(',',$data['make_pouch'])."', date_modify = NOW() WHERE printing_effect_id = '" .(int)$effect_id. "'";
		$this->query($sql);
	}
	
	public function getEffect($effect_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_printing_effect` WHERE printing_effect_id = '" .(int)$effect_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalEffect(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "label_printing_effect`  WHERE is_delete=0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getEffects($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_printing_effect` WHERE is_delete=0";
		
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
	public function updateStatus($status,$data){ 
			if($status == 0 || $status == 1){
				$sql = "UPDATE `" . DB_PREFIX . "label_printing_effect` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE printing_effect_id IN (" .implode(",",$data). ")";
				//echo $sql;die;
				$this->query($sql);
			}elseif($status == 2){
				$sql = "UPDATE `" . DB_PREFIX . "label_printing_effect` SET is_delete = '1', date_modify = NOW() WHERE printing_effect_id IN (" .implode(",",$data). ")";
				$this->query($sql);
			}

	}
	public function getMake(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_make` WHERE make_id IN (1,2)";
		$data = $this->query($sql);
        if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
}
?>