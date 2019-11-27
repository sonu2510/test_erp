<?php
class followup_calendar extends dbclass{
	
	public function addIndustry($data){
		$this->query("INSERT INTO " . DB_PREFIX . "enquiry_industry SET industry = '" .$data['name']. "', status = '" .$data['status']. "', date_added = NOW()");
		
		return $this->getLastId();
		
	}
	
	public function updateIndustry($industry_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "enquiry_industry` SET industry = '" .$data['name']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE enquiry_industry_id = '" .(int)$industry_id. "'";
		//layer = '".serialize($data['layer'])."',
		$this->query($sql);
		
	}
	
	public function getTotalIndustry($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "enquiry_industry WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['industry'])){
				$sql .= " AND industry LIKE '%".$filter_data['industry']."%' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}		
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getIndustrys($data,$filter_data=array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "enquiry_industry WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['industry'])){
				$sql .= " AND industry LIKE '%".$filter_data['industry']."%' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}		
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY industry";	
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
		
	public function getIndustry($industry_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "enquiry_industry` WHERE enquiry_industry_id = '" .(int)$industry_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	
	
	public function updateIndustryStatus($id,$status){
		$this->query("UPDATE " . DB_PREFIX . "enquiry_industry SET status = '" .(int)$status. "', date_modify = NOW() WHERE enquiry_industry_id = '".$id."' ");
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "product_material` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_material_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "product_material` SET is_delete = '1', date_modify = NOW() WHERE product_material_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}

	public  function getEvents($var1){

		//$var12 = "2017-06-01";
		//printr($var1);
		//$sql = "SELECT  ef.enquiry_note,ef.followup_date,e.enquiry_number,ef.enquiry_id FROM  enquiry as e,enquiry_followup as ef WHERE e.enquiry_id=ef.enquiry_id GROUP BY ef.enquiry_id limit 1 ";
		//echo $sql; die;
		$sql = "SELECT * FROM `enquiry_followup` WHERE followup_date = '".$var1."'  limit 1";
		//$sql = "SELECTef.followup_date,ef.enquiry_note FROM enquiry_followup as ef,enquiry as e WHERE e.enquiry_id=ef.enquiry_id limit 1";
		//echo $sql; die;
		$data = $this->query($sql);
		//printr($data);
		//return $data->row['total'];
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}

	}


	public function view_Event_detail(){
		//$sql = "SELECT f.followup_date,f.enquiry_note,e.enquiry_id,f.enquiry_followup_id FROM `enquiry_followup` as f , enquiry as e WHERE 	e.enquiry_id =f.enquiry_id AND e.is_delete ='0'";
		//$sql = "SELECTef.followup_date,ef.enquiry_note FROM enquiry_followup as ef,enquiry as e WHERE e.enquiry_id=ef.enquiry_id limit 1";
//echo $sql;die;
		$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
		
		if($user_type_id==1 && $user_id==1)

		{

		    $sql="select e.*,am.user_name from enquiry_followup as e,account_master as am  WHERE e.user_id=am.user_id AND e.user_type_id=am.user_type_id AND e.is_delete = 0";

		}

		else

		{

			if($user_type_id == 2){

    			//$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
    
    			//$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
    
    			//$set_user_id = $parentdata->row['user_id'];
    
    			//$set_user_type_id = $parentdata->row['user_type_id'];
				$set_user_id  = $user_id;
				$set_user_type_id = $user_type_id;

    		}else{
    
    			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
    
    			$set_user_id = $user_id;
    
    			$set_user_type_id = $user_type_id;
    
    		}

    		$str = '';
    
    		if(isset($userEmployee)){
    
    			$str = ' OR ( user_id IN ('.$userEmployee.') AND  user_type_id = 2 )';
    
    		}
		    
		     $sql="select e.*,am.user_name from enquiry_followup as e,account_master as am  WHERE e.user_id=am.user_id AND e.user_type_id=am.user_type_id AND e.is_delete = 0 AND (e.user_id='".$set_user_id."' AND e.user_type_id='".$set_user_type_id."' $str)";
		}
		
		
		$data = $this->query($sql);
		//printr($data);
		//return $data->row['total'];
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getUserEmployeeIds($user_type_id,$user_id){

			$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' 

			AND user_id = '".(int)$user_id."'";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row['ids'];

			}else{

				return false;

			}

		}
	public  function getEventsAll($var1){

		//$var12 = "2017-06-01";
		//printr($var1);
		$sql = "SELECT * FROM `enquiry_followup` WHERE followup_date = '".$var1."' ";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}

	}
		public  function getEnquiry(){
		
		$sql = "SELECT * FROM " . DB_PREFIX . "enquiry WHERE is_delete = 0";
		
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
		}
	
	public function addFollowup($data){
		$this->query("INSERT INTO " . DB_PREFIX . "enquiry_followup SET followup_date = '" .$data['date']."', enquiry_note = '" .$data['remark']. "',reminder='".$data['reminder']."'");
		return $this->getLastId();
	}
	
		public function updateFollowup($data,$enquiry_followup_id){
		$sql = "UPDATE enquiry_followup SET followup_date = '" .$data['date']."', enquiry_note = '" .$data['remark']. "',reminder='".$data['reminder']."'WHERE enquiry_followup_id = '" .$enquiry_followup_id. "'";
		
		$this->query($sql);		
	}
	
	
	public function insert_data($title,$date,$time){
		//printr($start);die;
		//$data=print_r($start);
		//$cr_date=CONVERT(VARCHAR(10),$date,110);
	//followup_date = '" .$date. "', ,$date
		$getdate=
		$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
			$this->query("INSERT INTO " . DB_PREFIX . "enquiry_followup SET   followup_date = '" .$date. "',enquiry_note = '" .$title. "',user_id = '".$user_id."',user_type_id = '".$user_type_id."',followup_time = '".$time."',date_added = NOW() ");
			}
	
		public function update_data($newtitle,$followup_id){
		$sql = "UPDATE enquiry_followup SET enquiry_note = '" .$newtitle."',date_modify = NOW() WHERE enquiry_followup_id = '" .$followup_id. "'";
		$this->query($sql);}
	
		public function delete_data($followup_id){
		$sql = "UPDATE enquiry_followup SET is_delete = 1 ,date_modify = NOW() WHERE enquiry_followup_id = '" .$followup_id. "' ";
		$this->query($sql);	
		}
	
/*	public function get_currentdate_calendar_details(){
		$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id=$_SESSION['LOGIN_USER_TYPE'];

		$sql="select * from enquiry_followup WHERE followup_Date=CURDATE() AND user_id='".$user_id."' AND user_type_id='".$user_type_id."' AND is_delete=0 ";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}


	}
	
	
	public function get_currentdate_Task_details(){
		$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id=$_SESSION['LOGIN_USER_TYPE'];

		$sql="select * from task_management WHERE due_date=CURDATE() AND assign_to_user_id='".$user_id."' AND assign_to_user_type_id='".$user_type_id."' AND is_delete=0 ";
		$data = $this->query($sql);
		//print_r($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}


	}*/
	
	public function send_followup_mail()
	{
		//printr('hi');die;
		
		$sql="SELECT e.*,enq.enquiry_number,enq.enquiry_for,enq.company_name,enq.email as cust_email,ac.email,enq.date_added as rec_date,enq.number_of_pouch_req,enq.filling,enq.weight,enq.material_combination FROM  enquiry_followup as e , enquiry as enq,account_master as ac WHERE e.followup_date=CURDATE() AND e.enquiry_id <> 0  AND e.user_id=enq.user_id AND e.user_type_id=enq.user_type_id AND e.enquiry_id=enq.enquiry_id AND e.user_id=ac.user_id AND e.user_type_id=ac.user_type_id AND enq.email!='' GROUP BY e.enquiry_followup_id DESC  ";
		$data = $this->query($sql);
	//	printr($data);die;
	   if($data->num_rows){
        	foreach($data->rows as $country){
                $email_id=$this->getUser($country['user_id'],$country['user_type_id']);
        		
        		$html='';
        		$subject = 'Followup Enquiry For '.strtoupper($country['company_name']);  
        		$html.="Today's Followup Enquiry For ".$country['enquiry_for']." As Follows,<br><br><br> Enquiry Received Date: ".dateformat("4",$country['rec_date'])." <br> Followup Date: ".dateformat("4",$country['followup_date'])." <br> Enquiry Number: ".$country['enquiry_number']." <br>Comapny Name: ".strtoupper($country['company_name'])." <br>Customer Email Id: ".$country['cust_email']." <br>Number of bags required: ".$country['number_of_pouch_req']." <br>Product To Fill In Inside: ".$country['filling']." <br>Weight To Fill In Each Bag: ".$country['weight']." <br>Material Combination: ".$country['material_combination'];
        		//if($country['enquiry_note']!='')
        		$html.=" <br> Inquiry specs: ".$country['enquiry_note'];
        		
        		/*$html.="<p>Hello,</p><br>
        		          <p>I hope you have been well!
        		          <p>Just touching base to see if you have received the samples.</p>
        		          <p>Where the samples suitable for your product?</p>
        		          <p>I will wait to hear back. Please let me know if anything else I can help you with.</p>";*/
        			
        		$email_temp=array('html'=>$html,'email'=>$country['email']);
        		
        		if($country['user_type_id']!='2')
        		    $cc = ADMIN_EMAIL_QUO;
        		else
        		{
        		    $admin_email=$this->getUser($email_id['user_id'],'4');
        		    $cc =$admin_email['email'].','.ADMIN_EMAIL_QUO;
        		}
        		//$bcc= 'erp@swisspac.net';
        		$obj_email = new email_template();
        		$rws_email_template = $obj_email->get_email_template(10); 
        		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
        			
        		$path = HTTP_SERVER."template/order_template.html";
        		$output = file_get_contents($path);  
        		
        		$search  = array('{tag:header}','{tag:details}');
        		$signature = '<br> Thank you. <br> Kind Regards, <br> SWISS PAC PVT LTD';//.$email_id['first_name'].' '.$email_id['last_name']
        		$message = '';
			    
			    $tag_val = array(
					"{{Enquiry Details}}" =>$html,
					"{{signature}}"	=> $signature,
				);
				
				$desc = $temp_desc;
				foreach($tag_val as $k=>$v)
				{
					@$desc = str_replace(trim($k),trim($v),trim($desc));
				} 
				
				$replace = array($subject,$desc);
				$message = str_replace($search, $replace, $output);
			    send_email_test($email_temp['email'],ADMIN_EMAIL_QUO,$subject,$message,'','','','',$cc);
		    }
		}
	}
	public function getUser($user_id,$user_type_id)
    {	
        if($user_type_id == 1){
            $sql = "SELECT u.user_id,ib.company_address,ib.company_name,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email, acc.commission FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
        }elseif($user_type_id == 2){
            $sql = "SELECT ib.color_plate_price,ib.company_name,ib.foil_plate_price,e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address, acc.commission FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
        }elseif($user_type_id == 4){
            $sql = "SELECT ib.user_name,ib.company_name,ib.color_plate_price,ib.foil_plate_price,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name,ib.vat_no, ib.international_branch_id as user_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email, acc.email1, acc.commission FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
        }else{
            $sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
        }
        $data = $this->query($sql);
        return $data->row;
    }
}

?>