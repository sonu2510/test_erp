<?php
class order extends dbclass{
	
	public function addOrder($data){	
	//printr($_SESSION['shipment_country']);
		//printr($data);die;
		//printr($_SESSION['product_array']);die;		
		$order_number = $this->generateOrderNumber();
		$first_name = addslashes($data['first_name']);
		$last_name = addslashes($data['last_name']);
		if(!isset($data['shipping_country_id'])) {			
			$shipment_country = $_SESSION['shipment_country'];
		}
		else {
			$shipment_country = $data['shipping_country_id'];
		}

		$customer_name = $data['first_name'].' '.$data['last_name'];		
		$this->query("INSERT INTO `" . DB_PREFIX . "address` SET user_type_id=0, user_id=0 , address_type_id=1, first_name='".$first_name."', last_name = '".$last_name."', address = '".$data['shipping_address_1']."', address_2 = '".$data['shipping_address_2']."', city = '".$data['shipping_city']."', country_id = '".$shipment_country."', zone_id=0, date_added=NOW(), date_modify=NOW()   ");
		$shipping_address_id = $this->getLastId();
		if(!empty($data['same_as_above']) && $data['same_as_above']==1){
			$this->query("INSERT INTO `" . DB_PREFIX . "address` SET user_type_id=0, user_id=0 , address_type_id=2, first_name='".$first_name."', last_name = '".$last_name."', address = '".$data['shipping_address_1']."', address_2 = '".$data['shipping_address_2']."', city = '".$data['shipping_city']."', country_id = '".$shipment_country."', zone_id=0, date_added=NOW(), date_modify=NOW()   ");	
		}else{
			$this->query("INSERT INTO `" . DB_PREFIX . "address` SET user_type_id=0, user_id=0 , address_type_id=2, first_name='".$first_name."', last_name = '".$last_name."', address = '".$data['billing_address_1']."', address_2 = '".$data['billing_address_2']."', city = '".$data['billing_city']."', country_id = '".$data['billing_country_id']."', zone_id=0, date_added=NOW(), date_modify=NOW()");	
		}
		$billing_address_id = $this->getLastId();
		if($_SESSION['LOGIN_USER_TYPE'] == 1){
			$order_currency_query = $this->query("SELECT u.product_rate,c.currency_code FROM `" . DB_PREFIX . "user` u INNER JOIN `" . DB_PREFIX . "country` c ON (u.default_curr = c.country_id) WHERE u.user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
		}else if($_SESSION['LOGIN_USER_TYPE'] == 4){
			$order_currency_query = $this->query("SELECT ib.product_rate,c.currency_code FROM `" . DB_PREFIX . "international_branch` ib INNER JOIN `" . DB_PREFIX . "country` c ON (ib.default_curr = c.country_id) WHERE ib.international_branch_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
		}else if($_SESSION['LOGIN_USER_TYPE'] == 2){
			$parent_user_query = $this->query("SELECT user_type_id,user_id FROM `" . DB_PREFIX . "employee` WHERE employee_id ='".$_SESSION['ADMIN_LOGIN_SWISS']."'  ");
			if($parent_user_query->row['user_type_id']==1){
				$order_currency_query = $this->query("SELECT u.product_rate,c.currency_code FROM `" . DB_PREFIX . "user` u INNER JOIN `" . DB_PREFIX . "country` c ON (u.default_curr = c.country_id) WHERE u.user_id = '".$parent_user_query->row['user_id']."' ");
			}else if($parent_user_query->row['user_type_id']==4){
				$order_currency_query = $this->query("SELECT ib.product_rate,c.currency_code FROM `" . DB_PREFIX . "international_branch` ib INNER JOIN `" . DB_PREFIX . "country` c ON (ib.default_curr = c.country_id) WHERE ib.international_branch_id = '".$parent_user_query->row['user_id']."' ");	
			}
		}		
		//printr($order_currency_query);die;
		$this->query("INSERT INTO `" . DB_PREFIX . "order` SET order_number = '".$order_number."',order_type = '".$data['order_type']."',company_name='".$this->escape($data['company'])."', website='".$data['website']."', customer_name='".$this->escape($customer_name)."', email = '".$data['email']."', contact_number='".$data['contact_number']."', vat_number = '".$data['vat_number']."', shipping_address_id = '".$shipping_address_id."',  billing_address_id = '".$billing_address_id."', added_by_user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."', added_by_user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', order_currency = '".$order_currency_query->row['currency_code']."',currency_rate = '".$order_currency_query->row['product_rate']."', order_status_id = 1, order_note ='".$data['order_note']."', order_instruction = '".$data['order_instruction']."', status = 1, date_added = NOW(), date_modify=NOW(), is_delete=0");		
		$order_id = $this->getLastId();		
		foreach($_SESSION['product_array'] as $order_product_id=>$product_data){
			$this->query("UPDATE `" . DB_PREFIX . "order_product` SET order_id = '".$order_id."' WHERE order_product_id = '".$order_product_id."' ");
		}		
	
		unset($_SESSION['product_array']);
		unset($_SESSION['shipment_country']);
		//echo "SUCCESS";die;
	}
	
	
	public function deleteOrder($order_id){		
		$this->query("DELETE o.*,op.*,opbp.* FROM `". DB_PREFIX ."order` o INNER JOIN `". DB_PREFIX ."order_product` op ON(o.order_id=op.order_id) INNER JOIN `". DB_PREFIX ."order_product_base_price` opbp ON (op.order_product_id=opbp.order_product_id) WHERE o.order_id = '".$order_id."'");
	}
		
	public function insertDieLine($die_lines=array(),$order_product_id){
		foreach($die_lines as $die_line){
			$this->query("INSERT INTO " . DB_PREFIX ."order_product_die_line SET order_product_id = '".$order_product_id."',name='".$die_line['die_name']."',ext = '".$die_line['die_ext']."'");
		}	
	}
	//edited by rohit
	public function UpdateDieLine($die_lines=array(),$order_product_id){
		foreach($die_lines as $die_line){
			if($die_line['order_id'] == $order_product_id) {
			$this->query("INSERT INTO " . DB_PREFIX ."order_product_die_line SET order_product_id = '".$order_product_id."',name='".$die_line['die_name']."',ext = '".$die_line['die_ext']."'");
			}
		}	
	}
	
	public function getdefaultcurrency()
	{
		if($_SESSION['LOGIN_USER_TYPE'] == 1){
			$order_currency_query = $this->query("SELECT u.product_rate,c.currency_code FROM `" . DB_PREFIX . "user` u INNER JOIN `" . DB_PREFIX . "country` c ON (u.default_curr = c.country_id) WHERE u.user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			
		}
		else if($_SESSION['LOGIN_USER_TYPE'] == 4){
			$order_currency_query = $this->query("SELECT ib.product_rate,c.currency_code FROM `" . DB_PREFIX . "international_branch` ib INNER JOIN `" . DB_PREFIX . "country` c ON (ib.default_curr = c.country_id) WHERE ib.international_branch_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			
		}
		else if($_SESSION['LOGIN_USER_TYPE'] == 2){
			
			$parent_user_query = $this->query("SELECT user_type_id,user_id FROM `" . DB_PREFIX . "employee` WHERE employee_id ='".$_SESSION['ADMIN_LOGIN_SWISS']."'  ");
			
			if($parent_user_query->row['user_type_id']==1){
				
				$order_currency_query = $this->query("SELECT u.product_rate,c.currency_code FROM `" . DB_PREFIX . "user` u INNER JOIN `" . DB_PREFIX . "country` c ON (u.default_curr = c.country_id) WHERE u.user_id = '".$parent_user_query->row['user_id']."' ");
				
			}
			else if($parent_user_query->row['user_type_id']==4){
				$order_currency_query = $this->query("SELECT ib.product_rate,c.currency_code FROM `" . DB_PREFIX . "international_branch` ib INNER JOIN `" . DB_PREFIX . "country` c ON (ib.default_curr = c.country_id) WHERE ib.international_branch_id = '".$parent_user_query->row['user_id']."' ");	
			}
		}
		if($order_currency_query->num_rows)
		{
			return $order_currency_query->row;
		}
		else
		{
			return false;
		}
	}
	
	public function getCurrencyList()
	{
		$sql = "SELECT * FROM country where status = 1 and currency_code!='' group by currency_code";
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
	public function getdefaultcountry($user_id,$user_type_id)
	{
		$sql = "SELECT country_id FROM address where user_id='".$user_id."' AND user_type_id = '".$user_type_id."'";
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
	
	public function getOrderProductDieLines($order_product_id){
		
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "order_product_die_line` WHERE order_product_id = '".$order_product_id."'");
		
		if($data->num_rows){
			return $data->rows;	
		}else{
			return false;	
		}
	}
	
	
	public function getOrderProductImages($order_product_id){
		
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "order_product_image` WHERE order_product_id = '".$order_product_id."'");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;	
		}
	}
	
	public function generateOrderNumber(){
		
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'order'");
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,5,'0',STR_PAD_LEFT);
		$number = 'CUST'.$strpad;
		return $number;
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
	
	public function getCountries(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY country_name";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveClient(){
		$sql = "SELECT *,CONCAT(first_name,' ',last_name) as name FROM " . DB_PREFIX . "client WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY first_name";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveLayer(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_layer` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY layer";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
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
	
	public function getOrderStatuses(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE is_delete=0");
		
		//$m = "SELECT * FROM `" . DB_PREFIX . "order_status` WHERE is_delete=0";
		//echo $m;
		//die;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	
	}
	
	
	//spout
	public function getActiveProductSpout(){
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductAccessorie(){
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY product_accessorie_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductOption(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_option` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY option_name";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductColors(){
		$data = $this->query("SELECT pouch_color_id,color FROM " . DB_PREFIX . "pouch_color WHERE status = '1' AND is_delete = '0' ORDER BY color ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductStyle(){
		$data = $this->query("SELECT pouch_style_id,style FROM " . DB_PREFIX . "pouch_style WHERE status = '1' AND is_delete = '0' ORDER BY style ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductVolume(){
		$data = $this->query("SELECT pouch_volume_id,volume FROM " . DB_PREFIX . "pouch_volume WHERE status = '1' AND is_delete = '0' ORDER BY volume ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActivePrintingEffect(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_effect` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY effect_name";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getOption(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_option` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY option_name";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function sumOfNumericArray($array){
		$total = 0;
		if(is_array($array) && !empty($array)){
			foreach($array as $key=>$val){
				$total += $val; 
			}
		}
		//echo $this->numberFormate($total,"3");die;
		return $this->numberFormate($total,"5");
	}
	
	
	/*public function getCalculateTransport($quantity,$selTransport){
		$sql = "SELECT $selTransport FROM " . DB_PREFIX . "product_transport WHERE from_quantity <= '".$quantity."' AND to_quantity >= '".$quantity."' ";		
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["$selTransport"];
		}else{
			return false;
		}
	}*/
	
	/*public function getZipperPrice(){
		$sql = "SELECT price FROM " . DB_PREFIX . "product_zipper WHERE status = '1' ORDER BY product_zipper_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['price'];
		}else{
			return false;
		}
	}*/
	
	/*public function checkZipper($option_id){
		$sql = "SELECT zipper FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".(int)$option_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['zipper'];
		}else{
			return false;
		}
	}*/
	
	function getZipperInfo($zipper_id){
		$data = $this->query("SELECT zipper_name, price, product_zipper_id FROM " . DB_PREFIX . "product_zipper WHERE product_zipper_id = '".(int)$zipper_id."' ");
		if($data->num_rows){
			$return  = array();
			$return['product_zipper_id'] = $data->row['product_zipper_id'];
			$return['zipper_name'] = $data->row['zipper_name'];
			$return['price'] = $data->row['price'];
			return $return;
		}else{
			$return  = array();
			$return['product_zipper_id'] = 0;
			$return['zipper_name'] = 'No zip';
			$return['price'] = 0.00;
			return $return;
		}
	}
	
	public function getCalculateZipperPrice($product_id,$height,$weight,$zipperBasePrice){
		$data = $this->query("SELECT calculate_zipper_with FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$product_id."' ");
		//$zipperBasePrice = $this->getZipperPrice();
		if($data->row['calculate_zipper_with'] && $data->row['calculate_zipper_with'] != ''){
			if($data->row['calculate_zipper_with'] == 'h'){
				$newhh = ($height / 1000);
				$zipperPrice = ($newhh * $zipperBasePrice * 10 );
			}else{
				$newww = ($weight / 1000);
				$zipperPrice = ($newww * $zipperBasePrice * 10 );
			}
		}else{
			$newww = ($weight / 1000);
			$zipperPrice = ($newww * $zipperBasePrice * 10 );
		}
		return $zipperPrice;
	}
	
	/*public function getValvePrice($user_type_id,$user_id){
		if($user_type_id == 4){
			$query = $this->query("SELECT valve_price FROM " . DB_PREFIX . "international_branch WHERE international_branch_id = '".(int)$user_id."' ");
		}
		if($query->row['valve_price'] > 0){
			return $this->numberFormate($data->row['valve_price'],"5");
		}else{
			return 0;
		}
	}*/
	
	public function getCalculateZipper($option_id,$width,$type){
		$sql = "SELECT * FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".(int)$option_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			$optionPrice = $data->row['price'];
			if($type == 1){
				$zipperPrice = 0;
				if($data->row['zipper'] == 1){
					$zipperBasePrice = $this->getZipperPrice();
					$newww = ($width / 1000);
					$zipperPrice = ($newww * $zipperBasePrice * 10 );// * $quantity );
				}
				//echo $zipperPrice;die;
				//return $this->numberFormate(($optionPrice + $pricePerBag + $packingPerPouch + $transportPerPouch),"3");
				return $this->numberFormate(($optionPrice + $zipperPrice ),"5");
			}else{
				return $this->numberFormate($optionPrice,"5");
			}
		}else{
			return false;
		}
	}
	
	public function getPrintingEffectPrice($effect_id){
		$sql = "SELECT price FROM " . DB_PREFIX . "printing_effect WHERE printing_effect_id = '".(int)$effect_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $this->numberFormate($data->row['price'],"3");
		}else{
			return 0;
		}
	}
	
	public function getcalculateProfit($quantity){
		$sql = "SELECT profit FROM " . DB_PREFIX . "product_profit WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $this->numberFormate($data->row['profit'],"3");
		}else{
			return false;
		}
	}
	
	public function getRollProfit($weight){
		/*if($quantity_type == 'kg'){
			$sql = 'profit_kg';
		}elseif($quantity_type == 'pieces'){
			$sel = 'profit_piece';
		}else{
			$sel = 'profit_meter';
		}
		$sql = "SELECT $sel FROM " . DB_PREFIX . "product_roll_profit WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' ";*/		
		$sql = "SELECT profit_kg FROM " . DB_PREFIX . "product_roll_profit WHERE from_kg <= '".$weight."' AND 	to_kg >= '".$weight."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["profit_kg"];
		}else{
			return $this->numberFormate(($weight / 15000),"3");
		}
	}
	
	public function getRollPackingPrice($kg){
		
		$sql = "SELECT price_kgs FROM " . DB_PREFIX . "product_roll_packing WHERE from_kgs <= '".$kg."' AND 	to_kgs >= '".$kg."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["price_kgs"];
		}else{
			return false;
		}
	}
	
	public function getRollTransportPrice($kg){
		
		$sql = "SELECT price_kgs FROM " . DB_PREFIX . "product_roll_transport WHERE from_kgs <= '".$kg."' AND 	to_kgs >= '".$kg."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["price_kgs"];
		}else{
			return false;
		}
	}
	
	public function getMaterialGsm($material_id){
		$sql = "SELECT gsm FROM " . DB_PREFIX . "product_material WHERE product_material_id = '".(int)$material_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['gsm'];
		}else{
			return false;
		}
	}
	
	public function checkMaterial($material_id){
		$sql = "SELECT material_name FROM " . DB_PREFIX . "product_material WHERE product_material_id = '".(int)$material_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if(strtolower($data->row['material_name']) == 'cpp'){
				return 1;
			}
		}else{
			return 0;
		}
	}
	
	public function getMaterialThickmessPrice($material_id,$thickness){
		$sql = "SELECT price FROM " . DB_PREFIX . "product_material_thickness_price WHERE product_material_id = '".(int)$material_id."' AND from_thickness <= '".$thickness."' AND to_thickness >= '".$thickness."' ";
		//echo $sql."==<br>";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['price'];
		}else{
			return false;
		}
	}
	
	//ink price
	public function getInkPrice1(){
		$sql = "SELECT price FROM " . DB_PREFIX . "ink_master WHERE status = '1' AND type = 'Normal' ORDER BY ink_master_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $this->numberFormate( $data->row['price'],"5");
		}else{
			return false;
		}
	}
	public function getInkPrice($basePrice,$type){
		$sql = "SELECT price FROM " . DB_PREFIX . "ink_master WHERE status = '1' AND type = 'Normal' ORDER BY ink_master_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				return $this->numberFormate( ( $basePrice * $data->row['price']),"5");
			}else{
				return $this->numberFormate( $data->row['price'],"5");
			}
		}else{
			return false;
		}
	}
	
	public function getInkSolventPrice($basePrice,$type){
		$sql = "SELECT price FROM " . DB_PREFIX . "ink_solvent WHERE status = '1' AND type = 'Normal' ORDER BY ink_solvent_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				return $this->numberFormate( ( $basePrice * $data->row['price']),"5");
			}else{
				return $this->numberFormate($data->row['price'],"5");
			}
		}else{
			return false;
		}
	}
	
	public function getAdhesivePrice($price,$layerCount,$type){
		$sql = "SELECT price FROM " . DB_PREFIX . "adhesive WHERE status = '1' AND type = 'Normal' ORDER BY adhesive_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),"5");
			}else{
				return $this->numberFormate($data->row['price'],"5");
			}
		}else{
			return false;
		}
	}
	
	public function getCppAdhesivePrice($price,$layerCount,$type){
		$sql = "SELECT price FROM " . DB_PREFIX . "cpp_adhesive WHERE status = '1' ORDER BY cpp_adhesive_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),"3");
			}else{
				return $this->numberFormate($data->row['price'],"3");
			}
		}else{
			return false;
		}
	}
	
	public function getAdhesiveSolventPrice($price,$layerCount,$type){
		$sql = "SELECT price FROM " . DB_PREFIX . "adhesive_solvent WHERE status = '1' AND type = 'Normal' ORDER BY adhesive_solvent_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),"5");
			}else{
				return $this->numberFormate($data->row['price'],"5");	
			}
		}else{
			return false;
		}
	}
	
	public function numberFormate($number,$decimalPoint=3){
		//return number_format($number,$decimalPoint);
		return number_format($number,$decimalPoint,".","");
	}
		
	
	public function getProductName($product_id){
		$sql = "SELECT product_name FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$product_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['product_name'];
		}else{
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
	
	//Start : listing
	public function getTotalOrders($user_type_id,$user_id,$filter_array=array()){


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
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getOrders($user_type_id,$user_id,$data,$filter_array=array()){		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT odr.* FROM `" . DB_PREFIX . "order` odr WHERE odr.is_delete=0";
		 //echo $sql;die;	
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
	
	
	public function getBaseCylinderPrice($currency_id){
		
		$sql = "SELECT price FROM " . DB_PREFIX . "product_cylinder_base_price WHERE currency_id='".$currency_id."' ";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['price'];	
		}else{
			return false;
		}
	}
	
	
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			//$sql = "SELECT u.user_name,u.first_name,u.last_name,am.user_type_id,am.user_id FROM " . DB_PREFIX ."user u, " . DB_PREFIX ."account_master am WHERE u.user_id = '".(int)$user_id."' AND am.user_id = '".(int)$user_id."' AND am.user_type_id = '".(int)$user_type_id."'";
			$sql = "SELECT u.user_name, co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			//$sql = "SELECT co.country_name,c.first_name,c.last_name,c.client_id FROM " . DB_PREFIX ."client c , country co, address addr WHERE c.client_id = '".(int)$user_id."' AND addr.user_type_id = '3' AND c.address_id = addr.address AND co.country_id=addr.country_id ";
			$sql = "SELECT co.country_name, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	//Close : Listing
	
	public function getQuotationSelectedData($quotation_id,$selected,$user_type_id="",$user_id=""){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT $selected FROM " . DB_PREFIX ."product_quotation WHERE product_quotation_id = '".(int)$quotation_id."'";
			}else{
				$sql = "SELECT $selected FROM " . DB_PREFIX ."product_quotation WHERE added_by_user_id = '".(int)$user_id."' AND added_by_user_type_id = '".(int)$user_type_id."' AND product_quotation_id = '".(int)$quotation_id."'";
			}
		}else{
			$sql = "SELECT $selected FROM " . DB_PREFIX ."product_quotation WHERE product_quotation_id = '".(int)$quotation_id."'";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getOrder($order_id,$user_type_id='',$user_id=''){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT odr.*,adr.address,adr.address_2,adr.city,adr.state,adr.postcode FROM `" . DB_PREFIX ."order` odr INNER JOIN `" . DB_PREFIX ."address` adr ON (odr.shipping_address_id=adr.address_id)  WHERE odr.order_id = '".(int)$order_id."'";
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
					$str = ' OR ( added_by_user_id IN ('.$userEmployee.') AND added_by_user_type_id = 2 ))';
				}
				
				$sql = "SELECT odr.*,adr.address,adr.address_2,adr.city,adr.state,adr.postcode FROM `" . DB_PREFIX ."order` odr INNER JOIN `" . DB_PREFIX ."address` adr ON (odr.shipping_address_id=adr.address_id) WHERE ((added_by_user_id = '".(int)$set_user_id."' AND added_by_user_type_id = '".(int)$set_user_type_id."') $str AND order_id = '".(int)$order_id."' ";
				
			}
		}else{
			$sql = "SELECT odr.*,adr.address,adr.address_2,adr.city,adr.state,adr.postcode FROM `" . DB_PREFIX ."order` odr INNER JOIN `" . DB_PREFIX ."address` adr ON (odr.shipping_address_id=adr.address_id)  WHERE odr.order_id = '".(int)$order_id."'";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getOrderCurrency($order_id){
		$data = $this->query("SELECT o.order_currency,o.currency_rate FROM `" . DB_PREFIX ."order` o WHERE order_id= '".$order_id."'");	
		if($data->num_rows && !empty($data->row['order_currency'])){
			$return = array(
				'currency' => $data->row['order_currency'],
				'rate' => $data->row['currency_rate']
			);	
		}else{
			$return = array(
				'currency' => 'INR',
				'rate' => 1
			);
		}
		//printr($return);die;
		return $return;
	}
	
	public function getOrderProducts($order_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX ."order_product` WHERE order_id = '".$order_id."' ");
		//echo "SELECT * FROM `" . DB_PREFIX ."order_product` WHERE order_id = '".$order_id."' ";
		//die;
		if($data->num_rows){
			return $data->rows;	
		}else{
			return false;
		}
	}
	
	public function getOrderTotal(){
		
		$data = $this->query("SELECT SUM(total_price) as total_price,COUNT(op.*) as total_product FROM `" . DB_PREFIX ."order_product` op INNER JOIN `" . DB_PREFIX ."order` o ON (op.order_id=o.order_id)");
		
		if($data->num_rows){
			return $data->rows;	
		}else{
			return false;	
		}
		
	}
	
	public function getOrderProductMaterials($order_product_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX ."order_product_layer` WHERE order_product_id='".$order_product_id."'");
		if($data->num_rows){
			return $data->rows;	
		}else{
			return false;
		}
	}
	
	public function getQuotationMaterial($quotation_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."product_quotation_layer WHERE product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getMultiQuotationMaterial($quotation_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."multi_product_quotation_layer WHERE product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function insertProductImage($product_id,$img_name){
		$this->query("INSERT INTO " . DB_PREFIX ."order_product_image SET product_id = '".$product_id."', token = '".$_SESSION['token']."', image_name = '".$img_name."'");
		
	}
	
	public function getProductOptionDetails($spout_id,$product_id,$zipper_id,$accessorie_id){
		
		$spout_data = $this->query("SELECT spout_name FROM " . DB_PREFIX ."product_spout WHERE product_spout_id ='".$spout_id."' ");
		$product_data = $this->query("SELECT product_name FROM " . DB_PREFIX ."product WHERE product_id ='".$product_id."' ");
		$zipper_data = $this->query("SELECT zipper_name FROM " . DB_PREFIX ."product_zipper WHERE product_zipper_id ='".$zipper_id."' ");
		$accessorie_data = $this->query("SELECT product_accessorie_name FROM " . DB_PREFIX ."product_accessorie WHERE product_accessorie_id ='".$accessorie_id."' ");
		//echo "SELECT product_name FROM " . DB_PREFIX ."product WHERE product_id ='".$product_id."' ";
		if(isset($product_data->row['product_name']) && !empty($product_data->row['product_name'])) {
		$data = array(
		  'spout' => $spout_data->row['spout_name'],
		  'name' => $product_data->row['product_name'],
		  'zipper' => $zipper_data->row['zipper_name'],
		  'accessorie_name' => $accessorie_data->row['product_accessorie_name']
		);
		
		return $data;
		}
		else {
			return false;
		}
	}
	public function getProductOptionDetails2($spout_id,$product_id,$accessorie_id){
		
		$spout_data = $this->query("SELECT spout_name FROM " . DB_PREFIX ."product_spout WHERE product_spout_id ='".$spout_id."' ");
		$product_data = $this->query("SELECT product_name FROM " . DB_PREFIX ."product WHERE product_id ='".$product_id."' ");		
		$accessorie_data = $this->query("SELECT product_accessorie_name FROM " . DB_PREFIX ."product_accessorie WHERE product_accessorie_id ='".$accessorie_id."' ");
		if(isset($product_data->row['product_name']) && !empty($product_data->row['product_name'])) {
		$data = array(
		  'spout' => $spout_data->row['spout_name'],
		  'name' => $product_data->row['product_name'],
		  'accessorie_name' => $accessorie_data->row['product_accessorie_name']
		);
		
		return $data;
		}
		else {
			return false;
		}
	}
	public function getQuotationNumner($quotation_id){
		$sql = "SELECT quotation_number FROM " . DB_PREFIX ."product_quotation WHERE product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['quotation_number'];
		}else{
			return false;
		}
	}
	
	public function insertImages($images=array(),$order_product_id){
		foreach($images as $image){
			$this->query("INSERT INTO " . DB_PREFIX ."order_product_image SET order_product_id = '".$order_product_id."',image_name='".$image['image_name']."'");
		}	
	}
	
	public function UpdateImages($images=array(),$order_product_id){
		
		foreach($images as $image){
			if($image['order_id'] == $order_product_id) {
			$this->query("INSERT INTO " . DB_PREFIX ."order_product_image SET order_product_id = '".$order_product_id."',image_name='".$image['image_name']."'");
			}
		}	
	}
	
	public function removeImage($order_product_id){
		$this->query("DELETE FROM " . DB_PREFIX ."order_product_image WHERE order_product_id='".$order_product_id."' ");
	}
	
	public function removeOrderedProduct($order_product_id){
		$this->query("DELETE FROM " . DB_PREFIX ."order_product WHERE order_product_id='".$order_product_id."' ");
		$this->removeImage($order_product_id);
	}
	
	
	
	public function getUsercurrency($user_type_id,$user_id){
		$sql = "SELECT cur.* FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."currency cur ON (ad.country_id=cur.country_id)  WHERE ad.address_type_id = '0' AND ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_type_id."'";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCountryCurrency($country_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."currency WHERE country_id = '".(int)$country_id."' ";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getParentInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name, gres, valve_price FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres, valve_price FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
		}
		
		if($sql){
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function getUserInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name, gres, valve_price  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
		}elseif($user_type_id == 2){
			
			$data = $this->query("SELECT first_name, last_name, user_type_id, user_id FROM `" . DB_PREFIX . "employee` WHERE employee_id = '" .(int)$user_id. "'");
			$parentInfo = array();
			$return = array();
			if($data->num_rows){
				$parentInfo = $this->getParentInfo($data->row['user_type_id'],$data->row['user_id']);
				if($parentInfo){
					$return['company_name'] = $parentInfo['company_name'];
					$return['gres'] = $parentInfo['gres'];
					$return['valve_price'] = $parentInfo['valve_price'];
				}else{
					$return['company_name'] = '';
					$return['gres'] = '';
					$return['valve_price'] = '';
				}
			}else{
				$return['company_name'] = '';
				$return['gres'] = '';
				$return['valve_price'] = '';
			}
			$return['first_name'] = $data->row['first_name'];
			$return['last_name']  = $data->row['last_name'];
			
			return $return;
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name, gres, valve_price FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres, valve_price FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
		}
		
		if($sql){
			$data = $this->query($sql);
			if($data && $data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function getUserCountry($user_tyep_id,$user_id){
		$sql = "SELECT co.country_id, co.country_code, co.currency_id FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) WHERE ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_tyep_id."' AND ad.address_type_id = '0'";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	
   
	public function getCurrencyInfo($currency_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."currency WHERE currency_id = '".(int)$currency_id."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function generateQuotationNumber(){
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME   = 'product_quotation'");
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,5,'0',STR_PAD_LEFT);
		return $strpad;
	}
		
	public function convertPrice($price,$currencyPrice){
		if($currencyPrice > 0){
			return $this->numberFormate(($price / $currencyPrice),"3");
		}else{
			return $price;
		}
	}
	
	
	public function checkQuantity($quotation_quantity,$material=array()){
		
		$a = array();
		
		foreach($material as $material_id){
			$data = $this->query("SELECT material_name,minimum_quantity FROM " . DB_PREFIX ."product_material WHERE product_material_id='".$material_id."'");
			
			if($data->row['minimum_quantity'] > $quotation_quantity){
				
				$a[] = array(
					'quantity' => $data->row['minimum_quantity'],
					'name' => $data->row['material_name']				
				);
				return $a;
			}
		}
		return $a;
	}
	
	public function getCylinderBasePrice($currency_id){
		$data = $this->query("SELECT price FROM " . DB_PREFIX ."product_cylinder_base_price WHERE currency_id = '".(int)$currency_id."'");
		if(isset($data->row['price']) && $data->row['price']){
			return $data->row['price'];
		}else{
			return false;
		}
	}
	
	public function getQuantity($type,$quantity_type){
		if($type == 'p'){
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."product_quantity WHERE status = '1' AND is_delete = '0'");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}else{
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."roll_quantity WHERE quantity_type = '".$quantity_type."' AND status = '1' AND is_delete = '0'");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
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
	
	public function getQuotationToken(){
		$token = md5(uniqid(mt_rand(), true));
		$data = $this->query("SELECT product_quotation_id FROM " . DB_PREFIX ." product_quotation WHERE token = '".$token."'");
		if($data->num_rows){
			$token = $this->getQuotationToken();
		}
		return $token;
	}
	
	
	public function getOnlyQuotationQuantity($quotation_id){
		$data = $this->query("SELECT product_quotation_quantity_id, product_quotation_id, quantity FROM " . DB_PREFIX ."product_quotation_quantity WHERE product_quotation_id = '".(int)$quotation_id."'");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;	
		}
	}
	
	public function getQuotationQuantity($quotation_id){

		$data = $this->query("SELECT product_quotation_quantity_id, product_quotation_id, quantity FROM " . DB_PREFIX ."product_quotation_quantity WHERE product_quotation_id = '".(int)$quotation_id."'");
		//printr($data);die;
		$return = '';
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				$zdata = $this->query("SELECT product_quotation_price_id, product_quotation_id, product_quotation_quantity_id, transport_type, zipper_txt, valve_txt, total_price, gress_price, customer_gress_price FROM " . DB_PREFIX ."product_quotation_price WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' ");	
				
				if($zdata->num_rows){
					foreach($zdata->rows as $zipData){
						if($zipData['transport_type'] == 'sea'){
							$return['sea'][$qunttData['quantity']][] = array(
								'text' 		=> $zipData['zipper_txt'].' '.$zipData['valve_txt'],
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   =>  $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'customerGressPrice' => $zipData['customer_gress_price'],
							);
						}
						if($zipData['transport_type'] == 'air'){
							$return['air'][$qunttData['quantity']][] = array(
								'text' 		=> $zipData['zipper_txt'].' '.$zipData['valve_txt'],
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   => $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'customerGressPrice' => $zipData['customer_gress_price'],
							);
						}
						if($zipData['transport_type'] == 'pickup'){
							$return['pickup'][$qunttData['quantity']][] = array(
								'text' 		=> $zipData['zipper_txt'].' '.$zipData['valve_txt'],
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   => $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'customerGressPrice' => $zipData['customer_gress_price'],
							);
						}
					}
				}
			}
		}
		//printr($return);die;
		return $return;
	}
	
	public function getQuotationQuantityForMail($quotation_id){
		$data = $this->query("SELECT product_quotation_quantity_id, product_quotation_id, quantity FROM " . DB_PREFIX ."product_quotation_quantity WHERE product_quotation_id = '".(int)$quotation_id."'");
		//printr($data);die;
		$return = '';
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				$zdata = $this->query("SELECT product_quotation_price_id, product_quotation_id, product_quotation_quantity_id, transport_type, zipper_txt, valve_txt, total_price, gress_price, customer_gress_price FROM " . DB_PREFIX ."product_quotation_price WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' ");	
				//printr($zdata);
				if($zdata->num_rows){
					foreach($zdata->rows as $zipData){
						
						if($zipData['transport_type'] == 'sea'){
							$return[$qunttData['quantity']][$zipData['zipper_txt'].' '.$zipData['valve_txt']]['sea'] = array(
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   =>  $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'customerGressPrice' => $zipData['customer_gress_price'],
							);
						}
						if($zipData['transport_type'] == 'air'){
							$return[$qunttData['quantity']][$zipData['zipper_txt'].' '.$zipData['valve_txt']]['air'] = array(
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   => $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'customerGressPrice' => $zipData['customer_gress_price'],
							);
						}
						if($zipData['transport_type'] == 'pickup'){
							$return[$qunttData['quantity']][$zipData['zipper_txt'].' '.$zipData['valve_txt']]['pickup'] = array(
								//'pricePerPouch'	=> $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']) / $qunttData['quantity'],"3"),
								'totalPrice'	   => $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'customerGressPrice' => $zipData['customer_gress_price'],
							);
						}
					}
				}
			}
		}
		//printr($return);die;
		return $return;
	}
	
	public function allowCurrencyStatus($user_type_id,$user_id){
		$status = false;
		if($user_type_id == 1){
			$data = $this->query("SELECT allow_currency FROM " . DB_PREFIX ."user WHERE user_id = '".(int)$user_id."'");
		}elseif($user_type_id == 2){
			$employee = $this->query("SELECT user_type_id, user_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."'");
			if($employee->num_rows){
				$status = $this->allowCurrencyStatus($employee->row['user_type_id'],$employee->row['user_id']);
			}
		}elseif($user_type_id == 4){
			$data = $this->query("SELECT allow_currency FROM " . DB_PREFIX ."international_branch WHERE international_branch_id = '".(int)$user_id."'");
		}elseif($user_type_id == 5){
			$data = $this->query("SELECT allow_currency FROM " . DB_PREFIX ."associate WHERE associate_id = '".(int)$user_id."'");
		}
		if(isset($data) && $data->num_rows){
			$status = $data->row['allow_currency'];
		}
		return $status;
	}
	
	public function getCurrencys(){
		$data = $this->query("SELECT currency_code, currency_id FROM " . DB_PREFIX ."currency WHERE status = '1' AND is_delete = '0' ");
		if($data->num_rows){
			return  $data->rows;
		}else{
			return false;
		}
	}
	
	public function getUserCurrencyInfo($user_type_id,$user_id){
		$data = $this->query("SELECT cur.* FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."currency cur ON(co.currency_id = cur.currency_id) WHERE ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_type_id."' AND ad.address_type_id = '0'");
		//printr($data);die;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getPlusMinusQuantity($quantity,$type){
		if($type == 1){
			$data = $this->query("SELECT plus_minus_quantity FROM " . DB_PREFIX . "roll_quantity WHERE quantity ='".$quantity."'");
		}else{
			$data = $this->query("SELECT plus_minus_quantity FROM " . DB_PREFIX . "product_quantity WHERE quantity ='".$quantity."' ");
		}
		if($data->num_rows){
			return $data->row['plus_minus_quantity'];
		}else{
			return false;
		}
	}
	
	public function getSelectedCurrecyForQuotation($quotation_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "product_quotation_currency WHERE product_quotation_id ='".$quotation_id."' ORDER BY product_quotation_currency_id ASC LIMIT 0,1");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getQuotationCurrecy($selCurrencyId,$source){
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "product_quotation_currency WHERE product_quotation_currency_id ='".$selCurrencyId."' AND source = '".$source."' ");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function setQuotationCurrency($quotation_id,$ecurrencyId,$currencyRate,$source){
		$currencyInfo = $this->getCurrencyInfo(decode($ecurrencyId));
		//printr($currencyInfo);die;
		if($currencyInfo){
			if($currencyRate == 'NO'){
				$currencyRate = $currencyInfo['price'];
			}
			$this->query("INSERT INTO " . DB_PREFIX . "product_quotation_currency SET product_quotation_id = '".$quotation_id."', currency_id = '".$currencyInfo['currency_id']."', currency_code = '".$currencyInfo['currency_code']."', currency_rate = '".(float)$currencyRate."', currency_base_rate = '".$currencyInfo['price']."', source = '".$source."', date_added = NOW()");
			return $this->getLastId();
		}else{
			return false;
		}
	}
	
	public function getEmailHistories($quotation_id){
		
		$data = $this->query("SELECT qc.currency_code, qc.date_added, qc.source, qc.currency_rate, qe.to_email FROM " . DB_PREFIX . "product_quotation_email_history qe RIGHT JOIN product_quotation_currency qc ON(qe.product_quotation_currency_id = qc.product_quotation_currency_id) WHERE qc.	product_quotation_id ='".$quotation_id."' ORDER BY qc.date_added DESC ");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCountry($country_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."country WHERE country_id = '".(int)$country_id."' ");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getSpout($spout_id){
		$data = $this->query("SELECT spout_name, price, product_spout_id FROM " . DB_PREFIX . "product_spout WHERE product_spout_id = '".(int)$spout_id."' ");
		$return  = array();
		if($data->num_rows){
			$return['product_spout_id'] = $data->row['product_spout_id'];
			$return['spout_name'] = $data->row['spout_name'];
			$return['price'] = $data->row['price'];
		}
		return $return;
	}
	
	public function addQuotationNew($data){
		//printr($data);die;
		$data['country_id']=$data['shipping_country_id'];
		$data['transpotation'] =array(encode($data['transpotation']));		
		$data['quantity']=array($data['quantity']); 
		$data['spout']=array(encode($data['spout']));
		$data['zipper']=array(encode($data['zipper']));
		$data['accessorie']=array(encode($data['accessorie']));
		$data['valve']=array($data['valve']);
		$data['size'] = 0;
		//include_once("product_quotation.php");
		//$obj_quotation = new productQuotation;
		//$result = $obj_quotation->addQuotationFormula($data,'O');		
		include_once("multi_product_quotation.php");
		$obj_quotation = new multiProductQuotation;		
		$result = $obj_quotation->addQuotationFormula($data,'O');		
//		printr($userInfo);die;
		return $result;
		
	}
	
	public function addQuotation($data){
		//echo "dasdaad";die;
		include_once("product_quotation.php");
		
		$obj_quotation = new productQuotation;
		//printr($data);die;
		
		$post_height = (int)$data['height'];
		$post_width = (int)$data['width'];
		$gusset = (int)$data['gusset'];
		$product_id = (int)$data['product'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$userInfo = $obj_quotation->getUserInfo($user_type_id,$user_id);
		$test['post_data']=$data;
		$productName = getName('product','product_id',$product_id,'product_name');
		if(strtolower($productName) == "roll"){
			return "Error";
		}else{
			$formulla = $obj_quotation->formulaHeightWidthGusset($post_height,$post_width,$gusset,$product_id);
			$test['formulla']=$formulla;
			$actualHeight = $formulla['formula'];
			$height = $formulla['height'];
			$width = $formulla['width'];	 
			if($formulla['intoHeight'] == 1){
				$widthHeight = $height;
			}elseif($formulla['intoWidth'] == 1){
				$widthHeight = $width;
			}else{
				$widthHeight = $width;
			}
			
			if(isset($data['material']) && !empty($data['material']) && isset($data['thickness']) && !empty($data['thickness'])){
				$total_material = count($data['material']);
				$layerPrice = array();
				$checkCppMaterial = 0;
				$setQueryData = array();
				$materialName = '';
				for($p=0;$p<$total_material;$p++){
					$setNumber = $p.'0';
					$addingActualHeight = ( $setNumber / 1000 );
					$newLayerWiseHeight = ( $actualHeight + $addingActualHeight);
					$test['addingActualHeight']=$addingActualHeight;
					$test['newLayerWiseHeight']=$newLayerWiseHeight;
					$gsm =$obj_quotation->getMaterialGsm($data['material'][$p]);
					$test['gsm']=$gsm;
				//Thickness
						$checkCppMaterial = $obj_quotation->checkMaterial($data['material'][$p]);
						$thicknessPrice = $obj_quotation->getMaterialThickmessPrice($data['material'][$p],$data['thickness'][$p]);
						$test['thicknessPrice']=$thicknessPrice;
						$layerWiseGsmThickness[$p+1] = $obj_quotation->getLayerWiseGsmThickness($newLayerWiseHeight,$widthHeight,$data['thickness'][$p],$gsm);			
						$test['layerWiseGsmThickness']=$layerWiseGsmThickness;
						$layerPrice[$p+1] = $obj_quotation->getLayerPrice($layerWiseGsmThickness[$p+1],$thicknessPrice);
						$test['layerPrice']=$layerPrice;
						$materialName = getName('product_material','product_material_id',$data['material'][$p],'material_name');
						$setQueryData[] = " layer = '".(int)($p+1)."', material_id = '".(int)$data['material'][$p]."', material_gsm = '".(float)$gsm."', material_thickness = '".$data['thickness'][$p]."', material_price = '".(float)$thicknessPrice."', material_name = '".$materialName."', layer_wise_gsmthickness = '".(float)$layerWiseGsmThickness[$p+1]."', layer_wise_price = '".(float)$layerPrice[$p+1]."', date_added = NOW()";					
				}
				$totalLayer = count($data['material']);
				$layerCount = (isset($p))?$p:'';
				//total GSM THICKNESS
				$totalLayerGsmThickness = $obj_quotation->sumOfNumericArray($layerWiseGsmThickness);
				$test['totalLayerGsmThickness']=$totalLayerGsmThickness;
				//Total Layer wise Price
				$totalLayerPrice = $obj_quotation->sumOfNumericArray($layerPrice);
				$test['totalLayerPrice']=$totalLayerPrice;
				//printing option and printing effect || Ink Solvent 
				//change code for change function
				if(isset($data['printing']) && $data['printing'] == 1){
					$printing_option = "With Printing";
					$onlyInkPrice = $obj_quotation->getInkPrice1($data['make']);
					$test['onlyInkPrice']=$onlyInkPrice;
					$inkSolventPrice = $obj_quotation->getInkSolventPrice($layerWiseGsmThickness[1],1,$data['make']);
					$test['inkSolventPrice']=$inkSolventPrice;
					$printingEffectPrice = 0;
					if(isset($data['printing_effect']) && !empty($data['printing_effect']) && (int)$data['printing_effect'] > 0){
						$printingEffectPrice = $obj_quotation->getPrintingEffectPrice($data['printing_effect']);
					$test['printingEffectPrice']=$printingEffectPrice;
					}
					$inkPrice = (($onlyInkPrice + $printingEffectPrice) * $layerWiseGsmThickness[1]);
						$test['inkPrice']=$inkPrice;
				}else{
					$printing_option = "Without Printing";
					$onlyInkPrice = 0;
					$printingEffectPrice = 0;
					$inkPrice = 0;
					$inkSolventPrice = 0;
				}
				
				//Adhesive and adhesive solvent
				if($checkCppMaterial == 1 ){
					$adhesivePrice = $obj_quotation->getCppAdhesivePrice($layerWiseGsmThickness[1],$layerCount,1);
					$test['adhesivePrice']=$adhesivePrice;
				}else{
					$adhesivePrice = $obj_quotation->getAdhesivePrice($layerWiseGsmThickness[1],$layerCount,1,$data['make']);
					$test['adhesivePrice']=$adhesivePrice;
				}
				$adhesiveSolventPrice = $obj_quotation->getAdhesiveSolventPrice($layerWiseGsmThickness[1],$layerCount,1,$data['make']);
				$test['adhesiveSolventPrice']=$adhesiveSolventPrice;
				//Total Price : SUM of all price and calculate average price
				$totalPrice = $obj_quotation->numberFormate(($totalLayerPrice + $inkPrice + $inkSolventPrice + $adhesivePrice + $adhesiveSolventPrice),"5") ;
				$test['totalPrice']=$totalPrice;
				//Packing price / pouch
				$packingPerPouch = $obj_quotation->newPackingCharges($post_height,$post_width,$gusset,$product_id);
				    $test['packingPerPouch']=$packingPerPouch;
				$valveBasePrice = 0;
				
				$valveTxt = 'No valve';
				if(isset($data['valve']) && !empty($data['valve'])){
					$valveTxt = 'With Valve';
					$valveBasePrice = $userInfo['valve_price'];
				 	$test['valveBasePrice']=$valveBasePrice;
				 }
			//	printr($data);
				//check product weight is with zipper or without zipper
				$zipperWiseData = array();
				if(isset($data['zipper']) && !empty($data['zipper'])){
					$zipper_id = $data['zipper'];
					$zdata = $obj_quotation->getZipperInfo($zipper_id);
					$calculateZipperPrice = 0;
						if($zdata['price'] > 0 ){
							$calculateZipperPrice = $obj_quotation->getCalculateZipperPrice($product_id,$post_height,$post_width,$zdata['price']);
						 $test['calculateZipperPrice']=$calculateZipperPrice;
						 }
						$zipperWiseData = array(
							'product_zipper_id'	=> $zdata['product_zipper_id'],
							'zipperText'		   => $zdata['zipper_name'],
							'zipperBasePrice'	  => $zdata['price'],
							'calculatePrice'	   => $calculateZipperPrice		
						);
						 $test['zipperWiseData']=$zipperWiseData;
				}
				
				//printr($zipperWiseData);die;
				
				//Spout
				$spoutArray = array();
				if(isset($data['spout']) && !empty($data['spout'])){
						$spout_id = $data['spout'];
						$spoutInfo = $obj_quotation->getSpout($spout_id);
						if($spoutInfo){
							$spoutArray = array(
								'product_spout_id'	=> $spoutInfo['product_spout_id'],
								'spout_name'		  => $spoutInfo['spout_name'],
								'price'	  		   => $spoutInfo['price']
							);
						}
						 $test['spoutArray']=$spoutArray;
				}
				
				//printr($spoutArray);die;
				
				//Accessorie
				$accessorieArray = array();
				if(isset($data['accessorie']) && !empty($data['accessorie'])){
					$accessorie_id = $data['accessorie'];
						$accessorieInfo = $obj_quotation->getAccessorie($accessorie_id);
						if($accessorieInfo){
							$accessorieArray = array(
								'product_accessorie_id'	=> $accessorieInfo['product_accessorie_id'],
								'accessorie_name'	 => $accessorieInfo['accessorie_name'],
								'price'	  		   => $accessorieInfo['price']
							);
						} 
						$test['accessorieArray']=$accessorieArray;
				}
				
				
				
				
				//sdaaaa
				$zipperBasePrice = 0;
				$zipperCalculatePrice = 0;
				$zipperText = '';
				if(isset($data['zipper']) && !empty($data['zipper'])){
					$zdata = $obj_quotation->getZipperInfo($data['zipper']);
					if($zdata){
						$zipperText = $zdata['zipper_name'];
						$zipperBasePrice = $zdata['price'];
						if($zdata['price'] > 0 ){
							$zipperCalculatePrice = $obj_quotation->getCalculateZipperPrice($product_id,$post_height,$post_width,$zdata['price']);
						}
					}
				}
				
				$valveBasePrice = 0;
				$valveTxt = 'No valve';
				if(isset($data['valve']) && $data['valve'] == 1){
					$valveTxt = 'with valve';
					$valveBasePrice = $userInfo['valve_price'];
				}
				
				$spoutBasePrice = 0;
				$spoutTxt = '';
				if(isset($data['spout']) && !empty($data['spout'])){
					$spoutInfo = $obj_quotation->getSpout($data['spout']);
					if($spoutInfo){
						$spoutBasePrice = $spoutInfo['price'];
						$spoutTxt = $spoutInfo['spout_name'];
					}
				}
				
				//Accessorie
				$accessorieBasePrice = 0;
				$accessorieTxt = '';
				if(isset($data['accessorie']) && !empty($data['accessorie'])){
					$accessorieInfo = $obj_quotation->getAccessorie($data['accessorie']);
					if($accessorieInfo){
						$accessorieBasePrice = $accessorieInfo['accessorie_name'];
						$accessorieTxt = $accessorieInfo['price'];
					}
				}
				
				
				
					
				//COURIER AND TRANSPORT CALCULATION
				$transportByAir = 0;
				$transportBySea = 0;
				$transportByPickup = 0;
				if(isset($data['transpotation']) && !empty($data['transpotation'])){
					if($data['transpotation']== 'air'){
						$transportByAir = 1;
					}
					if($data['transpotation']== 'sea'){
						$transportBySea = 1;
					}
					if($data['transpotation']== 'pickup'){
						$transportByPickup = 1;
					}
				}else{
					$transportBySea = 1;
				}
				
				$shilmentCountry = $obj_quotation->getCountry($data['shipping_country_id']);
				if(strtolower($shilmentCountry['country_name']) == "india"){
					$transportByAir = 0;
					$transportBySea = 0;
					$transportByPickup = 1;
				}
				
				
				
				
				//courie calculation
				$courierChargeWithZipper = 0;
				$courierChargeWithoutZipper = 0;
				$fuleSurchargeWithZipper  = 0;
				$serviceTaxWithZipper = 0;
				$fuleSurchargeWithoutZipper  = 0;
				$serviceTaxWithoutZipper = 0;
				$handlingCharge = 0;
				$fual_surcharge_base_price = 0;
				$service_tax_base_price = 0;
				$handling_base_price = 0;
				//pradip stop
				if($transportByAir){
					$countryCourierData = $obj_quotation->getCountryCourier($data['shipping_country_id']);
					$fual_surcharge_base_price = $countryCourierData['fuel_surcharge'];
					$service_tax_base_price = $countryCourierData['service_tax'];
					$handling_base_price = $countryCourierData['handling_charge'];
				}
				//user gress value
				$userGress = $userInfo['gres'];
				$customer_gress = 0;
				$customer_email = '';
				
				//new code for multipale quantity
				$quantityArray = $data['quantity'];
				//printr($quantityArray);
			//	die;
				$quantityWiseData = array();
				
				//foreach($quantityArray as $key=>$eQuantity){
					//Transpotation / pouch
				
				$transportAndCoutierCharge = 0;	
				$transportPerPouch = 0;
				$test1['$transportPerPouch']=$transportPerPouch;
				if($transportBySea){
					$transportPerPouch = $transportAndCoutierCharge = $obj_quotation->getCalculateTransport($post_height,$post_width,$gusset);
				}
				$test1['$transportPerPouch1']=$transportPerPouch;

					//	printr($eQuantity);
						$quantity = decode($data['quantity']);
						//die;
						//Wastage
						$wastageBase = $obj_quotation->getWastage($quantity);
						$addingWastage = 0;
						if($post_height > 500){
							$addingWastage = 10;
						}
						$totalWastage = ($wastageBase + $addingWastage);
						$wastage = $obj_quotation->numberFormate((($totalPrice * $totalWastage) / 100),"5");
						//Final price with wastage
						$finalPrice = ($totalPrice + $wastage);
						// price per bag
						$pricePerBag = $obj_quotation->numberFormate(($finalPrice / 1000),"5");
						$optionPrice = $obj_quotation->numberFormate(($pricePerBag + $packingPerPouch ),"5");
						//Profit / pouch
						//$profit = $obj_quotation->getcalculateProfit($quantity,);
						$profit = $obj_quotation->getcalculateProfit($quantity,$data['product'],$post_height,$post_width,$gusset);
						$finalyPerPuchPrice = $obj_quotation->numberFormate(($optionPrice + $profit ),"5");
						$totalWeightWithZipper = 0;
						$totalWeightWithoutZipper=0;
						$courierChargeBaseWithZipper = 0;
						$courierChargeBaseWithoutZipper = 0;
						
						
						$optionPrice = $obj_quotation->numberFormate(($pricePerBag + $packingPerPouch + $transportPerPouch ),"5");
						
						$pricePerPuchWithOption = $obj_quotation->numberFormate(($optionPrice + $profit + $zipperCalculatePrice + $valveBasePrice ),"5");
				//total price without coutier charge
						$ftotalPrice = $obj_quotation->numberFormate(($pricePerPuchWithOption * $quantity),"5");
				
						//echo $ftotalPrice;die;
						
						if(($data['zipper'])==2)
						{
							//Total Weight without zipper
							$totalWeightWithoutZipper = $obj_quotation->getCalculateWeightWithoutZipper($totalLayerGsmThickness,$quantity);
							if($transportByAir){
								$courierChargeBaseWithoutZipper = $obj_quotation->getCountryCourierCharge($data['shipping_country_id'],$countryCourierData['courier_id'],$totalWeightWithoutZipper);
							}
						}
						else
						{
							//Total Weight with zipper
							$totalWeightWithZipper = $obj_quotation->getCalculateWeightWithZipper($totalLayerGsmThickness,$quantity);
							if($transportByAir){
								$courierChargeBaseWithZipper = $obj_quotation->getCountryCourierCharge($data['shipping_country_id'],$countryCourierData['courier_id'], $totalWeightWithZipper);
							}
						}					
						
						$test['totalWeightWithoutZipper']=$totalWeightWithoutZipper;
						$test['totalWeightWithZipper']=$totalWeightWithZipper;
						$test['courierChargeBaseWithoutZipper']=$courierChargeBaseWithoutZipper;
						$test['courierChargeBaseWithZipper']=$courierChargeBaseWithZipper;
						//courier charge
						$courierBasePriceQuantityWise = array();
					if($transportByAir){
								$courierBasePriceQuantityWise[$quantity] = array(
									'withZipepr'  => $courierChargeBaseWithZipper, 
									'noZipper'	=> $courierChargeBaseWithoutZipper,
								);
								
								if($fual_surcharge_base_price > 0){
									if(($data['zipper'])==2)
									{
										$fuleSurchargeWithoutZipper = (($courierChargeBaseWithoutZipper * $fual_surcharge_base_price) / 100);
									}
									else
									{
										$fuleSurchargeWithZipper = (($courierChargeBaseWithZipper * $fual_surcharge_base_price) / 100);
									}
								}
								if($service_tax_base_price > 0){
									if(($data['zipper'])==2)
									{
										$courierCrhgFuleWithoutZipper = ($courierChargeBaseWithoutZipper + $fuleSurchargeWithoutZipper);
										$serviceTaxWithoutZipper = (($courierCrhgFuleWithoutZipper * $service_tax_base_price) / 100);
									}
									else
									{
										$courierCrhgFuleWithZipper = ($courierChargeBaseWithZipper + $fuleSurchargeWithZipper);
										$serviceTaxWithZipper = (($courierCrhgFuleWithZipper * $service_tax_base_price) / 100);
									}
								}
								if($handling_base_price > 0){
									$handlingCharge = $handling_base_price;
								}
								//courier charge with zipper								
								if(($data['zipper'])==2)
								{
								//courier charge without zipper
									$courierChargeWithoutZipper = $obj_quotation->numberFormate(($courierChargeBaseWithoutZipper + $fuleSurchargeWithoutZipper + $serviceTaxWithoutZipper + $handlingCharge),"3");
								}
								else
								{
									$courierChargeWithZipper = $obj_quotation->numberFormate(($courierChargeBaseWithZipper + $fuleSurchargeWithZipper + $serviceTaxWithZipper + $handlingCharge),"3");
								
								}								
							}
							
						$taxation='';
						$taxation_data='';
						if(isset($data['shipping_country_id']) && !empty($data['shipping_country_id']) && $data['shipping_country_id'] ==111)
						{
							$taxation= $data['taxation'];
							$sql = "SELECT excies,cst,vat,taxation_id FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' ORDER BY taxation_id DESC LIMIT 1";
							$data_tax = $obj_quotation->query($sql);
							$taxation_data=$data_tax->row;
						}
					//	printr($zipperWiseData);die;
						$zipperData = array();
						$zipperValue = $zipperWiseData;
						$valve_text= 'no Valve';
						$courierCharge=$courierChargeWithoutZipper;		
						$spoutPrice=0;	
						$accessoriePrice=0;		
						$courierChargeInFormula = $courierChargeWithoutZipper;		
						$withValvePrice = 0;
						$priceWithTransport = 0;
						$bySea = '';
						$byAir = '';
						$byPickup = '';	
						if($zipperValue['zipperBasePrice'] > 0){
							$courierCharge=$courierChargeWithZipper;	
							$courierChargeInFormula = $courierChargeWithZipper;	
						}
						//	echo	$courierCharge;
					
						$test1[$quantity]['$transportPerPouch2']=$transportPerPouch;
						$test[' $transportPerPouch']= $transportPerPouch;
						if(isset($spoutArray) && $spoutArray['price'] !=  0.000){
							$spoutPrice=$spoutArray['price'];
							if($transportByAir){
								 $courierChargeInFormula =  $courierChargeInFormula * 1.4;
							}
							if($transportBySea){
								 $transportPerPouch = $transportPerPouch + 0.10;
							}
						}
					//	echo  $courierChargeInFormula;die;
						$test1[$quantity]['$transportPerPouch3']=$transportPerPouch;
						//$test[' $transportPerPouch1']= $transportPerPouch;

						if(isset($accessorieArray) && !empty($accessorieArray) && $accessorieArray['price'] !=0.0000){
							$accessoriePrice=$accessorieArray['price'];
						}
						if(isset($data['valve']) && $data['valve']==1){
							$valve_text= 'with Valve';
						}
						if($transportByAir){
							//printr($zipperValue);
							$withValvePrice = $obj_quotation->numberFormate((($finalyPerPuchPrice + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity ) + $courierChargeInFormula);
							$byAir['totalPriceByAir'] = $withValvePrice;	
						}
						//echo $finalyPerPuchPrice .'+'. $transportPerPouch.'+'. $zipperValue['calculatePrice'] .'+'. $valveBasePrice .'+'. $spoutPrice .'+'.$accessoriePrice .'*'. $quantity.'+'.$courierChargeInFormula ;die;
						if($transportBySea){
							$priceWithTransport = $obj_quotation->numberFormate(($finalyPerPuchPrice + $transportPerPouch + $zipperValue['calculatePrice'] +  $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity );
							$bySea['totalPriceBySea'] = $priceWithTransport;
						}
						if($transportByPickup){
							$priceWithPickup = $obj_quotation->numberFormate(($finalyPerPuchPrice + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity );
							$byPickup['totalPriceByPickup'] = $priceWithPickup;
						}
						//echo $transportPerPouch;die;
						$test1['$transportPerPouch4']=$transportPerPouch;
						$zipperData[] = array(
								'zip_text'		=> $zipperValue['zipperText'],
								'valve_text'      => $valve_text,
								'spout_txt'	=> $spoutArray['spout_name'],
								'spout_price'		=> $spoutArray['price'],
								//'accessorie_txt'	=> $accessorieArray['accessorie_name'],
								//'accessorie_price'		=> $accessorieArray['price'],
								'transportPerPouch'	=> $transportPerPouch,									
								'courierCharge' => $courierChargeInFormula,
								'calculateZipperPrice'	=> $zipperValue['calculatePrice'],						
								'BySea'	=>  $bySea,
								'ByAir'	=> $byAir,
								'ByPickup' => $byPickup,
						);
							//printr($byAir);die;
						$test1['$transportPerPouch5']=$transportPerPouch;
							
				//	printr($zipperData);	
					//	echo  $courierChargeInFormula;die;
					//spout
					$spoutQuantityWiseData[] = array(
						'spout_txt'	=> $spoutArray['spout_name'],
						'price'		=> $spoutArray['price'],
					
					); 
					//Accessorie
					$accessorieQuantityWiseData[] = array(
						'accessorie_txt'	=> '',//$accessorieArray['accessorie_name'],
						'price'		=> ''//$accessorieArray['price'],
					);
					//gress persontage price
					//store quantity wise information
					$quantityWiseData[$quantity] = array(
						'wastageBase'	=> $wastageBase,
						'addingWastage'  => $addingWastage,
						'wastage'		=> $wastage,
						'nativePricePerBag' => $pricePerBag,
						'totalWeightWithZipper' => $totalWeightWithZipper,
						'totalWeightWithoutZipper' => $totalWeightWithoutZipper,
						'profit'		 => $profit,
						'pricePerBag'   => $pricePerBag,
						'wastageBasePrice' => $wastageBase,
						'wastageAddingPint' => $addingWastage,
						'zipperData'	=> $zipperData,
						'spoutData'     => $spoutQuantityWiseData,
						'accessorieData'	=> $accessorieQuantityWiseData,
					);
				//}
				
				//printr($quantityWiseData);die;
				//printr
				
				$test1['$transportPerPouch6']=$transportPerPouch;
				$test['quantityWiseData']=$quantityWiseData;
				$userCountry = $obj_quotation->getUserCountry($user_type_id,$user_id);
				//Extra tool Price
				$tool_price = $obj_quotation->getToolPrice($post_width,$gusset,$product_id);
				
				//deep(New Currency Price)
				if($user_type_id==1){
					$userCurrency = $obj_quotation->getCurrencyInfo($user_id);
				}else{
					$userCurrency =  $obj_quotation->getUserWiseCurrency($user_type_id,$user_id);
				}
				//Cylinder Price
				$cylinderPrice = $obj_quotation->getCalculateCylinderPrice($post_height,$post_width,$gusset,$data['shipping_country_id'],$product_id);
				$cylinderCurrencyPrice = $cylinderPrice;
			//	printr($userCurrency);
				if($user_type_id==1){
					$currCode ='INR'; 										
				}
				else{
					$currCode=$userCurrency['currency_code'];
				}
				if($userCurrency['cylinder_rate']){
						$cylinderCurrencyPrice = ($cylinderPrice / $userCurrency['cylinder_rate']);
						$tool_price = ($tool_price / $userCurrency['cylinder_rate']);
				}			
			//	echo $cylinderCurrencyPrice.'<br>';		
					//$cylinderCurrencyMinPrice = $obj_quotation->getCylinderBasePrice($userCurrency['counrty_id']);
					$cylinderCurrencyMinPrice = $obj_quotation->getCylinderBasePrice($currCode);
				//	echo $cylinderCurrencyMinPrice;
				if($cylinderCurrencyMinPrice && $cylinderCurrencyMinPrice >= $cylinderCurrencyPrice ){
					$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
				}else{
					$cylinderCurrencyBasePrice = $cylinderCurrencyPrice;
				} 
				//echo $cylinderCurrencyPrice.'<br>min '.$cylinderCurrencyMinPrice;
				if($cylinderCurrencyPrice <= $cylinderCurrencyMinPrice)
				{
					$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
				}
				
		//echo $cylinderCurrencyBasePrice;die;
			//	printr($userCountry);
				if($userCountry){
					$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';	
					//echo $countryCode ;
					$newQuotaionNumber = $obj_quotation->generateQuotationNumber();
					$quotation_number = $countryCode.$newQuotaionNumber;
				}else{
					$newQuotaionNumber = $obj_quotation->generateQuotationNumber();
					$quotation_number = 'IN'.$newQuotaionNumber;
				}
				//echo $quotation_number;die;
				$printingEffectName = getName('printing_effect','printing_effect_id',$data['printing_effect'],'effect_name');
				//printr($printingEffectName);die;
				$productName = getName('product','product_id',$data['product'],'product_name');
				//Deep Modified
				if($user_type_id!=1){
					if($userCurrency['currency_code'] && $userCurrency['product_rate']){
						$currency = $userCurrency['currency_code'];
						$currencyPrice = $userCurrency['product_rate'];
					}else{
						$currency = 'INR';
						$currencyPrice = '1';
					}
				}else{
					if( $userCurrency['cylinder_rate']){
						$currency ='INR';
						$currencyPrice = $userCurrency['cylinder_rate'];
					}else{
						$currency = 'INR';
						$currencyPrice = '1';
					}
				}
				//	printr($courierBasePriceQuantityWise);die;
		//$test1['$transportPerPouch7']=$transportPerPouch;
		//printr($test);die;
				/*$sql = "INSERT INTO ".DB_PREFIX."product_quotation SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect_id = '".(int)$data['printing_effect']."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."', 				packing_price = '".(float)$packingPerPouch."', valve_price = '".$valveBasePrice."', gress_percentage = '".$userGress."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."', tool_price = '".(float)$tool_price."', use_device = '".getDevice()."', status = '0', date_added = NOW(), added_by_shipping_country_id = '".$userCountry['shipping_country_id']."', currency = '".$currency."', currency_price = '".$currencyPrice."', customer_name = '". $data['customer']."', customer_email = '".$customer_email."', customer_gress_percentage = '".$customer_gress."', shipment_shipping_country_id = '".$data['shipping_country_id']."', added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."', quotation_number = '".$quotation_number."'";
				//printr($sql);die;
				$obj_quotation->query($sql);
				$productQuatiationId = $obj_quotation->getLastId();*/
				
				
				
				
				
				
				//if(isset($productQuatiationId) && (int)$productQuatiationId > 0 ){
					//quotation currency
					if($customer_email && decode($data['sel_currency']) > 0 ){ //&& $data['sel_currency_rate'] > 0
						$selCurrecy = $obj_quotation->getCurrencyInfo(decode($data['sel_currency']));
						if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
							$selCurrencyRate = $data['sel_currency_rate'];
						}else{
							$selCurrencyRate = $selCurrecy['price'];
						}
						//$obj_quotation->query("INSERT INTO ".DB_PREFIX."product_quotation_currency SET product_quotation_id = '".$productQuatiationId."', currency_id = '".$selCurrecy['currency_id']."', currency_code = '".$selCurrecy['currency_code']."', 	currency_rate = '".$selCurrencyRate."', currency_base_rate = '".$selCurrecy['price']."', date_added = NOW() ");
					}
					//INSERT QUOTATION QUANTITY TABLE 
					//die;
					
					if(isset($quantityWiseData) && !empty($quantityWiseData)){
						foreach($quantityWiseData as $quantity=>$quantityValue){
							
						//	echo "INSERT INTO ".DB_PREFIX."product_quotation_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_adding = '".$quantityValue['addingWastage']."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit = '".$quantityValue['profit']."', total_weight_without_zipper = '".$quantityValue['totalWeightWithoutZipper']."', total_weight_with_zipper = '".$quantityValue['totalWeightWithZipper']."', date_added = NOW()";die;
						//	printr($quantityValue);die;
							//$obj_quotation->query("INSERT INTO ".DB_PREFIX."product_quotation_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_adding = '".$quantityValue['addingWastage']."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit = '".$quantityValue['profit']."', total_weight_without_zipper = '".$quantityValue['totalWeightWithoutZipper']."', total_weight_with_zipper = '".$quantityValue['totalWeightWithZipper']."', date_added = NOW()");
							//$productQuatiationQuantityId = $obj_quotation->getLastId();
							//printr($quantityValue['zipperData']);die;
							//zipperData
							
							//printr($quantityValue);die;
							if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
							//	printr($spoutArray);
								foreach($quantityValue['zipperData'] as $zipData){
									//printr($quantityValue);die;
								//	echo  "INSERT INTO ".DB_PREFIX."product_quotation_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', zipper_txt = '".$zipData['zip_text']."', valve_txt = '".$zipData['valve_text']."', date_added = NOW(), zipper_price = '".$zipData['calculateZipperPrice']."',  ";
									//die;
									//$pricesql = "INSERT INTO ".DB_PREFIX."product_quotation_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', zipper_txt = '".$zipData['zip_text']."', valve_txt = '".$zipData['valve_text']."', date_added = NOW(), zipper_price = '".$zipData['calculateZipperPrice']."', spout_txt = '".$zipData['spout_txt']."', spout_base_price = '".$zipData['spout_price']."', accessorie_txt = '".$zipData['accessorie_txt']."', accessorie_base_price = '".$zipData['accessorie_price']."', ";
									//echo $pricesql;die;
									 if(isset($zipData['BySea']) && !empty($zipData['BySea'])){
										$customerGressPrice = 0; 
										$gressPrice = 0;
										$totalPricWithExcies =0;
										$totalPriceWithTax = 0;
										$tax_type='';
										$tax_percentage=0;
										$totalPriceForTax = 0;
										$excies = 0;
										if($customer_gress > 0){
											$customerGressPrice = $obj_quotation->numberFormate((($zipData['BySea']['totalPriceBySea'] * $customer_gress) / 100),"3");
										}
										if($userGress > 0){
											$gressPrice = $obj_quotation->numberFormate((($zipData['BySea']['totalPriceBySea'] * $userGress) / 100),"3");
										}
										if(isset($taxation_data) && !empty($taxation_data))
										{
											$totalPriceForTax = $zipData['BySea']['totalPriceBySea']+$gressPrice+$customerGressPrice;
											$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
											$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
											$excies =$taxation_data['excies'];
											$tax_type=$taxation;
											$tax_percentage=$taxation_data[$taxation];																				
										}	
										//$obj_quotation->query(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$transportPerPouch."', total_price = '".$zipData['BySea']['totalPriceBySea']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
										//die;
										 $total_order_price = $zipData['BySea']['totalPriceBySea'];
									 }
									 if(isset($zipData['ByAir']) && !empty($zipData['ByAir'])){
										 //printr($zipData);die;
										 $customerGressPrice = 0; 
										 $gressPrice = 0;
										 $totalPricWithExcies =0;
										$totalPriceWithTax = 0;
										$tax_type='';
										$tax_percentage=0;
										$totalPriceForTax = 0;
										$excies = 0;
										 if($customer_gress > 0){
										 	$customerGressPrice = $obj_quotation->numberFormate((($zipData['ByAir']['totalPriceByAir'] * $customer_gress) / 100),"3");
										}
										 if($userGress > 0){
											$gressPrice = $obj_quotation->numberFormate((($zipData['ByAir']['totalPriceByAir'] * $userGress) / 100),"3");
										}
										if(isset($taxation_data) && !empty($taxation_data))
										{
											$totalPriceForTax = $zipData['ByAir']['totalPriceByAir']+$gressPrice+$customerGressPrice;
											$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
											$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
											$excies =$taxation_data['excies'];
											$tax_type=$taxation;
											$tax_percentage=$taxation_data[$taxation];																				
										}	
										$courierBasePriceWithZipper = 0;
										$courierBasePriceNoZipper = 0;
									
										if(isset($courierBasePriceQuantityWise[$quantity])){
											$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
											$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
										}
									//	echo " $pricesql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$zipData['courierCharge']."', transport_price = '0', total_price = '".$zipData['ByAir']['totalPriceByAir']."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ";die;
										//$obj_quotation->query(" $pricesql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$zipData['courierCharge']."', transport_price = '0', total_price = '".$zipData['ByAir']['totalPriceByAir']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
										
										 $total_order_price = $zipData['ByAir']['totalPriceByAir'];
									}
									
									if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup'])){
										 $customerGressPrice = 0; 
										 $gressPrice = 0;
										 $totalPricWithExcies =0;
										$totalPriceWithTax = 0;
										$tax_type='';
										$tax_percentage=0;
										 $totalPriceForTax =0;
										 $excies = 0;
										 if($customer_gress > 0){
										 	$customerGressPrice = $obj_quotation->numberFormate((($zipData['ByPickup']['totalPriceByPickup'] * $customer_gress) / 100),"3");
										}
										 if($userGress > 0){
											$gressPrice = $obj_quotation->numberFormate((($zipData['ByPickup']['totalPriceByPickup'] * $userGress) / 100),"3");
										}
										//printr($taxation_data);
										 if(isset($taxation_data) && !empty($taxation_data))
										{
											$totalPriceForTax = $zipData['ByPickup']['totalPriceByPickup']+$gressPrice+$customerGressPrice;
											$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
											$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
											$excies =$taxation_data['excies'];
											$tax_type=$taxation;
											$tax_percentage=$taxation_data[$taxation];																				
										}	
										// echo " $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$zipData['ByPickup']['totalPriceByPickup']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ";die;
										// $obj_quotation->query(" $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$zipData['ByPickup']['totalPriceByPickup']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."' ");
										
									 $total_order_price = $zipData['ByPickup']['totalPriceByPickup'];
									}									
								}
							}
						}
					}
					
					
					$makeup_query = $this->query("SELECT make_name FROM ".DB_PREFIX."product_make WHERE make_id = '".$data['make']."' ");
				//echo $total_order_price;die;
					$this->query("INSERT INTO ".DB_PREFIX."order_product SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', quantity = '".(int)$quantity."',makeup = '".$makeup_query->row['make_name']."', layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."', packing_price = '".(float)$packingPerPouch."', valve_price = '".$valveBasePrice."', valve_txt = '".$valveTxt."', zipper_txt = '".$zipperText."', zipper_price = '".$zipperCalculatePrice."', spout_txt = '".$spoutTxt."', spout_price = '".$spoutBasePrice."', accessorie_txt = '".$accessorieTxt."', accessorie_price = '".$accessorieBasePrice."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."', native_price_per_bag = '".$pricePerBag."', wastage = '".$wastage."', profit = '".$profit."', total_weight_without_zipper = '".$totalWeightWithoutZipper."', total_weight_with_zipper = '".$totalWeightWithZipper."', transport_type = '".$data['transpotation']."', transport_charge = '".$transportAndCoutierCharge."', total_price = '".$total_order_price."', product_note = '".$data['product_note']."', product_instruction = '".$data['product_special_instruction']."', date_added = NOW(), date_modify=NOW(), is_delete=0, status=1 ");
				
				
				/*	$this->query("INSERT INTO ".DB_PREFIX."order_product SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', quantity = '".(int)$quantity."',makeup = '".$makeup_query->row['make_name']."', layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."', packing_price = '".(float)$packingPerPouch."', valve_price = '".$valveBasePrice."', valve_txt = '".$valveTxt."', zipper_txt = '".$zipperText."', zipper_price = '".$zipperCalculatePrice."', spout_txt = '".$spoutTxt."', spout_price = '".$spoutBasePrice."', accessorie_txt = '".$accessorieTxt."', accessorie_price = '".$accessorieBasePrice."', color = '".decode($data['color'])."', style = '".decode($data['style'])."', volume = '".decode($data['volume'])."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."', native_price_per_bag = '".$pricePerBag."', wastage = '".$wastage."', profit = '".$profit."', total_weight_without_zipper = '".$totalWeightWithoutZipper."', total_weight_with_zipper = '".$totalWeightWithZipper."', transport_type = '".$data['transpotation']."', transport_charge = '".$transportAndCoutierCharge."', total_price = '".$total_order_price."', product_note = '".$data['product_note']."', product_instruction = '".$data['product_special_instruction']."', date_added = NOW(), date_modify=NOW(), is_delete=0, status=1 ");
				*/
					$orderProductId = $this->getLastId();
					
					//die;
					//BASE PRICE
					$inkDefaultPrice = $obj_quotation->getInkPrice1($data['make']);
					$inkSolventDefaultPrice = $obj_quotation->getInkSolventPrice('',0,$data['make']);
					$printingEffectDefaultPrice = $obj_quotation->getPrintingEffectPrice($data['printing_effect']);
					$adhesiveDefaultPrice = $obj_quotation->getAdhesivePrice('','',0,$data['make']);
					$adhesiveSolventDefaultPrice = $obj_quotation->getAdhesiveSolventPrice('','',0,$data['make']);
					$cppAdhesiveDefaultPrice = $obj_quotation->getCppAdhesivePrice('','',0);
					//$packingWidthDefaultPrice = $obj_quotation->getDefaultPackingWidthPrice($post_width);
					//$packingHeightDefaultPrice = $obj_quotation->getDefaultPackingHeightPrice($post_height);
					$transportWidthDefaultPrice = $obj_quotation->getDefaultTransportWidthPrice($post_width,$gusset);
					$transportHeightDefaultPrice = $obj_quotation->getDefaulttransportHeightPrice($post_height);
					$cylinderVendorDefaultPrice = $obj_quotation->getCylinderVendorPrice($data['shipping_country_id']);
					//inert base price at a time product quotaion add than taht time real price. use for history
					//$obj_quotation->query("INSERT INTO ".DB_PREFIX."product_quotation_base_price SET product_quotation_id = '".(int)$productQuatiationId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$packingPerPouch."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
					//die;
					//INAERT DATA FOR LAYER WISE
					
					//inert base price at a time product quotaion add than taht time real price. use for history
					$this->query("INSERT INTO ".DB_PREFIX."order_product_base_price SET order_product_id = '".(int)$orderProductId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$packingPerPouch."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
					
					if(isset($setQueryData) && !empty($setQueryData)){
						foreach($setQueryData as $key=>$setquery){
							$setSql = "INSERT INTO ".DB_PREFIX."order_product_layer SET order_product_id = '".(int)$orderProductId."', ".$setquery;
							$this->query($setSql);
						}
					}
					
					$returnArray = array();
					$returnArray = array(
						'order_product_id' => $orderProductId,
						'total_price'	   => $total_order_price,
					);
					return $returnArray;
					
				//}
			}
		}
	}
	
	
	
	
	
	
	
	
	
	//### ORDER CALCULATION
	public function calculateOrderProduct($data){
		include_once("product_quotation.php");
		$obj_quotation = new productQuotation;
		
		//printr($data);die;
		$data['country_id'] = $data['shipping_country_id'];
		$post_height = (int)$data['height'];
		$post_width = (int)$data['width'];
		$gusset = (int)$data['gusset'];
		$make_id = (int)$data['make'];
		$quantity = decode($data['quantity']);
		$product_id = (int)$data['product'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$userInfo = $this->getUserInfo($user_type_id,$user_id);
		$product_note = $data['product_note'];
		$product_instruction = $data['product_special_instruction'];
		$productName = getName('product','product_id',$product_id,'product_name');
		if(strtolower($productName) == "roll"){
			return "Error";
		}else{
			$formulla = $obj_quotation->formulaHeightWidthGusset($post_height,$post_width,$gusset,$product_id);
			$actualHeight = $formulla['formula'];
			$height = $formulla['height'];
			$width = $formulla['width'];	
			if($formulla['intoHeight'] == 1){
				$widthHeight = $height;
			}elseif($formulla['intoWidth'] == 1){
				$widthHeight = $width;
			}else{
				$widthHeight = $width;
			}
			if(isset($data['material']) && !empty($data['material']) && isset($data['thickness']) && !empty($data['thickness'])){		
				$total_material = count($data['material']);
				$layerPrice = array();
				$checkCppMaterial = 0;
				$setQueryData = array();
				$materialName = '';
				for($p=0;$p<$total_material;$p++){
					$setNumber = $p.'0';
					$addingActualHeight = ( $setNumber / 1000 );
					$newLayerWiseHeight = ( $actualHeight + $addingActualHeight);
					//GSM
					$gsm =$this->getMaterialGsm($data['material'][$p]);
					//Thickness
					//if($p == 1){
						//if CPP material then add cpp price into adhesive and adhesive solvent	
						$checkCppMaterial = $obj_quotation->checkMaterial($data['material'][$p]);
						$thicknessPrice = $obj_quotation->getMaterialThickmessPrice($data['material'][$p],$data['thickness'][$p]);
						//echo $newLayerWiseHeight."===".$widthHeight."===".$data['thickness'][$p]."===".$gsm."===<br>";
						$layerWiseGsmThickness[$p+1] = $obj_quotation->getLayerWiseGsmThickness($newLayerWiseHeight,$widthHeight,$data['thickness'][$p],$gsm);	
						$layerPrice[$p+1] = $obj_quotation->getLayerPrice($layerWiseGsmThickness[$p+1],$thicknessPrice);
						$materialName = getName('product_material','product_material_id',$data['material'][$p],'material_name');
						$setQueryData[] = " layer = '".(int)($p+1)."', material_id = '".(int)$data['material'][$p]."', material_gsm = '".(float)$gsm."', material_thickness = '".$data['thickness'][$p]."', material_price = '".(float)$thicknessPrice."', material_name = '".$materialName."', layer_wise_gsmthickness = '".(float)$layerWiseGsmThickness[$p+1]."', layer_wise_price = '".(float)$layerPrice[$p+1]."', date_added = NOW()";
						//echo $layerWiseGsmThickness[$p+1];die;
					//}
				}
				$totalLayer = count($data['material']);
				$layerCount = (isset($p))?$p:'';
				//total GSM THICKNESS
				$totalLayerGsmThickness = $obj_quotation->sumOfNumericArray($layerWiseGsmThickness);
				//Total Layer wise Price
				$totalLayerPrice = $obj_quotation->sumOfNumericArray($layerPrice);
				//printing option and printing effect || Ink Solvent 
				if(isset($data['printing']) && $data['printing'] == 1){
					$printing_option = "With Printing";
					$onlyInkPrice = $obj_quotation->getInkPrice1($make_id);
					$inkSolventPrice = $obj_quotation->getInkSolventPrice($layerWiseGsmThickness[1],1,$make_id);
					$printingEffectPrice = 0;
					if(isset($data['printing_effect']) && !empty($data['printing_effect']) && (int)$data['printing_effect'] > 0){
						$printingEffectPrice = $obj_quotation->getPrintingEffectPrice($data['printing_effect']);
					}
					$inkPrice = (($onlyInkPrice + $printingEffectPrice) * $layerWiseGsmThickness[1]);
				}else{
					$printing_option = "Without Printing";
					$onlyInkPrice = 0;
					$printingEffectPrice = 0;
					$inkPrice = 0;
					$inkSolventPrice = 0;
				}
				//Adhesive and adhesive solvent
				if($checkCppMaterial == 1 ){
					$adhesivePrice = $obj_quotation->getCppAdhesivePrice($layerWiseGsmThickness[1],$layerCount,1);
				}else{
					$adhesivePrice = $obj_quotation->getAdhesivePrice($layerWiseGsmThickness[1],$layerCount,1,$make_id);
				}
				$adhesiveSolventPrice = $obj_quotation->getAdhesiveSolventPrice($layerWiseGsmThickness[1],$layerCount,1,$make_id);
				//Total Price : SUM of all price and calculate average price
				$totalPrice = $this->numberFormate(($totalLayerPrice + $inkPrice + $inkSolventPrice + $adhesivePrice + $adhesiveSolventPrice),"5") ;
				
				//Wastage
				$wastageBase = $obj_quotation->getWastage($quantity);
				
				$addingWastage = 0;
				if($post_height > 500){
					$addingWastage = 10;
				}
				$totalWastage = ($wastageBase + $addingWastage);
				$wastage = $obj_quotation->numberFormate((($totalPrice * $totalWastage) / 100),"5");
		
				//Final price with wastage
				$totalPriceWithoutOption = ($totalPrice + $wastage);
				// price per bag
				$pricePerBag = $obj_quotation->numberFormate(($totalPriceWithoutOption / 1000),"5");
				//Packing price / pouch
				$packingPerPouch = $obj_quotation->newPackingCharges($post_height,$post_width,$gusset,$product_id);
				//Chekc transfor type
				$transportByAir = 0;
				$transportBySea = 0;
				$transportByPickup = 0;
				if(isset($data['transpotation']) && !empty($data['transpotation']) && count($data['transpotation']) > 0 ){
					if($data['transpotation'] == 'air'){
						$transportByAir = 1;
					}else if($data['transpotation'] == 'sea'){
						$transportBySea = 1;
					}elseif($data['transpotation'] == 'pickup'){
						$transportByPickup = 1;
					}else{
						$transportBySea = 1;
					}
				}else{
					$transportBySea = 1;
				}
				
				$shilmentCountry = $obj_quotation->getCountry($data['country_id']);
				if(strtolower($shilmentCountry['country_name']) == "india"){
					$transportByAir = 0;
					$transportBySea = 0;
					$transportByPickup = 1;
					$data['transpotation'] = 'pickup';
				}
				
				//Transpotation / pouch
				$transportAndCoutierCharge = 0;
				$transportPerPouch = 0;
				if($transportBySea){
					$transportPerPouch = $transportAndCoutierCharge = $obj_quotation->getCalculateTransport($post_height,$post_width,$gusset);
				}
				$optionPrice = $obj_quotation->numberFormate(($pricePerBag + $packingPerPouch + $transportPerPouch ),"5");
				
				//Profit / pouch
				$profit = $obj_quotation->getcalculateProfit($quantity,$product_id,$post_height,$post_width,$gusset);
				$zipperBasePrice = 0;
				$zipperCalculatePrice = 0;
				$zipperText = '';
				if(isset($data['zipper']) && !empty($data['zipper'])){
					$zdata = $obj_quotation->getZipperInfo($data['zipper']);
					if($zdata){
						$zipperText = $zdata['zipper_name'];
						$zipperBasePrice = $zdata['price'];
						if($zdata['price'] > 0 ){
							$zipperCalculatePrice = $obj_quotation->getCalculateZipperPrice($product_id,$post_height,$post_width,$zdata['price']);
						}
					}
				}
				
				$valveBasePrice = 0;
				$valveTxt = 'No valve';
				if(isset($data['valve']) && $data['valve'] == 1){
					$valveTxt = 'with valve';
					$valveBasePrice = $userInfo['valve_price'];
				}
				
				$spoutBasePrice = 0;
				$spoutTxt = '';
				if(isset($data['spout']) && !empty($data['spout'])){
					$spoutInfo = $obj_quotation->getSpout($data['spout']);
					if($spoutInfo){
						$spoutBasePrice = $spoutInfo['price'];
						$spoutTxt = $spoutInfo['spout_name'];
					}
				}
				
				//Accessorie
				$accessorieBasePrice = 0;
				$accessorieTxt = '';
				if(isset($data['accessorie']) && !empty($data['accessorie'])){
						$accessorieInfo = $obj_quotation->getAccessorie($data['accessorie']);
						if($accessorieInfo){
							$accessorieBasePrice = $accessorieInfo['accessorie_name'];
							$accessorieTxt = $accessorieInfo['price'];
						}
					}
				}
				
				// Price with option
				$pricePerPuchWithOption = $obj_quotation->numberFormate(($optionPrice + $profit + $zipperCalculatePrice + $valveBasePrice ),"5");
				//total price without coutier charge
				$ftotalPrice = $obj_quotation->numberFormate(($pricePerPuchWithOption * $quantity),"5");
				
				//Total Weight without zipper
				$totalWeightWithoutZipper = $obj_quotation->getCalculateWeightWithoutZipper($totalLayerGsmThickness,$quantity);
				
				//Total Weight with zipper
				$totalWeightWithZipper = $obj_quotation->getCalculateWeightWithZipper($totalLayerGsmThickness,$quantity);
				
				
				//courie calculation
				$courierChargeWithZipper = 0;
				$courierChargeWithoutZipper = 0;
				$fuleSurchargeWithZipper  = 0;
				$serviceTaxWithZipper = 0;
				$fuleSurchargeWithoutZipper  = 0;
				$serviceTaxWithoutZipper = 0;
				$handlingCharge = 0;
				$fual_surcharge_base_price = 0;
				$service_tax_base_price = 0;
				$handling_base_price = 0;
				
				$courierChargeBaseWithZipper = 0;
				$courierChargeBaseWithoutZipper = 0;
				if($transportByAir){
					$countryCourierData = $obj_quotation->getCountryCourier($data['country_id']);
					$fual_surcharge_base_price = $countryCourierData['fuel_surcharge'];
					$service_tax_base_price = $countryCourierData['service_tax'];
					$handling_base_price = $countryCourierData['handling_charge'];
					if($fual_surcharge_base_price > 0){
						$fuleSurchargeWithZipper = (($courierChargeBaseWithZipper * $fual_surcharge_base_price) / 100);
						$fuleSurchargeWithoutZipper = (($courierChargeBaseWithoutZipper * $fual_surcharge_base_price) / 100);
					}
					if($service_tax_base_price > 0){
						$courierCrhgFuleWithZipper = ($courierChargeBaseWithZipper + $fuleSurchargeWithZipper);
						$serviceTaxWithZipper = (($courierCrhgFuleWithZipper * $service_tax_base_price) / 100);
						$courierCrhgFuleWithoutZipper = ($courierChargeBaseWithoutZipper + $fuleSurchargeWithoutZipper);
						$serviceTaxWithoutZipper = (($courierCrhgFuleWithoutZipper * $service_tax_base_price) / 100);
					}
					if($handling_base_price > 0){
						$handlingCharge = $handling_base_price;
					}
					
					$courierChargeBaseWithZipper = $obj_quotation->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'], $totalWeightWithZipper);
				
					$courierChargeBaseWithoutZipper = $obj_quotation->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'],$totalWeightWithoutZipper);
					
					
					//courier charge with zipper
					$courierChargeWithZipper = $obj_quotation->numberFormate(($courierChargeBaseWithZipper + $fuleSurchargeWithZipper + $serviceTaxWithZipper + $handlingCharge),"3");
					//courier charge without zipper
					$courierChargeWithoutZipper = $obj_quotation->numberFormate(($courierChargeBaseWithoutZipper + $fuleSurchargeWithoutZipper + $serviceTaxWithoutZipper + $handlingCharge),"3");
					
					if($zipperBasePrice > 0){
						$transportAndCoutierCharge = $courierChargeWithZipper;
						$ftotalPrice = $obj_quotation->numberFormate(($ftotalPrice + $courierChargeWithZipper),"5");
					}else{
						$transportAndCoutierCharge = $courierChargeWithoutZipper;
						$ftotalPrice = $obj_quotation->numberFormate(($ftotalPrice + $courierChargeWithoutZipper),"5");
					}
				}
				
				//user gress value
				$userGress = $userInfo['gres'];
				//Cylinder Price
				$userCountry = $obj_quotation->getUserCountry($user_type_id,$user_id);
				
				//deep modify
				if($user_type_id==1){
					$userCurrency = $obj_quotation->getCurrencyInfo($userCountry['currency_code']);
				}else{
					$userCurrency =  $obj_quotation->getUserWiseCurrency($user_type_id,$user_id);
				}
				
				//Cylinder Price
				$cylinderPrice = $obj_quotation->getCalculateCylinderPrice($post_height,$post_width,$data['country_id'],$product_id);
				
				$cylinderCurrencyPrice = $cylinderPrice;
				//printr($userCurrency);
				if($user_type_id==1){
					if($userCurrency['price']){
						$cylinderCurrencyPrice = ($cylinderPrice / $userCurrency['price']);
					}					
				//	$cylinderCurrencyMinPrice = $this->getCylinderBasePrice($userCurrency['counrty_id']);
					$cylinderCurrencyMinPrice = $obj_quotation->getCylinderBasePrice(111);
				//	echo $cylinderCurrencyMinPrice;die;
					if($cylinderCurrencyMinPrice && $cylinderCurrencyMinPrice >= $cylinderCurrencyPrice ){
						$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
					}else{
						$cylinderCurrencyBasePrice = $cylinderCurrencyPrice;
					}
				}
				else{
					if($userCurrency['cylinder_rate']){
						$cylinderCurrencyPrice = ($cylinderPrice / $userCurrency['cylinder_rate']);
					}					
					
					$cylinderCurrencyMinPrice = $obj_quotation->getCylinderBasePrice($userCurrency['currency_code']);
					
					if($cylinderCurrencyMinPrice && $cylinderCurrencyMinPrice >= $cylinderCurrencyPrice ){
						$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
					}else{
						$cylinderCurrencyBasePrice = $cylinderCurrencyPrice;
					}
				}
				//echo $cylinderCurrencyPrice.'<br>'.$cylinderCurrencyMinPrice;
				if($cylinderCurrencyPrice <= $cylinderCurrencyMinPrice){
					$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
				}
				
				
				
				//Cylinder Price
				/*$cylinderPrice = $obj_quotation->getCalculateCylinderPrice($post_height,$post_width,$data['country_id'],$product_id);
				$cylinderCurrencyPrice = $cylinderPrice;
				if($userCurrency['price']){
					$cylinderCurrencyPrice = ($cylinderPrice / $userCurrency['price']);
				}
				$cylinderCurrencyMinPrice = $obj_quotation->getCylinderBasePrice($userCurrency['currency_id']);
				if($cylinderCurrencyMinPrice && $cylinderCurrencyMinPrice >= $cylinderCurrencyPrice ){
					$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
				}else{
					$cylinderCurrencyBasePrice = $cylinderCurrencyPrice;
				}*/
				
				$printingEffectName = getName('printing_effect','printing_effect_id',$data['printing_effect'],'effect_name');
				
				$makeup_query = $this->query("SELECT make_name FROM ".DB_PREFIX."product_make WHERE make_id = '".$make_id."' ");
				
				
				$this->query("INSERT INTO ".DB_PREFIX."order_product SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', quantity = '".(int)$quantity."',makeup = '".$makeup_query->row['make_name']."', layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."', packing_price = '".(float)$packingPerPouch."', valve_price = '".$valveBasePrice."', valve_txt = '".$valveTxt."', zipper_txt = '".$zipperText."', zipper_price = '".$zipperCalculatePrice."', spout_txt = '".$spoutTxt."', spout_price = '".$spoutBasePrice."', accessorie_txt = '".$accessorieTxt."', accessorie_price = '".$accessorieBasePrice."', color = '".decode($data['color'])."', style = '".decode($data['style'])."', volume = '".decode($data['volume'])."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."', native_price_per_bag = '".$pricePerBag."', wastage = '".$wastage."', profit = '".$profit."', total_weight_without_zipper = '".$totalWeightWithoutZipper."', total_weight_with_zipper = '".$totalWeightWithZipper."', transport_type = '".$data['transpotation']."', transport_charge = '".$transportAndCoutierCharge."', total_price = '".$ftotalPrice."', product_note = '".$product_note."', product_instruction = '".$product_instruction."', date_added = NOW(), date_modify=NOW(), is_delete=0, status=1 ");
				
				$orderProductId = $this->getLastId();
				
				if(isset($orderProductId) && (int)$orderProductId > 0 ){
					
					//BASE PRICE
					$inkDefaultPrice = $obj_quotation->getInkPrice1($make_id);
					$inkSolventDefaultPrice = $obj_quotation->getInkSolventPrice('',0,$make_id);
					$printingEffectDefaultPrice = $obj_quotation->getPrintingEffectPrice($data['printing_effect']);
					$adhesiveDefaultPrice = $obj_quotation->getAdhesivePrice('','',0,$make_id);
					$adhesiveSolventDefaultPrice = $obj_quotation->getAdhesiveSolventPrice('','',0,$make_id);
					$cppAdhesiveDefaultPrice = $obj_quotation->getCppAdhesivePrice('','',0);
					
					$transportWidthDefaultPrice = $obj_quotation->getDefaultTransportWidthPrice($post_width,$gusset);
					$transportHeightDefaultPrice = $obj_quotation->getDefaulttransportHeightPrice($post_height);
					
					$cylinderVendorDefaultPrice = $obj_quotation->getCylinderVendorPrice($data['country_id']);
					//inert base price at a time product quotaion add than taht time real price. use for history
					$this->query("INSERT INTO ".DB_PREFIX."order_product_base_price SET order_product_id = '".(int)$orderProductId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$packingPerPouch."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
					
					if(isset($setQueryData) && !empty($setQueryData)){
						foreach($setQueryData as $key=>$setquery){
							$setSql = "INSERT INTO ".DB_PREFIX."order_product_layer SET order_product_id = '".(int)$orderProductId."', ".$setquery;
							$this->query($setSql);
						}
					}
					
				}
				
				$returnArray = array();
				$returnArray = array(
					'order_product_id' => $orderProductId,
					'total_price'	   => $ftotalPrice,
				);
				return $returnArray;
			}
	}
	
	public function getIndustrys(){
		$data = $this->query("SELECT enquiry_industry_id, industry FROM " . DB_PREFIX . "enquiry_industry WHERE is_delete = 0 ORDER BY industry ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getOrderHistories($order_id,$data){
		//$data = $this->query("SELECT oh.*,am.user_name FROM " . DB_PREFIX . "order_history oh LEFT JOIN `" . DB_PREFIX . "account_master` am ON (oh.user_type_id=am.user_type_id) AND (oh.user_id=am.user_id) ");
		
		$sql = "SELECT oh.*,am.user_name,os.status_name FROM order_history as oh,account_master as am,order_status as os where oh.user_type_id=am.user_type_id AND oh.user_id=am.user_id AND oh.order_status_id = os.order_status_id AND oh.order_id = '".$order_id."'";
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$datares = $this->query($sql);
		if($datares->num_rows){
			return $datares->rows;	
		}else{
			return false;	
		}
	}
	
	public function gettotalcountOrderHistories($order_id)
	{
				$data = $this->query("SELECT oh.*,am.user_name,os.status_name FROM order_history as oh,account_master as am,order_status as os where oh.user_type_id=am.user_type_id AND oh.user_id=am.user_id AND oh.order_status_id = os.order_status_id AND oh.order_id = '".$order_id."'");
				
		
		return $data->num_rows;		
				
	}
	
	
	public function addHistory($order_id,$order_status_id,$note,$email_notif){
		
		$this->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '".$order_id."',order_status_id = '".$order_status_id."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',email_notification = '".$email_notif."',note = '".$note."',date_added = NOW() ,date_modify = NOW()");
		
		$user_data = $this->query("SELECT user_name FROM " . DB_PREFIX . "account_master WHERE user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' "); 
		
		return $user_data->row['user_name'];
		
	}
	
	public function getOrderStatusName($order_status_id)
	{
		$Sql = "SELECT oh.order_id,os.status_name from order_history as oh,order_status as os 
		where os.order_status_id = '".$order_status_id."'";
		$data = $this->query($Sql);
		return isset($data->row['status_name'])?$data->row['status_name']:'';
	}
	
	public function getQuotionData($quotation_no)
	{
		$Sql = "SELECT product_quotation_id,product_id,printing_option,printing_effect_id,printing_effect,layer,height,width,gusset,shipment_country_id FROM product_quotation WHERE quotation_number = '".$quotation_no."' AND is_delete=0 ORDER BY product_quotation_id DESC LIMIT 1";
		$data = $this->query($Sql);
		if($data->num_rows>0)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	}
	
	public function getQuotionZipperData($product_quotation_id)
	{
			$data = $this->query("SELECT product_quotation_quantity_id, product_quotation_id, quantity FROM " . DB_PREFIX ."product_quotation_quantity WHERE product_quotation_id = '".(int)$product_quotation_id."'");
	//	printr($data);die;
		$return = '';$sea=0;$air=0;$pickup=0;
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				$zdata = $this->query("SELECT qp.product_quotation_id, qp.product_quotation_quantity_id, qp.transport_type, pz.product_zipper_id, qp.valve_txt,accessorie_txt,ps.product_spout_id,pa.product_accessorie_id,qp.total_price,qp.total_price_with_excies,qp.total_price_with_tax,qp.tax_type,qp.tax_percentage,qp.excies, qp.gress_price, qp.customer_gress_price,qp.make_pouch FROM " . DB_PREFIX ."product_quotation_price qp,product_zipper as pz,product_spout ps,product_accessorie pa WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' AND qp.zipper_txt=pz.zipper_name AND qp.spout_txt=ps.spout_name AND qp.accessorie_txt=pa.product_accessorie_name");	
				if($zdata->num_rows){
					//printr($zdata->rows);
					foreach($zdata->rows as $zipData){
						
						if($zipData['valve_txt'] == 'no Valve')
							$valve =0;
						else
							$valve=1;
							$zip=$zipData['product_zipper_id'];
							$accessorie =$zipData['product_accessorie_id'];
							$spout=$zipData['product_spout_id'];
							$make_pouch=$zipData['make_pouch'];
							if($zipData['transport_type']=='air')
								$air=1;
							elseif($zipData['transport_type']=='sea')
							$sea=1;
							elseif($zipData['transport_type']=='pickup')
								$pickup=1;		
								$checked = $zipData['transport_type'];	
							$qty[$zipData['transport_type']][]=array('quantity'=>$qunttData['quantity'],
													'total_price'=>$zipData['total_price']
												);					
						//	$transport[$zipData['transport_type']]=$zipData['transport_type'];
					}
					
				}
			}
			$return = array(
							'valve'=>$valve,
							'product_zipper_id'=>$zip,
							'product_spout_id'=>$spout,
							'product_accessorie_id'=>$accessorie,
							'quantity_arr'=>$qty,
							'air'=>$air,
							'sea'=>$sea,
							'pickup'=>$pickup,
							'checked'=>$checked,
							'make_pouch'=>$make_pouch														
						);
		}
	//	printr($return[);
		return $return;
	}
	
	public function getQuotionQtyData($product_quotation_id,$transport_type)
	{
			$data = $this->query("SELECT product_quotation_quantity_id, product_quotation_id, quantity FROM " . DB_PREFIX ."product_quotation_quantity WHERE product_quotation_id = '".(int)$product_quotation_id."'");
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				$zdata = $this->query("SELECT qp.transport_type,qp.total_price,qp.total_price_with_excies,qp.total_price_with_tax,qp.tax_type,qp.tax_percentage,qp.excies, qp.gress_price, qp.customer_gress_price FROM " . DB_PREFIX ."product_quotation_price qp WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' AND qp.transport_type='".$transport_type."' ");	
				if($zdata->num_rows){
					//printr($zdata->rows);
					foreach($zdata->rows as $zipData){	
					$tprice = $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3");		
					$data = $this->getQuotation($product_quotation_id);		
							$qty[]=array('quantity'=>$qunttData['quantity'],
										'encode_quantity'=>encode($qunttData['quantity']),
										'per_price'=>$this->numberFormate((($tprice / $qunttData['quantity']) / $data['currency_price']),"3"),
										'total_price'=>$this->numberFormate(($tprice / $data['currency_price'] ),"3")
												);					
					}
				}
			}			
		}
		return $qty;
	}
	public function getQuotation($quotation_id){
		$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
		$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT product_quotation_id, added_by_user_id, added_by_user_type_id, customer_name, shipment_country_id, quotation_number, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,pq.status,quotation_status,cn.country_name FROM " . DB_PREFIX ."product_quotation pq INNER JOIN " . DB_PREFIX ."country cn ON (pq.shipment_country_id=cn.country_id) WHERE product_quotation_id = '".(int)$quotation_id."'";
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
					$str = ' OR ( added_by_user_id IN ('.$userEmployee.') AND added_by_user_type_id = 2 ) ';
				}
				$str .= ' ) ';
				
				$sql = "SELECT product_quotation_id, added_by_user_id, added_by_user_type_id, customer_name, shipment_country_id, quotation_number, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,pq.status,quotation_status,cn.country_name FROM " . DB_PREFIX ."product_quotation pq INNER JOIN " . DB_PREFIX ."country cn ON (pq.shipment_country_id=cn.country_id) WHERE (( added_by_user_id = '".(int)$set_user_id."' AND added_by_user_type_id = '".(int)$set_user_type_id."') $str AND product_quotation_id = '".(int)$quotation_id."' ";
				
			}
		}else{
			$sql = "SELECT product_quotation_id, added_by_user_id, added_by_user_type_id, customer_name, shipment_country_id, quotation_number, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,pq.status,quotation_status FROM " . DB_PREFIX ."product_quotation WHERE product_quotation_id = '".(int)$quotation_id."'";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
	}
	
	/********************* 19 feb by rohit **************************/
	public function getQuotationPackingAndTransportDetails2($quotation_id){
		$sql = "SELECT pqbp.packing_price,pqbp.transport_width_base_price,pqbp.transport_height_base_price FROM " . DB_PREFIX ."multi_product_quotation pq INNER JOIN " . DB_PREFIX ."multi_product_quotation_base_price pqbp ON (pq.product_quotation_id=pqbp.product_quotation_id) WHERE pq.product_quotation_id = '".(int)$quotation_id."'";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	//for product display by quotation number
	public function getQuotationQuantity2($quotation_id){
		$paking_price=$this->getQuotationPackingAndTransportDetails2($quotation_id);
		$data = $this->query("SELECT mpqq.product_quotation_quantity_id, mpqq.product_quotation_id,mpqq.discount, printing_effect,mpqq.quantity,mpq.height,mpq.width,mpq.gusset,mpq.volume, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,customer_gress_percentage FROM " . DB_PREFIX ."multi_product_quotation_quantity as mpqq,multi_product_quotation as mpq WHERE mpqq.product_quotation_id = '".(int)$quotation_id."' AND mpqq.product_quotation_id=mpq.product_quotation_id ORDER BY mpqq.product_quotation_quantity_id") ;
		$return = '';
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				$zdata = $this->query("SELECT product_quotation_price_id, product_quotation_id, product_quotation_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt, total_price,total_price_with_excies,total_price_with_tax,tax_type,tax_percentage,excies, gress_price, customer_gress_price,zipper_price,valve_price,courier_charge,spout_base_price,accessorie_base_price,transport_price,make_name FROM " . DB_PREFIX ."multi_product_quotation_price as mpqp ,product_make as pm WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' AND mpqp.make_pouch=pm.make_id ORDER BY transport_type");	
				if($zdata->num_rows){
					 
					if(isset($zdata->rows[0]['excies']) && $zdata->rows[0]['excies']>0)
					{
						$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'],
							'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'],
							'Wastage' => $qunttData['wastage_base_price'],
							'Profit' => $qunttData['profit'],
							'Excies' => $zdata->rows[0]['excies'].' %',
							str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])) => $zdata->rows[0]['tax_percentage'].' %'					
						);
					}
					else
					{
						$quantity_option[$qunttData['quantity']] =  array(
						'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'],
						'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'],
						'Wastage' => $qunttData['wastage_base_price'],
						'Profit' => $qunttData['profit'],
						);
					}
					foreach($zdata->rows as $zipData){
						//printr($zipData['product_quotation_id']);die;
						$materialData = $this->getMultiQuotationMaterial($zipData['product_quotation_id']);						
						
						$new_tax[$qunttData['quantity']] =  array('Excies' => $zipData['excies']);
						$zipper_option =
						 array(
							'zipper_price' => $zipData['zipper_price'],
							'valve_price' => $zipData['valve_price'],
							'courier_charge' => $zipData['courier_charge']  ,
							'spout_price' => $zipData['spout_base_price'],
							'accessorie_price' => $zipData['accessorie_base_price'],
							
						);
						
						$txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
						$txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
						if($zipData['spout_txt']=='No Spout')
							$zipData['spout_txt']='';
						if($zipData['accessorie_txt']=='No Accessorie')
							$zipData['accessorie_txt']='';
						$email_txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
						$email_txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
							$return[$zipData['transport_type']][$qunttData['quantity']][] = array(
								'text' 		=> $txt,
								'email_text' 		=> $email_txt,
								'totalPrice'	   =>  $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'totalPriceWithExcies'	   =>  $this->numberFormate(($zipData['total_price_with_excies']),"3"),
								'totalPriceWithTax'	   =>  $this->numberFormate(($zipData['total_price_with_tax']),"3"),
								'tax_type'	   => $zipData['tax_type'] ,
								'tax_percentage'	   => $zipData['tax_percentage'] ,
								'excies'	   => $zipData['excies'] ,
								'customerGressPrice' => $zipData['customer_gress_price'],
								'product_quotation_quantity_id'=>$zipData['product_quotation_quantity_id'],
								'discount'=>$qunttData['discount'],
								'width'=>$qunttData['width'],
								'height'=>$qunttData['height'],
								'gusset'=>$qunttData['gusset'],
								'volume'=>$qunttData['volume'],
								'cylinder_price'=>$qunttData['cylinder_price'],
								'tool_price'=>$qunttData['tool_price'],
								'quantity_option'	=> $quantity_option[$qunttData['quantity']],
								'zipper_option' => $zipper_option,
								'courier_charge' => $zipData['courier_charge'],
								'transport_price' => $zipData['transport_price'] ,
								'packing_price'=>$paking_price['packing_price'],
								'gress_price'  => $zipData['gress_price'],
								'gress_percentage' => $qunttData['gress_percentage'],
								'customer_gress_percentage' => $qunttData['customer_gress_percentage'],
								'zipper_txt' => $zipData['zipper_txt'],
								'valve_txt' => $zipData['valve_txt'],
								'printing_effect' => $qunttData['printing_effect'],
								'materialData'=>$materialData,
								'make' => $zipData['make_name'],
								'product_quotation_price_id'=>$zipData['product_quotation_price_id'],								
							);
					}
				}
			}
		}
		//printr($return);die;
		return $return;
	}
	
	
	//get all data by quotation number
	public function getQuotationNumberId ($quotationNumber){
		$sql = "SELECT multi_product_quotation_id FROM multi_product_quotation_id WHERE multi_quotation_number = '".$quotationNumber."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getProductQuotId($quotNumber){
		$sql = "SELECT * FROM multi_product_quotation WHERE multi_product_quotation_id = '".$quotNumber."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	// get all quotation data by priceId
	public function getAddProDetail($priceId){
		$sql = "SELECT * FROM multi_product_quotation_price WHERE product_quotation_price_id= '".$priceId."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//get all data by product_quotation id
	public function getMulProDetail($quotationId){
		$sql = "SELECT * FROM multi_product_quotation WHERE product_quotation_id = '".$quotationId."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//get quantity detail by product	
	public function getQuantityDetail($quotationId){
		$sql = "SELECT * FROM multi_product_quotation_quantity WHERE product_quotation_id = '".$quotationId."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//get order product details
	public function getOrderDetail($order_id){
		$sql = "SELECT * FROM order_product WHERE order_product_id = '".(int)$order_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//get order material details
	public function getMaterialDetail($material_id){
		$sql = "SELECT * FROM product_material WHERE product_material_id = '".(int)$material_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//get product_spout data
	public function getSpoutDetail($spout_name){
		$sql = "SELECT product_spout_id FROM product_spout WHERE spout_name = '".$spout_name."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//get product_accessory data
	public function getAccessorieDetail($accessorie_name){
		$sql = "SELECT product_accessorie_id FROM product_accessorie WHERE product_accessorie_name = '".$accessorie_name."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//get order_product data
	public function getOrderProductDetail($order_product_id){
		$sql = "SELECT * FROM order_product WHERE order_product_id = '".$order_product_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//delete/disable order when order_product deleted
	public function removeOrderPro($order_id){
		$this->query("UPDATE `order` SET `is_delete` = '1' WHERE `order_id` = '".$order_id."'");
	}
	//get order material data
	public function getOrderMaterial($order_product_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."order_product_layer WHERE order_product_id = '".(int)$order_product_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getViewToolprice($product_id)
	{
		$sql  = "SELECT pe.*,p.product_name FROM product_extra_tool_price as pe,product as p WHERE pe.product_id = p.product_id AND 
		pe.product_id = '".$product_id."'"; 
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
	
	public function getGussetSuggestion($width,$gusset,$product_id)
	{
		$result = '';
		if($gusset!='')
		{
			$result = "AND gusset = '".$gusset."'";	
		}
		$cond = '';
		$cond1 ='';
		$sql1 = "SELECT price,width_to,gusset FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."') LIMIT 1";
	 	$data1 = $this->query($sql1);
	 	if( $data1->num_rows > 0)
	 	{
			if($gusset!='')
		 	{
		 		if($data1->row['gusset']==$gusset)
		 		{
			 		return 0;
		 		}
				elseif($data1->row['width_to']==$width && $data1->row['gusset']!=$gusset)
		 		{
			 		//echo $gusset;
			 		return $data1->rows;
		 		}
		 	}
		 	else
		 	{
				return 0;
		 	}
	 	}
		else
		{
			//printr($data1);die;
	 		if(!$data1->num_rows)
	 		{
				$cond1 = " LIMIT 1";
				$sql = "SELECT price,width_to,gusset FROM ( ( SELECT price,width_to,gusset,".$width."-width_to AS diff FROM product_extra_tool_price
    WHERE product_id = '" .(int)$product_id. "' AND width_to >'".$width."' ".$cond1." ) UNION ALL ( SELECT price,width_to,gusset,width_to-".$width." AS diff
    FROM product_extra_tool_price  WHERE product_id = '" .(int)$product_id. "' AND width_to <'".$width."' ".$cond1."  )) AS tmp ORDER BY diff LIMIT 2" ;
				$data = $this->query($sql);
				if($data->num_rows){
					return $data->rows;				
				}else{
					return false;
				}
	 		}
		}
	}
	
	public function getToolPrice($width,$gusset,$product_id){
	$cond = '';
	$cond1 ='';
	$sql1 = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."')
	AND gusset = '".$gusset."' LIMIT 1";
	 $data1 = $this->query($sql1);
	
	 if(!$data1->num_rows)
	 {
		if(isset($gusset) && $gusset>0)
		{
			$cond = " ORDER BY gusset,width_to  ASC";
		}
		else
		{
			$cond1 = " ORDER BY width_to ASC LIMIT 1";
		}	
			$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND (width_to > '".(int)$width."'
	) ".$cond1."";
			$data = $this->query($sql);
	
		if($data->num_rows >1)
		{
			$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to > '".(int)$width."') ".$cond." LIMIT 1";
			$data = $this->query($sql);
		}
			if($data->num_rows){
				return $data->row['price'];
			}else{
				return false;
			}
	 }
	 else
	 {
		return 0;
	 }
	}
	public function getUserWiseCurrency($user_type_id,$user_id)
	{
		if($user_type_id==2){
			$parent_data = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX ."employee WHERE employee_id='".$user_id."'");
			if($parent_data->num_rows){
				
				if($parent_data->row['user_type_id']==4){
					$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$parent_data->row['user_id']."' ";				
				}else if($parent_data->row['user_type_id']==5){
					$sql = "SELECT as.product_rate,as.cylinder_rate,as.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."associate as INNER JOIN " . DB_PREFIX ."country cn ON (as.default_curr=cn.country_id) WHERE as.associate_id = '".$parent_data->row['user_id']."' ";	
					
				}	
			}
		}
		if($user_type_id==4){
			$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$user_id."' ";		
		}		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}


	
}
?>