<?php
class laser_scoring_prices extends dbclass{
	
	
	public function addScoringPrice($price,$laser_scoring_type_id,$product_id){
		$sql = "INSERT INTO `" . DB_PREFIX . "laser_scoring_prices` SET  laser_product_id = '" .$product_id. "',laser_scoring_type_id = '".$laser_scoring_type_id."',laser_scoring_price='".$price."',status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
		
		$this->query($sql);
	}
	
	public function update_scoring_price($price,$laser_scoring_type_id,$product_id){
		
		$sql = "UPDATE `" . DB_PREFIX . "laser_scoring_prices` SET  laser_scoring_price='".$price."', date_added = NOW() WHERE laser_product_id = '" .(int)$product_id. "' AND laser_scoring_type_id = '" .(int)$laser_scoring_type_id. "'";
		$this->query($sql);	
		
	}
	
	public function getTotalProduct(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE is_delete = 0 AND status=1 AND product_id IN (3,22,20,4,5,7)";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getProduct($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE is_delete = 0 AND status=1 AND product_id IN (3,22,20,4,5,7)";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_id";	
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
	
	public function getTotalLaserScoringTypes(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "laser_scoring_types` WHERE is_delete = 0 AND status=1 " ;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getLaserScoringTypes($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "laser_scoring_types` WHERE is_delete = 0 AND status=1";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY type_id";	
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
	
	
	public function getScoringprice($product_id,$laser_scoring_type_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "laser_scoring_prices` WHERE laser_product_id = '" .(int)$product_id. "' AND laser_scoring_type_id = '" .(int)$laser_scoring_type_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateVolumeStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "laser_scoring_prices` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE 
		pouch_volume_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "laser_scoring_prices` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE pouch_volume_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "laser_scoring_prices` SET is_delete = '1', date_modify = NOW() WHERE pouch_volume_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	public function all_product()
	{
		 $sql = "SELECT * FROM product WHERE is_delete = 0 AND status = 1 ORDER BY product_name ASC";
		
		 $data = $this->query($sql);
		
		 if($data->num_rows){
			return $data->rows; 
			}
			else{
				return false;
			}
	}
	public function all_laser_scoring_types()
	{
		 $sql = "SELECT * FROM laser_scoring_types WHERE is_delete = 0 AND status = 1 ";
		
		 $data = $this->query($sql);
		
		 if($data->num_rows){
			return $data->rows; 
			}
			else{
				return false;
			}
	}
}
?>