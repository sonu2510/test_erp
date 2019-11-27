<?php
class exhibition extends dbclass{
	
	public function addExhibition($data){
			
		
		$query="INSERT INTO ".DB_PREFIX."exhibition_details SET exhibition_details='".$data['exhibition_details']."', exhibition_name='" .$data['exhibition_name']."',duration_from='".$data['f_date']."',duration_to ='".$data['t_date']."',city='".$data['city']."',country='".(int)$data['country_id'] ."' ,person_name_1 = '".$data['person_name_1']."',person_name_2 = '".$data['person_name_2']."',person_name_3 = '".$data['person_name_3']."',email_1 = '".$data['email_1']."',email_2 = '".$data['email_2']."',email_3 = '".$data['email_3']."',date_added = NOW() ,date_modify = NOW(),is_delete = '0', user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "',user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',status ='1'";
				 $this->query($query);
			$exhibition_id = $this->getLastId();
		}
		 
	public function updateExhibition($exhibition_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "exhibition_details` SET exhibition_details='".$data['exhibition_details']."', exhibition_name = '".$data['exhibition_name']."', duration_from = '".$data['f_date']."',duration_to = '".$data['t_date']."', city = '".$data['city']."',country='".(int)$data['country_id'] ."',person_name_1 = '".$data['person_name_1']."',person_name_2 = '".$data['person_name_2']."',person_name_3 = '".$data['person_name_3']."',email_1 = '".$data['email_1']."',email_2 = '".$data['email_2']."',email_3 = '".$data['email_3']."', date_modify = NOW(),is_delete = '0', user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "',user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',status ='1' WHERE exhibition_id = '" .(int)$exhibition_id. "'";
	
		$data=$this->query($sql);
		
		
		}
		
	
	public function getTotalExhibition($filter_data = array()){
		$sql = "SELECT COUNT(*) as total FROM exhibition_details  as e , country  as c  WHERE e.is_delete = '0' AND c.country_id = e.country" ;
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['name'])){
				$sql .= " AND e.exhibition_name LIKE '%".$filter_data['name']."%' ";		
			}
			if(!empty($filter_data['country'])){
				$sql .= " AND e.country = '". $filter_data['country']. "' ";	
				  
			}
			if($filter_data['status'] != ''){
				$sql .= " AND e.status = '".$filter_data['status']."' ";
			}
			
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}

	
	public function getExhibition_detail($exhibition_id){
	
		$sql ="SELECT *  FROM exhibition_details   WHERE exhibition_id = '".$exhibition_id."'" ;
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->row;	
		}else{
			return false;	
		}
	}

	
	  public function getUser($user_id, $user_type_id) 
	  { //echo $user_type_id;
        if ($user_type_id == 1) {

            $sql = "SELECT ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX . "user u LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";

            //echo $sql;
        } elseif ($user_type_id == 2) {

            $sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address FROM " . DB_PREFIX . "employee e LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } elseif ($user_type_id == 4) {

            $sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX . "international_branch ib LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '" . (int) $user_id . "' AND ad.address_type_id = '0' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "'";
        } else {



            $sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX . "client c LEFT JOIN " . DB_PREFIX . "address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '" . (int) $user_id . "') LEFT JOIN " . DB_PREFIX . "country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX . "account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '" . (int) $user_id . "' AND acc.user_type_id ='" . (int) $user_type_id . "' AND acc.user_id = '" . (int) $user_id . "' ";
        }

        $data = $this->query($sql);

        return $data->row;
    }

	public function getExhibition($data = array(), $filter_data = array()){
		$sql ="SELECT e.*,c.country_name FROM exhibition_details  as e , country  as c  WHERE e.is_delete = '0' AND c.country_id = e.country" ;
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['name'])){
				$sql .= " AND e.exhibition_name LIKE '%".$filter_data['name']."%' ";		
			}
			if(!empty($filter_data['country'])){
				$sql .= " AND c.country_id = '". $filter_data['country']. "' ";	
				  
			}
			if($filter_data['status'] != ''){
				$sql .= " AND e.status = '".$filter_data['status']."' ";
			}
			
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY e.exhibition_id";	
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
		//echo $sql;
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows){
			return $data->rows;	
		}else{
			return false;	
		}
	}
	  public function getUserList() {
        $sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX . "account_master ORDER BY user_name ASC";
        $data = $this->query($sql);
        //printr($data);die;
        return $data->rows;
    }

	public function updateStatus($status,$data){		
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "exhibition_details` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE exhibition_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "exhibition_details` SET is_delete = '1', date_modify = NOW() WHERE exhibition_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	//15-07-2017:aakashi
	
	public function get_sheet($post)
	{
		//printr($post);
		//printr($f_date.'============='.$t_date);die;
		
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];	
	
		if($post['exhibition_name'] != '0')
		{
			$exb_name= "AND e.exhibition_id ='".$post['exhibition_name']."'";
		}else{
			$exb_name = '';
		}
		
		if($post['f_date'] != '')
		{
			$date = "AND e.date_added >= '".$post['f_date']."' ";
		}
		if($post['t_date'] != '')
		{
			$date.= "AND  e.date_added <='".$post['t_date']."'";
		}
		
		$admin_user_id='';
			
			
		if($user_type_id == 1 && $user_id == 1)
			{
				$sql ="SELECT ed.exhibition_id as e_id ,ed.exhibition_name as e_name,ed.duration_from,ed.duration_to,e.* ,c.*,am.* FROM exhibition_details as ed ,country as c,enquiry as  e,account_master as am   WHERE ed.is_delete = '0'  AND e.enquiry_source_id='4' AND am.user_id=e.user_id AND am.user_type_id=e.user_type_id AND ed.status = '0' AND e.country_id='".$post['country_id']."' AND e.country_id=c.country_id   $date $exb_name  GROUP BY e.enquiry_id";
			}
		else{
				if($user_type_id == 2){
					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					//printr($userEmployee);
					$set_user_id = $parentdata->row['user_id'];
					$set_user_type_id = $parentdata->row['user_type_id']; 
				}else{
					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
					$set_user_id = $user_id;
					$set_user_type_id = $user_type_id;
					}
						$str ='';
						if($userEmployee)
						{
							$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
							
						}
						//$sql ="SELECT * FROM `exhibition_details as e`,country as c WHERE e.is_delete = '0' AND e.status = '0' AND e.country=c.country_id AND ((e.user_id = '".$set_user_id."' AND e.user_type_id ='".$set_user__type_id."') $str) $date";	
	
			$sql ="SELECT ed.exhibition_id as e_id ,ed.duration_from,ed.duration_to,ed.exhibition_name as e_name,e.*,c.*,am.* FROM exhibition_details as ed ,country as c,enquiry as  e,account_master as am  WHERE ed.is_delete = '0'  AND e.enquiry_source_id='4' AND e.exhibition_id = ed.exhibition_id AND  ed.status = '0' AND e.country_id=c.country_id AND am.user_id=e.user_id AND am.user_type_id=e.user_type_id AND e.country_id='".$post['country_id']."' ((ed.user_id = '".$set_user_id."' AND ed.user_type_id ='".$set_user__type_id."') $str) $date $exb_name  GROUP BY e.enquiry_id ";
			
//$sql="select * from account_master";
		}
				//echo $sql;
				 $data=$this->query($sql);	
				$html ='';
			//	printr($data);
				 if($data->num_rows){
					 
					 
					$html .= "<div class='form-group' >";
					$html .= '<div class="panel-body">';
					$html .= '<div class="table-responsive">';
						$html .= "<center  ><span  ><b id='lamination' >EXHIBITION REPORT</b></span></center><br>";
					$html .= '<table class="table b-t text-small table-hover"  id="lamination_report" >';
						$html .= ' <thead style="border:groove;" id = "title">';
			 
			 
								 $html .= ' <tr  style="border:groove;">';
									 $html .= ' <th>Leads Number</th>';
									 $html .= ' <th>Company Name</th>';										 
									 $html .= ' <th>Exhibition Name</th>';	
									 $html .= ' <th colspan="2">Exhibition Start</th>';	
									 $html .= ' <th colspan="2">Exhibition End</th>';
									 $html .= ' <th colspan="2">Country</th>';
									 $html .= ' <th colspan="2">posted By</th>';							 
								 $html .= ' </tr>';
						

					 $html .= '</thead>';
					  $html .= '<tbody  style="border:groove;" >';
							foreach($data->rows as $v)
									{	
									//printr($v);
									$data_e_name =array();
									if($v['exhibition_id']!='0'){
										$sql5 ="SELECT * FROM exhibition_details WHERE exhibition_id = '".$v['exhibition_id']."'";
										$data_e_name = $this->query($sql5);
									}
										if(!empty($data_e_name)){
											$exhibition_name = $data_e_name->row['exhibition_name'];
											$duration_from =$data_e_name->row['duration_from'];
											$duration_to =$data_e_name->row['duration_to'];
										}else{
											$exhibition_name = $v['exhibition_name'];
											$duration_from = $v['duration_from'];
											$duration_to = $v['duration_to'];
										}
										  $html .= ' <tr >';
										  $html .= '<td colspan="1"> '.$v['enquiry_number'].'</td>';
										  $html .= '<td colspan="1"> '.$v['company_name'].'</td>';
										  $html .= '<td colspan="1"> '.$exhibition_name.'</td>';
										  $html .= '<td colspan="2"> '.$duration_from.'</td>';
										  $html .= '<td colspan="2"> '.$duration_to.'</td>';
										  $html .= '<td colspan="2"> '.$v['country_name'].'</td>';
										  $html .= '<td colspan="2"> '.$v['user_name'].'</td>';
										  $html .= ' </tr >';
										  
									}
						$html .= ' <tbody>';
					$html .= ' </table>';
				$html .= ' </div>';
			$html .= ' </div>';
		

											
	 }else{
		 	$html .= "<div class='form-group' >";
			$html .= '<div class="panel-body">';
				$html .= "<center><span>No record found !</span></center><br>";
				$html .= "</div>";
					$html .= '</div>';
	 }
	 	return $html ; 
	}
	
	//15-07-2017 :aakashi
	public function getExhibitions() {
        $data = $this->query("SELECT * FROM `" . DB_PREFIX . "exhibition_details` WHERE status = '1' AND is_delete = 0 AND exhibition_name != '' ");


        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
    }
	
			
	public function getUserEmployeeIds($user_type_id,$user_id)
	{
				$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
				$data = $this->query($sql);
				//printr($data);
				if($data->num_rows){
					return $data->row['ids'];
				}else{
					return false;
				}
	}
	
}
?>
