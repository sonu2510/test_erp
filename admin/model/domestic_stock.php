<?php
class domestic_stock extends dbclass{
	
	public function getActiveProductCode()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE is_delete=0 AND status=1 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getActiveProduct()
	{
        $pro=$this->query("SELECT GROUP_CONCAT(DISTINCT(product_id)) as id FROM `catalogue_category` WHERE is_delete=0");
        
      //  printr($pro->row['id']);
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE is_delete=0 AND status=1 AND product_id IN (".$pro->row['id'].") ";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false; 
		}
	}
	public function getProductCd($product_code)
	{
		
		$result=$this->query("SELECT pc.product_code, pc.product_code_id, pc.description, clr.color, pc.volume, pc.measurement,pc.product  FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr WHERE pc.product_code LIKE '%".$product_code."%' AND pc.is_delete=0 AND pc.color=clr.pouch_color_id AND pc.status=1");
		return $result->rows;
	}
	public function addstock($data){
	//	printr($data);die;
		if($data['invoice_no']=='')
		    $data['invoice_no']='';
		$sql = "INSERT INTO `" . DB_PREFIX . "domestic_stock` SET  product_code_id = '".$data['product_code_id_add']."',parent_id ='0' , box_no='".$data['box_no']."' ,qty = '".$data['qty']."', dispatch_qty = '0' ,description='1',is_delete = '0',add_stock_date='".$data['date']."',date_added = NOW(),date_modify = NOW(),created_at = NOW(),updated_at = NOW(),invoice_no='".$data['invoice_no']."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
	  //  echo $sql;
		$this->query($sql);
	}
	public function getRackQty($product_code_id,$user_type_id,$user_id,$box_no='0')
	{	
	  $str_rack='';
		$box=''; 
		if($box_no!=0)
			$box='AND sm.box_no='.$box_no;
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT GROUP_CONCAT(stock_id) as stock_id,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.box_no FROM domestic_stock as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '".$product_code_id."' ".$box." GROUP BY sm.box_no ";//$str_getrackno
		} else {
		
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
				$str = 'OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = "2" ) ';
			}
			$sql = "SELECT GROUP_CONCAT(stock_id) as stock_id,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.box_no  FROM domestic_stock as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '".$product_code_id."' AND ((sm.user_id = '".(int)$set_user_id."' AND sm.user_type_id = '".(int)$set_user_type_id."') $str ) ".$box." GROUP BY sm.box_no ";
			
		}
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function dispatchstock($data)
	{
		$record1 = $this->getRackQty($data['product_code_id_add'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$data['box_no']);
		$partial = explode(',', $record1[0]['grouped_stock_id']);
		
		$final = array();
		$dispatch_qty=0;
		array_walk($partial, function($val,$key) use(&$final){
			list($key, $value) = explode(':', $val);
			$sql3 = "SELECT SUM(dispatch_qty) dispatch_qty FROM " . DB_PREFIX . "domestic_stock WHERE parent_id=".$value."";
			$data3 = $this->query($sql3);
				if($data3->num_rows){
					$dispatch_qty=$data3->row['dispatch_qty'];
				}
			$qty=$key-$dispatch_qty;
			if($qty>0)
				$final[] = array('id'=>$value,'qty'=>$key);	
		});
		
		$dis_qty=$data['qty'];
		foreach($final as $record)
		{	
			if($dis_qty>$record['qty'])
				$final_dis_qty=$record['qty'];
			else
				$final_dis_qty=$dis_qty;
				
			//update query
			$sql="INSERT INTO domestic_stock SET dispatch_qty='".$final_dis_qty."',parent_id='".$record['id']."',box_no='".$data['box_no']."',description=2,date_added=NOW(),date_modify=NOW(),created_at = NOW(),updated_at = NOW(), user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',product_code_id='".$data['product_code_id_add']."'";
			$data1=$this->query($sql);	
			if($dis_qty > $final_dis_qty) 
				$dis_qty=$dis_qty-$final_dis_qty;	
			else
				break;		
					
		}
		
	}
	public function gettotaldispatchSales($stock_id,$user_type_id,$user_id)
	{
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT sum(dispatch_qty) as total FROM domestic_stock WHERE parent_id IN (" .$stock_id. ") AND is_delete=0 " ;
		} else {
		
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
				$str = 'OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 ) ';
			}
		    $sql="SELECT SUM(dispatch_qty) as total FROM domestic_stock WHERE parent_id IN (" .$stock_id. ") AND ((user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."') $str )";
		}
	
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
	public function getUserEmployeeIds($user_type_id,$user_id){
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	public function getproduct_status($user_type_id,$user_id,$filter_data=array(),$option=array()){
		
		$sql = "SELECT sm.user_id,sm.user_type_id,sum(sm.qty)qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id,p.product_name ,pc.product_code,pc.product_code_id,sm.stock_id,pc.description,sm.box_no FROM domestic_stock as sm,product as p,product_code as pc WHERE sm.is_delete=0 AND p.product_id=pc.product AND pc.product_code_id = sm.product_code_id AND parent_id=0  AND sm.qty!=0";						
		
			if(!empty($filter_data)){				
		    	if(!empty($filter_data['product_code'])){
					$sql .= " AND pc.product_code LIKE '%".$filter_data['product_code']."%'";
				}
				if(!empty($filter_data['box']))
				{
					$sql .= " AND sm.box_no LIKE '%".$filter_data['box']."%'";
				}
			}
			$sql.=" GROUP BY sm.box_no,sm.product_code_id";
			if (isset($data['sort'])) {
    			$sql .= " ORDER BY " . $data['sort'];	
    		} else {
    			$sql .= " ORDER BY sm.stock_id";	
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
    
                $sql .= " LIMIT " . (int) $option['start'] . "," . (int) $option['limit'];
            }
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
	public function gettotaldispatch($stock_id)
	{
		$sql="SELECT SUM(dispatch_qty) as total FROM domestic_stock WHERE is_delete=0 AND parent_id IN (" .$stock_id. ")";
		
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
	public function gettotaldispatch_list($product_code_id,$box_no)
	{
		$sql="SELECT SUM(dispatch_qty) as total FROM domestic_stock WHERE is_delete=0 AND product_code_id = '".$product_code_id."' AND box_no = '".$box_no."'";
		
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
	public function getdispatchdetail($user_id,$user_type_id,$stock_id)
	{	
		if($user_type_id == 1 && $user_id == 1){
			$sql="SELECT sm.* FROM domestic_stock as sm WHERE sm.is_delete=0 AND sm.stock_id IN (".$stock_id.") ";
		}
		else
		{
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
				$str = 'AND ( (sm.user_id='.(int)$set_user_id.' AND sm.user_type_id='.(int)$set_user_type_id.') OR (sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ))';
			}
			
			$sql="SELECT sm.* FROM domestic_stock as sm WHERE sm.is_delete=0 AND sm.stock_id IN  (".$stock_id.") $str ";
		}
		
		$sql.=' GROUP BY sm.stock_id ORDER BY sm.date_added ASC';	
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;			
	}
	public function getdispatchdetail_list($user_id,$user_type_id,$product_code_id,$box_no)
	{	
		if($user_type_id == 1 && $user_id == 1){
			//$sql="SELECT sm.* FROM domestic_stock as sm WHERE sm.is_delete=0 AND sm.stock_id IN (".$stock_id.") ";
			$sql="SELECT sm.* FROM domestic_stock as sm WHERE sm.is_delete=0 AND sm.product_code_id = '".$product_code_id."' AND sm.box_no = '".$box_no."' ";
		}
		else
		{
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
				$str = 'AND ( (sm.user_id='.(int)$set_user_id.' AND sm.user_type_id='.(int)$set_user_type_id.') OR (sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ))';
			}
			
			//$sql="SELECT sm.* FROM domestic_stock as sm WHERE sm.is_delete=0 AND sm.stock_id IN (".$stock_id.") $str ";
			$sql="SELECT sm.* FROM domestic_stock as sm WHERE sm.is_delete=0 AND sm.product_code_id = '".$product_code_id."' AND sm.box_no = '".$box_no."' $str ";
		}
		
		$sql.=' GROUP BY sm.stock_id ORDER BY sm.date_added ASC';	
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;			
	}
	public function gettotaldispatchChild($stock_id)
	{
		$sql="SELECT sm.* FROM domestic_stock AS sm WHERE sm.is_delete=0 AND sm.parent_id = " .$stock_id. " ORDER BY date_added ASC";
		$data=$this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function gettotaldispatchChild_list($product_code_id,$box_no)
	{
		$sql="SELECT sm.* FROM domestic_stock AS sm WHERE sm.is_delete=0 AND sm.product_code_id = '".$product_code_id."' AND sm.box_no = '".$box_no."' ORDER BY date_added ASC";
		$data=$this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function getUser($user_id,$user_type_id)
	{
		$cond = '';
		
		if($user_type_id==2)
		{
			$sql = "SELECT ib.stock_order_price,ib.user_name, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email,ib.user_type_id,ib.user_id FROM " . DB_PREFIX ."employee ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '".(int)$user_type_id."' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ".$cond."";
		}
		elseif($user_type_id == 4)
		{
			$sql = "SELECT ib.stock_order_price,ib.user_name, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '".(int)$user_type_id."' AND ad.user_id = '".(int)$user_id."' ) LEFT  JOIN  " . DB_PREFIX ." country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ".$cond." ";
		}
		elseif($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}
		elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}
		else
		{
			return false;
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getdomesticStock($product_code_id)
	{
	//	$sql="SELECT sm.* ,sum(qty) as total_qty,Group_concate FROM domestic_stock AS sm WHERE sm.is_delete=0 AND product_code_id='".$product_code_id."'";
	
	    $sql="SELECT sm.user_id,sm.user_type_id,sum(sm.qty)qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id,sm.stock_id,sm.box_no FROM domestic_stock as sm WHERE sm.is_delete=0 AND  sm.product_code_id ='".$product_code_id."' AND parent_id=0  AND sm.qty!=0";
	
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
	public function getdomesticStockDispatch($stock_id)
	{

	    $sql="SELECT SUM(dispatch_qty) as total FROM domestic_stock WHERE parent_id IN (" .$stock_id. ")";
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
	public function getStockstatus($user_type_id,$user_id)
	{

	    $sql="SELECT d.*,d.description as s_description,pc.description,pc.product_code,p.product_name FROM domestic_stock as d,product as p,product_code as pc WHERE d.is_delete='0'AND p.product_id=pc.product AND pc.product_code_id=d.product_code_id ORDER BY `stock_id` DESC";
		$data=$this->query($sql);
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function check_invoice($post)
	{
	    if(strrchr($post['invoice_no'],'STK'))
	        $sql="SELECT SUM(quantity) as qty FROM stock_order_test as s, template_order_test as t WHERE s.stock_order_id=t.stock_order_id AND t.product_code_id='".$post['product_code_id_add']."' AND gen_order_id='".$post['invoice_no']."'";
	    else
	        $sql="SELECT SUM(quantity) as qty FROM proforma_product_code_wise as p, proforma_invoice_product_code_wise as pp WHERE pp.proforma_id=p.proforma_id AND pp.product_code_id='".$post['product_code_id_add']."' AND pro_in_no='".$post['invoice_no']."'";
	   
	   $data=$this->query($sql);
	   
	   $data_dome=$this->query("SELECT SUM(dispatch_qty) as quantity FROM domestic_stock WHERE invoice_no='".$post['invoice_no']."' AND product_code_id='".$post['product_code_id_add']."'  AND is_delete=0");
	   
	   if($data->num_rows)
	   {
	       return $data->row['qty'];
	   }
	   else
	   {
	       return false; 
	   }
	    
	}
}
?>