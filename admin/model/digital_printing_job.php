 <?php
//==>sonu
class digital_printing extends dbclass{
	public function getOperator()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM   user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type='12'";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getOperator_Printing()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM   user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type='3'";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getCountries(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE status='1' AND is_delete = '0' ORDER BY country_name ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}

	
	public function getINK()
	{
		$sql = "SELECT * FROM `product_category` as pc ,product_item_info as pi  WHERE pc.product_category_id = '17' AND pc.product_category_id = pi.product_category_id AND pc.is_delete ='0'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function addDigitalJob($file,$data)
	{
	//	printr($file['name']);
//	printr($data);die;
	$f_name =isset($file['name'])?$file['name']:'';
		/*if(isset($file['name'])&& !empty($file['name']))
		{
			$f_name =$file['name'] ;
		}*/
		$this->query("INSERT INTO " . DB_PREFIX . "digital_printing SET  digital_printing_no = '" .$data['lamination_no']. "',dieline_name = '".$f_name."',digital_printing_date = '" .$data['lamination_date']. "',job_name = '" .$data['job_name_text']. "',job_name_id = '".$data['job_name']."',country_id = '".$data['shipping_country_id']."',number_of_color_front ='".$data['color_front']."',number_of_color_back ='".$data['color_back']."',size_front ='".json_encode($data['color_size_front'])."',size_back = '".json_encode($data['color_size_back'])."',final_qty_recive ='".$data['qty_recieve']."', final_qty_printed ='".$data['qty_printed']."',final_qty_return ='".$data['qty_return']."',f_wastage_per = '".$data['total_wastage']."',style_bag ='".$data['style_bag']."',size_bag ='".$data['size_bag']."',start_time = '" .$data['start_time']. "',end_time = '" .$data['end_time']. "',screen_making_operator = '" .$data['screen_operator']. "',printing_oparator = '" .$data['printing_operator']. "',shift = '" .$data['shift']. "' ,job_due_date ='".$data['job_due_date']."' ,remark='".$data['remark']."', date_added = NOW(),date_modify = NOW(),is_delete=0,user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'");
			$digital_printing_id = $this->getLastId();
			foreach($data['ink_use_front'] as $ink_front)
			{
				$this->query("INSERT INTO  digital_printing_ink_front SET digital_printing_id ='".$digital_printing_id."',	ink_id ='".$ink_front['ink_id']."',qty_ink_recive = '" .$ink_front['qty_ink_recive']. "',qty_ink_used = '".$ink_front['qty_ink_used']."',	qty_ink_return ='".$ink_front['qty_ink_return']."',ink_wastage = '" .$ink_front['total_wastage_front']. "',date_modify = NOW(),is_delete=0");
			}
			foreach($data['ink_use_back'] as $ink_back)
			{
				$this->query("INSERT INTO  digital_printing_ink_back SET digital_printing_id ='".$digital_printing_id."',	ink_id ='".$ink_back['ink_id']."',qty_ink_recive = '" .$ink_back['qty_ink_recive']. "',qty_ink_used = '".$ink_back['qty_ink_used']."',	qty_ink_return ='".$ink_back['qty_ink_return']."',ink_wastage = '" .$ink_back['total_wastage_back']. "',date_modify = NOW(),is_delete=0");
			}
			
		
	   	
			
	}
	public function getlatestjobid()
	{
		$sql = "SELECT digital_printing_id FROM `" . DB_PREFIX . "digital_printing` WHERE is_delete = 0 ORDER BY  digital_printing_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['digital_printing_id'];
		}else{
			return false;
		}
	}
	public function getTotalJob($filter_data=array(),$job_date='')
	{
		$con='';
		if($job_date!='')
		{
			$con="AND digital_printing_date ='".$job_date."'";
		}
		
		$sql = "SELECT COUNT(*) as total FROM  digital_printing as pb, employee as om WHERE pb.is_delete = 0 AND pb.screen_making_operator=om.employee_id  $con ";
	//	printr($filter_data);
		if(!empty($filter_data)){
			if(!empty($filter_data['job_name'])){
				$sql .= " AND pb.job_name LIKE '%".$filter_data['job_name']."%' ";		
			}
			if(!empty($filter_data['operator_id'])){
				$sql .= " AND pb.screen_making_operator = '".$filter_data['operator_id']."' "; 	
			}
			if(!empty($filter_data['job_date'])){
				$sql .= " AND pb.digital_printing_date = '".$filter_data['job_date']."' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY digital_printing_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		return $data->row['total'];
	}
	public function getJob($option,$filter_data=array(),$job_date='')
	{
		$con='';
		if($job_date!='')
		{
			$con="AND pb.digital_printing_date='".$job_date."'";
		}
		$sql = "SELECT pb.*,CONCAT(om.first_name ,' ', om.last_name) as operator_name FROM  digital_printing as pb, employee as om WHERE pb.is_delete = 0 AND pb.screen_making_operator=om.employee_id  $con ";
		
		//printr($sql);die;
		if(!empty($filter_data)){
			if(!empty($filter_data['job_name'])){
				$sql .= " AND pb.job_name LIKE '%".$filter_data['job_name']."%' ";		
			}
			if(!empty($filter_data['operator_id'])){
				$sql .= " AND pb.digital_printing_date = '".$filter_data['operator_id']."' "; 	
			}
			
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY digital_printing_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
		}
		//echo $sql;die;
		$data = $this->query($sql);
		
		return $data->rows;
		
		
	}
	public function getJobDetail($digital_printing_id,$cond='')
	{
		
		//$sql = "SELECT pb.*,om.first_name as operator_name,mm.machine_name,i.roll_no,jm.job_name as job_num_id FROM  lamination as pb, employee as om,machine_master as mm,product_inward as i,job_master as jm WHERE pb.is_delete = 0 AND pb.lamination_id = '".$lamination_id."' AND pb.operator_id=om.employee_id AND pb.machine_id = mm.machine_id AND pb.roll_no_id=i.product_inward_id AND pb.job_name=jm.job_id";
		$sql = "SELECT l.*,om.first_name as operator_name FROM  digital_printing as l, digital_printing_ink_front as dpf,digital_printing_ink_back  as dpb, employee as       	om,product_inward as i WHERE l.is_delete = 0 AND l.digital_printing_id = '".$digital_printing_id."' AND  l.screen_making_operator=om.employee_id AND l.digital_printing_id=dpb.digital_printing_id AND l.digital_printing_id =dpf.digital_printing_id GROUP BY l.digital_printing_id";
		//echo $sql;
		$data = $this->query($sql);		
		//printr($data);die;	
			
		$sql2 = "SELECT * FROM   digital_printing_ink_front as df,product_item_info as pf  WHERE  df.digital_printing_id = '".$digital_printing_id."' AND df.is_delete = '0' AND df.ink_id =pf.product_item_id";	
		$digital_printing_ink_front = $this->query($sql2);
//printr($digital_printing_ink_front);
		
		$ink_use_front=array();
		foreach($digital_printing_ink_front->rows as $ink_front){
			//printr($ink_front);die;
		$ink_use_front[] = array(
                    'digital_pri_ink_id' =>$ink_front['digital_pri_ink_id'],
                    'digital_printing_id' =>$ink_front['digital_printing_id'],
					'ink_id'=>$ink_front['ink_id'],
					'ink_name'=>$ink_front['product_code'],
                    'qty_ink_recive' => $ink_front['qty_ink_recive'],
                    'qty_ink_used' => $ink_front['qty_ink_used'],
                    'qty_ink_return' => $ink_front['qty_ink_return'],
                    'ink_wastage' =>$ink_front['ink_wastage'],
                    'is_delete' => $ink_front['is_delete'],
                    'date_modify' => $ink_front['date_modify'],
                   
                );
		}
		$sql2 = "SELECT * FROM    digital_printing_ink_back   WHERE  digital_printing_id = '".$digital_printing_id."' AND is_delete = '0'";	
		$digital_printing_ink_back  = $this->query($sql2);
		//	printr($digital_printing_ink_back);die;
		$ink_use_back=array();
		foreach($digital_printing_ink_back->rows as $ink_back){
		
		$ink_use_back[] = array(
                    'digital_pri_ink_back_id' =>$ink_back['digital_pri_ink_back_id'],
                    'digital_printing_id' =>$ink_back['digital_printing_id'],
					'ink_id'=>$ink_front['ink_id'],
					'ink_name'=>$ink_front['product_code'],
                    'qty_ink_recive' => $ink_front['qty_ink_recive'],
                    'qty_ink_used' => $ink_front['qty_ink_used'],
                    'qty_ink_return' => $ink_front['qty_ink_return'],
                    'ink_wastage' =>$ink_front['ink_wastage'],
                    'is_delete' => $ink_front['is_delete'],
                    'date_modify' => $ink_front['date_modify'],
                );
		}
		
		$digital_printing =array();
            $digital_printing = array(
                'digital_printing_id' => $data->row['digital_printing_id'],
				'digital_printing_no' => $data->row['digital_printing_no'],
                'digital_printing_date' => $data->row['digital_printing_date'],
				'dieline_name'=>$data->row['dieline_name'],
                'shift' => $data->row['shift'],
                'job_name' => $data->row['job_name'],
				'job_name_id' =>$data->row['job_name_id'],
				
                'country_id' => $data->row['country_id'],
                'number_of_color_front' => $data->row['number_of_color_front'],
                'number_of_color_back' => $data->row['number_of_color_back'],
				'size_front' => $data->row['size_front'],				
				'size_back' => $data->row['size_back'],
				'style_bag' => $data->row['style_bag'],
				'size_bag' => $data->row['size_bag'],
				'final_qty_recive' => $data->row['final_qty_recive'],
				'final_qty_printed' => $data->row['final_qty_printed'],
				'final_qty_return' => $data->row['final_qty_return'],
				'screen_making_operator' => $data->row['screen_making_operator'],
				'printing_oparator' => $data->row['printing_oparator'],
				'start_time' => $data->row['start_time'],
				'end_time' => $data->row['end_time'],
				'job_due_date' => $data->row['job_due_date'],
				'remark' => $data->row['remark'],
				'f_wastage_per' => $data->row['f_wastage_per'],
                'shift' => $data->row['shift'],
                'user_id' => $data->row['user_id'],
                'user_type_id' => $data->row['user_type_id'],              
                'date_added' => $data->row['date_added'],
                'date_modify' => $data->row['date_modify'],
                'is_delete' => $data->row['is_delete'],
                'screen_operator_name' => $data->row['operator_name'],                              
                'ink_use_front' => $ink_use_front,
				'ink_use_back'=>$ink_use_back,
				
               
            );

		//printr($digital_printing);
		return $digital_printing;
	
	}
	public function updatedigital_printing($file,$digital_printing_id,$data)
	{
	// printr($data);die;
		
		$this->query(" UPDATE " . DB_PREFIX . "digital_printing SET  digital_printing_no = '" .$data['lamination_no']. "',dieline_name = '".$file."',digital_printing_date = '" .$data['lamination_date']. "',job_name = '" .$data['job_name_text']. "',country_id = '".$data['shipping_country_id']."',number_of_color_front ='".$data['color_front']."',number_of_color_back ='".$data['color_back']."',size_front ='".json_encode($data['color_size_front'])."',size_back = '".json_encode($data['color_size_back'])."',final_qty_recive ='".$data['qty_recieve']."', final_qty_printed ='".$data['qty_printed']."',final_qty_return ='".$data['qty_return']."',f_wastage_per = '".$data['total_wastage']."',style_bag ='".$data['style_bag']."',size_bag ='".$data['size_bag']."',start_time = '" .$data['start_time']. "',end_time = '" .$data['end_time']. "',screen_making_operator = '" .$data['screen_operator']. "',printing_oparator = '" .$data['printing_operator']. "',shift = '" .$data['shift']. "' ,job_due_date ='".$data['job_due_date']."' ,remark='".$data['remark']."', date_added = NOW(),date_modify = NOW(),is_delete=0,user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE digital_printing_id = '" .(int)$digital_printing_id. "'");
	//die;
		foreach($data['ink_use_front'] as $ink_use_front)
			{	
	//	printr($ink_use_front);die;
				if(isset($ink_use_front['digital_pri_ink_id']) && !empty($ink_use_front['digital_pri_ink_id'])){
					
					
					
					$this->query("UPDATE  digital_printing_ink_front SET digital_printing_id ='".$digital_printing_id."',	ink_id ='".$ink_use_front['ink_id']."',qty_ink_recive = '" .$ink_use_front['qty_ink_recive']. "',qty_ink_used = '".$ink_use_front['qty_ink_used']."',	qty_ink_return ='".$ink_use_front['qty_ink_return']."',ink_wastage = '" .$ink_use_front['total_wastage_front']. "',date_modify = NOW(),is_delete=0 WHERE digital_pri_ink_id = '".$ink_use_front['digital_pri_ink_id']."'");
				}else{
				//echo "hii";	
				$this->query("INSERT INTO  digital_printing_ink_front SET digital_printing_id ='".$digital_printing_id."',	ink_id ='".$ink_use_front['ink_id']."',qty_ink_recive = '" .$ink_use_front['qty_ink_recive']. "',qty_ink_used = '".$ink_use_front['qty_ink_used']."',	qty_ink_return ='".$ink_use_front['qty_ink_return']."',ink_wastage = '" .$ink_use_front['total_wastage_front']. "',date_modify = NOW(),is_delete=0");
				}
			}
			foreach($data['ink_use_back'] as $ink_use_back)
			{
					
				if(isset($ink_use_back['digital_pri_ink_back_id']) && !empty($ink_use_back['digital_pri_ink_back_id'])){
					echo"update";
					
					$this->query(" UPDATE digital_printing_ink_back SET digital_printing_id ='".$digital_printing_id."',	ink_id ='".$ink_use_back['ink_id']."',qty_ink_recive = '" .$ink_use_back['qty_ink_recive']. "',qty_ink_used = '".$ink_use_back['qty_ink_used']."',	qty_ink_return ='".$ink_use_back['qty_ink_return']."',ink_wastage = '" .$ink_use_back['total_wastage_back']. "',date_modify = NOW(),is_delete=0  WHERE digital_pri_ink_back_id = '".$ink_use_back['digital_pri_ink_back_id']."' ");
				}else{
					echo "insert";
				$this->query("INSERT INTO  digital_printing_ink_back SET digital_printing_id ='".$digital_printing_id."',	ink_id ='".$ink_use_back['ink_id']."',qty_ink_recive = '" .$ink_use_back['qty_ink_recive']. "',qty_ink_used = '".$ink_use_back['qty_ink_used']."',	qty_ink_return ='".$ink_use_back['qty_ink_return']."',ink_wastage = '" .$ink_use_back['total_wastage_back']. "',date_modify = NOW(),is_delete=0");
				}
			}
	}
	
	public function updateStatus($status,$data)
	{
		
		if($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "digital_printing SET is_delete = '1', date_modify = NOW() WHERE digital_printing_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getDateWiseJob($filter_data)
	{
		$sql = "SELECT digital_printing_date FROM digital_printing WHERE is_delete = 0 ";
			
			if(!empty($filter_data['job_date'])){
					$sql .= " AND digital_printing_date = '".$filter_data['job_date']."' "; 	
				}
				$sql .= "GROUP BY digital_printing_date";
				
				
			$data = $this->query($sql);
		//printr($data);
			return $data->rows;
		
	}
	
	public function viewdigital_printing_report($digital_printing_id)
	{
		
		$data = $this->getJobDetail($digital_printing_id);
		//	printr($data);
		$html = '';
		$printing_operator_name = $this->printing_operator_name($data['printing_oparator']);	
		$country_name = $this->getcountryname($data['country_id']);		
	//printr($printing_operator_name);die;
	
		
			$html .= "<div class='form-group' style='font-size:28'>
						<div class='table-responsive'>";
						$html .= "<center  ><span  ><b id='lamination' >DIGITAL PRINTING JOB REPORT</b></span></center><br>";
			
			
		
						$html .= '<table class="table b-t text-small table-hover"  id="lamination_report" >
								<thead>
								<tr id="first" style="border:groove;">
										
										  
										  <th>Digital Printing No</th>
										  <th>Digital Printing Date</th>
										  <th>Screen Operator Name  </th>
										  <th>Printing Operator Name </th>
										  <th>Shift</th>										
										  <th>Job Due Date</th>	
										  <th>Country Name</th>
										  <th>Style Of Bag </th>
										  <th>Size Of Bag </th>
										  <th>Qty Recive</th>
										  <th>Qty Printed</th>
										  <th>Qty Return</th>
										  <th>Wastage Qty</th>
										  <th colspan="2"></th>
										  
									</tr>
									</thead>
									<tbody style="border:groove;" >
									<tr >
										
										  
										 <td>'.ucwords($data['digital_printing_no']).'</td>
										 <td>'.dateFormat(4,$data['digital_printing_date']).'</td>
										 <td>'. ucwords($data['screen_operator_name']).' </td>
										 <td>'. ucwords($printing_operator_name).' </td>
										 <td>'.ucwords($data['shift']).'</th>										 
										 <th>'.dateFormat(4,$data['job_due_date']).'</td>	
										 <td>'.$country_name.'</td>
										 <td>'. ucwords($data['style_bag']).' </td>
										 <td>'. ucwords($data['size_bag']).' </td>
										 <td>'.ucwords($data['final_qty_recive']).'</td>
										 <td>'.ucwords($data['final_qty_printed']).'</td>
										 <td>'.ucwords($data['final_qty_return']).'</td>
										 <td>'.ucwords($data['f_wastage_per']).'</td>
										 <td colspan="2"></td>
										
									</tr>
									</tbody>
									<tbody>
									<tr><td colspan="15"> </td></tr>
									<tr id="first" style="border:groove;">
										
										  
										 <td  style="border:groove;"><b>Front Screen Size<b></td>';
										  if(isset($data['size_front'])&& !empty($data['size_front']) ){
										$p_back = json_decode($data['size_front']);
										foreach($p_back as $size_front){
										
										
										$html.=' <td style="border:groove;">'.$size_front.'</td>
											';
										}}
										
										
								$html.='<td colspan="12"></td></tr>
								<tr  id="first" style="border:groove;">
										
										  
										 <td style="border:groove;"><b>Back Screen Size<b></td>';
										  if(isset($data['size_back'])&& !empty($data['size_back']) ){
										$p_back = json_decode($data['size_back']);
										foreach($p_back as $size_back){
										
										
										$html.=' <td style="border:groove;">'.$size_back.'</td>
										';
										}
										  }
										
								$html.='<td colspan="12"></td></tr>
									<tr><td colspan="15"> </td></tr>
								</tbody>
									
									';
									$html .= '
										<thead >		
										
										<tr id="first" style="border:groove;">
									  	
									     <th colspan="11" >INK Use In Front :- </th>
										  <th colspan="3" ><th>
										 </tr>
										 <tr style="border:groove;">
										 <th colspan="1">Ink Name </th>
										 <th >Screen Size in Front</th>
										 <th >Qty Ink Recive </th>
										 <th >Qty Ink Used</th>
										 <th>Qty Ink Return</th>
										 <th colspan="10"></th>
										</tr>
									
										<tbody >	
										';
										foreach($data['ink_use_front'] as $ink_use_front){
											//printr($ink_use_front);
										
													$html.='
													<tr >
													
														<td>'.$ink_use_front['ink_name'].' </th>
														 <td colspan="1">'.$ink_use_front['qty_ink_recive'].'</td>
														 <td colspan="1" >'.$ink_use_front['qty_ink_used'].'</td>
														 <td colspan="1">'.$ink_use_front['qty_ink_return'].'</td>
														 <td colspan="1">'.$ink_use_front['ink_wastage'].'</td>
														 <td colspan="11"></td>
														 </tr>';
										}
										
										$html.='</tbody>
									</thead>
								';
							$html .= '
										<thead >		
										
										<tr id="first" style="border:groove;" >
									  
									     <th colspan="11" >INK Use In Back :- </th>
										  <th colspan="3" ><th>
										 </tr>
										 <tr style="border:groove;">
										 <th colspan="1">Ink Name </th>
										 <th >Screen Size in Front</th>
										 <th >Qty Ink Recive </th>
										 <th >Qty Ink Used</th>
										 <th>Qty Ink Return</th>
										 <th colspan="10"></th>
										</tr>
									
										<tbody >	
										';
										foreach($data['ink_use_back'] as $ink_use_back){
											//printr($ink_use_front);
										
													$html.='
													<tr >
													
														<td>'.$ink_use_back['ink_name'].' </th>
														 <td colspan="1">'.$ink_use_back['qty_ink_recive'].'</td>
														 <td colspan="1" >'.$ink_use_back['qty_ink_used'].'</td>
														 <td colspan="1">'.$ink_use_back['qty_ink_return'].'</td>
														 <td colspan="1">'.$ink_use_back['ink_wastage'].'</td>
														 <td colspan="11"></td>
														 </tr>';
										}
										
										$html.='</tbody>
									</thead>
								';
						$html .= "</tbody>
								</table>
							 </div>
						</div>";
		
		//	echo $html ; die;
			return $html;
	
		}
	
	public function job_detail($job_name)
	{	
		$sql="SELECT * FROM  job_master WHERE job_name LIKE '%".strtolower($job_name)."%' AND is_delete=0 AND status = 1 AND `printing_option` = '2' ";

		//echo $sql;die;
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	 public function getLastId() {
        $sql = "SELECT digital_printing_id FROM  digital_printing ORDER BY digital_printing_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['digital_printing_id'];
        } else {
            return false;
        }
    }
	
	public function remove_ink_front($digital_pri_ink_id)
	{		//printr($lamination_layer_id);die;
			$sql = "UPDATE `" . DB_PREFIX . "digital_printing_ink_front` SET is_delete = '1',  date_modify = NOW() WHERE digital_pri_ink_id = '".$digital_pri_ink_id."' ";		
			//echo $sql;die;
	   		$this->query($sql);
		
	}
	public function remove_ink_back($digital_pri_ink_back_id)
	{		//printr($lamination_layer_id);die;
			$sql = "UPDATE `" . DB_PREFIX . "digital_printing_ink_back` SET is_delete = '1',  date_modify = NOW() WHERE digital_pri_ink_back_id = '".$digital_pri_ink_back_id."' ";		
			//echo $sql;die;
	   		$this->query($sql);
		
	}
	
	public function printing_operator_name($id){
		$sql = "SELECT *,CONCAT(first_name ,' ', last_name) as printing_operator_name FROM `employee` WHERE is_delete = 0 AND  status=1  AND employee_id  = '".$id."'";
		//printr($sql);
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row['printing_operator_name'];
		}else{
			return false;
		
			}
	}
		public function getcountryname($id){
		$sql = "SELECT * FROM `country` WHERE is_delete = 0 AND  status=1 AND  `country_id` ='".$id."'";
		//printr($sql);
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->row['country_name'];
		}else{
			return false;
		
			}
	}

	
}
?>