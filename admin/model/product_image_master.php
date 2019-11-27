<?php
class product_image_master extends dbclass{
	
	public function getActiveProduct(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function addProductImage($post)
	{
		
		//printr($post);
		//die;
		$sql="INSERT INTO product_image SET product_id='".$post['product']."',product_image_url='".$post['image_url']."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',date_added=NOW(),date_modify=NOW(),status=1,is_delete=0";
		//echo $sql;
		//die;
		$this->query($sql);
	}
	
	public function updateProduct($product_image_id,$data){
		//printr($data);
	/*	$qry="select count(*) as total from product_image where product_id = '" .(int)$product_id. "'";
		$res=$this->query($qry);
		//printr($data);
		$cnt=$res->row['total'];
		if($cnt>0){
			//printr($cnt);*/
		$sql = "UPDATE `" . DB_PREFIX . "product_image` SET  product_image_url='".$data['image_url']."',product_id='".$data['product']."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',
		user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',date_added=NOW(),date_modify = NOW() WHERE product_image_id = '" .(int)$product_image_id. "'";
		//echo $sql;die;
		$this->query($sql);
		/*}
		else{
			
		$sql="INSERT INTO " . DB_PREFIX ."product_image SET product_image_url='".$data['image_url']."',product_id='".$product_id."', date_modify = NOW()";	
		$this->query($sql);	
		}*/
	}
	
	
	public function getProduct($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE product_id = '" .(int)$product_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getProductimg($product_image_id){
		$sql = "SELECT pi.*,p.product_name FROM " . DB_PREFIX . "product_image as pi,product as p WHERE pi.product_id=p.product_id AND pi.product_image_id = '" .(int)$product_image_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getzipper($product_zipper_id) 
	{
		$sql = "select * from " . DB_PREFIX ."product_zipper where product_zipper_id = '".$product_zipper_id."'";
		// $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	

	public function getTotalProductImage($user_type_id,$user_id){
	
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "product as p,product_image as pi WHERE pi.product_id=p.product_id AND pi.is_delete = '0'";
		$data = $this->query($sql);
		return $data->row['total'];
		
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
	
	public function getAllProducts(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE is_delete = '0'";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		
	}
	public function updateStatus($status,$data)
	{
		if($status == 0 || $status == 1){
			$sql = "UPDATE " . DB_PREFIX . "product_image SET status = '" .(int)$status. "',date_modify = NOW() WHERE product_image_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE " . DB_PREFIX . "product_image SET is_delete = '1', date_modify = NOW() WHERE product_image_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}	
	public function updateImagestatus($product_image_id,$status_value)
	{
		$sql = "UPDATE " . DB_PREFIX . "product_image SET status = '".$status_value."', date_modify = NOW()  WHERE product_image_id = '" .(int)$product_image_id. "'";
		$this->query($sql);
	}
	public function getProductsImage($data,$user_type_id,$user_id){
		$sql = "SELECT pi.*,p.product_name FROM " . DB_PREFIX . "product as p,product_image as pi WHERE pi.product_id=p.product_id AND pi.is_delete = '0'";
			
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_name";	
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
		//printr($data);die;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductZippers()
	{
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductSpout()
	{
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductAccessorie()
	{
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveMake()
	{
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY make_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
		
		
}
?>