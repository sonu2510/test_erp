<?php  //ruchi 30/4/2015 changes for price uk
class templateorder extends dbclass{

	public function GetOrderList($user_id,$usertypeid,$status='',$data='',$con='',$filter_array=array(),$client_id='',$dis_cond='',$dis_table='',$dis_select='',$page='',$st='',$stock_order_id='',$custom_order_id='')
	{
		//printr($custom_order_id);
		//die; 
		 $menu_id = $this->getMenuPermission(ORDER_ACCEPT_ID,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		 //printr($menu_id);
		$admin = '';
		if($status=='')
			$status ='AND pto.order_id = t.product_template_order_id';
			
			if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'  ";
				$dataadmin = $this->query($sqladmin);
				$cond = 'AND pto.admin_user_id = '. $dataadmin->row['user_id'].'';
				$admin_user_id =  $dataadmin->row['user_id'];
				$table= 'employee as ib ,';
				//echo $cond;
			}
			elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
			{
				$cond = 'AND pto.admin_user_id = '. $_SESSION['ADMIN_LOGIN_SWISS'].' ';
				//$cond = '';
				
				$admin_user_id = $user_id;
				$table= 'international_branch as ib , ';
			}
			else
			{
				$cond = ' ';
				$table = ' ';
				$admin_user_id ='';
				$page=0;
			}
			if(($menu_id OR $_SESSION['LOGIN_USER_TYPE']==1 ) OR $data!=2)
			{
				//$con='';
				$cond = ' ';
				$table = ' ';
			}
			if($page==1)
			{
				$admin = 'AND pto.admin_user_id="'.$admin_user_id.'"';
			}
			
			/*if($in_process_status != '0')
			{
				$sql = "SELECT t.quantity, sos.dis_qty FROM stock_order_status as sos, template_order as t WHERE sos.template_order_id = t.template_order_id  AND t.client_id = '".$client_id."'";
				$d = $this->query($sql);
				//foreach 
				printr($d);
				
			}
			else
			{
				
			}*/
			
			if($client_id!='')
			{
				$client_id=" AND t.client_id = '".$client_id."' AND ";
				
			}
			else
			{
				$client_id =" AND ";
			}
			if($stock_order_id!='')
			{
				$stock_order_id =" t.stock_order_id= '".$stock_order_id."' AND";
			}
			else
			{
				$stock_order_id = "";
			}
			
	/*	$sql = "SELECT ".$dis_select." so.gen_order_id,t.client_id,t.expected_ddate,t.note,t.template_order_id,t.quantity,p.product_id,p.email_product,p.product_name,pt.title,pts.width,pts.height,pts.gusset,pto.shipment_country,pto.ship_type as pto_ship_type,pto.admin_user_id,t.transport,
pts.volume,t.note,c.country_name,cu.currency_code,pc.email_color,pc.color,t.user_id,t.user_type_id,t.price,pts.valve,cd.client_name,		pts.zipper,pts.spout,pts.accessorie,t.ship_type,pt.product_template_id,t.address,t.date_added,pt.transportation_type,t.product_template_order_id,sos.review,sos.track_id,sos.date,sos.courier_id,pto.order_id,sos.process_by,sos.dispach_by,sos.status,pts.quantity1000,pts.quantity2000,pts.quantity5000,pts.quantity10000,t.price_uk FROM " .DB_PREFIX . " template_order t,currency as cu,product as p,product_template pt,product_template_size as pts,country as c,pouch_color as pc, product_template_order as pto,stock_order_status as sos, courier as co,client_details as cd,stock_order as so ".$dis_table."  WHERE t.product_id = p.product_id AND pt.product_template_id = t.template_id AND so.stock_order_id=t.stock_order_id AND so.client_id=t.client_id AND pto.admin_user_id=so.admin_user_id AND ".$con." t.template_order_id=sos.template_order_id  ".$status." AND pt.currency = cu.currency_id AND t.is_delete = 0  ".$cond."  
".$client_id."  t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color AND t.country=c.country_id AND pto.order_id = t.product_template_order_id ".$admin." AND  t.is_delete = 0 AND t.client_id=cd.client_id";*/	



	$sql = "SELECT ".$dis_select." so.gen_order_id,so.address_book_id,t.client_id,t.reference_no,t.order_type,t.expected_ddate,t.note,t.template_order_id,t.quantity,p.product_id,p.email_product,p.product_name,pt.title,pts.width,pts.height,pts.gusset,pto.shipment_country,pto.ship_type as pto_ship_type,pto.admin_user_id,t.transport,
pts.volume,t.note,c.country_name,cu.currency_code,pc.email_color,pc.color,t.user_id,t.user_type_id,t.price,pts.valve,cd.client_name,		pts.zipper,pts.spout,pts.accessorie,t.ship_type,pt.product_template_id,pts.product_template_size_id,t.address,t.date_added,pt.transportation_type,t.product_template_order_id,sos.review,sos.date,pto.order_id,sos.process_by,sos.dispach_by,sos.status,pts.quantity1000,pts.quantity2000,pts.quantity5000,pts.quantity10000,t.price_uk FROM " .DB_PREFIX . " template_order t,currency as cu,product as p,product_template pt,product_template_size as pts,country as c,pouch_color as pc, product_template_order as pto,stock_order_status as sos, courier as co,client_details as cd,stock_order as so ".$dis_table."  WHERE t.product_id = p.product_id AND pt.product_template_id = t.template_id AND so.stock_order_id=t.stock_order_id AND so.client_id=t.client_id AND pto.admin_user_id=so.admin_user_id AND ".$con." t.template_order_id=sos.template_order_id  ".$status." AND pt.currency = cu.currency_id AND t.is_delete = 0  ".$cond."  
".$client_id." ".$stock_order_id."  t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color AND t.country=c.country_id AND pto.order_id = t.product_template_order_id ".$admin." AND  t.is_delete = 0 AND t.client_id=cd.client_id";
//echo $sql.'<br>';//die; AND co.courier_id = sos.courier_id,,co.courier_name
	
			if(!empty($filter_array)) {
			if(!empty($filter_array['order_no'])){
				$sql .= " AND so.gen_order_id = '".$filter_array['order_no']."'";				
			}
			
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(t.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND p.product_name = '".$filter_array['product_name']."'";
			}
			if(!empty($filter_array['postedby']))
			{
				$spitdata = explode("=",$filter_array['postedby']);
				$sql .=" AND t.user_type_id = '".$spitdata[0]."' AND t.user_id = '".$spitdata[1]."'";
			}				
		}
		$sql .= " GROUP BY t.template_order_id"; 
		
		if (isset($data['sort'])) {
		$sql .= " ORDER BY " . $data['sort'];	
		} else {
		$sql .= " ORDER BY t.template_order_id";	
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
		//echo '<br>'.$sql;//die;
//echo $sql.'<br>';//die;
		$data = $this->query($sql);
		//printr($data);
		//printr($custom_order_id);
		if($custom_order_id!='')
		{
			$cust_data = $this->getCustomAcceptedRecords('3',$custom_order_id);
			//printr($cust_data);
			if(!empty($cust_data))
			{
				foreach($cust_data as $cust)
				{
					//printr($cust);
					array_push($data->rows,$cust);
				}			
			}
		}
		
	//printr($data->rows);
		if($data->num_rows)
		{
			//echo $con;
			return $data->rows;
		}
		else
		{
			if($custom_order_id!='')
				return $cust_data;
			else
				return false;
		}
	}
	public function GetEmailList($group_id)
	{
		$sql = "SELECT * FROM stock_order_email_history_id WHERE group_id = '".$group_id."' GROUP BY client_id ORDER BY stock_order_id DESC";
		$data=$this->query($sql);
		//echo $sql;
		//die;
		$arr_detail=array();
		foreach($data->rows as $val)
		{
			$details = $this->GetOrderList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],'AND t.status = 1 AND sos.status=1','','',$filter_array=array(),$val['client_id']);
		//printr($details);
			if($details)
				$arr_detail= array_merge($details,$arr_detail);
		}	
		return $arr_detail;	
	}
	public function getemailhistory($group_id)
	{
		$sql = "SELECT st.*,am.user_name,GROUP_CONCAT(DISTINCT product_template_order_id, '==', template_order_id,'==',client_id,'') as details FROM stock_order_email_history_id as st,account_master as am  WHERE st.user_id = am.user_id AND 
		st.user_type_id = am.user_type_id AND email_id='".$group_id."' GROUP BY group_id ORDER BY history_id DESC";
		$data=$this->query($sql);
		//echo $sql;
		//die;
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	public function GetCartOrderList($user_id,$usertypeid,$cond='',$status='',$filter_array,$interval,$dis_table,$dis_select='',$option,$s,$address_id='0')
	{
		$add_id='';
        if($address_id!=0)
        {
            $add_id =  "AND st.address_book_id='".$address_id."'"; 
        }
		
		//printr($status);
		//die;
		///echo $status;
		$menu_id=$cust_data='';
		if($status==0)
		{
			$menu_id=79;
		} 
		elseif($status==1)
		{
			$menu_id=80;
		}
		$con = '';
		
		$perm_cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' 
		AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
		//echo $sql;
		$dataper=$this->query($sql);
	//	printr($dataper);
		/*if($dis_select!='')
		{
			
		}*/
		if($dataper->num_rows)
		{	//echo $_SESSION['LOGIN_USER_TYPE'];
			//echo $status;
			//echo "hii";
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
				//echo $con;
			}
			$sql = "SELECT ".$dis_select." t.buyers_order_no,t.reference_no,t.order_type,t.price,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code,t.transport FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu  ".$dis_table."  WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con." AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id $add_id";
			
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
					//echo $con;
		
	//$sql = "SELECT t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con."  AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id ";	
	//echo $dis_table.'<br>';
		$sql = "SELECT ".$dis_select." t.buyers_order_no,t.reference_no,t.order_type,t.price,t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code,t.transport FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu ".$dis_table." WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond."  AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id ".$con." $add_id";		
		//echo $sql;
	//	die;
	}
	//echo $sql;
			if(!empty($filter_array)) {
				if(!empty($filter_array['order_no'])){
					$sql .= " AND st.gen_order_id = '".$filter_array['order_no']."'";				
			}
			//echo $sql;
			//die;
			if(!empty($filter_array['date'])){
				$sql .= " AND date(t.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
				//echo $sql;die;
			}	
		}
	//	$sql .= " GROUP BY cd.client_name ORDER BY t.template_order_id";
		//printr($interval);
		
		if($interval!='')
			$sql.=" AND t.date_added BETWEEN DATE_SUB(NOW(), INTERVAL ".$interval." DAY) AND NOW()";
	
	//echo '<br>';
	//die;       ORDER BY t.template_order_id ASC 
	$sql .= " GROUP BY st.stock_order_id, pto.admin_user_id ";
	
	
		if (isset($option['sort'])) {
			$sql .= " ORDER BY pto.admin_user_id";	
		} else {
			$sql .= " ORDER BY pto.admin_user_id";	
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
	//echo $sql.'<br></br>';
	//die;
		$data = $this->query($sql);
		
		//$data->rows = array();
		if($s!='0')
		{	
			//echo 'jjjj';
			$cust_data = $this->getCustomAcceptedRecords($s);
			if(!empty($cust_data))
			{
				foreach($cust_data as $cust)
				{
					array_push($data->rows,$cust);
				}			
			}
			//printr($data->rows);
		}
			
		
		//printr($cust_data);
		if($data->num_rows)
		{
			//echo $con;
			return $data->rows;
		}
		else
		{
			if(!empty($cust_data))
				return $cust_data;
			else
				return false;
		}
	}
	public function GetTotalCartOrderList($user_id,$usertypeid,$cond='',$status='',$filter_array,$interval,$dis_table,$dis_select,$s,$address_id='0')
	{
	    $add_id='';
        if($address_id!=0)
        {
            $add_id =  "AND st.address_book_id='".$address_id."'"; 
        }
	
		//printr($filter_array);
		//die; 
	//	SELECT t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id FROM template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt WHERE c.country_id = t.country AND cd.client_id = t.client_id AND t.is_delete = 0 AND (sos.status="1" ) AND t.status=1 AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND st.stock_order_id=t.stock_order_id GROUP BY st.stock_order_id ORDER BY t.template_order_id 
		$con = '';
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
		$sql = "SELECT ".$dis_select." t.product_template_order_id,t.customer_order_no,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto ".$dis_table." WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con."  AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND st.stock_order_id=t.stock_order_id $add_id";		
		//echo $sql;
	//	die;
			if(!empty($filter_array)) {
				if(!empty($filter_array['order_no'])){
					$sql .= " AND st.gen_order_id = '".$filter_array['order_no']."'";				
			}
			//echo $sql;
			//die;
			if(!empty($filter_array['date'])){
				$sql .= " AND date(t.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
				//echo $sql;die;
			}		
		}
		
		if($interval!='')
			$sql.=" AND t.date_added BETWEEN DATE_SUB(NOW(), INTERVAL ".$interval." DAY) AND NOW()";
		
		$sql .= " GROUP BY st.stock_order_id ORDER BY t.template_order_id";
		//echo $sql;
		//die;
		$data = $this->query($sql);
		//printr($stus)
		if($s!='0')
		{	
			//echo 'jjjj';
			$cust_data = $this->getCustomAcceptedRecords($s);
			if(!empty($cust_data))
			{
				foreach($cust_data as $cust)
				{
					array_push($data->rows,$cust);
				}			
			}
		}
		
		if($data->num_rows)
		{
			//echo $con;
			return $data->num_rows;
		}
		else
		{
			if(!empty($cust_data))
				return $cust_data;
			else
				return false;
		}
	}

	public function GetTotalOrderList($user_id,$usertypeid,$status,$filter_array=array(),$client_id='')
	{		
		$menu_id = $this->getMenuPermission(ORDER_ACCEPT_ID,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);		
		if($status=='')
			$status ='AND pto.order_id = t.product_template_order_id';
		if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'  ";
			$dataadmin = $this->query($sqladmin);
			$cond = 'AND pto.admin_user_id = '. $dataadmin->row['user_id'].'';
			$admin_user_id =  $dataadmin->row['user_id'];
			$table= 'employee as ib ,';
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$cond = 'AND pto.admin_user_id = '. $_SESSION['ADMIN_LOGIN_SWISS'].' ';
			//$cond = '';
			$admin_user_id = $user_id;
			$table= 'international_branch as ib , ';
		}
		else
		{
			$cond = ' ';
			$table = ' ';
		}
		if($menu_id OR $_SESSION['LOGIN_USER_TYPE']==1)
		{
			$cond = ' ';
			$table = ' ';
		}
		if($client_id!='')
		{
			$client_id=" AND t.client_id = '".$client_id."' AND ";
		}
		else
			$client_id =" AND ";
		$sql = "SELECT t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0
		 AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id";	
			if(!empty($filter_array)) {
				if(!empty($filter_array['order_no'])){
					$order = explode("-",$filter_array['order_no']);
					$sql .= " AND t.template_order_id = '".$order[1]."'";				
				}
			//echo $sql;
			//die;
				if(!empty($filter_array['date'])){			
					$sql .= " AND date(t.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";				
					//echo $sql;die;
				}			
				if(!empty($filter_array['product_name'])){
					$sql .= " AND p.product_name = '".$filter_array['product_name']."'";
				}			
				if(!empty($filter_array['postedby']))
				{
					$spitdata = explode("=",$filter_array['postedby']);
					//printr($spitdata);
					$sql .=" AND t.user_type_id = '".$spitdata[0]."' AND t.user_id = '".$spitdata[1]."'";
				}				
			}
			$sql .= " GROUP BY t.template_order_id"; 
			//echo $sql;
			//die;
			if (isset($data['sort'])) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY t.template_order_id";	
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
			$data = $this->query($sql);
			if($data->num_rows)
			{
				//echo $con;
				return $data->num_rows;
			}
			else
			{
				return false;
			}
	}
	public function GetTotalEmailList($user_id,$usertypeid,$status,$filter_array=array(),$client_id='')
	{		
		$menu_id = $this->getMenuPermission(ORDER_ACCEPT_ID,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);		
		if($status=='')
			$status ='AND pto.order_id = t.product_template_order_id';
		if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'  ";
			$dataadmin = $this->query($sqladmin);
			$cond = 'AND pto.admin_user_id = '. $dataadmin->row['user_id'].'';
			$admin_user_id =  $dataadmin->row['user_id'];
			$table= 'employee as ib ,';
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$cond = 'AND pto.admin_user_id = '. $_SESSION['ADMIN_LOGIN_SWISS'].' ';
			//$cond = '';
			$admin_user_id = $user_id;
			$table= 'international_branch as ib , ';
		}
		else
		{
			$cond = ' ';
			$table = ' ';
		}
		if($menu_id OR $_SESSION['LOGIN_USER_TYPE']==1)
		{
			$cond = ' ';
			$table = ' ';
		}
		$sql = "SELECT seoh.*,t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count FROM " .DB_PREFIX . "stock_order_email_history_id seoh,template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND seoh.template_order_id=t.template_order_id AND seoh.product_template_order_id=t.product_template_order_id";	
			if(!empty($filter_array)) {
				if(!empty($filter_array['order_no'])){
					$order = explode("-",$filter_array['order_no']);
					$sql .= " AND t.template_order_id = '".$order[1]."'";				
				}
				//echo $sql;
				//die;
				if(!empty($filter_array['date'])){			
					$sql .= " AND date(t.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";				
					//echo $sql;die;
				}			
				if(!empty($filter_array['product_name'])){
					$sql .= " AND p.product_name = '".$filter_array['product_name']."'";
				}			
				if(!empty($filter_array['postedby']))
				{
					$spitdata = explode("=",$filter_array['postedby']);
					//printr($spitdata);
					$sql .=" AND t.user_type_id = '".$spitdata[0]."' AND t.user_id = '".$spitdata[1]."'";
				}				
			}
			$sql .= " GROUP BY t.template_order_id"; 
			//echo $sql;
			//die;
			if (isset($data['sort'])) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY t.template_order_id";	
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
			$data = $this->query($sql);
			if($data->num_rows)
			{
				//echo $con;
				return $data->num_rows;
			}
			else
			{
				return false;
			}
	}
	
	public function GetEmailHistoryList($filter_array=array())
	{
		//printr($filter_array);
		//die;
		$sql = "SELECT GROUP_CONCAT(DISTINCT stock_order_id,' ',' ')as gen_stock_order_id,date,user_type_id,user_id,history_id,group_id,product_template_order_id,template_order_id,stock_order_id,email_id FROM " .DB_PREFIX . "stock_order_email_history_id WHERE email_id=0";
		if(!empty($filter_array))
		{
			if(!empty($filter_array['stock_order_id'])){
				$spitdata = explode("=",$filter_array['stock_order_id']);
				$sql .= " AND stock_order_id LIKE '%".$spitdata[0]."%'";	
			}
			if(!empty($filter_array['date'])){
				$sql .= " AND date = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}
			if(!empty($filter_array['postedby']))
			{
				$spitdata = explode("=",$filter_array['postedby']);
				$sql .=" AND user_type_id = '".$spitdata[0]."' AND user_id = '".$spitdata[1]."'";
			}			
		}
		else
		{
			//$_SESSION['ADMIN_LOGIN_SWISS'],
			if($_SESSION['LOGIN_USER_TYPE']!=1)
			{
				$sql .=" AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			}
		}
		
		$sql .=" GROUP BY group_id ORDER BY history_id DESC";
		//echo $sql;
		//die;
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
	
	public function GetTotalEmailHistoryList($filter_array=array())
	{			
		$sql = "SELECT * FROM " .DB_PREFIX . "stock_order_email_history_id WHERE email_id=0 ";			
			if(!empty($filter_array)) {
				if(!empty($filter_array['stock_order_id'])){
					//printr($filter_array['stock_order_id']);die;
					$order = explode("-",$filter_array['stock_order_id']);
					$sql .= " AND stock_order_id = '".$order[1]."'";				
				}
			//echo $sql;
			//die;
			if(!empty($filter_array['date'])){			
				$sql .= " AND date = '".date('Y-m-d',strtotime($filter_array['date']))."'";				
				//echo $sql;die;
			}			
			if(!empty($filter_array['postedby']))
			{
				$spitdata = explode("=",$filter_array['postedby']);
				//printr($spitdata);
				$sql .=" AND user_type_id = '".$spitdata[0]."' AND t.user_id = '".$spitdata[1]."'";
			}				
		}
		$sql .= " GROUP BY group_id"; 
		//echo $sql;
		//die;
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY history_id";	
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
		$data = $this->query($sql);
		if($data->num_rows)
		{
			//echo $con;
			return $data->num_rows;
		}
		else
		{
			return false;
		}
	}
	
	public function getUpdatedPrice($product_template_order_id,$template_order_id)
	{
		$sql ="SELECT new_price FROM stock_order_price_history WHERE product_template_order_id = ".$product_template_order_id." AND template_order_id=".$template_order_id." AND edited_by='1' ORDER BY order_history_id DESC LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			//echo $con;
			return $data->row['new_price'];
		}
		else
		{
			return false;
		}
	}
	
	public function getUpdatedPriceHistory($product_template_order_id,$template_order_id)
	{
		$sql ="SELECT * FROM stock_order_price_history WHERE product_template_order_id = ".$product_template_order_id." AND template_order_id=".$template_order_id." AND edited_by='1' ORDER BY order_history_id DESC";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			//echo $con;
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	
public function totalCount($user_id,$usertypeid,$client_id='',$tot_status,$stock_order_id,$status=0)
{
	//echo $stock_order_id.'<br>';
	///echo $tot_status.'<br>';
	//echo $stock_order_id;
	$menu_id = $this->getMenuPermission(ORDER_ACCEPT_ID,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);	
		if($_SESSION['LOGIN_USER_TYPE']==2) 
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'  ";
			$dataadmin = $this->query($sqladmin);
			$cond = 'AND pto.admin_user_id = '. $dataadmin->row['user_id'].'';
			$admin_user_id =  $dataadmin->row['user_id'];
			$table= 'employee as ib ,';
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$cond = 'AND pto.admin_user_id = '. $_SESSION['ADMIN_LOGIN_SWISS'].' ';
			//$cond = '';
			$admin_user_id = $user_id;
			$table= 'international_branch as ib , ';
		}
		else
		{
			$cond = ' ';
			$table = ' ';
		}
		if($menu_id OR $_SESSION['LOGIN_USER_TYPE']==1)
		{
			$cond = ' ';
			$table = ' ';
		}
		//SELECT * FROM (SELECT count(t.template_order_id) as c FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos WHERE pt.product_template_id = t.template_id AND t.template_order_id=sos.template_order_id AND t.status = 1 AND sos.status=0 AND t.is_delete = 0 AND t.client_id = '3' AND pto.order_id = t.product_template_order_id) as t1 , (SELECT count(t.template_order_id) as ct FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos WHERE pt.product_template_id = t.template_id AND t.template_order_id=sos.template_order_id AND t.status = 1 AND sos.status=0 AND t.is_delete = 0 AND t.client_id = '3' AND pto.order_id = t.product_template_order_id) as t2
	$sql ="SELECT * FROM (SELECT count(t.template_order_id) as pending FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id ".$cond." AND t.status = ".$tot_status." AND sos.status=0 AND s.stock_order_id=t.stock_order_id AND t.stock_order_id='".$stock_order_id."' AND t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = ".$client_id."  AND pto.order_id = t.product_template_order_id) as pending,(SELECT count(t.template_order_id) as total FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id ".$cond." AND t.status = ".$tot_status."  AND s.stock_order_id=t.stock_order_id AND t.stock_order_id='".$stock_order_id."' AND t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = ".$client_id."  AND pto.order_id = t.product_template_order_id) as total,(SELECT count(t.template_order_id) as accepted FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id ".$cond." AND t.status = ".$tot_status." ANd s.stock_order_id=t.stock_order_id AND t.stock_order_id='".$stock_order_id."' AND (sos.status=1 OR sos.status=3) AND  t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = ".$client_id."  AND pto.order_id = t.product_template_order_id) as accepted,(SELECT count(t.template_order_id) as decline FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id ".$cond." AND t.status = ".$tot_status." AND sos.status=2 AND s.stock_order_id=t.stock_order_id AND t.stock_order_id='".$stock_order_id."' AND t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = ".$client_id."  AND pto.order_id = t.product_template_order_id) as decline,(SELECT count(t.template_order_id) as dispatch FROM template_order t,product_template pt, product_template_order as pto,stock_order_status as sos,stock_order as s
	WHERE pt.product_template_id = t.template_id ".$cond." AND t.status = ".$tot_status." AND sos.status=3  AND s.stock_order_id=t.stock_order_id AND t.stock_order_id='".$stock_order_id."' AND t.template_order_id=sos.template_order_id   
	AND t.is_delete = 0 AND t.client_id = ".$client_id."  AND pto.order_id = t.product_template_order_id) as dispatch";
	
	//echo $sql;
	//echo "<br>";
	//echo "==========================================================";
	$data = $this->query($sql);
	//printr($data);//die;
		if($data->num_rows)
		{
			//echo $con;
			$dis_status='';
			if($status == '3' || $tot_status == '1')
			{
				$dis_status = 'OR sos.status=1 OR sos.status=2';
			}	
			$sql1 = "select t.transport FROM template_order as t,stock_order_status as sos WHERE t.stock_order_id=".$stock_order_id."  AND (sos.status=".$status." ".$dis_status.") AND t.template_order_id=sos.template_order_id ";
			//echo $sql1;echo "<br>";
			$data1 = $this->query($sql1);
			$transport = '';
			foreach($data1->rows as $key=>$d_rows)
			{
				//printr($d_rows);
				if($d_rows['transport'] == 'By Sea')
				{
					$tran = 'By Sea';
				}
				else
				{
					$tran = 'By Air';
				}
				//echo $tran;
				$transport[$tran] = $tran;
			}
			//printr($transport);
			return $total= array('total_count' => $data->row,
								'tran' =>$transport);
		}
		else
		{
			return false;
		}
}
	public function Checkoutrecords($status,$post)
	{
		
		//printr($post['post']);
		//die;
		
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
		//commented by jaya 17-08-2015
		$arr=array();
		foreach($post['post'] as $newval)
		{
			$ids=explode('==',$newval);
			//printr($ids);
		//$sql = "SELECT t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto WHERE c.country_id = t.country AND cd.client_id = t.client_id ".$con." AND  t.is_delete = 0 AND t.status=0  AND t.stock_order_id = st.stock_order_id AND t.stock_order_id='".$ids[3]."' AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND t.product_template_order_id=pto.order_id GROUP BY t.template_order_id ORDER BY t.template_order_id";
				$sql = "SELECT t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto WHERE c.country_id = t.country AND cd.client_id = t.client_id ".$con." AND  t.is_delete = 0 AND t.status=0  AND t.stock_order_id = st.stock_order_id AND st.gen_order_id='".$ids[3]."' AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND t.product_template_order_id=pto.order_id GROUP BY t.template_order_id ORDER BY t.template_order_id";
		
		//echo $sql;
		//echo '<br> ++++++++++++++++++++++++++++++++++++++';
		//die;	
		$data = $this->query($sql);
		$gen='';
		
		foreach($data->rows as $val)
		{
			$Sql = "UPDATE " .DB_PREFIX . "template_order  SET status = '".$status."' WHERE template_order_id = '".$val['template_order_id']."' AND  end_date > NOW()";
			$data2 = $this->query($Sql);
			$Sql1 = "UPDATE " .DB_PREFIX . "product_template_order  SET status = '".$status."' WHERE order_id = '".$val['product_template_order_id']."'";
			$data1 = $this->query($Sql1);
			//commented by jaya on 17-8-2015
			/*if($val['gen_order_id']!=$gen)
			{*/
				$arr[]=$val['template_order_id'].'=='.$val['product_template_order_id'].'=='.$val['client_id'];
			$gen=$val['gen_order_id'];
			//echo "UPDATE ". DB_PREFIX ."stock_order SET  status = '1' WHERE gen_order_id = '".$ids[3]."' ";
			//die;;
				//printr($arr);
					$this->query("UPDATE ". DB_PREFIX ."stock_order SET  status = '1' WHERE gen_order_id = '".$val['gen_order_id']."' ");
				
			//}
		}
		
		}
		//echo "UPDATE ". DB_PREFIX ."stock_order SET  status = '1' WHERE gen_order_id = '".$data->rows[0]['gen_order_id']."' ";
		
		//uncomment this fun [kinjal] 27/10/2016
		$this->sendOrderEmail($arr,0,ADMIN_EMAIL);
	}
	
	public function savenote($status,$post)
	{
	//printr($_POST);
		//$val = explode('=',$post['product_template_order_id']);
		for($i=1;$i<=$post['count'];$i++)
		{
			$Sql = "UPDATE " .DB_PREFIX . "template_order  SET note = '".$_POST['note'.$i]."' , price_uk='".$_POST['price_uk_'.$i]."' WHERE 
			template_order_id = '".$post['template_order_id'.$i]."' AND  end_date > NOW()";
			//echo $Sql;
			//die;
			$data = $this->query($Sql);			
		}	
		//$this->sendOrderEmail('',0,ADMIN_EMAIL,$_POST['product_template_order_id']);
	}
	
	
	
	/*public function sendOrderEmail($template_order_id='',$status,$adminEmail,$order_id='')
	{
	//	printr($template_order_id);
	//	die;
	$con='';
	$menu_id =0;
	if($status>0)
	{
		$con = '  t.template_order_id = '.$template_order_id .' AND';
		$cond = 'AND t.status = 1 AND sos.status='.$status.'';
		if($status==1)
		{
			$bg_color_code='#FFF0BA';
			$color_code='#FFDC5C';
			$span_color ='#FFC800';
			$span_class ='label bg-warning';
			$subject = 'Accepted Orders';
			$menu_id = ORDER_DISPATCHED_ID;
		}
		if($status==2)
		{
			$bg_color_code='#FCDCE0';
			$color_code='#D79FA6';
			$span_color ='#C22323';
			$span_class ='label bg-info';
			$subject = 'Declined Orders';			
		}
		if($status==3)
		{
			$bg_color_code='#D2FFE5';
			$color_code='#8CED9C';
			$span_color ='#D2FFE5';
			$span_class ='label bg-success';
			$subject = 'Dispatched Orders';
		}	
	}
	else
	{
		$cond = 'AND t.status = 1 AND sos.status='.$status.' AND pto.order_id='.$order_id.'';
		$bg_color_code='#8CC1ED';
		$color_code='#8CC1ED';
		$span_color ='#4B7496';
		$span_class ='label bg-info';
		$subject = 'Checked Out Orders';
		$menu_id = ORDER_NEWORDER_ID;
	}
			$permissionData = '';
			if($menu_id >0)
			$permissionData = $this->getUserPermission($menu_id);
			if(!empty($permissionData))
			{
				foreach($permissionData as $email_id)
				{
					$toEmail[$email_id['user_name']] = $email_id['email'];	
				}
			}
	//	echo $con;
		$setHtml = '';
		$setHtml .= '<div class="table-responsive">';
		$orders = $this->GetOrderList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$cond,'',$con);
								//	printr($orders);
								//	die;
									$f = 1;
									$total=0;$total_qty=0;
									$toEmail['super_admin'] = $adminEmail;
                            		foreach($orders as $order){  
								$setHtml .= '<style> table, th, td </style>';
		$setHtml .= '<div style="border:2px solid '.$color_code.';margin-bottom:10px;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tr style="border-color:'.$color_code.';background: '.$bg_color_code.';height:50px;"><td colspan="2">';
        $postedByData = $this->getUser($order['user_id'],$order['user_type_id']);
		////printr( $postedByData);
		//die;
		if(isset($postedByData['user_type_id']) && $postedByData['user_type_id'] == 4)
		{
			$adminpostedByData = $this->getUser($postedByData['user_id'],$postedByData['user_type_id']);
		}
		//printr($adminpostedByData);
		//die;
         $setHtml .= ' <span class="'.$span_class.'" style="font-size: 100%;background:'.$span_color.';"><b>&nbsp; STK-'.$order['template_order_id'].' </b></td></span>
		<td colspan="2"><span class="'.$span_class.'" style="font-size: 100%;background: '.$bg_color_code.';">'.$order['product_name'].' '.'';
        $setHtml .= '</td></span><td colspan="3"><span class="'.$span_class.'" style="font-size: 100%; ">'.$postedByData['user_name'].'</td></span></tr>'; 
        $setHtml .='<tr><td colspan="7">&nbsp;</td></tr><tr valign="top"><td colspan="2"><b>Option : </b>'.$order['zipper'].' '.$order['valve'].' '.$order['spout'].' '.$order['accessorie'].'<br><b>Dimension (Size) : </b>'.$order['width'].'X'.$order['height'].'X'.$order['gusset'].' ('.$order['volume'].')<br><b>Color : </b>'.$order['color'].'<br><b>Order Date :</b>'.$order['date_added'].'</td><td colspan="2"><b>Transportation : </b>'.$order['transportation_type'].'<br><b>Shipment Country : </b>'.$order['country_name'].' / '; 
                                                    if($order['ship_type'] == 0) $setHtml .='Self'; else  $setHtml .='Client';
                                                     $setHtml .='<br><b>Address :  </b><br><pre style="display: inline;padding:0px;margin: 0 0 0px; 
																font-size: 13px;line-height: 0;word-break:normal;word-wrap:normal;
																background-color:transparent;border: 0px solid #ccc;
																border-radius: 0px; ">'.$order['address'].'</pre>';
												if($status==3)
												{
														$setHtml .='<br><b>Track Id : '.$order['track_id'].'</b><br><b>Dispatched Date : </b>'.$order['date'].'
						<br><b>Courier : </b>'.$this->getCourier($order['courier_id']);
												}
                                              $setHtml .='</td>
                                                   <td><b>Quantity </b><br />'.$order['quantity'].'</td><td><b>Price Per Unit</b><br />'.$order['currency_code'].' '.$order['price'].'</td><td><b>Total Price </b><br />'.$order['currency_code'].' '.$order['price']*$order['quantity'].'</td></tr>';                   
    
                                            $setHtml .='<tr><td colspan="7"><b>Template Title : </b>'.$order['title'].'</td></tr>';                                      
												    if($order['note'] != '')
                                                    {
                                                   
                                                   $setHtml .='<tr><td colspan="7"><b>Note : </b>'.$order['note'].'</td></tr>';  
                                               } 
											    if($order['review'] != '')
                                                {
                                                   $setHtml .='<tr><td colspan="7"><b>Review : </b>'.$order['review'].'</td></tr>';  
                                               	} 
												  
                                                                 
    										 $setHtml .='<tr><td colspan="7"> &nbsp;</td></tr></table></div>'; 
										$toEmail[$postedByData['user_name']] = $postedByData['email'];
										if(isset($adminpostedByData) && $adminpostedByData!='')
										$toEmail[$adminpostedByData['user_name']] =$adminpostedByData['email'];
										
							if($status > 0)
							$subject = $subject.' STK_'.$order['template_order_id'].' '.$order['product_name']; 
											
									}
									
									$setHtml .= '</div>';
			//							printr($toEmail);
		//die;
								//	printr($setHtml);
								//die;	
									$obj_email = new email_template();
									$rws_email_template = $obj_email->get_email_template(4); 
									//$fromEmail = $postedByData['email'];
									$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
			$path = HTTP_SERVER."template/order_template.html";
			$output = file_get_contents($path);  
			//printr($toEmail);die;
			$search  = array('{tag:header}','{tag:details}');
			
			$message = '';
			$customerMessage = '';
			$AdmincustomerMessage = '';
			$signature = 'Thanks.';
			if($postedByData['email_signature']){
				$signature = nl2br($postedByData['email_signature']);
			}
			if($setHtml){
				$tag_val = array(
					"{{productDetail}}" =>$setHtml,
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
			$qstr = '';
			foreach($toEmail as $toemail)
			{
				send_email($toemail,$adminEmail,$subject,$message,'');
			}
	}*/
	
	public function getdeatiluser($product_template_order_id,$template_order_id)
	{
		$temp_id ='';
		if($template_order_id != '')
		{
			$temp_id = "AND t.template_order_id='".$template_order_id."'";
		}
		$sql="SELECT st.admin_user_id,t.user_id,t.user_type_id FROM template_order as t,stock_order as st WHERE t.product_template_order_id='".$product_template_order_id."'  $temp_id AND t.status=1 ANd t.stock_order_id=st.stock_order_id";
		//echo $sql;
	$data=$this->query($sql);
	//printr($data);
	return $data->row;
	}
	
	public function sendOrderEmail($post='',$status,$adminEmail,$check=0)
	{
		
		//printr($post);//die;
		$id=array();
		foreach($post as $val)
		{
			$arr = explode("==",$val);
			if(count($arr)=='2')
				$id[]=array('custom_order_id'=>$arr[0],'multi_custom_order_id'=>$arr[1]);
			else
				$id[]=array('template_order_id'=>$arr[0],'product_template_order_id'=>$arr[1],'client_id'=>$arr[2]);
				
			$k=$id[0];
		}//printr($id);die;
		$data = $this->query("SELECT group_id FROM stock_order_email_history_id ORDER BY group_id DESC LIMIT 1");
		if($data->num_rows>0)
		{
			$group_id = $data->row['group_id']+1;
		}
		else
			$group_id=1;
			$con='';
		foreach($id as $order_id)
		{
			$decline_html='';
			$html_ddate='';
			
			$menu_id =0;
			$template_order_id ='';
			if(!isset($order_id['custom_order_id']))
			{
				$con = '  t.template_order_id = '.$order_id['template_order_id'].' AND';
				$template_order_id=$order_id['template_order_id'];
			}
			else
			{
				$con='';
			}
			if($status>0)
			{
				$dis_status =$dis_cond =$dis_table=$dis_select='';
				
				if($status == '3')
				{
					//$dis_cond = 'OR ()';OR t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id
					$dis_status = 'OR sos.status= 1 ';
				}
				if(isset($order_id['custom_order_id']))
					$cond='';
				else
					$cond = 'AND t.status = 1 AND (sos.status='.$status.' '.$dis_status.')  AND  t.client_id ='.$order_id['client_id'].'';
					
				if($status==1)
				{
					//accept order
					$bg_color_code='#FFF0BA';
					$color_code='#FFDC5C';
					$span_color ='#FFC800';
					$span_class ='label bg-warning';
					$subject = 'Accepted Orders';
					$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$order_id['template_order_id']);
					$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
					$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
					$toEmail[$datauser['user_name']]=$datauser['email'];
					$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];
					$final_ddate = $this->getFinalddate($order_id['product_template_order_id'],$order_id['template_order_id']);
					///printr($final_ddate);die;
					$new_date = $final_ddate['new_final_ddate'];
					
					$menu_id = array('79','80');
						
					$html_ddate = $new_date;
					
					
				}
				if($status==2)
				{
					//decline order
					if(isset($order_id['custom_order_id']))
					{ 
						$user_cust_detail=$this->getdeatilCustuser($order_id['multi_custom_order_id']);
						$user_detail['user_id'] = $user_cust_detail['added_by_user_id'];
						$user_detail['user_type_id']=$user_cust_detail['added_by_user_type_id'];
						$user_detail['admin_user_id']=$user_cust_detail['admin_user_id'];
						$dec_qty_cust = $this->getCustomAcceptedRecords('2',$order_id['custom_order_id']);
						$dec_qty['decline_qty'] = $dec_qty_cust[0]['quantity'];
					}
					else
					{
						$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$template_order_id);
						$dec_qty=$this->declineQty($order_id['template_order_id'],$order_id['product_template_order_id']);
					}
					//$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$order_id['template_order_id']);
					$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
					$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
					$toEmail[$datauser['user_name']]=$datauser['email'];
					$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];
					$menu_id = array('79');	
						
					$decline_html = '<br><span>Your '.$dec_qty['decline_qty'].' Qty is Rejected</span>';
				}
				if($status==3)
				{
					//dispatch order
					//$dis_cond = 'OR (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id)';
					//$dis_table = ', stock_order_dispatch_history as sodh';	
					//$dis_select = 'sodh.dis_qty,max(sodh.stock_order_dispatch_history_id),';
					$bg_color_code='#D2FFE5';
					$color_code='#8CED9C';
					$span_color ='#D2FFE5';
					$span_class ='label bg-success';
					$subject = 'Dispatched Orders';
					$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$order_id['template_order_id']);
					$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
					$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
					$toEmail[$datauser['user_name']]=$datauser['email'];
					$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];
					$menu_id = array('79','80');
				}	
			}
			elseif($status==0)
			{
				$cond = 'AND t.status = 1 AND sos.status='.$status.' AND pto.order_id='.$order_id['product_template_order_id'].' AND  t.client_id ='.$order_id['client_id'].'';
				//echo $order_id['template_order_id'];
				$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$order_id['template_order_id']);
				
				//printr($user_detail);die;
				$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
				$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
				$toEmail[$datauser['user_name']]=$datauser['email'];
				$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];
				$menu_id =array('79');			                       
			}
			$permissionData = '';
			if($menu_id >0)
				$permissionData = $this->getUserPermission($menu_id);
				
				// sonu  added email for ankitsir on 20-4-2017  				
				//offline_id = 71 online id = 96
				$remove_email_ankit_sir = $this->getUser(96,2);
				//printr($remove_email_ankit_sir);
				
			//[kinjal] Edited on 9-5-2017	
			if(!empty($permissionData))
			{
				foreach($permissionData as $email_id)
				{
					//printr($email_id);
				        $remove_email_ankit_sir_a[$remove_email_ankit_sir['user_name']] = $remove_email_ankit_sir['email'];
						
						if(!in_array($email_id['email'],$remove_email_ankit_sir_a))
						{
							$toEmail[$email_id['user_name']] = $email_id['email'];	
						}
				}
			}
			$setHtml = '';
			$sub = '';
			$insert_qry = '';
			$setHtml .= '<div class="table-responsive">';
				
				$custom_order_id='';
				if(isset($order_id['custom_order_id']))
					$custom_order_id = $order_id['custom_order_id'];
					
				if(isset($order_id['custom_order_id']))
					$orders = $this->getCustomAcceptedRecords('2',$custom_order_id);
				else
					$orders = $this->GetOrderList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$cond,$status,$con);
					
			//$orders = $this->GetOrderList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$cond,$status,$con);
			//printr($orders);die;
			foreach($orders as $data)
			{
				$new_data[$data['gen_order_id']][]=$data;
				
			}	
			//printr($new_data);
			ksort($new_data);
			$f = 1;
			$total=0;$total_qty=0;
			$toEmail['swisspac'] = $adminEmail;
			$order_type='';
			foreach($new_data as $gen_order_id=>$data)
			{  
				///echo $status;
				//if($status=='1')
				//{
					//$setHtml .="<div><b>Order No : ".$gen_order_id.'</b><br><br>Your Final Delivery Date is '.dateFormat(4,$html_ddate);
				//}
				//else
				//{
					$setHtml .="<div><b>Order No : ".$gen_order_id.'</b>';
				//}
				
				$sub .= $gen_order_id.' , ';
				/*if($status=='1')
				{
					$setHtml .="<span>Your Final Delivery Date is </span><br><br>";
				}*/
				foreach($data as $order)
				{	
					if($order['order_type']!='')
						$order_type=$order['order_type'];
						
					$insert_qry .= "('".$gen_order_id."','".$order['template_order_id']."','".$order['product_template_order_id']."','".$_SESSION['ADMIN_LOGIN_SWISS']."','".$_SESSION['LOGIN_USER_TYPE']."',NOW(),'".$group_id."','".$order['client_id']."','".$check."') , ";
					
					$setHtml .='<br><br>Your Reference : '.$order['note'].'<br>';
					$setHtml .='<br><br>'.$order['quantity'].'&nbsp;&nbsp; X &nbsp;&nbsp;'.$order['volume'].'&nbsp;';
					
					if(!isset($order['custom_order_id']))
						$setHtml .='<span><b>'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_color']).' </b></span><span>';
					
					if(isset($order['product_id']) && $order['product_id']!=3)
						$setHtml .='<b>';
					if(!isset($order['custom_order_id']))
						 $setHtml .=preg_replace("/\([^)]+\)/","",preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_product']));
					else
						$setHtml .=preg_replace("/\([^)]+\)/","",preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['product_name']));
					
					 if(isset($order['product_id']) && $order['product_id']!=3)
						$setHtml .='</b>';
					 $setHtml .='</span>';
					  $setHtml .=$decline_html;
					$accessorie_txt_corner='';
					if(isset($order['accessorie_txt_corner']))
					    $accessorie_txt_corner = $order['accessorie_txt_corner'];
					$setHtml .='<br>Option : <span style="color:#FF0000;"> '.$order['zipper'].'</span>  <span style="color:#060;">'.$order['valve'].'</span> <span style="color:#FF6600;">'.$order['spout'].'</span> <span style="color:#0000FF;">'.$order['accessorie'].'</span><span style="color:#060;">'.$accessorie_txt_corner.'</span> ';
					$setHtml .='<br>';
					$postedByData = $this->getUser($order['user_id'],$order['user_type_id']);
				}
				if($order['address']!='')
				{
					$name='Customer&prime;s Address Below ';
					$address=$order['address'];
					$color='red';
				}
				else
				{	
					$name='Below Address';
					$address=$postedByData['address'].'<br>'.$postedByData['city'].' , '.$postedByData['state'].' ( '.$postedByData['country_name'].' )<br>'.$postedByData['postcode'].'<br>'.$postedByData['email'];
					$color='black';
				}
				if($status!=2)
				{
					$setHtml.='<br><br><b><span style="color:'.$color.'">Dispatch Directly To '.$name.'   <span style="color:blue">'.$order['transportation_type'].'</span> :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>'.$address.'</b></pre><br><br>';
				}
				if($order['review']!='' && $status==2)
					$setHtml.='<br><br><b><span style="color:red">Review :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>'.$order['review'].'</b></pre><br><br>';
				//else
				///	$setHtml.='<br><br><span style="color:red"><b>Dispatch Directly To '.$postedByData['first_name'].' '.$postedByData['last_name'].' Address Below :-</b></span><br><br><b><address>'.$postedByData['address'].'<br>'.$postedByData['city'].' , '.$postedByData['state'].' ( '.$postedByData['country_name'].' )<br>'.$postedByData['postcode'].'<br>'.$postedByData['email'].'</address></b>';
				//	$setHtml.='<br><br><b>Dispatch: ';
				//$setHtml.=$order['transportation_type'].'</b><br><br>';
				$setHtml.='</div>';
			}
				$setHtml.='<br>';
				$toEmail[$postedByData['user_name']] = $postedByData['email'];
				if(isset($adminpostedByData) && $adminpostedByData!='')
					$toEmail[$adminpostedByData['user_name']] =$adminpostedByData['email'];
				$sub=substr($sub,0,-2);
				if($status > 0)
				{
					if($status==2)
						$subject = 'YOUR REJECTED '.strtoupper($order_type).' OREDR NO: '.$sub.' Submited By '.$datauser['user_name'];    
					elseif($status==1)
						$subject = 'YOUR ACCEPTED '.strtoupper($order_type).' ORDER NO : '.$sub;
					elseif($status==3)
						$subject = 'YOUR DISPATCHED '.strtoupper($order_type).' ORDER NO : '.$sub;
				}
				else
					$subject = 'NEW '.strtoupper($order_type).' ORDER : '.$sub.' Submited By '.$datauser['user_name'];    
				$insert_qry=substr($insert_qry,0,-2);
		}
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(4); 
		//$fromEmail = $postedByData['email'];
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		$path = HTTP_SERVER."template/order_template.html";
		$output = file_get_contents($path);  
		//printr($postedByData);//die;
		$search  = array('{tag:header}','{tag:details}');
		
		$message = '';
		$signature='';
		if($postedByData['email_signature']){
			$signature = nl2br($postedByData['email_signature']);
		}
		//printr($setHtml);
		//die;
		if($setHtml){
			$tag_val = array(
				"{{productDetail}}" =>$setHtml,
				"{{ddsignature}}"	=> $signature,
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
		$qstr = '';
		//printr($message);
		//die;
		foreach($toEmail as $toemail)
		{
			//printr($toemail.'====='.$adminEmail);
			send_email($toemail,$adminEmail,$subject,$message,'');//uncomment this line
			
			
			//send_email('tech@swisspack.co.in','tech@swisspack.co.in',$subject,$message,'');
			
		}
	//	die;
		//send_email('tech@swisspack.co.in','tech@swisspack.co.in',$subject,$message,'');
		//if($status>0)
		//{
			$sql = "INSERT INTO stock_order_email_history_id (stock_order_id,template_order_id,product_template_order_id,user_id,user_type_id,date,group_id,client_id,email_id) VALUES ".$insert_qry."";
		$data=$this->query($sql);
		//}	
		$customerMessage = '';
		$AdmincustomerMessage = '';
		$signature = 'Thanks.';
	
	}
	public function getmultiplecountry($country)
	{
		$sql = "SELECT DISTINCT(country_name) as country_name,country_id FROM country WHERE " .$country;
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
	public function gettemplatetitle($user_id,$user_type,$product_id='',$country_id='',$transport='',$zippervalve='')
	{
		$zv ='';
		if($product_id !='' && $country_id =='')
		{
			$cond = 'AND t1.product_name = '.$product_id;
			$groupby = '';
		}
		if($product_id != '' && $country_id !='')
		{
			$cond = 'AND t1.product_name = '.$product_id;
			$groupby = 'GROUP BY t1.transportation_type';
		}
		if($product_id != '' && $country_id !='' && $transport !='' && $zippervalve == 'valve')
		{
			$cond = 'AND t1.product_name = '.$product_id.'  AND t1.transportation_type = "'.$transport.'"';
			$groupby = 'GROUP BY pts.valve';	
			$zv = ',pts.valve';		
		}
		if($product_id != '' && $country_id !='' && $transport !='' && $zippervalve == 'zipper')
		{
			$cond = 'AND t1.product_name = '.$product_id.'  AND t1.transportation_type = "'.$transport.'"';
			$groupby = 'GROUP BY pts.zipper';
			$zv = ',pts.zipper';				
		}
		if($product_id == '' && $country_id =='' && $transport =='')
		{
			$cond = '';
			$groupby = 'GROUP BY t1.product_name';
		}
		if($user_type==4)
		{
			$sql = "SELECT t1.title,t1.country,t1.product_template_id,t1.transportation_type,p.product_name,p.product_id ".$zv." FROM product_template as t1,product as p,product_template_size as pts where t1.product_template_id = pts.template_id AND p.product_id = t1.product_name and user=".$user_id." ".$cond." 
			".$groupby." ";
		}
		elseif($user_type == 2)
		{
			$sql1 = "SELECT * FROM employee WHERE employee_id = '".$user_id."'";
			$data = $this->query($sql1);
			$sql = "SELECT t1.title,t1.country,t1.product_template_id,t1.transportation_type,p.product_name,p.product_id ".$zv." FROM product_template as t1,product as p,product_template_size as pts WHERE t1.product_template_id = pts.template_id AND p.product_id = t1.product_name and  t1.user=".$data->row['user_id']." 
			".$cond." ".$groupby." ";
		}
		//echo $sql;//die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;			 
		}else{
			return false;
		}	
	}
	
	public function checkNewCartPermission($user_id,$user_type_id)
	{
		$sql = "SELECT order_s_no,status FROM template_order WHERE status = 1 AND date_added =  DATE_FORMAT(NOW(),'%Y-%m-%d')  AND end_date >               DATE_FORMAT(NOW(),'%Y-%m-%d') AND user_id=".$user_id." AND user_type_id=".$user_type_id." ORDER BY template_order_id DESC LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;			 
		}else{
			return false;
		}
	}

	public function orderLimit($userid,$user_type_id)
	{
		if($user_type_id==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$userid."'";
			$dataadmin = $this->query($sqladmin);
			$userid =  $dataadmin->row['user_id'];
		}
		$sql = "SELECT order_limit FROM international_branch WHERE international_branch_id= ".$userid." ORDER BY international_branch_id DESC LIMIT 1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['order_limit'];			 
		}else{
			return false;
		}
	}

	public function getColor($color_id)
	{
		$data = $this->query("SELECT pouch_color_id, color FROM " . DB_PREFIX . "pouch_color WHERE pouch_color_id='".$color_id."' AND status = '1' AND        is_delete = '0' ORDER BY color ASC LIMIT 1");		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}

	public function getaddProductDetails($product_id,$country_id,$transport,$valve,$zipper,$color,$volume)
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
		$color_id='"'.$color.'"';
		
		//[kinjal] : updated query on 22-12-2016 for get only active template
		$sql = "SELECT pts.*,p.product_name,p.product_id,pt.user,pt.country,pt.product_template_id FROM " . DB_PREFIX . "product_template_size pts,product p,product_template as
pt  WHERE  pt.product_name='".$product_id."' AND  pt.country LIKE '%".$countryid."%' AND  pt.transportation_type = '".$transport."' AND pt.user = '".$user_id."' AND pts.template_id=pt.product_template_id AND pt.product_name=p.product_id AND pts.valve='".$valve."' AND pts.zipper='".$zipper."' AND pts.is_delete = '0' AND pt.status='0' AND REPLACE(pts.volume, ' ', '') = REPLACE('".$volume."', ' ', '') AND pts.color LIKE '%".$color_id."%' ";	
	//	echo $sql;
		 //die;
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;
	}
	
	public function getTempalte($template_id)
	{
		$Sql = "SELECT pt.*,p.product_name,c.country_name,ib.first_name,ib.last_name,pts.*,cu.currency_code FROM " .DB_PREFIX . "product_template pt,product        p,country c,international_branch ib,product_template_size pts,currency as cu where pt.product_name = p.product_id and c.country_id = pt.country and ib.international_branch_id = pt.user and pts.template_id = pt.product_template_id and pt.product_template_id = '".$template_id."' 
		and pt.currency=cu.currency_id and pt.is_delete = '0' ";		
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
		
	public function getShipmentCountryName($userid)
	{
		if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$userid."'";
			//echo "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$userid."'";
			$dataadmin = $this->query($sqladmin);
			$userid =  $dataadmin->row['user_id'];
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$userid = $userid;
		}
		//echo $userid;
		$sql = "SELECT pt.user,c.country_name,c.country_id FROM " . DB_PREFIX . "product_template as pt,country as c  WHERE 
		pt.country=c.country_id AND pt.user = '".$userid."' and pt.is_delete = '0' GROUP BY c.country_id ";	
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;	
	}
	
	public function removeTemplateProduct($template_size_id)
	{
		$this->query("DELETE FROM " . DB_PREFIX ."product_template_size WHERE product_template_size_id='".$template_size_id."' ");
	}
	
	public function removeTemplateOrder($template_order_id)
	{
		if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			$dataadmin = $this->query($sqladmin);
			$admin_user_id =  $dataadmin->row['user_id'];
			$user_type_id = 2;
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$admin_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$user_type_id = 4;
		}
		else
		{
			return false;	
		}
	$ib_qty = "SELECT quantity FROM ".DB_PREFIX."international_branch ib,template_quantity tq WHERE international_branch_id='".$admin_user_id."' AND ib.stock_qty_price=tq.template_quantity_id LIMIT 1";
		$data_ib_qty = $this->query($ib_qty);
		if($data_ib_qty->num_rows)
			$ib_quantity =  $data_ib_qty->row['quantity'];
			
		$sqltot = "SELECT pto.order_id,pto.total_qty,t.quantity,t.template_size_id FROM ".DB_PREFIX."template_order as t ,product_template_order as pto WHERE t.template_order_id='".$template_order_id."' AND t.product_template_order_id=pto.order_id";
		$datatot = $this->query($sqltot);
		//printr($datatot);die;
		$total_qty=0;
		$this->query("DELETE FROM " . DB_PREFIX ."template_order WHERE template_order_id='".$template_order_id."' ");
		if($datatot->num_rows)
		{
			$total_qty = $datatot->row['total_qty']-$datatot->row['quantity'];
		}	
		if($ib_quantity>0)
		{
			$total_qty=$ib_quantity;
		}	
		if( $total_qty < 2000)
		{
			$quantitycolname = 'quantity1000';
		}
		else if($total_qty >= 2000 && $total_qty < 5000)
		{
			$quantitycolname = 'quantity2000';
		}
		else if($total_qty >= 5000 && $total_qty < 10000)
		{
			$quantitycolname = 'quantity5000';
		}
		else
		{
			$quantitycolname = 'quantity10000';
		}
		$sqlqty1 = "SELECT template_size_id,template_order_id FROM ".DB_PREFIX."template_order WHERE product_template_order_id = '".$datatot->row['order_id']."'";
		$dataqty1=$this->query($sqlqty1);
		
		if($dataqty1->num_rows)
		{
			foreach($dataqty1->rows as $sizedata)
			{
				$sqlqty = "SELECT ".$quantitycolname." as quantity FROM ".DB_PREFIX."product_template_size WHERE product_template_size_id = '".$sizedata['template_size_id']."' AND is_delete = '0'";
				$dataqty=$this->query($sqlqty);
				$price = $dataqty->row['quantity'];
				if($dataqty->num_rows)
				{
					$sqlorder1 = "UPDATE ". DB_PREFIX ."template_order SET price = '".$price."' WHERE template_order_id = '".$sizedata['template_order_id']."' ";
					$dataorder1 = $this->query($sqlorder1);
				}
			}
		}
		if($datatot->num_rows)
		{
			$sqlorder = "UPDATE ". DB_PREFIX ."product_template_order SET total_qty = '".$total_qty."' WHERE order_id = '".$datatot->row['order_id']."' ";
			$dataorder = $this->query($sqlorder);			
		}
	}
	public function deleteorder($data)
	{
		//printr($data);
		
		foreach($data as $val)
		{
			//printr($val);
			$splitdata=explode('==',$val);
			$this->query("DELETE FROM " . DB_PREFIX ." template_order WHERE template_order_id='".$splitdata[0]."'");
			$this->query("DELETE FROM " . DB_PREFIX ." stock_order WHERE gen_order_id='".$splitdata[3]."' AND client_id='".$splitdata[2]."'");
			$this->query("DELETE FROM " . DB_PREFIX ." stock_order_status WHERE template_order_id='".$splitdata[0]."' AND product_template_order_id='".$splitdata[1]."'");
		}
	//die;
	
	}
	
	public function getClientName($client_name)
	{
		if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			$dataadmin = $this->query($sqladmin);
			$admin_user_id =  $dataadmin->row['user_id'];
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$admin_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		}
		$result=$this->query("SELECT client_name,client_id FROM " . DB_PREFIX ."client_details WHERE client_name LIKE '".$client_name."%' AND added_by_id='".$admin_user_id."'");
		return $result->rows;
	}
	//mansi 22-1-2016 (add buyers order no)
	public function addtocart($client_name,$template_id,$template_size_id,$color,$quantity,$shipmentcountry,$product_id,$ship_type,$userid,$address,$permission,$transport,$note,$ddate,$client_price,$cust_order_no,$buyers_order_no,$order_type='',$product_code_id='',$reference_no,$email='')
	{
		//echo $buyers_order_no;die;
		//printr($_SESSION);die;
		if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$userid."'";
			$dataadmin = $this->query($sqladmin);
			$admin_user_id =  $dataadmin->row['user_id'];
			$user_type_id = 2;
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$admin_user_id = $userid;
			$user_type_id = 4;
		}
		else
		{
			return false;	
		}
		//printr($admin_user_id);
		$ib_qty = "SELECT quantity FROM ".DB_PREFIX."international_branch ib,template_quantity tq WHERE international_branch_id='".$admin_user_id."' AND ib.stock_qty_price=tq.template_quantity_id LIMIT 1";
		//echo $ib_qty;
		$data_ib_qty = $this->query($ib_qty);
		//printr($data_ib_qty);
		if($data_ib_qty->num_rows)
			$ib_quantity =  $data_ib_qty->row['quantity'];
		$client_id='';
        $client = "SELECT client_id FROM ".DB_PREFIX."client_details WHERE client_name ='".strtolower($client_name)."' AND email='".$email."' AND added_by_id='".$admin_user_id."'  LIMIT 1";
		$dataclient = $this->query($client);
		if($dataclient->num_rows)
			$client_id =  $dataclient->row['client_id'];
		
		if($client_id=='')
		{
			$this->query("INSERT INTO ".DB_PREFIX."client_details SET client_name ='".strtolower($client_name)."' ,email='".$email."',added_by_id='".$admin_user_id."' ");
			$client_id = $this->getLastId();
		}
		
		$from_address_book = $this->getClientNameFromAddBook($email);
		
		$add_book_id = $company_address_id='';
		if(!empty($from_address_book))
		{
			$add_book_id =  $from_address_book['address_book_id'];
			$company_address_id =  $from_address_book['company_address_id'];
		}
		
		if($add_book_id=='')
		{
			//printr('in if cond'.$add_book_id);
			$this->query("INSERT INTO ".DB_PREFIX."address_book_master SET company_name ='".strtolower($client_name)."' ,user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',status='1' , date_added = NOW()");
			$add_book_id = $this->getLastId();
			
			//echo $add_book_id;
			$this->query("INSERT INTO ".DB_PREFIX."company_address SET 	email_1='".$email."',address_book_id ='".$add_book_id."' ,country='".$shipmentcountry."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', user_type_id='".$_SESSION['LOGIN_USER_TYPE']."', date_added = NOW()");
		}
		
		//added by p
		/*$emp_t_id = $_SESSION['LOGIN_USER_TYPE'];
								$emp_t_id = (int)$emp_t_id;
						
								$emp_u_id = $_SESSION['ADMIN_LOGIN_SWISS'];
								$emp_u_id = (int)$emp_u_id;
								
								$qry1 = "SELECT user_id as u_id,user_type_id as t_id FROM `" . DB_PREFIX . "employee` WHERE employee_id='".$emp_u_id."' limit 1";
								$rslt = $this->query($qry1);
								$admin_id= $rslt->row['u_id'];
								$admin_type_id= $rslt->row['t_id'];
								
								//$cliet_name=$client_name;
								
								$qry = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "customer_address` WHERE ((user_id='".$admin_id."' and type_id='".$admin_type_id."') or (user_id='".$emp_u_id."' and type_id='".$emp_t_id."')) and customer_name='".strtolower($cliet_name)."'";
		$result = $this->query($qry);
		$check=$result->row['total'];
		
		if($check<=0){
				$qry1 = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "customer_address` WHERE ((emp_id='".$admin_id."' and emp_type_id='".$admin_type_id."') or (emp_id='".$emp_u_id."' and emp_type_id='".$emp_t_id."')) and customer_name='".strtolower($cliet_name)."'";
			$result1 = $this->query($qry1);
			$check1=$result1->row['total'];
								
								if($check1<=0){
										$query =  "INSERT INTO ".DB_PREFIX."customer_address SET customer_name = '".strtolower($client_name)."',address='".$address."',user_id='".$admin_id."',type_id='".$admin_type_id."',emp_id='".$emp_u_id."',emp_type_id='".$emp_t_id."',page_name='stock_order'";
								$this->query($query);
								}
								
							}	*/
							//end p
		
		
		
		$client_cond='';
		if($ship_type==1)
			$client_cond=' AND t.client_id='.$client_id;
		//echo $admin_user_id;
		//$sqltot = "SELECT pto.order_id,pto.total_qty FROM ".DB_PREFIX."product_template_order pto, template_order as t,stock_order_status sos WHERE pto.shipment_country = '".$shipmentcountry."' AND pto.product_id = '".$product_id."' AND pto.admin_user_id = '".$admin_user_id."' AND pto.order_id=t.product_template_order_id AND sos.product_template_order_id=pto.order_id AND sos.status!=1";
		$sqltot = "SELECT pto.order_id,pto.total_qty,t.template_size_id,t.template_order_id FROM ".DB_PREFIX."product_template_order pto, template_order as t,stock_order_status sos WHERE pto.shipment_country = '".$shipmentcountry."' AND pto.ship_type = '".$ship_type."' AND pto.admin_user_id = '".$admin_user_id."' AND pto.order_id=t.product_template_order_id AND sos.product_template_order_id=pto.order_id AND sos.status!=1 AND t.transport='".$transport."' ".$client_cond." GROUP BY t.template_order_id";
		$datatot = $this->query($sqltot);
		//echo $sqltot;//die;
		//printr($datatot);
		if($datatot->num_rows)
		{
			$total_qty = $datatot->row['total_qty']+$quantity;
		}
		else
		{
			$total_qty = $quantity;
		}
		if($ib_quantity>0)
		{
			$total_qty=$ib_quantity;
		}
		if($total_qty < 2000)
		{
			$quantitycolname = 'quantity1000';
		}
		else if($total_qty >= 2000 && $total_qty < 5000)
		{
			$quantitycolname = 'quantity2000';
		}
		else if($total_qty >= 5000 && $total_qty < 10000)
		{
			$quantitycolname = 'quantity5000';
		}
		else
		{
			$quantitycolname = 'quantity10000';
		}
		//printr($total_qty.'====='.$quantitycolname);
		foreach($datatot->rows as $size_data)
		{
			$sqlqty1 = "SELECT ".$quantitycolname." as quantity FROM ".DB_PREFIX."product_template_size WHERE product_template_size_id = '".$size_data['template_size_id']."' AND is_delete = '0'";
			$dataqty1=$this->query($sqlqty1);
			//printr($sqlqty1);
			$price1 = $dataqty1->row['quantity'];
			if($dataqty1->num_rows)
			{
				$sqlorder1 = "UPDATE ". DB_PREFIX ."template_order SET price = '".$price1."' WHERE template_order_id = '".$size_data['template_order_id']."'";
				$dataorder1 = $this->query($sqlorder1);			
			}
		}
		$sqlqty = "SELECT ".$quantitycolname." as quantity FROM ".DB_PREFIX."product_template_size WHERE product_template_size_id = '".$template_size_id."' AND is_delete = '0'";
		$dataqty=$this->query($sqlqty);
		$price = $dataqty->row['quantity'];
		//echo $sqlqty;die;
	//	$sql = "SELECT order_id FROM ".DB_PREFIX."product_template_order WHERE shipment_country = '".$shipmentcountry."' AND pto.ship_type = '".$ship_type."' AND product_id = '".$product_id."'  AND admin_user_id = '".$admin_user_id."'";
		$sql = "SELECT order_id FROM ".DB_PREFIX."product_template_order WHERE shipment_country = '".$shipmentcountry."' AND ship_type = '".$ship_type."'  AND admin_user_id = '".$admin_user_id."'";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			$order_id = $data->row['order_id'];
		//	$sqlorder = "UPDATE ". DB_PREFIX ."product_template_order SET price = '".$price."',total_qty = '".$total_qty."' WHERE 
			$sqlorder = "UPDATE ". DB_PREFIX ."product_template_order SET total_qty = '".$total_qty."' WHERE order_id = '".$order_id."'";
			$dataorder = $this->query($sqlorder);			
		}
		else
		{
			$sqlnew = "INSERT INTO ". DB_PREFIX ."product_template_order SET order_title = NOW(),shipment_country = '".$shipmentcountry."', ship_type = '".$ship_type."',	product_id = '".$product_id."' ,total_qty='".$quantity."', admin_user_id = '".$admin_user_id."'";	
			$datanew = $this->query($sqlnew);
			$order_id = $this->getLastId();						
		}
	//commented by jaya on 17-8-2015
		$st_sql = $this->query("SELECT stock_order_id FROM ".DB_PREFIX."stock_order WHERE client_id = '".$client_id."' AND status = '0'");
		//echo "SELECT stock_order_id FROM ".DB_PREFIX."stock_order WHERE client_id = '".$client_id."' AND status = '0'";
		if(!$st_sql->num_rows)
		{
			$sqlgenid = "INSERT INTO ". DB_PREFIX ."stock_order SET client_id = '".$client_id."',admin_user_id = '".$admin_user_id."', address_book_id = '".$add_book_id."' ";	
			//echo $sqlgenid;
			$datagenid = $this->query($sqlgenid);
			//commented by jaya on 17-8-2015
			$stock_order_id = $this->getLastId();
			$this->query("UPDATE ". DB_PREFIX ."stock_order SET gen_order_id = 'STK-".$stock_order_id."' WHERE stock_order_id = '".$stock_order_id."'");					
		}
		else
		{
			$stock_order_id=$st_sql->row['stock_order_id'];
		}
			
		$sqlflush = "SELECT order_flush_date FROM ".DB_PREFIX."international_branch WHERE international_branch_id = '".$admin_user_id."' AND is_delete = '0'";
		$dataflush=$this->query($sqlflush);
		$days = $dataflush->row['order_flush_date'];
		//echo $days;
		/*if($ship_type == 0)
		{
			$sqladdress = "SELECT address FROM ".DB_PREFIX."address WHERE user_id = '".$userid."' AND user_type_id=".$_SESSION['LOGIN_USER_TYPE']."";
			$dataaddress=$this->query($sqladdress);
			//$shipmentcountry = $dataaddress->row['country_id'];
			$address = $dataaddress->row['address'];
		}*/
		
		 if (isset($reference_no) && !empty($reference_no)) {
           $reference_number = $reference_no;
        } else {
           $reference_number = '0';
        }
            
		//mansi 22-1-2016 (add buyers order no)
		$sqlorder = "INSERT INTO ".DB_PREFIX."template_order SET client_id='".$client_id."',product_template_order_id ='".$order_id."',template_id='".$template_id."',quantity='".$quantity."',note='".$note."',expected_ddate='".$ddate."',price='".$price."',price_uk='".$client_price."',country='".$shipmentcountry."',transport='".$transport."',ship_type = '".$ship_type."',user_id = '".$userid."',user_type_id='".$user_type_id."',product_id='".$product_id."',status='0',template_size_id = '".$template_size_id."',address = '".$address."',color = '".$color."',stock_order_id='".$stock_order_id."',customer_order_no='".$cust_order_no."',buyers_order_no='".$buyers_order_no."',order_s_no='".$permission."',order_type='".$order_type."',reference_no = '".$reference_number."',date_added=NOW(),end_date=NOW()+INTERVAL ".$days." DAY";
		
		$dataorder = $this->query($sqlorder);
		$template_order_id = $this->getLastId();
		$sqlorderStatus = "INSERT INTO ".DB_PREFIX."stock_order_status SET product_template_order_id ='".$order_id."',template_order_id='".$template_order_id."',status =0";
		$dataorder = $this->query($sqlorderStatus);	
	}
	
	public function getUser($user_id,$user_type_id)
	{
		$cond = '';
		if($user_type_id==2)
		{
			$sql = "SELECT ib.stock_order_price,ib.user_name,co.currency_code,co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email,ib.user_type_id,ib.user_id FROM " . DB_PREFIX ."employee ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '".(int)$user_type_id."' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ".$cond."";
		}
		elseif($user_type_id == 4)
		{
			$sql = "SELECT ib.stock_order_price,ib.user_name,co.currency_code, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '".(int)$user_type_id."' AND ad.user_id = '".(int)$user_id."' ) LEFT  JOIN  " . DB_PREFIX ." country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ".$cond." ";
		}
		elseif($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id,co.currency_code, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}
		elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id,co.currency_code, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}
		else
		{
			return false;
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getclientpricepermission($user_id,$user_type_id)
	{
		if($user_type_id='4')
		{
			$sql="SELECT stock_price_compulsory FROM international_branch WHERE international_branch_id='".$user_id."'";
		}
		elseif($user_type_id='2')
		{
			$sql="SELECT stock_price_compulsory FROM employee WHERE employee_id='".$user_id."'";
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
/*	public function getUserPermission($menu_id)
	{
		$employee_ids=$this->getemployeeId($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$menu = implode('|',$menu_id);
		
		foreach($employee_ids as $k => $ids)
		{
			$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE user_type_id='2' AND user_id='".$ids['employee_id']."' AND add_permission REGEXP '".$menu."' AND edit_permission REGEXP '".$menu."' AND delete_permission REGEXP '".$menu."' AND view_permission REGEXP '".$menu."'";
			$data = $this->query($sql);
			foreach($data->rows as $empData){
			$emp_val[$k]=array('email'=>$empData['email'],
			'user_name'=>$empData['user_name']);
			}
		}
		return $emp_val;
	}*/
	public function getUserPermission($menu_id)
	{
		$menu = implode('|',$menu_id);
		
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE add_permission REGEXP '".$menu."' AND edit_permission REGEXP '".$menu."' AND delete_permission REGEXP '".$menu."' AND view_permission REGEXP '".$menu."'";
		//echo $sql;
		$data = $this->query($sql);
		return $data->rows;
	}
	public function getMenuPermission($menu_id,$user_id,$user_type_id)
	{
		$cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$cond." AND user_type_id = '".$user_type_id."' 
		AND user_id ='".$user_id."'";
		$data = $this->query($sql);
		return $data->rows;
	}
		
	public function updatePrice($data,$edited_by)
	{	
	if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			$dataadmin = $this->query($sqladmin);
			$admin_user_id =  $dataadmin->row['user_id'];
			$user_type_id = 2;
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4)
		{
			$admin_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$user_type_id = 4;
		}
		else
		{
			return false;	
		}
	$ib_qty = "SELECT quantity FROM ".DB_PREFIX."international_branch ib,template_quantity tq WHERE international_branch_id='".$admin_user_id."' AND ib.stock_qty_price=tq.template_quantity_id LIMIT 1";
		$data_ib_qty = $this->query($ib_qty);
		if($data_ib_qty->num_rows)
			$ib_quantity =  $data_ib_qty->row['quantity'];
			
		$sqltot = "SELECT pto.order_id,pto.total_qty,pto.price,t.quantity,t.template_size_id FROM ".DB_PREFIX."template_order as t ,product_template_order as pto WHERE t.template_order_id='".$data['template_order_id']."' AND t.product_template_order_id=pto.order_id";
		$datatot = $this->query($sqltot);
		if($edited_by == 0)
		{
			if($datatot->num_rows)
			{
				$total_qty=0;
				if($datatot->num_rows)
				{
					$total_qty = $datatot->row['total_qty']-$datatot->row['quantity'];
				}	
				
				if($ib_quantity>0)
				{
					$total_qty=$ib_quantity;
				}	
				if( $total_qty < 2000)
				{
					$quantitycolname = 'quantity1000';
				}
				else if($total_qty >= 2000 && $total_qty < 5000)
				{
					$quantitycolname = 'quantity2000';
				}
				else if($total_qty >= 5000 && $total_qty < 10000)
				{
					$quantitycolname = 'quantity5000';
				}
				else
				{
					$quantitycolname = 'quantity10000';
				}
				$sqlqty1 = "SELECT template_size_id,template_order_id FROM ".DB_PREFIX."template_order WHERE product_template_order_id = '".$datatot->row['order_id']."'";
		$dataqty1=$this->query($sqlqty1);
		
		if($dataqty1->num_rows)
		{
			foreach($dataqty1->rows as $sizedata)
			{
				$sqlqty = "SELECT ".$quantitycolname." as quantity FROM ".DB_PREFIX."product_template_size WHERE product_template_size_id = '".$sizedata['template_size_id']."' AND is_delete = '0'";
				$dataqty=$this->query($sqlqty);
				$price = $dataqty->row['quantity'];
				if($dataqty->num_rows)
				{
					$sqlorder1 = "UPDATE ". DB_PREFIX ."template_order SET price = '".$price."' WHERE template_order_id = '".$sizedata['template_order_id']."' ";
					$dataorder1 = $this->query($sqlorder1);
				}
			}
		}
		if($datatot->num_rows)
		{
			$sqlorder = "UPDATE ". DB_PREFIX ."product_template_order SET total_qty = '".$total_qty."' WHERE order_id = '".$datatot->row['order_id']."' ";
			$dataorder = $this->query($sqlorder);			
		}
		
				$sqlqty = "SELECT ".$quantitycolname." as quantity FROM ".DB_PREFIX."product_template_size WHERE product_template_size_id = '".$datatot->row['template_size_id']."' AND is_delete = '0'";
				$dataqty=$this->query($sqlqty);
				$price = $dataqty->row['quantity'];
				$sqlorderHistory =$this->query("INSERT INTO ". DB_PREFIX ."stock_order_price_history SET  product_template_order_id = '".$data['product_template_order_id']."',user_id = '".$data['user_id']."' , user_type_id = '".$data['user_type_id']."' , template_order_id ='".$data['template_order_id']."' , date_added = NOW() ,old_price = ".$datatot->row['price']." , old_qty =".$datatot->row['total_qty'].",new_price = '".$price."',new_qty = '".$total_qty."' , status = ''");
			}
		}
		else
		{
			$sqlorderHistory =$this->query("INSERT INTO ". DB_PREFIX ."stock_order_price_history SET  product_template_order_id = '".$data['product_template_order_id']."',user_id = '".$data['user_id']."' , user_type_id = '".$data['user_type_id']."' , template_order_id ='".$data['template_order_id']."' , date_added = NOW() ,old_price = ".$datatot->row['price'].",new_price = '".$data['price']."' , edited_by='1' , status = '".$data['status']."'");
		}
		return $sqlorderHistory;
	}
	
	/*public function updatestockorderstatus_old($value,$cond)
	{	
		
			$data = $this->query("UPDATE " . DB_PREFIX . "stock_order_status SET ".$value." WHERE ".$cond."");
			return $data;
	
	}*/
	
	public function updatePriceUk($price,$template_order_id)
	{	
		$data = $this->query("UPDATE " . DB_PREFIX . "template_order SET price_uk='".$price."' WHERE template_order_id='".$template_order_id."'");
		return $data;
	}
	
	public function getCourierCombo($selected=""){
		$sql = "SELECT * FROM " . DB_PREFIX . "courier WHERE status = '1' AND is_delete = '0'";
		$data = $this->query($sql);
		$html = '';
		if($data->num_rows){
			$html = '';
			$html .= '<select name="courier_id" id="courier_id" class="form-control validate[required]" style="width:70%" >';
					$html .= '<option value="">Select Courier</option>';
			foreach($data->rows as $courier){
				if($courier['courier_id'] == $selected ){
					$html .= '<option value="'.$courier['courier_id'].'" selected="selected">'.$courier['courier_name'].'</option>';
				}else{
					$html .= '<option value="'.$courier['courier_id'].'" >'.$courier['courier_name'].'</option>';
				}
			}
			$html .= '</select>';
		}
		return $html;
	}
	
	public function getCourier($courier_id)
	{
		$data = $this->query("SELECT courier_name FROM " . DB_PREFIX . "courier WHERE courier_id='".$courier_id."' ORDER BY courier_id ASC LIMIT 1");		
		if($data->num_rows){
			return $data->row['courier_name'];
		}else{
			return false;
		}
	}
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
		public function getActiveProductZippers(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getUserList(){
		$sql = "SELECT am.user_type_id,am.user_id,am.account_master_id,am.user_name,a.country_id,c.country_name FROM " . DB_PREFIX ."account_master as am,address as a,country as c WHERE am.user_type_id=a.user_type_id AND am.user_id=a.user_id AND c.country_id=a.country_id ORDER BY am.user_name ASC";
		$data = $this->query($sql);
		return $data->rows;
	}
	
	public function insertDelayHistory($ddate_value,$cond)
	{
		$sql = "INSERT INTO  ".DB_PREFIX." stock_delay_date_history SET $cond , $ddate_value";
		$this->query($sql);
	}
	public function getFinalddate($product_template_order_id,$template_order_id)
	{
		$temp_id ='';
		if($template_order_id!='')
		{	
			$temp_id = " AND template_order_id='".$template_order_id."'";
		}
		$sql = "SELECT *  FROM " . DB_PREFIX ."stock_delay_date_history WHERE product_template_order_id='".$product_template_order_id."' $temp_id ORDER BY delay_history_id DESC";
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
	
	public function getUpdatedDelayDateHistory($product_template_order_id,$template_order_id)
	{
		$sql = "SELECT *  FROM " . DB_PREFIX ."stock_delay_date_history WHERE product_template_order_id='".$product_template_order_id."' AND template_order_id='".$template_order_id."' ORDER BY delay_history_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function GetTotalCartOrderListForIndians($user_id,$usertypeid,$cond='',$status='',$filter_array,$interval)
	{
		
		//SELECT t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id FROM template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,product_template_size as pts WHERE c.country_id = t.country AND cd.client_id = t.client_id AND t.is_delete = 0 AND sos.status="0" AND t.status=1 AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND st.stock_order_id=t.stock_order_id
		$con = '';
		$cust_data = '';
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
		$sql = "SELECT st.admin_user_id,t.product_template_order_id,t.buyers_order_no,t.reference_no,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,pt.transportation_type FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,product_template_size as pts WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con."  AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND st.stock_order_id=t.stock_order_id";		
			if(!empty($filter_array)) {
				if(!empty($filter_array['order_no'])){
					$sql .= " AND st.gen_order_id = '".$filter_array['order_no']."'";				
				}
				if(!empty($filter_array['date'])){
					
					$sql .= " AND date(t.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
				}
				if(!empty($filter_array['by_shipment'])){
					
					$sql .= " AND pt.transportation_type = '".$filter_array['by_shipment']."'";
				}	
				if(!empty($filter_array['ib_user_name'])){
					
					$sql .= " AND st.admin_user_id = '".$filter_array['ib_user_name']."'";
				}	
				if(!empty($filter_array['country'])){
					
					$sql .= " AND t.country = '".$filter_array['country']."'";
				}	
		    }
		
		if($interval!='')
			$sql.=" AND t.date_added BETWEEN DATE_SUB(NOW(), INTERVAL ".$interval." DAY) AND NOW()";
	/*		
		if (isset($option['sort'])) {
			$sql .= " ORDER BY " .$option['sort'];	
		} else {
			$sql .= " ORDER BY t.template_order_id";	
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
		}*/
		//echo $sql;
		$data = $this->query($sql);
		$cust_size = 0;
		if($status=='1')
		{
			$cust_data = $this->getCustomAcceptedRecords($status,'','','',1);
			//$cust_cond=date_added;
			$cust_size = sizeof($cust_data);
		}
		//printr(sizeof($cust_data));
		
		if($data->num_rows)
		{
			return $data->num_rows + $cust_size;
		}
		else
		{
			return false;
		}
	}
	public function GetCartOrderListForIndians($user_id,$usertypeid,$cond='',$status='',$filter_array,$interval,$option)
	{
		//printr($status);
		$menu_id='';
		if($status==0)
		{
			$menu_id=79;
		} 
		elseif($status==1)
		{
			$menu_id=80;
		}
		
		$con = '';
		$cust_data = '';
		$cust_cond='';
		$perm_cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
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
			//printr($con.'r');
			//$sql = "SELECT t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,cu.currency_code FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con." AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id";
			$sql="SELECT st.admin_user_id,t.product_template_order_id,t.template_order_id,t.reference_no,t.order_type,t.buyers_order_no,cd.client_name,st.gen_order_id,st.address_book_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,cu.currency_code,pt.transportation_type,pts.zipper,pts.spout,pts.accessorie,pts.valve,pts.width,pts.height,pts.gusset,pts.volume,p.product_name,pc.color,t.quantity FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu,product_template_size as pts,pouch_color as pc,product as p WHERE c.country_id = t.country AND p.product_id=t.product_id AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con." AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color ";
			
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
			//printr($cond);
		//$sql = "SELECT t.product_template_order_id,sum(t.quantity) as  total_qty,sum(t.quantity*t.price) as total_price,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,count(t.product_template_order_id) as tot_count,cu.currency_code FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu WHERE c.country_id = t.country AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con." AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id";		
		$sql="SELECT st.admin_user_id,t.product_template_order_id,t.order_type,t.buyers_order_no,t.reference_no,t.template_order_id,cd.client_name,st.gen_order_id,st.address_book_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,cu.currency_code,pt.transportation_type,pts.zipper,pts.spout,pts.accessorie,pts.valve,pts.width,pts.height,pts.gusset,pts.volume,p.product_name,pc.color,t.quantity FROM " .DB_PREFIX . "template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu,product_template_size as pts,pouch_color as pc,product as p WHERE c.country_id = t.country AND p.product_id=t.product_id AND cd.client_id = t.client_id AND  t.is_delete = 0 ".$cond." ".$con." AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id  AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color   ";
	//SELECT t.product_template_order_id,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,cu.currency_code FROM template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu WHERE c.country_id = t.country AND cd.client_id = t.client_id AND t.is_delete = 0 AND sos.status="0" AND t.status=1 AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id
		//echo $sql;	
	}
		//echo $sql;
		if(!empty($filter_array)) {
				if(!empty($filter_array['order_no'])){
					$sql .= " AND st.gen_order_id = '".$filter_array['order_no']."'";				
				}
				if(!empty($filter_array['date'])){
					
					$sql .= " AND date(t.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
				}
				if(!empty($filter_array['by_shipment'])){
					
					$sql .= " AND pt.transportation_type = '".$filter_array['by_shipment']."'";
				}	
				if(!empty($filter_array['ib_user_name'])){
					
					$sql .= " AND st.admin_user_id = '".$filter_array['ib_user_name']."'";
				}	
				if(!empty($filter_array['country'])){
					
					$sql .= " AND t.country = '".$filter_array['country']."'";
				}	
		}
		

		if($interval!='')
			$sql.=" AND t.date_added BETWEEN DATE_SUB(NOW(), INTERVAL ".$interval." DAY) AND NOW()";

		
	if (isset($option['sort'])) {
			$sql .= " ORDER BY " .$option['sort'];	
		} else {
			$sql .= " ORDER BY user_id ";	
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
			
			//$sql .= " LIMIT 5";
		}
		//echo $sql;
		
		//printr($cust_data);
		$data = $this->query($sql);
		//printr($status);
		if($status=='1')
		{
			$cust_data = $this->getCustomAcceptedRecords($status);
			//printr($cust_cond);
			if(!empty($cust_data))
			{
				foreach($cust_data as $cust)
				{
					array_push($data->rows,$cust);
				}
			}
		}
		
		
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			if(!empty($cust_data))
				return $cust_data;
			else
				return false;
		}
	}
	
	public function sendDelayOrderEmail($post='',$status,$adminEmail,$send=0,$check=0)
	{
		//printr($status);die;
		$id=array();
		foreach($post as $val)
		{
			$arr = explode("==",$val);
			$id[]=array('template_order_id'=>$arr[0],'product_template_order_id'=>$arr[1],'client_id'=>$arr[2]);
			$k=$id[0];
		}
		//printr($id);die;
		$data = $this->query("SELECT group_id FROM stock_order_email_history_id ORDER BY group_id DESC LIMIT 1");
		if($data->num_rows>0)
		{
			$group_id = $data->row['group_id']+1;
		}
		else
			$group_id=1;
			$con='';
		foreach($id as $order_id)
		{
		
			$html_ddate='';
			
			$menu_id =0;
			$template_order_id ='';
			if($send != 1)
			{
				$con = '  t.template_order_id = '.$order_id['template_order_id'].' AND';
				$template_order_id=$order_id['template_order_id'];
			}
			if($status>0)
			{
				
				$cond = 'AND t.status = 1 AND sos.status='.$status.'  AND  t.client_id ='.$order_id['client_id'].'';
				if($status==1)
				{
					$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$template_order_id);
					//printr($user_detail);
					$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
					$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
					$toEmail[$datauser['user_name']]=$datauser['email'];
					$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];
					
					//printr($final_ddate);//die;
					if($send != 1)
					{
						$final_ddate = $this->getFinalddate($order_id['product_template_order_id'],$template_order_id);
						$new_date = $final_ddate['new_final_ddate'];
						
						$html_ddate = 'Your Expected Delivery Date is '.dateFormat(4,$final_ddate['old_ddate']).'<br>Your Order is Delay, <br> Your Final Delivery Date is '.dateFormat(4,$new_date);
						$menu_id = array('77');
					}
					else
					{
						$menu_id = array('79','80');
					}
					
				}
			$permissionData = '';
			if($menu_id >0)
				$permissionData = $this->getUserPermission($menu_id);
				
				
				//printr($permissionData);
			if(!empty($permissionData))
			{
				foreach($permissionData as $email_id)
				{
					$toEmail[$email_id['user_name']] = $email_id['email'];	
				}
			}
			$setHtml = '';
			$sub = '';
			$insert_qry = '';
			$setHtml .= '<div class="table-responsive">';
			$orders = $this->GetOrderList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$cond,$status,$con);
			//printr($orders);
			foreach($orders as $data)
			{
				$new_data[$data['gen_order_id']][]=$data;
				
			}	
			ksort($new_data);
			$f = 1;
			$total=0;$total_qty=0;
			$toEmail['swisspac'] = $adminEmail;
			$order_type='';
			foreach($new_data as $gen_order_id=>$data)
			{  
				$setHtml .="<div><b>Order No : ".$gen_order_id.'</b><br><br>';
				$sub .= $gen_order_id.' , ';
				foreach($data as $order)
				{	
				
					if($order['order_type']!='')
						$order_type = $order['order_type'];
					$insert_qry .= "('".$gen_order_id."','".$order['template_order_id']."','".$order['product_template_order_id']."','".$_SESSION['ADMIN_LOGIN_SWISS']."','".$_SESSION['LOGIN_USER_TYPE']."',NOW(),'".$group_id."','".$order['client_id']."','".$check."') , ";
					if($send!='1')
					{
						$setHtml .=$html_ddate;
					}
					else
					{	$final_ddate = $this->getFinalddate($order['product_template_order_id'],$order['template_order_id']);
						$new_date = $final_ddate['new_final_ddate'];
						$html_ddate = 'Your Expected Delivery Date is '.dateFormat(4,$new_date);
						$setHtml .=$html_ddate;
					}
					$setHtml .='<br><br>'.$order['quantity'].'&nbsp;&nbsp; X &nbsp;&nbsp;'.$order['volume'].'&nbsp;';
				
					$setHtml .='<span><b>'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_color']).' </b></span><span>';
					if($order['product_id']!=3)
						$setHtml .='<b>';
					 $setHtml .=preg_replace("/\([^)]+\)/","",preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_product']));
					 if($order['product_id']!=3)
						$setHtml .='</b>';
					 $setHtml .='</span>';
					$setHtml .='<br>Option : <span style="color:#FF0000;"> '.$order['zipper'].'</span>  <span style="color:#060;">'.$order['valve'].'</span> <span style="color:#FF6600;">'.$order['spout'].'</span> <span style="color:#0000FF;">'.$order['accessorie'].'</span>';
					$setHtml .='<br><br>';
					$postedByData = $this->getUser($order['user_id'],$order['user_type_id']);
				}
				if($order['address']!='')
				{
					$name='Customer&prime;s Address Below ';
					$address=$order['address'];
					$color='red';
				}
				else
				{	
					$name='Below Address';
					$address=$postedByData['address'].'<br>'.$postedByData['city'].' , '.$postedByData['state'].' ( '.$postedByData['country_name'].' )<br>'.$postedByData['postcode'].'<br>'.$postedByData['email'];
					$color='black';
				}
				if($status!=2)
				{
					$setHtml.='<br><br><b><span style="color:'.$color.'">Dispatch Directly To '.$name.'   <span style="color:blue">'.$order['transportation_type'].'</span> :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>'.$address.'</b></pre><br><br>';
				}
				if($order['review']!='' && $status==2)
					$setHtml.='<br><br><b><span style="color:red">Review :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>'.$order['review'].'</b></pre><br><br>';
				
				$setHtml.='</div>';
			}
				$setHtml.='<br>';
				$toEmail[$postedByData['user_name']] = $postedByData['email'];
				if(isset($adminpostedByData) && $adminpostedByData!='')
					$toEmail[$adminpostedByData['user_name']] =$adminpostedByData['email'];
				$sub=substr($sub,0,-2);
				if($send != 1)
					$subject = 'YOUR DELAYED '.$order_type.' OREDR NO: '.$sub.' Delay By '.$datauser['user_name'];   
				else
					 $subject = 'YOUR ACCEPTED '.$order_type.' ORDER NO: '.$sub;
					
				$insert_qry=substr($insert_qry,0,-2);
		}
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(4); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		$path = HTTP_SERVER."template/order_template.html";
		$output = file_get_contents($path);  
		$search  = array('{tag:header}','{tag:details}');
		
		$message = '';
		$signature='';
		if($postedByData['email_signature']){
			$signature = nl2br($postedByData['email_signature']);
		}
		if($setHtml){
			$tag_val = array(
				"{{productDetail}}" =>$setHtml,
				"{{ddsignature}}"	=> $signature,
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
		$qstr = '';
		//printr($order)
		foreach($toEmail as $toemail)
		{
			send_email($toemail,$adminEmail,$subject,$message,'');
			//send_email('tech@swisspack.co.in','tech@swisspack.co.in',$subject,$message,'');
		}
		
			$sql = "INSERT INTO stock_order_dispatch_history (stock_order_id,template_order_id,product_template_order_id,user_id,user_type_id,date,group_id,client_id,email_id) VALUES ".$insert_qry."";
		$data=$this->query($sql);
		$customerMessage = '';
		$AdmincustomerMessage = '';
		$signature = 'Thanks.';
	
		}
	}
	public function getDispatchQty($template_order_id,$product_template_order_id)
	{
		$data=$this->query("SELECT sum(dis_qty) as total_dis_qty FROM stock_order_dispatch_history WHERE template_order_id='".$template_order_id."' AND product_template_order_id='".$product_template_order_id."' ");
		
		if($data->num_rows)
		{
			return $data->row;
		}
		else
		{
			return false;
		}
		
		
	}
	
	public function updatestockorderstatus($value,$cond,$dis_qty,$rem_qty,$status,$template_order_id,$product_template_order_id,$total_qty,$n='0')
	{
		//echo $value.'<br>'.$cond.'<br>'.$dis_qty.'<br>'.$rem_qty.'<br>'.$status.'<br>'.$template_order_id.'<br>'.$product_template_order_id.'<br>'.$total_qty.'<br>';
		//die;
		
		if($n=='1')
		{
			//printr("UPDATE " . DB_PREFIX . "multi_custom_order SET ".$value." ".$dis_qty." WHERE ".$cond."");
			$value .= ",accept_decline_status='".$status."'";
			$data_update = $this->query("UPDATE " . DB_PREFIX . "multi_custom_order SET ".$value." ".$dis_qty." WHERE ".$cond."");
			return $data_update;
		}
		else
		{
			//printr('stock');
			if($status == '3' || $status == '2')
			{
				$data_update = $this->query("UPDATE " . DB_PREFIX . "stock_order_status SET ".$value." WHERE ".$cond."");
			
				$data_insert=$this->query("INSERT INTO " . DB_PREFIX . "stock_order_dispatch_history SET ".$value." ".$dis_qty." ".$rem_qty.", template_order_id='".$template_order_id."', product_template_order_id='".$product_template_order_id."',dis_date= NOW()");
				
				$data_select=$this->query("SELECT sum(dis_qty) as total_dis_qty FROM stock_order_dispatch_history WHERE template_order_id='".$template_order_id."' AND product_template_order_id='".$product_template_order_id."'");
				
				if($data_select->num_rows)
				{
					if($data_select->row['total_dis_qty'] == $total_qty)
					{
						$value .= ",status='".$status."'";
						$data_update = $this->query("UPDATE " . DB_PREFIX . "stock_order_status SET ".$value." WHERE ".$cond."");
						return $data_update;
					}
					else
					{
						return false;
					}
					
				}
				else
				{
					return false;
				}
				
			}
			else
			{
				$data = $this->query("UPDATE " . DB_PREFIX . "stock_order_status SET ".$value." WHERE ".$cond."");
				return $data;
			}
		}
		
	}
	
	public function getUpdatedTrackHistory($product_template_order_id,$template_order_id)
	{
		$sql = "SELECT sodh.*,c.courier_name  FROM " . DB_PREFIX ."stock_order_dispatch_history as sodh,courier as c  WHERE sodh.product_template_order_id='".$product_template_order_id."' AND sodh.template_order_id='".$template_order_id."' AND sodh.courier_id = c.courier_id ORDER BY sodh.stock_order_dispatch_history_id DESC";
		$data = $this->query($sql);
		//printr($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getSelectedRecord($user_data,$order_type='')
	{
		$id = explode("=",$user_data);
		$user_type_id = $id[0];
		$user_id = $id[1];
		
                $order='';
		if($order_type!='')
			$order = ' AND t.order_type = "'.$order_type.'"';

		if($user_type_id==2)
		{
			$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$user_id."'";
			$dataadmin = $this->query($sqladmin);
			$con =  'AND st.admin_user_id = "'.$dataadmin->row['user_id'].'"';
			$cust_con =  'AND mcoi.admin_user_id = "'.$dataadmin->row['user_id'].'"';
		}
		elseif($user_type_id==4)
		{
			$con =  "AND st.admin_user_id = '".$user_id."'";
			$cust_con =  "AND mcoi.admin_user_id = '".$user_id."'";
		}
		//echo $con;//die;
		$sql_user = "SELECT t.product_template_order_id,t.buyers_order_no,t.reference_no,t.template_order_id,cd.client_name,st.gen_order_id,t.stock_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,cu.currency_code,pt.transportation_type,pts.zipper,pts.spout,pts.accessorie,pts.valve,pts.width,pts.height,pts.gusset,pts.volume,p.product_name,pc.color,t.quantity,t.order_type FROM template_order t,client_details cd,stock_order st,country c,stock_order_status as sos,product_template_order as pto,product_template as pt,currency as cu,product_template_size as pts,pouch_color as pc,product as p WHERE c.country_id = t.country AND p.product_id=t.product_id AND cd.client_id = t.client_id AND t.is_delete = 0 AND sos.status='1' AND t.status=1 AND st.stock_order_id=t.stock_order_id AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND pt.currency=cu.currency_id AND t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND pc.pouch_color_id = t.color ".$con." ".$order." ORDER BY t.template_order_id ASC";
		//echo $sql_user;
		$data_user = $this->query($sql_user);
		 
               // if($order_type!='sample')
		//{
		      $cust_data = $this->getCustomAcceptedRecords($status='1','',$cust_con); 
		      if(!empty($cust_data))
		      {
			  foreach($cust_data as $cust)
			  {
				array_push($data_user->rows,$cust);
			  }
		      }
                //}
		
		if($data_user->num_rows>0)
		{
				
				return $data_user->rows;
		}
		else
		{
				if(!empty($cust_data))
					return $cust_data;
				else
					return false;
		}
	
	}
	
	public function sendDispatchOrderEmail($post='',$status='',$adminEmail='',$send=0,$check=0,$on_ship=0,$order_type='')
	{
		//$post[]='377==5==157';
		if($on_ship=='1')
		{
			$mail_instance = 'ready to Ship';
			$sen = 'YOUR SHIPED';
			$sen_detail = $s_detail = 'Ship';
		}
		else
		{
			$mail_instance = 'Dispatched';
			$sen = 'YOUR DISPATCHED';
			$sen_detail = 'Dispatch Directly';
			$s_detail = 'Dispatch';
		}
			
		if($status = '3')
		{
			$status = '1';
		}
		$id=array();
		
		foreach($post as $val)
		{
			
			$arr = explode("==",$val);
			if(count($arr)=='2')
				$id[]=array('custom_order_id'=>$arr[0],'multi_custom_order_id'=>$arr[1]);
			else
				$id[]=array('template_order_id'=>$arr[0],'product_template_order_id'=>$arr[1],'client_id'=>$arr[2]);
			
			$k=$id[0];
		}
		//printr($id);
		$data = $this->query("SELECT group_id FROM stock_order_email_history_id ORDER BY group_id DESC LIMIT 1");
		if($data->num_rows>0)
		{
			$group_id = $data->row['group_id']+1;
		}
		else
			$group_id=1;
			$con='';
		
		//$result = $obj_template->getSelectedRecord($post);
		//printr($result);
		
		$key='';
		foreach($id as $order_id)
		{	//printr($order_id);
			
			$html_ddate='';
			
			$menu_id =0;
			$template_order_id ='';
			if($send != 1 && !isset($order_id['custom_order_id']))
			{
				$con = '  t.template_order_id = '.$order_id['template_order_id'].' AND';
				$template_order_id=$order_id['template_order_id'];
			}
			else
			{
				$con='';
			}
			if($status>0)
			{
				if(isset($order_id['custom_order_id']))
					$cond='';
				else
					$cond = 'AND t.status = 1 AND (sos.status='.$status.' OR sos.status=3)   AND  t.client_id ='.$order_id['client_id'].'';
				if($status==1 || $status==3)
				{
					
					if(isset($order_id['custom_order_id']))
					{ 
						$user_cust_detail=$this->getdeatilCustuser($order_id['multi_custom_order_id']);
						$user_detail['user_id'] = $user_cust_detail['added_by_user_id'];
						$user_detail['user_type_id']=$user_cust_detail['added_by_user_type_id'];
						$user_detail['admin_user_id']=$user_cust_detail['admin_user_id'];
					}
					else
					{
						$user_detail=$this->getdeatiluser($order_id['product_template_order_id'],$template_order_id);
					}
					$datauser = $this->getUser($user_detail['user_id'],$user_detail['user_type_id']);
					$datauser_admin = $this->getUser($user_detail['admin_user_id'],4);
					$toEmail[$datauser['user_name']]=$datauser['email'];
					$toEmail[$datauser_admin['user_name']]=$datauser_admin['email'];
					
					$menu_id = array('79','80');
				}
				$permissionData = '';
				if($menu_id >0)
					$permissionData = $this->getUserPermission($menu_id);
				
				// sonu  added email for ankitsir on 20-4-2017  				
				//offline_id = 71 online id = 96
				$remove_email_ankit_sir = $this->getUser(96,2);
				//printr($remove_email_ankit_sir);
					
				//printr($permissionData);//die;
				//[kinjal] Edited on 9-5-2017	
				if(!empty($permissionData))
				{
					foreach($permissionData as $email_id)
					{
						$toEmail[$email_id['user_name']] = $email_id['email'];$remove_email_ankit_sir_a[$remove_email_ankit_sir['user_name']] = $remove_email_ankit_sir['email'];
						
						if(!in_array($email_id['email'],$remove_email_ankit_sir_a))
						{
							$toEmail[$email_id['user_name']] = $email_id['email'];	
						}	
					}
				}
				$setHtml = '';
				$sub = '';
				$insert_qry = '';
				$setHtml .= '<div class="table-responsive">';
				//echo $status;GetOrderList($user_id,$usertypeid,$status='',$data='',$con='',$filter_array=array(),$client_id='',$dis_cond='',$dis_table='',$dis_select='',$page='',$st='',$stock_order_id='',$custom_order_id='')
				$custom_order_id='';
				if(isset($order_id['custom_order_id']))
					$custom_order_id = $order_id['custom_order_id'];
					
				if(isset($order_id['custom_order_id']))
					$orders = $this->getCustomAcceptedRecords('3',$custom_order_id);
				else
					$orders = $this->GetOrderList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE'],$cond,$status,$con);
				//printr($orders);//die;
				//$key_con='';
				
				foreach($orders as $data)
				{
					$new_data[$data['gen_order_id']][]=$data;
					//$key_con = $data['gen_order_id'].' ';
					$key = $data['gen_order_id'];
				}
				ksort($new_data);
			}
			
		}
		//printr($new_data);die;
		//die;
		//$order_type  = '';
		$f = 1;
		$total=0;$total_qty=0;
		$toEmail['swisspac'] = $adminEmail;
		foreach($new_data as $gen_order_id=>$data)
		{  
			$setHtml .="<div><b>Order No : ".$gen_order_id.'</b><br><br>';
			$sub .= $gen_order_id.' , ';
			foreach($data as $order)
			{	
				/*if($order['order_type']!='')
					$order_type  = $order['order_type'];*/
				$dis_qty= $this->getUpdatedTrackHistory($order['product_template_order_id'],$order['template_order_id']);

				$insert_qry .= "('".$gen_order_id."','".$order['template_order_id']."','".$order['product_template_order_id']."','".$_SESSION['ADMIN_LOGIN_SWISS']."','".$_SESSION['LOGIN_USER_TYPE']."',NOW(),'".$group_id."','".$order['client_id']."','".$check."') , ";
				
				$setHtml .='<br><br>'.$order['quantity'].'&nbsp;&nbsp; X &nbsp;&nbsp;'.$order['volume'].'&nbsp;';
				if(!isset($order['custom_order_id']))
					$setHtml .='<span><b>'.preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_color']).' </b></span><span>';
				
				if(isset($order['product_id']) && $order['product_id']!=3)
					$setHtml .='<b>';
					
				if(!isset($order['custom_order_id']))
				 	$setHtml .=preg_replace("/\([^)]+\)/","",preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['email_product']));
				else
					$setHtml .=preg_replace("/\([^)]+\)/","",preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $order['product_name']));
				 
				 if(isset($order['product_id']) && $order['product_id']!=3)
					$setHtml .='</b>';
				 $setHtml .='</span>';
				 
				if(isset($order['custom_order_id']))
				 	$setHtml .='<br><span>Your '.$order['dis_qty'].' Qty is '.$mail_instance.'</span>'; 
				else
				 	$setHtml .='<br><span>Your '.$dis_qty[0]['dis_qty'].' Qty is '.$mail_instance.'</span>'; 
				
				$accessorie_txt_corner='';
				if(isset($order['accessorie_txt_corner']))
				    $accessorie_txt_corner = $order['accessorie_txt_corner'];
				 
				$setHtml .='<br>Option : <span style="color:#FF0000;"> '.$order['zipper'].'</span>  <span style="color:#060;">'.$order['valve'].'</span> <span style="color:#FF6600;">'.$order['spout'].'</span> <span style="color:#0000FF;">'.$order['accessorie'].'</span> <span style="color:#060;">'.$accessorie_txt_corner.'</span>';
				$setHtml .='<br><br>';
				$postedByData = $this->getUser($order['user_id'],$order['user_type_id']);
			}
			
			if($order['address']!='')
			{
				$name='Customer&prime;s Address Below ';
				$address=$order['address'];
				$color='red';
			}
			else
			{	
				$name='Below Address';
				$address=$postedByData['address'].'<br>'.$postedByData['city'].' , '.$postedByData['state'].' ( '.$postedByData['country_name'].' )<br>'.$postedByData['postcode'].'<br>'.$postedByData['email'];
				$color='black';
			}
			if($status!=2)
			{
				$setHtml.='<br><br><b><span style="color:'.$color.'">'.$sen_detail.' To '.$name.'   <span style="color:blue">'.$order['transportation_type'].'</span> :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>'.$address.'</b></pre><br><br>';
			}
			if($order['review']!='' && $status==2)
				$setHtml.='<br><br><b><span style="color:red">Review :-</span></b><br><br><pre style="font-size: 15px;font-weight: bolder;color: black;"><b>'.$order['review'].'</b></pre><br><br>';
			
			$setHtml.='</div>';
		}
		//echo $key;
		//die;
			//printr($new_data[$key][0]['track_id']);die;
			//$courier_nm = $this->getCourier($new_data[$key][0]['courier_id']);
			$setHtml.='<div><b>'.$s_detail.' Detail :</b><br><br>';
			//$setHtml.='<b>Track ID : '.$new_data[$key][0]['track_id'].'</b><br>';
			$setHtml.='<b>Date : '.dateFormat(4,$new_data[$key][0]['date']).'</b><br>';
			//$setHtml.='<b>Courier : '.$courier_nm.'</b><br>';
			$setHtml.='<b>Remark : '.$new_data[$key][0]['review'].'</b><br>';
			$setHtml.='</div>';
			$setHtml.='<br>';
			//printr($setHtml);die;
			$toEmail[$postedByData['user_name']] = $postedByData['email'];
			if(isset($adminpostedByData) && $adminpostedByData!='')
				$toEmail[$adminpostedByData['user_name']] =$adminpostedByData['email'];
			$sub=substr($sub,0,-2);
			if($send != 1)
				$subject = $sen.' '.strtoupper($order_type).' OREDR NO: '.$sub.' Dispatched For '.$datauser['user_name'];   
			else
				 $subject = 'ACCEPTED '.strtoupper($order_type).' ORDER NO: '.$sub;
				
			$insert_qry=substr($insert_qry,0,-2);
		
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(4); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		$path = HTTP_SERVER."template/order_template.html";
		$output = file_get_contents($path);  
		//printr($output);die;
		$search  = array('{tag:header}','{tag:details}');
		
		$message = '';
		$signature='';
		if($postedByData['email_signature']){
			$signature = nl2br($postedByData['email_signature']);
		}
		if($setHtml){
			$tag_val = array(
				"{{productDetail}}" =>$setHtml,
				"{{ddsignature}}"	=> $signature,
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
		$qstr = '';
		//printr($message);die;
		foreach($toEmail as $toemail)
		{
			//printr($toEmail);
			//printr($message);
			send_email($toemail,$adminEmail,$subject,$message,'');
		
		}
		//die;
		//send_email('tech@swisspack.co.in','tech@swisspack.co.in',$subject,$message,'');
		if($on_ship=='0')
		{
			$sql = "INSERT INTO stock_order_email_history_id (stock_order_id,template_order_id,product_template_order_id,user_id,user_type_id,date,group_id,client_id,email_id) VALUES ".$insert_qry."";
			//$data=$this->query($sql);
		}
		$customerMessage = '';
		$AdmincustomerMessage = '';
		$signature = 'Thanks.';
	//die;
		}
		
		public function declineQty($template_order_id,$product_template_order_id)
		{	
			$sql = "SELECT decline_qty FROM " . DB_PREFIX ."stock_order_dispatch_history  WHERE product_template_order_id='".$product_template_order_id."' AND template_order_id='".$template_order_id."' AND decline_qty!=0";
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
		
		public function getZipper($product_zipper) 
		{
			$sql = "select * from " . DB_PREFIX ."product_zipper where zipper_name = '".$product_zipper."'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}
			else {
				return false;
			}
		}
		public function getSpout($product_spout) 
		{
			$sql = "select * from " . DB_PREFIX ."product_spout where spout_name = '".$product_spout."'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}
			else {
				return false;
			}
		}
		public function getAccessorie($product_accessorie) 
		{
			$sql = "select * from " . DB_PREFIX ."product_accessorie where product_accessorie_name = '".$product_accessorie."'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}
			else {
				return false;
			}
		}
		public function getMeasurementid($mea)
		{
			$sql = "SELECT * FROM `" . DB_PREFIX . "template_measurement` WHERE measurement = '".$mea."' ";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}
			//[kinjal] (10-9-2016)
		
		public function getCustomAcceptedRecords($status='',$custom_order_id='',$con='',$multi_custom_order_id='',$group_by='')
		{
			
			
			$getData = " mco.dis_qty,mcoi.date_added,mco.accept_decline_status,custom_order_id,mco.added_by_user_id,mco.added_by_user_type_id, customer_name, mco.shipment_country_id,mco.multi_custom_order_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,mco.status,mco.custom_order_status,discount";
			//echo $con.'kju<br>';
			$custom_data = $this->getCustomOrder($getData,$status,$custom_order_id,$con,$multi_custom_order_id,$group_by);
			//printr($custom_data);
			return $custom_data;
		}
		
		public function getCustomOrderQuantity($custom_order_id)
		{
			//printr($custom_order_id);
			//echo '<br>';
			$paking_price=$this->getCustomOrderPackingAndTransportDetails($custom_order_id);
			$data = $this->query("SELECT mco.track_id,mco.courier_id,mco.date,mco.process_by,mco.dispach_by,mco.review,mco.expected_ddate,mco.dis_qty,mco.currency_price,mco.currency,mco.accept_decline_status,mco.custom_order_id,mcoq.cust_quantity,mcoq.custom_order_quantity_id,mco.product_id,p.product_name, mcoq.custom_order_id,mcoq.discount, printing_effect,mcoq.quantity,mco.height,mco.width,mco.gusset,mco.volume, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mco.layer,total_weight_with_zipper,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage FROM " . DB_PREFIX ."multi_custom_order_quantity as mcoq,multi_custom_order as mco,product as p WHERE mcoq.custom_order_id = '".(int)$custom_order_id."' AND mcoq.custom_order_id=mco.custom_order_id AND mco.product_id=p.product_id ORDER BY mcoq.custom_order_quantity_id") ;
			//printr($data);
			$return = '';
			if($data->num_rows){
				foreach($data->rows as $qunttData){
					//printr($qunttData);
					$zdata = $this->query("SELECT cust_total_price,custom_order_price_id, custom_order_id, custom_order_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt, total_price,total_price_with_excies,total_price_with_tax,tax_name,tax_type,tax_percentage,excies, gress_price, customer_gress_price,zipper_price,valve_price,courier_charge,spout_base_price,accessorie_base_price,transport_price,make_name FROM " . DB_PREFIX ."multi_custom_order_price as mcop ,product_make as pm WHERE custom_order_id = '".(int)$qunttData['custom_order_id']."' AND custom_order_quantity_id = '".(int)$qunttData['custom_order_quantity_id']."' AND mcop.make_pouch=pm.make_id ORDER BY transport_type");	
				///	printr($zdata);
					if($zdata->num_rows){
						if(isset($zdata->rows[0]['excies']) && $zdata->rows[0]['excies']>0)
						{
							$quantity_option[$qunttData['quantity']] =  array(
								'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'],
								'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'],
								'Wastage' => $qunttData['wastage_base_price'],
								'Profit' => $qunttData['profit'],
								'Excies' => $zdata->rows[0]['excies'].' %',
								'tax_name'=>$zdata->rows[0]['tax_name'],
								str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])) => $zdata->rows[0]['tax_percentage'].' %'					
							);
						}
						else
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'],
							'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'],
							'Wastage' => $qunttData['wastage_base_price'],
							'Profit' => $qunttData['profit'],
							);
						}
						foreach($zdata->rows as $zipData){
						//printr($zipData);
							$materialData = $this->getCustomOrderMaterial($zipData['custom_order_id']);
							$new_tax[$qunttData['quantity']] =  array('Excies' => $zipData['excies']);
							$zipper_option =
							 array(
								'zipper_price' => $zipData['zipper_price'],
								'valve_price' => $zipData['valve_price'],
								'courier_charge' => $zipData['courier_charge']  ,
								'spout_price' => $zipData['spout_base_price'],
								'accessorie_price' => $zipData['accessorie_base_price'],
								
							);
							
							$txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
							$txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
							$txt .= '<br>'.$zipData['accessorie_txt_corner'].'';
							
							if($zipData['spout_txt']=='No Spout')
								$zipData['spout_txt']='';
							if($zipData['accessorie_txt']=='No Accessorie')
								$zipData['accessorie_txt']='';
							$email_txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
							$email_txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
							
								$return[$zipData['transport_type']][$qunttData['quantity']][] = array(
									'text' 		=> $txt,
									'email_text' 		=> $email_txt,
									'totalPrice'	   =>  $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
									'totalPriceWithExcies'	   =>  $this->numberFormate(($zipData['total_price_with_excies']),"3"),
									'totalPriceWithTax'	   =>  $this->numberFormate(($zipData['total_price_with_tax']),"3"),
									'tax_type'	   => $zipData['tax_type'] ,
									'tax_percentage'	   => $zipData['tax_percentage'] ,
									'excies'	   => $zipData['excies'] ,
									'tax_name'=>$zipData['tax_name'],
									'customerGressPrice' => $zipData['customer_gress_price'],
									'custom_order_quantity_id'=>$zipData['custom_order_quantity_id'],
									'discount'=>$qunttData['discount'],
									'cust_quantity'=>$qunttData['cust_quantity'],
									'cust_total_price'=>$zipData['cust_total_price'],
									'width'=>$qunttData['width'],
									'height'=>$qunttData['height'],
									'gusset'=>$qunttData['gusset'],
									'volume'=>$qunttData['volume'],
									'cylinder_price'=>$qunttData['cylinder_price'],
									'tool_price'=>$qunttData['tool_price'],
									'quantity_option'	=> $quantity_option[$qunttData['quantity']],
									'zipper_option' => $zipper_option,
									'courier_charge' => $zipData['courier_charge'],
									'transport_price' => $zipData['transport_price'] ,
									'packing_price'=>$paking_price['packing_price'],
									'gress_price'  => $zipData['gress_price'],
									'gress_percentage' => $qunttData['gress_percentage'],
									'gress_sea' => $qunttData['gress_sea'],
									'gress_air' => $qunttData['gress_air'],
									'customer_gress_percentage' => $qunttData['customer_gress_percentage'],
									'zipper_txt' => $zipData['zipper_txt'],
									'valve_txt' => $zipData['valve_txt'],
									'accessorie_txt' => $zipData['accessorie_txt'],
									'accessorie_txt_corner' => $zipData['accessorie_txt_corner'],
									'spout_txt' => $zipData['spout_txt'],
									'printing_effect' => $qunttData['printing_effect'],
									'materialData'=>$materialData,
									'make' => $zipData['make_name'],
									'custom_order_price_id'=>$zipData['custom_order_price_id'],
									'layer'=>$qunttData['layer'].''.$zipData['custom_order_id'],
									'product_id'=>$qunttData['product_id'],
									'product_name'=>$qunttData['product_name'],
									'custom_order_id'=>$qunttData['custom_order_id'],
									'accept_decline_status'=>$qunttData['accept_decline_status'],
									'currency'=>$qunttData['currency'],
									'currency_price'=>$qunttData['currency_price'],
									'dis_qty'=>$qunttData['dis_qty'],
									'expected_ddate'=>$qunttData['expected_ddate'],
									'review'=>$qunttData['review'],
									'track_id'=>$qunttData['track_id'],
									'courier_id'=>$qunttData['courier_id'],
									'date'=>$qunttData['date'],
									'process_by'=>$qunttData['process_by'],
									'dispach_by'=>$qunttData['dispach_by'],
								
								);
						}
					}
				}
			}
		return $return;
	}
	public function getCustomOrderPackingAndTransportDetails($custom_order_id){
		$sql = "SELECT mcobp.packing_price,mcobp.transport_width_base_price,mcobp.transport_height_base_price,mco.product_note, mco.product_instruction FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."multi_custom_order_base_price mcobp ON (mco.custom_order_id=mcobp.custom_order_id) WHERE mco.custom_order_id = '".(int)$custom_order_id."'";
	
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	
	public function getCustomOrderMaterial($custom_order_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."multi_custom_order_layer WHERE custom_order_id = '".(int)$custom_order_id."'";
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
		
		
		
		public function getCustomOrder($getData = '*',$status='',$custom_order_id='',$con='',$multi_custom_order_id='',$group_by)
		{
			/*if($user_type_id && $user_id){
				if($user_id == 1 && $user_type_id == 1){
					$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN multi_custom_order_id as mcoi ON(mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE mco.accept_decline_status = '1'";
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
						$str = ' OR ( mco.added_by_user_id IN ('.$userEmployee.') AND mco.added_by_user_type_id = 2 ) ';
					}
					$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN " . DB_PREFIX ."multi_custom_order_id mcoi ON (mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE mco.accept_decline_status = '1' AND (mco.added_by_user_id = '".(int)$set_user_id."' AND mco.added_by_user_type_id = '".(int)$set_user_type_id."') $str ";
				}
			}else{
				$sql = "SELECT $getData,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode FROM " . DB_PREFIX ."multi_custom_order mco,multi_custom_order_id mcoi,address adr WHERE mco.multi_custom_order_id=mcoi.multi_custom_order_id  AND mco.multi_custom_order_id = '".(int)$custom_order_id."' AND mcoi.shipping_address_id=adr.address_id";
			}*/
			//echo $custom_order_id.'kinjal'; AND  mco.multi_custom_order_id = '22' GROUP BY mcoi.admin_user_id
			$menu_id = 79;
			$menu_id_sec = 80;
			//$con = '';
			
			$perm_cond ='( ( add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%" ) OR ( add_permission LIKE "%'.$menu_id_sec.'%" AND edit_permission LIKE "%'.$menu_id_sec.'%" AND delete_permission LIKE "%'.$menu_id_sec.'%" AND view_permission LIKE "%'.$menu_id_sec.'%" ) )';
			
			$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$perm_cond." AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id ='".$_SESSION['LOGIN_USER_TYPE']."'";
			//echo $sql;
			$dataper=$this->query($sql);
			
			$admin_user_id_cond='';
			if($dataper->num_rows=='0')
			{
				if($_SESSION['LOGIN_USER_TYPE']==2)
				{
					$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
					$dataadmin = $this->query($sqladmin);
					$admin_user_id_cond =  'AND mcoi.admin_user_id = "'.$dataadmin->row['user_id'].'"';
				}
				elseif($_SESSION['LOGIN_USER_TYPE']==4)
				{
					$admin_user_id_cond =  'AND mcoi.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'"';
				}
				
				
				//$admin_user_id_cond = 'AND mcoi.admin_user_id="7"';
			}
			
			$cust = $group = '';
			if($custom_order_id!='')
				$cust = "AND mco.custom_order_id = '".(int)$custom_order_id."'";
				
			if($multi_custom_order_id!='')
				$cust = "AND mco.multi_custom_order_id = '".(int)$multi_custom_order_id."'";
			
			if($group_by!='')
				$group = 'GROUP BY mcoi.admin_user_id,mcoi.multi_custom_order_id';
			//echo $cust;	
		$sql = "SELECT $getData,cn.country_name,mcoi.multi_custom_order_number,mcoi.company_name,mcoi.email,mcoi.order_note,mcoi.order_instruction,mcoi.contact_number,adr.address,adr.address_2,adr.city,adr.state,adr.postcode  FROM " . DB_PREFIX ."multi_custom_order mco INNER JOIN " . DB_PREFIX ."country cn ON (mco.shipment_country_id=cn.country_id) INNER JOIN multi_custom_order_id as mcoi ON(mco.multi_custom_order_id=mcoi.multi_custom_order_id) INNER JOIN `" . DB_PREFIX ."address` adr ON (mcoi.shipping_address_id=adr.address_id) WHERE mco.accept_decline_status =".$status." $cust $con $group $admin_user_id_cond ORDER BY mcoi.admin_user_id DESC";
		//echo $sql;
		$data = $this->query($sql);
		
	//	printr($data);
		$cust_array = '';
		if($data->num_rows)
		{
			   foreach($data->rows as $dat)
			   {
			  
				 $multi_custom_order_id=$dat['multi_custom_order_id'];
				 $custom_order_id=$dat['custom_order_id'];
				 $cdata[$dat['custom_order_id']] = array('company_name'=>$dat['company_name'],
				 										 'multi_custom_order_number'=> $dat['multi_custom_order_number'],
														 'multi_custom_order_id'=> $dat['multi_custom_order_id'],
														 'date_added'=> $dat['date_added'],
														 'country_name'=> $dat['country_name'],
														 'added_by_user_id'=> $dat['added_by_user_id'],
														 'added_by_user_type_id'=> $dat['added_by_user_type_id'],
														 'customer_name'=> $dat['company_name'],
														 'shipment_country_id'=> $dat['shipment_country_id']);
				 $result = $this->getCustomOrderQuantity($dat['custom_order_id']);
				 if($result!='')
					$quantityData[] =$result;
			   	  }
					//printr($quantityData);die;
			   if(!empty($quantityData))
			   {
					foreach($quantityData as $k=>$qty_data)
					{
						foreach($qty_data as $tag=>$qty)
						{
							foreach($qty as $q=>$arr)
							{
								$new_data[$tag][$q][]=$arr[0];
							}
						}	
					}
			   }
			   //printr($new_data);
			   foreach($new_data as $k=>$qty_data)
			   {
					foreach($qty_data as $skey=>$sdata)
					{
						foreach($sdata as $soption)
						{
							$cust_array[] = array('product_template_order_id' => $soption['custom_order_quantity_id'],
													'buyers_order_no' =>  '',
													'template_order_id' =>  '',
													'client_name' =>  ucwords($cdata[$soption['custom_order_id']]['customer_name']),
													'gen_order_id' =>  $cdata[$soption['custom_order_id']]['multi_custom_order_number'],
													'stock_order_id' => $cdata[$soption['custom_order_id']]['multi_custom_order_id'],
													'date_added' =>  $cdata[$soption['custom_order_id']]['date_added'],
													'country_name' =>  $cdata[$soption['custom_order_id']]['country_name'],
													'ship_type' =>  0,
													'client_id' =>  0,
													'user_id' => $cdata[$soption['custom_order_id']]['added_by_user_id'],
													'user_type_id' =>  $cdata[$soption['custom_order_id']]['added_by_user_type_id'],
													'currency_code' =>  $soption['currency'],
													'transportation_type' =>  'By '.ucwords($k),
													'zipper' =>  ucwords($soption['zipper_txt']),
													'spout' => ucwords($soption['spout_txt']),
													'accessorie' =>  ucwords($soption['accessorie_txt']),
													'accessorie_txt_corner' =>  ucwords($soption['accessorie_txt_corner']),
													'valve' =>  ucwords($soption['valve_txt']),
													'width' => (int)$soption['width'],
													'height' =>  (int)$soption['height'],
													'gusset' =>  $soption['gusset'],
													'volume' =>  $soption['volume'],
													'product_name' =>  $soption['product_name'],
													'product_id' =>  $soption['product_id'],
													'color' =>  '',
													'quantity' =>$skey,
													'custom_order_id' =>  $soption['custom_order_id'],
													'accept_decline_status' =>  $soption['accept_decline_status'],
													'total_price' => $soption['totalPrice'],
													'currency_price' => $soption['currency_price'],
													'dis_total_price' => $this->numberFormate(((($soption['totalPrice']/$skey)/$soption['currency_price'])*$soption['dis_qty']),"3"),
													'total_qty'=>$skey,
													'dis_qty'=>$soption['dis_qty'],
													'expected_ddate'=>$soption['expected_ddate'],
													'note'=>'',
													'title'=>$soption['product_name'],
													'price'=>$this->numberFormate((($soption['totalPrice']/$skey)/$soption['currency_price']),"3"),
													'address'=>'',
													'review'=>$soption['review'],
													'track_id'=>$soption['track_id'],
													'courier_id'=>$soption['courier_id'],
													'date'=>$soption['date'],
													'process_by'=>$soption['process_by'],
													'dispach_by'=>$soption['dispach_by'],
													'status'=>$soption['accept_decline_status'],
													'price_uk'=>'0.000',
													'order_type'=>'',
													'reference_no'=>'',
													'shipment_country'=>$cdata[$soption['custom_order_id']]['shipment_country_id']
													);
						}					
					}
			   }
			  
		}
		//printr($cust_array);
		return $cust_array;
	}
	
	public function getdeatilCustuser($multi_custom_order_id)
	{
		$sql="SELECT added_by_user_id,added_by_user_type_id,admin_user_id FROM multi_custom_order_id WHERE multi_custom_order_id='".$multi_custom_order_id."'";
		$data=$this->query($sql);
		return $data->row;
	}
	
	public function getActiveColor(){

			$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE status='1' AND is_delete = '0' ORDER BY color  ASC";

			$data = $this->query($sql);

			if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}

		}
	
	public function sendWarningMailForGenInv($data,$admin_email,$con='0',$order_type='')
	{
		$otype='';
		if($order_type!='')
			$otype = 'For '.ucfirst($order_type).' Order';
			
		//echo $otype;
		$html='';
		if($con=='0')
		{
			$user_data=explode('=',$data);
			$subject = 'Generating Invoice '.$otype;  
			$addedByinfo=$this->getUser('19','2');
			$html.='Please Genreate Invoice For '.$user_data[2].'.';
		}
		else
		{
			$subject = 'Add Details In Invoice '.$otype;  
			
			if($order_type=='sample')
			{
				//for pooja
				$addedByinfo=$this->getUser('68','2');
				
			}
			else
			{
				//for jaimini  
				$addedByinfo=$this->getUser('39','2');
				
				//for pinank
				$addedByinfo_one=$this->getUser('40','2');
				
			}
			
			$sql_q='SELECT country_name FROM `country` WHERE `country_id` = "'.$data.'" ';
			$data_q=$this->query($sql_q);
			
			$html.='Please Add Details in  Invoice For '.$data_q->row['country_name'].'.';
			
			$email_temp[]=array('html'=>$html,'email'=>$addedByinfo_one['email']);
		}
		
		
		$email_temp[]=array('html'=>$html,'email'=>$admin_email);
		$email_temp[]=array('html'=>$html,'email'=>$addedByinfo['email']);
		
		$form_email=$addedByinfo['email'];
		
		
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(4); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		$path = HTTP_SERVER."template/order_template.html";
		$output = file_get_contents($path);  
		
		$search  = array('{tag:header}','{tag:details}');
		
		$message = '';
		$signature='';
		$signature = 'Thanks.';
		
		foreach($email_temp as $val)
		{
			$message = '';
			if($val['html'])
			{
				$tag_val = array(
					"{{productDetail}}" =>$html,
					"{{ddsignature}}"	=> $signature,
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
			//printr($message);die;
			send_email($val['email'],$admin_email,$subject,$message,'');
		}
	}
	
	public function getVolume($volume)
	{
		
		$sql = "SELECT  pouch_volume_id,volume FROM  pouch_volume WHERE is_delete = '0' AND volume  LIKE '".$volume."%' ";
			
		//echo $sql;
		$data = $this->query($sql);
		//printr($data);
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
	}
	
	public function getClientNameFromAddBook($client_name)
	{
		
		if($_SESSION['LOGIN_USER_TYPE'] == 2){
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$userEmployee = $this->getUserEmployeeIds($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
			$set_user_id = $user_id;
			$set_user_type_id = $user_type_id;
		}
		$str = '';
		if($userEmployee){
			$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
		}
	
		$result=$this->query("SELECT address_book_id FROM " . DB_PREFIX ."address_book_master WHERE company_name = '".strtolower($client_name)."' AND ( ( user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' ) $str)");
		if($result->num_rows){
			return $result->row['address_book_id'];
		}else{
			return false;
		}
	
    	
	}
	//[kinjal] : 0n 7-6-2017
    public function Acceptorder($data)
    	{
    		
    		foreach($data as $val)
    		{
    			
    			$var = explode("==",$val);
    			$arr=array('user_id'=>$_SESSION['ADMIN_LOGIN_SWISS'],
    					'user_type_id'=>$_SESSION['LOGIN_USER_TYPE'],
    					'action'=>'1',
    					'currdate'=>date("Y-m-d")
    				);
    			$value = "process_by='".json_encode($arr)."',";
    		
    			$this->query('UPDATE stock_order_status SET '.$value.'status="1" WHERE template_order_id = "'.$var[0].'" AND product_template_order_id = "'.$var[1].'"');
    			
    		}
    	
    	
    	}
    	public function getInternationalUserList() {
        $sql = "SELECT * FROM " . DB_PREFIX . " account_master WHERE user_type_id=4  ORDER BY user_name ASC";
        $data = $this->query($sql);
        //printr($data);die;
        return $data->rows;
    }
    	//end [kinjal]

/*	



	public function updateAccDeclinestatus($value,$cond,$status)
	{
	//	printr($value.'=='.$cond.'=='.$status);die;
		
		$value .= ",accept_decline_status='".$status."'";
		//echo "UPDATE " . DB_PREFIX . "multi_custom_order SET ".$value." WHERE ".$cond."";die;
		
			$sql = "UPDATE " . DB_PREFIX . "multi_custom_order SET ".$value." WHERE ".$cond."";
			//echo $sql;
			$data_update = $this->query($sql);
		//
		return $data_update;
	}*/

	
}
?>