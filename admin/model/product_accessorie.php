<?php 
class productAccessorie extends dbclass{
	
	public function addAccessorie($data){
		
		$sql= "INSERT INTO ".DB_PREFIX."product_accessorie SET product_accessorie_name='".$data['product_accessorie_name']."',accessorie_abbrevation ='".$data['accessorie_abbrevation']."',product_accessorie_unit='".$data['unit']."',product_accessorie_min_qty='".$data['min_qty']."',price='".$data['price']."',wastage='".$data['wastage']."',serial_no='".$data['serial']."',status='".$data['status']."',date_added = NOW() ";
		$this ->query($sql);
		return $this->getLastId();
	}
	
	public function updateAccessorie($accessorie_id,$data){
		
		$sql = "UPDATE " . DB_PREFIX . "product_accessorie SET product_accessorie_name='".$data['product_accessorie_name']."',accessorie_abbrevation ='".$data['accessorie_abbrevation']."',price='".$data['price']."',product_accessorie_min_qty='".$data['min_qty']."',product_accessorie_unit='".$data['unit']."',wastage='".$data['wastage']."',serial_no='".$data['serial']."',status='".$data['status']."',date_added = NOW() WHERE product_accessorie_id = '" .(int)$accessorie_id. "'";
		$this->query($sql);
	}
	public function getAccessorie($accessorie_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "product_accessorie WHERE product_accessorie_id = '" .(int)$accessorie_id. "'";
		//echo $sql;die; 
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getTotalAccessories(){
		$sql = "SELECT COUNT(*) as total FROM ".DB_PREFIX."product_accessorie";
		$data = $this -> query($sql);
		return $data->row['total'];
	}
		
	public function getAccessories($data)
	{
	
	$sql = "SELECT * FROM ".DB_PREFIX."product_accessorie";
	
	if(isset($data['sort'])){
	$sql .=" ORDER BY ".$data['sort'];
	}else{ 
	$sql .=" ORDER BY product_accessorie_id";	
	}
	
	if(isset($data['order']) && ($data['order'] == 'DESC')){
		$sql .=" DESC";
	}else{
		$sql .=" ASC";
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
		$result = $this->query($sql);
		if($result->num_rows){
			return $result->rows;
		}else{
			return false;
		}
		}
		
		public function updateaccessorieStatus($accessorie_id,$status_value){	
	   $sql = "UPDATE " . DB_PREFIX . "product_accessorie SET status = '" .(int)$status_value ."' WHERE product_accessorie_id = '".$accessorie_id ."' ";
	   $this->query($sql);	
	}
	
	} 

?>