<?php //kinjal
class sheet_manage extends dbclass{
	
	public function addSheet($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "sheet_management` SET sheet_name = '".addslashes($data['name'])."', width = '" .$data['width']. "',height = '" .$data['height']. "',price = '" .$data['price']. "', printing_cost = '".$data['printing_cost']. "', wastage = '".$data['wastage']. "', weight = '" .$data['weight']. "',header_margin='".$data['header_margin']."',footer_margin='".$data['footer_margin']."',left_margin='".$data['left_margin']."',right_margin='".$data['right_margin']."',between_stickers='".$data['between_stickers']."',make_pouch='".implode(',',$data['make_pouch'])."',printing_effect='".implode(',',$data['printing_effect'])."',qty='".implode(',',$data['qty'])."', date_added = NOW(), date_modified = NOW(),status=1";
		$this->query($sql);
	}
	
	public function updateSheet($sheet_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "sheet_management` SET sheet_name = '".addslashes($data['name'])."', width = '" .$data['width']. "',height = '" .$data['height']. "',price = '" .$data['price']. "', printing_cost = '".$data['printing_cost']. "', wastage = '".$data['wastage']. "', weight = '" .$data['weight']. "',header_margin='".$data['header_margin']."',footer_margin='".$data['footer_margin']."',left_margin='".$data['left_margin']."',right_margin='".$data['right_margin']."',between_stickers='".$data['between_stickers']."',make_pouch='".implode(',',$data['make_pouch'])."',printing_effect='".implode(',',$data['printing_effect'])."',qty='".implode(',',$data['qty'])."',date_modified = NOW() WHERE sheet_id = '" .(int)$sheet_id. "'";
		$this->query($sql);
	}
	
	public function getMake(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_make` WHERE make_id IN (1,2)";
		$data = $this->query($sql);
        if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getLabelPrintingEffect(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "label_printing_effect` WHERE status = 1 AND is_delete = 0");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}			
	}
	
	public function getLabelQty(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "label_quantity` WHERE status=1 AND is_delete = 0");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getSheets($data=array()){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "sheet_management` WHERE is_delete = 0";
		
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
	
	public function getSheetData($sheet_id)
	{
		$sql = "Select * From `" . DB_PREFIX . "sheet_management` WHERE sheet_id = '".$sheet_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "sheet_management` SET status = '" .(int)$status. "',  date_modified = NOW() WHERE sheet_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "sheet_management` SET is_delete = '1', date_modified = NOW() WHERE sheet_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
}
?>