<?php 
class singaporeTaxCalender extends dbclass
{
	public function addTax($post)
	{
		$sql = "INSERT INTO singapore_tax_calender SET  date = '".$post['date']."',description = '".$post['description']."',remainder_date = '".$post['remainder_date']."',last_remainder_date = '".$post['last_remainder_date']."',status = '" .$post['status']. "',reminder = '".$post['reminder']."' ,user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW(),is_delete=0  ";
		
		//echo $sql;
		$data = $this->query($sql);
		$last_id=$this->getLastId();
		
		if(isset($post['img_nm']) && !empty($post['img_nm']))
		{
			foreach($post['img_nm'] as $imgnm)
				{
					//printr($imgnm);//die;
					$sql1 = "INSERT INTO singapore_image_upload SET  tax_sin_calender_id = '".$last_id."',image_name = '".$imgnm."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),is_delete=0";
					//echo $sql1;die;
					$data1 = $this->query($sql1);
					
				}
		}		
		//$post = $this->query($sql);
		
		//printr($post);
	
	}

	public function updateTax($tax_sin_calender_id,$post)
	{
		//printr($post);die;
		$sql = "UPDATE singapore_tax_calender SET date = '".$post['date']."',description = '".$post['description']."',remainder_date = '".$post['remainder_date']."',last_remainder_date = '".$post['last_remainder_date']."',status = '" .$post['status']. "',reminder = '".$post['reminder']."' , date_modify = NOW() WHERE tax_sin_calender_id = '" .(int)$tax_sin_calender_id. "'";
		//echo $sql;die;
		$data = $this->query($sql);	
		if(isset($post['img_nm']) && !empty($post['img_nm']))
		{
			foreach($post['img_nm'] as $imgnm)
			{
				if($imgnm!='')
				{
						$sql1 = "INSERT INTO singapore_image_upload SET  tax_sin_calender_id = '".$tax_sin_calender_id."',image_name = '".$imgnm."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),is_delete=0";
						$data1 = $this->query($sql1);	
				}
			}
		}	
	}
	
	public function updateStatus($status,$post){
		if($status == 0 || $status == 1){
			$sql = "UPDATE singapore_tax_calender SET status = '" .(int)$status. "',  date_modify = NOW() WHERE tax_sin_calender_id IN (" .implode(",",$post). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE singapore_tax_calender SET is_delete = '1', date_modify = NOW() WHERE tax_sin_calender_id IN (" .implode(",",$post). ")";
			$this->query($sql);
		}
	}

	public function getTotalTax(){
		$sql = "SELECT COUNT(*) as total FROM singapore_tax_calender WHERE is_delete=0";
		//echo $sql;die; 
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['total'];
		}else{
			return false;
		}
	}
	
	public function getTax($data){
		$sql = "SELECT * FROM singapore_tax_calender WHERE is_delete=0";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY tax_sin_calender_id";	
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
	
	public function getTaxRecord($tax_sin_calender_id)
	{
		$sql = "SELECT * FROM singapore_tax_calender WHERE is_delete=0 AND tax_sin_calender_id= '".$tax_sin_calender_id."'";
		
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function updateAciveStatus($tax_id,$status){
		$this->query("UPDATE singapore_tax_calender SET status = '" .(int)$status. "',  date_modify = NOW() WHERE tax_sin_calender_id='".$tax_id."'");
		//echo "UPDATE singapore_tax_calender SET status = '" .(int)$status. "',  date_modify = NOW() WHERE tax_sin_calender_id='".$tax_id."'";
	}
	
	public function updateImagestatus($tax_id,$status)
	{
		$sql = "UPDATE singapore_tax_calender SET status = '".$status."', date_modify = NOW()  WHERE tax_id = '" .(int)$tax_id. "'";
		$this->query($sql);
	}
	
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			//$sql = "SELECT u.user_name,u.first_name,u.last_name,am.user_type_id,am.user_id FROM " . DB_PREFIX ."user u, " . DB_PREFIX ."account_master am WHERE u.user_id = '".(int)$user_id."' AND am.user_id = '".(int)$user_id."' AND am.user_type_id = '".(int)$user_type_id."'";
			$sql = "SELECT u.user_name, co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			//$sql = "SELECT co.country_name,c.first_name,c.last_name,c.client_id FROM " . DB_PREFIX ."client c , country co, address addr WHERE c.client_id = '".(int)$user_id."' AND addr.user_type_id = '3' AND c.address_id = addr.address AND co.country_id=addr.country_id ";
			$sql = "SELECT co.country_name, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getTaxImgRecord($tax_sin_calender_id)
	{
		$sql = "SELECT * FROM singapore_image_upload WHERE is_delete=0 AND tax_sin_calender_id= '".$tax_sin_calender_id."'";
		
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function removeAusUploadRecord($sin_image_id)
	{
		$sql = "UPDATE singapore_image_upload SET is_delete = '1'  WHERE sin_image_id = '" .(int)$sin_image_id. "'";
		$this->query($sql);
		return 1;
	}
	
	public function getTodayReminderDatesSin(){
		date_default_timezone_set('UTC');
		$today_date = date("Y-m-d");
		//date LIKE CONCAT(YEAR(NOW()), '-', MONTH(NOW()), '%') changed by jaya on 7-7-2016
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "singapore_tax_calender` WHERE reminder='1' AND status='1' AND is_delete='0' AND MONTH(date)= MONTH(CURDATE()) AND YEAR(date)=YEAR(CURDATE()) AND (remainder_date='".$today_date."' OR last_remainder_date='".$today_date."')");
		//$sql="SELECT * FROM `" . DB_PREFIX . "singapore_tax_calender` WHERE reminder='1' AND status='1' AND is_delete='0' AND date LIKE CONCAT(YEAR(NOW()), '-', MONTH(NOW()), '%') AND (remainder_date='".$today_date."' OR last_remainder_date='".$today_date."')";
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
		
	}
	
	public function getUserPermission($menu_id)
	{
		$menu = implode('|',$menu_id);
		
		$sql = "SELECT email,user_name,user_type_id,user_id FROM " . DB_PREFIX ."account_master WHERE add_permission REGEXP '".$menu."' OR edit_permission REGEXP '".$menu."' OR delete_permission REGEXP '".$menu."' OR view_permission REGEXP '".$menu."'";
		$data = $this->query($sql);
		return $data->rows;
	}
	
	
	public function insertdata($desc,$due_date)
	{
		$sql = "INSERT INTO singapore_cron SET description='".$desc."',due_date='".$due_date."',date_added=Now()";
		//echo $sql;
		//die;
		$data=$this->query($sql);
		//return $data->rows;
		
	}
	
	public function sendEmail($tax_sin_calender_id,$adminEmail)
	{
		$sin = $this->getTaxRecord($tax_sin_calender_id);
		//printr($sin);//die;
		if($sin)
		{
			$sin_msg = '';
			$sin_msg .= '<b>Description :</b> ' .$sin['description']."<br/>";
			$sin_msg .= '<b>Due Date : </b>' .dateFormat(4,$sin['date'])."<br/>";
		
			//offline menu_id
			//$menu_id = array('148');
			
			//online menu_id
			$menu_id = array('140');
			
			$permissionData = '';
				if($menu_id >0)
					$permissionData = $this->getUserPermission($menu_id);
			
			//printr($permissionData);//die;
			if(!empty($permissionData))
			{
				foreach($permissionData as $email_id)
				{
					$toEmail[$email_id['user_name']] = $email_id['email'];	
				}
			}
			$toEmail['swisspac'] = $adminEmail;
			
			$subject = 'Singapore Tax Calender';
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(3); 
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
				//printr($toEmail);die;
			//$path = HTTP_SERVER."template/proforma_invoice.html";
			$path =HTTP_SERVER."template/proforma_invoice.html";
			$output = file_get_contents($path);  
				
			
			$search  = array('{tag:header}','{tag:details}');
					
			$tag_val = array(
					"{{taxDetail}}" => $sin_msg);
	
			if(!empty($tag_val))
			{
				$desc = $temp_desc;
				foreach($tag_val as $k=>$v)
				{	
					@$desc = str_replace(trim($k),trim($v),trim($desc));
				} 
			}
			
			$replace = array($subject,$desc);
			$message = str_replace($search, $replace, $output);
			//printr($toEmail);//die;
			//printr($message);die;
			foreach($toEmail as $toemail)
			{
				//printr($toemail);
				$response = send_email($toemail,$adminEmail,$subject,$message,'');
			
			}
		}
	}
}
?>