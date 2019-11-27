<?php 

// add By Sonu 17-03-2018
class digital_product_template extends dbclass{
	
	public function getActiveProduct(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' AND product_id IN (3,1,7,4,22,20,19,10,13,12,16,42,30,31,53,54,50,66,8)";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	} 
	
	public function getUserList(){
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
		$data = $this->query($sql);
		//printr($data);die;
		return $data->rows;
	}
	
	public function getActiveProductZippers(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	//spout
	public function getActiveProductSpout(){
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		} 
	}
	
	//color
	
	public function getActiveColor(){
		$data = $this->query("SELECT pouch_color_id, color FROM " . DB_PREFIX . "pouch_color WHERE status = '1' AND is_delete = '0' AND pouch_color_id IN(67,68,66,69,74,75) ORDER BY color_value ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getColor($color_id){
		$data = $this->query("SELECT pouch_color_id, color FROM " . DB_PREFIX . "pouch_color WHERE pouch_color_id='".$color_id."' AND status = '1' AND is_delete = '0' ORDER BY color ASC LIMIT 1");		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	//product accessorie
	public function getActiveProductAccessorie(){
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function deleteTemplate($template_id){
		$sql = "DELETE pt.*,ps.* FROM `" . DB_PREFIX . "digital_template` pt LEFT JOIN `" . DB_PREFIX . "digital_template_size` ps ON  pt.digital_template_id=ps.template_id  WHERE pt.digital_template_id='".$template_id."'";
		$this->query($sql);	
	}
	
	public function getProduct($product_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '".$product_id."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return 0;
		}
	}
	
	public function checkZipper($option_id){
		$sql = "SELECT zipper FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".(int)$option_id."' ";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['zipper'];
		}else{
			return false;
		}
	}
	
	public function updateQuotationStatus($quotation_id,$status_value){
		
		$sql = "UPDATE " . DB_PREFIX . "digital_template SET status = '".$status_value."', date_modify = NOW() WHERE digital_template_id = '" .(int)$quotation_id. "'";
		$this->query($sql);
	}
	
	public function getSpout($spout_id){
		$data = $this->query("SELECT spout_name, price, product_spout_id FROM " . DB_PREFIX . "product_spout WHERE product_spout_id = '".(int)$spout_id."' ");
		$return  = array();
		if($data->num_rows){
			$return['product_spout_id'] = $data->row['product_spout_id'];
			$return['spout_name'] = $data->row['spout_name'];
			$return['price'] = $data->row['price'];
		}
		return $return;
	}
	
	public function getAccessorie($accessorie_id){
		$data = $this->query("SELECT product_accessorie_name, price, product_accessorie_id FROM " . DB_PREFIX . "product_accessorie WHERE 	product_accessorie_id = '".(int)$accessorie_id."' ");
		$return  = array();
		if($data->num_rows){
			$return['product_accessorie_id'] = $data->row['product_accessorie_id'];
			$return['accessorie_name'] = $data->row['product_accessorie_name'];
			$return['price'] = $data->row['price'];
		}
		return $return;
	}
	public function getQuantities()
	{
		$sql = "SELECT * FROM ".DB_PREFIX." product_quantity where is_delete = '0' ";
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
	public function getInternational()
	{
		$sql = "SELECT first_name,last_name,international_branch_id  FROM " . DB_PREFIX . "international_branch  where is_delete = '0' ";	
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;
	}
	public function getCountry()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' AND is_delete = '0'";	
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;
	}
	
	public function getCurrency()
	{
		$sql = "SELECT *  FROM " . DB_PREFIX . "currency  where is_delete = '0' ";	
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;
	
	}
	
	public function getSelectedCurrency($currency_id)
	{
		$sql = "SELECT currency_code  FROM " . DB_PREFIX . "currency  WHERE currency_id='".$currency_id."' AND  is_delete = '0' LIMIT 1";	
		$data = $this->query($sql);
		$result = $data->row;
		return $result;
	
	}
	public function checkProductGusset($product_id){
		$data = $this->query("SELECT gusset_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['gusset_available'];	
		}else{
			return false;
		}
	}
	
	public function addHistory($title,$product,$country,$user,$currency){
		
		//printr($country);
		//printr($_POST);die;
		$user_data = $this->query("INSERT INTO " . DB_PREFIX . "digital_template SET title = '".$title."',product_name = '".$product."',
		country = '".$country."',user = '".$user."',currency = '".$currency."', status = '0',added_user='".$_SESSION['ADMIN_LOGIN_SWISS']."',added_user_type='".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW()");
		return $this->getLastId();
	}
	
	public function addProduct($id,$s,$t,$fu,$fifth,$w,$h,$g,$vol,$c,$product)
	{
	
			$s=$s;
			$t=$t;
			$fu=$fu;
			$fifth=$fifth;
			$first=$second=$third='';
		
		$this->query("INSERT INTO ".DB_PREFIX."digital_template_size SET template_id = '".(int)$id."',height = '".(float)$h."', width = '".(float)$w."', gusset = '".(float)$g."',quantity200='".$s."',quantity500='".$t."', quantity1000 = '".$fu."' ,quantity2000 = '".$fifth."' ,color = '".$c."', volume = '".$vol."', date_added = NOW(), date_modify=NOW(), is_delete=0 ");
		return $this->getLastId();
			
	}
	public function getaddProductDetails($id,$product,$status)
	{
		$sql = "SELECT pts.*,p.product_name FROM " . DB_PREFIX . "digital_template_size pts,product p  WHERE p.product_id = '".$product."' AND pts.template_id='".$id."' AND pts.is_delete = '0' ";	
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;
	}
	public function getActiveProductVolume(){
		$data = $this->query("SELECT pouch_volume_id,volume FROM " . DB_PREFIX . "pouch_volume WHERE status = '1' AND is_delete = '0' ORDER BY volume ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getTotalTemplate($user_id,$user_type_id,$filter_array=array(),$status)
	{
	    //printr($status);
		//printr($filter_array);
		$org_user_id=$user_id;
		if($user_type_id==1) 
		{
			$sql ="SELECT pt.*,p.product_name,p.product_id,ib.first_name,ib.last_name,pt.country FROM " .DB_PREFIX . "digital_template pt,product p,international_branch ib,digital_template_size pts where pts.template_id = pt.digital_template_id  AND pt.product_name = p.product_id AND ib.international_branch_id = pt.user AND pt.is_delete = '0' AND pt.price_status='".$status."'";
		}
		else
		{
			if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'  ";
				$dataadmin = $this->query($sqladmin);				
				$user_id=$dataadmin->row['user_id'];
			}
 
			 
			
		$sql ="SELECT pt.*,p.product_name,p.product_id,ib.first_name,ib.last_name FROM " .DB_PREFIX . "digital_template pt,product p,international_branch ib,digital_template_size pts where pts.template_id = pt.digital_template_id AND (pt.user='".$user_id."' OR 
			pt.added_user='".$org_user_id."' AND  pt.added_user_type='".$user_type_id."') and pt.product_name = p.product_id and  ib.international_branch_id = pt.user  AND pt.is_delete = '0' AND pt.price_status='".$status."'";
		
		}
		if(!empty($filter_array)) {
			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND pt.product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND pt.country LIKE '%".$filter_array['country']."%'";
			}
			
			
			if(!empty($filter_array['user']))
			{
				$sql .="   AND  pt.user = '".$filter_array['user']."'";
			}
		}
		
		
		$sql .= " GROUP BY pt.digital_template_id";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->num_rows;
		}
		else
		{
			return false;
		}
		
	}
	
	public function getTemplates($user_id,$user_type_id,$data,$filter_array=array(),$status){
		//printr($filter_array);
		//die;
		$org_user_id=$user_id;
		if($user_type_id==1)
		{
			//c.country_id = pt.country  ";    
			$sql ="SELECT pt.*,p.product_name,p.product_id,ib.first_name,ib.last_name,pt.country FROM " .DB_PREFIX . "digital_template pt,product p,international_branch ib,digital_template_size pts where pts.template_id = pt.digital_template_id  AND pt.product_name = p.product_id AND ib.international_branch_id = pt.user AND pt.is_delete = '0' AND pt.price_status='".$status."'";
			//$data = $this->query($sql);
		}
		else
		{
			if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'  ";
				$dataadmin = $this->query($sqladmin);				
				$user_id=$dataadmin->row['user_id'];
			}
		//	echo "SELECT pt.*,p.product_name,c.country_name,ib.first_name,ib.last_name FROM " .DB_PREFIX . "digital_template pt,product p,country c,international_branch ib where pt.user='".$user_id."' and pt.product_name = p.product_id and c.country_id = pt.country and ib.international_branch_id = pt.user and pt.is_delete = '0' ORDER BY digital_template_id DESC ";
		$sql ="SELECT pt.*,p.product_name,p.product_id,ib.first_name,ib.last_name FROM " .DB_PREFIX . "digital_template pt,product p,international_branch ib,digital_template_size pts where pts.template_id = pt.digital_template_id AND (pt.user='".$user_id."' OR 
			pt.added_user='".$org_user_id."' AND  pt.added_user_type='".$user_type_id."') and pt.product_name = p.product_id and  ib.international_branch_id = pt.user  AND pt.is_delete = '0' AND pt.price_status='".$status."'";
		
		}
		if(!empty($filter_array)) {
		
			if(!empty($filter_array['product_name'])){
				$sql .= " AND pt.product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND pt.country LIKE '%".$filter_array['country']."%'";
			}
			
			
			if(!empty($filter_array['user']))
			{
			
				$sql .=" AND pt.user = '".$filter_array['user']."' ";
			}
		}
		$sql .= " GROUP BY pt.digital_template_id";
		
		
		//changed by jaya on 27-5-2016
		if (isset($data['sort'])) {
			$sql .= " ORDER BY  pt.digital_template_id ";	
		}
		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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
		//echo $sql;
		//die;
		$data =$this->query( $sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getmultiplecountry($country)
	{
		$sql = "SELECT GROUP_CONCAT(country_name SEPARATOR ' , ') as country_name FROM country WHERE " .$country;
		$data=$this->query($sql);
		if($data->num_rows)
		{
			return $data->row['country_name'];
		}
		else
		{
			return false;
		}
		
		
	}
	public function getTempalte($template_id)
	{
		$Sql = "SELECT pt.*,pt.template_id as multi_product_quotation_id , p.product_id,p.product_name,ib.first_name,ib.last_name,pts.*,cu.currency_code FROM " .DB_PREFIX . "digital_template pt,product p,international_branch ib,digital_template_size pts,currency as cu where pt.product_name = p.product_id and ib.international_branch_id = pt.user and pts.template_id = pt.digital_template_id  AND  pt.digital_template_id = '".$template_id."' AND  pt.currency=cu.currency_id and pt.is_delete = '0'";		
		
        //$Sql = "SELECT pt.*,pt.template_id as multi_product_quotation_id ,mpt.multi_quotation_number, p.product_id,p.product_name,ib.first_name,ib.last_name,pts.*,cu.currency_code FROM " .DB_PREFIX . "digital_template pt,multi_product_template_id as mpt,product p,international_branch ib,digital_template_size pts,currency as cu where pt.template_id=mpt.multi_product_quotation_id AND pt.product_name = p.product_id and ib.international_branch_id = pt.user and pts.template_id = pt.digital_template_id  AND  pt.digital_template_id = '".$template_id."' AND  pt.currency=cu.currency_id and pt.is_delete = '0'";		

		$data = $this->query($Sql);
		//printr($template_id);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function addTemplate($template_id,$status)
	{ 
//            printr($template_id);die;
		$data = $this->query("UPDATE " . DB_PREFIX . "digital_template SET status = '0',price_status='".$status."' date_modify = NOW() WHERE digital_template_id = '" .(int)$template_id. "'");
	}
	
	public function updateTemplate($data,$status)
	{
		$data = $this->query("UPDATE " . DB_PREFIX . "digital_template SET title='".$data['title']."',price_status='".$status."',product_name='".$data['product']."',country='".json_encode($data['country_id'])."',user='".$data['user']."',currency='".$data['currency']."',date_modify = NOW() WHERE digital_template_id = '" .(int)$data['templateid']. "'");
	}
	

	public function removeTemplateProduct($digital_template_size_id){
		$this->query("DELETE FROM " . DB_PREFIX ."digital_template_size WHERE digital_template_size_id='".$digital_template_size_id."' ");
		//$this->removeImage($order_product_id);
	}
	public function getTemplateInfo($template_id,$status)
	{
		$sql = "SELECT * FROM digital_template WHERE price_status='".$status."' AND digital_template_id = '".$template_id."'";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
		
	} 
	public function getTemplateMultipleInfo($template_id)
	{
		$sql = "SELECT * FROM digital_template WHERE digital_template_id = '".$template_id."'";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	}
	
	public function getTempalteSize($digital_template_size_id)
	{
		$Sql = "SELECT pts.* FROM " .DB_PREFIX . "digital_template_size pts WHERE pts.digital_template_size_id = '".$digital_template_size_id."' LIMIT 1";		
		$data = $this->query($Sql);
		
		if($data->num_rows)
		{
			return $data->row;
		} 
		else
		{
			return false;
		}
	}
	
	public function updateTemplateSize($data)
	{
		//echo "UPDATE " . DB_PREFIX . "digital_template_size SET width='".$data['width']."',height='".$data['height']."',gusset='".$data['gusset']."',volume='".$data['volume']."',quantity1000='".$data['quantity1000']."',quantity2000='".$data['quantity2000']."',quantity5000='".$data['quantity5000']."',quantity10000='".$data['quantity10000']."',valve='".$data['valve']."',zipper='".$data['zipper']."',spout='".$data['spout']."',accessorie='".$data['accessorie']."',color='".json_encode($data['color'])."', date_modify = NOW() WHERE digital_template_size_id = '" .(int)$data['template_size_id']. "'";
		//die;
		
		
			$qty200=$data['quantity200'];	
			$qty500=$data['quantity500'];
			$qty1000=$data['quantity1000'];
			$qty2000=$data['quantity2000'];
			
	
		//echo $data['zipper'];
		//die;
		//echo "UPDATE " . DB_PREFIX . "digital_template_size SET width='".$data['width']."',height='".$data['height']."',gusset='".$data['gusset']."',volume='".$data['volume']."',quantity100='".$qty100."',quantity200='".$qty200."',quantity500='".$qty500."',quantity1000='".$qty1000."',quantity2000='".$qty2000."',quantity5000='".$qty5000."',quantity10000='".$qty10000."',valve='".$data['valve']."',zipper='".$data['zipper']."',spout='".$data['spout']."',accessorie='".$data['accessorie']."',color='".json_encode($data['color'])."', date_modify = NOW() WHERE digital_template_size_id = '" .(int)$data['template_size_id']. "'";die;
		
		$data = $this->query("UPDATE " . DB_PREFIX . "digital_template_size SET width='".$data['width']."',height='".$data['height']."',gusset='".$data['gusset']."',volume='".$data['volume']."',quantity200='".$qty200."',quantity500='".$qty500."',quantity1000='".$qty1000."',quantity2000='".$qty2000."',color='".json_encode($data['color'])."', date_modify = NOW() WHERE digital_template_size_id = '" .(int)$data['digital_template_size_id']. "'");
	}
	
	public function DuplicateMySQLRecord($template_id) 
	{
		  $data = $this->query(" SELECT  title,product_name,country,user,added_user,added_user_type,currency,status,date_added,date_modify,is_delete,price_status  FROM digital_template WHERE digital_template_id = '".$template_id."'");
		 // printr($data);die;
		   //foreach ($data->rows as $key => $value) {
		  		$data4 = $this->query("INSERT INTO " . DB_PREFIX . "digital_template SET title='".$data->row['title']." Copy',product_name='".$data->row['product_name']."',price_status='".$data->row['price_status']."',country='".$data->row['country']."',user='".$data->row['user']."',added_user='".$data->row['added_user']."',added_user_type='".$data->row['added_user_type']."',currency='".$data->row['currency']."',status='".$data->row['status']."',date_added=NOW(),date_modify=NOW(),is_delete='".$data->row['is_delete']."'");
		  // }
	$new_template_id = $this->getLastId();
	//printr($new_template_id);
		 //printr(" SELECT  width,height,gusset,volume,quantity100,quantity200,quantity500,quantity1000,quantity2000,quantity5000,quantity10000,valve,zipper,spout,accessorie,color,is_delete,description  FROM digital_template_size WHERE template_id = '".$template_id."'"); die;

	//[kinjal]: select description feild and insert record (26-12-2015)
	  $data1 = $this->query(" SELECT  width,height,gusset,volume,quantity100,quantity200,quantity500,quantity1000,quantity2000,quantity5000,quantity10000,color,is_delete,description  FROM digital_template_size WHERE template_id = '".$template_id."'");
		  foreach ($data1->rows as $key => $value) {
		  	           	$qty100=$value['quantity100'];
            			$qty200=$value['quantity200'];	
            			$qty500=$value['quantity500'];
            			$qty1000=$value['quantity1000'];
            			$qty2000=$value['quantity2000'];
            			$qty5000=$value['quantity5000']; 
            			$qty10000=$value['quantity10000'];
				
				//echo "INSERT INTO " . DB_PREFIX . "digital_template_size SET template_id='".$new_template_id."',width='".$value['width']."',height='".$value['height']."',gusset='".$value['gusset']."',volume='".$value['volume']."',quantity100='".$qty100."',quantity200='".$qty200."',quantity500='".$qty500."',quantity1000='".$qty1000."',quantity2000='".$qty2000."',quantity5000='".$qty5000."',quantity10000='".$qty10000."',valve='".$value['valve']."',zipper='".$value['zipper']."',spout='".$value['spout']."',accessorie='".$value['accessorie']."',color='".$value['color']."',date_added=NOW(),date_modify=NOW(),is_delete='".$value['is_delete']."'";die;
				$data2 = $this->query("INSERT INTO " . DB_PREFIX . "digital_template_size SET template_id='".$new_template_id."',width='".$value['width']."',height='".$value['height']."',gusset='".$value['gusset']."',volume='".$value['volume']."',quantity100='".$qty100."',quantity200='".$qty200."',quantity500='".$qty500."',quantity1000='".$qty1000."',quantity2000='".$qty2000."',quantity5000='".$qty5000."',quantity10000='".$qty10000."',color='".$value['color']."',description='".$value['description']."',date_added=NOW(),date_modify=NOW(),is_delete='".$value['is_delete']."'");
		}
	}
	

	public function updateStatus($status,$data)
	{		
		$sql = "UPDATE `" . DB_PREFIX . "digital_template` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE digital_template_id IN (" .implode(",",$data). ")";
		$this->query($sql);
	}
	public function getMakeNm($make_id)
	{
		$data = $this->query("SELECT make_name FROM product_make WHERE make_id = '".$make_id."'");
		
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	} 
	public function getSpoutWeight($spout_name)
	{
	
		$data = $this->query("SELECT weight,weight_temp FROM " . DB_PREFIX . "product_spout WHERE spout_name = '".$spout_name."' ");
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	
	public function getTintieWeight($TinTie_name)
	{
	
		$data = $this->query("SELECT weight FROM " . DB_PREFIX . "product_zipper WHERE zipper_name = '".$TinTie_name."' ");
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function getLayerDetail($template_id)
	{
		//echo "SELECT mptl.* FROM " . DB_PREFIX . "multi_product_template as mpt, multi_product_template_layer as mptl  WHERE mpt.multi_product_quotation_id = '".$template_id."' AND mpt.product_quotation_id=mptl.product_quotation_id";
		$data = $this->query("SELECT mptl.*,mpt.volume FROM " . DB_PREFIX . "multi_product_template as mpt, multi_product_template_layer as mptl  WHERE mpt.multi_product_quotation_id = '".$template_id."' AND mpt.product_quotation_id=mptl.product_quotation_id");
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	public function getsmitdata()
	{ 
		$sql="SELECT pts.color,pt.*,pt.template_id as multi_product_quotation_id , p.product_id,p.product_name,ib.first_name,ib.last_name,pts.*,cu.currency_code FROM digital_template pt,product p,international_branch ib,digital_template_size pts,currency as cu where pt.product_name = p.product_id AND ib.international_branch_id = pt.user AND pts.template_id = pt.digital_template_id AND pt.currency=cu.currency_id and pt.is_delete = '0' AND (pt.user='27' OR pt.added_user='27' AND pt.added_user_type='4')";
		$data=$this->query($sql);
		$html='';
		$html .="<table> 
						<tr>
							<td>Title<td>
							<td>product nm<td>
							<td>width X height X gusset<td>
							<td>size<td>
							<td>qty200<td>
							<td>qty500<td>
							<td>qty1000<td>
							<td>qty2000<td>
							<td>valve<td>
							<td>zipper<td>
							<td>spout<td>
							<td>accessorie<td>
							<td>color<td>
							
						<tr>";
			
		foreach($data as $d)
		{
			$html .="<tr>
							<td>".$d['title']."<td>
							<td>".$d['product_name']."<td>
							<td>".$d['width']." X ".$d['height']." X ".$d['gusset']."<td>
							<td>".$d['volume']."<td>						
							<td>".$d['quantity200']."<td>
							<td>".$d['quantity500']."<td>
							<td>".$d['quantity1000']."<td>
							<td>".$d['quantity2000']."<td>
							<td>".$d['valve']."<td>
							<td>".$d['zipper']."<td>
							<td>".$d['spout']."<td>
							<td>".$d['accessorie']."<td>
							<td>
								<table>
								<tr>
								  <td>";
									$color=
						$html .="</td>
								</tr>
							<td>
							
							
					<tr>";
			
			
		}

	}
	
	
}
?>