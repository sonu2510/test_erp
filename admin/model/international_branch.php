<?php
class international_branch extends dbclass{
	
	public function addBranch($data){
		//printr($data);
		
		//modified by jayashree
	if(!isset($data['stock_order']) || $data['stock_order']=='') 
		$data['stock_order']='';
	if(!isset($data['multi_quotation_price']) || $data['multi_quotation_price']=='')
		$data['multi_quotation_price']='';
	if(!isset($data['stock_price_compulsory']) || $data['stock_price_compulsory']=='')
		$data['stock_price_compulsory']='';
	$lang=array();
	$digital_quantity=array();
	if(isset($data['lang'])&& !empty($data['lang']))
	{
	    $lang = implode(",",$data['lang']);
	}	
	
	if(isset($data['digital_quantity'])&& !empty($data['digital_quantity']))
	{
	    $digital_quantity = implode(",",$data['digital_quantity']);
	}	
	
		
		$sql = "INSERT INTO " . DB_PREFIX . "international_branch SET company_name='".$data['company_name']."', first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', telephone = '" . $data['telephone'] . "', user_name = '".$data['user_name']."',digital_print_discount='".$data['digital_print_discount']."',foil_plate_price='".$data['foil_plate_price']."',foil_plate_price_swisspac='".$data['foil_plate_price_swisspac']."',digital_convert_rate='".$data['digital_convert_rate']."',color_plate_price='".$data['color_plate_price']."',color_plate_price_swisspac='".$data['color_plate_price_swisspac']."',gres='" . $data['gres'] . "',gres_cyli='" . $data['gres_cyli'] . "',gres_air='" . $data['gres_air'] . "',gres_sea='" . $data['gres_sea'] . "',stock_factory='".$data['stock_factory']."',stock_sea='".$data['stock_sea']."',stock_air='".$data['stock_air']."',stock_discount_air='".$data['stock_discount_air']."',	stock_discount_sea='".$data['stock_discount_sea']."',digital_discount_air='".$data['digital_discount_air']."',	digital_discount_sea='".$data['digital_discount_sea']."',custom_discount_air	='".$data['custom_discount_air']."',custom_discount_sea='".$data['custom_discount_sea']."',valve_price='".$data['valve_price']."',stock_valve_price='".$data['stock_valve_price']."', allow_currency = '".(int)$data['allow_currency']."', ip = '" . $_SERVER['REMOTE_ADDR'] . "', status = '".(int)$data['status']."',email_signature='".$data['email_signature']."',order_flush_date='".$data['order_flush_date']."',order_limit='".$data['order_limit']."',default_curr='".$data['default_curr']."',secondary_currency='".$data['secondary_currency']."',currency_val = '".$data['currval']."',product_rate='".$data['p_rate']."',cylinder_rate='".$data['c_rate']."',tool_rate='".$data['t_rate']."',approved = '1',Multi_Quotation_expiry_days = '".$data['expiry_days']."' ,date_added = NOW(),discount = '".$data['discount']."',gst = '".$data['gst']."',company_address = '".$data['company_address']."',bank_address = '".$data['bank_address']."',
		stock_template_percentage='".$data['stock_template_percentage']."',stock_order_price='".$data['stock_order']."',multi_quotation_price='".$data['multi_quotation_price']."',stock_price_compulsory='".$data['stock_price_compulsory']."',stock_qty_price='".$data['stock_order_qty']."',plastic_scoop_price='".$data['plastic_scoop_price']."',vat_no='".$data['vat_no']."',abn_no='".$data['abn_no']."',termsandconditions_invoice='".$data['termsandconditions_invoice']."',email_confirm = '".(int)$data['email_confirm']."',associate_acnt='".$acnt."',lang_id='".$lang."',default_cyli_base_price='".$data['default_cyli_base_price']."',digital_quantity='".$digital_quantity."',profit_type='".$data['profit_type']."',fedex_account_no='".$data['fedex_account_no']."'";
	
		$this->query($sql);
		$branch_id = $this->getLastId();
		//[kinjal]
		$acnt ='';
		if(isset($data['associate_acnt'])&& !empty($data['associate_acnt']))
		{
			$acnt = implode(",",$data['associate_acnt']);
			foreach($data['associate_acnt'] as $ass)
			{
				$account = explode("=",$ass);
				if($account[0]==2)
				{$s=array();
					//$sel="SELECT * FROM";
					if(in_array($ass,$data['associate_acnt']))
					{
						$s[]= $ass;
						$a = array_diff( $data['associate_acnt'], $s );
						array_push($a,'4='.$branch_id);
						$arr = implode(",",$a);
						$this->query("UPDATE " . DB_PREFIX . "employee SET associate_acnt = '" .$arr. "' WHERE employee_id = '" .$account[1]. "'");
					}
				}
				else if($account[0]==4)
				{  $s='';
					if(in_array($ass,$data['associate_acnt']))
					{
						$s[]= $ass;
						$a = array_diff( $data['associate_acnt'], $s );
						array_push($a,'4='.$branch_id);
						$arr = implode(",",$a);
						//echo "UPDATE " . DB_PREFIX . "international_branch SET associate_acnt = '" .$arr. "' WHERE international_branch_id = '" .$account[1]. "'";
						$this->query("UPDATE " . DB_PREFIX . "international_branch SET associate_acnt = '" .$arr. "' WHERE international_branch_id = '" .$account[1]. "'");
					}
					
				}
				//printr($account);die;
			}
		}
		
		foreach($data['profit_price'] as $transport=>$price_data)
		{
			foreach($price_data as $pkey=>$pval)
			{
				$sql = $this->query("INSERT INTO " . DB_PREFIX . "template_profit_price SET ib_id = '" . (int)$branch_id . "', profit_price = '" .$pval. "', profit_qty_id = '" .$pkey . "', transport = '".$transport."'");		
			}
		}//die;
		
		//[kinjal] 30-1-2018
		foreach($data['gress_quantity'] as $trans=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				$sql = $this->query("INSERT INTO " . DB_PREFIX . "gress_percentage SET ib_id = '" . (int)$branch_id . "', percentage = '" .$val. "', product_quantity = '" .$key . "', transport = '".$trans."'");		
			}
		}
		//end]
		//[kinjal] 16-9-2019
		foreach($data['gress_quantity_roll'] as $trans=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				$sql = $this->query("INSERT INTO " . DB_PREFIX . "gress_percentage SET ib_id = '" . (int)$branch_id . "', percentage = '" .$val. "', product_quantity = '" .$key . "', transport = '".$trans."', type = 'roll'");		
			}
		}
		//[kinjal] 1-10-2019
		foreach($data['gress_quantity_label'] as $trans=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				$sql = $this->query("INSERT INTO " . DB_PREFIX . "gress_percentage SET ib_id = '" . (int)$branch_id . "', percentage = '" .$val. "', product_quantity = '" .$key . "', transport = '".$trans."', type = 'label'");		
			}
		}
		//end
		
		
		//jayashree
		$termsSql = "INSERT INTO ". DB_PREFIX . "termsandconditions SET termsandconditions='".htmlspecialchars($data['termsandconditions'])."', user_id='".(int)$branch_id."', user_type_id='4',status='".(int)$data['status']."',date_added = NOW()";
		$this->query($termsSql);
		
		
		//account master data
		$salt = substr(md5(uniqid(rand(), true)), 0, 9);
		$sql1 = "INSERT INTO `" . DB_PREFIX . "account_master` SET user_type_id = '4', user_id = '" .(int)$branch_id. "', user_name = '" .$data['user_name']. "', salt = '" . $salt . "', password = '" . sha1($salt . sha1($salt . sha1($data['password']))) . "' , password_text = '" .$data['password']. "', email = '" .$data['email']. "', email1 = '" .$data['email1']. "',date_added = NOW(),commission = '" .$data['commission']. "'";
		$this->query($sql1);
		
		//insert address
		$this->query("INSERT INTO " . DB_PREFIX . "address SET user_type_id = '4', user_id = '" . (int)$branch_id . "', first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', company = '', address = '" . $data['address'] . "', city = '" . $data['city'] . "', state = '" . $data['state'] . "', postcode = '" . $data['postcode'] . "', country_id = '" . (int)$data['country_id'] . "'");
		
		$address_id = $this->getLastId();

		$this->query("UPDATE " . DB_PREFIX . "international_branch SET address_id = '" . (int)$address_id . "' WHERE international_branch_id = '" . (int)$branch_id . "'");
		return $branch_id;
	}
	
	public function updateBranch($branch_id,$data){	
	//[kinjal]
	  //  printr($_FILE);
		$acnt =array();
		if(isset($data['associate_acnt'])&& !empty($data['associate_acnt']))
		{
			$acnt = implode(",",$data['associate_acnt']);
			foreach($data['associate_acnt'] as $ass)
			{
				$account = explode("=",$ass);
				if($account[0]==2)
				{$s=array();
					//$sel="SELECT * FROM";
					if(in_array($ass,$data['associate_acnt']))
					{
						$s[]= $ass;
						$a = array_diff( $data['associate_acnt'], $s );
						array_push($a,'4='.$branch_id);
						$arr = implode(",",$a);
						$this->query("UPDATE " . DB_PREFIX . "employee SET associate_acnt = '" .$arr. "' WHERE employee_id = '" .$account[1]. "'");
					}
				}
				else if($account[0]==4)
				{  $s=array();
					if(in_array($ass,$data['associate_acnt']))
					{
						$s[]= $ass;
						$a = array_diff( $data['associate_acnt'], $s );
						array_push($a,'4='.$branch_id);
						$arr = implode(",",$a);
						//echo "UPDATE " . DB_PREFIX . "international_branch SET associate_acnt = '" .$arr. "' WHERE international_branch_id = '" .$account[1]. "'";
						$this->query("UPDATE " . DB_PREFIX . "international_branch SET associate_acnt = '" .$arr. "' WHERE international_branch_id = '" .$account[1]. "'");
					}
					
				}
				//printr($account);die;
			}
		}
		
		
		//ruchi	
		$profit_price = "SELECT price_id FROM template_profit_price WHERE ib_id='".$branch_id."'";
		$resultPrice = $this->query($profit_price);
		$re=$resultPrice->num_rows;
		
		foreach($data['profit_price'] as $transport=>$price_data)
		{
			foreach($price_data as $pkey=>$pval)
			{
				if($re==0)
				{
					$this->query("INSERT INTO " . DB_PREFIX . "template_profit_price SET ib_id = '" . (int)$branch_id . "', profit_price = '" .$pval. "', profit_qty_id = '" .$pkey . "', transport = '".$transport."'");
				}
				else
				{
					$sql = "UPDATE `" . DB_PREFIX . "template_profit_price` SET profit_price = '" .$pval . "', profit_qty_id = '" . $pkey. "', transport = '".$transport."' WHERE ib_id = '".(int)$branch_id."' AND profit_qty_id =  '".$pkey."' AND transport = '".$transport."'";
					$this->query($sql);				
				}
			}
		}
		//end
		
		//[kinjal] 30-1-2018
		$gress_quantity = "SELECT gress_id FROM gress_percentage WHERE ib_id='".$branch_id."' AND type='' ";
		$qty = $this->query($gress_quantity);
		$id=$qty->num_rows;
		foreach($data['gress_quantity'] as $trans=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				if($id==0)
				{
					$sql = $this->query("INSERT INTO " . DB_PREFIX . "gress_percentage SET ib_id = '" . (int)$branch_id . "', percentage = '" .$val. "', product_quantity = '" .$key . "', transport = '".$trans."'");
				}
				else
				{
					$sql = $this->query("UPDATE " . DB_PREFIX . "gress_percentage SET  percentage = '" .$val. "' WHERE ib_id = '" . (int)$branch_id . "' AND product_quantity='" .$key . "' AND transport = '".$trans."' AND type='' ");
				}
			}
		}
		//end
		//[kinjal] 16-9-2019
		$gress_quantity_roll = "SELECT gress_id FROM gress_percentage WHERE ib_id='".$branch_id."' AND type='roll' ";
		$qty_roll = $this->query($gress_quantity_roll);
		$id_roll=$qty_roll->num_rows;
		foreach($data['gress_quantity_roll'] as $trans=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				if($id_roll==0)
				{
					$sql = $this->query("INSERT INTO " . DB_PREFIX . "gress_percentage SET ib_id = '" . (int)$branch_id . "', percentage = '" .$val. "', product_quantity = '" .$key . "', transport = '".$trans."', type='roll'");
				}
				else
				{
					$sql = $this->query("UPDATE " . DB_PREFIX . "gress_percentage SET  percentage = '" .$val. "' WHERE ib_id = '" . (int)$branch_id . "' AND product_quantity='" .$key . "' AND transport = '".$trans."' AND type='roll'");
				}
			}
		}
		//[kinjal] 1-10-2019
		$gress_quantity_label = "SELECT gress_id FROM gress_percentage WHERE ib_id='".$branch_id."' AND type='label' ";
		$qty_label = $this->query($gress_quantity_label);
		$id_label=$qty_label->num_rows;
		foreach($data['gress_quantity_label'] as $trans=>$per_data)
		{
			foreach($per_data as $key=>$val)
			{
				if($id_label==0)
				{
					$sql = $this->query("INSERT INTO " . DB_PREFIX . "gress_percentage SET ib_id = '" . (int)$branch_id . "', percentage = '" .$val. "', product_quantity = '" .$key . "', transport = '".$trans."', type='label'");
				}
				else
				{
					$sql = $this->query("UPDATE " . DB_PREFIX . "gress_percentage SET  percentage = '" .$val. "' WHERE ib_id = '" . (int)$branch_id . "' AND product_quantity='" .$key . "' AND transport = '".$trans."' AND type='label'");
				}
			}
		}
		//end
		$lang=array();
		$digital_quantity=array();
    	if(isset($data['lang'])&& !empty($data['lang']))
    	{
    	    $lang = implode(",",$data['lang']);
    	}
    	if(isset($data['digital_quantity'])&& !empty($data['digital_quantity']))
    	{
    	    $digital_quantity = implode(",",$data['digital_quantity']);
    	}	
	//	printr($data);die; 
		//modified by jayashree		 
		$sql = "UPDATE `" . DB_PREFIX . "international_branch` ib, `" . DB_PREFIX . "account_master` am SET ib.company_name= '".$data['company_name']."' ,ib.first_name = '" . $data['first_name'] . "', ib.last_name = '" . $data['last_name'] . "', ib.telephone = '" . $data['telephone'] . "', ib.user_name = '".$data['user_name']."',stock_qty_price='".$data['stock_order_qty']."', am.user_name = '".$data['user_name']."', am.email = '" . $data['email'] . "', ib.status = '".(int)$data['status']."',digital_print_discount='".$data['digital_print_discount']."',foil_plate_price='".$data['foil_plate_price']."',foil_plate_price_swisspac='".$data['foil_plate_price_swisspac']."',digital_convert_rate='".$data['digital_convert_rate']."',color_plate_price='".$data['color_plate_price']."',color_plate_price_swisspac='".$data['color_plate_price_swisspac']."', ib.gres='" . $data['gres'] . "',ib.gres_cyli='" . $data['gres_cyli'] . "',ib.gres_air='" . $data['gres_air'] . "',ib.gres_sea='" . $data['gres_sea'] . "',ib.stock_factory='".$data['stock_factory']."',ib.stock_sea='".$data['stock_sea']."',ib.stock_air='".$data['stock_air']."',ib.digital_discount_air='".$data['digital_discount_air']."',	ib.digital_discount_sea='".$data['digital_discount_sea']."',ib.stock_discount_air='".$data['stock_discount_air']."',ib.stock_discount_sea='".$data['stock_discount_sea']."',ib.custom_discount_air	='".$data['custom_discount_air']."',ib.custom_discount_sea='".$data['custom_discount_sea']."', ib.valve_price='".$data['valve_price']."', ib.stock_valve_price='".$data['stock_valve_price']."',ib.email_signature='".$data['email_signature']."',ib.order_flush_date='".$data['order_flush_date']."',ib.order_limit='".$data['order_limit']."',ib.default_curr='".$data['default_curr']."',secondary_currency='".$data['secondary_currency']."',ib.currency_val = '".$data['currval']."',ib.product_rate='".$data['p_rate']."',ib.cylinder_rate='".$data['c_rate']."',ib.tool_rate='".$data['t_rate']."',ib.allow_currency = '".(int)$data['allow_currency']."',ib.stock_template_percentage='".$data['stock_template_percentage']."',ib.stock_order_price='".$data['stock_order']."',multi_quotation_price='".$data['multi_quotation_price']."',ib.date_modify = NOW(), am.date_modify = NOW(),discount = '".$data['discount']."',gst = '".$data['gst']."',company_address = '".$data['company_address']."',bank_address = '".$data['bank_address']."',ib.plastic_scoop_price='".$data['plastic_scoop_price']."',ib.vat_no='".$data['vat_no']."',ib.abn_no='".$data['abn_no']."', ib.stock_price_compulsory='".$data['stock_price_compulsory']."',ib.termsandconditions_invoice='".$data['termsandconditions_invoice']."',ib.Multi_Quotation_expiry_days='".$data['expiry_days']."',ib.email_confirm = '".(int)$data['email_confirm']."',associate_acnt='".$acnt."',default_cyli_base_price='".$data['default_cyli_base_price']."',lang_id='".$lang."',commission = '" .$data['commission']. "',digital_quantity='".$digital_quantity."',profit_type='".$data['profit_type']."' ,fedex_account_no='".$data['fedex_account_no']."' WHERE ib.international_branch_id = '".(int)$branch_id."' AND am.user_type_id = '4' AND am.user_id = '".(int)$branch_id."'";
		//echo $sql;die;
		$this->query($sql);				
		$insertTerms = "SELECT termsandconditions_id FROM termsandconditions WHERE user_id='".$branch_id."'";
		$result = $this->query($insertTerms);
		//printr($result);
		//die;
		
		if($result->row['termsandconditions_id']=='')
		{
			$termsSql = "INSERT INTO ". DB_PREFIX . "termsandconditions SET termsandconditions='".htmlspecialchars($data['termsandconditions'])."', user_id='".(int)$branch_id."', user_type_id='4',status='".(int)$data['status']."',date_added = NOW()";
			$this->query($termsSql);
		}
		else
		{
			$termsSql = "UPDATE `" . DB_PREFIX . "termsandconditions` SET termsandconditions ='".htmlspecialchars($data['termsandconditions'])."',date_modify = NOW() WHERE user_id = '".$branch_id."' AND user_type_id = '4' ";
		//echo $termsSql;
		//die;
			$this->query($termsSql);			
		}
		$branch = $this->getBranch($branch_id);
		if (isset($data['password']) && $data['password']!='') {
			$salt = substr(md5(uniqid(rand(), true)), 0, 9);
			$this->query("UPDATE `" . DB_PREFIX . "account_master` SET salt = '" . $salt . "', password = '" . sha1($salt . sha1($salt . sha1($data['password']))) . "' , password_text = '" .$data['password']. "', email = '" .$data['email']. "',  date_modify = NOW() WHERE user_type_id = '4' AND user_id = '" .(int)$branch['international_branch_id']. "' AND user_name = '".$data['user_name']."'");
		}
		
		$this->query("UPDATE " . DB_PREFIX . "account_master SET status='".(int)$data['status']."',email1 = '" .$data['email1']. "' WHERE user_type_id = '4' AND  user_id = '" . (int)$branch['international_branch_id'] . "'");
		//echo "UPDATE `" . DB_PREFIX . "account_master` SET salt = '" . $salt . "', password = '" . sha1($salt . sha1($salt . sha1($data['password']))) . "' , password_text = '" .$data['password']. "', email = '" .$data['email']. "', email1 = '" .$data['email1']. "', date_modify = NOW() WHERE user_type_id = '4' AND user_id = '" .(int)$branch['international_branch_id']. "' AND user_name = '".$data['user_name']."'";
		if($branch['address_id'] > 0){
			$this->query("UPDATE " . DB_PREFIX . "address SET first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', company = '', address = '" . $data['address'] . "', city = '" . $data['city'] . "', postcode = '" . $data['postcode'] . "', state = '" . $data['state'] . "', country_id = '" . (int)$data['country_id'] . "', date_modify = NOW() WHERE user_type_id = '4' AND user_id = '".(int)$branch['international_branch_id']."' AND address_id = '".(int)$branch['address_id']."'");
		}else{
			$this->query("INSERT INTO " . DB_PREFIX . "address SET user_type_id = '4', user_id = '" . (int)$branch['international_branch_id'] . "', first_name = '" . $data['first_name'] . "', last_name = '" . $data['last_name'] . "', company = '', address = '" . $data['address'] . "', city = '" . $data['city'] . "', state = '" . $data['state'] . "', postcode = '" . $data['postcode'] . "', country_id = '" . (int)$data['country_id'] . "'");
			$address_id = $this->getLastId();
			$this->query("UPDATE " . DB_PREFIX . "international_branch SET address_id = '" . (int)$address_id . "' WHERE international_branch_id = '" . (int)$branch['international_branch_id'] . "'");
		}
		//die;
	}
	
	public function getBranch($branch_id){
		$sql = "SELECT *, ib.international_branch_id,ib.first_name as ibfirst_name, ib.last_name as iblast_name,ib.discount,ib.associate_acnt FROM " . DB_PREFIX . "international_branch ib LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id) LEFT JOIN " . DB_PREFIX . "account_master am ON(am.user_name=ib.user_name) WHERE am.user_type_id = '4' AND ib.international_branch_id = '" .(int)$branch_id. "' AND am.user_id =ib.international_branch_id ";
		//echo $sql;die;
		$data = $this->query($sql);
		//printr($data->row);die;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalBranch($filter_array=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id) WHERE ib.is_delete = '0'";
		
		if(!empty($filter_array)){						
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}				
			if($filter_array['status'] != ''){
				$sql .= " AND ib.status = '".$filter_array['status']."' ";
			}			
			if(!empty($filter_array['name'])){
				$sql .= " AND CONCAT(ib.first_name,' ',ib.last_name) LIKE '%".$this->escape($filter_array['name'])."%'";
			}									
		}
		//echo $sql;die;		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getBranchs($data = array(),$filter_array=array()){
		//$sql = "SELECT *,CONCAT(first_name,' ',last_name) as name FROM `" . DB_PREFIX . "client`";		
		$sql = "SELECT *,CONCAT(ib.first_name,' ',ib.last_name) as name,am.email FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id)  WHERE ib.is_delete = '0'";
		//echo $sql;die;		
		if(!empty($filter_array)){			
			if(!empty($filter_array['email'])){
				$sql .= " WHERE am.email LIKE '%".$filter_array['email']."%'";
			}			
			if($filter_array['status'] != ''){
				$sql .= " WHERE ib.status = '".$filter_array['status']."' ";
			}			
			if(!empty($filter_array['name'])){
				$sql .= " HAVING name LIKE '%".$this->escape($filter_array['name'])."%'";
			}							
		}		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY international_branch";	
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
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	//ruchi
	public function getProfitPrice($branchId,$transport)
	{	
		$sql = "SELECT tq.template_quantity_id,tq.quantity,IFNULL(tpp.profit_price,0.00) AS profit_price FROM template_quantity AS tq LEFT JOIN  template_profit_price AS tpp ON(tq.template_quantity_id=tpp.profit_qty_id AND tpp.ib_id='".$branchId."' AND tpp.transport='".$transport."') WHERE tq.status = 1  ORDER BY tq.quantity ASC";
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
	//end
	
	//modified by jayashree
	
	public function getdefaultcurrency()
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
	//end 
	
	public function getStockQty()
	{
		$sql = "SELECT * FROM template_quantity WHERE status = 1 ORDER BY  quantity ASC";
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
	
	public function getdefaultcurrencyCode($default_curr)
	{
		$sql = "SELECT currency_code FROM country where status = 1 and currency_code!='' and country_id = '".$default_curr."' LIMIT 1";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows)
		{
			return $data->row['currency_code']; 
		}
		else
		{
			return false;
		}
	}
	
	public function getCountryName($country_id)
	{
		$sql = "SELECT country_name FROM country where status = 1 and country_code!='' and currency_code!='' and country_id = '".$country_id."' LIMIT 1";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows)
		{
			return $data->row['country_name'];
		}
		else
		{
			return false;
		}		
	}
	
	public function getOrderFlushDate($branch_id)
	{
		$sql = "SELECT order_flush_date FROM international_branch  WHERE international_branch_id = '".$branch_id."'";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows)
		{
			return $data->row['order_flush_date'];
		}
		else
		{
			return false;
		}		
	}
	//upload Logo image 
	public function uploadLogoImage($user_id,$data){
		//printr($data);die;
		//echo "Pending for start new work priority";die;		
		if(isset($data['name']) && $data['name'] != '' && $data['error'] == 0){			
			$validateImageExt = validateUploadImage($data);			
			if($validateImageExt){	
				require_once(DIR_SYSTEM . 'library/resize-class.php');
				$upload_path = DIR_UPLOAD.'admin/logo/';				
				$exist = $this->query("SELECT logo FROM " . DB_PREFIX . "international_branch WHERE international_branch_id = '".(int)$user_id."'");
				if($exist->row['logo'] != '' && file_exists($upload_path.$exist->row['logo'])){
					unlink($upload_path.$exist->row['logo']);
					unlink($upload_path.'50_'.$exist->row['logo']);
					unlink($upload_path.'100_'.$exist->row['logo']);
					unlink($upload_path.'200_'.$exist->row['logo']);
				}				
				$file_name = $data["name"];
				$filetemp = $data["tmp_name"];
				$upload_image_path = $upload_path."/".$file_name;
				
				if(file_exists($upload_image_path)) 
				{
					$file_name = rand().'_'.$file_name;
					
					$widthArray = array(200,100,50); //You can change dimension here.
					foreach($widthArray as $newwidth)
					{
						compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
					}
					
					/*uploadfile($filetemp,$file_name,$upload_path);
					list($width, $height, $type, $attr) = getimagesize($upload_path.$file_name);
					
					if($width >= 36){ $resizeWidth = 36; }else{ $resizeWidth = $width;}
					if($height >= 36){ $resizeHeight = 36; }else{ $resizeHeight = $width;}
					$resizeObj = new resize($upload_path.$file_name);		
					$resizeObj -> resizeImage($resizeWidth, $resizeHeight, 'exact');
					// *** 3) Save image
					$resizeObj -> saveImage($upload_path.'36x36_'.$file_name, 100);
					
					if($width >= 200){ $resizeWidth = 200; }else{ $resizeWidth = $width;}
					if($height >= 200){ $resizeHeight = 200; }else{ $resizeHeight = $width;}
					$resizeObj = new resize($upload_path.$file_name);		
					$resizeObj -> resizeImage($resizeWidth, $resizeHeight, 'exact');
					// *** 3) Save image
					$resizeObj -> saveImage($upload_path.'200x200_'.$file_name, 100);*/
					
				}else{
					
					$widthArray = array(200,100,50); //You can change dimension here.
					foreach($widthArray as $newwidth)
					{
						compressImage($validateImageExt,$filetemp,$upload_path,$file_name,$newwidth);
					}
					
					/*uploadfile($filetemp,$file_name,$upload_path);
					list($width, $height, $type, $attr) = getimagesize($upload_path.$file_name);
					
					if($width >= 36){ $resizeWidth = 36; }else{ $resizeWidth = $width;}
					if($height >= 36){ $resizeHeight = 36; }else{ $resizeHeight = $width;}
					$resizeObj = new resize($upload_path.$file_name);		
					$resizeObj -> resizeImage($resizeWidth, $resizeHeight, 'exact');
					// *** 3) Save image
					$resizeObj -> saveImage($upload_path.'36x36_'.$file_name, 100);
					
					if($width >= 200){ $resizeWidth = 200; }else{ $resizeWidth = $width;}
					if($height >= 200){ $resizeHeight = 200; }else{ $resizeHeight = $width;}
					$resizeObj = new resize($upload_path.$file_name);		
					$resizeObj -> resizeImage($resizeWidth, $resizeHeight, 'exact');
					// *** 3) Save image
					$resizeObj -> saveImage($upload_path.'200x200_'.$file_name, 100);*/
				}				
				if($file_name){
					$this->query("UPDATE " . DB_PREFIX . "international_branch SET logo = '" . $file_name . "' WHERE international_branch_id = '" .(int)$user_id. "'");
				}
			}
		}
	}
	
	public function updateStatus($status,$data){
		//printr($data);die;
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "international_branch` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE international_branch_id IN (" .implode(",",$data). ")";
			$this->query($sql);
			$this->query("UPDATE " . DB_PREFIX . "account_master SET status = '" .(int)$status. "' WHERE user_type_id = '4' AND user_id IN (" .implode(",",$data). ")");
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "international_branch` SET is_delete = '1', date_modify = NOW() WHERE international_branch_id IN (" .implode(",",$data). ")";
			$this->query($sql);
			//$sel_delete = "DELETE FROM account_master WHERE user_type_id = '4' AND user_id IN (" .implode(",",$data). ")";
			$sel_delete = "UPDATE `" . DB_PREFIX . "account_master` SET add_permission='', edit_permission='', delete_permission='', view_permission='' WHERE user_type_id = '4' AND user_id IN (" .implode(",",$data). ")";
			$this->query($sel_delete);
			
			$sql_select="SELECT * FROM employee WHERE user_type_id='4' AND user_id IN (" .implode(",",$data). ")";
			$select=$this->query($sql_select);
 
 			if($select->num_rows){
				foreach($select->rows as $row)
				{
					$sel_update = "UPDATE `" . DB_PREFIX . "account_master` SET add_permission='', edit_permission='', delete_permission='', view_permission='' WHERE user_type_id = '2' AND user_id=".$row['employee_id']."";
					$this->query($sel_update);
				}
			}
						
			
		}
	}
	
	public function getTerms($branch_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "termsandconditions WHERE user_id = '".$branch_id."' AND user_type_id = '4' AND is_delete = '0' ";
		//echo $sql;die;
		$data = $this->query($sql);
		//printr($data->row);die;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	
	public function getUserList($user_id=''){
			$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master WHERE  user_id!='".$user_id."' ORDER BY user_name ASC";
			$data = $this->query($sql);
		//printr($data);die;
			return $data->rows;
	}
	//[kinjal] 30-1-2018
	/*public function getQuantity(){
		$data = $this->query("SELECT product_quantity_id,quantity FROM " . DB_PREFIX ."product_quantity WHERE status = '1' AND is_delete = '0'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}*/
	//[kinjal] 16-9-2019 //commented the previous fun. too
	public function getQuantity($type='',$quantity_type=''){
		if($type == 'p'){
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."product_quantity WHERE status = '1' AND is_delete = '0'");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}else if($type == 'label'){
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."label_quantity WHERE status = '1' AND is_delete = '0'");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		} else{
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."roll_quantity WHERE quantity_type = '".$quantity_type."' AND status = '1' AND is_delete = '0' ORDER BY quantity ASC");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}
	}
	public function getGressPer($ib_id,$trans_type,$type=''){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."gress_percentage WHERE ib_id = '".$ib_id."' AND transport='".$trans_type."' AND type='".$type."' ");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	    //kinjal made on[4-12-2018]
	public function getLanguage(){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."language_master WHERE is_delete = '0'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}	
	public function getDigitalQuantity(){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."digital_quantity WHERE is_delete = '0' AND status=1");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	//end
}
?>