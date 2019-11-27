<?php
class product_item_info extends dbclass{

		public function addProductCode($data){
		
		
		//	printr($data);die;
			$process ='';
			if(isset($data['process_name']))
				$process = serialize($data['process_name']);
		
			$sql = "INSERT INTO `" . DB_PREFIX . "product_item_info` SET product_thickness='".$data['thickness']."', product_gsm='".$data['gsm']."', product_category_id = '".$data['product_category']."' ,product_name = '".$data['product_name']."', product_code = '".$data['product_code']."', unit = '".$data['unit']."', sec_unit='".$data['sec_unit']."',material='".$data['material']."', status = '" .(int)$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0, production_process_id='".$process."',added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',current_stock='".$data['current_stock']."'";
			//echo $sql;die;
			
			$this->query($sql);
			$product_item_id = $this->getLastId();
			//layers
			if(isset($data['layer']) && !empty($data['layer'])){
			foreach($data['layer'] as $key=>$layer_id){
				$this->query("INSERT INTO " . DB_PREFIX . " production_layer_material  SET 	product_item_id = '".$product_item_id."', layer_id = '" .$layer_id. "', date_modify = NOW()");
			}
		}
	

		}

		public function updateProductCode($product_item_id,$data){
		    
		    //printr($data);
			$process ='';
			if(isset($data['process_name']))
				$process = serialize($data['process_name']);
						 
			$sql = "UPDATE `" . DB_PREFIX . "product_item_info` SET product_thickness='".$data['thickness']."', product_gsm='".$data['gsm']."',product_category_id = '".$data['product_category']."' ,product_name = '".$data['product_name']."', product_code = '".$data['product_code']."', unit = '".$data['unit']."', sec_unit='".$data['sec_unit']."', material='".$data['material']."', status = '" .(int)$data['status']. "', date_modify = NOW(), production_process_id='".$process."',added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',current_stock='".$data['current_stock']."' WHERE product_item_id = '" .(int)$product_item_id. "'";
			$this->query($sql);
			if(isset($data['layer'])){
			$this->query("DELETE FROM " . DB_PREFIX . "production_layer_material WHERE product_item_id = '".(int)$product_item_id."'");
			foreach($data['layer'] as $key=>$layer_id){
				$this->query("INSERT INTO " . DB_PREFIX . "production_layer_material SET product_item_id = '".$product_item_id."', layer_id = '" .$layer_id. "', date_modify = NOW()");
			}
		}
		
		}

		public function getTotalProductCode($filter_data=array()){
		
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_item_info as p WHERE p.is_delete = '0'";
		if(!empty($filter_data)){
			if(!empty($filter_data['product_code'])){
				$sql .= " AND p.product_code LIKE '%".$filter_data['product_code']."%' ";		
			}
			
			if($filter_data['product_category'] != ''){
				$sql .= " AND p.product_category_id = '".$filter_data['product_category']."' "; 	
			}
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getProductCode($data,$filter_data=array()){
		$sql = "SELECT *,p.status FROM " . DB_PREFIX . " product_item_info as p,unit_master as t  WHERE p.is_delete = '0' AND p.unit=t.unit_id";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['product_code'])){
				$sql .= " AND p.product_code LIKE '%".$filter_data['product_code']."%' ";		
			}
			if(!empty($filter_data['product_name'])){
				$sql .= " AND p.product_name LIKE '%".$filter_data['product_name']."%' ";		
			}
			
			if($filter_data['product_category'] != ''){
				$sql .= " AND p.product_category_id = '".$filter_data['product_category']."' "; 	
			}
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_item_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " DESC";
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
	
	public function getProductCodeData($product_item_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "product_item_info  WHERE product_item_id = '" .(int)$product_item_id. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateStatus($status,$data){
		
		
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "product_item_info` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_item_id IN (" .implode(",",$data). ")";
	
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE  " . DB_PREFIX . "product_item_info SET is_delete = '1', date_modify = NOW() WHERE product_item_id IN (" .implode(",",$data). ")";
		
			$this->query($sql);
		}
	}
	
	public function updateCodeStatus($product_item_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "product_item_info` SET status = '" .(int)$status_value ."' WHERE product_item_id = '".$product_item_id ."' ";
	   $this->query($sql);	
	}
	
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, co.country_id, co.country_name, e.first_name, e.last_name, e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,ib.discount, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			return false;
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
		}
		
		public function getTotal($product_item_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_item_info` WHERE product_item_id = '" .(int)$product_item_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}

		public function getMeasurement()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "unit_master` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getProductCategory()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_category` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY product_category_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getColor()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getProductionProcess()
	{
		$sql="SELECT * FROM production_process WHERE is_delete=0 AND status=1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	Public function getCurrentStock($current_stock,$product_item_id)
	{
		/*$inward_qty =$in_qty=$bal_qty=0;
		$sql="SELECT SUM(pi.qty) as inward_qty,SUM(pj.input_qty) as input_qty, SUM(pj.balance_qty) as balance_qty,um.unit  FROM product_inward as pi LEFT JOIN printing_job as pj ON (pi.product_inward_id=pj.roll_no_id) LEFT JOIN unit_master as um ON (pi.unit_id = um.unit_id)WHERE pi.product_item_id='".$product_item_id."' ";
		echo $sql;
				$data = $this->query($sql);
				
		$unit ="SELECT um.unit,pi.product_item_id FROM product_item_info as pi ,unit_master as um  WHERE pi.product_item_id = '".$product_item_id."' AND um.unit_id = pi.unit";
			$unit_data = $this->query($unit);
		if($data->num_rows)
		{	
			$inward_qty = $data->row['inward_qty'];
			$in_qty = $data->row['input_qty'];
			$bal_qty= $data->row['balance_qty'];

		}
		$cur_stock = $current_stock + $inward_qty + $bal_qty - $in_qty;
		// $current_stock .'+' .$inward_qty .'+'.$bal_qty .'- '.$in_qty;
	//	return $cur_stock.' '.$unit_data->row['unit'];
		
		*/
	}
	public function getActiveLayer(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_layer` WHERE status='1' ";
		$sql .= " ORDER BY layer";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	

	public function getMaterialLayer($product_item_id){
		$sql = "SELECT GROUP_CONCAT(layer_id) as layer_ids FROM `" . DB_PREFIX . "production_layer_material` WHERE product_item_id = '" .(int)$product_item_id. "' GROUP BY product_item_id";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['layer_ids'];
		}else{
			return false;
		}
	}
	
}	


?>