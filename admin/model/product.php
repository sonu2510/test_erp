<?php
class product extends dbclass{
	
	public function addProduct($data){
		//printr($data);die;
		$gusset = '';
		if(isset($data['gusset']) && !empty($data['gusset'])){
			$gusset = implode(',',$data['gusset']);
		}
		$print_type = '';
		if(isset($data['printing_option_type']) && !empty($data['printing_option_type'])){
			$print_type = implode(',',$data['printing_option_type']);
		}		
		if(isset($data['zipperoption']) && ($data['zipperoption'] == 'h' || $data['zipperoption'] == 'w')){
			$zipperopt = $data['zipperoption'];
		}else{
			$zipperopt = 'w';
		}
		$bottom=isset($data['bottom_min_qty'])?$data['bottom_min_qty']:'';
		$side=isset($data['side_min_qty'])?$data['side_min_qty']:'';
		$both=isset($data['both_min_qty'])?$data['both_min_qty']:'';
		$no_guesst=isset($data['no_min_qty'])?$data['no_min_qty']:'';
		$printing_option=isset($data['printing_option'])?$data['printing_option']:'';
		$gusset_available=isset($data['gusset_available'])?$data['gusset_available']:'';
		$zipper_available=isset($data['zipper_available'])?$data['zipper_available']:'';
		$weight_available=isset($data['weight_available'])?$data['weight_available']:'';
		$tintie_available=isset($data['tintie_available'])?$data['tintie_available']:'';
		$spout_pouch_available=isset($data['spout_pouch_option'])?$data['spout_pouch_option']:'';
		$make_pouch_available=isset($data['pouch'])?$data['pouch']:'';
		$pouch='';
		if($make_pouch_available != '')
		{
			$pouch = implode(',',$data['pouch']);
		}	
		//printr($pouch);die;
		$sql = "INSERT INTO `" . DB_PREFIX . "product` SET product_name = '" .strip_tags($data['name']). "',email_product = '" .$data['product_name']. "',gusset_available = '".$gusset_available."',printing_option = '".$printing_option."',printing_option_type = '".$print_type."',bottom_min_qty = '" .$bottom. "',side_min_qty = '" .$side. "',both_min_qty = '" .$both. "',no_min_qty='".$no_guesst."',zipper_available = '".$zipper_available."',
		weight_available = '".$weight_available."',tintie_available = '".$tintie_available."',gusset = '".$gusset."',abbrevation='".$data['abbrevation']."',per_kg_price = '".$data['per_kg_price']."', strip_thickness = '" .$data['strip_thickness']. "',category = '" .implode(',',$data['category']). "',status = '" .$data['status']. "', calculate_zipper_with = '".$zipperopt."', spout_pouch_available = '".$spout_pouch_available."',make_pouch_available = '".$pouch."', date_added = NOW() ";
		//echo $sql;die;
		$this->query($sql);
		return $this->getLastId();
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
	
	public function getQuantity(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_quantity` WHERE status=1 AND is_delete=0");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function updateProduct($product_id,$data){
	//	printr($data);die;
		$gusset = '';
		if(isset($data['gusset']) && !empty($data['gusset'])){
			$gusset = implode(',',$data['gusset']);
		}
		
		$print_type = '';
		if(isset($data['printing_option_type']) && !empty($data['printing_option_type'])){
			$print_type = implode(',',$data['printing_option_type']);
		}
		
		if($data['zipperoption'] && $data['zipperoption'] == 'h' || $data['zipperoption'] == 'w'){
			$zipperopt = $data['zipperoption'];
		}else{
			$zipperopt = 'w';
		}
		$bottom=isset($data['bottom_min_qty'])&& isset($data['printing_option_type']) && in_array('bottom',$data['printing_option_type'])?$data['bottom_min_qty']:'';
		$side=isset($data['side_min_qty']) && isset($data['printing_option_type']) && in_array('side',$data['printing_option_type'])?$data['side_min_qty']:'';
		$both=isset($data['both_min_qty'])&& isset($data['printing_option_type']) && in_array('both',$data['printing_option_type'])?$data['both_min_qty']:'';
	$no_guesst=isset($data['no_min_qty'])&& isset($data['printing_option_type']) && in_array('no',$data['printing_option_type'])?$data['no_min_qty']:'';
		$make_pouch_available=isset($data['pouch'])?$data['pouch']:'';
		$pouch='';
		if($make_pouch_available != '')
		{
			$pouch = implode(',',$data['pouch']);
		}
		
		$sql = "UPDATE `" . DB_PREFIX . "product` SET product_name = '" .strip_tags($data['name']). "',email_product = '" .$data['product_name']. "',gusset_available = '".$data['gusset_available']."',printing_option = '".$data['printing_option']."',printing_option_type = '".$print_type."',bottom_min_qty = '" .$bottom. "',side_min_qty = '" .$side. "',both_min_qty = '" .$both. "',no_min_qty='".$no_guesst."',zipper_available = '".$data['zipper_available']."',weight_available = '".$data['weight_available']."',tintie_available = '".$data['tintie_available']."',gusset = '".$gusset."',abbrevation='".$data['abbrevation']."',per_kg_price = '".$data['per_kg_price']."', strip_thickness = '" .$data['strip_thickness']. "' ,spout_pouch_available = '".$data['spout_pouch_option']."',make_pouch_available = '".$pouch."',category = '" .implode(',',$data['category']). "', status = '" .$data['status']. "', calculate_zipper_with = '".$zipperopt."', date_modify = NOW() WHERE 
		product_id = '" .(int)$product_id. "'";
	//	echo $sql;die;
		$this->query($sql);
	}
	
	public function getTotalProduct($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE is_delete = '0'";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['name'])){
				$sql .= " AND product_name LIKE '%".$filter_data['name']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}
			
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
		
	}
	
	public function getProducts($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE is_delete = '0'";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['name'])){
				$sql .= " AND product_name LIKE '%".$filter_data['name']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}
			
		}
		
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
	
	/* ################## LAYER MASTER ###########################*/
	public function addProductLayer($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_layer` SET layer = '" .(int)$data['number']. "', status = '" .$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateLayer($layer_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_layer` SET layer = '" .$data['number']. "', status = '" .$data['status']. "',  date_modify = NOW() WHERE product_layer_id = '" .(int)$layer_id. "'";
		$this->query($sql);
	}
	
	public function getTotalLayer($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_layer` WHERE 1=1";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['layer'])){
				$sql .= " AND layer = '".$filter_data['layer']."' ";
			}
			
			if($filter_data['status']!=''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}
			
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getLayers($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_layer` WHERE 1=1";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['layer'])){
				$sql .= " AND layer = '".$filter_data['layer']."' ";
			}
			
			if($filter_data['status']!=''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}
			
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY layer";	
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
	
	public function getLayer($layer_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_layer` WHERE product_layer_id = '" .(int)$layer_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}

	
	/* ################## PRODUCT DETAIL MASTER ###########################*/
	public function getProductActive(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function updateStatus($status,$data){		
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "product` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "product` SET is_delete = '1', date_modify = NOW() WHERE product_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	public function getMake()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete='0' ";
		$sql .= " ORDER BY make_id";	
		$sql .= " ASC";
		//echo $sql;		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	
	}
	//[kinjal] (30/3/2016)  
	public function cloneData($post)
	{
		$product_id = $post['product'];
		$pro_new  = $post['product_to'];
		$abb = $post['abb_to'];
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete='0' AND product_id=".$product_id;
		$data = $this->query($sql);
		if($data->num_rows){
			$sql_insert = "INSERT INTO `" . DB_PREFIX . "product` SET product_name = '" .strip_tags($pro_new). "',email_product = '" .$pro_new. "',gusset_available = '".$data->row['gusset_available']."',printing_option = '".$data->row['printing_option']."',printing_option_type = '".$data->row['printing_option_type']."',bottom_min_qty = '" .$data->row['bottom_min_qty']. "',side_min_qty = '" .$data->row['side_min_qty']. "',both_min_qty = '" .$data->row['both_min_qty']. "',no_min_qty='".$data->row['no_min_qty']."',zipper_available = '".$data->row['zipper_available']."',weight_available = '".$data->row['weight_available']."',tintie_available = '".$data->row['tintie_available']."',gusset = '".$data->row['gusset']."',abbrevation='".$abb."',per_kg_price = '".$data->row['per_kg_price']."', strip_thickness = '" .$data->row['strip_thickness']. "',status = '" .$data->row['status']. "', calculate_zipper_with = '".$data->row['calculate_zipper_with']."', spout_pouch_available = '".$data->row['spout_pouch_available']."',make_pouch_available = '".$data->row['make_pouch_available']."', date_added = NOW() ";
			$data_insert = $this->query($sql_insert);
			$new_pro = $this->getLastId();
		
			$sql_get_size = "SELECT * FROM `" . DB_PREFIX . "size_master` WHERE product_id=".$product_id;
			$data_1 = $this->query($sql_get_size);
			if($data_1->num_rows)
			{
				foreach($data_1->rows as $cData)
				{
					$sql_sec = "INSERT INTO " . DB_PREFIX . "size_master SET product_id = '".(int)$new_pro."',product_zipper_id = '".(int)$cData['product_zipper_id']."', volume = '" .$cData['volume']. "',width = '".$cData['width']."', height = '" .$cData['height']. "', gusset = '" . $cData['gusset']. "', weight = '" .$cData['weight']. "', date_added = NOW()";
					$this->query($sql_sec);
				}
			}
			
			$sql_get_profit = "SELECT * FROM `" . DB_PREFIX . "product_profit` WHERE product_id=".$product_id;
			$data_2 = $this->query($sql_get_profit);
			if($data_2->num_rows)
			{
				foreach($data_2->rows as $pData)
				{
					$sql_third = "INSERT INTO `" . DB_PREFIX . "product_profit` SET product_id='".$new_pro."', quantity_id='".$pData['quantity_id']."', size_from='".$pData['size_from']."', size_to = '".$pData['size_to']."', profit = '".$pData['profit']."', plus_minus_quantity = '".$pData['plus_minus_quantity']."',wastage_per='".$pData['wastage_per']."', date_added = NOW()";
					$this->query($sql_third);
				}
			}
			
		}else{
			return false;
		}
		
	}
	
	public function getsize($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "size_master` WHERE product_id = '" .(int)$product_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getProfitPrices($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_profit` WHERE product_id = '" .(int)$product_id. "'";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getstockPrices($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "stock_profit` WHERE product_id = '" .(int)$product_id. "'";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getstockPrices_by_sea($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "stock_profit_by_sea` WHERE product_id = '" .(int)$product_id. "'";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getstockPrices_by_factory($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "stock_profit_factory` WHERE product_id = '" .(int)$product_id. "'";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getToolPrices($product_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' ORDER BY width_to ASC";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getWastage(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "stock_wastage`";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getProductList(){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE is_delete = 0 AND status = 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getProductOtherList($product_id){
	 		$string='';
			foreach($product_id as $key=>$val)
			  {
			  	$string .= $key.',';
				
			  }
			  $updated_string=substr($string, 0, -1);
			//  printr($updated_string);
			 // die;
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE product_id  IN($updated_string) AND is_delete = 0 AND status = 1";
		//echo $sql;die;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getAllColorCategory(){
		$sql = "SELECT * FROM " . DB_PREFIX . "pro_color_catagory WHERE is_delete = 0 AND status = 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}	
}
?>