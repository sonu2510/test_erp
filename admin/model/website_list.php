<?php
// (mansi)
class website_list extends dbclass{
		// searching weblist
		public function getTotalwebsite($filter_data){
		
			$sql = "SELECT DISTINCT(domain_name) FROM domain_data WHERE is_delete='0'";
			//echo $sql;
			
			if(!empty($filter_data)){
				if(!empty($filter_data['domain_name'])){
				$sql .= " AND domain_name LIKE '%".$filter_data['domain_name']."%' ";
				}
			}
				
				//echo $sql;
				$data = $this->query($sql);
				 if($data->num_rows){
					  return  $data->num_rows;
				 }	 	
				 else{		 					
					  return false;
				 }	

	}
	
		public function getweb($data,$filter_data){

			$sql = "SELECT MAX(date_added) as last_enquiry_date , count(domain_data_id) as total,domain_name FROM domain_data WHERE is_delete='0'";

			// echo $sql;						
			if(!empty($filter_data)){
				if(!empty($filter_data['domain_name'])){
				$sql .= " AND domain_name LIKE '%".$filter_data['domain_name']."%' ";
				}
			}
			//$sql .=' GROUP BY domain_data_id ';
			$sql.=' GROUP BY domain_name ';
			if (isset($data['sort'])) {
					$sql .= " ORDER BY ". $data['sort'];	
				} else {
					$sql .= " ORDER BY last_enquiry_date";	
				}
		
				if (isset($data['order']) && ($data['order'] == 'DESC')) {
					$sql .= " DESC";
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
				 if($data->num_rows){
					  return  $data->rows;
				 }	 	
				 else{		 					
					  return false;
				 }		 
	}

		//searching product name
		public function getTotalproduct($filter_data,$domain_name){
		//printr($filter_array);
			$sql3 = "SELECT * FROM domain_data WHERE is_delete = 0 AND domain_name='".$domain_name."'  ";
		//echo $sql;die; 
				if(!empty($filter_data)){
					if(!empty($filter_data['product_name'])){
					$sql3 .= " AND product_name LIKE '%".$filter_data['product_name']."%' ";
					}
				}
				if(!empty($filter_data)){
					if(!empty($filter_data['date_added'])){
					$sql3 .= " AND date_added  = '".$filter_data['date_added']."' ";
					}
				} 
				//echo $sql3;
				$data = $this->query($sql3);
				if($data->num_rows){
					return $data->num_rows;
				}else{
					return false;
				}
	}
	
		public function getProduct($data,$filter_data,$domain_name){
		
			$sql3 = "SELECT * FROM domain_data WHERE is_delete = 0 AND domain_name='".$domain_name."' ";
			//echo $sql3;//die;
			
				
				if(!empty($filter_data)){
					if(!empty($filter_data['product_name'])){
						$sql3 .= " AND product_name LIKE '%".$filter_data['product_name']."%' ";
					}
					if(!empty($filter_data['date_added'])){
						$sql3 .= " AND date_added = '".$filter_data['date_added']."' ";
					}
				}
				 
				if (isset($data['sort'])) {
					$sql3 .= " ORDER BY " . $data['sort'];	
				} else {
					$sql3 .= " ORDER BY domain_data_id";	
				}
		
				if (isset($data['order']) && ($data['order'] == 'DESC')) {
					$sql3 .= " DESC";
				} else {
					$sql3 .= " DESC";
				}
				
				if (isset($data['start']) || isset($data['limit'])) {
					if ($data['start'] < 0) {
						$data['start'] = 0;
				}			
		
					if ($data['limit'] < 1) {
						$data['limit'] = 20;
				}	
		
					$sql3 .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
				}	
				
				//echo $sql3;
			
				$data = $this->query($sql3);
				if($data->num_rows){
					return $data->rows;
				}else{
					return false;
				}
	}
	
		public function getAddWeb($domain_data_id){
		
				$sql = "SELECT *  FROM domain_data WHERE is_delete = 0 AND domain_data_id='".$domain_data_id."'  ";
				
				$data = $this->query($sql);
				if($data->num_rows){
					return $data->row;
				}else{
					return false;
				}
	}
	
		public function updateStatus($post){
		
			$sql = "UPDATE domain_data SET is_delete = '1', date_modify = NOW() WHERE domain_data_id IN (" .implode(",",$post). ")";
			$this->query($sql);
		
	}
	
	// view part of website list master [mansi]
		public function view_webList($product_list,$view_btn=''){
		//printr($product_list);
		$html = "";
		
		 $html .= "<div class='table-responsive'>
            <table class='table table-striped b-t text-small'>
            	<thead>
              	<tr>";
			    	if($view_btn=='1')
					{
                	 	$html .=" <th width='20'><input type='checkbox'></th>";
					}
					else
					{
						$html .=" <th> Domain Name </th>";
					}
					
          $html .=" <th> Enquiry Date </th>
                	<th> Product Name </th>
                    <th> Name </th>
                    <th> Company Name </th>
                    <th> Email </th>
                    <th> Phone Number </th>
                    <th> Enquiry Come From </th>
					 ";
					if($view_btn=='1')
					{
						 $html.="<th> </th>";
					}
					if($view_btn !='1')
					{
						 $html.="  <th> Address </th>
						  			 <th> Country </th>
						  			 <th> Weight </th>
						   			 <th> Number of Bags </th>
									 <th> Message </th>
									 <th> </th>";
					}
					
             $html .="	</tr>
                </thead> 
              	<tbody>";
						    $i=1;
							
				foreach($product_list as $product){
						   //printr($product);
         				
                      $html .= " <tr>";
					  
					    if($view_btn=='1')
						{
                        	$html .= "<td> <input type='checkbox' name='post[]' value=".$product['domain_data_id']."> </td>";
						}
						else
						{
							$html .= "<td> ".$product['domain_name']." </td>";
						}
						  $html .="	<td> ".dateFormat(4,$product['date_added'])." </td>
									<td> ".$product['product_name']." </td>
									<td> ".$product['name']." </td>
									<td> ".$product['company_name']." </td>
									<td> ".$product['email']." </td>
									<td> ".$product['phone_no']." </td>
									<td> ".$product['referer_url']." </td>";
									
						if($view_btn=='1')
						{
						
						 	$html .= " <td> <a class='btn btn-success' name='view' id='view' href=".HTTP_SERVER."admin/index.php?route=website_list&mod=add&domain_data_id=".encode($product['domain_data_id'])."> View </a> </td>";
						
						}
						else
						{
							 $html .="	<td> ".$product['address']." </td>
									    <td> ".$product['country']." </td>
							  	        <td> ".$product['weight']." </td>
							        	<td> ".$product['number_bags']." </td>
									    <td> ".$product['message']." </td>";
						
						}
						    $html .= " </tr>";	
				 }
						 
                    $html .=" </tbody>  
                 
			 </table>";
							 
					 return $html;
	}	
	public function send_email_enq($post,$email_id)
	{
		//printr($post);die;
		
		foreach($post as $data)
		{
				
				$html = "";
				$data_email = $this->getAddWeb($data);
				$url = preg_replace('#^www\.(.+\.)#i', '$1', $data_email['domain_name'])." - " .date("d/m/y")." [ ".$data_email['name']." ]";
				$subject = "Inquiry from ".$url;
				$html .= "<div class='table-responsive'>
							<table class='table table-striped b-t text-small'>
									<tr>
										<td colspan='2'>You have got the Inquiry from<br></td>
									</tr>
									<tr> 
										<td>Name : </td>
										<td>".$data_email['name']."</td>
									</tr>
									<tr> 
										<td>Company Name :</td>
										<td>".$data_email['company_name']."</td>
									</tr>
									<tr> 
										<td>Address: </td>
										<td>".$data_email['address']."</td>
									</tr>
									<tr> 
										<td>Country: </td>
										<td>".$data_email['country']."</td>
									</tr>
									<tr> 
										<td>Phone no: </td>
										<td>".$data_email['phone_no']."</td>
									</tr>
									<tr> 
										<td>Email: </td>
										<td>".$data_email['email']."</td>
									</tr>";
									if(isset($data_email['product_name']) && isset($data_email['weight']) && isset($data_email['number_bags']))
									{
										$html .= "<tr> 
													<td>Name of the product to be filled inside the bags: </td>
													<td>".$data_email['product_name']."</td>
												</tr>
												<tr> 
													<td>Weight to be filled in each bags:</td>
													<td>".$data_email['weight']."</td>
												</tr>
												<tr> 
													<td>Number of bags/rolls required: </td>
													<td>".$data_email['number_bags']."</td>
												</tr>";
									}
									
								$html .= "
										<tr>
											<td>Remarks / Requirements  :</td>
											<td>".$data_email['message']."</td>
										</tr>
										</table>
						</div>";
				
				//echo $html;die;
			send_email($email_id,'info@swissonline.in',$subject,$html,'');
				
		}
	}
}
?>