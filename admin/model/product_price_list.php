<?php
class pricelist extends dbclass{	
	public function InsertCSVData($handle)
	{
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$data=array();
		$first_time = true;
		$sql_delete = "TRUNCATE TABLE product_price_list";
		$data_sql=$this->query($sql_delete);
	  	while($data = fgetcsv($handle,1000,","))
		{
			///printr($first_time);//die;
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
				
			//changed by jaya on 22-12-2016 for the import because size_id is inserted in the excel itself
			
			$zip_id=$data[4];
			$spout_id=$data[5];
			$acce_id=$data[3];
			$product_id=$data[1];
			$product_name=$data[2];
			//changed by jaya 0n 22-12-2016 
			$accessorie_id=$acce_id;
			$zipper_id=$zip_id;
			$spout=$spout_id;
			
			$volume=$data[7];
			$width=$data[8];
			$height=$data[9];
			$gusset=$data[10];
			$all_price=$data[11];
			$clear_price=$data[12];
			$birdegrable_price=$data[13];
			$ultra_clear_price=$data[14];
			$sup_zz_oval_window=$data[15];
			$stripped_bkp_look_zz=$data[16];
			$sup_zz_jtk=$data[17];
			$sup_bkp_zz=$data[18];
			$sup_bkp_zz_oval_window=$data[19];
			$sup_bkp_whp_zz_full_rec_win=$data[20];
			$sup_zz_clear_bkp=$data[21];
			$sup_crystal_clear_price=$data[22];
			$sup_whp_zz=$data[23];
			$sup_gp_bp_zz=$data[24];
			$sup_gp_bp_zz_full_rect=$data[25];
			
			//changed by jaya on 22-12-2016 for the import because size_id is inserted in the excel itself
			//add field in price_list update 3-2-2017
			$si_id=$this->getsizevalue($product_id,$zipper_id,$volume,$width,$height,$gusset);
			$size_id=$si_id['size_master_id'];
		
		
//Notice: Error : Unknown column 'sup_whp_zz' in 'field list'
               
			$sql = "INSERT INTO " . DB_PREFIX . "product_price_list SET product_id= '".$product_id."',product_name = '" .$product_name. "',	accessorie_id= '".$accessorie_id."',zipper_id='".$zipper_id."',spout_id='".$spout."',size_id='".$size_id."',volume='".$volume."',width='".$width."',height='".$height."',gusset='".$gusset."',all_clr_price='".$all_price."',clear_price = '".$clear_price."', biodegradable_price = '".$birdegrable_price."',ultra_clear_price='".$ultra_clear_price."',sup_zz_oval_window='".$sup_zz_oval_window."',stripped_bkp_look_zz= '".$stripped_bkp_look_zz."',sup_zz_jtk= '".$sup_zz_jtk."',sup_bkp_zz = '".$sup_bkp_zz."',sup_bkp_zz_oval_window = '".$sup_bkp_zz_oval_window."',sup_bkp_whp_zz_full_rec_win= '".$sup_bkp_whp_zz_full_rec_win."',sup_zz_clear_bkp= '".$sup_zz_clear_bkp."',sup_whp_zz = '".$sup_whp_zz."',sup_crystal_clear_price='".$sup_crystal_clear_price."',sup_gp_bp_zz='".$sup_gp_bp_zz."',sup_gp_bp_zz_full_rect='".$sup_gp_bp_zz_full_rect."',status = 1,date_added = NOW(),date_modify = NOW(),user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',is_delete=0";		
		
		$data=$this->query($sql);//die;
		}			
	}
   	public function getzippervalue($zip_nm)
	{
				$sql="SELECT product_zipper_id FROM product_zipper WHERE zipper_name = '".$zip_nm."'";
				$data = $this->query($sql);
				if($data->num_rows){
					return $data->row;
				}else{
					return false;
				}
	}
	
	public function getspoutvalue($sp_num)
	{
				$sql="SELECT product_spout_id FROM product_spout WHERE spout_name = '".$sp_num."'";
				$data=$this->query($sql);
				if($data->num_rows){
					return $data->row;
				}
				else{
					return false;
				}
	}
	public function getaccessorievalue($ae_num)
	{
					$sql="SELECT product_accessorie_id FROM product_accessorie WHERE product_accessorie_name='".$ae_num."'";
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
	public function getsizevalue($product_id,$zipper_id,$volume,$width,$height,$gusset)
	{  			
			$sql="SELECT size_master_id FROM size_master WHERE product_id='".$product_id."' AND product_zipper_id='".$zipper_id."' AND volume LIKE '%".$volume."%' AND width='".$width."' AND height='".$height."' AND gusset='".$gusset."'";
			$data=$this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
		}
	}
	
	public function getTotalProducts()
	{
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getProducts($data)
	{
		
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
	public function getProduct($product_id){
		
			$sql = "SELECT * FROM `" . DB_PREFIX . "product` p WHERE product_id='".$product_id."'";
			
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}
	public function getProductPrices($product_id)
		{
			
			$sql = "SELECT p.*,pz.zipper_name,pa.product_accessorie_name,ps.spout_name FROM `" . DB_PREFIX . "product_price_list` as p, product_zipper as pz,product_accessorie as pa,product_spout as ps WHERE p.product_id = '" .(int)$product_id. "' AND p.zipper_id=pz.product_zipper_id AND pa.product_accessorie_id= p.accessorie_id AND ps.product_spout_id=p.spout_id AND p.is_delete=0";
			$data = $this->query($sql);
	       
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
			
			
		}
		public function getProforma_ProductPrices($product_id,$user_id)
		{
			
			$sql = "SELECT p.*,cc.color_name,pm.measurement,pz.zipper_name,pa.product_accessorie_name,ps.spout_name from proforma_price_list as p, product_zipper as pz,product_accessorie as pa,product_spout as ps, template_measurement as pm,color_catagory as cc where p.product_id = '" .(int)$product_id. "' AND p.category_id=cc.color_catagory_id AND p.zipper_id=pz.product_zipper_id AND pa.product_accessorie_id= p.accessorie_id AND ps.product_spout_id=p.spout_id AND pm.product_id=p.measurement AND p.user_id='".$user_id."' AND p.is_delete=0";
			$data = $this->query($sql);
	       
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
			
			
		}
	
	public function getProductvalue($product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product`  WHERE product_id='".$product_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function remove_product_record($product_id)
	{
		$this->query("DELETE FROM `" . DB_PREFIX . "product_price_list`  WHERE product_id='".$product_id."'");
	}
	
	// Add function for Proforma price list 24-8-2017  [sonu]
	
	
		public function getmeasurementvalue($product_id)
	{
		$sql = "SELECT measurement FROM `" . DB_PREFIX . "template_measurement`  WHERE product_id='".$product_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
		public function getTotalBranch($filter_array=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id) WHERE ib.is_delete = '0'";
		
		if(!empty($filter_array)){						
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}				
			if($filter_array['status'] != ''){
				$sql .= " AND ib.status = '".$filter_array['status']."' ";
			}			
			if(!empty($filter_array['name'])){
				$sql .= " AND CONCAT(ib.first_name,' ',ib.last_name) LIKE '%".$this->escape($filter_array['name'])."%'";
			}									
		}
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getBranchs($data = array(),$filter_array=array()){
		$sql = "SELECT *,CONCAT(ib.first_name,' ',ib.last_name) as name,am.email FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id)  WHERE ib.is_delete = '0'";

		if(!empty($filter_array)){			
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}			
			if($filter_array['status'] != ''){
				$sql .= " AND ib.status = '".$filter_array['status']."' ";
			}			
			if(!empty($filter_array['name'])){
								$sql .= " HAVING name LIKE '%".$this->escape($filter_array['name'])."%'" ;
			}							
		}		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY international_branch";	
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

	public function getdefaultcurrencyCode($default_curr)
	{
		$sql = "SELECT currency_code FROM country where status = 1 and currency_code!='' and country_id = '".$default_curr."' LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row['currency_code'];
		}
		else
		{
			return false;
		}
	}
	
	public function getCountryName($country_id)
	{
		$sql = "SELECT country_name FROM country where status = 1 and country_code!='' and currency_code!='' and country_id = '".$country_id."' LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row['country_name'];
		}
		else
		{
			return false;
		}		
	}
	
	public function InsertCSVDataAllCountry($handle)
	{
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$data=array();
		$first_time = true;
		
	  	while($data = fgetcsv($handle,1000,","))
		{ //printr($data);die;
			if ($first_time == true) {
				$first_time = false;
				$data_sql=$this->query("SELECT country_id FROM proforma_price_list WHERE country_id = '".$data[23]."'");
				if($data_sql->num_rows)
				{
					$data_sql=$this->query("DELETE FROM proforma_price_list WHERE country_id = ".$data_sql->row['country_id']);
				}
				continue;
			}
			//changed by jaya on 22-12-2016 for the import because size_id is inserted in the excel itself
			//printr($data);
			$product_id=$data[0];
			$category_id=$data[1];
			$accessorie_id=$data[2];
			$zipper_id=$data[3];
			$spout_id=$data[4];			
			//$size_id=$data[6];
			$volume=$data[6];
			$measurement=$data[7];	
			
			$mea =$this->getmeasurementvalue($measurement);			
		
			$valve=$data[8];			
			$quantity100=$data[9];
			$quantity200=$data[10];
			$quantity500=$data[11];
			$quantity1000=$data[12];
			$quantity2000=$data[13];
			$quantity5000=$data[14];			
			$quantity10000=$data[15];			
			$air_quantity100=$data[16];
			$air_quantity200=$data[17];
			$air_quantity500=$data[18];
			$air_quantity1000=$data[19];		
			$air_quantity2000=$data[20];
			$air_quantity5000=$data[21];			
			$air_quantity10000=$data[22];
			$country_id=$data[23];			
			$width=$data[24];
			$height=$data[25];
			$gusset=$data[26];
			
			$user_id=$data[28];
			$user_type_id=$data[29];
			//$vol=$volume.' '.$mea['measurement'];
			$vol=$data[30];
			$status=$data[31];//1 for expansive price & 0 for cheap price
			//changed by jaya on 22-12-2016 for the import because size_id is inserted in the excel itself
			//add field in price_list update 3-2-2017
		//printr($product_id.'=='.$zipper_id.'=='.$vol.'=='.$width.'=='.$height.'=='.$gusset);
			$si_id=$this->getsizevalue($product_id,$zipper_id,$vol,$width,$height,$gusset);
			
			$size_id=$si_id['size_master_id'];
			
			
			//printr($sql);
			$sql = "INSERT INTO " . DB_PREFIX . "proforma_price_list SET product_id= '".$product_id."',category_id = '" .$category_id. "',accessorie_id= '".$accessorie_id."',zipper_id='".$zipper_id."',spout_id='".$spout_id."',size_id='".$size_id."',volume='".$volume."',measurement='".$measurement."',width='".$width."',height='".$height."',gusset='".$gusset."',valve='".$valve."',quantity100='".$quantity100."',quantity200 = '".$quantity200."', quantity500 = '".$quantity500."',quantity1000='".$quantity1000."',quantity5000= '".$quantity5000."',quantity10000= '".$quantity10000."',air_quantity100='".$air_quantity100."',air_quantity200 = '".$air_quantity200."', air_quantity500 = '".$air_quantity500."',air_quantity1000='".$air_quantity1000."',quantity2000='".$quantity2000."',air_quantity2000='".$air_quantity2000."',air_quantity5000= '".$air_quantity5000."',air_quantity10000= '".$air_quantity10000."',country_id= '".$country_id."',date_added = NOW(),date_modify = NOW(),user_id='".$user_id."',user_type_id='".$user_type_id."',is_delete=0,price_status='".$status."'";		
			//printr($sql);
			$data=$this->query($sql);//die;
		}
		//die;
	}
	
	//end
	public function update_price($price_list_id,$width,$height,$gusset,$all_clr_price,$clear_price,$biodegradable_price,$ultra_clear_price,$sup_zz_oval_window,$stripped_bkp_look_zz,$sup_zz_jtk,$sup_bkp_zz,$sup_bkp_zz_oval_window,$sup_bkp_whp_zz_full_rec_win,$sup_zz_clear_bkp,$sup_whp_zz,$sup_crystal_clear_price,$sup_gp_bp_zz,$sup_gp_bp_zz_full_rect)
	{
		$this->query("UPDATE product_price_list SET width='".$width."',height='".$height."',gusset='".$gusset."',all_clr_price='".$all_clr_price."',clear_price = '".$clear_price."', biodegradable_price = '".$biodegradable_price."',ultra_clear_price='".$ultra_clear_price."',sup_zz_oval_window='".$sup_zz_oval_window."',stripped_bkp_look_zz= '".$stripped_bkp_look_zz."',sup_zz_jtk= '".$sup_zz_jtk."',sup_bkp_zz = '".$sup_bkp_zz."',sup_bkp_zz_oval_window = '".$sup_bkp_zz_oval_window."',sup_bkp_whp_zz_full_rec_win= '".$sup_bkp_whp_zz_full_rec_win."',sup_zz_clear_bkp= '".$sup_zz_clear_bkp."',sup_whp_zz = '".$sup_whp_zz."',sup_crystal_clear_price='".$sup_crystal_clear_price."',sup_gp_bp_zz='".$sup_gp_bp_zz."',sup_gp_bp_zz_full_rect='".$sup_gp_bp_zz_full_rect."',date_modify = NOW() WHERE 	price_list_id ='".$price_list_id."'");
	}
	
	public function update_proforma_price($price_list_id,$width,$height,$gusset,$quantity100,$quantity200,$quantity500,$quantity1000,$quantity2000,$quantity5000,$quantity10000,$air_quantity5000,$air_quantity10000,$air_quantity100,$air_quantity200,$air_quantity500,$air_quantity1000,$air_quantity2000)
	{
	   $this->query("UPDATE proforma_price_list SET width='".$width."',height='".$height."',gusset='".$gusset."',quantity100='".$quantity100."',quantity200='".$quantity200."',quantity500='".$quantity500."',quantity1000='".$quantity1000."',quantity2000='".$quantity2000."',quantity5000='".$quantity5000."',quantity10000='".$quantity10000."',air_quantity5000='".$air_quantity5000."',air_quantity10000='".$air_quantity10000."',air_quantity100='".$air_quantity100."',air_quantity200='".$air_quantity200."',air_quantity500='".$air_quantity500."',air_quantity1000='".$air_quantity1000."',air_quantity2000='".$air_quantity2000."',date_modify = NOW() WHERE 	price_id ='".$price_list_id."'");
	}
    public function Addproforma_price_qty($post,$admin_user_id)
	{
	   // printr($post);die;
	    
	    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		foreach($post['qty'] as $qty){
	    $sql = "INSERT INTO " . DB_PREFIX . "proforma_price_qty_master SET 	from_qty= '".$qty['from_qty']."',	to_qty = '" .$qty['to_qty']. "',field= '".implode(',',$qty['field'])."',	admin_user_id='".$admin_user_id."',date_added = NOW(),date_modify = NOW(),user_id='".$user_id."',user_type_id='".$user_type_id."',is_delete=0";		
	    	$this->query($sql);
		}	
	} 
	 public function Updateproforma_price_qty($post,$admin_user_id)
	{
	 
	 //printr($post);die;
	     
	    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		foreach($post['qty'] as $qty){
		  
		 if(isset($qty['price_qty_id']) && $qty['price_qty_id']!=''){
		      $sql_update = " UPDATE " . DB_PREFIX . "proforma_price_qty_master SET 	from_qty= '".$qty['from_qty']."',	to_qty = '" .$qty['to_qty']. "',field= '".implode(',',$qty['field'])."',	admin_user_id='".$admin_user_id."',date_modify = NOW(),user_id='".$user_id."',user_type_id='".$user_type_id."',is_delete=0 WHERE  price_qty_id='".$qty['price_qty_id']."'";	
		  
		    $this->query($sql_update);
		     
		 }  else{
	        $sql = "INSERT INTO " . DB_PREFIX . "proforma_price_qty_master SET 	from_qty= '".$qty['from_qty']."',	to_qty = '" .$qty['to_qty']. "',field= '".implode(',',$qty['field'])."',	admin_user_id='".$admin_user_id."',date_added = NOW(),date_modify = NOW(),user_id='".$user_id."',user_type_id='".$user_type_id."',is_delete=0";		
	        $this->query($sql);
	   	  }	
	          
		}	
	} 
	public function remove_row($price_qty_id)
	{
	    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	
		$sql_update = " UPDATE " . DB_PREFIX . "proforma_price_qty_master SET is_delete='1',date_modify = NOW(),user_id='".$user_id."',user_type_id='".$user_type_id."' WHERE  price_qty_id='".$price_qty_id."'";	
	    printr($sql_update);
		$this->query($sql_update);
	
	} 
	public function getproforma_price_qty($admin_user_id)
	{
	   // printr($post);die;
	    
	   	$sql = "SELECT * FROM proforma_price_qty_master where is_delete = 0 AND  admin_user_id='".$admin_user_id."'";
		$data = $this->query($sql);
		if($data->num_rows)
		{
		    return $data->rows;
		}
		else
		{
			return false;
		}		
	}
	public function getTableStructure()
	{
	   // printr($post);die;
	    
	   	$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'proforma_price_list' AND COLUMN_NAME LIKE '%quantity%' ";
		$data = $this->query($sql);
		if($data->num_rows)
		{
		    return $data->rows;
		}
		else
		{
			return false;
		}		
	}
	public function getProforma_ProductPricesForReport($user_id,$price_status=0)
	{
	
		$sql = "SELECT p.*,cc.color_name,pm.measurement as mea,pz.zipper_name,pa.product_accessorie_name,ps.spout_name,pp.product_name from proforma_price_list as p, product_zipper as pz,product_accessorie as pa,product_spout as ps, template_measurement as pm,color_catagory as cc, product as pp where p.category_id=cc.color_catagory_id AND p.zipper_id=pz.product_zipper_id AND pa.product_accessorie_id= p.accessorie_id AND ps.product_spout_id=p.spout_id AND pm.product_id=p.measurement AND p.user_id='".$user_id."' AND p.is_delete=0 AND p.product_id = pp.product_id AND p.price_status = '".$price_status."' ORDER BY  FIELD(p.product_id,'3','66','22','1','7','19','20','24','27','10','35','12','42', '53', '13', '16', '30', '31', '54', '50', '36', '26', '11', '18','4','40','41','14','15','63','62','28','48','47','61','5','9','37', '38','8'),p.zipper_id,p.spout_id,CONCAT(p.volume,' ',pm.measurement)  + 0   ASC";
		$data = $this->query($sql);
        //printr($sql);
        //printr($data);
        if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		
		
	}
	public function getDataOFReport($post,$n=0)
	{
		if($post)
		{
    		$coun = "SELECT country_name FROM country WHERE country_id =".$post[0]['country_id'];
    	    $data_con = $this->query($coun);
    	    
    	    $valve_p = "SELECT stock_valve_price,product_rate FROM international_branch WHERE international_branch_id =".$post[0]['user_id'];
    	    $v_price = $this->query($valve_p);
    	    $sel_currency_rate=1;
    	    /*if(isset($post['sel_currency']))
    	        $data->row['currency_code'] = $post['sel_currency'];
    	    if(isset($post['sel_currency_rate']))
    	        $sel_currency_rate = $post['sel_currency_rate'];*/
    	    $array = array();    
    	    foreach($post as $row)
            {
                $array[$row['product_name'].'=='.$row['product_id'].'=='.$row['spout_name'].'=='.$row['product_accessorie_name'].'=='.$row['zipper_name']][$row['zipper_name'].' - '.strtolower($row['volume'].' '.$row['mea'])][rtrim($row['color_name'])] = $row;
            }
            //printr($array);
            foreach($array as $arrr)
    	    {
    	           
        		        foreach($arrr as $mea=>$d)
            	        {
            	            foreach($d as $title=>$dd)
                            { //printr($dd['quantity100']);
                                 $array_new[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout_name'].'=='.$dd['product_accessorie_name'].'=='.$dd['zipper_name']][$dd['zipper_name'].' - '.strtolower($dd['volume'].' '.$dd['mea'])][rtrim($dd['color_name'])] = 
            	                array(   'currency_code'=>$dd['currency_code'],
            	                         'size'=>$dd['width']." (W) X ".$dd['height']." (H) X ".$dd['gusset']." (G)",
            	                         'quantity100'=>$dd['quantity100'],
            	                         'quantity200'=>$dd['quantity200'],
            	                         'quantity500'=>$dd['quantity500'],
            	                         'quantity1000'=>$dd['quantity1000'],
            	                         'quantity2000'=>$dd['quantity2000'],
            	                         'quantity5000'=>$dd['quantity5000'],
            	                         'quantity10000'=>$dd['quantity10000'],
            	                         
            	                         'quantity100_air'=>$dd['air_quantity100'],
            	                         'quantity200_air'=>$dd['air_quantity200'],
            	                         'quantity500_air'=>$dd['air_quantity500'],
            	                         'quantity1000_air'=>$dd['air_quantity1000'],
            	                         'quantity2000_air'=>$dd['air_quantity2000'],
            	                         'quantity5000_air'=>$dd['air_quantity5000'],
            	                         'quantity10000_air'=>$dd['air_quantity10000'],
            	                         
            	                        );
                            }
            	        }
        			
        	}
        	$html = "";//".$data->row['currency_code']."
    	    $html .="<div class='form-group'>
    					<div class='table-responsive'>
    						<span><h4><b>Prices in ".$post[0]['currency_code']." for ".$data_con->row['country_name'].".</b></h4></span>
    						<table class='table table-striped b-t text-small' style='width:50 %' border='1'>
    							<thead>";
    							$k=1;
    							    foreach($array_new as $p_nm=>$arr)
    							    {  // printr($arr);
    							        $p_detail = explode("==",$p_nm);
    							        $date ='';
        							        $spout = $acc = $zip=''; 
        							        
        							        if($post['report']=='By Sea' || $post['report']=='By Air' || $post['report']=='By Pickup')
                                                $no ='1';
                                            else
                                                $no ='2'; 
                                                
        							        if($k=='1')
        							            $date = '( '.dateFormat(4,date("Y-m-d")).' )';
        							        else
        							        {
        							            $html .="<tr><th colspan='".((7*$no)+4)."'></th></tr><tr><th colspan='".((7*$no)+4)."'></th></tr>";
        							        
                							       if($p_detail[2]!='No Spout')
                							            $spout = 'With '.$p_detail[2];
                							       if($p_detail[3]!='No Accessorie')
                							            $acc = 'With '.$p_detail[3];
                							       if($p_detail[4]!='No zip')
                							            $zip = $p_detail[4];
        							        }
        							        $html .="<tr><th colspan='".((7*$no)+4)."'>".$p_detail[0]." ".$date." ".$spout." ".$acc." ".$zip." </th></tr>
                    							     <tr>
                                                          <th>SR. NO.</th>
                                                          <th>CAPACITY</th>
                                                          <th>(POUCH SIZE)</th>
                                                          <th><center>DESCRIPTION</center></th>";
                                                          if($post[0]['country_id']!='111')
                                                          {
                                                              $html .="<th colspan='7'><center>By Sea</center></th>";
                                                              $html .="<th colspan='7'><center>By Air</center></th>";
                                                          }
                                                          else
                                                          {
                                                              $html .="<th colspan='7'></th>";
                                                          }
                                                          /*if($post['report']=='By Pickup')
                                                            $html .="<th colspan='".$colspan."'><center>By Pickup</center></th>";*/
                                             $html .="</tr>";
                                            $html .="<tr>
                                                         <th></th>
                                                          <th></th>
                                                          <th></th>
                                                          <th></th>";
                                                          $html .="<th >Price ( ".$post[0]['currency_code']." )  Qty100+</th>
        										                   <th >Price ( ".$post[0]['currency_code']." )  Qty200+</th>
        										                   <th >Price ( ".$post[0]['currency_code']." )  Qty500+</th>
        										                   <th >Price ( ".$post[0]['currency_code']." )  Qty1000+</th>
        										                   <th >Price ( ".$post[0]['currency_code']." )  Qty2000+</th>
        										                   <th >Price ( ".$post[0]['currency_code']." )  Qty5000+</th>
        										                   <th >Price ( ".$post[0]['currency_code']." )  Qty10000+</th>";
        										          if($post[0]['country_id']!='111')
        										          {
            										          $html .="<th >Price ( ".$post[0]['currency_code']." )  Qty100+</th>
            										                   <th >Price ( ".$post[0]['currency_code']." )  Qty200+</th>
            										                   <th >Price ( ".$post[0]['currency_code']." )  Qty500+</th>
            										                   <th >Price ( ".$post[0]['currency_code']." )  Qty1000+</th>
            										                   <th >Price ( ".$post[0]['currency_code']." )  Qty2000+</th>
            										                   <th >Price ( ".$post[0]['currency_code']." )  Qty5000+</th>
            										                   <th >Price ( ".$post[0]['currency_code']." )  Qty10000+</th>";
        										          }
        									$html .="</tr>";
        									$j=1;
        									foreach($arr as $mea=>$d)
    						    	        {
    						    	            $count=count($d);//printr($count);
    						    	            foreach($d as $title=>$dd)
    			    	                        {
    			    	                            $size =$dd['size'];  
    			    	                        }
    						    	            $html .="<tr>
    						    	                        <th style='vertical-align: top' style='vertical-align: top' rowspan='".$count."'>".$j."</th>
    						    	                        <th style='vertical-align: top' rowspan='".$count."'>".$mea."</th>";
    						    	               $html .="<th style='vertical-align: top' rowspan='".$count."'>".$size."</th>";
    						    	               $st='';
    			    	                            if($n==1)
    			    	                            {
    				    	                            $st='style="font-size:7px;"';
    			    	                            }
    			    	                            foreach($d as $title=>$dd)
    				    	                        {//printr($title);
    			    	                                      $html .="<td ".$st.">".$title."</td>";
    			    	                                      $html .="<td>".$dd['quantity100']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity200']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity500']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity1000']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity2000']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity5000']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity10000']*$sel_currency_rate."</td>";
    	    	                                                     if($post[0]['country_id']!='111')
    										                        {
    	    	                                                      $html .="<td>".$dd['quantity100_air']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity200_air']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity500_air']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity1000_air']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity2000_air']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity5000_air']*$sel_currency_rate."</td>
    	    	                                                      <td>".$dd['quantity10000_air']*$sel_currency_rate."</td>";
    										                        }
    	    	                                      $html .="</tr>";  
    			    	                                       
    				    	                        }
    				    	                    $html .="</tr>"; 
    				    	                    $j++;
    						    	        }
                                        $k++;
    							    }
    							    $html .="</thead>
                            </table>
                        </div>
                    </div>
                    <style>
                        table td {width:1%;}
                        table th {width:1%;}
                    </style>";
		}
		else
		{
		    $html .='No Records Found!!!!';
		}
	     //printr($html);
	    return $html;
	}
	

}
?>