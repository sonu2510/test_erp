<?php
class color_catagory extends dbclass{

	//sonu add field 14/11/16
	public function addColor($data){
        //printr($data);
		$sql = "INSERT INTO color_catagory SET color_name = '" .$data['color']. "',status = '".$data['status']."', date_added = NOW(),date_modify = NOW(),is_delete=0";
		//echo $sql;die;
		$this->query($sql);
	}
	//kavita:10-2-2017
	public function updateColor($color,$data){
		//printr(strip_tags($data['color']));die;
		$sql = "UPDATE `" . DB_PREFIX . "color_catagory` SET color_name = '" .strip_tags($data['color']). "',status = '" .$data['status']. "',  date_modify = NOW() WHERE color_catagory_id = '" .(int)$color. "'";
		$this->query($sql);		
	}
	
	
	public function getTotalColor($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "color_catagory` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['color_name'])){
				$sql .= " AND color LIKE '%".$filter_data['color_name']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}

	public function getColor($color){
		$sql = "SELECT * FROM color_catagory WHERE is_delete = '0'";
		//echo $sql;die;
		$data = $this->query($sql);
		//echo $sql;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getColor_detail($catagory_id){
		$sql = "SELECT * FROM color_catagory WHERE is_delete = '0' AND color_catagory_id='".$catagory_id."' ";
		$data = $this->query($sql);
		//echo $sql;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateColorStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "color_catagory` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE color_catagory_id = '".$id."' ";
		$this->query($sql);
	}
		
	public function updateStatus($status,$data){
			if($status == 0 || $status == 1){
				$sql = "UPDATE `" . DB_PREFIX . "color_catagory` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE color_catagory_id IN (" .implode(",",$data). ")";
				//echo $sql;die;
				$this->query($sql);
			}elseif($status == 2){
				$sql = "UPDATE `" . DB_PREFIX . "color_catagory` SET is_delete = '1', date_modify = NOW() WHERE color_catagory_id IN (" .implode(",",$data). ")";
				$this->query($sql);
			}

		}
	public function getAllColor()
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
		
}
?>

