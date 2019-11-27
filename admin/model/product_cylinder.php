<?php
class productCylinder extends dbclass{
	
	
	public function getCylinder($cylinder_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_cylinder_vendor` WHERE product_cylinder_vendor_id = '" .(int)$cylinder_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function addCylinder($data,$user_id,$user_type_id){
		
		$this->query("INSERT INTO `" . DB_PREFIX . "product_cylinder_vendor` SET price = '" . $data['price']. "', type = '".$data['type']."', status = '1', date_added = NOW(),date_modify = NOW(),user_id='".$user_id."',	user_type_id = '".$user_type_id."' ");
	}
	
	public function updateCylinder($cylinder_id,$data,$user_id,$user_type_id){

		$sql = "UPDATE `" . DB_PREFIX . "product_cylinder_vendor` SET price = '" .$data['price']. "', type = '".$data['type']."', status = '1',  
		date_modify = NOW(),user_id='".$user_id."', user_type_id ='".$user_type_id."' WHERE product_cylinder_vendor_id = '" .(int)$cylinder_id. "'";
		
		$this->query($sql);
	}
	
	public function getTotalCylinder(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_cylinder_vendor`";
		$data = $this->query($sql);
		if($data->row['total']>0){
			return 1;	
		}else{
			return false;
		}

		//return $data->row['total'];	
		
	}
	
	public function getProductCylinders($data){
		
		//$sql = "SELECT * FROM `" . DB_PREFIX . "product_cylinder_vendor`";
		
		/*if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_cylinder_vendor_id";	
		}*/
		$sql .= " ORDER BY type DESC";	
		
		/*if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}*/
		
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
	
	public function getProductCylindersNew($data){
		$sql = "( SELECT * FROM `product_cylinder_vendor` WHERE type = 1 ORDER BY date_added DESC LIMIT 0,1) UNION ( SELECT * FROM `product_cylinder_vendor` WHERE type = 0 ORDER BY date_added DESC LIMIT 0,1)";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}				
		
	}
	
	public function updateInk($ink_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "ink_master` SET price = '" .$data['price']. "', status = '" .$data['status']. "',  date_modify = NOW() WHERE ink_master_id = '" .(int)$ink_id. "'";
		$this->query($sql);
	}
	
	public function getTotalInk(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "ink_master`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getInks($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "ink_master`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ink_master_id";	
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
	public function addInkSolvent($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "ink_solvent` SET price = '" . $data['price']. "', status = '" .$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateInkSolvent($solvent_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "ink_solvent` SET price = '" .$data['price']. "', status = '" .$data['status']. "',  date_modify = NOW() WHERE ink_solvent_id = '" .(int)$solvent_id. "'";
		$this->query($sql);
	}
	
	public function getProductCylinder($cylinder_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_cylinder_vendor` WHERE product_cylinder_vendor_id = '" .(int)$cylinder_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
}
?>