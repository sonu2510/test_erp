<?php
class adhesive extends dbclass{
	//ruchi
	public function addAdhesive($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "adhesive` SET price = '" . $data['price']. "', adhesive_unit = '" . $data['unit']. "',adhesive_min_qty = '" . $data['min_qty']. "',make_id = '".$data['make']."', status = '" .$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	
	public function getAdhesive($adhesive_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "adhesive` WHERE adhesive_id = '" .(int)$adhesive_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateAdhesive($adhesive_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "adhesive` SET price = '" .$data['price']. "',adhesive_unit = '" . $data['unit']. "',adhesive_min_qty = '" . $data['min_qty']. "', make_id = '".$data['make']."', status = '" .$data['status']. "',  date_modify = NOW() WHERE adhesive_id = '" .(int)$adhesive_id. "'";
		$this->query($sql);
	}
	//ruchi
	/*public function InactiveAdhesive($adhesive_id,$data,$type){
		
		$sql = "UPDATE `" . DB_PREFIX . "adhesive` SET  status = '0',  date_modify = NOW() WHERE adhesive_id = '" .(int)$adhesive_id. "'";
		$this->query($sql);
		
		$sql1 = "INSERT INTO `" . DB_PREFIX . "adhesive` SET price = '" . $data['price']. "', type = '".$type."', status = '" .$data['status']. "', date_added = NOW()";
		$this->query($sql1);
		return $this->getLastId();
	}*/
	
	public function getTotalAdhesive(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "adhesive`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getAdhesives($data){
		$sql = "SELECT adhesive_id,price,m.make_id,m.status,make_name FROM `" . DB_PREFIX . "adhesive`AS a, `" . DB_PREFIX . "product_make` AS m WHERE m.make_id=a.make_id";
		
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
	
		public function getmakeData(){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make`  WHERE is_delete='0' AND status ='1'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	//===================== INK SOLVENT ==============
	//ruchi
	public function addAdhesiveSolvent($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "adhesive_solvent` SET price = '" . $data['price']. "',adhesive_solvent_unit = '" . $data['unit']. "', adhesive_solvent_min_qty = '" . $data['min_qty']. "',make_id = '".$data['make']."', status = '" .$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateAdhesiveSolvent($adhesive_solvent_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "adhesive_solvent` SET price = '" .$data['price']. "',adhesive_solvent_unit = '" . $data['unit']. "',adhesive_solvent_min_qty = '" . $data['min_qty']. "', make_id = '".$data['make']."', status = '" .$data['status']. "',  date_modify = NOW() WHERE adhesive_solvent_id = '" .(int)$adhesive_solvent_id. "'";
		$this->query($sql);
	}
	//ruchi
	
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
			$sql = "SELECT adhesive_solvent_id,price,m.make_id,m.status,make_name FROM `" . DB_PREFIX . "adhesive_solvent`AS a, `" . DB_PREFIX . "product_make` AS m WHERE m.make_id=a.make_id";
		
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