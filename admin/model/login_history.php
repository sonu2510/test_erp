<?php
class loginHistory extends dbclass{
	
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
	
	/*public function insertDuration($login_id){
		
		$data = $this->query("SELECT cast(last_login as time) as time FROM `" . DB_PREFIX . "login_history` WHERE login_history_id='".$login_id."'");
		$login_time = date("H:i:s",strtotime($data->row['time']));
		$current_time = date("H:i:s");
		
		//echo $current_time . "===" .$login_time;die; 
		$duration = $current_time - $login_time;
		echo $duration;die;
		
	}*/	
	
	public function getTotalHistory(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "login_history`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getLoginReports($data){
		$sql = "SELECT am.user_name,am.email,lh.* FROM `" . DB_PREFIX . "login_history` lh LEFT JOIN `" . DB_PREFIX . "account_master` am ON lh.user_id=am.user_id AND lh.user_type_id=am.user_type_id";
		
		$sql .= " ORDER BY lh.login_history_id";	
		
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
		
		$report_array = array();
		
		$data = $this->query($sql);
		
		foreach($data->rows as $detail){
			if($detail['user_type_id']==1){
				
				$user_details = $this->query("SELECT CONCAT(first_name,'',last_name) as name FROM `" . DB_PREFIX . "user` WHERE user_id='".$detail['user_id']."'");
		
			}else if($detail['user_type_id']==2){
				
				$user_details = $this->query("SELECT CONCAT(first_name,'',last_name) as name FROM `" . DB_PREFIX . "employee` WHERE employee_id='".$detail['user_id']."'");
				
			}else if($detail['user_type_id']==3){
				
				$user_details = $this->query("SELECT CONCAT(first_name,'',last_name) as name FROM `" . DB_PREFIX . "client` WHERE client_id='".$detail['user_id']."'");
				
			}else if($detail['user_type_id']==4){
				$user_details = $this->query("SELECT CONCAT(first_name,'',last_name) as name FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id='".$detail['user_id']."'");
				
			}else{
				
				$user_details = $this->query("SELECT CONCAT(first_name,'',last_name) as name FROM `" . DB_PREFIX . "associate` WHERE associate_id='".$detail['user_id']."'");
				
			}
			
			$report_array[] = array(			
				'user_name' => $detail['user_name'],
				'email' => $detail['email'],
				'login_status' => $detail['login_status'],
				'login_duration' => $detail['login_duration'],
				'ip' => $detail['ip'],
				'browser' => $detail['browser'],
				'last_login' => $detail['last_login'],
				'status' => $detail['last_login'],
				'name' => $user_details->row['name']
			); 			
		}
		
		//printr($report_array);die;
		if(!empty($report_array)){
			return $report_array;
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
	
}
?>