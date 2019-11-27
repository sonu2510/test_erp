<?php
//add by sonu
class pouching extends dbclass{
	public function getOperator()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name  FROM user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type='13'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
		public function getJuniorOperator()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type='13'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getJuniorOperator_name($id)
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.employee_id='".$id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getMachine()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "machine_master` WHERE is_delete = 0 AND production_process_id LIKE'%4%'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getRollName()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "roll_name` WHERE is_delete = 0";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getRollNo()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_inward` WHERE  slt_is_delete='0' AND is_delete = 0 AND status=1 AND roll_no!=''";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}	
	public function getzipper_details()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "product_item_info` WHERE   is_delete = 0 AND status=1 AND  product_category_id=5 ";
		$data = $this->query($sql);
		//printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getlatestNo()
	{
		$sql = "SELECT product_inward_id FROM `" . DB_PREFIX . "product_inward` WHERE  slt_is_delete='0' AND is_delete = '0' ORDER BY product_inward_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['product_inward_id'];
		}else{
			return false;
		}	
	}
	public function addpouching($data)
	{
	
		if((isset($data['remark'])) && (!empty($data['remark']))){
			$remark=$data['remark'];
		}else{
			$remark=$data['remark_pouching'];
		}
		
		
		
	
	
		$sql = "INSERT INTO `" . DB_PREFIX . "pouching` SET  pouching_no = '" .$data['pouching_no']. "',pouching_date = '" .$data['pouching_date']. "',job_id = '" .$data['job_id']. "',slitting_id = '" .$data['slitting_id']. "',operator_id = '" .$data['operator_id']. "',junior_id = '" .$data['junior_id']. "',machine_id = '" .$data['machine_id']. "',zipper_used = '" .$data['zipper_used']. "',zipper_used_kg = '" .$data['zipper_used_kg']. "',zipper_id = '" .$data['zipper_id']. "',start_time = '" .$data['start_time']. "',end_time = '" .$data['end_time']. "',shift = '" .$data['shift']. "',remark='".$remark."',remark_pouching='".$data['remark_pouching']."',input_qty='".$data['input_qty']."',output_qty='".$data['output_qty']."',output_qty_kg='".$data['output_qty_kg']."',output_qty_meter='".$data['output_qty_meter']."',online_setting_wastage='".$data['online_setting_wastage']."',total_wastage='".$data['total_wastage']."',operator_wastage='".$data['operator_wastage']."',lamination_wastage='".$data['lamination_wastage']."',date_added = NOW(),date_modify = NOW(),is_delete=0,added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
		$this->query($sql);
	//	echo $sql;die; 
		$pouching_id = $this->getLastId();
		
		if(isset($data['job_id']) && !empty($data['job_id'])){
			
					
				$this->query("UPDATE" . DB_PREFIX . " job_master  SET pouching_status = '1' ,pouching_id='".$pouching_id."'	WHERE job_id = '".$data['job_id']."'");
		
		}

/*	if(isset($data['roll_details']) && !empty($data['roll_details'])){

		foreach ($data['roll_details'] as $roll_details) {
		
		$sql = "INSERT INTO  pouching_roll_detail  SET 	pouching_id='".$pouching_id."',roll_no='".$roll_details['roll_no']."',	roll_input_qty='".$roll_details['roll_input_qty']."',roll_output_qty='".$roll_details['roll_output_qty']."',roll_bal_qty='".$roll_details['roll_bal_qty']."',date_added = NOW(),date_modify = NOW(),is_delete=0";
			$this->query($sql);
		
			$this->query("UPDATE" . DB_PREFIX . " slitting_process  SET p_input_qty='".$roll_details['roll_bal_qty']."',pouching_id='".$pouching_id."'WHERE slitting_material_id = '".$roll_details['roll_no']."'");
		
		}
		
	}*/

}
	
	public function getlatestpouchingid()
	{
		$sql = "SELECT pouching_no FROM `" . DB_PREFIX . "pouching` WHERE is_delete = 0 ORDER BY  pouching_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['pouching_no'];
		}else{
			return false;
		}
	}
	public function getTotalpouching($filter_data=array(),$pouching_date='')
	{
		$con='';
		if($pouching_date!='')
		{
			$con="AND pouching_date='".$pouching_date."'";
		}
		
		//$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "pouching` as sl WHERE sl.is_delete = 0 $con";
		$sql = "SELECT COUNT(*) as total,sl.*,CONCAT(om.first_name ,' ', om.last_name) as operator_name,m.machine_name  FROM pouching as sl, employee as om, machine_master as m WHERE sl.is_delete = 0 AND sl.operator_id=om.employee_id AND sl.machine_id = m.machine_id  $con ";
		//printr($filter_data);
		if(!empty($filter_data)){
			
			if(!empty($filter_data['operator_id'])){
				$sql .= " AND sl.operator_id = '".$filter_data['operator_id']."' "; 	
			}
			if(!empty($filter_data['pouching_date'])){
				$sql .= " AND sl.pouching_date = '".$filter_data['pouching_date']."' "; 	
			}
			if(!empty($filter_data['machine_id'])){
				$sql .= " AND sl.machine_id = '".$filter_data['machine_id']."' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pouching_id";	
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
	public function getpouching($option,$filter_data=array(),$pouching_date='')
	{
		$con='';
		if($pouching_date!='')
		{
			$con="AND pouching_date='".$pouching_date."'";
		}
		$sql = "SELECT sl.*,CONCAT(om.first_name ,' ', om.last_name) as operator_name,m.machine_name,j.job_no  FROM pouching as sl,job_master as j, employee as om,machine_master as m WHERE sl.is_delete = 0 AND sl.job_id = j.job_id AND sl.operator_id=om.employee_id AND  sl.machine_id = m.machine_id  $con ";
		
		//printr($sql);die;
		if(!empty($filter_data)){
			
			if(!empty($filter_data['operator_id'])){
				$sql .= " AND sl.operator_id = '".$filter_data['operator_id']."' "; 	
			}
			if(!empty($filter_data['machine_id'])){
				$sql .= " AND sl.machine_id = '".$filter_data['machine_id']."' "; 	
			}
			
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pouching_id";	
		}

		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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
		//echo $sql;die;
		$data = $this->query($sql);
		
		return $data->rows;
		
		
	}
	public function getJobDetail($slitting_id)
	{
		
		$sql = "SELECT sl.*,om.first_name as operator_name,mm.machine_name FROM slitting as sl, employee as om,machine_master as mm WHERE sl.is_delete = 0 AND sl.slitting_id = '".$slitting_id."' AND sl.operator_id=om.employee_id AND sl.machine_id = mm.machine_id ";
		$data = $this->query($sql);
		
		return $data->row;
		
	
	
	}
	public function getPouchDetail($pouching_id)
	{
		
	//	$sql = "SELECT sl.*,om.first_name as operator_name,mm.machine_name,pi.product_name FROM pouching as sl, employee as om,machine_master as mm ,product_item_info as pi  WHERE sl.is_delete = 0 AND sl.pouching_id = '".$pouching_id."' AND sl.operator_id=om.employee_id AND sl.machine_id = mm.machine_id AND pi.product_item_id = sl.zipper_id";
		$sql = "SELECT sl.*,om.first_name as operator_name,mm.machine_name FROM pouching as sl, employee as om,machine_master as mm   WHERE sl.is_delete = 0 AND sl.pouching_id = '".$pouching_id."' AND sl.operator_id=om.employee_id AND sl.machine_id = mm.machine_id";
		$data = $this->query($sql);
	
		
		return $data->row;
	
	}
	public function getslitting_roll_details($slitting_id)
	{
		
		$sql = "SELECT * FROM slitting_process  WHERE  is_delete=0 AND  slitting_id = '".$slitting_id."' ";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		

	}
	public function getPouchRollDetail($pouching_id)
	{
		
		$sql = "SELECT * FROM  pouching_roll_detail  WHERE  is_delete=0 AND  pouching_id = '".$pouching_id."' ";
		$data = $this->query($sql);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
		

	}
	
	public function updatepouching($pouching_id,$data)
	{
		
	//printr($data);die;
		if((isset($data['trimming_wastage'])) && !empty($data['trimming_wastage'])){
			$data['trimming_wastage']=$data['trimming_wastage'];
		}else{
			$data['trimming_wastage']=0.00;
		}
		if((isset($data['lamination_wastage'])) && !empty($data['lamination_wastage'])){
			$data['lamination_wastage']=$data['lamination_wastage'];
		}else{
			$data['lamination_wastage']=0.00;
		}
		if((isset($data['top_cut_wastage'])) && !empty($data['top_cut_wastage'])){
			$data['top_cut_wastage']=$data['top_cut_wastage'];
		}else{
			$data['top_cut_wastage']=0.00;
		}
		if((isset($data['printing_wastage'])) && !empty($data['printing_wastage'])){
			$data['printing_wastage']=$data['printing_wastage'];
		}else{
			$data['printing_wastage']=0.00;
		}
		if((isset($data['remark'])) && (!empty($data['remark']))){
			$remark=$data['remark'];
	
		}else{
			$remark=$data['remark_pouching'];
		}
	
		$sql = "UPDATE `" . DB_PREFIX . "pouching` SET  pouching_no = '" .$data['pouching_no']. "',pouching_date = '" .$data['pouching_date']. "',operator_id = '" .$data['operator_id']. "',junior_id = '" .$data['junior_id']. "',machine_id = '" .$data['machine_id']. "',zipper_used = '" .$data['zipper_used']. "',zipper_used_kg = '" .$data['zipper_used_kg']. "',zipper_id = '" .$data['zipper_id']. "',start_time = '" .$data['start_time']. "',end_time = '" .$data['end_time']. "',shift = '" .$data['shift']. "',remark='".$remark."',remark_pouching='".$data['remark_pouching']."',input_qty='".$data['input_qty']."',output_qty='".$data['output_qty']."',output_qty_kg='".$data['output_qty_kg']."',output_qty_meter='".$data['output_qty_meter']."',lamination_wastage='".$data['lamination_wastage']."',online_setting_wastage='".$data['online_setting_wastage']."',total_wastage='".$data['total_wastage']."',operator_wastage='".$data['operator_wastage']."', date_added = NOW(),date_modify = NOW(),is_delete=0,added_user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', added_user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE pouching_id = '" .(int)$pouching_id."'";

		$this->query($sql);

/*
		if(isset($data['roll_details']) && !empty($data['roll_details'])){

		foreach ($data['roll_details'] as $roll_details) {

			if(isset( $roll_details['pouching_roll_id']) && !empty($roll_details['pouching_roll_id'])){
		
					$sql = "UPDATE pouching_roll_detail  SET 	pouching_id='".$pouching_id."',roll_no='".$roll_details['roll_no']."',	roll_input_qty='".$roll_details['roll_input_qty']."',roll_output_qty='".$roll_details['roll_output_qty']."',roll_bal_qty='".$roll_details['roll_bal_qty']."',date_added = NOW(),date_modify = NOW(),is_delete=0 WHERE pouching_roll_id='".$roll_details['pouching_roll_id']."'";
						$this->query($sql);
					
						$this->query("UPDATE" . DB_PREFIX . " slitting_process  SET p_input_qty='".$roll_details['roll_bal_qty']."',pouching_id='".$pouching_id."'	WHERE slitting_material_id = '".$roll_details['roll_no']."'");
				}else{
					$sql = "INSERT INTO  pouching_roll_detail  SET 	pouching_id='".$pouching_id."',roll_no='".$roll_details['roll_no']."',	roll_input_qty='".$roll_details['roll_input_qty']."',roll_output_qty='".$roll_details['roll_output_qty']."',roll_bal_qty='".$roll_details['roll_bal_qty']."',date_added = NOW(),date_modify = NOW(),is_delete=0";
						$this->query($sql);
					
						$this->query("UPDATE" . DB_PREFIX . " slitting_process  SET p_input_qty='".$roll_details['roll_bal_qty']."',pouching_id='".$pouching_id."'	WHERE slitting_material_id = '".$roll_details['roll_no']."'");


				}	
		}
		
	}*/
	}

	
	
	public function job_detail()
	{	
	//	$sql="SELECT * FROM  printing_job WHERE  is_delete=0 AND  job_type='pouching' AND lamination_status='1'";
		$sql='SELECT * FROM `slitting` WHERE job_no !=" " AND is_delete="0" AND pouching_status="0"';
	//	echo $sql;
		$data = $this->query($sql);
	//	printr($data); 
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getDateWiseJob($filter_data)
	{
		$sql = "SELECT pouching_date FROM pouching WHERE is_delete = 0 ";
			
			if(!empty($filter_data['pouching_date'])){
					$sql .= " AND pouching_date = '".$filter_data['pouching_date']."' "; 	
				}
					$sql .= "GROUP BY pouching_date";
			
    	if (isset($filter_data['sort'])) {
			$sql .= " ORDER BY " . $filter_data['sort'];	
		} else {
			$sql .= " ORDER BY pouching_id";	
		}

		if (isset($filter_data['order']) && ($filter_data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {
			$sql .= " DESC";
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
			
			return $data->rows;
		
	}
	
	
	public function getLamination_details($lamination_id){
		$sql = "SELECT l.*,mm.machine_name,jm.job_name as job_num_id FROM  lamination as l,lamination_layer as ll, machine_master as mm,job_master as jm WHERE l.is_delete = 0 AND l.lamination_id = '".$lamination_id."' AND  l.machine_id = mm.machine_id AND l.job_id=jm.job_id AND l.lamination_id=ll.lamination_id";
	
		$data = $this->query($sql);		
		if ($data->num_rows) {
				return $data->row;
			} else {
				return false;
			}
	}
	public function view_pouching_report($pouching_id,$slitting_details)
	{
		
		$data = $this->getPouchDetail($pouching_id);
		 $product_name = $this->zipper_name($data['zipper_id']);
		 $job_details = $this->getJob_layer_details($slitting_details['job_id']);
		 $roll_code_details = $this->getLamination_details($slitting_details['roll_code_id']);
	//	 printr($roll_code_details);
		 $junior_operator=$this->getJuniorOperator_name($data['junior_id']);
       		$junior_name='';
       		if(!empty($junior_operator)){
       			$junior_name='<b>Junior </b> : '.$junior_operator['first_name'].' '. $junior_operator['last_name'];

       		}
		$html = '';
		$html .='<div class="width_div"><div style="text-align:center;border: 1px solid black;"><b><h4>POUCHING JOB REPORT</h4></b>';
		$html.='</div><div class="div_first" style=" width: 100%;float: left;  border: 1px solid black;font-size: 18px;">
							<table style="width: 100%;" >
							<tr>
							<td style="vertical-align: top;width: 50%;">
							 	
								<b>Shift :</b>   '.ucwords($data['shift']).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								<b> Date : </b>  '.dateFormat(4,$data['date_added']).'
								

							</td>
							<td style="vertical-align: top;width: 50%;"><b> Pouching machine Name  : </b>   '. ucwords($data['machine_name']).'
							</td>
							</tr>
							</table>';
							
							$html .='<div class="div_first" style="width: 100%; float: left;  border: 1px solid black;">

					<table cellspacing="0px" cellpadding="10px" border="1" style="width: 100%; border-spacing: 0px;font-size: 14px;">
						<tbody>
						<tr>
								<td ><div align="center"><b>Job Name </b></div></td>	
								<td><div align="center"><b>Product Name</b></div></td>	
								<td><div align="center"><b>Operator Name</b></div></td>
								<td ><div align="center"><b>Pouching No</b></div></td>
								<td ><div align="center"><b> No Of Pouches</b></div></td>
								<td ><div align="center"><b> Laminaton Roll Code</b></div></td>
						</tr>	
						<tr>
								<td ><div align="center"><b>'.$job_details['job_name'].'</b></div></td>	
								<td ><div align="center">'.$job_details['product_name'].'</div></td>								
								<td ><div align="center"><b>Main : </b>'.$data['operator_name'].'  <br>'.$junior_name.'</b></div></td>								
								<td ><div align="center">'.$data['pouching_no'].'</div></td>
								<td ><div align="center">'.$data['output_qty'].'</div></td>
								<td ><div align="center">'.$roll_code_details['roll_code'].'</div></td>
								
											
						</tr>
						<tr>
							
								<td><div align="center"><b>Operator</b></div></td>
								<td><div align="center"><b>Input Qty</b></div></td>
								<td ><div align="center"><b>Output Qty (Kgs & meter)</b></div></td>	
								<td><div align="center"><b>zipper</b></div></td>
								<td ><div align="center"><b>zipper used (meter & Kgs)</b></div></td>
								<td ><div align="center"><b>  Wastage Details </b></div></td>							
						</tr>
						<tr>
						 
						
								
								<td ><div align="center"><b><b>Main : </b>'.$data['operator_name'].'  <br>'.$junior_name.'</b></div></td>
								<td ><div align="center"><b>'.$data['input_qty'].' Kgs</b></div></td>	
								<td><div align="center"><b>'.$data['output_qty_kg'].'(Kgs)<br>'.$data['output_qty_meter'].'(Meter)</b></div></td>
								<td><div align="center"><b>'.$product_name.'</b></div></td>
								<td><div align="center"><b>'.$data['zipper_used'].'(Meter)<br>'.$data['zipper_used_kg'].'(Kgs)</b></div></td>
								<td ><div align="center">Total  Wastage (%) : '.$data['total_wastage'].' <br>Operator Wastage (%):  '.$data['operator_wastage'].'</div></td>								
						</tr>
					
						</tbody><tbody></table>
						</div>';



			
	
			return $html;
	
		}

	
	
	
	public function getLastId() {
        $sql = "SELECT pouching_id FROM  pouching ORDER BY pouching_id DESC LIMIT 1";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['pouching_id'];
        } else {
            return false;
        }
    }	
	public function input_roll_details($job_id,$n=0) {

	$qty='';
//	if($n==0){
	//	$qty="AND sp.p_input_qty!='0.000'";
//	}
     if(!empty($job_id)){
	     $sql = "SELECT sp.slitting_material_id,sp.roll_code FROM  slitting_process as sp,slitting as s ,job_master as j WHERE  s.slitting_status = '1' AND s.slitting_id=sp.slitting_id AND s.is_delete='0'  AND sp.job_id=j.job_id AND sp.is_delete='0' $qty   AND sp.job_id IN (" .implode(",",$job_id). ")";
	   $data = $this->query($sql);
		//printr($data);
		$sql_lami = "SELECT * FROM `" . DB_PREFIX . "lamination` WHERE is_delete = 0 AND status=1 AND job_id IN (" .implode(",",$job_id). ")";
	
		$data_l = $this->query($sql_lami);
	//printr($data);
		$comb_arr=array();
		$comb_arr['slitting']=$data->rows;
		$comb_arr['lamination']=$data_l->rows;
		
		return $comb_arr;
	 }		
	
    }
    public function input_roll_details_view($job_id,$n=0) {

	$qty='';
//	if($n==0){
		//$qty="AND sp.p_input_qty!='0.000'";
	//}
     if(!empty($job_id)){
	     $sql = "SELECT * FROM  slitting_process as sp,slitting as s ,job_master as j WHERE  s.slitting_status = '1' AND s.slitting_id=sp.slitting_id AND s.is_delete='0'  AND sp.job_id=j.job_id AND sp.is_delete='0' $qty   AND sp.job_id IN (" .implode(",",$job_id). ")";
	   $data = $this->query($sql);
		//printr($data);
		$sql_lami = "SELECT * FROM `" . DB_PREFIX . "lamination` WHERE is_delete = 0 AND status=1 AND job_id IN (" .implode(",",$job_id). ")";
	
		$data_l = $this->query($sql_lami);
	//printr($data);
		$comb_arr=array();
		$comb_arr['slitting']=$data->rows;
		$comb_arr['lamination']=$data_l->rows;
		
		return $comb_arr;
	 }		
	
    }
	public function getROllDetail($slitting_material_id) {
		
   
	     $sql = "SELECT sp.* FROM  slitting_process as sp,slitting as s WHERE  s.slitting_status = '1' AND s.slitting_id=sp.slitting_id AND s.is_delete='0' AND sp.is_delete='0' AND sp.slitting_material_id='".$slitting_material_id."' ";
	   $data = $this->query($sql);
		  if ($data->num_rows) {
            return $data->row;
        } else {
            return false;
        }
		
	
		
	 //die;
    }
    public function zipper_name($product_item_id) {
		
   
	   $sql = "SELECT * FROM product_item_info WHERE  product_item_id='".$product_item_id."' ";
	   $data = $this->query($sql); 
		  if ($data->num_rows) {
            return $data->row['product_name'];
        } else { 
            return false;
        }
		
	
		
	 //die;
    }
	
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "pouching` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE pouching_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "pouching` SET   is_delete = '1', date_modify = NOW() WHERE pouching_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	public function getJob_layer_details($job_id){
		
		$sql = "SELECT * FROM  job_master as j, product as p WHERE j.is_delete = 0  AND p.product_id=j.product AND j.job_id = '".$job_id."'";
		
	//	echo $sql;die;
		$data = $this->query($sql);
	//	printr($data);
	
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	
	
}
?>