<?php
class dashboard extends dbclass
{
	public function getLatestQuotation($user_type_id,$user_id)
	{
		if($user_type_id == 1 && $user_id == 1){
			
			$sql = "SELECT c.country_name,pq.product_quotation_id,mpq.multi_quotation_number,pq.multi_product_quotation_id,pq.quotation_status,pq.status,pq.customer_name,pq.product_name,pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_type,pq.date_added,pq.layer FROM " . DB_PREFIX . "multi_product_quotation pq ,multi_product_quotation_id mpq,country c WHERE pq.multi_product_quotation_id = mpq.multi_product_quotation_id AND c.country_id = pq.shipment_country_id AND pq.quotation_status = 1 AND  pq.status='1' GROUP BY mpq.multi_product_quotation_id ORDER BY pq.product_quotation_id DESC LIMIT 0,5";
		}else{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."'");
				 
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{	
				//echo $user_type_id; echo $user_id;
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			$str1='';
			if($userEmployee){//printr($userEmployee);die;
				$str = ' OR ( pq.added_by_user_id IN ('.$userEmployee.') AND pq.added_by_user_type_id IN ("2") ) ';
			}
			$str1=' GROUP BY mpq.multi_product_quotation_id ORDER BY pq.product_quotation_id DESC LIMIT 0,5';
			$sql = "SELECT c.country_name,pq.*,mpq.multi_quotation_number FROM " . DB_PREFIX . "multi_product_quotation pq,multi_product_quotation_id mpq,country c WHERE pq.multi_product_quotation_id = mpq.multi_product_quotation_id AND c.country_id = pq.shipment_country_id AND pq.status = '1' AND pq.quotation_status='1' AND (pq.added_by_user_id = '".(int)$set_user_id."' AND pq.added_by_user_type_id = '".(int)$set_user_type_id."'  ".$str." )  ".$str1."";
		}
		//printr($sql);
		$data = $this->query($sql);
		return $data->rows;
	}
	public function getUserEmployeeIds($user_type_id,$user_id)
	{
		if($user_id=='6' && $user_type_id=='4')
		    $sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' AND user_type='20'";
		else
		    $sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	
	public function getLatestEnquiries()
	{
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$sql = "SELECT am.user_name,c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.is_delete = 0";
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $user_id;//$this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( e.user_id IN ('.$userEmployee.') AND e.user_type_id = "2" )';
			}
			$sql .= " AND e.user_id = '".(int)$user_id."' AND e.user_type_id = '".(int)$user_type_id."' $str";
		}
		$sql .= " ORDER BY enquiry_id DESC LIMIT 0,5" ;	
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getUpcomingFollowup()
	{
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$today_date = date("Y-m-d");
		$sql = "SELECT am.user_name,e.enquiry_id,e.enquiry_number,ef.followup_date,CONCAT(e.first_name,' ',e.last_name) as name FROM " . DB_PREFIX . "enquiry_followup ef LEFT JOIN `" . DB_PREFIX . "enquiry` e ON (ef.enquiry_id=e.enquiry_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE ef.followup_date >= '".$today_date."'";
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $user_id; //$this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( e.user_id IN ('.$userEmployee.') AND e.user_type_id = "2" )';
			}
			$sql .= " AND e.user_id = '".(int)$user_id."' AND e.user_type_id = '".(int)$user_type_id."' $str";
		}
		$sql .= " ORDER BY ef.followup_date ASC LIMIT 0,5" ;	
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;	
		}else{
			return false;
		}
	}
	
	public function convertPrice($price,$currencyPrice){
		if($currencyPrice > 0){
			return $this->numberFormate(($price / $currencyPrice),"3");
		}else{
			return $price;
		}
	}
	
	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	
	public function getTotalQuotation($user_type_id,$user_id)
	{
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "multi_product_quotation";
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
				$str = ' OR ( added_by_user_id IN ('.$userEmployee.') AND added_by_user_type_id IN ("2") )';
			}
			$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "multi_product_quotation WHERE added_by_user_id = '".(int)$set_user_id."' AND added_by_user_type_id = '".(int)$set_user_type_id."' ".$str." ";
		}
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTotalActiveQuotation($user_type_id,$user_id)
	{
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "multi_product_quotation WHERE status=1";
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
				$str = ' OR ( added_by_user_id IN ('.$userEmployee.') AND added_by_user_type_id IN ("2") )';
			}
			$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "multi_product_quotation WHERE added_by_user_id = '".(int)$set_user_id."' AND added_by_user_type_id = '".(int)$set_user_type_id."' ".$str." AND status= '1'";
		}
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getTotalInactiveQuotation($user_type_id,$user_id)
	{
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "multi_product_quotation WHERE status=0";
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
				$str = ' OR ( added_by_user_id IN ('.(int)$userEmployee.') AND added_by_user_type_id IN ("2") )';
			}
			$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "multi_product_quotation WHERE added_by_user_id = '".(int)$set_user_id."' AND added_by_user_type_id = '".(int)$set_user_type_id."' ".$str." ";
		}
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getBackup()
	{
		$data = $this->query("SHOW TABLES");
		$backup_db_folder = DIR_SERVER."system_backup/".date('d-m-Y').'/database';
		$backup_files_folder = DIR_SERVER."system_backup/".date('d-m-Y');
		
		if (!file_exists($backup_db_folder)) {
			mkdir($backup_db_folder, 0777, true);
		}
		if (!file_exists($backup_files_folder)) {
			mkdir($backup_files_folder, 0777, true);
		}
		foreach($data->rows as $row){
			$backup_file  = $backup_db_folder.'/'.$row['Tables_in_swisspac'].".sql";
			$sql = "SELECT * INTO OUTFILE '$backup_file' FROM " . DB_PREFIX .$row['Tables_in_swisspac'];
			if(file_exists($backup_file)){
				unlink($backup_file);	
			}
			$this->query($sql);			
		}
		$this->createZip(DIR_SERVER, $backup_files_folder.'/backup_file.zip',true);
	}
	
	
	function createZip($source, $destination) 
	{
		if (extension_loaded('zip')) {
			if (file_exists($source)) {
				$zip = new ZipArchive();
				if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
					$source = realpath($source);
					if (is_dir($source)) {
						$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
						foreach ($files as $file) {
							$file = realpath($file);
							if (is_dir($file)) {
								$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
							} else if (is_file($file)) {
								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
							}
						}
					} else if (is_file($source)) {
						$zip->addFromString(basename($source), file_get_contents($source));
					}
				}
				return $zip->close();
			}
		}
		return false;
	}
	public function getLatestNewStock($cond='',$table='',$select='')
	{ 
			if($cond=='')
				$cond = 'AND sos.status=0 AND t.status=1';
			else
				$cond=$cond;
			$today_date = date("Y-m-d");
			$status= '0';
			$men_id = array('79','80');
			$menu= implode('|',$men_id);
			$con = '';
			$perm_cond = "add_permission REGEXP '".$menu."' AND edit_permission REGEXP '".$menu."' AND delete_permission REGEXP '".$menu."' AND view_permission REGEXP '".$menu."'";
			$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' 
			AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
			$dataper=$this->query($sql);
			if($dataper->num_rows)
			{	
				if($status == '')
				{	
					if($_SESSION['LOGIN_USER_TYPE']==2)
					{
						$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
						$dataadmin = $this->query($sqladmin);
						$con =  'AND pto.admin_user_id = "'.$dataadmin->row['user_id'].'"';
					}
					elseif($_SESSION['LOGIN_USER_TYPE']==4)
					{
						$con =  'AND pto.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'"';
					}
					elseif($_SESSION['LOGIN_USER_TYPE']==1)
					{
						$con = '';
					}
					else
					{
						return false;
					}
				}
				$sql = "SELECT ".$select." am.user_name,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code FROM " .DB_PREFIX . "template_order_test t,client_details cd,stock_order_test st,country c,stock_order_status_test as sos,product_template_order_test as pto,product_template as pt,currency as cu,account_master as am ".$table." WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con." AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND t.user_id = am.user_id AND t.user_type_id = am.user_type_id ";
			}
			else
			{ 
				if($_SESSION['LOGIN_USER_TYPE']==2)
				{
					$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
					$dataadmin = $this->query($sqladmin);
					$con =  'AND pto.admin_user_id = "'.$dataadmin->row['user_id'].'"';
				}
				elseif($_SESSION['LOGIN_USER_TYPE']==4)
				{
					$con =  'AND pto.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'"';
				}
				elseif($_SESSION['LOGIN_USER_TYPE']==1)
				{
					$con = '';
				}
				else
				{
					return false;
				}	
			    $sql = "SELECT ".$select." am.user_name,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code FROM " .DB_PREFIX . "template_order_test t,client_details cd,stock_order_test st,country c,stock_order_status_test as sos,product_template_order_test as pto,product_template as pt,currency as cu,account_master as am ".$table." WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con." AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND t.user_id = am.user_id AND t.user_type_id = am.user_type_id";		
			
		}
		$sql .= " GROUP BY st.stock_order_id ORDER BY t.template_order_id DESC LIMIT 0,5";
		$data=$this->query($sql);
		if($data->num_rows){
			return $data->rows;	
		}else{
			return false;
		}
	}
	
	public function getUpdatedDelayDateHistory()
	{
		
		$menu= '77';
		$status= '1';
		$con = '';
		$perm_cond ='add_permission LIKE "%'.$menu.'%" AND edit_permission LIKE "%'.$menu.'%" AND delete_permission LIKE "%'.$menu.'%" AND view_permission LIKE "%'.$menu.'%"';
		
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' 
		AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
		$dataper=$this->query($sql);
		if($dataper->num_rows)
		{
			if($status=='')
			{	
				if($_SESSION['LOGIN_USER_TYPE']==2)
				{
					$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
					$dataadmin = $this->query($sqladmin);
					$con =  'AND pto.admin_user_id = "'.$dataadmin->row['user_id'].'"';
				}
				elseif($_SESSION['LOGIN_USER_TYPE']==4)
				{
					$con =  'AND pto.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'"';
				}
				elseif($_SESSION['LOGIN_USER_TYPE']==1)
				{
					$con = '';
				}
				else
				{
					return false;
				}
				
			}
			$sql1 = "SELECT max(s.delay_history_id) as max_id FROM stock_delay_date_history_test as s, template_order_test as t WHERE t.template_order_id= s.template_order_id GROUP BY t.template_order_id LIMIT 0,5";
			$data1 = $this->query($sql1);
			 if($data1->num_rows)
			 {
				foreach($data1->rows as $data)
				{
					$sql= "SELECT sos.status as s,s.*,am.user_name,t.client_id,so.gen_order_id,cd.client_name,t.quantity,(t.quantity*t.price) as total_price,cu.currency_code,so.stock_order_id FROM template_order_test as t, stock_delay_date_history_test as s,account_master as am, stock_order_test as so, client_details as cd, product_template as pt, currency as cu,stock_order_status_test as sos,product_template_order_test as pto WHERE t.template_order_id= s.template_order_id AND s.edited_by_user_id = am.user_id AND s.edited_by_user_type_id = am.user_type_id AND t.client_id = so.client_id AND cd.client_id = t.client_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND s.template_order_id = sos.template_order_id AND s.delay_history_id = '".$data['max_id']."' AND sos.status !='2' AND so.admin_user_id=pto.admin_user_id AND t.stock_order_id = so.stock_order_id AND pto.order_id= s.product_template_order_id $con";
					$data2 = $this->query($sql);
					foreach($data2->rows as $d2)
					{
						$stock_delay[] = $d2;
					}
				}
				
			 }
		}
		else
		{	
			if($_SESSION['LOGIN_USER_TYPE']==2)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
				$dataadmin = $this->query($sqladmin);
				$con =  'AND pto.admin_user_id = "'.$dataadmin->row['user_id'].'"';
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==4)
			{
				$con =  'AND pto.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'"';
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==1)
			{
				$con = '';
			}
			else
			{
				return false;
			}
			$sql1 = "SELECT max(s.delay_history_id) as max_id FROM stock_delay_date_history_test as s, template_order_test as t WHERE t.template_order_id= s.template_order_id GROUP BY t.template_order_id LIMIT 0,5";
			$data1 = $this->query($sql1);
			 if($data1->num_rows)
			 {
				foreach($data1->rows as $data)
				{	
					$sql= "SELECT sos.status as s,s.*,am.user_name,t.client_id,so.gen_order_id,cd.client_name,t.quantity,(t.quantity*t.price) as total_price,cu.currency_code,so.stock_order_id FROM template_order_test as t, stock_delay_date_history_test as s,account_master as am, stock_order_test as so, client_details as cd, product_template as pt, currency as cu,stock_order_status_test as sos,product_template_order_test as pto WHERE t.template_order_id= s.template_order_id AND s.edited_by_user_id = am.user_id AND s.edited_by_user_type_id = am.user_type_id AND t.client_id = so.client_id AND cd.client_id = t.client_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND s.template_order_id = sos.template_order_id AND s.delay_history_id = '".$data['max_id']."' AND sos.status !='2' AND so.admin_user_id=pto.admin_user_id AND t.stock_order_id = so.stock_order_id  AND pto.order_id= s.product_template_order_id $con";
					$data2 = $this->query($sql);
					foreach($data2->rows as $d2)
					{
						$stock_delay[] = $d2;
					}
				}
				
			 }
			
		}
		if(isset($stock_delay) && !empty($stock_delay))
		{
			return $stock_delay;
		}
		else
		{
			return false;
		}
	}
	
	public function getlatestReminderDates($table_name,$order_id)
	{
		$data = $this->query("SELECT * FROM ".$table_name." WHERE ((remainder_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 day)) OR (last_remainder_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 day))) AND reminder='1' AND is_delete='0' ORDER BY ".$order_id." ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}	 
	
	}
    public function getLatestJobdata()
	{
         $sql = "SELECT pb.*,jm.job_name  FROM printing_job as pb,job_master as jm WHERE pb.is_delete = 0 AND  pb.job_name_id=jm.job_id  ORDER BY pb.job_id DESC LIMIT 5";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
 public function GetTotalSales($currency='',$pre_year=0)
 {
	$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
	$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
	$str=$ind='';$arr=array();
	$sql ="SELECT * FROM international_branch as ib, address as ad WHERE ad.address_id=ib.address_id AND ib.is_delete=0 AND ib.status='1' AND ib.international_branch_id IN ('7','6','24','33','10','44','19')";
	$data = $this->query($sql);
	if($pre_year==0)
	    $year = "YEAR(NOW())";
	else
	    $year = $pre_year;
	if($user_id=='1' && $user_type_id=='1')
	{
		foreach($data->rows as $row)
		{
			$userEmployee = $this->getUserEmployeeIds('4',$row['international_branch_id']);
			if($userEmployee)
			{
				$ind = ' OR ( si.added_user_id IN ('.$userEmployee.') AND si.added_user_type_id = 2 ) ';
				$str = ' OR (pi.added_by_user_id IN ('.$userEmployee.') AND pi.added_by_user_type_id = 2 ) ';
				$mex = ' OR (si.user_id IN ('.$userEmployee.') AND si.user_type_id = 2 ) ';
			}
			if($row['international_branch_id']=='10')
			{
			     //$sql1="SELECT SUM(si.payment_amount) as final_total,MONTHNAME(si.date_added) as month,si.user_id,si.user_type_id From packing_order as si WHERE si.is_delete=0  AND (si.user_id='".$row['international_branch_id']."' AND si.user_type_id='4' $str ) AND YEAR(si.date_added)=YEAR(NOW())  GROUP BY MONTHNAME(si.date_added) "; 
			     $sql1 = "SELECT SUM(si.payment_amount) as final_total,MONTHNAME(si.date_added) as month,c.currency_code,si.user_id,si.user_type_id From packing_order as si,proforma_product_code_wise as pi,currency as c WHERE si.is_delete=0 AND (si.user_id='".$row['international_branch_id']."' AND si.user_type_id='4' $mex ) AND YEAR(si.date_added)=YEAR(NOW()) AND pi.pro_in_no = si.pro_in_no AND c.currency_id = pi.currency_id AND  pi.currency_id = '10' GROUP BY MONTHNAME(si.date_added)";
			}
			else if($row['international_branch_id']=='6' || $row['international_branch_id']=='39')
			{
			    $sql1="SELECT SUM(si.invoice_total_amount) as final_total,MONTHNAME(si.invoice_date) as month From government_sales_invoice as si WHERE si.is_delete=0 AND si.invoice_status='1' AND (si.added_user_id='".$row['international_branch_id']."' AND si.added_user_type_id='4' $ind ) AND YEAR(si.invoice_date)=YEAR(NOW()) GROUP BY MONTHNAME(si.invoice_date) "; 
			}
			else
		        $sql1="SELECT SUM(p.payment_amount) as final_total, MONTHNAME(p.payment_receive_date) as month  From proforma_payment_detail as p, proforma_product_code_wise as pi,sales_invoice as si WHERE pi.proforma_id=p.proforma_id  AND pi.pro_in_no =si.proforma_no  AND  p.is_delete=0 AND si.is_delete=0 AND si.gen_status=0 AND si.status='1' AND (pi.added_by_user_id='".$row['international_branch_id']."' AND pi.added_by_user_type_id='4' $str ) AND YEAR(p.payment_receive_date)=YEAR(NOW())  GROUP BY MONTHNAME(p.payment_receive_date) "; 
		        //$sql1="SELECT SUM(si.amount_paid) as final_total,MONTHNAME(si.date_added) as month From sales_invoice as si WHERE si.is_delete=0 AND si.status='1' AND si.gen_status=0 AND (si.user_id='".$row['international_branch_id']."' AND si.user_type_id='4' $str ) AND YEAR(si.date_added)=YEAR(NOW()) GROUP BY MONTHNAME(si.date_added) "; 
		    
		    //printr($sql1);
			$data1 = $this->query($sql1);
			if($data1->num_rows)
			{
				foreach($data1->rows as $row1)
				{
					$arr[$row['international_branch_id']][]=array('Final_Total'=>$row1['final_total'],
							   'month'=>$row1['month'],
							   'user_name'=>'');
				}
			}
		}
	}
	else
	{
		if($user_type_id == 2){
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
			$row['international_branch_id']=$parentdata->row['user_id'];
		}else{
			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			
			$set_user_id = $user_id;
			$set_user_type_id = $user_type_id;
			$row['international_branch_id']=$user_id;
		}

		$str1='';
		if(isset($userEmployee))
		{
			$str = ' OR (pi.added_by_user_id IN ('.$userEmployee.') AND pi.added_by_user_type_id = "2" )';
            $ind = ' OR (si.added_user_id IN ('.$userEmployee.') AND si.added_user_type_id = 2 ) ';
            $mex = ' OR (si.user_id IN ('.$userEmployee.') AND si.user_type_id = 2 ) ';
		}
		if($set_user_id==10)
		{
            /*if($currency=='USD')
		        $sql1 = "SELECT SUM(p.payment_amount) as final_total,MONTHNAME(p.payment_receive_date) as month,c.currency_code,pi.added_by_user_id as user_id,pi.added_by_user_type_id as user_type_id From proforma_payment_detail as p, packing_order as si,proforma_product_code_wise as pi,currency as c WHERE pi.proforma_id=p.proforma_id  AND pi.pro_in_no =si. pro_in_no AND si.is_delete=0 AND (pi.added_by_user_id ='".$row['international_branch_id']."' AND pi.added_by_user_type_id ='4' $str ) AND YEAR(p.payment_receive_date)=".$year." AND pi.pro_in_no = si.pro_in_no AND c.currency_id = pi.currency_id AND  pi.currency_id = '2' GROUP BY MONTHNAME(p.payment_receive_date),pi.added_by_user_id";
		    else
		        $sql1 ="SELECT SUM(p.payment_amount) as final_total,MONTHNAME(p.payment_receive_date) as month,c.currency_code,pi.added_by_user_id as user_id,pi.added_by_user_type_id as user_type_id From proforma_payment_detail as p, packing_order as si,proforma_product_code_wise as pi,currency as c WHERE pi.proforma_id=p.proforma_id  AND pi.pro_in_no =si. pro_in_no AND si.is_delete=0 AND (pi.added_by_user_id ='".$row['international_branch_id']."' AND pi.added_by_user_type_id ='4' $str ) AND YEAR(p.payment_receive_date)=".$year."  AND pi.pro_in_no = si.pro_in_no AND c.currency_id = pi.currency_id AND  pi.currency_id = '10' GROUP BY MONTHNAME(p.payment_receive_date),pi.added_by_user_id";*/
            if($currency=='USD')
		        $sql1 = "SELECT SUM(si.payment_amount) as final_total,MONTHNAME(si.date_added) as month,c.currency_code,si.user_id,si.user_type_id From packing_order as si,proforma_product_code_wise as pi,currency as c WHERE si.is_delete=0 AND (si.user_id='".$row['international_branch_id']."' AND si.user_type_id='4' $mex ) AND YEAR(si.date_added)=".$year." AND pi.pro_in_no = si.pro_in_no AND c.currency_id = pi.currency_id AND  pi.currency_id = '2' GROUP BY MONTHNAME(si.date_added),si.user_id";
		    else
		        $sql1 = "SELECT SUM(si.payment_amount) as final_total,MONTHNAME(si.date_added) as month,c.currency_code,si.user_id,si.user_type_id From packing_order as si,proforma_product_code_wise as pi,currency as c WHERE si.is_delete=0 AND (si.user_id='".$row['international_branch_id']."' AND si.user_type_id='4' $mex ) AND YEAR(si.date_added)=".$year." AND pi.pro_in_no = si.pro_in_no AND c.currency_id = pi.currency_id AND  pi.currency_id = '10' GROUP BY MONTHNAME(si.date_added),si.user_id";
	    }
		else if($set_user_id==6  || $set_user_id=='39')
		   $sql1="SELECT SUM(si.invoice_total_amount) as final_total,MONTHNAME(si.invoice_date) as month,si.added_user_id as user_id,si.added_user_type_id as user_type_id From government_sales_invoice as si WHERE si.is_delete=0 AND si.invoice_status='1' AND (si.added_user_id='".$set_user_id."' AND si.added_user_type_id='".$set_user_type_id."' $ind ) AND YEAR(si.invoice_date)=YEAR(NOW())  GROUP BY MONTHNAME(si.invoice_date),si.added_user_id "; 
		else
		   $sql1="SELECT SUM(p.payment_amount) as final_total, MONTHNAME(p.payment_receive_date) as month, pi.added_by_user_id as user_id,pi.added_by_user_type_id as user_type_id  From proforma_payment_detail as p, proforma_product_code_wise as pi,sales_invoice as si WHERE pi.proforma_id=p.proforma_id  AND pi.pro_in_no =si.proforma_no  AND  p.is_delete=0 AND si.is_delete=0 AND si.gen_status=0 AND si.status='1' AND (pi.added_by_user_id='".$set_user_id."' AND pi.added_by_user_type_id='".$set_user_type_id."' $str ) AND YEAR(p.payment_receive_date)=YEAR(NOW())  GROUP BY MONTHNAME(p.payment_receive_date),pi.added_by_user_id "; 
		   //$sql1="SELECT SUM(si.amount_paid) as final_total,MONTHNAME(si.invoice_date) as month,si.user_id,si.user_type_id From sales_invoice as si WHERE si.is_delete=0 AND si.gen_status=0 AND si.status='1' AND (si.user_id='".$set_user_id."' AND si.user_type_id='".$set_user_type_id."' $str ) AND YEAR(si.invoice_date)=YEAR(NOW())  GROUP BY MONTHNAME(si.invoice_date),si.user_id "; 
		
		//printr($sql1);
		$data1 = $this->query($sql1);
		if($data1->num_rows)
		{
			foreach($data1->rows as $row1)
			{
				$sql2="SELECT user_name FROM account_master WHERE user_id='".$row1['user_id']."' AND user_type_id='".$row1['user_type_id']."'";
				$data2 = $this->query($sql2);
				$arr[$row['international_branch_id']][]=array('Final_Total'=>$row1['final_total'],
															  'month'=>$row1['month'],
															  'user_name' =>$data2->row['user_name']);
			}
			
		}  
	}
	if(!isset($arr) && !empty($arr))
		 $arr[]='';
	return $arr;
 }

    public function getChartView($s,$country,$con_curr,$key=0)
    {	
    	$html='';
    	if($key=='7')
		{
			$country = 'rgba(255,0,0,0.3)';
			$con_curr = 'Singapore (SGD)';
		}
		else if($key=='10')
		{
			$currency_for_mail = $this->getcurrencyformail($key,'4');
			$country = 'rgba(0,255,0,0.3)';
			$con_curr = 'Mexico (MXN)';
		}
		else if($key=='44')
		{
			$country = 'rgba(0,0,255,0.3)';
			$con_curr = 'Canada (CAD)';
		}
		else if($key=='24')
		{
			$country = 'rgba(192,192,192,0.3)';
			$con_curr = 'Melbourne (AUD)';
		}
		else if($key=='33')
		{
			$country = 'rgba(255,255,0,0.3)';
			$con_curr = 'Sydeny (AUD)';
		}
		else if($key=='19')
		{
			$currency_for_mail = $this->getcurrencyformail($key,'4');
			$country = 'rgba(19,160,165,1)';
			$con_curr = 'Dubai (AED)';
		}
		else
		{
			$country = 'rgba(255,0,255,0.3)';
			$con_curr = 'India (INR)';
		}
    	
    	$Jan=$Feb=$Mar=$Apr=$May=$June=$July=$Aug=$Sept=$Oct=$Nov=$Dec="0";
    	foreach($s as $month)
    	{ 
    		if(strtoupper($month['month'])==strtoupper('January'))
    		{
    			$Jan=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $Jan/$currency_for_mail['price'];
    		}
    		elseif(strtoupper($month['month'])==strtoupper('February'))
    		{
    			$Feb=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $Feb/$currency_for_mail['price']; 
    		}
    		elseif(strtoupper($month['month'])==strtoupper('March'))
    		{
    			$Mar=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $Feb/$currency_for_mail['price'];
    		}
    		elseif(strtoupper($month['month'])==strtoupper('April'))
    		{
    			$Apr=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $Apr/$currency_for_mail['price'];
    		}
    		elseif(strtoupper($month['month'])==strtoupper('May'))
    		{
    			$May=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $May/$currency_for_mail['price'];
    		}
    		elseif(strtoupper($month['month'])==strtoupper('June'))
    		{
    			$June=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $June/$currency_for_mail['price'];
    		}
    		elseif(strtoupper($month['month'])==strtoupper('July'))
    		{
    			$July=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $July/$currency_for_mail['price'];
    		}
    		elseif(strtoupper($month['month'])==strtoupper('August'))
    		{
    			$Aug=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $Aug/$currency_for_mail['price'];
    		}
    		elseif(strtoupper($month['month'])==strtoupper('September'))
    		{
    			$Sept=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $Sept/$currency_for_mail['price'];
    		}
    		elseif(strtoupper($month['month'])==strtoupper('October'))
    		{
    			$Oct=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $Oct/$currency_for_mail['price'];
    		}
    		elseif(strtoupper($month['month'])==strtoupper('November'))
    		{
    			$Nov=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $Nov/$currency_for_mail['price'];
    		}
    		else
    		{	
    			$Dec=$month['Final_Total'];
    			if($key=='19' || $key=='10')
    			  $usd_amt = $Dec/$currency_for_mail['price'];
    		}
    	}
    	   /*if($key=='19')
    	        $con_curr = 'Dubai : (USD)'.$usd_amt.'(AED)';
    	   else if($key=='10')     
    	        $con_curr = 'Mexico : (USD)'.$usd_amt.'(MXN)';*/
    		$html.= "{
    					label: '".$con_curr."',
    					data: [".$Jan.",".$Feb.",".$Mar.",".$Apr.",".$May.",".$June.",".$July.",".$Aug.",".$Sept.",".$Oct.",".$Nov.",".$Dec."],
    					backgroundColor: [
    						
    						
    							'".$country."',
    									'".$country."',
    									'".$country."',
    									'".$country."',
    									'".$country."',
    									'".$country."',
    									'".$country."',
    									'".$country."',
    									'".$country."',
    									'".$country."',
    									'".$country."',
    									'".$country."',],
    									borderColor: [
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										'rgba(255,99,132,1)',
    										],
    									borderWidth: 1
    									},	";
    	return $html;
    }
	
	public function GetTotalProforma()
	{
		$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
		$str='';$arr=array();
		if($user_id=='1' && $user_type_id=='1')
		{
			$sql ="SELECT * FROM international_branch as ib, address as ad WHERE ad.address_id=ib.address_id AND ib.is_delete=0 AND ib.status='1' AND ib.international_branch_id IN ('7','6','24','33','10','27')";
			$data = $this->query($sql);
			foreach($data->rows as $row)
			{
				$userEmployee = $this->getUserEmployeeIds('4',$row['international_branch_id']);
				if($userEmployee)
				{
					$str = ' OR ( pi.added_by_user_id IN ('.$userEmployee.') AND pi.added_by_user_type_id = 2 ) ';
				}
				$sql1="SELECT SUM(pi.invoice_total) as final_total,MONTHNAME(pi.date_added) as month From proforma as pi WHERE pi.is_delete=0 AND pi.status='1' AND (pi.added_by_user_id='".$row['international_branch_id']."' AND pi.added_by_user_type_id='4' $str ) AND YEAR(pi.date_added)=YEAR(NOW()) GROUP BY MONTHNAME(pi.date_added) "; 
				$data1 = $this->query($sql1);
				if($data1->num_rows)
				{
					foreach($data1->rows as $row1)
					{
						$arr[$row['international_branch_id']][]=array('Final_Total'=>$row1['final_total'],
								   'month'=>$row1['month'],
								   'user_name'=>'');
					}
					
				}
			}
		}
		else
		{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
				$row['international_branch_id']=$parentdata->row['user_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
				$row['international_branch_id']=$user_id;
			}
			$str1='';
			if(isset($userEmployee)){
				
				$str = ' OR ( pi.added_by_user_id IN ('.$userEmployee.') AND pi.added_by_user_type_id = 2 ) ';
				}
				
			$sql1="SELECT SUM(pi.invoice_total) as final_total,MONTHNAME(pi.date_added) as month,pi.added_by_user_id,pi.added_by_user_type_id From proforma as pi WHERE pi.is_delete=0 AND pi.status='1' AND (pi.added_by_user_id='".$set_user_id."' AND pi.added_by_user_type_id='".$set_user_type_id."' $str ) AND YEAR(pi.date_added)=YEAR(NOW())  GROUP BY MONTHNAME(pi.date_added),pi.added_by_user_id "; 
			$data1 = $this->query($sql1);
			if($data1->num_rows)
			{
				foreach($data1->rows as $row1)
				{
					$sql2="SELECT user_name FROM account_master WHERE user_id='".$row1['added_by_user_id']."' AND user_type_id='".$row1['added_by_user_type_id']."'";
					$data2 = $this->query($sql2);
					$arr[$row['international_branch_id']][]=array('Final_Total'=>$row1['final_total'],
																  'month'=>$row1['month'],
																  'user_name' =>$data2->row['user_name']);
				}
			}	
			
		}
		if(!isset($arr) && !empty($arr))
		    $arr[]='';
		return $arr;
	}
	
	
	public function GetTotalProformaProductCodeWise()
	{
		$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
		$str='';
		$arr=array();
		if($user_id=='1' && $user_type_id=='1')
		{
			$sql ="SELECT * FROM international_branch as ib, address as ad WHERE ad.address_id=ib.address_id AND ib.is_delete=0 AND ib.status='1' AND ib.international_branch_id IN ('7','6','24','33','10','44','19')";
			$data = $this->query($sql);
			foreach($data->rows as $row)
			{
				$userEmployee = $this->getUserEmployeeIds('4',$row['international_branch_id']);
				if($userEmployee)
				{
					$str = ' OR ( pi.added_by_user_id IN ('.$userEmployee.') AND pi.added_by_user_type_id = 2 ) ';
				}
				$sql1="SELECT SUM(pi.invoice_total) as final_total,MONTHNAME(pi.date_added) as month From proforma_product_code_wise as pi WHERE pi.is_delete=0 AND pi.status='1' AND (pi.added_by_user_id='".$row['international_branch_id']."' AND pi.added_by_user_type_id='4' $str ) AND YEAR(pi.date_added)=YEAR(NOW()) GROUP BY MONTHNAME(pi.date_added) "; 
				$data1 = $this->query($sql1);
				if($data1->num_rows)
				{
					foreach($data1->rows as $row1)
					{
						$arr[$row['international_branch_id']][]=array('Final_Total'=>$row1['final_total'],
								   'month'=>$row1['month'],
								   'user_name'=>'');
					}
					
				}
			}
		}
		else
		{
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
				$row['international_branch_id']=$parentdata->row['user_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
				$row['international_branch_id']=$user_id;
			}
			$str1='';
			if(isset($userEmployee)){
				$str = ' OR ( pi.added_by_user_id IN ('.$userEmployee.') AND pi.added_by_user_type_id = 2 ) ';
			}
				
			$sql1="SELECT SUM(pi.invoice_total) as final_total,MONTHNAME(pi.date_added) as month,pi.added_by_user_id,pi.added_by_user_type_id From  proforma_product_code_wise as pi WHERE pi.is_delete=0 AND pi.status='1' AND (pi.added_by_user_id='".$set_user_id."' AND pi.added_by_user_type_id='".$set_user_type_id."' $str ) AND YEAR(pi.date_added)=YEAR(NOW())  GROUP BY MONTHNAME(pi.date_added),pi.added_by_user_id "; 
			$data1 = $this->query($sql1);
			if($data1->num_rows)
			{
				foreach($data1->rows as $row1)
				{
					$sql2="SELECT user_name FROM account_master WHERE user_id='".$row1['added_by_user_id']."' AND user_type_id='".$row1['added_by_user_type_id']."'";
					$data2 = $this->query($sql2);
					$arr[$row['international_branch_id']][]=array('Final_Total'=>$row1['final_total'],
																  'month'=>$row1['month'],
																  'user_name' =>$data2->row['user_name']);
				}
			}	
		}
		if(!isset($arr) && !empty($arr))
		    $arr[]='';
		    
		return $arr;
	}
	
	public function getLatestLaminationdata()
	{
    	$sql = "SELECT pb.*,jm.job_name FROM  lamination as pb, employee as om, job_master as jm WHERE pb.is_delete = 0 AND pb.job_id=jm.job_id  GROUP 	by lamination_id   ORDER BY lamination_id  DESC LIMIT 5 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getLatestSlittingdata()
	{
		$sql = "SELECT sl.*,CONCAT(om.first_name ,' ', om.last_name) as operator_name,m.machine_name  FROM slitting as sl, employee as om,machine_master as m WHERE sl.is_delete = 0 AND sl.operator_id=om.employee_id AND  sl.machine_id = m.machine_id  ORDER BY slitting_id  DESC LIMIT 5 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getLatestPouchingdata()
	{
		$sql = "SELECT sl.*,CONCAT(om.first_name ,' ', om.last_name) as operator_name,m.machine_name,j.job_no ,j.job_name FROM pouching as sl,job_master as j, employee as om,machine_master as m WHERE sl.is_delete = 0 AND sl.job_id = j.job_id AND sl.operator_id=om.employee_id AND  sl.machine_id = m.machine_id  ORDER BY pouching_id  DESC LIMIT 5 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getbounceRecord()
	{
	    $return =array();
	    $first_day_this_month_gov = date('Y-m-01');
	    $first_day_this_month = date('Y-m-d', strtotime('-15 days', strtotime(date('Y-m-01'))));
	    $last_day_this_month  = date('Y-m-t');
	    
	    $sql = "SELECT COUNT(proforma_id) as total_inv,CONCAT(e.first_name ,' ', e.last_name) as emp_name,added_by_user_id,added_by_user_type_id  FROM proforma_product_code_wise as p,employee as e, international_branch as ib WHERE p.is_delete=0 AND p.status=1 AND proforma_status=0 AND p.added_by_user_id = e.employee_id AND p.added_by_user_type_id = '2' AND e.user_type='20' AND e.user_id= ib.international_branch_id AND ib.international_branch_id='6' AND p.date_added >='".$first_day_this_month."' AND p.date_added <='".$last_day_this_month."' GROUP BY p.added_by_user_id";
	    //echo $sql;
	    $data = $this->query($sql);
	    if($data->num_rows){
			foreach($data->rows as $row)
			{
			    $sql2="SELECT  COUNT(sales_invoice_id) as total_paid_inv FROM government_sales_invoice WHERE added_user_id='".$row['added_by_user_id']."' AND added_user_type_id='".$row['added_by_user_type_id']."' AND is_delete=0 AND status=1 AND invoice_status=1 AND invoice_date >='".$first_day_this_month_gov."' AND invoice_date <='".$last_day_this_month."'";
			    //echo $sql2;
			    $data2 = $this->query($sql2);
    		    $return[] = array(  'bounce_inv'=>(($row['total_inv']-$data2->row['total_paid_inv'])*100)/$row['total_inv'],
    			                    'sucess_inv'=>((($data2->row['total_paid_inv'])*100)/$row['total_inv']),
    			                    'total_invoice'=>$row['total_inv'],
    			                    'employee'=>$row['emp_name'],
    			                    'total_inv'=>$row['total_inv'],
    			                    'total_paid_inv'=>$data2->row['total_paid_inv'],);
			}
		}
		sort($return);
		//printr($return);
		return $return;
	}
	//kinjal done on 25-01-2019
	public function getLatestProforma()
	{
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$sql = "SELECT am.user_name,p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,account_master as am WHERE c.country_id=p.destination AND p.is_delete = 0 AND p.added_by_user_id = am.user_id AND p.added_by_user_type_id = am.user_type_id" ;
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee=$this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
    			$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
    			$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( p.added_by_user_id IN ('.$userEmployee.') AND p.added_by_user_type_id = 2 )';
			}
			$sql .= " AND (p.added_by_user_id='".$set_user_id."' AND p.added_by_user_type_id='".$set_user_type_id."' $str )";
		}
		$sql .= " ORDER BY p.proforma_id DESC LIMIT 0,5" ;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getLatestSalesInvoice()
	{
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$sql = "SELECT am.user_name,p.* FROM " . DB_PREFIX . "sales_invoice as p,account_master as am WHERE p.is_delete = 0 AND p.gen_status=0 AND p.user_id = am.user_id AND p.user_type_id = am.user_type_id" ;
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee=$this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				$set_user_id = $parentdata->row['user_id'];
    			$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
				$set_user_id = $user_id;
    			$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( p.user_id IN ('.$userEmployee.') AND p.user_type_id = 2 )';
			}
			$sql .= " AND (p.user_id='".$set_user_id."' AND p.user_type_id='".$set_user_type_id."' $str )";
		}
		$sql .= " ORDER BY p.invoice_id DESC LIMIT 0,5" ;
		$data = $this->query($sql);
		if($data->num_rows){ 
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getLatestJobMasterdata(){
	    	$sql = "SELECT * FROM  job_master as j, product as p WHERE j.is_delete = 0  AND p.product_id=j.product  ORDER BY j.job_id  DESC LIMIT 5  ";
	    	$data = $this->query($sql);
    		if($data->num_rows){
    			return $data->rows;
    		}else{
    			return false;
    		}
	}
	public function getLatestLaminationLayerData($lamination_id){
	    $sql ="SELECT * FROM   lamination_layer  WHERE is_delete = '0' AND lamination_id='".$lamination_id."' ";
      
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
	}
	public function getActiveProductCode(){
	    $sql ="SELECT * FROM `product_code` WHERE is_delete='0' AND color!='-1' ";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else { 
            return false;
        }
	}
	public function getcurrencyformail($user_id,$user_type_id){
		if($user_id=='2')
	       $sql = "SELECT ib.secondary_currency,cs.price,ib.secondary_currency,c.currency_code FROM international_branch as ib,currency_setting as cs,country as c WHERE ib.international_branch_id='".$user_id."' AND ib.is_delete = '0' AND cs.country_code = '15' AND cs.user_id='".$user_id."' AND c.country_id='15' ";
        else if($_SESSION['LOGIN_USER_TYPE']=='2')
            $sql = "SELECT ib.secondary_currency,cs.price,ib.secondary_currency,c.currency_code FROM international_branch as ib,currency_setting as cs,country as c WHERE ib.international_branch_id='".$user_id."' AND ib.is_delete = '0' AND ib.secondary_currency = cs.country_code AND cs.user_id='".$user_id."' AND c.country_id=ib.secondary_currency ";
        else
		    $sql = "SELECT ib.secondary_currency,cs.price,ib.secondary_currency,c.currency_code FROM international_branch as ib,currency_setting as cs,country as c WHERE ib.international_branch_id='".$user_id."' AND ib.is_delete = '0' AND ib.secondary_currency = cs.country_code AND cs.user_id='".$user_id."' AND c.country_id=ib.secondary_currency ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	

	
		//added by sonu  for search 12-11-2019
	public function getProformaDataForSearch($proforma_no,$n){
	
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$permission=0;
			if($user_type_id==1 && $user_id==1)

			{

				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND p.is_delete = '0'  " ;
                if(!empty($filter_data['product_code']))
					$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,proforma_invoice_product_code_wise as pi WHERE c.country_id=p.destination AND p.proforma_id = pi.proforma_id AND p.is_delete = '0'" ;

			}

			else

    		{
    
    				if($user_type_id == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
    
    				if($user_type_id==2 && ($user_id=='52' || $user_id=='204' || $user_id=='145' || $user_id=='91'))
    				    $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
                    else
                       $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
    
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
    				
    				if($permission==1)
    				    $per = " p.added_by_user_id IN (6,37,38,39) ";
    				else
    				    $per = "p.added_by_user_id='".$set_user_id."'";
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
    
    				$set_user_id = $user_id;
    
    				$set_user_type_id = $user_type_id;
                    
                    $per = "p.added_by_user_id='".$set_user_id."'";
    			}
    
    			$str = '';
    
    			if($userEmployee){
    
    				$str = ' OR ( p.added_by_user_id IN ('.$userEmployee.') AND  p.added_by_user_type_id = 2 )';
    
    			}
    
    				$sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c WHERE c.country_id=p.destination AND ( $per AND p.added_by_user_type_id = '".$set_user_type_id."' $str) AND p.is_delete = '0' " ;
                    if(!empty($filter_data['product_code']))
					       $sql = "SELECT p.*,c.country_name FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c,proforma_invoice_product_code_wise as pi WHERE c.country_id=p.destination AND p.proforma_id= pi.proforma_id AND ( $per  AND p.added_by_user_type_id='".$set_user_type_id."' $str) AND p.is_delete = '0'" ;

    		}

	if($n!=1)
		$sql .= " AND pro_in_no LIKE '%".$proforma_no."%' ";
	else
		$sql .= " AND buyers_order_no LIKE '%".$proforma_no."%' ";
	$data = $this->query($sql);
	//printr($data);
	   if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}

	}

	public function getCustomOrderDataForSearch($order_no){


	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$menu_id = 79;
		$perm_cond ='( add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"   ) ';
			
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
			//echo $sql;
		$dataper=$this->query($sql);
	if($user_type_id == 1 && $user_id == 1 || $dataper->num_rows!='0')
		{
			
		$sql="SELECT mpqi.multi_quotation_number,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id,CONCAT(mcop.transport_type,'',',') as transportation FROM multi_product_quotation_id as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.multi_product_quotation_id ";
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
				$str = ' OR ( mco.added_by_user_id IN ('.$userEmployee.') AND mco.added_by_user_type_id = 2 )';
			}
	
	
	$sql="SELECT mpqi.multi_quotation_number,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id,CONCAT(mcop.transport_type,'',',') as transportation FROM multi_product_quotation_id as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.multi_product_quotation_id AND  ((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str ) ";
		}

		$sql .= " AND mcoi.multi_custom_order_number LIKE '%".$order_no."%' ";
	 	$sql .= "GROUP BY mco.multi_custom_order_id";
$data = $this->query($sql);
	   if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}


	}public function getDigitalOrderDataForSearch($order_no){


	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$menu_id = 79;
		$perm_cond ='( add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"   ) ';
			
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
			//echo $sql;
		$dataper=$this->query($sql);
	if($user_type_id == 1 && $user_id == 1 || $dataper->num_rows!='0')
		{
			
			$sql="SELECT mpqi.digital_quotation_no,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id FROM digital_quotation as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.digital_quotation_id ";
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
				$str = ' OR ( mco.added_by_user_id IN ('.$userEmployee.') AND mco.added_by_user_type_id = 2 )';
			}
	
	
	$sql="SELECT mpqi.digital_quotation_no,mco.accept_decline_status,c.country_name,mco.status,mco.custom_order_id,mco.multi_custom_order_id,mcoi.customer_name,mcoi.company_name,mcoi.email,mcoi.reference_no,mco.product_name, mco.added_by_user_id,mco.added_by_user_type_id,mco.custom_order_status,mco.custom_order_type,mco.date_added,mco.layer, mco.use_device,mcop.zipper_txt,mcop.valve_txt, mcop.spout_txt,mcop.accessorie_txt,multi_custom_order_number, mcoi.multi_product_quotation_id FROM digital_quotation as mpqi , multi_custom_order mco,country c,multi_custom_order_price mcop,multi_custom_order_id as mcoi WHERE c.country_id = mco.shipment_country_id AND mco.custom_order_id = mcop.custom_order_id AND 1=1 AND mcoi.multi_custom_order_id=mco.multi_custom_order_id AND mcoi.multi_product_quotation_id=mpqi.digital_quotation_id AND  ((mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str ) ";
		}

		$sql .= " AND mcoi.multi_custom_order_number LIKE '%".$order_no."%' ";
		 	$sql .= "GROUP BY mco.multi_custom_order_id";
	 $data = $this->query($sql);

	   if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}


	}
	public function getSalesDataForSearch($sales_invoice_no){
	
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			if($user_type_id == 1 && $user_id == 1){
					$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination  WHERE  inv.is_delete = '0' AND inv.gen_status='0' " ;
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
						$str = ' OR ( inv.user_id IN ('.$userEmployee.') AND inv.user_type_id = 2 )';
					}
					$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "sales_invoice as inv LEFT JOIN country as c ON c.country_id=inv.final_destination WHERE  inv.is_delete = '0' AND inv.gen_status='0' AND  ((inv.user_id = '".(int)$set_user_id."' AND inv.user_type_id = '".(int)$set_user_type_id."' ) $str )  " ;
				}		
	$sql .= " AND invoice_no LIKE '%".$sales_invoice_no."%' ";
	$data = $this->query($sql);
//	printr($data);
	   if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}

	}
	public function getStockOrderDataForSearch($order_no){
		$menu_id=79;
		$status=0;
		$perm_cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' 
		AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
	
		$dataper=$this->query($sql);
		
		if($dataper->num_rows)
		{	
			$sql = "SELECT t.buyers_order_no,t.reference_no,t.country,t.order_type,t.price,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code,t.transport FROM " .DB_PREFIX . "template_order_test t,client_details cd,stock_order_test st,country c,stock_order_status_test as sos,product_template_order_test as pto,product_template as pt,currency as cu   WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0  AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id ";
			
		}
		else
		{		//echo "bye";
			    if($_SESSION['LOGIN_USER_TYPE']==2)
				{
					$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
					$dataadmin = $this->query($sqladmin);
					$con =  'AND pto.admin_user_id = "'.$dataadmin->row['user_id'].'"';
				}
				elseif($_SESSION['LOGIN_USER_TYPE']==4)
				{
					$con =  'AND pto.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'"';
				}
				elseif($_SESSION['LOGIN_USER_TYPE']==1)
				{
					$con = '';
				}
				else
				{
					return false;
				}
         
		$sql = "SELECT  GROUP_CONCAT(t.template_order_id) as temp_id,t.buyers_order_no,t.reference_no,t.order_type,t.country,t.price,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code,t.transport FROM " .DB_PREFIX . "template_order_test t,client_details cd,stock_order_test st,country c,stock_order_status_test as sos,product_template_order_test as pto,product_template as pt,currency as cu  WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0   AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id ";		

			$sql .= " AND st.gen_order_id LIKE '%".$order_no."%'";
			$sql .= " GROUP BY st.stock_order_id, pto.admin_user_id ";
			$sql .= " ORDER BY t.date_added DESC";	

			$data = $this->query($sql);
 		 if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}

		//echo $sql;
	//	die;
	    }
	
		

	}

	public function getMultiQuotationDataForSearch($quo_no){
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];

	if($user_type_id == 1 && $user_id == 1){
		$sql = "SELECT c.country_name,pq.status,pq.product_quotation_id,pq.multi_product_quotation_id,pq.customer_name,pq.product_name, pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_status,pq.quotation_type,pq.date_added,pq.layer, pq.use_device,pqp.zipper_txt,pqp.valve_txt,
		pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number FROM multi_product_quotation pq,country c,multi_product_quotation_price pqp,multi_product_quotation_id as mpq WHERE c.country_id = pq.shipment_country_id AND
		pq.product_quotation_id = pqp.product_quotation_id AND 1=1 AND mpq.multi_product_quotation_id=pq.multi_product_quotation_id  ";
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
				$str = ' OR ( pq.added_by_user_id IN ('.$userEmployee.') AND pq.added_by_user_type_id = 2 )';
			}
			//comment by sonu 15-3-2017 told by vikas sir 
			$sql = "SELECT pq.*,pqp.valve_txt,c.country_name,pqp.zipper_txt,pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number  FROM multi_product_quotation pq ,country as c, multi_product_quotation_id as mpq , multi_product_quotation_price  as pqp  WHERE  mpq.multi_product_quotation_id=pq.multi_product_quotation_id  AND  pq. shipment_country_id = c.country_id AND pq.product_quotation_id=pqp.product_quotation_id AND   pq.admin_user_id = '".(int)$set_user_id."' ";
		}
		$sql .= " AND mpq.multi_quotation_number LIKE '%".$quo_no."%'";
		$sql .= "GROUP BY pq.multi_product_quotation_id";

			$data = $this->query($sql);
 		 if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}


	}

	public function getDigitalQuotationDataForSearch($quo_no){
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];


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
		$sql .= " AND dqi.digital_quotation_no LIKE '%".$quo_no."%'";
	    $sql .= " GROUP BY dqi.digital_quotation_id";
		$data = $this->query($sql);
 		 if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
	}
	public function getLeadsDataForSearch($filter_data){
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		
		$sql = "SELECT am.user_name,c.country_name,e.*,CONCAT(e.first_name,' ',e.last_name) as name,es.source FROM `" . DB_PREFIX . "enquiry` e LEFT JOIN `" . DB_PREFIX . "enquiry_source` es ON (e.enquiry_source_id = es.enquiry_source_id) LEFT JOIN `" . DB_PREFIX . "country` c ON (e.country_id = c.country_id) LEFT JOIN `" . DB_PREFIX . "account_master` am ON (e.user_id = am.user_id) AND (e.user_type_id = am.user_type_id) WHERE e.is_delete = 0";
		
		if($user_type_id != 1 && $user_id != 1){
			if($user_type_id == 2){
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				//$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				//[kinjal] added on 5-10-2017
                //if($all_emp==1)
					$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'], $parentdata->row['user_id']);
			//	else 
				//	$userEmployee = $user_id;
			}else{
				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
			}
			$str = '';
			if($userEmployee){
				$str = ' OR ( e.user_id IN ('.$userEmployee.') AND e.user_type_id = "2" )';
			}
			
			$sql .= " AND (e.user_id = '".(int)$user_id."' AND e.user_type_id = '".(int)$user_type_id."' $str)";
		}
		$sql .= " AND (e.enquiry_number LIKE '%".$filter_data."%'  OR  CONCAT(e.first_name,' ',  e.last_name) LIKE '%".$filter_data."%')  ";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getPaymentDataForSearch($payment_amount){
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$permission=0;
			if($user_type_id==1 && $user_id==1)

			{

				$sql = "SELECT p.*,c.country_name,pd.* FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c , `proforma_payment_detail` as pd WHERE c.country_id=p.destination  AND   pd.proforma_id = p.proforma_id  AND p.is_delete = '0'  AND pd.is_delete=0 " ;
               
			}

			else

    		{

    			if($user_type_id == 2){
    
    				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
    
    				if($user_type_id==2 && ($user_id=='52' || $user_id=='204' || $user_id=='145' || $user_id=='91'))
    				    $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
                    else
                       $userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
    
    				$set_user_id = $parentdata->row['user_id'];
    
    				$set_user_type_id = $parentdata->row['user_type_id'];
    				
    				if($permission==1)
    				    $per = " p.added_by_user_id IN (6,37,38,39) ";
    				else
    				    $per = "p.added_by_user_id='".$set_user_id."'";
    
    			}else{
    
    				$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
    
    				$set_user_id = $user_id;
    
    				$set_user_type_id = $user_type_id;
                    
                    $per = "p.added_by_user_id='".$set_user_id."'";
    			}
    
    			$str = '';
    
    			if($userEmployee){
    
    				$str = ' OR ( p.added_by_user_id IN ('.$userEmployee.') AND  p.added_by_user_type_id = 2 )';
    
    			}
    
    			$sql = "SELECT p.*,c.country_name,pd.* FROM " . DB_PREFIX . "proforma_product_code_wise as p,country as c , `proforma_payment_detail` as pd WHERE c.country_id=p.destination  AND   pd.proforma_id = p.proforma_id  AND p.is_delete = '0'  AND pd.is_delete=0 AND ( $per AND p.added_by_user_type_id = '".$set_user_type_id."' $str) AND p.is_delete = '0' " ;  	

    		}

	
	$sql .= "  AND pd.payment_amount='".$payment_amount."' ";	
	$data = $this->query($sql);
	   if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}

	}
	//end

	
	
}
?>