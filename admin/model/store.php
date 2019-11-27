<?php
class store extends dbclass{
	
	public function addStore($data){
		
		$sql = "INSERT INTO " . DB_PREFIX . "store_setting SET setting_details='".$data."',status = '1',date_added = NOW(),	date_modified = NOW(),	is_delete = 0";
		$this->query($sql);
		//$branch_id = $this->getLastId();
	}
	
	public function updateStore($store_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "international_branch` ib, `" . DB_PREFIX . "account_master` am SET ib.company_name= '".$data['company_name']."' ,ib.first_name = '" . $data['first_name'] . "', ib.last_name = '" . $data['last_name'] . "', ib.telephone = '" . $data['telephone'] . "', ib.user_name = '".$data['user_name']."', am.user_name = '".$data['user_name']."', am.email = '" . $data['email'] . "', ib.status = '".(int)$data['status']."',ib.gres='" . $data['gres'] . "',valve_price='".$data['valve_price']."',ib.date_modify = NOW(), am.date_modify = NOW() WHERE ib.international_branch_id = '".(int)$branch_id."' AND am.user_type_id = '4' AND am.user_id = '".(int)$branch_id."'";
		//echo $sql;die;
		$this->query($sql);
	}
	
	
	public function getStore($store_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "store_setting WHERE store_setting_id='".$store_id."'";
		//echo $sql;die;
		$data = $this->query($sql);
		
		if($data->num_rows){
			
			$store_array = unserialize($data->row['setting_details']); 
			
			$store_details = array(
				'name' => $store_array['store_name'],
				'url'  => $store_array['store_url'],
				'owner' => $store_array['store_owner'],
				'address' => $store_array['store_address'],
				'email' => $store_array['store_email'],
				'telephone' => $store_array['store_telephone'],
				'fax' => $store_array['store_fax'],
				'meta_title' => $store_array['store_meta_title'],
				'meta_description' => $store_array['store_meta_description'],
				'meta_keywords' => $store_array['store_meta_keywords'],
				'country' => $store_array['country_id'],
				'state' => $store_array['store_state'],
				'item_per_page' => $store_array['default_item_per_page'],
				'allow_reviews' => $store_array['allow_reviews'],
				'price_with_tax' => $store_array['display_price_with_tax'],
				'invoic_prefix' => $store_array['invoic_prefix']
			);
			
			return $store_details;
		}else{
			return false;
		}
	}
	
	public function deleteStore($stores_id=array()){
		foreach($stores_id as $store_id){
			$this->query("UPDATE `" . DB_PREFIX . "store_setting` SET is_delete=1 WHERE store_setting_id='".$store_id."'");		
		}
	}
	
	public function getTotalStore(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "store_setting` WHERE is_delete = '0'";		
				
		$data = $this->query($sql);

		return $data->row['total'];
	}
	
	public function getStores($data = array()){
		//$sql = "SELECT *,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "client`";
		$sql = "SELECT * FROM `" . DB_PREFIX . "store_setting` WHERE is_delete = '0'";
		//echo $sql;die;
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY store_setting_id";	
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
			foreach($data->rows as $row){
				$setting = unserialize($row['setting_details']);	
				$store_array[] = array(
					'name' => $setting['store_name'],
					'url'  => $setting['store_url'],
					'status' => $row['status'],
					'store_id' => $row['store_setting_id']
				);
			}
			
			return $store_array;
		}else{
			return false;
		}
	}
}
?>