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
		exporter_orderno = '".$data['ref_no']."',gst='".$data['gst']."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."', buyers_orderno ='".$data['buyersno']."',other_ref ='".$data['other_ref']."',pre_carrier='".$data['pre_carrier']."',consignee='".addslashes($data['consignee'])."',buyer='".$data['other_buyer']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',port_load='".$data['portofload']."',port_discharge='".$data['port_of_dis']."',final_destination='".$data['country_id']."', taxation='".$taxation."', excies='".$excies."', tax='".$tax."', tax_mode ='".$data['tax_mode']."',tax_form ='".$form."' ,payment_terms='".$data['payment_terms']."',delivery='".$data['delivery']."',HS_CODE='".$data['hscode']."',pouch_type='".$data['printedpouches']."',pouch_desc='".$data['pouch_desc']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',cylinder_charges='".$data['cylinder_charges']."',container_no='".addslashes($data['container_no'])."',seal_no='".addslashes($data['seal_no'])."', postal_code = '".$data['postal_code']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',transportation='".encode($data['transport'])."',curr_id='".$data['currency']."',
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
		
		$sql ="SELECT * FROM invoice AS i LEFT JOIN invoice_product AS ip ON (i.invoice_id=ip.invoice_id) LEFT JOIN invoice_color AS ic ON (i.invoice_id=ic.invoice_id AND ip.invoice_product_id=ic.invoice_product_id) LEFT JOIN box_master AS bm ON (bm.pouch_volume = ic.size AND bm.pouch_volume_type=ic.measurement AND bm.product_id = ip.product_id AND bm.zipper=ip.zipper AND bm.valve=ip.valve AND bm.spout=ip.spout AND bm.accessorie=ip.accessorie AND bm.make_pouch=ip.make_pouch AND bm.transportation=i.transportation AND bm.is_delete=0) WHERE i.invoice_id=".$invoice_id." ORDER BY ip.invoice_product_id ASC";
		
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
		AND i.is_delete=0 AND i.invoice_id=ic.invoice_id ";
		$data = $this->query($sql);
	//printr($sql);
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
		$sql = "select ic.*,tm.measurement,pc.color from `".DB_PREFIX."invoice_color` as ic,template_measurement as tm,pouch_color as pc WHERE invoice_id='".$invoice_id."' AND invoice_product_id = '".$invoice_product_id."' AND ic.measurement=tm.product_id AND ic.color=pc.pouch_color_id ";
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
		
		
		
		
		$sql="select ic.qty,ig.qty as genqty,ic.color,ic.dimension,ic.invoice_color_id,ic.color_text,ic.net_weight,ic.size,ic.measurement,ic.invoice_product_id,ic.product_name,ifnull(ic.qty-ig.qty,ic.qty)  as remaingqty from (select ii.dimension,ii.qty,ii.size,ii.color_text,tm.measurement,ii.invoice_product_id,p.product_name ,ii.invoice_color_id,ii.invoice_id,c.color,ii.net_weight from invoice_color as ii,pouch_color as c,template_measurement  as tm,invoice_product as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id) as ic left join (select sum(qty) as qty,invoice_color_id from in_gen_invoice GROUP BY invoice_color_id) as ig on (ic.invoice_color_id=ig.invoice_color_id) where ic.invoice_id='".$invoice_id."'";
		$data = $this->query($sql);
		
		//[kinjal] made for custom on 7-4-2017
		$color = $this->query("SELECT * FROM invoice_color WHERE invoice_id='".$invoice_id."' AND color='-1'");
		if($color->num_rows)
		{
			$sql1="select ic.qty,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.color_text,ic.net_weight,ic.size,ic.measurement,ic.invoice_product_id,ic.product_name,ifnull(ic.qty-ig.qty,ic.qty)  as remaingqty from (select ii.dimension,ii.qty,ii.size,ii.color_text,tm.measurement,ii.invoice_product_id,p.product_name ,ii.invoice_color_id,ii.invoice_id,ii.color,ii.net_weight from invoice_color as ii,template_measurement  as tm,invoice_product as ip,product as p where tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ii.color='-1' ) as ic left join (select sum(qty) as qty,invoice_color_id from in_gen_invoice GROUP BY invoice_color_id) as ig on (ic.invoice_color_id=ig.invoice_color_id) where ic.invoice_id='".$invoice_id."'";
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
		//printr($postdata);//die;
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
			//printr($sql.'<br>');
			//echo 'hi in for loop';
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
			$sql = "select ifnull(ic.qty-ig.genqty,ic.qty) as total,ic.qty,ig.genqty from (select sum(qty) as qty,invoice_color_id,invoice_id from invoice_color group by invoice_id ) as ic	left join (select  sum(qty) as genqty,invoice_color_id from  in_gen_invoice group by invoice_id) as ig on (  ig.invoice_color_id=ic.invoice_color_id)  WHERE ic.invoice_id='".$invoice_id."'";	
			//echo $sql;	
			$data = $this->query($sql);
			//printr($data);
			if($data->num_rows) {
				
				//[kinjal] made if condition for when i get genqty null on 9-12-2016
				if($data->row['genqty']=='')
				{
					$box_qty='select sum(qty) as genqty,invoice_color_id from  in_gen_invoice WHERE invoice_id="'.$invoice_id.'"group by invoice_id';
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
	{	//printr($invoice_id);
			//printr($parent_id);
			//printr($str);
	//if($parent_id==0)
	//{
		 // $sql="select ic.dis_rate,ic.ref_no,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color as ii,pouch_color as c,template_measurement as tm,invoice_product as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice WHERE parent_id='".$parent_id."' ".$str." ".$pallet." AND box_no BETWEEN '701' AND '733') as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC ".$limit." "; 
//	}
//	else
	//{
	    	$sql="select ic.dis_rate,ic.ref_no,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,
		p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color as ii,pouch_color as c,template_measurement as tm,invoice_product as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice WHERE parent_id='".$parent_id."' ".$str." ".$pallet.") as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC ".$limit." "; 
	//	}
	//	echo $sql.'<br><br>';//die;
		$data = $this->query($sql);
		
		//http://192.168.1.166/erp/swisspac/admin/index.php?route=invoice&mod=box_detail&invoice_no=MjU5&inv_status=1
		
		//http://192.168.1.166/erp/swisspac/admin/index.php?route=invoice&mod=details&invoice_no=MjU5&price=0&inv_status=0
		
		
		//printr($data);
		/*
		$color = $this->query("SELECT * FROM invoice_color WHERE invoice_id='".$invoice_id."' AND color='-1'");
		if($color->num_rows)
		{
			$sql1="select ic.color as pouch_color_id,ic.dis_rate,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text  from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name, p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,ii.color_text ,ii.color from invoice_color as ii ,template_measurement as tm,invoice_product as ip,product as p where tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id AND ii.color='-1') as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice WHERE parent_id='".$parent_id."' ".$str." ".$pallet." ) as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC"; 
		//	echo $sql1.'<br><br>';//die;
			$data1 = $this->query($sql1);
			//printr($data1);
			foreach($data1 as $clr)
			{
				array_push($data->rows,$clr);
			}
		}*/
		
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
		p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color as ii,pouch_color as c,template_measurement as tm,invoice_product as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice WHERE parent_id='".$parent_id."' ".$str." ".$pallet.") as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' AND ic.buyers_o_no='".$buyers_no."' ORDER BY ig.box_no ASC ".$limit." "; 
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
			
		$sql = "SELECT SUM(box_weight) as total_box_weight, SUM(net_weight) as total_net_weight, SUM(box_weight+net_weight) as total_gross_weight FROM `" . DB_PREFIX . "in_gen_invoice` WHERE invoice_id = '".$invoice_id."' $parent_id";
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
			
		$sql = "UPDATE `" . DB_PREFIX . "invoice` SET invoice_no='".$data['invoiceno']."',invoice_date = '" .$data['invoicedate']. "',exporter_orderno = '".$data['ref_no']."',gst='".$data['gst']."',company_address='".$data['company_address']."',bank_address='".$data['bank_address']."', buyers_orderno ='".$data['buyersno']."',other_ref ='".$data['other_ref']."',pre_carrier='".$data['pre_carrier']."',consignee='".addslashes($data['consignee'])."',buyer='".$data['other_buyer']."',country_destination='".$data['country_final']."',vessel_name='".$data['vessel_name']."',port_load='".$data['portofload']."',port_discharge='".$data['port_of_dis']."',final_destination='".$data['country_id']."', taxation='".$taxation."', excies ='".$excies."', tax='".$tax."', tax_form ='".$form."', tax_mode ='".$data['tax_mode']."',payment_terms='".$data['payment_terms']."',delivery='".$data['delivery']."',HS_CODE='".$data['hscode']."',pouch_type='".$data['printedpouches']."',pouch_desc='".$data['pouch_desc']."',tran_desc='".$data['tran_desc']."',tran_charges='".$data['tran_charges']."',cylinder_charges='".$data['cylinder_charges']."',transportation='".encode($data['transport'])."',container_no='".addslashes($data['container_no'])."',seal_no='".addslashes($data['seal_no'])."',curr_id='".$data['currency']."',customer_name = '".addslashes($data['customer_name'])."', email = '".$data['email']."',status = '".$data['status']."',postal_code = '".$data['postal_code']."',account_code = '".$data['account_code']."',sent = '".$data['sent']."',invoice_status = '".$data['invoice_status']."',date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0,generate_status='1' WHERE invoice_id = '".$data['invoice_id']."'";
	
		$data1 = $this->query($sql);
		//printr($data1);
		$final_total=$this->addinvoicetotalamount($data['invoice_id']);
		return $data1;
		
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
		
		//[kinjal] query Upadated on 24-12-2016
		//$sql="SELECT * FROM invoice_product WHERE invoice_id='".$invoice_no."' AND product_id IN (10,23,11,18,6,34)";
		//echo $sql;
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
		$total_pallet_weight=$total_pallet*12;
		$invoice_qty=$this->getInvoiceTotalData($invoice_no);
		///printr($invoice_qty);//die;
		$box_detail=$this->gettotalboxweight($invoice_no);
		//printr($box_detail);
		$box_det=$this->gettotalboxweight($invoice_no,'1');
		
		$alldetails=$this->getProductdeatils($invoice_no);
		//$tot_qty_scoop=0;
		//$flag1 = array();
		$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_box_no = '';
		
		//sonu add 15-5-2017
		$pouch_no = $scoop_box_no = $roll_box_no = $mailer_box_no = $sealer_box_no = $storezo_box_no = $paper_box_no= $pouch_box_no = '';
		$scoop_name = $roll_name = $mailer_name = $sealer_name = $storezo_name = $paper_box_name=$pouch_name = '';
		//end
		$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series = '';		
		$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper = 0;
		//sonu add 15-5-2017
		$total_net_w_scoop =$total_gross_w_scoop = $total_net_w_roll=$total_gross_w_roll= $total_net_w_p =$total_gross_w_p  = $total_net_w_m =$total_gross_w_m= $total_net_w_s =$total_gross_w_s =$total_net_w_str =$total_gross_w_str =$total_net_w_pouch=0;
		//end
		$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty = $tot_paper_qty = 0;
		$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_paper_rate = 0;
		$total_net_amt_scp= $total_net_amt_rol=$total_net_amt_m=$total_net_amt_pouch=$total_net_amt_s=$total_net_amt_str=$total_net_amt_ppr=0;
		$abcd = 'A';
		//sonu add 15-5-2017
		$f_p=$f_scoop=$f_roll=$f_mailer=$f_sealer=$f_storezo=$f_box=true;
		//end
		
		foreach($alldetails as $details)
		{
			//printr($details);
			//$flag=0;
			
			//printr($net_pouches);
			if($details['product_id']=='11')
			{
				$tot_qty_scoop=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
			
				$tot_scoop_qty = $tot_scoop_qty + $tot_qty_scoop['total']; 
				$tot_scoop_rate = $tot_scoop_rate + $tot_qty_scoop['rate'];
				$total_amt_scoop = $total_amt_scoop + $tot_qty_scoop['tot_amt'];
				$net_pouches_scoop = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_scoop = $net_pouches_scoop ['n_wt'];
				//	printr($net_pouches_scoop);
				//sonu add 15-5-2017
				$total_gross_w_scoop = $net_pouches_scoop ['g_wt'];
				$scoop_box_no =  $net_pouches_scoop ['total_box'];
				$scoop_name = 'SCOOPS';
				//end
				$total_net_amt_scp = $net_pouches_scoop['total_amt'];
				$scoop_no = '1';
				$scoop_series = $abcd;
			}
			else if($details['product_id']=='6')
			{
				$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
				$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
				$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
				$net_pouches_roll = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_w_roll = $net_pouches_roll['n_wt'];
				//sonu add 15-5-2017
				$total_gross_w_roll = $net_pouches_roll['g_wt'];
				$roll_box_no = $net_pouches_roll['total_box'];
				$roll_name = 'ROLL';
				//end
				$total_net_amt_rol = $net_pouches_roll['total_amt'];
				$roll_no = '1';
				$roll_series = $abcd;
			}
			else if($details['product_id']=='10')
			{
				$tot_qty_mailer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_mailer_qty = $tot_mailer_qty + $tot_qty_mailer['total']; 
				$tot_mailer_rate = $tot_mailer_rate + $tot_qty_mailer['rate'];
				$total_amt_mailer = $total_amt_mailer + $tot_qty_mailer['tot_amt'];
				$net_pouches_m = $this->getIngenBox($invoice_no,$details['product_id'],0);
				$total_net_amt_m = $net_pouches_m['total_amt'];
				$total_net_w_m = $net_pouches_m['n_wt'];
				//sonu add 15-5-2017	
				$total_gross_w_m = $net_pouches_m['g_wt'];
				$mailer_box_no = $net_pouches_m['total_box'];
				$mailer_name = 'MAILER BAGS';		
				//end
				$mailer_no = '1';
				$mailer_series = $abcd;
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
				$storezo_series = $abcd;
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
				$paper_box_no = '1';
				$paper_series = $abcd;
			}
			else if($details['product_id']!='11' && $details['product_id']!='6' && $details['product_id']!='10' && $details['product_id']!='23' && $details['product_id']!='18' && $details['product_id']!='34')
			{
				$net_pouches_pouch = $this->getIngenBox($invoice_no,2,0);
				//printr($net_pouches_pouch);
				$total_net_w_pouch =$net_pouches_pouch['n_wt']+$box_det['total_net_weight'];
				$total_net_amt_pouch = $net_pouches_pouch['total_amt'];
				//sonu add 15-5-2017
				$total_gross_w_p = $net_pouches_pouch['g_wt']+$box_det['total_net_weight'];
				$pouch_box_no = $net_pouches_pouch['total_box'];
				$pouch_name = 'POUCHES';				
				//sonu end	
				$pouch_no='1';
			}
			$abcd++;
		
			/*if($flag == '1')
			{
				$flag1[] =$tot_qty_scoop;
				
			}*/
			
			//$scoop = array($flag);
			//$flag1[$flag == '1' ? 'Scoop' : 'flag'] = $flag;
			
		}
	//	printr($total_gross_w_scoop);
	//	printr($total_net_w_scoop);
		//add sonu 5-5-2017 
			if($invoice['country_destination']=='252')
		    	$uk_colspan = "10";
		    else
		       $uk_colspan = "8";
		  //end sonu  
		  
		  
		  
		  
		$totgross_weight=$box_detail['total_net_weight']+$box_detail['total_box_weight']+$box_det['total_net_weight'];
	
	
	    
	
	
	
		//echo $box_detail['total_net_weight']."==".$box_detail['total_box_weight']."===".$box_det['total_net_weight'];
		$taxation=$invoice['taxation'];
	
	
	
	// add sonu 20-6-2018	told by jaimini
		if($invoice['exporter_orderno']=='0'){
		    $exporter_orderno = '';
		    
		    
		}else{
		    $exporter_orderno =$invoice['exporter_orderno'];
		    
		}
		
		
		
	//end	
		
		
		
		
		
		$html='';                       
  		$html.='<div class="panel-body" id="print_div" style="padding-top: 0px;width:auto;">
					<div class="">
					 <div class="form-group ">  	';
      	$fixdata = $this->getFixmaster(); 
      	
       $html.='<table style="cellpadding:0px;cellspacing:0px;  font-size: 10px;" border="1" cellpadding="0" cellspacing="0"  >
	   	
	  	<tr><td colspan="'.$uk_colspan.'">';
				   if($status==1)
				   {
				   	$uk = '';
				   	if($invoice['country_destination']=='252'){
				   		$uk = 'Preferential';
				   }
				  		$html.='<h1>'.$uk.' INVOICE</h1>';
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
      	       		<td colspan="4"  style="vertical-align: top;">'.nl2br($fixdata['exporter']).'</td>
               		<td colspan="4"><table class="buyr_table" >';
					// <img src="'.HTTP_SERVER.'admin/model/barcodes.php?text=259 / 15-16 / 29-05-2015 &codetype=Code39&size=40" alt="259 / 15-16 / 29-05-2015 " /> <br><br>
					 $html.='<tr><td>'.$invoice['invoice_no'].'&nbsp;/&nbsp;'.(date('y')).'-'.(date('y')+1).'&nbsp;/&nbsp;'.date("d-m-Y",strtotime($invoice['invoice_date'])).'
              		 <span style="float:right">'. $exporter_orderno.'&nbsp;&nbsp;&nbsp;&nbsp;</span><br><br>
					 
                   <span><img src="http://www.bcgen.com/demo/linear-dbgs.aspx?D='.$invoice['invoice_no'].'"></span></td></tr>';
				    if($invoice['order_type']!='sample')
		 			{
		 			    
						if($invoice['country_destination']=='252'){
						   
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
               		 <td colspan="4">&nbsp;</td>
               		 <td colspan="4" rowspan="3" style="vertical-align: top;">'.$buyers.'</td>
             </tr>
      		 <tr>
           			 <td colspan="4" style="vertical-align: top;"><strong>Consignee :</strong></td>
          	</tr>';
		/*} else {
		
			$html .= '<tr>
							 <td colspan="4" style="vertical-align: top;"><strong>Consignee:</strong></td>
							 <td colspan="4">'.$buyers.'</td>
          			</tr>';
		}*/
       		  $html .='<tr>
          			 <td colspan="4" rowspan="2"  style="vertical-align: top;"><div>'.nl2br($invoice['consignee']).'</div></td>
       		  </tr>';
			  if($invoice['order_type']!='sample')
			  {
				  $html .='<tr>
						 <td colspan="2" style="vertical-align: top;"><strong><u>Country of origin of goods</u></strong><br/>'.$fixdata['country_origin_goods'].'</td>
						 <td colspan="2" style="vertical-align: top;"><strong><u>Country of Final Destination</u></strong><br />
							 <div>'; $country_name = $this->getCountryName($invoice['country_destination']);
							 	$html.= $country_name['country_name'].'</div></td>';
							
				$html.=' </tr>';
			 
         	$html .='<tr>
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
           				            	 //add sejal 24-4-2017
           				            	 $port_discharge ='';
    									if(is_numeric($invoice['port_discharge']) && ucwords(decode($invoice['transportation'])))
    									{
    										$country_name=$this->getCountryName($invoice['port_discharge']);
    										$port_discharge = $country_name['country_name'];
    											
    										
    									}
    									else
    									{	$port_discharge = $invoice['port_discharge'];
    										
    											
    									}
           				            	
           				            	
           				            	if($invoice['order_user_id']=='24' && ucwords(decode($invoice['transportation']))=='Sea')
										 {
											$html.= 'Melbourne Sea Port , '.$port_discharge.'</div></td>';
										 }
										 elseif($invoice['order_user_id']=='33' && ucwords(decode($invoice['transportation']))=='Sea')
										 {
											$html.= 'Sydney Sea Port , '.$port_discharge.'</div></td>';
										 }
										 else
										 {
											$html.= $port_discharge.'</div></td>';
										 }
										  // sejal end
							// $h
		 						// $html.= $country_name['country_name'].'</div></td>
           			$html.='<td colspan="2" style="vertical-align: top;"><strong><u>Final Destination:</u></strong>
           				<br />
            			 <div>';  $country_name=$this->getCountryName($invoice['final_destination']); 
            			 			//add sejal 24-4-2017
						 $final_destination ='';
									if(is_numeric($invoice['port_discharge']) && ucwords(decode($invoice['transportation'])))
    								{
    									 $country_name=$this->getCountryName($invoice['final_destination']); 
    										$final_destination = $country_name['country_name'];
    										
    									
    									}
    								else
    								{	$final_destination = $invoice['final_destination'];
    									
    										
    								}
            			 			 if($invoice['order_user_id']=='24' && ucwords(decode($invoice['transportation']))=='Sea')
        							 {
        							 	$html.= 'Melbourne Sea Port , '.$final_destination .'</div></td>';
        							 }
        							 elseif($invoice['order_user_id']=='33' && ucwords(decode($invoice['transportation']))=='Sea')
        							 {
        							 	$html.= 'Sydney Sea Port , '.$final_destination .'</div></td>';
        							 }
        							 else
        							 {
        								$html.=$final_destination .'</div></td>';
        							 }
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
				 
				 if($box_det['total_net_weight']!='')
				 	$child_net= $box_det['total_net_weight'];
				
				//printr($box_detail);
				$html.=' <tr id="one_tr">
								 <td  colspan="2" class="no_border">'.$total_no_of_box['tot'].' Boxes<br>(Nos. 1 to '.$total_no_of_box['tot'].')<br>Corrugated Box Packing<br>Gross weight : '.
									number_format($totgross_weight,3).' '.$measurement['measurement'].'<br><br>';
									if(ucwords(decode($invoice['transportation']))=='Sea')
										$html.='Tare Weight : '.$total_pallet_weight.' Kgs<br>';
									$html.=' Net Weight : '.number_format($box_detail['total_net_weight']+$child_net,3).'&nbsp;'.$measurement['measurement'].'<br>';
									if(ucwords(decode($invoice['transportation']))=='Sea')
									{
										//printr($total_pallet_box);
									//echo '(('.$total_no_of_box.')-('.$total_pallet_box.'))';
										$loose_boxes=(($total_no_of_box['tot'])-($total_pallet_box));
										$html.='Total '.$total_pallet.' Wooden Pallets<br>Contaning  '.$total_pallet_box.' Boxes  ';
										if($loose_boxes>0)
										$html.=' , & '.$loose_boxes.' Loose Boxes';
									}
									else
								
									$html.='Total '.$total_no_of_box['tot'].' Boxes ';	
											 
						 $html.='</td>	
										<td colspan="3"  class="no_border"><div>'. $fixdata['num_packages'].'</div></td>
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
                     		
                     			if($invoice['country_destination']=='42')	
						$html.='<br><br><br>A/c no : '.$invoice['account_code'];
						
                     		 $html.='</td>
					        
         		 			<td colspan="3" class="no_border">';
         		 			if($pouch_no=='1')
         		 			{
          				 	    $html.='<b>A) ';
								if($invoice['pouch_desc']!='')
									$html.=$invoice['pouch_desc'].'<br />';
								
								$html.= $invoice['pouch_type'].'</b></div><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HS CODE:&nbsp;'. $invoice['HS_CODE'].'<br>';
         		 			}
						 
						// $html.='<div><strong>We Intend to claim rewards under merchandise Export <br></strong> From India Scheame (MEIS)</div><br />';
					 //printr($invoice_qty);
					 //echo $roll_no;
								if($scoop_no == '1')
								{	
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate );
									//$total_rate_val=$tot_scoop_rate;
									$total_amt_val=$invoice_qty['tot'];	
								}
								if($roll_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate );
									//printr($tot_roll_rate);
									//$total_rate_val=$tot_roll_rate;
									$total_amt_val=$invoice_qty['tot'];	
								}
								if($mailer_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate );
									//$total_rate_val=$tot_mailer_rate;
									$total_amt_val=$invoice_qty['tot'];
								}
								if($sealer_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate );
									//$total_rate_val=$tot_sealer_rate;
									$total_amt_val=$invoice_qty['tot'];
								}
								if($storezo_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate );
									//$total_rate_val=$tot_storezo_rate;
									$total_amt_val=$invoice_qty['tot'];
									
								}
								if($paper_box_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate );
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
									
								}
								else
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									$total_rate_val=$invoice_qty['total_rate']-($tot_scoop_rate +$tot_roll_rate +$tot_mailer_rate +$tot_sealer_rate +$tot_storezo_rate +$tot_paper_rate );
									
								//$total_rate_val=$invoice_qty['rate'];
									//$total_amt_val=$total_qty_val*$invoice_qty['rate'];
									$total_amt_val=$invoice_qty['tot'];
								}

								//$rate_per = number_format((((($total_amt_val-$invoice['tran_charges'])-$invoice['cylinder_charges'])-($total_rate_val*$tot_scoop_qty)-($total_rate_val*$tot_roll_qty)-($total_rate_val*$tot_mailer_qty)-($total_rate_val*$tot_sealer_qty)-($total_rate_val*$tot_storezo_qty)-($total_rate_val*$tot_paper_qty))/$total_qty_val),8);
								
			//$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo))/$total_qty_val),8);
								
								
								
								//$amt = ($total_amt_val-$invoice['tran_charges'])-$invoice['cylinder_charges']-($total_rate_val*$tot_scoop_qty)-($total_rate_val*$tot_roll_qty)-($total_rate_val*$tot_mailer_qty)-($total_rate_val*$tot_sealer_qty)-($total_rate_val*$tot_storezo_qty)-($total_rate_val*$tot_paper_qty);
							
							if(ucwords(decode($invoice['transportation']))=='Sea')
							{
								//printr($total_net_w_pouch.'+'.$rate_per.'+'.$invoice['cylinder_charges']);
							//$amt_new = ($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
							//echo $invoice['cylinder_charges'];
							    $total_amt_val=$total_amt_val+$invoice['cylinder_charges'];
							  // printr($invoice['tran_charges']);
								/*$total_amt_val=$total_net_amt_pouch+$invoice['cylinder_charges'];

								$rate_per = number_format((($total_amt_val-($invoice['tran_charges']))/$total_net_w_pouch),8);
							
								
								$amt_new = ($total_amt_val-($invoice['tran_charges']));
							
								$amt = $amt_new;*/
								//printr($total_amt_val.'------');
								//printr($total_net_w_pouch.'********');
						
						
								$amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
									///	$rate_per = number_format(($total_amt_val/$total_net_w_pouch),8); comment by sonu  18-3-2017  
										$rate_per = number_format(( $amt/$total_net_w_pouch),8); 
						
								
							}
							else
							{
								//$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo))/$total_qty_val),8);
								//$amt = ($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
                                if($pouch_no=='1')
         		 						$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo))/$total_qty_val),8);
         		 				else
         		 				    $rate_per=0;
								$amt = ($total_amt_val-($invoice['tran_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
								
								
							}	
								
								//printr($amt);
								
								//printr('('.$total_amt_val.'-('.$invoice['tran_charges'].'+'.$invoice['cylinder_charges'].'+'.$total_amt_paper.'+'.$total_amt_roll.'+'.$total_amt_scoop.'+'.$total_amt_mailer.'+'.$total_amt_sealer.'+'.$total_amt_storezo.'))/'.$total_qty_val.'),8)');

								$air_rate = $amt * $currency['price'];
						
							
							 //comment by sonu told by jaimini 4-7-2017 
							 
							 if(ucwords(decode($invoice['transportation']))=='Sea')
							  { //[kinjal] : replace 1.9% to 1.5% on 3-12-2016 order by pinankbhai //[kinjal] on 20-1-2017 tols by pinank replace to it Licence No 3430002141 Dated 23.04.2012
								$html.='<div><strong>Shipment under the Duty DrawBack Scheme</strong>
								<br>Vide DBK SR No 392303 B @ 1.5% On FOB<br><strong>(Cenvat facility has been availed)<br>This Shipment is taken under the EPGC Licence<br>Licence No 3430002583 Dated 17.12.2014</strong></div><br />';
							  }
							  elseif((ucwords(decode($invoice['transportation']))=='Air') && ($air_rate >= '100000'))
							  { //[kinjal] : replace 2% to 1.5% on 3-12-2016 order by pinankbhai
								//removed this line <br>This Shipment is taken under the EPGC Licence<br>Licence No 3430002142 Dated 23.04.2012 on 3-12-2016 order by pinankbhai
								
								//[kinjal] : commented on 17-12-2016 order by pinankbhai
								//$html.='<div><strong>Shipment under the Duty DrawBack Scheme</strong><br>Vide DBK SR No 3923000099 B @ 1.5% On FOB<br><strong>(Cenvat facility has been availed)</strong></div><br />';
								if($invoice['country_destination']=='252')
								    $html.='<div><strong>Shipment under the Duty DrawBack Scheme</strong><br>Vide DBK SR No 392303 B @ 1.5% On FOB<br><strong>(Cenvat facility has been availed)</strong></div><br />';
							  }
					  
					  //$html.='<div><strong>We hereby declare that,we shall claim as admissible <br></strong> under the chapter 3 FTP</div><br />';
					  $html.='</td>';
					 //if($pouch_no=='1')
					  //{
        					if(ucwords(decode($invoice['transportation']))=='Sea')
        					{
                   					if($pouch_no=='1')
                   					    $html.='<td class="no_border" valign="top"><p align="center"> NET. WT.<br>'.number_format($total_net_w_pouch,3).' KGS <br>'.$total_qty_val.' Nos</p></td>';
                   					else
                   					    $html.='<td class="no_border"></td>';
                   				}
                   				else
                   				{
                   					if($pouch_no=='1')
                   					    $html.='<td class="no_border" valign="top"><p align="center">'.$total_qty_val.'</p></td>';
                   					else
                   					    $html.='<td class="no_border"></td>';
                   				}
                         if($status==1)
        				 { 		
        		          		if($pouch_no=='1')
        		          		{
        		          	        	$html.='<td class="no_border" valign="top"><p align="center">'.$rate_per.'</p></td>
                  					        	<td class="no_border" valign="top"><p align="center">'.number_format($amt,2).'</p>';
        		          		}
        		          		else
        		          		{
        		          		     $html.='<td class="no_border"></td><td class="no_border">';
        		          		}
        				 }
        				 else
        				 {
        					 	 //[kinjal] cond added on 16-5-2017 
        				        $html.='<td colspan="2" class="no_border" valign="top" >';
        				        if($pouch_no=='1')
         		 		    	{
            					 	if($scoop_no == '1' || $roll_no == '1' || $mailer_no == '1' || $sealer_no == '1' || $storezo_no == '1' || $paper_box_no == '1')
            					 	{
                						$html.=$pouch_box_no.' BOXES OF '.$pouch_name.'<br> Total GWT : '.number_format($total_gross_w_p,3).'KGS<br> Total NWT :'.number_format($total_net_w_pouch,3).'KGS';
            					 	}
         		 		    	}
        					 	$html.='</td>';
        					 	////[kinjal] END
        				 }
					 // }
				$html.='</tr>';
				
						$insurance='0';
							
							$html.='<tr> 
										<td colspan="2" class="no_border"></td>
										<td colspan="3" class="no_border">';
										if($status==1)
										{
												if($invoice['tran_charges']!='0')
													$html.='B) &nbsp;'. ucwords(decode($invoice['transportation'])).' Freight Charges';
												if($invoice['cylinder_charges']!='0' && ucwords(decode($invoice['transportation']))!='Sea')	
													$html.='<br />C)Cylinder Making Charges';
											
												if($invoice['country_destination']=='252' && ucwords(decode($invoice['transportation']))=='Air')
												{
													$html.='<br/>D)Insurance<br>
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
															if($invoice['tran_charges']!='0')
															 $html.=number_format($invoice['tran_charges'],2).'';
																
															if($invoice['cylinder_charges']!='0' && ucwords(decode($invoice['transportation']))!='Sea')	
															  $html.= '<br />'.number_format($invoice['cylinder_charges'],2);
															$insurance='0';
															 if($invoice['country_destination']=='252' && ucwords(decode($invoice['transportation']))=='Air')
															  {
																$tran_charges_tot=round($invoice['tran_charges'],2)+$amt;
																//printr($invoice['tran_charges'].'+'.$amt);
																$insurance=(($tran_charges_tot*110/100+$tran_charges_tot)*0.07)/100;
															//	printr($tran_charges_tot.'*110/100+'.$tran_charges_tot.'0.07/100');
																$html.='<br>'.number_format($insurance,2);
															  }
															 
										 
											 $html.='</p></td>';
										}
										
										 
										 
									$html.='</tr>';
						
								//printr($tot_scoop_rate);
								if($scoop_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$scoop_series.') Plastic Scoop</strong><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 39235090<br>
</div></td>';
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
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_scoop,2).'</p></td>';
										//	$html.='<td class="no_border" valign="top"><p align="center">'.((($tot_qty_scoop['tot_amt']-$invoice['tran_charges'])-$invoice['cylinder_charges'])/$tot_qty_scoop['total']).'</p></td>
										//	<td class="no_border" valign="top"><p align="center">'. round(($tot_qty_scoop['tot_amt']-$invoice['tran_charges'])-$invoice['cylinder_charges']).'</p></td>';
											
									}
									else
										$html.='<td class="no_border" colspan="2"><br>'.$scoop_box_no.' BOXES OF '.$scoop_name.'<br> Total GWT : '.$total_gross_w_scoop .'KGS<br> Total NWT :'.$total_net_w_scoop.'KGS</td>';
										
									$html.='</tr>';
								}
								if($roll_no == '1')
								{
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$roll_series.') Supplying in Rolls</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 3920<br>
</div></td>';
												if(ucwords(decode($invoice['transportation']))=='Sea')
													$html.='<td class="no_border" valign="top"><p align="center">NET. WT.<br>'.$total_net_w_roll .'<br>'.$tot_roll_qty.' Nos </p></td>';
												else
													$html.='<td class="no_border" valign="top"><p align="center">'.$tot_roll_qty.'</p></td>';
									if($status==1)
									{	
											if(ucwords(decode($invoice['transportation']))=='Sea')
											{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_roll/$total_net_w_roll,8).'</p></td>';
											}else{
												$html.='<td class="no_border" valign="top"><p align="center">'.number_format($total_amt_roll/$tot_roll_qty,8).'</p></td>';
											}
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
												<td colspan="3" class="no_border"><div><strong>'.$mailer_series.') Mailer Bag</strong><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 3923 <br>
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
									$html.='<tr>
												<td colspan="2" class="no_border"></td>
												<td colspan="3" class="no_border"><div><strong>'.$storezo_series.') Storezo</strong><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HS CODE : 3923<br>
</div></td>';
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
								if($paper_box_no == '1')
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
							//if($status==1)
							//{
									$html.='<tr>
												<td colspan="2" class="no_border">';
													if(decode($invoice['transportation'])=='sea')
														$html.='Container No :'.$invoice['container_no'].'<br>Seal No : '.$invoice['seal_no'];
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
										
							 			$html.= $invoice['tran_desc'].'<br />';
							 			if($invoice['country_destination']=='252')
										{
											$html.='<div><strong>" Statement Of Origin "</strong><br/>
The exporter <strong>"INREX3403000290EC005"</strong> of the products covered by this document declares that, except where otherwise clearly indicated, these products are of Indian preferential origin according to rules of origin of the Generalized System of Preferences of the European Union and that the origin criterion met is P.</div><br />';
							 			}
							 
										 $html.='<div><strong>We Intend to claim rewards under merchandise Export <br></strong> From India Scheame (MEIS)</div><br />';
										 
										 $html.='<div><strong>We hereby declare that,we shall claim as admissible <br></strong> under the chapter 3 FTP</div><br />';
							// printr($totgross_weight.'=='.$total_pallet_weight);
										  if(ucwords(decode($invoice['transportation']))=='Sea')
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

		//$invoice['cylinder_charges']
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
												if($detail['product_id']!='11' && $detail['product_id']!='6' && $detail['product_id']!='10' && $detail['product_id']!='23' && $detail['product_id']!='18' && $detail['product_id']!='34')
												{
													$charge = $invoice['tran_charges']+$invoice['cylinder_charges'];										
													$boxDetail = $this->getIngenBox($invoice_no,$n=2,$charge);
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
													if($f_scoop==true)
													{
														$pouch_detail['scoop']=$boxDetail;
														$f_scoop=false;
													}
												}
												else if($detail['product_id']=='6')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_roll==true)
													{
														$pouch_detail['roll']=$boxDetail;
														$f_roll=false;
													}
												}
												else if($detail['product_id']=='10')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_mailer==true)
													{
														$pouch_detail['Mailer Bag']=$boxDetail;
														$f_mailer=false;
													} 
												}
												else if($detail['product_id']=='23')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_sealer==true)
													{
														$pouch_detail['Sealer Machine']=$boxDetail;
														$f_sealer=false;
													}
												}
												else if($detail['product_id']=='18')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_storezo==true)
													{
														$pouch_detail['storezo']=$boxDetail;
														$f_storezo=false;
													}
												}
												else if($detail['product_id']=='34')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_box==true)
													{
														$pouch_detail['paper_box']=$boxDetail;
														$f_box=false;
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
																
																	$html.='<td class="no_border"><p align="right">'.$this->numberFormate(($data['qty_in_kgs']*$data['rate_in_kgs']),"2").'</p></td>
					</tr>';
											$sample_Total_price=$sample_Total_price+($data['qty_in_kgs']*$data['rate_in_kgs']);
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
	
	if(ucwords(decode($invoice['transportation']))=='Air')
		$Total_price = $final_amount = $final_amount + $invoice['cylinder_charges'];
		
         $html .='<tr>
					<td  colspan="2"></td>';						
					if($status==1){
						if($invoice['country_destination'] ==111){
					$html.='<td colspan="4"><div align="right"><strong>Grand Total</strong></div></td>';
						} else {
					$html.='<td colspan="4"><div align="right"><strong>Total</strong></div></td>';
						}
						
					$html .='<td><div align="center">'. $currency['currency_code'].'</div></td>
					
					<td><p align="center">'; $html.=$this->numberFormate(($final_amount),"2").'</p></td></tr>';
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
					<td colspan="4"><div align="right"><strong>Total</strong>';
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
						$html.='<b>FOB VALUE = '.(($final_amount-$invoice['tran_charges'])-$invoice['cylinder_charges']).'</b>';
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
				        if(ucwords(decode($invoice['transportation']))=='Air')
				            $html.='<span><strong>'. $fixdata['notes'].'</strong></span>';
				        else
				            $html.='<span><strong>'. $fixdata['sea_notes'].'</strong></span>';
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
			//printr($invoice_no);
		
			$invoice=$this->getInvoiceNetData($invoice_no);
			$currency=$this->getCurrencyName($invoice['curr_id']);	
			$alldetails=$this->getInvoiceProduct($invoice_no);
			
			//printr($invoice);die;
			
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
				$productcode=$this->getProductCode($details['invoice_product_id']);
			//	printr($productcode);
				$color = $this->getColorDetails($invoice_no,$details['invoice_product_id']); 	
				//if($productcode['description'] =='')
//				{
//					$description =$color_val['size'].' '.$color_val['measurement'].' '.$color_val['color'].' '.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$details['product_name'] ),0,3)).' '.$zipper_name.' '.$valve;
//				}else
//				{
//					$description=$productcode['description'];
//				}
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
					//printr($color_val);die;
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
										
										'*Description'=>$productcode['description'],
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
	
	public function viewInout($invoice_no,$status,$n=0,$from='',$to='')
	{
		$parent_id=0;
		if($n==1)
		{
			$details=$this->InoutLable($invoice_no,$parent_id,$from,$to);
		}
		else
		{
			$details=$this->colordetails($invoice_no,$parent_id);
		}
		$invoice=$this->getInvoiceNetData($invoice_no);
		$setHtml='';$description='';$valve='';$zipper_name='';
		$i=2;$gross_weight=0;$tot_box=count($details);
		//printr($details);
		if($details!='')
		{
				foreach($details as $val)
				{	
					$c_name='';$size='';$qty='';
					$zipper=$this->getZipper(decode($val['zipper']));
					$zipper_name=$zipper['zipper_name'];
					$valve=$val['valve'];
					
					
					
					$childBox=$this->colordetails($invoice_no,$val['in_gen_invoice_id']);
					$product_decreption = $this->getProductCode($val['invoice_product_id']);
					$net_w = $val['net_weight'];
					if(isset($childBox) && !empty($childBox))
					{
						foreach($childBox as $key=>$ch)
						{
							$net_w = $net_w+$ch['net_weight'];
						}
					}
					
					$gross_weight=$net_w+$val['box_weight'];
					
					
					
					if($val['pouch_color_id']=='-1')
					{
						$size=$val['dimension'];
						//$size=$val['size'].' '.$val['measurement'];
						$c_name=$val['color_text'];
						//$c_name=$val['color_text'];
					}
					else	
					{
						$size=$val['size'].' '.$val['measurement'];
						
					//	if($val['color_text']!='')
					        //	$c_name=$val['color'].'  '.$val['color_text'];
				    //	else
						         $c_name=$val['color'];
					
				
					}
						
					$qty=$val['genqty'];
					
					$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
					
					//if($product_decreption==''  )
					//{
						
						//$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
					//}else
					//{
							if( $val['product_id']=='13' ||$val['product_id']=='16' || $val['product_id']=='31' || $val['product_id']=='30')
							{
							$description=$product_decreption['description'];
							
							
							}
							else{
							$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$val['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;
							}
						
				//	}
					
					if($i%2==0)
					{	$a=$i-2;
						if($status==2)		
							$c=3;
						else
							$c=3;
						
						//width:600px;
							$setHtml .='<div style="" id="'.$i.'">
											<table class="table"  border="0" style="">
												<br><br>
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
																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['item_no'].'</td>';
																}
																else
																{
																	$setHtml .='<td  style="padding:0px;border:0px">ORDER NO.&nbsp;:&nbsp;</td>';
																	$setHtml .='<td  style="padding:0px;border:0px">'.$val['buyers_o_no'].'</td>';
																}	
																	
															$setHtml .='</tr>';
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
																	
																		<td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;font-size:10px;"><img src="http://www.bcgen.com/demo/linear-dbgs.aspx?D='.$val['box_unique_number'].'"></span><div></td>
																	</tr>
											
										</table>
									</td>';
										if(isset($childBox) && !empty($childBox))
										{
											foreach($childBox as $key=>$ch)
											{
											       // printr($ch);
													$setHtml .='<div style="" id="'.$i.'">
																	<table class="table"  border="0" style="">
																		<tr>';//width:600px;
												//} width:50%	
																	$setHtml .='<td style="border:none; border-top:none;">
																				<table  style="" id="sub_table" class="sub_table">
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
																		<td  style="padding:0px;border:0px" colspan="2"><div><span style="line-height:50px;font-size:10px;"><img src="http://www.bcgen.com/demo/linear-dbgs.aspx?D='.$val['box_unique_number'].'"></span><div></td>
																	</tr>
																
																</table></td></tr></table></div>
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
		//$deleted_by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');
		$sql="DELETE FROM in_gen_invoice WHERE in_gen_invoice_id='".$in_gen_invoice_id."' OR parent_id='".$in_gen_invoice_id."'";
		//$sql="UPDATE " . DB_PREFIX . "in_gen_invoice SET is_delete = '1',  deleted_by = '".$deleted_by."' WHERE in_gen_invoice_id='".$in_gen_invoice_id."' OR parent_id='".$in_gen_invoice_id."'";
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
					$setHtml .='<table class="table b-t table-striped text-small table-hover detail_table" style="  border: 1px solid black;">';
					if($price==1)
						$colspan=10;
					else
						$colspan=6;
							$setHtml .=' <thead>
												<tr>
													<th colspan="'.$colspan.'" style="text-align:center;padding:0;"><h4>Pallet Sheet No. '.$pallet['pallet_no'].'</h4></th>
												</tr>
												<tr>
													<th style="width:35px">Box Nos.</th>  ';                    
													if($invoice['country_destination']==253)
														$setHtml.='<th style="width:35px">Buyer\'s Order No</th>';
													$setHtml.='<th style="245px" >Product</th>';
													//[kinjal] on 20-2-2017 told by pinank this only come in uk cond. below too.
													if($invoice['country_destination']==170)
														$setHtml.='<th style="width:35px">'.$item_text.'</th>';
														
													if($invoice['country_destination']!=253)
														$setHtml.='<th style="width:35px">Size</th>';
													$setHtml.='<th style="width:35px">Quantity</th>
													<th style="width:35px">Gr. Wt in kgs</th>
													<th style="width:35px">Net. Wt in kgs</th>';
													if($price==1)
													{
														$setHtml.='<th style="width:35px">Rate</th>
														<th style="width:35px">Total Amount</th>';
													}
													//[kinjal] on 20-2-2017 told by pinank this not come in any cond. below too.
													/*if($invoice['final_destination']!=253)
														$setHtml.='<th style="width:35px">Buyer\'s Order No</th>';*/
													
													if($status==0)
													$setHtml .=	'<th style="width:35px">Action</th>';
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
		return $setHtml;				
	}
		
	function viewDetails($invoice_no,$status=0,$price=0,$p_break=0)
	{
	$setHtml='';
		
		//online : 177 & offline :193
		$menu_id = $this->getMenuPermission(177,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		//printr($menu_id);
		$invoice=$this->getInvoiceNetData($invoice_no);
			//printr($invoice);die;
		if($invoice['country_destination']==170)
			$item_text='Special Code';
		else
			$item_text='Item No.';
			
		$total_box=$this->colordetails($invoice_no,0,'');
		//printr(count($total_box));//die;
		if(isset($total_box) && !empty($total_box))
		{
			$tot_inv=count($total_box);
		//	printr($tot_inv);
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
					}
					else
							$limit='';
					
					$description='';$valve='';$zipper_name='';$gross_weight='';
					
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
									$setHtml .= '<br><br><br><div style="width:730px;">';
									//$style='page-break-before:always;';
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
										
										$setHtml .='<table class="table b-t table-striped text-small table-hover detail_table" style="width:730px;" >';
											if($price==1)
												$colspan=$i;
												
											else
												$colspan=10;
												
												$setHtml .=' <thead>';
														if($page_no=='0')
														{
															$setHtml.='<tr><th colspan="'.$colspan.'" style="text-align:center;">';
																if($price==1)
																	$setHtml.='<h2>Packaging Details With Price List</h2>';
																else
																	$setHtml .= '<h2>Packaging Details List </h2>';
															$setHtml.='</th></tr>';
														}
														$setHtml .='<tr>
																	<th colspan="'.$colspan.'" style="text-align:center;padding:0;"><h4>Detailed Packing List '.($page_no+1).'</h4></th>
																</tr>
																<tr>
																	<th style="width:20px">Box Nos.</th>  ';                    
																	if($invoice['country_destination']==253)
																		$setHtml.='<th style="width:35px">Buyer\'s Order No</th>';
																	$setHtml.='<th colspan="3">Product</th>';
																	//updated on 3-12-2016 [kinjal] if($invoice['final_destination']!=170)
																	if($invoice['country_destination']=='253')
																	{
																		$setHtml.='<th style="width:35px">'.$item_text.'</th>';
																	}
																	if($invoice['country_destination']!=253)
																	$setHtml.='<th colspan="2">Size</th>';
																	$setHtml.='<th style="width:25px">Quantity</th>
																			   <th style="width:35px">Gr. Wt in kgs</th>
																	           <th style="width:35px">Net. Wt in kgs </th>';
																	if($invoice['country_destination']==170)
																	{
																		$setHtml.='<th style="width:35px">invoice NUMBER</th>';
																		$setHtml.='<th style="width:35px">'.$item_text.'</th>';
																	}
																	if($price==1)
																	{
																		$setHtml.='<th style="width:25px">Rate</th>';
																		//add by sonu told by vikas sir 6-4-2017
																		if(!empty($menu_id) || !empty($menu_admin_permission))
																			{
																				$setHtml.='<th style="width:25px">Original Rate</th>';
																			}
																		$setHtml.='<th style="width:30px">Total Amount</th>';
																	}
																	if($invoice['country_destination']!=253)
																		$setHtml.='<th style="width:35px">Buyer\'s Order No</th>';
								
																	if($status==0)
																		$setHtml .=	'<th style="width:35px">Action</th>';
													$setHtml .=	'</tr>
														</thead>
														<tbody>';
															$parent_id=0;
															 
															$colordetails=$this->colordetails($invoice_no,$parent_id,'','',$limit);
															//printr($colordetails);
														//die;	
															$i=1;
															$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=0 ;$tot_discount_rate  =0;
															if(isset($colordetails) && !empty($colordetails))
															{	//printr($colordetails);
																$tot_qty=0;$tot_net_weight=0;$tot_gross_weight=0;$tot_rate=0;$tot_amt=$tot_netW=0;
																$bono='';$o_no =$ref_number='';
																foreach($colordetails as $key=>$color)
																{
																	//echo count($colordetails);
																	//printr($color);
																	$zipper=$this->getZipper(decode($color['zipper']));
																	//if($zipper['zipper_name']!='No zip')
																		$zipper_name=$zipper['zipper_name'];
																	// if($color['valve']!='No Valve')
																		$valve=$color['valve'];
																	$childBox=$this->colordetails($invoice_no,$color['in_gen_invoice_id']);
																	
																	$c_name=$color['color'];
																	if($color['pouch_color_id'] == '-1')
																//	if($color['color_text']!='')
																	{
																		$c_name = $color['color_text'];
																	}
																//	printr('abc'.$c_name);
																	
																	$description=$c_name. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$color['product_name'] ),0,3)).' '.$zipper_name.' '.$valve.') ' ;			
																	//told by jaimini 17-2-2017 comment by sonu pdf is not working correctly  23-3-2017 
																	
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
																		$size=$color['size'].' '.$color['measurement'];
																	}
																	//$size=$color['size'].' '.$color['measurement'];
																	$qty=$color['genqty'];
																	$rate=$color['rate'];
																	$discount_rate = $color['dis_rate'];
																	$net_w = $tot_netW = $color['net_weight'];
																	$child_qty=0;$total_r_child=$c_rate=$discount_total_r_child = $c_discount_rate =0 ;
																	$total_r=$qty*$rate;
																	 $o_no = $color['buyers_o_no'];
																	 $ref_number = $color['ref_no'];
																	 
																	 $div=' + ';
																	 if($p_break==1)
																	 	$div='<div class="line line-dashed m-t-large"></div>';
																		
																	if(isset($childBox) && !empty($childBox))
																	{
																		foreach($childBox as $ch)
																		{
																			$zipper_child=$this->getZipper(decode($ch['zipper']));
																			$description.=$div.''.$ch['color']. ' ('.strtoupper(substr(preg_replace('/(\B.|\s+)/','',$ch['product_name'] ),0,3)).' '.$zipper_child['zipper_name'].' '.$ch['valve'].') ' ;			
																			/*if($ch['dimension']!='')
																				$size.=' + '.$ch['dimension'];
																			else*/
																				$size.=$div.''.$ch['size'].' '.$ch['measurement'];
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
																			//$tot_net_weight = $tot_netW;
																			
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
																				
																				$setHtml .='<td><input type="text" class="form-control  validate[required]"  name="gen_id'.$i.'_page_no'.($page_no+1).'"  onblur="edit_box_no('.$i.','.($page_no+1).')" id="gen_id'.$i.'_page_no'.($page_no+1).'" style="width:auto;" value="'.$box_no.'"  />						 
																								 <input type="hidden"  name="gen_unique_id'.$i.'_page_no'.($page_no+1).'" id="gen_unique_id'.$i.'_page_no'.($page_no+1).'" value="'.$color['in_gen_invoice_id'].'"  />
																							</td>';
																										
																			}
																			else
																				$setHtml .='<td >'.$box_no.'</td>';
																			
																			$end=$box_no;
																			if($invoice['country_destination']==253)
																				$setHtml .='<td>'.$color['buyers_o_no'].'</td>';
																				
																			/*if(isset($childBox) && !empty($childBox))
																			{
																				$setHtml .='<td colspan="10">
																								<table>
																									<tr>
																									
																									</tr>
																								</table>
																							</td>';
																				
																				
																			}*/
																			
																			
																				
																			if($invoice['country_destination']==253)
																				$setHtml.='<td colspan="3">'.$size.' '.$description.'</td>';
																			else
																				$setHtml .='<td colspan="3">'.$description.'</td>';
																				
																			if($invoice['country_destination']=='253')
																					$setHtml.='<td>'.$color['item_no'].'</td>';
																						
																			if($invoice['country_destination']!=253)
																					$setHtml.='<td  colspan="2">'.$size.'</td>';
																					
																					$setHtml.='<td>'.$qty.'</td>
																					<td>'.number_format($gross_weight,3).'</td>
																					<td>'.$net_w.'</td>';
																					
																			if($invoice['country_destination']=='170')
																			{
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
																				$rate_for_print=number_format($color['rate'],4);
																				
																				$setHtml.='<td style="text-align:right">'.$rate.'</td>';
																					if(!empty($menu_id) || !empty($menu_admin_permission))
																					{
																						$setHtml.='<td style="text-align:right">'.$discount_rate.'</td>';
																					}
																				$setHtml.='<td style="text-align:right">'.number_format($tot_ch_all_rate,4).'</td>';
																							
																			}
																			
																			if($invoice['country_destination']!=253)
																			{
																				//$setHtml .='<td>'.$color['buyers_o_no'].'</td>';
																				
																				//if($bono!=$color['buyers_o_no'])
																				//{
																						//rowspan="'.$ct.'"
																					$setHtml .='<td>';
																					//$setHtml .='<div valign="center">'.$color['buyers_o_no'].'</div>';
																					
																				    if($invoice['country_destination']=='252')
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
														//$tot_inv
														//printr($price.'=='.$tot_inv.'=='.$box_no);
														
														if($price==1)
														{
															if($tot_inv==$box_no)
															{
																if($invoice['cylinder_charges']!='0')
																{
																	if(($box_no+1)==($tot_inv+1))
																	{
																			$setHtml.='<tr>
																							<td></td>
																							<td colspan="3">Cylinder Making Charges</td>
																							<td colspan="6"></td>
																							<td colspan="5">'.$invoice['cylinder_charges'].'</td>
																						</tr>';
																	}
																	$tot_amt = $tot_amt+$invoice['cylinder_charges'];
																}
															}
														}
														
														
															$setHtml.='<tr>
																			<td colspan="'.$colspan.'">&nbsp;</td>
																	   </tr>
																	   <tr>
																		   <td></td>';
																			if($invoice['country_destination']==253)
																			 	 $setHtml.='<td></td>';						 
																				 
																				 $setHtml.='<td><strong>Total</strong></td>';
																			if($invoice['country_destination']==253)				
																				 $setHtml.='<td></td>';	
																				
																				if($invoice['country_destination']!=253)
																					$setHtml.='<td colspan="4"></td>';				  
																			  
																			   $setHtml.='<td><strong>'.$tot_qty.'</strong></td>
																			   			  <td><strong>'.number_format($tot_gross_weight,2).'</strong></td>
																			   			  <td><strong>'.number_format($tot_net_weight,2).'</strong></td>';
																				
																				if($price==1)
																				{
																					$setHtml.= '<td style="text-align:right"><strong>'.$tot_rate.'</strong></td>';
																						if(!empty($menu_id) || !empty($menu_admin_permission))
																							{
																								$setHtml.='<td style="text-align:right"><strong>'.$tot_discount_rate.'</strong></td>';
																							}
																								
																						$setHtml.= '<td style="text-align:right"><strong>'.number_format($tot_amt,2).'</strong></td>';
																				}
																				
																			   if($invoice['country_destination']!=253)
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
																					'total_amount'=>$tot_amt,									
															);
															/*if($color['product_id']!='11' && $color['product_id']!='6' && $color['product_id']!='10' && $color['product_id']!='23' && $color['product_id']!='18' && $color['product_id']!='34')
															{
																$pouch_detail['pouch']=array('total_box'=>'');
																
															}*/
															
														/*	$detail_product_sea[]=array('Pouch Details'=>'',
															
															);*/
															//printr($pouch_detail);
										$setHtml.='</tbody>
									</table>
							</div>
						</div>
				';
			}
			//$i++;</form>
		}
	 //$setHtml.='<br class="break"">';
	//	printr($collapse_data);<br style="page-break-before: always;">  <form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
		$setHtml.='
						<div class="table-responsive"><br><br><br>
								<div style="width:730px;">
									<table class="table b-t table-striped text-small table-hover detail_table"> 
										<thead>							
											<tr>
												<th style="width:150px">Box Nos.</th>  
												<th style="150px">Page No.</th>
												<th style="width:100px">Quantity</th>
												<th style="width:100px">Gr. Wt in kgs</th>
												<th style="width:100px">Net. Wt in kgs</th>';
												if($price==1)
													$setHtml.='<th style="width:100px">Extended cost</th>
											</tr>
										</thead>
										<tbody>';
										$colordetails=$this->colordetails($invoice_no,$parent_id,'','',$limit);
									//	printr($colordetails);
										$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_amt=0;
										if(isset($collapse_data))
										{
											foreach($collapse_data as $dat)
											{
												$setHtml.='<tr>
																<td>'.$dat['box_no'].'</td>
																<td>'.$dat['page_no'].'</td>
																<td>'.$dat['qty'].'</td>
																<td>'.number_format($dat['gross_weight'],2).'</td>
																<td>'.number_format($dat['net_weight'],2).'</td>';
																
																
																if($price==1)
																	$setHtml.='<td>'.number_format($dat['total_amount'],2).'</td>
															</tr>
															';
												$tot_qty=$tot_qty+$dat['qty'];
												$tot_gross_weight=$tot_gross_weight+$dat['gross_weight'];
												$tot_net_weight=$tot_net_weight+$dat['net_weight'];
												$tot_amt=$tot_amt+$dat['total_amount'];
												
												
												
											}
										}
									//sonu add 14-4-2017
    										$alldetails=$this->getProductdeatils($invoice_no);
    										//$tot_qty_scoop=$tot_scoop_qty=$tot_scoop_rate=$total_amt_scoop=0;
											
											//added by [kinjal] on 12-5-2017 for other product in UK contory con 
											$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_box_no = '';
											$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series = '';
											$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper = 0;
											$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty = $tot_paper_qty = 0;
											$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_paper_rate = 0;
    											//printr($alldetails);
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
																$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
																$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
																$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
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
        										}
        									//[kinjal] END
        										//sonu END
										$setHtml.='<tr>
														<td></td>
														<td></td>
														<th>'.$tot_qty.'</th>
														<th>'.number_format($tot_gross_weight,2).'</th>
														<th>'.number_format($tot_net_weight,2).'</th>';
													//add sonu 14-4-201
														$insurance_rate =0;
													//end	
														if($invoice['country_destination']==252 && ucwords(decode($invoice['transportation']))=='Air')
														{
															//tran_charges_tot=round($invoice['tran_charges'],2);
													        //	printr($invoice['tran_charges'].'+'.$tot_amt);
													       
													       $insurance_rate= $tot_amt - $total_amt_scoop - $total_amt_roll -$total_amt_mailer - $total_amt_sealer - $total_amt_paper - $total_amt_storezo;
													       // printr($insurance_rate);
														
														
														//	$insurance=(($tot_amt*110/100+$tot_amt)*0.07)/100;  sonu comment 14-4-2017
														   
														    $insurance=(($insurance_rate*110/100+$insurance_rate)*0.07)/100;
														
														//	printr($insurance_rate.'*110/100+'.$insurance_rate.'0.07/100');
														
															$tot_amt = $tot_amt+$insurance;
														//	printr($insurance);
														}	
														
														if($price==1)
															$setHtml.='<th>'.number_format($tot_amt,2).'</th>
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
							<div class="table-responsive"><br><br><br>
									<div style="width:730px;">
										<table class="table b-t table-striped text-small table-hover detail_table"> 
											<thead>							
												<tr>
													<th style="width:150px">Detail</th>  
													<th style="150px">Total Boxes</th>
													<th style="width:100px">Quantity</th>
													<th style="width:100px">G.W.T</th>
													<th style="width:100px">N.W.T</th>';
													if($price==1)
														$setHtml.='<th style="width:100px">Extended cost AUD</th>
													
												</tr>
											</thead>
											<tbody>';
											$alldetails=$this->getProductdeatils($invoice_no);
											
											$f_p=$f_scoop=$f_roll=$f_mailer=$f_sealer=$f_storezo=$f_box=true;
											foreach($alldetails as $detail)
											{
												if($detail['product_id']!='11' && $detail['product_id']!='6' && $detail['product_id']!='10' && $detail['product_id']!='23' && $detail['product_id']!='18' && $detail['product_id']!='34')
												{
													
													$charge = $invoice['tran_charges']+$invoice['cylinder_charges'];										
													$boxDetail = $this->getIngenBox($invoice_no,$n=2,$charge);
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
													if($f_scoop==true)
													{
														$pouch_detail['scoop']=$boxDetail;
														$f_scoop=false;
													}
												}
												else if($detail['product_id']=='6')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_roll==true)
													{
														$pouch_detail['roll']=$boxDetail;
														$f_roll=false;
													}
												}
												else if($detail['product_id']=='10')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_mailer==true)
													{
														$pouch_detail['Mailer Bag']=$boxDetail;
														$f_mailer=false;
													} 
												}
												else if($detail['product_id']=='23')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_sealer==true)
													{
														$pouch_detail['Sealer Machine']=$boxDetail;
														$f_sealer=false;
													}
												}
												else if($detail['product_id']=='18')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_storezo==true)
													{
														$pouch_detail['storezo']=$boxDetail;
														$f_storezo=false;
													}
												}
												else if($detail['product_id']=='34')
												{
													$boxDetail = $this->getIngenBox($invoice_no,$detail['product_id']);
													if($f_box==true)
													{
														$pouch_detail['paper_box']=$boxDetail;
														$f_box=false;
													}
												}
												
												
											}
											//$pouch[]=$pouch_detail;
											//printr($pouch_detail);
											$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_amt=0;
											
											if(isset($pouch_detail))
											{
												foreach($pouch_detail as $key=>$data)
												{
													//printr($data);
													$setHtml.='<tr>
																	<td>'.$key.'</td>
																	<td>'.$data['total_box'].'</td>
																	<td>'.$data['qty'].'</td>
																	<td>'.number_format($data['g_wt'],2).'</td>
																	<td>'.number_format($data['n_wt'],2).'</td>';
																	
																	if($key=='Pouch Detail')
																		$data['total']=$data['total']+$invoice['cylinder_charges'];
																	
																	if($price==1)
																		$setHtml.='<td>'.number_format($data['total'],2).'</td>
																</tr>
																';
													$tot_qty=$tot_qty+$data['qty'];
													$tot_gross_weight=$tot_gross_weight+$data['g_wt'];
													$tot_net_weight=$tot_net_weight+$data['n_wt'];
													$tot_amt=$tot_amt+$data['total'];
													
												}
											}
											$setHtml.='<tr>
															<td></td>
															<td></td>
															<th>'.$tot_qty.'</th>
															<th>'.number_format($tot_gross_weight,2).'</th>
															<th>'.number_format($tot_net_weight,2).'</th>';
															if($price==1)
																$setHtml.='<th>'.number_format($tot_amt,2).'</th>
														</tr>
										</tbody>
									</table>
								</div>
							</div>
						';
			 }
			 $setHtml .='</form>';
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
	$tot_qty=0;$tot_gross_weight=0;$tot_net_weight=0;$tot_rate=0 ;
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
		//printr($invoice);
		$setHtml='';
		$setHtml .='<form class="form-horizontal" method="post" name="formmy" id="formmy" enctype="multipart/form-data">
						<div class="table-responsive">
							<div style="height:5%;">';//width:100%;margin:40px 50px;padding:10px;
							$setHtml .= '<table class="table" border="0" style=" font-size: 12px; ">';//width:700px;height:1084px
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
												$setHtml .= '<tr>';//width:370px;padding:25px;
											 $setHtml .= '<td style="border:none;padding-left: 55px;padding-top:10px;">
																<h4><br><u>EXPORTER:-</u></h4>	
																'.nl2br($fixdata['exporter']).'
																<h4><u>CONSIGNEE:-</u> </h4>
																<b>'.$invoice['customer_name'].'</b><br>'.nl2br($invoice['consignee']).'<br><span><img src="http://www.bcgen.com/demo/linear-dbgs.aspx?D='.$invoice['invoice_no'].'"></span>
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
					  //$setHtml .= '</tr>';
							$setHtml.='</table>
						</div>
					</div>
				</form>';
		//printr($setHtml);die;
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
			$inv_cond=" AND inv.order_type='commercial' ";
		elseif($user_type_id=='2' && $user_id=='68' && ($inv_status=='0' || $inv_status=='1'))
		    $inv_cond=" AND inv.order_type='sample' ";
		else
		    $inv_cond='';
		
		if($inv_status=='0')
		  $inv=' AND done_status!= 0';
		elseif($inv_status=='1')
		   $inv=' AND inv.date_added = "'.date("Y-m-d").'" AND done_status = 0';
		 elseif($inv_status=='2')
		    $inv=' AND done_status!= 0 AND order_user_id="'.$admin_user_id.'" AND import_status!=2 ';
		 elseif($inv_status=='3')
			$inv=' AND done_status!= 0 AND order_user_id="'.$admin_user_id.'"  AND import_status=2';
		
		$del_status='';
		if($delivery_mail!='')
		{
			$del_status = ' AND goods_delivery_email_status=0';
			$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		}
		
		if($user_type_id == 1 && $user_id == 1){
		//	$sql = "SELECT inv.*,c.country_name,c.foreign_port,c.country_id FROM " . DB_PREFIX . "invoice as inv,country as c WHERE c.country_id=inv.final_destination AND inv.is_delete = 0  $inv $del_status" ;
		        $sql = "SELECT inv.* FROM " . DB_PREFIX . "invoice as inv WHERE inv.is_delete = 0  $inv $del_status" ;
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
			$status_cond='';
			if($inv_status!='2' && $inv_status!='3')
				$status_cond="inv.user_id = '".(int)$set_user_id."' AND inv.user_type_id = '".(int)$set_user_type_id."' $str AND";
			
			//$sql = "SELECT inv.*,c.country_name,c.foreign_port,c.country_id FROM " . DB_PREFIX . "invoice as inv,country as c WHERE $status_cond c.country_id=inv.final_destination AND inv.is_delete = 0 $inv $del_status $inv_cond" ;
			    $sql = "SELECT inv.* FROM " . DB_PREFIX . "invoice as inv WHERE $status_cond   inv.is_delete = 0 $inv $del_status $inv_cond" ;
		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['invoice_no'])){				
			$sql .= " AND invoice_no LIKE '%".$filter_data['invoice_no']."%' ";		
			}
			if(!empty($filter_data['country_id'])){
				$sql .= " AND country_destination = '".$filter_data['country_id']."' ";		
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
		
		if($limit!='')
		   $sql .= " LIMIT ".$limit;
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
	
	public function getTotalInvoice($user_type_id,$user_id,$filter_data=array(),$inv_status,$admin_user_id='')
	{
		if($user_type_id=='2' && ($user_id=='39' || $user_id=='40') && ($inv_status=='0' || $inv_status=='1'))
			$inv_cond=" AND order_type='commercial' ";
		elseif($user_type_id=='2' && $user_id=='68' && ($inv_status=='0' || $inv_status=='1'))
		    $inv_cond=" AND order_type='sample' ";
		else
		    $inv_cond='';
			
   		if($inv_status=='0')
		  $inv=' done_status!= 0 AND';
		elseif($inv_status=='1')
		   $inv=' date_added = "'.date("Y-m-d").'" AND done_status = 0 AND'; 
		elseif($inv_status=='2')
			$inv=' done_status!= 0 AND order_user_id="'.$admin_user_id.'" AND import_status!=2 AND';
		elseif($inv_status=='3')
		    $inv=' done_status!= 0 AND order_user_id="'.$admin_user_id.'" AND import_status=2 AND ';
			//printr( $inv);
		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice` WHERE $inv is_delete = 0";
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
			$status_cond='';
			if($inv_status!='2' && $inv_status!='3')
				$status_cond=" user_id = '".(int)$set_user_id."' AND user_type_id = '".(int)$set_user_type_id."' $str AND";
			
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice` WHERE $status_cond  $inv is_delete = 0 $inv_cond" ;
		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['invoice_no'])){
				
			$sql .= " AND invoice_no LIKE '%".$filter_data['invoice_no']."%' ";		
			}
			if(!empty($filter_data['country_id'])){
				$sql .= " AND country_destination = '".$filter_data['country_id']."' ";		
			}
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND customer_name LIKE '%".$filter_data['customer_name']."%' ";
				//printr($sql);		
			}
			if(!empty($filter_data['email'])){
				$sql .= " AND email LIKE '%".$filter_data['email']."%' ";		
			}
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
	//	printr($sql);
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function updateInvoiceStatus($status,$data)
	{
		//printr($data);
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

		//printr($data);die;
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
				$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN multi_custom_order_id as mcoi ON(mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE (".$cust_cond.") AND mco.dispach_by LIKE '".$date_cond."' ";
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
				if($userEmployee){
					$str = ' OR ( mco.added_by_user_id IN ('.$userEmployee.') AND mco.added_by_user_type_id = 2 ) ';
				}
				//$str .= ' ) ';
				$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN " . DB_PREFIX ."multi_custom_order_id mcoi ON (mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE  (".$cust_cond.") AND (mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str AND mco.done_status ='0'  AND mco.dispach_by LIKE '".$date_cond."'";
				//echo $sql;
			}
		}else{
			$sql = "SELECT $getData,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode FROM " . DB_PREFIX ."multi_custom_order mco,multi_custom_order_id mcoi,address adr WHERE
			 mco.multi_custom_order_id=mcoi.multi_custom_order_id AND mco.done_status ='0' AND (".$cust_cond.") AND mcoi.shipping_address_id=adr.address_id AND mco.dispach_by LIKE '".$date_cond."'";
		}
		//echo $sql;
		//die;
		$data = $this->query($sql);
		//printr($data);
		
		return $data->rows;
	}
	
	public function getCustomOrderQuantity($custom_order_id){
		//printr($custom_order_id);die;
		$paking_price=$this->getCustomOrderPackingAndTransportDetails($custom_order_id);
		$data = $this->query("SELECT mco.custom_order_id,mco.multi_custom_order_id,mco.currency_price,mco.status,mco.shipment_country_id,mco.currency,mco.custom_order_status,mco.custom_order_type,mcoq.custom_order_quantity_id,mco.product_id,p.product_name, mcoq.custom_order_id,mcoq.discount, printing_effect,mcoq.quantity,mco.height,mco.width,mco.gusset,mco.volume,mco.dis_qty, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mco.layer,total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage FROM " . DB_PREFIX ."multi_custom_order_quantity as mcoq,multi_custom_order as mco,product as p WHERE (".$custom_order_id.") AND mcoq.custom_order_id=mco.custom_order_id AND mco.product_id=p.product_id ORDER BY mcoq.custom_order_quantity_id") ;
		//printr($data);die;
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
			$cond = 'AND pto.admin_user_id = '.$orders_user_id.'';
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
		$sql = "SELECT sodh.stock_order_dispatch_history_id,sodh.dis_qty,so.gen_order_id,t.reference_no,t.client_id,t.expected_ddate,t.template_order_id,t.quantity,p.product_id,p.email_product,p.product_name,pt.title,pts.width,pts.height,pts.gusset,pto.shipment_country,pto.ship_type as pto_ship_type,pto.admin_user_id,t.transport,pc.pouch_color_id,
pts.volume,t.note,c.country_name,cu.currency_code,pc.email_color,pc.color,t.user_id,t.user_type_id,t.price,pts.valve,cd.client_name,pts.product_template_size_id,pts.zipper,pts.spout,pts.accessorie,pts.description,t.ship_type,pt.product_template_id,t.address,t.date_added,pt.transportation_type,t.product_template_order_id,sos.review,sos.track_id,sos.date,sos.courier_id,pto.order_id,sos.process_by,sos.dispach_by,pts.quantity1000,pts.quantity2000,pts.quantity5000,pts.quantity10000,t.price_uk,t.order_type FROM " .DB_PREFIX . "stock_order_dispatch_history as sodh,template_order t,currency as cu,product as p,product_template pt,product_template_size as pts,country as c,pouch_color as pc, product_template_order as pto,stock_order_status as sos, courier as co,client_details as cd,stock_order as so  WHERE  t.product_id = p.product_id AND pt.product_template_id = t.template_id AND so.stock_order_id=t.stock_order_id AND so.client_id=t.client_id AND pto.admin_user_id=so.admin_user_id AND  t.template_order_id=sos.template_order_id  ".$status." AND pt.currency = cu.currency_id AND t.is_delete = 0  ".$cond." 
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
		//echo $sql;//die;
	//	$sql .= " GROUP BY st.template_order_id"; 
		//echo $sql;
		//die;
		/*if (isset($data['sort'])) {
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
		}*/
		//echo '<br>'.$sql;//die;
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
		
		$order='';
		if($order_type!='')
			$order = ' AND t.order_type = "'.$order_type.'"';
			
		if($user_type_id==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'";
			$dataadmin = $this->query($sqladmin);
			$con =  'AND st.admin_user_id = "'.$dataadmin->row['user_id'].'"';
			$cust_con =  'AND mcoi.admin_user_id = "'.$dataadmin->row['user_id'].'"';
			$admin_user_id=$dataadmin->row['user_id'];
		}
		elseif($user_type_id==4)
		{
			$con =  "AND st.admin_user_id = '".$user_id."'";
			$cust_con =  "AND mcoi.admin_user_id = '".$user_id."'";
			$admin_user_id= $user_id;
		}
		
		$stock_sql = "SELECT st.gen_order_id as order_num FROM template_order t,stock_order st,stock_order_dispatch_history as sodh WHERE t.is_delete = 0 AND ( (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=0)) AND t.status=1 AND st.stock_order_id=t.stock_order_id AND st.client_id=t.client_id ".$con." ".$order." AND sodh.dispach_by LIKE '".$date_cond."'  GROUP BY st.stock_order_id, st.admin_user_id";
		$stock_data = $this->query($stock_sql);
	//echo $stock_sql;
		$cust_data=array();
		//$cust_data->num_rows=0;
		//if($order_type!='sample')
	//	{
			$cust_sql = "SELECT mcoi.multi_custom_order_number as order_num FROM multi_custom_order mco,multi_custom_order_id as mcoi WHERE mco.multi_custom_order_id=mcoi.multi_custom_order_id ".$cust_con." AND mco.dispach_by LIKE '".$date_cond."' GROUP BY mcoi.multi_custom_order_id, mcoi.admin_user_id";
			//echo $cust_sql;
			$cust_data = $this->query($cust_sql);
		//}
		$custom_imp = '';
		$stock_imp='';
		$custom_ar='';
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
		//printr($trans);die;
		$import='';
		if(strtolower($trans[1])=='air')
			$import = ',import_status=1';
		
		$sql = "INSERT INTO `" . DB_PREFIX . "invoice` SET invoice_no = '".$invoice_no."',invoice_date = '".date("Y-m-d")."',buyers_orderno='".$data['uni_ids']."',uk_ref_no='".$data['uni_ref_ids']."',consignee='".$user_detail['company_address']."',customer_name = '".$user_detail['company_name']."',email = '".$user_detail['email']."',port_discharge='".$data['ship_country']."',curr_id='".$curr_id."',HS_CODE='39232990',pre_carrier='3',port_load='3',transportation='".encode(strtolower($trans[1]))."',order_type='".$data['order_type']."',status = '1',date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',final_destination='".$data['ship_country']."',country_destination='".$data['ship_country']."',is_delete=0,order_user_id='".$user_detail['user_id']."' $import";
		//echo $sql.'<br>Invoice query</br>';die;
		
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
							$final_rate_stock =number_format( ($data['rate_'.$template_order_id] - $dis_rate),3);
							
						}else{
							$discount_rate = $international_branch_data['stock_discount_sea'];
							
							$final_rate_stock = $data['rate_'.$template_order_id] ;
							  
						}
				}
			
			//printr($final_rate_stock.'finalrate'. $discount_rate);//die;
				
						//sonu end 1-4-2017
					//uncomment  by sonu 15-7-2017
				$this->query("UPDATE template_order SET done_status='1' WHERE template_order_id ='".$template_order_id."'");
				
				
				/*$sql2 = "Insert into invoice_product Set invoice_id='".$invoice_id."',product_id='".$data['product']."', valve = '".$data['valve']."', zipper = '".$data['zipper']."', spout = '".$data['spout']."',make_pouch='".$data['make']."', accessorie = '".$data['accessorie']."',net_weight='".$data['netweight']."',measurement_two='".$data['measurement']."',buyers_o_no='".$data['buyers_o_no']."',item_no='".$data['item_no']."', date_added = NOW(), date_modify = NOW(), is_delete = 0"; */
				$sql2 = "Insert into invoice_product Set invoice_id='".$invoice_id."',product_id='".$data['product_id_'.$template_order_id]."', valve = '".$data['valve_id_'.$template_order_id]."', zipper = '".encode($data['zipper_id_'.$template_order_id])."', spout = '".encode($data['spout_id_'.$template_order_id])."',make_pouch='".$data['make_id_'.$template_order_id]."', accessorie = '".encode($data['accessorie_id_'.$template_order_id])."',buyers_o_no = '".$data['stock_order_no_'.$template_order_id]."',measurement_two='1',ref_no='".$data['reference_no_'.$template_order_id]."',date_added = NOW(), date_modify = NOW(), is_delete = 0";
				//echo $sql2.'<br>product query for stock</br>';
				
				$data2=$this->query($sql2);
				$invoice_product_id = $this->getLastId();
	
				/*$sql3 = "Insert into invoice_color Set invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$color['color']."',color_text='".$clr_txt."', rate ='".$color['rate']."', qty = '".$color['qty']."', size = '".$color['size']."',  measurement = '".$color['measurement']."',dimension='".$dim."',net_weight='".$color['net_weight']."',date_added = NOW(), date_modify = NOW(), is_delete = 0";*/
				
				//chage sonu 1-4-2017
				$sql3 = "Insert into invoice_color Set invoice_id='".$invoice_id."',invoice_product_id='".$invoice_product_id."',color = '".$data['pouch_color_id_'.$template_order_id]."', rate ='".$final_rate_stock."',dis_rate = '".$data['rate_'.$template_order_id]."', discount_rate_percentage ='".$discount_rate."', qty = '".$data['dis_qty_'.$template_order_id]."',rack_status='".$data['dis_qty_'.$template_order_id]."', size = '".$data['size_'.$template_order_id]."',  measurement = '".$data['mea_'.$template_order_id]."',dimension='".$data['dimension_'.$template_order_id]."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 			
				//echo '<br>'.$sql3.'<br>color query for stock</br>';
				//die;
				$data3=$this->query($sql3);
				$invoice_color_id = $this->getLastId();
				$inv_id[]=$invoice_id."==".$invoice_product_id."==".$invoice_color_id;
			}
		}
		//sdie;
		//printr($data['multi_custom_order_id']);
		//printr($multi_custom_order_id_arr);
		if(isset($data['multi_custom_order_id']))
		{
			
			foreach($multi_custom_order_id_arr as $multi_custom_order_id)
			{
			
				$cust_array = explode("==",$multi_custom_order_id);
				
				$multi_custom_order_id_1 = $cust_array[0];
				$custom_order_id = $cust_array[1];
			//uncomment  by sonu 15-7-2017
				$this->query("UPDATE multi_custom_order SET done_status='1' WHERE multi_custom_order_id ='".$multi_custom_order_id."'");
				//sonu add 1-4-2017
				
				if(strtolower($trans[1])=='air')
				{
					if($international_branch_data['custom_discount_air'] !='0')
						{
								$discount_rate = $international_branch_data['custom_discount_air'];
								$dis_rate = number_format((($data['cust_rate_'.$custom_order_id] * $international_branch_data['custom_discount_air']) / 100),3 );					
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
								$final_rate_custom =number_format( ($data['cust_rate_'.$custom_order_id] - $dis_rate),3);
				
						}else{
								$discount_rate = $international_branch_data['custom_discount_sea'];
								$final_rate_custom = $data['cust_rate_'.$custom_order_id] ;
								
						}
				}
					//printr($final_rate_custom.'finalrate'. $discount_rate);
			//	///	printr($final_rate_custom);
				//die; //sonu End
				
				$sql4 = "Insert into invoice_product Set invoice_id='".$invoice_id."',product_id='".$data['cust_product_id_'.$custom_order_id]."', valve = '".$data['cust_valve_id_'.$custom_order_id]."', zipper = '".encode($data['cust_zipper_id_'.$custom_order_id])."', spout = '".encode($data['cust_spout_id_'.$custom_order_id])."',make_pouch='".$data['cust_make_id_'.$custom_order_id]."', accessorie = '".encode($data['cust_accessorie_id_'.$custom_order_id])."',ref_no='".$data['reference_no_'.$custom_order_id]."',buyers_o_no = '".$data['multi_cust_order_no_'.$custom_order_id]."',measurement_two='1',date_added = NOW(), date_modify = NOW(), is_delete = 0";
				//echo $sql4.'<br>product query for custom</br>';
				$data4=$this->query($sql4);
				$cust_invoice_product_id = $this->getLastId();
				
				//sonu change query 1-4-2017
				$sql5 = "Insert into invoice_color Set invoice_id='".$invoice_id."',invoice_product_id='".$cust_invoice_product_id."',color = '".$data['cust_pouch_color_id_'.$custom_order_id]."', rate ='".$final_rate_custom."',dis_rate = '".$data['cust_rate_'.$custom_order_id]."', discount_rate_percentage ='".$discount_rate."', qty = '".$data['cust_dis_qty_'.$custom_order_id]."',rack_status='".$data['cust_dis_qty_'.$custom_order_id]."', size = '".$data['cust_size_'.$custom_order_id]."',  measurement = '".$data['cust_mea_'.$custom_order_id]."',dimension='".$data['cust_dimension_'.$custom_order_id]."',date_added = NOW(), date_modify = NOW(), is_delete = 0"; 	
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
	//	printr($post);
		//die;
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
				$sql="UPDATE invoice_product SET net_weight='".$post['netweight_'.$template_order_id]."' WHERE invoice_product_id ='".$stock[1]."'" ;
				$this->query($sql);
				
				$sql2="UPDATE invoice_color SET net_weight='".$post['netweight_'.$template_order_id]."' $str   WHERE invoice_color_id ='".$stock[2]."'" ;
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
				
				$sql="UPDATE invoice_product SET net_weight='".$post['netweight_cust_'.$custom_order_id]."' WHERE invoice_product_id ='".$cust[1]."'" ;
				$this->query($sql);
				
				$sql2="UPDATE invoice_color SET net_weight='".$post['netweight_cust_'.$custom_order_id]."',color_text='".$post['job_card_name_'.$custom_order_id]."',measurement='".$measurement."' WHERE invoice_color_id ='".$cust[2]."'" ;
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
	public function updateuploadstatus($invoice_id,$customer_name)
	{
		$sql = "UPDATE `" . DB_PREFIX . "invoice` SET done_status='2' WHERE invoice_id = '".$invoice_id."'";	
		//$sql = "UPDATE `" . DB_PREFIX . "invoice` SET done_status='2' WHERE customer_name = '".$customer_name."'";	
		
		$data = $this->query($sql);	
		//printr($data);	
	}
	public function getProductdeatilsForSample($invoice_no)
	{
		$sql="SELECT ip.*,ic.*,p.product_name FROM invoice_product as ip,invoice_color as ic,product as p WHERE ip.invoice_id='".$invoice_no."' AND ip.invoice_product_id=ic.invoice_product_id AND p.product_id=ip.product_id";
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
		$sql = "UPDATE `" . DB_PREFIX . "invoice_color` SET $cond WHERE invoice_color_id IN (".$im.")";
		//echo $sql;	
		$data = $this->query($sql);	
	}
	public function shipedMailDetail($buyers_orderno,$order_uder_id,$date_added)
	{
		$stock_o_no = $custom_o_no = '';
			
			//$_POST['date_added']='';
			$order_nos = explode(",",$buyers_orderno);
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
			$sqlcol="UPDATE `" . DB_PREFIX . "invoice_color` SET rate_with_proportion='".$rate_por."',total_import_charges='".$import_charges."' WHERE invoice_id = '".$data['gen_invoice_id']."' AND invoice_color_id= '".$col['invoice_color_id']."' ";
			//echo $sqlcol.'</br>';
			$this->query($sqlcol);
		}
		//die;
		$sql="UPDATE `" . DB_PREFIX . "invoice` SET CIF_amt='".$data['cifamount']."',FOB_amt='".$data['fobamount']."',custom_duty='".$data['customduty']."',voti='".$data['voti']."',
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
		$sql_data = "UPDATE `" . DB_PREFIX . "invoice` SET custom_duty_deviation_per='".$dev_custom_per."',GST_on_import_deviation_per='".$dev_gst_per."',other_charges_deviation_per='".$dev_other_per."',clearing_charges_deviation_per='".$dev_clearing_per."' WHERE invoice_id = '".$data['gen_invoice_id']."'";
		$this->query($sql_data);
		//sonu add query 12/12/2016
		//$sql_data="INSERT INTO deviation_report set invoice_id='".$data['gen_invoice_id']."',custom_duty_charge='".$custom_duty."',gst_on_import_charge ='".$gst_on_import."',other_charge='".$other_charsges_by_user."',clearing_charge='".$clearing_charges."',date_added = NOW(), date_modify = NOW(),status=1, is_delete = 0,close_status=0";
		//$data_insert = $this->query($sql_data);
		//echo $sql_data;
		
	}
	
	public function convertInPurchase($data)
	{
		$sql = "UPDATE `" . DB_PREFIX . "invoice` SET import_status='2',purchase_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',purchase_user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' WHERE invoice_id = '".$data['con_invoice_id']."'";	
		$data = $this->query($sql);	
	}
	
	//sonu 10/12/2016
	public function sendemailtrackid($data)
	{
		
	                
		$sql = "UPDATE `" . DB_PREFIX . "invoice` SET track_id='".$data['trackid']."' WHERE invoice_id = '".$data['invoiceid']."'";	
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
	
			$sql="SELECT invoice_no,invoice_id FROM invoice WHERE import_status = 0 AND order_user_id='".$set_user_id."' AND transportation ='c2Vh' ";
			
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
		$scoop_no = $roll_no = $mailer_no = $sealer_no = $storezo_no = $paper_box_no = '';
		$scoop_series = $roll_series = $mailer_series = $sealer_series = $storezo_series = $paper_series = '';
		
		$total_amt_scoop = $total_amt_roll = $total_amt_mailer = $total_amt_sealer = $total_amt_storezo = $total_amt_paper = 0;
		
		
		$tot_scoop_qty = $tot_roll_qty = $tot_mailer_qty = $tot_sealer_qty = $tot_storezo_qty = $tot_paper_qty = 0;
		$tot_scoop_rate = $tot_roll_rate = $tot_mailer_rate = $tot_sealer_rate = $tot_storezo_rate = $tot_paper_rate = 0;
		$abcd = 'A';
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
				$scoop_series = $abcd;
			}
			else if($details['product_id']=='6')
			{
				$tot_qty_roll=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_roll_qty = $tot_roll_qty + $tot_qty_roll['total']; 
				$tot_roll_rate = $tot_roll_rate + $tot_qty_roll['rate'];
				$total_amt_roll = $total_amt_roll + $tot_qty_roll['tot_amt'];
				$roll_no = '1';
				$roll_series = $abcd;
			}
			else if($details['product_id']=='10')
			{
				$tot_qty_mailer=$this->getPlasticScoopQty($details['product_id'],$details['invoice_product_id'],$invoice_no);
				$tot_mailer_qty = $tot_mailer_qty + $tot_qty_mailer['total']; 
				$tot_mailer_rate = $tot_mailer_rate + $tot_qty_mailer['rate'];
				$total_amt_mailer = $total_amt_mailer + $tot_qty_mailer['tot_amt'];
				$mailer_no = '1';
				$mailer_series = $abcd;
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
				$paper_box_no = '1';
				$paper_series = $abcd;
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
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									//$total_rate_val=$tot_scoop_rate;

									$total_amt_val=$invoice_qty['tot'];	
								}
								if($roll_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									//printr($tot_roll_rate);
									//$total_rate_val=$tot_roll_rate;
									$total_amt_val=$invoice_qty['tot'];	
								}
								if($mailer_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									//$total_rate_val=$tot_mailer_rate;
									$total_amt_val=$invoice_qty['tot'];
								}
								if($sealer_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									//$total_rate_val=$tot_sealer_rate;
									$total_amt_val=$invoice_qty['tot'];
								}
								if($storezo_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									//$total_rate_val=$tot_storezo_rate;
									$total_amt_val=$invoice_qty['tot'];
									
								}
								if($paper_box_no == '1')
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									//$total_rate_val=$tot_paper_rate;
									$total_amt_val=$invoice_qty['tot'];
									
								}
								else
								{
									$total_qty_val=$invoice_qty['total_qty']-($tot_scoop_qty+$tot_roll_qty+$tot_mailer_qty+$tot_sealer_qty+$tot_storezo_qty+$tot_paper_qty);
									///$total_rate_val=$invoice_qty['rate'];
									//$total_amt_val=$total_qty_val*$invoice_qty['rate'];
									$total_amt_val=$invoice_qty['tot'];
								}
			
			
								$rate_per = number_format((($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo))/$total_qty_val),8);
								//$amt = ($total_amt_val-$invoice['tran_charges'])-$invoice['cylinder_charges']-($total_rate_val*$tot_scoop_qty)-($total_rate_val*$tot_roll_qty)-($total_rate_val*$tot_mailer_qty)-($total_rate_val*$tot_sealer_qty)-($total_rate_val*$tot_storezo_qty)-($total_rate_val*$tot_paper_qty);
								
								$amt = ($total_amt_val-($invoice['tran_charges']+$invoice['cylinder_charges']+$total_amt_paper+$total_amt_roll+$total_amt_scoop+$total_amt_mailer+$total_amt_sealer+$total_amt_storezo));
								
								//printr($amt);
								
								//printr('('.$total_amt_val.'-'.$invoice['tran_charges'].')-'.$invoice['cylinder_charges'].'-('.$total_rate_val.'*'.$tot_scoop_qty.')-('.$total_rate_val.'*'.$tot_roll_qty.')');

								$air_rate = $amt * $currency['price'];
		
				 if($invoice['country_destination']=='252' && ucwords(decode($invoice['transportation']))=='Air')
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
				//printr($final_amount);
				$sql = "UPDATE " . DB_PREFIX . "invoice SET invoice_total_amount = '" .$final_amount. "'WHERE invoice_id ='".$invoice_no."'";
				$data=$this->query($sql);
				
				
				
      
	 }
	 
	public function getIngenBox($invoice_id,$product_id=0,$charge=0)
	{
		
		if($product_id!='2')
			$pro_id= " AND ip.product_id = '".$product_id."'";
		else if($product_id=='2')
			$pro_id= " AND ip.product_id NOT IN (10,23,11,18,6,34)";
				
		$sql_pro="SELECT SUM(ic.qty) as total_qty,sum(ic.rate) as total_rate,sum(ic.rate*ic.qty) as total,ic.qty_in_kgs,ic.rate_in_kgs,GROUP_CONCAT(DISTINCT ic.invoice_color_id) as group_id_color FROM `invoice_product`as ip,invoice_color as ic WHERE ip.invoice_id='".$invoice_id."' AND ic.invoice_id='".$invoice_id."' AND ip.`invoice_product_id`=ic.`invoice_product_id` $pro_id " ;
		$data_pro = $this->query($sql_pro);
		//echo $sql_pro;
		//
		$sql="SELECT COUNT(*) as total_box,SUM(ig.net_weight) as net, SUM(ig.net_weight+ig.box_weight) as gross FROM in_gen_invoice as ig,invoice_product as ip WHERE ig.invoice_id='".$invoice_id."' AND ig.invoice_product_id =ip.invoice_product_id AND ig.is_delete='0' $pro_id AND ip.invoice_id='".$invoice_id."' AND ig.parent_id='0' ";
		$data = $this->query($sql);
	//printr($sql);
		
		//$sql_count="";
		
		if($data->num_rows)
		{
			//return $data->row;
			$d = array( 'total_box'=>$data->row['total_box'],
						 'qty' => $data_pro->row['total_qty'],
						 'g_wt' => $data->row['gross'],
						 'n_wt' => $data->row['net'],
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
	
	public function getProductCode($invoice_product_id)
	{
	//	printr($invoice_product_id);
		$sql="SELECT ip.*,ic.* FROM invoice_product as ip , invoice_color as ic WHERE ip.invoice_product_id='".$invoice_product_id."' AND ic. invoice_product_id ='".$invoice_product_id."'";
		
		$data=$this->query($sql);
	//	printr($data);
		
		$sql_detail ="SELECT product_code,description FROM product_code WHERE product ='".$data->row['product_id']."' AND valve ='".$data->row['valve']."'AND zipper ='".decode($data->row['zipper'])."'AND spout ='".decode($data->row['spout'])."'AND accessorie ='".decode($data->row['accessorie'])."'AND make_pouch ='".$data->row['make_pouch']."'  AND	volume ='".$data->row['size']."' AND measurement ='".$data->row['measurement']."' AND color = '".$data->row['color']."'" ; 
	//	echo $sql_detail ;
		//die;
		
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
		$sql = "UPDATE `".DB_PREFIX."invoice` SET remarks='".$data['remarks']."' , add_remark_status='1'WHERE invoice_id='".$data['invoice_id']."'";			
		$data=$this->query($sql);		
			
			
	}
	
	public function updateRateForCustomOrder($invoice_rate,$invoice_color_id)
	{
		$sql = "UPDATE ".DB_PREFIX."invoice_color SET  rate ='".$invoice_rate."' WHERE invoice_color_id='".$invoice_color_id."'";
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
		
		$sql="Select * from  in_gen_invoice WHERE invoice_id='".$invoice_id."' ORDER BY in_gen_invoice_id ASC";
		$data=$this->query($sql);
		$boxno=$this->in_gen_box_no($invoice_id);
			if(!empty($boxno))
				$box_no=$boxno+1;
			else
				$box_no=1;
		$i=0;
		foreach($data->rows as $r)
		{
			$k=$i+1;
			$sql1="UPDate in_gen_invoice SET box_no= '".$k."' WHERE in_gen_invoice_id=".$r['in_gen_invoice_id']."";	
			$this->query($sql1);
			$i++;
		}
	}
	//[kinjal] : 29-7-2017
	public function InoutLable($invoice_id,$parent_id=0,$from,$to)
	{	
		if($parent_id==0)
		{
		    $sql="select ic.dis_rate,ic.ref_no,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color as ii,pouch_color as c,template_measurement as tm,invoice_product as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice WHERE parent_id='".$parent_id."' AND box_no BETWEEN '".$from."' AND '".$to."') as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC "; 
		}
		else
		{
			$sql="select ic.dis_rate,ic.ref_no,ig.qty as genqty,ic.dimension,ic.color,ic.invoice_color_id,ic.rate,ic.valve,ic.buyers_o_no,ic.item_no,ig.in_gen_invoice_id,ic.zipper,ic.size,ic.net_weight,ig.box_no,ig.box_unique_number,ig.box_weight,ig.pallet_id,ic.measurement,ic.invoice_product_id,ic.product_name,ic.product_id,ic.color_text,ic.pouch_color_id from (select ii.qty,ii.size,ii.rate,tm.measurement,ii.invoice_product_id,p.product_name,
p.product_id,ii.invoice_color_id,ip.valve,ip.zipper,ip.buyers_o_no,ip.ref_no,ip.item_no,ii.net_weight,ii.invoice_id,ii.dis_rate,ii.dimension,c.color,ii.color_text,c.pouch_color_id from invoice_color as ii,pouch_color as c,template_measurement as tm,invoice_product as ip,product as p where c.pouch_color_id=ii.color AND tm.product_id=ii.measurement AND ii.invoice_product_id=ip.invoice_product_id AND ip.product_id=p.product_id ) as ic right join (select qty,in_gen_invoice_id,invoice_color_id,box_weight,box_no,box_unique_number,pallet_id from in_gen_invoice WHERE parent_id='".$parent_id."') as ig on (ic.invoice_color_id=ig.invoice_color_id ) where ic.invoice_id='".$invoice_id."' ORDER BY ig.box_no ASC "; 

		}
		//echo $sql.'<br><br>';die;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	//END [kinjal]
}
?>