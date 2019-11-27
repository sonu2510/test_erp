<?php
class pouchVolume extends dbclass{
	
	public function addVolume($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "pouch_volume` SET volume = '" .$data['volume']. "',volume_us='".$data['volume_us']."', status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
		$this->query($sql);
	}
	
	public function updateVolume($volume_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "pouch_volume` SET volume = '" .$data['volume']. "',volume_us='".$data['volume_us']."', status = '" .$data['status']. "',  date_modify = NOW() WHERE pouch_volume_id = '" .(int)$volume_id. "'";
		//layer = '".serialize($data['layer'])."',
		$this->query($sql);		
	}
	
	public function getTotalVolume($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "pouch_volume` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['volume'])){
				$sql .= " AND volume LIKE '%".$filter_data['volume']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getVolumes($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_volume` WHERE is_delete = 0";
		
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
			$sql .= " ORDER BY pouch_volume_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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
	
	public function getVolume($volume_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_volume` WHERE pouch_volume_id = '" .(int)$volume_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateVolumeStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "pouch_volume` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE pouch_volume_id = '".$id."' ";
		$this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "product_material` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_material_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "product_material` SET is_delete = '1', date_modify = NOW() WHERE product_material_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getActiveLayer(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_layer` WHERE status='1' ";
		$sql .= " ORDER BY layer";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getMaterialName($material_id){
		$sql = "SELECT material_name FROM `" . DB_PREFIX . "product_material` WHERE product_material_id = '".(int)$material_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['material_name'];
		}else{
			return false;
		}
	}
	
	//use in quotation page
	public function getLayerMaterial($layer){
		$sql = "SELECT material_id FROM `" . DB_PREFIX . "product_layer_material` WHERE layer_id = '".(int)$layer."' ";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			$array = array();
			foreach($data->rows as $data){
				$array[] = array(
					'material_id'	=> $data['material_id'],
					'material_name'  => $this->getMaterialName($data['material_id'])
				);
			}
			$sortArray = sortAssociateArrayByKey($array,'material_name',SORT_ASC);
			return $sortArray;
		}else{
			return false;
		}
	}
	
}
?>