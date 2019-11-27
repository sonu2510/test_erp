<?php
class country extends dbclass{
	
	// ruchi -->
	public function addCountry($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "country` SET country_name = '".$data['country_name']."', country_code = '".$data['country_code']."', currency_code = '".$data['currency_code']."',currency_id = '0', tax = '".$data['tax']."',default_courier_id='".$data['courier']."', status = '" .(int)$data['status']. "',foreign_port='".$data['foreign_port']."',date_added = NOW()";
		$this->query($sql);
		//echo $sql;die;
		return $this->getLastId();
	}
	
	public function updateCountry($country_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "country` SET country_name = '".$data['country_name']."', country_code = '".$data['country_code']."',currency_code = '".$data['currency_code']."', currency_id = '0', tax = '".$data['tax']."', default_courier_id='".$data['courier']."', status = '" .(int)$data['status']. "',foreign_port='".$data['foreign_port']."', date_modify = NOW() WHERE country_id = '" .(int)$country_id. "'";
		$this->query($sql);
	}
	// ruchi -->
	
	public function getCountry($country_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" .(int)$country_id. "'";
		$data = $this->query($sql);
		//printr($data->num_rows);
		//die;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCouriers(){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "courier WHERE is_delete = 0 AND status=1";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
	}
	
	public function getCurrencyList(){
		
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "currency WHERE is_delete = 0 AND status=1");
		return $data->rows;		
	}
	
	public function updateCurrencyPrice($country_id,$new_price){
		
		$sql = "UPDATE " . DB_PREFIX . "country SET currency_price ='".$new_price."' WHERE country_id = '".$country_id."' ";
		$this->query($sql);		
	}
	
	public function getTotalCountry($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "country` c LEFT JOIN `" . DB_PREFIX . "courier` cu ON c.default_courier_id=cu.courier_id
		LEFT JOIN `" . DB_PREFIX . "currency` crn ON c.currency_id = crn.currency_id WHERE c.is_delete = '0'";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['country'])){
				$sql .= " AND c.country_name LIKE '%".$filter_data['country']."%' ";
			}
			
			if(!empty($filter_data['country_code'])){
				$sql .= " AND c.country_code = '".$filter_data['country_code']."' ";
			}
			
			if(!empty($filter_data['currency_code'])){
				$sql .= " AND crn.currency_code = '".$filter_data['currency_code']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND c.status = '".$filter_data['status']."' ";
			}	
			
			if($filter_data['filter_courier'] != ''){
				$sql .= " AND c.default_courier_id = '".$filter_data['filter_courier']."' ";
			}			
		}
				
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getCountrys($data,$filter_data=array()){
		$sql = "SELECT crn.*,cu.courier_name,c.* FROM `" . DB_PREFIX . "country` c LEFT JOIN `" . DB_PREFIX . "courier` cu ON c.default_courier_id=cu.courier_id  LEFT JOIN `" . DB_PREFIX . "currency` crn ON c.currency_id = crn.currency_id WHERE c.is_delete = '0'";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['country'])){
				$sql .= " AND c.country_name LIKE '%".$filter_data['country']."%' ";
			}
			
			if(!empty($filter_data['country_code'])){
				$sql .= " AND c.country_code = '".$filter_data['country_code']."' ";
			}
			
			if(!empty($filter_data['currency_code'])){
				$sql .= " AND crn.currency_code = '".$filter_data['currency_code']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND c.status = '".$filter_data['status']."' ";
			}
			
			if($filter_data['filter_courier'] != ''){
				$sql .= " AND c.default_courier_id = '".$filter_data['filter_courier']."' ";
			}				
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY c.country_id";	
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
		
		$data = $this->query($sql);
		//printr($data);
		//die;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getZone($country_id,$courier_id){
		
		$sql  = "SELECT cz.zone FROM `" . DB_PREFIX . "courier_zone_country` czc LEFT JOIN `" . DB_PREFIX . "courier_zone` cz ON cz.courier_zone_id = czc.courier_zone_id WHERE czc.country_id='".$country_id."' AND czc.courier_id='".$courier_id."' ";
		
		$data = $this->query($sql);
		
		if($data->row){
			return $data->row['zone'];
		}else{
			return '';	
		}
	}
	
	public function updateStatus($status,$data){
		//printr($data);
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "country` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE country_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "country` SET is_delete = '1', date_modify = NOW() WHERE country_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
		//echo $sql;die;
	}
	
	public function updateCountryStatus($country_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "country` SET status = '" .(int)$status_value ."' WHERE country_id = '".$country_id ."' ";
	   $this->query($sql);	
	}
	
}
?>