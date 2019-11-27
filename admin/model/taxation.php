<?php
class taxation extends dbclass{

	public function addTaxation($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "taxation` SET tax_name='".$data['tax_name']."', excies = '".$data['excies']."', cst_with_form_c = '".$data['cst_with_form']."',
		cst_without_form_c = '".$data['cst_without_form']."', vat = '".$data['vat']."',cgst = '".$data['cgst']."',sgst = '".$data['sgst']."',igst = '".$data['igst']."',status = '" .(int)$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateTaxation($taxation_id,$data){

		
		$sql = "UPDATE `" . DB_PREFIX . "taxation` SET tax_name = '".$data['tax_name']."', excies = '".$data['excies']."', cst_with_form_c = '".$data['cst_with_form']."',
		 cst_without_form_c = '".$data['cst_without_form']."', vat = '".$data['vat']."',cgst = '".$data['cgst']."',sgst = '".$data['sgst']."',igst = '".$data['igst']."', status = '" .(int)$data['status']. "', date_modify = NOW() WHERE taxation_id = '" .(int)$taxation_id. "'";
		$this->query($sql);
	}

	public function getTaxation($taxation_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "taxation` WHERE taxation_id = '" .(int)$taxation_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalTaxation(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "taxation` WHERE is_delete = '0'";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTaxations($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "taxation` WHERE is_delete = '0'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "taxation` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE taxation_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "taxation` SET is_delete = '1', date_modify = NOW() WHERE taxation_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateTaxationStatus($taxation_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "taxation` SET status = '" .(int)$status_value ."' WHERE taxation_id = '".$taxation_id ."' ";
	   $this->query($sql);	
	}
	
}
?>