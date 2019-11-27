
<?php 
class quotation_report extends dbclass
{

			
			public function getReport($post)
			{ //printr($post);die;
 				$from_date=$post['f_date'];
				$t_date = $post['t_date'];
				$product_id = $post['product'];
				$country = $post['Shipment_Country'];
				$transport_type = $post['transport_type'];
				
				$user_type_id=$user_id='';
				$date='';
				$con='';
				$cond='';
				$condt='';
				if(isset($post['user_name'])  && $post['user_name']!= '')
				{
					//echo "hi1";
					//die;
					$user= explode("=",$post['user_name']);
				//	printr($user);
					$user_type_id = $user[0];
					$user_id = $user[1];
					$user_cond=" AND mpq.added_by_user_id ='".$user_id."' AND mpq.added_by_user_type_id ='".$user_type_id."'";
				
				}
				else
				{
					//echo "hi2";
					//die;
					$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
					$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
					$user_cond=" ";
				}
				if($from_date != '' && $t_date != '')
				{
					$f_date = $from_date;
					$to_date = $t_date;
					$date.= "AND (mpq.date_added >= '".$from_date."' AND mpq.date_added <= '".$t_date."')";
				}
				
				if($product_id!='')
				{
						$cond = " AND mpq.product_id = '".$product_id."'";
				}
				
				if($country!='')
				{
						$con = " AND cn.country_id = '".$country."'";
				}
				
				if($transport_type!='')
				{
						$condt = " AND mpqp.transport_type = '".$transport_type."'";	
				}
				if($_SESSION['ADMIN_LOGIN_SWISS'] =='1' && $_SESSION['LOGIN_USER_TYPE'] == '1')
				{
				//echo "hiiiiii";
				$sql = "SELECT (mpqp.total_price + mpqp.gress_price) as totalPrice,mpqq.product_quotation_quantity_id,mpq.product_quotation_id,mpq.currency, mpq.customer_name, mpq.product_name,mpq.added_by_user_id,mpq.added_by_user_type_id,mpq.cylinder_price, mpq.tool_price, mpq.width, mpq.height, mpq.gusset, mpqi.multi_quotation_number, mpqq.quantity, mpqp.zipper_txt, mpqp.valve_txt, mpqp.spout_txt, mpqp.accessorie_txt,cn.country_name,mpqq.discount,mpq.size_id,mpq.date_added, mpqp.total_price,mpqp.transport_type,mpqp.total_price_with_excies,mpqp.total_price_with_tax,mpqp.tax_name,mpqp.tax_type,mpqp.tax_percentage,mpqp.excies, mpqp.gress_price, mpqp.customer_gress_price,mpqp.zipper_price,mpqp.valve_price,mpqp.courier_charge,mpqp.spout_base_price,mpqp.accessorie_base_price,mpqp.transport_price,pm.make_name,mpq.currency_price,mpqp.transport_type, mpq.shipment_country_id,mpqp.total_price_with_tax,mpq.multi_product_quotation_id,am.user_name
				
				FROM multi_product_quotation as mpq,multi_product_quotation_id as mpqi, multi_product_quotation_quantity as mpqq, multi_product_quotation_price as mpqp, country as cn,product_make as pm,account_master as am
				
				WHERE mpq.is_delete=0 AND mpq.multi_product_quotation_id = mpqi.multi_product_quotation_id AND mpq.product_quotation_id=mpqq.product_quotation_id AND mpqq.product_quotation_quantity_id = mpqp.product_quotation_quantity_id AND mpq.product_quotation_id = mpqp.product_quotation_id AND mpq.shipment_country_id=cn.country_id AND mpqp.make_pouch=pm.make_id AND mpq.added_by_user_type_id=am.user_type_id AND mpq.added_by_user_id = am.user_id AND mpqi.multi_product_quotation_id!=0 AND mpqi.status = '1' AND mpqi.quotation_status='1' AND mpq.status='1' AND mpq.quotation_status='1' $cond $con $condt $date $user_cond";
				//echo $sql;
				}
				/*AND mpq.added_by_user_id ='".$user_id."' AND mpq.added_by_user_type_id ='".$user_type_id."'
				
			//echo $sql ;*/
				else
				{
			//$user_cond=" AND mpq.added_by_user_id ='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND mpq.added_by_user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
					
					if($user_type_id  == 2){
						$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
						$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
						$set_user_id = $parentdata->row['user_id'];
						$set_user_type_id = $parentdata->row['user_type_id'];
					}else{
						$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
						//printr($userEmployee);
						$set_user_id = $user_id;
						$set_user_type_id = $user_type_id;
					}
					$str = '';
					if($userEmployee){
						$str = ' OR ( mpq.added_by_user_id IN ('.$userEmployee.') AND mpq.added_by_user_type_id = 2 )';
					}
					$sql = "SELECT (mpqp.total_price + mpqp.gress_price) as totalPrice,mpqq.product_quotation_quantity_id,mpq.product_quotation_id,mpq.currency, mpq.customer_name, mpq.product_name, mpq.cylinder_price, mpq.tool_price, mpq.width, mpq.height, mpq.gusset,mpq.added_by_user_id,mpq.added_by_user_type_id,mpqi.multi_quotation_number, mpqq.quantity, mpqp.zipper_txt, mpqp.valve_txt, mpqp.spout_txt, mpqp.accessorie_txt,cn.country_name,mpqq.discount,mpq.size_id,mpq.date_added, mpqp.total_price,mpqp.transport_type,mpqp.total_price_with_excies,mpqp.total_price_with_tax,mpqp.tax_name,mpqp.tax_type,mpqp.tax_percentage,mpqp.excies, mpqp.gress_price, mpqp.customer_gress_price,mpqp.zipper_price,mpqp.valve_price,mpqp.courier_charge,mpqp.spout_base_price,mpqp.accessorie_base_price,mpqp.transport_price,pm.make_name,mpq.currency_price,mpqp.transport_type, mpq.shipment_country_id,mpqp.total_price_with_tax,mpq.multi_product_quotation_id,am.user_name
				
				FROM multi_product_quotation as mpq, multi_product_quotation_id as mpqi, multi_product_quotation_quantity as mpqq, multi_product_quotation_price as mpqp, country as cn,product_make as pm,account_master as am
				
				WHERE mpq.is_delete=0 AND mpq.multi_product_quotation_id = mpqi.multi_product_quotation_id AND mpq.product_quotation_id=mpqq.product_quotation_id AND mpqq.product_quotation_quantity_id = mpqp.product_quotation_quantity_id AND mpq.product_quotation_id = mpqp.product_quotation_id AND mpq.shipment_country_id=cn.country_id AND mpqp.make_pouch=pm.make_id AND mpq.added_by_user_type_id=am.user_type_id AND mpq.added_by_user_id = am.user_id  AND mpqi.multi_product_quotation_id!=0 AND mpqi.status = '1' AND mpqi.quotation_status='1' AND mpq.status='1' AND mpq.quotation_status='1' $cond $con $condt $date AND (mpq.added_by_user_id ='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND mpq.added_by_user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."' $str)";
				//echo $sql;
				//die;
				//printr($sql);

				}
				
								
				$sql .="ORDER BY mpqq.product_quotation_quantity_id";
				//
				//echo $sql;
				 $data=$this->query($sql);
				//printr($data);
				//echo $data->num_rows;
				 if($data->num_rows){
					foreach($data->rows as $row)
					{
						$materialData = $this->getQuotationMaterial($row['product_quotation_id']);  
						//printr($materialData);
						$quot_data= $row;
						$quot_data['material_data']=$materialData;
						//printr($quot_data);
						$quto_data_final[] =$quot_data;
					}
					
					
					
					//printr($quto_data_final);
					//die;
				/* {
					 
					 
					 $return[$Data['transport_type']] = array(
						'materialData'=>$materialData						   
																						   );
				 	 return  $data->rows;*/
					 	return  $quto_data_final;				
				 }
				 else
				 {
					 return false;
				 }
				
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
	
			public function getQuotationMaterial($quotation_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."multi_product_quotation_layer WHERE product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
			public function getUserList()
			{
				$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
				$data = $this->query($sql);
				//printr($data);die;
				return $data->rows;
			}
		
			
			public function getProducts()
			{
				$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE status = '1' AND is_delete = 0");
				if($data->num_rows)
				{
					return $data->rows;
				}
				else
				{
					return false;
				}		
			}
			
			public function getCountry()
			{
				$data = $this->query("SELECT * FROM `country` WHERE status = '1' AND is_delete = 0 ");
				if($data->num_rows)
				{
					return $data->rows;
				}
				 else 
				{
					return false;
				}
			}
		public function numberFormate($number,$decimalPoint=3){
			return number_format($number,$decimalPoint,".","");
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
	
	public function checkforplaceorder($product_quotation_id)
	{
		$sql="SELECT mc.multi_product_quotation_id,mp.date_added FROM multi_custom_order_id as mc,multi_product_quotation as mp WHERE mp.product_quotation_id=mc.multi_product_quotation_id AND mc.multi_product_quotation_id='".$product_quotation_id."'";
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	
	public function getexpiredate_custmorder($user_id,$user_type_id)
	{
		if($user_type_id=='4')
		{
			$sql="SELECT Multi_Quotation_expiry_days FROM international_branch WHERE international_branch_id='".$user_id."'";
			$data=$this->query($sql);
			if($data->num_rows)
				return $data->row;
			else
				return false;
		}
		if($user_type_id=='2')
		{
			$sql="SELECT user_id FROM employee WHERE employee_id='".$user_id."' AND user_type_id='4'";
			$data=$this->query($sql);
			if($data->num_rows)
			{
				$sql1="SELECT Multi_Quotation_expiry_days FROM international_branch WHERE international_branch_id='".$data->row['user_id']."'";
				$data1=$this->query($sql1);
				if($data1->num_rows)
					return $data1->row;
				else
					return false;
			}
			
		}
	
	}
	
			// view part
			public function view_quotation_report($multi_quotation_details,$post)
			{ 
			//printr($post);
			//die;
			$html = "";
				//$materialData = $this->getReport($records[0]['materialData'][0]['product_quotation_id']);
              //$html .="<span> Searching Date From: <b>".dateFormat(4,$stock_order_details['from_date'])."</b> To: <b>".dateFormat(4,$stock_order_details['to_date'])."</b></span> <br><br>";
                         // printr($multi_quotation_details);    
				 $html .= "<div class='form-group'>
						<div class='table-responsive'>
						&nbsp;&nbsp;<span> Searching Date From: <b>".dateFormat(4,$post['f_date'])."</b> To: <b>".dateFormat(4,$post['t_date'])."</b>";
					if(isset($post['user_name']) && $post['user_name']!='')
					{
						$user_id=explode("=",$post['user_name']);
						$user_name=$this->getuser($user_id[1],$user_id[0]);
						$html.=" For User : <b> ".$user_name['user_name']."<b>";
					}
				$html .="</span> <br><br>
					    <table class='table table-striped b-t text-small'  id='quotation_order'>
								<thead>
									<tr>
                                          <th>Sr No</th>
										  <th>Quotation Number</th>
                                          <th>Customer Name</th>
										  <th>Shipment Country</th>
                                          <th>Product Name</th>
										  <th>Quantity</th>
 									      <th>Layer:Material:Thickness</th>
										  <th>Price / pouch</th>
										  <th>Total</th>
										  <th>Price / pouch With Tax</th>
										  <th>Total Price With Tax</th>
										  <th>Cylender Price</th>
										  <th>Tool Price</th>
                                          
                                    </tr>
                                </thead>     
              
              					<tbody>";
								  	$i=1;
								if(isset($multi_quotation_details) && !empty($multi_quotation_details))
								{
										//printr($multi_quotation_details);
									foreach($multi_quotation_details  as $reports)
									{
										//printr($reports);
							$expiredate_cust = $this->getexpiredate_custmorder($reports['added_by_user_id'],$reports['added_by_user_type_id']);
                                    	$exp_date=$expiredate_cust['Multi_Quotation_expiry_days'];
						$date_added=strtotime($reports['date_added']);
						$final_date=date('y-m-d', $date_added);
						//printr($final_date);
						$fin='';
						if($exp_date!='')
						{
							$fin=date('y-m-d',strtotime($final_date."+ {$exp_date} days"));
							
							
						}
						$today=date('y-m-d'); 
										
										$place_order=$this->checkforplaceorder($reports['product_quotation_id']);
										
										//printr($reports['product_quotation_id']);
										//echo "<br>";
										//printr($place_order['multi_product_quotation_id']);
	$html .= "<tr valign='top' href=".HTTP_SERVER."admin/index.php%3Froute=multi_product_quotation&mod=view&quotation_id=".encode($reports['multi_product_quotation_id'])."&filter_edit=0>
									
                                     	<td>".$i."</td>
										
										 <td>".$reports['multi_quotation_number']."<br />
										 <small class='text-muted'>".dateFormat(4,$reports['date_added'])."</small><br />";
										 
										 if($place_order['multi_product_quotation_id']!='')
										 	$html.="<a class='label bg-success' href='javascript:void(0);'>Order Placed</a>";
										elseif($fin <= $today)
											$html.="<a class='label bg-warning' href='javascript:void(0);'>Expired</a>"; 
											
										 
										 
										 
										 $html.="</td>
										  
							    		<td>".$reports['customer_name']."</td>
									   
									    <td>".$reports['country_name']." /<br> By ".$reports['transport_type']."</td>
											
										<td>".$reports['product_name']." <br>
											(".$reports['zipper_txt'].' '.$reports['spout_txt'].' '.$reports['valve_txt'].' '.$reports['accessorie_txt'].")<br>
											<span style='color:#FF0000'><b>(".(int)$reports['width'].'X'.(int)$reports['height'].'X'.$reports['gusset'].")</b></span>
										</td>
  
                                        <td>".$reports['quantity']."</td>

										<td>";
											foreach($reports['material_data'] as $material)
											{
												$html .='<b>'.$material['layer'].'Layer </b> : '.$material['material_name'].' : '.$material['material_thickness'].'</br>';
											}
										//.$reports['Layer'].' '.$reports['material_name'].' '.$reports['material_thickness'].
										$html .="</td>
                                        
										
										
									    <td>" ; 
															if($reports['discount'] && $reports['discount'] >0.000)
															{
                                                                $html .="<b>Total : </b>";
																if($reports['size_id']!='0')
																{
																    $pretot= $this->numberFormate((($reports['totalPrice'] / $reports['quantity']) / $reports['currency_price']),"3"); 	
																}
																else
																{
																    $normal_val= $this->numberFormate((($reports['totalPrice'] / $reports['quantity']) / $reports['currency_price']),"3"); 	
																	$extra_val= $this->numberFormate(((($reports['totalPrice']*15/100) / $reports['quantity']) / $reports['currency_price']),"3"); 									
																	$pretot=$normal_val+$extra_val;
																}
															    $html .=$pretot." <br />
                                                                <b>Discount  ".$reports['discount']." %) : </b>";
																$predis = $pretot*$reports['discount']/100; 
																$html .= $this->numberFormate($predis,"3")."<br />
                                                                <b>Final Total : </b>
																".$reports['currency'].' '.$this->numberFormate(($pretot-$predis),"3");
															}
															else
															{
																	 	if($reports['size_id']!='0')
																		{
																			$html .= $reports['currency']." ".
																			$this->numberFormate((($reports['totalPrice'] / $reports['quantity']) / $reports['currency_price']),"3");		
																		}
                                                                        else
                                                                        {
                                                                        	
																			$normal_p=$this->numberFormate((($reports['totalPrice'] / $reports['quantity']) / $reports['currency_price']),"3");	
																			$extra_p=$this->numberFormate(((($reports['totalPrice'] / $reports['quantity']) / $reports['currency_price'])*15/100),"3");
																			$f_p=$normal_p+$extra_p;
																			$html .= $reports['currency']." ".$f_p;
                                                                        }
																	
															}
												$html .="</td>
                                       
										
									
										
									    <td>";
                                                                if($reports['discount'] && $reports['discount'] >0.000) 
																{
                                                                	$html .="<b>Total : </b>";
																	if($reports['size_id']!='0')
																	{
																		$tot= $this->numberFormate(($reports['totalPrice'] / $reports['currency_price'] ),"3");
																	}
																	else
																	{
																		$nor= $this->numberFormate(($reports['totalPrice'] / $reports['currency_price'] ),"3");
																		$extra= $this->numberFormate((($reports['totalPrice']*15/100) / $reports['currency_price'] ),"3");
																		$tot=$nor+$extra;
																}
																$html .=$tot."<br>
                                                                <b>Discount (".$reports['discount']." %) : </b>";
																$dis = $tot*$reports['discount']/100;
																$html .=$this->numberFormate($dis,"3")."<br />
                                                                <b>Final Total : </b>".$reports['currency']." ".($tot-$dis);
																	}
																	
																else 
																{
																	if($reports['size_id']=='0')
																	{
																			$extra_profit=$this->numberFormate((($reports['totalPrice']*15/100) / $reports['currency_price'] ),"3");
																			$normal=$this->numberFormate(($reports['totalPrice'] / $reports['currency_price'] ),"3");
																			$final_ex_profit=$extra_profit+$normal;
																		
																	 		$html .=$reports['currency']." ".$final_ex_profit;			
																	
																	}
																	else
																	{
																	
																		 $html .=$reports['currency']." ".$this->numberFormate(($reports['totalPrice'] / $reports['currency_price'] ),"3");
																	
																	}
																}
                                                                $html .="</td>
                                        	";
											 if($reports['transport_type']=='pickup') {
													if($reports['shipment_country_id']==111) { 
																$html .="<td>".$reports['currency']." ".$this->numberFormate((($reports['total_price_with_tax'] / $reports['quantity']) / $reports['currency_price'] ),"3")."
                                                                 </td>
                                                                 <td>".$reports['currency'].' '.$this->numberFormate(($reports['total_price_with_tax'] / $reports['currency_price'] ),"3")." </td>";
                                                  }  } 
												  else
												  {
													  $html.="<td></td><td></td>";
												  }
											$html.="
										
										 
										 
                                        <td>".(int)$reports['cylinder_price']."</td>

										<td>".(int)$reports['tool_price']."</td>
									 
                                        
                                     </tr>";

									$i++;
								}
							}	
							else
							{
								 $html .=" no records found";
							}
							
								
                                                             
                            $html .="   </tbody>
                               </table>";
            $html .=  " </div>
                    </div>
                ";
				return $html;
			
			}
			
}

?>                                                                         