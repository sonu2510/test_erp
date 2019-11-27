<?php
class transfer_invoice extends dbclass{	
	
	public function addOrder($data)
	{
		$userCountry = $this->getUserCountry($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
		if($userCountry){
			$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
		}else{ 
			$countryCode='IN';
		}
		$LastInvoiceId = $this->getLastIdTrans();
		$id=$LastInvoiceId['transfer_invoice_id']+1;
		$tra_id=str_pad($id,8,'0',STR_PAD_LEFT);
		$transfer_invoice_no=$countryCode.$tra_id;
		
		    
		if($data['dispatch']=='0')
			$data['customer_detail']='';
			
		$sql = "INSERT INTO transfer_invoice SET transfer_invoice_no='".$transfer_invoice_no."',trans_inv_date = '" .$data['invoicedate']. "',proforma_no='".$data['proforma_no']."',sales_no='".$data['sale_no']."',address_book_id='".$data['address_book_id']."',contact_no='".$data['contact_no']."',buyers_order_no='".addslashes($data['buyers_order_no'])."',customer_name='".addslashes($data['customer_name'])."',email = '".$data['email']."',customer_address = '".addslashes($data['customer_address'])."',country_id='".$data['country_final']."',city='".$data['city']."',region = '".$data['region']."',trans_satus  = '".$data['trans_from'][0]."',dis_or_warehouse = '".$data['dispatch']."',customer_detail = '".addslashes($data['customer_detail'])."' , pallet_nm = '".$data['pallet_nm']."', rack_no = '".$data['rack_no']."', user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',status = '".$data['status']."',date_added = NOW(),date_modify = NOW(),is_delete=0 ";
		$data_sql = $this->query($sql);
		$invoice_id = $this->getLastId();
		foreach($data['qty_master'] as $qty)
		{
			 $filling_option=$this->getSpoutDetail($qty['product_keyword']);
			 $filling='';
			 if($filling_option=='16' || $filling_option=='31' || $filling_option=='50' )
			    $filling=$qty['filling'];
			
			$sql1 = "INSERT INTO transfer_invoice_product SET invoice_id='".$invoice_id."',product_code_id='".$qty['product_keyword']."',filling='".$filling."',description  = '".$qty['product_name']."',qty = '".$qty['min_qty']."',rack_remaining_qty = '".$qty['min_qty']."',date_added = NOW(),date_modify = NOW(),is_delete=0 ";
			$data1 = $this->query($sql1);
		
		}
		//die;
	}
	
	public function updateOrder($data,$transfer_invoice_id)
	{
			
		if($data['dispatch']=='0')
			$data['customer_detail']='';
		else
		{
			$data['pallet_nm']=$data['rack_no']='';
		}
				
		$sql = "UPDATE transfer_invoice SET proforma_no='".$data['proforma_no']."',trans_inv_date = '" .$data['invoicedate']. "',sales_no='".$data['sale_no']."',address_book_id='".$data['address_book_id']."',contact_no='".$data['contact_no']."',customer_name='".$data['customer_name']."',buyers_order_no='".$data['buyers_order_no']."',email = '".$data['email']."',customer_address = '".$data['customer_address']."',country_id='".$data['country_final']."',city='".$data['city']."',region = '".$data['region']."',trans_satus  = '".$data['trans_from'][0]."',dis_or_warehouse = '".$data['dispatch']."',customer_detail = '".$data['customer_detail']."' ,  pallet_nm = '".$data['pallet_nm']."', rack_no = '".$data['rack_no']."', user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',status = '".$data['status']."', date_modify = NOW() WHERE 	transfer_invoice_id = '" .$transfer_invoice_id. "' ";
		$this->query($sql);
		
		
		
		foreach($data['qty_master'] as $UpdateQty)
		{
			 $filling_option=$this->getSpoutDetail($UpdateQty['product_keyword']);
			 $filling='';
			 if($filling_option=='16' || $filling_option=='31' || $filling_option=='50' )
			    $filling=$UpdateQty['filling'];
			 
			 if(isset($UpdateQty['min_qty_id']) && $UpdateQty['min_qty_id']!='') 
			 {
				$sql1 = "UPDATE transfer_invoice_product SET product_code_id='".$UpdateQty['product_keyword']."',filling='".$UpdateQty['filling']."',description  = '".$UpdateQty['product_name']."',qty = '".$UpdateQty['min_qty']."',rack_remaining_qty = '".$UpdateQty['min_qty']."', date_modify = NOW() WHERE invoice_product_id = '" .$UpdateQty['min_qty_id']. "' ";
				$this->query($sql1);
			 }
			 else
			 {
			 	$sql1 = "INSERT INTO transfer_invoice_product SET invoice_id='".$transfer_invoice_id."',product_code_id='".$UpdateQty['product_keyword']."',filling='".$UpdateQty['filling']."',description  = '".$UpdateQty['product_name']."',qty = '".$UpdateQty['min_qty']."',rack_remaining_qty = '".$UpdateQty['min_qty']."',date_added = NOW(),date_modify = NOW(),is_delete=0 ";
				$data1 = $this->query($sql1);
			 }
		}
	}
	
	
	public function getTotalInvoice($user_type_id,$user_id,$filter_data=array())
	{
		
		if($user_type_id == 1 && $user_id == 1)
		{
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "transfer_invoice` WHERE is_delete = '0'";
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
			if($set_user_id == '33')
				$region ='Melbourne';
			else
				$region ='Sydney';
				
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "transfer_invoice` WHERE is_delete = '0' AND ( ( ( user_id = '".(int)$set_user_id."' AND  user_type_id = '".(int)$set_user_type_id."' ) $str )  OR (place_status = 1 AND region = '".$region."' ) )";
		}
		
		/*if($set_user_id == '33')
		{
			$userEmployee_other = $this->getUserEmployeeIds('4','24');
			$str_sec = '( ( user_id = 24 AND  user_type_id = 4 ) OR ( user_id IN ('.$userEmployee_other.') AND user_type_id = 2 )) ';
		}
		else
		{	
			$userEmployee_other = $this->getUserEmployeeIds('4','33');
			$str_sec = '( ( user_id = 33 AND  user_type_id = 4 ) OR ( user_id IN ('.$userEmployee_other.') AND user_type_id = 2 )) ';
		}*/
			
		if(!empty($filter_data))
		{
			if(!empty($filter_data['invoice_no'])){
				
			$sql .= " AND transfer_invoice_no LIKE '%".$filter_data['invoice_no']."%' ";		
			}
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND customer_name LIKE '%".$filter_data['customer_name']."%' ";		
			}
			if(!empty($filter_data['email'])){
				$sql .= " AND email LIKE '%".$filter_data['email']."%' ";		
			}
			if(!empty($filter_data['user_name']))
			{
			$spitdata = explode("=",$filter_data['user_name']);
				$sql .=" AND user_type_id = '".$spitdata[0]."' AND user_id = '".$spitdata[1]."'";
			}
		}
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getInvoice($user_type_id,$user_id,$data,$filter_data=array())
	{
		if($user_type_id == 1 && $user_id == 1)
		{
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "transfer_invoice as inv LEFT JOIN country as c ON c.country_id=inv.country_id  WHERE  inv.is_delete = '0' " ;
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
			if($set_user_id == '33')
				$region ='Melbourne';
			else
				$region ='Sydney';
				
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "transfer_invoice as inv LEFT JOIN country as c ON c.country_id=inv.country_id WHERE  inv.is_delete = '0' AND  (((inv.user_id = '".(int)$set_user_id."' AND inv.user_type_id = '".(int)$set_user_type_id."' ) $str )  OR (place_status = 1 AND region = '".$region."' ) )" ;
		}
		
		if(!empty($filter_data))
		{
			if(!empty($filter_data['invoice_no'])){
				
			$sql .= " AND transfer_invoice_no LIKE '%".$filter_data['invoice_no']."%' ";		
			}
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND customer_name LIKE '%".$filter_data['customer_name']."%' ";		
			}
			if(!empty($filter_data['email'])){
				$sql .= " AND email LIKE '%".$filter_data['email']."%' ";		
			}
			if(!empty($filter_data['user_name']))
			{
			$spitdata = explode("=",$filter_data['user_name']);
				$sql .=" AND user_type_id = '".$spitdata[0]."' AND user_id = '".$spitdata[1]."'";
			}
		}
		
		$sql .=' GROUP BY transfer_invoice_id ';
		
		if (isset($data['sort'])) 
		{
			$sql .= " ORDER BY " .$data['sort'];	
		} else {
			$sql .= " ORDER BY transfer_invoice_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC'))
		{
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) 
		{
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
	
	public function getUser($user_id,$user_type_id)
	{	
		if($user_type_id == 1)
		{
			$sql = "SELECT ib.international_branch_id,ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2)
		{
			$sql = "SELECT ib.international_branch_id,e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4)
		{
			$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else
		{
			
			$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getCountry()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE is_delete = '0'  ORDER BY country_id ASC";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}else{
			return false;
		}
	}

	
	public function getCountryName($country_id)
	{
		$sql = "SELECT country_name FROM `" . DB_PREFIX . "country` WHERE country_id='".$country_id."' ";
		$sql .= " ORDER BY country_id";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCountryId($country_name)
	{
		$sql = "SELECT country_id FROM `" . DB_PREFIX . "country` WHERE country_name='".$country_name."' ";
		$sql .= " ORDER BY country_id";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	
	
	
	public function getUserEmployeeIds($user_type_id,$user_id)
	{
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids,GROUP_CONCAT(2) as type_ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row['ids'];
		}else{
			return false;
		}
	} 
	
	public function getUserCountry($user_tyep_id,$user_id)
	{
		$sql = "SELECT co.country_id, co.country_code, co.currency_id,co.currency_code FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) WHERE ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_tyep_id."' AND ad.address_type_id = '0'";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}else{
			return false;
		}
	}
	public function getProductCd($product_code_id)
	{
		$result=$this->query("SELECT description,product_code,product FROM " . DB_PREFIX ."product_code WHERE product_code_id='".$product_code_id."'");
		return $result->row;
	}
	
	
	public function getproductcodes()
	{
		$sql = "SELECT * FROM product_code WHERE  is_delete = '0' AND status=1 ORDER BY product_code_id ASC";
		//$sql = "SELECT * FROM product_code WHERE  is_delete = '0' AND status=1 AND  ( product_code NOT LIKE 'CUST%' AND product_code NOT LIKE 'LBL%' AND product_code NOT LIKE 'CPBB' ) ORDER BY product_code<>( product_code LIKE 'CUST%' OR product_code LIKE 'LBL%' OR product_code LIKE 'CPBB' ) ,( product_code NOT LIKE 'CUST%' AND product_code NOT LIKE 'LBL%' AND product_code NOT LIKE 'CPBB' ),product_code_id ASC";
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
		return $data->rows;
	}
	public function getInvoiceData($invoice_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "transfer_invoice  WHERE transfer_invoice_id = '" .(int)$invoice_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getInvoiceProduct($invoice_id,$n='')
	{
		$str='';
		if($n=='0')
			$str = " AND ip.rack_remaining_qty!='0' ";
			
		$sql = "SELECT ip.* FROM `" . DB_PREFIX . "transfer_invoice_product` as ip WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0".$str;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	
	public function delQty($invoice_product_id)
	{
		$this->query("DELETE FROM " . DB_PREFIX . "transfer_invoice_product WHERE invoice_product_id = '".(int)$invoice_product_id."' ");
	}
	
	public function getLastIdTrans() 
	{
		$sql = "SELECT transfer_invoice_id FROM transfer_invoice ORDER BY transfer_invoice_id DESC LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else {
			return false;
		}
	}
	
	public function updateInvoice($invoice_no,$status_value)
	{
		$sql = "UPDATE " . DB_PREFIX . "transfer_invoice SET status = '".$status_value."', date_modify = NOW()  WHERE transfer_invoice_id = '" .(int)$invoice_no. "'";
		$this->query($sql);
	}
	
	public function updateInvoiceStatus($status,$data)
	{
		if($status == 0 || $status == 1){
			$sql = "UPDATE " . DB_PREFIX . "transfer_invoice SET status = '" .(int)$status. "',  date_modify = NOW() WHERE transfer_invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE " . DB_PREFIX . "transfer_invoice SET is_delete = '1', date_modify = NOW() WHERE transfer_invoice_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getIbUserEmail($user_id,$user_type_id)
	{
		$sql = "SELECT email FROM account_master WHERE user_id ='".$user_id."' AND user_type_id=".$user_type_id;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	
	public function viewTransDetail($transfer_invoice_id)
	{
		$invoice = $this->getInvoiceData($transfer_invoice_id);
		$total_qty_price_data = $this->getInvoiceProduct($transfer_invoice_id);
	    if($invoice['buyers_order_no']!=0)
	          $invoice['buyers_order_no']=$invoice['buyers_order_no'];
    	else 
    	     $invoice['buyers_order_no']='';
	   
    	 if($invoice['contact_no']!=0)
    	    $invoice['contact_no']=$invoice['contact_no'];
    	 else	 
    	    $invoice['contact_no']='';
	    
		$html='';
		$html .='<div class="form-group">
                        	<label class="col-lg-3 control-label">Transfer Invoice No</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">'.
                            		$invoice['transfer_invoice_no'].'
                            	</label>
                        	</div>
                      	</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Invoice Date</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">'.
                                	dateFormat('4',$invoice['trans_inv_date']).'
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Proforma Invoice No</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		'.$invoice['proforma_no'].'
                            	</label>
                        	</div>
                      	</div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Sales Invoice No </label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		'.$invoice['sales_no'].'
                            	</label>
                        	</div>
                      	</div>  
                      	<div class="form-group">
                        	<label class="col-lg-3 control-label">Buyers order No </label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		'.$invoice['buyers_order_no'].'
                            	</label>
                        	</div>
                      	</div>  
                      	<div class="form-group">
                        	<label class="col-lg-3 control-label">Contact No </label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		'.$invoice['contact_no'].'
                            	</label>
                        	</div>
                      	</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Customer Name</label>
                            <div class="col-lg-9">
                                <label class="control-label normal-font">
                                	'.ucwords($invoice['customer_name']).'
                                </label>
                        	</div>
                     	</div>
                        
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Email</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	'.$invoice['email'].'
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Address</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	'.ucwords($invoice['customer_address']).'
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Country of Final Destination</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">';
                                	$country = $this->getCountryName($invoice['country_id']); 
									$html .=$country['country_name'].'
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">City</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	'.$invoice['city'].'
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Region</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	'.$invoice['region'].'
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Want Stock From</label>
                        	<div class="col-lg-9">
                            	<label class="control-label normal-font">';
										if($invoice['trans_satus']=='m_to_s')
											$html .='Melbourne To Sydeney';
										else
											$html .='Sydeney To Melbourne';
                            	$html .='</label>
                        	</div>
                      	</div>
                        
                       
                         <div class="form-group">
                        	<label class="col-lg-3 control-label">Do you want to dispatch order directly to customer?</label>
                        	<div class="col-lg-9">
                            	<label class="control-label normal-font">';
                            		if($invoice['dis_or_warehouse']=='1')
											$html .='Yes';
										else
											$html .='No';
									
                            	$html .='</label>
                        	</div>
                      	</div>
                       
                          <div class="form-group">
                        	<label class="col-lg-3 control-label">';if($invoice['dis_or_warehouse']=='1'){ $html .='Customer Detail';} else { $html .='Message';}$html .='</label>
                        	<div class="col-lg-9">
                            	<label class="control-label normal-font">';
                            				if($invoice['dis_or_warehouse']=='1')
												$html .=ucwords($invoice['customer_detail']);
										   else
										   		$html .='Please Transfer This Stock To Our Warehouse !!';
                            	$html .='</label>
                        	</div>
                      	</div>';
						
						if($invoice['dis_or_warehouse']=='0')
						{	$pallet_nm =$this->getGoodsData($invoice['pallet_nm']);
							$row = $pallet_nm['row'];
							$col = $pallet_nm['column_name'];
							$html .='<div class="form-group">
									<label class="col-lg-3 control-label">Pallet</label>
									  <div class="col-lg-4">
										<label class="control-label normal-font">
											'.$pallet_nm['name'].'
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label">Rack Number</label>
										  <div class="col-lg-4">
											<label class="control-label normal-font">';
											$d = 1;
											$sel='';
											for($i=1;$i<=$row;$i++)
											{
												for($r=1;$r<=$col;$r++) 
												{
													$num = $i.'='.$r;
													if($invoice['rack_no']==$num)
														$k=$d;
													
													$d++;
												}
											}
								   $html .=$k.'</label>
										</div>
								</div>';
						}
						
                 $html .='<div class="form-group">
                        	<label class="col-lg-3 control-label">Product Detail</label>
                        	<div class="col-lg-9">
                            	<label class="control-label normal-font">
                            		 <div class="table-responsive">
										<table class="tool-row table  b-t text-small" id="myTable">
                                        	<tr>
                                            	<th>Product Code</th>
                                                <th>Product Description</th>
                                                <th>Qty</th>
                                            </tr>';
                                            	  if(isset($total_qty_price_data) && !empty($total_qty_price_data))
												  { 
													foreach ($total_qty_price_data as $qtyRate)
							   						{
                                                        $html .='<tr>
																	<td>';$product_code = $this->getProductCd($qtyRate['product_code_id']);  $html .=$product_code['product_code'].'</td>
																	<td>'.ucwords($qtyRate['description']).' <b>'.$qtyRate['filling'].'</b></td>
																	<td>'.$qtyRate['qty'].'</td>
                                                        		 </tr>';
												    } 
												  } 
                                            
                                       $html .=' </table>
                                     </div>
                            	</label>
                        	</div>
                      	</div>';
		return $html;
	}
	
	//user_id : 33 (Sydney), 24 (melbourne)
	//this email fun. for approve or disapprove order, palce order email & Dispatch order directly to the customer
	public function send_mail($transfer_invoice_id,$admin_email,$statement='',$transfer_invoice_no='')
	{
		$ibInfo = $this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		
		if($_SESSION['LOGIN_USER_TYPE']==2)
			$loginIbEmail=$this->getIbUserEmail($ibInfo['international_branch_id'],'4');

		if($ibInfo['international_branch_id']=='33')
		{
			$otherIbEmail=$this->getIbUserEmail('24','4');//nandanau
			$otherIbEmp=$this->getIbUserEmail('60','2');//claudia
		}
		else
		{
			$otherIbEmail=$this->getIbUserEmail('33','4');//nandansy
			$otherIbEmp=$this->getIbUserEmail('70','2');//riddhi //offline : 69, online : 70
		}
				
		$html='';
		if($statement=='app')
		{
			$html.='We have all requested items in stock and will dispatch the items within few days.';
			$subject = $transfer_invoice_no.' Approve Order'; 
			$table_index = "approve_disapprove = 'app'";
		}
		elseif($statement == 'disapp')
		{
			$html.="We haven't all requested items in stock.";
			$subject = $transfer_invoice_no.' Disapprove Order'; 
			$table_index = "approve_disapprove = 'disapp'";
		}
		elseif($statement == 'place')
		{
			$html.=$this->viewTransDetail($transfer_invoice_id);
			$subject = $transfer_invoice_no.' Place Order';
			$table_index = "place_status='1'";
		}
		elseif($statement == 'dispatch_direct')
		{
			$html.="We Dispatch Your Order Directly To the customer as per your specification and requirements.";
			$subject = $transfer_invoice_no.' Dispatch Order';
			//$table_index = "dis_status = ''";
		}
		if($statement != 'dispatch_direct')
		{
			$sql="UPDATE transfer_invoice SET ".$table_index." WHERE transfer_invoice_id='".$transfer_invoice_id."'";
			$this->query($sql);
		}
		
		$html.='<link rel="stylesheet" href="'.HTTP_SERVER.'css/invoice.css">';	

		$email_temp[]=array('html'=>$html,'email'=>$admin_email);
		$email_temp[]=array('html'=>$html,'email'=>$otherIbEmail);
		$email_temp[]=array('html'=>$html,'email'=>$otherIbEmp);
		$email_temp[]=array('html'=>$html,'email'=>$ibInfo['email']);
		if($_SESSION['LOGIN_USER_TYPE']==2)
			$email_temp[]=array('html'=>$html,'email'=>$loginIbEmail['email']);
			
		$form_email=$ibInfo['email'];
		
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(1); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
		$path = HTTP_SERVER."template/proforma_invoice.html";
		$output = file_get_contents($path);  
		$search  = array('{tag:header}','{tag:details}');
		$signature = 'Thanks.';
		//printr($email_temp);die;
		foreach($email_temp as $val)
		{
			
			$toEmail =$form_email;
			$firstTimeemial = 1;								
			$message = '';
			if($val['html'])
			{
				$tag_val = array(
				"{{header}}"=>$subject,
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
			send_email($val['email'],$form_email,$subject,$message,'');
		}
	}
	
	public function savedispatch_racknotify($data,$admin_email)
	{
		$row_col=explode('=',$data['alldata']);
		$partial = explode(',', $data['stock_id']);
		$final = array();
		$dispatch_qty=0;
		array_walk($partial, function($val,$key) use(&$final){
			list($key, $value) = explode(':', $val);
			$sql3 = "SELECT SUM(dispatch_qty) dispatch_qty FROM " . DB_PREFIX . "stock_management WHERE parent_id=".$value."";
			$data3 = $this->query($sql3);
			if($data3->num_rows){
			$dispatch_qty=$data3->row['dispatch_qty'];
			}
			$qty=$key-$dispatch_qty;
			if($qty>0)
				$final[] = array('id'=>$value,'qty'=>$key);	
		});
		$dis_qty=$data['dispatch_qty'];
		foreach($final as $record)
		{	 
			if($dis_qty>$record['qty'])
				$final_dis_qty=$record['qty'];
			else
				$final_dis_qty=$dis_qty;
				
		$sql="INSERT INTO stock_management SET proforma_no='".$data['proforma_no']."',invoice_no='".$data['invoice_no']."',order_no='na',my_order_no='ns',box_no='0',container_no='0',dispatch_qty='".$final_dis_qty."',parent_id='".$record['id']."',product='".$data['product_id']."',goods_id='".$row_col[2]."' , row='".$row_col[0]."' ,column_name='".$row_col[1]."',company_name='".addslashes($data['company_name'])."',description=2,status=1,date_added=NOW(),date_modify=NOW(), user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',product_code_id='".$data['product_code_id']."',track_id = '0',courier = '".$data['courier_id']."', courier_amount = '".$data['courier_amount']."'";
		$data1=$this->query($sql);	
		if($dis_qty > $final_dis_qty) 
			$dis_qty=$dis_qty-$final_dis_qty;	
		else
			break;		
				
		}
		$remaining_qty=$data['sales_qty']-$data['dispatch_qty'];
		$sql="UPDATE transfer_invoice_product SET rack_remaining_qty='".$remaining_qty."' WHERE invoice_product_id='".$data['invoice_product_id']."'";
		$result=$this->query($sql);	
		
		
		//transfer stock to other admin's rack
		if($data['dis_or_warehouse']=='0')
		{
			$ibInfo = $this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
			if($ibInfo['international_branch_id']=='33')
				$other_admin = '24';
			else
				$other_admin = '33';
			
			$other_row_col=explode('=',$data['rack_no']);
			
			$sql_insert = "INSERT INTO stock_management SET order_no = 'na', my_order_no = 'na',proforma_no='".$data['proforma_no']."',invoice_no='".$data['invoice_no']."',description = '1',product = '".$data['product_id']."',qty = '".$final_dis_qty."',row='".$other_row_col[0]."',column_name='".$other_row_col[1]."',goods_id='".$data['pallet_nm']."',company_name='na',product_code_id='".$data['product_code_id']."', status='0',is_delete='0',date_added=NOW(),date_modify=NOW(),user_id='".$other_admin."',user_type_id='4'";
			$data_insert = $this->query($sql_insert);
		}
		else
		{
			$this->send_mail($data['invoice_id'],$admin_email,'dispatch_direct',$data['trans_no_dis']);
		}
		
		
	}
	
	public function getpallet()
	{
		if($_SESSION['LOGIN_USER_TYPE'] == 1 && $_SESSION['ADMIN_LOGIN_SWISS'] == 1)
		{
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' ";
		}
		else
		{
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
				$str = 'OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 ) ';
			}
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND ( user_id='".(int)$set_user_id."' AND user_type_id='".(int)$set_user_type_id."' $str )";
		}
		$sql .= " ORDER BY goods_master_id ASC";	
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
	}
	
	public function getGoodsData($goods_master_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE goods_master_id = '" .(int)$goods_master_id. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function changeRackStatusTrans($invoice_id)
	{
		$sql = "SELECT ip.rack_remaining_qty FROM `" . DB_PREFIX . "transfer_invoice_product` as ip WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete='0' AND ip.rack_remaining_qty='0'";
		$data = $this->query($sql);
		$count = $data->num_rows;
		
		$inv_product=$this->getInvoiceProduct($invoice_id);
        $count_pro = count($inv_product);
		if($count == $count_pro)
		{
			$sql1 ="UPDATE transfer_invoice SET dis_status='1' WHERE transfer_invoice_id='".$invoice_id."' ";
			$this->query($sql1);
		}
	}
	public function check_proforma($proforma_no)
	{
		$sql = "SELECT pro_in_no FROM `" . DB_PREFIX . "proforma_product_code_wise` WHERE pro_in_no='".$proforma_no."'  AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['pro_in_no'];
		}else{
			return false;
		}
	}
	public function check_sales($sale_no)
	{
		$sql = "SELECT invoice_no FROM `" . DB_PREFIX . "sales_invoice` WHERE invoice_no='".$sale_no."'  AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['invoice_no'];
		}else{
			return false;
		}
	}
	public function get_disc($product_code_text)
	{
		$result=$this->query("SELECT description FROM " . DB_PREFIX ."product_code WHERE product_code='".addslashes($product_code_text)."'");
		return $result->row['description'];
	}
	public function getSpoutDetail($product_code_id)
	{
		$result=$this->query("SELECT product FROM " . DB_PREFIX ."product_code WHERE product_code_id='".$product_code_id."'");
		return $result->row['product'];
	}
	
		public function getCustomerDetail($customer_name)

	{
		
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
        $str='';
		if($user_type_id == 2){

			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");

			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

			$set_user_id = $parentdata->row['user_id'];
            
			$set_user_type_id = $parentdata->row['user_type_id'];
			$ib = $this->getUser($set_user_id,$set_user_type_id);
			//$e =array();
			if($ib['country_id'] == '111')
			{
			 
			  $ib_id = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "address WHERE user_type_id = '4' AND country_id = '".$ib['country_id']."'");
			  foreach($ib_id->rows as $key=>$ids)
			  {
			      
			      $emp = $this->getUserEmployeeIds($ids['user_type_id'],$ids['user_id']);
			      //printr($ids['user_id']);
			      $e[] = $emp;
			  }
			  //foreach($e as $employee)
			  //{
			      $e_id = implode(",",$e);
			      //printr($e_id);
			  //}
			  if($e_id)
			    $str = ' OR ( aa.user_id IN ('.$e_id.') AND aa.user_type_id = 2 )';
			}
			else
			{
			//printr($e_id);
    			if($userEmployee)
    			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			}
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id='".$set_user_id ."' AND aa.user_type_id='".$set_user_type_id ."') $str )  GROUP BY aa.address_book_id LIMIT 15";
            //echo $sql;
		}
		else if($user_type_id == 4)
		{

			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);

			$set_user_id = $user_id;

			$set_user_type_id = $user_type_id;
			$ib = $this->getUser($set_user_id,$set_user_type_id);
			
			if($ib['country_id'] == '111')
			{
			 
			  $ib_id = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "address WHERE user_type_id = '4' AND country_id = '".$ib['country_id']."'");
			  foreach($ib_id->rows as $key=>$ids)
			  {
			      
			      $emp = $this->getUserEmployeeIds($ids['user_type_id'],$ids['user_id']);
			      //printr($ids['user_id']);
			      $e[] = $emp;
			  }
			  //foreach($e as $employee)
			  //{
			      $e_id = implode(",",$e);
			      //printr($e_id);
			  //}
			  if($e_id)
			    $str = ' OR ( aa.user_id IN ('.$e_id.') AND aa.user_type_id = 2 )';
			}
			else
			{
			//printr($e_id);
    			if($userEmployee)
    			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			}
				
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id='".$set_user_id ."' AND aa.user_type_id='".$set_user_type_id ."') $str  )  GROUP BY aa.address_book_id LIMIT 15";

		}

		else

		{

			$set_user_id = $user_id;

			$set_user_type_id  = $user_type_id;
			
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.company_name LIKE '%".$customer_name."%' GROUP BY aa.address_book_id LIMIT 15";
		}
       // if($user_type_id=='1' && $user_id=='1')
            //echo $sql;
		$data = $this->query($sql);
		//printr($data);

		if($data->num_rows){

			return $data->rows;

		}else{

			return false;

		}		
	}
}
?>