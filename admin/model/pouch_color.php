<?php
class pouchColor extends dbclass{
	//sonu add field 14/11/16
    public function addColor($data){
	//printr($data);die;
	
    if(isset($data['product'])&& !empty($data['product']))
	{
	    $product = implode(",",$data['product']);
	}  if(isset($data['make'])&& !empty($data['make']))
	{
	    $make = implode(",",$data['make']);
	}	
		$this->query("INSERT INTO `" . DB_PREFIX . "pouch_color` SET color = '" .strip_tags($data['color']). "',product_id ='".$product."',make_id ='". $make ."',pouch_color_abbr = '".$data['abbrevation']."', color_value='".$data['colorvalue']."', email_color = '" .$data['email_color']. "', color_category = '" .$data['color_category']. "', status = '" .$data['status']. "', date_added = NOW(),date_modify = NOW(),is_delete=0");
		$color_id = $this->getLastId();
		return $color_id;
	}
	
	public function updateColor($color_id,$data){
	
	
	 if(isset($data['product'])&& !empty($data['product']))
    	{
    	    $product = implode(",",$data['product']);
    	}  
    if(isset($data['make'])&& !empty($data['make']))
    	{
    	    $make = implode(",",$data['make']);
    	}
	//	printr($data);die;
		$sql = "UPDATE `" . DB_PREFIX . "pouch_color` SET color = '" .strip_tags($data['color']). "',product_id ='".$product."',make_id ='". $make."',pouch_color_abbr = '".$data['abbrevation']."', color_value='".$data['colorvalue']."', email_color = '" .$data['email_color']. "',color_category = '" .$data['color_category']. "',status = '" .$data['status']. "',  date_modify = NOW() WHERE pouch_color_id = '" .(int)$color_id. "'";
	//	echo $sql;die;
		//layer = '".serialize($data['layer'])."',
		$this->query($sql);		
	}
	
	public function getTotalColor($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "pouch_color` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['color'])){
				$sql .= " AND color LIKE '%".$filter_data['color']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getColors($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['color'])){
				$sql .= " AND color LIKE '%".$filter_data['color']."%' ";		
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pouch_color_id";	
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
	
	public function getColor($color_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE pouch_color_id = '" .(int)$color_id. "'";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	public function updateColorStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "pouch_color` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE pouch_color_id = '".$id."' ";
		$this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "pouch_color` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE pouch_color_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "pouch_color` SET is_delete = '1', date_modify = NOW() WHERE pouch_color_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	//manirul 11-2-17-->
	public function color_category(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "color_catagory` where status = '1' ORDER BY color_name ASC  ";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	//sonu add 11-5-2017
	public function getProduct(){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE is_delete ='0' AND status = '1'";
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	//end
	
	//sejal add 13-5-2017
	
	public function getActiveMake(){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY serial_no";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	//end
	public function getMakeName($make_id){
		$sql = "SELECT GROUP_CONCAT(make_name) as name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' AND make_id IN('".$make_id."') ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['name'];
		}else{
			return false;
		}
	}
	//end


    public function getColorCategory($c_id)	{
        
        $sql = "SELECT * FROM `color_catagory` WHERE `color_catagory_id`= '".$c_id."' ";
    //    echo $sql;die;
    	$data = $this->query($sql);
    //	printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
  
    }
}
?>