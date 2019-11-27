<?php  
class label_quotation extends dbclass{
    public function getDefaultcountry($user_id,$user_type_id)
	{
		$sql = "SELECT country_id FROM address WHERE user_id = '".$user_id."' AND user_type_id = '".$user_type_id."'";	
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

	public function getemployeeId($user_id,$user_type_id)
	{
		if($user_type_id==2)
		{
			$sql = "SELECT user_type_id,user_id  from employee WHERE employee_id='".$user_id."'";
			$data=$this->query($sql);
			if($data->num_rows)
			{
				$admin_user_id=$data->row['user_id'];
				$admin_user_type_id=$data->row['user_type_id'];
			}
			$sql1 = "SELECT employee_id FROM employee WHERE user_id='".$admin_user_id."' and user_type_id='".$admin_user_type_id."'";
			$data_val=$this->query($sql1);
			return $data_val->rows;
		}
		elseif($user_type_id==4)
		{
		    $sql1 = "SELECT employee_id FROM employee WHERE user_id='".$user_id."' AND user_type_id='".$user_type_id."'";
			$data_val=$this->query($sql1);
			return $data_val->rows;
		}
	}
    public function getActiveProduct(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' AND product_id IN (3,7,1) ORDER BY product_name ASC";//,20,19,10,4,12,13,16,42,30,31,53,54,50
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
	public function getUserEmployeeIds($user_type_id,$user_id){
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	public function getProductSize($product_id,$make_id)
	{   
	    $sql  = "SELECT s.*,pz.zipper_name FROM size_master as s,product_zipper as pz WHERE s.product_id = '".$product_id."' AND pz.product_zipper_id IN (1,2,3) AND s.product_zipper_id=pz.product_zipper_id AND s.weight!='0' ORDER BY width ASC"; // 
	    $data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function getCountryCombo($selected=""){
		$sql = "SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' AND is_delete = '0' AND default_courier_id > 0 ORDER BY country_name ASC";
		$data = $this->query($sql);
		$html = '';
		if($data->num_rows){
			$html = '';
			$html .= '<select name="country_id" id="country_id" class="form-control validate[required]" style="width:70%" >';
					$html .= '<option value="">Select Country</option>';
			foreach($data->rows as $country){
				if($country['country_id'] == $selected ){
					$html .= '<option value="'.$country['country_id'].'" selected="selected">'.$country['country_name'].'</option>';
				}else{
					$html .= '<option value="'.$country['country_id'].'" >'.$country['country_name'].'</option>';
				}
			}
			$html .= '</select>';
		}
		return $html;
	}
	public function getUserCurrencyInfo($user_type_id,$user_id){
            if( $_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
            {
                $data = $this->query("SELECT *  FROM " . DB_PREFIX . "currency  WHERE is_delete = '0' ");
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
    			$data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");
    		}
            if($data->num_rows){
			   return $data->rows;
            }else{
	    	    return false;
	    	}	
		  
		 return $currency;
    }
	public function getParentInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea,gres_cyli, valve_price FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea,gres_cyli, valve_price FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
		}
		if($sql){
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

    public function getUserInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name, gres,gres_air,gres_sea,gres_cyli, valve_price,product_rate  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
		}elseif($user_type_id == 2){
			$data = $this->query("SELECT first_name, last_name, user_type_id, user_id FROM `" . DB_PREFIX . "employee` WHERE employee_id = '" .(int)$user_id. "'");
            $parentInfo = array();
			$return = array();
			if($data->num_rows){
				$parentInfo = $this->getParentInfo($data->row['user_type_id'],$data->row['user_id']);
				if($parentInfo){
					$return['company_name'] = $parentInfo['company_name'];
					$return['gres'] = $parentInfo['gres'];
					$return['gres_air'] = $parentInfo['gres_air'];
					$return['gres_sea'] = $parentInfo['gres_sea'];
					$return['gres_cyli'] = $parentInfo['gres_cyli'];
					$return['valve_price'] = $parentInfo['valve_price'];
					$return['product_rate'] = $parentInfo['product_rate'];
				}else{
					$return['company_name'] = '';
					$return['gres'] = '';
					$return['gres_air'] = '';
					$return['gres_sea'] = '';
					$return['valve_price'] = '';
					$return['product_rate'] = '';
					$return['gres_cyli'] ='';
				}
			}else{
				$return['company_name'] = '';
				$return['gres'] = '';
				$return['gres_air'] = '';
				$return['gres_sea'] = '';
				$return['valve_price'] = '';
				$return['product_rate'] = '';
				$return['gres_cyli'] ='';
			}
			$return['first_name'] = $data->row['first_name'];
			$return['last_name']  = $data->row['last_name'];
			
			return $return;
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name,gres,gres_air,gres_sea,	gres_cyli, valve_price,product_rate FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea,	gres_cyli,valve_price,product_rate FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
		}
	    if($sql){
			$data = $this->query($sql);
			if($data && $data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	   
	}
	public function getUserWiseCurrency($user_type_id,$user_id)
	{
	    
		if($user_type_id==2){
			$parent_data = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX ."employee WHERE employee_id='".$user_id."'");
			if($parent_data->num_rows){
				
				if($parent_data->row['user_type_id']==4){
					$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$parent_data->row['user_id']."' ";				
				}else if($parent_data->row['user_type_id']==5){
					$sql = "SELECT as.product_rate,as.cylinder_rate,as.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."associate as INNER JOIN " . DB_PREFIX ."country cn ON (as.default_curr=cn.country_id) WHERE as.associate_id = '".$parent_data->row['user_id']."' ";	
				}	
			}
		}
		else if($user_type_id==4){
			$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$user_id."' ";		
		}
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	public function getCurrencyInfo($user_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."user WHERE user_id = '".$user_id."' LIMIT 1");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getLastIdAddress() {
        $sql = "SELECT address_book_id FROM  address_book_master ORDER BY address_book_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row;
        } else {
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

	public function getQuotationDetails($label_quotation_id) 
	{
		$sql ="SELECT * FROM label_quotation  WHERE is_delete=0 AND label_quotation_id ='".$label_quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
   
   	public function getClientName($customer_name)
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
			if($ib['country_id'] == '111')
			{
			  $ib_id = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "address WHERE user_type_id = '4' AND country_id = '".$ib['country_id']."'");
			  foreach($ib_id->rows as $key=>$ids)
			  {
			      $emp = $this->getUserEmployeeIds($ids['user_type_id'],$ids['user_id']);
			      $e[] = $emp;
			      $i[]=$ids['user_id'];
			  }
			  $e_id = implode(",",$e);
			  $set_user_id = implode(",",$i);
			  if($e_id)
			    $str = ' OR ( aa.user_id IN ('.$e_id.') AND aa.user_type_id = 2 )';
			}
			else
			{
    			if($userEmployee)
    			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			}
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.status=1 AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id IN (".$set_user_id .") AND aa.user_type_id='".$set_user_type_id ."') $str )  GROUP BY aa.address_book_id LIMIT 15";
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
			      $e[] = $emp;
			      $i[]=$ids['user_id'];
			  }
			  $e_id = implode(",",$e);
			  $set_user_id = implode(",",$i);
			  if($e_id)
			    $str = ' OR ( aa.user_id IN ('.$e_id.') AND aa.user_type_id = 2 )';
			}
			else
			{
    			if($userEmployee)
    			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			}
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.status=1 AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id IN (".$set_user_id .") AND aa.user_type_id='".$set_user_type_id ."') $str  )  GROUP BY aa.address_book_id LIMIT 15";
		}
		else
		{
            $set_user_id = $user_id;
            $set_user_type_id  = $user_type_id;
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.status=1 AND aa.company_name LIKE '%".$customer_name."%' GROUP BY aa.address_book_id LIMIT 15";
		}
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	
	}
   	
   	public function getQtyplusminus($qty)
	{
        $sql="SELECT * FROM  label_quantity WHERE quantity='".$qty."'";		     
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
   
    public function getUser($user_id,$user_type_id)
	{
		$cond = '';
		if($user_type_id==2)
		{
			$sql = "SELECT ib.color_plate_price,ib.company_name,ib.profit_type,ib.foil_plate_price,e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address, acc.commission FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}
		elseif($user_type_id == 4)
		{
			$sql = "SELECT ib.profit_type,ib.user_name,ib.company_name,ib.color_plate_price,ib.foil_plate_price,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name,ib.vat_no, ib.international_branch_id as user_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email, acc.email1, acc.commission FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}
		elseif($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id,co.currency_code, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email,ad.user_id FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}
		else{
			$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
	
		$data = $this->query($sql);
		return $data->row;
	}
    
     public function gettermsandconditions($user_id,$user_type_id){
		if($user_type_id == '4')
		{
		    $sql = "SELECT ts.termsandconditions, label_quo_termsandconditions,ts.user_id,ts.user_type_id FROM termsandconditions ts WHERE ts.user_id = '".$user_id."' AND ts.user_type_id = '4'  AND ts.is_delete = '0' LIMIT 1";
		}
		else
		{
		    $sql = "SELECT ts.termsandconditions, label_quo_termsandconditions,ts.user_id,ts.user_type_id,e.user_id FROM termsandconditions ts,employee e WHERE e.employee_id ='".$user_id."' AND ts.user_id = e.user_id AND ts.user_type_id = '4' AND ts.is_delete = '0' LIMIT 1";
		}
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
   public function label_quotation_mail($label_quot_id,$to_email='',$setQuotationCurrencyId='',$secondary_curr='',$sec_currency_rate='')
	{
        $html = $html_gress= $common_html=$sub2=$s="";  
        $data = $this->getQuotation($label_quot_id,'*',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
        $data_details = $this->getQuotationDetails($label_quot_id);
        $addedByinfo  = $this->getUser($data_details['user_id'],$data_details['user_type_id']);
        $user_details = $this->gettermsandconditions($data_details['user_id'],$data_details['user_type_id']);       
        $i=1;
        $new_data=array();
       
            foreach($data as $dat)
		    {
		      
		   		 $result = $this->getQuotationQuantity($dat['label_quotation_product_id']);
		  
				 if($result!='')
				    $quantityData[] =$result;
		    }
	
		    $sub =$data[0]['quotation_no'].' '.$data[0]['client_name'];
	
	
		   foreach($quantityData as $k=>$qty_data)
	        {
		        foreach($qty_data as $tag=>$qty)
    			{  
    				foreach($qty as $q=>$arr)
				    {   
					    if($arr[0]['volume']!='')
						{ 
							if($s!=$qty)
							{
								$sub2.=$arr[0]['volume'].' , ';
								$s=$qty;
							}
						}
					    $new_data[$arr[0]['text']][$arr[0]['dimension_with_unit']][$q][$tag][]=$arr[0];
    				
    				}
        		}	
    		}
    		
    		$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
    	   $html .='<table border="0px">';
			if($secondary_curr!='')
			{
				if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['user_type_id'] =='2' ) && $data[0]['admin_user_id']=='10' )
				    $html.='';
				else
				    $html .='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp;'.$secondary_curr.'</b></div><br><br>';
			}
			$cust_shape_die_cost='';
        		$sub1= substr($sub1,0,-3);
		        $sub=$sub.$sub1;
		        $i=1;
		        foreach($new_data as $key=>$value)
				{
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
						$price=$m ='';
    					foreach($qty_data as $qty=>$transport)
    					{
					        (int)$qty= preg_replace("/\([^)]+\)/","",$qty);
    						foreach($transport as $k=>$records1)
    						{
    						    
    						   foreach($records1 as $records)
    						   {    
        						    if($secondary_curr!='')
        							{
        								$selCurrency_currency_code=$secondary_curr;
        								$extra_profit=$this->numberFormate(($records['price_per_label']/$records['product_rate']),3)*$sec_currency_rate;
        								$tool_price = $records['tool_price'] *$sec_currency_rate;
        								$newPircegress = (($records['total_amount'] - $records['gress_total_amount']) / $records['product_rate'])*$sec_currency_rate;
								        $newPircegress = $this->numberFormate(($newPircegress/$records['quantity']),3);
        							}
        							else
        							{
        								$selCurrency_currency_code=$selCurrency['currency_code'];
        								$extra_profit=$this->numberFormate(($records['price_per_label']/$records['product_rate']),3);
        								$tool_price = $records['tool_price'];
        								$newPircegress = (($records['total_amount'] - $records['gress_total_amount']) / $records['product_rate']);
								        $newPircegress = $this->numberFormate(($newPircegress/$records['quantity']),3);
        							}
        						   
        						    if($dat['country_id']=='42')
                            		{
                            			if($k=='By Air')
                            				$k='Rush Order';
                            			if($k=='By Sea')
                            				$k='Normal Order';	
                            		} 
                            		$plus_minus_qty=$this->getQtyplusminus($records['quantity']);
        					        
        					        if($records['sticker_size']==0){ 
        					            $cust_shape_die_cost='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;Custom Shape Die Cost: </b>'.$selCurrency_currency_code.' '.$tool_price.'</td></tr>';
        					        }
        						    $price .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Price: </b>'.$selCurrency_currency_code.' '.$extra_profit.' Per 1 Label {For '.$records['quantity'].' labels in each design, plus or minus '.$plus_minus_qty['plus_minus_quantity'].' Quantity} - Price For '.$k;   
                                 	
                                 	$gress_tr = '<tr><td colspan="2"><b>Gress Percentage Prices: </b></td></tr>';
                                 	$gress_price .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Gress Price: </b>'.$selCurrency_currency_code.' '.$newPircegress.' Per 1 Label {For '.$records['quantity'].' labels in each design, plus or minus '.$plus_minus_qty['plus_minus_quantity'].' Quantity} - Price For '.$k.'  at GP '.$records['gress_per'].' %';   
                                 	$size_tr ='<tr><td colspan="2"><b>'.($i).'&nbsp;&nbsp;&nbsp;Size of pouch: </b>'.$size.'</td></tr> ';
                					$shape_tr='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp; Label Shape: </b>'.$records['shape_name'].'</td></tr> ';
                					$label_size_tr='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp; Label Size: </b>'.$records['sticker_dimension_with_unit'].'</td></tr> ';
                					$make_up_pouch_tr='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp; Make up of pouch: </b>'.$records['product_name'].'</td></tr> ';
                					$sheet_tr='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp; Label Material: </b>'.$records['make_name'].' Label</td></tr> ';
                					$printing_effect_detail_tr='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp; Printing Effect: </b>'.$records['printing_effect_detail'].'</td></tr> ';
    						   }
    						}
    					}

    					$html .=$size_tr;
    					$html .=$shape_tr;
    					$html .=$label_size_tr;
    					$html .=$make_up_pouch_tr;
    					$html .=$sheet_tr;
    					$html .=$printing_effect_detail_tr;
    				    $html .='<tr><td colspan="2"><br></td></tr>';
    					$html .=$price;
    					//for gress format
    					$html_gress .= $html;
    					$html_gress .=$gress_tr;
    					$html_gress .=$gress_price;
    					$html_gress .='<tr><td colspan="2"><br></td></tr>';
    					
    	    	        $html .='<tr><td colspan="2"><br></td></tr>';
        	   	        $i++;
					}
    	    	}
    	     
    	    $common_html .='<tr><td colspan="2"><br></td></tr>';
			$common_html .=$cust_shape_die_cost;
        	$common_html .='<tr><td colspan="2">'.html_entity_decode($user_details['label_quo_termsandconditions']).'</td></tr>';
        	$common_html .= '<tr><td colspan="2">---</td></tr>';
        	$common_html .='<tr><td colspan="2">'.html_entity_decode($addedByinfo['email_signature']).'</td></tr>';
        	$common_html.='</table>';
		    $html_gress .= $common_html;
		    $html .= $common_html;

		$subject = $data_details['quotation_no'].' '.$data_details['client_name'];
		$email_temp[]=array('html'=>$html,'g_html'=>$html_gress,'email'=>$addedByinfo['email'],'user_id'=>$data_details['user_id'],'user_type_id'=>$data_details['user_type_id']);
        if($to_email!='')
            $email_temp[]=array('html'=>$html,'g_html'=>$html_gress,'email'=>$to_email,'user_id'=>0,'user_type_id'=>0);
        if($data_details['user_type_id']=='2')
        {
            $admin_email=$this->getUser($addedByinfo['user_id'],'4');
            $email_temp[]=array('html'=>$html,'g_html'=>$html_gress,'email'=>$admin_email['email'],'user_id'=>$addedByinfo['user_id'],'user_type_id'=>'4');
            if($admin_email['email1']!='' && $admin_email['email1']!=$addedByinfo['email'])
            	$email_temp[]=array('html'=>$html,'g_html'=>$html_gress,'email'=>$admin_email['email1'],'user_id'=>$addedByinfo['user_id'],'user_type_id'=>'4');
        }
        $email_temp[]=array('html'=>$html,'g_html'=>$html_gress,'email'=>ADMIN_EMAIL,'user_id'=>'1','user_type_id'=>'1');
		$form_email=$addedByinfo['email'];
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(1); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
		$path = HTTP_SERVER."template/product_quotation.html";
		$output = file_get_contents($path);  
		$search  = array('{tag:header}','{tag:details}');
		$signature = 'Thanks.';
		$html_msg='';
		$i=0;
        foreach($email_temp as $val)
		{   $menu_id = $this->getMenuPermission('151',$val['user_id'],$val['user_type_id']);
			
			if(!empty($menu_id))
			{   $i=1;
			    $html_msg = $val['g_html'];
			}
			else
			    $html_msg = $val['html'];
			
			if($val['user_type_id']=='1') 
			{
			    if($i=='1')
			        $html_msg = $val['g_html'];
		    	else
			        $html_msg = $val['html'];
			}    
			$toEmail =$form_email;
			$firstTimeemial = 1;
			$subject = $data_details['quotation_no'].' '.$data_details['client_name']; 
			$message = '';
			
			if($val['html'])
			{
			    $tag_val = array(
					"{{productDetail}}" =>$html_msg,
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
			send_email($val['email'],$form_email,$subject,$message,'','');
			   
			  // send_email('erp@swisspac.net',$form_email,$subject,$message,'','');
		}//die;
	    if($to_email!='')
	        $this->query("INSERT INTO `" . DB_PREFIX . "label_quotation_email_history` SET label_quotation_id = '".$label_quot_id."', customer_name = '".addslashes($data_details['client_name'])."',customer_email = '".$data_details['email']."', user_type_id = '" .$_SESSION['LOGIN_USER_TYPE']. "', user_id = '" .$_SESSION['ADMIN_LOGIN_SWISS']. "', to_email = '".$to_email."', from_email = '" .$form_email. "', admin_default_email='".ADMIN_EMAIL."',currency_code='".$quantityData[0]['currency_code']."', sent_date = NOW()");
	}
    public function getMenuPermission($menu_id,$user_id,$user_type_id)
	{
		$cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
	    $sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$cond." AND user_type_id = '".$user_type_id."' AND user_id ='".$user_id."'";
		$data = $this->query($sql);
		return $data->rows;
	}
  
  
    public function getQuotationData($user_id,$user_type_id,$cond='',$filter_array=array(),$option) 
	{
		if($user_id == 1 && $user_type_id == 1){
		    $sql = "SELECT GROUP_CONCAT(DISTINCT dq.product_name SEPARATOR ',') as name,dqi.*,c.country_name  FROM " . DB_PREFIX ."  label_quotation_product dq, label_quotation as dqi,country as c   WHERE  dqi.is_delete=0   AND  dqi.country_id = c.country_id AND  dq.label_quotation_id= dqi.label_quotation_id";
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
				$str = ' OR ( dqi.user_id IN ('.$userEmployee.') AND dqi.user_type_id = 2 ) ';
			}
			$sql = "SELECT  GROUP_CONCAT(DISTINCT dq.product_name SEPARATOR ',') as name,dqi.* ,c.country_name  FROM " . DB_PREFIX ." label_quotation_product dq, label_quotation as dqi,country as c   WHERE  dqi.is_delete=0  AND dqi.country_id = c.country_id AND dq.label_quotation_id= dqi.label_quotation_id  AND (( dqi.user_id = '".(int)$set_user_id."' AND dqi.user_type_id = '".(int)$set_user_type_id."') $str )   ";
		}
        if(!empty($filter_array)) { 
			if(!empty($filter_array['quotation_no'])){
				$sql .= " AND dqi.quotation_no LIKE '%".$filter_array['quotation_no']."%'";
			}
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND dqi.client_name LIKE '%".addslashes($filter_array['customer_name'])."%'";
			}
			if(!empty($filter_array['customer_email'])){
				$sql .= " AND dqi.email LIKE '%".addslashes($filter_array['customer_email'])."%'";
			}
			if(!empty($filter_array['date'])){
				$sql .= " AND date(dqi.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}	
			if(!empty($filter_array['product_name'])){
				$sql .= " AND dq.product_name = '".$filter_array['product_name']."' ";
			}
			if(!empty($filter_array['country'])){
				$sql .= " AND dqi.country_id = '".$filter_array['country']."'";
			}
			if(!empty($filter_array['postedby']))
			{
			    $spitdata = explode("=",$filter_array['postedby']);
				$sql .="AND dqi.user_type_id = '".$spitdata[0]."' AND dqi.user_id = '".$spitdata[1]."'";
			}
        }
            if(isset($cond)){
    				$sql .= $cond;
    		}
            $sql .= " GROUP BY dqi.label_quotation_id";
    		if (isset($option['sort'])) {		
    				
    			$sql .= " ORDER BY " . $option['sort'];	
    		} 
            if (isset($option['order']) && ($option['order'] == 'DESC')) {
    			$sql .= " DESC";
    		} else {
    			$sql .= " ASC";
    		}
    		if (isset($option['start']) || isset($option['limit'])) {
    			if ($option['start'] < 0) {
    				$option['start'] = 0;
    			}			
    			if ($option['limit'] < 1) {
    				$option['limit'] = 20;
    			}	
    			$sql .= " LIMIT " . (int)$option['start'] . "," . (int)$option['limit'];
    		}
    	    $data = $this->query($sql);
            if($data->num_rows){
    			return $data->rows;
    		}
    		else {
    			return false;
    		}
	}
  	public function saveQuotation($label_quotation_id) 
	{
		$sql = "UPDATE " .DB_PREFIX . "label_quotation  SET quotation_status = '1' WHERE label_quotation_id = '".$label_quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){ 
			return $data->row;
		}
		else {
			return false;
		}
	}
    public function ProductSize($size_master_id)
	{
         $sql  = "SELECT * FROM size_master WHERE size_master_id = '".$size_master_id."' "; 
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
   
    public function getQuotationQuantity($label_quotation_product_id)
	{
	    $data = $this->query("SELECT * FROM label_quotation_quantity as dqp,label_quotation_product dq  WHERE dq.label_quotation_product_id = '".$label_quotation_product_id."' AND dq.label_quotation_product_id=dqp.label_quotation_product_id ORDER BY dqp.label_quotation_quantity_id ");
	   
	    $return = array();
	    foreach($data->rows as $quotation_quantity){
	     
	         $sheet_details = $this->getQtyandeffects($quotation_quantity['sheet_id']);
        	 $volume='';
        	 if($quotation_quantity['sticker_size']!=0){
        	        $sticker_size=$this->getStickerSizeData($quotation_quantity['sticker_size']);
        	        $volume=$sticker_size['volume'];
        	    }
        	   
        	   $shape_name=$this->getLabelShapeName($quotation_quantity['shape_id']);
        	    
        	    $mes_text='mm';  
        	   $printting_effect_name=$this->geteffctname($quotation_quantity['printing_effect_id']);
        	   $make_pouch=$this->ProductMake($quotation_quantity['make_id']);
        	   $return[$quotation_quantity['transport']][$quotation_quantity['quantity']][] = array(
                    'label_quotation_quantity_id' =>$quotation_quantity['label_quotation_quantity_id'],
                    'label_quotation_product_id' =>$quotation_quantity['label_quotation_product_id'],
                    'label_quotation_id' =>$quotation_quantity['label_quotation_id'],
                    'quantity' =>$quotation_quantity['quantity'],
                    'transport' =>$quotation_quantity['transport'],
                    'tool_price' =>$quotation_quantity['tool_price'],
                    'punching_price' =>$quotation_quantity['punching_price'],
                    'profit_type' =>$quotation_quantity['profit_type'],
                    'profit_price' =>$quotation_quantity['profit_price'],
                    'printing_effect_price' =>$quotation_quantity['printing_effect_price'],
                    'printing_effect_detail' =>$printting_effect_name,
                    'no_of_sticker_sheet' =>intval($quotation_quantity['no_of_sticker_sheet']),
                    'sheet_printing_cost' =>$quotation_quantity['printing_cost'],
                    'sheet_total_weight' =>$quotation_quantity['total_weight'],
                    'wastage_price' =>$quotation_quantity['wastage_price'],
                    'price_per_label' =>$quotation_quantity['price_per_label'],
                    'printing_effect_foil_price' =>$quotation_quantity['printing_effect_foil_price'],
                    'packing_price' =>$quotation_quantity['packing_price'],
                    'transport_price' =>$quotation_quantity['transport_price'],
                    'courier_charge' =>$quotation_quantity['courier_charge'],
                    'total_amount' =>$quotation_quantity['total_amount'],
                    'gress_per' =>$quotation_quantity['gress_per'],
                    'gress_total_amount' =>$quotation_quantity['gress_total_amount'],
                    'tax_type' =>$quotation_quantity['tax_type'],
                    'igst' =>$quotation_quantity['igst'],
                    'sgst' =>$quotation_quantity['sgst'],
                    'cgst' =>$quotation_quantity['cgst'],
                    'product_name' =>$quotation_quantity['product_name'],
                    'product_id' =>$quotation_quantity['product_id'],
                    'make_id' =>$quotation_quantity['make_id'],
                    'make_name' =>$make_pouch['make_name'],
                    'sheet_id' =>$quotation_quantity['sheet_id'],
                    'sheet_name' =>$sheet_details['sheet_name'],
                    'sheet_width' =>$quotation_quantity['sheet_width'],
                    'sheet_height' =>$quotation_quantity['sheet_height'],
                    'sheet_left_margin' =>$quotation_quantity['sheet_left_margin'],
                    'sheet_right_margin' =>$quotation_quantity['sheet_right_margin'],
                    'sheet_header_margin' =>$quotation_quantity['sheet_header_margin'],
                    'sheet_footer_margin' =>$quotation_quantity['sheet_footer_margin'],
                    'margin_between_stickers' =>$quotation_quantity['margin_between_stickers'],
                    'sheet_wastage' =>$quotation_quantity['sheet_wastage'],
                    'per_sheet_price' =>$quotation_quantity['per_sheet_price'],
                    'no_of_sticker_per_sheet' =>$quotation_quantity['no_of_sticker_per_sheet'],
                    'printing_effect_id' =>$quotation_quantity['printing_effect_id'],
                    'shape_id' =>$quotation_quantity['shape_id'],
                    'shape_name' =>$shape_name,
                    'shape_id' =>$quotation_quantity['shape_id'],
                    'size_master_id' =>$quotation_quantity['size_master_id'],
                    'width' =>$quotation_quantity['width'], 
                    'height' =>$quotation_quantity['height'],
                    'gusset' =>$quotation_quantity['gusset'],
                    'sticker_size' =>$quotation_quantity['sticker_size'],
                    'sticker_width' =>$quotation_quantity['sticker_width'],
                    'sticker_height' =>$quotation_quantity['sticker_height'],
                    'sup_window' =>$quotation_quantity['sup_window'],
                    'currency_code' =>$quotation_quantity['currency_code'], 
                    'product_rate' =>$quotation_quantity['product_rate'],
                    'currency_id' =>$quotation_quantity['currency_id'],
                    'volume' =>$volume,
                    'dimension_with_unit' =>'<b>'.$volume.'</b>  ['.$quotation_quantity['width'].' '.$mes_text.' Width X'.$quotation_quantity['height'].' '.$mes_text.' Height X'.$quotation_quantity['gusset'].' '.$mes_text.' Gusset]',
                    'sticker_dimension_with_unit' =>' ['.$quotation_quantity['sticker_width'].' '.$mes_text.'Width X'.$quotation_quantity['sticker_height'].'Height ]',
                    'text' =>$make_pouch['make_name']
                    ); 
	    }  
			 
	  
      return $return;
	}

	public function getQuotation($label_quotation_id,$getData = '*',$user_type_id='',$user_id=''){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
			    $sql = "SELECT *  FROM " . DB_PREFIX ."  label_quotation_product dq INNER JOIN  label_quotation as dqi ON(dq.label_quotation_id=dqi.label_quotation_id)  WHERE dq.label_quotation_id = '".(int)$label_quotation_id."'";
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
					$str = ' OR ( dqi.user_id IN ('.$userEmployee.') AND dqi.user_type_id = 2 ) ';
				}
				$sql = "SELECT *  FROM " . DB_PREFIX ." label_quotation_product dq INNER JOIN " . DB_PREFIX ." label_quotation as dqi ON(dq.label_quotation_id=dqi.label_quotation_id) WHERE dq.label_quotation_id = '".(int)$label_quotation_id."' AND (( dqi.user_id = '".(int)$set_user_id."' AND dqi.user_type_id = '".(int)$set_user_type_id."') $str ) ";
			}
		}else{
			$sql = "SELECT * FROM " . DB_PREFIX ." label_quotation_product dq,digital_quotation dqi WHERE dq.digital_quotation_id=dqi.label_quotation_id  AND dq.label_quotation_id = '".(int)$label_quotation_id."'";
		}
	
		$data = $this->query($sql);
		return $data->rows;
	}


	public function addQuotation($data=array()){
	    $product_id = $data['product'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS']; 
    	if($user_type_id == 1 && $user_id == 1){
			$admin_user_id ='1';
		}else{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$admin_user_id =$parentdata->row['user_id'];
				
			}else {
				$admin_user_id = $this->query("SELECT international_branch_id FROM `" . DB_PREFIX . "international_branch`  WHERE international_branch_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'");
			
				 $admin_user_id = $admin_user_id->row['international_branch_id'];
				
				}
		} 
	    $userInfo = $this->getUserInfo($user_type_id,$user_id);
	    if($user_type_id==1){
	    	$UserWiseCurrency['currency_code']='INR';
	    	$UserWiseCurrency['product_rate']='1';
	    	$UserWiseCurrency['currency_id']='1';
		}else{ 
			$UserWiseCurrency = $this->getUserWiseCurrency($user_type_id,$user_id);
			$currCode=$userCurrency['currency_code'];
		}
	
		$userCountry = $this->getUserCountry($user_type_id,$user_id);
		$productName = getName('product','product_id',$product_id,'product_name');
	
	    if($data['sticker_size']!=0){
	        $sticker_size=$this->getStickerSizeData($data['sticker_size']);
	             $sticker_width=$sticker_size['max_width'];
	             $sticker_height=$sticker_size['max_height'];
	    }else{
	        $sticker_width=$data['sticker_width'];
	       $sticker_height=$data['sticker_height'];
	    }
	     if($data['size']!=0){
	        $size=$this->ProductSize($data['size']);
	             $width=$size['width'];
	             $height=$size['height'];
	             $gusset=$size['gusset'];
	    }else{
	        $width=$data['width'];
	       $height=$data['height'];
	       $gusset=$data['gusset'];
	    }
	    $tax_type='';$igst=$cgst=$sgst='0';
	    if(isset($data['country_id']) && !empty($data['country_id']) && $data['country_id'] ==111)
		{
			$tax_name.=' tax_name="'.$data['normalform'].'"';
			$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
			$data_tax = $this->query($sql);
			$taxation_data=$data_tax->row;
			$formula_qty['taxation_data']=$taxation_data;
		}
		$formula=array();

	        if(isset($data['lable_quotation_id']) )
			{
				$lable_quotation_id = $data['lable_quotation_id'];
			}
			else
			{
			    $contacts = "SELECT email_1 FROM company_address WHERE email_1='".$data['email']."' AND is_delete=0";
				$datacontacts= $this->query($contacts);
				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
				{
						$sql1 = "INSERT INTO  address_book_master  SET status = '1', company_name = '" . addslashes($data['customer']) . "', user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "', date_added = NOW(),date_modify = NOW()";
						$datasql1 = $this->query($sql1);
						$address_id = $this->getLastIdAddress();
						$address_book_id = $address_id['address_book_id'];
						
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET email_1 = '" . $data['email'] . "',country = '" . $data['country_id'] . "' WHERE company_address_id ='" . $dataadd->row['company_address_id'] . "'";
							$datasql2 = $this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO  company_address  SET  address_book_id = '" . $address_book_id . "',country = '" . $data['country_id'] . "', email_1 = '" . $data['email'] . "' ,user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "' , date_added = NOW(),date_modify = NOW()";
							$datasql2 = $this->query($sql2);
						}
				} 
				else
				{	
						$address_book_id = $data['address_book_id'];							
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET email_1 = '" . $data['email'] . "',country = '" . $data['country_id'] . "' WHERE company_address_id ='" . $dataadd->row['company_address_id'] . "'";
							$datasql2 = $this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO  company_address  SET  address_book_id = '" . $address_book_id . "',country = '" . $data['country_id'] . "', email_1 = '" . $data['email'] . "' ,user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "' , date_added = NOW(),date_modify = NOW()";
							$datasql2 = $this->query($sql2);
						}
				}
				if($userCountry){
        			$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
        		}else{
        			$countryCode='IN';
        		}
			
        		$newQuotaionNumber = $this->generateQuotationNumber();
        		$quotation_number = $countryCode.'LBL'.$newQuotaionNumber;
            	
				$sql =  "INSERT INTO  label_quotation SET  client_name = '".addslashes($data['customer'])."',email = '".$data['email']."',quotation_no = '".$quotation_number."',status = '1',quotation_status = '0',address_book_id = '".$address_book_id."', date_added = '".date('Y-m-d')."',date_modify = '".date('Y-m-d H:i:s')."', user_id = '".(int)$user_id."', user_type_id = '".(int)$user_type_id."', country_id = '".$data['country_id']."', admin_user_id = '".$admin_user_id."'";		
		        $this->query($sql);
				$lable_quotation_id = $this->getLastId();
            	
			}
	  //sheet details  
    	$sheet_details = $this->getQtyandeffects($data['material']);
        $sheet_width=$sheet_details['width'];
        $sheet_height=$sheet_details['height'];
        $sheet_left_margin=$sheet_details['left_margin'];
        $sheet_right_margin=$sheet_details['right_margin'];
        $sheet_header_margin=$sheet_details['header_margin'];
        $sheet_footer_margin=$sheet_details['footer_margin'];
        $sticker_between_stickers=$sheet_details['between_stickers'];  
	  
	   //calculation for no of sticker per sheet start
    	    $calculate_sheet_width=$sheet_width-($sheet_left_margin+$sheet_right_margin);
            $calculate_sheet_height=$sheet_height-($sheet_footer_margin+$sheet_header_margin);
            $calculate_sticker_width=$sticker_width+($sticker_between_stickers);
            $calculate_sticker_height=$sticker_height+($sticker_between_stickers);
            $row=intval($calculate_sheet_width/$calculate_sticker_width);
            $col=intval($calculate_sheet_height/$calculate_sticker_height);
            $no_of_sticker=$row*$col;
        
            $per_sheet_price=$sheet_details['price'];
            $sheet_printing_cost=0;
            //$sheet_printing_cost=$sheet_details['printing_cost'];//per sheet cost
	        $sheet_wastage=$sheet_details['wastage'];//per sheet cost
	        $sheet_weight=$sheet_details['weight'];//per sheet cost
        
       // end
            $formula_qty['sheet_weight']=$sheet_details['weight'];
            $formula_qty['sheet_wastage']=$sheet_wastage;
            $formula_qty['sheet_printing_cost']=$sheet_printing_cost;
            $formula_qty['per_sheet_price']=$per_sheet_price;
            $formula['sticker_width']=$sticker_width;
            $formula['sticker_height']=$sticker_height;
            $formula['make_pouch']=$data['make'];
            $formula['sheet_width']=$sheet_width;
            $formula['sheet_height']=$sheet_height;
            $formula['sheet_left_margin']=$sheet_left_margin;
            $formula['sheet_right_margin']=$sheet_right_margin;
            $formula['sheet_header_margin']=$sheet_header_margin;
            $formula['sheet_footer_margin']=$sheet_footer_margin;
            $formula['sticker_between_stickers']=$sticker_between_stickers;
            $formula['calculate_sheet_width']=$calculate_sheet_width;
            $formula['calculate_sheet_height']=$calculate_sheet_height;
            $formula['calculate_sticker_width']=$calculate_sticker_width;
            $formula['calculate_sticker_height']=$calculate_sticker_height;
            $formula['row']=$row;
            $formula['col']=$col; 
            $formula['no_of_sticker']=$no_of_sticker; 
	        $sql_product =  "INSERT INTO ".DB_PREFIX." label_quotation_product SET  label_quotation_id = '".$lable_quotation_id."',product_id = '".$data['product']."',product_name = '".$productName."',make_id = '".$data['make']."',sheet_id = '".$data['material']."',sheet_width = '".$sheet_width."',sheet_height = '".$sheet_height."',sheet_left_margin = '".$sheet_left_margin."',sheet_right_margin = '".$sheet_right_margin."',sheet_header_margin = '".$sheet_header_margin."',sheet_footer_margin = '".$sheet_footer_margin."',margin_between_stickers = '".$sticker_between_stickers."',sheet_wastage = '".$sheet_wastage."',sheet_weight = '".$sheet_weight."',per_sheet_price = '".$per_sheet_price."',printing_effect_id = '".implode(",",$data['effect'])."',shape_id = '".$data['shape']."',size_master_id = '".$data['size']."',width = '".$width."',height = '".$height."',gusset = '".$gusset."',sticker_size = '".$data['sticker_size']."',sticker_width = '".$sticker_width."',sticker_height = '".$sticker_height."',no_of_sticker_per_sheet = '".$no_of_sticker."',sup_window = '".$data['sup_window']."',status = '1',is_delete=0,date_added = '".date('Y-m-d')."',date_modify = '".date('Y-m-d H:i:s')."'";	
	        $this->query($sql_product);
	    	$label_quotation_product_id = $this->getLastId();
            
        	        
        	    foreach($data['transpotation'] as $trans){        
            	    foreach($data['quantity'] as $quantity){
            	        
                            if($quantity<2000)
                                $qty = '2000';
                            else
                                $qty = $quantity;
            	        
            	              $formula_qty=array();
            	              $formula_qty['transpotation']=$trans.'=='.$qty;
            	              
            	              $price_details=$this->getcalculateProfit($quantity,$product_id,$sticker_height,$sticker_width,$data['profit']); //add $quantity for the getting real profit when order qty is < 2000.
            	               $total_effect_price=0;
            	               $effect_price_details=array();
            	                foreach($data['effect'] as $effect){
            	                    if($effect!=5){
            	                      $printing_effect_price=$this->getLabelEffectprice($effect);
            	                      $total_effect_price=$total_effect_price+$printing_effect_price; //per sheet cost
            	                      $effect_price_details[]=$effect.'=='.$printing_effect_price;
            	                    }
            	                }
            	                $formula_qty['total_effect_price']=$total_effect_price;
            	                
            	               $effect_p = array_unique($effect_price_details);
                		       $save_effect_price= implode(',',$effect_p);
                		     
                		       if($price_details['profit'])
                		       {
                		           $profit_price = $price_details['profit'];
                		           $profit_type = 'Rich';
                		       }
                		       else if($price_details['profit_poor'])
                		       {
                		           $profit_price = $price_details['profit_poor'];
                		           $profit_type = 'Profit Poor';
                		       }
                		       else
                		       {
                		           $profit_price = $price_details['profit_more_poor'];
                		           $profit_type = 'More Profit Poor';
                		       }
                		       $formula_qty['profit_price']=$profit_price;
                		        
            		        $tool_price_stock=$price_details['tool_price_stock']; // per sticker for stock only
        	                $tool_price_custom=$price_details['tool_price_custom']; // all over job custom only
        	              
        	                $total_per_sheet_price= $per_sheet_price+$total_effect_price;
            	            $formula_qty['total_per_sheet_price']=$total_per_sheet_price;
            	            
        	                $calculate_sticker_sheet=$qty/$no_of_sticker;
        	                $formula_qty['calculate_sticker_sheet']=$calculate_sticker_sheet;
        	                
                            $sticker_sheet_calculate_price=($calculate_sticker_sheet*$total_per_sheet_price);
                             $formula_qty['sticker_sheet_calculate_price']=$sticker_sheet_calculate_price;
                            
                            $total_sheet_weight=intval($calculate_sticker_sheet)*$sheet_details['weight'];
                            $formula_qty['total_sheet_weight']=$total_sheet_weight;
                            
                            $punching_price =$this->getPunchingCost(intval($calculate_sticker_sheet));
                            $formula_qty['punching_price']=$punching_price;
                            
                            $sticker_sheet_calculate_price=$sticker_sheet_calculate_price+$sheet_printing_cost;  // add printing cost per sheet // +$sheet_printing_cost
                             $formula_qty['sticker_sheet_calculate_price_with_printing_cost']=$sticker_sheet_calculate_price;
                            
                            $wastage_per_sheet=(($sticker_sheet_calculate_price*$sheet_wastage)/100);   // calculate wastage per sheet
                            $formula_qty['wastage_per_sheet']=$wastage_per_sheet;
                            
                            $sticker_sheet_calculate_price=$sticker_sheet_calculate_price+$wastage_per_sheet; //add wastage_per_sheet
                            $formula_qty['sticker_sheet_calculate_price+wastage']=$sticker_sheet_calculate_price;
                            
                            $price_per_label=($sticker_sheet_calculate_price/$qty); // price per label
                            $formula_qty['price_per_label']=$price_per_label;
                            
                                 if($data['sticker_size']!=0){
                                    // for stock pouch
                                    $price_per_label=$price_per_label+$tool_price_stock; // add tool cost per label
                                    $formula_qty['price_per_label_with_tool_cost']=$price_per_label;
                                    $final_tool_price=$tool_price_stock;
                                    $formula_qty['tool_price_stock']=$tool_price_stock;
                                 }
                                 $printing_effect_foil_price=0 ;  
                                 if(in_array(5,$data['effect'])){
                                     //foil printing effect
                                     $printing_effect_foil_price=$this->getLabelEffectprice(5);
                                      $price_per_label=$price_per_label+$printing_effect_foil_price; // add foil printing effect per label
                                      
                                      $formula_qty['price_per_label_with_tool_cost_foil_effect']=$price_per_label;
                                      $formula_qty['printing_effect_foil_price']=$printing_effect_foil_price;
                                 }
                                     
                                $packing_price=$this->getcalculatepacking_price($sticker_height,$sticker_width);
                                 $formula_qty['packing_price']=$packing_price;
                                 
                                $transport_price=$courier_price=0;
                                if($trans=='sea')
                                {
                                  $transport_price=0; //add transport price
                                  $formula_qty['transport_price']=$transport_price;
                                  //$gress_by_sea = $this->getGressQtyWise($quantity,$trans,$admin_user_id,'label');
                                }
                                else
                                {
                                  $courier_price=0;  //add courier price
                                  $formula_qty['transport_price']=$transport_price;
                                 // $gress_by_air = $this->getGressQtyWise($quantity,$trans,$admin_user_id,'label');
                                }
                                 
                                 
                                $price_per_label=$price_per_label+$packing_price+$profit_price+$transport_price; // add packing price + profit price + transport price per label
                                $formula_qty['price_per_label+packing+profit+transport']=$price_per_label;
                                
                                $total_label_amount=$price_per_label*$qty;
                                $formula_qty['$qty']=$qty;      
                              if($data['sticker_size']==0){
                                   //$total_label_amount=$total_label_amount+$tool_price_custom;  //add tool cost for custom job
                                   $final_tool_price=$tool_price_custom;
                                   $formula_qty['tool_price_custom']=$tool_price_custom;
                              }
                               $total_label_amount=$total_label_amount+$courier_price+$punching_price; //per job courier amt + punching cost
                               $formula_qty['total_label_amount_courier+punching']=$total_label_amount;
                               
                               $final_per_label_price = $total_label_amount / $qty;
                               $formula_qty['final_per_label_price']=$final_per_label_price;
                             $totalPriceForTax=0;
                            if(isset($taxation_data) && !empty($taxation_data))
							{
								$totalPriceForTax=$total_label_amount;
								if($data['normalform']=='Out Of Gujarat')
								{
									$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*$taxation_data['igst']/100);
									$formula_qty['$totalPricWithExcies+($totalPricWithExcies*$taxation_data[igst]/100)']=$totalPricWithExcies.'+('.$totalPricWithExcies.'*'.$taxation_data['igst'].'/100)';
									$igst =$taxation_data['igst'];
								}
								else
								{
									$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*($taxation_data['cgst']+$taxation_data['sgst'])/100);
									$formula_qty['$totalPricWithExcies+($totalPricWithExcies*($taxation_data[cgst]+$taxation_data[sgst])/100)']=$totalPricWithExcies.'+('.$totalPricWithExcies.'*('.$taxation_data['cgst'].'+'.$taxation_data['sgst'].')/100)';
									$cgst =$taxation_data['cgst'];
									$sgst =$taxation_data['sgst'];
								}
								$per_label_price_with_tax = $totalPriceWithTax / $qty;
								$formula_qty['$totalPriceWithTax']=$totalPriceWithTax;
								$formula_qty['$per_label_price_with_tax']=$per_label_price_with_tax;
								$formula_qty['$igst=$cgst=$sgst=']=$igst.'='.$cgst.'='.$sgst;
								$tax_type=$data['normalform'];
							}
							 $total_label_amount= $final_per_label_price * $quantity;
							 $gress_per_detail = $this->getGressQtyWise($quantity,$trans,$admin_user_id,'label');
                             $gress_per = $gressPrice = 0;
                             if($gress_per_detail)
                             {
                                $gressPrice = $this->numberFormate((($total_label_amount * $gress_per_detail['percentage']) / 100),"3");
                                $gress_per=$gress_per_detail['percentage'];
                             }    
                             $sql_qty =  "INSERT INTO ".DB_PREFIX." label_quotation_quantity SET  label_quotation_id = '".$lable_quotation_id."',label_quotation_product_id = '".$label_quotation_product_id."',quantity = '".$quantity."',transport = '".$trans."',tool_price = '".$final_tool_price."',punching_price = '".$punching_price."',profit_type = '".$profit_type."',profit_price = '".$profit_price."',printing_effect_price = '".$total_effect_price."',printing_effect_detail = '".$save_effect_price."',	no_of_sticker_sheet = '".$calculate_sticker_sheet."',printing_cost = '".$sheet_printing_cost."',total_weight = '".$total_sheet_weight."',wastage_price = '".$wastage_per_sheet."',price_per_label = '".$final_per_label_price."',printing_effect_foil_price = '".$printing_effect_foil_price."',packing_price = '".$packing_price."',transport_price = '".$transport_price."',courier_charge = '".$courier_price."',	total_amount = '".$total_label_amount."',gress_per = '".$gress_per."',gress_total_amount = '".$gressPrice."',tax_type = '".$tax_type."', igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."',per_label_price_with_tax = '".$per_label_price_with_tax."',total_amount_with_tax = '".$totalPriceWithTax."', currency_code = '".$UserWiseCurrency['currency_code']."',currency_id = '".$UserWiseCurrency['currency_id']."',product_rate = '".$UserWiseCurrency['product_rate']."',is_delete=0,date_added = '".date('Y-m-d')."',date_modify = '".date('Y-m-d H:i:s')."'";	
                             $this->query($sql_qty);      
            	        }
        	    } 
	     
          return $lable_quotation_id; 
	}
	
	public function getLabelEffectprice($effect_id)
	{
		$sql = "SELECT price FROM " . DB_PREFIX . "label_printing_effect WHERE is_delete=0 AND printing_effect_id = '".(int)$effect_id."' ";
	    $data = $this->query($sql);
		if($data->num_rows){
			return $this->numberFormate($data->row['price'],"3");
		}else{
			return 0;
		}
	}  
	public function geteffctname($effect_id){
	  $sql = "SELECT GROUP_CONCAT(effect_name)  as name FROM " . DB_PREFIX . "label_printing_effect WHERE is_delete=0 AND printing_effect_id IN( ".$effect_id.") ";
	  $data = $this->query($sql); 
	
		if($data->num_rows){
			return $data->row['name'];
		}else{
			return 0;
		}
	}   
	public function generateQuotationNumber(){
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'label_quotation'");
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,8,'0',STR_PAD_LEFT);
		return $strpad;
	}
	
	public function getProductData($product_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '".$product_id."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return 0;
		}
	}
	
	public function getPunchingCost($sheet_qty){
		$data = $this->query("SELECT price FROM " . DB_PREFIX . "label_punching WHERE from_qty <= '".$sheet_qty."' AND to_qty >= '".$sheet_qty."'");
		$price = 0;
		if($data->row['price'] > 0){
				$price = $data->row['price'];	
		}
    	return $this->numberFormate(($price),"5"); 
	}
	
	public function getcalculateProfit($quantity,$product_id,$height,$width,$profit_type){
		$size = $height*$width;
		$qunatityRow = $this->query("SELECT label_quantity_id FROM " . DB_PREFIX . "label_quantity WHERE quantity = '".$quantity."'");
		$quantity_id = $qunatityRow->row['label_quantity_id'];
		$data = $this->query("SELECT tool_price_stock,tool_price_custom,$profit_type FROM " . DB_PREFIX . "label_profit WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND size_from <= '".$size."' AND 	size_to >= '".$size."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getcalculatepacking_price($sticker_height,$sticker_width){
	    $cal_size= $sticker_height*$sticker_width;
		$data = $this->query("SELECT price FROM " . DB_PREFIX . "label_packing WHERE from_total <= '".$cal_size."' AND 	to_total >= '".$cal_size."'");
		$packing_charge =0;
		if($data->row['price'] > 0){
				$packing_charge = $data->row['price'];	
			}
    	return $this->numberFormate(($packing_charge),"5"); 
	}
    public function getProduct()   
	{ 
		$sql = "SELECT * from " . DB_PREFIX ."product where label_available=1 ORDER BY product_name ASC ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else {
			return false;
		}
	}
	public function getLabelShape()
	{
        $sql="SELECT * FROM lable_shape_master WHERE is_delete=0 AND status=1";		     
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
	public function getLabelShapeName($shape_id)
	{
        $sql="SELECT * FROM lable_shape_master WHERE is_delete=0 AND shape_master_id='".$shape_id."'";		     
		$data=$this->query($sql); 
		if($data->num_rows)
		{
			return $data->row['shape_name'];
		}
		else
		{
			return false;
		}
	} 	
	public function getLabelEffect()
	{
        $sql="SELECT * FROM label_printing_effect WHERE is_delete=0";		     
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
	public function getStickerSizeData($label_size_master_id)
	{
        $sql="SELECT * FROM  label_size_master WHERE is_delete=0 AND label_size_master_id='".$label_size_master_id."'";	
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
	public function getStickerSize($product_id,$size_master_id,$shape_id,$sup_window)
	{
	    $sql_text="";
	    if($product_id==3 || $product_id==8)
	        $sql_text=" AND product_category='".$sup_window."' ";
        $sql="SELECT * FROM  label_size_master WHERE is_delete=0 AND shape_id='".$shape_id."' AND size_master_id='".$size_master_id."' AND product_id='".$product_id."' $sql_text ";		     
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
	public function getLabelSheetmaterial($make_pouch)
	{
        $sql="SELECT * FROM sheet_management WHERE is_delete=0 AND make_pouch LIKE '%".$make_pouch."%'";		     
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
	public function getQtyandeffects($sheet_id)
	{
        $sql="SELECT * FROM sheet_management WHERE is_delete=0 AND sheet_id='".$sheet_id."' ";		     
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
	public function getLabelQty($label_quantity_id)
	{
	    $sql="SELECT * FROM label_quantity WHERE is_delete=0 AND label_quantity_id IN (".$label_quantity_id.") ";		     
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
	public function getMake(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_make` WHERE make_id IN (2,1) ORDER BY  FIELD (make_id,'2','1')";
		$data = $this->query($sql);
        if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function ProductMake($make_id){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0'  AND make_id =".$make_id;
	    $data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		} 
	}
	public function getEmailHistories($quotation_id)
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX . " label_quotation_email_history WHERE label_quotation_id ='".$quotation_id."' ORDER BY sent_date DESC ");
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	public function deleteQuotation($quotation_id){
		$this->query("DELETE  FROM " . DB_PREFIX . "label_quotation  WHERE label_quotation_id IN ('".$quotation_id."')");
    	$this->query("DELETE  FROM " . DB_PREFIX . "label_quotation_product  WHERE label_quotation_id IN ('".$quotation_id."')");
    	$this->query("DELETE  FROM " . DB_PREFIX . "label_quotation_quantity  WHERE label_quotation_id IN ('".$quotation_id."')"); 				
	}
    public function getUserList(){
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
		$data = $this->query($sql);
		return $data->rows;
	}
	public function getProductList(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' AND product_id IN(3,7,1,20,19,10,4,12,13,16,42,30,31,53,54,50,22) ORDER BY product_name ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getQuotationCurrecy($selCurrencyId,$source){
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "multi_product_quotation_currency WHERE product_quotation_currency_id ='".$selCurrencyId."' AND source = '".$source."' ");
		if($data->num_rows){
			$result =array(
							'product_quotation_currency_id' => $data->row['product_quotation_currency_id'],
							'currency_id' => $data->row['currency_id'],
							'currency_code' => $data->row['currency_code'],
							'currency_rate' => $data->row['currency_rate'],
							'currency_base_rate' => $data->row['currency_base_rate'],
							'source' => 1,
							'date_added' => $data->row['date_added'],
						);
			return $result;
		}else{
			return false;
		}
	}
	public function CheckQuotationTax($label_product_quotation_id)
	{
        $sql = "SELECT tax_type,currency_code as currency FROM " . DB_PREFIX ."label_quotation_quantity WHERE label_quotation_product_id = '".(int)$label_product_quotation_id."' ORDER BY label_quotation_product_id ASC LIMIT 1";
		$data = $this->query($sql);
		return $data->row;
	}
	public function setQuotationCurrency($quotation_id,$currency_code,$currencyRate,$source,$sec_curr='',$sec_curr_rate=''){
	    $this->query("INSERT INTO " . DB_PREFIX . "label_quotation_currency SET quotation_id = '".$quotation_id."', currency_id = '', currency_code = '".$currency_code."', currency_rate = '".(float)$currencyRate."', currency_base_rate = '', source = '".$source."',sec_curr='".$sec_curr."',sec_curr_rate='".$sec_curr_rate."',date_added = NOW()");
		return $this->getLastId();
	}
	public function getGressQtyWise($qty,$transport,$admin_user_id,$type='')
	{
		$data = $this->query("SELECT percentage FROM " . DB_PREFIX ."gress_percentage WHERE ib_id = '".$admin_user_id."' AND transport='".$transport."'  AND product_quantity='".$qty."' AND type='".$type."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function UpdateQuotation($lable_quotation_id)
	{
        $quote_detail = $this->getQuotationDetails($lable_quotation_id);
        $quote_product = $this->query("SELECT * FROM label_quotation_quantity as dqp,label_quotation_product dq, label_quotation as lq WHERE lq.label_quotation_id = '".$lable_quotation_id."' AND lq.label_quotation_id = dq.label_quotation_id AND lq.label_quotation_id = dqp.label_quotation_id AND dq.label_quotation_product_id=dqp.label_quotation_product_id");
        $total_count = $this->query("SELECT dqp.transport, dq.make_id, dq.sheet_id, COUNT(label_quotation_quantity_id) total_order,GROUP_CONCAT(label_quotation_quantity_id) as label_quotation_quantity_id FROM label_quotation_quantity as dqp,label_quotation_product dq, label_quotation as lq WHERE lq.label_quotation_id = '".$lable_quotation_id."' AND lq.label_quotation_id = dq.label_quotation_id AND lq.label_quotation_id = dqp.label_quotation_id AND dq.label_quotation_product_id=dqp.label_quotation_product_id GROUP BY dq.make_id, dqp.transport");
        $array_new = array();
        foreach($total_count->rows as $rate)
        {
            $label_qty_id = explode(',',$rate['label_quotation_quantity_id']);
            $sheet_details = $this->getQtyandeffects($rate['sheet_id']);
            $printing_cost = $sheet_details['printing_cost'] / $rate['total_order'];
            foreach($label_qty_id as $qty_id)
            {
                $array_new[$qty_id]=$printing_cost; 
            }
        }
        foreach($quote_product->rows as $quote)
        {
            $product_id = $quote['product_id'];
		    $admin_user_id =$quote_detail['admin_user_id'];
    		if($quote['sticker_size']!=0){
    	        $sticker_size=$this->getStickerSizeData($quote['sticker_size']);
    	        $sticker_width=$sticker_size['max_width'];
    	        $sticker_height=$sticker_size['max_height'];
    	    }else{
    	        $sticker_width=$quote['sticker_width'];
    	        $sticker_height=$quote['sticker_height'];
    	    }
    	    $width=$quote['width'];
    	    $height=$quote['height'];
    	    $gusset=$quote['gusset'];
    	    
    	    $sheet_width=$quote['sheet_width'];
            $sheet_height=$quote['sheet_height'];
            $sheet_left_margin=$quote['sheet_left_margin'];
            $sheet_right_margin=$quote['sheet_right_margin'];
            $sheet_header_margin=$quote['sheet_header_margin'];
            $sheet_footer_margin=$quote['sheet_footer_margin'];
            $sticker_between_stickers=$quote['margin_between_stickers'];
            $calculate_sheet_width=$sheet_width-($sheet_left_margin+$sheet_right_margin);
            $calculate_sheet_height=$sheet_height-($sheet_footer_margin+$sheet_header_margin);
            $calculate_sticker_width=$sticker_width+($sticker_between_stickers);
            $calculate_sticker_height=$sticker_height+($sticker_between_stickers);
            $row=intval($calculate_sheet_width/$calculate_sticker_width);
            $col=intval($calculate_sheet_height/$calculate_sticker_height);
            $no_of_sticker=$row*$col;
            $per_sheet_price=$quote['per_sheet_price'];
            $sheet_printing_cost=$array_new[$quote['label_quotation_quantity_id']];//per sheet cost
	        $sheet_wastage=$quote['sheet_wastage'];//per sheet cost
	        $sheet_weight=$quote['sheet_weight'];//per sheet cost
	        if($quote['quantity']<2000)
                $qty = '2000';
            else
                $qty = $quote['quantity'];
            if($quote['profit_type'] =='Rich')
                $profit_type = 'profit';
            else if($quote['profit_type'] =='Profit Poor')
                $profit_type = 'profit_poor';
            else
                $profit_type = 'profit_more_poor';

            $total_effect_price=$printing_effect_foil_price=0 ;
            $effect_price_details=array();
            $effect1=explode(',',$quote['printing_effect_detail']);
            foreach($effect1 as $eff){
                $effect = explode('==',$eff);
                if($effect[0]!=5){
                  $total_effect_price=$total_effect_price+$effect[1]; //per sheet cost
                  $effect_price_details[]=$effect.'=='.$effect[1];
                }
                else
                {
                    $printing_effect_foil_price = $effect[1];
                }
            }
            $total_per_sheet_price= $per_sheet_price+$total_effect_price;
            $calculate_sticker_sheet=$qty/$no_of_sticker;
            $sticker_sheet_calculate_price=($calculate_sticker_sheet*$total_per_sheet_price);
            $total_sheet_weight=intval($calculate_sticker_sheet)*$sheet_weight;
            $sticker_sheet_calculate_price=$sticker_sheet_calculate_price+$sheet_printing_cost;
            $wastage_per_sheet=(($sticker_sheet_calculate_price*$sheet_wastage)/100);
            $sticker_sheet_calculate_price=$sticker_sheet_calculate_price+$wastage_per_sheet;
            $price_per_label=($sticker_sheet_calculate_price/$qty);
            if($quote['sticker_size']!=0){
                // for stock pouch
                $price_per_label=$price_per_label+$quote['tool_price']; // add tool cost per label
                $final_tool_price=$tool_price_stock;
            }
            if($printing_effect_foil_price!='0'){
                 //foil printing effect
                 $price_per_label=$price_per_label+$printing_effect_foil_price; // add foil printing effect per label
            }
            $transport_price=$courier_price=0;
            if($trans=='sea')
                $transport_price=0; //add transport price
            else
                $courier_price=0;  //add courier price
                
            $price_per_label=$price_per_label+$quote['packing_price']+$quote['profit_price']+$transport_price;
            $total_label_amount=$price_per_label*$qty;
            if($quote['sticker_size']==0){
               $final_tool_price=$quote['tool_price'];
            }
            $total_label_amount=$total_label_amount+$courier_price+$quote['punching_price'];
            $final_per_label_price = $total_label_amount / $qty;
            $totalPriceForTax=0;
            if($qute['igst']!=0 || ($qute['cgst']!=0 && $qute['sgst']!=0))
			{
				$totalPriceForTax=$total_label_amount;
				if($qute['igst']!=0)
				{
					$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*$qute['igst']/100);
					$igst =$qute['igst'];
				}
				else
				{
					$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*($qute['cgst']+$qute['sgst'])/100);
					$cgst =$qute['cgst'];
					$sgst =$qute['sgst'];
				}
				$per_label_price_with_tax = $totalPriceWithTax / $qty;
				$tax_type=$qute['tax_type'];
			}
			$total_label_amount= $final_per_label_price * $quote['quantity'];
			if($quote['gress_per']!=0)
			{
			    $gressPrice = $this->numberFormate((($total_label_amount * $quote['gress_per']) / 100),"3");
			}
			$sql_qty =  "UPDATE label_quotation_quantity SET printing_cost = '".$sheet_printing_cost."',wastage_price = '".$wastage_per_sheet."',price_per_label = '".$final_per_label_price."',printing_effect_foil_price = '".$printing_effect_foil_price."',transport_price = '".$transport_price."',courier_charge = '".$courier_price."',	total_amount = '".$total_label_amount."',gress_total_amount = '".$gressPrice."',per_label_price_with_tax = '".$per_label_price_with_tax."',total_amount_with_tax = '".$totalPriceWithTax."'  WHERE label_quotation_quantity_id=".$quote['label_quotation_quantity_id']." ";
		    $this->query($sql_qty);  
        }
    }
}
?>