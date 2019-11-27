<?php
class emailTemplate extends dbclass{
	
	public function addTemplate($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "email_template` SET title = '" .$data['title']. "', subject = '" .$data['subject']. "', discription = '".$data['discription']."', status = '1', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateTemplate($template_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "email_template` SET title = '" .$data['title']. "', subject = '" .$data['subject']. "', discription = '".$data['discription']."', date_modify = NOW() WHERE email_template_id = '" .(int)$template_id. "'";
		$this->query($sql);
	}
	
	public function getTemplate($template_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "email_template` WHERE email_template_id = '" .(int)$template_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalTemplate(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "email_template` WHERE is_delete = '0'";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTemplates($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "email_template` WHERE is_delete = '0'";
		
		/*if($_SESSION['LOGIN_USER_TYPE'] == 1 && $_SESSION['ADMIN_LOGIN_SWISS'] == 1 ){
			return false;
		} else {
			return true;
		}*/
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY currency";	
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
	
}
?>