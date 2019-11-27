<?php
class handle extends dbclass{
	
	public function addHandle($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "handle_price` SET  handle_name = '" .$data['handle_name']. "',handle_price = '" .$data['handle_price']. "',status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
		
		$this->query($sql);
	}
	
	public function updateHandle($handle_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "handle_price` SET  handle_name = '" .$data['handle_name']. "',handle_price = '" .$data['handle_price']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE handle_id = '" .(int)$handle_id. "'";
		$this->query($sql);		
	}
	
	public function getTotal(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "handle_price` WHERE is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function gethandle($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "handle_price` WHERE is_delete = 0";
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY handle_id";	
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
	
	public function gettype($handle_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "handle_price` WHERE handle_id = '" .(int)$handle_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateHandleStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "handle_price` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE handle_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "handle_price` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE handle_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "handle_price` SET is_delete = '1', date_modify = NOW() WHERE handle_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>