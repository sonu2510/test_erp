<?php
class stockProfit extends dbclass{
	
	public function addProfit($data){

		$sql = "INSERT INTO `" . DB_PREFIX . "stock_profit` SET product_id = '".(int)$data['product_id']."', from_quantity = '" .(int)$data['from_quantity']. "', to_quantity = '" .(int)$data['to_quantity']. "', profit = '" . (float)$data['profit']. "',profit_poor = '".(float)$data['profit_poor']."',profit_more_poor = '".(float)$data['profit_more_poor']."',date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	public function updateProfit($product_id,$data){
		
		$this->query("DELETE FROM `" . DB_PREFIX . "stock_profit` WHERE product_id='".$product_id."'");

		foreach($data['product_details'] as $key=>$details){
			/*$result = array_unique($details);
			foreach($result as $res) {
				printr($res);
			}*/
			
			foreach($details as $detail){
				$size_data = $this->getSizeData($detail['size_master_id']);				
				//echo $details['quantity']."<br/>";
				//echo $detail['size']."==".$detail['profit']; 
				$this->query("INSERT INTO `" . DB_PREFIX . "stock_profit` SET product_id='".$product_id."', quantity_id='".$detail['quantity_id']."', size_master_id='".$detail['size_master_id']."', height='".$size_data[0]['height']."', width = '".$size_data[0]['width']."', gusset = '".$size_data[0]['gusset']."', volume='".$size_data[0]['volume']."', profit = '".$detail['profit']."',profit_poor = '".$detail['profit_poor']."',profit_more_poor = '".$detail['profit_more_poor']."',date_added = NOW(), date_modify = NOW()");
			
			}
		}
		//die;
	}
	
	public function getProfitPrices($product_id,$quantity_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "stock_profit` WHERE product_id = '" .(int)$product_id. "' AND quantity_id='".$quantity_id."' ";
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
	
	public function getTotalProfit(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "stock_profit`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTotalActiveProducts(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getProfits($data){
		$sql = "SELECT pp.*, p.product_name FROM " . DB_PREFIX . "stock_profit pp INNER JOIN " . DB_PREFIX . "product p ON(pp.product_id = p.product_id) ";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pp.stock_profit_id";	
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
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "template_quantity` WHERE status=1 AND is_delete=0");
		
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
	
	//Roll Profit
	public function addRollProfit($data){

		$sql = "INSERT INTO `" . DB_PREFIX . "product_roll_profit` SET from_kg = '" .(int)$data['from_kg']. "', to_kg = '" .(int)$data['to_kg']. "', profit_kg = '" . (float)$data['profit_kg']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateRollProfit($profit_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_roll_profit` SET from_kg = '" .(int)$data['from_kg']. "', to_kg = '" .(int)$data['to_kg']. "', profit_kg = '" . (float)$data['profit_kg']. "', date_modify = NOW() WHERE product_roll_profit_id = '" .(int)$profit_id. "'";
		$this->query($sql);
	}
	
	public function getRollProfit($profit_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_roll_profit` WHERE product_roll_profit_id = '" .(int)$profit_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalRollProfit(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_roll_profit`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getRollProfits($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_roll_profit`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_roll_profit_id";	
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
	//edited by rohit
	public function getSize($product_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "size_master` WHERE status=0 AND product_id='".$product_id."'");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
	}
	public function getSizeNew($product_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "size_master` WHERE status=0 AND product_id='".$product_id."' AND product_zipper_id = 2 ");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
	}
	public function getSizeData($size_master_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "size_master` WHERE status=0 AND size_master_id='".$size_master_id."'");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
	}
	//rohit
	public function ZipperData ($product_zipper_id) {
		$sql = "select * from ".DB_PREFIX."product_zipper where product_zipper_id='".$product_zipper_id."'";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->row;
		} else {
			return false;
		}
	}
	//sonu
	public function Size_detail($size_master_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "size_master` WHERE status=0 AND size_master_id='".$size_master_id."'");
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
}
?>