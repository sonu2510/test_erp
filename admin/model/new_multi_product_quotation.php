<?php 
class multiProductQuotation extends dbclass{

	public function getActiveProduct(){
		//[kinjal] : add 24 id to hide ultra product in product selection (17-5-2016)
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' AND product_id IN(5,1,20,7,19,10,9,3,4,6) ";//,55,56,57 for rice packaging //58,59 for Agarbatti packaging //65 standing pouch
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
		return $data->rows;
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
	
	public function getQuotationSelectedData($quotation_id,$selected,$user_type_id="",$user_id=""){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT $selected FROM " . DB_PREFIX ."new_multi_product_quotation as mp,new_multi_product_quotation_id as mpi WHERE mp.product_quotation_id = '".(int)$quotation_id."' AND mp.multi_product_quotation_id=mpi.multi_product_quotation_id";
			}else{
				$sql = "SELECT $selected FROM " . DB_PREFIX ."new_multi_product_quotation as mp,new_multi_product_quotation_id as mpi WHERE mp.added_by_user_id = '".(int)$user_id."' AND mp.added_by_user_type_id = '".(int)$user_type_id."' AND mp.product_quotation_id = '".(int)$quotation_id."' AND mp.multi_product_quotation_id=mpi.multi_product_quotation_id";
			}
		}else{
			$sql = "SELECT $selected FROM " . DB_PREFIX ."new_multi_product_quotation as mp,new_multi_product_quotation_id as mpi WHERE mp.product_quotation_id = '".(int)$quotation_id."' AND mp.multi_product_quotation_id=mpi.multi_product_quotation_id";
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	//copy by kinjal on (3-12-2018) for downloading pdf from quotation view page
	public function getQuotationSelectedData1($quotation_id,$selected,$user_type_id="",$user_id=""){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT $selected FROM " . DB_PREFIX ."new_multi_product_quotation as mp,new_multi_product_quotation_id as mpi WHERE mpi.multi_product_quotation_id = '".(int)$quotation_id."' AND mp.multi_product_quotation_id=mpi.multi_product_quotation_id";
			}else{
				$sql = "SELECT $selected FROM " . DB_PREFIX ."new_multi_product_quotation as mp,new_multi_product_quotation_id as mpi WHERE mp.added_by_user_id = '".(int)$user_id."' AND mp.added_by_user_type_id = '".(int)$user_type_id."' AND mpi.multi_product_quotation_id = '".(int)$quotation_id."' AND mp.multi_product_quotation_id=mpi.multi_product_quotation_id";
			}
		}else{
			$sql = "SELECT $selected FROM " . DB_PREFIX ."new_multi_product_quotation as mp,new_multi_product_quotation_id as mpi WHERE mpi.multi_product_quotation_id = '".(int)$quotation_id."' AND mp.multi_product_quotation_id=mpi.multi_product_quotation_id";
		}
		$data = $this->query($sql);
		//printr($sql);
		return $data->rows;
	}
	public function getTotalQuotation($user_type_id,$user_id,$filter_array=array(),$cond,$add_book_id='0'){

	$add_id='';
			if($add_book_id!=0)
				$add_id = "AND mpq.address_book_id='". $add_book_id ."'";

		if($user_type_id == 1 && $user_id == 1){
		$sql = "SELECT c.country_name,pq.status,pq.product_quotation_id,pq.multi_product_quotation_id,pq.customer_name,pq.product_name, pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_status,pq.quotation_type,pq.date_added,pq.layer, pq.use_device,pqp.zipper_txt,pqp.valve_txt,
		pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number FROM new_multi_product_quotation pq,country c,new_multi_product_quotation_price pqp,new_multi_product_quotation_id as mpq WHERE c.country_id = pq.shipment_country_id AND
		pq.product_quotation_id = pqp.product_quotation_id AND 1=1 AND mpq.multi_product_quotation_id=pq.multi_product_quotation_id $add_id";
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
		  	//comment by sonu 15-3-2017  told by vikas sir
		  	$sql = "SELECT pq.*,pqp.valve_txt,c.country_name,pqp.zipper_txt,pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number  FROM new_multi_product_quotation pq ,country as c, new_multi_product_quotation_id as mpq , new_multi_product_quotation_price  as pqp  WHERE  mpq.multi_product_quotation_id=pq.multi_product_quotation_id  AND  pq. shipment_country_id = c.country_id AND pq.product_quotation_id=pqp.product_quotation_id AND   pq.admin_user_id = '".(int)$set_user_id."' $add_id";
		}
		if(!empty($filter_array)) {
			if(!empty($filter_array['quotation_no'])){
				$sql .= " AND mpq.multi_quotation_number = '".$filter_array['quotation_no']."'";
			}
			
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND pq.customer_name LIKE '%".addslashes($filter_array['customer_name'])."%'";
			}
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(pq.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}
			
			if(!empty($filter_array['layer'])){
				$sql .= " AND pq.layer = '".$filter_array['layer']."'";
			}
			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND pq.product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND pq.shipment_country_id = '".$filter_array['country']."'";
			}
			
			if(!empty($filter_array['option'])){
				$sql .= " AND pq.option_id = '".$filter_array['option']."'";
			}
			if(!empty($filter_array['postedby']))
			{
			$spitdata = explode("=",$filter_array['postedby']);
				$sql .="AND pq.added_by_user_type_id = '".$spitdata[0]."' AND pq.added_by_user_id = '".$spitdata[1]."'";
			}
		}
		if(!empty($cond)){
				$sql .= $cond;
			}
		$sql .= "GROUP BY pq.multi_product_quotation_id";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->num_rows;
		}else{
			return false;
		}
		
		
	}
	
	public function getQuotations($user_type_id,$user_id,$data,$filter_array=array(),$add_book_id='0'){			
			$add_id='';
			if($add_book_id!=0)
				$add_id = "AND mpq.address_book_id='". $add_book_id ."'";
					
		if($user_type_id == 1 && $user_id == 1){
		$sql = "SELECT c.country_name,pq.status,pq.product_quotation_id,pq.multi_product_quotation_id,pq.customer_name,pq.product_name, pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_status,pq.quotation_type,pq.date_added,pq.layer, pq.use_device,pqp.zipper_txt,pqp.valve_txt,
		pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number FROM new_multi_product_quotation pq,country c,new_multi_product_quotation_price pqp,new_multi_product_quotation_id as mpq WHERE c.country_id = pq.shipment_country_id AND
		pq.product_quotation_id = pqp.product_quotation_id AND 1=1 AND mpq.multi_product_quotation_id=pq.multi_product_quotation_id $add_id ";
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
			$sql = "SELECT pq.*,pqp.valve_txt,c.country_name,pqp.zipper_txt,pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number  FROM new_multi_product_quotation pq ,country as c, new_multi_product_quotation_id as mpq , new_multi_product_quotation_price  as pqp  WHERE  mpq.multi_product_quotation_id=pq.multi_product_quotation_id  AND  pq. shipment_country_id = c.country_id AND pq.product_quotation_id=pqp.product_quotation_id AND   pq.admin_user_id = '".(int)$set_user_id."' $add_id ";
		}

		if(!empty($filter_array)) {
			if(!empty($filter_array['quotation_no'])){
				$sql .= " AND mpq.multi_quotation_number = '".$filter_array['quotation_no']."'";
			}
			
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND pq.customer_name LIKE '%".addslashes($filter_array['customer_name'])."%'";
			}
			
			if(!empty($filter_array['date'])){
				$sql .= " AND date(pq.date_added) = '".date('Y-m-d',strtotime($filter_array['date']))."'";
			}
			
			if(!empty($filter_array['layer'])){
				$sql .= " AND pq.layer = '".$filter_array['layer']."'";
			}
			
			if(!empty($filter_array['product_name'])){
				$sql .= " AND pq.product_name = '".$filter_array['product_name']."'";
			}
			
			if(!empty($filter_array['country'])){
				$sql .= " AND pq.shipment_country_id = '".$filter_array['country']."'";
			}
			
			if(!empty($filter_array['option'])){
				$sql .= " AND pq.option_id = '".$filter_array['option']."'";
			}
			if(!empty($filter_array['postedby']))
			{
			$spitdata = explode("=",$filter_array['postedby']);
				$sql .="AND pq.added_by_user_type_id = '".$spitdata[0]."' AND pq.added_by_user_id = '".$spitdata[1]."'";
			}
		}
		if(!empty($data['cond'])){
				$sql .= $data['cond'];
			}
					$sql .= "GROUP BY pq.multi_product_quotation_id";
		if (isset($data['sort'])) {
			
				
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pq.multi_product_quotation_id";	
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
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
		public function getQuotation($quotation_id,$getData = '*',$user_type_id='',$user_id=''){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
			$sql = "SELECT $getData,cn.country_name,mpqi.multi_quotation_number,mpqi.address_book_id  FROM " . DB_PREFIX ."new_multi_product_quotation pq INNER JOIN " . DB_PREFIX ."country cn ON (pq.shipment_country_id=cn.country_id) INNER JOIN new_multi_product_quotation_id as mpqi ON(pq.multi_product_quotation_id=mpqi.multi_product_quotation_id)  WHERE pq.multi_product_quotation_id = '".(int)$quotation_id."'";
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
					$str = ' OR ( pq.added_by_user_id IN ('.$userEmployee.') AND pq.added_by_user_type_id = 2 ) ';
				}
				$str .= ' ) ';
				
			$sql = "SELECT $getData,cn.country_name,mpqi.multi_quotation_number,mpqi.address_book_id  FROM " . DB_PREFIX ."new_multi_product_quotation pq INNER JOIN " . DB_PREFIX ."country cn ON (pq.shipment_country_id=cn.country_id) INNER JOIN " . DB_PREFIX ."new_multi_product_quotation_id mpqi ON (pq.multi_product_quotation_id=mpqi.multi_product_quotation_id) WHERE  pq.multi_product_quotation_id = '".(int)$quotation_id."' AND (( pq.added_by_user_id = '".(int)$set_user_id."' AND pq.added_by_user_type_id = '".(int)$set_user_type_id."') $str ";
				
			}
		}else{
			$sql = "SELECT $getData,mpqi.multi_quotation_number,mpqi.address_book_id FROM " . DB_PREFIX ."new_multi_product_quotation pq,new_multi_product_quotation_id mpqi WHERE pq.multi_product_quotation_id=mpqi.multi_product_quotation_id  AND pq.multi_product_quotation_id = '".(int)$quotation_id."'";
			 
		}
		
		$data = $this->query($sql);
		return $data->rows;
	}

	public function getNewCurrencys(){
		if($_SESSION['ADMIN_LOGIN_SWISS'] == 1 && $_SESSION['LOGIN_USER_TYPE'] == 1)
		{
		   	$data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."'");
		}
		else
		{
    		if($_SESSION['LOGIN_USER_TYPE'] == 2){
    			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
    			$set_user_id = $parentdata->row['user_id'];
    			$set_user_type_id = $parentdata->row['user_type_id'];
    		    if($set_user_id=='10')
    			    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");
                elseif($_SESSION['ADMIN_LOGIN_SWISS']=='37' || $_SESSION['ADMIN_LOGIN_SWISS']=='199')
                    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");
                else
                    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");

    		}else{
    			$set_user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    			$set_user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    		    $data = $this->query("SELECT cn.currency_code,cs.currency_id,cs.price FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$set_user_id."' AND user_type_id = '".$set_user_type_id."'");

    		}
    		

		}
		if($data->num_rows){
			return  $data->rows;
		}else{
			return false;
		}
	}
	
	public function getQuotationMaterial($quotation_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."new_multi_product_quotation_layer WHERE product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function getQuotationQuantity($quotation_id){
		$paking_price=$this->getQuotationPackingAndTransportDetails($quotation_id);
		$data = $this->query("SELECT mpq.quantity_type,mpq.product_id,mpqq.product_quotation_quantity_id, mpqq.product_quotation_id,mpqq.discount, printing_effect,mpqq.quantity,total_kgs,total_mtr,total_piece,mpq.height,mpq.width,mpq.size_id,mpq.gusset,mpq.extra_profit,mpq.volume, wastage_adding, wastage_base_price, wastage,packing_base_price, packing_charge, profit_base_price, profit, total_weight_without_zipper, mpq.layer,total_weight_with_zipper,cylinder_price,
		cylinder_price_withtax,tool_price,tool_price_withtax,gress_percentage,gress_air,gress_sea,customer_gress_percentage,mpq.adhesive_price,mpq.cpp_adhesive,mpq.adhesive_solvent_price,mpq.ink_price,mpq.cust_ink_mul_by,mpq.cust_adhesive_mul_by,mpq.ink_multi_by,mpq.ink_solvent_price FROM " . DB_PREFIX ."new_multi_product_quotation_quantity as mpqq,new_multi_product_quotation as mpq WHERE mpqq.product_quotation_id = '".(int)$quotation_id."' AND mpqq.product_quotation_id=mpq.product_quotation_id ORDER BY mpqq.product_quotation_quantity_id") ;
		$return = array();
		if($data->num_rows){
			foreach($data->rows as $qunttData){ 
				$zdata = $this->query("SELECT tamperevident,slider_place,gress_per,gress_cyli_per,cyli_gress_price,handle_id,handle_price,actual_laser_price,	laser_id,product_quotation_price_id, product_quotation_id, product_quotation_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt,spout_pouch_type,accessorie_txt,gusset_printing_type,total_price,total_price_with_excies,total_price_with_tax,tax_name,tax_type,tax_percentage,excies,igst,cgst,sgst, gress_price, customer_gress_price,zipper_price,valve_price,actual_courier_price,fuel_charge,service_tax,handling_charge,courier_charge,spout_base_price,accessorie_base_price,transport_price,	spout_transport_price,make_name,make_name_spanish,make_name_spanish_uk,make_name,make_name_italian,make_pouch,accessorie_base_price_corner,accessorie_txt_corner FROM " . DB_PREFIX ."new_multi_product_quotation_price as mpqp ,product_make as pm WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' AND mpqp.make_pouch=pm.make_id ORDER BY transport_type DESC");	
				if($zdata->num_rows){
					 
					if((isset($zdata->rows[0]['igst']) && $zdata->rows[0]['igst']>0) || ((isset($zdata->rows[0]['cgst']) && $zdata->rows[0]['cgst']>0)&&(isset($zdata->rows[0]['sgst']) && $zdata->rows[0]['sgst']>0)))
					{
						if($zdata->rows[0]['zipper_txt'][0]=='T')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper With Tin Tie' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit' => $qunttData['profit'],	
							'Tax Name'=> str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])));
						
						}
						else
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit' => $qunttData['profit'],
							'Tax Name'=> str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])));
						}
						if($qunttData['product_id']=='6')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Kgs'=>$qunttData['total_kgs'],
							'Total Mtr'=>$qunttData['total_mtr'],
							'Total Pieces'=>floor($qunttData['total_piece']),
							'Total Pieces Per Kg'=>floor($qunttData['total_piece']/$qunttData['total_kgs']),
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit Per Kg' => $qunttData['profit_base_price'],
							'Tax Name'=> str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])));
						}
						if(isset($zdata->rows[0]['igst']) && $zdata->rows[0]['igst']>0)
						{
							$quantity_option[$qunttData['quantity']]['IGST'] = $zdata->rows[0]['igst'].' %';
						}
						else
						{
							$quantity_option[$qunttData['quantity']]['CGST'] = $zdata->rows[0]['cgst'].' %';
							$quantity_option[$qunttData['quantity']]['SGST'] = $zdata->rows[0]['sgst'].' %';
						}
						if($zdata->rows[0]['spout_txt'] != 'No Spout')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper With Spout' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit' => $qunttData['profit'],
							);
						}
					}
					elseif(isset($zdata->rows[0]['excies']) && $zdata->rows[0]['excies']>0)
					{
						
						if($zdata->rows[0]['zipper_txt'][0]=='T')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper With Tin Tie' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit' => $qunttData['profit'],
							'Excies' => $zdata->rows[0]['excies'].' %',
							'tax_name'=>$zdata->rows[0]['tax_name'],
							str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])) => $zdata->rows[0]['tax_percentage'].' %');
						
						
						}
						else
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit' => $qunttData['profit'],
							'Excies' => $zdata->rows[0]['excies'].' %',
							'tax_name'=>$zdata->rows[0]['tax_name'],
							str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])) => $zdata->rows[0]['tax_percentage'].' %');
						}
						
						if($zdata->rows[0]['spout_txt'] != 'No Spout')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper With Spout' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit' => $qunttData['profit'],
							);
						}
						if($qunttData['product_id']=='6')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Kgs'=>$qunttData['total_kgs'],
							'Total Mtr'=>$qunttData['total_mtr'],
							'Total Pieces'=>floor($qunttData['total_piece']),
							'Total Pieces Per Kg'=>floor($qunttData['total_piece']/$qunttData['total_kgs']),
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit Per Kg' => $qunttData['profit_base_price'],
							'Tax Name'=>$zdata->rows[0]['tax_name'],
							);
						}
					}
					else
					{
						if($zdata->rows[0]['zipper_txt'][0]=='T')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper With Tin Tie' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit' => $qunttData['profit'],
							);
						}
						else
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit' => $qunttData['profit'],
							);
							
						}
						
						if($zdata->rows[0]['spout_txt'] != 'No Spout')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper With Spout' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'].' %',
							'Profit' => $qunttData['profit'],
							);
						}
						if($qunttData['product_id']=='6')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Kgs'=>$qunttData['total_kgs'],
							'Total Mtr'=>$qunttData['total_mtr'],
							'Total Pieces'=>floor($qunttData['total_piece']),
							'Total Pieces Per Kg'=>floor($qunttData['total_piece']/$qunttData['total_kgs']),
							'Wastage ' => $qunttData['wastage_base_price'].' %',
							'Profit Per Kg' => $qunttData['profit_base_price'],
							);
						}
					}
					foreach($zdata->rows as $zipData){
						$materialData = $this->getQuotationMaterial($zipData['product_quotation_id']);
						$new_tax[$qunttData['quantity']] =  array('Excies' => $zipData['excies']);
						$zipper_option =
						 array(
							'zipper_price' => $zipData['zipper_price'],
							'valve_price' => $zipData['valve_price'],
							'courier_charge' => $zipData['courier_charge']  ,
							'spout_price' => $zipData['spout_base_price'],
							'accessorie_price' => $zipData['accessorie_base_price'],
							'accessorie_price_corner' => $zipData['accessorie_base_price_corner'],
							
						);
						$laser_type ='';
						if($zipData['laser_id']!=0)
							$laser_type=$this->getLaserType($zipData['laser_id']);
						
						$handle_type['handle_name'] ='';
						if($zipData['handle_id']!=0)
							$handle_type=$this->getHandleType($zipData['handle_id']);
						
						$tamper = '';
						if($zipData['slider_place']!='')
						{
						    
    						if($zipData['slider_place'] == 'Slider Zipper On Top Of Pouch')
    						{
    						    $zipData['slider_place'] = 'Zipper On Top Of Pouch';
    						    $tamper = $zipData['slider_place'].' '.$zipData['tamperevident'];
    						}
    						else
    						{
    						    $zipData['slider_place'] = 'Zipper Inside Of Pouch';
    						    $tamper = $zipData['slider_place'];
    						}
						}
						//printr($zipData['slider_place'] );
						$txt = $zipData['zipper_txt'].' , '.$laser_type.' , '.$zipData['valve_txt'];
						$txt .= ',<br>'.$zipData['spout_txt'].' , With '.$zipData['accessorie_txt'].' , ';
						$txt .= '<br> '.$zipData['accessorie_txt_corner'].'<br>'.$tamper.'<br> ';
						$txt .=$handle_type['handle_name'];
						if($zipData['spout_txt']=='No Spout')
						{
							$zipData['spout_txt']='';
							$spout_txt = 'No Spout';
						}
						else
						{
							$spout_txt = $zipData['spout_txt'];
							$zipData['spout_txt']=' With '.$zipData['spout_txt'];
						}
						if($zipData['accessorie_txt']=='No Accessorie')
							$zipData['accessorie_txt']='';
						else
							$zipData['accessorie_txt']=' With '.$zipData['accessorie_txt'];
							
							
						if($zipData['spout_txt']=='No Spout')
								$zipData['spout_txt'] = $zipData['spout_txt'];
						else
								$zipData['spout_txt'] = $zipData['spout_txt'] .' ( '.$zipData['spout_pouch_type'].' )';
						$email_txt = $zipData['zipper_txt'].' , '.$zipData['valve_txt'];
						$email_txt .= '<br> '.$zipData['spout_txt'].' , '.$zipData['accessorie_txt'].'';
							$return[$zipData['transport_type']][$qunttData['quantity']][] = array(
								'text' 		=> $txt,
								'email_text' 		=> $email_txt,
								'totalPrice'	   =>  $this->numberFormate(($zipData['total_price'] + $zipData['gress_price']),"3"),
								'totalPriceWithExcies'	   =>  $this->numberFormate(($zipData['total_price_with_excies']),"3"),
								'totalPriceWithTax'	   =>  $this->numberFormate(($zipData['total_price_with_tax']),"3"),
								'tax_type'	   => $zipData['tax_type'] ,
								'tax_percentage'	   => $zipData['tax_percentage'] ,
								'excies'	   => $zipData['excies'] ,
								'igst'	   => $zipData['igst'] ,
								'sgst'	   => $zipData['sgst'] ,
								'cgst'	   => $zipData['cgst'] ,
								'tax_name'=>$zipData['tax_name'],
								'customerGressPrice' => $zipData['customer_gress_price'],
								'product_quotation_quantity_id'=>$zipData['product_quotation_quantity_id'],
								'discount'=>$qunttData['discount'],
								'width'=>$qunttData['width'],
								'size_id'=>$qunttData['size_id'],
								'height'=>$qunttData['height'],
								'gusset'=>$qunttData['gusset'],
								'volume'=>$qunttData['volume'],
								'extra_profit'=>$qunttData['extra_profit'],
								'cylinder_price'=>$qunttData['cylinder_price'],
								'tool_price'=>$qunttData['tool_price'],
								'cylinder_price_withtax'=>$qunttData['cylinder_price_withtax'],
								'tool_price_withtax'=>$qunttData['tool_price_withtax'],
								'quantity_option'	=> $quantity_option[$qunttData['quantity']],
								'zipper_option' => $zipper_option,
								'courier_charge' => $zipData['courier_charge'],
								'actual_courier_price' => $zipData['actual_courier_price'],
								'fuel_charge' => $zipData['fuel_charge'],
								'service_tax' => $zipData['service_tax'],
								'handling_charge' => $zipData['handling_charge'],
								'transport_price' => $zipData['transport_price'] ,
								'spout_transport_price' => $zipData['spout_transport_price'],
								'packing_price'=>$paking_price['packing_price'],
								'packing_base_price'=>$qunttData['packing_base_price'],
								'spout_additional_packing_price'=>$paking_price['spout_additional_packing_price'],
								'gress_price'  => $zipData['gress_price'],
								'gress_percentage' => $qunttData['gress_percentage'],
								'gress_sea' => $qunttData['gress_sea'],
								'gress_air' => $qunttData['gress_air'],
								'customer_gress_percentage' => $qunttData['customer_gress_percentage'],
								'zipper_txt' => $zipData['zipper_txt'],
								'valve_txt' => $zipData['valve_txt'],
								'spout_txt' => $spout_txt,
								'accessorie_txt' => $zipData['accessorie_txt'],
								'accessorie_txt_corner' => $zipData['accessorie_txt_corner'],
								'gusset_printing_type' =>$zipData['gusset_printing_type'],
								'spout_pouch_type' =>$zipData['spout_pouch_type'],
								'printing_effect' => $qunttData['printing_effect'],
								'materialData'=>$materialData,
								'make' => $zipData['make_name'],
								'make_name_spanish'=>utf8_encode($zipData['make_name_spanish']),
								'make_name_spanish_uk'=>utf8_encode($zipData['make_name_spanish_uk']),
								'make_name_italian'=>utf8_encode($zipData['make_name_italian']),
								'make_id' => $zipData['make_pouch'],
								'product_quotation_price_id'=>$zipData['product_quotation_price_id'],
								'layer'=>$qunttData['layer'].''.$zipData['product_quotation_id'],
								'product_quotation_quantity_id'=>$zipData['product_quotation_quantity_id'],
								'ink_price'=>$qunttData['ink_price'],
								'ink_multi_by'=>$qunttData['ink_multi_by'],
								'cust_ink_mul_by'=>$qunttData['cust_ink_mul_by'],
								'cust_adhesive_mul_by'=>$qunttData['cust_adhesive_mul_by'],
								'ink_solvent_price'=>$qunttData['ink_solvent_price'],
								'adhesive_price'=>$qunttData['adhesive_price'],
								'cpp_adhesive'=>$qunttData['cpp_adhesive'],
								'adhesive_solvent_price'=>$qunttData['adhesive_solvent_price'],
								'laser_type'=>$laser_type, 
								'laser_price'=> $zipData['actual_laser_price'],
								'handle_name'=>$handle_type['handle_name'],
								'handle_price'=>$zipData['handle_price'],
								'cyli_gress_price'=>$zipData['cyli_gress_price'],
								'gress_cyli_per'=>$zipData['gress_cyli_per'],
								'gress_per'=>$zipData['gress_per'],
								'quantity_type'=>$qunttData['quantity_type'],
								'slider_place'=>$zipData['slider_place'],
								'tamperevident'=>$zipData['tamperevident'],
								'laser_id'=>$zipData['laser_id'],
							);
					}
				}
			}
		}
		return $return;
	}
	
	public function getUserCurrencyInfo($user_type_id,$user_id){
		if($user_type_id==1)
		{
			$table = 'user';
			$colName='t.user_id';
		}
		if($user_type_id==2)
		{
			$data = $this->query("SELECT user_id,user_type_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."' ");
			$table = 'employee';
			$colName='t.employee_id';
			$user_id = $data->row['user_id'];
			$user_type_id=$data->row['user_type_id'];
		}
		if($user_type_id==3)
		{
			$table = 'client';
			$colName='t.client_id';
		}
		if($user_type_id==4)
		{
			$table = 'international_branch';
			$colName='t.international_branch_id';
		}
		
		$data = $this->query("SELECT cur.* FROM " . DB_PREFIX .$table." as t LEFT JOIN " . DB_PREFIX ."country cur ON(t.default_curr=cur.country_id)  WHERE ".$colName." = '".(int)$user_id."' ");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
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
	
	public function allowCurrencyStatus($user_type_id,$user_id){
		$status = false;
		if($user_type_id == 1){
			$data = $this->query("SELECT allow_currency FROM " . DB_PREFIX ."user WHERE user_id = '".(int)$user_id."'");
		}elseif($user_type_id == 2){
			$employee = $this->query("SELECT user_type_id, user_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."'");
			if($employee->num_rows){
				$status = $this->allowCurrencyStatus($employee->row['user_type_id'],$employee->row['user_id']);
			}
		}elseif($user_type_id == 4){
			$data = $this->query("SELECT allow_currency FROM " . DB_PREFIX ."international_branch WHERE international_branch_id = '".(int)$user_id."'");
		}elseif($user_type_id == 5){
			$data = $this->query("SELECT allow_currency FROM " . DB_PREFIX ."associate WHERE associate_id = '".(int)$user_id."'");
		}
		if(isset($data) && $data->num_rows){
			$status = $data->row['allow_currency'];
		}
		return $status;
	}
	
	public function getUser($user_id,$user_type_id){
		if($user_type_id == 1){
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name,u.user_id, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT ib.gst,e.user_name, e.user_id,co.country_id, e.multi_quotation_price,co.country_name, e.first_name, e.last_name, ib.email_confirm,e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.gst,ib.international_branch_id as user_id,ib.user_name,ib.discount,ib.multi_quotation_price,co.country_id, co.country_name, ib.first_name,ib.email_confirm ,ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email, acc.email1 FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			return false;
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getCurrencys(){
		$data = $this->query("SELECT currency_code, currency_id FROM " . DB_PREFIX ."currency WHERE status = '1' AND is_delete = '0' ");
		if($data->num_rows){
			return  $data->rows;
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
	
	public function getActiveMake(){
		$sql = "SELECT make_id,make_name FROM `" . DB_PREFIX . "product_make` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY serial_no";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveLayer(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_layer` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY layer";	
		$sql .= " ASC";
				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
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
	
	public function getActiveProductAccessorie(){
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY serial_no ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getProductSize($product_id,$zipper_id,$make_id,$spout_pouch)
	{
		
		if($make_id==5)
		{
			$sql = "SELECT * FROM spout_pouch_size_master WHERE product_id = '".$product_id."' AND spout_type_id = '".$spout_pouch."' ORDER BY width ASC ";
			
		}
		else
		{
			$sql  = "SELECT * FROM size_master WHERE product_id = '".$product_id."' AND product_zipper_id='".decode($zipper_id)."' ORDER BY width ASC"; 
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
	
	public function getViewToolprice($product_id)
	{
		$sql  = "SELECT pe.*,p.product_name FROM product_extra_tool_price as pe,product as p WHERE pe.product_id = p.product_id AND 
		pe.product_id = '".$product_id."'"; 
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
	
	public function getWidthSuggestion($width,$product_id)
	{
		$sql1 = "SELECT width_to FROM product_extra_tool_price WHERE product_id = '" .(int)$product_id. "' AND width_to = '".$width."'";
		$data1 = $this->query($sql1);
		if($data1->num_rows)
		{
			return false;
		}
		else
		{
			$sql = "SELECT width_to FROM ( ( SELECT width_to,".$width."-width_to AS diff FROM product_extra_tool_price WHERE product_id = '" .(int)$product_id. "' AND width_to < ".$width." ORDER BY width_to DESC  ) UNION ALL ( SELECT width_to,width_to-".$width." AS diff FROM product_extra_tool_price
    WHERE product_id = '" .(int)$product_id. "' AND width_to > ".$width."  ORDER BY width_to ASC ) ) AS tmp ORDER BY diff LIMIT 2" ;
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}
	}
	
	public function getGussetSuggestion($width,$gusset,$product_id)
	{
		$result = '';
		if($gusset!='')
		{
			$result = "AND gusset = '".$gusset."'";	
		}
		$cond = '';
		$cond1 ='';
		$sql1 = "SELECT price,width_to,gusset FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."')  LIMIT 1";
	 	$data1 = $this->query($sql1);
	 	if( $data1->num_rows > 0)
	 	{
			$sql3 = "SELECT price,width_to,gusset FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."') ".$result." LIMIT 1";
			$data3 = $this->query($sql3);
			if( $data3->num_rows > 0)
			{
				if($gusset!='')
				{
					if($data3->row['gusset']==$gusset)
					{
						return 0;
					}
					elseif($data3->row['width_to']==$width && $data3->row['gusset']!=$gusset)
					{
						return $data3->rows;
					}
				}
				else
				{
					return 0;
				}
			}
			else
			{
	 		if(!$data3->num_rows)
	 		{
				$cond1 = " LIMIT 1";
				$sql = "SELECT price,width_to,gusset FROM  product_extra_tool_price WHERE product_id = '" .(int)$product_id. "' AND width_to ='".$width."'  LIMIT 2" ;
				$data = $this->query($sql);
				if($data->num_rows){
					return $data->rows;				
				}else{
					return false;
				}
	 		}
			}
	 	}
		else
		{
	 		if(!$data1->num_rows)
	 		{
				$cond1 = " LIMIT 1";
				$sql = "SELECT price,width_to,gusset FROM ( ( SELECT price,width_to,gusset,".$width."-width_to AS diff FROM product_extra_tool_price
    WHERE product_id = '" .(int)$product_id. "' AND width_to >'".$width."' ".$cond1." ) UNION ALL ( SELECT price,width_to,gusset,width_to-".$width." AS diff
    FROM product_extra_tool_price  WHERE product_id = '" .(int)$product_id. "' AND width_to <'".$width."' ".$cond1."  )) AS tmp ORDER BY diff LIMIT 2" ;
				$data = $this->query($sql);
				if($data->num_rows){
					return $data->rows;				
				}else{
					return false;
				}
	 		}
		}
	}
	
	public function getToolPrice($width,$gusset,$product_id)
    {
         $cond = '';
         $cond1 ='';
    	 $sql1 = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."') AND gusset = '".$gusset."' LIMIT 1";
    	 $data1 = $this->query($sql1);
    	
    	 if(!$data1->num_rows)
    	 {
    		if(isset($gusset) && $gusset>0)
    		{
    			$cond = " ORDER BY gusset,width_to  ASC";
    		}
    		else
    		{
    			$cond1 = " ORDER BY width_to ASC LIMIT 1";
    		}	
			$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND (width_to > '".(int)$width."' ) ".$cond1."";
			$data = $this->query($sql);
    	
    		if($data->num_rows >1)
    		{
    			$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to > '".(int)$width."') ".$cond." LIMIT 1";
    			$data = $this->query($sql);
    		}
			if($data->num_rows){
				return $data->row['price'];
			}else{
				return false;
			}
    	 }
    	 else
    	 {
    		return 0;
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
	
	public function getQuantityById($quantity_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."product_quantity WHERE product_quantity_id = '".$quantity_id."' LIMIT 1");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function numberFormate($number,$decimalPoint=3){
		return number_format($number,$decimalPoint,".","");
	}
	
	public function getQuantity($type,$quantity_type){
		if($type == 'p'){
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."product_quantity WHERE status = '1' AND is_delete = '0'");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}else{
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."roll_quantity WHERE quantity_type = '".$quantity_type."' AND status = '1' AND is_delete = '0' ORDER BY quantity ASC");
			if($data->num_rows){
				return $data->rows;
			}else{
				return false;
			}
		}
	}
	
	public function checkProductGusset($product_id){
		$data = $this->query("SELECT gusset_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['gusset_available'];	
		}else{
			return false;
		}
	}
	
	public function getCurrencyValue($currency_id){
		$data = $this->query("SELECT price FROM " . DB_PREFIX ."currency_setting WHERE currency_id= '".$currency_id."'");
		return $data->row['price'];
	}
	
	
	public function getMaterialQuantity($material_id,$qty=0){
		$data = $this->query("SELECT pq.quantity FROM " . DB_PREFIX . "product_material_quantity pmq INNER JOIN " . DB_PREFIX . "product_quantity pq ON(pmq.product_quantity_id=pq.product_quantity_id) WHERE pmq.product_material_id = '".(int)$material_id."' AND pq.quantity>='".(int)$qty."' ORDER BY pq.quantity ASC ");	
		if($data->num_rows){
			$return = array();
			foreach($data->rows as $key=>$value){
				$return[] = $value['quantity'];
			}
			//printr($return);
			return $return;
		}else{
			return false;
		}
	}
	
	public function aasort ($array) {
		$newArray = array();
		$hv = array();
	   foreach ($array as $va) {
			$hv[] = max($va);
			$newArray = $this->makeOneArray($va,$newArray);
		}
		asort($hv);
		if(count($hv) > 1){
			array_pop($hv);
		}
		rsort($newArray);
		$highest = $this->getHighestValue($newArray,$hv);
		$totalCount = count($array);
		$common = $this->getCommonValue($newArray,$totalCount);
		$final = array_merge($highest,$common);
		return $final;
	}
	
	public function makeOneArray($array,$newArray){
		foreach ($array as $ii => $va) {
			$newArray[] = $va;
		}
		return $newArray;
	}
	
	public function getCommonValue($array,$totalCount){
		$common = array();
		foreach($array as $val){
			$tmp = array_count_values($array);
			$cnt = $tmp[$val];
			if($cnt == $totalCount){
				if(!in_array($val,$common)){
					$common[] = $val;
				}
			}
		}
		return $common;
	}
	
	public function getHighestValue($array,$hv){
		$highest = array();
		$max = max($hv);
		foreach($array as $val){
			if(!in_array($val,$hv) && $val > $max){
				$highest[] = $val;
			}
		}
		return $highest;
	}
	
	public function checkProductZipper($product_id){
		$data = $this->query("SELECT zipper_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['zipper_available'];	
		}else{
			return false;
		}
	}
	
	public function checkProductTintie($product_id){
		$data = $this->query("SELECT tintie_available,spout_pouch_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row;	
		}else{
			return false;
		}
	}
	
	public function getActiveProductZippers(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY serial_no ASC");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductZippersByTintie($tintie){
		if($tintie == '1')
		{
			$tin = " AND  zipper_name LIKE 'T%'";
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
	
	public function addQuotation($data){
		$result = $this->addQuotationFormula($data,'Q');
		return $result;
	}
	
	public function getUserInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name, gres,gres_air,gres_sea,	gres_cyli, valve_price  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
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
				}else{
					$return['company_name'] = '';
					$return['gres'] = '';
					$return['gres_air'] = '';
					$return['gres_sea'] = '';
					$return['valve_price'] = '';
					$return['gres_cyli'] ='';
				}
			}else{
				$return['company_name'] = '';
				$return['gres'] = '';
				$return['gres_air'] = '';
				$return['gres_sea'] = '';
				$return['valve_price'] = '';
				$return['gres_cyli'] ='';
			}
			$return['first_name'] = $data->row['first_name'];
			$return['last_name']  = $data->row['last_name'];
			
			return $return;
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name,gres,gres_air,gres_sea,	gres_cyli, valve_price FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea,	gres_cyli,valve_price FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
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
	
	public function getProduct($product_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE product_id = '".$product_id."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return 0;
		}
	}
	
	public function formulaHeightWidthGusset($height,$width,$gusset,$product_id){
		
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		$gussetFormula1 = 0;
		$heightGuseetFormula1 = 0;
		$actualHeight = 0;
		$widthFormula = 0;
		$calWidth = $width;
		$calHeight = $height;
		$intoHeight = 0;
		$intoWidth = 0;
		if(!empty($productGusset)){			
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$heightGuseetFormula1 = (($height* 2) + 25);
				$gussetFormula1 = 0;
				$actualHeight = ( $heightGuseetFormula1 + $gussetFormula1);
				$calWidth = $this->numberFormate(($width /1000),"3");
				$intoWidth = 1;
				
			}
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
			
				$widthFormula = (($width * 2) + 50);//chnage as per excel sheet 17-10-2014 + 25 to +50
				$gussetFormula1 = 0;
				$actualHeight = ( $widthFormula + $gussetFormula1);
				$calHeight = $this->numberFormate(($height /1000),"3");
				$intoHeight = 1;
				
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{	
				if($product_id==9)
				{
					//Quad Seal Gusset
					$widthGuseetFormula1 = $width + 10;
					$gussetFormula1 = ( $gusset + $gusset + 10 );
					//Height Gusset Formula :  heightFormula1 + gussetFormula1 + 10
					$calWidth1 = (( $widthGuseetFormula1 + $gussetFormula1) *2 )+35;				
					$calWidth = $this->numberFormate(($calWidth1 /1000),"3");
					$actualHeight = $height;
					$intoWidth = 1;
					
				}
				else
				{
				
					$widthFormula = ( $width + $gusset + $gusset );
					$widthFormula1 = ( ( $widthFormula * 2) + 50 );//chnage as per excel sheet 17-10-2014 + 25 to +50
					$gussetFormula1 = 0;
					$actualHeight = ( $widthFormula1 + $gussetFormula1);
					$calHeight = $this->numberFormate(($height /1000),"3");
					$intoHeight = 1;
					
				}
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{
				//mailer bag
				if($product_id==10)
				{
					$calWidth = $this->numberFormate(($width /1000),"3");
					$actualHeight = (($height * 2)+$gusset+50);
					$calHeight = $this->numberFormate(($actualHeight / 1000),"3");
				}
				else
				{
				    
					$heightGuseetFormula1 = (($height + 10) * 2);
					$gussetFormula1 = ( $gusset + $gusset + 10 );
					//Height Gusset Formula :  heightFormula1 + gussetFormula1 + 10
					$actualHeight = ( $heightGuseetFormula1 + $gussetFormula1 + 10 );
					$calWidth = $this->numberFormate(($width /1000),"3");
					$intoWidth = 1;
				}
				
			}elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
			
				//Flat Bottom Stand Up Pouches
				$widthFormula = ($width + $gusset + $gusset + 15);
				$widthFormula1 = ($widthFormula * 1);
				$calWidth = $this->numberFormate(($widthFormula1 /1000),"3");
				$heightFormula = ((($height + $gusset)+15) * 2);
				$actualHeight = ($heightFormula + 20 + 10);
				$calHeight = $this->numberFormate(($actualHeight / 1000),"3");
				$intoWidth = 1;
				
			}
		}
		//Formula : actual height devided by 1000
		$actualHeight1 = $this->numberFormate(($actualHeight / 1000),"3");
		$return = array();
		$return = array(
			'formula' => $actualHeight1,
			'width'	=> $calWidth,
			'height'	=> $calHeight,
			'intoHeight' => $intoHeight,
			'intoWidth'  => $intoWidth,
		);
		return  $return;
	}
	
	public function getMaterialGsm($material_id){
		$sql = "SELECT gsm FROM " . DB_PREFIX . "product_material WHERE product_material_id = '".(int)$material_id."' ";				
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['gsm'];
		}else{
			return false;
		}
	}
	
	public function checkMaterial($material_id){
		$sql = "SELECT material_name FROM " . DB_PREFIX . "product_material WHERE product_material_id = '".(int)$material_id."' ";				
		$data = $this->query($sql);
		if($data->num_rows){
			if(($material_id == '11') || ($material_id == '20')){    
				return 1;
			}
		}else{
			return 0;
		}
	}
	
	public function getMaterialThickmessPrice($material_id,$thickness){
		$sql = "SELECT price FROM " . DB_PREFIX . "product_material_thickness_price WHERE product_material_id = '".(int)$material_id."' AND from_thickness <= '".$thickness."' AND to_thickness >= '".$thickness."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['price'];
		}else{
			return false;
		}
	}
	
	public function getLayerWiseGsmThickness($actualHeight,$wh,$thickness,$gsm){
		//Formula1 : multiply all get value
		$priceFormula1 = $this->numberFormate(($actualHeight * $wh * $thickness * $gsm ),"5");
		return $priceFormula1;
	}
	
	public function getLayerPrice($layer_wise_price,$thickness_price){
		$priceFormula2 = $this->numberFormate(($layer_wise_price * $thickness_price),"3");
		return $priceFormula2;
	}
	
	public function sumOfNumericArray($array){
		$total = 0;
		if(is_array($array) && !empty($array)){
			foreach($array as $key=>$val){
				$total += $val; 
			}
		}
		return $this->numberFormate($total,"5");
	}
	
	public function getInkPrice1($makeid){
		$sql = "SELECT price FROM " . DB_PREFIX . "ink_master WHERE status = '1' AND make_id = '".$makeid."' ORDER BY ink_master_id DESC LIMIT 0,1";
		$data = $this->query($sql);
		if($data->num_rows){
			return $this->numberFormate( $data->row['price'],"5");
		}else{
			return false;
		}
	}
	
	public function getInkSolventPrice($basePrice,$type,$makeid){
		$sql = "SELECT price FROM " . DB_PREFIX . "ink_solvent WHERE status = '1' AND make_id = '".$makeid."' ORDER BY ink_solvent_id DESC LIMIT 0,1";
		$data = $this->query($sql);

		if($data->num_rows){
			if($type == 1){
				return $this->numberFormate( ( $basePrice * $data->row['price']),"5");
			}else{
				return $this->numberFormate($data->row['price'],"5");
			}
		}else{
			return false;
		}
	}
	
	public function getPrintingEffectPrice($effect_id){
		$sql = "SELECT price FROM " . DB_PREFIX . "printing_effect WHERE printing_effect_id = '".(int)$effect_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $this->numberFormate($data->row['price'],"3");
		}else{
			return 0;
		}
	}
	
	public function getAdhesivePriceCpp($price,$layerCount,$type,$makeid='',$val,$cond){
		
		if($cond==0)
			$sql = "SELECT price FROM " . DB_PREFIX . "adhesive WHERE status = '1' AND make_id = '".$makeid."' ORDER BY adhesive_id DESC LIMIT 0,1";
		elseif($cond==1)
			$sql = "SELECT price FROM " . DB_PREFIX . "cpp_adhesive WHERE status = '1' ORDER BY cpp_adhesive_id DESC LIMIT 0,1";
		elseif($cond==2)
			$sql = "SELECT price FROM " . DB_PREFIX . "adhesive_solvent WHERE status = '1' AND make_id = '".$makeid."' ORDER BY adhesive_solvent_id DESC LIMIT 0,1";
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),$val);
			}else{
				return $this->numberFormate($data->row['price'],$val);
			}
		}else{
			return false;
		}
	}
	
	public function getAdhesiveSolventPrice($price,$layerCount,$type,$makeid){
		$sql = "SELECT price FROM " . DB_PREFIX . "adhesive_solvent WHERE status = '1' AND make_id = '".$makeid."' ORDER BY adhesive_solvent_id DESC LIMIT 0,1";
				
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),"5");
			}else{
				return $this->numberFormate($data->row['price'],"5");	
			}
		}else{
			return false;
		}
	}
	
	public function getAdhesivePrice($price,$layerCount,$type,$makeid){
		$sql = "SELECT price FROM " . DB_PREFIX . "adhesive WHERE status = '1' AND make_id = '".$makeid."' ORDER BY adhesive_id DESC LIMIT 0,1";
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),"5");
			}else{
				return $this->numberFormate($data->row['price'],"5");
			}
		}else{
			return false;
		}
	}
	
	public function getCppAdhesivePrice($price,$layerCount,$type){
		$sql = "SELECT price FROM " . DB_PREFIX . "cpp_adhesive WHERE status = '1' ORDER BY cpp_adhesive_id DESC LIMIT 0,1";
		
		$data = $this->query($sql);
		if($data->num_rows){
			if($type == 1){
				$count = ($layerCount > 1)?($layerCount-1):$layerCount;
				return $this->numberFormate(($price * $count * $data->row['price']),"3");
			}else{
				return $this->numberFormate($data->row['price'],"3");
			}
		}else{
			return false;
		}
	}
		
	public function newPackingCharges($height,$width,$gusset,$product_id){
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		$packing_charge = 3.20;
		$total = 0;
		if(!empty($productGusset)){
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$total = ($height * $width);		
			}
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$total = ($height * $width);
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$total = ((($gusset*2) + $height)* $width); 
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{
				$total = ((($gusset*1)+$height)*$width);
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				$total = ((($gusset*3) + $height) * $width);
			}
			$data = $this->query("SELECT price FROM ". DB_PREFIX ."product_packing WHERE to_total >= '".$total."' AND from_total <= '".$total."'");
			if($data->row['price'] > 0){
				$packing_charge = $data->row['price'];	
			}
		}
		return $this->numberFormate(($packing_charge),"5");
	}
	
	function getZipperInfo($zipper_id){
		$data = $this->query("SELECT zipper_name, price, product_zipper_id,Weight,slider_price FROM " . DB_PREFIX . "product_zipper WHERE product_zipper_id = '".(int)$zipper_id."' ");
		if($data->num_rows){
			$return  = array();
			$return['product_zipper_id'] = $data->row['product_zipper_id'];
			$return['zipper_name'] = $data->row['zipper_name'];
			$return['price'] = $data->row['price'];
			$return['slider_price'] = $data->row['slider_price'];
			$return['Weight'] = $data->row['Weight'];
			return $return;
		}else{
			$return  = array();
			$return['product_zipper_id'] = 0;
			$return['zipper_name'] = 'No zip';
			$return['price'] = 0.00;
			$return['slider_price'] = 0.00;
			$return['Weight'] = 0.00;
			return $return;
		}
	}
	
	public function getCalculateZipperPrice($product_id,$height,$weight,$zipperBasePrice){
		$data = $this->query("SELECT calculate_zipper_with FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$product_id."' ");
		if($data->row['calculate_zipper_with'] && $data->row['calculate_zipper_with'] != ''){
			if($data->row['calculate_zipper_with'] == 'h'){
				$newhh = ($height / 1000);
				$zipperPrice = ($newhh * $zipperBasePrice * 10 );
			}else{
				if($product_id=='7' || $product_id=='9')
				{
					$weight=$weight+7;
				}
				$newww = ($weight / 1000);
				$zipperPrice = ($newww * $zipperBasePrice * 10 );
			}
		}else{
			$newww = ($weight / 1000);
			$zipperPrice = ($newww * $zipperBasePrice * 10 );
		}
		return $zipperPrice;
	}
	
	public function getSpout($spout_id){
		$data = $this->query("SELECT spout_name, price, product_spout_id,weight,by_sea,additional_packaging_price FROM " . DB_PREFIX . "product_spout WHERE product_spout_id = '".(int)$spout_id."' ");
		$return  = array();
		if($data->num_rows){
			$return['product_spout_id'] = $data->row['product_spout_id'];
			$return['spout_name'] = $data->row['spout_name'];
			$return['price'] = $data->row['price'];
			$return['weight'] = $data->row['weight'];
			$return['by_sea'] = $data->row['by_sea'];
			$return['additional_packaging_price'] = $data->row['additional_packaging_price'];
			return $return;
		}
		else
		{
			$return['product_spout_id'] = 0;
			$return['spout_name'] = 'No Spout';
			$return['price'] = 0.00;
			$return['weight'] = 0.00;
			$return['by_sea'] = 0.00;
			$return['additional_packaging_price'] = 0.00;
			return $return;
		
		}
		
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
	
	public function getCountry($country_id){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."country WHERE country_id = '".(int)$country_id."' ");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getCountryCourier($country_id){
		$cdata = $this->query("SELECT default_courier_id FROM " . DB_PREFIX . "country WHERE country_id = '".$country_id."'");
		if($cdata->row['default_courier_id']){
			$courier_id = $cdata->row['default_courier_id'];
		}else{
			$courier_id = 1;
		}
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "courier WHERE courier_id = '".$courier_id."'");		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	
	public function getCalculateTransport($height,$width,$gusset){
		$hPrice = 0;
		$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_transport_sea_height WHERE from_height <= '".$height."' AND to_height >= '".$height."' ");		
		if($data->num_rows){
			$hPrice = $data->row['price'];
		}
		$wPrice = 0;
		$newWidth = ($width + $gusset);
		$wdata = $this->query("SELECT price FROM " . DB_PREFIX . "product_transport_sea_width WHERE from_width <= '".$newWidth."' AND to_width >= '".$newWidth."' ");		
		if($wdata->num_rows){
			$wPrice = $wdata->row['price'];
		}
		return $this->numberFormate(($hPrice + $wPrice),"5");
	}
	
	public function getWastage($quantity){
		$sql = "SELECT wastage FROM " . DB_PREFIX . "product_wastage WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			$wastage = $data->row['wastage'];
			return $wastage;
		}else{
			return false;
		}
	}
	
	public function getcalculateProfit($quantity,$product_id,$height,$width,$gusset){
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		$size = 0;
		if(!empty($productGusset)){
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				
				$size = ($height * $width);		
			}
			
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ((($gusset*2) + $height)* $width); 
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{

				$size = ((($gusset*1)+$height)*$width);
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*3) + $height) * $width);
			}
		}

		$qunatityRow = $this->query("SELECT product_quantity_id FROM " . DB_PREFIX . "product_quantity WHERE quantity = '".$quantity."'");
		$quantity_id = $qunatityRow->row['product_quantity_id'];
		
		$data = $this->query("SELECT profit,wastage_per FROM " . DB_PREFIX . "product_profit WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND size_from <= '".$size."' AND 	size_to >= '".$size."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}

	public function getCalculateWeightZipper($basePrice,$quantity,$zipperVal){
		$addingPrice = ($basePrice + $zipperVal);
		//diveed by 1000
		$addingPrice1 = ($addingPrice / 1000);
		$addingPrice2 = $this->numberFormate(($addingPrice1 * $quantity),"3");
		return $addingPrice2;
	}
	public function getCountryCourierName($country_id,$courier_id)
	{
		$sql="SELECT cz.courier_zone_id,c.courier_name FROM courier_zone_country as cz,courier as c WHERE cz.country_id='".$country_id."' AND cz.courier_id='".$courier_id."' AND cz.courier_id=c.courier_id";
		$data=$this->query($sql);
		if($data->num_rows){
				$sql1="SELECT zone FROM courier_zone WHERE courier_zone_id='".$data->row['courier_zone_id']."'";
				$data1=$this->query($sql1);
			$return['zone'] = $data1->row['zone'];
			$return['courier_name'] = $data->row['courier_name'];
		}
		else
		{
			$return['zone'] ='';
			$return['courier_name'] = '';
		}
		return $return;
	}
	public function getCountryCourierCharge($country_id,$courier_id,$weight){
	
		$weight = $this->numberFormate(($weight),"2");
		$zdata = $this->query("SELECT courier_zone_id FROM " . DB_PREFIX . "courier_zone_country WHERE country_id = '".$country_id."' AND courier_id = '".$courier_id."'");		
		if(isset($zdata->row['courier_zone_id']) && $zdata->row['courier_zone_id']){
			$courier_zone_id = $zdata->row['courier_zone_id'];
		}else{
			$courier_zone_id = 1;
		}
		$data = $this->query("SELECT czp.price,czp.from_kg,czp.to_kg,c.fuel_surcharge,c.service_tax,c.handling_charge FROM " . DB_PREFIX . "courier_zone_price as czp,courier as c WHERE czp.courier_id = '".$courier_id."' AND 	czp.courier_zone_id = '".$courier_zone_id."' AND czp.from_kg <= '".$weight."' AND czp.to_kg >= '".$weight."' AND czp.courier_id=c.courier_id");	
		if(isset($data->row['price']) && $data->row['price']){
			$price = $data->row['price'];
			$baseKg = $data->row['to_kg'];
			$perKgPrice =$data->row['price']/$data->row['to_kg'];
		}else{
			$data = $this->query("SELECT czp.to_kg, czp.price,c.fuel_surcharge,c.service_tax,c.handling_charge FROM " . DB_PREFIX . "courier_zone_price as czp,courier as c WHERE czp.courier_id = '".$courier_id."' AND czp.courier_zone_id = '".$courier_zone_id."' AND czp.courier_id=c.courier_id ORDER BY czp.to_kg DESC LIMIT 0,1 ");
			$baseKg = $data->row['to_kg'];
			$basePrice = $data->row['price'];
			$perKgPrice = ($basePrice / $baseKg);
			$price = ($weight * $perKgPrice);
		}
		$price=array('price'=>$price,
					'to_kg'=>$baseKg,
					'PerKgPrice'=>$perKgPrice,
					'fuel_charge'=>$data->row['fuel_surcharge'],
					'service_tax'=>$data->row['service_tax'],
					'handling_charge'=>$data->row['handling_charge']);
		return $price;
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
		
	public function getCylinderVendorPrice($countryId = ''){
		$price = 28;
		if($countryId){
			$countryInfo = $this->getCountry($countryId);
			if($countryInfo && strtolower($countryInfo['country_name']) == 'india'){
				$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_cylinder_vendor WHERE type = '1' AND status = '1' ORDER BY product_cylinder_vendor_id DESC LIMIT 0,1");
			}else{
				$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_cylinder_vendor WHERE type = '0' AND status = '1' ORDER BY product_cylinder_vendor_id DESC LIMIT 0,1");
			}
		}else{
			$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_cylinder_vendor WHERE type = '0' AND status = '1' ORDER BY product_cylinder_vendor_id DESC LIMIT 0,1");
		}
		
		if($data->num_rows){
			$price = $this->numberFormate( $data->row['price'],"5");
		}
		return $price;
	}
	
	public function recursiveWidth($number,$width){
		$newWidth = 0;
		if($width==190)
			$number= $number+1;
		$width1 = ($width * $number);
		
		//commented by jaya on 23-3-2016
		
		if($width1 >= 400){
			$newWidth = $width1;
		}else{
			$num = ($number+1);
			$newWidth = $this->recursiveWidth($num,$width);
		}
		return $newWidth;
	}
	
	public function getCalculateCylinderPrice($height,$widht,$gusset,$countryId,$product_id,$gusset_printing_option)
	{
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		if(!empty($productGusset) && in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
		{
			$widht = $widht + $gusset + 7;
		}
		$cylinderVendorPrice = $this->getCylinderVendorPrice($countryId);
		$calculatePrice = 0;
		
		//commented by jaya on 23-3-2016
		
		if($widht >= 400){
			//[kinjal] (7-4-2017) in Front & Back  +  Bottom Gusset Printing cond after discussed with vikas sir and prashant sir
			if($gusset_printing_option=='Front & Back  +  Bottom Gusset Printing')
				$actualHeight = ((($height + 10) + $gusset + 10 ) * 2);
			else
				$actualHeight = (($height + 10) * 2);
				
			if($actualHeight > 999 ){
				$actualHeight1 = ($actualHeight + 200 );
			}else if($actualHeight <= 999 ){
				$actualHeight1 = ($actualHeight + 100 );
			}else{
				$actualHeight1 = ($actualHeight + 100 );
			}

			$price = ( $actualHeight1 * $widht * $cylinderVendorPrice);
			$calculatePrice = ceil($this->numberFormate(($price / 1000),"3"));

		}else{

			//[kinjal] (7-4-2017) in Front & Back  +  Bottom Gusset Printing cond after discussed with vikas sir and prashant sir
			if($gusset_printing_option=='Front & Back  +  Bottom Gusset Printing')
				$actualHeight = ((($height + 10) + $gusset + 10 ) * 2);
			else
				$actualHeight = (($height + 10) * 2);
				
			$actualHeight1 = ($actualHeight + 100 );
			$widhtFormula = $this->recursiveWidth(2,$widht);
			$price = ( $actualHeight1 * $widhtFormula * $cylinderVendorPrice);
			$calculatePrice = ceil($this->numberFormate(($price / 1000),"3"));
		}
		return $calculatePrice;
	}
	
	public function getCylinderBasePrice($curreny_code,$admin_user_id=''){
		$data = $this->query("SELECT price FROM " . DB_PREFIX ."product_cylinder_base_price WHERE currency_code = '".$curreny_code."'");
		//$data = $this->query("SELECT default_cyli_base_price as price FROM " . DB_PREFIX ."international_branch as ib WHERE international_branch_id = '".$admin_user_id."'");
		if(isset($data->row['price']) && $data->row['price']){
			return $data->row['price'];
		}else{
			return false;
		}
	}	
	
	public function generateQuotationNumber($multi_product_quotation_id){
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME   = 'product_quotation'");
		$count = $data->row['AUTO_INCREMENT']+$multi_product_quotation_id;
		$strpad = str_pad($count,8,'0',STR_PAD_LEFT);
		return $strpad;
	}
	
	public function getDefaulttransportHeightPrice($height){
		$hPrice = 0;
		$data = $this->query("SELECT price FROM " . DB_PREFIX . "product_transport_sea_height WHERE from_height <= '".$height."' AND to_height >= '".$height."' ");
		if($data->num_rows){
			$hPrice = $data->row['price'];
		}		
		return $this->numberFormate($hPrice,"3");
	}	
	
	public function getDefaultTransportWidthPrice($width,$gusset){
		$newWidth = ($width + $gusset);
		$wPrice = 0;
		$wdata = $this->query("SELECT price FROM " . DB_PREFIX . "product_transport_sea_width WHERE from_width <= '".$newWidth."' AND to_width >= '".$newWidth."' ");
		if($wdata->num_rows){
			$wPrice = $wdata->row['price'];
		}
		return $this->numberFormate($wPrice,"3");
	}
	
	public function getCurrencyInfoOld($currency_code){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ."currency WHERE currency_id = '".(int)$currency_code."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function setQuotationCurrency($quotation_id,$currency_code,$currencyRate,$source,$sec_curr='',$sec_curr_rate=''){
	
			$this->query("INSERT INTO " . DB_PREFIX . "new_multi_product_quotation_currency SET product_quotation_id = '".$quotation_id."', currency_id = '', currency_code = '".$currency_code."', currency_rate = '".(float)$currencyRate."', currency_base_rate = '', source = '".$source."',sec_curr='".$sec_curr."',sec_curr_rate='".$sec_curr_rate."',date_added = NOW()");
			return $this->getLastId();
	}
	
	public function getSelCurrencyInfo($currency_code){
		$data = $this->query("SELECT cs.price, c.currency_code FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country c ON(c.country_id=cs.country_code) WHERE c.currency_code= '".$currency_code."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function getSize($size_id,$table_name){
		$data = $this->query("SELECT gusset,height,width,volume FROM " . DB_PREFIX ." ".$table_name." WHERE size_master_id = '".(int)$size_id."' AND status=0 LIMIT 1");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function CheckQuotationTax($quotation_id)
	{

		$sql = "SELECT zipper_txt,valve_txt,tax_type,tax_percentage,excies FROM " . DB_PREFIX ."new_multi_product_quotation_price WHERE product_quotation_id = '".(int)$quotation_id."' ORDER BY product_quotation_price_id ASC LIMIT 1";
		$data = $this->query($sql);
		//printr($data);
		return $data->row;
	}	
	public function addQuotationFormula($data,$type)
	{
		/*if($_SESSION['ADMIN_LOGIN_SWISS']=='1'  && $_SESSION['LOGIN_USER_TYPE']=='1')
		    printr($data);*///die;
		
		//made by kinjal on 23-4-2018 for slider replaced as zipper
		$tamperevident = $slider_place = '';
		if($data['slider']=='1')
		{
		   $data['zipper'][0] = $data['slider_option']; 
		   $tamperevident = $data['tamperevident'];
		   $slider_place= $data['s_view'];
		   //,tamperevident='".$data['tamperevident']."',slider_place='".$data['s_view']."',
		}
		
		$post_height = $data['height'];
		$post_width = $data['width'];
		$gusset = $data['gusset'];
		
		$product_id = $data['product'];
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
		//if(($_SESSION['ADMIN_LOGIN_SWISS']=='41' || $_SESSION['ADMIN_LOGIN_SWISS']=='35') && $_SESSION['LOGIN_USER_TYPE']=='4')
		    //printr($admin_user_id);
		
		$userInfo = $this->getUserInfo($user_type_id,$user_id);
	
		$productName = getName('product','product_id',$product_id,'product_name');
		
		$courierChargeInFormula=0;
		if($data['product'] == "6"){
			//return "Error";
			
			$multi_quo_id = $this->addRollQuotation($data);
			//printr($multi_quo_id);
			return $multi_quo_id;
		}else{
			if($type=='Q')
			{
				
				
				if(isset($data['multi_quote_id']) && $data['multi_quote_id']!='')
				{
					$multi_product_quotation_id = $data['multi_quote_id'];
				}
				else
				{
				
					//[kinjal] : changed code on 23-6-2017
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
							if($data['company_address_id']=='')
							{
									$sql2 = "INSERT INTO  company_address  SET  address_book_id = '" . $address_book_id . "',country = '" . $data['country_id'] . "', email_1 = '" . $data['email'] . "' ,user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "' , date_added = NOW(),date_modify = NOW()";
									$datasql2 = $this->query($sql2);
							}
							else
							{
									$sql2 = "UPDATE company_address SET country = '" . $data['country_id']. "',email_1 = '" . $data['email'] . "' WHERE company_address_id ='" . $data['company_address_id'] . "'";
									$datasql2 = $this->query($sql2);
							
							}
							
					}
					
					$sql =  "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_id SET  status = '0',address_book_id = '".$address_book_id."', date_added = NOW(),date_modify = NOW(), added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."'";		
					$this->query($sql);
					$multi_product_quotation_id = $this->getLastId();
				}
			}
			elseif($type=='O')
			{
				if(isset($data['multi_cust_id']) && $data['multi_cust_id']!='')
				{
					$multi_custom_order_id = $data['multi_cust_id'];
				}
				else
				{
					$first_name = addslashes($data['first_name']);
					$last_name = addslashes($data['last_name']);
					if(!isset($data['shipping_country_id'])) {			
						$shipment_country = $_SESSION['shipment_country'];
					}
					else {
						$shipment_country = $data['shipping_country_id'];
					}
			
					$customer_name = $first_name.' '.$last_name;		
					$this->query("INSERT INTO `" . DB_PREFIX . "address` SET user_type_id=0, user_id=0 , address_type_id=1, first_name='".$first_name."', last_name = '".$last_name."', address = '".$data['shipping_address_1']."', address_2 = '".$data['shipping_address_2']."', city = '".$data['shipping_city']."', country_id = '".$shipment_country."', zone_id=0, date_added=NOW(), date_modify=NOW()   ");
					$shipping_address_id = $this->getLastId();
					if(!empty($data['same_as_above']) && $data['same_as_above']==1){
						$this->query("INSERT INTO `" . DB_PREFIX . "address` SET user_type_id=0, user_id=0 , address_type_id=2, first_name='".$first_name."', last_name = '".$last_name."', address = '".$data['shipping_address_1']."', address_2 = '".$data['shipping_address_2']."', city = '".$data['shipping_city']."', country_id = '".$shipment_country."', zone_id=0, date_added=NOW(), date_modify=NOW()   ");	
					}else{
						$this->query("INSERT INTO `" . DB_PREFIX . "address` SET user_type_id=0, user_id=0 , address_type_id=2, first_name='".$first_name."', last_name = '".$last_name."', address = '".$data['billing_address_1']."', address_2 = '".$data['billing_address_2']."', city = '".$data['billing_city']."', country_id = '".$data['billing_country_id']."', zone_id=0, date_added=NOW(), date_modify=NOW()");	
					}
					$billing_address_id = $this->getLastId();
					if($_SESSION['LOGIN_USER_TYPE'] == 1){
						$order_currency_query = $this->query("SELECT u.product_rate,c.currency_code FROM `" . DB_PREFIX . "user` u INNER JOIN `" . DB_PREFIX . "country` c ON (u.default_curr = c.country_id) WHERE u.user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
					}else if($_SESSION['LOGIN_USER_TYPE'] == 4){
						$order_currency_query = $this->query("SELECT ib.product_rate,c.currency_code FROM `" . DB_PREFIX . "international_branch` ib INNER JOIN `" . DB_PREFIX . "country` c ON (ib.default_curr = c.country_id) WHERE ib.international_branch_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' ");
					}else if($_SESSION['LOGIN_USER_TYPE'] == 2){
						$parent_user_query = $this->query("SELECT user_type_id,user_id FROM `" . DB_PREFIX . "employee` WHERE employee_id ='".$_SESSION['ADMIN_LOGIN_SWISS']."'  ");
						if($parent_user_query->row['user_type_id']==1){
							$order_currency_query = $this->query("SELECT u.product_rate,c.currency_code FROM `" . DB_PREFIX . "user` u INNER JOIN `" . DB_PREFIX . "country` c ON (u.default_curr = c.country_id) WHERE u.user_id = '".$parent_user_query->row['user_id']."' ");
						}else if($parent_user_query->row['user_type_id']==4){
							$order_currency_query = $this->query("SELECT ib.product_rate,c.currency_code FROM `" . DB_PREFIX . "international_branch` ib INNER JOIN `" . DB_PREFIX . "country` c ON (ib.default_curr = c.country_id) WHERE ib.international_branch_id = '".$parent_user_query->row['user_id']."' ");	
						}
					}	
					$sql =  "INSERT INTO ".DB_PREFIX."multi_custom_order_id SET  company_name='".$this->escape($data['company'])."', website='".$data['website']."', customer_name='".$this->escape($customer_name)."', email = '".$data['email']."', contact_number='".$data['contact_number']."', vat_number = '".$data['vat_number']."', shipping_address_id = '".$shipping_address_id."',  billing_address_id = '".$billing_address_id."', order_currency = '".$order_currency_query->row['currency_code']."',currency_rate = '".$order_currency_query->row['product_rate']."',order_note ='".$data['order_note']."', order_instruction = '".$data['order_instruction']."',custom_order_status=1,status = '0', date_added = NOW(),date_modify = NOW(), added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."'";		
					$this->query($sql);
					$multi_custom_order_id = $this->getLastId();
				}
			}
			if(decode($data['size'])!=0)
			{
				$size_id=decode($data['size']);
				if($data['make']==5)
				{
					$table_name='spout_pouch_size_master';
				}
				else
				{
					$table_name='size_master';
				}
				$size=$this->getSize($size_id,$table_name);
				if($data['product']=='10')
				{	//mailer bag
					$newSize[]=array('width'=>round($size['width']*25.4),
						'height'=>round($size['height']*25.4),
						'gusset'=>round($size['gusset']*25.4),
						'volume'=>$size['volume']
						);	
				}
				else
				{	
					$newSize[]=array('width'=>$size['width'],
						'height'=>$size['height'],
						'gusset'=>$size['gusset'],
						'volume'=>$size['volume']
						);	
				}
						
			}
			else
			{
				$newSize[]=array('width'=>$data['width'],
					'height'=>$data['height'],
					'gusset'=>$data['gusset'],
					'volume'=>''
					);
					
			}
			
			foreach($newSize as $size)
			{
			
				$post_height = $size['height'];
				$post_width = $size['width'];
				$gusset = $size['gusset'];
				$post_volume = $size['volume'];
				$test['post_height']=$post_height;
				$test['post_width']=$post_width;
				$formulla = $this->formulaHeightWidthGusset($post_height,$post_width,$gusset,$product_id);
				$actualHeight = $formulla['formula'];
				$height = $formulla['height'];
				$width = $formulla['width'];	 
				$test['height']=$height;
				$test['width']=$width;
				if($formulla['intoHeight'] == 1){
					$widthHeight = $height;
				}elseif($formulla['intoWidth'] == 1){
					$widthHeight = $width;
				}else{
					$widthHeight = $width;
				}
				
				
				if(isset($data['material']) && !empty($data['material']) && isset($data['thickness']) && !empty($data['thickness'])){
					$total_material = count($data['material']);
					$layerPrice = array();
					$checkCppMaterial = 0;
					$setQueryData = array();
					$materialName = '';
					for($p=0;$p<$total_material;$p++)
					{
						$setNumber = $p.'0';
						$addingActualHeight = ( $setNumber / 1000 ); 
						$newLayerWiseHeight = ( $actualHeight + $addingActualHeight);
						//[kinjal] on 19-4-2017 for when make pouch is paper that time it will be take polyester material //kinjal add make id 6 con for oxo on 9-2-2018 order by shishir sir
						if(($data['make']=='2' || $data['make']=='6') && $p==0)
						{
						        $material = $data['material'][0];
						        $material_thickness = $data['thickness'][0];
						        $thicknessPrice_paper = $this->getMaterialThickmessPrice( $material,$material_thickness);
						        $materialName_paper = getName('product_material','product_material_id',$data['material'][0],'material_name');
						        $gsm_paper =$this->getMaterialGsm($data['material'][0]);
						        $layerWiseGsmThickness_paper[$p+1] = $this->getLayerWiseGsmThickness($newLayerWiseHeight,$widthHeight,$material_thickness,$gsm_paper);
						        $layerPrice_paper[$p+1] = $this->getLayerPrice($layerWiseGsmThickness_paper[$p+1],$thicknessPrice_paper);
						        $data['material'][0] = 3;
						        $data['thickness'][0] = 12;
						}
						

						$gsm =$this->getMaterialGsm($data['material'][$p]);
						//Thickness
							$checkCppMaterial = $this->checkMaterial($data['material'][$p]);
							$thicknessPrice = $this->getMaterialThickmessPrice($data['material'][$p],$data['thickness'][$p]);
							$strip_thickness=0;
							if($data['product']=='10')
							{
								$mailer_strip=$this->getProduct($data['product']);
								$strip_thickness=$mailer_strip['strip_thickness'];
								$data_thickness = $data['thickness'][$p]+$mailer_strip['strip_thickness'];
							}	
							else
							{
								$data_thickness=$data['thickness'][$p];
							
							}	
							
							$test['$newLayerWiseHeight'][]=$newLayerWiseHeight;
							$test['$widthHeight'][]=$widthHeight;
							$test['$data_thickness'][]=$data_thickness;
							$test['$gsm'][]=$gsm;
							
								$layerWiseGsmThickness[$p+1] = $this->getLayerWiseGsmThickness($newLayerWiseHeight,$widthHeight,$data_thickness,$gsm);
								
							
							$materialName = getName('product_material','product_material_id',$data['material'][$p],'material_name');
							//3_11_2015 condition for mailer bag
							if($data['product']=='10')
							{
									$layerPrice[$p+1]=1000/$layerWiseGsmThickness[$p+1];
									
							}
							else
							{
									$layerPrice[$p+1] = $this->getLayerPrice($layerWiseGsmThickness[$p+1],$thicknessPrice);
							}
						        //kinjal add make id 6 con for oxo on 9-2-2018 order by shishir sir
								if(($data['make']=='2' || $data['make']=='6') && $p==0)
								{
								   
								    $setQueryData[] = " layer = '".(int)($p+1)."', material_id = '".(int)$material."', material_gsm = '".(float)$gsm_paper."', material_thickness = '".$material_thickness."', material_price = '".(float)$thicknessPrice_paper."', material_name = '".$materialName_paper."', layer_wise_gsmthickness = '".(float)$layerWiseGsmThickness_paper[$p+1]."', layer_wise_price = '".(float)$layerPrice_paper[$p+1]."', date_added = NOW()";	
								    $layerWiseGsmThicknessForPaper[$p+1]=$layerWiseGsmThickness_paper[$p+1];
								}
                                else
                                {
    							  
    							    $setQueryData[] = " layer = '".(int)($p+1)."', material_id = '".(int)$data['material'][$p]."', material_gsm = '".(float)$gsm."', material_thickness = '".$data['thickness'][$p]."', material_price = '".(float)$thicknessPrice."', material_name = '".$materialName."', layer_wise_gsmthickness = '".(float)$layerWiseGsmThickness[$p+1]."', layer_wise_price = '".(float)$layerPrice[$p+1]."', date_added = NOW()";	
                                    $layerWiseGsmThicknessForPaper[$p+1]=$layerWiseGsmThickness[$p+1];
                                    
                                }
					}
					$test[]['gsm']=$gsm;
					$test[]['newLayerWiseHeight']=$newLayerWiseHeight;
					$test[]['checkCppMaterial']=$checkCppMaterial;
					$test[]['thicknessPrice']=$thicknessPrice;
					$test[]['layerWiseGsmThickness']=$layerWiseGsmThickness;
					$test[]['layerPrice']=$layerPrice;
					$test[]['setQueryData']=$setQueryData;
					$totalLayer = count($data['material']);
					$layerCount = (isset($p))?$p:'';
					//total GSM THICKNESS
					//kinjal add make id 6 con for oxo on 9-2-2018 order by shishir sir
				    if($data['make']=='2'  || $data['make']=='6')
					{	
						$totalLayerGsmThickness = $this->sumOfNumericArray($layerWiseGsmThicknessForPaper);
					}
					else
						$totalLayerGsmThickness = $this->sumOfNumericArray($layerWiseGsmThickness);
					
						$test[]['totalLayerGsmThickness']=$totalLayerGsmThickness;
					//Total Layer wise Price
					$totalLayerPrice = $this->sumOfNumericArray($layerPrice);
						$test[]['totalLayerPrice']=$totalLayerPrice;
					//change code for change function
					$printing_option = "Without Printing";
						$onlyInkPrice = 0;
						$printingEffectPrice = 0;
						$inkPrice = 0;
						$inkSolventPrice = 0;
						 $cust_adhesive_mul_by = $cust_ink_mul_by = 0;
						 
				    $printing_effect = $data['printing_effect'];
					if(isset($data['printing']) && $data['printing'] == 1)
					{
						$printing_option = "With Printing";
						$onlyInkPrice = $this->getInkPrice1($data['make']);
						$inkSolventPrice = $this->getInkSolventPrice($layerWiseGsmThickness[1],1,$data['make']);
						if(isset($data['printing_effect']) && !empty($data['printing_effect']) && (int)$data['printing_effect'] > 0)
						{
							if($data['make']=='9' && $data['printing_effect']=='2')
							    $data['printing_effect']=3;
							$printingEffectPrice = $this->getPrintingEffectPrice($data['printing_effect']);
						}
						//[kinjal]: 19-8-2016 we changed to $inkPrice = (($onlyInkPrice + $printingEffectPrice) * $layerWiseGsmThickness[1]) to this if condition for only matt shiny effect
						$multi_by='';
						if($data['printing_effect'] == '3')
						{
							//[kinjal]: 11-4-2017 changed to  call this for only matt shiny effect
							$multi_by = $this->getPrintingEffectMultiBy($data['printing_effect']);
							$inkPrice = (($onlyInkPrice * $multi_by) * $layerWiseGsmThickness[1]);
						}
						else
							$inkPrice = (($onlyInkPrice + $printingEffectPrice) * $layerWiseGsmThickness[1]);
						
						//kinjal add make id 6 con for oxo on 9-2-2018 order by shishir sir
						if($data['make']=='2'  || $data['make']=='6')
						{
						    $cust_mul = $this->getCustmul();
						    $inkPrice = $inkPrice * $cust_mul['ink_mul'];
						    $inkSolventPrice = $inkSolventPrice * $cust_mul['ink_mul'];
						    $cust_ink_mul_by = $cust_mul['ink_mul'];
	;					}
					}			
					$test['onlyInkPrice']=$onlyInkPrice;
					$test['printingEffectPrice']=$printingEffectPrice;
					$test['inkPrice']=$inkPrice;	
					//if($_SESSION['ADMIN_LOGIN_SWISS']=='1'  && $_SESSION['LOGIN_USER_TYPE']=='1')
					    //printr($test);printr($data);
					//Adhesive and adhesive solvent
					if($checkCppMaterial == 1 ){
						$make='';
						$va=3;
						$cond=1;
					}else{
						$make=$data['make'];
						$va=5;
						$cond=0;
					}
					$adhesivePrice = $this->getAdhesivePriceCpp($layerWiseGsmThickness[1],$layerCount,1,$make,$va,$cond);
					
					$adhesiveSolventPrice = $this->getAdhesivePriceCpp($layerWiseGsmThickness[1],$layerCount,1,$make,$va,2);
					//kinjal add make id 6 con for oxo on 9-2-2018 order by shishir sir
					if($data['make']=='2'  || $data['make']=='6')
					{
					    $adhesivePrice = $adhesivePrice * $cust_mul['adhesive_mul'];
					    $adhesiveSolventPrice = $adhesiveSolventPrice * $cust_mul['adhesive_mul'];
					     $cust_adhesive_mul_by = $cust_mul['adhesive_mul'];
					}
					
					
					//Total Price : SUM of all price and calculate average price
					$test['inkSolventPrice']=$inkSolventPrice;
					
					if($data['product']=='10')
					{
						$adhesivePrice=0;
						$adhesiveSolventPrice=0;
						$inkPrice=0;
						$inkSolventPrice=0;
					}
					$totalPrice = $this->numberFormate(($totalLayerPrice + $inkPrice + $inkSolventPrice + $adhesivePrice + $adhesiveSolventPrice),"5") ;
					//Packing price / pouch
					$packingPerPouch = $this->newPackingCharges($post_height,$post_width,$gusset,$product_id);	
					$valveBasePrice = 0;

					if(isset($data['valve'][0]) && !empty($data['valve'][0])){
						$valveBasePrice = $userInfo['valve_price'];
					 }
					 $handle_price = $handle_id = 0;
					 if($data['product']=='55' || $data['product']=='56' || $data['product']=='57')
					 {
						 if(isset($data['handle_name']) && !empty($data['handle_name']))
						 {
							 $handle = $this->getHandleType($data['handle_name']);
							 $handle_price = $handle['handle_price']; 
							 $handle_id = $handle['handle_id'];
						 }
					 }
					 $test['adhesivePrice']=$adhesivePrice;	
					 $test['adhesiveSolventPrice']=$adhesiveSolventPrice;	
					 $test['totalPrice']=$totalPrice;	
					 $test['packingPerPouch']=$packingPerPouch;
					 $test['valveBasePrice']=$valveBasePrice;
					  $test['handle_price']=$handle_price;
					//check product weight is with zipper or without zipper
					$zipperWiseData = array();
					if(isset($data['zipper'][0]) && !empty($data['zipper'][0]))
					{
						$zipper_id = decode($data['zipper'][0]);
						$spout_id = decode($data['spout'][0]);
						$spdata = $this->getSpout($spout_id);
						$zdata = $this->getZipperInfo($zipper_id);
					
						//[kinjal] : called this function for getting price of laser scoring on (7-12-2017)
							$laser_price = $this->getLaserScoringPrice($product_id,$data['laser_name']);
						//END [kinjal]
						
						$calculateZipperPrice = 0;
							if($zipper_id!='2' AND $zipper_id!='5' AND $zipper_id!='6' AND $zipper_id!='7' AND $zipper_id!='8' AND $zipper_id!='9' AND $zipper_id!='10' AND $zipper_id!='11' AND $zipper_id!='12' AND $zipper_id!='13')
							{
								if($zdata['price'] > 0 ){
									$calculateZipperPrice = $this->getCalculateZipperPrice($product_id,$post_height,$post_width,$zdata['price']);
								}
								if($zipper_id=='14' || $zipper_id=='16')
								{
									//jaya : in slider zipper price added $zdata['slider_price'] slider price on date 3-9-2016
									$calculateZipperPrice = $calculateZipperPrice + $zdata['slider_price'];
									
								}
								
							}
							else
							{
								$calculateZipperPrice = $zdata['price'];
							}
							$zipperWiseData = array(
								'product_zipper_id'	=> $zdata['product_zipper_id'],
								'zipperText'		   => $zdata['zipper_name'],
								'zipperBasePrice'	  => $zdata['price'],
								'calculatePrice'	   => $calculateZipperPrice		
							);
							$zipperBasePrice = $zdata['price'];
							$zipperCalculatePrice =$calculateZipperPrice;
							$spoutweight = $spdata['weight'];
							$tintieweight=$zdata['Weight'];
					}
					$test['zipperWiseData']=$zipperWiseData;	
					$test['zipperBasePrice']=$zipperBasePrice;	
					$test['zipperCalculatePrice']=$zipperCalculatePrice;	
					$spoutArray = array();

					if(isset($data['spout'][0]) && !empty($data['spout'][0])){
						$spout_id = decode($data['spout'][0]);
							$spoutInfo = $this->getSpout($spout_id);
							if($spoutInfo){
								$spoutArray = array(
									'product_spout_id'	=> $spoutInfo['product_spout_id'],
									'spout_name'		  => $spoutInfo['spout_name'],
									'price'	  		   => $spoutInfo['price']
								);
							}
					}
					$test['spoutArray']=$spoutArray;	
					//Accessorie
					$accessorieArray = $accessorieArraySecond = array();
					if(isset($data['accessorie'][0]) && !empty($data['accessorie'][0])){
						$accessorie_id = decode($data['accessorie'][0]);
							$accessorieInfo = $this->getAccessorie($accessorie_id);
							if($accessorieInfo){
								$accessorieArray = array(
									'product_accessorie_id'	=> $accessorieInfo['product_accessorie_id'],
									'accessorie_name'	 => $accessorieInfo['accessorie_name'],
									'price'	  		   => $accessorieInfo['price']
								);
							} 
					}
						$accessorie_name_corner=$accessorie_price_corner='';
					if(isset($data['accessorie'][1]) && !empty($data['accessorie'][1])){
						$accessorie_id = decode($data['accessorie'][1]);
							$accessorieInfo = $this->getAccessorie($accessorie_id);
							if($accessorieInfo){
								$accessorieArraySecond = array(
									'product_accessorie_id'	=> $accessorieInfo['product_accessorie_id'],
									'accessorie_name'	 => $accessorieInfo['accessorie_name'],
									'price'	  		   => $accessorieInfo['price']
								);
							} 
							$accessorie_name_corner=$accessorieArraySecond['accessorie_name'];
							$accessorie_price_corner=$accessorieArraySecond['price'];
					}
						$test['accessorieArray']=$accessorieArray;	
						$test['accessorieArraySecond']=$accessorieArraySecond;	
					//COURIER AND TRANSPORT CALCULATION
					$transportByAir = 0;
					$transportBySea = 0;
					$transportByPickup = 0;
					if(isset($data['transpotation']) && !empty($data['transpotation']) && count($data['transpotation']) > 0 ){
						if(in_array(encode('air'),$data['transpotation'])){
							$transportByAir = 1;
							
						}
						if(in_array(encode('sea'),$data['transpotation'])){
							$transportBySea = 1;
							
						}
						if(in_array(encode('pickup'),$data['transpotation'])){
							$transportByPickup = 1;
						}
					}else{
						$transportBySea = 1;
					}
					$shilmentCountry = $this->getCountry($data['country_id']);
					if(strtolower($shilmentCountry['country_name']) == "india"){
						$transportByAir = 0;
						$transportBySea = 0;
						$transportByPickup = 1;
					}
					
					//courie calculation
					$courierChargeWithZipper = 0;
					$courierChargeWithoutZipper = 0;
					$fuleSurchargeWithZipper  = 0;
					$serviceTaxWithZipper = 0;
					$fuleSurchargeWithoutZipper  = 0;
					$serviceTaxWithoutZipper = 0;
					$handlingCharge = 0;
					$fual_surcharge_base_price = 0;
					$service_tax_base_price = 0;
					$handling_base_price = 0;
					if($transportByAir){
						$countryCourierData = $this->getCountryCourier($data['country_id']);
						$fual_surcharge_base_price = $countryCourierData['fuel_surcharge'];
						$service_tax_base_price = $countryCourierData['service_tax'];
						$handling_base_price = $countryCourierData['handling_charge'];
						
					}
				//	$gress_qtywise_sea = $this->getGressQtyWise($quantity,'sea',$admin_user_id);
			//		$gress_qtywise_sair = $this->getGressQtyWise($quantity,'sea',$admin_user_id);
					
					$userGress = $userInfo['gres'];
					$userGressSea = $userInfo['gres_sea'];
					$userGressAir = $userInfo['gres_air'];
					$userGressCylinder = $userInfo['gres_cyli'];
					
					$customer_gress = 0;
					$customer_email = '';
					if(isset($data['customer_check']) ){
						$customer_email = (isset($data['customer_email']) && $data['customer_email'] != '')?$data['customer_email']:'';
						$customer_gress =  (isset($data['customer_gress']) && (int)$data['customer_gress'] > 0 )?(int)$data['customer_gress']:0;					
					}
					//new code for multipale quantity
					$quantityArray = $data['quantity'];
					$quantityWiseData = array();
					$i=1;
					foreach($quantityArray as $key=>$eQuantity)
					{	
						//Transpotation / pouch
						$transportPerPouch = 0;
						$spout_by_sea_price = 0;
						if($transportBySea){
							$transportPerPouch1 = $this->getCalculateTransport($post_height,$post_width,$gusset);
							$spout_by_sea_price=$this->getSpout($spout_id);
							$transportPerPouch=$transportPerPouch1+$spout_by_sea_price['by_sea'];
							$originaltransportPerPouch=$transportPerPouch1;
							$spout_transport_by_sea=$spout_by_sea_price['by_sea'];
							$test['$transportPerPouch1']=$transportPerPouch1;
						}
						$spout_by_sea_price=$this->getSpout($spout_id);
						$perpouchpackingprice=$packingPerPouch+$spout_by_sea_price['additional_packaging_price'];
						$originalperpouchpackingprice=$packingPerPouch;
						$spout_additional_packing_price=$spout_by_sea_price['additional_packaging_price'];
							$quantity = decode($eQuantity);
							
							//Laser Cal [kinjal] on (7-12-2017)
							$withWidth = ($post_width * $laser_price)/100;
							//END [kinjal]
							
							//Wastage
							//edited by jayashree on 30th april 2015
							$profit = $this->getcalculateProfit($quantity,$data['product'],$post_height,$post_width,$gusset);
						
						
					      
					    	//add by sonu
					    	  
					    	  if($admin_user_id=='44' && $user_type_id=='4'){
						    	$gress_qtywise_air = $this->getGressQtyWise($quantity,'air',$admin_user_id);
						    	$userGressAir = $gress_qtywise_air['percentage'];
						        $gress_qtywise_sea = $this->getGressQtyWise($quantity,'sea',$admin_user_id);
							    $userGressSea = $gress_qtywise_air['percentage'];
					            }
				            //end
						
						
						
						
						
						
							$wastageBase = $profit['wastage_per'];
							$addingWastage = 0;
							if($post_height > 500){
								$addingWastage = 10;
							}
							$totalWastage = ($wastageBase + $addingWastage);
							$wastage = $this->numberFormate((($totalPrice * $totalWastage) / 100),"5");
							if($data['product']=='10')
							{
								$finalPrice = $totalPrice+$wastage;
								$mailer_per_kg_price1= $this->getProduct($data['product']);
								$mailer_per_kg_price=$mailer_per_kg_price1['per_kg_price'];
								
								$mailer_bag_price=$mailer_per_kg_price1['per_kg_price']/$finalPrice;
								$pricePerBag=$mailer_bag_price;
								$plastic_color=$data['color'];
							}
							else
							{
								$finalPrice = ($totalPrice + $wastage);
								$mailer_per_kg_price=0;
								$mailer_bag_price=0;
								$plastic_color='';

								$pricePerBag = $this->numberFormate(($finalPrice / 1000),"5");
							}
							
							$test['$transportPerPouch']=$transportPerPouch;
							
							$test['$perpouchpackingprice']=$perpouchpackingprice;
							$test['$totalWastage']=$wastageBase.'+'.$addingWastage;
							$test['wastage']=$totalPrice.'*'.$totalWastage;
								$test['wastage']=$wastage;
							$test['price_per_bag']=$pricePerBag;
							//edited today 12-10-2015
							$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice ),"5");
							//Profit / pouch
							$finalyPerPuchPrice = $this->numberFormate(($optionPrice + $profit['profit']),"5");
							$test['$finalyPerPuchPrice']=$optionPrice.'+'.$profit['profit'];
							$totalWeightWithZipper = 0;
							$totalWeightWithoutZipper=0;
							$courierChargeBaseWithZipper = 0;
							$courierChargeBaseWithoutZipper = 0;
							//edited today 12-10-2015
							$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $transportPerPouch ),"5");
							$pricePerPuchWithOption = $this->numberFormate(($optionPrice + $profit['profit'] + $zipperCalculatePrice + $valveBasePrice + $handle_price),"5");
							//total price without coutier charge
							$ftotalPrice = $this->numberFormate(($pricePerPuchWithOption * $quantity),"5"); //corrected on 9-10-2015 jaya
							$totalForFormula=0;
							$test['$pricePerPuchWithOption'] = $pricePerPuchWithOption;
							$test['$optionPrice'] = $pricePerBag.'==$pricePerBag+'.$perpouchpackingprice.'==$perpouchpackingprice+'.$transportPerPouch.'==$transportPerPouch';
							$test['$ftotalPrice'] = $optionPrice.'==$optionPrice+'.$profit['profit'].'==$profit+'.$zipperCalculatePrice.'==$zipperCalculatePrice'.$valveBasePrice.'==$valveBasePrice+'.$handle_price.'==$handle_price';
							if(decode($data['zipper'][0])==2 || decode($data['zipper'][0])==5 || decode($data['zipper'][0])==6 || decode($data['zipper'][0])==7 || decode($data['zipper'][0])==9)
							{
								$totalWeightWithoutZipper = $this->getCalculateWeightZipper($totalLayerGsmThickness,$quantity,2.5);
								if(decode($data['zipper'][0])!=2)
								{
									$totalForFormula = $totalWeightWithoutZipper+($quantity*$tintieweight);
									$totalWeightWithoutZipper=$totalForFormula;
								}
								else
								{
									if($data['make']==5)
									{
										$totalWeightWithoutZipper=$totalWeightWithoutZipper+($quantity*$spoutweight);
										$totalForFormula = $totalWeightWithoutZipper;
									}
									else if($data['product']=='10')
									{
										$totalWeightWithoutZipper=$quantity/$totalPrice;
										$test['mailer= totalWeightWithoutZipper']=$quantity.' / '.$totalPrice;
										$totalForFormula = $totalWeightWithoutZipper;
									}
									else
										$totalForFormula = $totalWeightWithoutZipper;
								}
								
							}
							else
							{
								//Total Weight with zipper
    								$totalWeightWithZipper = $this->getCalculateWeightZipper($totalLayerGsmThickness,$quantity,3.75);
								//Total Weight with slider zipper weight jaya : 3-9-2016
								if(decode($data['zipper'][0])=='14' || decode($data['zipper'][0])=='16')
								{
								    //tintie weight and slider zipper weight both comes in same variable that is $tintieweight jaya : 3-9-2016
									$totalWeightWithZipper = $totalWeightWithZipper + ($quantity*$tintieweight);								
								}
								$totalForFormula = $totalWeightWithZipper;	
							}	
							//courier charge
							$test['totalWeightWithZipper']=$totalWeightWithZipper;
							$courierBasePriceQuantityWise = array();
							$transportAndCoutierCharge = '';
							$actual_courier_per_kg_price = '';
							if($transportByAir)
							{								

								$courierChargeBaseZipper = $this->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'],$totalForFormula);
								$actual_courier_per_kg_price = $courierChargeBaseZipper['PerKgPrice'];
								$courierBasePriceQuantityWise[$quantity] = array(
									'withZipepr'  => decode($data['zipper'][0])!=2?$courierChargeBaseZipper['price']:0, 
									'noZipper'	=>  decode($data['zipper'][0])==2?$courierChargeBaseZipper['price']:0, 
								);
								$test['courierChargeBaseZipper']=$courierChargeBaseZipper['price'];
							
									if($fual_surcharge_base_price > 0){
										$fuleSurchargeForZipper = (($courierChargeBaseZipper['price'] * $fual_surcharge_base_price) / 100);
										if(decode($data['zipper'][0])==2)
										{
											$fuleSurchargeWithoutZipper = $fuleSurchargeForZipper;
										}
										else
										{
											$fuleSurchargeWithZipper = $fuleSurchargeForZipper;
										}
									}
									$test['fuleSurchargeForZipper']=$fuleSurchargeForZipper;
									if($service_tax_base_price > 0){
										$courierCrhgFuleZipper = ($courierChargeBaseZipper['price'] + $fuleSurchargeForZipper);
										$serviceTaxZipper = (($courierCrhgFuleZipper * $service_tax_base_price) / 100);
										if(decode($data['zipper'][0])==2)
										{
											$courierCrhgFuleWithoutZipper =$courierCrhgFuleZipper;
											$serviceTaxWithoutZipper = $serviceTaxZipper ;
										}
										else
										{
											$courierCrhgFuleWithZipper = $courierCrhgFuleZipper ;
											$serviceTaxWithZipper = $serviceTaxZipper ;
										}
									}
									$test['courierCrhgFuleZipper']=$courierCrhgFuleZipper;
									if($handling_base_price > 0){
										$handlingCharge = $handling_base_price;
									}
									//courier charge with zipper	
									$courierChargeZipper = $this->numberFormate(($courierChargeBaseZipper['price'] + $fuleSurchargeForZipper + $serviceTaxZipper + $handlingCharge),"3");
									$test['courierChargeZipper']=$courierChargeZipper;	
									if(decode($data['zipper'][0])==2)
									{
										//courier charge without zipper
										$courierChargeWithoutZipper = $courierChargeZipper;
									}
									else
									{
										$courierChargeWithZipper = $courierChargeZipper;
									}	
									$transportAndCoutierCharge = $courierChargeZipper;
									$ftotalPrice = $this->numberFormate(($ftotalPrice + $courierChargeZipper),"5");
									
								}
							$test['ftotalPrice']=$ftotalPrice;
							$taxation='';
							$taxation_data=array();
							$final_tax_name='';
							$tax_name='';
							if(isset($data['country_id']) && !empty($data['country_id']) && $data['country_id'] ==111)
							{
								/*$tax_name.=' tax_name="'.$data['normalform'].'"';
								$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
								$data_tax = $this->query($sql);
								$taxation_data=$data_tax->row;*/
								
								$sql="SELECT * FROM product_gst_master WHERE find_in_set(".$data['product'].",product_id) <> 0";
								$data_tax = $this->query($sql);
								$taxation_data=$data_tax->row;
							}
							$test['$tax_name']=$tax_name;
							$test['$taxation_data']=$taxation_data;
							$zipperData = array();
							$zipperValue = $zipperWiseData;
							$valve_text= 'No Valve';
							$courierCharge=$courierChargeWithoutZipper;		
							$spoutPrice=0;	
							$accessoriePrice=0;	
							$accessoriePriceSecond=0;	
							$courierChargeInFormula = $courierChargeWithoutZipper;		
							$withValvePrice = 0;
							$priceWithTransport = 0;
							$bySea =array();
							$byAir = array();
							$byPickup = array();
							if($zipperValue['zipperBasePrice'] > 0){
								$courierCharge=$courierChargeWithZipper;	
								$courierChargeInFormula = $courierChargeWithZipper;	
								//[kinjal] : on 14-6-2017 online:16 and offline:15
								if($transportByAir && ($zipper_id=='14' || $zipper_id=='16') )
								{
									$courierChargeInFormula =$courierChargeInFormula*1.2;
								}
							}
							if(isset($spoutArray) && $spoutArray['price'] !=  0.000){
								$spoutPrice=$spoutArray['price'];
								if($transportByAir){
									 $courierChargeInFormula =  $courierChargeInFormula * 1.4;
								}
								if($transportBySea){
									 $transportPerPouch = $transportPerPouch + 0.10;
								}
							}
							if(isset($accessorieArray) && $accessorieArray['price'] !=0.0000){
								$accessoriePrice=$accessorieArray['price'];
							}
							if(isset($accessorieArraySecond) && $accessorieArraySecond['price'] !=0.0000){
								$accessoriePriceSecond=$accessorieArraySecond['price'];
							}
							if(isset($data['valve']) && in_array('1',$data['valve'])){
								$valve_text= 'With Valve';
							}
							//Make else if condtion for center bz by default insert center in table and that view in email format (26-12-2015) [kinjal]: 
							$spout_pouch_type_txt= '';
							if(isset($data['spout_pouch']) && in_array('Corner',$data['spout_pouch']) && $data['make']==5){
								$spout_pouch_type_txt= 'Corner';
							}
							elseif(isset($data['spout_pouch']) && in_array('Center',$data['spout_pouch']) && $data['make']==5){
								$spout_pouch_type_txt= 'Center';
							}
								
							//end [kinjal]
							if($transportByAir){
								
								$withValvePrice = $this->numberFormate((($finalyPerPuchPrice + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice + $accessoriePriceSecond + $withWidth + $handle_price) * $quantity ) + $courierChargeInFormula);

								if($data['make']=='5' && decode($data['size'])==0)
								{
									$withValvePrice=$withValvePrice-$courierChargeInFormula;
									$withValvePrice=$withValvePrice+($withValvePrice*15/100);
									$withValvePrice=$withValvePrice+$courierChargeInFormula;
								}
								
								$byAir['totalPriceByAir'] = $withValvePrice;	
								$byAir['userGress'] = $userInfo['gres_air'];
								
							}
							$test['totalPriceByAir']= $withValvePrice;
							if($transportBySea){
							
								if($data['make']=='5' && decode($data['size'])==0)
								{
									$priceWithTransport =  $this->numberFormate((($finalyPerPuchPrice  + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice + $accessoriePriceSecond + $withWidth + $handle_price)  * $quantity ));
									$priceWithTransport=$priceWithTransport+($priceWithTransport*15/100);
									$priceWithTransport=$priceWithTransport+($transportPerPouch*$quantity);
								}	
								else
								{
									$priceWithTransport =  $this->numberFormate((($finalyPerPuchPrice + $transportPerPouch  + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice + $accessoriePriceSecond + $withWidth + $handle_price) * $quantity ));
								
								}
								$bySea['totalPriceBySea'] = $priceWithTransport;
								$bySea['userGress'] = $userInfo['gres_sea'];
								$test['$priceWithTransport'] = $finalyPerPuchPrice .'+'.$transportPerPouch.'+'.$zipperValue['calculatePrice'].'+'.$valveBasePrice.'+'.$spoutPrice.'+'.$accessoriePrice.'+'.$accessoriePriceSecond.'*'.$quantity;
							}
							$test['totalPriceBySea']= $priceWithTransport;
							if($transportByPickup){
								$priceWithPickup =$this->numberFormate((($finalyPerPuchPrice   + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice + $accessoriePriceSecond + $withWidth + $handle_price) * $quantity ));								
								
								if($data['make']=='5' && decode($data['size'])==0)
								{
									$priceWithPickup=$priceWithPickup+($priceWithPickup*15/100);
								}
								$byPickup['totalPriceByPickup'] = $priceWithPickup;
								$byPickup['userGress'] = $userInfo['gres'];
								$test['$priceWithPickup'] = $finalyPerPuchPrice .'+'.$withWidth.'+'.$zipperValue['calculatePrice'].'+'.$valveBasePrice.'+'.$spoutPrice.'+'.$accessoriePrice.'+'.$accessoriePriceSecond.'+'.$handle_price.'*'.$quantity;
							}
							//if($_SESSION['ADMIN_LOGIN_SWISS']=='17'  && $_SESSION['LOGIN_USER_TYPE']=='2')
							//printr($test);die;
							$zipperData[] = array(
									'zip_text'		=> $zipperValue['zipperText'],
									'valve_text'      => $valve_text,
									'spout_txt'	=> $spoutArray['spout_name'],
									'spout_pouch_type' => $spout_pouch_type_txt,
									'spout_price'		=> $spoutArray['price'],
									'accessorie_txt'	=> $accessorieArray['accessorie_name'],
									'accessorie_price'		=> $accessorieArray['price'],
									'accessorie_txt_corner'	=> $accessorie_name_corner,
									'accessorie_price_corner' => $accessorie_price_corner,
									'transportPerPouch'	=> $transportPerPouch,									
									'courierCharge' => $courierChargeInFormula,
									'actual_courier_price'=>$actual_courier_per_kg_price,
									'calculateZipperPrice'	=> $zipperValue['calculatePrice'],	
									'valve_price'=>$valveBasePrice,					
									'BySea'	=>  $bySea,
									'ByAir'	=> $byAir,
									'ByPickup' => $byPickup,
									'gressCyli'=> $userInfo['gres_cyli'],
								);
								$valveTxt =$valve_text;
								$zipperText =$zipperValue['zipperText'];
								$zipperCalculatePrice=$zipperValue['calculatePrice'];
								$spoutTxt=$spoutArray['spout_name'];
								$spoutBasePrice=$spoutArray['price'];
								$accessorieTxt =$accessorieArray['accessorie_name'];
								$accessorieBasePrice=$accessorieArray['price'];
								$accessorieTxtCorner =$accessorie_name_corner;
								$accessorieBasePriceCorner=$accessorie_price_corner;
						//spout
						$spoutQuantityWiseData[] = array(
							'spout_txt'	=> $spoutArray['spout_name'],
							'price'		=> $spoutArray['price'],
						
						); 
						//Accessorie
						$accessorieQuantityWiseData[] = array(
							'accessorie_txt'	=> $accessorieArray['accessorie_name'],
							'price'		=> $accessorieArray['price'],
						);
						
						$accessorieQuantityWiseDataCorner[] = array(
							'accessorie_txt_corner'	=> $accessorie_name_corner,
							'price_corner'		=> $accessorie_price_corner,
						);
						//gress percentage price
						//store quantity wise information
						$quantityWiseData[$quantity] = array(
							'wastageBase'	=> $wastageBase,
							'addingWastage'  => $addingWastage,
							'wastage'		=> $wastage,
							'nativePricePerBag' => $pricePerBag,
							'totalWeightWithZipper' => $totalWeightWithZipper,
							'totalWeightWithoutZipper' => $totalWeightWithoutZipper,
							'profit'		 => $profit['profit'],
							'pricePerBag'   => $pricePerBag,
							'wastageBasePrice' => $wastageBase,
							'wastageAddingPint' => $addingWastage,
							'zipperData'	=> $zipperData,
							'spoutData'     => $spoutQuantityWiseData,
							'accessorieData'	=> $accessorieQuantityWiseData,
							'accessorieDataCorner'	=> $accessorieQuantityWiseDataCorner,
							
						);
				$i++;}
					
					$userCountry = $this->getUserCountry($user_type_id,$user_id); 
					//Extra tool Price
					$tool_price = $this->getToolPrice($post_width,$gusset,$product_id);
					if($user_type_id==1){
						$userCurrency = $this->getCurrencyInfo($user_id);
						$userCurrency['tool_rate']='';
					}else{ 
						$userCurrency =  $this->getUserWiseCurrency($user_type_id,$user_id);
					}
					
					//Cylinder Price
					$cylinderPrice = $this->getCalculateCylinderPrice($post_height,$post_width,$gusset,$data['country_id'],$product_id,$data['gusset_printing_option']);
				        if($product_id=='7')
				            $cylinderPrice=$cylinderPrice+3000; 
				 
					$cylinderCurrencyPrice = $cylinderPrice;
				    
					if($user_type_id==1)
					{
						$currCode ='INR';
						//make if condition fro admin to get selectes currency code [kinjal] : 23-12-2015
						if(isset($data['selcurrency'])&& !empty($data['selcurrency']))
						{
							$curr = explode('==',$data['selcurrency']);
							$currCode= $curr[0];
						} 	
						//end [kinjal]									
					}
					else
					{
						$currCode=$userCurrency['currency_code'];
					}
					
					if($userCurrency['tool_rate']){
							$cylinderCurrencyPrice = ($cylinderPrice / $userCurrency['cylinder_rate']);
							$tool_price = ($tool_price / $userCurrency['tool_rate']);
					}
					$test['$cylinderCurrencyPrice1']=$cylinderCurrencyPrice;
					//make if condition fro admin to get tool & cylinder rate [kinjal] : 23-12-2015
					if($user_type_id==1 && isset($data['swiss_tool_rate']) && !empty($data['swiss_tool_rate']) && isset($data['swiss_cylinder_rate']) && !empty($data['swiss_cylinder_rate']))
					{
						$cylinderCurrencyPrice = ($cylinderPrice / $data['swiss_cylinder_rate']);
						$tool_price = ($tool_price / $data['swiss_tool_rate']);
						
						$cyliCurrencyPricenew = (3000 / $data['swiss_cylinder_rate']);
							$test['$cyliCurrencyPricenew']=$cyliCurrencyPricenew;
						if($product_id=='7')
						    $cylinderCurrencyPrice=$cylinderCurrencyPrice+$cyliCurrencyPricenew;
					}
					else
					{
						$data['swiss_cylinder_rate']=$data['swiss_tool_rate']='';
					}
					//end [kinjal]
					
					$cylinderCurrencyMinPrice = $this->getCylinderBasePrice($currCode,$admin_user_id);
					if($cylinderCurrencyMinPrice && $cylinderCurrencyMinPrice >= $cylinderCurrencyPrice ){
						$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
						if($userCurrency['tool_rate'])
						{
						    $cyliCurrencyPricenew = (3000 / $userCurrency['cylinder_rate']);
						    $test['$cyliCurrencyPricenew']=$cyliCurrencyPricenew;
						    if($product_id=='7')
    						        $cylinderCurrencyBasePrice=$cylinderCurrencyBasePrice+$cyliCurrencyPricenew;
    						$test['BasePrice']=$cylinderCurrencyBasePrice;
						}
						//[kinjal] added for cylinder gress price on (8-2-2018)
						$cylinderCurrencyGressPrice = $this->numberFormate((($cylinderCurrencyBasePrice * $userInfo['gres_cyli']) / 100),"3");
					}else{
						$cylinderCurrencyBasePrice = $cylinderCurrencyPrice;
						//[kinjal] added for cylinder gress price on (8-2-2018)
						$cylinderCurrencyGressPrice = $cylinderCurrencyBasePrice;
					} 
					if($cylinderCurrencyPrice <= $cylinderCurrencyMinPrice)
					{
						$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
						//[kinjal] added for cylinder gress price on (8-2-2018)
						if($userCurrency['tool_rate'])
						{
						    $cyliCurrencyPricenew = (3000 / $userCurrency['cylinder_rate']);
						    $test['$cyliCurrencyPricenew']=$cyliCurrencyPricenew;
						    if($product_id=='7')
    						        $cylinderCurrencyBasePrice=$cylinderCurrencyBasePrice+$cyliCurrencyPricenew;
    						//$test['BasePrice']=$cylinderCurrencyBasePrice;
						}
						$cylinderCurrencyGressPrice = $this->numberFormate((($cylinderCurrencyBasePrice * $userInfo['gres_cyli']) / 100),"3");
					}
					$test['$cylinderCurrencyBasePrice']=$cylinderCurrencyBasePrice;
					$test['$cylinderCurrencyPrice']=$cylinderCurrencyPrice;
					$test['$cylinderCurrencyMinPrice']=$cylinderCurrencyMinPrice;
					$test['$cylinderCurrencyGressPrice']=$cylinderCurrencyGressPrice;
					$test['gres_cyli']=$userInfo['gres_cyli'];			
					if($userCountry){
						$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
					}else{
						$countryCode='IN';
					}
					if($type=='Q')
					{
						$newQuotaionNumber = $this->generateQuotationNumber($multi_product_quotation_id);
						$quotation_number = $countryCode.$newQuotaionNumber;
					}
					$printingEffectName = getName('printing_effect','printing_effect_id',$printing_effect,'effect_name');
					$productName = getName('product','product_id',$data['product'],'product_name');
					$currency = 'INR';
					$currencyPrice = '1';
						
					if($user_type_id!=1){
						if($userCurrency['currency_code'] && $userCurrency['product_rate']){
							$currency = $userCurrency['currency_code'];
							$currencyPrice = $userCurrency['product_rate'];
							$test['product_rate']=$userCurrency['product_rate'];
						}
					}else{
						if( $userCurrency['cylinder_rate']){
							$currencyPrice = $userCurrency['cylinder_rate'];
						}
						if(isset($data['selcurrency'])&& !empty($data['selcurrency']))
						{
							$curr = explode('==',$data['selcurrency']);
							$currency= $curr[0];
							$currencyPrice = $data['sel_currency_rate'];
						}
					}
					if(isset($data['discount']))
						$data['discount']=$data['discount'];
					else
						$data['discount'] = 0.000;
					if($type=='Q')
					{
						
						$sql =  "UPDATE  ".DB_PREFIX."new_multi_product_quotation_id SET multi_quotation_number = '".$quotation_number."' WHERE multi_product_quotation_id = '".$multi_product_quotation_id."'";
						$this->query($sql);
						
						if($data['size']=='0')
							$ex_profit=0.00;
						else
							$ex_profit='';
							
						$sql_cyli="SELECT * FROM product_gst_master WHERE find_in_set(51,product_id) <> 0";
						$data_cyli = $this->query($sql_cyli);
						$taxation_cyli=$data_cyli->row;
								
						//jaya modified for cylinder price with taxation on 29-10-2015
						$totalcylinderpriceWithTax = $totaltoolpriceWithTax = '';
						if(isset($taxation_data) && !empty($taxation_data))
						{
							$cylinderpriceFortax = $cylinderCurrencyBasePrice;
							//[kinjal] updated on 10-7-2017 
							if($data['normalform']=='Out Of Gujarat')
							{
								/*$totalcylinderpriceWithTax = $cylinderpriceFortax+($cylinderpriceFortax*$taxation_data['igst']/100);
								$toolpriceFortax = $tool_price;
								$totaltoolpriceWithTax = $toolpriceFortax+($toolpriceFortax*$taxation_data['igst']/100);*/
								
								
								
								
								$totalcylinderpriceWithTax = $cylinderpriceFortax+($cylinderpriceFortax*$taxation_cyli['igst_percentage']/100);
								$toolpriceFortax = $tool_price;
								$totaltoolpriceWithTax = $toolpriceFortax+($toolpriceFortax*$taxation_cyli['igst_percentage']/100);
								
								$test['$cylinderpriceFortax+($cylinderpriceFortax*$taxation_cyli[igst_percentage]/100)']=$cylinderpriceFortax.'+('.$cylinderpriceFortax.'*'.$taxation_cyli['igst_percentage'].'/100)';
								
								$test['$totaltoolpriceWithTax = $toolpriceFortax+($toolpriceFortax*$taxation_cyli[igst_percentage]/100);']=$toolpriceFortax.'+('.$toolpriceFortax.'*'.$taxation_cyli['igst_percentage'].'/100)';
								
								
								
							}
							else
							{
								/*$totalcylinderpriceWithTax = $cylinderpriceFortax+($cylinderpriceFortax*($taxation_data['cgst']+$taxation_data['sgst'])/100);
								$toolpriceFortax = $tool_price;
								$totaltoolpriceWithTax = $toolpriceFortax+($toolpriceFortax*($taxation_data['cgst']+$taxation_data['sgst'])/100);*/
								
								$totalcylinderpriceWithTax = $cylinderpriceFortax+($cylinderpriceFortax*($taxation_cyli['cgst_percentage']+$taxation_cyli['sgst_percentage'])/100);
								$toolpriceFortax = $tool_price;
								$totaltoolpriceWithTax = $toolpriceFortax+($toolpriceFortax*($taxation_cyli['cgst_percentage']+$taxation_cyli['sgst_percentage'])/100);		
								
								$test['$cylinderpriceFortax+($cylinderpriceFortax*($taxation_cyli[cgst_percentage]+$taxation_cyli[sgst_percentage])/100)']=$cylinderpriceFortax.'+('.$cylinderpriceFortax.'*('.$taxation_cyli['cgst_percentage'].'+'.$taxation_cyli['sgst_percentage'].')/100)';
								$test['$toolpriceFortax+($toolpriceFortax*($taxation_cyli[cgst_percentage]+$taxation_cyli[sgst_percentage])/100)']=$toolpriceFortax.'+('.$toolpriceFortax.'*('.$taxation_cyli['cgst_percentage'].'+'.$taxation_cyli['sgst_percentage'].')/100)';
							}
							$test['$totalcylinderpriceWithTax']=$totalcylinderpriceWithTax;
						    $test['$toolpriceFortax']=$toolpriceFortax;
						    $test['$totaltoolpriceWithTax']=$totaltoolpriceWithTax;
																																	
						}
						
						//[kinjal] end
						 
					//add new two feild in insert query [kinjal] : 23-12-2015 (swiss_tool_rate ,swiss_cylinder_rate ); jaya added two fields cylinerpricewithtax on 29-10-2015 ; [kinjal] : 11-4-2017 added ink_multi_by
					
					$sql =  "INSERT INTO ".DB_PREFIX."new_multi_product_quotation SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect_id = '".(int)$printing_effect."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', volume = '".$post_volume."',plastic_color = '".$plastic_color."' ,mailer_per_kg_price='".$mailer_per_kg_price."',mailer_bag_price='".$mailer_bag_price."',strip_thickness='".$strip_thickness."',layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."',packing_price = '".(float)$originalperpouchpackingprice."',spout_additional_packing_price='".$spout_additional_packing_price."', valve_price = '".$valveBasePrice."', gress_percentage = '".$userGress."',gress_air = '".$userGressAir."',gress_sea = '".$userGressSea."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."',cylinder_price_withtax='".(float)$totalcylinderpriceWithTax."',tool_price = '".(float)$tool_price."',tool_price_withtax='".(float)$totaltoolpriceWithTax."',swiss_cylinder_rate = '".(float)$data['swiss_cylinder_rate']."', swiss_tool_rate = '".(float)$data['swiss_tool_rate']."', use_device = '".getDevice()."', status = '0', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', currency = '".$currency."', currency_price = '".$currencyPrice."', customer_name = '".addslashes($data['customer'])."', customer_email = '".$customer_email."',email = '".$data['email']."', customer_gress_percentage = '".$customer_gress."', shipment_country_id = '".$data['country_id']."', added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."',admin_user_id='".$admin_user_id."', multi_product_quotation_id = '".$multi_product_quotation_id."',size_id='".$data['size']."',extra_profit='".$ex_profit."',ink_multi_by = '".(float)$multi_by."',cust_ink_mul_by = '".$cust_ink_mul_by."' , cust_adhesive_mul_by='".$cust_adhesive_mul_by."'";
						
								
								$this->query($sql);
								$productQuatiationId = $this->getLastId();
								//added by p
								
								$emp_t_id = $_SESSION['LOGIN_USER_TYPE'];
								$emp_t_id = (int)$emp_t_id;
						
								$emp_u_id = $_SESSION['ADMIN_LOGIN_SWISS'];
								$emp_u_id = (int)$emp_u_id;
								
								$qry1 = "SELECT user_id as u_id,user_type_id as t_id FROM `" . DB_PREFIX . "employee` WHERE employee_id='".$emp_u_id."' limit 1";
								$rslt = $this->query($qry1);
								$admin_id= $rslt->row['u_id'];
								$admin_type_id= $rslt->row['t_id'];
								
								
								$cliet_name=$data['customer'];
								
								$qry = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "customer_address` WHERE ((user_id='".$admin_id."' and type_id='".$admin_type_id."') or (user_id='".$emp_u_id."' and type_id='".$emp_t_id."')) and customer_name='".addslashes(strtolower($cliet_name))."'";
								$result = $this->query($qry);
								$check=$result->row['total'];
								
								if($check<=0){
										$qry1 = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "customer_address` WHERE ((emp_id='".$admin_id."' and emp_type_id='".$admin_type_id."') or (emp_id='".$emp_u_id."' and emp_type_id='".$emp_t_id."')) and customer_name='".addslashes(strtolower($cliet_name))."'";
									$result1 = $this->query($qry1);
									$check1=$result1->row['total'];
									
									if($check1<=0){
										$query =  "INSERT INTO ".DB_PREFIX."customer_address SET customer_name = '".addslashes(strtolower($data['customer']))."',user_id='".$admin_id."',type_id='".$admin_type_id."',emp_id='".$emp_u_id."',emp_type_id='".$emp_t_id."',page_name='multi_quotation'";
								$this->query($query);
									}
								}
					//end p
								if(isset($productQuatiationId) && (int)$productQuatiationId > 0 ){
									//quotation currency
									if($customer_email && decode($data['sel_currency']) > 0 ){
										$selCurrecy = $this->getCurrencyInfoOld(decode($data['sel_currency']));
										if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
											$selCurrencyRate = $data['sel_currency_rate'];
										}else{
											$selCurrencyRate = $selCurrecy['price'];
										}
										$this->query("INSERT INTO ".DB_PREFIX."new_multi_product_quotation_currency SET product_quotation_id = '".$productQuatiationId."', currency_id = '".$selCurrecy['currency_id']."', currency_code = '".$selCurrecy['currency_code']."', 	currency_rate = '".$selCurrencyRate."', currency_base_rate = '".$selCurrecy['price']."', date_added = NOW() ");
									}
									//INSERT QUOTATION QUANTITY TABLE 
									
									if(isset($quantityWiseData) && !empty($quantityWiseData)){
										foreach($quantityWiseData as $quantity=>$quantityValue){
											
											$this->query("INSERT INTO ".DB_PREFIX."new_multi_product_quotation_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_adding = '".$quantityValue['addingWastage']."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit = '".$quantityValue['profit']."', total_weight_without_zipper = '".$quantityValue['totalWeightWithoutZipper']."', total_weight_with_zipper = '".$quantityValue['totalWeightWithZipper']."', date_added = NOW(),discount='".$data['discount']."'");
											$productQuatiationQuantityId = $this->getLastId();
											
											//zipperData
											if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
											
												foreach($quantityValue['zipperData'] as $zipData){
													
													$pricesql = "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', zipper_txt = '".$zipData['zip_text']."',gusset_printing_type='".$data['gusset_printing_option']."',valve_txt = '".$zipData['valve_text']."', date_added = NOW(), zipper_price = '".$zipData['calculateZipperPrice']."', spout_txt = '".$zipData['spout_txt']."', spout_base_price = '".$zipData['spout_price']."', accessorie_txt = '".$zipData['accessorie_txt']."', make_pouch = '".(int)$data['make']."',spout_pouch_type='".$zipData['spout_pouch_type']."',accessorie_base_price = '".$zipData['accessorie_price']."',valve_price = '".$zipData['valve_price']."' , accessorie_txt_corner = '".$zipData['accessorie_txt_corner']."', accessorie_base_price_corner = '".$zipData['accessorie_price_corner']."',actual_laser_price='".$laser_price."',laser_id='".$data['laser_name']."',handle_price = '".$handle_price."',handle_id='".$handle_id."',cyli_gress_price='".$cylinderCurrencyGressPrice."',gress_cyli_per='".$userInfo['gres_cyli']."',tamperevident='".$tamperevident."',slider_place='".$slider_place."',";
											

														$customerGressPrice = 0; 
														$gressPrice = 0;
														$gressPriceByPickup=0;
														$gressPriceByAir =0;
														$gressPriceBySea=0;
														$gress_qtywise_per_air=$gress_qtywise_per_pickup=$gress_qtywise_per_sea=0;
														$totalPricWithExcies =0;
														$totalPriceWithTax = 0;
														$tax_type='';
														$tax_percentage=0;
														$totalPriceForTax = 0;
														$excies = 0;$igst=$cgst=$sgst='0';
														$tot_price=0;
														if(isset($zipData['BySea']) && !empty($zipData['BySea']))
														{
															$tot_price=$zipData['BySea']['totalPriceBySea'];
															//kinjal on 9-2-2018 order by shishir sir
															
														}
														if(isset($zipData['ByAir']) && !empty($zipData['ByAir'])){
															$tot_price=$zipData['ByAir']['totalPriceByAir'];
															//kinjal on 9-2-2018 order by shishir sir
														
														}
														if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup']))
														{
															$tot_price=$zipData['ByPickup']['totalPriceByPickup'];
															//kinjal on 9-2-2018 order by shishir sir
															
														}
														
														
													    $test['$tot_price']=$tot_price;
													    
												
														if($customer_gress > 0)
														{
															$customerGressPrice = $this->numberFormate((($tot_price * $customer_gress) / 100),"3");
															
														}
														
														if(isset($zipData['ByPickup']['userGress']) && $zipData['ByPickup']['userGress'] > 0){
															$tot_price=$zipData['ByPickup']['totalPriceByPickup'];
															
															//[kinjal] done on 31-1-2018
															$gress_qtywise = $this->getGressQtyWise($quantity,'pickup',$admin_user_id);
															$gressPriceByPickup = $this->numberFormate((($tot_price * $gress_qtywise['percentage']) / 100),"3");
															
														    $gress_qtywise_per_pickup = $gress_qtywise['percentage'];
														}
														 if(isset($zipData['ByAir']['userGress']) && $zipData['ByAir']['userGress'] > 0){
															$tot_price=$zipData['ByAir']['totalPriceByAir'];
															
															//[kinjal] done on 31-1-2018
															$gress_qtywise = $this->getGressQtyWise($quantity,'air',$admin_user_id);
															$gressPriceByAir = $this->numberFormate((($tot_price * $gress_qtywise['percentage']) / 100),"3");
															
															$gress_qtywise_per_air = $gress_qtywise['percentage'];
														}
														 if(isset($zipData['BySea']['userGress']) && $zipData['BySea']['userGress'] > 0){
															$tot_price=$zipData['BySea']['totalPriceBySea'];
															
															//[kinjal] done on 31-1-2018
															$gress_qtywise = $this->getGressQtyWise($quantity,'sea',$admin_user_id);
															$gressPriceBySea = $this->numberFormate((($tot_price * $gress_qtywise['percentage']) / 100),"3");
															
															$gress_qtywise_per_sea = $gress_qtywise['percentage'];
														}
														if(isset($taxation_data) && !empty($taxation_data))
														{
															$totalPriceForTax = $tot_price+$gressPrice+$customerGressPrice;
															if($data['discount'])
															{
																$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
															}
														
															
															if($data['normalform']=='Out Of Gujarat')
															{
																$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*$taxation_data['igst_percentage']/100);
																$test['$totalPricWithExcies+($totalPricWithExcies*$taxation_data[igst_percentage]/100)']=$totalPricWithExcies.'+('.$totalPricWithExcies.'*'.$taxation_data['igst_percentage'].'/100)';
																$igst =$taxation_data['igst_percentage'];
															}
															else
															{
																$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*($taxation_data['cgst_percentage']+$taxation_data['sgst_percentage'])/100);
																$test['$totalPricWithExcies+($totalPricWithExcies*($taxation_data[cgst_percentage]+$taxation_data[sgst_percentage])/100)']=$totalPricWithExcies.'+('.$totalPricWithExcies.'*('.$taxation_data['cgst_percentage'].'+'.$taxation_data['sgst_percentage'].')/100)';
																$cgst =$taxation_data['cgst_percentage'];
																$sgst =$taxation_data['sgst_percentage'];
															}
															$test['$totalPriceWithTax']=$totalPriceWithTax;
															$test['$igst=$cgst=$sgst=']=$igst.'='.$cgst.'='.$sgst;
															$tax_type=$data['normalform'];
														}	
														 if(isset($zipData['ByAir']) && !empty($zipData['ByAir']))
														 {
															$courierBasePriceWithZipper = 0;
															$courierBasePriceNoZipper = 0;
															if(isset($courierBasePriceQuantityWise[$quantity])){
																$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
																$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
															}
															$this->query(" $pricesql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$zipData['courierCharge']."',actual_courier_price = '".$zipData['actual_courier_price']."',fuel_charge='".$courierChargeBaseZipper['fuel_charge']."',service_tax='".$courierChargeBaseZipper['service_tax']."',handling_charge='".$courierChargeBaseZipper['handling_charge']."',transport_price = '0',spout_transport_price='0',total_price = '".$zipData['ByAir']['totalPriceByAir']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."', gress_price = '".$gressPriceByAir."',gress_per = '".$gress_qtywise_per_air."', customer_gress_price = '".$customerGressPrice."',tax_name='".$final_tax_name."'");
														 }
														if(isset($zipData['BySea']) && !empty($zipData['BySea']))
														{
															$this->query(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$originaltransportPerPouch."',spout_transport_price='".$spout_transport_by_sea."',total_price = '".$zipData['BySea']['totalPriceBySea']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."',igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."', gress_price = '".$gressPriceBySea."',gress_per = '".$gress_qtywise_per_sea."', customer_gress_price = '".$customerGressPrice."',tax_name='".$final_tax_name."'");
														}													 
														if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup']))
														{
															$this->query(" $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0',spout_transport_price='0',total_price = '".$zipData['ByPickup']['totalPriceByPickup']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."', gress_price = '".$gressPriceByPickup."',gress_per = '".$gress_qtywise_per_pickup."', customer_gress_price = '".$customerGressPrice."',tax_name='".$final_tax_name."'");
														}		
												}
											}
										}
									}
								//if($_SESSION['ADMIN_LOGIN_SWISS']=='44'  && $_SESSION['LOGIN_USER_TYPE']=='4')
									 // printr($test);
									
									//BASE PRICE
									$inkDefaultPrice = $this->getInkPrice1($data['make']);
									$inkSolventDefaultPrice = $this->getInkSolventPrice('',0,$data['make']);
									$printingEffectDefaultPrice = $this->getPrintingEffectPrice($printing_effect);
									$adhesiveDefaultPrice = $this->getAdhesivePriceCpp('','',0,$data['make'],3,0);
									$adhesiveSolventDefaultPrice = $this->getAdhesivePriceCpp('','',0,$data['make'],5,2);
									$cppAdhesiveDefaultPrice = $this->getAdhesivePriceCpp('','',0,'',3,1);
									$transportWidthDefaultPrice = $this->getDefaultTransportWidthPrice($post_width,$gusset);
									$transportHeightDefaultPrice = $this->getDefaulttransportHeightPrice($post_height);
									$cylinderVendorDefaultPrice = $this->getCylinderVendorPrice($data['country_id']);
									//insert base price at a time product quotaion add than taht time real price. use for history
										
										$this->query("INSERT INTO ".DB_PREFIX."new_multi_product_quotation_base_price SET product_quotation_id = '".(int)$productQuatiationId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$originalperpouchpackingprice."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
									//INSERT DATA FOR LAYER WISE
									if(isset($setQueryData) && !empty($setQueryData)){
										foreach($setQueryData as $key=>$setquery){
										
											$setSql = "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_layer SET product_quotation_id = '".(int)$productQuatiationId."', ".$setquery;
											$this->query($setSql);
										}
									}
									return $multi_product_quotation_id;
								}
								
								
								
								
					}
					
					//custom order condition and calculation
					
					if($type=='O')
					{
						$custom_order_number = $this->generateCustomOderNumber($multi_custom_order_id);
						$sql =  "UPDATE  ".DB_PREFIX."multi_custom_order_id SET multi_custom_order_number = '".$custom_order_number."' WHERE multi_custom_order_id = '".$multi_custom_order_id."'";
						$this->query($sql);
									
						$sql =  "INSERT INTO ".DB_PREFIX."multi_custom_order SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect_id = '".(int)$printing_effect."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', volume = '".$post_volume."', layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."',packing_price = '".(float)$originalperpouchpackingprice."', valve_price = '".$valveBasePrice."', gress_percentage = '".$userGress."',gress_air = '".$userGressAir."',gress_sea = '".$userGressSea."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."', tool_price = '".(float)$tool_price."', use_device = '".getDevice()."', status = '0', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', currency = '".$currency."', currency_price = '".$currencyPrice."', customer_gress_percentage = '".$customer_gress."', product_note = '".$data['product_note']."', product_instruction = '".$data['product_special_instruction']."', shipment_country_id = '".$data['country_id']."', added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."', multi_custom_order_id = '".$multi_custom_order_id."'";
						$this->query($sql);
						$CustomOrderId = $this->getLastId();
						$emp_t_id = $_SESSION['LOGIN_USER_TYPE'];
						$emp_t_id = (int)$emp_t_id;				
						if(isset($CustomOrderId) && (int)$CustomOrderId > 0 ){
							//quotation currency
							if($customer_email && decode($data['sel_currency']) > 0 ){
								$selCurrecy = $this->getCurrencyInfoOld(decode($data['sel_currency']));
								if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
									$selCurrencyRate = $data['sel_currency_rate'];
								}else{
									$selCurrencyRate = $selCurrecy['price'];
								}
								$this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_currency SET custom_order_id = '".$CustomOrderId."', currency_id = '".$selCurrecy['currency_id']."', currency_code = '".$selCurrecy['currency_code']."', 	currency_rate = '".$selCurrencyRate."', currency_base_rate = '".$selCurrecy['price']."', date_added = NOW() ");
							}
							//INSERT QUOTATION QUANTITY TABLE 
						
							if(isset($quantityWiseData) && !empty($quantityWiseData)){
								foreach($quantityWiseData as $quantity=>$quantityValue){									
									$this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_quantity SET custom_order_id = '".$CustomOrderId."', quantity = '".$quantity."', wastage_adding = '".$quantityValue['addingWastage']."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit = '".$quantityValue['profit']."', total_weight_without_zipper = '".$quantityValue['totalWeightWithoutZipper']."', total_weight_with_zipper = '".$quantityValue['totalWeightWithZipper']."', date_added = NOW(),discount='".$data['discount']."'");
									$customOrderQuantityId = $this->getLastId();									
									//zipperData
									if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
									
										foreach($quantityValue['zipperData'] as $zipData){											
											$pricesql = "INSERT INTO ".DB_PREFIX."multi_custom_order_price SET custom_order_id = '".$CustomOrderId."', custom_order_quantity_id = '".$customOrderQuantityId."', zipper_txt = '".$zipData['zip_text']."', valve_txt = '".$zipData['valve_text']."', date_added = NOW(), zipper_price = '".$zipData['calculateZipperPrice']."', spout_txt = '".$zipData['spout_txt']."', spout_base_price = '".$zipData['spout_price']."', accessorie_txt = '".$zipData['accessorie_txt']."', make_pouch = '".(int)$data['make']."',accessorie_base_price = '".$zipData['accessorie_price']."',valve_price = '".$zipData['valve_price']."' , ";
																			
												$customerGressPrice = 0; 
												$gressPrice = 0;
												$gressPriceByPickup=0;
												$gressPriceByAir =0;
												$gressPriceBySea=0;
												$totalPricWithExcies =0;
												$totalPriceWithTax = 0;
												$tax_type='';
												$tax_percentage=0;
												$totalPriceForTax = 0;
												$excies = 0;$igst=$cgst=$sgst='0';
												if(isset($zipData['BySea']) && !empty($zipData['BySea']))
												{
													$tot_price=$zipData['BySea']['totalPriceBySea'];

												}
												if(isset($zipData['ByAir']) && !empty($zipData['ByAir'])){
													
													$tot_price=$zipData['ByAir']['totalPriceByAir'];
												}
												if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup']))
												{
													$tot_price=$zipData['ByPickup']['totalPriceByPickup'];
												}
												
												if($customer_gress > 0)
												{
													$customerGressPrice = $this->numberFormate((($tot_price * $customer_gress) / 100),"3");
												}
												if(isset($zipData['ByPickup']['userGress']) && $zipData['ByPickup']['userGress'] > 0){
													$gressPriceByPickup = $this->numberFormate((($tot_price * $zipData['ByPickup']['userGress']) / 100),"3");
												
												}
												 if(isset($zipData['ByAir']['userGress']) && $zipData['ByAir']['userGress'] > 0){
													$gressPriceByAir = $this->numberFormate((($tot_price * $zipData['ByAir']['userGress']) / 100),"3");
												
												}
												 if(isset($zipData['BySea']['userGress']) && $zipData['BySea']['userGress'] > 0){
													$gressPriceBySea = $this->numberFormate((($tot_price * $zipData['BySea']['userGress']) / 100),"3");
												}
												
												if(isset($taxation_data) && !empty($taxation_data))
												{
													$totalPriceForTax = $tot_price+$gressPrice+$customerGressPrice;
													if($data['discount'])
													{
														$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
													}
													
													$igst=$cgst=$sgst='0';
													if($data['normalform']=='Out Of Gujarat')
													{
														$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*$taxation_data['igst_percentage']/100);
														$igst =$taxation_data['igst_percentage'];
														//$tax_percentage=$taxation_data['igst_percentage'];
													}
													else
													{
														$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*($taxation_data['cgst_percentage']+$taxation_data['sgst_percentage'])/100);
														$cgst =$taxation_data['cgst_percentage'];
														$sgst =$taxation_data['sgst_percentage'];
														//$tax_percentage=$taxation_data['igst'];
													}
													$tax_type=$data['normalform'];
												}	
												 if(isset($zipData['ByAir']) && !empty($zipData['ByAir']))
												 {
													$courierBasePriceWithZipper = 0;
													$courierBasePriceNoZipper = 0;
													if(isset($courierBasePriceQuantityWise[$quantity])){
														$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
														$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
													}
													$this->query(" $pricesql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$zipData['courierCharge']."', transport_price = '0', total_price = '".$zipData['ByAir']['totalPriceByAir']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."', gress_price = '".$gressPriceByAir."', customer_gress_price = '".$customerGressPrice."',tax_name='".$final_tax_name."'");
												 }
												if(isset($zipData['BySea']) && !empty($zipData['BySea']))
												{
													$this->query(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$originaltransportPerPouch."',spout_transport_price='".$spout_transport_by_sea."', total_price = '".$zipData['BySea']['totalPriceBySea']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."', gress_price = '".$gressPriceBySea."', customer_gress_price = '".$customerGressPrice."',tax_name='".$final_tax_name."'");
												}													 
												if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup']))
												{
													$this->query(" $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$zipData['ByPickup']['totalPriceByPickup']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."',igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."', gress_price = '".$gressPriceByPickup."', customer_gress_price = '".$customerGressPrice."',tax_name='".$final_tax_name."'");
												}									
										}
									}
								}
							}
							
							//BASE PRICE
							$inkDefaultPrice = $this->getInkPrice1($data['make']);
							$inkSolventDefaultPrice = $this->getInkSolventPrice('',0,$data['make']);
							$printingEffectDefaultPrice = $this->getPrintingEffectPrice($printing_effect);
							$adhesiveDefaultPrice = $this->getAdhesivePriceCpp('','',0,$data['make'],3,0);
							$adhesiveSolventDefaultPrice = $this->getAdhesivePriceCpp('','',0,$data['make'],5,2);
							$cppAdhesiveDefaultPrice = $this->getAdhesivePriceCpp('','',0,'',3,1);
							$transportWidthDefaultPrice = $this->getDefaultTransportWidthPrice($post_width,$gusset);
							$transportHeightDefaultPrice = $this->getDefaulttransportHeightPrice($post_height);
							$cylinderVendorDefaultPrice = $this->getCylinderVendorPrice($data['country_id']);
							//inert base price at a time product quotaion add than taht time real price. use for history
							
							$this->query("INSERT INTO ".DB_PREFIX."multi_custom_order_base_price SET custom_order_id = '".(int)$CustomOrderId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$packingPerPouch."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");							
							
							//INSERT DATA FOR LAYER WISE
							if(isset($setQueryData) && !empty($setQueryData)){
								foreach($setQueryData as $key=>$setquery){								
									$setSql = "INSERT INTO ".DB_PREFIX."multi_custom_order_layer SET custom_order_id = '".(int)$CustomOrderId."', ".$setquery;
									$this->query($setSql);
								}
							}
							return $multi_custom_order_id;
						}
					}
				}
			}		
		}		
		
}	
	//rohit code start
	public function generateCustomOderNumber(){
		
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'multi_custom_order_id'");
		$count = $data->row['AUTO_INCREMENT'];
		$strpad = str_pad($count,5,'0',STR_PAD_LEFT);
		$number = 'CUST'.$strpad;
		return $number;
	}
	//rohit code end
	
	public function updateQuotationStatus($quotation_id,$status_value){
		
		$sql = "UPDATE " . DB_PREFIX . "new_multi_product_quotation SET status = '".$status_value."', date_modify = NOW() WHERE multi_product_quotation_id = '" .(int)$quotation_id. "'";
		$this->query($sql);
		
		$sql = "UPDATE " . DB_PREFIX . "new_multi_product_quotation_id SET status = '".$status_value."', date_modify = NOW() WHERE multi_product_quotation_id = '" .(int)$quotation_id. "'";
		$this->query($sql);
	}
	

	public function deleteQuotation($quotation_id){
		$sql = "SELECT product_quotation_id FROM " . DB_PREFIX ."multi_product_quotation WHERE multi_product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
		    foreach($data->rows as $row)
		    {
    		    //echo "DELETE  FROM " . DB_PREFIX . "multi_product_quotation  WHERE product_quotation_id='".$data->row['product_quotation_id']."'";
    			$this->query("DELETE  FROM " . DB_PREFIX . "new_multi_product_quotation  WHERE product_quotation_id='".$row['product_quotation_id']."'");
    			$this->query("DELETE  FROM " . DB_PREFIX . "new_multi_product_quotation_layer  WHERE product_quotation_id='".$row['product_quotation_id']."'");
    			$this->query("DELETE  FROM " . DB_PREFIX . "new_multi_product_quotation_price  WHERE product_quotation_id='".$row['product_quotation_id']."'");
    			$this->query("DELETE  FROM " . DB_PREFIX . "new_multi_product_quotation_quantity  WHERE product_quotation_id='".$row['product_quotation_id']."'");	
    			$this->query("DELETE  FROM " . DB_PREFIX . "new_multi_product_quotation_base_price  WHERE product_quotation_id='".$row['product_quotation_id']."'");
    			$this->query("DELETE  FROM " . DB_PREFIX . "new_multi_product_quotation_id  WHERE multi_product_quotation_id='".$row['product_quotation_id']."'");
		    }
		}
		
	}
	
	public function deleteProductQuotation($product_quotation_price_id){
		$cond='';
		$sql = "SELECT product_quotation_id,product_quotation_quantity_id  FROM " . DB_PREFIX ."new_multi_product_quotation_price WHERE product_quotation_price_id = '".(int)$product_quotation_price_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			$sql1 = "DELETE  FROM " . DB_PREFIX . "new_multi_product_quotation_price  WHERE product_quotation_price_id='".$product_quotation_price_id."'";
			$this->query($sql1);
		}
		$sql3 = "SELECT multi_product_quotation_id  FROM " . DB_PREFIX ."new_multi_product_quotation WHERE product_quotation_id = '".(int)$data->row['product_quotation_id']."'";
		$data3 = $this->query($sql3);
		$sql2 = "SELECT product_quotation_id  FROM " . DB_PREFIX ."new_multi_product_quotation WHERE multi_product_quotation_id = '".(int)$data3->row['multi_product_quotation_id']."'";
		$data2 = $this->query($sql2);
		foreach($data2->rows as $val)
		{
			$cond .= 'product_quotation_id ='.$val['product_quotation_id'] .' OR ';
		}	
		$cond=substr($cond,0,-3);
		$sql4 = "SELECT product_quotation_price_id  FROM " . DB_PREFIX ."new_multi_product_quotation_price WHERE ".$cond."";
		$data4 = $this->query($sql4);
		if($data4->num_rows==0){
			if($data2->num_rows){
			$this->deleteQuotation($data3->row['multi_product_quotation_id']);
			}
		}
	}
	
	public function upadteQuotation($quotation_id,$country_id=''){
		$this->query("UPDATE " . DB_PREFIX . "new_multi_product_quotation SET status = '1', quotation_status = '1', date_modify = NOW() WHERE multi_product_quotation_id = '" .(int)$quotation_id. "'");
		$this->query("UPDATE " . DB_PREFIX . "new_multi_product_quotation_id SET status = '1', quotation_status = '1', date_modify = NOW() WHERE multi_product_quotation_id = '" .(int)$quotation_id. "'");
		//send emial code
		//if($_SESSION['ADMIN_LOGIN_SWISS']!='37' && $_SESSION['LOGIN_USER_TYPE']!='2')
		    $this->sendQuotationEmail($quotation_id);
		if($country_id=='155')
			$this->sendQuotationEmailInSpanish($quotation_id);
		/*if($_SESSION['ADMIN_LOGIN_SWISS']=='37' && $_SESSION['LOGIN_USER_TYPE']=='2')
		    $this->sendQuotationEmailInItalian($quotation_id);*/
	}
	
	public function update_discount($quantity_id,$discount)
	{
		$this->query("UPDATE " . DB_PREFIX . "new_multi_product_quotation_quantity SET discount = ".$discount." WHERE product_quotation_quantity_id = '" .(int)$quantity_id. "'");
	}
	
	public function getQuotationPackingAndTransportDetails($quotation_id){
		$sql = "SELECT pqbp.packing_price,pq.spout_additional_packing_price,pqbp.transport_width_base_price,pqbp.transport_height_base_price FROM " . DB_PREFIX ."new_multi_product_quotation pq INNER JOIN " . DB_PREFIX ."new_multi_product_quotation_base_price pqbp ON (pq.product_quotation_id=pqbp.product_quotation_id) WHERE pq.product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}	
	}
	
	public function gettermsandconditions($user_id,$user_type_id){
		if($user_type_id == '4')
		{
		$sql = "SELECT ts.termsandconditions,ts.user_id,ts.user_type_id FROM termsandconditions ts WHERE ts.user_id = '".$user_id."' AND ts.user_type_id = '4'  AND ts.is_delete = '0' LIMIT 1";
		}
		else
		{
		$sql = "SELECT ts.termsandconditions,ts.user_id,ts.user_type_id,e.user_id FROM termsandconditions ts,employee e WHERE e.employee_id ='".$user_id."' AND ts.user_id = e.user_id AND ts.user_type_id = '4' AND ts.is_delete = '0' LIMIT 1";
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
	
	public function getQuotationCurrecy($selCurrencyId,$source){
			$data = $this->query("SELECT * FROM " . DB_PREFIX . "new_multi_product_quotation_currency WHERE product_quotation_currency_id ='".$selCurrencyId."' AND source = '".$source."' ");
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
	
	public function getcalculatePlusMinusQuantity($quantity,$product_id,$height,$width,$gusset,$type,$qty_type=''){
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		$size = 0;
		if(!empty($productGusset)){
			if(in_array('no_gusset_height',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);		
			}
			
			elseif(in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_height',$productGusset) && !in_array('side_gusset',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ($height * $width);
			}
			elseif(in_array('side_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('bottom_gusset',$productGusset))
			{
				$size = ((($gusset*2) + $height)* $width); 
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*1)+$height)*$width);
			}
			elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				$size = ((($gusset*3) + $height) * $width);
			}
		}
		if($qty_type=='')
		{
    		$qunatityRow = $this->query("SELECT product_quantity_id FROM " . DB_PREFIX . "product_quantity WHERE quantity = '".$quantity."'");
		    $quantity_id = $qunatityRow->row['product_quantity_id'];
		    $data = $this->query("SELECT plus_minus_quantity	FROM " . DB_PREFIX . "product_profit WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND size_from <= '".$size."' AND 	size_to >= '".$size."'");
		}
		else
		{
		    $data = $this->query("SELECT plus_minus_quantity FROM " . DB_PREFIX . "roll_quantity WHERE quantity = '".$quantity."' AND quantity_type = '".$qty_type."'");
		}

		if($data->num_rows){
			return $data->row['plus_minus_quantity'];
		}else{
			return false;
		}
	}
	
	public function getBaseCylinderPrice($currency_id,$user_admin_id=''){
		$sql = "SELECT cb.price FROM " . DB_PREFIX . "currency_setting cs, product_cylinder_base_price as cb , country as c WHERE cb.currency_code='".$currency_id."' AND cb.currency_code = c.currency_code AND c.country_id =cs.country_code";
		//$sql = "SELECT ib.default_cyli_base_price as price FROM " . DB_PREFIX . "international_branch as ib WHERE ib.international_branch_id = ".$user_admin_id;
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['price'];	
		}else{
			return false;
		}
	}
	public function getEmpAdminId($user_id)
	{
		$sql ="SELECT user_id FROM " . DB_PREFIX ."employee WHERE employee_id = '".(int)$user_id."'";
		$data = $this->query($sql);
		return $data->row['user_id'];
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
	
	public function sendQuotationEmail($quotation_id,$toEmail = '',$setQuotationCurrencyId='',$secondary_curr='',$sec_currency_rate=''){		
		$getData = ' product_quotation_id,pq.layer, pq.added_by_user_id, pq.added_by_user_type_id, customer_name, customer_gress_percentage, customer_email, shipment_country_id, multi_quotation_number,pq.multi_product_quotation_id, quotation_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,quantity_type,layer,added_by_country_id, currency, currency_id, currency_price, pq.date_added, cylinder_price,tool_price, customer_email,pq.status,discount,customer_gress_percentage,gress_percentage,gress_air,gress_sea';
		$data = $this->getQuotation($quotation_id,$getData);
		
		//if($_SESSION['ADMIN_LOGIN_SWISS']=='1'  && $_SESSION['LOGIN_USER_TYPE']=='1')
		    //printr($data);die;
		foreach($data as $dat)
	   {
		 $qdata= $this->getQuotationQuantity($dat['product_quotation_id']);
		 if($qdata!='')
		  $quantityData[] =$qdata;
	   }
	   //printr($quantityData);die; 
	    $str=$dat['product_name'];
	    if($dat['product_id'] == '6')
	    {
	        $str=$dat['product_name'];
	        $dat['product_name']='To Be Supplied in Rolls';
	       
	    }
		  //151 online id and offline id is 160
		$menu_id = $this->getMenuPermission(151,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$user_admin_id=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);

		//[kinjal] for pratik sir to go gress price mail on 28-4-2017
		$menu_id_pratik_sir = $this->getMenuPermission(151,'19','2',1);
		//END [kinjal]
		

		$menu_admin_permission='';
		if($_SESSION['LOGIN_USER_TYPE']!='4')
		{
			$menu_admin_permission=$this->getMenuPermission(151,$user_admin_id['user_id'],4);
		}
		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$str),0,3));		
		$sub =$dat['multi_quotation_number'] .' - '.ucwords($dat['customer_name']).' - custom printed '.$first;
		$gussetvalue='';$s='';$sub2='';$m_k='';$roll_detail='';
		foreach($quantityData as $k=>$qty_data)
		{
			foreach($qty_data as $tag=>$qty)
			{
				foreach($qty as $q=>$arr)
				{
					
				/*	if($dat['product_id'] == '6')
					{
					    foreach($arr as $soption){
					        
					        printr($arr);die;
        					  foreach($soption[0]['quantity_option'] as $squantityKey=>$squantity){
        					       if($squantityKey=='Total Pieces Per Kg' || $squantityKey=='Total Pieces')
        					            $roll_detail='<br/><span style="font-size:13px"><small class="text-muted">'.$squantityKey.' : '.$squantity.'</small></span >';
        					   }
					    }
					}
					*/
					if($arr[0]['gusset'] == '')
					{
						$gussetval = 'No Gusset';
					}
					else
					{
						if($dat['product_id'] == '7')
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm gusset }';
						}
						elseif($arr[0]['gusset'] == '0')
						{
							 $gussetval = ' ';	
						}
						else
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.
							$arr[0]['gusset'].'mm gusset}';
						}
					}
					if($dat['product_id'] != '10')
					{
						if($arr[0]['volume']!='')
						{ 
							if($s!=$qty)
							{
								$sub2.=$arr[0]['volume'].' , ';
								$s=$qty;
							}
							$gussetvalue.=$arr[0]['volume'].' , ';
						}
					}
					if($gussetvalue=='')
						$gussetvalue=' W : '.(int)$arr[0]['width'].'mm  x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval;
					if($arr[0]['volume']=='')
						$arr[0]['volume']='Custom';
					
					if($dat['product_id'] != '10')
						$key='W : '.(int)$arr[0]['width'].'mm x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval.' ['.$arr[0]['volume'].']';
					else
						$key='W : '.(int)$arr[0]['width'].'mm x '.'H : '.(int)$arr[0]['height'].'mm '.$gussetval;
						$m_k[$arr[0]['product_quotation_quantity_id']]='';
						
					$make=$arr[0]['make'];
					$make_id=$arr[0]['make_id'];
				
					$new_data[$arr[0]['text']][$key.' ('.$arr[0]['layer'].') '][$q.'('.$arr[0]['product_quotation_quantity_id'].')'][$tag][]=$arr[0];
					if($dat['product_id'] != '10')
					{
						$val123 ='';
						if($arr[0]['valve_txt']=='With Valve')
						    $val123 = $arr[0]['valve_txt'];
						$sub1= ' '.$arr[0]['zipper_txt'].' '.$val123.''.$sub2; 
					}
					else
						$sub1= $sub2; 
				}
			}	
			$n[$tag][]=array('width'=>$arr[0]['width'],'height'=>$arr[0]['height'],'gusset'=>$arr[0]['gusset'],'volume'=>$arr[0]['volume'],'make'=>$arr[0]['make']);
			$t=$tag;
		}
		$addedByInfo = $this->getUser($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
		
		$sub1= substr($sub1,0,-3);
		$sub=$sub.$sub1;
		
	
		$html='';$tax_str='';$html_gress=$html_pra='';
	
		if($addedByInfo)
		{
			$pqcquery='';
			$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
			
			    
			if($selCurrency)
				$pqcquery = " product_quotation_currency_id = '".$selCurrency['product_quotation_currency_id']."', ";
			$currency_rate[]=array('currency_rate'=>1,'user'=>0);
			if($dat['customer_email'] != '' || $toEmail != '')
				$currency_rate[]=array('currency_rate'=>($selCurrency['currency_rate']!='')?$selCurrency['currency_rate']:1,'user'=>1);
			else
				$currency_rate[]=array('currency_rate'=>1,'user'=>2);
			
			
				
			$html .='<table border="0px">';
			if($secondary_curr!='')
			{
				if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['added_by_user_type_id'] =='2' ) && $addedByInfo['user_id']=='10' )
				    $html.='';
				else
				    $html .='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp;'.$secondary_curr.'</b></div><br><br>';
			}
			$html_gress .='<table border="0px">';
			if($secondary_curr!='')
			{
	            if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['added_by_user_type_id'] =='2' ) && $addedByInfo['user_id']=='10' )				    
	                $html_gress .='';
				else
				    $html_gress .='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp;'.$secondary_curr.'</b></div><br><br>';
			}
		
			foreach($currency_rate as $cr)
			{
				$gettermsandconditions = $this->gettermsandconditions($dat['added_by_user_id'],$dat['added_by_user_type_id']);
				$shippingCountry = $this->getCountry($dat['shipment_country_id']);
				$html .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';$laser_para=$acc_para='0';
				foreach($new_data as $key=>$value)
				{
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{//printr($records[0]['laser_id']);die;
								if($user_admin_id['country_id']=='14')
    							    $num_format =$num = 2;
    							 else
    							     $num_format =$num = 3;
								
							
								if($k == "air") 
								{
									$color = "red";	
								}elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
							if($selCurrency)
							{
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code'],$user_admin_id['international_branch_id']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$cylinder_price = $cylinder_base_price;	
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
									}
									else
										$cylinder_price = $cylinder_currency_price;	
								}
								else
									$cylinder_price = $cylinder_currency_price;	
								
							 }
							 else
							 {
								$selCurrency['currency_code'] = $dat['currency'];
								$cylinder_price = $records[0]['cylinder_price'];
								$tool_price = $records[0]['tool_price'];
							 }
							 $cylinder_price_withtax = $records[0]['cylinder_price_withtax'];
							 $tool_price_withtax = $records[0]['tool_price_withtax'];
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
							
							if($records[0]['laser_id']=='2')
							    $laser_para=1;
							
							if($records[0]['accessorie_txt_corner']=='Round Corners')
							    $acc_para=1;
							
							$qty_type = ''; $bag_types='bags'; $bag_type='bag';
							if($dat['product_id'] == '6'){
							    $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
							
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
						
							
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							$newPirce = $newPirce/$qty;
							$taxvalue='';
							
							if($records[0]['tax_type']=='With In Gujarat')
								$tax_name_data='With In Gujarat';
							else
								$tax_name_data=$records[0]['tax_type'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 '.$bag_type.' including of all taxes.';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 '.$bag_type.' including of all taxes.';											

								}
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$taxvalue = '';
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$final = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvalue = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given )  = <b style="color:blue">'.$dat['currency'].' '.round($final,'3').' per 1 '.$bag_type.' </b>including of all taxes.';

								}
								else
								{
									$final = $newPirce + ($newPirce *($records[0]['igst'])/100);
									$taxvalue =' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given )  = <b style="color:blue">'.$dat['currency'].' '.round($final,'3').' per 1 '.$bag_type.' </b>including of all taxes.';

								}
							}
							$design='';
							if($dat['shipment_country_id'] == '111')
							    $design = 'in each design ';
							if($dat['product_id'] != '10')
								$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.' '.$design;
							else
								$bag='';
								
							if($k=='air')
							{
								if($dat['shipment_country_id'] == '42')
								{
									$txt_tarnsport=' Delivery Rush Order : 4 to 5 weeks approx';
								}
								else
								{
									$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'].'( Express Delivery )';
								}
								if($user_admin_id['country_id']=='14' || $user_admin_id['country_id']=='189')
								    $txt_tarnsport='( Express Delivery )';
							}
							if($k=='sea')
							{
								if($dat['shipment_country_id'] == '42')
								{
									$txt_tarnsport=' Delivery Normal Order : 8 to 10 weeks approx';
								}
								else
								{
									$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port'.'( Normal Delivery )';
								}
								if($user_admin_id['country_id']=='14' || $user_admin_id['country_id']=='189')
								    $txt_tarnsport='( Normal Delivery )';
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
						
							
							
							if(strpos($size,'Custom')!==false)
							{
								$normal_val = $this->numberFormate($newPirce ,$num_format);
								$extra_profit = $normal_val;
							}
							else
							{
								$normal_val = $this->numberFormate($newPirce ,$num_format);
								$extra_profit = $normal_val;
							}
						
							if($secondary_curr!='')
							{
								
								$selCurrency_currency_code=$secondary_curr;
								$extra_profit=$extra_profit*$sec_currency_rate;
								if($addedByInfo['country_id']=='189')
								    $extra_profit=(($extra_profit*$addedByInfo['gst'])/100)+$extra_profit;
							}
							else
							{
								$selCurrency_currency_code=$selCurrency['currency_code'];
								$extra_profit=$extra_profit;
							
							}
							
							
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency_currency_code.' '.$this->numberFormate($extra_profit ,$num).' per 1 '.$bag_type.' '.$taxvalue.'<b> { For '.$qty.' '.$bag_types.' '.$design.''.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span><br><br></td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.' '.$design;
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b>'.$dat['currency'].' '.$this->numberFormate($extra_profit ,$num).' per 1 '.$bag_type.' '.$taxvaluedis.'<b> { For '.$qty.' '.$bag_types.' '.$design.''.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
							}
							
						}
    					$pr = 'pouch';	
    					if($dat['product_id'] == '6')
    					    $pr = 'Rolls';	
    						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Make up of '.$pr.'  : </b> Custom Printed '.$dat['product_name'].' '.$roll_detail;
    					
    					if($dat['product_id'] != '10' && $dat['product_id'] != '6')
    						$m.=str_replace('<br>',' ',$records[0]['text']); // $records[0]['email_text']
    					$mat='';
    					if($dat['shipment_country_id'] != '111')
    					{
        					if($dat['product_id'] == '6')
        					    $m.='&nbsp;&nbsp;Make Up Of Rolls - '.$records[0]['make'].'</td></tr>';
        					else
        					    $m.='&nbsp;&nbsp;Make Pouch - '.$records[0]['make'].'</td></tr>';
    					}
                        else
                        {//kinjal made this condition on (18-4-2019) order by prashant sir
                            if($records[0]['make']=='9')
                                $mat='&nbsp;&nbsp;<b>[100% Fully Recyclable Material]</b>';
                        }
					}
					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Size : </b>'.$size.'</td></tr> ';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Material : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					if(isset($materialData) && !empty($materialData))
					{
						$materialStr = '';
						/*if($records[0]['make_id']=='6')
						{
								$j='1';
								for($gi=0;$gi<count($materialData);$gi++)
								{
									if($materialData[$gi]['material_id']!='16' && $records[0]['make_id']=='6')
									{
										if($j=='2')
										{
											
											$materialData[$gi]['material_name'] = 'oxo-Biodegradable PE';
											$materialData[$gi]['material_thickness'] = '80';
										}
										$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
									}
									$j++;
								}
						}
						else
						{*/
							for($gi=0;$gi<count($materialData);$gi++)
							{
								if($materialData[$gi]['material_id'] == '8')
									$materialStr .= '6 to '.(int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
								else
									$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
							}
						//}
						$html .= ''.substr($materialStr,0,-3).' '.$mat.'</td>';
					}
					
					$materialData='';
					$html .= '</tr>';
					//[kinjal] commented on 17-1-2018 told by shishirsir
					if($dat['shipment_country_id'] != '111')
					    $html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Shipment Country : </b>'.$shippingCountry['country_name'].'</td></tr>';
					$html .=$m;
					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Printing Effect : </b>'.$records[0]['printing_effect'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>';
					
					if($records[0]['gusset_printing_type']!='' && $dat['product_id'] != '6')
					  $html .='<br/>Gusset Printing Type : '.$records[0]['gusset_printing_type'];
					  
					 $html .='</td></tr>';
					 
					if($addedByInfo['country_id']==155)
						$txt='[Your Client Price]';
					else
						$txt='';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html .=$pmq;
					$html .=$gress;
					$html .=$pmq_gress;
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$cylinder_price=$cylinder_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$cylinder_price=$cylinder_price;
						}
						if($dat['shipment_country_id'] == '42')
						    $html .= '<tr><td colspan="2"><b style="color:red">* Remarks : To make it convenient and cost effective you can purchase some quantity in rush order and balance by normal order. </b></td></tr>';
						
						
						$html .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder price: </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($cylinder_price),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
						if($cylinder_price_withtax != '0.000')
							$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder Price With Tax: </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($cylinder_price_withtax),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
					if(isset($tool_price) && $tool_price>0.000)
					{
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$tool_price=$tool_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$tool_price=$tool_price;
						}
						
						$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Extra Tool price : </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price),$num_format).'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</td></tr>';
						if($tool_price_withtax != '0.000')
							$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Extra Tool Price With Tax: </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($tool_price_withtax),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html .= '</table>';
				if($user_admin_id['country_id']=='14')
				   $html .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b> All Above Price is excluding GST.</div>'; 
				//elseif($addedByInfo['country_id']=='189' && $secondary_curr!='')
				    //$html .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b> All PRICES ARE INCLUSIVE OF 12% VAT.</div>';
				if($laser_para=='1')
				{
				    $html .= '<div><b>With Laser Scored Bags</b><br>Advantage of laser scoring is the pouches gets cut in a straight line so while opening the bag the zipper does not get damaged by opening the pouch.<br>It is easy to open by Small Children & Elderly persons. After opening the bag design looks very premium due to bags are cut very precisely.</div>';
				}
				if($acc_para=='1')
				{
				    $html .= '<div><b>All four Rounded Corners</b><br>Advantage of all four rounded corner in pouches, is they look more premium and artisan then the pointed corner ones.<br>There is always a great chance that the pointed corner in pouches can get injury to humans, as the pointed edges are very sharp like knife.</div>';
				}
				$html .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
				
	
				//added by jaya 7-4-2016
				
				
				$html_gress .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				foreach($new_data as $key=>$value)
				{
				    
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{
								 	
								 	if($user_admin_id['country_id']=='14')
								        $num_format = $num = 2;
								     else
								         $num_format = $num = 3;
								
								if($k == "air") 
								{
									$color = "red";	
								}elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
							if($selCurrency)
							{
							    
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code'],$user_admin_id['international_branch_id']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$gress_cylinder_price = ($records[0]['cyli_gress_price'] / $cr['currency_rate']);
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$userInfo = $this->getUserInfo($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
										$cylinder_price = $cylinder_base_price;
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
										
										$gress_cylinder_price=$cylinder_price;
									}
									else
										$cylinder_price = $cylinder_currency_price;	
								}
								else
									$cylinder_price = $cylinder_currency_price;	
							 }
							 else
							 {
								$selCurrency['currency_code'] = $dat['currency'];
								$cylinder_price = $records[0]['cylinder_price'];
								$gress_cylinder_price = $records[0]['cyli_gress_price'];
								$tool_price = $records[0]['tool_price'];
							 }
							 $cylinder_price_withtax = $records[0]['cylinder_price_withtax'];
							 $tool_price_withtax = $records[0]['tool_price_withtax'];
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
								
							if($records[0]['laser_id']=='2')
							    $laser_para=1;
							if($records[0]['accessorie_txt_corner']=='Round Corners')
							    $acc_para=1;
							    
							$qty_type = ''; $bag_types='bags'; $bag_type='bag';
							if($dat['product_id'] == '6'){
							    $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
							    
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
						
							
							if(!empty($menu_id) || !empty($menu_admin_permission))
							{
							
						
									if($dat['shipment_country_id']==91)
										$txt='[Your Purchase Price]';
									else
										$txt='';
									$gress = '<tr><td colspan="2" >&nbsp;</td></tr><tr valign="top"><td width="60" colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Percentage Prices:'.$txt.'</b></td></tr>';								
									$newPircegress = ((($records[0]['totalPrice'] - $records[0]['customerGressPrice'] - $records[0]['gress_price']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
									$newPircegress = $newPircegress/$qty;
									$taxvaluegress='';
									
									if($records[0]['tax_type']=='With In Gujarat')
										$tax_name_data='With In Gujarat';
									else
										$tax_name_data=$records[0]['tax_type'];
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										$newPircegress =$this->numberFormate(($newPircegress-($newPircegress*$records[0]['discount']/100)),"3");
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' per 1 '.$bag_type.' including of all taxes.';	
										}
										else
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['igst'].'% IGST + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' per 1 '.$bag_type.' including of all taxes.';	

										}
									}						
									if($dat['shipment_country_id'] == '111')
									{
										$taxvaluegress = '';
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
											$taxvaluegress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' per 1 '.$bag_type.' including of all taxes.';

										}
										else
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
											$taxvaluegress = ' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' per 1 '.$bag_type.' including of all taxes.';

										}
										
									}
									if($k=='air')
									{
										$gp=$records[0]['gress_per'];
										if($dat['shipment_country_id'] == '42')
										{
											$txt_tarnsport=' Delivery Rush Order : 4 to 5 weeks approx';
										}
										else
										{
											$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'].'( Express Delivery )';
										}
										if($user_admin_id['country_id']=='14' || $user_admin_id['country_id']=='189')
										    $txt_tarnsport='( Express Delivery )';
									}
									if($k=='sea')
									{
										$gp=$records[0]['gress_per'];
										if($dat['shipment_country_id'] == '42')
										{
											$txt_tarnsport=' Delivery Normal Order : 8 to 10 weeks approx';
										}
										else
										{
											$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port'.'( Normal Delivery )';
										}
										if($user_admin_id['country_id']=='14' || $user_admin_id['country_id']=='189')
										    $txt_tarnsport='( Normal Delivery )';
									}
									if($k=='pickup')
									{
										$gp=$records[0]['gress_per'];
										$tax_str='Transportation cost NOT included.';
										$txt_tarnsport='By Pickup ';
									}
									
									$design='';
        							if($dat['shipment_country_id'] == '111')
        							    $design = 'in each design ';
        							if($dat['product_id'] != '10')
        								$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.' '.$design;
        							else
        								$bag='';

									if(strpos($size,'Custom')!==false)
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,$num_format);
										$newextra_profit = $newPriceGress_val;
										
									}
									else
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,$num_format);
										$newextra_profit = $newPriceGress_val;
									}
									if($secondary_curr!='')
									{
										$selCurrency_currency_code=$secondary_curr;
										$newextra_profit=$newextra_profit*$sec_currency_rate;
										if($addedByInfo['country_id']=='189')
								            $newextra_profit=(($newextra_profit*$addedByInfo['gst'])/100)+$newextra_profit;
									}
									else
									{
										$selCurrency_currency_code=$selCurrency['currency_code'];
										$newextra_profit=$newextra_profit;
								
									}
									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency_currency_code.' '.$this->numberFormate($newextra_profit ,$num).' per 1 '.$bag_type.' '.$taxvaluegress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span><br><br></td></tr>';	
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										if($dat['product_id'] != '10')
											$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.' '.$design;
										else
											$bag='';
										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b></td><td>'.$dat['currency'].' '.$this->numberFormate($newextra_profit ,$num).' per 1 '.$bag_type.' '.$taxvaluedisgress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';
									}
							}
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							$newPirce = $newPirce/$qty;
							$taxvalue='';
							
							if($records[0]['tax_type']=='With In Gujarat')
								$tax_name_data='With In Gujarat';
							else
								$tax_name_data=$records[0]['tax_type'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' +  ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 '.$bag_type.' including of all taxes.';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst']+$records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST'.' + ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 '.$bag_type.' including of all taxes.';											

								}
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$taxvalue = '';
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' +  ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 '.$bag_type.' including of all taxes.';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst']+$records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST'.' + ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' per 1 '.$bag_type.' including of all taxes.';											

								}
							}
							
							if($dat['product_id'] != '10')
								$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.' '.$design;
							else
								$bag='';
							if($k=='air')
							{
								if($dat['shipment_country_id'] == '42')
								{
									$txt_tarnsport=' Delivery Rush Order : 4 to 5 weeks approx';
								}
								else
								{
									$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'].'( Express Delivery )';
								}
								if($user_admin_id['country_id']=='14' || $user_admin_id['country_id']=='189')
								    $txt_tarnsport='( Express Delivery )';
							}
							if($k=='sea')
							{
								if($dat['shipment_country_id'] == '42')
								{
									$txt_tarnsport=' Delivery Normal Order : 8 to 10 weeks approx';
								}
								else
								{
									$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port'.'( Normal Delivery )';
								}
								if($user_admin_id['country_id']=='14' || $user_admin_id['country_id']=='189')
								    $txt_tarnsport='( Normal Delivery )';
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
						
							if(strpos($size,'Custom')!==false)
							{
								
								$normal_val = $this->numberFormate($newPirce ,$num_format);
								    
								$extra_profit = $normal_val;
							}
							else
							{
							    $normal_val = $this->numberFormate($newPirce ,$num_format);
								$extra_profit = $normal_val;
							}
						
							if($secondary_curr!='')
							{
								
								$selCurrency_currency_code=$secondary_curr;
								$extra_profit=$extra_profit*$sec_currency_rate;
								if($addedByInfo['country_id']=='189')
								            $extra_profit=(($extra_profit*$addedByInfo['gst'])/100)+$extra_profit;
							}
							else
							{
								$selCurrency_currency_code=$selCurrency['currency_code'];
								$extra_profit=$extra_profit;
							
							}

							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency_currency_code.' '.$this->numberFormate($extra_profit ,$num).' per 1 '.$bag_type.' '.$taxvalue.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.' '.$design;
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b>'.$dat['currency'].' '.$this->numberFormate($extra_profit ,$num).' per 1 '.$bag_type.' '.$taxvaluedis.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
							}
							
						}
						
						$pr = 'pouch';	
    					if($dat['product_id'] == '6')
    					    $pr = 'Rolls';
						
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Make up of '.$pr.'  : </b> Custom Printed '.$dat['product_name'].' ';
    					
    					if($dat['product_id'] != '10' && $dat['product_id'] != '6')
    						$m.=str_replace('<br>',' ',$records[0]['text']); // $records[0]['email_text']
    					
    					if($dat['shipment_country_id'] != '111')
    					{
        					if($dat['product_id'] == '6')
        					    $m.='&nbsp;&nbsp;Make Up Of Rolls - '.$records[0]['make'].'</td></tr>';
        					else
        					    $m.='&nbsp;&nbsp;Make Pouch - '.$records[0]['make'].'</td></tr>';
    					}
					}
					$html_gress .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Size : </b>'.$size.'</td></tr> ';
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Material : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					if(isset($materialData) && !empty($materialData))
					{
						$materialStr = '';
						/*if($records[0]['make_id']=='6')
						{
								$j='1';
								for($gi=0;$gi<count($materialData);$gi++)
								{
									if($materialData[$gi]['material_id']!='16' && $records[0]['make_id']=='6')
									{
										if($j=='2')
										{
											$materialData[$gi]['material_name'] = 'oxo-Biodegradable PE';
											$materialData[$gi]['material_thickness'] = '80';
										}
										$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
									}
									$j++;
								}
						}
						else
						{*/
							for($gi=0;$gi<count($materialData);$gi++)
							{
								$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
							}
						//}
						$html_gress .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html_gress .= '</tr>';
					//[kinjal] commented on 17-1-2018 told by shishirsir
					    $html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Shipment Country : </b>'.$shippingCountry['country_name'].'</td></tr>';
				
					$html_gress .=$m;
					$html_gress .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Printing Effect : </b>'.$records[0]['printing_effect'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>';
				
					if($records[0]['gusset_printing_type']!='' && $dat['product_id'] != '6')
					  $html_gress .='<br/>Gusset Printing Type : '.$records[0]['gusset_printing_type'];
					  
					 $html_gress .='</td></tr>';
					
					if($addedByInfo['country_id']==155)
						$txt='[Your Client Price]';
					else
						$txt='';
					
					
					
					
					    $html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
				    	$html_gress .=$pmq;
				    	$html_gress .=$gress;
    					$html_gress .=$pmq_gress;
				
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$cylinder_price=$cylinder_price*$sec_currency_rate;
							$gress_cylinder_price = $gress_cylinder_price * $sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$cylinder_price=$cylinder_price;
							$gress_cylinder_price = $gress_cylinder_price;
						}
						
						if((int)$cylinder_price==(int)$gress_cylinder_price){
						    $gress_cyli = $gress_cylinder_price;}
						else{
						    $gress_cyli = $cylinder_price-$gress_cylinder_price;}
						
						if($dat['shipment_country_id'] == '42')
						    $html_gress .= '<tr><td colspan="2"><b style="color:red">* Remarks : To make it convenient and cost effective you can purchase some quantity in rush order and balance by normal order. </b></td></tr>';
						
						$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder price: </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($cylinder_price),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b><br>
					                   <b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder Gress price: </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($gress_cyli),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
						if($cylinder_price_withtax != '0.000')
							$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder Price With Tax : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($cylinder_price_withtax),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
					
					if(isset($tool_price) && $tool_price>0.000)
					{
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$tool_price=$tool_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$tool_price=$tool_price;
						}
						
						$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Extra Tool price : </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price),$num_format).'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</td></tr>';
						if($tool_price_withtax != '0.000')
							$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Extra Tool Price With Tax: </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($tool_price_withtax),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
					}
					$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					}
					$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html_gress .= '</table>';
				if($user_admin_id['country_id']=='14')
				   $html_gress .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b> All Above Price is excluding GST.</div>';
				//elseif($addedByInfo['country_id']=='189' && $secondary_curr!='')
				    //$html_gress .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b> All PRICES ARE INCLUSIVE OF 12% VAT.</div>';
				if($laser_para=='1')
				{
				    $html_gress .= '<div><b>With Laser Scored Bags</b><br>Advantage of laser scoring is the pouches gets cut in a straight line so while opening the bag the zipper does not get damaged by opening the pouch. <br>It is easy to open by Small Children & Elderly persons. After opening the bag design looks very premium due to bags are cut very precisely.</div>';
				}
				if($acc_para=='1')
				{
				    $html_gress .= '<div><b>All four Rounded Corners</b><br>Advantage of all four rounded corner in pouches, is they look more premium and artisan then the pointed corner ones.<br>There is always a great chance that the pointed corner in pouches can get injury to humans, as the pointed edges are very sharp like knife.</div>';
				}
				$html_gress .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b><br/>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
				
				//added by kinjal on 15-2-2018
		        	$html_pra .= '<style> table, th, td </style>';
				$i=0;$c=0;$pmq_gress='';$gress='';$gp='';
				foreach($new_data as $key=>$value)
				{
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{
								 	if($user_admin_id['country_id']=='14')
								        $num_format = $num = 2;
								     else
								         $num_format = $num = 3;
								
								if($k == "air") 
								{
									$color = "red";	
								}elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
							if($selCurrency)
							{
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code'],$user_admin_id['international_branch_id']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$gress_cylinder_price = ($records[0]['cyli_gress_price'] / $cr['currency_rate']);
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$userInfo = $this->getUserInfo($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
										$cylinder_price = $cylinder_base_price;
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
										$gress_cylinder_price=$cylinder_price;
									}
									else
										$cylinder_price = $cylinder_currency_price;	
								}
								else
									$cylinder_price = $cylinder_currency_price;	
							 }
							 else
							 {
								$selCurrency['currency_code'] = $dat['currency'];
								$cylinder_price = $records[0]['cylinder_price'];
								$gress_cylinder_price = $records[0]['cyli_gress_price'];
								$tool_price = $records[0]['tool_price'];
							 }
							 $cylinder_price_withtax = $records[0]['cylinder_price_withtax'];
							 $tool_price_withtax = $records[0]['tool_price_withtax'];
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
								
							if($records[0]['laser_id']=='2')
							    $laser_para=1;	
							
							if($records[0]['accessorie_txt_corner']=='Round Corners')
							    $acc_para=1;
							    
							$qty_type = ''; $bag_types='bags'; $bag_type='bag';
							if($dat['product_id'] == '6'){
							    $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
							    
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
						
						
								
									if($dat['shipment_country_id']==91)
										$txt='[Your Purchase Price]';
									else
										$txt='';
									$gress = '<tr><td colspan="2" >&nbsp;</td></tr><tr valign="top"><td width="60" colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Percentage Prices:'.$txt.'</b></td></tr>';								
									$newPircegress = ((($records[0]['totalPrice'] - $records[0]['customerGressPrice'] - $records[0]['gress_price']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
									$newPircegress = $newPircegress/$qty;
									$taxvaluegress='';
									
									if($records[0]['tax_type']=='With In Gujarat')
										$tax_name_data='With In Gujarat';
									else
										$tax_name_data=$records[0]['tax_type'];
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										$newPircegress =$this->numberFormate(($newPircegress-($newPircegress*$records[0]['discount']/100)),"3");
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' per 1 '.$bag_type.' including of all taxes.';	
										}
										else
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['igst'].'% IGST + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' per 1 '.$bag_type.' including of all taxes.';	

										}
									}						
									if($dat['shipment_country_id'] == '111')
									{
										$taxvaluegress = '';
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
											$taxvaluegress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' per 1 '.$bag_type.' including of all taxes.';

										}
										else
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
											$taxvaluegress = ' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' per 1 '.$bag_type.' including of all taxes.';

										}
										
									}
									if($k=='air')
									{
									
										$gp=$records[0]['gress_per'];
										if($dat['shipment_country_id'] == '42')
										{
											$txt_tarnsport=' Delivery Rush Order : 4 to 5 weeks approx';
										}
										else
										{
											$txt_tarnsport='Door delivery by AIR in '.$shippingCountry['country_name'].'( Express Delivery )';
										}
										if($user_admin_id['country_id']=='14' || $user_admin_id['country_id']=='189')
										    $txt_tarnsport='( Express Delivery )';
									}
									if($k=='sea')
									{
										$gp=$records[0]['gress_per'];
										if($dat['shipment_country_id'] == '42')
										{
											$txt_tarnsport=' Delivery Normal Order : 8 to 10 weeks approx';
										}
										else
										{
											$txt_tarnsport='CIF '.$shippingCountry['country_name'].' sea port'.'( Normal Delivery )';
										}
										if($user_admin_id['country_id']=='14' || $user_admin_id['country_id']=='189')
										    $txt_tarnsport='( Normal Delivery )';
									}
									if($k=='pickup')
									{
										$gp=$records[0]['gress_per'];
										$tax_str='Transportation cost NOT included.';
										$txt_tarnsport='By Pickup ';
									}
									if($dat['product_id'] != '10')
										$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.' '.$design;
									else
										$bag='';
										
								    
									if(strpos($size,'Custom')!==false)
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,$num_format);
										$newextra_profit = $newPriceGress_val;
										
									}
									else
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,$num_format);
										$newextra_profit = $newPriceGress_val;
									}
									if($secondary_curr!='')
									{
										$selCurrency_currency_code=$secondary_curr;
										$newextra_profit=$newextra_profit*$sec_currency_rate;
										if($addedByInfo['country_id']=='189')
								            $newextra_profit=(($newextra_profit*$addedByInfo['gst'])/100)+$newextra_profit;
									}
									else
									{
										$selCurrency_currency_code=$selCurrency['currency_code'];
										$newextra_profit=$newextra_profit;
								
									}
									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency_currency_code.' '.$this->numberFormate($newextra_profit ,$num).' per 1 '.$bag_type.' '.$taxvaluegress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span><br><br></td></tr>';	
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										if($dat['product_id'] != '10')
											$bag='plus or minus '.$plus_minus_quantity.' '.$bag_types.' '.$design;
										else
											$bag='';
										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b></td><td>'.$dat['currency'].' '.$this->numberFormate($newextra_profit ,$num).' per 1 '.$bag_type.' '.$taxvaluedisgress.'<b> { For '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';
									}
								
						}
    					$pr = 'pouch';	
    					if($dat['product_id'] == '6')
    					    $pr = 'Rolls';
    					    
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Make up of '.$pr.'  : </b> Custom Printed '.$dat['product_name'].' ';
    					if($dat['product_id'] != '10' && $dat['product_id'] != '6')
    						$m.=str_replace('<br>',' ',$records[0]['text']);
    					
    					if($dat['shipment_country_id'] != '111')
    					{
        					if($dat['product_id'] == '6')
        					    $m.='&nbsp;&nbsp;Make Up Of Rolls - '.$records[0]['make'].'</td></tr>';
        					else
        					    $m.='&nbsp;&nbsp;Make Pouch - '.$records[0]['make'].'</td></tr>';
    					}
					}
					$html_pra .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Size : </b>'.$size.'</td></tr> ';
					$html_pra .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Material : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					if(isset($materialData) && !empty($materialData))
					{
						$materialStr = '';
						/*if($records[0]['make_id']=='6')
						{
								$j='1';
								for($gi=0;$gi<count($materialData);$gi++)
								{
									if($materialData[$gi]['material_id']!='16' && $records[0]['make_id']=='6')
									{
										if($j=='2')
										{
											$materialData[$gi]['material_name'] = 'oxo-Biodegradable PE';
											$materialData[$gi]['material_thickness'] = '80';
										}
										$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
									}
									$j++;
								}
						}
						else
						{*/
							for($gi=0;$gi<count($materialData);$gi++)
							{
								$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
							}
						//}
						$html_pra .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html_pra .= '</tr>';
					//[kinjal] commented on 17-1-2018 told by shishirsir
					    $html_pra .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Shipment Country : </b>'.$shippingCountry['country_name'].'</td></tr>';
				
					$html_pra .=$m;
					$html_pra .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Printing Effect : </b>'.$records[0]['printing_effect'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>';
					if($records[0]['gusset_printing_type']!='' && $dat['product_id'] != '6')
					  $html_pra .='<br/>Gusset Printing Type : '.$records[0]['gusset_printing_type'];
					  
					 $html_pra .='</td></tr>';

				    	$html_pra .=$gress;
    					$html_pra .=$pmq_gress;
				
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$cylinder_price=$cylinder_price*$sec_currency_rate;
							$gress_cylinder_price = $gress_cylinder_price * $sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$cylinder_price=$cylinder_price;
							$gress_cylinder_price = $gress_cylinder_price;
						}
						
						if((int)$cylinder_price==(int)$gress_cylinder_price){
						    $gress_cyli = $gress_cylinder_price;}
						else{
						    $gress_cyli = $cylinder_price-$gress_cylinder_price;}
						
						if($dat['shipment_country_id'] == '42')
						    $html_pra .= '<tr><td colspan="2"><b style="color:red">* Remarks : To make it convenient and cost effective you can purchase some quantity in rush order and balance by normal order. </b></td></tr>';
						
						$html_pra .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder Gress price: </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($gress_cyli),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b>
					</td></tr>';
						if($cylinder_price_withtax != '0.000' )
							$html_pra .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder Price With Tax : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($cylinder_price_withtax),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
					
					if(isset($tool_price) && $tool_price>0.000)
					{
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$tool_price=$tool_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$tool_price=$tool_price;
						}
						
						$html_pra .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Extra Tool price : </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price),$num_format).'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</td></tr>';
						if($tool_price_withtax != '0.000')
							$html_pra .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Extra Tool Price With Tax: </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($tool_price_withtax),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
					}
					$html_pra .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					}
					$html_pra .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html_pra .= '</table>';
				if($user_admin_id['country_id']=='14')
				   $html_pra .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b> All Above Price is excluding GST.</div>'; 
				//elseif($addedByInfo['country_id']=='189' && $secondary_curr!='')
				    //$html_pra .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b> All PRICES ARE INCLUSIVE OF 12% VAT.</div>';
				if($laser_para=='1')
				{
				    $html_pra .= '<div><b>With Laser Scored Bags</b><br>Advantage of laser scoring is the pouches gets cut in a straight line so while opening the bag the zipper does not get damaged by opening the pouch.<br> It is easy to open by Small Children & Elderly persons. After opening the bag design looks very premium due to bags are cut very precisely.</div>';
				}
				if($acc_para=='1')
				{
				    $html_pra .= '<div><b>All four Rounded Corners</b><br>Advantage of all four rounded corner in pouches, is they look more premium and artisan then the pointed corner ones.<br>There is always a great chance that the pointed corner in pouches can get injury to humans, as the pointed edges are very sharp like knife.</div>';
				}
				$html_pra .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b><br/>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
				
		        
				//[kinjal] : change index name on (9-4-2016)
				if($cr['user']==1 || $cr['user']==2)
				{
					
					if($cr['user']==1)
					{
						
						$new_html=$html_gress;
						$email_temp[]=array('html'=>$new_html,'email'=>($dat['customer_email'])?$dat['customer_email']:ADMIN_EMAIL);
					}
					if($cr['user']==2)
					{
						if(!empty($menu_id))
						{
							$new_html=$html_gress;
					    
						}
						else
						{
							$new_html=$html;
						
						}
						
						$email_temp[]=array('html'=>$new_html,'email'=>$addedByInfo['email']);
						
					}
				}
				if($toEmail!='' && $cr['user']!=0)
				{
					if(!empty($menu_id))
					{
						$new_html=$html_gress;
					
					}
					else
					{
						$new_html=$html;
					
					}	
					$email_temp[]=array('html'=>$new_html,'email'=>$toEmail);
					
				}
				if($cr['user']==0)
				{
				
					$new_html=$html_gress;
					$email_temp[]=array('html'=>$new_html,'email'=>ADMIN_EMAIL);
					//[kinjal] for pratik sir to go gress price mail on 28-4-2017
					    $email_temp[]=array('html'=>$html_pra,'email'=>$menu_id_pratik_sir[0]['email']);
					    $email_temp[]=array('html'=>$html_gress,'email'=>'swisspack1@gmail.com');
					//END [kinjal]
					if($data[0]['added_by_user_type_id']==2)
					{
						$admininfo = $this->getUser($addedByInfo['user_id'],'4');	
						$email_temp[]=array('html'=>$new_html,'email'=>$admininfo['email']);
						
						if($admininfo['email1']!='' && $admin_email['email1']!=$addedByinfo['email'])
						    $email_temp[]=array('html'=>$new_html,'email'=>$admininfo['email1']);
					}
					
				}
			
				
		
				$html='<table border="0px">';
				if($secondary_curr!='')
				{
					if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['added_by_user_type_id'] =='2' ) && $addedByInfo['user_id']=='10' )
					    $html.='';
					else
					    $html .='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp;'.$secondary_curr.'</b></div><br><br>';
				}
				$html_gress='<table border="0px">';
				if($secondary_curr!='')
				{
					if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['added_by_user_type_id'] =='2' ) && $addedByInfo['user_id']=='10' )
					    $html_gress.='';
					else
					    $html_gress.='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp;'.$secondary_curr.'</b></div><br><br>';
				}
			}
		
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(1); 
			$formEmail = $addedByInfo['email'];							
			$firstTimeemial = 0;
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
			$path = HTTP_SERVER."template/product_quotation.html";
			$output = file_get_contents($path);  
			$search  = array('{tag:header}','{tag:details}');
			$signature = 'Thanks.';
			if($addedByInfo['email_signature'])
				$signature = nl2br($addedByInfo['email_signature']);
			
			foreach($email_temp as $val)
			{
				if($toEmail == '')
				{
					$toEmail = $formEmail;
					$firstTimeemial = 1;
				}				
				$subject = $sub;
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
			
				    //if($_SESSION['ADMIN_LOGIN_SWISS']!='44'  && $_SESSION['LOGIN_USER_TYPE']!='4')
				         //send_email($val['email'],$formEmail,$subject,$message,'');
				    //else
				        //send_email('erp@swisspac.net',$formEmail,$subject,$message,'');//printr($message);printr($val['email']);//send_email('erp@swisspac.net',$formEmail,$subject,$message,'');
				        printr($message);printr($val['email']);
				        
				 
			}
		   
		}
		/*if($_SESSION['ADMIN_LOGIN_SWISS']=='44' && $_SESSION['LOGIN_USER_TYPE']=='4')
		    */die;
		$qstr_customer = '';
		if($dat['customer_email'] != '' && $firstTimeemial == 1)
		{
			$customer_email = $dat['customer_email'];
			$qstr_customer = " sent_customer = 1, customer_email = '".$customer_email."', ";
		}
		$qstr = '';
		if($firstTimeemial)
			$qstr = 'sent_admin = 1,';
			
		$this->query("INSERT INTO `" . DB_PREFIX . "new_multi_product_quotation_email_history` SET multi_product_quotation_id = '".$dat['multi_product_quotation_id']."', customer_name = '".addslashes($dat['customer_name'])."', user_type_id = '" .$dat['added_by_user_type_id']. "', user_id = '" .$dat['added_by_user_id']. "', to_email = '".$toEmail."', from_email = '" .$formEmail. "',   $qstr_customer admin_default_email='".ADMIN_EMAIL."', status = '1', $qstr $pqcquery date_added = NOW()");
	
	}
	
	public function getEmailHistories($quotation_id)
	{
		$data = $this->query("SELECT qc.currency_code,qc.sec_curr,qc.sec_curr_rate,qe.date_added, qc.source, qc.currency_rate, qe.to_email FROM " . DB_PREFIX . "new_multi_product_quotation_email_history qe RIGHT JOIN new_multi_product_quotation_currency qc ON(qe.product_quotation_currency_id = qc.product_quotation_currency_id) WHERE qe.multi_product_quotation_id ='".$quotation_id."' ORDER BY qc.date_added DESC ");
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	
	public function getSpoutWeight($spout_name)
	{
	
		$data = $this->query("SELECT weight FROM " . DB_PREFIX . "product_spout WHERE spout_name = '".$spout_name."' ");
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
	
	public function getColors(){
		$sql = "SELECT * FROM  mailer_bag_color WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY color";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
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
	public function getMenuPermission($menu_id,$user_id,$user_type_id,$n=0)
	{
		$cond ='add_permission LIKE "%'.$menu_id.'%" AND edit_permission LIKE "%'.$menu_id.'%" AND delete_permission LIKE "%'.$menu_id.'%" AND view_permission LIKE "%'.$menu_id.'%"';
		$sql = "SELECT email,user_name FROM " . DB_PREFIX ."account_master WHERE ".$cond." AND user_type_id = '".$user_type_id."' AND user_id ='".$user_id."'";
		
		
		$data = $this->query($sql);
		return $data->rows;
	}
	//[kinjal] - 11-4-2017 made this fun for got effect multi by value in matt shiny effect
	public function getPrintingEffectMultiBy($effect_id){
		$sql = "SELECT multi_by FROM " . DB_PREFIX . "printing_effect WHERE printing_effect_id = '".(int)$effect_id."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $this->numberFormate($data->row['multi_by'],"3");
		}else{
			return 0;
		}
	}
	
	public function getCustmul()
	{
	    $sql = "SELECT * FROM " . DB_PREFIX . "custom_multiplier_detail";
	    $data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//change function  sonu 11-4-2017
	public function getCustomerDetail($customer_name)

	{

		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
        $str='';
		if($user_type_id == 2){

			$parentdata = $this->query("SELECT user_type_id,user_id FROM " . DB_PREFIX . "employee WHERE employee_id = '".$user_id."' ");

			$userEmployee = $this->getUserEmployeeIds($parentdata->row['user_type_id'],$parentdata->row['user_id']);

			$set_user_id = $parentdata->row['user_id'];

			$set_user_type_id = $parentdata->row['user_type_id'];
			if($userEmployee)
			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			
			$sql= "SELECT aa.address_book_id, aa.company_name,cs.email_1,cs.country,cs.company_address_id FROM address_book_master as aa ". "LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) WHERE aa.is_delete=0 "
                    . "AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id ='".$set_user_id."' AND aa.user_type_id='".$set_user_type_id."') $str) LIMIT 15";
	

		}
		else if($user_type_id == 4)
		{

			$userEmployee = $this->getUserEmployeeIds($user_type_id,$user_id);

			$set_user_id = $user_id;

			$set_user_type_id = $user_type_id;
			
			if($userEmployee)
			    $str = ' OR ( aa.user_id IN ('.$userEmployee.') AND aa.user_type_id = 2 )';
			
			 $sql= "SELECT aa.address_book_id, aa.company_name,cs.email_1,cs.country,cs.company_address_id FROM address_book_master as aa ". "LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) WHERE aa.is_delete=0 "
                    . "AND aa.company_name LIKE '%".$customer_name."%' AND ((aa.user_id ='".$set_user_id."' AND aa.user_type_id='".$set_user_type_id."') $str) LIMIT 15";
		}

		else

		{
			$sql= "SELECT aa.address_book_id, aa.company_name,cs.email_1,cs.country,cs.company_address_id FROM address_book_master as aa ". "LEFT JOIN `company_address` as cs ON (aa.address_book_id=cs.`address_book_id`) WHERE aa.is_delete=0 "
                    . "AND aa.company_name LIKE '%".$customer_name."%' LIMIT 15";

		}

		
		
		$data = $this->query($sql);
		
		if($data->num_rows){

			return $data->rows;

		}else{

			return false;

		}		

	}
	//12-4-2017 sonu add
	 public function getLastIdAddress() {
        $sql = "SELECT address_book_id FROM  address_book_master ORDER BY address_book_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row;
        } else {
            return false;
        }
    }

	//[kinjal] made on 12-7-2017 to 13-7-2017
	public function clone_mutli_quo($multi_product_quotation_id_old)
	{
		$sql="SELECT * FROM new_multi_product_quotation_id WHERE multi_product_quotation_id='".$multi_product_quotation_id_old."'";
		$data = $this->query($sql);
		
		$sql1_insert =  "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_id SET  address_book_id = '".$data->row['address_book_id']."', date_added = NOW(),date_modify = NOW(), added_by_user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."', added_by_user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."',status='".$data->row['status']."',quotation_status='".$data->row['quotation_status']."'";		
		$this->query($sql1_insert);
		$multi_product_quotation_id = $this->getLastId();
		
		$userCountry = $this->getUserCountry($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
		if($userCountry){
			$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
		}else{
			$countryCode='IN';
		}

		
		
		$newQuotaionNumber = $this->generateQuotationNumber($multi_product_quotation_id);
		$quotation_number = $countryCode.$newQuotaionNumber;
		
		$sql6 =  "UPDATE  ".DB_PREFIX."new_multi_product_quotation_id SET multi_quotation_number = '".$quotation_number."' WHERE multi_product_quotation_id = '".$multi_product_quotation_id."'";
		$this->query($sql6);
		
		
		$sql1="SELECT * FROM new_multi_product_quotation WHERE multi_product_quotation_id='".$multi_product_quotation_id_old."'";
		$data1 = $this->query($sql1);
		foreach($data1->rows as $row)
		{
			$sql2_insert =  "INSERT INTO ".DB_PREFIX."new_multi_product_quotation SET product_id = '".$row['product_id']."', product_name = '".$row['product_name']."', printing_option = '".$row['printing_option']."', printing_effect_id = '".$row['printing_effect_id']."', printing_effect = '".$row['printing_effect']."', height = '".$row['height']."', width = '".$row['width']."', gusset = '".$row['gusset']."', volume = '".$row['volume']."',plastic_color = '".$row['plastic_color']."' ,mailer_per_kg_price='".$row['mailer_per_kg_price']."',mailer_bag_price='".$row['mailer_bag_price']."',strip_thickness='".$row['strip_thickness']."',layer = '".$row['layer']."', ink_price = '".$row['ink_price']."', ink_solvent_price = '".$row['ink_solvent_price']."', printing_effect_price = '".$row['printing_effect_price']."', adhesive_price = '".$row['adhesive_price']."', cpp_adhesive = '".$row['cpp_adhesive']."', adhesive_solvent_price = '".$row['adhesive_solvent_price']."', native_price = '".$row['native_price']."',packing_price = '".$row['packing_price']."',spout_additional_packing_price='".$row['spout_additional_packing_price']."', valve_price = '".$row['valve_price']."', gress_percentage = '".$row['gress_percentage']."',gress_air = '".$row['gress_air']."',gress_sea = '".$row['gress_sea']."', cylinder_price = '".$row['cylinder_price']."',cylinder_price_withtax='".$row['cylinder_price_withtax']."',tool_price = '".$row['tool_price']."',tool_price_withtax='".$row['tool_price_withtax']."',swiss_cylinder_rate = '".$row['swiss_cylinder_rate']."', swiss_tool_rate = '".$row['swiss_tool_rate']."', use_device = '".getDevice()."', status = '1', date_added = NOW(), added_by_country_id = '".$row['added_by_country_id']."', currency = '".$row['currency']."', currency_price = '".$row['currency_price']."', customer_name = '".addslashes($row['customer_name'])."', customer_email = '".$row['customer_email']."',email = '".$row['email']."', customer_gress_percentage = '".$row['customer_gress_percentage']."', shipment_country_id = '".$row['shipment_country_id']."', added_by_user_id = '".$row['added_by_user_id']."', added_by_user_type_id = '".$row['added_by_user_type_id']."',admin_user_id='".$row['admin_user_id']."', multi_product_quotation_id = '".$multi_product_quotation_id ."',size_id='".$row['size_id']."',extra_profit='".$row['extra_profit']."',ink_multi_by = '".$row['ink_multi_by']."',cust_ink_mul_by = '".$row['cust_ink_mul_by']."' , cust_adhesive_mul_by='".$row['cust_adhesive_mul_by']."',quotation_status='".$row['quotation_status']."'";
			$this->query($sql2_insert);
			$product_quotation_id = $this->getLastId();
			
			$sql2="SELECT * FROM new_multi_product_quotation_quantity WHERE product_quotation_id='".$row['product_quotation_id']."'";
			$data2 = $this->query($sql2);
			
			
			foreach($data2->rows as $r)
			{
				$this->query("INSERT INTO ".DB_PREFIX."new_multi_product_quotation_quantity SET product_quotation_id = '".$product_quotation_id."', quantity = '".$r['quantity']."', wastage_adding = '".$r['wastage_adding']."', wastage_base_price = '".$r['wastage_base_price']."', wastage = '".$r['wastage']."', native_price_per_bag = '".$r['native_price_per_bag']."', profit = '".$r['profit']."', total_weight_without_zipper = '".$r['total_weight_without_zipper']."', total_weight_with_zipper = '".$r['total_weight_with_zipper']."', date_added = NOW(),discount='".$r['discount']."'");
				$product_quotation_quantity_id=$this->getLastId();
				
				$sql3="SELECT * FROM new_multi_product_quotation_price WHERE product_quotation_id='".$row['product_quotation_id']."' AND product_quotation_quantity_id='".$r['product_quotation_quantity_id']."'";
				$data3= $this->query($sql3);
				foreach($data3->rows as $row3)
				{
					$pricesql = "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_price SET product_quotation_id = '".$product_quotation_id."', product_quotation_quantity_id = '".$product_quotation_quantity_id."', zipper_txt = '".$row3['zipper_txt']."',gusset_printing_type='".$row3['gusset_printing_type']."',valve_txt = '".$row3['valve_txt']."', date_added = NOW(), zipper_price= '".$row3['zipper_price']."', spout_txt= '".$row3['spout_txt']."', spout_base_price = '".$row3['spout_base_price']."', accessorie_txt = '".$row3['accessorie_txt']."', make_pouch = '".$row3['make_pouch']."',spout_pouch_type='".$row3['spout_pouch_type']."',accessorie_base_price = '".$row3['accessorie_base_price']."', accessorie_txt_corner = '".$row3['accessorie_txt_corner']."', accessorie_base_price_corner = '".$row3['accessorie_base_price_corner']."',transport_type='".$row3['transport_type']."',transport_base_price='".$row3['transport_base_price']."',transport_price='".$row3['transport_price']."',spout_transport_price='".$row3['spout_transport_price']."',courier_base_price_withzipper='".$row3['courier_base_price_withzipper']."',courier_base_price_nozipper='".$row3['courier_base_price_nozipper']."',courier_charge='".$row3['courier_charge']."',actual_courier_price='".$row3['actual_courier_price']."',fuel_charge='".$row3['fuel_charge']."',service_tax='".$row3['service_tax']."',handling_charge='".$row3['handling_charge']."',valve_price='".$row3['valve_price']."',total_price='".$row3['total_price']."',total_price_with_excies='".$row3['total_price_with_excies']."',total_price_with_tax='".$row3['total_price_with_tax']."',tax_type='".$row3['tax_type']."',tax_name='".$row3['tax_name']."',tax_percentage='".$row3['tax_percentage']."',excies='".$row3['excies']."',igst='".$row3['igst']."',cgst='".$row3['cgst']."',sgst='".$row3['sgst']."',gress_price='".$row3['gress_price']."',customer_gress_price='".$row3['customer_gress_price']."',total_charge='".$row3['total_charge']."'";
					$datapricesql= $this->query($pricesql);
				}
			}
			$sql4="SELECT * FROM new_multi_product_quotation_base_price WHERE product_quotation_id='".$row['product_quotation_id']."' ";
			$data4= $this->query($sql4);
			foreach($data4->rows as $row4)
			{
				$base = "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_base_price SET product_quotation_id = '".$product_quotation_id."',ink_base_price = '".$row4['ink_base_price']."',ink_solvent_base_price = '".$row4['ink_solvent_base_price']."',printing_effect_base_price = '".$row4['printing_effect_base_price']."',adhesive_base_price = '".$row4['adhesive_base_price']."',cpp_adhesive_base_price = '".$row4['cpp_adhesive_base_price']."',adhesive_solvent_base_price = '".$row4['adhesive_solvent_base_price']."',packing_price = '".$row4['packing_price']."',spout_packing_price = '".$row4['spout_packing_price']."',spout_courier_price = '".$row4['spout_courier_price']."',transport_width_base_price = '".$row4['transport_width_base_price']."',transport_height_base_price = '".$row4['transport_height_base_price']."',packing_base_price = '".$row4['packing_base_price']."',option_base_price = '".$row4['option_base_price']."',cylinder_base_price = '".$row4['cylinder_base_price']."',cylinder_vendor_base_price = '".$row4['cylinder_vendor_base_price']."',cylinder_currency_base_price = '".$row4['cylinder_currency_base_price']."',fuel_surcharge = '".$row4['fuel_surcharge']."',service_tax = '".$row4['service_tax']."',handling_charge = '".$row4['handling_charge']."',status = '".$row4['status']."',date_added = NOW(),date_modify = NOW(),is_delete = '".$row4['is_delete']."'";
				$database= $this->query($base);
			}
			
			$sql5="SELECT * FROM new_multi_product_quotation_layer WHERE product_quotation_id='".$row['product_quotation_id']."'";
			$data5= $this->query($sql5);
			foreach($data5->rows as $row2)
			{
				$layers = "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_layer SET product_quotation_id = '".$product_quotation_id."',layer = '".$row2['layer']."',material_id = '".$row2['material_id']."',material_gsm = '".$row2['material_gsm']."',material_thickness = '".$row2['material_thickness']."',material_price = '".$row2['material_price']."',material_name = '".$row2['material_name']."',layer_wise_gsmthickness = '".$row2['layer_wise_gsmthickness']."',layer_wise_price = '".$row2['layer_wise_price']."',date_added = NOW()";
				$datalayers= $this->query($layers);
			}
			
		}
	}
	
	public function sendQuotationEmailInSpanish($quotation_id,$toEmail = '',$setQuotationCurrencyId='',$secondary_curr='',$sec_currency_rate=''){		
				
		$getData = ' product_quotation_id,pq.layer, pq.added_by_user_id, pq.added_by_user_type_id, customer_name, customer_gress_percentage, customer_email, shipment_country_id, multi_quotation_number,pq.multi_product_quotation_id, quotation_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,quantity_type,layer,added_by_country_id, currency, currency_id, currency_price, pq.date_added, cylinder_price,tool_price, customer_email,pq.status,discount,customer_gress_percentage,gress_percentage,gress_air,gress_sea ';
		$data = $this->getQuotation($quotation_id,$getData);
		foreach($data as $dat)
	    {
			 $qdata= $this->getQuotationQuantity($dat['product_quotation_id']);
			 if($qdata!='')
			  $quantityData[] =$qdata;
	    }
		$product_nm_new =$this->getProduct($dat['product_id']);
		
	    $str=$product_nm_new['product_name_spanish'];
		
		$str=$dat['product_name'];
	    if($dat['product_id'] == '6')
	    {
	        $str=$dat['product_name'];
	        $dat['product_name']='Entregado en Rollo';
	       
	    }
		
		$menu_id = $this->getMenuPermission(151,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$user_admin_id=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		
		
		
		$menu_admin_permission='';
		if($_SESSION['LOGIN_USER_TYPE']!='4')
		{
			$menu_admin_permission=$this->getMenuPermission(151,$user_admin_id['user_id'],4);
			
		}
		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$str),0,3));		
		$sub =$dat['multi_quotation_number'] .' - '.ucwords($dat['customer_name']).' - custom printed '.$first;
		$gussetvalue='';$s='';$sub2='';$m_k='';
	
		foreach($quantityData as $k=>$qty_data)
		{
			foreach($qty_data as $tag=>$qty)
			{
				foreach($qty as $q=>$arr)
				{
					if($arr[0]['gusset'] == '')
					{
						$gussetval = 'sin fuelle';
					}
					else
					{
						if($dat['product_id'] == '7')
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm Fuelle de fondo }';
						}
						elseif($arr[0]['gusset'] == '0')
						{
							 $gussetval = ' ';	
						}
						else
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.
							$arr[0]['gusset'].'mm Fuelle de fondo}';
						}
					}
					if($dat['product_id'] != '10')
					{
						if($arr[0]['volume']!='')
						{ 
							if($s!=$qty)
							{
								$sub2.=$arr[0]['volume'].' , ';
								$s=$qty;
							}
							$gussetvalue.=$arr[0]['volume'].' , ';
						}
					}
					if($gussetvalue=='')
						$gussetvalue=' Ancho : '.(int)$arr[0]['width'].'mm  x '.'Alto : '.(int)$arr[0]['height'].'mm '.$gussetval;
					if($arr[0]['volume']=='')
						$arr[0]['volume']='Personalizado';
					
					if($dat['product_id'] != '10')
						$key='Ancho : '.(int)$arr[0]['width'].'mm x '.'Alto : '.(int)$arr[0]['height'].'mm '.$gussetval.' ['.$arr[0]['volume'].']';
					else
						$key='Ancho : '.(int)$arr[0]['width'].'mm x '.'Alto : '.(int)$arr[0]['height'].'mm '.$gussetval;
						$m_k[$arr[0]['product_quotation_quantity_id']]='';
						
					$make=$arr[0]['make_name_spanish'];
					$make_id=$arr[0]['make_id'];
				
					$new_data[$arr[0]['text']][$key.' ('.$arr[0]['layer'].') '][$q.'('.$arr[0]['product_quotation_quantity_id'].')'][$tag][]=$arr[0];
					if($dat['product_id'] != '10')
					{
						$zip_nm = $this->query("SELECT zipper_name_spanish FROM product_zipper WHERE zipper_name = '".$arr[0]['zipper_txt']."'");
						if($arr[0]['valve_txt']=='No Valve')
							$arr[0]['valve_txt'] = 'Sin Válvula';
						else	
							$arr[0]['valve_txt'] = 'Con válvula';
						$sub1= ' '.$zip_nm->row['zipper_name_spanish'].' '.$arr[0]['valve_txt'].''.$sub2; 
					}
					else
						$sub1= $sub2; 
				}
			}	
			$n[$tag][]=array('width'=>$arr[0]['width'],'height'=>$arr[0]['height'],'gusset'=>$arr[0]['gusset'],'volume'=>$arr[0]['volume'],'make'=>$arr[0]['make']);
			$t=$tag;
		}
		$addedByInfo = $this->getUser($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
		
		$sub1= substr($sub1,0,-3);
		$sub=$sub.$sub1;
		$html='';$tax_str='';$html_gress='';
		
		if($addedByInfo)
		{
			$pqcquery='';
			$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
			if($selCurrency)
				$pqcquery = " product_quotation_currency_id = '".$selCurrency['product_quotation_currency_id']."', ";
			$currency_rate[]=array('currency_rate'=>1,'user'=>0);
			if($dat['customer_email'] != '' || $toEmail != '')
				$currency_rate[]=array('currency_rate'=>($selCurrency['currency_rate']!='')?$selCurrency['currency_rate']:1,'user'=>1);
			else
				$currency_rate[]=array('currency_rate'=>1,'user'=>2);
				
			$html .='<table border="0px">';
			if($secondary_curr!='')
			{
				if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['added_by_user_type_id'] =='2' ) && $addedByInfo['user_id']=='10' )
				    $html.='';
				else
				    $html .='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp; '.$secondary_curr.'</b></div><br><br>';//Mexican Peso
			}
			$html_gress .='<table border="0px">';
			if($secondary_curr!='')
			{
				if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['added_by_user_type_id'] =='2' ) && $addedByInfo['user_id']=='10' )
				    $html_gress.='';
				else
				    $html_gress .='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp; '.$secondary_curr.'</b></div><br><br>';
			}
			foreach($currency_rate as $cr)
			{
				$gettermsandconditions = $this->gettermsandconditions($dat['added_by_user_id'],$dat['added_by_user_type_id']);
				$shippingCountry = $this->getCountry($dat['shipment_country_id']);
				
				$html .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				foreach($new_data as $key=>$value)
				{
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{
									if($secondary_curr!='')
        							{
        								
        								$selCurrency_currency_code=$secondary_curr;
        							}
        							else
        							{
        								$selCurrency_currency_code=$selCurrency['currency_code'];

        							}
        							$qty_type = ''; $bag_types='bolsas'; $bag_type='bolsa';
        							if($dat['product_id'] == '6'){
        							    $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
									if($secondary_curr!='')
									{
									    $con = 'México';
                					    $pri_m = 'pesos por 1 '.$bag_type;
                					    $pri_me = 'pesos';
                					    $pri=$pri_o='';
                					    $pri_mex  = 'pesos Precio';
                					    $num_format = 2;
									}
									else
									{
									    $con = $shippingCountry['country_name'];
                					    $pri = $selCurrency_currency_code;
                					    $pri_o = 'por 1 '.$bag_type;
                					    $pri_m=$pri_me=$pri_mex='';
                					    $num_format = 3;
									}
									
								
								$zip_nm = $this->query("SELECT zipper_name_spanish FROM product_zipper WHERE zipper_name = '".$records[0]['zipper_txt']."'");
								$spt_nm = $this->query("SELECT spout_name_spanish FROM product_spout WHERE spout_name = '".$records[0]['spout_txt']."'");
								if($records[0]['accessorie_txt']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_spanish FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt']."'");
									$records[0]['accessorie_txt'] = $acc_nm->row['product_accessorie_name_spanish'];
								}
								if($records[0]['accessorie_txt_corner']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_spanish FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt_corner']."'");
									$records[0]['accessorie_txt_corner'] = $acc_nm->row['product_accessorie_name_spanish'];
								}
								
								if($records[0]['valve_txt']=='No Valve')
									$records[0]['valve_txt'] = 'Sin Válvula';
								else	
									$records[0]['valve_txt'] = 'Con válvula';
								
								$eff_nm = $this->query("SELECT 	effect_name_spanish FROM " . DB_PREFIX . "printing_effect WHERE effect_name = '".$records[0]['printing_effect']."' ");
								$records[0]['printing_effect'] = $eff_nm->row['effect_name_spanish'];
								
								if($records[0]['laser_type']=='No Laser Scoring')
								    $laser_type ='Sin Incisión Laser';
							    else
							        $laser_type ='Con Incisión Laser';
								
								$tamper = '';
        						if($records[0]['slider_place']!='')
        						{
        						    
            						if($records[0]['slider_place'] == 'Slider Zipper On Top Of Pouch')
            						{
            						    $records[0]['slider_place'] = 'Zipper de Seguridad para niños';
            						    $tamper = $records[0]['slider_place'].' '.$records[0]['tamperevident'];
            						}
            						else
            						{
            						    $records[0]['slider_place'] = 'Zipper en la parte superior de lo bolsa';
            						    $tamper = $records[0]['slider_place'];
            						}
        						}
								
								$records[0]['email_text'] = $zip_nm->row['zipper_name_spanish'].' , '.$laser_type.' , '.$records[0]['valve_txt'].'<br> '.$spt_nm->row['spout_name_spanish'].' , '.$records[0]['accessorie_txt'] .' , '.$records[0]['accessorie_txt_corner'].' , '.$tamper;

								if($k == "air") 
								{
									$color = "red";	
								}elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
							if($selCurrency)
							{
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code'],$user_admin_id['international_branch_id']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$cylinder_price = $cylinder_base_price;	
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
									}
									else
										$cylinder_price = $cylinder_currency_price;	
								}
								else
									$cylinder_price = $cylinder_currency_price;	
							 }
							 else
							 {
								$selCurrency['currency_code'] = $dat['currency'];
								$cylinder_price = $records[0]['cylinder_price'];
								$tool_price = $records[0]['tool_price'];
							 }
							 $cylinder_price_withtax = $records[0]['cylinder_price_withtax'];
							 $tool_price_withtax = $records[0]['tool_price_withtax'];
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
								
							
							    
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
						
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							$newPirce = $newPirce/$qty;
							$taxvalue='';
							
							if($records[0]['tax_type']=='With In Gujarat')
								$tax_name_data='With In Gujarat';
							else
								$tax_name_data=$records[0]['tax_type'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
								
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' (Incluye IVA).';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' (Incluye IVA).';											

								}
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$taxvalue = '';
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$final = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvalue = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given )  = <b style="color:blue">'.$dat['currency'].' '.round($final,'3').' per 1 bag </b>including of all taxes.';

								}
								else
								{
									$final = $newPirce + ($newPirce *($records[0]['igst'])/100);
									$taxvalue =' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given )  = <b style="color:blue">'.$dat['currency'].' '.round($final,'3').' per 1 bag </b>including of all taxes.';

								}
							}
							if($dat['product_id'] != '10')
									$bag='más menos '.$plus_minus_quantity.' '.$bag_types;
								else
									$bag='';
							if($k=='air')
							{
							
								$txt_tarnsport='Tiempo de Entrega: 8 semanas aprox';
								
							}
							if($k=='sea')
							{
								$txt_tarnsport='Tiempo de Entrega: 14 semanas';
								
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
							if(strpos($size,'Custom')!==false)
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							else
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							
							if($secondary_curr!='')
							{
								
								$selCurrency_currency_code=$secondary_curr;
								$extra_profit=$extra_profit*$sec_currency_rate;
							}
							else
							{
								$selCurrency_currency_code=$selCurrency['currency_code'];
								$extra_profit=$extra_profit;
							
							}
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio : </b> '.$pri.' '.$this->numberFormate(($extra_profit),$num_format).' '.$pri_me.' por 1 '.$bag_type.' '.$taxvalue.'<b> { Para '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span><br><br></td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag='más menos '.$plus_minus_quantity.' '.$bag_types;
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Descuento ('.$records[0]['discount'].' %) : '.$pri.' </b> '.$this->numberFormate(($extra_profit),$num_format).' '.$pri_me.' por 1 '.$bag_type.' '.$taxvaluedis.'<b> { Para '.$qty.' '.$bag_types.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
							}
							
						}
						$pr = 'Bolsa';	
				        if($dat['product_id'] == '6')
					        $pr = 'Rollos';
						
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Tipo de  '.$pr.' : </b>  '.$product_nm_new['product_name_spanish'].' impresión personalizada ';
					if($dat['product_id'] != '10'  && $dat['product_id'] != '6' )
						$m.=str_replace('<br>',' ',$records[0]['text']);
						
					if($dat['product_id'] == '6')
					    $m.='&nbsp;&nbsp;Tipo de Rollos - '.$records[0]['make_name_spanish'].'</td></tr>';
					else
					    $m.='&nbsp;&nbsp;Tipo de Producto - '.$records[0]['make_name_spanish'].'</td></tr>';
					    
					}
					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Tamaño : </b>'.$size.'</td></tr> ';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Material : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					if(isset($materialData) && !empty($materialData))
					{
						$materialStr = '';
						/*if($records[0]['make_id']=='6')
						{
								$j='1';
								for($gi=0;$gi<count($materialData);$gi++)
								{
									if($materialData[$gi]['material_id']!='16' && $records[0]['make_id']=='6')
									{
										if($j=='2')
										{
											
											$materialData[$gi]['material_name'] = 'oxo-Biodegradable PE';
											$materialData[$gi]['material_thickness'] = '80';
										}
										$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
									}
									$j++;
								}
						}
						else
						{*/
							for($gi=0;$gi<count($materialData);$gi++)
							{
								$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
							}
						//}
						$html .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html .= '</tr>';
					
				
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; País de Entrega : </b> '.$con.' </td></tr>';
					$html .=$m;
					if($records[0]['gusset_printing_type']=='Front & Back  +  No Gusset Printing')
						$records[0]['gusset_printing_type']= 'Frente, Reverso, Fuelle de fondo NO impreso';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Side Gusset Printing')	
					    $records[0]['gusset_printing_type']= 'Frente & Reverso + Fuelles Laterales';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Bottom / Side Gusset Printing')
				    	$records[0]['gusset_printing_type']= 'Frente & Reverso + Fondo / Fuelles Laterales';
					else
						$records[0]['gusset_printing_type']= 'Frente & Reverso + Fuelle de Fondo Impreso';
					
					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Impresión : </b>'.$records[0]['printing_effect'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>';
					
					if($dat['product_id'] != '6')
					    $html.='<br/>Acabado : '.$records[0]['gusset_printing_type'];
					    
					$html .='</td></tr>';
					
					if($addedByInfo['country_id']==155)
						$txt='[Precio para el Cliente]';
					else
						$txt='';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html .=$pmq;
					$html .=$gress;
					$html .=$pmq_gress;
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$cylinder_price=$cylinder_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$cylinder_price=$cylinder_price;
						}
						
						
						
						$html .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Precio del Cilindro de Impresión  : </b>'.$pri.' '.$this->numberFormate(($cylinder_price),$num_format).'<b style="color:blue"> &nbsp; '.$pri_me.'  Por Color </b></td></tr>';
						if($cylinder_price_withtax != '0.000')
							$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; 
Costo del Cilindro de Impresión (CON IVA) : </b> '.$pri.''.$this->numberFormate(($cylinder_price_withtax),$num_format).'<b style="color:blue"> &nbsp; '.$pri_me.'  Por Color </b></td></tr>';
					
					if(isset($tool_price) && $tool_price>0.000)
					{
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$tool_price=$tool_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$tool_price=$tool_price;
						}
						
						$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo de Herramienta Adicional : </b> '.$pri.' '.$this->numberFormate(($tool_price),$num_format).' '.$pri_me.'. Este costo es aplicable debido a que el ancho/fuelle de la bolsa es impar. Este costo es solo aplicable en la primera impresión, para impresiones futuras se usará el  mismo.</td></tr>';
						if($tool_price_withtax != '0.000')
							$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo de Herramienta Adicional (Con IVA) : </b> '.$pri.' '.$this->numberFormate(($tool_price_withtax),$num_format).'  '.$pri_me.' <b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html .= '</table>';
				$html .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
		
				$html_gress .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				foreach($new_data as $key=>$value)
				{
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{
								$zip_nm = $this->query("SELECT zipper_name_spanish FROM product_zipper WHERE zipper_name = '".$records[0]['zipper_txt']."'");
								$spt_nm = $this->query("SELECT spout_name_spanish FROM product_spout WHERE spout_name = '".$records[0]['spout_txt']."'");
								if($records[0]['accessorie_txt']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_spanish FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt']."'");
									$records[0]['accessorie_txt'] = $acc_nm->row['product_accessorie_name_spanish'];
								}
								if($records[0]['accessorie_txt_corner']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_spanish FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt_corner']."'");
									$records[0]['accessorie_txt_corner'] = $acc_nm->row['product_accessorie_name_spanish'];
								}
								if($records[0]['valve_txt']=='No Valve')
									$records[0]['valve_txt'] = 'No Válvula';
								else	
									$records[0]['valve_txt'] = 'Con válvula';
								
								$eff_nm = $this->query("SELECT 	effect_name_spanish FROM " . DB_PREFIX . "printing_effect WHERE effect_name = '".$records[0]['printing_effect']."' ");
								$records[0]['printing_effect'] = $eff_nm->row['effect_name_spanish'];
								
								if($records[0]['laser_type']=='No Laser Scoring')
								    $laser_type ='Sin Incisión Laser';
							    else
							        $laser_type ='Con Incisión Laser';
								
								if($records[0]['slider_place']!='')
        						{
        						    
            						if($records[0]['slider_place'] == 'Slider Zipper On Top Of Pouch')
            						{
            						    $records[0]['slider_place'] = 'Zipper de Seguridad para niños';
            						    $tamper = $records[0]['slider_place'].' '.$records[0]['tamperevident'];
            						}
            						else
            						{
            						    $records[0]['slider_place'] = 'Zipper en la parte superior de lo bolsa';
            						    $tamper = $records[0]['slider_place'];
            						}
        						}
								
								$records[0]['email_text'] = $zip_nm->row['zipper_name_spanish'].' , '.$laser_type.' , '.$records[0]['valve_txt'].'<br> '.$spt_nm->row['spout_name_spanish'].' , '.$records[0]['accessorie_txt'] .' , '.$records[0]['accessorie_txt_corner'] .' , '.$tamper ;
								if($k == "air") 
								{
									$color = "red";	
								}
								elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
							if($selCurrency)
							{
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code'],$user_admin_id['international_branch_id']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$gress_cylinder_price = $gress_cylinder_price * $sec_currency_rate;
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$userInfo = $this->getUserInfo($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
										$cylinder_price = $cylinder_base_price;
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
										$gress_cylinder_price=$cylinder_price;
									}
									else
										$cylinder_price = $cylinder_currency_price;	
								}
								else
									$cylinder_price = $cylinder_currency_price;	
							 }
							 else
							 {
								$selCurrency['currency_code'] = $dat['currency'];
								$cylinder_price = $records[0]['cylinder_price'];
								$gress_cylinder_price = $records[0]['cyli_gress_price'];
								$tool_price = $records[0]['tool_price'];
							 }
							 $cylinder_price_withtax = $records[0]['cylinder_price_withtax'];
							 $tool_price_withtax = $records[0]['tool_price_withtax'];
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
								
								$qty_type = ''; $bag_types='bolsas'; $bag_type='bolsa';
							    if($dat['product_id'] == '6'){
							        $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
								
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
						
							if(!empty($menu_id) || !empty($menu_admin_permission))
							{
						
								if($records[0]['gress_percentage'] != '0.000' OR $records[0]['gress_air'] !='0.000' OR $records[0]['gress_sea'] !='0.000')
								{
								
									if($dat['shipment_country_id']==91)
										$txt='[Your Purchase Price]';
									else
										$txt='';
									$gress = '<tr><td colspan="2" >&nbsp;</td></tr><tr valign="top"><td width="60" colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Percentage Prices:'.$txt.'</b></td></tr>';								
									$newPircegress = ((($records[0]['totalPrice'] - $records[0]['customerGressPrice'] - $records[0]['gress_price']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
									$newPircegress = $newPircegress/$qty;
									$taxvaluegress='';
									
									if($records[0]['tax_type']=='With In Gujarat')
										$tax_name_data='With In Gujarat';
									else
										$tax_name_data=$records[0]['tax_type'];
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										$newPircegress =$this->numberFormate(($newPircegress-($newPircegress*$records[0]['discount']/100)),$num_format);
										
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' por 1 '.$bag_type.' (Incluye IVA).';	
										}
										else
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['igst'].'% IGST + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' por 1 '.$bag_type.' (Incluye IVA).';	

										}
									}						
									if($dat['shipment_country_id'] == '111')
									{
										$taxvaluegress = '';
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
											$taxvaluegress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' por 1 '.$bag_type.' (Incluye IVA).';

										}
										else
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
											$taxvaluegress = ' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' por 1 '.$bag_type.' (Incluye IVA).';

										}
										
									}
									if($k=='air')
									{
										$gp=$records[0]['gress_per'];
										$txt_tarnsport='Tiempo de Entrega: 8 semanas';
										
									}
									if($k=='sea')
									{
										$gp=$records[0]['gress_per'];
										$txt_tarnsport='Tiempo de Entrega: 14 semanas aprox ';
										
									}
									if($k=='pickup')
									{
										$gp=$records[0]['gress_per'];
										$tax_str='Transportation cost NOT included.';
										$txt_tarnsport='By Pickup ';
									}
									if($dat['product_id'] != '10')
										$bag='más menos '.$plus_minus_quantity.' '.$bag_types;
									else
										$bag='';
									if(strpos($size,'Custom')!==false)
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,3);
										$newextra_profit = $newPriceGress_val;
										
									}
									else
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,3);
										$newextra_profit = $newPriceGress_val;
									}
									if($secondary_curr!='')
									{
										$selCurrency_currency_code=$secondary_curr;
										$newextra_profit=$newextra_profit*$sec_currency_rate;
									}
									else
									{
										$selCurrency_currency_code=$selCurrency['currency_code'];
										$newextra_profit=$newextra_profit;
								
									}
									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio : </b> '.$pri.' '.$this->numberFormate(($newextra_profit),$num_format).' '.$pri_o.' ' .$pri_m.' '.$taxvaluegress.'<b> { Para '.$qty.' '.$bag_type.'  '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span><br><br></td></tr>';	
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										if($dat['product_id'] != '10')
											$bag='más menos '.$plus_minus_quantity.' '.$bag_types;
										else
											$bag='';
										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Descuento ('.$records[0]['discount'].' %) : </b></td><td> '.$pri.' '.$this->numberFormate(($newextra_profit),$num_format).' '.$pri_m.' '.$taxvaluedisgress.'<b> { Para '.$qty.' '.$bag_type.'  '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' at GP '.$gp.' % </span></td></tr>';
									}
								}	
							}
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							$newPirce = $newPirce/$qty;
							$taxvalue='';
							
							if($records[0]['tax_type']=='With In Gujarat')
								$tax_name_data='With In Gujarat';
							else
								$tax_name_data=$records[0]['tax_type'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),$num_format);
								
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' +  ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' (Incluye IVA).';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst']+$records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST'.' + ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' (Incluye IVA).';											

								}
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$taxvalue = '';
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' +  ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' (Incluye IVA).';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst']+$records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST'.' + ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' (Incluye IVA).';											

								}
							}
							
							if($dat['product_id'] != '10')
								$bag='más menos '.$plus_minus_quantity.' '.$bag_types;
							else
								$bag='';
							if($k=='air')
							{
								$gp=$records[0]['gress_per'];
								$txt_tarnsport='Tiempo de Entrega: 8 semanas';
								
							}
							if($k=='sea')
							{
								$gp=$records[0]['gress_per'];
								$txt_tarnsport='Tiempo de Entrega: 14 semanas aprox ';
								
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
							if(strpos($size,'Custom')!==false)
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							else
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							
							if($secondary_curr!='')
							{
								$selCurrency_currency_code=$secondary_curr;
								$extra_profit=$extra_profit*$sec_currency_rate;
							}
							else
							{
								$selCurrency_currency_code=$selCurrency['currency_code'];
								$extra_profit=$extra_profit;
							
							}
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio : </b> '.$pri.' '.$this->numberFormate(($extra_profit),$num_format).' '.$pri_o.' '.$pri_m.' '.$taxvalue.'<b> { Para  '.$qty.' '.$bag_types.'  '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag='más menos '.$plus_minus_quantity.' '.$bag_types;
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Descuento ('.$records[0]['discount'].' %) : </b>  '.$pri.'  '.$this->numberFormate(($extra_profit),$num_format).' '.$pri_o.' '.$pri_m.'  '.$taxvaluedis.'<b> { Para  '.$qty.' '.$bag_types.'  '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
							}
							
						}
						$pr = 'Bolsas';	
				        if($dat['product_id'] == '6')
					        $pr = 'Rollos';
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Tipo de '.$pr.'  : </b> '.$product_nm_new['product_name_spanish'].' impresión personalizada ';
					
					
					
					
					if($dat['product_id'] != '10'  && $dat['product_id'] != '6' )
						$m.=str_replace('<br>',' ',$records[0]['text']);
						
					if($dat['product_id'] == '6')
					    $m.='&nbsp;&nbsp;Tipo de Rollos - '.$records[0]['make_name_spanish'].'</td></tr>';
					else
					    $m.='&nbsp;&nbsp;Tipo de Producto - '.$records[0]['make_name_spanish'].'</td></tr>';
					}
					$html_gress .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Tamaño : </b>'.$size.'</td></tr> ';
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Material : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					if(isset($materialData) && !empty($materialData))
					{
						$materialStr = '';
						/*if($records[0]['make_id']=='6')
						{
								$j='1';
								for($gi=0;$gi<count($materialData);$gi++)
								{
									if($materialData[$gi]['material_id']!='16' && $records[0]['make_id']=='6')
									{
										if($j=='2')
										{
											$materialData[$gi]['material_name'] = 'oxo-Biodegradable PE';
											$materialData[$gi]['material_thickness'] = '80';
										}
										$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
									}
									$j++;
								}
						}
						else
						{*/
							for($gi=0;$gi<count($materialData);$gi++)
							{
								$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
							}
						//}
						$html_gress .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html_gress .= '</tr>';
					if($secondary_curr!='')
					{
					    $con = 'México';
					    $pri_m = 'pesos por 1 '.$bag_type;
					    $pri_me = 'pesos';
					    $pri='';
					    $num_format = 2;
					}
					else
					{
					     $con = $shippingCountry['country_name'];
					    $pri = $selCurrency_currency_code;
					    $pri_m=$pri_me='';
					     $num_format = 3;
					}
					
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; País de Entrega : </b> '.$con.' </td></tr>';
					$html_gress .=$m;
					if($records[0]['gusset_printing_type']=='Front & Back  +  No Gusset Printing')
						$records[0]['gusset_printing_type']= 'Frente, Reverso, Fuelle de fondo NO impreso';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Side Gusset Printing')	
					    $records[0]['gusset_printing_type']= 'Frente & Reverso + Fuelles Laterales';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Bottom / Side Gusset Printing')
				    	$records[0]['gusset_printing_type']= 'Frente & Reverso + Fondo / Fuelles Laterales';
					else
						$records[0]['gusset_printing_type']= 'Frente & Reverso + Fuelle de Fondo Impreso';
					
				
					$html_gress .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Impresión : </b>'.$records[0]['printing_effect'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>';
					
					if($dat['product_id'] != '6')
					    $html_gress.='<br/>Acabado : '.$records[0]['gusset_printing_type'];
					
					if($addedByInfo['country_id']==155)
						$txt='[Precio para el Cliente]';
					else
						$txt='';
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html_gress .=$pmq;
					$html_gress .=$gress;
					$html_gress .=$pmq_gress;
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$cylinder_price=$cylinder_price*$sec_currency_rate;
							$gress_cylinder_price = $gress_cylinder_price * $sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$cylinder_price=$cylinder_price;
							$gress_cylinder_price = $gress_cylinder_price;
						}
						
						if((int)$cylinder_price==(int)$gress_cylinder_price){
						    $gress_cyli = $gress_cylinder_price;}
						else{
						    $gress_cyli = $cylinder_price-$gress_cylinder_price;}
						
						
						$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Precio del Cilindro de Impresión  : </b> '.$pri.' '.$this->numberFormate(($cylinder_price),$num_format).'<b style="color:blue"> &nbsp; '.$pri_me.'  Por Color </b><br>
					                   <b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Precio del Cilindro de Impresión: </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($gress_cyli),$num_format).'<b style="color:blue"> &nbsp; Per Colour </b></td></tr>';
						if($cylinder_price_withtax != '0.000')
							$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo del Cilindro de Impresión (CON IVA) : </b> '.$pri.' '.$this->numberFormate(($cylinder_price_withtax),$num_format).'<b style="color:blue"> &nbsp; '.$pri_me.'  Por Color </b></td></tr>';
				
					if(isset($tool_price) && $tool_price>0.000)
					{
					
						if($secondary_curr!='')
						{
							
							$selCurrency_currency_code=$secondary_curr;
							$tool_price=$tool_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$tool_price=$tool_price;
						}
						
						$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo de Herramienta Adicional : </b> '.$pri.' '.$this->numberFormate(($tool_price),$num_format).' '.$pri_me.'. Este costo es aplicable debido a que el ancho/fuelle de la bolsa es impar. Este costo es solo aplicable en la primera impresión, para impresiones futuras se usará el  mismo.</td></tr>';
						if($tool_price_withtax != '0.000')
							$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo de Herramienta Adicional (Con IVA) : </b> '.$pri.' '.$this->numberFormate(($tool_price_withtax),$num_format).'  '.$pri_me.' <b style="color:blue"> &nbsp; Por Color </b></td></tr>';
					}
					$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					}
					$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html_gress .= '</table>';
				
				$html_gress .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b><br/>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
				
				$html_gress .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;Nota:<br/>   Las cantidades cotizadas son por cada diseño<br>
                                                                             Validez de la cotización: 15 días<br>
                                                                             Los precios anteriores causarán 16% de IVA<br>
                                                                             Los productos son entregados en nuestra bodega en la Ciudad de México.</b></div>';
				
				if($cr['user']==1 || $cr['user']==2)
				{
					
					if($cr['user']==1)
					{
						
						$new_html=$html_gress;
						$email_temp[]=array('html'=>$new_html,'email'=>($dat['customer_email'])?$dat['customer_email']:ADMIN_EMAIL);
					}
					if($cr['user']==2)
					{
						if(!empty($menu_id))
						{
							$new_html=$html_gress;
						
						}
						else
						{
							$new_html=$html;
						
						}	
						$email_temp[]=array('html'=>$new_html,'email'=>$addedByInfo['email']);
						
					}
				}
				if($toEmail!='' && $cr['user']!=0)
				{
					if(!empty($menu_id))
					{
						$new_html=$html_gress;
					
					}
					else
					{
						$new_html=$html;
					
					}	
					$email_temp[]=array('html'=>$new_html,'email'=>$toEmail);
					
				}
				if($cr['user']==0)
				{
					
					$new_html=$html_gress;
					$email_temp[]=array('html'=>$new_html,'email'=>ADMIN_EMAIL);
					if($data[0]['added_by_user_type_id']==2)
					{
						$admininfo = $this->getUser($addedByInfo['user_id'],'4');	
						$email_temp[]=array('html'=>$new_html,'email'=>$admininfo['email']);
					}
					
				}
				
				$html='<table border="0px">';
				if($secondary_curr!='')
				{
					if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['added_by_user_type_id'] =='2' ) && $addedByInfo['user_id']=='10' )
				        $html.='';
				    else
					    $html .='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp;'.$secondary_curr.'</b></div><br><br>';
				}
				$html_gress='<table border="0px">';
				if($secondary_curr!='')
				{
					if(($_SESSION['LOGIN_USER_TYPE']=='2' && $data[0]['added_by_user_type_id'] =='2' ) && $addedByInfo['user_id']=='10' )
				        $html_gress.='';
			    	else
				    	$html_gress.='<div style="align:center;font-size:15px;"><b> 1 '.$selCurrency['currency_code'].'='.$sec_currency_rate.'&nbsp;&nbsp;'.$secondary_curr.'</b></div><br><br>';
				}
			}
		    $addedByInfo['email_signature'] = ' CLIFTON PACKAGING SA DE CV  <br>                           
                                                5 de Mayo 201 Bodega C<br>
                                                Col. Ampliación Providencia  Del. Azcapotzalco<br>
                                                CP: 02440 <br>
                                                Ciudad de México<br>
                                                Línea Fija: (55) 5383 5478<br>';
		    
		    
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(1); 
			$formEmail = $addedByInfo['email'];							
			$firstTimeemial = 0;
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
			$path = HTTP_SERVER."template/product_quotation.html";
			$output = file_get_contents($path);  
			$search  = array('{tag:header}','{tag:details}');
			$signature = 'Thanks.';
			if($addedByInfo['email_signature'])
				$signature = nl2br($addedByInfo['email_signature']);
			
			foreach($email_temp as $val)
			{
				if($toEmail == '')
				{
					$toEmail = $formEmail;
					$firstTimeemial = 1;
				}				
				$subject = $sub;
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
				send_email($val['email'],$formEmail,$subject,$message,'');
				
			}
		
				
		}
		
		
	}
	//[kinjal] made fun on 7-12-2017
	public function getLaserScoring()
	{
		$sql="SELECT * FROM laser_scoring_types WHERE status=1 AND is_delete=0";
		$data= $this->query($sql);
		if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
	}
	public function getLaserScoringPrice($product_id,$laser_id)
	{
		$sql="SELECT laser_scoring_price FROM laser_scoring_prices WHERE laser_scoring_type_id = '".$laser_id."' AND laser_product_id='".$product_id."'";
		$data = $this->query($sql);
		if ($data->num_rows) {
            $result = $data->row['laser_scoring_price'];
			return $result;
			
        } else {
            return false;
        }
	}
	public function getLaserType($laser_id)
	{
		$sql="SELECT laser_name FROM laser_scoring_types WHERE type_id = '".$laser_id."'";
		$data = $this->query($sql);
		if ($data->num_rows) {
			return $data->row['laser_name'];
			
        } else {
            return false;
        }
	}
		//[kinjal] made fun on 20-12-2017
	public function gethandle()
	{
		$sql="SELECT * FROM handle_price WHERE status=1 AND is_delete=0";
		$data= $this->query($sql);
		if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
	}
	public function getHandleType($handle_id)
	{
		$sql="SELECT * FROM handle_price WHERE handle_id = '".$handle_id."'";
		$data = $this->query($sql);
		if ($data->num_rows) {
			return $data->row;
			
        } else {
            return false;
        }
	}
	//END [kinjal]
	//[kinjal] done on 31-1-2018
	public function getGressQtyWise($qty,$transport,$admin_user_id)
	{
		$data = $this->query("SELECT percentage FROM " . DB_PREFIX ."gress_percentage WHERE ib_id = '".$admin_user_id."' AND transport='".$transport."'  AND product_quantity='".$qty."'");
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//END
	//[kinjal] done on 19-3-2018
	public function sendQuotationEmailInItalian($quotation_id,$toEmail = '',$setQuotationCurrencyId='',$secondary_curr='',$sec_currency_rate=''){
	    		
				
		$getData = ' product_quotation_id,pq.layer, pq.added_by_user_id, pq.added_by_user_type_id, customer_name, customer_gress_percentage, customer_email, shipment_country_id, multi_quotation_number,pq.multi_product_quotation_id, quotation_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,quantity_type,layer,added_by_country_id, currency, currency_id, currency_price, pq.date_added, cylinder_price,tool_price, customer_email,pq.status,discount,customer_gress_percentage,gress_percentage,gress_air,gress_sea ';
		$data = $this->getQuotation($quotation_id,$getData);
		foreach($data as $dat)
	    {
			 $qdata= $this->getQuotationQuantity($dat['product_quotation_id']);
			 if($qdata!='')
			  $quantityData[] =$qdata;
	    }
		$product_nm_new =$this->getProduct($dat['product_id']);
		
	    $str=$product_nm_new['product_name_italian'];
	    if($dat['product_id'] == '6')
	    {
	        $str=$product_nm_new['product_name_italian'];
	        $product_nm_new['product_name_italian']='Fornito in bobina';
	       
	    }
		$menu_id = $this->getMenuPermission(151,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$user_admin_id=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		
		
		
		$menu_admin_permission='';
		if($_SESSION['LOGIN_USER_TYPE']!='4')
		{
			$menu_admin_permission=$this->getMenuPermission(151,$user_admin_id['user_id'],4);
			
		}
		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$product_nm_new['product_name']),0,3));		
		$sub =$dat['multi_quotation_number'] .' - '.ucwords($dat['customer_name']).' - Personalizzata '.$first;
		$gussetvalue='';$s='';$sub2='';$m_k='';
	
		foreach($quantityData as $k=>$qty_data)
		{
			foreach($qty_data as $tag=>$qty)
			{
				foreach($qty as $q=>$arr)
				{
					if($arr[0]['gusset'] == '')
					{
						$gussetval = 'Senza Soffietto';
					}
					else
					{
						if($dat['product_id'] == '7')
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm Fuelle de fondo }';
						}
						elseif($arr[0]['gusset'] == '0')
						{
							 $gussetval = ' ';	
						}
						else
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.
							$arr[0]['gusset'].'mm Soffietto}';
						}
					}
					if($dat['product_id'] != '10')
					{
						if($arr[0]['volume']!='')
						{ 
							if($s!=$qty)
							{
								$sub2.=$arr[0]['volume'].' , ';
								$s=$qty;
							}
							$gussetvalue.=$arr[0]['volume'].' , ';
						}
					}
					if($gussetvalue=='')
						$gussetvalue=' L : '.(int)$arr[0]['width'].'mm  x '.'A : '.(int)$arr[0]['height'].'mm '.$gussetval;
					if($arr[0]['volume']=='')
						$arr[0]['volume']='Personalizzato';
					
					if($dat['product_id'] != '10')
						$key='L : '.(int)$arr[0]['width'].'mm x '.'A : '.(int)$arr[0]['height'].'mm '.$gussetval.' ['.$arr[0]['volume'].']';
					else
						$key='L : '.(int)$arr[0]['width'].'mm x '.'A : '.(int)$arr[0]['height'].'mm '.$gussetval;
						$m_k[$arr[0]['product_quotation_quantity_id']]='';
						
					$make=$arr[0]['make_name_italian'];
					//printr($arr[0]['valve_txt']);
					$make_id=$arr[0]['make_id'];
				
					$new_data[$arr[0]['text']][$key.' ('.$arr[0]['layer'].') '][$q.'('.$arr[0]['product_quotation_quantity_id'].')'][$tag][]=$arr[0];
					if($dat['product_id'] != '10')
					{
						$zip_nm = $this->query("SELECT zipper_name_italian FROM product_zipper WHERE zipper_name = '".$arr[0]['zipper_txt']."'");
						if($arr[0]['valve_txt']=='No Valve')
							$arr[0]['valve_txt'] = 'Senza Valvola';
						else	
							$arr[0]['valve_txt'] = 'Con valvola';
						$sub1= ' '.$zip_nm->row['zipper_name_italian'].' '.$arr[0]['valve_txt'].''.$sub2; 
					}
					else
						$sub1= $sub2; 
				}
			}	
			$n[$tag][]=array('width'=>$arr[0]['width'],'height'=>$arr[0]['height'],'gusset'=>$arr[0]['gusset'],'volume'=>$arr[0]['volume'],'make'=>$arr[0]['make']);
			$t=$tag;
		}
		$addedByInfo = $this->getUser($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
		
		$sub1= substr($sub1,0,-3);
		$sub=$sub.$sub1;
		$html='';$tax_str='';$html_gress='';
		
		if($addedByInfo)
		{
			$pqcquery='';
			$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
			if($selCurrency)
				$pqcquery = " product_quotation_currency_id = '".$selCurrency['product_quotation_currency_id']."', ";
			$currency_rate[]=array('currency_rate'=>1,'user'=>0);
			if($dat['customer_email'] != '' || $toEmail != '')
				$currency_rate[]=array('currency_rate'=>($selCurrency['currency_rate']!='')?$selCurrency['currency_rate']:1,'user'=>1);
			else
				$currency_rate[]=array('currency_rate'=>1,'user'=>2);
				
			$html .='<table border="0px">';
			if($secondary_curr!='')
			{ 
			    $html.='';
			}
			$html_gress .='<table border="0px">';
			if($secondary_curr!='')
			{
				$html_gress.='';
			}
			foreach($currency_rate as $cr)
			{
				$gettermsandconditions = $this->gettermsandconditions($dat['added_by_user_id'],$dat['added_by_user_type_id']);
				$shippingCountry = $this->getCountry($dat['shipment_country_id']);
				
				$html .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				foreach($new_data as $key=>$value)
				{
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{
									if($secondary_curr!='')
        							{
        								$selCurrency_currency_code=$secondary_curr;
        							}
        							else
        							{
        								$selCurrency_currency_code=$selCurrency['currency_code'];

        							}
        							$num_format = 3;
									
								
								$zip_nm = $this->query("SELECT zipper_name_italian FROM product_zipper WHERE zipper_name = '".$records[0]['zipper_txt']."'");
								$spt_nm = $this->query("SELECT spout_name_italian FROM product_spout WHERE spout_name = '".$records[0]['spout_txt']."'");
								if($records[0]['accessorie_txt']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_italian FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt']."'");
									$records[0]['accessorie_txt'] = $acc_nm->row['product_accessorie_name_italian'];
								}
								if($records[0]['accessorie_txt_corner']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_italian FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt_corner']."'");
									$records[0]['accessorie_txt_corner'] = $acc_nm->row['product_accessorie_name_italian'];
								}
								
								if($records[0]['valve_txt']=='No Valve')
									$records[0]['valve_txt'] = 'Senza Valvola';
								else	
									$records[0]['valve_txt'] = 'Con valvola';
								
								$eff_nm = $this->query("SELECT 	effect_name_italian FROM " . DB_PREFIX . "printing_effect WHERE effect_name = '".$records[0]['printing_effect']."' ");
								$records[0]['printing_effect'] = $eff_nm->row['effect_name_italian'];
								
								if($records[0]['laser_type']=='No Laser Scoring')
								    $laser_type ='Senza pretaglio laser';
							    else
							        $laser_type ='Con pretaglio laser';
							 
							    if($records[0]['slider_place']!='')
        						{
        						    
            						if($records[0]['slider_place'] == 'Slider Zipper On Top Of Pouch')
            						{
            						    if($records[0]['tamperevident']=='With Tamper Evident')
            						        $records[0]['tamperevident'] = 'Con evidenziatura';
            						    else
            						        $records[0]['tamperevident'] = 'Senza evidenziatura';
            						        
            						    $records[0]['slider_place'] = 'Zip sopra la busta';
            						    $tamper = $records[0]['slider_place'].' '.$records[0]['tamperevident'];
            						}
            						else
            						{
            						    $records[0]['slider_place'] = 'Zip dentro la busta';
            						    $tamper = $records[0]['slider_place'];
            						}
        						}    
							     
								$records[0]['email_text'] = $zip_nm->row['zipper_name_italian'].' , '.$laser_type.' '.$records[0]['valve_txt'].'<br> '.$spt_nm->row['spout_name_italian'].' , '.$records[0]['accessorie_txt'] .' , '.$records[0]['accessorie_txt_corner'] .' , '.$tamper;
								if($k == "air") 
								{
									$color = "red";	
								}elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
							if($selCurrency)
							{
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code'],$user_admin_id['international_branch_id']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$cylinder_price = $cylinder_base_price;	
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
									}
									else
										$cylinder_price = $cylinder_currency_price;	
								}
								else
									$cylinder_price = $cylinder_currency_price;	
							 }
							 else
							 {
								$selCurrency['currency_code'] = $dat['currency'];
								$cylinder_price = $records[0]['cylinder_price'];
								$tool_price = $records[0]['tool_price'];
							 }
							 $cylinder_price_withtax = $records[0]['cylinder_price_withtax'];
							 $tool_price_withtax = $records[0]['tool_price_withtax'];
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
							
							$qty_type = ''; $bag_types='bolsas'; $bag_type='bolsa';
							if($dat['product_id'] == '6'){
							    $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
							    
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
						
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							$newPirce = $newPirce/$qty;
							$taxvalue='';
							
							if($records[0]['tax_type']=='With In Gujarat')
								$tax_name_data='With In Gujarat';
							else
								$tax_name_data=$records[0]['tax_type'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
								
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' cadauna tasse incluse.';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' cadauna tasse incluse.';											

								}
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$taxvalue = '';
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$final = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvalue = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given )  = <b style="color:blue">'.$dat['currency'].' '.round($final,'3').' cadauna tasse incluse.';

								}
								else
								{
									$final = $newPirce + ($newPirce *($records[0]['igst'])/100);
									$taxvalue =' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given )  = <b style="color:blue">'.$dat['currency'].' '.round($final,'3').' cadauna tasse incluse.';

								}
							}
							if($dat['product_id'] != '10')
									$bag='piu o meno '.$plus_minus_quantity;
								else
									$bag='';
							if($k=='air')
							{
							
								$txt_tarnsport='Inclusa spedizione via aereo a UK';
								
							}
							if($k=='sea')
							{
								$txt_tarnsport='Inclusa spedizione via mare a UK';
								
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
							if(strpos($size,'Custom')!==false)
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							else
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							
							if($secondary_curr!='')
							{
								
								$selCurrency_currency_code=$secondary_curr;
								$extra_profit=$extra_profit*$sec_currency_rate;
							    if($k=='air')
							        $extra_profit =(($extra_profit / 0.7) * 1.25) ;
							    if($k=='sea')
							        $extra_profit =(($extra_profit / 0.6) * 1.25);
							}
							else
							{
								$selCurrency_currency_code=$selCurrency['currency_code'];
								$extra_profit=$extra_profit;
							
							}
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prezzo : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($extra_profit),$num_format).'  cad. '.$taxvalue.'<b> { Per '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span><br><br></td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag='piu o meno '.$plus_minus_quantity;
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Sconto ('.$records[0]['discount'].' %) : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($extra_profit),$num_format).' cad. '.$taxvaluedis.'<b> { Per '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
							}
							
						}
						$pr = 'busta';	
				        if($dat['product_id'] == '6')
					        $pr = 'bobina';
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Tipo di '.$pr.' : </b> '.$product_nm_new['product_name_italian'];
					if($dat['product_id'] != '10'  && $dat['product_id'] != '6' )
						$m.=str_replace('<br>',' ',$records[0]['email_text']);
						
					    $m.='&nbsp;&nbsp;Tipo di '.$pr.' - '.$records[0]['make_name_italian'].'</td></tr>';
					}
					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Misure : </b>'.$size.'</td></tr> ';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Materiale : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					
					
					if(isset($materialData) && !empty($materialData))
					{   
						$materialStr = '';
						/*if($records[0]['make_id']=='6')
						{
								$j='1';
								for($gi=0;$gi<count($materialData);$gi++)
								{
									if($materialData[$gi]['material_id']!='16' && $records[0]['make_id']=='6')
									{
										if($j=='2')
										{
											
											$materialData[$gi]['material_name_italian'] = 'PE biodegradabile';
											$materialData[$gi]['material_thickness'] = '80';
										}
										$mat_nam = $this->query("SELECT material_name_italian FROM product_material WHERE 	material_name = '".$materialData[$gi]['material_name']."'");
										$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $mat_nam->row['material_name_italian'] .' / ';
									}
									$j++;
								}
						}
						else
						{*/
							for($gi=0;$gi<count($materialData);$gi++)
							{
								$mat_nam = $this->query("SELECT material_name_italian FROM product_material WHERE 	material_name = '".$materialData[$gi]['material_name']."'");
								$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '.$mat_nam->row['material_name_italian'] .' / ';
							}
						//}
						$html .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html .= '</tr>';
					
				
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Paese di destinazione: : </b> '.$shippingCountry['country_name'].' </td></tr>';
					$html .=$m;
					if($records[0]['gusset_printing_type']=='Front & Back  +  No Gusset Printing')
						$records[0]['gusset_printing_type']= 'Stampa Fronte + retro';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Side Gusset Printing')	
					    $records[0]['gusset_printing_type']= 'Stampa Fronte + retro +  soffietti laterali';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Bottom / Side Gusset Printing')
				    	$records[0]['gusset_printing_type']= 'Stampa Fronte + retro + fondo + soffietti';
					else
						$records[0]['gusset_printing_type']= 'Stampa Fronte + retro + fondo';
					
					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Finitura : </b>'.$records[0]['printing_effect'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>';
					
					if($dat['product_id'] != '6')
					    $html.='<br/>Superficie di Stampa : '.$records[0]['gusset_printing_type'];
					    
					$html .='</td></tr>';
					
					if($addedByInfo['country_id']==155)
						$txt='[Prezzo]';
					else
						$txt='';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html .=$pmq;
					$html .=$gress;
					$html .=$pmq_gress;
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$cylinder_price=$cylinder_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$cylinder_price=$cylinder_price;
						}
						
						
						
						$html .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Prezzo per cilindro di stampa  : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($cylinder_price),$num_format).'<b style="color:blue"> &nbsp; a colore </b></td></tr>';
						if($cylinder_price_withtax != '0.000')
							$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; 
Costo a cilindro tasse incluse : </b> '.$this->numberFormate(($cylinder_price_withtax),$num_format).'<b style="color:blue"> &nbsp; a colore </b></td></tr>';
					
					if(isset($tool_price) && $tool_price>0.000)
					{
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$tool_price=$tool_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$tool_price=$tool_price;
						}
						
						$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo per stampo extra : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price),$num_format).'. Questo costo è dovuto alla misura particolare di larghezza e/o soffietto.</td></tr>';
						if($tool_price_withtax != '0.000')
							$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo per stampo extra tasse incluse : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price_withtax),$num_format).' <b style="color:blue"> &nbsp; a colore </b></td></tr>';
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html .= '</table>';
				$html .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b>Pagamento: bonifico anticipato</div>';
		
				$html_gress .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				foreach($new_data as $key=>$value)
				{
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{
								$zip_nm = $this->query("SELECT zipper_name_italian FROM product_zipper WHERE zipper_name = '".$records[0]['zipper_txt']."'");
								$spt_nm = $this->query("SELECT spout_name_italian FROM product_spout WHERE spout_name = '".$records[0]['spout_txt']."'");
								if($records[0]['accessorie_txt']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_italian FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt']."'");
									$records[0]['accessorie_txt'] = $acc_nm->row['product_accessorie_name_italian'];
								}
								if($records[0]['accessorie_txt_corner']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_italian FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt_corner']."'");
									$records[0]['accessorie_txt_corner'] = $acc_nm->row['product_accessorie_name_italian'];
								}
								if($records[0]['valve_txt']=='No Valve')
									$records[0]['valve_txt'] = 'Senza Valvola';
								else	
									$records[0]['valve_txt'] = 'Con valvola';
								
								$eff_nm = $this->query("SELECT 	effect_name_italian FROM " . DB_PREFIX . "printing_effect WHERE effect_name = '".$records[0]['printing_effect']."' ");
								$records[0]['printing_effect'] = $eff_nm->row['effect_name_italian'];
							
							    if($records[0]['laser_type']=='No Laser Scoring')
								    $laser_type ='Senza pretaglio laser';
							    else
							        $laser_type ='Con pretaglio laser';
								
								if($records[0]['slider_place']!='')
        						{
        						    
            						if($records[0]['slider_place'] == 'Slider Zipper On Top Of Pouch')
            						{
            						    if($records[0]['tamperevident']=='With Tamper Evident')
            						        $records[0]['tamperevident'] = 'Con evidenziatura';
            						    else
            						        $records[0]['tamperevident'] = 'Senza evidenziatura';
            						        
            						    $records[0]['slider_place'] = 'Zip sopra la busta';
            						    $tamper = $records[0]['slider_place'].' '.$records[0]['tamperevident'];
            						}
            						else
            						{
            						    $records[0]['slider_place'] = 'Zip dentro la busta';
            						    $tamper = $records[0]['slider_place'];
            						}
        						} 
								
								
								$records[0]['email_text'] = $zip_nm->row['zipper_name_italian'].' , '.$laser_type.' , '.$records[0]['valve_txt'].'<br> '.$spt_nm->row['spout_name_italian'].' , '.$records[0]['accessorie_txt'].' , '.$records[0]['accessorie_txt_corner'] .' , '.$tamper;
								if($k == "air") 
								{
									$color = "red";	
								}
								elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
							if($selCurrency)
							{
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code'],$user_admin_id['international_branch_id']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$gress_cylinder_price = $gress_cylinder_price * $sec_currency_rate;
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$userInfo = $this->getUserInfo($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
										$cylinder_price = $cylinder_base_price;
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
										$gress_cylinder_price=$cylinder_price;
									}
									else
										$cylinder_price = $cylinder_currency_price;	
								}
								else
									$cylinder_price = $cylinder_currency_price;	
							 }
							 else
							 {
								$selCurrency['currency_code'] = $dat['currency'];
								$cylinder_price = $records[0]['cylinder_price'];
								$gress_cylinder_price = $records[0]['cyli_gress_price'];
								$tool_price = $records[0]['tool_price'];
							 }
							 $cylinder_price_withtax = $records[0]['cylinder_price_withtax'];
							 $tool_price_withtax = $records[0]['tool_price_withtax'];
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type); 
						
							if(!empty($menu_id) || !empty($menu_admin_permission))
							{
						
								if($records[0]['gress_percentage'] != '0.000' OR $records[0]['gress_air'] !='0.000' OR $records[0]['gress_sea'] !='0.000')
								{
								
									if($dat['shipment_country_id']==91)
										$txt='[Prezzo finale di vendita]';
									else
										$txt='';
									$gress = '<tr><td colspan="2" >&nbsp;</td></tr><tr valign="top"><td width="60" colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Percentage Prices:'.$txt.'</b></td></tr>';								
									$newPircegress = ((($records[0]['totalPrice'] - $records[0]['customerGressPrice'] - $records[0]['gress_price']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
									$newPircegress = $newPircegress/$qty;
									$taxvaluegress='';
									
									if($records[0]['tax_type']=='With In Gujarat')
										$tax_name_data='With In Gujarat';
									else
										$tax_name_data=$records[0]['tax_type'];
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										$newPircegress =$this->numberFormate(($newPircegress-($newPircegress*$records[0]['discount']/100)),$num_format);
										
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' cadauna tasse incluse.';	
										}
										else
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['igst'].'% IGST + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').' cadauna tasse incluse.';	

										}
									}						
									if($dat['shipment_country_id'] == '111')
									{
										$taxvaluegress = '';
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
											$taxvaluegress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' cadauna tasse incluse.';

										}
										else
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
											$taxvaluegress = ' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').' cadauna tasse incluse.';

										}
										
									}
									if($k=='air')
									{
										$gp=$records[0]['gress_per'];
										$txt_tarnsport='Inclusa spedizione via aereo a UK';
										
									}
									if($k=='sea')
									{
										$gp=$records[0]['gress_per'];
										$txt_tarnsport='Inclusa spedizione via mare a UK ';
										
									}
									if($k=='pickup')
									{
										$gp=$records[0]['gress_per'];
										$tax_str='Transportation cost NOT included.';
										$txt_tarnsport='By Pickup ';
									}
									if($dat['product_id'] != '10')
										$bag='piu o meno '.$plus_minus_quantity;
									else
										$bag='';
									if(strpos($size,'Custom')!==false)
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,3);
										$newextra_profit = $newPriceGress_val;
										
									}
									else
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,3);
										$newextra_profit = $newPriceGress_val;
									}
									if($secondary_curr!='')
									{
										$selCurrency_currency_code=$secondary_curr;
										$newextra_profit=$newextra_profit*$sec_currency_rate;
										if($k=='air')
        							        $newextra_profit =(($newextra_profit / 0.7) * 1.25) ;
        							    if($k=='sea')
        							        $newextra_profit =(($newextra_profit / 0.6) * 1.25);
									}
									else
									{
										$selCurrency_currency_code=$selCurrency['currency_code'];
										$newextra_profit=$newextra_profit;
								
									}
									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prezzo : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($newextra_profit),$num_format).' '.$taxvaluegress.'<b> { Per   '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' Prezzo lordo '.$gp.' % </span><br><br></td></tr>';	
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										if($dat['product_id'] != '10')
											$bag='piu o meno '.$plus_minus_quantity;
										else
											$bag='';
										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Sconto ('.$records[0]['discount'].' %) : '.$selCurrency_currency_code.' </b></td><td> '.$this->numberFormate(($newextra_profit),$num_format).' '.$taxvaluedisgress.'<b> { Per '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' Prezzo lordo '.$gp.' % </span></td></tr>';
									}
								}	
							}
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							$newPirce = $newPirce/$qty;
							$taxvalue='';
							
							if($records[0]['tax_type']=='With In Gujarat')
								$tax_name_data='With In Gujarat';
							else
								$tax_name_data=$records[0]['tax_type'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),$num_format);
								
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' +  ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' cadauna tasse incluse.';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst']+$records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST'.' + ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' cadauna tasse incluse.';											

								}
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$taxvalue = '';
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' +  ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 bolsa (Incluye IVA).';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst']+$records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST'.' + ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 bolsa (Incluye IVA).';											

								}
							}
							
							if($dat['product_id'] != '10')
									$bag='piu o meno '.$plus_minus_quantity;
								else
									$bag='';
							if($k=='air')
							{
								$gp=$records[0]['gress_per'];
								$txt_tarnsport='Inclusa spedizione via aereo a UK';
								
							}
							if($k=='sea')
							{
								$gp=$records[0]['gress_per'];
								$txt_tarnsport='Inclusa spedizione via mare a UK ';
								
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
							if(strpos($size,'Custom')!==false)
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							else
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							
							if($secondary_curr!='')
							{
								$selCurrency_currency_code=$secondary_curr;
								$extra_profit=$extra_profit*$sec_currency_rate;
								if($k=='air')
							        $extra_profit =(($extra_profit / 0.7) * 1.25) ;
							    if($k=='sea')
							        $extra_profit =(($extra_profit / 0.6) * 1.25);
							}
							else
							{
								$selCurrency_currency_code=$selCurrency['currency_code'];
								$extra_profit=$extra_profit;
							
							}
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prezzo : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($extra_profit),$num_format).' cad. '.$taxvalue.'<b> { Per  '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag='piu o meno '.$plus_minus_quantity;
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($extra_profit),$num_format).' cad. '.$taxvaluedis.'<b> { Per  '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
							}
							
						}
						
						$pr = 'busta';	
				        if($dat['product_id'] == '6')
					        $pr = 'bobina';
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Tipo di '.$pr.' : </b> '.$product_nm_new['product_name_italian'];
					
					
					
					
					if($dat['product_id'] != '10'  && $dat['product_id'] != '6' )
						$m.=str_replace('<br>',' ',$records[0]['email_text']);
						
					$m.='&nbsp;&nbsp;Tipo di '.$pr.' - '.$records[0]['make_name_italian'].'</td></tr>';
					}
					$html_gress .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Misure : </b>'.$size.'</td></tr> ';
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Materiale : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					if(isset($materialData) && !empty($materialData))
					{   
						$materialStr = '';
						/*if($records[0]['make_id']=='6')
						{
								$j='1';
								for($gi=0;$gi<count($materialData);$gi++)
								{
									if($materialData[$gi]['material_id']!='16' && $records[0]['make_id']=='6')
									{
										if($j=='2')
										{
											$materialData[$gi]['material_name_italian'] = 'oxo-Biodegradable PE';
											$materialData[$gi]['material_thickness'] = '80';
										}
										$mat_nam = $this->query("SELECT material_name_italian FROM product_material WHERE 	material_name = '".$materialData[$gi]['material_name']."'");
										$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $mat_nam->row['material_name_italian'] .' / ';
									}
									$j++;
								}
						}
						else
						{*/
							for($gi=0;$gi<count($materialData);$gi++)
							{
								$mat_nam = $this->query("SELECT material_name_italian FROM product_material WHERE 	material_name = '".$materialData[$gi]['material_name']."'");
								$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $mat_nam->row['material_name_italian'] .' / ';
							}
					//	}
						$html_gress .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html_gress .= '</tr>';
					$num_format = 3;
					
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Paese di destinazione : </b> '.$shippingCountry['country_name'].' </td></tr>';
					$html_gress .=$m;
					if($records[0]['gusset_printing_type']=='Front & Back  +  No Gusset Printing')
						$records[0]['gusset_printing_type']= 'Stampa Fronte + retro';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Side Gusset Printing')	
					    $records[0]['gusset_printing_type']= 'Stampa Fronte + retro +  soffietti laterali';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Bottom / Side Gusset Printing')
				    	$records[0]['gusset_printing_type']= 'Stampa Fronte + retro + fondo + soffietti';
					else
						$records[0]['gusset_printing_type']= 'Stampa Fronte + retro + fondo';
					
					
					$html_gress .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Finitura : </b>'.$records[0]['printing_effect'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>';
					
					if($dat['product_id'] != '6')
					    $html_gress.='<br/>Superficie di Stampa : '.$records[0]['gusset_printing_type'];
					    
					$html_gress .='</td></tr>';
					
					if($addedByInfo['country_id']==155)
						$txt='[Precio para el Cliente]';
					else
						$txt='';
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html_gress .=$pmq;
					$html_gress .=$gress;
					$html_gress .=$pmq_gress;
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$cylinder_price=$cylinder_price*$sec_currency_rate;
							$gress_cylinder_price = $gress_cylinder_price * $sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$cylinder_price=$cylinder_price;
							$gress_cylinder_price = $gress_cylinder_price;
						}
						
						if((int)$cylinder_price==(int)$gress_cylinder_price){
						    $gress_cyli = $gress_cylinder_price;}
						else{
						    $gress_cyli = $cylinder_price-$gress_cylinder_price;}
						
						
						$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Prezzo per cilindro di stampa  : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($cylinder_price),$num_format).'<b style="color:blue"> &nbsp; a colore </b><br>
					                   <b>&nbsp;&nbsp;&nbsp;&nbsp; Prezzo per cilindro di stampa: </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($gress_cyli),$num_format).'<b style="color:blue"> &nbsp; a colore </b></td></tr>';
						if($cylinder_price_withtax != '0.000')
							$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo a cilindro tasse incluse : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($cylinder_price_withtax),$num_format).'<b style="color:blue"> &nbsp; a colore </b></td></tr>';
				
					if(isset($tool_price) && $tool_price>0.000)
					{
					
						if($secondary_curr!='')
						{
							
							$selCurrency_currency_code=$secondary_curr;
							$tool_price=$tool_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$tool_price=$tool_price;
						}
						
						$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo per stampo extra : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price),$num_format).'. Questo costo è dovuto alla misura particolare di larghezza e/o soffietto.</td></tr>';
						if($tool_price_withtax != '0.000')
							$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Costo per stampo extra tasse incluse : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price_withtax),$num_format).' <b style="color:blue"> &nbsp; a colore </b></td></tr>';
					}
					$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					}
					$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html_gress .= '</table>';
				
				$html_gress .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b><br/>Pagamento : bonifico anticipato</div>';
			
				
				if($cr['user']==1 || $cr['user']==2)
				{
					
					if($cr['user']==1)
					{
						
						$new_html=$html_gress;
						$email_temp[]=array('html'=>$new_html,'email'=>($dat['customer_email'])?$dat['customer_email']:ADMIN_EMAIL);
					}
					if($cr['user']==2)
					{
						if(!empty($menu_id))
						{
							$new_html=$html_gress;
						
						}
						else
						{
							$new_html=$html;
						
						}	
						$email_temp[]=array('html'=>$new_html,'email'=>$addedByInfo['email']);
						
					}
				}
				if($toEmail!='' && $cr['user']!=0)
				{
					if(!empty($menu_id))
					{
						$new_html=$html_gress;
					
					}
					else
					{
						$new_html=$html;
					
					}	
					$email_temp[]=array('html'=>$new_html,'email'=>$toEmail);
					
				}
				if($cr['user']==0)
				{
					
					$new_html=$html_gress;
					$email_temp[]=array('html'=>$new_html,'email'=>ADMIN_EMAIL);
					if($data[0]['added_by_user_type_id']==2)
					{
						$admininfo = $this->getUser($addedByInfo['user_id'],'4');	
						$email_temp[]=array('html'=>$new_html,'email'=>$admininfo['email']);
					}
					
				}
				
				$html='<table border="0px">';
				if($secondary_curr!='')
				{
				    $html.='';
				   
				}
				$html_gress='<table border="0px">';
				if($secondary_curr!='')
				{
				    $html_gress.='';
				}
			}
		   
		    
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(1); 
			$formEmail = $addedByInfo['email'];							
			$firstTimeemial = 0;
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
			$path = HTTP_SERVER."template/product_quotation.html";
			$output = file_get_contents($path);  
			$search  = array('{tag:header}','{tag:details}');
			$signature = 'Thanks.';
			if($addedByInfo['email_signature'])
				$signature = nl2br($addedByInfo['email_signature']);
			
			foreach($email_temp as $val)
			{
				if($toEmail == '')
				{
					$toEmail = $formEmail;
					$firstTimeemial = 1;
				}				
				$subject = $sub;
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
				send_email($val['email'],$formEmail,$subject,$message,'');
				
			}
		}
		
		
	}
	public function sendQuotationEmailInSpanishUK($quotation_id,$toEmail = '',$setQuotationCurrencyId='',$secondary_curr='',$sec_currency_rate=''){
	    
	    
	    		
				
		$getData = ' product_quotation_id,pq.layer, pq.added_by_user_id, pq.added_by_user_type_id, customer_name, customer_gress_percentage, customer_email, shipment_country_id, multi_quotation_number,pq.multi_product_quotation_id, quotation_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,quantity_type,layer,added_by_country_id, currency, currency_id, currency_price, pq.date_added, cylinder_price,tool_price, customer_email,pq.status,discount,customer_gress_percentage,gress_percentage,gress_air,gress_sea ';
		$data = $this->getQuotation($quotation_id,$getData);
		foreach($data as $dat)
	    {
			 $qdata= $this->getQuotationQuantity($dat['product_quotation_id']);
			 if($qdata!='')
			  $quantityData[] =$qdata;
	    }
		$product_nm_new =$this->getProduct($dat['product_id']);
		
	    $str=$product_nm_new['product_name_spanish_uk'];
		
		$menu_id = $this->getMenuPermission(151,$_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		$user_admin_id=$this->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
		
		
		
		$menu_admin_permission='';
		if($_SESSION['LOGIN_USER_TYPE']!='4')
		{
			$menu_admin_permission=$this->getMenuPermission(151,$user_admin_id['user_id'],4);
			
		}
		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$product_nm_new['product_name']),0,3));		
		$sub =$dat['multi_quotation_number'] .' - '.ucwords($dat['customer_name']).' - Impresión Personalizada '.$first;
		$gussetvalue='';$s='';$sub2='';$m_k='';
	
		foreach($quantityData as $k=>$qty_data)
		{
			foreach($qty_data as $tag=>$qty)
			{
				foreach($qty as $q=>$arr)
				{
					if($arr[0]['gusset'] == '')
					{
						$gussetval = 'sin fuelle';
					}
					else
					{
						if($dat['product_id'] == '7')
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm + '.$arr[0]['gusset'].'mm Fuelle de fondo }';
						}
						elseif($arr[0]['gusset'] == '0')
						{
							 $gussetval = ' ';	
						}
						else
						{
							$gussetval = ' { '. $arr[0]['gusset'].'mm + '.
							$arr[0]['gusset'].'mm Fuelle}';
						}
					}
					if($dat['product_id'] != '10')
					{
						if($arr[0]['volume']!='')
						{ 
							if($s!=$qty)
							{
								$sub2.=$arr[0]['volume'].' , ';
								$s=$qty;
							}
							$gussetvalue.=$arr[0]['volume'].' , ';
						}
					}
					if($gussetvalue=='')
						$gussetvalue=' Ancho : '.(int)$arr[0]['width'].'mm  x '.' Alto : '.(int)$arr[0]['height'].'mm '.$gussetval;
					if($arr[0]['volume']=='')
						$arr[0]['volume']='Personalizado';
					
					if($dat['product_id'] != '10')
						$key='Ancho : '.(int)$arr[0]['width'].'mm x '.' Alto : '.(int)$arr[0]['height'].'mm '.$gussetval.' ['.$arr[0]['volume'].']';
					else
						$key='Ancho : '.(int)$arr[0]['width'].'mm x '.' Alto : '.(int)$arr[0]['height'].'mm '.$gussetval;
						$m_k[$arr[0]['product_quotation_quantity_id']]='';
						
					$make=$arr[0]['make_name_spanish_uk'];
					//printr($arr[0]['valve_txt']);
					$make_id=$arr[0]['make_id'];
				
					$new_data[$arr[0]['text']][$key.' ('.$arr[0]['layer'].') '][$q.'('.$arr[0]['product_quotation_quantity_id'].')'][$tag][]=$arr[0];
					if($dat['product_id'] != '10')
					{
						$zip_nm = $this->query("SELECT zipper_name_spanish_uk FROM product_zipper WHERE zipper_name = '".$arr[0]['zipper_txt']."'");
						if($arr[0]['valve_txt']=='No Valve')
							$arr[0]['valve_txt'] = 'Sin valvula';
						else	
							$arr[0]['valve_txt'] = 'Con valvula';
						$sub1= ' '.$zip_nm->row['zipper_name_spanish_uk'].' '.$arr[0]['valve_txt'].''.$sub2; 
					}
					else
						$sub1= $sub2; 
				}
			}	
			$n[$tag][]=array('width'=>$arr[0]['width'],'height'=>$arr[0]['height'],'gusset'=>$arr[0]['gusset'],'volume'=>$arr[0]['volume'],'make'=>$arr[0]['make']);
			$t=$tag;
		}
		$addedByInfo = $this->getUser($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
		
		$sub1= substr($sub1,0,-3);
		$sub=$sub.$sub1;
		$html='';$tax_str='';$html_gress='';
		
		if($addedByInfo)
		{
			$pqcquery='';
			$selCurrency = $this->getQuotationCurrecy($setQuotationCurrencyId,1);
			if($selCurrency)
				$pqcquery = " product_quotation_currency_id = '".$selCurrency['product_quotation_currency_id']."', ";
			$currency_rate[]=array('currency_rate'=>1,'user'=>0);
			if($dat['customer_email'] != '' || $toEmail != '')
				$currency_rate[]=array('currency_rate'=>($selCurrency['currency_rate']!='')?$selCurrency['currency_rate']:1,'user'=>1);
			else
				$currency_rate[]=array('currency_rate'=>1,'user'=>2);
				
			$html .='<table border="0px">';
			if($secondary_curr!='')
			{
				$html.='';
			}
			$html_gress .='<table border="0px">';
			if($secondary_curr!='')
			{
				    $html_gress.='';
			}
			foreach($currency_rate as $cr)
			{
				$gettermsandconditions = $this->gettermsandconditions($dat['added_by_user_id'],$dat['added_by_user_type_id']);
				$shippingCountry = $this->getCountry($dat['shipment_country_id']);
				
				$html .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				foreach($new_data as $key=>$value)
				{
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{
									if($secondary_curr!='')
        							{
        								
        								$selCurrency_currency_code=$secondary_curr;
        							}
        							else
        							{
        								$selCurrency_currency_code=$selCurrency['currency_code'];

        							}
        							$num_format = 3;
									
								
								$zip_nm = $this->query("SELECT zipper_name_spanish_uk FROM product_zipper WHERE zipper_name = '".$records[0]['zipper_txt']."'");
								$spt_nm = $this->query("SELECT spout_name_spanish_uk FROM product_spout WHERE spout_name = '".$records[0]['spout_txt']."'");
								if($records[0]['accessorie_txt']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_spanish_uk FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt']."'");
									$records[0]['accessorie_txt'] = $acc_nm->row['product_accessorie_name_spanish_uk'];
								}
								if($records[0]['accessorie_txt_corner']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_spanish_uk FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt_corner']."'");
									$records[0]['accessorie_txt_corner'] = $acc_nm->row['product_accessorie_name_spanish_uk'];
								}
								if($records[0]['valve_txt']=='No Valve')
									$records[0]['valve_txt'] = 'Sin valvula';
								else	
									$records[0]['valve_txt'] = 'Con valvula';
								
								$eff_nm = $this->query("SELECT 	effect_name_spanish_uk FROM " . DB_PREFIX . "printing_effect WHERE effect_name = '".$records[0]['printing_effect']."' ");
								$records[0]['printing_effect'] = $eff_nm->row['effect_name_spanish_uk'];
								
								if($records[0]['laser_type']=='No Laser Scoring')
								    $laser_type ='Sin Incisión Laser';
							    else if($records[0]['laser_type']=='ZIg Zag Scoring')
							        $laser_type ='incisión en zig zag';
							    else
							        $laser_type ='Con Incisión Laser';
							        
							        
							     
							    if($records[0]['slider_place']!='')
        						{
        						    
            						if($records[0]['slider_place'] == 'Slider Zipper On Top Of Pouch')
            						{
            						    if($records[0]['tamperevident']=='With Tamper Evident')
            						        $records[0]['tamperevident'] = 'con precinto de seguridad';
            						    else
            						        $records[0]['tamperevident'] = 'sin precinto de seguridad';
            						        
            						    $records[0]['slider_place'] = 'zipper en la parte superior de la bolsa';
            						    $tamper = $records[0]['slider_place'].' '.$records[0]['tamperevident'];
            						}
            						else
            						{
            						    $records[0]['slider_place'] = 'zipper en el interior de la bolsa';
            						    $tamper = $records[0]['slider_place'];
            						}
        						}
							        
								$records[0]['email_text'] = $zip_nm->row['zipper_name_spanish_uk'].' , '.$laser_type.' '.$records[0]['valve_txt'].'<br> '.$spt_nm->row['spout_name_spanish_uk'].' , '.$records[0]['accessorie_txt'] .' , '.$records[0]['accessorie_txt_corner'] .' , '.$tamper;
								if($k == "air") 
								{
									$color = "red";	
								}elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
							if($selCurrency)
							{
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code'],$user_admin_id['international_branch_id']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$cylinder_price = $cylinder_base_price;	
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
									}
									else
										$cylinder_price = $cylinder_currency_price;	
								}
								else
									$cylinder_price = $cylinder_currency_price;	
							 }
							 else
							 {
								$selCurrency['currency_code'] = $dat['currency'];
								$cylinder_price = $records[0]['cylinder_price'];
								$tool_price = $records[0]['tool_price'];
							 }
							 $cylinder_price_withtax = $records[0]['cylinder_price_withtax'];
							 $tool_price_withtax = $records[0]['tool_price_withtax'];
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
							
							$qty_type = ''; $bag_types='bolsas'; $bag_type='bolsa';
    							if($dat['product_id'] == '6'){
    							    $bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
						
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							$newPirce = $newPirce/$qty;
							$taxvalue='';
							
							if($records[0]['tax_type']=='With In Gujarat')
								$tax_name_data='With In Gujarat';
							else
								$tax_name_data=$records[0]['tax_type'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
								
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').'  por 1 '.$bag_type.' incluyendo todos los impuestos.';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').'  por 1 '.$bag_type.' incluyendo todos los impuestos.';											

								}
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$taxvalue = '';
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$final = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvalue = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given )  = <b style="color:blue">'.$dat['currency'].' '.round($final,'3').'  por 1 '.$bag_type.' incluyendo todos los impuestos.';

								}
								else
								{
									$final = $newPirce + ($newPirce *($records[0]['igst'])/100);
									$taxvalue =' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given )  = <b style="color:blue">'.$dat['currency'].' '.round($final,'3').'  por 1 '.$bag_type.' incluyendo todos los impuestos.';

								}
							}
							if($dat['product_id'] != '10')
									$bag='bags más o menos '.$plus_minus_quantity.' '.$bag_types;
								else
									$bag='';
							if($k=='air')
							{
							
								$txt_tarnsport='Entrega a puerta, vía aérea al Reino Unido (Envío Express)';
								
							}
							if($k=='sea')
							{
								$txt_tarnsport='Incluído costo al puerto de Reino Unido ( Entrega ordinaria)';
								
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
							if(strpos($size,'Custom')!==false)
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							else
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							
							if($secondary_curr!='')
							{
								
								$selCurrency_currency_code=$secondary_curr;
								$extra_profit=$extra_profit*$sec_currency_rate;
							    if($k=='air')
							        $extra_profit =(($extra_profit / 0.7) * 1.25) ;
							    if($k=='sea')
							        $extra_profit =(($extra_profit / 0.6) * 1.25);
							}
							else
							{
								$selCurrency_currency_code=$selCurrency['currency_code'];
								$extra_profit=$extra_profit;
							
							}
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($extra_profit),$num_format).'  por 1 '.$bag_type.' '.$taxvalue.'<b> { Para '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span><br><br></td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag='bags más o menos '.$plus_minus_quantity.' '.$bag_types;
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Descuento ('.$records[0]['discount'].' %) : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($extra_profit),$num_format).' por 1 '.$bag_type.' '.$taxvaluedis.'<b> { Para '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
							}
							
						}
						$pr = 'Tipo de Bolsa';	
				        if($dat['product_id'] == '6')
					        $pr = 'composición de la bobina';
					        
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;'.$pr.' : </b> Impresión Personalizada '.$product_nm_new['product_name_spanish_uk'];
					if($dat['product_id'] != '10' && $dat['product_id'] != '6')
						$m.=str_replace('<br>',' ',$records[0]['email_text']);
					
					if($dat['product_id'] == '6')	
					    $m.='&nbsp;&nbsp;composición de la bobina - '.$records[0]['make_name_spanish_uk'].'</td></tr>';
					else
					    $m.='&nbsp;&nbsp;Tipo de bolsa - '.$records[0]['make_name_spanish_uk'].'</td></tr>';
					}
					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Dimensiones : </b>'.$size.'</td></tr> ';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Composición : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					
					
					if(isset($materialData) && !empty($materialData))
					{   
						$materialStr = '';
						/*if($records[0]['make_id']=='6')
						{
								$j='1';
								for($gi=0;$gi<count($materialData);$gi++)
								{
									if($materialData[$gi]['material_id']!='16' && $records[0]['make_id']=='6')
									{
										if($j=='2')
										{
											
											$materialData[$gi]['material_name_spanish_uk'] = 'PE biodegradabile';
											$materialData[$gi]['material_thickness'] = '80';
										}
										$mat_nam = $this->query("SELECT material_name_spanish_uk FROM product_material WHERE 	material_name = '".$materialData[$gi]['material_name']."'");
										$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $mat_nam->row['material_name_spanish_uk'] .' / ';
									}
									$j++;
								}
						}
						else
						{*/
							for($gi=0;$gi<count($materialData);$gi++)
							{
								$mat_nam = $this->query("SELECT material_name_spanish_uk FROM product_material WHERE 	material_name = '".$materialData[$gi]['material_name']."'");
								$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '.$mat_nam->row['material_name_spanish_uk'] .' / ';
							}
						//}
						$html .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html .= '</tr>';
					
				
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; País de entrega : </b> '.$shippingCountry['country_name'].' </td></tr>';
					$html .=$m;
					if($records[0]['gusset_printing_type']=='Front & Back  +  No Gusset Printing')
						$records[0]['gusset_printing_type']= 'Frontal y trasera + SIN impresión fuelle base';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Side Gusset Printing')	
					    $records[0]['gusset_printing_type']= 'Frontal y trasera +impresión fuelle lateral';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Bottom / Side Gusset Printing')
				    	$records[0]['gusset_printing_type']= 'Frontal y trasera +impresión fuelle base/lateral';
					else
						$records[0]['gusset_printing_type']= 'Frontal y trasera +impresión fuelle base';
					
					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Efecto de impresión : </b>'.$records[0]['printing_effect'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>';
					
					if($dat['product_id'] != '6')
					    $html.='<br/>Tipo de impresión en el fuelle : '.$records[0]['gusset_printing_type'];
					
					$html .='</td></tr>';
					
					if($addedByInfo['country_id']==155)
						$txt='[Tu precio de compra]';
					else
						$txt='';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html .=$pmq;
					$html .=$gress;
					$html .=$pmq_gress;
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$cylinder_price=$cylinder_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$cylinder_price=$cylinder_price;
						}
						
						
						
						$html .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Precio Cilindro  : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($cylinder_price),$num_format).'<b style="color:blue"> &nbsp; Por Color </b></td></tr>';
						if($cylinder_price_withtax != '0.000')
							$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; 
precio del cilindro con impuestos : </b> '.$this->numberFormate(($cylinder_price_withtax),$num_format).'<b style="color:blue"> &nbsp; Por Color </b></td></tr>';
					
					if(isset($tool_price) && $tool_price>0.000)
					{
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$tool_price=$tool_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$tool_price=$tool_price;
						}
						
						$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Precio de herramienta extra : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price),$num_format).'. Estos costes son aplicable porque el ancho/fuelle base tiene una medida particular. Por lo tanto el precio extra de herramienta aplica.</td></tr>';
						if($tool_price_withtax != '0.000')
							$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Precio con impuestos de herramienta extra : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price_withtax),$num_format).' <b style="color:blue"> &nbsp; Por Color </b></td></tr>';
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					}
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html .= '</table>';
				$html .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b>Condiciones de pago: anticipado</div>';
		
				$html_gress .= '<style> table, th, td </style>';
				$i=0;$pmq='';$c=0;$pmq_gress='';$gress='';$gp='';
				foreach($new_data as $key=>$value)
				{
					foreach($value as $size=>$qty_data)
					{
						(int)$size= preg_replace("/\([^)]+\)/","",$size);
					foreach($qty_data as $qty=>$transport)
					{
					(int)$qty= preg_replace("/\([^)]+\)/","",$qty);
						foreach($transport as $k=>$records)
						{
								$zip_nm = $this->query("SELECT zipper_name_spanish_uk FROM product_zipper WHERE zipper_name = '".$records[0]['zipper_txt']."'");
								$spt_nm = $this->query("SELECT spout_name_spanish_uk FROM product_spout WHERE spout_name = '".$records[0]['spout_txt']."'");
								if($records[0]['accessorie_txt']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_spanish_uk FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt']."'");
									$records[0]['accessorie_txt'] = $acc_nm->row['product_accessorie_name_spanish_uk'];
								}
								if($records[0]['accessorie_txt_corner']!='')
								{
									$acc_nm = $this->query("SELECT product_accessorie_name_spanish_uk FROM product_accessorie WHERE product_accessorie_name = '".$records[0]['accessorie_txt_corner']."'");
									$records[0]['accessorie_txt_corner'] = $acc_nm->row['product_accessorie_name_spanish_uk'];
								}
								
								if($records[0]['valve_txt']=='No Valve')
									$records[0]['valve_txt'] = 'Sin valvula';
								else	
									$records[0]['valve_txt'] = 'Con valvula';
								
								$eff_nm = $this->query("SELECT 	effect_name_spanish_uk FROM " . DB_PREFIX . "printing_effect WHERE effect_name = '".$records[0]['printing_effect']."' ");
								
								if($records[0]['slider_place']!='')
        						{
        						    
            						if($records[0]['slider_place'] == 'Slider Zipper On Top Of Pouch')
            						{
            						    if($records[0]['tamperevident']=='With Tamper Evident')
            						        $records[0]['tamperevident'] = 'con precinto de seguridad';
            						    else
            						        $records[0]['tamperevident'] = 'sin precinto de seguridad';
            						        
            						    $records[0]['slider_place'] = 'zipper en la parte superior de la bolsa';
            						    $tamper = $records[0]['slider_place'].' '.$records[0]['tamperevident'];
            						}
            						else
            						{
            						    $records[0]['slider_place'] = 'zipper en el interior de la bolsa';
            						    $tamper = $records[0]['slider_place'];
            						}
        						}
								
							    if($records[0]['laser_type']=='No Laser Scoring')
								    $laser_type ='Sin Incisión Laser';
							    else if($records[0]['laser_type']=='ZIg Zag Scoring')
							        $laser_type ='incisión en zig zag';
							    else
							        $laser_type ='Con Incisión Laser';
							        
								$records[0]['printing_effect'] = $eff_nm->row['effect_name_spanish_uk'];
								
								$records[0]['email_text'] = $zip_nm->row['zipper_name_spanish_uk'].' , '.$laser_type.' '.$records[0]['valve_txt'].'<br> '.$spt_nm->row['spout_name_spanish_uk'].' , '.$records[0]['accessorie_txt'] .' , '.$records[0]['accessorie_txt_corner'] .' , '.$tamper;
								
								//$records[0]['email_text'] = $zip_nm->row['zipper_name_spanish_uk'].' , '.$records[0]['valve_txt'].'<br> '.$spt_nm->row['spout_name_spanish_uk'].' , '.$records[0]['accessorie_txt'] ;
								if($k == "air") 
								{
									$color = "red";	
								}
								elseif($k == "sea")
								{
									$color = "blue";	
								}
								elseif($k == "pickup")
								{
									$color = "green";
								}
							if($selCurrency)
							{
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code'],$user_admin_id['international_branch_id']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$gress_cylinder_price = $gress_cylinder_price * $sec_currency_rate;
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
									{
										$userInfo = $this->getUserInfo($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
										$cylinder_price = $cylinder_base_price;
										$cyliCurrencyPricenew = (3000 / $cr['currency_rate']);
                						if($dat['product_id']=='7')
                						    $cylinder_price=$cylinder_price+$cyliCurrencyPricenew;
										$gress_cylinder_price=$cylinder_price;
									}
									else
										$cylinder_price = $cylinder_currency_price;	
								}
								else
									$cylinder_price = $cylinder_currency_price;	
							 }
							 else
							 {
								$selCurrency['currency_code'] = $dat['currency'];
								$cylinder_price = $records[0]['cylinder_price'];
								$gress_cylinder_price = $records[0]['cyli_gress_price'];
								$tool_price = $records[0]['tool_price'];
							 }
							 $cylinder_price_withtax = $records[0]['cylinder_price_withtax'];
							 $tool_price_withtax = $records[0]['tool_price_withtax'];
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
								
							$qty_type = ''; $bag_types='bolsas'; $bag_type='bolsa';
                        	if($dat['product_id'] == '6'){
                        		$bag_type = $qty_type = $records[0]['quantity_type'];$bag_types = $records[0]['quantity_type'].'s';}
							
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type,$qty_type); 
						
							if(!empty($menu_id) || !empty($menu_admin_permission))
							{
						
								if($records[0]['gress_percentage'] != '0.000' OR $records[0]['gress_air'] !='0.000' OR $records[0]['gress_sea'] !='0.000')
								{
								
									if($dat['shipment_country_id']==91)
										$txt='[Prezzo finale di vendita]';
									else
										$txt='';
									$gress = '<tr><td colspan="2" >&nbsp;</td></tr><tr valign="top"><td width="60" colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Percentage Prices:'.$txt.'</b></td></tr>';								
									$newPircegress = ((($records[0]['totalPrice'] - $records[0]['customerGressPrice'] - $records[0]['gress_price']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
									$newPircegress = $newPircegress/$qty;
									$taxvaluegress='';
									
									if($records[0]['tax_type']=='With In Gujarat')
										$tax_name_data='With In Gujarat';
									else
										$tax_name_data=$records[0]['tax_type'];
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										$newPircegress =$this->numberFormate(($newPircegress-($newPircegress*$records[0]['discount']/100)),$num_format);
										
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').'  por 1 '.$bag_type.' incluyendo todos los impuestos.';	
										}
										else
										{
											$finaldisgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
										
											$taxvaluedisgress = ' + '.$records[0]['igst'].'% IGST + ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finaldisgress,'3').'  por 1 '.$bag_type.' incluyendo todos los impuestos.';	

										}
									}						
									if($dat['shipment_country_id'] == '111')
									{
										$taxvaluegress = '';
										if($records[0]['tax_type']=='With In Gujarat')
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['cgst']+$records[0]['sgst'])/100);
											$taxvaluegress = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').'  por 1 '.$bag_type.' incluyendo todos los impuestos.';

										}
										else
										{
											$finalgress = $newPircegress + ($newPircegress *($records[0]['igst'])/100);
											$taxvaluegress = ' + '.$records[0]['igst'].'% IGST ('.$tax_name_data.' Given ) = '.$dat['currency'].' '.round($finalgress,'3').'  por 1 '.$bag_type.' incluyendo todos los impuestos.';

										}
										
									}
									if($k=='air')
									{
										$gp=$records[0]['gress_per'];
										$txt_tarnsport='Entrega a puerta, vía aérea al Reino Unido (Envío Express) ';
										
									}
									if($k=='sea')
									{
										$gp=$records[0]['gress_per'];
										$txt_tarnsport='Incluído costo al puerto de Reino Unido ( Entrega ordinaria) ';
										
									}
									if($k=='pickup')
									{
										$gp=$records[0]['gress_per'];
										$tax_str='Transportation cost NOT included.';
										$txt_tarnsport='By Pickup ';
									}
									if($dat['product_id'] != '10')
										$bag='bags más o menos '.$plus_minus_quantity.' bolsas ';
									else
										$bag='';
									if(strpos($size,'Custom')!==false)
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,3);
										$newextra_profit = $newPriceGress_val;
										
									}
									else
									{
										$newPriceGress_val = $this->numberFormate($newPircegress ,3);
										$newextra_profit = $newPriceGress_val;
									}
									if($secondary_curr!='')
									{
										$selCurrency_currency_code=$secondary_curr;
										$newextra_profit=$newextra_profit*$sec_currency_rate;
										if($k=='air')
        							        $newextra_profit =(($newextra_profit / 0.7) * 1.25) ;
        							    if($k=='sea')
        							        $newextra_profit =(($newextra_profit / 0.6) * 1.25);
									}
									else
									{
										$selCurrency_currency_code=$selCurrency['currency_code'];
										$newextra_profit=$newextra_profit;
								
									}
									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($newextra_profit),$num_format).' '.$taxvaluegress.'<b> { Para   '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' Precio al mayor '.$gp.' % </span><br><br></td></tr>';	
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										if($dat['product_id'] != '10')
											$bag='bags más o menos '.$plus_minus_quantity.' '.$bag_types;
										else
											$bag='';
										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Descuento ('.$records[0]['discount'].' %) : '.$selCurrency_currency_code.' </b></td><td> '.$this->numberFormate(($newextra_profit),$num_format).' '.$taxvaluedisgress.'<b> { Para '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - '.$txt_tarnsport.'. '.$tax_str.' Precio al mayor '.$gp.' % </span></td></tr>';
									}
								}	
							}
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							$newPirce = $newPirce/$qty;
							$taxvalue='';
							
							if($records[0]['tax_type']=='With In Gujarat')
								$tax_name_data='With In Gujarat';
							else
								$tax_name_data=$records[0]['tax_type'];
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),$num_format);
								
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' +  ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' incluyendo todos los impuestos.';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst']+$records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST'.' + ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' incluyendo todos los impuestos.';											

								}
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$taxvalue = '';
								if($records[0]['tax_type']=='With In Gujarat')
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['cgst']+$records[0]['sgst'])/100);
									$taxvaluedis = ' + '.$records[0]['cgst'].'% CGST'.' + '.$records[0]['sgst'].'% SGST'.' +  ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' incluyendo todos los impuestos.';											
								}
								else
								{
									$finaldis = $newPirce + ($newPirce *($records[0]['igst']+$records[0]['igst'])/100);
									$taxvaluedis = ' + '.$records[0]['igst'].'% IGST'.' + ('.$tax_name_data.' Given )  = '.$dat['currency'].' '.round($finaldis,'3').' por 1 '.$bag_type.' incluyendo todos los impuestos.';											

								}
							}
							
							if($dat['product_id'] != '10')
									$bag='bags más o menos '.$plus_minus_quantity.' '.$bag_types;
								else
									$bag='';
							if($k=='air')
							{
								$gp=$records[0]['gress_per'];
								$txt_tarnsport='Entrega a puerta, vía aérea al Reino Unido (Envío Express) ';
								
							}
							if($k=='sea')
							{
								$gp=$records[0]['gress_per'];
								$txt_tarnsport='Incluído costo al puerto de Reino Unido ( Entrega ordinaria) ';
								
							}
							if($k=='pickup')
							{
								$txt_tarnsport='By Pickup ';
								$tax_str='Transportation cost NOT included.';
							}
							if(strpos($size,'Custom')!==false)
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							else
							{
								$normal_val = $this->numberFormate($newPirce ,3);
								$extra_profit = $normal_val;
							}
							
							if($secondary_curr!='')
							{
								$selCurrency_currency_code=$secondary_curr;
								$extra_profit=$extra_profit*$sec_currency_rate;
								if($k=='air')
							        $extra_profit =(($extra_profit / 0.7) * 1.25) ;
							    if($k=='sea')
							        $extra_profit =(($extra_profit / 0.6) * 1.25);
							}
							else
							{
								$selCurrency_currency_code=$selCurrency['currency_code'];
								$extra_profit=$extra_profit;
							
							}
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Precio : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($extra_profit),$num_format).' por 1 '.$bag_type.' '.$taxvalue.'<b> { Para  '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag='bags más o menos '.$plus_minus_quantity.' '.$bag_types;
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Descuento ('.$records[0]['discount'].' %) : '.$selCurrency_currency_code.' </b> '.$this->numberFormate(($extra_profit),$num_format).' por 1 '.$bag_type.' '.$taxvaluedis.'<b> { Para  '.$qty.' '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> -  '.$txt_tarnsport.'. '.$tax_str.'</span></td></tr>';
							}
							
						}
						
						$pr = 'Tipo de Bolsa';	
                    	if($dat['product_id'] == '6')
                    		$pr = 'composición de la bobina';	
						
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$pr.' : Impresión Personalizada </b> '.$product_nm_new['product_name_italian'];
					
					
					
					
					if($dat['product_id'] != '10' && $dat['product_id'] != '6')
						$m.=str_replace('<br>',' ',$records[0]['email_text']);
					
					if($dat['product_id'] == '6')	
                    	$m.='&nbsp;&nbsp;composición de la bobina - '.$records[0]['make_name_spanish_uk'].'</td></tr>';
                    else
                    	$m.='&nbsp;&nbsp;Tipo de bolsa - '.$records[0]['make_name_spanish_uk'].'</td></tr>';

					}
					$html_gress .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Dimensiones : </b>'.$size.'</td></tr> ';
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Composición : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					if(isset($materialData) && !empty($materialData))
					{   
						$materialStr = '';
						/*if($records[0]['make_id']=='6')
						{
								$j='1';
								for($gi=0;$gi<count($materialData);$gi++)
								{
									if($materialData[$gi]['material_id']!='16' && $records[0]['make_id']=='6')
									{
										if($j=='2')
										{
											$materialData[$gi]['material_name_spanish_uk'] = 'PE Oxo-degradable';
											$materialData[$gi]['material_thickness'] = '80';
										}
										$mat_nam = $this->query("SELECT material_name_spanish_uk FROM product_material WHERE 	material_name = '".$materialData[$gi]['material_name']."'");
										$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $mat_nam->row['material_name_spanish_uk'] .' / ';
									}
									$j++;
								}
						}
						else
						{*/
							for($gi=0;$gi<count($materialData);$gi++)
							{
								$mat_nam = $this->query("SELECT material_name_spanish_uk FROM product_material WHERE 	material_name = '".$materialData[$gi]['material_name']."'");
								$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $mat_nam->row['material_name_spanish_uk'] .' / ';
							}
						//}
						$html_gress .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html_gress .= '</tr>';
					$num_format = 3;
					
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; País de entrega : </b> '.$shippingCountry['country_name'].' </td></tr>';
					$html_gress .=$m;
					if($records[0]['gusset_printing_type']=='Front & Back  +  No Gusset Printing')
						$records[0]['gusset_printing_type']= 'Frontal y trasera + SIN impresión fuelle base';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Side Gusset Printing')	
					    $records[0]['gusset_printing_type']= 'Frontal y trasera +impresión fuelle lateral';
					elseif($records[0]['gusset_printing_type']=='Front & Back + Bottom / Side Gusset Printing')
				    	$records[0]['gusset_printing_type']= 'Frontal y trasera +impresión fuelle base/lateral';
					else
						$records[0]['gusset_printing_type']= 'Frontal y trasera +impresión fuelle base';
					
				
					$html_gress .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Efecto de impresión : </b>'.$records[0]['printing_effect'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>';
					
					if($dat['product_id'] != '6')
					    $html_gress.='<br/>Tipo de impresión en el fuelle : '.$records[0]['gusset_printing_type'];
					
					$html_gress .='</td></tr>';
					
					if($addedByInfo['country_id']==155)
						$txt='[Tu precio de compra]';
					else
						$txt='';
					$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; '.$txt.' </b></td></tr>';
					$html_gress .=$pmq;
					$html_gress .=$gress;
					$html_gress .=$pmq_gress;
					
						if($secondary_curr!='')
						{
							$selCurrency_currency_code=$secondary_curr;
							$cylinder_price=$cylinder_price*$sec_currency_rate;
							$gress_cylinder_price = $gress_cylinder_price * $sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$cylinder_price=$cylinder_price;
							$gress_cylinder_price = $gress_cylinder_price;
						}
						
						if((int)$cylinder_price==(int)$gress_cylinder_price){
						    $gress_cyli = $gress_cylinder_price;}
						else{
						    $gress_cyli = $cylinder_price-$gress_cylinder_price;}
						
						
						$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Precio Cilindro  : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($cylinder_price),$num_format).'<b style="color:blue"> &nbsp; Por Color </b><br>
					                   <b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder Gress price: </b>'.$selCurrency_currency_code.' '.$this->numberFormate(($gress_cyli),$num_format).'<b style="color:blue"> &nbsp; Por Color </b></td></tr>';
						if($cylinder_price_withtax != '0.000')
							$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Precio del cilindro con impuestos : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($cylinder_price_withtax),$num_format).'<b style="color:blue"> &nbsp; Por Color </b></td></tr>';
				
					if(isset($tool_price) && $tool_price>0.000)
					{
					
						if($secondary_curr!='')
						{
							
							$selCurrency_currency_code=$secondary_curr;
							$tool_price=$tool_price*$sec_currency_rate;
						}
						else
						{
							$selCurrency_currency_code=$selCurrency['currency_code'];
							$tool_price=$tool_price;
						}
						
						$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;  Precio de herramienta extra : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price),$num_format).'. Estos costes son aplicable porque el ancho/fuelle base tiene una medida particular. Por lo tanto el precio extra de herramienta aplica.</td></tr>';
						if($tool_price_withtax != '0.000')
							$html_gress .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Precio con impuestos de herramienta extra : </b> '.$selCurrency_currency_code.' '.$this->numberFormate(($tool_price_withtax),$num_format).' <b style="color:blue"> &nbsp; Por Color </b></td></tr>';
					}
					$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$pmq='';
					$pmq_gress='';
					$m='';
					$gress='';
					$i++;
					}
					$html_gress .= '<tr><td colspan="2">&nbsp;</td></tr>';
				}
				$html_gress .= '</table>';
				
				$html_gress .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp;</b><br/>Condiciones de pago: anticipado</div>';
			
				
				if($cr['user']==1 || $cr['user']==2)
				{
					
					if($cr['user']==1)
					{
						
						$new_html=$html_gress;
						$email_temp[]=array('html'=>$new_html,'email'=>($dat['customer_email'])?$dat['customer_email']:ADMIN_EMAIL);
					}
					if($cr['user']==2)
					{
						if(!empty($menu_id))
						{
							$new_html=$html_gress;
						
						}
						else
						{
							$new_html=$html;
						
						}	
						$email_temp[]=array('html'=>$new_html,'email'=>$addedByInfo['email']);
						
					}
				}
				if($toEmail!='' && $cr['user']!=0)
				{
					if(!empty($menu_id))
					{
						$new_html=$html_gress;
					
					}
					else
					{
						$new_html=$html;
					
					}	
					$email_temp[]=array('html'=>$new_html,'email'=>$toEmail);
					
				}
				if($cr['user']==0)
				{
					
					$new_html=$html_gress;
					$email_temp[]=array('html'=>$new_html,'email'=>ADMIN_EMAIL);
					if($data[0]['added_by_user_type_id']==2)
					{
						$admininfo = $this->getUser($addedByInfo['user_id'],'4');	
						$email_temp[]=array('html'=>$new_html,'email'=>$admininfo['email']);
					}
					
				}
				
				$html='<table border="0px">';
				if($secondary_curr!='')
				{
				    $html.='';
				}
				$html_gress='<table border="0px">';
				if($secondary_curr!='')
				{
			        $html_gress.='';
				}
			}
		   
		    
			$obj_email = new email_template();
			$rws_email_template = $obj_email->get_email_template(1); 
			$formEmail = $addedByInfo['email'];							
			$firstTimeemial = 0;
			$temp_desc = str_replace('\r\n',' ',$rws_email_template['discription']);				
			$path = HTTP_SERVER."template/product_quotation.html";
			$output = file_get_contents($path);  
			$search  = array('{tag:header}','{tag:details}');
			$signature = 'Thanks.';
			if($addedByInfo['email_signature'])
				$signature = nl2br($addedByInfo['email_signature']);
			
			foreach($email_temp as $val)
			{
				if($toEmail == '')
				{
					$toEmail = $formEmail;
					$firstTimeemial = 1;
				}				
				$subject = $sub;
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
				send_email($val['email'],$formEmail,$subject,$message,'');
				
			}
		
		
		}
	}
	//END KINJAL
	//[kinjal] done on 10-2-2018
	public function getRollWastage($totalKgs){
		
		$sql = "SELECT wastage_kg FROM " . DB_PREFIX . "product_roll_wastage WHERE from_kg <= '".$totalKgs."' AND 	to_kg >= '".$totalKgs."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["wastage_kg"];
		}else{
			return false;
		}
	}
	
	public function getRollProfit($weight){
		
		$sql = "SELECT profit_kg FROM " . DB_PREFIX . "product_roll_profit WHERE from_kg <= '".$weight."' AND 	to_kg >= '".$weight."' ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["profit_kg"];
		}else{
			return $this->numberFormate(($weight / 15000),"3");
		}
	}
	
	public function getRollPackingPrice($kg){
		
		$sql = "SELECT price_kgs FROM " . DB_PREFIX . "product_roll_packing WHERE from_kgs <= '".$kg."' AND 	to_kgs >= '".$kg."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["price_kgs"];
		}else{
			return false;
		}
	}
	public function getRollTransportPrice($kg){
		
		$sql = "SELECT price_kgs FROM " . DB_PREFIX . "product_roll_transport WHERE from_kgs <= '".$kg."' AND 	to_kgs >= '".$kg."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row["price_kgs"];
		}else{
			return false;
		}
	}
	public function addRollQuotation($data,$meter='',$menual_qty=''){
		//post_height = Repeat Length in roll calcualtion
		//printr($menual_qty);printr($meter);die;
		$post_height = (int)$data['height'];//Repeat Length
		$post_width = (int)$data['width'];
		$product_id = (int)$data['product'];
		$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
		$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		$userInfo = $this->getUserInfo($user_type_id,$user_id);
		$test = array();
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
		//printr($data);
		$productName = getName('product','product_id',$product_id,'product_name');
		if($product_id!='6'){
			return "Error";
		}else
		{	
			//price per 1000 pouch add + 20 in width
			$actualWidth = $this->numberFormate((($post_width + 20) / 1000),"5");
			$actualHeight = $this->numberFormate(($post_height  / 1000),"5");
			
			if($meter=='')
			{
    			if(isset($data['multi_quote_id']) && $data['multi_quote_id']!='')
    			{
    				$multi_product_quotation_id = $data['multi_quote_id'];
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
    							if($data['company_address_id']=='')
    							{
    									$sql2 = "INSERT INTO  company_address  SET  address_book_id = '" . $address_book_id . "',country = '" . $data['country_id'] . "', email_1 = '" . $data['email'] . "' ,user_id='" . $_SESSION['ADMIN_LOGIN_SWISS'] . "' ,user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "' , date_added = NOW(),date_modify = NOW()";
    									$datasql2 = $this->query($sql2);
    							}
    							else
    							{
    									$sql2 = "UPDATE company_address SET country = '" . $data['country_id'] . "',email_1 = '" . $data['email'] . "' WHERE company_address_id ='" . $data['company_address_id'] . "'";
    									$datasql2 = $this->query($sql2);
    							
    							}
    							
    					}
    				$sql =  "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_id SET  status = '0',address_book_id = '".$address_book_id."', date_added = NOW(),date_modify = NOW(), added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."'";		
    				$this->query($sql);
    				$multi_product_quotation_id = $this->getLastId();
    				
    			}
			}
        //printr($multi_product_quotation_id);
			if(isset($data['material']) && !empty($data['material']) && isset($data['thickness']) && !empty($data['thickness'])){
				
				$total_material = count($data['material']);
				$layerPrice = array();
				$materialName = '';
				$checkCppMaterial = 0;
				for($p=0;$p<$total_material;$p++){
					
					$setNumber = $p.'0';
					$addingActualWidth = ( $setNumber / 1000 );
					$newLayerWiseWidth = ( $actualWidth + $addingActualWidth);
					
					
					if(($data['make']=='2' || $data['make']=='6') && $p==0)
    				{
    				        $material = $data['material'][0];
    				        $material_thickness = $data['thickness'][0];
    				        $thicknessPrice_paper = $this->getMaterialThickmessPrice( $material,$material_thickness);
    				        $materialName_paper = getName('product_material','product_material_id',$data['material'][0],'material_name');
    				        $gsm_paper =$this->getMaterialGsm($data['material'][0]);
    				        $layerWiseGsmThickness_paper[$p+1] = $this->getLayerWiseGsmThickness($this->numberFormate(($post_width / 1000),"5"),$this->numberFormate(($post_height / 1000),"5"),$material_thickness,$gsm_paper);
    				        $layerPrice_paper[$p+1] = $this->getLayerPrice($layerWiseGsmThickness_paper[$p+1],$thicknessPrice_paper);
    				        $data['material'][0] = 3;
    				        $data['thickness'][0] = 12;
    				}
					
					
					$checkCppMaterial = $this->checkMaterial($data['material'][$p]);
					
					$gsm[$p+1] = $this->getMaterialGsm($data['material'][$p]);
					$thicknessPrice = $this->getMaterialThickmessPrice($data['material'][$p],$data['thickness'][$p]);
					
					$layerWisseGsmIntoThickness[$p+1] = $this->numberFormate(($gsm[$p+1] * $data['thickness'][$p]),"5");
					
					//##################### PRICE PER KG/1000 POUCH START
					$layerWisePricePerKgGsmThickness[$p+1] = $this->getLayerWiseGsmThickness($this->numberFormate(($post_width / 1000),"5"),$this->numberFormate(($post_height / 1000),"5"),$data['thickness'][$p],$gsm[$p+1]);		
					//##################### PRICE PER KG/1000 POUCH CLOSE
					
					//##################### PRICE PER price /1000 POUCH START
					$layerWisePricePer1000GsmThickness[$p+1] = $this->getLayerWiseGsmThickness($newLayerWiseWidth,$actualHeight,$data['thickness'][$p],$gsm[$p+1]);
					$layerPricePer1000[$p+1] = $this->getLayerPrice($layerWisePricePer1000GsmThickness[$p+1],$thicknessPrice);
					//##################### PRICE PER price /1000 POUCH CLOSE
					
					//####### SET SQL QUERY DATA 
					$materialName = getName('product_material','product_material_id',$data['material'][$p],'material_name');
					if(($data['make']=='2' || $data['make']=='6') && $p==0)
					{
					    $setQueryData[] = " layer = '".(int)($p+1)."', material_id = '".(int)$material."', material_gsm = '".(float)$gsm_paper."', material_thickness = '".$material_thickness."', material_price = '".(float)$thicknessPrice_paper."', material_name = '".$materialName_paper."', layer_wise_gsmthickness = '".(float)$layerWiseGsmThickness_paper[$p+1]."', layer_wise_price = '".(float)$layerPrice_paper[$p+1]."', date_added = NOW()";	
					    $layerWiseGsmThicknessForPaper[$p+1]=$layerWiseGsmThickness_paper[$p+1];
					    $layerWiseGsmThicknessNew[$p+1] = $layerWiseGsmThickness_paper[$p+1];
					}
                    else
                    {
					    $setQueryData[] = " layer = '".(int)($p+1)."', material_id = '".(int)$data['material'][$p]."', material_gsm = '".(float)$gsm[$p+1]."', material_thickness = '".$data['thickness'][$p]."', material_price = '".(float)$thicknessPrice."', material_name = '".$materialName."', layer_wise_gsmthickness = '".(float)$layerWisePricePer1000GsmThickness[$p+1]."', layer_wise_price = '".(float)$layerPricePer1000[$p+1]."', date_added = NOW()";
                        $layerWiseGsmThicknessForPaper[$p+1]=$layerWiseGsmThickness[$p+1];
                        $layerWiseGsmThicknessNew[$p+1] = $layerWisePricePer1000GsmThickness[$p+1];
                    }
					
				}
				
				$test['$gsm']=$gsm;
				$test['$thicknessPrice']=$thicknessPrice;
				$test['$layerWisseGsmIntoThickness']=$layerWisseGsmIntoThickness;
				$test['$layerWisePricePerKgGsmThickness']=$layerWisePricePerKgGsmThickness;
				$test['$layerWisePricePer1000GsmThickness']=$layerWisePricePer1000GsmThickness;
				$test['$layerPricePer1000']=$layerPricePer1000;
				$test['$setQueryData']=$setQueryData;
				$test['$layerWiseGsmThicknessForPaper']=$layerWiseGsmThicknessForPaper;
				$test['$layerWiseGsmThicknessNew']=$layerWiseGsmThicknessNew;
				
				//###### kgs / 1000 pouch
				$priceKgsPer1000 = $this->sumOfNumericArray($layerWisePricePerKgGsmThickness);
				
				$totalLayer = count($data['material']);
				$layerCount = (isset($p))?$p:'';
				
				//total layer price
				$totalLayerPrice = $this->sumOfNumericArray($layerPricePer1000);
				$test['$totalLayerPrice']=$totalLayerPrice;
				$cust_adhesive_mul_by = $cust_ink_mul_by = 0;
				if(isset($data['printing']) && $data['printing'] == 1){

					$printing_option = "With Printing";
					$onlyInkPrice = $this->getInkPrice1($data['make']);//$this->getInkPrice($layerWiseGsmThickness[1],1);
					$inkSolventPrice = $this->getInkSolventPrice($layerWiseGsmThicknessNew[1],1,$data['make']);
					$printingEffectPrice = 0;
					if(isset($data['printing_effect']) && !empty($data['printing_effect']) && (int)$data['printing_effect'] > 0){
						$printingEffectPrice = $this->getPrintingEffectPrice($data['printing_effect']);
					}
					$multi_by='';
					if($data['printing_effect'] == '3')
					{
						//[kinjal]: 11-4-2017 changed to  call this for only matt shiny effect
						$multi_by = $this->getPrintingEffectMultiBy($data['printing_effect']);
						$inkPrice = (($onlyInkPrice * $multi_by) * $layerWiseGsmThicknessNew[1]);
						$test['$inkPrice1']=$inkPrice.'='.$onlyInkPrice.'*'.$multi_b.'*'.$layerWiseGsmThicknessNew[1];
					}
					else
					{
					    $inkPrice = (($onlyInkPrice + $printingEffectPrice) * $layerWiseGsmThicknessNew[1]);
					    $test['$inkPrice1']=$inkPrice.'='.$onlyInkPrice.'+'.$printingEffectPrice.'*'.$layerWiseGsmThicknessNew[1];
					}    
					if($data['make']=='2' || $data['make']=='6')
					{
					    $cust_mul = $this->getCustmul();
					    $inkPrice = $inkPrice * $cust_mul['ink_mul'];
					    $inkSolventPrice = $inkSolventPrice * $cust_mul['ink_mul'];
					    $cust_ink_mul_by = $cust_mul['ink_mul'];
					}
                    
				}else{
					$printing_option = "No Printing";
					$onlyInkPrice = 0;
					$printingEffectPrice = 0;
					$inkPrice = 0;
					$inkSolventPrice = 0;
				}
				
				$test['$onlyInkPrice']=$onlyInkPrice;
				$test['$printingEffectPrice']=$printingEffectPrice;
				$test['$inkPrice']=$inkPrice;
				$test['$inkSolventPrice']=$inkSolventPrice;
				//Adhesive and adhesive solvent
				if($checkCppMaterial == 1 ){
					$make='';
					$va=3;
					$cond=1;
				}else{
					$make=$data['make'];
					$va=5;
					$cond=0;
				}
				//Adhesive and adhesive solvent
				$adhesivePrice = $this->getAdhesivePrice($layerWiseGsmThicknessNew[1],$layerCount,1,$make,$va,$cond);
				$adhesiveSolventPrice = $this->getAdhesiveSolventPrice($layerWiseGsmThicknessNew[1],$layerCount,1,$make,$va,2);
				
				$test['$adhesivePrice']=$adhesivePrice;
				$test['$adhesiveSolventPrice']=$adhesiveSolventPrice;
                if($data['make']=='2' || $data['make']=='6')
				{
				    $adhesivePrice = $adhesivePrice * $cust_mul['adhesive_mul'];
				    $adhesiveSolventPrice = $adhesiveSolventPrice * $cust_mul['adhesive_mul'];
				    $cust_adhesive_mul_by = $cust_mul['adhesive_mul'];
				    $test['$adhesivePrice']=$adhesivePrice .'*'.$cust_mul['adhesive_mul'];
				    $test['$adhesiveSolventPrice']=$adhesiveSolventPrice .'*'.$cust_mul['adhesive_mul'];
				}
				
			
				//START ################ POUCH / KG
					$layerWiseGsmIntoThicknessSum = $this->sumOfNumericArray($layerWisseGsmIntoThickness);
					$test['$layerWiseGsmIntoThicknessSum']=$layerWiseGsmIntoThicknessSum;
					//adding 6 by default
					$layerWiseGsmIntoThicknessSum1 = $this->numberFormate(($layerWiseGsmIntoThicknessSum + 6),"5");
					$test['$layerWiseGsmIntoThicknessSum1']=$layerWiseGsmIntoThicknessSum1.'='.$layerWiseGsmIntoThicknessSum.'+6';
					
					$pouchPerKgFormulla = $this->numberFormate(($post_height * $post_width * $layerWiseGsmIntoThicknessSum1),"5");
					$test['$pouchPerKgFormulla']=$pouchPerKgFormulla.'='.$post_height.'*'.$post_width.'*'.$layerWiseGsmIntoThicknessSum1;
					//after get result 1000 / get result
					//price pouch / kg
					$pouchPerKgFormulla1 = $this->numberFormate(((1000 / $pouchPerKgFormulla ) * 1000000),"5");
					$test['$pouchPerKgFormulla1']=$pouchPerKgFormulla1;

				//CLOSE ################ POUCH / KG
				
				//START ################ PRICE / MTR
					$pricePerMtrFormulaNew = $this->numberFormate(($pouchPerKgFormulla1 * $post_height) ,"5");
					$test['$pricePerMtrFormulaNew']=$pricePerMtrFormulaNew.'='.$pouchPerKgFormulla1.'*'.$post_height;
					$pricePerMtrFormulaNew1 = $this->numberFormate(($pricePerMtrFormulaNew / 1000) ,"5");
					
					$test['$pricePerMtrFormulaNew1']=$pricePerMtrFormulaNew1.'='.$pricePerMtrFormulaNew.'/1000';
				//CLOSE ################ PRICE / MTR
				
				//$quantity
				$quantityInMtr = 0;
				$quantityInKg = 0;
				$quantityInPieces = 0;
				$quantity_type = $data['quantity_type'];
				
				//COURIER AND TRANSPORT CALCULATION
				$transportByAir = 0;
				$transportBySea = 0;
				if(isset($data['transpotation']) && !empty($data['transpotation']) ){
				    if(in_array(encode('air'),$data['transpotation'])){
						$transportByAir = 1;
						
					}
					if(in_array(encode('sea'),$data['transpotation'])){
						$transportBySea = 1;
						
					}
					if(in_array(encode('pickup'),$data['transpotation'])){
							$transportByPickup = 1; 
					}
			        /*if(in_array('air',$data['transpotation'])){
						$transportByAir = 1;
					}else{
						$transportBySea = 1;
					}
					
					if(in_array('sea',$data['transpotation'])){
						$transportBySea = 1;
					}else{
						$transportByAir = 1;
					}*/
				}else{
					$transportBySea = 1;
				}
				$shilmentCountry = $this->getCountry($data['country_id']);
				if(strtolower($shilmentCountry['country_name']) == "india"){
					$transportByAir = 0;
					$transportBySea = 0;
					$transportByPickup = 1;
				}
				$courierChargeWithZipper = 0;
				$courierChargeWithoutZipper = 0;
				$fuleSurchargeWithZipper  = 0;
				$serviceTaxWithZipper = 0;
				$fuleSurchargeWithoutZipper  = 0;
				$serviceTaxWithoutZipper = 0;
				$handlingCharge = 0;
				$fual_surcharge_base_price = 0;
				$service_tax_base_price = 0;
				$handling_base_price = 0;
				if($transportByAir){
					$countryCourierData = $this->getCountryCourier($data['country_id']);
					$fual_surcharge_base_price = $countryCourierData['fuel_surcharge'];
					$service_tax_base_price = $countryCourierData['service_tax'];
					$handling_base_price = $countryCourierData['handling_charge'];
				}
				
				//new code for multipale quantity
				$userGress = $userInfo['gres'];
				$userGressSea = $userInfo['gres_sea'];
				$userGressAir = $userInfo['gres_air'];
				$userGressCylinder = $userInfo['gres_cyli'];
				
				$customer_gress = 0;
				$customer_email = '';
				if(isset($data['customer_check']) ){
					$customer_email = (isset($data['customer_email']) && $data['customer_email'] != '')?$data['customer_email']:'';
					$customer_gress =  (isset($data['customer_gress']) && (int)$data['customer_gress'] > 0 )?(int)$data['customer_gress']:0;
				}
				$quantityArray = $data['quantity'];
				$quantityWiseData = array();
				foreach($quantityArray as $key=>$eQuantity){
					if($quantity_type == 'meter')
					    $eQuantity = encode($eQuantity);
					    
					$quantity = decode($eQuantity);
					
					if($quantity_type == 'kg'){
						$quantityInKg = $quantity;
					}elseif($quantity_type == 'pieces'){
						$quantityInPieces = $quantity;
					}else{
						$quantityInMtr = $quantity;
					}
					
					if($meter=='kg')
					    $quantityInKg = $menual_qty;//printr($meter);//die;
					
					
					//START ################ TOTAL QUANTITY IN KGS
						$totalQuantityInKgs = $this->numberFormate(($quantityInMtr / $pricePerMtrFormulaNew1) ,"5");
						$test['$totalQuantityInKgs']=$totalQuantityInKgs.'='.$quantityInMtr.'/'.$pricePerMtrFormulaNew1;
					//CLOSE ################ TOTAL QUANTITY IN KGS
					$kgs = $totalQuantityInKgs;
					$piece = $this->numberFormate(($quantityInPieces / $pouchPerKgFormulla1),"5");
					$test['$piece']=$piece.'='.$quantityInPieces.'/'.$pouchPerKgFormulla1;
					
					//Total kgs
					$totalKgs = $this->numberFormate(($quantityInKg + $kgs + $piece),"5");
					$test['$totalKgs']=$totalKgs.'='.$quantityInKg.'+'.$kgs.'+'.$piece;
					//printr($meter);
					if($meter=='kg')
					    $totalKgs = $this->numberFormate(($quantityInKg + 0 + $piece),"5");
					
					$test['$totalKgs2']=$totalKgs.'='.$quantityInKg.'+'.$kgs.'+'.$piece;    
    				//Total Price : SUM of all price and calculate average price
    				$totalPrice = $this->numberFormate(($totalLayerPrice + $inkPrice + $inkSolventPrice + $adhesivePrice + $adhesiveSolventPrice),"3") ;
                    $test['$totalPrice']=$totalPrice;  
					
					//Wastage
					$wastageBasePrice = $this->getRollWastage($totalKgs);
					$test['$wastageBasePrice']= $wastageBasePrice;
					
					$wastage = $this->numberFormate((($wastageBasePrice * $totalPrice) / 100 ),"5");
					$test['$wastage']=$wastage.'='.$wastageBasePrice.'*'.$totalPrice.'/100';
					//START ########## PRICE / 1000 POUCH
						//$pricePer1000 = ($totalPrice);//$pricePer1000 = ($totalPrice + $totalPrice);
						$pricePer1000 = ($totalPrice+$wastage);
						$test['$pricePer1000']=$pricePer1000.'='.$totalPrice.'+'.$wastage;
					//CLOSE ########## PRICE / 1000 POUCH
					
					//START ################ PRICE / KG
						$pricePerKgFormula = $this->numberFormate(($pricePer1000 / 1000),"5");
						$test['$pricePerKgFormula']=$pricePerKgFormula.'='.$pricePer1000.'/1000';
						$pricePerKgFormula1 = $this->numberFormate(($pouchPerKgFormulla1 * $pricePerKgFormula),"5");
						$test['$pricePerKgFormula1']=$pricePerKgFormula1.'='.$pouchPerKgFormulla1.'*'.$pricePerKgFormula;
					//CLOSE ################ PRICE / KG
					
					//START ################ PRICE / MTR
						$pricePerMtrFormula = $this->numberFormate((($pouchPerKgFormulla1 * $post_height) / 1000) ,"5");
						$test['$pricePerMtrFormula']=$pricePerMtrFormula.'='.$pouchPerKgFormulla1.'*'.$post_height;
						$pricePerMtrFormula1 = $this->numberFormate(($pricePerKgFormula1 / $pricePerMtrFormula),"5");
						$test['$pricePerMtrFormula1']=$pricePerMtrFormula1.'='.$pricePerKgFormula1.'/'.$pricePerMtrFormula;
					//CLOSE ################ PRICE / MTR
					
					$mtr = $this->numberFormate(($quantityInKg * $pricePerMtrFormula),"5");
					$test['$mtr']=$mtr.'='.$quantityInKg.'*'.$pricePerMtrFormula;
					
					$kg1 = $this->numberFormate(($totalQuantityInKgs * $pouchPerKgFormulla1 ),"5");
					$mtr1 = $this->numberFormate(($quantityInKg * $pouchPerKgFormulla1 ),"5");
					$piece1 = $this->numberFormate(($piece * $pricePerMtrFormula ),"5");
					
					$test['$kg1']=$kg1.'='.$totalQuantityInKgs.'*'.$pouchPerKgFormulla1;
					$test['$mtr1']=$mtr1.'='.$quantityInKg.'*'.$pouchPerKgFormulla1;
					$test['$piece1']=$piece1.'='.$piece.'*'.$pricePerMtrFormula;
					
					//Total mater
					$totalMtr = $this->numberFormate(($quantityInMtr + $mtr + $piece1),"5");
					if($meter=='kg')
					    $totalMtr = $this->numberFormate((0 + $mtr + $piece1),"5");
					//Total Pieces
					$totalPiece = $this->numberFormate(($quantityInPieces + $mtr1 + $kg1),"5");
					
					$test['$totalMtr']=$totalMtr.'='.$quantityInMtr.'+'.$mtr.'+'.$piece1;
					$test['$totalPiece']=$totalPiece.'='.$quantityInPieces.'+'.$mtr1.'+'.$kg1;
					
					$mtr_array=array('total_kgs'=>$totalKgs,
							         'total_mtr'=>$totalMtr,
							         'total_piece'=>floor($totalPiece),
							         'total_piece_per_kg'=>floor($totalPiece/$totalKgs),
							         'floor_kg'=>floor($totalKgs));
					
					if($meter=='meter')
					    return $mtr_array;
					else if($meter=='kg')
					    return $totalMtr;
					
					//START ################ TOTAL CHARGE
						$profitPrice = $this->getRollProfit($totalKgs);
						$test['$profitPrice']=$profitPrice;
						
						$profitPerKg = 0;
						$profitPerPiece = 0;
						$profitPerMtr = 0;
						$profitForKg = ( $totalKgs * $profitPrice);
						$test['$profitForKg']=$profitForKg.'='.$totalKgs.'*'.$profitPrice;
						
						$totalProfit = $this->numberFormate($profitForKg,"5");
						$test['$totalProfit']=$totalProfit;
						
						//total packing charge
						$packingPrice = $this->getRollPackingPrice($totalKgs);
						$test['$packingPrice']=$packingPrice;
						
						$totalPackingCharge = $this->numberFormate(($totalKgs * $packingPrice),"5");
						$originalperpouchpackingprice=$packingPrice;
						
						$test['$totalPackingCharge']=$totalPackingCharge.'='.$totalKgs.'+'.$packingPrice;
						$test['$originalperpouchpackingprice']=$originalperpouchpackingprice;
						
						$fuleSurcharge  = 0;
						$serviceTax = 0;
						$handlingCharge = 0;
						$totalTransportCharge = 0;
						$courierCharge =$actual_courier_per_kg_price = 0;
						$transportPrice = 0;
						
						$taxation='';
						$taxation_data='';
						$final_tax_name='';
						$tax_name='';
						if(isset($data['country_id']) && !empty($data['country_id']) && $data['country_id'] ==111)
						{
							
							/*$tax_name.=' tax_name="'.$data['normalform'].'"';
							$sql = "SELECT excies,cst_with_form_c,cst_without_form_c,vat,taxation_id,cgst,sgst,igst FROM " . DB_PREFIX . "taxation WHERE status = '1' AND is_delete = '0' AND ".$tax_name." ORDER BY taxation_id DESC LIMIT 1";
							$data_tax = $this->query($sql);
							$taxation_data=$data_tax->row;*/
							$sql="SELECT * FROM product_gst_master WHERE find_in_set(".$data['product'].",product_id) <> 0";
							$data_tax = $this->query($sql);
							$taxation_data=$data_tax->row;
						}
						$test['$tax_name']=$tax_name;
						$test['$taxation_data']=$taxation_data;
						
						$byAir = '';
						$bySea = '';
						$byPickup = '';
						if($transportByAir){
							$courierCharge0 = $this->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'], $totalKgs);
							$test['$courierCharge0']=$courierCharge0;
							
							$actual_courier_per_kg_price = $courierCharge0['PerKgPrice'];
							$test['$actual_courier_per_kg_price']=$actual_courier_per_kg_price;
							
							if($fual_surcharge_base_price > 0){
								$fuleSurcharge = (($courierCharge0['price'] * $fual_surcharge_base_price) / 100);
								$test['$fuleSurcharge']=$fuleSurcharge.'='.$courierCharge0['price'].'*'.$fual_surcharge_base_price;
							}
							if($service_tax_base_price > 0){
								$courierCrhgFule = ($courierCharge0['price'] + $fuleSurcharge);
								$serviceTax = (($courierCrhgFule * $service_tax_base_price) / 100);
								
								$test['$courierCrhgFule']=$courierCrhgFule.'='.$courierCharge0['price'].'+'.$fuleSurcharge;
								$test['$serviceTax']=$serviceTax.'='.$courierCrhgFule.'+'.$service_tax_base_price;
							}
							if($handling_base_price > 0){
								$handlingCharge = $handling_base_price;
								$test['$handlingCharge']=$handlingCharge;
							}
							$courierCharge = $this->numberFormate(($courierCharge0['price'] + $fuleSurcharge + $serviceTax + $handlingCharge),"3");
							$totalCharge = $this->numberFormate(($totalProfit + $totalPackingCharge),"5");
							$addingGressPrice = 0;
							$pricePerUnit = 0;
							$totalPrice = 0;
							
							$test['$courierCharge']=$courierCharge.'='.$courierCharge0['price'].'+'.$fuleSurcharge.'+'.$serviceTax.'+'.$handlingCharge;
							$test['$totalCharge']=$totalCharge.'='.$totalProfit.'+'.$totalPackingCharge;
							
							//[kinjal] done on 31-1-2018
							$gress_qtywise = $this->getGressQtyWise($quantity,'air',$admin_user_id);
								$userGressAir = $gress_qtywise['percentage'];
							
							// price in mtr / unit
							if($quantityInMtr > 0){
								//Total Price  mater
								$totalPriceMtr = $this->numberFormate(($quantityInMtr * $pricePerMtrFormula1),"5");
								$totalPrice = $this->numberFormate(($totalPriceMtr + $totalCharge + $courierCharge),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInMtr),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $gress_qtywise['percentage']) / 100);
								}
								
								$test['$totalPriceMtr']=$totalPriceMtr.'='.$quantityInMtr.'*'.$pricePerMtrFormula1;
								$test['$totalPrice']=$totalPrice.'='.$totalPriceMtr.'+'.$totalCharge.'+'.$courierCharge;
								$test['$pricePerUnit']=$pricePerUnit.'='.$totalPrice.'/'.$quantityInMtr;
								$test['$addingGressPrice']=$addingGressPrice.'='.$totalPrice.'+'. $gress_qtywise['percentage'].'/100';
								
								
							}
							// price in KGS / unit
							if($quantityInKg > 0){
								//Total Price kgs
								$totalPriceKgs = $this->numberFormate(($quantityInKg * $pricePerKgFormula1 ),"5");
								$totalPrice = $this->numberFormate(($totalPriceKgs + $totalCharge + $courierCharge),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInKg),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $gress_qtywise['percentage']) / 100);
								}
								
								$test['$totalPriceKgs']=$totalPriceKgs.'='.$quantityInKg.'*'.$pricePerKgFormula1;
								$test['$totalPrice']=$totalPrice.'='.$totalPriceKgs.'+'.$totalCharge.'+'.$courierCharge;
								$test['$pricePerUnit']=$pricePerUnit.'='.$totalPrice.'/'.$quantityInKg;
								$test['$addingGressPrice']=$addingGressPrice.'='.$totalPrice.'+'. $gress_qtywise['percentage'].'/100';
							}
							// price in PIECE / unit
							if($quantityInPieces > 0){
								//Total PRICE Pieces
								$totalPricePiece = $this->numberFormate(($quantityInPieces * ( $pricePer1000 / 1000 )),"5");
								$totalPrice = $this->numberFormate(($totalPricePiece + $totalCharge + $courierCharge ),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInPieces),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $gress_qtywise['percentage']) / 100);
								}
								
								$test['$totalPricePiece']=$totalPricePiece.'='.$quantityInPieces.'*('.$pricePer1000.'/1000)';
								$test['$totalPrice']=$totalPrice.'='.$totalPricePiece.'+'.$totalCharge.'+'.$courierCharge;
								$test['$pricePerUnit']=$pricePerUnit.'='.$totalPrice.'/'.$quantityInPieces;
								$test['$addingGressPrice']=$addingGressPrice.'='.$totalPrice.'+'. $gress_qtywise['percentage'].'/100';
							}
							
							$byAir = array(
								'courierCharge'	=> $courierCharge,
								'totalCharge'		=> $totalCharge,
								'totalPrice'		 => $totalPrice,
								'addingPrice'		=> $addingGressPrice,
								'userGress'     =>$gress_qtywise['percentage'],
								
							);
						}
						
						if($transportBySea){
							//total transport price
							$transportPrice = $this->getRollTransportPrice($totalKgs);
							$totalTransportCharge = $this->numberFormate(($totalKgs * $transportPrice),"5");
							$totalCharge = $this->numberFormate(($totalProfit + $totalPackingCharge + $totalTransportCharge),"5");
							
							$test['$transportPrice']=$transportPrice;
							$test['$totalTransportCharge']=$totalTransportCharge.'='.$totalKgs.'+'.$transportPrice;
							$test['$totalCharge']=$totalCharge.'='.$totalProfit.'+'.$totalPackingCharge.'+'.$transportPrice;
							
							$addingGressPrice = 0;
							$pricePerUnit = 0;
							$totalPrice = 0;
							$gress_qtywise = $this->getGressQtyWise($quantity,'sea',$admin_user_id);
							$userGressSea=$gress_qtywise['percentage'];
							// price in mtr / unit
							if($quantityInMtr > 0){
								//Total Price  mater
								$totalPriceMtr = $this->numberFormate(($quantityInMtr * $pricePerMtrFormula1),"5");
								$totalPrice = $this->numberFormate(($totalPriceMtr + $totalCharge),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInMtr),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $gress_qtywise['percentage']) / 100);
								}
								
								$test['$totalPriceMtr']=$totalPriceMtr.'='.$quantityInMtr.'*'.$pricePerMtrFormula1;
								$test['$totalPrice']=$totalPrice.'='.$totalPriceMtr.'+'.$totalCharge;
								$test['$pricePerUnit']=$pricePerUnit.'='.$totalPrice.'/'.$quantityInMtr;
								$test['$addingGressPrice']=$addingGressPrice.'='.$totalPrice.'+'. $gress_qtywise['percentage'].'/100';
							}
							// price in KGS / unit
							if($quantityInKg > 0){
								//Total Price kgs
								$totalPriceKgs = $this->numberFormate(($quantityInKg * $pricePerKgFormula1 ),"5");
								$totalPrice = $this->numberFormate(($totalPriceKgs + $totalCharge ),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInKg),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $gress_qtywise['percentage']) / 100);
								}
								
								$test['$totalPriceKgs']=$totalPriceKgs.'='.$quantityInKg.'*'.$pricePerKgFormula1;
								$test['$totalPrice']=$totalPrice.'='.$totalPriceKgs.'+'.$totalCharge;
								$test['$pricePerUnit']=$pricePerUnit.'='.$totalPrice.'/'.$quantityInKg;
								$test['$addingGressPrice']=$addingGressPrice.'='.$totalPrice.'+'. $gress_qtywise['percentage'].'/100';
							}
							// price in PIECE / unit
							if($quantityInPieces > 0){
								//Total PRICE Pieces
								$totalPricePiece = $this->numberFormate(($quantityInPieces * ( $pricePer1000 / 1000 )),"5");
								$totalPrice = $this->numberFormate(($totalPricePiece + $totalCharge ),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInPieces),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $gress_qtywise['percentage']) / 100);
								}
								
								$test['$totalPricePiece']=$totalPricePiece.'='.$quantityInPieces.'*('.$pricePer1000.'/1000)';
								$test['$totalPrice']=$totalPrice.'='.$totalPricePiece.'+'.$totalCharge;
								$test['$pricePerUnit']=$pricePerUnit.'='.$totalPrice.'/'.$quantityInPieces;
								$test['$addingGressPrice']=$addingGressPrice.'='.$totalPrice.'+'. $gress_qtywise['percentage'].'/100';
							}
							
							$bySea = array(
								'transportCharge'	=> $totalTransportCharge,
								'transportBasePrice' => $transportPrice,
								'totalCharge'		=> $totalCharge,
								'totalPrice'		 => $totalPrice,
								'addingPrice'		=> $addingGressPrice,
								'userGress'     =>$gress_qtywise['percentage'],
							);
						}
						if($transportByPickup){
							$totalCharge = $this->numberFormate(($totalProfit + $totalPackingCharge),"5");
							$gress_qtywise = $this->getGressQtyWise($quantity,'pickup',$admin_user_id);
							
							
							$test['$totalCharge']=$totalCharge.'='.$totalProfit.'+'.$totalPackingCharge;

							// price in mtr / unit
							if($quantityInMtr > 0){
								//Total Price  mater
								$totalPriceMtr = $this->numberFormate(($quantityInMtr * $pricePerMtrFormula1),"5");
								$totalPrice = $this->numberFormate(($totalPriceMtr + $totalCharge),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInMtr),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $gress_qtywise['percentage']) / 100);
								}
								
								$test['$totalPriceMtr']=$totalPriceMtr.'='.$quantityInMtr.'*'.$pricePerMtrFormula1;
								$test['$totalPrice']=$totalPrice.'='.$totalPriceMtr.'+'.$totalCharge;
								$test['$pricePerUnit']=$pricePerUnit.'='.$totalPrice.'/'.$quantityInMtr;
								$test['$addingGressPrice']=$addingGressPrice.'='.$totalPrice.'+'. $gress_qtywise['percentage'].'/100';
							}
							// price in KGS / unit
							if($quantityInKg > 0){
								//Total Price kgs
								$totalPriceKgs = $this->numberFormate(($quantityInKg * $pricePerKgFormula1 ),"5");
								$totalPrice = $this->numberFormate(($totalPriceKgs + $totalCharge),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInKg),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $gress_qtywise['percentage']) / 100);
								}
								
								$test['$totalPriceKgs']=$totalPriceKgs.'='.$quantityInKg.'*'.$pricePerKgFormula1;
								$test['$totalPrice']=$totalPrice.'='.$totalPriceKgs.'+'.$totalCharge;
								$test['$pricePerUnit']=$pricePerUnit.'='.$totalPrice.'/'.$quantityInKg;
								$test['$addingGressPrice']=$addingGressPrice.'='.$totalPrice.'+'. $gress_qtywise['percentage'].'/100';
							}
							// price in PIECE / unit
							if($quantityInPieces > 0){
								//Total PRICE Pieces
								$totalPricePiece = $this->numberFormate(($quantityInPieces * ( $pricePer1000 / 1000 )),"5");
								$totalPrice = $this->numberFormate(($totalPricePiece + $totalCharge  ),"5");
								$pricePerUnit = $this->numberFormate(($totalPrice / $quantityInPieces),"5");
								if($userGress > 0){
									$addingGressPrice = (($totalPrice * $gress_qtywise['percentage']) / 100);
								}
								
								$test['$totalPricePiece']=$totalPricePiece.'='.$quantityInPieces.'*('.$pricePer1000.'/1000)';
								$test['$totalPrice']=$totalPrice.'='.$totalPricePiece.'+'.$totalCharge;
								$test['$pricePerUnit']=$pricePerUnit.'='.$totalPrice.'/'.$quantityInPieces;
								$test['$addingGressPrice']=$addingGressPrice.'='.$totalPrice.'+'. $gress_qtywise['percentage'].'/100';
							}
							$byPickup = array(
								'totalCharge'		=> $totalCharge,
								'totalPrice'		 => $totalPrice,
								'addingPrice'		=> $addingGressPrice,
								'userGress'     =>$gress_qtywise['percentage'],
							);
							
						}
					 //CLOSE ################ TOTAL CHARGE 
					
						//store quantity wise information
						$quantityWiseData[$quantity] = array(
							'wastageBase'	=> $wastageBasePrice,
							'wastage'		=> $wastage,
							'nativePricePerBag' => $pricePer1000,
							'totalKgs'	   => $totalKgs,
							'totalMtr'	   => $totalMtr,
							'totalPiece'	 => $totalPiece,
							'packingBase'	=> $packingPrice,
							'packingCharge'  => $totalPackingCharge,
							'totalQuantityInKgs'	=> $totalQuantityInKgs,
							'profitBase'	 => $profitPrice,
							'profit'		 => $totalProfit,
							'ByAir'		  => $byAir,
							'BySea'		  => $bySea,
							'ByPickup'		  => $byPickup,
							'gressCyli'=> $userInfo['gres_cyli'],
						);
				}
			
				$test['$quantityWiseData']=$quantityWiseData;
				//user country and currency info
				$userCountry = $this->getUserCountry($user_type_id,$user_id);
				$tool_price = $this->getToolPrice($post_width,$gusset,$product_id);
				if($user_type_id==1){
					$userCurrency = $this->getCurrencyInfo($user_id);
					$userCurrency['tool_rate']='';
				}else{
					$userCurrency =  $this->getUserWiseCurrency($user_type_id,$user_id);
				}
				$cylinderPrice = $this->getCalculateCylinderPrice($post_height,$post_width,'',$data['country_id'],$product_id);
				
				$cylinderCurrencyPrice = $cylinderPrice;
				
				$test['$cylinderCurrencyPrice']=$cylinderCurrencyPrice;
				if($user_type_id==1)
				{
					$currCode ='INR';
					//make if condition fro admin to get selectes currency code [kinjal] : 23-12-2015
					if(isset($data['selcurrency'])&& !empty($data['selcurrency']))
					{
						$curr = explode('==',$data['selcurrency']);
						$currCode= $curr[0];
					} 	
					//end [kinjal]									
				}
				else
				{
					$currCode=$userCurrency['currency_code'];
				}

				//Cylinder Price
				if($userCurrency['tool_rate']){
						$cylinderCurrencyPrice = ($cylinderPrice / $userCurrency['cylinder_rate']);
						$tool_price = ($tool_price / $userCurrency['tool_rate']);
				}
			
				if($user_type_id==1 && isset($data['swiss_tool_rate']) && !empty($data['swiss_tool_rate']) && isset($data['swiss_cylinder_rate']) && !empty($data['swiss_cylinder_rate']))
				{
					$cylinderCurrencyPrice = ($cylinderPrice / $data['swiss_cylinder_rate']);
					$tool_price = ($tool_price / $data['swiss_tool_rate']);
				}
				else
				{
					$data['swiss_cylinder_rate']=$data['swiss_tool_rate']='';
				}
				
				
				$cylinderCurrencyMinPrice = $this->getCylinderBasePrice($currCode,$admin_user_id);
				if($cylinderCurrencyMinPrice && $cylinderCurrencyMinPrice >= $cylinderCurrencyPrice ){
					$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
					//[kinjal] added for cylinder gress price on (8-2-2018)
					$cylinderCurrencyGressPrice = $this->numberFormate((($cylinderCurrencyBasePrice * $userInfo['gres_cyli']) / 100),"3");
				}else{
					$cylinderCurrencyBasePrice = $cylinderCurrencyPrice;
					//[kinjal] added for cylinder gress price on (8-2-2018)
					$cylinderCurrencyGressPrice = $cylinderCurrencyBasePrice;
				} 
				if($cylinderCurrencyPrice <= $cylinderCurrencyMinPrice)
				{
					$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
					//[kinjal] added for cylinder gress price on (8-2-2018)
					$cylinderCurrencyGressPrice = $this->numberFormate((($cylinderCurrencyBasePrice * $userInfo['gres_cyli']) / 100),"3");
				}
				$cylinderCurrencyMinPrice = $this->getCylinderBasePrice($currCode,$admin_user_id);
				
				
				
				$inkDefaultPrice = $this->getInkPrice1($data['make']);
				$inkSolventDefaultPrice = $this->getInkSolventPrice('',0,$data['make']);
				$printingEffectDefaultPrice = $this->getPrintingEffectPrice($data['printing_effect']);
				$adhesiveDefaultPrice = $this->getAdhesivePrice('','',0,$data['make']);
				$adhesiveSolventDefaultPrice = $this->getAdhesiveSolventPrice('','',0,$data['make']);
				$cppAdhesiveDefaultPrice = $this->getCppAdhesivePrice('','',0);;
				
				//quotation number
				$userCountry = $this->getUserCountry($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
				if($userCountry){
					$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';
					$newQuotaionNumber = $this->generateQuotationNumber($multi_product_quotation_id);
					$quotation_number = $countryCode.$newQuotaionNumber;
				}else{
					$newQuotaionNumber = $this->generateQuotationNumber($multi_product_quotation_id);
					$quotation_number = 'IN'.$newQuotaionNumber;
				}
				
				$printingEffectName = getName('printing_effect','printing_effect_id',$data['printing_effect'],'effect_name');
				$productName = getName('product','product_id',$data['product'],'product_name');
				
				if($userCurrency['currency_code'] && $userCurrency['product_rate']){
					$currency = $userCurrency['currency_code'];
					$currencyPrice = $userCurrency['product_rate'];
				}else{
					$currency = 'INR';
					$currencyPrice = '1';
				}
				
				$sql =  "UPDATE  ".DB_PREFIX."new_multi_product_quotation_id SET multi_quotation_number = '".$quotation_number."' WHERE multi_product_quotation_id = '".$multi_product_quotation_id."'";
				$this->query($sql);
				
				//added by kinjal for new gst on 4-10-2019	
				$sql_cyli="SELECT * FROM product_gst_master WHERE find_in_set(51,product_id) <> 0";
				$data_cyli = $this->query($sql_cyli);
				$taxation_cyli=$data_cyli->row;
				
				//printr($test);
				$totalcylinderpriceWithTax = $totaltoolpriceWithTax = '';
				if(isset($taxation_data) && !empty($taxation_data))
				{
					$cylinderpriceFortax = $cylinderCurrencyBasePrice;
					
					//[kinjal] updated on 10-7-2017 
					if($data['normalform']=='Out Of Gujarat')
					{
						/*$totalcylinderpriceWithTax = $cylinderpriceFortax+($cylinderpriceFortax*$taxation_data['igst']/100);*/
						$totalcylinderpriceWithTax = $cylinderpriceFortax+($cylinderpriceFortax*$taxation_cyli['igst_percentage']/100);

						$test['$cylinderpriceFortax+($cylinderpriceFortax*$taxation_cyli[igst]/100)']=$cylinderpriceFortax.'+('.$cylinderpriceFortax.'*'.$taxation_cyli['igst_percentage'].'/100)';

					}
					else
					{
						/*$totalcylinderpriceWithTax = $cylinderpriceFortax+($cylinderpriceFortax*($taxation_data['cgst']+$taxation_data['sgst'])/100);*/
						$totalcylinderpriceWithTax = $cylinderpriceFortax+($cylinderpriceFortax*($taxation_cyli['cgst_percentage']+$taxation_cyli['sgst_percentage'])/100);
						$test['$cylinderpriceFortax+($cylinderpriceFortax*($taxation_cyli[cgst_percentage]+$taxation_cyli[sgst_percentage])/100)']=$cylinderpriceFortax.'+('.$cylinderpriceFortax.'*('.$taxation_cyli['cgst_percentage'].'+'.$taxation_cyli['sgst_percentage'].')/100)';
					}
					$test['$totalcylinderpriceWithTax']=$totalcylinderpriceWithTax;
				    $test['$toolpriceFortax']=$toolpriceFortax;
				    $test['$totaltoolpriceWithTax']=$totaltoolpriceWithTax;
																															
				}
				//printr("INSERT INTO ".DB_PREFIX."multi_product_quotation SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect_id = '".(int)$data['printing_effect']."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."', use_device = '".getDevice()."', status = '0', quantity_type = '".$quantity_type."', gress_percentage = '".$userGress."',gress_air = '".$userGressAir."',gress_sea = '".$userGressSea."',packing_price = '".$originalperpouchpackingprice."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."',cylinder_price_withtax='".(float)$totalcylinderpriceWithTax."', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', currency = '".$currency."', currency_price = '".$currencyPrice."', customer_name = '". $data['customer']."', customer_email = '".$customer_email."', customer_gress_percentage = '".$customer_gress."', shipment_country_id = '".$data['country_id']."',multi_product_quotation_id = '".$multi_product_quotation_id."', added_by_user_id = '".(int)$_SESSION['ADMIN_LOGIN_SWISS']."', added_by_user_type_id = '".(int)$_SESSION['LOGIN_USER_TYPE']."',admin_user_id='".$admin_user_id."',ink_multi_by = '".(float)$multi_by."',cust_ink_mul_by = '".$cust_ink_mul_by."' , cust_adhesive_mul_by='".$cust_adhesive_mul_by."',swiss_cylinder_rate = '".(float)$data['swiss_cylinder_rate']."', swiss_tool_rate = '".(float)$data['swiss_tool_rate']."' ");
				//quotation_type = '1', 
				$sql1 = "INSERT INTO ".DB_PREFIX."new_multi_product_quotation SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect_id = '".(int)$data['printing_effect']."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', layer = '".(int)$totalLayer."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."', use_device = '".getDevice()."', status = '0', quantity_type = '".$quantity_type."', gress_percentage = '".$userGress."',gress_air = '".$userGressAir."',gress_sea = '".$userGressSea."',packing_price = '".$originalperpouchpackingprice."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."',cylinder_price_withtax='".(float)$totalcylinderpriceWithTax."', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', currency = '".$currency."', currency_price = '".$currencyPrice."', customer_name = '".addslashes($data['customer'])."', customer_email = '".$customer_email."', customer_gress_percentage = '".$customer_gress."', shipment_country_id = '".$data['country_id']."',multi_product_quotation_id = '".$multi_product_quotation_id."', added_by_user_id = '".(int)$_SESSION['ADMIN_LOGIN_SWISS']."', added_by_user_type_id = '".(int)$_SESSION['LOGIN_USER_TYPE']."',admin_user_id='".$admin_user_id."',ink_multi_by = '".(float)$multi_by."',cust_ink_mul_by = '".$cust_ink_mul_by."' , cust_adhesive_mul_by='".$cust_adhesive_mul_by."',swiss_cylinder_rate = '".(float)$data['swiss_cylinder_rate']."', swiss_tool_rate = '".(float)$data['swiss_tool_rate']."' ";
			
				$this->query($sql1);
			
				$productQuatiationId = $this->getLastId();
				if(isset($productQuatiationId) && (int)$productQuatiationId > 0 ){
					
					//INSERT QUOTATION QUANTITY TABLE 
					if(isset($quantityWiseData) && !empty($quantityWiseData)){
						
						foreach($quantityWiseData as $quantity=>$quantityValue){
						
							$this->query("INSERT INTO ".DB_PREFIX."new_multi_product_quotation_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit_base_price = '".$quantityValue['profitBase']."', profit = '".$quantityValue['profit']."', total_kgs = '".$quantityValue['totalKgs']."', total_mtr = '".$quantityValue['totalMtr']."', total_piece = '".$quantityValue['totalPiece']."', total_quantity_in_kgs = '".$quantityValue['totalQuantityInKgs']."', packing_base_price = '".$quantityValue['packingBase']."', packing_charge = '".$quantityValue['packingCharge']."', date_added = NOW()");
							$productQuatiationQuantityId = $this->getLastId();
							
							
							
							$pricesql = "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', date_added = NOW(), make_pouch = '".(int)$data['make']."', zipper_txt = 'No Zip',valve_txt = 'No Valve', spout_txt = 'No Spout',accessorie_txt = 'No Accessorie',cyli_gress_price='".$cylinderCurrencyGressPrice."',gress_cyli_per='".$userInfo['gres_cyli']."',";
							
							if(isset($quantityValue['BySea']) && !empty($quantityValue['BySea'])){
							    $t_price = $quantityValue['BySea']['totalPrice'];
							    $customerGressPrice = 0; 
								if($customer_gress > 0){
									$customerGressPrice = $this->numberFormate((($quantityValue['BySea']['totalPrice'] * $customer_gress) / 100),"3");
								}
							}
							 if(isset($quantityValue['ByAir']) && !empty($quantityValue['ByAir'])){
							    $t_price = $quantityValue['ByAir']['totalPrice'];
							    $customerGressPrice = 0; 
								if($customer_gress > 0){
									$customerGressPrice = $this->numberFormate((($zipData['ByAir']['totalPrice'] * $customer_gress) / 100),"3");
								}
							 }
							 if(isset($quantityValue['ByPickup']) && !empty($quantityValue['ByPickup'])){
							    $t_price = $quantityValue['ByPickup']['totalPrice'];
							    $customerGressPrice = 0; 
								if($customer_gress > 0){
									$customerGressPrice = $this->numberFormate((($zipData['ByPickup']['totalPrice'] * $customer_gress) / 100),"3");
								}
							 }
							if(isset($taxation_data) && !empty($taxation_data))
							{
								$totalPriceForTax = $t_price+$customerGressPrice;
								if($data['discount'])
								{
									$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
								}
								
								
								if($data['normalform']=='Out Of Gujarat')
								{
									$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*$taxation_data['igst_percentage']/100);
									$test['$totalPricWithExcies+($totalPricWithExcies*$taxation_data[igst_percentage]/100)']=$totalPricWithExcies.'+('.$totalPricWithExcies.'*'.$taxation_data['igst_percentage'].'/100)';
									$igst =$taxation_data['igst_percentage'];
								}
								else
								{
									$totalPriceWithTax = $totalPriceForTax+($totalPriceForTax*($taxation_data['cgst_percentage']+$taxation_data['sgst_percentage'])/100);
									$test['$totalPricWithExcies+($totalPricWithExcies*($taxation_data[cgst_percentage]+$taxation_data[sgst_percentage])/100)']=$totalPricWithExcies.'+('.$totalPricWithExcies.'*('.$taxation_data['cgst_percentage'].'+'.$taxation_data['sgst_percentage'].')/100)';
									$cgst =$taxation_data['cgst_percentage'];
									$sgst =$taxation_data['sgst_percentage'];
								}
								$test['$totalPriceWithTax']=$totalPriceWithTax;
								$test['$igst=$cgst=$sgst=']=$igst.'='.$cgst.'='.$sgst;
								$tax_type=$data['normalform'];
							}	
							
							
							 if(isset($quantityValue['BySea']) && !empty($quantityValue['BySea'])){
								
								$this->query(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$quantityValue['BySea']['transportCharge']."', transport_base_price = '".$quantityValue['BySea']['transportBasePrice']."', total_price = '".$quantityValue['BySea']['totalPrice']."', gress_price = '".$quantityValue['BySea']['addingPrice']."',gress_per = '".$quantityValue['BySea']['userGress']."', total_charge = '".$quantityValue['BySea']['totalCharge']."', customer_gress_price = '".$customerGressPrice."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."',igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."',tax_name='".$final_tax_name."'");
							 }
							 
							 if(isset($quantityValue['ByAir']) && !empty($quantityValue['ByAir'])){
								
								 $this->query(" $pricesql transport_type = 'air', courier_charge = '".$quantityValue['ByAir']['courierCharge']."',actual_courier_price = '".$zipData['actual_courier_price']."',fuel_charge='".$courierCharge0['fuel_charge']."',service_tax='".$courierCharge0['service_tax']."',handling_charge='".$courierCharge0['handling_charge']."', transport_price = '0', total_price = '".$quantityValue['ByAir']['totalPrice']."', gress_price = '".$quantityValue['ByAir']['addingPrice']."',gress_per = '".$quantityValue['BySea']['userGress']."', total_charge = '".$quantityValue['ByAir']['totalCharge']."', customer_gress_price = '".$customerGressPrice."',total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."',tax_name='".$final_tax_name."'");
							}
							if(isset($quantityValue['ByPickup']) && !empty($quantityValue['ByPickup']))
							{
								
								$this->query(" $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0',total_price = '".$quantityValue['ByPickup']['totalPrice']."', gress_price = '".$quantityValue['ByPickup']['addingPrice']."',gress_per = '".$quantityValue['ByPickup']['userGress']."',customer_gress_price = '".$customerGressPrice."',total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', igst='".$igst."',cgst='".$cgst."',sgst='".$sgst."',tax_name='".$final_tax_name."'");
							}
						
						}
					}
					
					//BASE PRICE
					$inkDefaultPrice = $this->getInkPrice1($data['make']);
					$inkSolventDefaultPrice = $this->getInkSolventPrice('',0,$data['make']);
					$printingEffectDefaultPrice = $this->getPrintingEffectPrice($data['printing_effect']);
					$adhesiveDefaultPrice = $this->getAdhesivePrice('','',0,$data['make']);
					$adhesiveSolventDefaultPrice = $this->getAdhesiveSolventPrice('','',0,$data['make']);
					$cppAdhesiveDefaultPrice = $this->getCppAdhesivePrice('','',0);
					$cylinderVendorDefaultPrice = $this->getCylinderVendorPrice();
					$transportWidthDefaultPrice = $this->getDefaultTransportWidthPrice($width,0);
				    $transportHeightDefaultPrice = $this->getDefaulttransportHeightPrice($height);
				    
					//inert base price at a time product quotaion add than taht time real price. use for history
					$this->query("INSERT INTO ".DB_PREFIX."new_multi_product_quotation_base_price SET product_quotation_id = '".(int)$productQuatiationId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', 	printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$originalperpouchpackingprice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."',transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', date_added = NOW()");
					
					//INAERT DATA FOR LAYER WISE
					if(isset($setQueryData) && !empty($setQueryData)){
						foreach($setQueryData as $key=>$setquery){

							$setSql = "INSERT INTO ".DB_PREFIX."new_multi_product_quotation_layer SET product_quotation_id = '".(int)$productQuatiationId."', ".$setquery;
							$this->query($setSql);
						}
					}
				//	printr($test);die;
					return $multi_product_quotation_id;
					
				}else{
					return false;
				}
			}
		}
	}
	public function getActiveZippers()
	{
	   $sql = "SELECT * FROM product_zipper WHERE product_zipper_id IN (14,16)";
	   $data=$this->query($sql);
        if($data->num_rows){
    		return $data->rows;
    	}else{
    		return false; 
    	}
	} 
	
	public function getQuotationsforcheckcustomorder($option){
	 
    	$sql1 = "SELECT mcoi.multi_product_quotation_id,ip.* FROM `invoice_product_test` as ip, invoice as i ,multi_custom_order_id as mcoi WHERE `buyers_o_no` LIKE '%CUST%' AND mcoi.multi_custom_order_number=ip.buyers_o_no AND i.invoice_id=ip.invoice_id AND i.`country_destination` = '42' AND i.is_delete=0 AND i.invoice_date >2018-01-01";  
    	$data1=$this->query($sql1);
         	$multi_product_quotation_id_arr=array();
         	   if($data1->num_rows){
         	      foreach($data1->rows as $d){
         	    $multi_product_quotation_id_arr[]=$d['multi_product_quotation_id'];
         	       }
         	   }
 	    $multi_product_quotation_id = array_unique($multi_product_quotation_id_arr);
	    $id_arr= implode(',',$multi_product_quotation_id);
 	  
 	  
 	    
    	$sql = "SELECT c.country_name,pq.status,pq.product_quotation_id,pq.multi_product_quotation_id,pq.customer_name,pq.product_name, pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_status,pq.quotation_type,pq.date_added,pq.layer, pq.use_device,pqp.zipper_txt,pqp.valve_txt,
        		pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number FROM new_multi_product_quotation pq,country c,new_multi_product_quotation_price pqp,new_multi_product_quotation_id as mpq WHERE c.country_id = pq.shipment_country_id AND
        		pq.product_quotation_id = pqp.product_quotation_id AND 1=1 AND mpq.multi_product_quotation_id=pq.multi_product_quotation_id AND pq.multi_product_quotation_id NOT IN (".$id_arr.") AND pq.shipment_country_id='42' AND pq.admin_user_id='27' AND pq.date_added>='2018-01-01'  ";
        	
	//	echo $sql;die;
	
		
			if(!empty($option['cond'])){
				$sql .= $option['cond'];
			}
					$sql .= "GROUP BY pq.multi_product_quotation_id";
		if (isset($option['sort'])) {
			
				
			$sql .= " ORDER BY " . $option['sort'];	
		} else {
			$sql .= " ORDER BY pq.multi_product_quotation_id";	
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
	    $data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
 
	 
	}
	public function getRollQuantity($material_id){
	    
		$mat = $this->query("SELECT roll_quantity FROM product_material WHERE product_material_id = '".(int)$material_id."'");
		$qty_id= $mat->row['roll_quantity'];
		$data = $this->query("SELECT pq.quantity FROM " . DB_PREFIX . "roll_quantity pq WHERE  pq.roll_quantity_id IN ($qty_id) ORDER BY pq.quantity ASC ");	
		//printr("SELECT pq.quantity FROM " . DB_PREFIX . "roll_quantity pq WHERE  pq.roll_quantity_id IN ($qty_id) ORDER BY pq.quantity ASC ");
		if($data->num_rows){
			$return = array();
			foreach($data->rows as $key=>$value){
				$return[] = $value['quantity'];
			}
			return $return;
		}else{
			return false;
		}
	}
}
?>