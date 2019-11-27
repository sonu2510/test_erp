<?php
class templateVolume extends dbclass{
	
	public function addTemplateVolume($data){
		//printr($data);
		//die;		
		$sql = "INSERT INTO `" . DB_PREFIX . "template_volume` SET volume = '" .$data['volume']. "', measurement_id ='".$data['measurement']."', status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
		$this->query($sql);
	}
	
	public function updateTemplateVolume($product_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "template_volume` SET volume = '" .$data['volume']. "',measurement_id ='".$data['measurement']."',status = '" .$data['status']. "',  date_modify = NOW() WHERE product_id = '" .(int)$product_id. "'";
		$this->query($sql);		
	}
	
	public function getTotalTemplateVolume($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "template_volume` WHERE is_delete = 0";
		if(!empty($filter_data)){
			if(!empty($filter_data['volume'])){
				$sql .= " AND volume LIKE '%".$filter_data['volume']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		//printr($filter_data);die;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTemplateVolumes($data,$filter_data=array()){
		$sql = "SELECT u.*,m.measurement FROM `" . DB_PREFIX . "template_volume` as u ,`" . DB_PREFIX . "template_measurement` as m WHERE u.measurement_id=m.product_id AND u.is_delete = 0";
		//printr($filter_data);
		//die;
		if(!empty($filter_data)){
			if(!empty($filter_data['volume'])){
				$sql .= " AND volume LIKE '%".$filter_data['volume']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY volume";	
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
	//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getTemplateVolume($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "template_volume` WHERE product_id = '" .(int)$product_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function UpdateTemplateVolumeStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "template_volume` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_id = '".$id."' ";
		//echo $sql;
		//die;
		$this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "template_volume` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "template_volume` SET is_delete = '1', date_modify = NOW() WHERE product_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>