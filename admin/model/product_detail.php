<?php
class productDetail extends dbclass{
	
	/* ################## PRODUCT DETAIL MASTER ###########################*/
	public function getProductActive(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getMaterialActive(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_material` WHERE status='1' ";
		$sql .= " ORDER BY material_name";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getLayerActive(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_layer` WHERE status='1' ";
		$sql .= " ORDER BY layer";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getOption(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_option` WHERE status='1' ";
		$sql .= " ORDER BY option_name";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function addProductDetail($data){
		printr($data);die;
		/*
		Array (
			[product] => 3
			[material] => 1
			[layer] => 2
				[layer_price] => Array([0] => 180 [1] => 142 )
			[form_wastage] => Array([0] => 0 [1] => 500 )
			[to_wastage] => Array( [0] => 500 [1] => 1000 )
			[wastage] => Array ([0] => 20 [1] => 30 )
			[hdn_wastagecount] => 1
			[option_1] => 5
			[option_3] => 6
			[option_4] => 7
			[option_2] => 8 
			[form_profit] => Array ([0] => 0 [1] => 500)
			[to_profit] => Array([0] => 500 [1] => 1000)
			[profit] => Array ([0] => 2 [1] => 4)
			[hdn_profitcount] => 1
			[packing_per_pouch] => 2 
			[transport_air] => 2
			[transport_sea] => 3
			[transport_other] => 10
			[transport_per_pouch] => 1000
			[status] => 1     [btn_save] =>  ) 
		*/
		$sql = "INSERT INTO `" . DB_PREFIX . "product_detail` SET product_id = '" .$data['product']. "', material_id = '".(int)$data['material']."', status = '" .$data['status']. "', date_added = NOW()";
		//echo $sql;die;
		$this->query($sql);
		return $this->getLastId();
	}
}
?>