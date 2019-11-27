<?php
class productstatus extends dbclass{	
	public function getProduct()
	{   
        $sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' ORDER BY product_name ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getProductCd($product_code)
	{
		$result=$this->query("SELECT pc.product_code, pc.product_code_id, pc.description, clr.color, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.product_code LIKE '%".$product_code."%' AND pc.is_delete=0 AND pc.color=clr.pouch_color_id AND p.product_id = pc.product AND pc.status=1");
		return $result->rows;
	}
	public function getColor(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "pouch_color` WHERE status='1' AND is_delete = '0' ORDER BY color  ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getSingleInvoice() {
		$sql = "select * from " . DB_PREFIX ." proforma_invoice_product_code_wise ";
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
	public function getProductCode($product_code_id)
	{
		$sql = "SELECT pc.*,p.product_name,pm.make_name,c.color,tm.measurement,pz.zipper_name,ps.spout_name,pa.product_accessorie_name FROM `" . DB_PREFIX . "product_code` AS pc LEFT JOIN product AS p ON pc.product=p.product_id LEFT JOIN product_make pm ON pc.make_pouch=pm.make_id LEFT JOIN pouch_color AS c ON c.pouch_color_id=pc.color LEFT JOIN template_measurement AS tm ON pc.measurement=tm.product_id  LEFT JOIN product_zipper AS pz ON pc.zipper=pz.product_zipper_id LEFT JOIN product_spout AS ps ON ps.product_spout_id=pc.spout LEFT JOIN product_accessorie AS pa ON pa.product_accessorie_id=pc.accessorie WHERE pc.product_code_id='".$product_code_id."' AND pc.is_delete=0 AND pc.status=1 ";
    	$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getProductCdAll($product_id,$volume,$color)
	{	
		$result=$this->query("SELECT pc.product_code, pc.product_code_id, pc.description, clr.color, pc.volume, pc.measurement,p.product_name,pc.product,pc.zipper FROM " . DB_PREFIX ."product_code as pc, pouch_color as clr, product as p WHERE pc.is_delete=0 AND pc.color=clr.pouch_color_id AND pc.product=p.product_id AND pc.product = '".$product_id."' AND pc.volume = '".$volume."' AND pc.color = '".$color."' " );
    	return $result->rows;
	}
	
	public function getGoodsStatus($filter_data=array(),$user_type_id,$user_id){
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0'";
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
			$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND ( user_id='".(int)$set_user_id."' 
			AND user_type_id='".(int)$set_user_type_id."' $str )";
		}
		
		if(!empty($filter_data)){
			
			if(!empty($filter_data['column_name'])){
				$sql .= " AND column_name='".$filter_data['column_name']."' ";
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
	public function getproduct_status($product_code_id,$user_type_id,$user_id,$n=''){
		
		$admin_user_id=$order_user_id='';
		if($n==1){
		    $group="GROUP BY sm.product_code_id";
		}else{
		    $group="GROUP BY sm.row,sm.column_name";
		}
		if($user_type_id == 1 && $user_id == 1){
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0'";
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
				$str = " user_id='".(int)$set_user_id."' AND user_type_id='".(int)$set_user_type_id."' OR ( user_id IN ('.$userEmployee.') AND user_type_id = 2 )";
			}
		    $admin_user_id=' AND pto.admin_user_id = '.$set_user_id; 
		    $order_user_id=' AND i.order_user_id = '.$set_user_id;
			if($set_user_id=='33' || $set_user_id=='24')//for aus melbourne and sydney
			{
			    if($set_user_id==33)
			        $other_id='24';
			    else 
			        $other_id='33';
			        
			    $userEmployeeother = $this->getUserEmployeeIds(4,$other_id);    
			    $str=" user_id IN (".$set_user_id.",".$other_id.") AND user_type_id='4' OR ( user_id IN (".$userEmployee.",".$userEmployeeother.") AND user_type_id = 2 ) ";
			}
			$sql = "SELECT * FROM `" . DB_PREFIX . "goods_master` WHERE is_delete = '0' AND ( $str ) ORDER BY user_id ASC";
		}
		
		$data = $this->query($sql);
		$f_val=array();
		if($data->num_rows)
		{
			foreach($data->rows as $val)
			{
				$sql2 = "SELECT sm.user_id,sm.user_type_id,sum(sm.qty)qty, GROUP_CONCAT(concat((sm.qty-sm.dispatch_qty),':',stock_id)separator ',' ) grouped_stock_id,GROUP_CONCAT(stock_id) grouped_s_id,p.product_name ,gm.goods_master_id,gm.name,sm.row,sm.column_name,pc.product_code,sm.stock_id,gm.row as g_row,gm.column_name as g_col ,pc.description as pro_description FROM stock_management as sm,product as p,product_code as pc,goods_master AS gm WHERE sm.is_delete=0 AND p.product_id=sm.product AND sm.goods_id = gm.goods_master_id AND  sm.goods_id='".$val['goods_master_id']."' AND pc.product_code_id = sm.product_code_id  AND sm.product_code_id='".(int)$product_code_id."' AND parent_id=0  AND gm.is_delete = '0'AND sm.qty!=0 AND sm.row!=0 AND sm.column_name!=0 $group ORDER BY sm.user_id ASC";
			    $data2 = $this->query($sql2);					
			//  printr($sql2);
			  //  printr($data2);
				foreach($data2->rows as $data_arr)
				{
				    $f_val[]=$data_arr;
				}	
			}	
		}
			
		$sql_pro = "SELECT pc.*,pa.product_accessorie_name,pz.zipper_name,ps.spout_name,tm.measurement From  product_code as pc, product_zipper as pz,product_spout as ps,product_accessorie as pa,template_measurement as tm WHERE pc.product_code_id = '".$product_code_id."' AND pc.zipper=pz.product_zipper_id AND ps.product_spout_id=pc.spout AND pa.product_accessorie_id=pc.accessorie AND  pc.measurement=tm.product_id";
		$data_pro = $this->query($sql_pro);
	
	    $meas=$data_pro->row['volume'].' '.$data_pro->row['measurement'];
		$sql3 ="SELECT t.product_template_order_id,t.buyers_order_no,t.template_order_id,cd.client_name,st.gen_order_id,t.date_added,c.country_name,t.ship_type,t.client_id,t.user_id,t.user_type_id,sos.status,t.product_id,t.template_id ,t.quantity,t.expected_ddate,sos.order_status_id  FROM template_order_test t,client_details cd,stock_order_test st,country c,stock_order_status_test as sos,product_template_order_test as pto,product_template as pt,product_template_size as pts WHERE c.country_id = t.country AND cd.client_id = t.client_id AND t.is_delete = 0 AND sos.status=1 AND t.status=1 AND t.template_order_id=sos.template_order_id AND st.client_id=t.client_id AND st.admin_user_id=pto.admin_user_id AND t.product_template_order_id=pto.order_id AND pt.product_template_id=t.template_id AND t.template_size_id=pts.product_template_size_id AND pts.template_id = pt.product_template_id AND st.stock_order_id=t.stock_order_id AND t.product_id = '".$data_pro->row['product']."' AND pts.valve = '".$data_pro->row['valve']."' AND pts.zipper = '".$data_pro->row['zipper_name']."' AND pts.spout = '".$data_pro->row['spout_name']."' AND pts.accessorie = '".$data_pro->row['product_accessorie_name']."' AND volume = '".$meas."' AND t.color = '".$data_pro->row['color']."' $admin_user_id";
	    $data3=$this->query($sql3);	
		
		$stock=array();
		if($data3->num_rows)
		{ 
			foreach($data3->rows as $key=>$data_val)
			{
			 	$data3->rows['product_code']=$data_pro->row['product_code'];
				$stock[$key]['gen_order_id']=$data_val['gen_order_id'];
				$stock[$key]['expected_ddate']=$data_val['expected_ddate'];
				$stock[$key]['template_order_id']=$data_val['template_order_id'];
				$stock[$key]['product_template_order_id']=$data_val['product_template_order_id'];										
				$stock[$key]['quantity']=$data_val['quantity'];
				$stock[$key]['product_code']=$data_pro->row['product_code'];
				$data_dis =$this->query("SELECT sum(dis_qty) as total_dis_qty FROM stock_order_dispatch_history WHERE template_order_id='".$data_val['template_order_id']."' AND product_template_order_id='".$data_val['product_template_order_id']."' ");
				$stock[$key]['total_dis_qty']=$data_dis->row['total_dis_qty'];
				
			}
	    }
	    
	    $sql4 ="SELECT ic.qty, i.invoice_no,i.tracking_no,i.trackinfo, i.transportation FROM  " . DB_PREFIX . "invoice_test as i,invoice_color_test as ic,invoice_product_test as ip WHERE i.is_delete=0 AND i.invoice_id=ic.invoice_id AND i.invoice_id=ip.invoice_id AND ic.invoice_product_id=ip.invoice_product_id AND ip.product_code_id = '".$product_code_id."' AND i.import_status!=2 AND i.is_delete=0 $order_user_id";
	    $data4=$this->query($sql4);	
		
		$order=array();
		if($data4->num_rows)
		{ 
			foreach($data4->rows as $key=>$data1)
			{
			 	$order[$key]['product_code']=$data_pro->row['product_code'];
				$order[$key]['invoice_no']=$data1['invoice_no'];
				$order[$key]['tracking_no']=$data1['tracking_no'];
				$order[$key]['trackinfo']=$data1['trackinfo'];										
				$order[$key]['transportation']=decode($data1['transportation']);
				$order[$key]['qty']=$data1['qty'];
				
			}
	    }
		$comb_arr=array();
		$comb_arr['stock']=$stock;
		$comb_arr['rack']=$f_val;
		$comb_arr['order']=$order;
		return json_encode($comb_arr);	
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
	public function getUserEmployeeIds($user_type_id,$user_id){
		$sql = "SELECT GROUP_CONCAT(employee_id) as ids FROM " . DB_PREFIX . "employee WHERE user_type_id = '".(int)$user_type_id."' AND user_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['ids'];
		}else{
			return false;
		}
	}
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
    public function getCSVData($handle,$charge)
	{
		
		$data=array();
		$first_time = $time = $t = true;
		
        //loop through the csv file 
		$array = '';
		while($data = fgetcsv($handle,1000,","))
		{
		    
		  
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
			$product_code_id=$this->getProductCodeId($data[0]);
	
			if(empty($product_code_id))
			{
			    $array[$data[0]][] = '<b>'.$data[0].'</b>   '.$data[1];
			}
			// printr($data);//die;
		}
	//	printr($array);
	//	die;
		return $array;
	}
	public function GetCompareCSVData($handle,$charge)
	{
		
		$data=$array=array();
		$first_time = $time = $t = true;
		
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	
        //loop through the csv file 
		while($data = fgetcsv($handle,1000,","))
		{
		    
		  
			if ($first_time == true) {
				$first_time = false;
				continue;
			}
			$product_code_id=$this->getProductCodeId($data[0]);
				
			if(!empty($product_code_id))
			{ 
			    $stock_data =$this->getproduct_status($product_code_id['product_code_id'],$user_type_id,$user_id,1);
		        $final_stock=json_decode($stock_data);  
		         if(!empty($final_stock->rack)){
		              $final_stock->rack[0]->xero_qty=$this->numberFormate($data[2],2);
		         }else{
		             $final_stock->rack[0]->xero_qty=$this->numberFormate($data[2],2);
		             $final_stock->rack[0]->pro_description=$data[1];
		             $final_stock->rack[0]->qty=0;
		         }
		        $array[$data[0]]=$final_stock->rack;
			 
			}

		}

		return $array;
	}
	public function getProductCodeId($product_code)
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_code` WHERE product_code='".$product_code."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function numberFormate($number,$decimalPoint=3){

			return number_format($number,$decimalPoint,".","");
 
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
	}
	
}
?>
