<?php
class invoice_fixmaster extends dbclass{
	
	// kinjal -->
	public function addFixmaster($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "invoice_fixmaster` SET exporter = '".$data['exporter']."', country_origin_goods = '".$data['country_origin_goods']."', mark_no = '".$data['mark_num']."',num_packages = '".$data['num_packages']."', googs_description = '".$data['goods_des']."',declaration='".$data['declaration']."',notes='".$data['footer_notes']."',sea_notes='".$data['sea_notes']."',status = '" .(int)$data['status']. "', date_added = NOW()";
		$this->query($sql);
	//	echo $sql;die;
		return $this->getLastId();
	}
	
	public function updateFixmaster($fixmaster_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX ."invoice_fixmaster` SET exporter = '".$data['exporter']."', country_origin_goods = '".$data['country_origin_goods']."', mark_no = '".$data['mark_num']."',num_packages = '".$data['num_packages']."', googs_description = '".$data['goods_des']."',declaration='".$data['declaration']."',notes='".$data['footer_notes']."',sea_notes='".$data['sea_notes']."',status = '" .(int)$data['status']. "', date_modify = NOW() WHERE fix_master_id = '" .(int)$fixmaster_id. "'";
		$this->query($sql);
		//echo $sql;die;
	}
	
	public function getInvoicefix($fix_master_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_fixmaster` WHERE fix_master_id = '" .(int)$fix_master_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalFixMaster(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "invoice_fixmaster` WHERE is_delete = '0'";
			
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getFix($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "invoice_fixmaster` WHERE is_delete = '0'";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY fix_master_id";	
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

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function updateFixmasterStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "invoice_fixmaster` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE fix_master_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "invoice_fixmaster` SET is_delete = '1', date_modify = NOW() WHERE fix_master_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateInvoiceFixStatus($fixmaster_id,$status_value){
		//echo "hiii";die;	
	   $sql = "UPDATE `" . DB_PREFIX . "invoice_fixmaster` SET status = '" .(int)$status_value ."' WHERE fix_master_id = '".$fixmaster_id ."' ";
	   $this->query($sql);	
	}
	
}
?>