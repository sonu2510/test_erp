<?php
//==>kinjal
class adhesive extends dbclass{
	
	
	public function getOperatorName()
	{
		$sql = "SELECT e.employee_id,e.user_name FROM employee as e WHERE e.is_delete = '0' AND e.user_type = '9'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}

	public function getChemistName()
	{
		$sql="SELECT *,CONCAT(first_name ,' ', last_name) as operator_name FROM  user_type_production as utp,employee as e WHERE  e.is_delete=0 AND e.status = 1 AND e.user_type=utp.user_type_id AND e.user_type='6'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getMachine()
	{
		$sql = "SELECT * FROM `" . DB_PREFIX . "machine_master` WHERE is_delete = 0 AND production_process_id LIKE'%3%'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function addAdhesive($data)
	{   
		
	
	
		$sql = "INSERT INTO adhesive_details SET  adhesive_no = '" .$data['adhesive_no']. "',operator_id = '" .$data['operator_id']. "',machine_id = '" .$data['machine_id']. "',shift = '" .$data['shift']. "',date = '" .$data['job_date']. "',hardner_used='".$data['hardner_used']."',	adhesive_used='".$data['adhesive_used']."',ethyle_used='".$data['ethyle_used']."',total_used='".$data['total_used']."', user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "',user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',status = '1', date_added = NOW(),is_delete=0";
		$this->query($sql);
        $adhesive_id = $this->getLastId();
		
			if(!empty($data['adhesive_details'] )){
				
				foreach($data['adhesive_details'] as $ad){
			
					$this->query("INSERT INTO  adhesive_material_details SET adhesive_id = '".$adhesive_id."', m_status = '0',material_name='adhesive', product_item_id = '".$ad['product_item_id']."',used = '".$ad['use']."',  date_added = NOW(),is_delete=0");
				}
			}
			if(!empty($data['hardner_details'] )){
			
				foreach($data['hardner_details'] as $har){
							$this->query("INSERT INTO  adhesive_material_details SET adhesive_id = '".$adhesive_id."', m_status = '1',material_name='hardner', product_item_id = '".$har['product_item_id']."',used = '".$har['use']."',  date_added = NOW(),is_delete=0");
				}
			}
			if(!empty($data['ethyle_details'] )){
		
				foreach($data['adhesive_details'] as $eth){
							$this->query("INSERT INTO  adhesive_material_details SET adhesive_id = '".$adhesive_id."', m_status = '2',material_name='ethyle', product_item_id = '".$eth['product_item_id']."',used = '".$eth['use']."', date_added = NOW(),is_delete=0");
				}
			}
		
      return $adhesive_id;
	  
			
	}
        
	public function getTotalAdhesiveDetails($filter_data=array())
	{
         
         
	
        $sql = "SELECT  COUNT(*) as total FROM adhesive_details as a, employee as e , machine_master as m WHERE a.is_delete = 0 AND e.employee_id=a.operator_id AND a.machine_id=m.machine_id ";
     // printr($filter_data);
		if(!empty($filter_data)){
			if(!empty($filter_data['date'])){
				$sql .= " AND a.date LIKE '%".$filter_data['date']."%' ";		
			}		
			if(!empty($filter_data['shift'])){
				$sql .= " AND a.shift LIKE '%".$filter_data['shift']."%' "; 	
			}
		}
		$data = $this->query($sql);
        if ($data->row) {
            	return $data->row['total'];
        } else {
            return false;
        }
	
	
	}
	public function getAdhesiveDetails_ALL($data,$filter_data=array())
	{
           
        $sql = "SELECT a.*,e.user_name,m.machine_name FROM adhesive_details as a, employee as e , machine_master as m  WHERE a.is_delete = 0 AND e.employee_id=a.operator_id AND a.machine_id=m.machine_id ";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['date'])){
				$sql .= " AND a.date LIKE '%".$filter_data['date']."%' ";		
			}		
			if(!empty($filter_data['shift'])){
				$sql .= " AND a.shift LIKE '%".$filter_data['shift']."%' "; 	
			}
		}
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY a.adhesive_id";	
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
		
		
		//	echo $sql;//die;
		  $data = $this->query($sql);
        if ($data->row) {
            return $data->rows;
        } else {
            return false;
        }
		
	}
        
	public function getAdhesive_All($adhesive_id)
 {

        $sql = "SELECT * FROM adhesive_details as a, employee as e , machine_master as m   WHERE a.is_delete = 0 AND e.employee_id=a.operator_id AND a.machine_id=m.machine_id  AND  adhesive_id='" . $adhesive_id . "'";
        $data = $this->query($sql);
        if ($data->row) {
            return $data->row;
        } else {
            return false;
        }
    }

	public function AdhesiveDetails($adhesive_id,$status)
		 {

				$sql = "SELECT * FROM adhesive_material_details as ad   ,product_item_info as p  WHERE ad.is_delete = 0  AND ad.m_status='".$status."' AND ad.adhesive_id='" . $adhesive_id . "' AND p.product_item_id=ad.product_item_id";
				$data = $this->query($sql);
				if ($data->row) {
					return $data->rows;
				} else {
					return false;
				}
			}
			
	
	
    public function updateAdhesive($data,$adhesive_id)
	{ 
//	printr($data);die;
	
		$sql = "UPDATE adhesive_details SET operator_id = '" .$data['operator_id']. "',machine_id = '" .$data['machine_id']. "',shift = '" .$data['shift']. "',date = '" .$data['job_date']. "',hardner_used='".$data['hardner_used']."',adhesive_used='".$data['adhesive_used']."',ethyle_used='".$data['ethyle_used']."',total_used='".$data['total_used']."', user_id = '" . $_SESSION['ADMIN_LOGIN_SWISS'] . "',user_type_id = '" . $_SESSION['LOGIN_USER_TYPE'] . "',status = '1', date_added = NOW(),is_delete=0 WHERE adhesive_id='".$adhesive_id."'";
		$this->query($sql);
    //    $adhesive_id = $this->getLastId();
		
			if(!empty($data['adhesive_details'] )){
				
				foreach($data['adhesive_details'] as $ad){
				if ((isset($ad['adhesive_material_id']) && ($ad['adhesive_material_id'] != '' )) &&  $ad['m_status']==0) {
						$this->query(" UPDATE  adhesive_material_details SET adhesive_id = '".$adhesive_id."', m_status = '0',material_name='adhesive', product_item_id = '".$ad['product_item_id']."',used = '".$ad['use']."',  date_added = NOW(),is_delete=0 WHERE adhesive_material_id='".$ad['adhesive_material_id']."'");
				
					}else{
						$this->query("INSERT INTO  adhesive_material_details SET adhesive_id = '".$adhesive_id."', m_status = '0',material_name='adhesive', product_item_id = '".$ad['product_item_id']."',used = '".$ad['use']."',  date_added = NOW(),is_delete=0");
					}
				}
			}
			if(!empty($data['hardner_details'] )){
			
				foreach($data['hardner_details'] as $har){
						if ((isset($har['adhesive_material_id']) && ($har['adhesive_material_id'] != '' )) &&  $har['m_status']==1) {
								$this->query("UPDATE adhesive_material_details SET adhesive_id = '".$adhesive_id."', m_status = '1',material_name='hardner', product_item_id = '".$har['product_item_id']."',used = '".$har['use']."',  date_added = NOW(),is_delete=0 WHERE adhesive_material_id='".$har['adhesive_material_id']."'");
						}else{
								$this->query("INSERT INTO  adhesive_material_details SET adhesive_id = '".$adhesive_id."', m_status = '1',material_name='hardner', product_item_id = '".$har['product_item_id']."',used = '".$har['use']."',  date_added = NOW(),is_delete=0");
						}
				}
			}
			if(!empty($data['ethyle_details'] )){
		
				foreach($data['ethyle_details'] as $eth){
						
					//	printr($eth);die;
						
						
						if ((isset($eth['adhesive_material_id']) && ($eth['adhesive_material_id'] != '' )) &&  $eth['m_status']==2) {
						//	echo"hii";
								$this->query("UPDATE  adhesive_material_details SET adhesive_id = '".$adhesive_id."', m_status = '2',material_name='ethyle', product_item_id = '".$eth['product_item_id']."',used = '".$eth['use']."', date_added = NOW(),is_delete=0 WHERE adhesive_material_id='".$eth['adhesive_material_id']."'");
						}else{
						
						
		
							$this->query("INSERT INTO  adhesive_material_details SET adhesive_id = '".$adhesive_id."', m_status = '2',material_name='ethyle', product_item_id = '".$eth['product_item_id']."',used = '".$eth['use']."', date_added = NOW(),is_delete=0");
						}							
				}
			}
		
		//die;
    }

    public function updateRollStatus($id,$status)
	{
            $sql = "UPDATE adhesive_details SET status = '" .(int)$status. "',  date_modify = NOW() WHERE adhesive_id = '".$id."' ";
			$this->query($sql);
	}
		
	public function updateStatus($status,$data)
	{
		
		if($status == 0 || $status == 1){
			$sql = "UPDATE adhesive_details SET status = '" .(int)$status. "',  date_modify = NOW() WHERE adhesive_id IN (" .implode(",",$data). ")";		
			
		
			
			$this->query($sql);
			//	printr($sql);die;
		}elseif($status == 2){
			$sql = "UPDATE adhesive_details SET is_delete = '1', date_modify = NOW() WHERE adhesive_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
        
    public function remove_adhesive($adhesive_material_id)
        {
            $sql = "UPDATE adhesive_material_details SET is_delete = '1' WHERE adhesive_material_id ='".$adhesive_material_id."' ";
            $this->query($sql);
        }
        
  
	public function getlatestAdhesiveid()
	{
		$sql = "SELECT adhesive_id FROM `" . DB_PREFIX . "adhesive_details` WHERE is_delete = 0 ORDER BY  adhesive_id DESC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['adhesive_id'];
		}else{
			return false;
		}
	}
	public function getAdhesive_details()
	{//online:8 and offline:8
		$sql = "SELECT * FROM " . DB_PREFIX . "product_item_info as pif, product_category as pc WHERE pif.product_category_id = '8' AND pif.is_delete=0 AND pif.product_category_id=pc.product_category_id";
//		echo $sql;
        $data = $this->query($sql);

        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
	}
	public function getHardner_details()
	{//online:10 and offline:10
		$sql = "SELECT * FROM " . DB_PREFIX . "product_item_info as pif, product_category as pc WHERE pif.product_category_id = '10' AND pif.is_delete=0 AND pif.product_category_id=pc.product_category_id";
//		echo $sql;
        $data = $this->query($sql);

        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
	}
	public function getEthyle_details()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "product_item_info as pif, product_category as pc WHERE pif.product_category_id = '9' AND pif.is_delete=0 AND pif.product_category_id=pc.product_category_id";
//		echo $sql;
        $data = $this->query($sql);

        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
	}
	
		public function viewAdhesive_report($adhesive_id){
		
			
		$adhesive_details_all = $this->getAdhesive_All($adhesive_id);
		$adhesive_details= $this->AdhesiveDetails($adhesive_id,0);
		$hardner_details= $this->AdhesiveDetails($adhesive_id,1);
		$ethyle_details= $this->AdhesiveDetails($adhesive_id,2);
		
		
		$html = '';
		//	printr($adhesive_details_all);		
		//die;
	
			$html .= "<div class='form-group' style='font-size:28'>
						<div class='table-responsive'>";
						$html .= "<center ><span  ><b id='lamination' >ADHESIVE ,HARDNER & ETHYLE USAGE</b></span></center><br>";
			
			
		
						$html .= '<table class="table b-t text-small table-hover"  id="lamination_report" >
								<thead>
								<tr id="first" style="border:groove;">
										
										 
										 <td colspan="2" height="100%"></td>					
										 <td colspan="2" width="auto"><b>Operator Name  : </b>'. ucwords($adhesive_details_all['user_name']).' </td>
										 <td colspan="2" width="auto"><b>Shift :</b>   '.$adhesive_details_all['shift'].'</td>
										 <td colspan="1"  ><b> Date : </b>  '.dateFormat(4,$adhesive_details_all['date']).'</td>
										<td  colspan="1" ></td>										 
									</tr>';
					
									
						$html.='<tr  style="border:groove;">
										
										
										 <td colspan="2" height="70%"><b id ="first">Adhesive Details(Kgs) : </b>';

										$html.='<table class="table b-t text-small table-hover"  id="lamination_report" >
													<thead style="border:groove;" id="data">
													<th  style="border:groove;">Name </th>												
													<th style="border:groove;">Used </th>												
													</thead>
												<tbody >';
												if(!empty($adhesive_details)){
													foreach($adhesive_details as $ad ) {
											
												
														$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;">'.$ad['product_name'].'</td>										
																<td  style="border:groove;">'.$ad['used'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;"><b>Total</b></td>
								
																<td  style="border:groove;"><b>'.$adhesive_details_all['adhesive_used'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;" id="data">	
															<td colspan="2"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table>			

										 </td>
										 	<td colspan="1"></td>
										 <td colspan="2" width="auto"><b id="first">Hardner Details (Kgs) :</b>';
										$html.='<table class="table b-t text-small table-hover"  id="lamination_report" >
													<thead style="border:groove;" id="data">
													<th  style="border:groove;">Name </th>												
													<th style="border:groove;"> Used </th>													
													</thead><tbody >';
												if(!empty($hardner_details)){
													foreach($hardner_details as $har ) {
											
												
														$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;">'.$har['product_name'].'</td>											
																<td  style="border:groove;">'.$har['used'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;"><b>Total</b></td>																
																<td  style="border:groove;"><b>'.$adhesive_details_all['hardner_used'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;" id="data">	
															<td colspan="2"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
								
										$html.= '</tbody>														
										</table>			



										 </td>
										 	<td colspan="1"></td>	
										<td colspan="2" width="auto"><b id="first">Ethyle Details(Kgs) :</b> ';
											$html.='<table  class="table b-t text-small table-hover" id="lamination_report" >
													<thead style="border:groove;" id="data">
													<th  style="border:groove;">Name </th>												
													<th style="border:groove;"> Used </th>												
													</thead>
												<tbody >';
												if(!empty($ethyle_details)){
													foreach($ethyle_details as $eth ) {
											
												
														$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;">'.$eth['product_name'].'</td>											
																<td  style="border:groove;">'.$eth['used'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;"><b>Total</b></td>													
																<td  style="border:groove;"><b>'.$adhesive_details_all['ethyle_used'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;" id="data">	
															<td colspan="2"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table> </td>';
									
									$html.= '</tr>';
						$html .= "</tbody>
								</table>
							 </div>
						</div>";
		
			//printr($html);
			return $html;
	
	}
	
	public function AdhesiveArrayForCSV($Adhesive_nos)
	{	
		$i=0;
		$html='';
			$html.='<div class="table-responsive">';
						$html.='<table class="table b-t text-small table-hover" style="border:groove;">';
						 $html.=' <thead style="border:groove;">';
						 $html.='<tr style="border:groove;"> ';                    			 
							$html.='<th>Operator Name </th>';
							 $html.='<th >Shift </th>';
							$html.='<th>Date</th>';
							  $html.='<th>Adhesive Details(Kgs) </th>';
							   $html.='<th>Hardner Details (Kgs) </th>';
								$html.='<th>Ethyle Details(Kgs)</th>';							 
							  $html.='</tr>';
							  $html.='</thead>';
					foreach($Adhesive_nos as $adhesive_id)
					{
					
					$adhesive_details_all = $this->getAdhesive_All($adhesive_id);
					$adhesive_details= $this->AdhesiveDetails($adhesive_id,0);
					$hardner_details= $this->AdhesiveDetails($adhesive_id,1);
					$ethyle_details= $this->AdhesiveDetails($adhesive_id,2);
							
									
							if(!empty($adhesive_details_all)){
								
										$html.='<tbody style="border:groove;">';
									   $html.='<tr style="border:groove;">';
										$html.='<td align="top">'. ucwords($adhesive_details_all['user_name']).'</td>';                         
										$html.='<td align="top">'. $adhesive_details_all['shift'].'</td>';                         
										$html.='<td align="top">'. dateFormat(4,$adhesive_details_all['date']).'</td>';                         
										
										$html.='<td>';
										$html.='<table class="table b-t text-small table-hover"  id="lamination_report" >
													<thead style="border:groove;" id="data">
													<th  style="border:groove;">Name </th>												
													<th style="border:groove;">Used </th>												
													</thead>
												<tbody >';
												if(!empty($adhesive_details)){
													foreach($adhesive_details as $ad ) {
											
												
														$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;">'.$ad['product_name'].'</td>										
																<td  style="border:groove;">'.$ad['used'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;"><b>Total</b></td>
								
																<td  style="border:groove;"><b>'.$adhesive_details_all['adhesive_used'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;" id="data">	
															<td colspan="2"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table>';							  
										$html.='</td>';
										$html.='<td>';
										$html.='<table class="table b-t text-small table-hover"  id="lamination_report" >
													<thead style="border:groove;" id="data">
													<th  style="border:groove;">Name </th>												
													<th style="border:groove;"> Used </th>													
													</thead><tbody >';
												if(!empty($hardner_details)){
													foreach($hardner_details as $har ) {
											
												
														$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;">'.$har['product_name'].'</td>											
																<td  style="border:groove;">'.$har['used'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;"><b>Total</b></td>																
																<td  style="border:groove;"><b>'.$adhesive_details_all['hardner_used'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;" id="data">	
															<td colspan="2"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
								
										$html.= '</tbody>														
										</table>';							  
										$html.='</td>';
										$html.='<td>';
										 	$html.='<table  class="table b-t text-small table-hover" id="lamination_report" >
													<thead style="border:groove;" id="data">
													<th  style="border:groove;">Name </th>												
													<th style="border:groove;"> Used </th>												
													</thead>
												<tbody >';
												if(!empty($ethyle_details)){
													foreach($ethyle_details as $eth ) {
											
												
														$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;">'.$eth['product_name'].'</td>											
																<td  style="border:groove;">'.$eth['used'].'</td>
															
														</tr>';	 
												
													}
													$html.='
														<tr style="border:groove;" id="data">	
																<td style="border:groove;"><b>Total</b></td>													
																<td  style="border:groove;"><b>'.$adhesive_details_all['ethyle_used'].'</b></td>
															
												</tr>';	 }else{
													$html.='
														<tr style="border:groove;" id="data">	
															<td colspan="2"style="border:groove;">No record Found !!!</td>																
														</tr>';
													
												}
										
										$html.= '</tbody>														
										</table>';				  
										$html.='</td>';
										
									
									   
									$html.='</tr>';
									
							  } else{ 
								  $html.='<tr><td colspan="5">No record found !</td></tr>';
							  }
						 
							 $html.=' </tbody>';
						 
					}
					  $html.=' </table>';
			 $html.=' </div>';
		  return $html;
		//return $input_array;
	}
}
?>