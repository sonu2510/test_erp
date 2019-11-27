<?php
class employee extends dbclass{
	
	public function addEmployee($data,$user_type_id,$user_id){
		//$sql = "INSERT INTO " . DB_PREFIX . "employee SET first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', telephone = '" . $data['telephone'] . "', ip = '" . $_SERVER['REMOTE_ADDR'] . "', status = '".(int)$data['status']."', approved = '1', date_added = NOW()";
	    $acnt =array();
		
		$lang=array();
    	if(isset($data['lang'])&& !empty($data['lang']))
    	{
    	    $lang = implode(",",$data['lang']);
    	}
		$sql = "INSERT INTO " . DB_PREFIX . "employee SET user_type_id = '".$user_type_id."', user_id = '".$user_id."', first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', telephone = '" . $data['telephone'] . "', user_name = '".$data['user_name']."', ip = '" . $_SERVER['REMOTE_ADDR'] . "', status = '".(int)$data['status']."', email_signature = '".$data['email_signature']."',stock_order_price='".$data['stock_order']."',multi_quotation_price='".$data['multi_quotation_price']."',stock_price_compulsory='".$data['stock_comp']."',approved = '1', date_added = NOW(),user_type = '".$data['user_type']."',associate_acnt='".$acnt."',lang_id='".$lang."'";
		
		$this->query($sql);
		$employee_id = $this->getLastId();
	
		
		if(isset($data['associate_acnt']) && !empty($data['associate_acnt']))
		{
			$acnt = implode(",",$data['associate_acnt']);
			foreach($data['associate_acnt'] as $ass)
			{
				$account = explode("=",$ass);
				//printr($ass);
				if($account[0]==2)
				{$s=array();
					//$sel="SELECT * FROM";
					if(in_array($ass,$data['associate_acnt']))
					{
						$s[]= $ass;
						$a = array_diff( $data['associate_acnt'], $s );
						array_push($a,'2='.$employee_id);
						$arr = implode(",",$a);
						$this->query("UPDATE " . DB_PREFIX . "employee SET associate_acnt = '" .$arr. "' WHERE employee_id = '" .$account[1]. "'");
					}
				}
				else if($account[0]==4)
				{  $s=array();
					if(in_array($ass,$data['associate_acnt']))
					{
						$s[]= $ass;
						$a = array_diff( $data['associate_acnt'], $s );
						array_push($a,'2='.$employee_id);
						$arr = implode(",",$a);
						//echo "UPDATE " . DB_PREFIX . "international_branch SET associate_acnt = '" .$arr. "' WHERE international_branch_id = '" .$account[1]. "'";
						$this->query("UPDATE " . DB_PREFIX . "international_branch SET associate_acnt = '" .$arr. "' WHERE international_branch_id = '" .$account[1]. "'");
					}
					
				}
				
				//printr($account);die;
			}
			//printr($data['associate_acnt']);
			//die;
			
		}
		//insert account master
		$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		$this->query("INSERT INTO `" . DB_PREFIX . "account_master` SET user_type_id = '2', user_id = '" .(int)$employee_id. "', user_name = '" .$data['user_name']. "', salt = '" . $salt . "', password = '" . sha1($salt . sha1($salt . sha1($data['password']))) . "' , password_text = '" .$data['password']. "', email = '" .$data['email']. "', commission = '" .$data['commission']. "', date_added = NOW()");
		
		//insert address
		$this->query("INSERT INTO " . DB_PREFIX . "address SET user_type_id = '2', user_id = '" . (int)$employee_id . "', first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', company = '', address = '" . $data['address'] . "', city = '" . $data['city'] . "', state = '" . $data['state'] . "', postcode = '" . $data['postcode'] . "', country_id = '" . (int)$data['country_id'] . "'");
		$address_id = $this->getLastId();

		$this->query("UPDATE " . DB_PREFIX . "employee SET address_id = '" . (int)$address_id . "' WHERE employee_id = '" . (int)$employee_id . "'");
		
		return $employee_id;
	}
	
	public function updateEmployee($employee_id,$data){
		$acnt =array();
		
		$lang=array();
    	if(isset($data['lang'])&& !empty($data['lang']))
    	{
    	    $lang = implode(",",$data['lang']);
    	}
		if(isset($data['associate_acnt']) && !empty($data['associate_acnt']))
		{
			$acnt = implode(",",$data['associate_acnt']);
			foreach($data['associate_acnt'] as $ass)
			{
				$account = explode("=",$ass);
				//printr($data['associate_acnt']);//die;
				if($account[0]==2)
				{   $s=array();
					//$sel="SELECT * FROM";
					if(in_array($ass,$data['associate_acnt']))
					{
						$s[]= $ass;
						$a = array_diff( $data['associate_acnt'], $s );
						array_push($a,'2='.$employee_id);
						$arr = implode(",",$a);
						//printr($arr);
						$this->query("UPDATE " . DB_PREFIX . "employee SET associate_acnt = '" .$arr. "' WHERE employee_id = '" .$account[1]. "'");
					}
				}
				else if($account[0]==4)
				{  $s=array();
					if(in_array($ass,$data['associate_acnt']))
					{
						$s[]= $ass;
						$a = array_diff( $data['associate_acnt'], $s );
						array_push($a,'2='.$employee_id);
						$arr = implode(",",$a);
						//printr($arr);
						//echo "UPDATE " . DB_PREFIX . "international_branch SET associate_acnt = '" .$arr. "' WHERE international_branch_id = '" .$account[1]. "'";
						$this->query("UPDATE " . DB_PREFIX . "international_branch SET associate_acnt = '" .$arr. "' WHERE international_branch_id = '" .$account[1]. "'");
					}
					
				}
				
				//printr($account);die;
			}
			//printr($data['associate_acnt']);
		//	die;
			
		}
		$sql = "UPDATE `" . DB_PREFIX . "employee` e, `" . DB_PREFIX . "account_master` am SET e.first_name = '" . $data['first_name'] . "', e.last_name = '" . $data['last_name'] . "', e.telephone = '" . $data['telephone'] . "', e.user_name = '".$data['user_name']."', am.user_name = '".$data['user_name']."', am.email = '" . $data['email'] . "', e.status = '".(int)$data['status']."',e.email_signature = '".$data['email_signature']."',stock_order_price='".$data['stock_order']."',multi_quotation_price='".$data['multi_quotation_price']."',e.stock_price_compulsory='".$data['stock_comp']."',e.date_modify = NOW(), am.date_modify = NOW(),e.user_type = '".$data['user_type']."',associate_acnt='".$acnt."',lang_id='".$lang."',commission = '" .$data['commission']. "' WHERE e.employee_id = '".(int)$employee_id."' AND am.user_type_id = '2' AND am.user_id = '".(int)$employee_id."'";
		//echo $sql;die;
		$this->query($sql);
		
		$this->query("UPDATE " . DB_PREFIX . "account_master SET status = '".(int)$data['status']."' WHERE user_type_id = '2' AND user_id = '" .(int)$employee_id. "' AND user_name = '".$data['user_name']."'");
		if (isset($data['password']) && !empty($data['password'])) {
			$salt = substr(md5(uniqid(rand(), true)), 0, 9);
			$sql1 = "UPDATE `" . DB_PREFIX . "account_master` SET salt = '" . $salt . "', password = '" . sha1($salt . sha1($salt . sha1($data['password']))) . "' , password_text = '" .$data['password']. "', email = '" .$data['email']. "', date_modify = NOW()  WHERE user_type_id = '2' AND user_id = '" .(int)$employee_id. "' AND user_name = '".$data['user_name']."'";
			//echo "SDasdasda";die;
			$this->query($sql1);
			
		}
		$employee = $this->getEmployee($employee_id);
		if($employee['address_id'] > 0){
			$this->query("UPDATE " . DB_PREFIX . "address SET first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', company = '', address = '" . $data['address'] . "', city = '" . $data['city'] . "', postcode = '" . $data['postcode'] . "', state = '" . $data['state'] . "', country_id = '" . (int)$data['country_id'] . "', date_modify = NOW() WHERE user_type_id = '2' AND user_id = '".(int)$employee_id."' ");
		}else{
			$this->query("INSERT INTO " . DB_PREFIX . "address SET user_type_id = '2', user_id = '" . (int)$employee_id . "', first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', company = '', address = '" . $data['address'] . "', city = '" . $data['city'] . "', state = '" . $data['state'] . "', postcode = '" . $data['postcode'] . "', country_id = '" . (int)$data['country_id'] . "'");
			$address_id = $this->getLastId();
			$this->query("UPDATE " . DB_PREFIX . "employee SET address_id = '" . (int)$address_id . "' WHERE employee_id = '" . (int)$employee_id . "'");
		}
	}
	
	public function getEmployee($employee_id){
		$sql = "SELECT *, e.first_name as efirst_name, e.last_name as elast_name FROM " . DB_PREFIX . "employee e LEFT JOIN " . DB_PREFIX . "address addr ON (e.address_id = addr.address_id) LEFT JOIN " . DB_PREFIX . "account_master am ON(am.user_name=e.user_name) WHERE am.user_type_id = '2' AND e.employee_id = '" .(int)$employee_id. "' AND am.user_id =e.employee_id AND addr.user_type_id = '2' AND addr.user_id = '" .(int)$employee_id. "' ";
		//echo $sql;die;
		$data = $this->query($sql);
		//printr($data->row);die;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalEmployee($user_type_id,$user_id,$filter_array=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "employee` e LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=e.user_name AND am.user_id = e.employee_id AND am.user_type_id = '2' WHERE e.is_delete = '0' AND e.user_type_id = '".$user_type_id."' AND e.user_id = '".$user_id."' AND e.is_delete='0' ";
		
		if(!empty($filter_array)){
			
			if(!empty($filter_array['name'])){
				$sql .= " AND CONCAT(first_name,' ',last_name) LIKE '%".$filter_array['name']."%' ";
			}
			
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}
			
			if($filter_array['status'] != ''){
				$sql .= " AND e.status = '".$filter_array['status']."' ";
			}				
		}
		
		$data = $this->query($sql);
	
		return $data->row['total'];
	}
	
	public function getEmployees($data,$user_type_id,$user_id,$filter_array=array()){
		
		//$sql = "SELECT *, CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "employee`";
		$sql = "SELECT *,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "employee` e LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=e.user_name AND am.user_id = e.employee_id AND am.user_type_id = '2' AND e.is_delete = '0' WHERE e.user_type_id = '".$user_type_id."' AND e.user_id = '".$user_id."' AND e.is_delete='0' ";
		
		if(!empty($filter_array)){
			
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}
			
			if($filter_array['status'] != ''){
				$sql .= " AND e.status = '".$filter_array['status']."' ";
			}
			
			if(!empty($filter_array['name'])){
				$sql .= " HAVING name LIKE '%".$filter_array['name']."%' ";
			}
		}
				
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY employee_id";	
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
		$query = $this->query($sql);
		//printr($query);die;
		if($query->num_rows){
			return $query->rows;
		}else{
			return false;
		}
	}
	
	public function updateStatus($user_type_id,$user_id,$status,$data){
		//echo $user_type_id.'user_type_id'.$user_id.'$user_id';
		//printr($data);
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "employee` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' AND employee_id IN (" .implode(",",$data). ")";
			$this->query($sql);
			$this->query("UPDATE " . DB_PREFIX . "account_master SET status = '".(int)$status."' WHERE user_type_id = '2' AND user_id IN (" .implode(",",$data). ")");
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "employee` SET is_delete = '1', date_modify = NOW() WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' AND employee_id IN (" .implode(",",$data). ")";
			$this->query($sql);
			//$sel_delete = "DELETE FROM account_master WHERE user_type_id = '2' AND user_id IN (" .implode(",",$data). ")";
			//$this->query($sel_delete);
			//echo $sel_delete;die;
		}
		//printr("UPDATE " . DB_PREFIX . "account_master SET status = '".(int)$status."' WHERE user_type_id = '".(int)$user_type_id."' AND user_id IN (" .implode(",",$data). ")");die;
	}
		
		public function getUserType(){
			$sql = "SELECT * FROM `" . DB_PREFIX . "user_type_production` WHERE is_delete = 0";
		
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}
		
    public function getUserList($user_id){
			$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master WHERE  user_id!='".$user_id."' ORDER BY user_name ASC";
			$data = $this->query($sql);
		
		    if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
	}
	
	public function getUserType_name($user_type){
		$sql = "SELECT * FROM `" . DB_PREFIX . "user_type_production` WHERE is_delete = 0 AND user_type_id ='".$user_type."' ";
	
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['user_type_name'];
		}else{
			return false;
		}
	}
	//kinjal made on[4-12-2018]
	public function getLanguage(){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."language_master WHERE is_delete = '0'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	//end
}
	
?>