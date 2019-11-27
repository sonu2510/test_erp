<?php
class spout_detail extends dbclass {
	
	
	public function getvalue(){
		$sql = "SELECT * FROM spout_detail WHERE is_delete=0";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY spout_id";	
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
		$data = $this->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "spout_detail WHERE is_delete=0" );
		return $data->row['total'];
	}
	
	public function addspout($data){
		
		$sql = "INSERT INTO `" . DB_PREFIX . "spout_detail` SET spout_name = '".$data['spout_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',wastage = '".$data['wastage']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',weight = '".$data['weight']."',profit_price_rich = '".$data['profit_price_rich']."',profit_price_poor = '".$data['profit_price_poor']."',status = '".$data['status']."', date_added = NOW()";
		 $data = $this->query($sql);
		 	$spout_id = $this->getLastId();

		 foreach($data['profit_price'] as $price=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				$sql = $this->query("INSERT INTO " . DB_PREFIX . "profit_spout SET spout_id = '" . (int)$spout_id . "', profit = '" .$val. "', qty = '" .$key . "', profit_type = '".$price."'");		
			}
		}
	}

	public function getspout($spout_id){
		//$spoutid=$spout_id;
		$sql = "SELECT * FROM `" . DB_PREFIX . "spout_detail` WHERE spout_id = '" .(int)$spout_id. "'";
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
	
	public function updatespout($spout_id,$data)
	{
		//$price=$post['price'];
		//printr($data);
	    $sql = "UPDATE `" . DB_PREFIX . "spout_detail` SET spout_name = '".$data['spout_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',wastage = '".$data['wastage']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',weight = '".$data['weight']."',status = '".$data['status']."',date_modify = NOW() where spout_id='".$spout_id."'";
		
		$data_s = $this->query($sql);


		$profit_quantity = "SELECT profit_id FROM profit_spout WHERE spout_id='".$spout_id."'";
		$qty = $this->query($profit_quantity);
		$id=$qty->num_rows;
		//printr($data);die;
		foreach($data['profit_price'] as $price=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				if($id==0)
				{
					$sql1 = $this->query("INSERT INTO " . DB_PREFIX . "profit_spout SET spout_id = '" . (int)$spout_id . "', profit = '" .$val. "', qty = '" .$key . "', profit_type = '".$price."'");
				}
				else
				{
					//printr("UPDATE " . DB_PREFIX . "profit_spout SET  profit = '" .$val. "' WHERE spout_id = '" . (int)$spout_id . "' AND qty='" .$key . "' AND profit_type = '".$price."'");
					$sql1 = $this->query("UPDATE " . DB_PREFIX . "profit_spout SET  profit = '" .$val. "' WHERE spout_id = '" . (int)$spout_id . "' AND qty='" .$key . "' AND profit_type = '".$price."'");
				}
			}
		}//die;
		
	}
	public function UpdateStatus($spout_id,$status){
		$sql = "UPDATE spout_detail SET status = '".$status."' WHERE spout_id = '".$spout_id."'";	
		//echo $sql;
		$data = $this->query($sql);
	}
	public function UpdatespoutStatus($status,$data){
	
		if($status == 0 || $status == 1){
			$sql = "UPDATE spout_detail SET status = '" .(int)$status. "',  date_modify = NOW() WHERE spout_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE spout_detail SET is_delete = '1', date_modify = NOW() WHERE spout_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getSize(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "size_master` WHERE product_id = 61";
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
		$data = $this->query("SELECT product_quantity_id,quantity FROM " . DB_PREFIX ."product_quantity WHERE status = '1' AND is_delete = '0'  ");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getprofit($spout_id,$type){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."profit_spout WHERE spout_id = '".$spout_id."' AND profit_type='".$type."'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
}
?>
