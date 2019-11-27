<?php
class enquiry extends dbclass{
	//sonu edit on 6/01/2017
	public function addEnquiry($data){
				
			if (isset($data['enquiry_source_details']) && !empty($data['enquiry_source_details'])) {
                $enquiry_source_details = $data['enquiry_source_details'];
                $exhibition_id = $data['enquiry_source_details'];
			} else {
				$enquiry_source_details = $data['exhibition_name'];
				$exhibition_id = $data['enquiry_source_id'];
			}	
					
			    $address_book_id = $data['company_name_id'];
				
				//[kinjal] : changed code on 23-6-2017
				$contacts = "SELECT email_1 FROM company_address WHERE email_1='".$data['email']."' AND is_delete=0";
				$datacontacts= $this->query($contacts);
                $contact_name = $data['first_name']."  ".$data['last_name'];
				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
				{
				        
						$sql1 = "INSERT INTO  address_book_master  SET status = '1', company_name = '" . strtolower($data['company_name']) . "',contact_name = '" . addslashes($contact_name) . "', exhibition_id = '" . $exhibition_id . "', user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "', date_added = NOW(),date_modify = NOW(),industry='".$data['industry']."' ";
						$datasql1 = $this->query($sql1);
						$address_book_id = $this->getLastIdAddress();
						
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						//printr($dataadd);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET c_address = '" . addslashes($data['customer_address']) . "',country = '" . addslashes($data['country_id']) . "',email_1 = '" . $data['email'] . "',phone_no = '".$data['mobile_number']."' WHERE company_address_id ='" . $dataadd->row['company_address_id'] . "'";
							$datasql2 = $this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO  company_address  SET  address_book_id = '" . $address_book_id . "',country = '" . $data['country_id'] . "', c_address = '" . addslashes($data['customer_address']) . "', email_1 = '" . $data['email'] . "' ,user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "' , date_added = NOW(),date_modify = NOW(),phone_no = '".$data['mobile_number']."'";
						$datasql2 = $this->query($sql2);
						}
						
						
						
				}
				else
				{	 
						$address_book_id = $data['company_name_id'];
						$sql1 = "UPDATE  address_book_master  SET exhibition_id = '" . $exhibition_id . "',industry='".$data['industry']."',user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "'  WHERE address_book_id ='" . $data['company_name_id'] . "' AND date_added = NOW() AND date_modify = NOW()";
						$datasql1 = $this->query($sql1);

						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						//printr($dataadd);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET c_address = '" . addslashes($data['customer_address']) . "',country = '" . addslashes($data['country_id']) . "',email_1 = '" . $data['email'] . "',phone_no = '".$data['mobile_number']."' WHERE company_address_id ='" . $dataadd->row['company_address_id'] . "'";
							$datasql2 = $this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO  company_address  SET  address_book_id = '" . $address_book_id . "',country = '" . $data['country_id'] . "', c_address = '" . addslashes($data['customer_address']) . "', email_1 = '" . $data['email'] . "' ,user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "' , date_added = NOW(),date_modify = NOW(),phone_no = '".$data['mobile_number']."'";
						$datasql2 = $this->query($sql2);
						}
						
				}
			
		$material_combination='';
		if(isset($data['material_combination']))
        {
            $material_combination= implode(',',$data['material_combination']);
        }
	    $enquiry_no = $this->generateEnquiryNumber();
		$this->query("INSERT INTO " . DB_PREFIX . "enquiry SET enquiry_number = '".$enquiry_no."',enquiry_source_details = '" . $enquiry_source_details . "', enquiry_for = '".$data['enquiry_for']."',company_name_id='".$address_book_id."',company_name='".$data['company_name']."', first_name = '".$data['first_name']."',last_name = '".$data['last_name']."',customer_address = '".$data['customer_address']."',phone_number = '".$data['phone_number']."', mobile_number = '".$data['mobile_number']."',fax = '".$data['fax']."',email= '".$data['email']."',industry = '".$data['industry']."',country_id = '".$data['country_id']."',website = '".$data['website']."', enquiry_source_id = '".$data['enquiry_source_id']."',enquiry_type = '".$data['enquiry_type']."',remark = '".$data['remark']."',number_of_pouch_req = '".$data['number_of_pouch_req']."',filling = '".$data['filling']."',weight = '".$data['weight']."',material_combination = '".$material_combination."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW()" );
		$enquiry_id = $this->getLastId();
		$date = date("d-m-Y");
		/*$reminder = $data['reminder'];
		if($data['reminder'] == '2')
		{
			
			$date = isset($data['followup_date']) ? $data['followup_date'] : date('Y-m-d');
			$reminder = date('Y-m-d', strtotime($date .' -2 day'));
		}else if($data['reminder'] == '3')
		{
			$date = isset($data['followup_date']) ? $data['followup_date'] : date('Y-m-d');
			$reminder = date('Y-m-d', strtotime($date .' -3 day'));
			//date('M d, Y', $date);
		}else
		{
			$date = isset($data['followup_date']) ? $data['followup_date'] : date('Y-m-d');
			$reminder = date('Y-m-d', strtotime($date .' -5 day'));
		
		}reminder = '".$reminder."',*/
	
		$this->query("INSERT INTO " . DB_PREFIX . "enquiry_followup SET enquiry_id = '".$enquiry_id."',followup_date = '".date("Y-m-d",strtotime($data['followup_date']))."',enquiry_note = '".$this->escape($data['enquiry_note'])."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),date_modify = NOW() ");
		
		return $enquiry_id;
	}
	
	public function updateEnquiry($industry_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "enquiry_industry` SET industry = '" .$data['name']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE enquiry_industry_id = '" .(int)$industry_id. "'";
		$this->query($sql);
		
	}
	
	public function generateEnquiryNumber(){
		
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'enquiry'");
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,5,'0',STR_PAD_LEFT);
		$number = 'ENR'.$strpad;
		return $number;
	}
	
	public function getUserEmployeeIds($user_type_id,$user_id){
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	
	public function getActiveProductZippers(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActivePrintingEffectEnquiry(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_effect` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY effect_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductSpout(){
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getTotalEnquiry($filter_data=array(),$address_id='0',$all_emp){
		
		 $add_id='';
        if($address_id!=0)
        {
            $add_id = "AND e.company_name_id='".$address_id."'";
        }
		
		$sql = "SELECT COUNT(*) as total,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM " . DB_PREFIX . "enquiry e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) WHERE e.is_delete = 0 $add_id";
		
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		
		
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				//$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				//[kinjal] added on 5-10-2017
				//if($all_emp==1)
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
				//else 
					//$userEmployee = $user_id;
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( e.user_id IN ('.$userEmployee.') AND e.user_type_id = "2" )';
			}
			
			$sql .= " AND (e.user_id = '".(int)$user_id."' AND e.user_type_id = '".(int)$user_type_id."' $str)";
		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['enquiry_number'])){
				$sql .= " AND e.enquiry_number LIKE '%".$filter_data['enquiry_number']."%' ";
			}
			
			if(!empty($filter_data['company'])){
				$sql .= " AND e.company_name LIKE '%".$filter_data['company']."%' ";
			}
			
			if(!empty($filter_data['email'])){
				$sql .= " AND e.email LIKE '%".$filter_data['email']."%' ";
			}
			
			if(!empty($filter_data['country'])){
				$sql .= " AND e.country_id = '".$filter_data['country']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND e.status = '".$filter_data['status']."' ";
			}
			
			if(!empty($filter_data['customer'])){
				//$sql .= " HAVING name LIKE '%".$this->escape($filter_data['customer'])."%'";
				$sql .= " AND CONCAT(e.first_name,' ',e.last_name) LIKE '%".$this->escape($filter_data['customer'])."%'";
			}
			if (!empty($filter_data['postedby'])) {
				
               $spitdata = explode("=",$filter_data['postedby']);
				$sql .="AND e.user_type_id = '".$spitdata[0]."' AND e.user_id = '".$spitdata[1]."'";
				
			 }
			 	
		}
		
		$data = $this->query($sql);
	//	printr($sql);
		if($data->num_rows > 0){
			return $data->row['total'];
		}else{
			return false;
		}
	}
	public function getEnquiries($data,$filter_data=array(),$address_id='0',$all_emp){
		
		$add_id='';
        if($address_id!=0)
        {
            $add_id = "AND e.company_name_id='".$address_id."'";
        }
		
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		
		$sql = "SELECT am.user_name,c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.is_delete = 0 $add_id";
		
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				//$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				//[kinjal] added on 5-10-2017
                //if($all_emp==1)
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
			//	else 
				//	$userEmployee = $user_id;
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( e.user_id IN ('.$userEmployee.') AND e.user_type_id = "2" )';
			}
			
			$sql .= " AND (e.user_id = '".(int)$user_id."' AND e.user_type_id = '".(int)$user_type_id."' $str)";
		}
		
    	if(!empty($filter_data)){
			if(!empty($filter_data['enquiry_number'])){
				$sql .= " AND e.enquiry_number LIKE '%".$filter_data['enquiry_number']."%' ";
			}
			
			if(!empty($filter_data['company'])){
				$sql .= " AND e.company_name LIKE '%".$filter_data['company']."%' ";
			}
			
			if(!empty($filter_data['email'])){
				$sql .= " AND e.email LIKE '%".$filter_data['email']."%' ";
			}
			
			if(!empty($filter_data['country'])){
				$sql .= " AND e.country_id = '".$filter_data['country']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND e.status = '".$filter_data['status']."' ";
			}
			
			if(!empty($filter_data['customer'])){
				//$sql .= " HAVING name LIKE '%".$this->escape($filter_data['customer'])."%'";
				$sql .= " AND CONCAT(e.first_name,' ',e.last_name) LIKE '%".$this->escape($filter_data['customer'])."%'";
			}
			if (!empty($filter_data['postedby'])) {
				
               $spitdata = explode("=",$filter_data['postedby']);
				$sql .="AND e.user_type_id = '".$spitdata[0]."' AND e.user_id = '".$spitdata[1]."'";
				
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
	//	echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	//sonu edit on 6/01/2017
	public function getEnquiry($enquiry_id){
		
		$sql = "SELECT c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source,e.company_name_id,e.number_of_pouch_req,e.remark FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) WHERE e.enquiry_id = '".$enquiry_id."'";
		
		$data = $this->query($sql);
		//echo $sql;
		if($data->row){
						
			$product_query = "SELECT pe.product_id,pe.product_enquiry_id,p.product_name ,pe.no_of_pouches,pe.sample_sent_date,remark_note FROM `" . DB_PREFIX . "product_enquiry` pe LEFT JOIN `" . DB_PREFIX . "product` p on (pe.product_id = p.product_id) WHERE pe.enquiry_id = '".$enquiry_id."' AND pe.is_delete = '0' ";
			
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
				$enquiry_size_query = "SELECT pes.*, pv.*  FROM `" . DB_PREFIX . "product_enquiry_size` pes LEFT JOIN `" . DB_PREFIX . "pouch_volume` pv ON
				(pes.product_size_id = pv.pouch_volume_id) WHERE pes.enquiry_id='".$enquiry_id."' AND pes.product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_size_data = $this->query($enquiry_size_query);
				
				$size_array = array();
				foreach($product_size_data->rows as $size){
					
					$size_array[] = array(
						'size' => $size['volume'],
						'pouch_volume_id'=>$size['pouch_volume_id'],
						'product_enquiry_size_id' =>$size['product_enquiry_size_id'],
					);	
				}
				
				//Printing Option
				$enquiry_printing_option_query = "SELECT * FROM `" . DB_PREFIX . "product_enquiry_printing_option` WHERE enquiry_id='".$enquiry_id."' AND product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_printing_option_data = $this->query($enquiry_printing_option_query);
				$printing_option_array = array();
				foreach($product_printing_option_data->rows as $print_option){
					$printing_option_array[] = array(
						'printing_option' => $print_option['printing_option_value'],
						'product_enquiry_printing_id'=>$print_option['product_enquiry_printing_id'],
					);	
				}
				
				//Printing Effect
				$enquiry_printing_effect_query = "SELECT pe.effect_name ,pe.printing_effect_id,pepe.product_enquiry_printing_effect_id FROM `" . DB_PREFIX . "product_enquiry_printing_effect` pepe LEFT JOIN `" . DB_PREFIX . "printing_effect` pe ON (pepe.printing_effect_id = pe.printing_effect_id)  WHERE enquiry_id='".$enquiry_id."' AND product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_printing_effect_data = $this->query($enquiry_printing_effect_query);
				$printing_effect_array = array();
				foreach($product_printing_effect_data->rows as $print_effect){
					$printing_effect_array[] = array(
						'printing_effect' => $print_effect['effect_name'],
						'printing_effect_id'=>$print_effect['printing_effect_id'],
						'product_enquiry_printing_effect_id' => $print_effect['product_enquiry_printing_effect_id'],
					);	
				}
				
				//Valve
				$enquiry_valve_query = "SELECT product_valve_value,product_enquiry_valve_id FROM `" . DB_PREFIX . "product_enquiry_valve` WHERE enquiry_id='".$enquiry_id."' AND product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_valve_data = $this->query($enquiry_valve_query);
				$valve_array = array();
				foreach($product_valve_data->rows as $valve){
					$valve_array[] = array(
						'valve' => $valve['product_valve_value'],
						'product_enquiry_valve_id' => $valve['product_enquiry_valve_id'],
					);	
				}
				
				
				//Zipper
				$enquiry_zipper_query = "SELECT  pez.*,pz.*  FROM `" . DB_PREFIX . "product_enquiry_zipper` pez LEFT JOIN `" . DB_PREFIX . "product_zipper` pz ON
				(pez.product_zipper_id = pz.product_zipper_id) WHERE pez.enquiry_id='".$enquiry_id."' AND pez.product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_zipper_data = $this->query($enquiry_zipper_query);
				$zipper_array = array();
				foreach($product_zipper_data->rows as $zipper){
					$zipper_array[] = array(
						'zipper' => $zipper['zipper_name'],
						'product_zipper_id' => $zipper['product_zipper_id'],
						'product_enquiry_zipper_id' => $zipper['product_enquiry_zipper_id'],
					);	
				}
			
				
				//Spoute
				$enquiry_spout_query = "SELECT spout_name,pes.* FROM `" . DB_PREFIX . "product_spout` ps LEFT JOIN `" . DB_PREFIX . "product_enquiry_spout` pes 
				ON (ps.	product_spout_id = pes.product_spout_id) WHERE enquiry_id='".$enquiry_id."' AND product_enquiry_id = '".$product_data['product_enquiry_id']."' ";
				$product_spout_data = $this->query($enquiry_spout_query);
				$spout_array = array();
				foreach($product_spout_data->rows as $spout){
					$spout_array[] = array(
						'spout' => $spout['spout_name'],
						'product_spout_id' => $spout['product_spout_id'],
						'product_enquiry_spout_id' => $spout['product_enquiry_spout_id'],
					);	
				}
				$product_array = array();
				$product_array[] = array(
					'product_id' => $product_data['product_id'],
					'product_enquiry_id' => $product_data['product_enquiry_id'],
					'product_name' => $product_data['product_name'],
					'no_of_pouches' => $product_data['no_of_pouches'],
					'sample_sent_date' => $product_data['sample_sent_date'],
					'remark_note' => $product_data['remark_note'],
					
					//'color' => $color_array,
					'size' => $size_array,
					'printing_option' => $printing_option_array,
					'printing_effect' => $printing_effect_array,
					'valve' => $valve_array,
					'zipper' => $zipper_array,
					'spout' => $spout_array
				);	 
			}
				
			
				
										  
		
			$enquiry_array = array(
				'enquiry_id' => $data->row['enquiry_id'],
				'address_bk_id' => $data->row['company_name_id'],
				'enquiry_number' => $data->row['enquiry_number'],
				'enquiry_for' => $data->row['enquiry_for'],
				'company_name' => $data->row['company_name'],
				'client_name' => $data->row['name'],
				'first_name'=>$data->row['first_name'],
				'last_name'=>$data->row['last_name'],
				'customer_address'=>$data->row['customer_address'],
				'phone_number'=>$data->row['phone_number'],
				'mobile_number' => $data->row['mobile_number'],
				'email' => $data->row['email'],
				'industry' => $data->row['industry'],
				'fax' => $data->row['fax'],
				'country_name' => $data->row['country_name'],
				'country_id' => $data->row['country_id'],
				'website' => $data->row['website'],
				'enquiry_source' => $data->row['source'],
				'enquiry_source_id'=>$data->row['enquiry_source_id'],
				'enquiry_type' => $data->row['enquiry_type'],
				'user_id' =>$data->row['user_id'],
				'user_type_id' =>$data->row['user_type_id'],
				'date_added' => $data->row['date_added'],
				'number_of_pouch_req' => $data->row['number_of_pouch_req'],
				'filling' => $data->row['filling'],
				'weight' => $data->row['weight'],
				'material_combination' => $data->row['material_combination'],
				'remark' => $data->row['remark'],
				'products' => $product_array,
				
			);
			
			//echo $sql;
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
	public function getFollowLatestDate($enquiry_id){
		$data = $this->query("SELECT Max(followup_date) as f_date FROM " . DB_PREFIX . "enquiry_followup  WHERE enquiry_id='".$enquiry_id."'");
		if($data->num_rows){
			return $data->row;	
		}else{
			return false;	
		}
	}
//<!--kavita:1-3-2017-->
	public function addHistory($enquiry_id,$date,$note,$reminder=''){
	
	
		$date_today = date("d-m-Y");
		$reminder_date = $reminder;
		/*if($reminder == '2')
		{
			
			$date = isset($date) ? $date : date('Y-m-d');
			$reminder_date = date('Y-m-d', strtotime($date .' -2 day'));
		}else if($reminder == '3')
		{
			$date_date = isset($date) ? $date : date('Y-m-d');
			$reminder_date = date('Y-m-d', strtotime($date .' -3 day'));
			
		}else
		{
			$date = isset($date) ? $date : date('Y-m-d');
			$reminder_date = date('Y-m-d', strtotime($date .' -5 day'));
		
		}reminder ='".$reminder_date."',*/
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
		$sql= "SELECT * FROM `pouch_volume` WHERE is_delete = 0 ORDER BY right(volume, 0)";
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
	
	
	public function getTodayFollowupDates(){
		
		$today_date = date("Y-m-d");
		
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "enquiry_followup` WHERE followup_date = '".$today_date."' ");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
		
	}
	
	
	
	public function getUserList(){
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
		$data = $this->query($sql);
		//printr($data);die;
		return $data->rows;
	}
    public function getenquiryReport($post) {
   //    printr($post);die;
        $product_id = $post['product'];
        $from_date = $post['f_date'];
        $t_date = $post['t_date'];
        $user = $post['user_name'];
        $u = explode("=", $user);
        $user_id = $u[1];
        $user_type_id = $u[0];
        $con = $emp_name='';
        $cond = '';
        $f_date = '';
        $to_date = '';
        
        if ($from_date != '') {
            $f_date = $from_date;
            $con = "AND e.date_added >= '" . $from_date . "' ";
        }
        if ($t_date != '') {
            $to_date = $t_date;
            $con .= "AND  e.date_added <='" . $t_date . "'";
        }
		$datauser_admin = $this->getUser($u[1], 4);
			
        if(!empty($post['emp_name']))
		{
			$datauser_emp = $this->getUser($post['emp_name'], 2);
			$emp_name = $datauser_emp['first_name'].' '.$datauser_emp['last_name'];
			$sql = "SELECT c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source,am.user_name FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (am.user_id = e.user_id AND am.user_type_id = e.user_type_id) WHERE e.is_delete=0 AND e.user_id = '" .$post['emp_name'] . "' AND e.user_type_id='2' $con";
        } 
		else 
		{
			$userEmployee = $this->getUserEmployeeIds(4, $u[1]);
			$set_user_id = $u[1];
			$set_user_type_id =4;
            $str = '';
            if ($userEmployee) {
                $str = ' OR ( e.user_id IN (' . $userEmployee . ') AND e.user_type_id = 2 )';
            }
            $sql = "SELECT c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source,am.user_name FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (am.user_id = e.user_id AND am.user_type_id = e.user_type_id) WHERE e.is_delete=0 AND ((e.user_id = '" . $set_user_id . "' AND e.user_type_id='" . $set_user_type_id . "') $str) $con";
        }
        $data = $this->query($sql);
        //printr($sql);die;
        if ($data->rows) {
            $final_array = array();
            foreach ($data->rows as $data_enquiry) {
                $enquiry_id = $data_enquiry['enquiry_id'];
                if ($product_id != '') {
                    $cond = " AND pe.product_id = '" . $product_id . "'";
                }
                $product_query = "SELECT pe.product_id,pe.product_enquiry_id,p.product_name,pe.enquiry_id FROM `" . DB_PREFIX . "product_enquiry` pe LEFT JOIN `" . DB_PREFIX . "product` p on (pe.product_id = p.product_id) WHERE pe.enquiry_id = '" . $enquiry_id . "' $cond ";
                $products_data = $this->query($product_query);
                //printr();
                $product = array();
                if ($products_data->num_rows) {
                    foreach ($products_data->rows as $product_data) {
                        //size
                        $enquiry_size_query = "SELECT pv.volume FROM `" . DB_PREFIX . "product_enquiry_size` pes LEFT JOIN `" . DB_PREFIX . "pouch_volume` pv ON
(pes.product_size_id = pv.pouch_volume_id) WHERE pes.enquiry_id='" . $enquiry_id . "' AND pes.product_enquiry_id = '" . $product_data['product_enquiry_id'] . "' ";
                        $product_size_data = $this->query($enquiry_size_query);
                        $size_array = array();
                        foreach ($product_size_data->rows as $size) {
                            $size_array[] = array(
                                'size' => $size['volume'],);
                        }


                        $product_array = array(
                            'product_id' => $product_data['product_id'],
                            'product_name' => $product_data['product_name'],
                            'size' => $size_array,
                        );

                        $product[] = $product_array;
                    }
                }
                    $enquiry_array[] = array(
                        'enquiry_id' => $data_enquiry['enquiry_id'],
                        'enquiry_number' => $data_enquiry['enquiry_number'],
                        'enquiry_for' => $data_enquiry['enquiry_for'],
                        'company_name' => $data_enquiry['company_name'],
                        'client_name' => $data_enquiry['name'],
                        'mobile_number' => $data_enquiry['mobile_number'],
                        'email' => $data_enquiry['email'],
                        'country_name' => $data_enquiry['country_name'],
                        'website' => $data_enquiry['website'],
                        'enquiry_source' => $data_enquiry['source'],
                        'enquiry_source_id' => $data->row['enquiry_source_id'],
                        'enquiry_type' => $data_enquiry['enquiry_type'],
                        'user_id' => $data_enquiry['user_id'],
                        'user_type_id' => $data_enquiry['user_type_id'],
                        'user_name' => $data_enquiry['user_name'],
                        'date_added' => $data_enquiry['date_added'],
                        'products' => $product,
                        'from_date' => $f_date,
                        'to_date' => $to_date,
						'admin_name' =>$datauser_admin['first_name'].' '.$datauser_admin['last_name'],
						'emp_name'=>$emp_name );
                    $final_array = $enquiry_array;
                    $product = '';
                //}
            }
            //printr($final_array);
            return $final_array;
        } else {
            return false;
        }
    }
    /*public function getenquiryReport_old($post) {
   //    printr($post);die;
        $product_id = $post['product'];
        $from_date = $post['f_date'];
        $t_date = $post['t_date'];
        $user = $post['user_name'];
        $u = explode("=", $user);
        $user_id = $u[1];
        $user_type_id = $u[0];
        $con = $emp_name='';
        $cond = '';
        $f_date = '';
        $to_date = '';
        
        if ($from_date != '') {
            $f_date = $from_date;
            $con = "AND e.date_added >= '" . $from_date . "' ";
        }
        if ($t_date != '') {
            $to_date = $t_date;
            $con .= "AND  e.date_added <='" . $t_date . "'";
        }
		$datauser_admin = $this->getUser($u[1], 4);
			
        if(!empty($post['emp_name']))
		{
			$datauser_emp = $this->getUser($post['emp_name'], 2);
			$emp_name = $datauser_emp['first_name'].' '.$datauser_emp['last_name'];
			$sql = "SELECT c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source,am.user_name FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (am.user_id = e.user_id AND am.user_type_id = e.user_type_id) WHERE e.is_delete=0 AND e.user_id = '" .$post['emp_name'] . "' AND e.user_type_id='2' $con";
        } 
		else 
		{
           
            
			$userEmployee = $this->getUserEmployeeIds(4, $u[1]);
			//printr($userEmployee);
			$set_user_id = $u[1];
			$set_user_type_id =4;
            
            $str = '';
            if ($userEmployee) {
                $str = ' OR ( e.user_id IN (' . $userEmployee . ') AND e.user_type_id = 2 )';
            }
            $sql = "SELECT c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source,am.user_name FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (am.user_id = e.user_id AND am.user_type_id = e.user_type_id) WHERE e.is_delete=0 AND ((e.user_id = '" . $set_user_id . "' AND e.user_type_id='" . $set_user_type_id . "') $str) $con";
        }
        //echo $sql;
        $data = $this->query($sql);

        if ($data->rows) {
            $final_array = array();
            foreach ($data->rows as $data_enquiry) {
                $enquiry_id = $data_enquiry['enquiry_id'];
                if ($product_id != '') {
                    $cond = " AND pe.product_id = '" . $product_id . "'";
                }
                $product_query = "SELECT pe.product_id,pe.product_enquiry_id,p.product_name,pe.enquiry_id FROM `" . DB_PREFIX . "product_enquiry` pe LEFT JOIN `" . DB_PREFIX . "product` p on (pe.product_id = p.product_id) WHERE pe.enquiry_id = '" . $enquiry_id . "' $cond ";
                $products_data = $this->query($product_query);
                if ($products_data->num_rows) {
                    foreach ($products_data->rows as $product_data) {
                        //size
                        $enquiry_size_query = "SELECT pv.volume FROM `" . DB_PREFIX . "product_enquiry_size` pes LEFT JOIN `" . DB_PREFIX . "pouch_volume` pv ON
(pes.product_size_id = pv.pouch_volume_id) WHERE pes.enquiry_id='" . $enquiry_id . "' AND pes.product_enquiry_id = '" . $product_data['product_enquiry_id'] . "' ";
                        $product_size_data = $this->query($enquiry_size_query);
                        $size_array = array();
                        foreach ($product_size_data->rows as $size) {
                            $size_array[] = array(
                                'size' => $size['volume'],);
                        }


                        $product_array = array(
                            'product_id' => $product_data['product_id'],
                            'product_name' => $product_data['product_name'],
                            'size' => $size_array,
                        );

                        $product[] = $product_array;
                    }
                    $enquiry_array[] = array(
                        'enquiry_id' => $data_enquiry['enquiry_id'],
                        'enquiry_number' => $data_enquiry['enquiry_number'],
                        'enquiry_for' => $data_enquiry['enquiry_for'],
                        'company_name' => $data_enquiry['company_name'],
                        'client_name' => $data_enquiry['name'],
                        'mobile_number' => $data_enquiry['mobile_number'],
                        'email' => $data_enquiry['email'],
                        'country_name' => $data_enquiry['country_name'],
                        'website' => $data_enquiry['website'],
                        'enquiry_source' => $data_enquiry['source'],
                        'enquiry_source_id' => $data->row['enquiry_source_id'],
                        'enquiry_type' => $data_enquiry['enquiry_type'],
                        'user_id' => $data_enquiry['user_id'],
                        'user_type_id' => $data_enquiry['user_type_id'],
                        'user_name' => $data_enquiry['user_name'],
                        'date_added' => $data_enquiry['date_added'],
                        'products' => $product,
                        'from_date' => $f_date,
                        'to_date' => $to_date,
						'admin_name' =>$datauser_admin['first_name'].' '.$datauser_admin['last_name'],
						'emp_name'=>$emp_name );
                    $final_array = $enquiry_array;
                    $product = '';
                }
            }
            //printr($final_array);
            return $final_array;
        } else {
            return false;
        }
    }*/
	
public function viewenquiryReport($data) {
		//printr($data);die;
        $html = '';
        $html .= "<div class='form-group'>
						<div class='table-responsive'>";
        if (!empty($data[0]['from_date']) && !empty($data[0]['to_date'])) {
            $html .= "&nbsp;&nbsp;&nbsp;&nbsp;<span>Searching Date From: <b>" . dateFormat(4, $data[0]['from_date']) . "</b> To: <b>" . dateFormat(4, $data[0]['to_date']) . "</b></span><br></br>";
        }
        $html .= "<table class='table table-striped b-t text-small' id='enquiry_report'>
								<thead>
									<tr>
										<th colspan='13'>Admin Name : ".$data[0]['admin_name']."</th>
									</tr>";
									if(isset($data[0]['emp_name']) && !empty($data[0]['emp_name']))
									{
										$html .= "<tr>
													<th colspan='13'>Employee Name : ".$data[0]['emp_name']."</th>
												</tr>";
									}
									$html .= "<tr>
                                          <th>Sr. No</th>
                                          <th>Inquiry Types</th>
                                          <th>Company Name<br>Customer Name</th>
                                          <th>Email</th>
                                          <th>Address</th>
                                          <th>Purpose</th>
                                          <th>Product Description</th>
                                          <th>Short Code</th>
                                          <th>Esti. Volume</th>
                                          <th>Samples</th>
                                          <th>Sample Dispatch Dt.</th>
                                          <th>Tracking Details</th>
                                          <th>Next F Up</th>
                                          <th>Deal Closed</th>
                                          <th>Posted By</th>
                                    </tr>
                                </thead>
                                <tbody>";
        if (isset($data) && !empty($data)) {
            $i = 1;
            foreach ($data as $enquiry) {// href='". HTTP_SERVER . "admin/index.php%3Froute=enquiry&mod=view&enquiry_id=" . encode($enquiry['enquiry_id']) ."'
                $html .= "<tr id='" . $enquiry['enquiry_id'] . "' valign='top' >
													<td>" .$i. "</td>
													<td>" . $enquiry['enquiry_type'] . "</td>
													<td><b>" . $enquiry['company_name'] . "</b><br>" . $enquiry['client_name'] . "</td>
													<td>" . $enquiry['email'] . "</td>
													<td></td>
													<td>" . $enquiry['enquiry_for'] . "</td>
													<td colspan='3'>
														<table class='table table-striped b-t text-small' id='enquiry'>";
                                                            foreach ($enquiry['products'] as $product) {
                                                                $html .= "<tr valign='top'>
                                            																			<td>" . $product['product_name'] . "<br></td>
                                            																			<td></td>
                                            																			<td>";
                                                                foreach ($product['size'] as $size) {
                                            
                                                                    $html .= "- " . $size['size'] . "<br/>";
                                                                }
                                                                $html .= "</td>
                                            																		</tr>";
                                                            }
                                                $html .= "</table>
												   </td>
												   <td></td>
												   <td></td>
												   <td></td>
												   <td>";
                $history_data = $this->getFollowLatestDate($enquiry['enquiry_id']);

                $html .= "" . date("d-M-Y", strtotime($history_data['f_date'])) . "";
                $html .= "</td>
												   <td></td>
												   <td>";
                $html .= $enquiry['user_name'] . "</td>
												 
											   </tr>";
	            $i++;
            }
        } else {
            $html = "<tr>No Records Found!!</tr>";
        }
        $html .= "</tbody>
									
								</table>
							</div>
						</div>";
        return $html;
    }
		
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			//$sql = "SELECT u.user_name,u.first_name,u.last_name,am.user_type_id,am.user_id FROM " . DB_PREFIX ."user u, " . DB_PREFIX ."account_master am WHERE u.user_id = '".(int)$user_id."' AND am.user_id = '".(int)$user_id."' AND am.user_type_id = '".(int)$user_type_id."'";
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_id as international_branch_id,e.user_name, co.country_id, co.country_name, e.first_name, e.last_name, e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.international_branch_id,ib.user_name,ib.discount, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			/*$sql = "SELECT co.country_id, co.country_name, c.first_name, c.last_name, c.email_signature, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";*/
			return false;
		}
	//	echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	public function updateEnquiryrecode($data){
		//printr($data);
		$enquiry_id = $data['enquiry_id'];
		
		//sonu add 22-4-2017
		
			if (isset($data['enquiry_source_details']) && !empty($data['enquiry_source_details'])) {
				$enquiry_source_details = $data['enquiry_source_details'];
				$exhibition_id = $data['enquiry_source_details'];
			} else {
				$enquiry_source_details = $data['exhibition_name'];
				$exhibition_id = $data['enquiry_source_id'];
			}
		$material_combination='';
		if(isset($data['material_combination']))
        {
            $material_combination= implode(',',$data['material_combination']);
        }
		$this->query(" UPDATE  enquiry SET  enquiry_for = '".$data['enquiry_for']."',enquiry_source_details = '" . $enquiry_source_details . "',company_name='".$data['company_name']."', first_name = '".$data['first_name']."',last_name = '".$data['last_name']."',customer_address = '".$data['customer_address']."',phone_number = '".$data['phone_number']."', mobile_number = '".$data['mobile_number']."',fax = '".$data['fax']."',email= '".$data['email']."',country_id = '".$data['country_id']."',website = '".$data['website']."', enquiry_source_id = '".$data['enquiry_source_id']."',enquiry_type = '".$data['enquiry_type']."',remark = '".$data['remark']."',number_of_pouch_req = '".$data['number_of_pouch_req']."',filling = '".$data['filling']."',weight = '".$data['weight']."',material_combination = '".$material_combination."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW() WHERE enquiry_id ='".$enquiry_id."' AND is_delete = '0' ");
			
		return $enquiry_id;
	}
	public function remove_enquiry_record($enquiry_id,$product_enquiry_id)
	{
		$this->query("UPDATE " . DB_PREFIX . "product_enquiry  SET is_delete = '1', date_modify = NOW() WHERE enquiry_id = '".$enquiry_id."' AND product_enquiry_id='".$product_enquiry_id."'  ");
	}
	
	public function getIndustrys(){
		$data = $this->query("SELECT enquiry_industry_id, industry FROM " . DB_PREFIX . "enquiry_industry WHERE is_delete = 0 ORDER BY industry ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	

	//[manirul] --> start
	public function getTotalFollowup($filter_data=array(),$address_id='0',$all_emp){
		
		//sonu add 19-4-2017
		$add_id='';
        if($address_id!=0)
        {
            $add_id = "AND e.company_name_id='".$address_id."'";
        }
		
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	
		$today_date = date("Y-m-d");
		
		//echo $today_date;die;
		$sql = "SELECT am.user_name,e.enquiry_id,e.enquiry_number,ef.*,COUNT(*) as total,CONCAT(e.first_name,' ',e.last_name) as name FROM " . DB_PREFIX . "enquiry_followup ef LEFT JOIN `" . DB_PREFIX . "enquiry` e ON (ef.enquiry_id=e.enquiry_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.is_delete>= 0 $add_id";
		
		//echo $sql;die;
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				if($all_emp==1)
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
				else 
					$userEmployee = $user_id;
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( e.user_id IN ('.$userEmployee.') AND e.user_type_id = "2" )';
			}
			
			$sql .= " AND e.user_id = '".(int)$user_id."' AND e.user_type_id = '".(int)$user_type_id."' $str";
		}
		
		$sql .= " ORDER BY ef.enquiry_followup_id " ;
		if(!empty($filter_data)){
			if(!empty($filter_data['enquiry_number'])){
				$sql .= " AND e.enquiry_number = '".$filter_data['enquiry_number']."' ";
			}
			
			
			if(!empty($filter_data['company'])){
				$sql .= " AND e.company_name = '".$filter_data['company']."' ";
			}
			//printr($filter_data['company']);die;
			if(!empty($filter_data['email'])){
				$sql .= " AND e.email = '".$filter_data['email']."' ";
			}
			
			
			if(!empty($filter_data['country'])){
				$sql .= " AND e.country_id = '".$filter_data['country']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND e.status = '".$filter_data['status']."' ";
			}
			
			//printr($filter_data['status']);die;
			if(!empty($filter_data['customer'])){
				$sql .= " HAVING name LIKE '%".$this->escape($filter_data['customer'])."%'";
			}
			
					
		}
		
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows > 0){
			return $data->row['total'];
		}else{
			return false;
		}
	}
	public function getFollowup($data,$filter_data=array(),$address_id='0',$all_emp)
	{
		//sonu 19-4-2017
		$add_id='';
        if($address_id!=0)
        {
            $add_id = "AND e.company_name_id='".$address_id."'";
        }


		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	
		$today_date = date("Y-m-d");
		
		$sql = "SELECT am.user_name,c.country_name,ef.*,e.*,CONCAT(e.first_name,' ',e.last_name) as name FROM " . DB_PREFIX . "enquiry_followup ef LEFT JOIN `" . DB_PREFIX . "enquiry` e ON (ef.enquiry_id=e.enquiry_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id)  LEFT JOIN country c on e.country_id=c.country_id WHERE e.is_delete= 0 $add_id ";
		//echo $sql;die;
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				if($all_emp==1)
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
				else 
					$userEmployee = $user_id;
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( e.user_id IN ('.$userEmployee.') AND e.user_type_id = "2" )';
			}
			
			$sql .= " AND e.user_id = '".(int)$user_id."' AND e.user_type_id = '".(int)$user_type_id."' $str";
		}
		
		//$sql .= " ORDER BY ef.enquiry_followup_id " ;	
		//echo $sql;die;
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY e.enquiry_id";
			
		}
		//echo $sql;die;
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		//echo $sql;die;
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
	
	public function getFollowup_enquiry_detail($enquiry_id)
	{
		
			$sql = "SELECT am.user_name,c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name FROM " . DB_PREFIX . "enquiry as e ,country as c,account_master as am                    WHERE e.enquiry_id = '".$enquiry_id."' AND e.country_id = c.country_id AND e.user_id = am.user_id ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
		//sonu:[21-4-2017]
	public function getLastIdAddress() {
        $sql = "SELECT address_book_id FROM  address_book_master ORDER BY address_book_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['address_book_id'];
        } else {
            return false;
        }
    }
	
	

	
	
	//sonu:[21-4-2017]
    public function getCompanyDetail($company_name) {

        $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

        $user_type_id = $_SESSION['LOGIN_USER_TYPE'];

        if ($user_type_id == 2) {

            $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");

            $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);

            $set_user_id = $parentdata->row['user_id'];

            $set_user_type_id = $parentdata->row['user_type_id'];

            $str = ' OR ( aa.user_id IN (' . $userEmployee . ') AND aa.user_type_id = 2 )';

           // $sql = "SELECT a.*,ca.company_address_id,ca.c_address,ca.email_1  FROM address_book_master as a LEFT JOIN company_address as ca ON (as.address_book_id=ca.address_book_id) LEFT JOIN factory_address as fa ON (as.address_book_id=fa.address_book_id) WHERE  a.address_book_id = ca.address_book_id AND a.company_name LIKE '%" . $company_name . "%' AND ((a.user_id='" . $set_user_id . "' AND a.user_type_id='" . $set_user_type_id . "') $str  ) GROUP by a.address_book_id";
            $sql= "SELECT aa.address_book_id,aa.vat_no,aa.website, aa.company_name,aa.industry, cs.company_address_id, cs.c_address,cs.email_1,cs.country,cs.phone_no,fa.factory_address_id, fa.f_address FROM address_book_master as aa "
                    . "LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 "
                    . "AND aa.company_name LIKE '%".$company_name."%' AND ((aa.user_id ='".$set_user_id."' AND aa.user_type_id='".$set_user_type_id."') $str) GROUP BY aa.address_book_id";    
            
        } else if ($user_type_id == 4) {

            $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);

            $set_user_id = $user_id;

            $set_user_type_id = $user_type_id;

            $str = ' OR ( aa.user_id IN (' . $userEmployee . ') AND aa.user_type_id = 2 )';
            //$sql = "SELECT a.*,ca.company_address_id,ca.c_address,ca.email_1 FROM address_book_master as a , company_address as ca  WHERE  a.address_book_id = ca.address_book_id AND a.company_name LIKE '%" . $company_name . "%' AND ((a.user_id='" . $set_user_id . "' AND a.user_type_id='" . $set_user_type_id . "') $str  ) GROUP by a.address_book_id";
            $sql= "SELECT aa.address_book_id,aa.vat_no,aa.website, aa.company_name,aa.industry, cs.company_address_id, cs.c_address,cs.email_1,cs.country,cs.phone_no,fa.factory_address_id, fa.f_address FROM address_book_master as aa "
                    . "LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 "
                    . "AND aa.company_name LIKE '%".$company_name."%' AND ((aa.user_id ='".$set_user_id."' AND aa.user_type_id='".$set_user_type_id."')  $str) GROUP BY aa.address_book_id";    
        } else {

            $set_user_id = $user_id;

            $set_user_type_id = $user_type_id;

            //$sql = "SELECT a.*,ca.company_address_id,ca.c_address,ca.email_1 FROM address_book_master as a , company_address as ca WHERE  a.address_book_id = ca.address_book_id AND a.company_name LIKE '%" . $company_name . "%' GROUP by a.address_book_id ";
            $sql= "SELECT aa.address_book_id,aa.vat_no,aa.website, aa.company_name, aa.industry,cs.company_address_id, cs.c_address,cs.email_1,cs.country,cs.phone_no,fa.factory_address_id, fa.f_address FROM address_book_master as aa "
                    . "LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 "
                    . "AND aa.company_name LIKE '%".$company_name."%' GROUP BY aa.address_book_id";    
        }

        $data = $this->query($sql);
        //printr($data); die;
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }
	
	//sonu add 22-4-2017
	
	 public function getExhibitions() {
        $data = $this->query("SELECT * FROM `" . DB_PREFIX . "exhibition_details` WHERE status = '1' AND is_delete = 0");


        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }

	
	
	//end
	
	public function getIBList() {
        $sql = "SELECT international_branch_id,address_id,CONCAT(first_name,' ',last_name) as user_name FROM international_branch";
        $data = $this->query($sql);
        return $data->rows;
    }
    
    public function getEmpList($ib_id,$val)
	{
		$ib=explode('=',$ib_id);
		$userEmployee = $this->getUserEmployeeIds('4', $ib[1]);
		$sql = "SELECT CONCAT(first_name,' ',last_name) as user_name,employee_id FROM employee WHERE employee_id IN (".$userEmployee.") AND is_delete=0";
		$data = $this->query($sql);
                if($data->num_rows){
                    return $data->rows;
                }
                else{
                    return false;
                }
		
		
        
	}
	
	public function getMenuPermission()
	{
		$perm_cond = "add_permission REGEXP '232' AND edit_permission REGEXP '232' AND delete_permission REGEXP '232' AND view_permission REGEXP '232'";
        $sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
		$dataper=$this->query($sql);
		if($dataper->num_rows)
		{
			return 1;
		}
		else
		{
			return false;
		}
			
	}
	
	 public function get_size($product_id) {
        $data = $this->query("SELECT * FROM `" . DB_PREFIX . "size_master` as s,pouch_volume as v WHERE s.product_id ='".$product_id."' AND v.volume = s.volume  GROUP BY v.pouch_volume_id ");
		//printr($data);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }
	public function getInternationalUserList() {
        $sql = "SELECT * FROM " . DB_PREFIX . " account_master WHERE user_type_id=4  ORDER BY user_name ASC";
        $data = $this->query($sql);
        //printr($data);die;
        return $data->rows;
    }
    
    	public function getTotalCustomerEnquiry($filter_data=array(),$address_id='0',$all_emp){
	//	printr("hii");
		
		
	
		$sql = "SELECT COUNT(*) as total FROM `quotation_request` WHERE sales_emp_id!=44 ";
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		
	//		printr($sql);
	//		printr($user_type_id.'=='.$user_id);
			if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
			
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
			    	$sql .= " AND (sales_emp_id = '".(int)$user_id."')";
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				if($userEmployee){
				$str = ' OR ( sales_emp_id IN ('.$userEmployee.')  AND sales_emp_type_id =2)';
		    	}
			
		    	$sql .= " AND (sales_emp_id = '".(int)$user_id."'  AND sales_emp_type_id ='4' $str)";
			}
		
		
		}
	if(!empty($filter_data)){
			if(!empty($filter_data['company'])){
				$sql .= " AND company_name LIKE '%".$filter_data['company']."%' ";
			} 
			if(!empty($filter_data['email'])){
				$sql .= " AND email LIKE '%".$filter_data['email']."%' ";
			}
			
			if(!empty($filter_data['filter_phone_no'])){
				$sql .= " AND phone_no LIKE '%".$filter_data['filter_phone_no']."%' ";
			}	if(!empty($filter_data['customer'])){
				//$sql .= " HAVING name LIKE '%".$this->escape($filter_data['customer'])."%'";
				$sql .= " AND contact_name LIKE '%".$this->escape($filter_data['customer'])."%'";
			}
			if(!empty($filter_data['filter_whatsapp'])){
				$sql .= " AND email LIKE '%".$filter_data['filter_whatsapp']."%' ";
			}
			if(!empty($filter_data['filter_address'])){
				$sql .= " AND address LIKE '%".$filter_data['filter_address']."%' ";
			}
			if (!empty($filter_data['postedby'])) {
				
               $spitdata = explode("=",$filter_data['postedby']);
				$sql .="AND sales_emp_id = '".$spitdata[1]."' AND sales_emp_type_id='".$spitdata[0]."'";
				
			 }
			 	
		}
		 //	printr($sql);
		$data = $this->query($sql);
	 
	//	printr($data);
		if($data->num_rows > 0){ 
			return $data->row['total'];
		}else{
			return false;
		}
	}
	
	public function getCustomerEnquiries($data,$filter_data=array(),$address_id='0',$all_emp){
	
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		
		$sql = "SELECT * FROM `quotation_request` WHERE sales_emp_id!=44 ";
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
			
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
			    	$sql .= " AND (sales_emp_id = '".(int)$user_id."')";
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				if($userEmployee){
				$str = ' OR ( sales_emp_id IN ('.$userEmployee.')  AND sales_emp_type_id =2)';
		    	}
			
		    	$sql .= " AND (sales_emp_id = '".(int)$user_id."'  AND sales_emp_type_id ='4' $str)";
			}
		
		
		}
		
    	if(!empty($filter_data)){
			if(!empty($filter_data['company'])){
				$sql .= " AND company_name LIKE '%".$filter_data['company']."%' ";
			}
			if(!empty($filter_data['email'])){
				$sql .= " AND email LIKE '%".$filter_data['email']."%' ";
			}
			
			if(!empty($filter_data['filter_phone_no'])){
				$sql .= " AND phone_no LIKE '%".$filter_data['filter_phone_no']."%' ";
			}
			if(!empty($filter_data['filter_whatsapp'])){
				$sql .= " AND email LIKE '%".$filter_data['filter_whatsapp']."%' ";
			}
			if(!empty($filter_data['filter_address'])){
				$sql .= " AND address LIKE '%".$filter_data['filter_address']."%' ";
			}
			if(!empty($filter_data['customer'])){
				//$sql .= " HAVING name LIKE '%".$this->escape($filter_data['customer'])."%'";
				$sql .= " AND contact_name LIKE '%".$this->escape($filter_data['customer'])."%'";
			}
			if (!empty($filter_data['postedby'])) {
				
               $spitdata = explode("=",$filter_data['postedby']);
				$sql .="AND sales_emp_id = '".$spitdata[1]."' AND sales_emp_type_id='".$spitdata[0]."'";
				
			 }
			 	
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY reuest_id";	
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
	//	printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false; 
		}
	} 
	public function getCustomerEnquiry($reuest_id){
	//	printr($reuest_id);
		$sql = "SELECT * FROM `quotation_request` WHERE sales_emp_id!=44 AND reuest_id='".$reuest_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	} 
	
		public function getUserListIndia()
		{
			$userEmployee = $this->getUserEmployeeIds('4','6');
			$sql = "SELECT e.employee_id,CONCAT(e.first_name,' ',e.last_name) as name,acc.email FROM " . DB_PREFIX . "employee e , account_master acc WHERE acc.user_name=e.user_name AND acc.user_type_id = '2' AND acc.user_id IN (" .$userEmployee . ") AND user_type='20'";
			$data=$this->query($sql);
			if ($data->num_rows) {
				return $data->rows;
			} else {
				return false;
			}
		}
	public function getConversion($branch_id,$lang_id,$emp_id=0)
	{
		$lang = explode(',',$lang_id);
		$array_data =array();
		/*mysql_query ("set character_set_results='utf8'"); */
		$str='';
		if($emp_id!=0)
		    $str = ' AND emp_id='.$emp_id;
		foreach($lang as $langs)
		{
		   $sql = "SELECT * FROM `" . DB_PREFIX . "cust_inquiry_page` WHERE user_id = '".$branch_id."' AND lang_id='".$langs."' $str";
		   $data = $this->query($sql);
		   if($data->num_rows)
		    $array_data[]=$data->row;
		}

		if($array_data){
			return $array_data;
		}else{ 
			return false;
		}
	}
    public function getUserClient($user_id,$user_type_id)
	{
		$cond = '';
		if ($user_type_id == 1) {

            $sql = "SELECT ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state,ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX . "user u LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
            //echo $sql;
        } elseif ($user_type_id == 2) {

            $sql = "SELECT e.lang_id,e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address,ib.international_branch_id FROM " . DB_PREFIX . "employee e LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } elseif ($user_type_id == 4) {

            $sql = "SELECT ib.lang_id,ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX . "international_branch ib LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } else {
            $sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX . "client c LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '" . (int) $user_id . "' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "' ";
        }
		$data = $this->query($sql);
		//printr($data);
		return $data->row;
	}
	public function viewCustomerLeadsReport($post){
	  	$to_date = $post['t_date'];
        $f_date = $post['f_date'];
         $user=$post['user_name'];  
          $arr=explode('=',$user); 
     //   printr($arr);
	    $sql = "SELECT * FROM `quotation_request` as q ,employee as e WHERE e.employee_id=q.sales_emp_id AND q.sales_emp_id!=44  AND q.sales_emp_id='".$arr[1]."' AND q.sales_emp_type_id='".$arr[0]."' AND  q.date_added >= '" . $f_date . "' AND q.date_added <='" . $to_date . "' GROUP BY q.email" ;
        //echo $sql;
         $data = $this->query($sql);
           $html='';
        	if ($data->num_rows) {
        	   
		//printr($data);
			$html='	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div class="panel-body font_medium" id="print_div" style="font-size: 20px; page-break-before: always;" >';
	         $html .='<div style="text-align:center; font-size: 18px;"><b>CUSTOMER LEADS REPORT </b></div>';
	         $html .='<div style="text-align:center; font-size: 18px;"><b><span><h4>Searching Date From: <b>' . dateFormat(4, $f_date) . '</b> To: <b>' . dateFormat(4, $to_date) . '</b></h4><br><b>'.$data->row['first_name'].' '.$data->row['last_name'].'</b></span></b>';
		     $html.='</div>';
		     $html.='<div class="table-responsive" style=" width: 100%;float: left;  font-size: 12px;">';
				$html.='<table class="table table-striped b-t text-small" style=" width: 100%; border:1; font-size: 14px;" >
					<thead >
					<tr style=" border:1;">
						<th><b>Company Name</b></th>
						<th><b>Contact Name</b></th>
						<th><b>Email</b></th>
						<th><b>Contact no</b></th>
					';
					
					
				$html.='</tr>
					</thead>
					<tbody style=" border:1;">';
				
                		 foreach ($data->rows as $d) {
                		   //  printr($d);
                		      	$html.=' <tr>
                		      	<td>'.$d['company_name'].'</td>
                		      	<td>'.$d['contact_name'].'</td>
                		      	<td>'.$d['email'].'</td>
                		      	<td>'.$d['phone_no'].'</td>
                		      	</tr>';
						 }
            								         
    	    	$html.='</tbody></table>';
				$html.='</div></div></form>';
			}
		return $html;
	}
	public function getUserListReport()
	{
	//	$userEmployee = $this->getUserEmployeeIds('4','6');
		$sql = "SELECT e.employee_id,CONCAT(e.first_name,' ',e.last_name) as name,acc.email,e.user_name FROM " . DB_PREFIX . "employee e , account_master acc WHERE acc.user_name=e.user_name ";
		$data=$this->query($sql);
		if ($data->num_rows) {
			return $data->rows;
		} else {
			return false;
		}
	}
	public function check_customer($email)
	{
	    $contacts = "SELECT ca.email_1,ab.user_id,ab.user_type_id FROM company_address as ca,address_book_master as ab WHERE ca.email_1='".$email."' AND ca.is_delete=0 AND ab.is_delete=0 AND ab.address_book_id =ca.address_book_id";
		$datacontacts= $this->query($contacts);
		if($datacontacts->num_rows) {
			return $datacontacts->row;
		} else {
			return 0;
		}
	}
	public function getProformaData($email,$from_date)
	{ 
	    $data= $this->query("SELECT p.pro_in_no,p.email,p.for_freight_charge,p.invoice_total,p.customer_name,p.payment_status,p.invoice_date,am.user_name FROM proforma_product_code_wise as p ,account_master as am  WHERE p.email LIKE '%".$email."%' AND p.added_by_user_id=am.user_id AND p.added_by_user_type_id=am.user_type_id AND p.is_delete=0 AND p.status=1  AND p.date_added >= '" .$from_date. "'");
		if($data->num_rows) {
			return $data->rows; 
		} else {
			return 0;
		}
	}
	public function getTaxInvoiceData($email,$admin_user_id,$from_date,$pro_in_no)
	{
	    $table='sales_invoice';$fatch='invoice_no';
	    if($admin_user_id==10)
	        $table='packing_order';$fatch='order_no as invoice_no,';
	    $data= $this->query("SELECT $fatch date_added as sales_date,email,delivery_address,pro_in_no as proforma_num FROM $table WHERE email LIKE '%".$email."%' AND is_delete=0 AND date_added >= '" .$from_date. "' AND pro_in_no LIKE '%".$pro_in_no."%'");
		if($data->num_rows) {
			return $data->row;
		} else {
			return 0;
		}
	}
	public function getContactVsOrderReport($post)
	{//created by kinjal on 1-7-2019
	    $str =$date_con=$user_name=$date_inv='';
	   // printr($post);//die;
	   if($post['emp_name']!='')
	   {
	        $users = explode('=',$post['emp_name']);
	        $str = " AND e.user_id = '".$users[1]."' AND e.user_type_id='".$users[0]."' ";
	        if($users[0]=='2')
	        {
	            $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$users[1]."' ");
	            $admin_user_id=$parentdata->row['user_id'];
	        }
	        else
	            $admin_user_id=$users[1];
	        $user_name= $this->getUser($users[1],$users[0]);
	        //printr($user_name);
	   }
	   else
	   {
	       $users = explode('=',$post['user_name']);
	       $admin_user_id=$users[1];
	       $userEmployee = $this->getUserEmployeeIds($users[0],$users[1]);
	       if($userEmployee)
	            $str =" OR (e.user_id IN (".$userEmployee.") AND e.user_type_id=2)";
	            
	       $str = " AND (e.user_id = '".$users[1]."' AND e.user_type_id = '".$users[0]."' $str)";
	   }
	   if($post['f_date']!='' && $post['t_date']!='')
	   {
	        $date_con = " AND e.date_added >= '" .$post['f_date']. "' AND  e.date_added <='" .$post['t_date']. "' ";
	        $date_inv = " AND p.date_added >= '" .$post['f_date']. "' ";
	        $date_pro = " AND pr.date_added >= '" .$post['f_date']. "' ";
	   }
	    if($admin_user_id=='10'){
            // $data = $this->query("SELECT am.user_name,am.user_id as u_id,am.user_type_id as u_type_id, p.order_no as invoice_no,p.order_date as invoice_date,pr.pro_in_no,pr.date_added as pro_date,p.delivery_address as cust_add,p.pro_in_no as pro_no,e.*  FROM `enquiry` as e LEFT JOIN account_master as am ON (e.user_id = am.user_id AND e.user_type_id = am.user_type_id) LEFT JOIN packing_order as p ON (p.email=e.email $date_inv AND pr.payment_status=1 ) LEFT JOIN proforma_product_code_wise as pr ON (pr.email=e.email $date_pro ) WHERE e.is_delete = 0 $str $date_con ORDER BY p.packing_order_id DESC");
            //$data = $this->query("SELECT am.user_name,e.*  FROM `enquiry` as e,account_master as am WHERE e.user_id = am.user_id AND e.user_type_id = am.user_type_id AND e.is_delete = 0 $str $date_con ORDER BY e.enquiry_id DESC");
            $data = $this->query("SELECT am.user_name,pr.pro_in_no,pr.date_added as pro_date,pr.for_freight_charge,pr.address_info as cust_add,pr.payment_status,e.*  FROM `enquiry` as e LEFT JOIN account_master as am ON (e.user_id = am.user_id AND e.user_type_id = am.user_type_id) LEFT JOIN proforma_product_code_wise as pr ON (pr.email=e.email $date_pro AND pr.payment_status=1) WHERE e.is_delete = 0 $str $date_con ORDER BY pr.proforma_id DESC");
        }else{
            //$data = $this->query("SELECT am.user_name,am.user_id as u_id,am.user_type_id as u_type_id,p.invoice_no,p.invoice_date,p.consignee as cust_add, e.*,p.proforma_no as pro_in_no  FROM `enquiry` as e LEFT JOIN account_master as am ON (e.user_id = am.user_id AND e.user_type_id = am.user_type_id) LEFT JOIN sales_invoice as p ON (p.email=e.email $date_inv ) WHERE e.is_delete = 0 $str $date_con ORDER BY p.invoice_id DESC");
        }     
	   $html='';
	    if($data->num_rows)
    	{
    	    foreach($data->rows as $dt)
        	{
        	    $report_data[$dt['email']][$dt['pro_in_no']]=$dt;
        	    $tax_data = $this->getTaxInvoiceData($dt['email'],$admin_user_id,$post['f_date'],$dt['pro_in_no']);
        	    $report_data[$dt['email']][$dt['pro_in_no']]['tax']=$tax_data;
            }
            $html.="<div class='table-responsive' id='excel_div'>
					    <table class='tool-row b-t text-small' width='75%' border='1'>
    						<thead>
    						    <tr><td colspan='12'>Searching Date <b>From: ".dateFormat(4, $post['f_date'])." To: ".dateFormat(4, $post['t_date'])."</b></td></tr>";
    						    if($post['emp_name']!='')
								{
									$html .= "<tr>
												<th colspan='12'>Employee Name : ".$user_name['first_name']." ".$user_name['last_name']."</th>
											</tr>";
								}
    				$html .= "<tr>
									<th>Sr. No.</th>
									<th>Inquiry. No.<br><small>Registered On</small></th>
									<th>Proforma No.<br><small>Generated On</small></th>
									<th>Invoice. No.<br><small>Generated On</small></th>
									<th>Company Name</th>
									<th>Name Of Customer</th>
									<th>Delivery Address</th>
									<th>POCountry</th>
									<th>Mo. Number</th>
								    <th>Email</th>
									<th>REMARK</th>
									<th>Paid / Order / Sample</th>";
									if($post['emp_name']=='')
									    $html.="<th>Contact Owner</th>";
						$html.="</tr>
        					</thead>
        					<tbody>";
        					    $i=0;$k=1;$cond='false';
            					foreach($report_data as $detail)
        					    {  $txt='';$pro_no=$text='';
        					        foreach($detail as $dt)
        					        { //printr($dt);
        					           $style ="style='background-color: aliceblue;'";
                                        if($i%2==0)
                                            $style ="style='background-color: antiquewhite;'";
            					        /*if($i==0)
                                            $html.="<tr><th colspan='11' style='color:red;text-align:center;'><h3>Order Received Against Inquiry</h3></th></tr>";*/
            					        $inv_no='';
            					        if($dt['tax']!='0')
                                        {   
                                            $txt = "<b>".$dt['tax']['invoice_no']."</b><br>".dateFormat(4, $dt['tax']['sales_date']);
                                            $inv_no = $dt['tax']['invoice_no'];
                                            $text='Order';
                                        }
                                        
                                        if($dt['pro_in_no']!='')
                                            $pro_no = "<b>".$dt['pro_in_no']."</b><br>".dateFormat(4, $dt['pro_date']);
                                        else if($dt['tax']!='0')
                                            $pro_no = "<b>".$dt['tax']['proforma_num']."</b>";  
                                        /*if($inv_no=='' && $cond=='false' && $i!=0)
                                        {
                                            $html.="<tr><th colspan='11' style='color:red;text-align:center;'><h3>Only Inquiry Registered</h3></th></tr>";
                                            $cond = 'true';
                                        }*/
                                        $country = $this->getCountryName($dt['country_id']);
                                        $cust_add = $dt['cust_add'];
                                        if($dt['tax']['delivery_address']!='')
                                            $cust_add = $dt['tax']['delivery_address'];
                                         
                                         if($dt['for_freight_charge']=='Yes')
                                            $text='Samples Sent';
                                         
                                            
                                       
                                         
            					         $html.="<tr ".$style.">   
            					                    <td>".$k."</td>
            					                    <td><b>".$dt['enquiry_number']."</b><br>".dateFormat(4, $dt['date_added'])."</td>
            					                    <td>".$pro_no."</td>
            					                    <td>".$txt."</td>
            					                    <td>".$dt['company_name']."</td>
            					                    <td>".$dt['first_name']." ".$dt['last_name']."</td>
            					                    <td>".$cust_add."</td>
            										<td>".$country."</td>
            										<td>".$dt['mobile_number']."</td>
            										<th>".$dt['email']."</th>
            										<td>".$dt['remark']."</td>
            										<td>".$text."</td>";
            									    if($post['emp_name']=='')
            									    {
                									    $user_name= $this->getUser($dt['user_id'],$dt['user_type_id']);
                									    $html.="<td>".$user_name['first_name']." ".$user_name['last_name']."</td>";
            									    }
                						$html.="</tr>";
            					       // die;
        					        }
            						$i++;$k++;
            					}
    			 $html .="</tbody>
					 </table>
		          </div>";
		   return $html;
    	}
    	else
    	{
    		return false;
    	}
	}
	public function getCountryName($country_id)
	{
		$sql = "SELECT country_name FROM country where status = 1 and country_code!='' and currency_code!='' and country_id = '".$country_id."' LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row['country_name'];
		}
		else
		{
			return false;
		}		
	}
	
}
?>