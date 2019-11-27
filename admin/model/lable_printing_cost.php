<?php
class label_printing extends dbclass{
	public function addLabelPrintingCost($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "label_sheet_printing_costing` SET from_qty = '" .(float)$data['from_qty']. "', to_qty = '" .(float)$data['to_qty']. "', price = '" .(float)$data['price']. "', date_added = NOW(), date_modify = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateLabelPrintingCost($id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "label_sheet_printing_costing` SET from_qty = '" .(float)$data['from_qty']. "', to_qty = '" .(float)$data['to_qty']. "', price = '" .(float)$data['price']. "', date_modify = NOW() WHERE printing_id = '" .(int)$id. "'";
		$this->query($sql);
	}
	
	public function getLabelPrintingCost($id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_sheet_printing_costing` WHERE printing_id = '" .(int)$id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	public function getlabelPrintingCostDetails($data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "label_sheet_printing_costing`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY printing_id";	
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
}
?>