<?php
class stockProfit extends dbclass{
	

	public function getActiveProducts(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE status = 1 AND is_delete = 0");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}			
	}
	

	public function getTotalActiveProducts(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE label_available=1 AND status=1 AND is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	

	public function getProduct($product_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` p WHERE product_id='".$product_id."'";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}

	
	public function getProducts($data){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE label_available=1 AND  status=1 AND is_delete = 0";
		
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
	
	public function getLabelPrintShapes($product_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "lable_shape_master` WHERE status=1 AND is_delete = 0 AND product_ids LIKE '%".$product_id."%'";
	    $data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function add_label_size_master($product_id,$shape_id,$product_category,$data){
	   
	    for($i=0;$i<count($data['size_id']);$i++){
	            $size_details=$this->getSizeData($data['size_id'][$i]);
	            
	        	$sql = "INSERT INTO `" . DB_PREFIX . "label_size_master` SET product_category = '".$product_category."',product_id = '".$product_id."',shape_id = '".$shape_id."',size_master_id = '".$data['size_id'][$i]."',height = '".$size_details['height']."',width = '".$size_details['width']."',gusset = '".$size_details['gusset']."',volume = '".$size_details['volume']."',max_height = '".$data['max_height'][$i]."',max_width = '".$data['max_width'][$i]."',min_height = '".$data['min_height'][$i]."',min_width = '".$data['min_width'][$i]."',status =1,is_delete =0 ";
		        //printr($sql);
		        $this->query($sql);
	    }
	     //printr('asd');
	    //die;
	}
	public function get_label_size_master_details($product_id,$shape_id,$product_category){
	    $sql="SELECT * FROM `" . DB_PREFIX . "label_size_master` WHERE product_id='".$product_id."' AND shape_id='".$shape_id."' AND product_category='".$product_category."' ";
	    $data = $this->query($sql);
	    //printr($shape_id);die;
		if($data->num_rows) {
			return $data->rows;
		} else {
			return false;
		}
	}
	public function update_label_size_master($product_id,$shape_id,$product_category,$data){
	   
	    for($i=0;$i<count($data['label_size_master_id']);$i++){
	            
	        	$sql = "UPDATE  `" . DB_PREFIX . "label_size_master` SET max_height = '".$data['max_height'][$i]."',max_width = '".$data['max_width'][$i]."',min_height = '".$data['min_height'][$i]."',min_width = '".$data['min_width'][$i]."' where label_size_master_id='".$data['label_size_master_id'][$i]."'";
		        //printr($sql);
		        $this->query($sql);
	    }
	     //printr('asd');
	    //die;
	}

	//edited by rohit  
	public function getSize($product_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "stock_profit` WHERE product_id='".$product_id."' and quantity_id =1");
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
	}

	public function getSizeData($size_master_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "size_master` WHERE status=0 AND size_master_id='".$size_master_id."'");
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
			
	}
	//rohit
	public function ZipperData ($product_zipper_id) {
		$sql = "select * from ".DB_PREFIX."product_zipper where product_zipper_id='".$product_zipper_id."'";
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->row;
		} else {
			return false;
		}
	}
	//sonu
	public function Size_detail($size_master_id){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "size_master` WHERE status=0 AND size_master_id='".$size_master_id."'");
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
}
?>