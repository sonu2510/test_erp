<?php
class pouchStyle extends dbclass{
	
	public function addStyle($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "pouch_style` SET style = '" .$data['style']. "', status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
		$this->query($sql);
	}
	
	public function updateStyle($style_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "pouch_style` SET style = '" .$data['style']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE pouch_style_id = '" .(int)$style_id. "'";
		//layer = '".serialize($data['layer'])."',
		$this->query($sql);		
	}
	
	public function getTotalStyle($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "pouch_style` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['style'])){
				$sql .= " AND style LIKE '%".$filter_data['style']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getStyles($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_style` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['style'])){
				$sql .= " AND style LIKE '%".$filter_data['style']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pouch_style_id";	
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
	
	public function getStyle($style_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_style` WHERE pouch_style_id = '" .(int)$style_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateStyleStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "pouch_style` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE pouch_style_id = '".$id."' ";
		$this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "pouch_style` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE pouch_style_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "pouch_style` SET is_delete = '1', date_modify = NOW() WHERE pouch_style_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>