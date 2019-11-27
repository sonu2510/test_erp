<?php
class cylinder extends dbclass{
	
	public function addCylinder($data){
		//printr($data);
		//die;
		$size=explode('X',$data['size']);
		//printr($size);
		//die;
		$sql = "INSERT INTO `" . DB_PREFIX . "cylinder` SET order_no = '".$data['order_no']."',company_name = '".$data['company_name']."',
		width = '".$size[0]."',height = '".$size[1]."',gusset = '".$size[2]."', discription = '".$data['discription']."',cylinder_date='".$data['cylinder_date']."', vander_name = '" .$data['vander_name']."',est_receive_date = '" .$data['receive_date']."',status = '".(int)$data['status']."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),date_modify = NOW()";
		//echo $sql;die;
		$this->query($sql);
		return $this->getLastId();
	}

	
	public function updateCylinder($order_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX ."cylinder` SET order_no = '".$data['order_no']."',company_name = '".$data['company_name']."', product_name = '".$data['product_name']."', shipment_name = '".$data['shipment_name']."',width = '".$data['width']."',height = '".$data['height']."',gusset = '".$data['gusset']."', discription = '".$data['discription']."',cylinder_date='".$data['cylinder_date']."', vander_name = '" .$data['vander_name']."',est_receive_date = '" .$data['receive_date']."' , status = '" .(int)$data['status']. "',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_modify = NOW() WHERE order_id = '" .(int)$order_id. "'";
		//echo $sql;die;
		$this->query($sql);
	}
	
	public function updateCyl($order_id,$datetime)
	{
		$sql = "UPDATE `" . DB_PREFIX ."cylinder` SET status=2,receive_date='".$datetime."'  WHERE order_id = '" .(int)$order_id. "'";
		//echo $sql;
		$this->query($sql);
	}

	public function cylinderdetails($cylinder,$cond='')
	{
		$sql = "SELECT * FROM ".DB_PREFIX."cylinder WHERE order_no='".$cylinder."' ".$cond."";
		$data=$this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
		
	}
	public function removeOrderedProduct($order_id)
	{
		$sql = "DELETE FROM ".DB_PREFIX."cylinder WHERE order_id='".$order_id."'";
		$data=$this->query($sql);
	}
	
	public function getCylinderValue($order_id)
	{
		$sql = "SELECT * from cylinder where order_id='".$order_id."'"; 
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	
	public function getVanderName()
	{
		$data = $this->query("SELECT vander_first_name,vander_last_name,vander_id FROM " . DB_PREFIX . "vander WHERE is_delete = 0 AND status=1");
		return $data->rows;	
	}
	
	public function getvander($vender_id)
	{
		$data = $this->query("SELECT vander_first_name,vander_last_name FROM " . DB_PREFIX . "vander WHERE vander_id = '".$vender_id."'");
		return $data->row;	
		
	}
	public function getProductList(){
		$data = $this->query("SELECT product_name,product_id FROM " . DB_PREFIX . "product WHERE is_delete = 0 AND status=1");
		return $data->rows;	
	}
	
	
	public function getShipmentList(){
		$data = $this->query("SELECT country_name,country_id FROM " . DB_PREFIX . "country WHERE is_delete = 0 AND status=1");
		return $data->rows;	
	}

	public function getTotalCylinder($filter_data=array()){
		$sql = "SELECT COUNT(*) as total,c.*,p.product_name,p.gusset_available,a.country_name,c.order_id,c.company_name FROM cylinder as c,product as p,country as a WHERE c.product_name=p.product_id AND c.shipment_name=a.country_id AND is_delet = '0' ";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['order_no'])){
				$sql .= " AND order_no = '".$filter_data['order_no']."' ";
			}
			
			
			if(!empty($filter_data['company_name'])){
				$sql .= " AND company_name = '".$filter_data['company_name']."' ";
			}
			
			if($filter_data['product_name'] != ''){
				$sql .= " AND product_name = '".$filter_data['product_name']."' ";
			}			
		}
		//echo $sql;		
		$data = $this->query($sql);
		
		return $data->row['total'];
	}
	

	
	public function getCylinders($data,$filter_data=array()){
		$sql = "SELECT c.*,p.product_name,p.gusset_available,a.country_name,c.order_id,c.company_name FROM cylinder as c,product as p,country as a WHERE c.product_name=p.product_id AND c.shipment_name=a.country_id AND is_delet = '0' ";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['order_no'])){
				$sql .= " AND c.order_no = '".$filter_data['order_no']."' ";
			}
			
			
			if(!empty($filter_data['company_name'])){
				$sql .= " AND c.company_name = '".$filter_data['company_name']."' ";
			}
			
			if($filter_data['product_name'] != ''){
				$sql .= " AND c.product_name = '".$filter_data['product_name']."' ";
			}			
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY order_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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
	
	public function checkProductGusset($product_id){
		$data = $this->query("SELECT gusset_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['gusset_available'];	
		}else{
			return false;
		}
	}

	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "cylinder` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE order_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "cylinder` SET is_delet = '1', date_modify = NOW() WHERE order_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateCylinderStatus($order_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "cylinder` SET status = '" .(int)$status_value ."' WHERE order_id = '".$order_id ."' ";
	   $this->query($sql);	
	}
	
	public function deleteOrder($order_id){		
		$this->query("DELETE o.*,op.*,opbp.* FROM `". DB_PREFIX ."order` o INNER JOIN `". DB_PREFIX ."order_product` op ON(o.order_id=op.order_id) INNER JOIN `". DB_PREFIX ."order_product_base_price` opbp ON (op.order_product_id=opbp.order_product_id) WHERE o.order_id = '".$order_id."'");
	}
	
	public function getTotalOrders($user_type_id,$user_id,$filter_array=array(),$status){
		$sql = "SELECT COUNT(o.order_id) as total FROM (SELECT odr.* FROM `order` odr,order_product op WHERE odr.is_delete=0 AND op.order_id=odr.order_id ";
		//echo  $sql;die;
		if(!empty($filter_array)) {
			if(!empty($filter_array['order_no'])){
				$sql .= " AND odr.order_number = '".$filter_array['order_no']."'";
			}
			
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND odr.company_name LIKE '%".$filter_array['customer_name']."%'";
			}
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(odr.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}				
		}
		if($status==1)
		{
			$con='IN';
			$status_cond="AND c.status=".$status."";
		}
		elseif($status==2)
		{
			$con='IN';
			$status_cond="AND c.status=".$status."";
		}
		else
		{
			$con='NOT IN';
			$status_cond='';
		}
		$sql .=" AND (op.width,op.height,op.gusset) ".$con." (SELECT c.width,c.height,c.gusset FROM cylinder AS c WHERE c.order_no=op.order_id ".$status_cond.") GROUP BY odr.order_id) as o";
		//echo $sql;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getOrders($user_type_id,$user_id,$data,$filter_array=array(),$status){
		//echo $status;
		//die;		
		$sql = "SELECT odr.* FROM `order` odr,order_product op WHERE odr.is_delete=0 AND op.order_id=odr.order_id ";
		if(!empty($filter_array)) {
			if(!empty($filter_array['order_no'])){
				$sql .= " AND odr.order_number = '".$filter_array['order_no']."'";
			}
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND odr.company_name LIKE '%".$filter_array['customer_name']."%'";
			}
			if(!empty($filter_array['date'])){
				$sql .= " AND date(odr.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}								   
		}
		if($status==1)
		{
			$con='IN';
			$status_cond="AND c.status=".$status."";
		}
		elseif($status==2)
		{
			$con='IN';
			$status_cond="AND c.status=".$status."";
		}
		else
		{
			$con='NOT IN';
			$status_cond='';
		}
		
		$sql .=" AND (op.width,op.height,op.gusset) ".$con." (SELECT c.width,c.height,c.gusset FROM cylinder AS c WHERE c.order_no=op.order_id ".$status_cond.") GROUP BY odr.order_id";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY odr." . $data['sort'];	
		} else {
			$sql .= " ORDER BY odr.order_id";	
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
		
		//$sql = "SELECT * FROM `order` WHERE order_id IN(SELECT order_no FROM cylinder) ORDER BY `order_id` ASC ";


		//echo $sql;
		//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
/*	public function getTotalOrders($user_type_id,$user_id,$filter_array=array()){


		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "order` odr WHERE odr.is_delete=0 ";
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
				$str = ' OR ( added_by_user_id IN ('.$userEmployee.') AND added_by_user_type_id = "2" )';
			}
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "order` odr WHERE added_by_user_id = '".(int)$set_user_id."' AND added_by_user_type_id = '".(int)$set_user_type_id."' AND odr.is_delete = 0 $str ";
			
		}
		//echo  $sql;die;
		if(!empty($filter_array)) {
			if(!empty($filter_array['order_no'])){
				$sql .= " AND odr.order_number = '".$filter_array['order_no']."'";
			}
			
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND odr.customer_name LIKE '%".$filter_array['customer_name']."%'";
			}
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(odr.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}				
		}
		echo $sql;
		$data = $this->query($sql);
		return $data->row['total'];
	}*/
	
	/*public function getOrders($user_type_id,$user_id,$data,$filter_array=array()){		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT odr.* FROM `" . DB_PREFIX . "order` odr WHERE odr.is_delete=0";
		}else{
			//echo "SDada";die;
			
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
				$str = ' OR ( odr.added_by_user_id IN ('.$userEmployee.') AND odr.added_by_user_type_id = 2 )';
			}
			$sql = "SELECT odr.* FROM `" . DB_PREFIX . "order` odr WHERE odr.is_delete=0 AND odr.added_by_user_id = '".(int)$set_user_id."' AND odr.added_by_user_type_id = '".(int)$set_user_type_id."' $str";
		}
		
		
		if(!empty($filter_array)) {

			if(!empty($filter_array['order_no'])){

				$sql .= " AND odr.order_number = '".$filter_array['order_no']."'";
			}

			
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND odr.customer_name LIKE '%".$filter_array['customer_name']."%'";
			}
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(odr.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}								   
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY odr." . $data['sort'];	
		} else {
			$sql .= " ORDER BY odr.order_id";	
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
	*/
	
	public function getInprocessOrders($user_type_id,$user_id,$data,$filter_array=array(),$order_no,$cond=''){		
		//$sql = "SELECT odr.* FROM `order` odr,order_product op WHERE odr.is_delete=0 AND op.order_id=odr.order_id ";
		//printr($data);
		$sql = "SELECT * FROM `cylinder` WHERE order_no = '".$order_no."' ".$cond."";
			
		/*if($status==1)
			$con='IN';
		else
			$con='NOT IN';
		$sql .=" AND (op.width,op.height,op.gusset) ".$con." (SELECT c.width,c.height,c.gusset FROM cylinder AS c WHERE c.order_no=op.order_id AND c.status=".$status.") GROUP BY odr.order_id";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY odr." . $data['sort'];	
		} else {
			$sql .= " ORDER BY odr.order_id";	
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
		*/
		//$sql = "SELECT * FROM `order` WHERE order_id IN(SELECT order_no FROM cylinder) ORDER BY `order_id` ASC ";


		//echo $sql;
		//die;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getVendername($vander_id)
	{
		//echo $vander_id;
		$sql = "SELECT vander_first_name,vander_last_name FROM vander WHERE vander_id = '".$vander_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
	}
	
	
	public function getTotalInprocessOrders($user_type_id,$user_id,$filter_array=array(),$order_no,$cond=''){
		$sql = "SELECT COUNT(*) as total FROM `cylinder` WHERE order_no = '".$order_no."'  ".$cond."";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	
	public function getOrderProducts($order_id,$cond){
		$data=$this->query("SELECT op.*,o.*  FROM order_product AS op,standupp_swisserp.order AS o
WHERE op.order_id=o.order_id AND op.order_id='".$order_id."' AND (op.width,op.height,op.gusset) NOT IN (SELECT c.width,c.height,c.gusset FROM cylinder AS c WHERE c.order_no=op.order_id) ORDER BY `order_product_id` ASC ");

		//$data=$this->query("SELECT op.* FROM order_product as op LEFT JOIN cylinder as c ON op.order_id=c.order_no WHERE op.order_id = '".$order_id."' 		".$cond." (op.width!=c.width AND op.height!=c.height AND op.gusset!=c.gusset) ");
		//echo "SELECT op.* FROM order_product as op LEFT JOIN cylinder as c ON op.order_id=c.order_no WHERE op.order_id = '".$order_id."' 
		//.$cond." (op.width!=c.width AND op.height!=c.height AND op.gusset!=c.gusset)  ";
		//die;
		//echo "SELECT * FROM `" . DB_PREFIX ."order_product` WHERE order_id = '".$order_id."' ";
		//die;
		//$data=$this->query("SELECT * FROM order_product AS op WHERE NOT EXISTS (SELECT * FROM cylinder As c WHERE c.order_no=op.order_id AND op.order_id ='".$order_id."' AND c.status=0) ORDER BY `order_product_id` ASC ");
		//echo "SELECT * FROM order_product AS op WHERE NOT EXISTS (SELECT * FROM cylinder As c WHERE c.order_no=op.order_id AND op.order_id ='".$order_id."' AND c.status=0) ORDER BY `order_product_id` ASC ";
		//die;
		if($data->num_rows){
			return $data->rows;	
		}else{
			return false;
		}
	}
}
?>