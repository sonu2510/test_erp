<?php
class currency extends dbclass{
	
	public function addCurrency($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "currency` SET currency_name = '" .$data['name']. "', currency_code = '" .$data['code']. "', symbol = '', price = '" .(float)$data['price']. "', status = '" .(int)$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateCurrency($currency_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "currency` SET currency_name = '" .$data['name']. "', currency_code = '" .$data['code']. "', symbol = '', price = '" .(float)$data['price']. "', status = '" .(int)$data['status']. "', date_modify = NOW() WHERE currency_id = '" .(int)$currency_id. "'";
		$this->query($sql);
	}
	
	public function getCurrency($currency_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "currency` WHERE currency_id = '" .(int)$currency_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalCurrency(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "currency` WHERE is_delete = '0'";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getCurrencys($data){
		$sql = "SELECT c.* FROM `" . DB_PREFIX . "currency` c WHERE c.is_delete = '0'";
		
		/*if($_SESSION['LOGIN_USER_TYPE'] == 1 && $_SESSION['ADMIN_LOGIN_SWISS'] == 1 ){
			return false;
		} else {
			return true;
		}*/
		
		
		$sql .= " ORDER BY currency_id";	
		

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
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "currency` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE currency_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "currency` SET is_delete = '1', date_modify = NOW() WHERE currency_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}


public function checkcurrencyname($currency_name){
		
 		$sql = "SELECT currency_id FROM currency WHERE currency_name='".$currency_name."' ";
		
         $data = $this->query($sql);
		 if($data->num_rows){
			return false;
		}else{
			return true;
		}
	
}

public function checkcurrencycode($currency_code){
		
 		$sql = "SELECT currency_id FROM currency WHERE currency_code='".$currency_code."' ";
		
         $data = $this->query($sql);
		 if($data->num_rows){
			return false;
		}else{
			return true;
		}
	
}

}
?>