<?php
class invoice extends dbclass{	
	
	public function addInvoice($data=array())
	{
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
					$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id FROM " . DB_PREFIX . "taxation WHERE 
					status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
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
		if(!isset($data['remarks']))
			$data['remarks']='';
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
		$sql = "INSERT INTO `" . DB_PREFIX . "invoice` SET invoice_no = '".$data['invoiceno']."',invoice_date = '" .$data['invoicedate']. "',
		exporter_orderno = '".$data['ref_no']."',gst='".$data['gst']."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."', buyers_orderno ='".$data['buyersno']."',other_ref ='".$data['other_ref']."',pre_carrier='".$data['pre_carrier']."',consignee='".addslashes($data['consignee'])."',buyer='".$data['other_buyer']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',
		port_load='".$data['portofload']."',port_discharge='".$data['port_of_dis']."',final_destination='".$data['country_id']."', taxation='".$taxation."', excies='".$excies."', tax='".$tax."', tax_mode ='".$data['tax_mode']."',tax_form ='".$form."' ,payment_terms='".$data['payment_terms']."',delivery='".$data['delivery']."',HS_CODE='".$data['hscode']."',pouch_type='".$data['printedpouches']."',pouch_desc='".$data['pouch_desc']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',cylinder_charges='".$data['cylinder_charges']."',container_no='".addslashes($data['container_no'])."',seal_no='".addslashes($data['seal_no'])."', remarks='".$data['remarks']."',postal_code = '".$data['postal_code']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',transportation='".encode($data['transport'])."',curr_id='".$data['currency']."',
		status = '".$data['status']."',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0";
		$datasql=$this->query($sql);
		$invoice_id = $this->getLastId();
		}
		$sql2 = "Insert into invoice_product Set invoice_id='".$invoice_id."',product_id='".$data['product']."', valve = '".$data['valve']."', zipper = '".$data['zipper']."', spout = '".$data['spout']."',make_pouch='".$data['make']."', accessorie = '".$data['accessorie']."',net_weight='".$data['netweight']."',measurement_two='".$data['measurement']."',buyers_o_no='".$data['buyers_o_no']."',item_no='".$data['item_no']."', date_added = NOW(), date_modify = NOW(), is_delete = 0"; 
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
				$sql3 = "Insert into invoice_color Set invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$color['color']."',color_text='".$clr_txt."', rate ='".$color['rate']."', qty = '".$color['qty']."', size = '".$color['size']."',  measurement = '".$color['measurement']."',dimension='".$dim."',net_weight='".$color['net_weight']."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 			
				$data3=$this->query($sql3);
			}
		}		
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
		$sql ="SELECT * FROM invoice AS i LEFT JOIN invoice_product AS ip ON (i.invoice_id=ip.invoice_id) LEFT JOIN invoice_color AS ic ON (i.invoice_id=ic.invoice_id AND ip.invoice_product_id=ic.invoice_product_id) LEFT JOIN box_master AS bm ON (bm.pouch_volume = ic.size AND bm.pouch_volume_type=ic.measurement AND bm.product_id = ip.product_id AND bm.zipper=ip.zipper AND bm.valve=ip.valve AND bm.spout=ip.spout AND bm.accessorie=ip.accessorie AND bm.make_pouch=ip.make_pouch AND bm.transportation=i.transportation AND bm.is_delete=0) WHERE i.invoice_id=".$invoice_id." ORDER BY ip.product_id ASC,bm.pouch_volume DESC,ic.qty DESC";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	
	public function in_gen_box_uni_id()
	{
		$data = $this->query("SELECT Max(box_unique_id) as box_uni_no FROM in_gen_invoice");
		$box_uni = $data->row['box_uni_no'];
		return $box_uni;
	}
	
	public function genrateLable($invoice_no)
	{
		/*$space=0;
		$data1=$this->getInvoiceProductWithBox($invoice_no);
		$tot_box='';$box_qty='';$tot_box_arr='';$next_pro_id='';
		$box_no=1;$remove=0;
		foreach($data1 as $new_data)
		{
			if($new_data['color']!='-1')
				$new[$new_data['product_id']][]=$new_data;
			else
				$new[$new_data['product_id'].'-'.$new_data['invoice_color_id']][]=$new_data;
		}
	//	printr($new);die;
		foreach($new as $data)
		{
			foreach($data as $key=>$datas)
			{
				if( !isset($data[$key]['remove']))
				{
					$tot_box=$datas['qty']/$datas['quantity'];
					
					$tot_box_arr=explode('.',$tot_box);		
						
					if(isset($tot_box_arr[1]) && $tot_box_arr[1]>0)
					{
						$space=1;
						$val=$tot_box_arr[0]+1;
						$tot_box=$val;
					}
				//echo $tot_box;
					if(isset($data[$key-1]['invoice_product_id']) )
					{
						$tot_gross_weight=$tot_gross_weight+($datas['box_weight']*$tot_box)+$datas['net_weight'];	
					}
					else
						$tot_gross_weight='';
					
					if($tot_gross_weight=='')
							$tot_gross_weight=($datas['box_weight']*$tot_box)+$datas['net_weight'];	
					if($tot_gross_weight!='')
						$sql="UPDATE invoice_product SET gross_weight='".$tot_gross_weight."' WHERE invoice_product_id ='".$datas['invoice_product_id']."'" ;
						$this->query($sql);
							
					$detail='';$c=0;
					
					for($i=1;$i<=$tot_box;$i++)
					{					
						$box_qty=$datas['quantity'];
						
							$detail[$c]=array('size'=>$datas['size'],
											'measurement'=>$datas['measurement'],
											'color'=>$datas['color'],
											'color_txt'=>$datas['color_text'],
											'dimension'=>$datas['dimension'],
											'box_qty'=>$box_qty,
											'net_wt'=> $datas['net_weight'],
											'gross_wt'=> ($datas['net_weight']+$datas['box_weight']));	
						
						if($space==1)
						{
							if($i<$tot_box)
							{
								$box_qty=$datas['quantity'];
								$box_qty_other='';							
							}
							else
							{
								$box_qty=($datas['qty']-(($i-1)*$datas['quantity']));												
							}	
							$detail[$c]['box_qty']=$box_qty;	
							$color=$datas['color'];
							$color_txt=$datas['color_text'];
							if(isset($data[$key+1]))
								$next_pro_id=$data[$key+1]['invoice_product_id'];
							if($next_pro_id==$datas['invoice_product_id'] && isset($data[$key+1]))	
							{	
								if($datas['quantity']<$data[$key+1]['qty'])
									$other=$datas['quantity']-$box_qty;										
								else
								{
									$other=$data[$key+1]['qty'];
									$data[$key+1]['remove']=1;
									$remove=1;
								}
								if($other>0 )
								{	$c++;
									$detail[$c]=array('size'=>$data[$key+1]['size'],
											'measurement'=>$data[$key+1]['measurement'],
											'color'=>$data[$key+1]['color'],
											'color_txt'=>$data[$key+1]['color_text'],
											'dimension'=>$data[$key+1]['dimension'],
											'box_qty'=>$other,
											'net_wt'=> $data[$key+1]['net_weight'],
											'gross_wt'=> ($data[$key+1]['net_weight']+$data[$key+1]['box_weight']));						
								}									
							}								
						}
						$box[]=array('box_no'=>$box_no,
									'total_box'=>$tot_box,
									'total_qty'=>$datas['qty'],							
									'item_no'=>$datas['item_no'],							
									'buyers_o_no'=>$datas['buyers_o_no'],
									'product_id'=>$datas['product_id'],
									'zipper'=>$datas['zipper'],
									'valve'=>$datas['valve'],
									'invoice_id'=>$datas['invoice_id'],
									'detail'=>$detail,
								);
					$box_no++;
					}
				}
			}
		}
		//printr($box);
		//die;
		return $box;*/
	}	
	
	/*public function addInInvoice($invoice_no)
	{
		//$Lable=$this->genrateLable($invoice_no);
	    $sql2="Delete from in_lable_invoice where invoice_id='".$invoice_no."' ";
		$data2=$this->query($sql2);
		$new_id = $this->in_gen_box_uni_id();
		$new_box_id= $new_id + 1;
		foreach($Lable as $box_detail)
		{
			$sql="Insert into in_lable_invoice set invoice_id='".$box_detail['invoice_id']."',box_unique_id='".$new_box_id."',box_unique_number='BX".sprintf("%014s",$new_box_id)."',box_no='".$box_detail['box_no']."',	zipper='".$box_detail['zipper']."',	valve='".$box_detail['valve']."',	product='".$box_detail['product_id']."',buyers_order_no='".$box_detail['buyers_o_no']."',item_no='".$box_detail['item_no']."',detail='".json_encode($box_detail['detail'])."',date_added=NOW(),status=0";
			$data = $this->query($sql);
			$new_box_id++;
		}
	}*/
	
	/*public function addOutInvoice($invoice_no)
	{
	    $sql2="Delete from out_lable_invoice where invoice_id='".$invoice_no."' ";
		$data2=$this->query($sql2);
		$new_id = $this->out_gen_box_uni_id();
		$new_box_id= $new_id + 1;
		//$Lable=$this->genrateLable($invoice_no);
		foreach($Lable as $box_detail)
		{
			$sql="Insert into out_lable_invoice set invoice_id='".$box_detail['invoice_id']."',box_unique_id='".$new_box_id."',box_unique_number='BX".sprintf("%014s",$new_box_id)."',box_no='".$box_detail['box_no']."',	product='".$box_detail['product_id']."',	zipper='".$box_detail['zipper']."',	valve='".$box_detail['valve']."',buyers_order_no='".$box_detail['buyers_o_no']."',item_no='".$box_detail['item_no']."',detail='".json_encode($box_detail['detail'])."',date_added=NOW(),status=0";
			$data = $this->query($sql);
			$new_box_id++;
		}
	}		*/
	
	public function out_gen_box_uni_id()
	{
		$data = $this->query("SELECT Max(box_unique_id) as box_uni_no FROM out_lable_invoice");
		$box_uni = $data->row['box_uni_no'];
		return $box_uni;
	}
	
	public function getInvoiceData($invoice_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "invoice  WHERE invoice_id = '" .(int)$invoice_id. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getInvoiceNetData($invoice_id)
	{
		$sql = "SELECT SUM(ip.net_weight) as net_weight,SUM(ip.gross_weight) as gross_weight, i.* FROM  " . DB_PREFIX . "invoice as i,invoice_product as ip WHERE i.invoice_id =ip.invoice_id  AND i.invoice_id = '" .(int)$invoice_id. "' AND i.is_delete=0";
		//echo $sql;
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
		$sql = "SELECT SUM(ic.qty) as total_qty,SUM(ic.rate) as total_rate,ic.rate,SUM(ic.qty*ic.rate) as tot FROM  " . DB_PREFIX . "invoice as i,invoice_color as ic WHERE i.invoice_id = '" .(int)$invoice_id. "' 
		AND i.is_delete=0 AND i.invoice_id=ic.invoice_id";
		$data = $this->query($sql);
	//	printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getInvoiceProductId($invoice_product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_product` WHERE invoice_product_id = '" .(int)$invoice_product_id. "'  AND is_delete=0 ";
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
			$sql = "SELECT e.user_name, e.user_id,co.country_id, e.multi_quotation_price,ib.company_address,ib.company_name,ib.default_curr,co.country_name, e.first_name, e.last_name, ib.email_confirm,e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_address,ib.default_curr,ib.company_name,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
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
		$sql = "select ic.*,tm.measurement,pc.color from `".DB_PREFIX."invoice_color` as ic,template_measurement as tm,pouch_color as pc WHERE invoice_id='".$invoice_id."' AND invoice_product_id = '".$invoice_product_id."' AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id ";
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
		$sql="select ic.qty,ig.qty as genqty,ic.color,ic.invoice_color_id,ic.color_text,ic.net_weight,ic.size,ic.measurement,ic.invoice_product_id,ic.product_name,ifnull(ic.qty-ig.qty,ic.qty)  as remaingqty from (select ii.qty,ii.size,ii.color_text,tm.measurement,ii.invoice_product_id,p.product_name ,ii.invoice_color_id,ii.invoice_id,c.color,ii.net_weight from invoice_color as ii,pouch_color as c,template_measurement  as tm,invoice_product as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id) as ic left join (select sum(qty) as qty,invoice_color_id from in_gen_invoice GROUP BY invoice_color_id) as ig on (ic.invoice_color_id=ig.invoice_color_id) where ic.invoice_id='".$invoice_id."'";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->rows;
		}
		else {
			return false;
		}
	}
	public function in_gen_box_no($invoice_id)
	{
		$data = $this->query("SELECT box_no FROM in_gen_invoice WHERE invoice_id='".$invoice_id."' ORDER BY box_no DESC LIMIT 1");
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
		//printr($postdata['in_product_id']);
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
			$sql= "INSERT INTO in_gen_invoice SET invoice_id='".$postdata['invoice_id']."',invoice_color_id='".$postdata['detail']."',qty='".$postdata['per_qty']."', box_weight ='".$postdata['per_box_weight']."', net_weight ='".$postdata['net_weight']."',box_unique_id='".$box_unique_id."',parent_id='".$postdata['in_gen_id']."',box_unique_number='".$box_unique_number."',date_added = NOW(),date_modify = NOW(),box_no='".$box_no."',invoice_product_id='".$postdata['in_product_id']."',is_delete = 0"; 	
			//printr($sql);
			$data=$this->query($sql);
			$box_no++;
		}
		//die;
	}
	
	public function savePallet($postdata)
	{
		$data = $this->query("SELECT pallet_no FROM invoice_pallet WHERE invoice_id='".$postdata['invoice_no']."' ORDER BY pallet_id DESC LIMIT 1");
		$start = $data->row['pallet_no'];	
		for($i=1;$i<=$postdata['total_pallet'];$i++)
		{
			$data=$this->query("INSERT INTO invoice_pallet SET pallet_no='".($start+$i)."', invoice_id='".$postdata['invoice_no']."'");
		}
	}
	
	public function deletePallet($postdata)
	{
		$data=$this->query("DELETE  FROM invoice_pallet WHERE pallet_id='".$postdata['pallet_id']."'");
		$data1=$this->query("UPDATE in_gen_invoice SET  pallet_id='0' WHERE pallet_id='".$postdata['pallet_id']."' AND invoice_id='".$postdata['invoice_id']."'");
		return $data;
	}
	
	public function savePalletdetail($postdata)
	{
		$total_box=count($postdata['detail']);
		$sql1= "UPDATE in_gen_invoice SET pallet_id='0' WHERE pallet_id='".$postdata['pallet_id']."' AND invoice_id='".$postdata['invoice_id']."'"; 	
		$data1=$this->query($sql1);
		foreach($postdata['detail'] as $in_gen_invoice_id)
		{
			$sql= "UPDATE in_gen_invoice SET pallet_id='".$postdata['pallet_id']."' WHERE in_gen_invoice_id='".$in_gen_invoice_id."'"; 	
			$data=$this->query($sql);
		}
		return $data;
	}
	
	public function getPallet($invoice_id)
	{
		$sql1= "SELECT * FROM invoice_pallet WHERE invoice_id='".$invoice_id."' ORDER BY pallet_no ASC";
		$result=$this->query($sql1);
		return $result->rows;
	}
	public function getPalletS($invoice_id)
	{
		$sql1= "SELECT ip.*,count(ig.pallet_id) as tot FROM invoice_pallet AS ip , in_gen_invoice AS ig WHERE ip.invoice_id='".$invoice_id."' AND ip.pallet_id=ig.pallet_id GROUP BY ip.pallet_id ORDER BY ip.pallet_no ASC";
		$result=$this->query($sql1);
		return $result->rows;
	}
	public function getPalletDetailstotal($invoice_id) 
	{	
		$sql1= "SELECT * FROM in_gen_invoice WHERE invoice_id='".$invoice_id."' AND pallet_id=0";
		$result=$this->query($sql1);
		return $result->num_rows;
	}
	public function getColorDetailstotal($invoice_id) 
	{	
		$sql1= "Select * from in_gen_invoice where invoice_id='".$invoice_id."'";
		$result=$this->query($sql1);
		//printr($result);
		//die;
		if($result->num_rows==0)
		{
			$sql = "select sum(qty) as total from invoice_color where invoice_id='".$invoice_id."' ";
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
		$sql = "select ifnull(ic.qty-ig.genqty,ic.qty) as total from (select sum(qty) as qty,invoice_color_id,invoice_id from invoice_color group by invoice_id ) as ic
		left join (select  sum(qty) as genqty,invoice_color_id from  in_gen_invoice group by invoice_id) as ig on (  ig.invoice_color_id=ic.invoice_color_id)  WHERE ic.invoice_id='".$invoice_id."'";	
		//echo $sql;	
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->row['total'];
		}
		else {
			return false;
		}
		}
		//echo $sql;
	}
	//select ic.qty,ig.titalqty,ic.qty from (select qty ,invoice_color_id from invoice_color) ic left join (select total_qty,invoice_color_id from in_gen_invoice GROUP BY invoice_color_id) 
	public function consol_list($invoice_id)
	{
		$sql="SELECT ig.in_gen_invoice_id, COUNT(DISTINCT ig.in_gen_invoice_id) AS total_boxes, GROUP_CONCAT(ig.box_no) AS grouped_box_no, SUM(ig.qty) AS qty,SUM(ig.box_weight+ig.net_weight) AS gross_weight,SUM(ig.net_weight) AS net_weight  ,SUM(ic.rate) AS rate,ip.item_no,ip.zipper,ip.valve,p.product_name,CONCAT(ic.size,' ',tm.measurement) AS size,pc.color,ic.color_text FROM in_gen_invoice AS ig , invoice_product AS ip,product AS p,invoice_color AS ic,template_measurement AS tm,pouch_color AS pc WHERE ig.invoice_id=1 AND ig.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ig.invoice_color_id=ic.invoice_color_id AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id GROUP BY ig.invoice_color_id,ig.invoice_product_id";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function colordetails($invoice_id,$parent_id=0,$str='',$pallet='',$limit='')
	{	
		$sql="select ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,
		p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color as ii,pouch_color as c,template_measurement as tm,invoice_product as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice WHERE parent_id='".$parent_id."' ".$str." ".$pallet.") as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC ".$limit." "; 
	//echo $sql.'<br><br>';//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function gettotalboxweight($invoice_id)
	{
		$sql = "SELECT SUM(box_weight) as total_box_weight, SUM(net_weight) as total_net_weight, SUM(box_weight+net_weight) as total_gross_weight FROM `" . DB_PREFIX . "in_gen_invoice` WHERE invoice_id = '".$invoice_id."' AND parent_id=0";
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
		$sql = "SELECT invoice_no FROM `" . DB_PREFIX . "invoice` WHERE invoice_no = '" .(int)$invoice_no. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function removeInvoice($invoice_product_id,$invoice_id)
	{
		$sql1=$this->query("DELETE FROM invoice_color WHERE invoice_product_id='".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
		$sql = $this->query("DELETE FROM invoice_product  WHERE `invoice_product_id` = '".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
		 $this->query("DELETE FROM in_gen_invoice  WHERE `invoice_product_id` = '".$invoice_product_id."' AND invoice_id='".$invoice_id."'");
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
		if(!isset($data['remarks']))
			$data['remarks']='';
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
		$sql = "UPDATE `" . DB_PREFIX . "invoice` SET invoice_no='".$data['invoiceno']."',invoice_date = '" .$data['invoicedate']. "',exporter_orderno = '".$data['ref_no']."',gst='".$data['gst']."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."', buyers_orderno ='".$data['buyersno']."',other_ref ='".$data['other_ref']."',pre_carrier='".$data['pre_carrier']."',consignee='".addslashes($data['consignee'])."',buyer='".$data['other_buyer']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',port_load='".$data['portofload']."',port_discharge='".$data['port_of_dis']."',final_destination='".$data['country_id']."', taxation='".$taxation."', excies ='".$excies."', tax='".$tax."', tax_form ='".$form."', tax_mode ='".$data['tax_mode']."',payment_terms='".$data['payment_terms']."',delivery='".$data['delivery']."',HS_CODE='".$data['hscode']."',pouch_type='".$data['printedpouches']."',pouch_desc='".$data['pouch_desc']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',cylinder_charges='".$data['cylinder_charges']."',remarks='".$data['remarks']."',transportation='".encode($data['transport'])."',container_no='".addslashes($data['container_no'])."',seal_no='".addslashes($data['seal_no'])."',curr_id='".$data['currency']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',status = '".$data['status']."',postal_code = '".$data['postal_code']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0,generate_status='1' WHERE invoice_id = '".$data['invoice_id']."'";
		//echo $sql;die;
		$data = $this->query($sql);
		return $data;
	}
	
	public function updateInvoiceProduct($data)
	{		
		$sql1 = "UPDATE `".DB_PREFIX."invoice_product` SET 
		invoice_id='".$data['invoice_id']."',product_id='".$data['product']."', valve = '".$data['valve']."',make_pouch='".$data['make']."', zipper = '".$data['zipper']."', spout = '".$data['spout']."', accessorie = '".$data['accessorie']."',net_weight='".$data['netweight']."',measurement_two='".$data['measurement']."',buyers_o_no='".$data['buyers_o_no']."',item_no='".$data['item_no']."',date_added = NOW(), date_modify = NOW(), is_delete = 0 Where invoice_product_id = '".$data['pro_id']."'";
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
						$sql = "UPDATE `".DB_PREFIX."invoice_color` SET invoice_id = '".$id."', invoice_product_id = '".$product_id."',color_text='".$clr_txt."',color = '".$color['color']."',size='".$color['size']."', rate ='".$color['rate']."', qty = '".$color['qty']."', measurement = '".$color['measurement']."',dimension='".$dim."',net_weight='".$color['net_weight']."' WHERE invoice_color_id='".$color['invoice_color_id']."'";
					}
					else
					{
						$sql = "DELETE FROM `".DB_PREFIX."invoice_color` WHERE invoice_color_id='".$color['invoice_color_id']."'";				
						$this->query("DELETE FROM  ".DB_PREFIX."in_gen_invoice WHERE invoice_color_id='".$color['invoice_color_id']."'");				
					}
				}
				else
				{
					$sql = "INSERT INTO `".DB_PREFIX."invoice_color` set invoice_id = '".$id."', invoice_product_id = '".$product_id."',color_text='".$clr_txt."',color = '".$color['color']."',size='".$color['size']."', rate ='".$color['rate']."', qty = '".$color['qty']."', measurement = '".$color['measurement']."',dimension='".$dim."',net_weight='".$color['net_weight']."'";
				}
				$data = $this->query($sql);
			}			
		}
	}
	public function updateBoxno($data)
	{
		$sql = "UPDATE `".DB_PREFIX."in_gen_invoice` SET box_no='".$data['box_no']."' WHERE in_gen_invoice_id='".$data['gen_unique_id']."'";
		//echo $sql;
		$data=$this->query($sql);
		return $data;
	}
	public function updatePalletNo($data)
	{
		$sql = "UPDATE `".DB_PREFIX."invoice_pallet` SET pallet_no='".$data['pallet_no']."' WHERE pallet_id='".$data['pallet_id']."'";
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
		$sql = "SELECT count(in_gen_invoice_id) as tot FROM " . DB_PREFIX . "in_lable_invoice WHERE invoice_id = '".$invoice_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getTotalBox($invoice_id)
	{
		$sql = "SELECT count(in_gen_invoice_id) as tot FROM " . DB_PREFIX . "in_gen_invoice WHERE invoice_id = '".$invoice_id."' AND parent_id=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;

		}
	}
	public function getPlasticScoopQty($product_id,$invoice_product_id,$invoice_no)
	{
		$sql="SELECT SUM(qty) as total,SUM(rate) as tot_rate,rate,(SUM(qty)*SUM(rate)) as tot_amt FROM invoice_color WHERE invoice_id='".$invoice_no."' AND invoice_product_id='".$invoice_product_id."'";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function getProductdeatils($invoice_no)
	{
		$sql="SELECT * FROM invoice_product WHERE invoice_id='".$invoice_no."'";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	
	public function getTotalPallet($invoice_id)
	{
		$sql="SELECT * FROM invoice_pallet WHERE invoice_id='".$invoice_id."'";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->num_rows;
		else
			return false;
	}
	
	public function viewInvoice($status,$invoice_no)
	{	
		$invoice=$this->getInvoiceNetData($invoice_no);
		//printr($invoice);die;
		$pallet=$this->getPalletS($invoice_no);
		$total_pallet=count($pallet);
		$total_pallet_box=0;
		foreach($pallet as $p)
		{
			$total_pallet_box=$p['tot']+$total_pallet_box;
		}
	
		$total_pallet_weight=$total_pallet*23;
		$invoice_qty=$this->getInvoiceTotalData($invoice_no);
		//printr($invoice_qty);die;
		$box_detail=$this->gettotalboxweight($invoice_no);
		$alldetails=$this->getProductdeatils($invoice_no);
		//printr($alldetails);
		//$tot_qty_scoop=0;
		$flag1 = array();
		$scoop_no = '';
		$scoop ='';
		$tot_scoop_qty = 0;
		$tot_scoop_rate = 0;
		foreach($alldetails as $details)
		{
			//zprintr($details);
			$flag=0;
			if($details['product_id']=='11')
			{
				$tot_qty_scoop=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				//printr($tot_qty_scoop);
				$flag=1;
				$scoop = array($flag);
			}
			if($flag == '1')
			{
				$flag1[] =$tot_qty_scoop;
				$tot_scoop_qty = $tot_scoop_qty + $tot_qty_scoop['total']; 
				$tot_scoop_rate = $tot_scoop_rate + $tot_qty_scoop['rate'];
				$scoop_no = '1';
			}
			
			//$scoop = array($flag);
			//$flag1[$flag == '1' ? 'Scoop' : 'flag'] = $flag;
			
		}
		//printr($scoop_no);
		//printr($tot_scoop_qty);
		//printr($tot_scoop_rate);
		$totgross_weight=$box_detail['total_net_weight']+$box_detail['total_box_weight'];
		$taxation=$invoice['taxation'];
		$html='';                       
  		$html.='<div class="panel-body" id="print_div" style="padding-top: 0px;width:754px">
					<div class="">
					 <div class="form-group ">  	';
      	$fixdata = $this->getFixmaster(); 
       $html.='<table style="cellpadding:0px;cellspacing:0px;  font-size: 10px;" border="1" cellpadding="0" cellspacing="0" >
	   		<tr><td colspan="8">';
				   if($status==1){
				  		$html.='<h1>INVOICE</h1>';
				   }
				  else{
				  		$html.='<h1>PACKING</h1>';
				   }
				  $html.='</td></tr>
        	 <tr>
          		 <td colspan="4" ><strong>Exporter:</strong></td>
         		 <td colspan="4"><strong><u>Invoice No. & Date:</u></strong><strong style="float:right"><u> Exporters Order No.: </u>&nbsp;&nbsp;&nbsp;&nbsp;
                 </strong></td>
         	 </tr>
      	     <tr>
      	       		<td colspan="4"  style="vertical-align: top;"><div>'.nl2br($fixdata['exporter']).'</div></td>
               		<td colspan="4"><table>';
					// <img src="'.HTTP_SERVER.'admin/model/barcodes.php?text=259 / 15-16 / 29-05-2015 &codetype=Code39&size=40" alt="259 / 15-16 / 29-05-2015 " /> <br><br>
					 $html.='<tr><td>'.$invoice['invoice_no'].'&nbsp;/&nbsp;'.(date('y')).'-'.(date('y')+1).'&nbsp;/&nbsp;'.date("d-m-Y",strtotime($invoice['invoice_date'])).'
              		 <span style="float:right">'.$invoice['exporter_orderno'].'&nbsp;&nbsp;&nbsp;&nbsp;</span><br><br>
					 
                   <span><img src="http://www.bcgen.com/demo/linear-dbgs.aspx?D='.$invoice['invoice_no'].'"></span></td></tr>
					 <tr><td><strong>Buyers Order/Ref. No.:</strong> &nbsp;'.$invoice['buyers_orderno'].'</td></tr>
					 <tr>';if($status==1){
          					$html.='<td ><strong>Other Referene(s):</strong> &nbsp;'. $invoice['other_ref'].'</td>'; }
							$html.='</tr></table></td>
    	     </tr>';
		if($status==1){
             $html .= '<tr>              
               		 <td colspan="4">&nbsp;</td>
               		 <td colspan="4" rowspan="3" style="vertical-align: top;"><strong><u>Buyer (If other than consignee):</u></strong><br/>'.$invoice['buyer'].'</td>
             </tr>
      		 <tr>
           			 <td colspan="4" style="vertical-align: top;"><strong>Consignee :</strong></td>
          	</tr>';
		} else {
			$html .= '<tr>
           			 <td colspan="4" style="vertical-align: top;"><strong>Consignee:</strong></td>
					 <td colspan="4"><strong><u>Buyer (If other than consignee):</u></strong><br/>'.$invoice['buyer'].'</td>
          	</tr>';
		}
       		  $html .='<tr>
          			 <td colspan="4" rowspan="2"  style="vertical-align: top;"><div>'.nl2br($invoice['consignee']).'</div></td>
       		  </tr>
         	  <tr>
          			 <td colspan="2" style="vertical-align: top;"><strong><u>Country of origin of goods</u></strong><br/>'.$fixdata['country_origin_goods'].'</td>
           			 <td colspan="2" style="vertical-align: top;"><strong><u>Country of Final Destination</u></strong><br />
            			 <div>'; $country_name = $this->getCountryName($invoice['country_destination']);
						 	$html.= $country_name['country_name'].'</div></td>
        	 </tr>
         	<tr>
          			 <td colspan="2" style="vertical-align: top;"><strong><u>Pre-Carrige By:</u></strong><br/>
         		  			  <div>By '. ucwords(decode($invoice['transportation'])).'</div></td>
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
		 						 $html.= $country_name['country_name'].'</div></td>
           			<td colspan="2" style="vertical-align: top;"><strong><u>Final Destination:</u></strong>
           				<br />
            			 <div>';  $country_name=$this->getCountryName($invoice['final_destination']); 
							 $html.= $country_name['country_name'].'</div></td>
         </tr>
         <tr style="vertical-align: top;">'; $currency=$this->getCurrencyName($invoice['curr_id']); 
		 	//printr($currency);
				 $measurement=$this->getMeasurementName($invoice['measurement']);
           		$html.='<th colspan="2">Marks & No.'; if($status==1){ $html.='/Container No.</th>
                
          		 <th colspan="2">No. & Kind of Packages</th>
          		 <th>Description</th>
                 <th>Quntity In Nos.</th>
           		 <th>Rate per '. $currency['currency_code'].'<br />
           		 			 Per 1 Nos</th>
          		 <th>Amount <br />'. $currency['currency_code'].'</th>';

                  } else { 
					 $html.='<th colspan="3">Description</th>
					 <th>Quntity In Nos.</th>
					 <th colspan="2">Remarks</th>';
                  } 
				  $total_no_of_box=$this->getTotalBox($invoice_no);
				 $html.='</tr>
				 <tr>
          		 <td  colspan="2" class="no_border">'.$total_no_of_box['tot'].' Boxes<br>(Nos. 1 to '.$total_no_of_box['tot'].')<br>Corrugated Box Packing<br>Gross weight : '.
			 		number_format($totgross_weight,3).' '.$measurement['measurement'].'<br>';
					if(ucwords(decode($invoice['transportation']))=='Sea')
						$html.='Tare Weight : '.$total_pallet_weight.' Kgs<br>';
					$html.=' Net Weight : '.number_format($box_detail['total_net_weight'],3).'&nbsp;'.$measurement['measurement'].'<br>';
					if(ucwords(decode($invoice['transportation']))=='Sea')
					{
					//echo '(('.$total_no_of_box.')-('.$total_pallet_box.'))';
						$loose_boxes=(($total_no_of_box['tot'])-($total_pallet_box));
						$html.='Total '.$total_pallet.' Wooden Pallets<br>Contaning  '.$total_pallet_box.' Boxes  ';
						if($loose_boxes>0)
						$html.=' , & '.$loose_boxes.' Loose Boxes';
					}
					else
					$html.='Total '.$total_no_of_box['tot'].' Boxes ';				 
                     $html.='</td>	<td colspan="3"  class="no_border"><div>'. $fixdata['num_packages'].'</div></td>
                     <td class="no_border"></td>';
                     
					 if($status == 1) {
                     $html.='<td class="no_border"></td>
                     <td class="no_border"></td>';
                      } else { 
                     $html.='<td class="no_border" colspan="2"></td>';
                      }

                     $html.='</tr>
                     <tr>
                     <td colspan="2" class="no_border">'. $fixdata['mark_no'].' </td>
					 
         		 <td colspan="3" class="no_border">
          				 <div><strong>A)&nbsp;'. $invoice['pouch_desc'].'</strong><br />'. $invoice['pouch_type'].'</div><br />';
						 
						// $html.='<div><strong>We Intend to claim rewards under merchandise Export <br></strong> From India Scheame (MEIS)</div><br />';
					  
						if($scoop_no == '1')
						{	
							$total_qty_val=$invoice_qty['total_qty']-$tot_scoop_qty;
							$total_rate_val=$tot_scoop_rate;
							//echo $invoice_qty['rate'].'-'.$tot_qty_scoop['rate'];
							//$total_amt_val=$total_qty_val*$total_rate_val;
							$total_amt_val=$invoice_qty['tot'];	
							//echo $total_amt_val;			
						}
						else
						{
							$total_qty_val=$invoice_qty['total_qty'];
							$total_rate_val=$invoice_qty['rate'];
							//$total_amt_val=$total_qty_val*$invoice_qty['rate'];
							$total_amt_val=$invoice_qty['tot'];
						}
						//echo $total_qty_val;
						$rate_per = number_format((((($total_amt_val-$invoice['tran_charges'])-$invoice['cylinder_charges'])-($total_rate_val*$tot_scoop_qty))/$total_qty_val),8);
						$amt = round(($total_amt_val-$invoice['tran_charges'])-$invoice['cylinder_charges']-($total_rate_val*$tot_scoop_qty));
						
						$air_rate = $amt * $currency['price'];
						
					  if(ucwords(decode($invoice['transportation']))=='Sea')
					  {
					  	$html.='<div><strong>Shipment under the Duty DrawBack Scheme</strong>
						<br>Vide DBK SR No 3923000099 B @ 1.9% On FOB<br><strong>(Cenvat facility has been availed)<br>This Shipment is taken under the EPGC Licence<br>Licence No 3430002141 Dated 23.04.2012</strong></div><br />';
					  }
					  elseif((ucwords(decode($invoice['transportation']))=='Air') && ($air_rate >= '100000'))
					  {
					  	$html.='<div><strong>Shipment under the Duty DrawBack Scheme</strong>
						<br>Vide DBK SR No 3923000099 B @ 2% On FOB<br><strong>(Cenvat facility has been availed)<br>This Shipment is taken under the EPGC Licence<br>Licence No 3430002142 Dated 23.04.2012</strong></div><br />';
					  }
					  
					  //$html.='<div><strong>We hereby declare that,we shall claim as admissible <br></strong> under the chapter 3 FTP</div><br />';
					  $html.='</td>';
					 // printr($tot_qty_scoop['tot_amt']);
					  //die;
               	
				//printr($total_amt_val);
				//printr($tot_qty_scoop['tot_rate']);
				//;
				
           		$html.='<td class="no_border" valign="top"><p align="center">'.$total_qty_val.'</p></td>';
                 if($status==1)
				 { 		
		          		$html.='<td class="no_border" valign="top"><p align="center">'.$rate_per.'</p></td>
          		<td class="no_border" valign="top"><p align="center">'.$amt.'</p>';
				
						$html.='</tr>';
				}
				//printr($flag);
				
						if($scoop_no == '1')
						{
							$html.='<tr>
							<td colspan="2" class="no_border"></td>
							<td colspan="3" class="no_border"><div><strong>Plastic Scoop</strong></div></td>
							<td class="no_border" valign="top"><p align="center">'.$tot_scoop_qty.'</p></td>';
							if($status==1)
							{	
									$html.='<td class="no_border" valign="top"><p align="center">'.$total_rate_val.'</p></td>
									<td class="no_border" valign="top"><p align="center">'.$total_rate_val*$tot_scoop_qty.'</p></td>';
								//	$html.='<td class="no_border" valign="top"><p align="center">'.((($tot_qty_scoop['tot_amt']-$invoice['tran_charges'])-$invoice['cylinder_charges'])/$tot_qty_scoop['total']).'</p></td>
								//	<td class="no_border" valign="top"><p align="center">'. round(($tot_qty_scoop['tot_amt']-$invoice['tran_charges'])-$invoice['cylinder_charges']).'</p></td>';
									$html.='</tr>';
							}
							
						}
						$insurance='0';
						if($status==1)
						{	
							$html.='<tr><td colspan="2" class="no_border"></td>
							<td colspan="3" class="no_border"> HS CODE:&nbsp;'. $invoice['HS_CODE'].'</td>
							<td class="no_border"></td><td class="no_border"></td><td class="no_border"></td>
							</tr><tr>
							<td colspan="2" class="no_border">';
							if(decode($invoice['transportation'])=='sea')
								$html.='Container No :'.$invoice['container_no'].'<br>Seal No : '.$invoice['seal_no'];
							$html.='</td>';
                 if($status==1){
                 	$html.='<td colspan="3" class="no_border"><div><strong>B)&nbsp;'. ucwords(decode($invoice['transportation'])).' Freight Charges</strong><br /><strong>C)Cylinder Making Charges</strong>';
					
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
						 <td class="no_border" valign="top"><p align="center">'. round($invoice['tran_charges'],2).'<br>'.$invoice['cylinder_charges'];
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
					 { 
						  $html.='<td colspan="2" class="no_border" valign="top">'.nl2br($invoice['remarks']).'</td>';
                     }
						  //echo $insurance;
         $html.='</tr>';
		
		 //echo $total_amt_val.'-'.$tot_qty_scoop['tot_amt'];
		// if($flag==1)
			//$final_amount=$total_amt_val+$tot_qty_scoop['tot_amt'];
		//else
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
		}

         $html .='<tr>
					<td  colspan="2"></td>';						
					if($status==1){
						if($invoice['final_destination'] ==111){
					$html.='<td colspan="4"><div align="right"><strong>Grand Total</strong></div></td>';
						} else {
					$html.='<td colspan="4"><div align="right"><strong>Total</strong></div></td>';
						}
						
					$html .='<td><div align="center">'. $currency['currency_code'].'</div></td>
					<td><p align="center">'; $html.=$final_amount.'</p></td></tr>';
		if($invoice['final_destination'] ==111 ) {
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
					<td colspan="4"><div align="right"><strong>Total</strong>';
		$html .='</div></td>
					<td></td>
					<td><p align="center">'.$Total_price.'</p></td>
				</tr>';	
		}
         $html .='<tr>';
         				 $number=$this->convert_number($Total_price);
     					$html.='<td colspan="6"><strong>Amount Chargable :</strong>&nbsp;<u>'. $number.'</u>&nbsp;.&nbsp;('. $currency['currency_code'].') in words</td><td colspan="2">';
						if($invoice['final_destination']=='238')
						$html.='<b>FOB VALUE = '.(($final_amount-$invoice['tran_charges'])-$invoice['cylinder_charges']).'</b>';
						$html.='</td>
         	</tr>';
                 }else {
               			 $html.='<td colspan="3"><div align="right"><strong>Total:</strong></div></td>
               			 <td><p align="center">'.$invoice_qty['total_qty'].'</p></td>
                		<td colspan="2"><strong>Total Net Weight:</strong>&nbsp;'.round($invoice['net_weight']).'&nbsp;'.$measurement['measurement'].'</td></tr>';
                 }
         
         $html.='<tr>
         			<td colspan="5" rowspan="1" valign="top"><p><strong><u>Declaration</u></strong></p>
           		 	'. $fixdata['declaration'].' <br /><span><strong>'. $fixdata['notes'].'</strong></span></td>
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
		return $html;
	}
	/*public function InsertCSVData($handle)
	{
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$data=array();
		$first_time = true;
		$invoice_no='';
	  	//loop through the csv file 
		while($data = fgetcsv($handle,1000,",","'"))
		{
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
			if($invoice_no!=$data[10])
			{
				$invoice_no=$data[10];
				$gst=$data[27]/$data[24]*100;
				$address=$data[2].' '.$data[3].' '.$data[4].' '.$data[5];
				$sql = "INSERT INTO `" . DB_PREFIX . "invoice` SET invoice_no = '".$data[10]."',invoice_date = '" .$data[12]. "',
		exporter_orderno = '',gst='".$gst."',company_address='',bank_address='', buyers_orderno ='',other_ref ='".$data[11]."',pre_carrier='',consignee='".addslashes($address)."',buyer='',country_destination='',vessel_name='',customer_name = '".addslashes($data[0])."', email = '".$data[1]."',port_load='".$data[6]."',port_discharge='',final_destination='".$data[7]."', taxation='', excies='', tax='', tax_mode ='',tax_form ='' ,payment_terms='',delivery='',HS_CODE='',pouch_type='',pouch_desc='',tran_desc='',tran_charges='',cylinder_charges='',container_no='',seal_no='', remarks='',postal_code = '".$data[8]."',account_code = '".$data[25]."',sent = '".$data[34]."',invoice_status = '".$data[35]."',transportation='',curr_id='".$data[32]."',status = '',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0";
			}
			if ($data[0]) { 
				printr($data);
			} 
		}
	}*/
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
				$color = $this->getColorDetails($invoice_no,$details['invoice_product_id']); 			
				foreach($color as $color_val)
				{
					$zipper=$this->getZipper(decode($details['zipper']));
					if($zipper['zipper_name']!='No zip')
						$zipper_name=$zipper['zipper_name'];
					if($details['valve']!='No Valve')
					 	$valve=$details['valve'];
					$tot[$invoice['invoice_no']]=$color_val['qty']*$color_val['rate'];
					$tot_tax[$invoice['invoice_no']]=($tot_tax[$invoice['invoice_no']]+(($tot[$invoice['invoice_no']]*$invoice['gst'])/100));
					$subtotal[$invoice['invoice_no']]=$subtotal[$invoice['invoice_no']]+$tot[$invoice['invoice_no']];
					
					$input_array[$i++] = 
									Array('*ContactName'=>$invoice['customer_name'],
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
										'InventoryItemCode'=>$color_val['size'].' '.$color_val['measurement'].' '.$color_val['color'].' '.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$details['product_name'] ),0,3)).' '.$zipper_name.' '.$valve,
										'*Description'=>$color_val['size'].' '.$color_val['measurement'].' '.$color_val['color'].' '.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$details['product_name'] ),0,3)).' '.$zipper_name.' '.$valve,
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
		
		return $input_array;
	}
	
	public function viewInvoiceForIB($status,$invoice_no)
	{	
		
		$invoice=$this->getInvoiceNetData($invoice_no);
		$currency=$this->getCurrencyName($invoice['curr_id']);	
		$alldetails=$this->getInvoiceProduct($invoice_no);
		//printr($alldetails);
		
		$html='';                       
  		$html.='<style>table {border-collapse: collapse;}td{padding: 6px;}</style>
		<div id="print_div" style="padding-top: 0px;width:754px">
					<div class="">
					 <div id="thetable">';
      	$fixdata = $this->getFixmaster(); 
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
						$setHtml .='<b>Marks & NO. &nbsp;&nbsp;&nbsp;&nbsp;	 :&nbsp;'.$val['box_no'].'/'.$data->num_rows.' Boxes </b><br>';
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
	
	public function viewInout($invoice_no,$status)
	{ //echo $status;
		$parent_id=0;
		$details=$this->colordetails($invoice_no,$parent_id);
		//printr($details);
	//die;
		$invoice=$this->getInvoiceNetData($invoice_no);
		//printr($details);
		
		$setHtml='';$description='';$valve='';$zipper_name='';
		$i=2;$gross_weight=0;$tot_box=count($details);
		if($details!='')
		{
		
		foreach($details as $val)
		{	//printr($val);
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
			//echo $c_name;
			if($val['dimension']!='')
				$size=$val['dimension'];
			else
				$size=$val['size'].' '.$val['measurement'];
				
			$qty=$val['genqty'];
			/*if(isset($childBox) && !empty($childBox))
			{
				//printr($childBox);	
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
			}*/
			//echo $c_name;
			$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
			
			if($description=='')
			{
				
				$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
			}
			//echo $status;
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
				
					$setHtml .='<div style="width:600px;" id="'.$i.'"><table class="table"  border="0" style="  font-size: 14px; width:600px;margin:0px;padding:0px;"><tr>';															             }		
			
		/*if((isset($childBox) && !empty($childBox)) ||  $details!='')
		{
			if(isset($childBox) && !empty($childBox))
			{
				$i = $i;
				foreach($childBox as $ch)
				{
			}*/
				
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
										<td  style="padding:0px;border:0px">'.$val['net_weight'].' Kg</td>
									</tr>
								<tr>';
									if($invoice['final_destination'] == '170' || $invoice['final_destination'] == '253')
									{
										if($invoice['final_destination'] == '170')
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
									<td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;font-size:10px;"><img src="http://www.bcgen.com/demo/linear-dbgs.aspx?D='.$val['box_unique_number'].'"></span><div></td>
									</tr>
									
								</table></td>';
						if(isset($childBox) && !empty($childBox))
						{
							foreach($childBox as $key=>$ch)
							{// printr($ch);
								//printr(sizeof($key));
									$setHtml .='<div style="width:600px;" id="'.$i.'"><table class="table"  border="0" style="  font-size: 14px; width:600px;margin:0px;padding:0px;"><tr>';
								//}	
									$setHtml .='<td style="border:none; border-top:none; width:50%">
												<table  style="  font-size: 14px; ">
													<tr>
														<td  style="padding:0px;border:0px">BOX NO.&nbsp;:&nbsp;</td>
														<td  style="padding:0px;border:0px"><b></b></td>
													</tr>
													<tr>
														<td  style="padding:0px;border:0px">QTY NOS.&nbsp;:&nbsp;</td>
														<td  style="padding:0px;border:0px">'.$ch['genqty'].' PCS</td>
													</tr>
													<tr>
														<td  style="padding:0px;border:0px" valign="top">DESCRIPTION&nbsp;:&nbsp;</td>
														<td  style="padding:0px;border:0px"><b>'.$ch['color']. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$ch['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.')</b></td>
													</tr> 
													<tr>
														<td  style="padding:0px;border:0px">SIZE&nbsp;:&nbsp;</td>
														<td  style="padding:0px;border:0px">'.$ch['size'].' '.$ch['measurement'].'</td>
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
															<td  style="padding:0px;border:0px">'.$ch['net_weight'].' Kg</td>
														</tr>
														<tr>';
																if($invoice['final_destination'] == '170' || $invoice['final_destination'] == '253')
																{
																	if($invoice['final_destination'] == '170')
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
																else
																{
																	$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																	$setHtml .='<td  style="padding:0px;border:0px">'.$ch['buyers_o_no'].'</td>';
																}
													
												
												//if(ucwords(decode($invoice['transportation']))=='sea')
												$setHtml .='</tr><tr>
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
												
												</table></td>
												';
																					
												}
								}
						
			$description='';
			$i++;
			if($i%2==0)
			{
				$setHtml .='</tr></table></div>';	
				
			}			
			
		}
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
							if($invoice['final_destination'] == '170' || $invoice['final_destination'] == '253')
							{
								if($invoice['final_destination'] == '170')
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
							<th style="width: 7%;">Gr. Wt in kgs</th>
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
		$sql="DELETE FROM in_gen_invoice WHERE in_gen_invoice_id='".$in_gen_invoice_id."' OR parent_id='".$in_gen_invoice_id."'";
		//echo 
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
		$setHtml='';
		foreach($total_pallet as $pallet)
		{
			$invoice=$this->getInvoiceNetData($invoice_no);
			if($invoice['final_destination']==170)
				$item_text='Special Code';
			else
				$item_text='Item No.';
			$description='';$valve='';$zipper_name='';$gross_weight='';
		
			$setHtml.='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">';
			$setHtml .= '<div class="table-responsive" >';
			$setHtml .='<table class="table b-t table-striped text-small table-hover" style="  border: 1px solid black;">';
			if($price==1)
				$colspan=10;
			else
				$colspan=8;
			$setHtml .=' <thead>
							<tr>
								<th colspan="'.$colspan.'" style="text-align:center;padding:0;"><h4>Pallet Sheet No. '.$pallet['pallet_no'].'</h4></th>
							</tr>
							<tr>
								<th style="width:35px">Box Nos.</th>  ';                    
								if($invoice['final_destination']==253)
									$setHtml.='<th style="width:35px">Buyer\'s Order No</th>';
								$setHtml.='<th style="245px" >Product</th>';
								$setHtml.='<th style="width:35px">'.$item_text.'</th>';
								if($invoice['final_destination']!=253)
									$setHtml.='<th style="width:35px">Size</th>';
								$setHtml.='<th style="width:35px">Quantity</th>
								<th style="width:35px">Gr. Wt in kgs</th>
								<th style="width:35px">Net. Wt in kgs</th>';
								if($price==1)
								{
									$setHtml.='<th style="width:35px">Rate</th>
									<th style="width:35px">Total Amount</th>';
								}
								if($invoice['final_destination']!=253)
									$setHtml.='<th style="width:35px">Buyer\'s Order No</th>';
								
			if($status==0)
			$setHtml .=	'<th style="width:35px">Action</th>';
				$setHtml .=	'			</tr>
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
				$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=0;
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
							if($ch['dimension']!='')
								$size.=' + '.$ch['dimension'];
							else
								$size.=' + '.$ch['size'].' '.$ch['measurement'];
							//$size.=' + '.$ch['size'].' '.$ch['measurement'];
							$qty.=' + '.$ch['genqty'];
						}
					}
					$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$color['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
			
					$gross_weight=$color['net_weight']+$color['box_weight'];
					$tot_qty=$color['genqty']+$tot_qty;
					$tot_gross_weight=$gross_weight+$tot_gross_weight;
					$tot_net_weight=$color['net_weight']+$tot_net_weight;
					$tot_rate=$color['rate']+$tot_rate;
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
					$setHtml .='<td >'.$box_no.'</td>';
					if($invoice['final_destination']==253)
						$setHtml .='<td>'.$color['buyers_o_no'].'</td>';
						if($invoice['final_destination']==253)
							$setHtml.='<td>'.$size.' '.$description.'</td>';
						else
							$setHtml .='<td>'.$description.'</td>';
						$setHtml.='<td>'.$color['item_no'].'</td>';
						if($invoice['final_destination']!=253)
								$setHtml.='<td>'.$size.'</td>';
								$setHtml.='<td>'.$qty.'</td>
								<td>'.$gross_weight.'</td>
								<td>'.$color['net_weight'].'</td>
								';
									if($price==1)
						{
							$setHtml.='<td>'.$color['rate'].'</td>
							<td >'.$qty*$color['rate'].'</td>';
						}
						if($invoice['final_destination']!=253)
							$setHtml .='<td>'.$color['buyers_o_no'].'</td>';
						if($status==0)
						{
							$setHtml .='<td ><a class="btn btn-danger btn-sm" id="'.$color['in_gen_invoice_id'].'" href="javascript:void(0);">
								<i class="fa fa-trash-o"></i></a>
								<a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" id="addmore" data-original-title="Add Box Detail" onclick="add_box('.$invoice_no.','.$i.','.$color['in_gen_invoice_id'].','.$color['box_weight'].')"><i class="fa fa-plus"></i></a>
							</td>';
						}
					$setHtml .='</tr>';
					$description='';
					$i++;				
				}		
			}
			$setHtml.='</tbody></table></div><br><br></form>';
		}
		return $setHtml;				
	}
		
	function viewDetails($invoice_no,$status=0,$price=0)
	{
	$setHtml='';
		$invoice=$this->getInvoiceNetData($invoice_no);
		//printr($invoice);
		if($invoice['final_destination']==170)
			$item_text='Special Code';
		else
			$item_text='Item No.';
		$total_box=$this->colordetails($invoice_no,0,'');
		////printr($total_box);//die;
		if(isset($total_box) && !empty($total_box))
		{
			$tot_inv=count($total_box);
			$per_page=15;
			$total_pages=$tot_inv/$per_page;$setHtml='';
			//echo $total_pages;
				for($page_no=0;$page_no<$total_pages;$page_no++)
				{
					$start=$per_page*$page_no;
					//echo $start;
					$end=$start+$per_page;
					//echo $end;
					if($tot_inv>15)
					{
					if($page_no==0)
						$limit=' LIMIT 15 OFFSET 0';
					else
						$limit=' LIMIT 15 OFFSET  '.$start;
					//echo $start;
				}else
				$limit='';
			$description='';$valve='';$zipper_name='';$gross_weight='';
		
			$setHtml.='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">';
				
			$setHtml .= '<div class="table-responsive"  style="width:730px;">';
			if($page_no>0)
				$setHtml .= '<br ><br><br><div style="width:730px;">';
			else
				$setHtml .= '<div style="width:730px;">';
			$setHtml .='<table class="table b-t table-striped text-small table-hover" style="width:730px;">';
			if($price==1)
				$colspan=10;
			else
				$colspan=8;
			$setHtml .=' <thead>
							<tr>
								<th colspan="'.$colspan.'" style="text-align:center;padding:0;"><h4>Detailed Packing List '.($page_no+1).'</h4></th>
							</tr>
							<tr>
								<th style="width:35px">Box Nos.</th>  ';                    
								if($invoice['final_destination']==253)
									$setHtml.='<th style="width:35px">Buyer\'s Order No</th>';
								$setHtml.='<th style="245px" >Product</th>';
								if($invoice['final_destination']!=170)
								{
									$setHtml.='<th style="width:35px">'.$item_text.'</th>';
								}
								if($invoice['final_destination']!=253)
								$setHtml.='<th style="width:35px">Size</th>';
								$setHtml.='<th style="width:35px">Quantity</th>
								<th style="width:35px">Gr. Wt in kgs</th>
								<th style="width:35px">Net. Wt in kgs</th>';
								if($invoice['final_destination']==170)
								{
									$setHtml.='<th style="width:35px">invoice NUMBER</th>';
									$setHtml.='<th style="width:35px">'.$item_text.'</th>';
								}
								if($price==1)
								{
									$setHtml.='<th style="width:35px">Rate</th>
									<th style="width:35px">Total Amount</th>';
								}
								if($invoice['final_destination']!=253)
									$setHtml.='<th style="width:35px">Buyer\'s Order No</th>';
								
			if($status==0)
			$setHtml .=	'<th style="width:35px">Action</th>';
				$setHtml .=	'</tr>
						</thead>
						<tbody>';
			$parent_id=0;
			 
			$colordetails=$this->colordetails($invoice_no,$parent_id,'','',$limit);
			//printr($colordetails);
		//	die;	
			$i=1;
			$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=0;
			if(isset($colordetails) && !empty($colordetails))
			{	//printr($colordetails);
				$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=0;
				foreach($colordetails as $key=>$color)
				{
					//echo count($i);
					//printr($color);
					$zipper=$this->getZipper(decode($color['zipper']));
					//if($zipper['zipper_name']!='No zip')
						$zipper_name=$zipper['zipper_name'];
					// if($color['valve']!='No Valve')
						$valve=$color['valve'];
					$childBox=$this->colordetails($invoice_no,$color['in_gen_invoice_id']);
					
					$c_name=$color['color'];
					if($color['pouch_color_id'] == '-1')
					{
						$c_name = $color['color_text'];
					}
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
							if($ch['dimension']!='')
								$size.=' + '.$ch['dimension'];
							else
								$size.=' + '.$ch['size'].' '.$ch['measurement'];
							//$size.=' + '.$ch['size'].' '.$ch['measurement'];
							$qty.=' + '.$ch['genqty'];
							$tot_qty=$tot_qty+$ch['genqty'];
						}
					}
					$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$color['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;			
					$gross_weight=$color['net_weight']+$color['box_weight'];
					$tot_qty=$color['genqty']+$tot_qty;
					$tot_gross_weight=$gross_weight+$tot_gross_weight;
					$tot_net_weight=$color['net_weight']+$tot_net_weight;
					$tot_rate=$color['rate']+$tot_rate;
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
					//printr($invoice);
					//$cnt = sizeof($invoice).'invoice';
					//echo $cnt;
					if($status==0)
					{
						
						$setHtml .='<td ><input type="text" class="form-control  validate[required]"  name="gen_id'.$i.'_page_no'.($page_no+1).'"  
						onblur="edit_box_no('.$i.','.($page_no+1).')" id="gen_id'.$i.'_page_no'.($page_no+1).'"
						 value="'.$box_no.'"  />						 
						 <input type="hidden"  name="gen_unique_id'.$i.'_page_no'.($page_no+1).'" id="gen_unique_id'.$i.'_page_no'.($page_no+1).'"
						 value="'.$color['in_gen_invoice_id'].'"  />
						 </td>';
						 						
					}
					else
					$setHtml .='<td >'.$box_no.'</td>';
					$end=$box_no;
					if($invoice['final_destination']==253)
						$setHtml .='<td>'.$color['buyers_o_no'].'</td>';
						if($invoice['final_destination']==253)
							$setHtml.='<td>'.$size.' '.$description.'</td>';
						else
							$setHtml .='<td>'.$description.'</td>';
						if($invoice['final_destination']!=170)
								$setHtml.='<td>'.$color['item_no'].'</td>';
						if($invoice['final_destination']!=253)
								$setHtml.='<td>'.$size.'</td>';
								$setHtml.='<td>'.$qty.'</td>
								<td>'.number_format($gross_weight,3).'</td>
								<td>'.$color['net_weight'].'</td>
								';
								if($invoice['final_destination']=='170')
								{
									//printr(count($colordetails));
									$row='';
									if($i == '1')
									{
										$row = 'rowspan="'.count($colordetails).'"';
										$setHtml.='<td '.$row.' style="vertical-align: middle;"><b>Invoice No.'.$invoice['invoice_no'].'/'.(date('y')).'-'.(date('y')+1).'</b></td>';
									}
									//$setHtml.='<td '.$row.' style="vertical-align: middle";><b>Invoice No.'.$invoice['invoice_no'].'/'.(date('y')).'-'.(date('y')+1).'</b></td>';	
									$setHtml.='<td>'.$color['item_no'].'</td>';
								}
									if($price==1)
									{
										$setHtml.='<td style="float:right">'.number_format($color['rate'],2).'</td>
										<td style="text-align:right">'.number_format($qty*$color['rate'],2).'</td>';
									}
						if($invoice['final_destination']!=253)
							$setHtml .='<td>'.$color['buyers_o_no'].'</td>';
						if($status==0)
						{
							$setHtml .='<td ><a class="btn btn-danger btn-sm" id="'.$color['in_gen_invoice_id'].'" href="javascript:void(0);">
								<i class="fa fa-trash-o"></i></a>
								<a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" id="addmore" data-original-title="Add Box Detail" onclick="add_box('.$invoice_no.','.$i.','.$color['in_gen_invoice_id'].','.$color['box_weight'].')"><i class="fa fa-plus"></i></a>
								</td>';
						}
					
						$setHtml .='</tr>';
					$description='';
					$i++;	
				}
		
			$setHtml.='<tr><td colspan="'.$colspan.'">&nbsp;</td></tr>
					   <tr>
						   <td></td>';
				if($invoice['final_destination']==253)
				  $setHtml.='<td></td>';						 
					$setHtml.='<td><strong>Total</strong></td>';				
					$setHtml.='<td></td>';	
					if($invoice['final_destination']!=253)
					$setHtml.='<td></td>';				  
				   $setHtml.='<td><strong>'.$tot_qty.'</strong></td>
				   <td><strong>'.$tot_gross_weight.'</strong></td>
				   <td><strong>'.$tot_net_weight.'</strong></td>
					';
			if($price==1)
			{
				$setHtml.= '<td style="float:right"><strong>'.$tot_rate.'</strong></td>
						   <td style="text-align:right"><strong>'.number_format($tot_amt,2).'</strong></td>';
			}
			   if($invoice['final_destination']!=253)
				$setHtml.='<td></td>';		
				if($status==0)		
					$setHtml.='<td></td>';
				$setHtml.='</tr>';
			}
			$collapse_data[]=array('box_no'=>($start+1).' To '.$end,
									'page_no'=>($page_no+1),
									'qty'=>$tot_qty,
									'gross_weight'=>$tot_gross_weight,
									'net_weight'=>$tot_net_weight,									
			);
			$setHtml.='</tbody></table></div></div></form>';
			}
			//$i++;
		}
	//	printr($collapse_data);
		$setHtml.='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data"><div class="table-responsive"><br><br><br style="page-break-before: always;"><div style="width:730px;"><table class="table b-t table-striped text-small table-hover"> <thead>							
							<tr>
								<th style="width:150px">Box Nos.</th>  
								<th style="150px">Page No.</th>
								<th style="width:100px">Quantity</th>
								<th style="width:100px">Gr. Wt in kgs</th>
								<th style="width:100px">Net. Wt in kgs</th>
							</tr>
						</thead>
						<tbody>';
	$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;
	if(isset($collapse_data))
	{
		foreach($collapse_data as $dat)
		{
			$setHtml.='<tr>
							<td>'.$dat['box_no'].'</td>
							<td>'.$dat['page_no'].'</td>
							<td>'.$dat['qty'].'</td>
							<td>'.$dat['gross_weight'].'</td>
							<td>'.$dat['net_weight'].'</td>
						</tr>
						';
			$tot_qty=$tot_qty+$dat['qty'];
			$tot_gross_weight=$tot_gross_weight+$dat['gross_weight'];
			$tot_net_weight=$tot_net_weight+$dat['net_weight'];
		}
	}
		$setHtml.='<tr>
						<td></td>
						<td></td>
						<th>'.$tot_qty.'</th>
						<th>'.$tot_gross_weight.'</th>
						<th>'.$tot_net_weight.'</th>
					</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>';
		return $setHtml;				
	}
	
	function viewConsolidatedSheet($invoice_no)
	{
		$consol_data=$this->consol_list($invoice_no);
		$setHtml='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
		<div class="table-responsive">
			<div style="width:730px;">
				<table class="table b-t table-striped text-small table-hover"> 
					<thead>		
						<tr>
							<th colspan="8" style="text-align:center;padding:0;"><h4>Consolidated Item Sheet</h4></th>
						</tr>					
							<tr>
								<th style="width:100px">Box Nos.</th>  
								<th style="width:50px">Total Boxes</th>
								<th style="width:200px">Description</th>
								<th style="width:50px">Item No</th>
								<th style="width:50px">Quantity (NOS.)</th>
								<th style="width:100px">Gross Weight (kgs)</th>
								<th style="width:100px">Net Weight (kgs)</th>
								<th style="width:50px">Extended Cost USD</th>
							</tr>
						</thead>
						<tbody>';
	$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_rate=0;
		foreach($consol_data as $dat)
		{	//printr($dat);
			$zipper=$this->getZipper(decode($dat['zipper']));
			$zipper_name=$zipper['zipper_name'];
			$valve=$dat['valve'];
			$c_name=$dat['color'];
			$size=$dat['size'];
			$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$dat['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;			
			$total_box_arr=explode(',',$dat['grouped_box_no']);
			$first = reset($total_box_arr);
   			$last = end($total_box_arr);

			$setHtml.='<tr>
							<td>'.$first.' To '.$last.'</td>
							<td>'.$dat['total_boxes'].'</td>
							<td>'.$description.'</td>
							<td>'.$dat['item_no'].'</td>
							<td>'.$dat['qty'].'</td>
							<td>'.$dat['gross_weight'].'</td>
							<td>'.$dat['net_weight'].'</td>
							<td>'.$dat['rate'].'</td>
						</tr>
						';
			$tot_qty=$tot_qty+$dat['qty'];
			$tot_gross_weight=$tot_gross_weight+$dat['gross_weight'];
			$tot_net_weight=$tot_net_weight+$dat['net_weight'];
			$tot_rate=$tot_rate+$dat['rate'];
		}
		$setHtml.='<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<th>'.$tot_qty.'</th>
						<th>'.$tot_gross_weight.'</th>
						<th>'.$tot_net_weight.'</th>
						<th>'.$tot_rate.'</th>
					</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>';
		$setHtml.='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
		<div class="table-responsive">
			<div style="width:730px;">
				<table class="table b-t table-striped text-small table-hover"> 
					<thead>		
							<tr>
								<th style="width:100px"></th>  
								<th style="width:50px"></th>
								<th style="width:200px"></th>
								<th style="width:50px"></th>
								<th style="width:50px">Quantity (NOS.)</th>
								<th style="width:100px">Gross Weight (kgs)</th>
								<th style="width:100px">Net Weight (kgs)</th>
								<th style="width:50px">Extended Cost USD</th>
							</tr>
						</thead>
						<tbody>';
		$setHtml.='<tr>
						<td></td>
						<td></td>
						<th>Total</th>
						<td></td>
						<th>'.$tot_qty.'</th>
						<th>'.$tot_gross_weight.'</th>
						<th>'.$tot_net_weight.'</th>
						<th>'.$tot_rate.'</th>
					</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>';
		return $setHtml;			
	}
	
	function viewAddress($invoice,$status='')
	{	
		$setHtml='';
		$setHtml .= '<table class="table" border="1" style=" font-size: 10px; width:700px;height:1084px">';
			$fixdata = $this->getFixmaster();
			$i = 1;
//	$setHtml .= '<tr >';
			$transportation=decode($invoice['transportation']);
			if($transportation=='sea')
			{
				$incval=2;
			}
			else
			{	
				$incval=6;
			}
					while($i <= $incval) {
						if($incval==6)
						{
							if($i%2!=0)
							$setHtml .= '<tr>';
						}
						else
							$setHtml .= '<tr>';
						 $setHtml .= '<td style="border:none;width:370px">
    										<h4><u>EXPORTER:-</u></h4>	
        									'.nl2br($fixdata['exporter']).'
                          					<h4><u>CONSIGNEE:-</u> </h4>
        									<b>'.$invoice['customer_name'].'</b><br>'.nl2br($invoice['consignee']).'<br><br><span><img src="http://www.bcgen.com/demo/linear-dbgs.aspx?D='.$invoice['invoice_no'].'"></span>
										</td>';
									if($incval==6)
									{	
										if($i%2==0)										
									 	$setHtml .= '</tr>';
									}
									else
									{
										$setHtml .= '</tr>';
									}
							$i++;
							}
  $setHtml .= '</tr>';
		$setHtml.='</table>';
		return($setHtml);
	}
	
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
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "invoice as inv,country as c WHERE c.country_id=inv.final_destination AND inv.is_delete = 0  AND inv.date_added='".date("Y-m-d")."' AND done_status='0'";
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
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "invoice as inv,country as c WHERE inv.user_id = '".(int)$set_user_id."' AND inv.user_type_id = '".(int)$set_user_type_id."'AND inv.is_delete = 0 $str AND c.country_id=inv.final_destination   AND inv.date_added='".date("Y-m-d")."' AND done_status='0' ";
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
		}
		$sql .=' GROUP BY invoice_id ';
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " .$data['sort'];	
		} else {
			$sql .= " ORDER BY invoice_no";	
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
		//printr($sql);
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
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice` WHERE is_delete = 0 AND date_added='".date("Y-m-d")."' AND done_status='0'";
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
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice` WHERE is_delete = 0 AND user_id = '".(int)$set_user_id."' AND user_type_id = '".(int)$set_user_type_id."' $str AND date_added='".date("Y-m-d")."' AND done_status='0' ";
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
		}
//printr($sql);
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function updateInvoiceStatus($status,$data)
	{
		if($status == 0 || $status == 1){
			$sql = "UPDATE " . DB_PREFIX . "invoice SET status = '" .(int)$status. "',  date_modify = NOW() WHERE invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE " . DB_PREFIX . "invoice SET is_delete = '1', date_modify = NOW() WHERE invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateInvoice($invoice_no,$status_value)
	{
		$sql = "UPDATE " . DB_PREFIX . "invoice SET status = '".$status_value."', date_modify = NOW()  WHERE invoice_no = '" .(int)$invoice_no. "'";
		$this->query($sql);
	}
	
	public function getInvoiceProduct($invoice_id)
	{
		$sql = "SELECT ip.*,p.product_name FROM `" . DB_PREFIX . "invoice_product` as ip,product p WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0 AND ip.product_id=p.product_id";
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
	
	public function getInvoiceColorlable($invoice_color_id)
	{
		$sql ="SELECT * FROM invoice_color AS ic LEFT JOIN  invoice_product AS ip ON (ic.invoice_product_id=ip.invoice_product_id)  WHERE ic.invoice_color_id=".$invoice_color_id."";
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
	
	public function getCustomOrder($cust_cond='',$getData = '*',$user_type_id='',$user_id=''){
		$date = date("d-m-Y");
		//$date ='24-09-2016'; 
		$date_cond = '%"currdate":"'.$date.'"%';
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN multi_custom_order_id as mcoi ON(mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE (".$cust_cond.") AND mco.dispach_by LIKE '".$date_cond."' ";
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
				$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN " . DB_PREFIX ."multi_custom_order_id mcoi ON (mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE  (".$cust_cond.") AND (mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str AND mco.dispach_by LIKE '".$date_cond."'";
				//echo $str;
			}
		}else{
			$sql = "SELECT $getData,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode FROM " . DB_PREFIX ."multi_custom_order mco,multi_custom_order_id mcoi,address adr WHERE
			 mco.multi_custom_order_id=mcoi.multi_custom_order_id  AND (".$cust_cond.") AND mcoi.shipping_address_id=adr.address_id AND mco.dispach_by LIKE '".$date_cond."'";
		}
		//echo $sql;
		//die;
		$data = $this->query($sql);
		return $data->rows;
	}
	
	public function getCustomOrderQuantity($custom_order_id){
		$paking_price=$this->getCustomOrderPackingAndTransportDetails($custom_order_id);
		$data = $this->query("SELECT mco.custom_order_id,mco.multi_custom_order_id,mco.currency_price,mco.status,mco.shipment_country_id,mco.currency,mco.custom_order_status,mco.custom_order_type,mcoq.custom_order_quantity_id,mco.product_id,p.product_name, mcoq.custom_order_id,mcoq.discount, printing_effect,mcoq.quantity,mco.height,mco.width,mco.gusset,mco.volume,mco.dis_qty, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mco.layer,total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage FROM " . DB_PREFIX ."multi_custom_order_quantity as mcoq,multi_custom_order as mco,product as p WHERE (".$custom_order_id.") AND mcoq.custom_order_id=mco.custom_order_id AND mco.product_id=p.product_id ORDER BY mcoq.custom_order_quantity_id") ;
		$return = '';
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				$zdata = $this->query("SELECT custom_order_price_id, custom_order_id, custom_order_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt, total_price,total_price_with_excies,total_price_with_tax,tax_name,tax_type,tax_percentage,excies, gress_price, customer_gress_price,zipper_price,valve_price,courier_charge,spout_base_price,accessorie_base_price,transport_price,make_name,make_pouch FROM " . DB_PREFIX ."multi_custom_order_price as mcop ,product_make as pm WHERE custom_order_id = '".(int)$qunttData['custom_order_id']."' AND custom_order_quantity_id = '".(int)$qunttData['custom_order_quantity_id']."' AND mcop.make_pouch=pm.make_id ORDER BY transport_type");	
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
							
						);
						
						$txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
						$txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
						/*if($zipData['spout_txt']=='No Spout')
							$zipData['spout_txt']='';
						if($zipData['accessorie_txt']=='No Accessorie')
							$zipData['accessorie_txt']='';*/
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
		$data = $this->query("SELECT stock_order_id,client_id FROM " . DB_PREFIX ." stock_order WHERE gen_order_id = '".$stock_order_number."'");
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
		$data = $this->query("SELECT stock_order_id,client_id FROM " . DB_PREFIX ."stock_order WHERE gen_order_id IN (".$result.")");
		
		//$data = $this->query("SELECT stock_order_id,client_id FROM " . DB_PREFIX ." stock_order WHERE gen_order_id = '".$stock_order_number."'");
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
			return false;
	}
	public function GetStockOrderList($user_id,$usertypeid,$status='',$client_id='',$stock_order_id='',$sodh_client,$sodh_cond,$temp='')
	{
		//printr($status);
		//die; 
		 $menu_id = $this->getMenuPermission(ORDER_ACCEPT_ID,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		 //printr($menu_id);
		$admin = '';
		if($status=='')
		$status ='AND pto.order_id = t.product_template_order_id';
		if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'  ";
			$dataadmin = $this->query($sqladmin);
			$cond = 'AND pto.admin_user_id = '. $dataadmin->row['user_id'].'';
			$admin_user_id =  $dataadmin->row['user_id'];
			$table= 'employee as ib ,';
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$cond = 'AND pto.admin_user_id = '. $_SESSION['ADMIN_LOGIN_SWISS'].' ';
			//$cond = '';
			$admin_user_id = $user_id;
			$table= 'international_branch as ib , ';
		}
		else
		{
			$cond = ' ';
			$table = ' ';
			$admin_user_id ='';
			$page=0;
		}
		if(($menu_id OR $_SESSION['LOGIN_USER_TYPE']==1 ) OR $data!=2)
		{
			//$con='';
			$cond = ' ';
			$table = ' ';
		}
		if($page==1)
		{
			$admin = 'AND pto.admin_user_id="'.$admin_user_id.'"';
		}
		
		$stock_cond='';
		$curr_d=date("d-m-Y");
		//$curr_d='24-09-2016';
		//echo 'sdfsdf'.$stock_order_id;
		$date_cond='';
		if($stock_order_id!='')
		{
			$date_cond = '%"currdate":"'.$curr_d.'"%';
			$stock_cond=" AND (".$sodh_cond.") AND (sodh.template_order_id = t.template_order_id AND sodh.product_template_order_id=t.product_template_order_id AND sodh.status=0) AND sodh.dispach_by LIKE '".$date_cond."' ";
		}
		
		if($temp!='')
			$stock_cond =" AND (".$temp.") AND (sodh.template_order_id = t.template_order_id AND sodh.product_template_order_id=t.product_template_order_id AND sodh.status=0) AND sodh.dispach_by LIKE '".$date_cond."' ";
		
			
		if($client_id!='')
		{
			//$client_id=" AND t.client_id = '".$client_id."' AND ";
			$client_id=" AND (".$sodh_client.") AND ";
		}
		else
			$client_id =" AND ";
		
		///echo $client_id;$date = date("Y-m-d");
		$sql = "SELECT sodh.stock_order_dispatch_history_id,sodh.dis_qty,so.gen_order_id,t.client_id,t.expected_ddate,t.template_order_id,t.quantity,p.product_id,p.email_product,p.product_name,pt.title,pts.width,pts.height,pts.gusset,pto.shipment_country,pto.ship_type as pto_ship_type,pto.admin_user_id,t.transport,pc.pouch_color_id,
pts.volume,t.note,c.country_name,cu.currency_code,pc.email_color,pc.color,t.user_id,t.user_type_id,t.price,pts.valve,cd.client_name,pts.product_template_size_id,pts.zipper,pts.spout,pts.accessorie,pts.description,t.ship_type,pt.product_template_id,t.address,t.date_added,pt.transportation_type,t.product_template_order_id,sos.review,sos.track_id,sos.date,sos.courier_id,pto.order_id,sos.process_by,sos.dispach_by,pts.quantity1000,pts.quantity2000,pts.quantity5000,pts.quantity10000,t.price_uk FROM " .DB_PREFIX . "stock_order_dispatch_history as sodh,template_order t,currency as cu,product as p,product_template pt,product_template_size as pts,country as c,pouch_color as pc, product_template_order as pto,stock_order_status as sos, courier as co,client_details as cd,stock_order as so  WHERE  t.product_id = p.product_id AND pt.product_template_id = t.template_id AND so.stock_order_id=t.stock_order_id AND so.client_id=t.client_id AND pto.admin_user_id=so.admin_user_id AND  t.template_order_id=sos.template_order_id  ".$status." AND pt.currency = cu.currency_id AND t.is_delete = 0  ".$cond." 
".$client_id."  t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color AND t.country=c.country_id AND pto.order_id = t.product_template_order_id ".$admin." AND  t.is_delete = 0 AND t.client_id=cd.client_id".$stock_cond;	
	
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
		$sql .= " GROUP BY t.template_order_id"; 
	//	$sql .= " GROUP BY st.template_order_id"; 
		//echo $sql;
		//die;
		if (isset($data['sort'])) {
		$sql .= " ORDER BY " . $data['sort'];	
		} else {
		$sql .= " ORDER BY t.template_order_id";	
		}
		if (isset($data['order']) && ($data['order'] == 'ASC')) {
		$sql .= " ASC";
		} else {
		$sql .= " DESC";
		}
		//echo $sql;
		//echo '<br>';
		//die;
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
			$data['start'] = 0;
			}			
			if ($data['limit'] < 1) {
			$data['limit'] = 20;
			}	
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo '<br>'.$sql;//die;
		$data = $this->query($sql);
		//printr($data->rows);
		if($data->num_rows)
		{
			//echo $con;
			foreach($data->rows as $data)
			{
				
				$stock_data = array($data);
				
				$stock[$data['transport']][$data['template_order_id']] = $stock_data;
			}
			
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

	public function userOrderData($user_id,$user_type_id)
	{
		$date = date("d-m-Y");
		//$date = '24-09-2016';
		$date_cond = '%"currdate":"'.$date.'"%';
		if($user_type_id==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'";
			$dataadmin = $this->query($sqladmin);
			$con =  'AND st.admin_user_id = "'.$dataadmin->row['user_id'].'"';
			$cust_con =  'AND mcoi.admin_user_id = "'.$dataadmin->row['user_id'].'"';
		}
		elseif($user_type_id==4)
		{
			$con =  "AND st.admin_user_id = '".$user_id."'";
			$cust_con =  "AND mcoi.admin_user_id = '".$user_id."'";
		}
		
		$stock_sql = "SELECT st.gen_order_id as order_num FROM template_order t,stock_order st,stock_order_dispatch_history as sodh WHERE t.is_delete = 0 AND ( (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=0)) AND t.status=1 AND st.stock_order_id=t.stock_order_id AND st.client_id=t.client_id ".$con." AND sodh.dispach_by LIKE '".$date_cond."' GROUP BY st.stock_order_id, st.admin_user_id";
		$stock_data = $this->query($stock_sql);
		
		$cust_sql = "SELECT mcoi.multi_custom_order_number as order_num FROM multi_custom_order mco,multi_custom_order_id as mcoi WHERE mco.multi_custom_order_id=mcoi.multi_custom_order_id ".$cust_con." AND mco.dispach_by LIKE '".$date_cond."' GROUP BY mcoi.multi_custom_order_id, mcoi.admin_user_id";
		$cust_data = $this->query($cust_sql);
		$custom_imp = '';
		$stock_imp='';
		$custom_ar='';
		if($cust_data->num_rows)
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
							  'custom_order_no' => $custom_imp);
				return $o_no;
		}
		else
		{
			if(!empty($cust_data))
			{
				$o_no = array('stock_order_no' => '',
							  'custom_order_no' => $custom_imp);
				return $o_no;
			}
			else
				return false;
		}
	
	}
	public function getLastIdInvoice() {
		$sql = "SELECT invoice_id FROM invoice ORDER BY invoice_id DESC LIMIT 1";
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
		//printr($data);
		//die;
		$template_order_id_arr = $data['template_order_id'];
		$multi_custom_order_id_arr= $data['multi_custom_order_id'];
		
		$order_by=explode("=",$data['order_by']);
		$user_detail = $this->getUser($order_by[1],$order_by[0]);
		
		$curr_id = $this->getUserCurrencyByUser($user_detail['default_curr']);
		
		$userCountry = $this->getUserCountry($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
		if($userCountry){
			$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
		}else{
			$countryCode='IN';
		}	
		
		
		$LastInvoiceId = $this->getLastIdInvoice();
		$id=$LastInvoiceId['invoice_id']+1;
		$pur_id=str_pad($id,8,'0',STR_PAD_LEFT);
		$invoice_no=$countryCode.$pur_id;
		//echo $invoice_no;die;
		/*$sql = "INSERT INTO `" . DB_PREFIX . "invoice` SET invoice_no = '".$data['invoiceno']."',invoice_date = '" .date("Y-m-d"). "',exporter_orderno = '".$data['ref_no']."',gst='".$data['gst']."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."', buyers_orderno ='".$data['buyersno']."',other_ref ='".$data['other_ref']."',pre_carrier='".$data['pre_carrier']."',consignee='".addslashes($data['consignee'])."',buyer='".$data['other_buyer']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',port_load='".$data['portofload']."',port_discharge='".$data['port_of_dis']."',final_destination='".$data['country_id']."', taxation='".$taxation."', excies='".$excies."', tax='".$tax."', tax_mode ='".$data['tax_mode']."',tax_form ='".$form."' ,payment_terms='".$data['payment_terms']."',delivery='".$data['delivery']."',HS_CODE='".$data['hscode']."',pouch_type='".$data['printedpouches']."',pouch_desc='".$data['pouch_desc']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',cylinder_charges='".$data['cylinder_charges']."',container_no='".addslashes($data['container_no'])."',seal_no='".addslashes($data['seal_no'])."', remarks='".$data['remarks']."',postal_code = '".$data['postal_code']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',transportation='".encode($data['transport'])."',curr_id='".$data['currency']."',status = '".$data['status']."',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0";*/
		
		$trans = explode(' ',$data['trans']);
		
		
		
		$sql = "INSERT INTO `" . DB_PREFIX . "invoice` SET invoice_no = '".$invoice_no."',invoice_date = '".date("Y-m-d")."',buyers_orderno='".$data['uni_ids']."',consignee='".$user_detail['company_address']."',customer_name = '".$user_detail['company_name']."',email = '".$user_detail['email']."',port_discharge='".$data['ship_country']."',curr_id='".$curr_id."',HS_CODE='39232990',pre_carrier='3',port_load='3',transportation='".encode(strtolower($trans[1]))."',status = '1',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',final_destination='".$data['ship_country']."',country_destination='".$data['ship_country']."',is_delete=0";
		$datasql=$this->query($sql);
		$invoice_id = $this->getLastId();
		
		$inv_id=array();
		foreach($template_order_id_arr as $key=>$template_order_id)
		{
			
			/*$sql2 = "Insert into invoice_product Set invoice_id='".$invoice_id."',product_id='".$data['product']."', valve = '".$data['valve']."', zipper = '".$data['zipper']."', spout = '".$data['spout']."',make_pouch='".$data['make']."', accessorie = '".$data['accessorie']."',net_weight='".$data['netweight']."',measurement_two='".$data['measurement']."',buyers_o_no='".$data['buyers_o_no']."',item_no='".$data['item_no']."', date_added = NOW(), date_modify = NOW(), is_delete = 0"; */
			$sql2 = "Insert into invoice_product Set invoice_id='".$invoice_id."',product_id='".$data['product_id_'.$template_order_id]."', valve = '".$data['valve_id_'.$template_order_id]."', zipper = '".encode($data['zipper_id_'.$template_order_id])."', spout = '".encode($data['spout_id_'.$template_order_id])."',make_pouch='".$data['make_id_'.$template_order_id]."', accessorie = '".encode($data['accessorie_id_'.$template_order_id])."',measurement_two='1',date_added = NOW(), date_modify = NOW(), is_delete = 0";
			$data2=$this->query($sql2);
			$invoice_product_id = $this->getLastId();

			/*$sql3 = "Insert into invoice_color Set invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$color['color']."',color_text='".$clr_txt."', rate ='".$color['rate']."', qty = '".$color['qty']."', size = '".$color['size']."',  measurement = '".$color['measurement']."',dimension='".$dim."',net_weight='".$color['net_weight']."',date_added = NOW(), date_modify = NOW(), is_delete = 0";*/
			$sql3 = "Insert into invoice_color Set invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$data['pouch_color_id_'.$template_order_id]."', rate ='".$data['rate_'.$template_order_id]."', qty = '".$data['dis_qty_'.$template_order_id]."', size = '".$data['size_'.$template_order_id]."',  measurement = '".$data['mea_'.$template_order_id]."',dimension='".$data['dimension_'.$template_order_id]."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 			
			
			$data3=$this->query($sql3);
			$invoice_color_id = $this->getLastId();
			$inv_id[]=$invoice_id."==".$invoice_product_id."==".$invoice_color_id;
		}
		
		foreach($multi_custom_order_id_arr as $key=>$multi_custom_order_id)
		{
			$cust_array = explode("==",$multi_custom_order_id);
			$multi_custom_order_id_1 = $cust_array[0];
			$custom_order_id = $cust_array[1];
			
			$sql4 = "Insert into invoice_product Set invoice_id='".$invoice_id."',product_id='".$data['cust_product_id_'.$custom_order_id]."', valve = '".$data['cust_valve_id_'.$custom_order_id]."', zipper = '".encode($data['cust_zipper_id_'.$custom_order_id])."', spout = '".encode($data['cust_spout_id_'.$custom_order_id])."',make_pouch='".$data['cust_make_id_'.$custom_order_id]."', accessorie = '".encode($data['cust_accessorie_id_'.$custom_order_id])."',measurement_two='1',date_added = NOW(), date_modify = NOW(), is_delete = 0";
			$data4=$this->query($sql4);
			$cust_invoice_product_id = $this->getLastId();
			
			$sql5 = "Insert into invoice_color Set invoice_id='".$invoice_id."',invoice_product_id='".$cust_invoice_product_id."',color = '".$data['cust_pouch_color_id_'.$custom_order_id]."', rate ='".$data['cust_rate_'.$custom_order_id]."', qty = '".$data['cust_dis_qty_'.$custom_order_id]."', size = '".$data['cust_size_'.$custom_order_id]."',  measurement = '".$data['cust_mea_'.$custom_order_id]."',dimension='".$data['cust_dimension_'.$custom_order_id]."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 	
			$data3=$this->query($sql5);
			$cust_invoice_color_id = $this->getLastId();
			$inv_id[]=$invoice_id."==".$cust_invoice_product_id."==".$cust_invoice_color_id;
		}
		
		return $inv_id;
	}
	
	public function getDispatchQty($template_order_id,$product_template_order_id)
	{
		
		$date = date("Y-m-d");
		//$date = '2016-09-24';
		//echo "SELECT sum(dis_qty) as total_dis_qty FROM stock_order_dispatch_history WHERE template_order_id='".$template_order_id."' AND product_template_order_id='".$product_template_order_id."'  AND dis_date = '".$date."'";
		$data=$this->query("SELECT sum(dis_qty) as total_dis_qty FROM stock_order_dispatch_history WHERE template_order_id='".$template_order_id."' AND product_template_order_id='".$product_template_order_id."' AND dis_date = '".$date."' ");
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
		
		
	}
	
	public function addBoxDetail($post)
	{
		//savelabeldetail
		//printr($post);
		//die;
		$template_order_id_arr = $post['template_order_id'];
		$multi_custom_order_id_arr= $post['multi_custom_order_id'];
		
		foreach($template_order_id_arr as $key=>$template_order_id)
		{
			$stock = explode("==",$post['stk_invoice_id'.$template_order_id]);
			
			$sql="UPDATE invoice_product SET net_weight='".$post['netweight_'.$template_order_id]."' WHERE invoice_product_id ='".$stock[1]."'" ;
			$this->query($sql);
			
			$sql2="UPDATE invoice_color SET net_weight='".$post['netweight_'.$template_order_id]."' WHERE invoice_color_id ='".$stock[2]."'" ;
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
		
		foreach($multi_custom_order_id_arr as $key=>$multi_custom_order_id)
		{
			$cust_array = explode("==",$multi_custom_order_id);
			$multi_custom_order_id_1 = $cust_array[0];
			$custom_order_id = $cust_array[1];			
			
			$cust = explode("==",$post['cust_invoice_id'.$custom_order_id]);
			
			$sql="UPDATE invoice_product SET net_weight='".$post['netweight_cust_'.$custom_order_id]."' WHERE invoice_product_id ='".$cust[1]."'" ;
			$this->query($sql);
			
			$sql2="UPDATE invoice_color SET net_weight='".$post['netweight_cust_'.$custom_order_id]."',measurement='1' WHERE invoice_color_id ='".$cust[2]."'" ;
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
	
	public function getUserCurrencyByUser($curr_id)
	{
		$sql="SELECT cu.currency_id FROM country as co, currency as cu WHERE co.country_id='".$curr_id."' AND cu.currency_code=co.currency_code";
		$data = $this->query($sql);
		return $data->row['currency_id'];	
	}
	public function alldone($invoice_id)
	{
		
			$sql = "UPDATE `" . DB_PREFIX . "invoice` SET done_status='1' WHERE invoice_id = '".$invoice_id."'";	
			$data = $this->query($sql);
		
		
	}
}
?>