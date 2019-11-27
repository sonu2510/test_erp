<?php
class productMaterial extends dbclass{
	
	public function addMaterial($data){
	    $roll_qty = '';
	    if(isset($data['quantityRoll']) && !empty($data['quantityRoll']))
	    {
	        $roll_qty=implode(',',$data['quantityRoll']);
	    }
	    
		$sql = "INSERT INTO `" . DB_PREFIX . "product_material` SET material_name = '" .$data['name']. "',material_unit = '".$data['qtyunit']."',gsm = '".$data['gsm']."',minimum_quantity = '".$data['minimum_quantity']."',roll_quantity='".$roll_qty."', status = '" .$data['status']. "', date_added = NOW()";
		//echo $sql;die;
		$this->query($sql);
		$material_id = $this->getLastId();
		
		//layers
		if(isset($data['layer']) && !empty($data['layer'])){
			foreach($data['layer'] as $key=>$layer_id){
				$this->query("INSERT INTO " . DB_PREFIX . "product_layer_material SET material_id = '".$material_id."', layer_id = '" .$layer_id. "', date_modify = NOW()");
			}
		}
	
		//effect (ruchi)
		if(isset($data['effect']) && !empty($data['effect'])){
			foreach($data['effect'] as $key=>$effect_id){
				$this->query("INSERT INTO " . DB_PREFIX . "product_printing_effect SET material_id = '".$material_id."', effect_id = '" .$effect_id. "', date_modify = NOW()");
			}
		}
		if(isset($data['make']) && !empty($data['make'])){
			foreach($data['make'] as $key=>$make_id){
				$this->query("INSERT INTO " . DB_PREFIX . "product_material_make SET material_id = '".$material_id."', make_id = '" .$make_id. "', date_modify = NOW()");
			}
		}
		//(ruchi)
		
		//thickness
		if(isset($data['thickness']) && !empty($data['thickness'])){
			for($i=0;$i<count($data['thickness']['price']);$i++){
				//echo $data['thickness']['from'][$i]."==".$data['thickness']['to'][$i]."==".$data['thickness']['price'][$i]."<br>";	
				$this->query("INSERT INTO " . DB_PREFIX . "product_material_thickness_price SET product_material_id = '".(int)$material_id."', from_thickness = '" .$data['thickness']['from'][$i]. "', to_thickness = '" .$data['thickness']['to'][$i]. "', price = '" .$data['thickness']['price'][$i]. "', date_modify = NOW()");
			}
		}
		
		if(isset($data['new_thickness']) && !empty($data['new_thickness'])){			
			foreach($data['new_thickness'] as $material_thickness){
				$this->query("INSERT INTO " . DB_PREFIX . "product_material_thickness SET product_material_id = '".(int)$material_id."',thickness = '".$material_thickness . "',status=1,date_added=NOW(),date_modify = NOW(),is_delete=0 ");
			}							
		}
		
		if(isset($data['quantity']) && !empty($data['quantity'])){
			foreach($data['quantity'] as $quantity_id){
				$this->query("INSERT INTO " . DB_PREFIX . "product_material_quantity SET product_material_id = '".(int)$material_id."', product_quantity_id = '".$quantity_id . "', date_added=NOW()");
			}
		}
		
		return $material_id;
	}
	
	public function updateMaterial($material_id,$data){
		//printr($data);
		$roll_qty = '';
	    if(isset($data['quantityRoll']) && !empty($data['quantityRoll']))
	    {
	        $roll_qty=implode(',',$data['quantityRoll']);
	    }
		
		$sql = "UPDATE `" . DB_PREFIX . "product_material` SET material_name = '" .$data['name']. "',material_unit = '".$data['qtyunit']."',minimum_quantity = '".$data['minimum_quantity']."', gsm = '".$data['gsm']."',roll_quantity='".$roll_qty."',  status = '" .$data['status']. "',  date_modify = NOW() WHERE product_material_id = '" .(int)$material_id. "'";
		//layer = '".serialize($data['layer'])."',
		$this->query($sql);
		if(isset($data['layer'])){
			$this->query("DELETE FROM " . DB_PREFIX . "product_layer_material WHERE material_id = '".(int)$material_id."'");
			foreach($data['layer'] as $key=>$layer_id){
				$this->query("INSERT INTO " . DB_PREFIX . "product_layer_material SET material_id = '".$material_id."', layer_id = '" .$layer_id. "', date_modify = NOW()");
			}
		}
		
		//effect (ruchi)
		if(isset($data['effect'])){
			$this->query("DELETE FROM " . DB_PREFIX . "product_printing_effect WHERE material_id = '".(int)$material_id."'");
			foreach($data['effect'] as $key=>$effect_id){
				$this->query("INSERT INTO " . DB_PREFIX . "product_printing_effect SET material_id = '".$material_id."', effect_id = '" .$effect_id. "', date_modify = NOW()");
			}
		}
		
		if(isset($data['make'])){
			$this->query("DELETE FROM " . DB_PREFIX . "product_material_make WHERE material_id = '".(int)$material_id."'");
			foreach($data['make'] as $key=>$make_id){
				$this->query("INSERT INTO " . DB_PREFIX . "product_material_make SET material_id = '".$material_id."', make_id = '" .$make_id. "', date_modify = NOW()");
			}
		}
		//(ruchi)
		
		//thickness
		if(isset($data['thickness'])){
			$this->query("DELETE FROM " . DB_PREFIX . "product_material_thickness_price WHERE product_material_id = '".(int)$material_id."'");
			for($i=0;$i<count($data['thickness']['price']);$i++){
				//echo $data['thickness']['from'][$i]."==".$data['thickness']['to'][$i]."==".$data['thickness']['price'][$i]."<br>";	
				$this->query("INSERT INTO " . DB_PREFIX . "product_material_thickness_price SET product_material_id = '".(int)$material_id."', from_thickness = '" .$data['thickness']['from'][$i]. "', to_thickness = '" .$data['thickness']['to'][$i]. "', price = '" .$data['thickness']['price'][$i]. "', date_modify = NOW()");
			}
		}
		
		if(isset($data['new_thickness'])){
			$this->query("DELETE FROM " . DB_PREFIX . "product_material_thickness WHERE product_material_id = '".(int)$material_id."'");
			
			foreach($data['new_thickness'] as $material_thickness){
				$this->query("INSERT INTO " . DB_PREFIX . "product_material_thickness SET product_material_id = '".(int)$material_id."',thickness = '".$material_thickness . "',status=1,date_added=NOW(),date_modify = NOW(),is_delete=0 ");
			}							
		}
		
		if(isset($data['quantity']) && !empty($data['quantity'])){
			$this->query("DELETE FROM " . DB_PREFIX . "product_material_quantity WHERE product_material_id = '".(int)$material_id."'");
			foreach($data['quantity'] as $quantity_id){
				$this->query("INSERT INTO " . DB_PREFIX . "product_material_quantity SET product_material_id = '".(int)$material_id."', product_quantity_id = '".$quantity_id . "', date_added=NOW()");
			}
		}
		
	}
	
	public function getTotalMaterial($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_material` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['material'])){
				$sql .= " AND material_name LIKE '%".$filter_data['material']."%' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}		
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getMaterials($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_material` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['material'])){
				$sql .= " AND material_name LIKE '%".$filter_data['material']."%' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}		
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY material_name";	
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
	
	public function getMaterial($material_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_material` WHERE product_material_id = '" .(int)$material_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getMaterialLayer($material_id){
		$sql = "SELECT GROUP_CONCAT(layer_id) as layer_ids FROM `" . DB_PREFIX . "product_layer_material` WHERE material_id = '" .(int)$material_id. "' GROUP BY material_id";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['layer_ids'];
		}else{
			return false;
		}
	}
	
	//(ruchi)
	public function getMaterialEffect($material_id){
		$sql = "SELECT GROUP_CONCAT(effect_id) as effect_ids FROM `" . DB_PREFIX . "product_printing_effect` WHERE material_id = '" .(int)$material_id. "' GROUP BY material_id";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['effect_ids'];
		}else{
			return false;
		}
	}
	
	public function getMaterialMake($material_id){
		$sql = "SELECT GROUP_CONCAT(make_id) as make_ids FROM `" . DB_PREFIX . "product_material_make` WHERE material_id = '" .(int)$material_id. "' GROUP BY material_id";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['make_ids'];
		}else{
			return false;
		}
	}
	//(ruchi)
	
	public function getMaterialThickness($material_id){
		$sql = "SELECT * FROM product_material_thickness_price WHERE product_material_id = '".(int)$material_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getMaterialNewThickness($material_id){
		$sql = "SELECT * FROM product_material_thickness WHERE product_material_id = '".(int)$material_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function updateMaterialStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "product_material` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_material_id = '".$id."' ";
		$this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "product_material` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_material_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "product_material` SET is_delete = '1', date_modify = NOW() WHERE product_material_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getActiveLayer(){
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
	
	//ruchi
	public function getActiveEffect(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_effect` WHERE status='1' ";
		$sql .= " ORDER BY effect_name";	
		$sql .= " ASC";
			//	echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveMake(){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY make_name";	
		$sql .= " ASC";
			//	echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			
			return $data->rows;
		}else{
			return false;
		}
	}
	//ruchi
	
	//updated query by [kinjal] on 15/5/2017
	public function getMaterialName($material_id){
		$sql = "SELECT material_name FROM `" . DB_PREFIX . "product_material` WHERE product_material_id = '".(int)$material_id."' AND is_delete=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['material_name'];
		}else{
			return false;
		}
	}
	
	//use in quotation page
	public function getLayerMaterial($layer){
		$sql = "SELECT material_id FROM `" . DB_PREFIX . "product_layer_material` WHERE layer_id = '".(int)$layer."' ";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			$array = array();
			foreach($data->rows as $data){
				$array[] = array(
					'material_id'	=> $data['material_id'],
					'material_name'  => $this->getMaterialName($data['material_id'])
				);
			}
			$sortArray = sortAssociateArrayByKey($array,'material_name',SORT_ASC);
			return $sortArray;
		}else{
			return false;
		}
	}
	
	//ruchi (select material according to make)
	public function getLayerMakeMaterial($layer,$make){
		//		$sql = "SELECT material_id FROM `" . DB_PREFIX . "product_layer_material` WHERE layer_id = '".(int)$layer."' ";

		$sql = "SELECT lm.material_id FROM `" . DB_PREFIX . "product_layer_material` AS lm ,`" . DB_PREFIX . "product_material_make` AS m WHERE layer_id = '".(int)$layer."' AND lm.material_id=m.material_id AND m.make_id='".$make."'";
		//echo $sql;
	$data = $this->query($sql);
		if($data->num_rows){
			$array = array();
			foreach($data->rows as $data){
				$array[] = array(
					'material_id'	=> $data['material_id'],
					'material_name'  => $this->getMaterialName($data['material_id'])
				);
			}
			$sortArray = sortAssociateArrayByKey($array,'material_name',SORT_ASC);
			return $sortArray ;
		}else{
			return false;
		}
	}
	
	public function getQuantitys(){
		$data = $this->query("SELECT product_quantity_id, quantity FROM `" . DB_PREFIX . "product_quantity` WHERE status = '1'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getMaterialQuantity($material_id){
		$data = $this->query("SELECT product_quantity_id FROM product_material_quantity WHERE product_material_id = '".(int)$material_id."'");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getQuantity()
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."roll_quantity WHERE quantity_type = 'kg' AND status = '1' AND is_delete = '0' ORDER BY quantity ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
}
?>