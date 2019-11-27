<?php
//kinjal
class invoice_city extends dbclass{
	
	public function addcity($data)
	{
		$city = $data['city_name'];
		$sql1="Select city_name from invoice_city where city_name='".$city."' AND is_delete=0";
		$data1 = $this->query($sql1);
		if($data1->num_rows == 0)
		{
			$sql = "INSERT INTO `" . DB_PREFIX . "invoice_city` SET city_name = '" .ucwords($city). "',
		city_code = '".$data['city_code']."', status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
			$this->query($sql); 
			return 1;
		}
		else
		{	
			 echo '<script type="text/javascript">alert(" City Name Alreay Exists! Add Another ")</script>';
			 return 0;
		}
		
	}
	
	public function updateCity($id,$post)
	{	$city = $post['city_name'];
		$old_city = $post['old_city_name'];
		
		$sql1="Select city_name from invoice_city where city_name='".$city."' AND is_delete=0";
		$data1 = $this->query($sql1);
		if($data1->num_rows == 0 || $city == $old_city)
		{	
			$sql = "UPDATE `" . DB_PREFIX . "invoice_city` SET city_name = '" .ucwords($city). "',
			city_code = '".$post['city_code']."', status = '" .$post['status']. "',date_modify = NOW() Where invoice_city_id = '".$id."'";
			$this->query($sql);
			return 1;
		}
		else
		{	
				echo '<script type="text/javascript">alert(" City Name Alreay Exists! Add Another ")</script>';
				return 0;
		}
	}
	
	public function getTotalCity($filter_data=array()){

		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice_city` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['city_name'])){
				
			$sql .= " AND city_name LIKE '%".$filter_data['city_name']."%' ";		
			}
			if(!empty($filter_data['city_code'])){
				$sql .= " AND city_code LIKE '%".$filter_data['city_code']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		//echo $sql;
		$data = $this->query($sql);
		//printr($data);
		return $data->row['total'];
	}
	
	public function getInvcity($data,$filter_data=array()){
	
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_city` WHERE is_delete = 0 " ;
		
		if(!empty($filter_data)){
			if(!empty($filter_data['city_name'])){
				
			$sql .= " AND city_name LIKE '%".$filter_data['city_name']."%' ";		
			}
			if(!empty($filter_data['city_code'])){
				$sql .= " AND city_code LIKE '%".$filter_data['city_code']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY invoice_city_id";	
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
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCityData($city_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_city` WHERE invoice_city_id = '" .(int)$city_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getICity(){
		
		$sql = "SELECT city_name FROM `" . DB_PREFIX . "invoice_city`";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
		
	public function updateInvCityStatus($status,$data){
		//echo $status;
		if($status == 0 || $status == 1){
			$sql = "UPDATE " . DB_PREFIX . "invoice_city SET status = '" .(int)$status. "',  date_modify = NOW() WHERE invoice_city_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE " . DB_PREFIX . "invoice_city SET is_delete = '1', date_modify = NOW() WHERE invoice_city_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	
	
	public function updateCityStatus($city_id,$status_value){
		echo $city_id;
		echo $status_value;

		$sql = "UPDATE " . DB_PREFIX . "invoice_city SET status = '".$status_value."', date_modify = NOW() WHERE invoice_city_id = '" .(int)$city_id. "'";
		$this->query($sql);
	}
}
?>