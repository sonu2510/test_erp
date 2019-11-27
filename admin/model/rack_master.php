<?php
class rack_master extends dbclass{
	// kinjal -->
	public function addRack($data){
		$sql = "INSERT INTO `" . DB_PREFIX . "rack_master` SET row = '".$data['row']."', column_no = '".$data['column']."', status = '" .(int)$data['status']. "', date_added = NOW()";
		$this->query($sql);
		return $this->getLastId();
	}
	
	public function updateRAck($rack_master_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "rack_master` SET row = '".$data['row']."', column_no = '".$data['column']."', status = '" .(int)$data['status']. "', date_modify = NOW() WHERE rack_master_id = '" .(int)$rack_master_id. "'";
		$this->query($sql);
	}
	
	public function getRackData($rack_master_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "rack_master` WHERE rack_master_id = '" .(int)$rack_master_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}

	 
	public function getActiveProductZippers()
	{
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductSpout()
	{
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function getActiveProductAccessorie()
	{
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveMake()
	{
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY make_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getPouchVolume()
	{
		$sql = "SELECT pouch_volume_id,volume FROM `" . DB_PREFIX . "pouch_volume` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY volume";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getPouchColor()
	{
		$sql = "SELECT pouch_color_id,color FROM `" . DB_PREFIX . "pouch_color` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY color";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getTotalRack($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "rack_master` WHERE is_delete = '0'";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['column_no'])){
				$sql .= " AND column_no='".$filter_data['column_no']."' ";
			}
			
			if(!empty($filter_data['row'])){
				$sql .= " AND row = '".$filter_data['row']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}			
		}
				
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getRackMaster($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "rack_master` WHERE is_delete = '0'";
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['column_no'])){
				$sql .= " AND column_no='".$filter_data['column_no']."' ";
			}
			
			if(!empty($filter_data['row'])){
				$sql .= " AND row = '".$filter_data['row']."' ";
			}
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' ";
			}			
		}

		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY rack_master_id";	
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
	
	public function getImageurl(){
		
		$sql = "select p_i.*,p.product_name from product_image as p_i,product as p where p.product_id=p_i.product_id and p_i.status=1 and p_i.is_delete=0";
		$data=$this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		
	}
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "rack_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE rack_master_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "rack_master` SET is_delete = '1', date_modify = NOW() WHERE rack_master_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function updateRackStatus($rack_master_id,$status_value){	
	   $sql = "UPDATE `" . DB_PREFIX . "rack_master` SET status = '" .(int)$status_value ."' WHERE rack_master_id = '".$rack_master_id ."' ";
	   $this->query($sql);	
	}
	
	public function updatePrice($data)
	{
		$sql = "UPDATE `".DB_PREFIX."stock_management` SET price='".$data['price']."' WHERE stock_id='".$data['stock_id']."'";
		$data=$this->query($sql);
		return $data;
	}
	//rohit
	public function getStockByGoodsId($goods_id) {
		$sql = "select * from `" . DB_PREFIX . "stock_management` where goods_id = '".$goods_id."'";
		//printr($sql);
		$data = $this->query($sql);
		if($data->num_rows) {
			return $data->rows;
		} else {
			return false;
		}
	}
	//end rohit
	//priya(23-4-2015)
	public function  addstock($post,$n=0)
	{ //printr($post);die;
		$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
		$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
			if($user_type_id== 2){
			    $user_type_id=$_SESSION['LOGIN_USER_TYPE'];
	            $user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
			    
			}else if( $user_type_id== 4){
		        $user_type_id=$_SESSION['LOGIN_USER_TYPE'];
                $user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
			    
			}else{
		    	$user_id=$post['added_user_id'];
		        $user_type_id=$post['user_type_id'];
			}
		    
		$roll_code=$rate='';
		$date="date_added='".date("Y-m-d")."'";
		if($n==1)
		{
			if(isset($post['product_code_id_add']))
			    $post['product_code_id'] = $post['product_code_id_add'];
		//	$user_id=$post['added_user_id'];
		//	$user_type_id=$post['user_type_id'];
			$rack_detail = $post['pallet'];
			$d = explode("=",$rack_detail);
			$post['row1']=$d[0];
			$post['col1']=$d[1];
			$post['goods_id']=$d[2];
			$roll_code=',roll_code = "'.$post['roll_code'].'"';
			$rate=',opening_value = "'.$post['rate'].'"';
			$date="date_added='".$post['date']."'";
		}
		if( $user_type_id== 2){
			//echo "emp";
				$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");
				$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
					//printr($userEmployee);
				$set_user_id = $parentdata->row['user_id'];
				$set_user_type_id = $parentdata->row['user_type_id'];
			}else{
				$set_user_id =$_SESSION['ADMIN_LOGIN_SWISS'];
				$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			}
		
		if($post['description']==3)
			$status = 2;
		else
			$status = 0;
		$sql = "INSERT INTO stock_management SET order_no = '".$post['orderno']."', my_order_no = '".$post['my_orderno']."',proforma_no='".$post['proforma_no']."',invoice_no='".$post['invoice_no']."',description = '".$post['description']."',product = '".$post['product_id']."',qty = '".$post['qty']."',row='".$post['row1']."',column_name='".$post['col1']."',goods_id='".$post['goods_id']."',company_name='".$post['company_name']."',product_code_id='".$post['product_code_id']."', status='".$status."',is_delete='0',date_added='".$post['date']."',date_modify='".$post['date']."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',added_user_id='".$user_id."',added_user_type_id='".$user_type_id."' $roll_code";
	//echo $sql;
		$data = $this->query($sql);
		
		$sql1="SELECT qty as tot_pur FROM stock_management WHERE product_code_id='".$post['product_code_id']."' AND user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND parent_id='0' AND $date AND is_delete=0 ORDER BY stock_id DESC LIMIT 1";
		//echo $sql1;
		$data1 = $this->query($sql1);
		//printr($data1);
		// next day date date_added='".date("y-m-d", strtotime(' +1 day'))
		$sql2="SELECT * FROM inventory_opening_stock WHERE user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$post['product_code_id']."' AND date_added=(SELECT MAX(date_added) FROM inventory_opening_stock WHERE user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$post['product_code_id']."')";
		$data2=$this->query($sql2);
		//printr($data2);
		if($data2->num_rows)
		{
			$opening_qty=$data2->row['opening_qty']+$data1->row['tot_pur'];
			
			if(strtotime($data2->row['date_added'])== strtotime(date('y-m-d')))
			{
				$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$opening_qty."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$post['product_code_id']."'$rate";
				//echo $sql_qry.'<br>if cond';
				$data3=$this->query($sql_qry);
				
			}
			else if(strtotime($data2->row['date_added']) < strtotime(date('y-m-d')))
			{
				$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$opening_qty."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$post['product_code_id']."'$rate";
				//echo $sql_qry.'<br>else if cond';
				$data3=$this->query($sql_qry);
			}
			else
			{
				$sql_qry="UPDATE inventory_opening_stock SET opening_qty='".$opening_qty."' $rate WHERE date_added='".date("y-m-d", strtotime(' +1 day'))."' AND user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$post['product_code_id']."'";
				//echo $sql_qry.'<br>else cond';
				$data3=$this->query($sql_qry);
			}
			
		}
		else
		{
			$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$data1->row['tot_pur']."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$post['product_code_id']."'$rate";
			$data3=$this->query($sql_qry);
		}
		
		//die;
	}
	
    public function getRowColumn(){
		$sql = "SELECT * FROM `". DB_PREFIX . "goods_master` WHERE status='1' AND is_delete = '0'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getstock($goods_id,$row,$col){
		$sql = "SELECT st.*,p.product_name FROM " . DB_PREFIX . "stock_management as st,product as p WHERE st.goods_id = '" .(int)$goods_id. "' AND st.row='".$row."' AND st.column_name='".$col."' AND st.product=p.product_id AND st.description!=2";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	//sonu upadate 11/01/2017 
	public function savedispatch_racknotify($data,$n=0)
	{
	    //printr($data);die;
        
		if($_SESSION['LOGIN_USER_TYPE'] == 2){
			//echo "emp";
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				//printr($userEmployee);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		}
		//add courier    by sonu 29-3-2017 courier
		$courier_id=0;
		if($data['courier_id'] == '0')
		{
			if (isset($data['courier']) && !empty ($data['courier'])) {
			    $courier_id = $data['courier'];					
			}
	
		}else{
				$courier_id = $data['courier_id'];	
		}
	
		$row_col=explode('=',$data['alldata']);
		$partial = explode(',', $data['stock_id']);
		$final = array();
		$dispatch_qty=0;
			//printr($partial);
	//	printr($data);
		array_walk($partial, function($val,$key) use(&$final){
			list($key, $value) = explode(':', $val);
			$sql3 = "SELECT SUM(dispatch_qty) dispatch_qty FROM " . DB_PREFIX . "stock_management WHERE parent_id=".$value."";
			$data3 = $this->query($sql3);
			//printr($data3);
			if($data3->num_rows){
			    $dispatch_qty=$data3->row['dispatch_qty'];
			}
			$qty=$key-$dispatch_qty;
			
			///printr($value);
			$sql_find = "SELECT box_no FROM " . DB_PREFIX . "stock_management WHERE stock_id=".$value."";
			$find = $this->query($sql_find);
			
			if($qty>0)
				$final[] = array('id'=>$value,'qty'=>$key,'box_no'=>$find->row['box_no']);	
		});
		
		if($n==0)
			$dis_qty=$data['dispatch_qty'];
			
		$dis_qty1=0;
	//printr($qty);
//	die;
	//printr($dis_qty);
/*	if(!isset($data['box_no']))
		$data['box_no']='';*/
	
		foreach($final as $record)
		{	$box='';
			if($n==0)
			{
				if($dis_qty>$record['qty'])
					$final_dis_qty=$record['qty'];
				else
					$final_dis_qty=$dis_qty;
			}
			else
			{				
				$box= ', box_no = "'.$record['box_no'].'"';
				$dis_qty=$final_dis_qty=$data['dispatch_qty'][$record['id']];
				$dis_qty1+=$data['dispatch_qty'][$record['id']];
			}
				
		//printr($final_dis_qty);
			

		$sql="INSERT INTO stock_management SET segments = '".$data['segments']."',proforma_no='".$data['proforma_no']."',invoice_no='".$data['invoice_no']."',dispatch_qty='".$final_dis_qty."',parent_id='".$record['id']."',product='".$data['product_id']."',goods_id='".$row_col[2]."' , row='".$row_col[0]."' ,column_name='".$row_col[1]."',company_name='".addslashes($data['company_name'])."',description=2,status=1,date_added=NOW(),date_modify=NOW(), user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data['product_code_id']."',courier = '".$courier_id."',india_courier='".$data['courier_india']."', courier_amount = '".$data['courier_amount']."' $box";
		//if($_SESSION['LOGIN_USER_TYPE']!=2 && $_SESSION['ADMIN_LOGIN_SWISS']!='56')
            $data1=$this->query($sql);
        
			if($n==0)
			{
				if($dis_qty > $final_dis_qty) 
					$dis_qty=$dis_qty-$final_dis_qty;	
				else
					break;		
			}
			else
			{
				$get_rem_qty = $this->query("SELECT rack_remaining_qty FROM sales_invoice_product WHERE invoice_product_id='".$data['invoice_product_id']."'");
				$remaining_qty=$get_rem_qty->row['rack_remaining_qty']-$dis_qty1;
			}		
				
		}
		if($n==0)
			$remaining_qty=$data['sales_qty']-$data['dispatch_qty'];
			
		//printr($remaining_qty);
		if(isset($data['sales_status']) && isset($data['sales_status'])==1)
		{
			$sql_n="UPDATE invoice_color_test SET rack_status='".$remaining_qty."' WHERE invoice_product_id='".$data['invoice_product_id']."'";
		}
		else
		{
			$sql_n="UPDATE sales_invoice_product SET rack_remaining_qty='".$remaining_qty."' WHERE invoice_product_id='".$data['invoice_product_id']."'";
		}
		//if($_SESSION['LOGIN_USER_TYPE']!=2 && $_SESSION['ADMIN_LOGIN_SWISS']!='56')
		    $result=$this->query($sql_n);	
		
            
		$sql4="SELECT dispatch_qty,stock_id FROM stock_management WHERE product_code_id='".$data['product_code_id']."' AND user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND parent_id!='0' AND date_added='".date("y-m-d")."' AND is_delete=0 ORDER BY stock_id DESC LIMIT 1";
		$data4 = $this->query($sql4);
		
		// next day date date_added='".date("y-m-d", strtotime(' +1 day'))
		$sql2="SELECT * FROM inventory_opening_stock WHERE user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$data['product_code_id']."' AND date_added=(SELECT MAX(date_added) FROM inventory_opening_stock WHERE user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$data['product_code_id']."')";
		$data2=$this->query($sql2);
		
		    if($data2->num_rows)
    		{
    			$opening_qty=$data2->row['opening_qty']-$data4->row['dispatch_qty'];
    			
    			if(strtotime($data2->row['date_added'])== strtotime(date('y-m-d')))
    			{
    				$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$opening_qty."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data['product_code_id']."'";
    				$data3=$this->query($sql_qry);
    				
    			}
    			else if(strtotime($data2->row['date_added']) < strtotime(date('y-m-d')))
    			{
    				$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$opening_qty."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data['product_code_id']."'";
    				$data3=$this->query($sql_qry);
    			}
    			else
    			{
    				$sql_qry="UPDATE inventory_opening_stock SET opening_qty='".$opening_qty."' WHERE date_added='".date("y-m-d", strtotime(' +1 day'))."' AND user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$data['product_code_id']."'";
    				$data3=$this->query($sql_qry);
    			}
    		}
	
		
	}
	public function savedispatch($data)
	{
		//printr($data);die;
		if($_SESSION['LOGIN_USER_TYPE'] == 2){
			//echo "emp";
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				//printr($userEmployee);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		}
		
		
		$row_col=explode('-',$data['row_column']);
		$partial = explode(',', $data['stock_id']);
		$final = array();
		$dispatch_qty=0;
		array_walk($partial, function($val,$key) use(&$final){
			list($key, $value) = explode(':', $val);
			$sql3 = "SELECT SUM(dispatch_qty) dispatch_qty FROM " . DB_PREFIX . "stock_management WHERE parent_id=".$value."";
			$data3 = $this->query($sql3);
			if($data3->num_rows){
			$dispatch_qty=$data3->row['dispatch_qty'];
			}
			$qty=$key-$dispatch_qty;
			if($qty>0)
				$final[] = array('id'=>$value,'qty'=>$key);	
		});
		$dis_qty=$data['dispatch_qty'];
		//printr($dis_qty);//die;
		if($data['courier_id'] == '0')
		{
			if (isset($data['courier']) && !empty ($data['courier'])) {
			$courier_id = $data['courier'];					
			}
	
		}else{
				$courier_id = $data['courier_id'];	
		}
		foreach($final as $record)
		{	
			if($dis_qty>$record['qty'])
				$final_dis_qty=$record['qty'];
			else
				$final_dis_qty=$dis_qty;
				
		//printr($final_dis_qty);
			
		$sql="INSERT INTO stock_management SET proforma_no='".$data['proforma_no']."',invoice_no='".$data['invoice_no']."',order_no='".$data['order_no']."',my_order_no='".$data['my_order_no']."',box_no='".$data['box_no']."',container_no='".$data['container_no']."',dispatch_qty='".$final_dis_qty."',parent_id='".$record['id']."',product='".$data['product']."',goods_id='".$data['goods_id']."' , row='".$row_col[0]."' ,column_name='".$row_col[1]."',company_name='".addslashes($data['company_name'])."',description=2,status=1,date_added='".$data['date']."',date_modify='".$data['date']."', user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data['product_code_id']."',track_id = '".$data['track_id']."',courier = '".$courier_id."',courier_amount = '".$data['courier_amount']."'";
		//	echo $sql;die;
			$data1=$this->query($sql);	
			if($dis_qty > $final_dis_qty) 
				$dis_qty=$dis_qty-$final_dis_qty;	
			else
				break;						
		}
		
		$sql1="SELECT dispatch_qty,stock_id FROM stock_management WHERE product_code_id='".$data['product_code_id']."' AND user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND parent_id!='0' AND date_added='".date("y-m-d")."' AND is_delete=0 ORDER BY stock_id DESC LIMIT 1";
		$data1 = $this->query($sql1);
		
		// next day date date_added='".date("y-m-d", strtotime(' +1 day'))
		$sql2="SELECT * FROM inventory_opening_stock WHERE user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$data['product_code_id']."' AND date_added=(SELECT MAX(date_added) FROM inventory_opening_stock WHERE user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$data['product_code_id']."')";
		$data2=$this->query($sql2);
		
		if($data2->num_rows)
		{
			$opening_qty=$data2->row['opening_qty']-$data1->row['dispatch_qty'];
			
			if(strtotime($data2->row['date_added'])== strtotime(date('y-m-d')))
			{
				$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$opening_qty."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data['product_code_id']."'";
				$data3=$this->query($sql_qry);
				
			}
			else if(strtotime($data2->row['date_added']) < strtotime(date('y-m-d')))
			{
				$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$opening_qty."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data['product_code_id']."'";
				$data3=$this->query($sql_qry);
			}
			else
			{
				$sql_qry="UPDATE inventory_opening_stock SET opening_qty='".$opening_qty."' WHERE date_added='".date("y-m-d", strtotime(' +1 day'))."' AND user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$data['product_code_id']."'";
				$data3=$this->query($sql_qry);
			}
		}
		
		
		//$sql="INSERT INTO stock_management SET proforma_no='".$data['proforma_no']."',invoice_no='".$data['invoice_no']."',order_no='".$data['order_no']."',	dispatch_qty='".$data['dispatch_qty']."',parent_id='".$data['stock_id']."',product='".$data['product']."',goods_id='".$data['goods_id']."' , row='".$row_col[0]."' , valve='".$data['valve']."',zipper_id='".$data['zipper_id']."',spout_id='".$data['spout_id']."',color_id='".$data['color_id']."', accessorie_id='".$data['accessorie_id']."',make_id='".$data['make_id']."',size_id='".$data['size_id']."',column_name='".$row_col[1]."',company_name='".$data['company_name']."',description=2,status=1,date_added='".$data['date']."',date_modify='".$data['date']."',	user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
	}
	public function gettotaldispatch($stock_id)
	{
		$sql="SELECT SUM(dispatch_qty) as total FROM stock_management WHERE is_delete=0 AND parent_id IN (" .$stock_id. ")";
		//echo $sql;
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
	public function gettotaldispatchChild($stock_id)
	{
		$sql="SELECT sm.* FROM stock_management AS sm WHERE sm.is_delete=0 AND sm.parent_id = " .$stock_id. " ORDER BY date_added ASC";
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
	public function getdispatchdetail($user_id,$user_type_id,$stock_id)
	{	
		//echo $user_type_id;SELECT sm.*,p.product_name FROM stock_management as sm,product as p WHERE sm.product=p.product_id AND sm.stock_id IN (".$stock_id.") AND sm.user_id='".$user_id."' AND sm.user_type_id='".$user_type_id."'
		if($user_type_id == 1 && $user_id == 1){
			$sql="SELECT sm.*,p.product_name FROM stock_management as sm,product as p WHERE sm.is_delete=0 AND sm.product=p.product_id AND sm.stock_id IN (".$stock_id.") ";
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
				//printr($userEmployee);
				$set_user_id = $user_id;
				$set_user_type_id = $user_type_id;
			}
			$str = '';
			if($userEmployee){
				$str = 'AND ( (sm.user_id='.(int)$set_user_id.' AND sm.user_type_id='.(int)$set_user_type_id.') OR (sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ))';
			}
			//echo $str;
			$sql="SELECT sm.*,p.product_name FROM stock_management as sm,product as p WHERE sm.is_delete=0 AND sm.product=p.product_id AND sm.stock_id IN  (".$stock_id.") $str ";
		}
		
		$sql.=' GROUP BY sm.stock_id';
		
		//echo $sql;
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;			
	}
	
	public function searchRecords($data)
	{	
		$sql="SELECT sm.*,p.product_name FROM stock_management as sm,product as p,product_code as pc WHERE sm.product=p.product_id AND pc.product_code_id=sm.product_code_id AND ( sm.stock_id IN (".$data['stock_id'].") OR sm.parent_id IN (".$data['stock_id'].") )";		
		
		if(!empty($data['proforma_no'])){
			$sql .= " AND sm.proforma_no='".$data['proforma_no']."' ";
		}
		if(!empty($data['orderno'])){
			$sql .= " AND sm.orderno = '".$data['orderno']."' ";
		}
		if($data['invoice_no'] != ''){
			$sql .= " AND sm.invoice_no = '".$data['invoice_no']."' ";
		}	
		if($data['company_name'] != ''){
			$sql .= " AND sm.company_name = '".$data['company_name']."' ";
		}		
		if($data['product_code'] != ''){
			$sql .= " AND pc.product_code = '".$data['product_code']."' ";
		}	
		if($data['date'] != ''){
			$sql .= " AND sm.date_added = '".$data['date']." ' ";
		}			
		$sql.=' GROUP BY sm.stock_id ORDER BY date_added ASC';
		
		//echo $sql;
		$data=$this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
			
	}
//priya(25-4-15)
	public function getrackdetail($user_id,$user_type_id,$goods_id,$row,$col,$option='',$filter_data=array(),$f_date='',$t_date=''){
		
		$str_date_sql=$goods_data='';
		if(!empty($f_date) && !empty($t_date)){
	    $str_date_sql=" AND sm.date_added BETWEEN '".$f_date."' AND '".$t_date."'  ";    
	    }
	    if(!empty($row) && !empty($col)){
	        $goods_data=" AND sm.row='".$row."' AND sm.column_name='".$col."'  ";    
	    }
    	    
		if($user_type_id == 1 && $user_id == 1){
		//	$sql = "SELECT sm.*,p.product_name FROM " . DB_PREFIX . "stock_management as sm,product as p WHERE p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' AND sm.row='".$row."' AND sm.column_name='".$col."'  AND parent_id=0 GROUP BY sm.stock_id";
			$sql = "SELECT sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id,p.product_name FROM stock_management as sm,product as p,product_code as pc WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' AND pc.product_code_id = sm.product_code_id $goods_data AND parent_id=0 $str_date_sql ";
			//echo $sql;
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
				$str = 'AND  ( (sm.user_id = '.(int)$set_user_id.' AND sm.user_type_id = '.(int)$set_user_type_id.') OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) )';
			}
			
	//	$sql = "SELECT sm.*,p.product_name FROM " . DB_PREFIX . "stock_management as sm,product as p WHERE p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' AND sm.row='".$row."' AND sm.column_name='".$col."' AND parent_id=0  AND  $str GROUP BY sm.stock_id";
	
	$sql = "SELECT sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id,p.product_name FROM stock_management as sm,product as p,product_code as pc WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' $goods_data AND pc.product_code_id = sm.product_code_id AND parent_id=0 $str $str_date_sql ";
		//echo $sql;
		}
		if(!empty($filter_data)){
			
			if(!empty($filter_data['proforma_no'])){
				$sql .= " AND sm.proforma_no='".$filter_data['proforma_no']."' ";
			}
			
			if(!empty($filter_data['orderno'])){
				$sql .= " AND sm.orderno = '".$filter_data['orderno']."' ";
			}
			
			if($filter_data['invoice_no'] != ''){
				$sql .= " AND sm.invoice_no = '".$filter_data['invoice_no']."' ";
			}	
			if($filter_data['company_name'] != ''){
				$sql .= " AND sm.company_name = '".$filter_data['company_name']."' ";
			}		
			if($filter_data['product_code'] != ''){
				$sql .= " AND pc.product_code = '".$filter_data['product_code']."' ";
			}	
			if($filter_data['date'] != ''){
				$sql .= " AND sm.date_added = '".$filter_data['date']." ' ";
			}			
		}
		//$sql.=' GROUP BY sm.zipper_id,sm.spout_id,sm.valve,sm.accessorie_id,sm.make_id,sm.size_id,sm.color_id,sm.product';
		$sql.=' GROUP BY sm.product_code_id';
		if (isset($option['sort'])) {
			$sql .= " ORDER BY " . $option['sort'];	
		} else {
			$sql .= " ORDER BY date_added";	
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
		if($data->num_rows)
			return $data;
		else{
			return false;
		}	
	}
		public function getrackdetailTEST($user_id,$user_type_id,$goods_id,$row,$col,$option='',$filter_data=array(),$f_date,$t_date){
		
		$str_date_sql=$goods_data='';
		if(!empty($f_date) && !empty($t_date)){
	    $str_date_sql=" AND sm.date_added BETWEEN '".$f_date."' AND '".$t_date."'  ";    
	    }
	    if(!empty($row) && !empty($col)){
	        $goods_data=" AND sm.row='".$row."' AND sm.column_name='".$col."'  ";    
	    }
    	    
		if($user_type_id == 1 && $user_id == 1){
		//	$sql = "SELECT sm.*,p.product_name FROM " . DB_PREFIX . "stock_management as sm,product as p WHERE p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' AND sm.row='".$row."' AND sm.column_name='".$col."'  AND parent_id=0 GROUP BY sm.stock_id";
			$sql = "SELECT sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id,p.product_name FROM stock_management as sm,product as p,product_code as pc WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' AND pc.product_code_id = sm.product_code_id $goods_data AND parent_id=0 $str_date_sql ";
			//echo $sql;
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
				$str = 'AND  ( (sm.user_id = '.(int)$set_user_id.' AND sm.user_type_id = '.(int)$set_user_type_id.') OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) )';
			}
			
	//	$sql = "SELECT sm.*,p.product_name FROM " . DB_PREFIX . "stock_management as sm,product as p WHERE p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' AND sm.row='".$row."' AND sm.column_name='".$col."' AND parent_id=0  AND  $str GROUP BY sm.stock_id";

	$sql = "SELECT GROUP_CONCAT(stock_id ) grouped_s_id,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,pc.description as code_product,rl.rack_label,pc.product_code,p.product_name,sm.row,sm.column_name,sum(sm.qty) tot_qty FROM stock_management as sm,product as p,product_code as pc,rack_label_details as rl WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' $goods_data AND pc.product_code_id = sm.product_code_id AND parent_id=0 $str $str_date_sql AND sm.row=rl.row AND sm.column_name=rl.column_name AND sm.goods_id=rl.goods_id";
		//echo $sql;
		} 
		if(!empty($filter_data)){
			
			if(!empty($filter_data['proforma_no'])){
				$sql .= " AND sm.proforma_no='".$filter_data['proforma_no']."' ";
			}
			
			if(!empty($filter_data['orderno'])){
				$sql .= " AND sm.orderno = '".$filter_data['orderno']."' ";
			}
			
			if($filter_data['invoice_no'] != ''){
				$sql .= " AND sm.invoice_no = '".$filter_data['invoice_no']."' ";
			}	
			if($filter_data['company_name'] != ''){
				$sql .= " AND sm.company_name = '".$filter_data['company_name']."' ";
			}		
			if($filter_data['product_code'] != ''){
				$sql .= " AND pc.product_code = '".$filter_data['product_code']."' ";
			}	
			if($filter_data['date'] != ''){
				$sql .= " AND sm.date_added = '".$filter_data['date']." ' ";
			}			
		}
		//$sql.=' GROUP BY sm.zipper_id,sm.spout_id,sm.valve,sm.accessorie_id,sm.make_id,sm.size_id,sm.color_id,sm.product';
		$sql.=' GROUP BY sm.row,sm.column_name,sm.product_code_id';
		if (isset($option['sort'])) {
			$sql .= " ORDER BY " . $option['sort'];	
		} else {
			$sql .= " ORDER BY rl.rack_label + 0";	
		}
        
        //$sql.=", rl.rack_label + 0";
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
		if($data->num_rows)
			return $data;
		else{
			return false;
		}	
	}
		
	public function deleterecord($data)
	{
		$sql = "DELETE  FROM `" . DB_PREFIX . "stock_management` WHERE stock_id IN (" .implode(",",$data). ")";
	    $this->query($sql);
	}
	
	public function getproduct($product_id){
		$sql = "SELECT product_name FROM `" . DB_PREFIX . "product` WHERE product_id = '" .(int)$product_id ."'";
		$data = $this->query($sql);
		if($data->num_rows)
			return $data->row;
		else{
			return false;
		}	
			
	}
	
	public function getCourierCombo($selected=""){
		$sql = "SELECT * FROM " . DB_PREFIX . "courier WHERE status = '1' AND is_delete = '0'";
		$data = $this->query($sql);
		$html = '';
		if($data->num_rows){
			$html = '';
			$html .= '<select name="courier_id" id="courier_id" class="form-control" style="width:70%"  onchange="getcourier_value()">';
					$html .= '<option value="">Select Courier</option>
							  <option value="0" >Other</option>';
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
	
	//copied from(template order);
    public function getUser($user_id,$user_type_id)
	{
		$cond = '';
		
		if($user_type_id==2)
		{
			$sql = "SELECT ib.stock_order_price,ib.user_name, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.employee_id, ib.user_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email,ib.user_type_id,ib.user_id FROM " . DB_PREFIX ."employee ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '".(int)$user_type_id."' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ".$cond."";
		}
		elseif($user_type_id == 4)
		{
			$sql = "SELECT ib.stock_order_price,ib.user_name, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id as user_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '".(int)$user_type_id."' AND ad.user_id = '".(int)$user_id."' ) LEFT  JOIN  " . DB_PREFIX ." country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ".$cond." ";
		}
		elseif($user_type_id == 1){
			//$sql = "SELECT u.user_name,u.first_name,u.last_name,am.user_type_id,am.user_id FROM " . DB_PREFIX ."user u, " . DB_PREFIX ."account_master am WHERE u.user_id = '".(int)$user_id."' AND am.user_id = '".(int)$user_id."' AND am.user_type_id = '".(int)$user_type_id."'";
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
	//priya//sonu 8/12/2016 added user type id =1 
	public function getUserEmployeeIds($user_type_id,$user_id){
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
	
	public function getZipper($product_zipper_id) 
	{
		$sql = "select * from " . DB_PREFIX ."product_zipper where product_zipper_id = '".$product_zipper_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	public function getSpout($product_spout_id) 
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
	public function getAccessorie($product_accessorie_id) 
	{
		$sql = "select * from " . DB_PREFIX ."product_accessorie where product_accessorie_id = '".$product_accessorie_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	public function getMake($make_id) 
	{
		$sql = "select * from " . DB_PREFIX ."product_make where make_id = '".$make_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	public function getColor($pouch_color_id) 
	{
		$sql = "select * from " . DB_PREFIX ."pouch_color where pouch_color_id = '".$pouch_color_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
	public function getSize($size_id) 
	{
		$sql = "select * from " . DB_PREFIX ."pouch_volume where pouch_volume_id = '".$size_id."'";
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}
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
	public function getProductCd($product_code)
	{
		//echo $product_code;
		$result=$this->query("SELECT pc.product_code, pc.product_code_id, pc.description, clr.color, pc.volume, pc.measurement,pc.product  FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr WHERE pc.product_code LIKE '%".$product_code."%' AND pc.is_delete=0 AND pc.color=clr.pouch_color_id AND pc.status=1");
		return $result->rows;
	}
	public function getProductCode($product_code_id)
	{
		//echo $product_code;
		$result=$this->query("SELECT pc.*,p.product_name,pm.make_name,pm.make_id,c.color,c.pouch_color_id,tm.measurement,pz.zipper_name,pz.product_zipper_id,ps.spout_name,ps.product_spout_id,pa.product_accessorie_name,pa.product_accessorie_id FROM `" . DB_PREFIX . "product_code` AS pc LEFT JOIN product AS p ON pc.product=p.product_id LEFT JOIN product_make pm ON pc.make_pouch=pm.make_id LEFT JOIN pouch_color AS c ON c.pouch_color_id=pc.color LEFT JOIN template_measurement AS tm ON pc.measurement=tm.product_id  LEFT JOIN product_zipper AS pz ON pc.zipper=pz.product_zipper_id LEFT JOIN product_spout AS ps ON ps.product_spout_id=pc.spout LEFT JOIN product_accessorie AS pa ON pa.product_accessorie_id=pc.accessorie WHERE pc.product_code_id='".$product_code_id."' AND pc.is_delete=0 AND pc.status=1 ");
		//echo "SELECT pc.*,p.product_name,pm.make_name,pm.make_id,c.color,c.pouch_color_id,tm.measurement,pz.zipper_name,pz.product_zipper_id,ps.spout_name,ps.product_spout_id,pa.product_accessorie_name,pa.product_accessorie_id FROM `" . DB_PREFIX . "product_code` AS pc LEFT JOIN product AS p ON pc.product=p.product_id LEFT JOIN product_make pm ON pc.make_pouch=pm.make_id LEFT JOIN pouch_color AS c ON c.pouch_color_id=pc.color LEFT JOIN template_measurement AS tm ON pc.measurement=tm.product_id  LEFT JOIN product_zipper AS pz ON pc.zipper=pz.product_zipper_id LEFT JOIN product_spout AS ps ON ps.product_spout_id=pc.spout LEFT JOIN product_accessorie AS pa ON pa.product_accessorie_id=pc.accessorie WHERE pc.product_code_id='".$product_code_id."' AND pc.is_delete=0 AND pc.status=1 ";
		return $result->row;
	}
	public function updatestock($data)
	{
		$sql = "UPDATE `" . DB_PREFIX . "stock_management` SET is_delete = '1', date_modify = NOW() WHERE stock_id IN (" .implode(",",$data). ")";
		$this->query($sql);
	
	}
	
	public function showInvoice($invoice_no,$product_code_id)
	{
		$sql="Select si.invoice_no,spi.product_code_id from sales_invoice as si, sales_invoice_product as spi where si.invoice_no='".$invoice_no."' AND  si.is_delete=0 AND si.gen_status='0' AND spi.product_code_id='".$product_code_id."' AND spi.invoice_id=si.invoice_id";
		
		//echo $sql;
		//echo "<br>";
		$dat=$this->query($sql);
		//printr($dat);die;
		if($dat->num_rows){
			$sql_no = "SELECT invoice_no,product_code_id FROM stock_management where invoice_no='".$dat->row['invoice_no']."' AND  is_delete=0 AND product_code_id='".$dat->row['product_code_id']."'";
			//echo $sql_no;
			$sql_data=$this->query($sql_no);
			//printr($sql_data);die;
			if($sql_data->num_rows){
					return 	$sql_data->row['invoice_no'];
			}else{
				return 1;
			}
		}else{
			return 0;
		}
	}
	// mansi 22-1-2016 (made for rack_label in rack master )
	
	public function getRackLabel($goods_master_id,$raw,$col){
	
		//echo $col;
		$sql = "SELECT rack_label,goods_id,row, column_name FROM stock_management WHERE row='".$raw."' AND column_name='".$col."' AND goods_id='".$goods_master_id."' GROUP BY goods_id, row, column_name";
		//$sql = "UPDATE stock_management_new SET rack_label='".$rack_label."' WHERE row='".$row."' AND column_name='".$column_name."' AND goods_id='".$goods_id."' ";
		//echo $sql;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}
	}

	public function update_value($goods_id,$raw,$col,$rack_label){
		
		$sql ="UPDATE stock_management SET rack_label='".$rack_label."' WHERE row='".$raw."' AND column_name='".$col."' AND goods_id='".$goods_id."' ";
		$this->query($sql);
	
	
		//$sql ="UPDATE  rack_label_details SET rack_label='".$rack_label."' WHERE row='".$raw."' AND column_name='".$col."' AND goods_id='".$goods_id."' ";
		//$this->query($sql);
	}
	
	public function getInvoice($user_type_id,$user_id,$note=0)
	{
		//echo $user_type_id."hi hii".$user_id.'<br>';
		//sonu added 8/12/2016
		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "invoice as inv LEFT JOIN country as c  ON c.country_id=inv.final_destination  WHERE  inv.is_delete = 0  AND rack_notify_status = 0 AND customer_dispatch=0" ;
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
				$str = ' OR ( inv.purchase_user_id IN ('.$userEmployee.') AND inv.purchase_user_type_id = 2)';
			}
			$sql = "SELECT inv.*,c.country_name FROM " . DB_PREFIX . "invoice as inv LEFT JOIN country as c  ON c.country_id=inv.final_destination WHERE  inv.is_delete = 0 AND (( inv.purchase_user_id = '".(int)$set_user_id."' AND inv.purchase_user_type_id = '".(int)$set_user_type_id."' ) $str ) AND inv.is_delete = 0 AND rack_notify_status = 0 AND customer_dispatch = 0" ;
		}
		//printr($userEmployee);
		$sql .=' AND inv.date_added >="2016-11-21" GROUP BY inv.invoice_id ';
		
		if($note=='1')
			$sql .=' ORDER BY inv.date_added DESC';
		else
			$sql .=' ORDER BY inv.invoice_date DESC';
		//echo $sql;die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getInvoiceDetails($invoice_id)
	
	{
	    
	  	$sql_invoice = "SELECT * FROM " . DB_PREFIX . "invoice  WHERE invoice_id = '" .(int)$invoice_id. "' AND is_delete=0";
		$data_invoice = $this->query($sql_invoice);
        if($data_invoice->num_rows){
			return $data_invoice->row;
		}else{
			return false;
		}
	}
	
	//sonu change query 9/12/2016
    public function getInvoiceProduct($invoice_id)
	{
		//[kinjal] updated on 15-12-2016
		$sql_pro ="SELECT * FROM invoice_product WHERE invoice_id ='".$invoice_id."'";
		$data = $this->query($sql_pro);
		$data_pro=array();
		
		if($data->num_rows)
		{
			foreach($data->rows as $row)
			{
			    $mystring = $row['buyers_o_no'];
			    $findme = 'CUST';
				$pos = strpos($mystring, $findme);
				
				$clr='';
				
			     if($row['product_code_id']==0)
			     {
			         	$sql_clr ="SELECT * FROM invoice_color WHERE invoice_id ='".$invoice_id."' AND invoice_product_id='".$row['invoice_product_id']."'";
			         	$data_clr = $this->query($sql_clr);
			         	if($data_clr->row['dimension']!='')
			         	{
    			         	$dia = explode('X',$data_clr->row['dimension']);
    			         	$volume ='AND pc.width='.$dia[0].' AND pc.height='.$dia[1].' AND pc.gusset='.floor($dia[2]).' ';
			         	}
			         	else
			         	{
			         	   $volume ='AND pc.volume='.$data_clr->row['size'].' AND pc.measurement='.$data_clr->row['measurement'].' ';  
			         	}
			         	$sql = "SELECT ip.*,ic.qty,ic.rack_status,pc.product_code,ic.invoice_color_id,ip.product_id from invoice_color as ic,invoice_product as ip,product_code as pc WHERE ip.invoice_product_id ='".$row['invoice_product_id']."'  AND ip.invoice_product_id = ic.invoice_product_id AND pc.product=ip.product_id AND pc.valve=ip.valve AND pc.zipper ='".decode($row['zipper'])."' AND pc.spout ='".decode($row['spout'])."' AND pc.accessorie ='".decode($row['accessorie'])."'AND pc.make_pouch=ip.make_pouch   AND ic.color = pc.color AND ip.product_id!=6 $clr $volume";
                        if($row['product_id']=='6')
                            $sql = "SELECT ip.*,ic.qty,ic.rack_status,pc.product_code,ic.invoice_color_id,ig.net_weight,ip.product_id,ig.in_gen_invoice_id from invoice_color as ic,invoice_product as ip,product_code as pc,in_gen_invoice as ig WHERE ip.invoice_product_id ='".$row['invoice_product_id']."'  AND ip.invoice_product_id = ic.invoice_product_id AND pc.product=ip.product_id AND pc.valve=ip.valve AND pc.zipper ='".decode($row['zipper'])."' AND pc.spout ='".decode($row['spout'])."' AND pc.accessorie ='".decode($row['accessorie'])."'AND pc.make_pouch=ip.make_pouch   AND ic.color = pc.color AND ip.product_id=6 AND ip.invoice_product_id = ig.invoice_product_id AND ic.invoice_color_id=ig.invoice_color_id AND ig.rack_status=0 $clr $volume";
			     }
			    else
				{
				    $sql = "SELECT ip.*,ic.qty,ic.rack_status,ic.color as cust_color,pc.product_code,ic.invoice_color_id,ip.product_id from invoice_color as ic,invoice_product as ip,product_code as pc WHERE ip.invoice_product_id ='".$row['invoice_product_id']."'  AND ip.invoice_product_id = ic.invoice_product_id AND pc.product_code_id=ip.product_code_id  AND ic.color = pc.color AND ip.product_id!=6 $clr"; 
				    if($row['product_id']=='6')
			           $sql = "SELECT ip.*,ic.qty,ic.rack_status,ic.color as cust_color,pc.product_code,ic.invoice_color_id,ig.net_weight,ip.product_id,ig.in_gen_invoice_id from invoice_color as ic,invoice_product as ip,product_code as pc,in_gen_invoice as ig WHERE ip.invoice_product_id ='".$row['invoice_product_id']."'  AND ip.invoice_product_id = ic.invoice_product_id AND pc.product_code_id=ip.product_code_id  AND ic.color = pc.color AND ip.product_id=6 AND ip.invoice_product_id = ig.invoice_product_id AND ic.invoice_color_id=ig.invoice_color_id AND ig.rack_status=0 $clr";     
				}
			      
				$data_p = $this->query($sql);
                if($data_p->num_rows)
                { 
				    if($data_p->row['product_id']==6)
				    {
				        foreach($data_p->rows as $d_pro)
				        {
				            $data_pro[] = $d_pro;
				        }
				    }
				    else
				        $data_pro[] = $data_p->row;
                }
			}
		}
	    if($data_invoice['order_user_id']=='19'){
    	    $final_data_array=array();
    	    
    	   foreach($data_pro as $d_pro){
    	    
    	       if($d_pro['cust_color']!='-1')
    	         array_push($final_data_array,$d_pro);
    	   }
    	}else{
    	    $final_data_array=$data_pro;
    	}
		if(!empty($final_data_array)){
			return $final_data_array;
		}else{
			return false;
		}		
	} 
	public function getInvoiceProduct_test($invoice_id)
	{
	 //   printr($invoice_id);die;
		//[kinjal] updated on 15-12-2016
		$sql_pro ="SELECT * FROM invoice_product_test WHERE invoice_id ='".$invoice_id."' ORDER by FIELD(invoice_product_id,'32349','32349','32349','32349','32349','32349','32349','32349','32349','32349','32350','32350','32350','32350','32350','32350','32350','32350','32350','32350','32351','32351','32351','32351','32351','32351','32351','32351','32351','32351','32352','32352','32352','32352','32352','32352','32352','32352','32352','32352','32353','32353','32353','32353','32353','32353','32353','32353','32353','32353','32354','32354','32354','32354','32354','32354','32354','32354','32354','32354','32355','32355','32355','32355','32355','32355','32355','32355','32355','32355','32356','32356','32356','32356','32356','32356','32356','32356','32356','32356','32357','32357','32357','32357','32357','32357','32357','32357','32357','32357','32358','32358','32358','32358','32358','32358','32358','32358','32358','32358','32373','32373','32373','32374','32374','32374','32375','32375','32375','32376','32376','32376','32377','32377','32377','32378','32378','32378','32379','32379','32379','32535','32535','32535','32535','32535','32530','32530','32531','32531','32531','32531','32531','32532','32532','32532','32532','32532','32533','32533','32533','32533','32533','32534','32534','32534','32534','32534','32510','32510','32510','32511','32511','32511','32512','32512','32512','32530','32300','32300','32300','32300','32300','32300','32300','32300','32300','32300','32300','32300','32300','32300','32300','32300','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32301','32302','32302','32302','32303','32298','32298','32298','32298','32298','32298','32298','32298','32298','32298','32298','32298','32298','32298','32298','32298','32298','32298','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32299','32300','32300','32300','32300','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32296','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32297','32298','32298','32302','32302','32302','32302','32302','32302','32302','32302','32302','32302','32302','32302','32302','32302','32302','32302','32302','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32303','32515','32515','32515','32515','32515','32515','32516','32516','32516','32516','32516','32517','32517','32517','32517','32517','32517','32518','32518','32518','32518','32518','32518','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32304','32323','32323','32323','32323','32323','32323','32323','32323','32324','32324','32324','32324','32327','32327','32328','32328','32328','32328','32328','32328','32329','32329','32329','32329','32329','32329','32330','32330','32330','32330','32330','32330','32324','32324','32324','32324','32324','32324','32325','32325','32325','32325','32325','32325','32325','32325','32325','32325','32326','32326','32326','32326','32326','32326','32326','32326','32326','32326','32327','32327','32327','32327','32327','32327','32327','32327','32323','32323','32310','32310','32310','32310','32310','32310','32310','32310','32310','32310','32310','32310','32310','32310','32310','32310','32310','32310','32311','32311','32311','32311','32311','32311','32311','32311','32311','32311','32311','32311','32311','32311','32311','32311','32311','32311','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32305','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32306','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32307','32308','32308','32308','32308','32308','32308','32308','32308','32312','32312','32312','32312','32311','32340','32340','32340','32340','32340','32340','32340','32340','32340','32340','32340','32340','32340','32340','32341','32341','32341','32341','32341','32341','32342','32342','32342','32342','32342','32342','32343','32343','32343','32343','32343','32343','32332','32312','32312','32312','32312','32312','32311','32312','32312','32312','32312','32312','32312','32312','32312','32312','32312','32312','32308','32308','32308','32308','32308','32308','32308','32308','32308','32308','32308','32308','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32309','32310','32310','32331','32331','32331','32331','32331','32331','32331','32331','32331','32331','32332','32332','32332','32332','32332','32332','32332','32332','32332','32333','32333','32333','32333','32333','32333','32334','32334','32334','32334','32334','32334','32335','32520','32520','32520','32520','32520','32520','32521','32521','32521','32521','32521','32521','32523','32523','32523','32523','32523','32523','32519','32519','32519','32519','32519','32519','32522','32522','32522','32522','32522','32522','32335','32335','32335','32335','32336','32336','32336','32336','32336','32336','32337','32337','32337','32337','32337','32337','32338','32338','32338','32338','32338','32338','32339','32339','32339','32339','32339','32339','32340','32340','32340','32340','32340','32340','32335','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32313','32344','32344','32344','32344','32344','32344','32345','32345','32345','32345','32345','32345','32346','32346','32346','32346','32346','32346','32347','32347','32347','32347','32347','32347','32347','32347','32347','32347','32348','32348','32348','32348','32348','32348','32514','32516','32513','32513','32514','32513','32514','32530','32530','32369','32369','32369','32369','32369','32369','32369','32369','32369','32370','32370','32370','32370','32370','32370','32371','32371','32371','32371','32371','32371','32372','32372','32372','32372','32372','32372','32367','32314','32314','32314','32314','32314','32314','32314','32314','32314','32314','32315','32315','32315','32315','32315','32315','32315','32315','32315','32315','32316','32316','32316','32316','32316','32318','32318','32319','32319','32319','32319','32319','32319','32319','32319','32319','32319','32320','32320','32320','32320','32320','32320','32320','32320','32320','32320','32321','32322','32364','32364','32365','32365','32365','32365','32365','32365','32366','32366','32366','32366','32366','32366','32367','32367','32367','32367','32367','32368','32368','32368','32368','32368','32368','32359','32359','32369','32359','32360','32363','32363','32316','32316','32316','32316','32316','32317','32317','32317','32317','32317','32317','32317','32317','32317','32317','32318','32318','32318','32318','32318','32318','32318','32318','32321','32321','32321','32321','32321','32321','32321','32321','32322','32322','32322','32322','32322','32322','32322','32322','32322','32321','32359','32359','32359','32360','32360','32361','32361','32361','32361','32361','32362','32362','32362','32362','32362','32362','32363','32363','32363','32363','32364','32364','32364','32364','32360','32360','32360','32361','32524','32524','32524','32524','32525','32525','32525','32525','32526','32526','32526','32526','32527','32527','32527','32527','32528','32528','32528','32528','32529','32529','32529','32529','')";
		$data = $this->query($sql_pro);
		$data_pro=array();
		 
		if($data->num_rows)
		{ 
			foreach($data->rows as $row)
			{
			    $mystring = $row['buyers_o_no'];
			    $findme = 'CUST';
				$pos = strpos($mystring, $findme);
				
				$clr='';
				
			   	$sql_clr ="SELECT * FROM invoice_color_test WHERE invoice_id ='".$invoice_id."' AND invoice_product_id='".$row['invoice_product_id']."'";
			         	$data_clr = $this->query($sql_clr);
			         	$arr = explode(" ",$data_clr->row['size']);
			         	   $volume =' AND pc.volume ='.$arr[0].'  AND pc.measurement='.$data_clr->row['measurement'].' ';  
			         
			         	$sql = "SELECT ip.*,ic.qty,ic.rack_status,pc.product_code,ic.invoice_color_id,ip.product_id from invoice_color_test as ic,invoice_product_test as ip,product_code as pc WHERE ip.invoice_product_id ='".$row['invoice_product_id']."'  AND ip.invoice_product_id = ic.invoice_product_id AND pc.product=ip.product_id AND pc.valve=ip.valve AND pc.zipper ='".decode($row['zipper'])."' AND pc.spout ='".decode($row['spout'])."'  AND pc.accessorie ='".decode($row['accessorie'])."' AND pc.make_pouch=ip.make_pouch   AND ic.color = pc.color AND ip.product_id!=6 $clr $volume";
                     
			      
		
		//	 echo $sql.'<br></br>';//die;
			 
			    
				$data_p = $this->query($sql);
                 if($data_p->num_rows)
                { 
				    if($data_p->row['product_id']==6)
				    {
				        foreach($data_p->rows as $d_pro)
				        {
				            $data_pro[] = $d_pro;
				        }
				    }
				    else
				        $data_pro[] = $data_p->row;
                }
                
			}
		}
      $final_data_array=$data_pro;
      if(!empty($final_data_array)){
			return $final_data_array;
		}else{
			return false;
		}	

	
	}
	public function getCustomDigitalProduct($invoice_product_id)
	{
	    $sql = "SELECT * FROM product_code WHERE  is_delete = '0' AND status=1 AND color='-1' AND   ( product_code LIKE 'CUST%') ORDER BY product_code_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getCreditInvoice($user_type_id,$user_id,$note=0)
	{
		//echo $user_type_id."hi hii".$user_id.'<br>';
		//sonu added 8/12/2016
		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT sn.*,si.invoice_no FROM " . DB_PREFIX . "sales_credit_note as sn LEFT JOIN sales_invoice as si  ON si.invoice_id=sn.invoice_id  WHERE  sn.is_delete = 0  AND sn.credit_notify_status = 0" ;
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
				$str = ' OR ( sn.user_id IN ('.$userEmployee.') AND sn.user_type_id = 2 )';
			}
			$sql = "SELECT sn.*,si.invoice_no FROM " . DB_PREFIX . "sales_credit_note as sn,sales_invoice as si  WHERE  si.invoice_id=sn.invoice_id AND sn.is_delete = 0 AND ( sn.user_id = '".(int)$set_user_id."' AND sn.user_type_id = '".(int)$set_user_type_id."' $str ) AND sn.credit_notify_status = 0" ;
		}
		
		$sql .=' AND sn.date_added >="2017-01-02" GROUP BY sn.invoice_id ';
		
		if($note=='1')
			$sql .=' ORDER BY sn.date_added DESC';
		else
			$sql .=' ORDER BY sn.invoice_date DESC';
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function addCreditDetail($invoice_id,$pallet,$span_inv_no,$store_qty,$pur_qty,$sales_credit_note_id,$product_code_id,$product_id)
	{
		if($_SESSION['LOGIN_USER_TYPE'] == 2){
		
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				//printr($userEmployee);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		}
		
		$status = '0';
		$arr = explode("=",$pallet);
		$sql = "INSERT INTO stock_management SET order_no = 'na', my_order_no = 'na',proforma_no='na',invoice_no='".$span_inv_no."',invoice_id ='".$invoice_id."', sales_credit_note_id = '".$sales_credit_note_id."', description = '1',product = '".$product_id."',qty = '".$store_qty."',row='".$arr[0]."',column_name='".$arr[1]."',goods_id='".$arr[2]."',company_name='na',product_code_id='".$product_code_id."', status='".$status."',is_delete='0',date_added=NOW(),date_modify=NOW(),user_id='".$set_user_id."',user_type_id='".$set_user_type_id."'";
		//echo $sql;
		$data = $this->query($sql); 
	
			//echo $sql ; die;
	
		$remaining_qty = $pur_qty-$store_qty;
		$sql1="UPDATE  sales_credit_note SET rack_remaining_qty='".$remaining_qty."' WHERE sales_credit_note_id='".$sales_credit_note_id."'";
		//echo $sql1;
		$result=$this->query($sql1);
		
		
	}
	
	public function getSalesInvoice($user_type_id,$user_id,$note=0)
	{
		$ib_user = $this->getUser($user_id,$user_type_id);//printr($ib_user);
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT inv.* FROM " . DB_PREFIX . "sales_invoice as inv  WHERE  inv.is_delete = 0 AND inv.gen_status='0' AND rack_notify_status = 0" ;
			//if($ib_user=='33' || $ib_user=='24')
			    //$sql = "SELECT inv.* FROM " . DB_PREFIX . "sales_invoice as inv, transfer invoice as ti  WHERE  inv.is_delete = 0 AND inv.gen_status='0' AND rack_notify_status = 0 AND " ;
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
			$sql = "SELECT inv.* FROM " . DB_PREFIX . "sales_invoice as inv  WHERE  inv.is_delete = 0 AND ( inv.user_id = '".(int)$set_user_id."' AND inv.user_type_id = '".(int)$set_user_type_id."' $str ) AND inv.is_delete = 0 AND inv.gen_status='0' AND rack_notify_status = 0 AND status=1" ;

		}
		
		$sql .=' AND inv.date_added >="2017-01-02" AND inv.customer_dispatch = 0 GROUP BY inv.invoice_id ';
		
		if($note=='1')
			$sql .=' ORDER BY inv.date_added DESC';
		else
			$sql .=' ORDER BY inv.invoice_date DESC';
	    
		$data = $this->query($sql);
	
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getSalesInvoiceProduct($invoice_id,$n='')
	{
		$str='';
		if($n=='')
			$str = " AND ip.rack_remaining_qty!='0'";
			
		$sql = "SELECT ip.*,p.product_name,p.product_id,pc.product_code FROM `" . DB_PREFIX . "sales_invoice_product` as ip,product p,product_code as pc WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0 AND pc.product_code_id=ip.product_code_id AND pc.product=p.product_id AND ip.customer_dispatch_p=0 ".$str;
		//echo $sql;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	public function getcreditnoteProduct($invoice_id,$n='')
	{
		$str='';
		if($n=='')
			$str = " AND scn.rack_remaining_qty!='0'  ";
			
		$sql = "SELECT ip.*,scn.* ,p.product_name,p.product_id,pc.product_code FROM `" . DB_PREFIX . "sales_invoice_product` as ip,product p,product_code as pc ,sales_credit_note as scn WHERE ip.invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete=0 AND pc.product_code_id=ip.product_code_id AND scn.invoice_id = '" .(int)$invoice_id. "' AND pc.product=p.product_id  AND ip.invoice_product_id = scn.invoice_product_id $str";
		//echo $sql;
	
		$data = $this->query($sql);
			//printr($data);die;
			
		
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}		
	}
	//sonu 9/12/2016 change table name
	public function  addRackDetail($invoice_product_id,$pallet,$span_inv_no,$pur_qty,$store_qty,$product_code_id=0,$in_gen_invoice_id=0)
	{ 
	//	printr($invoice_product_id.'='.$pallet.'='.$span_inv_no.'='.$pur_qty.'='.$store_qty.'='.$product_code_id);
		if($_SESSION['LOGIN_USER_TYPE'] == 2){
			//echo "emp";
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				//printr($userEmployee);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		}
		
		$arr = explode("=",$pallet);
		$data_arr = $this->getInvoiceRecord($invoice_product_id);
		//printr($data_arr);die;
		$status = '0';
		$product_id=$data_arr['product_id'];
		if($product_code_id!='0' && $product_code_id!='NaN')
		{
		    $data_arr['product_code_id']=$product_code_id;
		    $sql_pro ="SELECT * FROM invoice_product WHERE invoice_product_id = '" .$invoice_product_id. "'";
		    $data_cust = $this->query($sql_pro);
		    $data_arr['product'] = $data_cust->row['product_id'];
		}
		
		  
		$sql = "INSERT INTO stock_management SET order_no = 'na', my_order_no = 'na',proforma_no='na',invoice_no='".$span_inv_no."',description = '1',product = '".$data_arr['product']."',qty = '".$store_qty."',row='".$arr[0]."',column_name='".$arr[1]."',		goods_id='".$arr[2]."',company_name='na',product_code_id='".$data_arr['product_code_id']."', status='".$status."',is_delete='0',date_added=NOW(),date_modify=NOW(),user_id='".$set_user_id."',user_type_id='".$set_user_type_id."'";
		$data = $this->query($sql);

		$remaining_qty=$pur_qty-$store_qty;
		//printr($pur_qty.'==='.$remaining_qty);
		//printr($product_id);die;
		
		if($product_id==6)
		{
		    $remaining_qty=$pur_qty-1;//printr($pur_qty.'==='.$remaining_qty);
		    $sql1="UPDATE invoice_color SET rack_status='".$remaining_qty."' WHERE invoice_product_id='".$invoice_product_id."'";
		    $this->query("UPDATE in_gen_invoice SET rack_status='1' WHERE invoice_product_id='".$invoice_product_id."' AND in_gen_invoice_id='".$in_gen_invoice_id."'");//die;
		}
		else
		    $sql1="UPDATE invoice_color SET rack_status='".$remaining_qty."' WHERE invoice_product_id='".$invoice_product_id."'";
		    
		$result=$this->query($sql1);
	//printr($remaining_qty);
		//[kinjal] : on 3-2-2016
		$sql2="SELECT qty as tot_pur FROM stock_management WHERE product_code_id='".$data_arr['product_code_id']."' AND user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND parent_id='0' AND date_added='".date("y-m-d")."' AND is_delete=0 ORDER BY stock_id DESC LIMIT 1";
		$data2 = $this->query($sql2);	
		
		$sql3="SELECT * FROM inventory_opening_stock WHERE user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$data_arr['product_code_id']."' AND date_added=(SELECT MAX(date_added) FROM inventory_opening_stock WHERE user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$data_arr['product_code_id']."')";
		$data3=$this->query($sql3);
		if($data3->num_rows)
		{
			$opening_qty=$data3->row['opening_qty']+$data2->row['tot_pur'];	
			
			$from_opening = $data3->row['opening_qty'] * $data3->row['opening_value'];
			$from_pur = $data2->row['tot_pur'] * $data_arr['rate_with_proportion'];
			
			$opening_rate = ($from_opening+$from_pur)/$opening_qty;
			
			if(strtotime($data3->row['date_added'])== strtotime(date('y-m-d')))
			{
				$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$opening_qty."',opening_value='".$opening_rate."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data_arr['product_code_id']."'";
				//echo $sql_qry.'<br>if cond';die;
				$data4=$this->query($sql_qry);
				
			}
			else if(strtotime($data3->row['date_added']) < strtotime(date('y-m-d')))
			{
				$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$opening_qty."',opening_value='".$opening_rate."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data_arr['product_code_id']."'";
				//echo $sql_qry.'<br>else if cond';die;
				$data4=$this->query($sql_qry);
			}
			else
			{
				$sql_qry="UPDATE inventory_opening_stock SET opening_qty='".$opening_qty."',opening_value='".$opening_rate."' WHERE date_added='".date("y-m-d", strtotime(' +1 day'))."' AND user_id='".$set_user_id."' AND user_type_id='".$set_user_type_id."' AND product_code_id='".$data_arr['product_code_id']."'";
				//echo $sql_qry.'<br>else cond';die;
				$data4=$this->query($sql_qry);
			}
			
		}
		else
		{
			$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$data2->row['tot_pur']."',opening_value='".$data_arr['rate_with_proportion']."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data_arr['product_code_id']."'";
			//echo $sql_qry.'<br>else cond last';die;
			$data4=$this->query($sql_qry);
		}
		//printr($data3);
		//die;
		
	
	}
	//sonu 9/12/2016 change query
	public function getInvoiceRecord($invoice_product_id)
	{  
				
		 $sql_pro ="SELECT * FROM invoice_product WHERE invoice_product_id = '" .(int)$invoice_product_id. "'";
		 $data = $this->query($sql_pro);		 
		 
	//	 $sql = "SELECT ip.*,ic.*,pc.product_code,pc.product_code_id ,pc.product FROM `" . DB_PREFIX . "invoice_product` as ip, invoice_color as ic,product_code as pc  WHERE  ip.invoice_product_id = '" .(int)$invoice_product_id. "'  AND ip.invoice_product_id = ic.invoice_product_id AND pc.product=ip.product_id AND pc.valve=ip.valve AND pc.zipper ='".decode($data->row['zipper'])."' AND pc.spout ='".decode($data->row['spout'])."' AND pc.accessorie ='".decode($data->row['accessorie'])."'AND pc.make_pouch=ip.make_pouch  AND pc.volume=ic.size AND pc.measurement=ic.measurement AND ic.color = pc.color";
		 
		 
		$sql = "SELECT ip.*,ic.*,pc.product_code,pc.product_code_id ,pc.product FROM `" . DB_PREFIX . "invoice_product` as ip, invoice_color as ic,product_code as pc  WHERE  ip.invoice_product_id = '" .(int)$invoice_product_id. "'  AND ip.invoice_product_id = ic.invoice_product_id AND pc.product_code_id=ip.product_code_id";
		$data_1= $this->query($sql);	

		if($data->num_rows){
			return $data_1->row;
		}else{
			return false;
		}		 
	}
	//sonu 9/12/2016 change table name
	public function changeRackStatus($invoice_id)
	{
//	printr($invoice_id);
		$data_invoice = $this->getInvoiceDetails($invoice_id);
		if($data_invoice['order_user_id']=='19')
	    	$sql = "SELECT ic.rack_status FROM `" . DB_PREFIX . "invoice_color` as ic WHERE invoice_id = '" .(int)$invoice_id. "' AND ic.is_delete='0' AND color!='-1' AND ic.rack_status='0'";
	    else
	    	$sql = "SELECT ic.rack_status FROM `" . DB_PREFIX . "invoice_color` as ic WHERE invoice_id = '" .(int)$invoice_id. "' AND ic.is_delete='0' AND ic.rack_status='0'";// AND color!='-1'

		$data = $this->query($sql);
		$count = $data->num_rows;
	//	printr($data_invoice);
		$inv_product=$this->getInvoiceProduct($invoice_id);
	
		    	
        $count_pro = count($inv_product);
//printr($count_pro);
		if($count == $count_pro)
		{
			$sql1 ="UPDATE invoice SET rack_notify_status='1' WHERE invoice_id='".$invoice_id."' ";
			$this->query($sql1);
		}
	}
	public function getRackQty($product_code_id,$user_type_id,$user_id,$f_date,$t_date,$rack_data='',$n=0)
	{	 
	    $str_date_sql='';
	    if(!empty($f_date) && !empty($t_date)){
	        $str_date_sql=" AND sm.date_added BETWEEN  '".$f_date."' AND '".$t_date."'  ";    
	    }
	    //SELECT gm.name,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name FROM stock_management_new as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '1' AND ((sm.user_id = '7' AND sm.user_type_id = '4') OR ( sm.user_id IN (21,22,42,57,59,67) AND sm.user_type_id = 2 ) ) AND gm.goods_master_id = sm.goods_id GROUP BY sm.row,sm.column_name
		$str_rack='';
		if(isset($rack_data['product_code_id'])&& $rack_data['product_code_id']!=''){
		//	if(!isset($product_code_id)&& $product_code_id=''){
			$product_code_id = $rack_data['product_code_id'];
		} 
		$goods_id=array();
		if(isset($rack_data['alldata']) && $rack_data['alldata']!='')
		{
		    //$goods_id=explode('=',$rack_data['rack_name']);
		//	printr($rack_data['alldata']);
			$rack=explode("=",$rack_data['alldata']);
			$row=$rack[0];
			$column=$rack[1];
			$goods_id=$rack[2];
			
			//echo $row."=".$column."=".$goods_id;
			$str_rack = " AND sm.goods_id='".$goods_id."' AND sm.row='".$row."' AND sm.column_name='".$column."' ";
			//die;
		}
		$date_str = '';		
		/*
		if($f_date != '')
			$date_str = "AND sm.date_added >= '".$f_date."' AND  sm.date_added <= '".$t_date."'";*/
		$stock_id= ' AND parent_id=0';
		if($n==1)
			$stock_id = ' AND parent_id=0 AND sm.stock_id IN ('.$rack_data['parent_id'].')';
		//printr($str_rack);
	    //die;
			
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT gm.goods_master_id,gm.name,GROUP_CONCAT(sm.box_no,'=',(sm.qty-sm.dispatch_qty),'=',stock_id) as box_no,GROUP_CONCAT(stock_id) as stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col,sm.rack_label FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND sm.is_delete=0 AND sm.product_code_id = '".$product_code_id."'".$date_str." AND gm.goods_master_id = sm.goods_id  $str_rack AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0' $stock_id $str_date_sql GROUP BY sm.row,sm.column_name ,sm.goods_id";//$str_getrackno
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
			$sql = "SELECT gm.goods_master_id,gm.name,GROUP_CONCAT(stock_id) as stock_id,GROUP_CONCAT(sm.box_no,'=',(sm.qty-sm.dispatch_qty),'=',stock_id) as box_no,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col,sm.rack_label FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND sm.is_delete=0 AND sm.product_code_id = '".$product_code_id."' AND ((sm.user_id = '".(int)$set_user_id."' AND sm.user_type_id = '".(int)$set_user_type_id."') $str )".$date_str." AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0' $str_rack $stock_id $str_date_sql  GROUP BY sm.row,sm.column_name,sm.goods_id ";
		//	echo $sql;
		}
	//echo $sql;//die;
		//printr($sql);die;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function gettotaldispatchSales($stock_id,$user_type_id,$user_id)
	{
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT sum(dispatch_qty) as total FROM stock_management WHERE parent_id IN (" .$stock_id. ") AND is_delete=0" ;
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
		$sql="SELECT SUM(dispatch_qty) as total FROM stock_management WHERE parent_id IN (" .$stock_id. ") AND ((user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."') $str ) AND is_delete=0";
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
	public function changeRackStatusSales($invoice_id)
	{
		$sql = "SELECT ip.rack_remaining_qty FROM `" . DB_PREFIX . "sales_invoice_product` as ip WHERE invoice_id = '" .(int)$invoice_id. "' AND ip.is_delete='0' AND ip.customer_dispatch_p='0' AND ip.rack_remaining_qty='0' AND ip.product_codE_id!=0 ";
		$data = $this->query($sql);
		$count = $data->num_rows;
		//printr($count);
		$inv_product=$this->getSalesInvoiceProduct($invoice_id,$n='0');
        $count_pro = count($inv_product);
        //printr($count_pro);
		if($count == $count_pro)
		{
			$sql1 ="UPDATE sales_invoice SET rack_notify_status='1' WHERE 	invoice_id='".$invoice_id."' ";
			$this->query($sql1);
		}
	}
	public function showoldstock($goods_master_id)
	{
		$sql = "SELECT product_code_id,row,column_name,stock_id FROM `" . DB_PREFIX . "stock_management` WHERE product_code_id!='0' AND goods_id = '" .$goods_master_id. "' AND parent_id='0' AND is_delete='0'";
		//echo $sql;
		$data = $this->query($sql);
		
		if($data->num_rows)
		{
			$final_val=array();
			foreach($data->rows as $val)
			{
				$final_val[]=$val['stock_id'];
				
				
			}
			$result = "'" . implode ( "', '", $final_val ) . "'";
			$sql1 = "SELECT product_code_id,row,column_name,stock_id,parent_id FROM `" . DB_PREFIX . "stock_management` WHERE product_code_id!='0' AND goods_id = '" .$goods_master_id. "'  
			AND is_delete='0' AND parent_id IN (".$result.")";
			//echo $sql1;
			$data1 = $this->query($sql1);
			if($data1->num_rows)
			{
				$f_arr=array();
				foreach($data1->rows as $f_val)
				{
					$f_arr[] = $f_val['stock_id'];
					$f_arr[] = $f_val['parent_id'];
				}
				$f_result = "'" . implode ( "', '", $f_arr ) . "'";
				
				$sql_new = "SELECT st.product_code_id,st.date_added,pc.product_code,st.qty,st.row,st.column_name,st.stock_id,st.parent_id,g.row as g_row,g.column_name as g_col FROM  " . DB_PREFIX . "stock_management as st,product_code as pc,goods_master as g WHERE st.product_code_id!='0' AND st.goods_id = '" .$goods_master_id. "'  
				AND st.is_delete='0' AND st.stock_id NOT IN (".$f_result.") AND st.parent_id='0' AND st.product_code_id=pc.product_code_id AND st.goods_id=g.goods_master_id AND st.date_added < ADDDATE(NOW(), INTERVAL -9 MONTH)";
				//echo $sql_new;
				$data_new=$this->query($sql_new);
				if($data_new->num_rows)
				{
					return $data_new->rows;
				}
				else
				{
					return false;
				}
			
			}
			
		}
	}
	public function list_old_new($goods_master_id)
	{
		$sql = "SELECT st.qty,st.product_code_id,st.row,st.column_name,st.stock_id,st.date_added,st.invoice_no,pi.date_added as pur_date,pc.product_code FROM `" . DB_PREFIX . "stock_management` as st, purchase_invoice as pi, product_code as pc WHERE st.product_code_id!='0' AND  st.goods_id = '" .$goods_master_id. "' AND st.parent_id='0' AND st.is_delete='0' AND st.invoice_no=pi.invoice_no AND pc.product_code_id = st.product_code_id ORDER BY pi.invoice_id  ASC";
		$data = $this->query($sql);
		//printr($data);die;
		if($data->num_rows)
		{
			return $data->rows;
		}
		else
		{
			return false;
		}
	}
	public function getBoxForProduct($invoice_id,$invoice_product_id,$invoice_color_id)
	{
		$sql="SELECT * FROM in_gen_invoice WHERE invoice_id='".$invoice_id."' AND invoice_product_id ='".$invoice_product_id."'	AND invoice_color_id='".$invoice_color_id."' AND is_delete='0'";
		$data = $this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	
	public function changeRackStatusInCredit($invoice_id)
	{
		$sql = "SELECT rack_remaining_qty FROM `" . DB_PREFIX . "sales_credit_note` WHERE invoice_id = '" .(int)$invoice_id. "' AND is_delete='0' AND rack_remaining_qty='0'";
		$data = $this->query($sql);
		$count = $data->num_rows;
		//printr($data);
		$inv_product=$this->getcreditnoteProduct($invoice_id,1);
        $count_pro = count($inv_product);
		//printr($inv_product);
		if($count == $count_pro)
		{
			$sql1 ="UPDATE sales_credit_note SET credit_notify_status='1' WHERE invoice_id='".$invoice_id."' ";
			$this->query($sql1);
		}
	}
	public function getUserList($user) {

        if($_SESSION['LOGIN_USER_TYPE']==2)
		{
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '". $_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}
		else
		{
			$set_user_id =  $_SESSION['ADMIN_LOGIN_SWISS'];
			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		}
		$sql = "SELECT ac.user_type_id,ac.user_id,ac.account_master_id,ac.user_name FROM " . DB_PREFIX . "account_master as ac, employee as e WHERE e.employee_id=ac.user_id AND  ac.user_name LIKE '%" . $user . "%' ORDER BY ac.user_name ASC";
        //	$sql = "SELECT ac.user_type_id,ac.user_id,ac.account_master_id,ac.user_name FROM " . DB_PREFIX . "account_master as ac, employee as e WHERE e.employee_id=ac.user_id AND e.user_id='".$set_user_id."' AND e.user_type_id='".$set_user_type_id."' AND  ac.user_name LIKE '%" . $user . "%' ORDER BY ac.user_name ASC";
        $data = $this->query($sql);

        //printr($data);die;

        return $data->rows;
    }
    public function getproduct_status($user_type_id,$user_id,$filter_data,$option){
		
		$sql_data='';
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0'";
		}
		else{
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
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND (user_id='".(int)$set_user_id."' AND user_type_id='".(int)$set_user_type_id."' $str )";
		}
		//echo $sql;
			$data = $this->query($sql);
			
			if(!empty($filter_data)){
				if(!empty($filter_data['color_name'])){
					$sql_data .= " AND pc.color =".$filter_data['color_name']." ";
				}		
				if(!empty($filter_data['product'])){
					$sql_data .= " AND pc.product =".$filter_data['product']." ";
				}
		    	if(!empty($filter_data['product_code'])){
					$sql_data .= " AND pc.product_code LIKE '%".$filter_data['product_code']."%'";
				}
				if(!empty($filter_data['column_name'])){
					
					$g=1;
								
						$j = $filter_data['column_name'];
						if (is_numeric($j))
						{
						  
        							for($l=1;$l<=32;$l++)
        								{
        									for($m=1;$m<=32;$m++) 
        										{
        											
        											$p=$g;
        											if($j==$p)
        											{
        																																		
        												  $n=  $l.'@'.$m;
        												
        											}
        											$g++;
        										}
        							
        								} 
        						//echo $n;
        					$r_col = explode('@',$n);
        					
        					$sql_data .= " AND sm.row =".$r_col[0]." AND sm.column_name =".$r_col[1]." ";
						}
						else
						{
						    $sql_data .= " AND sm.rack_label LIKE '%".$j."%'";
						}
				}
			}
			
			$limit_data='';
		
		
       

            if (isset($option['start']) || isset($option['limit'])) {
    
                if ($option['start'] < 0) {
    
                    $option['start'] = 0;
                }
    
                if ($option['limit'] < 1) {
    
                    $option['limit'] = 20;
                }
    
                $limit_data .= " LIMIT " . (int) $option['start'] . "," . (int) $option['limit'];
            }
			
			
			$f_val=array();
			if($data->num_rows)
				{
					//$final_val=array();
					
					foreach($data->rows as $val)
					{
						//$final_val[]=$val['goods_master_id'];
						
						
						$sql2 = "SELECT sm.user_id,sm.user_type_id,sum(sm.qty)qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id,p.product_name ,gm.name,sm.row,sm.column_name,pc.product_code,sm.stock_id,gm.row as g_row,gm.column_name as g_col,pc.description,sm.rack_label FROM stock_management as sm,product as p,product_code as pc,goods_master AS gm WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = gm.goods_master_id AND  sm.goods_id='".$val['goods_master_id']."' AND pc.product_code_id = sm.product_code_id AND parent_id=0  AND gm.is_delete = '0'AND sm.qty!=0 AND sm.row!=0 AND sm.column_name!=0 $sql_data  GROUP BY sm.row,sm.column_name,sm.product_code_id $limit_data ";
						
						 
						
					//	echo $sql2.'<br>'; //die;
						//die;
						$data2 = $this->query($sql2);					
						foreach($data2->rows as $data_arr)
						{
						    //printr($data_arr['dispatch_qty']);
							$f_val[]=$data_arr;
						
						
						}
						
					}    
					     
						
						
					}

			//$comb_arr=array();
			$comb_arr=$f_val;
			//printr($comb_arr);
			return $comb_arr;	
									
			
	}
	public function getProductCategory()
	{
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
	public function getSalesInvoiceCountry($inv_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "sales_invoice` WHERE status='1' AND is_delete = '0' AND invoice_id = '".$inv_id."' ";
			$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['final_destination'];
		}else{
			return false;
		}
	}
	
	
	//add sonu 10-8-2017 india dispatch
	
	
	public function getInvoiceData($invoice_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "sales_invoice  WHERE invoice_id = '" .(int)$invoice_id. "' AND rack_notify_status ='1'";
		$data = $this->query($sql);
		//printr($data);
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	 public function getUserList_detail() {

        $sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX . "account_master ORDER BY user_name ASC";

        $data = $this->query($sql);

        //printr($data);die;

        return $data->rows;
    }

	
		public function getSalesInvoiceForDispatched($note=0)
		{
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			if($user_type_id == 1 && $user_id == 1){
				$sql = "SELECT inv.* FROM " . DB_PREFIX . "sales_invoice as inv  WHERE  inv.is_delete = 0 AND inv.gen_status='0' AND inv.rack_notify_status = 1   " ;
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
				$sql = "SELECT inv.* FROM " . DB_PREFIX . "sales_invoice as inv  WHERE  inv.is_delete = 0 AND ( inv.user_id = '".(int)$set_user_id."' AND inv.user_type_id = '".(int)$set_user_type_id."' $str ) AND inv.is_delete = 0 AND inv.gen_status='0' AND inv.rack_notify_status = 1  " ;
			}
			
		//	$sql .=' AND inv.date_added >="2017-01-02" GROUP BY inv.invoice_id ';
			
			if($note=='1')
				$sql .=' ORDER BY inv.date_added DESC';
			else
				$sql .=' ORDER BY inv.invoice_date DESC';
				
			//echo $sql;
			$data = $this->query($sql);
		
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}
		public function getSalesInvoiceForDispatchedTotal($note=0)
		{
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];		
			if($user_type_id == 1 && $user_id == 1){
				$sql = "SELECT COUNT(*) as total  FROM " . DB_PREFIX . "sales_invoice as inv  WHERE  inv.is_delete = 0 AND inv.gen_status='0' AND inv.rack_notify_status = 1   " ;
				
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
					$str = ' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )';
				}
				$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "sales_invoice as inv  WHERE  inv.is_delete = 0 AND ( inv.user_id = '".(int)$set_user_id."' AND inv.user_type_id = '".(int)$set_user_type_id."' $str ) AND inv.is_delete = 0 AND inv.gen_status='0' AND inv.rack_notify_status = 1  " ;
			}
		
			//$sql .=' AND inv.date_added >="2017-01-02" GROUP BY inv.invoice_id ';
			
			if($note=='1')
				$sql .=' ORDER BY inv.date_added DESC';
			else
				$sql .=' ORDER BY inv.invoice_date DESC';
				
		//	echo $sql;
			$data = $this->query($sql);
			//printr($data);
			return $data->row['total'];
		}
		
		
		
	function addcourier($courier_name,$aws_no,$sent_date,$invoice_id,$admin_email){
			$sql = "UPDATE " . DB_PREFIX . "sales_invoice SET courier_name = '" .$courier_name. "',aws_no = '" .$aws_no. "',sent_date = '" .date("Y-m-d", strtotime($sent_date)). "',courier_status ='1' ,date_modify = NOW() WHERE invoice_id ='".$invoice_id."'";
		
		//	echo $sql;die;
			$this->query($sql);
			
			
		//	$send_email_for_dispated_stock=$this->send_email_for_dispated_stock($invoice_id,$admin_email);
		
	}
	
/*	function send_email_for_dispated_stock($invoice_id,$admin_email)
	{
		
		
		$pro_data=$this->getInvoiceData($invoice_id);
		//printr($pro_data);die;
		$html='';

		$subject = ' Dispatch  Details for proforma invoice no:-'.$pro_data['proforma_no'];  

	
		$html.='Hi Team ,<br>';
		
		$html.="<p>Below proforma invoice no <span style='color:red'><b>".$pro_data['proforma_no']."<b></span> for clien <span style='color:red'> <b>".$pro_data['customer_name']."</b></span>is dispatched on  <span style='color:red'> <b>".dateFormat("4",$pro_data['send_date'])."</b></span></p>";	
		$html.='<b>Dispatch Details</b><br>';
		$html.='<b>Courier Name :-</b><span style="color:red">'.$pro_data['courier_name'].' </span><br>';
		$html.='<b>AWS No :-</b><span style="color:red">'.$pro_data['aws_no'].' </span><br>';
	
		
	//	printr($html);die;
		
	
		//printr($html);die;
		//add Online user_id
	
	/*$thakor_bhai=$this->getUser('117','2');
		$mahendra_bhai=$this->getUser('118','2');
		$pro_user=$this->getUser($pro_data['user_id'],$pro_data['user_type_id']);
		$prashanr=$this->getUser('144','2');
		$parul_email_id=$this->getUser('52','2');	
		$addedByinfo_himani=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$email_temp[]=array('html'=>$html,'email'=>$thakor_bhai['email']);
		$email_temp[]=array('html'=>$html,'email'=>$mahendra_bhai['email']);
		$email_temp[]=array('html'=>$html,'email'=>$himani['email']);
		$email_temp[]=array('html'=>$html,'email'=>$prashanr['email']);
		$email_temp[]=array('html'=>$html,'email'=>$pro_user['email']);
		$email_temp[]=array('html'=>$html,'email'=>$parul_email_id['email']);
		$email_temp[]=array('html'=>$html,'email'=>$addedByinfo_himani['email']);
		*/
		
	

	/*	$email_temp[]=array('html'=>$html,'email'=>'sharanpurohit25@gmail.com');
		
	
		$obj_email = new email_template();
		$rws_email_template = $obj_email->get_email_template(14); 
		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
			
		$path = HTTP_SERVER."template/order_template.html";
		$output = file_get_contents($path);  
		
		$search  = array('{tag:header}','{tag:details}');
		
		//add himanu email for from_email $addedByinfo_himani['email'];
		$from_email = 'tech@swisspack.co.in';
		$message = '';
		$signature='';
		$signature = 'Thanks.';
		//printr($search);die;
		//printr($email_temp);die;
		foreach($email_temp as $val)
		{
			$message = '';
			if($val['html'])
			{
				$tag_val = array(
					"{{dispatch Detail}}" =>$html,
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
			send_email($val['email'],$from_email,$subject,$message,'','');
	
		}
		
	}
	*/
	

	public function courier_details($invoice_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "sales_invoice  WHERE is_delete='0'  AND invoice_id = '" .(int)$invoice_id. "'";
		$data = $this->query($sql);
		//printr($data);		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
		public function getRackQtyDispatchIndia($product_code_id,$user_type_id,$user_id,$f_date,$t_date,$rack_data='')
	{	
		//SELECT gm.name,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name FROM stock_management_new as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND sm.product_code_id = '1' AND ((sm.user_id = '7' AND sm.user_type_id = '4') OR ( sm.user_id IN (21,22,42,57,59,67) AND sm.user_type_id = 2 ) ) AND gm.goods_master_id = sm.goods_id GROUP BY sm.row,sm.column_name
		$str_rack='';
		
	//	printr($rack_data);
		if(isset($rack_data['pallet_sales']) && $rack_data['pallet_sales']!='')
		{
			//printr($rack_data['pallet_sales']);
			$rack=explode("=",$rack_data['pallet_sales']);
			$row=$rack[0];
			$column=$rack[1];
			$goods_id=$rack[2];
			
			//echo $row."=".$column."=".$goods_id;
			$str_rack = " AND sm.goods_id='".$goods_id."' AND sm.row='".$row."' AND sm.column_name='".$column."' ";
			//die;
		}
				
		$date_str = '';
		if($f_date != '')
			$date_str = "AND sm.date_added >= '".$f_date."' AND  sm.date_added <= '".$t_date."'";
			
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT gm.name,GROUP_CONCAT(stock_id) as stock_id,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND is_delete=0 AND sm.product_code_id = '".$product_code_id."' GROUP BY sm.row,sm.column_name ";
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
			$sql = "SELECT gm.name,GROUP_CONCAT(stock_id) as stock_id,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND parent_id=0 AND is_delete=0 AND sm.product_code_id = '".$product_code_id."' AND ((sm.user_id = '".(int)$set_user_id."' AND sm.user_type_id = '".(int)$set_user_type_id."') $str )".$date_str." AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0' $str_rack  GROUP BY sm.row,sm.column_name ";
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
	

	
//sonu end

//sonu add for india stock dispatch for india_add.php 19-8-2017
	
	public function getRack_qty_dis($pallet_details,$user_id,$user_type_id,$f_date,$t_date,$rack_id)
	{
	    $goods_id=$row=$column='';
	    if(isset($rack_id) && $rack_id!='')
		{
    		 $goods_data=explode("=",$rack_id);
    		 $goods_id=$goods_data[2];  
		}
		if(isset($pallet_details) && $pallet_details!='')
		{
			//printr($rack_data['pallet_sales']);
			$rack=explode("=",$pallet_details);
			$row=$rack[0];
			$column=$rack[1];
			//$goods_id=$rack[2];
		} 
	//	$data=$this->getrackdetail($user_id,$user_type_id,$goods_id,$row,$column,'','',$f_date,$t_date);
		$data=$this->getrackdetailTEST($user_id,$user_type_id,$goods_id,$row,$column,'','',$f_date,$t_date);
			//printr($data);die;
			
		return $data;
		
	
		
	
	}
	public function getLabel($col_row,$goods_master_id)
    {
        $explode = explode('@',$col_row);
        $sql="SELECT rack_label FROM stock_management WHERE goods_id='".$goods_master_id."' AND row='".$explode[0]."' AND column_name = '".$explode[1]."' AND rack_label!='' LIMIT 1 ";
        $data = $this->query($sql);
        if($data->num_rows){
            return $data->row['rack_label'];
        }else{
			return false;
		}
    }
    public function goods_master_detail($goods_id)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND goods_master_id='".$goods_id."'";
        $data = $this->query($sql);
      // echo $sql;
       
        if($data->num_rows){
            return $data->row;
        }else{
			return false;
		}
    }
    public function InsertCSVData($handle)
	{
       // CANADA=44 UAE=19
		$user_type_id ='4';
		$user_id ='44';
		
		
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
		$data=array();
		$first_time = true;

	  	while($data = fgetcsv($handle,1000,","))
		{

			if ($first_time == true) {
				$first_time = false;
				continue;
			}
	//printr($data);
		  $goods_data=$this->goods_master_detail($data[3]);
		  
		  

			$r_no=array();
			$d=1;
			$rc = $goods_data['row'].'@'.$goods_data['column_name'];
			for($i=1;$i<=$goods_data['row'];$i++)
			{
				for($r=1;$r<=$goods_data['column_name'];$r++) 
				{
					
						$r_no[] = $i.'='.$r.'='.$d;

						if($d==$data[0]){
							$row=$i;
							$col=$r;

						}
					$d++;
				}
			}
															
			$product_code_data=$this->getProductcode_id($data[1]);
			$goods_id=$data[3];
			$box_no=$data[4];
			$rack_label=$data[6];
			$invoice_no=$data[7];
			$box_unique_id=$data[5];
			$product_code_id=$product_code_data['product_code_id'];
			$product_id=$product_code_data['product'];
			$qty=$data[2];


		$sql = "INSERT INTO stock_management SET order_no = 'na', my_order_no = 'na',proforma_no='na',invoice_no='".$invoice_no."',description = '1',box_no='".$box_no."',product = '".$product_id."',qty = '".$qty."',row='".$row."',column_name='".$col."',goods_id='".$goods_id."',rack_label='".$rack_label."',product_code_id='".$product_code_id."', status='0',is_delete='0',date_added=NOW(),date_modify=NOW(),user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',added_user_id='".$set_user_id."',added_user_type_id='".$set_user_type_id."',box_unique_id='".$box_unique_id."' ";
       //     printr($sql);
  
          $data = $this->query($sql);
		  
		    //$sql_qry="INSERT INTO inventory_opening_stock SET opening_qty='".$qty."',date_added='".date("y-m-d", strtotime(' +1 day'))."',user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$product_code_id."'";
			//$data3=$this->query($sql_qry);
        }
	//	die;	
	}
	public function getGoodsdata($user_id,$user_type_id){
	
			    $sql = "select * from `" . DB_PREFIX . "goods_master` where user_id='".$user_id."' AND user_type_id='".$user_type_id."' AND is_delete=0";
			   // echo $sql;
      		  	$data = $this->query($sql);
       			if($data->num_rows){
					return $data->row;
				}else{
					return false;
				}
	}

	public function getProductcode_id($product_code){
		$sql="SELECT * FROM product_code WHERE product_code = '".$product_code."' AND status=1 AND is_delete=0";
		$data = $this->query($sql);
   			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}

	}
	public function getSingleInvoice($proforma_invoice_id) {

			$sql = "select * from " . DB_PREFIX ." proforma_product_code_wise where pro_in_no = '".$proforma_invoice_id."'";
        //echo $sql;die;
			$data = $this->query($sql);

			if($data->num_rows){

				return $data->row;

			}
			else {
				return false;
			}
		}
	
	
	public function getrackdetailforShift($user_id,$user_type_id,$goods_id,$row,$col,$stock_id){
		
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id,p.product_name FROM stock_management as sm,product as p,product_code as pc WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' AND pc.product_code_id = sm.product_code_id AND sm.row='".$row."' AND sm.column_name='".$col."' AND parent_id=0 AND stock_id IN('".$stock_id."')";
		
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
				$str = 'AND  ( (sm.user_id = '.(int)$set_user_id.' AND sm.user_type_id = '.(int)$set_user_type_id.') OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) )';
			}
			

	        $sql = "SELECT sm.*,sum(sm.qty) tot_qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id ) grouped_s_id,p.product_name FROM stock_management as sm,product as p,product_code as pc WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = '" .(int)$goods_id. "' AND sm.row='".$row."' AND sm.column_name='".$col."' AND pc.product_code_id = sm.product_code_id AND parent_id=0 AND stock_id IN('".$stock_id."') $str ";

		}
	
		$sql.=' GROUP BY sm.product_code_id';
	
		$data = $this->query($sql);
	
		if($data->num_rows)
			return $data;
		else{
			return false;
		}	
			
	}
	public function savedispatch_stock_shift($data)
	{ 
	  
		if($_SESSION['LOGIN_USER_TYPE'] == 2){
			//echo "emp";
			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);
				//printr($userEmployee);
			$set_user_id = $parentdata->row['user_id'];
			$set_user_type_id = $parentdata->row['user_type_id'];
		}else{
			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		}
		//add courier    by sonu 29-3-2017 courier
		$courier_id=0;	
		$row_col=explode('=',$data['alldata']);
		$partial = explode(',', $data['stock_id']);
		$final = array();
		$shift_qty=0;
		//	printr($partial);
	
		array_walk($partial, function($val,$key) use(&$final){
			list($key, $value) = explode(':', $val);
			$sql3 = "SELECT SUM(qty) shift_qty FROM " . DB_PREFIX . "stock_management WHERE stock_id=".$value."";
			$data3 = $this->query($sql3);

			if($data3->num_rows){
			$shift_qty=$data3->row['shift_qty'];
			}

			$qty=$key-$shift_qty;
			
		//	if($qty>0)
				$final[] = array('id'=>$value,'qty'=>$key);	
		});
	//	printr($final);
		$shift_qty1=$data['shift_qty'];
			//printr($data);

	if(!isset($data['box_no']))
		$data['box_no']='';
		
			$shift_goods_data= explode('=', $data['pallet_detail']);
		
	//	printr($final);
		foreach($final as $record)
		{	
			if($shift_qty1>$record['qty'])
				$final_dis_qty=$record['qty'];
			else
				$final_dis_qty=$shift_qty1;
				
	//printr($final_dis_qty);
			 

		$sql="INSERT INTO stock_management SET proforma_no='na',invoice_no='na',dispatch_qty='".$final_dis_qty."',parent_id='".$record['id']."',box_no='".$data['box_no']."',product='".$data['product_id']."',goods_id='".$row_col[2]."' , row='".$row_col[0]."' ,column_name='".$row_col[1]."',company_name='na',description=2,status=1,date_added=NOW(),date_modify=NOW(),shift_status='OUT', user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data['product_code_id']."'";
			$data1=$this->query($sql);	
			//printr($sql);
		

		$sql_shift="INSERT INTO stock_management SET proforma_no='na',invoice_no='na',qty='".$final_dis_qty."',shift_parent_id='".$record['id']."',box_no='".$data['box_no']."',product='".$data['product_id']."',goods_id='".$shift_goods_data[2]."' , row='".$shift_goods_data[0]."' ,column_name='".$shift_goods_data[1]."',company_name='na',description=1,status=1,date_added=NOW(),date_modify=NOW(),shift_status='IN', user_id='".$set_user_id."',user_type_id='".$set_user_type_id."',product_code_id='".$data['product_code_id']."'";
			//printr($sql_shift);
			
			$data_shift=$this->query($sql_shift);	
			if($shift_qty1 > $final_dis_qty) 
				$shift_qty1=$shift_qty1-$final_dis_qty;	
			else
				break;		
				
		}
		
			//die;
	}
	
	public function getSalesInvoiceProductDispatch($invoice_no){
	    
	    $sql="SELECT s.*,p.product_code,(p.description)as product_description FROM `stock_management` as s ,product_code as p WHERE s.`invoice_no`= '".$invoice_no."' AND p.product_code_id=s.product_code_id ORDER BY `stock_id` DESC";
        $data = $this->query($sql);
	
		if($data->num_rows)
			return $data->rows;
		else{
			return false;
		}	
			
	} 
	
	public function getRackLabelCanada($col_row,$goods_master_id){
	
	     $explode = explode('@',$col_row);
        $sql="SELECT rack_label FROM rack_label_details WHERE goods_id='".$goods_master_id."' AND row='".$explode[0]."' AND column_name = '".$explode[1]."' AND rack_label!='' LIMIT 1 ";
        $data = $this->query($sql);
        if($data->num_rows){
            return $data->row['rack_label'];
        }else{
			return false;
		}
	
	/*	$sql = "SELECT rack_label,goods_id,row, column_name FROM rack_label_details WHERE row='".$raw."' AND column_name='".$col."' AND goods_id='".$goods_master_id."' GROUP BY goods_id, row, column_name";
	
		$data = $this->query($sql);
	
		if($data->num_rows){
			return $data->row;
		}
		else {
			return false;
		}*/
	}
	
	public function getActiveProduct(){

			$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' ORDER BY product_name ASC";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
	}

	public function getProductSize($product_id)
	{
		$sql  = "SELECT * FROM size_master WHERE product_id = '".$product_id."' GROUP BY volume ORDER BY volume + 0";
		$data = $this->query($sql);

		if($data->num_rows)	{
			return $data->rows;
		}
		else{
			return false;
		}
	}
	    public function getSizeWiseReport($product_id,$volume,$f_date,$t_date)
		{   
		    $str_date_sql='';
        		if(!empty($f_date) && !empty($t_date)){
        	    $str_date_sql=" AND date_added BETWEEN '".$f_date."' AND '".$t_date."'  ";    
        	    }
			$get_product_code="SELECT * FROM product_code WHERE product = '".$product_id."' AND volume = '".$volume."' AND color != -1 ";
			$product_code = $this->query($get_product_code);
			//printr($_SESSION['ADMIN_LOGIN_SWISS']);
			$product_code_array = array();
			foreach($product_code->rows as $product_code_data) {
			    //do something
			    $product_code_array[] = $product_code_data['product_code_id'] ;
			}
			
			
			$session_user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
			//$session_user_type_id=$_SESSION['LOGIN_USER_TYPE'];

			$get_goods_id='SELECT * FROM goods_master WHERE user_id = '.$session_user_id.' AND status =1 ORDER BY goods_master_id ';
		
			$goods_data = $this->query($get_goods_id);
			if($goods_data->num_rows == 0){
			    $get_parent_id=" SELECT user_id FROM `employee` WHERE employee_id ='".$session_user_id."' ";
			    $parent_data = $this->query($get_parent_id);
			    $parent_id=$parent_data->row['user_id'];
			    //printr($parent_id);die;
			    $get_goods_id='SELECT * FROM goods_master WHERE user_id = '.$parent_id.' AND status =1 ORDER BY goods_master_id ';
			    $goods_data = $this->query($get_goods_id);
			} 
		//	printr($goods_data);die;
				//printr($goods_data);
			//printr($goods_data);
			$goods_data_array = array();
			foreach($goods_data->rows as $goods_records) {
			    //do something
			    $goods_data_array[] = $goods_records['goods_master_id'] ;
			}
		
			$size_wise_Data="SELECT *,sum(qty) as tot_qty,GROUP_CONCAT(stock_id ) grouped_s_id,GROUP_CONCAT(concat((qty-dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id FROM stock_management WHERE product_code_id IN (".implode(",",$product_code_array).")  AND goods_id IN (".implode(",",$goods_data_array).") $str_date_sql GROUP by product_code_id ";
			//printr($size_wise_Data);
			//echo $size_wise_Data;
			$final_data=$this->query($size_wise_Data);
			
    
			if($final_data->num_rows)	{
				return $final_data->rows;
			}
			else{
				return false;
			}
		}
        //sonu end
        public function getIBUser()
        {
            $IB_data = $this->query('SELECT * FROM international_branch WHERE is_delete = 0 AND status =1 AND international_branch_id IN (44) ORDER BY international_branch_id');
			if($IB_data->num_rows)	{
				return $IB_data->rows;
			}
			else{
				return false;
			}
        }
        public function getRandomGoodsdata($user_id,$user_type_id)
        {
            $userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);
		    $set_user_id = $user_id;
			$set_user_type_id = $user_type_id;
            $str = $str_per = '';
			if($userEmployee){
				$str = 'OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) ';
				$str_per = 'OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 ) ';
			}
			$menu_id= '325';
			$permission=$this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX ."account_master WHERE add_permission LIKE '%".$menu_id."%' AND edit_permission LIKE '%".$menu_id."%' AND delete_permission LIKE '%".$menu_id."%' AND view_permission LIKE '%".$menu_id."%' AND ((user_id = '".$user_id."' AND user_type_id ='".$user_type_id."') $str_per )");
            //printr($permission);
            $limit = $permission->num_rows*3;
            
            $sql = "SELECT DISTINCT rld.rack_label,gm.goods_master_id,gm.row,gm.column_name,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,sm.row as stock_row,sm.column_name as stock_cloumn,gm.name FROM stock_management as sm,goods_master as gm,rack_label_details rld WHERE sm.is_delete=0 AND ((sm.user_id = '".(int)$set_user_id."' AND sm.user_type_id = '".(int)$set_user_type_id."') $str ) AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0' AND rld.goods_id=gm.goods_master_id AND rld.row=sm.row AND rld.column_name=sm.column_name GROUP BY sm.row,sm.column_name ORDER BY RAND()*CONCAT(sm.row,',',sm.column_name,',',gm.goods_master_id) LIMIT $limit";
			$data = $this->query($sql);
           	if($data->num_rows){
        		$array = array_chunk($data->rows,3);
        	    foreach($array as $key=>$arr)
        		{
        		   $records[$permission->rows[$key]['user_id'].'=='.$permission->rows[$key]['user_type_id']]= $arr; 
        		}
        		return $records;
        	}else{
        		return false;
        	}
        }
        public function sendMailOfToCountPhysicalStock($data,$user) {
    		
    		$user_data = explode('==',$user);
    		$user_info = $this->getUser($user_data[0],$user_data[1]);
    	
    		$html='';
    		$html.='Hello '.$user_info['first_name'].' '.$user_info['last_name'].',<br></br>';
    		$html.='This is system generated E-mail for the Physical Stock verification in ERP.<br><b span="color:red;">This task will be expired in two days.</b><br></br>Please Check stocks for the following racks listed below.<br></br>';
    		$html.='<ol>';
        		foreach($data as $d)
        		{
        		    $html.='<li>'.$d['name'].' : <b>'.$d['rack_label'].'</b></li>';
        		    $expired_date = date('Y/m/d', strtotime('+5 days'));
        		    //$this->query("INSERT INTO `" . DB_PREFIX . "physical_stock_task` SET goods_id= '".$d['goods_master_id']."', row = '".$d['stock_row']."', column_no ='".$d['stock_cloumn']."', rack_label = '".$d['rack_label']."', verify_option = 'Random Stock Verification', verify_by = 'Rack Wise', user_id = '".$user_data[0]."', user_type_id = '".$user_data[1]."', assigned_date = NOW(),expired_on=".$expired_date.", is_delete = 0");
		        }
    		$html.='</ol><b style="color:red;">Note : After counting all the stock from this racks you have to upload this stock in erp for the stock verification process.<b>';
    		
    		$email_temp[]=array('html'=>$html,'email'=>$user_info['email']);
    		$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);
    		$form_email= ADMIN_EMAIL;
    		$obj_email = new email_template();
    		$rws_email_template = $obj_email->get_email_template(1); 
    		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
    			
    		$path = HTTP_SERVER."template/order_template.html";
    		$output = file_get_contents($path);  
    		
    		$search  = array('{tag:header}','{tag:details}');
    		
    		$subject = 'Physical Stock Verification in ERP.';
    		$signature = 'Thanks.';
    	//	printr($email_temp);
    		foreach($email_temp as $val)
    		{
    			$message = '';
    			if($val['html'])
    			{
    				$tag_val = array(
    					"{{productDetail}}" =>$html,
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
    		//printr($message);printr($form_email);printr($subject);printr($val['email']);die;
    		
    		    $email_format[]= array('message'=>$message,
    		                          'from_email'=>$from_email,
    		                          'subject'=>$subject,
    		                          'to_email'=>$val['email']);
    			//send_email($val['email'],$form_email,$subject,$message,'');
    			//send_email('erp@swisspac.net',$form_email,$subject,$message,'','','','1');die;
    		}
    		//printr($email_format);//die;
    		return $email_format;
    	}
    	public function getTask($verify_option,$stock_verify_by)
    	{
    	    $data=$this->query("SELECT * FROM " . DB_PREFIX ."physical_stock_task WHERE verify_option LIKE '".$verify_option."' AND verify_by LIKE '".$stock_verify_by."' AND  user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' AND expired_on>=NOW() AND closed_by_user_on='0000-00-00 00:00:00'");
    	    if($data->num_rows){
        		return $data->rows;
        	}else{
        		return false;
        	}
    	}
    	public function submitTask($data)
    	{  
    	   foreach($data['product_code_id'] as $key=>$post)
	       {   
	           $this->query("INSERT INTO `" . DB_PREFIX . "physical_stock_task_details` SET task_id= '".$data['task_id']."',product_code_id= '".$post."',physically_counted_qty= '".$data['added_qty'][$key]."',original_rack_qty= '".$data['original_qty'][$key]."',user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',date_added= NOW(),  is_delete = 0");
	       }
	    }
	    public function getTaskDetails($task_id)
    	{
    	    $data=$this->query("SELECT * FROM " . DB_PREFIX ."physical_stock_task_details as pd,physical_stock_task p WHERE pd.task_id= '".$task_id."' AND  pd.user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND pd.user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' AND pd.is_delete=0 AND pd.task_id=p.task_id");
    	    if($data->num_rows){
        		return $data->rows;
        	}else{
        		return false;
        	}
    	}
    	public function verify_records($task_ids,$n=0)
    	{
    	    $str='';
    	    if($n==1)
    	        $str  ="AND (pd.original_rack_qty - pd.physically_counted_qty) !=0";
    	    $data=$this->query("SELECT * FROM " . DB_PREFIX ."physical_stock_task_details as pd,physical_stock_task p WHERE pd.task_id IN (".$task_ids.") AND pd.is_delete=0 AND pd.task_id=p.task_id ");
    	    if($data->num_rows){
        		return $data->rows;
        	}else{
        		return false;
        	}
    	}
    	public function send_mail_to_verifyrecords($task_ids)
    	{
    	    $task_details = $this->verify_records($task_ids,1);
    	    
    	    $user_info = $this->getUser($task_details[0]['user_id'],$task_details[0]['user_type_id']);
    	    $html='';
    		$html.='Hello '.$user_info['first_name'].' '.$user_info['last_name'].',<br></br>';
    		$html.="This is system generated E-mail for the Physical Stock verification in ERP.<br></br>When we verify your imported physical stock with our ERP system, we've got a difference for the following between your imported physical stock and our ERP system stock.<br></br>";
    	    $i=1;
    	    
    	    $html.='<table border="1" class="table table-striped m-b-none text-small">
    	                 <thead>
                	        <tr>
                	            <th>Product Code (Description)</th>
                	            <th>Imported Physical Stock Quantity</th>
                	            <th>ERP Stock Quantity</th>
                	            <th>Difference</th>
                	        </tr>
                	    </thead>
                	    <tbody>';
                	    $task_id=0;
                	    foreach($task_details as $task)
                	    {
                	        $desc = $this->getProductCode($task['product_code_id']);
                	        if($task_id!=$task['task_id'])
                	        {
                	           $rack_name = $this->goods_master_detail($task['goods_id']);
                	           $html.='<tr><td colspan="5"><center><h3 style="color:red;">'.$i.'. '.$rack_name['name'].' <b>('.$task['rack_label'].')</h3></center></td><tr>';
                	           $i++;
                	        }    
                	                
                              $html.='<tr>
                                          <td>'.$desc['product_code'].'<br><small>'.$desc['description'].'</small></td>
                                          <td>'.$task['physically_counted_qty'].'</td>
                                          <td>'.$task['original_rack_qty'].'</td>
                                          <td>'.($task['original_rack_qty']-$task['physically_counted_qty']).'</td>';
                              $html.='</tr>';
                	        $task_id = $task['task_id'];
                	    }
                	 $html.='</tbody>
                	</table>';
        
            //printr($html);die;
            $email_temp[]=array('html'=>$html,'email'=>$user_info['email']);
            if($task_details[0]['user_type_id']==2)
			{
				$admininfo = $this->getUser($user_info['user_id'],'4');	
				$email_temp[]=array('html'=>$new_html,'email'=>$admininfo['email']);
			}
    		$form_email= ADMIN_EMAIL;
    		$obj_email = new email_template();
    		$rws_email_template = $obj_email->get_email_template(1); 
    		$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);
    		
    		$path = HTTP_SERVER."template/order_template.html";
    		$output = file_get_contents($path);  
    		
    		$search  = array('{tag:header}','{tag:details}');
    		
    		$subject = 'Inventory Reconciliation Report.';
    		$signature = 'Thanks.';
    		foreach($email_temp as $val)
    		{
    			$message = '';
    			if($val['html'])
    			{
    				$tag_val = array(
    					"{{productDetail}}" =>$html,
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
    			//printr($message);printr($form_email);printr($subject);printr($val['email']);//die;
    			send_email($val['email'],$form_email,$subject,$message,'');
    			//send_email('erp@swisspac.net',$form_email,$subject,$message,'','','','1');die;
    		}//die;
    	    
    	}
    	public function submit_comments($data)
    	{  
    	   $this->query("UPDATE `" . DB_PREFIX . "physical_stock_task` SET submitted_date= NOW()  WHERE task_id = '".$data['task_id']."'");
    	   foreach($data['detail_id'] as $key=>$post)
	       {   
	           $this->query("UPDATE `" . DB_PREFIX . "physical_stock_task_details` SET review = '".$data['comments'][$key]."' WHERE detail_id = '".$post."'");
	       }
	    }
	    public function close_task($task_ids)
    	{   
    	    $this->query("UPDATE `" . DB_PREFIX . "physical_stock_task` SET closed_by_user_on= NOW()  WHERE task_id IN (".$task_ids.")");
    	}
    	public function getProductRackQty($product,$user_type_id,$user_id)
    	{	
    	    if($user_type_id == 1 && $user_id == 1){
    			$sql = "SELECT pc.product_code,pc.description,gm.goods_master_id,gm.name,GROUP_CONCAT(sm.box_no,'=',(sm.qty-sm.dispatch_qty),'=',stock_id) as box_no,GROUP_CONCAT(stock_id) as stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col,sm.rack_label FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND sm.is_delete=0 AND pc.product_code LIKE '%".$product."%' AND gm.goods_master_id = sm.goods_id AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0' AND sm.parent_id=0 GROUP BY sm.product_code_id ";//$str_getrackno//,sm.goods_id
        	     
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
    				$str = 'OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) ';
    			}
        			$sql = "SELECT pc.product_code,pc.description,gm.goods_master_id,gm.name,GROUP_CONCAT(stock_id) as stock_id,GROUP_CONCAT(sm.box_no,'=',(sm.qty-sm.dispatch_qty),'=',stock_id) as box_no,GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,SUM(sm.qty-sm.dispatch_qty) as store_qty,sm.row,sm.column_name,gm.row as g_row,gm.column_name as g_col,sm.rack_label FROM stock_management as sm,product_code as pc,goods_master as gm WHERE pc.product_code_id = sm.product_code_id AND sm.is_delete=0 AND pc.product_code LIKE '%".$product."%' AND ((sm.user_id = '".(int)$set_user_id."' AND sm.user_type_id = '".(int)$set_user_type_id."') $str ) AND gm.goods_master_id = sm.goods_id AND gm.is_delete='0' AND sm.parent_id=0 GROUP BY sm.product_code_id ";
        	}
        	$data = $this->query($sql);
    		if($data->num_rows){
    			return $data->rows;
    		}else{
    			return false;
    		} 
    	}
    public function getOutwardData($f_date,$t_date,$status,$user_type_id,$user_id,$product_code_id=0){
    
    	$str_date_sql=$goods_data='';
		if(!empty($f_date) && !empty($t_date)){
            $str_date_sql=" AND sm.date_added BETWEEN '".$f_date."' AND '".$t_date."'  ";    
	    }
	    if($product_code_id!=0)
            $str_date_sql .=" AND sm.product_code_id = ".$product_code_id;
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT sm.qty,sm.dispatch_qty,pc.description as code_product,pc.product_code,p.product_name,sm.row,sm.column_name,sm.invoice_no,sm. company_name,si.consignee,si.invoice_id FROM stock_management as sm,product as p,product_code as pc,sales_invoice as si WHERE si.invoice_no=sm.invoice_no AND sm.is_delete=0 AND p.product_id=sm.product AND sm.description=2 AND pc.product_code_id = sm.product_code_id  $str_date_sql  ";
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
				$str = ' AND  ( (sm.user_id = '.(int)$set_user_id.' AND sm.user_type_id = '.(int)$set_user_type_id.') OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) )';
			}
               	/*if($set_user_id=='10')
                	$sql = "SELECT sm.qty,sm.date_added,sm.dispatch_qty,pc.description as code_product,pc.product_code,p.product_name,sm.row,sm.column_name,sm.goods_id,sm.user_id,sm.user_type_id,sm.invoice_no,sm.company_name,si.consignee,si.invoice_id,si.is_delete as sales_delete,si.status as s_status FROM stock_management as sm,product as p,product_code as pc,sales_invoice as si WHERE si.invoice_no=sm.invoice_no AND sm.is_delete=0 AND p.product_id=sm.product AND sm.description=2 AND pc.product_code_id = sm.product_code_id    $str_date_sql $str";
                else*/
                    $sql = "SELECT sm.qty,sm.date_added,sm.dispatch_qty,pc.description as code_product,pc.product_code,p.product_name,sm.row,sm.column_name,sm.goods_id,sm.user_id,sm.user_type_id,sm.invoice_no,sm.company_name,si.consignee,si.invoice_id,si.is_delete as sales_delete,si.status as s_status FROM stock_management as sm,product as p,product_code as pc,sales_invoice as si WHERE si.invoice_no=sm.invoice_no AND sm.is_delete=0 AND p.product_id=sm.product AND sm.description=2 AND pc.product_code_id = sm.product_code_id    $str_date_sql $str";
		} 
        $data = $this->query($sql);
        if($data->num_rows) 
			return $data->rows;
		else{
			return false;
		}	
	}
	public function getInwardData($f_date,$t_date,$status,$user_type_id,$user_id,$product_code_id=0){
    
    	$str_date_sql=$goods_data='';
		if(!empty($f_date) && !empty($t_date)){
            $str_date_sql .=" AND sm.date_added BETWEEN '".$f_date."' AND '".$t_date."'  ";    
        }
        if($product_code_id!=0)
            $str_date_sql .=" AND sm.product_code_id = ".$product_code_id;
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT sm.qty,sm.dispatch_qty,pc.description as code_product,pc.product_code,p.product_name,sm.row,sm.column_name,sm.invoice_no,sm. company_name,si.consignee,si.invoice_id FROM stock_management as sm,product as p,product_code as pc,sales_invoice as si WHERE si.invoice_no=sm.invoice_no AND sm.is_delete=0 AND p.product_id=sm.product AND sm.description=2 AND pc.product_code_id = sm.product_code_id  $str_date_sql  ";
		
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
				$str = ' AND  ( (sm.user_id = '.(int)$set_user_id.' AND sm.user_type_id = '.(int)$set_user_type_id.') OR ( sm.user_id IN ('.$userEmployee.') AND sm.user_type_id = 2 ) )';
			}
               	$sql = "SELECT sm.order_no,sm.qty,sm.date_added,sm.dispatch_qty,pc.description as code_product,pc.product_code,p.product_name,sm.row,sm.column_name,sm.goods_id,sm.user_id,sm.user_type_id,sm.invoice_no,sm.company_name,si.consignee,si.invoice_id,si.is_delete as sales_delete,si.status as s_status,si.customer_name as customer_name FROM stock_management as sm,product as p,product_code as pc,invoice_test as si WHERE si.invoice_no=sm.invoice_no AND sm.is_delete=0 AND p.product_id=sm.product AND sm.description=1 AND parent_id=0 AND si.is_delete=0 AND pc.product_code_id = sm.product_code_id   AND si.order_user_id='".$set_user_id."'  $str_date_sql $str";
			} 
	
	    $data = $this->query($sql);
    		if($data->num_rows) 
    			return $data->rows;
    		else{
    			return false;
    		}	
	}
	//kinjal added this function to get box no on dispatch stock page (08-10-2019)
	public function getBoxNo($barcode_number)
	{
		$sql="SELECT box_no FROM in_gen_invoice_test WHERE box_unique_number LIKE '%".$barcode_number."%' AND is_delete='0'";
		$data = $this->query($sql);
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function getDispatchQtywithCustomer($product_code_id,$user_type_id,$user_id,$n,$m){
		$date='';
		if($n==1){
			$d_select="COUNT(DISTINCT si.email) as total ";			
		}else{
			$d_select="SUM(sp.qty) as total";			
			//$d_select="SUM(sp.qty) as total";			
		}

		$curent_date=date("Y-m-d");		
		$f_date = date_create($curent_date);		
		$t_date= date_create($curent_date);		
		date_add($f_date, date_interval_create_from_date_string('-'.$m.' month'));
	
		if($f_date != '')
		{
			$date = " AND si.invoice_date >= '".date_format($f_date, 'Y-m-d')."' ";
		}
		if($t_date != '')
		{
			$date.= " AND  si.invoice_date <='".date_format($t_date, 'Y-m-d')."'";
		}
	
	if($user_type_id == 1 && $user_id == 1){

	//	$sql=" SELECT ".$d_select." FROM stock_management as s,sales_invoice as si,sales_invoice_product as  sp WHERE s.product_code_id = '".$product_code_id."'   AND  sp.product_code_id = '".$product_code_id."' AND   sp.invoice_id=si.invoice_id AND s.qty=0  AND si.is_delete=0 AND s.is_delete=0 AND s.invoice_no=si.invoice_no  ".$date;

		$sql1=" SELECT ".$d_select." FROM sales_invoice as si ,sales_invoice_product as  sp WHERE sp.product_code_id = '".$product_code_id."'  AND sp.invoice_id=si.invoice_id  AND si.is_delete=0  ".$date;

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
				$str = ' AND  ( (si.user_id = '.(int)$set_user_id.' AND si.user_type_id = '.(int)$set_user_type_id.') OR ( si.user_id IN ('.$userEmployee.') AND si.user_type_id = 2 ) )';
			}


	 	$sql1=" SELECT ".$d_select." FROM sales_invoice as si ,sales_invoice_product as  sp WHERE sp.product_code_id = '".$product_code_id."'  $str AND sp.invoice_id=si.invoice_id  AND si.is_delete=0  ".$date;

	
	 }

	
	$data = $this->query($sql1);

		if($data->num_rows)
			return $data->row['total'];
		else
			return false;

	}
}
?>