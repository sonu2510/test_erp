<?php
class unitmaster extends dbclass{
	
	public function addProductunit($data){
		//printr($data);
		//die;		
		
		$sql1 = "SELECT * FROM `" . DB_PREFIX . "unit_master` WHERE  unit ='".$data['unit']."' AND is_delete=0";
		$data1=$this->query($sql1);
		
		if(!$data1->num_rows){
			$sql = "INSERT INTO `" . DB_PREFIX . "unit_master` SET  unit ='".$data['unit']."', status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
			$this->query($sql);
			return 1;
		}
		else{
		return 0;	
		}
	}
	
	public function updateProductunit($unit_id,$data){
		
		$sql1 = "SELECT * FROM `" . DB_PREFIX . "unit_master` WHERE  unit ='".$data['unit']."' AND is_delete=0 AND unit_id !=".$unit_id;
		$data1=$this->query($sql1);
		//printr($sql1);die;
		if(!$data1->num_rows){
			
			$sql = "UPDATE `" . DB_PREFIX . "unit_master` SET unit ='".$data['unit']."',status = '" .$data['status']. "',  date_modify = NOW() WHERE unit_id = '" .(int)$unit_id. "'";
			$this->query($sql);	
			return 1;
		}
		else{
			return 0;	
		}
	}
	
	public function getTotalProduct($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "unit_master` WHERE is_delete = 0";
		if(!empty($filter_data)){
			if(!empty($filter_data['unit'])){
				$sql .= " AND unit LIKE '%".$filter_data['unit']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		//printr($filter_data);die;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getProducts($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "unit_master` WHERE is_delete = 0";
		//printr($filter_data);
		//die;
		if(!empty($filter_data)){
			if(!empty($filter_data['unit'])){
				$sql .= " AND unit LIKE '%".$filter_data['unit']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY unit";	
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
	
	public function getProduct($unit_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "unit_master` WHERE unit_id = '" .(int)$unit_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function UpdateProductunitStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "unit_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE unit_id = '".$id."' ";
		//echo $sql;
		//die;
		$this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "unit_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE unit_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "unit_master` SET is_delete = '1', date_modify = NOW() WHERE unit_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>