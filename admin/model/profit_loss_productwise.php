<?php
//[kinjal]  http://www.noupe.com/design/webdesign-takeaway-40-css-buttons-tutorials-and-code-snippets-88778.html
class profit_loss extends dbclass{

	public function getProductCd($product_code)
	{
		$result=$this->query("SELECT product_code,product_code_id,description FROM " . DB_PREFIX ."product_code WHERE product_code LIKE '%".$product_code."%' AND is_delete=0");
		return $result->rows;
	}
	
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
	
	public function get_profit_loss($f_date,$t_date)
	{
	
		if($_SESSION['LOGIN_USER_TYPE'] != 1)
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
			$sql1 = "SELECT si.invoice_id,sip.qty,sip.rate,si.invoice_no, sip.product_code_id,si.purchase_invoiceno,p.product_code FROM " . DB_PREFIX . "sales_invoice_product as sip, sales_invoice as si,product_code as p  WHERE sip.invoice_id=si.invoice_id AND si.is_delete=0 AND si.gen_status='0' AND si.user_id IN (".$emp_admin_id.") AND si.user_type_id In (".$emp_type_admin_id.")  AND si.date_added >= '".$f_date."' AND  si.date_added <= '".$t_date."' AND p.product_code_id = sip.product_code_id AND sip.product_code_id != '-1'";
			$res1=$this->query($sql1);
			//printr($res1);
			//$sql2 = "SELECT SUM(pip.qty) as pur_qty,pip.rate as pur_rate FROM " . DB_PREFIX . "purchase_invoice_product as pip, purchase_invoice as pi  WHERE pi.is_delete = 0 AND pip.invoice_id=pi.invoice_id AND pip.product_code_id = '".$product_code_id."' AND pi.user_id IN (".$emp_admin_id.") AND pi.user_type_id In (".$emp_type_admin_id.") AND pi.date_added >= '".$f_date."' AND  pi.date_added <= '".$t_date."' ";
			//$res2=$this->query($sql2);
			$pur_inv_no = '';
			if($res1->num_rows)
			{	
				$product_code=array();
				$tot = 0;
				foreach($res1->rows as $row)
				{
					$cal_qty = $row['qty'] * $row['rate'];
					
					if($row['purchase_invoiceno']!='')
					{
						
						$purchase_no=explode(",", $row['purchase_invoiceno']);
						$result = "'" . implode ( "', '", $purchase_no ) . "'";

						$sql2 = "SELECT pip.qty,pip.rate, pip.product_code_id,pi.invoice_no FROM " . DB_PREFIX . "purchase_invoice_product as pip, purchase_invoice as pi  WHERE pi.is_delete = 0 AND pip.invoice_id=pi.invoice_id AND pip.product_code_id = '".$row['product_code_id']."' AND pi.invoice_no IN  (".$result.") ";
						
						$res2=$this->query($sql2);
						if($res2->num_rows)
						{	
							$k=0;
							foreach($res2->rows as $row2)
							{
								if($res2->num_rows>1)
									$k = $k+$row2['rate'];
								else
									$k = $row2['rate'];
							}
							if($res2->num_rows>1)
							{
								$rate_purchase = ($k/$res2->num_rows);
							}
							else
							{
								$rate_purchase = $k;
							}
							$purchase_qty = $rate_purchase * $row['qty'];
							$product_code[] = array('purchase_qty' =>  $purchase_qty,
													'product_code' => $row['product_code_id'],
													'sales_qty' => $cal_qty,
													'product_code_text' => $row['product_code']);
							
							$pur_inv_no = $row['purchase_invoiceno'];
						}
					}	
							
				}
				//echo $pur_inv_no;
				if($pur_inv_no != '')
				{
					foreach($product_code as $pro_code)
					{
						  $id = $pro_code['product_code_text'];
						  $product_wise[$id][] = $pro_code['purchase_qty'];
						  $product_wise_1[$id][]= $pro_code['sales_qty'];
					}
					$new_1 = array();
					foreach($product_wise_1 as $key_1 => $value_1)
						$new_1[$key_1] = array_sum($value_1);
	
					$new = array();
					foreach($product_wise as $key => $value)
					{
						$new[] = array('product_code' => $key, 
									   'purchase_qty_tot' => array_sum($value),
									   'sales_qty_tot' => $new_1[$key]);
					}
					$return = $new;
				}
				else
				{
					$return = '0';
				}
			}
			return $return;
		}
		else
		{
			return 0;
		}		
		//echo $return;
		
	}
	//end 
}
?>