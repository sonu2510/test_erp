<?php
class product_minimumqty extends dbclass{
		
		public function addQty($post){
			
			if($_SESSION['LOGIN_USER_TYPE']=='4')
			{
				$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
			}
			else
			{
				$user_admin_id=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
				$user_id=$user_admin_id['user_id'];
			}
			
			foreach($post['qty_master'] as $qty){ //,rack_qty = '".$qty['rack_qty']."'
				$sql = "INSERT INTO product_minimumqty SET product_code_id='".$qty['product_keyword']."',min_qty = '".$qty['min_qty']."',user_id = '".$user_id."',user_type_id = '4',date_added = NOW(),is_delete=0 ";
				$data = $this->query($sql);
			
			}
		}
		
		public function updateQty($post) {
			
			 foreach($post['qty_master'] as $UpdateQty)
			 {
			
			
				 if(isset($UpdateQty['min_qty_id']) && $UpdateQty['min_qty_id']!='')
				 {
				 
					$sql = "UPDATE product_minimumqty SET product_code_id='".$UpdateQty['product_keyword']."',min_qty = '".$UpdateQty['min_qty']."', date_modify = NOW() WHERE min_qty_id = '" .$UpdateQty['min_qty_id']. "' ";
					$this->query($sql);
					
				 }
				 else
				 {
					if($_SESSION['LOGIN_USER_TYPE']=='4')
					{
						$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
					}
					else
					{
						$user_admin_id=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
						$user_id=$user_admin_id['user_id'];
					}
					
				
					$sql = "INSERT INTO product_minimumqty SET product_code_id='".$UpdateQty['product_keyword']."',min_qty = '".$UpdateQty['min_qty']."',user_id = '".$user_id."',user_type_id = '4',date_added = NOW(),date_modify = NOW(),is_delete=0 ";
					
					$this->query($sql);
					
				 }
			
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
		public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name,u.user_id, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, e.user_id,co.country_id, e.multi_quotation_price,co.country_name, e.first_name, e.last_name, ib.email_confirm,e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,ib.discount,ib.multi_quotation_price,co.country_id, co.country_name, ib.first_name,ib.email_confirm ,ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			return false;
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getgoods_id($user_id)
	{
		$user_type_id='4';
		$userEmployee=$this->getUserEmployeeIds($user_type_id,$user_id);
		
		
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			
			$str = '';
			if($userEmployee){
				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			}
		
		$sql = "SELECT * FROM goods_master WHERE  is_delete = '0' AND ((user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' ) $str ) ORDER BY goods_master_id ASC";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
			
			
		
	}
   public function getQtyPrice($user_id)
   {	
		$sql = "SELECT m.*,p.description,p.product_code FROM product_minimumqty as m,product_code as p WHERE m.is_delete = '0' AND m.product_code_id=p.product_code_id AND m.user_id='".$user_id."' AND m.user_type_id = '4' ORDER BY m.min_qty ASC";
		
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}

	}
	
	public function deleteQty($id){
	
		$this->query("DELETE FROM " . DB_PREFIX . "product_minimumqty WHERE min_qty_id = '".(int)$id."'");	
	}
	
	public function delQty($min_qty_id){
	
		$this->query("DELETE FROM " . DB_PREFIX . "product_minimumqty WHERE min_qty_id = '".(int)$min_qty_id."' AND is_delete=0");
	}
	
	public function getProductCd($product_code_id)
	{
		$result=$this->query("SELECT description FROM " . DB_PREFIX ."product_code WHERE product_code_id='".$product_code_id."'");
		return $result->row;
	}
	
	public function getproductcodes()
	{
		$sql = "SELECT * FROM product_code WHERE  is_delete = '0' AND ( product_code NOT LIKE 'CUST%' AND product_code NOT LIKE 'LBL%' AND product_code NOT LIKE 'CPBB' ) ORDER BY product_code<>( product_code LIKE 'CUST%' OR product_code LIKE 'LBL%' OR product_code LIKE 'CPBB' ) ,( product_code NOT LIKE 'CUST%' AND product_code NOT LIKE 'LBL%' AND product_code NOT LIKE 'CPBB' ),product_code_id ASC";

		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		
		
	}
	public function getTotalBranch($filter_array=array(),$user_type_id,$user_id){
		
		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id) WHERE ib.is_delete = '0'" ;
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
			
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id) WHERE ib.is_delete = '0' AND ib.international_branch_id = '".(int)$set_user_id."'";
		}
		
		if(!empty($filter_array)){						
			if(!empty($filter_array['email'])){
				$sql .= " AND am.email LIKE '%".$filter_array['email']."%'";
			}				
					
			if(!empty($filter_array['name'])){
				$sql .= " AND CONCAT(ib.first_name,' ',ib.last_name) LIKE '%".$this->escape($filter_array['name'])."%'";
			}									
		}
			
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
	
	public function getUserEmployeeIdsStock($user_type_id,$user_id)
	{
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids,GROUP_CONCAT(2) as type_ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function get_disc($product_code_id,$branch_id='')
	{
		
		if($_SESSION['LOGIN_USER_TYPE'] == '1')
		{	
			$user_type_id = '4';	
			$user_id = $branch_id;	
		}
		else
		{
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];	
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];	
		}
		
	
		$return = $this->getRemQty($product_code_id,$user_type_id,$user_id);
		
			
		return $return;
	}
	
	public function getRemQty($product_code_id,$user_type_id,$user_id)
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
				$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
			}
			
			$admin_user_id=' AND pto.admin_user_id = '.$set_user_id;
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND (user_id='".(int)$set_user_id."' AND user_type_id='".(int)$set_user_type_id."' $str )";
			$data = $this->query($sql);
			$f_val=array();
			if($data->num_rows)
			{
				
				foreach($data->rows as $val)
				{
					
					$sql2 = "SELECT sm.user_id,sm.user_type_id,sum(sm.qty)qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id,p.product_name ,gm.name,sm.row,sm.column_name,pc.product_code,sm.stock_id,gm.row as g_row,gm.column_name as g_col FROM stock_management as sm,product as p,product_code as pc,goods_master AS gm WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = gm.goods_master_id AND  sm.goods_id='".$val['goods_master_id']."' AND pc.product_code_id = sm.product_code_id  AND sm.product_code_id='".(int)$product_code_id."' AND parent_id=0  AND gm.is_delete = '0'AND sm.qty!=0 AND sm.row!=0 AND sm.column_name!=0 GROUP BY sm.row,sm.column_name ";
					
					$data2 = $this->query($sql2);					
					foreach($data2->rows as $data_arr)
					{
						//printr($data_arr);
						$f_val[]=$data_arr;
					
					
					}
					
				}    
				
			}
			return $f_val;
		
	}
	public function gettotaldispatchSales($stock_id)
	{
		
		$sql = "SELECT sum(dispatch_qty) as total FROM stock_management WHERE is_delete=0 AND parent_id IN (" .$stock_id. ")" ;
		
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
	 public function sendminiqtyMail()
	 {
		
		 $subject = 'Stock Inventory Alert';
		 $send=0;
		 $sql = "SELECT *,CONCAT(ib.first_name,' ',ib.last_name) as name,am.email FROM `" . DB_PREFIX . "international_branch` ib LEFT JOIN " . DB_PREFIX . "product_minimumqty pmin ON (ib.international_branch_id = pmin.user_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON am.user_name=ib.user_name AND am.user_id = ib.international_branch_id AND am.user_type_id = '4' LEFT JOIN " . DB_PREFIX . "address addr ON (ib.address_id = addr.address_id)  WHERE ib.is_delete = '0' AND pmin.user_type_id='4' GROUP BY pmin.user_id" ;
		$data=$this->query($sql);
		//printr($data);die;
		 
		 if($data->num_rows)
	     {
			 foreach($data->rows as $r)
			 {   $html='';
				 $email_temp=array();
				 
				 $total_qty = $this->getQtyPrice($r['international_branch_id']);
				 if($total_qty)
				 { $html.='We inform you that you have enough stock for the following products:<br>';
					 $i=1;
					 foreach($total_qty as $qty)
					 { 
						$rack_qty = $this->get_disc($qty['product_code_id'],$r['international_branch_id']);
						
						$dis_qty_la = $r_qty = 0;
						if($qty['product_code']!=''){
							
							foreach($rack_qty as $dis_qty)
							{
								$dispatch_qty=$this->gettotaldispatchSales($dis_qty['grouped_s_id']);
								$dis_qty_la +=$dispatch_qty['total'];
								$r_qty +=$dis_qty['qty'];
							}
						}
						$remaining_qty = $r_qty-$dis_qty_la;
						
						if($remaining_qty <	 $qty['min_qty'])
						{
							$send=1;
							$html.=$i.' : '.$qty['product_code'].'<br>';
							$addedByinfo=$this->getUser($r['international_branch_id'],4);
							$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);
							$email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);
							
							if($addedByinfo['country_id']=='111')
							{
								$pratik_sir=$this->getUser(19,2);
								$email_temp[]=array('html'=>$html,'email'=>$pratik_sir['email']);
								$prashant=$this->getUser(144,2);
								$email_temp[]=array('html'=>$html,'email'=>$prashant['email']);
							}
							$i++;
						}
						
						$obj_email = new email_template();
						$rws_email_template = $obj_email->get_email_template(7); 
						$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				

						$path = HTTP_SERVER."template/order_template.html";
						$output = file_get_contents($path);  

						$search  = array('{tag:header}','{tag:details}');
						$form_email = ADMIN_EMAIL;
						$signature = 'Thanks.';
						foreach($email_temp as $val)
						{
							
							$firstTimeemial = 1;								

							$message = '';
							if($val['html'])

							{

								$tag_val = array(

								"{{header}}"=>'Stock Inventory Detail',

									"{{PouchMakersDetail}}" =>$val['html'],

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
							
							if($send==1)
							{
								send_email($val['email'],$form_email,$subject,$message,'',$url);
							}
							
						}
						
					 }
					
				 }
			 }
			
		 }
	 }
	
}

	
?>