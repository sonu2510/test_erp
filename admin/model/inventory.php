<?php
class inventory extends dbclass{
//kinjal
	public function addProduct($vander_name,$description,$delivery_date,$reminder_date)
	{ 
		if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$sqladmin = "SELECT user_id,user_type_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			$dataadmin = $this->query($sqladmin);
			$admin_user_id =  $dataadmin->row['user_id'];
			$admin_type_id =  $dataadmin->row['user_type_id'];
		}
		elseif($_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['LOGIN_USER_TYPE']==4)
		{
				$admin_user_id = 0;
				$admin_type_id = 0;
		}
		else
		{
			return false;
		}
	
		$sql = "INSERT INTO `".DB_PREFIX."purchase_indent` SET vander_id = '".$vander_name."',due_date='".$delivery_date."',
			reminder_date='".$reminder_date."',description='".$description."',added_by_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',added_by_type_id='".$_SESSION['LOGIN_USER_TYPE']."',admin_user_id='".$admin_user_id."',admin_type_id='".$admin_type_id."',status=0,added_date =NOW(),is_delete=0";
		$datasql=$this->query($sql);
		return $this->getLastId();
	}
	
	public function addmultipleitems($indent_id,$items_id,$itemsqty)
	{ 	
			$val = explode("-",$items_id);
			$tablename=$val[0];
			$id= $val[1];
			
		if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$sqladmin = "SELECT user_id,user_type_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			$dataadmin = $this->query($sqladmin);
			$admin_user_id =  $dataadmin->row['user_id'];
			$admin_type_id =  $dataadmin->row['user_type_id'];
		}
		elseif($_SESSION['LOGIN_USER_TYPE']==1 || $_SESSION['LOGIN_USER_TYPE']==4)
		{
			$admin_user_id = 0;
			$admin_type_id = 0;
		}
		else
		{
			return false;
		}
			
		$sql = "INSERT INTO `".DB_PREFIX."purchase_indent_items` SET indent_id = '".$indent_id."',gen_indent_id='INDT0000000".$indent_id."',item_id='".$id."',table_name='".$tablename."',total_qty='".$itemsqty."',status=0";
		
		$data=$this->query($sql);
		$id=$this->getLastId();
		
		$sql = "INSERT INTO purchase_indent_history SET purchase_indent_id='".$indent_id."',purchase_indent_items_id='".$id."',
pending_qty='".$itemsqty."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',admin_user_id='".$admin_user_id."',admin_type_id='".$admin_type_id."',date=NOW()";

		$data=$this->query($sql);
	
	}
	
	
	public function addProductStock($data,$items_id)
	{	//die;
		$val = explode("-",$items_id);
		//printr($val);
			$tablename=$val[0];
			$id= $val[1];
			$ono=$data['ono'];
		$sql = "INSERT INTO `" . DB_PREFIX . "inventory_stock` SET  order_no ='".$ono."',added_date='" .$data['date']. "',added_by_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',added_by_type_id='".$_SESSION['LOGIN_USER_TYPE']."', item_id='".$id."',table_name='".$tablename."',qty='".$data['itemsqty']."',status=0,is_delete=0";
		//echo $sql;die;
		$this->query($sql);
		
	}
	public function getTotalProductStk($user_id,$usertypeid)
	{	
		$con = '';
			if($_SESSION['LOGIN_USER_TYPE']==2)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
				$dataadmin = $this->query($sqladmin);
				$con =  'AND ph.admin_user_id = "'.$dataadmin->row['user_id'].'" AND ph.admin_user_id!=0';
				
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==4)
			{
				$con =  'AND (ph.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'" OR ph.user_type_id=4)';
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==1)
			{
				$con = '';
			}
			else
			{
				return false;
			}
		
		$sql =  "SELECT * from inventory_stock";
		$sql .=" GROUP BY order_no";
		
		$data = $this->query($sql);
		return $data->num_rows;
	}
	
	public function getProductStk($data,$user_id,$usertypeid)
	{
		$con = '';
			if($_SESSION['LOGIN_USER_TYPE']==2)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
				$dataadmin = $this->query($sqladmin);
				$con =  'AND ph.admin_user_id = "'.$dataadmin->row['user_id'].'" AND ph.admin_user_id!=0';
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==4)
			{
				$con =  'AND (ph.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'" OR ph.user_type_id=4)';
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==1)
			{
				$con = '';
			}
			else
			{
				return false;
			}
		$sql = "SELECT * from inventory_stock GROUP BY order_no";
		
		/*if (isset($data['sort'])) {
			$sql .= " GROUP BY c.indent_id ORDER BY " . $data['sort'];	
		} else {
			$sql .= " GROUP BY c.indent_id ORDER BY indnet_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
*/		
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
	public function getSum($t_name,$item_id,$order_no)
	{	
		$sql = "SELECT SUM(qty) as sum FROM " . DB_PREFIX . "inventory_stock WHERE table_name ='".$t_name."' AND item_id='".$item_id."' AND 
		order_no='".$order_no."' ";
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;
	}
	
	public function getaddProductDetails($id)
	{	
		$sql = "SELECT * FROM " . DB_PREFIX . "purchase_indent_items WHERE indent_id = '".$id."'";
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;
	}
	
	public function getVander(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "vander` WHERE is_delete = 0 AND status=1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getVanderName(){
		$sql = "SELECT c.*,p.vander_name,p.vander_id FROM purchase_indent as c,vander as p WHERE c.vander_id=p.vander_id AND c.vander_id=a.vander_name AND is_delet = '0' ";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function getItem($tablename,$id)
	{	
	if($tablename == 'product_accessorie' || $tablename == 'product_zipper' || $tablename == 'product_spout' || $tablename == 'product_material' )
	{
		if($tablename == 'product_accessorie')
		{
			$cond = 'product_accessorie_name';
			$unit = 'product_accessorie_unit';
		}
		elseif($tablename == 'product_zipper')
		{
			$cond = 'zipper_name';
			$unit = 'zipper_unit';
		}
		elseif($tablename == 'product_spout')
		{	
			$cond = 'spout_name';
			$unit = 'spout_unit';
		}
		else
		{
			$cond = 'material_name';
			$unit = 'material_unit';
		}
		$sql= "SELECT ".$cond." as itemname,".$unit." as unit FROM  ".$tablename." WHERE ".$tablename."_id = ".$id."";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;}
	}
	else
	{	
				if($tablename == 'ink_master')
				{
					$cond = 'm.make_name';
					$unit = 'a.ink_master_unit';
				}
				elseif($tablename == 'ink_solvent')
				{
					$cond = 'm.make_name';
					$unit = 'a.ink_solvent_unit';
				}
				elseif($tablename == 'adhesive')
				{
					$cond = 'm.make_name';
					$unit = 'a.adhesive_unit';
				}
				else
				{
					$cond = 'm.make_name';
					$unit = 'a.adhesive_solvent_unit';
				}
	
				$sql= "SELECT ".$cond." as itemname,".$unit." as unit FROM  ".$tablename." as a, product_make as m where a.".$tablename."_id = ".$id." OR m.make_id=a.make_id  ";
				$data = $this->query($sql);
					if($data->num_rows){
					return $data->row;}
	}}
	
	public function getitemslist($table)
	{ 
		$sql = "SELECT * FROM  ".$table." ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function getitemsOfInk($table)
	{	
		$sql = "SELECT a.*,m.make_id,m.make_name FROM  ".$table." as a,product_make as m  Where m.make_id=a.make_id ";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	
	public function getApprove($id,$table)
	{	
		$sql = "SELECT pi.purchase_indent_items_id,pi.item_id,ph.purchase_indent_items_id,SUM(ph.approve_qty) as app_qty FROM  purchase_indent_items as pi,purchase_indent_history as ph Where ph.purchase_indent_items_id=pi.purchase_indent_items_id AND pi.item_id='".$id."' AND pi.table_name='".$table."' ";
		//echo $sql;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function getStockQty($id,$table)
	{	
		$sql = "SELECT SUM(qty) as qty FROM inventory_stock Where item_id='".$id."' AND table_name='".$table."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function getId($id)
	{	
		$sql = "SELECT item_id FROM purchase_indent_items Where indent_id'".$id."' ";
		$data = $this->query($sql);
		//printr($data);
		echo $sql;
		if($data->num_rows){
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function getPdctStock($id)
	{	//echo $id;
		$sql = "SELECT order_no,added_by_id,added_by_type_id,added_date,qty,item_id,table_name FROM inventory_stock Where order_no='".$id."' 
		GROUP BY item_id";
		$data = $this->query($sql);
		//printr($data);
		//echo $sql;
		if($data->num_rows){
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function getTotalInventory($cond,$filter_data=array(),$user_id,$usertypeid,$status='')
	{	
		
		$con = '';
		if($status)
		{
			if($_SESSION['LOGIN_USER_TYPE']==2)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
				$dataadmin = $this->query($sqladmin);
				$con =  'AND ph.admin_user_id = "'.$dataadmin->row['user_id'].'" AND ph.admin_user_id!=0';
				
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==4)
			{
				$con =  'AND (ph.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'" OR ph.user_type_id=4)';
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
		
		$sql =  "SELECT c.*,p.vander_first_name,p.vander_last_name,p.company_name,p.vander_id,c.indent_id,pi.gen_indent_id,ph.* FROM purchase_indent_history as ph,purchase_indent as c,vander as p,purchase_indent_items as pi WHERE pi.indent_id=c.indent_id AND c.vander_id=p.vander_id AND ph.purchase_indent_items_id=pi.purchase_indent_items_id ".$cond." ".$con."  AND c.admin_user_id=ph.admin_user_id AND p.is_delete=0";
		if(!empty($filter_data)){
			
			if(!empty($filter_data['indent_id'])){
				$sql .= " AND pi.gen_indent_id = '".$filter_data['indent_id']."' ";
			}
			
			if(!empty($filter_data['vander_id'])){
				$sql .= " AND c.vander_id = '".$filter_data['vander_id']."' ";
			}
			if(!empty($filter_data['added_by_id'])){
				$sql .= " AND c.added_by_id = '".$filter_data['added_by_id']."' ";
			}		
		}
		$sql .=" GROUP BY c.indent_id";
		
		$data = $this->query($sql);
		return $data->num_rows;
	}
	
	public function getInventory($data,$cond,$filter_data=array(),$user_id,$usertypeid,$status='')
	{
		$con = '';
		if($status)
		{
			if($_SESSION['LOGIN_USER_TYPE']==2)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
				$dataadmin = $this->query($sqladmin);
				$con =  'AND ph.admin_user_id = "'.$dataadmin->row['user_id'].'" AND ph.admin_user_id!=0';
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==4)
			{
				$con =  'AND (ph.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'" OR ph.user_type_id=4)';
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

		$sql = "SELECT c.*,p.vander_first_name,p.vander_last_name,p.company_name,p.vander_id,c.indent_id,pi.gen_indent_id,ph.* FROM purchase_indent_history as ph,purchase_indent as c,vander as p,purchase_indent_items as pi WHERE pi.indent_id=c.indent_id AND c.vander_id=p.vander_id AND ph.purchase_indent_items_id=pi.purchase_indent_items_id ".$cond." ".$con." AND c.admin_user_id=ph.admin_user_id  AND p.is_delete=0";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['indent_id'])){
				$sql .= " AND pi.gen_indent_id = '".$filter_data['indent_id']."' ";
			}
			
			if(!empty($filter_data['vander_id'])){
				$sql .= " AND c.vander_id = '".$filter_data['vander_id']."' ";
			}
			if(!empty($filter_data['added_by_id'])){
				$sql .= " AND c.added_by_id = '".$filter_data['added_by_id']."' ";
			}		
		}
				
		
		if (isset($data['sort'])) {
			$sql .= " GROUP BY c.indent_id ORDER BY " . $data['sort'];	
		} else {
			$sql .= " GROUP BY c.indent_id ORDER BY indnet_id";	
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
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getTotalPending($order='',$cond,$filter_data=array(),$user_id,$usertypeid,$status='')
	{	
	
		$con = '';
		if($status)
		{
			if($_SESSION['LOGIN_USER_TYPE']==2)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
				$dataadmin = $this->query($sqladmin);
				$con =  'AND ph.admin_user_id = "'.$dataadmin->row['user_id'].'" AND ph.admin_user_id!=0';
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==4)
			{
				$con =  'AND (ph.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'" OR ph.user_type_id=4)';
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
		$sql =  "select ph.*,pi.*,c.*,v.vander_id,v.vander_first_name,v.vander_last_name,v.company_name from purchase_indent_history as ph,purchase_indent_items as pi,purchase_indent as c,vander as v where ".$order." c.indent_id=ph.purchase_indent_id AND pi.indent_id=c.indent_id ".$con." AND c.admin_user_id=ph.admin_user_id AND ph.purchase_indent_items_id=pi.purchase_indent_items_id AND c.vander_id=v.vander_id ".$cond."";
		
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['indent_id'])){
				$sql .= " AND pi.gen_indent_id = '".$filter_data['indent_id']."' ";
			}
			
			if(!empty($filter_data['vander_id'])){
				$sql .= " AND c.vander_id = '".$filter_data['vander_id']."' ";
			}
			if(!empty($filter_data['added_by_id'])){
				$sql .= " AND c.added_by_id = '".$filter_data['added_by_id']."' ";
			}		
		}
		$sql .= " GROUP BY c.indent_id";
		//echo $sql;
		$data = $this->query($sql);
		return $data->num_rows;
	}
	
	public function getPending($data,$order='',$cond,$filter_data=array(),$user_id,$usertypeid,$status='')
	{
		$con = '';
		if($status)
		{
			if($_SESSION['LOGIN_USER_TYPE']==2)
			{
				$sqladmin = "SELECT user_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
				$dataadmin = $this->query($sqladmin);
				$con =  'AND ph.admin_user_id = "'.$dataadmin->row['user_id'].'" AND ph.admin_user_id!=0 ';
			}
			elseif($_SESSION['LOGIN_USER_TYPE']==4)
			{
				$con =  'AND(ph.admin_user_id = "'.$_SESSION['ADMIN_LOGIN_SWISS'].'" OR ph.user_type_id=4)';
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
		$sql = "select ph.*,pi.*,c.*,v.vander_id,v.vander_first_name,v.vander_last_name,v.company_name from purchase_indent_history as ph,purchase_indent_items as pi,purchase_indent as c,vander as v where ".$order." c.indent_id=ph.purchase_indent_id AND pi.indent_id=c.indent_id ".$con." AND c.admin_user_id=ph.admin_user_id AND ph.purchase_indent_items_id=pi.purchase_indent_items_id AND c.vander_id=v.vander_id ".$cond."";
		
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['indent_id'])){
				$sql .= " AND pi.gen_indent_id = '".$filter_data['indent_id']."' ";
			}
			
			if(!empty($filter_data['vander_id'])){
				$sql .= " AND c.vander_id = '".$filter_data['vander_id']."' ";
			}
			if(!empty($filter_data['added_by_id'])){
				$sql .= " AND c.added_by_id = '".$filter_data['added_by_id']."' ";
			}		
		}
				
		
		if (isset($data['sort'])) {
			$sql .= " GROUP BY c.indent_id ORDER BY " .$data['sort'];	
		} else {
			$sql .= " GROUP BY c.indent_id";	
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
		
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getZone($country_id,$courier_id){
		
		$sql  = "SELECT cz.zone FROM `" . DB_PREFIX . "courier_zone_country` czc LEFT JOIN `" . DB_PREFIX . "courier_zone` cz ON cz.courier_zone_id = czc.courier_zone_id WHERE czc.country_id='".$country_id."' AND czc.courier_id='".$courier_id."' ";
		
		$data = $this->query($sql);
		
		if($data->row){
			return $data->row['zone'];
		}else{
			return '';	
		}
	}
	public function getPen($indent_id,$cond)
	{	
	$sql="SELECT c.indent_id,c.added_by_id,c.added_by_type_id,c.due_date,c.added_date,c.reminder_date,c.vander_id,c.description,v.vander_first_name,v.vander_last_name,v.vander_id,v.address,v.email_id,pi.item_id,pi.table_name,pi.total_qty,pi.gen_indent_id,ph.history_id,ph.date,ph.purchase_indent_id,
SUM(ph.rec_qty) as receive_qty,ph.pending_qty,SUM(ph.cancle_qty) as can_qty,SUM(ph.approve_qty) as app_qty,ph.status,ph.approve_qty,ph.review,ph.purchase_indent_items_id,ph.user_id,ph.user_type_id from purchase_indent as c,purchase_indent_items as pi,vander as v,purchase_indent_history as ph WHERE ph.purchase_indent_id = '" .(int)$indent_id. "' AND c.vander_id = v.vander_id AND c.indent_id = pi.indent_id AND ph.purchase_indent_id = pi.indent_id AND pi.purchase_indent_items_id = ph.purchase_indent_items_id ".$cond."";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		
	}
	
	public function getInven($indent_id,$cond='',$order='',$status='')
	{
	
		$sql="select a.* from (SELECT c.indent_id,c.added_by_id,c.added_by_type_id,c.due_date,c.added_date,c.reminder_date,c.vander_id,c.description,p.vander_first_name,p.vander_last_name,p.address,p.email_id,pi.item_id,pi.table_name,pi.total_qty,pi.gen_indent_id,ph.history_id,ph.date,ph.purchase_indent_id,ph.rec_qty,ph.pending_qty,ph.cancle_qty,ph.approve_qty,ph.status,ph.purchase_indent_items_id,ph.user_id,ph.user_type_id FROM purchase_indent as c,vander as p,purchase_indent_items as pi,purchase_indent_history as ph WHERE ".$order." c.indent_id = '" .(int)$indent_id. "' AND c.vander_id=p.vander_id AND pi.indent_id = c.indent_id AND ph.status='".$status."' AND ph.purchase_indent_items_id = pi.purchase_indent_items_id  ".$cond.") as a Group BY a.purchase_indent_items_id";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getName($k,$table)
	{
		$sql = "SELECT * FROM  ".$table." WHERE ".$k." ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getNameR($k,$table)
	{
		$sql = "SELECT a.*,m.make_name FROM  ".$table." as a,product_make as m WHERE ".$k." OR m.make_id=a.make_id";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}

	public function getRec($purchase_indent_items_id,$cond='')
	{
		$sql="SELECT SUM(rec_qty) as receive_qty,user_id,user_type_id FROM purchase_indent_history WHERE 
		purchase_indent_items_id='".$purchase_indent_items_id."' ".$cond."";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	
	public function getRecive($purchase_indent_items_id,$cond)
	{
		$sql="SELECT SUM(rec_qty) as max_qty,user_id,user_type_id,rec_qty FROM purchase_indent_history WHERE 
		purchase_indent_items_id='".$purchase_indent_items_id."' AND rec=2";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}	
	
		public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			
			$sql = "SELECT u.user_name, co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			
			$sql = "SELECT co.country_name, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getPurchase_indent($indent_id)
	{
		$sql = "SELECT pi.*,v.* FROM purchase_indent as pi,vander as v WHERE pi.indent_id = '".$indent_id."' AND v.vander_id=pi.vander_id";
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
	
	public function updateindentstatus($status,$indent_id,$receiveqty,$rec_qty,$review='',$purchase_indent_items_id='',$appqty='',$h_qty='',$cancel='',$pending='',$pen_qty='',$cancelqty='')
	{	
		
		if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$sqladmin = "SELECT user_id,user_type_id FROM ".DB_PREFIX."employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."'";
			$dataadmin = $this->query($sqladmin);
			$admin_user_id =  $dataadmin->row['user_id'];
			$admin_type_id =  $dataadmin->row['user_type_id'];
		}
		elseif($_SESSION['LOGIN_USER_TYPE'] == 4 || $_SESSION['LOGIN_USER_TYPE']==1)
		{
			$admin_user_id = 0;
			$admin_type_id = 0;
		}
		else
		{
				return false;
		}
		
		$approve=$rec_qty-$appqty;
		$cancleq=$rec_qty-$cancelqty;
		$pen=$pending-$pen_qty;
		if(($approve==0 || $cancleq==0) && $pen==0)
			{	
				$sql1 = "UPDATE ".DB_PREFIX."purchase_indent_items SET status=1 WHERE purchase_indent_items_id='".$purchase_indent_items_id."' ";
				$data1 = $this->query($sql1);
			}
	
			if($status==1)
			{	
				$sql2 = "UPDATE ".DB_PREFIX."purchase_indent_history SET pending_qty=0,status=1 WHERE history_id='".$h_qty."' ";
				$data2 = $this->query($sql2);
				$sql3="Select rec_qty from purchase_indent_history where history_id='".$h_qty."'";
				$data3 = $this->query($sql3);
				$rec_qty= $data3->row['rec_qty'];
				$cancel_qty=$cancelqty;
				$receiveqty=$rec_qty-$cancelqty;
				$pending_qty=$pen_qty;
				$cancel=$review;
			}
			if($status==3)
			{			
					$cancel_qty=$pen_qty;
					$pending_qty='';
			}
			
			if($status==0)
			{	$sql2 = "UPDATE ".DB_PREFIX."purchase_indent_history SET pending_qty=0,status=1 WHERE history_id='".$h_qty."' ";
				$data2 = $this->query($sql2);
				$sql3="Select rec_qty from purchase_indent_history where history_id='".$h_qty."'";
				$data3 = $this->query($sql3);
				$rec_qty= $data3->row['rec_qty'];
				$app=round($appqty);
				$receiveqty=$rec_qty-$app;
				$pending_qty=$pen_qty;
				$cancel_qty='';
			}
			$rec=0;
			if($status==2)
			{	
				$rec=2;
				$pending_qty=$pen_qty-$receiveqty;
				$cancel_qty='';
			}
			$sql="INSERT INTO purchase_indent_history SET purchase_indent_id='".$indent_id."',purchase_indent_items_id='".$purchase_indent_items_id."',pending_qty='".$pending_qty."',rec_qty='".$receiveqty."',cancle_qty='".$cancel_qty."',approve_qty='".$appqty."',user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."',admin_user_id='".$admin_user_id."',admin_type_id='".$admin_type_id."',review='".$cancel."',date=NOW(),status=0,rec='".$rec."'";
			$data=$this->query($sql);
	}
	
	public function getHistory($purchase_indent_items_id,$cond)
	{
		$sql = "SELECT * FROM " . DB_PREFIX ."purchase_indent_history WHERE purchase_indent_items_id='".$purchase_indent_items_id."'  ".$cond."";
		$data = $this->query($sql);
		return $data->rows;
	}
	
	public function getUserList(){
		$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
		$data = $this->query($sql);
		return $data->rows;
	}
	
	public function removeItem($purchase_indent_items_id){
		$this->query("DELETE FROM " . DB_PREFIX ."purchase_indent_items WHERE purchase_indent_items_id='".$purchase_indent_items_id."'");
		$this->query("DELETE FROM " . DB_PREFIX ."purchase_indent_history WHERE purchase_indent_items_id='".$purchase_indent_items_id."'");
	}
	public function deleteIndent($indnet_id)
	{
		$this->query("DELETE FROM " . DB_PREFIX ."purchase_indent_items WHERE indent_id='".$indnet_id."'");
		$this->query("DELETE FROM " . DB_PREFIX ."purchase_indent_history WHERE purchase_indent_id='".$indnet_id."'");
		$this->query("DELETE FROM " . DB_PREFIX ."purchase_indent WHERE indent_id='".$indnet_id."'");
	}
	
}
?>