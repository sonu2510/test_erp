<?php
class machine_master extends dbclass{
	
	public function addMachine($data){
		//printr($data);die;
		$process ='';
			if(isset($data['process_name']))
				$process = json_encode($data['process_name']);
		$sql = "INSERT INTO ". DB_PREFIX ." machine_master SET machine_name = '" .$data['machine']. "',status = '" .$data['status']. "',production_process_id='".$process."', date_added = NOW(),date_modify = NOW(),is_delete=0";
	//	echo $sql;die;
		
		$this->query($sql);
		
	}
	
	public function updateMachine($machine_id,$data){
		$process ='';
			if(isset($data['process_name']))
				$process = json_encode($data['process_name']);
						 
		
		$sql = "UPDATE `" . DB_PREFIX . "machine_master` SET   machine_name = '" .$data['machine']. "',status = '" .$data['status']. "',production_process_id='".$process."',  date_modify = NOW() WHERE machine_id = '" .(int)$machine_id. "'";
		$this->query($sql);		
	}
	
	public function getTotalMachine($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "machine_master` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['machine'])){
				$sql .= " AND machine_name LIKE '%".$filter_data['machine']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getMachines($data,$filter_data=array()){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "machine_master` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['machine'])){
				$sql .= " AND machine_name LIKE '%".$filter_data['machine']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY machine_name";	
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
	
	public function getMachine($machine_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "machine_master` WHERE machine_id = '" .(int)$machine_id. "'";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateMachineStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "machine_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE 
		machine_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "machine_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE machine_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "machine_master` SET is_delete = '1', date_modify = NOW() WHERE machine_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	public function getProductionProcess()
	{
		$sql="SELECT * FROM production_process WHERE is_delete=0 AND status=1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
}
?>