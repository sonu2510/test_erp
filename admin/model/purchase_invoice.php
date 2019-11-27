<?php
class purchase_invoice extends dbclass{	
	
	public function addInvoice($data=array())
	{
		//printr($data);die;
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		if(isset($data['invoice_id']) && !empty($data['invoice_id'])) {
		$invoice_id = $data['invoice_id'];
		} else
		{
			if(isset($data['form']))
				$form=implode(",",$data['form']);
			else
				$form='';				
		
		$sql = "INSERT INTO `" . DB_PREFIX . "purchase_invoice` SET invoice_no = '".$data['invoiceno']."',invoice_date = '" .$data['invoicedate']. "',
		exporter_orderno = '".$data['ref_no']."',gst='0', buyers_orderno ='".$data['buyersno']."',consignee='".addslashes($data['consignee'])."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',	port_load='".$data['portofload']."',final_destination='".$data['country_id']."', payment_terms='".$data['payment_terms']."',postal_code = '".$data['postal_code']."',region = '".$data['region']."',type = '".$data['type']."',tax_type = '".$data['tax_type']."',freight_charge = '".$data['freight_charge']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',curr_id='".$data['currency']."',status = '".$data['status']."',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',transportation='".$data['transportation'][0]."',is_delete=0";
		$datasql=$this->query($sql);
		$invoice_id = $this->getLastId();
		}
		$sql2 = "Insert into purchase_invoice_product Set invoice_id='".$invoice_id."',product_code_id='".$data['product_code_id']."',without_freight_charge_rate ='".$data['color'][0]['rate']."', qty = '".$data['color'][0]['qty']."', rack_status = '".$data['color'][0]['qty']."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 
		$data2=$this->query($sql2);
		$invoice_product_id = $this->getLastId();
		
		return $invoice_id;
	}

	public function getInvoiceData($invoice_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "purchase_invoice  WHERE invoice_id = '" .(int)$invoice_id. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getInvoiceNetData($invoice_id)
	{
		$sql = "SELECT  i.* FROM  " . DB_PREFIX . "purchase_invoice as i,purchase_invoice_product as ip WHERE i.invoice_id =ip.invoice_id  AND i.invoice_id = '" .(int)$invoice_id. "' AND i.is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getUserList(){
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
		$data = $this->query($sql);
		return $data->rows;
	}
	
	public function getInvoiceTotalData($invoice_id)
	{
		$sql = "SELECT SUM(ic.qty) as total_qty,SUM(ic.rate) as total_rate,ic.rate,SUM(ic.qty*ic.rate) as tot FROM  " . DB_PREFIX . "purchase_invoice as i,purchase_invoice_product as ic WHERE i.invoice_id = '" .(int)$invoice_id. "' 
		AND i.is_delete=0 AND i.invoice_id=ic.invoice_id";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getInvoiceProductId($invoice_product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "purchase_invoice_product` WHERE invoice_product_id = '" .(int)$invoice_product_id. "'  AND is_delete=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	
	public function sendInvoiceEmail($invoice_no,$status,$to_email,$url='')
	{
		$invoice=$this->getInvoiceData($invoice_no);
		$html ='';				
		$html .=$this->viewInvoiceForIB($status,$invoice_no);
		$html .='<link rel="stylesheet" href="'.HTTP_SERVER.'css/invoice.css">';	
		$addedByinfo=$this->getUser($invoice['user_id'],$invoice['user_type_id']);
		$subject = 'Invoice Details';  
		$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);
		$email_temp[]=array('html'=>$html,'email'=>$to_email);
		$email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);
		$form_email=$addedByinfo['email'];
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(1); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
		$path = HTTP_SERVER."template/proforma_invoice.html";
		$output = file_get_contents($path);  
		$search  = array('{tag:header}','{tag:details}');
		$signature = 'Thanks.';
		foreach($email_temp as $val)
		{
			$toEmail =$form_email;
			$firstTimeemial = 1;								
			$message = '';
			if($val['html'])
			{
				$tag_val = array(
				"{{header}}"=>'Invoice Detail',
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
		//send_email($val['email'],$form_email,$subject,$message,'',$url);
		}
	}
	
	public function getUser($user_id,$user_type_id)
	{
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.company_address,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			
			$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getCityName()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_city` WHERE is_delete = '0' ORDER BY invoice_city_id ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCountry()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE is_delete = '0'  ORDER BY country_id ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getCurrency()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "currency` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function getColorDetails($invoice_id,$invoice_product_id) 
	{	
		$sql = "select ic.* from `".DB_PREFIX."purchase_invoice_product` as ic WHERE invoice_id='".$invoice_id."' AND invoice_product_id = '".$invoice_product_id."'";
		$data = $this->query($sql);
		if($data->num_rows) {
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
	
	public function checkInvoiceNo($invoice_no)
	{
		$sql = "SELECT invoice_no FROM `" . DB_PREFIX . "purchase_invoice` WHERE invoice_no = '" .(int)$invoice_no. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function removeInvoice($invoice_product_id,$invoice_id)
	{
		$sql = $this->query("DELETE FROM purchase_invoice_product  WHERE `invoice_product_id` = '".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
		
		//[kinjal] :  to update freight charge rate (13-2-2016)
		$this->changeRateByFrieght($invoice_id); 
	}
	
	public function checkProductZipper($product_id)
	{
		$data = $this->query("SELECT zipper_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['zipper_available'];	
		}else{
			return false;
		}
	}
	
	public function updateInvoiceRecord($data)	
	{
		//printr($data);die;
		$inv_id = $data['invoice_id'];
		if(isset($data['form']))
			$form=implode(",",$data['form']);
		else
			$form='';
		
		$sql = "UPDATE `" . DB_PREFIX . "purchase_invoice` SET invoice_no='".$data['invoiceno']."',invoice_date = '" .$data['invoicedate']. "',exporter_orderno = '".$data['ref_no']."',gst='0', buyers_orderno ='".$data['buyersno']."',consignee='".addslashes($data['consignee'])."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',port_load='".$data['portofload']."',final_destination='".$data['country_id']."', payment_terms='".$data['payment_terms']."',curr_id='".$data['currency']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',status = '".$data['status']."',postal_code = '".$data['postal_code']."',region = '".$data['region']."',type = '".$data['type']."',tax_type = '".$data['tax_type']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',freight_charge = '".$data['freight_charge']."',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',transportation='".$data['transportation'][0]."',is_delete=0 WHERE invoice_id = '".$data['invoice_id']."'";
		$data = $this->query($sql);
		
		//[kinjal] :  to update freight charge rate (13-2-2016)
		$this->changeRateByFrieght($inv_id); 
		
		return $data;
	}
	
	public function updateInvoiceProduct($data)
	{		
		$sql1 = "UPDATE `".DB_PREFIX."purchase_invoice_product` SET invoice_id='".$data['invoice_id']."',product_code_id='".$data['product_code_id']."', without_freight_charge_rate ='".$data['color'][0]['rate']."', qty = '".$data['color'][0]['qty']."', rack_status = '".$data['color'][0]['qty']."',date_added = NOW(), date_modify = NOW(), is_delete = 0 Where invoice_product_id = '".$data['pro_id']."'";
		$data1 = $this->query($sql1);
	}
	
	public function getCountryName($country_id)
	{
		$sql = "SELECT country_name FROM `" . DB_PREFIX . "country` WHERE country_id='".$country_id."' ";
		$sql .= " ORDER BY country_id";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCountryId($country_name)
	{
		$sql = "SELECT country_id FROM `" . DB_PREFIX . "country` WHERE country_name='".$country_name."' ";
		$sql .= " ORDER BY country_id";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCityNameAgain($city_name)
	{
		$sql = "SELECT city_name FROM `" . DB_PREFIX . "invoice_city` WHERE invoice_city_id='".$city_name."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getCityId($city_name)
	{
		$sql = "SELECT invoice_city_id FROM `" . DB_PREFIX . "invoice_city` WHERE city_name='".$city_name."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCurrencyName($curr_id)
	{
		$sql = "SELECT currency_code FROM `" . DB_PREFIX . "currency` WHERE currency_id = '".$curr_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	
	
	public function getCurrencyId($currency_code)
	{
		$sql = "SELECT currency_code FROM `" . DB_PREFIX . "currency` WHERE currency_code = '".$currency_code."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	

	public function getProductdeatils($invoice_no)
	{
		$sql="SELECT * FROM purchase_invoice_product WHERE invoice_id='".$invoice_no."'";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	
	public function invoiceArrayForCSV($invoice_nos)
	{	
		$i=0;
		foreach($invoice_nos as $invoice_no)
		{
		
			$invoice=$this->getInvoiceNetData($invoice_no);
			$currency=$this->getCurrencyName($invoice['curr_id']);	
			$alldetails=$this->getInvoiceProduct($invoice_no);
			$fixdata = $this->getFixmaster(); 
			$country=$this->getCountryName($invoice['final_destination']);
			//$city=$this->getCityNameAgain($invoice['port_load']);
			$invoice['consignee']=isset($invoice['consignee'])?$invoice['consignee']:'';
			$address = explode(' ',str_replace("\n", " ", $invoice['consignee']));
			$part = ceil(count($address) / 4);
			$address1 = implode(' ', array_slice($address, 0, $part));
			$address2 = implode(' ', array_slice($address, $part, $part));
			$address3 = implode(' ', array_slice($address, $part * 2));
			$address4 = implode(' ', array_slice($address, $part * 4));

			$subtotal[$invoice['invoice_no']]=0;$tot_tax[$invoice['invoice_no']]=0; $zipper_name='';   $valve='';  
		//printr($invoice);die;
			foreach($alldetails as $details)
			{
				$product_code_details=$this->getProductCode($details['product_code_id']);
				//$color = $this->getColorDetails($invoice_no,$details['invoice_product_id']); 			
				//foreach($color as $color_val)
				//{
				//	$zipper=$this->getZipper(decode($details['zipper']));
				//	if($zipper['zipper_name']!='No zip')
				//		$zipper_name=$zipper['zipper_name'];
				//	if($details['valve']!='No Valve')
				//	 	$valve=$details['valve'];
				
					$tot[$invoice['invoice_no']]=$details['qty']*$details['rate'];
					$tot_tax[$invoice['invoice_no']]=($tot_tax[$invoice['invoice_no']]+(($tot[$invoice['invoice_no']]*$invoice['gst'])/100));
					$subtotal[$invoice['invoice_no']]=$subtotal[$invoice['invoice_no']]+$tot[$invoice['invoice_no']];
					
					$input_array[$i++] = 
									Array('*ContactName'=>$invoice['customer_name'],
										'EmailAddress'=>$invoice['email'],
										'POAddressLine1'=>$address1,
										'POAddressLine2'=>$address2,
										'POAddressLine3'=>$address3,
										'POAddressLine4'=>$address4,
										'POCity'=>$invoice['port_load'],
										'PORegion'=>$invoice['region'],
										'POPostalCode'=>$invoice['postal_code'],
										'POCountry'=>$country['country_name'],
										'*InvoiceNumber'=>$invoice['invoice_no'],
										'Reference'=>$invoice['exporter_orderno'],
										'*InvoiceDate'=>date("d-M-y",strtotime($invoice['invoice_date'])),
										'*DueDate'=>date("d-M-y",strtotime($invoice['invoice_date'])),
										'PlannedDate'=>'',
										'Total'=>'',
										'TaxTotal'=>'',
										'InvoiceAmountPaid'=>0,
										'InvoiceAmountDue'=>'',
										'InventoryItemCode'=>$product_code_details['product_code'],
										'*Description'=>$product_code_details['description'],
										'*Quantity'=>$details['qty'],
										'*UnitAmount'=>$details['rate'],
										'Discount'=>0,
										'LineAmout'=>$tot[$invoice['invoice_no']],
										'*AccountCode'=>$invoice['account_code'],
										'*TaxType'=>$invoice['tax_type'],
										'TaxAmount'=>($tot[$invoice['invoice_no']]*($invoice['gst']/100)),
										'TrackingName1'=>'',
										'TrackingOption1'=>'',
										'TrackingName2'=>'',
										'TrackingOption2'=>'',
										'Currency'=>$currency['currency_code'],
										'Type'=>$invoice['type'],
										'Sent'=>$invoice['sent'],
										'Status'=>$invoice['invoice_status'],									
								); 
				//}
			
			}
			$in_no=0;
			foreach($input_array as $key=>$in_arr)
			{
				$input_array[$key]['TaxTotal']= $tot_tax[$input_array[$key]['*InvoiceNumber']];
				$input_array[$key]['Total']= $subtotal[$input_array[$key]['*InvoiceNumber']]+$tot_tax[$input_array[$key]['*InvoiceNumber']];
				$input_array[$key]['InvoiceAmountDue']= $subtotal[$input_array[$key]['*InvoiceNumber']]+$tot_tax[$input_array[$key]['*InvoiceNumber']];
			}
		}
		return $input_array;
	}
	public function InsertCSVData($handle)
	{
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$data=array();
		$first_time = true;
		$invoice_no='';
		$ibInfo = $this->getUser($user_id,$user_type_id);
		//printr($ibInfo);
		//die;
		$qty_new = 0;
		if($user_id==1 && $user_type_id==1)
		{
			$addedByInfo['company_address']=$ibInfo['address'];
			$addedByInfo['bank_address']='';
		}
		else
		{
			$addedByInfo['company_address']=$ibInfo['company_address'];
			$addedByInfo['bank_address']=$ibInfo['bank_address'];
		}
		
	  	//loop through the csv file 
		while($data = fgetcsv($handle,1000,","))
		{
			//printr($data);//die;
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
				$country=$this->getCountryId($data[9]);
				//$city=$this->getCityId($data[6]);
				$Currency=$this->getCurrencyId($data[32]);
				$product_code_id=$this->getProductCodeId($data[19]);
				
				$contactName=$data[0];
				$emailAddress=$data[1];
				$address=$data[2].' <br> '.$data[3].' <br> '.$data[4].' <br> '.$data[5];
				//$POCity=$data[6];
				$PORegion=$data[7];
				$POPostalCode=$data[8];
				//$POCountry=$data[9];
				$InvoiceNumber=$data[10];
				$Reference=$data[11];
				$InvoiceDate=date("Y-m-d",strtotime($data[12]));
				$DueDate=date("Y-m-d",strtotime($data[13]));
				$PlannedDate=$data[14];
				$Total=$data[15];
				$TaxTotal=$data[16];
				$InvoiceAmountPaid=$data[17];
				$InvoiceAmountDue=$data[18];
				$InventoryItemCode=$data[19];
				$Description=$data[20];
				$Quantity=$data[21];
				$UnitAmount=$data[22];
				$Discount=$data[23];
				$LineAmout=$data[24];
				$AccountCode=$data[25];
				$TaxType=$data[26];
				$TaxAmount=$data[27];
				$TrackingName1=$data[28];
				$TrackingOption1=$data[29];
				$TrackingName2=$data[30];
				$TrackingOption2=$data[31];
				$city=$data[6];
				//$Currency=$data[32];
				$Type=$data[33];
				$Sent=$data[34];
				$Status=$data[35];
				if($data[24]!=0 && $data[27]!=0)
				{
					$gst=$data[27]/$data[24]*100;
				}
				else
				{
					$gst=0;
				}
				
				/*$freight_charge =;
				if($freight_charge != '0' || $freight_charge != '')
				{
					
				}
				else
				{
					
				}*/
				//echo $gst .'='.$data[27].'/'.$data[24].'*100';
				//echo $gst;
				//die;
			if($invoice_no!=$data[10])
			{
				$qty_total = 0;
				$invoice_no=$data[10];
				$sql = "INSERT INTO `" . DB_PREFIX . "purchase_invoice` SET invoice_no = '".$InvoiceNumber."',invoice_date = '" .$InvoiceDate. "',
		exporter_orderno = '".$Reference."',gst='".$gst."', buyers_orderno ='".$Reference."',consignee='".addslashes($address)."',company_address='".addslashes($addedByInfo['company_address'])."',bank_address='".addslashes($addedByInfo['bank_address'])."',country_destination='".$country['country_id']."',vessel_name='',customer_name = '".addslashes($contactName)."', email = '".$emailAddress."',port_load='".$city."',final_destination='".$country['country_id']."',payment_terms='',postal_code = '".$POPostalCode."',region = '".$PORegion."',type = '".$Type."',tax_type = '".$TaxType."',account_code = '".$AccountCode."',sent = '".$Sent."',invoice_status = '".$Status."',curr_id='".$Currency['currency_code']."',status = 1,date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0";		
		
			//	echo $sql.'<br><br> INOICE Product <br><br>';
				$datasql=$this->query($sql);
				$invoice_id = $this->getLastId();
				//$invoice_id=1;
			}
			/*if($invoice_no==$data[10])
			{	
				$qty_total = $qty_total + $Quantity;
				echo $qty_total.'<br> rate = '.$UnitAmount.'<br>';
				echo $qty_new = $qty_total;
			}*/
			
			$sql2 = "Insert into purchase_invoice_product Set invoice_id='".$invoice_id."',product_code_id='".$product_code_id['product_code_id']."',rate ='".$UnitAmount."',without_freight_charge_rate ='".$UnitAmount."', qty = '".$Quantity."', rack_status = '".$Quantity."', date_added = NOW(), date_modify = NOW(), is_delete = 0"; 
			$data2=$this->query($sql2);
		}
		//printr($qty_new);
		return $invoice_id;
	}
	public function viewInvoiceForIB($status,$invoice_no)
	{	
		
		$invoice=$this->getInvoiceNetData($invoice_no);
		$currency=$this->getCurrencyName($invoice['curr_id']);	
		$alldetails=$this->getInvoiceProduct($invoice_no);
		//printr($invoice);
		//$image= HTTP_UPLOAD."admin/store_logo/logo.png";
		//printr($image);
		
		
		if($invoice['user_type_id'] == '1' && $invoice['user_id'] =='1')
		{
			$image= HTTP_UPLOAD."admin/store_logo/logo.png";
			$img = '<img src="'.$image.'" alt="Image">';
		}
		else
		{
			if($invoice['user_type_id'] == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$invoice['user_id']."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($invoice['user_type_id'],$invoice['user_id']);
				$set_user_id = $invoice['user_id'];
				$set_user_type_id = $invoice['user_type_id'];
			}
			
			$data=$this->query("SELECT logo FROM international_branch WHERE international_branch_id = '".$set_user_id."'");
			//echo "SELECT logo FROM international_branch WHERE international_branch_id = '".$set_user_id."'";
			if($data->row['logo'] != '')
			{
				$image= HTTP_UPLOAD."admin/logo/200_".$data->row['logo'];
				//echo $image;
				$img = '<img src="'.$image.'" alt="Image">';
			}
			else
			{
				$img ='';
			}			
		}
		
		$html='';                       
  		$html.='<style>table {border-collapse: collapse;}td{padding: 6px;}
				.line-dashed {
				  border-style: dashed;
				  background: transparent;
				}
				.line {
				  height: 2px;
				  margin: 10px 0;
				  font-size: 0;
				  overflow: hidden;
				  background-color:#fff;
				  border-width: 0;
				  border-top: 1px solid #e0e4e8;
				}</style>
				<div id="print_div" style="padding-top: 0px;width:754px">
				<center>'.$img.'</center><br><br>
					<div class="">
					 <div id="thetable">';
      	$fixdata = $this->getFixmaster(); 
       $html.='<table width="800px" >
	  <tr>
    	<td valign="top" ><h2>TAX INVOICE</h2></td>
        <td valign="top"><table><tr><td><strong>Invoice Date</strong></td></tr><tr><td>'.date("d M Y",strtotime($invoice['invoice_date'])).'</td></tr></table></td> 
        <td valign="top" rowspan="2">'.nl2br($invoice['company_address']).'</td>
    </tr>
    <tr>
    	<td valign="top"><b>'.nl2br($invoice['customer_name']).'</b><br><br>'.nl2br($invoice['consignee']).'</td>
        <td valign="top"><table><tr><td><strong>Invoice Number</strong></td></tr><tr><td>'.$invoice['invoice_no'].'</td></tr><tr><td><strong>Order Number</strong></td></tr><tr><td>'.$invoice['buyers_orderno'].'</td></tr></table></td> 
     
    </tr>
</table>
<table  width="800px" style="margin-top: 50px;">
	<tr style="border-bottom: 3px solid black; height:40px">
    	<th width="200px" align="left">Description</th>
        <th align="left">Quantity</th>
        <th align="left">Unit Price</th>
        <th align="left">Amount '. $currency['currency_code'].'</th>
    </tr>';
	$subtotal=0;$tot_tax=0;
	if($alldetails)
	{
	foreach($alldetails as $details)
	{
		//printr($details);
		if($details['product_code_id']!='-1')
			$product_code_details=$this->getProductCode($details['product_code_id']);
		else
			$product_code_details['description']='CYLINDER';
		//$product_code_details=$this->getProductCode($details['product_code_id']);
			//$color = $this->getColorDetails($invoice_no,$details['invoice_product_id']); 			
			//printr($color);
			//foreach($color as $color_val)
		//	{
				//$zipper=$this->getZipper(decode($details['zipper']));
				$tot=$details['qty']*$details['rate'];
				$tot_tax=($tot_tax+(($tot*$invoice['gst'])/100));
				$subtotal=$subtotal+$tot;
				$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
					<td width="200px">'.$product_code_details['description'].'</td>
					<td>'.$details['qty'].'</td>
					<td>'.$details['rate'].'</td>
					<td>'.$tot.'</td>
				</tr>';
			//}
		}
	}
	
    $html.='
     <tr>
    	<td></td>
        <td></td>
       <td  style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">Subtotal</td>
       <td  style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">'.$subtotal.'</td>
    </tr>';
	$subtotal=$subtotal;
	/*if($invoice['freight_charge'] != 0)
	{
	 $html.='<tr>
			<td></td>
			<td></td>
		   <td  style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">Freight Charges</td>
		   <td  style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">'.sprintf('%0.2f',$invoice['freight_charge']).'</td>
    	</tr>';
	}*/
	
    $html.='<tr>
    	<td></td>
        <td></td>
        <td>Invoice Total '. $currency['currency_code'].'</td>
        <td>'.($subtotal).'</td>
    </tr>
    <tr>
    	<td></td>
        <td></td>
        <td style="border-bottom: 3px solid black;">Advance Recevied '. $currency['currency_code'].'</td>
        <td style="border-bottom: 3px solid black;">0.00</td>
    </tr>
    <tr>
    	<td></td>
        <td></td>
        <th align="left">Amount Due '. $currency['currency_code'].'</th>
        <th align="left">'.($subtotal).'</th>
    </tr>
	
</table>
<table width="800px"  style="margin-top: 50px; ">
	<tr>
			<th align="left" valign="top" width="300px">Payment Due Date: '.date("d M Y",strtotime($invoice['invoice_date'])).'</th>
			<td  width="400px"><table><tr><td align="left"><strong>Bank Details:</strong></td></tr><tr><td>'.nl2br($invoice['bank_address']).'</td></tr></table></td>
	</tr>
</table>

<div class="line line-dashed "></div>
<table width="800px"  style="margin-top: 50px; " >
	<tr valign="top">
    	<td align="left"><table><tr><td><strong>PAYMENT ADVICE</strong></td></tr><tr><td>To: '.nl2br($invoice['company_address']).'</td></tr></table></td>
        <td>
        	<table >
            	<tr>
                	<th>Customer </th>
                    <td>'.$invoice['customer_name'].'</td>
                </tr>
                <tr style="border-bottom: 3px solid  rgba(0, 0, 0, 0.09);">
                	<th>Invoice Number</th>
                    <td>'.$invoice['invoice_no'].'</td>                    
                </tr>
                <tr>
                	<th>Amount Due</th>
                    <td>'.($subtotal+$tot_tax).'</td>
                </tr>
                <tr style="border-bottom: 3px solid  rgba(0, 0, 0, 0.09);">
                	<th>Due Date</th>
                    <td>'.date("d M Y",strtotime($invoice['invoice_date'])).'</td>
                </tr>
                <tr>
                	<th>Amount Enclosed</th>
                    <td style="border-bottom: 3px solid black;"></td>
                </tr>
                <tr>
                	<td></td>
                    <td>Enter the amount you are paying above</td>
                </tr>
            </table>
        </td>
    </tr>
</table>';
			 $html.='</div>
		</div>
	</div>';
		return $html;
	}
	
	public function getActiveProductName($product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE product_id='".$product_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getProductCodeId($product_code)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE product_code='".$product_code."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
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
		$sql = "SELECT pc.*,p.product_name,pm.make_name,c.color,tm.measurement,pz.zipper_name,ps.spout_name,pa.product_accessorie_name FROM `" . DB_PREFIX . "product_code` AS pc LEFT JOIN product AS p ON pc.product=p.product_id LEFT JOIN product_make pm ON pc.make_pouch=pm.make_id LEFT JOIN pouch_color AS c ON c.pouch_color_id=pc.color LEFT JOIN template_measurement AS tm ON pc.measurement=tm.product_id  LEFT JOIN product_zipper AS pz ON pc.zipper=pz.product_zipper_id LEFT JOIN product_spout AS ps ON ps.product_spout_id=pc.spout LEFT JOIN product_accessorie AS pa ON pa.product_accessorie_id=pc.accessorie WHERE pc.product_code_id='".$product_code_id."' AND pc.is_delete=0 AND pc.status=1 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
		
	public function getZipper($product_zipper_id) 
	{
		$sql = "select * from " . DB_PREFIX ."product_zipper where product_zipper_id = '".$product_zipper_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	
	public function getColorName($color_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE pouch_color_id='".$color_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	
	
	public function getSpout($product_spout_id) 
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
	
	public function getInvoice($user_type_id,$user_id,$data,$filter_data=array())
	{
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "purchase_invoice as inv LEFT JOIN country as c  ON c.country_id=inv.final_destination  WHERE  inv.is_delete = 0 " ;
		} else {
		
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
				$str = ' OR ( inv.user_id IN ('.$userEmployee.') AND inv.user_type_id = 2 )';
			}
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "purchase_invoice as inv LEFT JOIN country as c  ON c.country_id=inv.final_destination WHERE  inv.is_delete = 0 AND inv.user_id = '".(int)$set_user_id."' AND inv.user_type_id = '".(int)$set_user_type_id."' $str AND inv.is_delete = 0" ;
		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['invoice_no'])){				
			$sql .= " AND invoice_no LIKE '%".$filter_data['invoice_no']."%' ";		
			}
			if(!empty($filter_data['country_id'])){
				$sql .= " AND final_destination = '".$filter_data['country_id']."' ";		
			}
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND customer_name LIKE '%".$filter_data['customer_name']."%' ";		
			}
			if(!empty($filter_data['email'])){
				$sql .= " AND email LIKE '%".$filter_data['email']."%' ";		
			}
			if($filter_data['status'] != ''){
				$sql .= " AND inv.status = '".$filter_data['status']."' "; 	
			}
			if(!empty($filter_data['user_name']))
			{
				$spitdata = explode("=",$filter_data['user_name']);
				$sql .=" AND inv.user_type_id = '".$spitdata[0]."' AND inv.user_id = '".$spitdata[1]."'";
			}
		}
		$sql .=' GROUP BY invoice_id ';
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " .$data['sort'];	
		} else {
			$sql .= " ORDER BY invoice_date";	
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
	
	public function getAccessorie($product_accessorie_id) 
	{
		$sql = "select * from " . DB_PREFIX ."product_accessorie where product_accessorie_id = '".$product_accessorie_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	
	public function getFixmaster()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_fixmaster` WHERE is_delete = '0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalInvoice($user_type_id,$user_id,$filter_data=array())
	{
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "purchase_invoice` WHERE is_delete = 0";
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
				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			}
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "purchase_invoice` WHERE is_delete = 0 AND user_id = '".(int)$set_user_id."' AND user_type_id = '".(int)$set_user_type_id."' $str  AND is_delete = 0" ;
		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['invoice_no'])){
				
			$sql .= " AND invoice_no LIKE '%".$filter_data['invoice_no']."%' ";		
			}
			if(!empty($filter_data['country_id'])){
				$sql .= " AND final_destination = '".$filter_data['country_id']."' ";		
			}
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND customer_name LIKE '%".$filter_data['customer_name']."%' ";		
			}
			if(!empty($filter_data['email'])){
				$sql .= " AND email LIKE '%".$filter_data['email']."%' ";		
			}
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
			if(!empty($filter_data['user_name']))
			{
			$spitdata = explode("=",$filter_data['user_name']);
				$sql .=" AND user_type_id = '".$spitdata[0]."' AND user_id = '".$spitdata[1]."'";
			}
		}
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function updateInvoiceStatus($status,$data)
	{
		if($status == 0 || $status == 1){
			$sql = "UPDATE " . DB_PREFIX . "purchase_invoice SET status = '" .(int)$status. "',  date_modify = NOW() WHERE invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
			//$sql1 = "UPDATE " . DB_PREFIX . "purchase_invoice_product SET status = '" .(int)$status. "',  date_modify = NOW() WHERE invoice_id IN (" .implode(",",$data). ")";
			//$this->query($sql1);
		}elseif($status == 2){
			$sql = "UPDATE " . DB_PREFIX . "purchase_invoice SET is_delete = '1', date_modify = NOW() WHERE invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
			//$sql1 = "UPDATE " . DB_PREFIX . "purchase_invoice_product SET is_delete = '1', date_modify = NOW() WHERE invoice_id IN (" .implode(",",$data). ")";
			//$this->query($sql1);
		}
	}
	
	public function updateInvoice($invoice_no,$status_value)
	{
		$sql = "UPDATE " . DB_PREFIX . "purchase_invoice SET status = '".$status_value."', date_modify = NOW()  WHERE invoice_id = '" .(int)$invoice_no. "'";
		$this->query($sql);
	}
	
	public function getInvoiceProduct($invoice_id)
	{
		//$sql = "SELECT ip.*,p.product_name FROM `" . DB_PREFIX . "purchase_invoice_product` as ip,product p,product_code as pc WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0 AND pc.product_code_id=ip.product_code_id AND pc.product=p.product_id";
		$sql = "SELECT ip.* FROM `" . DB_PREFIX . "purchase_invoice_product` as ip WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	
	public function getUserEmployeeIds($user_type_id,$user_id)
	{
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
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
	public function getLastIdPur() {
		$sql = "SELECT invoice_id FROM purchase_invoice ORDER BY invoice_id DESC LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	public function getProductCd($product_code)
	{
		$result=$this->query("SELECT product_code,product_code_id,description FROM " . DB_PREFIX ."product_code WHERE product_code LIKE '%".$product_code."%' AND is_delete=0");
		return $result->rows;
	}
	//[kinjal] : create to update freight charge rate (13-2-2016)
	public function changeRateByFrieght($invoice_id)
	{	
		$invoice = $this->getInvoiceData($invoice_id);
		$invoice_detail = $this->getInvoiceProduct($invoice_id); 
		$qty_val = 0;
		foreach($invoice_detail as $detail) 
		{
			$qty_val = $qty_val + $detail['qty'];
		}
		foreach($invoice_detail as $detail) 
		{
			$freight_charge = $invoice['freight_charge'];
			$freight_cal = (( $invoice['freight_charge'] / $qty_val) + $detail['without_freight_charge_rate']);
			$sql = "UPDATE " . DB_PREFIX . "purchase_invoice_product SET rate = '".$freight_cal."', date_modify = NOW()  WHERE invoice_id = '" .(int)$invoice_id. "' AND invoice_product_id = '".$detail['invoice_product_id']."'";
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
	public function getProductCdAll($product_id,$volume,$color)
	{	
		$result=$this->query("SELECT pc.product_code, pc.product_code_id, pc.description, clr.color, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.is_delete=0 AND pc.color=clr.pouch_color_id AND pc.product=p.product_id AND pc.product = '".$product_id."' AND pc.volume = '".$volume."' AND pc.color = '".$color."' " );
		return $result->rows;
	}
}
?>