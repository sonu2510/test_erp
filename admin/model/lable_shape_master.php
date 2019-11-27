<?php 
// added by  sonu 21-09-2019
class lable_shape_master extends dbclass{
	 
	public function addShape($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "lable_shape_master` SET shape_name = '" .$data['name']. "',product_ids = '".implode(',',$data['product_ids'])."', status = '" .(int)$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateShape($shape_master_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "lable_shape_master` SET shape_name = '" .$data['name']. "',product_ids = '".implode(',',$data['product_ids'])."', status = '" .(int)$data['status']. "', date_modify = NOW() WHERE shape_master_id = '" .(int)$shape_master_id. "'";
		$this->query($sql);
	}
	
	public function getShapeDetails($shape_master_id){ 
		$sql = "SELECT * FROM `" . DB_PREFIX . "lable_shape_master` WHERE shape_master_id = '" .(int)$shape_master_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{ 
			return false;
		}
	}
	
	public function getTotalShape(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "lable_shape_master`  WHERE is_delete=0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getShape($data){ 
		$sql = "SELECT * FROM `" . DB_PREFIX . "lable_shape_master` WHERE is_delete=0";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY shape_name";	
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
	public function updateStatus($status,$data){ 
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "lable_shape_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE shape_master_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "lable_shape_master` SET is_delete = '1', date_modify = NOW() WHERE shape_master_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
    }
    public function getActiveProduct(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product`  WHERE is_delete=0 AND status=1 AND label_available = 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
}
?>