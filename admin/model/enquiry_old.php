<?php
class enquiry extends dbclass{
	
	public function addEnquiry($data){
		
		$enquiry_no = $this->generateEnquiryNumber();
		$this->query("INSERT INTO " . DB_PREFIX . "enquiry SET enquiry_number = '".$enquiry_no."', enquiry_for = '".$data['enquiry_for']."',company_name='".$data['company_name']."', first_name = '".$data['first_name']."',last_name = '".$data['last_name']."',phone_number = '".$data['phone_number']."', mobile_number = '".$data['mobile_number']."',fax = '".$data['fax']."',email= '".$data['email']."',country_id = '".$data['country_id']."',website = '".$data['website']."', enquiry_source_id = '".$data['enquiry_source_id']."',enquiry_type = '".$data['enquiry_type']."',status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW()");
		
		$enquiry_id = $this->getLastId();
		
		$this->query("INSERT INTO " . DB_PREFIX . "enquiry_followup SET enquiry_id = '".$enquiry_id."',followup_date = '".date("Y-m-d",strtotime($data['followup_date']))."',enquiry_note = '".$this->escape($data['enquiry_note'])."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),date_modify = NOW() ");
		
		
		foreach($data['nproducts'] as $product){
			$this->query("INSERT INTO " . DB_PREFIX . "product_enquiry SET enquiry_id = '".$enquiry_id."',product_id = '".$product['product_id']."',
			date_added = NOW(),date_modify = NOW()");	
			
			$product_enquiry_id = $this->getLastId();
			
			/*foreach($product['product_color'] as $product_color_id){
			
				$this->query("INSERT INTO " . DB_PREFIX . "product_enquiry_color SET enquiry_id = '".$enquiry_id."',product_enquiry_id = '".$product_enquiry_id."',product_color_id = '".$product_color_id."',date_added = NOW(),date_modify = NOW()");
			}*/
			
			foreach($product['product_volume'] as $product_size_id){
			
				$this->query("INSERT INTO " . DB_PREFIX . "product_enquiry_size SET enquiry_id = '".$enquiry_id."',product_enquiry_id = '".$product_enquiry_id."',product_size_id = '".$product_size_id."',date_added = NOW(),date_modify = NOW()");
			}
			
			foreach($product['product_printing_option'] as $product_printing_option){
			
				$this->query("INSERT INTO " . DB_PREFIX . "product_enquiry_printing_option SET enquiry_id = '".$enquiry_id."',product_enquiry_id = '".$product_enquiry_id."',printing_option_value = '".$product_printing_option."',date_added = NOW(),date_modify = NOW()");
			}
			
			foreach($product['product_printing_effect'] as $product_printing_effect_id){
			
				$this->query("INSERT INTO " . DB_PREFIX . "product_enquiry_printing_effect SET enquiry_id = '".$enquiry_id."',product_enquiry_id = '".$product_enquiry_id."',printing_effect_id = '".$product_printing_effect_id."',date_added = NOW(),date_modify = NOW()");
			}
			
			foreach($product['product_valve'] as $product_valve){
			
				$this->query("INSERT INTO " . DB_PREFIX . "product_enquiry_valve SET enquiry_id = '".$enquiry_id."',product_enquiry_id = '".$product_enquiry_id."',product_valve_value = '".$product_valve."',date_added = NOW(),date_modify = NOW()");
			}
			
			foreach($product['product_zipper'] as $product_zipper){
			
				$this->query("INSERT INTO " . DB_PREFIX . "product_enquiry_zipper SET enquiry_id = '".$enquiry_id."',product_enquiry_id = '".$product_enquiry_id."',product_zipper_id = '".$product_zipper."',date_added = NOW(),date_modify = NOW()");
			}
			
		}
		return $enquiry_id;
	}
	
	public function updateEnquiry($industry_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "enquiry_industry` SET industry = '" .$data['name']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE enquiry_industry_id = '" .(int)$industry_id. "'";
		//layer = '".serialize($data['layer'])."',
		$this->query($sql);
		
	}
	
	public function generateEnquiryNumber(){
		
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'enquiry'");
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,5,'0',STR_PAD_LEFT);
		$number = 'ENR'.$strpad;
		return $number;
	}
	
	
	public function getTotalEnquiry($filter_data=array()){
		$sql = "SELECT COUNT(*) as total,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM " . DB_PREFIX . "enquiry e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) WHERE e.is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['enquiry_number'])){
				$sql .= " AND e.enquiry_number = '".$filter_data['enquiry_number']."' ";
			}
			
			if(!empty($filter_data['company'])){
				$sql .= " AND e.company_name = '".$filter_data['company']."' ";
			}
			
			if(!empty($filter_data['email'])){
				$sql .= " AND e.email = '".$filter_data['email']."' ";
			}
			
			if(!empty($filter_data['country'])){
				$sql .= " AND e.country_id = '".$filter_data['country']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND e.status = '".$filter_data['status']."' ";
			}
			
			if(!empty($filter_data['customer'])){
				$sql .= " HAVING name LIKE '%".$this->escape($filter_data['customer'])."%'";
			}
					
		}
		
		$data = $this->query($sql);
		if($data->num_rows > 0){
			return $data->row['total'];
		}else{
			return false;
		}
	}
	
	public function getEnquiries($data,$filter_data=array()){
		$sql = "SELECT c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) WHERE e.is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['enquiry_number'])){
				$sql .= " AND e.enquiry_number = '".$filter_data['enquiry_number']."' ";
			}
			
			if(!empty($filter_data['company'])){
				$sql .= " AND e.company_name = '".$filter_data['company']."' ";
			}
			
			if(!empty($filter_data['email'])){
				$sql .= " AND e.email = '".$filter_data['email']."' ";
			}
			
			if(!empty($filter_data['country'])){
				$sql .= " AND e.country_id = '".$filter_data['country']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND e.status = '".$filter_data['status']."' ";
			}
			
			if(!empty($filter_data['customer'])){
				$sql .= " HAVING name LIKE '%".$this->escape($filter_data['customer'])."%'";
			}
					
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY enquiry_id";	
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
	
	public function getEnquiry($enquiry_id){
		
		$sql = "SELECT c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) WHERE e.enquiry_id = '".$enquiry_id."'";
		
		$data = $this->query($sql);
		
		if($data->row){
						
			$product_query = "SELECT pe.product_id,pe.product_enquiry_id,p.product_name FROM `" . DB_PREFIX . "product_enquiry` pe LEFT JOIN `" . DB_PREFIX . "product` p on (pe.product_id = p.product_id) WHERE pe.enquiry_id = '".$enquiry_id."' ";
			
			$products_data = $this->query($product_query);
			
			//printr($products_data);die;
			
			foreach($products_data->rows as $product_data){
				
				//Color
				/*$enquiry_color_query = "SELECT pc.color FROM `" . DB_PREFIX . "product_enquiry_color` pec LEFT JOIN `" . DB_PREFIX . "pouch_color` pc ON
				(pec.product_color_id = pc.pouch_color_id) WHERE pec.enquiry_id='".$enquiry_id."' AND pec.product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_color_data = $this->query($enquiry_color_query);
				$color_array = array();
				foreach($product_color_data->rows as $color){
					$color_array[] = array(
						'color' => $color['color'],
					);	
				}*/
				
				//Size
				$enquiry_size_query = "SELECT pv.volume FROM `" . DB_PREFIX . "product_enquiry_size` pes LEFT JOIN `" . DB_PREFIX . "pouch_volume` pv ON
				(pes.product_size_id = pv.pouch_volume_id) WHERE pes.enquiry_id='".$enquiry_id."' AND pes.product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_size_data = $this->query($enquiry_size_query);
				$size_array = array();
				foreach($product_size_data->rows as $size){
					$size_array[] = array(
						'size' => $size['volume'],
					);	
				}
				
				//Printing Option
				$enquiry_printing_option_query = "SELECT printing_option_value FROM `" . DB_PREFIX . "product_enquiry_printing_option` WHERE enquiry_id='".$enquiry_id."' AND product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_printing_option_data = $this->query($enquiry_printing_option_query);
				$printing_option_array = array();
				foreach($product_printing_option_data->rows as $print_option){
					$printing_option_array[] = array(
						'printing_option' => $print_option['printing_option_value'],
					);	
				}
				
				//Printing Effect
				$enquiry_printing_effect_query = "SELECT pe.effect_name FROM `" . DB_PREFIX . "product_enquiry_printing_effect` pepe LEFT JOIN `" . DB_PREFIX . "printing_effect` pe ON (pepe.printing_effect_id = pe.printing_effect_id)  WHERE enquiry_id='".$enquiry_id."' AND product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_printing_effect_data = $this->query($enquiry_printing_effect_query);
				$printing_effect_array = array();
				foreach($product_printing_effect_data->rows as $print_effect){
					$printing_effect_array[] = array(
						'printing_effect' => $print_effect['effect_name'],
					);	
				}
				
				//Valve
				$enquiry_valve_query = "SELECT product_valve_value FROM `" . DB_PREFIX . "product_enquiry_valve` WHERE enquiry_id='".$enquiry_id."' AND product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_valve_data = $this->query($enquiry_valve_query);
				$valve_array = array();
				foreach($product_valve_data->rows as $valve){
					$valve_array[] = array(
						'valve' => $valve['product_valve_value'],
					);	
				}
				
				
				//Zipper
				$enquiry_zipper_query = "SELECT pz.zipper_name FROM `" . DB_PREFIX . "product_enquiry_zipper` pez LEFT JOIN `" . DB_PREFIX . "product_zipper` pz ON
				(pez.product_zipper_id = pz.product_zipper_id) WHERE pez.enquiry_id='".$enquiry_id."' AND pez.product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_zipper_data = $this->query($enquiry_zipper_query);
				$zipper_array = array();
				foreach($product_zipper_data->rows as $zipper){
					$zipper_array[] = array(
						'zipper' => $zipper['zipper_name'],
					);	
				}
				
				$product_array[] = array(
					'product_id' => $product_data['product_id'],
					'product_name' => $product_data['product_name'],
					//'color' => $color_array,
					'size' => $size_array,
					'printing_option' => $printing_option_array,
					'printing_effect' => $printing_effect_array,
					'valve' => $valve_array,
					'zipper' => $zipper_array
				);	 
			}
		
			$enquiry_array = array(
				'enquiry_id' => $data->row['enquiry_id'],
				'enquiry_number' => $data->row['enquiry_number'],
				'enquiry_for' => $data->row['enquiry_for'],
				'company_name' => $data->row['company_name'],
				'client_name' => $data->row['name'],
				'mobile_number' => $data->row['mobile_number'],
				'email' => $data->row['email'],
				'country_name' => $data->row['country_name'],
				'website' => $data->row['website'],
				'enquiry_source' => $data->row['source'],
				'enquiry_type' => $data->row['enquiry_type'],
				'products' => $product_array
			);
			
			
			return $enquiry_array;
			//printr($enquiry_array);die;
			
		}else{
			return false;	
		}
		
	}
		
	public function getIndustry($industry_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "enquiry_industry` WHERE enquiry_industry_id = '" .(int)$industry_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	
	
	public function updateEnquiryStatus($id,$status){
		$this->query("UPDATE " . DB_PREFIX . "enquiry SET status = '" .(int)$status. "', date_modify = NOW() WHERE enquiry_id = '".$id."' ");
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "enquiry` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE enquiry_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "enquiry` SET is_delete = '1', date_modify = NOW() WHERE enquiry_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	
	public function getFollowUpHistories($enquiry_id){
		$data = $this->query("SELECT ef.*,am.user_name FROM " . DB_PREFIX . "enquiry_followup ef LEFT JOIN `" . DB_PREFIX . "account_master` am ON (ef.user_type_id=am.user_type_id) AND (ef.user_id=am.user_id) WHERE enquiry_id='".$enquiry_id."'");
		
		if($data->num_rows){
			return $data->rows;	
		}else{
			return false;	
		}
	}
	
	public function addHistory($enquiry_id,$date,$note){
		
		//echo $enquiry_id."====".$date."==".$note;die;
			
		$this->query("INSERT INTO " . DB_PREFIX . "enquiry_followup SET enquiry_id = '".$enquiry_id."',followup_date = '".date("Y-m-d",strtotime($date))."',enquiry_note = '".$this->escape($note)."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW() ");
		
		$user_data = $this->query("SELECT user_name FROM " . DB_PREFIX . "account_master WHERE user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' "); 
		
		return $user_data->row['user_name'];
		
	}
	
	public function getEnquirySources(){	
		$sql = "SELECT * FROM `" . DB_PREFIX . "enquiry_source` WHERE is_delete = 0";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
	}
	
	public function getProductColors(){		
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE is_delete = 0";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}			
	}
	
	public function getProductVolumes(){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_volume` WHERE is_delete = 0";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}			
	}
	
	public function getProductStyle(){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_style` WHERE is_delete = 0";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}			
	}
	
	public function getProducts(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE status = '1' AND is_delete = 0");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	
}
?>