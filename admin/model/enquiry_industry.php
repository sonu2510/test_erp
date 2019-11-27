<?php
class enquiryIndustry extends dbclass{
	
	public function addIndustry($data){
		$this->query("INSERT INTO " . DB_PREFIX . "enquiry_industry SET industry = '" .$data['name']. "', status = '" .$data['status']. "', date_added = NOW()");
		return $this->getLastId();
	}
	
	public function updateIndustry($industry_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "enquiry_industry` SET industry = '" .$data['name']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE enquiry_industry_id = '" .(int)$industry_id. "'";
		//layer = '".serialize($data['layer'])."',
		$this->query($sql);
		
	}
	
	public function getTotalIndustry($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "enquiry_industry WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['industry'])){
				$sql .= " AND industry LIKE '%".$filter_data['industry']."%' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}		
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getIndustrys($data,$filter_data=array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "enquiry_industry WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['industry'])){
				$sql .= " AND industry LIKE '%".$filter_data['industry']."%' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}		
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY industry";	
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
		
	public function getIndustry($industry_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "enquiry_industry` WHERE enquiry_industry_id = '" .(int)$industry_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	
	
	public function updateIndustryStatus($id,$status){
		$this->query("UPDATE " . DB_PREFIX . "enquiry_industry SET status = '" .(int)$status. "', date_modify = NOW() WHERE enquiry_industry_id = '".$id."' ");
	}
	//[kinjal] updated on 17-7-2017	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "enquiry_industry` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE enquiry_industry_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "enquiry_industry` SET is_delete = '1', date_modify = NOW() WHERE enquiry_industry_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
}
?>