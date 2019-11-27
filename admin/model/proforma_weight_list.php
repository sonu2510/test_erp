<?php
class proforma_weight_list extends dbclass{

	//sonu 28-02-2018


	public function getTotalActiveProducts(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}

	public function getProducts($data){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		
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
	
	public function getProductCategory($product_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE is_delete = 0 AND status = 1 AND product_id='".$product_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getZipper(){
		$sql = "SELECT * FROM " . DB_PREFIX . "product_zipper WHERE is_delete = 0 ";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getSize($product_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "size_master` WHERE product_id = '" .(int)$product_id. "' GROUP BY volume ORDER BY size_master_id ASC";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function Allcatogery_detail($color_catagory_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "pro_color_catagory` WHERE color_catagory_id = '" .(int)$color_catagory_id. "' ";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}public function getProductWeightDetails($product_id,$category_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "proforma_weight_list` WHERE product_id = '" .(int)$product_id. "' AND category_id='".$category_id."' AND is_delete='0'";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function addWeight($product_id,$data){
		foreach($data['catogory'] as $key=>$details){
			$arr=array();
			foreach($details as $detail){
					$arr=explode("==", $detail['size']);
					$sql = "INSERT INTO `" . DB_PREFIX . "proforma_weight_list` SET product_id='".$product_id."',size_id='".$arr[0]."', volume='".$arr[1]."', weight = '".$detail['weight']."',zipper_id='".$detail['product_zipper_id']."' ,category_id='".$detail['category']."' ,date_added = NOW(), date_modify = NOW()";
					$this->query($sql);
			}//die;
		}
	}
		public function updateWeight($product_id,$data){
	//printr($data);
		foreach($data['catogory'] as $key=>$details){
		    $arr=array();
			foreach($details as $detail){
			//	printr($detail);
				$arr=explode("==", $detail['size']);
				if(isset($detail['weight_id']) && $detail['weight_id']!='')
				{ 
					$sql = "UPDATE `" . DB_PREFIX . "proforma_weight_list` SET product_id='".$product_id."',size_id='".$arr[0]."', volume='".$arr[1]."', weight = '".$detail['weight']."', zipper_id='".$detail['product_zipper_id']."' ,category_id='".$detail['category']."' ,date_added = NOW(), date_modify = NOW() WHERE weight_id='".$detail['weight_id']."'";
					$this->query($sql);
				}
				else{
					$sql = "INSERT INTO `" . DB_PREFIX . "proforma_weight_list` SET product_id='".$product_id."',size_id='".$arr[0]."', volume='".$arr[1]."', weight = '".$detail['weight']."',zipper_id='".$detail['product_zipper_id']."',category_id='".$detail['category']."' ,date_added = NOW(), date_modify = NOW()";
						$this->query($sql);
				}
			
			}//die;
		}

	}
	public function remove_weight_record($weight_id){
		$sql = "UPDATE `" . DB_PREFIX . "proforma_weight_list` SET is_delete='1' WHERE weight_id='".$weight_id."'";
		$this->query($sql);
		
	}
	
		public function InsertCSVData($handle)
	{
		$data=array();
		$first_time = true;
		
		while($data = fgetcsv($handle,1000,","))
		{
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
				$product_id=$data[0];
				$volume=$data[2];
				$zip_id=$data[3];
				$weight=$data[4];
				$category_id=$data[5];
				
				$size = "SELECT size_master_id FROM size_master WHERE product_id = '".$product_id."' AND product_zipper_id = '".$zip_id."' AND volume= '".$volume."'";
				$data_size = $this->query($size);
				$size_id=$data_size->row['size_master_id'];

				$sql = "INSERT INTO `" . DB_PREFIX . "proforma_weight_list` SET product_id = '".$product_id."',size_id = '".$size_id. "',volume = '".$volume."',zipper_id='".$zip_id."',weight ='".$weight."',category_id='".$category_id."',date_added = NOW(),date_modify = NOW(),is_delete=0";		
				$datasql=$this->query($sql);
		}
	}	
		
		
}
?>

