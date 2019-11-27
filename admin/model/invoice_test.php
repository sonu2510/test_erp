<?php
class invoice extends dbclass{	
	
	/*public function addInvoice($data=array())
	{
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		//printr($data);die;
		if(isset($data['invoice_id']) && !empty($data['invoice_id'])) {
		    $invoice_id = $data['invoice_id'];
		} else
		{
			$taxation='';
			$taxation_data='';
			$final_tax_name='';
			$tax_name='';
			if(isset($data['country_id']) && !empty($data['country_id']) && $data['country_id'] ==111)
			{
				if($data['tax_mode']=='form')
				{
					$val=$data['form'];
					foreach($val as $taxval)
					{
						$final_tax_name.= $taxval.',';
						$tax_name.=' tax_name="'.$taxval.'" OR ';										
					}
					$final_tax_name=substr($final_tax_name,0,-1);
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
					$tax_name.=' tax_name="'.$data['tax_mode'].'"';
					$final_tax_name=$data['tax_mode'];
				}				
				if($tax_name!='')
				{
					$taxation= $data['taxation'];
					$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
					$data_tax = $this->query($sql);
					$taxation_data=$data_tax->row;
				}
			}
			if(isset($taxation_data) && !empty($taxation_data)) {
				$excies =$taxation_data['excies'];
				$taxation=$taxation;			
				$tax=$taxation_data[$taxation];
			}
			else{
				$taxation ='';
				$excies = 0;
				$tax = 0;
			}
		if(isset($data['form']))
		    $form=implode(",",$data['form']);
		else
		    $form='';				
		if(!isset($data['ref_no']))
			$data['ref_no']='';
		if(!isset($data['buyersno']))
			$data['buyersno']='';
		if(!isset($data['other_ref']))
			$data['other_ref']='';
		if(!isset($data['pre_carrier']))
			$data['pre_carrier']='';
		if(!isset($data['other_buyer']))
			$data['other_buyer']='';
		if(!isset($data['portofload']))
			$data['portofload']='';
		if(!isset($data['port_of_dis']))
			$data['port_of_dis']='';
		if(!isset($data['delivery']))
			$data['delivery']='';
		if(!isset($data['hscode']))
			$data['hscode']='';
		if(!isset($data['printedpouches']))
			$data['printedpouches']='';
		if(!isset($data['pouch_desc']))
			$data['pouch_desc']='';
		if(!isset($data['tran_desc']))
			$data['tran_desc']='';
		if(!isset($data['tran_charges']))
			$data['tran_charges']='';
		
		if(!isset($data['transport']))
			$data['transport']='';
		if(!isset($data['container_no']))
			$data['container_no']='';
		if(!isset($data['seal_no']))
			$data['seal_no']='';
		if(!isset($data['cylinder_charges']))
			$data['cylinder_charges']='';
		if(!isset($data['postal_code']))
			$data['postal_code']='';
		if(!isset($data['account_code']))
			$data['account_code']='';
		if(!isset($data['sent']))
			$data['sent']='';
		if(!isset($data['invoice_status']))
		$data['invoice_status']='';
		$sql = "INSERT INTO `" . DB_PREFIX . "invoice_test` SET invoice_no = '".$data['invoiceno']."',invoice_date = '" .$data['invoicedate']. "',
		exporter_orderno = '".$data['ref_no']."',gst='".$data['gst']."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."', buyers_orderno ='".$data['buyersno']."',other_ref ='".$data['other_ref']."',pre_carrier='".$data['pre_carrier']."',consignee='".addslashes($data['consignee'])."',buyer='".$data['other_buyer']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',port_load='".$data['portofload']."',port_discharge='".$data['port_of_dis']."',final_destination='".$data['country_id']."', taxation='".$taxation."', excies='".$excies."', tax='".$tax."', tax_mode ='".$data['tax_mode']."',tax_form ='".$form."' ,payment_terms='".$data['payment_terms']."',delivery='".$data['delivery']."',HS_CODE='".$data['hscode']."',pouch_type='".$data['printedpouches']."',pouch_desc='".$data['pouch_desc']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',cylinder_charges='".$data['cylinder_charges']."',container_no='".addslashes($data['container_no'])."',seal_no='".addslashes($data['seal_no'])."', postal_code = '".$data['postal_code']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',transportation='".encode($data['transport'])."',curr_id='".$data['currency']."',
		status = '".$data['status']."',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0";
		$datasql=$this->query($sql);
		$invoice_id = $this->getLastId();
		}
		$sql2 = "Insert into invoice_product_test Set invoice_id='".$invoice_id."',product_id='".$data['product']."', valve = '".$data['valve']."', zipper = '".$data['zipper']."', spout = '".$data['spout']."',make_pouch='".$data['make']."', accessorie = '".$data['accessorie']."',net_weight='".$data['netweight']."',measurement_two='".$data['measurement']."',buyers_o_no='".$data['buyers_o_no']."',item_no='".$data['item_no']."', date_added = NOW(), date_modify = NOW(), is_delete = 0"; 
		$data2=$this->query($sql2);
		$invoice_product_id = $this->getLastId();

		if(isset($data['color']) && !empty($data['color'])) 
		{
			foreach($data['color'] as $color) 
			{
				if(isset($color['dimension']))
					$dim=$color['dimension'];
				else
					$dim='';
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
				$sql3 = "Insert into invoice_color_test Set invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$color['color']."',color_text='".$clr_txt."', rate ='".$color['rate']."', qty = '".$color['qty']."', size = '".$color['size']."',  measurement = '".$color['measurement']."',dimension='".$dim."',net_weight='".$color['net_weight']."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 			
				$data3=$this->query($sql3);
			}
		}
		$final_total=$this->addinvoicetotalamount($invoice_id);
		return $invoice_id;
	}
	*/


	public function addInvoice($data=array())
	{

		//printr($data);
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		if(isset($data['invoice_id']) && !empty($data['invoice_id'])) {
		$invoice_id = $data['invoice_id'];
		} else
		{
			$taxation='';
			$taxation_data='';
			$final_tax_name='';
			$tax_name='';
			if(isset($data['country_id']) && !empty($data['country_id']) && $data['country_id'] ==111)
			{
				if($data['tax_mode']=='form')
				{
					$val=$data['form'];
					foreach($val as $taxval)
					{
						$final_tax_name.= $taxval.',';
						$tax_name.=' tax_name="'.$taxval.'" OR ';										
					}
					$final_tax_name=substr($final_tax_name,0,-1);
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
					$tax_name.=' tax_name="'.$data['tax_mode'].'"';
					$final_tax_name=$data['tax_mode'];
				}				
				if($tax_name!='')
				{
					$taxation= $data['taxation'];
					$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
					$data_tax = $this->query($sql);
					$taxation_data=$data_tax->row;
				}
			}
			if(isset($taxation_data) && !empty($taxation_data)) {
				$excies =$taxation_data['excies'];
				$taxation=$taxation;			
				$tax=$taxation_data[$taxation];
			}
			else{
				$taxation ='';
				$excies = 0;
				$tax = 0;
			}
		if(isset($data['form']))
		    $form=implode(",",$data['form']);
		else
		    $form='';				
		if(!isset($data['ref_no']))
			$data['ref_no']='';
		if(!isset($data['pro_ref_no']))
			$data['pro_ref_no']='';
		if(!isset($data['buyersno']))
			$data['buyersno']='';
		if(!isset($data['other_ref']))
			$data['other_ref']='';
		if(!isset($data['pre_carrier']))
			$data['pre_carrier']='';
		if(!isset($data['other_buyer']))
			$data['other_buyer']='';
		if(!isset($data['portofload']))
			$data['portofload']='';
		if(!isset($data['port_of_dis']))
			$data['port_of_dis']='';
		if(!isset($data['delivery']))
			$data['delivery']='';
		if(!isset($data['hscode']))
			$data['hscode']='';
		if(!isset($data['printedpouches']))
			$data['printedpouches']='';
		if(!isset($data['pouch_desc']))
			$data['pouch_desc']='';
		if(!isset($data['tran_desc']))
			$data['tran_desc']='';
		if(!isset($data['tran_charges']))
			$data['tran_charges']='';		
		if(!isset($data['transport']))
			$data['transport']='';
		if(!isset($data['container_no']))
			$data['container_no']='';
		if(!isset($data['seal_no']))
			$data['seal_no']='';
		if(!isset($data['cylinder_charges']))
			$data['cylinder_charges']='';
		if(!isset($data['postal_code']))
			$data['postal_code']='';
		if(!isset($data['account_code']))
			$data['account_code']='';
		if(!isset($data['sent']))
			$data['sent']='';
		if(!isset($data['invoice_status']))
		$data['invoice_status']='';
		$sql = "INSERT INTO `" . DB_PREFIX . "invoice_test` SET invoice_no = '".$data['invoiceno']."',invoice_date = '" .$data['invoicedate']. "',
		exporter_orderno = '".$data['ref_no']."',gst='".$data['gst']."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."', buyers_orderno ='".$data['buyersno']."',other_ref ='".$data['other_ref']."',pre_carrier='".$data['pre_carrier']."',consignee='".addslashes($data['consignee'])."',buyer='".$data['other_buyer']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',port_load='".$data['portofload']."',port_discharge='".$data['port_of_dis']."',final_destination='".$data['country_id']."', taxation='".$taxation."', excies='".$excies."', tax='".$tax."', tax_mode ='".$data['tax_mode']."',tax_form ='".$form."' ,payment_terms='".$data['payment_terms']."',delivery='".$data['delivery']."',HS_CODE='".$data['hscode']."',pouch_type='".$data['printedpouches']."',pouch_desc='".$data['pouch_desc']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',cylinder_charges='".$data['cylinder_charges']."',container_no='".addslashes($data['container_no'])."',seal_no='".addslashes($data['seal_no'])."', postal_code = '".$data['postal_code']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',transportation='".encode($data['transport'])."',curr_id='".$data['currency']."',
		status = '".$data['status']."',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0";
		$datasql=$this->query($sql);
		$invoice_id = $this->getLastId();
		
		    
		}
		$measure = $this->getMeasurementName($data['measurement']); 
        $data['size']=$data['size'].' '.$measure['measurement'];  
        
		$sql2 = "Insert into invoice_product_test Set invoice_id='".$invoice_id."',product_id='".$data['product_id']."',product_code_id='".$data['product_code_id']."',valve = '".$data['valve']."',zipper = '".encode($data['zipper'])."',spout = '".encode($data['spout'])."',make_pouch='".$data['make']."', accessorie = '".encode($data['accessorie'])."',net_weight='".$data['net_weight']."',measurement_two='".$data['measurement']."',buyers_o_no='".$data['buyers_o_no']."',ref_no='".$data['pro_ref_no']."',item_no='".$data['item_no']."', date_added = NOW(), date_modify = NOW(), is_delete = 0"; 
		$data2=$this->query($sql2);
		$invoice_product_id = $this->getLastId();
				$sql3 = "Insert into invoice_color_test Set invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$data['color_product']."',color_text='".addslashes($data['job_name'])."', rate ='".$data['rate']."',dis_rate ='".$data['rate']."', qty = '".$data['qty']."', rack_status = '".$data['qty']."', size = '".$data['size']."',  measurement = '".$data['measurement']."',dimension='".$data['dimension']."',net_weight='".$data['net_weight']."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 			
				$data3=$this->query($sql3);
		
		$final_total=$this->addinvoicetotalamount($invoice_id);
		return $invoice_id; 
	}
	public function getLable($invoice_id,$table)
	{
		$data = $this->query("SELECT * FROM " .$table." WHERE invoice_id=".$invoice_id." AND status = '0' AND is_delete = '0' ORDER BY box_no ASC");
		if($data->num_rows){
			return $data->rows; 
		}else{
			return false;
		}
	}  
	 
	public function getInvoiceProductWithBox($invoice_id)
	{
		//printr($invoice_id);
		//$sql ="SELECT * FROM invoice AS i LEFT JOIN invoice_product AS ip ON (i.invoice_id=ip.invoice_id) LEFT JOIN invoice_color AS ic ON (i.invoice_id=ic.invoice_id AND ip.invoice_product_id=ic.invoice_product_id) LEFT JOIN box_master AS bm ON (bm.pouch_volume = ic.size AND bm.pouch_volume_type=ic.measurement AND bm.product_id = ip.product_id AND bm.zipper=ip.zipper AND bm.valve=ip.valve AND bm.spout=ip.spout AND bm.accessorie=ip.accessorie AND bm.make_pouch=ip.make_pouch AND bm.transportation=i.transportation AND bm.is_delete=0) WHERE i.invoice_id=".$invoice_id." ORDER BY ip.product_id ASC,bm.pouch_volume DESC,ic.qty DESC";
		
	//	$sql ="SELECT * FROM invoice_test AS i LEFT JOIN invoice_product_test AS ip ON (i.invoice_id=ip.invoice_id) LEFT JOIN invoice_color_test AS ic ON (i.invoice_id=ic.invoice_id AND ip.invoice_product_id=ic.invoice_product_id) LEFT JOIN box_master AS bm ON (bm.pouch_volume = concat('',ic.size * 1) AND bm.pouch_volume_type=ic.measurement AND bm.product_id = ip.product_id AND bm.zipper=ip.zipper AND bm.valve=ip.valve AND bm.spout=ip.spout AND bm.accessorie=ip.accessorie AND bm.make_pouch=ip.make_pouch AND bm.transportation=i.transportation AND bm.is_delete=0) WHERE i.invoice_id=".$invoice_id." ORDER BY ip.invoice_product_id ASC";
		
	//	$sql ="SELECT * FROM invoice_test AS i LEFT JOIN invoice_product_test AS ip ON (i.invoice_id=ip.invoice_id) LEFT JOIN invoice_color_test AS ic ON (i.invoice_id=ic.invoice_id AND ip.invoice_product_id=ic.invoice_product_id) LEFT JOIN box_master AS bm ON (bm.pouch_volume = CONCAT(ic.size,' ',ic.measurement) AND bm.product_id = ip.product_id AND bm.zipper=ip.zipper AND bm.valve=ip.valve AND bm.spout=ip.spout AND bm.accessorie=ip.accessorie AND bm.make_pouch=ip.make_pouch AND bm.transportation=i.transportation AND bm.is_delete=0) WHERE i.invoice_id=".$invoice_id." ORDER BY ip.invoice_product_id ASC";
	
	//	$sql ="SELECT * FROM invoice_test AS i , invoice_product_test AS ip ,invoice_color_test AS ic,box_master as bm ,template_measurement as tm WHERE i.invoice_id=ip.invoice_id AND i.invoice_id=ic.invoice_id AND ip.invoice_product_id=ic.invoice_product_id AND ic.measurement=tm.product_id AND CONCAT( bm.pouch_volume,' ',tm.measurement)= ic.size AND bm.pouch_volume_type=ic.measurement AND bm.product_id = ip.product_id AND bm.zipper=ip.zipper AND bm.valve=ip.valve AND bm.spout=ip.spout AND bm.accessorie=ip.accessorie AND bm.make_pouch=ip.make_pouch AND bm.transportation=i.transportation AND bm.is_delete=0 AND i.invoice_id=".$invoice_id." ORDER BY ip.invoice_product_id ASC";
	
	//AND bm.make_pouch=ip.make_pouch
		$sql ="SELECT * ,ip.product_id as p_id FROM invoice_test AS i LEFT JOIN invoice_product_test AS ip ON (i.invoice_id=ip.invoice_id) LEFT JOIN invoice_color_test AS ic ON (i.invoice_id=ic.invoice_id AND ip.invoice_product_id=ic.invoice_product_id) LEFT JOIN template_measurement AS tm ON (ic.measurement =tm.product_id) LEFT JOIN box_master AS bm ON ( CONCAT(bm.pouch_volume,' ',tm.measurement )=ic.size  AND bm.pouch_volume_type=ic.measurement AND bm.product_id = ip.product_id AND bm.zipper=ip.zipper AND bm.valve=ip.valve AND bm.spout=ip.spout AND  bm.make_pouch=ip.make_pouch AND bm.accessorie=ip.accessorie  AND bm.transportation=i.transportation AND bm.is_delete=0 AND ic.qty>=bm.quantity ) WHERE i.invoice_id=".$invoice_id." ORDER BY ip.invoice_product_id ASC";//AND ic.qty=bm.quantity
		
		//echo $sql;//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	
	public function in_gen_box_uni_id()
	{
		$data = $this->query("SELECT Max(box_unique_id) as box_uni_no FROM in_gen_invoice_test WHERE is_delete=0");
		$box_uni = $data->row['box_uni_no'];
		return $box_uni;
	}
	
	
	public function out_gen_box_uni_id()
	{
		$data = $this->query("SELECT Max(box_unique_id) as box_uni_no FROM out_lable_invoice");
		$box_uni = $data->row['box_uni_no'];
		return $box_uni;
	}
	
	public function getInvoiceData($invoice_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "invoice_test  WHERE invoice_id = '" .(int)$invoice_id. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getInvoiceNetData($invoice_id)
	{
		$sql = "SELECT SUM(ip.net_weight) as net_weight,SUM(ip.gross_weight) as gross_weight, i.* FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip WHERE i.invoice_id =ip.invoice_id  AND i.invoice_id = '" .(int)$invoice_id. "' AND i.is_delete=0";
		//echo $sql;
	//	printr('<br>')
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getInvoiceTotalData($invoice_id)
	{
		$sql = "SELECT SUM(ic.qty) as total_qty,SUM(ic.rate) as total_rate,ic.rate,SUM(ic.qty*ic.rate) as tot FROM  " . DB_PREFIX . "invoice_test as i,invoice_color_test as ic WHERE i.invoice_id = '" .(int)$invoice_id. "' 
		AND i.is_delete=0 AND i.invoice_id=ic.invoice_id ";
		$data = $this->query($sql);
    //echo $sql;
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getInvoiceProductId($invoice_product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_product_test` WHERE invoice_product_id = '" .(int)$invoice_product_id. "'  AND is_delete=0 ";
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
		$html .=$this->viewInvoice($status,$invoice_no);
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
			$sql = "SELECT u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2){
			/*$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";*/
			$sql = "SELECT e.user_name, e.user_id,co.country_id, e.multi_quotation_price,ib.company_address,ib.company_name,ib.default_curr,co.country_name, e.first_name, e.last_name,ib.international_branch_id, ib.email_confirm,e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.international_branch_id as user_id,ib.user_name,co.country_id,ib.gst,ib.company_address,ib.default_curr,ib.company_name,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
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
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
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
	
	public function getColorDetails($invoice_id,$invoice_product_id) 
	{	
		$sql = "select ic.*,tm.measurement,pc.color from `".DB_PREFIX."invoice_color_test` as ic,template_measurement as tm,pouch_color as pc WHERE invoice_id='".$invoice_id."' AND invoice_product_id = '".$invoice_product_id."' AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id ";
	//	echo $sql;
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->rows;
		}
		else {
			return false;
		}
	}
	public function getColordesc($invoice_id) 
	{	
		//$sql="select ic.*,tm.measurement,pc.color,p.product_name from invoice_color as ic,template_measurement as tm,pouch_color as pc,invoice_product as ip,product as p WHERE ic.invoice_id='".$invoice_id."' AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id AND ic.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id";
		//$sql="select ic.qty,ig.qty as genqty,ic.qty-ig.qty from (select qty ,invoice_color_id,invoice_id from invoice_color) as ic left join (select qty,invoice_color_id from in_gen_invoice GROUP BY invoice_color_id) as ig on (ic.invoice_color_id=ig.invoice_color_id) where ic.invoice_id='".$invoice_id."'";
		
		
		
		
		$sql="select ic.qty,ig.qty as genqty,ic.color,ic.dimension,ic.invoice_color_id,ic.color_text,ic.net_weight,ic.size,ic.measurement,ic.invoice_product_id,ic.product_name,ifnull(ic.qty-ig.qty,ic.qty)  as remaingqty from (select ii.dimension,ii.qty,ii.size,ii.color_text,tm.measurement,ii.invoice_product_id,p.product_name ,ii.invoice_color_id,ii.invoice_id,c.color,ii.net_weight from invoice_color_test as ii,pouch_color as c,template_measurement  as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id) as ic left join (select sum(qty) as qty,invoice_color_id from in_gen_invoice_test  WHERE is_delete=0 GROUP BY invoice_color_id) as ig on (ic.invoice_color_id=ig.invoice_color_id) where ic.invoice_id='".$invoice_id."'";
		$data = $this->query($sql);
		
		//[kinjal] made for custom on 7-4-2017
		$color = $this->query("SELECT * FROM invoice_color_test WHERE invoice_id='".$invoice_id."' AND color='-1'");
		if($color->num_rows)
		{
			$sql1="select ic.qty,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.color_text,ic.net_weight,ic.size,ic.measurement,ic.invoice_product_id,ic.product_name,ifnull(ic.qty-ig.qty,ic.qty)  as remaingqty from (select ii.dimension,ii.qty,ii.size,ii.color_text,tm.measurement,ii.invoice_product_id,p.product_name ,ii.invoice_color_id,ii.invoice_id,ii.color,ii.net_weight from invoice_color_test as ii,template_measurement  as tm,invoice_product_test as ip,product as p where tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ii.color='-1' ) as ic left join (select sum(qty) as qty,invoice_color_id from in_gen_invoice_test  WHERE is_delete=0 GROUP BY invoice_color_id) as ig on (ic.invoice_color_id=ig.invoice_color_id) where ic.invoice_id='".$invoice_id."'";
			$data1 = $this->query($sql1);
			foreach($data1 as $clr)
			{
				array_push($data->rows,$clr);
			}
		}
		
		if($data->num_rows) {
			return $data->rows;
		}
		else {
			return false;
		}
	}
	public function in_gen_box_no($invoice_id)
	{
		$data = $this->query("SELECT box_no FROM in_gen_invoice_test WHERE invoice_id='".$invoice_id."' AND is_delete=0 ORDER BY box_no DESC LIMIT 1");
		//printr("SELECT box_no FROM in_gen_invoice WHERE invoice_id='".$invoice_id."' ORDER BY box_no DESC LIMIT 1");
		
		if($data->num_rows)
			$box_no = $data->row['box_no'];
		else
			$box_no='';

		return $box_no;
	}
	public function savelabeldetail($postdata)
	{
		/*$data=$this->getInvoiceColorlable($postdata['detail']);
		printr($data);
		die;
	*/
		//printr($postdata);//die;
		if(isset($postdata['per_net_weight']))
		    $postdata['net_weight'] = $postdata['per_net_weight'];
	     
		$total_box=$postdata['total_box'];
		$boxno=$this->in_gen_box_no($postdata['invoice_id']);
		if(!empty($boxno))
			$box_no=$boxno+1;
		else
			$box_no=1;
			
		if($postdata['in_gen_id']!=0)
			$box_no='';
		
		for($i=1;$i<=$total_box;$i++)
		{
			$box_unique_id=0;$box_unique_number='';
			if($postdata['in_gen_id']==0)
			{ 
				$box_uid=$this->in_gen_box_uni_id();
				$box_unique_id=$box_uid+1;
				$box_unique_number='BX'.sprintf("%014s",$box_unique_id);
			}
			
			//printr($box_no);
			$sql= "INSERT INTO in_gen_invoice_test SET invoice_id='".$postdata['invoice_id']."',invoice_color_id='".$postdata['detail']."',qty='".$postdata['per_qty']."', box_weight ='".$postdata['per_box_weight']."', net_weight ='".$postdata['net_weight']."',box_unique_id='".$box_unique_id."',parent_id='".$postdata['in_gen_id']."',box_unique_number='".$box_unique_number."',date_added = NOW(),date_modify = NOW(),box_no='".$box_no."',invoice_product_id='".$postdata['in_product_id']."',is_delete = 0"; 	
		//	printr($sql.'<br>');
	//		echo 'hi in for loop';
			$data=$this->query($sql);
			$this->query("INSERT INTO in_gen_invoice_test_new SET invoice_id='".$postdata['invoice_id']."',invoice_color_id='".$postdata['detail']."',qty='".$postdata['per_qty']."', box_weight ='".$postdata['per_box_weight']."', net_weight ='".$postdata['net_weight']."',box_unique_id='".$box_unique_id."',parent_id='".$postdata['in_gen_id']."',box_unique_number='".$box_unique_number."',date_added = NOW(),date_modify = NOW(),box_no='".$box_no."',invoice_product_id='".$postdata['in_product_id']."',is_delete = 0"); 	
		
			$box_no++;
		}
		//die;
	}
	
	public function savePallet($postdata)
	{
		$data = $this->query("SELECT pallet_no FROM invoice_pallet_test WHERE invoice_id='".$postdata['invoice_no']."' ORDER BY pallet_id DESC LIMIT 1");
		$start = $data->row['pallet_no'];	
		for($i=1;$i<=$postdata['total_pallet'];$i++)
		{
			$data=$this->query("INSERT INTO invoice_pallet_test SET pallet_no='".($start+$i)."', invoice_id='".$postdata['invoice_no']."'");
		}
	}
	
	public function deletePallet($postdata)
	{
		$data=$this->query("DELETE  FROM invoice_pallet_test WHERE pallet_id='".$postdata['pallet_id']."'");
		$data1=$this->query("UPDATE in_gen_invoice_test SET  pallet_id='0' WHERE pallet_id='".$postdata['pallet_id']."' AND invoice_id='".$postdata['invoice_id']."'");
		$this->query("UPDATE in_gen_invoice_test_new SET  pallet_id='0' WHERE pallet_id='".$postdata['pallet_id']."' AND invoice_id='".$postdata['invoice_id']."'");
		return $data;
	}
	
	public function savePalletdetail($postdata)
	{
		$total_box=count($postdata['detail']);
		$this->query("UPDATE in_gen_invoice_test_new SET pallet_id='0' WHERE pallet_id='".$postdata['pallet_id']."' AND invoice_id='".$postdata['invoice_id']."'"); 
		$sql1= "UPDATE in_gen_invoice_test SET pallet_id='0' WHERE pallet_id='".$postdata['pallet_id']."' AND invoice_id='".$postdata['invoice_id']."'"; 	
		$data1=$this->query($sql1);
		foreach($postdata['detail'] as $in_gen_invoice_id)
		{
			$this->query("UPDATE in_gen_invoice_test_new SET pallet_id='".$postdata['pallet_id']."' WHERE in_gen_invoice_id='".$in_gen_invoice_id."'");
			$sql= "UPDATE in_gen_invoice_test SET pallet_id='".$postdata['pallet_id']."' WHERE in_gen_invoice_id='".$in_gen_invoice_id."'"; 	
			$data=$this->query($sql);
		}
		return $data;
	}
	
	public function getPallet($invoice_id)
	{
		$sql1= "SELECT * FROM invoice_pallet_test WHERE invoice_id='".$invoice_id."' ORDER BY pallet_no ASC";
		$result=$this->query($sql1);
		return $result->rows;
	}
	public function getPalletS($invoice_id)
	{
		$sql1= "SELECT ip.*,count(ig.pallet_id) as tot FROM invoice_pallet_test AS ip , in_gen_invoice_test AS ig WHERE ip.invoice_id='".$invoice_id."' AND ip.pallet_id=ig.pallet_id AND ig.is_delete=0 GROUP BY ip.pallet_id ORDER BY ip.pallet_no ASC";
		$result=$this->query($sql1);
		return $result->rows;
	}
	public function getPalletDetailstotal($invoice_id) 
	{	
		$sql1= "SELECT * FROM in_gen_invoice_test WHERE invoice_id='".$invoice_id."' AND is_delete=0 AND pallet_id=0";
		$result=$this->query($sql1);
		return $result->num_rows;
	}
	public function getColorDetailstotal($invoice_id) 
	{	
		$sql1= "Select * from in_gen_invoice_test where invoice_id='".$invoice_id."' AND is_delete=0 ";
		$result=$this->query($sql1);
		//printr($result);
	//	die;
		if($result->num_rows==0)
		{
			$sql = "select sum(qty) as total from invoice_color_test where invoice_id='".$invoice_id."' ";
			$data=$this->query($sql);
			//echo $sql;
			if($data->num_rows)
			{
				return $data->row['total'];
			}
			else
			{
				return false;
			}
		}
		else
		{
			$sql = "select ifnull(ic.qty-ig.genqty,ic.qty) as total,ic.qty,ig.genqty from (select sum(qty) as qty,invoice_color_id,invoice_id from invoice_color_test group by invoice_id ) as ic	left join (select  sum(qty) as genqty,invoice_color_id from  in_gen_invoice_test WHERE is_delete=0 group by invoice_id) as ig on (  ig.invoice_color_id=ic.invoice_color_id)  WHERE ic.invoice_id='".$invoice_id."'";	
		//	echo $sql;	die;
			$data = $this->query($sql);
		//	printr($data);
			if($data->num_rows) {
				
				//[kinjal] made if condition for when i get genqty null on 9-12-2016
				if($data->row['genqty']=='')
				{
					$box_qty='select sum(qty) as genqty,invoice_color_id from  in_gen_invoice_test WHERE invoice_id="'.$invoice_id.'" AND is_delete=0 group by invoice_id';
					$data_qty = $this->query($box_qty);
					return $data->row['qty']-$data_qty->row['genqty'];
				}
				else
					return $data->row['total'];
			}
			else {
				return false;
			}
		}
	//	echo $sql;die; 
	}
	//select ic.qty,ig.titalqty,ic.qty from (select qty ,invoice_color_id from invoice_color) ic left join (select total_qty,invoice_color_id from in_gen_invoice GROUP BY invoice_color_id) 
	public function consol_list($invoice_id)
	{
		$sql="SELECT ip.invoice_product_id,ig.in_gen_invoice_id, COUNT(DISTINCT ig.in_gen_invoice_id) AS total_boxes, GROUP_CONCAT(ig.box_no ORDER BY ig.box_no ASC ) AS grouped_box_no, SUM(ig.qty) AS qty,SUM(ig.box_weight+ig.net_weight) AS gross_weight,SUM(ig.net_weight) AS net_weight  ,SUM(ic.rate) AS rate,ip.item_no,ip.zipper,ip.product_id,ip.valve,p.product_name,ic.size,tm.measurement,pc.color,ic.color_text,ic.dimension,ip.ref_no,ip.buyers_o_no,SUM(ic.rate*ic.qty) AS cost FROM in_gen_invoice_test AS ig , invoice_product_test AS ip,product AS p,invoice_color_test AS ic,template_measurement AS tm,pouch_color AS pc WHERE ig.invoice_id='".$invoice_id."' AND ig.is_delete=0 AND ig.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ig.invoice_color_id=ic.invoice_color_id AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id GROUP BY ig.invoice_color_id,ig.invoice_product_id ORDER BY ig.box_no,ig.in_gen_invoice_id ASC";
	//	echo $sql;//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function colordetails($invoice_id,$parent_id=0,$str='',$pallet='',$limit=' ',$option=array())
	{//	printr($option);
			//printr($parent_id);
			//printr($str); 
			
		//if($parent_id==0)
		//{
		   // $sql="select ic.dis_rate,ic.ref_no,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color_test as ii,pouch_color as c,template_measurement as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice_test WHERE parent_id='".$parent_id."' ".$str." ".$pallet." AND box_no BETWEEN '142' AND '260') as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC ".$limit." "; 
		//}
		//else
		//{
		$b_no="";
	/*	if($invoice_id =='1420'){
		$b_no=" AND ig.box_no<='1159' AND  ig.box_no>='601'";
		}*/
		  $sql="select ic.dis_rate,ic.ref_no,ic.no_of_cylinder,ic.cylinder_rate,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.filling_details,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ig.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.filling_details,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.no_of_cylinder,ii.cylinder_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color_test as ii,pouch_color as c,template_measurement as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id,net_weight from in_gen_invoice_test WHERE parent_id='".$parent_id."' AND is_delete=0 ".$str." ".$pallet.") as ig on (ic.invoice_color_id=ig.invoice_color_id ) where  ic.invoice_id='".$invoice_id."' $b_no "; 
//}
	    
	    if (isset($option['sort'])) {
			$sql .= " ORDER BY " .$option['sort'];	
		} else {
			$sql .= " ORDER BY ig.box_no";	
		}

		if (isset($option['order']) && ($option['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($option['start']) || isset($option['limit'])) {
			if ($option['start'] < 0) {
				$option['start'] = 0;
			}	
			if ($option['limit'] < 1) { 
				$option['limit'] = 600;
			}	
			$sql .= " LIMIT " . (int)$option['start'] . "," . (int)$option['limit'];
		}
		else
		    $sql.=$limit;  
		  
		  
		   // $sql.='LIMIT 200';
	//	 echo $sql.'<br><br>';
		  $data = $this->query($sql);	
	//	printr($sql);
		
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getcount($invoice_id,$parent_id=0,$str='',$pallet='',$limit='',$buyers_no)
	{	
		$sql="select ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,count(ic.buyers_o_no) as ct_buy,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,
		p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color_test as ii,pouch_color as c,template_measurement as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice_test WHERE parent_id='".$parent_id."' AND is_delete=0 ".$str." ".$pallet.") as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' AND ic.buyers_o_no='".$buyers_no."' ORDER BY ig.box_no ASC ".$limit." "; 
	//echo $sql.'<br><br>';//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function gettotalboxweight($invoice_id,$n=0)
	{
		if($n=='1')
			$parent_id='AND parent_id!=0';
		else
			$parent_id='AND parent_id=0';
			
		$sql = "SELECT SUM(box_weight) as total_box_weight, SUM(net_weight) as total_net_weight, SUM(box_weight+net_weight) as total_gross_weight FROM `" . DB_PREFIX . "in_gen_invoice_test` WHERE invoice_id = '".$invoice_id."' AND is_delete=0 $parent_id";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
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
	
	
	
	public function checkInvoiceNo($invoice_no)
	{
		$sql = "SELECT invoice_no FROM `" . DB_PREFIX . "invoice_test` WHERE invoice_no = '" .(int)$invoice_no. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function removeInvoice($invoice_product_id,$invoice_id)
	{
		$sql1=$this->query("DELETE FROM invoice_color_test WHERE invoice_product_id='".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
		//$sql1=$this->query("UPDATE " . DB_PREFIX . "invoice_color_test SET is_delete = '1', date_modify = NOW() WHERE invoice_product_id='".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
		$sql = $this->query("DELETE FROM invoice_product_test  WHERE `invoice_product_id` = '".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
		//$sql = $this->query("UPDATE " . DB_PREFIX . "invoice_product_test SET is_delete = '1', date_modify = NOW() WHERE invoice_product_id='".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
		 //$this->query("DELETE FROM in_gen_invoice_test  WHERE `invoice_product_id` = '".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
		 $deleted_by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');
		 $this->query("UPDATE " . DB_PREFIX . "in_gen_invoice_test SET is_delete = '1', deleted_by = '".$deleted_by."' WHERE `invoice_product_id` = '".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
		 $this->query("UPDATE " . DB_PREFIX . "in_gen_invoice_test_new SET is_delete = '1', deleted_by = '".$deleted_by."' WHERE `invoice_product_id` = '".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
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
	//print_r($data);die;
		if($data['country_id'] == 111) {
		if(isset($data['tax_mode']) && $data['tax_mode'] == 'normal') {
				if(isset($data['taxation'])){
					$taxation = $data['taxation'];
					$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id FROM " . DB_PREFIX . "taxation WHERE tax_name ='Normal' AND status = '1' AND is_delete = '0' ORDER BY taxation_id DESC LIMIT 1";
					$data_tax = $this->query($sql);
					$taxation_data=$data_tax->row;
					$excies = $taxation_data['excies'];
					$tax = $taxation_data[$taxation];			
				}
			}elseif(isset($data['tax_mode']) && $data['tax_mode'] == 'form'){
				if(isset($data['taxation'])) $taxation = $data['taxation']; else $taxation ='';
				$form_names = $data['form'];
				$h_form=array();
				foreach($form_names as $form_name) {
					if($form_name == 'H Form') {
						$sql = "SELECT excies FROM " . DB_PREFIX . "taxation WHERE tax_name = '".$form_name."' AND status = '1' AND is_delete = '0' ORDER BY taxation_id DESC LIMIT 1";
						$data_tax = $this->query($sql);
						$taxation_data=$data_tax->row;
						$excies = $taxation_data['excies'];
						$tax = 0;
						array_push($h_form,'h_form');
					}elseif($form_name == 'CT1' || $form_name == 'CT3') {
						$excies=0;
						if(in_array("h_form", $h_form)){
							$excies=0;
							$tax=0;
						}
						else{
						$sql = "SELECT cst_with_form_c,cst_without_form_c,vat FROM " . DB_PREFIX . "taxation WHERE tax_name = '".$form_name."' AND status = '1' AND is_delete = '0' ORDER BY taxation_id DESC LIMIT 1";
						$data_tax = $this->query($sql);
						$taxation_data=$data_tax->row;
						$tax=$taxation_data[$taxation];
						array_push($h_form,$form_name);
						}
					}
				}
				if(in_array("h_form", $h_form) && (in_array("CT1", $h_form) || in_array("CT3", $h_form)) ){
					$excies=0;
					$tax=0;
					$taxation ='';
				}
			}}
			 else{
				$taxation ='';
				$tax = 0;
				$excies = 0;
			}
		if(isset($data['form']))
			$form=implode(",",$data['form']);
		else
			$form='';
		if(!isset($data['ref_no']))
			$data['ref_no']='';
		if(!isset($data['buyersno']))
			$data['buyersno']='';
		if(!isset($data['other_ref']))
			$data['other_ref']='';
		if(!isset($data['pre_carrier']))
			$data['pre_carrier']='';
		if(!isset($data['other_buyer']))
			$data['other_buyer']='';
		if(!isset($data['portofload']))
			$data['portofload']='';
		if(!isset($data['port_of_dis']))
			$data['port_of_dis']='';
		if(!isset($data['delivery']))
			$data['delivery']='';
		if(!isset($data['hscode']))
			$data['hscode']='';
		if(!isset($data['printedpouches']))
			$data['printedpouches']='';
		if(!isset($data['pouch_desc']))
			$data['pouch_desc']='';
		if(!isset($data['tran_desc']))
			$data['tran_desc']='';
		if(!isset($data['tran_charges']))
			$data['tran_charges']='';
		if(!isset($data['extra_tran_charges']))
			$data['extra_tran_charges']='';
		if(!isset($data['tool_cost']))
			$data['tool_cost']='';
		if(!isset($data['transport']))
			$data['transport']='';
		if(!isset($data['container_no']))
			$data['container_no']='';	
		if(!isset($data['rfid_no']))
			$data['rfid_no']='';
		if(!isset($data['seal_no']))
			$data['seal_no']='';
		if(!isset($data['cylinder_charges']))
			$data['cylinder_charges']='';
		if(!isset($data['postal_code']))     
			$data['postal_code']='';
		if(!isset($data['account_code']))
			$data['account_code']='';
		if(!isset($data['sent']))
			$data['sent']='';
		if(!isset($data['invoice_status']))
			$data['invoice_status']='';
	    if(!isset($data['uk_ref_no']))
			$data['uk_ref_no']=''; 
	    if(!isset($data['remarks']))
			$data['remarks']='';
	     if(!isset($data['customer_dispatch']))
			$data['customer_dispatch']='0'; 
		if(!isset($data['igst_status']))
			$data['igst_status']='0';
		if(!isset($data['courier_id']))
			$data['courier_id']='0'; 
		if(!isset($data['air_f_status']))//add by sonu 05-09-2019 for munus air freight
			$data['air_f_status']='0'; 
	    if(!isset($data['show_pallet']))
			$data['show_pallet']='0';
	     
	
	     	$import=''; 
	         if($data['customer_dispatch']==1  && $data['transport'] =='sea'){
		        	$import = ',import_status=1';
	         }
	     
	     
	       $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');      	 
		//kavita patel 16-8-2017 invoice_title='".$data['invoice_title']."'	;	
		$sql = "UPDATE `" . DB_PREFIX . "invoice_test` SET invoice_no='".$data['invoiceno']."',invoice_date = '" .$data['invoicedate']. "',uk_ref_no='".$data['uk_ref_no']."',exporter_orderno = '".$data['ref_no']."',invoice_title='".$data['invoice_title']."',gst='".$data['gst']."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."', buyers_orderno ='".$data['buyersno']."',other_ref ='".$data['other_ref']."',pre_carrier='".$data['pre_carrier']."',consignee='".addslashes($data['consignee'])."',buyer='".$data['other_buyer']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',port_load='".$data['portofload']."',port_discharge='".$data['port_of_dis']."',final_destination='".$data['country_id']."', taxation='".$taxation."', excies ='".$excies."', tax='".$tax."', tax_form ='".$form."', tax_mode ='".$data['tax_mode']."',payment_terms='".$data['payment_terms']."',delivery='".$data['delivery']."',HS_CODE='".$data['hscode']."',pouch_type='".$data['printedpouches']."',pouch_desc='".$data['pouch_desc']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',extra_tran_charges='".$data['extra_tran_charges']."',tool_cost='".$data['tool_cost']."',cylinder_charges='".$data['cylinder_charges']."',transportation='".encode($data['transport'])."',remarks='".$data['remarks']."',container_no='".addslashes($data['container_no'])."',rfid_no='".addslashes($data['rfid_no'])."',seal_no='".addslashes($data['seal_no'])."',curr_id='".$data['currency']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',status = '".$data['status']."',postal_code = '".$data['postal_code']."',account_code = '".$data['account_code']."',show_pallet = '".$data['show_pallet']."',courier_id = '".$data['courier_id']."',customer_dispatch = '".$data['customer_dispatch']."',igst_status = '".$data['igst_status']."',sent = '".$data['sent']."',air_f_status = '".$data['air_f_status']."',invoice_status = '".$data['invoice_status']."',date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' ,is_delete=0,generate_status='1',edit_by='".$by."'  $import  WHERE invoice_id = '".$data['invoice_id']."'";
	
		$data1 = $this->query($sql);
		//printr($data1);
		$final_total=$this->addinvoicetotalamount($data['invoice_id']);
		return $data1;
		
	}
	
/*	public function updateInvoiceProduct($data)
	{		
		$sql1 = "UPDATE `".DB_PREFIX."invoice_product_test` SET invoice_id='".$data['invoice_id']."',product_id='".$data['product']."', valve = '".$data['valve']."',make_pouch='".$data['make']."', zipper = '".$data['zipper']."', spout = '".$data['spout']."', accessorie = '".$data['accessorie']."',net_weight='".$data['netweight']."',measurement_two='".$data['measurement']."',buyers_o_no='".$data['buyers_o_no']."',item_no='".$data['item_no']."',date_added = NOW(), date_modify = NOW(), is_delete = 0 Where invoice_product_id = '".$data['pro_id']."'";
		$data1 = $this->query($sql1);
		$id=$data['invoice_id'];
		$product_id=$data['pro_id'];
		if(isset($data['color']) && !empty($data['color'])) {
		//	$sql3 = "DELETE FROM `" . DB_PREFIX . "invoice_color` WHERE invoice_product_id='".$data['pro_id']."'";
		 //$data2=$this->query($sql3);
	//	 printr($data['color']);
			foreach($data['color'] as $color)
			{   if(isset($color['dimension']))
					$dim=$color['dimension'];
				else
					$dim='';
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
				
				if(isset($color['invoice_color_id']) && $color['invoice_color_id']!='')
				{
					if(isset($color['color']) && $color['color']!='')
					{
						$sql = "UPDATE `".DB_PREFIX."invoice_color_test` SET invoice_id = '".$id."', invoice_product_id = '".$product_id."',color_text='".$clr_txt."',color = '".$color['color']."',size='".$color['size']."', rate ='".$color['rate']."', qty = '".$color['qty']."', measurement = '".$color['measurement']."',dimension='".$dim."',net_weight='".$color['net_weight']."' WHERE invoice_color_id='".$color['invoice_color_id']."'";
					}
					else
					{
						$sql = "DELETE FROM `".DB_PREFIX."invoice_color_test` WHERE invoice_color_id='".$color['invoice_color_id']."'";				
						$this->query("DELETE FROM  ".DB_PREFIX."in_gen_invoice_test WHERE invoice_color_id='".$color['invoice_color_id']."'");				
					}
				}
				else
				{
					$sql = "INSERT INTO `".DB_PREFIX."invoice_color_test` set invoice_id = '".$id."', invoice_product_id = '".$product_id."',color_text='".$clr_txt."',color = '".$color['color']."',size='".$color['size']."', rate ='".$color['rate']."', qty = '".$color['qty']."', measurement = '".$color['measurement']."',dimension='".$dim."',net_weight='".$color['net_weight']."'";
				}
				$data = $this->query($sql);
			}			
		}
	}*/


//change by sonu 06-03-2018
	public function updateInvoiceProduct($data)
	{	
	    
	   // printr($data);//die;
	    if($data['u_product_code']!="add"){
	        $val="zipper = '".$data['zipper']."', spout = '".$data['spout']."', accessorie = '".$data['accessorie']."'";
	    }else{
	        $val="zipper = '".encode($data['zipper'])."',spout = '".encode($data['spout'])."', accessorie = '".encode($data['accessorie'])."'";
	    }
	     $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');      	 
		$sql1 = "UPDATE `".DB_PREFIX."invoice_product_test` SET invoice_id='".$data['invoice_id']."',product_id='".$data['product_id']."',product_code_id='".$data['product_code_id']."', valve = '".$data['valve']."',make_pouch='".$data['make']."', net_weight='".$data['net_weight']."',measurement_two='".$data['measurement']."',buyers_o_no='".$data['buyers_o_no']."',ref_no='".$data['pro_ref_no']."',item_no='".$data['item_no']."',date_added = NOW(), date_modify = NOW(), is_delete = 0 ,".$val." ,edit_by='".$by."' Where invoice_product_id = '".$data['pro_id']."'";
	//	echo $sql1; die;
		$data1 = $this->query($sql1);
		$id=$data['invoice_id'];
		$product_id=$data['pro_id'];
	
		$measure = $this->getMeasurementName($data['measurement']); 
        $data['size']=$data['size'].' '.$measure['measurement'];  
		
		$sql3 = " UPDATE invoice_color_test Set invoice_product_id='".$product_id."',invoice_id='".$data['invoice_id']."',color = '".$data['color_product']."',color_text='".addslashes($data['job_name'])."', rate ='".$data['rate']."',dis_rate ='".$data['rate']."', qty = '".$data['qty']."', rack_status = '".$data['qty']."', size = '".$data['size']."',  measurement = '".$data['measurement']."',dimension='".$data['dimension']."',net_weight='".$data['net_weight']."',date_added = NOW(), date_modify = '".date('Y-m-d H:i:s')."', is_delete = 0,edit_by='".$by."' WHERE invoice_color_id='".$data['invoice_color_id']."'"; 
	
			$data3=$this->query($sql3);
		

			

	}
	public function updateBoxno($data)
	{
		$this->query("UPDATE `".DB_PREFIX."in_gen_invoice_test` SET box_no='".$data['box_no']."' WHERE in_gen_invoice_id='".$data['gen_unique_id']."'");
		$sql = "UPDATE `".DB_PREFIX."in_gen_invoice_test` SET box_no='".$data['box_no']."' WHERE in_gen_invoice_id='".$data['gen_unique_id']."'";
		//echo $sql;
		$data=$this->query($sql);
		return $data;
	}
	public function updatePalletNo($data)
	{
		$sql = "UPDATE `".DB_PREFIX."invoice_pallet_test` SET pallet_no='".$data['pallet_no']."' WHERE pallet_id='".$data['pallet_id']."'";
		$data=$this->query($sql);
		return $data;
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
	
	public function getCurrencyName($curr_id)
	{
		$sql = "SELECT currency_code,price FROM `" . DB_PREFIX . "currency` WHERE currency_id = '".$curr_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalBoxold($invoice_id)
	{
		$sql = "SELECT count(in_gen_invoice_id) as tot FROM " . DB_PREFIX . "in_lable_invoice_test WHERE invoice_id = '".$invoice_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getTotalBox($invoice_id)
	{
		$sql = "SELECT count(in_gen_invoice_id) as tot FROM " . DB_PREFIX . "in_gen_invoice_test WHERE invoice_id = '".$invoice_id."' AND is_delete=0 AND parent_id=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;

		}
	}
	public function getPlasticScoopQty($product_id,$invoice_product_id,$invoice_no)
	{
		$sql="SELECT SUM(qty) as total,SUM(rate) as tot_rate,rate,(SUM(qty)*SUM(rate)) as tot_amt FROM invoice_color_test WHERE invoice_id='".$invoice_no."' AND invoice_product_id='".$invoice_product_id."'";
		$data=$this->query($sql);
		//echo $sql;
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function getProductdeatils($invoice_no,$n=0)
	{
		$sql="SELECT * FROM invoice_product_test WHERE invoice_id='".$invoice_no."'";
		if($n==1)
		    $sql="SELECT * FROM invoice_product_test WHERE invoice_id='".$invoice_no."' AND product_id NOT IN (10,23,11,18,6,34,47,48,63,72)";

		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	
	public function getTotalPallet($invoice_id)
	{
		$sql="SELECT * FROM invoice_pallet_test WHERE invoice_id='".$invoice_id."'";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->num_rows;
		else
			return false;
	}
	
	public function viewInvoice($status,$invoice_no)
	{	
		$invoice=$this->getInvoiceNetData($invoice_no);
	//	printr($invoice['transportation']);
	//	printr(decode($invoice['transportation']));
		//die;
	
	
	
		$pallet_details=$this->getPallet($invoice_no);
		$pallet=$this->getPallets($invoice_no);
	
	   // printr($pallet);
	  //  printr($pallet_details);
		$total_pallet_details=count($pallet_details);
	
		//printr($pallet);
		$total_pallet=count($pallet);
		//$total_pallet=20;
		$total_pallet_box=0;
		foreach($pallet as $p) 
		{
			$total_pallet_box=$p['tot']+$total_pallet_box;
		}
		
	
		//$total_pallet_weight=$total_pallet*23;
		//[kinjal] on 20-2-2017 told by pinank
	
	
	
		$total_pallet_weight=$total_pallet_details*12;  
		//	$total_pallet_weight=$total_pallet*12; //change by sonu 1-10-2019    told by pinank
	
    
	
		$invoice_qty=$this->getInvoiceTotalData($invoice_no);
		///printr($invoice_qty);//die;
		$box_detail=$this->gettotalboxweight($invoice_no);
		//printr($box_detail);
		$box_det=$this->gettotalboxweight($invoice_no,'1');
		
		$alldetails=$this->getProductdeatils($invoice_no);
		
		$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_no = $con_no =$gls_no = $val_no =$chair_no=$silica_gel_no =$oxygen_absorbers_no = 0;
		
		//sonu add 15-5-2017
		$scoop_box_no = $roll_box_no = $mailer_box_no = $sealer_box_no = $storezo_box_no = $paper_box_no= $pouch_box_no = $con_box_no = $gls_box_no = $val_box_no =$chair_box_no=$silica_gel_box_no=$oxygen_absorbers_box_no =0;
		$scoop_name = $roll_name = $mailer_name = $sealer_name = $storezo_name = $paper_box_name=$pouch_name = $con_box_name=$gls_box_name=$val_box_name=$chair_box_name=$silica_gel_box_name=$oxygen_absorbers_box_name='';
		//end
		$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series = $con_series = $gls_series = $val_series =$chair_series =$silica_gel_series=$oxygen_absorbers_series ='';		
		$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper = $total_amt_con =$total_amt_gls =$total_amt_val =$total_amt_valve=$total_amt_chair=$total_amt_silica_gel=$total_amt_oxygen_absorbers = 0;
		//sonu add 15-5-2017
		$total_net_w_scoop =$total_gross_w_scoop = $total_net_w_roll=$total_gross_w_roll= $total_net_w_p =$total_gross_w_p  = $total_net_w_m =$total_gross_w_m= $total_net_w_s =$total_gross_w_s =$total_net_w_str =$total_gross_w_str =$total_net_w_pouch=$total_gross_w_con =$total_net_w_con=$total_gross_w_gls =$total_net_w_gls=$total_gross_w_val =$total_net_w_val=$total_net_w_chair=$total_net_w_silica_gel=$total_net_w_oxygen_absorbers=0;
		//end
		$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty = $tot_paper_qty = $tot_con_qty =$tot_gls_qty =$tot_val_qty=$tot_chair_qty=$tot_silica_gel_qty=$tot_oxygen_absorbers_qty =0;
		$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_paper_rate = $tot_con_rate =$tot_gls_rate =$tot_val_rate =$tot_chair_rate=$tot_silica_gel_rate=$tot_oxygen_absorbers_rate =$roll_price=0;
		$total_net_amt_scp= $total_net_amt_rol=$total_net_amt_m=$total_net_amt_pouch=$total_net_amt_s=$total_net_amt_str=$total_net_amt_ppr=$total_net_amt_con=$total_net_amt_gls=$total_net_amt_val=$total_net_amt_chair=$total_net_amt_silica_gel=$total_net_amt_oxygen_absorbers=0;
		$abcd = 'A';
		//sonu add 15-5-2017
		$f_p=$f_scoop=$f_roll=$f_mailer=$f_sealer=$f_storezo=$f_box=$f_con=$f_gls=$f_val=$f_chair=$f_oxygen_absorbers=$f_silica_gel=true;
		//end
		$first='false';$air_f = 0;
		$allproduct=$this->getProductdeatils($invoice_no,1);
		if(!empty($allproduct))
		{
		   if($first=='false')
			{
			    $air_f = 0;
			    $first = 'true';
			} 
		}
	//	printr($allproduct);
	//	printr($air_f);
		foreach($alldetails as $details)
		{
		
			if($details['product_id']=='11')
			{
				$tot_qty_scoop=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);//printr($tot_qty_scoop);
				$tot_scoop_qty = $tot_scoop_qty + $tot_qty_scoop['total']; 
				$tot_scoop_rate = $tot_scoop_rate + $tot_qty_scoop['rate'];
				$total_amt_scoop = $total_amt_scoop + $tot_qty_scoop['tot_amt'];
				$net_pouches_scoop = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_scoop = $net_pouches_scoop ['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_scoop = $net_pouches_scoop ['g_wt'];
				$scoop_box_no =  $net_pouches_scoop ['total_box'];
			//printr($scoop_box_no);
				$scoop_name = 'SCOOPS';
				//end
				$total_net_amt_scp = $net_pouches_scoop['total_amt'];
				$scoop_no = '1';
				$scoop_series = 'B';
				if($first=='false')
				{
				    $air_f = 1;
				    $first = 'true';
				}
			}
			else if($details['product_id']=='6')
			{
			   
				$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
				$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
				//$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
				$net_pouches_roll = $this->getIngenBox($invoice_no,$details['product_id'],0);
				
			//	printr($tot_qty_roll); 
				$total_net_w_roll = $net_pouches_roll['n_wt'];
				//printr($net_pouches_roll['n_wt']);
				//sonu add 15-5-2017
				$total_gross_w_roll = $net_pouches_roll['g_wt'];
				$roll_box_no = $net_pouches_roll['total_box'];
				$roll_name = 'ROLL';
				//end
				//$net_pouches_roll['total_amt']=$net_pouches_roll['total_amt']- ($net_pouches_roll['qty']*$tot_qty_roll['rate']);
				
				$roll_price=$net_pouches_roll['qty']*$tot_qty_roll['rate'];
				$total_amt_roll = $net_pouches_roll['total_amt'];
				
			//	printr($total_amt_roll);
		//	printr($roll_price);
				$roll_no = '1';
				$roll_series = 'C';
				if($first=='false')
				{
				    $air_f = 2;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='10')
			{
				$tot_qty_mailer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_mailer_qty = $tot_mailer_qty + $tot_qty_mailer['total']; 
				$tot_mailer_rate = $tot_mailer_rate + $tot_qty_mailer['rate'];
				$total_amt_mailer = $total_amt_mailer + $tot_qty_mailer['tot_amt'];
				$net_pouches_m = $this->getIngenBox($invoice_no,$details['product_id'],0);
				//printr($net_pouches_m);
				$total_net_amt_m = $net_pouches_m['total_amt'];
				$total_net_w_m = $net_pouches_m['n_wt'];
				//sonu add 15-5-2017	
				$total_gross_w_m = $net_pouches_m['g_wt'];
				$mailer_box_no = $net_pouches_m['total_box'];
				$mailer_name = 'MAILER BAGS';		
				//end
				$mailer_no = '1';
				$mailer_series = 'C';
				if($first=='false')
				{
				    $air_f = 3;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='23')
			{
				$tot_qty_sealer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_sealer_qty = $tot_sealer_qty + $tot_qty_sealer['total']; 
				$tot_sealer_rate = $tot_sealer_rate + $tot_qty_sealer['rate'];
				$total_amt_sealer = $total_amt_sealer + $tot_qty_sealer['tot_amt'];
				$net_pouches_s = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_s = $net_pouches_s['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_s = $net_pouches_s['g_wt'];
				$sealer_box_no = $net_pouches_s['total_box'];
				$sealer_name ='SEALER MACHINE';
				//end
				$total_net_amt_s = $net_pouches_s['total_amt'];
				$sealer_no = '1';
				$sealer_series = $abcd;
				if($first=='false')
				{
				    $air_f = 4;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='18')
			{
				$tot_qty_storezo=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_storezo_qty = $tot_storezo_qty + $tot_qty_storezo['total']; 
				$tot_storezo_rate = $tot_storezo_rate + $tot_qty_storezo['rate'];
				$total_amt_storezo = $total_amt_storezo + $tot_qty_storezo['tot_amt'];
				$net_pouches_str = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_str = $net_pouches_str['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_str = $net_pouches_str['g_wt'];
				$storezo_box_no = $net_pouches_str['total_box']; 
				$storezo_name = 'STOREZO';
				//end
				$total_net_amt_str = $net_pouches_str['total_amt'];
				$storezo_no = '1';
				$storezo_series = 'C';
				if($first=='false')
				{
				    $air_f = 5;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='34')
			{
				$tot_qty_paper=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_paper_qty = $tot_paper_qty + $tot_qty_paper['total']; 
				$tot_paper_rate = $tot_paper_rate + $tot_qty_paper['rate'];
				$total_amt_paper = $total_amt_paper + $tot_qty_paper['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_p = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_p = $net_pouches_p['g_wt'];
				$paper_box_no = $net_pouches_p['total_box'];
				$paper_box_name = 'PAPER BOX';
				//end
				$total_net_amt_ppr = $net_pouches_p['total_amt'];
				$paper_no = '1';
				$paper_series = $abcd;
				if($first=='false')
				{
				    $air_f = 6;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='47')
			{
				$tot_qty_con=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_con_qty = $tot_con_qty + $tot_qty_con['total']; 
				$tot_con_rate = $tot_con_rate + $tot_qty_con['rate'];
				$total_amt_con = $total_amt_con + $tot_qty_con['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_con = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_con = $net_pouches_p['g_wt'];
				$con_box_no = $net_pouches_p['total_box'];
				$con_box_name = 'PLASTIC DISPOSABLE LID / CONTAINER';
				//end
				$total_net_amt_con = $net_pouches_p['total_amt'];
				$con_no = '1';
				$con_series = $abcd;
				if($first=='false')
				{
				    $air_f = 7;
				     $first = 'true';
				}
			}
			else if($details['product_id']=='48')
			{
				$tot_qty_gls=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_gls_qty = $tot_gls_qty + $tot_qty_gls['total']; 
				$tot_gls_rate = $tot_gls_rate + $tot_qty_gls['rate'];
				$total_amt_gls = $total_amt_gls + $tot_qty_gls['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_gls = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_gls = $net_pouches_p['g_wt'];
				$gls_box_no = $net_pouches_p['total_box'];
				$gls_box_name = 'PLASTIC GLASSES';
				//end
				$total_net_amt_gls = $net_pouches_p['total_amt'];
				$gls_no = '1';
				$gls_series = $abcd;
				if($first=='false')
				{
				    $air_f = 8;
				     $first = 'true';
				}
			}else if($details['product_id']=='72')
			{ 
				$tot_qty_chair=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_chair_qty = $tot_chair_qty + $tot_qty_chair['total']; 
				$tot_chair_rate = $tot_chair_rate + $tot_qty_chair['rate'];
				$total_amt_chair = $total_amt_chair + $tot_qty_chair['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_chair = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				
			//	print($net_pouches_p);
				$total_gross_w_chair = $net_pouches_p['g_wt'];
				$chair_box_no = $net_pouches_p['total_box'];
				$chair_box_name = 'H/B CHAIR';
				//end
				$total_net_amt_chair = $net_pouches_p['total_amt'];
				$chair_no = '1';
				$chair_series = 'C ';
				if($first=='false')
				{
				    $air_f = 10;
				     $first = 'true';
				}
				
			}
			else if($details['product_id']=='38')
			{
				$tot_qty_silica_gel=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_silica_gel_qty = $tot_silica_gel_qty + $tot_qty_silica_gel['total']; 
				$tot_silica_gel_rate = $tot_silica_gel_rate + $tot_qty_silica_gel['rate'];
				$total_amt_silica_gel = $total_amt_silica_gel + $tot_qty_silica_gel['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_silica_gel = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				
		//	printr($net_pouches_p);
				$total_gross_w_silica_gel = $net_pouches_p['g_wt'];
				$silica_gel_box_no = $net_pouches_p['total_box'];
				$silica_gel_box_name = 'Silica Gel';
				//end
				$total_net_amt_silica_gel = $net_pouches_p['total_amt'];
				$silica_gel_no = '1';
				$silica_gel_series =$abcd;
				if($first=='false')
				{
				    $air_f = 11;
				     $first = 'true';
				}
				
			}
			else if($details['product_id']=='37')
			{
				$tot_qty_oxygen_absorbers=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_oxygen_absorbers_qty = $tot_oxygen_absorbers_qty + $tot_qty_oxygen_absorbers['total']; 
				$tot_oxygen_absorbers_rate = $tot_oxygen_absorbers_rate + $tot_qty_oxygen_absorbers['rate'];
				$total_amt_oxygen_absorbers = $total_amt_oxygen_absorbers + $tot_qty_oxygen_absorbers['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_oxygen_absorbers = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				 
			//	printr($tot_qty_oxygen_absorbers);
			//	printr($details['product_id']);
			//	printr($net_pouches_p);
				$total_gross_w_oxygen_absorbers = $net_pouches_p['g_wt'];
				$oxygen_absorbers_box_no = $net_pouches_p['total_box'];
				$oxygen_absorbers_box_name = 'Oxigen absorbers';
				//end
				$total_net_amt_oxygen_absorbers = $net_pouches_p['total_amt'];
				$oxygen_absorbers_no = '1';
				$oxygen_absorbers_series = $abcd;
				if($first=='false')
				{
				    $air_f = 12;
				     $first = 'true';
				}
				
			}
			else if($details['product_id']=='63')
			{
				$tot_qty_val=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_val_qty = $tot_val_qty + $tot_qty_val['total']; 
				$tot_val_rate = $tot_val_rate + $tot_qty_val['rate'];
				$total_amt_valve = $total_amt_valve + $tot_qty_val['tot_amt'];
				$net_pouches_p = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_val = $net_pouches_p['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_val = $net_pouches_p['g_wt'];
				$val_box_no = $net_pouches_p['total_box'];
				$val_box_name = 'PLASTIC CAP';
				//end
				$total_net_amt_val = $net_pouches_p['total_amt'];
				$val_no = '1';
				$val_series = $abcd;
				if($first=='false')
				{
				    $air_f = 9;
				     $first = 'true';
				}
			}
			else if($details['product_id']!='11' && $details['product_id']!='6' && $details['product_id']!='10' && $details['product_id']!='23' && $details['product_id']!='18' && $details['product_id']!='34' && $details['product_id']!='47' && $details['product_id']!='48' && $details['product_id']!='63' && $details['product_id']!='72' && $details['product_id']!='38' && $details['product_id']!='37')
			{
				$net_pouches_pouch = $this->getIngenBox($invoice_no,2,0);
				$total_net_w_pouch =$net_pouches_pouch['n_wt'];
				$total_net_amt_pouch = $net_pouches_pouch['total_amt'];
				//sonu add 15-5-2017
				$total_gross_w_p = $net_pouches_pouch['g_wt'];
				$pouch_box_no = $net_pouches_pouch['total_box'];
				$pouch_name = 'POUCHES';	
				//printr($net_pouches_pouch);
				//sonu end	
				if($first=='false')
				{
				    $air_f = 0;
				     $first = 'true';
				}
			}
			$abcd++;
            
           
			
		}
	    
			if($invoice['country_destination']=='252')
			$uk_colspan = "8";
		    else
		    $uk_colspan = "8";
		  
		  
		  
		  
		 // printr($air_f);	
	// add sonu 20-6-2018	told by jaimini
		if($invoice['exporter_orderno']=='0'){
		    $exporter_orderno = '';
		    
		    
		}else{
		    $exporter_orderno =$invoice['exporter_orderno'];
		    
		} 
		
		$totgross_weight=$box_detail['total_net_weight']+$box_detail['total_box_weight']+$box_det['total_net_weight']; 
		
	    $courier_account_number=$this->getcourier_account_number($invoice['order_user_id'],$invoice['country_destination'],$invoice['courier_id']);
	   
	    $fedex_no='';
    	if($courier_account_number['account_number']!='' &&  ucwords(decode($invoice['transportation']))=='Air'){
                $fedex_no='<br><b> '.$courier_account_number['company_name'].'  '.$courier_account_number['courier_name'].'  Account No : '.$courier_account_number['account_number'].'</b> ';
    	}
		    
		 
	//	printr($box_detail['total_net_weight'].'+'.$box_detail['total_box_weight'].'+'.$box_det['total_net_weight']);
		$taxation=$invoice['taxation'];
		$html='';                       
  		$html.='<div class="panel-body" id="print_div" style="padding-top: 0px;width:754px">
					<div class="">
					 <div class="form-group ">  	';
     if($invoice['invoice_date']>'2019-06-19'){
      	$fixdata = $this->getFixmaster(2); 
     }else{
         $fixdata = $this->getFixmaster(1); 
     }
       $html.='<table style="cellpadding:0px;cellspacing:0px;  font-size: 10px;" border="1" cellpadding="0" cellspacing="0" >
	   	
	  	<tr><td colspan="'.$uk_colspan.'">';
				   if($status==1)
				   {
				   	$uk = '';
				   	if($invoice['country_destination']=='252')
				   		//kavita 16-8-2017
				   		$uk = $invoice['invoice_title'];
				   		
						$str=strtoupper($uk);
				  		$html.='<h1>'.$str.' INVOICE</h1>';
						//end
				   }
				  else{
				  		$html.='<h1>PACKING</h1>';
				   }
				    
				   	if($invoice['invoice_date']<'2019-09-26'){ 
				   	    $invoiceno_td_text="<u>Invoice No. & Date:</u>";
				   	}else{
				   	     $invoiceno_td_text="Invoice No:";
				   	}
				  $html.='</td></tr>
        
        	 <tr>
          		 <td colspan="4" ><strong>Exporter:</strong></td>
         		 <td colspan="4"><strong>'.$invoiceno_td_text.'</strong><strong style="float:right"><u> Exporters Order No.: </u>&nbsp;&nbsp;&nbsp;&nbsp;
                 </strong></td>
         	 </tr>
      	     <tr>
      	       		<td colspan="4"  style="vertical-align: top;"><div>'.nl2br($fixdata['exporter']).'</div></td>
               		<td colspan="4"><table>';
					// <img src="https://www.bcgen.com/demo/linear-dbgs.aspx?D='.$invoice['invoice_no'].'">
					//<img class="barcode" alt="'.$invoice['invoice_no'].'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.$invoice['invoice_no'].'&codetype=code128&orientation=horizontal&size=30&print=true"/>
					 
					 
					 $html.='<tr>';
					 	
					  if($invoice['invoice_date']<'2019-09-27'){ 
					      $html .='<td>'.$invoice['invoice_no'].'&nbsp;/&nbsp;';
        					 $dt = strtotime($invoice['invoice_date']);
        				//	 echo $dt;
        					 if(date("m",$dt)>=4)
        					   $html .=(date("y",$dt)).'-'.(date("y",$dt)+1);
        					 else
        					   $html .=(date("y",$dt)-1).'-'.(date("y",$dt));
        					    
        					 $html .='&nbsp;/&nbsp;'.date("d-m-Y",strtotime($invoice['invoice_date'])).'';
              		 }else{
              		    $html .='<td style="font-weight: bold;">'.$invoice['invoice_no'].'&nbsp;&nbsp;&nbsp;&nbsp;';
              		    $html .='<br>Date : '.date("d-m-Y",strtotime($invoice['invoice_date'])).'&nbsp;&nbsp;&nbsp;&nbsp;';
              		     $html .= 'F.Y : ';
              		    $dt = strtotime($invoice['invoice_date']);
              		     if(date("m",$dt)>=4)
        					   $html .=(date("Y",$dt)).'-'.(date("y",$dt)+1);
        					 else
        					   $html .=(date("Y",$dt)-1).'-'.(date("y",$dt)); 
        			$html.="&nbsp;&nbsp;&nbsp;&nbsp;";
              		 }
              		 
                	$html .='<span style="float:right">'.$exporter_orderno .'&nbsp;&nbsp;&nbsp;&nbsp;</span>
					 
                   <span><img style="width:156px;height:81px;" class="barcode" alt="'.trim($invoice['invoice_no']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($invoice['invoice_no']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span>
                   
                   
                   </td></tr>';
                   if($invoice_no=='5'){
                    $html .='<br><br><span><img src="https://chart.googleapis.com/chart?chs=156x81&cht=qr&chl='.$invoice['invoice_no'].'"></span>';
                   }
                    if($invoice['order_type']!='sample') 
		 			{
		 			    
					//	if($invoice['country_destination']=='252' ||$invoice['country_destination']=='251'|| $invoice['country_destination']=='129'|| $invoice['country_destination']=='90'||$invoice['country_destination']=='170'||$invoice['country_destination']=='172'||$invoice['country_destination']=='230'||$invoice['country_destination']=='253'||$invoice['country_destination']=='209'){
						//&& $invoice['country_destination']!='42' 04-01-2017
						
						    if($invoice['uk_ref_no']!=''){
						        $invoice['uk_ref_no']=$invoice['uk_ref_no'];
						    }else{
						       $invoice['uk_ref_no']= $invoice['buyers_orderno'];
						    }
						//printr($invoice['uk_ref_no']);
						
						   	if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155' && $invoice['country_destination']!='42' ){
						    	$html.='<tr><td ><strong>Buyers Order/Ref. No.:</strong> &nbsp;'.$invoice['uk_ref_no'].' </td></tr>';
									
				        	}else{
						    	$html.='<tr><td><strong>Buyers Order/Ref. No.:</strong> &nbsp;'.$invoice['buyers_orderno'].'</td></tr>';
								
					        }
					        $html.='<tr>';
									 if($status==1){
										$html.='<td ><strong>Other Referene(s):</strong> &nbsp;'. $invoice['other_ref'].'</td>'; 
										}
					}
								$html.='</tr></table></td>
    	     </tr>';
			 if($invoice['order_type']!='sample')
		 		$buyers = '<strong><u>Buyer (If other than consignee):</u></strong><br/>'.$invoice['buyer'];
			 else
			 	$buyers = 'FREE SAMPLE NO COMMERCIAL VALUE INVOLVED ';
		
		    
		//if($status==1){
             $html .= '<tr>              
               		 <td colspan="4">&nbsp;</td>';
               	if($invoice['buyer']!='')	 
               	     $html .= ' <td colspan="4" rowspan="3" style="vertical-align: top;">'.$buyers.' '.$fedex_no.'</td>';
               	 else
                	$html .= ' <td colspan="4" rowspan="3" style="vertical-align: top;">'.$buyers.'</td>';
               	
            $html .= '  </tr>
      		 <tr>
           			 <td colspan="4" style="vertical-align: top;"><strong>Consignee :</strong></td>
          	</tr>';
		/*} else {
		
			$html .= '<tr>
							 <td colspan="4" style="vertical-align: top;"><strong>Consignee:</strong></td>
							 <td colspan="4">'.$buyers.'</td>
          			</tr>';
		}*/
		//if( $_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
		   // printr(nl2br($invoice['consignee']));die;
		    
	        if($invoice['customer_dispatch']==1){
	            $pouch_maker_fedex_no='';
	        }
       		  $html .='<tr>
          			        <td colspan="4" rowspan="2"  style="vertical-align: top;"><div>'.nl2br($invoice['consignee']).''.$fedex_no.'</div></td>
       		         </tr>';
       		         
       		         
			  if($invoice['order_type']!='sample')
			  {
				  $html .='<tr>
						 <td colspan="2" style="vertical-align: top;"><strong><u>Country of origin of goods</u></strong><br/>'.$fixdata['country_origin_goods'].'</td>
						 <td colspan="2" style="vertical-align: top;"><strong><u>Country of Final Destination</u></strong><br />
							 <div>'; 
						//	 printr($invoice['country_destination']);
							 if(is_numeric($invoice['country_destination']))
    								{
							 
						        	 $country_name = $this->getCountryName($invoice['country_destination']);
							 	     $html.= utf8_encode($country_name['country_name']);
    								}else{
    								    $html.= utf8_encode($invoice['country_destination']);
    								}
				$html.='</div></td> </tr>'; 
				if($invoice['invoice_date']>'2019-01-22')
			    	$pre_carrige='By Road';
			    else
			        $pre_carrige='By '. ucwords(decode($invoice['transportation'])).'';
			 /*	  <div>By '. ucwords(decode($invoice['transportation'])).'</div></td>  told by pinank 23-01-2019 */
         	$html .='<tr>
          			 <td colspan="2" style="vertical-align: top;"><strong><u>Pre-Carrige By:</u></strong><br/>
         		  			  <div>'.$pre_carrige.'</div></td>
          			 <td colspan="2" style="vertical-align: top;"><strong><u>Place Of Receipt By Pre-Carrier:</u></strong><br />
           					  <div>'; $city_name = $this->getCityNameAgain($invoice['pre_carrier']);
						 	$html.= $city_name['city_name'].'</div></td>
          			 <td colspan="4" rowspan="3" valign="top"><strong>Payments Terms:</strong>&nbsp;'.$invoice['payment_terms'].'<br /><br>
                        <strong>Delivery:</strong>&nbsp;'. $invoice['delivery'].'<br /><br>
                        <strong>Mode of Shipment:</strong>&nbsp;By '. ucwords(decode($invoice['transportation'])).'
                        </td>
         	</tr>
           <tr>
          	 		<td colspan="2" style="vertical-align: top;"><strong><u>Vessel Name and No.:</u></strong><br />
            			 <div>'. $invoice['vessel_name'].'</div></td>
             
         	  		<td  colspan="2" style="vertical-align: top;"><strong><u>Port of Loading:</u></strong><br />
            			 <div>'; $city_name = $this->getCityNameAgain($invoice['port_load']);
						 	$html.= $city_name['city_name'].'</div></td>
         	</tr>
        	<tr>
           			<td colspan="2" style="vertical-align: top;"><strong><u>Port of Discharge:</u></strong>
         			  <br />
           				<div>'; $country_name=$this->getCountryName($invoice['port_discharge']); 
           				            	 //add sejal 24-4-2017
           				            	 
           				            
           				            	 $port_discharge ='';
    									if(is_numeric($invoice['port_discharge']) && ucwords(decode($invoice['transportation'])))
    									{
    									    
    										$country_name=$this->getCountryName($invoice['port_discharge']);
    										$port_discharge = $country_name['country_name'];
    											
    										
    									}
    									else
    									{	// printr($invoice['port_discharge']);
    									    $port_discharge = $invoice['port_discharge'];
    										
    											
    									}
           				            	
           				            	
           				            /*	if($invoice['order_user_id']=='24' && ucwords(decode($invoice['transportation']))=='Sea')
										 {
											$html.= 'Melbourne Sea Port , '.$port_discharge.'</div></td>';
										 }
										 elseif($invoice['order_user_id']=='33' && ucwords(decode($invoice['transportation']))=='Sea')
										 {
											$html.= 'Sydney Sea Port , '.$port_discharge.'</div></td>';
										 }
										 else
										 {*/
											$html.= utf8_decode($port_discharge).'</div></td>';
										// }
										  // sejal end
							// $h
		 						// $html.= $country_name['country_name'].'</div></td>
           			$html.='<td colspan="2" style="vertical-align: top;"><strong><u>Final Destination:</u></strong>
           				<br />
            			 <div>';  $country_name=$this->getCountryName($invoice['final_destination']); 
            			 			//add sejal 24-4-2017
						 $final_destination ='';
									if(is_numeric($invoice['port_discharge']))
    								{
    									 $country_name=$this->getCountryName($invoice['final_destination']); 
    										$final_destination = $country_name['country_name'];
    										
    									
    									}
    								else
    								{	$final_destination = $invoice['final_destination'];
    									
    										
    								}
            			 			/* if($invoice['order_user_id']=='24' && ucwords(decode($invoice['transportation']))=='Sea')
        							 {
        							 	$html.= 'Melbourne Sea Port , '.$final_destination .'</div></td>';
        							 }
        							 elseif($invoice['order_user_id']=='33' && ucwords(decode($invoice['transportation']))=='Sea')
        							 {
        							 	$html.= 'Sydney Sea Port , '.$final_destination .'</div></td>';
        							 }
        							 else
        							 {*/
        								$html.=utf8_decode($final_destination) .'</div></td>';
        							// }
							 		 // sejal end
							// $html.= $country_name['country_name'].'</div></td>
        $html.=' </tr>';
		  }
		 else
		 {
		 	  $html .='<tr>
						<td colspan="4" style="vertical-align: top;"><strong>IN THIS SHIPMENT</strong><br>No Foreign Exchange involve in this shipment</td>
         			</tr>';
		 }
		 
		 $currency=$this->getCurrencyName($invoice['curr_id']);
		 $total_no_of_box=$this->getTotalBox($invoice_no);
		 $child_net = 0;
		 $measurement=$this->getMeasurementName($invoice['measurement']);
		 if($invoice['order_type']!='sample')
		 {
		 
         	$html.='<tr style="vertical-align: top;">';  
		 	//printr($currency);
				 
           		$html.='<th colspan="2">Marks & No.'; 
				if($status==1)
				{ 
					$html.=' /Container No.</th>
                
					 <th colspan="2">No. & Kind of Packages</th>
					 <th>Description</th>
					 <th>Quntity In Nos.</th>
					 <th>Rate per '. $currency['currency_code'].'<br />
								 Per 1 Nos</th>
					 <th>Amount <br />'. $currency['currency_code'].'</th>';

                  } else { 
					 $html.='</th><th colspan="3">Description</th>
					 <th>Quntity In Nos.</th>
					 <th colspan="2">Remarks</th>';
                  } 
				  
				 $html.='</tr>';
				
				// added by sonu 21-05-2018
				    $thailand_line="";
					if($invoice['country_destination']=='238')
					/*    $thailand_line ="EXPORTER SWISS PAC PVT LTD CONSIGNEE FOIL PACKAGING CO. LTD";*/
					    $thailand_line ="Exporter SWISS PAC PVT LTD <br>Consignee Foil Packaging co Ltd <br>";
				
				    
				
				 
				 if($box_det['total_net_weight']!='')
				 	$child_net= $box_det['total_net_weight'];
				
				//printr($box_detail); 
				$html.=' <tr id="one_tr">
								 <td  colspan="2" class="no_border">'.$total_no_of_box['tot'].' Boxes<br>(Nos. 1 to '.$total_no_of_box['tot'].')<br>Corrugated Box Packing<br>'.$thailand_line.'Gross weight : '.
									number_format($totgross_weight,3).' '.$measurement['measurement'].'<br>';
									if(ucwords(decode($invoice['transportation']))=='Sea' || $invoice['show_pallet']=='1')
										$html.='Tare Weight : '.$total_pallet_weight.' Kgs<br>';
									$html.=' Net Weight : '.number_format($box_detail['total_net_weight']+$child_net,3).'&nbsp;'.$measurement['measurement'].'<br>';
									if(ucwords(decode($invoice['transportation']))=='Sea' || $invoice['show_pallet']=='1')
									{
									//	printr($total_pallet_box);
								//	echo '(('.$total_no_of_box['tot'].')-('.$total_pallet_box.'))';
										$loose_boxes=(($total_no_of_box['tot'])-($total_pallet_box));
										$html.='Total '.$total_pallet.' Wooden Pallets<br>Contaning  '.$total_pallet_box.' Boxes  ';
										if($loose_boxes>0)
										$html.=' , & '.$loose_boxes.' Loose Boxes';
									}
									else
								
									$html.='Total '.$total_no_of_box['tot'].' Boxes ';	
											 
						 $html.='</td>	
										<td colspan="3"  class="no_border"><div>';
										if($pouch_name=='POUCHES'){ 
										  //  printr($pouch_name);
    										   if($invoice['country_destination'] != '253'){
    										       
    										     $html.=''.$fixdata['num_packages'].'';
    										   }else{
    										        if($invoice['invoice_date']<'2019-07-16'){
    										           $html.=''.$fixdata['num_packages_us'].'';
    										        }
    										   }
										   }
									$html.='</div></td>
										<td class="no_border"></td>'; 
						 
						 if($status == 1) {
								 $html.='<td class="no_border"></td>
										 <td class="no_border"></td>';
						  } else { 
								 $html.='<td class="no_border" colspan="2"></td>';
						  }

                     $html.='</tr>
                     <tr>
                     		<td colspan="2" class="no_border">'. $fixdata['mark_no'];
                     		
                     			if($invoice['country_destination']=='42'|| $invoice['country_destination']=='14')	
					            	$html.='<br><br><br>A/c no : '.$invoice['account_code'];
					        	if($invoice['country_destination']=='189')	
					            	$html.='<br><br><br>Fedex A/c no : '.$invoice['account_code'];
					       
						//Coffee Pouches
                     		 $html.='</td> 
					 
         		 			<td colspan="3" class="no_border">';
         		 			if($pouch_name=='POUCHES'){
          				 		
              				 		$html.='<b>A) ';
              				 	 
              				 	
    								if($invoice['pouch_desc']!='')
    									$html.=$invoice['pouch_desc'].'<br />';
    								
    								$html.= $invoice['pouch_type'].'</b></div>';
    								  
    								 
    							//	 printr($invoice['country_destination']);
    							
    							if($invoice['country_destination']=='251'){
    								      if($invoice['invoice_date']>'2019-09-04'){
        								     $modal_no=$this->getmodelnumber($invoice_no);
        								     //wordwrap($modal_no,40,"<br>\n")
        								     $html.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><p style=" width:auto;">Model Numbers :</b> '.$modal_no.'</p>';
    								      }
    								 }
    						    	$html.='<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HS CODE:&nbsp;'. $invoice['HS_CODE'].'<br>';
    						       // if($invoice['country_destination']=='252')
    						       $cond_net_weight = number_format($box_detail['total_net_weight']+$child_net,3); 
    						      
    						       if(str_replace(',', '', $cond_net_weight) >'200' && $invoice['invoice_date']>'2018-03-31' &&  $invoice['country_destination']!='169'){//printr($cond_net_weight);
                						            $html.='<div><strong>Shipment under the Duty DrawBack Scheme<br>Vide DBK SR No 392399 B @ 1.5 % On FOB<br> (Cenvat facility has been availed)</strong></div>';
                						           //  $html.='<div><strong>We Intend to claim rewards under merchandise Export <br></strong> From India Scheame (MEIS)</div><br />';
                				                    	 //printr($invoice_qty);
                					                    //echo $roll_no;
                						       }
         		 			}
						       if(ucwords(decode($invoice['transportation']))=='Sea') 
								{  
							        if($invoice['invoice_date']>'2018-10-21'){
							              $html.='<br><br><div><strong>This Shipment taken under the EPCG Licence <br> Licence No 3430003005 Dated 23.01.2017 </strong></div>';
							           }
							        else    if($invoice['invoice_date']>'2018-07-06'){
								     $html.='<br><br><div><strong>This Shipment taken under the EPCG Licence <br> Licence No 3430002776 Dated 01.12.2015 </strong></div>';
							       }
							           else{
							         $html.='<br><br><div><strong>This Shipment taken under the EPCG Licence <br> Licence No 3430002582 Dated 17.12.2014 </strong></div>';
							         
							       }
								}
							
								if($air_f=='0'){
								     
								     	$total_amt_val=$invoice_qty['tot'];
								     //	printr($total_amt_val);
								     	if(ucwords(decode($invoice['transportation']))=='Sea')
							            {
    								           $total_amt_val=$total_amt_val+$invoice['cylinder_charges'];
    								            if($invoice['invoice_date']<'2019-03-19'){	
                								    $amt = ($total_amt_val-($invoice['tran_charges']+$roll_price+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel));
                							    }
                							    else
                							       	$amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_roll+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_silica_gel+$total_amt_oxygen_absorbers));
                                
							          
							            }else{
							                      if($invoice['invoice_date']<'2018-10-12'){	
                        							  if($invoice_no!='1899'){
                        								    if($invoice['country_destination']=='172' || $invoice['country_destination']=='253' )//$invoice['country_destination']=='125' || 
                                								{
                                								    
                                								    $rate_per = number_format((($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_silica_gel+$total_amt_oxygen_absorbers))/$total_qty_val),8);
                                								    $amt = ($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel));
                                								}
                        							  }
                        						 }
                        					  $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_silica_gel+$total_amt_oxygen_absorbers));

							            }
								       
    								if($amt<$invoice['tran_charges']){
                                            if($air_f==0 && $total_amt_scoop>$invoice['tran_charges']){
                                                $air_f=5;
                                            }
                                            else if($total_amt_roll>$invoice['tran_charges']){
                                                $air_f=2; 
                                            }
                                            else{
                                                 $air_f=1;
                                            }
                                     }  
    								
								    
								}
								if($invoice['invoice_date']>'2019-09-06')
                                {
                                    $air_f=$invoice['air_f_status'];
                                }
							//	printr($air_f);
								//trying to minus air freight equally from all price and rate
								/*$fr=0;
								if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
								{   $tran_charges_divided=0;
								   if($scoop_no == '1')
								       $fr+=1;
								   if($roll_no == '1')
								       $fr+=1;
								    if($mailer_no == '1')
								       $fr+=1;
								    if($sealer_no == '1')
								       $fr+=1;
								    if($storezo_no == '1')
								       $fr+=1;
								    if($paper_no == '1')
								       $fr+=1;
								    if($con_no == '1')
								       $fr+=1;
								    if($gls_no == '1')
								       $fr+=1;
								    if($val_no == '1')
								       $fr+=1;
								    if($pouch_name = 'POUCHES')
								        $fr+=1;
								     
								   $tran_charges_divided =  $invoice['tran_charges'] / $fr;
								}*/
								
								
								if($scoop_no == '1')
								{	
								       // printr($invoice['tran_charges'].'&1');
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_chair_qty+$tot_silica_gel_qty+$tot_oxygen_absorbers_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_con_rate +$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_silica_gel_rate+$tot_oxygen_absorbers_rate);
									$total_amt_val=$invoice_qty['tot'];
									
									//trying to minus air freight equally from all price and rate
									/*if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
									    $total_amt_scoop = $total_amt_scoop - $tran_charges_divided;
									else
									{*/
									    if($air_f=='1')
									       $total_amt_scoop = $total_amt_scoop - $invoice['tran_charges'];
									//}
									    
									       
								}
								if($roll_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_silica_gel_qty+$tot_oxygen_absorbers_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_con_rate +$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_silica_gel_rate+$tot_oxygen_absorbers_rate);
					
									$total_amt_val=($invoice_qty['tot']-$roll_price)+$total_amt_roll;//printr($total_amt_roll); 
									if($air_f=='2')
									       $total_amt_roll = $total_amt_roll - $invoice['tran_charges'];
									  
									 
								}
								if($mailer_no == '1')
								{
								  //  printr($invoice['tran_charges'].'&3');
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_silica_gel_qty+$tot_oxygen_absorbers_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_con_rate +$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_silica_gel_rate+$tot_oxygen_absorbers_rate);
									//$total_rate_val=$tot_mailer_rate;
									$total_amt_val=$invoice_qty['tot'];
									if($air_f=='3')
									       $total_amt_mailer = $total_amt_mailer - $invoice['tran_charges'];
								}
								if($sealer_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_silica_gel_qty+$tot_oxygen_absorbers_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_con_rate +$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_silica_gel_rate+$tot_oxygen_absorbers_rate);
									//$total_rate_val=$tot_sealer_rate;
								//	printr($invoice['tran_charges'].'&4');
									$total_amt_val=$invoice_qty['tot'];
									if($air_f=='4')
									       $total_amt_sealer = $total_amt_sealer - $invoice['tran_charges'];
								}
								if($storezo_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_silica_gel_qty+$tot_oxygen_absorbers_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_silica_gel_rate +$tot_oxygen_absorbers_rate );
									//$total_rate_val=$tot_storezo_rate;
								//	printr($invoice['tran_charges'].'&5');
									    $total_amt_val=$invoice_qty['tot'];
									    if($air_f=='5')
									        $total_amt_storezo = $total_amt_storezo - $invoice['tran_charges'];
									
								}
								if($paper_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_silica_gel_qty+$tot_oxygen_absorbers_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_silica_gel_rate+$tot_oxygen_absorbers_rate);
									//$total_rate_val=$tot_paper_rate;
									
								//	printr($invoice['tran_charges'].'&6');
									$total_amt_val=$invoice_qty['tot'];
									if($air_f=='6')
									        $total_amt_paper = $total_amt_paper - $invoice['tran_charges'];
									
								}
								if($con_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_silica_gel_qty+$tot_oxygen_absorbers_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_silica_gel_rate+$tot_oxygen_absorbers_rate);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
								//	printr($invoice['tran_charges'].'&7');
									if($air_f=='7')
									        $total_amt_con = $total_amt_con - $invoice['tran_charges'];
									
								}
								if($gls_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_silica_gel_qty+$tot_oxygen_absorbers_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_silica_gel_rate+$tot_oxygen_absorbers_rate);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
								//	printr($invoice['tran_charges'].'&8');
									if($air_f=='8')
									        $total_amt_gls = $total_amt_gls - $invoice['tran_charges'];
									
								}
								if($chair_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_silica_gel_qty+$tot_gls_qty+$tot_oxygen_absorbers_qty+$tot_val_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_silica_gel_rate+$tot_oxygen_absorbers_rate);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
								//	printr($invoice['tran_charges'].'&8');
									if($air_f=='10')
									        $total_amt_chair = $total_amt_chair - $invoice['tran_charges'];
									
								}
								if($silica_gel_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_chair_qty+$tot_gls_qty+$tot_oxygen_absorbers_qty+$tot_val_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_oxygen_absorbers_rate+$tot_chair_rate);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
								//	printr($invoice['tran_charges'].'&8');
									if($air_f=='11')
									        $total_amt_silica_gel = $total_amt_silica_gel - $invoice['tran_charges'];
									
								}
								if($oxygen_absorbers_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_chair_qty+$tot_gls_qty+$tot_silica_gel_qty+$tot_val_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_silica_gel_rate+$tot_chair_rate);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
								//	printr($invoice['tran_charges'].'&8');
									if($air_f=='11')
									        $total_amt_oxygen_absorbers = $total_amt_oxygen_absorbers - $invoice['tran_charges'];
									
								}
								if($val_no == '1') 
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qtyy+$tot_chair_qty+$tot_silica_gel_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate +$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_oxygen_absorbers_rate+$tot_silica_gel_rate);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
								//	printr($invoice['tran_charges'].'&9');
									if($air_f=='9')
									        $total_amt_valve = $total_amt_valve - $invoice['tran_charges'];
									
								}
								else
								{
								   
								  	if($pouch_name=='POUCHES'){
								  	    
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty+$tot_val_qty+$tot_chair_qty+$tot_silica_gel_qty+$tot_oxygen_absorbers_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate+$tot_con_rate+$tot_gls_rate+$tot_val_rate+$tot_chair_rate+$tot_silica_gel_rate+$tot_oxygen_absorbers_rate );
								    //printr($invoice_qty['tot']);
							            	//$total_rate_val=$invoice_qty['rate'];
								        	//$total_amt_val=$total_qty_val*$invoice_qty['rate'];
								        //	printr($invoice['tran_charges'].'&10');
								         
								        
									 if($roll_no == '1')
								  	        $total_amt_val=$total_amt_val;
								  	     else
								  	        $total_amt_val=$invoice_qty['tot'];
								   
								  	}/*else{
								  	    
								  	    $total_qty_val=$invoice_qty['total_qty'];
								  	    $total_rate_val=$invoice_qty['total_rate'];
								  	     if($roll_no == '1')
								  	        $total_amt_val=$total_amt_val;
								  	     else
								  	        $total_amt_val=$invoice_qty['tot'];
								  	}*/
								  
								}
                
								//$rate_per = number_format((((($total_amt_val-$invoice['tran_charges'])-$invoice['cylinder_charges'])-($total_rate_val*$tot_scoop_qty)-($total_rate_val*$tot_roll_qty)-($total_rate_val*$tot_mailer_qty)-($total_rate_val*$tot_sealer_qty)-($total_rate_val*$tot_storezo_qty)-($total_rate_val*$tot_paper_qty))/$total_qty_val),8);
								
			//$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo))/$total_qty_val),8);
								
								
								
								//$amt = ($total_amt_val-$invoice['tran_charges'])-$invoice['cylinder_charges']-($total_rate_val*$tot_scoop_qty)-($total_rate_val*$tot_roll_qty)-($total_rate_val*$tot_mailer_qty)-($total_rate_val*$tot_sealer_qty)-($total_rate_val*$tot_storezo_qty)-($total_rate_val*$tot_paper_qty);
							
							if(ucwords(decode($invoice['transportation']))=='Sea')
							{
							    
							    
								//printr($total_net_w_pouch.'+'.$rate_per.'+'.$invoice['cylinder_charges']);
							//$amt_new = ($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
							//echo $invoice['cylinder_charges'];
							  
						//	  printr($invoice['cylinder_charges']);
						//	  printr($total_amt_val);
							    $total_amt_val=$total_amt_val+$invoice['cylinder_charges']+$invoice['tool_cost']; // add tool cost told by pinank for invoice no EXP947 24-10-2019
						
						        //trying to minus air freight equally from all price and rate
						        //$tran_charges_divided = $invoice['tran_charges'];
						        /*if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
						                 $invoice['tran_charges'] = $invoice['tran_charges'] - $tran_charges_divided;
						         else
						            $tran_charges_divided = $invoice['tran_charges'];*/
						            
						        /*if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
						            printr($invoice['tran_charges']);*/
							  // printr($invoice['tran_charges']);
								/*$total_amt_val=$total_net_amt_pouch+$invoice['cylinder_charges'];

								$rate_per = number_format((($total_amt_val-($invoice['tran_charges']))/$total_net_w_pouch),8);
							
								
								$amt_new = ($total_amt_val-($invoice['tran_charges']));
							
								$amt = $amt_new;*/
							//	printr($total_amt_val.'------');
								//printr($total_net_w_pouch.'********');
						
						
							//	$amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve));
							
							//	printr($amt);
							
						//		printr($total_amt_roll);
                            						//	printr($total_amt_scoop);
                            						//   printr($total_amt_val);
							
							 
							 
							 
							    if($invoice['invoice_date']<'2019-03-19'){	
								    $amt = ($total_amt_val-($invoice['tran_charges']+$roll_price+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel));
							    }
							    else
							       	$amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_roll+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_silica_gel+$total_amt_oxygen_absorbers));
 
										$rate_per = number_format(( $amt/$total_net_w_pouch),8); 
						
						
						
							//	printr($total_amt_val);	
						
						
							}
							
								
							else
							{
							    
							    //trying to minus air freight equally from all price and rate
						        /*if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
						            $tran_charges_divided = $invoice['tran_charges'] - $tran_charges_divided;
						        else
						            $tran_charges_divided = $invoice['tran_charges'];*/
							    
							    
							   	/*if($invoice['country_destination']=='172' || $invoice['country_destination']=='252')
								{
								       $total_amt_val=$total_amt_val+$invoice['cylinder_charges'];
								}  */
							    
								//$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo))/$total_qty_val),8);
								//$amt = ($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
								
						
					        	//	printr($total_amt_roll);printr($total_amt_val);
						
						        /*$tran_charges_divided = $invoice['tran_charges'];
						        if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
						                 $invoice['tran_charges'] = $invoice['tran_charges'] - $tran_charges_divided;*/
						       
						            
								$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$total_amt_roll+$total_amt_paper+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_oxygen_absorbers+$total_amt_silica_gel))/$total_qty_val),8);
						
						
						
							//	$amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
					           
					       //     printr($total_amt_val);
					           
					            $amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_silica_gel+$total_amt_oxygen_absorbers));

								
						        
						
						
							 if($invoice['invoice_date']<'2018-10-12'){	
							  if($invoice_no!='1899'){
								    if($invoice['country_destination']=='172' || $invoice['country_destination']=='253' )//$invoice['country_destination']=='125' || 
        								{ //printr('dfsdfgdgdgdgg');
        								    
        								    $rate_per = number_format((($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_silica_gel+$total_amt_oxygen_absorbers))/$total_qty_val),8);
        								    $amt = ($total_amt_val-($total_amt_paper+$roll_price+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls+$total_amt_valve+$total_amt_chair+$total_amt_silica_gel+$total_amt_oxygen_absorbers));
        								}
							  }
							 }
								
							}	
							
					
						//	printr($amt);	
							//	printr('('.$total_amt_val.'-('.$invoice['tran_charges'].'+'.$invoice['cylinder_charges'].'+'.$total_amt_paper.'+'.$total_amt_roll.'+'.$total_amt_scoop.'+'.$total_amt_mailer.'+'.$total_amt_sealer.'+'.$total_amt_storezo.'))/'.$total_qty_val.'),8)');

								$air_rate = $amt * $currency['price'];
						
							 /* if(ucwords(decode($invoice['transportation']))=='Sea')
							  { //[kinjal] : replace 1.9% to 1.5% on 3-12-2016 order by pinankbhai //[kinjal] on 20-1-2017 tols by pinank replace to it Licence No 3430002141 Dated 23.04.2012
								$html.='<div><strong>Shipment under the Duty DrawBack Scheme</strong>
								<br>Vide DBK SR No 392303 B @ 1.5% On FOB<br><strong>(Cenvat facility has been availed)<br>This Shipment is taken under the EPGC Licence<br>Licence No 3430002583 Dated 17.12.2014</strong></div><br />';
							  }
							  elseif((ucwords(decode($invoice['transportation']))=='Air') && ($air_rate >= '100000'))
							  { //[kinjal] : replace 2% to 1.5% on 3-12-2016 order by pinankbhai
								//removed this line <br>This Shipment is taken under the EPGC Licence<br>Licence No 3430002142 Dated 23.04.2012 on 3-12-2016 order by pinankbhai
								
								//[kinjal] : commented on 17-12-2016 order by pinankbhai
								//$html.='<div><strong>Shipment under the Duty DrawBack Scheme</strong><br>Vide DBK SR No 3923000099 B @ 1.5% On FOB<br><strong>(Cenvat facility has been availed)</strong></div><br />';
								$html.='<div><strong>Shipment under the Duty DrawBack Scheme</strong><br>Vide DBK SR No 392303 B @ 1.5% On FOB<br><strong>(Cenvat facility has been availed)</strong></div><br />';
							  }*/
					  
					  //$html.='<div><strong>We hereby declare that,we shall claim as admissible <br></strong> under the chapter 3 FTP</div><br />';
					  $html.='</td>';
			if($pouch_name=='POUCHES'){
			    
			        /*  if($air_f=='0'){
			              $amt = $amt - $invoice['tran_charges'];
			          }*/
			          
			    
			        
				    	if(ucwords(decode($invoice['transportation']))=='Sea')
				    	{
           				
           					$html.='<td class="no_border" valign="top"><p align="center"> NET. WT.<br>'.number_format($total_net_w_pouch,3).' KGS <br>'.$total_qty_val.' Nos</p></td>';
           				}
           				else
           					$html.='<td class="no_border" valign="top"><p align="center">'.$total_qty_val.'</p></td>';
                     if($status==1)
    				 { 		
    		          		$html.='<td class="no_border" valign="top"><p align="center">'.$rate_per.'</p></td>
              						<td class="no_border" valign="top"><p align="center">'.number_format($amt,2).'</p>';
    				 }
    				 else 
    				 {
    					 	 //[kinjal] cond added on 16-5-2017 
    				        $html.='<td colspan="2" class="no_border" valign="top" >';
    					 	if($scoop_no == '1' || $roll_no == '1' || $mailer_no == '1' || $sealer_no == '1' || $storezo_no == '1' || $paper_no == '1' || $con_no == '1' || $gls_no == '1' || $val_no =='1' || $chair_no =='1'|| $silica_gel_no =='1'|| $oxygen_absorbers_no =='1')
    					 	{
        						$html.=$pouch_box_no.' BOXES OF '.$pouch_name.'<br> Total GWT : '.$total_gross_w_p.'KGS<br> Total NWT :'.$total_net_w_pouch.'KGS';
    					 	}
    					 	$html.='</td>';
    					 	////[kinjal] END
    				 }
			}else{
			    $html.='<td class="no_border" valign="top"><p align="center"></td><td class="no_border" valign="top"><p align="center"></td><td class="no_border" valign="top"><p align="center"></td>';
			}
				$html.='</tr>';
				
					$insurance='0';$sri_charge=0;
					 //only scoop condition 
				if($pouch_name=='POUCHES'){
						
							 //only scoop condition 
					//if($invoice['extra_tran_charges']!='0' && $invoice['tran_charges']='0.00')$html.='B) &nbsp;'. ucwords(decode($invoice['transportation'])).' Freight Charges';
							
							$html.='<tr> 
										<td colspan="2" class="no_border"></td>
										<td colspan="3" class="no_border">';
										if($status==1) 
										{
												if($invoice['tran_charges']!='0' || $invoice['extra_tran_charges']!='0')
													$html.=' &nbsp;'. ucwords(decode($invoice['transportation'])).' Freight Charges';
												
												if($invoice['cylinder_charges']!='0' && ucwords(decode($invoice['transportation']))!='Sea')	
													$html.='<br>Cylinder Making Charges';
											    if($invoice['invoice_id']=='1809')
											        $html.='<br>Design Charges';
												if(($invoice['country_destination']=='252' ||  $invoice['order_user_id']==2 )&& ucwords(decode($invoice['transportation']))=='Air')
												{
													$html.='<br>Insurance';
												}
												
										}
										$html.='</td>
										<td class="no_border"></td>';
										if($status==2)
											$html.='<td class="no_border" colspan="2"> <p align="center"></p></td>';
										else
										{
											$html.='<td class="no_border"></td>
													<td class="no_border"> 
														<p align="center">';
														
														if($invoice['invoice_id']=="2177"){
														    	$tran_charges_tot=round($invoice['tran_charges'],2)+$amt;
																//printr($tran_charges_tot);
																$insurance=(($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100;
        												    $invoice['tran_charges']=$invoice['tran_charges']-$insurance;
        												}
															if($invoice['tran_charges']!='0'  || $invoice['extra_tran_charges']!='0')
															     $html.=number_format($invoice['tran_charges']+$invoice['extra_tran_charges'],2).'';
															else if ($invoice['extra_tran_charges']!='0') 	
																 $html.=number_format($invoice['extra_tran_charges'],2).'';
																 
															/*if($invoice['extra_tran_charges']!='0' && $invoice['tran_charges']='0.00' )*/	 
																 
															if($invoice['cylinder_charges']!='0' && ucwords(decode($invoice['transportation']))!='Sea')	
															  $html.= '<br />'.number_format($invoice['cylinder_charges'],2);
															if($invoice['invoice_id']=='1809'){
											                    $html.='<br>1200.00';$sri_charge = '1200';}
															$insurance='0';
															 if(($invoice['order_user_id']==2 || $invoice['country_destination']=='252') && ucwords(decode($invoice['transportation']))=='Air')
															  {
																$tran_charges_tot=round($invoice['tran_charges'],2)+$amt;
																//printr($tran_charges_tot);
																$insurance=(($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100;
															//	printr($tran_charges_tot.'*110/100+'.$tran_charges_tot.'0.07/100');
																$html.='<br>'.number_format($insurance,2);
															  }
															  
															  
															 
															 
										 
											 $html.='</p></td>';
										}
										
										 
										 
									$html.='</tr>';
				}          
								//printr($invoice);
								
								
									if($invoice['tool_cost'] !='0.00'  && ucwords(decode($invoice['transportation']))!='Sea')
    								    { 
    								       
        									$html.='<tr>
        												<td colspan="2" class="no_border"></td>
        												<td colspan="3" class="no_border"><div><strong> Set up Cost </strong>
        												</div></td>'; 
        											
        													$html.='<td class="no_border" valign="top"><p align="center"></p></td>';
        													
        													$html.='<td class="no_border" valign="top"><p align="center"></p></td>';
        									if($status==1)
        									{
        									    $html.='<td class="no_border" valign="top"><p align="center">'.number_format($invoice['tool_cost'],2).'</p></td>';
        									}
        									else
        										$html.='<td class="no_border" ></td>';
        										//colspan="2"
        									$html.='</tr>';
    							    	}
								if($scoop_no == '1')
								{
								    //	if($invoice['invoice_id']=='899')
								    	if($invoice['country_destination']=='238')
                                			$text = " Plastic Stoppers,Lids,Cap & Other Closures";
                                		    else
                                		  $text = " Plastic Scoop "; 
								    
								if($pouch_name!='POUCHES'){
								       $a_amt=  $total_amt_scoop;
								      $scoop_series='A';
								   }else{
								      $a_amt= $total_amt_scoop;
								       $scoop_series=$scoop_series;
								   } 
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><strong>'.$scoop_series.') '.$text.' </strong><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39235090<br>
</td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_scoop .'<br>'.$tot_scoop_qty.' Nos </p></td>';
												else
													$html.='<td class="no_border" valign="top"><p align="center">'.$tot_scoop_qty.'</p></td>';
									if($status==1)
									{	
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_scoop/$total_net_w_scoop,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_scoop/$tot_scoop_qty,8).'</p></td>';
											}
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($a_amt,2).'</p></td>';
										//	$html.='<td class="no_border" valign="top"><p align="center">'.((($tot_qty_scoop['tot_amt']-$invoice['tran_charges'])-$invoice['cylinder_charges'])/$tot_qty_scoop['total']).'</p></td>
										//	<td class="no_border" valign="top"><p align="center">'. round(($tot_qty_scoop['tot_amt']-$invoice['tran_charges'])-$invoice['cylinder_charges']).'</p></td>';
											
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$scoop_box_no.' BOXES OF '.$scoop_name.'<br> Total GWT : '.$total_gross_w_scoop .'KGS<br> Total NWT :'.$total_net_w_scoop.'KGS</td>';
										
									$html.='</tr>';
								}
								//change for roll  by sonu 30-05-2018 
								if($roll_no == '1')
								{ //Printed Polyester Rolls
								
								 //	printr($total_amt_val);
								 		
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$roll_series.') Printed or Unprinted Flexible Packaging Material of Rolls</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>Printed Polyester Rolls : 39201012
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>HS CODE : 39201012<br>
</div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_roll .' KGS<br>'.$tot_roll_qty.' Rolls </p></td>';
												else
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_roll .' KGS<br>'.$tot_roll_qty.' Rolls </p></td>';
									if($status==1)
									{	
										/*	if(ucwords(decode($invoice['transportation']))=='Sea')
											{*/
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format(($total_amt_roll)/$total_net_w_roll,8).'</p></td>';
										/*	}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_roll+$roll_price/$total_net_w_roll,8).'</p></td>';
											}*/
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_roll,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$roll_box_no.' BOXES OF '.$roll_name.'<br> Total GWT : '.$total_gross_w_roll.'KGS 
												<br> Total NWT :'.$total_net_w_roll.'KGS</td>';
										
									$html.='</tr>';
								}
								if($mailer_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$mailer_series.') Mailer Bag</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39232990<br>
</div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_m.'<br>'.$tot_mailer_qty.' Nos </p></td>';
												else
													$html.='<td class="no_border" valign="top"><p align="center">'.$tot_mailer_qty.' </p></td>';
									if($status==1)
									{	
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_mailer/$total_net_w_m,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_mailer/$tot_mailer_qty,8).'</p></td>';
											}
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_mailer,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$mailer_box_no.' BOXES OF '.$mailer_name.'<br> Total GWT : '.$total_gross_w_m.'KGS 
												<br> Total NWT :'.$total_net_w_m.'KGS</td>';
										
									$html.='</tr>';
								}
								if($sealer_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$sealer_series.') Sealer Machine</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 84223000<br>
</div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_s .'<br>'.$tot_sealer_qty.' Nos </p></td>';
												else
												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_sealer_qty.'</p></td>';
									if($status==1)
									{
										
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_sealer/$total_net_w_s,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_sealer/$tot_sealer_qty,8).'</p></td>';
											}	
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_sealer,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$sealer_name.' BOXES OF '.$sealer_name.'<br> Total GWT : '.$total_gross_w_s.'KGS 
												<br> Total NWT :'.$total_net_w_s.'KGS</td>';
										
									$html.='</tr>';
								}
								if($storezo_no == '1')
								{
								if($storezo_series=='A'){
								      $storezo_series='C';
								   }else{
								       $storezo_series=$storezo_series; 
								   } 
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$storezo_series.') Storezo High barrier Bag</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39232100<br><br>';
                                                       // coomented by kinjal (told by ronak)
                                                       //if(ucwords(decode($invoice['transportation']))!='Air')
                                                           // $html.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 3923<br>';
                                                $html.='</div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_str .'<br>'.$tot_storezo_qty.' Nos </p></td>';
												else
													$html.='<td class="no_border" valign="top"><p align="center">'.$tot_storezo_qty.'</p></td>';
									if($status==1)
									{	
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_storezo/$total_net_w_str,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_storezo/$tot_storezo_qty,8).'</p></td>';
											}	
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_storezo,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$storezo_box_no.' BOXES OF '.$storezo_name.'<br> Total GWT : '.$total_gross_w_str.'KGS 
												<br> Total NWT :'.$total_net_w_str.'KGS</td>';
										
									$html.='</tr>';
								}
								if($paper_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$paper_series.') Paper Board Boxes</strong><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 48191010<br>
</div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_p .'<br>'.$tot_paper_qty.' Nos </p></td>';
												else
												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_paper_qty.'</p></td>';
									if($status==1)
									{	
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_paper/$total_net_w_p,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_paper/$tot_paper_qty,8).'</p></td>';
											}	
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_paper,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$paper_box_no.' BOXES OF '.$paper_box_name.'<br> Total GWT : '.$total_gross_w_p.'KGS 
												<br> Total NWT :'.$total_net_w_p.'KGS</td>';
										
									$html.='</tr>';
								}
								if($con_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$con_series.') Plastic Disposable Lid / Container</strong><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39241090<br>
</div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_con .'<br>'.$tot_con_qty.' Nos </p></td>';
												else
												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_con_qty.'</p></td>';
									if($status==1)
									{	
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_con/$total_net_w_con,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_con/$tot_con_qty,8).'</p></td>';
											}	
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_con,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$con_box_no.' BOXES OF '.$con_box_name.'<br> Total GWT : '.$total_gross_w_con.'KGS 
												<br> Total NWT :'.$total_net_w_con.'KGS</td>';
										
									$html.='</tr>';
								}
								if($gls_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$con_series.') Plastic Glasses</strong><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39241090<br>
</div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_gls .'<br>'.$tot_gls_qty.' Nos </p></td>';
												else
												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_gls_qty.'</p></td>';
									if($status==1)
									{	
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$total_net_w_gls,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls/$tot_gls_qty,8).'</p></td>';
											}	
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_gls,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$gls_box_no.' BOXES OF '.$gls_box_name.'<br> Total GWT : '.$total_gross_w_gls.'KGS 
												<br> Total NWT :'.$total_net_w_gls.'KGS</td>';
										
									$html.='</tr>';
								}
								if($chair_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$chair_series.') H/B CHAIR</strong><br>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 94036000<br></div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_chair .'<br>'.$tot_chair_qty.' Nos </p></td>';
												else
												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_chair_qty.'</p></td>';
									
									  //printr($total_net_w_chair); 
								//	  printr($total_amt_chair); 
									if($status==1)
									{	   
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_chair/$total_net_w_chair,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_chair/$tot_chair_qty,8).'</p></td>';
											}	
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_chair,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$chair_box_no.' BOXES OF '.$chair_box_name.'<br> Total GWT : '.$total_gross_w_chair.'KGS 
												<br> Total NWT :'.$total_net_w_chair.'KGS</td>';
										
									$html.='</tr>';
								}
								if($silica_gel_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$silica_gel_series.') Silica Gel / Moisture Absorbers</strong><br>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 38249025<br></div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_silica_gel .'<br>'.$tot_silica_gel_qty.' Nos </p></td>';
												else
												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_silica_gel_qty.'</p></td>';
									
									  //printr($total_net_w_chair); 
								//	  printr($total_amt_chair); 
									if($status==1)
									{	   
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_silica_gel/$total_net_w_silica_gel,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_silica_gel/$tot_silica_gel_qty,8).'</p></td>';
											}	
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_silica_gel,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$silica_gel_box_no.' BOXES OF '.$silica_gel_box_name.'<br> Total GWT : '.$total_gross_w_silica_gel.'KGS 
												<br> Total NWT :'.$total_net_w_silica_gel.'KGS</td>';
										
									$html.='</tr>';
								}	
							if($oxygen_absorbers_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$oxygen_absorbers_series.') Oxygen Absorbers</strong><br>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 38249990<br></div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_oxygen_absorbers .'<br>'.$tot_oxygen_absorbers_qty.' Nos </p></td>';
												else
												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_oxygen_absorbers_qty.'</p></td>';
									
									  //printr($total_net_w_chair); 
								//	  printr($total_amt_chair); 
									if($status==1)
									{	   
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_oxygen_absorbers/$total_net_w_oxygen_absorbers,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_oxygen_absorbers/$tot_oxygen_absorbers_qty,8).'</p></td>';
											}	
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_oxygen_absorbers,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$oxygen_absorbers_box_no.' BOXES OF '.$oxygen_absorbers_box_name.'<br> Total GWT : '.$total_gross_w_oxygen_absorbers.'KGS 
												<br> Total NWT :'.$total_net_w_oxygen_absorbers.'KGS</td>';
										
									$html.='</tr>';
								}
								if($val_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$val_series.') Plastic Cap </strong><br>
												   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  V-105 <br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39232100<br>
                                                </div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_val .'<br>'.$tot_val_qty.' Nos </p></td>';
												else
												    $html.='<td class="no_border" valign="top"><p align="center">'.$tot_val_qty.'</p></td>';
									if($status==1)
									{	
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_valve/$total_net_w_val,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_valve/$tot_val_qty,8).'</p></td>';
											}	
											$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_valve,2).'</p></td>';
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$val_box_no.' BOXES OF '.$val_box_name.'<br> Total GWT : '.$total_gross_w_val.'KGS 
												<br> Total NWT :'.$total_net_w_val.'KGS</td>';
										
									$html.='</tr>';
								}
								 
								 //only scoop condition 
								 if($pouch_name!='POUCHES'){
									$html.='<tr> 
										<td colspan="2" class="no_border"></td>
										<td colspan="3" class="no_border">';
										if($status==1)
										{
													if($invoice['tran_charges']!='0' || $invoice['extra_tran_charges']!='0')
													$html.='<b> &nbsp;'. ucwords(decode($invoice['transportation'])).' Freight Charges</b>';
												if($invoice['cylinder_charges']!='0' && ucwords(decode($invoice['transportation']))!='Sea')	
													$html.='<br>Cylinder Making Charges';
											
												if(( $invoice['order_user_id']==2 || $invoice['country_destination']=='252') && ucwords(decode($invoice['transportation']))=='Air')
												{
													$html.='<br>Insurance
';
												} 
										}
										$html.='</td>  
										<td class="no_border"></td>';
										if($status==2)
											$html.='<td class="no_border" colspan="2"> <p align="center"></p></td>';
										else
										{
											$html.='<td class="no_border"></td>
													<td class="no_border"> 
														<p align="center">';
															if($invoice['tran_charges']!='0'  || $invoice['extra_tran_charges']!='0')
															     $html.=number_format($invoice['tran_charges']+$invoice['extra_tran_charges'],2).'';
															else if ($invoice['extra_tran_charges']!='0') 	
																 $html.=number_format($invoice['extra_tran_charges'],2).'';
																 
																
															if($invoice['cylinder_charges']!='0' && ucwords(decode($invoice['transportation']))!='Sea')	
															  $html.= '<br />'.number_format($invoice['cylinder_charges'],2);
															$insurance='0';
															 if((  $invoice['order_user_id']==2 || $invoice['country_destination']=='252') && ucwords(decode($invoice['transportation']))=='Air')
															  {
																$tran_charges_tot=round($invoice['tran_charges'],2)+$amt;
															//	printr($invoice['tran_charges'].'+'.$amt);
																$insurance=(($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100;
															//	printr($tran_charges_tot.'*110/100+'.$tran_charges_tot.'0.07/100');
																$html.='<br>'.number_format($insurance,2);
															  }
															 
															 
										 
											 $html.='</p></td>';
										}
										
										 
										 
									$html.='</tr>';
		 }
						
							//if($status==1)
							//{
									$html.='<tr>
												<td colspan="2" class="no_border">';
												$rfid_no='';
													if(decode($invoice['transportation'])=='sea'){
													    if($invoice['rfid_no']!='')
													        $rfid_no='<br> RFID E Seal No :'.$invoice['rfid_no'];
														$html.='<b>Container No :'.$invoice['container_no'].'<br>Seal No : '.$invoice['seal_no'].' '.$rfid_no.'</b>';
													}
										$html.='</td>';
							 			$html.='<td colspan="3" class="no_border"><div>';
										
										/* if($status==1)
										 {
											
												 
												if($invoice['tran_charges']!='0')
													$html.='<strong>B)&nbsp;'. ucwords(decode($invoice['transportation'])).' Freight Charges</strong>';
												if($invoice['cylinder_charges']!='0')	
													$html.='<br /><strong>C)Cylinder Making Charges</strong>';
											
												if($invoice['country_destination']=='252' && ucwords(decode($invoice['transportation']))=='Air')
												{
													$html.='<br>
<strong>D)Insurance</strong></br>';
												} 
										}*/
										
							 			$html.= $invoice['tran_desc'].'';
							 			if($invoice['curr_id']=='3' || $invoice['country_destination']=='252' || $invoice['country_destination']=='195' || $invoice['country_destination']=='96' || $invoice['country_destination']=='90' || $invoice['country_destination']=='230' || $invoice['country_destination']=='6' || $invoice['country_destination']=='15' || $invoice['country_destination']=='22' || $invoice['country_destination']=='64' || $invoice['country_destination']=='76' || $invoice['country_destination']=='82' || $invoice['country_destination']=='83' || $invoice['country_destination']=='93' || $invoice['country_destination']=='116'|| $invoice['country_destination']=='119' || $invoice['country_destination']=='132'|| $invoice['country_destination']=='139' || $invoice['country_destination']=='140'|| $invoice['country_destination']=='148'|| $invoice['country_destination']=='159'|| $invoice['country_destination']=='161'|| $invoice['country_destination']=='170'|| $invoice['country_destination']=='192'|| $invoice['country_destination']=='207'|| $invoice['country_destination']=='12'|| $invoice['country_destination']=='21'|| $invoice['country_destination']=='37'|| $invoice['country_destination']=='60'|| $invoice['country_destination']=='64'|| $invoice['country_destination']=='66'|| $invoice['country_destination']=='89'|| $invoice['country_destination']=='109'|| $invoice['country_destination']=='110'|| $invoice['country_destination']=='124'|| $invoice['country_destination']=='138'|| $invoice['country_destination']=='170'|| $invoice['country_destination']=='179'|| $invoice['country_destination']=='191'|| $invoice['country_destination']=='196'|| $invoice['country_destination']=='211'|| $invoice['country_destination']=='231'|| $invoice['country_destination']=='245'|| $invoice['country_destination']=='250'|| $invoice['country_destination']=='217'|| $invoice['country_destination']=='218'|| $invoice['country_destination']=='224')
										{
											$html.='<div><strong>" Statement Of Origin "</strong><br/>
The exporter <strong>"INREX3403000290EC005"</strong> of the products covered by this document declares that, except where otherwise clearly indicated, these <br/>products are of Indian preferential origin according to rules of origin of the Generalized System of Preferences of the European Union and that the origin criterion met is P.</div>';
							 			}
							 
										 $html.='<div><strong>We Intend to claim rewards under merchandise Export <br></strong> From India Scheame (MEIS)</div>';
										 
										 $html.='<div><strong>We hereby declare that,we shall claim as admissible <br></strong> under the chapter 3 FTP</div>';
							// printr($totgross_weight.'=='.$total_pallet_weight);
										  if(ucwords(decode($invoice['transportation']))=='Sea' || $invoice['show_pallet']=='1')
										  {
											//$t_gross_w = number_format($totgross_weight,3);
											$tgross = number_format($totgross_weight+$total_pallet_weight,3);
											//printr($totgross_weight.'=='.$total_pallet_weight);
											$html.=' Gross Weight :  '. number_format($totgross_weight,3).' '.$measurement['measurement'].' (Without Pallets )<br>Net Weight:&nbsp;'.number_format($box_detail['total_net_weight']+$child_net,3).'&nbsp;'.$measurement['measurement'].'<br>Tare Weight Of (Pallets): '.$total_pallet_weight.' Kgs<br><b>Total Gross Weight : '.$tgross.' Kgs</b><br>Total '.$total_pallet.' Wooden Pallets<br>Contaning  '.$total_pallet_box.' Boxes  ';
											if($loose_boxes>0)
												$html.=' , & '.$loose_boxes.' Loose Boxes';
										}
										else
										{
											$html.=' Total Gross Weight: '. number_format($totgross_weight,3).' '.$measurement['measurement'].'';
										  	$html.='<br />Total Net Weight:&nbsp;'.number_format($box_detail['total_net_weight']+$child_net,3).'&nbsp;'.$measurement['measurement'].'<br />Total '. $total_no_of_box['tot'].' Boxes'.'<br />
										  '. $fixdata['googs_description'].'';
										 }
								
								 

								 
									$html.='</td>
											 <td class="no_border"></td>';
											 if($status==1)
											 {
												$html.=' <td class="no_border"></td>';
												
												$html.=' <td class="no_border" valign="top"><p align="center"></p></td>';
												/*if($status==1)
												{
													if($invoice['tran_charges']!='0')
													 $html.=round($invoice['tran_charges'],2).'<br>';
														
													if($invoice['cylinder_charges']!='0')	
													  $html.= $invoice['cylinder_charges'];
													$insurance='0';
												}
											  if($invoice['country_destination']=='252' && ucwords(decode($invoice['transportation']))=='Air')
											  {
												$tran_charges_tot=round($invoice['tran_charges'],2)+$amt;
												$insurance=(($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100;
												
												$html.='<br>'.$insurance;
											  }*/
											 }
											 else
											 {
												$html.=' <td class="no_border" colspan="2">'.nl2br($invoice['remarks']).'</td>';
											 }
						  
					//}
							
							
							/*if($status==1)
							{
									$html.='<tr>
												<td colspan="2" class="no_border">';
													if(decode($invoice['transportation'])=='sea')
														$html.='Container No :'.$invoice['container_no'].'<br>Seal No : '.$invoice['seal_no'];
												$html.='</td>';
							 
									 if($status==1)
									 {
										$html.='<td colspan="3" class="no_border"><div>';
											
											if($invoice['tran_charges']!='0')
												$html.='<strong>B)&nbsp;'. ucwords(decode($invoice['transportation'])).' Freight Charges</strong>';
											if($invoice['cylinder_charges']!='0')	
												$html.='<br /><strong>C)Cylinder Making Charges</strong>';
										
											if($invoice['country_destination']=='252' && ucwords(decode($invoice['transportation']))=='Air')
											{
												$html.='</br><strong>D)Insurance</strong></br>';
											} 
									}
							 $html.= $invoice['tran_desc'].'<br />';
							 
							 $html.='<div><strong>We Intend to claim rewards under merchandise Export <br></strong> From India Scheame (MEIS)</div><br />';
							 
							 $html.='<div><strong>We hereby declare that,we shall claim as admissible <br></strong> under the chapter 3 FTP</div><br />';
							 
								  if(ucwords(decode($invoice['transportation']))=='Sea')
								  {
									$html.=' Gross Weight :  '. number_format($totgross_weight,3).' '.$measurement['measurement'].' (Without Pallets )<br>Net Weight:&nbsp;'.number_format($box_detail['total_net_weight'],3).'&nbsp;'.$measurement['measurement'].'<br>Tare Weight Of (Pallets): '.$total_pallet_weight.' Kgs<br><b>Total Gross Weight : '. (number_format($totgross_weight,3)+$total_pallet_weight).' Kgs</b><br>Total '.$total_pallet.' Wooden Pallets<br>Contaning  '.$total_pallet_box.' Boxes  ';
									if($loose_boxes>0)
										$html.=' , & '.$loose_boxes.' Loose Boxes';
								}
								else
								{
									$html.=' Total Gross Weight: '. number_format($totgross_weight,3).' '.$measurement['measurement'].'';
								  $html.='<br />Total Net Weight:&nbsp;'.number_format($box_detail['total_net_weight'],3).'&nbsp;'.$measurement['measurement'].'<br />'. $total_no_of_box['tot'].' Boxes'.'<br />
								  '. $fixdata['googs_description'].'';
								 }
								
								 
								 
							$html.='</td>
							 <td class="no_border"></td>
							 <td class="no_border"></td>
							 <td class="no_border" valign="top"><p align="center">';
								if($invoice['tran_charges']!='0')
								 $html.=round($invoice['tran_charges'],2).'<br>';
								 
								if($invoice['cylinder_charges']!='0')	
								  $html.= $invoice['cylinder_charges'];
								$insurance='0';
							  if($invoice['country_destination']=='252' && ucwords(decode($invoice['transportation']))=='Air')
							  {
								$tran_charges_tot=round($invoice['tran_charges'],2)+$amt;
								$insurance=(($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100;
								$html.='<br>'.$insurance;
							  }
							  $html.='</p></td>';
						  
					}
					else
					 { //echo 'jiii';
						  $html.='<td colspan="2" class="no_border" valign="top">'.nl2br($invoice['remarks']).'</td>';
                     }*/
         $html.='</tr>';
		
		 //echo $total_amt_val.'-'.$tot_qty_scoop['tot_amt'];
		// if($flag==1)
			//$final_amount=$total_amt_val+$tot_qty_scoop['tot_amt'];
		//else


//printr($total_amt_val);
		//$invoice['cylinder_charges']
		
			if($invoice['invoice_id']=="2177"){
														    $insurance=0;
        												}
        												
       //printr($total_amt_val);
		$final_amount=$total_amt_val+$insurance+$sri_charge;
		$excies = '';
		$tax ='';
			
		$excies = $invoice['excies'];
		$tax = $invoice['tax'];			
		$excies_price = ($final_amount*$excies)/100;
		$tax_price = (($final_amount+$excies_price)*$tax)/100;
		$Total_price=($final_amount+$tax_price+$excies_price);
		
		if($currency['currency_code'] == 'INR') {
			$Total_price = round($Total_price);
		}
	}
	else
	{
		$html.='<tr style="vertical-align: top;">
					 <th colspan="2">Marks & No./Container No.</th>
					 <th colspan="2">No. & Kind pf Packages</th>
					 <th>Description</th>
					 <th>Quntity In Kgs.<br />'. $currency['currency_code'].'</th>
					 <th>Rate Per Kgs.</th>
					 <th>Amount <br />'. $currency['currency_code'].'</th>			
				</tr>'; 
		$sample_products=$this->getProductdeatilsForSample($invoice_no);
		//printr($sample_products);
		$s_no=1;$sample_Total_price = 0;
		$firsttime = true;
		
		
			$f_p=$f_scoop=$f_roll=$f_mailer=$f_sealer=$f_storezo=$f_box=true;
			$alldetails=$this->getProductdeatils($invoice_no);
			//printr($alldetails);
			foreach($alldetails as $detail)
											{
												if($detail['product_id']!='11' && $detail['product_id']!='6' && $detail['product_id']!='10' && $detail['product_id']!='23' && $detail['product_id']!='18' && $detail['product_id']!='34' && $detail['product_id']!='47' && $detail['product_id']!='48'  && $detail['product_id']!='63'&& $detail['product_id']!='72')
												{
													$charge = $invoice['tran_charges']+$invoice['cylinder_charges'];										
													$boxDetail = $this->getIngenBox($invoice_no,$n=2,$charge);
													//printr($boxDetail);
													//$boxDetail['product_id']='6';
													if($f_p==true)
													{
														$pouch_detail['Pouch Detail']=$boxDetail;
														$f_p=false;
													}
													
												}
												else if($detail['product_id']=='11')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']='6';
													if($f_scoop==true)
													{
														$pouch_detail['scoop']=$boxDetail;
														$f_scoop=false;
													}
												}
												else if($detail['product_id']=='6')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);

												//	printr($boxDetail);
													$boxDetail['product_id']=$detail['product_id'];

													if($f_roll==true)
													{
														$pouch_detail['roll']=$boxDetail;
														$f_roll=false;
													}
												}
												else if($detail['product_id']=='10')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_mailer==true)
													{
														$pouch_detail['Mailer Bag']=$boxDetail;
														$f_mailer=false;
													} 
												}
												else if($detail['product_id']=='23')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_sealer==true)
													{
														$pouch_detail['Sealer Machine']=$boxDetail;
														$f_sealer=false;
													}
												}
												else if($detail['product_id']=='18')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_storezo==true)
													{
														$pouch_detail['Storezo']=$boxDetail;
														$f_storezo=false;
													}
												}
												else if($detail['product_id']=='34')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_box==true)
													{
														$pouch_detail['Paper Box']=$boxDetail;
														$f_box=false;
													}
												}
												else if($detail['product_id']=='47')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_con==true)
													{
														$pouch_detail['Plastic Disposable Lid / Container']=$boxDetail;
														$f_con=false;
													}
												}
												else if($detail['product_id']=='48')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_gls==true)
													{
														$pouch_detail['Plastic Glasses']=$boxDetail;
														$f_gls=false;
													}
												}
												else if($detail['product_id']=='72')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_chair==true)
													{
														$pouch_detail['Chair']=$boxDetail;
														$f_chair=false;
													}
												}
												else if($detail['product_id']=='38')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_silica_gel==true)
													{
														$pouch_detail['Silica gel']=$boxDetail;
														$f_silica_gel=false;
													}
												}else if($detail['product_id']=='37')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_oxygen_absorbers==true)
													{
														$pouch_detail['oxygen absorbers']=$boxDetail;
														$f_oxygen_absorbers=false;
													}
												}
												else if($detail['product_id']=='63')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_val==true)
													{
														$pouch_detail['Plastic Cap']=$boxDetail;
														$f_val=false;
													}
												}
											}
											//printr($pouch_detail);
											if(isset($pouch_detail))
											{
												foreach($pouch_detail as $key=>$data)
												{
													$id = str_replace(',', 'A', $data['group_id']);
													$id_c = "'".$id."'";
													$qty = isset($data['qty_in_kgs'])?$data['qty_in_kgs']:'';
													$sample_rate = isset($data['rate_in_kgs'])?$data['rate_in_kgs']:'';
													
													

													$html.='<tr>
																	<td colspan="2" class="no_border"><p align="center">'.$data['total_box'].' Boxes</td>
																	<td colspan="2" class="no_border"><p align="center">'.$data['total_box'].' Boxes</td>
																	<td class="no_border">'.$key.'</td>';
																	if($invoice['done_status']=='1')
																	{
																		$html.='
																		<td class="no_border"><p align="center">'.$qty.'</p></td>
																		<td class="no_border"><p align="right">'.$sample_rate.'</p></td>';
																	}
																	else
																	{
																		$html.='
																		<td class="no_border"><p align="center"><input type="" name="qty_'.$id.'" id="qty_'.$id.'" value="'.$qty.'" onchange="change_qty_per_kg('.$id_c.',0)"></p></td>
																		<td class="no_border"><p align="right"><input type="" name="sample_rate_'.$id.'" id="sample_rate_'.$id.'" value="'.$sample_rate.'" onchange="change_qty_per_kg('.$id_c.',1)"></p></td>';
																	}
																
																if($data['product_id']!='6'){
																	$html.='<td class="no_border"><p align="right">'.$this->numberFormate(($data['qty_in_kgs']*$data['rate_in_kgs']),"2").'</p></td>';
																}
																else{
																	$html.='<td class="no_border"><p align="right">'.$this->numberFormate(($data['total_amt']),"2").'</p></td>';
																}	
																
									$html.='</tr>';
										if($data['product_id']!='6'){
											$sample_Total_price=$sample_Total_price+($data['qty_in_kgs']*$data['rate_in_kgs']);
										}else{
											$sample_Total_price=$sample_Total_price+($data['total_amt']);
										}

										

												}
											}
		/*foreach($sample_products as $sample)
		{
			$qty = isset($sample['qty_in_kgs'])?$sample['qty_in_kgs']:'';
			$sample_rate = isset($sample['rate_in_kgs'])?$sample['rate_in_kgs']:'';
			$html.='<tr>';
				if($firsttime)
				{
					$html.='<td colspan="2" class="no_border"><p align="center">'.$total_no_of_box['tot'].' Boxes</p></td>
							<td colspan="2" class="no_border"><p align="center">'.$total_no_of_box['tot'].' Boxes</p></td>';
					$firsttime = false;
				}
				else
				{
					$html.='<td colspan="2" class="no_border"></td>
						<td colspan="2" class="no_border"></td>';
				}
				$html.='<td class="no_border">'.$sample['product_name'].'</td>';
					if($invoice['done_status']=='1')
					{
						$html.='
						<td class="no_border"><p align="center">'.$qty.'</p></td>
						<td class="no_border"><p align="right">'.$sample_rate.'</p></td>';
					}
					else
					{
						$html.='
						<td class="no_border"><p align="center"><input type="" name="qty_'.$sample['invoice_color_id'].'" id="qty_'.$sample['invoice_color_id'].'" value="'.$qty.'" onchange="change_qty_per_kg('.$sample['invoice_color_id'].',0)"></p></td>
						<td class="no_border"><p align="right"><input type="" name="sample_rate_'.$sample['invoice_color_id'].'" id="sample_rate_'.$sample['invoice_color_id'].'" value="'.$sample_rate.'" onchange="change_qty_per_kg('.$sample['invoice_color_id'].',1)"></p></td>';
					}
					$html.='<td class="no_border"><p align="right">'.$this->numberFormate(($sample['qty_in_kgs']*$sample['rate_in_kgs']),"2").'</p></td>
					</tr>';
			$s_no++;
			$sample_Total_price=$sample_Total_price+($sample['qty_in_kgs']*$sample['rate_in_kgs']);
		}*/
		$final_amount = $Total_price = $sample_Total_price;	
		
	}

	if(ucwords(decode($invoice['transportation']))=='Air' || ucwords(decode($invoice['transportation']))=='Road' )
		$Total_price = $final_amount = $final_amount + $invoice['cylinder_charges'];

		  //printr($Total_price);
if($invoice['invoice_date']<'2018-10-12'){	
if($invoice_no!='1899'){
	if(($invoice['country_destination']=='172' || $invoice['country_destination']=='253') && ucwords(decode($invoice['transportation']))=='Air')//$invoice['country_destination']=='125' || 
				$Total_price = $final_amount = $final_amount + round($invoice['tran_charges'],2);												
}}
	
         $html .='<tr>
					<td  colspan="2"></td>';						
                    if($status==1){
                    						if($invoice['country_destination'] ==111){
                    					$html.='<td colspan="4"><div align="right"><strong>Grand Total</strong></div></td>';
                    						} else {
                    					$html.='<td colspan="4"><div align="right"><strong>Total</strong></div></td>';
                    						}
                    						
                    				
                    					if(ucwords(decode($invoice['transportation']))=='Air' || ucwords(decode($invoice['transportation']))=='Road' ){
                    					$html .='<td><div align="center">'. $currency['currency_code'].'</div></td>
                    					
                    					<td><p align="center">'; $html.=$this->numberFormate(($final_amount+$invoice['extra_tran_charges']+$invoice['tool_cost']),"2").'</p></td></tr>';
                    					$Total_price=$final_amount+$invoice['extra_tran_charges']+$invoice['tool_cost'];
                    					
                    					}else{
                    					 $html .='<td><div align="center">'. $currency['currency_code'].'</div></td>
                    					<td><p align="center">'; $html.=$this->numberFormate(($final_amount+$invoice['extra_tran_charges']),"2").'</p></td></tr>';
                    					$Total_price=$final_amount+$invoice['extra_tran_charges'];
                    				
                    					}
                    					
                    		if($invoice['country_destination'] ==111 ) {
                        		$html .='<tr>
                        					<td colspan="2"></td>
                        					<td colspan="4"><div align="right"><strong>Excies '.$excies.' %</strong>';
                        					if($excies ==0)
                        						$html .='<br><span>( '.str_replace("H Form,","",$invoice['tax_form']).' is given )</span>';
                        					
                        		$html .='</div></td>
                        					<td></td>
                        					<td><p align="center">'.$excies_price.'</p></td>
                        				</tr>';
                        		$html .='<tr>
                        					<td colspan="2"></td>
                        					<td colspan="4"><div align="right"><strong>Tax '.$tax.' %</strong>';
                        					if($tax ==0)
                        					$html .='<br><span>( H Form is given)</span>';
                        					else
                        					$html .='<br><span>( '.str_replace('_', ' ', $taxation).' )</span></div></td>';
                        		$html .='</div></td>
                        				<td></td>
                        					<td><p align="center">'.$tax_price.'</p></td>
                        				</tr>';
                        		$html .='<tr>
                        					<td colspan="2"></td>
                        					<td colspan="4"><div align="right"><strong>Total </strong>';
                        		$html .='</div></td>
                        					<td></td>
                        					<td><p align="center">'.$Total_price.'</p></td>
                        				</tr>';	
                    		}
                             $html .='<tr>';
                             				 //$number=$this->convert_number (number_format($Total_price,1));
                    						 $number=$this->convert_number ($Total_price);
                         					$html.='<td colspan="6"><strong>Amount Chargable :</strong>&nbsp;<u>'. $number.'</u>&nbsp;.&nbsp;('. $currency['currency_code'].') in words</td><td colspan="2">';
                    						if($invoice['country_destination']=='238')
                    					    	$html.='<b>FOB VALUE = '.number_format((($final_amount)-$invoice['tran_charges']),2).' USD</b>';
                    						$html.='</td>
                             	</tr>';
                }else {
               			 $html.='<td colspan="3"><div align="right"><strong>Total:</strong></div></td>
               			 <td><p align="center">'.$invoice_qty['total_qty'].'</p></td>
                		<td colspan="2"><strong>Total Net Weight:</strong>&nbsp;'.number_format($box_detail['total_net_weight']+$child_net,3).'&nbsp;'.$measurement['measurement'].'</td></tr>';
						//round($invoice['net_weight'])
                 }
         
         $html.='<tr>
         			<td colspan="5" rowspan="1" valign="top"><p><strong><u>Declaration</u></strong></p>
           		 	'. $fixdata['declaration'].' <br />';
           		 	
           		  
					if($invoice['order_type']!='sample') 
				    {
				        if(decode($invoice['transportation'])=='air' || decode($invoice['transportation'])=='road'){
				            
				           
    				        if($invoice['igst_status']=='1'){
    				             $html.='<span><strong>'. $fixdata['sea_notes'].'</strong></span>';
    				        }else{
    				             $html.='<span><strong>'. $fixdata['notes'].'</strong></span>';
    				        }
				        }else{
				            $html.='<span><strong>'. $fixdata['sea_notes'].'</strong></span>';
				        }
				    }
				    
						
					
					$html.='</td>
           			<td colspan="3" rowspan="1" valign="top"><strong>Signature Date : '.date("d-m-Y",strtotime($invoice['invoice_date'])).'</strong>
						<p><strong>For : SWISS PAC PVT . LTD.</strong></p>
             			
            			<br><br>
						<p><strong>Authorized Person</strong></p>
					</td>
         		</tr>
		   </table>
			 <div class="form-group">
			 <div class="col-lg-9 col-lg-offset-3">';
			 $html.='</div>
			 </div>
		 </div>
		</div>
	  </div>';
	  
	  $sql = "UPDATE " . DB_PREFIX . "invoice_test SET invoice_total_amount = '" .$Total_price. "'WHERE invoice_id ='".$invoice_no."'";
	  $data=$this->query($sql);
	  
		return $html;
	}
	
	public function invoiceArrayForCSV($invoice_nos)
	{	
	    $i=0;
		foreach($invoice_nos as $invoice_no)
		{
			//printr($invoice_no);
		
			$invoice=$this->getInvoiceNetData($invoice_no);
			$currency=$this->getCurrencyName($invoice['curr_id']);	
			$alldetails=$this->getInvoiceProduct($invoice_no);
			
		//	printr($alldetails);die;
			
    		if($invoice['invoice_date']>'2019-06-19'){
          	$fixdata = $this->getFixmaster(2); 
         }else{
             $fixdata = $this->getFixmaster(1); 
         }
			$country=$this->getCountryName($invoice['final_destination']);
			$city=$this->getCityNameAgain($invoice['port_load']);
			$address = explode(' ',str_replace("\n", " ", $invoice['consignee']));
			$part = ceil(count($address) / 4);
			$address1 = implode(' ', array_slice($address, 0, $part));
			$address2 = implode(' ', array_slice($address, $part, $part));
			$address3 = implode(' ', array_slice($address, $part * 2));
			$address4 = implode(' ', array_slice($address, $part * 3));

			$subtotal[$invoice['invoice_no']]=0;$tot_tax[$invoice['invoice_no']]=0; $zipper_name='';   $valve='';  
		
			foreach($alldetails as $details)
			{
			    $box = $this->getIngenBox($invoice_no,$details['product_id'],$charge=0);
				$productcode=$this->getProductCode($details['invoice_product_id'],1);
				//printr($box);
				$color = $this->getColorDetails($invoice_no,$details['invoice_product_id']); 	
				//if($productcode['description'] =='')
//				{
//					$description =$color_val['size'].' '.$color_val['measurement'].' '.$color_val['color'].' '.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$details['product_name'] ),0,3)).' '.$zipper_name.' '.$valve;
//				}else
//				{
//					$description=$productcode['description'];
//				}
            $box_roll=array();
            if($details['product_id']=='6')
                $box_roll = $this->getIngenBoxForRoll($invoice_no,$details['invoice_product_id'],$charge=0);
                //printr($box_roll);
				foreach($color as $color_val)
				{
				  //  printr($color_val);
					$zipper=$this->getZipper(decode($details['zipper']));
					if($zipper['zipper_name']!='No zip') 
						$zipper_name=$zipper['zipper_name'];
					if($details['valve']!='No Valve')
					 	$valve=$details['valve'];
					if($details['product_id']=='6')
					{
					    $color_val['qty']= $box_roll['n_wt'].' KG';
					    $tot[$invoice['invoice_no']]=$box_roll['n_wt']*$color_val['rate'];
					}
					else
					   $tot[$invoice['invoice_no']]=$color_val['qty']*$color_val['rate']; 
					   
					$tot_tax[$invoice['invoice_no']]=($tot_tax[$invoice['invoice_no']]+(($tot[$invoice['invoice_no']]*$invoice['gst'])/100));
					$subtotal[$invoice['invoice_no']]=$subtotal[$invoice['invoice_no']]+$tot[$invoice['invoice_no']];
					//printr($color_val);//die;
					$input_array[$i++] = 
									Array('*ContactName'=>"Swiss Pac Pvt Ltd.",
										'EmailAddress'=>$invoice['email'],
										'POAddressLine1'=>$address1,
										'POAddressLine2'=>$address2,
										'POAddressLine3'=>$address3,
										'POAddressLine4'=>$address4,
										'POCity'=>$city['city_name'],
										'PORegion'=>$country['country_name'],
										'POPostalCode'=>$invoice['postal_code'],
										'POCountry'=>$country['country_name'],
										'*InvoiceNumber'=>$invoice['invoice_no'],
										'Reference'=>$invoice['other_ref'],
										'*InvoiceDate'=>date("d-M-y",strtotime($invoice['invoice_date'])),
										'*DueDate'=>date("d-M-y",strtotime($invoice['invoice_date'])),
										'PlannedDate'=>'',
										'Total'=>'',
										'TaxTotal'=>'',
										'InvoiceAmountPaid'=>0,
										'InvoiceAmountDue'=>'',
										'InventoryItemCode'=>$productcode['product_code'],
										'*Description'=>$productcode['description'].'('.$details['product_name'].')  '.$color_val['color_text'].'</b>',
										//'InventoryItemCode'=>$color_val['size'].' '.$color_val['measurement'].' '.$color_val['color'].' '.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$details['product_name'] ),0,3)).' '.$zipper_name.' '.$valve,
										//'*Description'=>$color_val['size'].' '.$color_val['measurement'].' '.$color_val['color'].' '.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$details['product_name'] ),0,3)).' '.$zipper_name.' '.$valve,
										'*Quantity'=>$color_val['qty'],
										'*UnitAmount'=>$color_val['rate'],
										'Discount'=>0,
										'LineAmout'=>$tot[$invoice['invoice_no']],
										'*AccountCode'=>$invoice['account_code'],
										'*TaxType'=>'GST on Income',
										'TaxAmount'=>($tot[$invoice['invoice_no']]*($invoice['gst']/100)),
										'TrackingName1'=>'',
										'TrackingOption1'=>'',
										'TrackingName2'=>'',
										'TrackingOption2'=>'',
										'Currency'=>$currency['currency_code'],
										'Type'=>'Sales Invoice',
										'Sent'=>$invoice['sent'],
										'Status'=>$invoice['invoice_status'],
								); 
				}
			
			}
			$in_no=0;
			foreach($input_array as $key=>$in_arr)
			{
				$input_array[$key]['TaxTotal']= $tot_tax[$input_array[$key]['*InvoiceNumber']];
				$input_array[$key]['Total']= $subtotal[$input_array[$key]['*InvoiceNumber']]+$tot_tax[$input_array[$key]['*InvoiceNumber']];
				$input_array[$key]['InvoiceAmountDue']= $subtotal[$input_array[$key]['*InvoiceNumber']]+$tot_tax[$input_array[$key]['*InvoiceNumber']];
			}
		}
	//printr($input_array);
		return $input_array;
	}
	
	public function viewInvoiceForIB($status,$invoice_no)
	{	
		
		$invoice=$this->getInvoiceNetData($invoice_no);
		$currency=$this->getCurrencyName($invoice['curr_id']);	
		$alldetails=$this->getInvoiceProduct($invoice_no);
	//	printr($alldetails);
		
		$html='';                       
  		$html.='<style>table {border-collapse: collapse;}td{padding: 6px;}</style>
		<div id="print_div" style="padding-top: 0px;width:754px">
					<div class="">
					 <div id="thetable">';
      if($invoice['invoice_date']>'2019-06-19'){
      	$fixdata = $this->getFixmaster(2); 
     }else{
         $fixdata = $this->getFixmaster(1); 
     }
       $html.='<table width="800px"  >
	<tr>
    	<td valign="top" ><h2>TAX INVOICE</h2></td>
        <td valign="top"><table><tr><td><strong>Invoice Date</strong></td></tr><tr><td>'.date("d M Y",strtotime($invoice['invoice_date'])).'</td></tr></table></td> 
        <td valign="top" rowspan="2">'.nl2br($invoice['company_address']).'</td>
    </tr>
    <tr>
    	<td valign="top">'.nl2br($invoice['consignee']).'</td>
        <td valign="top"><table><tr><td><strong>Invoice Number</strong></td></tr><tr><td>'.$invoice['invoice_no'].'</td></tr><tr><td><strong>Order Number</strong></td></tr><tr><td>'.$invoice['buyers_orderno'].'</td></tr></table></td> 
     
    </tr>
</table>
<table  width="800px" style="margin-top: 50px; ">
	<tr style="border-bottom: 3px solid black; height:40px">
    	<th width="200px">Description</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>GST</th>
        <th>Amount '. $currency['currency_code'].'</th>
    </tr>';
	$subtotal=0;$tot_tax=0;
	foreach($alldetails as $details)
	{
		$color = $this->getColorDetails($invoice_no,$details['invoice_product_id']); 			
		//printr($color);
		foreach($color as $color_val)
		{
			$zipper=$this->getZipper(decode($details['zipper']));
			$tot=$color_val['qty']*$color_val['rate'];
			$tot_tax=($tot_tax+(($tot*$invoice['gst'])/100));
			$subtotal=$subtotal+$tot;
			$html.='<tr style="border-bottom: 1px solid rgba(0, 0, 0, 0.09);">
				<td width="200px">'.$details['item_no'].','.$color_val['size'].' '.$color_val['measurement'].' '.$details['product_name'].' '.$zipper['zipper_name'].' '.$color_val['color'].'</td>
				<td>'.$color_val['qty'].'</td>
				<td>'.$color_val['rate'].'</td>
				<td>'.round($invoice['gst']).'</td>
				<td>'.$tot.'</td>
			</tr>';
		}
	}
    $html.='
     <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td>Subtotal</td>
        <td>'.$subtotal.'</td>
    </tr>
    <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td  style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">Total GST '.round($invoice['gst']).'%</td>
        <td  style="border-bottom: 3px solid rgba(0, 0, 0, 0.09);">'.$tot_tax.'</td>
    </tr>
    <tr>
    	<td></td>
        <td></td>
        <td></td>
        <td>Invoice Total '. $currency['currency_code'].'</td>
        <td>'.($subtotal+$tot_tax).'</td>
    </tr>
    <tr >
    	<td></td>
        <td></td>
        <td></td>
        <td style="border-bottom: 3px solid black;">Advance Recevied '. $currency['currency_code'].'</td>
        <td style="border-bottom: 3px solid black;">0.00</td>
    </tr>
    <tr>
    	<td></td>
        <td></td>
        <td></td>
        <th>Amount Due '. $currency['currency_code'].'</th>
        <th>'.($subtotal+$tot_tax).'</th>
    </tr>
	
</table>
<table width="800px"  style="margin-top: 50px; ">
<tr >
    	<th  valign="top">Payment Due Date: '.date("d M Y",strtotime($invoice['invoice_date'])).'</th>
        <td ><table><tr><td><strong>Bank Details:</strong></td></tr><tr><td>'.nl2br($invoice['bank_address']).'</td></tr></table></td>
    </tr>
</table>
<table width="800px"  style="margin-top: 50px; " >
	<tr>
    	<td><table><tr><td><strong>PAYMENT ADVICE</strong></td></tr><tr><td>To: '.nl2br($invoice['company_address']).'</td></tr></table></td>
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
	
	public function viewInoutold($invoice_no,$status)
	{ 
		$invoice=$this->getInvoiceNetData($invoice_no);
		if($status==1)
			$table='in_lable_invoice';
		else
			$table='out_lable_invoice';
		$sql="SELECT * FROM ".$table." WHERE invoice_id='".$invoice_no."' ";
		$data=$this->query($sql);
		$setHtml='';$description='';$valve='';$zipper_name='';
		$i=2;
		foreach($data->rows as $val)
		{
			$detail=json_decode($val['detail']);
			$c_name='';$size='';$qty='';
			$p_name=$this->getActiveProductName($val['product']);
				$zipper=$this->getZipper(decode($val['zipper']));
			if($zipper['zipper_name']!='No zip')
				$zipper_name=$zipper['zipper_name'];
			 if($val['valve']!='No Valve')
			 	$valve=$val['valve'];
			foreach($detail as $detail_data)
			{
				$qty .=$detail_data->box_qty.' + ';
				if($detail_data->color=='-1')
				{

					if($detail_data->color_txt=='')
					{
						$c_text=$this->getColorName($detail_data->color);
							$c_text=$c_text['color'];
					}
					else
						$c_text=$detail_data->color_txt;
					$description=$c_text. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$p_name['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
					if($detail_data->dimension!='')
						$size.=$detail_data->dimension.' + '; 
					else
						$size.=$detail_data->size.' '.$measurement['measurement'].' + '; 					
				}
				else
				{
					$color=$this->getColorName($detail_data->color);
					$c_name.=$color['color'].' + ';
					$measurement=$this->getMeasurementName($detail_data->measurement);
					if($detail_data->dimension!='')
						$size.=$detail_data->dimension.' + '; 
					else
						$size.=$detail_data->size.' '.$measurement['measurement'].' + '; 					
				}
				$gross_weight=$detail_data->gross_wt; 
				$net_weight=$detail_data->net_wt; 
			}
			$c_name=substr($c_name,0,-2);	
			$size=substr($size,0,-2);	
			$qty=substr($qty,0,-2);	
			if($description=='')
			{
				$description=$c_name . ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$p_name['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
			}
			
			if($i%2==0)
				$setHtml .='<div style="width:100%"><table class="table"  border="0" style="  font-size: 16px; width:100%"><tr>';
			$setHtml .='<td style="border:none; border-top:none; width:50%">
						
						BOX NO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<b>'.$val['box_no'].'</b><br>
						QTY NOS.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$qty.' PCS<br>';
				//	$setHtml .='ITEM NOS.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$val['item_no'].'<br>';
				$setHtml .='DESCRIPTION&nbsp;&nbsp;:&nbsp;<b>'.$description.'</b><br>
						SIZE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;'.$size.'<br>';
				if($status==2)		
				{
				$setHtml .='GROSS WT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	 :&nbsp;'.$gross_weight.' Kg<br>
						Net WT &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	 :&nbsp;'.$net_weight.' Kg<br>
						ORDER NO. &nbsp;&nbsp;&nbsp;&nbsp;	 :&nbsp;'.$val['buyers_order_no'].'<br>';
						if(ucwords(decode($invoice['transportation']))=='sea')
						$setHtml .='<b>Marks & NO . &nbsp;&nbsp;&nbsp;&nbsp;	 :&nbsp;'.$val['box_no'].'/'.$data->num_rows.' Boxes </b><br>';
				}
				$setHtml .='<div class="barcode_lable"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div>
                        <div><span class="barcode" style="line-height:50px;font-size:10px;">'.$val['box_unique_number'].'</span><div>
						
						</td>';
			$description='';
			$i++;
			if($i%2==0)
				$setHtml .='</tr></table></div>';
		}
		$setHtml .='</tr></table></div>';
		return $setHtml;
	}
	
	public function viewInout_updatequeryin_gen($invoice_no,$status,$n=0,$from='',$to='')
	{
		//ini_set('MAX_EXECUTION_TIME', 0);
		$parent_id=0;
		if($n==1) 
		{
			$details=$this->InoutLable($invoice_no,$parent_id,$from,$to);
			$details1=$this->colordetails($invoice_no,$parent_id);
			$tot_box=count($details1);
		}
		else
		{
			$details=$this->colordetails($invoice_no,$parent_id);
			$tot_box=count($details);
		}
		$invoice=$this->getInvoiceNetData($invoice_no);
		$setHtml='';$description='';$valve='';$zipper_name='';
		$i=4;$gross_weight=0;
		//printr($details);
		if($details!='')
		{
				foreach($details as $val)
				{	
					$c_name='';$size='';$qty='';
					$zipper=$this->getZipper(decode($val['zipper']));
					$zipper_name=$zipper['zipper_name'];
					$valve=$val['valve'];
					
					$p_name=$this->getActiveProductName($val['product_id']);
					
					$childBox=$this->colordetails($invoice_no,$val['in_gen_invoice_id']);
					$product_decreption = $this->getProductCode($val['invoice_product_id']);
					
				//printr($product_decreption);
					$net_w = $val['net_weight'];
					if(isset($childBox) && !empty($childBox))
					{
						foreach($childBox as $key=>$ch)
						{
						    
							$net_w = $net_w+$ch['net_weight'];
						}
					}
				//	printr($product_decreption);
					$gross_weight=$net_w+$val['box_weight'];
					
							
				// change by sonu   add change for color text 30-10-2017
											
					if($val['color_text']!=''){
				    	$c_name=$val['color_text'].'  '.$val['color'];
					}else{
					    $c_name=$val['color'];
					}
					if($val['filling_details']!=''){
				    	$filling_details=$val['filling_details'];
					}else{
					    $filling_details='';
					}
				//	echo $c_name;
					//end
					if( $val['product_id']!='47' ||$val['product_id']!='48'||$val['product_id']!='72')
					    $val['size']= filter_var($val['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
					if($val['pouch_color_id']=='-1')
					{
						$size_cd =$size=$val['dimension'];
						
					}
					else	
					{
					     if($invoice['country_destination'] != '253'){
					         	$size=$val['size'].' '.$val['measurement'];
					            $size_cd = $val['size'].'='.$val['measurement']; 
						 }else{
						       	$size_us=$val['size'].' '.$val['measurement'];
						       	 $size_cd = $val['size'].'='.$val['measurement']; 
						         $s_us=$this->getsizeForUS($size_us);
						         if($s_us!=''){
						              $size=$s_us;
						         }else{
						              $size=$size_us;
						         }
						         
						 }
					
					}	//printr($size);
					$size_new='';
					if($size=='250. gm' || $size=='500. gm')
					    $size_new=' [NEW SIZE] ';
						
					
					$qty=$val['genqty'];
					
					$description= $filling_details.''.$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
					
					//if($product_decreption==''  )
					//{
						
						//$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
					//}else
					//{
							if( $val['product_id']=='13' ||$val['product_id']=='16'|| $val['product_id']=='31' || $val['product_id']=='30'|| $val['product_id']=='37'|| $val['product_id']=='38')
							{
							    $description=$product_decreption['description'].' '. $filling_details;
							
							
							}
							else
							{
							    $description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') '.$size_new ;
							}
						
				//	}
					
					if($i%4==0)
					{	$a=$i-4;
						if($status==2)		
							$c=4;
						else
							$c=4;
						$style='';
						//if($i%12==4 && $i!='4')
						    //$style="page-break-before:always;";
						    
							$setHtml .='<div id="'.$i.'='.($i%12).'" style="'.$style.'">
											<table class="table"  border="0" >
											
													<tr>';															            
					 }		
				
													//width:50%
												$setHtml .='<td style="border:none; border-top:none; ">
																<table  style="" id="sub_table" class="sub_table">
																	<tr>
																		<td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px;text-align:left;"><b>'.$val['box_no'].'</b></td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$val['genqty'].' PCS</td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
																		
																		
																	</tr>
																	<tr>
																	<td  style="padding:0px;border:0px" colspan="2" ><b>'.$description.'</b></td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$size.'</td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px">MODE OF SHIPMENT&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.ucwords(decode($invoice['transportation'])).'</td>
												                    </tr><tr>';
												                    	if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
            																{
            																	if($invoice['country_destination'] == '170')
            																	{
            																		$label ='SPECIAL CODE';
            																	
            																	} 
            																	else
            																	{
            																		$label = 'ITEM NO.';
            																	}
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['item_no'].'</td>';
            																}
            																//&& $invoice['country_destination']!='42' 04-01-2017
            																
            																
                                                                						    
            																else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155'&& $invoice['country_destination']!='42' ){
            																    
            																	 if($val['ref_no']!=0){
            																	 
                                                                						$val['ref_no']=$val['ref_no'];
                                                                				 }else{
                                                                				     $val['ref_no']= $val['buyers_o_no'];
                                                                				 }
            																    
            																    $setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['ref_no'].'</td>';
            																}
            																else 
            																{
            																	$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['buyers_o_no'].'</td>';
            																}	
            												$setHtml.='</tr>';
																	
												if($status==2)		
												{
												$setHtml .='<tr>
																<td  style="padding:0px;border:0px">GROSS WT&nbsp;:&nbsp;</td>
																<td  style="padding:0px;border:0px">'.number_format($gross_weight,3).' Kg</td>
															</tr>
															<tr>
																<td  style="padding:0px;border:0px">Net WT&nbsp;:&nbsp;</td>
																<td  style="padding:0px;border:0px">'.$net_w.' Kg</td>
															</tr>
														';
															/*	if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
																{
																	if($invoice['country_destination'] == '170')
																	{
																		$label ='SPECIAL CODE';
																	
																	} 
																	else
																	{
																		$label = 'ITEM NO.';
																	}
																	$setHtml .='<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['item_no'].'</td>';
																}
																//&& $invoice['country_destination']!='42' 04-01-2017
																
																
                                                    						    
																else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155'&& $invoice['country_destination']!='42' ){
																    
																	 if($val['ref_no']!=0){
																	 
                                                    						$val['ref_no']=$val['ref_no'];
                                                    				 }else{ 
                                                    				     $val['ref_no']= $val['buyers_o_no'];
                                                    				 }
																    
																    $setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['ref_no'].'</td>';
																}
																else 
																{
																	$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['buyers_o_no'].'</td>';
																}	
																	
															$setHtml .='</tr>';*/
															//told by pinak 9-3-2017
														if(ucwords(decode($invoice['transportation']))=='Air')
														{
														$setHtml .='<tr>
																		<td  style="padding:0px;border:0px"><b>Marks & NO .&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$val['box_no'].'/'.$tot_box.'Boxes </b></td>
																	</tr>';	
																	}			
												}
														$setHtml .='<tr>
																		<td  style="padding:0px;border:0px" colspan="2"><div class="barcode_lable" style=" padding: 0px;"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div></td>
																	</tr>
																	<tr>
																	
																		<td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;font-size:10px;"><img style="width:159px;" class="barcode" alt="'.trim($val['box_unique_number']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($val['box_unique_number']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span>';
																		
																	
                                                					if($invoice_no=='5'){
                                                                    $setHtml .='<br><br><span><img src="https://chart.googleapis.com/chart?chs=156x81&cht=qr&chl='.$val['box_unique_number'].'*'.$p_name['abbrevation'].'*'.$val['genqty'].'*'.$size_cd.'*'.$val['box_no'].'*'.$invoice['invoice_no'].'*'.$product_decreption['product_code_id'].'"></span>';
                                                                   }
															$setHtml .='<div></td>
																	</tr>
											
										</table>
									</td>';
								
								
					$description='';
					$i++; 
				 
					if($i%4==0)
					{
					  //  echo $i.'if';
						$setHtml .='</tr></table></div>';	
						
					}	
					
					
					if(isset($childBox) && !empty($childBox))
										{
											foreach($childBox as $key=>$ch)
											{
										        
										       
											  // product_id
											   	$product_decreption = $this->getProductCode($ch['invoice_product_id']);
											  
											    
											        $child_zipper=$this->getZipper(decode($ch['zipper']));
                                													if($i%4==0)
                                					{	$a=$i-4;
                                						if($status==2)		
                                							$c=4;
                                						else
                                							$c=4;
                                						$style='';
                                						//if($i%12==4)
						                                    //$style='page-break-before:always;';
                                							$setHtml .='<div id="'.$i.'='.($i%12).'" style="'.$style.'" >
                                											<table class="table"  border="0" >
                                											
                                													<tr>';															            
                                					 }		
																		
												//} width:50%	         
												
												// change by sonu   add change for color text 30-10-2017
												
                                                        					if($ch['color_text']!=''){ 
                                                        				         $c_name_ch=$ch['color_text'] .'  '.$ch['color'];
                                                        					}else{
                                                        					    $c_name_ch=$ch['color'];
                                                        					}
                                                        					if($ch['filling_details']!=''){ 
                                                        				         $filling_details_ch=$ch['filling_details'];
                                                        					}else{
                                                        					     $filling_details_ch='';
                                                        					}
                                                        					
                                                        					if( $ch['product_id']!='47' ||$ch['product_id']!='48'||$ch['product_id']!='72')
					                                                                $ch['size']= filter_var($ch['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                                                        					//end							
												                            if($ch['pouch_color_id']=='-1')
                                                        					{
                                                        						$size_ch=$ch['dimension'];
                                                        						$size_code = $ch['dimension'];
                                                        						
                                                        					}
                                                        					else	
                                                        					{
                                                        					     if($invoice['country_destination']!=253){
            																	    	$size_ch=$ch['size'].' '.$ch['measurement'];
            																	   }else{
            																	       	$size_us_c=$ch['size'].' '.$ch['measurement'];
            																	         $s_us_c=$this->getsizeForUS($size_us_c);
            																	       if($s_us!=''){
                                                        						              $size_ch=$s_us_c;
                                                        						         }else{
                                                        						              $size_ch=$size_us_c;
                                                        						         }
            																	   }
                                                        					    
                                                        					
                                                        						$size_code = $ch['size'].'='.$ch['measurement'];
                                                        					}
                                                        					
                                                        				    $size_new_ch='';
                                                            					if($size_ch=='250. gm' || $size_ch=='500. gm')
                                                            					    $size_new_ch=' [NEW SIZE] ';
                                                        					
                                                        					
                                                        						if( $ch['product_id']=='13' ||$ch['product_id']=='16'|| $ch['product_id']=='31' || $ch['product_id']=='30'|| $ch['product_id']=='37'|| $ch['product_id']=='38')
                                                        							{
                                                        							$description_ch=$product_decreption['description'].' '.$filling_details_ch;
                                                        							
                                                        							
                                                        							}
                                                        							else{
                                                        							    $description_ch=$c_name_ch. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$ch['product_name'] ),0,3)).' '.$child_zipper['zipper_name'].' '.$ch['valve'].')'.$size_new_ch ;
                                                        							}
                            																	$setHtml .='<td style="border:none; border-top:none;">
																				<table  style="" id="sub_table" class="sub_table">
																					<tr>
																						<td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
																						<td  style="padding:0px;border:0px"><b>'.$val['box_no'].'</b></td>
																					</tr>
																					<tr>
																						<td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
																						<td  style="padding:0px;border:0px">'.$ch['genqty'].' PCS</td>
																					</tr>
																					<tr>
																						<td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
																						
																					</tr> 
																					<tr>
																				    	<td  style="padding:0px;border:0px" colspan="2" ><b>'.$description_ch.'</b></td>
																					</tr>
																					<tr>
																						<td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
																						<td  style="padding:0px;border:0px">';
																						
																						
																						//.$ch['size'].' '.$ch['measurement'].
																						
																						$setHtml .= $size_ch.'</td>
																					</tr>
																					<tr>
																						<td  style="padding:0px;border:0px">MODE OF SHIPMENT&nbsp;:&nbsp;</td>
																						<td  style="padding:0px;border:0px">'.ucwords(decode($invoice['transportation'])).'</td>
																		</tr>';
														if($status==2)		
														{
															$setHtml .='<tr>
																			<td  style="padding:0px;border:0px">GROSS WT&nbsp;:&nbsp;</td>
																			<td  style="padding:0px;border:0px">'.number_format($gross_weight,3).' Kg</td>
																		</tr>
																		<tr>
																			<td  style="padding:0px;border:0px">Net WT&nbsp;:&nbsp;</td>
																			<td  style="padding:0px;border:0px">'.$net_w.' Kg</td>
																		</tr>
																		<tr>';
																				if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
																				{
																					if($invoice['country_destination'] == '170')
																					{
																						$label ='SPECIAL CODE';
																					} 
																					else
																					{
																						$label = 'ITEM NO.';
																					}
																					$setHtml .='<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
																					$setHtml .='<td  style="padding:0px;border:0px">'.$ch['item_no'].'</td>';
																				}
																				//&& $invoice['country_destination']!='42'  04-01-2018
																				
																				
																				
																			
																				else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155' && $invoice['country_destination']!='42' ){
																				    
																				    	 if($ch['ref_no']!=0){
                                                                						       $ch['ref_no']=$ch['ref_no'];
                                                                						    }else{
                                                                						       $ch['ref_no']= $ch['buyers_o_no'];
                                                                						    }
																                               $setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																                            	$setHtml .='<td  style="padding:0px;border:0px">'.$ch['ref_no'].'</td>';
															                    	}
																				else
																				{
																					$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																					$setHtml .='<td  style="padding:0px;border:0px">'.$ch['buyers_o_no'].'</td>';
																				}
																	
																
																$setHtml .='</tr><tr>
																		<td  style="padding:0px;border:0px"><b>Marks & NO.&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$val['box_no'].'/'.$tot_box.'Boxes </b></td>
																	</tr>';				
														}
														
														$setHtml .='<tr>
																		<td  style="padding:0px;border:0px" colspan="2"><div class="barcode_lable" style=" padding: 0px;"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div></td>
																	</tr>
																	 <tr>
																		<td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;font-size:10px;"><img style="width:159px;" class="barcode" alt="'.trim($val['box_unique_number']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($val['box_unique_number']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span>';
																		
																		
                                                					if($invoice_no=='5'){
                                                                    $setHtml .='<br><br><span><img src="https://chart.googleapis.com/chart?chs=156x81&cht=qr&chl='.$val['box_unique_number'].'*'.$p_name['abbrevation'].'*'.$val['genqty'].'*'.$size_code.'*'.$val['box_no'].'*'.$invoice['invoice_no'].'*'.$product_decreption['product_code_id'].'"></span>';
                                                                   }
															$setHtml .='<div></td> 
																	</tr>
																
																</table></td>
																';
										
											$i++; 
				 
                            					if($i%4==0)
                            					{
                            					  //  echo $i.'if';
                            						$setHtml .='</tr></table></div>';	
                            						
                            					}																
										}
										
										
										}
								
					
					 
				}
				//printr($setHtml);die;
		}
		$setHtml .='</tr></table></div>';
	//printr($setHtml);die;
		return $setHtml;
	}
	public function viewInout($invoice_no,$status,$n=0,$from='',$to='')
	{
		//ini_set('MAX_EXECUTION_TIME', 0);
		$parent_id=0;
		if($n==1) 
		{
			$details=$this->InoutLable($invoice_no,$parent_id,$from,$to);
			$details1=$this->colordetailsTest($invoice_no,$parent_id);
			$tot_box=count($details1); 
		}
		else
		{
			$details=$this->colordetailsTest($invoice_no,$parent_id);
			$tot_box=count($details);
		}
		$invoice=$this->getInvoiceNetData($invoice_no);
		$setHtml='';$description='';$valve='';$zipper_name='';
		if($status==2)	{
		    $i=3;$r=4;
		
		}else{
		    $i=3;$r=4;
		}$gross_weight=0;
		//printr($details);
		if($details!='')
		{
				foreach($details as $val1)
				{	
				    $val=$this->Product_detailTest($invoice_no,$val1['invoice_product_id'],$val1['invoice_color_id']);
					
					$val['box_no']=$val1['box_no'];
					$val['genqty']=$val1['genqty'];
					$val['net_weight']=$val1['net_weight'];
					$c_name='';$size='';$qty='';
					$zipper=$this->getZipper(decode($val['zipper']));
					$zipper_name=$zipper['zipper_name'];
					$valve=$val['valve'];
					
					$p_name=$this->getActiveProductName($val['product_id']);
					
					$childBox=$this->colordetailsTest($invoice_no,$val1['in_gen_invoice_id']);
					$product_decreption = $this->getProductCode($val['invoice_product_id']);
					
				//printr($product_decreption);
					$net_w = $val['net_weight'];
					if(isset($childBox) && !empty($childBox))
					{
						foreach($childBox as $key=>$ch)
						{
						    
							$net_w = $net_w+$ch['net_weight'];
						}
					}
				//	printr($product_decreption);
					$gross_weight=$net_w+$val1['box_weight'];
					
							
				// change by sonu   add change for color text 30-10-2017
											
					if($val['color_text']!=''){
				    	$c_name=$val['color_text'].'  '.$val['color'];
					}else{
					    $c_name=$val['color'];
					}
					if($val['filling_details']!=''){
				    	$filling_details=$val['filling_details'];
					}else{
					    $filling_details='';
					}
				//	echo $c_name;
					//end
					if( $val['product_id']!='47' ||$val['product_id']!='48'||$val['product_id']!='72')
					    $val['size']= filter_var($val['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
					if($val['pouch_color_id']=='-1')
					{
						$size_cd =$size=$val['dimension'];
						
					}
					else 	
					{
					     if($invoice['country_destination'] != '253'){
					         	$size=$val['size'].' '.$val['measurement'];
					            $size_cd = $val['size'].'='.$val['measurement']; 
						 }else{
						       	$size_us=$val['size'].' '.$val['measurement'];
						       	 $size_cd = $val['size'].'='.$val['measurement']; 
						         $s_us=$this->getsizeForUS($size_us);
						         if($s_us!=''){
						              $size=$s_us;
						         }else{
						              $size=$size_us;
						         }
						         
						 }
					
					}	//printr($size);
					$size_new='';
					if($size=='250. gm' || $size=='500. gm')
					    $size_new=' [NEW SIZE] ';
						
					
					$qty=$val['genqty'];
					
					$description= $filling_details.''.$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
					
					//if($product_decreption==''  )
					//{
						
						//$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
					//}else
					//{
							if( $val['product_id']=='13' ||$val['product_id']=='16'|| $val['product_id']=='31' || $val['product_id']=='30'|| $val['product_id']=='37'|| $val['product_id']=='38')
							{
							    if($val['color_text']=='')
							        $description=$product_decreption['description'].' '. $filling_details;
							    else
							        $description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') '. $filling_details;
							}
							else
							{
							    $description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') '.$size_new ;
							}
						
				//	}
					
					if($i%3==0)
					{	$a=$i-$r;
						if($status==2)		
							$c=$r;
						else
							$c=$r;
						$style='';
						if($i%3=='2' && $i!=2)
						    $style="page-break-before:always;";
						    
						//	$setHtml .='<style>.innerbox{  width:250px;     max-width:250px;  display: inline-block;  } </style>';
							$setHtml .='<div id="'.$i.'='.($i%3).'" style="'.$style.'">
											<table class="table"  border="0" width="100%" >
											
													<tr>';															            
					 }		 
				 
													//width:50%
												$setHtml .='<td style="width:33%;border:none; border-top:none;">
																<table  style="" id="innerbox" class="innerbox" style="max-width:550px; height:300px;display: inline-block;" >
																	<tr>
																		<td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px;text-align:left;"><b>'.$val['box_no'].'</b></td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$val['genqty'].' PCS</td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
																		
																		
																	</tr>
																	<tr>
																	<td  style="padding:0px;border:0px" colspan="2" class="test"><b>'.$description.'</b></td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$size.'</td>
																	</tr>
																<tr >';
												                    	if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
            																{
            																	if($invoice['country_destination'] == '170')
            																	{
            																		$label ='SPECIAL CODE';
            																	
            																	} 
            																	else
            																	{
            																		$label = 'ITEM NO.';
            																	}
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['item_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
            																}
            																//&& $invoice['country_destination']!='42' 04-01-2017
            																
            																
                                                                						    
            																else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155' && $invoice['country_destination']!='42' ){
            																    
            																	 if($val['ref_no']!=0){
            																	 
                                                                						$val['ref_no']=$val['ref_no'];
                                                                				 }else{
                                                                				     $val['ref_no']= $val['buyers_o_no'];
                                                                				 }
            																    
            																    $setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['ref_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
            																}
            																else 
            																{
            																	$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['buyers_o_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
            																}	
            												$setHtml.='</tr>';
																	
												if($status==2)		
												{
												$setHtml .='<tr>
																<td  style="padding:0px;border:0px">GROSS WT&nbsp;:&nbsp;</td>
																<td  style="padding:0px;border:0px">'.number_format($gross_weight,3).' Kg</td>
															</tr>
															<tr>
																<td  style="padding:0px;border:0px">Net WT&nbsp;:&nbsp;</td>
																<td  style="padding:0px;border:0px">'.$net_w.' Kg</td>
															</tr>
														';
															/*	if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
																{
																	if($invoice['country_destination'] == '170')
																	{
																		$label ='SPECIAL CODE';
																	
																	} 
																	else
																	{
																		$label = 'ITEM NO.';
																	}
																	$setHtml .='<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['item_no'].'</td>';
																}
																//&& $invoice['country_destination']!='42' 04-01-2017
																
																
                                                    						    
																else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155' && $invoice['country_destination']!='42' ){
																    
																	 if($val['ref_no']!=0){
																	 
                                                    						$val['ref_no']=$val['ref_no'];
                                                    				 }else{ 
                                                    				     $val['ref_no']= $val['buyers_o_no'];
                                                    				 }
																    
																    $setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['ref_no'].'</td>';
																}
																else 
																{
																	$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['buyers_o_no'].'</td>';
																}	
																	
															$setHtml .='</tr>';*/
															//told by pinak 9-3-2017
														if(ucwords(decode($invoice['transportation']))=='Air')
														{
														$setHtml .='<tr>
																		<td  style="padding:0px;border:0px"><b>Marks & NO .&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$val['box_no'].'/'.$tot_box.'Boxes </b></td>
																	</tr>';	
																	}			
												}
														$setHtml .='<tr>
																		<td  style="padding:0px;border:0px" colspan="2"><div class="barcode_lable" style=" padding: 0px;"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div></td>
																	</tr>
																	<tr>
																	
																		<td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;"><img style="width:159px;" class="barcode" alt="'.trim($val1['box_unique_number']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($val1['box_unique_number']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span>';
																		
																	
                                                					if($invoice_no=='5'){
                                                                    $setHtml .='<br><br><span><img src="https://chart.googleapis.com/chart?chs=156x81&cht=qr&chl='.$val1['box_unique_number'].'*'.$p_name['abbrevation'].'*'.$val1['genqty'].'*'.$size_cd.'*'.$val1['box_no'].'*'.$invoice['invoice_no'].'*'.$product_decreption['product_code_id'].'"></span>';
                                                                   }
															$setHtml .='<div></td>
																	</tr>
											
										</table>
									</td>';
								
								
					$description='';
					$i++; 
				 
					if($i%3==0)
					{
					  //  echo $i.'if';
						$setHtml .='</tr></table></div>';	
						
					}	
					
					
					if(isset($childBox) && !empty($childBox))
					{
						foreach($childBox as $key=>$ch1)
						{ 
					        
					        $ch=$this->Product_detailTest($invoice_no,$ch1['invoice_product_id'],$ch1['invoice_color_id']);
						  // product_id
						   	$product_decreption = $this->getProductCode($ch['invoice_product_id']);
						  
						    
						        $child_zipper=$this->getZipper(decode($ch['zipper']));
            			        if($i%3==0)
            					{	$a=$i-$r;
            						if($status==2)		
            							$c=$r;
            						else
            							$c=$r;
            						$style='';
            						if($i%3=='2' && $i!=2)
            						    $style="page-break-before:always;";
            							$setHtml .='<div id="'.$i.'='.($i%3).'" style="'.$style.'" >
            											<table class="table"  border="0" width="100%">
            											
            													<tr>';															            
            					 }		
													
							//} width:50%	         
							
							// change by sonu   add change for color text 30-10-2017
							
                                    					if($ch['color_text']!=''){ 
                                    				         $c_name_ch=$ch['color_text'] .'  '.$ch['color'];
                                    					}else{
                                    					    $c_name_ch=$ch['color'];
                                    					}
                                    					if($ch['filling_details']!=''){ 
                                    				         $filling_details_ch=$ch['filling_details'];
                                    					}else{
                                    					     $filling_details_ch='';
                                    					}
                                    					
                                    					if( $ch['product_id']!='47' ||$ch['product_id']!='48'||$ch['product_id']!='72')
                                                                $ch['size']= filter_var($ch['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                                    					//end							
							                            if($ch['pouch_color_id']=='-1')
                                    					{
                                    						$size_ch=$ch['dimension'];
                                    						$size_code = $ch['dimension'];
                                    						
                                    					}
                                    					else	
                                    					{
                                    					     if($invoice['country_destination']!=253){
															    	$size_ch=$ch['size'].' '.$ch['measurement'];
															   }else{
															       	$size_us_c=$ch['size'].' '.$ch['measurement'];
															         $s_us_c=$this->getsizeForUS($size_us_c);
															       if($s_us!=''){
                                    						              $size_ch=$s_us_c;
                                    						         }else{
                                    						              $size_ch=$size_us_c;
                                    						         }
															   }
                                    					    
                                    					
                                    						$size_code = $ch['size'].'='.$ch['measurement'];
                                    					}
                                    					
                                    				    $size_new_ch='';
                                        					if($size_ch=='250. gm' || $size_ch=='500. gm')
                                        					    $size_new_ch=' [NEW SIZE] ';
                                    					
                                    					
                                    						if( $ch['product_id']=='13' ||$ch['product_id']=='16'|| $ch['product_id']=='31' || $ch['product_id']=='30'|| $ch['product_id']=='37'|| $ch['product_id']=='38')
                                    							{
                                    							$description_ch=$product_decreption['description'].' '.$filling_details_ch;
                                    							
                                    							
                                    							}
                                    							else{
                                    							    $description_ch=$c_name_ch. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$ch['product_name'] ),0,3)).' '.$child_zipper['zipper_name'].' '.$ch['valve'].')'.$size_new_ch ;
                                    							}
        											$setHtml .='<td style="width:33%;border:none; border-top:none;">
															<table  style="" id="innerbox" class="innerbox" style=" max-width:550px; height:300px;display: inline-block;" >
																<tr> 
																	<td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
																	<td  style="padding:0px;border:0px"><b>'.$val['box_no'].'</b></td>
																</tr>
																<tr> 
																	<td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
																	<td  style="padding:0px;border:0px">'.$ch1['genqty'].' PCS</td>
																</tr>
																<tr>
																	<td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
																	
																</tr> 
																<tr>
															    	<td  style="padding:0px;border:0px" colspan="2" class="test" ><b>'.$description_ch.'</b></td>
																</tr>
																<tr>
																	<td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
																	<td  style="padding:0px;border:0px">';
																	
																	
																	//.$ch['size'].' '.$ch['measurement'].
																	
																	$setHtml .= $size_ch.'</td>
																</tr>
																';
																
														$setHtml .='<tr>';
															if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
															{
																if($invoice['country_destination'] == '170')
																{
																	$label ='SPECIAL CODE';
																} 
																else
																{
																	$label = 'ITEM NO.';
																}
																$setHtml .='<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
																$setHtml .='<td  style="padding:0px;border:0px">'.$ch['item_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
															}
															//&& $invoice['country_destination']!='42'  04-01-2018
															
															
															
														
															else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155' && $invoice['country_destination']!='42' ){
															    
															    	 if($ch['ref_no']!=0){
                                            						       $ch['ref_no']=$ch['ref_no']; 
                                            						    }else{
                                            						       $ch['ref_no']= $ch['buyers_o_no'];
                                            						    }
											                               $setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
											                            	$setHtml .='<td  style="padding:0px;border:0px">'.$ch['ref_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
										                    	}
															else
															{
																$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																$setHtml .='<td  style="padding:0px;border:0px">'.$ch['buyers_o_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
															}
												
											
											$setHtml .='</tr>';	
											if($status==2)		
											{
											
														$setHtml .='<tr>
																		<td  style="padding:0px;border:0px">GROSS WT&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.number_format($gross_weight,3).' Kg</td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px">Net WT&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$net_w.' Kg</td>
																	</tr>';
													$setHtml .='<tr>
															<td  style="padding:0px;border:0px"><b>Marks & NO.&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
															<td  style="padding:0px;border:0px">'.$val['box_no'].'/'.$tot_box.'Boxes </b></td>
														</tr>';				 
											}
									
									$setHtml .='<tr>
													<td  style="padding:0px;border:0px" colspan="2"><div class="barcode_lable" style=" padding: 0px;"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div></td>
												</tr>
												 <tr>
													<td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;"><img style="width:159px;" class="barcode" alt="'.trim($val1['box_unique_number']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($val1['box_unique_number']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span>';
													
													
                            					if($invoice_no=='5'){
                                                $setHtml .='<br><br><span><img src="https://chart.googleapis.com/chart?chs=156x81&cht=qr&chl='.$val1['box_unique_number'].'*'.$p_name['abbrevation'].'*'.$val1['genqty'].'*'.$size_code.'*'.$val1['box_no'].'*'.$invoice['invoice_no'].'*'.$product_decreption['product_code_id'].'"></span>';
                                               }
										$setHtml .='<div></td>
												</tr>
											
											</table></td>
											';
					
						$i++; 

        					if($i%3==0)
        					{
        					  //  echo $i.'if';
        						$setHtml .='</tr></table></div>';	
        						
        					}																
					    }
					
					
					}
					 
				}
				//printr($setHtml);die;
		}
		$setHtml .='</tr></table></div>';
	//printr($setHtml);die;
	    
		return $setHtml;
	}
	
	
	
	public function viewInout19_9_2015($invoice_no,$status)
	{ //echo $status;
		$parent_id=0;
		$details=$this->colordetails($invoice_no,$parent_id);
	//printr($details);
	//die;
		$invoice=$this->getInvoiceNetData($invoice_no);
		//printr($invoice);
		
		$setHtml='';$description='';$valve='';$zipper_name='';
		$i=2;$gross_weight=0;$tot_box=count($details);
		if($details!='')
		{
		
		foreach($details as $val)
		{//printr($val);
			$c_name='';$size='';$qty='';
			$zipper=$this->getZipper(decode($val['zipper']));
			//if($zipper['zipper_name']!='No zip')
				$zipper_name=$zipper['zipper_name'];
			// if($val['valve']!='No Valve')
			 	$valve=$val['valve'];
			$gross_weight=$val['net_weight']+$val['box_weight'];
			$childBox=$this->colordetails($invoice_no,$val['in_gen_invoice_id']);
				
			$c_name=$val['color'];
			//printr($c_name);
			//die;
			
			if($val['dimension']!='')
				$size=$val['dimension'];
			else
				$size=$val['size'].' '.$val['measurement'];
				
			$qty=$val['genqty'];
			if(isset($childBox) && !empty($childBox))
			{
				foreach($childBox as $ch)
				{
					
					if($ch['dimension']!='')
						$chsize=$ch['dimension'];
					else
						$chsize=$ch['size'].' '.$ch['measurement'];
					$c_name.=' + '.$ch['color'];
					$size.=' + '.$chsize;
					$qty.=' + '.$ch['genqty'];
				}
			}
			$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
			
			if($description=='')
			{
				
				$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
			}
			
			if($i%2==0)
			{	$a=$i-2;
				if($status==2)		
					$c=3;
				else
					$c=3;
				/*if($a%$c==0)
				{
					$setHtml.='<br style="page-break-before:always" ><div style="width:730px;"><table class="table"  border="0" style="  font-size: 16px; width:730px;margin-right:5px"><tr>';
				}
				else*/
					$setHtml .='<div style="width:600px;" id="'.$i.'"><table class="table"  border="0" style="  font-size: 14px; width:600px;margin:0px;padding:0px;"><tr>';			}
		$setHtml .='<td style="border:none; border-top:none; width:50%">
						<table  style="  font-size: 14px; ">
							<tr>
								<td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
								<td  style="padding:0px;border:0px"><b>'.$val['box_no'].'</b></td>
							</tr>
							<tr>
								<td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
								<td  style="padding:0px;border:0px">'.$val['genqty'].' PCS</td>
							</tr>
							<tr>
								<td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
								<td  style="padding:0px;border:0px"><b>'.$description.'</b></td>
							</tr>
							<tr>
								<td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
								<td  style="padding:0px;border:0px">'.$size.'</td>
							</tr>';
				if($status==2)		
				{
				$setHtml .='<tr>
								<td  style="padding:0px;border:0px">GROSS WT&nbsp;:&nbsp;</td>
								<td  style="padding:0px;border:0px">'.number_format($gross_weight,3).' Kg</td>
							</tr>
						<tr>
								<td  style="padding:0px;border:0px">Net WT&nbsp;:&nbsp;</td>
								<td  style="padding:0px;border:0px">'.$val['net_weight'].' Kg</td>
							</tr>
						<tr>';
							if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
							{
								if($invoice['country_destination'] == '170')
								{
									$label ='SPECIAL CODE';
								} 
								else
								{
									$label = 'ITEM NO.';
								}
								$setHtml .='<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
								//$setHtml .='<td  style="padding:0px;border:0px">ITEM NO.&nbsp;:&nbsp;</td>';
								$setHtml .='<td  style="padding:0px;border:0px">'.$val['item_no'].'</td>';
							}
							else
							{
								$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
								$setHtml .='<td  style="padding:0px;border:0px">'.$val['buyers_o_no'].'</td>';
							}	
								
						$setHtml .='</tr>';
						//if(ucwords(decode($invoice['transportation']))=='sea')
						$setHtml .='<tr>
								<td  style="padding:0px;border:0px"><b>Marks & NO.&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td  style="padding:0px;border:0px">'.$val['box_no'].'/'.$tot_box.'Boxes </b></td>
							</tr>';				
				}
				$setHtml .='<tr>
								<td  style="padding:0px;border:0px" colspan="2"><div class="barcode_lable" style=" padding: 0px;"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div></td>
							</tr>
                        <tr>
							<td  style="padding:0px;border:0px" colspan="2"><div><span class="barcode" style="line-height:50px;font-size:10px;">'.$val['box_unique_number'].'</span><div></td>
							</tr>
						
						</table></td>';

			$description='';
			$i++;
			if($i%2==0)
			{
				$setHtml .='</tr></table></div>';	
				
			}			
			
		}
		}
		$setHtml .='</tr></table></div>';
		
		return $setHtml;
	}
		
	function viewDetailsold($invoice_no)
	{
		$sql="SELECT * FROM in_lable_invoice WHERE invoice_id='".$invoice_no."' ";
		$data=$this->query($sql);
		$setHtml='';$description='';$valve='';$zipper_name='';
		$setHtml='';
		$setHtml .= '<div class="table-responsive">';
		$setHtml .='<table class="table b-t table-striped text-small table-hover">';
		$setHtml .=' <thead>
						<tr>
							<th colspan="7" style="text-align:center;padding:0;"><h4>Detailed Packing List</h4></th>
						</tr>
						<tr>
							<th style="width: 5%;">Box Nos.</th>                      
							<th style="width:40%">Product</th>
							<th style="width: 8%;">Buyer\'s Order No</th>
							<th style="width: 8%;">Size</th>
							<th style="width: 8%;">Quantity</th>
							<th style="width: 7%;">Gr. Wt in kgs </th>
							<th style="width: 7%;">Net. Wt in kgs</th>
						</tr>
					</thead>
					<tbody>';
		$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;
		foreach($data->rows as $val)
		{
			$detail=json_decode($val['detail']);
			$c_name='';$size='';$qty='';
			$p_name=$this->getActiveProductName($val['product']);
				$zipper=$this->getZipper(decode($val['zipper']));
				if($zipper['zipper_name']!='No zip')
					$zipper_name=$zipper['zipper_name'];
				 if($val['valve']!='No Valve')
					$valve=$val['valve'];
			foreach($detail as $detail_data)
			{
				
				$qty .=$detail_data->box_qty.' + ';
				if($detail_data->color=='-1')
				{
					if($detail_data->color_txt=='')
					{
						$c_text=$this->getColorName($detail_data->color);
						$c_text=$c_text['color'];
					}
					else
						$c_text=$detail_data->color_txt;
					$description=$c_text. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$p_name['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;	
						$measurement=$this->getMeasurementName($detail_data->measurement);
					//if($detail_data->dimension!='')
					//	$size.=$detail_data->dimension.' + '; 
					//else
						$size.=$detail_data->size.' '.$measurement['measurement'].' + '; 			
				}
				else
				{
					$color=$this->getColorName($detail_data->color);
					$c_name.=$color['color'].' + ';
					$measurement=$this->getMeasurementName($detail_data->measurement);
					//if($detail_data->dimension!='')
						//$size.=$detail_data->dimension.' + '; 
					//else
						$size.=$detail_data->size.' '.$measurement['measurement'].' + '; 
				}
				$gross_weight=$detail_data->gross_wt; 
				$net_weight=$detail_data->net_wt; 
			}
			$c_name=substr($c_name,0,-2);	
			$size=substr($size,0,-2);	
			$qty=substr($qty,0,-2);	
			if($description=='')
			{
				
				$description=$c_name . ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$p_name['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
			}
			$tot_qty=$qty+$tot_qty;
			$tot_gross_weight=$gross_weight+$tot_gross_weight;
			$tot_net_weight=$net_weight+$tot_net_weight;
			$setHtml .='<tr>
						<td >'.$val['box_no'].'</td>
						<td>'.$description.'</td>
						<td>'.$val['buyers_order_no'].'</td>
						<td>'.$size.'</td>
						<td>'.$qty.'</td>
						<td>'.$gross_weight.'</td>
						<td>'.$net_weight.'</td>
						</tr>';
			$description='';
		}
		$setHtml.='<tr><td colspan="7">&nbsp;</td></tr>
				   <tr>
					   <td></td>
					   <td></td>
					   <td></td>
					   <td></td>
					   <td><strong>'.$tot_qty.'</strong></td>
					   <td><strong>'.$tot_gross_weight.'</strong></td>
					   <td><strong>'.$tot_net_weight.'</strong></td>
				</tr>';
		$setHtml.='</tbody></table></div>';
		return $setHtml;				
	}
	
	public function deleteBox($in_gen_invoice_id)
	{
		$deleted_by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');
		//$sql="DELETE FROM in_gen_invoice_test WHERE in_gen_invoice_id='".$in_gen_invoice_id."' OR parent_id='".$in_gen_invoice_id."'";
		$this->query("UPDATE " . DB_PREFIX . "in_gen_invoice_test_new SET is_delete = '1',  deleted_by = '".$deleted_by."' WHERE in_gen_invoice_id='".$in_gen_invoice_id."' OR parent_id='".$in_gen_invoice_id."'");
		$sql="UPDATE " . DB_PREFIX . "in_gen_invoice_test SET is_delete = '1',  deleted_by = '".$deleted_by."' WHERE in_gen_invoice_id='".$in_gen_invoice_id."' OR parent_id='".$in_gen_invoice_id."'";
		
		$data=$this->query($sql);
		/*SET @num := 0;
	    UPDATE your_table SET id = @num := (@num+1);
	    ALTER TABLE tableName AUTO_INCREMENT = 1;
*/
		return $data;
	}
	
	function viewPalletSheet($invoice_no,$status=0,$price=0)
	{
		$total_pallet = $this->getPalletS($invoice_no); 
		//printr($total_pallet );
		$setHtml='';
		foreach($total_pallet as $pallet)
		{
			$invoice=$this->getInvoiceNetData($invoice_no);
			if($invoice['country_destination']==170)
				$item_text='Special Code';
			else
				$item_text='';//$item_text='Item No.';//[kinjal] on 20-2-2017 told by pinank this not come in any cond. below too.
				
				$style='page-break-before:always;';
				if($pallet['pallet_no']=='1')
					$style='';
			$description='';$valve='';$zipper_name='';$gross_weight='';
		
			$setHtml.='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data" style="'.$style.'">';
				$setHtml .= '<div class="table-responsive" >';
					$setHtml .='<table class="table b-t table-striped text-small table-hover detail_table" style=" font-size: 12px;width:800% border: 1px solid black;">';
					if($price==1)
						$colspan=10;
					else
						$colspan=6;
							$setHtml .=' <thead>
												<tr>
													<th colspan="'.$colspan.'" style="font-size: 15px;text-align:center;padding:0;"><h4><b>Pallet Sheet No. '.$pallet['pallet_no'].'</b></h4></th>
												</tr> 
												<tr>
													<th style="width:4%">BOX NOS.</th>  ';                    
													if($invoice['country_destination']==253)
														$setHtml.='<th style="width:5%" >Buyer\'s Order No</th>';
													$setHtml.='<th  style="width:12%">DESCRIPTION</th>';
													//[kinjal] on 20-2-2017 told by pinank this only come in uk cond. below too.
													if($invoice['country_destination']==170)
														$setHtml.='<th style="width:5%">'.$item_text.'</th>';
														
													if($invoice['country_destination']!=253)
														$setHtml.='<th style="width:10%">SIZE</th>';
													$setHtml.='<th style="width:5%">QTY NOS.</th>
													<th style="width:5%">Gross Weight(KGS)</th>
													<th style="width:5%">NET Weight(KGS)</th>';
													if($price==1)
													{
														$setHtml.='<th style="width:5%">RATE</th>
														<th style="width:5%">TOTAL AMOUNT</th>';
													}
													//[kinjal] on 20-2-2017 told by pinank this not come in any cond. below too.
													/*if($invoice['final_destination']!=253)
														$setHtml.='<th style="width:35px">Buyer\'s Order No</th>';*/
													
													if($status==0)
													$setHtml .=	'<th style="width:5%">Action</th>';
									$setHtml .=	'</tr>
										</thead>
										<tbody>';
										$parent_id=0;
										$str=' AND pallet_id='.$pallet['pallet_id'].'';
										$colordetails=$this->colordetails($invoice_no,$parent_id,'',$str);
										//printr($colordetails);
										//die;	
										$i=1;
										if(isset($colordetails) && !empty($colordetails))
										{
											$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=$tot_discount_rate=0;
											foreach($colordetails as $color)
											{
												//printr($color);
												//die;
												$zipper=$this->getZipper(decode($color['zipper']));
												//if($zipper['zipper_name']!='No zip')
													$zipper_name=$zipper['zipper_name'];
												// if($color['valve']!='No Valve')
													$valve=$color['valve'];
												$childBox=$this->colordetails($invoice_no,$color['in_gen_invoice_id']);
												
												$c_name=$color['color'];
												$color_text_ch=$color['color_text'];
												if($color['dimension']!='')
													$size=$color['dimension'];
												else
													$size=$color['size'].' '.$color['measurement'];
												//$size=$color['size'].' '.$color['measurement'];
												$qty=$color['genqty'];
												if(isset($childBox) && !empty($childBox))
												{
													foreach($childBox as $ch)
													{
														$c_name.=' + '.$ch['color'];
														$color_text_ch.=$ch['color_text'];
														if($ch['dimension']!='')
															$size.=' + '.$ch['dimension'];
														else
															$size.=' + '.$ch['size'].' '.$ch['measurement'];
														//$size.=' + '.$ch['size'].' '.$ch['measurement'];
														$qty.=' + '.$ch['genqty'];
													}
												}
												$description=$color_text_ch.'  '.$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$color['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
										
												$gross_weight=$color['net_weight']+$color['box_weight'];
												$tot_qty=$color['genqty']+$tot_qty;
												$tot_gross_weight=$gross_weight+$tot_gross_weight;
												$tot_net_weight=$color['net_weight']+$tot_net_weight;
												$tot_rate=$color['rate']+$tot_rate;
												$tot_discount_rate  = $color['dis_rate'] + $tot_discount_rate ;
												$tot_amt=($qty*$color['rate'])+$tot_amt;
												if(isset($color['box_no']) && $color['box_no']!=0)
												{
													$box_no=$color['box_no'];
												}
												else
												{
													$box_no='';
												}
												
												$setHtml .='<tr>';
													$setHtml .='<td>'.$box_no.'</td>';
													if($invoice['country_destination']==253)
														$setHtml .='<td>'.$color['buyers_o_no'].'</td>';
													
													if($invoice['country_destination']==253)
														$setHtml.='<td>'.$size.' '.$description.'</td>';
													else
														$setHtml .='<td>'.$description.'</td>';
													
													if($invoice['country_destination']==170)
														$setHtml.='<td>'.$color['item_no'].'</td>';
													
													if($invoice['country_destination']!=253)
															$setHtml.='<td>'.$size.'</td>';
															$setHtml.='<td>'.$qty.'</td>
															<td>'.$gross_weight.'</td>
															<td>'.$color['net_weight'].'</td>';
													if($price==1)
													{
														$setHtml.='<td>'.$color['rate'].'</td>
																   <td>'.$qty*$color['rate'].'</td>';
													}
													/*if($invoice['final_destination']!=253)
														$setHtml .='<td>'.$color['buyers_o_no'].'</td>';*/
													if($status==0)
													{
														$setHtml .='<td><a class="btn btn-danger btn-sm" id="'.$color['in_gen_invoice_id'].'" href="javascript:void(0);">
																			<i class="fa fa-trash-o"></i></a>
																			<a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" id="addmore" data-original-title="Add Box Detail" onclick="add_box('.$invoice_no.','.$i.','.$color['in_gen_invoice_id'].','.$color['box_weight'].')"><i class="fa fa-plus"></i></a>
																	</td>';
													}
												$setHtml .='</tr>';
												$description='';
												$i++;				
											}		
										}
						$setHtml.='</tbody>
						</table>
				</div>
				
			</form>';
		}
	//	printr($setHtml);
		return $setHtml;				
	}
		
	function viewDetails($invoice_no,$status=0,$price=0,$p_break=0,$option=array(),$show_status='0')
	{ //printr($show_status);die;
	    $setHtml='';
		
		$invoice_qty=$this->getInvoiceTotalData($invoice_no);
		$alldetails=$this->getProductdeatils($invoice_no);
		//$tot_qty_scoop=$tot_scoop_qty=$tot_scoop_rate=$total_amt_scoop=0;
		
		//added by [kinjal] on 12-5-2017 for other product in UK contory con 
		$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_no = $con_no =$gls_no =$val_no =$silica_gel_no ='';
		$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series = $con_series =$gls_series =$val_series=$chair_series =$silica_gel_series ='';
		$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper = $total_amt_con =$total_amt_gls=$total_amt_valve=$total_amt_val=$total_amt_silica_gel=$total_amt_chair=0;
		$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty = $tot_paper_qty = $tot_con_qty = $tot_gls_qty =$tot_val_qty=$tot_chair_qty=$tot_silica_gel_qty = 0;
		$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_paper_rate = $tot_con_rate =$tot_gls_rate=$tot_val_rate=$tot_chair_rate=$tot_silica_gel_rate=0;
		//	printr($alldetails);
			foreach($alldetails as $details){
						if($details['product_id']=='11')
            			{
            				$tot_qty_scoop=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
            				$tot_scoop_qty = $tot_scoop_qty + $tot_qty_scoop['total']; 
            				$tot_scoop_rate = $tot_scoop_rate + $tot_qty_scoop['rate'];
            				$total_amt_scoop = $total_amt_scoop + $tot_qty_scoop['tot_amt'];
            			}
						else if($details['product_id']=='6')
						{
							$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);

						//	printr($tot_qty_roll);
							$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
							$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
						//	$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
							$net_pouches_roll = $this->getIngenBox($invoice_no,$details['product_id'],0);
						
							$total_amt_roll = $net_pouches_roll['total_amt'];
						
						}
						else if($details['product_id']=='10')
						{
							$tot_qty_mailer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_mailer_qty = $tot_mailer_qty + $tot_qty_mailer['total']; 
							$tot_mailer_rate = $tot_mailer_rate + $tot_qty_mailer['rate'];
							$total_amt_mailer = $total_amt_mailer + $tot_qty_mailer['tot_amt'];
						}
						else if($details['product_id']=='23')
						{
							$tot_qty_sealer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_sealer_qty = $tot_sealer_qty + $tot_qty_sealer['total']; 
							$tot_sealer_rate = $tot_sealer_rate + $tot_qty_sealer['rate'];
							$total_amt_sealer = $total_amt_sealer + $tot_qty_sealer['tot_amt'];
						}
						else if($details['product_id']=='18')
						{
							$tot_qty_storezo=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_storezo_qty = $tot_storezo_qty + $tot_qty_storezo['total']; 
							$tot_storezo_rate = $tot_storezo_rate + $tot_qty_storezo['rate'];
							$total_amt_storezo = $total_amt_storezo + $tot_qty_storezo['tot_amt'];
						}
						else if($details['product_id']=='34')
						{
							$tot_qty_paper=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_paper_qty = $tot_paper_qty + $tot_qty_paper['total']; 
							$tot_paper_rate = $tot_paper_rate + $tot_qty_paper['rate'];
							$total_amt_paper = $total_amt_paper + $tot_qty_paper['tot_amt'];
						}
						else if($details['product_id']=='47')
						{
							$tot_qty_con=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_con_qty = $tot_con_qty + $tot_qty_con['total']; 
							$tot_con_rate = $tot_con_rate + $tot_qty_con['rate'];
							$total_amt_con = $total_amt_con + $tot_qty_con['tot_amt'];
						}
						else if($details['product_id']=='48')
						{
							$tot_qty_gls=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_gls_qty = $tot_gls_qty + $tot_qty_gls['total']; 
							$tot_gls_rate = $tot_gls_rate + $tot_qty_gls['rate'];
							$total_amt_gls = $total_amt_gls + $tot_qty_gls['tot_amt'];
						}
						else if($details['product_id']=='72')
						{
							$tot_qty_chair=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_chair_qty = $tot_chair_qty + $tot_qty_chair['total']; 
							$tot_chair_rate = $tot_chair_rate + $tot_qty_chair['rate'];
							$total_amt_chair = $total_amt_chair + $tot_qty_chair['tot_amt'];
						}	else if($details['product_id']=='37')
						{
							$tot_qty_oxygen_absorbers=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_oxygen_absorbers_qty = $tot_oxygen_absorbers_qty + $tot_qty_oxygen_absorbers['total']; 
							$tot_oxygen_absorbers_rate = $tot_oxygen_absorbers_rate + $tot_qty_oxygen_absorbers['rate'];
							$total_amt_oxygen_absorbers = $total_amt_oxygen_absorbers + $tot_qty_oxygen_absorbers['tot_amt'];
						}	else if($details['product_id']=='38')
						{
							$tot_qty_silica_gel=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_silica_gel_qty = $tot_silica_gel_qty + $tot_qty_silica_gel['total']; 
							$tot_silica_gel_rate = $tot_chair_rate + $tot_qty_silica_gel['rate'];
							$total_amt_silica_gel = $total_amt_silica_gel + $tot_qty_silica_gel['tot_amt'];
						}
						else if($details['product_id']=='63')
						{
							$tot_qty_val=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_val_qty = $tot_val_qty + $tot_qty_val['total']; 
							$tot_val_rate = $tot_val_rate + $tot_qty_val['rate'];
							$total_amt_valve = $total_amt_valve + $tot_qty_val['tot_amt'];
						}else{
						    $total_amt_val=$invoice_qty['tot'];
						}
			}
		
	//	printr($tot_qty_roll);
	//	printr($total_amt_roll.'==='.$tot_roll_qty.'=\='.$tot_roll_rate);
		//online : 177 & offline :193
		$menu_id = $this->getMenuPermission(177,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		//printr($menu_id);
		$invoice=$this->getInvoiceNetData($invoice_no);
		$currency=$this->getCurrencyName($invoice['curr_id']);
		if($invoice['country_destination']==170)
			$item_text='Special Code';
		else
			$item_text='Item No.';
			
		$totalboxes=$this->colordetails($invoice_no,0,'','','');	
		$total_box=$this->colordetails($invoice_no,0,'','','',$option);
	//	printr(count($total_box));//die;
		//if($show_status==0)
		//{
		    if(isset($total_box) && !empty($total_box))
    		{
    		   /* if($invoice_no=='1420'){
    		        $tot_inv=600;
    		        
    		    }else{*/
    			    $tot_inv=count($total_box);
    		        
    		  //  }
    	//	$tot_inv=count($total_box);
    		//	printr($tot_inv);
    	//	if($invoice['box_limit']!=0)
    		
    			$per_page=$invoice['box_limit'];
    			$total_pages=$tot_inv/$per_page;
    			$setHtml='';
    			//getColorDetailstotalecho $tot_inv.'=='.$per_page;die;
    				for($page_no=0;$page_no<$total_pages;$page_no++)
    				{
    					$start=$per_page*$page_no;
    					//echo $start;
    					$end=$start+$per_page;
    					//echo $end;
    					if($tot_inv>$invoice['box_limit'])
    					{
    						if($page_no==0)
    							$limit=' LIMIT '.$invoice['box_limit'].' OFFSET 0';
    						else
    							$limit=' LIMIT '.$invoice['box_limit'].' OFFSET  '.$start;
    					}
    					else
    							$limit='';
    					
    					$description='';$valve='';$zipper_name='';$gross_weight='';
    				//	printr($price);
    						$style='';
    						if($p_break==1)
    						{
    							if($page_no>0)
    								$style='page-break-before:always;';
    						}
    							//echo $style;
    					$setHtml.='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data" style="'.$style.'">';
    				
    					$setHtml .= '<div class="table-responsive"  style="width:730px;">';
    								if($page_no>0)
    								{
    									$setHtml .= '<div style="width:730px;">';
    									//<br><br><br>   $style='page-break-before:always;';
    								}
    								else
    								{
    									$setHtml .= '<div style="width:730px;">';
    									//$style='';
    									/*if($price==1)
    										$setHtml.='<h1>Packaging Details With Price List</h1>';
    									else
    										$setHtml .= '<h1>Packaging Details List </h1>';*/
    								}
    									/*$style='page-break-before:always;';
    									if($p_break==1)
    										$style='';*/
    										if(!empty($menu_id) || !empty($menu_admin_permission))
    											$i = 13;
    										else
    											$i = 12;
    										
    										$setHtml .='<table class="table b-t table-striped text-small table-hover detail_table" style="width:730px;">';
    											if($price==1)
    											{
    												$colspan=$i;
    												if($invoice['country_destination']==170)
    											    	$colspan=12;
    											}	
    											else
    											{
    												$colspan=10;
    												if($invoice['country_destination']==170)
    											    	$colspan=12;
    											}	
    												$setHtml .=' <thead>';
    														if($page_no=='0')
    														{
    															$setHtml.='<tr><th  colspan="'.$colspan.'" style="text-align:center;">';
    																if($price==1)
    																	$setHtml.='<h2>Packaging Details With Price List</h2>';
    																else
    																	$setHtml .= '<h2>Packaging Details List </h2>';
    															$setHtml.='</th></tr>';
    														}
    														$setHtml .='<tr>
    																	<th  colspan="'.$colspan.'" style="text-align:center;padding:0;"><h4>Detailed Packing List '.($page_no+1).'</h4></th>
    																</tr>
    																<tr>
    																	<th  style="text-align:center" style="width:20px">Box Nos.</th>  ';                    
    																	if($invoice['country_destination']==253)
    																		$setHtml.='<th style="text-align:center" style="width:35px">Buyer\'s Order No</th>';
    																	$setHtml.='<th  style="text-align:center" colspan="3">Product</th>';
    																	//updated on 3-12-2016 [kinjal] if($invoice['final_destination']!=170)
    																	if($invoice['country_destination']=='253')
    																	{
    																		$setHtml.='<th  style="text-align:center"style="width:35px">'.$item_text.'</th>';
    																	}
    																	if($invoice['country_destination']!=253)
    																	    $setHtml.='<th  style="text-align:center"colspan="2">Size</th>';
    																	    
    																	$setHtml.='<th style="text-align:center" style="width:25px">Quantity</th>
    																			   <th style="text-align:center" style="width:35px">Gr. Wt in kgs</th>
    																	           <th  style="text-align:center" style="width:35px">Net. Wt in kgs </th>';
    																	if($invoice['country_destination']==170 && $price==0)
    																	{
    																	    
    																	    
    																		$setHtml.='<th style="text-align:center" style="width:35px">invoice NUMBER</th>';
    																		$setHtml.='<th  style="text-align:center"style="width:35px">'.$item_text.'</th>';
    																	}
    																	if($price==1)
    																	{
    																		$setHtml.='<th style="text-align:center" style="width:25px">Rate</th>';
    																		//add by sonu told by vikas sir 6-4-2017
    																		if(!empty($menu_id) || !empty($menu_admin_permission))
    																			{
    																				$setHtml.='<th  style="text-align:center"style="width:25px">Original Rate</th>';
    																			}
    																		$setHtml.='<th  style="text-align:center" style="width:30px">Total Amount</th>';
    																	}
    																	if($invoice['country_destination']!=253)
    																		$setHtml.='<th style="text-align:center" style="width:35px">Buyer\'s Order No</th>';
    								
    																	if($status==0)
    																		$setHtml .=	'<th style="text-align:center" style="width:35px">Action</th>';
    													$setHtml .=	'</tr>
    														</thead>
    														<tbody>';
    														
    													
    															$parent_id=0;
    															 
    															$colordetails=$this->colordetails($invoice_no,$parent_id,'','',$limit,$option);
    															//printr($colordetails);
    														//die;	
    															$i=1;
    															$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=0 ;$tot_discount_rate  =$tot_amt_show=$tot_show=$total_amt_air=0;
    															if(isset($colordetails) && !empty($colordetails))
    															{
    															    
    															    //	printr($colordetails);
    																$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=$tot_netW=0;
    																$bono='';$o_no =$ref_number=$item_number='';
    																$i_color_id='0';
    																foreach($colordetails as $key=>$color)
    																{
    																	//	printr($color);
    																	   /* if($i_color_id!=$color['invoice_color_id']){
    																	        
	                                                                           if($color['cylinder_rate']!='0.00' && $color['no_of_cylinder'] !='0'){
	                                                                               
	                                                                             
    	                                                                           $cylinder_html.='<tbody>							
                                                            											<tr>
                                                            												<th style="text-align:center" style="width:150px">'.$color['color_text'].'</th>  
                                                            												<th style="text-align:center" style="150px">'.$color['no_of_cylinder'].'</th>
                                                            												<th style="text-align:center" style="width:100px">'.$color['cylinder_rate'].'</th>
                                                            												<th style="text-align:center" style="width:100px">'.($color['no_of_cylinder']*$color['cylinder_rate']).'</th>
                                                            											</tr>
                                                                    							    </tbody>
                                                        									';
	                                                                               
	                                                                           }
	                                                                            $i_color_id=$color['invoice_color_id'];
    																	    }*/
  
    																	
    																	
    																	
    																	
    																	
    																	
    																	
    																	
    																	//echo count($colordetails);
    																	//printr($color);
    																	$zipper=$this->getZipper(decode($color['zipper']));
    																	//if($zipper['zipper_name']!='No zip')
    																		$zipper_name=$zipper['zipper_name'];
    																	// if($color['valve']!='No Valve')
    																		$valve=$color['valve'];
    																	$childBox=$this->colordetails($invoice_no,$color['in_gen_invoice_id']);
    																	
    																	$c_name=$color['color'];
    																//	if($color['pouch_color_id'] == '-1')
    																	if($color['color_text']!='')
    																	{
    																		$c_name = $color['color_text'].'   '.$color['color'];
    																	}
    																if($color['filling_details']!=''){
                                                    				    	$filling_details=$color['filling_details'];
                                                    					}else{
                                                    					    $filling_details='';
                                                    					}
    																//	printr('abc'.$c_name);
    																	
    																	
    																	
    																	 	$product_decreption = $this->getProductCode($color['invoice_product_id']);
    																	 	
    																	 	
    																    if($color['product_id'] == '47'|| $color['product_id'] == '48'|| $color['product_id'] == '72'){
    																        $color['size']=$color['size'];
    																         $description=$product_decreption['description'];
    																    }else{
    																        $color['size']= filter_var($color['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    																        $description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$color['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') '.$filling_details.'' ;
    																    }
    																				
    																	//told by jaimini 17-2-2017 comment by sonu pdf is not working correctly  23-3-2017 
    																	//printr($color['size']);die;
    																	if($color['pouch_color_id'] == '-1')
    																	{
    																		//told by jaimini 30-3-2017
    																		//if($color['size'] =='0')
    																		//{
    																			$size=$color['dimension'];
    																			
    																		//}else{
    																			//$size=$color['size'].' '.$color['measurement'];
    																		//}
    																	}else{
    																	   if($invoice['country_destination']!=253){
    																	    	$size=$color['size'].' '.$color['measurement'];
    																	   }else{
    																	       	$size_us=$color['size'].' '.$color['measurement'];
    																	         $s_us=$this->getsizeForUS($size_us);
    																	       if($s_us!=''){
                                                						              $size=$s_us;
                                                						         }else{
                                                						              $size=$size_us;
                                                						         }
    																	   } 
    																	}
    																	//$size=$color['size'].' '.$color['measurement'];
    																	$qty=$color['genqty'];
    																	$rate=$color['rate'];
    																	$discount_rate = $color['dis_rate'];
    																	$net_w = $tot_netW = $color['net_weight'];
    																	$child_qty=0;$total_r_child=$c_rate=$discount_total_r_child = $c_discount_rate =0 ;
    																
    																	if($color['product_id']!=6)
    																	    $total_r=$qty*$rate;
    																	else
    																	    $total_r=$color['net_weight']*$rate;
    														
    																	
    																//	printr($color);
    																	 $o_no = $color['buyers_o_no'];
    																	 $ref_number = $color['ref_no'];
    																	 $item_number = $color['item_no'];
    																	 
    																	 $div=' + ';
    																	 if($p_break==1)
    																	 	$div='<div class="line line-dashed m-t-large"></div>';
    																		
        																	if(isset($childBox) && !empty($childBox))
        																	{
        																		foreach($childBox as $ch)
        																		{// printr($ch);
    																		    if($ch['product_id'] != '47'||$ch['product_id'] != '48'||$ch['product_id'] != '72')
    																		        $ch['size']=filter_var($ch['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    																		   	if($ch['filling_details']!=''){
                                                            				    	$filling_details_ch=$ch['filling_details'];
                                                            					}else{
                                                            					    $filling_details_ch='';
                                                            					}
    																			
    																			if($color['pouch_color_id'] == '-1' ||  $ch['color_text']!="")
            																	{
            																	   
            																		$c_name_ch = $ch['color_text'].' '.$ch['color'] ;	
            																	
            																	    $size_ch=$ch['dimension'];
            																			
            																		
            																	}
            																else{
            																	        
            																	  
            																	    
            																	     if($invoice['country_destination']!=253){
                																	    	$size_ch=$ch['size'].' '.$ch['measurement'];
                																	   }else{
                																	       	$size_us_c=$ch['size'].' '.$ch['measurement'];
                																	         $s_us_c=$this->getsizeForUS($size_us_c);
                																	       if($s_us!=''){
                                                            						              $size_ch=$s_us_c;
                                                            						         }else{
                                                            						              $size_ch=$size_us_c;
                                                            						         }
                																	   }
            																	
            																		$c_name_ch=$ch['color'];
            																	}
    																		//	printr($color['pouch_color_id']);
    																		//	printr(	$c_name_ch);
    																			$zipper_child=$this->getZipper(decode($ch['zipper']));
    																			$description.=$div.''.$c_name_ch. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$ch['product_name'] ),0,3)).' '.$zipper_child['zipper_name'].' '.$ch['valve'].') '.$filling_details_ch.'' ;			
    																			/*if($ch['dimension']!='')
    																				$size.=' + '.$ch['dimension'];
    																			else*/
    																				$size.=$div.''.$size_ch;
    																			//$size.=' + '.$ch['size'].' '.$ch['measurement'];
    																			if($ch['genqty']!='')
    																			$qty.=$div.''.$ch['genqty'];
    																			//$child_qty += $ch['genqty'];
    																			if($ch['rate']!='')
    																			{
    																				$rate.=$div.''.$ch['rate'];
    																				$total_r_child+=($ch['genqty']*$ch['rate']);
    																				$c_rate +=$ch['rate'];
    																			}
    																			if($ch['dis_rate']!=''){
    																				$discount_rate.=$div.''.$ch['dis_rate'];
    																				$discount_total_r_child+=($ch['genqty']*$ch['dis_rate']);
    																				$c_discount_rate +=$ch['dis_rate'];
    																			}
    																			$net_w.=$div.''.$ch['net_weight'];
    																			
    																			$tot_qty=$tot_qty+$ch['genqty'];
    																			$tot_netW = $tot_netW + $ch['net_weight'];
    																			$o_no .= $div.''.$ch['buyers_o_no'];
    																			$ref_number .= $div.''.$ch['ref_no'];
    																			$item_number .=  $div.''.$ch['item_no'];
    																			//$tot_net_weight = $tot_netW;
    																			
    																		//printr($rate);	printr($qty);	
    																		}
    																	}
    																	$tot_ch_all_rate=$total_r_child+$total_r;
    																	
    																	//printr($description);
    																	//$gross_weight=$color['net_weight']+$color['box_weight'];
    																	$gross_weight=$tot_netW+$color['box_weight'];
    																	
    																	//echo $gross_weight;
    																	
    																	$tot_qty=$color['genqty']+$tot_qty;
    																	
    																	$tot_gross_weight=$gross_weight+$tot_gross_weight;
    																	
    																	$tot_net_weight=$tot_netW+$tot_net_weight;
    																	
    																	$tot_rate=$tot_rate+$color['rate']+$c_rate;
    																	$tot_discount_rate = $tot_discount_rate + $color['dis_rate']+$c_discount_rate;
    																	$tot_amt=$tot_ch_all_rate+$tot_amt;
    																	if(isset($color['box_no']) && $color['box_no']!=0)
    																	{
    
    																		$box_no=$color['box_no'];
    																	}
    																	else
    																	{
    																		$box_no='';
    																	}
    																
    																	$setHtml .='<tr>';
    																	//printr($invoice);
    																	//$cnt = sizeof($invoice).'invoice';
    																	//echo $cnt;
    																			if($status==0)
    																			{
    																				
    																				$setHtml .='<td style="text-align:center"><input type="text" class="form-control  validate[required]"  name="gen_id'.$i.'_page_no'.($page_no+1).'"  onblur="edit_box_no('.$i.','.($page_no+1).')" id="gen_id'.$i.'_page_no'.($page_no+1).'" style="width:auto;" value="'.$box_no.'"  />						 
    																								 <input type="hidden"  name="gen_unique_id'.$i.'_page_no'.($page_no+1).'" id="gen_unique_id'.$i.'_page_no'.($page_no+1).'" value="'.$color['in_gen_invoice_id'].'"  />
    																							</td>';
    																										
    																			}
    																			else
    																				$setHtml .='<td style="text-align:center">'.$box_no.'</td>';
    																			
    																			$end=$box_no;
    																		
    																			if($invoice['country_destination']==253)
    																				$setHtml .='<td style="text-align:center"">'.$color['ref_no'].'</td>';
    																			
    																	
    																			if($invoice['country_destination']==253)
    																				$setHtml.='<td colspan="3" style="text-align:center">'.$size.' '.$description.'</td>';
    																			else
    																				$setHtml .='<td colspan="3" style="text-align:center">'.$description.'</td>';
    																				
    																			if($invoice['country_destination']=='253' )
    																					$setHtml.='<td style="text-align:center">'.$color['item_no'].'</td>';
    																						
    																			if($invoice['country_destination']!=253)
    																					$setHtml.='<td  colspan="2" style="text-align:center">'.$size.'</td>';
    																					
    																					$setHtml.='<td style="text-align:center">'.$qty.'</td>
    																					<td style="text-align:center">'.number_format($gross_weight,3).'</td>
    																					<td style="text-align:center">'.$net_w.'</td>';
    																					
    																			if($invoice['country_destination']=='170' && $price==0 )
    																			{
    																				$row='';
    																				if($i == '1')
    																				{
    																					$row = 'rowspan="'.count($colordetails).'"';
    																					$setHtml.='<td '.$row.' style="vertical-align: middle;"><b>Invoice No.'.$invoice['invoice_no'].'/'.(date('y')).'-'.(date('y')+1).'</b></td>';
    																				}
    																				//$setHtml.='<td '.$row.' style="vertical-align: middle";><b>Invoice No.'.$invoice['invoice_no'].'/'.(date('y')).'-'.(date('y')+1).'</b></td>';	
    																					$setHtml.='<td style="text-align:center" >'.$item_number.'</td>';
    																			}
    																			
    																			if($price==1)
    																			{
    																				$rate_for_print=number_format($color['rate'],3);
    																				
    																				$setHtml.='<td style="text-align:center">'.$rate.' </td>';//.number_format((float)$rate,3).
    																					if(!empty($menu_id) || !empty($menu_admin_permission))
    																					{
    																						$setHtml.='<td style="text-align:center">'.number_format($discount_rate,3).'</td>';
    																					}
    																				$setHtml.='<td style="text-align:center">'.number_format($tot_ch_all_rate,3).'</td>';
    																							
    																			}
    //printr($invoice['country_destination']);
    																			if($invoice['country_destination']!=253)
    																			{
    																				//$setHtml .='<td>'.$color['buyers_o_no'].'</td>';
    																				 
    																				//if($bono!=$color['buyers_o_no'])
    																				//{
    																						//rowspan="'.$ct.'"
    																					$setHtml .='<td style="text-align:center">';
    																					//$setHtml .='<div valign="center">'.$color['buyers_o_no'].'</div>';
    																					
    																				    //if($invoice['country_destination']=='252'|| $invoice['country_destination']=='238'||$invoice['country_destination']=='129'||$invoice['country_destination']=='251'||$invoice['country_destination']=='129'|| $invoice['country_destination']=='90'||$invoice['country_destination']=='170'|| $invoice['country_destination']=='172'||$invoice['country_destination']=='230'||$invoice['country_destination']=='253'||$invoice['country_destination']=='209')
    																				// && $invoice['country_destination']!='42' 04-01-2018
    																				
    																				  if($ref_number!=0){
                                                            						       $ref_number=$ref_number;
                                                            						    }else{
                                                            						       $ref_number= $o_no;
                                                            						    }
    						
    																					if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='42' && $invoice['country_destination']!='155')	
    																						$setHtml .=$ref_number;
    																					else
    																					 	$setHtml .=$o_no;
    																					 	
    																					$setHtml .='</td>';
    																					
    																				//}
    																		
    																					$bono=$color['buyers_o_no'];
    																			}
    																			
    																			if($status==0)
    																			{
    																				$setHtml .='<td>
    																								<a class="btn btn-danger btn-sm" id="'.$color['in_gen_invoice_id'].'" href="javascript:void(0);">
    																								<i class="fa fa-trash-o"></i></a>
    																								<a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" id="addmore" data-original-title="Add Box Detail" onclick="add_box('.$invoice_no.','.$i.','.$color['in_gen_invoice_id'].','.$color['box_weight'].')"><i class="fa fa-plus"></i></a>
    																							</td>';
    																			}
    																	
    															$setHtml .='</tr>';
    																	$description='';
    																	$i++;	
    																}
    													//	printr($tot_inv);
    													
    														$sri_charge=0;
    														if($price==1)
    														{
    														    //	printr($box_no);
    														    	//printr($tot_inv);
    														
    															if($tot_inv==$box_no)
    															{
    															     //	printr($invoice['extra_tran_charges']);
    																if($invoice['cylinder_charges']!='0.00')
    																{
    																    
    																	if(($box_no+1)==($tot_inv+1))
    																	{
    																			$setHtml.='<tr>
    																							<td style="text-align:center"></td>
    																							<td style="text-align:center" colspan="3">Cylinder Making Charges</td>
    																							<td style="text-align:center" colspan="6"></td>
    																							<td style="text-align:center" colspan="5">'.$invoice['cylinder_charges'].'</td>
    																						</tr>';
    																	}
    																	
    																}
    																if($invoice['tool_cost']!='0.00')
    																{
    																	if(($box_no+1)==($tot_inv+1))
    																	{
    																			$setHtml.='<tr>
    																							<td style="text-align:center"></td>
    																							<td style="text-align:center" colspan="3">Set Up Cost</td>
    																							<td style="text-align:center" colspan="6"></td>
    																							<td style="text-align:center" colspan="5">'.$invoice['tool_cost'].'</td>
    																						</tr>';
    																	}
    																	
    																}
    																if($invoice['invoice_id']=='1809')
    																{
    																    $sri_charge='1200';
    																    $setHtml.='<tr>
    																							<td style="text-align:center"></td>
    																							<td style="text-align:center" colspan="3">Design Charges</td>
    																							<td style="text-align:center" colspan="6"></td>
    																							<td style="text-align:center" colspan="5">1200</td>
    																						</tr>';
    																}
    																$tra =$invoice['extra_tran_charges'] ;
    																
    															if($invoice['invoice_date']<'2018-10-12'){	
    																if(($invoice['country_destination']==172  || $invoice['country_destination']==253) &&  ucwords(decode($invoice['transportation']))=='Air')//|| $invoice['country_destination']==125 
        														    {
        																if($invoice['tran_charges']!=0 )
        																{
        																
        														            if(($box_no-1)==($tot_inv-1))
        																	{
        																		/*	$setHtml.='<tr>
        																							<td style="text-align:center"></td>
        																							<td style="text-align:center" colspan="3">Air Fright Charges</td>
        																							<td style="text-align:center" colspan="6"></td>
        																							<td style="text-align:center" colspan="5">'.$invoice['tran_charges'].'</td>
        																						</tr>';*/
        																	}
        																	
        														        }
        														      
        														        $tra = $invoice['tran_charges'];
    																}
    															    
    															}
    															//	printr($invoice); 
																  if($invoice['extra_tran_charges']!=0.00)
    																{//printr($invoice); 
    																
    														            if(($box_no-1)==($tot_inv-1))
    																	{
    																			$setHtml.='<tr>
    																							<td style="text-align:center"></td>
    																							<td style="text-align:center" colspan="3">Extra Air Fright Charges</td>
    																							<td style="text-align:center" colspan="6"></td>
    																							<td style="text-align:center" colspan="5">'.$invoice['extra_tran_charges'].'</td>
    																						</tr>';
    																	}
    																	
    														        }
    																if($invoice['country_destination']!=252)
    																{
    																    $tot_amt_show = $tot_amt+$invoice['cylinder_charges']+$tra;
    																    
    																}
    																else
    																{
    																    $tot_amt_show = $tot_amt+$tra;
    																}
    																if($invoice['country_destination']==252 || $invoice['order_user_id']==2)
                                                					{
                                                					    $tot_show = $tot_amt+$tra+$sri_charge;
                                                					    
                                                					 //   printr($tot_amt);
                                                					       if(ucwords(decode($invoice['transportation']))=='Air')
                                                    					   {
                                                    					       $insurance_rate= $total_amt_val - $total_amt_scoop - $total_amt_roll -$total_amt_mailer - $total_amt_sealer - $total_amt_paper - $total_amt_storezo - $total_amt_con - $total_amt_gls;

                                                    					       $insurance=(($insurance_rate*110/100+$insurance_rate)*0.07)/100;
                                                    					       
                                                    					      // printr($tot_show);
                                                    					       $tot_show = $tot_show + $invoice['cylinder_charges']+ $insurance;
                                                    					   }
                                                    					   else
                                                    					        $tot_show = $tot_show + $invoice['cylinder_charges'];
                                                					}
                                                					else{
                                                					     //  $tot_show = $tot_amt+$invoice['cylinder_charges']+$tra+$sri_charge+$invoice['tool_cost']; comment by sonu 24-10-2019 beacuse duble time plus cylinder in packing list 
                                                					    $tot_show = $tot_amt+$invoice['cylinder_charges']+$tra+$sri_charge+$invoice['tool_cost'];
                                                					}
    																//$tot_show = $tot_amt+$invoice['cylinder_charges']+$tra;//
    																$tot_amt = $tot_amt_show;
    																//printr($tot_amt_show.'=='.$tot_amt);
    																//printr($tot_amt.'='.$invoice['cylinder_charges'].'+'.$tra);
    															}
    															else
    															{
    																$tot_show = $tot_amt+$sri_charge+$invoice['tool_cost'];
    																//printr($tot_show);
    															}
    														}
    														//$tot_show+=$tot_show
    														
    															$setHtml.='<tr>
    																			<td style="text-align:center" colspan="'.$colspan.'">&nbsp;</td>
    																	   </tr> 
    																	   <tr>
    																		   <td></td>';
    																			if($invoice['country_destination']==253)
    																			 	 $setHtml.='<td style="text-align:center"></td><td style="text-align:center"></td><td style="text-align:center"></td>';						 
    																				 
    																				 $setHtml.='<td style="text-align:center"><strong>Total</strong></td>';
    																			if($invoice['country_destination']==253)				
    																				 $setHtml.='<td style="text-align:center"></td>';	
    																				
    																				if($invoice['country_destination']!=253)
    																					$setHtml.='<td style="text-align:center" colspan="4"></td>';				  
    																			  
    																			   $setHtml.='<td style="text-align:center"><strong>'.$tot_qty.'</strong></td>
    																			   			  <td style="text-align:center"><strong>'.number_format($tot_gross_weight,3).'</strong></td>
    																			   			  <td style="text-align:center"><strong>'.number_format($tot_net_weight,3).'</strong></td>';
    																				
    																				if($price==1)
    																				{
    																					$setHtml.= '<td style="text-align:center"><strong></strong></td>';//'.$tot_rate.'
    																						if(!empty($menu_id) || !empty($menu_admin_permission))
    																							{
    																								$setHtml.='<td style="text-align:center"><strong></strong></td>';//'.$tot_discount_rate.'
    																							}
    																								
    																						$setHtml.= '<td style="text-align:center"><strong>'.number_format($tot_show,2).'</strong></td>';
    																				}
    																				
    																			   if($invoice['country_destination']!=253)
    																					$setHtml.='<td style="text-align:center"></td>';
    																						
    																				if($status==0)		
    																					$setHtml.='<td style="text-align:center"></td>';
    																$setHtml.='</tr>';
    												}
    
    															$collapse_data[]=array('box_no'=>($start+1).' To '.$end,
    																					'page_no'=>($page_no+1),
    																					'qty'=>$tot_qty,
    																					'gross_weight'=>$tot_gross_weight,
    																					'net_weight'=>$tot_net_weight,
    																					'total_amount'=>$tot_show,									
    															);
    															/*if($color['product_id']!='11' && $color['product_id']!='6' && $color['product_id']!='10' && $color['product_id']!='23' && $color['product_id']!='18' && $color['product_id']!='34')
    															{
    																$pouch_detail['pouch']=array('total_box'=>'');
    																
    															}*/
    															
    														/*	$detail_product_sea[]=array('Pouch Details'=>'',
    															
    															);*/
    														//	printr($collapse_data);
    										$setHtml.='</tbody>
    									</table>
    							</div>
    						</div>
    				';
    			}
    			
    		}
		//}//printr($collapse_data);
		if($show_status==1)
		{
    		/*if(isset($totalboxes) && !empty($totalboxes))
            {
            	$tot_inv=count($totalboxes);
            	$per_page=30;
            	$total_pages=$tot_inv/$per_page;
            	for($page_no=0;$page_no<$total_pages;$page_no++)
            	{
            		$start=$per_page*$page_no;
            		$end=$start+$per_page;
            		if($tot_inv>30)
            		{
            			if($page_no==0)
            				$limit=' LIMIT 30 OFFSET 0';
            			else
            				$limit=' LIMIT 30 OFFSET  '.$start;
            		}
            		else
            				$limit='';
            	
            	$colordetails=$this->colordetails($invoice_no,$parent_id,'','',$limit,'');
            	$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=0 ;$tot_discount_rate  =$tot_amt_show=$tot_show=$total_amt_air=0;
            	if(isset($colordetails) && !empty($colordetails))
            	{//	printr($colordetails);
            		$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=$tot_netW=0;
            		
            		foreach($colordetails as $key=>$color)
            		{
            			$childBox=$this->colordetails($invoice_no,$color['in_gen_invoice_id']);
            			$net_w = $tot_netW = $color['net_weight'];
            			$child_qty=0;$total_r_child=$c_rate=$discount_total_r_child = $c_discount_rate =0 ;
            			$total_r=$color['genqty']*$color['rate'];
            			if(isset($childBox) && !empty($childBox))
            			{
            				foreach($childBox as $ch)
            				{
            					$net_w.=$div.''.$ch['net_weight'];
            					$tot_qty=$tot_qty+$ch['genqty'];
            					$tot_netW = $tot_netW + $ch['net_weight'];
            				}
            			}
            			$tot_ch_all_rate=$total_r_child+$total_r;
            			$gross_weight=$tot_netW+$color['box_weight'];
            			$tot_qty=$color['genqty']+$tot_qty;
            			$tot_gross_weight=$gross_weight+$tot_gross_weight;
            			$tot_net_weight=$tot_netW+$tot_net_weight;
            			$tot_rate=$tot_rate+$color['rate']+$c_rate;
            			$tot_discount_rate = $tot_discount_rate + $color['dis_rate']+$c_discount_rate;
            			$tot_amt=$tot_ch_all_rate+$tot_amt;
            			if(isset($color['box_no']) && $color['box_no']!=0)
            			{
            
            				$box_no=$color['box_no'];
            			}
            			else
            			{
            				$box_no='';
            			}
            			$end=$box_no;
            			if($price==1)
            			{ //printr($invoice['country_destination']);
            			
            				if($tot_inv==$box_no)
            				{
            					$tra = 0;
            					if(($invoice['country_destination']==172 || $invoice['country_destination']==253 ) && ucwords(decode($invoice['transportation']))=='Air')//|| $invoice['country_destination']==125 
            					{
            						$tra = $invoice['tran_charges'];
            					}
            					if($invoice['country_destination']!=252)
            					{
            						$tot_amt_show = $tot_amt+$invoice['cylinder_charges']+$tra;
            					}
            					else
            					{
            						$tot_amt_show = $tot_amt+$tra;
            					}
            					$tot_show = $tot_amt+$invoice['cylinder_charges']+$tra;
            					$tot_amt = $tot_amt_show;
            					//printr($tot_show);
            					//printr($tot_show.'='.$invoice['cylinder_charges'].'+'.$tra);
            				}
            				else
            				{
            					$tot_show = $tot_amt;
            					//printr($tot_show);
            				}
            				
            			}
            		}
            	}
            	$collapse_data[]=array('box_no'=>($start+1).' To '.$end,
            				'page_no'=>($page_no+1),
            				'qty'=>$tot_qty,
            				'gross_weight'=>$tot_gross_weight,
            				'net_weight'=>$tot_net_weight,
            				'total_amount'=>$tot_show,									
            	);
            	}
            }*/
            $setHtml = '';
		}
	 //$setHtml.='<br class="break"">';
	//	printr($collapse_data);<br style="page-break-before: always;">  <form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
    
    
     $cylinder_details = "SELECT ic.*  FROM  " . DB_PREFIX . "invoice_test as i,invoice_color_test as ic WHERE  i.invoice_id =ic.invoice_id AND i.invoice_id = '" .(int)$invoice_no. "' AND i.is_delete=0 AND cylinder_rate!=0.00 AND no_of_cylinder!=0 AND ic.color='-1'";
    $cylinder_html='';
		$cylinder_data = $this->query($cylinder_details);
         if($cylinder_data->num_rows){
             
             	
       
        $cylinder_html.='<div class="table-responsive"><br>
        						<div style="width:730px;">
        							<table class="table b-t table-striped text-small table-hover detail_table"> 
        								<thead>							
        									<tr>
        										<th style="text-align:center" style="width:150px">Name of Cylinder</th>  
        										<th style="text-align:center" style="150px">No Of Cylinder</th>
        										<th style="text-align:center" style="width:100px">One Cylinder Price</th>
        										<th style="text-align:center" style="width:100px">Total Price</th>
        									</tr>
        								</thead>
        							';
                foreach($cylinder_data->rows as $cylinder){
                $cylinder_html.='<tbody>							
							<tr>
								<th style="text-align:center" style="width:150px">'.$cylinder['color_text'].'</th>  
								<th style="text-align:center" style="150px">'.$cylinder['no_of_cylinder'].'</th>
								<th style="text-align:center" style="width:100px">'.$cylinder['cylinder_rate'].'</th>
								<th style="text-align:center" style="width:100px">'.($cylinder['no_of_cylinder']*$cylinder['cylinder_rate']).'</th>
							</tr>
					    </tbody>
                                                        									';
            }} 
    
    
    
    	 $cylinder_html.='</table></div></div>';
		//	 echo $cylinder_html;
			 $setHtml.=$cylinder_html;
										
		$setHtml.='
						<div class="table-responsive"><br>
								<div style="width:730px;">
									<table class="table b-t table-striped text-small table-hover detail_table"> 
										<thead>							
											<tr>
												<th style="text-align:center" style="width:150px">Box Nos.</th>  
												<th style="text-align:center" style="150px">Page No.</th>
												<th style="text-align:center" style="width:100px">Quantity</th>
												<th style="text-align:center" style="width:100px">Gr. Wt in kgs</th>
												<th  style="text-align:center" style="width:100px">Net. Wt in kgs</th>';
												if($price==1)
													$setHtml.='<th  style="text-align:center" style="width:100px">Extended cost</th>
											</tr>
										</thead>
										<tbody>';
										$colordetails=$this->colordetails($invoice_no,$parent_id,'','',$limit);
									
										$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_amt1=0;
										if(isset($collapse_data))
										{
											foreach($collapse_data as $dat)
											{
												$setHtml.='<tr>
																<td style="text-align:center">'.$dat['box_no'].'</td>
																<td style="text-align:center">'.$dat['page_no'].'</td>
																<td style="text-align:center">'.$dat['qty'].'</td>
																<td style="text-align:center">'.number_format($dat['gross_weight'],3).'</td>
																<td style="text-align:center">'.number_format($dat['net_weight'],3).'</td>';
																
																
																if($price==1)
																{
																	if($invoice['country_destination']==252)
																	    $setHtml.='<td style="text-align:center">'.number_format($dat['total_amount'],2).'</td>';
																	else
																	    $setHtml.='<td style="text-align:center">'.number_format($dat['total_amount'],2).'</td>';
																}
																	
															$setHtml.='</tr>
															';
												$tot_qty=$tot_qty+$dat['qty'];
												$tot_gross_weight=$tot_gross_weight+$dat['gross_weight'];
												$tot_net_weight=$tot_net_weight+$dat['net_weight'];
												$tot_amt1=$tot_amt1+$dat['total_amount'];
												//printr($tot_amt1.' === in loop'.$dat['total_amount']);
												
												
											}
										}	
										//printr($tot_amt1);
									//sonu add 14-4-2017 
    										
        									//[kinjal] END
        										//sonu END
										$setHtml.='<tr>
														<td style="text-align:center"></td>
														<td style="text-align:center"></td>
														<th style="text-align:center">'.$tot_qty.'</th>
														<th style="text-align:center">'.number_format($tot_gross_weight,3).'</th>
														<th style="text-align:center">'.number_format($tot_net_weight,3).'</th>';
													//add sonu 14-4-201
														$insurance_rate =0;
													//end	
														if(($invoice['country_destination']==252 ||  $invoice['order_user_id']==2  ) && ucwords(decode($invoice['transportation']))=='Air')
														{
															//tran_charges_tot=round($invoice['tran_charges'],2);
													        //	printr($invoice['tran_charges'].'+'.$tot_amt);
													       //printr($tot_amt);
													       $insurance_rate= $tot_amt1 - $total_amt_scoop - $total_amt_roll -$total_amt_mailer - $total_amt_sealer - $total_amt_paper - $total_amt_storezo - $total_amt_con - $total_amt_gls;
													       // printr($insurance_rate);
														    
														    $insurance_rate = $insurance_rate - $invoice['cylinder_charges'];
														     
														//	$insurance=(($tot_amt*110/100+$tot_amt)*0.07)/100;  sonu comment 14-4-2017
														   
														    $insurance=(($insurance_rate*110/100+$insurance_rate)*0.07)/100;
														
															//printr($insurance_rate.'*110/100+'.$insurance_rate.'0.07/100');
														
															$tot_amt1 = $tot_amt1;//
															//printr($insurance);
														}
														else if(($invoice['country_destination']==172|| $invoice['country_destination']==125 || $invoice['country_destination']==253 ) && ucwords(decode($invoice['transportation']))=='Air')
														{
														   $tot_amt1 = $tot_amt1;	
														}
														else
															$tot_amt1 = $tot_amt1;//+$invoice['cylinder_charges']	
														//printr($invoice['cylinder_charges']);
														if($price==1)
															$setHtml.='<th style="text-align:center">'.number_format($tot_amt1,2).'</th>
													</tr>
									</tbody>
								</table>
							</div>
						</div>
					';
		//sonu add 24/12/2016 </form>
		//printr($invoice);
	 if(decode($invoice['transportation'])== 'sea')
	 {	
		   //<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
		   $setHtml.='
							<div class="table-responsive"><br>
									<div style="width:730px;">
										<table class="table b-t table-striped text-small table-hover detail_table"> 
											<thead>							
												<tr>
													<th style="text-align:center" style="width:150px">Detail</th>  
													<th style="text-align:center"  style="150px">Total Boxes</th>
													<th style="text-align:center" style="width:100px">Quantity</th>
													<th  style="text-align:center"style="width:100px">G.W.T</th>
													<th  style="text-align:center" style="width:100px">N.W.T</th>';
													if($price==1)
														$setHtml.='<th style="text-align:center"  style="width:100px">Extended cost '.$currency['currency_code'].'</th>
													
												</tr>
											</thead>
											<tbody>';
											$alldetails=$this->getProductdeatils($invoice_no);
											
											$f_p=$f_scoop=$f_roll=$f_mailer=$f_sealer=$f_storezo=$f_box=$f_con=$f_oxygen_absorbers=$f_silica_gel=$f_chair=$f_val=true;
											foreach($alldetails as $detail)
											{
												if($detail['product_id']!='11' && $detail['product_id']!='6' && $detail['product_id']!='10' && $detail['product_id']!='23' && $detail['product_id']!='18' && $detail['product_id']!='34' && $detail['product_id']!='47' && $detail['product_id']!='48' && $detail['product_id']!='63' && $detail['product_id']!='72' && $detail['product_id']!='37' && $detail['product_id']!='38')
												{
													
													$charge = $invoice['tran_charges']+$invoice['cylinder_charges'];										
													$boxDetail = $this->getIngenBox($invoice_no,$n=2,$charge);
														$boxDetail['product_id']=$detail['product_id'];
													//printr($boxDetail);
													if($f_p==true)
													{
														$pouch_detail['Pouch Detail']=$boxDetail;
														$f_p=false;
													}
													
												}
												else if($detail['product_id']=='11')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);

                                                	$boxDetail['product_id']=$detail['product_id'];
													if($f_scoop==true)
													{
														$pouch_detail['scoop']=$boxDetail;
														$f_scoop=false;
													}
												}
												else if($detail['product_id']=='6')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
														$boxDetail['product_id']=$detail['product_id'];
													$boxDetail['product_id']='6';
													if($f_roll==true)
													{
														$pouch_detail['roll']=$boxDetail;
														$f_roll=false;
													}
												//	printr($boxDetail);
												}
												else if($detail['product_id']=='10')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
														$boxDetail['product_id']=$detail['product_id'];
													if($f_mailer==true)
													{
														$pouch_detail['Mailer Bag']=$boxDetail;
														$f_mailer=false;
													} 
												}
												else if($detail['product_id']=='23')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_sealer==true)
													{
														$pouch_detail['Sealer Machine']=$boxDetail;
														$f_sealer=false;
													}
												}
												else if($detail['product_id']=='18')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_storezo==true)
													{
														$pouch_detail['Storezo']=$boxDetail;
														$f_storezo=false;
													}
												}
												else if($detail['product_id']=='34')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_box==true)
													{  
														$pouch_detail['Paper Box']=$boxDetail;
														$f_box=false;
													}
												}
												else if($detail['product_id']=='47')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_con==true)
													{
														$pouch_detail['Plastic Disposable Lid / Container']=$boxDetail;
														$f_con=false;
													}
												}
												else if($detail['product_id']=='48')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													
													if($f_gls==true)
													{
														$pouch_detail['Plastic Glasses']=$boxDetail;
														$f_gls=false;
													}
												}
												else if($detail['product_id']=='72')
												{
												   
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_chair==true)
													{
														$pouch_detail['Chair']=$boxDetail;
												        	$f_chair=false;
													}
												//	 printr($pouch_detail);
												}
												else if($detail['product_id']=='37')
												{
												   
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_oxygen_absorbers==true)
													{
														$pouch_detail['oxygen absorbers']=$boxDetail;
												        	$f_oxygen_absorbers=false;
													}
												//	 printr($pouch_detail);
												}
												else if($detail['product_id']=='38')
												{
												   
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_silica_gel==true)
													{
														$pouch_detail['silica gel']=$boxDetail;
												        	$f_silica_gel=false;
													}
												//	 printr($pouch_detail);
												}
												else if($detail['product_id']=='63')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_val==true)
													{
														$pouch_detail['Plastic Cap']=$boxDetail;
														$f_val=false;
													}
												}
											} 
											//$pouch[]=$pouch_detail;
										
											$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_amt=0;
											//	printr($pouch_detail);
											if(isset($pouch_detail))
											{
												foreach($pouch_detail as $key=>$data)
												{
												//	printr($data);
													
													if($data['product_id']==6)
												    	$data['total']=$data['total_amt'];
												    
													$setHtml.='<tr>
																	<td style="text-align:center">'.$key.'</td>
																	<td style="text-align:center">'.$data['total_box'].'</td>
																	<td style="text-align:center">'.$data['qty'].'</td>
																	<td style="text-align:center">'.number_format($data['g_wt'],3).'</td>
																	<td style="text-align:center">'.number_format($data['n_wt'],3).'</td>';
																	
																	if($key=='Pouch Detail')
																		$data['total']=$data['total']+$invoice['cylinder_charges'];
																	
																	if($price==1)
																		$setHtml.='<td style="text-align:center">'.number_format($data['total'],3).'</td>
																</tr>
																';
													$tot_qty=$tot_qty+$data['qty'];
													$tot_gross_weight=$tot_gross_weight+$data['g_wt'];
													$tot_net_weight=$tot_net_weight+$data['n_wt'];
													$tot_amt=$tot_amt+$data['total'];
													
												}
											}
											$setHtml.='<tr>
															<td style="text-align:center"></td>
															<td style="text-align:center"></td>
															<th style="text-align:center">'.$tot_qty.'</th>
															<th style="text-align:center">'.number_format($tot_gross_weight,3).'</th>
															<th style="text-align:center">'.number_format($tot_net_weight,3).'</th>';
															if($price==1)
																$setHtml.='<th style="text-align:center" >'.number_format($tot_amt,2).'</th>
														</tr>
										</tbody>
									</table>
								</div>
							</div>
						';
			 }
			 
		
			 if($status==0){
		            return $arr = array('html'=>$setHtml,
		                        'total_box'=>count($totalboxes));
    		}
    		else
    		    return $setHtml;
		
		return $setHtml;				
	}
	function viewDetailsTest($invoice_no,$status=0,$price=0,$p_break=0,$option=array(),$show_status='0')
	{ //printr($show_status);die;
	    $setHtml='';
		
		$invoice_qty=$this->getInvoiceTotalData($invoice_no);
		$alldetails=$this->getProductdeatils($invoice_no);
		//$tot_qty_scoop=$tot_scoop_qty=$tot_scoop_rate=$total_amt_scoop=0;
		
		//added by [kinjal] on 12-5-2017 for other product in UK contory con 
		$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_no = $con_no =$gls_no=$chair_no =$val_no ='';
		$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series = $con_series =$gls_series =$chair_series =$val_series ='';
		$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper = $total_amt_con =$total_amt_gls=$total_amt_valve=$total_amt_val=$total_amt_chair=0;
		$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty = $tot_paper_qty = $tot_con_qty = $tot_gls_qty =$tot_val_qty=$tot_chair_qty = 0;
		$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_paper_rate = $tot_con_rate =$tot_gls_rate=$tot_chair_rate=$tot_val_rate=0;
		//	printr($alldetails);
			foreach($alldetails as $details){
						if($details['product_id']=='11')
            			{
            				$tot_qty_scoop=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
            				$tot_scoop_qty = $tot_scoop_qty + $tot_qty_scoop['total']; 
            				$tot_scoop_rate = $tot_scoop_rate + $tot_qty_scoop['rate'];
            				$total_amt_scoop = $total_amt_scoop + $tot_qty_scoop['tot_amt'];
            			}
						else if($details['product_id']=='6')
						{
							$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);

						//	printr($tot_qty_roll);
							$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
							$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
						//	$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
							$net_pouches_roll = $this->getIngenBox($invoice_no,$details['product_id'],0);
						
							$total_amt_roll = $net_pouches_roll['total_amt'];
						
						}
						else if($details['product_id']=='10')
						{
							$tot_qty_mailer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_mailer_qty = $tot_mailer_qty + $tot_qty_mailer['total']; 
							$tot_mailer_rate = $tot_mailer_rate + $tot_qty_mailer['rate'];
							$total_amt_mailer = $total_amt_mailer + $tot_qty_mailer['tot_amt'];
						}
						else if($details['product_id']=='23')
						{
							$tot_qty_sealer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_sealer_qty = $tot_sealer_qty + $tot_qty_sealer['total']; 
							$tot_sealer_rate = $tot_sealer_rate + $tot_qty_sealer['rate'];
							$total_amt_sealer = $total_amt_sealer + $tot_qty_sealer['tot_amt'];
						}
						else if($details['product_id']=='18')
						{
							$tot_qty_storezo=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_storezo_qty = $tot_storezo_qty + $tot_qty_storezo['total']; 
							$tot_storezo_rate = $tot_storezo_rate + $tot_qty_storezo['rate'];
							$total_amt_storezo = $total_amt_storezo + $tot_qty_storezo['tot_amt'];
						}
						else if($details['product_id']=='34')
						{
							$tot_qty_paper=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_paper_qty = $tot_paper_qty + $tot_qty_paper['total']; 
							$tot_paper_rate = $tot_paper_rate + $tot_qty_paper['rate'];
							$total_amt_paper = $total_amt_paper + $tot_qty_paper['tot_amt'];
						}
						else if($details['product_id']=='47')
						{
							$tot_qty_con=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_con_qty = $tot_con_qty + $tot_qty_con['total']; 
							$tot_con_rate = $tot_con_rate + $tot_qty_con['rate'];
							$total_amt_con = $total_amt_con + $tot_qty_con['tot_amt'];
						}
						else if($details['product_id']=='48')
						{
							$tot_qty_gls=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_gls_qty = $tot_gls_qty + $tot_qty_gls['total']; 
							$tot_gls_rate = $tot_gls_rate + $tot_qty_gls['rate'];
							$total_amt_gls = $total_amt_gls + $tot_qty_gls['tot_amt'];
						}
						else if($details['product_id']=='72')
						{
							$tot_qty_chair=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_chair_qty = $tot_chair_qty + $tot_qty_chair['total']; 
							$tot_chair_rate = $tot_chair_rate + $tot_qty_chair['rate'];
							$total_amt_chair = $total_amt_chair + $tot_qty_chair['tot_amt'];
						}
						else if($details['product_id']=='63')
						{
							$tot_qty_val=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
							$tot_val_qty = $tot_val_qty + $tot_qty_val['total']; 
							$tot_val_rate = $tot_val_rate + $tot_qty_val['rate'];
							$total_amt_valve = $total_amt_valve + $tot_qty_val['tot_amt'];
						}else{
						    $total_amt_val=$invoice_qty['tot'];
						}
			}
		
	//	printr($tot_qty_roll);
	//	printr($total_amt_roll.'==='.$tot_roll_qty.'=\='.$tot_roll_rate);
		//online : 177 & offline :193
		$menu_id = $this->getMenuPermission(177,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		//printr($menu_id);
		$invoice=$this->getInvoiceNetData($invoice_no);
		$currency=$this->getCurrencyName($invoice['curr_id']);
		if($invoice['country_destination']==170)
			$item_text='Special Code';
		else
			$item_text='Item No.';
			
		$totalboxes=$this->colordetails($invoice_no,0,'','','');	
		$total_box=$this->colordetails($invoice_no,0,'','','',$option);
	//	printr(count($total_box));die;
		//if($show_status==0)
		//{
		    if(isset($total_box) && !empty($total_box))
    		{
    		   /* if($invoice_no=='1420'){
    		        $tot_inv=600;
    		        
    		    }else{*/
    			    $tot_inv=count($total_box);
    		        
    		  //  }
    	//	$tot_inv=count($total_box);
    		//	printr($tot_inv);
    	//	if($invoice['box_limit']!=0)
    		
    			$per_page=$invoice['box_limit'];
    			$total_pages=$tot_inv/$per_page;
    			$setHtml='';
    			//getColorDetailstotalecho $tot_inv.'=='.$per_page;die;
    				for($page_no=0;$page_no<$total_pages;$page_no++)
    				{
    					$start=$per_page*$page_no;
    					//echo $start;
    					$end=$start+$per_page;
    					//echo $end;
    					if($tot_inv>$invoice['box_limit'])
    					{
    						if($page_no==0)
    							$limit=' LIMIT '.$invoice['box_limit'].' OFFSET 0';
    						else
    							$limit=' LIMIT '.$invoice['box_limit'].' OFFSET  '.$start;
    					}
    					else
    							$limit='';
    					
    					$description='';$valve='';$zipper_name='';$gross_weight='';
    				//	printr($price);
    						$style='';
    						if($p_break==1)
    						{
    							if($page_no>0)
    								$style='page-break-before:always;';
    						}
    							//echo $style;
    					$setHtml.='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data" style="'.$style.'">';
    				
    					$setHtml .= '<div class="table-responsive"  style="width:730px;">';
    								if($page_no>0)
    								{
    									$setHtml .= '<div style="width:730px;">';
    									//<br><br><br>   $style='page-break-before:always;';
    								}
    								else
    								{
    									$setHtml .= '<div style="width:730px;">';
    									//$style='';
    									/*if($price==1)
    										$setHtml.='<h1>Packaging Details With Price List</h1>';
    									else
    										$setHtml .= '<h1>Packaging Details List </h1>';*/
    								}
    									/*$style='page-break-before:always;';
    									if($p_break==1)
    										$style='';*/
    										if(!empty($menu_id) || !empty($menu_admin_permission))
    											$i = 13;
    										else
    											$i = 12;
    										
    										$setHtml .='<table class="table b-t table-striped text-small table-hover detail_table" style="width:730px;">';
    											if($price==1)
    											{
    												$colspan=$i;
    												if($invoice['country_destination']==170)
    											    	$colspan=12;
    											}	
    											else
    											{
    												$colspan=10;
    												if($invoice['country_destination']==170)
    											    	$colspan=12;
    											}	
    												$setHtml .=' <thead>';
    														if($page_no=='0')
    														{
    															$setHtml.='<tr><th  colspan="'.$colspan.'" style="text-align:center;">';
    																if($price==1)
    																	$setHtml.='<h2>Packaging Details With Price List</h2>';
    																else
    																	$setHtml .= '<h2>Packaging Details List </h2>';
    															$setHtml.='</th></tr>';
    														}
    														$setHtml .='<tr>
    																	<th  colspan="'.$colspan.'" style="text-align:center;padding:0;"><h4>Detailed Packing List '.($page_no+1).'</h4></th>
    																</tr>
    																<tr>
    																	<th  style="text-align:center" style="width:20px">Box Nos.</th>  ';                    
    																	if($invoice['country_destination']==253)
    																		$setHtml.='<th style="text-align:center" style="width:35px">Buyer\'s Order No</th>';
    																	$setHtml.='<th  style="text-align:center" colspan="3">Product</th>';
    																	//updated on 3-12-2016 [kinjal] if($invoice['final_destination']!=170)
    																	if($invoice['country_destination']=='253')
    																	{
    																		$setHtml.='<th  style="text-align:center"style="width:35px">'.$item_text.'</th>';
    																	}
    																	if($invoice['country_destination']!=253)
    																	    $setHtml.='<th  style="text-align:center"colspan="2">Size</th>';
    																	    
    																	$setHtml.='<th style="text-align:center" style="width:25px">Quantity</th>
    																			   <th style="text-align:center" style="width:35px">Gr. Wt in kgs</th>
    																	           <th  style="text-align:center" style="width:35px">Net. Wt in kgs </th>';
    																	if($invoice['country_destination']==170 && $price==0)
    																	{
    																	    
    																	    
    																		$setHtml.='<th style="text-align:center" style="width:35px">invoice NUMBER</th>';
    																		$setHtml.='<th  style="text-align:center"style="width:35px">'.$item_text.'</th>';
    																	}
    																	if($price==1)
    																	{
    																		$setHtml.='<th style="text-align:center" style="width:25px">Rate</th>';
    																		//add by sonu told by vikas sir 6-4-2017
    																		if(!empty($menu_id) || !empty($menu_admin_permission))
    																			{
    																				$setHtml.='<th  style="text-align:center"style="width:25px">Original Rate</th>';
    																			}
    																		$setHtml.='<th  style="text-align:center" style="width:30px">Total Amount</th>';
    																	}
    																	if($invoice['country_destination']!=253)
    																		$setHtml.='<th style="text-align:center" style="width:35px">Buyer\'s Order No</th>';
    								
    																	if($status==0)
    																		$setHtml .=	'<th style="text-align:center" style="width:35px">Action</th>';
    													$setHtml .=	'</tr>
    														</thead>
    														<tbody>';
    														
    													
    															$parent_id=0;
    															 
    															$colordetails=$this->colordetailsTest($invoice_no,$parent_id,'','',$limit,$option);
    														//	printr($colordetails);
    														//die;	
    															$i=1;
    															$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=0 ;$tot_discount_rate  =$tot_amt_show=$tot_show=$total_amt_air=0;
    															if(isset($colordetails) && !empty($colordetails))
    															{//	printr($colordetails);
    																$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=$tot_netW=0;
    																$bono='';$o_no =$ref_number=$item_number='';
    																$i_color_id='0';
    																foreach($colordetails as $key=>$color1)
    																{
    																    $color=$this->Product_detailTest($invoice_no,$color1['invoice_product_id'],$color1['invoice_color_id']);
                                                                       //  printr($color);
                                                                            $color['genqty']=$color1['genqty'];
                                                                            $color['net_weight']=$color1['net_weight'];
    																    
    																	//	printr($color);
    																	   /* if($i_color_id!=$color['invoice_color_id']){
    																	        
	                                                                           if($color['cylinder_rate']!='0.00' && $color['no_of_cylinder'] !='0'){
	                                                                               
	                                                                             
    	                                                                           $cylinder_html.='<tbody>							
                                                            											<tr>
                                                            												<th style="text-align:center" style="width:150px">'.$color['color_text'].'</th>  
                                                            												<th style="text-align:center" style="150px">'.$color['no_of_cylinder'].'</th>
                                                            												<th style="text-align:center" style="width:100px">'.$color['cylinder_rate'].'</th>
                                                            												<th style="text-align:center" style="width:100px">'.($color['no_of_cylinder']*$color['cylinder_rate']).'</th>
                                                            											</tr>
                                                                    							    </tbody>
                                                        									';
	                                                                               
	                                                                           }
	                                                                            $i_color_id=$color['invoice_color_id'];
    																	    }*/
  
    																	
    																	
    																	
    																	
    																	
    																	
    																	
    																	
    																	//echo count($colordetails);
    																	//printr($color);
    																	$zipper=$this->getZipper(decode($color['zipper']));
    																	//if($zipper['zipper_name']!='No zip')
    																		$zipper_name=$zipper['zipper_name'];
    																	// if($color['valve']!='No Valve')
    																		$valve=$color['valve'];
    																	$childBox=$this->colordetailsTest($invoice_no,$color1['in_gen_invoice_id']);
    																	
    																	$c_name=$color['color'];
    																//	if($color['pouch_color_id'] == '-1')
    																	if($color['color_text']!='')
    																	{
    																		$c_name = $color['color_text'].'   '.$color['color'];
    																	}
    																if($color['filling_details']!=''){
                                                    				    	$filling_details=$color['filling_details'];
                                                    					}else{
                                                    					    $filling_details='';
                                                    					}
    																//	printr('abc'.$c_name);
    																	
    																	
    																	
    																	 	$product_decreption = $this->getProductCode($color['invoice_product_id']);
    																	 	
    																	 	
    																    if($color['product_id'] == '47'||$color['product_id'] == '48' || $color['product_id'] == '72'){
    																        $color['size']=$color['size'];
    																         $description=$product_decreption['description'];
    																    }else{
    																        $color['size']= filter_var($color['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    																        $description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$color['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') '.$filling_details.'' ;
    																    }
    																				
    																	//told by jaimini 17-2-2017 comment by sonu pdf is not working correctly  23-3-2017 
    																	//printr($color['size']);die;
    																	if($color['pouch_color_id'] == '-1')
    																	{
    																		//told by jaimini 30-3-2017
    																		//if($color['size'] =='0')
    																		//{
    																			$size=$color['dimension'];
    																			
    																		//}else{
    																			//$size=$color['size'].' '.$color['measurement'];
    																		//}
    																	}else{
    																	   if($invoice['country_destination']!=253){
    																	    	$size=$color['size'].' '.$color['measurement'];
    																	   }else{
    																	       	$size_us=$color['size'].' '.$color['measurement'];
    																	         $s_us=$this->getsizeForUS($size_us);
    																	       if($s_us!=''){
                                                						              $size=$s_us;
                                                						         }else{
                                                						              $size=$size_us;
                                                						         }
    																	   } 
    																	}
    																	//$size=$color['size'].' '.$color['measurement'];
    																	$qty=$color1['genqty'];
    																	$rate=$color['rate'];
    																	$discount_rate = $color['dis_rate'];
    																	$net_w = $tot_netW = $color['net_weight'];
    																	$child_qty=0;$total_r_child=$c_rate=$discount_total_r_child = $c_discount_rate =0 ;
    																
    																	if($color['product_id']!=6)
    																	    $total_r=$qty*$rate;
    																	else
    																	    $total_r=$color['net_weight']*$rate;
    														
    																	
    																//	printr($color);
    																	 $o_no = $color['buyers_o_no'];
    																	 $ref_number = $color['ref_no'];
    																	 $item_number = $color['item_no'];
    																	 
    																	 $div=' + ';
    																	 if($p_break==1)
    																	 	$div='<div class="line line-dashed m-t-large"></div>';
    																		
        																	if(isset($childBox) && !empty($childBox))
        																	{
        																		foreach($childBox as $ch1)
        																		{// printr($ch);
        																		
        																		
        																		  $ch=$this->Product_detailTest($invoice_no,$ch1['invoice_product_id'],$ch1['invoice_color_id']);
        																		  $ch['genqty']=$ch1['genqty'];
        																		  $ch['net_weight']=$ch1['net_weight'];
        																		  
    																		    if($ch['product_id'] != '47'||$ch['product_id'] != '48')
    																		        $ch['size']=filter_var($ch['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    																		   	if($ch['filling_details']!=''){
                                                            				    	$filling_details_ch=$ch['filling_details'];
                                                            					}else{
                                                            					    $filling_details_ch='';
                                                            					}
    																			
    																			if($color['pouch_color_id'] == '-1' ||  $ch['color_text']!="")
            																	{
            																	   
            																		$c_name_ch = $ch['color_text'].' '.$ch['color'] ;	
            																	
            																	    $size_ch=$ch['dimension'];
            																			
            																		
            																	}
            																else{
            																	        
            																	  
            																	    
            																	     if($invoice['country_destination']!=253){
                																	    	$size_ch=$ch['size'].' '.$ch['measurement'];
                																	   }else{
                																	       	$size_us_c=$ch['size'].' '.$ch['measurement'];
                																	         $s_us_c=$this->getsizeForUS($size_us_c);
                																	       if($s_us!=''){
                                                            						              $size_ch=$s_us_c;
                                                            						         }else{
                                                            						              $size_ch=$size_us_c;
                                                            						         }
                																	   }
            																	
            																		$c_name_ch=$ch['color'];
            																	}
    																		//	printr($color['pouch_color_id']);
    																		//	printr(	$c_name_ch);
    																			$zipper_child=$this->getZipper(decode($ch['zipper']));
    																			$description.=$div.''.$c_name_ch. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$ch['product_name'] ),0,3)).' '.$zipper_child['zipper_name'].' '.$ch['valve'].') '.$filling_details_ch.'' ;			
    																			/*if($ch['dimension']!='')
    																				$size.=' + '.$ch['dimension'];
    																			else*/
    																				$size.=$div.''.$size_ch;
    																			//$size.=' + '.$ch['size'].' '.$ch['measurement'];
    																			if($ch1['genqty']!='')
    																			$qty.=$div.''.$ch1['genqty'];
    																			//$child_qty += $ch['genqty'];
    																			if($ch['rate']!='')
    																			{
    																				$rate.=$div.''.$ch['rate'];
    																				$total_r_child+=($ch['genqty']*$ch['rate']);
    																				$c_rate +=$ch['rate'];
    																			}
    																			if($ch['dis_rate']!=''){
    																				$discount_rate.=$div.''.$ch['dis_rate'];
    																				$discount_total_r_child+=($ch['genqty']*$ch['dis_rate']);
    																				$c_discount_rate +=$ch['dis_rate'];
    																			}
    																			$net_w.=$div.''.$ch['net_weight'];
    																			
    																			$tot_qty=$tot_qty+$ch['genqty'];
    																			$tot_netW = $tot_netW + $ch['net_weight'];
    																			$o_no .= $div.''.$ch['buyers_o_no'];
    																			$ref_number .= $div.''.$ch['ref_no'];
    																			$item_number .=  $div.''.$ch['item_no'];
    																			//$tot_net_weight = $tot_netW;
    																			
    																		//printr($rate);	printr($qty);	
    																		}
    																	}
    																	$tot_ch_all_rate=$total_r_child+$total_r;
    																	
    																	//printr($description);
    																	//$gross_weight=$color['net_weight']+$color['box_weight'];
    																	$gross_weight=$tot_netW+$color1['box_weight'];
    																	
    																	//echo $gross_weight;
    																	
    																	$tot_qty=$color['genqty']+$tot_qty;
    																	
    																	$tot_gross_weight=$gross_weight+$tot_gross_weight;
    																	
    																	$tot_net_weight=$tot_netW+$tot_net_weight;
    																	
    																	$tot_rate=$tot_rate+$color['rate']+$c_rate;
    																	$tot_discount_rate = $tot_discount_rate + $color['dis_rate']+$c_discount_rate;
    																	$tot_amt=$tot_ch_all_rate+$tot_amt;
    																	if(isset($color1['box_no']) && $color1['box_no']!=0)
    																	{
    
    																		$box_no=$color1['box_no'];
    																	}
    																	else
    																	{
    																		$box_no='';
    																	}
    																
    																	$setHtml .='<tr>';
    																	//printr($invoice);
    																	//$cnt = sizeof($invoice).'invoice';
    																	//echo $cnt;
    																			if($status==0)
    																			{
    																				
    																				$setHtml .='<td style="text-align:center"><input type="text" class="form-control  validate[required]"  name="gen_id'.$i.'_page_no'.($page_no+1).'"  onblur="edit_box_no('.$i.','.($page_no+1).')" id="gen_id'.$i.'_page_no'.($page_no+1).'" style="width:auto;" value="'.$box_no.'"  />						 
    																								 <input type="hidden"  name="gen_unique_id'.$i.'_page_no'.($page_no+1).'" id="gen_unique_id'.$i.'_page_no'.($page_no+1).'" value="'.$color['in_gen_invoice_id'].'"  />
    																							</td>';
    																										
    																			}
    																			else
    																				$setHtml .='<td style="text-align:center">'.$box_no.'</td>';
    																			
    																			$end=$box_no;
    																		
    																			if($invoice['country_destination']==253)
    																				$setHtml .='<td style="text-align:center"">'.$color['ref_no'].'</td>';
    																			
    																	
    																			if($invoice['country_destination']==253)
    																				$setHtml.='<td colspan="3" style="text-align:center">'.$size.' '.$description.'</td>';
    																			else
    																				$setHtml .='<td colspan="3" style="text-align:center">'.$description.'</td>';
    																				
    																			if($invoice['country_destination']=='253' )
    																					$setHtml.='<td style="text-align:center">'.$color['item_no'].'</td>';
    																						
    																			if($invoice['country_destination']!=253)
    																					$setHtml.='<td  colspan="2" style="text-align:center">'.$size.'</td>';
    																					
    																					$setHtml.='<td style="text-align:center">'.$qty.'</td>
    																					<td style="text-align:center">'.number_format($gross_weight,3).'</td>
    																					<td style="text-align:center">'.$net_w.'</td>';
    																					
    																			if($invoice['country_destination']=='170' && $price==0 )
    																			{
    																				$row='';
    																				if($i == '1')
    																				{
    																					$row = 'rowspan="'.count($colordetails).'"';
    																					$setHtml.='<td '.$row.' style="vertical-align: middle;"><b>Invoice No.'.$invoice['invoice_no'].'/'.(date('y')).'-'.(date('y')+1).'</b></td>';
    																				}
    																				//$setHtml.='<td '.$row.' style="vertical-align: middle";><b>Invoice No.'.$invoice['invoice_no'].'/'.(date('y')).'-'.(date('y')+1).'</b></td>';	
    																					$setHtml.='<td style="text-align:center" >'.$item_number.'</td>';
    																			}
    																			
    																			if($price==1)
    																			{
    																				$rate_for_print=number_format($color['rate'],3);
    																				
    																				$setHtml.='<td style="text-align:center">'.$rate.' </td>';//.number_format((float)$rate,3).
    																					if(!empty($menu_id) || !empty($menu_admin_permission))
    																					{
    																						$setHtml.='<td style="text-align:center">'.number_format($discount_rate,3).'</td>';
    																					}
    																				$setHtml.='<td style="text-align:center">'.number_format($tot_ch_all_rate,3).'</td>';
    																							
    																			}
    //printr($invoice['country_destination']);
    																			if($invoice['country_destination']!=253)
    																			{
    																				//$setHtml .='<td>'.$color['buyers_o_no'].'</td>';
    																				 
    																				//if($bono!=$color['buyers_o_no'])
    																				//{
    																						//rowspan="'.$ct.'"
    																					$setHtml .='<td style="text-align:center">';
    																					//$setHtml .='<div valign="center">'.$color['buyers_o_no'].'</div>';
    																					
    																				    //if($invoice['country_destination']=='252'|| $invoice['country_destination']=='238'||$invoice['country_destination']=='129'||$invoice['country_destination']=='251'||$invoice['country_destination']=='129'|| $invoice['country_destination']=='90'||$invoice['country_destination']=='170'|| $invoice['country_destination']=='172'||$invoice['country_destination']=='230'||$invoice['country_destination']=='253'||$invoice['country_destination']=='209')
    																				// && $invoice['country_destination']!='42' 04-01-2018
    																				
    																				  if($ref_number!=0){
                                                            						       $ref_number=$ref_number;
                                                            						    }else{
                                                            						       $ref_number= $o_no;
                                                            						    }
    						
    																					if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155')	
    																						$setHtml .=$ref_number;
    																					else
    																					 	$setHtml .=$o_no;
    																					 	
    																					$setHtml .='</td>';
    																					
    																				//}
    																		
    																					$bono=$color['buyers_o_no'];
    																			}
    																			
    																			if($status==0)
    																			{
    																				$setHtml .='<td>
    																								<a class="btn btn-danger btn-sm" id="'.$color['in_gen_invoice_id'].'" href="javascript:void(0);">
    																								<i class="fa fa-trash-o"></i></a>
    																								<a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" id="addmore" data-original-title="Add Box Detail" onclick="add_box('.$invoice_no.','.$i.','.$color['in_gen_invoice_id'].','.$color1['box_weight'].')"><i class="fa fa-plus"></i></a>
    																							</td>';
    																			}
    																	
    															$setHtml .='</tr>';
    																	$description='';
    																	$i++;	
    																}
    													//	printr($tot_inv);
    													
    														$sri_charge=0;
    														if($price==1)
    														{
    														    //	printr($box_no);
    														    	//printr($tot_inv);
    														
    															if($tot_inv==$box_no)
    															{
    															     //	printr($invoice['extra_tran_charges']);
    																if($invoice['cylinder_charges']!='0.00')
    																{
    																    
    																	if(($box_no+1)==($tot_inv+1))
    																	{
    																			$setHtml.='<tr>
    																							<td style="text-align:center"></td>
    																							<td style="text-align:center" colspan="3">Cylinder Making Charges</td>
    																							<td style="text-align:center" colspan="6"></td>
    																							<td style="text-align:center" colspan="5">'.$invoice['cylinder_charges'].'</td>
    																						</tr>';
    																	}
    																	
    																}
    																if($invoice['tool_cost']!='0.00')
    																{ 
    																	if(($box_no+1)==($tot_inv+1))
    																	{
    																			$setHtml.='<tr>
    																							<td style="text-align:center"></td>
    																							<td style="text-align:center" colspan="3">Set Up Cost</td>
    																							<td style="text-align:center" colspan="6"></td>
    																							<td style="text-align:center" colspan="5">'.$invoice['tool_cost'].'</td>
    									 													</tr>';
    																	}
    																	
    																}
    																if($invoice['invoice_id']=='1809')
    																{
    																    $sri_charge='1200';
    																    $setHtml.='<tr>
    																							<td style="text-align:center"></td>
    																							<td style="text-align:center" colspan="3">Design Charges</td>
    																							<td style="text-align:center" colspan="6"></td>
    																							<td style="text-align:center" colspan="5">1200</td>
    																						</tr>';
    																}
    																$tra =$invoice['extra_tran_charges'] ;
    																
    															if($invoice['invoice_date']<'2018-10-12'){	
    																if(($invoice['country_destination']==172  || $invoice['country_destination']==253) &&  ucwords(decode($invoice['transportation']))=='Air')//|| $invoice['country_destination']==125 
        														    {
        																if($invoice['tran_charges']!=0 )
        																{
        																
        														            if(($box_no-1)==($tot_inv-1))
        																	{
        																		/*	$setHtml.='<tr>
        																							<td style="text-align:center"></td>
        																							<td style="text-align:center" colspan="3">Air Fright Charges</td>
        																							<td style="text-align:center" colspan="6"></td>
        																							<td style="text-align:center" colspan="5">'.$invoice['tran_charges'].'</td>
        																						</tr>';*/
        																	}
        																	
        														        }
        														      
        														        $tra = $invoice['tran_charges'];
    																}
    															    
    															}
    															//	printr($invoice); 
																  if($invoice['extra_tran_charges']!=0.00)
    																{//printr($invoice); 
    																
    														            if(($box_no-1)==($tot_inv-1))
    																	{
    																			$setHtml.='<tr>
    																							<td style="text-align:center"></td>
    																							<td style="text-align:center" colspan="3">Extra Air Fright Charges</td>
    																							<td style="text-align:center" colspan="6"></td>
    																							<td style="text-align:center" colspan="5">'.$invoice['extra_tran_charges'].'</td>
    																						</tr>';
    																	}
    																	
    														        }
    																if($invoice['country_destination']!=252)
    																{
    																    $tot_amt_show = $tot_amt+$invoice['cylinder_charges']+$tra;
    																    
    																}
    																else
    																{
    																    $tot_amt_show = $tot_amt+$tra;
    																}
    																if($invoice['country_destination']==252 || $invoice['order_user_id']==2)
                                                					{
                                                					    $tot_show = $tot_amt+$tra+$sri_charge;
                                                					    
                                                					 //   printr($tot_amt);
                                                					       if(ucwords(decode($invoice['transportation']))=='Air')
                                                    					   {
                                                    					       $insurance_rate= $total_amt_val - $total_amt_scoop - $total_amt_roll -$total_amt_mailer - $total_amt_sealer - $total_amt_paper - $total_amt_storezo - $total_amt_con - $total_amt_gls;

                                                    					       $insurance=(($insurance_rate*110/100+$insurance_rate)*0.07)/100;
                                                    					       
                                                    					      // printr($tot_show);
                                                    					       $tot_show = $tot_show + $invoice['cylinder_charges']+ $insurance;
                                                    					   }
                                                    					   else
                                                    					        $tot_show = $tot_show + $invoice['cylinder_charges'];
                                                					}
                                                					else
                                                					    $tot_show = $tot_amt+$invoice['cylinder_charges']+$tra+$sri_charge+$invoice['tool_cost'];
    																
    																//$tot_show = $tot_amt+$invoice['cylinder_charges']+$tra;//
    																$tot_amt = $tot_amt_show;
    																//printr($tot_amt_show.'=='.$tot_amt);
    																//printr($tot_amt.'='.$invoice['cylinder_charges'].'+'.$tra);
    															}
    															else
    															{
    																$tot_show = $tot_amt+$sri_charge+$invoice['tool_cost'];
    																//printr($tot_show);
    															}
    														}
    														//$tot_show+=$tot_show
    														
    															$setHtml.='<tr>
    																			<td style="text-align:center" colspan="'.$colspan.'">&nbsp;</td>
    																	   </tr> 
    																	   <tr>
    																		   <td></td>';
    																			if($invoice['country_destination']==253)
    																			 	 $setHtml.='<td style="text-align:center"></td><td style="text-align:center"></td><td style="text-align:center"></td>';						 
    																				 
    																				 $setHtml.='<td style="text-align:center"><strong>Total</strong></td>';
    																			if($invoice['country_destination']==253)				
    																				 $setHtml.='<td style="text-align:center"></td>';	
    																				
    																				if($invoice['country_destination']!=253)
    																					$setHtml.='<td style="text-align:center" colspan="4"></td>';				  
    																			  
    																			   $setHtml.='<td style="text-align:center"><strong>'.$tot_qty.'</strong></td>
    																			   			  <td style="text-align:center"><strong>'.number_format($tot_gross_weight,3).'</strong></td>
    																			   			  <td style="text-align:center"><strong>'.number_format($tot_net_weight,3).'</strong></td>';
    																				
    																				if($price==1)
    																				{
    																					$setHtml.= '<td style="text-align:center"><strong></strong></td>';//'.$tot_rate.'
    																						if(!empty($menu_id) || !empty($menu_admin_permission))
    																							{
    																								$setHtml.='<td style="text-align:center"><strong></strong></td>';//'.$tot_discount_rate.'
    																							}
    																								
    																						$setHtml.= '<td style="text-align:center"><strong>'.number_format($tot_show,2).'</strong></td>';
    																				}
    																				
    																			   if($invoice['country_destination']!=253)
    																					$setHtml.='<td style="text-align:center"></td>';
    																						
    																				if($status==0)		
    																					$setHtml.='<td style="text-align:center"></td>';
    																$setHtml.='</tr>';
    												}
    
    															$collapse_data[]=array('box_no'=>($start+1).' To '.$end,
    																					'page_no'=>($page_no+1),
    																					'qty'=>$tot_qty,
    																					'gross_weight'=>$tot_gross_weight,
    																					'net_weight'=>$tot_net_weight,
    																					'total_amount'=>$tot_show,									
    															);
    															/*if($color['product_id']!='11' && $color['product_id']!='6' && $color['product_id']!='10' && $color['product_id']!='23' && $color['product_id']!='18' && $color['product_id']!='34')
    															{
    																$pouch_detail['pouch']=array('total_box'=>'');
    																
    															}*/
    															
    														/*	$detail_product_sea[]=array('Pouch Details'=>'',
    															
    															);*/
    														//	printr($collapse_data);
    										$setHtml.='</tbody>
    									</table>
    							</div>
    						</div>
    				';
    			}
    			
    		}
		//}//printr($collapse_data);
		if($show_status==1)
		{
    		/*if(isset($totalboxes) && !empty($totalboxes))
            {
            	$tot_inv=count($totalboxes);
            	$per_page=30;
            	$total_pages=$tot_inv/$per_page;
            	for($page_no=0;$page_no<$total_pages;$page_no++)
            	{
            		$start=$per_page*$page_no;
            		$end=$start+$per_page;
            		if($tot_inv>30)
            		{
            			if($page_no==0)
            				$limit=' LIMIT 30 OFFSET 0';
            			else
            				$limit=' LIMIT 30 OFFSET  '.$start;
            		}
            		else
            				$limit='';
            	
            	$colordetails=$this->colordetails($invoice_no,$parent_id,'','',$limit,'');
            	$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=0 ;$tot_discount_rate  =$tot_amt_show=$tot_show=$total_amt_air=0;
            	if(isset($colordetails) && !empty($colordetails))
            	{//	printr($colordetails);
            		$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=$tot_netW=0;
            		
            		foreach($colordetails as $key=>$color)
            		{
            			$childBox=$this->colordetails($invoice_no,$color['in_gen_invoice_id']);
            			$net_w = $tot_netW = $color['net_weight'];
            			$child_qty=0;$total_r_child=$c_rate=$discount_total_r_child = $c_discount_rate =0 ;
            			$total_r=$color['genqty']*$color['rate'];
            			if(isset($childBox) && !empty($childBox))
            			{
            				foreach($childBox as $ch)
            				{
            					$net_w.=$div.''.$ch['net_weight'];
            					$tot_qty=$tot_qty+$ch['genqty'];
            					$tot_netW = $tot_netW + $ch['net_weight'];
            				}
            			}
            			$tot_ch_all_rate=$total_r_child+$total_r;
            			$gross_weight=$tot_netW+$color['box_weight'];
            			$tot_qty=$color['genqty']+$tot_qty;
            			$tot_gross_weight=$gross_weight+$tot_gross_weight;
            			$tot_net_weight=$tot_netW+$tot_net_weight;
            			$tot_rate=$tot_rate+$color['rate']+$c_rate;
            			$tot_discount_rate = $tot_discount_rate + $color['dis_rate']+$c_discount_rate;
            			$tot_amt=$tot_ch_all_rate+$tot_amt;
            			if(isset($color['box_no']) && $color['box_no']!=0)
            			{
            
            				$box_no=$color['box_no'];
            			}
            			else
            			{
            				$box_no='';
            			}
            			$end=$box_no;
            			if($price==1)
            			{ //printr($invoice['country_destination']);
            			
            				if($tot_inv==$box_no)
            				{
            					$tra = 0;
            					if(($invoice['country_destination']==172 || $invoice['country_destination']==253 ) && ucwords(decode($invoice['transportation']))=='Air')//|| $invoice['country_destination']==125 
            					{
            						$tra = $invoice['tran_charges'];
            					}
            					if($invoice['country_destination']!=252)
            					{
            						$tot_amt_show = $tot_amt+$invoice['cylinder_charges']+$tra;
            					}
            					else
            					{
            						$tot_amt_show = $tot_amt+$tra;
            					}
            					$tot_show = $tot_amt+$invoice['cylinder_charges']+$tra;
            					$tot_amt = $tot_amt_show;
            					//printr($tot_show);
            					//printr($tot_show.'='.$invoice['cylinder_charges'].'+'.$tra);
            				}
            				else
            				{
            					$tot_show = $tot_amt;
            					//printr($tot_show);
            				}
            				
            			}
            		}
            	}
            	$collapse_data[]=array('box_no'=>($start+1).' To '.$end,
            				'page_no'=>($page_no+1),
            				'qty'=>$tot_qty,
            				'gross_weight'=>$tot_gross_weight,
            				'net_weight'=>$tot_net_weight,
            				'total_amount'=>$tot_show,									
            	);
            	}
            }*/
            $setHtml = '';
		}
	 //$setHtml.='<br class="break"">';
	//	printr($collapse_data);<br style="page-break-before: always;">  <form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
    
    
     $cylinder_details = "SELECT ic.*  FROM  " . DB_PREFIX . "invoice_test as i,invoice_color_test as ic WHERE  i.invoice_id =ic.invoice_id AND i.invoice_id = '" .(int)$invoice_no. "' AND i.is_delete=0 AND cylinder_rate!=0.00 AND no_of_cylinder!=0 AND ic.color='-1'";
    $cylinder_html='';
		$cylinder_data = $this->query($cylinder_details);
         if($cylinder_data->num_rows){
             
             	
       
        $cylinder_html.='<div class="table-responsive"><br>
        						<div style="width:730px;">
        							<table class="table b-t table-striped text-small table-hover detail_table"> 
        								<thead>							
        									<tr>
        										<th style="text-align:center" style="width:150px">Name of Cylinder</th>  
        										<th style="text-align:center" style="150px">No Of Cylinder</th>
        										<th style="text-align:center" style="width:100px">One Cylinder Price</th>
        										<th style="text-align:center" style="width:100px">Total Price</th>
        									</tr>
        								</thead>
        							';
                foreach($cylinder_data->rows as $cylinder){
                $cylinder_html.='<tbody>							
							<tr>
								<th style="text-align:center" style="width:150px">'.$cylinder['color_text'].'</th>  
								<th style="text-align:center" style="150px">'.$cylinder['no_of_cylinder'].'</th>
								<th style="text-align:center" style="width:100px">'.$cylinder['cylinder_rate'].'</th>
								<th style="text-align:center" style="width:100px">'.($cylinder['no_of_cylinder']*$cylinder['cylinder_rate']).'</th>
							</tr>
					    </tbody>
                                                        									';
            }} 
    
    
    
    	 $cylinder_html.='</table></div></div>';
		//	 echo $cylinder_html;
			 $setHtml.=$cylinder_html;
										
		$setHtml.='
						<div class="table-responsive"><br>
								<div style="width:730px;">
									<table class="table b-t table-striped text-small table-hover detail_table"> 
										<thead>							
											<tr>
												<th style="text-align:center" style="width:150px">Box Nos.</th>  
												<th style="text-align:center" style="150px">Page No.</th>
												<th style="text-align:center" style="width:100px">Quantity</th>
												<th style="text-align:center" style="width:100px">Gr. Wt in kgs</th>
												<th  style="text-align:center" style="width:100px">Net. Wt in kgs</th>';
												if($price==1)
													$setHtml.='<th  style="text-align:center" style="width:100px">Extended cost</th>
											</tr>
										</thead>
										<tbody>';
										$colordetails=$this->colordetails($invoice_no,$parent_id,'','',$limit);
									
										$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_amt1=0;
										if(isset($collapse_data))
										{
											foreach($collapse_data as $dat)
											{
												$setHtml.='<tr>
																<td style="text-align:center">'.$dat['box_no'].'</td>
																<td style="text-align:center">'.$dat['page_no'].'</td>
																<td style="text-align:center">'.$dat['qty'].'</td>
																<td style="text-align:center">'.number_format($dat['gross_weight'],3).'</td>
																<td style="text-align:center">'.number_format($dat['net_weight'],3).'</td>';
																
																
																if($price==1)
																{
																	if($invoice['country_destination']==252)
																	    $setHtml.='<td style="text-align:center">'.number_format($dat['total_amount'],2).'</td>';
																	else
																	    $setHtml.='<td style="text-align:center">'.number_format($dat['total_amount'],2).'</td>';
																}
																	
															$setHtml.='</tr>
															';
												$tot_qty=$tot_qty+$dat['qty'];
												$tot_gross_weight=$tot_gross_weight+$dat['gross_weight'];
												$tot_net_weight=$tot_net_weight+$dat['net_weight'];
												$tot_amt1=$tot_amt1+$dat['total_amount'];
												//printr($tot_amt1.' === in loop'.$dat['total_amount']);
												
												
											}
										}	
										//printr($tot_amt1);
									//sonu add 14-4-2017 
    										
        									//[kinjal] END
        										//sonu END
										$setHtml.='<tr>
														<td style="text-align:center"></td>
														<td style="text-align:center"></td>
														<th style="text-align:center">'.$tot_qty.'</th>
														<th style="text-align:center">'.number_format($tot_gross_weight,3).'</th>
														<th style="text-align:center">'.number_format($tot_net_weight,3).'</th>';
													//add sonu 14-4-201
														$insurance_rate =0;
													//end	
														if(($invoice['country_destination']==252 ||  $invoice['order_user_id']==2  ) && ucwords(decode($invoice['transportation']))=='Air')
														{
															//tran_charges_tot=round($invoice['tran_charges'],2);
													        //	printr($invoice['tran_charges'].'+'.$tot_amt);
													       //printr($tot_amt);
													       $insurance_rate= $tot_amt1 - $total_amt_scoop - $total_amt_roll -$total_amt_mailer - $total_amt_sealer - $total_amt_paper - $total_amt_storezo - $total_amt_con - $total_amt_gls;
													       // printr($insurance_rate);
														    
														    $insurance_rate = $insurance_rate - $invoice['cylinder_charges'];
														     
														//	$insurance=(($tot_amt*110/100+$tot_amt)*0.07)/100;  sonu comment 14-4-2017
														   
														    $insurance=(($insurance_rate*110/100+$insurance_rate)*0.07)/100;
														
															//printr($insurance_rate.'*110/100+'.$insurance_rate.'0.07/100');
														
															$tot_amt1 = $tot_amt1;//
															//printr($insurance);
														}
														else if(($invoice['country_destination']==172|| $invoice['country_destination']==125 || $invoice['country_destination']==253 ) && ucwords(decode($invoice['transportation']))=='Air')
														{
														   $tot_amt1 = $tot_amt1;	
														}
														else
															$tot_amt1 = $tot_amt1;//+$invoice['cylinder_charges']	
														//printr($invoice['cylinder_charges']);
														if($price==1)
															$setHtml.='<th style="text-align:center">'.number_format($tot_amt1,2).'</th>
													</tr>
									</tbody>
								</table>
							</div>
						</div>
					';
		//sonu add 24/12/2016 </form>
		//printr($invoice);
	 if(decode($invoice['transportation'])== 'sea')
	 {	
		   //<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
		   $setHtml.='
							<div class="table-responsive"><br>
									<div style="width:730px;">
										<table class="table b-t table-striped text-small table-hover detail_table"> 
											<thead>							
												<tr>
													<th style="text-align:center" style="width:150px">Detail</th>  
													<th style="text-align:center"  style="150px">Total Boxes</th>
													<th style="text-align:center" style="width:100px">Quantity</th>
													<th  style="text-align:center"style="width:100px">G.W.T</th>
													<th  style="text-align:center" style="width:100px">N.W.T</th>';
													if($price==1)
														$setHtml.='<th style="text-align:center"  style="width:100px">Extended cost '.$currency['currency_code'].'</th>
													
												</tr>
											</thead>
											<tbody>';
											$alldetails=$this->getProductdeatils($invoice_no);
											
											$f_p=$f_scoop=$f_roll=$f_mailer=$f_sealer=$f_storezo=$f_box=$f_con=$f_val=$f_chair=true;
											foreach($alldetails as $detail)
											{
											    //printr($detail['product_id']);
												if($detail['product_id']!='11' && $detail['product_id']!='6' && $detail['product_id']!='10' && $detail['product_id']!='23' && $detail['product_id']!='18' && $detail['product_id']!='34' && $detail['product_id']!='47' && $detail['product_id']!='48' && $detail['product_id']!='63' && $detail['product_id']!='72')
												{
													
													$charge = $invoice['tran_charges']+$invoice['cylinder_charges'];										
													$boxDetail = $this->getIngenBox($invoice_no,$n=2,$charge);
													//printr($boxDetail);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_p==true)
													{
														$pouch_detail['Pouch Detail']=$boxDetail;
														$f_p=false;
													}
													
												}
												else if($detail['product_id']=='11') 
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
                                                    $boxDetail['product_id']=$detail['product_id'];
													if($f_scoop==true)
													{
														$pouch_detail['scoop']=$boxDetail;
														$f_scoop=false;
													}
												}
												else if($detail['product_id']=='6')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
											//		$boxDetail['product_id']='6';
													if($f_roll==true)
													{
														$pouch_detail['roll']=$boxDetail;
														$f_roll=false;
													}
												//	printr($boxDetail);
												}
												else if($detail['product_id']=='10')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
														$boxDetail['product_id']=$detail['product_id'];
													if($f_mailer==true)
													{
														$pouch_detail['Mailer Bag']=$boxDetail;
														$f_mailer=false;
													} 
												}
												else if($detail['product_id']=='23')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_sealer==true)
													{
														$pouch_detail['Sealer Machine']=$boxDetail;
														$f_sealer=false;
													}
												}
												else if($detail['product_id']=='18')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_storezo==true)
													{
														$pouch_detail['Storezo']=$boxDetail;
														$f_storezo=false;
													}
												}
												else if($detail['product_id']=='34')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_box==true)
													{
														$pouch_detail['Paper Box']=$boxDetail;
														$f_box=false;
													}
												}
												else if($detail['product_id']=='47')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_con==true)
													{
														$pouch_detail['Plastic Disposable Lid / Container']=$boxDetail;
														$f_con=false;
													}
												}
												else if($detail['product_id']=='48')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_gls==true)
													{
														$pouch_detail['Plastic Glasses']=$boxDetail;
														$f_gls=false;
													}
												}
												else if($detail['product_id']=='72')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_chair==true)
													{
														$pouch_detail['Chair']=$boxDetail;
												    	$f_chair=false;
													}
													
												//	printr($boxDetail['product_id']);
												//	printr($pouch_detail);
												}
												else if($detail['product_id']=='63') 
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													$boxDetail['product_id']=$detail['product_id'];
													if($f_val==true)
													{
														$pouch_detail['Plastic Cap']=$boxDetail;
														$f_val=false;
													}
												}
											}
											//$pouch[]=$pouch_detail;
										//	printr($pouch_detail);
											$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_amt=0;
											
											if(isset($pouch_detail))
											{
												foreach($pouch_detail as $key=>$data)
												{
												//	printr($data);
													
													if($data['product_id']==6)
												    	$data['total']=$data['total_amt'];
												    
													$setHtml.='<tr>
																	<td style="text-align:center">'.$key.'</td>
																	<td style="text-align:center">'.$data['total_box'].'</td>
																	<td style="text-align:center">'.$data['qty'].'</td>
																	<td style="text-align:center">'.number_format($data['g_wt'],3).'</td>
																	<td style="text-align:center">'.number_format($data['n_wt'],3).'</td>';
																	
																	if($key=='Pouch Detail')
																		$data['total']=$data['total']+$invoice['cylinder_charges'];
																	
																	if($price==1)
																		$setHtml.='<td style="text-align:center">'.number_format($data['total'],3).'</td>
																</tr>
																';
													$tot_qty=$tot_qty+$data['qty'];
													$tot_gross_weight=$tot_gross_weight+$data['g_wt'];
													$tot_net_weight=$tot_net_weight+$data['n_wt'];
													$tot_amt=$tot_amt+$data['total'];
													
												}
											}
											$setHtml.='<tr>
															<td style="text-align:center"></td>
															<td style="text-align:center"></td>
															<th style="text-align:center">'.$tot_qty.'</th>
															<th style="text-align:center">'.number_format($tot_gross_weight,3).'</th>
															<th style="text-align:center">'.number_format($tot_net_weight,3).'</th>';
															if($price==1)
																$setHtml.='<th style="text-align:center" >'.number_format($tot_amt,2).'</th>
														</tr>
										</tbody>
									</table>
								</div>
							</div>
						';
			 }
			 
		
			 if($status==0){
		            return $arr = array('html'=>$setHtml,
		                        'total_box'=>count($totalboxes));
    		}
    		else
    		    return $setHtml;
		
		return $setHtml;				
	}
	
	function viewConsolidatedSheet($invoice_no)
	{ 
		$consol_data=$this->consol_list($invoice_no);
		//printr($consol_data);die;
		$invoice=$this->getInvoiceNetData($invoice_no);
	
		if($invoice['country_destination']==253 || $invoice['country_destination']==170)
		    $colspan='10';
		else
		    $colspan='9';
		$setHtml='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
		<div class="table-responsive" style="width:730px;">
			<div style="width:730px;">
				<table class="table b-t table-striped text-small table-hover detail_table" border="1"> 
					<thead>		
						<tr>
							<th colspan="'.$colspan.'" style="text-align:center;padding:0;"><h4>Consolidated Item Sheet</h4></th>
						</tr>					
							<tr>
								<th style="width:100px">Box Nos.</th>  
								<th style="width:50px">Total Boxes</th>';
								//if($invoice['country_destination']==253)
							        $setHtml.='	<th style="width:50px">Purchase Order No.</th>';
							    
							$setHtml.='<th style="width:200px">Description</th>';
								if($invoice['country_destination']!=253)
								    $setHtml.='<th style="width:50px">size</th>';
								
							if($invoice['country_destination']==253 || $invoice['country_destination']==170)
						        $setHtml.='<th style="width:50px">Item No</th>';
						
						$setHtml.='<th style="width:50px">Quantity (NOS.)</th>
								<th style="width:100px">Gross Weight (kgs)</th>
								<th style="width:100px">Net Weight (kgs)</th>';
								//if($invoice['country_destination']==253)
					                $setHtml.='<th style="width:50px">Extended Cost USD</th>';
					$setHtml.='</tr>
						</thead>
					<tbody>';
    	$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_rate=$tot_box=$tot_cost=0 ;
    	$remaining=array();$new_box=array();
    	$number =0;
		foreach($consol_data as $dat)
		{	
		    //	printr($invoice['country_destination']);
		    //	printr($dat['invoice_product_id']);
		    	
		    $inv_pro =$this->getInvoiceProductColorId($dat['invoice_product_id']);
		  
			$zipper=$this->getZipper(decode($dat['zipper']));
			$zipper_name=$zipper['zipper_name'];
			$valve=$dat['valve'];
			
			if($dat['color']=='Custom')
		        $c_name=$dat['color_text'];
		    else
		        $c_name=$dat['color'];



	/*		$size= filter_var($dat['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
			
        if($dat['product_id'] == '47'||$dat['product_id'] == '48'|| $dat['product_id'] == '72'){
            $size=$dat['size'];           
        }else{
         	$size= filter_var($dat['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
        }
*/



			$product_decreption=$this->getProductCode($dat['invoice_product_id']);
		
			if($dat['product_id'] == '47'||$dat['product_id'] == '48'||$dat['product_id'] == '72'){
		           $dat['size']=$dat['size'];     
		         $description=$product_decreption['description'];
		    }else{
		       	$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$dat['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
		       	$dat['size']= filter_var($dat['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
		    }
		   //printr($size);			
				$dat['size']=$dat['size'].' '.$dat['measurement'];
 		//	printr($size);

			$total_box_arr=explode(',',$dat['grouped_box_no']);
			
			//$first = reset($total_box_arr);
   		//	$last = end($total_box_arr);
   		
   		//[kinjal] started on 22-2-2018
           
            if($dat['product_id'] == '6')
                $cost =  ($inv_pro['rate'] * $dat['net_weight'] );
            else
                $cost =  ($inv_pro['rate'] * $inv_pro['qty'] );
            
            $k = reset($total_box_arr);
            $arr = $remain= array();
            foreach($total_box_arr as $b)
            { //printr($k.'=='.$b);
                if($k==$b)
                {
                    $arr[]=$k;
                    $k=$k+1;
                }
                else
                { //printr($b);
                    $remain[]=$b;
                    $sql ="SELECT ip.invoice_product_id,ig.in_gen_invoice_id, COUNT(DISTINCT ig.in_gen_invoice_id) AS total_boxes, GROUP_CONCAT(ig.box_no) AS grouped_box_no, SUM(ig.qty) AS qty,SUM(ig.box_weight+ig.net_weight) AS gross_weight,SUM(ig.net_weight) AS net_weight  ,SUM(ic.rate) AS rate,ip.item_no,ip.zipper,ip.valve,p.product_name,ic.size,tm.measurement,pc.color,ic.color_text,ic.dimension,ip.ref_no,ip.buyers_o_no FROM in_gen_invoice_test AS ig , invoice_product_test AS ip,product AS p,invoice_color_test AS ic,template_measurement AS tm,pouch_color AS pc WHERE ig.invoice_id='".$invoice_no."' AND ig.is_delete=0 AND ig.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ig.invoice_color_id=ic.invoice_color_id AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id AND ig.box_no IN (".$b.")";
        		    $data = $this->query($sql);
        		    $dat['qty'] = $dat['qty']-$data->row['qty'];
        		    $cost = $cost - ($data->row['rate'] * $data->row['qty'] );
        		    $dat['gross_weight'] = $dat['gross_weight'] -$data->row['gross_weight'] ;
        		    $dat['net_weight'] = $dat['net_weight'] - $data->row['net_weight'];
                }
            }
            
           // printr($remain);
            $num = count($remain);
            $first = reset($arr);
   			$last = end($arr);
            if(!empty($remain))
                $remaining[] = $remain;
             //END [kinjal]    
		    //printr($remaining);
			$fl = $first;
			if($first!=$last)
			    $fl = $first.' To '.$last;
			    
			if($dat['size']=='' || $dat['size']=='0')
			    $dat['size'] = $dat['dimension'];
			    
			   if($dat['product_id'] == '47'||$dat['product_id'] == '48'||$dat['product_id'] == '72')
			   {
			       $dat['size']= filter_var($product_decreption['volume'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
			   }else{
			   	$dat['size']=$dat['size'];
			   }
			$setHtml.='<tr>';
				$setHtml.='	<td>'.$fl.'</td>
							<td>'.($dat['total_boxes']-$num).'</td>';
					//		printr($invoice['country_destination']);
						if($invoice['country_destination']!=14 && $invoice['country_destination']!=155 && $invoice['country_destination']!=214 && $invoice['country_destination']!=42 ){
						        $setHtml.=' <td>'.$dat['ref_no'].'</td>';
						   } else{
						        $setHtml.=' <td>'.$dat['buyers_o_no'].'</td>';
						   }
						     if($invoice['country_destination']!=253)
						        $setHtml.='<td>'.$description.'</td>';
							else{
						         $s_us=$this->getsizeForUS($dat['size']);
						         
						    //     printr($dat['size']);
						    //     printr($s_us); 
						         if($s_us!=''){
						              $size=$s_us;
						         }else{
						              $size=$dat['size'];
						         }
						         $setHtml.='<td>'.$size.' '.$description.'</td>';
						   }   
                   
               
				if($invoice['country_destination']!=253)
					$setHtml.='	<td>'.$dat['size'].'</td>';
				if($invoice['country_destination']==253  || $invoice['country_destination']==170)
					$setHtml.='	<td>'.$dat['item_no'].'</td>';
					
					$setHtml.='<td>'.$dat['qty'].'</td>
							<td>'.$dat['gross_weight'].'</td>
							<td>'.$dat['net_weight'].'</td>';
							//if($invoice['country_destination']==253)
					                $setHtml.='<td>'.number_format($cost,2).'</td>';
							
			$setHtml.='</tr>';
			
			
    			 $i=0;
    			 
    			 //$new_box=array();
    		if(!empty($remaining) && $remaining!='' )
    		{//printr($total_box_arr);
    			foreach($remaining as $rem)
    		    {
    		        //printr($rem);//printr($rem[0]-1);printr($total_box_arr);
    		        //foreach($rem as $r)
    		        //{
    		            $rem_arr[$rem[0]] = $rem[0];
    		        //}
    		        //printr($total_box_arr);
    		        $find_in_arr = in_array(($rem[0]-1),$total_box_arr);
    		        //printr($number.'before');
    		        $other_box = 0;
    		        if($number+1 == $rem[0])
    		            $other_box = '1';
    		           
    		          //printr($number.'after'); 
    		        $rem_find = in_array($rem[0],$new_box);
    		        //printr($rem_find);printr($new_box);printr($rem[0]);
    		        if(($find_in_arr==1 || $other_box=='1') && empty($rem_find))
                    {        //  printr($find_in_arr.'find_in_arr');printr($other_box.'other_box');   
            		    $f = reset($rem);
               			$l = end($rem);
               			$fl1 = $f;
            			if($f!=$l)
            			    $fl1 = $f.' To '.$l;
            			//printr($fl1);    
               			$box = count($rem);
               			$in = implode(",",$rem);
               		
               			$sql ="SELECT ip.product_id,ip.invoice_product_id,ig.in_gen_invoice_id, COUNT(DISTINCT ig.in_gen_invoice_id) AS total_boxes, GROUP_CONCAT(ig.box_no) AS grouped_box_no, SUM(ig.qty) AS qty,SUM(ig.box_weight+ig.net_weight) AS gross_weight,SUM(ig.net_weight) AS net_weight  ,ic.rate AS rate,ip.item_no,ip.zipper,ip.valve,p.product_name,ic.size,tm.measurement,pc.color,ic.color_text,ic.dimension,ip.ref_no,ip.buyers_o_no FROM in_gen_invoice_test AS ig , invoice_product_test AS ip,product AS p,invoice_color_test AS ic,template_measurement AS tm,pouch_color AS pc WHERE ig.invoice_id='".$invoice_no."' AND ig.is_delete=0 AND ig.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ig.invoice_color_id=ic.invoice_color_id AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id AND ig.box_no IN (".$in.")";
            		    $data = $this->query($sql);
            		   
            		    $zipper1=$this->getZipper(decode($data->row['zipper']));
            			$zipper_name1=$zipper1['zipper_name'];
            			
            			
            				if($data->row['color']=='Custom')
            		        	$c_name1=$data->row['color_text'];
            		         else
            		            $c_name1=$data->row['color'];

            			$description1=$c_name1. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$data->row['product_name'] ),0,3)).' '.$zipper_name1.' '.$data->row['valve'].') ' ;
            		  
            		  // printr($data->row);
            		 
							   $data->row['size'] = filter_var(  $data->row['size'] , FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
						
            		    if(($data->row['size']=='' || $data->row['size']=='0') && $data->row['size']!='0 kg' )
            			    $data->row['size'] = $data->row['dimension'];
            			else
            				$data->row['size'] = $data->row['size'].' '.$data->row['measurement'];

            		      $setHtml.='<tr>';
            	            $setHtml.='	<td>'.$fl1.'</td>
            						    <td>'.$box.'</td>';
            						    
            						    if($invoice['country_destination']!=14 && $invoice['country_destination']!=155 && $invoice['country_destination']!=214 && $invoice['country_destination']==42 )
            						        $setHtml.=' <td>'.$data->row['ref_no'].'</td>';
            						    else
            						        $setHtml.=' <td>'.$data->row['buyers_o_no'].'</td>';
            						        
            						    if($invoice['country_destination']!=253)
            						        $setHtml.='<td>'.$description1.'</td>';
            							else{
            						         $s_us=$this->getsizeForUS($data->row['size']);
            						         if($s_us!=''){
            						              $size=$s_us;
            						         }else{
            						              $size=$data->row['size'];
            						         }
            						         
            						         $setHtml.='<td>'.$size.' '.$description1.'</td>';
            						   }   
            						 
            							if($invoice['country_destination']!=253)
            					$setHtml.='	<td>'.$data->row['size'].'</td>';
            							if($invoice['country_destination']==253  || $invoice['country_destination']==170)
            					$setHtml.='	<td>'.$data->row['item_no'].'</td>';
            					
            					$setHtml.='<td>'.$data->row['qty'].'</td>
            							<td>'.$data->row['gross_weight'].'</td>
            							<td>'.$data->row['net_weight'].'</td>';
            				if($data->row['product_id'] == '6')
            				{
                                $setHtml.='<td>'.number_format($data->row['rate']*$data->row['net_weight'],2).'</td>';
                                $tot_cost = $tot_cost + $data->row['rate']*$data->row['net_weight'];   
            				}
                            else
                            {
                                $setHtml.='<td>'.number_format($data->row['rate']*$data->row['qty'],2).'</td>';
                                $tot_cost = $tot_cost + $data->row['rate']*$data->row['qty'];   
                            }			
            				 //$setHtml.='<td>'.number_format($data->row['rate']*$data->row['qty'],2).'</td>';
            							
            		    	$setHtml.='</tr>';
		            //printr(end($rem));
                    $number = end($rem);
                     //printr($number.'be lated');
                        $tot_qty=$tot_qty+$data->row['qty'];
            			$tot_gross_weight=$tot_gross_weight+$data->row['gross_weight'];
            			$tot_net_weight=$tot_net_weight+$data->row['net_weight'];
            			$tot_rate=$tot_rate+$data->row['rate'];
                        $new_box[$rem[0]] = $f;
                        //printr($new_box);echo 'in last';
                    $i++;    
                    }
    		            
    		    }
    		}
			$tot_qty=$tot_qty+$dat['qty'];
			$tot_gross_weight=$tot_gross_weight+$dat['gross_weight'];
			$tot_net_weight=$tot_net_weight+$dat['net_weight'];
			$tot_rate=$tot_rate+$dat['rate'];
			$tot_box = $tot_box +$dat['total_boxes'];
			$tot_cost = $tot_cost + $cost;
		}
        //sort($remaining);
        sort($new_box);sort($rem_arr);
        //printr($new_box);printr($rem_arr);
	    $arr_diff = array_diff($rem_arr,$new_box);
	    //printr($arr_diff);
	    foreach($arr_diff as $diff)
        {
            $diff_arr[][] = $diff;
        }
	    //printr($diff_arr);
	    if(!empty($arr_diff))
	    {
	       //printr($arr_diff); 
	        foreach($diff_arr as $rem)
    		{
    		    $f = reset($rem);
       			$l = end($rem);
       			$fl1 = $f;
    			if($f!=$l)
    			    $fl1 = $f.' To '.$l;
    		 
       			$box = count($rem);
       			$in = implode(",",$rem);
       	
       			$sql ="SELECT ip.product_id,ip.invoice_product_id,ig.in_gen_invoice_id, COUNT(DISTINCT ig.in_gen_invoice_id) AS total_boxes, GROUP_CONCAT(ig.box_no) AS grouped_box_no, SUM(ig.qty) AS qty,SUM(ig.box_weight+ig.net_weight) AS gross_weight,SUM(ig.net_weight) AS net_weight  ,ic.rate AS rate,ip.item_no,ip.zipper,ip.valve,p.product_name,ic.size,tm.measurement,pc.color,ic.color_text,ic.dimension,ip.ref_no FROM in_gen_invoice_test AS ig , invoice_product_test AS ip,product AS p,invoice_color_test AS ic,template_measurement AS tm,pouch_color AS pc WHERE ig.invoice_id='".$invoice_no."' AND ig.is_delete=0 AND ig.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ig.invoice_color_id=ic.invoice_color_id AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id AND ig.box_no IN (".$in.")";
    		    $data = $this->query($sql);
    		   
    		    $zipper1=$this->getZipper(decode($data->row['zipper']));
    			$zipper_name1=$zipper1['zipper_name'];
    			
    			
    				if($data->row['color']=='Custom')
    		        	$c_name1=$data->row['color_text'];
    		         else
    		             $c_name1=$data->row['color'];
    			$description1=$c_name1. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$data->row['product_name'] ),0,3)).' '.$zipper_name1.' '.$data->row['valve'].') ' ;
    		  
    			   $data->row['size'] = filter_var(  $data->row['size'] , FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
						
            		    if(($data->row['size']=='' || $data->row['size']=='0') && $data->row['size']!='0 kg' )
            			    $data->row['size'] = $data->row['dimension'];
            			else
            				$data->row['size'] = $data->row['size'].' '.$data->row['measurement'];




    		   
    			
    		      $setHtml.='<tr>';
    	            $setHtml.='	<td>'.$fl1.'</td>
    						    <td>'.$box.'</td>';
    						    
    						    if($invoice['country_destination']!=14 && $invoice['country_destination']!=155 && $invoice['country_destination']!=214 && $invoice['country_destination']==42 )
    						        $setHtml.=' <td>'.$dat['ref_no'].'</td>';
    						    else
    						        $setHtml.=' <td>'.$dat['buyers_o_no'].'</td>';
    						        
    						    if($invoice['country_destination']!=253)
    						        $setHtml.='<td>'.$description1.'</td>';
    							else{
    						         $s_us=$this->getsizeForUS($data->row['size']);
    						         if($s_us!=''){
    						              $size=$s_us;
    						         }else{
    						              $size=$data->row['size'];
    						         }
    						         
    						         $setHtml.='<td>'.$size.' '.$description1.'</td>';
    						   }   
    						        
    						
    							if($invoice['country_destination']!=253)
    					$setHtml.='	<td>'.$data->row['size'].'</td>';
    							if($invoice['country_destination']==253  || $invoice['country_destination']==170)
    					$setHtml.='	<td>'.$data->row['item_no'].'</td>';
    					
    					$setHtml.='<td>'.$data->row['qty'].'</td>
    							<td>'.$data->row['gross_weight'].'</td>
    							<td>'.$data->row['net_weight'].'</td>';
    							//if($invoice['country_destination']==253)
    					        if($data->row['product_id'] == '6')
                				{
                                    $setHtml.='<td>'.number_format($data->row['rate']*$data->row['net_weight'],2).'</td>';
                                    $tot_cost = $tot_cost + $data->row['rate']*$data->row['net_weight'];   
                				}
                                else
                                {
                                    $setHtml.='<td>'.number_format($data->row['rate']*$data->row['qty'],2).'</td>';
                                    $tot_cost = $tot_cost + $data->row['rate']*$data->row['qty'];   
                                }
    					               
    					       // $setHtml.='<td>'.number_format($data->row['rate']*$data->row['qty'],2).'</td>';
    							
    		    	$setHtml.='</tr>';
    		    	$tot_qty=$tot_qty+$data->row['qty'];
        			$tot_gross_weight=$tot_gross_weight+$data->row['gross_weight'];
        			$tot_net_weight=$tot_net_weight+$data->row['net_weight'];
        			$tot_rate=$tot_rate+$data->row['rate'];
        			//$tot_cost = $tot_cost + $data->row['rate']*$data->row['qty'];   
    		}
	    }
		$total_cost = $tot_cost;
		 //END [kinjal]
		 
		if($invoice['cylinder_charges']!='0')
		{
		    $setHtml.='<tr>
    						<td style="text-align:center"></td>
    						<td style="text-align:center" colspan="3">Cylinder Making Charges</td>
    						<td style="text-align:center" colspan="3"></td>
    						<td style="text-align:center" colspan="5">'.number_format($invoice['cylinder_charges'],2).'</td>
    					</tr>';
    		$total_cost = $total_cost + $invoice['cylinder_charges'];
		}
	    if($invoice['tool_cost']!='0.00')
		{
		    $setHtml.='<tr>
    						<td style="text-align:center"></td>
    						<td style="text-align:center" colspan="3">Set up Cost</td>
    						<td style="text-align:center" colspan="3"></td>
    						<td style="text-align:center" colspan="5">'.number_format($invoice['tool_cost'],2).'</td>
    					</tr>';
    		$total_cost = $total_cost + $invoice['tool_cost'];
		} 
		$setHtml.='<tr>
						<td></td>
						<td></td>
						<td></td>';
						//if($invoice['country_destination']==253)
						        $setHtml.='<td></td>';
						if($invoice['country_destination']!=253)
				$setHtml.='	<td></td>';
						if($invoice['country_destination']==253  || $invoice['country_destination']==170)
				$setHtml.='	<td></td>';
				$setHtml.='	<th>'.$tot_qty.'</th>
						<th>'.$tot_gross_weight.'</th>
						<th>'.$tot_net_weight.'</th>';
						//if($invoice['country_destination']==253)
		                    $setHtml.='<th>'.number_format($total_cost,2).'</th>';
		$setHtml.='</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>';
		$setHtml.='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
		<div class="table-responsive">
			<div style="width:730px;">
				<table class="table b-t table-striped text-small table-hover detail_table"> 
					<thead>		
							<tr>
								<th style="width:100px"></th>  
								<th style="width:50px"></th>
								<th style="width:200px"></th>
								<th style="width:100px"></th>
								<th style="width:100px"></th>
								<th style="width:50px">Quantity (NOS.)</th>
								<th style="width:100px">Gross Weight (kgs)</th>
								<th style="width:100px">Net Weight (kgs)</th>';
								//if($invoice['country_destination']==253)
						    $setHtml.='<th style="width:50px">Extended Cost USD</th>';
					$setHtml.='</tr>
						</thead>
						<tbody>';
		$setHtml.='<tr>
						<td></td>
						<td>'.$tot_box.'</td>
						<th>Total</th>
						<td></td><td></td>
						<th>'.$tot_qty.'</th>
						<th>'.$tot_gross_weight.'</th> 
						<th>'.$tot_net_weight.'</th>';
						//if($invoice['country_destination']==253)
					        $setHtml.='<th>'.number_format($total_cost,2).'</th>';
			$setHtml.='</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>';
		return $setHtml;			
	}
	
function viewAddress($invoice,$status='')
	{	
	   // printr($invoice);
		$setHtml='';
		$setHtml .='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
						<div class="table-responsive">
							<div style="height:2%;">';
							$setHtml .= '<table class="table" border="0" style=" font-size: 11px; ">';
							if($invoice['invoice_date']>'2019-06-19'){
                              	$fixdata = $this->getFixmaster(2); 
                             }else{
                                 $fixdata = $this->getFixmaster(1); 
                             }
								$i = 1;
								$transportation=decode($invoice['transportation']);
								/*if($transportation=='sea')
								{
									$incval=2;
								}
								else
								{	
									$incval=12;
								}
										while($i <= $incval) {
											if($incval==12)
											{  if($i==1){$setHtml .= '<tr>';}
											    if($i==9){$setHtml .= '<tr>';}
												
												if($i<=9)if($i%5==0){
												    $setHtml .= '<tr id="'.$i.'">';}
											}
											else
												$setHtml .= '<tr>';
											 $setHtml .= '<td style="border:none;padding-left: 30px;padding-top:4px;">
																<h4><u>EXPORTER:-</u></h4>	
																'.nl2br($fixdata['exporter']).'
																<h4><u>CONSIGNEE:-</u> </h4>
																<b>'.$invoice['customer_name'].'</b><br>'.nl2br($invoice['consignee']).'<br><span><img src="http://www.bcgen.com/demo/linear-dbgs.aspx?D='.$invoice['invoice_no'].'"></span>
															</td>';
														if($incval==12)
														{	
															if($i%8==0)										
															$setHtml .= '</tr>'; 
															
															if($i%12==0)										
															$setHtml .= '</tr>';
														}
														else
														{
															$setHtml .= '</tr>';
														}
												$i++;
												}*/
												if($transportation=='sea')
                								{
                									$incval=2;
                								}
                								else
                								{	
                									$incval=9;
                								}
                										while($i <= $incval) {
                											if($incval==9)
                											{  if($i==1){$setHtml .= '<tr>';}
                											    if($i==7){$setHtml .= '<tr>';}
                												
                												if($i<=7)if($i%4==0){
                												    $setHtml .= '<tr id="'.$i.'">';}
                											}
                											else
                												$setHtml .= '<tr>';
                											 $setHtml .= '<td style="border:none;padding-left: 30px;padding-top:40px;">
                																<h4><u>EXPORTER:-</u></h4>	
                																'.nl2br($fixdata['exporter']).'
                																<h4><u>CONSIGNEE:-</u> </h4>
                																<b>'.$invoice['customer_name'].'</b><br>'.nl2br($invoice['consignee']).'<br><span><img style="width:156px;" class="barcode" alt="'.trim($invoice['invoice_no']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($invoice['invoice_no']).'&codetype=Code128&orientation=horizontal&size=30&print=true"/></span>
                															</td>';
                														if($incval==9)
                														{	
                															if($i%6==0)										
                															$setHtml .= '</tr>'; 
                															 
                															if($i%9==0)										
                															$setHtml .= '</tr>';
                														}
                														else
                														{
                															$setHtml .= '</tr>';
                														}
                												$i++;
                												}
							$setHtml.='</table>
						</div>
					</div>
				</form>';
		return($setHtml);
	}
	//changed by jaya on 8-12-2016
	function convert_number_old($number) 
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
	
	
	function convert_number($numval){
			   error_reporting(0);
				$moneystr = "";
				//printr($numval);
				// handle the millions
				//$numval = '4200.1';
				$milval = (integer)($numval / 1000000);
				//printr($milval);
				if($milval > 0)  {

				  $moneystr = $this->getwords($milval) . " Million";

				  }

				 

				// handle the thousands

				$workval = $numval - ($milval * 1000000); // get rid of millions

				$thouval = (integer)($workval / 1000);
	//printr($thouval);
				if($thouval > 0)  {

				  $workword = $this->getwords($thouval);

				  if ($moneystr == "")    {

					$moneystr = $workword . " Thousand";

					}else{

					$moneystr .= " " . $workword . " Thousand";

					}

				  }

				

				// handle all the rest of the dollars

				$workval = $workval - ($thouval * 1000); // get rid of thousands

				$tensval = (integer)($workval);

				if ($moneystr == ""){

				  if ($tensval > 0){

					$moneystr = $this->getwords($tensval);

					}else{

					$moneystr = "Zero";

					}

				  }else // non zero values in hundreds and up

				  {

				  $workword = $this->getwords($tensval);

				  $moneystr .= " " . $workword;

				  }

				 

				// plural or singular 'dollar'

				$workval = (integer)($numval);

				if ($workval == 1){

				  $moneystr .= "  & ";

				  }else{

				  $moneystr .= " & ";

				  }

				 

				// do the cents - use printf so that we get the

				// same rounding as printf

			$workstr = sprintf("%3.2f",$numval); // convert to a string

			//$intstr = substr($workstr,strlen() - 2, 2);

			$intstr = substr($workstr,- 2, 2);

			$workint = (integer)($intstr);

			if ($workint == 0){

			  $moneystr .= "Zero";

			  }else{

			  $moneystr .= $this->getwords($workint);

			  }

			if ($workint == 1){

			  $moneystr .= " Cent";

			  }else{

			  $moneystr .= " Cents";

			  }

			 

			// done 
				
			return $moneystr;

	}

		function getwords($workval)

		{
			//printr($workval);
			$numwords = array(

			  1 => "One",

			  2 => "Two",

			  3 => "Three",

			  4 => "Four",

			  5 => "Five",

			  6 => "Six",

			  7 => "Seven",

			  8 => "Eight",

			  9 => "Nine",

			  10 => "Ten",

			  11 => "Eleven",

			  12 => "Twelve",

			  13 => "Thirteen",

			  14 => "Fourteen",

			  15 => "Fifteen",

			  16 => "Sixteen",

			  17 => "Seventeen",

			  18 => "Eighteen",

			  19 => "Nineteen",

			  20 => "Twenty",

			  30 => "Thirty",

			  40 => "Forty",

			  50 => "Fifty",

			  60 => "Sixty",

			  70 => "Seventy",

			  80 => "Eighty",

			  90 => "Ninety");

			 

			// handle the 100's

			$retstr = "";

			$hundval = (integer)($workval / 100);

			if ($hundval > 0){

			  $retstr = $numwords[$hundval] . " Hundred";

			  }

			 

			// handle units and teens

			$workstr = "";

			$tensval = $workval - ($hundval * 100); // dump the 100's

			 

			// do the teens

			//printr($tensval);
			if (($tensval < 20) && ($tensval > 0)){

			  $workstr = $numwords[$tensval];
			   // got to break out the units and tens

			  }else{

			  $tempval = ((integer)($tensval / 10)) * 10; // dump the units

			  $workstr = $numwords[$tempval]; // get the tens
			 // echo '$workstr';

			  $unitval = $tensval - $tempval; // get the unit value

			  if ($unitval > 0){

				$workstr .= " " . $numwords[$unitval];

				}

			  }

			 

			// join the parts together 

			if ($workstr != ""){

			  if ($retstr != "")

			  {

				$retstr .= " " . $workstr;

				}else{

				$retstr = $workstr;

				}

			  }
		//printr($retstr);
			return $retstr;

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
	
	public function getInvoice($user_type_id,$user_id,$data,$filter_data=array(),$inv_status,$admin_user_id='',$limit='',$delivery_mail='')
	{
		//printr($_SESSION);die;
		//printr($inv_status);
		if($user_type_id=='2' && ($user_id=='39' || $user_id=='40') && ($inv_status=='0' || $inv_status=='1'))
			$inv_cond=" AND i.order_type='commercial' ";
		elseif($user_type_id=='2' && $user_id=='68' && ($inv_status=='0' || $inv_status=='1'))
		    $inv_cond=" AND i.order_type='sample' ";
		else
		    $inv_cond='';
		
		if($inv_status=='0')
		  $inv=' AND i.done_status!= 0';
		elseif($inv_status=='1')
		   $inv=' AND i.date_added = "'.date("Y-m-d").'" AND i.done_status = 0';
		 elseif($inv_status=='2')
		    $inv=' AND i.done_status!= 0 AND i.order_user_id="'.$admin_user_id.'" AND i.import_status!=2 ';
		 elseif($inv_status=='3')
			$inv=' AND i.done_status!= 0 AND i.order_user_id="'.$admin_user_id.'"  AND i.import_status=2';
		
		$del_status='';
		if($delivery_mail!='')
		{
			$del_status = ' AND i.goods_delivery_email_status=0';
			$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		}
		
		if($user_type_id == 1 && $user_id == 1){
		//	$sql = "SELECT inv.*,c.country_name,c.foreign_port,c.country_id FROM " . DB_PREFIX . "invoice as inv,country as c WHERE c.country_id=inv.final_destination AND inv.is_delete = 0  $inv $del_status" ;
		        $sql = "SELECT i.* FROM " . DB_PREFIX . "invoice_test as i WHERE i.is_delete = 0  $inv $del_status" ;
		        if(!empty($filter_data['product_code']))
		            $sql = "SELECT i.* FROM " . DB_PREFIX . "invoice_test as i, invoice_product_test as ip WHERE i.is_delete = 0  $inv $del_status AND i.invoice_id = ip.invoice_id" ;
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
				$str = ' OR ( i.user_id IN ('.$userEmployee.') AND i.user_type_id = 2 )';
			}
			$status_cond='';
			if($inv_status!='2' && $inv_status!='3')
				$status_cond="i.user_id = '".(int)$set_user_id."' AND i.user_type_id = '".(int)$set_user_type_id."' $str AND";
			
			//$sql = "SELECT inv.*,c.country_name,c.foreign_port,c.country_id FROM " . DB_PREFIX . "invoice as inv,country as c WHERE $status_cond c.country_id=inv.final_destination AND inv.is_delete = 0 $inv $del_status $inv_cond" ;
			    $sql = "SELECT i.* FROM " . DB_PREFIX . "invoice_test as i WHERE $status_cond   i.is_delete = 0 $inv $del_status $inv_cond" ;
			    if(!empty($filter_data['product_code']))
			        $sql = "SELECT i.* FROM " . DB_PREFIX . "invoice_test as i, invoice_product_test as ip WHERE $status_cond   i.is_delete = 0 $inv $del_status $inv_cond AND i.invoice_id = ip.invoice_id " ;
		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['invoice_no'])){				
			$sql .= " AND i.invoice_no LIKE '%".$filter_data['invoice_no']."%' ";		
			}
			if(!empty($filter_data['country_id'])){
				$sql .= " AND i.final_destination = '".$filter_data['country_id']."' ";		
			}
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND i.customer_name LIKE '%".$filter_data['customer_name']."%' ";		
			}
			//add by sonu 21-11-2017
			if(!empty($filter_data['ref_no'])){
				$sql .= " AND( i.uk_ref_no LIKE '%".$filter_data['ref_no']."%' OR i.buyers_orderno LIKE '%".$filter_data['ref_no']."%') ";
				//printr($sql);		
			}
			if(!empty($filter_data['email'])){
				$sql .= " AND i.email LIKE '%".$filter_data['email']."%' ";		
			}
			if($filter_data['status'] != ''){
				$sql .= " AND i.status = '".$filter_data['status']."' "; 	
			}
			if($filter_data['product_code'] != ''){
				$sql .= " AND ip.product_code_id = '".$filter_data['product_code']."'"; 	
			}
		}
		$sql .=' GROUP BY i.invoice_id ';
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " .$data['sort'];	
		} else {
			$sql .= " ORDER BY i.invoice_id";	
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
		
		if($limit!='')
		   $sql .= " LIMIT ".$limit;
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
	
	public function getFixmaster($id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_fixmaster` WHERE is_delete = '0' AND fix_master_id='".$id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalInvoice($user_type_id,$user_id,$filter_data=array(),$inv_status,$admin_user_id='')
	{
		if($user_type_id=='2' && ($user_id=='39' || $user_id=='40') && ($inv_status=='0' || $inv_status=='1'))
			$inv_cond=" AND i.order_type='commercial' ";
		elseif($user_type_id=='2' && $user_id=='68' && ($inv_status=='0' || $inv_status=='1'))
		    $inv_cond=" AND i.order_type='sample' ";
		else
		    $inv_cond='';
			
   		if($inv_status=='0')
		  $inv=' i.done_status!= 0 AND';
		elseif($inv_status=='1')
		   $inv=' i.date_added = "'.date("Y-m-d").'" AND i.done_status = 0 AND'; 
		elseif($inv_status=='2')
			$inv=' i.done_status!= 0 AND i.order_user_id="'.$admin_user_id.'" AND i.import_status!=2 AND';
		elseif($inv_status=='3')
		    $inv=' i.done_status!= 0 AND i.order_user_id="'.$admin_user_id.'" AND i.import_status=2 AND ';
			//printr( $inv);
		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice_test` as i WHERE $inv i.is_delete = 0";
			if(!empty($filter_data['product_code']))
			    $sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice_test` as i, invoice_product_test as ip WHERE $inv i.is_delete = 0 AND i.invoice_id = ip.invoice_id";
			    
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
				$str = ' OR ( i.user_id IN ('.$userEmployee.') AND i.user_type_id = 2 )';
			}
			$status_cond='';
			if($inv_status!='2' && $inv_status!='3')
				$status_cond=" i.user_id = '".(int)$set_user_id."' AND i.user_type_id = '".(int)$set_user_type_id."' $str AND";
			
			   $sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice_test` as i WHERE $status_cond  $inv i.is_delete = 0 $inv_cond" ;
			    if(!empty($filter_data['product_code']))
			        $sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice_test` as i, invoice_product_test as ip WHERE $status_cond  $inv i.is_delete = 0 $inv_cond AND i.invoice_id = ip.invoice_id" ;
		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['invoice_no'])){
				
			$sql .= " AND i.invoice_no LIKE '%".$filter_data['invoice_no']."%' ";		
			}
			if(!empty($filter_data['country_id'])){
				$sql .= " AND i.final_destination = '".$filter_data['country_id']."' ";		
			}
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND i.customer_name LIKE '%".$filter_data['customer_name']."%' "; 
				//printr($sql);		
			}
			if(!empty($filter_data['ref_no'])){
				$sql .= " AND( i.uk_ref_no LIKE '%".$filter_data['ref_no']."%' OR i.buyers_orderno LIKE '%".$filter_data['ref_no']."%') ";
				//printr($sql);		
			}
			if(!empty($filter_data['email'])){
				$sql .= " AND i.email LIKE '%".$filter_data['email']."%' ";		
			}
			if($filter_data['status'] != ''){
				$sql .= " AND i.status = '".$filter_data['status']."' "; 	
			}
			if($filter_data['product_code'] != ''){
				$sql .= " AND ip.product_code_id = '".$filter_data['product_code']."' GROUP BY i.invoice_id"; 	
			}
		}
		//printr($sql);
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function updateInvoiceStatus($status,$data)
	{
		//printr($data);
		if($status == 0 || $status == 1){
			$sql = "UPDATE " . DB_PREFIX . "invoice_test SET status = '" .(int)$status. "',  date_modify = '".date('Y-m-d H:i:s')."' WHERE invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
		  $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');      	 
			$sql = "UPDATE " . DB_PREFIX . "invoice_test SET is_delete = '1',delete_by='".$by."', date_modify = '".date('Y-m-d H:i:s')."' WHERE invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateInvoice($invoice_no,$status_value)
	{
		$sql = "UPDATE " . DB_PREFIX . "invoice_test SET status = '".$status_value."', date_modify = '".date('Y-m-d H:i:s')."' WHERE invoice_no = '" .(int)$invoice_no. "'";
		$this->query($sql);
	}
	
	public function getInvoiceProduct($invoice_id)
	{
		$sql = "SELECT ip.*,p.product_name FROM `" . DB_PREFIX . "invoice_product_test` as ip,product p WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0 AND ip.product_id=p.product_id";
		$data = $this->query($sql);

	//	printr($sql);//die;
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
	
	public function getInvoiceColorlable($invoice_color_id)
	{
		$sql ="SELECT * FROM invoice_color AS ic LEFT JOIN  invoice_product_test AS ip ON (ic.invoice_product_id=ip.invoice_product_id)  WHERE ic.invoice_color_id=".$invoice_color_id."";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	
	public function getCustomId($custom_order_number)
	{
		$custom_order=explode(",", $custom_order_number);
		$result = "'" . implode ( "', '", $custom_order ) . "'";
		//$data = $this->query("SELECT multi_custom_order_id FROM " . DB_PREFIX ."multi_custom_order_id WHERE multi_custom_order_number = '".$custom_order_number."'");
		$data = $this->query("SELECT multi_custom_order_id FROM " . DB_PREFIX ."multi_custom_order_id WHERE multi_custom_order_number IN (".$result.")");
		if($data->num_rows)
		{
			//return $data->row['multi_custom_order_id'];
			return $data->rows;
		}
		else
			return false;
	}
	
public function getCustomOrder($cust_cond='',$getData = '*',$user_type_id='',$user_id='',$orders_user_id='',$upload_time='')
	{
		
		$date = date("d-m-Y");
		
		if($upload_time!='')
			//$date_cond = '%"currdate":"'.date("d-m-Y", strtotime($upload_time) ).'"%'; commet by sonu  24-2-2017
			$date_cond = '%"currdate":"'.date("d-m-Y", strtotime($date) ).'"%';
		else
			$date_cond = '%"currdate":"'.$date.'"%';
			
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode,mcoi.quotation_status   FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN multi_custom_order_id as mcoi ON(mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE   (".$cust_cond.") AND mco.dispach_by LIKE '".$date_cond."'";
			}else{
				if($user_type_id == 2){
					/*$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					$set_user_id = $parentdata->row['user_id'];
					$set_user_type_id = $parentdata->row['user_type_id'];*/
					$userEmployee = $this->getUserEmployeeIds('4',$orders_user_id);
					//$set_user_id = $user_id;
					//$set_user_type_id = $user_type_id;
					$set_user_id = $orders_user_id;
					$set_user_type_id = '4';
				}else{
					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
					$set_user_id = $user_id;
					$set_user_type_id = $user_type_id;
				}
				$str = '';
				
				if($set_user_id=='24' || $set_user_id=='33')
				{
				    $userEmployee_s = $this->getUserEmployeeIds(4,33);
				    $userEmployee_m = $this->getUserEmployeeIds(4,24);
				   $str =" AND ((mco.added_by_user_id = '24' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."' OR ( mco.added_by_user_id IN (".$userEmployee_m.") AND mco.added_by_user_type_id = 2 )) OR (mco.added_by_user_id = '33' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."' OR ( mco.added_by_user_id IN (".$userEmployee_s.") AND mco.added_by_user_type_id = 2 )))";
				   
				   
			
				} 
				else
				{
				    $str = " AND ((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."')  ";
    				if($userEmployee){
    					$str .= ' OR ( mco.added_by_user_id IN ('.$userEmployee.') AND mco.added_by_user_type_id = 2 ) ';
    				}
    				$str .=")";
				}
			
			
			
			
			
			
			
				//$str .= ' ) ';
			
			
			
			
			
			
			
				$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode ,mcoi.quotation_status FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN " . DB_PREFIX ."multi_custom_order_id mcoi ON (mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE (".$cust_cond.")   $str AND mco.done_status ='0' AND mco.dispach_by LIKE '".$date_cond."'";
				//echo $sql;
		
		
		
		
		
		
			}
		}else{
			$sql = "SELECT $getData,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode,mcoi.quotation_status  FROM " . DB_PREFIX ."multi_custom_order mco,multi_custom_order_id mcoi,address adr WHERE
			 mco.multi_custom_order_id=mcoi.multi_custom_order_id AND mco.done_status ='0' AND (".$cust_cond.") AND mcoi.shipping_address_id=adr.address_id AND mco.dispach_by LIKE '".$date_cond."' ";
		}
	//	echo $sql;
	//	die;
		$data = $this->query($sql);
		//printr($data);
		
		return $data->rows;
	}
	   
	public function getCustomOrderQuantity($custom_order_id){
		//printr($custom_order_id);die;
		$paking_price=$this->getCustomOrderPackingAndTransportDetails($custom_order_id);
		$data = $this->query("SELECT mcoq.product_code_id,mco.custom_order_id,mco.multi_custom_order_id,mco.currency_price,mco.status,mco.shipment_country_id,mco.currency,mco.custom_order_status,mco.custom_order_type,mcoq.custom_order_quantity_id,mco.product_id,p.product_name, mcoq.custom_order_id,mcoq.discount, printing_effect,mcoq.quantity,mco.height,mco.width,mco.gusset,mco.volume,mco.dis_qty, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mco.layer,total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage,mco.quotation_status,back_color,front_color,total_color FROM " . DB_PREFIX ."multi_custom_order_quantity as mcoq,multi_custom_order as mco,product as p WHERE (".$custom_order_id.") AND mcoq.custom_order_id=mco.custom_order_id AND mco.product_id=p.product_id ORDER BY mcoq.custom_order_quantity_id") ;
	//	printr($data);die;
		$return = array();
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				$zdata = $this->query("SELECT custom_order_price_id, custom_order_id, custom_order_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt, total_price,total_price_with_excies,total_price_with_tax,tax_name,tax_type,tax_percentage,excies, gress_price, customer_gress_price,zipper_price,valve_price,courier_charge,spout_base_price,accessorie_base_price,transport_price,make_name,make_pouch,	accessorie_txt_corner,pouch_price,pouch_price_with_tax,color_plate_price,color_plate_price_with_tax,total_price,total_price_with_tax,template_price,gress_pouch_price,print_price,plate_price_with_discount,digital_print_discount,color_plate_price_with_discount_count FROM " . DB_PREFIX ."multi_custom_order_price as mcop ,product_make as pm WHERE custom_order_id = '".(int)$qunttData['custom_order_id']."' AND custom_order_quantity_id = '".(int)$qunttData['custom_order_quantity_id']."' AND mcop.make_pouch=pm.make_id ORDER BY transport_type");	
				//printr($zdata);die;
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
						
						$txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
						$txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
						/*if($zipData['spout_txt']=='No Spout')
							$zipData['spout_txt']='';
						if($zipData['accessorie_txt']=='No Accessorie')
							$zipData['accessorie_txt']='';*/
						$email_txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
						$email_txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
						    if($qunttData['quotation_status']=='1'){
						        $zipData['transport_type']= ucwords($zipData['transport_type']);
						    }else{
						         $zipData['transport_type']='By '.ucwords($zipData['transport_type']);
						    }
						
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
								'accessorie_txt_corner' => $zipData['accessorie_txt_corner'],
								'spout_txt' => $zipData['spout_txt'],
								'printing_effect' => $qunttData['printing_effect'],
								'materialData'=>$materialData,
								'make' => $zipData['make_name'],
								'custom_order_price_id'=>$zipData['custom_order_price_id'],
								'layer'=>$qunttData['layer'].''.$zipData['custom_order_id'],
								'product_id'=>$qunttData['product_id'],
								'product_name'=>$qunttData['product_name'],
								'custom_order_type'=>$qunttData['custom_order_type'],
								'custom_order_status'=>$qunttData['custom_order_status'],
								'currency'=>$qunttData['currency'],
								'shipment_country_id'=>$qunttData['shipment_country_id'],
								'status' =>$qunttData['status'],
								'currency_price' =>$qunttData['currency_price'],
								'multi_custom_order_id' =>$qunttData['multi_custom_order_id'],
								'custom_order_id' =>$qunttData['custom_order_id'],
								'make_id'=>$zipData['make_pouch'],
								'dis_qty'=>$qunttData['dis_qty'],
								'prouduct_code_id'=>$qunttData['product_code_id'],
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
							);
					}
				}
			}
		}
		//printr($return);die;
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
	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	
	public function getStockIdold($stock_order_number)
	{
		$data = $this->query("SELECT stock_order_id,client_id FROM " . DB_PREFIX ." stock_order_test WHERE gen_order_id = '".$stock_order_number."'");
		if($data->num_rows)
		{
			return $data->row;
		}
		else
			return false;
	}
	
	public function getStockId($stock_order_number)
	{
		$stock_order=explode(",", $stock_order_number);
		$result = "'" . implode ( "', '", $stock_order ) . "'";
		$data = $this->query("SELECT stock_order_id,client_id FROM " . DB_PREFIX ."stock_order_test WHERE gen_order_id IN (".$result.")");
	    //$data = $this->query("SELECT stock_order_id,client_id FROM " . DB_PREFIX ." stock_order WHERE gen_order_id = '".$stock_order_number."'");
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
			return false;
	}
	public function GetStockOrderList($user_id,$usertypeid,$status='',$client_id='',$stock_order_id='',$sodh_client,$sodh_cond,$temp='',$orders_user_id='',$upload_time='',$done_status='')
	{
		//printr($orders_user_id);
		//die; 
		 $menu_id = $this->getMenuPermission(ORDER_ACCEPT_ID,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		 //printr($menu_id);
		$admin = '';
		if($status=='')
		$status ='AND pto.order_id = t.product_template_order_id';
		if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
		{
			//$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$orders_user_id."'  ";

			//$dataadmin = $this->query($sqladmin);
			if($orders_user_id=='24' || $orders_user_id=='33'){
			   	$cond = "AND( pto.admin_user_id ='33' OR  pto.admin_user_id ='24')";
			}else{
			    $cond = 'AND( pto.admin_user_id = '.$orders_user_id.')';
			}
			$admin_user_id = $orders_user_id; 
			$table= 'employee as ib ,';
			$page=0;
			//echo $admin_user_id;
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$cond = 'AND pto.admin_user_id = '. $_SESSION['ADMIN_LOGIN_SWISS'].' ';
			//$cond = '';
			$admin_user_id = $user_id;
			$table= 'international_branch as ib , ';
			$page=0;
			//echo '2';
		}
		else
		{
			$cond = ' ';
			$table = ' ';
			$admin_user_id ='';
			$page=0;
			//echo '3';
		}
		if(($menu_id OR $_SESSION['LOGIN_USER_TYPE']==1 ))
		{
			$cond = ' ';
			$table = ' ';
		}
		if($page==1)
		{
			$admin = 'AND pto.admin_user_id="'.$admin_user_id.'"';
		}
		
		$stock_cond='';
		$curr_d=date("d-m-Y");
		
		//$curr_d='08-11-2016';
		$date_cond='';
		if($stock_order_id!='')
		{
			$date_cond = '%"currdate":"'.$curr_d.'"%';
			$stock_cond=" AND (".$sodh_cond.") AND (sodh.template_order_id = t.template_order_id AND sodh.product_template_order_id=t.product_template_order_id AND sodh.status=0) AND sodh.dispach_by LIKE '".$date_cond."' ";
		}
		
		if($temp!='')
			$stock_cond =" AND (".$temp.") AND (sodh.template_order_id = t.template_order_id AND sodh.product_template_order_id=t.product_template_order_id AND sodh.status=0) AND sodh.dispach_by LIKE '".$date_cond."' ";
		
		
		if($upload_time!='')
		{
			$cond = 'AND pto.admin_user_id = '.$orders_user_id.' ';
			$table= 'international_branch as ib , ';
			$page=0;
			$date_cond = '%"currdate":"'.date("d-m-Y", strtotime($upload_time) ).'"%';
			$stock_cond=" AND (".$sodh_cond.") AND (sodh.template_order_id = t.template_order_id AND sodh.product_template_order_id=t.product_template_order_id AND sodh.status=0) AND sodh.dispach_by LIKE '".$date_cond."' ";
		}	
			
		if($client_id!='')
		{
			//$client_id=" AND t.client_id = '".$client_id."' AND ";
			$client_id=" AND (".$sodh_client.") AND ";
		}
		else
			$client_id =" AND ";
		
		///echo $client_id;$date = date("Y-m-d");
		$sql = "SELECT t.product_code_id,sodh.stock_order_dispatch_history_id,sodh.dis_qty,so.gen_order_id,t.digital_print_color,t.front_color,t.back_color,t.stock_print,t.reference_no,t.filling_details,t.client_id,t.expected_ddate,t.template_order_id,t.quantity,p.product_id,p.email_product,p.product_name,pt.title,pts.width,pts.height,pts.gusset,pto.shipment_country,pto.ship_type as pto_ship_type,pto.admin_user_id,t.transport,pc.pouch_color_id,
pts.volume,t.note,c.country_name,cu.currency_code,pc.email_color,pc.color,t.user_id,t.user_type_id,t.price,pts.valve,cd.client_name,pts.product_template_size_id,pts.zipper,pts.spout,pts.accessorie,pts.description,t.ship_type,pt.product_template_id,t.address,t.date_added,pt.transportation_type,t.product_template_order_id,sos.review,sos.track_id,sos.date,sos.courier_id,pto.order_id,sos.process_by,sos.dispach_by,pts.quantity1000,pts.quantity2000,pts.quantity5000,pts.quantity10000,t.price_uk,t.order_type FROM " .DB_PREFIX . "stock_order_dispatch_history_test as sodh,template_order_test t,currency as cu,product as p,product_template pt,product_template_size as pts,country as c,pouch_color as pc, product_template_order_test as pto,stock_order_status_test as sos, courier as co,client_details as cd,stock_order_test as so  WHERE  t.product_id = p.product_id AND pt.product_template_id = t.template_id AND so.stock_order_id=t.stock_order_id AND so.client_id=t.client_id AND pto.admin_user_id=so.admin_user_id AND  t.template_order_id=sos.template_order_id  ".$status." AND pt.currency = cu.currency_id AND t.is_delete = 0  ".$cond." 
".$client_id."  t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color AND t.country=c.country_id AND pto.order_id = t.product_template_order_id ".$admin." AND  t.is_delete = 0 AND t.done_status='0' AND t.client_id=cd.client_id".$stock_cond;	
	
		if(!empty($filter_array)) {
			if(!empty($filter_array['order_no'])){
				$sql .= " AND so.gen_order_id = '".$filter_array['order_no']."'";				
			}
			//echo $sql;
			//die;
			if(!empty($filter_array['date'])){
				$sql .= " AND date(t.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
				//echo $sql;die;
			}			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND p.product_name = '".$filter_array['product_name']."'";
			}
			if(!empty($filter_array['postedby']))
			{
				$spitdata = explode("=",$filter_array['postedby']);
				//printr($spitdata);
				$sql .=" AND t.user_type_id = '".$spitdata[0]."' AND t.user_id = '".$spitdata[1]."'";
			}				
		}
		$sql .= " GROUP BY t.template_order_id ORDER BY t.template_order_id ASC"; 
	
	  //  echo $sql;die;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows)
		{
			//echo $con;
			foreach($data->rows as $data)
			{
				
				$stock_data = array($data);
				
				$stock[$data['transport']][$data['template_order_id']] = $stock_data;
			}
			//printr($stock);
			return $stock;
			
		}
		else
		{
			return false;
		}
	}
	public function getMenuPermission($menu_id,$user_id,$user_type_id)
	{
		$cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$cond." AND user_type_id = '".$user_type_id."' 
		AND user_id ='".$user_id."'";
		$data = $this->query($sql);
		return $data->rows;
	}

	public function userOrderData($user_id,$user_type_id,$order_type='')
	{
		$date = date("d-m-Y");
		//printr($date);
		//printr("===================================");
		$date_cond = '%"currdate":"'.$date.'"%';
		$con=$cust_con='';
		$order='';
		$admin_user_id='';
		if($order_type!='')
			$order = ' AND t.order_type = "'.$order_type.'"';
			
		if($user_type_id==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'";
			$dataadmin = $this->query($sqladmin);
			$con =  'AND st.admin_user_id = "'.$dataadmin->row['user_id'].'"';
			$cust_con =  'AND mcoi.admin_user_id = "'.$dataadmin->row['user_id'].'"';
			$admin_user_id=$dataadmin->row['user_id']; 
			if($admin_user_id=='24' || $admin_user_id=='33'){
			    $con=' AND (st.admin_user_id = "24"  OR  st.admin_user_id = "33")';	 
			    $cust_con='  AND (mcoi.admin_user_id  = "24" OR mcoi.admin_user_id  = "33")';	
			}else{
			   	$con =  ' AND (st.admin_user_id = "'.$dataadmin->row['user_id'].'") ';
		    	$cust_con =  'AND (mcoi.admin_user_id = "'.$dataadmin->row['user_id'].'")';
		   	}
		
		}
		elseif($user_type_id==4)
		{
			$con =  "AND st.admin_user_id = '".$user_id."'";
			$cust_con =  "AND mcoi.admin_user_id = '".$user_id."'";
			$admin_user_id= $user_id;
			if($admin_user_id=='24' || $admin_user_id=='33'){
			    $con=' AND (st.admin_user_id = "24"  OR  st.admin_user_id = "33")';	 
			    $cust_con='  AND (mcoi.admin_user_id  = "24" OR mcoi.admin_user_id  = "33")';	
			}else{
			   	$con =  ' AND (st.admin_user_id = "'.$user_id.'") ';
		    	$cust_con =  'AND (mcoi.admin_user_id = "'.$user_id.'")';
		   	}
		
		}
		
		$stock_sql = "SELECT st.gen_order_id as order_num FROM template_order_test t,stock_order_test st,stock_order_dispatch_history_test as sodh WHERE t.is_delete = 0 AND ( (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=0)) AND t.status=1 AND st.stock_order_id=t.stock_order_id AND st.client_id=t.client_id ".$con." ".$order." AND sodh.dispach_by LIKE '".$date_cond."'  GROUP BY st.stock_order_id, st.admin_user_id";
		$stock_data = $this->query($stock_sql);
	//echo $stock_sql;
		$cust_data=array();
		//$cust_data->num_rows=0;
		//if($order_type!='sample')
		//{
			$cust_sql = "SELECT mcoi.multi_custom_order_number as order_num FROM multi_custom_order mco,multi_custom_order_id as mcoi WHERE mco.multi_custom_order_id=mcoi.multi_custom_order_id ".$cust_con." AND mco.dispach_by LIKE '".$date_cond."' AND done_status=0 GROUP BY mcoi.multi_custom_order_id, mcoi.admin_user_id";
			//echo $cust_sql;
			$cust_data = $this->query($cust_sql);
		//}
		$custom_imp = '';
		$stock_imp='';
		$custom_ara=array();
		$stock_ar=array();
		$o_no=array();
		//printr($cust_sql);
		if(!empty($cust_data) && $cust_data->num_rows>0)
		{
			foreach($cust_data->rows as $cust)
			{
				$custom_ar[] = $cust['order_num'];
			}
			$custom_imp = implode(",",$custom_ar);
		}

		if($stock_data->num_rows>0)
		{
				foreach($stock_data->rows as $stock)
				{
					$stock_ar[] = $stock['order_num'];
				}
				$stock_imp = implode(",",$stock_ar);
				$o_no = array('stock_order_no' => $stock_imp,
							  'custom_order_no' => $custom_imp,
							  'order_user_id' =>$admin_user_id);
				return $o_no;
		}
		else
		{
			if(!empty($cust_data))
			{
				$o_no = array('stock_order_no' => '',
							  'custom_order_no' => $custom_imp,
							  'order_user_id' =>$admin_user_id);
				//printr($o_no);
				return $o_no;
			}
			else
				return false;
		}
	
	}
	public function getLastIdInvoice() {
		$sql = "SELECT invoice_id FROM invoice_test ORDER BY invoice_id DESC LIMIT 1";
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
	
	public function addInvoiceData($data)
	{
	//	printr($data);
//	die;
		//$template_order_id_arr[]=$multi_custom_order_id_arr[]='';
		if(isset($data['template_order_id']))
			$template_order_id_arr = $data['template_order_id'];
		
		if(isset($data['multi_custom_order_id']))
			$multi_custom_order_id_arr= $data['multi_custom_order_id'];
		
		
		$order_by=explode("=",$data['order_by']);
		$user_detail = $this->getUser($order_by[1],$order_by[0]);
		
		$curr_id = $this->getUserCurrencyByUser($user_detail['default_curr']);
		//add by sonu told by vikas sir 1-4-2017
		$user = explode("=",$data['user_name']);
		$international_branch_data = $this->getInternational_branch_detail($user[1],$user[0]);
			//printr($international_branch_data);
		//end
		$userCountry = $this->getUserCountry($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
		if($userCountry){
			$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
		}else{
			$countryCode='IN';
		}	
		
		//printr($template_order_id_arr);
		//printr($multi_custom_order_id_arr);
		
		$LastInvoiceId = $this->getLastIdInvoice();
		$id=$LastInvoiceId['invoice_id']+1;
		$pur_id=str_pad($id,8,'0',STR_PAD_LEFT);
		$invoice_no=$countryCode.$pur_id;
		//echo $invoice_no;die;
		/*$sql = "INSERT INTO `" . DB_PREFIX . "invoice` SET invoice_no = '".$data['invoiceno']."',invoice_date = '" .date("Y-m-d"). "',exporter_orderno = '".$data['ref_no']."',gst='".$data['gst']."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."', buyers_orderno ='".$data['buyersno']."',other_ref ='".$data['other_ref']."',pre_carrier='".$data['pre_carrier']."',consignee='".addslashes($data['consignee'])."',buyer='".$data['other_buyer']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',port_load='".$data['portofload']."',port_discharge='".$data['port_of_dis']."',final_destination='".$data['country_id']."', taxation='".$taxation."', excies='".$exci$es."', tax='".$tax."', tax_mode ='".$data['tax_mode']."',tax_form ='".$form."' ,payment_terms='".$data['payment_terms']."',delivery='".$data['delivery']."',HS_CODE='".$data['hscode']."',pouch_type='".$data['printedpouches']."',pouch_desc='".$data['pouch_desc']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',cylinder_charges='".$data['cylinder_charges']."',container_no='".addslashes($data['container_no'])."',seal_no='".addslashes($data['seal_no'])."', remarks='".$data['remarks']."',postal_code = '".$data['postal_code']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',transportation='".encode($data['transport'])."',curr_id='".$data['currency']."',status = '".$data['status']."',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0";*/
		
		$trans = explode(' ',$data['trans']);
	//	printr($trans);die;
		$import='';
		if(strtolower($trans[1])=='air')
			$import = ',import_status=1';
			$title='';
		if(isset($data['invoice_title']))
		    $title=",invoice_title='".$data['invoice_title']."'";
		$sql = "INSERT INTO `" . DB_PREFIX . "invoice_test` SET invoice_no = '".$invoice_no."',invoice_date = '".date("Y-m-d")."',buyers_orderno='".$data['uni_ids']."' $title ,uk_ref_no='".$data['uni_ref_ids']."',consignee='".addslashes($user_detail['company_address'])."',customer_name = '".$user_detail['company_name']."',email = '".$user_detail['email']."',port_discharge='".$data['ship_country']."',curr_id='".$curr_id."',HS_CODE='39232990',pre_carrier='3',port_load='3',transportation='".encode(strtolower($trans[1]))."',order_type='".$data['order_type']."',status = '1',measurement='1',box_limit='30',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',final_destination='".$data['ship_country']."',country_destination='".$data['ship_country']."',is_delete=0,order_user_id='".$user_detail['user_id']."' $import";
		//echo $sql.'<br>Invoice query</br>';//die;
		
		$datasql=$this->query($sql);
		$invoice_id = $this->getLastId();
		
		$inv_id=array();
		
		if(isset($data['template_order_id']))
		{
			foreach($template_order_id_arr as $key=>$template_order_id)
			{
				// told by vikas sir 1-4-2017
				
				
				if(strtolower($trans[1])=='air')
				{
						if($international_branch_data['stock_discount_air'] !='0')
						{		
								$discount_rate = $international_branch_data['stock_discount_air'];						
								$dis_rate = number_format((($data['rate_'.$template_order_id] * $international_branch_data['stock_discount_air']) / 100),3 );
								/*if($user_detail['user_id']=='2')
								    $final_rate_stock =number_format( ($data['rate_'.$template_order_id] + $dis_rate),3);
								else*/
								    $final_rate_stock =number_format( ($data['rate_'.$template_order_id] - $dis_rate),3);
								///printr($final_rate_stock.'finalrate'.$data['rate_'.$template_order_id]);
						}else{
								$discount_rate = $international_branch_data['stock_discount_air'];
								$final_rate_stock = $data['rate_'.$template_order_id] ;
							
							}
				}else{
						if($international_branch_data['stock_discount_sea']!='0')
						{
							$discount_rate = $international_branch_data['stock_discount_sea'];
							$dis_rate = number_format((($data['rate_'.$template_order_id] * $international_branch_data['stock_discount_sea']) / 100),3 );											
							/*if($user_detail['user_id']=='2')
								$final_rate_stock =number_format( ($data['rate_'.$template_order_id] + $dis_rate),3);
							else*/
							    $final_rate_stock =number_format( ($data['rate_'.$template_order_id] - $dis_rate),3);
							
						}else{
							$discount_rate = $international_branch_data['stock_discount_sea'];
							
							$final_rate_stock = $data['rate_'.$template_order_id] ;
							  
						}
				}
			
			//printr($final_rate_stock.'finalrate'. $discount_rate);//die;
				
						//sonu end 1-4-2017
				
				//$this->query("UPDATE template_order SET done_status='1' WHERE template_order_id ='".$template_order_id."'");
			/*	if($data['digital_print_color_'.$template_order_id]!='')
				{
				    $data['pouch_color_id_'.$template_order_id] = '-1';
				    $data['product_code_id_'.$template_order_id] = '0';
				}*/
				
				/*$sql2 = "Insert into invoice_product Set invoice_id='".$invoice_id."',product_id='".$data['product']."', valve = '".$data['valve']."', zipper = '".$data['zipper']."', spout = '".$data['spout']."',make_pouch='".$data['make']."', accessorie = '".$data['accessorie']."',net_weight='".$data['netweight']."',measurement_two='".$data['measurement']."',buyers_o_no='".$data['buyers_o_no']."',item_no='".$data['item_no']."', date_added = NOW(), date_modify = NOW(), is_delete = 0"; */
		 		$sql2 = "Insert into invoice_product_test Set invoice_id='".$invoice_id."',product_id='".$data['product_id_'.$template_order_id]."',product_code_id='".$data['product_code_id_'.$template_order_id]."', valve = '".$data['valve_id_'.$template_order_id]."', zipper = '".encode($data['zipper_id_'.$template_order_id])."', spout = '".encode($data['spout_id_'.$template_order_id])."',make_pouch='".$data['make_id_'.$template_order_id]."', accessorie = '".encode($data['accessorie_id_'.$template_order_id])."',buyers_o_no = '".$data['stock_order_no_'.$template_order_id]."',measurement_two='1',digital_print_color='".$data['digital_print_color_'.$template_order_id]."',ref_no='".$data['reference_no_'.$template_order_id]."',order_id='".$template_order_id."',order_size_id='".$data['product_template_size_id_'.$template_order_id]."',filling_details='".$data['filling_details_'.$template_order_id]."',date_added = NOW(), date_modify = NOW(), is_delete = 0";
		//		echo $sql2.'<br>product query for stock</br>';
			
				
				$data2=$this->query($sql2);
				$invoice_product_id = $this->getLastId();
	
				/*$sql3 = "Insert into invoice_color Set invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$color['color']."',color_text='".$clr_txt."', rate ='".$color['rate']."', qty = '".$color['qty']."', size = '".$color['size']."',  measurement = '".$color['measurement']."',dimension='".$dim."',net_weight='".$color['net_weight']."',date_added = NOW(), date_modify = NOW(), is_delete = 0";*/
				
				//chage sonu 1-4-2017
				$sql3 = "Insert into invoice_color_test Set invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$data['pouch_color_id_'.$template_order_id]."', rate ='".$final_rate_stock."',dis_rate = '".$data['rate_'.$template_order_id]."', discount_rate_percentage ='".$discount_rate."', qty = '".$data['dis_qty_'.$template_order_id]."',rack_status='".$data['dis_qty_'.$template_order_id]."', size = '".$data['size_'.$template_order_id]."',  measurement = '".$data['mea_'.$template_order_id]."',dimension='".$data['dimension_'.$template_order_id]."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 			
				//echo '<br>'.$sql3.'<br>color query for stock</br>';
				//die;
				$data3=$this->query($sql3);
				$invoice_color_id = $this->getLastId();
				$inv_id[]=$invoice_id."==".$invoice_product_id."==".$invoice_color_id;
			}
		}
	//	die;
		//printr($data['multi_custom_order_id']);
		//printr($multi_custom_order_id_arr);
		if(isset($data['multi_custom_order_id']))
		{
			
			foreach($multi_custom_order_id_arr as $multi_custom_order_id)
			{
			
				$cust_array = explode("==",$multi_custom_order_id);
				
				$multi_custom_order_id_1 = $cust_array[0];
				$custom_order_id = $cust_array[1];
			//	$this->query("UPDATE multi_custom_order SET done_status='1' WHERE multi_custom_order_id ='".$multi_custom_order_id."'");
				//sonu add 1-4-2017
				 
				if($data['cust_product_id_'.$custom_order_id]!='6')
			    	$data['cust_dis_qty_'.$custom_order_id]=$data['cust_dis_qty_'.$custom_order_id];
				else
				    $data['cust_dis_qty_'.$custom_order_id]=$data['cust_roll_qty_no_'.$custom_order_id];
			
				if(strtolower($trans[1])=='air')
				{
					if($international_branch_data['custom_discount_air'] !='0')
						{
								$discount_rate = $international_branch_data['custom_discount_air'];
								$dis_rate = number_format((($data['cust_rate_'.$custom_order_id] * $international_branch_data['custom_discount_air']) / 100),3 );					
								if($user_detail['user_id']=='2')
								    $final_rate_custom =number_format( ($data['cust_rate_'.$custom_order_id] + $dis_rate),3);
								else
								    $final_rate_custom =number_format( ($data['cust_rate_'.$custom_order_id] - $dis_rate),3);
					
						}else{
								 $discount_rate = $international_branch_data['custom_discount_air'];
								 $final_rate_custom = $data['cust_rate_'.$custom_order_id] ;						
								
							}
					
				}else{
					
						if($international_branch_data['custom_discount_sea'] !='0')
						{
								$discount_rate = $international_branch_data['custom_discount_sea'];
								$dis_rate = number_format((($data['cust_rate_'.$custom_order_id] * $international_branch_data['custom_discount_sea']) / 100),3 );
								if($user_detail['user_id']=='2')
								    $final_rate_custom =number_format( ($data['cust_rate_'.$custom_order_id] + $dis_rate),3);
								else
								    $final_rate_custom =number_format( ($data['cust_rate_'.$custom_order_id] - $dis_rate),3);
				
						}else{
								$discount_rate = $international_branch_data['custom_discount_sea'];
								$final_rate_custom = $data['cust_rate_'.$custom_order_id] ;
								
						}
				}
					//printr($final_rate_custom.'finalrate'. $discount_rate);
			//	///	printr($final_rate_custom);
				//die; //sonu End
				if($data['cust_accessorie_id_cor_'.$custom_order_id]!='')
				    $data['cust_accessorie_id_'.$custom_order_id] = $data['cust_accessorie_id_cor_'.$custom_order_id] ;
				
				$sql4 = "Insert into invoice_product_test Set invoice_id='".$invoice_id."',product_id='".$data['cust_product_id_'.$custom_order_id]."', product_code_id='".$data['cust_product_code_id_'.$custom_order_id]."',valve = '".$data['cust_valve_id_'.$custom_order_id]."', zipper = '".encode($data['cust_zipper_id_'.$custom_order_id])."', spout = '".encode($data['cust_spout_id_'.$custom_order_id])."',make_pouch='".$data['cust_make_id_'.$custom_order_id]."', accessorie = '".encode($data['cust_accessorie_id_'.$custom_order_id])."',ref_no='".$data['reference_no_'.$custom_order_id]."',digital_print_color='".$data['cust_digital_print_color_'.$custom_order_id]."',order_id='".$custom_order_id."',buyers_o_no = '".$data['multi_cust_order_no_'.$custom_order_id]."',measurement_two='1',date_added = NOW(), date_modify = NOW(), is_delete = 0";
				//echo $sql4.'<br>product query for custom</br>';
				$data4=$this->query($sql4);
				$cust_invoice_product_id = $this->getLastId();
				
				//sonu change query 1-4-2017
				$sql5 = "Insert into invoice_color_test Set invoice_id='".$invoice_id."',invoice_product_id='".$cust_invoice_product_id."',color = '".$data['cust_pouch_color_id_'.$custom_order_id]."', rate ='".$final_rate_custom."',dis_rate = '".$data['cust_rate_'.$custom_order_id]."', discount_rate_percentage ='".$discount_rate."', qty = '".$data['cust_dis_qty_'.$custom_order_id]."',rack_status='".$data['cust_dis_qty_'.$custom_order_id]."', size = '".$data['cust_size_'.$custom_order_id]."',  measurement = '".$data['cust_mea_'.$custom_order_id]."',dimension='".$data['cust_dimension_'.$custom_order_id]."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 	
				//echo $sql5.'<br>color query for custom</br>';
				$data3=$this->query($sql5);
				$cust_invoice_color_id = $this->getLastId();
				$inv_id[]=$invoice_id."==".$cust_invoice_product_id."==".$cust_invoice_color_id;
			}
		}
		
		return $inv_id;
	}
	
	public function getDispatchQty($template_order_id,$product_template_order_id)
	{
		
		$date = date("Y-m-d");
		//$date = '2016-11-08'; 
		//$date = '2016-09-24';
		//echo "SELECT sum(dis_qty) as total_dis_qty FROM stock_order_dispatch_history WHERE template_order_id='".$template_order_id."' AND product_template_order_id='".$product_template_order_id."'  AND dis_date = '".$date."'";
		$data=$this->query("SELECT sum(dis_qty) as total_dis_qty FROM stock_order_dispatch_history_test WHERE template_order_id='".$template_order_id."' AND product_template_order_id='".$product_template_order_id."' AND dis_date = '".$date."' ");
	//	echo $sql;//die;
		if($data->num_rows)
		{ 
			return $data->row;
		}
		else
		{
			return false;
		}
		
		
	}
	
/*	public function addBoxDetail($post)
	{
		//savelabeldetail
		printr($post);
	//	die;
		$str ='';
		if(isset($post['template_order_id']))
			$template_order_id_arr = $post['template_order_id'];
		
		if(isset($post['multi_custom_order_id']))
			$multi_custom_order_id_arr= $post['multi_custom_order_id'];
		
		
		if(isset($post['template_order_id']))
		{
			foreach($template_order_id_arr as $key=>$template_order_id)
			{
					
					if($post['order_type'] == 'sample')
					{
						$str = " ,rate='".$post['rate_'.$template_order_id]."'";	
					}
				
				$stock = explode("==",$post['stk_invoice_id'.$template_order_id]);				
				$sql="UPDATE invoice_product_test SET net_weight='".$post['netweight_'.$template_order_id]."',item_no ='".$post['item_no_'.$template_order_id]."' WHERE invoice_product_id ='".$stock[1]."'" ;
				$this->query($sql);
				
				$sql2="UPDATE invoice_color_test SET net_weight='".$post['netweight_'.$template_order_id]."' $str   WHERE invoice_color_id ='".$stock[2]."'" ;
				$this->query($sql2);
				
				
				
				$stock_array = array('detail'=>$stock[2],
									'per_qty'=>$post['box_qty_'.$template_order_id],
									'per_box_weight'=>$post['boxweight_'.$template_order_id],
									'total_box'=>$post['total_box_'.$template_order_id],
									'invoice_id'=>$stock[0],
									'in_gen_id'=>'0',
									'net_weight'=>$post['netweight_'.$template_order_id],
									'qty'=>'',
									'in_product_id'=>$stock[1],
									);
				$save_stock_box = $this->savelabeldetail($stock_array);
			}
		}
		if(isset($post['multi_custom_order_id']))
		{
			foreach($multi_custom_order_id_arr as $key=>$multi_custom_order_id)
			{
				$cust_array = explode("==",$multi_custom_order_id);
				$multi_custom_order_id_1 = $cust_array[0];
				$custom_order_id = $cust_array[1];			
				
				//[sonu] added on 12-4-2017 for custom 
				if($post['cust_mea_'.$custom_order_id] == '')
				{
					$measurement = '1';
				}else{
					$measurement =  $post['cust_mea_'.$custom_order_id] ;
				}
				//eNd
				
				$cust = explode("==",$post['cust_invoice_id'.$custom_order_id]);
				
				$sql="UPDATE invoice_product_test SET net_weight='".$post['netweight_cust_'.$custom_order_id]."' WHERE invoice_product_id ='".$cust[1]."'" ;
				$this->query($sql);
				
				$sql2="UPDATE invoice_color_test SET net_weight='".$post['netweight_cust_'.$custom_order_id]."',color_text='".$post['job_card_name_'.$custom_order_id]."',measurement='".$measurement."' WHERE invoice_color_id ='".$cust[2]."'" ;
	    	//	echo $sql2;die;
				$this->query($sql2);
				
				$cust_array = array('detail'=>$cust[2],
									'per_qty'=>$post['box_qty_cust_'.$custom_order_id],
									'per_box_weight'=>$post['boxweight_cust_'.$custom_order_id],
									'total_box'=>$post['total_box_cust_'.$custom_order_id],
									'invoice_id'=>$cust[0],
									'in_gen_id'=>'0',
									'net_weight'=>$post['netweight_cust_'.$custom_order_id],
									'qty'=>'',
									'in_product_id'=>$cust[1],
									);
				$save_cust_box = $this->savelabeldetail($cust_array);
			}
		}
	}*/
	
public function addBoxDetail($post)
	{
	
		$str ='';
		if(isset($post['template_order_id']))
			$template_order_id_arr = $post['template_order_id'];
		
		if(isset($post['multi_custom_order_id']))
			$multi_custom_order_id_arr= $post['multi_custom_order_id'];
		
		
		if(isset($post['template_order_id']))
		{
			foreach($template_order_id_arr as $key=>$template_order_id)
			{
					
					if($post['order_type'] == 'sample')
					{
						$str = " ,rate='".$post['rate_'.$template_order_id]."'";	
					}
				
				$stock = explode("==",$post['stk_invoice_id'.$template_order_id]);				
				$sql="UPDATE invoice_product_test SET net_weight='".$post['netweight_'.$template_order_id]."',item_no ='".$post['item_no_'.$template_order_id]."' WHERE invoice_product_id ='".$stock[1]."'" ;
				$this->query($sql);
				
				$sql2="UPDATE invoice_color_test SET net_weight='".$post['netweight_'.$template_order_id]."' $str   WHERE invoice_color_id ='".$stock[2]."'" ;
				$this->query($sql2);
				
				
				
				$stock_array = array('detail'=>$stock[2],
									'per_qty'=>$post['box_qty_'.$template_order_id],
									'per_box_weight'=>$post['boxweight_'.$template_order_id],
									'total_box'=>$post['total_box_'.$template_order_id],
									'invoice_id'=>$stock[0],
									'in_gen_id'=>'0',
									'net_weight'=>$post['netweight_'.$template_order_id],
									'qty'=>'',
									'in_product_id'=>$stock[1],
									);
				$save_stock_box = $this->savelabeldetail($stock_array);
			}
		}
		if(isset($post['multi_custom_order_id']))
		{
			foreach($multi_custom_order_id_arr as $key=>$multi_custom_order_id)
			{
				$cust_array = explode("==",$multi_custom_order_id);
				$multi_custom_order_id_1 = $cust_array[0];
				$custom_order_id = $cust_array[1];			
				
				//[sonu] added on 12-4-2017 for custom 
				if($post['cust_mea_'.$custom_order_id] == '')
				{
					$measurement = '1';
				}else{
					$measurement =  $post['cust_mea_'.$custom_order_id] ;
				}
				//eNd
				
				
			
				 
			
				 
				 //die;
				 
				 $cust = explode("==",$post['cust_invoice_id'.$custom_order_id]);
				if($post['cust_product_id_'.$custom_order_id]!='6'){
        			
        				
        				$sql="UPDATE invoice_product_test SET net_weight='".$post['netweight_cust_'.$custom_order_id]."' WHERE invoice_product_id ='".$cust[1]."'" ;
        				$this->query($sql);
        				
        				$sql2="UPDATE invoice_color_test SET net_weight='".$post['netweight_cust_'.$custom_order_id]."',color_text='".addslashes($post['job_card_name_'.$custom_order_id])."',measurement='".$measurement."' WHERE invoice_color_id ='".$cust[2]."'" ;
        				$this->query($sql2);
        				
        				$cust_array = array('detail'=>$cust[2],
        									'per_qty'=>$post['box_qty_cust_'.$custom_order_id],
        									'per_box_weight'=>$post['boxweight_cust_'.$custom_order_id],
        									'total_box'=>$post['total_box_cust_'.$custom_order_id],
        									'invoice_id'=>$cust[0],
        									'in_gen_id'=>'0',
        									'net_weight'=>$post['netweight_cust_'.$custom_order_id],
        									'qty'=>'',
        									'in_product_id'=>$cust[1],
        									);
        			$save_cust_box = $this->savelabeldetail($cust_array);
        									
				}else{
				    	$sql="UPDATE invoice_product_test SET net_weight='".$post['netweight_cust_'.$custom_order_id]."' WHERE invoice_product_id ='".$cust[1]."'" ;
        				$this->query($sql);
        				
        				$sql2="UPDATE invoice_color_test SET net_weight='".$post['netweight_cust_'.$custom_order_id]."',color_text='".addslashes($post['job_card_name_'.$custom_order_id])."',measurement='".$measurement."' WHERE invoice_color_id ='".$cust[2]."'" ;
        				$this->query($sql2);
        		
				    	 $roll_net_data=json_decode($post['cust_roll_details_'.$custom_order_id]);
				    	 
			        	   $cust_roll_array=array();
    				    	 if(!empty($roll_net_data->roll)){
        				    	     foreach($roll_net_data->roll as $key=>$netdata){
        				    	         
        				    	 
            				    	         	$cust_roll_array = array('detail'=>$cust[2],
                    									//'per_qty'=>1,
                    									'per_qty'=>$netdata->Rbox_qty,
                    									'per_box_weight'=>$netdata->RboxWeight,
                    									'total_box'=>$netdata->Rtotalbox,
                    									'invoice_id'=>$cust[0],
                    									'in_gen_id'=>'0',
                    									'net_weight'=>$netdata->Rnetweight,
                    									'qty'=>$netdata->Rbox_qty,
                    									'in_product_id'=>$cust[1],
                    									);
                    							 $save_cust_box = $this->savelabeldetail($cust_roll_array);
        				    	         
        				    	     }
    				    	 }
				    	 
				    	
				    	 
				} 
		
				
			//	die;
		
			}
		}
	}
	
	public function getUserCurrencyByUser($curr_id)
	{
		$sql="SELECT cu.currency_id FROM country as co, currency as cu WHERE co.country_id='".$curr_id."' AND cu.currency_code=co.currency_code";
		$data = $this->query($sql);
		return $data->row['currency_id'];	
	}
	public function alldone($invoice_id)
	{
	   $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');      	 
		$sql = "UPDATE `" . DB_PREFIX . "invoice_test` SET done_status='1' ,done_by='".$by."' WHERE invoice_id = '".$invoice_id."'";	
		$data = $this->query($sql);
		
	}
	public function updateuploadstatus($invoice_id,$customer_name='')
	{
		$sql = "UPDATE `" . DB_PREFIX . "invoice_test` SET done_status='2' WHERE invoice_id = '".$invoice_id."'";	
		//$sql = "UPDATE `" . DB_PREFIX . "invoice` SET done_status='2' WHERE customer_name = '".$customer_name."'";	
		
		$data = $this->query($sql);	
		//printr($data);	
	}
	public function getProductdeatilsForSample($invoice_no)
	{
		$sql="SELECT ip.*,ic.*,p.product_name FROM invoice_product_test as ip,invoice_color_test as ic,product as p WHERE ip.invoice_id='".$invoice_no."' AND ip.invoice_product_id=ic.invoice_product_id AND p.product_id=ip.product_id";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	public function change_qty_per_kg($invoice_id,$value,$n)
	{ //last updated on 16-1-2017 plz check back up for that [kinjal]
		if($n==0)
			$cond = "qty_in_kgs='".$value."'";
		else if($n==1)
			$cond = "rate_in_kgs='".$value."'";
			
		$ex= explode("A",$invoice_id);
		$im = implode(",",$ex);
		 $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');      	 
		$sql = "UPDATE `" . DB_PREFIX . "invoice_color_test` SET $cond ,date_modify = '".date('Y-m-d H:i:s')."',edit_by='".$by."' WHERE invoice_color_id IN (".$im.")";
		//echo $sql;	
		$data = $this->query($sql);	
	}
	public function shipedMailDetail($buyers_orderno,$order_uder_id,$date_added)
	{
		$stock_o_no = $custom_o_no = '';
			
			//$_POST['date_added']='';
			$order_nos = explode(",",$buyers_orderno);
		    $cust=array();
			foreach($order_nos as $o_no)
			{
				if (strpos($o_no, 'CUST') !== false)
				{
					 $cust[]=$o_no;
					 
				}
				else
				{
					$stk[]=$o_no;
					
				}
			}
		
			if(isset($cust))
				$custom_o_no = implode(",",$cust);
			if(isset($stk))
				$stock_o_no = implode(",",$stk);
				
			$stock_order_number_arr = $this->getStockId($stock_o_no);
			$multi_custom_order_id_arr = $this->getCustomId($custom_o_no);
			
			$firsttime = true;
			$sodh=$sodh_client='';
			$orders_user_id = $order_uder_id;
			$upload_time=$date_added;
			
			//printr($multi_custom_order_id_arr);
			//printr($stock_order_number_arr);
			
			if(!empty($stock_order_number_arr))
			{
				foreach($stock_order_number_arr as $stock_order_id)
				{	
					if($firsttime)
					{	
						$sodh .= 'so.stock_order_id = "'.$stock_order_id['stock_order_id'].'"';
						$sodh_client .= 't.client_id = "'.$stock_order_id['client_id'].'"';
						$firsttime = false;
					}
					else
					{
						$sodh .= 'OR so.stock_order_id = "'.$stock_order_id['stock_order_id'].'"';
						$sodh_client .= 'OR t.client_id = "'.$stock_order_id['client_id'].'"';
					}
					
				}
				$orders = $this->GetStockOrderList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],'AND t.status = 1',$stock_order_id['client_id'],$stock_order_id['stock_order_id'],$sodh_client,$sodh,'',$orders_user_id,$upload_time);
				//printr($orders);
				foreach($orders as $key=>$stock_order)
				{
				   foreach($stock_order as $stock)
				   {
						foreach($stock as $s_data)
						{
							$post[] =($s_data['template_order_id'].'=='.$s_data['product_template_order_id'].'=='.$stock_order_id['client_id']);
						}
				   }
				}
				
				//
			}
			
			$cust_cond='';
			$ftime=true;
			if(!empty($multi_custom_order_id_arr))
			{
				foreach($multi_custom_order_id_arr as $multi_custom_order_id)
				{	
					if($ftime)
					{	
						$cust_cond .= 'mco.multi_custom_order_id = "'.$multi_custom_order_id['multi_custom_order_id'].'"';
						$ftime = false;
					}
					else
					{
						$cust_cond .= 'OR mco.multi_custom_order_id = "'.$multi_custom_order_id['multi_custom_order_id'].'"';
					}
					
				}
				$getData = " custom_order_id,mco.added_by_user_id,mco.added_by_user_type_id, customer_name, shipment_country_id,mco.multi_custom_order_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,mco.status,mco.custom_order_status,discount";
				$data = $this->getCustomOrder($cust_cond,$getData,$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$orders_user_id,$upload_time);
				//printr($data);
				$cust_qty='';
			$fsttime=true;
			foreach($data as $custom_order_id)
			{	
				if($fsttime)
				{	
					$cust_qty .= 'mcoq.custom_order_id = "'.$custom_order_id['custom_order_id'].'"';
					$fsttime = false;
				}
				else
				{
					$cust_qty .= 'OR mcoq.custom_order_id = "'.$custom_order_id['custom_order_id'].'"';
				}
				$cust_o_id[$custom_order_id['multi_custom_order_id']]=$custom_order_id['multi_custom_order_number'];
			}
			$result_cust = $this->getCustomOrderQuantity($cust_qty);
			  if($result_cust!='')
				$quantityData[] =$result_cust;
			  } 
		  
			  if(!empty($quantityData))
			  {
					foreach($quantityData as $k=>$qty_data)
					{
						foreach($qty_data as $tag=>$qty)
						{
							foreach($qty as $q=>$arr)
							{
								$new_data[$tag][$q]=$arr;
							}
						}	
					}
					$cust_total = count($new_data);
					foreach($new_data as $k=>$qty_data)
					{	
						foreach($qty_data as $skey=>$sdata)
						{
							foreach($sdata as $soption)
							{
								$post[] =($soption['custom_order_id'].'=='.$soption['multi_custom_order_id']);
							}
						}
					}
			}
			
			return $post;
	}
	
	public function saveImportCharges($data)
	{
		//printr($data);//die;
		$col_data=$this->getProductdeatilsForSample($data['gen_invoice_id']);
		//printr($col_data);die;
		foreach($col_data as $col)
		{
			//printr($col);
			//$import_charges=$data['cifamount']+$data['fobamount']+$data['customduty']+$data['voti']+$data['gst']+$data['othercharges']+$data['clearingcharges'];
			
			$import_charges=$data['customduty']+$data['othercharges']+$data['clearingcharges'];
			
			$rate=($col['rate']/$data['cifamount'])*($import_charges);
			$rate_por=$rate+$col['rate'];
			$sqlcol="UPDATE `" . DB_PREFIX . "invoice_color_test` SET rate_with_proportion='".$rate_por."',total_import_charges='".$import_charges."' WHERE invoice_id = '".$data['gen_invoice_id']."' AND invoice_color_id= '".$col['invoice_color_id']."' ";
			//echo $sqlcol.'</br>';
			$this->query($sqlcol);
		}
		//die;
		$sql="UPDATE `" . DB_PREFIX . "invoice_test` SET CIF_amt='".$data['cifamount']."',FOB_amt='".$data['fobamount']."',custom_duty='".$data['customduty']."',voti='".$data['voti']."',
		GST_on_import='".$data['gst']."',other_charges='".$data['othercharges']."',clearing_charges='".$data['clearingcharges']."',agent_name='".$data['agentname']."',agent_address='".$data['agentaddress']."',ABN_no='".$data['abnno']."',agent_emailid='".$data['mailid']."',import_status='1',purchase_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',purchase_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' WHERE invoice_id = '".$data['gen_invoice_id']."'";
		$this->query($sql);	
		
		//printr($data['gen_country_id']);
		//deviation report calculation [kinjal] on 9-12-3016
		$sql_agent = 'SELECT * FROM import_charges WHERE country_id="'.$data['gen_country_id'].'" AND is_delete=0';
		$data_agent= $this->query($sql_agent);
		//printr($data_agent);
		$html='';
		$dev_custom_per=$dev_gst_per=$dev_other_per=$dev_clearing_per='0';
		if($data_agent->num_rows)
		{
			foreach($data_agent->rows as $agent)
			{
				if(strtoupper($agent['agent_name'])==strtoupper($data['agentname']))
				{
					$custom_duty = (($data['customduty'] * 100)/$data['fobamount']); // for get custom duty charge in %
					$custom_duty_in_erp = $agent['custom_duty'];
					
					$gst_on_import = (($data['gst'] * 100)/$data['fobamount']); // for get gst charge in %
					$gst_on_import_in_erp = $agent['Gst_on_import'];
					
					$other_charsges_by_user = $data['othercharges'];
					$other_charges_in_erp = $agent['other_charges'];
					
					$clearing_charges  = (($data['clearingcharges'] * 100)/$data['fobamount']);
					$clearing_charges_in_erp = $agent['clearing_charges'];
					
					$html.='<p>As Per Our Standard Charges ';
					
					$diff_custom_duty = $custom_duty_in_erp - $custom_duty;
					if ($diff_custom_duty < 0) 
					{
						$html.='<b>Custom Duty Charge</b> is '.number_format($custom_duty_in_erp,2).' % And your specified charge is '.number_format($custom_duty,2).' % ,';
						$dev_custom_per = $custom_duty;
					} 
					
					$diff_gst_on_import = $gst_on_import_in_erp - $gst_on_import;
					if ($diff_gst_on_import < 0) 
					{
						$html.='<b>Gst On Import Charge</b> is '.number_format($gst_on_import_in_erp,2).' % And your specified charge is '.number_format($gst_on_import,2).' % ,';
						$dev_gst_per = $gst_on_import;
					}
					
					$diff_other_charges = $other_charges_in_erp - $other_charsges_by_user;
					if ($diff_other_charges < 0) 
					{
						$html.='<b>Other Charge</b> is '.$other_charges_in_erp.' And your specified charge is '.$other_charsges_by_user.' ,';
						$dev_other_per = $other_charsges_by_user;
					}
					
					$diff_clearing_charges = $clearing_charges_in_erp - $clearing_charges;
					if ($diff_clearing_charges < 0) 
					{
						$html.='<b>Clearing Charge</b> is '.number_format($clearing_charges_in_erp,2).' % And your specified charge is '.number_format($clearing_charges,2).' % ,';
						$dev_clearing_per=$clearing_charges;
					}
					
					$html.='so how this difference is come ?</p><br>
							<p>Please Give us Clarification and Explanation for this. </p>';
					$html .='<link rel="stylesheet" href="'.HTTP_SERVER.'css/invoice.css">';
					
					//$vijay_email_id=$this->getUser('user_id','user_type_id');	gen_email_address	
					$vijay_email_id = 'servicetax@zinzuwadiaco.com'; 
					$email_temp[]=array('html'=>$html,'email'=>$data['admin_email']);
					$email_temp[]=array('html'=>$html,'email'=>$data['gen_email_address']);
					$email_temp[]=array('html'=>$html,'email'=>$vijay_email_id);
					
					$form_email=$data['admin_email'];
					
					$obj_email = new email_template();
					$rws_email_template = $obj_email->get_email_template(7); 
					$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
					$path = HTTP_SERVER."template/proforma_invoice.html";
					$output = file_get_contents($path);  
					$search  = array('{tag:header}','{tag:details}');
					$signature = 'Thanks.';
					
					$subject = 'Deviation Report For '.$data['gen_inv_number']; 
					
					foreach($email_temp as $val)
					{
						$toEmail =$form_email;
						$firstTimeemial = 1;								
						$message = '';
						if($val['html'])
						{
							$tag_val = array(
							"{{header}}"=>$subject,
								"{{PouchMakersDetail}}" =>$val['html'],
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
						send_email($val['email'],$form_email,$subject,$message,'','');
					}
				}
			}
		}
		
		//[kinjal] added query 13/12/2016
		$sql_data = "UPDATE `" . DB_PREFIX . "invoice_test` SET custom_duty_deviation_per='".$dev_custom_per."',GST_on_import_deviation_per='".$dev_gst_per."',other_charges_deviation_per='".$dev_other_per."',clearing_charges_deviation_per='".$dev_clearing_per."' WHERE invoice_id = '".$data['gen_invoice_id']."'";
		$this->query($sql_data);
		//sonu add query 12/12/2016
		//$sql_data="INSERT INTO deviation_report set invoice_id='".$data['gen_invoice_id']."',custom_duty_charge='".$custom_duty."',gst_on_import_charge ='".$gst_on_import."',other_charge='".$other_charsges_by_user."',clearing_charge='".$clearing_charges."',date_added = NOW(), date_modify = NOW(),status=1, is_delete = 0,close_status=0";
		//$data_insert = $this->query($sql_data);
		//echo $sql_data;
		
	} 
	
	public function convertInPurchase($data)
	{
		//printr($data);
		$data_inv = $this->query("SELECT * FROM invoice_test  WHERE invoice_id = '".$data['con_invoice_id']."'");

//	printr($data_inv);
		$sql_inv = "INSERT INTO `" . DB_PREFIX . "invoice` SET invoice_no = '".$data_inv->row['invoice_no']."',order_user_id='".$data_inv->row['order_user_id']."',done_status='".$data_inv->row['done_status']."',customer_dispatch='".$data_inv->row['customer_dispatch']."',igst_status='".$data_inv->row['igst_status']."',show_pallet='".$data_inv->row['show_pallet']."',order_type='".$data_inv->row['order_type']."',invoice_date = '" .$data_inv->row['invoice_date']. "',exporter_orderno = '".$data_inv->row['exporter_orderno']."',gst='".$data_inv->row['gst']."',company_address='".$data_inv->row['company_address']."',bank_address='".$data_inv->row['bank_address']."', buyers_orderno ='".$data_inv->row['buyers_orderno']."',other_ref ='".$data_inv->row['other_ref']."',pre_carrier='".$data_inv->row['pre_carrier']."',consignee='".addslashes($data_inv->row['consignee'])."',buyer='".$data_inv->row['buyer']."',country_destination='".$data_inv->row['country_destination']."',vessel_name='".$data_inv->row['vessel_name']."',customer_name = '".addslashes($data_inv->row['customer_name'])."', email = '".$data_inv->row['email']."',port_load='".$data_inv->row['port_load']."',port_discharge='".$data_inv->row['port_discharge']."',final_destination='".$data_inv->row['final_destination']."', taxation='".$data_inv->row['taxation']."', excies='".$data_inv->row['excies']."', tax='".$data_inv->row['tax']."', tax_mode ='".$data_inv->row['tax_mode']."',tax_form ='".$data_inv->row['tax_form']."' ,payment_terms='".$data_inv->row['payment_terms']."',delivery='".$data_inv->row['delivery']."',HS_CODE='".$data_inv->row['HS_CODE']."',pouch_type='".$data_inv->row['pouch_type']."',pouch_desc='".$data_inv->row['pouch_desc']."',tran_desc='".$data_inv->row['tran_desc']."',tran_charges='".$data_inv->row['tran_charges']."',cylinder_charges='".$data_inv->row['cylinder_charges']."',container_no='".addslashes($data_inv->row['container_no'])."',rfid_no='".addslashes($data_inv->row['rfid_no'])."',seal_no='".addslashes($data_inv->row['seal_no'])."', postal_code = '".$data_inv->row['postal_code']."',account_code = '".$data_inv->row['account_code']."',sent = '".$data_inv->row['sent']."',invoice_status = '".$data_inv->row['invoice_status']."',transportation='".encode($data_inv->row['transportation'])."',curr_id='".$data_inv->row['curr_id']."',status = '".$data_inv->row['status']."',date_added = NOW(),date_modify = NOW(),user_id='".$data_inv->row['user_id']."',user_type_id='".$data_inv->row['user_type_id']."',is_delete=0";
		$this->query($sql_inv);
		
		
		//printr($sql_inv);die;
		$invoice_id = $this->getLastId();
		
		$data_pro = $this->query("SELECT * FROM invoice_product_test  WHERE invoice_id = '".$data['con_invoice_id']."'");
		
//	printr($data['con_invoice_id']);
	
		if($data_pro->num_rows)
		{
		    foreach($data_pro->rows as $row)
		    {
	            //  printr($row);
	            	$sql_pro = "Insert into invoice_product SET product_code_id='".$row['product_code_id']."',invoice_id='".$invoice_id."',product_id='".$row['product_id']."', valve = '".$row['valve']."', zipper = '".$row['zipper']."', spout = '".$row['spout']."',make_pouch='".$row['make_pouch']."', accessorie = '".$row['accessorie']."',net_weight='".$row['net_weight']."',measurement_two='".$row['measurement_two']."',buyers_o_no='".$row['buyers_o_no']."',item_no='".$row['item_no']."', date_added = NOW(), date_modify = NOW(), is_delete = 0"; 
		            $this->query($sql_pro);
		            $invoice_product_id = $this->getLastId();
		            echo $sql_pro;
		            
		            $sql_clr = $this->query("SELECT * FROM invoice_color_test  WHERE invoice_product_id = '".$row['invoice_product_id']."' AND invoice_id = '".$data['con_invoice_id']."'");
		           // printr($sql_clr);
		            $sql_color = "Insert into invoice_color SET invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$sql_clr->row['color']."',color_text='".addslashes($sql_clr->row['color_text'])."', rate ='".$sql_clr->row['rate']."', qty = '".$sql_clr->row['qty']."', rack_status = '".$sql_clr->row['qty']."',size = '".$sql_clr->row['size']."',  measurement = '".$sql_clr->row['measurement']."',dimension='".$sql_clr->row['dimension']."',net_weight='".$sql_clr->row['net_weight']."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 			
                    $this->query($sql_color);
                    $invoice_color_id = $this->getLastId();
                    
                    $data_ingen = $this->query("SELECT * FROM in_gen_invoice_test  WHERE invoice_id = '".$data['con_invoice_id']."' AND is_delete=0 AND invoice_color_id = '".$sql_clr->row['invoice_color_id']."' AND invoice_product_id = '".$row['invoice_product_id']."'");
                    foreach($data_ingen->rows as $clr)
                    {
			            $sql_in= "INSERT INTO in_gen_invoice SET invoice_id='".$invoice_id."',invoice_color_id='".$invoice_color_id."',qty='".$clr['qty']."', box_weight ='".$clr['box_weight']."', net_weight ='".$clr['net_weight']."',box_unique_id='".$clr['box_unique_id']."',parent_id='".$clr['parent_id']."',box_unique_number='".$clr['box_unique_number']."',date_added = NOW(),date_modify = NOW(),box_no='".$clr['box_no']."',invoice_product_id='".$invoice_product_id."',is_delete = 0"; 	
                        $this->query($sql_in);
                    }
                    
		    }
		}
		
		$data_pallet = $this->query("SELECT * FROM  invoice_pallet_test  WHERE invoice_id = '".$data['con_invoice_id']."'");
		if($data_pallet->num_rows)
		{
		    foreach($data_pro->rows as $row_pallet)
		    {
		        $data_pallet=$this->query("INSERT INTO invoice_pallet SET pallet_no='".$row_pallet['pallet_no']."', invoice_id='".$row_pallet['invoice_id']."'");
		    }
		}
		
		
		
		$sql_up = "UPDATE `" . DB_PREFIX . "invoice` SET import_status='2',purchase_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',purchase_user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' WHERE invoice_id = '".$invoice_id."'";
		$data_up = $this->query($sql_up);	
		
		$sql = "UPDATE `" . DB_PREFIX . "invoice_test` SET import_status='2',purchase_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',purchase_user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', convert_to_purchase_date=NOW() WHERE invoice_id = '".$data['con_invoice_id']."'";	
		$data = $this->query($sql);	
		
		
	//	die;
	}
	
	//sonu 10/12/2016
	public function sendemailtrackid($data)
	{
		
	                
		$sql = "UPDATE `" . DB_PREFIX . "invoice_test` SET track_id='".$data['trackid']."' WHERE invoice_id = '".$data['invoiceid']."'";	
		$data_sql = $this->query($sql);		
					
					
		$html='';
		
		$html.='Track Id :'. $data['trackid'];
		$html.='<br>Information :'. $data['trackinfo']; 			
		$email_id=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		//printr($email_id);					
		$email_temp[]=array('html'=>$html,'email'=>$data['admin']);
		$email_temp[]=array('html'=>$html,'email'=>$data['emailid']);
					
		$form_email=$email_id['email'];
		//printr($form_email);
		
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(7); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
		$path = HTTP_SERVER."template/proforma_invoice.html";
		$output = file_get_contents($path);  
		$search  = array('{tag:header}','{tag:details}');
		$signature = 'Thanks.';
		
		$subject = 'Tracking Id for '.$data['invoiceno']; 
		
		foreach($email_temp as $val)
		{
			$toEmail =$form_email;
			$firstTimeemial = 1;								
			$message = '';
			if($val['html'])
			{
				$tag_val = array(
				"{{header}}"=>$subject,
					"{{PouchMakersDetail}}" =>$val['html'],
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
			
			//printr($message);die;
		   send_email($val['email'],$form_email,$subject,$message,'','');
		}
	}
	
	public function getimportchargedetail($user_id,$user_type_id)
	{   
	  
		if($user_id == 1 && $user_type_id==1)
		{
			return false;
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
		
			
		
		}
	
			$sql="SELECT invoice_no,invoice_id FROM invoice_test WHERE import_status = 0 AND order_user_id='".$set_user_id."' AND transportation ='c2Vh' ";
			
			//$sql="SELECT invoice_no,invoice_id FROM invoice WHERE import_status = 0 AND purchase_user_id='".$user_id."' AND purchase_user_type_id='".$user_type_id."' ";
			
			$data=$this->query($sql);		
			
			if($data->num_rows)
			{
				return $data->rows;
			}
			else
			{
				return false;
			}
		
	}
	//sonu add 21/12/2016
	 public function  addinvoicetotalamount($invoice_no)
	 {
		 $invoice=$this->getInvoiceNetData($invoice_no);
		//printr($invoice);die;
		$pallet=$this->getPalletS($invoice_no);		
		$total_pallet=count($pallet);
		$total_pallet_box=0;
		foreach($pallet as $p)
		{   
		   
			$total_pallet_box=$p['tot']+$total_pallet_box;
			// printr($total_pallet_box);
		}
	
		$total_pallet_weight=$total_pallet*23;
		$invoice_qty=$this->getInvoiceTotalData($invoice_no);
		//printr($invoice_qty);die;
		$box_detail=$this->gettotalboxweight($invoice_no);
		
		$box_det=$this->gettotalboxweight($invoice_no,'1');
		
		$alldetails=$this->getProductdeatils($invoice_no);
		//printr($box_detail);die;
		//printr($alldetails);die;
		//$tot_qty_scoop=0;die;
		$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_no = $con_no = $gls_no =$val_no ='';
		$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series = $con_series =$gls_series =$val_series ='';
		
		$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper = $total_amt_con=$total_amt_gls=$total_amt_val=0;
		
		
		$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty = $tot_paper_qty =  $tot_con_qty = $tot_gls_qty = $tot_val_qty =0;
		$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_paper_rate =$tot_con_rate =$tot_gls_rate =$tot_val_rate = 0;
		$abcd = ' A ';
		foreach($alldetails as $details)
		{
			//printr($details);
			//$flag=0;
			if($details['product_id']=='11')
			{
				$tot_qty_scoop=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_scoop_qty = $tot_scoop_qty + $tot_qty_scoop['total']; 
				$tot_scoop_rate = $tot_scoop_rate + $tot_qty_scoop['rate'];
				$total_amt_scoop = $total_amt_scoop + $tot_qty_scoop['tot_amt'];
				$scoop_no = '1';
				
				$scoop_series = 'B';
			}
			else if($details['product_id']=='6')
			{
				$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
				$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
				$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
				$roll_no = '1';
				$roll_series = 'C';
			}
			else if($details['product_id']=='10')
			{
				$tot_qty_mailer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_mailer_qty = $tot_mailer_qty + $tot_qty_mailer['total']; 
				$tot_mailer_rate = $tot_mailer_rate + $tot_qty_mailer['rate'];
				$total_amt_mailer = $total_amt_mailer + $tot_qty_mailer['tot_amt'];
				$mailer_no = '1';
				$mailer_series = 'C';
			}
			else if($details['product_id']=='23')
			{
				$tot_qty_sealer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_sealer_qty = $tot_sealer_qty + $tot_qty_sealer['total']; 
				$tot_sealer_rate = $tot_sealer_rate + $tot_qty_sealer['rate'];
				$total_amt_sealer = $total_amt_sealer + $tot_qty_sealer['tot_amt'];
				$sealer_no = '1';
				$sealer_series = $abcd;
			}
			else if($details['product_id']=='18')
			{
				$tot_qty_storezo=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_storezo_qty = $tot_storezo_qty + $tot_qty_storezo['total']; 
				$tot_storezo_rate = $tot_storezo_rate + $tot_qty_storezo['rate'];
				$total_amt_storezo = $total_amt_storezo + $tot_qty_storezo['tot_amt'];
				$storezo_no = '1';
				$storezo_series = $abcd;
			}
			else if($details['product_id']=='34')
			{
				$tot_qty_paper=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_paper_qty = $tot_paper_qty + $tot_qty_paper['total']; 
				$tot_paper_rate = $tot_paper_rate + $tot_qty_paper['rate'];
				$total_amt_paper = $total_amt_paper + $tot_qty_paper['tot_amt'];
				$paper_no = '1';
				$paper_series = $abcd;
			}
			else if($details['product_id']=='47')
			{
				$tot_qty_con=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_con_qty = $tot_con_qty + $tot_qty_con['total']; 
				$tot_con_rate = $tot_con_rate + $tot_qty_con['rate'];
				$total_amt_con = $total_amt_con + $tot_qty_con['tot_amt'];
				$con_no = '1';
				$con_series = $abcd;
			}
			else if($details['product_id']=='48')
			{
				$tot_qty_gls=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_gls_qty = $tot_gls_qty + $tot_qty_gls['total']; 
				$tot_gls_rate = $tot_gls_rate + $tot_qty_gls['rate'];
				$total_amt_gls = $total_amt_gls + $tot_qty_gls['tot_amt'];
				$gls_no = '1';
				$gls_series = $abcd;
			}
			else if($details['product_id']=='63')
			{
				$tot_qty_val=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_val_qty = $tot_val_qty + $tot_qty_val['total']; 
				$tot_val_rate = $tot_val_rate + $tot_qty_val['rate'];
				$total_amt_val = $total_amt_val + $tot_qty_val['tot_amt'];
				$val_no = '1';
				$val_series = $abcd;
			}
			$abcd++;
			//printr($abcd);
			/*if($flag == '1')
			{
				$flag1[] =$tot_qty_scoop;
				
			}*/
			
			//$scoop = array($flag);
			//$flag1[$flag == '1' ? 'Scoop' : 'flag'] = $flag;
			
		}
		
		$insurance='0';
		$totgross_weight=$box_detail['total_net_weight']+$box_detail['total_box_weight']+$box_det['total_net_weight'];
		$taxation=$invoice['taxation'];		
		$currency=$this->getCurrencyName($invoice['curr_id']);
		 $total_no_of_box=$this->getTotalBox($invoice_no);
		 $measurement=$this->getMeasurementName($invoice['measurement']);
				if($scoop_no == '1')
								{	
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty);
									//$total_rate_val=$tot_scoop_rate;

									$total_amt_val=$invoice_qty['tot'];	
								}
								if($roll_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty);
									//printr($tot_roll_rate);
									//$total_rate_val=$tot_roll_rate;
									$total_amt_val=$invoice_qty['tot'];	
								}
								if($mailer_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty);
									//$total_rate_val=$tot_mailer_rate;
									$total_amt_val=$invoice_qty['tot'];
								}
								if($sealer_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty);
									//$total_rate_val=$tot_sealer_rate;
									$total_amt_val=$invoice_qty['tot'];
								}
								if($storezo_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty);
									//$total_rate_val=$tot_storezo_rate;
									$total_amt_val=$invoice_qty['tot'];
									
								}
								if($paper_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
									
								}
								if($con_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
									
								}if($gls_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
									
								}
								else
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty+$tot_con_qty+$tot_gls_qty);
									///$total_rate_val=$invoice_qty['rate'];
									//$total_amt_val=$total_qty_val*$invoice_qty['rate'];
									$total_amt_val=$invoice_qty['tot'];
								}
			
			
								$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls))/$total_qty_val),8);
								//$amt = ($total_amt_val-$invoice['tran_charges'])-$invoice['cylinder_charges']-($total_rate_val*$tot_scoop_qty)-($total_rate_val*$tot_roll_qty)-($total_rate_val*$tot_mailer_qty)-($total_rate_val*$tot_sealer_qty)-($total_rate_val*$tot_storezo_qty)-($total_rate_val*$tot_paper_qty);
								
								$amt = ($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo+$total_amt_con+$total_amt_gls));
								
								//printr($amt);
								
								//printr('('.$total_amt_val.'-'.$invoice['tran_charges'].')-'.$invoice['cylinder_charges'].'-('.$total_rate_val.'*'.$tot_scoop_qty.')-('.$total_rate_val.'*'.$tot_roll_qty.')');

								$air_rate = $amt * $currency['price'];
		
				 if(($invoice['country_destination']=='252'|| $invoice['order_user_id']==2) && ucwords(decode($invoice['transportation']))=='Air')
				 {
					$tran_charges_tot=round($invoice['tran_charges'],2)+$amt;
					$insurance=(($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100;
					
				 } 
			$final_amount=$total_amt_val+$insurance;
		$excies = '';
		$tax ='';
			
		$excies = $invoice['excies'];
		$tax = $invoice['tax'];			
		$excies_price = ($final_amount*$excies)/100;
		$tax_price = (($final_amount+$excies_price)*$tax)/100;
		$Total_price=($final_amount+$tax_price+$excies_price);
		
				if($currency['currency_code'] == 'INR') {
					$Total_price = round($Total_price);
				}else
					{
						$sample_products=$this->getProductdeatilsForSample($invoice_no);
							//printr($sample_products);
							$s_no=1;$sample_Total_price = 0;
							$firsttime = true;
						foreach($sample_products as $sample)
						{
							$qty = isset($sample['qty_in_kgs'])?$sample['qty_in_kgs']:'';
							$s_no++;
							$sample_Total_price=$sample_Total_price+($sample['qty']*$sample['rate']);
						}
		
					//printr($sample_Total_price);
					$final_amount = $Total_price = $sample_Total_price;	
		
				}
			//	printr($final_amount); 
				$sql = "UPDATE " . DB_PREFIX . "invoice_test SET invoice_total_amount = '" .$final_amount. "'WHERE invoice_id ='".$invoice_no."'";
	//		echo $sql;die;
				$data=$this->query($sql);
				
				
				
      
	 }
	 
	public function getIngenBox($invoice_id,$product_id=0,$charge=0)
	{
		
		if($product_id!='2')
			$pro_id= " AND ip.product_id = '".$product_id."'";
		else if($product_id=='2')
			$pro_id= " AND ip.product_id NOT IN (10,23,11,18,6,34,47,48,72,63,37,38)";
		
		$sql_pro="SELECT SUM(ic.qty) as total_qty,sum(ic.rate) as total_rate,sum(ic.rate*ic.qty) as total,ic.qty_in_kgs,ic.rate_in_kgs,GROUP_CONCAT(DISTINCT ic.invoice_color_id) as group_id_color FROM `invoice_product_test`as ip,invoice_color_test as ic WHERE ip.invoice_id='".$invoice_id."' AND ic.invoice_id='".$invoice_id."' AND ip.`invoice_product_id`=ic.`invoice_product_id` $pro_id " ;
		$data_pro = $this->query($sql_pro);
	//	echo $sql_pro;
		//
		$sql="SELECT COUNT(*) as total_box,SUM(ig.net_weight) as net, SUM(ig.net_weight+ig.box_weight) as gross FROM in_gen_invoice_test as ig,invoice_product_test as ip WHERE ig.invoice_id='".$invoice_id."' AND ig.is_delete=0 AND ig.invoice_product_id =ip.invoice_product_id AND ig.is_delete='0' $pro_id AND ip.invoice_id='".$invoice_id."'  AND ig.parent_id='0'";//
		$data = $this->query($sql);
		
		$sql1="SELECT COUNT(*) as total_box,SUM(ig.net_weight) as net, SUM(ig.net_weight+ig.box_weight) as gross FROM in_gen_invoice_test as ig,invoice_product_test as ip WHERE ig.invoice_id='".$invoice_id."' AND ig.is_delete=0 AND ig.invoice_product_id =ip.invoice_product_id AND ig.is_delete='0' $pro_id AND ip.invoice_id='".$invoice_id."'";//
		$data1 = $this->query($sql1);
//	printr($data_pro);
	//	printr($sql);
	//	printr($sql1);
		//$sql_count="";
		 
		if($data->num_rows)
		{
		    $count=1;
		    if($product_id==6){
		    	$count=count(explode(',',$data_pro->row['group_id_color']));
		    	$data_pro->row['total_rate']=($data_pro->row['total_rate']/$count);
		    }
		//	printr($count);
			//return $data->row;
			$d = array( 'total_box'=>$data->row['total_box'],
						 'qty' => $data_pro->row['total_qty'],
						 'g_wt' => $data1->row['gross'],
						 'n_wt' => $data1->row['net'],
						 'total_amt' =>$data_pro->row['total_rate']*$data->row['net'],
						 'total' =>$data_pro->row['total'],
						 'qty_in_kgs'=>$data_pro->row['qty_in_kgs'],
						 'rate_in_kgs'=>$data_pro->row['rate_in_kgs'],
						 'group_id'=>$data_pro->row['group_id_color']
						 );
			return $d;
		}
		else
			return false;		
	}
	
	public function getProductCode($invoice_product_id,$n=0)
	{
	//	printr($invoice_product_id);
		$sql="SELECT ip.*,ic.* FROM invoice_product_test as ip , invoice_color_test as ic WHERE ip.invoice_product_id='".$invoice_product_id."' AND ic. invoice_product_id ='".$invoice_product_id."'";
		
		$data=$this->query($sql);

    	if($n==1){
            		if($data->row['product_code_id']==0)
            	        	$sql_detail ="SELECT product_code,description FROM product_code WHERE product ='".$data->row['product_id']."' AND valve ='".$data->row['valve']."'AND zipper ='".decode($data->row['zipper'])."'AND spout ='".decode($data->row['spout'])."'AND accessorie ='".decode($data->row['accessorie'])."'AND make_pouch ='".$data->row['make_pouch']."'  AND	volume ='".$data->row['size']."' AND measurement ='".$data->row['measurement']."' AND color = '".$data->row['color']."'" ; 
            	    else    
            		       $sql_detail = "SELECT product_code,volume,description,product_code_id FROM product_code WHERE product_code_id = '".$data->row['product_code_id']."'";
    	}else{
    	    $sql_detail = "SELECT product_code,volume,description,product_code_id FROM product_code WHERE product_code_id = '".$data->row['product_code_id']."'";
    	}

	$data2=$this->query($sql_detail);		
		//	printr($data2);
			if($data2->num_rows)
			{
				return $data2->row;
			}
			else
			{
				return false;
			}
		
	}
	//sonu add 23-2-2017
	public function addremark($data)
	{
		
		//printr($data);die;
		$sql = "UPDATE `".DB_PREFIX."invoice_test` SET remarks='".$data['remarks']."' WHERE invoice_id='".$data['invoice_id']."'";			
		$data=$this->query($sql);		
			
			
	} 
	
	public function updateRateForCustomOrder($invoice_rate,$invoice_color_id)
	{
		$sql = "UPDATE ".DB_PREFIX."invoice_color_test SET  rate ='".$invoice_rate."',date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE invoice_color_id='".$invoice_color_id."'";
		//echo $sql;die;			
		$data=$this->query($sql);		
	}
	public function updateCylinderRate($cylinder_rate,$invoice_color_id)
	{
		$sql = "UPDATE ".DB_PREFIX."invoice_color_test SET  cylinder_rate ='".$cylinder_rate."' WHERE invoice_color_id='".$invoice_color_id."'";
		//echo $sql;die;			
		$data=$this->query($sql);		
	}
	public function updateCylinderNo($no_of_cylinder,$invoice_color_id)
	{
		$sql = "UPDATE ".DB_PREFIX."invoice_color_test SET  no_of_cylinder ='".$no_of_cylinder."' WHERE invoice_color_id='".$invoice_color_id."'";
		//echo $sql;die;			
		$data=$this->query($sql);		
	}
	public function getInternational_branch_detail($user_id,$user_type_id)
	{
		//printr($user_id.'==='.$user_type_id);
		if($user_id == 1 && $user_type_id==1)
		{
			//$sql = "SELECT * FROM international_branch WHERE is_delete = 0";
			return false;	
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
		
			
		
		
				$sql = "SELECT * FROM international_branch WHERE is_delete = 0 AND international_branch_id = '".$set_user_id."'  ";
				//echo $sql;
				//printr($data);
				$data=$this->query($sql);
			//	printr($data);
					if($data->num_rows){
						return $data->row;
					}else{
						return false;
					}
			}
	}
	

	
	//add by sonu
	
	public function set_series($invoice_id)
	{
		
		$sql="Select * from  in_gen_invoice_test WHERE invoice_id='".$invoice_id."' AND is_delete=0  ORDER BY in_gen_invoice_id ASC";
		$data=$this->query($sql);
    //	printr($data);die;
		$boxno=$this->in_gen_box_no($invoice_id);
			if(!empty($boxno))
				$box_no=$boxno+1;
			else
				$box_no=1;
		$i=0;
		foreach($data->rows as $r)
		{
    	  if($r['box_unique_id']!='0'){
    			$k=$i+1;
    			$this->query("UPDATE in_gen_invoice_test_new SET box_no= '".$k."' WHERE   in_gen_invoice_id=".$r['in_gen_invoice_id']." AND is_delete=0 ");
    			$sql1="UPDate in_gen_invoice_test SET box_no= '".$k."' WHERE   in_gen_invoice_id=".$r['in_gen_invoice_id']." AND is_delete=0 ";	
    		//	echo $sql1;die;
    			$this->query($sql1);
    			$i++;
    		  }
		}
	}
	//[kinjal] : 29-7-2017
	public function InoutLable($invoice_id,$parent_id=0,$from,$to)
	{	
		if($parent_id==0)
		{
		    $sql="select ic.dis_rate,ic.ref_no,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ig.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color_test as ii,pouch_color as c,template_measurement as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id,net_weight from in_gen_invoice_test WHERE parent_id='".$parent_id."' AND is_delete=0 AND box_no BETWEEN '".$from."' AND '".$to."') as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC "; 
		}
		else
		{
			$sql="select ic.dis_rate,ic.ref_no,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ig.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,
p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color_test as ii,pouch_color as c,template_measurement as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id,net_weight from in_gen_invoice_test WHERE parent_id='".$parent_id."' AND is_delete=0 ) as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC "; 

		}
	//	echo $sql.'<br><br>';//die;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	//END [kinjal]
	
	//india dispatch 11-8-2017
	
	//sonu add 
	
	
	public function getInvoice_for_dipatch_notification($user_type_id,$user_id,$note=0){
		
		if($user_type_id == 1 && $user_id == 1){
				$sql = "SELECT * FROM " . DB_PREFIX . "invoice_test WHERE is_delete = 0 AND invoice_date>='2017-08-16' AND generate_status='1'" ;
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
				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			}
			$sql = "SELECT * FROM " . DB_PREFIX . "invoice_test WHERE is_delete = 0 AND invoice_date>='2017-08-16' AND ( user_id = '".(int)$set_user_id."' AND user_type_id = '".(int)$set_user_type_id."' $str ) AND is_delete = 0 AND generate_status='1' AND rack_notify_status!=1" ;
		}
		
		$sql .=' AND date_added >="2017-01-02" GROUP BY invoice_id ';
		
		if($note=='1')
			$sql .=' ORDER BY date_added DESC';
		else
			$sql .=' ORDER BY invoice_date DESC';
			
		//echo $sql;
		$data = $this->query($sql);
	
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		
	}


	
		
	public function getInvoiceDetailsForDispatch($invoice_id)
	{
		//$sql = "SELECT ip.*,ic.* ,pc.* FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip, invoice_color_test as ic ,product_code as pc WHERE i.invoice_id =ip.invoice_id  AND i.invoice_id =ic.invoice_id AND i.invoice_id = '" .(int)$invoice_id. "' AND i.is_delete=0 AND  ic.invoice_product_id=ip.invoice_product_id AND pc.product_code_id = ip.product_code_id AND rack_status!=0 ";
		$sql = "SELECT ip.*,ic.*  FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip, invoice_color_test as ic WHERE i.invoice_id =ip.invoice_id  AND i.invoice_id =ic.invoice_id AND i.invoice_id = '" .(int)$invoice_id. "' AND i.is_delete=0 AND  ic.invoice_product_id=ip.invoice_product_id  AND rack_status!=0 AND ic.color!='-1'";

		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			/*foreach($data->rows as $r)
			{
			  // printr($r);
			  if($r['valve']=='With Valve')
			  {
			     $sql = "SELECT ip.*,ic.* ,pc.* FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip, invoice_color_test as ic ,product_code as pc WHERE i.invoice_id =ip.invoice_id  AND i.invoice_id =ic.invoice_id AND i.invoice_id = '" .(int)$invoice_id. "' AND i.is_delete=0 AND  ic.invoice_product_id=ip.invoice_product_id AND pc.product_code_id = ip.product_code_id AND rack_status!=0 ";
			  }
			}*/
			return $data->rows;
		}else{
			return false;
		}
	}
	public function changeRackStatusSales($invoice_id)
	{
		$sql = "SELECT ip.rack_status FROM `" . DB_PREFIX . "invoice_color_test` as ip WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete='0' AND ip.rack_status='0'";
		$data = $this->query($sql);
		$count = $data->num_rows;
		//printr($sql);
		$inv_product=$this->getSalesInvoiceProduct($invoice_id,$n='0');
        $count_pro = count($inv_product);
		//printr($count_pro);die;
		if($count == $count_pro)
		{
			$sql1 ="UPDATE invoice_test SET rack_notify_status='1' WHERE 	invoice_id='".$invoice_id."' ";
			$this->query($sql1);
			
		}
	}
	
	public function getSalesInvoiceProduct($invoice_id,$n='')
	{
		$str='';
		if($n=='')
			$str = " AND ip.rack_remaining_qty!='0' ";
			
		$sql = "SELECT ip.*,p.product_name,p.product_id,pc.product_code FROM `" . DB_PREFIX . "invoice_product_test` as ip,product p,product_code as pc WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0 AND pc.product_code_id=ip.product_code_id AND pc.product=p.product_id ".$str;
		//echo $sql;
		$data = $this->query($sql);
		//printr($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	} 
	public function getproductcd($product_code_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE product_code_id = '" .$product_code_id. "' AND is_delete='0' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	public function getnovalvepro($data)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE product = '".$data['product']."' AND zipper = '".$data['zipper']."' AND spout = '".$data['spout']."' AND accessorie = '".$data['accessorie']."' AND make_pouch = '".$data['make_pouch']."' AND color = '".$data['color']."' AND volume = '".$data['volume']."' AND measurement = '".$data['measurement']."' AND valve = 'No Valve' AND is_delete='0' ";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	public function getvaccumepro($data,$n)
	{
		if($n==1)
			$product_id = 12;
		elseif($n==2)
		    $product_id = 53;
		else
			$product_id = 42;
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE product = '".$product_id."' AND zipper = '".$data['zipper']."' AND spout = '1' AND accessorie = '".$data['accessorie']."' AND make_pouch = '4' AND color = '".$data['color']."' AND volume = '".$data['volume']."' AND measurement = '".$data['measurement']."' AND valve = 'No Valve' AND is_delete='0' ";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	public function change_price($post)
	{
		//printr($post);die;
		$alldetails=$this->getProductdeatils($post['form_invoice_id']);
		//printr($alldetails);die;
		$total_qty = $post['invoice_total_qty'];
		foreach($alldetails as $detail)
		{
			if($detail['product_id'] =='18'){
    	    
    	    //printr($detail);
        		if($total_qty < 100)
        		{
        			$quantitycolname = 'quantity100';
        		}
        		else if($total_qty >= 100 && $total_qty < 200)
        		{
        			$quantitycolname = 'quantity200';
        		}
        		else if($total_qty >= 200 && $total_qty < 500)
        		{
        			$quantitycolname = 'quantity500';
        		}
        		else
        		{
        		    $quantitycolname = 'quantity500';
        		}
			}else{
				  if($total_qty < 2000)
					{
						$quantitycolname = 'quantity1000';
					}
					else if($total_qty >= 2000 && $total_qty < 5000)
					{
						$quantitycolname = 'quantity2000';
					}
					else if($total_qty >= 5000 && $total_qty < 10000)
					{
						$quantitycolname = 'quantity5000';
					}
					else
					{
						$quantitycolname = 'quantity10000';
					}
				}
			
			//$dataqty1 = array();
			if($detail['order_size_id']!=0)
			{
				$sqlqty1 = "SELECT ".$quantitycolname." as quantity FROM ".DB_PREFIX."product_template_size WHERE product_template_size_id = '".$detail['order_size_id']."' AND is_delete = '0'";
				//echo $sqlqty1
				$dataqty1=$this->query($sqlqty1);
                if($detail['product_id'] !='11')
                {
    				$sql1 ="UPDATE invoice_color_test SET rate='".$dataqty1->row['quantity']."' WHERE 	invoice_id='".$post['form_invoice_id']."' AND invoice_product_id = '".$detail['invoice_product_id']."' ";
    				$this->query($sql1);
                }
			}
			//printr($dataqty1);//die;
		}
		//die;
		
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
	public function getProductCdDetails($product_code)

	{

		$result=$this->query("SELECT pc.product_code,pc.valve,pc.spout,pc.product_code_id, pc.description, clr.color,clr.pouch_color_id,pc.accessorie,pc.make_pouch, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.product_code LIKE '%".$product_code."%' AND pc.is_delete=0 AND pc.color=clr.pouch_color_id AND p.product_id = pc.product AND pc.status=1");

	//	printr($result);

	return $result->rows;

	}
	public function product_code_details($product_code_id)

	{

		$result=$this->query("SELECT pc.product_code,pc.valve,pc.spout,pc.product_code_id, pc.description, clr.color,clr.pouch_color_id,pc.accessorie,pc.make_pouch, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.product_code_id = '".$product_code_id."' AND pc.is_delete=0 AND pc.color=clr.pouch_color_id AND p.product_id = pc.product AND pc.status=1");

	//	printr($result);

	return $result->row;

	}
	public function getInvoiceProductColorId($invoice_product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_color_test` WHERE invoice_product_id = '" .(int)$invoice_product_id. "'  AND is_delete=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	
	public function GetdigitalColorName($pouch_color_id){
	    
	  $arr=explode("==",$pouch_color_id);
	    	$sql = "SELECT  color FROM  pouch_color WHERE is_delete = '0' AND pouch_color_id='".$arr[0]."'";
//		echo $sql;die;
		$data = $this->query($sql);
		return $data->row['color'];
	}
	public function getsizeForUS($volume){
	    
	  
	   	$sql = "SELECT  volume_us FROM  pouch_volume WHERE is_delete = '0'AND status='1' AND volume='".$volume."'";
//		echo $sql;die;
		$data = $this->query($sql);
		return $data->row['volume_us']; 
	}
	public function viewbusinessReport($post,$n=0){
	    	$to_date = $post['t_date'];
            $f_date = $post['f_date'];
            $con = "AND inv.invoice_date >= '" . $f_date . "' AND  inv.invoice_date <='" . $to_date . "' ";
          $sql = "SELECT SUM(inv.invoice_total_amount) as total_amt, SUM(inv.tran_charges) as freight,inv.transportation FROM " . DB_PREFIX . "invoice_test as inv WHERE inv.is_delete = 0   AND generate_status='1' AND done_status='1' ".$con." GROUP BY inv.transportation" ;
       //echo $sql;
          $data = $this->query($sql);
          $html='';
          
          if($data->num_rows){
          
        	foreach($data->rows as $data1){
        	  
        	   
        	        $air[decode($data1['transportation'])]=array('freight'=>$data1['freight'],
        	                'totam_amt'=>$data1['total_amt']);
        	   
        	    
        	}
        
        			 	$html .= '<div class="table-responsive">';
        					 $html .= '<table class="tool-row table-striped  b-t text-small" id="myTable">';
        					 $html .= ' <thead>';
        			             
        			                  if($n!='1')
        			                      $html .= ' <tr><td colspan="4"><span class="text-muted m-l-small pull-right"><a class="label bg-success" href="javascript:void(0);" id="excel_link" onclick="get_report()"><i class="fa fa-print"></i> Excel</a></span></td></tr>';
        			             $html .='<tr>';
            			             foreach($air as $key=>$val)
            			             {
            			                 $html .= '<th colspan="2">'.$key.'</th>';	
            			             }
        					    $html .= '</tr><tr>';
        					        for($i=1;$i<=2;$i++)
        					        {
        					            $html .='<th>Freight Amount</th>
        					                     <th>Business Value</th>';
        					        }
        					  $html .= '</tr>
        					        </thead>
        					  <tbody>
        					    <tr>';
        					            foreach($air as $key=>$val)
            			                {
        			                        $html .='<td>'.$air[$key]['freight'].'</td>
        					                            <td>'.$air[$key]['totam_amt'].'</td>';
            			                }
        					  
        					 
        					$html .= ' </tr></tbody>';
        			    $html .= ' </table>';
        			$html .= ' </div>';
	 	

         }
    	else
    	{
        	$html.= "<center><b> There are no record available</b></center>";		
    	}
	  
          
	    return $html;
	}

    public function reviceInvoice($invoice_id,$revice_invoice_status){
        
    if($revice_invoice_status!=1){
     
               $invoice_product_details=$this->getProductdeatils($invoice_id); 
                  $stock_arr=$cust_arr=array();
                   foreach($invoice_product_details as $product){
                      if($product['order_id']!='0'){
                        if(strrchr($product['buyers_o_no'],'CUST'))
                            $cust_arr[]=$product['order_id'];
                        else
                            $stock_arr[]=$product['order_id'];
                      }
                   }
                	$cust_arr1 = array_unique($cust_arr);
            	    $cust_no_array= implode(',',$cust_arr1);
            	    $stock_arr1 = array_unique($stock_arr);
            	    $stock_no_array= implode(',',$stock_arr1);
                    $dispatch_by='{"user_id":"144","user_type_id":"2","action":"3","currdate":"'.date('d-m-Y').'"}';
                  
                  if($stock_no_array!=''){
                     $sql_stock=" UPDATE `stock_order_dispatch_history_test` SET `dispach_by`='".$dispatch_by."',`dis_date`='".date("Y-m-d")."' WHERE `template_order_id` IN (".$stock_no_array.")";
                     echo  $sql_stock;
                    $data_stock = $this->query($sql_stock);
                 
                   $sql_stock_tem=" UPDATE `template_order_test` SET `done_status` = '0' WHERE `template_order_id` IN (".$stock_no_array.")";
                     echo  $sql_stock;
                   $data_stock_tem = $this->query($sql_stock_tem);
                  } 
                  if($cust_no_array!=''){
                      $sql_cust="UPDATE multi_custom_order SET done_status='0',dispach_by='".$dispatch_by."' WHERE custom_order_id  IN(".$cust_no_array.")";
                     echo  $sql_cust;
                	$data_cust = $this->query($sql_cust);
                  }
               
    }else{
        $sql = "UPDATE `" . DB_PREFIX . "invoice_test` SET done_status='0',generate_status='0',courier_status=0,invoice_date='".date("Y-m-d")."',date_added = NOW(), date_added = NOW(), date_modify = NOW() WHERE invoice_id = '".$invoice_id."'";	
		$data = $this->query($sql);
    }
       //die;
    }
	public function viewFullSalePurReport($post,$n)
	{
	    $product_code='';
	    if($post['product_code']!='')
	           $product_code="AND ip.product_code_id='".$post['product_code']."'"; //$product_code=$post['product_code'];
	        
	    if($_SESSION['LOGIN_USER_TYPE'] == 1 && $_SESSION['ADMIN_LOGIN_SWISS'] == 1){
    		$sql_pur = "SELECT pc.product_code_id,pc.product_code,ic.qty,ic.rate,i.customer_name,i.invoice_no,pc.description,i.invoice_date,ic.color_text,ip.digital_print_color FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip,product_code as pc,invoice_color_test as ic WHERE i.invoice_id =ip.invoice_id ".$product_code." AND pc.product_code_id=ip.product_code_id AND i.is_delete=0 AND ip.invoice_product_id = ic.invoice_product_id AND i.invoice_id=ic.invoice_id  AND i.invoice_date >= '".$post['f_date']."' AND i.invoice_date <='". $post['t_date'] ."' AND i.import_status=2";
    	    $sql_sale = "SELECT pc.product_code_id,pc.product_code,ip.qty,ip.rate,i.customer_name,i.invoice_no,pc.description,i.invoice_date,ip.stock_print as digital_print_color FROM  " . DB_PREFIX . "sales_invoice as i,sales_invoice_product as ip,product_code as pc WHERE i.invoice_id =ip.invoice_id  ".$product_code." AND pc.product_code_id=ip.product_code_id AND i.is_delete=0 AND i.invoice_date >= '".$post['f_date']."' AND i.invoice_date <='". $post['t_date'] ."'";
		} else {
		
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
				$str_sale = ' OR ( i.user_id IN ('.$userEmployee.') AND i.user_type_id = 2 )';
				$str_pur = ' OR ( i.purchase_user_id IN ('.$userEmployee.') AND i.purchase_user_type_id = 2 )';
			}
		
		    $sql_pur = "SELECT pc.product_code_id,pc.product_code,ic.qty,ic.rate,i.customer_name,i.invoice_no,pc.description,i.invoice_date,ic.color_text,ip.digital_print_color FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip,product_code as pc,invoice_color_test as ic WHERE i.invoice_id =ip.invoice_id  ".$product_code." AND pc.product_code_id=ip.product_code_id AND i.is_delete=0 AND ip.invoice_product_id = ic.invoice_product_id AND i.invoice_id=ic.invoice_id  AND i.invoice_date >= '".$post['f_date']."' AND i.invoice_date <='". $post['t_date'] ."' AND i.import_status=2 AND (i.purchase_user_id = '".(int)$set_user_id."' AND i.purchase_user_type_id = '".(int)$set_user_type_id."' $str_pur )";
    	    $sql_sale = "SELECT pc.product_code_id,pc.product_code,ip.qty,ip.rate,i.customer_name,i.invoice_no,pc.description,i.invoice_date,ip.stock_print as digital_print_color FROM  " . DB_PREFIX . "sales_invoice as i,sales_invoice_product as ip,product_code as pc WHERE i.invoice_id =ip.invoice_id  ".$product_code." AND pc.product_code_id=ip.product_code_id AND i.is_delete=0 AND i.invoice_date >= '".$post['f_date']."' AND i.invoice_date <='". $post['t_date'] ."' AND  ( i.user_id = '".(int)$set_user_id."' AND i.user_type_id = '".(int)$set_user_type_id."' $str_sale )";
		}
	   $data_pur = $this->query($sql_pur);
       $data_sale = $this->query($sql_sale);
	   	//printr($sql_pur);
	   	//printr($sql_sale);
		$final_array = array();
		if($data_pur->num_rows){
			$final_array = $data_pur->rows;
		}
	
		if($data_sale->num_rows){
			if($final_array)
			    $final_array = array_merge($final_array,$data_sale->rows);//array_push($final_array, $data_sale->rows);
			else
			    $final_array = $data_sale->rows;
		}
        
        //printr($final_array);die;
        //echo '1';
        function cmp($a, $b) {
            return $a["product_code_id"] - $b["product_code_id"];
        }
        usort($final_array,"cmp");
       // echo '2';
        
        //printr($final_array);//die;
		$html='';
		if($final_array)
		{
		    $html .= '<div class="table-responsive">';
    			 $html .= '<table class="tool-row b-t text-small" id="myTable" border=1>';
    			 $html .= ' <thead>';
    	                  if($n!='1')
    	                      $html .= ' <tr><td colspan="7"><span class="text-muted m-l-small pull-right"><a class="label bg-success" href="javascript:void(0);" id="excel_link" onclick="get_report_sale_pur()"><i class="fa fa-print"></i> Excel</a></span></td></tr>';
    	               
    	                $html.='<tr>
    	                            <th>Sr. No.</th>
    	                            <th>Invoice Date</th>
    	                            <th>Invoice Satus</th>
    	                            <th>Invoice No.</th>
    	                            <th>Customer Name</th>
    	                            <th>Product Code / Description</th>
    	                            <th>Total Amount</th>
    	                            <th>Qty</th>
    	                        </tr>
    	                        <tr><td colspan="8"></td></tr>
    	                    </thead>
    	                    <tbody>';
    	                    $amount = $qty=$i=0;$j=1;
                		    foreach($final_array as $array)
                		    { //printr($array);
                		        $style ="style='background-color: aliceblue;'";
                                    if($i%2==0)
                                        $style ="style='background-color: antiquewhite;'";
                		        $html .='<tr '.$style.'>
                		                        <td>'.$j.'</td>
                    		                    <td>'.dateFormat(3,$array['invoice_date']).'</td>';
                    		                    if( strpos( $array['invoice_no'], 'EXP' ) !== false)
                    		                        $status='Payable Invoice';
                    		                    else
                    		                        $status='Receivable Invoice'; 
                    		              $print='';
                    		              if($array['digital_print_color']!='' && $array['digital_print_color']=='Digital Print')
                    		                $print='<span style="color:red;">( Digital Print Order )</span>';
                    		           $html .='<td>'.$status.'</td>
                    		                    <td>'.$array['invoice_no'].'</td>
                    		                    <td>'.$array['customer_name'].'</td>
                    		                    <td>'.$array['product_code'].'<br>'.$array['description'].'<br><b>'.$array['color_text'].' '.$print.'</b></td>
                    		                    <td>'.$array['qty']*$array['rate'].'</td>
                    		                    <td>'.$array['qty'].'</td>
                		                 </tr>';
                		        $amount += $array['qty']*$array['rate'];
                		        $qty += $array['qty'];
                		        $i++;$j++;
                		    }
                		    $html.='<tr>
                		                <td>Total <b>'.$array['product_code'].'</b> ( '.$array['description'].' )</td>
                		                <td></td>
                		                <td></td>
                		                <td></td>
                		                <td></td>
                		                <td></td>
                		                <td>'.$amount.'</td>
                		                <td>'.$qty.'</td>
                		            </tr>';
                    $html .= '</tbody>
                          </table>';
            $html .= ' </div>';
		}
		else
		{
		    $html .= '<div class="table-responsive">No Records Found!!!!!</div>';
		}
	    
	    return $html;
	}
	public function viewFullAirSeaShippmentReport($post,$n=0)
	{
	    $html='';
	    if($_SESSION['LOGIN_USER_TYPE'] == 1 && $_SESSION['ADMIN_LOGIN_SWISS'] == 1){
    	   $sql="SELECT * FROM `invoice_test` WHERE is_delete=0 AND done_status=1  AND invoice_date >= '".$post['f_date']."' AND invoice_date<='".$post['t_date']."'";
    		} else {
		
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
		
		   	   $sql="SELECT * FROM `invoice_test` WHERE is_delete=0 AND done_status=1  AND admin_user_id='".$set_user_id."' AND invoice_date >= '".$post['f_date']."' AND invoice_date<='".$post['t_date']."'";
		  	  
		
			}
		  $html .= '<div class="table-responsive">';
			 $html .= '<table class="tool-row b-t text-small" id="myTable" border=1>';
			 $html .= ' <thead>';
	                  if($n!='1')
	                      $html .= ' <tr><td colspan="7"><span class="text-muted m-l-small pull-right"><a class="label bg-success" href="javascript:void(0);" id="excel_link" onclick="get_report_sale_pur()"><i class="fa fa-print"></i> Excel</a></span></td></tr>';
	               
	                $html.='<tr>
	                            <th>Sr. No.</th>
	                            <th>Invoice No</th>
	                            <th>Transportation</th>
	                            <th>Product Name</th>
	                            <th>Total box .</th>
	                        </tr>
	                    </thead>
	                    <tbody>';
	  $invoice_data = $this->query($sql);
	  	if($invoice_data->num_rows){
	  	        $i=1;
	  	    foreach($invoice_data->rows as $data){
	  	         $sql_total_box="SELECT COUNT(*) as total  FROM `in_gen_invoice_test` WHERE is_delete=0 AND `invoice_id`IN(". $data['invoice_id'] .")";
	  	          $total_box_inv = $this->query($sql_total_box);
	            $sql_pro="SELECT ip.product_id, GROUP_CONCAT(invoice_product_id)as ids,p.product_name FROM `invoice_product_test` as ip,product as p WHERE  p.product_id=ip.product_id AND invoice_id='".$data['invoice_id']."' GROUP by product_id";
	  	        	  $invoice_pro_data = $this->query($sql_pro);
	  	        	   	if($invoice_pro_data->num_rows){
	  	                
	  	                     foreach($invoice_pro_data->rows as $pro_data){
	  	                            $sql_pro_box="SELECT COUNT(*) as total  FROM `in_gen_invoice_test` WHERE is_delete=0 AND `invoice_product_id`IN(". $pro_data['ids'] .")";
	  	                          	  $total_box = $this->query($sql_pro_box);
	  	                         $html.='<tr>
            	                            <td>'.$i.'</td> 
            	                            <td> '.$data['invoice_no'].' => <b>Total Box Per Invoice :</b>'.$total_box_inv->row['total'].'</td>
            	                            <td>By '. ucwords(decode($data['transportation'])).'</td>
            	                            <td>'.$pro_data['product_name'].'</td>
            	                            <td>'.$total_box->row['total'].'</td>
	                              </tr>';
	                            
	  	                    $i++;
	  	                    
	  	                     }
	  	        
	  	        	   	}
	  	    
	  	    }
	  }
	    $html .= '</tbody>
                  </table>';
      $html .= ' </div>';
	//   echo $sql;die;
	 //  printr($html);
	//   printr($invoice_data);
	   
	 ///  die;
	    return $html;
	}
	
	
	
	
	
	
	/*function cmp($a, $b)
    {
        if ($a["product_code_id"] == $b["product_code_id"]) {
            return 0; 
        }
        return ($a["product_code_id"] < $b["product_code_id"]) ? -1 : 1;
        echo 'sdf';
        return $a["product_code_id"] - $b["product_code_id"];
    }*/  

    
  function viewDetailsDatatable($invoice_no,$status=0,$price=0,$p_break=0,$option=array(),$show_status='0')
	{
	$invoice_qty=$this->getInvoiceTotalData($invoice_no);
		$alldetails=$this->getProductdeatils($invoice_no);
		$cylinder_array=array();
		 $cylinder_details = "SELECT ic.*  FROM  " . DB_PREFIX . "invoice_test as i,invoice_color_test as ic WHERE  i.invoice_id =ic.invoice_id AND i.invoice_id = '" .(int)$invoice_no. "' AND i.is_delete=0 AND cylinder_rate!=0.00 AND no_of_cylinder!=0 AND ic.color='-1'";
		$cylinder_data = $this->query($cylinder_details);

		//printr($cylinder_data);
			if($cylinder_data->num_rows){
			
			    $cylinder_array = $cylinder_data->rows;
		}  
        
		return array('invoice_qty'=>$invoice_qty,'alldetails'=>$alldetails,'price'=>$price,'status'=>$status,'cylinder_data'=>$cylinder_array);
	}
	
	public function colordetailsTest($invoice_id,$parent_id=0,$str='',$pallet='',$limit=' ',$option=array())
	{
		$b_no="";
	
		  $sql="select *,ig.qty as genqty from in_gen_invoice_test as ig WHERE parent_id='".$parent_id."' AND ig.is_delete=0 ".$str." ".$pallet." AND  ig.invoice_id='".$invoice_id."'"; 

	    
	    if (isset($option['sort'])) {
			$sql .= " ORDER BY " .$option['sort'];	
		} else {
			$sql .= " ORDER BY ig.box_no";	
		}

		if (isset($option['order']) && ($option['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($option['start']) || isset($option['limit'])) {
			if ($option['start'] < 0) {
				$option['start'] = 0;
			}	
			if ($option['limit'] < 1) { 
				$option['limit'] = 600;
			}	
			$sql .= " LIMIT " . (int)$option['start'] . "," . (int)$option['limit'];
		}
		else
		    $sql.=$limit;  
		  
		  
		 
	 //echo $sql.'<br><br>';//die;
		  $data = $this->query($sql);	

		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	} 
	public function Product_detailTest($invoice_id,$invoice_product_id,$invoice_color_id)
	{
		$b_no=""; 
	
		  $sql="select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.filling_details,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.no_of_cylinder,ii.cylinder_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color_test as ii,pouch_color as c,template_measurement as tm,invoice_product_test as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id  AND ip.invoice_product_id='".$invoice_product_id."' AND ii.invoice_color_id='".$invoice_color_id."'"; 

	   
		  $data = $this->query($sql);	

		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		} 
	}
	public function viewInout_update($invoice_no,$status){
		//ini_set('MAX_EXECUTION_TIME', 0);
		$parent_id=0;
		if($n==1) 
		{
			$details=$this->InoutLable($invoice_no,$parent_id,$from,$to);
			$details1=$this->colordetailsTest($invoice_no,$parent_id);
			$tot_box=count($details1);
		}
		else
		{
			$details=$this->colordetailsTest($invoice_no,$parent_id);
			$tot_box=count($details);
		}
		$invoice=$this->getInvoiceNetData($invoice_no);
		$setHtml='';$description='';$valve='';$zipper_name='';
		if($status==2)	{
		    $i=4;$r=4;
		
		}else{
		    $i=4;$r=4;
		}
	$gross_weight=0;
		//printr($details);
		if($details!='')
		{
				foreach($details as $val1)
				{	
				    $val=$this->Product_detailTest($invoice_no,$val1['invoice_product_id'],$val1['invoice_color_id']);
					
					$val['box_no']=$val1['box_no'];
					$val['genqty']=$val1['genqty'];
					$c_name='';$size='';$qty='';
					$zipper=$this->getZipper(decode($val['zipper']));
					$zipper_name=$zipper['zipper_name'];
					$valve=$val['valve'];
					
					$p_name=$this->getActiveProductName($val['product_id']);
					
					$childBox=$this->colordetailsTest($invoice_no,$val1['in_gen_invoice_id']);
					$product_decreption = $this->getProductCode($val['invoice_product_id']);
					
				//printr($product_decreption);
					$net_w = $val['net_weight'];
					if(isset($childBox) && !empty($childBox))
					{
						foreach($childBox as $key=>$ch)
						{
						    
							$net_w = $net_w+$ch['net_weight'];
						}
					}
				//	printr($product_decreption);
					$gross_weight=$net_w+$val1['box_weight'];
					
							
				// change by sonu   add change for color text 30-10-2017
											
					if($val['color_text']!=''){
				    	$c_name=$val['color_text'].'  '.$val['color'];
					}else{
					    $c_name=$val['color'];
					}
					if($val['filling_details']!=''){
				    	$filling_details=$val['filling_details'];
					}else{
					    $filling_details='';
					}
				//	echo $c_name;
					//end
					if( $val['product_id']!='47' ||$val['product_id']!='48'||$val['product_id']!='72')
					    $val['size']= filter_var($val['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
					if($val['pouch_color_id']=='-1')
					{
						$size_cd =$size=$val['dimension'];
						
					}
					else	
					{
					     if($invoice['country_destination'] != '253'){
					         	$size=$val['size'].' '.$val['measurement'];
					            $size_cd = $val['size'].'='.$val['measurement']; 
						 }else{
						       	$size_us=$val['size'].' '.$val['measurement'];
						       	 $size_cd = $val['size'].'='.$val['measurement']; 
						         $s_us=$this->getsizeForUS($size_us);
						         if($s_us!=''){
						              $size=$s_us;
						         }else{
						              $size=$size_us;
						         }
						         
						 }
					
					}	//printr($size);
					$size_new='';
					if($size=='250. gm' || $size=='500. gm')
					    $size_new=' [NEW SIZE] ';
						
					
					$qty=$val['genqty'];
					
					$description= $filling_details.''.$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
					
					//if($product_decreption==''  )
					//{
						
						//$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
					//}else
					//{
							if( $val['product_id']=='13' ||$val['product_id']=='16'|| $val['product_id']=='31' || $val['product_id']=='30'|| $val['product_id']=='37'|| $val['product_id']=='38')
							{
							    $description=$product_decreption['description'].' '. $filling_details;
							
							
							}
							else
							{
							    $description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') '.$size_new ;
							}
						
				//	}
					
					if($i%$r==0)
					{	$a=$i-$r;
						if($status==2)		
							$c=$r;
						else
							$c=$r;
						$style='';
					/*	if($i%5=='0')
						     $style="page-break-before:always;";*/
						    
						//	$setHtml .='<style>.innerbox{  width:250px;     max-width:250px;  display: inline-block;  } </style>';
							$setHtml .='<div id="'.$i.'='.($i%12).'" style="'.$style.'" >
											<table class="table"  border="0" width="100%">
											
													<tr >';															            
					 }		 
				 
													//width:50%
												$setHtml .='<td width="25%"style="border:5px; font-size:13px;" >
																<table  style="" id="innerbox" class="innerbox" style="width:450px;max-width:550px; height:300px;display: inline-block;font-size:13px;" >
																	<tr>
																		<td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px;text-align:left;"><b>'.$val['box_no'].'</b></td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$val['genqty'].' PCS</td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
																		
																		
																	</tr>
																	<tr>
																	<td  style="padding:0px;border:0px" colspan="2" class="test"><b>'.$description.'</b></td>
																	</tr>
																	<tr>
																		<td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$size.'</td>
																	</tr>
																<tr>';
												                    	if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
            																{
            																	if($invoice['country_destination'] == '170')
            																	{
            																		$label ='SPECIAL CODE';
            																	
            																	} 
            																	else
            																	{
            																		$label = 'ITEM NO.';
            																	}
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['item_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
            																}
            																//&& $invoice['country_destination']!='42' 04-01-2017
            																
            																
                                                                						    
            																else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155'&& $invoice['country_destination']!='42' ){
            																    
            																	 if($val['ref_no']!=0){
            																	 
                                                                						$val['ref_no']=$val['ref_no'];
                                                                				 }else{
                                                                				     $val['ref_no']= $val['buyers_o_no'];
                                                                				 }
            																    
            																    $setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['ref_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
            																}
            																else 
            																{
            																	$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
            																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['buyers_o_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
            																}	
            												$setHtml.='</tr>';
																	
												if($status==2)		
												{
												$setHtml .='<tr>
																<td  style="padding:0px;border:0px">GROSS WT&nbsp;:&nbsp;</td>
																<td  style="padding:0px;border:0px">'.number_format($gross_weight,3).' Kg</td>
															</tr>
															<tr>
																<td  style="padding:0px;border:0px">Net WT&nbsp;:&nbsp;</td>
																<td  style="padding:0px;border:0px">'.$net_w.' Kg</td>
															</tr>
														';
												
														if(ucwords(decode($invoice['transportation']))=='Air')
														{
														$setHtml .='<tr>
																		<td  style="padding:0px;border:0px"><b>Marks & NO .&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$val['box_no'].'/'.$tot_box.'Boxes </b></td>
																	</tr>';	
																	}			
												}
														$setHtml .='<tr>
																		<td  style="padding:0px;border:0px" colspan="2"><div class="barcode_lable" style=" padding: 0px;"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div></td>
																	</tr>
																	<tr class="test">
																	
																		<td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;font-size:13px;"><img style="width:159px;" class="barcode" alt="'.trim($val1['box_unique_number']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($val1['box_unique_number']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span>';
																		
																	
                                                					if($invoice_no=='5'){
                                                                    $setHtml .='<br><br><span><img src="https://chart.googleapis.com/chart?chs=156x81&cht=qr&chl='.$val1['box_unique_number'].'*'.$p_name['abbrevation'].'*'.$val1['genqty'].'*'.$size_cd.'*'.$val1['box_no'].'*'.$invoice['invoice_no'].'*'.$product_decreption['product_code_id'].'"></span>';
                                                                   }
															$setHtml .='<div></td>
																	</tr>
											
										</table>
									</td>';
								
								
					$description='';
					$i++; 
				 
					if($i%$r==0)
					{
					  //  echo $i.'if';
						$setHtml .='</tr></table></div>';	
						
					}	
					
					
					if(isset($childBox) && !empty($childBox))
										{
											foreach($childBox as $key=>$ch1)
											{
										        
										        $ch=$this->Product_detailTest($invoice_no,$ch1['invoice_product_id'],$ch1['invoice_color_id']);
											  // product_id
											   	$product_decreption = $this->getProductCode($ch['invoice_product_id']);
											  
											    
											        $child_zipper=$this->getZipper(decode($ch['zipper']));
                                			if($i%$r==0)
                                					{	$a=$i-$r;
                                						if($status==2)		
                                							$c=$r;
                                						else
                                							$c=$r;
                                						$style='';
                                						//if($i%12==4)
						                                    //$style='page-break-before:always;';
                                							$setHtml .='<div id="'.$i.'='.($i%12).'" style="'.$style.'" >
                                											<table class="table"  border="0" style="width:900px"  >
                                											
                                													<tr>';															            
                                					 }		
																		
												//} width:50%	         
												
												// change by sonu   add change for color text 30-10-2017
												
                                                        					if($ch['color_text']!=''){ 
                                                        				         $c_name_ch=$ch['color_text'] .'  '.$ch['color'];
                                                        					}else{
                                                        					    $c_name_ch=$ch['color'];
                                                        					}
                                                        					if($ch['filling_details']!=''){ 
                                                        				         $filling_details_ch=$ch['filling_details'];
                                                        					}else{
                                                        					     $filling_details_ch='';
                                                        					}
                                                        					
                                                        					if( $ch['product_id']!='47' ||$ch['product_id']!='48'||$ch['product_id']!='72')
					                                                                $ch['size']= filter_var($ch['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                                                        					//end							
												                            if($ch['pouch_color_id']=='-1')
                                                        					{
                                                        						$size_ch=$ch['dimension'];
                                                        						$size_code = $ch['dimension'];
                                                        						
                                                        					}
                                                        					else	
                                                        					{
                                                        					     if($invoice['country_destination']!=253){
            																	    	$size_ch=$ch['size'].' '.$ch['measurement'];
            																	   }else{
            																	       	$size_us_c=$ch['size'].' '.$ch['measurement'];
            																	         $s_us_c=$this->getsizeForUS($size_us_c);
            																	       if($s_us!=''){
                                                        						              $size_ch=$s_us_c;
                                                        						         }else{
                                                        						              $size_ch=$size_us_c;
                                                        						         }
            																	   }
                                                        					    
                                                        					
                                                        						$size_code = $ch['size'].'='.$ch['measurement'];
                                                        					}
                                                        					
                                                        				    $size_new_ch='';
                                                            					if($size_ch=='250. gm' || $size_ch=='500. gm')
                                                            					    $size_new_ch=' [NEW SIZE] ';
                                                        					
                                                        					
                                                        						if( $ch['product_id']=='13' ||$ch['product_id']=='16'|| $ch['product_id']=='31' || $ch['product_id']=='30'|| $ch['product_id']=='37'|| $ch['product_id']=='38')
                                                        							{
                                                        							$description_ch=$product_decreption['description'].' '.$filling_details_ch;
                                                        							
                                                        							
                                                        							}
                                                        							else{
                                                        							    $description_ch=$c_name_ch. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$ch['product_name'] ),0,3)).' '.$child_zipper['zipper_name'].' '.$ch['valve'].')'.$size_new_ch ;
                                                        							}
                            													$setHtml .='<td style="border:none; border-top:none;font-size:13px;" width="25%">
									                                            	<table  style="" id="innerbox1" class="innerbox1" style="width:350px;max-width:450px; height:300px;display: inline-block;font-size:13px;">
																					<tr>  
																						<td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
																						<td  style="padding:0px;border:0px"><b>'.$val['box_no'].'</b></td>
																					</tr>
																					<tr> 
																						<td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
																						<td  style="padding:0px;border:0px">'.$ch1['genqty'].' PCS</td>
																					</tr>
																					<tr>
																						<td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
																						
																					</tr> 
																					<tr>
																				    	<td  style="padding:0px;border:0px" colspan="2" class="test" ><b>'.$description_ch.'</b></td>
																					</tr>
																					<tr>
																						<td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
																						<td  style="padding:0px;border:0px">';
																						
																						
																						//.$ch['size'].' '.$ch['measurement'].
																						
																						$setHtml .= $size_ch.'</td>
																					</tr>';
																$setHtml .='	<tr>';
																				if($invoice['country_destination'] == '170' || $invoice['country_destination'] == '253')
																				{
																					if($invoice['country_destination'] == '170')
																					{
																						$label ='SPECIAL CODE';
																					} 
																					else
																					{
																						$label = 'ITEM NO.';
																					}
																					$setHtml .='<td  style="padding:0px;border:0px">'.$label.'&nbsp;:&nbsp;</td>';
																					$setHtml .='<td  style="padding:0px;border:0px">'.$ch['item_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
																				}
																				//&& $invoice['country_destination']!='42'  04-01-2018
																				
																				
																				
																			
																				else if($invoice['country_destination']!='14' && $invoice['country_destination']!='214'  && $invoice['country_destination']!='155' ){
																				    
																				    	 if($ch['ref_no']!=0){
                                                                						       $ch['ref_no']=$ch['ref_no'];
                                                                						    }else{
                                                                						       $ch['ref_no']= $ch['buyers_o_no'];
                                                                						    }
																                               $setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																                            	$setHtml .='<td  style="padding:0px;border:0px">'.$ch['ref_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
															                    	}
																				else
																				{
																					$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																					$setHtml .='<td  style="padding:0px;border:0px">'.$ch['buyers_o_no'].' (<b>'.ucwords(decode($invoice['transportation'])).'</b>)</td>';
																				}
																	
																
																$setHtml .='</tr>';							
														if($status==2)		
														{
															$setHtml .='<tr>
																			<td  style="padding:0px;border:0px">GROSS WT&nbsp;:&nbsp;</td>
																			<td  style="padding:0px;border:0px">'.number_format($gross_weight,3).' Kg</td>
																		</tr>
																		<tr>
																			<td  style="padding:0px;border:0px">Net WT&nbsp;:&nbsp;</td>
																			<td  style="padding:0px;border:0px">'.$net_w.' Kg</td>
																		</tr>';
														
																$setHtml .='<tr>
																		<td  style="padding:0px;border:0px"><b>Marks & NO.&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
																		<td  style="padding:0px;border:0px">'.$val['box_no'].'/'.$tot_box.'Boxes </b></td>
																	</tr>';				 
														}
														
														$setHtml .='<tr>
																		<td  style="padding:0px;border:0px" colspan="2"><div class="barcode_lable" style=" padding: 0px;"><b>INSPECTED BY</b>&nbsp;&nbsp;: </div></td>
																	</tr>
																	 <tr class="test">
																		<td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;font-size:13px;"><img style="width:159px;" class="barcode" alt="'.trim($val1['box_unique_number']).'" src="'.HTTP_SERVER.'admin/barcode/barcode.php?text='.trim($val1['box_unique_number']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span>';
																		
																		
                                                					if($invoice_no=='5'){
                                                                    $setHtml .='<br><br><span><img src="https://chart.googleapis.com/chart?chs=156x81&cht=qr&chl='.$val1['box_unique_number'].'*'.$p_name['abbrevation'].'*'.$val1['genqty'].'*'.$size_code.'*'.$val1['box_no'].'*'.$invoice['invoice_no'].'*'.$product_decreption['product_code_id'].'"></span>';
                                                                   }
															$setHtml .='<div></td>
																	</tr>
																
																</table></td> 
																';
										
											$i++; 
				 
                            					if($i%$r==0)
                            					{
                            					  //  echo $i.'if';
                            						$setHtml .='</tr></table></div>';	
                            						
                            					}																
										}
										
										
										}
								
					
					 
				}
				//printr($setHtml);die;
		}
		$setHtml .='</tr></table></div>';
	//printr($setHtml);die;
	    
		return $setHtml;
	}
    public function getIngenBoxForRoll($invoice_id,$invoice_product_id,$charge=0)
    {
        $sql="SELECT COUNT(*) as total_box,SUM(ig.net_weight) as net, SUM(ig.net_weight+ig.box_weight) as gross FROM in_gen_invoice_test as ig,invoice_product_test as ip WHERE ig.invoice_id='".$invoice_id."' AND ig.is_delete=0 AND ig.invoice_product_id =ip.invoice_product_id AND ig.is_delete='0' AND ip.invoice_id='".$invoice_id."'  AND ig.parent_id='0' AND ig.invoice_product_id = '".$invoice_product_id."'";//
		$data = $this->query($sql);
		
		$sql1="SELECT COUNT(*) as total_box,SUM(ig.net_weight) as net, SUM(ig.net_weight+ig.box_weight) as gross FROM in_gen_invoice_test as ig,invoice_product_test as ip WHERE ig.invoice_id='".$invoice_id."' AND ig.is_delete=0 AND ig.invoice_product_id =ip.invoice_product_id AND ig.is_delete='0' AND ip.invoice_id='".$invoice_id."' AND ig.invoice_product_id = '".$invoice_product_id."'";//
		$data1 = $this->query($sql1);
		
		if($data->num_rows)
		{
		    $count=1;
		    
		    	$count=count(explode(',',$data_pro->row['group_id_color']));
		    	$data_pro->row['total_rate']=($data_pro->row['total_rate']/$count);
		    
			$d = array( 'total_box'=>$data->row['total_box'],
						 
						 'g_wt' => $data1->row['gross'],
						 'n_wt' => $data1->row['net'],
						 
						 );
			return $d;
		}
		else
			return false;
    }
    public function viewbarcode($from=0,$to=0)
	{$STR='AND barcode_id BETWEEN 1660 AND 1669';// 
		if($from!=0)
		    $STR=" AND barcode_id BETWEEN ".$from." AND ".$to."";
		$sql="SELECT * FROM `barcode` WHERE `user_id`='19' AND `user_type_id`='4' $STR";
		//printr($sql);die;
		$data = $this->query($sql);
		$setHtml='';$description='';$valve='';$zipper_name='';
		$i=2;$gross_weight=0;
		$bar=1;
		if($data->num_rows)
		{		
				foreach($data->rows as $val)
				{	
					if($i%2==0)
					{	
						$style='';
						if($i%3=='2' && $i!=2)
						    $style="page-break-before:always;";
						    
						$setHtml .='<div id="'.$i.'='.($i%3).'" style="'.$style.'">
										<table class="table" border="0" >
											<tr>';															            
					 }		
				                $setHtml .='<td style="border:none; border-top:none; ">
												<table style="" width="100%" border="0" id="sub_table" class="sub_table">
													<tr>
														<td  style="width:40%;padding:0px;border:none;">BOX NO. : </td>
														<td  style="width:60%;padding:0px;border:none;text-align:left;"><b>'.$val['box_no'].' [ '.$val['rack_label'].']</b></td>
													</tr>
													<tr>
														<td  style="padding:0px;border:none;">PRODUCT CODE : </td>
														<td  style="padding:0px;border:none;text-align:left;"><b>'.$val['product_code'].'</b></td>
													</tr>
													<tr>
														<td  style="padding:0px;border:none;">PRODUCT NAME : </td>
														<td  style="padding:0px;border:none;"><b>'.$val['product_name'].'</b></td>
													</tr>
													<tr>
														<td  style="padding:0px;border:none;" valign="top">DESC. :  </td>
														<td  style="padding:0px;border:none;" colspan="2" class="test"><b>'.$val['decription'].'</b></td>																			
													</tr>
													<tr>
														<td  style="padding:0px;border:none;">QTY PCS. : </td>
														<td  style="padding:0px;border:none;">'.$val['qty'].' PCS</td>
													</tr>';
        								$setHtml .='<tr>
        												<td  style="padding:0px;border:none;"><b>INSPECTED BY</b> : </td>';
        											
        									$barcode = 'TBX'.sprintf("%014s",$bar);
        									$setHtml .='<td  style="padding:0px;border:none;"><span style="line-height:50px;font-size:10px;"><img style="width:200px;" class="barcode" alt="'.trim($val['barcode']).'" src="https://swissonline.in/admin/barcode/barcode.php?text='.trim($val['barcode']).'&codetype=Code128&orientation=horizontal&size=40&print=true"/></span></td>
        											</tr>
        											<tr>
														<td  style="padding:0px;border:none;"></td>
														<td  style="padding:0px;border:none;"></td>
													</tr>
													
										</table>
									</td>';
					$i++;
				    if($i%2==0)
					{
					  $setHtml .='</tr></table></div>';	
					}	
				    $bar++;
				    //$this->query("UPDATE barcode SET  barcode='".$barcode."' WHERE 	barcode_id='".$val['barcode_id']."'");
				}
		}
	    $setHtml .='</tr></table></div>';
        return $setHtml;
	}
	public function convert($invoice_id)
	{
		$sql = "UPDATE `" . DB_PREFIX . "invoice_test` SET import_status='0'  WHERE invoice_id = '".decode($invoice_id)."'";	
		$data = $this->query($sql);	

	}
	public function convertInPurchase_new($invoice_id)
	{
		$sql = "UPDATE `" . DB_PREFIX . "invoice_test` SET import_status='2',purchase_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',purchase_user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',convert_to_purchase_date=NOW() WHERE invoice_id = '".$invoice_id."'";	
		$data = $this->query($sql);	

	}
	public function getInvoiceProduct_insert($invoice_id)
	{
		$sql_pro ="SELECT * FROM invoice_product_test WHERE invoice_id ='".$invoice_id."'";
		$data = $this->query($sql_pro);
		$data_pro=array();
		if($data->num_rows)
		{
			foreach($data->rows as $row)
			{
			    $mystring = $row['buyers_o_no'];
			    $findme = 'CUST';
				$pos = strpos($mystring, $findme);
				$clr='';
				
			     if($row['product_code_id']==0)
			     {
			         	$sql_clr ="SELECT * FROM invoice_color_test WHERE invoice_id ='".$invoice_id."' AND invoice_product_id='".$row['invoice_product_id']."'";
			         	$data_clr = $this->query($sql_clr);
						/*if($data_clr->row['dimension']!='')
			         	{
    			         	$dia = explode('x',strtolower($data_clr->row['dimension']));
    			         	$volume ='AND pc.width='.$dia[0].' AND pc.height='.$dia[1].' AND pc.gusset='.floor($dia[2]).' ';
			         	}
			         	else
			         	{
			         	   $volume ='AND pc.volume='.$data_clr->row['size'].' AND pc.measurement='.$data_clr->row['measurement'].' ';  
			         	}*/
			         	$sql = "SELECT ip.*,ic.qty,ic.rate,ic.rack_status,ic.invoice_color_id,ip.product_id from invoice_color_test as ic,invoice_product_test as ip WHERE ip.invoice_product_id ='".$row['invoice_product_id']."'  AND ip.invoice_product_id = ic.invoice_product_id  AND ip.product_id!=6 $clr $volume";//AND pc.product=ip.product_id
                        if($row['product_id']=='6')
                            $sql = "SELECT ip.*,ic.qty,ic.rate,ic.rack_status,ic.invoice_color_id,ig.net_weight,ip.product_id,ig.in_gen_invoice_id from invoice_color_test as ic,invoice_product_test as ip,in_gen_invoice_test as ig WHERE ip.invoice_product_id ='".$row['invoice_product_id']."' AND ig.is_delete=0 AND ip.invoice_product_id = ic.invoice_product_id AND ip.product_id=6 AND ip.invoice_product_id = ig.invoice_product_id AND ic.invoice_color_id=ig.invoice_color_id $clr $volume";
			     }
			     else
				 { 
				    $sql = "SELECT ip.*,ic.qty,ic.rate,ic.rack_status,ic.color as cust_color,pc.product_code,ic.invoice_color_id,ip.product_id from invoice_color_test as ic,invoice_product_test as ip,product_code as pc WHERE ip.invoice_product_id ='".$row['invoice_product_id']."'  AND ip.invoice_product_id = ic.invoice_product_id AND pc.product_code_id=ip.product_code_id  AND ic.color = pc.color AND ip.product_id!=6 $clr"; 
				    if($row['product_id']=='6')
			           $sql = "SELECT ip.*,ic.qty,ic.rate,ic.rack_status,ic.color as cust_color,pc.product_code,ic.invoice_color_id,ig.net_weight,ip.product_id,ig.in_gen_invoice_id from invoice_color_test as ic,invoice_product_test as ip,product_code as pc,in_gen_invoice_test as ig WHERE ip.invoice_product_id ='".$row['invoice_product_id']."' AND ig.is_delete=0 AND ip.invoice_product_id = ic.invoice_product_id AND pc.product_code_id=ip.product_code_id  AND ic.color = pc.color AND ip.product_id=6 AND ip.invoice_product_id = ig.invoice_product_id AND ic.invoice_color_id=ig.invoice_color_id  $clr";     
				 }
			   // printr($sql);
				$data_p = $this->query($sql);
                if($data_p->num_rows)
                { 
				    $data_pro[] = $data_p->row;
                }
			}
		}
		//printr($data_pro);
    	   $final_data_array=$data_pro;
    	if(!empty($final_data_array)){
			return $final_data_array;
		}else{
			return false;
		}		
			
	}
	public function getBoxForProduct($invoice_id,$invoice_product_id,$invoice_color_id)
	{
		$sql="SELECT * FROM in_gen_invoice_test WHERE invoice_id='".$invoice_id."' AND invoice_product_id ='".$invoice_product_id."' AND invoice_color_id='".$invoice_color_id."' AND is_delete='0' AND rack_done_status=0";
	//	echo $sql;
		$data = $this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	public function getparentbox_number($parent_id)
	{
		$sql="SELECT * FROM in_gen_invoice_test WHERE in_gen_invoice_id='".$parent_id."' AND is_delete=0 AND rack_done_status=0";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function  addRackDetail($post)
	{ 
		if($_SESSION['LOGIN_USER_TYPE'] == 2)
		{	
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		}
		foreach($post as $inv_pro_id=>$data)
		{ 	
			if($inv_pro_id!='invoice_no' && $inv_pro_id!='company_name')
			{ 	
				if(isset($data['box']))
				{ $product_id = $this->getproductcd($data['product_code']);
					foreach($data as $key=>$d)
					{   
					   $pallet = explode("=",$data['pallet']);
						if($key=='box')
						{	$qty=0;
							foreach($d as $dt)
							{   $arr = explode("==",$dt);//printr($dt);
								$sql = "INSERT INTO stock_management SET order_no = '".$data['order_no']."', my_order_no = 'na',box_no='".$arr[0]."',proforma_no='na',invoice_no='".$post['invoice_no']."',description = '1',product = '".$product_id['product']."',qty = '".$arr[2]."',row='".$pallet[0]."',column_name='".$pallet[1]."',goods_id='".$pallet[2]."',company_name='".$post['company_name']."',product_code_id='".$data['product_code']."', status='0',is_delete='0',date_added=NOW(),date_modify=NOW(),user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";		
								$data_query = $this->query($sql);
								$qty+=$arr[2];
								
								$this->query("UPDATE in_gen_invoice_test_new SET rack_done_status='1' WHERE in_gen_invoice_id='".$arr[1]."'");
								$sql2="UPDATE in_gen_invoice_test SET rack_done_status='1' WHERE in_gen_invoice_id='".$arr[1]."'";
								$result1=$this->query($sql2);
								
							}
							$rem_qty=$data['rack_status']-$qty;
							$sql1="UPDATE invoice_color_test SET rack_status='".$rem_qty."' WHERE invoice_product_id='".$data['invoice_product_id']."'";
							$result=$this->query($sql1);
						}
						
					}
				}
			}
			
		}
		
	}
   public function SaveTrackingDetails($post){ 
       
        $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');  
     
      	$sql = "UPDATE `" . DB_PREFIX . "invoice_test` SET tracking_no ='".$post['tracking_no']."',sent_date ='".$post['sent_date']."',courier_name ='".$post['courier_name']."',trackinfo ='".$post['trackinfo']."',courier_info_added_by='".$by."' ,courier_status='1' WHERE invoice_id = '".$post['invoiceid']."'";	
		$data_sql = $this->query($sql);	  
		
		//printr($sql);die;
    }
    public function getMenuPermission_mail($menu_id)
	{
		$cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$cond." AND invoice_track_mail=0 ";
		$data = $this->query($sql);
		return $data->rows;
	}
   function send_mail_customer($data,$file){
	 
	    $this->query("UPDATE `" . DB_PREFIX . "invoice_test` SET send_email =1 WHERE invoice_id='".$data['invoice_id_send']."'");
		$invoice_data=$this->getInvoiceData($data['invoice_id_send']);
		$by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s'); 
		
		 $filename1 = basename($_FILES["file_excel"]["name"]);
         $ext1 = substr($filename1, strrpos($filename1, '.') + 1);

		$sql = "INSERT INTO `" . DB_PREFIX . "invoice_direct_dispatched_customer_details` SET invoice_no ='".$data['invoice_no_send']."',invoice_id='".$data['invoice_id_send']."',tracking_no='".$data['tracking_no_mail']."',	track_info='".$data['track_info_mail']."',send_date='".date('Y-m-d H:i:s')."',toemail='".$data['toemail']."',ccemail='".$data['ccemail']."',message='".$data['message']."',courier_name='".$data['courier_name_mail']."',sheet_name='".$_FILES["file_excel"]["name"]."',extension='".$ext1."',sendby='".$by."',is_delete=0,date_added='".date('Y-m-d H:i:s')."'";
		$data_sql = $this->query($sql);	  
		$html='';
		
	
            
	    $attachment[]=DIR_UPLOAD."admin/return_doc/php-pdf-merge-master/example/".$data['invoice_id_send'].':'.$data['invoice_no_send'].'/invoice-'.$data['invoice_no_send'].'.pdf';
	  //  $attachment[]=DIR_UPLOAD."admin/return_doc/php-pdf-merge-master/example/".$data['invoice_id_send'].':'.$data['invoice_no_send'].'/second.pdf';
	    $attachment[]=DIR_UPLOAD."admin/return_doc/php-pdf-merge-master/example/".$data['invoice_id_send'].':'.$data['invoice_no_send'].'/'.$_FILES["file_excel"]["name"];
	    
	  //  printr($attachment);die;
		$html.= $data['message'];
		


	/*	$to_email=explode(',',$data['toemail']);
		foreach($to_email as $email){
		    	$email_temp[]=array('html'=>$html,'email'=>$email);
		}*/
		/*$email_temp[]=array('html'=>$html,'email'=>$data['emailform']);*/
	
		
		/*$menu_admin_permission=$this->getMenuPermission_mail(329);

	    if(!empty($menu_admin_permission))
		foreach($menu_admin_permission as $permission){
		    $email_temp[]=array('html'=>$html,'email'=>$permission['email']);
		}*/
		
		 
		
		$email_id=$this->getUser($invoice_data['user_id'],$invoice_data['user_type_id']); 
		
		//$signature = '<b><span style="color:red"><br> Thanks & Regards, <br>'.$email_id['first_name'].' '.$email_id['last_name'].'<br>  Swiss Pac Pvt Ltd</span></b>';
		$signature = '<b><br> Thanks & Regards, <br>'.$email_id['first_name'].' '.$email_id['last_name'].'<br>  Swiss Pac Pvt Ltd</b>';
		$subject =$data['subject']; 
		
		
		$form_email=$email_id['email'];
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(7); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
		$path = HTTP_SERVER."template/proforma_invoice.html";
		$output = file_get_contents($path);  
		$search  = array('{tag:header}','{tag:details}');

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
	 
	
		    send_email_test($data['toemail'],$form_email,$subject,$message,$attachment,'','1',$data['bccemail'],$data['ccemail']);
		 
		//}
		
		//die;
    }
     public function getCCEmails($admin_user_id){
    		/*$userEmployee = $this->getUserEmployeeIds(4,$admin_user_id);
    		$set_user_id = $admin_user_id;
    		$set_user_type_id = 4;
    		$str = '';
    		if($userEmployee){
    			$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 ) '; 
            } 
            $sql="SELECT GROUP_CONCAT(email) as t_email  FROM `account_master` WHERE (`user_id` ='".$admin_user_id."' AND `user_type_id`= 4  $str)";
            $data=$this->query($sql);
         //   echo $sql;die;
            	if($data->num_rows)
    			return $data->row['t_email'];
    		else
    			return false;*/ 
    			
    			
    		 $sql="SELECT * FROM `tracking_email_list` WHERE admin_user_id='".$admin_user_id."'";
                
              $data=$this->query($sql);
            //  printr($data);
            	if($data->num_rows)
    			    return $data->row;
    	    	else
    		    	return false;
    			
    			
    			
    }
    public function getmodelnumber($invoice_id){
        $sql=" SELECT GROUP_CONCAT(DISTINCT(size)) as model_no FROM `invoice_color_test` WHERE invoice_id='".$invoice_id."'";
                
              $data=$this->query($sql);
            //  printr($data);
            	if($data->num_rows)
    			    return $data->row['model_no'];
    	    	else
    		    	return false; 
    }  
    public function getCouriers(){
        $sql=" SELECT * FROM `courier` WHERE is_delete=0 AND status=1 ORDER BY `courier_id` ASC";
                
              $data=$this->query($sql);
            //  printr($data);
            	if($data->num_rows)
    			    return $data->rows;
    	    	else
    		    	return false; 
    }   
    public function getcourier_account_number($user_id,$country_id,$courier_id)
	{
	
	        $sql = "SELECT * FROM courier_account_number as ca,international_branch as ib   WHERE   ib.international_branch_id=ca.admin_user_id   AND ca.admin_user_id='".$user_id."' AND ca.courier_id='".$courier_id."' AND ca.status=1 AND ca.is_delete=0  AND ca.country_id=".$country_id;
	        //	echo $sql;
			$data=$this->query($sql);
				if($data->num_rows){
					return $data->row;
				}else{
					return false;
				}
		
	}
    public function dispatch_order_detail($order_id){ 
            $sql_order="SELECT GROUP_CONCAT(custom_order_id) as order_id FROM `multi_custom_order` WHERE `multi_custom_order_id` = '".$order_id."'";
            $data_order=$this->query($sql_order);
             
               $sql=" SELECT * FROM `invoice_product_test` as ip ,invoice_color_test as ic , invoice_test as i ,product_code as p WHERE ip.`order_id` IN(". $data_order->row['order_id'].") AND p.product_code_id=ip.product_code_id AND ip.invoice_id=i.invoice_id AND ip.invoice_product_id=ic.invoice_product_id AND i.invoice_id=ic.invoice_id  AND ip.order_size_id=0 AND i.is_delete=0 AND i.done_status=1";
            //   echo $sql;die;
              $data=$this->query($sql);
            	if($data->num_rows)
    			    return $data->rows;
    	    	else
    		    	return false; 
    }
}
?>