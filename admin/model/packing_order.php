<?php	class packing_order extends dbclass{
		
	public function get_packing_order_detail($filter_data = array(),$data=array(),$gen_pro_as=0)
	{
		/*if (!empty($filter_data['product_code'])) {
			$sql = " SELECT *from packing_order as p , packing_order_product_code_wise as pa , product_code as pc  WHERE p.is_delete = '0' AND p.packing_order_id = pa.packing_order_id AND pc.product_code_id = pa.product_code_id AND pc.product_code LIKE '%".$filter_data['product_code']."%'";				
		}
		else
			$sql = "SELECT *from packing_order as p WHERE p.is_delete = '0' ";*/
		
		if($gen_pro_as>0)
		{//clifton(1) //swisspac(2)
		    if(!empty($filter_data['product_code'])) {
				$sql = " SELECT p.payment_status as pay_status,p.*,pp.* from packing_order as p , packing_order_product_code_wise as pa , product_code as pc,proforma_product_code_wise as pp  WHERE p.is_delete = '0' AND p.packing_order_id = pa.packing_order_id AND pc.product_code_id = pa.product_code_id AND pc.product_code LIKE '%".$filter_data['product_code']."%'  AND pp.pro_in_no=p.pro_in_no AND pp.gen_pro_as=".$gen_pro_as."";				
			}
			else
				$sql = "SELECT p.payment_status as pay_status,p.* from packing_order as p WHERE p.is_delete = '0' AND p.gen_pro_as=".$gen_pro_as."";
		}
		else
		{//common
		    if (!empty($filter_data['product_code'])) {
				$sql = " SELECT p.payment_status as pay_status,p.*,pp.* from packing_order as p , packing_order_product_code_wise as pa , product_code as pc  WHERE p.is_delete = '0' AND p.packing_order_id = pa.packing_order_id AND pc.product_code_id = pa.product_code_id AND pc.product_code LIKE '%".$filter_data['product_code']."%'";				
			}
			else
				$sql = "SELECT p.payment_status as pay_status,p.*  from packing_order as p WHERE p.is_delete = '0' AND p.gen_pro_as=".$gen_pro_as."";
		}
			
        if (!empty($filter_data)) {
			if (!empty($filter_data['ref_order_no'])) {
				$sql .= " AND p.ref_order_no LIKE '%" . $filter_data['ref_order_no'] . "%' ";
				
			}
			if (!empty($filter_data['cust_email'])) {
				$sql .= " AND p.email LIKE '%" . $filter_data['cust_email'] . "%' ";
				
			}
			if (!empty($filter_data['cust_nm'])) {
				$sql .= " AND p.cust_nm LIKE '%" . $filter_data['cust_nm'] . "%' ";
				
			}if (!empty($filter_data['pro_in_no'])) {
				$sql .= " AND p.pro_in_no LIKE '%" . $filter_data['pro_in_no'] . "%' ";
				
			}
			if(!empty($filter_data['postedby']))
			{
				$spitdata = explode("=",$filter_data['postedby']);
				$sql .=" AND p.user_id = '".$spitdata[1]."' AND 	p.user_type_id = '".$spitdata[0]."'";
			}
		}
		
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY p.packing_order_id";	
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
		
	
	//get total filter data // bahadur
	
	
	public function getTotal_Packing_Order($filter_data=array(),$gen_pro_as=0){
		
		
		if($gen_pro_as>0)
		{//clifton(1) //swisspac(2)
		    if(!empty($filter_data['product_code'])) {
				$sql = " SELECT COUNT(*) as total from packing_order as p , packing_order_product_code_wise as pa , product_code as pc,proforma_product_code_wise as pp  WHERE p.is_delete = '0' AND p.packing_order_id = pa.packing_order_id AND pc.product_code_id = pa.product_code_id AND pc.product_code LIKE '%".$filter_data['product_code']."%' AND pp.pro_in_no=p.pro_in_no AND pp.gen_pro_as=".$gen_pro_as."";				
			}
			else
				$sql = "SELECT COUNT(*) as total from packing_order as p WHERE p.is_delete = '0' AND p.gen_pro_as=".$gen_pro_as."";
		}
		else
		{//common
		    if(!empty($filter_data['product_code'])) {
			    $sql = " SELECT COUNT(*) as total from packing_order as p , packing_order_product_code_wise as pa , product_code as pc  WHERE p.is_delete = '0' AND p.packing_order_id = pa.packing_order_id AND pc.product_code_id = pa.product_code_id AND pc.product_code LIKE '%".$filter_data['product_code']."%'";				
			}
			else
				$sql = "SELECT COUNT(*) as total from packing_order as p WHERE p.is_delete = '0' AND p.gen_pro_as=".$gen_pro_as."";
		}
		
		/*if(!empty($filter_data['product_code'])) {
			$sql = " SELECT *, COUNT(*) as total from packing_order as p , packing_order_product_code_wise as pa , product_code as pc  WHERE p.is_delete = '0' AND p.packing_order_id = pa.packing_order_id AND pc.product_code_id = pa.product_code_id AND pc.product_code LIKE '%".$filter_data['product_code']."%'";				
		}
		else
			$sql = "SELECT *, COUNT(*) as total from packing_order as p WHERE p.is_delete = '0' ";*/
		
		
		 if (!empty($filter_data)) {
			if (!empty($filter_data['ref_order_no'])) {
				$sql .= " AND p.ref_order_no LIKE '%" . $filter_data['ref_order_no'] . "%' ";
				
			}
			if (!empty($filter_data['cust_email'])) {
				$sql .= " AND p.email LIKE '%" . $filter_data['cust_email'] . "%' ";
				
			}
			if (!empty($filter_data['cust_nm'])) {
				$sql .= " AND p.cust_nm LIKE '%" . $filter_data['cust_nm'] . "%' ";
				
			}if (!empty($filter_data['pro_in_no'])) {
				$sql .= " AND p.pro_in_no LIKE '%" . $filter_data['pro_in_no'] . "%' ";
				
			}
			if(!empty($filter_data['postedby']))
			{
				$spitdata = explode("=",$filter_data['postedby']);
				$sql .=" AND p.user_id = '".$spitdata[1]."' AND 	p.user_type_id = '".$spitdata[0]."'";
			}
		}
	//echo $sql;
		$data = $this->query($sql);
		
		return $data->row['total'];
	}
	
	//edit
	public function packing_order_detail($order_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "packing_order` WHERE packing_order_id = '" .(int)$order_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	// bahadur and hasmukh
	//insert
	
	public function add_packing_order($data){
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		
		
		$user=$this->query("INSERT INTO " . DB_PREFIX . "packing_order SET order_no='".$data['order_no']."', user_id='" . $user_id . "',user_type_id='" . $user_type_id . "',ref_order_no = '" . $data['ref_order_no']."', order_date = '".date("Y-m-d",strtotime($data['order_date']))."',	payment_amount = '".$data['amt_maxico']."',date_added=now(),date_modify=now()");
		//print_r($user);
		
		}
	
	//mexico refrence no
	
	public function getOrderNo()
	{
		$sql = "SELECT packing_order_id FROM `" . DB_PREFIX . "packing_order` WHERE is_delete = '0' ORDER BY packing_order_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['packing_order_id'];
		}else{
			return false;
		}	
	}
	
	
	//Update bahadur
	

    public function update_packing_order($data)
    {
    	$sql = "UPDATE `" . DB_PREFIX . "packing_order` SET ref_order_no = '" . $data['ref_order_no']."', cust_nm='".addslashes($data['customer_name'])."',freight_charges='".$data['ship_cost']."', rfc_no='".$data['rfc']."', email='".$data['email']."', order_date = '".date("Y-m-d",strtotime($data['order_date']))."',payment_amount = '".$data['amt_maxico']."',date_modify=now() WHERE packing_order_id ='".$data['packing_order_id']."'";
    //	echo $sql;
    	$this->query($sql);
    }
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "packing_order` SET status = '" .(int)$status. "' WHERE packing_order_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
			
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "packing_order` SET is_delete = '1' WHERE packing_order_id IN (" .implode(",",$data). ")";
			$this->query($sql);
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

	

	
	public function getActiveColor(){

		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE status='1' AND is_delete = '0' ORDER BY color  ASC";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->rows;

		}else{

			return false;

		}

	}
		
	public function getActiveProductCode()

	{

		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE is_delete=0 AND status=1 ";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->rows;

		}else{

			return false;

		}

	}
	public function getProductCode($product_code_id)

	{

		$sql = "SELECT pc.*,p.product_name,pm.make_name,c.color,tm.measurement,pz.zipper_name,ps.spout_name,pa.product_accessorie_name,pc.width,pc.Height,pc.gusset FROM `" . DB_PREFIX . "product_code` AS pc LEFT JOIN product AS p ON pc.product=p.product_id LEFT JOIN product_make pm ON pc.make_pouch=pm.make_id LEFT JOIN pouch_color AS c ON c.pouch_color_id=pc.color LEFT JOIN template_measurement AS tm ON pc.measurement=tm.product_id  LEFT JOIN product_zipper AS pz ON pc.zipper=pz.product_zipper_id LEFT JOIN product_spout AS ps ON ps.product_spout_id=pc.spout LEFT JOIN product_accessorie AS pa ON pa.product_accessorie_id=pc.accessorie WHERE pc.product_code_id='".$product_code_id."' AND pc.is_delete=0 AND pc.status=1 ";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row;

		}else{

			return false;

		}

	}	
	public function getMeasurement()

	{

		$sql = "SELECT * FROM `" . DB_PREFIX . "template_measurement` WHERE is_delete = '0' ";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->rows;

		}else{

			return false;

		}

	}
	
	public function getProforma($proforma_id) {

			$sql = "select * from ".DB_PREFIX." packing_order where packing_order_id = '".$proforma_id."'";

			$data = $this->query($sql);

			if($data->num_rows) {

				return $data->row;

			} else {

				return false;

			}

	}
		
	public function getInvoice_data($proforma_id) {
		//printr($proforma_id);
		$sql = "select * from " . DB_PREFIX ." packing_order_product_code_wise where 	proforma_packing_order_id = '".$proforma_id."' AND is_delete = '0'";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row;

		}

		else {

			return false;

		}

	}
		
	public function getInvoice($proforma_id) {
			//printr($proforma_id);
			$sql = "select * from " . DB_PREFIX ." packing_order_product_code_wise where 	packing_order_id = '".$proforma_id."' AND is_delete = '0'";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}

			else {

				return false;

			}

	}
	
	public function getMeasurementName($product_id)

	{

		$sql = "SELECT * FROM `" . DB_PREFIX . "template_measurement` WHERE product_id = '".$product_id."' ";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row;

		}else{

			return false;

		}

	}
	
	public function getProductCdAll($product_id,$volume,$color)

	{	

	
		$result=$this->query("SELECT pc.product_code, pc.product_code_id, pc.description, clr.color, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.is_delete=0 AND pc.color=clr.pouch_color_id AND pc.product=p.product_id AND pc.product = '".$product_id."' AND pc.volume = '".$volume."' AND pc.color = '".$color."' " );
	   
		return $result->rows;

	}

	public function getProductCd($product_code)

	{

		$result=$this->query("SELECT pc.product_code, pc.product_code_id, pc.description, clr.color, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.product_code LIKE '%".$product_code."%' AND pc.is_delete=0 AND pc.color=clr.pouch_color_id AND p.product_id = pc.product");

		//printr($result);

		return $result->rows;
		//[kinjal] : added on 11/7/2017
	

	}
	
	public function InsertPacking_Order($post=array()) {

			
			//printr($post);//die;
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];

			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

			if(isset($post['packing_order_id']) && !empty($post['packing_order_id'])) {
					
				$packing_order_id['packing_order_id'] = $post['packing_order_id'];
					//printr($packing_order_id);
				//	printr($packing_order_id);
			} else 

			{
			
				$user="INSERT INTO " . DB_PREFIX . "packing_order SET  user_id='" . $user_id . "',user_type_id='" . $user_type_id . "',order_no = '" . $post['order_no']."',freight_charges='".$post['ship_cost']."', cust_nm='".$post['customer_name']."', email='".$post['email']."',ref_order_no = '" . $post['ref_order_no']."', order_date = '".date("Y-m-d",strtotime($post['order_date']))."', billing_order_address = '" . addslashes($post['billing_order_address'])."',delivery_address = '" . addslashes($post['delivery_address'])."',dispatched_date = '".date("Y-m-d",strtotime($post['dispatched_date']))."', courier = '" . $post['courier']."', tracking_details = '" . $post['tracking_details']."',date_added=now()";
				
				//echo $user; 
				$data = $this->query($user);
					
				$packing_order_id = $this->getLastId();
				//printr($packing_order_id);
			}
 
				if($post['product_code_id'] != '-1' && $post['product_code_id'] != '0')

				{

					$size = '';

					$mea = $post['measurement'];

					//$clr_txt = '';

				}

				else

				{

					$size = $post['size'];

					$mea = $post['measurement'];

					//$clr_txt = $post['color_text'];

				}
				$clr_txt = '';
				if(isset($post['color_text']) && $post['color_text']!='')
					$clr_txt = $post['color_text'];
				
				if($post['product_id']=='31' || $post['product_id']=='16')
					$filling = $post['filling'];
				else
					$filling = '';
				
				
				$sql = "INSERT INTO packing_order_product_code_wise SET packing_order_id = '".$packing_order_id['packing_order_id']."',added_by_user_id = '".$user_id."', added_by_user_type_id = '".$user_type_id."', product_code_id = '".$post['product_code_id']."', product_name = '".$post['real_product_name']."',description = '".$post['description']."',quantity = '".$post['qty']."',sales_qty='". $post['qty'] ."',rate = '".$post['rate']."',color_text = '".$clr_txt."',  measurement='".$mea."',  size = '".$size."', date_added = NOW(), date_modify = NOW(), is_delete = 0,filling='".$filling."',tool_price='".$post['tool_price']."'";

				//echo $sql;
				$data = $this->query($sql);			

				$ProInID = $this->getLastInvoiceId();

				//printr($InvoiceId);	
				//printr($ProInID);
//die;
				$returnArray = array(

						'proforma_packing_order_id' => $ProInID ,

						'order_id' => $packing_order_id						

				);

				return $returnArray;



		}
		
		
		

		
	public function updateInvoice($post) {

			

		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];

		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

		$product_name = $this->getProduct($post['product_id']);

		

		if($post['product_code_id'] == '-1' || $post['product_code_id']=='0')

		{

			$size = $post['size'];

			$mea = $post['measurement'];

			//$clr_txt = $post['color_text'];

			if($post['product_code_id'] == '-1')

				$product_nm = 'Custom';

			else

				$product_nm = 'Cylinder';

		}

		else

		{

			$size = '';

			$mea = $post['measurement'];

			//$clr_txt = '';

			$product_nm = $product_name['product_name'];

		}
		$clr_txt = '';
		if(isset($post['color_text']) && $post['color_text']!='')
			$clr_txt = $post['color_text'];
		
		if($post['product_id']=='31' || $post['product_id']=='16')
			$filling = $post['filling'];
		else
			$filling = '';
			
		$sql = "UPDATE  packing_order_product_code_wise SET packing_order_id = '".$post['packing_order_id']."',added_by_user_id = '".$user_id."', added_by_user_type_id = '".$user_type_id."',  product_code_id = '".$post['product_code_id']."', product_name = '".$post['real_product_name']."',description = '".$post['description']."',quantity = '".$post['qty']."',sales_qty='". $post['qty'] ."',rate = '".$post['rate']."',color_text = '".$clr_txt."',  measurement='".$mea."',  size = '".$size."', date_added = NOW(), date_modify = NOW(), is_delete = 0,filling='".$filling."',tool_price='".$post['tool_price']."' WHERE proforma_packing_order_id = '".$post['pro_id']."'   ";



		$data = $this->query($sql);

		

	}

	public function getProduct($product_id)

	{

		$sql  = "SELECT * FROM product WHERE product_id = '".$product_id."' "; 

		$data = $this->query($sql);

		if($data->num_rows)

		{

			return $data->row;

		}

		else

		{

			return false;

		}

	}
	
	public function getProformaInvoice($proforma_id) {

		$sql = "SELECT * ,pc.product_code,pc.description as pro_dec FROM  packing_order_product_code_wise as p ,product_code as pc where packing_order_id = '".$proforma_id."' AND  pc.product_code_id = p.product_code_id AND p.is_delete = '0'";
			//echo $sql;
		$data = $this->query($sql);

		if($data->num_rows){

			return $data->rows;

		}

		else {

			return false;

		}

	}
		
	public function getLastInvoiceId() {

			$sql = "SELECT proforma_packing_order_id FROM packing_order_product_code_wise ORDER BY proforma_packing_order_id DESC LIMIT 1";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}

			else {

				return false;

			}

	}
	public function getLastId() {

				$sql = "SELECT 	packing_order_id FROM packing_order ORDER BY 	packing_order_id  DESC LIMIT 1";
				//echo $sql;
				$data = $this->query($sql);

				if($data->num_rows){

					return $data->row;

				}

				else {

					return false;

				}

	}
				
	
	
	
	public function removeInvoice($proforma_packing_order_id,$packing_order_id)

	{	//echo "DELETE FROM packing_order_product_code_wise WHERE proforma_packing_order_id = '".$proforma_packing_order_id."' AND packing_order_id='".$packing_order_id."'";die;
		$sql = $this->query("DELETE FROM packing_order_product_code_wise WHERE proforma_packing_order_id = '".$proforma_packing_order_id."' AND packing_order_id='".$packing_order_id."'");

	}

	
	public function updateProforma ($post) {

			//printr($post);
			$sql = "UPDATE `" . DB_PREFIX . "packing_order` SET ref_order_no = '" . $post['ref_order_no']."', order_date = '".date("Y-m-d",strtotime($post['order_date']))."', rfc_no='".$post['rfc']."',cust_nm='".addslashes($post['customer_name'])."', email='".$post['email']."', billing_order_address = '" . addslashes($post['billing_order_address'])."',delivery_address = '" .addslashes($post['delivery_address'])."',dispatched_date = '".date("Y-m-d",strtotime($post['dispatched_date']))."', courier = '" . $post['courier']."', tracking_details = '" . $post['tracking_details']."',date_modify=now() WHERE packing_order_id='".$post['packing_order_id']."'";
		
			//echo $sql; die;
			$data = $this->query($sql);
		return $data;
	}

	public function generatePackingNumber(){

		$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'packing_order'");

		$count = $data->row['AUTO_INCREMENT'];

		$strpad = str_pad($count,8,'0',STR_PAD_LEFT);

		return $strpad;

	}

	public function InsertCSVData($handle)
    {
	
	
    	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    	$data=array();
    	$first_time = true;
    	$invoice_no='';
    //	$ibInfo = $this->getUser($user_id,$user_type_id);
    	//printr($ibInfo);
    	//die;
    	
    	$pi ='PACK';
    
    			$new_pro_in_no = $this->generatePackingNumber();
    
    			$order_no = $pi.$new_pro_in_no;
    	
      	//loop through the csv file 
    	while($data = fgetcsv($handle,1000,","))
    	{
    		//printr($data);//die;
    		if ($first_time == true) {
    			$first_time = false;
    			continue;
    		}
    		if(empty($ref_order_check)){
    			$ref_order_check=0;
    		}
    
    			$ref_order_no=$data[0];
    			$billing_order_address=$data[1];
    			$order_date=$data[2];
    			$delivery_address=$data[3];
    			$dispatched_date=$data[4];
    			$courier=$data[5];
    			$tracking_details=$data[6];
    			$total_amount_of_order=$data[15];
    			$size=$data[7];
    			$measurement=$data[8];
    			$product_code_id=$data[9];
    			$quantity=$data[13];
    			$product_name=$data[10];
    			$color_text=$data[11];
    			$description=$data[12];
    			$rate=$data[14];
    	if($ref_order_check!=$data[0])
    		{
    			$pi ='PACK';
    
    			$new_pro_in_no = $this->generatePackingNumber();
    
    			$order_no = $pi.$new_pro_in_no;
    			
    			$ref_order_check =$data[0];
    		
    			$a = explode('/',$order_date);
    			//printr($a);
    			$New_order_date = $a[2].'-'.$a[1].'-'.$a[0];
    			
    			$dis = explode('/',$dispatched_date);
    			
    			$New_dis_date = $dis[2].'-'.$dis[1].'-'.$dis[0];
    		
    			
    			$sql="INSERT INTO " . DB_PREFIX . "packing_order SET  user_id='" . $user_id . "',user_type_id='" . $user_type_id . "',order_no = '". $order_no."',ref_order_no = '" .$ref_order_no."', order_date = '".$New_order_date."',dispatched_date = '".$New_dis_date."',billing_order_address = '" .addslashes( $billing_order_address)."',delivery_address = '".addslashes($delivery_address)."',courier = '" . $courier."', tracking_details = '" .$tracking_details."',date_added=now(),payment_amount='".$total_amount_of_order."'";
    			
    			
    			$datasql=$this->query($sql);
    			$packing_order_id = $this->getLastId();
    			//printr($packing_order_id);
    		}
    	//	printr($packing_order_id);die;
    		$sql2 = "INSERT INTO packing_order_product_code_wise SET packing_order_id = '".$packing_order_id['packing_order_id']."',added_by_user_id = '".$user_id."', added_by_user_type_id = '".$user_type_id."', product_code_id = '".$product_code_id."', product_name = '".$product_name."',description = '".$description."',quantity = '".$quantity."',rate = '".$rate."',color_text = '".$color_text."',  measurement='".$measurement."',  size = '".$size."', date_added = NOW(), date_modify = NOW(), is_delete = 0";
    		
    		$data2=$this->query($sql2);
    	}
    	//$packing_order_id = $this->getLastId();
    //	echo $sql;die;
    	//printr($qty_new);
    	return $packing_order_id;
    }
	
    public function invoiced_status_update($order_id,$invoice_status)
    {
    	if($invoice_status==1){
    	    $sql = "UPDATE `" . DB_PREFIX . "packing_order` SET invoiced_status = 0 WHERE packing_order_id ='".$order_id."'";
    	}else if($invoice_status==0)
    	{
    	    $sql = "UPDATE `" . DB_PREFIX . "packing_order` SET invoiced_status = 1 WHERE packing_order_id ='".$order_id."'";	
    	}
    	//printr($sql);die;
    	$this->query($sql);
    }	
    public function packingArrayForCSV($packing_nos)
    {	
    	$i=0;
    	$html='';
    		$html.='<div class="table-responsive">';
    					$html.='<table class="table b-t text-small table-hover" style="border:groove;">';
    					 $html.=' <thead style="border:groove;">';
    					 $html.='<tr style="border:groove;"> ';                    			 
    						$html.='<th>Order No  </th>';
    						 $html.='<th >Refrence Order No</th>';
    						$html.='<th>Customer Detail<br><small class="text-muted">Email</small></th>';
    						  $html.='<th>Description / Rate / Qty</th>';
    						   $html.='<th>Total Price</th>';
    							$html.='<th>Invoice Info</th>';
    						   $html.='<th>Delivery & Contact Details</th>';
    							$html.='<th>Dispatched Date</th>';
    							$html.='<th>Courier</th>';
    							 $html.='<th>Tracking Details</th>';
    						  $html.='</tr>';
    						  $html.='</thead>';
    				foreach($packing_nos as $packing_no)
    				{
    				
    					//$packing_product_details=$this->getProformaInvoice($packing_no);
    				//	printr($packing_product_details);
    					$packing_order=$this->packing_order_detail($packing_no);
    						
    								
    						if(!empty($packing_order)){
    							
    									$html.='<tbody style="border:groove;">';
    								   $html.='<tr style="border:groove;">';
    									$html.='<td align="top">'.$packing_order['order_no'].'</td>';                         
    									$html.='<td align="top">'.$packing_order['ref_order_no'].'<br>'.dateformat('4',$packing_order['order_date']).'</td>';                         
    									$html.='<td align="top">'.stripslashes($packing_order['cust_nm']).'<br>'.$packing_order['email'].'</td>';  
    									$html.='<td >';
    									  $html.='<table style="border:groove;">';
    									  $packing_product_details=$this->getProformaInvoice($packing_no);
    										if(!empty($packing_product_details)){
    														foreach($packing_product_details as $pack)
    														{
    															
    															
    														$html.='<tr >';
    															$html.='<td align="top">'. $pack['pro_dec'].'</td>';
    																$html.='<td align="top" >'. $pack['rate'].'</td>';
    															$html.='<td align="top" >'. $pack['quantity'].'</td>';
    														$html.='<tr>';
    														}
    										}
    										$html.='</table>';						  
    									$html.='</td>';
    									$html.='<td align="top">'.$packing_order['payment_amount'].'</td>';         
    									$html.='<td align="top">'.$packing_order['billing_order_address'].'</td>';         
    									$html.='<td align="top">'.$packing_order['delivery_address'].'</td>';         
    									$html.='<td align="top">'.dateformat('4',$packing_order['dispatched_date']).'</td>';         
    									$html.='<td align="top">'.$packing_order['courier'].'</td>';         
    									$html.='<td align="top">'.$packing_order['tracking_details'].'</td>';         
    								  
    								
    								   
    								$html.='</tr>';
    								
    						  } else{ 
    							  $html.='<tr><td colspan="5">No record found !</td></tr>';
    						  }
    					 
    						 $html.=' </tbody>';
    					 
    				}
    				  $html.=' </table>';
    		 $html.=' </div>';
    	  return $html;
    	//return $input_array;
    }
    
    public function getUser($user_id,$user_type_id)
    
    	{	//echo $user_type_id;
    
    		if($user_type_id == 1){
    
    			$sql = "SELECT u.user_id,ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
    
    			//echo $sql;
    
    		}elseif($user_type_id == 2){
    
    			$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
    
    			
    
    		}elseif($user_type_id == 4){
    
    			$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id as user_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
    
    		}else{
    
    			
    
    			$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
    
    		}
    
    		$data = $this->query($sql);
    
    		return $data->row;
    
    	}
    public function getUserList(){
    
    	$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
    
    	$data = $this->query($sql);
    
    	return $data->rows;
    
    }
    public function getReport($post)
    {
    	 //printr($post);die;
     	$from_date=$post['f_date'];
    	$t_date = $post['t_date'];
    	$user_type_id=$user_id='';
    	$con='';
    	$cond='';
    	$date=$group='';
        
        $amt_cond='*';
    	if(isset($post['user_name']) && $post['user_name']!='')
    	{
    		$u = explode("=", $post['user_name']);
    		$added_by_user_id = $u[1];
    		$added_by_user_type_id = $u[0];
    		$user_condition=" AND po.user_id ='".$added_by_user_id."' AND po.user_type_id ='".$added_by_user_type_id."' ";
    	}
    	else
    	{
    		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    		$user_condition=$group=" ";
    		
            if(isset($post['full_amt']) && $post['full_amt']=='1')
            {
                $amt_cond='SUM(payment_amount) as final_amount,po.*,am.*';
                $group = 'GROUP BY po.user_id,po.user_type_id';
            }
    	}
    	
    	if($from_date != '' && $t_date != '')
    	{
    		
    		$date = "AND (po.order_date >= '".$from_date."' AND po.order_date <= '".$t_date."') ";
    	}
    	if($post['gen_pro_as']!=0)
    	{//clifton(1) //swisspac(2)
    	    if(isset($post['product_code']) && $post['product_code']!='')
    		    $sql="SELECT $amt_cond,pc.product_code,pc.description from  packing_order as po ,account_master as am,packing_order_product_code_wise as pop,product_code as pc,proforma_product_code_wise as pp  WHERE  pc.product_code_id=pop.product_code_id AND am.user_id=po.user_id AND am.user_type_id=po.user_type_id AND po.packing_order_id!=0 AND po.is_delete=0 AND po.packing_order_id=pop.packing_order_id AND pop.product_code_id = '".$post['product_code']."' $date $user_condition $group AND po.pro_in_no=pp.pro_in_no AND pp.gen_pro_as=".$post['gen_pro_as']."";
    		else
    		    $sql="SELECT $amt_cond from  packing_order as po ,account_master as am,proforma_product_code_wise as pp  WHERE  am.user_id=po.user_id AND am.user_type_id=po.user_type_id AND po.packing_order_id!=0 AND po.is_delete=0 $date $user_condition $group AND po.pro_in_no=pp.pro_in_no AND pp.gen_pro_as=".$post['gen_pro_as']."";
    		
    	}
    	else
    	{//common
    	    if(isset($post['product_code']) && $post['product_code']!='')
    		    $sql="SELECT $amt_cond,pc.product_code,pc.description from  packing_order as po ,account_master as am,packing_order_product_code_wise as pop,product_code as pc  WHERE  pc.product_code_id=pop.product_code_id AND am.user_id=po.user_id AND am.user_type_id=po.user_type_id AND po.packing_order_id!=0 AND po.is_delete=0 AND po.packing_order_id=pop.packing_order_id AND pop.product_code_id = '".$post['product_code']."' $date $user_condition $group";
    		else
    		    $sql="SELECT $amt_cond from  packing_order as po ,account_master as am  WHERE  am.user_id=po.user_id AND am.user_type_id=po.user_type_id AND po.packing_order_id!=0 AND po.is_delete=0  $date $user_condition $group";
    	}
    	$sql.=" ORDER BY po.date_added ASC";
        //echo $sql;
        //printr('<br>');//die;
    	 $data=$this->query($sql);
    	 //printr($data);//die;
    	 if($data->num_rows)
    	 { 
    	 	 return  $data->rows;
    		 					
    	 }
    	 else
    	 {
    		 return false;
    	 }
    	
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


	public function getEmpList()
	{
		$userEmployee = $this->getUserEmployeeIds('4', '10');
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM account_master WHERE ((user_id='10' AND user_type_id='4') OR (user_type_id='2' AND user_id IN (".$userEmployee.")))ORDER BY user_name ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else{
			return false;
		}
		
		
		
	}
		
	

// view part
public function view_packing_report($custom_order_details,$post)
{ 
	//printr($custom_order_details);//die;
    $html = "";
    $amt_cond='0';
    if(isset($post['full_amt']) && $post['full_amt']=='1')
        $amt_cond=$post['full_amt'];
        
		 $html .= "<div class='form-group'>
				<div class='table-responsive'>
				&nbsp;&nbsp;<span> Searching Date From: <b>".dateFormat(4,$post['f_date'])."</b> To: <b>".dateFormat(4,$post['t_date'])."</b></span> <br><br>
			    <table class='table  table-striped b-t text-small' id='custom_order'>
						<thead>";
						
							$html .= "<tr>
                                          <th>Sr No</th>
                                          <th>Order Date</th>
                                          <th>Order No</th>
                                          <th>Reference Order No</th>
										  <th>Customer Name</th>
										  <th>Description /Normal Rate / Express Rate / Qty /Pedimento Mexico</th>
                                          <th>Payment amount</th>
                                          <th>Payment amount Without Vat & Shipping Charge</th>
										  <th>Dispatched date</th>
										  <th>Posted By</th>
										  <th>Invoiced Status</th>
                                           <th>Destination</th>   
                                        </tr>
                                    </thead>     
          
                  					<tbody>";
    								  	$i=1;$payment_amount=$withoutVatandShipping=0;$arr_user = array();
    								if(isset($custom_order_details) && !empty($custom_order_details))
    								{
    									
    									foreach($custom_order_details  as $reports)
    									{//printr($reports['packing_order_id']);
    										$proforma = $this->getProformaInvoiceId($reports['packing_order_id']);
                                            $country = $this->query("SELECT country_name  FROM " . DB_PREFIX . "country  WHERE country_id = '".$reports['destination']."'");	
    			                            $totalwithoutVatandShipping = $reports['payment_amount'] - ((( $reports['payment_amount'] * $proforma['gst_tax'] ) / (100 + $proforma['gst_tax'] )) +$proforma['freight_charges'] )  ;
                                            $style ="style='background-color: darkgray;'";
                                            if($i%2==0)
                                                $style ="style='background-color: antiquewhite;'";
        									$html .= "<tr valign='top' ".$style.">
                                                         	
                                                         	<td>".$i."</td>
                    
                                                            <td>".dateFormat(4,$reports['order_date'])."</td> 
                                                            
                                                            <td>".$reports['order_no']." <br>".$reports['pro_in_no']."</td>
                                                            
                                                            <td>".$reports['ref_order_no']."</td>
                                                            
                                                            <td>".$reports['cust_nm']."</td>
                                                            
                                                            <td>
                                                                <table>";
                                                                $product_code=isset($reports['product_code'])?$reports['product_code']:'';
                                                                    if($product_code!='')
                                                                    {   
                                                                        $html.="<tr>
                                                                                    <td>".$product_code."<br> ".$reports['description']."</td>
                                                                                    <td>".$reports['rate']."</td>
                                                                                    <td>".$reports['express_rate']."</td>
                                                                                    <td>".$reports['quantity']."</td>
                                                                                    <td>".$reports['pedimento_mexico']."</td>
                                                                                </tr>";
                                                                    }
                                                                    else
                                                                    {
                                                                        $html.="<tr>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                </tr>";
                                                                    }
                                                        $html.="</table>
                                                            </td>
                                                            
                                                            <td style='text-align:right;'>".$reports['payment_amount']."</td>
                                                            
                                                            <td style='text-align:right;'>".number_format($totalwithoutVatandShipping,2)."</td>
                                                            
                                                            <td>".dateFormat(4,$reports['dispatched_date'])."</td> 
                                                            
                    									   	<td><span style='color:red;'>".$reports['user_name']."</span></td>";
                    									   	
                    									   	if($reports['invoiced_status']=='1')
                    									   	    $html.="<td><span style='color:green;'><b>Invoiced</b></span></td>";
                    									   	else
                    									   	    $html.="<td><span style='color:red;'><b>Not Invoiced</b></span></td>";
        									            
                                                            $html.="<td><span style='color:red;'>".$country->row['country_name']."</span></td>"; 
                                           $html.="</tr>";
    									
    								        $payment_amount+=$reports['payment_amount'];
    								        $withoutVatandShipping+=$totalwithoutVatandShipping;
    								        $arr_user[$reports['user_name']] = array('payment_amount' =>isset($reports['final_amount'])?$reports['final_amount']:'0',
    								                                                 'withoutVatandShipping'=>number_format($totalwithoutVatandShipping,2));
    									    $i++;
    								}
				        
								
    								$html .=" <tr valign='top'>
    								                <td colspan='6' style='text-align:right;'>Total</td>
    								                <td style='text-align:right;'>".$payment_amount."</td>
    								                <td style='text-align:right;'>".number_format($withoutVatandShipping,2)."</td>
    								                <td colspan='3'></td>
    								          </tr>";
								
                    }
					else
					{
						 $html .=" <tr valign='top'><td>no records found</td></tr>";
					}
					if($amt_cond==1)
					{
					    $html="<div class='form-group'>
    						<div class='table-responsive'>
    						&nbsp;&nbsp;<span> Searching Date From: <b>".dateFormat(4,$post['f_date'])."</b> To: <b>".dateFormat(4,$post['t_date'])."</b></span> <br><br>
    					    <table class='table  b-t text-small' id='custom_order' border='1'>
    					        <thead>
    					            <tr valign='top'>
						                <td></td>
						                <td>Payment amount</td>";
						                if($post['user_name']!='')
						                    $html.="<td>Payment amount Without Vat & Shipping Charge</td>";
    						$html.="</tr>
    					        </thead>
    					        <body>";
    					        if($post['user_name']!='')
    					        {
    					            
    					            $html.="<tr valign='top'>
								                <td>Total</td>
								                <td style='text-align:right;'>".$payment_amount."</td>
								                <td style='text-align:right;'>".number_format($withoutVatandShipping,2)."</td>
            								</tr>";
    							}
    							else
    							{   $i=$payment=0;
    							    foreach($arr_user as $key=>$user)
    							    {
    							        $style ="style='background-color: darkgray;'";
                                            if($i%2==0)
                                                $style ="style='background-color: antiquewhite;'";
    							        $html.="<tr valign='top' ".$style.">
    								                <td>".$key."</td>
    								                <td style='text-align:right;'>".$user['payment_amount']."</td>
                								</tr>";
                					    $payment+=$user['payment_amount'];
                					    $i++;
    							    }
    							    $html.="<tr>
    							                <td>Total</td>
    							                <td style='text-align:right;'><b>".$payment."</b></td>
    							           </tr>";
    							}
					}
						
                                                     
                    $html .="   </tbody>
                       </table>";
    $html .=  " </div>
            </div>
        ";
		return $html;
	
	}
			
			
    
    public function getProformaInvoiceId($packing_order_id){
    
    	$sql = "SELECT p.* FROM " . DB_PREFIX . "proforma_product_code_wise as p,packing_order as po WHERE po.pro_in_no = p.pro_in_no AND po.packing_order_id='" .(int)$packing_order_id. "'";
    	//echo $sql;
    	$data = $this->query($sql);
    			if($data->num_rows){
    				return $data->row;
    			}
    			else{
    				return false;
    			}
    }
    public function Sales_status_update($packing_order_id){
    
    	 $sql = "UPDATE `" . DB_PREFIX . "packing_order` SET sales_status = 1 WHERE packing_order_id ='".$packing_order_id."'";	
    			$data = $this->query($sql);
    		
    }
    public function getcurrencyName($currency_id){
    
    	$sql = "SELECT currency_code  FROM " . DB_PREFIX . "currency WHERE currency_id = '" .(int)$currency_id. "'";
    	$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else{
			return false;
		}
    }

}
?>