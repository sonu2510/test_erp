<?php
class enquirySource extends dbclass{
	
	public function addSource($data){
		$this->query("INSERT INTO " . DB_PREFIX . "enquiry_source SET source = '" .$data['name']. "', status = '" .$data['status']. "', date_added = NOW()");
		return $this->getLastId();
	}
	
	public function updateSource($source_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "enquiry_source` SET source = '" .$data['name']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE enquiry_source_id = '" .(int)$source_id. "'";
		//layer = '".serialize($data['layer'])."',
		$this->query($sql);
		
	}
	
	public function getTotalSource($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "enquiry_source WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['source'])){
				$sql .= " AND source LIKE '%".$filter_data['source']."%' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}		
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getSources($data,$filter_data=array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "enquiry_source WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['source'])){
				$sql .= " AND source LIKE '%".$filter_data['source']."%' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}		
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY enquiry_source_id";	
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
		
	public function getSource($source_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "enquiry_source` WHERE enquiry_source_id = '" .(int)$source_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	
	
	public function updateSourceStatus($id,$status){
		$this->query("UPDATE " . DB_PREFIX . "enquiry_source SET status = '" .(int)$status. "', date_modify = NOW() WHERE enquiry_source_id = '".$id."' ");
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "enquiry_source` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE enquiry_source_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "enquiry_source` SET is_delete = '1', date_modify = NOW() WHERE enquiry_source_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
}
?>