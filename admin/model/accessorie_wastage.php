<?php
class accessorie_wastage extends dbclass{
	
	public function addProfit($data){
		//[kinjal] : add watsage per in query[30 apr 2015(2:54 pm)]
		$sql = "INSERT INTO `" . DB_PREFIX . "accessorie_wastage` SET product_id = '".(int)$data['product_id']."', from_quantity = '" .(int)$data['from_quantity']. "', to_quantity = '" .(int)$data['to_quantity']. "', profit = '" . (float)$data['profit']. "',wastage_per='".(float)$data['wastage']."', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateProfit($product_id,$data){
		//printr($data);die;
		$this->query("DELETE FROM `" . DB_PREFIX . "accessorie_wastage` WHERE product_id='".$product_id."'");
		//[kinjal] : add watsage per in query[30 apr 2015(2:54 pm)]
		foreach($data['product_details'] as $key=>$details){
			foreach($details as $detail){
				$zipper=json_encode($detail['zipper']);
				$spout=json_encode($detail['spout']);
				$accessorie=json_encode($detail['accessorie']);
				$tintie=json_encode($detail['tintie']);
				
				$this->query("INSERT INTO `" . DB_PREFIX . "accessorie_wastage` SET product_id='".$product_id."', quantity_id='".$detail['quantity_id']."', size_from='".$detail['size_from']."', size_to = '".$detail['size_to']."',zipper='".$zipper."', spout='".$spout."', accessorie='".$accessorie."', tin_tie='".$tintie."', date_added = NOW(), date_modify = NOW()");
			}
		}
		//die;
	}
	
	public function getWastagePrices($product_id,$quantity_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "accessorie_wastage` WHERE product_id = '" .(int)$product_id. "' AND quantity_id='".$quantity_id."' ";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProducts(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE status = 1 AND is_delete = 0");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}			
	}
	
	public function getTotalActiveProducts(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
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
	
	public function getQuantityData(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_quantity` WHERE status=1 AND is_delete=0");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
	}
	
	public function getProducts($data){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		
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
	
	public function getActiveZipper()
	{
		$sql = "Select * From `" . DB_PREFIX . "product_zipper` WHERE is_delete=0 AND status=1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductAccessorie(){
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductSpout(){
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductTinTie(){
		$data = $this->query("SELECT product_tintie_id, tintie_name, price FROM " . DB_PREFIX . "product_tintie WHERE status = '1' AND is_delete = '0' ORDER BY tintie_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
}
?>