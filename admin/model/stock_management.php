<?php 
class stock_management extends dbclass{

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
	
	public function getActiveProductName($product_name){
		$sql = "SELECT product_name FROM `" . DB_PREFIX . "product` WHERE product_id='".$product_name."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getRowColumn(){
	if($user_type_id == 1 && $user_id == 1){
		$sql = "SELECT * FROM `". DB_PREFIX . "goods_master` WHERE status='1' AND is_delete = '0'";
		}else{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			}
			$sql = "SELECT * FROM `". DB_PREFIX . "goods_master` WHERE status='1' AND is_delete = '0' AND user_id='".(int)$set_user_id ."' AND user_type_id='".(int)$set_user_type_id."' $str";
		}
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getGoodsRowColumn($goods_master_id){
		$sql = "SELECT * FROM `". DB_PREFIX . "goods_master` WHERE goods_master_id='".$goods_master_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function  addstock($post){

		$sql = "INSERT INTO stock_management SET order_no = '".$post['orderno']."',description = '".$post['description']."',product = '".$post['product']."',qty = '".$post['qty']."',row='".$post['row']."',column_name='".$post['column_name']."',goods_id='".$post['goods_id']."',
		status='".$post['status']."',is_delete='0',date_added=NOW(),date_modify=NOW()";
		$data = $this->query($sql);
		
	}
	
	public function getTotalStock($filter_data,$user_type_id,$user_id){

		//$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "stock_management` WHERE is_delete = '0'";
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "stock_management` st INNER JOIN `" . DB_PREFIX ."product` p WHERE p.product_id = st.product 
			AND st.is_delete = '0'";		
		}else{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( st.user_id IN ('.$userEmployee.') AND st.user_type_id = 2 )';
			}
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "stock_management` st INNER JOIN `" . DB_PREFIX ."product` p WHERE p.product_id = st.product 
			AND st.is_delete = '0' AND st.user_id='".(int)$set_user_id."' AND st.user_type_id='".(int)$set_user_type_id."' $str";
		}
		if(!empty($filter_data)) {
			if(!empty($filter_data['orderno'])){
				$sql .= " AND st.order_no = '".$filter_data['orderno']."'";
			}
			
			if(!empty($filter_data['description'])){
				$sql .= " AND st.description ='".$filter_data['description']."'";
			}
			
			if($filter_data['status'] !=''){
				$sql .= " AND st.status = '".$filter_data['status']."'";
			}
			
			if(!empty($filter_data['product'])){
				$sql .= " AND st.product = '".$filter_data['product']."'";
			}
		}

		$data=$this->query($sql);
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
	
	public function getStock($option,$filter_data,$user_type_id,$user_id){
	if($user_type_id == 1 && $user_id == 1){
		$sql = "SELECT st.*,p.product_name FROM " . DB_PREFIX . "stock_management as st,product as p WHERE  p.product_id = st.product AND st.is_delete = '0'";
		}else{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( st.user_id IN ('.$userEmployee.') AND st.user_type_id = 2 )';
			}
		$sql = "SELECT st.*,p.product_name FROM " . DB_PREFIX . "stock_management as st,product as p WHERE  p.product_id = st.product AND st.is_delete = '0' AND st.user_id='".$set_user_id."' ANd st.user_type_id='".$set_user_type_id."' $str";
		}
		if(!empty($filter_data)) {
			if(!empty($filter_data['orderno'])){
				$sql .= " AND st.order_no = '".$filter_data['orderno']."'";
			}
			
			if(!empty($filter_data['description'])){
				$sql .= " AND st.description ='".$filter_data['description']."'";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND st.status = '".$filter_data['status']."'";
			}
			
			if(!empty($filter_data['product'])){
				$sql .= " AND st.product = '".$filter_data['product']."'";
			}
		}
		if (isset($option['sort'])) {
			$sql .= " ORDER BY " .$option['sort'];	
		} else {
			$sql .= " ORDER BY stock_id";	
		}
		if (isset($option['order']) && ($option['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		$data=$this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else {
			return false;
		}
	}
	
	public function getStockData($stock_id){
		$sql = "SELECT * FROM stock_management WHERE stock_id = '".$stock_id."'";
		$data=$this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else {
			return false;
		}
	}
	
	public function updatestock($stock_id,$post){
		$sql = "UPDATE stock_management SET order_no = '".$post['orderno']."',description = '".$post['description']."',product = '".$post['product']."',qty = '".$post['qty']."',row='".$post['row']."',column_name='".$post['column_name']."',goods_id='".$post['goods_id']."',
		status='".$post['status']."',is_delete='0',date_modify=NOW() WHERE stock_id = '".$stock_id."'";
		$this->query($sql);
		
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "stock_management` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE stock_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "stock_management` SET is_delete = '1', date_modify = NOW() WHERE stock_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	public function updatestockStatus($stock_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "stock_management` SET status = '" .(int)$status_value ."' WHERE stock_id = '".$stock_id ."'";
	   $this->query($sql);	
	}
	
}
?>