<?php
class productProfit extends dbclass{
	
	public function addProfit($data){
		//[kinjal] : add watsage per in query[30 apr 2015(2:54 pm)]
		$sql = "INSERT INTO `" . DB_PREFIX . "product_profit` SET product_id = '".(int)$data['product_id']."', from_quantity = '" .(int)$data['from_quantity']. "', to_quantity = '" .(int)$data['to_quantity']. "', profit = '" . (float)$data['profit']. "',wastage_per='".(float)$data['wastage']."', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateProfit($product_id,$data){
		
		$this->query("DELETE FROM `" . DB_PREFIX . "product_profit` WHERE product_id='".$product_id."'");
		//[kinjal] : add watsage per in query[30 apr 2015(2:54 pm)]
		foreach($data['product_details'] as $key=>$details){
			//printr($details);die;
			foreach($details as $detail){ 
				//echo $details['quantity']."<br/>";
				//echo $detail['size']."==".$detail['profit']; 
				$this->query("INSERT INTO `" . DB_PREFIX . "product_profit` SET product_id='".$product_id."', quantity_id='".$detail['quantity_id']."',volume='".$detail['volume']."', size_from='".$detail['size_from']."', size_to = '".$detail['size_to']."', profit = '".$detail['profit']."', plus_minus_quantity = '".$detail['plus_minus']."',
				wastage_per='".$detail['wastage']."', date_added = NOW(), date_modify = NOW()");	
			}
		}
		//die;
	}
	
	public function getProfitPrices($product_id,$quantity_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_profit` WHERE product_id = '" .(int)$product_id. "' AND quantity_id='".$quantity_id."' ";
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
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_profit`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTotalActiveProducts($type=''){
	    $cond='';
	    if($type!='')
	        $cond= ' AND label_available=1';
	    
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0 $cond";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getProfits($data){
		$sql = "SELECT pp.*, p.product_name FROM " . DB_PREFIX . "product_profit pp INNER JOIN " . DB_PREFIX . "product p ON(pp.product_id = p.product_id) ";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pp.product_profit_id";	
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
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_quantity` WHERE status=1 AND is_delete=0");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
	}
	
	public function getProducts($data,$type=''){
		$cond='';
	    if($type!='')
	        $cond= ' AND label_available=1';
	        
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0 $cond";
		
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
	
	//Label Profit
	public function getLabelQuantityData(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "label_quantity` WHERE status=1 AND is_delete=0");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
	}
	
	public function addLabelProfit($data){

		$sql = "INSERT INTO `" . DB_PREFIX . "label_profit` SET product_id = '".(int)$data['product_id']."', quantity_id='".$data['quantity_id']."',volume='".$data['volume']."',size_from = '" .(int)$data['size_from']. "', size_to = '" .(int)$data['size_to']. "', profit = '" . (float)$data['profit']. "',profit_poor = '".(float)$data['profit_poor']."',profit_more_poor = '".(float)$data['profit_more_poor']."',tool_price_stock = '".(float)$data['tool_price_stock']."',tool_price_custom = '".(float)$data['tool_price_custom']."', date_added = NOW()";
	    $this->query($sql);
		return $this->getLastId();
	}
	
	public function updateLabelProfit($product_id,$data){
		foreach($data['product_details'] as $key=>$details){
			foreach($details as $detail){ 
				if($detail['label_profit_id']=='')
				{
    				$this->query("INSERT INTO `" . DB_PREFIX . "label_profit` SET product_id='".$product_id."', quantity_id='".$detail['quantity_id']."',volume='".$detail['volume']."', size_from='".$detail['size_from']."', size_to = '".$detail['size_to']."', profit = '".$detail['profit']."',profit_poor = '".$detail['profit_poor']."',profit_more_poor = '".$detail['profit_more_poor']."',tool_price_stock = '".$detail['tool_price_stock']."',tool_price_custom = '".$detail['tool_price_custom']."',  date_added = NOW(), date_modify = NOW()");
				}
				else
				{
				    $this->query("UPDATE `" . DB_PREFIX . "label_profit` SET product_id='".$product_id."', quantity_id='".$detail['quantity_id']."',volume='".$detail['volume']."', size_from='".$detail['size_from']."', size_to = '".$detail['size_to']."', profit = '".$detail['profit']."',profit_poor = '".$detail['profit_poor']."',profit_more_poor = '".$detail['profit_more_poor']."',tool_price_stock = '".$detail['tool_price_stock']."',tool_price_custom = '".$detail['tool_price_custom']."',  date_modify = NOW() WHERE label_profit_id = '" .(int)$detail['label_profit_id']. "'");
		        }
			}
		}
	}
	
	public function getLabelProfit($profit_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_profit` WHERE label_profit_id = '" .(int)$profit_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getLabelProfitPrices($product_id,$quantity_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_profit` WHERE product_id = '" .(int)$product_id. "' AND quantity_id='".$quantity_id."' ";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getLabelProfits($data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_profit`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY label_profit_id";	
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