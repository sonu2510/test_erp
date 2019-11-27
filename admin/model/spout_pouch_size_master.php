<?php
class spout_pouch_size_master extends dbclass{
	// kinjal -->
	public function addTool($data){
		$sql = "INSERT INTO " . DB_PREFIX . "spout_pouch_size_master SET product_id = '".(int)$data['product_id']."',spout_type_id = '".(int)$data['spout_type_id']."', volume = '" .(int)$data['volume']. "',width = '".$detail['width']."', height = '" .(int)$data['height']. "', gusset = '" . (float)$data['gusset']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
		
	}
	// kinjal -->
	public function updateTool($product_id,$data){
		$this->query("DELETE FROM " . DB_PREFIX . "spout_pouch_size_master WHERE product_id='".$product_id."'");
		$gusset='';
		foreach($data['product_details'] as $key=>$details){
			foreach($details as $detail){
				
				$gusset='gusset = "",';
				if(isset($detail['gusset']) && $detail['gusset']!='')
				{
					$gusset='gusset = "'.$detail['gusset'].'",';
				}
				
				$this->query("INSERT INTO `" . DB_PREFIX . "spout_pouch_size_master` SET product_id='".$product_id."',spout_type_id='".$detail['spout_type_id']."', volume='".$detail['volume']."', width = '".$detail['width']."', height = '".$detail['height']."',".$gusset." date_added = NOW(), date_modify = NOW()");
				//echo "INSERT INTO `" . DB_PREFIX . "spout_pouch_size_master` SET product_id='".$product_id."',spout_type_id='".$detail['spout_type_id']."', volume='".$detail['volume']."', width = '".$detail['width']."', height = '".$detail['height']."',".$gusset." date_added = NOW(), date_modify = NOW()";
				//die;
			}
		}
	}
	
	public function getToolPrices($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "spout_pouch_size_master` WHERE product_id = '" .(int)$product_id. "' ORDER BY spout_type_id ASC";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getTotalActiveProducts(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0 AND spout_pouch_available = 1";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	
	public function getProduct($product_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` p WHERE product_id='".$product_id."'";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getProducts($data){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0  AND spout_pouch_available = 1";
		
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