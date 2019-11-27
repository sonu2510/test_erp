<?php
class pro_invoice extends dbclass{

		function convert_number($number) 
		{ 
    		if (($number < 0) || ($number > 999999999)) 
    		{ 
    			throw new Exception("Number is out of range");
    		} 
		    $Gn = floor($number / 1000000);  /* Millions (giga) */ 
    		$number -= $Gn * 1000000; 
    		$kn = floor($number / 1000);     /* Thousands (kilo) */ 
    		$number -= $kn * 1000; 
    		$Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    		$number -= $Hn * 100; 
    		$Dn = floor($number / 10);       /* Tens (deca) */ 
    		$n = $number % 10;               /* Ones */ 
    		$res = ""; 
		    if ($Gn) 
    		{ 
        		$res .= $this->convert_number($Gn) . " Million"; 
    		} 
		    if ($kn) 
    		{ 
        		$res .= (empty($res) ? "" : " ") . 
           		$this->convert_number($kn) . " Thousand"; 
    		} 
		    if ($Hn) 
    		{ 
        		$res .= (empty($res) ? "" : " ") . 
            	$this->convert_number($Hn) . " Hundred"; 
    		} 
		    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        	"Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        	"Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        	"Nineteen"); 
    		$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        	"Seventy", "Eigthy", "Ninety"); 
		    if ($Dn || $n) 
    		{ 
        		if (!empty($res)) 
        		{ 
            		$res .= " and "; 
        		} 
		        if ($Dn < 2) 
       			{ 
            		$res .= $ones[$Dn * 10 + $n]; 
        		} 
        		else 
        		{ 
            		$res .= $tens[$Dn]; 
	            	if ($n) 
            		{ 
                		$res .= "-" . $ones[$n]; 
            		} 
        		} 
    		} 
		    if (empty($res)) 
    		{ 
        		$res = "zero"; 
    		} 
		    return $res; 
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
			$sql = "SELECT pouch_color_id,color,pouch_color_abbr,color_value,email_color,color_category,status FROM `" . DB_PREFIX . "pouch_color` WHERE status='1' AND is_delete = '0' ORDER BY color  ASC";
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
		
		//modify [kinjal]: (1-2-2016) in employee cond 
		public function getUser($user_id,$user_type_id)
		{	//echo $user_type_id;
			if($user_type_id == 1){
				$sql = "SELECT ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
				//echo $sql;
			}elseif($user_type_id == 2){
				$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
				
			}elseif($user_type_id == 4){
				$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			}else{
				
				$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
			}
			//echo $sql;
			$data = $this->query($sql);
			return $data->row;
		}
		public function getCurrency()
		{
			$sql = "SELECT *  FROM " . DB_PREFIX . "currency  WHERE is_delete = '0' ";	
			$data = $this->query($sql);
			$result = $data->rows;
			return $result;	
		}
		public function getCurrencyId($cuurr_id)
		{
			$sql = "SELECT currency_code  FROM " . DB_PREFIX . "currency  WHERE currency_id = '".$cuurr_id."' ";	
			$data = $this->query($sql);
			$result = $data->row;
			return $result;	
		}
		public function getCountry($con_id)
		{
			$sql = "SELECT country_name  FROM " . DB_PREFIX . "country  WHERE country_id = '".$con_id."'";	
			$data = $this->query($sql);
			$result = $data->row;
			return $result;	
		}
		public function getActiveProductAccessorie(){
			$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
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
		public function checkProductZipper($product_id){
			$data = $this->query("SELECT zipper_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
			if($data->num_rows){
				return $data->row['zipper_available'];	
			}else{
				return false;
			}
		}
		
		public function checkProductTintie($product_id){
			$data = $this->query("SELECT tintie_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
			if($data->num_rows){
				return $data->row['tintie_available'];	
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
		public function checkProductGusset($product_id){
			$data = $this->query("SELECT gusset_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
			if($data->num_rows){
				return $data->row['gusset_available'];	
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
		public function ProductSize($size_master_id)
		{
			$sql  = "SELECT * FROM size_master WHERE size_master_id = '".$size_master_id."' "; 
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
		public function addProformaNew($post=array()) {
		/*printr($post);
		die;*/
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$product_name = $this->getProduct($post['product']);
			$size = decode($post['size']);
			if($size != 0 || $size !='') {			
				$getMasterSize = $this->ProductSize(decode($post['size']));
				$height = $getMasterSize['height'];
				$width = $getMasterSize['width'];
				$gusset = $getMasterSize['gusset'];
				$volume = $getMasterSize['volume'];
			} else {
				$height = $post['height'];
				$width = $post['width'];
				$gusset = $post['gusset'];
				$volume = '';
			}
			if(isset($post['proforma_id'])) {
				$proforma_invoice_id['proforma_id'] = $post['proforma_id'];
			} 
			else 
			{
				$userCountry = $this->getUserCountry($user_type_id,$user_id);
				if($userCountry){
					$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
				}else{
					$countryCode='IN';
				}
				$pi = 'PI-';
				$new_pro_in_no = $this->generateProformaNumber();
				$pro_in_no = $pi.$countryCode.$new_pro_in_no;
				$taxation='';
				$tax_data='';
				$tax_name='';
				$tax_mode='';
				$final_tax_nm='';
				$excies_per=0;
				$taxation_per=0;
				$taxation=0;
				//modify [kinjal]: (1-2-2016) for singapore cond
				$gst_tax = '0';
				//$tin_no = '';
				$packing_charges='0';
				if(isset($post['country_id']) && !empty($post['country_id']) && $post['country_id'] ==111)
				{	
					$tax_mode=$post['taxation'];
					$packing_charges=$post['packing'];
					//$tin_no = $post['tin_no'];
					if($tax_mode=='form')
					{	
						$val=$post['form'];
						foreach($val as $taxval)
						{	
							$final_tax_nm.= $taxval.',';
							$tax_name.=' tax_name="'.$taxval.'" OR ';
						}
						$final_tax_nm=substr($final_tax_nm,0,-1);
						$tax_name=substr($tax_name,0,-3);
						if(count($val)>1)
						{
							if(in_array('H Form',$val))
							{
								$tax_name='';
							}
						}
					}
					else
					{
						$tax_name.=' tax_name="'.$post['taxation'].'"';
						$final_tax_nm=$post['taxation'];
					}
					if($post['nrm_tax']!='sez_no_tax')
					{
						if($tax_name!='')
						{
							$taxation= $post['taxation'];
							$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id FROM " . DB_PREFIX . "taxation WHERE 
							status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
							$data_tax = $this->query($sql);
							$tax_data=$data_tax->row;
						}
					}
					if(isset($tax_data) && !empty($tax_data))
					{ 
						$taxation=$post['nrm_tax'];
						$excies_per=$tax_data['excies'];
						$taxation_per=$tax_data[$taxation];
						if($final_tax_nm == "H Form")
						{	
							$taxation='';
						}
					}
					else if($post['nrm_tax']=='sez_no_tax')
					{
						$excies_per='';
						$taxation=$post['nrm_tax'];
						$taxation_per='';
					}
					else
					{   
						$excies_per='';
						$taxation='';
						$taxation_per='';
					}
		
				}
				elseif(isset($post['country_id']) && !empty($post['country_id']) && $post['country_id'] =='214')
					$gst_tax = $post['gst_tax'];
				
				
				if(isset($post['country_id']) && !empty($post['country_id']) && $post['country_id'] != '111')
				{
					$discount=$post['discount'];
					
				}
				else
					$discount='0';
				
				$state=$gst=$hst=$pst=0;
				if(isset($post['country_id']) && $post['country_id'] == '42')
				{
					$state = $post['state'];
					$gst = $post['gst'];
					$pst = $post['pst'];
					$hst = $post['hst'];
				}
				if(!isset($post['same_as_above']))
					$post['same_as_above'] = '';
					
					
			//sonu add 21-4-2017		
				/*if($post['address_book_id']=='')
				{
						$sql1 = "INSERT INTO address_book_master SET status = '1', company_name = '".addslashes($post['customer_name'])."',vat_no='".$post['vat_no']."', user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' ,user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW()";
						$datasql1=$this->query($sql1);
						$address_id = $this->getLastIdAddress();
						$address_book_id = $address_id['address_book_id'];
				
				}
				else
				{
						$address_book_id = $post['address_book_id'];
						$sql1 = "UPDATE address_book_master SET vat_no='".$post['vat_no']."', company_name = '".addslashes($post['customer_name'])."' WHERE address_book_id ='".$post['address_book_id']."'";
						$datasql1=$this->query($sql1);
				}
						
				if($post['company_address_id']=='')
				{			
						$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($post['clientaddress'])."', email_1 = '".$post['email']."', country= '".$post['country_id']."', date_added = NOW()";
						$datasql2=$this->query($sql2);
				}
				else
				{
						$sql2 = "UPDATE company_address SET c_address = '".addslashes($post['clientaddress'])."',email_1 = '".$post['email']."', country= '".$post['country_id']."' WHERE company_address_id ='".$post['company_address_id']."'";
						$datasql2=$this->query($sql2);
				}
				if($post['factory_address_id']=='')
				{		
						$sql3 = "INSERT INTO factory_address SET  address_book_id = '".$address_book_id."',f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."', date_added = NOW()";
						$datasql3=$this->query($sql3);
				}
				else
				{
						if($post['same_as_above'] != '1')
						{	
							$sql3 = "UPDATE factory_address SET f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."' WHERE factory_address_id ='".$post['factory_address_id']."'";
							$datasql3=$this->query($sql3);
						}
				}*/
					//die;
				
				$address_book_id = $post['address_book_id'];
				//[kinjal] : changed code on 23-6-2017
				$contacts = "SELECT email_1 FROM company_address WHERE email_1='".$post['email']."' AND is_delete=0";
				$datacontacts= $this->query($contacts);
				//printr($datacontacts);
				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
				{
						$sql1 = "INSERT INTO address_book_master SET status = '1', company_name = '".addslashes($post['customer_name'])."',vat_no='".$post['vat_no']."', user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' ,user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW()";
						//echo $sql1;
						$datasql1=$this->query($sql1);
						$address_id = $this->getLastIdAddress();
						$address_book_id = $address_id['address_book_id'];
						//printr($address_book_id);
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						//printr($dataadd);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET c_address = '".addslashes($post['clientaddress'])."',email_1 = '".$post['email']."', country= '".$post['country_id']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
							$datasql2=$this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($post['clientaddress'])."', email_1 = '".$post['email']."', country= '".$post['country_id']."', date_added = NOW()";
							$datasql2=$this->query($sql2);
						}
						
						$add_id_fac = "SELECT address_book_id,factory_address_id FROM factory_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd_fac= $this->query($add_id_fac);
						//intr($dataadd_fac);
						if($dataadd_fac->num_rows)
						{
							if($post['same_as_above'] != '1')
							{	
								$sql3 = "UPDATE factory_address SET f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."' WHERE factory_address_id ='".$dataadd_fac->row['factory_address_id']."'";
								$datasql3=$this->query($sql3);
							}
						}
						else
						{
							$sql3 = "INSERT INTO factory_address SET  address_book_id = '".$address_book_id."',f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."', date_added = NOW()";
							$datasql3=$this->query($sql3);
						}
						
				}
				else
				{	
						$address_book_id = $post['address_book_id'];
						$sql1 = "UPDATE address_book_master SET vat_no='".$post['vat_no']."', company_name = '".addslashes($post['customer_name'])."' WHERE address_book_id ='".$post['address_book_id']."'";
						//echo $sql1;
						$datasql1=$this->query($sql1);

						if($post['company_address_id']=='')
						{			
								$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($post['clientaddress'])."', email_1 = '".$post['email']."', country= '".$post['country_id']."', date_added = NOW()";
								$datasql2=$this->query($sql2);
						}
						else
						{
								$sql2 = "UPDATE company_address SET c_address = '".addslashes($post['clientaddress'])."',email_1 = '".$post['email']."', country= '".$post['country_id']."' WHERE company_address_id ='".$post['company_address_id']."'";
								$datasql2=$this->query($sql2);
						}
						if($post['factory_address_id']=='')
						{		
								$sql3 = "INSERT INTO factory_address SET  address_book_id = '".$address_book_id."',f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."', date_added = NOW()";
								$datasql3=$this->query($sql3);
						}
						else
						{
								if($post['same_as_above'] != '1')
								{	
									$sql3 = "UPDATE factory_address SET f_address = '".addslashes($post['client_del_address'])."', country= '".$post['country_id']."' WHERE factory_address_id ='".$post['factory_address_id']."'";
									$datasql3=$this->query($sql3);
								}
						}
				}
				
				$sql = "INSERT INTO proforma set invoice_number = '".$post['invoiceno']."', pro_in_no = '".$pro_in_no."',proforma = '".$post['Proforma']."', customer_name = '".addslashes($post['customer_name'])."',address_book_id = '".$address_book_id."', email = '".$post['email']."',	buyers_order_no = '".$post['buyersno']."', invoice_date = '".$post['invoicedate']."', goods_country = '".$post['country']."', buyers_date = '".$post['buyers_date']."',
				 address_info = '".addslashes($post['clientaddress'])."',delivery_address_info='".addslashes($post['client_del_address'])."',same_as_above='".$post['same_as_above']."',vat_no='".$post['vat_no']."', delivery_info = '".$post['delivery']."', currency_id = '".$post['currency']."', bank_id = '".$post['bank_id']."', payment_terms = '".$post['payment_terms']."', destination = '".$post['country_id']."', state = '".$state."',gst = '".$gst."',pst = '".$pst."',hst = '".$hst."' ,port_loading = '".$post['port_loading']."', transportation = '".encode($post['transport'])."', sign_date = '".$post['signature_date']."', added_by_user_id = '".$user_id."', added_by_user_type_id = '".$user_type_id."', status = '1' , proforma_status = '1' , date_added = NOW(), date_modify = NOW(), is_delete = 0,tax_mode='".$tax_mode."',tax_form_name='".$final_tax_nm."',excies_per='".$excies_per."',taxation='".$taxation."',taxation_per='".$taxation_per."',freight_charges='".$post['freight']."',packing_charges='".$packing_charges."',gst_tax='".$gst_tax."',discount='".$discount."'";
				
				$data = $this->query($sql);
				$ProformaId = $this->getLastId();
				$proforma_invoice_id = $ProformaId;
	
				}
				if($post['product']==0)
				{
					$product_nm = "Cylinder";
					$valve='';
					$zipper='';
					$spout='';
					$accessorie='';		
				}
				else
				{
					$product_nm = $product_name['product_name'];
					$valve=$post['valve'];
					$zipper=$post['zipper'];
					$spout=$post['spout'];
					$accessorie=$post['accessorie'];
				}
				
				if($post['product']=='31' || $post['product']=='16')
					$filling = $post['filling'];
				else
					$filling = '';

				$sql = "INSERT INTO proforma_invoice SET added_by_user_id = '".$user_id."', added_by_user_type_id = '".$user_type_id."', proforma_id = '".$proforma_invoice_id['proforma_id']."', invoice_number ='".$post['invoiceno']."',    product_id = '".$post['product']."', product_name = '".$product_nm."', size = '".$post['size']."',  height = '".$height."', width = '".$width."', gusset = '".$gusset."', volume = '".$volume."',  valve = '".$valve."', 
				zipper = '".$zipper."', spout = '".$spout."', accessorie = '".$accessorie."',filling='".$filling."', date_added = NOW(), date_modify = NOW(), is_delete = 0  ";
				$data = $this->query($sql);
				$InvoiceId = $this->getLastId();
				$ProInID = $this->getLastInvoiceId();
				
				
				if(isset($post['color']) && !empty($post['color'])) {
					foreach($post['color'] as $color) {
					//printr($color);
					$clr_txt='';				
					if($post['product']==0)
					{
						$clr_id = "0";
					}
					else{
						$clr_id = $color['color'];
					}
					if(isset($color['color']) == -1)
					{
						$clr_txt = $color['color_text'] ;
					}
				
					$sql = "INSERT INTO `".DB_PREFIX."proforma_color` set proforma_id = '".$proforma_invoice_id['proforma_id']."', proforma_invoice_id = '".$ProInID['proforma_invoice_id']."', color = '".$clr_id."',color_text='".$clr_txt."', rate ='".$color['rate']."', quantity = '".$color['qty']."',description ='".$color['description']."'";
					//echo $sql;die;
					$data = $this->query($sql);
					}
				}
				$returnArray = array(
						'proforma_invoice_id' => $proforma_invoice_id,
						'proforma_id' => $proforma_invoice_id						
				);
				return $returnArray;

		}
		public function getInvoice($proforma_id) {
			$sql = "select * from " . DB_PREFIX ."proforma_invoice where proforma_id = '".$proforma_id."' AND is_delete = '0'";
			$data = $this->query($sql);
			//echo $sql;
			if($data->num_rows){
				return $data->rows;
			}
			else {
				return false;
			}
		}
		public function getSpout($product_spout_id) {
			$sql = "select * from " . DB_PREFIX ."product_spout where product_spout_id = '".$product_spout_id."'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}
			else {
				return false;
			}
		}
		public function getZipper($product_zipper_id) {
			$sql = "select * from " . DB_PREFIX ."product_zipper where product_zipper_id = '".$product_zipper_id."'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}
			else {
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
		public function getLastId() {
			$sql = "SELECT proforma_id FROM proforma ORDER BY proforma_id DESC LIMIT 1";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}
			else {
				return false;
			}
		}
		public function getProformaInvoice($proforma_id) {
			$sql = "SELECT * FROM proforma_invoice where proforma_id = '".$proforma_id."'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->rows;
			}
			else {
				return false;
			}
		}
		public function getProInNo() {
			$sql = "SELECT pro_in_no FROM proforma ORDER BY proforma_id DESC LIMIT 1";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}
			else {
				return false;
			}
		}
		public function BankDetails($bank_id)
		{
			$sql = "SELECT * FROM bank_detail WHERE bank_detail_id='".$bank_id."'";
			$data=$this->query($sql);
			if($data->num_rows)
			{
				return $data->row;
			}
			else
			{	
				return false;
			}
		}
		public function removeInvoice($proforma_invoice_id,$proforma_id){
		//echo "DELETE FROM proforma_color WHERE `proforma_invoice_id` = '".$proforma_invoice_id."' AND proforma_id='".$proforma_id."'<br>DELETE FROM proforma_invoice WHERE `proforma_invoice_id` = '".$proforma_invoice_id."' AND proforma_id='".$proforma_id."'";
			$sql1 = $this->query("DELETE FROM proforma_color WHERE proforma_invoice_id = '".$proforma_invoice_id."' AND proforma_id='".$proforma_id."'");
			//echo "DELETE FROM proforma_color WHERE `proforma_invoice_id` = '".$proforma_invoice_id."' AND proforma_id='".$proforma_id."'";
			////echo "DELETE FROM proforma_invoice WHERE proforma_invoice_id = '".$proforma_invoice_id."' AND proforma_id='".$proforma_id."'";
			$sql = $this->query("DELETE FROM proforma_invoice WHERE proforma_invoice_id = '".$proforma_invoice_id."' AND proforma_id='".$proforma_id."'");
			
			$update_total_invoice_price = $this->UpdateTotalInvoicePrice($proforma_id);
		}
		public function getLastInvoiceId() {
			$sql = "SELECT proforma_invoice_id FROM proforma_invoice ORDER BY proforma_invoice_id DESC LIMIT 1";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}
			else {
				return false;
			}
		}
/*	public function getColorDetails($proforma_id,$proforma_invoice_id) {
	
		$sql = "select * from `".DB_PREFIX."proforma_color` WHERE proforma_id='".$proforma_id."' AND proforma_invoice_id = '".$proforma_invoice_id."' ";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->rows;
		}
		else {
			return false;
		}
	}*/
		public function getColorDetails($proforma_id,$proforma_invoice_id) {
			$sql = "select pc.*,poc.color as color_name from ".DB_PREFIX."proforma_color as pc,pouch_color as poc  WHERE pc.color=poc.pouch_color_id AND pc.proforma_id='".$proforma_id."' AND pc.proforma_invoice_id = '".$proforma_invoice_id."'";
			$data = $this->query($sql);
			if($data->num_rows) {
				return $data->rows;
			}
			else {
				return false;
			}
		}
		/*public function getColorName($pouch_color_id){
		$sql = "SELECT color FROM `" . DB_PREFIX . "pouch_color` WHERE pouch_color_id = '".$pouch_color_id."' ";
		$sql .= " ORDER BY color";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}*/
		public function getTotalInvoice($filter_data=array(), $status, $proforma_status,$user_id,$user_type_id,$is_delete,$add_book_id='0'){
		//sonu add
			$add_id='';
			if($add_book_id!=0)
				$add_id = "AND p.address_book_id='". $add_book_id."'";
		
		//end
		
		
			if($user_type_id==1 && $user_id==1)
			{
				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND p.is_delete = '".$is_delete."' $add_id ";
				//echo $sql;
				
			}
			else
			{
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
				$str = ' OR ( p.added_by_user_id IN ('.$userEmployee.') AND p.added_by_user_type_id = 2 )';
			}
				
				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND p.is_delete = '".$is_delete."' 
			    AND (p.added_by_user_id='".$set_user_id."' AND p.added_by_user_type_id='".$set_user_type_id."' $str)  $add_id ";
				//echo $sql;
			}
				if($status >= '0') {
				$sql .= " AND p.status ='".$status."' ";
			}
			if($proforma_status >= '0') {
				$sql .= " AND proforma_status ='".$proforma_status."' ";
			}
			if(!empty($filter_data)){
				if(!empty($filter_data['customer_name'])){
					$sql .= " AND customer_name LIKE '%".addslashes($filter_data['customer_name'])."%' ";		
				}
				if(!empty($filter_data['email'])){
					$sql .= " AND email LIKE '%".$filter_data['email']."%' ";		
				}
				if(!empty($filter_data['invoice_number'])){
					$sql .= " AND pro_in_no LIKE '%".$filter_data['invoice_number']."%' ";		
				}
				if(!empty($filter_data['postedby']))
				{
					$spitdata = explode("=",$filter_data['postedby']);
					$sql .="AND p.added_by_user_type_id = '".$spitdata[0]."' AND p.added_by_user_id = '".$spitdata[1]."'";
				}
			}
			$data = $this->query($sql);
			return $data->num_rows;
		}
		public function getInvoices($data,$filter_data=array(), $status, $proforma_status,$user_id,$user_type_id,$is_delete,$add_book_id='0'){	
			//sonu add
			$add_id='';
			if($add_book_id!=0)
				$add_id = "AND p.address_book_id='". $add_book_id ."'";			
			//end
			
			if($user_type_id==1 && $user_id==1)
			{
				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND p.is_delete = '".$is_delete."' $add_id " ;
			}
			else
			{
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
				$str = ' OR ( p.added_by_user_id IN ('.$userEmployee.') AND  p.added_by_user_type_id = 2 )';
			}
				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma as p,country as c WHERE c.country_id=p.destination AND 
				(p.added_by_user_id = '".$set_user_id."' AND p.added_by_user_type_id = '".$set_user_type_id."' $str) AND p.is_delete = '".$is_delete."' $add_id " ;
			}
			if($status >= '0') {
				$sql .= " AND p.status ='".$status."' ";
			}
			if($proforma_status >= '0') {
				$sql .= " AND proforma_status ='".$proforma_status."' ";
			}
			if(!empty($filter_data)){
				if(!empty($filter_data['customer_name'])){
					$sql .= " AND customer_name LIKE  '%".addslashes($filter_data['customer_name'])."%'";		
				}
				if(!empty($filter_data['email'])){
					$sql .= " AND email LIKE '%".$filter_data['email']."%' ";		
				}
				if(!empty($filter_data['invoice_number'])){
					$sql .= " AND pro_in_no LIKE '%".$filter_data['invoice_number']."%' ";		
				}
				if(!empty($filter_data['postedby']))
				{
					$spitdata = explode("=",$filter_data['postedby']);
					$sql .="AND p.added_by_user_type_id = '".$spitdata[0]."' AND p.added_by_user_id = '".$spitdata[1]."'";
				}
			}
			if (isset($data['sort'])) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY proforma_id";	
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
		public function getUserEmployeeIds($user_type_id,$user_id){
			$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' 
			AND user_id = '".(int)$user_id."'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row['ids'];
			}else{
				return false;
			}
		}
		public function updateProformaStatus($status,$data){
			if($status == 0 || $status == 1){
				$sql = "UPDATE `" . DB_PREFIX . "proforma` SET status = '" .(int)$status. "',  date_modify = NOW() 
				WHERE proforma_id IN (" .implode(",",$data). ")";
				$this->query($sql);
			}elseif($status == 2){
				$sql = "UPDATE `" . DB_PREFIX . "proforma` SET is_delete = '1', date_modify = NOW() WHERE proforma_id IN (" .implode(",",$data). ")";
				$this->query($sql);
			}
		}
		public function updateProStatus($proforma_id,$status_value){
			$sql = "UPDATE " . DB_PREFIX . "proforma SET status = '".$status_value."', date_modify = NOW() WHERE proforma_id = '" .(int)$proforma_id. "'";
			$this->query($sql);
		}
		public function getUserInfo($user_id) {
			$sql = "SELECT * from ".DB_PREFIX."user WHERE user_id = '".$user_id."'";
			$data = $this->query($sql);
			if($data->num_rows) {
				return $data->row;
			} else {
				return false;
			}
		}
		public function getProformaData($proforma_id){
			$sql = "SELECT p.*,b.* FROM " . DB_PREFIX . "proforma as p,bank_detail as b WHERE b.bank_detail_id=p.bank_id AND p.proforma_id = '" .(int)$proforma_id. "'";
			//echo $sql;
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}
		public function getSingleInvoice($proforma_invoice_id) {
			$sql = "select * from " . DB_PREFIX ."proforma_invoice where proforma_invoice_id = '".$proforma_invoice_id."'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}
			else {
				return false;
			}
		}
		public function getbankDetails($currency_code) {
			$sql = " SELECT * from bank_detail WHERE curr_code = '".$currency_code."' AND is_delete = '0' ";
			$data = $this->query($sql);
			if($data->num_rows) {
				return $data->rows;
			} else {
				return false;
			}
		}
		public function updateInvoice($post) {
		//printr($post);
		//die;
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$product_name = $this->getProduct($post['product']);
			if(isset($post['size']) && ($post['size'] != '0')) {
				$size = decode($post['size']);
			if($size != 0 || $size !='') {			
				$getMasterSize = $this->ProductSize(decode($post['size']));
				$height = $getMasterSize['height'];
				$width = $getMasterSize['width'];
				$gusset = $getMasterSize['gusset'];
				// mansi 23-1-2016 (problem create at edit time in proforma)
				$volume = $getMasterSize['volume'];		
			}} else {
				$height = $post['height'];
				$width = $post['width'];
				$gusset = $post['gusset'];
				$volume = '';
			}
			if($post['product']==0)
			{
				$product_nm = "Cylinder";
				$zipper='';
				$valve='';
				$spout='';
				$accessorie='';
			}
			else
			{
				$product_nm = $product_name['product_name'];
				$zipper=$post['zipper'];
				$valve=$post['valve'];
				$spout=$post['spout'];
				$accessorie=$post['accessorie'];
			}
			
			if($post['product']=='31' || $post['product']=='16')
				$filling = $post['filling'];
			else
				$filling = '';
					
			$sql = "UPDATE `".DB_PREFIX."proforma_invoice` SET  product_id = '".$post['product']."', product_name = '".$product_nm."',  height = '".$height."', width = '".$width."', gusset = '".$gusset."', volume = '".$volume."' ,valve = '".$valve."', zipper = '".$zipper."', spout = '".$spout."', accessorie = '".$accessorie."',size='".$post['size']."' ,filling='".$filling."', date_modify = NOW(),	is_delete = 0 
			WHERE proforma_invoice_id = '".$post['pro_id']."'  ";
			$data = $this->query($sql);
	
			if(isset($post['color']) && !empty($post['color'])) {
				//$this->query("DELETE FROM `" . DB_PREFIX . "proforma_color` WHERE proforma_invoice_id='".$post['pro_id']."'");
				foreach($post['color'] as $color) {
					$clr_txt='';
					if($post['product']==0)
					{
						$clr_id = "0";
					}
					else{
						$clr_id = $color['color'];
					}
					if(isset($color['color'])== -1)
					{	
						if(!empty($color['color_text']))
						{
							$clr_txt = $color['color_text'] ;
						}
						else
						{
							$clr_txt = '';
						}
					}
					if(isset($color['id']) && $color['id']!='')
					{
							
							if(isset($color['color']) && $color['color']!='')
							{
					
							$sql = "UPDATE `".DB_PREFIX."proforma_color` SET proforma_id = '".$post['invoiceno']."', proforma_invoice_id = '".$post['pro_id']."', color = '".$clr_id."',color_text='".$clr_txt."', rate ='".$color['rate']."', quantity = '".$color['qty']."',description='".$color['description']."' WHERE id='".$color['id']."'";
							}
							else
							{
								$sql = "DELETE FROM `".DB_PREFIX."proforma_color` WHERE id='".$color['id']."'";				
							}
					}
					else
					{
						$sql = "INSERT INTO `".DB_PREFIX."proforma_color` set proforma_id = '".$post['invoiceno']."', proforma_invoice_id = '".$post['pro_id']."', color = '".$clr_id."',color_text='".$clr_txt."', rate ='".$color['rate']."', quantity = '".$color['qty']."',description='".$color['description']."'";
					}
					$data = $this->query($sql);
				}
			}
		}
		public function updateProforma ($post) {
			//printr($post);die;
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$taxation='';
			$tax_data='';
			$tax_name='';
			$final_tax_nm='';
			$excies_per=0;
			$taxation_per=0;
			$tax_mode='';
			$freight='';
		//	$tin_no = '';
			$packing_charges=0;
			if(isset($post['country_id']) && !empty($post['country_id']) && $post['country_id'] != '111')
			{
				$discount=$post['discount'];
			}
			else
				$discount='0';
			
			if(isset($post['country_id']) && !empty($post['country_id']) && $post['country_id'] ==111)
			{
				///$tin_no = $post['tin_no'];
				$packing_charges=$post['packing'];
				$discount='0';
				$tax_mode=$post['taxation'];
				if($tax_mode=='form')
				{	
					$val=$post['form'];
					foreach($val as $taxval)
					{	
						$final_tax_nm.= $taxval.',';
						$tax_name.=' tax_name="'.$taxval.'" OR ';
					}
					$final_tax_nm=substr($final_tax_nm,0,-1);
					$tax_name=substr($tax_name,0,-3);
					if(count($val)>1)
					{
						if(in_array('H Form',$val))
						{
							$tax_name='';
						}
					}
				}
				else
				{
					$tax_name.=' tax_name="'.$post['taxation'].'"';
					$final_tax_nm=$post['taxation'];
				}
				if($post['nrm_tax']!='sez_no_tax')
				{
					if($tax_name!='')
					{
						$taxation= $post['taxation'];
						$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id FROM " . DB_PREFIX . "taxation WHERE 
						status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
						$data_tax = $this->query($sql);
						$tax_data=$data_tax->row;
					}
				}
			//printr($tax_data);
				if(isset($tax_data) && !empty($tax_data))
				{ 
					$taxation=$post['nrm_tax'];
					$excies_per=$tax_data['excies'];
					$taxation_per=$tax_data[$taxation];
					if($final_tax_nm == "H Form")
					{	
						$taxation='';
					}
				}
				else if($post['nrm_tax']=='sez_no_tax')
				{
					$excies_per='';
					$taxation=$post['nrm_tax'];
					$taxation_per='';
				}
				else
				{   
					$excies_per='';
					$taxation='';
					$taxation_per='';
				}
				//$freight=$post['freight'];
			}
			if(!isset($post['same_as_above']))
					$post['same_as_above'] = '';
					
			$state=$gst=$hst=$pst=0;
			if(isset($post['country_id']) && $post['country_id'] == '42')
			{
				$state = $post['state'];
				$gst = $post['gst'];
				$pst = $post['pst'];
				$hst = $post['hst'];
			}
			
			$freight=$post['freight'];
			//echo $freight;die;
			//echo $taxation_per;die;
			$sql = "UPDATE `".DB_PREFIX."proforma` SET invoice_number = '".$post['invoiceno']."', proforma = '".$post['Proforma']."', customer_name = '".addslashes($post['customer_name'])."', email = '".$post['email']."', buyers_order_no = '".$post['buyersno']."', invoice_date = '".$post['invoicedate']."', goods_country = '".$post['country']."', buyers_date = '".$post['buyers_date']."', address_info = '".addslashes($post['clientaddress'])."',delivery_address_info='".addslashes($post['client_del_address'])."',same_as_above='".$post['same_as_above']."',vat_no='".$post['vat_no']."', delivery_info = '".$post['delivery']."', currency_id = '".$post['currency']."', bank_id = '".$post['bank_id']."', payment_terms = '".$post['payment_terms']."', destination = '".$post['country_id']."',state = '".$state."',gst = '".$gst."',pst = '".$pst."',hst = '".$hst."' ,port_loading = '".$post['port_loading']."', transportation = '".encode($post['transport'])."', sign_date = '".$post['signature_date']."', date_modify = NOW(), is_delete = 0,tax_mode='".$tax_mode."',tax_form_name='".$final_tax_nm."',excies_per='".$excies_per."',taxation='".$taxation."',freight_charges='".$freight."',packing_charges='".$packing_charges."',taxation_per='".$taxation_per."',discount='".$discount."' WHERE proforma_id = '".$post['invoiceno']."'	";
			//echo $sql;
			//die;
			$data = $this->query($sql);
			
			
			$update_total_invoice_price = $this->UpdateTotalInvoicePrice($post['invoiceno']);
			return $data;
		}
		public function getProforma($proforma_id) {
			$sql = "select * from ".DB_PREFIX."proforma where proforma_id = '".$proforma_id."'";
			$data = $this->query($sql);
			if($data->num_rows) {
				return $data->row;
			} else {
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
				if(!$data1->num_rows)
	 			{
					$cond1 = " LIMIT 1";
					$sql = "SELECT price,width_to,gusset FROM ( ( SELECT price,width_to,gusset,".$width."-width_to AS diff FROM product_extra_tool_price WHERE product_id = '" .(int)$product_id. "' AND width_to >'".$width."' ".$cond1." ) UNION ALL ( SELECT price,width_to,gusset,width_to-".$width." AS diff FROM product_extra_tool_price  WHERE product_id = '" .(int)$product_id. "' AND width_to <'".$width."' ".$cond1."  )) AS tmp ORDER BY diff LIMIT 2" ;
					$data = $this->query($sql);
					if($data->num_rows){
						return $data->rows;				
					}else{
						return false;
					}
	 			}
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
		public function numberFormate($number,$decimalPoint=3){
			return number_format($number,$decimalPoint,".","");
		}
		public function generateProformaNumber(){
			//for online 
			$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='swissin_swisserp' AND TABLE_NAME= 'proforma'");
			//for Offonline 
			//$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='swisspac' AND TABLE_NAME= 'proforma'");
			$count = $data->row['AUTO_INCREMENT'];
			$strpad = str_pad($count,8,'0',STR_PAD_LEFT);
			return $strpad;
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
		public function saveProformaStatus($proforma_id){
			$sql = "UPDATE `" . DB_PREFIX . "proforma` SET proforma_status = '0'  WHERE proforma_id =".$proforma_id;
			$data = $this->query($sql);
			return $data;
		}
		public function sendInvoiceEmail($proforma_id,$to_email,$url)
		{	
			$html ='';
			$proforma=$this->getProformaData($proforma_id);
			
			$proforma_id=$proforma['proforma_id'];
			$proforma_inv=$this->getProformaInvoice($proforma_id);
			$html .='<style>
 			.col-lg-3 {width: 15%;}
			#client {
    		border-left: 6px solid #0087c3;
    		float: left;
    		padding-left: 6px;
			}	
			h1 {
				background:#333;
    			border-bottom: 1px solid #5d6975;
    			border-top: 1px solid #5d6975;
    			color: #FFF;
    			font-size:  12px;
    			font-weight: normal;
    			line-height: 1.4em;
    			margin: 0 0 20px;
    			text-align: center;
			}
			article, article address, table.meta, table.inventory { margin: 0 0 3em; }
			table.meta, table.balance { float: right; width: 50%; }
			table.meta:after, table.balance:after { clear: both; content: ""; display: table; }
		
		/* table meta */
		table.meta th { width: 40%;  font-size:  12px; }
		table.meta td { width: 60%;   font-size:  12px; }
		/* table items */
		table { font-size:  12px; table-layout: fixed; width: 100%; }
		table { border-collapse: separate; border-spacing: 1px; font-size:  12px;  }
		th, td { border-width: 1px;position: relative; text-align: left;  font-size:  12px; }
		th, td { border-radius: 0em; border-style: solid; font-size:  12px;}
		th { background: #EEE; border-color: #BBB; font-size:  12px; }
		td { border-color: #DDD; font-size:  12px;}
		@font-face {

			font-family:IDAutomationHC39M;
			src:url("'.HTTP_SERVER.'css/Fonts/IDAutomationHC39M.ttf"),
		}
		.barcode{
			font-family:IDAutomationHC39M !important;	
		}</style>';
		$html .= $this->viewProformaInvoice($proforma_id);
		$addedByinfo=$this->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);
		//printr($addedByinfo);
		$subject = $proforma['pro_in_no'].' '.$proforma['customer_name'];  
			$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);
			$email_temp[]=array('html'=>$html,'email'=>$to_email);
			$email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);
			$form_email=$addedByinfo['email'];
			$obj_email = new email_template();
		
			$rws_email_template = $obj_email->get_email_template(5); 
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
			$path = HTTP_SERVER."template/proforma_invoice.html";
			$output = file_get_contents($path);  
			$search  = array('{tag:header}','{tag:details}');
			$signature = 'Thanks.';
			if($_SESSION['ADMIN_LOGIN_SWISS']=='17' && $_SESSION['LOGIN_USER_TYPE']=='2')
			{
				//printr($email_temp);die;
			
			}
			
			//printr(ADMIN_EMAIL);//die;

			//$k=1;
			foreach($email_temp as $val)
			{// printr($val['email']);//die;
			  //echo $k.'1';
				$toEmail =$form_email;
				$firstTimeemial = 1;								
				$subject =$proforma['pro_in_no'].' '.$proforma['customer_name'];  
				$message = '';
				
				if($val['html'])
				{
					$tag_val = array(
					"{{header}}"=>'Proforma Invoice Detail',
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
			//printr($message);
				//(old)
				//send_email('tech@swisspack.co.in','tech@swisspack.co.in',$subject,$message,'',$url);
				send_email($val['email'],$form_email,$subject,$message,'',$url);
				//ADMIN_EMAIL // [kinjal] : 26/3/2016
				//send_email($val['email'],$form_email,$subject,$message,'',$url);
				//$k++;
			}	
			//send_email('tech@swisspack.co.in','tech@swisspack.co.in',$subject,$message,'',$url);
			//die;
		}
		
		function viewProformaInvoice($proforma_id,$n='0') {
			$html ='';
			//[kinjal] added on 10-5-2017
			$sd = '<span style="border-bottom: 1px dashed #ccc;display: -webkit-box;"></span>';
			if($n=='1')
				$sd = '<div class="line line-dashed m-t-large"></div>';
				
			$proforma=$this->getProformaData($proforma_id);
			//printr($proforma);die;
			$proforma_id=$proforma['proforma_id'];
			$proforma_inv=$this->getProformaInvoice($proforma_id);
			//printr($proforma_inv);
			$user_name=$this->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
			//printr($user_name);
			$show_vat='';
			$admin_vat_no='';
			if($proforma['destination']==111)
			{
				$title='Consignor';
				$address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway
				<br>At Dabhasa vaillage,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
				$sign='Swiss PAC PVT LTD';
				$user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
				$vat_no = $proforma['vat_no'];
				$admin_vat_no = 'GST No. : '.$user['vat_no'];
				$show_vat = 'GST No. :'.$vat_no;
				//$tin_no = '<br>Tin No. :'.$proforma['tin_no'];
			}
			else
			{	
				//$tin_no = '';
				if($proforma['added_by_user_id']!='1' && $proforma['added_by_user_type_id'] != '1')
				{	$title='From';
					$user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
					//printr($user);
					$address=nl2br($user['company_address']);
					$sign=$user['company_name'];
					if($proforma['destination']==155)
						$admin_vat_no = 'RFC No. : '.$user['vat_no'];
					else
						$admin_vat_no = 'Vat No. : '.$user['vat_no'];
					
					$vat_no = $proforma['vat_no'];
					if($proforma['destination']==155)
						$show_vat = 'RFC No. :'.$vat_no;
					else if($proforma['destination']==214)
						$show_vat = 'Gst No. : '.$vat_no;	
					else
						$show_vat = 'Vat No. :'.$vat_no;
				}
				else
				{
					$title='Consignor';
					$address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway
					<br>At Dabhasa vaillage,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
					$sign='Swiss PAC PVT LTD';
				}
				
			}
			$html .='<div style="text-align:center;border: 1px solid black;">PROFORMA INVOICE </div>
						<div style=" width: 100%;float: left;  border: 1px solid black;font-size: 18px;">
							<table style="" class="table b-t text-small">
								<tr>
									<td style="vertical-align: top;">
									
										<p><b>'.$title.'<br></p><p><br>'.$address.'<br></p>'.$admin_vat_no.' 
									</td>
									<td style="padding: 0px;">
										<table style="width: 100%;border: 1px solid black;border-spacing: 0px;" cellspacing="0px" cellpadding="10px">
											<tbody>
											<tr>
												<td valign="top"><b>Invoice No.&amp; Date</b></td>
												<td>'.$proforma['pro_in_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['invoice_date']).'<br><br><br><span class="barcode" >'.$proforma['pro_in_no'].'</span><br><br></td>
											</tr>
											<tr>
												<td><b>Proforma :</b></td>
												<td>&nbsp;'.dateFormat(4,$proforma['proforma']).'</td>
											</tr>
											<tr>
												<td><b>Buyers Order No. &amp; Date:</b></td>
												<td>'.$proforma['buyers_order_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['buyers_date']).'</td>
											</tr>
											<tr>
												<td><b>Country of origin of goods:</b></td>
												<td>'.$proforma['goods_country'].'</td>
											</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</table>
						</div>
						
						<div style="width: 100%; float: left;  border: 1px solid black;font-size: 18px;">
						
							<table>
								<tr>
									<td style="vertical-align: top;">
											<table cellspacing="0px" cellpadding="0px" >
											<tbody>
											<tr>
												<td><b>Consignee</b></td>';
												if($proforma['same_as_above']!='1')
												{
													$html .='<td><b>Delivery Address</b></td>';
												}
											$html .='</tr>
											<tr>
												<td><p>'.$proforma['customer_name'].'<br/>'.nl2br($proforma['address_info']).'<br/>Email : '.$proforma['email'].'<br>'.$show_vat.'</p></td>';
												if($proforma['same_as_above']!='1')
												{
													$html .='<td><p>'.nl2br($proforma['delivery_address_info']).'</p></td>';
												}
											$html .='</tr>
											</tbody>
										</table>
									</td>
									<td style="padding: 0px;vertical-align: top;">
										<table cellspacing="0px" cellpadding="0px" style=" border-spacing: 0px; width: 100%;border: 1px solid black;padding: 0px;">
											<tbody>
											<tr>
												<td style="text-align:center" colspan="2"> <b>Terms of Delivery &amp; Payment</b></td>
											</tr>
											<tr>
												<td><b>Delivery:</b></td>
												<td>'.$proforma['delivery_info'].'</td>
											</tr>
											<tr>
												<td><b>Mode Of Shipment:</b></td>
												<td>By '.ucwords(decode($proforma['transportation'])).'</td>
											</tr>
											<tr>
												<td><b>Payment Terms:</b></td>
												<td>'.$proforma['payment_terms'].'</td>
											</tr>
											</tbody>
										</table>
										<table style=" border-spacing: 0px; width:100%;border: 1px solid black;">
											 <tbody>
											 <tr>
												<td><b>Port Of Loading:</b></td>
												<td><b>Final Destination:</b></td>
											 </tr>';
											 $con_id =$proforma['destination'];
											$countrys = $this->getCountry($con_id);

									$html.='<tr><td>'.$proforma['port_loading'].'</td>
												<td>'.$countrys['country_name'].'</td>
											</tr>											
											</tbody>
										</table>
									</td>
								</tr>
							</table>
							
						</div>';	
						
						
						
						 $currency = $this->getCurrencyId($proforma['currency_id']);
					$html .='<div style=" width: 100%;float: left;  border: 1px solid black;">
							<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 14px;">
								<tbody>
								<tr>
									<td width="5%"><div align="center"><b>Sr. No</b></div></td>
									<td width="60%"><div align="center"><b>Discription of Goods</b></div></td>
									<td width="10%"><b>Quantity In Units</b></td>
									<td width="15%"><b>Rate &nbsp;'.$currency['currency_code'].'</b></td>
									<td width="10%"><b>Amount &nbsp;'.$currency['currency_code'].'</b></td>
								</tr>';
								$total = 0;$total_rate=0; $final_total=0; $n=1;$total_excies_rate=0;$ex_per=array();$total_taxation_rate=0;$tax_per=array();$taxa=array();
						//[kinjal] : added on [22-8-2016]
						$custom_pro_id=0;
						foreach($proforma_inv as $invoice_key=>$invoice){
							
									$getProductSpout = $this->getSpout(decode($invoice['spout']));
									$getProductZipper = $this->getZipper(decode($invoice['zipper']));
									$zipper_name='';
									$valve_name='';
									$spout_name=$acc_name='';
									if($invoice['valve']=='With Valve')
										$valve_name=$invoice['valve'];
									//if($getProductZipper['zipper_name']!='No zip')
										$zipper_name=$getProductZipper['zipper_name'];
									if($getProductSpout['spout_name']!='No Spout')
										$spout_name=$getProductSpout['spout_name'];
									
								$getProductAccessorie = $this->getAccessorie(decode($invoice['accessorie']));	
								///printr($getProductAccessorie);
									if($getProductAccessorie['product_accessorie_name']!='No Accessorie')	
								        $acc_name=$getProductAccessorie['product_accessorie_name'];
								        
									//printr($getProductAccessorie);
									if($invoice['product_id'] == 3)
									{
										$gusset = floatval($invoice['gusset']).'+'.floatval($invoice['gusset']);
									}
									else
									{
										$gusset = floatval($invoice['gusset']);
									}
									$quantity = $this->getColorDetails($proforma['proforma_id'],$invoice['proforma_invoice_id']);
								$html .='<tr><td>'.$n.'</td>';
								if($invoice['product_id']!='37')
								{
										if($invoice['product_id']=='10')
										{
											$html .='<td><b>Size : </b>'.floatval($invoice['width']).' inch &nbsp;Width &nbsp;X&nbsp;'.floatval($invoice['height']).' inch &nbsp;Height &nbsp;';
											if($gusset>0)
												$html .='X&nbsp;'.$gusset.' inch';
										}
										/*else if($invoice['product_id']=='37')
										{
											$html .='<td><b>Size : </b>'.floatval($invoice['width']).' cc &nbsp;';
										}*/
										else
										{
											$html .='<td><b>Size : </b>'.floatval($invoice['width']).' mm &nbsp;Width &nbsp;X&nbsp;'.floatval($invoice['height']).' mm &nbsp;Height &nbsp;';
													if($gusset>0)
														$html .='X&nbsp;'.$gusset.' mm';
										}
								}
											
											if($invoice['volume']>0 && $invoice['product_id']!='37')
												$html .=' ('.$invoice['volume'].')';
												
											if($invoice['volume']>0 && $invoice['product_id']=='37')
												$html .= '<td><b>Size : </b>'.$invoice['volume'];
												
												$html .='<br><b>Make up of pouch :</b>'.$invoice['product_name'];
												
												if($invoice['product_id']!='37')
													$html .='<b> '.$zipper_name.'  '.$valve_name.' '.$spout_name.' '.$acc_name.'</b>';
												if($invoice['filling']!='')
													$html.='<br><b>Filling Option: </b>'.$invoice['filling'].'';
													
												foreach($quantity as $quantity_val) { 
											//	printr($quantity_val);
													$clr_text='';
													if($quantity_val['color']=='-1')
													{
														$custom_pro_id = '1';
														if($quantity_val['color_text']!='')
														$clr_text = "(".$quantity_val['color_text'] .")";
													}
										$html .='<br><b>Color : '.$quantity_val['color_name'].' '.$clr_text.'<br></b>';
										if($quantity_val['description']!='')
										{
											$html .='<b>Material Description : </b>'.$quantity_val['description'].'<br>';
										}
										//[kinjal] added on 10-5-2017
										$html .=$sd;
									}
									$html .='</td><td><br>';
									//[kinjal] edited on 10-5-2017
									foreach($quantity as $quantity_val) {
							//printr($quantity_val);
										$total = $total+$quantity_val['quantity'] ;$total_qty = $quantity_val['quantity'];
										if($quantity_val['description']!='')
											$html .='<br>';
										$html .='<br>'.$total_qty.''.$sd;
									}
									$html .='</td><td><br>';                                                                        
									foreach($quantity as $rate_val) { 
							//printr($rate_val['rate']);
										$total_rate=$total_rate+$rate_val['rate'];$total_rt = $rate_val['rate'];
										if($rate_val['description']!='')
											$html .='<br>';
										$html .='<br>'.$total_rt.''.$sd;
										
									}
									$html.='</td><td><br>';
									foreach($quantity as $rate_val) {
										$total_amnt = $rate_val['quantity'] * $rate_val['rate'];
										if($rate_val['description']!='')
											$html .='<br>';
										$html.= '<br>'.$total_amnt.''.$sd;
										$final_total=$final_total+$total_amnt;
									}
									$html .='</td></tr>';
									//[kinjal] End
									//sonu add  2/12/2016
										if($invoice['product_id']=='18')
											{
																$n=$n+1;
													
															$html .='<tr><td>'.$n.'</td><td><b> Cabel Tie ';
															
															
														
														$html .='</td><td><br><br>';
														foreach($quantity as $quantity_val) {
												//printr($quantity_val);
															$total = $total+$quantity_val['quantity']  ; 
															$total_qty = $quantity_val['quantity'] * 2;
															$html .= $total_qty.'<br>';
														}
														$html .='</td><td><br><br>';
														foreach($quantity as $rate_val) { 
												//printr($rate_val);
															$total_rate=$rate_val['rate'] * 0 ;
															$html .=$total_rate.'<br>';
															
														}
														
														$html.='</td><td><br><br>';
														foreach($quantity as $rate_val) {
															$total_amnt = $rate_val['quantity'] *0;
															$html.= $total_amnt.'<br>'; 
															$final_total=$final_total+$total_amnt;
														}
														$html .='</td></tr>';
											
										}
										$n++;
						}
									
								if($proforma['freight_charges']!=0)
								{
									//printr($proforma['packing_charges']);
									$freight_charges=round($proforma['freight_charges'],3);
									if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
					  				{
										$final_total=$final_total;
									}
									else
									{
										$final_total=$final_total+$freight_charges;
									
									$html .='<tr>
											<td></td>
												<td><div align="right">
														<strong>Freight Charges </strong>
														</div></td>
													 <td><b></b></td>
												<td></td>
												<td><p>'.$freight_charges.'</p></td>
										  </tr>';
									}
								}
								else
								{
									$final_total=$final_total;
									//$freight_charges = '';
								}
								if($proforma['destination']!=111)
								{
									$final_total=$final_total-$proforma['discount'];
									
									$html.=
									    '<tr>
										<td></td>
										<td><div align="right"><strong>Discount</strong></div></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>'.round($proforma['discount'],3).'</td>
									  </tr>';
									  
									  $html.='<tr>
										<td></td>
										<td><div align="right"><strong>Sub Total</strong></div></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>'.round($final_total,3).'</td>
									  </tr>';
								
								}
								$gst = 0;
								if($proforma['destination']=='214')
								{
									$gst = (($final_total*$proforma['gst_tax'])/100);
									$html.='<tr>
										<td></td>
										<td><div align="right"><strong>Gst Tax ('.$proforma['gst_tax'].') % </strong></div></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>'.$gst.'</td>
									  </tr>';
								}
								
								$tax_price = 0;
								if($proforma['destination']=='42')
								{
									if(($proforma['gst']!='0.000' || $proforma['pst']!='0.000') && $proforma['hst']=='0.000')
	
									{
	
											$tax_gst = $final_total * ($proforma['gst'] / 100);  
	
											$tax_pst_price = $final_total * ($proforma['pst'] / 100);                      					
	
											$tax_price=$tax_gst+$tax_pst_price;
	
											
											$html.='<tr>
													<td></td>
													<td><div align="right"><strong>Gst Tax ('.$proforma['gst'].') % </strong></div></td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>'.$tax_gst.'</td>
												  </tr>';
											$html.='<tr>
													<td></td>
													<td><div align="right"><strong>Gst PST ('.$proforma['pst'].') % </strong></div></td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>'.$tax_pst_price.'</td>
												  </tr>';
									}
	
									else if($proforma['hst']!='0.000' && ($proforma['gst']=='0.000' && $proforma['pst']=='0.000'))
	
									{
	
											$tax_hst = $final_total * ($proforma['hst'] / 100); 
	
											$tax_price = $tax_hst;
											
											$html.='<tr>
													<td></td>
													<td><div align="right"><strong>HST Tax ('.$proforma['hst'].') % </strong></div></td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>'.$tax_hst.'</td>
												  </tr>';
											
									}
	
									else if($proforma['hst']=='0.000' && $proforma['gst']=='0.000' && $proforma['pst']=='0.000')
	
									{
	
											$tax_gst = $final_total * ($proforma['gst'] / 100);  
	
											$tax_pst_price = $final_total * ($proforma['pst'] / 100); 
	
											$tax_price=$tax_gst+$tax_pst_price;
	
											
											$html.='<tr>
													<td></td>
													<td><div align="right"><strong>GST Tax ('.$proforma['gst'].') % </strong></div></td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>'.$tax_gst.'</td>
												  </tr>';
										
											$html.='<tr>
													<td></td>
													<td><div align="right"><strong>PST Tax ('.$proforma['pst'].') % </strong></div></td>
													<td>&nbsp;</td>
													<td>&nbsp;</td>
													<td>'.$tax_pst_price.'</td>
												  </tr>';
									}
								}
								$Total_price = $gst+$final_total+$tax_price;
								$html.='<tr>
									<td></td>
									<td></td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								  </tr><tr>
									<td></td>
									<td></td>
									<td><p align="center"><b>'.$total.'</b></p></td>
									<td><p align="center">Total('.$currency['currency_code'].')</p></td>
									<td><p align="center"><b>'.($gst+$final_total+$tax_price).'</b></p></td>
								  </tr>';
							if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
					  		{
						 			
									/*if($invoice['product_id'] != '18' || $invoice['product_id'] != '10' || $invoice['product_id'] != '11')
									{
										$total_excies_rate = 0;
										$total_taxation = $final_total*$proforma['taxation_per']/100;echo $total_taxation;
									}
									else
									{
										$total_excies_rate = $final_total+($final_total*$proforma['excies_per']/100);
										$total_taxation = $total_excies_rate*$proforma['taxation_per']/100;
									}
									echo $total_excies_rate.'<br>'.$total_taxation.'tot<br>'.$final_total;
									echo $proforma['excies_per'];*/
									//printr($invoice);
									
									if($proforma['packing_charges']!=0)
									{
											$packing_charges=round($proforma['packing_charges'],3);
											if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']!=111)
											{
												$final_total=$final_total;
											}
											else
											{
												$final_total=$final_total+$packing_charges;
											
												$html .='<tr>
														<td></td>
															<td><div align="right">
																	<strong>Packing Charges </strong>
																	</div></td>
																 <td><b></b></td>
															<td></td>
															<td><p align="center">'.$packing_charges.'</p></td>
													  </tr>';
											}
									}
								 $html.='<tr>';
								 	//sonu add packing charge 19-1-2017
								
								//sonu  add 2/12/2016 remove || $invoice['product_id'] == '27' told by gopi di 14/2/2017 add 35 told by vivek
								if($invoice['product_id'] == '18' || $invoice['product_id'] == '10' || $invoice['product_id'] == '11' || $invoice['product_id'] == '35' )
								{
										$total_excies_rate = $final_total;
										$total_taxation = $final_total*$proforma['taxation_per']/100;
								  }
								
								 
								  else 
								  {	  
									 if($proforma['taxation']!='sez_no_tax')
										{
											 $html.='<td></td>
															<td><div align="right">
																	<strong>Excise '.$proforma['excies_per'].'%</strong>';
																	if($proforma['excies_per'] ==0)
																	{
																			
																		$html .='<br><span>( Against  '.str_replace("H Form,","",$proforma['tax_form_name']).'  )</span>';
																	}
														$html .='</div></td>
																 <td><b></b></td>
															<td></td>
															<td><p align="center">'.round(($final_total*$proforma['excies_per']/100),3).'</p></td>
													  </tr>
													  <tr>';
										}
										$total_excies_rate = $final_total+($final_total*$proforma['excies_per']/100);
										$total_taxation = $total_excies_rate*$proforma['taxation_per']/100;
								  }
								if($proforma['taxation']!='sez_no_tax')
								{
									$html.='<td></td>
										<td><div align="right">';
												if($proforma['taxation_per'] ==0)
													$html .='<strong>Tax  '.$proforma['taxation_per'].' %</strong><br><span>( Against H Form )</span>';
												else
												{
												
												$t_name=explode(' ',str_replace('_', ' ',$proforma['taxation']));
													$html .='<strong>'.$t_name[0].'  '.$proforma['taxation_per'].' %</strong>';
													if($proforma['taxation']=='cst_with_form_c')
														$html .='<br><span>( CST Against  Form C )</span>';
												}
												
												$html .='</div></td>
											 <td><b></b></td>
										<td></td>
										<td><p align="center">'.round($total_taxation).'</p></td>
								  </tr>';
								}
								else
								{
									$total_excies_rate=$final_total;
									$total_taxation =0;
								}
							if($proforma['freight_charges']!=0)
							{
								  $html .='<tr>
									<td></td>
										<td><div align="right">
												<strong>Freight Charges </strong>
												</div></td>
											 <td><b></b></td>
										<td></td>
										<td><p align="center">'.$proforma['freight_charges'].'</p></td>
								  </tr>';
								  }
							} 
					//printr($proforma['destination']);
							if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
					  		{
							  $html .='<tr>
												<td></td>
												<td><div align="right"><strong>Total</strong></div></td>
												<td></td>';
												$Total_price=($total_excies_rate+$total_taxation+$proforma['freight_charges']);
												    
												$html.='<td></td><td><p align="center">'.round($Total_price).'</p></td>
											</tr>';
							}
							$html .='</tbody>
							</table>
						</div>';
						
						
						
					 	if(isset($Total_price) && $Total_price!=0){  
 							$number = $this->convert_number(round($Total_price));
 						} else{
 	 						$number = $this->convert_number(round($final_total));
  						}
  						
						//printr($Total_price);				
			$html .='<div style="width: 100%; float: left;  border: 1px solid black;font-size: 16px;">
							<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;">
								<tbody><tr>
		<td colspan="2" valign="top"><strong>Amount Chargeable(In Words): '.$number.'{'.$currency['currency_code'].'}</strong></td>
	</tr>
	<tr>
		<td valign="top" width="50%"><div><strong>Declaration:</strong><br>We declare that this Invoice shows the actual price of the <br>goods described and that all particular are true and <br>correct.<br>';
		if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==214)
					{
						  $html.='<p>'.$user['note_invoice'].'</p>';
					}
		
		if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
		{
			$html .='<strong>Delivery schedule:</strong><br> All stock pouches will be ready in 10-15 days after the total invoice amount is transferred..<br> If the goods are ready we will send it asap. <br>Some colors or sizes can even take few days more for production.<br><b style="color:red"> NOTE : OUR ALL CONSIGNMENTS WILL COME "TO PAY"...<br>Please double check the color and size of product(s) before approval of proforma invoice; Hence company will not responsible for any change.</b></b>';
			
			//[kinjal] : added on [22-8-2016] onlu for custom printed products
			if($custom_pro_id=='1')
			{
				$html.='<br><br><b style="color:red">Packing Charges will be extra for per box. <br> Please note that in commercial production there can be variances in total output.  For a production run of 10,000 units, there can sometimes be a maximum over/under production variance of 3500 pouches per 10,000 bags. In such an event we will always provide a refund for any difference where we have a shortfall of pouches. However, in an instance where we have over-production upto the variance levels mentioned above, we ask that you kindly commit to pay for the additional volume. Please note that because of the commercial nature of the print process, sometimes slight shift registrations can occur.</b>';
			}
		}
		$html .='</div></td>
		<td valign="top" class="sign_td">
		<table border="0" align="right"  cellspacing="0px" cellpadding="0px" style=" border-spacing: 0px;" >
		<tr>
			<td width="50%"><p align="left">Signature &amp; Date:<br>For <strong>'.$sign.'</strong><br>
			<p style="text-align:right;margin-top:20px;margin-bottom:0;padding:0px;">'.$user_name['first_name'].' '.$user_name['last_name'].'</p><hr/>
				<p id="prefix" style="text-align:right;float:right;" >Authorised Signature</p>
			</td>
		</tr> 
	</table></td>
	</tr></tbody></table></div>';
			
$html .='<div style=" width: 100%;float: left;  border: 1px solid black;page-break-before: always;font-size: 16px;">
							<table cellspacing="0px" cellpadding="10px" border="1" style=" width:100%;">
								<tbody><tr>
									<td valign="top" colspan="2"><h1 align="center">BANK DETAIL</h1></td>
								</tr>
								<tr>
	 								<td colspan="2"><b>'.$currency['currency_code'].'</b></td>
								</tr>
								<tr>
									<td><b>Beneficiary Name</b></td>
									<td >'.$proforma['bank_accnt'].'</td>
								</tr>
								<tr>
									<td><b>Beneficiary Address</b></td>
									<td>'.$proforma['benefry_add'].'</td>
								</tr>
								<tr>
									<td><b>Beneficiary Bank Name</b></td>
									<td>'.$proforma['benefry_bank_name'].'</td>
								</tr>
								<tr>
									<td><b>Account Number</b></td>
									<td>'.$proforma['accnt_no'].'</td>
								</tr>';
								
							
								
								
								if($proforma['destination']!='42'){
									//printr($proforma); die;
								$html.='<tr>
									<td><b>IFSC Code</b></td>
									<td>'.$proforma['swift_cd_hsbc'].'</td>
								</tr>';
								
								$html.='<tr>
									<td><b>MICR Code</b></td>
									<td>'.$proforma['micr_code'].'</td>
								</tr>';
								}
								
								
								$html.='<tr>
									<td><b>Beneficiary Bank Address</b></td>
									<td>'.$proforma['benefry_bank_add'].'</td>
								</tr>';
						if($currency['currency_code']=='MXN'){ 
						$html .='<tr>
									<td><b>Clabe</b></td>
									<td>'.$proforma['clabe'].'</td>
								</tr>';
						}
						if($proforma['intery_bank_name']!=''){ 
						$html .='<tr>
									<td><b>Intermediary Bank Name</b></td>
									<td>'.$proforma['intery_bank_name'].'</td>
								</tr>';
						}
						if($proforma['hsbc_accnt_intery_bank']!=''){ 
						$html .='<tr>
								<td><b>Intermediary Bank</b></td>
								<td>'.$proforma['hsbc_accnt_intery_bank'].'</td>
								</tr>';
						}
						if($proforma['swift_cd_intery_bank']!=''){ 
						$html .='<tr>
								<td><b>Swift Code of Intermediary Bank</b></td>
								<td>'.$proforma['swift_cd_intery_bank'].'</td>
								</tr>';
						}
						if($proforma['hsbc_accnt_intery_bank']!=''){ 
						$html .='<tr>
							<td><b>Intermediary Bank ABA Routing Number</b></td>
							<td>'.$proforma['intery_aba_rout_no'].'</td>
						</tr>';
						}
					$html .='</tr></tbody></table></div>';
					if(isset($user['country_id']) && ( $user['country_id'] == '14' || $user['country_id'] == '214' || $user['country_id'] == '155'))
					{
						$html .='<div style=" border: 1px solid black;page-break-before: always;font-size: 16px;">'.
						$user['termsandconditions_invoice'].'<div>';
					}
		//printr($html);
		return $html ;
	}
	
		
		
		function oldviewProformaInvoice($proforma_id) {
			$html ='';
			$proforma=$this->getProformaData($proforma_id);
			$proforma_id=$proforma['proforma_id'];
			$proforma_inv=$this->getProformaInvoice($proforma_id);
			if($proforma['destination']==111)
				$title='Consignor';
			else
				$title='Exporter';
			$html .='<div class="panel-body font_medium">
			<table style="width:100%;cellpadding:0px;cellspacing:0px;" border="1">
			<tr>
				<th colspan="2" scope="col" style="font-size:20px;  text-align: center;">PROFORMA INVOICE</th>
			</tr>
			<tr>
				<td  valign="top" id="client">
					<b>'.$title.'<br>SWISS PAC PVT LTD</b><br>Padra Jambusar National highway<br />
					At Dabhasa vaillage,Pin 391440<br />Taluka.Padra, Dist.Vadodara(State Gujarat) India 
				</td>';
			$html .='<td>
			<table  border="0" cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px; width:100%;">
			<tr>
				<td valign="top"><b>Invoice No.&amp; Date</b></td>
				<td>'.$proforma['pro_in_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['invoice_date']).'<br><br><br><span class="barcode" >'.$proforma['pro_in_no'].'</span><br><br></td>
			</tr>
			<tr>
				<td><b>Proforma :</b></td>
				<td>&nbsp;'.dateFormat(4,$proforma['proforma']).'</td>
			</tr>
			<tr>
				<td ><b>Buyers Order No. &amp; Date:</b></td>
				<td>'.$proforma['buyers_order_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['buyers_date']).'</td>
			</tr>
			<tr>
				<td ><b>Country of origin of goods:</b></td>
				<td>'.$proforma['goods_country'].'</td>
			</tr>
			</table>
			</td>
			</tr>';
  		  $html .='<tr>
			<td rowspan="2" valign="top"><p><b>Consignee</b></p>
			<p>'.$proforma['customer_name'].'<br/>'.nl2br($proforma['address_info']).'<br/>Email : '.$proforma['email'].'</p>
			</td>
			<td >
			<table  border="0"  cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px; width: 100%">
		<tr>
			<td colspan="2" style="text-align:center"> <b>Terms of Delivery &amp; Payment</b></td>
		</tr>
		<tr>
			<td ><b>Delivery:</b></td>
			<td>'.$proforma['delivery_info'].'</td>
		</tr>
		<tr>
			<td><b>Mode Of Shipment:</b></td>
			<td>By '.ucwords(decode($proforma['transportation'])).'</td>
		</tr>
		<tr>
			<td><b>Payment Terms:</b></td>
			<td>'.$proforma['payment_terms'].'</td>
		</tr>
		</table>
		</td>
		</tr>';
 	  $html .='<tr>
		<td ><table border="0" style=" border-spacing: 0px; width:100%;">
	 <tr>
		<td><b>Port Of Loading:</b></td>
		<td><b>Final Destination:</b></td>
	 </tr>
	 <tr>';
	$con_id =$proforma['destination'];
	$countrys = $this->getCountry($con_id);

	$html .='<td>'.$proforma['port_loading'].'</td>
		<td>'.$countrys['country_name'].'</td>
	</tr>
	</table></td>
	</tr>';
  
  $currency = $this->getCurrencyId($proforma['currency_id']);
  $html .='<tr>
		<td colspan="2" valign="top" style="border-color: white;"><table  border="1"  cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px;">
	<tr>
		<td width="5%"><div align="center"><b>Sr. No</b></div></td>
		<td width="60%" ><div align="center"><b>Discription of Goods</b></div></td>
		<td width="10%"><b>Quantity In Units</b></td>
		<td width="15%"><b>Rate &nbsp;'.$currency['currency_code'].'</b></td>
		<td width="10%"><b>Amount &nbsp;'.$currency['currency_code'].'</b></td>
	</tr>';
	$total = 0;$total_rate=0; $final_total=0; $n=1;$total_excies_rate=0;$ex_per=array();$total_taxation_rate=0;$tax_per=array();$taxa=array();
	foreach($proforma_inv as $invoice_key=>$invoice){
	
		$getProductSpout = $this->getSpout(decode($invoice['spout']));
		$getProductZipper = $this->getZipper(decode($invoice['zipper']));
		$zipper_name='';
		$valve_name='';
		if($invoice['valve']=='With Valve')
			$valve_name=$invoice['valve'];
		if($getProductZipper['zipper_name']=='With zip')
			$zipper_name=$getProductZipper['zipper_name'];
		$getProductAccessorie = $this->getAccessorie(decode($invoice['accessorie']));
		if($invoice['product_id'] == 3)
		{
			$gusset = floatval($invoice['gusset']).'+'.floatval($invoice['gusset']);
		}
		else
		{
			$gusset = floatval($invoice['gusset']);
		}
		$quantity = $this->getColorDetails($proforma['proforma_id'],$invoice['proforma_invoice_id']);
										
  		$html .='<tr>
			<td >'.$n.'</td>
			<td ><b>Size : </b>'.floatval($invoice['width']).' mm &nbsp;Width &nbsp;X&nbsp;'.floatval($invoice['height']).' mm &nbsp;Height &nbsp;';
				if($gusset>0)
					$html .='X&nbsp;'.$gusset.' mm';
			
				if($invoice['volume']>0)
					$html .=' ('.$invoice['volume'].')';
					$html .='<br><b>Make up of pouch :</b> '.$invoice['product_name'].'&nbsp<b>'.$zipper_name.'&nbsp'.$valve_name.'</b><br>';
				foreach($quantity as $quantity_val) {
					//$colorName = $this->getColorName($quantity_val['color']);
					$clr_text='';
					if($quantity_val['color']=='-1')
					{
						//$quantity_val['color_name']='Custom';
						if($quantity_val['color_text']!='')
						$clr_text = "(".$quantity_val['color_text'] .")";
					}
					/*elseif($quantity_val['color']=='0')
					{
						$quantity_val['color_name']='Cylinder';
					}*/
					$html .='<b>Color : '.$quantity_val['color_name'].' '.$clr_text.'<br>';
					if($quantity_val['description']!='')
					{					
					$html .='Material Description : </b>'.$quantity_val['description'].'<br>';
					}
				}
				$html .='</td>
			<td><br><br>';
		foreach($quantity as $quantity_val) {
			$total = $total+$quantity_val['quantity'] ;$total_qty = $quantity_val['quantity'];
			$html .=$total_qty.'<br>';
		}
	$html .='</td>
		<td><br><br>';
		
	foreach($quantity as $rate_val) { 
		$total_rate=$total_rate+$rate_val['rate'];$total_rt = $rate_val['rate'];
		$html .=$total_rt.'<br>';
		
	}
	$html .='</td>
		<td><br><br>';
	foreach($quantity as $rate_val) {
	
		$total_amnt = $rate_val['quantity'] * $rate_val['rate'];
		$html.= $total_amnt.'<br>'; 
		$final_total=$final_total+$total_amnt;
	}
	$html .='</td>
	</tr>';
	  
	  $n++;}				
	  //}
      $html .='<tr>
        <td></td>
        <td></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>';
	  $html .='<tr>
        <td></td>
        <td></td>
        <td><p align="center"><b>'.$total.'</b></p></td>
        <td><p align="center">Total('.$currency['currency_code'].')</p></td>
        <td><p align="center"><b>'.$final_total.'</b></div></td>
      </tr>';
   	  if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
	  {
		 $total_excies_rate = $final_total+($final_total*$proforma['excies_per']/100);
		
		 $total_taxation = $total_excies_rate*$proforma['taxation_per']/100;
		  
	  $html .='
	   <tr>
    	<td></td>
        <td><div align="right">
				<strong>Excies '.$proforma['excies_per'].' %</strong>';
				if($proforma['excies_per'] ==0)
		{
			$html .='<br><span>( '.str_replace("H Form,","",$proforma['tax_form_name']).' is given )</span>';
		}
				$html .='</div></td>
   			 <td><b></b></td>
        <td></td>
        <td><p align="center">';
					$html .= round(($final_total*$proforma['excies_per']/100),3).'</p></td>
  </tr>';
	 
  $html .='
  <tr>
    <td></td>
        <td><div align="right">
				<strong>Tax  '.$proforma['taxation_per'].' %</strong>';
				if($proforma['taxation_per'] ==0)
					$html .='<br><span>( H Form is given)</span>';
					else
					$html .='<br><span>( '.str_replace('_', ' ',$proforma['taxation']).' )</span>';	
				$html .='
				</div></td>
   			 <td><b></b></td>
        <td></td>
        <td><p align="center">';
					$html .=round($total_taxation).'</p></td>
  </tr>';
   $html .='
  <tr>
    <td></td>
        <td><div align="right">
				<strong>Freight Charges </strong>
				</div></td>
   			 <td><b></b></td>
        <td></td>
        <td><p align="center">';
					$html .=round($proforma['freight_charges'],3).'</p></td>
  </tr>';
	 
  $html .='
  <tr>
					<td></td>
					<td><div align="right"><strong>Total</strong></div></td>
					<td></td>
					<td></td>';
					$Total_price=($total_excies_rate+$total_taxation+$proforma['freight_charges']);
					$html .='<td><p align="center">'.round($Total_price).'</p></td>
				</tr>';
	}
				$html .='
    </table></td>
  </tr>';
 if(isset($Total_price) && $Total_price!=0){  
 	$number = $this->convert_number(round($Total_price));
 } else{
 	 $number = $this->convert_number(round($final_total));
  }
  $user_name=$this->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
 //printr($user_name);
  //die;
 $html .='<tr>
		<td colspan="2" valign="top"><strong>Amount Chargeable(In Words): '.$number.'{'.$currency['currency_code'].'}</strong></td>
	</tr>
	<tr>
		<td valign="top" width="50%"><div><strong>Declaration:</strong><br>We declare that this Invoice shows the actual price of the <br>goods described and that all particular are true and <br>correct.
		</div></td>
		<td valign="top" class="sign_td">
		<table border="0" align="right"  cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px;" >
		<tr>
			<td width="50%"><p align="left">Signature &amp; Date:<br>For <strong>Swiss PAC PVT LTD</strong><br>
			<p style="text-align:right;margin-top:70px;margin-bottom:0;">'.$user_name['first_name'].' '.$user_name['last_name'].'</p><hr/>
				<p id="prefix" style="text-align:right;float:right;" >Authorised Signature</p><br />
			</td>
		</tr> 
	</table></td>
	</tr></tbody></table></div>';
  $html .='<div class="panel-body font_medium" ><table border="1" cellspacing="0px" cellpadding="10px" style=" width:100%;">
		<tr>
			<td colspan="2" valign="top"><h1 align="center">BANK DETAIL</h1></td>
		</tr>
		<tr>
			<td colspan="2"><b>'.$currency['currency_code'].'</b></td>
		</tr>
		<tr>
			<td><b>Beneficiary Name</b></td>
			<td >'.$proforma['bank_accnt'].'</td>
		</tr>
		<tr>
			<td><b>Beneficiary Address</b></td>
			<td>'.$proforma['benefry_add'].'</td>
		</tr>
		<tr>
			<td><b>Beneficiary Bank Name</b></td>
			<td>'.$proforma['benefry_bank_name'].'</td>
		</tr>
		<tr>
			<td><b>Account Number</b></td>
			<td>'.$proforma['accnt_no'].'</td>
		</tr>
		
		
			<td><b>IFSC Code</b></td>
			<td>'.$proforma['swift_cd_hsbc'].'</td>
		</tr>
		<tr>
			<td><b>MICR Code</b></td>
			<td>'.$proforma['micr_code'].'</td>
		</tr>
		<tr>
			<td><b>Beneficiary Bank Address</b></td>
			<td>'.$proforma['benefry_bank_add'].'</td>
		</tr>';
			if($proforma['intery_bank_name']!=''){ 
		$html .='<tr>
			<td><b>Intermediary Bank Name</b></td>
			<td>'.$proforma['intery_bank_name'].'</td>
		</tr>';
			}
		if($proforma['hsbc_accnt_intery_bank']!=''){ 
		$html .='<tr>
			<td><b>Intermediary Bank</b></td>vi
			<td>'.$proforma['hsbc_accnt_intery_bank'].'</td>
		</tr>';
		}
		if($proforma['swift_cd_intery_bank']!=''){ 
		$html .='<tr>
			<td><b>Swift Code of Intermediary Bank</b></td>
			<td>'.$proforma['swift_cd_intery_bank'].'</td>
		</tr>';
		}
		if($proforma['hsbc_accnt_intery_bank']!=''){ 
		$html .='<tr>
			<td><b>Intermediary Bank ABA Routing Number</b></td>
			<td>'.$proforma['intery_aba_rout_no'].'</td>
		</tr>';
		}
	$html .='</table></div>';
		return $html ;
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
	public function approveordis($data)
	{
		$sql="INSERT INTO `" . DB_PREFIX . "proforma_history` SET proforma_id='".$data['proforma_id']."',appr_disapp_status='".$data['val']."',description='".$data['description']."',
		app_dis_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',app_dis_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',app_dis_date='".$data['app_dis_date']."'";
		//echo $sql;
		$data=$this->query($sql);
		return $data; 
	
	}
	public function getappdisdata($proformaid,$limit)
	{
		$sql = "SELECT * FROM proforma_history WHERE proforma_id='".$proformaid."' ORDER BY proforma_his_id DESC ".$limit;
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	
	}
	public function getCompanyAdd($user_type_id,$user_id)
	{
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
		$sql="SELECT ib.company_address,ib.company_name,ib.vat_no,ib.termsandconditions_invoice,ib.note_invoice,a.country_id FROM international_branch as ib,address as a WHERE ib.international_branch_id='".$set_user_id."' AND ib.is_delete='0' AND a.address_id=ib.address_id";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->row;
		else
			return false;
		
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
	public function stateDetail($state_id)
	{
		$sql = "Select * From taxation_canada WHERE taxation_canada_id = '".$state_id."'";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->row;
		} else {
			return false;
		}
	}
	public function getCustomerDetail($customer_name)
	{
		//[kinjal] : changed query on 13-4-2017 
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];

		if($user_type_id == 2){

			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");

			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

			$set_user_id = $parentdata->row['user_id'];

			$set_user_type_id = $parentdata->row['user_type_id'];
			
			$str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			
			$sql = "SELECT aa.address_book_id,aa.vat_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.company_name LIKE '%".strtolower($customer_name)."%' AND ((aa.user_id='".$set_user_id ."' AND aa.user_type_id='".$set_user_type_id ."') $str  )  GROUP BY aa.address_book_id";

		}
		else if($user_type_id == 4)
		{

			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);

			$set_user_id = $user_id;

			$set_user_type_id = $user_type_id;
				$str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
				
			$sql = "SELECT aa.address_book_id,aa.vat_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.company_name LIKE '%".strtolower($customer_name)."%' AND ((aa.user_id='".$set_user_id ."' AND aa.user_type_id='".$set_user_type_id ."') $str  )  GROUP BY aa.address_book_id";

		}

		else

		{

			$set_user_id = $user_id;

			$set_user_type_id  = $user_type_id;
			
			$sql = "SELECT aa.address_book_id,aa.vat_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.company_name LIKE '%".strtolower($customer_name)."%' GROUP BY aa.address_book_id";
		}

		$str='';

		//if($userEmployee){

			//$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			
		//	$sql = "SELECT a.address_book_id,a.company_name,a.vat_no,a.user_id,a.user_type_id,ca.company_address_id,ca.c_address,ca.email_1 ,fa.factory_address_id,fa.f_address FROM address_book_master as a , company_address as ca  ,factory_address as fa  WHERE  a.address_book_id = ca.address_book_id AND a.address_book_id = fa.address_book_id AND a.company_name LIKE '%".$customer_name."%' AND ((a.user_id='".$set_user_id ."' AND a.user_type_id='".$set_user_type_id ."') $str  )";
	

	//	}	

		//$sql = "SELECT * FROM `" . DB_PREFIX . "xero_customer` WHERE name LIKE '%".$customer_name."%' AND ((user_id='".$set_user_id ."' AND user_type_id='".$set_user_type_id ."') $str  )";
			
//	echo $sql;
		
		
		$data = $this->query($sql);
		//printr($data);

		if($data->num_rows){

			return $data->rows;

		}else{

			return false;

		}		
	
		
	
	}
	//[sonu] onb 12/11/2016
	public function getproductprice($size,$valve,$zipper,$spout,$accessorie,$color)
	{  
		$sql="SELECT color_value FROM  pouch_color WHERE pouch_color_id='".$color."'";
	    $data=$this->query($sql);
	//	printr($data);
		
		if($data->row['color_value'] == '1'){
			$colorprice='all_clr_price';
		}
		elseif($data->row['color_value']=='2'){
			$colorprice='clear_price';
		}
		elseif($data->row['color_value']=='3'){
			$colorprice='biodegradable_price';
		}
		elseif($data->row['color_value']=='4'){
			$colorprice='ultra_clear_price';
		}
		elseif($data->row['color_value']=='5'){
			$colorprice='sup_zz_oval_window';
		}
		elseif($data->row['color_value']=='5'){
			$colorprice='sup_zz_oval_window';
		}
		elseif($data->row['color_value']=='6'){
			$colorprice='stripped_bkp_look_zz';
		}
		elseif($data->row['color_value']=='7'){
			$colorprice='sup_zz_jtk';
		}
		elseif($data->row['color_value']=='8'){
			$colorprice='sup_bkp_zz';
		}
		elseif($data->row['color_value']=='9'){
			$colorprice='sup_bkp_zz_oval_window';
		}
		elseif($data->row['color_value']=='10'){
			$colorprice='sup_bkp_whp_zz_full_rec_win';
		}
		elseif($data->row['color_value']=='11'){
			$colorprice='sup_zz_clear_bkp';
		}
		elseif($data->row['color_value']=='12'){
			$colorprice='sup_crystal_clear_price';
		}
		elseif($data->row['color_value']=='13'){
			$colorprice='sup_whp_zz';
		}
		elseif($data->row['color_value']=='14'){
			$colorprice='sup_gp_bp_zz';
		}
		elseif($data->row['color_value']=='15'){
			$colorprice='sup_gp_bp_zz_full_rect';
		}
		else
		{
			$colorprice='';
		}
		//printr($colorprice);
		$sql1="SELECT ".$colorprice." as price FROM product_price_list WHERE zipper_id='".decode($zipper)."' AND size_id='".decode($size)."' AND spout_id ='".decode($spout)."' AND accessorie_id='".decode($accessorie)."'";
	  //  echo $sql1;//die;
		
		$data1 = $this->query($sql1);
		if($data1->num_rows) {
			
			if($valve=='With Valve')
			{
				$price = $data1->row['price']+3;
				return $price;
			}
			else
			    return $data1->row['price'];
		} else {
			return false;
		}
	}
	
	
	  public function getLastIdAddress() {
        $sql = "SELECT address_book_id FROM  address_book_master ORDER BY address_book_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row;
        } else {
            return false;
        }
    }
	
		//sonu add function 17-6-2017
	public function UpdateTotalInvoicePrice($proforma_id)
	{
		$proforma=$this->getProformaData($proforma_id);
			//printr($proforma);die;
			$proforma_id=$proforma['proforma_id'];
			$proforma_inv=$this->getProformaInvoice($proforma_id);
			//printr($proforma_inv);
			$user_name=$this->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
			//printr($user_name);
			$show_vat='';
			$admin_vat_no='';
		
			
			 $con_id =$proforma['destination'];
			 $countrys = $this->getCountry($con_id);
			 $currency = $this->getCurrencyId($proforma['currency_id']);
					
				$total = 0;$total_rate=0; $final_total=0; $n=1;$total_excies_rate=0;$ex_per=array();$total_taxation_rate=0;$tax_per=array();$taxa=array();
					
						$custom_pro_id=0;
						foreach($proforma_inv as $invoice_key=>$invoice){
							
									$getProductSpout = $this->getSpout(decode($invoice['spout']));
									$getProductZipper = $this->getZipper(decode($invoice['zipper']));
									$zipper_name='';
									$valve_name='';
									$spout_name= $acc_name='';
									if($invoice['valve']=='With Valve')
										$valve_name=$invoice['valve'];
									//if($getProductZipper['zipper_name']!='No zip')
										$zipper_name=$getProductZipper['zipper_name'];
									if($getProductSpout['spout_name']!='No Spout')
										$spout_name=$getProductSpout['spout_name'];
									$getProductAccessorie = $this->getAccessorie(decode($invoice['accessorie']));	
								///printr($getProductAccessorie);
									if($getProductAccessorie['product_accessorie_name']!='No Accessorie')	
								        $acc_name=$getProductAccessorie['product_accessorie_name'];
										
									if($invoice['product_id'] == 3)
									{
										$gusset = floatval($invoice['gusset']).'+'.floatval($invoice['gusset']);
									}
									else
									{
										$gusset = floatval($invoice['gusset']);
									}
									$quantity = $this->getColorDetails($proforma['proforma_id'],$invoice['proforma_invoice_id']);
							
												foreach($quantity as $quantity_val) { 
											
													$clr_text='';
													if($quantity_val['color']=='-1')
													{
														$custom_pro_id = '1';
														if($quantity_val['color_text']!='')
														$clr_text = "(".$quantity_val['color_text'] .")";
													}
                                                                                                        //printr($quantity_val['color_name']);
									
									}
								
									
									foreach($quantity as $quantity_val) {
						
										$total = $total+$quantity_val['quantity'] ;$total_qty = $quantity_val['quantity'];
									
									}
                                                                        //printr($quantity_val['quantity']);
								
									foreach($quantity as $rate_val) { 
					
										$total_rate=$total_rate+$rate_val['rate'];$total_rt = $rate_val['rate'];
									
									}
								
									foreach($quantity as $rate_val) {
										$total_amnt = $rate_val['quantity'] * $rate_val['rate'];
								
										$final_total=$final_total+$total_amnt;
									}
								
										if($invoice['product_id']=='18')
											{
												
														foreach($quantity as $quantity_val) {
											
															$total = $total+$quantity_val['quantity']  ; 
															$total_qty = $quantity_val['quantity'] * 2;
														
														}
														
														foreach($quantity as $rate_val) { 
											
															$total_rate=$rate_val['rate'] * 0 ;
														
															
														}
														
													
														foreach($quantity as $rate_val) {
															$total_amnt = $rate_val['quantity'] *0;
														
															$final_total=$final_total+$total_amnt;
														}
											
											
										}
									$n++;
						}
									
								
								if($proforma['destination']!=111)
								{
									$final_total=$final_total-$proforma['discount'];
									
								
								}
								$gst = 0;
								if($proforma['destination']=='214')
								{
									$gst = (($final_total*$proforma['gst_tax'])/100);
								
								}
								
								$tax_price = 0;
								if($proforma['destination']=='42')
								{
									if(($proforma['gst']!='0.000' || $proforma['pst']!='0.000') && $proforma['hst']=='0.000')
	
									{
	
											$tax_gst = $final_total * ($proforma['gst'] / 100);  
	
											$tax_pst_price = $final_total * ($proforma['pst'] / 100);                      					
	
											$tax_price=$tax_gst+$tax_pst_price;
	
									}
	
									else if($proforma['hst']!='0.000' && ($proforma['gst']=='0.000' && $proforma['pst']=='0.000'))
	
									{
	
											$tax_hst = $final_total * ($proforma['hst'] / 100); 
	
											$tax_price = $tax_hst;
									
									}
	
									else if($proforma['hst']=='0.000' && $proforma['gst']=='0.000' && $proforma['pst']=='0.000')
	
									{
	
											$tax_gst = $final_total * ($proforma['gst'] / 100);  
	
											$tax_pst_price = $final_total * ($proforma['pst'] / 100); 
	
											$tax_price=$tax_gst+$tax_pst_price;
	
										
									}
								}
								$Total_price = $gst+$final_total+$tax_price;
							
							if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
					  		{
											
												if($proforma['packing_charges']!=0)
												{
														$packing_charges=round($proforma['packing_charges'],3);
														
														if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']!=111)
														{
															$final_total=$final_total;
															
														}
														else
														{
															$final_total=$final_total+$packing_charges;
															
														
														}
												}
										
												// add by sonu told by archna mem & parul mem 2-5-2017
												
													//	if($proforma['freight_charges']!=0)
											//	{
													//	$freight_charges=round($proforma['freight_charges'],3);
														
													//	if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']!=111)
												//		{
													//		$final_total=$final_total;
															
													//	}
													//	else
													//	{
													//		$final_total=$final_total+$freight_charges;
															
												
														
													//	}
											//	}
												
											
											
											if($invoice['product_id'] == '18' || $invoice['product_id'] == '10' || $invoice['product_id'] == '11' )
											{
													$total_excies_rate = $final_total;
													$total_taxation = $final_total*$proforma['taxation_per']/100;
											  }
											  else 
											  {
													$total_excies_rate = $final_total+($final_total*$proforma['excies_per']/100);
													$total_taxation = $total_excies_rate*$proforma['taxation_per']/100;
											  }
											if($proforma['taxation']=='sez_no_tax')
											{
												$total_excies_rate=$final_total;
												$total_taxation =0;
											}
										
										if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
										{
											$Total_price=($total_excies_rate+$total_taxation+$proforma['freight_charges']);
															//$Total_price=($total_excies_rate+$total_taxation);
														
													
										}
									
									
									
									if(isset($Total_price) && $Total_price!=0){  
										$number = $this->convert_number(round($Total_price));
									} else{
										$number = $this->convert_number(round($final_total));
									}
								
							}
								
									
						//	printr($Total_price);	die;
							
							   $sql = "UPDATE `" . DB_PREFIX . "proforma` SET invoice_total = " .round($Total_price). " WHERE proforma_id=" .$proforma_id;
       						   $this->query($sql);
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
	
	
}
?>