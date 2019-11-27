<?php
class productCylinderBasePrice extends dbclass{
	
	
	public function getCylinder($cylinder_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_cylinder_base_price` WHERE product_cylinder_base_price_id = '" .(int)$cylinder_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function addCylinderBasePrice($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_cylinder_base_price` SET price = '" . $data['price']. "',currency_id = '',currency_code = '".$data['currency_code']."', status = '1', date_added = NOW(),date_modify = NOW()";
		
		$this->query($sql);	
	}
	
	public function updateCylinderBasePrice($cylinder_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "product_cylinder_base_price` SET price = '" .$data['price']. "',currency_id = '',currency_code = '".$data['currency_code']."',status = '1',date_modify = NOW() WHERE product_cylinder_base_price_id = '" .(int)$cylinder_id. "'";
		
		$this->query($sql);
	}
	
	public function getTotalCylinder(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_cylinder_base_price` pcb ";
		$data = $this->query($sql);
		return $data->row['total'];	
	}
	
	public function getProductCylinders($data){
		
		$sql = "SELECT pcb.* FROM `" . DB_PREFIX . "product_cylinder_base_price` pcb ";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_cylinder_base_price_id";	
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
	
	public function getCurrencyList(){
		//echo "SELECT c.currency_code,c.country_id FROM  " . DB_PREFIX . "country as c, " . DB_PREFIX . "product_cylinder_base_price as p WHERE c.is_delete = 0 AND c.status=1 AND c.currency_code <> p.currency_code GROUP BY c.currency_code";die;
		$data = $this->query("SELECT currency_code,country_id FROM  " . DB_PREFIX . "country  WHERE is_delete = 0 AND status=1   GROUP BY currency_code");
		return $data->rows;		
	}
	
	public function getTotalBranch($filter_array=array()){
		$sql = "SELECT COUNT(*) as total FROM `international_branch` ib, `account_master` am ,address as addr,country as c WHERE am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' AND ib.address_id = addr.address_id AND ib.is_delete = '0' AND addr.country_id=c.country_id";

		if(!empty($filter_array)){						
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}				
			if($filter_array['status'] != ''){
				$sql .= " AND ib.status = '".$filter_array['status']."' ";
			}			
			if(!empty($filter_array['name'])){
				$sql .= " AND CONCAT(ib.first_name,' ',ib.last_name) LIKE '%".$this->escape($filter_array['name'])."%'";
			}									
		}
		//echo $sql;die;		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getBranchs($data = array(),$filter_array=array()){
		//$sql = "SELECT *,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "client`";		
		$sql = "SELECT *,CONCAT(ib.first_name,' ',ib.last_name) as name,am.email,c.country_name FROM `international_branch` ib, `account_master` am ,address as addr,country as c WHERE am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' AND ib.address_id = addr.address_id AND ib.is_delete = '0' AND addr.country_id=c.country_id";
		//echo $sql;die;		
		if(!empty($filter_array)){			
			if(!empty($filter_array['email'])){
				$sql .= " WHERE am.email LIKE '%".$filter_array['email']."%'";
			}			
			if($filter_array['status'] != ''){
				$sql .= " WHERE ib.status = '".$filter_array['status']."' ";
			}			
			if(!empty($filter_array['name'])){
				$sql .= " HAVING name LIKE '%".$this->escape($filter_array['name'])."%'";
			}							
		}		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY international_branch";	
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
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function update_price($international_branch_id,$gres_cyli,$default_cyli_base_price){
	   	$sql = "UPDATE `" . DB_PREFIX . "international_branch` SET gres_cyli = '" .$gres_cyli. "',default_cyli_base_price = '".$default_cyli_base_price."',date_modify = NOW() WHERE international_branch_id = '" .(int)$international_branch_id. "'";
	//	echo $sql;die;
		$this->query($sql);
	    
	}
	public function getdefaultcurrencyCode($default_curr)
	{
		$sql = "SELECT currency_code FROM country where status = 1 and currency_code!='' and country_id = '".$default_curr."' LIMIT 1";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows)
		{
			return $data->row['currency_code']; 
		}
		else
		{
			return false; 
		}
	}
	
}
?>