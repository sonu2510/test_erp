<?php

//jayashree
class currency extends dbclass{
	
	public function addCurrency($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "currency_setting` SET currency_name = '" .$data['name']. "', country_code = '" .$data['currcode']. "',user_type_id = '".$data['user_type_id']."',user_id = '".$data['user_id']."',symbol = '', price = '" .(float)$data['price']. "', status = '" .(int)$data['status']. "', date_added = NOW(),date_modify = NOW()";
		//echo $sql;
		//die;
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateCurrency($currency_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "currency_setting` SET currency_name = '" .$data['name']. "', country_code = '" .$data['currcode']. "', symbol = '', price = '" .(float)$data['price']. "',user_type_id = '".$data['user_type_id']."',user_id = '".$data['user_id']."', status = '" .(int)$data['status']. "', date_modify = NOW() WHERE currency_id = '" .(int)$currency_id. "'";
		
		//echo $sql;
		//die;
		$this->query($sql);
	}
	
	public function getCurrency($currency_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "currency_setting` WHERE currency_id = '" .(int)$currency_id. "'";
		//echo $sql;
		//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getcurrencycode($user_id,$user_type_id)
	{
		
		if($user_type_id == '1')
		{
			$sql = "SELECT u.default_curr,u.user_name,c.country_id,c.country_code,c.currency_code FROM user as u,country as c where c.country_code!='' and u.user_id = '".$user_id."' group by  c.currency_code";
					
		}
		elseif($user_type_id == '4')
		{
		    
			$sql = "SELECT ib.default_curr,ib.user_name,c.country_id,c.country_code,c.currency_code FROM international_branch as ib,country as c where ib.default_curr!= c.country_id and c.country_code!='' and ib.international_branch_id = '".$user_id."'            group by c.currency_code";
		}
		elseif($user_type_id == '3')
		{
			
			$sql = "SELECT cl.default_curr,cl.user_name,c.country_id,c.country_code,c.currency_code FROM client as cl,country as c where cl.default_curr!= c.country_id and c.country_code!='' and cl.client_id = '".$user_id."' group by c.currency_code";
			
		
		}
		elseif($user_type_id == '5')
		{
			
			$sql = "SELECT ass.default_curr,ass.user_name,c.country_id,c.country_code,c.currency_code FROM associate as ass,country as c where ass.default_curr!= c.country_id and c.country_code!='' and ass.associate_id = '".$user_id."' 
			group by c.currency_code";
					
		}// modify by [kinjal] on (17/10/2016)
		elseif($user_type_id == '2')
		{
			
			$employee = $this->query("SELECT user_type_id, user_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."'");
			if($employee->num_rows){
				$emp_id=$employee->row['user_id'];
				$emp_type_id = $employee->row['user_type_id'];
				$sql = "SELECT ib.default_curr,ib.user_name,c.country_id,c.country_code,c.currency_code FROM international_branch as ib,country as c where ib.default_curr!= c.country_id and c.country_code!='' and ib.international_branch_id = '".$emp_id."'            group by c.currency_code";
			}
					
		}
		$data = $this->query($sql);
		
		
		
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	
	public function getcurrencyname($user_id,$user_type_id)
	{
		
		if($user_type_id == '1')
		{
			$sql = "SELECT u.default_curr,u.user_name,c.country_id,c.country_code,c.currency_code FROM user as u,country as c where u.default_curr= c.country_id and c.country_code!='' and u.user_id = '".$user_id."' group by c.currency_code";
					
		}
		elseif($user_type_id == '4')
		{
			$sql = "SELECT ib.default_curr,ib.user_name,c.country_id,c.country_code,c.currency_code FROM international_branch as ib,country as c where ib.default_curr= c.country_id and c.country_code!='' and ib.international_branch_id = '".$user_id."' group by c.currency_code";
		
		}elseif($user_type_id == '3')
		{
			
			$sql = "SELECT cl.default_curr,cl.user_name,c.country_id,c.country_code,c.currency_code FROM client as cl,country as c where cl.default_curr= c.country_id and c.country_code!='' and cl.client_id  = '".$user_id."' group by c.currency_code";
			
		}
		elseif($user_type_id == '5')
		{
			
			$sql = "SELECT ass.default_curr,ass.user_name,c.country_id,c.country_code,c.currency_code FROM associate as ass,country as c where ass.default_curr= c.country_id and c.country_code!='' and ass.associate_id  = '".$user_id."' group by c.currency_code";
			
		}// modify by [kinjal] on (17/10/2016)
		elseif($user_type_id == '2')
		{
			
			$employee = $this->query("SELECT user_type_id, user_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."'");
			if($employee->num_rows){
				$emp_id=$employee->row['user_id'];
				$emp_type_id = $employee->row['user_type_id'];
				$sql = "SELECT ib.default_curr,ib.user_name,c.country_id,c.country_code,c.currency_code FROM international_branch as ib,country as c where ib.default_curr= c.country_id and c.country_code!='' and ib.international_branch_id = '".$emp_id."' group by c.currency_code";
			}
					
		}
		
		$data = $this->query($sql);
				
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
		
	}
	
	public function getTotalcurrencyname($user_id){
		
		$sql = "SELECT COUNT(*) as total FROM international_branch as ib,country as c where ib.default_curr= c.country_id and c.country_code!='' and ib.international_branch_id = '".$user_id."'";
		$data = $this->query($sql);
		return $data->row['total'];
	}
		
	public function getTotalCurrency($user_id,$user_type_id){
		// modify by [kinjal] on (17/10/2016)
		//$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "currency_setting` WHERE user_id = '".$user_id."' AND is_delete = '0'";
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "currency_setting` WHERE user_id = '".$user_id."' AND user_type_id = '".$user_type_id."' AND is_delete = '0'";
		//echo $sql;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getCurrencys($data,$user_id,$user_type_id){
		//$sql = "SELECT c.* FROM `" . DB_PREFIX . "currency_setting` c WHERE c.is_delete = '0'";
		
		/*if($_SESSION['LOGIN_USER_TYPE'] == 1 && $_SESSION['ADMIN_LOGIN_SWISS'] == 1 ){
			return false;
		} else {
			return true;
		}*/
		// modify by [kinjal] on (17/10/2016)
		/*$sql = "SELECT cs.currency_id,cs.user_id,cs.user_type_id,cs.currency_name,cs.price,cs.status,c.country_id,c.country_name,c.country_code,c.currency_code FROM currency_setting as cs,country as c where c.country_id = cs.country_code and cs.is_delete = '0' and cs.user_id = '".$user_id."' and cs.user_type_id!='2'";*/
		$sql = "SELECT cs.currency_id,cs.user_id,cs.user_type_id,cs.currency_name,cs.price,cs.status,c.country_id,c.country_name,c.country_code,c.currency_code FROM currency_setting as cs,country as c where c.country_id = cs.country_code and cs.is_delete = '0' and cs.user_id = '".$user_id."' AND cs.user_type_id = '".$user_type_id."'";
		
		$sql .= " ORDER BY cs.currency_id";	
		//echo $sql;
		//die;
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
			$sql = "UPDATE `" . DB_PREFIX . "currency_setting` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE currency_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "currency_setting` SET is_delete = '1', date_modify = NOW() WHERE currency_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}


public function checkcurrencyname($currency_name){
		
 		$sql = "SELECT currency_id FROM currency_setting WHERE currency_name='".$currency_name."' ";
		
         $data = $this->query($sql);
		 if($data->num_rows){
			return false;
		}else{
			return true;
		}
	
}

public function checkcurrencycode($currency_code){
		
 		$sql = "SELECT currency_id FROM currency_setting WHERE country_code='".$currency_code."' ";
		
         $data = $this->query($sql);
		 if($data->num_rows){
			return false;
		}else{
			return true;
		}
	
}

}
?>