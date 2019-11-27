<?php
class product_code extends dbclass{
	
	// [kinjal] --> 
	public function addProductCode($data,$product_code_image){
			// mansi 8-2-2016 (change for add "CUST" in product code not in swisspac condition)
		
		$product_code=$data['product_code'];
		$width=$gusset=$height='0';
		if($_SESSION['ADMIN_LOGIN_SWISS'] !='1' AND $_SESSION['LOGIN_USER_TYPE'] !='1'){ 
			$product_code=$data['CUST'].$data['product_code']; 
			 $data['box_no'] ='';
			 $width =$data['width']; 
			 $gusset =$data['gusset']; 
			 $height =$data['height'];
		 }
		 $acc_sec='';
		if(isset($data['accessorie'][1]) && !empty($data['accessorie'][1]))
		{
		    //$acc_sec = implode(',',$data['accessorie']);
		    $acc_sec = $data['accessorie'][1];
		}
		$sql = "INSERT INTO `" . DB_PREFIX . "product_code` SET product_code = '".$product_code."', description = '".$data['description']."', product = '".$data['product']."', valve = '".$data['valve']."',zipper='".$data['zipper']."', spout='".$data['spout']."', accessorie='".$data['accessorie'][0]."',accessorie_second='".$acc_sec."', make_pouch='".$data['make']."',color='".$data['color']."', volume='".$data['volume']."', measurement='".$data['measurement']."',box_no = '".$data['box_no']."' , status = '" .(int)$data['status']. "', date_added = NOW(),width = '".$width."',height = '".$height."',gusset = '".$gusset."',product_code_image='".$product_code_image."',product_quotation_price_id='".$data['product_quotation_price_id']."',quotation_no='".$data['quotation_no']."'";
	/*	if($_SESSION['ADMIN_LOGIN_SWISS'] =='1' AND $_SESSION['LOGIN_USER_TYPE'] =='1')
	    	printr($sql);die;*/
		//echo $sql;
		$this->query($sql);
		return $this->getLastId();
	}
	public function updateProductCode($product_code_id,$data,$product_code_image){
		// mansi 8-2-2016 (change for add "CUST" in product code not in swisspac condition)
		$product_code=$data['product_code'];
		$width=$gusset=$height='0';
		if($_SESSION['ADMIN_LOGIN_SWISS'] !='1' AND $_SESSION['LOGIN_USER_TYPE'] !='1'){
			$product_code=$data['CUST'].$data['product_code']; 
			 $data['box_no'] ='';
			 $width =$data['width']; 
			 $gusset =$data['gusset']; 
			 $height =$data['height'];
		 }
		$acc_sec='';
		if(isset($data['accessorie'][1]) && !empty($data['accessorie'][1]))
		{
		    //$acc_sec = implode(',',$data['accessorie']);
		    $acc_sec = $data['accessorie'][1];
		}			 
		$sql = "UPDATE `" . DB_PREFIX . "product_code` SET product_code = '".$product_code."', description = '".$data['description']."', product = '".$data['product']."', valve = '".$data['valve']."',zipper='".$data['zipper']."', spout='".$data['spout']."', accessorie='".$data['accessorie'][0]."',accessorie_second='".$acc_sec."', make_pouch='".$data['make']."', color='".$data['color']."', volume='".$data['volume']."', measurement='".$data['measurement']."', box_no = '".$data['box_no']."' , status = '" .(int)$data['status']. "', date_modify = NOW(),width = '".$width."',height = '".$height."',gusset = '".$gusset."',product_code_image='".$product_code_image."',product_quotation_price_id='".$data['product_quotation_price_id']."',quotation_no='".$data['quotation_no']."' WHERE product_code_id = '" .(int)$product_code_id. "'";
		$this->query($sql);
		//die;
	}
	
	public function getActiveProduct()
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
	
	public function getProductName($product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE product_id = '".$product_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getActiveProductZippers()
	{
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductSpout()
	{
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductAccessorie()
	{
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY serial_no ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveMake()
	{
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
	
	public function getColor()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getColorName($color_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE pouch_color_id='".$color_id."' AND is_delete = '0' ";
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
	
	public function getTotalProductCode($sort_by_product,$filter_data=array()){
		//printr($data['sort_by']);//die;
		//$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "product_code as p WHERE p.is_delete = '0'";
		$sql = "SELECT COUNT(*) as total,p.*,t.measurement ,sp.spout_name,pm.make_name,pz.zipper_name,product.product_name,pa.product_accessorie_name FROM product_code as p ,template_measurement as t,product_spout as sp,product_make as pm,product_zipper as pz ,product as product, product_accessorie as pa  WHERE p.is_delete = '0' AND p.measurement=t.product_id AND sp.product_spout_id=p.spout AND pm.make_id=p.make_pouch  AND pz.product_zipper_id=p.zipper AND product.product_id=p.product AND pa.product_accessorie_id=p.accessorie ";
	
		if(!empty($filter_data)){
			
			if(!empty($filter_data['product_code'])){
				$sql .= " AND p.product_code LIKE '%".addslashes($filter_data['product_code'])."%' ";
			}
			
			if(!empty($filter_data['volume'])){
				$volume=explode(' ',$filter_data['volume']);
				//printr($volume);
				//die;
				$sql .= " AND p.volume  LIKE '%".$volume[0]."%'";
			}
			
			if(!empty($filter_data['product'])){
				$sql .= " AND p.product = '".$filter_data['product']."' ";
			}
			
			if($filter_data['color'] != ''){
				$sql .= " AND p.color = '".$filter_data['color']."' ";
			}	
			
			if($filter_data['box_no'] != ''){
				$sql .= " AND p.box_no = '".$filter_data['box_no']."' ";
			}			
		}
		
		if ($sort_by_product!='') {
			$not = '';
			$and_or = 'OR';
			if($sort_by_product != 'CUST')
			{
				$not = 'NOT';
				$and_or = 'AND';
			}				
				$sql .= "AND ( p.product_code ".$not." LIKE 'CUST%' ".$and_or."  p.product_code ".$not." LIKE 'LBL%' ".$and_or."  p.product_code ".$not." LIKE 'CPBB' )";
		} 
	//	echo $sql;	die;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getProductCode($data,$filter_data=array()){
		
		//select * from product_code order by product_code <> "( product_code NOT LIKE 'CUST%' AND product_code NOT LIKE 'LBL%' AND product_code NOT LIKE 'CPBB' )", "( product_code LIKE 'CUST%' OR product_code LIKE 'LBL%' OR product_code LIKE 'CPBB' )"
		//SELECT p.*,t.measurement ,sp.spout_name,pm.make_name,pz.zipper_name,product.product_name,pa.product_accessorie_name FROM product_code as p ,template_measurement as t,product_spout as sp,product_make as pm,product_zipper as pz ,product as product, product_accessorie as pa  WHERE p.is_delete = '0' AND p.measurement=t.product_id AND sp.product_spout_id=p.spout AND pm.make_id=p.make_pouch  AND pz.product_zipper_id=p.zipper AND product.product_id=p.product AND pa.product_accessorie_id=p.accessorie
	
		//$sql = "SELECT p.*,t.measurement  FROM " . DB_PREFIX . "product_code as p,template_measurement as t  WHERE p.is_delete = '0' AND p.measurement=t.product_id";
		
		$sql = "SELECT p.*,t.measurement ,sp.spout_name,pm.make_name,pz.zipper_name,product.product_name,pa.product_accessorie_name FROM product_code as p ,template_measurement as t,product_spout as sp,product_make as pm,product_zipper as pz ,product as product, product_accessorie as pa  WHERE p.is_delete = '0' AND p.measurement=t.product_id AND sp.product_spout_id=p.spout AND pm.make_id=p.make_pouch  AND pz.product_zipper_id=p.zipper AND product.product_id=p.product AND pa.product_accessorie_id=p.accessorie ";
	
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['product_code'])){
				$sql .= " AND p.product_code LIKE '%".addslashes($filter_data['product_code'])."%' ";
			}
			
			if(!empty($filter_data['volume'])){
				$volume=explode(' ',$filter_data['volume']);
				//printr(sizeof($volume));
				$sql .= " AND p.volume LIKE '%".$volume[0]."%'";
				if(sizeof($volume)> 1)
				$sql .= " AND t.measurement LIKE '%".$volume[1]."%' ";
			}
			if(!empty($filter_data['product'])){
				$sql .= " AND p.product = '".$filter_data['product']."' ";
			}
			
			if($filter_data['color'] != ''){
				$sql .= " AND p.color = '".$filter_data['color']."' ";
			}
			
			if($filter_data['box_no'] != ''){
				$sql .= " AND p.box_no = '".$filter_data['box_no']."' ";
			}				
		}
		
		if (isset($data['sort_by']) && $data['sort_by']!='') {
			$not = '';
			$and_or = 'OR';
			if($data['sort_by'] != 'CUST')
			{
				$not = 'NOT';
				$and_or = 'AND';
			}	
				$sql .= " AND ( p.product_code ".$not." LIKE 'CUST%' ".$and_or."  p.product_code ".$not." LIKE 'LBL%' ".$and_or."  p.product_code ".$not." LIKE 'CPBB' )";
					
		} 
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY p.product_code_id";	
		}

		if (isset($data['order'])) 
		{ //&& ($data['order'] == 'ASC')
			if($data['order'] == 'ASC')
				$sql .= "<>( p.product_code NOT LIKE 'CUST%' AND p.product_code NOT LIKE 'LBL%' AND p.product_code NOT LIKE 'CPBB' ) ,( p.product_code LIKE 'CUST%' OR p.product_code LIKE 'LBL%' OR p.product_code LIKE 'CPBB' )" ;	
			else
				$sql .= "<>( p.product_code LIKE 'CUST%' OR p.product_code LIKE 'LBL%' OR p.product_code LIKE 'CPBB' ) ,( p.product_code NOT LIKE 'CUST%' AND p.product_code NOT LIKE 'LBL%' AND p.product_code NOT LIKE 'CPBB' )" ;
			
			$sql .= ",p.product_code ".$data['order']." ";
		}
		else 
		{
			$sql .= " DESC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

	
			if($data['limit']=='all')
			{
				$sql .= '';
			}
			else
			{
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
			
		}
	//	echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getProductCodeData($product_code_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "product_code  WHERE product_code_id = '" .(int)$product_code_id. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "product_code` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_code_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "product_code` SET is_delete = '1', date_modify = NOW() WHERE product_code_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateProductStatus($product_code_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "product_code` SET status = '" .(int)$status_value ."' WHERE product_code_id = '".$product_code_id ."' ";
	   $this->query($sql);	
	}
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, co.country_id, co.country_name, e.first_name, e.last_name, e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,ib.discount, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			return false;
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row;
		}


		public function code_stock($data)
		{
		$sql = "INSERT INTO productcode_stockmanagement  SET order_no = '".$data['orderno']."',proforma_no = '".$data['proforma_no']."',invoice_no = '".$data['invoice_no']."',product_code_id = '".$data['product_code_id']."',product_code = '".$data['product_code']."',qty = '".$data['qty']."',price = '',dispatch_qty = '',description = '".$data['description']."',company_name = '".$data['company_name']."',  status = '" .(int)$data['status']. "',is_delete='0', date_added = NOW(),date_modify=NOW(),user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' ";
		//echo $sql;
		$data = $this->query($sql);
		//return $post->row;
		}
		
		public function getView($product_id)
		{
			$sql = "SELECT * FROM `productcode_stockmanagement` WHERE product_code_id = '".$product_id."'";
			$data = $this->query($sql);
			
			return $data->rows;
		}
		
		public function getTotal($product_id)
		{
			//$sql = " SELECT  total_qty.* FROM productcode_stockmanagement as ps (SELECT sum(qty) as total_d_qty FROM productcode_stockmanagement WHERE description='3' AND status='1') as d_qty";( sum(qty) as total_s_qty -sum(qty) as total_d_qty ) as total_qty
			$sql = " SELECT * FROM 
			(SELECT sum(ps.qty) as store_qty FROM productcode_stockmanagement as ps WHERE ps.description !='3' AND ps.status='0' AND ps.product_code_id ='".$product_id."') as total_s_qty,
			(SELECT sum(ps.qty) as dis_qty FROM productcode_stockmanagement as ps WHERE ps.description ='3' AND ps.status='1' AND ps.product_code_id ='".$product_id."') as total_d_qty  ";
						
			 
			//echo $sql;
			$data = $this->query($sql);
			
			return $data->row;
		}
		public function cloneData($post)
		{
			//printr($post);die;
			$product_id = $post['product'];
			
			$volume = $post['volume_from'];
			$mea = $post['measurement_from'];
			
			$volume_1 = $post['volume_to'];
			$mea_1 = $post['measurement_to'];
			$mea_sec = $this->getMeasurementName($mea_1);
			
			$sql = "SELECT p.*,t.measurement FROM " . DB_PREFIX . "product_code as p,template_measurement as t WHERE p.volume=".$volume." AND p.measurement=".$mea." AND p.product=".$product_id." AND p.is_delete=0  AND p.measurement=t.product_id";
			$data = $this->query($sql);
			if($data->num_rows){
				foreach($data->rows as $cData)
				{
						$product_code = $cData['product_code'];
						$pro_desc =  $cData['description'];
						$pro_mea = $cData['measurement'];
						
						//$a = substr($pro_mea, 0, 1);
						//preg_match_all('/\d+/', $product_code, $matches);
						//echo $match[0];
						
						$result = str_replace($volume, $volume_1, $product_code);
						$result_desc = str_replace($volume, $volume_1, $pro_desc);
						
						//preg_match_all('/(?<='.$num.')\S+/i', $pro_desc, $matches);
						//printr($match); 
						
						$mea_desc = str_replace($pro_mea, $mea_sec['measurement'], $result_desc);
						
						$check_sql = "SELECT * FROM  `product_code` WHERE  is_delete=0 AND product_code='".$result."'";
						$data_1 = $this->query($check_sql);
						//printr($data_1);
						if(!$data_1->num_rows)
						{
							$sql_insert = "INSERT INTO `" . DB_PREFIX . "product_code` SET product_code = '".$result."', description = '".$mea_desc."', product = '".$product_id."', valve = '".$cData['valve']."',zipper='".$cData['zipper']."', spout='".$cData['spout']."', accessorie='".$cData['accessorie']."', make_pouch='".$cData['make_pouch']."',color='".$cData['color']."', volume='".$volume_1."', measurement='".$mea_1."',box_no = '".$cData['box_no']."' , status = '" .(int)$cData['status']. "', date_added = NOW()";
							$data_insert = $this->query($sql_insert);
							
						}
				}
				
			}else{
				return false;
			}
			
		}
		public function getproductcode_values($product_code_id)
		{
			//$sql="SELECT pc.*,p.product_name FROM product_code as pc,product as p where pc.product=p.product_id and pc.is_delete=0 and pc.product_code_id='".$product_code_id."'";
			$sql = "SELECT pc.*,p.product_name,pm.make_name,c.color,tm.measurement,pz.zipper_name,ps.spout_name,pa.product_accessorie_name,sm.width as w,sm.Height as h,sm.gusset as g,p.product_name_spanish,pm.make_name_spanish,c.color_spanish,pz.zipper_name_spanish,ps.spout_name_spanish,pa.product_accessorie_name_spanish FROM `" . DB_PREFIX . "product_code` AS pc LEFT JOIN product AS p ON pc.product=p.product_id LEFT JOIN product_make pm ON pc.make_pouch=pm.make_id LEFT JOIN pouch_color AS c ON c.pouch_color_id=pc.color LEFT JOIN template_measurement AS tm ON pc.measurement=tm.product_id  LEFT JOIN product_zipper AS pz ON pc.zipper=pz.product_zipper_id LEFT JOIN product_spout AS ps ON ps.product_spout_id=pc.spout LEFT JOIN product_accessorie AS pa ON pa.product_accessorie_id=pc.accessorie LEFT JOIN size_master AS sm ON sm.product_id = pc.product AND sm.product_zipper_id=pc.zipper AND sm.volume=CONCAT(pc.volume,' ',tm.measurement) WHERE pc.product_code_id='".$product_code_id."' AND pc.is_delete=0 AND pc.status=1";
        
			$data=$this->query($sql);//printr($sql);
			if($data->num_rows)
				return $data->row;
			else
				return false;
		
		}
		
	public function productArrayForCSV($product_codes)
	{	
	$i=0;
		
		
		foreach($product_codes as $product_code)
		{
		
			$product=$this->getproductcode_values($product_code);
			
			$input_array[$i++] = 
									Array('product_code'=>$product['product_code'],
									    'productName'=>$product['product_name'],
										'Description'=>$product['description'],
										'dimension'=>$product['w'].'mm X '.$product['h'].'mm X '.$product['g'].'mm',
									); 
				//}
			
			//} 
			//$in_no=0;
			/*foreach($input_array as $key=>$in_arr)
			{
				$input_array[$key]['TaxTotal']= $tot_tax[$input_array[$key]['*InvoiceNumber']];
				$input_array[$key]['Total']= $subtotal[$input_array[$key]['*InvoiceNumber']]+$tot_tax[$input_array[$key]['*InvoiceNumber']];
				$input_array[$key]['InvoiceAmountDue']= $subtotal[$input_array[$key]['*InvoiceNumber']]+$tot_tax[$input_array[$key]['*InvoiceNumber']];
			}*/
		}
		//printr($input_array);die;
		return $input_array;
	}
		
	public function getQuotationProductDetail($product_quotation_price_id){
		//printr($product_quotation_price_id);
		
		$sql="SELECT pz.product_zipper_id,valve_txt,pa.product_accessorie_id,sp.product_spout_id,pro.height,(pro.product_id) as product ,pro.width,pro.gusset,m.make_pouch FROM `multi_product_quotation_price` as m ,multi_product_quotation as pro, product_zipper as pz,product_spout as sp,product_accessorie as pa WHERE pa.product_accessorie_name=m.accessorie_txt AND sp.spout_name=m.spout_txt AND pz.zipper_name=m.zipper_txt AND m.product_quotation_price_id ='".$product_quotation_price_id."' AND m.product_quotation_id=pro.product_quotation_id";		
	//	printr($sql);
		$data=$this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	public function getAccessorie($product_accessorie_id) {

			$sql = "select * from " . DB_PREFIX ."product_accessorie where product_accessorie_id = '".$product_accessorie_id."'";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}

			else {

				return false;

			}

	}
}		
?>
    


