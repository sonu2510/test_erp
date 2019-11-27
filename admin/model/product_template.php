<?php 
class productTemplate extends dbclass{
	
	public function getActiveProduct(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' ";
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
		$data = $this->query("SELECT pouch_color_id, color FROM " . DB_PREFIX . "pouch_color WHERE status = '1' AND is_delete = '0' ORDER BY color ASC");
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
		$sql = "DELETE pt.*,ps.* FROM `" . DB_PREFIX . "product_template` pt LEFT JOIN `" . DB_PREFIX . "product_template_size` ps ON  pt.product_template_id=ps.template_id  WHERE pt.product_template_id='".$template_id."'";
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
		
		$sql = "UPDATE " . DB_PREFIX . "product_template SET status = '".$status_value."', date_modify = NOW() WHERE product_template_id = '" .(int)$quotation_id. "'";
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
	
	public function addHistory($title,$product,$country,$user,$currency,$transport){
		
		//printr($country);
		//printr($_POST);die;
		$user_data = $this->query("INSERT INTO " . DB_PREFIX . "product_template SET title = '".$title."',product_name = '".$product."',
		country = '".$country."',user = '".$user."',currency = '".$currency."', transportation_type='".$transport."',status = '1',added_user='".$_SESSION['ADMIN_LOGIN_SWISS']."',added_user_type='".$_SESSION['LOGIN_USER_TYPE']."',date_added = NOW()");
		return $this->getLastId();
	}
	
	public function addProduct($id,$f,$s,$t,$fu,$w,$h,$g,$vol,$v,$a,$sp,$z,$c,$product)
	{
		if($product == '18')
		{
			$first=$f;
			$second=$s;	
			$third=$t;
			$f=$fu;
			$s=$t=$fu='';
		}
		else
		{
			$f=$f;
			$s=$s;
			$t=$t;
			$fu=$fu;
			$first=$second=$third='';
		}
		//echo "INSERT INTO ".DB_PREFIX."product_template_size SET template_id = '".(int)$id."',height = '".(float)$h."', width = '".(float)$w."', gusset = '".(float)$g."',quantity100='".$first."',quantity200='".$second."',quantity500='".$third."', quantity1000 = '".$f."',quantity2000 = '".$s."',quantity5000 = '".$t."',quantity10000 = '".$fu."',valve = '".$v."', zipper = '".$z."', spout = '".$sp."', accessorie = '".$a."', color = '".$c."', volume = '".$vol."', date_added = NOW(), date_modify=NOW(), is_delete=0 ";die;
		$this->query("INSERT INTO ".DB_PREFIX."product_template_size SET template_id = '".(int)$id."',height = '".(float)$h."', width = '".(float)$w."', gusset = '".(float)$g."',quantity100='".$first."',quantity200='".$second."',quantity500='".$third."', quantity1000 = '".$f."',quantity2000 = '".$s."',quantity5000 = '".$t."',quantity10000 = '".$fu."',valve = '".$v."', zipper = '".$z."', spout = '".$sp."', accessorie = '".$a."', color = '".$c."', volume = '".$vol."', date_added = NOW(), date_modify=NOW(), is_delete=0 ");
		return $this->getLastId();
			
	}
	public function getaddProductDetails($id,$product)
	{
		//echo $product;
		//die;
		$sql = "SELECT pts.*,p.product_name FROM " . DB_PREFIX . "product_template_size pts,product p  WHERE p.product_id = '".$product."' AND pts.template_id='".$id."' AND pts.is_delete = '0' ";	
		$data = $this->query($sql);
		//echo $sql;
		//printr($data);
		//die;
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
	public function getTemplates($user_id,$user_type_id,$data,$filter_array=array(),$tran){
		
		$trans = '';
		if($tran!='')
		    $trans=" AND pt.transportation_type LIKE '%".$tran."%'";
		    
		$org_user_id=$user_id;
		if($user_type_id==1)
		{
			//c.country_id = pt.country  ";
			$sql ="SELECT pt.*,p.product_name,p.product_id,ib.first_name,ib.last_name,pt.country FROM " .DB_PREFIX . "product_template pt,product p,international_branch ib,product_template_size pts where  pts.template_id = pt.product_template_id   AND pt.product_name = p.product_id AND ib.international_branch_id = pt.user AND pt.is_delete = '0' $trans";
			//$data = $this->query($sql);
		}
		else
		{
			if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'";
				$dataadmin = $this->query($sqladmin);				
				$user_id=$dataadmin->row['user_id'];
			}
		//	echo "SELECT pt.*,p.product_name,c.country_name,ib.first_name,ib.last_name FROM " .DB_PREFIX . "product_template pt,product p,country c,international_branch ib where pt.user='".$user_id."' and pt.product_name = p.product_id and c.country_id = pt.country and ib.international_branch_id = pt.user and pt.is_delete = '0' ORDER BY product_template_id DESC ";
		$sql ="SELECT pt.*,p.product_name,p.product_id,ib.first_name,ib.last_name FROM " .DB_PREFIX . "product_template pt,product p,international_branch ib,product_template_size pts where pts.template_id = pt.product_template_id  AND (pt.user='".$user_id."' OR 
		pt.added_user='".$org_user_id."' AND pt.added_user_type='".$user_type_id."') AND pt.product_name = p.product_id AND  ib.international_branch_id = pt.user  AND pt.is_delete = '0' $trans";
		
		}
		if(!empty($filter_array)) {
			if(!empty($filter_array['transport'])){
				$sql .= " AND pt.transportation_type LIKE '%".$filter_array['transport']."%'";
			}
			
			if(!empty($filter_array['zipper'])){
				$sql .= " AND  pts.zipper LIKE '%".$filter_array['zipper']."%'";
			}
			
			if(!empty($filter_array['spout'])){
				$sql .= " AND pts.spout LIKE '%".$filter_array['spout']."%'";
			}
			
			if(!empty($filter_array['accessorie'])){
				$sql .= " AND pts.accessorie LIKE '%".$filter_array['accessorie']."%'";
			}
			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND pt.product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND pt.country LIKE '%".$filter_array['country']."%'";
			}
			
			if(!empty($filter_array['valve'])){
				$sql .= " AND pts.valve LIKE '%".$filter_array['valve']."%'";
			}
			if(!empty($filter_array['user']))
			{
				$sql .= "   AND pt.user = '".$filter_array['user']."'";
			}
		}
		$sql .= " GROUP BY pt.product_template_id ";
		/*if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pt.product_template_id";	
		}*/
		//changed by jaya on 27-5-2016
		if (isset($data['sort'])) {
			$sql .= " ORDER BY  pt.user ";	
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
	//	echo $sql;
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
		$Sql = "SELECT pt.*,pt.template_id as multi_product_quotation_id , p.product_id,p.product_name,ib.first_name,ib.last_name,pts.*,cu.currency_code FROM " .DB_PREFIX . "product_template pt,product p,international_branch ib,product_template_size pts,currency as cu where pt.product_name = p.product_id AND ib.international_branch_id = pt.user AND pts.template_id = pt.product_template_id  AND  pt.product_template_id = '".$template_id."' AND pt.currency=cu.currency_id and pt.is_delete = '0' ORDER BY pts.volume RLIKE 'gm' DESC,pts.volume + 0 ASC ";		
		 //$Sql = "SELECT pt.*,pt.template_id as multi_product_quotation_id ,mpt.multi_quotation_number, p.product_id,p.product_name,ib.first_name,ib.last_name,pts.*,cu.currency_code FROM " .DB_PREFIX . "product_template pt,multi_product_template_id as mpt,	product p,international_branch ib,product_template_size pts,currency as cu where pt.template_id=mpt.multi_product_quotation_id AND pt.product_name = p.product_id and ib.international_branch_id = pt.user and pts.template_id = pt.product_template_id  AND  pt.product_template_id = '".$template_id."' AND  pt.currency=cu.currency_id and pt.is_delete = '0'";
		//echo $Sql;
		$data = $this->query($Sql);
		//printr($data);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function gettemplateNo($template_id)
	{
	    //printr("SELECT mpi.multi_quotation_number, FROM multi_product_template_id as mpi,product_template as pt Where mpi.status = '1' AND pt.product_template_id = '" .(int)$template_id. "' AND pt.template_id = mpi.multi_product_quotation_id");
		$data = $this->query("SELECT mpi.multi_quotation_number,multi_product_quotation_id FROM multi_product_template_id as mpi,product_template as pt Where mpi.status = '1' AND pt.product_template_id = '" .(int)$template_id. "' AND pt.template_id = mpi.multi_product_quotation_id");
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
	}
	public function addTemplate($template_id)
	{
		$data = $this->query("UPDATE " . DB_PREFIX . "product_template SET status = '0', date_modify = NOW() WHERE product_template_id = '" .(int)$template_id. "'");
	}
	
	public function updateTemplate($data)
	{
		$data = $this->query("UPDATE " . DB_PREFIX . "product_template SET title='".$data['title']."',product_name='".$data['product']."',country='".json_encode($data['country_id'])."',user='".$data['user']."',currency='".$data['currency']."',transportation_type='".$data['transport']."', date_modify = NOW() WHERE product_template_id = '" .(int)$data['templateid']. "'");
	}
	
	public function getTotalTemplate($user_id,$user_type_id,$filter_array=array(),$tran)
	{
		//printr($filter_array);
		$trans = '';
		if($tran!='')
		    $trans=" AND pt.transportation_type LIKE '%".$tran."%'";
		    
		$org_user_id=$user_id;
		if($user_type_id==1)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "product_template as pt,product_template_size as pts WHERE pts.template_id = pt.product_template_id $trans";
		}
		else
		{
			if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'  ";
				$dataadmin = $this->query($sqladmin);				
				$user_id=$dataadmin->row['user_id'];
			}
			$sql = "SELECT * FROM " . DB_PREFIX . "product_template as pt,product_template_size as pts WHERE pts.template_id = pt.product_template_id  AND (pt.user='".$user_id."' OR 
			pt.added_user='".$org_user_id."'  AND  pt.added_user_type='".$user_type_id."') $trans";
		}
		if(!empty($filter_array)) {
			if(!empty($filter_array['transport'])){
				$sql .= " AND pt.transportation_type LIKE '%".$filter_array['transport']."%'";
			}
			
			if(!empty($filter_array['zipper'])){
				$sql .= " AND  pts.zipper LIKE '%".$filter_array['zipper']."%'";
			}
			
			if(!empty($filter_array['spout'])){
				$sql .= " AND pts.spout LIKE '%".$filter_array['spout']."%'";
			}
			
			if(!empty($filter_array['accessorie'])){
				$sql .= " AND pts.accessorie LIKE '%".$filter_array['accessorie']."%'";
			}
			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND pt.product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND pt.country LIKE '%".$filter_array['country']."%'";
			}
			
			if(!empty($filter_array['valve'])){
				$sql .= " AND pts.valve LIKE '%".$filter_array['valve']."%'";
			}
			if(!empty($filter_array['user']))
			{
				$sql .="  AND pt.user = '".$filter_array['user']."'";
			}
		}
		
		
		$sql .= " GROUP BY pt.product_template_id";
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
	
	public function removeTemplateProduct($template_size_id){
		$this->query("DELETE FROM " . DB_PREFIX ."product_template_size WHERE product_template_size_id='".$template_size_id."' ");
		//$this->removeImage($order_product_id);
	}
	public function getTemplateInfo($template_id)
	{
		$sql = "SELECT * FROM product_template WHERE product_template_id = '".$template_id."'";
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
		$sql = "SELECT * FROM product_template WHERE product_template_id = '".$template_id."'";
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
	
	public function getTempalteSize($template_size_id)
	{
		$Sql = "SELECT pts.* FROM " .DB_PREFIX . "product_template_size pts WHERE pts.product_template_size_id = '".$template_size_id."' LIMIT 1";		
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
		//echo "UPDATE " . DB_PREFIX . "product_template_size SET width='".$data['width']."',height='".$data['height']."',gusset='".$data['gusset']."',volume='".$data['volume']."',quantity1000='".$data['quantity1000']."',quantity2000='".$data['quantity2000']."',quantity5000='".$data['quantity5000']."',quantity10000='".$data['quantity10000']."',valve='".$data['valve']."',zipper='".$data['zipper']."',spout='".$data['spout']."',accessorie='".$data['accessorie']."',color='".json_encode($data['color'])."', date_modify = NOW() WHERE product_template_size_id = '" .(int)$data['template_size_id']. "'";
		//die;
		if($data['product'] == '18')
		{
			$qty100=$data['quantity1000'];
			$qty200=$data['quantity2000'];	
			$qty500=$data['quantity5000'];
			$qty1000=$data['quantity10000'];
			$qty5000=$qty2000=$qty10000='';
			$qty30000=$qty50000=$qty100000=$qty15000=$qty20000='';
		}
		else if($data['product'] == '61')
		{
			$qty10000=$data['quantity1000'];
			$qty15000=$data['quantity2000'];	
			$qty20000=$data['quantity5000'];
			$qty30000=$data['quantity10000'];
			$qty50000=$data['quantity20000'];
			$qty100000=$data['quantity30000'];
			
			$qty1000=$qty2000='';
			$qty500=$qty200=$qty100=$qty5000='';
		}
		else if($data['product'] == '47' || $data['product'] == '48')
		{
			$qty1000=$data['quantity1000'];
			$qty2000=$data['quantity2000'];	
			$qty5000=$data['quantity5000'];
			$qty10000=$data['quantity10000'];
			$qty50000=$data['quantity50000'];
			$qty100000=$data['quantity100000'];
			
			$qty15000=$qty20000=$qty30000='';
			$qty500=$qty200=$qty100='';
		}
		else
		{
			$qty1000=$data['quantity1000'];
			$qty2000=$data['quantity2000'];	
			$qty5000=$data['quantity5000'];
			$qty10000=$data['quantity10000'];
			
			$qty500=$qty200=$qty100=$qty30000='';
			$qty50000=$qty100000=$qty15000=$qty20000='';
		}
		//echo $data['zipper'];
		//die;
	//	echo "UPDATE " . DB_PREFIX . "product_template_size SET width='".$data['width']."',height='".$data['height']."',gusset='".$data['gusset']."',volume='".$data['volume']."',quantity100='".$qty100."',quantity200='".$qty200."',quantity500='".$qty500."',quantity1000='".$qty1000."',quantity2000='".$qty2000."',quantity5000='".$qty5000."',quantity10000='".$qty10000."',quantity15000='".$qty15000."',quantity20000='".$qty20000."',quantity30000='".$qty30000."',quantity50000='".$qty50000."',quantity100000='".$qty100000."',valve='".$data['valve']."',zipper='".$data['zipper']."',spout='".$data['spout']."',accessorie='".$data['accessorie']."',color='".json_encode($data['color'])."', date_modify = NOW() WHERE product_template_size_id = '" .(int)$data['template_size_id']. "'";die;
	//	die;
		$data = $this->query("UPDATE " . DB_PREFIX . "product_template_size SET width='".$data['width']."',height='".$data['height']."',gusset='".$data['gusset']."',volume='".$data['volume']."',quantity100='".$qty100."',quantity200='".$qty200."',quantity500='".$qty500."',quantity1000='".$qty1000."',quantity2000='".$qty2000."',quantity5000='".$qty5000."',quantity10000='".$qty10000."',quantity15000='".$qty15000."',quantity20000='".$qty20000."',quantity30000='".$qty30000."',quantity50000='".$qty50000."',quantity100000='".$qty100000."',valve='".$data['valve']."',zipper='".$data['zipper']."',spout='".$data['spout']."',accessorie='".$data['accessorie']."',color='".json_encode($data['color'])."', date_modify = NOW() WHERE product_template_size_id = '" .(int)$data['template_size_id']. "'");
    return $data['template_size_id'];
	}
	
	public function DuplicateMySQLRecord($template_id) 
	{
		  $data = $this->query(" SELECT  title,product_name,country,user,added_user,added_user_type,currency,transportation_type,status,date_added,date_modify,is_delete,template_id  FROM product_template WHERE product_template_id = '".$template_id."'");
		   foreach ($data->rows as $key => $value) {
		  		$data4 = $this->query("INSERT INTO " . DB_PREFIX . "product_template SET title='".$value['title']." Copy',template_id='".$value['template_id']."',product_name='".$value['product_name']."',country='".$value['country']."',user='".$value['user']."',added_user='".$value['added_user']."',added_user_type='".$value['added_user_type']."',currency='".$value['currency']."',transportation_type='".$value['transportation_type']."',status='".$value['status']."',date_added=NOW(),date_modify=NOW(),is_delete='".$value['is_delete']."'");
		   }
            $new_template_id = $this->getLastId();
	//[kinjal]: select description feild and insert record (26-12-2015)
	  $data1 = $this->query(" SELECT  width,height,gusset,volume,quantity100,quantity200,quantity500,quantity1000,quantity2000,quantity5000,quantity10000,quantity15000,quantity20000,quantity30000,quantity50000,quantity100000,valve,zipper,spout,accessorie,color,is_delete,description  FROM product_template_size WHERE template_id = '".$template_id."'");
	  
		  foreach ($data1->rows as $key => $value) {
		  		if($value['quantity100']!='0' && $value['quantity200']!='0' && $value['quantity500']!='0')
			 	{
					$qty100=$value['quantity100'];
					$qty200=$value['quantity200'];	
					$qty500=$value['quantity500'];
					$qty1000=$value['quantity1000'];
					$qty5000=$qty2000=$qty10000='';
					$qty15000=$qty20000=$qty30000=$qty50000=$qty100000=$qty200000='';
				}
				if($value['quantity15000']!='0' && $value['quantity50000']!='0' && $value['quantity100000']!='0' && $value['quantity30000']!='0' && $value['quantity10000']!='0' && $value['quantity20000']!='0')
			 	{
    				$qty10000=$value['quantity10000'];
        			$qty15000=$value['quantity15000'];	
        			$qty20000=$value['quantity20000'];
        			$qty30000=$value['quantity30000'];
        			$qty50000=$value['quantity50000'];
        			$qty100000=$value['quantity100000'];
        			$qty5000=$qty2000='';
        			$qty500=$qty200=$qty100=$qty1000=$qty200000='';
				}
				if($value['quantity1000']!='0' && $value['quantity2000']!='0' && $value['quantity5000']!='0' && $value['quantity10000']!='0' && $value['quantity50000']!='0' && $value['quantity100000']!='0')
			 	{
    			    $qty1000=$value['quantity1000'];
        			$qty2000=$value['quantity2000'];	
        			$qty5000=$value['quantity5000'];
        			$qty10000=$value['quantity10000'];
        			$qty50000=$value['quantity50000'];
        			$qty100000=$value['quantity100000'];
        		    $qty500=$qty200=$qty100=$qty200000=$qty15000=$qty20000=$qty30000='';
				}
				else
				{
					$qty1000=$value['quantity1000'];
					$qty2000=$value['quantity2000'];	
					$qty5000=$value['quantity5000'];
					$qty10000=$value['quantity10000'];
					$qty500=$qty200=$qty100='';$qty25000=$qty50000=$qty100000=$qty200000=$qty20000=$qty30000=$qty15000='';
				}	
				//echo "INSERT INTO " . DB_PREFIX . "product_template_size SET template_id='".$new_template_id."',width='".$value['width']."',height='".$value['height']."',gusset='".$value['gusset']."',volume='".$value['volume']."',quantity100='".$qty100."',quantity200='".$qty200."',quantity500='".$qty500."',quantity1000='".$qty1000."',quantity2000='".$qty2000."',quantity5000='".$qty5000."',quantity10000='".$qty10000."',valve='".$value['valve']."',zipper='".$value['zipper']."',spout='".$value['spout']."',accessorie='".$value['accessorie']."',color='".$value['color']."',date_added=NOW(),date_modify=NOW(),is_delete='".$value['is_delete']."'";die;
				$data2 = $this->query("INSERT INTO " . DB_PREFIX . "product_template_size SET template_id='".$new_template_id."',width='".$value['width']."',height='".$value['height']."',gusset='".$value['gusset']."',volume='".$value['volume']."',quantity100='".$qty100."',quantity200='".$qty200."',quantity500='".$qty500."',quantity1000='".$qty1000."',quantity2000='".$qty2000."',quantity5000='".$qty5000."',quantity10000='".$qty10000."',quantity15000='".$qty15000."',quantity20000='".$qty20000."',quantity30000='".$qty30000."',quantity50000='".$qty50000."',quantity100000='".$qty100000."',valve='".$value['valve']."',zipper='".$value['zipper']."',spout='".$value['spout']."',accessorie='".$value['accessorie']."',color='".$value['color']."',description='".$value['description']."',date_added=NOW(),date_modify=NOW(),is_delete='".$value['is_delete']."'");
		}
	}
	
	public function DuplicateColourClone($template_size_id) 
	{
		$data1 = $this->query("SELECT color FROM product_template_size WHERE product_template_size_id = '".$template_size_id."'");
		$_SESSION['clonecolor']=$data1->row['color']; 
		return $data1; 
	}
	public function DuplicatePasteColour($template_size_id) 
	{
		$data1 = $this->query("UPDATE " . DB_PREFIX . "product_template_size SET color ='".$_SESSION['clonecolor']."' WHERE product_template_size_id = '".$template_size_id."'");
		//echo "UPDATE " . DB_PREFIX . "product_template_size SET color ='".$_SESSION['clonecolor']."' WHERE product_template_size_id = '".$template_size_id."'";
		//return $data1; 
	}
	public function updateStatus($status,$data)
	{		
		$sql = "UPDATE `" . DB_PREFIX . "product_template` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE product_template_id IN (" .implode(",",$data). ")";
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
		$sql="SELECT pts.color,pt.*,pt.template_id as multi_product_quotation_id , p.product_id,p.product_name,ib.first_name,ib.last_name,pts.*,cu.currency_code FROM product_template pt,product p,international_branch ib,product_template_size pts,currency as cu where pt.product_name = p.product_id AND ib.international_branch_id = pt.user AND pts.template_id = pt.product_template_id AND pt.currency=cu.currency_id and pt.is_delete = '0' AND (pt.user='27' OR (pt.added_user='27' AND pt.added_user_type='4') )";// OR pt.added_user='7' AND pt.added_user_type='4'
//	    echo $sql;
		$data=$this->query($sql);
      //printr($data);
		$html='';
		$html .="<style>.table2 { font-size: 100%; table-layout: fixed; width: 100%; font-size:  12px;}
					.table2 { border-collapse: separate; border-spacing: 2px;font-size:  12px; }
					th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left;font-size:  12px; }
					th, td { border-radius: 0.25em; border-style: solid;font-size:  12px; }
					th { background: #EEE; border-color: #BBB;font-size:  12px; }
					td { border-color: #DDD; font-size:  12px;}
					.table3 td{border-width: 0px; }
					.form-horizontal .form-group {margin-right: -15px; margin-left: -15px;}
					.form-group {margin-bottom: 15px;}.panel {border-color: #e3e8ed;}
			</style>
			<table border='1'>
						<tr>
							<td>Title</td>
							<td>product nm</td>
							<td>Transpotation Type</td>
							<td>width X height X gusset</td>
							<td>size</td>
							<td>qty100</td>
							<td>qty200</td>
							<td>qty500</td>
							<td>qty1000</td>
							<td>qty2000</td>
							<td>qty5000</td>
							<td>qty10000</td>
							<td>valve</td>
							<td>zipper</td>
							<td>spout</td>
							<td>accessorie</td>
							<td>color</td>
						</tr>";
			
		foreach($data->rows as $d)
		{ //printr($d);
			$html.="<tr>
							<td>".$d['title']."</td>
							<td>".$d['product_name']."</td>
							<td>".$d['transportation_type']."</td>
							<td>".$d['width']." X ".$d['height']." X ".$d['gusset']."</td>
							<td>".$d['volume']."</td>
							<td>".$d['quantity100']."</td>
							<td>".$d['quantity200']."</td>
							<td>".$d['quantity500']."</td>
							<td>".$d['quantity1000']."</td>
							<td>".$d['quantity2000']."</td>
							<td>".$d['quantity5000']."</td>
							<td>".$d['quantity10000']."</td>
							<td>".$d['valve']."</td>
							<td>".$d['zipper']."</td>
							<td>".$d['spout']."</td>
							<td>".$d['accessorie']."</td>
							<td>
								
							";
									$color=json_decode($d['color']);
									//printr($color);
									$implode=implode(",",$color);
									//printr($implode);
									$sql1 = 'SELECT color FROM pouch_color where pouch_color_id in ('.$implode.')';
									$data1=$this->query($sql1);
									//	printr($data1);
										foreach($data1->rows as $r)
		                                {//printr($r['color']);
									        $html .=" ,".$r['color'];
		                                }
						$html.="
							</td>
							
							
					</tr>";
		
			
		}

		$html.="</table>";
		//
	//	printr($html);
		return $html;

	}
    public function getEmpList()
    {
		$sql = "SELECT am.user_type_id,am.user_id,am.account_master_id,CONCAT(ib.first_name ,' ',ib.last_name) as user_name FROM " . DB_PREFIX ."account_master as am, international_branch as ib WHERE ib.international_branch_id=am.user_id AND ib.is_delete=0 AND am.user_type_id='4'ORDER BY user_name ASC";
		$data = $this->query($sql);
		return $data->rows;
	}
	public function getDataOFReport($post,$n=0)
	{
	    //printr($post);die;
	    $transportaion = '';
	    if($post['report']!='0')
	      $transportaion = " AND pt.transportation_type = '".$post['report']."'";
	    
	    $sql="SELECT pts.color,pt.*,pt.template_id as multi_product_quotation_id , p.product_id,p.product_name,ib.first_name,ib.last_name,pts.*,cu.currency_code FROM product_template pt,product p,international_branch ib,product_template_size pts,currency as cu where pt.product_name = p.product_id AND ib.international_branch_id = pt.user AND pts.template_id = pt.product_template_id AND pt.currency=cu.currency_id AND pt.is_delete = '0'  AND (pt.user='".$post['user_name']."' OR (pt.added_user='".$post['user_name']."' AND pt.added_user_type='4') ) AND pt.country LIKE '%".$post['country']."%' AND pts.valve='no Valve' ".$transportaion." AND pt.status = '0' ORDER BY  FIELD(p.product_id,'3','66','22','1','7','19','20','24','27','10','35','12','42', '53', '13', '16', '30', '31', '54', '50', '36', '26', '11', '18','4','40','41','14','15','63','62','28','48','47','61','5','9','37', '38') ,pts.zipper, pts.spout ASC" ;//AND pt.status = '0' ".$post['country']."  ".$post['user_name']." 
	    //echo $sql;
	    $data = $this->query($sql);
	    
	    $coun = "SELECT country_name FROM country WHERE country_id =".$post['country'];
	    $data_con = $this->query($coun);
	    
	    $valve_p = "SELECT stock_valve_price,product_rate FROM international_branch WHERE international_branch_id =".$post['user_name'];
	    $v_price = $this->query($valve_p);
	    //printr($v_price);
	    $sel_currency_rate=1;
	    if(isset($post['sel_currency']))
	        $data->row['currency_code'] = $post['sel_currency'];
	    if(isset($post['sel_currency_rate']))
	        $sel_currency_rate = $post['sel_currency_rate'];
	    
	   
	        
	    foreach($data->rows as $row)
	    {
	        $array[$row['product_name'].'=='.$row['product_id'].'=='.$row['spout'].'=='.$row['accessorie'].'=='.$row['zipper']][$row['transportation_type']][$row['zipper'].' - '.strtolower($row['volume'])][rtrim($row['title'])] = $row;
	    }
	    foreach($array as $arr)
	    {
	        foreach($arr as $tra=>$arrr)
    		{   
    		        foreach($arrr as $mea=>$d)
        	        {
        	            foreach($d as $title=>$dd)
                        {
                             $array_new[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])] = 
        	                array(   'currency_code'=>$dd['currency_code'],
        	                         'size'=>$dd['width']." (W) X ".$dd['height']." (H) X ".$dd['gusset']." (G)",
        	                         'quantity100'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity100'],
        	                         'quantity200'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity200'],
        	                         'quantity500'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity500'],
        	                         'quantity1000'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity1000'],
        	                         'quantity2000'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity2000'],
        	                         'quantity5000'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity5000'],
        	                         'quantity10000'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity10000'],
        	                         'quantity15000'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity15000'],
        	                         'quantity50000'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity50000'],
        	                         'quantity100000'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity100000'],
        	                         'quantity20000'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity20000'],
        	                         'quantity30000'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity30000'],
        	                         'quantity100_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity100'],
        	                         'quantity200_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity200'],
        	                         'quantity500_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity500'],
        	                         'quantity1000_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity1000'],
        	                         'quantity2000_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity2000'],
        	                         'quantity5000_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity5000'],
        	                         'quantity10000_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity10000'],
        	                         'quantity15000_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity15000'],
        	                         'quantity50000_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity50000'],
        	                         'quantity100000_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity100000'],
        	                         'quantity20000_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity20000'],
        	                         'quantity30000_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity30000'],
        	                         'quantity100_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity100'],
        	                         'quantity200_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity200'],
        	                         'quantity500_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity500'],
        	                         'quantity1000_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity1000'],
        	                         'quantity2000_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity2000'],
        	                         'quantity5000_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity5000'],
        	                         'quantity10000_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity10000'],
        	                         'quantity15000_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity15000'],
        	                         'quantity50000_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity50000'],
        	                         'quantity100000_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity100000'],
        	                         'quantity20000_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity20000'],
        	                         'quantity30000_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['quantity30000'],
        	                         
        	                         'product_template_id'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['product_template_id'],
        	                         'template_id'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Sea'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['template_id'],
        	                         'product_template_id_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['product_template_id'],
        	                         'template_id_air'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Air'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['template_id'],
                                     'product_template_id_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['product_template_id'],
        	                         'template_id_pick'=>$array[$dd['product_name'].'=='.$dd['product_id'].'=='.$dd['spout'].'=='.$dd['accessorie'].'=='.$dd['zipper']]['By Pickup'][$dd['zipper'].' - '.strtolower($dd['volume'])][rtrim($dd['title'])]['template_id'],
      
        	                     );
                        }
        	        }
    			
    		}
	    }
	    //printr($array_new);die;
	    $html = "";
	    $html .="<div class='form-group'>
					<div class='table-responsive'>
						<span><h4><b>Prices in ".$data->row['currency_code']." for ".$data_con->row['country_name'].".</b></h4></span>
						<table class='table table-striped b-t text-small' style='width:50 %' border='1'>
							<thead>";
							    $i=$k=1;
							    foreach($array_new as $p_nm=>$arr)
							    {   $p_detail = explode("==",$p_nm);
							        //if($p_detail[1] != '22')
							        //{
    							        $date ='';
    							          if($p_detail[1] == '18')
    							            $colspan = '4';
    							          else if($p_detail[1] == '61' || $p_detail[1] == '47' || $p_detail[1] == '48')
    							            $colspan = '6';
    							          else
    							            $colspan = '4';
    							        $spout = $acc = $zip=''; 
    							        
    							        if($post['report']=='By Sea' || $post['report']=='By Air' || $post['report']=='By Pickup')
                                            $no ='1';
                                        else
                                            $no ='2'; 
                                            
    							        if($k=='1')
    							            $date = '( '.dateFormat(4,date("Y-m-d")).' )';
    							        else
    							        {
    							            $html .="<tr><th colspan='".(($colspan*$no)+4)."'></th></tr><tr><th colspan='".(($colspan*$no)+4)."'></th></tr>";
    							        
            							       if($p_detail[2]!='No Spout')
            							            $spout = 'With '.$p_detail[2];
            							       if($p_detail[3]!='No Accessorie')
            							            $acc = 'With '.$p_detail[3];
            							       if($p_detail[4]!='No zip')
            							            $zip = $p_detail[4];
    							        }
                                           							            
            							$html .="<tr><th colspan='".(($colspan*$no)+4)."'>".$p_detail[0]." ".$date." ".$spout." ".$acc." ".$zip." </th></tr>
                							     <tr>
                                                      <th>SR. NO.</th>
                                                      <th>CAPACITY</th>
                                                      <th>(POUCH SIZE)</th>
                                                      <th><center>DESCRIPTION</center></th>";
                                                      if($post['report']=='0' || $post['report']=='By Sea')
                                                        $html .="<th colspan='".$colspan."'><center>By Sea</center></th>";
                                                      if($post['report']=='0' || $post['report']=='By Air')
                                                        $html .="<th colspan='".$colspan."'><center>By Air</center></th>";
                                                      if($post['report']=='By Pickup')
                                                        $html .="<th colspan='".$colspan."'><center>By Pickup</center></th>";
                                         $html .="</tr>";
                                        $html .="<tr>
                                                     <th></th>
                                                      <th></th>
                                                      <th></th>
                                                      <th></th>";
                                                      
                                                      for($j=1;$j<=$no;$j++)
                                                      {
                                                        if($p_detail[1] == '18')
                                                        {
        										            $html .="<th >Price ( ".$data->row['currency_code']." )  Qty100+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty200+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty500+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty1000+</th>";
                                                        }
                                                        else if($p_detail[1] == '61')
                                                        {
                                                            $html .="<th >Price ( ".$data->row['currency_code']." )  Qty10000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty15000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty20000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty30000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty50000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty100000+</th>";
                                                        }
                                                
        									            else if($p_detail[1] == '47' || $p_detail[1] == '48')
                										{
                										    $html .="<th >Price ( ".$data->row['currency_code']." )  Qty1000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty2000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty5000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty10000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty50000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty100000+</th>";
                										}
        									            else
        										        {
                                                            $html .="<th >Price ( ".$data->row['currency_code']." )  Qty1000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty2000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty5000+</th>
            										                  <th >Price ( ".$data->row['currency_code']." )  Qty10000+</th>";
                                                        }
                                                       // $html.="<td></td>";
                                                      }
                                        $html .="</tr>";
    							    	    foreach($arr as $mea=>$d)
							    	        {
							    	            $count=count($d);
							    	            foreach($d as $title=>$dd)
				    	                        {
				    	                            $size =$dd['size'];  
				    	                        }
							    	            $html .="<tr>
							    	                        <th style='vertical-align: top' style='vertical-align: top' rowspan='".$count."'>".$i."</th>
							    	                        <th style='vertical-align: top' rowspan='".$count."'>".$mea."</th>";
							    	               $html .="<th style='vertical-align: top' rowspan='".$count."'>".$size."</th>";
							    	                      
							    	                        $st='';
						    	                            if($n==1)
						    	                            {
							    	                            $st='style="font-size:7px;"';
						    	                            }
							    	                      
							    	                        foreach($d as $title=>$dd)
							    	                        {   
						    	                                       $html .="<td ".$st.">".$title."</td>";
						    	                                            if($p_detail[1] == '18')
                        													{
                        														$qty100 = $dd['quantity100'];
                        														$qty200 = $dd['quantity200'];
                        														$qty500 = $dd['quantity500'];
                        														$qty1000 = $dd['quantity1000'];
                        														$a100 = 'quantity100_air';$a200 = 'quantity200_air';$a500 = 'quantity500_air';$a1000 = 'quantity1000_air';
                        														$p100 = 'quantity1000_pick';$p200 = 'quantity2000_pick';$p500 = 'quantity5000_pick';$p1000 = 'quantity10000_pick';
                        													}
                        													else if($p_detail[1] == '61')
                        													{
                            													$qty100 = $dd['quantity10000'];
                                                                    			$qty200 = $dd['quantity15000'];
                                                                    			$qty500 = $dd['quantity20000'];
                                                                    			$qty1000 = $dd['quantity30000'];
                                                                    			$qty2000 = $dd['quantity50000'];
                                                                				$qty3000 = $dd['quantity100000'];
                                                                				$a100 = 'quantity10000_air';$a200 = 'quantity15000_air';$a500 = 'quantity20000_air';$a1000 = 'quantity30000_air';$a2000 = 'quantity50000_air';$a3000 = 'quantity100000_air';
                        													    $p100 = 'quantity10000_pick';$p200 = 'quantity15000_pick';$p500 = 'quantity20000_pick';$p1000 = 'quantity30000_pick';$p2000 = 'quantity50000_pick';$p3000 = 'quantity100000_pick';
                        													}
                        													else if($p_detail[1] == '47' || $p_detail[1] == '48')
                        													{
                            													$qty100 = $dd['quantity1000'];
                                                                    			$qty200 = $dd['quantity2000'];
                                                                    			$qty500 = $dd['quantity5000'];
                                                                    			$qty1000 = $dd['quantity10000'];
                                                                    			$qty2000 = $dd['quantity50000'];
                                                                				$qty3000 = $dd['quantity100000'];
                                                                				$a100 = 'quantity1000_air';$a200 = 'quantity2000_air';$a500 = 'quantity5000_air';$a1000 = 'quantity10000_air';$a2000 = 'quantity50000_air';$a3000 = 'quantity100000_air';
                                                                				$p100 = 'quantity1000_pick';$p200 = 'quantity2000_pick';$p500 = 'quantity5000_pick';$p1000 = 'quantity10000_pick';$p2000 = 'quantity50000_pick';$p3000 = 'quantity100000_pick';
                        													}
                        													else
                        													{
                        														$qty100 = $dd['quantity1000'];
                        														$qty200 = $dd['quantity2000'];
                        														$qty500 = $dd['quantity5000'];
                        														$qty1000 = $dd['quantity10000'];
                        														$a100 = 'quantity1000_air';$a200 = 'quantity2000_air';$a500 = 'quantity5000_air';$a1000 = 'quantity10000_air';
                        														$p100 = 'quantity1000_pick';$p200 = 'quantity2000_pick';$p500 = 'quantity5000_pick';$p1000 = 'quantity10000_pick';
                        													}
				    	                                            if($post['report']=='0' || $post['report']=='By Sea')
				    	                                            {
				    	                                            
    				    	                                            $html .="<td>".$qty100*$sel_currency_rate."</td>
    				    	                                                     <td>".$qty200*$sel_currency_rate."</td>
    				    	                                                     <td>".$qty500*$sel_currency_rate."</td>
    				    	                                                     <td>".$qty1000*$sel_currency_rate."</td>";
    						    	                                     if($p_detail[1] == '61' || $p_detail[1] == '47' || $p_detail[1] == '48')
                                                                                  $html .="<td>".$qty2000*$sel_currency_rate."</td><td>".$qty3000*$sel_currency_rate."</td>";
				    	                                            }
				    	                                            if($post['report']=='0' || $post['report']=='By Air')
				    	                                            {
                                                                        $html .="<td>".$dd[$a100]*$sel_currency_rate."</td>
    				    	                                                     <td>".$dd[$a200]*$sel_currency_rate."</td>
    				    	                                                     <td>".$dd[$a500]*$sel_currency_rate."</td>
    				    	                                                     <td>".$dd[$a1000]*$sel_currency_rate."</td>";
    						    	                                     if($p_detail[1] == '61' || $p_detail[1] == '47' || $p_detail[1] == '48')
                                                                                  $html .="<td>".$dd[$a2000]*$sel_currency_rate."</td><td>".$dd[$a3000]*$sel_currency_rate."</td>";
				    	                                            }
				    	                                            if($post['report']=='By Pickup')
				    	                                            {
                                                                        $html .="<td>".$dd[$p100]*$sel_currency_rate."</td>
    				    	                                                     <td>".$dd[$p200]*$sel_currency_rate."</td>
    				    	                                                     <td>".$dd[$p500]*$sel_currency_rate."</td>
    				    	                                                     <td>".$dd[$p1000]*$sel_currency_rate."</td>";
    						    	                                     if($p_detail[1] == '61' || $p_detail[1] == '47' || $p_detail[1] == '48')
                                                                                  $html .="<td>".$dd[$p2000]*$sel_currency_rate."</td><td>".$dd[$p3000]*$sel_currency_rate."</td>";
				    	                                            }
				    	                                             if($n==0 && ($_SESSION['LOGIN_USER_TYPE']==1 && $_SESSION['ADMIN_LOGIN_SWISS']==1) )
				    	                                             {
				    	                                                $html .="<td> ";
				    	                                                    if($post['report']=='0' || $post['report']=='By Sea')
				    	                                                        $html .="<a class='label bg-success' href='".HTTP_SERVER."/admin/index.php?route=product_template&mod=detail&template_id=".encode($dd['template_id'])." ' target='_blank'>Sea Details</a></br><br>";
				    	                                                    if($post['report']=='0' || $post['report']=='By Air')
				    	                                                        $html .="<a id='hide_div' class='label bg-success' href='".HTTP_SERVER."/admin/index.php?route=product_template&mod=detail&template_id=".encode($dd['template_id_air'])."' target='_blank'>Air Details</a>";
				    	                                                    if($post['report']=='By Pickup')
				    	                                                        $html .="<a id='hide_div' class='label bg-success' href='".HTTP_SERVER."/admin/index.php?route=product_template&mod=detail&template_id=".encode($dd['template_id_pick'])."' target='_blank'>Pickup Details</a>";
							    	                                    $html .="</td>";
				    	                                             }
							    	                            $html .="</tr>";    
							    	                        }
							    	               
							    	        $html .="</tr>";
							    	                /*if($p_detail[1] == '3')
							    	                {
							    	                    $html .="<tr><td></td><td></td><td></td>
							    	                                 <td>Oxo-Degradable Bags - Brand: Bak2Earth - Stand up pouch</td>
							    	                                 <td>".$array['Oxo-Degradable Bags - Brand: Bak2Earth - Stand up pouch==22=='.$p_detail[2].'=='.$p_detail[3].'=='.$p_detail[4]][$mea]."</td>
							    	                                 <td>".$qty100."</td>
							    	                                 <td>".$qty100."</td>
							    	                                 <td>".$qty100."</td>
							    	                                 <td>".$qty100."</td>
							    	                                 <td>".$qty100."</td>
							    	                                 <td>".$qty100."</td>
							    	                                 <td>".$qty100."</td>
							    	                            </tr>";
							    	                }*/
							    	            $i++;
							    	        }
    							    	        
    							       $k++;
							    
							        //} 
							        if($p_detail[1] == '3' || $p_detail[1] == '7' || $p_detail[1] == '1')
							            $html .="<tr><th colspan='".(($colspan*$no)+4)."'><span style='color: red;'>Please add  ".$data->row['currency_code']." ".number_format(($v_price->row['stock_valve_price']/$v_price->row['product_rate']),3)." extra on above price for VALVE fitting.		</span></th></tr>";  
							    }
                    $html .="</thead>
                        </table>
                    </div>
                </div>
                <style>
                    table td {width:1%;}
                    table th {width:1%;}
                </style>";
        return $html;
	}
	public function getNewCurrencys($user_id)
	{
		
		$set_user_id = $user_id;
		$set_user_type_id = '4';
	    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."' ");
	  //  $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND cn.country_id =".$country." ");
        
		if($data->num_rows){
			return  $data->rows;
		}else{
			return false;
		}
	}
	public function getIBDetail($user_id)
	{
	    $data = $this->query("SELECT c.currency_code,ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr FROM international_branch as ib,country as c WHERE ib.international_branch_id=".$user_id." AND ib.default_curr = c.country_id");
	    return  $data->row;
	}
	public function getUser($user_id,$user_type_id)
    {	//echo $user_type_id;
        if($user_type_id == 1){

			$sql = "SELECT u.user_id,ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email, acc.commission FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
        }elseif($user_type_id == 2){

			$sql = "SELECT ib.color_plate_price,ib.foil_plate_price,e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address, acc.commission FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
        }elseif($user_type_id == 4){

			$sql = "SELECT ib.user_name,ib.color_plate_price,ib.foil_plate_price,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name,ib.vat_no, ib.international_branch_id as user_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email, acc.email1, acc.commission FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
        }else{
            $sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
        }
        $data = $this->query($sql);
        return $data->row;

	}
}
?>