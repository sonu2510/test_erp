<?php  //[kinjal]-->
class samplerequest extends dbclass{
	
	public function addRequest($data,$code){
				
		$address_book_id = $data['address_book_id'];
				
				$contacts = "SELECT email_1 FROM company_address WHERE email_1='".$data['email_1']."' AND is_delete=0";
				$datacontacts= $this->query($contacts);
				//printr($datacontacts);
				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
				{
						$sql1 = "INSERT INTO address_book_master SET status = '1', company_name = '".addslashes($data['company_nm'])."',contact_name = '".addslashes($data['contact_nm'])."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' ,user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW(),is_delete=0";
						//echo $sql1;
						$datasql1=$this->query($sql1);
						$address_id = $this->getLastIdAddress();
						$address_book_id = $address_id['address_book_id'];
						//printr($address_book_id);
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						//printr($dataadd);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET c_address = '".addslashes($data['address'])."',email_1 = '".$data['email_1']."', country= '".$data['country_id']."',city='".$data['city']."',pincode='".$data['pin_code']."',phone_no='".$data['phone_no']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
							$datasql2=$this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($data['address'])."', email_1 = '".$data['email_1']."', country= '".$data['country_id']."',city='".$data['city']."',pincode='".$data['pin_code']."', date_added = NOW(),phone_no='".$data['phone_no']."'";
							$datasql2=$this->query($sql2);
						}
						
						
				}
				else
				{	
						$address_book_id = $data['address_book_id'];
						$sql1 = "UPDATE address_book_master SET company_name = '".addslashes($data['company_nm'])."',contact_name = '".addslashes($data['contact_nm'])."' WHERE address_book_id ='".$data['address_book_id']."'";
						//echo $sql1;
						$datasql1=$this->query($sql1);
							
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						//printr($dataadd);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET c_address = '".addslashes($data['address'])."',email_1 = '".$data['email_1']."', country= '".$data['country_id']."',city='".$data['city']."',pincode='".$data['pin_code']."',phone_no='".$data['phone_no']."' WHERE company_address_id ='".$dataadd->row['company_address_id']."'";
							$datasql2=$this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO company_address SET  address_book_id = '".$address_book_id."',c_address = '".addslashes($data['address'])."', email_1 = '".$data['email_1']."', country= '".$data['country_id']."',city='".$data['city']."',pincode='".$data['pin_code']."', date_added = NOW(),phone_no='".$data['phone_no']."'";
							$datasql2=$this->query($sql2);
						}
						//echo $sql2;
						
				}
				//$pi = 'SAMP-';
			//	$new_no = $this->generateSampleNumber();
				//$no = $pi.$new_no;
				
		     $pi = 'S';
        	$new_no = $this->generateSampleNumber();
        	
        	$no = $pi.$new_no;
		
			$sql1 = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND tax_name='".$data['taxation']."' ORDER BY taxation_id DESC LIMIT 1";
	            	$data_tax = $this->query($sql1);
    	            $tax_data=$data_tax->row;
    	            
    	            
		$sql= "INSERT INTO ".DB_PREFIX."sample_request SET company_nm='".addslashes($data['company_nm'])."',sample_no='".$no."',no_of_package='".$data['no_of_package']."',pouch_mode='".$data['pouch_mode']."',amount='".$data['amount']."',identification_marks='".$data['identification_marks']."',address_book_id='".$address_book_id."',contact_nm ='".addslashes($data['contact_nm'])."',phone_no='".$data['phone_no']."',phone_no1='".$data['phone_no1']."',phone_no2='".$data['phone_no2']."',email_1='".$data['email_1']."',email_2='".$data['email_2']."',email_3='".$data['email_3']."',city='".$data['city']."',country='".$data['country_id']."',currency_id='".$data['currency_id']."',pin_code='".$data['pin_code']."',address='".addslashes($data['address'])."',remark='".$data['remark']."',invoice_status='".$data['invoice_status']."',date_added = NOW(),date_modify=NOW(),sended_otp='".$code."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',requester = '".$data['requester']."' ,taxation='".$data['taxation']."',cgst='".$tax_data['cgst']."',sgst='".$tax_data['sgst']."',igst='".$tax_data['igst']."'";
		//echo $sql;
		$this ->query($sql);
	//	die;
		
		
		return $this->getLastId();
	}
	
	public function updateRequest($request_id,$data){
		
		$sql1 = "SELECT taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND tax_name='".$data['taxation']."' ORDER BY taxation_id DESC LIMIT 1";
	            	$data_tax = $this->query($sql1);
    	            $tax_data=$data_tax->row;
    	            
		$sql = "UPDATE " . DB_PREFIX . "sample_request SET company_nm='".addslashes($data['company_nm'])."',sample_no='".$data['sample_no']."',pouch_mode='".$data['pouch_mode']."',no_of_package='".$data['no_of_package']."',amount='".$data['amount']."',identification_marks='".$data['identification_marks']."',contact_nm ='".addslashes($data['contact_nm'])."',phone_no=".$data['phone_no'].",phone_no1='".$data['phone_no1']."',phone_no2='".$data['phone_no2']."',email_1='".$data['email_1']."',email_2='".$data['email_2']."',email_3='".$data['email_3']."',city='".$data['city']."',currency_id='".$data['currency_id']."',country='".$data['country_id']."',pin_code='".$data['pin_code']."',address='".addslashes($data['address'])."' ,date_modify=NOW(),remark='".$data['remark']."',requester = '".$data['requester']."',invoice_status = '".$data['invoice_status']."',taxation='".$data['taxation']."',cgst='".$tax_data['cgst']."',sgst='".$tax_data['sgst']."',igst='".$tax_data['igst']."'  WHERE request_id = '" .(int)$request_id. "'";
	    //echo $sql;
		$this->query($sql);
	}
	public function getRequest($request_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "sample_request WHERE request_id = '" .(int)$request_id. "'";
		//echo $sql;die; 
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getTotalRequest($filter_data){
		//if(($_SESSION['ADMIN_LOGIN_SWISS']==1 && $_SESSION['LOGIN_USER_TYPE']==1) || ($_SESSION['ADMIN_LOGIN_SWISS']==145 && $_SESSION['LOGIN_USER_TYPE']==2) )
			$sql = "SELECT COUNT(*) as total FROM ".DB_PREFIX."sample_request WHERE is_delete=0";
		//else
			//$sql = "SELECT COUNT(*) as total FROM ".DB_PREFIX."sample_request WHERE is_delete=0  AND user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."'";
		 if (!empty($filter_data)) {
            if (!empty($filter_data['name'])) {
                $sql .= " AND company_nm LIKE '%" . $filter_data['name'] . "%' ";
				
            }
			
			 if (!empty($filter_data['contact_nm'])) {
                $sql .= " AND contact_nm LIKE '%" . $filter_data['contact_nm'] . "%' ";
				
            }

            if (!empty($filter_data['user_name'])) {
				$spitdata = explode("=", $filter_data['user_name']);               
			  $sql .= " AND user_type_id = '" . $spitdata[0] . "' AND user_id = '" . $spitdata[1] . "'";
				
            }
            if (!empty($filter_data['sample'])) {
				            
				 $sql .= " AND sample_no LIKE '%" . $filter_data['sample'] . "%' ";
				
            }
            if (!empty($filter_data['address'])) {
				            
				 $sql .= " AND address LIKE '%" . $filter_data['address'] . "%' ";
				
            }
			
		 }
		//echo $sql;
		$data = $this -> query($sql);
		return $data->row['total'];
	}
		
	public function getRequests($data,$filter_data)
	{
	
    //if(($_SESSION['ADMIN_LOGIN_SWISS']==1 && $_SESSION['LOGIN_USER_TYPE']==1) || ($_SESSION['ADMIN_LOGIN_SWISS']==145 && $_SESSION['LOGIN_USER_TYPE']==2) )
		$sql = "SELECT * FROM ".DB_PREFIX."sample_request WHERE is_delete=0";
//	else
		//$sql = "SELECT * FROM ".DB_PREFIX."sample_request WHERE is_delete=0 AND user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."'";
	
	if (!empty($filter_data)) {
            if (!empty($filter_data['name'])) {
                $sql .= " AND company_nm LIKE '%" . $filter_data['name'] . "%' ";
				
            }
			
			 if (!empty($filter_data['contact_nm'])) {
                $sql .= " AND contact_nm LIKE '%" . $filter_data['contact_nm'] . "%' ";
				
            }

            if (!empty($filter_data['email'])) {
                $sql .= " AND email_1 LIKE '%" . $filter_data['email'] . "%' ";
				
            }
			if (!empty($filter_data['user_name'])) {
				$spitdata = explode("=", $filter_data['user_name']);               
				$sql .= " AND user_type_id = '" . $spitdata[0] . "' AND user_id = '" . $spitdata[1] . "'";
				
            }
			if (!empty($filter_data['sample'])) {
				            
				 $sql .= " AND sample_no LIKE '%" . $filter_data['sample'] . "%' ";
				
            }
            if (!empty($filter_data['address'])) {
				            
				 $sql .= " AND address LIKE '%" . $filter_data['address'] . "%' ";
				
            }
		 }
		
	
	if(isset($data['sort'])){
		$sql .=" ORDER BY ".$data['sort'];
	}else{ 
		$sql .=" ORDER BY request_id";	
	}
	
	if(isset($data['order']) && ($data['order'] == 'DESC')){
		$sql .=" ASC";
	}else{
		$sql .=" DESC";
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
		$result = $this->query($sql);
		if($result->num_rows){
			return $result->rows;
		}else{
			return false;
		}
		}
		
	
	public function getUser($user_id,$user_type_id)
	{
		$cond = '';
		if ($user_type_id == 1) {

            $sql = "SELECT acc.status,ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state,ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX . "user u LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
            //echo $sql;
        } elseif ($user_type_id == 2) {

            $sql = "SELECT acc.status,e.lang_id,e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address,ib.international_branch_id FROM " . DB_PREFIX . "employee e LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } elseif ($user_type_id == 4) {

            $sql = "SELECT acc.status,ib.lang_id,ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX . "international_branch ib LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } else {
            $sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX . "client c LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '" . (int) $user_id . "' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "' ";
        }
		$data = $this->query($sql);
		//printr($data);
		return $data->row;
	}
	
/*	public function send_otp_mail($admin_email,$otp,$toemail,$n=0,$req_id,$k=0)
	{
		
		$html=$w='';
		$addedByinfo=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		if($k==0)
		{
			if($n==1)
				$w='Regenrated';
			
			$request_data = $this->getRequest($req_id);
			   
			$subject = $w.' Sample Request Number From Swiss Pac Pvt Ltd.';  
			
			$html.="This is system generated mail.<br> This is your Sample Request Number : <b> ".$otp."</b><br> As discussed with <b>".$requinfo['name']."</b>
			        <br>Kindly forward this Sample Request number's email to ".$addedByinfo['email']." to get Tracking Number.";
			$email_temp[]=array('html'=>$html,'email'=>$admin_email);
		}
		else
		{
			$subject = 'Please get Sample Request Number From The Customer';
			
			$html.="Please follow the below details : <br>
					<b>Company Name :</b>".$otp['company_nm']."<br>
					<b>Customer Name :</b>".$otp['contact_nm']."<br>
					<b>Contact No :</b>".$otp['phone_no']."<br>
					<b>Email :</b>".$otp['email_1']."<br>";
			
		}
			
		$email_temp[]=array('html'=>$html,'email'=>$toemail);
        
		$from_email=$admin_email;
	
		
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(8); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		$path = HTTP_SERVER."template/order_template.html";
		$output = file_get_contents($path);  
		
		$search  = array('{tag:header}','{tag:details}');
		
		$message = '';
		$signature='';
		$signature = 'Thanks.';
		
		foreach($email_temp as $val)
		{
			$message = '';
			if($val['html'])
			{
				$tag_val = array(
					"{{otpDetail}}" =>$html,
					"{{ddsignature}}"	=> $signature,
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
		
			send_email($val['email'],$from_email,$subject,$message,'');
		}	
	
	}*/
	public function updateRequestOTP($reuest_id,$post,$admin_email){	
	   $sql = "UPDATE " . DB_PREFIX . "sample_request SET received_otp = '" .(int)$post['otp'] ."' WHERE request_id = '".$reuest_id ."' ";
	  // echo $sql;
	   $this->query($sql);
		
		$addedByinfo=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		
		$request_data = $this->getRequest($reuest_id);	
	
	    $country_nm = $this->query("SELECT country_name FROM `country` WHERE country_id='".$request_data['country']."' ");
		//printr($country_nm);
		$html='';
		
		$subject = 'Sample Request '.$request_data['sample_no'].' Notification by --> '.$addedByinfo['user_name'];  
		//$addedByinfo=$this->getUser('19','2');
		$html.="Please send the sample to the below customer as per the details.<br>";
		
		$html.="<p>
		        <strong>Company Name : </strong>".$request_data['company_nm']."<br>
		        <strong>Customer Name : </strong>".$request_data['contact_nm']."<br>
				<strong>Address : </strong>".$request_data['address']."<br>
				<strong>City : </strong>".$request_data['city']."<br>
				<strong>Country : </strong>".$country_nm->row['country_name']."<br>
				<strong>Pin Code : </strong>".$request_data['pin_code']."<br>
				<strong>Phone No : </strong>".$request_data['phone_no']."<br><br>
				<strong>Remark : </strong>".$request_data['remark']."<br><br></p>";
		$u = explode("=", $request_data['requester']);
		$req_by=$this->getUser($u[1],$u[0]);
		
	    $addedByinfo=$this->getUser('91','2');
		//printr($addedByinfo);die;	
		
		if(isset($req_by['employee_id']) && $req_by['employee_id']=='34')
		{
		    $email_temp[]=array('html'=>$html,'email'=>$req_by['email']);
		    $pooja_email=$this->getUser('145','2');
		    $email_temp[]=array('html'=>$html,'email'=>$pooja_email['email']);
		}
		else
		    $email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);

		$form_email=$admin_email;
		
		
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(9); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		$path = HTTP_SERVER."template/order_template.html";
		$output = file_get_contents($path);  
		
		$search  = array('{tag:header}','{tag:details}');
		
		$message = '';
		$signature='';
		$signature = 'Thanks.';
		
		foreach($email_temp as $val)
		{
			$message = '';
			if($val['html'])
			{
				$tag_val = array(
					"{{sampleDetail}}" =>$html,
					"{{ddsignature}}"	=> $signature,
				);
				//printr($tag_val);
				if(!empty($tag_val))
				{
					$desc = $temp_desc;
					$desc .= $req_by['user_name']; 
					foreach($tag_val as $k=>$v)
					{
						@$desc = str_replace(trim($k),trim($v),trim($desc));
					} 
				}
				$replace = array($subject,$desc);
				$message = str_replace($search, $replace, $output);
			}
			//printr($message);die;
			send_email($val['email'],$admin_email,$subject,$message,'');
		}
	}
	
	public function getActiveProduct($reuest_id,$post){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE is_delete= 0 AND status=0 ";
		$data = $this->query($sql);
	}
	public function savedispatch($courier_name,$aws_no,$sent_date,$request_id,$no,$admin_email){
		//printr(date("Y-m-d", strtotime($sent_date)));die;
        
        if($sent_date == '0000-00-00')
            $sent_date = date("Y-m-d");
        
		$sql = "UPDATE " . DB_PREFIX . "sample_request SET sent_date = '" .date("Y-m-d", strtotime($sent_date)) ."',courier_name='".$courier_name."',aws_no='".$aws_no."' WHERE request_id = '".$request_id ."' ";
		//echo $sql;die;
		$data = $this->query($sql);
		
		$addedByinfo_user=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		
		$request_data = $this->getRequest($request_id);	
		
		$html='';
		if($no=='1')
		    $s = 'Updated';
		else
		    $s='';
		
		$subject = $s.' Traking Detail About Sample Request '.$request_data['sample_no'];  
		
		$html.="<b>Sample Requested Date : </b>".$request_data['date_added']."<br>
				<b>Sample Dispatch  Date : </b>".$request_data['sent_date']."<br>
				<b>Company Name   : </b>".$request_data['company_nm']."<br>
				<b>Courier Name   : </b>".$request_data['courier_name']."<br>
				<b>AWS No        : </b>".$request_data['aws_no']."<br>";		
		
		//$addedByinfo=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$u = explode("=", $request_data['requester']);	
		$addedByinfo=$this->getUser($u[1],$u[0]);
		//printr($addedByinfo);die;	
		$email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);
        $email_temp[]=array('html'=>$html,'email'=>$admin_email);
		
		$from=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$form_email=$from['email'];
		
		
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(9); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		$path = HTTP_SERVER."template/order_template.html";
		$output = file_get_contents($path);  
		
		$search  = array('{tag:header}','{tag:details}');
		
		$message = '';
		$signature='';
		$signature = 'Thanks.';
		
		foreach($email_temp as $val)
		{
			$message = '';
			if($val['html'])
			{
				$tag_val = array(
					"{{sampleDetail}}" =>$html,
					"{{ddsignature}}"	=> $signature,
				);
				//printr($tag_val);
				if(!empty($tag_val))
				{
					$desc = $temp_desc;
					$desc .= $addedByinfo_user['user_name'];
					foreach($tag_val as $k=>$v)
					{
						@$desc = str_replace(trim($k),trim($v),trim($desc));
					} 
				}
				$replace = array($subject,$desc);
				$message = str_replace($search, $replace, $output);
			}
		//	printr($message);die;
			send_email($val['email'],$form_email,$subject,$message,'');
		}
		
	}
	 public function getUserList() {

        $sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX . "account_master ORDER BY user_name ASC";

        $data = $this->query($sql);

        //printr($data);die;

        return $data->rows;
    }
	public function getCustomerDetail($customer_name)
	{
		
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];

		if($user_type_id == 2){

			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");

			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

			$set_user_id = $parentdata->row['user_id'];

			$set_user_type_id = $parentdata->row['user_type_id'];
			
			$str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			
			$sql = "SELECT aa.address_book_id,aa.company_name,aa.contact_name,aa.vat_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address,cs.city,cs.state,cs.country,cs.pincode,cs.phone_no FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.company_name LIKE '%".strtolower($customer_name)."%' AND ((aa.user_id='".$set_user_id ."' AND aa.user_type_id='".$set_user_type_id ."') $str  )  GROUP BY aa.address_book_id";
			//echo $sql;
		}
		else if($user_type_id == 4)
		{

			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);

			$set_user_id = $user_id;

			$set_user_type_id = $user_type_id;
				$str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
				
			$sql = "SELECT aa.address_book_id,aa.company_name,aa.vat_no,aa.contact_name, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address,cs.city,cs.state,cs.country,cs.pincode,cs.phone_no FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.company_name LIKE '%".strtolower($customer_name)."%' AND ((aa.user_id='".$set_user_id ."' AND aa.user_type_id='".$set_user_type_id ."') $str  )  GROUP BY aa.address_book_id";

		}

		else

		{

			$set_user_id = $user_id;

			$set_user_type_id  = $user_type_id;
			
			$sql = "SELECT aa.address_book_id,aa.company_name,aa.vat_no,aa.contact_name, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address,cs.city,cs.state,cs.country,cs.pincode,cs.phone_no FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.company_name LIKE '%".strtolower($customer_name)."%' GROUP BY aa.address_book_id";
		}
        //echo $sql;
		$data = $this->query($sql);
		//printr($data);

		if($data->num_rows){

			return $data->rows;

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
	 public function getUserEmployeeIds($user_type_id, $user_id) {

        $sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '" . (int) $user_type_id . "' 

			AND user_id = '" . (int) $user_id . "'";

        $data = $this->query($sql);

        if ($data->num_rows) {

            return $data->row['ids'];
        } else {

            return false;
        }
    }
    
    public function updateStatus($status,$data)
	{
	   //printr($status);
		if($status == 2){

			$sql = "UPDATE `" . DB_PREFIX . "sample_request` SET is_delete = '1', date_modify = NOW() WHERE request_id IN (" .implode(",",$data). ")";
            //echo $sql;
			$this->query($sql);

			}
	}
	public function generateSampleNumber(){

         	$year = date("Y", time()); 
           	$month = date("m", time());
            $sql_year=" AND ( MONTH(date_added) > '3' AND YEAR(date_added) ='".$year."')OR ( MONTH(date_added) < '4' AND YEAR(date_added) ='".($year+1)."')";
			$data = $this->query("SELECT MAX(sample_no) as sample_no  FROM sample_request WHERE is_delete=0  AND status='0' $sql_year ORDER BY request_id DESC limit 1");
            
			$count = $data->row['sample_no'];
			 
            $no=(int) filter_var($count, FILTER_SANITIZE_NUMBER_INT);
            $n=$no+1;
			$strpad = str_pad($n,6,'0',STR_PAD_LEFT);

			return $strpad;

		}
		public function update_otp($request_id,$code)
    	{
    	
    
    			$sql = "UPDATE `" . DB_PREFIX . "sample_request` SET sended_otp = '".$code."' WHERE request_id = '".$request_id."'";
    
    			$this->query($sql);
    
    		
    	}
		public function getUserListIndia()
		{
			$userEmployee = $this->getUserEmployeeIds('4','6');
		
	    	$sql = "SELECT e.employee_id,CONCAT(e.first_name,' ',e.last_name) as name,acc.email FROM " . DB_PREFIX . "employee e , account_master acc WHERE acc.user_name=e.user_name AND acc.user_type_id = '2' AND acc.user_id IN (" .$userEmployee . ") AND e.user_type = 20";
		    //printr($sql);
			$data=$this->query($sql);
			
			if ($data->num_rows) {
				return $data->rows;
			} else {
				return false;
			}
		}
		public function getQuoRequest($post,$user_id,$file,$type='')
    	{
    	    $slash=$b=$end_b='';
    	    $conversion=array(array('company_name'=>'','customer_name'=>'','email'=>'','phone_no'=>'','address'=>'','fill_in_pouch'=>'','weight'=>'','no_of_bags'=>'','bussiness_card'=>''));
    	    if($type!='')
    	    {
        	    $county = $this->getUser($user_id,$type);

                $conversion = $this->getConversion($county['international_branch_id'],$county['lang_id'],$user_id);
                 $b='<b style="font-size: medium;">';$end_b='</b>';
                 if($county['international_branch_id']=='6' || $county['international_branch_id']=='35')
                    $b=$end_b='';
                    $slash=' / ';
    	    }   
    	    $file_name='';
    		if(!empty($file['die_line']['name']) && $file['die_line']['name']!='')
    		{
    		    $upload_path = DIR_UPLOAD.'admin/client_busi_card/';
    		    $file_name = $file['die_line']['name'];
    			$filetemp = $file['die_line']['tmp_name'];	
    			$upload_file_path = $upload_path.$file_name;
    		    move_uploaded_file($filetemp,$upload_file_path);
    		    $attachments[]= DIR_UPLOAD.'admin/client_busi_card/'.$file['die_line']['name'].'';
    		}
    		//if($user_id!='56')
    		$data = $this->query("INSERT INTO `" . DB_PREFIX . "quotation_request` SET company_name='".$post['company_name']."',contact_name='".$post['contact_name']."',email='".$post['email']."',phone_no='".$post['phone_no']."',address='".addslashes($post['address'])."',filling='".$post['filling']."',weight='".$post['weight']."',num_bag='".$post['num_bag']."',card_name='".$file_name."',sales_emp_id='".$user_id."',sales_emp_type_id='2', date_added = NOW()");
    	    
    	    $subject = 'Customer Inquiry - '.$post['company_name'];
			$addedByinfo=$this->getUser($user_id,'2');
    	    $post['address'] = str_replace('\r',' ',$post['address']);
    	    $html ='';
    	    	
		    $html.='<style>
                        .p_cls {
                            margin-left: 30px ;
                        }
                    </style>';
    	    $html.='<fieldset>
    	                <legend><p><b>Hi '.$addedByinfo['name'].',</b></p></legend>';
        	    $html.="<table class='p_cls table b-t text-small table-hover'>
        	                <tr>
        	                    <th style='text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>1) Customer Company Name".$slash." ".$b." ".hex2bin($conversion[0]['company_name'])." ".$end_b." : </th><td style='border-bottom: 1px dotted black;'>".$post['company_name']."</td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>2) Customer Name".$slash." ".$b." ".hex2bin($conversion[0]['customer_name'])." ".$end_b." : </th><td style='border-bottom: 1px dotted black;'>".$post['contact_name']."</td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>3) Email / Whatsapp No.".$slash." ".$b." ".hex2bin($conversion[0]['email'])." ".$end_b." : </th><td style='border-bottom: 1px dotted black;'>".$post['email']."</td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>4) Phone No. / Mobile No.".$slash." ".$b." ".hex2bin($conversion[0]['phone_no'])." ".$end_b." : </th><td style='border-bottom: 1px dotted black;'>".$post['phone_no']."</td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>5) Address With Pin code".$slash." ".$b." ".hex2bin($conversion[0]['address'])." ".$end_b." : </th><td style='border-bottom: 1px dotted black;'>".str_replace('\n','<br>',$post['address'])."</td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>6) What whould you like to fill in pouch?".$slash." ".$b." ".hex2bin($conversion[0]['fill_in_pouch'])." ".$end_b." : </th><td style='border-bottom: 1px dotted black;'>".$post['filling']."</td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>7) How much amount of weight you want to fill in pouch?".$slash." ".$b." ".hex2bin($conversion[0]['weight'])." ".$end_b." : </th><td style='border-bottom: 1px dotted black;'>".$post['weight']."</td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>8) Number of bags required".$slash." ".$b." ".hex2bin($conversion[0]['no_of_bags'])." ".$end_b." : </th><td style='border-bottom: 1px dotted black;'>".$post['num_bag']."</td>
        	                </tr>
        	                <tr><th></th><td></td></tr>
        	                <tr>
        	                    <th style='text-align: left;vertical-align: top;border-bottom: 1px dotted black;'>9) Upload Visiting / Business Card".$slash." ".$b." ".hex2bin($conversion[0]['bussiness_card'])." ".$end_b." : </th><td style='border-bottom: 1px dotted black;'></td>
        	                </tr>
        	            </table>
    	           </fieldset>";
           
			$email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);
            if($user_id!='44' || $user_id!='209')
                $email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL_QUO);
            /*else
                printr($email_temp);//die;*/
		    $from_email=$post['email'];
    	    $obj_email = new email_template();
    	    
    		$rws_email_template = $obj_email->get_email_template(11); 
    		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
    			
    		$path = HTTP_SERVER."template/order_template.html";
    		$output = file_get_contents($path);  
    		
    		$search  = array('{tag:header}','{tag:details}');
    		
    		$message = '';
    		$signature = 'Thanks.';
    		/*if($user_id=='92')
    		    printr($email_temp);die;*/
    		foreach($email_temp as $val)
    		{
    			$message = '';
    			if($val['html'])
    			{
    				$tag_val = array(
    					"{{inquiryDetail}}" =>$html,
    					"{{ddsignature}}"	=> $signature,
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
    		   
    		    if($file['die_line']['name']=='')
    			    send_email($val['email'],$from_email,$subject,$message,'');
    			else
    			   send_email($val['email'],$from_email,$subject,$message,$attachments,'',1); 
    		}
    	    
    	}

    	public function viewsampledata($request_id){
    	    
    	    
    	    $data = $this->getRequest($request_id);
    	   /* if($_SESSION['ADMIN_LOGIN_SWISS']==1 && $_SESSION['LOGIN_USER_TYPE']==1)
    		{
    		    //$data1=$this->query("SELECT * FROM sample_request WHERE request_id='1179'");
    		    //printr($data);
    		    print_r(str_replace('\r\n',' <br>',$data['address']));
    		}*/
    	    $fixdata = $this->getFixmaster();  
    	     $currency=$this->getCurrencyName($data['currency_id']);
    	  $img = ' <img src="'. HTTP_SERVER.'admin/controller/sample_request/invoice_logo.png" width="70%">';
    	 $igst=$cgst=$sgst=0;
    	  $bold=$bold1='';
    	$html='';                       
  	
				
			$html.='<div class="panel-body" id="print_div" style="padding-top: 0px;width:754px font-size=14Px"><div class="panel-body"">  	';
      	$fixdata = $this->getFixmaster(); 
      	   $html.='<div style=" width:754px">'.$img.'</div>'; 
      $html.='<table style="cellpadding:0px;cellspacing:0px;font-size: 14px;padding-top: 12px;width:754px;" border="1" cellpadding="0" cellspacing="0">';
	   	
	  	    
			
		    	 $html.='<tbody><tr><td colspan="8"><h4><b><center>TAX INVOICE</center></b></h4></td></tr>';
				  $html.='<tr>
      	       	            	<td colspan="5" width="60%" style="vertical-align: top;"><div>'.nl2br($fixdata['exporter']).'</div></td>
               	    	        <td  colspan="3" width="40%"><b>INVOICE NO. : </b>'.$data['sample_no'].'&nbsp;&nbsp;&nbsp;&nbsp;<b>DATE :</b> '.date("d-m-Y",strtotime($data['sent_date'])).'</td>
        			    </tr>';    
			 

	
             $html .= ' <tr>
           			 <td colspan="8" style="vertical-align: top;"><strong>BILLING ADDRESS:</strong>
                         <br>
           		    	 <strong>'.stripslashes(ucwords($data['company_nm'])).'<br>'.$data['contact_nm'].'</strong><br>'.nl2br(str_replace('\r\n',' <br>',$data['address'])).' '.$data['email_1'].'<br>'.$data['phone_no'].'
           			 </td>
          	        </tr>';
		$html.='<tr style="vertical-align: top;">';  
			$html.='<td  colspan="5"  width="auto"><center><b>DESCRIPTION OF GOODS</b></center></td>
        		    <td colspan="1" ><center><b>NO OF PACKAGES</b></center></td> 
        		    <td colspan="1" ><center><b>IDENTIFICATION MARKS</b></center></td>
        	    	<td colspan="1" ><center><b>AMOUNT <br />INR <span style="font-family: DejaVu Sans;">&#x20B9;</span></b></center></td>';
    	$html.='</tr>';
		$html.='<tr style="vertical-align: top; height:90Px;">';  
           	    	$html.='<td  colspan="5" class="no_border"  width="auto" ><b>PRINTED OR UNPRINTED FLEXIBLE PACKAGING MATERIAL OF POUCHES <br>HSN NO.39232990</b></td>'; 
           	    	$html.='<td colspan="1"  >'.$data['no_of_package'].'</td>'; 
			    	$html.='<td colspan="1"  >'.$data['identification_marks'].'</td>';
			     	$html.='<td colspan="1"  >'.$data['amount'].' <br><br></td>';
		$html.='</tr>';
				 
    	$html.='<tr style="vertical-align: top;">';  

              	$html.='<td colspan="5" class="no_border"  ><b>'.$data['pouch_mode'].' NO COMMERCIAL VALUE INVOLVED IN THIS SHIPMENT</b><br></td><td colspan="1" ><b> Sub Total </b></td><td colspan="1" ></td>'; 
		    	$html.='<td  colspan="1" >'.$data['amount'].'</td>';
    	 $html.='</tr>';
    	 if($data['taxation']=='With in Gujarat'){
        	 $html.='<tr style="vertical-align: top;">';  
                        $cgst=($data['amount']*$data['cgst']/100);
                  	$html.='<td colspan="5" class="no_border"  ></td><td colspan="1" ><b>Cgst  </b></td><td colspan="1" ><b> '.$data['cgst'].' %</b></td>'; 
    		    	$html.='<td  colspan="1" >'.$cgst.'</td>';
        	 $html.='</tr>';
        	 $html.='<tr style="vertical-align: top;">';  
                 $sgst=($data['amount']*$data['sgst']/100);
                  	$html.='<td colspan="5" class="no_border"  ></td><td colspan="1" ><b>Sgst  </b><td colspan="1" ><b> '.$data['sgst'].' %</b></td>'; 
    		    	$html.='<td  colspan="1" >'.$sgst.'</td>';
        	 $html.='</tr>';		   
    	 }else if($data['taxation']=='Out Of Gujarat'){
    	     
    	     	 $html.='<tr style="vertical-align: top;">';  
                 $igst=($data['amount']*$data['igst']/100);
                  	$html.='<td colspan="5" class="no_border"  ></td><td colspan="1" ><b>Igst  </b><td colspan="1" ><b> '.$data['igst'].' %</b></td>'; 
    		    	$html.='<td  colspan="1" >'.$igst.'</td>';
        	 $html.='</tr>';
    	 }	
    	  $final_amt=$data['amount']+$igst+$cgst+$sgst;
    	$html.='<tr style="vertical-align: top;">';  

              	$html.='<td colspan="5" class="no_border"  ></td><td colspan="1" ><b> Total </b></td><td colspan="1" ><b>  </b></td>'; 
		    	$html.='<td  colspan="1" >'.$final_amt.'</td>';
    	 $html.='</tr>';
    	 $html.='<tr style="vertical-align: top;">';  
                   
              	$html.='<td colspan="8" ><b>Amount In Words  : <b>'.$number=$this->convert_number_new(round($final_amt)).' Only.</b></td>'; 
		    
    	 $html.='</tr>';		
						
         $html.='<tr>';
          $html.='<td colspan="5" rowspan="1" valign="top"><p><strong><u>Declaration</u></strong></p>
           		 	'. $fixdata['declaration'].' <br /></td>';
           $html.=' <td  colspan="3" rowspan="1" valign="top"><strong>Signature</strong>
						<br><br>
						<p><strong>For : SWISS PAC PVT . LTD.</strong></p>
             			
            			<br><br>
						<p><strong>Authorized Person</strong></p>
					</td>
         		</tr>';
 	$html.='<tbody></table>
	  </div></div>';
	  
	  
//	printr($html);
	  
		return $html;
    	}
	public function viewsampledataOther($request_id){
    	    $data = $this->getRequest($request_id);
    	    $fixdata = $this->getFixmaster();  
    	     $currency=$this->getCurrencyName($data['currency_id']);
        	  $img = ' <img src="'. HTTP_SERVER.'admin/controller/sample_request/invoice_logo.png" width="70%">';
        	 $igst=$cgst=$sgst=0;
        	  $bold=$bold1='';
        	$html='';                       
  	
				
			$html.='<div class="panel-body" id="print_div" style="padding-top: 0px;width:754px font-size=14Px"><div class="panel-body"">  	';
      	$fixdata = $this->getFixmaster(); 
      	   $html.='<div style=" width:754px">'.$img.'</div>'; 
      $html.='<table style="cellpadding:0px;cellspacing:0px;font-size: 14px;padding-top: 12px;width:754px;" border="1" cellpadding="0" cellspacing="0">';
	   	
	  	   // printr($data);
			
		    	 $html.='<tbody><tr><td colspan="8"><h4><b><center>SAMPLE DETAILS</center></b></h4></td></tr>';
				  $html.='<tr>
      	       	            	<td colspan="5" width="60%" style="vertical-align: top;"><div>'.nl2br($fixdata['exporter']).'</div></td>
               	    	        <td  colspan="3" width="40%"><b>Sample No. : </b>'.$data['sample_no'].'&nbsp;&nbsp;&nbsp;&nbsp;<br><b>Traking No :</b> '.$data['aws_no'].'<br><b>DATE :</b> '.date("d-m-Y",strtotime($data['sent_date'])).'</td>
        			        </tr>';    
			 

	
             $html .= '<tr>
           			 <td colspan="8" style="vertical-align: top;"><strong>ADDRESS:</strong>
                         <br>
           		    	 <strong>'.stripslashes(ucwords($data['company_nm'])).'<br>'.$data['contact_nm'].'</strong><br>'.nl2br(str_replace('\r\n',' <br>',$data['address'])).' '.$data['email_1'].'<br>'.$data['phone_no'].'
           			 </td>
          	        </tr>';
		$html.='<tr style="vertical-align: top;">';  
			$html.='<td  colspan="8"  width="auto"><center><b>DESCRIPTION OF GOODS</b></center></td>';
        	//	  printr($data);
    	$html.='</tr>';
		$html.='<tr style="vertical-align: top; height:90Px;">';  
           	    	$html.='<td  colspan="8" class="no_border"  width="auto" ><b>'.nl2br(str_replace('\r\n',' <br>',$data['remark'])).'</b></td>'; 
		$html.='</tr>';
				 
    
      
 	$html.='<tbody></table>
	  </div></div>';
	   
	  
//	printr($html);
	  
		return $html;
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
	public function getCurrency()
		{
			$sql = "SELECT *  FROM " . DB_PREFIX . "currency  WHERE is_delete = '0' ";
			$data = $this->query($sql);
			$result = $data->rows;
			return $result;	

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
function convert_number_new($number) 

		{ 

    		if (($number < 0) || ($number > 999999999)) 

    		{ 

    			throw new Exception("Number is out of range");

    		} 

		    $Gn = floor($number / 100000);  /* Lacs (giga) */ 

    		$number -= $Gn * 100000; 

    		$kn = floor($number / 1000);     /* Thousands (kilo) */ 

    		$number -= $kn * 1000; 

    		$Hn = floor($number / 100);      /* Hundreds (hecto) */ 

    		$number -= $Hn * 100; 

    		$Dn = floor($number / 10);       /* Tens (deca) */ 

    		$n = $number % 10;               /* Ones */ 



    		$res = ""; 

		    if ($Gn) 

    		{ 

        		$res .= $this->convert_number_new($Gn) . " Lacs"; 

    		} 

		    if ($kn) 

    		{ 

        		$res .= (empty($res) ? "" : " ") . 

           		$this->convert_number_new($kn) . " Thousand"; 

    		} 

		    if ($Hn) 

    		{ 

        		$res .= (empty($res) ? "" : " ") . 

            	$this->convert_number_new($Hn) . " Hundred"; 

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
		
	public function getConversion($branch_id,$lang_id,$emp_id=0)
	{
		$lang = explode(',',$lang_id);
		$array_data =array();
		/*mysql_query ("set character_set_results='utf8'"); */
		$str='';
		if($emp_id!=0)
		    $str = ' AND emp_id='.$emp_id;
		foreach($lang as $langs)
		{
		   $sql = "SELECT * FROM `" . DB_PREFIX . "cust_inquiry_page` WHERE user_id = '".$branch_id."' AND lang_id='".$langs."' $str";
		   $data = $this->query($sql);
		   if($data->num_rows)
		        $array_data[]=$data->row;
		   else
		   {
		       $sql = "SELECT * FROM `" . DB_PREFIX . "cust_inquiry_page` WHERE user_id = '".$branch_id."' AND lang_id='".$langs."'";
    		   $data = $this->query($sql);
    		   $array_data[]=$data->row;
		   }
		}

		if($array_data){
			return $array_data;
		}else{
			return false;
		}
	}
    public function get_remark($address_book_id){
        $sql="SELECT * FROM `sample_request` WHERE `address_book_id` = '".$address_book_id."' ORDER BY `request_id` ASC";
        $data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
    }
} 

?>