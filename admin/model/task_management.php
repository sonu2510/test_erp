<?php
class task_management extends dbclass{
	
		public function get_task_details($filter_data = array())
	{
		
			$sql="SELECT * fROM task_management as t, employee as e WHERE  t.is_delete = '0' AND e.employee_id=t.assign_to_user_id ";
		//$assign_by_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
      //  $assign_by_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			if($user_type_id != 1 && $user_id != 1){
				if($user_type_id == 2){
					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				}else{
					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				}
				$str = '';
				if($userEmployee){
					$str = ' OR ( 	t.assign_by_user_id IN ('.$userEmployee.') AND 	t.assign_by_user_type_id = "2" )';
				}
			
			$sql .= " AND 	t.assign_by_user_id = '".(int)$user_id."' AND 	t.assign_by_user_type_id = '".(int)$user_type_id."' $str";
		}
		
		 if (!empty($filter_data)) {
            if (!empty($filter_data['task_name'])) {
                $sql .= " AND t.task_name LIKE '%" . $filter_data['task_name'] . "%' ";
				// $sql .= " AND t.task_name LIKE '%" . $filter_data['task_name'] . "%' ";
            }


            if (!empty($filter_data['priority'])) {
                $sql .= " AND t.priority LIKE '%" . $filter_data['priority'] . "%' ";
				//$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
            }
			
		 }
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY t.task_management_id";	
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
		
		public function updateTask($task_management_id,$data){
		
		
		$assign_by_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
        $assign_by_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		//$shared_task_with=(',',$data['shared_task_user_id']);
		$shared_task_with='';
		if(isset($data['shared_task_user_id']) && !empty($data['shared_task_user_id']))
			$shared_task_with=implode(',',$data['shared_task_user_id']);
		$sql = "UPDATE `" . DB_PREFIX . "task_management` SET task_name = '" .$data['task_name']. "',priority = '" .$data['priority']. "',start_date = '".date("Y-m-d",strtotime($data['start_date']))."',due_date = '".date("Y-m-d",strtotime($data['due_date']))."',assign_to_user_id='" .$data['assigned_to_user_id'] . "',
		shared_task_user_id='".$shared_task_with."',description= '" .$data['description']. "' WHERE task_management_id = '" .(int)$task_management_id. "'";
		//layer = '".serialize($data['layer'])."',
		
		$this->query($sql);
		
		
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
	
	
	//get total Task
	public function getTotalTask($filter_data = array()){
	
			$sql="SELECT *, COUNT(*) as total  fROM task_management  WHERE  is_delete = '0' ";
	
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			if($user_type_id != 1 && $user_id != 1){
				if($user_type_id == 2){
					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				}else{
					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				}
				$str = '';
				if($userEmployee){
					$str = ' OR ( 	assign_by_user_id IN ('.$userEmployee.') AND 	assign_by_user_type_id = "2" )';
				}
			
			$sql .= " AND 	assign_by_user_id = '".(int)$user_id."' AND 	assign_by_user_type_id = '".(int)$user_type_id."' $str";
		}
		
		 if (!empty($filter_data)) {
            if (!empty($filter_data['task_name'])) {
                $sql .= " AND task_name LIKE '%" . $filter_data['task_name'] . "%' ";
				
            }


            if (!empty($filter_data['priority'])) {
                $sql .= " AND priority LIKE '%" . $filter_data['priority'] . "%' ";
				
            }
			
		 }
		
			
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY task_management_id";	
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
		
		return $data->row['total'];
		
		
		
		
		
		
	}
	
	//edit
	public function get_task_data($task_management_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "task_management` WHERE task_management_id = '" .(int)$task_management_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function addTask($data){
		$assign_by_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
        $assign_by_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$shared_task_with='';
		if(isset($data['shared_task_user_id']) && !empty($data['shared_task_user_id']))
			$shared_task_with=implode(',',$data['shared_task_user_id']);
		
		$user=$this->query("INSERT INTO " . DB_PREFIX . "task_management SET shared_task_user_id='".$shared_task_with."', assign_to_user_type_id='" .$data['assigned_to_user_type_id'] . "',assign_to_user_id='" .$data['assigned_to_user_id'] . "', assign_by_user_id='" . $assign_by_user_id . "',assign_by_user_type_id='" . $assign_by_user_type_id . "',task_name='" . $data['task_name'] . "',priority ='" .$data['priority']. "',start_date = '".date("Y-m-d",strtotime($data['start_date']))."',due_date = '".date("Y-m-d",strtotime($data['due_date']))."', description = '" . $data['description']."'");
		//print_r($user);
		
		}
		public function gettask(){
		$assign_by_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
        $assign_by_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$sql="SELECT * FROM " . DB_PREFIX . "  employee where user_id = '" . $assign_by_user_id."'";
		$data1 = $this->query($sql);
		if($data1->num_rows){
			return $data1->row;
		}else{
			return false;
		}
		
	}
	
	///fatch recoerd
	
	
	
	public function getIBList() {
        $sql = "SELECT international_branch_id,address_id,CONCAT(first_name,' ',last_name) as user_name FROM international_branch";
        $data = $this->query($sql);
        //printr($data);die;
        return $data->rows;
    }
	
	
	public function getEmployeeName()
	{
		
		$assign_by_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
        $assign_by_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$sql="SELECT * fROM employee  WHERE  user_id='".$assign_by_user_id."'  ";
		$data = $this->query($sql);
			//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		
		
	}
	
	////End
	

	
	public function updateSourceStatus($id,$status){
		$this->query("UPDATE " . DB_PREFIX . "task_management SET status = '" .(int)$status. "' WHERE task_management_id = '".$id."' ");
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "task_management` SET status = '" .(int)$status. "' WHERE task_management_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "task_management` SET is_delete = '1' WHERE task_management_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
}
?>