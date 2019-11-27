<?php
class client_generated_proforma_list extends dbclass{
//mansi -->
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
		
		public function getProformaInvoice($proforma_id) {
			$sql = "SELECT * FROM standupp_client_proforma.proforma_invoice where proforma_id = '".$proforma_id."' AND is_delete = '0'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->rows;
			}
			else {
				return false;
			}
		}		
		public function getColorDetails($proforma_id,$proforma_invoice_id) {
			$sql = "select pc.*,poc.color as color_name from standupp_client_proforma.proforma_color as pc,pouch_color as poc  WHERE pc.color=poc.pouch_color_id AND pc.proforma_id='".$proforma_id."' AND pc.proforma_invoice_id = '".$proforma_invoice_id."'";
			$data = $this->query($sql);
			if($data->num_rows) {
				return $data->rows;
			}
			else {
				return false;
			}
		}
		
		public function getTotalInvoice($filter_data=array(),$user_type_id='',$user_id='')
		{
			if($user_type_id=='1' && $user_id=='1')
			{
				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "standupp_client_proforma.proforma as p,country as c WHERE c.country_id=p.destination AND p.is_delete = 0 ";
			}
			
			if(!empty($filter_data)){
				if(!empty($filter_data['customer_name'])){
					$sql .= " AND customer_name LIKE '%".$filter_data['customer_name']."%' ";		
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
		
		public function getInvoices($data,$filter_data=array(),$user_type_id='',$user_id='')
		{	
			if($user_type_id=='1' && $user_id=='1')
			{
				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "standupp_client_proforma.proforma as p,country as c WHERE c.country_id=p.destination AND p.is_delete = 0 ";
			}
		
			if(!empty($filter_data)){
				if(!empty($filter_data['customer_name'])){
					$sql .= " AND customer_name LIKE  '%".$filter_data['customer_name']."%'";		
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
			
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}
		
		public function updateProformaStatus($status,$data){
			if($status == 0 || $status == 1){
				$sql = "UPDATE standupp_client_proforma.proforma SET status = '" .(int)$status. "',  date_modify = NOW() WHERE proforma_id IN (" .implode(",",$data). ")";
				$this->query($sql);
			}elseif($status == 2){
				$sql = "UPDATE standupp_client_proforma.proforma SET is_delete = '1', date_modify = NOW() WHERE proforma_id IN (" .implode(",",$data). ")";
				//printr($sql);die;
				$this->query($sql);
			}
		}
		
		public function updateProStatus($proforma_id,$status_value){
			$sql = "UPDATE standupp_client_proforma.proforma SET status = '".$status_value."', date_modify = NOW() WHERE proforma_id = '" .(int)$proforma_id. "'";
			//echo $sql;
			$this->query($sql);
		}
		
		public function getProformaData($proforma_id){
			$sql = "SELECT p.*,b.* FROM " . DB_PREFIX . "standupp_client_proforma.proforma as p,bank_detail as b WHERE b.bank_detail_id=p.bank_id AND p.proforma_id = '" .(int)$proforma_id. "'";
			//echo $sql;
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
		function viewProformaInvoice($proforma_id) {
			$html ='';
			$proforma=$this->getProformaData($proforma_id);
			//printr($proforma);
			///die;
			$proforma_id=$proforma['proforma_id'];
			$proforma_inv=$this->getProformaInvoice($proforma_id);
			
			//$user_name=$this->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
			//printr($user_name);
			$show_vat='';
			
				$title='Consignor';
				$address='SWISS PAC PVT LTD</b><br>Padra Jambusar National highway<br>
								At Dabhasa vaillage,Pin 391440<br>Taluka.Padra, Dist.Vadodara(State Gujarat) India ';
				$sign='Swiss PAC PVT LTD';
				//$user=$this->getCompanyAdd($proforma['added_by_user_type_id'],$proforma['added_by_user_id']);
				$vat_no = $proforma['vat_no'];
				//$admin_vat_no = 'Vat No. : '.$user['vat_no'];
				$show_vat = 'Vat No. :'.$vat_no;

			$html .='<div style="text-align:center;border: 1px solid black;">PROFORMA INVOICE</div>
						<div style=" float: left;  border: 1px solid black;font-size: 18px;">
							<table style="">
								<tr>
									<td style="vertical-align: top;">
									
										<b>'.$title.'<br><br>'.$address.'<br> 
									</td>
									<td style="padding: 0px;">
										<table style="border: 1px solid black;" cellspacing="0px" cellpadding="10px" style=" border-spacing: 0px; ">
											<tbody><tr>
												<td valign="top"><b>Invoice No.&amp; Date</b></td>
												<td>'.$proforma['pro_in_no'].' &nbsp;/&nbsp; '.dateFormat(4,$proforma['invoice_date']).'<br><br><br><span class="barcode" >'.$proforma['pro_in_no'].'</span><br><br></td>
												</tr>
											<tr>
												<td><b>Proforma :</b></td>
												<td>&nbsp;'.dateFormat(4,$proforma['proforma']).'</td>
											</tr>
											<tr>
												<td><b>Buyers Date:</b></td>
												<td>'.dateFormat(4,$proforma['buyers_date']).'</td>
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
						
						<div style=" float: left;  border: 1px solid black;font-size: 18px;">
						
							<table>
								<tr>
									<td style="vertical-align: top;">
									
										<p><b>Consignee</b></p>
										<p>'.$proforma['customer_name'].'<br/>'.nl2br($proforma['address_info']).'<br/>Email : '.$proforma['email'].'<br>'.$show_vat.'</p>
									</td>
									<td style="padding: 0px;vertical-align: top;">
										<table cellspacing="0px" cellpadding="0px" style=" border-spacing: 0px; width: 100%;border: 1px solid black;padding: 0px;">
											<tbody><tr>
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
											 <tbody><tr>
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
					$html .='<div style=" float: left;  border: 1px solid black;">
							<table cellspacing="0px" cellpadding="10px" border="1" style=" border-spacing: 0px;font-size: 14px;">
								<tbody>
								<tr>
									<td width="5%"><div align="center"><b>Sr. No</b></div></td>
									<td width="60%"><div align="center"><b>Discription of Goods</b></div></td>
									<td width="10%"><b>Quantity In Units</b></td>
									<td width="15%"><b>Rate &nbsp;'.$currency['currency_code'].'</b></td>
									<td width="10%"><b>Amount &nbsp;'.$currency['currency_code'].'</b></td>
								</tr>';
								$total = 0;$total_rate=0; $final_total=0; $n=1;$total_excies_rate=0;$ex_per=array();$total_taxation_rate=0;$tax_per=array();$taxa=array();
					foreach($proforma_inv as $invoice_key=>$invoice){ 
					
								   $getProductId =$this->getClientProduct($invoice['product_id']);
									//printr($getProductId);
									//$getProductSpout = $this->getSpout(decode($invoice['spout']));
									//$getProductZipper = $this->getZipper(decode($invoice['zipper']));
									$zipper_name='';
									$valve_name='';
									$spout_name='';
									if($invoice['valve']=='With Valve')
										$valve_name=$invoice['valve'];
									if($getProductId['zipper_name']!='No zip')
										$zipper_name=$getProductId['zipper_name'];
									if($getProductId['spout_name']!='No Spout')
										$spout_name=$getProductId['spout_name'];
									//$getProductAccessorie = $this->getAccessorie(decode($invoice['accessorie']));
									if($invoice['product_id'] == 3)
									{
										$gusset = floatval($invoice['gusset']).'+'.floatval($invoice['gusset']);
									}
									else
									{
										$gusset = floatval($invoice['gusset']);
									}
									$quantity = $this->getColorDetails($proforma['proforma_id'],$invoice['proforma_invoice_id']);
								$html .='<tr><td valign="top">'.$n.'</td>
										<td><b>Size : </b>'.floatval($invoice['width']).' mm &nbsp;Width &nbsp;X&nbsp;'.floatval($invoice['height']).' mm &nbsp;Height &nbsp;';
											if($gusset>0)
												$html .='X&nbsp;'.$gusset.' mm';
											if($invoice['volume']>0)
												$html .=' ('.$invoice['volume'].')';
												$html .='<br><b>Product Description : </b>'.$getProductId['product_desc'].'<b>';
												$html .='<br><b>Make up of pouch :</b>'.$invoice['product_name'].'<b>&nbsp;'.$zipper_name.'&nbsp;'.$valve_name.' &nbsp;'.$spout_name.'</b><br>';
												foreach($quantity as $quantity_val) {
												//printr($quantity_val);
													$clr_text='';
													if($quantity_val['color']=='-1')
													{
														if($quantity_val['color_text']!='')
														$clr_text = "(".$quantity_val['color_text'] .")";
													}
										$html .='<b>Color : '.$quantity_val['color_name'].' '.$clr_text.'<br></b>';
										if($quantity_val['description']!='')
										{
											$html .='<b>Material Description : </b>'.$quantity_val['description'].'<br>';
										}
									}
									$html .='</td><td><br><br><br>';
									foreach($quantity as $quantity_val) {
										$total = $total+$quantity_val['quantity'] ;$total_qty = $quantity_val['quantity'];
										$html .=$total_qty.'<br>';
										if($quantity_val['description']!='')
										{
											$html .='<br>';
										}
									}
									$html .='</td><td><br><br><br>';
									foreach($quantity as $rate_val) { 
										$total_rate=$total_rate+$rate_val['rate'];$total_rt = $rate_val['rate'];
										$html .=$total_rt.'<br>';
										if($rate_val['description']!='')
										{
											$html .='<br>';
										}
									}
									
									$html.='</td><td><br><br><br>';
									foreach($quantity as $rate_val) {
										$total_amnt = $rate_val['quantity'] * $rate_val['rate'];
										$html.= $total_amnt.'<br>';
										//[kinjal] if con 22-12-2015
										if($rate_val['description']!='')
										{
											$html .='<br>';
										}
										$final_total=$final_total+$total_amnt;
									}
									$html .='</td></tr>';
									$n++;}
								if($proforma['freight_charges']!=0)
								{
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
									<td><p align="center"><b>'.$final_total.'</b></p></td>
								  </tr>';
							if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
					  		{
						 			$total_excies_rate = $final_total+($final_total*$proforma['excies_per']/100);
						 			$total_taxation = $total_excies_rate*$proforma['taxation_per']/100;
		  
								  $html.='<tr>
									<td></td>
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
							  <tr>
								<td></td>
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
  						
										
			$html .='<div style=" float: left;  border: 1px solid black;font-size: 16px;">
							<table cellspacing="0px" cellpadding="10px" border="1" style=" border-spacing: 0px;">
								<tbody><tr>
		<td colspan="2" valign="top"><strong>Amount Chargeable(In Words): '.$number.'{'.$currency['currency_code'].'}</strong></td>
	</tr>
	<tr>
		<td valign="top" width="50%"><div><strong>Declaration:</strong><br>We declare that this Invoice shows the actual price of the <br>goods described and that all particular are true and <br>correct.<br>';
		if(isset($proforma['destination']) && !empty($proforma['destination']) && $proforma['destination']==111)
		{
		$html .='<strong>Delivery schedule:</strong><br> All stock pouches will be ready in 10-15 days after the total invoice amount is transferred..<br> If the goods are ready we will send it asap. <br>Some colors or sizes can even take few days more for production.<br><b style="color:red"> NOTE : OUR ALL CONSIGNMENTS WILL COME "TO PAY"...</b>';
		}
		$html .='</div></td>
		<td valign="top" class="sign_td">
		<table border="0" align="right"  cellspacing="0px" cellpadding="0px" style=" border-spacing: 0px;" >
		<tr>  
			<td width="50%"><p align="left">Signature &amp; Date:<br>For <strong>'.$sign.'</strong><br>
			<p style="text-align:right;margin-top:20px;margin-bottom:0;padding:0px;"></p><hr/>
				<p id="prefix" style="text-align:right;float:right;" >Authorised Signature</p>
			</td>
		</tr> 
	</table></td>
	</tr></tbody></table></div>';
			
$html .='<div style=" float: left;  border: 1px solid black;page-break-before: always;font-size: 16px;">
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
								</tr>
								<tr>
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
					$html .='</tbody></table></div>';
					
		return $html ;
	}
	public function getProforma($proforma_id) {
		$sql = "select * from standupp_client_proforma.proforma where proforma_id = '".$proforma_id."'";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->row;
		} else {
			return false;
		}
	}
	public function getClientProduct($client_product_id)
	{	//change query as new table of qty product wise(21-12-2015)[kinjal]
		$sql="SELECT cpm.*, p.product_name, zip.zipper_name, sp.spout_name, acc.product_accessorie_name FROM  client_product_master as cpm, product as p, product_spout as sp, product_zipper as zip, product_accessorie as acc WHERE cpm.client_product_id='".$client_product_id."' AND cpm.is_delete='0' AND cpm.status = '1' AND p.product_id=cpm.product_id AND sp.product_spout_id = cpm.spout_id AND zip.product_zipper_id = cpm.zipper_id AND acc.product_accessorie_id = cpm.accessories_id";
		$data = $this->query($sql);
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}	
}?>