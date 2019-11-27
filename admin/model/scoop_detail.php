<?php
class scoop_detail extends dbclass {
	
	
	public function getvalue(){
		$sql = "SELECT * FROM scoop_detail WHERE is_delete=0";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY scoop_name + 0";	
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
	
	public function getcount()
	{
		$data = $this->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "scoop_detail WHERE is_delete=0" );
		return $data->row['total'];
	}
	
	public function addscoop($data){
		
		$sql = "INSERT INTO `" . DB_PREFIX . "scoop_detail` SET scoop_name = '".$data['scoop_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',wastage = '".$data['wastage']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',status = '".$data['status']."', date_added = NOW()";
		$data1 = $this->query($sql);
		
		$scoop_id = $this->getLastId();
		foreach($data['profit_price'] as $price=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				$sql = $this->query("INSERT INTO " . DB_PREFIX . "profit_scoop SET scoop_id = '" . (int)$scoop_id . "', profit = '" .$val. "', qty = '" .$key . "', profit_type = '".$price."'");		
			}
		}
	}

	public function getScoop($scoop_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "scoop_detail` WHERE scoop_id = '" .(int)$scoop_id. "'";
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
	
	public function updatescoop($scoop_id,$data)
	{
		//$price=$post['price'];
		
	    $sql = "UPDATE `" . DB_PREFIX . "scoop_detail` SET scoop_name = '".$data['scoop_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',wastage = '".$data['wastage']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',status = '".$data['status']."',date_modify = NOW() where scoop_id='".$scoop_id."'";
		
		$data1 = $this->query($sql);
		
		$profit_quantity = "SELECT profit_id FROM profit_scoop WHERE scoop_id='".$scoop_id."'";
		$qty = $this->query($profit_quantity);
		$id=$qty->num_rows;
		//printr($data);
		foreach($data['profit_price'] as $price=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				if($id==0)
				{
					$sql1 = $this->query("INSERT INTO " . DB_PREFIX . "profit_scoop SET scoop_id = '" . (int)$scoop_id . "', profit = '" .$val. "', qty = '" .$key . "', profit_type = '".$price."'");
				}
				else
				{
					$sql1 = $this->query("UPDATE " . DB_PREFIX . "profit_scoop SET  profit = '" .$val. "' WHERE scoop_id = '" . (int)$scoop_id . "' AND qty='" .$key . "' AND profit_type = '".$price."'");
				}
			}
		}
		//die;
		
		
	}
	public function UpdateStatus($scoop_id,$status){
		$sql = "UPDATE scoop_detail SET status = '".$status."' WHERE scoop_id = '".$scoop_id."'";	
		//echo $sql;
		$data = $this->query($sql);
	}
	public function UpdateScoopStatus($status,$data){
	
		if($status == 0 || $status == 1){
			$sql = "UPDATE scoop_detail SET status = '" .(int)$status. "',  date_modify = NOW() WHERE scoop_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE scoop_detail SET is_delete = '1', date_modify = NOW() WHERE scoop_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getSize(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "size_master` WHERE product_id = 11";
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
	public function getQuantity(){
		$data = $this->query("SELECT template_quantity_id,quantity FROM " . DB_PREFIX ."template_quantity WHERE status = '1' AND is_delete = '0' AND  template_quantity_id IN (1,2,3,4)");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getprofit($scoop_id,$type){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."profit_scoop WHERE scoop_id = '".$scoop_id."' AND profit_type='".$type."'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
}
?>
