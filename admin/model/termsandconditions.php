<?php
class termsandconditions extends dbclass{
	
	public function addTerms($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "termsandconditions` SET termsandconditions = '".$data['termsandconditions']."',status = '" .(int)$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateTerms($termsandconditions_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "termsandconditions` SET termsandconditions= '".$data['termsandconditions']."',status = '" .(int)$data['status']. "', date_modify = NOW() WHERE termsandconditions_id = '" .(int)$termsandconditions_id. "'";
		$this->query($sql);
	}

	public function getTerm($termsandconditions_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "termsandconditions` WHERE termsandconditions_id = '" .(int)$termsandconditions_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalTerms(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "termsandconditions` WHERE is_delete = '0'";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTerms($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "termsandconditions` WHERE is_delete = '0'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "termsandconditions` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE termsandconditions_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "termsandconditions` SET is_delete = '1', date_modify = NOW() WHERE termsandconditions_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateTermsStatus($termsandconditions_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "termsandconditions` SET status = '" .(int)$status_value ."' WHERE termsandconditions_id = '".$termsandconditions_id ."' ";
	   $this->query($sql);	
	}
	
}
?>