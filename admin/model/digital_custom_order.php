<?php  // sonu 29-5-2019
class digital_custom_order extends dbclass{
	
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
	
	public function getIndustrys(){
		$data = $this->query("SELECT enquiry_industry_id, industry FROM " . DB_PREFIX . "enquiry_industry WHERE is_delete = 0 ORDER BY industry ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCountries(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE status='1' AND is_delete = '0' ORDER BY country_name ASC";
		$data = $this->query($sql);
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
	
	public function getUserCurrencyInfo($user_type_id,$user_id){
		if($user_type_id==1)
		{
			$table = 'user';
			$colName='t.user_id';
		}
		if($user_type_id==2)
		{
			$data = $this->query("SELECT user_id,user_type_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."' ");
			$table = 'employee';
			$colName='t.employee_id';
			$user_id = $data->row['user_id'];
			$user_type_id=$data->row['user_type_id'];
		}
		if($user_type_id==3)
		{
			$table = 'client';
			$colName='t.client_id';
		}
		if($user_type_id==4)
		{
			$table = 'international_branch';
			$colName='t.international_branch_id';
		}
		
		$data = $this->query("SELECT cur.* FROM " . DB_PREFIX .$table." as t LEFT JOIN " . DB_PREFIX ."country cur ON(t.default_curr=cur.country_id)  WHERE ".$colName." = '".(int)$user_id."' ");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
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
	
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_id as international_branch_id,e.user_name, co.country_id, co.country_name, e.first_name, e.last_name, e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";		

				
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,ib.discount, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email, acc.email1 FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			return false;
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	
	public function getProduct($product_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '".$product_id."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return 0;
		}
	}
	
	public function getQuantityById($quantity_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."product_quantity WHERE product_quantity_id = '".$quantity_id."' LIMIT 1");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getProductSize($product_id,$zipper_id)
	{
		$sql  = "SELECT * FROM size_master WHERE product_id = '".$product_id."' AND product_zipper_id='".decode($zipper_id)."'"; 
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
	
	public function getViewToolprice($product_id)
	{
		$sql  = "SELECT pe.*,p.product_name FROM product_extra_tool_price as pe,product as p WHERE pe.product_id = p.product_id AND pe.product_id = '".$product_id."'"; 
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
	
	
	
	
	
	public function getActiveProductZippers(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
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
	
	public function getZipperInfo($zipper_id){
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
	
	public function getMaterialQuantity($material_id,$qty=0){
		$data = $this->query("SELECT pq.quantity FROM " . DB_PREFIX . "product_material_quantity pmq INNER JOIN " . DB_PREFIX . "product_quantity pq ON(pmq.product_quantity_id=pq.product_quantity_id) WHERE pmq.product_material_id = '".(int)$material_id."' AND pq.quantity>='".(int)$qty."' ORDER BY pq.quantity ASC ");	
		if($data->num_rows){
			$return = '';
			foreach($data->rows as $key=>$value){
				$return[] = $value['quantity'];
			}
			return $return;
		}else{
			return false;
		}
	}
	
	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
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
	
	public function getCurrencyValue($currency_id){
		$data = $this->query("SELECT price FROM " . DB_PREFIX ."currency_setting WHERE currency_id= '".$currency_id."'");
		return $data->row['price'];
	}
	
	public function aasort ($array) {
		$newArray = array();
		$hv = array();
	   foreach ($array as $va) {
			$hv[] = max($va);
			$newArray = $this->makeOneArray($va,$newArray);
		}
		asort($hv);
		if(count($hv) > 1){
			array_pop($hv);
		}
		rsort($newArray);
		$highest = $this->getHighestValue($newArray,$hv);
		$totalCount = count($array);
		$common = $this->getCommonValue($newArray,$totalCount);
		$final = array_merge($highest,$common);
		return $final;
	}
	
	public function makeOneArray($array,$newArray){
		foreach ($array as $ii => $va) {
			$newArray[] = $va;
		}
		return $newArray;
	}
	
	public function getCommonValue($array,$totalCount){
		$common = array();
		foreach($array as $val){
			$tmp = array_count_values($array);
			$cnt = $tmp[$val];
			if($cnt == $totalCount){
				if(!in_array($val,$common)){
					$common[] = $val;
				}
			}
		}
		return $common;
	}
	
	public function getHighestValue($array,$hv){
		$highest = array();
		$max = max($hv);
		foreach($array as $val){
			if(!in_array($val,$hv) && $val > $max){
				$highest[] = $val;
			}
		}
		return $highest;
	}
	
	public function getCurrencyInfo($user_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."user WHERE user_id = '".$user_id."' LIMIT 1");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
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
	
	public function getCustomOrder($custom_order_id,$getData = '*',$user_type_id='',$user_id=''){
		if($user_type_id && $user_id){
		
		    $menu_id = 79;
    		$perm_cond ='( add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%" ) ';
    			
    		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
    			//echo $sql;
    		$dataper=$this->query($sql);
    		
			if($user_id == 1 && $user_type_id == 1 || $dataper->num_rows!=0){
				$sql = "SELECT $getData,mcoi.reference_no,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN multi_custom_order_id as mcoi ON(mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE mco.multi_custom_order_id = '".(int)$custom_order_id."'";
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
					$str = ' OR ( mco.added_by_user_id IN ('.$userEmployee.') AND mco.added_by_user_type_id = 2 ) ';
				}
				//$str .= ' ) ';
				$sql = "SELECT $getData,mcoi.reference_no,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN " . DB_PREFIX ."multi_custom_order_id mcoi ON (mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE  mco.multi_custom_order_id = '".(int)$custom_order_id."' AND ((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str )";
				//echo $str;
			}
		}else{
			$sql = "SELECT $getData,mcoi.reference_no,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode FROM " . DB_PREFIX ."multi_custom_order mco,multi_custom_order_id mcoi,address adr WHERE
			 mco.multi_custom_order_id=mcoi.multi_custom_order_id  AND mco.multi_custom_order_id = '".(int)$custom_order_id."' AND mcoi.shipping_address_id=adr.address_id";
		}
	//	echo $sql;
		//die;
		$data = $this->query($sql);
		
		return $data->rows;
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
	
	public function CheckCustomOrderTax($custom_order_id)
	{
		$sql = "SELECT zipper_txt,valve_txt,tax_type,tax_percentage,excies FROM " . DB_PREFIX ."multi_custom_order_price WHERE custom_order_id = '".(int)$custom_order_id."' ORDER BY custom_order_price_id ASC LIMIT 1";
		$data = $this->query($sql);
		return $data->row;
	}	
	
	public function insertImages($images=array(),$custom_order_id){
		foreach($images as $image){
			$this->query("INSERT INTO " . DB_PREFIX ."multi_custom_order_product_image SET order_product_id = '".$custom_order_id."',image_name='".addslashes($image['image_name'])."',ext='".$image['image_ext']."'");
		}	
	}
	
	public function insertDieLine($die_lines=array(),$custom_order_id){
		foreach($die_lines as $die_line){ 
	        $this->query("INSERT INTO " . DB_PREFIX ."multi_custom_order_product_die_line SET order_product_id = '".$custom_order_id."',name='".addslashes($die_line['die_name'])."',ext='".$die_line['die_ext']."'");
			
	//		printr("INSERT INTO " . DB_PREFIX ."multi_custom_order_product_die_line SET order_product_id = '".$custom_order_id."',name='".$die_line['die_name']."',	ext='".$die_line['die_ext']."'");
		  
		}	
	} 
	
	public function insertNote($post)
	{
		if(!empty($post['product_special_instruction'])){

			foreach ($post['product_special_instruction'] as $key => $value) {

				//	printr("UPDATE multi_custom_order SET product_instruction='".$value."' WHERE custom_order_id='".$key."'");
		//	die;
			$this->query("UPDATE multi_custom_order SET product_instruction='".$value."' WHERE custom_order_id='".$key."'");
				
			}
		//die;
		}
		
	}
	
	public function getCustomOrderQuantity($custom_order_id){
		$paking_price=$this->getCustomOrderPackingAndTransportDetails($custom_order_id);
		$data = $this->query("SELECT mco.quantity_type,mco.accept_decline_status,mco.custom_order_id,mcoq.cust_quantity,mcoq.product_code_id,mcoq.custom_order_quantity_id,mco.product_id,p.product_name, mcoq.custom_order_id,mcoq.discount, printing_effect,mcoq.quantity,mco.height,mco.width,mco.gusset,mco.volume, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mco.layer,total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage,	back_color,front_color,	total_color ,mco.product_instruction,mco.quotation_status,client_order_qty FROM " . DB_PREFIX ."multi_custom_order_quantity as mcoq,multi_custom_order as mco,product as p WHERE mcoq.custom_order_id = '".(int)$custom_order_id."' AND mcoq.custom_order_id=mco.custom_order_id AND mco.product_id=p.product_id ORDER BY mcoq.custom_order_quantity_id") ;
	//	printr("SELECT mco.quantity_type,mco.accept_decline_status,mco.custom_order_id,mcoq.cust_quantity,mcoq.custom_order_quantity_id,mco.product_id,p.product_name, mcoq.custom_order_id,mcoq.discount, printing_effect,mcoq.quantity,mco.height,mco.width,mco.gusset,mco.volume, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mco.layer,total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage FROM " . DB_PREFIX ."multi_custom_order_quantity as mcoq,multi_custom_order as mco,product as p WHERE mcoq.custom_order_id = '".(int)$custom_order_id."' AND mcoq.custom_order_id=mco.custom_order_id AND mco.product_id=p.product_id ORDER BY mcoq.custom_order_quantity_id");

		$return = array();
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				//printr($qunttData);
				$zdata = $this->query("SELECT cust_total_price,custom_order_price_id, custom_order_id, custom_order_quantity_id, transport_type, client_total_price,zipper_txt, valve_txt, spout_txt, accessorie_txt,accessorie_txt_corner, total_price,total_price_with_excies,total_price_with_tax,tax_name,tax_type,tax_percentage,excies, gress_price, customer_gress_price,zipper_price,valve_price,courier_charge,spout_base_price,accessorie_base_price,transport_price,make_name,	pouch_price,pouch_price_with_tax,color_plate_price,color_plate_price_with_tax,total_price,total_price_with_tax,template_price,gress_pouch_price,print_price,plate_price_with_discount,digital_print_discount,color_plate_price_with_discount_count FROM " . DB_PREFIX ."multi_custom_order_price as mcop ,product_make as pm WHERE custom_order_id = '".(int)$qunttData['custom_order_id']."' AND custom_order_quantity_id = '".(int)$qunttData['custom_order_quantity_id']."' AND mcop.make_pouch=pm.make_id ORDER BY transport_type");	
		

			//	printr("SELECT cust_total_price,custom_order_price_id, custom_order_id, custom_order_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt,accessorie_txt_corner, total_price,total_price_with_excies,total_price_with_tax,tax_name,tax_type,tax_percentage,excies, gress_price, customer_gress_price,zipper_price,valve_price,courier_charge,spout_base_price,accessorie_base_price,transport_price,make_name,	pouch_price,pouch_price_with_tax,color_plate_price,color_plate_price_with_tax,total_price,total_price_with_tax,template_price,gress_pouch_price,print_price,plate_price_with_discount,digital_print_discount,color_plate_price_with_discount_count FROM " . DB_PREFIX ."multi_custom_order_price as mcop ,product_make as pm WHERE custom_order_id = '".(int)$qunttData['custom_order_id']."' AND custom_order_quantity_id = '".(int)$qunttData['custom_order_quantity_id']."' AND mcop.make_pouch=pm.make_id ORDER BY transport_type");

				if($zdata->num_rows){
					if(isset($zdata->rows[0]['excies']) && $zdata->rows[0]['excies']>0)
					{
						$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'],
							'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'],
							'Wastage' => $qunttData['wastage_base_price'],
							'Profit' => $qunttData['profit'],
							'Excies' => $zdata->rows[0]['excies'].' %',
							'tax_name'=>$zdata->rows[0]['tax_name'],
							'client_total_price'=>$zdata->rows[0]['client_total_price'],
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
				//	printr($zipData);
						$materialData = $this->getCustomOrderMaterial($zipData['custom_order_id']);
						$new_tax[$qunttData['quantity']] =  array('Excies' => $zipData['excies']);
						$zipper_option =
						 array(
							'zipper_price' => $zipData['zipper_price'],
							'valve_price' => $zipData['valve_price'],
							'courier_charge' => $zipData['courier_charge']  ,
							'spout_price' => $zipData['spout_base_price'],
							'accessorie_price' => $zipData['accessorie_base_price'],
							'pouch_price'=>$zipData['pouch_price'],
							'gress_pouch_price'=>$zipData['gress_pouch_price'],
							'pouch_price_with_tax'=>$zipData['pouch_price_with_tax'],
							'color_plate_price'=>$zipData['color_plate_price'],
							'color_plate_price_with_tax'=>$zipData['color_plate_price_with_tax'],
							'template_price'=>$zipData['template_price'],
							'print_price'=>$zipData['print_price'],
							'plate_price_with_discount'=>$zipData['plate_price_with_discount'],
							'digital_print_discount'=>$zipData['digital_print_discount'],
							'color_plate_price_with_discount_count'=>$zipData['color_plate_price_with_discount_count'],
							
						);
					//	printr($zipper_option);
						$txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
						$txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
						$txt .= '<br> '.$zipData['accessorie_txt_corner'].'<br>';
						if($zipData['spout_txt']=='No Spout')
							$zipData['spout_txt']='';
						if($zipData['accessorie_txt']=='No Accessorie')
							$zipData['accessorie_txt']='';
						$email_txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
						$email_txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
						//$email_txt .= '<br> '.$zipData['accessorie_txt_corner'].'<br>';
						
							$return[$zipData['transport_type']][$qunttData['quantity']][] = array(
								'text' 		=> $txt,
								'email_text' 		=> $email_txt,
								'totalPrice'	   =>  $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'totalPriceWithExcies'	   =>  $this->numberFormate(($zipData['total_price_with_excies']),"3"),
								'totalPriceWithTax'	   =>  $this->numberFormate(($zipData['total_price_with_tax']),"3"),
								'tax_type'	   => $zipData['tax_type'] ,
								'tax_percentage'	   => $zipData['tax_percentage'] ,
								'excies'	   => $zipData['excies'] ,
								'tax_name'=>$zipData['tax_name'],
								'customerGressPrice' => $zipData['customer_gress_price'],
								'custom_order_quantity_id'=>$zipData['custom_order_quantity_id'],
								'discount'=>$qunttData['discount'],
								'cust_quantity'=>$qunttData['cust_quantity'],
								'cust_total_price'=>$zipData['cust_total_price'],
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
								'gress_sea' => $qunttData['gress_sea'],
								'gress_air' => $qunttData['gress_air'],
								'customer_gress_percentage' => $qunttData['customer_gress_percentage'],
								'zipper_txt' => $zipData['zipper_txt'],
								'valve_txt' => $zipData['valve_txt'],
								'accessorie_txt' => $zipData['accessorie_txt'],
								'spout_txt' => $zipData['spout_txt'],
								'printing_effect' => $qunttData['printing_effect'],
								'materialData'=>$materialData,
								'make' => $zipData['make_name'],
								'custom_order_price_id'=>$zipData['custom_order_price_id'],
								'layer'=>$qunttData['layer'].''.$zipData['custom_order_id'],
								'product_id'=>$qunttData['product_id'],
								'product_name'=>$qunttData['product_name'],
								'product_code_id'=>$qunttData['product_code_id'],
								'custom_order_id'=>$qunttData['custom_order_id'],
								'product_instruction'=>$qunttData['product_instruction'],
								'accept_decline_status'=>$qunttData['accept_decline_status'],
								'quantity_type'=>$qunttData['quantity_type'],						
								'pouch_price'=>$zipData['pouch_price'],
								'pouch_price_with_tax'=>$zipData['pouch_price_with_tax'],
								'color_plate_price'=>$zipData['color_plate_price'],
								'color_plate_price_with_tax'=>$zipData['color_plate_price_with_tax'],
								'total_color'=>$qunttData['total_color'],
								'front_color'=>$qunttData['front_color'],
								'back_color'=>$qunttData['back_color'],
								'quotation_status'=>$qunttData['quotation_status'],
								'gress_pouch_price'=>$zipData['gress_pouch_price'],
								'template_price'=>$zipData['template_price'],
								'print_price'=>$zipData['print_price'],
								'plate_price_with_discount'=>$zipData['plate_price_with_discount'],
								'digital_print_discount'=>$zipData['digital_print_discount'],
								'color_plate_price_with_discount_count'=>$zipData['color_plate_price_with_discount_count'],
								'client_order_qty'=>$qunttData['client_order_qty'],
								'client_total_price'=>$zipData['client_total_price'],
								
								
							);

					}
				}
			}
		}
		//printr($return);
		return $return;
	}
	
	public function getCustomOrderPackingAndTransportDetails($custom_order_id){
		$sql = "SELECT mcobp.packing_price,mcobp.transport_width_base_price,mcobp.transport_height_base_price,mco.product_note, mco.product_instruction FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."multi_custom_order_base_price mcobp ON (mco.custom_order_id=mcobp.custom_order_id) WHERE mco.custom_order_id = '".(int)$custom_order_id."'";
	
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	
	public function getCustomOrderMaterial($custom_order_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."multi_custom_order_layer WHERE custom_order_id = '".(int)$custom_order_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getEmpAdminId($user_id)
	{
		$sql ="SELECT user_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		return $data->row['user_id'];
	}	
	
	public function deleteCustomOrder($custom_order_id){
		$sql = "SELECT custom_order_id FROM " . DB_PREFIX ."multi_custom_order WHERE multi_custom_order_id = '".(int)$custom_order_id."'";
		$data = $this->query($sql);
	 //   printr($data);die;
		if($data->num_rows){
			$this->query("DELETE  FROM " . DB_PREFIX . "multi_custom_order  WHERE custom_order_id='".$data->row['custom_order_id']."'");
			$this->query("DELETE  FROM " . DB_PREFIX . "multi_custom_order_layer  WHERE custom_order_id='".$data->row['custom_order_id']."'");
			$this->query("DELETE  FROM " . DB_PREFIX . "multi_custom_order_price  WHERE custom_order_id='".$data->row['custom_order_id']."'");
			$this->query("DELETE  FROM " . DB_PREFIX . "multi_custom_order_quantity  WHERE custom_order_id='".$data->row['custom_order_id']."'");	
			$this->query("DELETE  FROM " . DB_PREFIX . "multi_custom_order_base_price  WHERE custom_order_id='".$data->row['custom_order_id']."'");
			$this->query("DELETE  FROM " . DB_PREFIX . "multi_custom_order_id  WHERE multi_custom_order_id='".$data->row['custom_order_id']."'");		
		}
	}
	
	public function getTotalCustomOrder($user_type_id,$user_id,$filter_array=array(),$cond,$add_book_id='0')
	{
	   //sonu add 21-4-2017
	   $add_id='';
		if($add_book_id!=0)
			$add_id = "AND mcoi.address_book_id='". $add_book_id."'";
	   
	   //end
	   
	   
	     //[kinjal] added on 17-4-2017 for which person have accept decline permission
	    $menu_id = 79;
	    $perm_cond ='( add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%" ) ';
		
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
		//echo $sql;
		$dataper=$this->query($sql);
			
		if($user_type_id == 1 && $user_id == 1 || $dataper->num_rows!='0')
		{
		   // $sql = "SELECT c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt,mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id ANDmco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id  $add_id";
		   
		 $sql="SELECT mpqi.digital_quotation_no,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id FROM digital_quotation as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.digital_quotation_id $add_id";
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
    			
    			if($userEmployee){
    				$str = ' OR ( mco.added_by_user_id IN ('.$userEmployee.') AND mco.added_by_user_type_id = 2 )';
    			}
			$sql="SELECT mpqi.digital_quotation_no,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id FROM digital_quotation as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.digital_quotation_id AND  ((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str )$add_id ";
		}
		if(!empty($filter_array)) {
			if(!empty($filter_array['custom_order_no'])){
				$sql .= " AND mcoi.multi_custom_order_number = '".$filter_array['custom_order_no']."'";
			}
			if(!empty($filter_array['quo_no'])){
				$sql .= " AND mpqi.digital_quotation_no LIKE '%".$filter_array['quo_no']."%'";
			}
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND mcoi.customer_name LIKE '%".addslashes($filter_array['customer_name'])."%'";
			}
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(mco.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}
			
			if(!empty($filter_array['layer'])){
				$sql .= " AND mco.layer = '".$filter_array['layer']."'";
			}
			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND mco.product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND mco.shipment_country_id = '".$filter_array['country']."'";
			}
			
			if(!empty($filter_array['option'])){
				$sql .= " AND mco.option_id = '".$filter_array['option']."'";
			}
			if(!empty($filter_array['postedby']))
			{
			$spitdata = explode("=",$filter_array['postedby']);
				$sql .="AND mco.added_by_user_type_id = '".$spitdata[0]."' AND mco.added_by_user_id = '".$spitdata[1]."'";
			}
		}
		if(!empty($cond)){
				$sql .= $cond;
			}
		$sql .= "GROUP BY mco.multi_custom_order_id";

		//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->num_rows;
		}else{
			return false;
		}
	}
	
	public function getCustomOrders($user_type_id,$user_id,$data,$filter_array=array(),$add_book_id='0'){
		
		$add_id='';
			if($add_book_id!=0)
				$add_id = "AND mcoi.address_book_id='". $add_book_id."'";
				
		$menu_id = 79;
		$perm_cond ='( add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"   ) ';
			
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
			//echo $sql;
		$dataper=$this->query($sql);
			
		if($user_type_id == 1 && $user_id == 1 || $dataper->num_rows!='0')
		{
		  //  $sql = "SELECT mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mco.product_name,mcoi.reference_no, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt,	mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id FROM multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND	mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id  $add_id";
		  
		  	//change by sonu 12-01-2018
		$sql="SELECT mpqi.digital_quotation_no,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id FROM digital_quotation as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.digital_quotation_id $add_id";
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
				$str = ' OR ( mco.added_by_user_id IN ('.$userEmployee.') AND mco.added_by_user_type_id = 2 )';
			}
	//		$sql = "SELECT mco.accept_decline_status,c.country_name,mco.*,mcop.valve_txt,mcop.zipper_txt,mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number,mcoi.customer_name,mcoi.address_book_id,mcoi.reference_no,mcoi.email,mcoi.company_name, mcoi.multi_product_quotation_id FROM multi_custom_order mco LEFT JOIN country c ON c.country_id = mco.shipment_country_id LEFT JOIN multi_custom_order_price mcop ON mco.custom_order_id=mcop.custom_order_id LEFT JOIN multi_custom_order_id as mcoi ON mcoi.multi_custom_order_id=mco.multi_custom_order_id WHERE((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."' )$str)  $add_id";
	
	$sql="SELECT mpqi.digital_quotation_no,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id FROM digital_quotation as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.digital_quotation_id AND  ((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str )$add_id ";
		}
		
		
		if(!empty($filter_array)) {
			if(!empty($filter_array['custom_order_no'])){
				$sql .= " AND mcoi.multi_custom_order_number = '".$filter_array['custom_order_no']."'";
			}
			if(!empty($filter_array['quo_no'])){
				$sql .= " AND mpqi.digital_quotation_no LIKE '%".$filter_array['quo_no']."%'";
			}
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND mcoi.customer_name LIKE '%".addslashes($filter_array['customer_name'])."%'";
			}
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(mco.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}
			
			if(!empty($filter_array['layer'])){
				$sql .= " AND mco.layer = '".$filter_array['layer']."'";
			}
			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND mco.product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND mco.shipment_country_id = '".$filter_array['country']."'";
			}
			
			if(!empty($filter_array['option'])){
				$sql .= " AND mco.option_id = '".$filter_array['option']."'";
			}
			if(!empty($filter_array['postedby']))
			{
			$spitdata = explode("=",$filter_array['postedby']);
				$sql .="AND mco.added_by_user_type_id = '".$spitdata[0]."' AND mco.added_by_user_id = '".$spitdata[1]."'";
			}
		}
		if(!empty($data['cond'])){
				$sql .= $data['cond'];
			}
					$sql .= "GROUP BY mco.multi_custom_order_id";
		if (isset($data['sort'])) {
			
				
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY mco.multi_custom_order_id";	
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
		//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getUserList(){
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
		$data = $this->query($sql);
		//printr($data);die;
		return $data->rows;
	}
	
	public function upadteCustomOrder($custom_order_id){
		$this->query("UPDATE " . DB_PREFIX . "multi_custom_order SET status = '1', custom_order_status = '1', date_modify = NOW() WHERE multi_custom_order_id = '" .(int)$custom_order_id. "'");
		$this->query("UPDATE " . DB_PREFIX . "multi_custom_order_id SET status = '1', custom_order_status = '1', date_modify = NOW() WHERE multi_custom_order_id = '" .(int)$custom_order_id. "'");
    	if($_SESSION['ADMIN_LOGIN_SWISS']=='10' || $_SESSION['ADMIN_LOGIN_SWISS']=='44')
		    $this->sendCustomOrderEmailTest($custom_order_id);
		 else
	        $this->sendCustomOrderEmail($custom_order_id);
	}
	

	public function getCustomOrderCurrecy($selCurrencyId,$source){
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "multi_custom_order_currency WHERE custom_order_currency_id ='".$selCurrencyId."' AND source = '".$source."' ");
		if($data->num_rows){
			$result =array(
							'custom_order_currency_id' => $data->row['custom_order_currency_id'],
							'currency_id' => $data->row['currency_id'],
							'currency_code' => $data->row['currency_code'],
							'currency_rate' => $data->row['currency_rate'],
							'currency_base_rate' => $data->row['currency_base_rate'],
							'source' => 1,
							'date_added' => $data->row['date_added'],
						);
			return $result;
		}else{
			return false;
		}
	}
	
	public function gettermsandconditions($user_id,$user_type_id){
		if($user_type_id == '4')
		{
		$sql = "SELECT ts.termsandconditions,ts.user_id,ts.user_type_id FROM termsandconditions ts WHERE ts.user_id = '".$user_id."' AND ts.user_type_id = '4'  AND ts.is_delete = '0' LIMIT 1";
		}
		else
		{
		$sql = "SELECT ts.termsandconditions,ts.user_id,ts.user_type_id,e.user_id FROM termsandconditions ts,employee e WHERE e.employee_id ='".$user_id."' AND ts.user_id = e.user_id AND ts.user_type_id = '4' AND ts.is_delete = '0' LIMIT 1";
		}

		$data = $this->query($sql);
	//	printr($data);	
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
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
	
	public function getBaseCylinderPrice($currency_id){
		$sql = "SELECT cb.price FROM " . DB_PREFIX . "currency_setting cs, product_cylinder_base_price as cb , country as c WHERE cb.currency_code='".$currency_id."' AND cb.currency_code = c.currency_code AND c.country_id =cs.country_code";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['price'];	
		}else{
			return false;
		}
	}
	
	public function getcalculatePlusMinusQuantity($quantity,$product_id,$height,$width,$gusset,$type,$qty_type=''){
		 $sql="SELECT * FROM digital_quantity WHERE quantity='".$quantity."'";		     
		$data=$this->query($sql); 
		
		if($data->num_rows){
			return $data->row['plus_minus_quantity'];
		}else{
			return false;
		}
	}
	
	public function getSelCurrencyInfo($currency_id){
		$data = $this->query("SELECT cs.price, c.currency_code FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country c ON(c.country_id=cs.country_code) WHERE cs.currency_id= '".$currency_id."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function setCustomOrderCurrency($custom_order_id,$currency_code,$currencyRate,$source){	
		$this->query("INSERT INTO " . DB_PREFIX . "multi_custom_order_currency SET custom_order_id = '".$custom_order_id."', currency_id = '', currency_code = '".$currency_code."', currency_rate = '".(float)$currencyRate."', currency_base_rate = '', source = '".$source."', date_added = NOW()");
		return $this->getLastId();
	}
	

	public function getNewCurrencys(){
		if($_SESSION['ADMIN_LOGIN_SWISS'] == 1 && $_SESSION['LOGIN_USER_TYPE'] == 1)
		{
		   	$data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."'");
		}
		else
		{
    		if($_SESSION['LOGIN_USER_TYPE'] == 2){
    			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    			$set_user_id = $parentdata->row['user_id'];
    			$set_user_type_id = $parentdata->row['user_type_id'];
    		    if($set_user_id=='10')
    			    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");
                elseif($_SESSION['ADMIN_LOGIN_SWISS']=='37' || $_SESSION['ADMIN_LOGIN_SWISS']=='199')
                    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");
                else
                    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");

    		}else{
    			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    		    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");

    		}
    		

		}
		if($data->num_rows){
			return  $data->rows;
		}else{
			return false;
		}
	}
	public function getEmailHistories($custom_order_id)
	{
		$data = $this->query("SELECT mcoc.currency_code, mcoeh.date_added, mcoc.source, mcoc.currency_rate, mcoeh.to_email FROM " . DB_PREFIX . "multi_custom_order_email_history mcoeh RIGHT JOIN multi_custom_order_currency mcoc ON(mcoeh.custom_order_currency_id = mcoc.custom_order_currency_id) WHERE mcoeh.multi_custom_order_id ='".$custom_order_id."' ORDER BY mcoc.date_added DESC ");
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	
	public function deleteProductCustomOrder($custom_order_price_id){
		$cond='';
		$sql = "SELECT custom_order_id,custom_order_quantity_id  FROM " . DB_PREFIX ."multi_custom_order_price WHERE custom_order_price_id = '".(int)$custom_order_price_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			$sql1 = "DELETE  FROM " . DB_PREFIX . "multi_custom_order_price  WHERE custom_order_price_id='".$custom_order_price_id."'";
			$this->query($sql1);
		}
		$sql3 = "SELECT multi_custom_order_id  FROM " . DB_PREFIX ."multi_custom_order WHERE custom_order_id = '".(int)$data->row['custom_order_id']."'";
		$data3 = $this->query($sql3);
		$sql2 = "SELECT custom_order_id  FROM " . DB_PREFIX ."multi_custom_order WHERE multi_custom_order_id = '".(int)$data3->row['multi_custom_order_id']."'";
		$data2 = $this->query($sql2);
		foreach($data2->rows as $val)
		{
			$cond .= 'cusotm_order_id ='.$val['custom_order_id'] .' OR ';
		}	
		$cond=substr($cond,0,-3);
		$sql4 = "SELECT custom_order_price_id  FROM " . DB_PREFIX ."multi_custom_order_price WHERE ".$cond."";
		$data4 = $this->query($sql4);
		//printr($data4);die;
		if($data4->num_rows==0){
			if($data2->num_rows){
			$this->deleteCustomOrder($data3->row['multi_custom_order_id']);
			}
		}
	}
	
	public function update_discount($quantity_id,$discount)
	{
		$this->query("UPDATE " . DB_PREFIX . "multi_custom_order_quantity SET discount = ".$discount." WHERE custom_order_quantity_id = '" .(int)$quantity_id. "'");
	}
	
	public function getQuotationId($quotation_no)
	{

		$data = $this->query("SELECT digital_quotation_id FROM " . DB_PREFIX ."digital_quotation WHERE digital_quotation_no = '".$quotation_no."'");
		if($data->num_rows)
		{
			return $data->row['digital_quotation_id'];
		}
		else
			return false;
	}
	
	public function addQuotationToCustomOrder($data)
	{	
		
		$first_name = addslashes($data['first_name']);
		$last_name = addslashes($data['last_name']);
		$printing_price=array();
		//printr($data);die;
		
	
				$contacts = "SELECT email_1 FROM company_address WHERE email_1='".$data['email']."' AND is_delete=0";
				$datacontacts= $this->query($contacts);
				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
				{
						$sql1 = "INSERT INTO address_book_master SET status = '1', company_name = '".addslashes($data['company'])."',vat_no='".$data['vat_number']."', user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' ,user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added=NOW()";
						$datasql1=$this->query($sql1);
						$address_id = $this->getLastIdAddress();
						$address_book_id = $address_id['address_book_id'];
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET c_address = '".addslashes($data['shipping_address_1'])."',city = '".$data['shipping_city']."',pincode ='".$data['shipping_postcode']."',email_1 = '".$data['email']."', country= '".$data['shipping_country_id']."',phone_no= '".$data['contact_number']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
							$datasql2=$this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($data['shipping_address_1'])."',city = '".$data['shipping_city']."',pincode ='".$data['shipping_postcode']."', email_1 = '".$data['email']."', country= '".$data['shipping_country_id']."', date_added=NOW(),phone_no= '".$data['contact_number']."'";
							$datasql2=$this->query($sql2);
						}
						
						$add_id_fac = "SELECT address_book_id,factory_address_id FROM factory_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd_fac= $this->query($add_id_fac);
						if($dataadd_fac->num_rows)
						{
							if(!isset($data['same_as_above']))
							{
								$sql3 = "UPDATE factory_address SET f_address = '".addslashes($data['billing_address_1'])."',city = '".$data['billing_city']."',pincode ='".$data['billing_postcode']."', country= '".$data['billing_country_id']."' WHERE factory_address_id ='".$dataadd_fac->row['factory_address_id']."'";
								$datasql3=$this->query($sql3);
							}
						}
						else
						{
							$sql3 = "INSERT INTO factory_address SET  address_book_id = '".$address_book_id."',f_address = '".addslashes($data['billing_address_1'])."', country= '".$data['billing_country_id']."', date_added=NOW(),pincode ='".$data['billing_postcode']."',city = '".$data['billing_city']."' ";
							$datasql3=$this->query($sql3);
						}
						
				}
				else
				{	
						$address_book_id = $data['address_book_id'];
						$sql1 = "UPDATE address_book_master SET vat_no='".$data['vat_number']."' WHERE address_book_id ='".$data['address_book_id']."'";

						$datasql1=$this->query($sql1);

						if($data['company_address_id']=='')
						{
							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($data['shipping_address_1'])."',city = '".$data['shipping_city']."',pincode ='".$data['shipping_postcode']."', email_1 = '".$data['email']."', country= '".$data['shipping_country_id']."', date_added=NOW(),phone_no= '".$data['contact_number']."'";
							$datasql2=$this->query($sql2);
						}
						else
						{
							$sql2 = "UPDATE company_address SET c_address = '".addslashes($data['shipping_address_1'])."',city = '".$data['shipping_city']."',pincode ='".$data['shipping_postcode']."',email_1 = '".$data['email']."', country= '".$data['shipping_country_id']."',phone_no= '".$data['contact_number']."' WHERE company_address_id ='".$data['company_address_id']."'";
							$datasql2=$this->query($sql2);
						}
						if($data['factory_address_id']=='')
						{
							$sql3 = "INSERT INTO factory_address SET  address_book_id = '".$address_book_id."',f_address = '".addslashes($data['billing_address_1'])."', country= '".$data['billing_country_id']."', date_added=NOW(),pincode ='".$data['billing_postcode']."',city = '".$data['billing_city']."'";
							$datasql3=$this->query($sql3);
						}
						else
						{
							if(!isset($data['same_as_above']))
							{
								$sql3 = "UPDATE factory_address SET f_address = '".addslashes($data['billing_address_1'])."',city = '".$data['billing_city']."',pincode ='".$data['billing_postcode']."', country= '".$data['billing_country_id']."' WHERE factory_address_id ='".$data['factory_address_id']."'";
								$datasql3=$this->query($sql3);
							}
						}
				}
		
		
		if(!isset($data['shipping_country_id'])) {			
			$shipment_country = $_SESSION['shipment_country'];
		}
		else {
			$shipment_country = $data['shipping_country_id'];
		}
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$userCountry = $this->getUserCountry($user_type_id,$user_id);
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
		$admin_data = '';
		if($_SESSION['LOGIN_USER_TYPE'] != 1)
		{
			$getAdminData = $this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
			$admin_data = ", admin_user_id = '".$getAdminData['international_branch_id']."'";
		}
		
		if(isset($data['ref_no'])) {			
			$ref_no = $data['ref_no'];
		}
		else {
			$ref_no = '';
		}
		

		$sql =  "INSERT INTO ".DB_PREFIX."multi_custom_order_id SET  multi_product_quotation_id='".$data['multi_quote_id']."',company_name='".$this->escape($data['company'])."', website='".$data['website']."', customer_name='".$this->escape($customer_name)."',address_book_id='".$data['address_book_id']."', email = '".$data['email']."', contact_number='".$data['contact_number']."',reference_no ='".$ref_no."', vat_number = '".$data['vat_number']."', shipping_address_id = '".$shipping_address_id."',  billing_address_id = '".$billing_address_id."', order_currency = '".$order_currency_query->row['currency_code']."',currency_rate = '".$order_currency_query->row['product_rate']."',order_note ='".$data['order_note']."', order_instruction = '".$data['order_instruction']."',custom_order_status=1,status = '0',quotation_status = '1', date_added = NOW(),date_modify = NOW(), added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."' $admin_data ";		
	//	printr($sql);
	//	printr($data);//die;
		$this->query($sql);
		$multi_custom_order_id = $this->getLastId();
	
		$custom_order_number = $this->generateCustomOderNumber($multi_custom_order_id);
		$sql =  "UPDATE  ".DB_PREFIX."multi_custom_order_id SET multi_custom_order_number = '".$custom_order_number."' WHERE multi_custom_order_id = '".$multi_custom_order_id."'";
		$this->query($sql);
		foreach($data['digital_product_quotation_price_id'] as $digital_product_quotation_price_id_arr)
		{	
			//printr($digital_product_quotation_price_id_arr);
			$arr = explode("==",$digital_product_quotation_price_id_arr);
			$digital_product_quotation_price_id = $arr[0];
			$digital_product_quotation_id = $arr[1];

			
			

			$template_price=$data['template_price_'.$digital_product_quotation_price_id];			
			$print_price=$data['print_price_'.$digital_product_quotation_price_id];
			$print_price_with_discount=$data['print_price_with_discount_'.$digital_product_quotation_price_id];
		    $color_plate_price_with_discount_count=$data['color_plate_price_with_discount_count_'.$digital_product_quotation_price_id];
			$gress_pouch_price=$data['gress_pouch_price_'.$digital_product_quotation_price_id];
			$digital_print_discount=$data['digital_print_discount_'.$digital_product_quotation_price_id];
			$cust_product_code_id=$data['cust_product_code_id_'.$digital_product_quotation_price_id];
			$stock_product_code_id=$data['stock_product_code_id_'.$digital_product_quotation_price_id];
			$color_plate_price_swisspac=$data['color_plate_price_swisspac'.$digital_product_quotation_price_id];
		//add client order qty and rate 27-11-2019
			$client_order_qty=$data['added_order_qty_'.$digital_product_quotation_price_id];
			$client_rate=$data['added_order_rate_'.$digital_product_quotation_price_id];



			//printr($arr[1]);
			//get records from multi_product_quotation_price
		//	$multi_product_quotation_price = $this->query("SELECT * FROM `digital_product_quotation`  WHERE digital_product_quotation_id ='".$digital_product_quotation_id."'ORDER BY `digital_product_quotation_id` ASC LIMIT 1 ");	
			$multi_product_quotation_price = $this->query("SELECT * FROM `digital_product_quotation_price` WHERE digital_product_quotation_price_id='".$digital_product_quotation_price_id."' ORDER BY `digital_product_quotation_price_id` DESC LIMIT 1");	
			$multi_product_quotation_price_ary = array($multi_product_quotation_price->row['digital_product_quotation_id'] =>  $multi_product_quotation_price->row);
			$multi_product_quotation_price_ary2 =array($multi_product_quotation_price->row['digital_quotation_id'] => $multi_product_quotation_price_ary);
			$price_data=array($multi_product_quotation_price->row['digital_quotation_id'] => $multi_product_quotation_price_ary2);
			
		//	printr($price_data);

			//get records from multi_product_quotation base on product_quotation_id from price table
			$quot_data= $this->query("SELECT * FROM `digital_product_quotation` as  dp , digital_quotation as d  WHERE dp. digital_quotation_id=d. digital_quotation_id AND dp. digital_product_quotation_id ='".$digital_product_quotation_id."' ORDER BY dp. digital_product_quotation_id ASC LIMIT 1");	
		//	$multi_product_quotation =array($multi_product_quotation_price->row['digital_product_quotation_id'] => $quot_data->row);


			//get records from multi_product_quotation_layer base on product_quotation_id from price table
	
	/*		$layer_data= $this->query("SELECT * FROM " . DB_PREFIX . "multi_product_quotation_layer WHERE product_quotation_id = '".$multi_product_quotation_price->row['product_quotation_id']."' ");	
		$multi_product_quotation[$multi_product_quotation_price->row['product_quotation_id']]['layer']=$layer_data->rows;*/


		
			//get records from multi_product_quotation_quantity based on product_quotation_quantity_id from price table
			$qty_data = $this->query("SELECT * FROM `digital_product_quotation` as  dp , digital_quotation as d  WHERE dp. digital_quotation_id=d. digital_quotation_id AND dp. digital_product_quotation_id ='".$digital_product_quotation_id."' ORDER BY dp. digital_product_quotation_id ASC LIMIT 1 ");
			//	printr($qty_data);
		//	printr($qty_data );
			$qty_data->row['template_price'] = $template_price;
			$qty_data->row['print_price'] = $print_price;
			$qty_data->row['print_price_with_discount'] = $print_price_with_discount;
			$qty_data->row['plate_price_with_discount'] = $plate_price_with_discount;
			$qty_data->row['color_plate_price_with_discount_count'] = $color_plate_price_with_discount_count;
			$qty_data->row['gress_pouch_price'] = $gress_pouch_price;
			$qty_data->row['digital_print_discount'] = $digital_print_discount;
			$qty_data->row['cust_product_code_id'] = $cust_product_code_id;
			$qty_data->row['stock_product_code_id'] = $stock_product_code_id;
			$qty_data->row['color_plate_price_swisspac'] = $color_plate_price_swisspac;
			$qty_data->row['client_order_qty'] = $client_order_qty;
			$qty_data->row['client_rate'] = $client_rate*$client_order_qty;
		
				//$qty_data->row=array_merge($qty_data->row,$printing_price);
		$multi_product_quotations=array($multi_product_quotation_price->row['digital_quotation_id'] => $qty_data->row);
		
		//printr($multi_product_quotations);
		/*$printing_price=array('template_price'=>$template_price,
								'print_price'=>$print_price,
								'print_price_with_discount'=>$print_price_with_discount,
								'plate_price_with_discount'=>$plate_price_with_discount,
								'color_plate_price_with_discount_count'=>$color_plate_price_with_discount_count,
								'gress_pouch_price'=>$gress_pouch_price,
								'digital_print_discount'=>$digital_print_discount,
			);
*/

	        $multi_product_quotations[$multi_product_quotation_price->row['digital_quotation_id']]['price_data']=$price_data[$multi_product_quotation_price->row['digital_quotation_id']][$multi_product_quotation_price->row['digital_quotation_id']];
		
			$multi_product_quotation[$multi_product_quotation_price->row['digital_quotation_id']]['product_quotation_quantity_data']=$multi_product_quotations;
			
			$mul_pro_quto[]=$multi_product_quotation;
		

/*
			$mul_pro_quto['template_price']=$template_price;
			$mul_pro_quto['print_price']=$print_price;
			$mul_pro_quto['print_price_with_discount']=$print_price_with_discount;
			$mul_pro_quto['plate_price_with_discount']=$plate_price_with_discount;
			$mul_pro_quto['color_plate_price_with_discount_count']=$color_plate_price_with_discount_count;
			$mul_pro_quto['gress_pouch_price']=$gress_pouch_price;
			$mul_pro_quto['digital_print_discount']=$digital_print_discount;*/
			


	/*	$multi_product_quotation[$multi_product_quotation_price->row['digital_quotation_id']]['template_price']=$template_price;
			$multi_product_quotation[$multi_product_quotation_price->row['digital_quotation_id']]['print_price']=$print_price;
		$multi_product_quotation[$multi_product_quotation_price->row['digital_quotation_id']]['print_price_with_discount']=$print_price_with_discount;
			$multi_product_quotation[$multi_product_quotation_price->row['digital_quotation_id']]['plate_price_with_discount']=$plate_price_with_discount;
			$multi_product_quotation[$multi_product_quotation_price->row['digital_quotation_id']]['color_plate_price_with_discount_count']=$color_plate_price_with_discount_count;
			$multi_product_quotation[$multi_product_quotation_price->row['digital_quotation_id']]['gress_pouch_price']=$gress_pouch_price;
			$multi_product_quotation[$multi_product_quotation_price->row['digital_quotation_id']]['digital_print_discount']=$digital_print_discount;*/
		}	
	//	printr($mul_pro_quto);
	//	die;
			
	foreach($mul_pro_quto as $multi_product_quotation)
	{
		foreach($multi_product_quotation as $product_quotation_id=>$product_quotation_data)
		{	

				//printr($product_quotation_data);die;
			foreach($product_quotation_data['product_quotation_quantity_data'] as $qty_id=>$product_quotation_quantity_data)
				{
						
							
							$size_details=array();
							if(isset($product_quotation_quantity_data['size_id']))
								$size_details=$this->getSize($product_quotation_quantity_data['size_id']);								
			   	    			$spout_detail = $this->getSpoutName($product_quotation_quantity_data['spout_id']);
			            		$acce_detail = $this->getAccessorieName($product_quotation_quantity_data['accessorie_id']);
			            		$make_up_pouch = $this->ProductMake($product_quotation_quantity_data['make_id']);
			            		$acce_sec_detail = $this->getAccessorieName($product_quotation_quantity_data['accessorie_sec_id']);
			            		$zip_detail = $this->getZipperName($product_quotation_quantity_data['zipper_id']);
			            	
			            		$color_detail = $this->getColor($product_quotation_quantity_data['color_id']);
			            		$total_color=explode('==', $product_quotation_quantity_data['total_color']);






			            







							//printr($product_quotation_data['size_id']);
							$sql =  "INSERT INTO ".DB_PREFIX."multi_custom_order SET product_id = '".(int)$product_quotation_quantity_data['product_id']."', product_name = '".$product_quotation_quantity_data['product_name']."', printing_effect = '".$product_quotation_quantity_data['printing_effect']."', height = '".(float)$size_details['height']."', width = '".(float)$size_details['width']."', gusset = '".(float)$size_details['gusset']."', volume = '".$size_details['volume']."', use_device = '".getDevice()."', status = '0',quotation_status = '1', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', shipment_country_id = '".$product_quotation_quantity_data['country_id']."', added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."', multi_custom_order_id = '".$multi_custom_order_id."'";
						
						
							$this->query($sql);
							$CustomOrderId = $this->getLastId();

					//	printr($sql);
							
							//get records from multi_product_quotation_base_price base on product_quotation_id from price table
						/*	$base_price_data= $this->query("SELECT * FROM " . DB_PREFIX . "multi_product_quotation_base_price WHERE product_quotation_id = '".$product_quotation_id."' LIMIT 1");	
							$base_price =$base_price_data->row;
							
							$this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_base_price SET custom_order_id = '".(int)$CustomOrderId."', ink_base_price = '".$base_price['ink_base_price']."', ink_solvent_base_price = '".$base_price['ink_solvent_base_price']."', printing_effect_base_price = '".$base_price['printing_effect_base_price']."', adhesive_base_price = '".$base_price['adhesive_base_price']."', cpp_adhesive_base_price = '".$base_price['cpp_adhesive_base_price']."', adhesive_solvent_base_price = '".$base_price['adhesive_solvent_base_price']."', packing_price = '".$base_price['packing_price']."', spout_packing_price = '".$base_price['spout_packing_price']."', spout_courier_price = '".$base_price['spout_courier_price']."', transport_width_base_price = '".$base_price['transport_width_base_price']."', transport_height_base_price = '".$base_price['transport_height_base_price']."', cylinder_base_price = '".$base_price['cylinder_base_price']."', cylinder_vendor_base_price = '".$base_price['cylinder_vendor_base_price']."', cylinder_currency_base_price = '".$base_price['cylinder_currency_base_price']."', fuel_surcharge = '".$base_price['fuel_surcharge']."', service_tax = '".$base_price['service_tax']."', handling_charge = '".$base_price['handling_charge']."', date_added = NOW()");				
									*/


						/*	foreach($product_quotation_data['layer'] as $layer_arr){								
								$setSql = "INSERT INTO ".DB_PREFIX."multi_custom_order_layer SET custom_order_id = '".(int)$CustomOrderId."', layer='".$layer_arr['layer']."',material_id='".$layer_arr['material_id']."',material_gsm='".$layer_arr['material_gsm']."',material_thickness='".$layer_arr['material_thickness']."',material_price='".$layer_arr['material_price']."',material_name='".$layer_arr['material_name']."',layer_wise_gsmthickness='".$layer_arr['layer_wise_gsmthickness']."',layer_wise_price='".$layer_arr['layer_wise_price']."',date_added=NOW()";
								$this->query($setSql);
							}*/
					


						
								//$printr_price = 'printr_price_'.$digital_product_quotation_price_id;
							
							
							
							
								
								
								foreach($product_quotation_quantity_data['price_data'] as $price_arr)
								{
									

										$this->query("UPDATE  ".DB_PREFIX."multi_custom_order SET currency = '".$price_arr['currency_code']."' WHERE custom_order_id = '".$CustomOrderId."'");
				


								//	printr($price_arr);
									$this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_quantity SET custom_order_id = '".$CustomOrderId."',client_order_qty='".$product_quotation_quantity_data['client_order_qty']."',quantity = '".$price_arr['quantity']."',back_color = '".$product_quotation_quantity_data['back_color']."',front_color = '".$product_quotation_quantity_data['front_color']."',total_color = '".$product_quotation_quantity_data['total_color']."', product_code_id='".$product_quotation_quantity_data['cust_product_code_id']."',stock_product_code_id='".$product_quotation_quantity_data['stock_product_code_id']."',date_added = NOW()");
									$customOrderQuantityId = $this->getLastId();	
									//	printr("INSERT INTO ".DB_PREFIX."multi_custom_order_quantity SET custom_order_id = '".$CustomOrderId."',quantity = '".$price_arr['quantity']."',back_color = '".$product_quotation_quantity_data['back_color']."',front_color = '".$product_quotation_quantity_data['front_color']."',total_color = '".$product_quotation_quantity_data['total_color']."',product_code_id = '".$product_quotation_quantity_data['product_code_id']."', date_added = NOW()");	

								//	$cust_total = 'cust_total_'.$product_quotation_quantity_data['product_quotation_price_id'];
									$this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_price SET custom_order_id = '".$CustomOrderId."', custom_order_quantity_id = '".$customOrderQuantityId."', transport_type = '".$product_quotation_quantity_data['transport_type']."', zipper_txt = '".$zip_detail['zipper_name']."', valve_txt = '".$product_quotation_quantity_data['valve']."', spout_txt = '".$spout_detail['spout_name']."', make_pouch = '".$product_quotation_quantity_data['make_id']."',  accessorie_txt = '".$acce_detail['product_accessorie_name']."',  accessorie_txt_corner = '".$acce_detail['product_accessorie_name']."',transport_price='".$price_arr['transport_price_per_pouch']."',total_price_with_tax='".$price_arr['total_price_with_tax']."',total_price='".$price_arr['total_price']."',pouch_price='".$price_arr['price']."',	pouch_price_with_tax='".$price_arr['pouch_price_with_tax']."',color_plate_price='".$price_arr['color_plate_price']."',color_plate_price_swisspac='".$product_quotation_quantity_data['color_plate_price_swisspac']."',client_total_price='".$product_quotation_quantity_data['client_rate']."',date_added = NOW(),template_price='".$product_quotation_quantity_data['template_price']."',print_price='".$product_quotation_quantity_data['print_price']."',print_price_with_discount='".$product_quotation_quantity_data['print_price_with_discount']."',plate_price_with_discount='".$product_quotation_quantity_data['plate_price_with_discount']."',gress_pouch_price='".$product_quotation_quantity_data['gress_pouch_price']."',digital_print_discount='".$product_quotation_quantity_data['digital_print_discount']."',color_plate_price_with_discount_count='".$product_quotation_quantity_data['color_plate_price_with_discount_count']."'");
									$customOrderPriceId = $this->getLastId();	

								//	printr("INSERT INTO ".DB_PREFIX."multi_custom_order_price SET custom_order_id = '".$CustomOrderId."', custom_order_quantity_id = '".$customOrderQuantityId."', transport_type = '".$product_quotation_quantity_data['transport_type']."', zipper_txt = '".$zip_detail['zipper_name']."', valve_txt = '".$product_quotation_quantity_data['valve']."', spout_txt = '".$spout_detail['spout_name']."', make_pouch = '".$product_quotation_quantity_data['make_id']."',  accessorie_txt = '".$acce_detail['product_accessorie_name']."',  accessorie_txt_corner = '".$acce_detail['product_accessorie_name']."',transport_price='".$price_arr['transport_price_per_pouch']."',total_price_with_tax='".$price_arr['total_price_with_tax']."',total_price='".$price_arr['total_price']."',pouch_price='".$price_arr['price']."',	pouch_price_with_tax='".$price_arr['pouch_price_with_tax']."',color_plate_price='".$price_arr['color_plate_price']."',date_added = NOW(),template_price='".$product_quotation_quantity_data['template_price']."',print_price='".$product_quotation_quantity_data['print_price']."',print_price_with_discount='".$product_quotation_quantity_data['print_price_with_discount']."',plate_price_with_discount='".$product_quotation_quantity_data['plate_price_with_discount']."',gress_pouch_price='".$product_quotation_quantity_data['gress_pouch_price']."',digital_print_discount='".$product_quotation_quantity_data['digital_print_discount']."',color_plate_price_with_discount_count='".$product_quotation_quantity_data['color_plate_price_with_discount_count']."'");	
								}				
							
				}
		}
	}
	    //if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
	// die;
		return $multi_custom_order_id;		
	}
	
	public function getUserCountry($user_tyep_id,$user_id){
		$sql = "SELECT co.country_id, co.country_code, co.currency_id,co.currency_code FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) WHERE ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_tyep_id."' AND ad.address_type_id = '0'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function generateCustomOderNumber(){
		
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'multi_custom_order_id'");
	//	printr("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'multi_custom_order_id'");die;
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,5,'0',STR_PAD_LEFT);
		$number = 'DPCUST'.$strpad;
		return $number;
	}
	
	public function updateCustomOrderStatus($custom_order_id,$status_value){
		
		$sql = "UPDATE " . DB_PREFIX . "multi_custom_order SET status = '".$status_value."', date_modify = NOW() WHERE multi_custom_order_id = '" .(int)$custom_order_id. "'";
		$this->query($sql);
		
		$sql = "UPDATE " . DB_PREFIX . "multi_custom_order_id SET status = '".$status_value."', date_modify = NOW() WHERE multi_custom_order_id = '" .(int)$custom_order_id. "'";
		$this->query($sql);
	}
	public function getFullCustomDetail($custom_order_id)
	{
		$sql = "SELECT mco.product_note, mco.product_instruction, mcod.name, mcod.order_product_die_line_id FROM " . DB_PREFIX ." multi_custom_order as mco, multi_custom_order_product_die_line as mcod WHERE mco.custom_order_id='".$custom_order_id."' AND mco.custom_order_id=mcod.order_product_id ";
		$data = $this->query($sql);
		
		$sql2 = "SELECT image_name,order_product_image_id FROM " . DB_PREFIX ." multi_custom_order_product_image WHERE order_product_id='".$custom_order_id."'";
		$data2 = $this->query($sql2);
		
		if($data->num_rows){
			return array('multi_order' => $data->rows,
						'artwork' => $data2->rows);
		}else{
			return false;
		}	
	}
	public function getQuotaDetail($quotation_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX ." multi_product_quotation WHERE multi_product_quotation_id='".$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	
	//[kinjal] (10-9-2016)
	public function updateAccDeclinestatus($value,$cond,$status)
	{	
		$value .= ",accept_decline_status='".$status."'";
		//echo "UPDATE " . DB_PREFIX . "multi_custom_order SET ".$value." WHERE ".$cond."";die;
		$data_update = $this->query("UPDATE " . DB_PREFIX . "multi_custom_order SET ".$value." WHERE ".$cond."");
		return $data_update;
	}
	//2-3-2017 sonu told by vikas sir
	
	public function getUserData($user_id,$user_type_id)
	{
		$sql="SELECT email FROM account_master WHERE  user_id='".$user_id."' AND user_type_id='".$user_type_id."'" ;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	//2-3-2017 sonu told by vikas sir
	
	public function sendCustomOrderEmail($custom_order_id,$toEmail = '',$setCustomOrderCurrencyId=''){	
	    
	    $custom_detail = $this->getDielineArtwork($custom_order_id);
	//	printr($custom_detail);printr($custom_order_id);printr($toEmail);printr($setCustomOrderCurrencyId);die;
		//$url_dieline
		//$base_url = base_url();
		//[sonu] edited on 11-5-2017
		
		 $attachments =array();
		if(!empty($custom_detail['multi_order']))
		{ //echo 'sonu';
    		foreach($custom_detail['multi_order'] as $image)
    		{
    					$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    					
    					if($ext=='pdf')
    					{
    						$url_dieline[] = DIR_UPLOAD.'admin/digital_order_dieline_pdf/'.$image['name'].'';
    							
    					}
    					else
    					{
    						$url_dieline[] = DIR_UPLOAD.'admin/digital_order_dieline_image/100_'.$image['name'].'';
    					}
    				
    		}
		}
				
	  //  printr($url_dieline);
	
		    	if(isset($url_dieline))
					$attachments = $url_dieline;		    
			
		
	//    printr($attachments);
		$getData = ' custom_order_id,mco.layer, mco.added_by_user_id, mco.added_by_user_type_id, customer_name, customer_gress_percentage,  shipment_country_id, multi_custom_order_number,mco.multi_custom_order_id, custom_order_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,custom_order_type,layer,added_by_country_id, currency, currency_id, currency_price, mco.date_added, cylinder_price,tool_price, mcoi.email as customer_email,mco.status,discount,customer_gress_percentage,gress_percentage,gress_air,gress_sea,quantity_type,mcoi.shipping_address_id,mcoi.billing_address_id';
		$data = $this->getCustomOrder($custom_order_id,$getData);
		//if($_SESSION['ADMIN_LOGIN_SWISS']=='41' && $_SESSION['LOGIN_USER_TYPE']=='4')
		//printr($data);die;
		
		$shipping_address = $this->getCustomAddress($data[0]['shipping_address_id']);
		$billing_address = $this->getCustomAddress($data[0]['billing_address_id']);
		foreach($data as $dat)
	   {
		   //printr($dat);
		 $qdata= $this->getCustomOrderQuantity($dat['custom_order_id']);
		 $UserDetail = $this->getUserData($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		 //printr($UserDetail);
		 if($qdata!='')
		  $quantityData[] =$qdata;
	   }
	    $str=$dat['product_name'];       


        $menu_id = $this->getMenuPermission(151,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$user_admin_id=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);

		//[kinjal] for pratik sir to go gress price mail on 28-4-2017
		$menu_id_pratik_sir = $this->getMenuPermission(151,'19','2',1);
		//END [kinjal]
		

		$menu_admin_permission='';
		if($_SESSION['LOGIN_USER_TYPE']!='4')
		{
			$menu_admin_permission=$this->getMenuPermission(151,$user_admin_id['international_branch_id'],4);
		}


        /*printr($menu_id);
        printr($user_admin_id);
        printr($menu_admin_permission);
        die;*/
		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$str),0,3));		
		$sub =$dat['multi_custom_order_number'] .' - '.ucwords($dat['customer_name']).' - Digital  printed '.$first;
		//printr($quantityData);





		$gussetvalue='';$s='';$sub2='   ';$m_k=array();
		foreach($quantityData as $k=>$qty_data)
		{
			foreach($qty_data as $tag=>$qty)
			{
				//printr($qty);
				//die;
				foreach($qty as $q=>$arr)
				{
					
					
					 $client_order_qty='';
				    if($arr[0]['client_order_qty']!=0){
						$client_order_qty='<br><span style="color:blue;font-size: 12px; ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Client Order Qty :</b> '.$arr[0]['client_order_qty'].' </span>';
				    }
					
					
					
					if($arr[0]['gusset'] == '')
					{
						$gussetval = 'No Gusset';
					}
					else
					{
						if($dat['product_id'] == '7')
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm gusset }';
						}
						elseif($arr[0]['gusset'] == '0')
						{
							 $gussetval = ' ';	
						}
						else
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.
							$arr[0]['gusset'].'mm gusset}';
						}
					}
					if($dat['product_id'] != '10')
					{
						if($arr[0]['volume']!='')
						{ 
							if($s!=$qty)
							{
								$sub2.=$arr[0]['volume'].' , ';
								$s=$qty;
							}
							$gussetvalue.=$arr[0]['volume'].' , ';
						}
					}
					if($gussetvalue=='')
						$gussetvalue=' W : '.(int)$arr[0]['width'].'mm  x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval;
					if($arr[0]['volume']=='')
						$arr[0]['volume']='Custom';
					
					if($dat['product_id'] != '10')
						$key='W : '.(int)$arr[0]['width'].'mm x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval.' ['.$arr[0]['volume'].']';
					else
						$key='W : '.(int)$arr[0]['width'].'mm x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval;
						$m_k[$arr[0]['custom_order_quantity_id']]=array();
				
					$new_data[$arr[0]['text']][$key.' ('.$arr[0]['layer'].') '][$q.'('.$arr[0]['custom_order_quantity_id'].')'][$tag][]=$arr[0];
					if($dat['product_id'] != '10')
					{
						$sub1= ' '.$arr[0]['zipper_txt'].' '.$arr[0]['valve_txt'].''.$sub2; 
					}
					else
						$sub1= $sub2; 
				}
			}	
			$n[$tag][]=array('width'=>$arr[0]['width'],'height'=>$arr[0]['height'],'gusset'=>$arr[0]['gusset'],'volume'=>$arr[0]['volume']);
			$t=$tag;
		}
		$addedByInfo = $this->getUser($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
	//printr($addedByInfo);die;
		//printr($m_k);die;
		$sub1= substr($sub1,0,-3);
		$sub=$sub.$sub1;
		//printr($sub);die;
		$html='';$tax_str='';
		if($addedByInfo)
		{
			$pqcquery='';
			$selCurrency = $this->getCustomOrderCurrecy($setCustomOrderCurrencyId,1);
		//	printr($selCurrency);
			if($selCurrency)
				$pqcquery = " custom_order_currency_id = '".$selCurrency['custom_order_currency_id']."', ";
			$currency_rate[]=array('currency_rate'=>1,'user'=>0);
			if($UserDetail['email'] != '' || $toEmail != '')
				$currency_rate[]=array('currency_rate'=>($selCurrency['currency_rate']!='')?$selCurrency['currency_rate']:1,'user'=>1);
			else
				$currency_rate[]=array('currency_rate'=>1,'user'=>2);
			//printr($currency_rate);die;
			$html .='<table border="0px">';
			foreach($currency_rate as $cr)
			{
				$gettermsandconditions = $this->gettermsandconditions($dat['added_by_user_id'],$dat['added_by_user_type_id']);
				
				$shippingCountry = $this->getCountry($dat['shipment_country_id']);
				
				$html .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				//printr($new_data);
				foreach($new_data as $key=>$value)
				{
					//printr($qty_data);die;
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
						//printr($size);
					foreach($qty_data as $qty=>$transport)
					{//printr($transport);//die;
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{ //if($_SESSION['ADMIN_LOGIN_SWISS']=='41' && $_SESSION['LOGIN_USER_TYPE']=='4')
						  //  printr($k);
								if($k == "By Air") 
								{
									$color = "red";	
								}elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
 
								//printr($color);
													/*	if($selCurrency)
														{
															$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code']);	
															//printr($cr['currency_rate']);
															$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
															$tool_price=0;
															if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
																$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
															if($cylinder_base_price)
															{
																if($cylinder_currency_price < $cylinder_base_price)
																	$cylinder_price = $cylinder_base_price;	
																else
																	$cylinder_price = $cylinder_currency_price;	
															}
															else
																$cylinder_price = $cylinder_currency_price;	
														 }
														 else
														 {
															$selCurrency['currency_code'] = $dat['currency'];
															$cylinder_price = $records[0]['cylinder_price'];
															$tool_price = $records[0]['tool_price'];
														 }*/
							if($dat['custom_order_type']==1)
								$type=1;	
							else
								$type=0;
								$qty_type = ''; $bag_types='bags'; $bag_type='bag';
							if($dat['product_id'] == '6'){
							    $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
						
						
							//if($_SESSION['ADMIN_LOGIN_SWISS']=='41' && $_SESSION['LOGIN_USER_TYPE']=='4')
							    //printr($bag_type.'----'.$qty_type.'----'.$bag_types.'-----'.$records[0]['quantity_type']);
							
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
					
					
					
						//	echo $cr['user'];
						/*	if($cr['user']==0 || $addedByInfo['country_id']==155)
							{
								if($records[0]['gress_percentage'] != '0.000' OR $records[0]['gress_air'] !='0.000' OR $records[0]['gress_sea'] !='0.000')
								{
									if($dat['shipment_country_id']==91)
										$txt='[Your Purchase Price]';
									else
										$txt='';
									$gress = '<tr><td colspan="2" >&nbsp;</td></tr><tr valign="top"><td width="60" colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Percentage Prices:'.$txt.'</b></td></tr>';								
									$newPircegress = ((($records[0]['totalPrice'] - $records[0]['customerGressPrice'] - $records[0]['gress_price']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
								//	echo '((('.$records[0]['totalPrice'].' - '.$records[0]['customerGressPrice'] .'-'. $records[0]['gress_price'].') / '.(float)$dat['currency_price'].') / '.(float)$cr['currency_rate'].' )';
									$newPircegress = $newPircegress/$qty;
									$taxvaluegress='';
									if($records[0]['tax_name']=='Normal')
											$tax_name_data='No Form';
										else
											$tax_name_data=$records[0]['tax_name'];
									//printr($tax_name_data);
									//die;
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										$newPircegress =$this->numberFormate(($newPircegress-($newPircegress*$records[0]['discount']/100)),"3");
										$totaldisgress = $newPircegress + ($newPircegress*$records[0]['excies']/100);
										$finaldisgress = $totaldisgress + ($totaldisgress *$records[0]['tax_percentage']/100);
										
										$taxvaluedisgress = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' per 1 '.$bag_type.' including of all taxes.';	
									}						
									if($dat['shipment_country_id'] == '111')
									{
										$totalgress = $newPircegress + ($newPircegress*$records[0]['excies']/100);
										$finalgress = $totalgress + ($totalgress *$records[0]['tax_percentage']/100);
										$taxvaluegress = '';
										$taxvaluegress = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' per 1 '.$bag_type.' including of all taxes.';
									}
									
									if($k=='By Air')
									{
										$gp=$records[0]['gress_air'];
										$txt_tarnsport='Door delivery by  Air in '.$shippingCountry['country_name'];
									}
									//printr($txt_tarnsport);
									if($k=='sea')
									{
										$gp=$records[0]['gress_sea'];
										$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port';
									}
									if($k=='pickup')
									{
										$gp=$records[0]['gress_percentage'];
										$tax_str='Transportation cost NOT included.';
										$txt_tarnsport='By Pickup ';
									}
									if($dat['product_id'] != '10')
										$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
									else
										$bag='';
									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate($newPircegress ,"3").' per 1 '.$bag_type.' '.$taxvaluegress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';	
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										if($dat['product_id'] != '10')
											$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
										else
											$bag='';
										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b></td><td>'.$dat['currency'].' '.$newPircegress.' per 1 '.$bag_type.' '.$taxvaluedisgress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';
									}
								}	
							}*/
						//	$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							//echo '((('.$records[0]['totalPrice'].' +'. $records[0]['customerGressPrice'].') / '.(float)$dat['currency_price'].') / '.(float)$cr['currency_rate'] .')';
							
						
							$newPirce =$records[0]['pouch_price'];//per pouch price
							$gress_pouch_price =$records[0]['gress_pouch_price'];//per gress pouch price
							$taxvalue='';
						//	printr($records[0]);
						
							if($records[0]['tax_name']=='Normal')
								$tax_name_data='No Form';
							else
								$tax_name_data=$records[0]['tax_name'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
								$totaldis = $newPirce + ($newPirce*$records[0]['excies']/100);
								$finaldis = $totaldis + ($totaldis *$records[0]['tax_percentage']/100);
								$taxvaluedis = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 '.$bag_type.' including of all taxes.';											
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$total = $newPirce + ($newPirce*$records[0]['excies']/100);
								$final = $total + ($total *$records[0]['tax_percentage']/100);
								$taxvalue = '';
								$taxvalue = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($final,'3').' per 1 '.$bag_type.' including of all taxes.';
							}
							if($dat['product_id'] != '10')
									$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
								else
									$bag='';
							if($k=='By Air')
							{
								$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'];
							}
							if($k=='sea')
							{
								$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port';
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
							 
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$dat['currency'].' '.$this->numberFormate($newPirce ,"3").' per 1 '.$bag_type.' '.$taxvalue.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$qty.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span>'.$client_order_qty.'</td></tr>';	
						
    					/*	if(!empty($menu_id) || !empty($menu_id_pratik_sir))
    						{
    							if($dat['product_id'] != '10')
    								$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
    							else
    								$bag='';
    							$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Gress Price : </b>'.$dat['currency'].' '.$gress_pouch_price.' per 1 '.$bag_type.' '.$taxvaluedis.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
    						}	*/
							
						}
						
					$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Make up of pouch  : </b> Custom  Digital Printed '.$dat['product_name'].' ';
					if($dat['product_id'] != '10')
						$m.=str_replace('<br>',' ',$records[0]['email_text']);
					$m.='</td></tr>';
					}
					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Size : </b>'.$size.'</td></tr> ';
				
				
					$html .=$m;
					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Printing Ink To Use : </b>'.$records[0]['printing_effect'].'</td></tr>';
					

					   $total_color = explode('==',$records[0]['total_color']);   


					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Quoted Printing in : </b>'.$total_color[1].' Colors </td></tr>';
				

					if($addedByInfo['country_id']==155)
						$txt='[Your Client Price]';
					else
						$txt='';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html .=$pmq;				
					$html .=$pmq_gress;

					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					
				;
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html .= '</table>';
				$html .= '<div><b> Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				if(!empty($shipping_address)){
				$html .= '<div><b> Shipping Address ( Delivery Address ) : </b><br>&nbsp;&nbsp;&nbsp;&nbsp; '.nl2br($shipping_address['address']).'<br>&nbsp;&nbsp;&nbsp;&nbsp; '.$shipping_address['city'].','.$shipping_address['state'].'<br>';
				if($shipping_address['postcode']!=''){ 
					$html .= 'Postcode'.$shipping_address['postcode'];
				}
				if($data[0]['contact_number']!=''){ 
					$html .= '<br>&nbsp;&nbsp;&nbsp;&nbsp; Contact no'.$data[0]['contact_number'];
					}
				$html .= '</div>';
			}
				$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				if(!empty($billing_address)){
				$html .= '<div><b>Billing Address ( Buyer Address ) : </b><br> '.nl2br($billing_address['address']).'<br>&nbsp;&nbsp;&nbsp;&nbsp; '.$billing_address['city'].','.$billing_address['state'].'<br> ';
				if($billing_address['postcode']!=''){ 
					$html .= 'Postcode'.$billing_address['postcode'];
					}
				$html .= '</div>';
			}
				
				
			    //printr($cr);//printr($toEmail);//die;
		
				if($cr['user']==1 || $cr['user']==2)
				{
					
					if($cr['user']==1)
						$email_temp[]=array('html'=>$html,'email'=>($UserDetail['email'])?$UserDetail['email']:ADMIN_EMAIL);
					if($cr['user']==2)
						$email_temp[]=array('html'=>$html,'email'=>$addedByInfo['email']);
				}
				if($toEmail!='' && $cr['user']!=0)
					$email_temp[]=array('html'=>$html,'email'=>$toEmail);
				if($cr['user']==0)
					$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);


				
				
				if($dat['added_by_user_type_id']=='2')
				{
				    $datauser = $this->getUser($dat['added_by_user_id'],$dat['added_by_user_type_id']);
					$datauser_admin = $this->getUser($datauser['international_branch_id'],4);
					if($datauser_admin['email1']!='' && $datauser_admin['email1']!=$datauser_admin['email'])
					    $email_temp[]=array('html'=>$html,'email'=>$datauser_admin['email1']);
				} 
					// added email for ankitsir on 19-4-2017 				
				//offline_id = 71 online id = 96
				$add_email_prashant = $this->getUser(144,2);
				     $email_temp[]=array('html'=>$html,'email'=>$add_email_prashant['email']);
				 $add_email_pratikbhai = $this->getUser(19,2);
				     $email_temp[]=array('html'=>$html,'email'=>$add_email_pratikbhai['email']);
			 	$add_email_msn = $this->getUser(237,2);
				     $email_temp[]=array('html'=>$html,'email'=>$add_email_msn['email']);
				        
				     
				$html='<table border="0px">';
			}

     // printr($email_temp);die;
		//	printr($html);die;
		
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(1); 
			$formEmail = $addedByInfo['email'];							
			$firstTimeemial = 0;
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
			$path = HTTP_SERVER."template/product_quotation.html";
			$output = file_get_contents($path);  
			$search  = array('{tag:header}','{tag:details}');
			$signature = 'Thanks.';
			if($addedByInfo['email_signature'])
				$signature = nl2br($addedByInfo['email_signature']);
			
		
			
			//printr($email_temp);die;
			foreach($email_temp as $val)
			{
				if($toEmail == '')
				{
					$toEmail = $formEmail;
					$firstTimeemial = 1;
				}				
				$subject = $sub;
				$message = '';
				if($val['html'])
				{
					$tag_val = array(
						"{{productDetail}}" =>$val['html'],
						"{{signature}}"	=> $signature,
					);
					if(!empty($tag_val))
					{
						$desc = $temp_desc;
						foreach($tag_val as $k=>$v)
						{
							@$desc = str_replace(trim($k),trim($v),trim($desc));
						} 
					}
					$replace = array($subject,$desc);
					$message = str_replace($search, $replace, $output);
				}
		
    			   send_email($val['email'],$formEmail,$subject,$message,$attachments,'','1');
				
	
			}		
		}
	
		    //die;
		$qstr_customer = '';
		if($UserDetail['email'] != '' && $firstTimeemial == 1)
		{
			$customer_email = $UserDetail['email'];
			$qstr_customer = " sent_customer = 1, customer_email = '".$customer_email."', ";
		}
		$qstr = '';
		if($firstTimeemial)
			$qstr = 'sent_admin = 1,';
		
		$this->query("INSERT INTO `" . DB_PREFIX . "multi_custom_order_email_history` SET multi_custom_order_id = '".$dat['multi_custom_order_id']."', customer_name = '".addslashes($dat['customer_name'])."', user_type_id = '" .$dat['added_by_user_type_id']. "', user_id = '" .$dat['added_by_user_id']. "', to_email = '".$toEmail."', from_email = '" .$formEmail. "',   $qstr_customer admin_default_email='".ADMIN_EMAIL."', status = '1', $qstr $pqcquery date_added = NOW()");
	//	die;
	}
	
	//sonu add 21-4-2017
	public function getCustomerAllDetail($address_book_id)
	 {
		$sql = "SELECT aa.address_book_id,aa.vat_no, aa.company_name, cs.company_address_id, cs.c_address,cs.city as c_city ,cs.state as c_state ,cs.pincode as c_pincode ,cs.email_1,fa.factory_address_id, fa.f_address ,fa.city as f_city , fa.state as f_state ,fa.pincode as f_pincode  FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND 	aa.address_book_id = '".$address_book_id."' GROUP BY aa.address_book_id";
		 //echo $sql;
			$data = $this->query($sql);
	
			if ($data->num_rows) {	
				return $data->row;
			}else {	
				return false;
			}
    }
	
	//21-4-2017 sonu add
	 public function getLastIdAddress() {
        $sql = "SELECT address_book_id FROM  address_book_master ORDER BY address_book_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row;
        } else {
            return false;
        }
    }
	//sonu End
	
	 public function getmulti_quation_id($id)
    {
        $sql = "SELECT * FROM digital_quotation WHERE digital_quotation_id='".$id."'";
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
    
    //made by [kinjal] on 5-11-2017
	public function getDielineArtwork($custom_order_id)
	{
		//printr($custom_order_id);
		$sql = "SELECT mco.product_note, mco.product_instruction, mcod.name, mcod.order_product_die_line_id FROM " . DB_PREFIX ." multi_custom_order as mco, multi_custom_order_product_die_line as mcod WHERE mco.multi_custom_order_id='".$custom_order_id."' AND mco.custom_order_id=mcod.order_product_id ";
		$data = $this->query($sql);
      //  echo $sql;
		//printr($data);
		$sql2 = "SELECT mco.product_note, mco.product_instruction, mcoi.image_name, mcoi.order_product_image_id FROM " . DB_PREFIX ." multi_custom_order as mco, multi_custom_order_product_image as mcoi WHERE mco.multi_custom_order_id='".$custom_order_id."' AND mco.custom_order_id=mcoi.order_product_id ";
		//$sql2 = "SELECT image_name,order_product_image_id FROM " . DB_PREFIX ." multi_custom_order_product_image WHERE order_product_id='".$custom_order_id."'";
		$data2 = $this->query($sql2);
	//	echo $sql2;
		//die;
		if($data->num_rows){
			return array('multi_order' => $data->rows,
						'artwork' => $data2->rows);
		}else{
			return false;
		}	
	}
	//[kinjal] end
	
	
	public function getUserAddress($user_id,$user_type_id){
	    
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

		$sql="SELECT * FROM international_branch as ib,address as a WHERE ib.international_branch_id='".$set_user_id."' AND ib.is_delete='0' AND a.address_id=ib.address_id";

		$data=$this->query($sql);

		if($data->num_rows)

			return $data->row;

		else

			return false;

		

	    
	}

	public function viewCustomOrder($custom_order_id,$currency,$currency_price){
	    
	  
	   
	    	$getData = " mcoi.address_book_id,mcoi.multi_product_quotation_id,mcoi.date_added,mco.accept_decline_status,custom_order_id,mco.added_by_user_id,mco.added_by_user_type_id, customer_name, shipment_country_id,mco.multi_custom_order_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,mco.status,mco.custom_order_status,discount";
        	$data = $this->getCustomOrder($custom_order_id,$getData,$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
	        $tax_type = $this->CheckCustomOrderTax($data[0]['custom_order_id']);
	         $multi_quation_id = $this->getmulti_quation_id($data[0]['multi_product_quotation_id']);
	         
	        $html='';
	       // printr($_SESSION['LOGIN_USER_TYPE']);
	       // printr($_SESSION['ADMIN_LOGIN_SWISS']);
	   
	        
	       	$html='	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div class="panel-body font_medium" id="print_div" style="font-size: 20px;" >';
	        // $html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;"><b><h4> JOB REPORT</h4></b>'; 
	         $html .='<div  style="font-size: 14px;"><div style="text-align:center;"><b>CUSTOM ORDER NO: '.$data[0]['multi_custom_order_number'].'</b>';
			$html.='</div>
			<div class="div_first" style=" width: 100%;float: left; font-size: 12px;">
							<table class="table" style="font-size: 12px;">
							<tbody>
							<tr><td style="vertical-align: top;width: 50%;"><b>Reference No :</b> '.ucwords($data[0]['reference_no']).'</td>
						        <td style="vertical-align: top;width: 50%;"><b>Customer Name :</b>'.ucwords($data[0]['customer_name']).'</td>
							</tr>
							<tr><td style="vertical-align: top;width: 50%;"><b>Quotation No :</b> '. ucwords($multi_quation_id['multi_quotation_number']).'</td>
						        <td style="vertical-align: top;width: 50%;"></td>
							</tr>
							<tr><td style="vertical-align: top;width: 50%;"><b>Shipment Country :</b> '.$data[0]['country_name'].'</td>
						        <td style="vertical-align: top;width: 50%;"><b>Email :</b>'.ucwords($data[0]['email']).'<br><b>Contact Number:</b>'.$data[0]['contact_number'].'</td>
							</tr>
							<tr><td style="vertical-align: top;width: 50%;"><b>Printing Option : </b>'.$data[0]['printing_option'].'</td>
						        <td style="vertical-align: top;width: 50%;"><b>Shipping Address :</b>'.ucwords($data[0]['address'])." ,".$data[0]['city']." ,".$data[0]['state'].'</td>
							</tr>
							</tbody>
							</table></div></form>';
						
			 foreach($data as $dat)
					   {//printr($dat);
					       //printr($dat['currency'].'=='. $dat['currency_price']);
					       //$dat['currency']= $currency;
					       ///$dat['currency_price']=$currency_rate;
						//printr($dat['currency'].'=='. $dat['currency_price']); 
					  
					   	 $multi_custom_order_id=$dat['multi_custom_order_id'];
					  	 //printr($multi_custom_order_id);
						 $custom_order_id=$dat['custom_order_id'];
						 //echo $custom_order_id;
						 $country_id = $dat['shipment_country_id'];
						 //printr($country_id);
					 	 $result = $this->getCustomOrderQuantity($dat['custom_order_id']);
						//printr($result);
						 if($result!='')
						 	$quantityData[] =$result;
					   } 
					   //printr($multi_custom_order_id);
					  //printr($quantityData);
					   if(!empty($quantityData))
					   {
						foreach($quantityData as $k=>$qty_data)
						{
							//printr($quantityData);
							foreach($qty_data as $tag=>$qty)
							{	
								foreach($qty as $q=>$arr)
								{
									$new_data[$tag][$q][]=$arr[0];
									//printr($tag);
								}
							}	
						}
					foreach($new_data as $k=>$qty_data)
						{
							if($dat['shipment_country_id'] == 42)
							{
								if($k=='air')
									$rush = 'Rush order';
								else
								    $rush = 'Normal order';
							}
							else
								$rush = $k;
								
					
						
						  $html .='<div  style="font-size: 14px;">
						          <div style="text-align:center;">
		            
			            <div class="div_first" style=" width: 100%;float: left; font-size: 12px;">';
						$html.='<table class="table" style="font-size: 12px;border: 1px solid black;" >
										 	<tbody >
											  <tr >
                                                <td style="border: 1px solid black;width:7%;"><b>Product Name</b></td>
                                                <td style="border: 1px solid black;width:8%;"><b>Quantity</b></td>';
                                               if($dat['custom_order_type'] != 1){ 
                                                	$html.='	<td style="border: 1px solid black;width:15%;"><b>Option(Printing Effect )</b></td>';
                                              } 
                                               	$html.='<td style="border: 1px solid black;width:10%;"><b>Dimension (Make Pouch)</b></td>
                                                <td style="border: 1px solid black;width:10%;"><b>Layer:Material<br>:Thickness</b></td>';
                                              if($dat['custom_order_status'] == 0){
													 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
    													{ 				
    													 if($dat['currency']=='INR')
    										                	$html.='<td style="border: 1px solid black;width:5%;"><b>Discount</b></td>';
    													}
													}
                                            $html.=' <td style="border: 1px solid black;width:8%;"><b>Price / pouch</b></td>
                                                <td style="border: 1px solid black;width:10%;"><b>Total</b></td>';
                                            if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){  
													    
                                                	$html.=' <td style="border: 1px solid black;width:5%;"><b> Price / pouch  With Tax</b> </td>
                                                 <td style="border: 1px solid black;width:5%;"><b>Total Price With Tax </b></td>';
                                                 } }
                                                	$html.=' <td style="border: 1px solid black;width:7%;"><b>Cylender Price</b></td>
                                                 <td style="border: 1px solid black;width:5%;"><b>Tool Price</b></td>
                                             </tr>
										  ';
						               
                                           $i=1;
                                                foreach($qty_data as $skey=>$sdata){
                                               
                                                  
                                                     
                                                        foreach($sdata as $soption){        
															//  printr($soption);
                                                           	$html.=' <tr  style="border: 1px solid black;" id="quotation-row-'.$soption['custom_order_price_id'].'">
                                                                        <td style="border: 1px solid black;width:7;">'. $soption['product_name'].'</td>
                                                            <td style="border: 1px solid black;width:8;">'.$skey.'';
																if($soption['cust_quantity'] != '0')
																	$html.='</br></br><b>Order Qty: </b>'.$soption['cust_quantity'].' </td>';
                                                        	$html.=' <td style="border: 1px solid black;width:15;">'. ucwords($soption['text']).' ('.$soption['printing_effect'].')</td>
                                                                <td style="border: 1px solid black;width:13;">'.(int)$soption['width'].'X'.(int)$soption['height'].'X'.
																$soption['gusset']; 
																if($data[0]['product_name']!=10)
																{
																    if($soption['volume']>0) 
																   	$html.='('.$soption['volume'].')';
																    
																}
																else  
																	$html.='(Custom)'.' ('.$soption['make'].')</td>';
                                                               	$html.='<td style="border: 1px solid black;width:5;">';
                                                          
                                                          
                                                             for($gi=0;$gi<count($soption['materialData']);$gi++){
											                   	$html.=' <b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
									                        	}
                                                             	$html.='</td>';
															if($dat['custom_order_status'] == 0){
																	 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																{ 
																    if($dat['currency']=='INR')
																$html.='<td style="border: 1px solid black;width:8;"><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['custom_order_quantity_id'].'" class="form-control" style="width: 100px;" ></td>'; } }
                                                                
                                                                 
                                                              	$html.='<td style="border: 1px solid black;width:10;">';
            												  if($soption['discount'] && $soption['discount'] >0.000) {
                                                                          	$html.='<b>Total : </b>'.
                                                                          	$pretot= $this->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
                                                                          		$html.= $pretot .'<br />
                                                                            <b>Discount ('. $soption['discount'].' %) : </b>';
            																 $predis = $pretot*$soption['discount']/100; 
            																	$html.= $this->numberFormate($predis,"3").'<br />
                                                                            <b>Final Total : </b>
            																'. $dat['currency'].' '.$this->numberFormate(($pretot-$predis),"3").'';
            														 }else 
            														 
            														        	$html.= $dat['currency'].' '.$this->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
                                                               	$html.=' </td>';
                                                               //printr($soption['totalPrice'].' /'. $skey.') /'.$dat['currency_price']);
                                                               
                                                               
                                                               	$html.='  <td style="border: 1px solid black;width:5;">';
																
                                                                        if($soption['discount'] && $soption['discount'] >0.000) {
                                                                                	$html.=' <b>Total : </b>';
                                                                                	$tot= $this->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");
                                                                                		$html.= $tot.'<br />
                                                                                <b>Discount ('. $soption['discount'].' %) : </b>';
                                                                                 $dis = $tot*$soption['discount']/100; 
                                                                                				$html.= $this->numberFormate($dis,"3").'<br />;
                                                                                				<b>Final Total : </b>
                                                                                			'. $dat['currency'].' '.($tot-$dis);
																				if($soption['cust_total_price'] != '0.000') 
																				{
																						$html.='<br><br><b>Order Total : </b>'.$soption['cust_total_price'].'<br />
																						<b>Discount ('. $soption['discount'].'%) : </b>';
																					
																				 $dis_cus = $soption['cust_total_price']*$soption['discount']/100;
																							 	$html.= $this->numberFormate($dis_cus,"3").'<br />';
																								
																				$html.='<b>Order Final Total : </b>'.($soption['cust_total_price']-$dis_cus).'<br />';
                                                                                	//echo $dat['currency'].' '.($tot-$dis);
																				}
                                                                               
                                                                  } 
                                                                          else
																		  {
                                                                                 	$html.= $dat['currency'].' '.$this->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");
																				 	$html.='<br /><br /><b>Order Final Total : </b>'.$soption['cust_total_price'];
																		 }
																		
																	
                                                               	$html.='</td>';
                                                                 if($k=='pickup') {
																	  if($data[0]['shipment_country_id']==111) { 
                                                  			    	$html.='<td style="border: 1px solid black;width:5;">
																	        '.$dat['currency'].' '.$this->numberFormate((($soption['totalPriceWithTax'] / $skey) / $dat['currency_price'] ),"3");
                                                                 	$html.='</td>
                                                                          <td style="border: 1px solid black;width:5;">'
																	    . $dat['currency'].' '.$obj_custom_order->numberFormate(($soption['totalPriceWithTax'] / $dat['currency_price'] ),"3");
                                                                	$html.=' </td>';
                                                 }  }  
                                                 	$html.='<td style="border: 1px solid black;width:7;">';
                                                 	if($soption['cylinder_price']>0) {	$html.= (int)$soption['cylinder_price'];}else '';
												 	$html.=' </td>
                                                                 <td style="border: 1px solid black;width:5;">';
                                                                 if($soption['tool_price']>0) {	$html.= (int)$soption['tool_price'];}else '';
                                                                 	$html.=' </td>';
                                                                 
                                                              
                                                               
                                                              	$html.='   </tr>';
                                                            
                                                        }
                                                      
                                                    	$html.=' </tr>';
                                                   $i++;
                                                }
                                           
                                          	$html.='</tbody>
										</table>
									 </div></div>
							 </form>';
						
						
						}
						
					   }
										
	
			return $html;
	    
	}
    public function getActiveProductCode()
	{

		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE is_delete=0 AND status=1 AND product_code LIKE '%CUST%'";

		$data = $this->query($sql);

		if($data->num_rows){

			return $data->rows;

		}else{

			return false;

		}

	}
	public function clone_data($multi_custom_order_id)
	{
	    $sql = "SELECT * FROM `" . DB_PREFIX . "multi_custom_order_id` WHERE multi_custom_order_id = '".$multi_custom_order_id."'";
	    $data=$this->query($sql);
	    if($data->num_rows)
	    {
	        $sql1 =  "INSERT INTO ".DB_PREFIX."multi_custom_order_id SET  multi_product_quotation_id='".$data->row['multi_product_quotation_id']."',company_name='".$this->escape($data->row['company_name'])."', website='".$data->row['website']."', customer_name='".$this->escape($data->row['customer_name'])."',address_book_id='".$data->row['address_book_id']."', email = '".$data->row['email']."', contact_number='".$data->row['contact_number']."',reference_no ='".$data->row['reference_no']."', vat_number = '".$data->row['vat_number']."', shipping_address_id = '".$data->row['shipping_address_id']."',  billing_address_id = '".$data->row['billing_address_id']."', order_currency = '".$data->row['order_currency']."',currency_rate = '".$data->row['currency_rate']."',order_note ='".$data->row['order_note']."', order_instruction = '".$data->row['order_instruction']."',custom_order_status=1,status = '0', date_added = NOW(),date_modify = NOW(), added_by_user_id = '".$data->row['added_by_user_id']."', added_by_user_type_id = '".$data->row['added_by_user_type_id']."', admin_user_id = '".$data->row['admin_user_id']."'";		
            $this->query($sql1);
            $multi_custom_order_id_new = $this->getLastId();
            
            $custom_order_number = $this->generateCustomOderNumber($multi_custom_order_id_new);
            $sql2 =  "UPDATE  ".DB_PREFIX."multi_custom_order_id SET multi_custom_order_number = '".$custom_order_number."' WHERE multi_custom_order_id = '".$multi_custom_order_id_new."'";
            $this->query($sql2);
            
             $sql3 = "SELECT * FROM `" . DB_PREFIX . "multi_custom_order` WHERE multi_custom_order_id = '".$multi_custom_order_id."' AND is_delete=0";
	         $data1=$this->query($sql3);
	         
	         if($data1->num_rows)
	         {
	             foreach($data1->rows as $row)
	             {
    	                $sql4 =  "INSERT INTO ".DB_PREFIX."multi_custom_order SET product_id = '".$row['product_id']."', product_name = '".$row['product_name']."', printing_option = '".$row['printing_option']."', printing_effect_id = '".$row['printing_effect_id']."', printing_effect = '".$row['printing_effect']."', height = '".$row['height']."', width = '".$row['width']."', gusset = '".$row['gusset']."', volume = '".$row['volume']."', layer = '".$row['layer']."', ink_price = '".$row['ink_price']."', ink_solvent_price = '".$row['ink_solvent_price']."', printing_effect_price = '".$row['printing_effect_price']."', adhesive_price = '".$row['adhesive_price']."', cpp_adhesive = '".$row['cpp_adhesive']."', adhesive_solvent_price = '".$row['adhesive_solvent_price']."', native_price = '".$row['native_price']."',packing_price = '".$row['packing_price']."', valve_price = '".$row['valve_price']."', gress_percentage = '".$row['gress_percentage']."',gress_air = '".$row['gress_air']."',gress_sea = '".$row['gress_sea']."', cylinder_price = '".$row['cylinder_price']."', tool_price = '".$row['tool_price']."', use_device = '".getDevice()."', status = '1',custom_order_status='1', date_added = NOW(), added_by_country_id = '".$row['added_by_country_id']."', currency = '".$row['currency']."', currency_price = '".$row['currency_price']."', customer_gress_percentage = '".$row['customer_gress_percentage']."', product_note = '".$row['product_note']."', product_instruction = '".$row['product_instruction']."', shipment_country_id = '".$row['shipment_country_id']."', added_by_user_id = '".$row['added_by_user_id']."', added_by_user_type_id = '".$row['added_by_user_type_id']."', multi_custom_order_id = '".$multi_custom_order_id_new."'";
                         $this->query($sql4);
                         $CustomOrderId = $this->getLastId();
                         
                         $sql5 = "SELECT * FROM `" . DB_PREFIX . "multi_custom_order_base_price` WHERE custom_order_id = '".$row['custom_order_id']."' AND is_delete=0";
    	                 $data2=$this->query($sql5);
    	                 $this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_base_price SET custom_order_id = '".(int)$CustomOrderId."', ink_base_price = '".$data2->row['ink_base_price']."', ink_solvent_base_price = '".$data2->row['ink_solvent_base_price']."', printing_effect_base_price = '".$data2->row['printing_effect_base_price']."', adhesive_base_price = '".$data2->row['adhesive_base_price']."', cpp_adhesive_base_price = '".$data2->row['cpp_adhesive_base_price']."', adhesive_solvent_base_price = '".$data2->row['adhesive_solvent_base_price']."', packing_price = '".$data2->row['packing_price']."', spout_packing_price = '".$data2->row['spout_packing_price']."', spout_courier_price = '".$data2->row['spout_courier_price']."', transport_width_base_price = '".$data2->row['transport_width_base_price']."', transport_height_base_price = '".$data2->row['transport_height_base_price']."', cylinder_base_price = '".$data2->row['cylinder_base_price']."', cylinder_vendor_base_price = '".$data2->row['cylinder_vendor_base_price']."', cylinder_currency_base_price = '".$data2->row['cylinder_currency_base_price']."', fuel_surcharge = '".$data2->row['fuel_surcharge']."', service_tax = '".$data2->row['service_tax']."', handling_charge = '".$data2->row['handling_charge']."', date_added = NOW()");				

                        $sql6 = "SELECT * FROM `" . DB_PREFIX . "multi_custom_order_layer` WHERE custom_order_id = '".$row['custom_order_id']."'";
    	                $data3=$this->query($sql6);
    	                foreach($data3->rows as $row)
    	                {
    	                    $this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_layer SET custom_order_id = '".(int)$CustomOrderId."', layer='".$row['layer']."',material_id='".$row['material_id']."',material_gsm='".$row['material_gsm']."',material_thickness='".$row['material_thickness']."',material_price='".$row['material_price']."',material_name='".$row['material_name']."',layer_wise_gsmthickness='".$row['layer_wise_gsmthickness']."',layer_wise_price='".$row['layer_wise_price']."',date_added=NOW()");
    	                }
    	                
    	                $sql6 = "SELECT * FROM `" . DB_PREFIX . "multi_custom_order_quantity` WHERE custom_order_id = '".$row['custom_order_id']."'";
    	                $data4=$this->query($sql6);
    	                
    	                $this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_quantity SET custom_order_id = '".$CustomOrderId."', product_code_id = '".$data4->row['product_code_id']."', quantity = '".$data4->row['quantity']."',cust_quantity= '".$data4->row['cust_quantity']."', wastage_adding = '".$data4->row['wastage_adding']."', wastage_base_price = '".$data4->row['wastage_base_price']."', wastage = '".$data4->row['wastage']."', native_price_per_bag = '".$data4->row['native_price_per_bag']."', profit = '".$data4->row['profit']."', total_weight_without_zipper = '".$data4->row['total_weight_without_zipper']."', total_weight_with_zipper = '".$data4->row['total_weight_with_zipper']."', date_added = NOW(),discount='".$data4->row['discount']."'");
                        $customOrderQuantityId = $this->getLastId();
                        
                        $sql7 = "SELECT * FROM `" . DB_PREFIX . "multi_custom_order_price` WHERE custom_order_id = '".$row['custom_order_id']."' AND custom_order_quantity_id = '".$data4->row['custom_order_quantity_id']."'";
    	                $data5=$this->query($sql7);
                        
                        $this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_price SET custom_order_id = '".$CustomOrderId."', custom_order_quantity_id = '".$customOrderQuantityId."', transport_type = '".$data5->row['transport_type']."', zipper_txt = '".$data5->row['zipper_txt']."', valve_txt = '".$data5->row['valve_txt']."', spout_txt = '".$data5->row['spout_txt']."', make_pouch = '".$data5->row['make_pouch']."', spout_base_price = '".$data5->row['spout_base_price']."', accessorie_txt = '".$data5->row['accessorie_txt']."',  accessorie_txt_corner = '".$data5->row['accessorie_txt_corner']."', accessorie_base_price_corner = '".$data5->row['accessorie_base_price_corner']."',date_added = NOW(),accessorie_base_price='".$data5->row['accessorie_base_price']."',transport_base_price='".$data5->row['transport_base_price']."',transport_price='".$data5->row['transport_price']."',courier_base_price_withzipper='".$data5->row['courier_base_price_withzipper']."',courier_base_price_nozipper='".$data5->row['courier_base_price_nozipper']."',courier_charge='".$data5->row['courier_charge']."',zipper_price='".$data5->row['zipper_price']."',valve_price='".$data5->row['valve_price']."',total_price='".$data5->row['total_price']."',cust_total_price='".$data5->row[$cust_total]."',total_price_with_excies='".$data5->row['total_price_with_excies']."',total_price_with_tax='".$price_arr['total_price_with_tax']."',tax_type='".$data5->row['tax_type']."',tax_name='".$data5->row['tax_name']."',tax_percentage='".$data5->row['tax_percentage']."',excies='".$data5->row['excies']."',gress_price='".$data5->row['gress_price']."',customer_gress_price='".$data5->row['customer_gress_price']."',total_charge='".$data5->row['total_charge']."'");
	             }
	             
	         }
            
	    }
	    
	    
	}
	public function getSize($size_id){
		$data = $this->query("SELECT gusset,height,width,volume FROM " . DB_PREFIX ." size_master WHERE size_master_id = '".(int)$size_id."' AND status=0 LIMIT 1");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
 public function getColor($color_id,$make_id=0)
	{
		$make='';
		if($make_id!=0)
		    $make = " AND make_id LIKE '%".$make_id."%'";
		$data = $this->query("SELECT pouch_color_id, color FROM " . DB_PREFIX . "pouch_color WHERE pouch_color_id='".$color_id."' AND status = '1' AND is_delete = '0' ".$make." ORDER BY color ASC LIMIT 1");		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getSpoutname($product_spout_id) 
	{
        $sql = "select * from " . DB_PREFIX ."product_spout where product_spout_id = '".$product_spout_id."'";
        $data = $this->query($sql);
        if($data->num_rows){
			return $data->row;
        }
        else {
            return false;
        }
    }
     public function getZipperName($product_zipper_id) {
		$sql = "select * from " . DB_PREFIX ."product_zipper where product_zipper_id = '".$product_zipper_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	public function getAccessorieName($product_accessorie_id) {
		$sql = "select * from " . DB_PREFIX ."product_accessorie where product_accessorie_id = '".$product_accessorie_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
		public function ProductMake($make_id){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0'  AND make_id =".$make_id;
	    $data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
		public function uploadDieline($post,$file,$n=0)
	{
		

		//printr($file);

		foreach($file['name'] as $key1=>$fname1)
		{
			$product=array();	
			foreach ($fname1 as $key => $fname) {
				# code...

				//printr($key1);die;
			
		    $ext = pathinfo($fname, PATHINFO_EXTENSION);
    		
        		$product[] = array(
    				'die_name' => $fname,
    				'die_ext' => $ext
    			);
    		    	
			$data = array(
				'name' => $fname,
				'size' => $file['size'][$key1][$key]
			);
			$file_name = $fname;
    		$filetemp = $file['tmp_name'][$key1][$key];
    		//printr($filetemp);

    	//	die;
    		$validateImageExt = validateUploadImage($data);	
    		if($ext == 'pdf'){
    		
    			    $upload_path = DIR_UPLOAD.'admin/digital_order_dieline_pdf/';
    		
    			    
    			$upload_file_path = $upload_path.$file_name;				
    			if(file_exists($upload_file_path)) 
    			{
    				$file_name = rand().'_'.$file_name;
    				move_uploaded_file($filetemp,$upload_path.$file_name);
    			}else{
    				move_uploaded_file($filetemp,$upload_file_path);
    			}
    		}
            else if($validateImageExt)
            {
    			
    			    $upload_path = DIR_UPLOAD.'admin/digital_order_dieline_image/';				
    		
    			$upload_file_path = $upload_path.$file_name;
    			
    			
    			require_once(DIR_SYSTEM . 'library/resize-class.php');			
    			if(file_exists($upload_file_path)) 
    			{
    				$file_name = rand().'_'.$file_name;
    				if(file_exists($upload_file_path)) 
    				{
    					move_uploaded_file($filetemp,$upload_path.$file_name);
    				}else{
    					move_uploaded_file($filetemp,$upload_file_path);
    				}
    				$widthArray = array(500,100); //You can change dimension here.
    				foreach($widthArray as $newwidth){
    					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
    				}
    			}else{
    				$widthArray = array(500,100); //You can change dimension here.
    				foreach($widthArray as $newwidth){
    					compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
    				}
    				if(file_exists($upload_file_path)) 
    				{
    					move_uploaded_file($filetemp,$upload_path.$file_name);
    				}else{
    					move_uploaded_file($filetemp,$upload_file_path);
    				}				
    			}
    		}
    	}
    	 $this->insertDieLine($product,$key1);
    }
    	//die;
	       
	  
	
	}
public function dielineImageDetails($order_product_id){
	$sql="SELECT * FROM `multi_custom_order_product_die_line` WHERE `order_product_id` = '".$order_product_id."' ORDER BY `order_product_die_line_id` DESC";
	$data=$this->query($sql);
	if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	

	}
	public function getTaxationCanada()
	{

		$sql = "SELECT * FROM taxation_canada ORDER BY state ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else {
			return false;
		}

	}
	public function getCustomAddress($address_id)
	{

		$sql = "SELECT * FROM address WHERE address_id='".$address_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	 //add by sonu  29-05-2019  for  stock template print 
	public function getaddProductDetailsForDigitalPrint($product_code_id,$country_id,$color,$user_id,$user_type_id)
	{
	 
		if($user_type_id == 2)
		{
			$sql1 = "SELECT * FROM employee WHERE employee_id = '".$user_id."'";
			$data = $this->query($sql1);
			$user_id=$data->row['user_id'];
		}
		else
		{ 
			$user_id=$user_id; 
		}
		$countryid='"'.$country_id.'"'; 
	//	printr($user_id.'=='.$user_type_id);

		$sql1="SELECT pz.zipper_name,pc.valve,pm.volume,pc.color,pc.product FROM product_code as pc, pouch_volume as pm,product_zipper as pz WHERE pm.volume = (Select CONCAT(p.volume,' ',m.measurement) as volume from product_code as p, template_measurement as m WHERE p.measurement=m.product_id AND p.product_code_id='".$product_code_id."') AND pc.product_code_id='".$product_code_id."' AND pc.zipper=pz.product_zipper_id";
		$data1 = $this->query($sql1);
		
		if($data1->num_rows)
		{
		    $arr=explode("==",$color);
		    
		    $color_id='"'.$arr[0].'"';
			$sql = "SELECT pts.*,p.product_name,p.product_id,pt.user,pt.country,pt.digital_template_id FROM " . DB_PREFIX . "digital_template_size pts,product p,digital_template as pt  WHERE  pt.product_name='".$data1->row['product']."' AND  pt.country LIKE '%".$countryid."%'  AND pt.user = '".$user_id."' AND pts.template_id=pt.digital_template_id AND pt.product_name=p.product_id AND pts.is_delete = '0' AND pt.status='0' AND REPLACE(pts.volume, ' ', '') = REPLACE('".$data1->row['volume']."', ' ', '') AND pt.price_status = 1 AND pts.color LIKE '%".$color_id."%' ";	
        	$data = $this->query($sql);
        
   
		    $result = $data->row;
			return $result;
		}
		else
			return false;
	}
	public function getColorPlatePrice($user_id,$user_type_id,$n=0)
	{

    	if($user_type_id=='2')
		{ 
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'";
			$dataadmin = $this->query($sqladmin);
		
			$admin_user_id =  $dataadmin->row['user_id'];
		    
			$sql="SELECT color_plate_price,color_plate_price_swisspac FROM international_branch WHERE international_branch_id='".$admin_user_id ."'";
			
		}
		elseif($user_type_id=='4')
		{
			$sql="SELECT color_plate_price,color_plate_price_swisspac FROM international_branch WHERE international_branch_id='".$user_id."'";
		}
	
		//echo $user_id;die;
		$data=$this->query($sql); 
		if($data->num_rows)
		{
			if($n==0)
			    return $data->row['color_plate_price'];
			 else
			    return $data->row['color_plate_price_swisspac']; 
		}
		else
		{
			return false;
		}
	}
	public function getaddProductDetails($product_code_id,$country_id,$transport,$color,$user_id,$user_type_id)
	{
		/*if($_SESSION['ADMIN_LOGIN_SWISS'] == 44)
		    printr($color.''.$transport);*/
		if($user_type_id == 2)
		{
			$sql1 = "SELECT * FROM employee WHERE employee_id = '".$user_id."'";
			$data = $this->query($sql1);
			$user_id=$data->row['user_id'];
		}
		else
		{
			$user_id=$user_id;
		}
		$countryid='"'.$country_id.'"';
 
		
	
		$sql1="SELECT pz.zipper_name,spout.spout_name,pc.valve,pm.volume,pc.color,pc.product,pa.product_accessorie_name FROM product_code as pc, pouch_volume as pm,product_zipper as pz,product_spout as spout,product_accessorie as pa WHERE pm.volume = (Select CONCAT(p.volume,' ',m.measurement) as volume from product_code as p, template_measurement as m WHERE p.spout=spout.product_spout_id AND p.measurement=m.product_id AND p.product_code_id='".$product_code_id."') AND pc.product_code_id='".$product_code_id."' AND pc.zipper=pz.product_zipper_id AND pc.accessorie=pa.product_accessorie_id";
		$data1 = $this->query($sql1);
       
		if($data1->num_rows)
		{
		    $color_id='"'.$data1->row['color'].'"';
			$sql = "SELECT pts.*,p.product_name,p.product_id,pt.user,pt.country,pt.product_template_id FROM " . DB_PREFIX . "product_template_size pts,product p,product_template as pt  WHERE  pt.product_name='".$data1->row['product']."' AND  pt.country LIKE '%".$countryid."%' AND  pt.transportation_type = '".$transport."' AND pt.user = '".$user_id."' AND pts.template_id=pt.product_template_id AND pt.product_name=p.product_id AND pts.valve='".$data1->row['valve']."' AND pts.spout='".$data1->row['spout_name']."' AND pts.zipper='".$data1->row['zipper_name']."' AND pts.accessorie='".$data1->row['product_accessorie_name']."' AND pts.is_delete = '0' AND pt.status='0' AND REPLACE(pts.volume, ' ', '') = REPLACE('".$data1->row['volume']."', ' ', '') AND pts.color LIKE '%".$color_id."%' ";	
			$data = $this->query($sql);
		    //echo $sql;
			$result = $data->row;
			return $result;
		}
		else
			return false;
	}
	
	public function getMenuPermission($menu_id,$user_id,$user_type_id,$n=0)
	{
		$cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$cond." AND user_type_id = '".$user_type_id."' AND user_id ='".$user_id."'";
		//printr($sql);
		
		$data = $this->query($sql);
		return $data->rows;
	}
    public function getproductCode($product_code_id)
	{
		$sql = "SELECT product_code FROM " . DB_PREFIX ."product_code WHERE product_code_id = '".$product_code_id."'";
	    $data = $this->query($sql);
		return $data->row;
	}
	public function sendCustomOrderEmailTest($custom_order_id,$toEmail = '',$setCustomOrderCurrencyId=''){
	    	
	    
	    $custom_detail = $this->getDielineArtwork($custom_order_id);
	//	printr($custom_detail);printr($custom_order_id);printr($toEmail);printr($setCustomOrderCurrencyId);die;
		//$url_dieline
		//$base_url = base_url();
		//[sonu] edited on 11-5-2017
		
		 $attachments =array();
		if(!empty($custom_detail['multi_order']))
		{ //echo 'sonu';
    		foreach($custom_detail['multi_order'] as $image)
    		{
    					$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    					
    					if($ext=='pdf')
    					{
    						$url_dieline[] = DIR_UPLOAD.'admin/digital_order_dieline_pdf/'.$image['name'].'';
    							
    					}
    					else
    					{
    						$url_dieline[] = DIR_UPLOAD.'admin/digital_order_dieline_image/100_'.$image['name'].'';
    					}
    				
    		}
		}
				
	  //  printr($url_dieline);
	
		    	if(isset($url_dieline))
					$attachments = $url_dieline;		    
			
		
	//    printr($attachments);
		$getData = ' custom_order_id,mco.layer, mco.added_by_user_id, mco.added_by_user_type_id, customer_name, customer_gress_percentage,  shipment_country_id, multi_custom_order_number,mco.multi_custom_order_id, custom_order_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,custom_order_type,layer,added_by_country_id, currency, currency_id, currency_price, mco.date_added, cylinder_price,tool_price, mcoi.email as customer_email,mco.status,discount,customer_gress_percentage,gress_percentage,gress_air,gress_sea,quantity_type,mcoi.shipping_address_id,mcoi.billing_address_id';
		$data = $this->getCustomOrder($custom_order_id,$getData);
		//if($_SESSION['ADMIN_LOGIN_SWISS']=='41' && $_SESSION['LOGIN_USER_TYPE']=='4')
		//printr($data);die;
		
		$shipping_address = $this->getCustomAddress($data[0]['shipping_address_id']);
		$billing_address = $this->getCustomAddress($data[0]['billing_address_id']);
		foreach($data as $dat)
	   {
		   //printr($dat);
		 $qdata= $this->getCustomOrderQuantity($dat['custom_order_id']);
		 $UserDetail = $this->getUserData($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		 //printr($UserDetail);
		 if($qdata!='')
		  $quantityData[] =$qdata;
	   }
	    $str=$dat['product_name'];       


        $menu_id = $this->getMenuPermission(151,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$user_admin_id=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);

		//[kinjal] for pratik sir to go gress price mail on 28-4-2017
		$menu_id_pratik_sir = $this->getMenuPermission(151,'19','2',1);
		//END [kinjal]
		

		$menu_admin_permission='';
		if($_SESSION['LOGIN_USER_TYPE']!='4')
		{
			$menu_admin_permission=$this->getMenuPermission(151,$user_admin_id['international_branch_id'],4);
		}


        /*printr($menu_id);
        printr($user_admin_id);
        printr($menu_admin_permission);
        die;*/
		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$str),0,3));		
		$sub =$dat['multi_custom_order_number'] .' - '.ucwords($dat['customer_name']).' - Digital  printed '.$first;
		//printr($quantityData);





		$gussetvalue='';$s='';$sub2='   ';$m_k=array();
		foreach($quantityData as $k=>$qty_data)
		{
			foreach($qty_data as $tag=>$qty)
			{
				//printr($qty);
				//die;
				foreach($qty as $q=>$arr)
				{
					if($arr[0]['gusset'] == '')
					{
						$gussetval = 'No Gusset';
					}
					else
					{
						if($dat['product_id'] == '7')
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm gusset }';
						}
						elseif($arr[0]['gusset'] == '0')
						{
							 $gussetval = ' ';	
						}
						else
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.
							$arr[0]['gusset'].'mm gusset}';
						}
					}
					if($dat['product_id'] != '10')
					{
						if($arr[0]['volume']!='')
						{ 
							if($s!=$qty)
							{
								$sub2.=$arr[0]['volume'].' , ';
								$s=$qty;
							}
							$gussetvalue.=$arr[0]['volume'].' , ';
						}
					}
					if($gussetvalue=='')
						$gussetvalue=' W : '.(int)$arr[0]['width'].'mm  x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval;
					if($arr[0]['volume']=='')
						$arr[0]['volume']='Custom';
					
					if($dat['product_id'] != '10')
						$key='W : '.(int)$arr[0]['width'].'mm x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval.' ['.$arr[0]['volume'].']';
					else
						$key='W : '.(int)$arr[0]['width'].'mm x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval;
						$m_k[$arr[0]['custom_order_quantity_id']]=array();
				
					$new_data[$arr[0]['text']][$key.' ('.$arr[0]['layer'].') '][$q.'('.$arr[0]['custom_order_quantity_id'].')'][$tag][]=$arr[0];
					if($dat['product_id'] != '10')
					{
						$sub1= ' '.$arr[0]['zipper_txt'].' '.$arr[0]['valve_txt'].''.$sub2; 
					}
					else
						$sub1= $sub2; 
				}
			}	
			$n[$tag][]=array('width'=>$arr[0]['width'],'height'=>$arr[0]['height'],'gusset'=>$arr[0]['gusset'],'volume'=>$arr[0]['volume']);
			$t=$tag;
		}
		$addedByInfo = $this->getUser($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
	//printr($addedByInfo);die;
		//printr($m_k);die;
		$sub1= substr($sub1,0,-3);
		$sub=$sub.$sub1;
		//printr($sub);die;
		$html='';$tax_str='';
		if($addedByInfo)
		{
			$pqcquery='';
			$selCurrency = $this->getCustomOrderCurrecy($setCustomOrderCurrencyId,1);
		//	printr($selCurrency);
			if($selCurrency)
				$pqcquery = " custom_order_currency_id = '".$selCurrency['custom_order_currency_id']."', ";
			$currency_rate[]=array('currency_rate'=>1,'user'=>0);
			if($UserDetail['email'] != '' || $toEmail != '')
				$currency_rate[]=array('currency_rate'=>($selCurrency['currency_rate']!='')?$selCurrency['currency_rate']:1,'user'=>1);
			else
				$currency_rate[]=array('currency_rate'=>1,'user'=>2);
			//printr($currency_rate);die;
			$html .='<table border="0px">';
			foreach($currency_rate as $cr)
			{
				$gettermsandconditions = $this->gettermsandconditions($dat['added_by_user_id'],$dat['added_by_user_type_id']);
				
				$shippingCountry = $this->getCountry($dat['shipment_country_id']);
				
				$html .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				//printr($new_data);
				foreach($new_data as $key=>$value)
				{
					//printr($qty_data);die;
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
						//printr($size);
					foreach($qty_data as $qty=>$transport)
					{//printr($transport);//die;
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{ //if($_SESSION['ADMIN_LOGIN_SWISS']=='41' && $_SESSION['LOGIN_USER_TYPE']=='4')
						  //  printr($k);
								if($k == "By Air") 
								{
									$color = "red";	
								}elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
 
								//printr($color);
													/*	if($selCurrency)
														{
															$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code']);	
															//printr($cr['currency_rate']);
															$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
															$tool_price=0;
															if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
																$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
															if($cylinder_base_price)
															{
																if($cylinder_currency_price < $cylinder_base_price)
																	$cylinder_price = $cylinder_base_price;	
																else
																	$cylinder_price = $cylinder_currency_price;	
															}
															else
																$cylinder_price = $cylinder_currency_price;	
														 }
														 else
														 {
															$selCurrency['currency_code'] = $dat['currency'];
															$cylinder_price = $records[0]['cylinder_price'];
															$tool_price = $records[0]['tool_price'];
														 }*/
							if($dat['custom_order_type']==1)
								$type=1;	
							else
								$type=0;
								$qty_type = ''; $bag_types='bags'; $bag_type='bag';
							if($dat['product_id'] == '6'){
							    $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
						
						
							//if($_SESSION['ADMIN_LOGIN_SWISS']=='41' && $_SESSION['LOGIN_USER_TYPE']=='4')
							    //printr($bag_type.'----'.$qty_type.'----'.$bag_types.'-----'.$records[0]['quantity_type']);
							
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
					
					
					
						//	echo $cr['user'];
						/*	if($cr['user']==0 || $addedByInfo['country_id']==155)
							{
								if($records[0]['gress_percentage'] != '0.000' OR $records[0]['gress_air'] !='0.000' OR $records[0]['gress_sea'] !='0.000')
								{
									if($dat['shipment_country_id']==91)
										$txt='[Your Purchase Price]';
									else
										$txt='';
									$gress = '<tr><td colspan="2" >&nbsp;</td></tr><tr valign="top"><td width="60" colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Percentage Prices:'.$txt.'</b></td></tr>';								
									$newPircegress = ((($records[0]['totalPrice'] - $records[0]['customerGressPrice'] - $records[0]['gress_price']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
								//	echo '((('.$records[0]['totalPrice'].' - '.$records[0]['customerGressPrice'] .'-'. $records[0]['gress_price'].') / '.(float)$dat['currency_price'].') / '.(float)$cr['currency_rate'].' )';
									$newPircegress = $newPircegress/$qty;
									$taxvaluegress='';
									if($records[0]['tax_name']=='Normal')
											$tax_name_data='No Form';
										else
											$tax_name_data=$records[0]['tax_name'];
									//printr($tax_name_data);
									//die;
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										$newPircegress =$this->numberFormate(($newPircegress-($newPircegress*$records[0]['discount']/100)),"3");
										$totaldisgress = $newPircegress + ($newPircegress*$records[0]['excies']/100);
										$finaldisgress = $totaldisgress + ($totaldisgress *$records[0]['tax_percentage']/100);
										
										$taxvaluedisgress = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' per 1 '.$bag_type.' including of all taxes.';	
									}						
									if($dat['shipment_country_id'] == '111')
									{
										$totalgress = $newPircegress + ($newPircegress*$records[0]['excies']/100);
										$finalgress = $totalgress + ($totalgress *$records[0]['tax_percentage']/100);
										$taxvaluegress = '';
										$taxvaluegress = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' per 1 '.$bag_type.' including of all taxes.';
									}
									
									if($k=='By Air')
									{
										$gp=$records[0]['gress_air'];
										$txt_tarnsport='Door delivery by  Air in '.$shippingCountry['country_name'];
									}
									//printr($txt_tarnsport);
									if($k=='sea')
									{
										$gp=$records[0]['gress_sea'];
										$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port';
									}
									if($k=='pickup')
									{
										$gp=$records[0]['gress_percentage'];
										$tax_str='Transportation cost NOT included.';
										$txt_tarnsport='By Pickup ';
									}
									if($dat['product_id'] != '10')
										$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
									else
										$bag='';
									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate($newPircegress ,"3").' per 1 '.$bag_type.' '.$taxvaluegress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';	
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										if($dat['product_id'] != '10')
											$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
										else
											$bag='';
										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b></td><td>'.$dat['currency'].' '.$newPircegress.' per 1 '.$bag_type.' '.$taxvaluedisgress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';
									}
								}	
							}*/
						//	$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							//echo '((('.$records[0]['totalPrice'].' +'. $records[0]['customerGressPrice'].') / '.(float)$dat['currency_price'].') / '.(float)$cr['currency_rate'] .')';
							
						
							$newPirce =$records[0]['pouch_price'];//per pouch price
							$gress_pouch_price =$records[0]['gress_pouch_price'];//per gress pouch price
							$taxvalue='';
						//	printr($records[0]);
						
							if($records[0]['tax_name']=='Normal')
								$tax_name_data='No Form';
							else
								$tax_name_data=$records[0]['tax_name'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
								$totaldis = $newPirce + ($newPirce*$records[0]['excies']/100);
								$finaldis = $totaldis + ($totaldis *$records[0]['tax_percentage']/100);
								$taxvaluedis = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 '.$bag_type.' including of all taxes.';											
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$total = $newPirce + ($newPirce*$records[0]['excies']/100);
								$final = $total + ($total *$records[0]['tax_percentage']/100);
								$taxvalue = '';
								$taxvalue = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($final,'3').' per 1 '.$bag_type.' including of all taxes.';
							}
							if($dat['product_id'] != '10')
									$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
								else
									$bag='';
							if($k=='By Air')
							{
								$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'];
							}
							if($k=='sea')
							{
								$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port';
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
							 
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$dat['currency'].' '.$this->numberFormate($newPirce ,"3").' per 1 '.$bag_type.' '.$taxvalue.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$qty.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';	
						
    						if(!empty($menu_id) || !empty($menu_id_pratik_sir))
    						{
    							if($dat['product_id'] != '10')
    								$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
    							else
    								$bag='';
    							$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Gress Price : </b>'.$dat['currency'].' '.$gress_pouch_price.' per 1 '.$bag_type.' '.$taxvaluedis.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
    						}	
							 
						}
						
					$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Make up of pouch  : </b> Custom  Digital Printed '.$dat['product_name'].' ';
					if($dat['product_id'] != '10')
						$m.=str_replace('<br>',' ',$records[0]['email_text']);
					$m.='</td></tr>';
					}
					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Size : </b>'.$size.'</td></tr> ';
				
				
					$html .=$m;
					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Printing Ink To Use : </b>'.$records[0]['printing_effect'].'</td></tr>';
					

					   $total_color = explode('==',$records[0]['total_color']);   


					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Quoted Printing in : </b>'.$total_color[1].' Colors </td></tr>';
				

					if($addedByInfo['country_id']==155)
						$txt='[Your Client Price]';
					else
						$txt='';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html .=$pmq;				
					$html .=$pmq_gress;

					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					
				;
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html .= '</table>';
				$html .= '<div><b> Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				if(!empty($shipping_address)){
				$html .= '<div><b> Shipping Address ( Delivery Address ) : </b><br>&nbsp;&nbsp;&nbsp;&nbsp; '.nl2br($shipping_address['address']).'<br>&nbsp;&nbsp;&nbsp;&nbsp; '.$shipping_address['city'].','.$shipping_address['state'].'<br>';
				if($shipping_address['postcode']!=''){ 
					$html .= 'Postcode'.$shipping_address['postcode'];
				}
				if($data[0]['contact_number']!=''){ 
					$html .= '<br>&nbsp;&nbsp;&nbsp;&nbsp; Contact no'.$data[0]['contact_number'];
					}
				$html .= '</div>';
			}
				$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				if(!empty($billing_address)){
				$html .= '<div><b>Billing Address ( Buyer Address ) : </b><br> '.nl2br($billing_address['address']).'<br>&nbsp;&nbsp;&nbsp;&nbsp; '.$billing_address['city'].','.$billing_address['state'].'<br> ';
				if($billing_address['postcode']!=''){ 
					$html .= 'Postcode'.$billing_address['postcode'];
					}
				$html .= '</div>';
			}
				
				
			    //printr($cr);//printr($toEmail);//die;
		
				if($cr['user']==1 || $cr['user']==2)
				{
					
					if($cr['user']==1)
						$email_temp[]=array('html'=>$html,'email'=>($UserDetail['email'])?$UserDetail['email']:ADMIN_EMAIL);
					if($cr['user']==2)
						$email_temp[]=array('html'=>$html,'email'=>$addedByInfo['email']);
				}
				if($toEmail!='' && $cr['user']!=0)
					$email_temp[]=array('html'=>$html,'email'=>$toEmail);
				if($cr['user']==0)
					$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);


				
				
				if($dat['added_by_user_type_id']=='2')
				{
				    $datauser = $this->getUser($dat['added_by_user_id'],$dat['added_by_user_type_id']);
					$datauser_admin = $this->getUser($datauser['international_branch_id'],4);
					if($datauser_admin['email1']!='' && $datauser_admin['email1']!=$datauser_admin['email'])
					    $email_temp[]=array('html'=>$html,'email'=>$datauser_admin['email1']);
				} 
					// added email for ankitsir on 19-4-2017 				
				//offline_id = 71 online id = 96
				$add_email_prashant = $this->getUser(144,2);
				     $email_temp[]=array('html'=>$html,'email'=>$add_email_prashant['email']);
				 $add_email_pratikbhai = $this->getUser(19,2);
				     $email_temp[]=array('html'=>$html,'email'=>$add_email_pratikbhai['email']);
			 	$add_email_msn = $this->getUser(237,2);
				     $email_temp[]=array('html'=>$html,'email'=>$add_email_msn['email']);
				        
				     
				$html='<table border="0px">';
			}

     // printr($email_temp);die;
		//	printr($html);die;
		
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(1); 
			$formEmail = $addedByInfo['email'];							
			$firstTimeemial = 0;
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
			$path = HTTP_SERVER."template/product_quotation.html";
			$output = file_get_contents($path);  
			$search  = array('{tag:header}','{tag:details}');
			$signature = 'Thanks.';
			if($addedByInfo['email_signature'])
				$signature = nl2br($addedByInfo['email_signature']);
			
		
			
			//printr($email_temp);die;
			foreach($email_temp as $val)
			{
				if($toEmail == '')
				{
					$toEmail = $formEmail;
					$firstTimeemial = 1;
				}				
				$subject = $sub;
				$message = '';
				if($val['html'])
				{
					$tag_val = array(
						"{{productDetail}}" =>$val['html'],
						"{{signature}}"	=> $signature,
					);
					if(!empty($tag_val))
					{
						$desc = $temp_desc;
						foreach($tag_val as $k=>$v)
						{
							@$desc = str_replace(trim($k),trim($v),trim($desc));
						} 
					}
					$replace = array($subject,$desc);
					$message = str_replace($search, $replace, $output);
				}
		
    			   send_email($val['email'],$formEmail,$subject,$message,$attachments,'','1');
				
	
			}		
		}
	
		    //die;
		$qstr_customer = '';
		if($UserDetail['email'] != '' && $firstTimeemial == 1)
		{
			$customer_email = $UserDetail['email'];
			$qstr_customer = " sent_customer = 1, customer_email = '".$customer_email."', ";
		}
		$qstr = '';
		if($firstTimeemial)
			$qstr = 'sent_admin = 1,';
		
		$this->query("INSERT INTO `" . DB_PREFIX . "multi_custom_order_email_history` SET multi_custom_order_id = '".$dat['multi_custom_order_id']."', customer_name = '".addslashes($dat['customer_name'])."', user_type_id = '" .$dat['added_by_user_type_id']. "', user_id = '" .$dat['added_by_user_id']. "', to_email = '".$toEmail."', from_email = '" .$formEmail. "',   $qstr_customer admin_default_email='".ADMIN_EMAIL."', status = '1', $qstr $pqcquery date_added = NOW()");
	//	die;
	
	    
	    
	}
	
	public function orderview($new_data,$shipping_address,$billing_address,$n=0,$addedByInfo,$data){
	    
	   	/*foreach($data as $dat)
	   	{
	   	    
	   	}*/
	   	$html='';
	   	$html .='<table border="0px">';
	    	$gettermsandconditions = $this->gettermsandconditions($dat['added_by_user_id'],$dat['added_by_user_type_id']);
				
				$shippingCountry = $this->getCountry($dat['shipment_country_id']);
				
				$html .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				//printr($new_data);
				foreach($new_data as $key=>$value)
				{
					//printr($qty_data);die;
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);

					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
        						{ //if($_SESSION['ADMIN_LOGIN_SWISS']=='41' && $_SESSION['LOGIN_USER_TYPE']=='4')
        						  //  printr($k);
        								if($k == "By Air") 
        								{
        									$color = "red";	
        								}elseif($k == "sea")
        								{
        									$color = "blue";	
        								}
        								elseif($k == "pickup")
        								{
        									$color = "green";
        								}
         
        							
        							if($dat['custom_order_type']==1)
        								$type=1;	
        							else
        								$type=0;
        								$qty_type = ''; $bag_types='bags'; $bag_type='bag';
        							if($dat['product_id'] == '6'){
        							    $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
        						
        						
        							//if($_SESSION['ADMIN_LOGIN_SWISS']=='41' && $_SESSION['LOGIN_USER_TYPE']=='4')
        							    //printr($bag_type.'----'.$qty_type.'----'.$bag_types.'-----'.$records[0]['quantity_type']);
        							
        								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
        					
        					
        					
        			
        							$newPirce =$records[0]['pouch_price'];//per pouch price
        							$gress_pouch_price =$records[0]['gress_pouch_price'];//per gress pouch price
        							$taxvalue='';
        						//	printr($records[0]);
        						
        							if($records[0]['tax_name']=='Normal')
        								$tax_name_data='No Form';
        							else
        								$tax_name_data=$records[0]['tax_name'];
        							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
        							{
        								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
        								$totaldis = $newPirce + ($newPirce*$records[0]['excies']/100);
        								$finaldis = $totaldis + ($totaldis *$records[0]['tax_percentage']/100);
        								$taxvaluedis = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 '.$bag_type.' including of all taxes.';											
        							}						
        							if($dat['shipment_country_id'] == '111')
        							{
        								$total = $newPirce + ($newPirce*$records[0]['excies']/100);
        								$final = $total + ($total *$records[0]['tax_percentage']/100);
        								$taxvalue = '';
        								$taxvalue = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($final,'3').' per 1 '.$bag_type.' including of all taxes.';
        							}
        							if($dat['product_id'] != '10')
        									$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
        								else
        									$bag='';
        							if($k=='By Air')
        							{
        								$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'];
        							}
        							if($k=='sea')
        							{
        								$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port';
        							}
        							if($k=='pickup')
        							{
        								$txt_tarnsport='By Pickup ';
        								$tax_str='Transportation cost NOT included.';
        							}
        							
        							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$dat['currency'].' '.$this->numberFormate($newPirce ,"3").' per 1 '.$bag_type.' '.$taxvalue.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$qty.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';	
        						
        						    if($n==1){
        						      //  printr('bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
                					/*	if(!empty($menu_id) || !empty($menu_id_pratik_sir))
                						{*/
                							if($dat['product_id'] != '10')
                								$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
                							else
                								$bag='';
                							$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Gress Price : </b>'.$dat['currency'].' '.$gress_pouch_price.' per 1 '.$bag_type.' '.$taxvaluedis.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
                					/*	}*/	
            							
        					    	}
        						}
						
					$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Make up of pouch  : </b> Custom  Digital Printed '.$dat['product_name'].' ';
					if($dat['product_id'] != '10')
						$m.=str_replace('<br>',' ',$records[0]['email_text']);
					$m.='</td></tr>';
					}
					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Size : </b>'.$size.'</td></tr> ';
				
				
					$html .=$m;
					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Printing Ink To Use : </b>'.$records[0]['printing_effect'].'</td></tr>';
					

					   $total_color = explode('==',$records[0]['total_color']);   


					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Quoted Printing in : </b>'.$total_color[1].' Colors </td></tr>';
				

					if($addedByInfo['country_id']==155)
						$txt='[Your Client Price]';
					else
						$txt='';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html .=$pmq;				
					$html .=$pmq_gress;

					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
			
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html .= '</table>';
				$html .= '<div><b> Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
				$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				if(!empty($shipping_address)){
        				$html .= '<div><b> Shipping Address ( Delivery Address ) : </b><br>&nbsp;&nbsp;&nbsp;&nbsp; '.nl2br($shipping_address['address']).'<br>&nbsp;&nbsp;&nbsp;&nbsp; '.$shipping_address['city'].','.$shipping_address['state'].'<br>';
        				if($shipping_address['postcode']!=''){ 
        					$html .= 'Postcode'.$shipping_address['postcode'];
        				}
        				if($data[0]['contact_number']!=''){ 
        					$html .= '<br>&nbsp;&nbsp;&nbsp;&nbsp; Contact no'.$data[0]['contact_number'];
        					}
        				$html .= '</div>';
		    	}
				$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				if(!empty($billing_address)){
    				$html .= '<div><b>Billing Address ( Buyer Address ) : </b><br> '.nl2br($billing_address['address']).'<br>&nbsp;&nbsp;&nbsp;&nbsp; '.$billing_address['city'].','.$billing_address['state'].'<br> ';
    				if($billing_address['postcode']!=''){ 
    					$html .= 'Postcode'.$billing_address['postcode'];
    					}
    				$html .= '</div>';
		    	}
			
		
			
			return $html;
	}


}

?>