<?php
class productZipper extends dbclass{
	
	public function addZipper($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "product_zipper` SET zipper_name = '".$data['name']."',zipper_unit = '".$data['unit']."',zipper_min_qty = '".$data['min_qty']."', price = '" . $data['price']. "',wastage='".$data['wastage']."',Weight='".$data['Weight']."' ,zipper_abbr = '".$data['abbr']."',serial_no='".$data['serial_no']."',slider_price='".$data['slider_price']."',remark ='".$data['remark']."',status = '".$data['status']."', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	
	public function getZipper($zipper_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE product_zipper_id = '" .(int)$zipper_id. "'";
		//echo $sql;die; 
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateZipper($zipper_id,$data){
		$sql = "UPDATE `" . DB_PREFIX . "product_zipper` SET zipper_name = '".$data['name']."',zipper_abbr = '".$data['abbr']."',price = '" .$data['price']. "',zipper_min_qty = '".$data['min_qty']."', zipper_unit = '".$data['unit']."',wastage='".$data['wastage']."',Weight='".$data['Weight']."',serial_no='".$data['serial_no']."',slider_price='".$data['slider_price']."',remark ='".$data['remark']."',status = '".$data['status']."', date_modify = NOW() WHERE product_zipper_id = '" .(int)$zipper_id. "'";
		$this->query($sql);
	}
	
	public function getTotalZipper(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_zipper`";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getZippers($data){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_zipper`";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY product_zipper_id";	
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