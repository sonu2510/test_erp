<?php 
class color_catagory extends dbclass{
	
	public function getColor()
	{
		 $sql = "SELECT * FROM pouch_color WHERE is_delete = 0 AND status = 1";
		
		 $data = $this->query($sql);
		
		 if($data->num_rows){
			return $data->rows; 
			}
			else{
				return false;
			}
	}
	
	public function addColor($data)
	{
		$this->query("INSERT INTO `" . DB_PREFIX . "report_color_category` SET  category = '" .$data['category']. "',color = '" .implode(',',$data['color']). "',status = '" .$data['status']. "',date_added = NOW(),date_modify = NOW(),is_delete=0,user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'");	
	}
	public function updateColor($category_id,$data)
	{  	
		$sql = "UPDATE `" . DB_PREFIX . "report_color_category` SET category = '" .$data['category']. "',color = '" .implode(',',$data['color']). "',status = '" .$data['status']. "',date_modify = NOW(),is_delete=0 WHERE category_id = '".$category_id."'";
		$this->query($sql);	
	}
	public function getTotalColor(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "report_color_category` WHERE is_delete = 0";
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getColors($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "report_color_category` WHERE is_delete = 0";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY category_id";	
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
	public function getColor_detail($category_id){
		$sql = "SELECT * FROM report_color_category WHERE category_id='".$category_id."' ";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
				return false;
		}	
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "report_color_category` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE category_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "report_color_category` SET is_delete = '1', date_modify = NOW() WHERE category_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>