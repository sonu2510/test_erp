<?php
class leave_type extends dbclass
{
//insert data for leave application


	public function Add_Leave($data)
	{
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_detail =$this->getUser($user_id,$user_type_id);
		

		$sql = "INSERT INTO " . DB_PREFIX . "leave_application SET 	user_name = '".$user_detail['user_name']."',user_id='".$user_id."', user_type_id='".$user_type_id."',leave_type_name='".$data['leave_type_name']."',leave_title	='" . $data['leave_title'] . "',no_of_days='" . $data['ending_date'] . "', commencing_date='" . $data['f_date'] . "',ending_date='" . $data['t_date']."',message	='" . $data['messag'] . "'";

		$data = $this->query($sql);

	}

	
	public function getUser($user_id,$user_type_id)

		{	//echo $user_type_id;

			if($user_type_id == 1){

				$sql = "SELECT ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";

				//echo $sql;

			}elseif($user_type_id == 2){

				$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";

				

			}elseif($user_type_id == 4){

				$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";

			}else{

				

				$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";

			}

			$data = $this->query($sql);

			return $data->row;

		}
    public function addleave($data)
    {

        $sql = "INSERT INTO leave_type SET leave_type_name = '" . $data['leave_type_name'] . "',status = '" . $data['status'] . "', date_added = NOW(),date_modify = NOW(),is_delete=0";
        //echo $sql;die;
        $this->query($sql);
    }

	
	
	
    public function updateleave($leave_type_id, $data)
    {

		$sql = "UPDATE " . DB_PREFIX . "leave_type SET leave_type_name = '" .$data['leave_type_name']. "',status = '" .$data['status']. "' WHERE leave_type_id = '" .(int)$leave_type_id. "'";
		
		$this->query($sql);
    }



    public function getTotalleave($filter_data=array()){
        //$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "leave_type` WHERE is_delete = 0";
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "leave_application` la LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_id = la.user_id AND am.user_type_id = 'la.user_type_id'  WHERE la.is_delete = '0'";
		//printr($filter_array);
        if(!empty($filter_data)){						
						
			if(!empty($filter_data['user_name'])){
				$sql .= " AND la.user_name LIKE '%".$this->escape($filter_data['user_name'])."%'";
			}									
		}
		//echo $sql;die;		
		$data = $this->query($sql);
		return $data->row['total'];
	}

    public function getleave(){
        $sql = "SELECT * FROM leave_type WHERE is_delete = '0'";
        //echo $sql;die;
	
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	
	}
       
    public function getleave_detail($leave_type_id){
        $sql = "SELECT * FROM leave_type WHERE is_delete = '0' AND leave_type_id='".$leave_type_id."' ";
		$data = $this->query($sql);
        //echo $sql;
        if($data->num_rows){
            return $data->row;
        }else{
            return false;
        }
    }

    public function updateleavestatus($id,$approval_status){
        $sql = "UPDATE `" . DB_PREFIX . "leave_type` SET approval_status = '" .(int)$statusapproval_status. "',  date_modify = NOW() WHERE leave_type_id = '".$id."' ";
        $this->query($sql);
    }

    public function updateStatus($status,$data){
        if($approval_status == 0 || $approval_status == 1){
            $sql = "UPDATE `" . DB_PREFIX . "leave_application` SET approval_status = '" .(int)$status. "' WHERE leave_id IN (" .implode(",",$data). ")";
            //echo $sql;die;
            $this->query($sql);
       
        }

    }
	
	/*public function getLeave_Details(){
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		
		$sql="SELECT * FROM leave_application WHERE user_id = '".$user_id."' AND user_type_id='".$user_type_id."' ";
		
		$data = $this->query($sql);
		//	printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}*/
	
		
	public function getUserEmployeeIds($user_type_id, $user_id) {
			$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '" . (int) $user_type_id . "' AND user_id = '" . (int) $user_id . "'";
	//		echo $sql;
			$data = $this->query($sql);

			if ($data->num_rows) {
				return $data->row['ids'];
			} else {
				return false;
			}
		}
	
	
	public function getLeave_Details($user_id,$user_type_id,$filter_data=array(),$option=array()) { //echo $user_type_id;
	
		$sql="SELECT l.*,ac.user_name,ac.email FROM leave_application as l ,account_master as ac WHERE ac.user_id=l.user_id AND ac.user_type_id=l.user_type_id AND l.is_delete=0 ";
		
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			if($user_type_id != 1 && $user_id != 1){
				if($user_type_id == 2){
					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				}else{
					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
					$set_user_id = $user_id;

					$set_user_type_id = $user_type_id;
					
				
							$str = '';
							 if($userEmployee){
							
								 $str = ' OR (l.user_id IN ('.$userEmployee.') AND 	l.user_type_id = "2" )';
							 }
							$sql = "SELECT  l.*,ac.user_name,ac.email  FROM " . DB_PREFIX . "leave_application as l,account_master as ac WHERE ac.user_id=l.user_id AND ac.user_type_id=l.user_type_id AND l.is_delete = '0'  AND  (l.user_id='" . $set_user_id . "' AND l.user_type_id='" . $set_user_type_id . "' $str) ";

			
					}
					if($user_type_id == 2){
					$str = '';
					$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
					$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
					$sql = "SELECT  l.*,ac.user_name,ac.email  FROM " . DB_PREFIX . "leave_application as l,account_master as ac WHERE   ac.user_id=l.user_id AND ac.user_type_id=l.user_type_id AND l.is_delete = '0' AND (l.user_id='" . $set_user_id . "' AND l.user_type_id='" . $set_user_type_id . "' $str)  ";

					}
			}
			//echo $sql;
		//	echo "<br>";
	
		if (!empty($filter_data)) {
				
            if (isset($filter_data['user_name'])) {
                $sql .= "AND l.user_name LIKE '%" . $filter_data['user_name'] . "%' ";
				//printr($filter_data);
				//echo $sql;
            }
		}	
		if (isset($option['sort'])) {
			$sql .= " ORDER BY " . $option['sort'];	
		} else {
			$sql .= " ORDER BY l.leave_id";	
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
		//echo $sql.'<br>';
       $data = $this->query($sql);
			
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	 public function leave_popup_detail($leave_id) {
	//printr($post);die;
	
        $sql = "SELECT * FROM leave_application WHERE leave_id = '" . $leave_id . "' ORDER BY leave_id DESC";
		
        $data = $this->query($sql);
		//printr($data);die;
        if ($data->num_rows) {

            return $data->row;
        } else {

            return false;
        }
		
    }
	

	public function updateTypeStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "leave_type` SET status = '" .(int)$status. "' WHERE leave_type_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "leave_type` SET is_delete = '1' WHERE leave_type_id IN (" .implode(",",$data). ")";		
			$this->query($sql);
		}
	}
	
 
 
  public function active($id){
        $sql = "UPDATE `" . DB_PREFIX . "leave_type` SET status = 0  WHERE leave_type_id = '".$id."' ";
        $this->query($sql);
    }
	 public function inactive($id){
        $sql = "UPDATE `" . DB_PREFIX . "leave_type` SET status = 1 WHERE leave_type_id = '".$id."' ";
        $this->query($sql);
    }
	
	
	 public function approve($id,$reason,$re_from_date,$re_to_date){
        $sql = "UPDATE  leave_application SET approval_status = 1 , reason='".$reason."',re_from_date='".$re_from_date."',re_to_date='".$re_to_date."' WHERE leave_id = '".$id."' ";
        
		$this->query($sql);
    }
	 public function disapprove($id,$reason,$re_from_date,$re_to_date){
        $sql = "UPDATE  leave_application SET approval_status = 0 ,reason='".$reason."' ,re_from_date='".$re_from_date."',re_to_date='".$re_to_date."' WHERE leave_id = '".$id."' ";
        $this->query($sql);
    }
 
 
	
   public function ApproveDisapproveAdd($data) {
	
	if($approval_status == 2){
	$sql = "Insert into leave_application SET reason='".$data['reason']."',re_from_date='".$data['re_from_date']."',re_to_date='".$data['re_to_date']."'";
	$data = $this->query($sql);
	
	}
	}
 }
?>

