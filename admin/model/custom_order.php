<?php 
class custom_order extends dbclass{
	
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
	
	public function getActiveMake(){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' ORDER BY make_name ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveLayer(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_layer` WHERE status='1' AND is_delete = '0'  ORDER BY layer ASC";				
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
	
	public function getWidthSuggestion($width,$product_id)
	{
		$sql1 = "SELECT width_to FROM product_extra_tool_price WHERE product_id = '" .(int)$product_id. "' AND width_to = '".$width."'";
		$data1 = $this->query($sql1);
		if($data1->num_rows)
		{
			return false;
		}
		else
		{
			$sql = "SELECT width_to FROM ( ( SELECT width_to,".$width."-width_to AS diff FROM product_extra_tool_price WHERE product_id = '" .(int)$product_id. "' AND width_to < ".$width." ORDER BY width_to DESC  ) UNION ALL ( SELECT width_to,width_to-".$width." AS diff FROM product_extra_tool_price WHERE product_id = '" .(int)$product_id. "' AND width_to > ".$width."  ORDER BY width_to ASC ) ) AS tmp ORDER BY diff LIMIT 2" ;
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
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
		$sql1 = "SELECT price,width_to,gusset FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."')  LIMIT 1";
	 	$data1 = $this->query($sql1);
	 	if( $data1->num_rows > 0)
	 	{
			$sql3 = "SELECT price,width_to,gusset FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."') ".$result." LIMIT 1";
			$data3 = $this->query($sql3);
			if( $data3->num_rows > 0)
			{
				if($gusset!='')
				{
					if($data3->row['gusset']==$gusset)
					{
						return 0;
					}
					elseif($data3->row['width_to']==$width && $data3->row['gusset']!=$gusset)
					{
						return $data3->rows;
					}
				}
				else
				{
					return 0;
				}
			}
			else
			{
				if(!$data3->num_rows)
				{
					$cond1 = " LIMIT 1";
					$sql = "SELECT price,width_to,gusset FROM  product_extra_tool_price
		WHERE product_id = '" .(int)$product_id. "' AND width_to ='".$width."'  LIMIT 2" ;
					$data = $this->query($sql);
					if($data->num_rows){
						return $data->rows;				
					}else{
						return false;
					}
				}
			}
	 	}
		else
		{
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
	
	public function getToolPrice($width,$gusset,$product_id)
	{
		$cond = '';
		$cond1 ='';
		$sql1 = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."')	AND gusset = '".$gusset."' LIMIT 1";
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
			$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND (width_to > '".(int)$width."'	) ".$cond1."";
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
			$return = array();
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
				$sql = "SELECT $getData,mcoi.reference_no,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode,mco.product_instruction  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN multi_custom_order_id as mcoi ON(mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE mco.multi_custom_order_id = '".(int)$custom_order_id."'";
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
				$sql = "SELECT $getData,mcoi.reference_no,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode,mco.product_instruction  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN " . DB_PREFIX ."multi_custom_order_id mcoi ON (mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE  mco.multi_custom_order_id = '".(int)$custom_order_id."' AND ((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str )";
				//echo $str;
			}
		}else{
			$sql = "SELECT $getData,mcoi.reference_no,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode,mco.product_instruction FROM " . DB_PREFIX ."multi_custom_order mco,multi_custom_order_id mcoi,address adr WHERE mco.multi_custom_order_id=mcoi.multi_custom_order_id  AND mco.multi_custom_order_id = '".(int)$custom_order_id."' AND mcoi.shipping_address_id=adr.address_id";
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
			//printr("INSERT INTO " . DB_PREFIX ."multi_custom_order_product_image SET order_product_id = '".$custom_order_id."',image_name='".addslashes($image['image_name'])."',ext='".$image['image_ext']."'");
		}	
	}
	
	public function insertDieLine($die_lines=array(),$custom_order_id){
		foreach($die_lines as $die_line){ 
	        $this->query("INSERT INTO " . DB_PREFIX ."multi_custom_order_product_die_line SET order_product_id = '".$custom_order_id."',name='".addslashes($die_line['die_name'])."',ext='".$die_line['die_ext']."'");
		//	printr("INSERT INTO " . DB_PREFIX ."multi_custom_order_product_die_line SET order_product_id = '".$custom_order_id."',name='".addslashes($die_line['die_name'])."',ext='".$die_line['die_ext']."'");
		}	
	} 
	
	public function insertNote($post)
	{
		$this->query("UPDATE multi_custom_order SET product_note='".$post['product_note']."',product_instruction='".$post['product_special_instruction']."' WHERE custom_order_id='".$post['custom_order_id']."'");
	
	}
	
	public function getCustomOrderQuantity($custom_order_id){
		$paking_price=$this->getCustomOrderPackingAndTransportDetails($custom_order_id);
		$data = $this->query("SELECT mco.quantity_type,mco.accept_decline_status,mco.custom_order_id,mcoq.cust_quantity,mcoq.product_code_id,mcoq.custom_order_quantity_id,mco.product_id,p.product_name, mcoq.custom_order_id,mcoq.discount, printing_effect,mcoq.quantity,mcoq.client_order_qty,mco.height,mco.width,mco.gusset,mco.volume, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mco.layer,total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage,mco.product_instruction FROM " . DB_PREFIX ."multi_custom_order_quantity as mcoq,multi_custom_order as mco,product as p WHERE mcoq.custom_order_id = '".(int)$custom_order_id."' AND mcoq.custom_order_id=mco.custom_order_id AND mco.product_id=p.product_id ORDER BY mcoq.custom_order_quantity_id") ;
	$return = array();
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				//printr($qunttData);
				$zdata = $this->query("SELECT cust_total_price,custom_order_price_id, custom_order_id, custom_order_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt,accessorie_txt_corner, total_price,client_total_price,total_price_with_excies,total_price_with_tax,tax_name,tax_type,tax_percentage,excies, gress_price, customer_gress_price,zipper_price,valve_price,courier_charge,spout_base_price,accessorie_base_price,transport_price,make_name FROM " . DB_PREFIX ."multi_custom_order_price as mcop ,product_make as pm WHERE custom_order_id = '".(int)$qunttData['custom_order_id']."' AND custom_order_quantity_id = '".(int)$qunttData['custom_order_quantity_id']."' AND mcop.make_pouch=pm.make_id ORDER BY transport_type");	
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
					//printr($zipData);
						$materialData = $this->getCustomOrderMaterial($zipData['custom_order_id']);
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
								'accept_decline_status'=>$qunttData['accept_decline_status'],
								'quantity_type'=>$qunttData['quantity_type'],
								'product_instruction'=>$qunttData['product_instruction'],
								'client_order_qty'=>$qunttData['client_order_qty'],
								'client_total_price'=>$zipData['client_total_price'],
							);
					}
				}
			}
		}
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
		   
		 $sql="SELECT mpqi.multi_quotation_number,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id FROM multi_product_quotation_id as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.multi_product_quotation_id $add_id";
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
			$sql="SELECT mpqi.multi_quotation_number,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id FROM multi_product_quotation_id as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.multi_product_quotation_id AND  ((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str )$add_id ";
		}
		if(!empty($filter_array)) {
			if(!empty($filter_array['custom_order_no'])){
				$sql .= " AND mcoi.multi_custom_order_number = '".$filter_array['custom_order_no']."'";
			}
			if(!empty($filter_array['quo_no'])){
				$sql .= " AND mpqi.multi_quotation_number LIKE '%".$filter_array['quo_no']."%'";
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
		$sql="SELECT mpqi.multi_quotation_number,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id,CONCAT(mcop.transport_type,'',',') as transportation FROM multi_product_quotation_id as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.multi_product_quotation_id $add_id";
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
	
	$sql="SELECT mpqi.multi_quotation_number,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id,CONCAT(mcop.transport_type,'',',') as transportation FROM multi_product_quotation_id as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.multi_product_quotation_id AND  ((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str )$add_id ";
		}
		 
		
		if(!empty($filter_array)) {
			if(!empty($filter_array['custom_order_no'])){
				$sql .= " AND mcoi.multi_custom_order_number = '".$filter_array['custom_order_no']."'";
			}
			if(!empty($filter_array['quo_no'])){
				$sql .= " AND mpqi.multi_quotation_number LIKE '%".$filter_array['quo_no']."%'";
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
		//send emial code
		$this->sendCustomOrderEmail($custom_order_id);
	}
	
	//public function sendCustomOrderEmail($custom_order_id,$toEmail = '',$setCustomOrderCurrencyId=''){		
//		$getData = ' custom_order_id,mco.layer, mco.added_by_user_id, mco.added_by_user_type_id, customer_name, customer_gress_percentage,  shipment_country_id, multi_custom_order_number,mco.multi_custom_order_id, custom_order_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,custom_order_type,layer,added_by_country_id, currency, currency_id, currency_price, mco.date_added, cylinder_price,tool_price, mcoi.email as customer_email,mco.status,discount,customer_gress_percentage,gress_percentage,gress_air,gress_sea ';
//		$data = $this->getCustomOrder($custom_order_id,$getData);
//		foreach($data as $dat)
//	   {
//		 $qdata= $this->getCustomOrderQuantity($dat['custom_order_id']);
//		 if($qdata!='')
//		  $quantityData[] =$qdata;
//	   }
//	   $str=$dat['product_name'];
//		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$str),0,3));		
//		$sub =$dat['multi_custom_order_number'] .' - '.ucwords($dat['customer_name']).' - custom printed '.$first;
//		//printr($quantityData);
//		$gussetvalue='';$s='';$sub2='   ';$m_k='';
//		foreach($quantityData as $k=>$qty_data)
//		{
//			foreach($qty_data as $tag=>$qty)
//			{
//				//printr($qty);
//				//die;
//				foreach($qty as $q=>$arr)
//				{
//					if($arr[0]['gusset'] == '')
//					{
//						$gussetval = 'No Gusset';
//					}
//					else
//					{
//						if($dat['product_id'] == '7')
//						{
//							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm gusset }';
//						}
//						elseif($arr[0]['gusset'] == '0')
//						{
//							 $gussetval = ' ';	
//						}
//						else
//						{
//							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.
//							$arr[0]['gusset'].'mm gusset}';
//						}
//					}
//					if($dat['product_id'] != '10')
//					{
//						if($arr[0]['volume']!='')
//						{ 
//							if($s!=$qty)
//							{
//								$sub2.=$arr[0]['volume'].' , ';
//								$s=$qty;
//							}
//							$gussetvalue.=$arr[0]['volume'].' , ';
//						}
//					}
//					if($gussetvalue=='')
//						$gussetvalue=' W : '.(int)$arr[0]['width'].'mm  x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval;
//					if($arr[0]['volume']=='')
//						$arr[0]['volume']='Custom';
//					
//					if($dat['product_id'] != '10')
//						$key='W : '.(int)$arr[0]['width'].'mm x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval.' ['.$arr[0]['volume'].']';
//					else
//						$key='W : '.(int)$arr[0]['width'].'mm x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval;
//						$m_k[$arr[0]['custom_order_quantity_id']]='';
//				
//					$new_data[$arr[0]['text']][$key.' ('.$arr[0]['layer'].') '][$q.'('.$arr[0]['custom_order_quantity_id'].')'][$tag][]=$arr[0];
//					if($dat['product_id'] != '10')
//					{
//						$sub1= ' '.$arr[0]['zipper_txt'].' '.$arr[0]['valve_txt'].''.$sub2; 
//					}
//					else
//						$sub1= $sub2; 
//				}
//			}	
//			$n[$tag][]=array('width'=>$arr[0]['width'],'height'=>$arr[0]['height'],'gusset'=>$arr[0]['gusset'],'volume'=>$arr[0]['volume']);
//			$t=$tag;
//		}
//		$addedByInfo = $this->getUser($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
//	//	printr($addedByInfo);die;
//		//printr($m_k);die;
//		$sub1= substr($sub1,0,-3);
//		$sub=$sub.$sub1;
//		//printr($sub);die;
//		$html='';$tax_str='';
//		if($addedByInfo)
//		{
//			$pqcquery='';
//			$selCurrency = $this->getCustomOrderCurrecy($setCustomOrderCurrencyId,1);
//		//	printr($selCurrency);
//			if($selCurrency)
//				$pqcquery = " custom_order_currency_id = '".$selCurrency['custom_order_currency_id']."', ";
//			$currency_rate[]=array('currency_rate'=>1,'user'=>0);
//			if($dat['customer_email'] != '' || $toEmail != '')
//				$currency_rate[]=array('currency_rate'=>($selCurrency['currency_rate']!='')?$selCurrency['currency_rate']:1,'user'=>1);
//			else
//				$currency_rate[]=array('currency_rate'=>1,'user'=>2);
//			//printr($currency_rate);die;
//			$html .='<table border="0px">';
//			foreach($currency_rate as $cr)
//			{
//				$gettermsandconditions = $this->gettermsandconditions($dat['added_by_user_id'],$dat['added_by_user_type_id']);
//				
//				$shippingCountry = $this->getCountry($dat['shipment_country_id']);
//				
//				$html .= '<style> table, th, td </style>';
//				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
//				//printr($new_data);
//				foreach($new_data as $key=>$value)
//				{
//					//printr($qty_data);die;
//					foreach($value as $size=>$qty_data)
//					{
//						(int)$size= preg_replace("/\([^)]+\)/","",$size);
//						//printr($size);
//					foreach($qty_data as $qty=>$transport)
//					{//printr($transport);die;
//					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
//						foreach($transport as $k=>$records)
//						{
//								if($k == "air") 
//								{
//									$color = "red";	
//								}elseif($k == "sea")
//								{
//									$color = "blue";	
//								}
//								elseif($k == "pickup")
//								{
//									$color = "green";
//								}
//							if($selCurrency)
//							{
//								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code']);	
//								//printr($cr['currency_rate']);
//								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
//								$tool_price=0;
//								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
//									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
//								if($cylinder_base_price)
//								{
//									if($cylinder_currency_price < $cylinder_base_price)
//										$cylinder_price = $cylinder_base_price;	
//									else
//										$cylinder_price = $cylinder_currency_price;	
//								}
//								else
//									$cylinder_price = $cylinder_currency_price;	
//							 }
//							 else
//							 {
//								$selCurrency['currency_code'] = $dat['currency'];
//								$cylinder_price = $records[0]['cylinder_price'];
//								$tool_price = $records[0]['tool_price'];
//							 }
//							if($dat['custom_order_type']==1)
//								$type=1;	
//							else
//								$type=0;
//								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type); 
//						//	echo $cr['user'];
//							if($cr['user']==0 || $addedByInfo['country_id']==155)
//							{
//								if($records[0]['gress_percentage'] != '0.000' OR $records[0]['gress_air'] !='0.000' OR $records[0]['gress_sea'] !='0.000')
//								{
//									if($dat['shipment_country_id']==91)
//										$txt='[Your Purchase Price]';
//									else
//										$txt='';
//									$gress = '<tr><td colspan="2" >&nbsp;</td></tr><tr valign="top"><td width="60" colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Percentage Prices:'.$txt.'</b></td></tr>';								
//									$newPircegress = ((($records[0]['totalPrice'] - $records[0]['customerGressPrice'] - $records[0]['gress_price']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
//								//	echo '((('.$records[0]['totalPrice'].' - '.$records[0]['customerGressPrice'] .'-'. $records[0]['gress_price'].') / '.(float)$dat['currency_price'].') / '.(float)$cr['currency_rate'].' )';
//									$newPircegress = $newPircegress/$qty;
//									$taxvaluegress='';
//									if($records[0]['tax_name']=='Normal')
//											$tax_name_data='No Form';
//										else
//											$tax_name_data=$records[0]['tax_name'];
//									//printr($tax_name_data);
//									//die;
//									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
//									{
//										$newPircegress =$this->numberFormate(($newPircegress-($newPircegress*$records[0]['discount']/100)),"3");
//										$totaldisgress = $newPircegress + ($newPircegress*$records[0]['excies']/100);
//										$finaldisgress = $totaldisgress + ($totaldisgress *$records[0]['tax_percentage']/100);
//										
//										$taxvaluedisgress = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' per 1 bag including of all taxes.';	
//									}						
//									if($dat['shipment_country_id'] == '111')
//									{
//										$totalgress = $newPircegress + ($newPircegress*$records[0]['excies']/100);
//										$finalgress = $totalgress + ($totalgress *$records[0]['tax_percentage']/100);
//										$taxvaluegress = '';
//										$taxvaluegress = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' per 1 bag including of all taxes.';
//									}
//									
//									if($k=='air')
//									{
//										$gp=$records[0]['gress_air'];
//										$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'];
//									}
//									if($k=='sea')
//									{
//										$gp=$records[0]['gress_sea'];
//										$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port';
//									}
//									if($k=='pickup')
//									{
//										$gp=$records[0]['gress_percentage'];
//										$tax_str='Transportation cost NOT included.';
//										$txt_tarnsport='By Pickup ';
//									}
//									if($dat['product_id'] != '10')
//										$bag='plus or minus '.$plus_minus_quantity.' bags';
//									else
//										$bag='';
//									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate($newPircegress ,"3").' per 1 bag '.$taxvaluegress.'<b> { For '.$qty.' bags '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';	
//									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
//									{
//										if($dat['product_id'] != '10')
//											$bag='plus or minus '.$plus_minus_quantity.' bags';
//										else
//											$bag='';
//										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b></td><td>'.$dat['currency'].' '.$newPircegress.' per 1 bag '.$taxvaluedisgress.'<b> { For '.$qty.' bags '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';
//									}
//								}	
//							}
//							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
//							//echo '((('.$records[0]['totalPrice'].' +'. $records[0]['customerGressPrice'].') / '.(float)$dat['currency_price'].') / '.(float)$cr['currency_rate'] .')';
//							$newPirce = $newPirce/$qty;
//							$taxvalue='';
//							if($records[0]['tax_name']=='Normal')
//								$tax_name_data='No Form';
//							else
//								$tax_name_data=$records[0]['tax_name'];
//							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
//							{
//								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
//								$totaldis = $newPirce + ($newPirce*$records[0]['excies']/100);
//								$finaldis = $totaldis + ($totaldis *$records[0]['tax_percentage']/100);
//								$taxvaluedis = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';											
//							}						
//							if($dat['shipment_country_id'] == '111')
//							{
//								$total = $newPirce + ($newPirce*$records[0]['excies']/100);
//								$final = $total + ($total *$records[0]['tax_percentage']/100);
//								$taxvalue = '';
//								$taxvalue = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
//							}
//							if($dat['product_id'] != '10')
//									$bag='plus or minus '.$plus_minus_quantity.' bags';
//								else
//									$bag='';
//							if($k=='air')
//							{
//								$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'];
//							}
//							if($k=='sea')
//							{
//								$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port';
//							}
//							if($k=='pickup')
//							{
//								$txt_tarnsport='By Pickup ';
//								$tax_str='Transportation cost NOT included.';
//							}
//							
//							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate($newPirce ,"3").' per 1 bag '.$taxvalue.'<b> { For '.$qty.' bags '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';	
//							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
//							{
//								if($dat['product_id'] != '10')
//									$bag='plus or minus '.$plus_minus_quantity.' bags';
//								else
//									$bag='';
//								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b>'.$dat['currency'].' '.$newPirce.' per 1 bag '.$taxvaluedis.'<b> { For '.$qty.' bags '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
//							}							
//						}
//						
//						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Make up of pouch  : </b> Custom Printed '.$dat['product_name'].' ';
//					if($dat['product_id'] != '10')
//						$m.=str_replace('<br>',' ',$records[0]['email_text']);
//					$m.='</td></tr>';
//					}
//					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Size : </b>'.$size.'</td></tr> ';
//					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Material : </b>';
//					$materialData = $this->getCustomOrderMaterial($records[0]['materialData'][0]['custom_order_id']);
//					if(isset($materialData) && !empty($materialData))
//					{
//						$materialStr = '';
//						for($gi=0;$gi<count($materialData);$gi++)
//						{
//							$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
//						}
//						$html .= ''.substr($materialStr,0,-3).'</td>';
//					}
//					$materialData='';
//					$html .= '</tr>';
//					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Shipment Country : </b>'.$shippingCountry['country_name'].'</td></tr>';
//					$html .=$m;
//					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Printing Effect : </b>'.$records[0]['printing_effect'].'</td></tr>';
//					if($addedByInfo['country_id']==155)
//						$txt='[Your Client Price]';
//					else
//						$txt='';
//					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
//					$html .=$pmq;
//					$html .=$gress;
//					$html .=$pmq_gress;
//					if($dat['product_id'] != '10')
//					{
//						$html .= '<tr><td colspan="2">&nbsp;</td></tr>
//					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($cylinder_price),"3").'</td></tr>';
//					}
//					if(isset($tool_price) && $tool_price>0.000)
//					{
//						$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Extra Tool price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($tool_price),"3").'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</td></tr>';
//					}
//					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
//					$pmq='';
//					$pmq_gress='';
//					$m='';
//					$gress='';
//					$i++;	//printr($html);die;
//					}
//					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
//				}
//				$html .= '</table>';
//				$html .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp; Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
//				
//				if($cr['user']==1 || $cr['user']==2)
//				{
//					//printr($html);die;
//					if($cr['user']==1)
//						$email_temp[]=array('html'=>$html,'email'=>($dat['customer_email'])?$dat['customer_email']:ADMIN_EMAIL);
//					if($cr['user']==2)
//						$email_temp[]=array('html'=>$html,'email'=>$addedByInfo['email']);
//				}
//				if($toEmail!='' && $cr['user']!=0)
//					$email_temp[]=array('html'=>$html,'email'=>$toEmail);
//				if($cr['user']==0)
//					$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);
//				$html='<table border="0px">';
//			}
//			$obj_email = new email_template();
//			$rws_email_template = $obj_email->get_email_template(1); 
//			$formEmail = $addedByInfo['email'];							
//			$firstTimeemial = 0;
//			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
//			$path = HTTP_SERVER."template/product_quotation.html";
//			$output = file_get_contents($path);  
//			$search  = array('{tag:header}','{tag:details}');
//			$signature = 'Thanks.';
//			if($addedByInfo['email_signature'])
//				$signature = nl2br($addedByInfo['email_signature']);
//			
//			//printr($email_temp);die;
//			foreach($email_temp as $val)
//			{
//				if($toEmail == '')
//				{
//					$toEmail = $formEmail;
//					$firstTimeemial = 1;
//				}				
//				$subject = $sub;
//				$message = '';
//				if($val['html'])
//				{
//					$tag_val = array(
//						"{{productDetail}}" =>$val['html'],
//						"{{signature}}"	=> $signature,
//					);
//					if(!empty($tag_val))
//					{
//						$desc = $temp_desc;
//						foreach($tag_val as $k=>$v)
//						{
//							@$desc = str_replace(trim($k),trim($v),trim($desc));
//						} 
//					}
//					$replace = array($subject,$desc);
//					$message = str_replace($search, $replace, $output);
//				}
//				send_email($val['email'],$formEmail,$subject,$message,'');
//			//printr($message);	
//			}		
//		}//die;
//		$qstr_customer = '';
//		if($dat['customer_email'] != '' && $firstTimeemial == 1)
//		{
//			$customer_email = $dat['customer_email'];
//			$qstr_customer = " sent_customer = 1, customer_email = '".$customer_email."', ";
//		}
//		$qstr = '';
//		if($firstTimeemial)
//			$qstr = 'sent_admin = 1,';
//		
//		$this->query("INSERT INTO `" . DB_PREFIX . "multi_custom_order_email_history` SET multi_custom_order_id = '".$dat['multi_custom_order_id']."', customer_name = '".addslashes($dat['customer_name'])."', user_type_id = '" .$dat['added_by_user_type_id']. "', user_id = '" .$dat['added_by_user_id']. "', to_email = '".$toEmail."', from_email = '" .$formEmail. "',   $qstr_customer admin_default_email='".ADMIN_EMAIL."', status = '1', $qstr $pqcquery date_added = NOW()");
//		//die;
//	}
//	
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
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		$size = 0;
		if(!empty($productGusset)){
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);		
			}			
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ((($gusset*2) + $height)* $width); 
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*1)+$height)*$width);
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*3) + $height) * $width);
			}
		}
		if($qty_type=='')
		{
		    $qunatityRow = $this->query("SELECT product_quantity_id FROM " . DB_PREFIX . "product_quantity WHERE quantity = '".$quantity."'");
	        $quantity_id = $qunatityRow->row['product_quantity_id'];
		    $data = $this->query("SELECT plus_minus_quantity	FROM " . DB_PREFIX . "product_profit WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND size_from <= '".$size."' AND 	size_to >= '".$size."'");
		}
		else
		{
		    $data = $this->query("SELECT plus_minus_quantity FROM " . DB_PREFIX . "roll_quantity WHERE quantity = '".$quantity."' AND quantity_type = '".$qty_type."'");
		}
		   
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
	
	/*public function getNewCurrencys(){
		$data = $this->query("SELECT cn.currency_code,cs.currency_id FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' ");
		if($data->num_rows){
			return  $data->rows;
		}else{
			return false;
		}
	}*/
	public function getNewCurrencys(){
		if($_SESSION['ADMIN_LOGIN_SWISS'] == 1 && $_SESSION['LOGIN_USER_TYPE'] == 1)
		{
		   	$data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."'  ORDER BY cs.currency_id ASC");
		}
		else
		{
    		if($_SESSION['LOGIN_USER_TYPE'] == 2){
    			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    			$set_user_id = $parentdata->row['user_id'];
    			$set_user_type_id = $parentdata->row['user_type_id'];
    		    if($set_user_id=='10')
    			    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."' ORDER BY cs.currency_id ASC");
                elseif($_SESSION['ADMIN_LOGIN_SWISS']=='37' || $_SESSION['ADMIN_LOGIN_SWISS']=='199')
                    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."' ORDER BY cs.currency_id ASC");
                else
                    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."' ORDER BY cs.currency_id ASC");

    		}else{
    			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    		    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."' ORDER BY cs.currency_id ASC");

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
	//	echo "SELECT multi_product_quotation_id FROM " . DB_PREFIX ."multi_product_quotation_id WHERE multi_quotation_number = '".$quotation_no."'";
		$data = $this->query("SELECT multi_product_quotation_id FROM " . DB_PREFIX ."multi_product_quotation_id WHERE multi_quotation_number = '".$quotation_no."'");
		if($data->num_rows)
		{
			return $data->row['multi_product_quotation_id'];
		}
		else
			return false;
	}
	
	public function addQuotationToCustomOrder($data)
	{	
		
		$first_name = addslashes($data['first_name']);
		$last_name = addslashes($data['last_name']);
		
	
		
		//[kinjal] : changed code on 23-6-2017
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
		
		if(isset($data['shipping_state'])) {			
			$data['shipping_state'] = $data['shipping_state'];
		}
		else {
			$data['shipping_state'] = '';
		}
	
		if(isset($data['billing_state'])) {			
			$data['billing_state'] = $data['billing_state'];
		}
		else {
			$data['billing_state'] = '';
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
		$this->query("INSERT INTO `" . DB_PREFIX . "address` SET user_type_id=0, user_id=0 , address_type_id=1, first_name='".$first_name."', last_name = '".$last_name."', address = '".$data['shipping_address_1']."', address_2 = '".$data['shipping_address_2']."', city = '".$data['shipping_city']."',state = '".$data['shipping_state']."',  country_id = '".$shipment_country."', zone_id=0, date_added=NOW(), date_modify=NOW()   ");
		$shipping_address_id = $this->getLastId();
		if(!empty($data['same_as_above']) && $data['same_as_above']==1){
			$this->query("INSERT INTO `" . DB_PREFIX . "address` SET user_type_id=0, user_id=0 , address_type_id=2, first_name='".$first_name."', last_name = '".$last_name."', address = '".$data['shipping_address_1']."', address_2 = '".$data['shipping_address_2']."', city = '".$data['shipping_city']."',state = '".$data['shipping_state']."',  country_id = '".$shipment_country."', zone_id=0, date_added=NOW(), date_modify=NOW()   ");	
		}else{
			$this->query("INSERT INTO `" . DB_PREFIX . "address` SET user_type_id=0, user_id=0 , address_type_id=2, first_name='".$first_name."', last_name = '".$last_name."', address = '".$data['billing_address_1']."', address_2 = '".$data['billing_address_2']."', city = '".$data['billing_city']."',state = '".$data['billing_state']."', country_id = '".$data['billing_country_id']."', zone_id=0, date_added=NOW(), date_modify=NOW()");	
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
		

		$sql =  "INSERT INTO ".DB_PREFIX."multi_custom_order_id SET  multi_product_quotation_id='".$data['multi_quote_id']."',company_name='".$this->escape($data['company'])."', website='".$data['website']."', customer_name='".$this->escape($customer_name)."',address_book_id='".$data['address_book_id']."', email = '".$data['email']."', contact_number='".$data['contact_number']."',reference_no ='".$ref_no."', vat_number = '".$data['vat_number']."', shipping_address_id = '".$shipping_address_id."',  billing_address_id = '".$billing_address_id."', order_currency = '".$order_currency_query->row['currency_code']."',currency_rate = '".$order_currency_query->row['product_rate']."',order_note ='".$data['order_note']."', order_instruction = '".$data['order_instruction']."',custom_order_status=1,status = '0', date_added = NOW(),date_modify = NOW(), added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."' $admin_data ";		
		$this->query($sql);
		$multi_custom_order_id = $this->getLastId();
	
		$custom_order_number = $this->generateCustomOderNumber($multi_custom_order_id);
		$sql =  "UPDATE  ".DB_PREFIX."multi_custom_order_id SET multi_custom_order_number = '".$custom_order_number."' WHERE multi_custom_order_id = '".$multi_custom_order_id."'";
		$this->query($sql);
		foreach($data['product_quotation_price_id'] as $product_quotation_price_id_arr)
		{	
			$arr = explode("==",$product_quotation_price_id_arr);
			$product_quotation_price_id = $arr[0];
			
			//get records from multi_product_quotation_price
			$multi_product_quotation_price = $this->query("SELECT * FROM " . DB_PREFIX . "multi_product_quotation_price WHERE product_quotation_price_id = '".$product_quotation_price_id."' LIMIT 1 ");	
			$multi_product_quotation_price_ary = array($multi_product_quotation_price->row['product_quotation_price_id'] =>  $multi_product_quotation_price->row);
			$multi_product_quotation_price_ary2 =array($multi_product_quotation_price->row['product_quotation_quantity_id'] => $multi_product_quotation_price_ary);
			$price_data=array($multi_product_quotation_price->row['product_quotation_id'] => $multi_product_quotation_price_ary2);
			

			//get records from multi_product_quotation base on product_quotation_id from price table
			$quot_data= $this->query("SELECT * FROM " . DB_PREFIX . "multi_product_quotation WHERE product_quotation_id = '".$multi_product_quotation_price->row['product_quotation_id']."' LIMIT 1");	
		$multi_product_quotation =array($multi_product_quotation_price->row['product_quotation_id'] => $quot_data->row);

			//get records from multi_product_quotation_layer base on product_quotation_id from price table
			$layer_data= $this->query("SELECT * FROM " . DB_PREFIX . "multi_product_quotation_layer WHERE product_quotation_id = '".$multi_product_quotation_price->row['product_quotation_id']."' ");	
		$multi_product_quotation[$multi_product_quotation_price->row['product_quotation_id']]['layer']=$layer_data->rows;
		
			//get records from multi_product_quotation_quantity based on product_quotation_quantity_id from price table
			$qty_data = $this->query("SELECT * FROM " . DB_PREFIX . "multi_product_quotation_quantity WHERE product_quotation_quantity_id = '".$multi_product_quotation_price->row['product_quotation_quantity_id']."' LIMIT 1");
			$qty_data->row['product_quotation_price_id'] = $product_quotation_price_id;
			
			
		$multi_product_quotations=array($multi_product_quotation_price->row['product_quotation_quantity_id'] => $qty_data->row);
		
	$multi_product_quotations[$multi_product_quotation_price->row['product_quotation_quantity_id']]['price_data']=$price_data[$multi_product_quotation_price->row['product_quotation_id']][$multi_product_quotation_price->row['product_quotation_quantity_id']];
			
			
			$multi_product_quotation[$multi_product_quotation_price->row['product_quotation_id']]['product_quotation_quantity_data']=$multi_product_quotations;
			
			$mul_pro_quto[]=$multi_product_quotation;
			
			
		}	
		//printr($mul_pro_quto);
		//die;
			
	foreach($mul_pro_quto as $multi_product_quotation)
	{
		foreach($multi_product_quotation as $product_quotation_id=>$product_quotation_data)
		{
			
			$sql =  "INSERT INTO ".DB_PREFIX."multi_custom_order SET product_id = '".(int)$product_quotation_data['product_id']."', product_name = '".$product_quotation_data['product_name']."', printing_option = '".$product_quotation_data['printing_option']."', printing_effect_id = '".(int)$product_quotation_data['printing_effect_id']."', printing_effect = '".$product_quotation_data['printing_effect']."', height = '".(float)$product_quotation_data['height']."', width = '".(float)$product_quotation_data['width']."', gusset = '".(float)$product_quotation_data['gusset']."', volume = '".$product_quotation_data['volume']."', layer = '".(int)$product_quotation_data['layer']."', ink_price = '".(float)$product_quotation_data['ink_price']."', ink_solvent_price = '".(float)$product_quotation_data['ink_solvent_price']."', printing_effect_price = '".(float)$product_quotation_data['printing_effect_price']."', adhesive_price = '".(float)$product_quotation_data['adhesive_price']."', cpp_adhesive = '".(int)$product_quotation_data['cpp_adhesive']."', adhesive_solvent_price = '".(float)$product_quotation_data['adhesive_solvent_price']."', native_price = '".(float)$product_quotation_data['native_price']."',packing_price = '".(float)$product_quotation_data['packing_price']."', valve_price = '".$product_quotation_data['valve_price']."', gress_percentage = '".$product_quotation_data['gress_percentage']."',gress_air = '".$product_quotation_data['gress_air']."',gress_sea = '".$product_quotation_data['gress_sea']."', cylinder_price = '".(float)$product_quotation_data['cylinder_price']."', tool_price = '".(float)$product_quotation_data['tool_price']."', use_device = '".getDevice()."', status = '0', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', currency = '".$product_quotation_data['currency']."', currency_price = '".$product_quotation_data['currency_price']."', customer_gress_percentage = '".$product_quotation_data['customer_gress_percentage']."', product_note = '".$data['product_note']."', product_instruction = '".$data['product_special_instruction']."', shipment_country_id = '".$product_quotation_data['shipment_country_id']."', added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."', multi_custom_order_id = '".$multi_custom_order_id."',quantity_type = '".$product_quotation_data['quantity_type']."'";
			$this->query($sql);
			$CustomOrderId = $this->getLastId();
			
			//get records from multi_product_quotation_base_price base on product_quotation_id from price table
			$base_price_data= $this->query("SELECT * FROM " . DB_PREFIX . "multi_product_quotation_base_price WHERE product_quotation_id = '".$product_quotation_id."' LIMIT 1");	
			$base_price =$base_price_data->row;
			
			$this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_base_price SET custom_order_id = '".(int)$CustomOrderId."', ink_base_price = '".$base_price['ink_base_price']."', ink_solvent_base_price = '".$base_price['ink_solvent_base_price']."', printing_effect_base_price = '".$base_price['printing_effect_base_price']."', adhesive_base_price = '".$base_price['adhesive_base_price']."', cpp_adhesive_base_price = '".$base_price['cpp_adhesive_base_price']."', adhesive_solvent_base_price = '".$base_price['adhesive_solvent_base_price']."', packing_price = '".$base_price['packing_price']."', spout_packing_price = '".$base_price['spout_packing_price']."', spout_courier_price = '".$base_price['spout_courier_price']."', transport_width_base_price = '".$base_price['transport_width_base_price']."', transport_height_base_price = '".$base_price['transport_height_base_price']."', cylinder_base_price = '".$base_price['cylinder_base_price']."', cylinder_vendor_base_price = '".$base_price['cylinder_vendor_base_price']."', cylinder_currency_base_price = '".$base_price['cylinder_currency_base_price']."', fuel_surcharge = '".$base_price['fuel_surcharge']."', service_tax = '".$base_price['service_tax']."', handling_charge = '".$base_price['handling_charge']."', date_added = NOW()");				
					
			foreach($product_quotation_data['layer'] as $layer_arr){								
				$setSql = "INSERT INTO ".DB_PREFIX."multi_custom_order_layer SET custom_order_id = '".(int)$CustomOrderId."', layer='".$layer_arr['layer']."',material_id='".$layer_arr['material_id']."',material_gsm='".$layer_arr['material_gsm']."',material_thickness='".$layer_arr['material_thickness']."',material_price='".$layer_arr['material_price']."',material_name='".$layer_arr['material_name']."',layer_wise_gsmthickness='".$layer_arr['layer_wise_gsmthickness']."',layer_wise_price='".$layer_arr['layer_wise_price']."',date_added=NOW()";
				$this->query($setSql);
			}
			foreach($product_quotation_data['product_quotation_quantity_data'] as $qty_id=>$product_quotation_quantity_data)
			{
				$cust_name = 'cust_qty_'.$product_quotation_quantity_data['product_quotation_price_id'];
				$pro_code_id ='product_code_id_'.$product_quotation_quantity_data['product_quotation_price_id'];
				$client_qty = $data['added_order_qty_'.$product_quotation_quantity_data['product_quotation_price_id']];
				$client_rate = $data['added_order_rate_'.$product_quotation_quantity_data['product_quotation_price_id']];
				$client_total_price = $client_qty*$client_rate ;
				/*if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1'){
				    printr($client_rate);
				    printr($client_qty);
				    printr($product_quotation_quantity_data['product_quotation_price_id']);
				    printr("INSERT INTO ".DB_PREFIX."multi_custom_order_quantity SET custom_order_id = '".$CustomOrderId."', product_code_id = '".$data[$pro_code_id]."', quantity = '".$product_quotation_quantity_data['quantity']."', client_order_qty = '".$client_qty."',cust_quantity= '".$data[$cust_name]."', wastage_adding = '".$product_quotation_quantity_data['wastage_adding']."', wastage_base_price = '".$product_quotation_quantity_data['wastage_base_price']."', wastage = '".$product_quotation_quantity_data['wastage']."', native_price_per_bag = '".$product_quotation_quantity_data['native_price_per_bag']."', profit = '".$product_quotation_quantity_data['profit']."', total_weight_without_zipper = '".$product_quotation_quantity_data['total_weight_without_zipper']."', total_weight_with_zipper = '".$product_quotation_quantity_data['total_weight_with_zipper']."', date_added = NOW(),discount='".$product_quotation_quantity_data['discount']."'");;
				    printr("INSERT INTO ".DB_PREFIX."multi_custom_order_price SET custom_order_id = '".$CustomOrderId."', custom_order_quantity_id = '".$customOrderQuantityId."', transport_type = '".$price_arr['transport_type']."', zipper_txt = '".$price_arr['zipper_txt']."', valve_txt = '".$price_arr['valve_txt']."', spout_txt = '".$price_arr['spout_txt']."', make_pouch = '".$price_arr['make_pouch']."', spout_base_price = '".$price_arr['spout_base_price']."', accessorie_txt = '".$price_arr['accessorie_txt']."',  accessorie_txt_corner = '".$price_arr['accessorie_txt_corner']."', accessorie_base_price_corner = '".$price_arr['accessorie_base_price_corner']."',date_added = NOW(),accessorie_base_price='".$price_arr['accessorie_base_price']."',transport_base_price='".$price_arr['transport_base_price']."',transport_price='".$price_arr['transport_price']."',courier_base_price_withzipper='".$price_arr['courier_base_price_withzipper']."',courier_base_price_nozipper='".$price_arr['courier_base_price_nozipper']."',courier_charge='".$price_arr['courier_charge']."',zipper_price='".$price_arr['zipper_price']."',valve_price='".$price_arr['valve_price']."',total_price='".$price_arr['total_price']."',client_total_price='".$client_total_price."',cust_total_price='".$data[$cust_total]."',total_price_with_excies='".$price_arr['total_price_with_excies']."',total_price_with_tax='".$price_arr['total_price_with_tax']."',tax_type='".$price_arr['tax_type']."',tax_name='".$price_arr['tax_name']."',tax_percentage='".$price_arr['tax_percentage']."',excies='".$price_arr['excies']."',gress_price='".$price_arr['gress_price']."',customer_gress_price='".$price_arr['customer_gress_price']."',total_charge='".$price_arr['total_charge']."'");
				    die;
				}*/
				$this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_quantity SET custom_order_id = '".$CustomOrderId."', product_code_id = '".$data[$pro_code_id]."', quantity = '".$product_quotation_quantity_data['quantity']."', client_order_qty = '".$client_qty."',cust_quantity= '".$data[$cust_name]."', wastage_adding = '".$product_quotation_quantity_data['wastage_adding']."', wastage_base_price = '".$product_quotation_quantity_data['wastage_base_price']."', wastage = '".$product_quotation_quantity_data['wastage']."', native_price_per_bag = '".$product_quotation_quantity_data['native_price_per_bag']."', profit = '".$product_quotation_quantity_data['profit']."', total_weight_without_zipper = '".$product_quotation_quantity_data['total_weight_without_zipper']."', total_weight_with_zipper = '".$product_quotation_quantity_data['total_weight_with_zipper']."', date_added = NOW(),discount='".$product_quotation_quantity_data['discount']."'");
				$customOrderQuantityId = $this->getLastId();		
				
				foreach($product_quotation_quantity_data['price_data'] as $price_arr)
				{
					$cust_total = 'cust_total_'.$product_quotation_quantity_data['product_quotation_price_id'];
					$this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_price SET custom_order_id = '".$CustomOrderId."', custom_order_quantity_id = '".$customOrderQuantityId."', transport_type = '".$price_arr['transport_type']."', zipper_txt = '".$price_arr['zipper_txt']."', valve_txt = '".$price_arr['valve_txt']."', spout_txt = '".$price_arr['spout_txt']."', make_pouch = '".$price_arr['make_pouch']."', spout_base_price = '".$price_arr['spout_base_price']."', accessorie_txt = '".$price_arr['accessorie_txt']."',  accessorie_txt_corner = '".$price_arr['accessorie_txt_corner']."', accessorie_base_price_corner = '".$price_arr['accessorie_base_price_corner']."',date_added = NOW(),accessorie_base_price='".$price_arr['accessorie_base_price']."',transport_base_price='".$price_arr['transport_base_price']."',transport_price='".$price_arr['transport_price']."',courier_base_price_withzipper='".$price_arr['courier_base_price_withzipper']."',courier_base_price_nozipper='".$price_arr['courier_base_price_nozipper']."',courier_charge='".$price_arr['courier_charge']."',zipper_price='".$price_arr['zipper_price']."',valve_price='".$price_arr['valve_price']."',total_price='".$price_arr['total_price']."',client_total_price='".$client_total_price."',cust_total_price='".$data[$cust_total]."',total_price_with_excies='".$price_arr['total_price_with_excies']."',total_price_with_tax='".$price_arr['total_price_with_tax']."',tax_type='".$price_arr['tax_type']."',tax_name='".$price_arr['tax_name']."',tax_percentage='".$price_arr['tax_percentage']."',excies='".$price_arr['excies']."',gress_price='".$price_arr['gress_price']."',customer_gress_price='".$price_arr['customer_gress_price']."',total_charge='".$price_arr['total_charge']."'");
					$customOrderPriceId = $this->getLastId();		
				}				
			}
		}
	}
	    //if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
	        //die;
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
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,5,'0',STR_PAD_LEFT);
		$number = 'CUST'.$strpad;
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
		//printr($custom_detail);
		//$url_dieline
		//$base_url = base_url();
		//[kinjal] edited on 11-5-2017
		$attachments='';
		if(!empty($custom_detail['multi_order']))
		{ //echo 'kinjal';
    		foreach($custom_detail['multi_order'] as $image)
    		{
    					$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    					
    					if($ext!='pdf')
    					{
    						$url_dieline[] = DIR_UPLOAD.'admin/dieline/'.$image['name'].'';
    							
    					}
    					else
    					{
    						$url_dieline[] = DIR_UPLOAD.'admin/pdfdieline/'.$image['name'].'';
    					}
    				
    		}
		}
				
	    //$url_artwork[]='';
	    if(!empty($custom_detail['artwork']))
		{
    		foreach($custom_detail['artwork'] as $image)
    		{
    			$ext = pathinfo($image['image_name'], PATHINFO_EXTENSION); 
    			if($ext!='pdf')
    			{
    				$url_artwork[] = DIR_UPLOAD.'admin/artwork/100_'.$image['image_name'].'';
    				
    			}
    			else
    			{
    				$url_artwork[] = DIR_UPLOAD.'admin/pdfartwork/'.$image['image_name'].'';
    			}
    			
    				
    				
    		
    		}
		}
		//die;		//$url = array_push($url_artwork,$url_dieline) ;
				//$c = array_combine($a, $b);
		    	if(isset($url_dieline) && isset($url_artwork))
					$attachments = array_merge($url_dieline,$url_artwork);
		    	else if(isset($url_artwork))
					$attachments = $url_artwork;
				else if(isset($url_dieline))
					$attachments = $url_dieline;
				else
				    $attachments ='';
			//printr($attachments);die;
				//$attachments = array_merge($url_dieline,$url_artwork);
	    
		$getData = ' mcoi.shipping_address_id,mcoi.admin_user_id,mcoi.billing_address_id,custom_order_id,mco.layer, mco.added_by_user_id, mco.added_by_user_type_id, customer_name, customer_gress_percentage,  shipment_country_id, multi_custom_order_number,mco.multi_custom_order_id, custom_order_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,custom_order_type,layer,added_by_country_id, currency, currency_id, currency_price, mco.date_added, cylinder_price,tool_price, mcoi.email as customer_email,mco.status,discount,customer_gress_percentage,gress_percentage,gress_air,gress_sea,quantity_type ';
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
	   /*if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
	        printr($quantityData);die;*/
	        
	    $str=$dat['product_name'];
        if($dat['product_id'] == '6')
        {
            $str=$dat['product_name'];
            $dat['product_name']='To Be Supplied in Rolls';
           
        }
		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$str),0,3));		
		$sub =$dat['multi_custom_order_number'] .' - '.ucwords($dat['customer_name']).' - custom printed '.$first;
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
						$m_k[$arr[0]['custom_order_quantity_id']]='';
				
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
				
				foreach($new_data as $key=>$value)
				{
					//printr($qty_data);die;
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
						//printr($size);
					foreach($qty_data as $qty=>$transport)
					{//printr($transport);die;
					    (int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{ 	
						    $instruction = '';
							if($records[0]['product_instruction']!='')
							    $instruction = '<b>Special Instruction : </b>'.preg_replace( "/\r|\n/", " ", $records[0]['product_instruction']);
						
						    /*if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
	                         printr($records);die;*/
								if($k == "air") 
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
							if($selCurrency)
							{
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$cylinder_price = $cylinder_base_price;	
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
									}
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
						//	echo $cr['user'];
							if($cr['user']==0 || $addedByInfo['country_id']==155)
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
									
									if($k=='air')
									{
										$gp=$records[0]['gress_air'];
										$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'];
									}
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
									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate($newPircegress ,"3").' per 1 '.$bag_type.' '.$taxvaluegress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span><br><span> &nbsp;&nbsp;&nbsp;&nbsp;'.$instruction.'</span>'.$client_order_qty.'</td></tr>';	
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										if($dat['product_id'] != '10')
											$bag= 'plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
										else
											$bag='';
										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b></td><td>'.$dat['currency'].' '.$newPircegress.' per 1 '.$bag_type.' '.$taxvaluedisgress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';
									}
								}	
							}
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							//echo '((('.$records[0]['totalPrice'].' +'. $records[0]['customerGressPrice'].') / '.(float)$dat['currency_price'].') / '.(float)$cr['currency_rate'] .')';
							$newPirce = $newPirce/$qty;
							$taxvalue='';
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
									$bag= 'plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
								else
									$bag='';
							if($k=='air')
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
							
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate($newPirce ,"3").' per 1 '.$bag_type.' '.$taxvalue.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span><br><span> &nbsp;&nbsp;&nbsp;&nbsp;'.$instruction.'</span>'.$client_order_qty.'</td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag= 'plus or minus '.$plus_minus_quantity.' '.$bag_types.'';
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b>'.$dat['currency'].' '.$newPirce.' per 1 '.$bag_type.' '.$taxvaluedis.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
							}							
						}
						
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Make up of pouch  : </b> Custom Printed '.$dat['product_name'].' ';
					if($dat['product_id'] != '10')
						$m.=str_replace('<br>',' ',$records[0]['email_text']);
					$m.='</td></tr>';
					}
					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Size : </b>'.$size.'</td></tr> ';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Material : </b>';
					$materialData = $this->getCustomOrderMaterial($records[0]['materialData'][0]['custom_order_id']);
					if(isset($materialData) && !empty($materialData))
					{
						$materialStr = '';
						for($gi=0;$gi<count($materialData);$gi++)
						{
							$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
						}
						$html .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html .= '</tr>';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Shipment Country : </b>'.$shippingCountry['country_name'].'</td></tr>';
					$html .=$m;
					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Printing Effect : </b>'.$records[0]['printing_effect'].'</td></tr>';
					if($addedByInfo['country_id']==155)
						$txt='[Your Client Price]';
					else
						$txt='';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html .=$pmq;
					$html .=$gress;
					$html .=$pmq_gress;
					if($dat['product_id'] != '10')
					{
						$html .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($cylinder_price),"3").'</td></tr>';
					}
					if(isset($tool_price) && $tool_price>0.000)
					{
						$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Extra Tool price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($tool_price),"3").'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</td></tr>';
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;	//printr($html);die;
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html .= '</table>';
				$html .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp; Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
				
			    
		    if(!empty($shipping_address)){
    				$html .= '<br><div><b>Shipping Address ( Delivery Address ) : </b><br>'.nl2br($shipping_address['address']).'<br>'.$shipping_address['state'].'<br>';
                    				if($shipping_address['postcode']!=''){ 
                    					$html .= 'Postcode'.$shipping_address['postcode'].'';
                    				}
                    				$html .= $data[0]['country_name'];
                    				if($data[0]['contact_number']!=''){ 
                    					$html .= '<br>Contact no'.$data[0]['contact_number'];
                    					}
    				$html .= '</div>';
	    	}
			$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
        	if(!empty($billing_address)){
				$html .= '<div><b>Billing Address ( Buyer Address ) : </b><br> '.nl2br($billing_address['address']);//.'<br>'.$billing_address['city'].','.$billing_address['state'].'<br> '
            				if($billing_address['postcode']!=''){ 
            					    $html .= '<br>Postcode'.$billing_address['postcode'];
            				}
				$html .= '</div>';
        	}
				    
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
				            
				//printr($add_email_ankit_sir);
				 if($data[0]['admin_user_id']=='44' || $data[0]['admin_user_id']=='24' || $data[0]['admin_user_id']=='33')
				 {
    				 $add_email_ankit_sir = $this->getUser(96,2);           
    				      $email_temp[]=array('html'=>$html,'email'=>$add_email_ankit_sir['email']);
				 }
				 $add_email_prashant = $this->getUser(144,2);
				     $email_temp[]=array('html'=>$html,'email'=>$add_email_prashant['email']);
				 $add_email_pratikbhai = $this->getUser(19,2);
				     $email_temp[]=array('html'=>$html,'email'=>$add_email_pratikbhai['email']);
			 	$add_email_msn = $this->getUser(237,2);
				     $email_temp[]=array('html'=>$html,'email'=>$add_email_msn['email']);
				        
				
				$html='<table border="0px">';
			}
		
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
				
			/*	if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1'){
    			    //printr($message);
    			    //printr($val['email']);die;
    			    send_email_new($val['email'],$formEmail,$subject,$message,$attachments,'','1');die;
				}
    			else*/
    			    send_email($val['email'],$formEmail,$subject,$message,$attachments,'','1');
					
			}		
		}
		/*if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
		   die;*/
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
		//die;
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
        $sql = "SELECT * FROM multi_product_quotation_id WHERE multi_product_quotation_id='".$id."'";
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
	//	printr($custom_order_id);
		$sql = "SELECT mco.product_note, mco.product_instruction, mcod.name, mcod.order_product_die_line_id FROM " . DB_PREFIX ." multi_custom_order as mco, multi_custom_order_product_die_line as mcod WHERE mco.multi_custom_order_id='".$custom_order_id."' AND mco.custom_order_id=mcod.order_product_id ";
		$data = $this->query($sql);
        //echo $sql;
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

	public function viewCustomOrder($custom_order_id,$currency=0,$currency_price=0){
	    
	  
	   
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

		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE is_delete=0 AND status=1 AND product_code LIKE 'CUST%'";

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
	public function uploadDieline($post,$file,$n=0)
	{
		$product=array();
		foreach($file['name'] as $key=>$fname)
		{
		    $ext = pathinfo($fname, PATHINFO_EXTENSION);
    		if($n==1)
    		{
        		$product[] = array(
    				'die_name' => $fname,
    				'die_ext' => $ext
    			);
    		}
    		if($n==2)
    		{
    		    $product[] = array(
    				'image_name' => $fname,
    				'image_ext' => $ext
    			);
    		}
			$data = array(
				'name' => $fname,
				'size' => $file['size'][$key]
			);
			$file_name = $fname;
    		$filetemp = $file['tmp_name'][$key];
    		$validateImageExt = validateUploadImage($data);	
    		if($ext == 'pdf'){
    			if($n==1)
    			    $upload_path = DIR_UPLOAD.'admin/pdfdieline/';
    			if($n==2)
    			    $upload_path = DIR_UPLOAD.'admin/pdfartwork/';
    			    
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
    			if($n==1)
    			    $upload_path = DIR_UPLOAD.'admin/dieline/';				
    			if($n==2)
    			    $upload_path = DIR_UPLOAD.'admin/artwork/';
    			    
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
    	if($n==1)//die-line
	        $this->insertDieLine($product,$post['custom_order_id']);
	    if($n==2)//art-work
	        $this->insertImages($product,$post['custom_order_id']);
	
	}
	public function getproductCode($product_code_id)
	{
		$sql = "SELECT product_code FROM " . DB_PREFIX ."product_code WHERE product_code_id = '".$product_code_id."'";
	    $data = $this->query($sql);
		return $data->row;
	}
}
?>