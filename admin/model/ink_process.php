<?php
//==>kinjal
class ink_process extends dbclass{
	
	
	public function getOperatorName()
	{
		$sql = "SELECT e.employee_id,e.user_name FROM employee as e WHERE e.is_delete = '0' AND e.user_type = '3'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getCamistName()
	{
		$sql = "SELECT e.employee_id,e.user_name FROM employee as e WHERE e.is_delete = '0' AND e.user_type = '6'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function job_detail($job_name)
	{	
		$sql="SELECT * FROM  job_master WHERE job_no LIKE '%".strtolower($job_name)."%' AND is_delete=0 AND status = 1 ";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function addInkProcess($data)
	{   
		$sql = "INSERT INTO ink_process SET  job_id = '" .$data['job_id']. "',operator_id = '" .$data['operator_id']. "',chemist_id = '" .$data['camist_id']. "',shift = '" .$data['shift']. "',date = '" .$data['job_date']. "', user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "',user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',status = '1', date_added = NOW(),is_delete=0";
		$this->query($sql);
                $process_id = $this->getLastId();
                foreach($data['multiplerows'] as $ink){
                    $this->query("INSERT INTO  ink_process_detail SET ink_p_id = '".$process_id."', ink_name = '".$ink['ink_name']."', ink_issue = '".$ink['issue']."', ink_return = '".$ink['return']."', ink_use = '".$ink['use']."', remark = '".$ink['remark']."', date_added = NOW(),is_delete=0");
                    }
                    return $process_id;
			
	}
        
	public function getTotalInkProcess($filter_data=array())
	{
            //printr($filter_data);
            $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
             $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "ink_process` as i , job_master as jm WHERE i.job_id=jm.job_id AND i.is_delete = 0";
                if ($user_type_id != 1 && $user_id != 1) {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
            }
          //  $sql .= " AND jm.user_id = '" . (int) $user_id . "' AND jm.user_type_id = '" . (int) $user_type_id . "' ";
        }
		if(!empty($filter_data)){
			if(!empty($filter_data['job_name'])){
				$sql .= " AND jm.job_name LIKE '%".$filter_data['job_name']."%' ";		
			}
			if(!empty($filter_data['operator_id'])){
				$sql .= " AND i.operator_id = '".$filter_data['operator_id']."' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY jm.job_name";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
               // echo $sql;die;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getInkProcess($data,$filter_data=array())
	{
            $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
            $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$sql = "select i.*,e.employee_id as operator_id,e.user_name as operator_name,c.employee_id as chemist_id, c.user_name as chemist_name,jm.job_id,jm.job_no,jm.job_name from ink_process as i LEFT JOIN job_master as jm ON i.job_id=jm.job_id LEFT JOIN employee as e ON i.operator_id=e.employee_id LEFT JOIN employee as c ON i.chemist_id=c.employee_id WHERE i.is_delete = '0' ";
                if ($user_type_id != 1 && $user_id != 1) {
            if ($user_type_id == 2) {
                $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '" . $user_id . "' ");
                $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
            } else {
                $userEmployee = $this->getUserEmployeeIds($user_type_id, $user_id);
            }
            //$sql .= " AND jm.user_id = '" . (int) $user_id . "' AND jm.user_type_id = '" . (int) $user_type_id . "' ";
        }
			
		if(!empty($filter_data['job_name'])){
				$sql .= " AND jm.job_name LIKE '%".$filter_data['job_name']."%' ";		
			}
			if(!empty($filter_data['operator_id'])){
				$sql .= " AND i.operator_id = '".$filter_data['operator_id']."' "; 	
			}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY jm.job_id";	
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

                $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
            }
//            echo $sql;die;
		$data = $this->query($sql);
		return $data->rows;
		
		
	}
        
	public function getInkProcesDetail($ink_id)
 {

        $sql = "SELECT * FROM ink_process as i, job_master as j  WHERE i.is_delete = 0 AND i.ink_p_id='" . $ink_id . "' AND i.job_id=j.job_id ";
        $data = $this->query($sql);
        if ($data->row) {
            $ink_process_detail = "SELECT * FROM ink_process_detail as ipd WHERE ipd.ink_p_id='" . $ink_id . "' AND ipd.is_delete = '0' ";
            $ink_process_data = $this->query($ink_process_detail);
            $ink_array = array();
            foreach ($ink_process_data->rows as $ink_data) {
                $ink_array[] = array(
                    'ink_process_id' => $data->row['ink_p_id'],
                    'ink_process_detail_id' => $ink_data['ink_p_d_id'],
                    'ink_name' => $ink_data['ink_name'],
                    'ink_issue' => $ink_data['ink_issue'],
                    'ink_return' => $ink_data['ink_return'],
                    'ink_use' => $ink_data['ink_use'],
                    'remark' => $ink_data['remark'],
                    'date_added' => $ink_data['date_added'],
                    'date_modify' => $ink_data['date_modify'],
                    'is_delete' => $ink_data['is_delete'],
                );
            }
            $all_ink_array = array(
                'ink_process_id' => $data->row['ink_p_id'],
                'job_id' => $data->row['job_id'],
                'job_name_text' => $data->row['job_no'],
                'job_name' => $data->row['job_name'],
                'operator_id' => $data->row['operator_id'],
                'chemist_id' => $data->row['chemist_id'],
                'shift' => $data->row['shift'],
                'date' => $data->row['date'],
                'date_added' => $data->row['date_added'],
                'date_modify' => $data->row['date_modify'],
                'status' => $data->row['status'],
                'is_delete' => $data->row['is_delete'],
                'ink_detail' => $ink_array,
            );
//            printr($all_ink_array);die;
            return $all_ink_array;
        } else {
            return false;
        }
    }

    public function updateInkProcess($ink_id)
	{   //printr($ink_id);
        $id = $ink_id['ink_id'];
        $sql = "UPDATE ink_process SET  job_id = '" . $ink_id['job_id'] . "',operator_id = '" . $ink_id['operator_id'] . "',chemist_id = '" . $ink_id['camist_id'] . "',shift = '" . $ink_id['shift'] . "',date = '" . $ink_id['job_date'] . "',date_modify = NOW() WHERE ink_p_id = '" . $id . "'";
        $this->query($sql);
        foreach ($ink_id['multiplerows'] as $ink) {
            if (isset($ink['ink_process_detail_id']) && ($ink['ink_process_detail_id'] != '')) {
                $this->query("UPDATE ink_process_detail SET ink_name = '" . $ink['ink_name'] . "', ink_issue = '" . $ink['issue'] . "', ink_return = '" . $ink['return'] . "', ink_use = '" . $ink['use'] . "', remark = '" . $ink['remark'] . "', date_modify = NOW() WHERE ink_p_d_id='" . $ink['ink_process_detail_id'] . "'");
            } else {
                $this->query("INSERT INTO  ink_process_detail SET ink_p_id = '" . $id . "', ink_name = '" . $ink['ink_name'] . "', ink_issue = '" . $ink['issue'] . "', ink_return = '" . $ink['return'] . "', ink_use = '" . $ink['use'] . "', remark = '" . $ink['remark'] . "', date_added = NOW(),is_delete=0");
            }//die;
        }
    }

    public function updateInkStatus($id,$status)
	{
            $sql = "UPDATE ink_process SET status = '" .(int)$status. "',  date_modify = NOW() WHERE ink_p_id = '".$id."' ";
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data)
	{
		if($status == 0 || $status == 1){
			$sql = "UPDATE ink_process SET status = '" .(int)$status. "',  date_modify = NOW() WHERE ink_p_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE ink_process SET is_delete = '1', date_modify = NOW() WHERE ink_p_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
        
    public function remove_ink($ink_id)
        {
            $sql = "UPDATE ink_process_detail SET is_delete = '1' WHERE ink_p_d_id = $ink_id ";
            $this->query($sql);
        }
        
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
	
	public function getInk()
	{//online:4 and offline:9
		$sql = "SELECT * FROM " . DB_PREFIX . "product_item_info as pif, product_category as pc WHERE pif.product_category_id = '4' AND pif.is_delete=0 AND pif.product_category_id=pc.product_category_id";
//		echo $sql;
        $data = $this->query($sql);

        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
	}
}
?>