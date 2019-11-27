<?php 
class custom_order_report extends dbclass
{

			public function getReport($post)
			{
 				$from_date=$post['f_date'];
				$t_date = $post['t_date'];
				$product_id = $post['product'];
				$country = $post['Shipment_Country'];
				$user_type_id=$user_id='';
				$con='';
				$cond='';
				$date='';
	            
				if(isset($post['user_name']) && $post['user_name']!='')
				{
					$u = explode("=", $post['user_name']);
					$user_id = $u[1];
					$user_type_id = $u[0];
					$user_condition=" AND mcoi.added_by_user_id ='".$u[1]."' AND mcoi.added_by_user_type_id ='".$u[0]."' ";
				}
				else
				{
					$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
					$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
					$user_condition=" ";
				}//printr($user_condition);
				if($from_date != '' && $t_date != '')
				{
					
					$date = "AND (mcoi.date_added >= '".$from_date."' AND mcoi.date_added <= '".$t_date."') ";
				}
				if($product_id!='')
				{
						$cond = " AND mco.product_id = '".$product_id."'";
				}
				
				if($country!='')
				{
						$con = " AND cn.country_id = '".$country."'";
				}
				
				if($_SESSION['ADMIN_LOGIN_SWISS'] =='1' && $_SESSION['LOGIN_USER_TYPE'] == '1')
				{
				   // $sql = "SELECT mcoi.multi_custom_order_id,mcoi.date_added,mcoi.multi_custom_order_number,mco.product_name,mcop.zipper_txt,mcop.valve_txt,mcop.spout_txt,mcop.accessorie_txt,mcoi.company_name, mcoq.quantity,mco.height,mco.width,mco.gusset,mcop.transport_type,cn.country_name,mcoi.added_by_user_type_id,mcoi.added_by_user_id,am.user_name  FROM 	multi_custom_order_id as mcoi,multi_custom_order as mco,multi_custom_order_price as mcop,multi_custom_order_quantity as mcoq,country as cn, account_master as am,multi_product_quotation as mpq  WHERE mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mco.custom_order_id=mcop.custom_order_id AND mco.custom_order_id=mcoq.custom_order_id AND mpq.product_quotation_id=mcoi.multi_product_quotation_id AND mco.shipment_country_id=cn.country_id AND am.user_id=mcoi.added_by_user_id AND am.user_type_id=mcoi.added_by_user_type_id AND mcoi.multi_product_quotation_id!=0 AND mcoi.status = '1' AND mcoi.custom_order_status AND mco.status='1' AND mco.custom_order_status='1' $cond $con $date $user_condition ";
				    $sql = "SELECT mcoi.multi_custom_order_id,mcoi.multi_product_quotation_id,mcoi.date_added,mcoi.multi_custom_order_number,mco.product_name,mcop.zipper_txt,mcop.valve_txt,mcop.spout_txt,mcop.accessorie_txt,mcoi.company_name, mcoq.quantity,mco.height,mco.width,mco.gusset,mcop.transport_type,mcop.	total_price,mcop.gress_price,mco.currency_price,mco.currency,mco.cylinder_price,mco.gress_air,mco.gress_sea,cn.country_name,mcoi.added_by_user_type_id,mcoi.added_by_user_id,am.user_name FROM multi_custom_order_id as mcoi,multi_custom_order as mco,multi_custom_order_price as mcop,multi_custom_order_quantity as mcoq,country as cn, account_master as am,multi_product_quotation as mpq  WHERE mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mco.custom_order_id=mcop.custom_order_id AND mco.custom_order_id=mcoq.custom_order_id AND mpq.product_quotation_id=mcoi.multi_product_quotation_id AND mco.shipment_country_id=cn.country_id AND am.user_id=mcoi.added_by_user_id AND am.user_type_id=mcoi.added_by_user_type_id AND mcoi.multi_product_quotation_id!=0 AND mcoi.status = '1' AND mcoi.custom_order_status AND mco.status='1' AND mco.custom_order_status='1' $cond $con $date $user_condition ";
	
				}
				else
				{
	
					if($user_type_id  == 2){
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
						$str = ' OR ( mcoi.added_by_user_id IN ('.$userEmployee.') AND mcoi.added_by_user_type_id = 2 )';
					}
					
					 //$sql = "SELECT mcoi.multi_custom_order_id,mcoi.date_added,mcoi.multi_custom_order_number,mco.product_name,mcop.zipper_txt,mcop.valve_txt,mcop.spout_txt,mcop.accessorie_txt,mcoi.company_name, mcoq.quantity,mco.height,mco.width,mco.gusset,mcop.transport_type,cn.country_name,mcoi.added_by_user_type_id,mcoi.added_by_user_id,am.user_name  FROM 	multi_custom_order_id as mcoi,multi_custom_order as mco,multi_custom_order_price as mcop,multi_custom_order_quantity as mcoq,country as cn, account_master as am,multi_product_quotation as mpq  WHERE mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mco.custom_order_id=mcop.custom_order_id AND mco.custom_order_id=mcoq.custom_order_id AND mpq.product_quotation_id=mcoi.multi_product_quotation_id AND mco.shipment_country_id=cn.country_id AND am.user_id=mcoi.added_by_user_id AND am.user_type_id=mcoi.added_by_user_type_id AND mcoi.multi_product_quotation_id!=0 AND mcoi.status = '1' AND mcoi.custom_order_status AND mco.status='1' AND mco.custom_order_status='1' $cond $con $date  AND (mcoi.added_by_user_id ='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND mcoi.added_by_user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."' $str) ";
					 $sql = "SELECT mcoi.multi_custom_order_id,mcoi.multi_product_quotation_id,mcoi.date_added,mcoi.multi_custom_order_number,mco.product_name,mcop.zipper_txt,mcop.valve_txt,mcop.spout_txt,mcop.accessorie_txt,mcoi.company_name, mcoq.quantity,mco.height,mco.width,mco.gusset,mcop.transport_type,mcop.	total_price,mcop.gress_price,mco.currency_price,mco.currency,mco.cylinder_price,mco.gress_air,mco.gress_sea,cn.country_name,mcoi.added_by_user_type_id,mcoi.added_by_user_id,am.user_name FROM multi_custom_order_id as mcoi,multi_custom_order as mco,multi_custom_order_price as mcop,multi_custom_order_quantity as mcoq,country as cn, account_master as am,multi_product_quotation as mpq WHERE mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mco.custom_order_id=mcop.custom_order_id AND mco.custom_order_id=mcoq.custom_order_id AND mpq.product_quotation_id=mcoi.multi_product_quotation_id AND mco.shipment_country_id=cn.country_id AND am.user_id=mcoi.added_by_user_id AND am.user_type_id=mcoi.added_by_user_type_id AND mcoi.multi_product_quotation_id!=0 AND mcoi.status = '1' AND mcoi.custom_order_status AND mco.status='1' AND mco.custom_order_status='1' $cond $con $date  AND (mcoi.added_by_user_id ='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND mcoi.added_by_user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."' $str) ";
			    }
				//echo $sql;//die;
				 $data=$this->query($sql);
				 if($data->num_rows)
				 {
				 	 return  $data->rows;
					 					
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

			public function getUserList()
			{
				if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
				    $sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
				else
				{
				    if($_SESSION['LOGIN_USER_TYPE']  == 2){
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
				    $sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master WHERE (user_id ='".$set_user_id."' AND user_type_id ='".$set_user_type_id."' $str) ORDER BY user_name ASC";
				}
				$data = $this->query($sql);
			    //printr($sql);die;
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
	
			// view part
			public function view_custom_report($custom_order_details,$post)
			{ 
				//printr($custom_order_details);
			    $html = "";
			

				 $html .= "<div class='form-group'>
						<div class='table-responsive'>
						&nbsp;&nbsp;<span> Searching Date From: <b>".dateFormat(4,$post['f_date'])."</b> To: <b>".dateFormat(4,$post['t_date'])."</b></span> <br><br>
					    <table class='table table-striped b-t text-small' id='custom_order' border='1'>
								<thead>
									<tr>
                                          <th>Sr No</th>
                                          <th>Order Date</th>
                                          <th>Order No</th>
                                          <th>Product</th>
										  <th>Option</th>
                                          <th>Quantity</th>
                                          <th>Original Pouch Price (".$custom_order_details[0]['currency'].")</th>
                                          <th>split Pouch Price  (".$custom_order_details[0]['currency'].")</th>
                                          <th>Gress (%) </th>
										  <th>Dimension</th>
										  <th>Transportation</th>
										  <th>Customer Name</th>
                                          <th>Shipment Country</th>
										  <th>Posted By</th>
                                          
                                    </tr>
                                </thead>     
              
              					<tbody>";
								  	$i=1;
								if(isset($custom_order_details) && !empty($custom_order_details))
								{   
								    
									foreach($custom_order_details  as $reports)
									{ 
                                        $quo_data = $this->query("SELECT mp.gress_per FROM `multi_product_quotation_price` as mp,multi_product_quotation_id as mpi,multi_product_quotation_quantity as mq,multi_product_quotation as m WHERE mpi.multi_product_quotation_id = m.multi_product_quotation_id AND mpi.multi_product_quotation_id= '".$reports['multi_product_quotation_id']."' AND mp.product_quotation_id = m.product_quotation_id AND m.product_quotation_id = mq.product_quotation_id AND mp.product_quotation_id = mq.product_quotation_id and mp.product_quotation_quantity_id = mq.product_quotation_quantity_id AND quantity = '".$reports['quantity']."' AND transport_type = '".$reports['transport_type']."' LIMIT 1");
                                        //printr($quo_data);
                                        if($reports['transport_type']=='air'){
                                            $reports_gress=$reports['gress_air'];
                                        }else{
                                              $reports_gress=$reports['gress_sea'];
                                        }
                                        $pouch_price=$this->numberFormate(((($reports['total_price']+$reports['gress_price']) / $reports['quantity'])/$reports['currency_price']),3);
    								    $gress_price=$this->numberFormate((($reports['gress_price'] / $reports['quantity'])/$reports['currency_price']),3);
    								    $gress_pouch_price=$pouch_price-$gress_price;
    									$gress_per1 = (round(($pouch_price*100)/$gress_pouch_price)-100);
    							        
    									//printr((94373.395+29288.295));
    									$html .= "<tr valign='top' href=".HTTP_SERVER."admin/index.php%3Froute=custom_order&mod=view&custom_order_id=".encode($reports['multi_custom_order_id'])."&filter_edit=0>
                                         	<td>".$i."</td>
    
                                            <td>".dateFormat(4,$reports['date_added'])."</td> 
                                            
                                            <td>".$reports['multi_custom_order_number']."</td>
                                            
                                            <td>".$reports['product_name']."</td>
                                            
                                            <td>".$reports['zipper_txt'].' '.$reports['spout_txt'].' '.$reports['valve_txt'].' '.$reports['accessorie_txt']."</td>
                                            
                                            <td>".$reports['quantity']."</td>
                                          
                                            <td>".$pouch_price."</td>
                                          
                                            <td>".$gress_pouch_price."</td>
                                            
                                             <td>".$quo_data->row['gress_per']."</td>
                                            
                                            <td>".(int)$reports['width'].'X'.(int)$reports['height'].'X'.$reports['gusset']."</td>
                                            
    									    <td>".$reports['transport_type']."</td>
                                            
    									    <td>".$reports['company_name']."</td>
                                            
                                            <td>".$reports['country_name']."</td>
    
    										<td>".$reports['user_name']."</td>
    									 
                                            
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
	public function numberFormate($number,$decimalPoint=3){

			return number_format($number,$decimalPoint,".","");

		}			
}

?>                                                                         