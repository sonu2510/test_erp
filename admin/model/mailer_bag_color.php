<?php
class mailerBagColor extends dbclass{
	
	public function addColor($data){

		$sql = "INSERT INTO mailer_bag_color SET color = '" .strip_tags($data['color']). "',email_color = '" .$data['email_color']. "',  status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0";
		$this->query($sql);
	}
	
	public function updateColor($color_id,$data){
	//	printr(strip_tags($data['color']));die;
		$sql = "UPDATE mailer_bag_color SET color = '" .strip_tags($data['color']). "', email_color = '" .$data['email_color']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE plastic_color_id = '" .(int)$color_id. "'";
		//layer = '".serialize($data['layer'])."',
		$this->query($sql);		
	}
	
	public function getTotalColor($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM  mailer_bag_color WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['color'])){
				$sql .= " AND color LIKE '%".$filter_data['color']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getColors($data,$filter_data=array()){
		$sql = "SELECT * FROM  mailer_bag_color WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['color'])){
				$sql .= " AND color LIKE '%".$filter_data['color']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY plastic_color_id";	
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
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	
	public function getColor($color_id){
		$sql = "SELECT * FROM  mailer_bag_color WHERE plastic_color_id = '" .(int)$color_id. "'";
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
	
	public function updateColorStatus($id,$status){
		$sql = "UPDATE  mailer_bag_color SET status = '" .(int)$status. "',  date_modify = NOW() WHERE plastic_color_id = '".$id."' ";
		$this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1)
		{
			$sql = "UPDATE  mailer_bag_color SET status = '" .(int)$status. "',  date_modify = NOW() WHERE plastic_color_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}
		elseif($status == 2)
		{
			$sql = "UPDATE mailer_bag_color SET is_delete = '1', date_modify = NOW() WHERE plastic_color_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>
