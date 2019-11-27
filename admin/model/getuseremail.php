<?php 
class userinfo extends dbclass{
	
	public function getUserEmail($user_type_id){
		if($user_type_id == 1){
			$sql = "SELECT *,CONCAT(first_name,'  ',last_name) as name FROM " . DB_PREFIX . "user u LEFT JOIN " . DB_PREFIX ."account_master am ON am.user_id=u.user_id AND u.user_name = am.user_name  WHERE u.is_delete='0' AND am.user_type_id = '".$user_type_id."'";
		}elseif($user_type_id == 3){
			$sql = "SELECT *,CONCAT(first_name,'  ',last_name) as name FROM " . DB_PREFIX . "client c LEFT JOIN " . DB_PREFIX . "account_master am ON am.user_name=c.user_name AND am.user_id = c.client_id AND am.user_type_id = '".$user_type_id."' WHERE c.is_delete = '0'";
		}elseif($user_type_id ==4){
			$sql = "SELECT *,CONCAT(first_name,'  ',last_name) as name FROM ".DB_PREFIX." international_branch i LEFT JOIN ".DB_PREFIX." account_master am ON i.international_branch_id = am.user_id AND i.user_name = am.user_name AND am.user_type_id ='".$user_type_id."' WHERE i.is_delete = '0'";
		}elseif($user_type_id == 5){
			$sql = "SELECT *,CONCAT(first_name,'  ',last_name) as name FROM " . DB_PREFIX . "associate a LEFT JOIN " . DB_PREFIX . "account_master am ON am.user_name=a.user_name AND am.user_id = a.associate_id AND am.user_type_id = '".$user_type_id."' WHERE a.is_delete='0'";
		}
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function getempemail($user_type_id,$user_type){
		$sql = "SELECT *,CONCAT(first_name,'  ',last_name) as name FROM ".DB_PREFIX." employee e LEFT JOIN ".DB_PREFIX." account_master am ON e.employee_id=am.user_id AND e.user_name = am.user_name WHERE e.user_type_id = '".$user_type_id."' AND e.user_id='".$user_type."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
	}
}

	

?>