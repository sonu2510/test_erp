<?php
class invoice_inventory extends dbclass{	

	public function getProductCodeName($product_code_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "product_code WHERE product_code_id = '" .(int)$product_code_id. "' AND is_delete=0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getUser($user_id,$user_type_id)
	{
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.company_address,ib.company_name,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_name,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			
			$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getMeasurementName($product_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "template_measurement` WHERE product_id = '".$product_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getColorName($color_id)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE pouch_color_id = '".$color_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getSalesQty($product_code_id,$user_type_id,$user_id,$f_date='',$t_date='',$cal_qty='')
	{	
		//echo $user_type_id.'-----'.$user_id;
		//printr($cal_qty);
		
		if($cal_qty=='')
		{
			$date_str = '';
		}
		else if($f_date != '' && $t_date!='' && $cal_qty=='2')
			$date_str = " AND si.date_added >= '".$f_date."' AND  si.date_added <= '".$t_date."'";
		else if($cal_qty=='1')
		{
			///$date_1st_s = date('Y-m-d',strtotime(date('Y-01-01')));
			//$date_1st_s = '2015-1-1';
			$date_str = " AND si.date_added < '".$f_date."' ";
		}	
		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT SUM(sip.qty) as qty  ,AVG(rate) as rate FROM " . DB_PREFIX . "sales_invoice_product as sip, sales_invoice as si  WHERE sip.product_code_id = '".$product_code_id."' AND sip.invoice_id=si.invoice_id AND si.is_delete=0 AND si.gen_status='1' ".$date_str;
			
		} else {
		
		if($user_type_id == 2){
			//echo "emp";
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					//printr($userEmployee);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
			//echo "admin";
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( si.user_id IN ('.$userEmployee.') AND si.user_type_id = 2 )';
			}
			$sql = "SELECT SUM(sip.qty) as qty ,AVG(rate) as rate FROM " . DB_PREFIX . "sales_invoice_product as sip, sales_invoice as si  WHERE sip.product_code_id = '".$product_code_id."' AND sip.invoice_id=si.invoice_id AND si.is_delete=0 AND si.gen_status='0' AND ( (si.user_id = '".(int)$set_user_id."' AND si.user_type_id = '".(int)$set_user_type_id."') $str ) ".$date_str;
		}
	//echo $sql;echo "<br>";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getTotalInvoice($user_type_id,$user_id,$filter_data=array())
	{
		//echo $user_type_id.'========'.$user_id;
		/*if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT pc.*,SUM(pip.qty) as qty FROM " . DB_PREFIX . "product_code as pc, purchase_invoice_product as pip, purchase_invoice as pi WHERE  pi.is_delete = 0 AND pip.invoice_id=pi.invoice_id AND pc.product_code_id=pip.product_code_id GROUP BY pc.product_code_id" ;
		} else {
		
		if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{*/
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			//}
			$str = '';
			$emp = '';
			if($userEmployee){
				$str = ' OR ( pi.user_id IN ('.$userEmployee.') AND pi.user_type_id = 2 )';
				$emp = 'AND ( (sm.user_id = '.(int)$set_user_id.' AND sm.user_type_id = '.(int)$set_user_type_id.') OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) )';
			}
			$sql="SELECT * FROM product_code WHERE is_delete=0";
			//$sql = "SELECT pc.*,pc.description des,SUM(pip.qty) as pur_qty FROM " . DB_PREFIX . "product_code as pc, purchase_invoice_product as pip, purchase_invoice as pi  WHERE pi.is_delete = 0 AND pip.invoice_id=pi.invoice_id AND pc.product_code_id=pip.product_code_id AND ( ( pi.user_id = '".(int)$set_user_id."' AND pi.user_type_id = '".(int)$set_user_type_id."') ) GROUP BY pc.product_code_id";
		//}
		if(!empty($filter_data)){
			if(!empty($filter_data['product_code'])){
				$sql .= " AND product_code LIKE '%".$filter_data['product_code']."%' ";
			}
			
			if(!empty($filter_data['description'])){
				$sql .= " AND description LIKE '%".$filter_data['description']."%' ";
			}
		}
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->num_rows;
			//return $data->'4';
		}else{
			//echo $emp;
			
			/*$sql1 = "SELECT pc.*,pc.description des,sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id, sm.row,sm.column_name FROM stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 $emp ";
			//$sql1="SELECT pc.*,pc.description des,sm.*,SUM(sm.qty) as s_qty,sm.row,sm.column_name FROM stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND sm.parent_id=0 $emp";
			$data1 = $this->query($sql1);
		//printr($data);
			if($data1->row['stock_id'] != ''){
				return $data1->num_rows;
			}else{
				return false;
			}*/
			return false;
		}
		
		
		//return $data->num_rows;
	}
	
	public function getInvoice($user_type_id,$user_id,$data,$filter_data=array())
	{
		//echo $user_type_id.'------'.$user_id; die;
		//sonu 7/12/2016
	
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT pc.*,SUM(pip.qty) as qty,sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id FROM " . DB_PREFIX . "product_code as pc, purchase_invoice_product as pip, purchase_invoice as pi WHERE pi.is_delete = 0 AND pip.invoice_id=pi.invoice_id AND pc.product_code_id=pip.product_code_id stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0" ;
		} else {
		
		if($user_type_id == 2){
			//echo "emp";
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					//printr($userEmployee);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
			//echo "admin";
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = $sal = $emp = '';
			if($userEmployee){
				$str = '  OR ( pi.user_id IN ('.$userEmployee.') AND pi.user_type_id = 2 )';
				$emp = '  OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 )';
				$sal = ' OR (si.user_id IN ('.$userEmployee.') AND si.user_type_id = 2)';
			}
		
	
	

		$sql="SELECT pc.product_code_id,pc.product_code,pc.description FROM product_code as pc  WHERE ( ( (pc.product_code_id IN(select sip.product_code_id from sales_invoice as si,sales_invoice_product as sip where si.is_delete=0 AND si.invoice_id=sip.invoice_id AND si.user_id='".$user_id."' and si.user_type_id='".$user_type_id."' $sal))OR (pc.product_code_id IN(select pip.product_code_id from purchase_invoice as pi,purchase_invoice_product as pip where pi.is_delete=0 AND pi.invoice_id=pip.invoice_id AND pi.user_id='".$user_id."' and pi.user_type_id='".$user_type_id."' $str))
OR (pc.product_code_id IN(select sm.product_code_id from stock_management as sm where parent_id=0  AND sm.user_id='".$user_id."' and sm.user_type_id='".$user_type_id."' $emp))) OR (pc.product_code_id IN(select i.product_code_id from inventory_opening_stock as i where is_delete ='0' AND i.user_id='".$user_id."' and i.user_type_id='".$user_type_id."' ))) ";
		
		//$sql="SELECT pc.product_code_id,pc.product_code,pc.description,pc.opening_stock_qty ,iso.opening_value,iso.opening_qty FROM product_code as pc, inventory_opening_stock as iso WHERE pc.is_delete='0'  AND iso.is_delete='0' AND user_id='".$user_id."'";
		//SELECT pc.product_code_id,pc.product_code,pc.description FROM product_code as pc WHERE ( (pc.product_code_id IN(select sip.product_code_id from sales_invoice as si,sales_invoice_product as sip where si.is_delete=0 AND si.invoice_id=sip.invoice_id AND si.user_id='24' and si.user_type_id='4' OR (si.user_id IN (45,60,61,62) AND si.user_type_id = 2)))OR(pc.product_code_id IN(select pip.product_code_id from purchase_invoice as pi,purchase_invoice_product as pip where pi.is_delete=0 AND pi.invoice_id=pip.invoice_id AND pi.user_id='24' and pi.user_type_id='4' OR ( pi.user_id IN (45,60,61,62) AND pi.user_type_id = 2 ))) OR (pc.product_code_id IN(select sm.product_code_id from stock_management as sm where parent_id=0 AND sm.user_id='24' and sm.user_type_id='4' OR ( sm.user_id IN (45,60,61,62) AND sm.user_type_id = 2 )))) OR (pc.product_code_id IN(select i.product_code_id from inventory_opening_stock as i where is_delete ='0' AND i.user_id='24' and i.user_type_id='4' ))
			//echo $sql; die;
			
		}
		
		if(!empty($filter_data)){
			if(!empty($filter_data['product_code'])){
				$sql .= " AND pc.product_code LIKE '%".$filter_data['product_code']."%' ";
			}
			
			if(!empty($filter_data['description'])){
				$sql .= " AND pc.description LIKE '%".$filter_data['description']."%' ";
			}
		}
		$sql .=' GROUP BY pc.product_code_id ';
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " .$data['sort'];	
		} else {
			$sql .= " ORDER BY pc.product_code_id";	
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
	//echo $sql;			
		$data = $this->query($sql);
		//printr($data);
		$data_pro=array();
		if($data)
		{
		
		foreach($data->rows as $row)
			{
				
				//printr($row);
				$sql_data = "SELECT i.*,pc.* FROM  inventory_opening_stock as i ,product_code as pc  WHERE i.user_id='".$user_id."' AND i.product_code_id='".$row['product_code_id']."' AND pc.product_code_id = i.product_code_id AND i.opening_qty !='0' ";
				//echo $sql_data;
				$data_sql=$this->query($sql_data);
				if($data_sql->num_rows)
						$data_pro[] = $data_sql->row;
			}
		}
		 return $data_pro;
		
		//if($data->num_rows){
//			return $data->rows;
//		}else{
			//echo $emp; "SELECT pc.*,pc.description des,sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id, sm.row,sm.column_name FROM stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 $emp ";
			/*$sql1 = "SELECT pc.*,pc.description des,sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id, sm.row,sm.column_name FROM stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 $emp GROUP BY pc.product_code_id"; //"SELECT pc.*,pc.description des,sm.*,SUM(sm.qty) as s_qty,sm.row,sm.column_name FROM stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND sm.parent_id=0 $emp ";
		echo $sql1;
		//die;
			$data1 = $this->query($sql1);
		
		//printr($data1);
			if($data1->num_rows){
			//echo 'jaya';
				return $data1->rows;
			}else{//echo 'jaya';
				return false;
			}*/
			//return false;
		//}
	}
	
	public function getStocklist($table,$product_code_id,$user_id)
	{ 
		//echo $user_id;die;
		$user_type_id = '4';
		/*if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT pi.*,pip.* FROM " . DB_PREFIX . " ".$table."_product as pip, ".$table." as pi   WHERE pi.is_delete = 0 AND pip.invoice_id=pi.invoice_id AND pip.product_code_id='".$product_code_id."' " ;
		} else {
		
		if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];*/
			//}else{
		$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
		$set_user_id = $user_id;
		$set_user_type_id = $user_type_id;
			//}
			$str = '';
			if($userEmployee){
				$str = ' OR ( pi.user_id IN ('.$userEmployee.') AND pi.user_type_id = 2 )';
			}
			$sql = "SELECT pi.*,pip.* FROM " . DB_PREFIX . " ".$table."_product as pip, ".$table." as pi WHERE pi.is_delete = 0 AND pip.invoice_id=pi.invoice_id AND pip.product_code_id='".$product_code_id."' AND (( pi.user_id = '".(int)$set_user_id."' AND pi.user_type_id = '".(int)$set_user_type_id."' ) $str)";
	//	}
		$data = $this->query($sql);
		//echo $sql;
		if($data->num_rows){
			return $data->rows;
		}
		else
		{
			return false;
		}
	} 
	
	public function getUserEmployeeIds($user_type_id,$user_id)
	{
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	} 
	public function getTotalBranch($filter_array=array(),$user_type_id,$user_id){
		
		//echo $user_type_id;
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id) WHERE ib.is_delete = '0'" ;
		} else {
		
		if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				//printr($userEmployee);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			//echo $set_user_type_id;
			//$str = '';
			/*if($userEmployee){
				$str = ' OR ( pi.user_id IN ('.$userEmployee.') AND pi.user_type_id = 2 )';
			}*/
			//echo "hiiiii";
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id) WHERE ib.is_delete = '0' AND ib.international_branch_id = '".(int)$set_user_id."'";
		}
		//echo $sql;
		//$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id) WHERE ib.is_delete = '0'";
		
		if(!empty($filter_array)){						
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}				
			/*if($filter_array['status'] != ''){
				$sql .= " AND ib.status = '".$filter_array['status']."' ";
			}*/			
			if(!empty($filter_array['name'])){
				$sql .= " AND CONCAT(ib.first_name,' ',ib.last_name) LIKE '%".$this->escape($filter_array['name'])."%'";
			}									
		}
		//echo $sql;die;		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getBranchs($data = array(),$filter_array=array(),$user_type_id,$user_id){
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT *,CONCAT(ib.first_name,' ',ib.last_name) as name,am.email FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id)  WHERE ib.is_delete = '0'" ;
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
		
			$sql = "SELECT *,CONCAT(ib.first_name,' ',ib.last_name) as name,am.email FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id)  WHERE ib.is_delete = '0'  AND ib.international_branch_id = '".(int)$set_user_id."'";
		}
		
		if(!empty($filter_array)){			
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}			
			if(!empty($filter_array['name'])){
				$sql .= " AND CONCAT(ib.first_name,' ',ib.last_name) LIKE '%".$this->escape($filter_array['name'])."%'";
			}							
		}		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY ib.international_branch_id";	
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
	
	public function getRackQty($product_code_id,$user_type_id,$user_id,$f_date,$t_date)
	{	
		$date_str = '';
		if($f_date != '')
			$date_str = "AND sm.date_added >= '".$f_date."' AND  sm.date_added <= '".$t_date."'";
			
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id FROM stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '".$product_code_id."'".$date_str ;
			//$sql = "SELECT gm.name,GROUP_CONCAT(stock_id) as stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col,sm.rack_label FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '".$product_code_id."'".$date_str." AND gm.goods_master_id = sm.goods_id  $str_rack AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0'  GROUP BY sm.row,sm.column_name ";//$str_getrackno
		} else {
		
		if($user_type_id == 2){
			//echo "emp";
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					//printr($userEmployee);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
			//echo "admin";
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			
			if($userEmployee){
				$str = 'OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) ';
			}
			//SELECT SUM(sip.qty) as qty FROM " . DB_PREFIX . "sales_invoice_product as sip, sales_invoice as si  WHERE sip.product_code_id = '".$product_code_id."' AND sip.invoice_id=si.invoice_id AND si.is_delete=0 AND ( (si.user_id = '".(int)$set_user_id."' AND si.user_type_id = '".(int)$set_user_type_id."') $str )
			$sql = "SELECT sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id, sm.row,sm.column_name FROM stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '".$product_code_id."' AND ((sm.user_id = '".(int)$set_user_id."' AND sm.user_type_id = '".(int)$set_user_type_id."') $str )".$date_str;
			//$sql = "SELECT gm.name,GROUP_CONCAT(stock_id) as stock_id,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col,sm.rack_label FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '".$product_code_id."' AND ((sm.user_id = '".(int)$set_user_id."' AND sm.user_type_id = '".(int)$set_user_type_id."') $str )".$date_str." AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0' $str_rack  GROUP BY sm.row,sm.column_name ";

			//echo $sql;
		}
		
		//echo "<br>";
		//echo "===";
		//echo $sql;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getRackQtyNew($product_code_id,$user_type_id,$user_id,$f_date,$t_date)
	{	
	  		
		$date_str = '';
		if($f_date != '')
			$date_str = "AND sm.date_added >= '".$f_date."' AND  sm.date_added <= '".$t_date."'";
			
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT gm.goods_master_id,gm.name,GROUP_CONCAT(stock_id) as stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col,sm.rack_label FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '".$product_code_id."'".$date_str." AND gm.goods_master_id = sm.goods_id  AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0'  GROUP BY sm.row,sm.column_name ";//$str_getrackno
		} else {
		
		if($user_type_id == 2){
			//echo "emp";
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					//printr($userEmployee);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
			//echo "admin";
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			
			if($userEmployee){
				$str = 'OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) ';
			}
			$sql = "SELECT gm.goods_master_id,gm.name,GROUP_CONCAT(stock_id) as stock_id,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col,sm.rack_label FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '".$product_code_id."' AND ((sm.user_id = '".(int)$set_user_id."' AND sm.user_type_id = '".(int)$set_user_type_id."') $str )".$date_str." AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0' GROUP BY sm.row,sm.column_name ";
			//echo $sql;
		}
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function gettotaldispatch($stock_id,$user_type_id,$user_id)
	{
		//printr($stock_id);
		//.implode(",",$data).
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT sum(dispatch_qty) as total FROM stock_management WHERE is_delete=0" ;
		} else {
		
		if($user_type_id == 2){
			//echo "emp";
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					//printr($userEmployee);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
			//echo "admin";
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			
			if($userEmployee){
				$str = 'OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 ) ';
			}
		//$sql="SELECT SUM(dispatch_qty) as total FROM stock_management WHERE parent_id IN (" .implode(",",$stock_id). ")";
		$sql="SELECT SUM(dispatch_qty) as total FROM stock_management WHERE parent_id IN (" .$stock_id. ") AND ((user_id = '".$user_id."' AND user_type_id = '".$user_type_id."') $str )";
		//echo "<br>";
		//echo $sql;
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

	
	public function getRackWiseQty($product_code_id,$user_id)
	{	$user_type_id='4';
		/*if($user_type_id == 1 && $user_id == 1){
			$sql="SELECT sm.*,p.product_name FROM stock_management as sm,product as p WHERE sm.product=p.product_id AND sm.stock_id IN (".$stock_id.") ";
		}
		else
		{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{*/
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			//}
			$str = '';
			if($userEmployee){
				$str = 'AND ( (sm.user_id='.(int)$set_user_id.' AND sm.user_type_id='.(int)$set_user_type_id.') OR (sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ))';
			}
			//echo $str;
			$sql="SELECT sm.*,p.product_name, pc.product_code, gm.name FROM stock_management as sm,product as p,product_code as pc, goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND sm.product=p.product_id AND sm.product_code_id = '".$product_code_id."' AND parent_id=0 AND sm.goods_id = gm.goods_master_id $str ";
		//}
		
		$sql.=' GROUP BY sm.stock_id';
		
		//echo $sql;
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;		
	}
	public function gettotaldispatchChild($stock_id)
	{
		$sql="SELECT sm.* FROM stock_management AS sm WHERE sm.parent_id = " .$stock_id. " ORDER BY date_added ASC";
		//echo $sql;
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
	//sonu chage query 26/12/2016
	public function getPursQty($product_code_id,$user_type_id,$user_id,$f_date='',$t_date='',$cal_qty='')
	{	
		//$product_code_id=205;
		if($cal_qty=='')
		{
			$date_str = '';
		}
		else if($f_date != '' && $t_date !='' && $cal_qty=='2')
			$date_str = " AND i.date_added >= '".$f_date."' AND  i.date_added <= '".$t_date."'";
		else if($cal_qty=='1')
		{
			//$date_1st_p = date('Y-m-d',strtotime(date('Y-01-01')));
			//$date_1st_p = '2015-1-1';pi.date_added >= '".$date_1st_p."' AND
			$date_str = " AND i.date_added < '".$f_date."' ";
		
		}
		 //echo $date_str;
			if($user_type_id == 1 && $user_id == 1){
			$sql = $sql = "SELECT SUM(ic.qty) as pur_qty, AVG(ic.rate) as rate from invoice as i,invoice_color as ic,invoice_product as ip WHERE ip.invoice_id =i.invoice_id AND ip.invoice_product_id = ic.invoice_product_id AND ip.product_id = '".$row['product']."' AND ip.valve='".$row['valve']."' AND ip.zipper ='".encode($row['zipper'])."' AND ip.spout ='".encode($row['spout'])."' AND ip.accessorie ='".encode($row['accessorie'])."'AND ip.make_pouch='".$row['make_pouch']."'  AND ic.size='".$row['volume']."' AND ic.measurement='".$row['measurement']."' AND  is_delete=0 $date_str" ;
		} else {
		
		if($user_type_id == 2){
			//echo "emp";
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
			//echo "admin";
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			///rintr($userEmployee);
			$str = '';
			if($userEmployee){
				$str = ' OR ( i.purchase_user_id IN ('.$userEmployee.') AND i.purchase_user_type_id = 2 )';
			}
		//	$sql ="SELECT SUM(pip.qty) as pur_qty ,AVG(rate) as rate FROM " . DB_PREFIX . "purchase_invoice_product as pip, purchase_invoice as pi  WHERE pi.is_delete = 0 AND pip.invoice_id=pi.invoice_id AND pip.product_code_id = '".$product_code_id."' AND ( (pi.user_id = '".(int)$set_user_id."' AND pi.user_type_id = '".(int)$set_user_type_id."')  $str)".$date_str;
//		//}
	
				$sql_product = "SELECT * FROM product_code WHERE product_code_id='".$product_code_id."'";
				$data = $this->query($sql_product);
				//printr($data);
				//die;
				if($data->num_rows)
				{
					//foreach($data->rows as $row)
					//{
						$sql = "SELECT SUM(ic.qty) as pur_qty, AVG(ic.rate) as rate from invoice as i,invoice_color as ic,invoice_product as ip WHERE ip.invoice_id =i.invoice_id AND ip.invoice_product_id = ic.invoice_product_id AND ip.product_id = '".$data->row['product']."' AND ip.valve='".$data->row['valve']."' AND ip.zipper ='".encode($data->row['zipper'])."' AND ip.spout ='".encode($data->row['spout'])."' AND ip.accessorie ='".encode($data->row['accessorie'])."'AND ip.make_pouch='".$data->row['make_pouch']."'  AND ic.size='".$data->row['volume']."' AND ic.measurement='".$data->row['measurement']."' AND ( (i.purchase_user_id  = '".(int)$set_user_id."' AND i.purchase_user_type_id  = '".(int)$set_user_type_id."') $str ) $date_str ";
					
						$data_p = $this->query($sql);
						
						///$data_pro[] = $data_p->row;
						//echo $sql;die;
					//}
				}
				
	}
	        
			//printr($data_p);
			if($data_p->num_rows)
				{
					return $data_p->row;
				}
				else
				{
					return false;
				}
	}
	
	// mansi (for opening stock qty)
	//public function openingStockQty($product_code_id,$open_qty )
//	{
//		$sql = "UPDATE product_code SET opening_stock_qty='".$open_qty."', date_modify = NOW() WHERE product_code_id = '" .(int)$product_code_id. "'";
//		$data = $this->query($sql);
//		//printr($data); die;
//		
//	}
	//sonu 8/12/2016
//	public function getupdatestockqtybycontry($product_code_id,$open_qty_by_country,$branch_id)
//	{   
//	    
//		if($branch_id == 24 )
//		{
//			$openingstock ='opening_stock_qty_australia';	
//			
//		}
//		elseif($branch_id == 8 )
//		{
//			$openingstock ='opening_stock_qty_canada';
//			
//		}
//		elseif($branch_id == 10 )
//		{
//			$openingstock ='opening_stock_qty_maxico';
//			
//		}
//		
//		elseif($branch_id== 7 ){
//			$openingstock ='opening_stock_qty_singapore';
//			
//		}
//		else
//		{
//			$openingstock ='opening_stock_qty';
//			//$sql = "UPDATE product_code SET opening_stock_qty ='".$open_qty_by_country."', date_modify = NOW() WHERE product_code_id = '" .(int)$product_code_id. "'";
//		
//		}
//		$sql = "UPDATE product_code SET ".$openingstock." ='".$open_qty_by_country."', date_modify = NOW() WHERE product_code_id = '" .(int)$product_code_id. "'";
//	    //echo $sql;
//		$data = $this->query($sql);
//		//printr($data); die;
//		
//	}
	public function addInventory($data, $branch_id)
	{
		//$sql ="SELECT  FROM " . DB_PREFIX . " ";
		//printr($data);die;
		if($_SESSION['ADMIN_LOGIN_SWISS']=='49' && $_SESSION['LOGIN_USER_TYPE']=='2')
		{
			$emp_type_id = '2';
			$emp_id = '49';
		}
		else
		{
			$emp_type_id = $_SESSION['LOGIN_USER_TYPE'];
			$emp_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		}
		$n=0;
		$user_type_id = '4';
		$user_id = $branch_id;	
		$html = "";
		$user_company = $this->getUser($user_id,$user_type_id);
		$html .= "<div class='table-responsive'>
		                <table class='table b-t text-small table-hover'>
        		          <thead>
						  <tr><td colspan='10' style='text-align:center'><h3><b>Invoice Inventory</b></h3></td></tr>
						    <tr>
								<td colspan='5' style='text-align:right'><h5><b>".$user_company['company_name']."</b></h5></td>
								<td colspan='5' style='text-align:left'><h5>Date From : ".dateFormat('4',$data['f_date'])." To: ".dateFormat('4',$data['t_date'])."</h5></td>
								
							</tr>
                		    <tr>  
								  <th rowspan='2'>Sr.No</th>
								  <th rowspan='2'>Product Code</th>
								  <th rowspan='2'>Description</th>
								  <th colspan='2'id='open_stock' >Opening Stock Qty </th>								
								  <th colspan='2'> Stock In </th>
								  <th colspan='2'>Stock Out </th>
								  <th colspan='1'>Closing </th>
								 
								";
								  if((($_SESSION['ADMIN_LOGIN_SWISS']=='59' || $_SESSION['ADMIN_LOGIN_SWISS']=='62' || $_SESSION['ADMIN_LOGIN_SWISS']=='50' || $_SESSION['ADMIN_LOGIN_SWISS']=='49') && ($_SESSION['LOGIN_USER_TYPE']=='2')) || 
									 			($_SESSION['ADMIN_LOGIN_SWISS']=='10' && $_SESSION['LOGIN_USER_TYPE']=='4')) {
								   				$html .= "<th colspan='2' style='text-align:center'>Physical Stock</th>";
											$n=1;
								  }
                $html .= "  </tr>
                             <tr> 
							 	 
								  <th>Qty</th>
								  <th>value</th>
								  <th> Qty</th>
								  <th>value</th>
								  <th> Qty</th>
								  <th>value</th>
								  <th> Qty</th>
								
								  ";
								  if($n=='1' && $emp_type_id == '2' && $emp_id = '49')
								 	$html .= "<th>Auditor</th><th>Employee</th>";
        		    $html .= " </tr>
                		  </thead>
	                 	  <tbody>";
                  			 
							  
							  $invoices = $this->getInvoice('4',$branch_id,'','');
							  
							 
								$i=1;
							
							if($invoices !='')
							{
                      			foreach($invoices as $invoice){
									//printr($invoice); 
									
									$latest_sales_qty = $this->getSalesQty($invoice['product_code_id'],$user_type_id,$user_id,$data['f_date'],'',1);
									//printr($latest_sales_qty );die;
									if($n=='1') 
									{
										$phy_stock = $this->getPhyStock($emp_type_id,$emp_id,$invoice['product_code_id'],$data['f_date'],$data['t_date']);
										
									}
										
										
									 
									$sales_qty = $this->getSalesQty($invoice['product_code_id'],$user_type_id,$user_id,$data['f_date'],$data['t_date'],2);
									
									$latest_purchase_qty = $this->getPursQty($invoice['product_code_id'],$user_type_id,$user_id,$data['f_date'],'',1);
									//printr($latest_purchase_qty);
									
									$purchase_qty = $this->getPursQty($invoice['product_code_id'],$user_type_id,$user_id,$data['f_date'],$data['t_date'],2);
									
									 
									$rack_qty = $this->getRackQty($invoice['product_code_id'],$user_type_id,$user_id,$data['f_date'],$data['t_date']);
									//printr($rack_qty);
									
//												}
												
									$open_stock_qty_new =($invoice['opening_qty'] +  $latest_purchase_qty['pur_qty']);
									$open_stock_qty_new = $open_stock_qty_new -  $latest_sales_qty['qty'];
									//echo "open_stock_qty_new =".$invoice['opening_stock_qty']."+".$latest_purchase_qty['pur_qty']."-".$latest_sales_qty['qty'];
								//printr($open_stock_qty_new);
									
									$dis_qty='';
									if(!empty($rack_qty) && $rack_qty['grouped_s_id'] != '')
									{	
										//printr($rack_qty['grouped_s_id']);
										$dispatch_qty=$this->gettotaldispatch($rack_qty['grouped_s_id'],$user_type_id,$user_id);
										//printr($dispatch_qty);
										$dis_qty =	isset($dispatch_qty['total']) ? $dispatch_qty['total']: '' ; 
									}
									$rac_qty = isset($rack_qty['tot_qty']) ? $rack_qty['tot_qty'] : '';
									$pro_c_id = isset($invoice['product_code']) ? $invoice['product_code'] : '';
									$desc = isset($invoice['description']) ? $invoice['description'] : '';
									$p_qty = isset($invoice['pur_qty']) ? $invoice['pur_qty'] : '';

									 $var_open =isset($invoice['opening_qty'])?$invoice['opening_qty']:'';
									  
									  $var = $open_stock_qty_new;
									  //printr($var);
									//die;
									$total_purchase_qty=$purchase_qty['pur_qty']+$invoice['opening_qty'];
									//$Qty_new = $total_purchase_qty - $sales_qty['qty'];
									
									$new_qty = $rac_qty - $dis_qty;
									
									$balance_qty = $var + $purchase_qty['pur_qty'] - $sales_qty['qty'];
									//echo '$Qty_new'.$Qty_new;
                       			$html.= "<tr id='product_code_row' valign='top' >                        
                          					<td>".$i."</td>
                          					<td id='product_code_td'> ".$pro_c_id."	</td>";
										
                                $html.= "<td id='desc_td'>".$desc."</td>";
                                      
                                $html.= "<td> ". $var."</td>";
								$html.= "<td> ".$invoice['opening_value']."</td>";                                      
								$html.= " <td> ".$purchase_qty['pur_qty']."</td>";
								$html.= " <td> ".$purchase_qty['rate']."</td>";
									  
                          		$html.= " <td>".$sales_qty['qty']."</td>";
								$html.= " <td>".$sales_qty['rate']."</td>
									  
										  <td>".$balance_qty."</td>
										  
										";
										  
										  
										  if($n=='1' && $emp_type_id == '2' && $emp_id = '49' && !empty($phy_stock))
										  {
										  		$html.= "<td>".$phy_stock['stock_qty']."<br>(".dateFormat('4',$phy_stock['date_added']).")</td>";
												if($phy_stock['kath_add_date']!='')
													$html.= "<td>".$phy_stock['kath_phy_stock']."<br>(".dateFormat('4',$phy_stock['kath_add_date']).")</td>";
										  }
										  else if($n=='1' && !empty($phy_stock))
										  {
												$html.= "<td>".$phy_stock['stock_qty']."<br>(".dateFormat('4',$phy_stock['date_added']).")</td>";
										  }
                                      
							$html.= "</tr>";
								$i++;
								}
                    		} 
							else{ $html .="<tr  valign='top'><td colspan='5'>No record found !</td></tr>"; } 
						
							
                  		  $html.= " </tbody>
                		</table>
              	   </div>";
				  // printr($html);
		return $html;
		
	}
	public function addPhyStock($product_code_id,$phy_stock_qty,$yes)
	{
		$y_n_status=1;
		if($yes=='0')
		{
			$phy_stock_qty = 0;
			$y_n_status = 0;
		}
		
		$sql = "INSERT INTO `" . DB_PREFIX . "physical_stock_activity` SET product_code_id = '".$product_code_id."', stock_qty = '".$phy_stock_qty."', y_n_status = '".$y_n_status."', user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW()";
		$this->query($sql);
	
	}
	public function getPhyStockqty($product_code_id,$n=0)
	{
		if($n=='1')
		{
			$sql="SELECT * FROM physical_stock_activity WHERE product_code_id = '".$product_code_id."' AND user_id = '10' AND  user_type_id='4' ORDER BY physical_stock_activity_id DESC";
		}
		else
		{
			$sql="SELECT * FROM physical_stock_activity WHERE product_code_id = '".$product_code_id."' AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND  user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' ORDER BY physical_stock_activity_id DESC";
		}
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getPopularProduct($user_type_id,$user_id)
	{
		if($user_type_id == 1 && $user_id == 1){
			//$sql = "SELECT pc.*,SUM(sip.qty) as qty,sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id FROM " . DB_PREFIX . "product_code as pc, sales_invoice_product as sip, sales_invoice as si WHERE si.is_delete = 0 AND sip.invoice_id=si.invoice_id AND pc.product_code_id=sip.product_code_id stock_management as sm,product_code as pc WHERE pc.product_code_id = sm.product_code_id AND parent_id=0" ;
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
			$str = $sal = $emp = '';
			if($userEmployee){
				$str = '  OR ( pi.user_id IN ('.$userEmployee.') AND pi.user_type_id = 2 )';
				$emp = '  OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 )';
				$sal = ' OR (si.user_id IN ('.$userEmployee.') AND si.user_type_id = 2)';
			}
			$sql="SELECT sum(sip.qty) as t_qty,sip.product_code_id,pc.product_code_id,pc.product_code,pc.description,pc.opening_stock_qty,si.date_added FROM sales_invoice_product as sip, sales_invoice as si,product_code as pc WHERE sip.invoice_id=si.invoice_id AND si.is_delete=0 AND si.gen_status='0'AND (si.user_id='".$user_id."' and si.user_type_id='".$user_type_id."' $sal) AND sip.product_code_id = pc.product_code_id
			 ";
		}
		//echo $sql;
		/*if(!empty($filter_data)){
			if(!empty($filter_data['product_code'])){
				$sql .= " AND product_code LIKE '%".$filter_data['product_code']."%' ";
			}
			
			if(!empty($filter_data['description'])){
				$sql .= " AND description LIKE '%".$filter_data['description']."%' ";
			}
		}
		
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " .$data['sort'];	
		} else {
			$sql .= " ORDER BY product_code_id";	
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
		}*/
		//SELECT sum(sip.qty) as t_qty,sip.product_code_id,pc.product_code_id,pc.product_code,pc.description,pc.opening_stock_qty FROM sales_invoice_product as sip, sales_invoice as si,product_code as pc WHERE sip.invoice_id=si.invoice_id AND si.is_delete=0 AND si.gen_status='0'AND (si.user_id='7' and si.user_type_id='4' OR (si.user_id IN (21,22,42,57,59,67) AND si.user_type_id = 2)) AND sip.product_code_id = pc.product_code_id Group By sip.product_code_id
		
		$sql .=' GROUP BY sip.product_code_id,si.date_added';
		//echo $sql;
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->rows;
		}else{
	
			return false;
		}
	}
	public function getPhyStock($user_type_id,$user_id,$product_code_id,$f_date,$t_date)
	{
		$sql="SELECT * FROM physical_stock_activity WHERE product_code_id = '".$product_code_id."' AND user_id = '".$user_id."' AND  user_type_id='".$user_type_id."' AND date_added >= '".$f_date."' AND  date_added <= '".$t_date."' ORDER BY physical_stock_activity_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			
			if($user_type_id=='2' && $user_id=='49')
			{
				$sql1="SELECT * FROM physical_stock_activity WHERE product_code_id = '".$product_code_id."' AND user_id = '10' AND  user_type_id='4' AND date_added >= '".$f_date."' AND  date_added <= '".$t_date."' ORDER BY physical_stock_activity_id DESC";
				$data1 = $this->query($sql1);
				if($data1->num_rows)
				{
					$data->row['kath_phy_stock'] = $data1->row['stock_qty'];
					$data->row['kath_add_date'] = $data1->row['date_added'];
										
				}
				else
				{
					$data->row['kath_phy_stock'] = '';
					$data->row['kath_add_date'] = '';
				}
				
			}
			return $data->row;
		}else{
			return false;
		}
	}
	//sonu 26/12/2016 add function
	
	//public function getupdatestockqtybyrate($product_code_id,$open_stock_rate,$branch_id)
//	{
//		
//		if($branch_id == 24 )
//		{
//			$opening_rate='rate_opening_stock_qty_aus';	
//		}
//		elseif($branch_id == 8 )
//		{
//			$opening_rate='rate_opening_stock_qty_cana';
//		}
//		elseif($branch_id == 10 )
//		{
//			$opening_rate='rate_opening_stock_qty_maxi';
//		}		
//		elseif($branch_id== 7 )
//		{
//			$opening_rate='rate_opening_stock_qty_sing';			
//		}
//		else
//		{
//			$opening_rate='rate_opening_stock_qty';
//			
//		}
//		$sql = "UPDATE product_code SET ".$opening_rate." ='".$open_stock_rate."', date_modify = NOW() WHERE product_code_id = '" .(int)$product_code_id. "'";
//		
//	    //echo $sql;
//		$data = $this->query($sql);
//		//printr($data); die;
//		
//		
//	}
    public function getLabel($col_row,$goods_master_id)
    {
        $explode = explode('@',$col_row);
        $sql="SELECT * FROM stock_management WHERE goods_id='".$goods_master_id."' AND row='".$explode[0]."' AND column_name = '".$explode[1]."' AND rack_label!=''";
        $data = $this->query($sql);
        if($data->num_rows){
            return $data->row['rack_label'];
        }else{
			return false;
		}
    }
    public function Report($branch_id)
    {
        //printr($branch_id);die;
        $html = '';
        $user_type_id = '4';
	    $user_id = $branch_id;
	    $invoices = $this->getInvoice($user_type_id,$user_id,'','');
	    if($invoices !='')
	    {
	       $html.='<div class="table-responsive">
		                <table class="table b-t text-small table-hover">
        		          <thead>
                		    <tr>
                		        <th rowspan="2">Product Code</th>
        		              <th rowspan="2">Description</th>
        		              <th rowspan="2" >Rack Status</th>
        		            </tr>
                		  </thead>
	                 	 <tbody>';
	       
                           foreach($invoices as $invoice)
                           {
                               $rack_qty_new = $this->getRackQtyNew($invoice['product_code_id'],$user_type_id,$user_id,'','');
                               $html .='<tr id="product_code_row">
                                          <td id="product_code_td">'.$invoice['product_code'].'</td>
                                          <td id="desc_td">'.$invoice['description'].'</td>
                                          <td>
                                                <table border="1">
                									<th>Rack Name</th>
                									<th>Rack Position</th>
                									<th>Qty</th>';
                                                        if(!empty($rack_qty_new))
                										{
                											foreach($rack_qty_new as $rack)
                											{
                												$d=1;
                												$rc = $rack['row'].'@'.$rack['column_name'];
                												for($i=1;$i<=$rack['g_row'];$i++)
                												{
                													for($r=1;$r<=$rack['g_col'];$r++) 
                													{
                														$n = $i.'@'.$r;
                														if($rc==$n)
                														{
                															$col_row = $rc;
                															$k=$d;
                													    	$r_no[]=$k;	
                														}
                														$d++;
                													}
                												}
                												$dispatch_qty=$this->gettotaldispatch($rack['stock_id'],$user_type_id,$user_id);
                												$lable = $this->getLabel($col_row,$rack['goods_master_id']);
                												$rm_qty=$rack['store_qty']-$dispatch_qty['total'];
                												$l =$k;
                												if($lable!='')
                												    $l = $lable;
                												$html.= '<tr><td>'.$rack['name'].'</td>
                																<td align="center">'.$l.'</td>
                																<td>'.$rm_qty.'</td>
                													</tr>';
                												$a[]=$k;
                												$r_qty[] =$rm_qty.'='.$k;
                											}
                										}
                            		 $html .= '</table>
                                            </td>';
                                $html.='</tr>';
                               
                               
                           }
                $html.='</tbody>
                </table>
              	  
            </div>';
	    }
	   // printr($html);die;
	    return $html;
    }
}
?>