<?php
class cppAdhesive extends dbclass{
	
	public function addAdhesive($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "cpp_adhesive` SET price = '" . $data['price']. "', status = '" .$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	
	public function getCppAdhesive($adhesive_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "cpp_adhesive` WHERE cpp_adhesive_id = '" .(int)$adhesive_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateCppAdhesive($adhesive_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "cpp_adhesive` SET price = '" .$data['price']. "', status = '" .$data['status']. "',  date_modify = NOW() WHERE cpp_adhesive_id = '" .(int)$adhesive_id. "'";
		$this->query($sql);
	}
	
	public function getTotalCppAdhesive(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "cpp_adhesive`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getCppAdhesives($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "cpp_adhesive`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY adhesive_id";	
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
	
	//===================== INK SOLVENT ==============
	public function addAdhesiveSolvent($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "adhesive_solvent` SET price = '" . $data['price']. "', status = '" .$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateAdhesiveSolvent($adhesive_solvent_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "adhesive_solvent` SET price = '" .$data['price']. "', status = '" .$data['status']. "',  date_modify = NOW() WHERE adhesive_solvent_id = '" .(int)$adhesive_solvent_id. "'";
		$this->query($sql);
	}
	
	public function getAdhesiveSolvent($adhesive_solvent_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "adhesive_solvent` WHERE adhesive_solvent_id = '" .(int)$adhesive_solvent_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalAdhesiveSolvent(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "adhesive_solvent`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getAdhesiveSolvents($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "adhesive_solvent`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY adhesive_solvent_id";	
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