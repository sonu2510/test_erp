<?php
class inward extends dbclass{
	
	public function addProducts($data){
		//printr($data);die;
		$sql = "INSERT INTO `" . DB_PREFIX . "product_inward` SET  roll_no='".$data['roll_no']."',inward_no = '" .$data['inward_no']. "',vender_id = '".$data['vendor_info_id']."',inward_size = '".$data['inward_size']."',qty ='".$data['qty']."' , product_category_id = '".$data['product_category_id']."', product_item_id = '".$data['product_item_id']."' ,unit_id = '".$data['unit_id']."',sec_unit_id='".$data['sec_unit_id']."',date_added = NOW(),date_modify = NOW(),user_id='".$data['user_id']."',user_type_id='2',is_delete=0,status=1,inward_date='".$data['inward_date']."',manufacutring_date='".$data['manufacutring_date']."',added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
	//	echo $sql;die;
		$this->query($sql);
	}
	
	public function updateProducts($product_inward_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "product_inward` SET roll_no='".$data['roll_no']."',inward_no= '" .$data['inward_no']. "',vender_id = '".$data['vendor_info_id']."',inward_size = '".$data['inward_size']."',qty ='".$data['qty']."' , product_category_id = '".$data['product_category_id']."', product_item_id = '".$data['product_item_id']."' ,unit_id = '".$data['unit_id']."',sec_unit_id='".$data['sec_unit_id']."',date_modify = NOW(),status=1,inward_date='".$data['inward_date']."',manufacutring_date='".$data['manufacutring_date']."',user_id='".$data['user_id']."',user_type_id='2',added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE product_inward_id='".$product_inward_id."'";
		$this->query($sql);		
	}
	
		
	public function getTotalProduct($filter_data=array()){
		$sql = "SELECT COUNT(*) as total,CONCAT(vi.vender_first_name,' ',vi.vender_last_name) as vender_name,pi.product_name FROM `" . DB_PREFIX . "product_inward` as sd, vendor_info as vi,product_item_info as pi  WHERE sd.is_delete = 0 AND sd.slt_is_delete='0' AND sd.vender_id = vi.vendor_info_id AND pi.product_item_id = sd.product_item_id";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['vendor_name'])){
				$sql .= " AND vi.vendor_info_id LIKE '%".$filter_data['vendor_name']."%' ";		
			}
			
			//if(!empty($filter_data['product_name'])){
				//$sql .= " AND sd.product_id = '".$filter_data['product_name']."' "; 	
			//}
				if(!empty($filter_data['roll_no'])){
				$sql .= " AND sd.roll_no LIKE '%".$filter_data['roll_no']."%' "; 	
			}
			
			if(!empty($filter_data['inward_no'])){
				$sql .= " AND sd.inward_no LIKE '%".$filter_data['inward_no']."%' "; 	
			}
			
			if(!empty($filter_data['inward_size'])){
				$sql .= " AND sd.inward_size LIKE '%".$filter_data['inward_size']."%' "; 	
			}
			
			if(!empty($filter_data['product_name'])){
				$sql .= " AND pi.product_name LIKE '%" .$filter_data['product_name']."%' "; 	
			}
			
			
		}
	//	echo $sql;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	
	
	public function getProducts($data,$filter_data=array()){
		$sql = "SELECT sd.*,CONCAT(vi.vender_first_name,' ',vi.vender_last_name) as vender_name,pi.product_name,um.unit FROM `" . DB_PREFIX . "product_inward` as sd, vendor_info as vi,product_item_info as pi,unit_master as um WHERE  sd.slt_is_delete='0' AND sd.is_delete = 0  AND sd.vender_id = vi.vendor_info_id AND pi.product_item_id = sd.product_item_id AND um.unit_id=sd.unit_id";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['vendor_name'])){
				$sql .= " AND vi.vendor_info_id LIKE'%".$filter_data['vendor_name']."%' ";		
			}
			if(!empty($filter_data['roll_no'])){
				$sql .= " AND sd.roll_no LIKE '%".$filter_data['roll_no']."%' "; 	
			}
			
			if(!empty($filter_data['inward_no'])){
				$sql .= " AND sd.inward_no LIKE '%".$filter_data['inward_no']."%' "; 	
			}
			
			if(!empty($filter_data['inward_size'])){
				$sql .= " AND sd.inward_size LIKE '%".$filter_data['inward_size']."%' "; 	
			}
			
			if(!empty($filter_data['product_name'])){
				$sql .= " AND pi.product_name LIKE '%".$filter_data['product_name']."%' "; 	
			}
			
		}

		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	 
		} else {
			$sql .= " ORDER BY sd.product_inward_id";	
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
	
	public function getProductItem($product_inward_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_inward` WHERE product_inward_id = '" .(int)$product_inward_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateProductStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "product_inward` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_inward_id = '".$id."' ";
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "product_inward` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_inward_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "product_inward` SET  slt_is_delete='1' AND is_delete = '1', date_modify = NOW() WHERE product_inward_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getActiveProductCategory()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_category` WHERE status='1' AND is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getVendors()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "vendor_info` WHERE status='1' AND is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getUnit(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "unit_master` WHERE status='1' AND is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getUserType(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "user_type_production` WHERE status='1' AND is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getlatestNo()
	{
		$sql = "SELECT product_inward_id FROM `" . DB_PREFIX . "product_inward` WHERE slt_is_delete='0' AND is_delete = '0' ORDER BY product_inward_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['product_inward_id'];
		}else{
			return false;
		}	
	}
	public function getProductDetail($product_item)
	{
		$sql = "SELECT * FROM  product_item_info WHERE product_name LIKE '%".strtolower($product_item)."%' AND is_delete=0 AND status = 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getProductItemInfo($proitemid)
	{
		$sql="SELECT * FROM  product_item_info WHERE product_item_id='".$proitemid."' AND is_delete=0 AND status = 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getUserDetail()
	{
		$sql="SELECT * FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type='2'";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
}
?>