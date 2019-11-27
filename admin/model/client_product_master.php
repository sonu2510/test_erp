<?php
class client_product_master extends dbclass {

		public function getvalue($option,$filter_data=array(),$product_type_id)
		{
			$sql = "SELECT p.product_name,cpm.* FROM product as p,client_product_master as cpm WHERE cpm.product_id =p.product_id AND cpm.is_delete=0 AND cpm.product_type_id = '".$product_type_id."'";
		
			if(!empty($filter_data)){
				
				if(!empty($filter_data['filter_desc'])){
					$sql .= " AND cpm.product_desc LIKE '%".$filter_data['filter_desc']."%' ";		
				}
				
				if($filter_data['filter_name'] != ''){
					$sql .= " AND cpm.product_id = '".$filter_data['filter_name']."' ";
				}
			}
			
			if (isset($data['sort'])) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY client_product_id";	
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
	
	public function getcount($filter_data=array(),$product_type_id)
	{
		
			$sql = "SELECT COUNT(*) as total,p.product_name,cpm.* FROM product as p,client_product_master as cpm WHERE cpm.product_id =p.product_id AND cpm.is_delete=0 AND cpm.product_type_id = '".$product_type_id."'" ;
			
			if(!empty($filter_data)){
				
				if(!empty($filter_data['filter_desc'])){
					$sql .= " AND cpm.product_desc LIKE '%".$filter_data['filter_desc']."%' ";		
				}
				
				if($filter_data['filter_name'] != ''){
					$sql .= " AND cpm.product_id = '".$filter_data['filter_name']."' ";
				}
			}
			
			$data = $this->query($sql);
			return $data->row['total'];
	}
		
	public function getProduct($client_product_id){
		
		$sql = "SELECT * FROM client_product_master WHERE client_product_id = '" .(int)$client_product_id. "'";
	
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	}
		
	public function addproduct($data)
	{//,valve = '".$data['valve']."'
		
		$sql = "INSERT INTO client_product_master SET 	product_type_id = '".$data['product_type_id']."',product_desc = '".$data['product_desc']."',product_id ='".$data['product']."',zipper_id ='".$data['zipper']."',spout_id = '".$data['spout']."',accessories_id = '".$data['accessorie']."',status = '".$data['status']."', date_added = NOW()";
		
		$data_product = $this->query($sql);
		$client_product_id = $this->getLastId();
		
		if(isset($data['size']) && !empty($data['size']))
		{
			foreach($data['size'] as $size){
				foreach($size as $key=>$size_data){
					foreach($size_data['qty_master'] as $qty){
						$sql2 = "INSERT INTO client_product_quantity_master SET price = '".$qty['price']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),is_delete=0,client_product_id='".$client_product_id."',client_qty_id = '".$qty['client_qty_id']."',product_size_id = '".$key."' ";
						$data2 = $this->query($sql2);
					}
				}
			}
		}	
	}
	
	public function updateproduct($client_product_id,$data)
	{
	    $sql = "UPDATE client_product_master SET product_desc = '".$data['product_desc']."',product_id ='".$data['product']."',zipper_id ='".$data['zipper']."',spout_id = '".$data['spout']."',accessories_id = '".$data['accessorie']."',status = '".$data['status']."',date_modify = NOW() where client_product_id='".$client_product_id."'";
		//echo $sql.'<br>';
		$data_product = $this->query($sql);
		if(isset($data['size']) && !empty($data['size']))
		{
			foreach($data['size'] as $size){
				foreach($size as $key=>$size_data){
					foreach($size_data['qty_master'] as $UpdateQty){
						 if(isset($UpdateQty['client_pro_qty_id']) && $UpdateQty['client_pro_qty_id']!=''){
							
							$sql = "UPDATE client_product_quantity_master SET price = '".$UpdateQty['price']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_modify = NOW() WHERE client_qty_id = '" .$UpdateQty['client_qty_id']. "' AND client_product_id='".$client_product_id."' AND product_size_id = '" .$UpdateQty['product_size_id']. "'";
							$this->query($sql);
							//echo $sql.'<br>';
						 }
						 else{
						 
							$sql = "INSERT INTO client_product_quantity_master SET price = '".$UpdateQty['price']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),date_modify = NOW(),is_delete=0 ,client_product_id='".$client_product_id."', client_qty_id = '" .$UpdateQty['client_qty_id']. "',product_size_id = '".$key."'";
							$this->query($sql);
							//echo $sql.'<br>';
						 }
					
					}
				}			
			}		
		}	
		
	}
	
		public function UpdateStatus($client_product_id,$status){
		$sql = "UPDATE client_product_master SET status = '".$status."' WHERE client_product_id = '".$client_product_id."'";	
		//echo $sql;
		$data = $this->query($sql);
	}
	
		public function UpdateProductStatus($status,$data){
	
		if($status == 0 || $status == 1){
			$sql = "UPDATE client_product_master SET status = '" .(int)$status. "',  date_modify = NOW() WHERE client_product_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE client_product_master SET is_delete = '1', date_modify = NOW() WHERE client_product_id IN (" .implode(",",$data). ")";
			$this->query($sql);
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

		public function getActiveProduct(){
			$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' ORDER BY product_name ASC";
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
	
		public function getActiveProductAccessorie(){
			$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
	}
	// for searching
		public function getActiveProductSearch()
		{
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
	
	// for quantity master
		 public function getQtyPrice()
		 {
		$sql = "SELECT * FROM client_quantity_master WHERE  is_delete = '0' ORDER BY from_qty ASC";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	// kinjal 23-12-2014
	 public function getclientProQtyPrice($client_product_id,$size_master_id){
		//$sql = "SELECT * FROM client_product_quantity_master WHERE  is_delete = '0' AND client_product_id='".$client_product_id."' ORDER BY from_qty ASC";
		$sql = "SELECT cpq.*,cq.from_qty,cq.to_qty,cq.client_qty_id FROM client_quantity_master as cq LEFT JOIN client_product_quantity_master as cpq ON cq.client_qty_id = cpq.client_qty_id AND cpq.client_product_id='".$client_product_id."' AND cpq.product_size_id	='".$size_master_id."' UNION SELECT cpq.*,cq.from_qty,cq.to_qty,cq.client_qty_id FROM client_quantity_master as cq RIGHT JOIN client_product_quantity_master as cpq ON cq.client_qty_id = cpq.client_qty_id  AND cpq.client_product_id='".$client_product_id."' AND cpq.product_size_id	='".$size_master_id."' WHERE cpq.client_product_id='".$client_product_id."' AND cpq.product_size_id	='".$size_master_id."' AND from_qty!='NULL' AND to_qty!='NULL' ORDER BY from_qty ASC";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getclientRemaingQtyPrice(){
		//$sql = "SELECT cpq.*,cq.* FROM client_product_quantity_master as cpq, client_quantity_master as cq WHERE  cq.is_delete = '0' AND cq.to_qty!=cpq.to_qty AND cq.from_qty!=cpq.from_qty ORDER BY from_qty ASC";
		$sql = "SELECT * FROM client_quantity_master as cq WHERE NOT EXISTS ( SELECT cpq.from_qty,cpq.to_qty FROM client_product_quantity_master cpq WHERE cq.from_qty = cpq.from_qty AND cq.to_qty = cpq.to_qty)";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function deleteQty($id){
	
		$this->query("DELETE FROM " . DB_PREFIX . "client_product_quantity_master WHERE client_qty_id = '".(int)$id."' AND client_product_id=");	
	}
	
	public function getProductSize($product_id,$zipper_id)
	{
		$sql  = "SELECT * FROM size_master WHERE product_id = '".$product_id."' AND product_zipper_id='".$zipper_id."'"; 
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
}
?>