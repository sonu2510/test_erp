<?php 
class stock_order_report extends dbclass
{

	public function getReport($post,$m)
	{
	 
	    $from_date=$post['f_date'];
		$t_date = $post['t_date'];
		
		
		
		$status=$product_id=$cond=$con=$check=$sta=$over=$date=$sum =$clr_cond=$clr =$country=$ship_type='';
		$user_type_id=$user_id='';
		if($m=='0')
		{
			$status = $post['Status'];
			$product_id = $post['product'];
		}
		if(isset($post['Shipment_Country']))
		    $country = $post['Shipment_Country'];
		    
		if($post['Ship_type']!='')
		    $ship_type = "AND t.transport ='".$post['Ship_type']."'";		
		if(isset($post['user_name']) && $post['user_name']!='')
		{
			$u = explode("=", $post['user_name']); 
			$user_id = $u[1];
			$user_type_id = $u[0];
			$user_condition = " AND t.user_id ='".$user_id."' AND t.user_type_id ='".$user_type_id."' ";
		}
		else
		{
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			$user_condition=" ";
		}
				
		if($from_date != '')
		{
			$f_date = $from_date;
			$date = "AND t.date_added >= '".$from_date."' ";
		}
		if($t_date != '')
		{
			$to_date = $t_date;
			$date.= "AND  t.date_added <='".$t_date."'";
		}
		if($product_id!='')
		{
				$cond = " AND t.product_id = '".$product_id."'";
		}
		if($country!='')
		{
				$con = " AND c.country_id = '".$country."'";
		}
		
		if($status!='')
		{
				$sta = " AND sos.status = '".$status."'";
		}
		if(isset($_POST['Overcross_Delivery']) && $_POST['Overcross_Delivery']!='')
		{
		
			$over= " AND sos.status = '".$_POST['Overcross_Delivery']."'";
		
		}
		$clr =',product_code as pc,pouch_color as clr';
		$clr_cond =' AND t.product_code_id = pc.product_code_id AND pc.color=clr.pouch_color_id';
		$sum = 'clr.color,pc.product_code,';
		if($m=='1')
		{
			$group =' GROUP BY t.product_code_id ORDER BY SUM(t.quantity) DESC '; 
			$sum = 'SUM(t.quantity) as order_qty,clr.color,pc.product_code,(pc.description) as product_code_description,';
			
		}
		else
			$group =' GROUP BY t.template_order_id ORDER BY so.gen_order_id DESC ';
		
	$dis_cond=$dis_table=$dis_select=$sen=$cond_st='';
	 if($status==3)
	{
		$dis_cond = ' (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=0)';
		$dis_table = ', stock_order_dispatch_history_test as sodh';	
		$dis_select = 'sum(sodh.dis_qty) as  dis_qty,sum(sodh.dis_qty*t.price) as dis_total_price,';
		$cond_st = 'AND ('.$dis_cond.') AND t.status=1';
	}
	else if($status==2)
	{
		$dis_cond = 'AND (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=2)';
		$dis_table = ', stock_order_dispatch_history_test as sodh';	
		$dis_select = 'sum(sodh.decline_qty) as  dis_qty,sum(sodh.decline_qty*t.price) as dis_total_price,';
		$cond_st = 'AND (sos.status="'.$status.'" '.$dis_cond.') AND t.status=1';
	}   
	    $log_id = $log_type_id='';
	    if($_SESSION['LOGIN_USER_TYPE'] == '2' )
	    {
    	    $admin_id = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    		$log_id = $admin_id->row['user_id'];
    		$log_type_id = $admin_id->row['user_type_id'];
	    }
	
		if(($_SESSION['ADMIN_LOGIN_SWISS'] =='1' && $_SESSION['LOGIN_USER_TYPE'] == '1' ) || ($log_id=='6' && $log_type_id=='4'))
		{
		
			$sql= "SELECT $sum pts.zipper,t.transport,pts.spout,pts.accessorie,t.ship_type,pts.valve,t.product_code_id,t.quantity,t.date_added,so.gen_order_id,p.product_name,cd.client_name,c.country_name,sos.process_by,t.template_order_id, sos.dispach_by,sos.status,t.user_id,t.user_type_id,am.user_name,t.client_id,t.stock_order_id,t.product_template_order_id,pts.width,pts.height,pts.gusset,pts.volume FROM template_order_test as t,stock_order_test as so,product as p,client_details as cd,country as c,stock_order_status_test as sos, account_master as am $dis_table ,product_template_size as pts,product_template pt $clr WHERE so.stock_order_id=t.stock_order_id AND t.product_id=p.product_id AND t.client_id=cd.client_id AND t.country=c.country_id AND t.template_order_id=sos.template_order_id AND t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id  AND t.template_order_id=sos.template_order_id AND am.user_id=t.user_id AND am.user_type_id=t.user_type_id $cond_st $cond $con $sta $over $date $user_condition $clr_cond $ship_type";
		 
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
				$str = ' OR ( t.user_id IN ('.$userEmployee.') AND t.user_type_id = 2 )';
			}
		 	
			$sql= "SELECT $sum pts.zipper,t.transport,pts.spout,pts.accessorie,t.ship_type,pts.valve,t.product_code_id,t.quantity,t.date_added,so.gen_order_id,p.product_name,cd.client_name,c.country_name,sos.process_by,t.template_order_id,sos.dispach_by,sos.status,t.user_id,t.user_type_id,am.user_name,t.client_id,t.stock_order_id,t.product_template_order_id,pts.width,pts.height,pts.gusset,pts.volume FROM template_order_test as t,stock_order_test as so,product as p,client_details as cd,country as c,stock_order_status_test as sos,account_master as am $dis_table,product_template_size as pts,product_template pt $clr WHERE so.stock_order_id=t.stock_order_id AND t.product_id=p.product_id AND t.client_id=cd.client_id AND t.country=c.country_id AND t.template_order_id=sos.template_order_id AND t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND t.template_order_id=sos.template_order_id AND am.user_id=t.user_id AND am.user_type_id=t.user_type_id   AND (t.user_id ='".$set_user_id."' AND t.user_type_id ='".$set_user_type_id."' $str) $cond_st $cond $con $sta $over $date $user_condition $clr_cond $ship_type";
		}
		$sql .=$group;
		//printr($sql);

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
			
			
	public function getUserList()
	{
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
		$data = $this->query($sql);
		return $data->rows;
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
		$data = $this->query($sql);
		return $data->row;
	}
			
	public function getDelay($delay)
	{
		$data = $this->query("SELECT sd.new_final_ddate FROM stock_delay_date_history_test as sd WHERE sd.template_order_id='".$delay."'");
		if($data->num_rows)
		{
			return $data->row;
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

	public function getStatus()
	{
		$data = $this->query("SELECT * FROM `stock_order_status_test` WHERE ");
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}	
	}
			
	public function getDispatchQty($template_order_id,$product_template_order_id)
	{
		$data=$this->query("SELECT sum(dis_qty) as total_dis_qty FROM stock_order_dispatch_history_test WHERE template_order_id='".$template_order_id."' AND product_template_order_id='".$product_template_order_id."' ");
		
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
		
		
	}
		
	// view part
	public function view_stock_report($stock_order_details,$post)
	{ 
		
	$html = "";
		 $html .= "<div class='form-group'>
				<div class='table-responsive'>
				&nbsp;&nbsp;<span> Searching Date From: <b>".dateFormat(4,$post['f_date'])."</b> To: <b>".dateFormat(4,$post['t_date'])."</b></span> <br><br>
			    <table class='table table-striped b-t text-small' id='stock_order' >
						<thead>
							<tr>
                                  <th>Sr No</th>
                                  <th>Order Date</th>
                                  <th>Order No</th>
                                  <th>Product Code</th>
                                  <th>Product Information</th>
								  <th>Product Description</th>
                                  <th>Client Name</th>
                                  <th>Shipment Country</th>
                                  <th>Shipment Type</th>
                                  <th>Accepted Date</th>                                          
                                  <th>Dispatch Date</th>
                                  <th>Order Qty</th>
								  <th>Dispatch Qty</th>
								  <th>Posted By</th>
                                  
                            </tr>
                        </thead>     
      
      					<tbody>";
						  	$i=1;
						if(isset($stock_order_details) && !empty($stock_order_details))
						{
								
						foreach($stock_order_details  as $reports)
						{
						    $dis_qty=$this->getDispatchQty($reports['template_order_id'],$reports['product_template_order_id']);
							$final_data=json_decode($reports['process_by']);
							$final_dispatch=json_decode($reports['dispach_by']);
							$final_decline=json_decode($reports['process_by']);
							
							$delay = $this->getDelay($reports['template_order_id']);
							if(isset($final_decline->action) && $final_decline->action == '2') 
							{
								$decline_date=  dateFormat(4,$final_decline->currdate);
							}
							else
							{
								$decline_date = 'na';
							}
							
							if(isset($final_data->action) && $final_data->action == '1')
							{
								$accept_date =  dateFormat(4,$final_data->currdate);
							}
							else
							{
								$accept_date = 'na';
							}
							if(isset($delay['new_final_ddate']) && ($delay['new_final_ddate']!='')) 
							{ 
								$final_tentatived= dateFormat(4,$delay['new_final_ddate']); 
							}
						    else
						    { 
								$final_tentatived =  'na'; 
							}
							if(isset($final_dispatch->currdate) && ($final_dispatch->currdate!='')) 
							{ 	$final_dis_date = dateFormat(4,$final_dispatch->currdate);
							} else { 
								$final_dis_date ='na';
								}
                            
							$html .= "<tr  valign='top'  href=".HTTP_SERVER."admin/index.php%3Froute=template_order&mod=dispatch&client_id=".encode($reports['client_id'])."&stock_order_id=".encode($reports['stock_order_id']).">

                             	<td>".$i."</td>
                                 <td>".dateFormat(4,$reports['date_added'])."</td> 
                                
                                <td>". $reports['gen_order_id']."</td>
                                
                                <td><b>".$reports['product_code']."</b></td>
                                
                                <td>". $reports['product_name']."<br><b>Color : </b>".$reports['color']."</td>
								
								<td>". $reports['zipper']." ".$reports['valve']." ".$reports['spout']." ".$reports['accessorie']."<br>
										<b>Dimension (Size) :</b> <span style='color:red'>".$reports['width']."X".$reports['height']."X".$reports['gusset']." (".$reports['volume'].")</span></td>
                                
                                <td>". $reports['client_name']."</td>
                                
                                <td>". $reports['country_name']."</td>
                                <td>". $reports['transport']."</td>
                                
                                <td>". $accept_date ."</td>              
                                
                                <td>".$final_dis_date."</td>
                                
                                <td>". $reports['quantity'] ."</td>
								
								<td>". $dis_qty['total_dis_qty'] ."</td>
                                
							   <td>".$reports['user_name']."</a>
							 </td>
                                
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
	
	public function stock_report($stock_order_details,$post)
	{
		//Printr($post);
		$ship='';
		if(!empty($post['Ship_type']))
      		$ship = 'Shipment By : '.$post['Ship_type'];
		$html = "";
		 $html .= "<div class='form-group' >
				<div class='table-responsive'>
				&nbsp;&nbsp;<span> Searching Date From: <b>".dateFormat(4,$post['f_date'])."</b> To: <b>".dateFormat(4,$post['t_date'])."<br>&nbsp;&nbsp;&nbsp;".$ship."</b></span><br><br>
			    <table class='table table-striped b-t text-small' id='stock_order' border='1' style='font-size:10.5px;'>
						<thead>
							<tr>
                                  <th>Sr No</th>
                                  <th>Product Code [Product Name]</th>
								  <th>Product Description</th>
                                  <th>Order Qty</th>

                            </tr>
                        </thead>     
      
      					<tbody>";
      					    $arg = '&F='.encode($post['f_date']).'&T='.encode($post['t_date']);
      					    $country = '';
      					    if(!empty($post['Shipment_Country']))
      					        $country = '&country='.encode($post['Shipment_Country']);
      					    
      					    $i=1;
      					    if(isset($stock_order_details) && !empty($stock_order_details))
							{
									 
							    foreach($stock_order_details  as $reports)
							    {
							     //   printrt($reports);
							        $html .= "<tr>
							                       <td valign='top'>".$i."</td>
							                       <td valign='top'><a href=".HTTP_SERVER."admin/index.php?route=stock_order_report&mod=view_ref_no&product_code_id=".encode($reports['product_code_id'])."".$arg."".$country.">".$reports['product_code']."&nbsp;&nbsp;[<span style='color:red'>".$reports['product_name']."</span>]</a> <br>".$reports['product_code_description'] ."</td>
							                       <td valign='top'>". $reports['zipper']." ".$reports['valve']." ".$reports['spout']." ".$reports['accessorie']."<br>
										                    <b>Dimension (Size) :</b> <span style='color:red'>".$reports['width']."X".$reports['height']."X".$reports['gusset']." (".$reports['volume'].")&nbsp;&nbsp;&nbsp;&nbsp;</span>
										                    <b>Color : </b>".$reports['color']."
										            </td>
										            <td valign='top'>". $reports['order_qty'] ."</td>
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
	
	public function getRef_details($product_code_id,$from,$to,$country)
	{
		
		/*$dis_cond = ' (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=0)';
		$dis_table = ', stock_order_dispatch_history_test as sodh';	
		$dis_select = 'sum(sodh.dis_qty) as  dis_qty,sum(sodh.dis_qty*t.price) as dis_total_price,';
		$cond_st = 'AND ('.$dis_cond.') AND t.status=1';*/
		
		$product_code_details = $this->query("SELECT * FROM `product_code` as pc,product as p  WHERE  product_code_id='".$product_code_id."' AND p.product_id=pc.product");
		$data = $this->query("SELECT *,c.client_name From template_order_test as t,stock_order_test as s,client_details as c  WHERE c.client_id=s.client_id AND s.stock_order_id=t.stock_order_id AND t.product_code_id='".$product_code_id."' AND date_added >= '".$from."' AND date_added <= '".$to."'  AND t.country='".$country."' GROUP BY t.stock_order_id");
		//printr($data);
		//printr("SELECT *,c.client_name From template_order_test as t,stock_order_test as s,client_details as c $dis_table WHERE c.client_id=s.client_id AND s.stock_order_id=t.stock_order_id AND t.product_code_id='".$product_code_id."' AND date_added >= '".$from."' AND date_added <= '".$to."' $cond_st AND t.country='".$country."' GROUP BY t.stock_order_id");
		$html = "";
		 $html .= "<div class='form-group' >
				<div class='table-responsive'>		
				<span>&nbsp;&nbsp;<b>Product Name: </b>".$product_code_details->row['product_name']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Product Description </b>".$product_code_details->row['description']."</span> <br><br>				
			    <table class='table table-striped b-t text-small' id='stock_order' border='1' style='font-size:10.5px;'>
						<thead>
							<tr>
                                  <th>Sr No</th>
								  <th>Client Name </th>
                                  <th>Buyer's Order/Ref No</th>								 
                                  <th>Order Qty</th>
                                 

                            </tr>
                        </thead>     
      
      					<tbody>";
      					    $i=1;
							if($data->num_rows){
      					  	
							    foreach($data->rows  as $reports)
							    {
									if($reports['reference_no']!=''){
										$ref_no=$reports['reference_no'];
									}else{
										$ref_no=$reports['gen_order_id'];
										}
							        $html .= "<tr >
													<td valign='top'>".$i."</td>
													<td valign='top'>".$reports['client_name']."</td>
													<td valign='top'>".$ref_no."</td>
													<td valign='top'>".$reports['quantity']."</td>
													
							                     
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
	public function GetCartOrderList($post)
	{
	    
		//printr($post);
		$user_id ='0';
		$con='';
		if(isset($post['user_name']) && !empty($post['user_name']))
		{
			$explode = explode('=',$post['user_name']);
			if($explode[1]  == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$explode[0]."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($explode[1],$explode[0]);						
				$set_user_id = $explode[0];
				$set_user_type_id = $explode[1];
			}
			
			$user_id = $post['user_name'];
			$con =  'AND pto.admin_user_id = "'.$set_user_id.'"';
		}
		$status_cond_stock = $stock_print_stock = $stock_print_digital = $status_cond_digital = $status_cond_custom = $stock_print_custom='';
		
		if(in_array('Digital', $post['order']) &&  in_array('Stock', $post['order'])  && in_array('Custom', $post['order']))
		{
		    $status_cond_stock = 'AND (sos.status="0" OR sos.status="1")';
		    $status_cond_custom ='AND (mco.accept_decline_status="0" OR mco.accept_decline_status="1")';
		}
		else
		{
		    if(in_array('Digital', $post['order']) && in_array('Stock', $post['order']))
    		{
    		    $status_cond_stock = 'AND (sos.status="0" OR sos.status="1")';
    		    $stock_print_stock = 'AND (stock_print="" OR stock_print="stock" OR stock_print="Digital Print")';
    		}
    		else
    		{
        		if(in_array('Digital', $post['order']))
        		{
        		    $status_cond_digital = 'AND (sos.status="0" OR sos.status="1")';
        		    $stock_print_digital = 'AND stock_print="Digital Print"';
        		}
        		if(in_array('Stock', $post['order']))
        		{
        		    $status_cond_stock = 'AND (sos.status="0" OR sos.status="1")';
        		    $stock_print_stock = 'AND (stock_print="" OR stock_print="stock")';
        		}
    		}
    		if(in_array('Custom', $post['order']))
    		{//printr('Custom');
    		    $status_cond_custom = 'AND (mco.accept_decline_status="0" OR mco.accept_decline_status="1")';
    		}
		}

	
		if(in_array('Digital', $post['order']) || in_array('Stock', $post['order']))
		{
	        $sql="SELECT pci.product_code,sos.status,st.admin_user_id,t.product_template_order_id,t.template_order_id,t.digital_print_color,t.stock_print,t.order_type,t.buyers_order_no,t.reference_no,cd.client_name,st.gen_order_id,st.address_book_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,cu.currency_code,pt.transportation_type,pts.zipper,pts.spout,pts.accessorie,pts.valve,pts.width,pts.height,pts.gusset,pts.volume,p.product_name,pc.color,t.quantity FROM " .DB_PREFIX . "template_order_test t,client_details cd,stock_order_test st,country c,stock_order_status_test as sos,product_template_order_test as pto,product_template as pt,currency as cu,product_template_size as pts,pouch_color as pc,product as p,product_code as pci WHERE c.country_id = t.country AND p.product_id=t.product_id AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$con." ".$status_cond_stock." ".$stock_print_stock." ".$status_cond_digital." ".$stock_print_digital." AND st.stock_order_id=t.stock_order_id AND t.status=1 AND  t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color AND t.product_code_id = pci.product_code_id ";
	        $data = $this->query($sql);
		}
		//printr($sql);
	    if(in_array('Custom', $post['order']))
	    {
    		$cust_data = $this->getCustomAcceptedRecords($status_cond_custom,1,$user_id);
    		if(!empty($cust_data))
    		{
    			foreach($cust_data as $cust)
    			{
    				array_push($data->rows,$cust);
    			}
    		}
	    }
	    //printr($cust_data);
		if($data->num_rows)
		{
			
			return $data->rows;
		}
		else
		{
			if(!empty($cust_data))
				return $cust_data;
			else
				return false;
		}
	}
	public function getCustomAcceptedRecords($status_cond_custom='',$group_by='',$user_id)
	{
		
		
		$getData = " mco.dis_qty,mcoi.date_added,mco.accept_decline_status,custom_order_id,mco.added_by_user_id,mco.added_by_user_type_id, customer_name, shipment_country_id,mco.multi_custom_order_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,mco.status,mco.custom_order_status,discount";
		
		$custom_data = $this->getCustomOrder($getData,$status_cond_custom,$group_by,$user_id);
		return $custom_data;
	}
	public function getCustomOrder($getData = '*',$status_cond_custom='',$group_by,$user_id)
    {
    		
    		
    		$admin_user_id_cond=$group='';
    		if($user_id!='0')
    		    $admin_user_id_cond =  'AND mcoi.admin_user_id = "'.$user_id.'"';
    
    		if($group_by!='')
    			$group = 'GROUP BY mcoi.admin_user_id,mcoi.multi_custom_order_id';
    			
    	    $sql = "SELECT $getData,mcoi.admin_user_id,cn.country_name,mcoi.reference_no,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN multi_custom_order_id as mcoi ON(mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE mcoi.is_delete=0  ".$status_cond_custom." $group $admin_user_id_cond ORDER BY mcoi.admin_user_id ,mcoi.multi_custom_order_id DESC ";//LIMIT 30
            //printr($sql1);
            
    		$data = $this->query($sql);
    		
    		$cust_array = '';
    		if($data->num_rows)
    		{	
    			   foreach($data->rows as $dat)
    			   {
    			  
        				 $multi_custom_order_id=$dat['multi_custom_order_id'];
        				 $custom_order_id=$dat['custom_order_id'];
        				 $cdata[$dat['custom_order_id']] = array('company_name'=>$dat['company_name'],
        				 										 'multi_custom_order_number'=> $dat['multi_custom_order_number'],
        				 										 'reference_no'=> $dat['reference_no'],
        														 'multi_custom_order_id'=> $dat['multi_custom_order_id'],
        														 'date_added'=> $dat['date_added'],
        														 'country_name'=> $dat['country_name'],
        														 'added_by_user_id'=> $dat['added_by_user_id'],
        														 'added_by_user_type_id'=> $dat['added_by_user_type_id'],
        														 'customer_name'=> $dat['company_name'],
        														 'admin_user_id'=>$dat['admin_user_id']);
        				 $result = $this->getCustomOrderQuantity($dat['custom_order_id']);
        				 if($result!='')
        					$quantityData[] =$result;
        				
    			   	}
    					//die;
    			   if(!empty($quantityData))
    			   {
    					foreach($quantityData as $k=>$qty_data)
    					{
    						foreach($qty_data as $tag=>$qty)
    						{
    							foreach($qty as $q=>$arr)
    							{
    								$new_data[$tag][$q][]=$arr[0];
    							}
    						}	
    					}
    			   }
    			  // printr($new_data);
    			   foreach($new_data as $k=>$qty_data)
    			   {
    					foreach($qty_data as $skey=>$sdata)
    					{
    						foreach($sdata as $soption)
    						{
    							$cust_array[] = array('product_template_order_id' => $soption['custom_order_quantity_id'],
    													'buyers_order_no' =>  '',
    													'template_order_id' =>  '',
    													'client_name' =>  ucwords($cdata[$soption['custom_order_id']]['customer_name']),
    													'gen_order_id' =>  $cdata[$soption['custom_order_id']]['multi_custom_order_number'],
    													'stock_order_id' => $cdata[$soption['custom_order_id']]['multi_custom_order_id'],
    													'date_added' =>  $cdata[$soption['custom_order_id']]['date_added'],
    													'country_name' =>  $cdata[$soption['custom_order_id']]['country_name'],
    													'admin_user_id' =>  $cdata[$soption['custom_order_id']]['admin_user_id'],
    													'ship_type' =>  0,
    													'client_id' =>  0,
    													'user_id' => $cdata[$soption['custom_order_id']]['added_by_user_id'],
    													'user_type_id' =>  $cdata[$soption['custom_order_id']]['added_by_user_type_id'],
    													'currency_code' =>  $soption['currency'],
    													'transportation_type' =>  'By '.ucwords($k),
    													'zipper' =>  ucwords($soption['zipper_txt']),
    													'spout' => ucwords($soption['spout_txt']),
    													'accessorie' =>  ucwords($soption['accessorie_txt']),
    													'valve' =>  ucwords($soption['valve_txt']),
    													'width' => (int)$soption['width'],
    													'height' =>  (int)$soption['height'],
    													'gusset' =>  $soption['gusset'],
    													'volume' =>  $soption['volume'],
    													'product_name' =>  $soption['product_name'],
    													'product_id' =>  $soption['product_id'],
    													'color' =>  '',
    													'quantity' =>$skey,
    													'custom_order_id' =>  $soption['custom_order_id'],
    													'accept_decline_status' =>  $soption['accept_decline_status'],
    													'total_price' => $soption['totalPrice'],
    													'currency_price' => $soption['currency_price'],
    													'dis_total_price' => $this->numberFormate(((($soption['totalPrice']/$skey)/$soption['currency_price'])*$soption['dis_qty']),"3"),
    													'total_qty'=>$skey,
    													'dis_qty'=>$soption['dis_qty'],
    													'expected_ddate'=>$soption['expected_ddate'],
    													'note'=>'',
    													'title'=>$soption['product_name'],
    													'price'=>$this->numberFormate((($soption['totalPrice']/$skey)/$soption['currency_price']),"3"),
    													'address'=>'',
    													'review'=>$soption['review'],
    													'track_id'=>$soption['track_id'],
    													'courier_id'=>$soption['courier_id'],
    													'date'=>$soption['date'],
    													'process_by'=>$soption['process_by'],
    													'dispach_by'=>$soption['dispach_by'],
    													'status'=>$soption['accept_decline_status'],
    													'price_uk'=>'0.000',
    													'order_type'=>'',
    													'reference_no'=>'',
    													'filling_details'=>'',
    													'reference_no'=>$cdata[$soption['custom_order_id']]['reference_no'],
    													'stock_print'=>'',
    													'digital_print_color'=>'',
    													'digital_dieline'=>'',
    													'product_code'=>'',
    													);
    						}					
    					}
    			   }
    			  
    		}
    	//printr($cust_array);die;
    	return $cust_array;
    }
	public function getCustomOrderQuantity($custom_order_id)
	{
		//printr($custom_order_id);die;
		//echo '<br>';
		$paking_price=$this->getCustomOrderPackingAndTransportDetails($custom_order_id);
		//printr("SELECT mco.track_id,mco.courier_id,mco.date,mco.process_by,mco.dispach_by,mco.review,mco.expected_ddate,mco.dis_qty,mco.currency_price,mco.currency,mco.accept_decline_status,mco.custom_order_id,mcoq.cust_quantity,mcoq.custom_order_quantity_id,mco.product_id,p.product_name, mcoq.custom_order_id,mcoq.discount, printing_effect,mcoq.quantity,mco.height,mco.width,mco.gusset,mco.volume, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mco.layer,total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage FROM " . DB_PREFIX ."multi_custom_order_quantity as mcoq,multi_custom_order as mco,product as p WHERE mcoq.custom_order_id = '".(int)$custom_order_id."' AND mcoq.custom_order_id=mco.custom_order_id AND mco.product_id=p.product_id ORDER BY mcoq.custom_order_quantity_id");

		$data = $this->query("SELECT mco.track_id,mco.courier_id,mco.date,mco.process_by,mco.dispach_by,mco.review,mco.expected_ddate,mco.dis_qty,mco.currency_price,mco.currency,mco.accept_decline_status,mco.custom_order_id,mcoq.cust_quantity,mcoq.custom_order_quantity_id,mco.product_id,p.product_name, mcoq.custom_order_id,mcoq.discount, printing_effect,mcoq.quantity,mco.height,mco.width,mco.gusset,mco.volume, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mco.layer,total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage FROM " . DB_PREFIX ."multi_custom_order_quantity as mcoq,multi_custom_order as mco,product as p WHERE mcoq.custom_order_id = '".(int)$custom_order_id."' AND mcoq.custom_order_id=mco.custom_order_id AND mco.product_id=p.product_id ORDER BY mcoq.custom_order_quantity_id") ;
		//printr($data);
		$return = '';
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				//printr($qunttData);
				$zdata = $this->query("SELECT cust_total_price,custom_order_price_id, custom_order_id, custom_order_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt, total_price,total_price_with_excies,total_price_with_tax,tax_name,tax_type,tax_percentage,excies, gress_price, customer_gress_price,zipper_price,valve_price,courier_charge,spout_base_price,accessorie_base_price,transport_price,make_name FROM " . DB_PREFIX ."multi_custom_order_price as mcop ,product_make as pm WHERE custom_order_id = '".(int)$qunttData['custom_order_id']."' AND custom_order_quantity_id = '".(int)$qunttData['custom_order_quantity_id']."' AND mcop.make_pouch=pm.make_id ORDER BY transport_type");	
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
					
						$materialData = $this->getCustomOrderMaterial($zipData['custom_order_id']);
						//printr($materialData);
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
						if($zipData['spout_txt']=='No Spout')
							$zipData['spout_txt']='';
						if($zipData['accessorie_txt']=='No Accessorie')
							$zipData['accessorie_txt']='';
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
								'cust_quantity'=>$qunttData['cust_quantity'],
								'cust_total_price'=>$zipData['cust_total_price'],
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
								'custom_order_id'=>$qunttData['custom_order_id'],
								'accept_decline_status'=>$qunttData['accept_decline_status'],
								'currency'=>$qunttData['currency'],
								'currency_price'=>$qunttData['currency_price'],
								'dis_qty'=>$qunttData['dis_qty'],
								'expected_ddate'=>$qunttData['expected_ddate'],
								'review'=>$qunttData['review'],
								'track_id'=>$qunttData['track_id'],
								'courier_id'=>$qunttData['courier_id'],
								'date'=>$qunttData['date'],
								'process_by'=>$qunttData['process_by'],
								'dispach_by'=>$qunttData['dispach_by'],
							
							);//printr($return);
					}//printr($return);
				}
			}
		}
	//	printr($return);
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
	public function pending_order_report($stock_order_details,$post)
	{
		$html = $cust = $stock = $digi = "";
		 $html .= "<div class='form-group' >
				<div class='table-responsive'>";
				
				if(isset($post['user_name']) && !empty($post['user_name']))
        		{
        			$user_nm = $this->getUser($post['user_name'],'4');
        			$html .= "&nbsp;&nbsp;<span> Report For : <b>".$user_nm['first_name']." ".$user_nm['last_name']."</b></span> <br><br>";
        		}
				if(isset($post['order']))
        		{
        			if(in_array('Digital', $post['order']))
        			    $digi = 'Digital Print';
        			if(in_array('Stock', $post['order']))
        			    $stock = 'Stock';
        			if(in_array('Custom', $post['order']))
        			    $cust = 'Custom';
        			$html .= "&nbsp;&nbsp;<span> Order Type : <b style='color:red;'>".$stock." </b> , <b style='color:green;' >".$digi." </b> , <b style='color:blue;'>".$cust."</b>  Order </span> <br><br>";
        		}
				
			$html .= "<table class='table table-striped b-t text-small' id='stock_order' border='1' style='font-size:10.5px;'>
						<thead>
							<tr>
                                  <th>Sr No</th>
                                  <th>Order No. [Order Type]</th>
                                  <th>Product Code [Product Name]</th>
								  <th>Product Description</th>
                                  <th>Order Qty</th>
                                  <th>Remaining Qty</th>
                                  <th>Status</th>";
                                  if($post['user_name']=='')
                                    $html.="<th>User Name</th>";
                     $html.="</tr>
                        </thead>     
      
      					<tbody>";
      					    $arg = '&F='.encode($post['f_date']).'&T='.encode($post['t_date']);
      					    $country = '';
      					    if(!empty($post['Shipment_Country']))
      					        $country = '&country='.encode($post['Shipment_Country']);
      					    $i=1;
      					    if(isset($stock_order_details) && !empty($stock_order_details))
							{
									
							    foreach($stock_order_details  as $reports)
							    {
							        $dis_qty=$this->getDispatchQty($reports['template_order_id'],$reports['product_template_order_id']);//
							        $html .= "<tr>
							                       <td valign='top'>".$i."</td>
							                       <td valign='top'>".$reports['gen_order_id'];
							                       if($reports['stock_print'] == 'stock')
							                            $html.=" <span style='color:blue'><b>Stock</b></span>";
							                       elseif($reports['stock_print']=='Digital Print')
							                            $html.=" <span style='color:red'><b>Digital Print</b></span>";
							                       else
							                            $html.="";
							               $html.="</td>
							                       <td valign='top'><b>".$reports['product_code']."</b>&nbsp;&nbsp;[<span style='color:red'>".$reports['product_name']."</span>]</td>
							                       <td valign='top'>". $reports['zipper']." ".$reports['valve']." ".$reports['spout']." ".$reports['accessorie']."<br>
										                    <b>Dimension (Size) :</b> <span style='color:red'>".$reports['width']."X".$reports['height']."X".$reports['gusset']." (".$reports['volume'].")&nbsp;&nbsp;&nbsp;&nbsp;</span>
										                    <b>Color : </b>".$reports['color']."
										            </td>
										            <td valign='top'>".$reports['quantity']."</td>
										            <td valign='top'>".($reports['quantity']-$dis_qty['total_dis_qty'])."</td>
										            <td valign='top'>";
										                if($reports['status']=='0')
										                    $html.="<b style='color:red;'>New Order</b>";
										                else
										                    $html.="<b style='color:green;'>Accepted</b>";
										  $html .= "</td>";
										         if($post['user_name']=='')
										         {
										            if($reports['admin_user_id']=='0')
										                $html.="<td valign='top'>Swiss Pac</td>";
										            else
										            {
										                $user_nm = $this->getUser($reports['admin_user_id'],'4');
										                $html.="<td valign='top'>".$user_nm['first_name']." ".$user_nm['last_name']."</td>";
										            }
										         } 
							            $html.="</tr>";
							    
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
	public function getdeatiluser($product_template_order_id,$template_order_id)
	{
		$temp_id ='';
		if($template_order_id != '')
		{
			$temp_id = "AND t.template_order_id='".$template_order_id."'";
		}
		$sql="SELECT st.admin_user_id,t.user_id,t.user_type_id FROM template_order_test as t,stock_order_test as st WHERE t.product_template_order_id='".$product_template_order_id."'  $temp_id AND t.status=1 ANd t.stock_order_id=st.stock_order_id";
	$data=$this->query($sql);
	return $data->row;
	}
	public function getFinalddate($product_template_order_id,$template_order_id)
	{
		$temp_id ='';
		if($template_order_id!='')
		{	
			$temp_id = " AND template_order_id='".$template_order_id."'";
		}
		$sql = "SELECT *  FROM " . DB_PREFIX ."stock_delay_date_history_test WHERE product_template_order_id='".$product_template_order_id."' $temp_id ORDER BY delay_history_id DESC";
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
	public function getdeatilCustuser($multi_custom_order_id)
	{
		$sql="SELECT added_by_user_id,added_by_user_type_id,admin_user_id FROM multi_custom_order_id WHERE multi_custom_order_id='".$multi_custom_order_id."'";
		$data=$this->query($sql);
		return $data->row;
	}
	public function declineQty($template_order_id,$product_template_order_id)
	{	
		$sql = "SELECT decline_qty FROM " . DB_PREFIX ."stock_order_dispatch_history_test  WHERE product_template_order_id='".$product_template_order_id."' AND template_order_id='".$template_order_id."' AND decline_qty!=0";
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
		public function GetOrderList($user_id,$usertypeid,$status='',$data='',$con='',$filter_array=array(),$client_id='',$dis_cond='',$dis_table='',$dis_select='',$page='',$st='',$stock_order_id='',$custom_order_id='')
	{
		//printr($custom_order_id);
		//die; 
		 $menu_id = $this->getMenuPermission(ORDER_ACCEPT_ID,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		 //printr($menu_id);
		$admin = '';
		if($status=='')
			$status =' AND pto.order_id = t.product_template_order_id';
			
			if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'  ";
				$dataadmin = $this->query($sqladmin);
				$cond = ' AND pto.admin_user_id = '. $dataadmin->row['user_id'].'';
				$admin_user_id =  $dataadmin->row['user_id'];
				$table= 'employee as ib ,';
				//echo $cond;
			}
			elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
			{
				$cond = ' AND pto.admin_user_id = '. $_SESSION['ADMIN_LOGIN_SWISS'].' ';
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
				$admin = ' AND pto.admin_user_id="'.$admin_user_id.'"';
			}
			
			/*if($in_process_status != '0')
			{
				$sql = "SELECT t.quantity, sos.dis_qty FROM stock_order_status as sos, template_order as t WHERE sos.template_order_id = t.template_order_id  AND t.client_id = '".$client_id."'";
				$d = $this->query($sql);
				//foreach 
				printr($d);
				
			}
			else
			{
				
			}*/
			
			if($client_id!='')
			{
				$client_id=" AND t.client_id = '".$client_id."' AND ";
				
			}
			else
			{
				$client_id =" AND ";
			}
			if($stock_order_id!='')
			{
				$stock_order_id =" t.stock_order_id= '".$stock_order_id."' AND";
			}
			else
			{
				$stock_order_id = " ";
			}
			
	/*	$sql = "SELECT ".$dis_select." so.gen_order_id,t.client_id,t.expected_ddate,t.note,t.template_order_id,t.quantity,p.product_id,p.email_product,p.product_name,pt.title,pts.width,pts.height,pts.gusset,pto.shipment_country,pto.ship_type as pto_ship_type,pto.admin_user_id,t.transport,
pts.volume,t.note,c.country_name,cu.currency_code,pc.email_color,pc.color,t.user_id,t.user_type_id,t.price,pts.valve,cd.client_name,		pts.zipper,pts.spout,pts.accessorie,t.ship_type,pt.product_template_id,t.address,t.date_added,pt.transportation_type,t.product_template_order_id,sos.review,sos.track_id,sos.date,sos.courier_id,pto.order_id,sos.process_by,sos.dispach_by,sos.status,pts.quantity1000,pts.quantity2000,pts.quantity5000,pts.quantity10000,t.price_uk FROM " .DB_PREFIX . " template_order t,currency as cu,product as p,product_template pt,product_template_size as pts,country as c,pouch_color as pc, product_template_order as pto,stock_order_status as sos, courier as co,client_details as cd,stock_order as so ".$dis_table."  WHERE t.product_id = p.product_id AND pt.product_template_id = t.template_id AND so.stock_order_id=t.stock_order_id AND so.client_id=t.client_id AND pto.admin_user_id=so.admin_user_id AND ".$con." t.template_order_id=sos.template_order_id  ".$status." AND pt.currency = cu.currency_id AND t.is_delete = 0  ".$cond."  
".$client_id."  t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color AND t.country=c.country_id AND pto.order_id = t.product_template_order_id ".$admin." AND  t.is_delete = 0 AND t.client_id=cd.client_id";*/	



	$sql = "SELECT ".$dis_select." t.reference_no,t.filling_details,t.front_color,t.back_color,t.stock_print,t.front_color,t.back_color,t.digital_print_color,t.digital_dieline,so.gen_order_id,so.address_book_id,t.client_id,t.order_type,t.expected_ddate,t.note,t.template_order_id,t.quantity,p.product_id,p.email_product,p.product_name,pt.title,pts.width,pts.height,pts.gusset,pto.shipment_country,pto.ship_type as pto_ship_type,pto.admin_user_id,t.transport,
pts.volume,t.note,c.country_name,cu.currency_code,pc.email_color,pc.color,t.user_id,t.user_type_id,t.price,pts.valve,cd.client_name,pts.zipper,pts.spout,pts.accessorie,t.ship_type,pt.product_template_id,pts.product_template_size_id,t.address,t.date_added,pt.transportation_type,t.product_template_order_id,sos.review,sos.date,pto.order_id,sos.process_by,sos.dispach_by,sos.status,pts.quantity1000,pts.quantity2000,pts.quantity5000,pts.quantity10000,t.price_uk FROM " .DB_PREFIX . " template_order_test t,currency as cu,product as p,product_template pt,product_template_size as pts,country as c,pouch_color as pc, product_template_order_test as pto,stock_order_status_test as sos, courier as co,client_details as cd,stock_order_test as so ".$dis_table."  WHERE t.product_id = p.product_id AND pt.product_template_id = t.template_id AND so.stock_order_id=t.stock_order_id AND so.client_id=t.client_id AND pto.admin_user_id=so.admin_user_id AND ".$con." t.template_order_id=sos.template_order_id  ".$status." AND pt.currency = cu.currency_id AND t.is_delete = 0  ".$cond."  
".$client_id." ".$stock_order_id."  t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color AND t.country=c.country_id AND pto.order_id = t.product_template_order_id ".$admin." AND  t.is_delete = 0 AND t.client_id=cd.client_id";
//echo $sql.'<br>';//die; AND co.courier_id = sos.courier_id,,co.courier_name
	
		if(!empty($filter_array)) {
			if(!empty($filter_array['order_no'])){
				$sql .= " AND so.gen_order_id = '".$filter_array['order_no']."'";				
			}
			
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(t.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND p.product_name = '".$filter_array['product_name']."'";
			}
			if(!empty($filter_array['postedby']))
			{
				$spitdata = explode("=",$filter_array['postedby']);
				$sql .=" AND t.user_type_id = '".$spitdata[0]."' AND t.user_id = '".$spitdata[1]."'";
			}				
		}
		$sql .= " GROUP BY t.template_order_id"; 
		
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
//echo $sql.'<br>';//die;
		$data = $this->query($sql);
		//printr($data);
		//printr($custom_order_id);
		if($custom_order_id!='')
		{
			$cust_data = $this->getCustomAcceptedRecords('3',$custom_order_id);
			//printr($cust_data);
			if(!empty($cust_data))
			{
				foreach($cust_data as $cust)
				{
					//printr($cust);
					array_push($data->rows,$cust);
				}			
			}
		}
		
	//printr($data->rows);
		if($data->num_rows)
		{
			//echo $con;
			return $data->rows;
		}
		else
		{
			if($custom_order_id!='')
				return $cust_data;
			else
				return false;
		}
	}
	public function getMenuPermission($menu_id,$user_id,$user_type_id)
	{
		$cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$cond." AND user_type_id = '".$user_type_id."' AND user_id ='".$user_id."'";
		$data = $this->query($sql);
		return $data->rows;
	}
		public function GetdigitalColorName($pouch_color_id){
	    
	  $arr=explode("==",$pouch_color_id);
	    	$sql = "SELECT  color FROM  pouch_color WHERE is_delete = '0' AND pouch_color_id='".$arr[0]."'";
//		echo $sql;die;
		$data = $this->query($sql);
		return $data->row['color'];
	}
	public function ViewOrderEmail($post=array(),$status)
	{
	  //  printr($post);die;	
		$id=array(); 

		foreach($post as $val)
		{
			$arr = explode("==",$val);
			if(count($arr)=='2')
				$id[]=array('custom_order_id'=>$arr[0],'multi_custom_order_id'=>$arr[1]);
			else
				$id[]=array('template_order_id'=>$arr[0],'product_template_order_id'=>$arr[1],'client_id'=>$arr[2]);
				
			$k=$id[0];
		}
		 
	//	printr($id);die; 
	/*	$data = $this->query("SELECT group_id FROM stock_order_email_history_id_test ORDER BY group_id DESC LIMIT 1");
		if($data->num_rows>0)
		{
			$group_id = $data->row['group_id']+1;
		}
		else
			$group_id=1;
			$con='';*/
		foreach($id as $order_id)
		{
			$decline_html='';
			$html_ddate='';
			
			$menu_id =0;
			$template_order_id ='';
			if(!isset($order_id['custom_order_id']))
			{
				$con = '  t.template_order_id = '.$order_id['template_order_id'].' AND';
				$template_order_id=$order_id['template_order_id'];
			}
			else
			{
				$con='';
			}
			if($status>0)
			{
				$dis_status =$dis_cond =$dis_table=$dis_select='';
				
				if($status == '3')
				{
					$dis_status = 'OR sos.status= 1 ';
				}
				if(isset($order_id['custom_order_id']))
					$cond='';
				else
					$cond = 'AND t.status = 1 AND (sos.status='.$status.' '.$dis_status.')  AND  t.client_id ='.$order_id['client_id'].'';
					
				if($status==1)
				{
					//accept order
					$bg_color_code='#FFF0BA';
					$color_code='#FFDC5C';
					$span_color ='#FFC800';
					$span_class ='label bg-warning';
					$subject = 'Accepted Orders';
		        	$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$order_id['template_order_id']);
				/*	
					$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
					$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
					$toEmail[$datauser['user_name']]=$datauser['email'];
					$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];
					if($datauser_admin['email1']!='' && $datauser_admin['email1']!=$datauser_admin['email'])
					    $toEmail[$datauser_admin['user_name']]=$datauser_admin['email1'];*/
					    
					    
					$final_ddate = $this->getFinalddate($order_id['product_template_order_id'],$order_id['template_order_id']);
					$new_date = $final_ddate['new_final_ddate'];
					//Permission Accept/Decline 79
					$menu_id = array('"79"','"80"');
					$html_ddate = $new_date;

				}
				if($status==2)
				{
					//decline order
					if(isset($order_id['custom_order_id']))
					{ 
						$user_cust_detail=$this->getdeatilCustuser($order_id['multi_custom_order_id']);
						$user_detail['user_id'] = $user_cust_detail['added_by_user_id'];
						$user_detail['user_type_id']=$user_cust_detail['added_by_user_type_id'];
						$user_detail['admin_user_id']=$user_cust_detail['admin_user_id'];
						$dec_qty_cust = $this->getCustomAcceptedRecords('2',$order_id['custom_order_id']);
						$dec_qty['decline_qty'] = $dec_qty_cust[0]['quantity'];
					}
					else
					{
						$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$template_order_id);
						$dec_qty=$this->declineQty($order_id['template_order_id'],$order_id['product_template_order_id']);
					}
					$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
					$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
					$toEmail[$datauser['user_name']]=$datauser['email'];
					$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];
					if($datauser_admin['email1']!='' && $datauser_admin['email1']!=$datauser_admin['email'])
					    $toEmail[$datauser_admin['user_name']]=$datauser_admin['email1'];
					$menu_id = array('"79"');	
						//Permission Accept/Decline 79	
					$decline_html = '<br><span>Your '.$dec_qty['decline_qty'].' Qty is Rejected</span>';
				}
				if($status==3)
				{
					$bg_color_code='#D2FFE5';
					$color_code='#8CED9C';
					$span_color ='#D2FFE5';
					$span_class ='label bg-success';
					$subject = 'Dispatched Orders';
					$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$order_id['template_order_id']);
				/*	$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
					$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
					if($datauser_admin['email1']!='' && $datauser_admin['email1']!=$datauser_admin['email'])
					    $toEmail[$datauser_admin['user_name']]=$datauser_admin['email1'];
					$toEmail[$datauser['user_name']]=$datauser['email'];
					$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];*/
					$menu_id = array('"79"','"80"');
					
						//Permission Accept/Decline 79  Permission Dispatched  80
				}	
			}
			elseif($status==0)
			{ //place order
				$cond = 'AND t.status = 1 AND sos.status='.$status.' AND pto.order_id='.$order_id['product_template_order_id'].' AND  t.client_id ='.$order_id['client_id'].'';
				$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$order_id['template_order_id']);
				
			/*	$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
				$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
				if($datauser_admin['email1']!='' && $datauser_admin['email1']!=$datauser_admin['email'])
					    $toEmail[$datauser_admin['user_name']]=$datauser_admin['email1'];
				$toEmail[$datauser['user_name']]=$datauser['email'];
				$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];
				$menu_id =array('"79"');*/
				//Permission Accept/Decline 79
			}
		
			$permissionData = '';
	
		/*	if($menu_id >0)
				$permissionData = $this->getUserPermission($menu_id);*/
				
				
				// sonu  added email for ankitsir on 20-4-2017  				
				//offline_id = 71 online id = 96
		//		$remove_email_ankit_sir = $this->getUser(96,2);
			
			//[kinjal] Edited on 9-5-2017	
		/*	if(!empty($permissionData))
			{
				foreach($permissionData as $email_id)
				{
						$remove_email_ankit_sir_a[$remove_email_ankit_sir['user_name']] = $remove_email_ankit_sir['email'];
						
						if(!in_array($email_id['email'],$remove_email_ankit_sir_a))
						{
							$toEmail[$email_id['user_name']] = $email_id['email'];	
						}
						
				}
				
			}*/
			$setHtml = '';
			$sub = '';
			$insert_qry = '';
			$setHtml .= '<div class="table-responsive">';
				
				$custom_order_id='';
				if(isset($order_id['custom_order_id']))
					$custom_order_id = $order_id['custom_order_id'];
					
				if(isset($order_id['custom_order_id']))
					$orders = $this->getCustomAcceptedRecords('2',$custom_order_id);
				else
					$orders = $this->GetOrderList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$cond,$status,$con);
			    
			 		
			foreach($orders as $data)
			{
				$ref ='';
				if($data['reference_no']!='' && $data['reference_no']!='0')
				    $ref = ' ['.$data['reference_no'].'] ';
				$new_data[$data['gen_order_id'].' '.$ref][]=$data;
				
			}	
			ksort($new_data);
			$f = 1;
			$total=0;$total_qty=0;
			$toEmail['swisspac'] = $adminEmail;
			$order_type='';
			foreach($new_data as $gen_order_id=>$data)
			{  
				
				$setHtml .="<div><b>Order No : ".$gen_order_id.'</b>';
					
				$sub .= $gen_order_id.' , ';
				if($status=='1')
				{
					$setHtml .="<span>Your Final Delivery Date is </span><br><br>";
				}
				$digi = '0';
				foreach($data as $order)
				{	
					if($order['order_type']!='')
						$order_type=$order['order_type'];
						
					$insert_qry .= "('".$gen_order_id."','".$order['template_order_id']."','".$order['product_template_order_id']."','".$_SESSION['ADMIN_LOGIN_SWISS']."','".$_SESSION['LOGIN_USER_TYPE']."',NOW(),'".$group_id."','".$order['client_id']."','".$check."') , ";
					
					$setHtml .='<br><br>Your Reference : '.$order['note'].'<br>';
					$setHtml .='<br><br>'.$order['quantity'].'&nbsp;&nbsp; X &nbsp;&nbsp;'.$order['volume'].'&nbsp;';
					
					//kinjal done on [2-08-2018]
					if($order['product_id']==7 && ($order['volume']=='250. gm' || $order['volume']=='500. gm'))
					    $setHtml.='<span style="color:red"> New Size </span>';
					
					if(!isset($order['custom_order_id']))
						$setHtml .='<span><b>'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_color']).' </b></span><span>';
					
					if(isset($order['product_id']) && $order['product_id']!=3)
						$setHtml .='<b>';
					if(!isset($order['custom_order_id']))
						 $setHtml .=preg_replace("/\([^)]+\)/","",preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_product']));
					else
						$setHtml .=preg_replace("/\([^)]+\)/","",preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['product_name']));
					
					if(!isset($order['custom_order_id']) && $order['stock_print']=='Digital Print')
					{
					    $digital_color=$this->GetdigitalColorName($order['digital_print_color']);
					    $setHtml.='<span style="color:red;"><b> Digital Printing With '.$digital_color.'</b></span><br>';
					    $setHtml.='<b> Front Side :  '.$order['front_color'].' Color <br>Back Side :  '.$order['back_color'].' Color</b>';
					    $digi = '1';
					    $toEmail['Jagdish Kotak'] = 'digital@swisspac.net';
					   if($order['digital_dieline']!='')
					   { 
    					    $ext = pathinfo($order['digital_dieline'], PATHINFO_EXTENSION);
    					   if($ext=='pdf')
    					        $url_dieline[] = DIR_UPLOAD.'admin/digital_print_dieline/'.$order['digital_dieline'].'';
    					   else
    					        $url_dieline[] = DIR_UPLOAD.'admin/digital_print_dieline/500_'.$order['digital_dieline'].'';
					   }
					   /*if( $_SESSION['LOGIN_USER_TYPE']=='4' && $_SESSION['ADMIN_LOGIN_SWISS']=='10')
		                    printr($order['stock_print']);*/
					}   
					 if(isset($order['product_id']) && $order['product_id']!=3)
						$setHtml .='</b>';
					 $setHtml .='</span>';
					  $setHtml .=$decline_html;
					$setHtml .='<br>Option : <span style="color:#FF0000;"> '.$order['zipper'].'</span>  <span style="color:#060;">'.$order['valve'].'</span> <span style="color:#FF6600;">'.$order['spout'].'  '.$order['filling_details'].'</span> <span style="color:#0000FF;font-size: small;"><b>'.$order['accessorie'].'</b></span>';
					$setHtml .='<br>';
					$postedByData = $this->getUser($order['user_id'],$order['user_type_id']);
				}
				
				if($order['address']!='')
				{
					$name='Customer&prime;s Address Below ';
					$address=$order['address'];
					$color='red';
				}
				else
				{	
					$name='Below Address';
					$address=$postedByData['address'].'<br>'.$postedByData['city'].' , '.$postedByData['state'].' ( '.$postedByData['country_name'].' )<br>'.$postedByData['postcode'].'<br>'.$postedByData['email'];
					$color='black';
				}
				if($status!=2)
				{
					$setHtml.='<br><br><b><span style="color:'.$color.'">Dispatch Directly To '.$name.'   <span style="color:blue">'.$order['transportation_type'].'</span> :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>'.$address.'</b></pre><br><br>';
				}
				if($order['review']!='' && $status==2)
					$setHtml.='<br><br><b><span style="color:red">Review :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>'.$order['review'].'</b></pre><br><br>';
				$setHtml.='</div>';
			}
				$setHtml.='<br>';
				
				
				$toEmail[$postedByData['user_name']] = $postedByData['email'];
				if(isset($adminpostedByData) && $adminpostedByData!='')
					$toEmail[$adminpostedByData['user_name']] =$adminpostedByData['email'];
				$sub=substr($sub,0,-2);
				if($status > 0)
				{
					if($status==2)
						$subject = 'YOUR REJECTED '.strtoupper($order_type).' OREDR NO: '.$sub.' Submited By '.$datauser['user_name'];    
					elseif($status==1)
						$subject = 'YOUR ACCEPTED '.strtoupper($order_type).' ORDER NO : '.$sub;
					elseif($status==3)
						$subject = 'YOUR DISPATCHED '.strtoupper($order_type).' ORDER NO : '.$sub;
				}
				else
				{
					$subject = 'NEW '.strtoupper($order_type).' ORDER : '.$sub.' Submited By '.$datauser['user_name'];    
				}
			//	$insert_qry=substr($insert_qry,0,-2);
		}
	//	printr($setHtml);die; 
	   
	    return $setHtml;
		
	
	}
	
}

?>

