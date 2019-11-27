<?php
class vendorinfo extends dbclass{
	public function getVenderValue($vendor_info_id)
	{
		
		$sql = "SELECT * from vendor_info where vendor_info_id='".$vendor_info_id."'"; 
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	
	

	public function getTotalVender($filter_array=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "vendor_info` WHERE is_delete = '0' ";
			//echo $sql;die;
		if(!empty($filter_array)){
			
			if(!empty($filter_array['name'])){
				$sql .= " AND CONCAT(vender_first_name,' ',vender_last_name) LIKE '%".$this->escape($filter_array['name'])."%'";
			}
			
			if(!empty($filter_array['email_id'])){
				$sql .= " AND email_id LIKE '%".$filter_array['email_id']."%'";
			}
			//echo $filter_array['status']."===";die;
			if($filter_array['status']!=''){								
				$sql .= " AND status = '".$filter_array['status']."' ";
			}
						
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getVenders($data = array(),$filter_array=array()){
				
		$sql = "SELECT CONCAT(vender_first_name,' ',vender_last_name) as name,email_id,status,vendor_info_id FROM `" . DB_PREFIX . "vendor_info` WHERE is_delete= '0'  ";
		//echo $sql;die;

		if(!empty($filter_array)){
			//echo $filter_array;die;
			//echo $filter_array['email_id']."===";die;
			if(!empty($filter_array['email_id'])){
				$sql .= " AND email_id LIKE '%".$filter_array['email_id']."%'";
			}
			
			if(!empty($filter_array['name'])){
				$sql .= " HAVING name LIKE '%".$this->escape($filter_array['name'])."%'";
			}
			
			//echo $filter_array['status']."===";die;
			
			if($filter_array['status']!=''){								
				$sql .= " AND status = '".$filter_array['status']."' ";
			}			
		}
		
		if(isset($data['sort'])){
			$sql .= " ORDER BY " . $data['sort'];
			} else {
			$sql .= " ORDER BY vendor_info_id";	
		
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
	
	public function getCountryList(){
		
		$data = $this->query("SELECT country_name,country_id FROM " . DB_PREFIX . "country WHERE is_delete = 0 AND status=1");
		return $data->rows;	
		
	}
	
	public function addVender($data){
		//printr($data);die;
		
		//$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		$sql = "INSERT INTO `" . DB_PREFIX . "vendor_info` SET 	company_name = '" .$data['companyname']. "',vender_first_name = '" .$data['firstname']. "', vender_last_name = '" .$data['lastname']. "', address = '" .$data['address']. "', email_id = '" .$data['email_id']. "', contact_no = '" .$data['contact_no'] . "',   country = '" . $data['country_name'] . "',state = '" .$data['state']. "',city = '" .$data['city']. "',postcode = '" .$data['postcode']. "',fax_no = '" .(int)$data['faxno']. "',status = '" .(int)$data['status']. "',remark = '" .$data['remark']. "',bank_detail = '" .$data['bankdetail']. "',date_added = NOW()";
		//echo $sql;die;
		$this->query($sql);
		return $this->getLastId();
 	}
	
	public function updateVender($vendor_info_id,$data){
		//printr($data);die;
	
	$sql = "UPDATE `" . DB_PREFIX . "vendor_info` SET company_name = '" .$data['companyname']. "',vender_first_name = '" .$data['firstname']."',vender_last_name = '" .$data['lastname']. "', address = '" .$data['address']. "', email_id = '" .$data['email_id']. "', contact_no = '" .$data['contact_no']. "',   country = '" . $data['country_name'] . "',state = '" .$data['state']. "',city = '" .$data['city']. "',postcode = '" .$data['postcode']. "',fax_no = '" .$data['faxno']. "',status = '" .(int)$data['status']. "',remark = '" .$data['remark']. "',bank_detail = '" .$data['bankdetail']. "',date_modify = NOW() WHERE vendor_info_id = '" .(int)$vendor_info_id. "'";
		//echo $sql;die;
		$this->query($sql);
		
 	}
	
	public function deleteVender($vendor_info_id){
	//	echo $vendor_info_id;die;
		//echo "delete";die;
		$sql="Select p.vender_id from purchase_indent as p,purchase_indent_items as pi where p.vender_id='".$vendor_info_id."' AND   pi.indent_id=p.indent_id AND pi.status=0";
		$dat=$this->query($sql);
		//printr($dat);die;
		if(!isset($dat->row['vendor_info_id']) && empty($dat->row['vendor_info_id']))
		{	//echo "hii";die;
			$sql = "UPDATE `" . DB_PREFIX . "vendor_info` SET is_delete=1 WHERE vendor_info_id='".$vendor_info_id."'";
			$this->query($sql);
		}
		else
		{	
			echo '<script type="text/javascript">alert("Sorry you can not Delete This Record")</script>';
			
		}
	}
	public function updateStatus($status,$data)
	{	//echo "update";die;
		
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "vendor_info` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE vendor_info_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "vendor_info` SET is_delete = '1', date_modify = NOW() WHERE vendor_info_id IN (" .implode(",",$data). ")";
			$this->query($sql);
	
	}
	
	}
	
	//end 
}
?>