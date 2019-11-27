<?php
class associate extends dbclass{
	
	public function addAssociate($data){
		
		//modified by jayashree
		
		$sql = "INSERT INTO " . DB_PREFIX . "associate SET company_name='".$data['company_name']."',first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', telephone = '" . $data['telephone'] . "', user_name = '".$data['user_name']."', ip = '" . $_SERVER['REMOTE_ADDR'] . "', gres='".$data['gres']."',gres_air='" . $data['gres_air'] . "',gres_sea='" . $data['gres_sea'] . "', valve_price='".$data['valve_price']."', stock_valve_price='".$data['stock_valve_price']."', allow_currency = '".(int)$data['allow_currency']."',default_curr='".$data['default_curr']."',currency_val = '".$data['currval']."',product_rate='".$data['p_rate']."',cylinder_rate='".$data['c_rate']."',status = '".(int)$data['status']."', 
		email_signature = '".$data['email_signature']."', approved = '1', date_added = NOW()";
		$this->query($sql);
		$associate_id = $this->getLastId();
		
		//account master data
		$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		$sql1 = "INSERT INTO `" . DB_PREFIX . "account_master` SET user_type_id = '5', user_id = '" .(int)$associate_id. "', user_name = '" .$data['user_name']. "', salt = '" . $salt . "', password = '" . sha1($salt . sha1($salt . sha1($data['password']))) . "' , password_text = '" .$data['password']. "', email = '" .$data['email']. "', date_added = NOW()";
		$this->query($sql1);
		
		//insert address
		$this->query("INSERT INTO " . DB_PREFIX . "address SET user_type_id = '5', user_id = '" . (int)$associate_id . "', first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', company = '', address = '" . $data['address'] . "', city = '" . $data['city'] . "', state = '" . $data['state'] . "', postcode = '" . $data['postcode'] . "', country_id = '" . (int)$data['country_id'] . "'");
		
		$address_id = $this->getLastId();

		$this->query("UPDATE " . DB_PREFIX . "associate SET address_id = '" . (int)$address_id . "' WHERE associate_id = '" . (int)$associate_id . "'");
		return $associate_id;
	}
	
	public function updateAssociate($associate_id,$data){
		
		//modified by jayashree
		
		$sql = "UPDATE `" . DB_PREFIX . "associate` a, `" . DB_PREFIX . "account_master` am SET a.company_name='".$data['company_name']."',a.first_name = '" . $data['first_name'] . "', a.last_name = '" . $data['last_name'] . "', a.telephone = '" . $data['telephone'] . "', a.user_name = '".$data['user_name']."', am.user_name = '".$data['user_name']."', am.email = '" . $data['email'] . "', a.gres='".$data['gres']."',a.gres_air='" . $data['gres_air'] . "',a.gres_sea='" . $data['gres_sea'] . "', a.valve_price='".$data['valve_price']."', a.stock_valve_price='".$data['stock_valve_price']."', a.default_curr='".$data['default_curr']."',a.currency_val = '".$data['currval']."',a.product_rate='".$data['p_rate']."',a.cylinder_rate='".$data['c_rate']."',a.allow_currency = '".(int)$data['allow_currency']."', a.status = '".(int)$data['status']."', a.email_signature = '".$data['email_signature']."', a.date_modify = NOW(), am.date_modify = NOW() WHERE a.associate_id = '".(int)$associate_id."' AND am.user_type_id = '5' AND am.user_id = '".(int)$associate_id."'";
		//echo $sql;die;
		$this->query($sql);
		
		$associate = $this->getAssociate($associate_id);
		if (isset($data['password']) && !empty($data['password'])) {
			$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		//	echo "UPDATE `" . DB_PREFIX . "account_master` SET salt = '" . $salt . "', password = '" . sha1($salt . sha1($salt . sha1($data['password']))) . "' , password_text = '" .$data['password']. "', date_modify = NOW() WHERE user_type_id = '5' AND user_id = '" .(int)$associate['associate_id']. "' AND user_name = '".$associate['user_name']."'";die;
			$this->query("UPDATE `" . DB_PREFIX . "account_master` SET salt = '" . $salt . "', password = '" . sha1($salt . sha1($salt . sha1($data['password']))) . "' , password_text = '" .$data['password']. "', date_modify = NOW() WHERE user_type_id = '5' AND user_id = '" .(int)$associate['associate_id']. "' AND user_name = '".$associate['user_name']."'");
		}
		if($associate['address_id'] > 0){
			$this->query("UPDATE " . DB_PREFIX . "address SET first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', company = '', address = '" . $data['address'] . "', city = '" . $data['city'] . "', postcode = '" . $data['postcode'] . "', state = '" . $data['state'] . "', country_id = '" . (int)$data['country_id'] . "', date_modify = NOW() WHERE user_type_id = '5' AND user_id = '".(int)$associate['associate_id']."' AND address_id = '".(int)$associate['address_id']."'");
		}else{
			$this->query("INSERT INTO " . DB_PREFIX . "address SET user_type_id = '5', user_id = '" . (int)$associate['associate_id'] . "', first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', company = '', address = '" . $data['address'] . "', city = '" . $data['city'] . "', state = '" . $data['state'] . "', postcode = '" . $data['postcode'] . "', country_id = '" . (int)$data['country_id'] . "'");
			$address_id = $this->getLastId();
			$this->query("UPDATE " . DB_PREFIX . "associate SET address_id = '" . (int)$address_id . "' WHERE associate_id = '" . (int)$associate['associate_id'] . "'");
		}
		
	}
	
	public function getAssociate($associate_id){
		$sql = "SELECT *, a.first_name as afirst_name, a.last_name as alast_name FROM " . DB_PREFIX . "associate a LEFT JOIN " . DB_PREFIX . "address addr ON (a.address_id = addr.address_id) LEFT JOIN " . DB_PREFIX . "account_master am ON(am.user_name=a.user_name) WHERE am.user_type_id = '5' AND a.associate_id = '" .(int)$associate_id. "' AND am.user_id =a.associate_id ";
		//echo $sql;die;
		$data = $this->query($sql);
		//printr($data->row);die;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	//modified by jayashree
	
	public function getdefaultcurrency()
	{
		$sql = "SELECT * FROM country where status = 1 and country_code!='' and currency_code!='' group by currency_code";
		//echo $sql;
		//die;
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
	//end 
	
	public function getTotalAssociate($filter_array=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "associate` a LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=a.user_name AND am.user_id = a.associate_id AND am.user_type_id = '5' WHERE a.is_delete = '0'";

		if(!empty($filter_array)){
						
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}
			
			if($filter_array['status'] != ''){
				$sql .= " AND a.status = '".$filter_array['status']."' ";
			}
			
			if(!empty($filter_array['name'])){			
				$sql .= " AND CONCAT(first_name,' ',last_name) LIKE '%".$this->escape($filter_array['name'])."%'";			
			}
							
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getAssociates($data = array(),$filter_array=array()){
		//$sql = "SELECT *,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "client`";
		$sql = "SELECT *,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "associate` a LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=a.user_name AND am.user_id = a.associate_id AND am.user_type_id = '5' WHERE a.is_delete = '0'";
		//echo $sql;die;
		
		if(!empty($filter_array)){
			
			if(!empty($filter_array['email'])){
				$sql .= "WHERE am.email LIKE '%".$filter_array['email']."%'";
			}
			
			if($filter_array['status'] != ''){
				$sql .= " AND a.status = '".$filter_array['status']."' ";
			}
						
			if(!empty($filter_array['name'])){
				$sql .= " HAVING name LIKE '%".$this->escape($filter_array['name'])."%'";
			}
							
		}
				
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY first_name";	
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
	
	//upload Logo image 
	public function uploadLogoImage($user_id,$data){
		//printr($data);die;
		//echo "Pending for start new work priority";die;
		
		if(isset($data['name']) && $data['name'] != '' && $data['error'] == 0){
			
			$validateImageExt = validateUploadImage($data);
			
			if($validateImageExt){
				require_once(DIR_SYSTEM . 'library/resize-class.php');
				$upload_path = DIR_UPLOAD.'admin/logo/';
				
				$exist = $this->query("SELECT logo FROM " . DB_PREFIX . "associate WHERE associate_id = '".(int)$user_id."'");
				if($exist->row['logo'] != '' && file_exists($upload_path.$exist->row['logo'])){
					unlink($upload_path.$exist->row['logo']);
					unlink($upload_path.'50_'.$exist->row['logo']);
					unlink($upload_path.'100_'.$exist->row['logo']);
					unlink($upload_path.'200_'.$exist->row['logo']);
				}
				
				$file_name = $data["name"];
				$filetemp = $data["tmp_name"];
				$upload_image_path = $upload_path."/".$file_name;
				
				if(file_exists($upload_image_path)) 
				{
					$file_name = rand().'_'.$file_name;
					
					$widthArray = array(200,100,50); //You can change dimension here.
					foreach($widthArray as $newwidth)
					{
						compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
					}
					
					/*uploadfile($filetemp,$file_name,$upload_path);
					list($width, $height, $type, $attr) = getimagesize($upload_path.$file_name);
					
					if($width >= 36){ $resizeWidth = 36; }else{ $resizeWidth = $width;}
					if($height >= 36){ $resizeHeight = 36; }else{ $resizeHeight = $width;}
					$resizeObj = new resize($upload_path.$file_name);		
					$resizeObj -> resizeImage($resizeWidth, $resizeHeight, 'exact');
					// *** 3) Save image
					$resizeObj -> saveImage($upload_path.'36x36_'.$file_name, 100);
					
					if($width >= 200){ $resizeWidth = 200; }else{ $resizeWidth = $width;}
					if($height >= 200){ $resizeHeight = 200; }else{ $resizeHeight = $width;}
					$resizeObj = new resize($upload_path.$file_name);		
					$resizeObj -> resizeImage($resizeWidth, $resizeHeight, 'exact');
					// *** 3) Save image
					$resizeObj -> saveImage($upload_path.'200x200_'.$file_name, 100);*/
					
				}else{
					
					$widthArray = array(200,100,50); //You can change dimension here.
					foreach($widthArray as $newwidth)
					{
						compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
					}
					
					/*uploadfile($filetemp,$file_name,$upload_path);
					list($width, $height, $type, $attr) = getimagesize($upload_path.$file_name);
					
					if($width >= 36){ $resizeWidth = 36; }else{ $resizeWidth = $width;}
					if($height >= 36){ $resizeHeight = 36; }else{ $resizeHeight = $width;}
					$resizeObj = new resize($upload_path.$file_name);		
					$resizeObj -> resizeImage($resizeWidth, $resizeHeight, 'exact');
					// *** 3) Save image
					$resizeObj -> saveImage($upload_path.'36x36_'.$file_name, 100);
					
					if($width >= 200){ $resizeWidth = 200; }else{ $resizeWidth = $width;}
					if($height >= 200){ $resizeHeight = 200; }else{ $resizeHeight = $width;}
					$resizeObj = new resize($upload_path.$file_name);		
					$resizeObj -> resizeImage($resizeWidth, $resizeHeight, 'exact');
					// *** 3) Save image
					$resizeObj -> saveImage($upload_path.'200x200_'.$file_name, 100);*/
				}
				
				if($file_name){
					$this->query("UPDATE " . DB_PREFIX . "associate SET logo = '" . $file_name . "' WHERE associate_id = '" .(int)$user_id. "'");
				}
			}
		}
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "associate` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE associate_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "associate` SET is_delete = '1', date_modify = NOW() WHERE associate_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}	
}
?>