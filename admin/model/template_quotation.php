<?php 
class multiProductQuotation extends dbclass{

	public function getActiveProduct(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND is_delete = '0' AND product_id NOT IN (51,52) ";
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
	
	public function getInternational()
	{
		$sql = "SELECT first_name,last_name,international_branch_id  FROM " . DB_PREFIX . "international_branch  where is_delete = '0' ";	
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;
	
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
	
	public function getQuantities()
	{
		$sql = "SELECT * FROM ".DB_PREFIX." template_quantity where is_delete = '0' AND status=1";
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
	public function getActiveColor(){
		$data = $this->query("SELECT pouch_color_id, color FROM " . DB_PREFIX . "pouch_color WHERE status = '1' AND is_delete = '0' ORDER BY color ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getTotalQuotation($user_type_id,$user_id,$filter_array=array(),$cond){
		
		if($user_type_id == 1 && $user_id == 1){
		$sql = "SELECT c.country_name,pq.status,pq.product_quotation_id,pq.multi_product_quotation_id,pq.customer_name,pq.product_name, pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_status,pq.quotation_type,pq.date_added,pq.layer, pq.use_device,pqp.zipper_txt,pqp.valve_txt,
		pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number FROM multi_product_template pq,country c,multi_product_template_price pqp,multi_product_template_id as mpq WHERE c.country_id = pq.shipment_country_id AND
		pq.product_quotation_id = pqp.product_quotation_id AND 1=1 AND mpq.multi_product_quotation_id=pq.multi_product_quotation_id ";
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
			$sql = "SELECT c.country_name,pq.*,pqp.valve_txt,pqp.zipper_txt,pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number FROM multi_product_template pq LEFT JOIN country c ON c.country_id = pq.shipment_country_id LEFT JOIN multi_product_template_price pqp ON pq.product_quotation_id=pqp.product_quotation_id LEFT JOIN multi_product_template_id as mpq ON mpq.multi_product_quotation_id=pq.multi_product_quotation_id WHERE pq.added_by_user_id = '".(int)$set_user_id."' AND pq.added_by_user_type_id = '".(int)$set_user_type_id."' $str ";
		}
		if(!empty($filter_array)) {
			if(!empty($filter_array['quotation_no'])){
				$sql .= " AND mpq.multi_quotation_number = '".$filter_array['quotation_no']."'";
			}
			
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND pq.customer_name LIKE '%".$filter_array['customer_name']."%'";
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
	
	public function getQuotations($user_type_id,$user_id,$data,$filter_array=array()){
		if($user_type_id == 1 && $user_id == 1){
		$sql = "SELECT c.country_name,pq.status,pq.product_quotation_id,pq.multi_product_quotation_id,mptq.profit_type,pq.customer_name,pq.product_name, pq.added_by_user_id,pq.added_by_user_type_id,pq.quotation_status,pq.quotation_type,pq.date_added,pq.layer, pq.use_device,pqp.zipper_txt,pqp.valve_txt,
		pqp.spout_txt,pqp.accessorie_txt,multi_quotation_number FROM multi_product_template pq,country c,multi_product_template_price pqp,multi_product_template_id as mpq,multi_product_template_quantity as mptq WHERE c.country_id = pq.shipment_country_id AND
		pq.product_quotation_id = pqp.product_quotation_id AND mptq.product_quotation_id=pqp.product_quotation_id AND 1=1 AND mpq.multi_product_quotation_id=pq.multi_product_quotation_id ";
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
			$sql = "SELECT c.country_name,pq.*,pqp.valve_txt,pqp.zipper_txt,pqp.spout_txt,mptq.profit_type,pqp.accessorie_txt,multi_quotation_number FROM multi_product_template pq LEFT JOIN country c ON c.country_id = pq.shipment_country_id LEFT JOIN multi_product_template_price pqp ON pq.product_quotation_id=pqp.product_quotation_id LEFT JOIN multi_product_template_id as mpq ON mpq.multi_product_quotation_id=pq.multi_product_quotation_id LEFT JOIN multi_product_template_quantity as mptq ON mptq.product_quotation_id=pqp.product_quotation_id  WHERE pq.added_by_user_id = '".(int)$set_user_id."' AND pq.added_by_user_type_id = '".(int)$set_user_type_id."' $str ";
		}
		if(!empty($filter_array)) {
			if(!empty($filter_array['quotation_no'])){
				$sql .= " AND mpq.multi_quotation_number = '".$filter_array['quotation_no']."'";
			}
			
			if(!empty($filter_array['customer_name'])){
				$sql .= " AND pq.customer_name LIKE '%".$filter_array['customer_name']."%'";
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

		//changed by jaya on 27-5-2016
		if (isset($data['sort'])) {
			
				
			$sql .= " ORDER BY  mptq.profit_type ";	
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		if (isset($data['sort'])) {
			
				
			$sql .= " , " . $data['sort'];	
		} else {
			$sql .= " , pq.multi_product_quotation_id ";	
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
	
		public function getQuotation($quotation_id,$getData = '*',$user_type_id='',$user_id=''){
		if($user_type_id && $user_id){
			if($user_id == 1 && $user_type_id == 1){
				$sql = "SELECT $getData,cn.country_name,mpqi.multi_quotation_number  FROM " . DB_PREFIX ."multi_product_template pq INNER JOIN " . DB_PREFIX ."country cn ON (pq.shipment_country_id=cn.country_id) INNER JOIN multi_product_template_id as mpqi ON(pq.multi_product_quotation_id=mpqi.multi_product_quotation_id)  WHERE pq.multi_product_quotation_id = '".(int)$quotation_id."'";
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
				
				$sql = "SELECT $getData,cn.country_name,mpqi.multi_quotation_number  FROM " . DB_PREFIX ."multi_product_template pq INNER JOIN " . DB_PREFIX ."country cn ON (pq.shipment_country_id=cn.country_id) INNER JOIN " . DB_PREFIX ."multi_product_template_id mpqi ON (pq.multi_product_quotation_id=mpqi.multi_product_quotation_id) WHERE (( pq.added_by_user_id = '".(int)$set_user_id."' AND pq.added_by_user_type_id = '".(int)$set_user_type_id."') $str AND pq.multi_product_quotation_id = '".(int)$quotation_id."' ";
				
			}
		}else{
			$sql = "SELECT $getData,mpqi.multi_quotation_number FROM " . DB_PREFIX ."multi_product_template pq,multi_product_template_id mpqi WHERE
			 pq.multi_product_quotation_id=mpqi.multi_product_quotation_id  AND pq.multi_product_quotation_id = '".(int)$quotation_id."'";
			 
		}
		$data = $this->query($sql);
		return $data->rows;
	}
	
	public function getNewCurrencys(){
		$data = $this->query("SELECT cn.currency_code,cs.currency_id FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country cn ON (cn.country_id=cs.country_code) WHERE cs.status = '1' AND cs.is_delete = '0' AND user_id = '".$_SESSION['ADMIN_LOGIN_SWISS']."' AND user_type_id = '".$_SESSION['LOGIN_USER_TYPE']."' ");
		if($data->num_rows){
			return  $data->rows;
		}else{
			return false;
		}
	}
	public function getData(){
		$data = $this->query("SELECT * FROM " . DB_PREFIX ." template_quotation ");
		if($data->num_rows){
			return  $data->rows;
		}else{
			return false;
		}
	}
	
	public function getQuotationMaterial($quotation_id){
		$sql = "SELECT * FROM " . DB_PREFIX ."multi_product_template_layer WHERE product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	 
	public function getQuotationQuantity($quotation_id){
		$paking_price=$this->getQuotationPackingAndTransportDetails($quotation_id);
		$data = $this->query("SELECT mpqq.product_quotation_quantity_id,mpq.product_id,mpqq.product_quotation_id,mpqq.discount, printing_effect,mpqq.quantity,mpq.height,mpq.width,mpq.gusset,mpq.volume, wastage_adding, wastage_base_price, wastage, 	packing_base_price, packing_charge, profit_base_price, profit,profit_type, mpqq.total_weight_without_zipper,mpq.adhesive_price,mpq.cpp_adhesive,mpq.adhesive_solvent_price,mpq.ink_price,mpq.ink_solvent_price,
		mpqq.total_weight_with_zipper,total_calculated_weight,cylinder_price,tool_price,gress_percentage,gress_air,gress_sea,customer_gress_percentage,mpq.ink_sel,mpq.ink_mul_by,mpq.adh_mul_by FROM " . DB_PREFIX ."multi_product_template_quantity as mpqq,multi_product_template as mpq WHERE mpqq.product_quotation_id = '".(int)$quotation_id."' AND mpqq.product_quotation_id=mpq.product_quotation_id ORDER BY mpqq.product_quotation_quantity_id") ;
		$return = array();
		if($data->num_rows){
			foreach($data->rows as $qunttData){
				$zdata = $this->query("SELECT product_quotation_price_id, product_quotation_id, product_quotation_quantity_id, transport_type, zipper_txt, valve_txt, spout_txt, accessorie_txt, total_price,total_price_with_excies,total_price_with_tax,tax_type,tax_percentage,excies, gress_price, customer_gress_price,zipper_price,valve_price,actual_courier_price,courier_charge,spout_base_price,accessorie_base_price,transport_price,make_name FROM " . DB_PREFIX ."multi_product_template_price as mpqp ,product_make as pm WHERE product_quotation_id = '".(int)$qunttData['product_quotation_id']."' AND product_quotation_quantity_id = '".(int)$qunttData['product_quotation_quantity_id']."' AND mpqp.make_pouch=pm.make_id ORDER BY transport_type");	
				if($zdata->num_rows){
				    
				    if($qunttData['profit_type']=='0')
				        $qunttData['profit_type'] = 'Rich'; 
				    else if($qunttData['profit_type']=='1')
				        $qunttData['profit_type'] = 'Poor';
				    else
				        $qunttData['profit_type'] = 'More Poor';
				        
					if(isset($zdata->rows[0]['excies']) && $zdata->rows[0]['excies']>0)
					{
						$quantity_option[$qunttData['quantity']] =  array(
						
							'Wastage' => $qunttData['wastage_base_price'],
							'Profit' => $qunttData['profit'],
							'Profit Type' => $qunttData['profit_type'],
							'Excies' => $zdata->rows[0]['excies'].' %',
							str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])) => $zdata->rows[0]['tax_percentage'].' %'					
						);
						if($qunttData['product_id']==11)
						{
							$quantity_option[$qunttData['quantity']]['Total Weight'] =  $qunttData['total_calculated_weight'].' KG';
						}
						else
						{
							if($zdata->rows[0]['zipper_txt'][0]=='T')
							{
								$quantity_option[$qunttData['quantity']] =  array(
								'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
								'Total Weight Without Zipper With Tin Tie' => $qunttData['total_weight_without_zipper'].' KG',
								'Wastage' => $qunttData['wastage_base_price'],
								'Profit' => $qunttData['profit'],
								'Profit Type' => $qunttData['profit_type'],
								'Excies' => $zdata->rows[0]['excies'].' %',
								'tax_name'=>$zdata->rows[0]['tax_name'],
								str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])) => $zdata->rows[0]['tax_percentage'].' %');
							
							
							}
							else
							{
								$quantity_option[$qunttData['quantity']] =  array(
								'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
								'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'].' KG',
								'Wastage' => $qunttData['wastage_base_price'],
								'Profit' => $qunttData['profit'],
								'Profit Type' => $qunttData['profit_type'],
								'Excies' => $zdata->rows[0]['excies'].' %',
								'tax_name'=>$zdata->rows[0]['tax_name'],
								str_replace('_',' ',strtoupper($zdata->rows[0]['tax_type'])) => $zdata->rows[0]['tax_percentage'].' %');
							}
						
						}
						if($zdata->rows[0]['spout_txt'] != 'No Spout')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper With Spout' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'],
							'Profit' => $qunttData['profit'],
							'Profit Type' => $qunttData['profit_type'],
							);
						}
					}
					else
					{
						$quantity_option[$qunttData['quantity']] =  array(
						'Wastage' => $qunttData['wastage_base_price'],
						'Profit' => $qunttData['profit'],
						'Profit Type' => $qunttData['profit_type'],
						);
						if($qunttData['product_id']==11)
						{
							$quantity_option[$qunttData['quantity']]['Total Weight'] = $qunttData['total_calculated_weight'].' KG';
						}
						else
						{
							if($zdata->rows[0]['zipper_txt'][0]=='T')
							{
								$quantity_option[$qunttData['quantity']] =  array(
								'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
								'Total Weight Without Zipper With Tin Tie' => $qunttData['total_weight_without_zipper'].' KG',
								'Wastage' => $qunttData['wastage_base_price'],
								'Profit' => $qunttData['profit'],
								'Profit Type' => $qunttData['profit_type'],
								);
							}
							else
							{
								$quantity_option[$qunttData['quantity']] =  array(
								'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
								'Total Weight Without Zipper' => $qunttData['total_weight_without_zipper'].' KG',
								'Wastage' => $qunttData['wastage_base_price'],
								'Profit' => $qunttData['profit'],
								'Profit Type' => $qunttData['profit_type'],
								);
							}
							
						}
						if($zdata->rows[0]['spout_txt'] != 'No Spout')
						{
							$quantity_option[$qunttData['quantity']] =  array(
							'Total Weight With Zipper' => $qunttData['total_weight_with_zipper'].' KG',
							'Total Weight Without Zipper With Spout' => $qunttData['total_weight_without_zipper'].' KG',
							'Wastage' => $qunttData['wastage_base_price'],
							'Profit' => $qunttData['profit'],
							'Profit Type' => $qunttData['profit_type'],
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
							
						);
						
						$txt = $zipData['zipper_txt'].' '.$zipData['valve_txt'];
						$txt .= '<br>'.$zipData['spout_txt'].' '.$zipData['accessorie_txt'].'';
						
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
								'customerGressPrice' => $zipData['customer_gress_price'],
								'product_quotation_quantity_id'=>$zipData['product_quotation_quantity_id'],
								'discount'=>$qunttData['discount'],
								'width'=>$qunttData['width'],
								'height'=>$qunttData['height'],
								'gusset'=>$qunttData['gusset'],
								'volume'=>$qunttData['volume'],
								'cylinder_price'=>$qunttData['cylinder_price'],
								'tool_price'=>$qunttData['tool_price'],
								'quantity_option'	=> $quantity_option[$qunttData['quantity']],
								'zipper_option' => $zipper_option,
								'courier_charge' => $zipData['courier_charge'],
								'actual_courier_price' => $zipData['actual_courier_price'],
								'transport_price' => $zipData['transport_price'] ,
								'packing_price'=>$paking_price['packing_price'],
								'spout_additional_packing_price'=>$paking_price['spout_additional_packing_price'],
								'gress_price'  => $zipData['gress_price'],
								'gress_percentage' => $qunttData['gress_percentage'],
								'gress_sea' => $qunttData['gress_sea'],
								'gress_air' => $qunttData['gress_air'],
								'customer_gress_percentage' => $qunttData['customer_gress_percentage'],
								'zipper_txt' => $zipData['zipper_txt'],
								'valve_txt' => $zipData['valve_txt'],
								'spout_txt' => $zipData['spout_txt'],
								'accessorie_txt' => $zipData['accessorie_txt'],
								'printing_effect' => $qunttData['printing_effect'],
								'materialData'=>$materialData,
								'make' => $zipData['make_name'],
								'product_quotation_price_id'=>$zipData['product_quotation_price_id'],
								'product_quotation_quantity_id'=>$zipData['product_quotation_quantity_id'],
								'ink_price'=>$qunttData['ink_price'],
								'ink_solvent_price'=>$qunttData['ink_solvent_price'],
								'ink_sel'=>$qunttData['ink_sel'],
								'ink_mul_by'=>$qunttData['ink_mul_by'],
								'adh_mul_by'=>$qunttData['adh_mul_by'],
								'adhesive_price'=>$qunttData['adhesive_price'],
								'cpp_adhesive'=>$qunttData['cpp_adhesive'],
								'adhesive_solvent_price'=>$qunttData['adhesive_solvent_price'],	
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
			$sql = "SELECT u.user_name, co.country_id, co.country_name, u.first_name, u.last_name, u.email_signature, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 2){
			$sql = "SELECT e.user_name, co.country_id, co.country_name, e.first_name, e.last_name, e.email_signature, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
			
		}elseif($user_type_id == 4){
			$sql = "SELECT ib.user_name,ib.discount, co.country_id, co.country_name, ib.first_name, ib.last_name, ib.email_signature, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}elseif($user_type_id == 5){
			$sql = "SELECT a.user_name, co.country_id, co.country_name, a.first_name, a.last_name, a.email_signature, a.associate_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(a.first_name,' ',a.last_name) as name, a.telephone, acc.email FROM " . DB_PREFIX ."associate a LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=a.address_id AND ad.user_type_id = '5' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=a.user_name) WHERE a.associate_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";
		}else{
			return false;
		}
		$data = $this->query($sql);
		return $data->row;
	}
	
	public function getCurrency()
	{
		$sql = "SELECT *  FROM " . DB_PREFIX . "currency  WHERE is_delete = '0' ";	
		$data = $this->query($sql);
		$result = $data->rows;
		return $result;	
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
		$sql = "SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' AND is_delete = '0' AND default_courier_id > 0";
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
		$sql .= " ORDER BY make_name";	
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
		$data = $this->query("SELECT product_spout_id, spout_name, price FROM " . DB_PREFIX . "product_spout WHERE status = '1' AND is_delete = '0' ORDER BY spout_name ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getActiveProductAccessorie(){
		$data = $this->query("SELECT product_accessorie_id, product_accessorie_name, price FROM " . DB_PREFIX . "product_accessorie WHERE status = '1' AND is_delete = '0' ORDER BY price ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getProductSize($product_id,$zipper_id,$make_id)
	{	
	    if($product_id=='11')
	        $sql  = "SELECT * FROM size_master WHERE product_id = '".$product_id."' AND product_zipper_id='".decode($zipper_id)."' ORDER BY volume + 0 ASC"; 
	    else
	        $sql  = "SELECT * FROM size_master WHERE product_id = '".$product_id."' AND product_zipper_id='".decode($zipper_id)."'"; 
 
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
		$sql1 = "SELECT price,width_to,gusset FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."') LIMIT 1";
	 	$data1 = $this->query($sql1);
	 	if( $data1->num_rows > 0)
	 	{
			if($gusset!='')
		 	{
		 		if($data1->row['gusset']==$gusset)
		 		{
			 		return 0;
		 		}
				elseif($data1->row['width_to']==$width && $data1->row['gusset']!=$gusset)
		 		{
			 		return $data1->rows;
		 		}
		 	}
		 	else
		 	{
				return 0;
		 	}
	 	}
		else
		{	if(!$data1->num_rows)
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
	
	public function getToolPrice($width,$gusset,$product_id){
    	$cond = '';
    	$cond1 ='';
    	$sql1 = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND ( width_to = '".(int)$width."')
    	AND gusset = '".$gusset."' LIMIT 1";
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
    			$sql = "SELECT price FROM `" . DB_PREFIX . "product_extra_tool_price` WHERE product_id = '" .(int)$product_id. "' AND (width_to > '".(int)$width."'
    	) ".$cond1."";
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
		if($user_type_id==4){
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
			$data = $this->query("SELECT quantity FROM " . DB_PREFIX ."roll_quantity WHERE quantity_type = '".$quantity_type."' AND status = '1' AND is_delete = '0'");
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
	
	
	public function getMaterialQuantity($material_id){
		$data = $this->query("SELECT pq.quantity FROM " . DB_PREFIX . "product_material_quantity pmq INNER JOIN " . DB_PREFIX . "product_quantity pq ON(pmq.product_quantity_id=pq.product_quantity_id) WHERE pmq.product_material_id = '".(int)$material_id."' ORDER BY pq.quantity ASC ");	
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
	
	public function getActiveProductZippers(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' ORDER BY zipper_name ASC");
		
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
	//[kinjal] : edited on [22-8-2016] for get stock price on diff. transport by condition
	public function getUserInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name, gres,gres_air,gres_sea, stock_valve_price as valve_price,stock_factory,stock_sea,stock_air FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
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
					$return['valve_price'] = $parentInfo['valve_price'];
					$return['stock_factory'] = $parentInfo['stock_factory'];
					$return['stock_sea'] = $parentInfo['stock_sea'];
					$return['stock_air'] = $parentInfo['stock_air'];
				}else{
					$return['company_name'] = '';
					$return['gres'] = '';
					$return['gres_air'] = '';
					$return['gres_sea'] = '';
					$return['valve_price'] = '';
					$return['stock_factory'] = '';
					$return['valve_price'] = '';
					$return['valve_price'] = '';
				}
			}else{
				$return['company_name'] = '';
				$return['gres'] = '';
				$return['gres_air'] = '';
				$return['gres_sea'] = '';
				$return['valve_price'] = '';
				$return['stock_factory'] = '';
				$return['valve_price'] = '';
				$return['valve_price'] = '';
			}
			$return['first_name'] = $data->row['first_name'];
			$return['last_name']  = $data->row['last_name'];
			
			return $return;
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea, stock_valve_price as valve_price,stock_factory,stock_sea,stock_air FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea, stock_valve_price as valve_price FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
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
					$widthGuseetFormula1 = $width + 10;
					$gussetFormula1 = ( $gusset + $gusset + 10 );
					//Height Gusset Formula :  heightFormula1 + gussetFormula1 + 10
					$calWidth1 = (( $widthGuseetFormula1 + $gussetFormula1) *2 )+35;				
					$calWidth = $this->numberFormate(($calWidth /1000),"3");
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
				$heightGuseetFormula1 = (($height + 10) * 2);
				$gussetFormula1 = ( $gusset + $gusset + 10 );
				//Height Gusset Formula :  heightFormula1 + gussetFormula1 + 10
				$actualHeight = ( $heightGuseetFormula1 + $gussetFormula1 + 10 );
				$calWidth = $this->numberFormate(($width /1000),"3");
				$intoWidth = 1;
			}elseif(in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
			{
				//Flat Bottom Stand Up Pouches
				$widthFormula = ($width + $gusset + $gusset + 15);
				$widthFormula1 = ($widthFormula * 1);
				$calWidth = $this->numberFormate(($widthFormula1 /1000),"3");
				$heightFormula = (($height + $gusset) * 2);
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
			if(strtolower($data->row['material_name']) == 'cpp'){
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
		$data = $this->query("SELECT zipper_name, price, product_zipper_id,Weight FROM " . DB_PREFIX . "product_zipper WHERE product_zipper_id = '".(int)$zipper_id."' ");
		if($data->num_rows){
			$return  = array();
			$return['product_zipper_id'] = $data->row['product_zipper_id'];
			$return['zipper_name'] = $data->row['zipper_name'];
			$return['price'] = $data->row['price'];
			$return['Weight'] = $data->row['Weight'];
			return $return;
		}else{
			$return  = array();
			$return['product_zipper_id'] = 0;
			$return['zipper_name'] = 'No zip';


			$return['price'] = 0.00;
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
		$data = $this->query("SELECT spout_name, price, product_spout_id,weight,by_sea,additional_packaging_price,weight_temp FROM " . DB_PREFIX . "product_spout WHERE product_spout_id = '".(int)$spout_id."' ");
		$return  = array();
		if($data->num_rows){
			$return['product_spout_id'] = $data->row['product_spout_id'];
			$return['spout_name'] = $data->row['spout_name'];
			$return['price'] = $data->row['price'];
			$return['weight'] = $data->row['weight'];
			$return['by_sea'] = $data->row['by_sea'];
			$return['additional_packaging_price'] = $data->row['additional_packaging_price'];
			$return['weight_temp'] = $data->row['weight_temp'];
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
			$return['weight_temp'] = 0.00;
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
		$sql = "SELECT wastage FROM " . DB_PREFIX . "stock_wastage WHERE from_quantity <= '".$quantity."' AND 	to_quantity >= '".$quantity."' ";		
		$data = $this->query($sql);
		if($data->num_rows){
			$wastage = $data->row['wastage'];
			return $wastage;
		}else{
			return false;
		}
	}
	//[kinjal] edited on 13-5-2017 in stock size cond
	public function getcalculateProfit($quantity,$product_id,$height,$width,$gusset,$transportation,$size_id=0){
		//printr($quantity);printr($product_id);printr($height);printr($width);printr($gusset);printr($transportation);printr($size_id);
		if($size_id!=0 && !empty($size_id))
			$size_master_id="size_master_id='".$size_id."'";
		else
			$size_master_id="height = '".$height."' AND width = '".$width."' AND gusset = '".$gusset."'";
			
		if($product_id==10)
		{
		    $size_query = $this->query("SELECT * FROM size_master WHERE product_id = '".$product_id."'");
		    foreach($size_query->rows as $query)
		    {
		        $w = round($query['width']*25.4);$h = round($query['height']*25.4);$g = round($query['gusset']*25.4);
		        if($w==$width && $h==$height && $g==$gusset)
		        {
		          $size_master_id="height = '".$query['height']."' AND width = '".$query['width']."' AND gusset = '".$query['gusset']."'";  
		        }
		    }
		}
		
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

		$qunatityRow = $this->query("SELECT template_quantity_id FROM " . DB_PREFIX . "template_quantity WHERE quantity = '".$quantity."'");
		$quantity_id = $qunatityRow->row['template_quantity_id'];		
		
		if($transportation=='YWly')//Air
		{
			$data = $this->query("SELECT profit,profit_poor,profit_more_poor FROM " . DB_PREFIX . "stock_profit WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND $size_master_id");
		}
		elseif($transportation=='c2Vh')//Sea
		{
			$data = $this->query("SELECT profit,profit_poor,profit_more_poor FROM " . DB_PREFIX . "stock_profit_by_sea WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND $size_master_id");
		
		}
		elseif($transportation=='cGlja3Vw')//pickup //mansi 22-3-2016(add condition for factory pickup)
		{
			$data = $this->query("SELECT profit,profit_poor,profit_more_poor FROM " . DB_PREFIX . "stock_profit_factory WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND $size_master_id");
		}
        //echo "SELECT profit,profit_poor,profit_more_poor FROM " . DB_PREFIX . "stock_profit_factory WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND $size_master_id";
        //printr($data);
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
	
	public function getWeightbysizemaster($product_id,$volume,$width,$height){
		$sql="SELECT weight FROM size_master WHERE product_id='".$product_id."' AND volume='".$volume."' AND width='".$width."' AND height='".$height."'";
		$data=$this->query($sql);
		return $data->row['weight'];
	
	}
	
	public function getCountryCourierCharge($country_id,$courier_id,$weight){
		$zdata = $this->query("SELECT courier_zone_id FROM " . DB_PREFIX . "courier_zone_country WHERE country_id = '".$country_id."' AND courier_id = '".$courier_id."'");			
		if(isset($zdata->row['courier_zone_id']) && $zdata->row['courier_zone_id']){
			$courier_zone_id = $zdata->row['courier_zone_id'];
		}else{
			$courier_zone_id = 1;
		}
		$data = $this->query("SELECT price FROM " . DB_PREFIX . "courier_zone_price WHERE courier_id = '".$courier_id."' AND 	courier_zone_id = '".$courier_zone_id."' AND from_kg <= '".$weight."' AND 	to_kg >= '".$weight."' ");
		//printr("SELECT price FROM " . DB_PREFIX . "courier_zone_price WHERE courier_id = '".$courier_id."' AND 	courier_zone_id = '".$courier_zone_id."' AND from_kg <= '".$weight."' AND 	to_kg >= '".$weight."'");
		if(isset($data->row['price']) && $data->row['price']){
			$price = $data->row['price'];
		}else{

			$data = $this->query("SELECT to_kg, price FROM " . DB_PREFIX . "courier_zone_price WHERE courier_id = '".$courier_id."' AND courier_zone_id = '".$courier_zone_id."' ORDER BY to_kg DESC LIMIT 0,1 ");
			$baseKg = $data->row['to_kg'];
			$basePrice = $data->row['price'];
			$perKgPrice = ($basePrice / $baseKg);
			$price = ($weight * $perKgPrice);
		}
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
			$number=$number+1;
		$width1 = ($width * $number);
		if($width1 >= 380){
			$newWidth = $width1;
		}else{
			$num = ($number+1);
			$newWidth = $this->recursiveWidth($num,$width);
		}
		return $newWidth;
	}
	
	public function getCalculateCylinderPrice($height,$widht,$gusset,$countryId,$product_id)
	{
		
		$product = $this->getProduct($product_id);
		$productGusset = explode(',',$product['gusset']);
		if(!empty($productGusset) && in_array('bottom_gusset',$productGusset) && !in_array('no_gusset_width',$productGusset) && !in_array('no_gusset_width',$productGusset) && in_array('side_gusset',$productGusset))
		{
			$widht = $widht + $gusset + 7;
			
		}
	
		$cylinderVendorPrice = $this->getCylinderVendorPrice($countryId);
		
		$calculatePrice = 0;
		if($widht >= 380){
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
			$actualHeight = (($height + 10) * 2);
			$actualHeight1 = ($actualHeight + 100 );
			
			$widhtFormula = $this->recursiveWidth(2,$widht);
			$price = ( $actualHeight1 * $widhtFormula * $cylinderVendorPrice);
			
			$calculatePrice = ceil($this->numberFormate(($price / 1000),"3"));
		}
		
		return $calculatePrice;
	}
	
	public function getCylinderBasePrice($curreny_code){
		$data = $this->query("SELECT price FROM " . DB_PREFIX ."product_cylinder_base_price WHERE currency_code = '".$curreny_code."'");
		if(isset($data->row['price']) && $data->row['price']){
			return $data->row['price'];
		}else{
			return false;
		}
	}	
	
	public function generateQuotationNumber($multi_product_quotation_id){
		$data = $this->query("SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME   = 'multi_product_template_id' ");
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
	
	public function setQuotationCurrency($quotation_id,$currency_code,$currencyRate,$source){
	
		$this->query("INSERT INTO " . DB_PREFIX . "multi_product_template_currency SET product_quotation_id = '".$quotation_id."', currency_id = '', currency_code = '".$currency_code."', currency_rate = '".(float)$currencyRate."', currency_base_rate = '', source = '".$source."', date_added = NOW()");
		return $this->getLastId();
	}
	
	public function getSelCurrencyInfo($currency_id){
		$data = $this->query("SELECT cs.price, c.currency_code FROM " . DB_PREFIX ."currency_setting cs INNER JOIN " . DB_PREFIX ."country c ON(c.country_id=cs.country_code) WHERE cs.currency_id= '".$currency_id."'");
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
		$sql = "SELECT zipper_txt,valve_txt,tax_type,tax_percentage,excies FROM " . DB_PREFIX ."multi_product_template_price WHERE product_quotation_id = '".(int)$quotation_id."' ORDER BY product_quotation_price_id ASC LIMIT 1";
		$data = $this->query($sql);
		return $data->row;
	}	
	public function addQuotationFormula($data,$type)
	{
		//printr($data);die;
	    /*printr($data['quantity']);
		$data['quantity'] =array('MTUwMDA=',
		                          'MjAwMDA=',
		                          'MzAwMDA=',
		                          );
	    printr($data['quantity']);//die;*/
		$post_height = $data['height'];
		$post_width = $data['width'];
		$gusset = $data['gusset'];
		$product_id = (int)$data['product'];
		//printr($product_id);
		if($type=='Q')
		{
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		}
		if($type=='T')
		{
			$user_type_id = $data['user_type_id'];
			$user_id = $data['user_id'];
			$userCurrency =  $this->getUserWiseCurrency($user_type_id,$user_id);
		}
		$userInfo = $this->getUserInfo($user_type_id,$user_id);
		$productName = getName('product','product_id',$product_id,'product_name');
		$courierChargeInFormula=0;
		if($product_id == "61" || $product_id == "11" || $product_id == "37" || $product_id == "38" || $product_id == "47" || $product_id == "48" || $product_id == "70" ){
			
			$other_product = $this->addQuoForOtherProduct($data,$type);
		   //die;	
			return $other_product;
			//return "Error";
		}else{
			if($type=='Q')
			{
				if(isset($data['multi_quote_id']) && $data['multi_quote_id']!='')
				{
					$multi_product_quotation_id = $data['multi_quote_id'];
				}
				else
				{
				$sql =  "INSERT INTO ".DB_PREFIX."multi_product_template_id SET  status = '0', date_added = NOW(),date_modify = NOW(), added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."'";
		
				$this->query($sql);
				$multi_product_quotation_id = $this->getLastId();
				}
			}
			if($type=='T')
			{
			    $vtext='';
			    if($data['valve'][0]==1)
			        $vtext =' With Valve';
			        
				if(isset($data['multi_quote_id']) && $data['multi_quote_id']!='')
				{
					$multi_product_quotation_id = $data['multi_quote_id'];
				}
				else
				{
				    $user_data = $this->query("INSERT INTO " . DB_PREFIX . "product_template SET title = '".$data['customer']."".$vtext."',product_name = '".$data['product']."',country = '".json_encode(array($data['country_id']))."',template_id = '".$data['template_id']."',user = '".$data['user_id']."',currency = '".$data['currency']."', transportation_type='By ".ucfirst(decode($data['transpotation'][0]))."',stock_delivery='".(decode($data['stockdelivery'][0]))."',status = '0',date_added = NOW()");
		            $multi_product_quotation_id = $this->getLastId();
				}
			}
			if(decode($data['size'])!=0)
			{	
				$size_id=decode($data['size']);
				$table_name='size_master';
				$size=$this->getSize($size_id,$table_name);
				/*$newSize[]=array('width'=>$size['width'],
					'height'=>$size['height'],
					'gusset'=>$size['gusset'],
					'volume'=>$size['volume']
					);*/
				if($data['product']=='10')
				{	//mailer bag
					$newSize[]=array('width'=>round($size['width']*25.4),
						'height'=>round($size['height']*25.4),
						'gusset'=>round($size['gusset']*25.4),
						'volume'=>$size['volume']
						);
					//printr($newSize);
				}
				elseif($data['product']=='66')
				{
				    $newSize[]=array('width'=>round($size['width']+10),
						'height'=>round($size['height']+10),
						'gusset'=>round($size['gusset']),
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
			//	echo 'hi';
			}
			else
			{	
				if(($data['product']=='18' || $data['product']=='11' || $data['product']=='16' || $data['product']=='47' || $data['product']=='48' || $data['product']=='70') && $data['volume']!='')
				{	$newSize[]=array('width'=>$data['width'],
						'height'=>$data['height'],
						'gusset'=>$data['gusset'],
						'volume'=>$data['volume']
						);
					
				}
				else
				{
					$newSize[]=array('width'=>$data['width'],
						'height'=>$data['height'],
						'gusset'=>$data['gusset'],
						'volume'=>''
						);
				}
			//	echo 'else';
			}	
			//printr($newSize);	die;
			foreach($newSize as $size)
			{		
				$post_height = $size['height'];
				$post_width = $size['width'];
				$gusset = $size['gusset'];
				$post_volume = $size['volume'];
				
				$formulla = $this->formulaHeightWidthGusset($post_height,$post_width,$gusset,$product_id);
				$actualHeight = $formulla['formula'];
				$height = $formulla['height'];
				$width = $formulla['width'];	 
				if($formulla['intoHeight'] == 1){
					$widthHeight = $height;
				}elseif($formulla['intoWidth'] == 1){
					$widthHeight = $width;
				}else{
					$widthHeight = $width;
				}
		        
		            
		        //printr($data['material']);
				if(isset($data['material']) && !empty($data['material']) && isset($data['thickness']) && !empty($data['thickness'])){
				    //if($data['product']=='61')
		              //  $total_material = 0;
		            //else
				        $total_material = count($data['material']);
				    
				   /// printr($total_material);    
					$layerPrice = array();
					$checkCppMaterial = 0;
					$setQueryData = array();
					$materialName = '';
					for($p=0;$p<$total_material;$p++)
					{  //printr($total_material);
						$setNumber = $p.'0';
						$addingActualHeight = ( $setNumber / 1000 );
						$newLayerWiseHeight = ( $actualHeight + $addingActualHeight);
						
						$gsm =$this->getMaterialGsm($data['material'][$p]);
						
					    //Thickness
    					$checkCppMaterial = $this->checkMaterial($data['material'][$p]);
    				
    					$thicknessPrice = $this->getMaterialThickmessPrice($data['material'][$p],$data['thickness'][$p]);	
    					$test['newLayerWiseHeight']=$newLayerWiseHeight;
    					$test['widthHeight']=	$widthHeight;
    					$test['data[thickness][p]']=$data['thickness'][$p];
    					$test['$gsm']=$gsm;
    									
    					$layerWiseGsmThickness[$p+1] = $this->getLayerWiseGsmThickness($newLayerWiseHeight,$widthHeight,$data['thickness'][$p],$gsm);			
    					$layerPrice[$p+1] = $this->getLayerPrice($layerWiseGsmThickness[$p+1],$thicknessPrice);
    					$materialName = getName('product_material','product_material_id',$data['material'][$p],'material_name');
    					$setQueryData[] = " layer = '".(int)($p+1)."', material_id = '".(int)$data['material'][$p]."', material_gsm = '".(float)$gsm."', material_thickness = '".$data['thickness'][$p]."', material_price = '".(float)$thicknessPrice."', material_name = '".$materialName."', layer_wise_gsmthickness = '".(float)$layerWiseGsmThickness[$p+1]."', layer_wise_price = '".(float)$layerPrice[$p+1]."', date_added = NOW()";	
    			    }
					$test['all data']=$setQueryData;
					$totalLayer = count($data['material']);
					$layerCount = (isset($p))?$p:'';
					//total GSM THICKNESS

					$test['layerCount']=$layerCount.'layer count'.$totalLayer;
					$test['layerWiseGsmThickness']=$layerWiseGsmThickness;
					$totalLayerGsmThickness = $this->sumOfNumericArray($layerWiseGsmThickness);
					//Total Layer wise Price
					$totalLayerPrice = $this->sumOfNumericArray($layerPrice);
					//printing option and printing effect || Ink Solvent 
					//change code for change function
					$stk_adh_mul = $stk_ink_mul = 0;    
					if(isset($data['printing']) && $data['printing'] == 1)
					{
						//in other paper cond it will be take normal ink price [kinjal] added on 11-4-2017
						$make_cond = $data['make'];
						if (in_array("28", $data['material']))
							$make_cond = '1'; 
						
						$printing_option = "With Printing";
						$onlyInkPrice = $this->getInkPrice1($make_cond);
						$inkSolventPrice = $this->getInkSolventPrice($layerWiseGsmThickness[1],1,$make_cond);
						if(isset($data['printing_effect']) && !empty($data['printing_effect']) && (int)$data['printing_effect'] > 0)
						{
							$printingEffectPrice = $this->getPrintingEffectPrice($data['printing_effect']);
						}
						
						$inkPrice = (($onlyInkPrice + $printingEffectPrice) * $layerWiseGsmThickness[1]);
						
						//in white paper cond ink price will be 0 [kinjal] added on 11-4-2017
						if (in_array("6", $data['material']))
							$inkPrice =0 ;
						
						//in brown paper cond ink price will be 0 [kinjal] added on 11-4-2017
						if (in_array("7", $data['material']))
							$inkPrice =0 ; 
												
						//[kinjal] added on 19-4-2017 by ink sel if yes ink price & ink solvent will be added either not
						if($data['ink_sel'][0]==1)
							$inkPrice =$inkSolventPrice=0 ;
							
						if($data['ink_sel'][0]==0 && ($data['make']=='2' || $data['make']=='6'))
						{
							$stk_ink_mul = $data['stk_ink_mul_by'];
							$inkPrice = $inkPrice*$data['stk_ink_mul_by'];
							$inkSolventPrice=$inkSolventPrice*$data['stk_ink_mul_by'];
						}

					}
					else
					{
					    $printing_option = "Without Printing";
						$onlyInkPrice = 0;
						$printingEffectPrice = 0;
						$inkPrice = 0;
						$inkSolventPrice = 0;
					}
					
					if($checkCppMaterial == 1 ){
						$make='';
						$va=3;
						$cond=1;
					}else{
						$make=$data['make'];
						$va=5;
						$cond=0;
					}
					$test['totalLayerGsmThickness']=$totalLayerGsmThickness;
					
					//Adhesive and adhesive solvent
					$adhesivePrice = $this->getAdhesivePriceCpp($layerWiseGsmThickness[1],$layerCount,1,$make,$va,$cond);
					
					$adhesiveSolventPrice = $this->getAdhesivePriceCpp($layerWiseGsmThickness[1],$layerCount,1,$make,$va,2);
					
					if($data['ink_sel'][0]==0 && ($data['make']=='2' || $data['make']=='6'))
					{
						$stk_adh_mul = $data['stk_adh_mul_by'];
						$inkPrice = $inkPrice*$data['stk_adh_mul_by'];
						$inkSolventPrice=$inkSolventPrice*$data['stk_adh_mul_by'];
					}
					if($data['product']=='10')
					{
						$adhesivePrice=0;
						$adhesiveSolventPrice=0;
						$inkPrice=0;
						$inkSolventPrice=0;
					}
					//Total Price : SUM of all price and calculate average price
					$totalPrice = $this->numberFormate(($totalLayerPrice + $inkPrice + $inkSolventPrice + $adhesivePrice + $adhesiveSolventPrice),"5") ;
					$test['totalPrice']=$totalPrice;
					//Packing price / pouch
					if($data['product']!='18' || $data['product']!='11' || $data['product']!='61')
					{
						$packingPerPouch = $this->newPackingCharges($post_height,$post_width,$gusset,$product_id);
					}
					$valveBasePrice = 0;
					if(isset($data['valve'][0]) && !empty($data['valve'][0])){
						$valveBasePrice = $userInfo['valve_price'];
					 }
					//check product weight is with zipper or without zipper
					$zipperWiseData = array();
					if(isset($data['zipper'][0]) && !empty($data['zipper'][0]))
					{
						$zipper_id = decode($data['zipper'][0]);
						$zdata = $this->getZipperInfo($zipper_id);
						$spout_id = decode($data['spout'][0]);
						$spdata = $this->getSpout($spout_id);
						
						$calculateZipperPrice = 0;

							 if($zipper_id!='5' AND $zipper_id!='6' AND $zipper_id!='7' AND $zipper_id!='9')
								{
									if($zdata['price'] > 0 ){
										$calculateZipperPrice = $this->getCalculateZipperPrice($product_id,$post_height,$post_width,$zdata['price']);
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
					//Spout
					$spoutArray = array();
                    if($data['product']!='61')
                    {
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
                    }
                    else
                    {
                        $spout_id = decode($data['spout'][0]);
                        $spoutInfo = $this->getSpout($spout_id);
                        $spout_detail = $this->spoutDetail($post_volume);
                    	if($spoutInfo){
								$spoutArray = array(
									'product_spout_id'	=> $spoutInfo['product_spout_id'],
									'spout_name'		  => $spoutInfo['spout_name'],
									'price'	  		   => $spout_detail['basic_price']
								);
							}
                    }
					$test['spoutArray']=$spoutArray;
					//Accessorie
					$accessorieArray = array();
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
					//user gress value
					$userGress = $userInfo['gres'];
					$userGressSea = $userInfo['gres_sea'];
					$userGressAir = $userInfo['gres_air'];
					
					//[kinjal] : user stock price value for type 'T' [22-8-2016]
					$userStockFactory = $userInfo['stock_factory'];
					$userStockSea = $userInfo['stock_sea'];
					$userStockAir = $userInfo['stock_air'];
					
					
					$customer_gress = 0;
					$customer_email = '';
					if(isset($data['customer_check']) ){
						$customer_email = (isset($data['customer_email']) && $data['customer_email'] != '')?$data['customer_email']:'';
						$customer_gress =  (isset($data['customer_gress']) && (int)$data['customer_gress'] > 0 )?(int)$data['customer_gress']:0;					
					}
					
					//new code for multipale quantity
					$quantityArray = $data['quantity'];
					$quantityWiseData = array();
					
					foreach($quantityArray as $key=>$eQuantity)
					{
						//printr($eQuantity);
						//Transpotation / pouch
						$transportPerPouch = 0;
						$spout_by_sea_price = 0;
						if($transportBySea){
							if($data['product']=='18')
							{
								$storezo_detail = $this->storeDetail($post_volume);
								$transportPerPouch = $storezo_detail['transport_price'];
							}
							else if($data['product']=='61')
							{
								$spout_detail = $this->spoutDetail($post_volume);
								$transportPerPouch = $spout_detail['transport_price'];
							}
							else if($data['product']=='11')
							{
							    $scoop_detail = $this->scoopDetail($post_volume);
							    $transportPerPouch = $scoop_detail['transport_price'];
							}
							else
							{	
								$transportPerPouch = $this->getCalculateTransport($post_height,$post_width,$gusset);
								$spout_by_sea_price=$this->getSpout($spout_id);
								$transportPerPouch=$transportPerPouch+$spout_by_sea_price['by_sea'];
							}
						}
						
							$spout_by_sea_price=$this->getSpout($spout_id);
							$test['transportPerPouch']=$transportPerPouch;
							if($data['product']=='18')
							{
								$storezo_detail = $this->storeDetail($post_volume);
								$originalperpouchpackingprice=$storezo_detail['packing_price'];
								$cable_ties_price_stoarezo=$storezo_detail['cable_ties_price'];
								$perpouchpackingprice=$storezo_detail['packing_price'];
								$packingPerPouch=$storezo_detail['packing_price'];
								$spout_additional_packing_price=0;
							}
							else if($data['product']=='61')
							{	$spout_detail = $this->spoutDetail($post_volume);
								$originalperpouchpackingprice=$spout_detail['packing_price'];
								$cable_ties_price_stoarezo=0;
								$perpouchpackingprice=$spout_detail['packing_price'];
								$packingPerPouch=$spout_detail['packing_price'];
								$spout_additional_packing_price=0;
							}
							else if($data['product']=='11')
							{
							    $scoop_detail = $this->scoopDetail($post_volume);
							    $originalperpouchpackingprice=$scoop_detail['packing_price'];
								$cable_ties_price_stoarezo=0;
								$perpouchpackingprice=$scoop_detail['packing_price'];
								$packingPerPouch=$scoop_detail['packing_price'];
								$spout_additional_packing_price=0;
							}
							else
							{
								$perpouchpackingprice=$packingPerPouch+$spout_by_sea_price['additional_packaging_price'];
								$originalperpouchpackingprice=$packingPerPouch;
								$spout_additional_packing_price=$spout_by_sea_price['additional_packaging_price'];
								$cable_ties_price_stoarezo=0;
							}
							$test['cable_ties_price_stoarezo']=$cable_ties_price_stoarezo;
							$test['originalperpouchpackingprice']=$originalperpouchpackingprice;
					
							$quantity = decode($eQuantity);
							//Wastage
							$wastageBase = $this->getWastage($quantity);
							$wastageBaseArray = json_decode($wastageBase);
							$wastageBase=0;
							
							foreach($wastageBaseArray as $key=>$val)
							{
								if($product_id == $key)
								{
									$wastageBase = $val;
								}
							}
							$addingWastage = 0;
							if($post_height > 500){
								$addingWastage = 10;
							}
							$totalWastage = ($wastageBase + $addingWastage);
							if($data['product']=='18')
							{
								$storezo_detail = $this->storeDetail($post_volume);
								$wastage=($storezo_detail['basic_price']*$storezo_detail['wastage'])/100;
							}
							else if($data['product']=='61')
							{	
							    $spout_detail = $this->spoutDetail($post_volume);
							    $wastage=($spout_detail['basic_price']*$spout_detail['wastage'])/100;
							}
							else if($data['product']=='11')
							{
							    $scoop_detail = $this->scoopDetail($post_volume);
							    $wastage=($scoop_detail['basic_price']*$scoop_detail['wastage'])/100;
							}
							else
							{
								$wastage = $this->numberFormate((($totalPrice * $totalWastage) / 100),"5");
							}
							
							$test['wastage']=$wastage;
							$finalPrice = ($totalPrice + $wastage);
							// price per bag
							if($data['product']=='18')
							{
								$pricePerBag_storezo = $this->storeDetail($post_volume);
								$pricePerBag = $pricePerBag_storezo['basic_price'];
								$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $wastage),"5");
							}
							else if($data['product']=='61')
							{
							    $pricePerBag_spout = $this->spoutDetail($post_volume);
							    $pricePerBag = $pricePerBag_spout['basic_price'];
								$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $wastage),"5");
							}
							else if($data['product']=='11')
							{
							    $pricePerBag_scoop = $this->scoopDetail($post_volume);
							    $pricePerBag = $pricePerBag_scoop['basic_price'];
								$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $wastage),"5");
							}
							else
							{
								$pricePerBag = $this->numberFormate(($finalPrice / 1000),"5");
								$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice ),"5");
							}
							$test['optionPrice']=$optionPrice;
							$test['pricePerBag']=$pricePerBag;
							
								if($data['product']=='18')
								{
									$storezo_det = $this->storeDetail($post_volume);
									if($data['profit'][0]==0)
										$profit=$storezo_det['profit_price_rich'];
									else
										$profit=$storezo_det['profit_price_poor'];
								}
								else if($data['product']=='61')
    							{
    							    if($data['profit'][0]==0)
										$profit= $this->spoutProfit($post_volume,$quantity,'rich');
									else
										$profit= $this->spoutProfit($quantity,'poor');
    							}
								else if($data['product']=='11')
								{
								    
									if($data['profit'][0]==0)
										$profit= $this->scoopProfit($post_volume,$quantity,'rich');
									else
										$profit= $this->scoopProfit($quantity,'poor');
								}
								else
								{
									$profit_old = $this->getcalculateProfit($quantity,$data['product'],$post_height,$post_width,$gusset,$data['transpotation'][0],decode($data['size']));
									if($data['profit'][0]==0)
									{
										$profit=$profit_old['profit'];
									}
									else if($data['profit'][0]==1)
									{
										$profit=$profit_old['profit_poor'];
									}
									else
									{
										$profit=$profit_old['profit_more_poor'];
									}
								}
						    $test['$profit']=$profit;
							$finalyPerPuchPrice = $this->numberFormate(($optionPrice + $profit + $cable_ties_price_stoarezo ),"5");
							$totalWeightWithZipper =$totalWeightWithZipper_100_plus = 0;
							$totalWeightWithoutZipper=$totalWeightWithoutZipper_100_plus=0;
							$courierChargeBaseWithZipper = 0;
							$courierChargeBaseWithoutZipper = 0;
							$zipperCalculatePrice = 0;
							
							$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $transportPerPouch ),"5");
							
							$pricePerPuchWithOption = $this->numberFormate(($optionPrice + $profit + $zipperCalculatePrice + $valveBasePrice ),"5");
							//total price without coutier charge
							$test['pricePerPuchWithOption']=$pricePerPuchWithOption;
							$test['quantity']=$quantity;
							$ftotalPrice = $this->numberFormate(($pricePerPuchWithOption * $quantity),"5");
							$test['total_price']=$ftotalPrice;
							$totalForFormula=0;
							if($product_id==11)
							{
								$getweight = $this->getWeightbysizemaster($product_id,$post_volume,$post_width,$post_height);
								$cal_weight = $getweight * $quantity;
							}
							else
							{
								$cal_weight='';
							}
							if($type=='T' && $data['charge'] == '100 Plus Weight')
							    $qty = '25000';
							else
							    $qty = $quantity;
				            if(decode($data['zipper'][0])==2 || decode($data['zipper'][0])==5 || decode($data['zipper'][0])==6 || decode($data['zipper'][0])==7  || decode($data['zipper'][0])==9)
							{
								//Total Weight without zipper
								$totalWeightWithoutZipper = $this->getCalculateWeightZipper($totalLayerGsmThickness,$quantity,2.5);
								$test['totalWeightWithoutZipper']=$totalWeightWithoutZipper;
								if(decode($data['zipper'][0])!=2)
								{
									$totalForFormula = $totalWeightWithoutZipper+($quantity*$tintieweight);
									$totalWeightWithoutZipper=$totalForFormula;
								}
								else
								{
								    $totalForFormula = $totalWeightWithoutZipper;
								}
								if($type=='T' && $data['charge'] == '100 Plus Weight')
								{   
								    $totalWeightWithoutZipper_100_plus = $this->getCalculateWeightZipper($totalLayerGsmThickness,$qty,2.5);
    								$test['totalWeightWithoutZipper']=$totalWeightWithoutZipper_100_plus;
    								if(decode($data['zipper'][0])!=2)
    								{
    									$totalForFormula_100_plus = $totalWeightWithoutZipper_100_plus+($qty*$tintieweight);
    									$totalWeightWithoutZipper_100_plus=$totalForFormula;
    								}
    								else
    								{
    								    $totalForFormula_100_plus = $totalWeightWithoutZipper_100_plus;
    								} 
								}
								
							}
							else
							{
							
								$totalWeightWithZipper = $this->getCalculateWeightZipper($totalLayerGsmThickness,$quantity,3.75);
								$totalForFormula = $totalWeightWithZipper;
								if($type=='T' && $data['charge'] == '100 Plus Weight')
								{
								    $totalWeightWithZipper_100_plus = $this->getCalculateWeightZipper($totalLayerGsmThickness,$qty,3.75);
								    $totalForFormula_100_plus = $totalWeightWithZipper_100_plus;
								}
							}
							$totalWeightWithZipper_spout = $totalWeightWithZipper_spout_100_plus=0;
							if($data['make']==5)
							{	
								
								$totalWeightWithZipper_spout = $this->getCalculateWeightZipper($totalLayerGsmThickness,$quantity,3.75);
								$spdata_weight = $this->getSpout($spout_id);
								$totalWeightWithoutZipper=(($totalWeightWithZipper_spout+($quantity*$spoutweight))*$spdata_weight['weight_temp']);
								$totalForFormula = $totalWeightWithoutZipper;
								if($type=='T' && $data['charge'] == '100 Plus Weight')
								{
								    $totalWeightWithZipper_spout_100_plus = $this->getCalculateWeightZipper($totalLayerGsmThickness,$qty,3.75);
    								$totalWeightWithoutZipper_100_plus=(($totalWeightWithZipper_spout_100_plus+($qty*$spoutweight))*$spdata_weight['weight_temp']);
    								$totalForFormula_100_plus = $totalWeightWithoutZipper_100_plus;
								}
							}
							$test['totalForFormula'] = $totalForFormula;			
							$test['$totalForFormula_100_plus'] = $totalForFormula_100_plus;			
							//courier charge
							$test['totalWeightWithZipper']=$totalWeightWithZipper;
							$courierBasePriceQuantityWise = array();
							$transportAndCoutierCharge = '';
							$actual_courier_per_kg_price = '';
							$courierChargeBaseZipper_100_plus = $actual_courier_per_kg_price_100_plus = 0;
							if($transportByAir)
							{								
                                /*printr($totalForFormula);
								printr($totalForFormula_100_plus);*/
								if($type=='T' && $data['charge'] == '100 Plus Weight')
								{
								    $courierChargeBaseZipper_100_plus = $this->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'],ceil($totalForFormula_100_plus));
								    $actual_courier_per_kg_price_100_plus = $courierChargeBaseZipper_100_plus;
								}
								    $courierChargeBaseZipper = $this->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'],ceil($totalForFormula));
								//echo "below is the courier charge data";
								$actual_courier_per_kg_price = $courierChargeBaseZipper;
								$courierBasePriceQuantityWise[$quantity] = array(
									'withZipepr'  => decode($data['zipper'][0])!=2?$courierChargeBaseZipper:0, 
									'noZipper'	=>  decode($data['zipper'][0])==2?$courierChargeBaseZipper:0, 
								);
									$test['courierChargeBaseZipper']=$courierChargeBaseZipper;
									if($fual_surcharge_base_price > 0){
										$fuleSurchargeForZipper = (($courierChargeBaseZipper * $fual_surcharge_base_price) / 100);
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
										$courierCrhgFuleZipper = ($courierChargeBaseZipper + $fuleSurchargeForZipper);
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
									$courierChargeZipper = $this->numberFormate(($courierChargeBaseZipper + $fuleSurchargeForZipper + $serviceTaxZipper + $handlingCharge),"3");
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
									if($type=='T' && $data['charge'] == '100 Plus Weight')
									{
									    if($fual_surcharge_base_price > 0){
										    $fuleSurchargeForZipper_100_plus = (($courierChargeBaseZipper_100_plus * $fual_surcharge_base_price) / 100);
    										if(decode($data['zipper'][0])==2)
    										{
    											$fuleSurchargeWithoutZipper_100_plus = $fuleSurchargeForZipper_100_plus;
    										}
    										else
    										{
    											$fuleSurchargeWithZipper_100_plus = $fuleSurchargeForZipper_100_plus;
    										}
                						}
                                        if($service_tax_base_price > 0){
    										$courierCrhgFuleZipper_100_plus = ($courierChargeBaseZipper_100_plus + $fuleSurchargeForZipper_100_plus);
    										$serviceTaxZipper_100_plus = (($courierCrhgFuleZipper_100_plus * $service_tax_base_price) / 100);
    										if(decode($data['zipper'][0])==2)
    										{
    											$courierCrhgFuleWithoutZipper_100_plus =$courierCrhgFuleZipper_100_plus;
    											$serviceTaxWithoutZipper_100_plus = $serviceTaxZipper_100_plus ;
    										}
    										else
    										{
    											$courierCrhgFuleWithZipper_100_plus = $courierCrhgFuleZipper_100_plus ;
    											$serviceTaxWithZipper_100_plus = $serviceTaxZipper_100_plus ;
    										}
            							}
                                        if($handling_base_price > 0){
            								$handlingCharge_100_plus = $handling_base_price;
            							}
                                        $courierChargeZipper_100_plus = $this->numberFormate(($courierChargeBaseZipper_100_plus + $fuleSurchargeForZipper_100_plus + $serviceTaxZipper_100_plus + $handlingCharge_100_plus),"3");
                                        if(decode($data['zipper'][0])==2)
                                        {
    										//courier charge without zipper
    										$courierChargeWithoutZipper_100_plus = $courierChargeZipper_100_plus;
    									}
    									else
    									{
    										$courierChargeWithZipper_100_plus = $courierChargeZipper_100_plus;
    									}
    									$plus_100_per_kg_price = $courierChargeZipper_100_plus / round($totalForFormula_100_plus);
    									$cal_with_ori_kgprice =  $plus_100_per_kg_price * round($totalForFormula);
    									$ftotalPrice = $this->numberFormate(($ftotalPrice + $cal_with_ori_kgprice),"3");
    									$test['$courierChargeZipper_100_plus']=$courierChargeZipper_100_plus;
    									$test['$totalForFormula_100_plus']=$totalForFormula_100_plus;
    									$test['$plus_100_per_kg_price']=$plus_100_per_kg_price;
    									$test['$totalForFormula']=$totalForFormula;
    									$test['$cal_with_ori_kgprice']=$cal_with_ori_kgprice;
    									//$test['$ftotalPrice']=$ftotalPrice;
									}
								}
								$test['ftotalPrice']=$ftotalPrice;
							$taxation='';
							$taxation_data='';
						
							$zipperData = array();
							$zipperValue = $zipperWiseData;
							$valve_text= 'no Valve';
							$courierCharge=$courierChargeWithoutZipper;		
							$spoutPrice=0;	
							$accessoriePrice=0;		
							$courierChargeInFormula = $courierChargeWithoutZipper;
							if($type=='T' && $data['charge'] == '100 Plus Weight')
							    $courierChargeInFormula_100_plus = $courierChargeWithoutZipper_100_plus;
							$withValvePrice = 0;
							$priceWithTransport = 0;
							$bySea = array();
							$byAir = array();
							$byPickup = array();	
							if($zipperValue['zipperBasePrice'] > 0){
								$courierCharge=$courierChargeWithZipper;	
								$courierChargeInFormula = $courierChargeWithZipper;
								if($type=='T' && $data['charge'] == '100 Plus Weight')
								    $courierChargeInFormula_100_plus = $courierChargeWithZipper_100_plus;
							}
							if(isset($spoutArray) && $spoutArray['price'] !=  0.000){
								$spoutPrice=$spoutArray['price'];
								if($transportByAir){
									  $courierChargeInFormula =  $courierChargeInFormula;
									  if($type=='T' && $data['charge'] == '100 Plus Weight')
								            $courierChargeInFormula_100_plus =  $courierChargeInFormula_100_plus;
								}
								if($transportBySea){
									 $transportPerPouch = $transportPerPouch + 0.10;
								}
							}
							if(isset($accessorieArray) && $accessorieArray['price'] !=0.0000){
								$accessoriePrice=$accessorieArray['price'];
							}
							if(isset($data['valve']) && in_array('1',$data['valve'])){
								$valve_text= 'with Valve';
							}
							
							if($transportByAir){
								if($type=='T' && $data['charge'] == '100 Plus Weight')
								    $withValvePrice = $this->numberFormate((($finalyPerPuchPrice + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity ) + $cal_with_ori_kgprice);
								else
								    $withValvePrice = $this->numberFormate((($finalyPerPuchPrice + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity ) + $courierChargeInFormula);
								$byAir['totalPriceByAir'] = $withValvePrice;	
								$byAir['userGress'] = $userInfo['gres_air'];
								$byAir['userStockPrice'] = $userInfo['stock_air'];
							}
							if($transportBySea){
								$priceWithTransport =  $this->numberFormate((($finalyPerPuchPrice + $transportPerPouch  + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity ));
								$bySea['totalPriceBySea'] = $priceWithTransport;
								$bySea['userGress'] = $userInfo['gres_sea'];
								$bySea['userStockPrice'] = $userInfo['stock_sea'];
							}
							if($transportByPickup){
								$priceWithPickup =$this->numberFormate((($finalyPerPuchPrice   + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity ));								
								$byPickup['totalPriceByPickup'] = $priceWithPickup;
								$byPickup['userStockPrice'] = $userInfo['stock_factory'];
							}
							
							$zipperData[] = array(
									'zip_text'		=> $zipperValue['zipperText'],
									'make'		=> $data['make'],
									'valve_text'      => $valve_text,
									'spout_txt'	=> $spoutArray['spout_name'],
									'spout_price'		=> $spoutArray['price'],
									'accessorie_txt'	=> $accessorieArray['accessorie_name'],
									'accessorie_price'		=> $accessorieArray['price'],
									'transportPerPouch'	=> $transportPerPouch,									
									'courierCharge' => $courierChargeInFormula,
									'courierCharge_100_plus' => $courierChargeInFormula_100_plus,
									'actual_courier_price'=>$actual_courier_per_kg_price,
									'actual_courier_price_100_plus'=>$actual_courier_per_kg_price_100_plus,
									'calculateZipperPrice'	=> $zipperValue['calculatePrice'],	
									'valve_price'=>$valveBasePrice,					
									'BySea'	=>  $bySea,
									'ByAir'	=> $byAir,
									'ByPickup' => $byPickup,
								);
							
							$test['$zipperData']=$zipperData;
							
								$valveTxt =$valve_text;
								$zipperText =$zipperValue['zipperText'];
								$zipperCalculatePrice=$zipperValue['calculatePrice'];
								$spoutTxt=$spoutArray['spout_name'];
								$spoutBasePrice=$spoutArray['price'];
								$accessorieTxt =$accessorieArray['accessorie_name'];
								$accessorieBasePrice=$accessorieArray['price'];
						//spout
						$spoutQuantityWiseData[] = array(
							'spout_txt'	=> $spoutArray['spout_name'],
							'price'		=> $spoutArray['price'],
						
						);
						$test['$spoutQuantityWiseData']=$spoutQuantityWiseData;
						//Accessorie
						$accessorieQuantityWiseData[] = array(
							'accessorie_txt'	=> $accessorieArray['accessorie_name'],
							'price'		=> $accessorieArray['price'],
						);
						$test['$accessorieQuantityWiseData']=$accessorieQuantityWiseData;
						//gress persontage price

						//store quantity wise information

						$quantityWiseData[$quantity] = array(
							'wastageBase'	=> $wastageBase,
							'addingWastage'  => $addingWastage,
							'wastage'		=> $wastage,
							'nativePricePerBag' => $pricePerBag,
							'totalWeightWithZipper' => ceil($totalWeightWithZipper),
							'totalWeightWithoutZipper' => ceil($totalWeightWithoutZipper),
							'total_cal_weight' => $cal_weight,
							'profit'		 => $profit,
							'profit_type' => $data['profit'][0],
							'pricePerBag'   => $pricePerBag,
							'wastageBasePrice' => $wastageBase,
							'wastageAddingPint' => $addingWastage,
							'zipperData'	=> $zipperData,
							'spoutData'     => $spoutQuantityWiseData,
							'accessorieData'	=> $accessorieQuantityWiseData,
							'ink_price'=>$inkPrice,
							'ink_solvent_price'=>$inkSolventPrice,
							'adhesive_price'=>$adhesivePrice,
							'cpp_adhesive'=>$checkCppMaterial,
							'adhesive_solvent_price'=>$adhesiveSolventPrice,
							'totalWeightWithZipper_spout_100_plus'=>ceil($totalWeightWithZipper_spout_100_plus),
							'totalWeightWithZipper_100_plus'=>ceil($totalWeightWithZipper_100_plus),
							'totalWeightWithoutZipper_100_plus'=>ceil($totalWeightWithoutZipper_100_plus),
						);
					}
					$test['$quantityWiseData']=$quantityWiseData;
					$userCountry = $this->getUserCountry($user_type_id,$user_id);
					$test['$userCountry']=$userCountry;
					//printr($test);//die;
					//Extra tool Price
					$tool_price = $this->getToolPrice($post_width,$gusset,$product_id);
					$test['$tool_price']=$tool_price;
					//(New Currency Price)
					if($user_type_id==1){
						$userCurrency = $this->getCurrencyInfo($user_id);
						$userCurrency['tool_rate']='';
					}else{
						$userCurrency =  $this->getUserWiseCurrency($user_type_id,$user_id);
					}
					$test['$userCurrency']=$userCurrency;
					//Cylinder Price
					$cylinderPrice = $this->getCalculateCylinderPrice($post_height,$post_width,$gusset,$data['country_id'],$product_id);
					
					$test['$cylinderPrice']=$cylinderPrice;
					$cylinderCurrencyPrice = $cylinderPrice;
					if($user_type_id==1){
						$currCode ='INR'; 										
					}
					else{
						$currCode=$userCurrency['currency_code'];
					}
					if($userCurrency['tool_rate']){
							$cylinderCurrencyPrice = ($cylinderPrice / $userCurrency['cylinder_rate']);
							$tool_price = ($tool_price / $userCurrency['tool_rate']);
					}
					$test['$tool_price']=$tool_price;
					$cylinderCurrencyMinPrice = $this->getCylinderBasePrice($currCode);
					if($cylinderCurrencyMinPrice && $cylinderCurrencyMinPrice >= $cylinderCurrencyPrice ){
						$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
					}else{
						$cylinderCurrencyBasePrice = $cylinderCurrencyPrice;
					} 
					if($cylinderCurrencyPrice <= $cylinderCurrencyMinPrice)
					{
						$cylinderCurrencyBasePrice = $cylinderCurrencyMinPrice;
					}
					if($userCountry){
						$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
					}else{
						$countryCode='IN';
					}
					$test['$cylinderCurrencyBasePrice']=$cylinderCurrencyBasePrice;
					$newQuotaionNumber = $this->generateQuotationNumber($multi_product_quotation_id);
					$quotation_number = 'TEM'.$newQuotaionNumber;
					$printingEffectName = getName('printing_effect','printing_effect_id',$data['printing_effect'],'effect_name');
					$productName = getName('product','product_id',$data['product'],'product_name');
					$currency = 'INR';
					$currencyPrice = '1';
					if($user_type_id!=1){
						if($userCurrency['currency_code'] && $userCurrency['product_rate']){
							$currency = $userCurrency['currency_code'];
							$currencyPrice = $userCurrency['product_rate'];
						}
					}else{
						if( $userCurrency['cylinder_rate']){
							$currencyPrice = $userCurrency['cylinder_rate'];
						}
					}
					if(isset($data['discount']))
						$data['discount']=$data['discount'];
					else
						$data['discount'] = 0.000;
					//printr($test);die;
					    if($type=='Q')
        				{ 
        					
        					$sql =  "UPDATE  ".DB_PREFIX."multi_product_template_id SET multi_quotation_number = '".$quotation_number."' WHERE multi_product_quotation_id = '".$multi_product_quotation_id."'";
        					$this->query($sql);
        					$test['$sql']=$sql;			
        					$incrementval='';
        					$decrementval='';
        					if($data['incdec']==1)
        					{
        						$incrementval = $data['incdecval'];
        					}
        					else
        					{
        						$decrementval = $data['incdecval'];
        					}
        					
        					if($data['product']=='66')
        					{
        					    $post_height = $post_height-10;
        					    $post_width = $post_width-10;
        					}
        					$sql =  "INSERT INTO ".DB_PREFIX."multi_product_template SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect_id = '".(int)$data['printing_effect']."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', volume = '".$post_volume."', layer = '".(int)$totalLayer."',ink_sel='".$data['ink_sel'][0]."',ink_mul_by='".$data['stk_adh_mul_by']."',adh_mul_by='".$data['stk_adh_mul_by']."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."',packing_price = '".(float)$originalperpouchpackingprice."', spout_additional_packing_price='".$spout_additional_packing_price."',valve_price = '".$valveBasePrice."', gress_percentage = '".$userGress."',gress_air = '".$userGressAir."',gress_sea = '".$userGressSea."', cylinder_price = '".(float)$cylinderCurrencyBasePrice."', tool_price = '".(float)$tool_price."', use_device = '".getDevice()."', status = '0', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', currency = '".$currency."', currency_price = '".$currencyPrice."', customer_name = '".addslashes($data['customer'])."', customer_email = '".$customer_email."', customer_gress_percentage = '".$customer_gress."', 
        shipment_country_id = '".$data['country_id']."', added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."',increment='".$incrementval."',decrement='".$decrementval."',multi_product_quotation_id = '".$multi_product_quotation_id."'";
        								$this->query($sql);
        								$productQuatiationId = $this->getLastId();
        					if($data['product']=='66')
        					{
        					    $post_height = $post_height+10;
        					    $post_width = $post_width+10;
        					}
        					
        					$test['$sql']=$sql;	
        								if(isset($productQuatiationId) && (int)$productQuatiationId > 0 ){
        									//quotation currency
        									if($customer_email && decode($data['sel_currency']) > 0 ){
        										$selCurrecy = $this->getCurrencyInfoOld(decode($data['sel_currency']));
        										if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
        											$selCurrencyRate = $data['sel_currency_rate'];
        										}else{
        											$selCurrencyRate = $selCurrecy['price'];
        										}
        										$this->query("INSERT INTO ".DB_PREFIX."multi_product_template_currency SET product_quotation_id = '".$productQuatiationId."', currency_id = '".$selCurrecy['currency_id']."', currency_code = '".$selCurrecy['currency_code']."',currency_rate = '".$selCurrencyRate."', currency_base_rate = '".$selCurrecy['price']."', date_added = NOW() ");
        									}
        									//INSERT QUOTATION QUANTITY TABLE 
        									if(isset($quantityWiseData) && !empty($quantityWiseData)){
        										foreach($quantityWiseData as $quantity=>$quantityValue){
        											$this->query("INSERT INTO ".DB_PREFIX."multi_product_template_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_adding = '".$quantityValue['addingWastage']."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit = '".$quantityValue['profit']."', profit_type='".$quantityValue['profit_type']."',total_weight_without_zipper = '".$quantityValue['totalWeightWithoutZipper']."', total_weight_with_zipper = '".$quantityValue['totalWeightWithZipper']."',total_calculated_weight = '".$quantityValue['total_cal_weight']."',totalWeightWithZipper_spout = '".$quantityValue['totalWeightWithZipper_spout']."',date_added = NOW(),discount='".$data['discount']."'");
        											$productQuatiationQuantityId = $this->getLastId();
        											
        											//zipperData
        											if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
        												foreach($quantityValue['zipperData'] as $zipData){
        													$pricesql = "INSERT INTO ".DB_PREFIX."multi_product_template_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', zipper_txt = '".$zipData['zip_text']."', valve_txt = '".$zipData['valve_text']."', date_added = NOW(), zipper_price = '".$zipData['calculateZipperPrice']."', spout_txt = '".$zipData['spout_txt']."', spout_base_price = '".$zipData['spout_price']."', accessorie_txt = '".$zipData['accessorie_txt']."', make_pouch = '".(int)$data['make']."',accessorie_base_price = '".$zipData['accessorie_price']."',valve_price = '".$zipData['valve_price']."' , ";
        													
        														$customerGressPrice = 0; 
        														$gressPrice = 0;
        														$totalPricWithExcies =0;
        														$totalPriceWithTax = 0;
        														$tax_type='';
        														$tax_percentage=0;
        														$totalPriceForTax = 0;
        														$excies = 0;
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
        														$test['userGress By pickup']=$zipData['ByPickup']['userGress'];
        														 if(isset($zipData['ByPickup']['userGress']) && $zipData['ByPickup']['userGress'] > 0){
        															$tot_price=$zipData['ByPickup']['totalPriceByPickup'];
        															$gressPrice = $this->numberFormate((($tot_price * $zipData['ByPickup']['userGress']) / 100),"3");
        															$test['gressPrice']=$gressPrice;
        															$test['userGress']=$zipData['ByPickup']['userGress'];
        															$etst['tot_price']=$tot_price;
        														}
        														 if(isset($zipData['ByAir']['userGress']) && $zipData['ByAir']['userGress'] > 0){
        															$tot_price=$zipData['ByAir']['totalPriceByAir'];
        															$gressPrice = $this->numberFormate((($tot_price * $zipData['ByAir']['userGress']) / 100),"3");
        														}
        														 if(isset($zipData['BySea']['userGress']) && $zipData['BySea']['userGress'] > 0){
        															$tot_price=$zipData['BySea']['totalPriceBySea'];
        															$gressPrice = $this->numberFormate((($tot_price * $zipData['BySea']['userGress']) / 100),"3");
        														}
        														
        														if(isset($taxation_data) && !empty($taxation_data))
        														{
        															$totalPriceForTax = $tot_price+$gressPrice+$customerGressPrice;
        															if($data['discount'])
        															{
        															$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
        															}
        															$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
        															$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
        															$excies =$taxation_data['excies'];
        															$tax_type=$taxation;			
        															$tax_percentage=$taxation_data[$taxation];																				
        														}	
        														 if(isset($zipData['ByAir']) && !empty($zipData['ByAir']))
        														 {
        															$courierBasePriceWithZipper = 0;
        															$courierBasePriceNoZipper = 0;
        															if(isset($courierBasePriceQuantityWise[$quantity])){
        																$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
        																$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
        															}
        															$this->query(" $pricesql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$zipData['courierCharge']."',actual_courier_price = '".$zipData['actual_courier_price']."',transport_price = '0', total_price = '".$zipData['ByAir']['totalPriceByAir']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."',color='".json_encode($data['color'])."'");
        														 }
        														 if(isset($zipData['BySea']) && !empty($zipData['BySea']))
        														{
        															$this->query(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$transportPerPouch."', total_price = '".$zipData['BySea']['totalPriceBySea']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."',color='".json_encode($data['color'])."'");
        														}													 
        														if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup']))
        														{
        														$this->query(" $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$zipData['ByPickup']['totalPriceByPickup']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."',color='".json_encode($data['color'])."'");
        													}									
        												}
        											}
        										}
        									}
        									//printr($test);die;
        									//BASE PRICE
        									$inkDefaultPrice = $this->getInkPrice1($data['make']);
        									$inkSolventDefaultPrice = $this->getInkSolventPrice('',0,$data['make']);
        									$printingEffectDefaultPrice = $this->getPrintingEffectPrice($data['printing_effect']);
        									$adhesiveDefaultPrice = $this->getAdhesivePriceCpp('','',0,$data['make'],3,0);
        									$adhesiveSolventDefaultPrice = $this->getAdhesivePriceCpp('','',0,$data['make'],5,2);
        									$cppAdhesiveDefaultPrice = $this->getAdhesivePriceCpp('','',0,'',3,1);
        									$transportWidthDefaultPrice = $this->getDefaultTransportWidthPrice($post_width,$gusset);
        									$transportHeightDefaultPrice = $this->getDefaulttransportHeightPrice($post_height);
        									$cylinderVendorDefaultPrice = $this->getCylinderVendorPrice($data['country_id']);
        									//inert base price at a time product quotaion add than taht time real price. use for history
        									
        									$this->query("INSERT INTO ".DB_PREFIX."multi_product_template_base_price SET product_quotation_id = '".(int)$productQuatiationId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$originalperpouchpackingprice."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '".$cylinderPrice."', cylinder_vendor_base_price = '".$cylinderVendorDefaultPrice."', cylinder_currency_base_price = '".$cylinderCurrencyMinPrice."', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
        									
        									//INSERT DATA FOR LAYER WISE
        									if(isset($setQueryData) && !empty($setQueryData)){
        										foreach($setQueryData as $key=>$setquery){
        										
        											$setSql = "INSERT INTO ".DB_PREFIX."multi_product_template_layer SET product_quotation_id = '".(int)$productQuatiationId."', ".$setquery;
        											$this->query($setSql);
        											
        											
        										}
        									}
        									
        									return $multi_product_quotation_id;
        								}
        					
        					    
        				}
					if($type=='T')
					{
				
						$incrementval='';
						$decrementval='';
						if($data['incdec']==1)
						{
							$incrementval = $data['incdecval'];
						}
						else
						{
							$decrementval = $data['incdecval'];
						}
						
										//quotation currency
										if($customer_email && decode($data['sel_currency']) > 0 ){
											$selCurrecy = $this->getCurrencyInfoOld(decode($data['sel_currency']));
											if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
												$selCurrencyRate = $data['sel_currency_rate'];
											}else{
												$selCurrencyRate = $selCurrecy['price'];
											}
										
										}
									
										if(isset($quantityWiseData) && !empty($quantityWiseData)){
											foreach($quantityWiseData as $quantity=>$quantityValue){
												
												if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
												$profit=$quantityValue['profit'];
													foreach($quantityValue['zipperData'] as $zipData){
														$valve=$zipData['valve_text'];
														$zipper=$zipData['zip_text'];
														$spout=$zipData['spout_txt'];
														$make_pouch=$zipData['make'];
														$accessorie=$zipData['accessorie_txt'];
														$disc_arr[$quantity]['zipPrice']=$zipData['calculateZipperPrice'];
														$disc_arr[$quantity]['spoutPrice']=$zipData['spout_price'];
														$disc_arr[$quantity]['accessoriePrice']=$zipData['accessorie_price'];
														$disc_arr[$quantity]['valvePrice']=$zipData['valve_price'];
														$disc_arr[$quantity]['total_weight_without_zipper']=$quantityValue['totalWeightWithoutZipper'];
														$disc_arr[$quantity]['total_weight_with_zipper']=$quantityValue['totalWeightWithZipper'];
														$disc_arr[$quantity]['totalcalweight']=$quantityValue['total_cal_weight'];
														$disc_arr[$quantity]['wastageBase']=$quantityValue['wastageBase'];
														$disc_arr[$quantity]['courier_charge']=$zipData['courierCharge'];
														$disc_arr[$quantity]['courierCharge_100_plus']=$zipData['courierCharge_100_plus'];
														$disc_arr[$quantity]['actual_courier_price']=$zipData['actual_courier_price'];
														$disc_arr[$quantity]['actual_courier_price_100_plus']=$zipData['actual_courier_price_100_plus'];
														$disc_arr[$quantity]['packing_price']=$packingPerPouch;
														$disc_arr[$quantity]['spout_additional_packing_price']=$spout_additional_packing_price;
														$disc_arr[$quantity]['transportPerPouch']=$transportPerPouch;
														$disc_arr[$quantity]['ink_price']=$quantityValue['ink_price'];
														$disc_arr[$quantity]['ink_solvent_price']=$quantityValue['ink_solvent_price'];
														$disc_arr[$quantity]['adhesive_price']=$quantityValue['adhesive_price'];
														$disc_arr[$quantity]['cpp_adhesive']=$quantityValue['cpp_adhesive'];
														$disc_arr[$quantity]['adhesive_solvent_price']=$quantityValue['adhesive_solvent_price'];
														$disc_arr[$quantity]['totalWeightWithZipper_spout']=$quantityValue['totalWeightWithZipper_spout'];
														
														$disc_arr[$quantity]['totalWeightWithZipper_spout_100_plus']=$quantityValue['totalWeightWithZipper_spout_100_plus'];
														$disc_arr[$quantity]['total_weight_without_zipper_100_plus']=$quantityValue['totalWeightWithoutZipper_100_plus'];
														$disc_arr[$quantity]['total_weight_with_zipper_100_plus']=$quantityValue['totalWeightWithZipper_100_plus'];
															$customerGressPrice = 0; 
															//[kinjal] : edited on [22-8-2016]
															$gressPrice = $stockPrice= $user_stock_per = $real_tot_price = $tot_price_with_stock_price = 0;
															$totalPricWithExcies =0;
															$totalPriceWithTax = 0;
															$tax_type='';
															$tax_percentage=0;
															$totalPriceForTax = 0;
															$excies = 0;
															
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
																$gressPrice = $this->numberFormate((($tot_price * $zipData['ByPickup']['userGress']) / 100),"3");
															}
															if(isset($zipData['ByAir']['userGress']) && $zipData['ByAir']['userGress'] > 0){
																$gressPrice = $this->numberFormate((($tot_price * $zipData['ByAir']['userGress']) / 100),"3");
															}
															if(isset($zipData['BySea']['userGress']) && $zipData['BySea']['userGress'] > 0){
																$gressPrice = $this->numberFormate((($tot_price * $zipData['BySea']['userGress']) / 100),"3");
															}
															
															//[kinjal] : added for stock price by transportation wise [22-8-2016]
															if(isset($zipData['ByPickup']['userStockPrice']) && $zipData['ByPickup']['userStockPrice'] > 0){
																$stockPrice = $this->numberFormate((($tot_price * $zipData['ByPickup']['userStockPrice']) / 100),"3");
																$user_stock_per = $zipData['ByPickup']['userStockPrice'];
															}
															if(isset($zipData['ByAir']['userStockPrice']) && $zipData['ByAir']['userStockPrice'] > 0){
																$stockPrice = $this->numberFormate((($tot_price * $zipData['ByAir']['userStockPrice']) / 100),"3");
																$user_stock_per = $zipData['ByAir']['userStockPrice'];
															}
															if(isset($zipData['BySea']['userStockPrice']) && $zipData['BySea']['userStockPrice'] > 0){
																$stockPrice = $this->numberFormate((($tot_price * $zipData['BySea']['userStockPrice']) / 100),"3");
																$user_stock_per = $zipData['BySea']['userStockPrice'];
															}
															$disc_arr[$quantity]['user_stock_per']=$user_stock_per;
															
															if(decode($data['stockdelivery'][0]) == 'other_country_to_customer')
															{
																$real_tot_price = $tot_price;
																$tot_price = $tot_price + $stockPrice;
																$tot_price_with_stock_price = $tot_price;
															}
															$disc_arr[$quantity]['real_tot_price']=$real_tot_price;
															$disc_arr[$quantity]['tot_price_with_stock_price']=$tot_price_with_stock_price;
															$disc_arr[$quantity]['currencyPrice']=$currencyPrice;
															//end [kinjal]
															
															
															if(isset($taxation_data) && !empty($taxation_data))
															{
																$totalPriceForTax = $tot_price+$gressPrice+$customerGressPrice;
																if($data['discount'])
																{
																$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
																}
																$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
																$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
																$excies =$taxation_data['excies'];
																$tax_type=$taxation;			
																$tax_percentage=$taxation_data[$taxation];																				
															}	
															
															 if(isset($zipData['ByAir']) && !empty($zipData['ByAir']))
															 {
																$courierBasePriceWithZipper = 0;
																$courierBasePriceNoZipper = 0;
																if(isset($courierBasePriceQuantityWise[$quantity])){
																	$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
																	$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
																}
																$total_Price=($tot_price/decode($data['quantity'][0]))/$currencyPrice;
															 }
															 if(isset($zipData['BySea']) && !empty($zipData['BySea']))
															{
																$total_Price=($tot_price/decode($data['quantity'][0]))/$currencyPrice;
															}													 
															if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup']))
															{
																$total_Price=($tot_price/decode($data['quantity'][0]))/$currencyPrice;
															}		
															//printr($currencyPrice);die;							
													}
												}
											}
										}
									//	printr($test);die;											
										$arr=array('width'=>$data['width'],
												'height'=>$data['height'],
												'gusset'=>$data['gusset'],
												'volume'=>$data['volume'],
												'qty'=>decode($data['quantity'][0]),
												'total_price'=>$total_Price,
												'template_id'=>$multi_product_quotation_id,
												'valve'=>$valve,
												'zipper'=>$zipper,
												'spout'=>$spout,
												'profit'=>$profit,
												'accessorie'=>$accessorie,
												'total_weight_with_zipper'=>$disc_arr[$quantity]['total_weight_with_zipper'],
												'totalcalweight'=>$disc_arr[$quantity]['totalcalweight'],
												'total_weight_without_zipper'=>$disc_arr[$quantity]['total_weight_without_zipper'],
												'totalWeightWithZipper_spout'=>$disc_arr[$quantity]['totalWeightWithZipper_spout'],
												'wastage'=>$disc_arr[$quantity]['wastageBase'],
												'ink_price'=>$disc_arr[$quantity]['ink_price'],
												'ink_solvent_price'=>$disc_arr[$quantity]['ink_solvent_price'],
												'adhesive_price'=>$disc_arr[$quantity]['adhesive_price'],
												'cpp_adhesive'=>$disc_arr[$quantity]['cpp_adhesive'],
												'adhesive_solvent_price'=>$disc_arr[$quantity]['adhesive_solvent_price'],
												'make_pouch'=>$make_pouch,
												'courier_charges'=>$disc_arr[$quantity]['courier_charge'],
												'courierCharge_100_plus'=>$disc_arr[$quantity]['courierCharge_100_plus'],
												'actual_courier_price'=>$disc_arr[$quantity]['actual_courier_price'],
												'actual_courier_price_100_plus'=>$disc_arr[$quantity]['actual_courier_price_100_plus'],
												'spout_price'=>$disc_arr[$quantity]['spoutPrice'],
												'accessories_price'=>$disc_arr[$quantity]['accessoriePrice'],
												'zipper_price'=>$disc_arr[$quantity]['zipPrice'],
												'valve_price'=>$disc_arr[$quantity]['valvePrice'],
												'packing_price'=>$disc_arr[$quantity]['packing_price'],
												'spout_additional_packing_price'=>$disc_arr[$quantity]['spout_additional_packing_price'],
												'transportPerPouch'=>$disc_arr[$quantity]['transportPerPouch'],
												'user_stock_per'=>$disc_arr[$quantity]['user_stock_per'],
												'real_tot_price'=>$disc_arr[$quantity]['real_tot_price'],
												'tot_price_with_stock_price'=>$disc_arr[$quantity]['tot_price_with_stock_price'],
												'currencyPrice'=>$disc_arr[$quantity]['currencyPrice'],
												'color'=>json_encode($data['color']),
												'total_weight_without_zipper_100_plus'=>$disc_arr[$quantity]['total_weight_without_zipper_100_plus'],
												'totalWeightWithZipper_spout_100_plus'=>$disc_arr[$quantity]['totalWeightWithZipper_spout_100_plus'],
												'total_weight_with_zipper_100_plus'=>$disc_arr[$quantity]['total_weight_with_zipper_100_plus']
												);
							//printr($test);die;					
							return $arr;
						}
					
					}
			}
			
		}
		
	}	
	
	public function updateQuotationStatus($quotation_id,$status_value){
		
		$sql = "UPDATE " . DB_PREFIX . "multi_product_template SET status = '".$status_value."', date_modify = NOW() WHERE multi_product_quotation_id = '" .(int)$quotation_id. "'";
		$this->query($sql);
		
		$sql = "UPDATE " . DB_PREFIX . "multi_product_template_id SET status = '".$status_value."', date_modify = NOW() WHERE multi_product_quotation_id = '" .(int)$quotation_id. "'";
		$this->query($sql);
	}
	

	public function deleteQuotation($quotation_id){

		$sql = "SELECT product_quotation_id FROM " . DB_PREFIX ."multi_product_template WHERE multi_product_quotation_id = '".(int)$quotation_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
		    foreach($data->rows as $row)
		    {
    			$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template  WHERE product_quotation_id='".$row['product_quotation_id']."'");
    			$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template_layer  WHERE product_quotation_id='".$row['product_quotation_id']."'");
    			$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template_price  WHERE product_quotation_id='".$row['product_quotation_id']."'");
    			$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template_quantity  WHERE product_quotation_id='".$row['product_quotation_id']."'");	
    			$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template_base_price  WHERE product_quotation_id='".$row['product_quotation_id']."'");
		    }
    			$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template_id  WHERE multi_product_quotation_id='".$quotation_id."'");		
		}
	}
	
	public function deleteProductQuotation($product_quotation_price_id){
		$cond='';
		$sql = "SELECT product_quotation_id,product_quotation_quantity_id  FROM " . DB_PREFIX ."multi_product_template_price WHERE product_quotation_price_id = '".(int)$product_quotation_price_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			$sql1 = "DELETE  FROM " . DB_PREFIX . "multi_product_template_price  WHERE product_quotation_price_id='".$product_quotation_price_id."'";
			$this->query($sql1);
			$sql5 = "SELECT product_quotation_id,product_quotation_quantity_id  FROM " . DB_PREFIX ."multi_product_template_price WHERE product_quotation_id = '".(int)$data->row['product_quotation_id']."'";
			$data5 = $this->query($sql5);
			if($data5->num_rows==0){
				$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template  WHERE product_quotation_id='".$data->row['product_quotation_id']."'");
				$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template_base_price  WHERE product_quotation_id='".$data->row['product_quotation_id']."'");
				$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template_currency  WHERE product_quotation_id='".$data->row['product_quotation_id']."'");
				$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template_layer  WHERE product_quotation_id='".$data->row['product_quotation_id']."'");
				$this->query("DELETE  FROM " . DB_PREFIX . "multi_product_template_quantity  WHERE product_quotation_id='".$data->row['product_quotation_id']."'");
			}				
		}
		$sql3 = "SELECT multi_product_quotation_id  FROM " . DB_PREFIX ."multi_product_template WHERE product_quotation_id = '".(int)$data->row['product_quotation_id']."'";
		$data3 = $this->query($sql3);
		$sql2 = "SELECT product_quotation_id  FROM " . DB_PREFIX ."multi_product_template WHERE multi_product_quotation_id = '".(int)$data3->row['multi_product_quotation_id']."'";
		$data2 = $this->query($sql2);
		foreach($data2->rows as $val)
		{
			$cond .= 'product_quotation_id ='.$val['product_quotation_id'] .' OR ';
		}	
		$cond=substr($cond,0,-3);
		$sql4 = "SELECT product_quotation_price_id,product_quotation_id  FROM " . DB_PREFIX ."multi_product_template_price WHERE ".$cond."";
		$data4 = $this->query($sql4);
		if($data4->num_rows==0){
			if($data2->num_rows){
			$this->deleteQuotation($data3->row['multi_product_quotation_id']);
			}
		}
	}
	
	public function upadteQuotation($quotation_id){
		$this->query("UPDATE " . DB_PREFIX . "multi_product_template SET status = '1', quotation_status = '1', date_modify = NOW() WHERE multi_product_quotation_id = '" .(int)$quotation_id. "'");
		$this->query("UPDATE " . DB_PREFIX . "multi_product_template_id SET status = '1', quotation_status = '1', date_modify = NOW() WHERE multi_product_quotation_id = '" .(int)$quotation_id. "'");
		//send emial code
		//$this->sendQuotationEmail($quotation_id);
	}
	
	public function update_discount($quantity_id,$discount)
	{
		$this->query("UPDATE " . DB_PREFIX . "multi_product_template_quantity SET discount = ".$discount." WHERE product_quotation_quantity_id = '" .(int)$quantity_id. "'");
	}
	
	public function getQuotationPackingAndTransportDetails($quotation_id){
		$sql = "SELECT pqbp.packing_price,pqbp.transport_width_base_price,pq.spout_additional_packing_price,pqbp.transport_height_base_price FROM " . DB_PREFIX ."multi_product_template pq INNER JOIN " . DB_PREFIX ."multi_product_template_base_price pqbp ON (pq.product_quotation_id=pqbp.product_quotation_id) WHERE pq.product_quotation_id = '".(int)$quotation_id."'";
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
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "multi_product_template_currency WHERE product_quotation_currency_id ='".$selCurrencyId."' AND source = '".$source."' ");
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
	
	public function getcalculatePlusMinusQuantity($quantity,$product_id,$height,$width,$gusset,$type){
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
		$data = $this->query("SELECT plus_minus_quantity	FROM " . DB_PREFIX . "product_profit WHERE product_id ='".$product_id."' AND 	quantity_id = '".$quantity_id."' AND size_from <= '".$size."' AND 	size_to >= '".$size."'");
		if($data->num_rows){
			return $data->row['plus_minus_quantity'];
		}else{
			return false;
		}
	}
	
	public function getBaseCylinderPrice($currency_id){
		$sql = "SELECT cb.price FROM " . DB_PREFIX . "currency_setting cs, product_cylinder_base_price as cb , country as c WHERE cb.currency_code='".$currency_id."' AND cb.currency_code = c.currency_code AND c.country_id =cs.country_code";
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
	//[kinjal] : edited on [22-8-2016] fatch stock_factory,stock_sea,stock_air in ib user con. 
	public function getParentInfo($user_type_id,$user_id){
		$sql = '';
		if($user_type_id == 1){
			$sql = "SELECT first_name, last_name  FROM `" . DB_PREFIX . "user` WHERE user_id = '" .(int)$user_id. "' ";
		}elseif($user_type_id == 4){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea, stock_valve_price as valve_price,stock_factory,stock_sea,stock_air FROM `" . DB_PREFIX . "international_branch` WHERE international_branch_id = '" .(int)$user_id. "'";
		}elseif($user_type_id == 5){
			$sql = "SELECT first_name, last_name, company_name, gres,gres_air,gres_sea, stock_valve_price as valve_price FROM `" . DB_PREFIX . "associate` WHERE associate_id = '" .(int)$user_id. "'";
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
	
	public function sendQuotationEmail($quotation_id,$toEmail = '',$setQuotationCurrencyId=''){		
		$getData = ' product_quotation_id, pq.added_by_user_id, pq.added_by_user_type_id, customer_name, customer_gress_percentage, customer_email, shipment_country_id, multi_quotation_number,pq.multi_product_quotation_id, quotation_type, product_id, product_name, printing_option, printing_effect, height, width, gusset,quantity_type,layer,added_by_country_id, currency, currency_id, currency_price, pq.date_added, cylinder_price,tool_price, customer_email,pq.status,discount,customer_gress_percentage,gress_percentage,gress_air,gress_sea ';
		$data = $this->getQuotation($quotation_id,$getData);
		foreach($data as $dat)
	   {
		 $qdata= $this->getQuotationQuantity($dat['product_quotation_id']);
		 if($qdata!='')
		  $quantityData[] =$qdata;
	   }
	   $str=$dat['product_name'];
		$first = strtoupper(substr(preg_replace('/(\B.|\s+)/','',$str),0,3));		
		$sub =$dat['multi_quotation_number'] .' - '.ucwords($dat['customer_name']).' - custom printed '.$first;
		$gussetvalue='';$s='';$sub2='   ';
		foreach($quantityData as $k=>$qty_data)
		{
			foreach($qty_data as $tag=>$qty)
			{
				foreach($qty as $q=>$arr)
				{
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
					$new_data[$arr[0]['text']][$key.' ('.$arr[0]['materialData'][0]['material_name'].') '][$q.'('.$arr[0]['product_quotation_quantity_id'].')'][$tag][]=$arr[0];
					if($dat['product_id'] != '10')
					{
						$sub1= ' '.$arr[0]['zipper_txt'].' '.$arr[0]['valve_txt'].''.$sub2; 
					}
					else
						$sub1= $sub2; 
				}
			}	
			$n[$tag][]=array('width'=>$arr[0]['width'],'height'=>$arr[0]['height'],'gusset'=>$arr[0]['gusset'],'volume'=>$arr[0]['volume']);
			$t=$tag;
		}
		$addedByInfo = $this->getUser($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
		$sub1= substr($sub1,0,-3);
		$sub=$sub.$sub1;
		$html='';
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
								$cylinder_base_price = $this->getBaseCylinderPrice($selCurrency['currency_code']);	
								$cylinder_currency_price = ($records[0]['cylinder_price'] / $cr['currency_rate']);
								$tool_price=0;
								if(isset($records[0]['tool_price']) && $records[0]['tool_price']>0.000)
									$tool_price = ($records[0]['tool_price'] / $cr['currency_rate']);
								if($cylinder_base_price)
								{
									if($cylinder_currency_price < $cylinder_base_price)
										$cylinder_price = $cylinder_base_price;	
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
							if($dat['quotation_type']==1)
								$type=1;	
							else
								$type=0;
								$plus_minus_quantity = $this->getcalculatePlusMinusQuantity($qty,$dat['product_id'],$records[0]['height'],$records[0]['width'],$records[0]['gusset'],$type); 
							if($cr['user']==0)
							{
								if($records[0]['gress_percentage'] != '0.000' OR $records[0]['gress_air'] !='0.000' OR $records[0]['gress_sea'] !='0.000')
								{
									$gress = '<tr><td colspan="2" >&nbsp;</td></tr><tr valign="top"><td width="60" colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Gress Percentage Prices:</b></td></tr>';								
									$newPircegress = ((($records[0]['totalPrice'] - $records[0]['customerGressPrice'] - $records[0]['gress_price']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
									$newPircegress = $newPircegress/$qty;
									$taxvaluegress='';
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										$newPircegress =$this->numberFormate(($newPircegress-($newPircegress*$records[0]['discount']/100)),"3");
										$totaldisgress = $newPircegress + ($newPircegress*$records[0]['excies']/100);
										$finaldisgress = $totaldisgress + ($totaldisgress *$records[0]['tax_percentage']/100);
										$taxvaluedisgress = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' = '.$dat['currency'].' '.round($finaldisgress,'3').' per 1 bag including of all taxes.';											
									}						
									if($dat['shipment_country_id'] == '111')
									{
										$totalgress = $newPircegress + ($newPircegress*$records[0]['excies']/100);
										$finalgress = $totalgress + ($totalgress *$records[0]['tax_percentage']/100);
										$taxvaluegress = '';
										$taxvaluegress = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' = '.$dat['currency'].' '.round($finalgress,'3').' per 1 bag including of all taxes.';
									}
									if($k=='air')
										$gp=$records[0]['gress_air'];
									if($k=='sea')
										$gp=$records[0]['gress_sea'];
									if($k=='pickup')
										$gp=$records[0]['gress_percentage'];
									if($dat['product_id'] != '10')
										$bag='plus or minus '.$plus_minus_quantity.' bags';
									else
										$bag='';
									$pmq_gress .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate($newPircegress ,"3").' per 1 bag '.$taxvaluegress.'<b> { For '.$qty.' bags '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - By '.$k.' at GP '.$gp.' % </span></td></tr>';	
									if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
									{
										if($dat['product_id'] != '10')
											$bag='plus or minus '.$plus_minus_quantity.' bags';
										else
											$bag='';
										$pmq_gress .='<tr valign="top"><td width="20px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b></td><td>'.$dat['currency'].' '.$newPircegress.' per 1 bag '.$taxvaluedisgress.'<b> { For '.$qty.' bags '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - By '.$k.' at GP '.$gp.' % </span></td></tr>';
									}
								}	
							}
							$newPirce = ((($records[0]['totalPrice'] + $records[0]['customerGressPrice']) / (float)$dat['currency_price']) / (float)$cr['currency_rate'] );
							$newPirce = $newPirce/$qty;
							$taxvalue='';
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)
							{
								$newPirce =$this->numberFormate(($newPirce-($newPirce*$records[0]['discount']/100)),"3");
								$totaldis = $newPirce + ($newPirce*$records[0]['excies']/100);
								$finaldis = $totaldis + ($totaldis *$records[0]['tax_percentage']/100);
								$taxvaluedis = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' = '.$dat['currency'].' '.round($finaldis,'3').' per 1 bag including of all taxes.';											
							}						
							if($dat['shipment_country_id'] == '111')
							{
								$total = $newPirce + ($newPirce*$records[0]['excies']/100);
								$final = $total + ($total *$records[0]['tax_percentage']/100);
								$taxvalue = '';
								$taxvalue = ' + '.$records[0]['excies'].'% Excise'.' + '.$records[0]['tax_percentage'].'% '.str_replace('_',' ',strtoupper($records[0]['tax_type'])).' = '.$dat['currency'].' '.round($final,'3').' per 1 bag including of all taxes.';
							}
							if($dat['product_id'] != '10')
									$bag='plus or minus '.$plus_minus_quantity.' bags';
								else
									$bag='';
							$pmq .= '<tr valign="top"><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate($newPirce ,"3").' per 1 bag '.$taxvalue.'<b> { For '.$qty.' bags '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - By '.$k.'.</span></td></tr>';	
							if(isset($records[0]['discount']) && $records[0]['discount']>0.000)							
							{
								if($dat['product_id'] != '10')
									$bag='plus or minus '.$plus_minus_quantity.' bags';
								else
									$bag='';
								$pmq .='<tr valign="top"><td width="20px">&nbsp;</td><td><b>Discount ('.$records[0]['discount'].' %) : </b>'.$dat['currency'].' '.$newPirce.' per 1 bag '.$taxvaluedis.'<b> { For '.$qty.' bags '.$bag.' }</b>&nbsp;<span style="color:'.$color.'"> - By '.$k.'.</span></td></tr>';
							}
							
						}
						
						$m='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Make up of pouch  : </b> Custom Printed '.$dat['product_name'];
					if($dat['product_id'] != '10')
						$m.=str_replace('<br>',' ',$records[0]['email_text']);
					$m.='</td></tr>';
					}
					$html .='<tr><td colspan="2"><b>'.($i+1).'&nbsp;&nbsp;&nbsp; Size : </b>'.$size.'</td></tr> ';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Material : </b>';
					$materialData = $this->getQuotationMaterial($records[0]['materialData'][0]['product_quotation_id']);
					if(isset($materialData) && !empty($materialData))
					{
						$materialStr = '';
						for($gi=0;$gi<count($materialData);$gi++)
						{
							$materialStr .= (int)$materialData[$gi]['material_thickness'] .' mic '. $materialData[$gi]['material_name'] .' / ';
						}
						$html .= ''.substr($materialStr,0,-3).'</td>';
					}
					$materialData='';
					$html .= '</tr>';
					$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Shipment Country : </b>'.$shippingCountry['country_name'].'</td></tr>';
					$html .=$m;
					$html .='<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Printing Effect : </b>'.$records[0]['printing_effect'].'</td></tr>';
					$html .= '<tr><td colspan="2">&nbsp;</td></tr>';
					$html .=$pmq;
					$html .=$gress;
					$html .=$pmq_gress;
					if($dat['product_id'] != '10')
					{
						$html .= '<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Cylinder price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($cylinder_price),"3").'</td></tr>';
					}
					if(isset($tool_price) && $tool_price>0.000)
					{
						$html .= '<tr><td colspan="2"><b>&nbsp;&nbsp;&nbsp;&nbsp; Extra Tool price : </b>'.$selCurrency['currency_code'].' '.$this->numberFormate(($tool_price),"3").'. This cost is applicable because the width / bottom gusset is odd in size. So the extra tool cost is applicable.</td></tr>';
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
				$html .= '<div><b>&nbsp;&nbsp;&nbsp;&nbsp; Terms : </b>'.html_entity_decode($gettermsandconditions['termsandconditions']).'</div>';
				
				if($cr['user']==1 || $cr['user']==2)
				{
					
					if($cr['user']==1)
						$email_temp[]=array('html'=>$html,'email'=>($dat['customer_email'])?$dat['customer_email']:ADMIN_EMAIL);
					if($cr['user']==2)
						$email_temp[]=array('html'=>$html,'email'=>$addedByInfo['email']);
				}
				if($toEmail!='' && $cr['user']!=0)
					$email_temp[]=array('html'=>$html,'email'=>$toEmail);
				if($cr['user']==0)
					$email_temp[]=array('html'=>$html,'email'=>ADMIN_EMAIL);
				$html='<table border="0px">';
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
		$qstr_customer = '';
		if($dat['customer_email'] != '' && $firstTimeemial == 1)
		{
			$customer_email = $dat['customer_email'];
			$qstr_customer = " sent_customer = 1, customer_email = '".$customer_email."', ";
		}
		$qstr = '';
		if($firstTimeemial)
			$qstr = 'sent_admin = 1,';
		
		$this->query("INSERT INTO `" . DB_PREFIX . "multi_product_template_email_history` SET multi_product_quotation_id = '".$dat['multi_product_quotation_id']."', customer_name = '".addslashes($dat['customer_name'])."', user_type_id = '" .$dat['added_by_user_type_id']. "', user_id = '" .$dat['added_by_user_id']. "', to_email = '".$toEmail."', from_email = '" .$formEmail. "',   $qstr_customer admin_default_email='".ADMIN_EMAIL."', status = '1', $qstr $pqcquery date_added = NOW()");
	
	}
	
	public function getEmailHistories($quotation_id)
	{
		$data = $this->query("SELECT qc.currency_code, qe.date_added, qc.source, qc.currency_rate, qe.to_email FROM " . DB_PREFIX . "multi_product_template_email_history qe RIGHT JOIN multi_product_template_currency qc ON(qe.product_quotation_currency_id = qc.product_quotation_currency_id) WHERE qe.multi_product_quotation_id ='".$quotation_id."' ORDER BY qc.date_added DESC ");
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	//edited by rohit
	//get multiquotationID by quotation number
	public function getMulQuotId($quotation_number)
	{
		$sql = "SELECT multi_product_quotation_id FROM multi_product_template_id WHERE multi_product_quotation_id = '".$quotation_number."'";
		$data = $this->query($sql);
		if($data->num_rows)
			return $data->rows;
		else
			return false;
	}
	// get all quotation data by priceId
	public function getAddProDetail($productQuotId){
		$sql = "SELECT * FROM multi_product_quotation_price WHERE product_quotation_id= '".$productQuotId."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//get product_spout data
	public function getSpoutDetail($spout_name){
		$sql = "SELECT product_spout_id FROM product_spout WHERE spout_name = '".$spout_name."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getZipDetail($zip_name){
		$sql = "SELECT product_zipper_id FROM product_zipper WHERE zipper_name = '".$zip_name."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	//get product_accessory data
	public function getAccessorieDetail($accessorie_name){
		$sql = "SELECT product_accessorie_id FROM product_accessorie WHERE product_accessorie_name = '".$accessorie_name."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getColorDetail($id){
		$sql = "SELECT color FROM multi_product_template_price WHERE 	product_quotation_quantity_id = '".$id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}	
	
	public function addTemplate($post,$quotation_no)
	{
	//printr($post);
	//	printr($quotation_no);die;
		$quotation_no = decode($quotation_no);
		$result = $this->getMulQuotId($quotation_no);
		$quotation_id = $result[0]['multi_product_quotation_id'];	
		$arr['template_id']='';	
		$sql = "SELECT customer_name,shipment_country_id,product_id,width,height,gusset,volume,printing_option,mptq.profit_type,mptq.quantity_type,mpt.product_quotation_id,mptl.layer,printing_effect_id,valve_txt,zipper_txt,make_pouch,accessorie_txt,spout_txt,color,transport_type,quantity,material_id,material_thickness,mpt.ink_sel,mpt.ink_mul_by,mpt.adh_mul_by,mpt.ink_solvent_price,mpt.ink_price FROM multi_product_template mpt LEFT JOIN multi_product_template_layer mptl ON mpt.product_quotation_id=mptl.product_quotation_id LEFT JOIN multi_product_template_quantity mptq ON mpt.product_quotation_id=mptq.product_quotation_id LEFT JOIN  multi_product_template_price mptp ON mptq.product_quotation_quantity_id=mptp.product_quotation_quantity_id WHERE mpt.multi_product_quotation_id = '".$quotation_no."' GROUP BY mptp.product_quotation_quantity_id ";		
		$data = $this->query($sql);
	
	    if($post['valve']==1)
	       $valve_text='with Valve';
	    else
	       $valve_text='no Valve';
	
		$valve='';
		if($data->num_rows)
		{
			$sql1 = "SELECT * FROM product_template WHERE template_id = '".$quotation_id."' AND country LIKE '%".'"'.$post['country_id'].'"'."%' AND  user='".$post['filter_user_name']."' AND currency='".$post['currency']."' AND transportation_type='By ".ucfirst(decode($post['transpotation'][0]))."'";		
		    $data1 = $this->query($sql1);
		    $id=array();
		    foreach($data1->rows as $row)
		    {
		        $id[]=$row['product_template_id'];
		    }
		    $product_template_id=implode(',',$id);
		    $found=0;

		    if(!empty($product_template_id))
		    {
    		    $sql_valve="SELECT valve FROM product_template_size WHERE template_id IN (".$product_template_id.")";
    		    
    		    $datav = $this->query($sql_valve);
    		    $input = array_map("unserialize", array_unique(array_map("serialize", $datav->rows)));
    		    if(array_search($valve_text, array_column($input, 'valve')) !== False)
    		        $found=1;
		    }
		    if($data1->num_rows==0 || $found==0)
    		{ 
    			$product_id='';
    			foreach($data->rows as $val)
    			{
    				if($post['valve']==1)
    				{
    				    $valve_txt= 'with Valve';
        				$valve=1;
    				}
    				else
    				{
        				$valve_txt= $val['valve_txt'];
        					if($valve_txt=='no Valve')
        						$valve=0;
        					else
        						$valve=1;
    				}
    				$qty = array(encode($val['quantity']));					
    				$zsql = "SELECT product_zipper_id FROM product_zipper WHERE zipper_name = '".$val['zipper_txt']."' ";		
    				$zdata = $this->query($zsql);
    				
    				$ssql = "SELECT product_spout_id FROM product_spout WHERE spout_name = '".$val['spout_txt']."' ";		
    				$sdata = $this->query($ssql);
    				
    				$asql = "SELECT product_accessorie_id FROM product_accessorie WHERE product_accessorie_name = '".$val['accessorie_txt']."' ";		
    				$adata = $this->query($asql);
    				
    				$sql2 = "SELECT material_id,material_thickness FROM multi_product_template_layer mptl WHERE product_quotation_id = '".$val['product_quotation_id']."'  ";		
    				$data2 = $this->query($sql2);
    				$material_thickness=array();
    				$material_id=array();
    				foreach($data2->rows as $v)
    				{
    					$material_id[]=$v['material_id'];
    					$material_thickness[]=$v['material_thickness'];
    				}
    			$profit_type=$val['profit_type'];
    				$post_data=array('customer'=>$val['customer_name'],
    						'sel_currency'=>'',
    						'country_id'=>$post['country_id'],
    						'currency_id'=>$post['currency'],
    						'currency'=>$post['currency'],
    						'taxation'=>'',
    						'product'=>$val['product_id'],
    						'valve'=>array($valve),
    						'zipper'=>array(encode($zdata->row['product_zipper_id'])),
    						'make'=>$val['make_pouch'],
    						'size'=>'',
    						'incdec'=>'',
    						'incdecval'=>'',
    						'width'=>$val['width'],
    						'height'=>$val['height'],
    						'gusset'=>$val['gusset'],
    						'volume'=>$val['volume'],
    						'printing'=>($val['printing_option']=='With Printing')?1:'',
    						'layer'=>encode($val['layer']),
    						'material'=>$material_id,
    						'thickness'=>$material_thickness,
    						'printing_effect'=>$val['printing_effect_id'],
    						'quantity_type'=>$val['quantity_type'],
    						'profit'=>array($val['profit_type']),
    						'quantity'=>$qty,
    						'spout'=>array(encode($sdata->row['product_spout_id'])),
    						'accessorie'=>array(encode($adata->row['product_accessorie_id'])),
    						'color'=>json_decode($val['color']),
    						'transpotation'=>$post['transpotation'],
    						'charge'=>$post['charge'],
    						'stockdelivery' => $post['stockdelivery'],
    						'user_type_id'=>4,
    						'user_id'=>$post['filter_user_name'],
    						'multi_quote_id'=>$arr['template_id'],
    						'adh_mul_by'=>$val['adh_mul_by'],
    						'ink_mul_by'=>$val['ink_mul_by'],
    						'ink_sel'=>$val['ink_sel'],
    						'ink_solvent_price'=>$val['ink_solvent_price'],
    						'ink_price'=>$val['ink_price'],
    						'template_id'=>$quotation_id,
    			);
    				//printr($post_data);//die;
    				$arr = $this->addQuotationFormula($post_data,'T');
    		     // printr($arr);die;
    				$sql_update = "UPDATE ". DB_PREFIX ."product_template SET userCurrencyPrice = '".$arr['currencyPrice']."' WHERE 	product_template_id = '".$arr['template_id']."' ";
    	        //printr($arr);
    	        //printr($sql_update);//die;
    	        	$this->query($sql_update);
    				
    				$product_id=$val['product_id'];
    				$arr_data[$arr['width'].'X'.$arr['height'].'X'.$arr['gusset'].'X'.$arr['zipper'].'X'.$arr['valve'].'X'.$arr['spout'].'X'.$arr['accessorie']][$arr['qty']] =$arr;
    				
    			}
    	//	printr($arr_data);
    		//	die;
    			$qty1000=0;
    			$qty2000=0;
    			$qty5000=0;
    			$qty10000=0;
    			$qty100=$qty200=$qty500=0;
    			$qty20000=$qty15000=$qty100000=$qty30000=$qty50000=0;
    			    $stock_template_per = $this->getstocktemplateper($post['filter_user_name']);
    			foreach($arr_data as $k=>$v)
    			{
                
    				foreach($v as $key=>$da)
    				{//printr($key);
        				$desc_array[$key]=array('total_weight_with_zipper'=>$da['total_weight_with_zipper'],
        							'totalcalweight'=>$da['totalcalweight'],
        							'total_weight_without_zipper'=>$da['total_weight_without_zipper'],
        							'make_pouch'=>$da['make_pouch'],
        							'wastage'=>$da['wastage'],
        							'valve_price'=>$da['valve_price'],
        							'spout_price'=>$da['spout_price'],
        							'courier_charges'=>$da['courier_charges'],
        							'courierCharge_100_plus'=>$da['courierCharge_100_plus'],
        							'actual_courier_price'=>$da['actual_courier_price'],
        							'actual_courier_price_100_plus'=>$da['actual_courier_price_100_plus'],
        							'accessories_price'=>$da['accessories_price'],
        							'zipper_price'=>$da['zipper_price'],
        							'profit'=>$da['profit'],
        							'profit_type'=>$profit_type,
        							'packing_price'=>$da['packing_price'],
        							'spout_additional_packing_price'=>$da['spout_additional_packing_price'],
        							'user_stock_per'=>$da['user_stock_per'],
        							'real_tot_price'=>$da['real_tot_price'],
        							'tot_price_with_stock_price'=>$da['tot_price_with_stock_price'],
        							'currencyPrice'=>$da['currencyPrice'],
        							'transportPerPouch'=>$da['transportPerPouch'],
        							'size'=>$da['volume'],
        							'ink_price' =>$da['ink_price'],
            						'ink_solvent_price' => $da['ink_solvent_price'],
            						'adhesive_price' =>$da['adhesive_price'], 
            						'cpp_adhesive' => $da['cpp_adhesive'],
            						'adhesive_solvent_price' => $da['adhesive_solvent_price'],
        							'totalWeightWithZipper_spout'=>$da['totalWeightWithZipper_spout'],
        							'total_weight_without_zipper_100_plus'=>$da['total_weight_without_zipper_100_plus'],
        							'total_weight_with_zipper_100_plus'=>$da['total_weight_with_zipper_100_plus'],
        							'totalWeightWithZipper_spout_100_plus'=>$da['totalWeightWithZipper_spout_100_plus'],
        							);
    					$profit_price=$this->getProfitPrice($post['filter_user_name'],decode($post['transpotation'][0]),$key);
    					
    					//printr($desc_array);die;
    					if($product_id=='18')
    					{
    						if($key=='100')
    						{
    							$qty100=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));							
    						}
    						if($key=='200')
    							$qty200=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));
    						if($key=='500')
    							$qty500=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));
    						if($key=='1000')
    						{
    							$qty1000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));	
    						}
    					}
    					else if($product_id=='47' || $product_id=='48' || $product_id=='70')
    					{
    						if($key=='1000')
    						{
    							$qty1000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));							
    						}
    						if($key=='2000')
    							$qty2000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));
    						if($key=='5000')
    							$qty5000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));
    						if($key=='10000')
    						{
    							$qty10000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));	
    						}
    						if($key=='50000')
    						{
    							$qty50000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));	
    						}
    						if($key=='100000')
    						{
    							$qty100000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));	
    						}
    					}
    					else if($product_id=='61')
    					{
    						if($key=='10000')
    						{
    							$qty10000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));							
    						}
    						if($key=='15000')
    							$qty15000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));
    						if($key=='20000')
    							$qty20000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));
    						if($key=='30000')
    						{
    							$qty30000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));	
    						}
    						if($key=='50000')
    						{
    							$qty50000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));	
    						}
    						if($key=='100000')
    						{
    							$qty100000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));	
    						}
    					}
    					else
    					{
    						if($key=='1000')
    						{
    							$qty1000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));							
    						}
    						if($key=='2000')
    							$qty2000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));
    						if($key=='5000')
    							$qty5000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));
    						if($key=='10000')
    						{
    							$qty10000=($da['total_price']+($da['total_price']*($stock_template_per['stock_template_percentage']/100)));	
    						}
    					
    					
    					}
    					
    				}
    			    //printr($desc_array);
    				$sql2="INSERT INTO ".DB_PREFIX."product_template_size SET template_id = '".(int)$da['template_id']."',height = '".(float)$da['height']."', width = '".(float)$da['width']."', gusset = '".(float)$da['gusset']."', volume = '".$da['volume']."', quantity1000 = '".$qty1000."',quantity2000 = '".$qty2000."',quantity5000 = '".$qty5000."',quantity10000 = '".$qty10000."',quantity100='".$qty100."',quantity200='".$qty200."',quantity500='".$qty500."',quantity15000='".$qty15000."',quantity50000='".$qty50000."',quantity100000='".$qty100000."',quantity20000='".$qty20000."',quantity30000='".$qty30000."',valve = '".$da['valve']."', zipper = '".$da['zipper']."', spout = '".$da['spout']."',accessorie = '".$da['accessorie']."',color=".json_encode($da['color']).",date_added = NOW(), date_modify=NOW(), is_delete=0 ,description='".json_encode($desc_array)."'";
    				//echo $sql2;die;
    				$this->query($sql2);
    			}
    			//die;
    			
    		    
    		}
		
		else
			return 'no';
		}
		else
		{
			return false;
		}
	}
	
	//ruchi
	public function getProfitPrice($branchId,$transport,$qty)
	{	
		$sql = "SELECT tq.template_quantity_id,tq.quantity,IFNULL(tpp.profit_price,0.00) AS profit_price FROM template_quantity AS tq LEFT JOIN  template_profit_price AS tpp ON(tq.template_quantity_id=tpp.profit_qty_id AND tpp.ib_id='".$branchId."' AND tpp.transport='".$transport."') WHERE tq.status = 1 AND tq.quantity='".$qty."' ORDER BY tq.quantity ASC";
		$data = $this->query($sql);
		if($data->num_rows)
		{
			return $data->row['profit_price'];
		}
		else
		{
			return false;
		}		
	}
	//end
	
	public function updatePrice($data)
	{	
		$sql = "UPDATE ". DB_PREFIX ."multi_product_template_price SET total_price = '".$data['price']."' WHERE product_quotation_price_id = '".$data['product_quotation_price_id']."' ";
		$data = $this->query($sql);			
		return $data;
	}
	public function getstocktemplateper($user_id)
	{
		$sql = "SELECT * FROM international_branch WHERE international_branch_id = '".$user_id."'";
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
	public function getcurrency_name($curr_id)
	{
		$sql = "SELECT currency_code FROM country where country_id='".$curr_id."'"; 
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
	public function checkProductTintie($product_id){
		$data = $this->query("SELECT tintie_available,spout_pouch_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row;	
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

		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0' $tin ORDER BY zipper_name ASC");
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
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
	
	public function storeDetail($volume)
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "storezo_detail WHERE select_volume = '".$volume."' AND status='1' ");
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function scoopDetail($volume)
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "scoop_detail WHERE select_volume = '".$volume."' AND status='1' ");
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function scoopProfit($post_volume,$qty,$type)
	{
		$data = $this->query("SELECT profit FROM " . DB_PREFIX . "profit_scoop as ps,scoop_detail as sd WHERE ps.qty = '".$qty."' AND sd.select_volume = '".$post_volume."' AND ps.scoop_id=sd.scoop_id AND profit_type = '".$type."'");
		if($data->num_rows)
			return $data->row['profit'];
		else
			return false;
	}
	public function spoutDetail($volume)
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "spout_detail WHERE select_volume = '".$volume."' AND status='1' ");
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function spoutProfit($post_volume,$qty,$type)
	{
		$data = $this->query("SELECT profit FROM " . DB_PREFIX . "profit_spout as ps,spout_detail as sd WHERE ps.qty = '".$qty."' AND sd.select_volume = '".$post_volume."' AND ps.spout_id=sd.spout_id AND profit_type = '".$type."'");
		if($data->num_rows)
			return $data->row['profit'];
		else
			return false;
	}
	public function oxyDetail($volume)
	{
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "oxy_silica_detail WHERE select_volume = '".$volume."' AND status='1' ");
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function cupsDetail($volume,$product_id)
	{
		//printr("SELECT * FROM " . DB_PREFIX . "cup_detail WHERE select_volume = '".$volume."' AND status='1' AND product_id='".$product_id."' ");
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "cup_detail WHERE select_volume = '".$volume."' AND status='1' AND product_id='".$product_id."' ");
		if($data->num_rows)
			return $data->row;
		else
			return false;
	}
	public function cupsProfit($volume,$quantity,$type,$product_id,$trans)
	{
		//printr("SELECT profit FROM " . DB_PREFIX . "cup_detail as cd, profit_cup as pc WHERE cd.select_volume = '".$volume."' AND cd.status='1' AND pc.qty = '".$quantity."' AND pc.profit_type = '".$type."' AND cd.product_id = '".$product_id."' AND pc.transportation = '".$trans."' AND cd.cup_id=pc.cup_id");
		$data = $this->query("SELECT * FROM " . DB_PREFIX . "cup_detail as cd, profit_cup as pc WHERE cd.select_volume = '".$volume."' AND cd.status='1' AND pc.qty = '".$quantity."' AND pc.profit_type = '".$type."' AND cd.product_id = '".$product_id."' AND pc.transportation = '".$trans."' AND cd.cup_id=pc.cup_id");
		if($data->num_rows)
			return $data->row['profit'];
		else
			return false;
	}
	public function oxyProfit($post_volume,$qty,$type)
	{
		//echo "SELECT profit FROM " . DB_PREFIX . "oxy_silica_detail as ps,oxy_silica_detail as sd WHERE ps.qty = '".$qty."' AND sd.select_volume = '".$post_volume."' AND ps.oxy_silica_id=sd.oxy_silica_id AND profit_type = '".$type."'";
		$data = $this->query("SELECT profit FROM " . DB_PREFIX . "profit_oxy as ps,oxy_silica_detail as sd WHERE ps.qty = '".$qty."' AND sd.select_volume = '".$post_volume."' AND ps.oxy_silica_id=sd.oxy_silica_id AND profit_type = '".$type."'");
		if($data->num_rows)
			return $data->row['profit'];
		else
			return false;
	}
	public function addQuoForOtherProduct($data,$type)
	{
	    
	    //printr($data);die;
	    $post_height = $data['height'];
		$post_width = $data['width'];
		$gusset = $data['gusset'];
		$product_id = (int)$data['product'];
		if($type=='Q')
		{
			$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
			$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
		}
		if($type=='T')
		{
			$user_type_id = $data['user_type_id'];
			$user_id = $data['user_id'];
			$userCurrency =  $this->getUserWiseCurrency($user_type_id,$user_id);
		}
		//printr($userCurrency);
		$userInfo = $this->getUserInfo($user_type_id,$user_id);
		$productName = getName('product','product_id',$product_id,'product_name');
		$courierChargeInFormula=0;
	    if($type=='Q')
		{
			if(isset($data['multi_quote_id']) && $data['multi_quote_id']!='')
			{
				$multi_product_quotation_id = $data['multi_quote_id'];
			}
			else
			{
				$sql =  "INSERT INTO ".DB_PREFIX."multi_product_template_id SET  status = '0', date_added = NOW(),date_modify = NOW(), added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."'";
		
				$this->query($sql);
				$multi_product_quotation_id = $this->getLastId();
			}
		}
		if($type=='T')
		{
			if(isset($data['multi_quote_id']) && $data['multi_quote_id']!='')
			{
				$multi_product_quotation_id = $data['multi_quote_id'];
			}
			else
			{
				$user_data = $this->query("INSERT INTO " . DB_PREFIX . "product_template SET title = '".$data['customer']."',product_name = '".$data['product']."',country = '".json_encode(array($data['country_id']))."',template_id = '".$data['template_id']."',user = '".$data['user_id']."',currency = '".$data['currency']."', transportation_type='By ".ucfirst(decode($data['transpotation'][0]))."',stock_delivery='".(decode($data['stockdelivery'][0]))."',status = '0',date_added = NOW()");
		        $multi_product_quotation_id = $this->getLastId();
			}
		}
			if(decode($data['size'])!=0)
			{	
				$size_id=decode($data['size']);
				$table_name='size_master';
				$size=$this->getSize($size_id,$table_name);
				$newSize[]=array('width'=>$size['width'],
					'height'=>$size['height'],
					'gusset'=>$size['gusset'],
					'volume'=>$size['volume']
					);
			}
			else
			{	
				if(($data['product']=='18' || $data['product']=='11' || $data['product']=='16' || $data['product']=='47'|| $data['product']=='48' || $data['product']=='70') && $data['volume']!='')
				{	$newSize[]=array('width'=>$data['width'],
						'height'=>$data['height'],
						'gusset'=>$data['gusset'],
						'volume'=>$data['volume']
						);
					
				}
				else
				{
					$newSize[]=array('width'=>$data['width'],
						'height'=>$data['height'],
						'gusset'=>$data['gusset'],
						'volume'=>$data['volume']
						);
				}
			}
			//printr($newSize);
			foreach($newSize as $size)
			{
			    $post_height = $size['height'];
    			$post_width = $size['width'];
    			$gusset = $size['gusset'];
    			$post_volume = $size['volume'];
    			
    			$formulla = $this->formulaHeightWidthGusset($post_height,$post_width,$gusset,$product_id);
    			//printr($formulla);
    			$test['$formulla']=$formulla;
    			$actualHeight = $formulla['formula'];
    			$height = $formulla['height'];
    			$width = $formulla['width'];	 
    			if($formulla['intoHeight'] == 1){
    				$widthHeight = $height;
    			}elseif($formulla['intoWidth'] == 1){
    				$widthHeight = $width;
    			}else{
    				$widthHeight = $width;
    			}
    			$valveBasePrice = 0;
				if(isset($data['valve'][0]) && !empty($data['valve'][0])){
					$valveBasePrice = $userInfo['valve_price'];
				 }
				//check product weight is with zipper or without zipper
				$zipperWiseData = array();
				if(isset($data['zipper'][0]) && !empty($data['zipper'][0]))
				{
					$zipper_id = decode($data['zipper'][0]);
					$zdata = $this->getZipperInfo($zipper_id);
					$spout_id = decode($data['spout'][0]);
					$spdata = $this->getSpout($spout_id);
					
					$calculateZipperPrice = 0;

						 if($zipper_id!='5' AND $zipper_id!='6' AND $zipper_id!='7' AND $zipper_id!='9')
							{
								if($zdata['price'] > 0 ){
									$calculateZipperPrice = $this->getCalculateZipperPrice($product_id,$post_height,$post_width,$zdata['price']);
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
						//$spoutweight = $spdata['weight'];
						$tintieweight=$zdata['Weight'];
				}
				$test['zipperWiseData']=$zipperWiseData;	
				$test['zipperBasePrice']=$zipperBasePrice;	
				$test['zipperCalculatePrice']=$zipperCalculatePrice;
				
				//Spout
				$spoutArray = array();
				if($data['product']!='61')
                {
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
                }
                else
                {
                    $spout_id = decode($data['spout'][0]);
                    $spoutInfo = $this->getSpout($spout_id);
                    $spout_detail = $this->spoutDetail($post_volume);
                    //printr($spout_detail);
                	if($spoutInfo){
							$spoutArray = array(
								'product_spout_id'	=> $spoutInfo['product_spout_id'],
								'spout_name'		  => $spoutInfo['spout_name'],
								'price'	  		   => $spout_detail['basic_price']
							);
						}
                }
				$test['spoutArray']=$spoutArray;
				//Accessorie
				$accessorieArray = array();
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
				$quantityArray = $data['quantity'];
				$quantityWiseData = array();
				
				foreach($quantityArray as $key=>$eQuantity)
				{
				        $transportPerPouch = 0;
						$spout_by_sea_price = 0;
						if($transportBySea){
							if($data['product']=='61')
							{
								$spout_detail = $this->spoutDetail($post_volume);
								$transportPerPouch = $spout_detail['transport_price'];
							}
							else if($data['product']=='11')
							{
							    $scoop_detail = $this->scoopDetail($post_volume);
							    $transportPerPouch = $scoop_detail['transport_price'];
							}
							else if($data['product']=='37')
							{
							    $oxy_detail = $this->oxyDetail($post_volume);
							    $transportPerPouch = $oxy_detail['transport_price'];
							}
							else if($data['product']=='38')
							{
							    $oxy_detail = $this->oxyDetail($post_volume);
							    $transportPerPouch = $oxy_detail['transport_price'];
							}
							else if($data['product']=='47' || $data['product']=='48' || $data['product']=='70')
							{
							    $cupsDetail = $this->cupsDetail($post_volume,$data['product']);
							    $transportPerPouch = $cupsDetail['transport_price'];
							}
				        }
						$spout_by_sea_price=$this->getSpout($spout_id);
						$test['transportPerPouch']=$transportPerPouch;
						if($data['product']=='61')
						{	$spout_detail = $this->spoutDetail($post_volume);
							$originalperpouchpackingprice=$spout_detail['packing_price'];
							$cable_ties_price_stoarezo=0;
							$perpouchpackingprice=$spout_detail['packing_price'];
							$packingPerPouch=$spout_detail['packing_price'];
							$spout_additional_packing_price=0;
						}
						else if($data['product']=='11')
						{
						    $scoop_detail = $this->scoopDetail($post_volume);
						    $originalperpouchpackingprice=$scoop_detail['packing_price'];
							$cable_ties_price_stoarezo=0;
							$perpouchpackingprice=$scoop_detail['packing_price'];
							$packingPerPouch=$scoop_detail['packing_price'];
							$spout_additional_packing_price=0;
						}
						else if($data['product']=='38')
						{
						    $oxy_detail = $this->oxyDetail($post_volume);
						    $originalperpouchpackingprice=$oxy_detail['packing_price'];
							$cable_ties_price_stoarezo=0;
							$perpouchpackingprice=$oxy_detail['packing_price'];
							$packingPerPouch=$oxy_detail['packing_price'];
							$spout_additional_packing_price=0;
						}
						else if($data['product']=='37')
						{
						    $oxy_detail = $this->oxyDetail($post_volume);
						    $originalperpouchpackingprice=$oxy_detail['packing_price'];
							$cable_ties_price_stoarezo=0;
							$perpouchpackingprice=$oxy_detail['packing_price'];
							$packingPerPouch=$oxy_detail['packing_price'];
							$spout_additional_packing_price=0;
						}
						else if($data['product']=='47' || $data['product']=='48' || $data['product']=='70')
						{
						    $cupsDetail = $this->cupsDetail($post_volume,$data['product']);
						    $originalperpouchpackingprice=$cupsDetail['packing_price'];
							$cable_ties_price_stoarezo=0;
							$perpouchpackingprice=$cupsDetail['packing_price'];
							$packingPerPouch=$cupsDetail['packing_price'];
							$spout_additional_packing_price=0;
						}
						
						$test['cable_ties_price_stoarezo']=$cable_ties_price_stoarezo;
						$test['originalperpouchpackingprice']=$originalperpouchpackingprice;
				
						$quantity = decode($eQuantity);
						$addingWastage = 0;
						if($post_height > 500){
							$addingWastage = 10;
						}
						$totalWastage = ($wastageBase + $addingWastage);
						if($data['product']=='61')
						{	
						    $spout_detail = $this->spoutDetail($post_volume);
						    $wastage=($spout_detail['basic_price']*$spout_detail['wastage'])/100;
						}
						else if($data['product']=='11')
						{
						    $scoop_detail = $this->scoopDetail($post_volume);
						    $wastage=($scoop_detail['basic_price']*$scoop_detail['wastage'])/100;
						}
						else if($data['product']=='38')
						{
						    $oxy_detail = $this->oxyDetail($post_volume);
						    $wastage=($oxy_detail['basic_price']*$oxy_detail['wastage'])/100;
						}
						else if($data['product']=='37')
						{
						    $oxy_detail = $this->oxyDetail($post_volume);
						    $wastage=($oxy_detail['basic_price']*$oxy_detail['wastage'])/100;
						}
						else if($data['product']=='47' || $data['product']=='48' || $data['product']=='70')
						{
						    $cupsDetail = $this->cupsDetail($post_volume,$data['product']);
						    $wastage=($cupsDetail['basic_price']*$cupsDetail['wastage'])/100;
						}
						$test['wastage']=$wastage;
						$finalPrice = $wastage;
						// price per bag
						if($data['product']=='61')
						{
						    $pricePerBag_spout = $this->spoutDetail($post_volume);
						    $pricePerBag = $pricePerBag_spout['basic_price'];
							$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $wastage),"5");
							$spoutweight = $pricePerBag_spout['weight'];
						}
						else if($data['product']=='11')
						{
						    $pricePerBag_scoop = $this->scoopDetail($post_volume);
						    $pricePerBag = $pricePerBag_scoop['basic_price'];
							$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $wastage),"5");
						}
						else if($data['product']=='37')
						{
						    $pricePerBag_oxy = $this->oxyDetail($post_volume);
						    $pricePerBag = $pricePerBag_oxy['basic_price'];
							$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $wastage),"5");
							$oxy = $pricePerBag_oxy['weight'];
						}
						else if($data['product']=='38')
						{
						    $pricePerBag_oxy = $this->oxyDetail($post_volume);
						    $pricePerBag = $pricePerBag_oxy['basic_price'];
							$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $wastage),"5");
							$oxy = $pricePerBag_oxy['weight'];
						}
						else if($data['product']=='47' || $data['product']=='48' || $data['product']=='70')
						{
						    $pricePerBag_cup = $this->cupsDetail($post_volume,$data['product']);
						    $pricePerBag = $pricePerBag_cup['basic_price'];
							$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $wastage),"5");
							$cup = $pricePerBag_cup['weight'];
						}
						$test['optionPrice']=$optionPrice;
						$test['pricePerBag']=$pricePerBag;
						
						if($data['product']=='61')
						{
						    if($data['profit'][0]==0)
								$profit= $this->spoutProfit($post_volume,$quantity,'rich');
							else
								$profit= $this->spoutProfit($post_volume,$quantity,'poor');
						}
						else if($data['product']=='11')
						{
						    
							if($data['profit'][0]==0)
								$profit= $this->scoopProfit($post_volume,$quantity,'rich');
							else
								$profit= $this->scoopProfit($post_volume,$quantity,'poor');
						}
						else if($data['product']=='37' || $data['product']=='38')
						{
						   //echo $data['profit'][0]; 
							if($data['profit'][0]==0)
								$profit= $this->oxyProfit($post_volume,$quantity,'rich');
							else
								$profit= $this->oxyProfit($post_volume,$quantity,'poor');
						}
						else if($data['product']=='47' || $data['product']=='48' || $data['product']=='70')
						{
						    if($transportByAir)
						        $trans = 'air';
						    else if($transportBySea)
						        $trans = 'sea';
						    else if($transportByPickup)   
						        $trans = 'pickup';
						    
						    if($data['profit'][0]==0)
								$profit= $this->cupsProfit($post_volume,$quantity,'rich',$data['product'],$trans);
							else
								$profit= $this->cupsProfit($post_volume,$quantity,'poor',$data['product'],$trans);
						}
						//printr($profit);
						//printr($test);
						$finalyPerPuchPrice = $this->numberFormate(($optionPrice + $profit + $cable_ties_price_stoarezo ),"5");
						$totalWeightWithZipper = 0;
						$totalWeightWithoutZipper=0;
						$courierChargeBaseWithZipper = 0;
						$courierChargeBaseWithoutZipper = 0;
						$zipperCalculatePrice = 0;
						
						$optionPrice = $this->numberFormate(($pricePerBag + $perpouchpackingprice + $transportPerPouch ),"5");
						
						$pricePerPuchWithOption = $this->numberFormate(($optionPrice + $profit + $zipperCalculatePrice + $valveBasePrice ),"5");
						//total price without coutier charge
						$test['pricePerPuchWithOption']=$pricePerPuchWithOption;
						$test['quantity']=$quantity;
						$ftotalPrice = $this->numberFormate(($pricePerPuchWithOption * $quantity),"5");
						$totalForFormula=0;
						//printr($test);
						if($product_id==11)
						{
							$getweight = $this->getWeightbysizemaster($product_id,$post_volume,$post_width,$post_height);
							$cal_weight = $getweight * $quantity;
						}
						else if($data['product']=='61')
						{
							$cal_weight = $spoutweight * $quantity;
						}
						else if($data['product']=='37' || $data['product']=='38')
						{
						   $cal_weight = $oxy * $quantity;
						}
						else if($data['product']=='47' || $data['product']=='48' || $data['product']=='70')
						{
						    $cal_weight = $cup * $quantity;
						}
						else
						{
							$cal_weight='0';
						}
						$test['$cal_weight']=$cal_weight;
						//printr($test);
						if(decode($data['zipper'][0])==2)
						{
							//Total Weight without zipper
							$totalWeightWithoutZipper = $this->getCalculateWeightZipper(0,$quantity,2.5);
							$test['totalWeightWithoutZipper']=$totalWeightWithoutZipper;
							
							$totalForFormula = $totalWeightWithoutZipper;
							
						}
						$totalWeightWithZipper_spout = 0;
						if($data['make']==5)
						{	
							$test['make'] = $data['make'];
							$totalWeightWithZipper_spout = $this->getCalculateWeightZipper(0,$quantity,3.75);
							
							$test['$totalWeightWithZipper_spout'] = $totalWeightWithZipper_spout;
							
							$totalWeightWithoutZipper=(($totalWeightWithZipper_spout+($quantity*$spoutweight)));
							$test['totalWeightWithZipper_make']=$totalWeightWithZipper.'='.$totalWeightWithZipper_spout.'+'.$quantity.'*'.$spoutweight;
							$totalForFormula = $totalWeightWithoutZipper;
						}
						if($product_id==11 || $data['product']=='61' || $data['product']=='37' || $data['product']=='38' || $data['product']=='47' || $data['product']=='48' || $data['product']=='70')
						    $totalWeightWithoutZipper = $cal_weight;
						
						//printr($test);	
						$test['totalForFormula'] = $totalForFormula;			
						//courier charge
						$test['totalWeightWithZipper']=$totalWeightWithZipper;
						$courierBasePriceQuantityWise = array();
						$transportAndCoutierCharge = '';
						$actual_courier_per_kg_price = '';
						if($transportByAir)
						{								

							$courierChargeBaseZipper = $this->getCountryCourierCharge($data['country_id'],$countryCourierData['courier_id'],ceil($cal_weight));
							$test['$courierChargeBaseZipper']=$courierChargeBaseZipper;
							//echo "below is the courier charge data";
							$actual_courier_per_kg_price = $courierChargeBaseZipper;
							$courierBasePriceQuantityWise[$quantity] = array(
								'withZipepr'  => decode($data['zipper'][0])!=2?$courierChargeBaseZipper:0, 
								'noZipper'	=>  decode($data['zipper'][0])==2?$courierChargeBaseZipper:0, 
							);
								$test['courierChargeBaseZipper']=$courierChargeBaseZipper;
								if($fual_surcharge_base_price > 0){
									$fuleSurchargeForZipper = (($courierChargeBaseZipper * $fual_surcharge_base_price) / 100);
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
									$courierCrhgFuleZipper = ($courierChargeBaseZipper + $fuleSurchargeForZipper);
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
									$test['$serviceTaxZipper']=$serviceTaxZipper;
								}
									
								$test['courierCrhgFuleZipper']=$courierCrhgFuleZipper;
								if($handling_base_price > 0){
									$handlingCharge = $handling_base_price;
										$test['$handlingCharge']=$handlingCharge;
								}
								//courier charge with zipper	
								$courierChargeZipper = $this->numberFormate(($courierChargeBaseZipper + $fuleSurchargeForZipper + $serviceTaxZipper + $handlingCharge),"3");
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
							$test['$courierChargeZipper']=$courierChargeZipper;
							$test['ftotalPrice']=$ftotalPrice;
							$taxation='';
							$taxation_data='';
						
							$zipperData = array();
							$zipperValue = $zipperWiseData;
							$valve_text= 'no Valve';
							$courierCharge=$courierChargeWithoutZipper;		
							$spoutPrice=0;	
							$accessoriePrice=0;		
							$courierChargeInFormula = $courierChargeWithoutZipper;		
							$withValvePrice = 0;
							$priceWithTransport = 0;
							$bySea = array();
							$byAir = array();
							$byPickup = array();	
							if($zipperValue['zipperBasePrice'] > 0){
								$courierCharge=$courierChargeWithZipper;	
								$courierChargeInFormula = $courierChargeWithZipper;	
							}
							$test['$courierChargeInFormula']=$courierChargeInFormula;
							if(isset($spoutArray) && $spoutArray['price'] !=  0.000){
								$spoutPrice=$spoutArray['price'];
								if($transportByAir){
									  $courierChargeInFormula =  $courierChargeInFormula;
								}
								if($transportBySea){
									 $transportPerPouch = $transportPerPouch + 0.10;
								}
							}
							if(isset($accessorieArray) && $accessorieArray['price'] !=0.0000){
								$accessoriePrice=$accessorieArray['price'];
							}
							if(isset($data['valve']) && in_array('1',$data['valve'])){
								$valve_text= 'with Valve';
							}
							if($transportByAir){
								$withValvePrice = $this->numberFormate((($finalyPerPuchPrice + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity ) + $courierChargeInFormula);
								
								$byAir['totalPriceByAir'] = $withValvePrice;	
								$byAir['userGress'] = $userInfo['gres_air'];
								$byAir['userStockPrice'] = $userInfo['stock_air'];//printr($byAir);
							}
							if($transportBySea){
								$priceWithTransport =  $this->numberFormate((($finalyPerPuchPrice + $transportPerPouch  + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity ));
								$bySea['totalPriceBySea'] = $priceWithTransport;
								$bySea['userGress'] = $userInfo['gres_sea'];
								$bySea['userStockPrice'] = $userInfo['stock_sea'];
							}
							if($transportByPickup){
								$priceWithPickup =$this->numberFormate((($finalyPerPuchPrice   + $zipperValue['calculatePrice'] + $valveBasePrice + $spoutPrice + $accessoriePrice) * $quantity ));	
								$test['$priceWithPickup'] = $finalyPerPuchPrice.'+'.$zipperValue['calculatePrice'].'+'.$valveBasePrice.'+'.$spoutPrice.'+'.$accessoriePrice.'*'.$quantity;
								$byPickup['totalPriceByPickup'] = $priceWithPickup;
								$byPickup['userStockPrice'] = $userInfo['stock_factory'];
							}
							
							$zipperData[] = array(
									'zip_text'		=> $zipperValue['zipperText'],
									'make'		=> $data['make'],
									'valve_text'      => $valve_text,
									'spout_txt'	=> $spoutArray['spout_name'],
									'spout_price'		=> $spoutArray['price'],
									'accessorie_txt'	=> $accessorieArray['accessorie_name'],
									'accessorie_price'		=> $accessorieArray['price'],
									'transportPerPouch'	=> $transportPerPouch,									
									'courierCharge' => $courierChargeInFormula,
									'actual_courier_price'=>$actual_courier_per_kg_price,
									'calculateZipperPrice'	=> $zipperValue['calculatePrice'],	
									'valve_price'=>$valveBasePrice,					
									'BySea'	=>  $bySea,
									'ByAir'	=> $byAir,
									'ByPickup' => $byPickup,
								);
						//	printr($transportByAir);
							$test['$zipperData']=$zipperData;
							$valveTxt =$valve_text;
								$zipperText =$zipperValue['zipperText'];
								$zipperCalculatePrice=$zipperValue['calculatePrice'];
								$spoutTxt=$spoutArray['spout_name'];
								$spoutBasePrice=$spoutArray['price'];
								$accessorieTxt =$accessorieArray['accessorie_name'];
								$accessorieBasePrice=$accessorieArray['price'];
						//spout
						$spoutQuantityWiseData[] = array(
							'spout_txt'	=> $spoutArray['spout_name'],
							'price'		=> $spoutArray['price'],
						
						);
						$test['$spoutQuantityWiseData']=$spoutQuantityWiseData;
						//Accessorie
						$accessorieQuantityWiseData[] = array(
							'accessorie_txt'	=> $accessorieArray['accessorie_name'],
							'price'		=> $accessorieArray['price'],
						);
						$test['$accessorieQuantityWiseData']=$accessorieQuantityWiseData;
						//gress persontage price

						//store quantity wise information

						$quantityWiseData[$quantity] = array(
							'wastageBase'	=> $wastageBase,
							'addingWastage'  => $addingWastage,
							'wastage'		=> $wastage,
							'nativePricePerBag' => $pricePerBag,
							'totalWeightWithZipper' => ceil($totalWeightWithZipper),
							'totalWeightWithoutZipper' => ceil($totalWeightWithoutZipper),
							'total_cal_weight' => $cal_weight,
							'profit'		 => $profit,
							'profit_type' => $data['profit'][0],
							'pricePerBag'   => $pricePerBag,
							'wastageBasePrice' => $wastageBase,
							'wastageAddingPint' => $addingWastage,
							'zipperData'	=> $zipperData,
							'spoutData'     => $spoutQuantityWiseData,
							'accessorieData'	=> $accessorieQuantityWiseData,
							'ink_price'=>$inkPrice,
							'ink_solvent_price'=>$inkSolventPrice,
							'adhesive_price'=>$adhesivePrice,
							'cpp_adhesive'=>$checkCppMaterial,
							'adhesive_solvent_price'=>$adhesiveSolventPrice,
							'totalWeightWithZipper_spout'=>ceil($totalWeightWithZipper_spout)
						);
			    }
			    $test['$quantityWiseData']=$quantityWiseData;
			    //printr($test);	
				$userCountry = $this->getUserCountry($user_type_id,$user_id);
				$test['$userCountry']=$userCountry;
				if($user_type_id==1){
					$userCurrency = $this->getCurrencyInfo($user_id);
					$userCurrency['tool_rate']='';
				}else{
					$userCurrency =  $this->getUserWiseCurrency($user_type_id,$user_id);
				}
				$test['$userCurrency']=$userCurrency;
				if($userCountry){
					$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
				}else{
					$countryCode='IN';
				}
				$newQuotaionNumber = $this->generateQuotationNumber($multi_product_quotation_id);
				$quotation_number = 'TEM'.$newQuotaionNumber;
				$printingEffectName = getName('printing_effect','printing_effect_id',$data['printing_effect'],'effect_name');
				$productName = getName('product','product_id',$data['product'],'product_name');
				$currency = 'INR';
				$currencyPrice = '1';
				if($user_type_id!=1){
					if($userCurrency['currency_code'] && $userCurrency['product_rate']){
						$currency = $userCurrency['currency_code'];
						$currencyPrice = $userCurrency['product_rate'];
					}
				}else{
					if( $userCurrency['cylinder_rate']){
						$currencyPrice = $userCurrency['cylinder_rate'];
					}
				}
				if(isset($data['discount']))
					$data['discount']=$data['discount'];
				else
					$data['discount'] = 0.000;
					
				if($type=='Q')
				{ 
					
					$sql =  "UPDATE  ".DB_PREFIX."multi_product_template_id SET multi_quotation_number = '".$quotation_number."' WHERE multi_product_quotation_id = '".$multi_product_quotation_id."'";
					$this->query($sql);
					$test['$sql']=$sql;			
					$incrementval='';
					$decrementval='';
					if($data['incdec']==1)
					{
						$incrementval = $data['incdecval'];
					}
					else
					{
						$decrementval = $data['incdecval'];
					}
					$sql =  "INSERT INTO ".DB_PREFIX."multi_product_template SET product_id = '".(int)$data['product']."', product_name = '".$productName."', printing_option = '".$printing_option."', printing_effect_id = '".(int)$data['printing_effect']."', printing_effect = '".$printingEffectName."', height = '".(float)$post_height."', width = '".(float)$post_width."', gusset = '".(float)$gusset."', volume = '".$post_volume."', layer = '".(int)$totalLayer."',ink_sel='".$data['ink_sel'][0]."',ink_mul_by='".$data['stk_adh_mul_by']."',adh_mul_by='".$data['stk_adh_mul_by']."', ink_price = '".(float)$inkPrice."', ink_solvent_price = '".(float)$inkSolventPrice."', printing_effect_price = '".(float)$printingEffectPrice."', adhesive_price = '".(float)$adhesivePrice."', cpp_adhesive = '".(int)$checkCppMaterial."', adhesive_solvent_price = '".(float)$adhesiveSolventPrice."', native_price = '".(float)$totalPrice."',packing_price = '".(float)$originalperpouchpackingprice."', spout_additional_packing_price='".$spout_additional_packing_price."',valve_price = '".$valveBasePrice."', gress_percentage = '".$userGress."',gress_air = '".$userGressAir."',gress_sea = '".$userGressSea."', cylinder_price = '0', tool_price = '0', use_device = '".getDevice()."', status = '0', date_added = NOW(), added_by_country_id = '".$userCountry['country_id']."', currency = '".$currency."', currency_price = '".$currencyPrice."', customer_name = '".addslashes($data['customer'])."', customer_email = '".$customer_email."', customer_gress_percentage = '".$customer_gress."', 
shipment_country_id = '".$data['country_id']."', added_by_user_id = '".(int)$user_id."', added_by_user_type_id = '".(int)$user_type_id."',increment='".$incrementval."',decrement='".$decrementval."',multi_product_quotation_id = '".$multi_product_quotation_id."'";
								$this->query($sql);
								$productQuatiationId = $this->getLastId();
					            $test['$sql']=$sql;	
								if(isset($productQuatiationId) && (int)$productQuatiationId > 0 ){
									//quotation currency
									if($customer_email && decode($data['sel_currency']) > 0 ){
										$selCurrecy = $this->getCurrencyInfoOld(decode($data['sel_currency']));
										if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
											$selCurrencyRate = $data['sel_currency_rate'];
										}else{
											$selCurrencyRate = $selCurrecy['price'];
										}
										$this->query("INSERT INTO ".DB_PREFIX."multi_product_template_currency SET product_quotation_id = '".$productQuatiationId."', currency_id = '".$selCurrecy['currency_id']."', currency_code = '".$selCurrecy['currency_code']."',currency_rate = '".$selCurrencyRate."', currency_base_rate = '".$selCurrecy['price']."', date_added = NOW() ");
									}
									//INSERT QUOTATION QUANTITY TABLE 
									if(isset($quantityWiseData) && !empty($quantityWiseData)){
										foreach($quantityWiseData as $quantity=>$quantityValue){
											$this->query("INSERT INTO ".DB_PREFIX."multi_product_template_quantity SET product_quotation_id = '".$productQuatiationId."', quantity = '".$quantity."', wastage_adding = '".$quantityValue['addingWastage']."', wastage_base_price = '".$quantityValue['wastageBase']."', wastage = '".$quantityValue['wastage']."', native_price_per_bag = '".$quantityValue['nativePricePerBag']."', profit = '".$quantityValue['profit']."', profit_type='".$quantityValue['profit_type']."',total_weight_without_zipper = '".$quantityValue['totalWeightWithoutZipper']."', total_weight_with_zipper = '".$quantityValue['totalWeightWithZipper']."',total_calculated_weight = '".$quantityValue['total_cal_weight']."',totalWeightWithZipper_spout = '".$quantityValue['totalWeightWithZipper_spout']."',date_added = NOW(),discount='".$data['discount']."'");
											$productQuatiationQuantityId = $this->getLastId();
											
											//zipperData
											if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
												foreach($quantityValue['zipperData'] as $zipData){
													$pricesql = "INSERT INTO ".DB_PREFIX."multi_product_template_price SET product_quotation_id = '".$productQuatiationId."', product_quotation_quantity_id = '".$productQuatiationQuantityId."', zipper_txt = '".$zipData['zip_text']."', valve_txt = '".$zipData['valve_text']."', date_added = NOW(), zipper_price = '".$zipData['calculateZipperPrice']."', spout_txt = '".$zipData['spout_txt']."', spout_base_price = '".$zipData['spout_price']."', accessorie_txt = '".$zipData['accessorie_txt']."', make_pouch = '".(int)$data['make']."',accessorie_base_price = '".$zipData['accessorie_price']."',valve_price = '".$zipData['valve_price']."' , ";
													
														$customerGressPrice = 0; 
														$gressPrice = 0;
														$totalPricWithExcies =0;
														$totalPriceWithTax = 0;
														$tax_type='';
														$tax_percentage=0;
														$totalPriceForTax = 0;
														$excies = 0;
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
														$test['userGress By pickup']=$zipData['ByPickup']['userGress'];
														 if(isset($zipData['ByPickup']['userGress']) && $zipData['ByPickup']['userGress'] > 0){
															$tot_price=$zipData['ByPickup']['totalPriceByPickup'];
															$gressPrice = $this->numberFormate((($tot_price * $zipData['ByPickup']['userGress']) / 100),"3");
															$test['gressPrice']=$gressPrice;
															$test['userGress']=$zipData['ByPickup']['userGress'];
															$etst['tot_price']=$tot_price;
														}
														 if(isset($zipData['ByAir']['userGress']) && $zipData['ByAir']['userGress'] > 0){
															$tot_price=$zipData['ByAir']['totalPriceByAir'];
															$gressPrice = $this->numberFormate((($tot_price * $zipData['ByAir']['userGress']) / 100),"3");
														}
														 if(isset($zipData['BySea']['userGress']) && $zipData['BySea']['userGress'] > 0){
															$tot_price=$zipData['BySea']['totalPriceBySea'];
															$gressPrice = $this->numberFormate((($tot_price * $zipData['BySea']['userGress']) / 100),"3");
														}
														
														if(isset($taxation_data) && !empty($taxation_data))
														{
															$totalPriceForTax = $tot_price+$gressPrice+$customerGressPrice;
															if($data['discount'])
															{
															$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
															}
															$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
															$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
															$excies =$taxation_data['excies'];
															$tax_type=$taxation;			
															$tax_percentage=$taxation_data[$taxation];																				
														}	
														 if(isset($zipData['ByAir']) && !empty($zipData['ByAir']))
														 {
															$courierBasePriceWithZipper = 0;
															$courierBasePriceNoZipper = 0;
															if(isset($courierBasePriceQuantityWise[$quantity])){
																$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
																$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
															}
															$this->query(" $pricesql transport_type = 'air', courier_base_price_withzipper = '".$courierBasePriceWithZipper."', courier_base_price_nozipper = '".$courierBasePriceNoZipper."', courier_charge = '".$zipData['courierCharge']."',actual_courier_price = '".$zipData['actual_courier_price']."', transport_price = '0', total_price = '".$zipData['ByAir']['totalPriceByAir']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."',color='".json_encode($data['color'])."'");
														 }
														 if(isset($zipData['BySea']) && !empty($zipData['BySea']))
														{
															$this->query(" $pricesql transport_type = 'sea',  courier_charge = '0', transport_price = '".$transportPerPouch."', total_price = '".$zipData['BySea']['totalPriceBySea']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."',color='".json_encode($data['color'])."'");
														}													 
														if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup']))
														{
														    $this->query(" $pricesql transport_type = 'pickup', courier_charge = '0', transport_price = '0', total_price = '".$zipData['ByPickup']['totalPriceByPickup']."', total_price_with_excies = '".$totalPricWithExcies."', total_price_with_tax = '".$totalPriceWithTax."', tax_type = '".$tax_type."', tax_percentage = '".$tax_percentage."', excies = '".$excies."', gress_price = '".$gressPrice."', customer_gress_price = '".$customerGressPrice."',color='".json_encode($data['color'])."'");
													    }									
												}
											}
										}
									}
									//printr($test);
									//BASE PRICE
									$inkDefaultPrice = 0;
									$inkSolventDefaultPrice = 0;
									$printingEffectDefaultPrice =0;
									$adhesiveDefaultPrice = 0;
									$adhesiveSolventDefaultPrice = 0;
									$cppAdhesiveDefaultPrice =0;
									$transportWidthDefaultPrice = $this->getDefaultTransportWidthPrice($post_width,$gusset);
									$transportHeightDefaultPrice = $this->getDefaulttransportHeightPrice($post_height);
									//$cylinderVendorDefaultPrice = $this->getCylinderVendorPrice($data['country_id']);
									//inert base price at a time product quotaion add than taht time real price. use for history
									
									$this->query("INSERT INTO ".DB_PREFIX."multi_product_template_base_price SET product_quotation_id = '".(int)$productQuatiationId."', ink_base_price = '".$inkDefaultPrice."', ink_solvent_base_price = '".$inkSolventDefaultPrice."', printing_effect_base_price = '".$printingEffectDefaultPrice."', adhesive_base_price = '".$adhesiveDefaultPrice."', cpp_adhesive_base_price = '".$cppAdhesiveDefaultPrice."', adhesive_solvent_base_price = '".$adhesiveSolventDefaultPrice."', packing_price = '".$originalperpouchpackingprice."', spout_packing_price = '0.10', spout_courier_price = '1.4', transport_width_base_price = '".$transportWidthDefaultPrice."', transport_height_base_price = '".$transportHeightDefaultPrice."', cylinder_base_price = '0', cylinder_vendor_base_price = '0', cylinder_currency_base_price = '0', fuel_surcharge = '".$fual_surcharge_base_price."', service_tax = '".$service_tax_base_price."', handling_charge = '".$handling_base_price."', date_added = NOW()");
									
									//INSERT DATA FOR LAYER WISE
									if(isset($setQueryData) && !empty($setQueryData)){
										foreach($setQueryData as $key=>$setquery){
										
											$setSql = "INSERT INTO ".DB_PREFIX."multi_product_template_layer SET product_quotation_id = '".(int)$productQuatiationId."', ".$setquery;
											$this->query($setSql);
											
											
										}
									}
									//printr($multi_product_quotation_id.'kooooo');
									return $multi_product_quotation_id;
									//printr($multi_product_quotation_id.'kooooo');
								}
					
					    
				}
				//printr($quantityWiseData);
				if($type=='T')
				{//printr($type);
		                //printr($currencyPrice);	
					$incrementval='';
					$decrementval='';
					if($data['incdec']==1)
					{
						$incrementval = $data['incdecval'];
					}
					else
					{
						$decrementval = $data['incdecval'];
					}
					
									//quotation currency
									if($customer_email && decode($data['sel_currency']) > 0 ){
										$selCurrecy = $this->getCurrencyInfoOld(decode($data['sel_currency']));
										if(isset($data['sel_currency_rate']) && (float)$data['sel_currency_rate'] > 0){
											$selCurrencyRate = $data['sel_currency_rate'];
										}else{
											$selCurrencyRate = $selCurrecy['price'];
										}
									
									}
								
									if(isset($quantityWiseData) && !empty($quantityWiseData)){
										foreach($quantityWiseData as $quantity=>$quantityValue){//printr($data);
											
											if(isset($quantityValue['zipperData']) && !empty($quantityValue['zipperData'])){
											$profit=$quantityValue['profit'];
												foreach($quantityValue['zipperData'] as $zipData){//printr($zipData);
													$valve=$zipData['valve_text'];
													$zipper=$zipData['zip_text'];
													$spout=$zipData['spout_txt'];
													$make_pouch=$zipData['make'];
													$accessorie=$zipData['accessorie_txt'];
													$disc_arr[$quantity]['zipPrice']=$zipData['calculateZipperPrice'];
													$disc_arr[$quantity]['spoutPrice']=$zipData['spout_price'];
													$disc_arr[$quantity]['accessoriePrice']=$zipData['accessorie_price'];
													$disc_arr[$quantity]['valvePrice']=$zipData['valve_price'];
													$disc_arr[$quantity]['total_weight_without_zipper']=$quantityValue['totalWeightWithoutZipper'];
													$disc_arr[$quantity]['total_weight_with_zipper']=$quantityValue['totalWeightWithZipper'];
													$disc_arr[$quantity]['totalcalweight']=$quantityValue['total_cal_weight'];
													$disc_arr[$quantity]['wastageBase']=$quantityValue['wastageBase'];
													$disc_arr[$quantity]['courier_charge']=$zipData['courierCharge'];
													$disc_arr[$quantity]['actual_courier_price']=$zipData['actual_courier_price'];
													$disc_arr[$quantity]['packing_price']=$packingPerPouch;
													$disc_arr[$quantity]['spout_additional_packing_price']=$spout_additional_packing_price;
													$disc_arr[$quantity]['transportPerPouch']=$transportPerPouch;
													$disc_arr[$quantity]['ink_price']=$quantityValue['ink_price'];
													$disc_arr[$quantity]['ink_solvent_price']=$quantityValue['ink_solvent_price'];
													$disc_arr[$quantity]['adhesive_price']=$quantityValue['adhesive_price'];
													$disc_arr[$quantity]['cpp_adhesive']=$quantityValue['cpp_adhesive'];
													$disc_arr[$quantity]['adhesive_solvent_price']=$quantityValue['adhesive_solvent_price'];
													$disc_arr[$quantity]['totalWeightWithZipper_spout']=$quantityValue['totalWeightWithZipper_spout'];
													
														$customerGressPrice = 0; 
														//[kinjal] : edited on [22-8-2016]
														$gressPrice = $stockPrice= $user_stock_per = $real_tot_price = $tot_price_with_stock_price = 0;
														$totalPricWithExcies =0;
														$totalPriceWithTax = 0;
														$tax_type='';
														$tax_percentage=0;
														$totalPriceForTax = 0;
														$excies = 0;
														
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
															$gressPrice = $this->numberFormate((($tot_price * $zipData['ByPickup']['userGress']) / 100),"3");
														}
														if(isset($zipData['ByAir']['userGress']) && $zipData['ByAir']['userGress'] > 0){
															$gressPrice = $this->numberFormate((($tot_price * $zipData['ByAir']['userGress']) / 100),"3");
														}
														if(isset($zipData['BySea']['userGress']) && $zipData['BySea']['userGress'] > 0){
															$gressPrice = $this->numberFormate((($tot_price * $zipData['BySea']['userGress']) / 100),"3");
														}
														
														//[kinjal] : added for stock price by transportation wise [22-8-2016]
														if(isset($zipData['ByPickup']['userStockPrice']) && $zipData['ByPickup']['userStockPrice'] > 0){
															$stockPrice = $this->numberFormate((($tot_price * $zipData['ByPickup']['userStockPrice']) / 100),"3");
															$user_stock_per = $zipData['ByPickup']['userStockPrice'];
														}
														if(isset($zipData['ByAir']['userStockPrice']) && $zipData['ByAir']['userStockPrice'] > 0){
															$stockPrice = $this->numberFormate((($tot_price * $zipData['ByAir']['userStockPrice']) / 100),"3");
															$user_stock_per = $zipData['ByAir']['userStockPrice'];
														}
														if(isset($zipData['BySea']['userStockPrice']) && $zipData['BySea']['userStockPrice'] > 0){
															$stockPrice = $this->numberFormate((($tot_price * $zipData['BySea']['userStockPrice']) / 100),"3");
															$user_stock_per = $zipData['BySea']['userStockPrice'];
														}
														$disc_arr[$quantity]['user_stock_per']=$user_stock_per;
														
														if(decode($data['stockdelivery'][0]) == 'other_country_to_customer')
														{
															$real_tot_price = $tot_price;
															$tot_price = $tot_price + $stockPrice;
															$tot_price_with_stock_price = $tot_price;
														}
														$disc_arr[$quantity]['real_tot_price']=$real_tot_price;
														$disc_arr[$quantity]['tot_price_with_stock_price']=$tot_price_with_stock_price;
														$disc_arr[$quantity]['currencyPrice']=$currencyPrice;
														//end [kinjal]
														
														
														if(isset($taxation_data) && !empty($taxation_data))
														{
															$totalPriceForTax = $tot_price+$gressPrice+$customerGressPrice;
															if($data['discount'])
															{
															$totalPriceForTax=$totalPriceForTax*$data['discount']/100;
															}
															$totalPricWithExcies = $totalPriceForTax+($totalPriceForTax*$taxation_data['excies']/100);
															$totalPriceWithTax = $totalPricWithExcies+($totalPricWithExcies*$taxation_data[$taxation]/100);
															$excies =$taxation_data['excies'];
															$tax_type=$taxation;			
															$tax_percentage=$taxation_data[$taxation];																				
														}	
														
														 if(isset($zipData['ByAir']) && !empty($zipData['ByAir']))
														 {
															$courierBasePriceWithZipper = 0;
															$courierBasePriceNoZipper = 0;
															if(isset($courierBasePriceQuantityWise[$quantity])){
																$courierBasePriceWithZipper = $courierBasePriceQuantityWise[$quantity]['withZipepr'];
																$courierBasePriceNoZipper = $courierBasePriceQuantityWise[$quantity]['noZipper'];
															}
															$total_Price=($tot_price/decode($data['quantity'][0]))/$currencyPrice;
															//printr('Air'.$total_Price.'=('.$tot_price.'/'.decode($data['quantity'][0]).')/'.$currencyPrice.')');
														 }
														 if(isset($zipData['BySea']) && !empty($zipData['BySea']))
														{
															$total_Price=($tot_price/decode($data['quantity'][0]))/$currencyPrice;
														//printr('Sea'.$total_Price.'=('.$tot_price.'/'.decode($data['quantity'][0]).')/'.$currencyPrice.')');
														}													 
														if(isset($zipData['ByPickup']) && !empty($zipData['ByPickup']))
														{
															$total_Price=($tot_price/decode($data['quantity'][0]))/$currencyPrice;
															//printr('pickup'.$total_Price.'=('.$tot_price.'/'.decode($data['quantity'][0]).')/'.$currencyPrice.')');
														}
														
																					
												}
											}
										}
									}
																				
									$arr=array('width'=>$data['width'],
											'height'=>$data['height'],
											'gusset'=>$data['gusset'],
											'volume'=>$data['volume'],
											'qty'=>decode($data['quantity'][0]),
											'total_price'=>$total_Price,
											'template_id'=>$multi_product_quotation_id,
											'valve'=>$valve,
											'zipper'=>$zipper,
											'spout'=>$spout,
											'profit'=>$profit,
											'accessorie'=>$accessorie,
											'total_weight_with_zipper'=>$disc_arr[$quantity]['total_weight_with_zipper'],
											'totalcalweight'=>$disc_arr[$quantity]['totalcalweight'],
											'total_weight_without_zipper'=>$disc_arr[$quantity]['total_weight_without_zipper'],
											'totalWeightWithZipper_spout'=>$disc_arr[$quantity]['totalWeightWithZipper_spout'],
											'wastage'=>$disc_arr[$quantity]['wastageBase'],
											'ink_price'=>$disc_arr[$quantity]['ink_price'],
											'ink_solvent_price'=>$disc_arr[$quantity]['ink_solvent_price'],
											'adhesive_price'=>$disc_arr[$quantity]['adhesive_price'],
											'cpp_adhesive'=>$disc_arr[$quantity]['cpp_adhesive'],
											'adhesive_solvent_price'=>$disc_arr[$quantity]['adhesive_solvent_price'],
											'make_pouch'=>$make_pouch,
											'courier_charges'=>$disc_arr[$quantity]['courier_charge'],
											'actual_courier_price'=>$disc_arr[$quantity]['actual_courier_price'],
											'spout_price'=>$disc_arr[$quantity]['spoutPrice'],
											'accessories_price'=>$disc_arr[$quantity]['accessoriePrice'],
											'zipper_price'=>$disc_arr[$quantity]['zipPrice'],
											'valve_price'=>$disc_arr[$quantity]['valvePrice'],
											'packing_price'=>$disc_arr[$quantity]['packing_price'],
											'spout_additional_packing_price'=>$disc_arr[$quantity]['spout_additional_packing_price'],
											'transportPerPouch'=>$disc_arr[$quantity]['transportPerPouch'],
											'user_stock_per'=>$disc_arr[$quantity]['user_stock_per'],
											'real_tot_price'=>$disc_arr[$quantity]['real_tot_price'],
											'tot_price_with_stock_price'=>$disc_arr[$quantity]['tot_price_with_stock_price'],
											'currencyPrice'=>$disc_arr[$quantity]['currencyPrice'],
											'color'=>json_encode($data['color']));
											
					//printr($arr);//die;
						return $arr;
					}
				
			}	
	}
	public function getQuantity1(){
		$data = $this->query("SELECT template_quantity_id,quantity FROM " . DB_PREFIX ." template_quantity WHERE template_quantity_id IN (1,2,3,4,6,7) AND is_delete = '0'  ");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getdataexcel($data)
	{
	       foreach($data as $dat)
           {
         	 $result = $this->getQuotationQuantity($dat['product_quotation_id']);
        	 //printr($dat['product_quotation_id']);
        	 if($result!='')
        	    $quantityData[] =$result;
           }
	    
	    $html='';
		$html .="<style>.table2 { font-size: 100%; table-layout: fixed; width: 100%; font-size:  12px;}
					.table2 { border-collapse: separate; border-spacing: 2px;font-size:  12px; }
					th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left;font-size:  12px; }
					th, td { border-radius: 0.25em; border-style: solid;font-size:  12px; }
					th { background: #EEE; border-color: #BBB;font-size:  12px; }
					td { border-color: #DDD; font-size:  12px;}
					.table3 td{border-width: 0px; }
					.form-horizontal .form-group {margin-right: -15px; margin-left: -15px;}
					.form-group {margin-bottom: 15px;}.panel {border-color: #e3e8ed;}
			</style>
			<table border='1'>
						<tr><td colspan='4'>Quotation Number : ".$data[0]['multi_quotation_number']."</td></tr>
						<tr><td colspan='4'>Shipment Country  : ".$data[0]['country_name']."</td></tr>
						<tr><td colspan='4'>Product Name : ".$data[0]['product_name']." </td></tr>
						<tr><td colspan='4'>Printing Option : ".$data[0]['printing_option']." </td></tr>
						<tr>
							<td>Quntity</td>
							<td>Dimension</td>";
							if($dat['quotation_type'] != 1)
							{
						        $html .="<td>Option(Printing Effect )</td>";
							}
							$html .="<td>Price / pouch</td>
						</tr>";
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
						foreach($new_data as $k=>$qty_data)
					    {
					       $i=1;$k=1;
                          foreach($qty_data as $skey=>$sdata)
                          {
    					       foreach($sdata as $soption)
    					       {  $price = $this->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");
        					       $html.="<tr>
        							<td>".$skey."</td>
        							<td>".(int)$soption['width']."X".(int)$soption['height']."X".$soption['gusset'];
        							    if($data[0]['product_name']!=10)
        							    {
        							        if($soption['volume']>0) 
        							            $html.=" (".$soption['volume'].")";
        							    }
    									else 
    									   $html.=" (Custom)"." (".$soption['make'].")";
    									   
        							$html.="</td>
        							<td>".ucwords($soption['text'])." (".$soption['printing_effect'].")</td>
        							<td>".$dat['currency']." ".$price."</td>
            					</tr>";
    					       }
                          }
					    }
		return $html;			
	}
}
?>