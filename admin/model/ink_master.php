<?php
class inkMaster extends dbclass{
	//ruchi
	public function addInk($data){
		
		$sql = "INSERT INTO `" . DB_PREFIX . "ink_master` SET price = '" . $data['price']. "' ,ink_master_unit = '" . $data['unit']. "',  ink_master_min_qty = '" . $data['min_qty']. "',make_id = '".$data['make']."', status = '" .$data['status']. "', date_added = NOW()";
		
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function getInk($ink_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "ink_master` WHERE ink_master_id = '" .(int)$ink_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateInk($ink_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "ink_master` SET price = '" .$data['price']. "', ink_master_unit = '" . $data['unit']. "', ink_master_min_qty = '" . $data['min_qty']. "',make_id = '".$data['make']."', status = '" .$data['status']. "',  date_modify = NOW() WHERE ink_master_id = '" .(int)$ink_id. "'";
		$this->query($sql);
	}
	//ruchi
	
	/*public function InactiveInk($ink_id,$data,$type){
		$sql = "UPDATE `" . DB_PREFIX . "ink_master` SET  status = '0',  date_modify = NOW() WHERE ink_master_id = '" .(int)$ink_id. "'";
		$this->query($sql);
		
		$sql = "INSERT INTO `" . DB_PREFIX . "ink_master` SET price = '" . $data['price']. "', type = '".$type."', status = '" .$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}*/
	
	public function getTotalInk(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "ink_master`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getInks($data){
		$sql = "SELECT ink_master_id,price,m.make_id,m.status,make_name FROM `" . DB_PREFIX . "ink_master` AS i, `" . DB_PREFIX . "product_make` AS m WHERE m.make_id=i.make_id";
		//echo $sql;
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ink_master_id";	
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
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	

	public function getmakeData(){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make`  WHERE is_delete='0' AND status ='1'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	//===================== INK SOLVENT ==============
	//ruchi
	public function addInkSolvent($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "ink_solvent` SET price = '" . $data['price']. "',ink_solvent_unit = '" . $data['unit']. "' ,ink_solvent_min_qty = '" . $data['min_qty']. "',make_id = '".$data['make']."', status = '" .$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateInkSolvent($solvent_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "ink_solvent` SET price = '" .$data['price']. "',ink_solvent_unit = '" . $data['unit']. "', ink_solvent_min_qty = '" . $data['min_qty']. "',make_id = '".$data['make']."', status = '" .$data['status']. "',  date_modify = NOW() WHERE ink_solvent_id = '" .(int)$solvent_id. "'";
		$this->query($sql);
	}
	//ruchi
	
	public function getInkSolvent($solvent_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "ink_solvent` WHERE ink_solvent_id = '" .(int)$solvent_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalInkSolvent(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "ink_solvent`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getInkSolvents($data){
		$sql = "SELECT ink_solvent_id,price,m.make_id,m.status,make_name FROM `" . DB_PREFIX . "ink_solvent` AS i, `" . DB_PREFIX . "product_make` AS m WHERE m.make_id=i.make_id";

		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ink_solvent_id";	
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
	//kavita 19-4-2017
	//[kinjal] edited on 20-4-2-2017
	public function AddCustMulValue($data)
	{
		$sql="INSERT INTO`" . DB_PREFIX . "custom_multiplier_detail` SET ink_mul='".$data['ink_mul']."',adhesive_mul='".$data['adhesive_mul']."',date_added = NOW(),date_modify=NOW()";
	
		$this->query($sql);
		return $this->getLastId();
	}
	//kavita END
	
	public function getCusotmMuldata()
	{
			$sql = "SELECT * FROM `" . DB_PREFIX . "custom_multiplier_detail`";
			$data=$this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
	}
	
	public function EditCustMulValue($data)
	{
			$sql = "UPDATE `" . DB_PREFIX . "custom_multiplier_detail` SET ink_mul='".$data['ink_mul']."',adhesive_mul='".$data['adhesive_mul']."',  date_modify = NOW() WHERE 	custom_mul_id = '" .(int)$data['custom_mul_id']. "'";
			$this->query($sql);
	}
	//[kinjal] END
	//[kinjal] made this fun for transfer data xero to address book table on 21-7-2017
	/*public function transfer()
	{
			$sql="SELECT * FROM xero_customer";
			$data=$this->query($sql);
			if($data->num_rows){
				foreach($data->rows as $d)
				{ //printr($d);die;
					$sql1="INSERT INTO " . DB_PREFIX . "address_book_master SET company_name='" . addslashes($d['name']) . "', vat_no = '" . $d['tin_no'] . "', user_id = '" .$d['user_id'] . "',user_type_id = '" .$d['user_type_id']. "',status = '1', date_added = NOW(),date_modify = NOW()";
					$this->query($sql1);
					$address_book_id = $this->getLastId();
					
					$sql2="INSERT INTO " . DB_PREFIX . "company_address SET address_book_id = " . $address_book_id . ", c_address = " . addslashes($d['address']) . ",email_1 = " . $d['email'] . ", user_id = " .$d['user_id']. ",
					 user_type_id = " .$d['user_type_id']. ",	date_added = NOW(),date_modify = NOW()";
					
					$this->query($sql2);
					
					$sql3="INSERT INTO " . DB_PREFIX . "factory_address SET address_book_id = " . $address_book_id . ", f_address = " . addslashes($d['delivery_address']) . ",email_1 = " . $d['email']. ",user_id = " .$d['user_id']. ", user_type_id = " .$d['user_type_id']. ", date_added = NOW(),date_modify = NOW()";
					$this->query($sql3);
				}
			}else{
				return false;
			}
	}*/
}
?>