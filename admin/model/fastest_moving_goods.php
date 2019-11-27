<?php
//[kinjal]
class fastest_moving_goods extends dbclass{

	
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
	public function getUserEmployeeIds($user_type_id,$user_id)
	{
		if($user_id=='6' && $user_type_id=='4')
		    $sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' AND user_type='20'";
		else
		    $sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	public function get_sheet($f_date,$t_date,$by_option)
	{
		
		$from_date = new DateTime($f_date);
		$to_date = new DateTime($t_date);
		$interval = DateInterval::createFromDateString('1 month');
		$period   = new DatePeriod($from_date, $interval, $to_date);
		
		foreach ($period as $dt) {
			$month_year[] = $dt->format("Y-m");
		}
		
		$res = array();
		foreach($month_year as $my) 
		{
			$mmyy = explode('-',$my);
			if($_SESSION['LOGIN_USER_TYPE'] != 1)
		    {
						if($_SESSION['LOGIN_USER_TYPE'] == 2){
							 
							$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
            				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
            				$set_user_id = $parentdata->row['user_id'];
            				$set_user_type_id = $parentdata->row['user_type_id'];
            				 
						}else if($_SESSION['LOGIN_USER_TYPE'] == '4'){
						
							$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
				            $set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
            				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
            			}
						$str = '';
            			if($userEmployee){
            				$str = ' OR ( si.added_user_id IN ('.$userEmployee.') AND si.added_user_type_id IN ("2") ) ';
            			}
						$order_by = $pro='';
						 $group_by = ', sip.product_code_id';
						if($by_option == '1')
						    $order_by = 'tot_qty';
						else if($by_option == '2')
						{
						    $pro = 'AND p.product_code!="sample"';
						    $order_by = 'rate';
						}
						else if($by_option == '3')
						{
						    $pro = 'AND p.product_code!="sample"';
						    $group_by = ', p.product';
						    $order_by = 'tot_qty ';
						}
						else if($by_option == '4')
						{
						    $pro = 'AND p.product_code!="sample"';
						    $group_by = ', CONCAT(p.volume," ",tm.measurement) ,sip.product_code_id';
						    $order_by = 'tot_qty  ';
						}
                        
                        if($set_user_id=='10')
						    $sql1 = "SELECT SUM(sip.quantity) tot_qty,si.date_added,si.pro_in_no,sip.product_code_id,p.product_code,sip.rate,pro.product_name,CONCAT(p.volume,' ',tm.measurement) as size FROM " . DB_PREFIX . "packing_order_product_code_wise as sip, packing_order as si,product_code as p,product as pro, template_measurement as tm  WHERE sip.packing_order_id=si.packing_order_id AND si.is_delete=0 AND (si.added_user_id = '".(int)$set_user_id."' AND si.added_user_type_id = '".(int)$set_user_type_id."'  ".$str." )  AND p.product_code_id = sip.product_code_id AND MONTH(si.date_added) = '".$mmyy[1]."' AND YEAR(si.date_added) = '".$mmyy[0]."' AND pro.product_id=p.product ".$pro." AND tm.product_id = p.measurement GROUP BY  Month(si.date_added) ".$group_by." ORDER BY ".$order_by." DESC";//si.date_added ASC,
                        else if($set_user_id=='6')
                        {
						    $pro = 'AND p.product_code!="sample" AND p.product_code NOT LIKE "CUST%" ';
						    $sql1 = "SELECT GROUP_CONCAT(si.sales_invoice_id),SUM(sip.qty) tot_qty,si.date_added,sip.product_code_id,p.product_code,sip.rate,pro.product_name,CONCAT(p.volume,' ',tm.measurement) as size FROM " . DB_PREFIX . "government_sales_invoice_product as sip, government_sales_invoice as si,product_code as p,product as pro, template_measurement as tm  WHERE sip.sales_invoice_id=si.sales_invoice_id AND si.is_delete=0 AND (si.added_user_id = '".(int)$set_user_id."' AND si.added_user_type_id = '".(int)$set_user_type_id."'  ".$str." ) AND p.product_code_id = sip.product_code_id AND MONTH(si.date_added) = '".$mmyy[1]."' AND YEAR(si.date_added) = '".$mmyy[0]."' AND pro.product_id=p.product ".$pro." AND tm.product_id = p.measurement GROUP BY  Month(si.date_added) ".$group_by." ORDER BY ".$order_by." DESC";//si.date_added ASC,
                        }
                        else
						    $sql1 = "SELECT SUM(sip.qty) tot_qty,si.date_added,si.invoice_no,sip.product_code_id,p.product_code,sip.rate,pro.product_name,CONCAT(p.volume,' ',tm.measurement) as size FROM " . DB_PREFIX . "sales_invoice_product as sip, sales_invoice as si,product_code as p,product as pro, template_measurement as tm  WHERE sip.invoice_id=si.invoice_id AND si.is_delete=0 AND si.gen_status='0' AND AND (si.added_user_id = '".(int)$set_user_id."' AND si.added_user_type_id = '".(int)$set_user_type_id."'  ".$str." ) AND p.product_code_id = sip.product_code_id AND MONTH(si.date_added) = '".$mmyy[1]."' AND YEAR(si.date_added) = '".$mmyy[0]."' AND pro.product_id=p.product ".$pro." AND tm.product_id = p.measurement GROUP BY  Month(si.date_added) ".$group_by." ORDER BY si.date_added ASC,".$order_by." DESC LIMIT 10";
						
						//echo $sql1;
						$res1=$this->query($sql1);
						
						if($res1->num_rows!='0')
							$res[$res1->row['date_added']] = $res1->rows;					
					}
					else
					{
						$res = '';
					}		
			
		}		
		return $res;
	}
	
	public function getUser($user_id,$user_type_id)
	{
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.company_address,ib.company_name,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_name,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.inter
national_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			
			$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		$data = $this->query($sql);
		return $data->row;
	}
	//end 
}
?>