<?php
class sales_invoice extends dbclass{	
	// add discount option (mansi = 1-2-2016)
	public function addInvoice($data=array())
	{	
		//printr($data);die;
	
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$case_breaking_rate = '';
		$label_charges ='';
		$prepress_charges =$extra_charge='0';
		$exporter_orderno = $extra_charge_nm='';
		$tax_maxico='';
		$purchase_invoiceno='';
		$region='';
		$type='';
		$vessal_name='';
		if(isset($data['sin_account_code']) && !empty($data['sin_account_code']) && isset($data['sin_fright_charge']) && !empty($data['sin_fright_charge']) &&  isset($data['sin_qty']) && !empty($data['sin_qty']))
		{
			$rate = $data['sin_fright_charge'];
			//$case_breaking_rate = $data['sin_case_breaking_fees'];
			$qty = $data['sin_qty'];
		}
		else
		{
			$rate = $data['color'][0]['rate'];
			
			$qty=$data['color'][0]['qty'];
		}
		$cal_pay_terms=$rate * $qty;
		if(isset($data['sin_case_breaking_fees']) && !empty($data['sin_case_breaking_fees']))
		{
			$case_breaking_rate = $data['sin_case_breaking_fees'];
			$qty = $data['sin_qty'];
			//$sin_account_code = $data['sin_account_code'];
		}
		if(isset($data['sin_label_charges']) && !empty($data['sin_label_charges']))
		{
			$label_charges = $data['sin_label_charges'];
			$qty = $data['sin_qty'];
			//$sin_account_code = $data['sin_account_code'];
		}
		if(isset($data['sin_prepress_charges']) && !empty($data['sin_prepress_charges']))
		{
			$prepress_charges = $data['sin_prepress_charges'];
			$qty = $data['sin_qty'];
			//$sin_account_code = $data['sin_account_code'];
		}
		if(isset($data['ref_no']) && !empty($data['ref_no']))
		{
			$exporter_orderno = $data['ref_no'];
		}
		
		//$case_break_fees=$case_breaking_rate * $qty;
		
		if(isset($data['invoice_id']) && !empty($data['invoice_id'])) {
			$invoice_id = $data['invoice_id'];
		}
		else
		{
    			if(isset($data['form']))
    				$form=implode(",",$data['form']);
    			else
    				$form='';
    			
    			$del_add='';
    			if(isset($data['delivery_info']) && !empty($data['delivery_info']))
    			{
    				$del_add = $data['delivery_info'];
    			}
    		//add sonu 13-4-2017
    			//printr($data);
             $address_book_id = $data['address_book_id'];
    			
    			//sonu END 
    			
    			//[kinjal] : changed code on 23-6-2017
    				$contacts = "SELECT email_1 FROM company_address WHERE email_1='".$data['email']."' AND is_delete=0";
    				$datacontacts= $this->query($contacts);
    				//printr($datacontacts);
    				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
    				{
    						$sql1 = "INSERT INTO address_book_master SET status = '1', company_name = '".addslashes($data['customer_name'])."', user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' ,user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW()";
    						$datasql1=$this->query($sql1);
    						$address_id = $this->getLastIdAddress();
    						$address_book_id = $address_id['address_book_id'];
    						//printr($address_book_id);
    						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
    						$dataadd= $this->query($add_id);
    						//printr($dataadd);
    						if($dataadd->num_rows)
    						{
    							$sql2 = "UPDATE company_address SET c_address = '".addslashes($data['consignee'])."',email_1 = '".$data['email']."', country= '".$data['country_id']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
    				
    							$datasql2=$this->query($sql2);
    						}
    						else
    						{
    							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($data['consignee'])."', email_1 = '".$data['email']."', country= '".$data['country_id']."', date_added = NOW()";
    						
    							$datasql2=$this->query($sql2);
    						}
    						
    												
    				}
    				else
    				{	
    						$address_book_id = $post['address_book_id'];
    					//	$sql1 = "UPDATE address_book_master SET vat_no='".$post['vat_no']."', company_name = '".addslashes($post['customer_name'])."' WHERE address_book_id ='".$post['address_book_id']."'";
    						//echo $sql1;
    						//$datasql1=$this->query($sql1);
                            $add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						    $dataadd= $this->query($add_id);
    						if($dataadd->num_rows)
    						{
    							$sql2 = "UPDATE company_address SET c_address = '".addslashes($data['consignee'])."',email_1 = '".$data['email']."', country= '".$data['country_id']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
    				
    							$datasql2=$this->query($sql2);
    						}
    						else
    						{
    							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($data['consignee'])."', email_1 = '".$data['email']."', country= '".$data['country_id']."', date_added = NOW()";
    						
    							$datasql2=$this->query($sql2);
    						}
    						
    				} 
    			if(isset($data['portofload']) && !empty($data['portofload']) && isset($data['city_id']) && !empty($data['city_id']) )
    			{	
    				$sql2 = "INSERT INTO `" . DB_PREFIX . "city` SET city_name = '".addslashes($data['portofload'])."', country = '".$data['country_id']."', date_added = now(),date_modify = now()";
    				//$sql2;die;
    				$datasql2=$this->query($sql2);
    				//printr($city_id);die;
    			}
    			if(isset($data['tax_maxico']) &&  !empty($data['tax_maxico']) || $data['country_final'] != '42')
    			$tax_maxico = $data['tax_maxico'];
    				//printr($data);
    			$state=$gst=$hst=$pst=0;
			    if($data['country_final'] == '42')
    			{
    				$tax_maxico =0;
    				$state = $data['state'];
    				if(isset($data['gst_checkbox']))
    				{		
    					
    					$gst = $data['can_gst'];
    					//printr($gst."*****");
    						
    				}
    			 	else
    				{
    					$gst=0;
    					//printr($gst."&&&&&");
    						
    				}
    				if(isset($data['pst_checkbox']))
    				{	
    					$pst = $data['pst'];
    					//printr($pst."*****");
    					
    				}
    				else
    				{
    					$pst = 0;
    					//printr($pst."*&&**");
    				}
    				if(isset($data['hst_checkbox']))
    				{
    					
    					$hst = $data['hst'];
    				}
    				else
    				{			
    					$hst = 0;
    				}
    		
    			if(isset($data['qst_no']))
    				{
    					
    					$qst_no = $data['qst_no'];
    				}
    				else
    				{			
    					$qst_no = 0;
    				}
    			
    			$payment_maxico = implode(',',$data['payment']);
    			$pay_type_maxico = $data['payment_type'];
    			$detail_maxico =  addslashes($data['detail_maxico']);
    			if($data['country_maxico'] == '155')
    			{
    				$orderdate_maxico = $data['orderdate'];
    				$del_info_maxico = $data['delivery_info'];
    				$amt_maxico = $data['amt_maxico'];
    				$buyers_orderno =$vessal_name=$port_load =$postal_code=$region =$type=$tax_type=$account_code=$sent_msg=$inv_status=$amt_paid='';
    			}
    			else
    			{
    				$buyers_orderno = $data['buyersno'];
    				//$vessal_name=$data['vessel_name'];
    				$port_load =$data['portofload'];
    				$postal_code=$data['postal_code'];
    				if(isset($data['region']))
    						$region =$data['region'];
    					else
    						$region ='';
    					if(isset($data['type']))
    						$type =$data['type'];
    						else
    						$type ='';
    					if(isset($data['vessel_name']))
    						$vessel_name =$data['vessel_name'];
    						else
    						$vessel_name ='';
    				//$region =$data['region'];
    				//$type=$data['type'];
    				$tax_type=$data['tax_type'];
    				$account_code=$data['account_code'];
    				$sent_msg=$data['sent'];
    				$inv_status=$data['invoice_status'];
    				$amt_paid=$data['amount_paid'];
    				$orderdate_maxico=$del_info_maxico=$amt_maxico ='';
    			}
    			
    			$payment_terms = $this->numberFormate(($cal_pay_terms+(($cal_pay_terms)*$tax_maxico/100)),3);
    			$userCountry = $this->getUserCountry($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
    			if($userCountry){
    				$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
    			}else{
    				$countryCode='IN';
    			}
    			$si = 'SI-';
    			$LastInvoiceId = $this->getLastIdSales();
    			$id=$LastInvoiceId['invoice_id']+1;
    			$pur_id=str_pad($id,8,'0',STR_PAD_LEFT);
    			
    			$invoice_no= $si.$countryCode.$pur_id;
    			
    			
    			$sql = "INSERT INTO `" . DB_PREFIX . "sales_invoice` SET purchase_invoiceno='".$purchase_invoiceno."',proforma_no='".$data['proforma_no']."',invoice_no = '".$invoice_no."',invoice_date = '" .$data['invoicedate']. "',reorder_date = '".$data['reorder_date']."',
    		exporter_orderno = '".$exporter_orderno."',gst='".$data['gst']."', buyers_orderno ='".$buyers_orderno."',consignee='".addslashes($data['consignee'])."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."',country_destination='".$data['country_final']."',vessel_name='".$vessal_name."',customer_name = '".addslashes($data['customer_name'])."',address_book_id = '".$address_book_id ."', email = '".$data['email']."',port_load='".$port_load."',final_destination='".$data['country_id']."',qst_no='".$qst_no."',state = '".$state."',can_gst = '".$gst."',pst = '".$pst."',hst = '".$hst."', payment_terms='".$payment_terms."',postal_code = '".$postal_code."',region = '".$region."',type = '".$type."',discount = '".$data['discount']."', tax_type = '".$tax_type."',account_code = '".$account_code."',sent = '".$sent_msg."',invoice_status = '".$inv_status."',curr_id='".$data['currency']."',status = '".$data['status']."',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',amount_paid='".$amt_paid."',is_delete=0,order_date_maxico='".$orderdate_maxico."', delivery_info_maxico='".$del_info_maxico."', tax_maxico = '".$tax_maxico."', payment_maxico = '".$payment_maxico."', pay_type_maxico='".$pay_type_maxico."', amt_maxico = '".$amt_maxico."',detail_maxico= '".$detail_maxico."',gen_status='1'";
    		
    			$datasql=$this->query($sql);
    			$invoice_id = $this->getLastId();
    		} 
		//printr($data);//die;
		}
		if(isset($data['invoice_id']) && !empty($data['invoice_id']) && isset($data['payment_terms']) && !empty($data['payment_terms'])) 
		{
			$payment_maxico = implode(',',$data['payment']);
			$pay_type_maxico = $data['payment_type'];
			if($data['country_maxico'] == '155')
				$amt_maxico = "amt_maxico ='".$data['amt_maxico']."',";
			else
				$amt_maxico = "amount_paid ='".$data['amount_paid']."',";
				
			$detail_maxico =  addslashes($data['detail_maxico']);
			$tax_maxico = 0;
			if($data['country_maxico'] != '42')
				$tax_maxico = $data['tax_maxico'];
			
			$payment_terms = $this->numberFormate(($data['payment_terms']+($cal_pay_terms+(($cal_pay_terms)*$tax_maxico/100))),2);
			
			$sql1 = "UPDATE `".DB_PREFIX."sales_invoice` SET payment_maxico = '".$payment_maxico."', pay_type_maxico='".$pay_type_maxico."', ".$amt_maxico."  detail_maxico= '".$detail_maxico."' , tax_maxico = '".$tax_maxico."', payment_terms='".$payment_terms."',curr_id='".$data['currency']."' Where invoice_id = '".$invoice_id."'";
			$data1 = $this->query($sql1);
		}
		
		$product_code_id=$data['product_code_id'];
		$sin_account_code ='';
		//printr($data['sin_account_code'].'=='.$data['sin_fright_charge'].'=='.$data['sin_qty']);
		if(isset($data['sin_account_code']) && !empty($data['sin_account_code']) && isset($data['sin_account_code']) && !empty($data['sin_fright_charge']) && isset($data['sin_qty']) && !empty($data['sin_qty']))
		{
			$product_code_id='';
			$rate = $data['sin_fright_charge'];
			
			$qty = $data['sin_qty'];
			$sin_account_code = $data['sin_account_code'];
		}
		if(isset($data['sin_case_breaking_fees']) && !empty($data['sin_case_breaking_fees']))
		{
			$case_breaking_rate = $data['sin_case_breaking_fees'];
			$qty = $data['sin_qty'];
			$sin_account_code = $data['sin_account_code'];
		}
		if(isset($data['sin_label_charges']) && !empty($data['sin_label_charges']))
		{
			$label_charges = $data['sin_label_charges'];
			$qty = $data['sin_qty'];
			$sin_account_code = $data['sin_account_code'];
		}
		if(isset($data['sin_prepress_charges']) && !empty($data['sin_prepress_charges']))
		{
			$prepress_charges = $data['sin_prepress_charges'];
			$qty = $data['sin_qty'];
			$sin_account_code = $data['sin_account_code'];
		}
		if(isset($data['charges_name']) && !empty($data['charges_name']) && isset($data['extra_charge']) && !empty($data['extra_charge']))
		{
			$extra_charge_nm = $data['charges_name'];
			$extra_charge = $data['extra_charge'];
			$qty = '1';
		}
		if($product_code_id == '-1')
			$sin_account_code = $data['sin_account_code'];
		
		 $pedimento_mexico = '';
		 if(isset($data['pedimento_mexico']))
			 $pedimento_mexico = $data['pedimento_mexico'];
		
	$sql2 = "Insert into sales_invoice_product Set invoice_id='".$invoice_id."',product_code_id='".$product_code_id."',product_description ='".$data['product_name']."',sin_account_code='".$sin_account_code."', rate ='".$rate."',case_breaking_fees='".$case_breaking_rate."',label_charges='".$label_charges."',prepress_charges ='".$prepress_charges."',extra_charge_name='".$extra_charge_nm."',extra_charge='".$extra_charge."',qty ='".$qty."', rack_remaining_qty='".$qty."',date_added = NOW(), date_modify = NOW(), is_delete = 0,pedimento_mexico='".$pedimento_mexico."'"; 
		$data2=$this->query($sql2);
		$invoice_product_id = $this->getLastId();
		return $invoice_id;
		$sql_data = " SELECT * FROM  sales_invoice_product WHERE  invoice_id='".$invoice_id."' AND product_code_id ='".$product_code_id."' ";
		//echo $sql_data;die;
		return $invoice_id;
		$data_pro = $this->query($sql_data);
		if($data_pro->num_rows){
			$sql_update = " UPDATE sales_invoice SET rack_notify_status ='1' WHERE invoice_id='".$invoice_id."'";
			$data_update = $this->query($sql_update);
			
			
		}else{
			return false;
		}

}
	public function getInvoiceData($invoice_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "sales_invoice  WHERE invoice_id = '" .(int)$invoice_id. "'";
		//echo $sql;
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getInvoiceNetData($invoice_id)
	{ 
		$sql = "SELECT  i.*,ip.* FROM  " . DB_PREFIX . "sales_invoice as i,sales_invoice_product as ip WHERE i.invoice_id =ip.invoice_id AND i.invoice_id = '" .(int)$invoice_id. "'  AND i.is_delete=0";
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
		$sql = "SELECT SUM(ic.qty) as total_qty,SUM(ic.rate) as total_rate,ic.rate,SUM(ic.qty*ic.rate) as tot FROM  " . DB_PREFIX . "sales_invoice as i,sales_invoice_product as ic WHERE i.invoice_id = '" .(int)$invoice_id. "' 
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
		$sql = "SELECT * FROM `" . DB_PREFIX . "sales_invoice_product` WHERE invoice_product_id = '" .(int)$invoice_product_id. "'";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	
	public function getCustomerDetail($customer_name)
	{
		
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

		$data = $this->query($sql);
		//printr($data);

		if($data->num_rows){

			return $data->rows;

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
		    send_email($val['email'],$form_email,$subject,$message,'',$url);
		}
	}
	
	public function getUser($user_id,$user_type_id)
	{	
		if($user_type_id == 1){
			$sql = "SELECT ib.international_branch_id,ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2){
			$sql = "SELECT ib.international_branch_id,e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,ib.color_plate_price,foil_plate_price,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
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
		$sql = "select ic.* from `".DB_PREFIX."sales_invoice_product` as ic WHERE invoice_id='".$invoice_id."' AND invoice_product_id = '".$invoice_product_id."'";
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
		$sql = "SELECT invoice_no FROM `" . DB_PREFIX . "sales_invoice` WHERE invoice_no = '" .(int)$invoice_no. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function check_purchaseno($purchaseinvoice_no)
	{
		$purchase_no=explode(",", $purchaseinvoice_no);
		$result = "'" . implode ( "', '", $purchase_no ) . "'";
		
		$sql = "SELECT invoice_no FROM `" . DB_PREFIX . "purchase_invoice` WHERE invoice_no IN (" .$result. ")  AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->num_rows;
		}else{
			return false;
		}
	}
	public function check_proforma($proforma_no)
	{
	    $sql = "SELECT pro_in_no FROM `" . DB_PREFIX . "proforma_product_code_wise` WHERE pro_in_no='".$proforma_no."'  AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['pro_in_no'];
		}else{
			return false;
		}
	}
	
	public function removeInvoice($POST)
	{
		parse_str($POST['form_data'], $data);
		
		$cal_amt=$POST['inv_rate']*$POST['inv_qty'];
		$inv_amt=$this->numberFormate(($POST['payment_terms_maxico']-($cal_amt+($cal_amt*$POST['tax_maxico']/100))),2);
		$sql = $this->query("UPDATE sales_invoice SET payment_terms='".$inv_amt."' WHERE invoice_id='".$data['invoice_id_model']."'");
		$sql1 = $this->query("DELETE FROM sales_invoice_product  WHERE `invoice_product_id` = '".$data['invoice_product_id_model']."' AND invoice_id='".$data['invoice_id_model']."'");
		//mansi 10-2-2016 
		$final_amount = $this->updateTotalInvoiceAmount($data['invoice_id_model']);
		
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
	// add $discount (mansi = 1-2-2016)
	public function updateInvoiceRecord($data)	
	{
		/*if($_SESSION['LOGIN_USER_TYPE']=='4' && $_SESSION['ADMIN_LOGIN_SWISS']=='44')
		    printr($data);die;*/
		
		$pro_no = $data['proforma_no'];
		$invoice_id = $data['invoice_id'];
		if(isset($data['tax_maxico']) && !empty($data['tax_maxico']))
		{
			$tax_maxico = $data['tax_maxico'];
				
		}
		
		$state=$gst=$hst=$pst=0;
		$exporter_orderno='';
		$purchase_invoiceno='';
		$region='';
		$type='';
		$vessal_name='';
		if(isset($data['purchase_no']) && !empty($data['purchase_no']) && isset($data['vessel_name']) && !empty($data['vessel_name']) &&  isset($data['region']) && !empty($data['region'])
		&&  isset($data['type']) && !empty($data['type']))
		{
			$purchase_invoiceno=$data['purchase_no'];
			$region=$data['region'];
			$type=$data['type'];
			$vessal_name=$data['vessel_name'];
		}
		
		
		if(isset($data['ref_no']) && !empty($data['ref_no']))
		{
				$exporter_orderno = $data['ref_no'];
				
		}
		if($data['country_maxico'] == '42')
		{
			$tax_maxico =0;
			$state = $data['state'];
			$tax_maxico =0;
			$state = $data['state'];
			if(isset($data['gst_checkbox']))
			{	
				$gst = $data['can_gst'];	
			}
			else
			{
					$gst=0;
					
			}
			if(isset($data['pst_checkbox']))
			{
				$pst = $data['pst'];
				
			}
			else
			{
				$pst = 0;
			}
			if(isset($data['hst_checkbox']))
			{
				$hst = $data['hst'];
				
			}
			else
			{			
				$hst = 0;
			}

			//$gst = $data['can_gst'];
			//$pst = $data['pst'];
			//$hst = $data['hst'];
		}
		$payment_maxico = implode(',',$data['payment']); 
		$pay_type_maxico = $data['payment_type'];
		$detail_maxico =  addslashes($data['detail_maxico']);
		if($data['country_maxico'] == '155')
		{
			$orderdate_maxico = $data['orderdate'];
			$del_info_maxico = $data['delivery_info'];
			$amt_maxico = $data['amt_maxico'];
			$buyers_orderno =$vessal_name=$port_load =$postal_code=$region =$type=$tax_type=$account_code=$sent_msg=$inv_status=$amt_paid='';
		}
		else
		{
			$buyers_orderno = $data['buyersno'];
			//$vessal_name=$data['vessel_name'];
			$port_load =$data['portofload'];
			$postal_code=$data['postal_code'];
				if(isset($data['region']))
						$region =$data['region'];
					else
						$region ='';
					if(isset($data['type']))
						$type =$data['type'];
						else
						$type ='';
					if(isset($data['vessel_name']))
						$vessel_name =$data['vessel_name'];
						else
						$vessel_name ='';
			//$region =$data['region'];
			//$type=$data['type'];
			$tax_type=$data['tax_type'];
			$account_code=$data['account_code'];
			$sent_msg=$data['sent'];
			$inv_status=$data['invoice_status'];
			$amt_paid=$data['amount_paid'];
			$orderdate_maxico=$del_info_maxico=$amt_maxico ='';
		}
		if(isset($data['qst_no']))
						$qst_no =$data['qst_no'];
					else
						$qst_no =0;
		
		if(isset($data['form']))
			$form=implode(",",$data['form']);
		else 
			$form='';
		$by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');
		$sql = "UPDATE `" . DB_PREFIX . "sales_invoice` SET purchase_invoiceno='".$purchase_invoiceno."',invoice_no = '".$data['invoiceno']."',invoice_date = '" .$data['invoicedate']. "',reorder_date = '".$data['reorder_date']."',date_of_payment_receipt = '" .$data['date_of_payment_receipt']. "',exporter_orderno = '".$exporter_orderno."',gst='".$data['gst']."', buyers_orderno ='".$buyers_orderno."',consignee='".addslashes($data['consignee'])."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."',country_destination='".$data['country_final']."',vessel_name='".$vessal_name."',customer_name = '".addslashes($data['customer_name'])."',address_book_id = '".$data['address_book_id']."', email = '".$data['email']."',port_load='".$port_load."',final_destination='".$data['country_id']."',customer_dispatch='".$data['customer_dispatch']."',qst_no='".$qst_no."',state = '".$state."',can_gst = '".$gst."',pst = '".$pst."',hst = '".$hst."', payment_terms='".$data['payment_terms']."',postal_code = '".$postal_code."',region = '".$region."',type = '".$type."',discount = '".$data['discount']."',tax_type = '".$tax_type."',account_code = '".$account_code."',sent = '".$sent_msg."',invoice_status = '".$inv_status."',curr_id='".$data['currency']."',status = '".$data['status']."',date_modify = NOW(),amount_paid='".$amt_paid."',is_delete=0,order_date_maxico='".$orderdate_maxico."', delivery_info_maxico='".$del_info_maxico."', tax_maxico = '".$tax_maxico."', payment_maxico = '".$payment_maxico."', pay_type_maxico='".$pay_type_maxico."', amt_maxico = '".$amt_maxico."', detail_maxico= '".$detail_maxico."', gen_status='0',edit_by = '".$by."' WHERE invoice_id = '".$data['invoice_id']."'";
		$data = $this->query($sql);
		//[kinjal] 10-2-2016
		$this->updateTotalInvoiceAmount($invoice_id);
		
		$this->ChangedProformaStatus($pro_no);
		return $data;
	}
	
	public function updateInvoiceProduct($data)
	{	
		//printr($data);die;
		$product_code_id=$data['product_code_id'];
		$rate = $data['color'][0]['rate'];
		if($data['sin_fright_charge']=='0')
			$rate=0;
		$qty=$data['color'][0]['qty'];
		$sin_account_code ='';
		$case_breaking_rate ='';
		$label_charges=$extra_charge='0';
		$prepress_charges=$extra_charge_nm='';
		if($product_code_id =='-1' || $product_code_id=='-2' || $product_code_id=='')
		{
			if(isset($data['sin_account_code']) && !empty($data['sin_account_code']) && isset($data['sin_fright_charge']) && !empty($data['sin_fright_charge']) &&  isset($data['sin_qty']) && !empty($data['sin_qty']))
			{
				if($product_code_id=='')
					$product_code_id='';
					
				$rate = $data['sin_fright_charge'];
				
				//if(isset($data['sin_case_breaking_fees']))
					
					
				$qty = $data['sin_qty'];
				$sin_account_code = $data['sin_account_code'];
				//echo "hi";
			}
			if(isset($data['sin_case_breaking_fees']) && !empty($data['sin_case_breaking_fees']))
			{
				$case_breaking_rate = $data['sin_case_breaking_fees'];
				$qty = $data['sin_qty'];
				$sin_account_code = $data['sin_account_code'];
			}
			if(isset($data['sin_label_charges']) && !empty($data['sin_label_charges']))
			{
				$label_charges = $data['sin_label_charges'];
				$qty = $data['sin_qty'];
				$sin_account_code = $data['sin_account_code'];
			}
			if(isset($data['sin_prepress_charges']) && !empty($data['sin_prepress_charges']))
			{
				$prepress_charges = $data['sin_prepress_charges'];
				$qty = $data['sin_qty'];
				$sin_account_code = $data['sin_account_code'];
			}
			if(isset($data['charges_name']) && !empty($data['charges_name']) && isset($data['extra_charge']) && !empty($data['extra_charge']))
			{
				$extra_charge_nm = $data['charges_name'];
				$extra_charge = $data['extra_charge'];
				$qty = '1';
			}
			//printr($data);
			//die;
		}
		$pedimento_mexico = '';
		 if(isset($data['pedimento_mexico']))
			 $pedimento_mexico = $data['pedimento_mexico'];
		 $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');
		$sql1 = "UPDATE `".DB_PREFIX."sales_invoice_product` SET invoice_id='".$data['invoice_id']."',product_code_id='".$product_code_id."',product_description ='".$data['product_name']."', sin_account_code= '".$sin_account_code."', rate ='".$rate."',case_breaking_fees='".$case_breaking_rate."',label_charges='".$label_charges."',prepress_charges='".$prepress_charges."', extra_charge_name='".$extra_charge_nm."',extra_charge='".$extra_charge."',qty = '".$qty."',rack_remaining_qty='".$qty."',date_added = NOW(), date_modify = NOW(), is_delete = 0,pedimento_mexico = '".$pedimento_mexico."',edit_by='".$by."' Where invoice_product_id = '".$data['pro_id']."'";
		$data1 = $this->query($sql1);
		//printr($data);
		//echo $sql1;
		// die;
		if($data['country_maxico'] == '155')
			$amt_maxico = "amt_maxico ='".$data['amt_maxico']."' ";
		else
			$amt_maxico = "amount_paid ='".$data['amount_paid']."' ";
			
		 $sql2 ="UPDATE `".DB_PREFIX."sales_invoice` SET payment_terms='".$data['payment_terms']."', ".$amt_maxico.",customer_dispatch='".$data['customer_dispatch']."' WHERE invoice_id ='".$data['invoice_id']."' ";
		 $this->query($sql2);
		
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
		$sql = "SELECT currency_code,currency_id FROM `" . DB_PREFIX . "currency` WHERE currency_code = '".$currency_code."' ";
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
	
	public function getProductdeatils($invoice_no)
	{
		$sql="SELECT * FROM sales_invoice_product WHERE invoice_id='".$invoice_no."'";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	// csv export
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
			$address = explode(' ',str_replace("<br>", " ",str_replace("\n", " ", $invoice['consignee'])));
			$part = ceil(count($address) / 4);
			$address1 = implode(' ', array_slice($address, 0, $part));
			$address2 = implode(' ', array_slice($address, $part, $part));
			$address3 = implode(' ', array_slice($address, $part * 2));
			$address4 = implode(' ', array_slice($address, $part * 4));
			$acc_code = $invoice['account_code'];
			// mansi 1-2-2016 (add discount in csv export)
			$subtotal[$invoice['invoice_no']]=0;$tot_tax[$invoice['invoice_no']]=$freight=0; $zipper_name='';  $valve=''; 
			foreach($alldetails as $details)
			{	
				if($details['product_code_id']=='0')
				{
					$product_code_details['description']='Freight';
					$product_code_details['product_code']='';
					$invoice['account_code']=$details['sin_account_code'];
				}
				else
				{
					$product_code_details=$this->getProductCode($details['product_code_id']);
					$invoice['account_code']=$acc_code;
				}
				$amt_paid = $invoice['amount_paid'];
				if($invoice['country_destination'] == '155')
					$amt_paid = $invoice['amt_maxico'];
					$tot[$invoice['invoice_no']]=$details['qty']*$details['rate'];
					$tax=$fri='0';
					if($details['product_code_id']=='0')
					{
						$tax=((($tot[$invoice['invoice_no']]*$invoice['tax_maxico'])/100));
						$fri=$tot[$invoice['invoice_no']];
						$freight =$details['qty']*$details['rate'];
					}
					
					$tot_tax[$invoice['invoice_no']]=(($tot_tax[$invoice['invoice_no']]+(($tot[$invoice['invoice_no']]*$invoice['tax_maxico'])/100)));
					$subtotal[$invoice['invoice_no']]=$subtotal[$invoice['invoice_no']]+$tot[$invoice['invoice_no']];
					 // mansi 1-2-2016 
					$gst = $invoice['tax_maxico'];
					if($invoice['gst']!='0')
					    $gst = $invoice['gst'];
					 $invoice_discount=($invoice['discount']*100)/($invoice['payment_terms']);
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
										'InvoiceAmountPaid'=>$this->numberFormate($amt_paid,2),
										'InvoiceAmountDue'=>'',
										'InventoryItemCode'=>$product_code_details['product_code'],
										'*Description'=>$product_code_details['description'],
										'*Quantity'=>$details['qty'],
										'*UnitAmount'=>$details['rate'],
										'Discount'=>0,
										'LineAmout'=>$this->numberFormate($tot[$invoice['invoice_no']],2),
										'*AccountCode'=>$invoice['account_code'],
										'*TaxType'=>$invoice['tax_type'],
										'TaxAmount'=>$this->numberFormate(($tot[$invoice['invoice_no']]*($gst/100)),2),
										'TrackingName1'=>'',
										'TrackingOption1'=>'',
										'TrackingName2'=>'',
										'TrackingOption2'=>'',
										'Currency'=>$currency['currency_code'],
										'Type'=>$invoice['type'],
										//'TotalDiscount'=>$this->numberFormate($invoice_discount,2),
										'Sent'=>$invoice['sent'],
										'Status'=>$invoice['invoice_status'],									
								); 
			}
			$in_no=0;
			foreach($input_array as $key=>$in_arr)
			{
				$input_array[$key]['TaxTotal']= $this->numberFormate($tot_tax[$input_array[$key]['*InvoiceNumber']],2);
				$input_array[$key]['TaxAmount']= $this->numberFormate($tot_tax[$input_array[$key]['*InvoiceNumber']],2);
				$input_array[$key]['Total']= $this->numberFormate($subtotal[$input_array[$key]['*InvoiceNumber']]+$tot_tax[$input_array[$key]['*InvoiceNumber']],2);
				$input_array[$key]['InvoiceAmountDue']= $this->numberFormate($subtotal[$input_array[$key]['*InvoiceNumber']]+$tot_tax[$input_array[$key]['*InvoiceNumber']],2);
			}
		}
		return $input_array;
	}
	public function InsertCSVData($handle,$charge)
	{
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$data=array();
		$first_time = $time = $t = true;
		$invoice_no=$inv_no='';
		$ibInfo = $this->getUser($user_id,$user_type_id);
		$addedByInfo['company_address']=$ibInfo['company_address'];
		$addedByInfo['bank_address']=$ibInfo['bank_address'];
		$customer_id='';
	  	$m='';
	  	$c=array();
        while($data1 = fgetcsv($charge,1000,","))
		{		//printr($data1);//die;	
			
			
		
			///printr($m);
			$cond1 = strtolower($data1[20]);
			$cond2 = strtolower($data1[19]);
		    if($inv_no!=$data1[10])
			{   $m='';
			    $inv_no=$data1[10];
			    if($cond1=='case-breaking' || $cond2=='case-breaking')
    			{
    			    $m.=",case_breaking_fees='".$data1[22]."'";
    			}
    			else if($cond1=='handling charges' || $cond2=='handling charges')
    			{
    			    $m.=",handling_charge='".$data1[22]."'";
    			}
    			else if($cond1=='re-stocking charges' || $cond2=='re-stocking charges')
    			{
    			    $m.=",re-stocking_charge='".$data1[22]."'";
    			}
    			else if($cond1=='lable charges' || $cond2=='lable charges')
    			{
    			    $m.=",label_charges='".$data1[22]."'";
    			}
    			else if($cond1=='prepress charges' || $cond2=='prepress charges')
    			{
    			    $m.=",prepress_charges='".$data1[22]."'";
    			}
    			else if($cond1=='Tin tie application' || $cond2=='Tin tie application')
    			{
    			    $m.=",tintie_charges='".$data1[22]."'";
    			}
			   //printr('if--->'.$m);
			}
			else
			{   
			    if($cond1=='case-breaking' || $cond2=='case-breaking')
    			{
    			    $m.=",case_breaking_fees='".$data1[22]."'";
    			}
    			else if($cond1=='handling charges' || $cond2=='handling charges')
    			{
    			    $m.=",handling_charge='".$data1[22]."'";
    			}
    			else if($cond1=='re-stocking charges' || $cond2=='re-stocking charges')
    			{
    			    $m.=",re-stocking_charge='".$data1[22]."'";
    			}
    			else if($cond1=='lable charges' || $cond2=='lable charges')
    			{
    			    $m.=",label_charges='".$data1[22]."'";
    			}
    			else if($cond1=='prepress charges' || $cond2=='prepress charges')
    			{
    			    $m.=",prepress_charges='".$data1[22]."'";
    			}
    			else if($cond1=='Tin tie application' || $cond2=='Tin tie application')
    			{
    			    $m.=",tintie_charges='".$data1[22]."'";
    			}
    			//printr('else--->'.$m);
			}
			$c[$inv_no] = $m;
			//printr($c);
		}
       //die;
        //loop through the csv file 
		
		while($data = fgetcsv($handle,1000,","))
		{	//printr($data);//die;	
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
				$country=$this->getCountryId($data[9]);
			
				$Currency=$this->getCurrencyId($data[32]);
				
								
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
				//$InvoiceAmountPaid=$data[17];
				if($data[9] == 'Mexico')
					$amt_paid = ",amt_maxico ='".$data[17]."'";
				else
					$amt_paid = ",amount_paid ='".$data[17]."'";
					
				$InvoiceAmountDue=$data[18];
				$InventoryItemCode=$data[19];
				$Description=$data[20];
				$Quantity=$data[21];
				$UnitAmount=$data[22];
				$Discount=$data[23];
				$LineAmout=$data[24];
				$AccountCodeSin = '';
				//if($data[19] == '')
					//$product_code_id['product_code_id']='-1';
				//else
					$product_code_id=$this->getProductCodeId($data[19]);
				$cond = strtolower($data[19]);
    			if($cond=='freight' || $cond=='shipping' || $cond=='case-breaking' || $cond=='handling charges' || $cond=='re-stocking charges' || $cond=='lable charges' || $cond=='prepress charges' || $cond=='tin tie application')
    			{  //echo $data[20];
					$AccountCodeSin=$data[25];
					$AccountCode='';
					$product_code_id['product_code_id']='0';
					if($data[20]!='Shipping')
					{
					   $UnitAmount ='0'; 
					}
					$sdata = $c[$data[10]];
    				if ($time == true) {
        				 $time = false;
    			    }
				}
			/*	else if($c[$data[10]]!='')
				{
				    $AccountCodeSin=$data[25];
    				$AccountCode='';
    				$product_code_id['product_code_id']='0';
				    $sdata = $c[$data[10]];
				    if($time == true) {
        				 $time = false;
    			    }
				}*/
				else
				{
					$AccountCode=$data[25];
					$t = false;
					$sdata = '';
				}
				
				$TaxType=$data[26];
				$TaxAmount=$data[27];
				$TrackingName1=$data[28];
				$TrackingOption1=$data[29];
				$TrackingName2=$data[30];
				$TrackingOption2=$data[31];
				$city=$data[6];
				//$Currency=$data[32];
				$Type=$data[33];
				// mansi 1-2-2016
				$discount=$data[34];
				$Sent=$data[35];
				//$Status=$data[36];
				$gst=$data[27]/$data[24]*100;
				//printr($product_code_id);
			if($invoice_no!=$data[10])
			{
				
				$total_amt='0';
				$invoice_no=$data[10];
				$contactName=addslashes($contactName);
				$sql3 = "SELECT id FROM `" . DB_PREFIX . "xero_customer` WHERE name = '".$contactName."' ";
				$data3 = $this->query($sql3);
				
				if($data3->num_rows){
					$customer_id=$data3->row['id'];
				}
				
				if($customer_id=='')
				{				
					$contactName=addslashes($contactName);
					$sql1 = "INSERT INTO `" . DB_PREFIX . "xero_customer` SET name ='".$contactName."', email = '".$emailAddress."',address='".addslashes($address)."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
					//echo $sql1.'<br>';
					$datasql1=$this->query($sql1);
					$customer_id = $this->getLastId();
				
                }
				$contacts = "SELECT email_1,company_address_id FROM company_address WHERE email_1='".$emailAddress."' AND is_delete=0";
				$datacontacts= $this->query($contacts);
				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
				{
						$sql1 = "INSERT INTO address_book_master SET status = '1', company_name = '".$contactName."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' ,user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW()";
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
							$sql2 = "UPDATE company_address SET c_address = '".addslashes($address)."',email_1 = '".$emailAddress."', country= '".$country['country_id']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
							$datasql2=$this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($address)."', email_1 = '".$emailAddress."', country= '".$country['country_id']."', date_added = NOW()";
							$datasql2=$this->query($sql2);
						}
						
				}
				else
				{	
						
					$sql2 = "UPDATE company_address SET c_address = '".addslashes($address)."',email_1 = '".$emailAddress."', country= '".$country['country_id']."' WHERE company_address_id = '".$datacontacts->row['company_address_id']."'";
					$datasql2=$this->query($sql2);
						
				}
				
				//$final_amount=$this->numberFormate(((($total_dis+$final_tax)-$amout_paid)),3);
				
				$sql = "INSERT INTO `" . DB_PREFIX . "sales_invoice` SET invoice_no = '".$InvoiceNumber."',invoice_date = '" .$InvoiceDate. "',
		exporter_orderno = '".$Reference."',gst='".$gst."', buyers_orderno ='".$Reference."',consignee='".addslashes($address)."',company_address='".addslashes($addedByInfo['company_address'])."',bank_address='".addslashes($addedByInfo['bank_address'])."',country_destination='".$country['country_id']."',vessel_name='',customer_name = '".addslashes($contactName)."', email = '".$emailAddress."',port_load='".$city."',final_destination='".$country['country_id']."',payment_terms='".$Total."',postal_code = '".$POPostalCode."',region = '".$PORegion."',type = '".$Type."',discount = '".$discount."',tax_type = '".$TaxType."',final_total = '".$Total."',account_code = '".$AccountCode."',sent = '".$Sent."',curr_id='".$Currency['currency_id']."',status = 1,date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0 ".$amt_paid."";		
			   //printr($sql);
			
				$datasql=$this->query($sql);
				$invoice_id = $this->getLastId();
			
			}
			if($time == false || $t == false)
			{
			    $sql2 = "Insert into sales_invoice_product Set invoice_id='".$invoice_id."',product_code_id='".$product_code_id['product_code_id']."',sin_account_code='".$AccountCodeSin."', rate ='".$UnitAmount."', qty = '".$Quantity."',rack_remaining_qty='".$Quantity."',date_added = NOW(), date_modify = NOW(), is_delete = 0 ".$sdata; 
			    //printr($sql2);
			    $data2=$this->query($sql2);
			}
			if($data[20]!='Freight'  || $data[20]=='Shipping')
			{
				$total_amt = $total_amt+$LineAmout;
				//echo $total_amt.'+'.$LineAmout;
			}
			//printr($total_amt);
			$tax = (($TaxTotal*100)/$total_amt);
		//	echo  '(('.$TaxTotal.'*100)/'.$total_amt.')';
			if($tax != '')
			{
				$sql4 = "UPDATE `".DB_PREFIX."sales_invoice` SET tax_maxico='".$tax."' Where invoice_id ='".$invoice_id."'";
				//printr($sql4);
			 	$data4=$this->query($sql4);
			
			}
					
		}
       //die;
		return $invoice_id;
	}
	public function viewInvoiceForIB($status,$invoice_no)
	{	
		
		$invoice=$this->getInvoiceNetData($invoice_no);
		//printr($invoice);die;
		//echo "kkkk";
		$currency=$this->getCurrencyName($invoice['curr_id']);	
		$alldetails=$this->getInvoiceProduct($invoice_no);
		//printr($currency);//die;
		
		 if($invoice['contact_name']!='')
			    $contact_name='<b>Kind Attention :</b> '.$invoice['contact_name'];
            else
            	$contact_name='';
		
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
				//echo "1 echio  ";
			}else{
				$userEmployee = $this->getUserEmployeeIds($invoice['user_type_id'],$invoice['user_id']);
				$set_user_id = $invoice['user_id'];
				//echo $set_user_id."2";
				$set_user_type_id = $invoice['user_type_id'];
			}
			$user_info=$this->getUser($set_user_id,'4');
			//printr($user_info);
			//echo $set_user_id;
			$data=$this->query("SELECT logo,abn_no,termsandconditions_invoice,vat_no FROM international_branch WHERE international_branch_id = '".$set_user_id."'");
			////echo "SELECT logo FROM international_branch WHERE international_branch_id = '".$set_user_id."'";
		//	printr($data);
			if(isset($data->row['logo']) &&  $data->row['logo']!= '')
			{
				$image= HTTP_UPLOAD."admin/logo/200_".$data->row['logo'];
				if($invoice['country_destination']=='155')
				    $img = '<img src="'.$image.'" alt="Image" width="267px;">';
				else
				    $img = '<img src="'.$image.'" alt="Image">';
			}
			else
			{
				$img ='';
			}			
		}
		$au_user=$this->getUser($invoice['user_id'],$invoice['user_type_id']);
	//	printr($au_user);
		$atten_user = $au_user['first_name'].' '.$au_user['last_name'];
		$order_number = $invoice['buyers_orderno'];
		if($set_user_id=='7')
		{	 // mansi ("Tax"=>Gst) 8-3-2016
			$th_gst = 'TAX GST @';
			$advance_status = 'Total Net Payments';
			$tax_per = ''; //Tax Exempte removed told by sofia 17-5-2019 
		}
		else if($set_user_id=='27')
		{
			$th_gst = $invoice['tax_type'];
			$tax_per = round($invoice['tax_maxico']).' % ';
			
			$advance_status = 'Advance Received';
		}
		else
		{
			if($set_user_id=='10')
			{
				$th_gst = 'VAT';
				$order_number = $invoice['exporter_orderno'];
			}elseif($set_user_id=='19'){
			    	$th_gst = 'VAT';
			}
			else
			{
				$th_gst = 'GST';
			}
			$advance_status = 'Advance Received';
			$tax_per = round($invoice['tax_maxico']).' % ';
		}
		$html=''; 
		$qst_no=$invoice['qst_no']; //td{padding: 6px;}
		$html.='<style>table {border-collapse: collapse;}.line-dashed {
				  border-style: dashed;
				  background: transparent;
				}
				.line {
				  height: 2px;
				  margin: 10px 0;
				  font-size: 0;
				  overflow: hidden;
				  background-color: #fff;
				  border-width: 0;
				  border-top: 1px solid #e0e4e8;
				}
				 .nobreak {
                        page-break-inside: avoid;
                  }
				
				</style>
				<div style="padding-top: 0px; border: 1px solid black;font-size:14px;">';
				if($invoice['country_destination']=='155')
				    $html.=$img;
				else
				    $html.='<center>'.$img.'</center>';
				    
				    $html.='<br>
					
					<div class="">
					 <div  style="width: 100%;">';//width:754px
      	$fixdata = $this->getFixmaster(); 
		// str_ireplace(':','Smit','Pouch Direct Pvt Ltd attention : Like canada ');
       $text_tax ='TAX';
       $td_tax = '<th align="left" style="border-bottom: 3px solid black;">'.$th_gst.'</th>';
       $td_tax_val='<td style="border-bottom: 1px solid black;">'.$tax_per.'</td>';
       $blank_td='<td></td>';
       $td_text='Enter the amount you are paying above';
       
       if($set_user_id=='19')
         $td_tax=$td_tax_val=$blank_td=$td_text='';
        
       $html.='<table width="100%">
	            <tr>
                	<td valign="top" ><h2>'.$text_tax.' INVOICE</h2></td>
                    <td valign="top">
                        <table>
                            <tr>
                                <td><strong>Invoice Date</strong></td>
                            </tr>
                            <tr>
                                <td>'.date("d M Y",strtotime($invoice['invoice_date'])).'</td>
                            </tr>
                        </table>
                    </td>';
            		 if(isset($set_user_id) && $set_user_id == '24')
            		  	 $html.=' <td valign="top" rowspan="2">'.nl2br($invoice['company_address']).'<br></br>Attention : '.$atten_user.'<br><br>ABN : '.$data->row['abn_no'].'</td>';
            	   	 else
            	   	 {
            	   	    $html.=' <td valign="top" rowspan="2">';
            	   	    if($invoice['country_destination']=='155')
            	   	        $html .='<b>Emisor</b><br>';
            	   	   if($set_user_id=='19')
            	   	       $html .=nl2br($invoice['company_address']).'<br>TRN :'.$data->row['vat_no'].'<br></br>Attention : '.$atten_user.'</td>';
            	   	  else if($set_user_id=='44')
            	   	       $html .=nl2br($invoice['company_address']).'<br><br><b>QST No. : </b>1224569528TQ0001<br></br>Attention : '.$atten_user.'</td>';
            	   	   else
            	   	       $html .=nl2br($invoice['company_address']).'<br></br>Attention : '.$atten_user.'</td>';
            	   	    
            	   	 }
         $html.=' </tr>
                <tr>
    	            <td valign="top">';
                	if($set_user_id=='10')
                	    $html .='<b>Receptor</b><br>';
                	$state = '';
                	if($user_info['country_id']=='42')
                	{
                	    $state_data = $this->stateDetail($invoice['state']);
                	    $state='<b>State</b> : '.$state_data['state'];
                	}
                	$html .=stripslashes($invoice['customer_name']).'<br>'.nl2br($invoice['consignee']).'<br>'.$state.'<br>'.$contact_name.'</td>
                    <td valign="top">
            			<table>
            					<tr><td><strong>Invoice Number</strong></td></tr><tr><td>'.$invoice['invoice_no'].'</td></tr>
            					<tr><td><strong>Order Number</strong></td></tr><tr><td>'.$order_number.'</td></tr>';
            					if($set_user_id=='44')
            					{
            						$html.='<tr><td><strong>GST/HSTNo. : </strong></td></tr><tr><td>'.$data->row['vat_no'].'</td></tr>';
            					}
            					if(isset($set_user_id) && $set_user_id == '24')
            						$html.='<tr><td><strong>ABN</strong></td></tr><tr><td>'.$data->row['abn_no'].'</td></tr>';
            			$html.=' </table>
            		</td> 
     
            </tr>
        </table>
        <table  width="100%" style="margin-top: 50px; ">
        		<tr style="border-bottom: 3px solid black; height:40px">
                	<th align="left" style="border-bottom: 3px solid black;" >Description</th>
                    <th align="left" style="border-bottom: 3px solid black;" >Quantity </th>
                    <th align="left" style="border-bottom: 3px solid black;">Unit Price</th>
                    '.$td_tax.'
                    <th style="border-bottom: 3px solid black;"><div align="center">Amount '. $currency['currency_code'].'</div></th>
                </tr>';
            	$subtotal=0;$tot_tax=$freight_tot=$case_breaking_fees_tot=0;$label_charges_tot=0;$prepress_charges_tot=0;$product_code_freight=$p_code_id=$frieght_qty=$freight_rate=$dis=$p_code = '';
	$handling_charge_tot=$restocking_charge_tot=$tintie_charges_tot=0;
	if(isset($alldetails) && $alldetails!='')
	{
		foreach($alldetails as $details)
		{	$zipper_name=$spout_name=$acc_name=$valve_name='';//printr($details);
			$get_size = array('size_master_id'=> '',

								'product_id'=>'',

								'product_zipper_id'=>'',

								'volume'=>'',

								'width'=>'',

								'height'=>'',

								'gusset'=>'',

								'weight'=>'');
			
			$pro_code = '';
			if($details['product_code_id'] == '-1')
			{
				$product_code = $details['product_description'];
				
			}
			elseif($details['product_code_id'] == '0')
			{
				if($details['rate']!='0')
				{
					$product_code_freight = 'Freight Charges : '.$details['rate'];
					$p_code = $p_code_id=$details['product_code_id'];
					$frieght_qty = $details['qty'];
					$freight_rate = $details['rate'];
					$freight_tot = $frieght_qty * $freight_rate;
					$dtl['rate'] =$details['rate'];
				}
				if($details['case_breaking_fees']!='0')
				{
					$product_code_case_fees = 'Case Breaking Charges :  '.$details['case_breaking_fees'];
					$p_code = $p_code_id=$details['product_code_id'];
					$case_breaking_qty = $details['qty'];
					$case_breaking_fees = $details['case_breaking_fees'];
					$case_breaking_fees_tot = $case_breaking_qty * $case_breaking_fees;
					$dtl['case_breaking_fees'] =$details['case_breaking_fees'];
				}
				if($details['label_charges']!='0')
				{
					$product_code_label_charges = 'Label Charges :  '.$details['label_charges'];
					$p_code = $p_code_id=$details['product_code_id'];
					$label_charges_qty = $details['qty'];
					$label_charges = $details['label_charges'];
					$label_charges_tot = $label_charges_qty * $label_charges;
					$dtl['label_charges'] =$details['label_charges'];
				}
				if($details['prepress_charges']!='0')
				{
					$product_code_prepress_charges = 'Prepress Charges :  '.$details['prepress_charges'];
					$p_code = $p_code_id=$details['product_code_id'];
					$prepress_charges_qty  = $details['qty'];
					$prepress_charges = $details['prepress_charges'];
					$prepress_charges_tot = $prepress_charges_qty  * $prepress_charges;
					$dtl['prepress_charges'] =$details['prepress_charges'];
				}
				if($details['handling_charge']!='0')
				{
					$product_code_handling_charge = 'Handling Charges :  '.$details['handling_charge'];
					$p_code = $p_code_id=$details['product_code_id'];
					$handling_charge_qty  = $details['qty'];
					$handling_charge = $details['handling_charge'];
					$handling_charge_tot = $handling_charge_qty  * $handling_charge;
					$dtl['handling_charge'] =$details['handling_charge'];
				}
				if($details['re-stocking_charge']!='0')
				{
					$product_code_restocking_charge = 'Re-stocking Charges :  '.$details['re-stocking_charge'];
					$p_code = $p_code_id=$details['product_code_id'];
					$restocking_charge_qty  = $details['qty'];
					$restocking_charge = $details['re-stocking_charge'];
					$restocking_charge_tot = $restocking_charge_qty  * $restocking_charge;
					$dtl['re-stocking_charge'] =$details['re-stocking_charge'];
				}
				if($details['tintie_charges']!='0')
				{
					$product_code_tintie_charges = 'Re-stocking Charges :  '.$details['tintie_charges'];
					$p_code = $p_code_id=$details['product_code_id'];
					$tintie_charges_qty  = $details['qty'];
					$tintie_charges = $details['tintie_charges'];
					$tintie_charges_tot = $tintie_charges_qty  * $tintie_charges;
					$dtl['tintie_charges'] =$details['tintie_charges'];
				}
				if($details['extra_charge']!='0')
				{
					$product_code_extra_charges = $details['charges_name'].' : ';
					$p_code = $p_code_id=$details['product_code_id'];
					$extra_charges_qty  = '1';
					$extra_charges = $details['extra_charge'];
					$extra_charges_tot = $extra_charges_qty  * $extra_charges;
					$dtl['extra_charge'] =$details['extra_charge'];
				}
				
			}
			else
			{
			    
				$product_code_details=$this->getProductCode($details['product_code_id']);
				//printr($product_code_details);
				$product_code =$product_code_details['description'];
				$p_code_id=$details['product_code_id'];
			
			    if($set_user_id=='10' ||$set_user_id=='19')
				{
					$pro_code = '<b>Product Code :</b> '.$product_code_details['product_code'];
					if($product_code_details['valve']=='With Valve')

						$valve_name=$product_code_details['valve'];

					if($product_code_details['zipper_name']!='No zip')

						$zipper_name=$product_code_details['zipper_name'];

					if($product_code_details['spout_name']!='No Spout')

						$spout_name=$product_code_details['spout_name'];
					
					if($product_code_details['product_accessorie_name']!='No Accessorie')	
						
						$acc_name=$product_code_details['product_accessorie_name'];
					
					$get_size = $this->getSizeDetail($product_code_details['product'],$product_code_details['zipper'],$product_code_details['volume'],$product_code_details['measurement']);
					
					if($product_code_details['product'] == 3)
						$gusset = floatval($get_size['gusset']).'+'.floatval($get_size['gusset']);
					else
						$gusset = floatval($get_size['gusset']);
					
					if($product_code_details['width']!='0' && $product_code_details['height']!='0' && $product_code_details['gusset']!='0')
					{
					    $size_product = '</b>'.floatval($product_code_details['width']).' mm &nbsp;Width &nbsp;X&nbsp;'.floatval($product_code_details['height']).' mm &nbsp;Height &nbsp;';
					    if($product_code_details['product'] == 3)
    						$gusset = floatval($product_code_details['gusset']).'+'.floatval($product_code_details['gusset']);
    					else
    						$gusset = floatval($product_code_details['gusset']);
					}
					else
					    $size_product = '</b>'.floatval($get_size['width']).' mm &nbsp;Width &nbsp;X&nbsp;'.floatval($get_size['height']).' mm &nbsp;Height &nbsp;';
					
				}
				
			
			
			}
		    
		        
		       
		        
		        $foil_plate_price=$digital_plate_price=0;
				$tot=$details['qty']*$details['rate'];
				
				if($details['stock_print']=='Digital Print')
		            $tot = $tot+ ($user_info['color_plate_price']*$details['plate']); $digital_plate_price=$user_info['color_plate_price']*$details['plate'];
		       	if($details['stock_print']=='Foil Stamping')
		            $tot = $tot+ ($user_info['foil_plate_price']*$details['plate']); $foil_plate_price=$user_info['foil_plate_price']*$details['plate'];
		        
				$tax_gst=$invoice['tax_maxico'];
				
				
				    
				$tax=$fri='0';
				if($details['product_code_id'] == '0')
				{
					$tax=((($tot*$tax_gst)/100));
					$fri=$tot;
				}
				//[kinjal] modify 10-2-2016
				
		    	if( $_SESSION['ADMIN_LOGIN_SWISS']==1 &&  $_SESSION['LOGIN_USER_TYPE']==1){
				 
				//    printr($subtotal.'=fwqf=');
				}
				
				$tot_tax=($tot_tax+(($tot*$tax_gst)/100));
				$subtotal=$subtotal+$tot-$fri;
				if($details['tool_price']!='0')
				    $subtotal = $subtotal+$details['tool_price'];
			
				//[kinjal] modify 10-2-2016
				//printr($p_code_id);
			
				
				if($set_user_id=='19' && $p_code_id!='0')
				{
				    
				    //printr($details);
				    $html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
    						    <td style="border-bottom: 1px solid black;">'.$pro_code;
            					
            							if($product_code_details['product'] == 6)
            								$html .='<br><br><b>Size : </b>'.floatval($product_code_details['width']).'mm &nbsp; Roll Width';
            							else
            								$html .='<br><b>Size :</b> '.$size_product;
            							if($gusset>0)
            								$html .='X&nbsp;'.$gusset.' mm Gusset';
            							
            							$html .='<br><b>Make up of pouch : </b>'.$product_code_details['product_name'].'<b>&nbsp;'.$zipper_name.'&nbsp;'.$valve_name.' &nbsp;'.$spout_name.' &nbsp;'.$acc_name.'</b><br>';
            							$html .='<b>Color : </b>'.$product_code_details['color'];
            							$html .='<br><b>Material Description : </b>'.$details['product_description'];
            					        
    					       
    					            if($details['stock_print']=='Digital Print')
    						            $html .='<br><b>Plates For Digital Printing</b>';   
    						       if($details['stock_print']=='Foil Stamping')
    						            $html .='<br><b>Plates For Foil Stamping</b>';
    						     		
    				$html .='</td>
    						<td style="border-bottom: 1px solid black;">'.$details['qty'];
    						
    						if($details['stock_print']=='Digital Print')
    						            $html .='<br>'.$details['plate'].'';
    						 if($details['stock_print']=='Foil Stamping')
    						            $html .='<br>'.$details['plate'].'';
    						            
    					$html.='</td>
    						<td align="center"  style="border-bottom: 1px solid black;">'.$this->numberFormate($details['rate'],3);
    						    
    						    if($details['stock_print']=='Digital Print')
    						            $html .='<br>'.$user_info['color_plate_price'].'</br> Per 1 Plate'; 
    						     if($details['stock_print']=='Foil Stamping')
    						            $html .='<br>'.$user_info['foil_plate_price'].' </br>Per 1 Plate';
    						
    						$html.='</td>
    						'.$td_tax_val.'
    						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($details['qty']*$details['rate'],2);
    						    if($details['stock_print']=='Digital Print')
    						            $html .='<br>'.$user_info['color_plate_price']*$details['plate'].'';
    						   if($details['stock_print']=='Foil Stamping')
    						            $html .='<br>'.$user_info['foil_plate_price']*$details['plate'].'';
    						            
    						$html.='</td>
    					</tr>';
				    
				}
			elseif($p_code_id!='0')
				{
    				$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
    						    <td style="border-bottom: 1px solid black;">'.$pro_code;
            						if($invoice['country_destination']!='155')
            						    $html .=$product_code;
            						else
            						{
            							if($product_code_details['product'] == 6)
            								$html .='<br><br><b>Size : </b>'.floatval($product_code_details['width']).'mm &nbsp; Roll Width';
            							else
            								$html .='<br><b>Size :</b> '.$size_product;
            							if($gusset>0)
            								$html .='X&nbsp;'.$gusset.' mm Gusset';
            							
            							$html .='<br><b>Make up of pouch : </b>'.$product_code_details['product_name'].'<b>&nbsp;'.$zipper_name.'&nbsp;'.$valve_name.' &nbsp;'.$spout_name.' &nbsp;'.$acc_name.'</b><br>';
            							$html .='<b>Color : </b>'.$product_code_details['color'];
            							$html .='<br><b>Material Description : </b>'.$details['product_description'];
            						//	if($details['tool_price']!='0')
										//	$html .='<br/><div style="vertical-align: bottom;"><b>Tool Price : </b><br></div>';
            						}
    					            if($details['stock_print']=='Digital Print')
    						            $html .='<br><b>Plates For Digital Printing</b>';
    				$html .='</td>
    						<td style="border-bottom: 1px solid black;">'.$details['qty'];
    					//	printr($user_info);
    						if($details['stock_print']=='Digital Print')
    						            $html .='<br>'.$details['plate'].'';
    				    	 if($details['stock_print']=='Foil Stamping')
    						            $html .='<br><b>Plates For Foil Stamping</b>';
    						            
    					$html.='</td>
    						<td align="center"  style="border-bottom: 1px solid black;">'.$this->numberFormate($details['rate'],3);
    						    if($details['stock_print']=='Digital Print')
    						            $html .='<br>'.$user_info['color_plate_price'].' <br/> Per 1 Plate';
    						   if($details['stock_print']=='Foil Stamping')
    						            $html .='<br>'.$user_info['foil_plate_price'].' <br/>Per 1 Plate';
    						
    						$html.='</td>
    						'.$td_tax_val.'
    						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($details['qty']*$details['rate'],2);
    						    if($details['stock_print']=='Digital Print')
    						            $html .='<br>'.$user_info['color_plate_price']*$details['plate'].'';
    						     if($details['stock_print']=='Foil Stamping')
    						            $html .='<br>'.$user_info['foil_plate_price']*$details['plate'].'';
    						   //if($details['tool_price']!='0')
    						      //$html .='<br><div align="right" style="vertical-align: bottom;">'.$details['tool_price'].'</div>';
    						$html.='</td>
    					</tr>';
				}
				if($details['tool_price']!='0')
				{
				    $html.='<tr style="border-bottom: 3px solid black; height:40px;border-top: 1px solid rgba(0, 0, 0, 0.09);">
				                <td><b>Tool Price : </b></td>
				                <td></td>
				                <td></td>
				                <td></td>
				                <td align="center">'.$details['tool_price'].'</td>
				            </tr>';
				}
		}
	}
		
	//mansi(1-2-2016) add discount field
	//printr($invoice['discount']);
	
	$total_dis=$subtotal-(($subtotal*$invoice['discount'])/100);
	$tax_gst=$invoice['tax_maxico'];
	
	//$tax_gst=10;
	if($set_user_id=='10')
	{
		$amout_paid=$invoice['amt_maxico'];
		
	}
	else
	{ 
		$amout_paid=$invoice['amount_paid'];
	}
	
    $html.='
     <tr>
    	<td></td>
        <td></td>
        '.$blank_td.'
        <td>Subtotal</td>
        <td align="center">'.$this->numberFormate($subtotal,2).'</td>
    </tr>
	 <tr>
    	<td></td>
        <td></td>
        '.$blank_td.'
        <td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">Discount </td>
        <td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$invoice['discount'].' %</td>
    </tr>
	 <tr>
    	<td></td>
        <td></td>
        '.$blank_td.'
        <td></td>
        <td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$total_dis.'</td>
    </tr>';//printr($p_code_id);
    if($invoice['delivery_charges']!='0.00')
	    $total_dis +=$invoice['delivery_charges'];
	    
	$final_tax= ($total_dis*$tax_gst)/100;
	if($p_code == '0')
	{
		if(!empty($dtl['rate']))
		{
			$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
						<td  width="200px"  style="border-bottom: 1px solid black;">'.$product_code_freight.'</td>
						<td  style="border-bottom: 1px solid black;">'.$frieght_qty.'</td>
						<td align="center"  style="border-bottom: 1px solid black;">'.$this->numberFormate($freight_rate,2).'</td>
						<td align="center"  style="border-bottom: 1px solid black;"></td>
						<td align="center"  style="border-bottom: 1px solid black;">'.$this->numberFormate($freight_tot,2).'</td>
					</tr>
					 <tr>
						<td></td>
						<td></td>
						<td></td> 
						<td  ></td>
						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot,2).'</td>
					</tr>';
		}
		if(!empty($dtl['case_breaking_fees']))
		{
					
					$html.=' <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
        						<td style="border-bottom: 1px solid black;">'.$product_code_case_fees.'</td>
        						<td style="border-bottom: 1px solid black;">'.$case_breaking_qty .'</td>
        						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($case_breaking_fees ,2).'</td>
        						<td style="border-bottom: 1px solid black;"></td>
        						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($case_breaking_fees_tot ,2).'</td>
        					</tr>
        					 <tr>
        						<td></td>
        						<td></td>
        						<td></td>
        						<td></td>
        						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot+$case_breaking_fees_tot,2).'</td>
        					</tr>';
		}
		if(!empty($dtl['label_charges']))
		{
					
					$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
        						<td style="border-bottom: 1px solid black;">'.$product_code_label_charges.'</td>
        						<td style="border-bottom: 1px solid black;">'.$label_charges_qty .'</td>
        						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($label_charges ,2).'</td>
        						<td style="border-bottom: 1px solid black;"></td>
        						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($label_charges_tot ,2).'</td>
        					</tr>
        					 <tr>
        						<td></td>
        						<td></td>
        						<td></td>
        						<td></td>
        						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot+$case_breaking_fees_tot+$label_charges_tot,2).'</td>
        					</tr>';
		}
		if(!empty($dtl['prepress_charges']))
		{
					
					$html.='  <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
        						<td style="border-bottom: 1px solid black;">'.$product_code_prepress_charges.'</td>
        						<td style="border-bottom: 1px solid black;">'.$prepress_charges_qty .'</td>
        						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($prepress_charges ,2).'</td>
        						<td  style="border-bottom: 1px solid black;"></td>
        						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($prepress_charges_tot ,2).'</td>
        					</tr>
        					 <tr>
        						<td></td>
        						<td></td>
        						<td></td>
        						<td></td>
        						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot+$case_breaking_fees_tot+$label_charges_tot+$prepress_charges_tot,2).'</td>
        					</tr>';
		}
	    if(!empty($dtl['handling_charge']))
		{
			$html.='  <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
						<td style="border-bottom: 1px solid black;">'.$product_code_handling_charge.'</td>
						<td style="border-bottom: 1px solid black;">'.$handling_charge_qty .'</td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($handling_charge ,2).'</td>
						<td  style="border-bottom: 1px solid black;"></td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($handling_charge_tot ,2).'</td>
					</tr>
					 <tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot+$case_breaking_fees_tot+$label_charges_tot+$prepress_charges_tot,2).'</td>
					</tr>';
		}
		if(!empty($dtl['re-stocking_charge']))
		{
			$html.='  <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
						<td style="border-bottom: 1px solid black;">'.$product_code_restocking_charge.'</td>
						<td style="border-bottom: 1px solid black;">'.$restocking_charge_qty .'</td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($restocking_charge ,2).'</td>
						<td  style="border-bottom: 1px solid black;"></td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($restocking_charge_tot ,2).'</td>
					</tr>
					 <tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot+$case_breaking_fees_tot+$label_charges_tot+$prepress_charges_tot,2).'</td>
					</tr>';
		}
		
		if(!empty($dtl['tintie_charges']))
		{
			$html.='  <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
						<td style="border-bottom: 1px solid black;">'.$product_code_tintie_charges.'</td>
						<td style="border-bottom: 1px solid black;">'.$tintie_charges_qty .'</td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($tintie_charges ,2).'</td>
						<td  style="border-bottom: 1px solid black;"></td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($tintie_charges_tot ,2).'</td>
					</tr>
					 <tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot+$case_breaking_fees_tot+$label_charges_tot+$prepress_charges_tot,2).'</td>
					</tr>';
		}
					$total_dis = $total_dis+$freight_tot+$case_breaking_fees_tot+$label_charges_tot+$prepress_charges_tot+$handling_charge_tot+$restocking_charge_tot+$tintie_charges_tot;
					
					$final_tax= ($total_dis*$tax_gst)/100;
					
					
					
	}
	if($invoice['delivery_charges']!='0.00'){
    	$html.='<tr>
					<td></td>
					<td></td>
					<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">Delivery Charges </td>
					<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($invoice['delivery_charges']),2).'</td>
			  </tr>';
	}
    $tax_price =$tax_sgst=$tax_igst=$tax_cgst= 0;
		//add  	Indonesia ->$invoice['country_destination'] != '112' sonu told by vijay sir  5-5-2017  
	if($invoice['country_destination'] != '42'&&  $invoice['country_destination'] != '112' && $invoice['country_destination'] != '111')
	{
	   $html.=' <tr>
        			<td></td>
        			<td></td>
        			'.$blank_td.'
        			<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);"> '.$th_gst.' '.round($tax_gst).'%</td>
        			<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($final_tax,2).'</td>
        		</tr>';
	}
		//add  	Indonesia -> $invoice['country_destination'] != '112' sonu told by vijay sir   5-5-2017
	else if($invoice['country_destination'] == '112'){
		
	}
	//end
	else if($invoice['country_destination'] == '111'){
		if ($invoice['tax_mode'] != 'sez_no_tax') {
				if(($invoice['tax_mode'] == 'With in Gujarat'))
					{
						$tax_sgst = $total_dis * ($invoice['sgst'] / 100);  

						$tax_cgst = $total_dis * ($invoice['cgst'] / 100);                      					

						$tax_price=$tax_sgst+$tax_cgst;

						
						$html.='<tr>
    								<td></td>
    								<td></div></td>
    								<td></td>
    								<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">SGST Tax ('.$invoice['sgst'].') %</td>
    								<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($tax_sgst),2).'</td>
							  </tr>';
						$html.='<tr>
    								<td></td>
    								<td></td>
    								<td></td>
    								<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">CGST Tax('.$invoice['cgst'].') % </td>
    								<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($tax_cgst),2).'</td>
							  </tr>';
					}
					else
						{
							$tax_igst = $total_dis * ($invoice['igst'] / 100);  

							$tax_price=$tax_igst;

							
							$html.='<tr>
    									<td></td>
    									<td></div></td>
    									<td></td>
    									<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">IGST Tax ('.$invoice['igst'].') %</td>
    									<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($tax_igst),2).'</td>
								  </tr>';
							
						}
		}else{
				$tax_price=$tax_igst;
		}
	    
	}
	else
	{
	     $pst_text=' Gst PST Tax';
        if($invoice['state']=='11')
            $pst_text='QST Tax';
	    
		if(($invoice['can_gst']!='0.000' || $invoice['pst']!='0.000') && $invoice['hst']=='0.000')
	
		{

				$tax_gst = $total_dis * ($invoice['can_gst'] / 100);  

				$tax_pst_price = $total_dis * ($invoice['pst'] / 100);                      					

				$tax_price=$tax_gst+$tax_pst_price;

				
				$html.='<tr>
    						<td></td>
    						<td></div></td>
    						<td></td>
    						<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">Gst Tax ('.$invoice['can_gst'].') %</td>
    						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($tax_gst),2).'</td>
					  </tr>';
				$html.='<tr>
    						<td></td>
    						<td></td>
    						<td></td>
    						<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$pst_text.' ('.$invoice['pst'].') % </td>
    						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($tax_pst_price),2).'</td>
					  </tr>';
		}

		else if($invoice['hst']!='0.000' && ($invoice['can_gst']=='0.000' && $invoice['pst']=='0.000'))

		{

				$tax_hst = $total_dis * ($invoice['hst'] / 100); 

				$tax_price = $tax_hst;
				
				$html.='<tr>
    						<td></td>
    						<td></td>
    						<td></td>
    						<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">HST Tax ('.$invoice['hst'].') %</td>
    						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($tax_hst),2).'</td>
					  </tr>';
				
		}

		else if($invoice['hst']=='0.000' && $invoice['can_gst']=='0.000' && $invoice['pst']=='0.000')

		{

				$tax_gst = $total_dis * ($invoice['can_gst'] / 100);  

				$tax_pst_price = $total_dis * ($invoice['pst'] / 100); 

				$tax_price=$tax_gst+$tax_pst_price;

				
				$html.='<tr>
    						<td></td>
    						<td></div></td>
    						<td></td>
    						<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);"> Gst Tax ('.$invoice['can_gst'].') %</td>
    						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($tax_gst),2).'</td>
					  </tr>';
				$html.='<tr>
    						<td></td>
    						<td></td>
    						<td></td>
    						<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$pst_text.' ('.$invoice['pst'].') % </td>
    						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($tax_pst_price),2).'</td>
					  </tr>';
		}
		
		
	}
	  if($set_user_id=='19')
        {
            /*if($invoice['delivery_charges']!='0.00'){
            	$html.='<tr>
    						<td></td>
    						<td></td>
    						<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">Delivery Charges </td>
    						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($invoice['delivery_charges']),2).'</td>
					  </tr>';
            }*/
            if($invoice['other_charges']!='0.00'){
            	$html.='<tr>
    						<td></td>
    						<td></td>
    						<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);"><b>'.$invoice['other_charges_comments'].'</b></td>
    						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($invoice['other_charges']),2).'</td>
					  </tr>';
            }
            if($details['extra_charges']!='0.00'){
            	$html.='<tr>
    						<td></td>
    						<td></td>
    						<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);"><b>'.$details['extra_charge_name'].'</b></td>
    						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate(($details['extra_charge']),2).'</td>
					  </tr>';
            }
	
        }
	
		//add  	Indonesia -> $invoice['country_destination'] == '112' sonu told by vijay bhai  5-5-2017 
	if($invoice['country_destination'] == '112'){
		$tax_price = 0;
		$final_tax = 0;
	}
	//end 

    $html.='  <tr>
            	<td></td>
                <td></td>
                '.$blank_td.'
                <td>Invoice Total '. $currency['currency_code'].'</td>
                <td align="center">'.$this->numberFormate(($total_dis+$final_tax+$tax_price+$invoice['other_charges']+$details['extra_charge']),2).'</td>
            </tr>';
   
       
   
	 //modify [kinjal] 10-2-2016
	
 $Payment_detail_customer = $this->Payment_detail_for_Customer($invoice['proforma_no']);
//rintr($Payment_detail_customer);
if(!empty($Payment_detail_customer)){
    $amout_paid=0;
    foreach($Payment_detail_customer as $payment){
    $html.='   <tr>
            	<td></td>
                <td></td>
                '.$blank_td.'
                <td style="border-bottom: 3px solid black;">'.$advance_status.'('.$payment['payment_mode'].') '. $currency['currency_code'].'</td>
                <td align="center" style="border-bottom: 3px solid black;">'.$this->numberFormate($payment['payment_amount'],2).'</td>
            </tr>';
               $amout_paid=$amout_paid+$payment['payment_amount'];
           } 
     
      //  printr($amout_paid);
    }
     else{
                $html.='
           <tr>
            	<td></td>
                <td></td>
                '.$blank_td.'
                <td style="border-bottom: 3px solid black;">'.$advance_status.' '. $currency['currency_code'].'</td>
                <td align="center" style="border-bottom: 3px solid black;">'.$this->numberFormate($amout_paid,2).'</td>
            </tr>';
     }   
            
          $html.='  <tr>
            	<td></td>
                <td></td>
                '.$blank_td.'
                <th align="left">Amount Due '. $currency['currency_code'].'</th>
                <th><div align="center">'.$this->numberFormate(((($total_dis+$final_tax+$tax_price+$invoice['other_charges']+$details['extra_charge'])-$amout_paid)),2).'</div></th>
            </tr>
	
    </table>
    <div class="line line-dashed  style="'.$page_style.' "></div>
    <table width="100%"  style="">
        ';
                $f_amt=$this->numberFormate(((($total_dis+$final_tax+$tax_price+$invoice['other_charges']+$details['extra_charge'])-$amout_paid)),2);
            	$sql_new = "UPDATE sales_invoice SET final_total = ".$f_amt." WHERE invoice_id=".$invoice_no." ";
	            $this->query($sql_new);		
             $due_amount=(($total_dis+$final_tax)-$amout_paid);
             
             if($due_amount!=0)
             {
                $html.='<tr ><th  width="50%" valign="top" align="left">Payment Due Date: '.date("d M Y",strtotime($invoice['invoice_date'])).'</th></tr>';
             }
         $html.='<tr>';
                 $html.='<td width="50%">
                            <table width="100%">
                                <tr>
                                    <td align="left"><strong><u>Bank Details:</u></strong></td>
                                </tr>
                                <tr>
                                    <td>';
                                    if($set_user_id=='44')
                                    {
                                         $html.='<table width="100%"  style="margin-top: 10px;">
                                                    <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
                            							<td><b>E-Transfer Details</b></td>
                            							<td>sales@pouchmakers.com</td>
                            						</tr>
                            						<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
                            							<td><b>Paypal Detail</b></td>
                            							<td>paypal.me/PouchMakersCanadaINC</td>
                            						</tr>
                            						</table>';
                                    } 
                                    if($set_user_id=='19')
                                    {
                                        $bank = $this->getBankData($invoice['proforma_no']);
                                        $html.='<table width="100%"  style="margin-top: 10px;">
                                                    <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
                            							<td><b>Beneficiary Name</b></td>
                            							<td>'.$bank['bank_accnt'].'</td>
                            						</tr>
                            						<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
                            							<td><b>Beneficiary Bank Name</b></td>
                            							<td>'.$bank['benefry_bank_name'].'</td>
                            						</tr>
                            						<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
                            							<td><b>Account Number</b></td>
                            							<td>'.$bank['accnt_no'].'</td>
                            						</tr>
                            						<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
                            							<td><b>IBAN Number/ IFSC Code</b></td>
                            							<td>'.$bank['swift_cd_hsbc'].'</td>
                            						</tr>
                            						<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
                            							<td><b>Beneficiary Bank Address</b></td>
                            							<td>'.$bank['benefry_bank_add'].'</td>
                            						</tr>
                            						<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
                            							<td><b>Intermediary Bank Name</b></td>
                            							<td>'.$bank['intery_bank_name'].'</td>
                            						</tr>
                            						<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
                            							<td><b>Swift Code of Intermediary Bank</b></td>
                            							<td>'.$bank['swift_cd_intery_bank'].'</td>
                            						</tr>
                                                </table>';
                                    }
                                    else
                                        $html.=nl2br($invoice['bank_address']);
                                        
                                    $html.='</td>
                                </tr>
                            </table>
                        </td>
        </tr>
    </table>

<div class="line line-dashed "></div>';
 $page_style='';
if($set_user_id!='10')
{           
            if($user_info['country_id']=='42')
                $page_style='page-break-after: always;';
		$html.='<table width="100%"  style="'.$page_style.'" >
		';
			$note=$this->query("SELECT logo,abn_no,note_invoice,termsandconditions_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'");
		if($invoice['country_destination']=='42')
		{
		 
		
			// printr($note);		
		    $html.='<tr>
				
				  <td valign="top" width="50%">'.$note->row['note_invoice'].'</td>
				</tr>';
		}
      /*  if($invoice['country_destination']=='235'||$invoice['country_destination']=='209'||$invoice['country_destination']=='251')
		{
		 
		//	$note=$this->query("SELECT logo,abn_no,note_invoice,termsandconditions_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'");
			// printr($note);		
		    $html.='<tr>
				/
				  <td valign="top" width="50%">'.$note->row['termsandconditions_invoice'].'</td>
				</tr>';
		}*/
	if($invoice['country_destination']=='14')
		{
		 
		
			// printr($note);		
		    $html.='<tr>
				
				  <td valign="top" width="50%"><b>Note: </b> Every delivery would be entitled to "Authority to Leave". Pouch Direct will  not accept responsibility for the safety of parcels if the products get lost or damaged once delivered by the transport company.<br></td>
				</tr>';
		}
			$html.='<tr>
				    <td>
				        <table>
				            <tr valign="top">
				                <td align="left"><strong>PAYMENT ADVICE</strong></td>
				            </tr>';
					  if(isset($set_user_id) && $set_user_id == '24')
						 $html.=' <tr><td>To: '.nl2br($invoice['company_address']).'<br></br>Attention : '.$atten_user.'<br><br>ABN : '.$data->row['abn_no'].'</td></tr></table></td>';
					 else
						 $html.=' <tr><td>To: '.nl2br($invoice['company_address']).'<br></br>Attention : '.$atten_user.'</td></tr></table></td>';
				
						  
				$html.='<td>
					<table width="100%">
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
							<td>'.$this->numberFormate(((($total_dis+$final_tax+$tax_price+$invoice['other_charges']+$details['extra_charge'])-$amout_paid)),3).'</td>
						</tr>';
						$amount_due=($total_dis+$final_tax);
						if($amount_due!=0)
						{
							$html.='<tr style="border-bottom: 3px solid  rgba(0, 0, 0, 0.09);">
    									<th>Due Date</th>
    									<td>'.date("d M Y",strtotime($invoice['invoice_date'])).'</td>
								    </tr>';
						}
					   $html.=' <tr>
							<th>Amount Enclosed</th>
							<td style="border-bottom: 3px solid black;"></td>
						</tr>
						<tr>
							<td></td>
							<td>'.$td_text.'</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>';
}

			 $html.='</div>
		</div>
	  </div>';
	 // printr($data);
	  // || $data->row['country_id'] == '214' || $data->row['country_id'] == '155'width:754px
	   	if(isset($user_info['country_id']) && ( $user_info['country_id'] == '14'))
		{
		    $html .='<div style=" border: 1px solid black;page-break-before: always;font-size: 10px;">'.$data->row['termsandconditions_invoice'].'</div>';
			    
		}
		if(isset($user_info['country_id']) && ($user_info['country_id'] == '42'))
		{
		    $html .='<div style=" border: 1px solid black;font-size: 12px;">'.$data->row['termsandconditions_invoice'].'</div>';
			    
		}
		if(isset($user_info['country_id']) && ( $user_info['country_id'] == '214'))
		{
			$html .='<div style=" border: 1px solid black;page-break-before: always;font-size: 14px;"><strong>Terms &amp; Conditions</strong><br />
					All payments have to be made within 3 days. If payment is not received or payment method is declined, the buyer forfeits the ownership of any items purchased. If no payment is received, no items will be shipped or collected. Once payment has made, you have agree that all details are true.<br />
					<br />
					<strong>Refund/Return Policy</strong><br />
					Items are not entitled to be refunded or returned. If an item is unsatisfactory, a written explanation is needed before the item may be considered for a replacement. If the item matches the description by the seller and the buyer is unsatisfied, seller is not responsible for refund/return.<br />
					<br />
					<strong>Custom Pouches :</strong><br />
					<br />
					1) 50% Advanced Deposit . Balance of 50% Payment before/upon Collection.<br />
					2) Quantity Variation Applies.<br />
					3) Once Finalize, No Exchanges or Returns are Allowed<br />
					4) All orders have to be Collected within 10 days<br />
					<br />
					<br />
					<strong>Custom Plastic Cups / Containers :</strong><br />
					1) 100% Advance for Purchase below $500<br />
					2) 50% Advanced Deposit for Purchase above $500. Balance of 50% Payment before/upon Collection.<br />
					3) Once Finalize, No Exchanges or Returns are Allowed<br />
					4) All orders have to be Collected within 10 days<br />
					I have read and agree to the terms and conditions.<br />
					<br />
					<span style="color:#FF0000">Authorised Signatory</span><br />
					<br />
					  ___________________ 
					<br><b>Sign here</b></div>';
		}
	
	
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
		$sql = "SELECT pc.*,p.product_name,pm.make_name,c.color,tm.measurement,pz.zipper_name,ps.spout_name,pa.product_accessorie_name,p.product_name_spanish,pm.make_name_spanish,c.color_spanish,pz.zipper_name_spanish,ps.spout_name_spanish,pa.product_accessorie_name_spanish FROM `" . DB_PREFIX . "product_code` AS pc LEFT JOIN product AS p ON pc.product=p.product_id LEFT JOIN product_make pm ON pc.make_pouch=pm.make_id LEFT JOIN pouch_color AS c ON c.pouch_color_id=pc.color LEFT JOIN template_measurement AS tm ON pc.measurement=tm.product_id  LEFT JOIN product_zipper AS pz ON pc.zipper=pz.product_zipper_id LEFT JOIN product_spout AS ps ON ps.product_spout_id=pc.spout LEFT JOIN product_accessorie AS pa ON pa.product_accessorie_id=pc.accessorie WHERE pc.product_code_id='".$product_code_id."' AND pc.is_delete=0 AND pc.status=1 ";
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
	
	public function getInvoice($user_type_id,$user_id,$data,$filter_data=array(),$is_delete,$add_book_id='0')
	{
		//sonu add 21-4-2017
		$add_id='';
		if($add_book_id!=0)
		   $add_id = "AND inv.address_book_id='". $add_book_id."'";
			
		
		
		//end
		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination  WHERE  inv.is_delete = '".$is_delete."' AND inv.gen_status='0'  $add_id " ;
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
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination WHERE  inv.is_delete = '".$is_delete."' AND inv.gen_status='0' AND  ((inv.user_id = '".(int)$set_user_id."' AND inv.user_type_id = '".(int)$set_user_type_id."' ) $str ) $add_id " ;
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
			if($filter_data['filter_customer_order_no'] != ''){
				$sql .= " AND exporter_orderno LIKE '%".$filter_data['filter_customer_order_no']."%' "; 	
			}
		}
		$sql .=' GROUP BY invoice_id ';
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " .$data['sort'];	
		} else {
			$sql .= " ORDER BY invoice_id";	
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
	
	public function getTotalInvoice($user_type_id,$user_id,$filter_data=array(),$is_delete,$add_book_id='0')
	{
		//SONU ADD 21-4-2017
		$add_id='';
		if($add_book_id!=0)
			$add_id = "AND address_book_id='". $add_book_id."'";
		
		//END
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "sales_invoice` WHERE is_delete = '".$is_delete."' AND gen_status='0' $add_id ";
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
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "sales_invoice` WHERE is_delete = '".$is_delete."' AND gen_status='0' AND ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str ) $add_id ";
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
			if($filter_data['filter_customer_order_no'] != ''){
				$sql .= " AND exporter_orderno LIKE '%".$filter_data['filter_customer_order_no']."%' "; 	
			}
			if(!empty($filter_data['user_name']))
			{
			$spitdata = explode("=",$filter_data['user_name']);
				$sql .=" AND user_type_id = '".$spitdata[0]."' AND user_id = '".$spitdata[1]."'";
			}
		}
	//	echo $sql;
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function updateInvoiceStatus($status,$data)
	{
		if($status == 0 || $status == 1){
			$sql = "UPDATE " . DB_PREFIX . "sales_invoice SET status = '" .(int)$status. "',  date_modify = NOW() WHERE invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');
			$sql = "UPDATE " . DB_PREFIX . "sales_invoice SET is_delete = '1', delete_by = '".$by."' ,date_modify = NOW() WHERE invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateInvoice($invoice_no,$status_value)
	{
		$sql = "UPDATE " . DB_PREFIX . "sales_invoice SET status = '".$status_value."', date_modify = NOW()  WHERE invoice_id = '" .(int)$invoice_no. "'";
		$this->query($sql);
	}
	
	public function getInvoiceProduct($invoice_id)
	{
		$sql = "SELECT ip.* FROM `" . DB_PREFIX . "sales_invoice_product` as ip WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	
	public function getUserEmployeeIds($user_type_id,$user_id)
	{
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids,GROUP_CONCAT(2) as type_ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	} 
	public function getLastIdSales() {
		$sql = "SELECT invoice_id FROM sales_invoice ORDER BY invoice_id DESC LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
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
	//mansi (for stock available quantity)
	
	public function getProductCd($product_code)
	{
		$result=$this->query("SELECT product_code,product_code_id,description FROM " . DB_PREFIX ."product_code WHERE product_code LIKE '%".$product_code."%' AND is_delete=0");
		return $result->rows;
	}
	
	public function getClientName($client_name)
	{
		$result=$this->query("SELECT sales_invoice_address_id,client_name,billing_information_address,delievery_address FROM " . DB_PREFIX ."sales_invoice_address WHERE client_name LIKE '".$client_name."%' AND is_delete=0");
		return $result->rows;
	}
	
	public function InsertGeneratedData($data,$country)
	{	
		$payment_maxico = implode(',',$data['payment']);
			$pay_type_maxico = $data['payment_type'];
			if($country == '155')
				$amt_maxico = "amt_maxico ='".$data['amt_maxico']."',";
			else
				$amt_maxico = "amount_paid ='".$data['amount_paid']."',";
				
			$detail_maxico =  addslashes($data['detail_maxico']);
			$tax_maxico = 0;
			if($country != '42')
				$tax_maxico = $data['tax_maxico'];
			
			$sql1 = "UPDATE `".DB_PREFIX."sales_invoice` SET payment_maxico = '".$payment_maxico."',pay_type_maxico='".$pay_type_maxico."', ".$amt_maxico." detail_maxico= '".$detail_maxico."' , tax_maxico = '".$tax_maxico."', payment_terms='".$data['payment_terms']."',curr_id='".$data['currency']."',gen_status='0'  Where invoice_id = '".$data['invoice_id']."'";
			$data1 = $this->query($sql1);
			
	}
	
	public function updatetax_invoice($invoice_id,$tax)
	{
	
		$sql = "SELECT qty,rate FROM  `".DB_PREFIX."sales_invoice_product` WHERE invoice_id='".$invoice_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
				$total=0;
				foreach($data->rows as $row)
				{
					$amt = $row['qty'] * $row['rate'];
					
					$total+=$amt;
				}
				$amt_tot = $this->numberFormate(($total+(($total * $tax)/100)),2);
				$sql1 = "UPDATE `".DB_PREFIX."sales_invoice` SET payment_terms='".$amt_tot."', tax_maxico = '".$tax."' Where invoice_id = '".$invoice_id."'";
				$data1 = $this->query($sql1);
				return $amt_tot ;				
		}else{
			return false;
		}
		
		
	
	}
//if u change in this fun so plzzzz also change this fun on proforma_product_code_wise model 
	public function getUserEmployeeIdsStock($user_type_id,$user_id)
	{
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids,GROUP_CONCAT(2) as type_ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
//if u change in this fun so plzzzz also change this fun on proforma_product_code_wise model 
	
	public function getStockQty($product_code_id,$pro_no)
	{
	
		
			if($_SESSION['LOGIN_USER_TYPE'] == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
				$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			}
			
			$admin_user_id=' AND pto.admin_user_id = '.$set_user_id;
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND (user_id='".(int)$set_user_id."' AND user_type_id='".(int)$set_user_type_id."' $str )";
			$data = $this->query($sql);
			$f_val=array();
			
				
			if($data->num_rows)
			{
				//$final_val=array();
				
				foreach($data->rows as $val)
				{
					//$final_val[]=$val['goods_master_id'];
					
					$sql2 = "SELECT sm.user_id,sm.user_type_id,sum(sm.qty)qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id,p.product_name ,gm.name,sm.row,sm.column_name,pc.product_code,sm.stock_id,gm.row as g_row,gm.column_name as g_col FROM stock_management as sm,product as p,product_code as pc,goods_master AS gm WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = gm.goods_master_id AND  sm.goods_id='".$val['goods_master_id']."' AND pc.product_code_id = sm.product_code_id  AND sm.product_code_id='".(int)$product_code_id."' AND parent_id=0  AND gm.is_delete = '0'AND sm.qty!=0 AND sm.row!=0 AND sm.column_name!=0 GROUP BY sm.row,sm.column_name ";
					
					$data2 = $this->query($sql2);					
					foreach($data2->rows as $data_arr)
					{
						//printr($data_arr);
						$f_val[]=$data_arr;
					
					
					}
					
				}    
				
			}
			$dis_qty_la = $r_qty = 0;
			foreach($f_val as $val)
			{
				$sql_dis= "SELECT sum(dispatch_qty) as total FROM stock_management WHERE is_delete=0 AND parent_id IN (" .$val['grouped_s_id']. ")" ;
				$data2_dis = $this->query($sql_dis);				
				$dis_qty_la +=$data2_dis->row['total'];
				$r_qty +=$val['qty'];
			}
			///$return = array('remaining_qty' =>$r_qty-$dis_qty_la,);
			$remaining_qty=$r_qty-$dis_qty_la;
		
		$sql4 = "SELECT tp.qty  FROM transfer_invoice as t,transfer_invoice_product as tp WHERE t.transfer_invoice_id = tp.invoice_id AND t.proforma_no='".$pro_no."' AND tp.product_code_id='".$product_code_id."' AND t.dis_or_warehouse='1'";
		$res4 = $this->query($sql4);
		
		//printr($res4);
		if($res4->num_rows > 0)
			$tran_qty=$res4->row['qty'];
		else
			$tran_qty='empty';
		
			//printr($tran_qty);
		//$remaining_qty=$result->row['qty']-$result->row['dispatch_qty'];
		//printr($remaining_qty);
		//$remaining_qty_new=$remaining_qty-$tran_qty;
		
		//printr($remaining_qty_new);
		$return = array('remaining_qty' =>$r_qty-$dis_qty_la,
						//'opening_stock_qty' =>$result->row['opening_stock_qty'],
						//'sales_qty'=>$res1->row['sales_qty'],
						//'pur_qty'=>$res2->row['pur_qty'],
						//'rem_qty_display'=> (($result->row['opening_stock_qty']+$res2->row['pur_qty'])-$res1->row['sales_qty']),
						'tran_qty' =>$tran_qty);
		
		
		return $return;
	}
	
	/*public function getStockQty($product_code_id,$pro_no)
	{
	
		if($_SESSION['LOGIN_USER_TYPE'] == 2){
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$userEmployee = $this->getUserEmployeeIdsStock($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$user_emp_id = $userEmployee['ids'];
			$user_emp_type_id=$userEmployee['type_ids'];
			 
			//  for user_id
			 $emp_id = explode(',',$user_emp_id);
			 array_push($emp_id,$parentdata->row['user_id']);
			 $emp_admin_id = implode(',', $emp_id);
			
			// for user_type_id	
			 $emp_type_id = explode(',',$user_emp_type_id);
			 array_push($emp_type_id,$parentdata->row['user_type_id']);
			  $emp_type_admin_id = implode(',',$emp_type_id);
					
		}else if($_SESSION['LOGIN_USER_TYPE'] == '4'){
		
			$userEmployee = $this->getUserEmployeeIdsStock($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
			$user_emp_id = $userEmployee['ids'];
			$user_emp_type_id=$userEmployee['type_ids'];
			
			 //  for user_id
			 $emp_id = explode(',',$user_emp_id);
			 array_push($emp_id,$_SESSION['ADMIN_LOGIN_SWISS']);
			 $emp_admin_id = implode(',', $emp_id);
			
			// for user_type_id	
			 $emp_type_id = explode(',',$user_emp_type_id);
			 array_push($emp_type_id,$_SESSION['LOGIN_USER_TYPE']);
			 $emp_type_admin_id = implode(',',$emp_type_id);
				
		}
	
		$sql3 = "SELECT sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id, sm.row,sm.column_name FROM stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '".$product_code_id."' AND sm.user_id IN (".$emp_admin_id.") AND sm.user_type_id In (".$emp_type_admin_id.")";

		$final=$this->query($sql3);
		$group_id=$final->row['grouped_s_id'];
	
		
		$sql = "SELECT * FROM
  	(SELECT sum(sm.qty) as qty,pc.opening_stock_qty,pc.product_code_id FROM stock_management as sm,product_code as pc WHERE sm.is_delete=0 AND sm.user_id IN (".$emp_admin_id.") AND sm.user_type_id In (".$emp_type_admin_id.") AND sm.product_code_id='".$product_code_id."' AND sm.product_code_id=pc.product_code_id ) qty
    INNER JOIN
    (SELECT sum(sm.dispatch_qty) as dispatch_qty,pc.opening_stock_qty,pc.product_code_id FROM stock_management as sm,product_code as pc WHERE sm.is_delete=0 AND sm.user_id IN (".$emp_admin_id.") AND sm.user_type_id In (".$emp_type_admin_id.") AND sm.product_code_id='".$product_code_id."' AND sm.product_code_id=pc.product_code_id AND parent_id IN ('".$group_id."') ) dis_qty
    on qty.product_code_id=dis_qty.product_code_id";
		
		$result=$this->query($sql);
		
		$sql1 = "SELECT SUM(sip.qty) as sales_qty FROM " . DB_PREFIX . "sales_invoice_product as sip, sales_invoice as si  WHERE sip.product_code_id = '".$product_code_id."' AND sip.invoice_id=si.invoice_id AND si.is_delete=0 AND si.gen_status='0' AND si.user_id IN (".$emp_admin_id.") AND si.user_type_id In (".$emp_type_admin_id.")";
		$res1=$this->query($sql1);
		
		$sql2 = "SELECT SUM(pip.qty) as pur_qty FROM " . DB_PREFIX . "purchase_invoice_product as pip, purchase_invoice as pi  WHERE pi.is_delete = 0 AND pip.invoice_id=pi.invoice_id AND pip.product_code_id = '".$product_code_id."' AND pi.user_id IN (".$emp_admin_id.") AND pi.user_type_id In (".$emp_type_admin_id.")";
		$res2=$this->query($sql2);
		
		
		$sql4 = "SELECT tp.qty FROM transfer_invoice as t,transfer_invoice_product as tp WHERE t.transfer_invoice_id = tp.invoice_id AND t.proforma_no='".$pro_no."' AND tp.product_code_id='".$product_code_id."' AND t.dis_or_warehouse='1'";
		$res4 = $this->query($sql4);
        //echo $sql4;
		//printr($res4);
		if($res4->num_rows > 0)
			$tran_qty=$res4->row['qty'];
		else
			$tran_qty='empty';
		
			
		$remaining_qty=$result->row['qty']-$result->row['dispatch_qty'];
		$return = array('remaining_qty' =>$remaining_qty,
						'opening_stock_qty' =>$result->row['opening_stock_qty'],
						'sales_qty'=>$res1->row['sales_qty'],
						'pur_qty'=>$res2->row['pur_qty'],
						'rem_qty_display'=> (($result->row['opening_stock_qty']+$res2->row['pur_qty'])-$res1->row['sales_qty']),
						'tran_qty' =>$tran_qty);
		
        //printr($return);
        return $return;
	}*/
	public function updatePaymentTerms($invoice_id,$payment_terms)
	{
		$sql2 ="UPDATE `".DB_PREFIX."sales_invoice` SET payment_terms='".$this->numberFormate($payment_terms,2)."' WHERE invoice_id ='".$invoice_id."' ";
		$this->query($sql2);
		
	}
	// mansi 8-1-16
	public function getProformaDetail($proforma_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX ." proforma WHERE proforma_id='".$proforma_id."'";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	//mansi 9-2-2016
	//modify [kinjal] [mansi] 10-2-2016
	public function updateTotalInvoiceAmount($invoice_no)
    {
    		
    	//$invoice=$this->getInvoiceData($invoice_no);
    	//printr($invoice);
    	
    	$invoice=$this->getInvoiceNetData($invoice_no);
    	//printr($invoice);	
    	$alldetails=$this->getInvoiceProduct($invoice_no);
    	$admin_detail =$this->getUser($invoice['user_id'],$invoice['user_type_id']);
    	$subtotal=0;$tot_tax=0;$product_code_freight=$p_code_id=$frieght_qty=$freight_rate=$freight_tot=$dis='';
    	foreach($alldetails as $details)
    	{
    		///printr($details);
    		if($details['product_code_id'] == '0')
    		{
    			$p_code_id=$details['product_code_id'];
    			$frieght_qty = $details['qty'];
    			$freight_rate = $details['rate'];
    			$freight_tot = $frieght_qty * $freight_rate;
    			
    		}
    		$tot=$details['qty']*$details['rate'];
    		//echo '<br>$tot'.$tot.'='.$details['qty'].'*'.$details['rate']; 
    		
    		$tax_gst=$invoice['tax_maxico'];
    		//echo '<br>$tax_gst'.$tax_gst;
    		$tax=$fri='0';
    		if($details['product_code_id'] == '0')
    		{
    			$tax=((($tot*$tax_gst)/100));
    			//echo '<br>$tax'.$tax.'=((('.$tot*$tax_gst.')/100))';
    			//echo '<br> $fri = $tot '.$fri. '=' .$tot ;
    			$fri = $tot;
    		}
    		$tot_tax=($tot_tax+(($tot*$tax_gst)/100));
    		//echo '<br> $tot_tax='.$tot_tax.'=('.$tot_tax.'+(('.$tot.'*'.$tax_gst.')/100))';
    		$subtotal=$subtotal+$tot-$fri;
    		//echo '<br>$subtotal'.$subtotal.'='.$subtotal.'+'.$tot.'-'.$fri;
    		
    	} 
    	//$total_dis=$subtotal-$invoice['discount'];
    	$total_dis=$subtotal-(($subtotal*$invoice['discount'])/100);
    	//echo '<br> $total_dis =' .$total_dis. '=' .$subtotal. '-' .$invoice['discount'];
    	$tax_gst=$invoice['tax_maxico'];
    	//echo '<br> $tax_gst =' .$invoice['tax_maxico'];
    	$final_tax= ($total_dis*$tax_gst)/100;
    	//echo '<br> $final_tax =' .$total_dis. '*' .$tax_gst. '/ 100';
    	if($admin_detail['international_branch_id']=='10')
    	{
    		$amout_paid=$invoice['amt_maxico'];
    		//echo '<br> $amout_paid=' .$invoice['amt_maxico'];
    		
    	}
    	else
    	{
    		$amout_paid=$invoice['amount_paid'];
    		///echo '<br> $amout_paid =' .$invoice['amount_paid'];
    	}
    	$tax_price = 0;
    	if($p_code_id == 0)
    	{
    		$total_dis = $total_dis+$freight_tot;
    		///echo '<br>total_dis Frieght '.$total_dis.' ='. $total_dis.'+'.$freight_tot;
    		$final_tax= ($total_dis*$tax_gst)/100;
    		///echo '<br>final_tax fright'.$final_tax.'= ('.$total_dis.'*'.$tax_gst.')/100';
    	}
    	if($admin_detail['international_branch_id'] == '44')
    	{
    			//add  	Indonesia -> $proforma['destination']!='112' sonu told by vijay sir   5-5-2017  
    		 if($invoice['country_destination'] == '112' ){
    			$tax_price = 0; 
    			$final_tax = 0;
    		}
    		//end
    		
    		else if(($invoice['can_gst']!='0.000' || $invoice['pst']!='0.000') && $invoice['hst']=='0.000')
    		{
    				$tax_gst = $total_dis * ($invoice['can_gst'] / 100);  
    
    				$tax_pst_price = $total_dis * ($invoice['pst'] / 100);                      					
    
    				$tax_price=$tax_gst+$tax_pst_price;
    		}
    		else if($invoice['hst']!='0.000' && ($invoice['can_gst']=='0.000' && $invoice['pst']=='0.000'))
    		{
    			$tax_hst = $total_dis * ($invoice['hst'] / 100); 
    			$tax_price = $tax_hst;
    		}
    		
    		else if($invoice['hst']=='0.000' && $invoice['can_gst']=='0.000' && $invoice['pst']=='0.000')
    		{
    			$tax_gst = $total_dis * ($invoice['can_gst'] / 100);  
    
    			$tax_pst_price = $total_dis * ($invoice['pst'] / 100); 
    
    			$tax_price=$tax_gst+$tax_pst_price;
    		}
    	}
    	if($invoice['country_destination'] == '111'){
    		if ($invoice['tax_mode'] != 'sez_no_tax') {
    				if(($invoice['tax_mode'] == 'With in Gujarat'))
    					{
    						$tax_sgst = $total_dis * ($invoice['sgst'] / 100);  
    
    						$tax_cgst = $total_dis * ($invoice['cgst'] / 100);                      					
    
    						$tax_price=$tax_sgst+$tax_cgst;
    
    					}else{
    						$tax_igst = $total_dis * ($invoice['igst'] / 100);  
    
    							$tax_price=$tax_igst;
    
    					}
    		}else{
    			$tax_price=$tax_igst;
    		}
    	}
    $Payment_detail_customer = $this->Payment_detail_for_Customer($invoice['proforma_no']);
    //rintr($Payment_detail_customer);
    if(!empty($Payment_detail_customer)){
       $amout_paid=0;
        foreach($Payment_detail_customer as $payment){
                   $amout_paid=$amout_paid+$payment['payment_amount'];
               }
        }else{$amout_paid=$amout_paid;}
    	
    	$final_amount=$this->numberFormate(((($total_dis+$final_tax+$tax_price)-$amout_paid)),3);
    	//echo ' <br> $final_amount='.$final_amount.'='.$total_dis.'+'.$final_tax.'+'.$tax_price.')-'.$amout_paid.')';
    	//die;
    	$sql_new = "UPDATE sales_invoice SET final_total = ".$final_amount." WHERE invoice_id=".$invoice_no." ";
    	//echo '<br>'.$sql_new;
    	$this->query($sql_new);		
    		
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
	public function genCreditNote($data)
	{
        $get = "SELECT MAX(sr_no) as max_no FROM sales_credit_note WHERE invoice_id = '".$data['gen_invoice_id']."' AND is_delete=0";
		$data_get = $this->query($get);
		$sr_no = $data_get->row['max_no']+1;
		//printr($data);die;
		$max_cn = "SELECT sales_credit_note_id FROM sales_credit_note ORDER BY sales_credit_note_id DESC LIMIT 1";
		$max = $this->query($max_cn);
		if(!$max->num_rows)
			$cn_no = 1;
		else
			$cn_no = $max->row['sales_credit_note_id']+1;
			
		if(isset($data['post']))
		{
					
			foreach($data['post'] as $post)
			{
				$qty = $data['qty_change_'.$post];
				$pur_id=str_pad($cn_no,5,'0',STR_PAD_LEFT);
				$cre_no='CN-'.$pur_id;
				$sql = "INSERT INTO sales_credit_note SET invoice_id = '".$data['gen_invoice_id']."',invoice_product_id = '" .$post. "',invoice_date= NOW(),qty = '".$qty."',rack_remaining_qty = '".$qty."',reason = '".$data['reason']."',sr_no = '".$sr_no."',cre_no='".$cre_no."',refund_amount='".$data['refund_amonut']."',other_charges = '".$data['other_charge']."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW(),date_modify=NOW()";
				$this->query($sql);
			}
			return 1;
		}
		else
		{
			return 0;
		}
	}
	public function getCreditNoteDetail($invoice_product_id)
	{
		$sql ="SELECT * FROM sales_credit_note WHERE invoice_product_id = '".$invoice_product_id."' AND is_delete=0 ORDER BY date_added DESC";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->row;
		} else {
			return false;
		}
	}
	public function getCredit($invoice_id)
	{
		$sql ="SELECT * FROM sales_credit_note WHERE invoice_id = '".$invoice_id."' AND is_delete=0 GROUP BY cre_no";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->rows;
		} else {
			return false;
		}
	}
	public function getFullCreditDetail($invoice_id,$cre_no)
	{
		$sql = "SELECT scn.*,s.product_code_id,s.product_description,s.rate,s.sin_account_code FROM sales_credit_note as scn, sales_invoice_product as s WHERE scn.invoice_id = '".$invoice_id."' AND cre_no='".$cre_no."' AND s.invoice_product_id = scn.invoice_product_id AND scn.is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->rows;
		} else {
			return false;
		}
	}
	public function viewCreditNote($invoice_no,$cre_no)
	{
			$invoice=$this->getInvoiceNetData($invoice_no);
			$currency=$this->getCurrencyName($invoice['curr_id']);	
			$alldetails=$this->getFullCreditDetail($invoice_no,$cre_no);
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
				$user_info=$this->getUser($set_user_id,'4');
				$data=$this->query("SELECT logo,abn_no,note_invoice,termsandconditions_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'");
				
				if(isset($data->row['logo']) &&  $data->row['logo']!= '')
				{
					$image= HTTP_UPLOAD."admin/logo/200_".$data->row['logo'];
					$img = '<img src="'.$image.'" alt="Image">';
				}
				else
				{
					$img ='';
				}			
			}
			
			$au_user=$this->getUser($invoice['user_id'],$invoice['user_type_id']);
			$atten_user = $au_user['first_name'].' '.$au_user['last_name'];
			$order_number = $invoice['buyers_orderno'];
			if($invoice['country_destination']=='214')
			{	 
				$th_gst = 'TAX GST @';
				$advance_status = 'Total Net Payments';
				$tax_per = 'Tax Exempt';
			}
			else if($invoice['country_destination']=='42')
			{
				if(($invoice['can_gst']!='0.000' || $invoice['pst']!='0.000') && $invoice['hst']=='0.000')
				{
					$th_gst = 'GST & PST';
					$tax_per = 'GST : '.round($invoice['can_gst']).' % <br>' .'PST : '.round($invoice['pst']).' %';
				}
				else if($invoice['hst']!='0.000' && ($invoice['can_gst']=='0.000' && $invoice['pst']=='0.000'))
				{
					$th_gst = 'HST';
					$tax_per = round($invoice['hst']).' % ';
				}
				else if($invoice['hst']=='0.000' && $invoice['can_gst']=='0.000' && $invoice['pst']=='0.000')
				{
					$th_gst = 'GST & PST';
					$tax_per = 'GST : '.round($invoice['can_gst']).' % <br>' .'PST : '.round($invoice['pst']).' %';
				}
				$advance_status = 'Advance Received';
			}
			else
			{
				if($invoice['country_destination']=='155')
				{
					$th_gst = 'VAT';
					$order_number = $invoice['exporter_orderno'];
				}
				else
				{
					$th_gst = 'GST';
				}
				$advance_status = 'Advance Received';
				$tax_per = round($invoice['tax_maxico']).' % ';
			}
			$html='';
			$qst_no=$invoice['qst_no'];
			$html.='<style> table {border-collapse: collapse;}
							td{padding: 6px;}
						  .line-dashed 
						   {
							  border-style: dashed;
							  background: transparent;
						  }
						  .line ]
						  {
							  height: 2px;
							  margin: 10px 0;
							  font-size: 0;
							  overflow: hidden;
							  background-color: #fff;
							  border-width: 0;
							  border-top: 1px solid #e0e4e8;
						 }
					</style>
					<div id="print_div" style="padding-top: 0px; border: 1px solid black;">
						<center>'.$img.'</center><br><br>
							<div>
								<div id="thetable" style="width: 100%;">';
								$fixdata = $this->getFixmaster(); 
								$html.='<table width="100%">
											<tr>
												<td valign="top" ><h2>CREDIT NOTE</h2></td>
												<td valign="top">
													<table>
														<tr>
															<td>
																<strong>Date</strong>
															</td>
														</tr>
														<tr>
															<td>'.date("d M Y",strtotime($alldetails[0]['date_added'])).'
															</td>
														</tr>
													</table>
												</td>';
												 if(isset($set_user_id) && $set_user_id == '24')
												 {
													 $html.=' <td valign="top" rowspan="2">'.str_ireplace('-',$atten_user,nl2br($invoice['company_address'])).'<br><br>ABN : '.$data->row['abn_no'].'</td>';
												 }
												 else
												 { //<tr><td><strong>Account Number</strong></td></tr><tr><td></td></tr>
													$html.=' <td valign="top" rowspan="2">'.nl2br($invoice['company_address']).'<br></br>Attention : '.$atten_user.'</td>';
												 }
								  $html.=' </tr>
											<tr>
												<td valign="top">'.stripslashes($invoice['customer_name']).'<br>'.nl2br($invoice['consignee']).'</td>
												<td valign="top">
													<table>
															<tr><td><strong>Credit Note #</strong></td></tr><tr><td>'.$alldetails[0]['cre_no'].'</td></tr>
															<tr><td><strong>Reference</strong></td></tr><tr><td>'.$invoice['invoice_no'].'</td></tr>';
															if($invoice['country_destination']=='42')
															{
																$html.='<tr><td><strong>QST No.</strong></td></tr><tr><td>'.$qst_no.'</td></tr>';
															}
															if(isset($set_user_id) && $set_user_id == '24')
															{
																$html.='<tr><td><strong>ABN</strong></td></tr><tr><td>'.$data->row['abn_no'].'</td></tr>';
															}
											$html.=' </table>
												</td> 
											</tr>
										</table>
									
										<table  width="100%" style="margin-top: 50px; ">
											<tr style="border-bottom: 3px solid black; height:40px">
												<th width="200px" align="left">Description</th>
												<th align="left">Quantity</th>
												<th align="left">Unit Price</th>
												<th align="left">'.$th_gst.'</th>
												<th align="left">Amount '. $currency['currency_code'].'</th>
											</tr>';
											$subtotal=0;$tot_tax=$freight_tot=0;$other_charges=0;$product_code_freight=$p_code_id=$frieght_qty=$freight_rate=$dis='';
											if(isset($alldetails) && $alldetails!='')
											{
												foreach($alldetails as $details)
												{
													
													if($details['product_code_id'] == '-1')
													{
														$product_code = $details['product_description'];
													}
													elseif($details['product_code_id'] == '0')
													{
														$product_code_freight = 'Freight Charges : '.$details['rate'];
														$p_code_id=$details['product_code_id'];
														$frieght_qty = $details['qty'];
														$freight_rate = $details['rate'];
														$freight_tot = $frieght_qty * $freight_rate;
													}
													else
													{
														$product_code_details=$this->getProductCode($details['product_code_id']);
														$product_code =$product_code_details['description'];
													}
														
													
														$tot=$details['qty']*$details['rate'];
														$tax_gst=$invoice['tax_maxico'];
														
														
														$tax=$fri='0';
														if($details['product_code_id'] == '0')
														{
															$tax=((($tot*$tax_gst)/100));
															$fri=$tot;
														}
														$tot_tax=($tot_tax+(($tot*$tax_gst)/100));
														$subtotal=$subtotal+$tot+$details['other_charges']-$fri;
														 if($details['product_code_id'] != '0')
														{
															$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
																		<td width="200px">'.$product_code.'</td>
																		<td>'.$details['qty'].'</td>
																		<td>'.$this->numberFormate($details['rate'],3).'</td>
																		<td>'.$tax_per.'</td>
																		<td>'.$this->numberFormate($tot,3).'</td>
																   </tr>';
														}
													
												}
											}
												 
											//$total_dis=$subtotal-$invoice['discount'];
											$total_dis=$subtotal-(($subtotal*$invoice['discount'])/100);
											$tax_gst=$invoice['tax_maxico'];
											$final_tax= ($total_dis*$tax_gst)/100;
											
											if($invoice['country_destination']=='155')
											{
												$amout_paid=$invoice['amt_maxico'];
												
											}
											else
											{
												$amout_paid=$invoice['amount_paid'];
											}
									
											if($p_code_id == '0')
											{
												$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
																<td  width="200px">'.$product_code_freight.'</td>
																<td>'.$frieght_qty.'</td>
																<td>'.$this->numberFormate($freight_rate,3).'</td>
																<td></td>
																<td>'.$this->numberFormate($freight_tot,3).'</td>
															</tr>
															 ';
															 
															$total_dis = $total_dis+$freight_tot;
															$final_tax= ($total_dis*$tax_gst)/100;
											}
											if($details['other_charges']!='0.0000')
													{
															$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
																	<td width="200px">Other Charges </td>
																	<td></td>
																	<td>'.$this->numberFormate($details['other_charges'],3).'</td>
																	<td></td>
																	<td>'.$this->numberFormate($details['other_charges'],3).'</td>
															   </tr>';
														
													}
												
													$html.='<tr>
																<td></td>
																<td></td>
																<td></td>
																<td>Sub Total</td>
																<td>'.$this->numberFormate($subtotal+$freight_tot,3).'</td>
															</tr>';
											$tax_price = 0;
											if($invoice['country_destination'] != '42')
											{
											   $html.=' <tr>
															<td></td>
															<td></td>
															<td></td>
															<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);"> '.$th_gst.' '.round($tax_gst).'%</td>
															<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($final_tax,3).'</td>
														</tr>';
											}
											else
											{
												if(($invoice['can_gst']!='0.000' || $invoice['pst']!='0.000') && $invoice['hst']=='0.000')
											
												{
										
														$tax_gst = $total_dis * ($invoice['can_gst'] / 100);  
										
														$tax_pst_price = $total_dis * ($invoice['pst'] / 100);                      					
										
														$tax_price=$tax_gst+$tax_pst_price;
										
														
														$html.='<tr>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">Gst Tax ('.$invoice['can_gst'].') %</td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">'.$tax_gst.'</td>
															  </tr>';
														$html.='<tr>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">PST Tax('.$invoice['pst'].') % </td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">'.$tax_pst_price.'</td>
															  </tr>';
												}
										
												else if($invoice['hst']!='0.000' && ($invoice['can_gst']=='0.000' && $invoice['pst']=='0.000'))
										
												{
										
														$tax_hst = $total_dis * ($invoice['hst'] / 100); 
										
														$tax_price = $tax_hst;
														
														$html.='<tr>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">HST Tax ('.$invoice['hst'].') %</td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">'.$tax_hst.'</td>
															  </tr>';
														
												}
										
												else if($invoice['hst']=='0.000' && $invoice['can_gst']=='0.000' && $invoice['pst']=='0.000')
										
												{
										
														$tax_gst = $total_dis * ($invoice['can_gst'] / 100);  
										
														$tax_pst_price = $total_dis * ($invoice['pst'] / 100); 
										
														$tax_price=$tax_gst+$tax_pst_price;
										
														
														$html.='<tr>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);"> Gst Tax ('.$invoice['can_gst'].') %</td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">'.$tax_gst.'</td>
															  </tr>';
														$html.='<tr>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">PST Tax('.$invoice['pst'].') % </td>
																	<td style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">'.$tax_pst_price.'</td>
															  </tr>';
												}
											}
										
									$html.='<tr>
												<td></td>
												<td></td>
												<td></td>
												<td>Total Amount '. $currency['currency_code'].'</td>
												<td>'.$this->numberFormate(($total_dis+$final_tax+$tax_price),3).'</td>
											</tr>';
											$refund_amt = $alldetails[0]['refund_amount'];
											$html.='
										   <tr>
												<td></td>
												<td></td>
												<td></td>
												<td style="border-bottom: 3px solid black;">Less Credit To Invoice(s) / Refund (s) '. $currency['currency_code'].'</td>
												<td style="border-bottom: 3px solid black;">'.$this->numberFormate($refund_amt,3).'</td>
											</tr>
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td align="left">Remaining Credit '. $currency['currency_code'].'</th>
												<td align="left">'.$this->numberFormate(((($total_dis+$final_tax+$tax_price)-$refund_amt)),3).'</th>
											</tr>
									
									</table>
						
									<hr style="border-top: dotted 2px;" />
							
									<table width="100%"  style="margin-top: 50px;" >
										<tr>
											<td>
												<table>
													<tr valign="top">
														<td>
															<strong><h2>CREDIT ADVICE</h2></strong>
														</td>
													</tr>
												</table>
											</td>
											<td>
													<table>
														<tr>
															<th>Customer </th>
															<td>'.$invoice['customer_name'].'</td>
														</tr>
														<tr>
															<th>Credit Note #</th>
															<td>'.$alldetails[0]['cre_no'].'</td>                    
														</tr>
														<tr>
															<th>Credit Amount</th>
															<td>'.$refund_amt.'</td>
														</tr>
													</table>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<table>
													<tr valign="top">
														<td valign="top">';
															$abn ='';
															if(isset($set_user_id) && $set_user_id == '24')
															{
																$abn='ABN: '.$data->row['abn_no'].'.';
															}
																
															$html.='<p>'.$abn.'Registered Office : '.preg_replace("/(\/[^>]*>)([^<]*)(<)/","\\1\\3",$invoice['company_address']).'</p>
														</td>
													</tr>
												</table>
											</td>
										</tr>	
									</table>	
							</div>
						</div>
				 </div>';
				return $html;	
				//SELECT si.*,sc.* FROM sales_invoice_product si LEFT OUTER JOIN sales_credit_note sc ON si.invoice_product_id = sc.invoice_product_id WHERE si.is_delete=0 AND si.`invoice_id`='469' GROUP BY si.`invoice_product_id`
	}
	public function removeCredit($sales_credit_note_id)
	{
		$sql = $this->query("UPDATE sales_credit_note SET is_delete='1' WHERE sales_credit_note_id='".$sales_credit_note_id."'");
	}
	public function edit_cre_qty($sales_credit_note_id,$qty,$refund_amt,$invoice_id,$sr_no)
	{
		//printr($refund_amt);//die;
		if($refund_amt == 'refund')
		{
			$var = "qty='".$qty."' ,";
			$where = "sales_credit_note_id='".$sales_credit_note_id."'";
		}
		else
		{
			$var = "refund_amount='".$refund_amt."' ,";
			$where = "invoice_id='".$invoice_id."' AND sr_no='".$sr_no."'";
		}
		
		/*echo "UPDATE sales_credit_note SET ".$var." date_modify=NOW() WHERE ".$where;
		die;*/
		$sql = $this->query("UPDATE sales_credit_note SET ".$var." date_modify=NOW(),refund_amount='".$refund_amt."' WHERE ".$where);
	}
	
	function getReorderDate()
	{
		$sql = "SELECT * FROM  sales_invoice WHERE reorder_date!='0000-00-00' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->rows;
		} else {
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
	
	//kavita 12-4-2017
	public function getLastIdAddress() {
		$sql = "SELECT address_book_id FROM  address_book_master ORDER BY address_book_id DESC LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}//END
	//[kinjal] on 21-6-2017
    public function ChangedProformaStatus($pro_no)
	{
		//printr($pro_no);die;
		$sql="SELECT * FROM sales_invoice WHERE proforma_no='".$pro_no."' AND is_delete=0 AND status=1";
		$data = $this->query($sql);
		//printr($data);
		$pro_d = $this->query("SELECT proforma_id FROM proforma_product_code_wise WHERE pro_in_no='".$pro_no."'");
		$proforma_id = $pro_d->row['proforma_id'];
		$sale_arr=array();
        if ($data->num_rows) {
			foreach($data->rows as $row)
			{
				//printr($row);
				$sql1="SELECT sum(qty) as total_qty,product_code_id FROM sales_invoice_product WHERE invoice_id='".$row['invoice_id']."' AND customer_dispatch_p='0' GROUP BY invoice_product_id ";
				$data1 = $this->query($sql1);
				//printr($data1);
				foreach($data1->rows as $r)
				{
					$sales[$row['invoice_id']][$r['product_code_id']]=$r;
				}
			
			}
			/*if($_SESSION['LOGIN_USER_TYPE']=='2' && $_SESSION['ADMIN_LOGIN_SWISS']=='56')
			    printr($sales);*/
			    
			foreach($sales as $sale)
			{
				
				foreach($sale as $key=>$sa)
				{
					/*if($_SESSION['LOGIN_USER_TYPE']=='2' && $_SESSION['ADMIN_LOGIN_SWISS']=='56')
					    printr($key);printr($sa['total_qty']);*/
					//printr($sa['total_qty']);
					$sale_arr[$key]+=$sa['total_qty'];
				}
			}
			/*if($_SESSION['LOGIN_USER_TYPE']=='2' && $_SESSION['ADMIN_LOGIN_SWISS']=='56')
			       printr($sale_arr);die;*/
			//printr($sale_arr);die;
			$sql2="SELECT quantity,product_code_id,proforma_invoice_id,sales_qty FROM proforma_invoice_product_code_wise WHERE proforma_id='".$proforma_id."' GROUP BY proforma_invoice_id";
			$data2 = $this->query($sql2);
			
			foreach($data2->rows as $p)
			{
				$proforma[$p['product_code_id']]=$p;
			}
			//printr($proforma);
			///printr($proforma);
			//die;
			foreach($sale_arr as $key=> $s)
			{//printr($proforma[$key]['product_code_id'].'====='.$key);
				if($proforma[$key]['product_code_id']==$key)
				{ 
					  $sales_qty = $proforma[$key]['quantity']-$s;
					  //echo $sales_qty.'====sales_qty'.$proforma[$key]['proforma_invoice_id'];
					  $pro = "UPDATE " . DB_PREFIX . "proforma_invoice_product_code_wise SET sales_qty = '".$sales_qty."' WHERE proforma_invoice_id = '" .$proforma[$key]['proforma_invoice_id']. "'";
					  $this->query($pro);
					  
					  
				}
				//if($proforma[$key]['sales_qty']==0)
					
			}
			
			$pro_data = "select * from " . DB_PREFIX . "proforma_invoice_product_code_wise where proforma_id = '" . $proforma_id . "' AND is_delete = '0' AND customer_dispatch_p='0'";
			$pro_count=$this->query($pro_data);
			$count_pro = $pro_count->num_rows;
			//printr($count_pro);
			$sql_count = "SELECT sales_qty FROM `" . DB_PREFIX . "proforma_invoice_product_code_wise`  WHERE proforma_id = '" .(int)$proforma_id . "' AND is_delete='0' AND sales_qty='0' AND customer_dispatch_p='0'";
			$data_count = $this->query($sql_count);
			$count = $data_count->num_rows;
			//printr($data_count);
			
			//echo $count.'===='.$count_pro;
			if($count == $count_pro)
			{
				$sql1 ="UPDATE proforma_product_code_wise SET sales_status='1' WHERE proforma_id='".$proforma_id."' ";
				$this->query($sql1);
			}
			//printr($sql1);
			
			
			
        }
		
	}
	public function gettotalWithoutCyli($sales_id)
	{
		$invoice=$this->getInvoiceNetData($sales_id);
		$alldetails=$this->getInvoiceProduct($sales_id);
		$subtotal=0;$tot_tax=0;$product_code_freight=$p_code_id=$frieght_qty=$freight_rate=$freight_tot=$dis='';
		if(!empty($alldetails))
		{
    		foreach($alldetails as $details)
    		{
    			$pro_id = $this->query("SELECT product FROM product_code WHERE product_code_id = ".$details['product_code_id']);
    			if($details['product_code_id']!=0 && $pro_id->row['product']!='51')
    			{
    				$tot=$details['qty']*$details['rate'];
    				$tax_gst=$invoice['tax_maxico'];
    				$tot_tax=($tot_tax+(($tot*$tax_gst)/100));
    				$subtotal=$subtotal+$tot;
    			}
    			
    		}
		}
		$total_dis=$subtotal-(($subtotal*$invoice['discount'])/100);
		$tax_gst=$invoice['tax_maxico'];
		$final_tax= ($total_dis*$tax_gst)/100;
		$tax_price = 0;
		if($invoice['country_destination'] == '42' && $invoice['country_destination'] == '112' )
		{
			if($invoice['country_destination'] == '112' ){
				$tax_price = 0; 
				$final_tax = 0;
			}
			else if(($invoice['can_gst']!='0.000' || $invoice['pst']!='0.000') && $invoice['hst']=='0.000')
			{
					$tax_gst = $total_dis * ($invoice['can_gst'] / 100);  

					$tax_pst_price = $total_dis * ($invoice['pst'] / 100);                      					

					$tax_price=$tax_gst+$tax_pst_price;
			}
			else if($invoice['hst']!='0.000' && ($invoice['can_gst']=='0.000' && $invoice['pst']=='0.000'))
			{
				$tax_hst = $total_dis * ($invoice['hst'] / 100); 
				$tax_price = $tax_hst;
			}
			
			else if($invoice['hst']=='0.000' && $invoice['can_gst']=='0.000' && $invoice['pst']=='0.000')
			{
				$tax_gst = $total_dis * ($invoice['can_gst'] / 100);  

				$tax_pst_price = $total_dis * ($invoice['pst'] / 100); 

				$tax_price=$tax_gst+$tax_pst_price;
			}
		}
		if($invoice['country_destination'] == '111'){
			if ($invoice['tax_mode'] != 'sez_no_tax') {
					if(($invoice['tax_mode'] == 'With in Gujarat'))
						{
							$tax_sgst = $total_dis * ($invoice['sgst'] / 100);  

							$tax_cgst = $total_dis * ($invoice['cgst'] / 100);                      					

							$tax_price=$tax_sgst+$tax_cgst;

						}else{
							$tax_igst = $total_dis * ($invoice['igst'] / 100);  

								$tax_price=$tax_igst;

						}
			}else{
				$tax_price=$tax_igst;
			}
		}
		$final_amount=$this->numberFormate(((($total_dis+$final_tax+$tax_price))),3);
		return $final_amount;
		//printr($sales_id .'===='. $final_amount);
	}
	public function viewInvoiceForIBSpanish($status,$invoice_no)
	{	
		
		$invoice=$this->getInvoiceNetData($invoice_no);
		//printr($invoice);die;
		//echo "kkkk";
		$currency=$this->getCurrencyName($invoice['curr_id']);	
		$alldetails=$this->getInvoiceProduct($invoice_no);
		//printr($invoice_no);die;
		
		//if($invoice['user_type_id'] != )
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
				//echo "1 echio  ";
			}else{
				$userEmployee = $this->getUserEmployeeIds($invoice['user_type_id'],$invoice['user_id']);
				$set_user_id = $invoice['user_id'];
				//echo $set_user_id."2";
				$set_user_type_id = $invoice['user_type_id'];
			}
			$user_info=$this->getUser($set_user_id,'4');
			//printr($user_info);
			//echo $set_user_id;
			$data=$this->query("SELECT logo,abn_no,termsandconditions_invoice FROM international_branch WHERE international_branch_id = '".$set_user_id."'");
			//echo "SELECT logo FROM international_branch WHERE international_branch_id = '".$set_user_id."'";
			//printr($data);
			if(isset($data->row['logo']) &&  $data->row['logo']!= '')
			{
				$image= HTTP_UPLOAD."admin/logo/200_".$data->row['logo'];
				if($invoice['country_destination']=='155')
				    $img = '<img src="'.$image.'" alt="Image" width="267px;">';
				else
				    $img = '<img src="'.$image.'" alt="Image">';
			}
			else
			{
				$img ='';
			}			
		}
		$au_user=$this->getUser($invoice['user_id'],$invoice['user_type_id']);
		//printr($au_user);
		$atten_user = $au_user['first_name'].' '.$au_user['last_name'];
		$order_number = $invoice['exporter_orderno'];
		
		//$advance_status = 'Pago Recibido';
		$tax_per = round($invoice['tax_maxico']).' % ';
		
		$html=''; 
		$qst_no=$invoice['qst_no']; 
		$html.='<style>table {border-collapse: collapse;}td{padding: 6px;}.line-dashed {
				  border-style: dashed;
				  background: transparent;
				}
				.line {
				  height: 2px;
				  margin: 10px 0;
				  font-size: 0;
				  overflow: hidden;
				  background-color: #fff;
				  border-width: 0;
				  border-top: 1px solid #e0e4e8;
				}</style>
				<div id="print_div" style="padding-top: 0px; border: 1px solid black;">';
				if($invoice['country_destination']=='155')
				    $html.=$img;
				else
				    $html.='<center>'.$img.'</center>';
				    
				    $html.='<br><br>
					
					<div class="">
					 <div id="thetable" style="width: 100%;">';//width:754px
      	$fixdata = $this->getFixmaster(); 
		// str_ireplace(':','Smit','Pouch Direct Pvt Ltd attention : Like canada ');
       $html.='<table width="100%">
	<tr>
    	<td valign="top" ><h2>FACTURA</h2></td>
        <td valign="top"><table><tr><td><strong>Fecha</strong></td></tr><tr><td>'.date("d M Y",strtotime($invoice['invoice_date'])).'</td></tr></table></td>';
		  $html.=' <td valign="top" rowspan="2">';
	   	    if($invoice['country_destination']=='155')
	   	        $html .='<b>Emisor</b><br>';
	   	        
	   	    $html .=nl2br($invoice['company_address']).'<br></br>Atención : '.$atten_user.'</td>';
	   	 
   $html.=' </tr>
    <tr>
    	<td valign="top">';
    	if($invoice['country_destination']=='155')
    	    $html .='<b>Receptor</b><br>';
    	    
    	$html .=stripslashes($invoice['customer_name']).'<br>'.nl2br($invoice['consignee']).'</td>
        <td valign="top">
			<table>
					<tr><td><strong>Factura N°</strong></td></tr><tr><td>'.$invoice['invoice_no'].'</td></tr>
					<tr><td><strong>Pedido N°</strong></td></tr><tr><td>'.$order_number.'</td></tr>';
			$html.=' </table>
		</td> 
     
    </tr>
</table>
<table  width="100%" style="margin-top: 50px; ">
		<tr style="border-bottom: 3px solid black; height:40px">
    	<th width="200px" align="left" style="border-bottom: 3px solid black;" >Descripción</th>
        <th align="left" style="border-bottom: 3px solid black;" >Cantidad </th>
        <th align="left" style="border-bottom: 3px solid black;">Precio Unitario</th>
        <th align="left" style="border-bottom: 3px solid black;">IVA (16%)</th>
        <th style="border-bottom: 3px solid black;"><div align="center">Cantidad '. $currency['currency_code'].'</div></th>
    </tr>';
	$subtotal=0;$tot_tax=$freight_tot=$case_breaking_fees_tot=0;$label_charges_tot=0;$prepress_charges_tot=0;$product_code_freight=$p_code_id=$frieght_qty=$freight_rate=$dis='';
	if(isset($alldetails) && $alldetails!='')
	{
		foreach($alldetails as $details)
		{
			$zipper_name=$spout_name=$acc_name=$valve_name='';
			$get_size = array('size_master_id'=> '',

								'product_id'=>'',

								'product_zipper_id'=>'',

								'volume'=>'',

								'width'=>'',

								'height'=>'',

								'gusset'=>'',

								'weight'=>'');
			
			$pro_code = '';
			if($details['product_code_id'] == '-1')
			{
				$product_code = $details['product_description'];
				
			}
			elseif($details['product_code_id'] == '0')
			{
				if($details['rate']!='0.0000')
				{
					$product_code_freight = 'Freight Charges : '.$details['rate'];
					$p_code_id=$details['product_code_id'];
					$frieght_qty = $details['qty'];
					$freight_rate = $details['rate'];
					$freight_tot = $frieght_qty * $freight_rate;
				}
				if($details['case_breaking_fees']!='0.0000')
				{
					$product_code_case_fees = 'Case Breaking Charges :  '.$details['case_breaking_fees'];
					$p_code_id=$details['product_code_id'];
					$case_breaking_qty = $details['qty'];
					$case_breaking_fees = $details['case_breaking_fees'];
					$case_breaking_fees_tot = $case_breaking_qty * $case_breaking_fees;
				}
				if($details['label_charges']!='0.0000')
				{
					$product_code_label_charges = 'Label Charges :  '.$details['label_charges'];
					$p_code_id=$details['product_code_id'];
					$label_charges_qty = $details['qty'];
					$label_charges = $details['label_charges'];
					$label_charges_tot = $label_charges_qty * $label_charges;
				}
				if($details['prepress_charges']!='0.0000')


				{
					$product_code_prepress_charges = 'Prepress Charges :  '.$details['prepress_charges'];
					$p_code_id=$details['product_code_id'];
					$prepress_charges_qty  = $details['qty'];
					$prepress_charges = $details['prepress_charges'];
					$prepress_charges_tot = $prepress_charges_qty  * $prepress_charges;
				}
			}
			else
			{
				$product_code_details=$this->getProductCode($details['product_code_id']);
				$product_code =$product_code_details['description'];
				$pro_code = $product_code_details['product_code'];
				$pro_code = '<b>Product Code : </b>'.$product_code_details['product_code'];
				if($invoice['country_destination']=='155')
				{
					
					if($product_code_details['valve']=='No Valve')
						$valve_name='Sin Válvula';
					else	
						$valve_name='Con válvula';
					
					if($product_code_details['zipper_name']!='No zip')

						$zipper_name=$product_code_details['zipper_name_spanish'];

					if($product_code_details['spout_name']!='No Spout')

						$spout_name=$product_code_details['spout_name_spanish'];
					
					if($product_code_details['product_accessorie_name']!='No Accessorie')	
						
						$acc_name=$product_code_details['product_accessorie_name_spanish'];
					
					$get_size = $this->getSizeDetail($product_code_details['product'],$product_code_details['zipper'],$product_code_details['volume'],$product_code_details['measurement']);
					
					if($product_code_details['product'] == 3)
						$gusset = floatval($get_size['gusset']).'+'.floatval($get_size['gusset']);
					else
						$gusset = floatval($get_size['gusset']);
					
					$size_product = '</b>'.floatval($get_size['width']).' mm &nbsp;Ancho &nbsp;X&nbsp;'.floatval($get_size['height']).' mm &nbsp;Ancho &nbsp;';
					
				}
				
				
			}
		//printr($product_code_details);
				$tot=$details['qty']*$details['rate'];
				$tax_gst=$invoice['tax_maxico'];
				
				
				$tax=$fri='0';
				if($details['product_code_id'] == '0')
				{
					$tax=((($tot*$tax_gst)/100));
					$fri=$tot;
				}
				//[kinjal] modify 10-2-2016
				$tot_tax=($tot_tax+(($tot*$tax_gst)/100));
				$subtotal=$subtotal+$tot-$fri;
				if($details['tool_price']!='0')
				    $subtotal=$subtotal+$details['tool_price'];
				//[kinjal] modify 10-2-2016
				if($p_code_id!='0')
				$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
						<td style="border-bottom: 1px solid black;">'.$pro_code;//<br>'.$product_code.'
						if($invoice['country_destination']=='155')
						{
							if($product_code_details['product'] == 6)
								$html .='<b>Tamaño : </b>'.floatval($product_code_details['width']).'mm &nbsp; Roll Width';
							else
								$html .='<b>Tamaño : </b>'.$size_product;
							if($gusset>0)
								$html .='X&nbsp;'.$gusset.' mm Ancho';
							
							$html .='<br><b>Tipo de Bolsa :</b>'.$product_code_details['product_name'].'<b>&nbsp;'.$zipper_name.'&nbsp;'.$valve_name.' &nbsp;'.$spout_name.' &nbsp;'.$acc_name.'</b><br>';
							$html .='<b>Color : </b>'.$product_code_details['color'];
							$html .='<br><b>Material : </b>'.$details['product_description'];
						}
						
				$html .='</td>
						<td style="border-bottom: 1px solid black;">'.$details['qty'].'</td>
						<td align="center"  style="border-bottom: 1px solid black;">'.$this->numberFormate($details['rate'],3).'</td>
						<td style="border-bottom: 1px solid black;">'.$tax_per.'</td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($tot,2).'</td>
					</tr>';
					if($details['tool_price']!='0')
    				{
    				    $html.='<tr style="border-bottom: 3px solid black; height:40px;border-top: 1px solid rgba(0, 0, 0, 0.09);">
    				                <td><b>Costo de Herramienta Adicional : </b></td>
    				                <td></td>
    				                <td></td>
    				                <td align="center">'.$details['tool_price'].'</td>
    				            </tr>';
    				}
			
		}
	}
		
	//mansi(1-2-2016) add discount field
	//printr($invoice['discount']);
	
	$total_dis=$subtotal-(($subtotal*$invoice['discount'])/100);
	$tax_gst=$invoice['tax_maxico'];
	$final_tax= ($total_dis*$tax_gst)/100;
	//$tax_gst=10;
	if($invoice['country_destination']=='155')
	{
		$amout_paid=$invoice['amt_maxico'];
		
	}
	else
	{
		$amout_paid=$invoice['amount_paid'];
	}
	
    $html.='
     <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td>Subtotal</td>
        <td align="center">'.$this->numberFormate($subtotal,2).'</td>
    </tr>
	 <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">Descuento </td>
        <td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$invoice['discount'].' %</td>
    </tr>
	 <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td></td>
        <td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$total_dis.'</td>
    </tr>';
	if($p_code_id == '0')
	{
		if($details['rate']!='0.0000')
		{
			$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
						<td  width="200px"  style="border-bottom: 1px solid black;">'.$product_code_freight.'</td>
						<td  style="border-bottom: 1px solid black;">'.$frieght_qty.'</td>
						<td align="center"  style="border-bottom: 1px solid black;">'.$this->numberFormate($freight_rate,2).'</td>
						<td  style="border-bottom: 1px solid black;"></td>
						<td align="center"  style="border-bottom: 1px solid black;">'.$this->numberFormate($freight_tot,2).'</td>
					</tr>
					 <tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot,2).'</td>
					</tr>';
		}
		if($details['case_breaking_fees']!='0.0000')
		{
					
					$html.=' <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
						<td style="border-bottom: 1px solid black;">'.$product_code_case_fees.'</td>
						<td style="border-bottom: 1px solid black;">'.$case_breaking_qty .'</td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($case_breaking_fees ,2).'</td>
						<td style="border-bottom: 1px solid black;"></td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($case_breaking_fees_tot ,2).'</td>
					</tr>
					 <tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot+$case_breaking_fees_tot,2).'</td>
					</tr>';
		}
		if($details['label_charges']!='0.0000')
		{
					
					$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
						<td style="border-bottom: 1px solid black;">'.$product_code_label_charges.'</td>
						<td style="border-bottom: 1px solid black;">'.$label_charges_qty .'</td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($label_charges ,2).'</td>
						<td style="border-bottom: 1px solid black;"></td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($label_charges_tot ,2).'</td>
					</tr>
					 <tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot+$case_breaking_fees_tot+$label_charges_tot,2).'</td>
					</tr>';
		}
		if($details['prepress_charges']!='0.0000')
		{
					
					$html.='  <tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
						<td style="border-bottom: 1px solid black;">'.$product_code_prepress_charges.'</td>
						<td style="border-bottom: 1px solid black;">'.$prepress_charges_qty .'</td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($prepress_charges ,2).'</td>
						<td  style="border-bottom: 1px solid black;"></td>
						<td align="center" style="border-bottom: 1px solid black;">'.$this->numberFormate($prepress_charges_tot ,2).'</td>
					</tr>
					 <tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($total_dis+$freight_tot+$case_breaking_fees_tot+$label_charges_tot+$prepress_charges_tot,2).'</td>
					</tr>';
		}
					$total_dis = $total_dis+$freight_tot+$case_breaking_fees_tot+$label_charges_tot+$prepress_charges_tot;
					//printr($total_dis.'=== +'.$freight_tot.'=== +'.$case_breaking_fees_tot.'=== +'.$label_charges_tot.'=== +'.$prepress_charges_tot);
					$final_tax= ($total_dis*$tax_gst)/100;
					
					
					
	}
    $tax_price =$tax_sgst=$tax_igst=$tax_cgst= 0;
		//add  	Indonesia ->$invoice['country_destination'] != '112' sonu told by vijay sir  5-5-2017  
	 $html.=' <tr>
			<td></td>
			<td></td>
			<td></td>
			<td style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);"> IVA (16%) '.round($tax_gst).'%</td>
			<td align="center" style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">'.$this->numberFormate($final_tax,2).'</td>
		</tr>';
	
    $html.='  <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td>Total '. $currency['currency_code'].'</td>
        <td align="center">'.$this->numberFormate(($total_dis+$final_tax+$tax_price),2).'</td>
    </tr>';
	
	 //modify [kinjal] 10-2-2016
	
    $html.='
   <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td style="border-bottom: 3px solid black;">Pago Recibido '. $currency['currency_code'].'</td>
        <td align="center" style="border-bottom: 3px solid black;">'.$this->numberFormate($amout_paid,2).'</td>
    </tr>
    <tr>
    	<td></td>
        <td></td>
        <td></td>
        <th align="left">Pendiente '. $currency['currency_code'].'</th>
        <th><div align="center">'.$this->numberFormate(((($total_dis+$final_tax+$tax_price)-$amout_paid)),2).'</div></th>
    </tr>
	
	</table>
	<table width="100%"  style="margin-top: 50px; ">
	<tr >';
	 $due_amount=(($total_dis+$final_tax)-$amout_paid);
	 
	 if($due_amount!=0)
	 {
		$html.='<th  valign="top" align="left" width="50%">Fecha de Pago: '.date("d M Y",strtotime($invoice['invoice_date'])).'</th>';
	 }
		 $html.='<td width="50%"><table><tr><td align="left"><strong>DatosBancarios:</strong></td></tr><tr><td>'.nl2br($invoice['bank_address']).'</td></tr></table></td>
		</tr>
	</table>

	<div class="line line-dashed "></div>

	';

			 $html.='</div>
		</div>
	  </div>';
		return $html;
	}
	
	public function getSizeDetail($product_id,$zipper_id,$volume,$mea)

	{

		$size_volume = $volume.' '.$mea;

		$sql = "SELECT * FROM `" . DB_PREFIX . "size_master` WHERE product_id = '".$product_id."' AND product_zipper_id=".$zipper_id." AND volume='".$size_volume."'";
       // echo $sql;
		$data = $this->query($sql);

		if($data->num_rows){

			return $data->row;

		}else{

			return false;

		}

	}
	//[kinjal] : made on 19-6-2018 for harshbhai(ca)
    public function getCSVData($handle,$charge)
	{
		
		$data=array();
		$first_time = $time = $t = true;
		
        //loop through the csv file 
		$array = '';
		while($data = fgetcsv($handle,1000,","))
		{
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
			$InvoiceNumber=$data[10];
				
			$product_code_id=$this->getProductCodeId($data[19]);
			$des = '';
			$cond = strtolower($data[19]);
			if($cond=='freight' || $cond=='shipping' || $cond=='case-breaking' || $cond=='handling charges' || $cond=='re-stocking charges' || $cond=='lable charges' || $cond=='prepress charges' || $cond=='tin tie application')
			{ 
				
				$product_code_id['product_code_id']='0';
				$des = $data[20];
			}
			if (strpos($data[20], 'Sample') !== false) 
			{
			    $des = $data[20];
			}
			if(empty($product_code_id))
			{
			    $array[$InvoiceNumber][] = '<b>'.$data[19].'</b>   '.$data[20];
			}
					
		}
		
		return $array;
	}
	//on 1-11-2018
	public function getBankData($proforma_no){

			$sql = "SELECT b.* FROM " . DB_PREFIX . "proforma_product_code_wise as p,bank_detail as b WHERE b.bank_detail_id=p.bank_id AND p.pro_in_no = '" .$proforma_no. "'";

			//echo $sql;

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}else{

				return false;

			}

		}
		
		public function Payment_detail_for_Customer($proforma_id){
		 $sql = "SELECT * FROM `proforma_payment_detail` as pd ,proforma_product_code_wise as p  WHERE  p.pro_in_no= '" . $proforma_id . "' AND pd.proforma_id = p.proforma_id  AND p.is_delete = '0' AND pd.is_delete='0'";
		 $data = $this->query($sql);
		 	if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}
		}
	public function getPIid($proforma_no){
		$pro_d = $this->query("SELECT proforma_id FROM proforma_product_code_wise WHERE pro_in_no='".$proforma_no."'");
		$proforma_id = $pro_d->row['proforma_id'];
		return $proforma_id;
    }
	function send_mail_customer($data)
	{
    	$sales=$this->getInvoiceData($data['sales_invoice_send']);
		$html='';
		$html.= $data['message'];
		
		$email_temp[]=array('html'=>$html,'email'=>$data['emailform']);
		//$email_temp[]=array('html'=>$html,'email'=>$data['admin']);
		$email_temp[]=array('html'=>$html,'email'=>$data['toemail']);
		
		if($data['bccemail']=='')
		    $data['bccemail'] = $data['admin'];
		else
		    $data['bccemail'] .=','.$data['admin'];
		
		$email_id=$this->getUser($sales['user_id'],$sales['user_type_id']);
		$signature = '<br> Thank you <br> kind Regards, <br>'.$email_id['first_name'].' '.$email_id['last_name'];
		$subject =$data['subject']; 
		
		
		$form_email=$email_id['email'];
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(7); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
		$path = HTTP_SERVER."template/proforma_invoice.html";
		$output = file_get_contents($path);  
		$search  = array('{tag:header}','{tag:details}');
		$to_email_ids=implode(",", array_column($email_temp,"email"));
		/*foreach($email_temp as $val)
		{*/
			$toEmail =$form_email;
			$firstTimeemial = 1;								
			$message = '';
			if($html)
			{
				$tag_val = array(
				    "{{header}}"=>$subject,
					"{{PouchMakersDetail}}" =>$html,
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
		   // printr($to_email_ids);printr($form_email);printr($subject);printr($message);printr($data['url']);printr($data['bccemail']);printr($data['ccemail']);
				send_email_test($to_email_ids,$form_email,$subject,$message,'',$data['url'],'',$data['bccemail'],$data['ccemail']); 
			
		//}
	//	die;
    }
    public function genSalesInvoice($invoice_id)
	{
		$sql = "UPDATE " . DB_PREFIX . "sales_invoice SET gen_status = '0',rack_notify_status='1' WHERE invoice_id =".$invoice_id."";
		$this->query($sql);
    }
    public function save_courier_details($data){
		$courier_type1= ''	;
		if(isset($data['courier_type1']))
			$courier_type1= implode(',',$data['courier_type1']);
		$this->query("INSERT INTO sales_dispatched_courier_details SET sales_invoice_id = '".$data['sales_invoice_id']."', consignment_option = '".$data['consignment_option']."',courier_option = '".$data['courier_option']."',courier_date = '".$data['courier_date']."',courier_type = '".implode(',',$data['courier_type'])."',other_box_details = '".$data['other_box_details']."',courier_freight = '".$data['courier_freight']."',consignment_no = '".addslashes($data['consignment_no'])."',returned_goods='".$data['returned_goods']."', courier_option_sec = '".$data['courier_option1']."',courier_date_sec = '".$data['courier_date']."',courier_type_sec = '".$courier_type1."',other_box_details_sec = '".$data['other_box_details1']."',courier_freight_sec = '".$data['courier_freight1']."',consignment_no_sec = '".addslashes($data['consignment_no1'])."',buyer_order_no = '".$data['buyer_order_no']."',status='1',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' ,user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW(), date_modify = NOW()");
    }
	//end [kinjal]
}
?>