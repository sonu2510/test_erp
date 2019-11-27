<?php
class category extends dbclass{
	
	public function getTotalCategory(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "category`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getCategorys($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "category`";
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
	
	public function addCategory($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "category` SET name = '" .$data['name']. "', status = '" .(int)$data['status']. "', is_delete = '0', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
}
?>