<?php 
class custom_order_invoice_report extends dbclass
{

			public function getReport($custom_order_id)
			{
		
				//$con='';
			//	$cond='';
				//$date='';
	
			 $sql = "SELECT mcop.total_price,mcop.gress_price,mco.currency,mco.currency_price,mcoi.multi_custom_order_id,mcoi.date_added,mcoi.multi_custom_order_number,mcop.custom_order_id ,mco.product_name,mcop.zipper_txt,mcop.valve_txt,mcop.spout_txt,mcop.accessorie_txt,mcoi.company_name,
				 mcoq.quantity,mco.height,mco.width,mco.gusset,mcop.transport_type,cn.country_name,mcoi.added_by_user_type_id,mcoi.added_by_user_id,am.user_name
				          FROM 	multi_custom_order_id as mcoi,multi_custom_order as mco,multi_custom_order_price as mcop,multi_custom_order_quantity as mcoq,country as cn, account_master as am,multi_product_quotation as mpq
	  WHERE mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mco.custom_order_id=mcop.custom_order_id AND mco.custom_order_id=mcoq.custom_order_id AND mpq.product_quotation_id=mcoi.multi_product_quotation_id AND mco.shipment_country_id=cn.country_id AND am.user_id=mcoi.added_by_user_id AND am.user_type_id=mcoi.added_by_user_type_id AND mcoi.multi_product_quotation_id!=0 AND mcoi.status = '1' AND mcoi.custom_order_status='1' AND mco.status='1' AND mco.custom_order_status='1' AND mco.custom_order_id='".$custom_order_id."'  ";
	
			
		//	echo $sql.'<br><br>';
		//	echo $custom_order_id.'<br><br>';
			
			$data=$this->query($sql);
			
				 if($data->num_rows)
				 {
				 	 return  $data->row;
					 					
				 }
				 else
				 {
					 return false;
				 }
				
			}
			public function getReport1($post)
			{
			    //printr($post['trans']);
			    $from_date=$post['f_date'];
				$t_date = $post['t_date'];
				$product_id = $post['product'];
				$country = $post['Shipment_Country'];
				$trans = $post['trans'];
				$user_type_id=$user_id='';
				$con='';
				$cond='';
				$date='';
				$cond_trans='';
				if(isset($post['user_name']) && $post['user_name']!='')
				{
					//$user = $post['user_name'];
					
					$u = explode("=", $post['user_name']);
					$added_by_user_id = $u[1];
					$added_by_user_type_id = $u[0];
					
					if($added_by_user_type_id=='2')
					{
					    $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$added_by_user_id."' ");
						$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
						$set_user_id = $parentdata->row['user_id'];
						$set_user_type_id = $parentdata->row['user_type_id']; 
						$user_condition=" AND i.order_user_id ='".$parentdata->row['user_id']."'";
					
					}
					else
					{
					    $userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
					    $set_user_id = $added_by_user_id;
						$set_user_type_id = $added_by_user_type_id; 
						$user_condition=" AND i.order_user_id ='".$set_user_id."'";
					}
				
				
					
				}
				
				
				if($from_date != '' && $t_date != '')
				{
					
					$date = "AND (i.date_added >= '".$from_date."' AND i.date_added <= '".$t_date."') ";
				}
				if($product_id!='')
				{
					$cond = " AND ip.product_id = '".$product_id."'";
				}
				if($trans!='')
				{
					$cond_trans = " AND i.transportation = '".$trans."'";
				}
				
				if($country!='')
				{
					$con = " AND i.final_destination = '".$country."'";
				}
				if($_SESSION['ADMIN_LOGIN_SWISS'] =='1' && $_SESSION['LOGIN_USER_TYPE'] == '1')
				{
				    $sql = "SELECT i.*,ip.*,ic.*,c.currency_code FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip ,invoice_color_test as ic,currency as c WHERE i.is_delete=0 AND i.done_status=1 AND i.invoice_id=ip.invoice_id AND i.invoice_id=ic.invoice_id AND ip.buyers_o_no LIKE '%cust%' AND ip.order_id!=0 AND ip.invoice_product_id = ic.invoice_product_id AND c.currency_id = i.curr_id $cond_trans $cond $con $date $user_condition  ";
				}
				else
				{
				     $sql = "SELECT i.*,ip.* ,ic.*,c.currency_code FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip ,invoice_color_test as ic ,currency as c WHERE i.is_delete=0 AND i.done_status=1 AND  i.invoice_id=ip.invoice_id AND ip.buyers_o_no LIKE '%cust%' AND i.invoice_id=ic.invoice_id AND ip.order_id!=0 AND ip.invoice_product_id = ic.invoice_product_id AND c.currency_id = i.curr_id $cond_trans $cond $con $date $user_condition ";
			    } 
		//	  echo $sql;//die;
			     $data=$this->query($sql);
			     
		//	 printr($data);
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
	
	
	        public function getInvoiceData($order_id){
	            
            		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_product_test` as ip,invoice_color_test as ic WHERE ip.order_id = '" .(int)$order_id. "' AND ip.is_delete=0 AND ip.invoice_product_id=ic.invoice_product_id";
            		$data = $this->query($sql);
            
            	//	printr($data);//die;
            		if($data->num_rows){
            			return $data->row;
            		}else{
            			return false;
            		}		

	
	        }
	         public function getCustomData($order_id){
	            
            		$sql = "SELECT * FROM `" . DB_PREFIX . "FROM multi_custom_order_id as mcoi,multi_custom_order as mco,multi_custom_order_price as mcop,multi_custom_order_quantity as mcoq,country as cn, account_master as am,multi_product_quotation as mpq WHERE ip.order_id = '" .(int)$order_id. "' AND ip.is_delete=0 AND ip.invoice_product_id=ic.invoice_product_id";
            		$data = $this->query($sql);
            
            	//	printr($data);//die;
            		if($data->num_rows){
            			return $data->row;
            		}else{
            			return false;
            		}		

	
	        }
	        		
        	public function getInvoiceDetailsForCustomOrder($invoice_id)
        	{
        		//$sql = "SELECT ip.*,ic.* ,pc.* FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip, invoice_color_test as ic ,product_code as pc WHERE i.invoice_id =ip.invoice_id  AND i.invoice_id =ic.invoice_id AND i.invoice_id = '" .(int)$invoice_id. "' AND i.is_delete=0 AND  ic.invoice_product_id=ip.invoice_product_id AND pc.product_code_id = ip.product_code_id AND rack_status!=0 ";
        		$sql = "SELECT ip.*,ic.*  FROM  " . DB_PREFIX . "invoice_test as i,invoice_product_test as ip, invoice_color_test as ic WHERE i.invoice_id =ip.invoice_id  AND i.invoice_id =ic.invoice_id AND i.invoice_id = '" .(int)$invoice_id. "' AND i.is_delete=0 AND  ic.invoice_product_id=ip.invoice_product_id  AND rack_status!=0 AND ic.color ='-1'AND ip.order_id !='0'";
        
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
			// view part
			public function view_custom_report($custom_order_details,$post)
			{ 
				
			$html = "";
			
              //$html .="<span> Searching Date From: <b>".dateFormat(4,$stock_order_details['from_date'])."</b> To: <b>".dateFormat(4,$stock_order_details['to_date'])."</b></span> <br><br>";
                              
				 $html .= "<div class='form-group'>
						<div class='table-responsive'>
						&nbsp;&nbsp;<span> Searching Date From: <b>".dateFormat(4,$post['f_date'])."</b> To: <b>".dateFormat(4,$post['t_date'])."</b></span> <br><br>
					    <table class='table table-striped b-t text-small' id='custom_order'>
								<thead>
									<tr>
                                          <th>Sr No</th>
                                          <th>Order Date</th>
                                          <th>Order No</th>
                                          <th>Product</th>
										  <th>Option</th>
                                          <th>Custom order Quantity</th>
                                          <th>Custom order Rate</th>
                                          <th>Invoice Quantity</th>
                                          <th>Invoice Rate</th>
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
									//	printr($custom_order_details);
									foreach($custom_order_details  as $reports1)
									{
									
									//printr($custom_order_details);
									   $reports=$this->getReport($reports1['order_id']);
                                        
                                       
								        	   
								        	   //printr($reports);
                                                if(!empty($reports)){
                                                    // href=".HTTP_SERVER."admin/index.php%3Froute=custom_order&mod=view&custom_order_id=".encode($order_data_report['multi_custom_order_id'])."&filter_edit=0
            									$cust_rate = ((($reports['total_price'] + $reports['gress_price']) /$reports['quantity'])/$reports['currency_price']);
            									$html .= "<tr valign='top'>
                                                             	<td>".$i."</td>
                        
                                                                <td>".dateFormat(4,$reports['date_added'])."</td> 
                                                                
                                                                <td>".$reports['multi_custom_order_number']."<b> Invoice No :-".$reports1['invoice_no']." </b> </td>
                                                                
                                                                <td>".$reports['product_name']." <br><b> Job Name:- ".$reports1['color_text']."  </b></td>
                                                                
                                                                <td>".$reports['zipper_txt'].' '.$reports['spout_txt'].' '.$reports['valve_txt'].' '.$reports['accessorie_txt']."</td>
                                                                
                                                                <td>".$reports['quantity']."</td>
                                                               
                                                                <td>".$reports['currency'] ." ".number_format($cust_rate,3,".","")."</td>
                                                               
                                                                <td>".$reports1['qty']."</td>
                                                               
                                                                <td>".$reports1['currency_code']." ".$reports1['rate']."</td>
                                                                
                                                                <td>".(int)$reports['width'].'X'.(int)$reports['height'].'X'.$reports['gusset']."</td>
                                                                
                        									    <td>".$reports['transport_type']."</td>
                                                                
                        									    <td>".$reports['company_name']."</td>
                                                                
                                                                <td>".$reports['country_name']."</td>
                        
                        										<td>".$reports['user_name']."</td>
            									 
                                                    
                                                        </tr>";
            									$i++;
                                                
                                         }
								
									
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