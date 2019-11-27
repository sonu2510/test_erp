<?php
class catalogue_category extends dbclass{	
    
	public function getActiveProduct(){
		//[kinjal] : add 24 id to hide ultra product in product selection (17-5-2016)
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND  is_delete = '0'  ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){ 
			return $data->rows;
		}else{ 
			return false;
		}
	}
	public function getProductSize($product_id)
	{
		$sql  = "SELECT  * FROM size_master as s,product_zipper as pz  WHERE s.product_id = '".$product_id."'  AND  s.product_zipper_id=pz.product_zipper_id  ORDER BY  s.width ASC"; 	
		$data = $this->query($sql);
	//   echo $sql;
		if($data->num_rows)
		{
			return $data->rows;
		}else{ 
			return false;
		}
	}
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

	public function getCatalogue_categoryDetails($catalogue_category_id){ 
		$sql = "SELECT * FROM `" . DB_PREFIX . "catalogue_category`  where catalogue_category_id='".$catalogue_category_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function addcatalogue_category($data){  
	    if(isset($data['size_master_id'])&& !empty($data['size_master_id']))
    	{
    	    $size = implode(",",$data['size_master_id']);
    	} 
    	if(isset($data['color'])&& !empty($data['color']))
    	{
    	    $color = implode(",",$data['color']);
    	}
        $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');     
        
		$sql = "INSERT INTO `" . DB_PREFIX . "catalogue_category` SET catalogue_category_name = '" .strip_tags($data['catalogue_category_name']). "',product_id ='".$data['product']."',color_catagory_id ='". $data['color_catagory_id']."',size_master_id = '".$size."',color = '".$color."', status = '".$data['status']. "', date_added ='".date('Y-m-d')."' ,date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0" ;
 		$data = $this->query($sql);
	
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	} 
	public function Updatecatalogue_category($data,$catalogue_category_id){ 
	 
	    if(isset($data['size_master_id'])&& !empty($data['size_master_id']))
    	{
    	    $size = implode(",",$data['size_master_id']);
    	}
    	if(isset($data['color'])&& !empty($data['color']))
    	{
    	    $color = implode(",",$data['color']); 
    	}
        $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');     
        
		$sql = " UPDATE `" . DB_PREFIX . "catalogue_category` SET catalogue_category_name = '" .strip_tags($data['catalogue_category_name']). "',product_id ='".$data['product']."',color_catagory_id ='". $data['color_catagory_id']."',size_master_id = '".$size."',color = '".$color."', status = '".$data['status']. "', date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',updated_by='".$by."',is_delete=0 WHERE catalogue_category_id='".$catalogue_category_id."'" ;
 		$data = $this->query($sql);
	
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	} 
	public function updateStatus($status,$data){ 
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "catalogue_category` SET status = '" .(int)$status. "',  date_modify = '".date('Y-m-d H:i:s')."' WHERE catalogue_category_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "catalogue_category` SET is_delete = '1', date_modify = '".date('Y-m-d H:i:s')."' WHERE catalogue_category_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	 public function getTotalCatalogue_category($filter_data = array()) {
//	      printr($filter_data);//die; 
	    $sql = "SELECT  COUNT(*) as total FROM `" . DB_PREFIX . "catalogue_category` as c ,product as p,color_catagory as cc  where cc.color_catagory_id=c.color_catagory_id AND c.is_delete=0 AND  c.product_id=p.product_id  AND c.status = '1'   ";
       
        if (!empty($filter_data)) {
                if (!empty($filter_data['catalogue_category_name'])) {
                    $sql .= " AND c.catalogue_category_name LIKE '%" . $filter_data['catalogue_category_name'] . "%' ";
                }
      	    	if (!empty($filter_data['color_catagory_id'])) {
                    $sql .= " AND c.color_catagory_id LIKE '%" . $filter_data['color_catagory_id'] . "%' ";
                }
                 if ($filter_data['product_id'] != '') {
                    $sql .= " AND c.product_id = '" . $filter_data['product_id'] . "' ";
                }
		
         }
        $data = $this->query($sql);
        return $data->row['total'];
    }
	public function getCatalogue_category($option,$filter_data){
	    
	    //printr($filter_data);//die; 
	    $sql = "SELECT *,c.status as c_status FROM `" . DB_PREFIX . "catalogue_category` as c ,product as p,color_catagory as cc  where cc.color_catagory_id=c.color_catagory_id AND c.is_delete=0   AND  c.product_id=p.product_id ";
	     if (!empty($filter_data)) {
                if (!empty($filter_data['catalogue_category_name'])) {
                    $sql .= " AND c.catalogue_category_name LIKE '%" .$filter_data['catalogue_category_name'] . "%' ";
                }
      	    	if (!empty($filter_data['color_catagory_id'])) {
                    $sql .= " AND c.color_catagory_id LIKE '%" . $filter_data['color_catagory_id'] . "%' ";
                }
                 if ($filter_data['product_id'] != '') {
                    $sql .= " AND c.product_id = '" . $filter_data['product_id'] . "' ";
                }
         }
        if (isset($option['sort'])) {
			$sql .= " ORDER BY " . $option['sort'];	
		}else{
			$sql .= " ORDER BY c.catalogue_category_id";  
		}
		if (isset($option['order']) && ($option['order'] == 'DESC')) {
			$sql .= " DESC";
		}else{
			$sql .= " ASC";
		}
		if (isset($option['start']) || isset($option['limit'])) {
			if ($option['start'] < 0) {
				$option['start'] = 0;
			}
			if ($option['limit'] < 1) {
				$option['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$option['start'] . "," . (int)$option['limit'];
		}
		 
	//	echo $sql;//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	} 
	
	public function getCategoryColor($color_catagory_id){
	
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE color_category='".$color_catagory_id."' AND  is_delete = 0";
		
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	    
	}
	public function color_name($pouch_color_id){
	
		$sql = "SELECT color FROM `" . DB_PREFIX . "pouch_color` WHERE pouch_color_id='".$pouch_color_id."' AND  is_delete = 0";
		
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['color'];
		}else{
			return false;
		} 
	    
	} 
	public function getSizeData($size_master_id){
	//$sql  = "SELECT * FROM size_master WHERE size_master_id = '".$size_master_id."'   "; 	
		$sql_data  = "SELECT  * FROM size_master as s,product_zipper as pz  WHERE   s.product_zipper_id=pz.product_zipper_id  AND size_master_id = '".$size_master_id."'"; 	
		
	//	echo $sql_data;die;
		$data = $this->query($sql_data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	     
	}
	public function addcatalogue_category_color($data,$catalogue_category_id){  
	  
      $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');     
	   foreach($data as $key=>$size){
	       foreach($size as $key=>$color){
	        $color_data = implode(",",$size[$key]); 
	        $sql = "INSERT INTO `catalogue_category_color` SET size_master_id = '".$key."',catalogue_category_id = '".$catalogue_category_id."',color = '".$color_data."', status = '1', date_added ='".date('Y-m-d')."' ,date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0" ;
	      
	        $data = $this->query($sql);
	       }
	   }
   
	 //   printr($data);die;
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}  
	public function getCategoryColorDetails($catalogue_category_id,$size=''){   
	    $sql_text='';
	    if($size!='')
	        $sql_text=" AND size_master_id='".$size."'";
	   $sql  = "SELECT * FROM catalogue_category_color WHERE catalogue_category_id = '".$catalogue_category_id."' $sql_text "; 
	   $data = $this->query($sql);
	 
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	} 
	public function Updatecatalogue_category_color($data,$catalogue_category_id){ 
	  
      $by = $_SESSION['ADMIN_LOGIN_SWISS']."==".$_SESSION['LOGIN_USER_TYPE']."==".date('Y-m-d H:i:s');   
	
	  $category_color_id=array();$color_data=$category_added_color='';
	
	   foreach($data as $key=>$size){
	     
	       foreach($size as $key=>$color){
    	       
    	         $color_data = implode(",",$size[$key]); 
    	         $catalogue_category_color_id = explode("==",$key);
    	         $category_added_color_arr[] = $catalogue_category_color_id[1]; 
    	         
    	         
            	        if($catalogue_category_color_id[1]!=''){
            	             $sql = " UPDATE `catalogue_category_color` SET size_master_id = '".$key."',catalogue_category_id = '".$catalogue_category_id."',color = '".$color_data."', status = '1',date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0 WHERE catalogue_category_color_id='".$catalogue_category_color_id[1]."'" ;
                    	     $data = $this->query($sql);
            	        }else{
                    	      $sql = "INSERT INTO `catalogue_category_color` SET size_master_id = '".$key."',catalogue_category_id = '".$catalogue_category_id."',color = '".$color_data."', status = '1', date_added ='".date('Y-m-d')."' ,date_modify = '".date('Y-m-d H:i:s')."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0" ;
                    	     $data = $this->query($sql);
            	         }
    	         }  
	        
	 

	   }
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	   
	}  
	public function getCatalogue_category_Color_Details($product_id){ 
		$sql = "SELECT * FROM `" . DB_PREFIX . "catalogue_category`  where product_id='".$product_id."' AND is_delete=0 AND status=1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false; 
		}
	}
	public function getProductcode($size,$color){ 
	    $sql="SELECT * FROM `product_code` as pc,size_master as s,template_measurement as t  WHERE  pc.product = s.product_id AND  t.product_id=pc.measurement AND s.volume =CONCAT(pc.volume,' ',t.measurement) AND pc.zipper = s.product_zipper_id  AND pc.valve='No Valve' AND s.size_master_id='".$size."' AND pc.color='".$color."' ";	
	   // echo $sql;
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->row['product_code_id'];
		}else{
			return false;
		}
	}
	public function getdomesticStock($product_code_id)
	{
	//	$sql="SELECT sm.* ,sum(qty) as total_qty,Group_concate FROM domestic_stock AS sm WHERE sm.is_delete=0 AND product_code_id='".$product_code_id."'";
	
	    $sql="SELECT sm.user_id,sm.user_type_id,sum(sm.qty)qty,sm.product_code_id , GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id,GROUP_CONCAT(sm.box_no) grouped_box_id,sm.stock_id,sm.box_no FROM domestic_stock as sm WHERE sm.is_delete=0 AND  sm.product_code_id ='".$product_code_id."' AND parent_id=0  AND sm.qty!=0";
	
		$data=$this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	}
	public function getdomesticStockDispatch($product_code_id)
	{

	    $sql="SELECT SUM(dispatch_qty) as total FROM domestic_stock WHERE product_code_id IN (" .$product_code_id. ")";
	   // echo $sql;
		$data=$this->query($sql);
	
		if($data->num_rows)
		{
			return $data->row['total'];
		}
		else
		{
			return false;
		}
	}


}
?>