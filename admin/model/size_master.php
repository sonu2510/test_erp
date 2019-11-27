<?php
class sizemaster extends dbclass{
	// kinjal -->
	public function addTool($data){
		$sql = "INSERT INTO " . DB_PREFIX . "size_master SET product_id = '".(int)$data['product_id']."',product_zipper_id = '".(int)$data['product_zipper_id']."', volume = '" .(int)$data['volume']. "',width = '".$detail['width']."', height = '" .$data['height']. "', gusset = '" . $data['gusset']. "', weight = '" .$data['weight']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
		
	}
	// kinjal -->
	public function updateTool($product_id,$data){
		$gusset='';
		foreach($data['product_details'] as $key=>$details){
			foreach($details as $detail){
				//printr($detail);//die;
				$gusset='gusset = "",';
				$weight='weight = "",';
				if(isset($detail['gusset']) && $detail['gusset']!='')
				{
					$gusset='gusset = "'.$detail['gusset'].'",';
				}
				if(isset($detail['weight']) && $detail['weight']!='')
				{
					$weight='weight = "'.$detail['weight'].'",';
				}
				if(isset($detail['size_master_id']) && $detail['size_master_id']!='')
				{
					
					$sql = "UPDATE `" . DB_PREFIX . "size_master` SET product_id='".$product_id."',product_zipper_id='".$detail['product_zipper_id']."', volume='".$detail['volume']."', width = '".$detail['width']."', height = '".$detail['height']."',".$gusset." ".$weight."  date_modify = NOW() WHERE size_master_id='".$detail['size_master_id']."'";
					//echo $sql;
					$this->query($sql);
				}
				else
				{
					$sql = "INSERT INTO `" . DB_PREFIX . "size_master` SET product_id='".$product_id."',product_zipper_id='".$detail['product_zipper_id']."', volume='".$detail['volume']."', width = '".$detail['width']."', height = '".$detail['height']."',".$gusset." ".$weight." date_added = NOW(), date_modify = NOW()";
					$this->query($sql);
				}
			
			}//die;
		}
	}
	/*public function updateTool($product_id,$data){
		
		$this->query("DELETE FROM " . DB_PREFIX . "size_master WHERE product_id='".$product_id."'");
		$gusset='';
		foreach($data['product_details'] as $key=>$details){
			//printr($details);die;
			foreach($details as $detail){
				//echo $details['quantity']."<br/>";
				//echo $detail['size']."==".$detail['profit']; 
				$gusset='gusset = "",';
				$weight='weight = "",';
				if(isset($detail['gusset']) && $detail['gusset']!='')
				{
					$gusset='gusset = "'.$detail['gusset'].'",';
				}
				if(isset($detail['weight']) && $detail['weight']!='')
				{
					$weight='weight = "'.$detail['weight'].'",';
				}
				$this->query("INSERT INTO `" . DB_PREFIX . "size_master` SET product_id='".$product_id."',product_zipper_id='".$detail['product_zipper_id']."', volume='".$detail['volume']."', width = '".$detail['width']."', height = '".$detail['height']."',".$gusset." ".$weight." date_added = NOW(), date_modify = NOW()");
				//echo "INSERT INTO `" . DB_PREFIX . "size_master` SET product_id='".$product_id."',product_zipper_id='".$detail['product_zipper_id']."', volume='".$detail['volume']."', width = '".$detail['width']."', height = '".$detail['height']."',".$gusset." ".$weight."  date_added = NOW(), date_modify = NOW()";
				//die;
			}
		}
		//die;
	}*/
	public function getToolPrices($product_id){
	    $order='';
	    if($product_id=='37' || $product_id=='11')
	        $order = " ORDER BY volume + 0 ASC";
		$sql = "SELECT * FROM `" . DB_PREFIX . "size_master` WHERE product_id = '" .(int)$product_id. "' $order";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getZipper(){
		$sql = "SELECT * FROM " . DB_PREFIX . "product_zipper WHERE is_delete = 0 ";
		$data = $this->query($sql);
		//printr($data);
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
	
	public function gettools($data){
		$sql = "SELECT pp.*, p.product_name FROM " . DB_PREFIX . "size_master pp INNER JOIN " . DB_PREFIX . "product p ON(pp.product_id = p.product_id) ";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pp.size_id";	
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
	
	public function remove_size_record($size_master_id)
	{
		$this->query("DELETE FROM `" . DB_PREFIX . "size_master`  WHERE size_master_id='".$size_master_id."'");
	}
}
?>