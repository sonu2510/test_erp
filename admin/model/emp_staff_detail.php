<?php
class emp_staff_detail extends dbclass{
		
	public function addpersonal_info($data){
		$dob = date('Y-m-d', strtotime( $data['dob'] ));
		$doj = date('Y-m-d', strtotime( $data['doj'] ));
		$dolev = date('Y-m-d', strtotime( $data['dolev'] ));
		
		$sql = "INSERT INTO `" . DB_PREFIX . "emp_staff_detail` SET staff_group_id='".$data['staff_group_id']."',series ='".$data['series']."',fname = '" .$data['fname']. "',mname= '" .$data['mname']. "',lname = '" .$data['lname']. "',gen= '" .$data['gen']. "',	radio_m = '" .$data['radio_m']. "',dob = '" .$dob. "',pno = '" .$data['pno']. "',mno = '" .$data['mno']. "',addr = '" .$data['addr']. "',corr_addr = '" .$data['corr_addr']. "',city = '" .$data['city']. "',state = '" .$data['state']. "',country = '" .$data['country']. "',pin = '" .$data['pin']. "',email = '" .$data['email']. "',c_name = '" .$data['c_name']. "',pri_no = '" .$data['pri_no']. "',alter_no1 = '" .$data['alter_no1']. "',adhar_card_no = '" .$data['adhar_card_no']. "',adhar_card_nm = '" .$data['adhar_card_nm']. "',water_id_card_no = '" .$data['water_id_card_no']. "',water_id_card_nm = '" .$data['water_id_card_nm']. "',pan_card_no = '" .$data['pan_card_no']. "',pan_card_nm = '" .$data['pan_card_nm']. "',bank_acc_no = '" .$data['bank_acc_no']. "',bank_acc_nm = '" .$data['bank_acc_nm']. "',ifsc_code = '" .$data['ifsc_code']. "',micr_code = '" .$data['micr_code']. "',branch_addr = '" .$data['branch_addr']. "',doj = '" .$doj. "',	dolev = '" .$dolev. "',salary = '" .$data['salary']. "',relation='".$data['relation']."',radio_pf = '" .$data['radio_pf']. "', status = '1', is_delete = '0', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function getTotalDepartment($filter_array=array(),$status){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "emp_staff_detail` as es, staff_group_details as sg WHERE es.is_delete = '0' AND es.staff_group_id=sg.staff_group_id".$status;
		
		if(!empty($filter_array)){						
			if(!empty($filter_array['filter_name'])){
				$sql .= " AND CONCAT(es.fname,' ',es.mname,' ',es.lname) LIKE '%".$this->escape($filter_array['filter_name'])."%'";
			}				
			if($filter_array['filter_taskname'] != ''){
				$sql .= " AND es.staff_group_id = '".$filter_array['filter_taskname']."' ";
			}												
		}
		
		
		//echo $sql.'<br>';
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getDepartments($data,$filter_array=array(),$status){
		$sql = "SELECT es.*,sg.staff_group_name,sg.staff_group_id FROM emp_staff_detail as es, staff_group_details as sg WHERE es.is_delete = '0' AND es.staff_group_id=sg.staff_group_id".$status;
		
		if(!empty($filter_array)){						
			if(!empty($filter_array['filter_name'])){
				$sql .= " AND CONCAT(es.fname,' ',es.mname,' ',es.lname) LIKE '%".$this->escape($filter_array['filter_name'])."%'";
			}				
			if($filter_array['filter_taskname'] != ''){
				$sql .= " AND es.staff_group_id = '".$filter_array['filter_taskname']."' ";
			}												
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY es.doj";	
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
	public function getSupplier(){
		$sql = "SELECT * FROM staff_group_details where is_delete=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getcountry(){
		$sql = "SELECT * FROM country where is_delete=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getpersonalinfo($emp_staff_detail_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "emp_staff_detail` WHERE emp_staff_detail_id = '" .(int)$emp_staff_detail_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updatepersonlainfo($info_id,$data){
		$dob = date('Y-m-d', strtotime( $data['dob'] ));
		$doj = date('Y-m-d', strtotime( $data['doj'] ));
		$dolev = date('Y-m-d', strtotime( $data['dolev'] ));
		$sql = "UPDATE `" . DB_PREFIX . "emp_staff_detail` SET staff_group_id='".$data['staff_group_id']."',series ='".$data['series']."',fname = '" .$data['fname']. "',mname= '" .$data['mname']. "',lname = '" .$data['lname']. "',gen= '" .$data['gen']. "',	radio_m = '" .$data['radio_m']. "',dob = '" .$dob. "',pno = '" .$data['pno']. "',mno = '" .$data['mno']. "',addr = '" .$data['addr']. "',corr_addr = '" .$data['corr_addr']. "',city = '" .$data['city']. "',state = '" .$data['state']. "',country = '" .$data['country']. "',pin = '" .$data['pin']. "',email = '" .$data['email']. "',c_name = '" .$data['c_name']. "',pri_no = '" .$data['pri_no']. "',alter_no1 = '" .$data['alter_no1']. "',adhar_card_no = '" .$data['adhar_card_no']. "',adhar_card_nm = '" .$data['adhar_card_nm']. "',water_id_card_no = '" .$data['water_id_card_no']. "',water_id_card_nm = '" .$data['water_id_card_nm']. "',pan_card_no = '" .$data['pan_card_no']. "',pan_card_nm = '" .$data['pan_card_nm']. "',bank_acc_no = '" .$data['bank_acc_no']. "',bank_acc_nm = '" .$data['bank_acc_nm']. "',ifsc_code = '" .$data['ifsc_code']. "',micr_code = '" .$data['micr_code']. "',branch_addr = '" .$data['branch_addr']. "',doj = '" .$doj. "',	dolev = '" .$dolev. "',salary = '" .$data['salary']. "',relation='".$data['relation']."',radio_pf = '" .$data['radio_pf']. "',status = '".$data['status']."',date_modify = NOW() WHERE emp_staff_detail_id = '" .(int)$info_id. "'";
		$this->query($sql);
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "emp_staff_detail` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE emp_staff_detail_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "emp_staff_detail` SET is_delete = '1', date_modify = NOW() WHERE emp_staff_detail_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	//upload Logo image 
	public function uploadLogoImage($insert_id,$data){
		if(isset($data['name']) && $data['name'] != '' && $data['error'] == 0){			
			$validateImageExt = validateUploadImage($data);			
			if($validateImageExt){	
				require_once(DIR_SYSTEM . 'library/resize-class.php');
				$upload_path = DIR_UPLOAD.'admin/profile/';				
				$exist = $this->query("SELECT profile FROM " . DB_PREFIX . "emp_staff_detail WHERE emp_staff_detail_id = '".(int)$insert_id."'");
				if($exist->row['profile'] != '' && file_exists($upload_path.$exist->row['profile'])){
					unlink($upload_path.$exist->row['profile']);
					unlink($upload_path.'50_'.$exist->row['profile']);
					unlink($upload_path.'100_'.$exist->row['profile']);
					unlink($upload_path.'200_'.$exist->row['profile']);
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
					
				}else{
					
					$widthArray = array(200,100,50); //You can change dimension here.
					foreach($widthArray as $newwidth)
					{
						compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
					}
					
				}				
				if($file_name){
					$this->query("UPDATE " . DB_PREFIX . "emp_staff_detail SET profile = '" . $file_name . "' WHERE emp_staff_detail_id = '" .(int)$insert_id. "'");
				}
			}
		}
	}
}
?>