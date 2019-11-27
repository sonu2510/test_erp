<?php
class samplesheet extends dbclass{
	
	public function addSample($data){
	
		$sql = "INSERT INTO `" . DB_PREFIX . "sample_sheet` SET  customer_name = '" .$data['customer_name']. "',customer_visit_date = '".$data['customer_visit_date']."',customer_address = '".$data['customer_address']."',email ='".$data['email']."',telephone = '".$data['telephone']."',customer_requirements = '".$data['customer_requirements']."',customer_Product = '".$data['customer_Product']."',weight = '".$data['weight']."',total_bag = '".$data['total_bag']."',courier_name = '".$data['courier_name']."',tracking_no = '".$data['tracking_no']."',sample_user_name ='".$data['sample_user_name']."',dis_date ='".$data['dis_date']."',f1_date ='".$data['f1_date']."',f1_description ='".$data['f1_description']."',f2_date ='".$data['f2_date']."',f2_description ='".$data['f2_description']."',f3_date = '" .$data['f3_date']. "',f3_description ='".$data['f3_description']."',deal ='".$data['deal']."',status = '" .$data['status']. "',date_added = NOW(),date_modify = NOW(),is_delete=0";
		
		$this->query($sql);
	}
	
	public function updateSample($sample_id,$data){
		
		$sql = "UPDATE `" . DB_PREFIX . "sample_sheet` SET  customer_name = '" .$data['customer_name']. "',customer_visit_date = '".$data['customer_visit_date']."',customer_address = '".$data['customer_address']."',email ='".$data['email']."',telephone = '".$data['telephone']."',customer_requirements = '".$data['customer_requirements']."',customer_Product = '".$data['customer_Product']."',weight = '".$data['weight']."',total_bag = '".$data['total_bag']."',courier_name = '".$data['courier_name']."',tracking_no = '".$data['tracking_no']."',sample_user_name ='".$data['sample_user_name']."',dis_date ='".$data['dis_date']."',f1_date ='".$data['f1_date']."',f1_description ='".$data['f1_description']."',f2_date ='".$data['f2_date']."',f2_description ='".$data['f2_description']."',f3_date = '" .$data['f3_date']. "',f3_description ='".$data['f3_description']."',deal ='".$data['deal']."',status = '" .$data['status']. "',  date_modify = NOW() WHERE sample_id = '" .(int)$sample_id. "'";
		$this->query($sql);		
	}
	
	public function getTotalSample($filter_data=array()){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "sample_sheet` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND customer_name LIKE '%".$filter_data['customer_name']."%' ";		
			}
			
			/*if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}*/
		}
		
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function getSamples($data,$filter_data=array()){
		$sql = "SELECT * FROM `" . DB_PREFIX . "sample_sheet` WHERE is_delete = 0";
		
		if(!empty($filter_data)){
			if(!empty($filter_data['customer_name'])){
				$sql .= " AND customer_name LIKE '%".$filter_data['customer_name']."%' ";		
			}
			
			/*if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}*/
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY sample_id";	
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
	
	public function getSample($sample_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "sample_sheet` WHERE sample_id = '" .(int)$sample_id. "'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	
	public function updateSampleStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "sample_sheet` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE 
		sample_id = '".$id."' ";
		//echo $sql;die;
	    $this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "sample_sheet` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE sample_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "sample_sheet` SET is_delete = '1', date_modify = NOW() WHERE sample_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}
	
	public function getUserList()
		{
			$sql = "SELECT user_type_id,user_id,account_master_id,user_name FROM " . DB_PREFIX ."account_master ORDER BY user_name ASC";
			$data = $this->query($sql);
			//printr($data);die;
			return $data->rows;
		}
		
	public function getSampleView($sample_id)
		{
			$data = $this->query("SELECT * FROM `sample_sheet` WHERE sample_id = '".$sample_id."' ");
			//echo "SELECT * FROM `sample_sheet` WHERE sample_id = '".$sample_id."' AND status = '1' AND is_delete = 0 ";
		
			if($data->num_rows)
			{
				return $data->row;
			}
			 else 
			{
				return false;
			}
		}
		
		public function sample_view_detail($sample_detail)
		{
		
			$html = "";
			
			$html.="<div class='form-group'>
						<div class='table-responsive'>
					
					    <table class='table table-striped b-t text-small' id='stock_order' >
								<thead>
									<tr>
                                          <th>Customer Detail</th>
                                         
                                          <th> Customer Requirements</th>
										  
                                          <th> Customer Product</th>
                                         
                                          <th>Sample Dispatch Date</th>
										  
                                          <th>Dispatch Detail</th>
										  
                                          <th>Sample Sent By</th>
										  
                                          <th>Follow Up-1</th>
										  
                                          <th>Follow Up-2 </th>
										  
										   <th>Follow Up-3 </th>
                                         
                                          <th>Deal Closed</th>
                                          
                                    </tr>
                                </thead> 
                                
                                <tbody>";
								
                                $html .="<tr  valign='top'> <td>Customer Name: ".$sample_detail['customer_name']." <br><br> Customer Visit Date: ".dateFormat(4,$sample_detail['customer_visit_date'])." <br><br> Address: ".$sample_detail['customer_address']." <br><br> Email: ".$sample_detail['email']." <br><br> Telephone: ".$sample_detail['telephone']." </td>
                                
                               
                                 <td>".$sample_detail['customer_requirements']."</td>
								 
                                 <td>Customer Product: ".$sample_detail['customer_Product']." <br><br>Weight of Product in each bag: ".$sample_detail['weight']." <br><br> Total no of bag: ".$sample_detail['total_bag']."  </td>
								
                                 <td>".dateFormat(4,$sample_detail['dis_date'])."</td>
								 
                                 <td>Courier Name: ".$sample_detail['courier_name']." <br><br> Tracking No:  ".$sample_detail['tracking_no']."</td>";
								 
                                  $html .="<td>".$sample_detail['sample_user_name']."</td>
								 
                                 <td>Date: ".dateFormat(4,$sample_detail['f1_date'])." <br><br> Description: ".$sample_detail['f1_description']."  </td>
								 
								 <td>Date: ".dateFormat(4,$sample_detail['f2_date'])." <br><br> Description: ".$sample_detail['f2_description']."  </td>
								  
								 <td>Date: ".dateFormat(4,$sample_detail['f3_date'])." <br><br> Description: ".$sample_detail['f3_description']."  </td>
                               
                                 <td>".$sample_detail['deal']."</td></tr>
								 
                                </tbody>
                          </table>
                        </div>
                      </div>";
					  
					  return $html;
			
		}
			
}
?>