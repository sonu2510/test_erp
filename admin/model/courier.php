<?php
class courier extends dbclass{
	
	public function addCourier($data){
		
		$sql = "INSERT INTO " . DB_PREFIX . "courier SET courier_name = '" . $data['courier_name'] . "', contact_person = '" . $data['contact_person'] . "', email = '".$data['email']."', telephone = '" . $data['telephone'] . "', fuel_surcharge = '".(float)$data['fuel_surcharge']."', service_tax = '" .(float)$data['service_tax'] . "', handling_charge = '".(float)$data['handling_charge']."', status = '".(int)$data['status']."', date_added = NOW()";
		$this->query($sql);
		$courier_id = $this->getLastId();
		
		//courier zone master
		/*if(isset($data['zone']) && !empty($data['zone'])){
			foreach($data['zone'] as $key=>$val){
				$this->query("INSERT INTO " . DB_PREFIX . "courier_zone SET courier_id = '".(int)$courier_id."', zone = '". $val. "', status = '1', date_added = NOW()");
			}
		}*/
		return $courier_id;
	}
	
	public function updateCourier($courier_id,$data){
		$sql = "UPDATE " . DB_PREFIX . "courier SET courier_name = '" . $data['courier_name'] . "', contact_person = '" . $data['contact_person'] . "', email = '".$data['email']."', telephone = '" . $data['telephone'] . "', fuel_surcharge = '".(float)$data['fuel_surcharge']."', service_tax = '" .(float)$data['service_tax'] . "', handling_charge = '".(float)$data['handling_charge']."', status = '".(int)$data['status']."', date_modify = NOW() WHERE courier_id = '".(int)$courier_id."'";
		$this->query($sql);
		//courier zone master
		/*if(isset($data['zone']) && !empty($data['zone'])){
			$this->query("DELETE FROM " . DB_PREFIX . "courier_zone WHERE courier_id = '".(int)$courier_id."'");
			foreach($data['zone'] as $key=>$val){
				$this->query("INSERT INTO " . DB_PREFIX . "courier_zone SET courier_id = '".(int)$courier_id."', zone = '". $val. "', status = '1', date_added = NOW()");
			}
		}*/
	}
	
	public function updateCourierZonePrice($from_kg,$to_kg,$price,$updateId){
		
		$this->query("UPDATE " . DB_PREFIX . "courier_zone_price SET from_kg = '".$from_kg."', to_kg = '".$to_kg."', price = '".$price."', date_modify = NOW() WHERE courier_zone_price_id = '".(int)$updateId."'");
					
	}
	
	public function deleteCourierZonePrice($id){
		$this->query("DELETE FROM " . DB_PREFIX . "courier_zone_price WHERE courier_zone_price_id = '".(int)$id."'");	
	}
	
	public function addCourierZonePrice($courier_id,$courier_zone_id,$from_kg,$to_kg,$price){
		
		$this->query("INSERT INTO " . DB_PREFIX . "courier_zone_price SET courier_id = '".$courier_id."', courier_zone_id = '".$courier_zone_id."', from_kg = '".$from_kg."', to_kg = '".$to_kg."', price = '".$price."', status = '1', date_added = NOW() ");
		
	}
	
	public function getCourier($courier_id,$selectedColoum = '*'){
		$sql = "SELECT $selectedColoum FROM " . DB_PREFIX . "courier WHERE courier_id = '" .(int)$courier_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCourierZone($courier_id){
		$sql = "SELECT courier_id,zone FROM " . DB_PREFIX . "courier_zone WHERE courier_id = '" .(int)$courier_id. "' AND status = '1' AND is_delete = '0'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getTotalCourier(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "courier` WHERE is_delete = '0'";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getCouriers($data = array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "courier` WHERE is_delete = '0'";
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY courier_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
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
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	//zone code
	public function getCountry($notGet){
		$sql = "SELECT country_id,country_name FROM `" . DB_PREFIX . "country` WHERE is_delete = '0' AND status = '1'";
		if($notGet){
			$sql .= " AND country_id NOT IN (".$notGet.")";
		}
		$sql .= " ORDER BY country_name ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getTotalCourierZone($courier_id){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "courier_zone` WHERE courier_id = '".(int)$courier_id."' AND is_delete = '0'" ;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getCourierZones($courier_id,$data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "courier_zone` WHERE courier_id = '".(int)$courier_id."' AND is_delete = '0'";
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY courier_zone_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
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
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function chckAddedZoneCountry($courier_id,$courier_zone_id=''){
		$sql = "SELECT GROUP_CONCAT(country_id) as countrys FROM `" . DB_PREFIX . "courier_zone_country` WHERE courier_id = '".(int)$courier_id."' AND is_delete = '0' ";
		//echo $sql;die;
		if($courier_zone_id){
			$sql .= " AND courier_zone_id != '".(int)$courier_zone_id."'";
		}
		$sql .=" GROUP BY courier_id ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['countrys'];
		}else{
			return false;
		}
	}
	
	
	public function chckSelectedZoneCountry($courier_id,$courier_zone_id=''){
		$sql = "SELECT GROUP_CONCAT(country_id) as countrys FROM `" . DB_PREFIX . "courier_zone_country` WHERE courier_id = '".(int)$courier_id."' AND is_delete = '0' ";
		//echo $sql;die;
		if($courier_zone_id){
			$sql .= " AND courier_zone_id = '".(int)$courier_zone_id."'";
		}
		$sql .=" GROUP BY courier_id ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['countrys'];
		}else{
			return false;
		}
	}
	
	public function getTotalZonePrice($courire_id,$courier_zone_id){
		
		$sql = "SELECT COUNT(*) as total_zone_price FROM `" . DB_PREFIX . "courier_zone_price` WHERE courier_zone_id = '".(int)$courier_zone_id."' AND courier_id = '".(int)$courire_id."' AND is_delete = '0' AND status = '1'";
		
		$data=$this->query($sql);	
		
		return $data->row['total_zone_price'];
	}
	
	public function getZonePrice($courire_id,$courier_zone_id,$option=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "courier_zone_price` WHERE courier_zone_id = '".(int)$courier_zone_id."' AND courier_id = '".(int)$courire_id."' AND is_delete = '0' AND status = '1'";
		
		$sql .=" LIMIT ".$option['start'].",".$option['limit']." ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function addCourierZone($courier_id,$data){
		//courier zone
		$this->query("INSERT INTO " . DB_PREFIX . "courier_zone SET courier_id = '".(int)$courier_id."', zone = '".$data['zone']. "', status = '1', date_added = NOW()");
		$zone_id = $this->getLastId();
		
		//courier zone country
		if(isset($data['country']) && !empty($data['country'])){
			foreach($data['country'] as $key=>$country_id){
				$this->query("INSERT INTO " . DB_PREFIX . "courier_zone_country SET courier_id = '".(int)$courier_id."', courier_zone_id = '".$zone_id. "', country_id = '".(int)$country_id."', status = '1', date_added = NOW()");
			}
		}
		
		//price masetr 
		if(isset($data['price']) && !empty($data['price'])){
			for($i=0;$i<count($data['price']['price']);$i++){
				$this->query("INSERT INTO " . DB_PREFIX . "courier_zone_price SET courier_id = '".(int)$courier_id."', courier_zone_id = '".$zone_id."', from_kg = '" .$data['price']['from_kg'][$i]. "', to_kg = '" .$data['price']['to_kg'][$i]. "', price = '" .$data['price']['price'][$i]. "', status = '1', date_added = NOW()");
			}
		}
	}
			
	
	public function updateCourierZone($courier_id,$courier_zone_id,$data){
		$sql = "UPDATE " . DB_PREFIX . "courier_zone SET zone = '" . $data['zone'] . "', status = '".(int)$data['status']."', date_modify = NOW() WHERE courier_id = '".(int)$courier_id."' AND courier_zone_id = '".(int)$courier_zone_id."'";
		$this->query($sql);
		
		
		//courier zone country  
		if(isset($data['country']) && !empty($data['country'])){
			$this->query("DELETE FROM " . DB_PREFIX . "courier_zone_country WHERE courier_zone_id = '".(int)$courier_zone_id."' AND courier_id = '".(int)$courier_id."'");
			foreach($data['country'] as $key=>$country_id){
			
				$dataval=$this->query("SELECT country_id FROM courier_zone_country WHERE country_id='".$country_id."'");
				if($dataval->num_rows)
				{
					$this->query("UPDATE " . DB_PREFIX . "courier_zone_country SET courier_id = '".(int)$courier_id."', courier_zone_id = '".$courier_zone_id. "', country_id = '".(int)$country_id."', status = '1', date_added = NOW() WHERE country_id='".$country_id."'" );
				}
				else
				{
					$this->query("INSERT INTO " . DB_PREFIX . "courier_zone_country SET courier_id = '".(int)$courier_id."', courier_zone_id = '".$courier_zone_id. "', country_id = '".(int)$country_id."', status = '1', date_added = NOW()");
				}
				
				//mansi
				$this->query("UPDATE `country` SET default_courier_id='' WHERE default_courier_id='".(int)$courier_id."' 
				AND country_id='".(int)$country_id."'");
				$this->query("UPDATE `country` SET default_courier_id = '".(int)$courier_id."' WHERE country_id = '".(int)$country_id."' " ); 
				//echo $sql;
			}
		}
		else
		{
			$this->query("DELETE FROM " . DB_PREFIX . "courier_zone_country WHERE courier_zone_id = '".(int)$courier_zone_id."' AND courier_id = '".(int)$courier_id."'");
		}
		
		//price masetr 
		/*if(isset($data['price']) && !empty($data['price'])){
			$this->query("DELETE FROM " . DB_PREFIX . "courier_zone_price WHERE courier_zone_id = '".(int)$courier_zone_id."' AND courier_id = '".(int)$courier_id."'");
			for($i=0;$i<count($data['price']['price']);$i++){
				$this->query("INSERT INTO " . DB_PREFIX . "courier_zone_price SET courier_id = '".(int)$courier_id."', courier_zone_id = '".$courier_zone_id."', from_kg = '" .$data['price']['from_kg'][$i]. "', to_kg = '" .$data['price']['to_kg'][$i]. "', price = '" .$data['price']['price'][$i]. "', status = '1', date_added = NOW()");
			}
		}*/
	}
	
	public function getZone($courier_id,$courier_zone_id){
		$sql = "SELECT courier_zone_id,zone FROM " . DB_PREFIX . "courier_zone WHERE courier_zone_id = '" .(int)$courier_zone_id. "' AND courier_id = '".(int)$courier_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function increment_decrement_price($postdata){
	
		$arr = explode(",",$postdata['courier_zone_id']);
			
		foreach($arr as $courier_zone_id)
		{
			
			if($postdata['inc_dec'] == '1')
			{
				$sql="UPDATE courier_zone_price SET price=(to_kg*'".$postdata['price_val']."')+price WHERE courier_id='".$postdata['courier_id']."' AND 
				courier_zone_id='".$courier_zone_id."'";
				
			}
			else
			{
				$sql="UPDATE courier_zone_price SET price=price-(to_kg*'".$postdata['price_val']."') WHERE courier_id='".$postdata['courier_id']."' AND 
				courier_zone_id='".$courier_zone_id."'";
				
			}
			$this->query($sql);
				
				$insert_sql = "INSERT INTO " . DB_PREFIX . "courier_price_history SET courier_id = '".(int)$postdata['courier_id']."', courier_zone_id = '".$courier_zone_id."',value='".$postdata['price_val']."',increment_decrement='".$postdata['inc_dec']."',date_added = NOW() ";
				$this->query($insert_sql);
			
			}
			
		}	
			
	public function getCourierZoneDetail($courier_zone_id){
	
		$sql ="SELECT * FROM courier_zone WHERE courier_zone_id= '".$courier_zone_id."'";
		$data= $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalHistoryPrice($courire_id,$courier_zone_id){
		
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "courier_price_history` WHERE courier_zone_id = '".(int)$courier_zone_id."' AND courier_id = '".(int)$courire_id."' AND is_delete = '0'";
		
		$data=$this->query($sql);	
		
		return $data->row['total'];
	}
	
	public function getCourierHistory($courier_id,$courier_zone_id,$data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "courier_price_history` WHERE courier_zone_id = '".(int)$courier_zone_id."' AND courier_id = '".(int)$courier_id."' AND is_delete = '0'";
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY courier_price_history_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
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
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getLatestHistory($courire_id,$courier_zone_id,$option=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "courier_price_history` WHERE courier_zone_id = '".(int)$courier_zone_id."' AND courier_id = '".(int)$courire_id."' AND is_delete = '0' ORDER BY courier_price_history_id DESC LIMIT 1";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
}
?>