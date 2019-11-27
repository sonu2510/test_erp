<?php
//jayashree
class boxMaster extends dbclass{
	
	public function addPouch($data)
	{	
		//printr($data);
		$product_id =$data['product'];
		$valve = $data['valve'];
		$zip =$data['zipper'];
		$spout = $data['spout'];
		$acce = $data['accessorie'];
		$mk = $data['make'];
		$pouch_v = $data['pouch_volume'];
		$pouch_v_type = $data['pouch_volume_type'];
		//$qty = $data['pouch_quantity'];
		//$box_wt =$data['box_weight'];
		//$box_wt_type = $data['box_weight_type'];
		
		//edited by [kinjal] (12/10/2016)
		/*$sql1="Select * from box_master where product_id='".$product_id."' AND valve='".$valve."' AND zipper = '".$zip."' AND spout = '".$spout."' AND make_pouch='".$mk."' AND accessorie = '".$acce."' AND pouch_volume= '".$pouch_v."' AND pouch_volume_type='".$pouch_v_type."' AND is_delete=0";
		//echo $sql1;
		$data1 = $this->query($sql1);*/

		//[kinjal]: add transportation for insert transport[30apr (1:20pm)]
		/*if($data1->num_rows == 0)
		{*/ //echo "hi";
			$sql = "INSERT INTO `" . DB_PREFIX . "box_master` SET pouch_volume = '" .$data['pouch_volume']. "',pouch_volume_type = '".$data['pouch_volume_type']."', product_id='".$data['product']."', valve = '".$data['valve']."', zipper = '".$data['zipper']."', spout = '".$data['spout']."',make_pouch='".$data['make']."', accessorie = '".$data['accessorie']."',transportation='".encode($data['transport'])."', quantity = '".$data['pouch_quantity']."',  box_weight = '".$data['box_weight']."', box_weight_type = '".$data['box_weight_type']."', status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0,cust_box_weight = '".$data['cust_box_weight']."',cust_box_weight_type = '".$data['cust_box_weight_type']."',cust_quantity='".$data['cust_quantity']."', net_weight = '" .$data['net_weight']. "',cust_net_weight = '" .$data['cust_net_weight']. "',net_weight_type = '" .$data['net_weight_type']. "',cust_net_weight_type = '" .$data['cust_net_weight_type']. "'";
			$this->query($sql);
			//echo $sql;
			return 1;
		/*}
		else
		{
			return 0;
		}*/
	}
	public function getProductPouch() {
		$sql = "select * from `".DB_PREFIX."box_master` where is_delete = 0";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->rows;
		}
		else {
			return false;
		}
	}
	public function getMeasurementName($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "template_measurement` WHERE product_id = '".$product_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getMeasurement(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "template_measurement` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function updateTransportation($status,$data){
		
		$sql = $sql = "UPDATE `" . DB_PREFIX . "box_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE pouch_id IN (" .implode(",",$data). ")";		
		$this->query($sql);		
	}
	
	public function getTotalProduct($filter_data=array(),$prod_id){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "box_master` WHERE is_delete = 0 and product_id='".$prod_id."'";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['pouch_volume'])){
				
				$ex=$filter_data['pouch_volume'];
					$im = preg_split('#\s+#', $ex);

					$cnt = count($im);
					$one=$im[0];
					if($cnt==1)
			 		{	
						if(is_numeric($filter_data['pouch_volume'])){}
						
						else{					
							$sql1="select * from ". DB_PREFIX . "template_measurement where measurement = '".$filter_data['pouch_volume']."'";
							$data1 = $this->query($sql1);
							$volume_type = $data1->row['product_id'];					
						}
					}
					else
					{
							$sql1="select * from ". DB_PREFIX . "template_measurement where measurement = '".$im[1]."'";
							$data1 = $this->query($sql1);
							$volume_type = $data1->row['product_id'];						
					}
					
					 if($cnt==1)
			 		{	
			 			if(is_numeric($filter_data['pouch_volume']))
						{
							$sql .= " AND pouch_volume = '".$one."'";
						}
						else
						{	
							$sql .= " AND pouch_volume_type =  '".$volume_type."'";
						}
					 }
					else
					{
						$imr = $im[1];
						$sql .= " AND pouch_volume = '".$one."' AND pouch_volume_type = '".$volume_type."' ";	
					}
				//$sql .= " AND pouch_volume LIKE '%".$filter_data['pouch_volume']."%' ";		
			}
			if(!empty($filter_data['quantity'])){
				$sql .= " AND quantity LIKE '%".encode($filter_data['quantity'])."%' ";		
			}
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}

		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getActiveMake(){
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
	public function getPouches($data,$filter_data=array(),$product_id){
	
		$sql = "SELECT * FROM `" . DB_PREFIX . "box_master` WHERE is_delete = 0 And product_id='".$product_id."'" ;
		
		if(!empty($filter_data)){
			if(!empty($filter_data['pouch_volume'])){
				
				$ex=$filter_data['pouch_volume'];
					$im = preg_split('#\s+#', $ex);
					$cnt = count($im);
					$one=$im[0];
					
					if($cnt==1)
			 		{	
						if(is_numeric($filter_data['pouch_volume'])){}
						
						else{
					
							$sql1="select * from ". DB_PREFIX . "template_measurement where measurement = '".$filter_data['pouch_volume']."'";
							$data1 = $this->query($sql1);
							$volume_type = $data1->row['product_id'];
					
						}
					}
					else
					{
							$sql1="select * from ". DB_PREFIX . "template_measurement where measurement = '".$im[1]."'";
							$data1 = $this->query($sql1);
							$volume_type = $data1->row['product_id'];	
						
					}
					
					 if($cnt==1)
			 		{	
			 			if(is_numeric($filter_data['pouch_volume']))
						{
							$sql .= " AND pouch_volume LIKE  '%".$one."%'";
						}
						else
						{
							$sql .= " AND pouch_volume_type LIKE  '%".$volume_type."%'";
						}
					 }
					else
					{
						$imr = $im[1];
						$sql .= " AND pouch_volume = '".$one."' AND pouch_volume_type = '".$volume_type."' ";	
					}
				
			}
			if(!empty($filter_data['quantity'])){
				$sql .= " AND quantity LIKE '%".$filter_data['quantity']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pouch_id";	
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
	
	public function getPouchData($pouch_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "box_master` WHERE pouch_id = '" .(int)$pouch_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updatePouchData($id,$post)
	{ 
		//edited by [kinjal] (12/10/2016)
		$old_array = array( 'pouch_id' => $id,'product' =>$post['product'],'va'=>trim($post['va']),'zip'=> $post['zip'],'spo' => $post['spo'],'acce' => $post['acce'],
							'mk' => $post['mk'],'pouch_v' => $post['pouch_v'],'pouch_v_type'=> $post['pouch_v_type']								
		);
		$new_array = array( 'pouch_id' => $id,'product' =>$post['product'],'va'=>trim($post['valve']),'zip'=> $post['zipper'],'spo' => $post['spout'],
							'acce' => $post['accessorie'],'mk' => $post['make'],'pouch_v' => $post['pouch_volume'],'pouch_v_type'=> $post['pouch_volume_type']
		);
		$differance =  array_diff($old_array, $new_array);
		
		$sql1="Select * from box_master where product_id='".$post['product']."' AND valve='".$post['valve']."' AND zipper = '".$post['zipper']."' AND spout = '".$post['spout']."' AND make_pouch='".$post['make']."' AND accessorie = '". $post['accessorie']."' AND pouch_volume= '".$post['pouch_volume']."' AND pouch_volume_type='".$post['pouch_volume_type']."' AND is_delete=0";
		//echo $sql1;
		$data1 = $this->query($sql1);
		//[kinjal]: add transportation for update transport[30apr (1:20pm)]

		/*if($data1->num_rows == 0 || empty($differance))
		{
*/			$sql = "UPDATE `" . DB_PREFIX . "box_master` SET pouch_volume = '" .$post['pouch_volume']. "', pouch_volume_type = '".$post['pouch_volume_type']."', quantity = '".$post['pouch_quantity']."', box_weight = '".$post['box_weight']."', box_weight_type = '".$post['box_weight_type']."',valve = '".$post['valve']."', zipper = '".$post['zipper']."', spout = '".$post['spout']."',make_pouch='".$post['make']."', accessorie = '".$post['accessorie']."',transportation='".encode($post['transport'])."', status = '" .$post['status']. "',  date_modify = NOW(),cust_box_weight = '".$post['cust_box_weight']."', cust_box_weight_type = '".$post['cust_box_weight_type']."',cust_quantity='".$post['cust_quantity']."' ,net_weight = '" .$post['net_weight']. "',cust_net_weight = '" .$post['cust_net_weight']. "',net_weight_type = '" .$post['net_weight_type']. "',cust_net_weight_type = '" .$post['cust_net_weight_type']. "' WHERE pouch_id = '".$id."' ";
		$this->query($sql);
			return 1;
		/*}
		else
		{
			return 0;
		}*/
	}
		
	public function updatepouchStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "box_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE pouch_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "box_master` SET is_delete = 1, date_modify = NOW() WHERE pouch_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	
	
	//update transportation status
	public function updateBoxMasterStatus($pouch_id,$status_value){
		$sql = "UPDATE " . DB_PREFIX . "box_master SET status = '".$status_value."', date_modify = NOW() WHERE pouch_id = '" .(int)$pouch_id. "'";
		$this->query($sql);
	}
	
	public function getTotalActiveProducts(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getProducts($data){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		
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
	public function getProductName($product_id){
		$sql="Select product_name,product_id from product where product_id='".$product_id."' ";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
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
	
	public function getCurrentProduct($product_id){
		$sql = "SELECT product_name FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' and product_id='".$product_id."' ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getCurrentZipper($zipper_id){
		$data = $this->query("SELECT zipper_name FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' and product_zipper_id='".$zipper_id."' ");
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getCurrentSpout($spout_id){
		$data = $this->query("SELECT spout_name FROM `" . DB_PREFIX . "product_spout` WHERE status='1' AND is_delete = '0' and product_spout_id='".$spout_id."' ");
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getCurrentAccessorie($accessorie_id){
		$data = $this->query("SELECT product_accessorie_name FROM `" . DB_PREFIX . "product_accessorie` WHERE status='1' AND is_delete = '0' and product_accessorie_id='".$accessorie_id."' ");
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getCurrentMake($make_id){
		$data = $this->query("SELECT make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' and make_id='".$make_id."' ");
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//rohit
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
	public function checkProductZipper($product_id){
		$data = $this->query("SELECT zipper_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['zipper_available'];	
		}else{
			return false;
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
	public function InsertCSVData($handle)
	{
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$data=array();
		$first_time = true;
	
	  	while($data = fgetcsv($handle,1000,","))
		{
		
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
				$pouch_volume=$data[0];
				$pouch_volume_type=$data[1];
				$product_id=$data[2];
				$valve=$data[3];
				$zipper=$data[4];
				$spout=$data[5];
				$make_pouch=$data[6];
				$accessorie=$data[8];
				$transportation=$data[7];
				$quantity=$data[9];
				$box_weight=$data[12];
				$box_weight_type=$data[13];
				$net_weight=$data[14];
				$net_weight_type=$data[15];
				$cust_quantity=$data[16];
				$cust_box_weight=$data[17];
				$cust_box_weight_type=$data[18];
				$cust_net_weight=$data[19]; 
				$cust_net_weight_type=$data[20];
				$status=$data[21];
        
        //   printr($data);  die; 
	
		$sql = "INSERT INTO `" . DB_PREFIX . "box_master` SET pouch_volume = '" .$pouch_volume. "',	pouch_volume_type = '".$pouch_volume_type."', product_id='".$product_id."', valve = '".$valve."', zipper = '".encode($zipper)."', spout = '".encode($spout)."',make_pouch='".$make_pouch."', accessorie = '".encode($accessorie)."',transportation='".encode($transportation)."', quantity = '".$quantity."',  box_weight = '".$box_weight."', box_weight_type = '".$box_weight_type."', status = '" .$status. "', date_added = NOW(),date_modify = NOW(),is_delete=0,cust_box_weight = '".$cust_box_weight."',cust_box_weight_type = '".$cust_box_weight_type."',cust_quantity='".$cust_quantity."', net_weight = '" .$net_weight. "',cust_net_weight = '" .$cust_net_weight. "',net_weight_type = '" .$net_weight_type. "',cust_net_weight_type = '" .$cust_net_weight_type. "'";
			$data = $this->query($sql);
	//	echo $sql;//die;
     // printr($data);  die; 

			
		}
	}
}
?>