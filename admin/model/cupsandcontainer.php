<?php
class cupsandcontainer extends dbclass {
	
	
	public function getvalue(){
		$sql = "SELECT * FROM cup_detail WHERE is_delete=0";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cup_id";	
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
		$data = $this->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "cup_detail WHERE is_delete=0" );
		return $data->row['total'];
	}
	public function getproduct()
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "product WHERE is_delete=0 AND status = 1 AND product_id IN(47,48,70)" );
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function addcup($data){
		
		$sql = "INSERT INTO `" . DB_PREFIX . "cup_detail` SET cup_name = '".$data['cup_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',wastage = '".$data['wastage']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',weight = '".$data['weight']."',status = '".$data['status']."', date_added = NOW(),product_id='".$data['product']."'";
		 $d = $this->query($sql);
		 $cup_id = $this->getLastId();
          
		 foreach($data['profit_price'] as $price=>$per_data)
		{   
			foreach($per_data as $key=>$val)
			{
			    foreach($val as $qty=>$v)
			    {
				    $sql = $this->query("INSERT INTO " . DB_PREFIX . "profit_cup SET cup_id = '" . (int)$cup_id . "', profit = '" .$v. "', qty = '" .$qty. "', profit_type = '".$key."',transportation='".$price."'");		
			
			    }   
			}
		}
	}

	public function getcup($cup_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "cup_detail` WHERE cup_id = '" .(int)$cup_id. "'";
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
	
	public function updatecup($cup_id,$data)
	{
	    $sql = "UPDATE `" . DB_PREFIX . "cup_detail` SET cup_name = '".$data['cup_name']."',basic_price = '".$data['basic_price']."',select_volume = '".$data['select_volume']."',wastage = '".$data['wastage']."',transport_price = '".$data['transport_price']."',packing_price = '".$data['packing_price']."',weight = '".$data['weight']."',status = '".$data['status']."',date_modify = NOW(),product_id='".$data['product']."' where cup_id='".$cup_id."'";
		$data_s = $this->query($sql);


		$profit_quantity = "SELECT profit_id FROM profit_cup WHERE cup_id='".$cup_id."'";
		$qty = $this->query($profit_quantity);
		$id=$qty->num_rows;
		//printr($data);die;
		foreach($data['profit_price'] as $price=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				foreach($val as $qty=>$v)
			    {
    				if($id==0)
    				{
    					$sql1 = $this->query("INSERT INTO " . DB_PREFIX . "profit_cup SET cup_id = '" . (int)$cup_id . "', profit = '" .$v. "', qty = '" .$qty . "', profit_type = '".$key."',transportation='".$price."'");
    				}
    				else
    				{
    					$sql1 = $this->query("UPDATE " . DB_PREFIX . "profit_cup SET  profit = '" .$v. "' WHERE cup_id = '" . (int)$cup_id . "' AND qty='" .$qty . "' AND profit_type = '".$key."' AND transportation='".$price."'");
    				}
			    }
			}
		}
		
	}
	public function UpdateStatus($cup_id,$status){
		$sql = "UPDATE cup_detail SET status = '".$status."' WHERE cup_id = '".$cup_id."'";	
		$data = $this->query($sql);
	}
	public function UpdatecupStatus($status,$data){
	
		if($status == 0 || $status == 1){
			$sql = "UPDATE cup_detail SET status = '" .(int)$status. "',  date_modify = NOW() WHERE cup_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE cup_detail SET is_delete = '1', date_modify = NOW() WHERE cup_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getSize($cup_id){
		$sql = "SELECT sm.* FROM " . DB_PREFIX . "size_master as sm WHERE sm.product_id = '".$cup_id."'";
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
		$data = $this->query("SELECT template_quantity_id,quantity FROM " . DB_PREFIX ." template_quantity WHERE template_quantity_id IN (1,2,3,4,6,7) AND is_delete = '0'  ");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getprofit($cup_id,$type,$trans){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."profit_cup WHERE cup_id = '".$cup_id."' AND profit_type='".$type."' AND transportation='".$trans."'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
}
?>
