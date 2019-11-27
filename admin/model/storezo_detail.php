<?php
class storezo_detail extends dbclass {
	
	/*public function getvalue(){
		$data = $this->query("SELECT * FROM storezo_detail ORDER BY storezo_id ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}*/
	
	public function getvalue(){
		$sql = "SELECT * FROM storezo_detail WHERE is_delete=0";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY storezo_id";	
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
		$data = $this->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "storezo_detail WHERE is_delete=0" );
		return $data->row['total'];
	}
	
	public function addstorezo($data){
		
		$sql = "INSERT INTO `" . DB_PREFIX . "storezo_detail` SET storezo_name = '".$data['storezo_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',cable_ties_price = '".$data['cable_ties_price']."',wastage = '".$data['wastage']."',storezo_weight = '".$data['storezo_weight']."',cable_ties_weight = '".$data['cable_ties_weight']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',profit_price_rich = '".$data['profit_price_rich']."',profit_price_poor = '".$data['profit_price_poor']."',status = '".$data['status']."', date_added = NOW()";
		//echo $sql ;die;
		 $data = $this->query($sql);
		 //printr($data);
		/* if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}*/
	}

	public function getStorezo($storezo_id){
		//$spoutid=$spout_id;
		$sql = "SELECT * FROM `" . DB_PREFIX . "storezo_detail` WHERE storezo_id = '" .(int)$storezo_id. "'";
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
	
	public function updatestorezo($storezo_id,$data)
	{
		//$price=$post['price'];
		
	    $sql = "UPDATE `" . DB_PREFIX . "storezo_detail` SET storezo_name = '".$data['storezo_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',cable_ties_price = '".$data['cable_ties_price']."',wastage = '".$data['wastage']."',storezo_weight = '".$data['storezo_weight']."',cable_ties_weight = '".$data['cable_ties_weight']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',profit_price_rich = '".$data['profit_price_rich']."',profit_price_poor = '".$data['profit_price_poor']."',status = '".$data['status']."',date_modify = NOW() where storezo_id='".$storezo_id."'";
		
		$data = $this->query($sql);
		
	}
	public function UpdateStatus($storezo_id,$status){
		$sql = "UPDATE storezo_detail SET status = '".$status."' WHERE storezo_id = '".$storezo_id."'";	
		//echo $sql;
		$data = $this->query($sql);
	}
	public function UpdateStorezoStatus($status,$data){
	
		if($status == 0 || $status == 1){
			$sql = "UPDATE storezo_detail SET status = '" .(int)$status. "',  date_modify = NOW() WHERE storezo_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE storezo_detail SET is_delete = '1', date_modify = NOW() WHERE storezo_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	

}
?>
