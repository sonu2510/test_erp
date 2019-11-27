<?php
class goods_master extends dbclass{
	
	// kinjal -->
	public function addGodds($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "goods_master` SET row = '".$data['row']."', column_name = '".$data['column']."', company = '".$data['company']."', capacity = '".$data['capacity']."', description = '".$data['description']."', user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',status = '" .(int)$data['status']. "', name = '".$data['name']."',date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateGoods($goods_master_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "goods_master` SET name = '".$data['name']."', row = '".$data['row']."', column_name = '".$data['column']."', company = '".$data['company']."', capacity = '".$data['capacity']."', description = '".$data['description']."', status = '" .(int)$data['status']. "',date_modify = NOW() WHERE goods_master_id = '" .(int)$goods_master_id. "'";
		$this->query($sql);
	}
	
	public function getGoodsData($goods_master_id){

		$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE goods_master_id = '" .(int)$goods_master_id. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalGoods($filter_data=array(),$user_type_id,$user_id){
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0'";
		}
		else{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			}
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND ( user_id='".(int)$set_user_id."' 
			AND user_type_id='".(int)$set_user_type_id."' $str )";
		}
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['column_name'])){
				$sql .= " AND column_name='".$filter_data['column_name']."' ";
			}
			
			if(!empty($filter_data['row'])){
				$sql .= " AND row = '".$filter_data['row']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}			
		}
		//echo $sql;		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, co.country_id, co.country_name, e.first_name, e.last_name, e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,ib.discount, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			return false;
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getGoodsMaster($data,$filter_data=array(),$user_type_id,$user_id,$good_id=array()){
	    $goods_id='';
	    if(!empty($good_id))
	    {
	        $g_id = implode(',',$good_id);
	        $goods_id = 'AND goods_master_id IN ('.$g_id.')';
	    }
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' $goods_id ";
		}
		else{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			}
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND (user_id='".(int)$set_user_id."' AND user_type_id='".(int)$set_user_type_id."' $str  )$goods_id";
		}
		if(!empty($filter_data)){
			
			if(!empty($filter_data['column_name'])){
				$sql .= " AND column_name='".$filter_data['column_name']."' ";
			}
			
			if(!empty($filter_data['row'])){
				$sql .= " AND row = '".$filter_data['row']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}			
		}

		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY goods_master_id";	
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
	public function getUserEmployeeIds($user_type_id,$user_id){
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "goods_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE goods_master_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "goods_master` SET is_delete = '1', date_modify = NOW() WHERE goods_master_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateGoodsStatus($goods_master_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "goods_master` SET status = '" .(int)$status_value ."' WHERE goods_master_id = '".$goods_master_id ."' ";
	   $this->query($sql);	
	}
	public function checkName($name) {
		$sql = "select * from `".DB_PREFIX."goods_master` where name='".$name."' AND is_delete=0";
		$data = $this->query($sql);
		return $data->num_rows;
	}
	
}
?>