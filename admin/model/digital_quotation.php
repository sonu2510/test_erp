<?php  
class digital_quotation extends dbclass{
    public function getColor($color_id,$make_id=0)
	{
		$make='';
		if($make_id!=0)
		    $make = " AND make_id LIKE '%".$make_id."%'";
		$data = $this->query("SELECT pouch_color_id, color FROM " . DB_PREFIX . "pouch_color WHERE pouch_color_id='".$color_id."' AND status = '1' AND is_delete = '0' ".$make." ORDER BY color ASC LIMIT 1");		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}

	public function getaddProductDetails($product_code_id,$country_id,$transport,$color)
	{
		if($_SESSION['LOGIN_USER_TYPE'] == 2)
		{
			$sql1 = "SELECT * FROM employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			$data = $this->query($sql1);
			$user_id=$data->row['user_id'];
		}
		else
		{
			$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
		}
		$status=0;
		if($country_id=='155')
		    $status=1;
		else if($country_id=='42') 
		    $status=1;
		$countryid='"'.$country_id.'"';
        $sql1 ="SELECT * FROM `proforma_price_list` as pp ,pouch_color as pc , product_code as p WHERE pp.product_id =p.product AND pc.color_category =pp.category_id AND pp.`volume` = p.volume AND pp.`measurement`=p.measurement AND pp.accessorie_id=p.accessorie AND pp.zipper_id=p.zipper AND pp.spout_id =p.spout AND  pp.country_id ='".$country_id."' AND p.product_code_id='".$product_code_id."' AND p.color=pc.pouch_color_id AND pp.price_status='".$status."' group by p.product_code_id";
	   // printr($sql1);die; 
		$data1 = $this->query($sql1);
        if($data1->num_rows)
		{
			return $data1->rows;
		}
		else
			return false;
	}
	public function getaddProductDetailsForDigitalPrint($product_code_id,$country_id,$color,$price_status=0)
	{
		if($_SESSION['LOGIN_USER_TYPE'] == 2)
		{
			$sql1 = "SELECT * FROM employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			$data = $this->query($sql1);
			$user_id=$data->row['user_id'];
		}
		else
		{ 
			$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
		}
		$countryid='"'.$country_id.'"'; 
		$sql1="SELECT pz.zipper_name,pc.valve,pm.volume,pc.color,pc.product FROM product_code as pc, pouch_volume as pm,product_zipper as pz WHERE pm.volume = (Select CONCAT(p.volume,' ',m.measurement) as volume from product_code as p, template_measurement as m WHERE p.measurement=m.product_id AND p.product_code_id='".$product_code_id."') AND pc.product_code_id='".$product_code_id."' AND pc.zipper=pz.product_zipper_id";
		$data1 = $this->query($sql1);
		
		if($data1->num_rows)
		{
		    $arr=explode("==",$color);
		    $color_id='"'.$arr[0].'"';
			$sql = "SELECT pts.*,p.product_name,p.product_id,pt.user,pt.country,pt.digital_template_id FROM " . DB_PREFIX . "digital_template_size pts,product p,digital_template as pt  WHERE  pt.product_name='".$data1->row['product']."' AND  pt.country LIKE '%".$countryid."%'  AND pt.user = '".$user_id."' AND pts.template_id=pt.digital_template_id AND pt.product_name=p.product_id AND pts.is_delete = '0' AND pt.status='0' AND REPLACE(pts.volume, ' ', '') = REPLACE('".$data1->row['volume']."', ' ', '') AND  pt.price_status='".$price_status."'  AND pts.color LIKE '%".$color_id."%' ";	
        	$data = $this->query($sql);
		    $result = $data->rows;
			return $result;
		}
		else
			return false;
	}
	
	public function getTempalte($template_id)
	{
		$Sql = "SELECT pt.*,p.product_name,c.country_name,ib.first_name,ib.last_name,pts.*,cu.currency_code FROM " .DB_PREFIX . "product_template pt,product p,country c,international_branch ib,product_template_size pts,currency as cu where pt.product_name = p.product_id and c.country_id = pt.country and ib.international_branch_id = pt.user and pts.template_id = pt.product_template_id and pt.product_template_id = '".$template_id."' 
		and pt.currency=cu.currency_id and pt.is_delete = '0' ";		
		$data = $this->query($Sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	
	public function getProductName($template_id)
	{
		$sql = "SELECT product_name FROM product_template WHERE product_template_id = '".$template_id."'";	
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
	
	public function getDefaultcountry($user_id,$user_type_id)
	{
		$sql = "SELECT country_id FROM address WHERE user_id = '".$user_id."' AND user_type_id = '".$user_type_id."'";	
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
	public function removeTemplateProduct($template_size_id)
	{
		$this->query("DELETE FROM " . DB_PREFIX ."product_template_size WHERE product_template_size_id='".$template_size_id."' ");
	}
	public function getClientName($customer_name)
	{   
	    $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
        $str='';
		if($user_type_id == 2){
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
			$ib = $this->getUser($set_user_id,$set_user_type_id);
			if($ib['country_id'] == '111')
			{
			  $ib_id = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "address WHERE user_type_id = '4' AND country_id = '".$ib['country_id']."'");
			  foreach($ib_id->rows as $key=>$ids)
			  {
			      $emp = $this->getUserEmployeeIds($ids['user_type_id'],$ids['user_id']);
			      $e[] = $emp;
			      $i[]=$ids['user_id'];
			  }
			  $e_id = implode(",",$e);
			  $set_user_id = implode(",",$i);
			  if($e_id)
			    $str = ' OR ( aa.user_id IN ('.$e_id.') AND aa.user_type_id = 2 )';
			}
			else
			{
    			if($userEmployee)
    			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			}
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.status=1 AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id IN (".$set_user_id .") AND aa.user_type_id='".$set_user_type_id ."') $str )  GROUP BY aa.address_book_id LIMIT 15";
		}
		else if($user_type_id == 4)
		{
			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			$set_user_id = $user_id;
			$set_user_type_id = $user_type_id;
			$ib = $this->getUser($set_user_id,$set_user_type_id);
			if($ib['country_id'] == '111')
			{
			  $ib_id = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "address WHERE user_type_id = '4' AND country_id = '".$ib['country_id']."'");
			  foreach($ib_id->rows as $key=>$ids)
			  {
			      $emp = $this->getUserEmployeeIds($ids['user_type_id'],$ids['user_id']);
			      $e[] = $emp;
			      $i[]=$ids['user_id'];
			  }
			  $e_id = implode(",",$e);
			  $set_user_id = implode(",",$i);
			  if($e_id)
			    $str = ' OR ( aa.user_id IN ('.$e_id.') AND aa.user_type_id = 2 )';
			}
			else
			{
    			if($userEmployee)
    			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			}
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.status=1 AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id IN (".$set_user_id .") AND aa.user_type_id='".$set_user_type_id ."') $str  )  GROUP BY aa.address_book_id LIMIT 15";
		}
		else
		{
            $set_user_id = $user_id;
            $set_user_type_id  = $user_type_id;
			$sql = "SELECT aa.address_book_id,aa.vat_no,cs.phone_no, aa.company_name, cs.company_address_id, cs.c_address,cs.email_1,fa.factory_address_id, fa.f_address FROM address_book_master as aa LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) LEFT JOIN factory_address as fa ON (aa.`address_book_id`=fa.`address_book_id`) WHERE aa.is_delete=0 AND aa.status=1 AND aa.company_name LIKE '%".$customer_name."%' GROUP BY aa.address_book_id LIMIT 15";
		}
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	
	}
	public function getUser($user_id,$user_type_id)
	{
		$cond = '';
		if($user_type_id==2)
		{
			$sql = "SELECT ib.lang_id,ib.stock_order_price,ib.user_name,co.currency_code,co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email,ib.user_type_id,ib.user_id FROM " . DB_PREFIX ."employee ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '".(int)$user_type_id."' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ".$cond."";
		}
		elseif($user_type_id == 4)
		{
			$sql = "SELECT ib.lang_id,ib.stock_order_price,ib.user_name,co.currency_code, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id as user_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email, acc.email1 FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '".(int)$user_type_id."' AND ad.user_id = '".(int)$user_id."' ) LEFT  JOIN  " . DB_PREFIX ." country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ".$cond." ";
		}
		elseif($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id,co.currency_code, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email,ad.user_id FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}
		elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id,co.currency_code, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email,ad.user_id FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}
		else
		{
			return false;
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getColorPlatePrice($user_id,$user_type_id,$stock_print='')
	{
        $field = 'color_plate_price';
        if($stock_print=='Foil Print')
            $field = 'foil_plate_price';
        if($user_type_id=='2')
		{ 
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'";
			$dataadmin = $this->query($sqladmin);
		    $admin_user_id =  $dataadmin->row['user_id'];
		    $sql="SELECT $field FROM international_branch WHERE international_branch_id='".$admin_user_id ."'";
		}
		elseif($user_type_id=='4')
		{
			$sql="SELECT $field FROM international_branch WHERE international_branch_id='".$user_id."'";
		}
		//echo $sql;die;
	    $data=$this->query($sql); 
		if($data->num_rows)
		{
			return $data->row[$field];
		}
		else
		{
			return false;
		}
	}
	public function getemployeeId($user_id,$user_type_id)
	{
		if($user_type_id==2)
		{
			$sql = "SELECT user_type_id,user_id  from employee WHERE employee_id='".$user_id."'";
			$data=$this->query($sql);
			if($data->num_rows)
			{
				$admin_user_id=$data->row['user_id'];
				$admin_user_type_id=$data->row['user_type_id'];
			}
			$sql1 = "SELECT employee_id FROM employee WHERE user_id='".$admin_user_id."' and user_type_id='".$admin_user_type_id."'";
			$data_val=$this->query($sql1);
			return $data_val->rows;
		}
		elseif($user_type_id==4)
		{
		    $sql1 = "SELECT employee_id FROM employee WHERE user_id='".$user_id."' AND user_type_id='".$user_type_id."'";
			$data_val=$this->query($sql1);
			return $data_val->rows;
		}
	
	}
    public function getActiveProduct(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' AND product_id IN (3,7,1) ORDER BY product_name ASC";//,20,19,10,4,12,13,16,42,30,31,53,54,50
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	public function getUserEmployeeIds($user_type_id,$user_id){
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	public function getActiveColorForDigitalPrint(){
        $sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE status='1' AND pouch_color_id IN(67,68,69,66) AND is_delete = '0'  ORDER BY color_value ASC";//,74,75
        $data = $this->query($sql);
        if($data->num_rows){
            return $data->rows;
        }else{
            return false;
        }
    }
	public function getActiveProductZippersByTintie($tintie){
		if($tintie == '1')
		{
			$tin = "";// AND  zipper_name LIKE 'T%'
		}
		else
		{
			$tin = " AND  zipper_name NOT LIKE 'T%'";
		}
        $data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' $tin ORDER BY serial_no ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function checkProductZipper($product_id){
		$data = $this->query("SELECT zipper_available,tintie_available,spout_pouch_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['zipper_available'];	
		}else{
			return false;
		}
	}
	public function getActiveMake(){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0'  AND make_id NOT IN (9,7,3)  ";
		$sql .= " ORDER BY serial_no";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getProductSize($product_id,$make_id)
	{   
	    $sql  = "SELECT s.*,pz.zipper_name FROM size_master as s,product_zipper as pz WHERE s.product_id = '".$product_id."' AND pz.product_zipper_id IN (1,2,3,16) AND s.product_zipper_id=pz.product_zipper_id AND s.weight!='0' ORDER BY width ASC"; // 
	    //echo $sql;
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
	public function getColorName($product_id,$zipper_id,$volume)
	{
	    $vol = explode(" ",$volume);
	    $sql = "SELECT color FROM product_color_detail_size_wise WHERE product_id = '".$product_id."' AND volume = '".$volume."' AND zipper_id = ".$zipper_id."";
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
	public function getActiveProductSpout(){
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY serial_no ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getCountryCombo($selected=""){
		$sql = "SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' AND is_delete = '0' AND default_courier_id > 0 ORDER BY country_name ASC";
		$data = $this->query($sql);
		$html = '';
		if($data->num_rows){
			$html = '';
			$html .= '<select name="country_id" id="country_id" class="form-control validate[required]" style="width:70%" >';
					$html .= '<option value="">Select Country</option>';
			foreach($data->rows as $country){
				if($country['country_id'] == $selected ){
					$html .= '<option value="'.$country['country_id'].'" selected="selected">'.$country['country_name'].'</option>';
				}else{
					$html .= '<option value="'.$country['country_id'].'" >'.$country['country_name'].'</option>';
				}
			}
			$html .= '</select>';
		}
		return $html;
	}
	public function getUserCurrencyInfo($user_type_id,$user_id){
            if( $_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
            {
                $data = $this->query("SELECT *  FROM " . DB_PREFIX . "currency  WHERE is_delete = '0' ");
            }
            else
            {
    			if($_SESSION['LOGIN_USER_TYPE'] == 2){
                    $parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
                    $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
                    $set_user_id = $parentdata->row['user_id'];
                    $set_user_type_id = $parentdata->row['user_type_id'];
                }else{
                    $userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
                    $set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
                    $set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
                }
    			$data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");
    		}
            if($data->num_rows){
			   return $data->rows;
            }else{
	    	    return false;
	    	}	
		  
		 return $currency;
    }
	public function getActiveProductAccessorie(){
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY serial_no ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getProductCode($product,$zipper,$make,$spout,$accessorie,$volume,$color,$valve){
	    
	    $vol = explode(" ",$volume);//printr($vol);
	    if($vol[1]=='with')
	        $vol = preg_split('/(?<=[0-9])(?=[a-z]+)/i',$volume);
	    // printr($vol);   
	   $data = $this->query("SELECT product_code_id, product_code FROM " . DB_PREFIX . "product_code as pc,template_measurement as t WHERE pc.product='".$product."' AND pc.measurement = t.product_id AND pc.zipper='".$zipper."' AND	pc.valve='".$valve."' AND pc.spout='".$spout."' AND	pc.accessorie='".$accessorie[0]."' AND pc.accessorie_second='".$accessorie[1]."' AND pc.make_pouch='".$make."' AND pc.color = '".$color."' AND pc.volume = '".$vol[0]."' AND t.measurement = '".$vol[1]."' AND pc.status = '1' AND pc.is_delete = '0'");
		//printr("SELECT product_code_id, product_code FROM " . DB_PREFIX . "product_code as pc,template_measurement as t WHERE pc.product='".$product."' AND pc.measurement = t.product_id AND pc.zipper='".$zipper."' AND	pc.valve='".$valve."' AND pc.spout='".$spout."' AND	pc.accessorie='".$accessorie[0]."' AND pc.accessorie_second='".$accessorie[1]."' AND pc.make_pouch='".$make."' AND pc.color = '".$color."' AND pc.volume = '".$vol[0]."' AND t.measurement = '".$vol[1]."' AND pc.status = '1' AND pc.is_delete = '0'");die;
		if($data->num_rows){ 
			return $data->row;
		}else{
			return false;
		}
	}
	public function getDigitalQty($user_id)
	{
		if($user_id==1)
		    $sql="SELECT GROUP_CONCAT(quantity,' ',' ') as digital_quantity FROM digital_quantity WHERE status='1' AND is_delete=0";
		else
		    $sql="SELECT digital_quantity FROM international_branch WHERE international_branch_id='".$user_id."'";
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
	public function getParentInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea,gres_cyli, valve_price FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea,gres_cyli, valve_price FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
		}
		if($sql){
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	public function getUserInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name, gres,gres_air,gres_sea,	gres_cyli, valve_price,product_rate  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
		}elseif($user_type_id == 2){
			$data = $this->query("SELECT first_name, last_name, user_type_id, user_id FROM `" . DB_PREFIX . "employee` WHERE employee_id = '" .(int)$user_id. "'");
            $parentInfo = array();
			$return = array();
			if($data->num_rows){
				$parentInfo = $this->getParentInfo($data->row['user_type_id'],$data->row['user_id']);
				if($parentInfo){
					$return['company_name'] = $parentInfo['company_name'];
					$return['gres'] = $parentInfo['gres'];
					$return['gres_air'] = $parentInfo['gres_air'];
					$return['gres_sea'] = $parentInfo['gres_sea'];
					$return['gres_cyli'] = $parentInfo['gres_cyli'];
					$return['valve_price'] = $parentInfo['valve_price'];
					$return['product_rate'] = $parentInfo['product_rate'];
				}else{
					$return['company_name'] = '';
					$return['gres'] = '';
					$return['gres_air'] = '';
					$return['gres_sea'] = '';
					$return['valve_price'] = '';
					$return['product_rate'] = '';
					$return['gres_cyli'] ='';
				}
			}else{
				$return['company_name'] = '';
				$return['gres'] = '';
				$return['gres_air'] = '';
				$return['gres_sea'] = '';
				$return['valve_price'] = '';
				$return['product_rate'] = '';
				$return['gres_cyli'] ='';
			}
			$return['first_name'] = $data->row['first_name'];
			$return['last_name']  = $data->row['last_name'];
			
			return $return;
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name,gres,gres_air,gres_sea,	gres_cyli, valve_price,product_rate FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea,	gres_cyli,valve_price,product_rate FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
		}
	    if($sql){
			$data = $this->query($sql);
			if($data && $data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}else{
			return false;
		}
	   
	}
	public function getUserWiseCurrency($user_type_id,$user_id)
	{
		if($user_type_id==2){
			$parent_data = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX ."employee WHERE employee_id='".$user_id."'");
			if($parent_data->num_rows){
				
				if($parent_data->row['user_type_id']==4){
					$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$parent_data->row['user_id']."' ";				
				}else if($parent_data->row['user_type_id']==5){
					$sql = "SELECT as.product_rate,as.cylinder_rate,as.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."associate as INNER JOIN " . DB_PREFIX ."country cn ON (as.default_curr=cn.country_id) WHERE as.associate_id = '".$parent_data->row['user_id']."' ";	
				}	
			}
		}
		else if($user_type_id==4){
			$sql = "SELECT ib.product_rate,ib.cylinder_rate,ib.tool_rate,ib.default_curr,cn.currency_code,cn.currency_id FROM " . DB_PREFIX ."international_branch ib INNER JOIN " . DB_PREFIX ."country cn ON (ib.default_curr=cn.country_id) WHERE ib.international_branch_id = '".$user_id."' ";		
		}
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}		
	}
	public function getCurrencyInfo($user_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."user WHERE user_id = '".$user_id."' LIMIT 1");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getLastIdAddress() {
        $sql = "SELECT address_book_id FROM  address_book_master ORDER BY address_book_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row;
        } else {
            return false;
        }
    }
	public function addQuotation($data,$type='Q',$digital_data,$palte_price,$stock_data,$product_code_id){
	    /*printr($data);
	    printr($digital_data);
	    printr($palte_price);
	    printr($stock_data);
	    printr($product_code_id);die;*/
	    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	    if($user_type_id == 1 && $user_id == 1){
			$admin_user_id ='1';
		}else{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$admin_user_id =$parentdata->row['user_id'];
			}else {
				$admin_user_id = $this->query("SELECT international_branch_id FROM `" . DB_PREFIX . "international_branch`  WHERE international_branch_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'");
			    $admin_user_id = $admin_user_id->row['international_branch_id'];
			}
		}
		$userInfo = $this->getUserInfo(4,$admin_user_id);
		$userCountry = $this->getUserCountry($user_type_id,$user_id);
		if($type=='Q')
		{
			if(isset($data['digital_quotation_id']) && $data['digital_quotation_id']!='')
			{
				$digital_quotation_id = $data['digital_quotation_id'];
			}
			else
			{
			    $contacts = "SELECT email_1 FROM company_address WHERE email_1='".$data['email']."' AND is_delete=0";
				$datacontacts= $this->query($contacts);
				if(!isset($datacontacts->row['email_1']) && empty($datacontacts->row['email_1']))
				{
						$sql1 = "INSERT INTO  address_book_master  SET status = '1', company_name = '" . addslashes($data['customer']) . "', user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "', date_added = NOW(),date_modify = NOW()";
						$datasql1 = $this->query($sql1);
						$address_id = $this->getLastIdAddress();
						$address_book_id = $address_id['address_book_id'];
						
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET email_1 = '" . $data['email'] . "',country = '" . $data['country_id'] . "' WHERE company_address_id ='" . $dataadd->row['company_address_id'] . "'";
							$datasql2 = $this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO  company_address  SET  address_book_id = '" . $address_book_id . "',country = '" . $data['country_id'] . "', email_1 = '" . $data['email'] . "' ,user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "' , date_added = NOW(),date_modify = NOW()";
							$datasql2 = $this->query($sql2);
						}
				}
				else
				{	
						$address_book_id = $data['address_book_id'];							
						$add_id = "SELECT address_book_id,company_address_id FROM company_address WHERE address_book_id='".$address_book_id."' AND is_delete=0";
						$dataadd= $this->query($add_id);
						if($dataadd->num_rows)
						{
							$sql2 = "UPDATE company_address SET email_1 = '" . $data['email'] . "',country = '" . $data['country_id'] . "' WHERE company_address_id ='" . $dataadd->row['company_address_id'] . "'";
							$datasql2 = $this->query($sql2);
						}
						else
						{
							$sql2 = "INSERT INTO  company_address  SET  address_book_id = '" . $address_book_id . "',country = '" . $data['country_id'] . "', email_1 = '" . $data['email'] . "' ,user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "' , date_added = NOW(),date_modify = NOW()";
							$datasql2 = $this->query($sql2);
						}
				}
				if($userCountry){
        			$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
        		}else{
        			$countryCode='IN';
        		}
				if($type=='Q')
        		{
        			$newQuotaionNumber = $this->generateQuotationNumber();
        			$quotation_number = $countryCode.'DP'.$newQuotaionNumber;
        		}
				$sql =  "INSERT INTO ".DB_PREFIX."digital_quotation SET  client_name = '".addslashes($data['customer'])."',email = '".$data['email']."',digital_quotation_no = '".$quotation_number."',status = '1',quotation_status = '0',address_book_id = '".$address_book_id."',reference_no = '".$data['ref_no']."', date_added = NOW(),date_modify = NOW(), user_id = '".(int)$user_id."', user_type_id = '".(int)$user_type_id."', country_id = '".$data['country_id']."', admin_user_id = '".$admin_user_id."'";		
				$this->query($sql);
				$digital_quotation_id = $this->getLastId();
			}
	    }
	    $product_name = $this->query("SELECT * FROM product WHERE product_id = '".$data['product']."'");
	    $sql_product =  "INSERT INTO ".DB_PREFIX."digital_product_quotation SET  digital_quotation_id = '".$digital_quotation_id."',product_code_id = '".$product_code_id."',product_id = '".$data['product']."',product_name = '".$product_name->row['product_name']."', valve = '".$data['valve']."',zipper_id = '".$data['zipper']."',make_id = '".$data['make']."',accessorie_id = '".$data['accessorie'][0]."',spout_id = '".$data['spout']."',size_id = '".$data['size']."',color_id = '".$data['color']."',front_color = '".$data['front_color']."',back_color = '".$data['back_color']."',total_color = '".$data['digital_print_color']."',printing_effect = '".$data['effect']."',stock_print = '".$data['stock_print']."',transport_type = '".$data['transpotation']."',status = '1'";		
		$this->query($sql_product);
		$digital_product_quotation_id = $this->getLastId();
		if($user_type_id==1){
			$userCurrency = $this->getCurrencyInfo($user_id);
			$currCode ='INR';
		}else{ 
			$userCurrency =  $this->getUserWiseCurrency($user_type_id,$user_id);
			$currCode=$userCurrency['currency_code'];
		}
		$taxation_data='';
		$tax_name='';
		if(isset($data['con_id']) && !empty($data['con_id']) && $data['con_id'] ==111)
		{
			$tax_name.=' tax_name="'.$data['con_id'].'"';
    		$sql_tax = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
    		$data_tax = $this->query($sql_tax);
    		$taxation_data=$data_tax->row;
		}
		$prefix ='';
		if($data['transpotation']=='By Air')
		    $prefix = 'air_';
		    
        if($stock_data)
        {
            foreach($stock_data as $details)
            {
                $total_quantity=0;
                $arr=explode("==",$data['digital_print_color']);
                $valve_price=0;
                if($data['valve']=='With Valve')
                {
                    if($data['con_id']=='42')
                        $valve_price='0.10';
                    else
                        $valve_price=$userInfo['valve_price']/$userInfo['product_rate'];
                }
               // printr($details['quantity2000'].'+'.$digital_data[0]['quantity1000'].'+('.$palte_price.'*'.$arr[1].')/1000+'.$valve_price);
                $qty['200']= (($details[$prefix.'quantity1000']+$digital_data[0]['quantity200']+(($palte_price*$arr[1])/200))+$valve_price);
                $qty['500']= (($details[$prefix.'quantity1000']+$digital_data[0]['quantity500']+(($palte_price*$arr[1])/500))+$valve_price);
                $qty['1000']= (($details[$prefix.'quantity1000']+$digital_data[0]['quantity1000']+(($palte_price*$arr[1])/1000))+$valve_price);
                $qty['2000']= (($details[$prefix.'quantity2000']+$digital_data[0]['quantity2000']+(($palte_price*$arr[1])/2000))+$valve_price);
                $qty['5000']= (($details[$prefix.'quantity5000']+$digital_data[0]['quantity5000']+(($palte_price*$arr[1])/5000))+$valve_price);
                $qty['10000']= (($details[$prefix.'quantity10000']+$digital_data[0]['quantity10000']+(($palte_price*$arr[1])/10000))+$valve_price);
                $qty['15000']= (($details[$prefix.'quantity10000']+$digital_data[0]['quantity10000']+(($palte_price*$arr[1])/15000))+$valve_price);
                //printr($qty);
                foreach($data['quantity'] as $quantity)
            	{
            	    $transportprice_qty_wise=0;
            	    if($data['con_id']!='111')
					{
					    $transport_price_per_pouch=0.04;
					    $total_transport_price=$quantity * $transport_price_per_pouch;
					}
            	    $totalPrice = $quantity * $qty[$quantity];
            	    $per_pouch_price = $qty[$quantity];
            	    $totalPriceWithTax = $pouchPriceWithTax =$igst=$cgst=$sgst=$palte_price_withtax='0';
            	    $tax_type='';
					$total_weight = $quantity * $data['weight'];
					if(isset($taxation_data) && !empty($taxation_data))
					{
						if($data['normalform']=='Out Of Gujarat')
						{
							$totalPriceWithTax = $totalPrice+($totalPrice*$taxation_data['igst']/100);
							$pouchPriceWithTax = $per_pouch_price+($per_pouch_price*$taxation_data['igst']/100);
							$palte_price_withtax= $palte_price+($palte_price*$taxation_data['igst']/100);
							$igst =$taxation_data['igst'];
						}
						else
						{
							$totalPriceWithTax = $totalPrice+($totalPrice*($taxation_data['cgst']+$taxation_data['sgst'])/100);
							$pouchPriceWithTax = $per_pouch_price+($per_pouch_price*($taxation_data['cgst']+$taxation_data['sgst'])/100);
							$palte_price_withtax= $palte_price+($per_pouch_price*($taxation_data['cgst']+$taxation_data['sgst'])/100);
							$cgst =$taxation_data['cgst'];
							$sgst =$taxation_data['sgst'];
						}
						$tax_type=$data['normalform'];
					}
					
					
					// add by sonu for gress price swisspac to sountry 16-10-2019
					$total_color = explode('==',$data['digital_print_color']);
					 $digital_printing_price = $this->getaddProductDetailsForDigitalPrint($product_code_id,$data['con_id'],$data['digital_print_color'],1);
								
					 $template_details = $this->getProductTemplateDetails($product_code_id,$data['con_id'], $data['transpotation'],$data['digital_print_color'],$user_id,$user_type_id);
				       

	                   $color_plate_price_gress = $this->getColorPlatePriceForGress($user_id,$user_type_id,1); 
	                   
                          if($quantity < 200)
                        		{  
                        		    
                        		//	$template_price =$template_details['quantity1000'];
                        			$print_price = $digital_printing_price[0]['quantity200'];
                        		}
                        		else if($quantity >= 200 && $quantity < 500)
                        		{
                        			//$template_price = $template_details['quantity1000'];
                        			$print_price = $digital_printing_price[0]['quantity200']; 
                        		} 
                        		else if($quantity >= 500 && $quantity < 1000)
                        		{
                        	    	//$template_price = $template_details['quantity1000'];
                        			$print_price = $digital_printing_price[0]['quantity500']; 
                        		}
                        		else if($quantity >= 1000 && $quantity < 2000)
                        		{
                        			//$template_price = $template_details['quantity1000'];
                        			$print_price = $digital_printing_price[0]['quantity1000'];
                        		}
                        		else if($quantity >= 2000 && $quantity < 5000)
                        		{
                        	    	//$template_price = $template_details['quantity2000'];
                        			$print_price = $digital_printing_price[0]['quantity1000'];
                        		}
                        		else if($quantity >= 5000 && $quantity < 10000)
                        		{
                        		    //$template_price = $template_details['quantity5000'];
                        			$print_price = $digital_printing_price[0]['quantity1000'];
                        		}
                	            else 
                        		{
                        	    	//$template_price = $template_details['quantity10000'];
                        			$print_price = $digital_printing_price[0]['quantity1000'];
                        		}
                        		  
                              $template_price=$template_details['quantity10000'];                           

                             
                              $color_plate_price_count=(($color_plate_price_gress*$total_color[1])/$quantity);                         
                         

                         $gress_pouch_price= $template_price+$print_price+$color_plate_price_count;		
					
							// end gress price swisspac to sountry 16-10-2019
            	 
            	 
            	    $sql_qty =  "INSERT INTO ".DB_PREFIX."digital_product_quotation_price SET  digital_quotation_id = '".$digital_quotation_id."',digital_product_quotation_id= '".$digital_product_quotation_id."',quantity = '".$quantity."',price = '".$qty[$quantity]."',pouch_price_with_tax = '".$pouchPriceWithTax."',total_price = '".$totalPrice."',total_price_with_tax = '".$totalPriceWithTax."',color_plate_price = '".$palte_price."',color_plate_price_withtax = '".$palte_price_withtax."',currency_code = '".$currCode."',tax_type = '".$tax_type."',igst = '".$igst."',sgst = '".$sgst."',cgst = '".$cgst."',total_transport_price = '".$total_transport_price."',transport_price_per_pouch = '".$transport_price_per_pouch."',weight = '".$total_weight."',valve_base_price='".$valve_price."',gress_pouch_price='".$gress_pouch_price."',template_price='".$template_price."',print_price='".$print_price."',gress_color_plate_price='".$color_plate_price_gress."'";		
	            	$this->query($sql_qty);
	            }
            	
            }
        }//die;
	    return $digital_quotation_id;  
	}
	public function generateQuotationNumber(){
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'digital_quotation'");
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,8,'0',STR_PAD_LEFT);
		return $strpad;
	}
	public function getUserCountry($user_tyep_id,$user_id){
		$sql = "SELECT co.country_id, co.country_code, co.currency_id,co.currency_code FROM " . DB_PREFIX ."address ad LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) WHERE ad.user_id = '".(int)$user_id."' AND ad.user_type_id = '".(int)$user_tyep_id."' AND ad.address_type_id = '0'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getQuotation($digital_quotation_id,$getData = '*',$user_type_id='',$user_id=''){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
			    $sql = "SELECT *  FROM " . DB_PREFIX ." digital_product_quotation dq INNER JOIN digital_quotation as dqi ON(dq.digital_quotation_id=dqi.digital_quotation_id)  WHERE dq.digital_quotation_id = '".(int)$digital_quotation_id."'";
			}else{
				if($user_type_id == 2){
					$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					$set_user_id = $parentdata->row['user_id'];
					$set_user_type_id = $parentdata->row['user_type_id'];
				}else{
					$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
					$set_user_id = $user_id;
					$set_user_type_id = $user_type_id;
				}
				$str = '';
				if($userEmployee){
					$str = ' OR ( dqi.user_id IN ('.$userEmployee.') AND dqi.user_type_id = 2 ) ';
				}
				$sql = "SELECT *  FROM " . DB_PREFIX ."digital_product_quotation dq INNER JOIN " . DB_PREFIX ."digital_quotation as dqi ON(dq.digital_quotation_id=dqi.digital_quotation_id) WHERE dq.digital_quotation_id = '".(int)$digital_quotation_id."' AND (( dqi.user_id = '".(int)$set_user_id."' AND dqi.user_type_id = '".(int)$set_user_type_id."') $str ) ";
			}
		}else{
			$sql = "SELECT * FROM " . DB_PREFIX ."digital_product_quotation dq,digital_quotation dqi WHERE dq.digital_quotation_id=dqi.digital_quotation_id  AND dq.digital_quotation_id = '".(int)$quotation_id."'";
		}
		//echo $sql;
		$data = $this->query($sql);
		return $data->rows;
	}
	public function getQuotationQuantity($digital_product_quotation_id)
	{
	    $data = $this->query("SELECT * FROM digital_product_quotation_price as dqp,digital_product_quotation dq  WHERE dq.digital_product_quotation_id = '".$digital_product_quotation_id."' AND dq.digital_product_quotation_id=dqp.digital_product_quotation_id ORDER BY dqp.digital_product_quotation_price_id ");
	    $return = array();
		if($data->num_rows){
		    return $data->rows; 
		}
		else
		    return false;
	}
	public function ProductMake($make_id){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0'  AND make_id =".$make_id;
	    $data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getSpoutname($product_spout_id) 
	{
        $sql = "select * from " . DB_PREFIX ."product_spout where product_spout_id = '".$product_spout_id."'";
        $data = $this->query($sql);
        if($data->num_rows){
			return $data->row;
        }
        else {
            return false;
        }
    }
    public function getZipperName($product_zipper_id) {
		$sql = "select * from " . DB_PREFIX ."product_zipper where product_zipper_id = '".$product_zipper_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
    public function getAccessorieName($product_accessorie_id) {
		$sql = "select * from " . DB_PREFIX ."product_accessorie where product_accessorie_id = '".$product_accessorie_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	public function ProductSize($size_master_id)
	{
         $sql  = "SELECT * FROM size_master WHERE size_master_id = '".$size_master_id."' "; 
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
	public function saveQuotation($digital_quotation_id) 
	{
		$sql = "UPDATE " .DB_PREFIX . "digital_quotation  SET quotation_status = '1' WHERE digital_quotation_id = '".$digital_quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
    public function getQuotationData($user_id,$user_type_id,$cond='',$filter_array=array(),$option) 
	{
		if($user_id == 1 && $user_type_id == 1){
		    $sql = "SELECT GROUP_CONCAT(DISTINCT dq.product_name SEPARATOR ',') as name,dqi.*,c.country_name  FROM " . DB_PREFIX ." digital_product_quotation dq, digital_quotation as dqi,country as c   WHERE  dqi.is_delete=0   AND  dqi.country_id = c.country_id AND  dq.digital_quotation_id= dqi.digital_quotation_id";
		}else{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( dqi.user_id IN ('.$userEmployee.') AND dqi.user_type_id = 2 ) ';
			}
			$sql = "SELECT  GROUP_CONCAT(DISTINCT dq.product_name SEPARATOR ',') as name,dqi.* ,c.country_name  FROM " . DB_PREFIX ."digital_product_quotation dq, digital_quotation as dqi,country as c   WHERE  dqi.is_delete=0  AND dqi.country_id = c.country_id AND dq.digital_quotation_id= dqi.digital_quotation_id  AND (( dqi.user_id = '".(int)$set_user_id."' AND dqi.user_type_id = '".(int)$set_user_type_id."') $str )   ";
		}
        if(!empty($filter_array)) {
			if(!empty($filter_array['quotation_no'])){
				$sql .= " AND dqi.digital_quotation_no LIKE '%".$filter_array['quotation_no']."%'";
			}
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND dqi.client_name LIKE '%".addslashes($filter_array['customer_name'])."%'";
			}
			if(!empty($filter_array['customer_email'])){
				$sql .= " AND dqi.email LIKE '%".addslashes($filter_array['customer_email'])."%'";
			}
			if(!empty($filter_array['date'])){
				$sql .= " AND date(dqi.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}	
			if(!empty($filter_array['product_name'])){
				$sql .= " AND dq.product_name = '".$filter_array['product_name']."' ";
			}
			if(!empty($filter_array['country'])){
				$sql .= " AND dqi.country_id = '".$filter_array['country']."'";
			}
			if(!empty($filter_array['postedby']))
			{
			    $spitdata = explode("=",$filter_array['postedby']);
				$sql .="AND dqi.user_type_id = '".$spitdata[0]."' AND dqi.user_id = '".$spitdata[1]."'";
			}
        }
        if(isset($cond)){
				$sql .= $cond;
		}
        $sql .= " GROUP BY dqi.digital_quotation_id";
		if (isset($option['sort'])) {		
				
			$sql .= " ORDER BY " . $option['sort'];	
		} 
        if (isset($option['order']) && ($option['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
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
		//echo $sql;
	    $data = $this->query($sql);
        if($data->num_rows){
			return $data->rows;
		}
		else {
			return false;
		}
	}
    public function Digital_quotation_mail($digital_quot_id,$to_email='',$setQuotationCurrencyId='',$secondary_curr='',$sec_currency_rate='')
	{
	        $html ="";  
            $data = $this->getQuotation($digital_quot_id,$getData,$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
            //printr($data);die;
            $data_details = $this->getQuotationDetails($digital_quot_id);
            $addedByinfo  = $this->getUser($data_details['user_id'],$data_details['user_type_id']);
            $user_details = $this->gettermsandconditions($data_details['user_id'],$data_details['user_type_id']);       
            
            $i=1;
            $new_data=array();
            //if( $_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
            //{ 
                foreach($data as $dat)
    		    {
    		   		 $result = $this->getDigiQuotationQuantity($dat['digital_product_quotation_id']);
    				 if($result!='')
    				    $quantityData[] =$result;
    		    }
    		    $sub =$dat[0]['digital_quotation_no'].' '.$dat[0]['client_name'];
    		    foreach($quantityData as $k=>$qty_data)
		        {
			        foreach($qty_data as $tag=>$qty)
        			{  
        				foreach($qty as $q=>$arr)
    				    {   //printr($arr);
    					    if($arr[0]['volume']!='')
    						{ 
    							if($s!=$qty)
    							{
    								$sub2.=$arr[0]['volume'].' , ';
    								$s=$qty;
    							}
    						}
    					    $new_data[$arr[0]['text']][$arr[0]['dimension_with_unit']][$q][$tag][]=$arr[0];
        					if($dat['product_id'] != '10')
        					{
        						$val123 ='';
        						if($arr[0]['quantity_data']['valve']=='With Valve')
        						    $val123 = $arr['quantity_data']['valve'];
        						    
        						$sub1= ' '.$arr[0]['zipper_name'].' '.$val123.''.$sub2; 
        					}
        					else
        						$sub1= $sub2; 
        				}
            		}	
        		}
        		$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
        	
    			/*$currency_rate[]=array('currency_rate'=>1,'user'=>0);
    			if($dat['customer_email'] != '' || $toEmail != '')
    				$currency_rate[]=array('currency_rate'=>($selCurrency['currency_rate']!='')?$selCurrency['currency_rate']:1,'user'=>1);
    			else
    				$currency_rate[]=array('currency_rate'=>1,'user'=>2);*/
    				
    			$html .='<table border="0px">';
    			if($secondary_curr!='')
    			{
    				if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['user_type_id'] =='2' ) && $data[0]['admin_user_id']=='10' )
    				    $html.='';
    				else
    				    $html .='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp;'.$secondary_curr.'</b></div><br><br>';
    			}
    			//printr($html);die;
        		
            		$sub1= substr($sub1,0,-3);
    		        $sub=$sub.$sub1;
    		        $i=1;
    		        foreach($new_data as $key=>$value)
    				{
    					foreach($value as $size=>$qty_data)
    					{
    						//printr($dat['country_id']);
    						(int)$size= preg_replace("/\([^)]+\)/","",$size);
    						$price=$m ='';
        					foreach($qty_data as $qty=>$transport)
        					{
    					        (int)$qty= preg_replace("/\([^)]+\)/","",$qty);
        						foreach($transport as $k=>$records1)
        						{
        						   foreach($records1 as $records)
        						   {
            						   
            						    if($secondary_curr!='')
            							{
            								
            								$selCurrency_currency_code=$secondary_curr;
            								$extra_profit=$records['quantity_data']['price']*$sec_currency_rate;
            								
            							}
            							else
            							{
            								$selCurrency_currency_code=$selCurrency['currency_code'];
            								$extra_profit=$records['quantity_data']['price'];
            							
            							}
            						   
            						   $color_detail = $this->getColor($records['quantity_data']['color_id']);
            						   $plus_minus = $this->getDigitalQtyplusminus($records['quantity_data']['quantity']); 
            						   $total_color=explode('==', $records['quantity_data']['total_color']);
            						    if($dat['country_id']=='42')
                                		{
                                			if($k=='By Air')
                                				$k='Rush Order';
                                			if($k=='By Sea')
                                				$k='Normal Order';	
                                		} 
            						   //<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quoted Printing in  : </b>'.$total_color[1].' Colors &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
            						   $price .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Printing in '.$total_color[1].' Color Price: </b>'.$extra_profit.' '.$selCurrency_currency_code.'  Per 1 Bag <b>{ For '.floatval($records['quantity_data']['quantity']).' Bags in each design plus or minus '.$plus_minus['plus_minus_quantity'].' Bags in each design }</b></span> -  <span style="color:green">Price For '.$k.' </span></td></tr>';   
                                       $m ='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp; Make up of pouch: </b> Custom Digital Print on any stock product color of <b>'.$dat['product_name'].'</b>,'.$records['zipper_name'].','.$records['quantity_data']['valve'].','.$records[0]['spout_name'].','.$records['product_accessorie_name'].','.$records['product_accessorie_name'].' </td></tr> ';
                	 	               $printing_effect = $records['quantity_data']['printing_effect'];
                	 	               $plate_price = $records['quantity_data']['currency_code'].' '.$records['quantity_data']['color_plate_price'];
        						   }
        						}
        					}
        					if($dat['product_id']=='12')
                    			$vol_text='liquid volume.';
                    		else
                    			$vol_text='coffee beans Density volume.';
        					$html .='<tr><td colspan="2"><b>'.($i).'&nbsp;&nbsp;&nbsp;Size of pouch: </b>'.$size.'&nbsp;&nbsp;This Size is based on '.$vol_text.' If you want to fill a different product then you may require different size of bag. </td></tr> ';
        					$html .=$m;
        					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp; Printing Ink To Use: </b>'.$printing_effect.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr><tr><td colspan="2"><br></td></tr>';
        					$html .=$price;
        	    	        $html .='<tr><td colspan="2"><br></td></tr>';
        	    	        //$html .='<tr><td colspan="2"><b>   Set up Cost :</b>'.$plate_price.' <span style="color:blue"><b>Per 1 color {Customer has to pay with every order. For example if you have 2 color then it would be set up cost multiply by number of color.}</b></span></td></tr>';
            	   	        $html .='<tr><td colspan="2"><br></td></tr>';
            	   	        $i++;
    					}
        	    	}
        	     
				$html .= '<tr><td colspan="2"><b style="color:green">Front side printing and backside printing it will be counted as two different colors, so if you have black color on front side and same black color on backside then it will be count as two different colors. </b></span></td></tr>';
				$html .= '<tr><td colspan="2"><b style="color:green">In Digital printing color/shades would vary up to 25% due to nature of printing technology limitation. We cannot match Pantone Color exactly, there would be 25% variation in color.<br/>If you want to match precise color then we have to use rotogravure technology. Minimum custom print run starts from 10,000 pieces.</b></span></td></tr>';
                $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
            	$html .='<tr><td colspan="2"><span style="color:red"><b>Note: Taxes and transportation extra on above price.</b></span></td></tr>';
            //	$html .='<tr><td colspan="2"><b>Delivery schedule:</b> Approx 3-4 Weeks after receiving advance. </td></tr>'; chnage by sonu 19-09-2019 told by satveer
            	$html .='<tr><td colspan="2"><b>Delivery schedule:</b> Approximately 4 Weeks after receiving advance. </td></tr>';
            	$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
            	$html .='<tr><td colspan="2">'.html_entity_decode($user_details['digital_quo_termsandconditions']).'</td></tr>';
            	$html .= '<tr><td colspan="2">&nbsp;</td></tr>'; 
            	$html .= '<tr><td colspan="2">---</td></tr>';
            	$html .='<tr><td colspan="2">'.html_entity_decode($addedByinfo['email_signature']).'</td></tr>';
            	$html.='</table>';
				/*printr($sub);
				printr($html);
				die;*/ 
            
    		$subject = $data_details['digital_quotation_no'].' '.$data_details['client_name'];
			$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);
			$email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);
            if($to_email!='')
                $email_temp[]=array('html'=>$html,'email'=>$to_email);
            if($data_details['user_type_id']==2)
            {
                $admin_email=$this->getUser($addedByinfo['user_id'],'4');
                if($admin_email['email1']!='' && $admin_email['email1']!=$addedByinfo['email'])
                	$email_temp[]=array('html'=>$html,'email'=>$admin_email['email1']);
            }
			$form_email=$addedByinfo['email'];
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(1); 
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			$path = HTTP_SERVER."template/product_quotation.html";
			$output = file_get_contents($path);  
			$search  = array('{tag:header}','{tag:details}');
			$signature = 'Thanks.';                   
              
			foreach($email_temp as $val)
			{
				$toEmail =$form_email;
				$firstTimeemial = 1;
				$subject = $data_details['digital_quotation_no'].' '.$data_details['client_name']; 
				$message = '';
				if($val['html'])
				{
				$tag_val = array(
						"{{productDetail}}" =>$val['html'],
						"{{signature}}"	=> $signature,
					);
					if(!empty($tag_val))
					{
						$desc = $temp_desc;
						foreach($tag_val as $k=>$v)
						{
							@$desc = str_replace(trim($k),trim($v),trim($desc));
						} 
					} 

					$replace = array($subject,$desc);
					$message = str_replace($search, $replace, $output);
				}
		        /*if($to_email!=''){
                     printr($form_email);printr($to_email);printr($val['email']);printr($message);			 
				}else*/
			/*if($to_email!='')
				printr($message);die;*/
				//if($to_email=='')
				    send_email($val['email'],$form_email,$subject,$message,'','');
				   
				   //send_email('erp@swisspac.net',$form_email,$subject,$message,'','');
			}
		    if($to_email!='')
		        $this->query("INSERT INTO `" . DB_PREFIX . "digital_quotation_email_history` SET digital_quotation_id = '".$digital_quot_id."', customer_name = '".addslashes($data_details['client_name'])."',customer_email = '".$data_details['email']."', user_type_id = '" .$_SESSION['LOGIN_USER_TYPE']. "', user_id = '" .$_SESSION['ADMIN_LOGIN_SWISS']. "', to_email = '".$to_email."', from_email = '" .$form_email. "', admin_default_email='".ADMIN_EMAIL."',currency_code='".$quantityData[0]['currency_code']."', sent_date = NOW()");
	}
	public function getQuotationDetails($digital_quotation_id) 
	{
		$sql ="SELECT * FROM digital_quotation  WHERE is_delete=0 AND digital_quotation_id ='".$digital_quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
    public function gettermsandconditions($user_id,$user_type_id){
		if($user_type_id == '4')
		{
		    $sql = "SELECT ts.termsandconditions,	digital_quo_termsandconditions,ts.user_id,ts.user_type_id FROM termsandconditions ts WHERE ts.user_id = '".$user_id."' AND ts.user_type_id = '4'  AND ts.is_delete = '0' LIMIT 1";
		}
		else
		{
		    $sql = "SELECT ts.termsandconditions,digital_quo_termsandconditions,ts.user_id,ts.user_type_id,e.user_id FROM termsandconditions ts,employee e WHERE e.employee_id ='".$user_id."' AND ts.user_id = e.user_id AND ts.user_type_id = '4' AND ts.is_delete = '0' LIMIT 1";
		}
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
	public function getDigitalQtyplusminus($qty)
	{
        $sql="SELECT * FROM digital_quantity WHERE quantity='".$qty."'";		     
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
	public function getProduct($make_id) 
	{
		$sql = "SELECT * from " . DB_PREFIX ."product where make_pouch_available LIKE '%".$make_id."%' AND product_id IN (3,7,1,20,19,10,4,12,13,16,42,30,31,53,54,50,22,8) ORDER BY product_name ASC ";//
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else {
			return false;
		}
	}
	public function CheckQuotationTax($digital_product_quotation_id)
	{
        $sql = "SELECT tax_type,currency_code as currency FROM " . DB_PREFIX ."digital_product_quotation_price WHERE digital_product_quotation_id = '".(int)$digital_product_quotation_id."' ORDER BY digital_product_quotation_id ASC LIMIT 1";
		$data = $this->query($sql);
		return $data->row;
	}
	public function getDigiQuotationQuantity($digital_product_quotation_id,$lang_details=array())
	{
	    $data = $this->query("SELECT * FROM digital_product_quotation_price as dqp,digital_product_quotation dq  WHERE dq.digital_product_quotation_id = '".$digital_product_quotation_id."' AND dq.digital_product_quotation_id=dqp.digital_product_quotation_id ORDER BY dqp.digital_product_quotation_price_id ");
	    $return = array();
		if($data->num_rows){
		    foreach($data->rows as $qunttData){  
		        $spout_detail = $this->getSpoutName($qunttData['spout_id']);
        		$acce_detail = $this->getAccessorieName($qunttData['accessorie_id']);
        		$acce_sec_detail = $this->getAccessorieName($qunttData['accessorie_sec_id']);
        		$zip_detail = $this->getZipperName($qunttData['zipper_id']);
        		$size_detail = $this->ProductSize($qunttData['size_id']);
        		$make_detail = $this->ProductMake($qunttData['make_id']);
        	    if($acce_detail['product_accessorie_name']=='No Accessorie')
                    $acce_detail['product_accessorie_name'] = $acce_detail['product_accessorie_name'].'s';
                    
        		$txt = $zip_detail['zipper_name'].', '.$qunttData['valve'].',<br>'.$spout_detail['spout_name'].', With '.$acce_detail['product_accessorie_name'].', <br> '.$acce_sec_detail['product_accessorie_name'].'<br>';
        		
        		if($qunttData['valve']=='With Valve')
				    $valve_txt = $lang_details['with_valve'];
				else
				    $valve_txt = $lang_details['no_valve'];
        		
        		$txt_lang = $zip_detail['zipper_name_'.$lang_details['language']].' '.$valve_txt.', '.$acce_detail['product_accessorie_name_'.$lang_details['language']].', <br> '.$acce_sec_detail['product_accessorie_name_'.$lang_details['language']].'<br>';
		        if($qunttData['product_id']=='10')
        			$mes_text=' inch';
        		else
        			$mes_text=' mm';
		        
		        $return[$qunttData['transport_type']][$qunttData['quantity']][] = array(
		                'text' 		=> $txt,
		                'text_lang' 		=> $txt_lang,
		                'quantity_data' =>$qunttData,
		                'dimension'=>'<b>'.$size_detail['volume'].'</b> <br>['.$size_detail['width'].'X'.$size_detail['height'].'X'.$size_detail['gusset'].']',
		                'make_name'=>$make_detail['make_name'],
		                'zipper_name'=>$zip_detail['zipper_name'],
		                'spout_name'=>$spout_detail['spout_name'],
		                'product_accessorie_name'=>$acce_detail['product_accessorie_name'],
		                'product_accessorie_name_sec'=>$acce_sec_detail['product_accessorie_name'],
		                'volume'=>$size_detail['volume'],
		                'dimension_with_unit'=>'<b>'.$size_detail['volume'].'</b>['.$size_detail['width'].' '.$mes_text.'X'.$size_detail['height'].' '.$mes_text.'X'.$size_detail['gusset'].' '.$mes_text.']',
		        );
		    }
		    return $return;
		}
		else
		    return false;
	}
	public function getEmailHistories($quotation_id)
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "digital_quotation_email_history WHERE digital_quotation_id ='".$quotation_id."' ORDER BY sent_date DESC ");
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	public function getUserList(){
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
		$data = $this->query($sql);
		return $data->rows;
	}
	public function getProductList(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' AND product_id IN(3,7,1,20,19,10,4,12,13,16,42,30,31,53,54,50,22) ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
    public function deleteQuotation($quotation_id){
		$this->query("DELETE  FROM " . DB_PREFIX . "digital_quotation  WHERE digital_quotation_id IN ('".$quotation_id."')");
    	$this->query("DELETE  FROM " . DB_PREFIX . "digital_product_quotation  WHERE digital_quotation_id IN ('".$quotation_id."')");
    	$this->query("DELETE  FROM " . DB_PREFIX . "digital_product_quotation_price  WHERE digital_quotation_id IN ('".$quotation_id."')"); 				
		
	}
	
	public function getexpiredate_custmorder($user_id,$user_type_id)
	{
		if($user_type_id=='4')
		{
			$sql="SELECT Multi_Quotation_expiry_days FROM international_branch WHERE international_branch_id='".$user_id."'";
			$data=$this->query($sql);
			if($data->num_rows)
				return $data->row;
			else
				return false;
		}
		if($user_type_id=='2')
		{
			$sql="SELECT user_id FROM employee WHERE employee_id='".$user_id."' AND user_type_id='4'";
			$data=$this->query($sql);
			if($data->num_rows)
			{
				$sql1="SELECT Multi_Quotation_expiry_days FROM international_branch WHERE international_branch_id='".$data->row['user_id']."'";
				$data1=$this->query($sql1);
				if($data1->num_rows)
					return $data1->row;
				else
					return false;
			}
			
		}
	
	}
	public function getQuotationCurrecy($selCurrencyId,$source){
			$data = $this->query("SELECT * FROM " . DB_PREFIX . "digital_quotation_currency WHERE product_quotation_currency_id ='".$selCurrencyId."' AND source = '".$source."' ");
		if($data->num_rows){
			$result =array(
							'product_quotation_currency_id' => $data->row['product_quotation_currency_id'],
							'currency_id' => $data->row['currency_id'],
							'currency_code' => $data->row['currency_code'],
							'currency_rate' => $data->row['currency_rate'],
							'currency_base_rate' => $data->row['currency_base_rate'],
							'source' => 1,
							'date_added' => $data->row['date_added'],
						);
			return $result;
		}else{
			return false;
		}
	}
	public function setQuotationCurrency($quotation_id,$currency_code,$currencyRate,$source,$sec_curr='',$sec_curr_rate=''){
	
			$this->query("INSERT INTO " . DB_PREFIX . "digital_quotation_currency SET product_quotation_id = '".$quotation_id."', currency_id = '', currency_code = '".$currency_code."', currency_rate = '".(float)$currencyRate."', currency_base_rate = '', source = '".$source."',sec_curr='".$sec_curr."',sec_curr_rate='".$sec_curr_rate."',date_added = NOW()");
			return $this->getLastId();
	}
	
	// add by sonu for gress price swisspac to sountry 16-10-2019
	public function getColorPlatePriceForGress($user_id,$user_type_id,$stock_print='')
	{
        $field = 'color_plate_price_swisspac';
        if($stock_print=='Foil Print')
            $field = 'foil_plate_price_swisspac';
        if($user_type_id=='2')
		{ 
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'";
			$dataadmin = $this->query($sqladmin);
		    $admin_user_id =  $dataadmin->row['user_id'];
		    $sql="SELECT $field FROM international_branch WHERE international_branch_id='".$admin_user_id ."'";
		}
		elseif($user_type_id=='4')
		{
			$sql="SELECT $field FROM international_branch WHERE international_branch_id='".$user_id."'";
		}
	//	echo $sql;die;
	    $data=$this->query($sql); 
		if($data->num_rows)
		{
			return $data->row[$field];
		}
		else
		{
			return false;
		}
	}
	
	
	public function getProductTemplateDetails($product_code_id,$country_id,$transport,$color,$user_id,$user_type_id){


		// printr($color.''.$transport);
		if($user_type_id == 2)
		{
			$sql1 = "SELECT * FROM employee WHERE employee_id = '".$user_id."'";
			$data = $this->query($sql1);
			$user_id=$data->row['user_id'];
		}
		else
		{
			$user_id=$user_id;
		}
		$countryid='"'.$country_id.'"';
 
		
	
		$sql1="SELECT pz.zipper_name,spout.spout_name,pc.valve,pm.volume,pc.color,pc.product,pa.product_accessorie_name FROM product_code as pc, pouch_volume as pm,product_zipper as pz,product_spout as spout,product_accessorie as pa WHERE pm.volume = (Select CONCAT(p.volume,' ',m.measurement) as volume from product_code as p, template_measurement as m WHERE p.spout=spout.product_spout_id AND p.measurement=m.product_id AND p.product_code_id='".$product_code_id."') AND pc.product_code_id='".$product_code_id."' AND pc.zipper=pz.product_zipper_id AND pc.accessorie=pa.product_accessorie_id";
		$data1 = $this->query($sql1);
       
		if($data1->num_rows)
		{
		    $color_id='"'.$data1->row['color'].'"';
			$sql = "SELECT pts.*,p.product_name,p.product_id,pt.user,pt.country,pt.product_template_id FROM " . DB_PREFIX . "product_template_size pts,product p,product_template as pt  WHERE  pt.product_name='".$data1->row['product']."' AND  pt.country LIKE '%".$countryid."%' AND  pt.transportation_type = '".$transport."' AND pt.user = '".$user_id."' AND pts.template_id=pt.product_template_id AND pt.product_name=p.product_id AND pts.valve='".$data1->row['valve']."' AND pts.spout='".$data1->row['spout_name']."' AND pts.zipper='".$data1->row['zipper_name']."' AND pts.accessorie='".$data1->row['product_accessorie_name']."' AND pts.is_delete = '0' AND pt.status='0' AND REPLACE(pts.volume, ' ', '') = REPLACE('".$data1->row['volume']."', ' ', '') AND pts.color LIKE '%".$color_id."%' ";	
			$data = $this->query($sql);
		    //echo $sql;
			$result = $data->row;
			return $result;
		}
		else
			return false;

	}
	//end
	//made by kinjal for the other lang. on (21-10-2019)
	public function getLangDetails($lang_id,$user_id,$user_type_id)
	{
	    $cond = ' AND user_id ='.$user_id.' AND user_type_id = '.$user_type_id;
	    if($user_type_id==2)
	        $cond = ' AND find_in_set ('.$user_id.',emp_id) ';
	    $data = $this->query("SELECT * FROM " . DB_PREFIX . "multi_quote_lang_format WHERE lang_id = '".$lang_id."' ".$cond." ");
	    if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function sendQuotationEmailInOtherLang($digital_quot_id,$to_email='',$setQuotationCurrencyId='',$secondary_curr='',$sec_currency_rate='')
	{
	        $html ="";  
            $data = $this->getQuotation($digital_quot_id,$getData,$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
            $data_details = $this->getQuotationDetails($digital_quot_id);
            $addedByinfo  = $this->getUser($data_details['user_id'],$data_details['user_type_id']);
            $user_details = $this->gettermsandconditions($data_details['user_id'],$data_details['user_type_id']);       
            $lang_details = $this->getLangDetails($addedByinfo['lang_id'],$data_details['user_id'],$data_details['user_type_id']);
            //printr($lang_details);die;
            $i=1;
            $new_data=array();
            
                foreach($data as $dat)
    		    {
    		   		 $result = $this->getDigiQuotationQuantity($dat['digital_product_quotation_id'],$lang_details);
    				 if($result!='')
    				    $quantityData[] =$result;
    		    }
    		    $sub =$dat[0]['digital_quotation_no'].' '.$dat[0]['client_name'];
    		    foreach($quantityData as $k=>$qty_data)
		        {
			        foreach($qty_data as $tag=>$qty)
        			{  
        				foreach($qty as $q=>$arr)
    				    {   //printr($arr);
    					    if($arr[0]['volume']!='')
    						{ 
    							if($s!=$qty)
    							{
    								$sub2.=$arr[0]['volume'].' , ';
    								$s=$qty;
    							}
    						}
    					    $new_data[$arr[0]['text_lang']][$arr[0]['dimension_with_unit']][$q][$tag][]=$arr[0];
        					if($dat['product_id'] != '10')
        					{
        						$valve_txt ='';
        						if($arr[0]['quantity_data']['valve']=='With Valve')
        						    $valve_txt = $lang_details['with_valve'];
        						
        						$zip_nm = $this->query("SELECT * FROM product_zipper WHERE zipper_name = '".$arr[0]['zipper_name']."'");
        						$sub1= ' '.$zip_nm->row['zipper_name_'.$lang_details['language']].' '.$valve_txt.''.$sub2; 
        					}
        					else
        						$sub1= $sub2; 
        				}
            		}	
        		}
        		$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
        	    $html .='<table border="0px">';
    			if($secondary_curr!='')
    			{
    				if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['user_type_id'] =='2' ) && $data[0]['admin_user_id']=='10' )
    				    $html.='';
    				else
    				    $html .='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp;'.$secondary_curr.'</b></div><br><br>';
    			}
    			
            		$sub1= substr($sub1,0,-3);
    		        $sub=$sub.$sub1;
    		        $i=1;
    		        foreach($new_data as $key=>$value)
    				{
    					foreach($value as $size=>$qty_data)
    					{
    						//printr($dat['country_id']);
    						(int)$size= preg_replace("/\([^)]+\)/","",$size);
    						$price=$m ='';
        					foreach($qty_data as $qty=>$transport)
        					{
    					        (int)$qty= preg_replace("/\([^)]+\)/","",$qty);
        						foreach($transport as $k=>$records1)
        						{
        						   foreach($records1 as $records)
        						   {
            						   
            						    if($secondary_curr!='')
            							{
            								
            								$selCurrency_currency_code=$secondary_curr;
            								$extra_profit=$records['quantity_data']['price']*$sec_currency_rate;
            								
            							}
            							else
            							{
            								$selCurrency_currency_code=$selCurrency['currency_code'];
            								$extra_profit=$records['quantity_data']['price'];
            							
            							}
            						   
            						   $color_detail = $this->getColor($records['quantity_data']['color_id']);
            						   $plus_minus = $this->getDigitalQtyplusminus($records['quantity_data']['quantity']); 
            						   $total_color=explode('==', $records['quantity_data']['total_color']);
            						    
                            			if($k=='By Air')
                            				$k=$lang_details['express_delivery'];
                            			if($k=='By Sea')
                            				$k=$lang_details['normal_delivery'];	
                                	
                                	    $product_name = $this->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '".$dat['product_id']."' ");
                                	    $zip_nm = $this->query("SELECT * FROM product_zipper WHERE zipper_name = '".$records['zipper_name']."'");
                                	    $spout_sql=$this->query("SELECT * FROM product_spout WHERE spout_name = '".$records[0]['spout_name']."'");
                                	    $acc_sql=$this->query("SELECT * FROM product_accessorie WHERE product_accessorie_name = '".$records['product_accessorie_name']."'");
                                	    $acc_cor_sql=$this->query("SELECT * FROM product_accessorie WHERE product_accessorie_name = '".$records['product_accessorie_name']."'");
                                	    $effe_nam = $this->query("SELECT * FROM printing_effect WHERE effect_name LIKE '%".$records['quantity_data']['printing_effect']."%'");
                                	    
                                	    if($records['quantity_data']['printing_effect'] == 'Matt Finish Ink')
                                	        $effe_nam = $lang_details['matt_finish_ink_digi'];
                                	    else if ($records['quantity_data']['printing_effect'] == 'Gloss Finish Ink')
                                	        $effe_nam = $lang_details['gloss_finish_ink_digi'];
                                	    else
                                	        $effe_nam = $lang_details['matt_gloss_finish_ink_digi'];
                                	        
		                                $valve ='';
        						        if($records['quantity_data']['valve']=='With Valve')
        						            $valve = $lang_details['with_valve'];
        						        else
        						            $valve = $lang_details['no_valve'];
        						    
            						   $price .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$lang_details['print_in_clr_digi'].' '.$total_color[1].' '.$lang_details['color_text'].': </b>'.$extra_profit.' '.$selCurrency_currency_code.'  '.$lang_details['per_1'].' '.$lang_details['bag'].' <b>{ '.$lang_details['for_pouch'].' '.floatval($records['quantity_data']['quantity']).' '.$lang_details['bags'].' '.$lang_details['in_each_design_digi'].' '.$lang_details['plus_or_minus'].' '.$plus_minus['plus_minus_quantity'].' '.$lang_details['bags'].' '.$lang_details['in_each_design_digi'].' }</b></span> -  <span style="color:green">'.$lang_details['price'].' '.$lang_details['per_1'].' '.$k.' </span></td></tr>';   
                                       $m ='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp; '.$lang_details['make_up_pouch'].': </b> '.$lang_details['digi_color_note'].' <b>'.$product_name->row['product_name_'.$lang_details['language']].'</b>, '.$zip_nm->row['zipper_name_'.$lang_details['language']].', '.$valve.','.$spout_sql->row['spout_name_'.$lang_details['language']].', '.$acc_sql->row['product_accessorie_name_'.$lang_details['language']].', '.$acc_cor_sql->row['product_accessorie_name_'.$lang_details['language']].' </td></tr> ';
                	 	               $printing_effect = $effe_nam;
                	 	               $plate_price = $records['quantity_data']['currency_code'].' '.$records['quantity_data']['color_plate_price'];
        						   }
        						}
        					}
        					if($dat['product_id']=='12')
                    			$vol_text=$lang_details['note_liquid_of_digi'];
                    		else
                    			$vol_text=$lang_details['note_coffee_of_digi'];
                    			
        					$html .='<tr><td colspan="2"><b>'.($i).'&nbsp;&nbsp;&nbsp;'.$lang_details['size_of_pouch_digi'].': </b>'.$size.'&nbsp;&nbsp;'.$vol_text.'</td></tr> ';
        					$html .=$m;
        					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp; '.$lang_details['printing_ink_to_use_digi'].': </b>'.$printing_effect.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr><tr><td colspan="2"><br></td></tr>';
        					$html .=$price;
        	    	        $html .='<tr><td colspan="2"><br></td></tr>';
        	    	        $html .='<tr><td colspan="2"><br></td></tr>';
            	   	        $i++;
    					}
        	    	}
        	     
				$html .= $lang_details['digi_print_note'];
                $html .= '<tr><td colspan="2">&nbsp;</td></tr>';
            	$html .='<tr><td colspan="2"><span style="color:red"><b>'.$lang_details['tax_note_digi'].'</b></span></td></tr>';
                $html .='<tr><td colspan="2">'.$lang_details['delivery_notes_digi'].' </td></tr>';
            	$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
            	$html .='<tr><td colspan="2">'.html_entity_decode($lang_details['digi_terms_cond']).'</td></tr>';
            	$html .= '<tr><td colspan="2">&nbsp;</td></tr>'; 
            	$html .= '<tr><td colspan="2">---</td></tr>';
            	$html .='<tr><td colspan="2">'.html_entity_decode($addedByinfo['email_signature']).'</td></tr>';
            	$html.='</table>';
				
    		$subject = $data_details['digital_quotation_no'].' '.$data_details['client_name'];
			$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);
			$email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);
            if($to_email!='')
                $email_temp[]=array('html'=>$html,'email'=>$to_email);
            if($data_details['user_type_id']==2)
            {
                $admin_email=$this->getUser($addedByinfo['user_id'],'4');
                if($admin_email['email1']!='' && $admin_email['email1']!=$addedByinfo['email'])
                	$email_temp[]=array('html'=>$html,'email'=>$admin_email['email1']);
            }
			$form_email=$addedByinfo['email'];
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(1); 
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			$path = HTTP_SERVER."template/product_quotation.html";
			$output = file_get_contents($path);  
			$search  = array('{tag:header}','{tag:details}');
			$signature = 'Thanks.';                   
            foreach($email_temp as $val)
			{
				$toEmail =$form_email;
				$firstTimeemial = 1;
				$subject = $data_details['digital_quotation_no'].' '.$data_details['client_name']; 
				$message = '';
				if($val['html'])
				{
				$tag_val = array(
						"{{productDetail}}" =>$val['html'],
						"{{signature}}"	=> $signature,
					);
					if(!empty($tag_val))
					{
						$desc = $temp_desc;
						foreach($tag_val as $k=>$v)
						{
							@$desc = str_replace(trim($k),trim($v),trim($desc));
						} 
					} 

					$replace = array($subject,$desc);
					$message = str_replace($search, $replace, $output);
				}
		        
				send_email($val['email'],$form_email,$subject,$message,'','');
			}
	}
}
    

	

?>