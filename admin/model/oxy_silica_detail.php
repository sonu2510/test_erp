<?php
class oxy_silica_detail extends dbclass {
	
	
	public function getvalue(){
		$sql = "SELECT * FROM oxy_silica_detail WHERE is_delete=0";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY oxy_silica_id";	
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
		$data = $this->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "oxy_silica_detail WHERE is_delete=0" );
		return $data->row['total'];
	}
	
	public function addoxy_silica($data){
		
		//printr($data);
		$sql = "INSERT INTO `" . DB_PREFIX . "oxy_silica_detail` SET prodct_name = '".$data['oxy_silica_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',wastage = '".$data['wastage']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',weight = '".$data['weight']."',status = '".$data['status']."', date_added = NOW()";
		// echo $sql;
		 $data1 = $this->query($sql);
		 	$oxy_silica_id = $this->getLastId();

		foreach($data['profit_price'] as $price=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				$sql = $this->query("INSERT INTO " . DB_PREFIX . "profit_oxy SET oxy_silica_id = '" . (int)$oxy_silica_id . "', profit = '" .$val. "', qty = '" .$key . "', profit_type = '".$price."'");		
				//echo $sql;
			}
		}
		//die;
	}

	public function getoxy_silica($oxy_silica_id){
		//$spoutid=$spout_id;
		$sql = "SELECT * FROM `" . DB_PREFIX . "oxy_silica_detail` WHERE oxy_silica_id = '" .(int)$oxy_silica_id. "'";
	   //echo $sql;
		//die;
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
	
	public function updateoxy_silica($oxy_silica_id,$data)
	{
		//$price=$post['price'];
		
	    $sql = "UPDATE `" . DB_PREFIX . "oxy_silica_detail` SET prodct_name = '".$data['oxy_silica_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',wastage = '".$data['wastage']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',weight = '".$data['weight']."',status = '".$data['status']."',date_modify = NOW() where oxy_silica_id='".$oxy_silica_id."'";
		
		$data_s = $this->query($sql);


		$profit_quantity = "SELECT profit_id FROM profit_oxy WHERE oxy_silica_id='".$oxy_silica_id."'";
		$qty = $this->query($profit_quantity);
		$id=$qty->num_rows;
		//printr($data);die;
		foreach($data['profit_price'] as $price=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				if($id==0)
				{
					$sql1 = $this->query("INSERT INTO " . DB_PREFIX . "profit_oxy SET oxy_silica_id = '" . (int)$oxy_silica_id . "', profit = '" .$val. "', qty = '" .$key . "', profit_type = '".$price."'");
				}
				else
				{
					$sql1 = $this->query("UPDATE " . DB_PREFIX . "profit_oxy SET  profit = '" .$val. "' WHERE oxy_silica_id = '" . (int)$oxy_silica_id . "' AND qty='" .$key . "' AND profit_type = '".$price."'");
				}
			}
		}
		
	}
	public function UpdateStatus($oxy_silica_id,$status){
		$sql = "UPDATE oxy_silica_detail SET status = '".$status."' WHERE oxy_silica_id = '".$oxy_silica_id."'";	
		//echo $sql;
		$data = $this->query($sql);
	}
	public function UpdatespoutStatus($status,$data){
	
		if($status == 0 || $status == 1){
			$sql = "UPDATE oxy_silica_detail SET status = '" .(int)$status. "',  date_modify = NOW() WHERE oxy_silica_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE oxy_silica_detail SET is_delete = '1', date_modify = NOW() WHERE oxy_silica_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getSize(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "size_master` WHERE product_id IN(37,38)";
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
		$data = $this->query("SELECT template_quantity_id,quantity FROM " . DB_PREFIX ."template_quantity WHERE status = '1' AND is_delete = '0' AND template_quantity_id IN (1,2,3,4)");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getprofit($oxy_silica_id,$type){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."profit_oxy WHERE oxy_silica_id = '".$oxy_silica_id."' AND profit_type='".$type."'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
}
?>
