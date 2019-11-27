<?php
class toolPricing extends dbclass{
	
	public function addTool($data){
	//	echo "INSERT INTO " . DB_PREFIX . "product_extra_tool_price SET product_id = '".(int)$data['product_id']."', width_from = '" .(int)$data['width_from']. "', width_to = '" .(int)$data['width_to']. "', gusset = '" . (float)$data['gusset']. "',price = '" . (float)$data['price']. "', date_added = NOW()";die;
		$sql = "INSERT INTO " . DB_PREFIX . "product_extra_tool_price SET product_id = '".(int)$data['product_id']."', width_from = '" .(int)$data['width_from']. "', width_to = '" .(int)$data['width_to']. "', gusset = '" . (float)$data['gusset']. "',price = '" . (float)$data['price']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateTool($product_id,$data){
		
		$this->query("DELETE FROM " . DB_PREFIX . "product_extra_tool_price WHERE product_id='".$product_id."'");
		$gusset='';
		foreach($data['product_details'] as $key=>$details){
			//printr($details);die;
			foreach($details as $detail){
				//echo $details['quantity']."<br/>";
				//echo $detail['size']."==".$detail['profit']; 
				if(isset($detail['gusset']) && $detail['gusset']!='')
				{
					$gusset='gusset = "'.$detail['gusset'].'",';
				}
				$this->query("INSERT INTO `" . DB_PREFIX . "product_extra_tool_price` SET product_id='".$product_id."',  width_from='".$detail['width_from']."', width_to = '".$detail['width_to']."', ".$gusset."  price = '".$detail['price']."', date_added = NOW(), date_modify = NOW()");	
			}
		}
		//die;
	}
	
	public function getToolPrices($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' ORDER BY width_to ASC";
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
	
	public function getTotalTool(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_extra_tool_price`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTotalActiveProducts(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function gettools($data){
		$sql = "SELECT pp.*, p.product_name FROM " . DB_PREFIX . "product_extra_tool_price pp INNER JOIN " . DB_PREFIX . "product p ON(pp.product_id = p.product_id) ";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pp.product_tool_id";	
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
}
?>