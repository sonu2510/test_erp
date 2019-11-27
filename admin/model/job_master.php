<?php
//add by sonu
class job extends dbclass {

    public function addJob($data,$file) {
		
//	printr($data);die;
      
		if(!isset($data['user_details'])) 
		  	$data['user_details']='0';
			
        $sql = "INSERT INTO job_master SET job_no='".$data['job_no']."',job_name = '" . $data['jobname'] . "',product_name = '" . $data['product_name'] . "',pouch_type = '" . $data['pouch_type'] . "',job_date = '" . $data['job_date'] . "',country_id = '" . $data['country_id']. "',user_details = '" . $data['user_details']."',product='".$data['product']."', size_pro = '" . $data['size_pro']."',width = '" . $data['width']."',height = '" . $data['height']."',gusset = '" . $data['gusset']."',layers = '" . $data['layers']."',m_process = '" . $data['m_process']."',no_of_pouch = '" . $data['no_of_pouch']."',no_of_pouch_kg = '" . $data['no_of_pouch_kg']."',sealing = '" . $data['sealing']."',status = '" . $data['status']."', date_added = NOW(),date_modify = NOW(),is_delete=0,user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."',user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
        
		$this->query($sql);
		$job_id = $this->getLastId();
	
		if(isset($data['material']) && !empty($data['material'])){
			$i=1;
			foreach($data['material'] as $material){
			//	printr($material['material_id']);die;
			//	printr($material);die;
				$this->query("INSERT INTO " . DB_PREFIX . " job_layer_details  SET 	job_id = '".$job_id."',layer_id='".$i."', product_item_layer_id = '" .$material['material_id']. "', film_size = '" .$material['film_size']. "',date_modify = NOW(),date_added = NOW(),is_delete=0,status='0'");
				$i++;
			}
		}
		if(isset($file) && !empty($file)){
			
			foreach($file as $key=>$file_name){
					if($file_name!=""){
						$ext = pathinfo($file_name, PATHINFO_EXTENSION);
						if($ext == 'pdf' || $ext == 'jpg' || $ext == 'png' ){
								$this->query("INSERT INTO " . DB_PREFIX . " job_dieline_details  SET job_id = '".$job_id."',job_name='".$file_name."', date_added = NOW(),is_delete=0");
						}
					}
			}
		}
     
        if($data) {
            return $data;
        } else {
            return false;
        } 
	
    }

	public function updateJob($data,$file){
		
	//	printr($file);die;
		if(!isset($data['user_details']))
		  	$data['user_details']='0';
		
		 $sql = "UPDATE job_master SET job_no='".$data['job_no']."', job_name = '" . $data['jobname'] . "', product_name = '" . $data['product_name'] . "',pouch_type = '" . $data['pouch_type'] . "',job_date = '" . $data['job_date'] . "',country_id = '" . $data['country_id']. "',user_details = '" . $data['user_details']."',product='".$data['product']."', size_pro = '" . $data['size_pro']."',width = '" . $data['width']."',height = '" . $data['height']."',gusset = '" . $data['gusset']."',m_process = '" . $data['m_process']."',printing_option = '" . $data['printing_option']."',layers = '" . $data['layers']."',sealing = '" . $data['sealing']."',no_of_pouch = '" . $data['no_of_pouch']."',no_of_pouch_kg = '" . $data['no_of_pouch_kg']."',status = '" . $data['status']."', date_added = NOW(),date_modify = NOW(),is_delete=0  WHERE job_id = '" .$data['jobid']. "'";
		 
		 if(isset($data['material']) && !empty($data['material'])){
			$this->query("DELETE FROM " . DB_PREFIX . "job_layer_details WHERE job_id = '" .$data['jobid']. "'");
			$i=1;
			
			foreach($data['material'] as $material){
				
				$this->query("INSERT INTO " . DB_PREFIX . " job_layer_details  SET 	job_id = '" .$data['jobid']. "',layer_id='".$i."', product_item_layer_id = '" .$material['material_id']. "', film_size = '" .$material['film_size']. "', date_modify = NOW(),date_added = NOW(),is_delete=0,status='0'");
				$i++;
			}
		}
		if(isset($file) && !empty($file)){

			//$this->query("DELETE FROM " . DB_PREFIX . "job_dieline_details WHERE job_id = '" .$data['jobid']. "'");
			
		//	printr($file);die;
			foreach($file as $key=>$file_name){
				//printr($file_name);
				if($file_name!=""){
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					if($ext == 'pdf' || $ext == 'jpg' || $ext == 'png' ){
			
					$this->query("INSERT INTO " . DB_PREFIX . " job_dieline_details  SET job_id = '".$data['jobid']."',job_name='".$file_name."', date_added = NOW(),is_delete=0");
					}
			
				}
			}
		}
		
	
		$this->query($sql);		
	}
	
    public function getTotalJob($filter_data = array()) {
       // $sql = "SELECT COUNT(*) as total FROM job_master as j,product as p,country as c WHERE j.is_delete = 0 AND j.country_id=c.country_id AND p.product_id=j.product";
        $sql = "SELECT COUNT(*) as total FROM job_master WHERE is_delete = 0  ";

        if (!empty($filter_data)) {
            if (!empty($filter_data['job_name'])) {
                $sql .= " AND job_name LIKE '%" . $filter_data['job_name'] . "%' ";
            }
  		if (!empty($filter_data['job_no'])) {
                $sql .= " AND job_no LIKE '%" . $filter_data['job_no'] . "%' ";
            }

            if ($filter_data['status'] != '') {
                $sql .= " AND status = '" . $filter_data['status'] . "' ";
            }
			if($filter_data['product'] != ''){
				$sql .= " AND product = '".$filter_data['product']."' "; 	
			}
        }
        $data = $this->query($sql);
        return $data->row['total'];
    }

	
	public function getAllJobs($data,$filter_data=array()){
		//$sql = "SELECT * FROM  job_master as j,product as p,country as c WHERE j.is_delete = 0 AND j.country_id=c.country_id AND p.product_id=j.product";
			$sql = "SELECT * FROM  job_master WHERE is_delete = 0  ";
		if(!empty($filter_data)){
			 if (!empty($filter_data['job_name'])) {
                $sql .= " AND job_name LIKE '%" . $filter_data['job_name'] . "%' ";
            }
  			if (!empty($filter_data['job_no'])) {
                $sql .= " AND job_no LIKE '%" . $filter_data['job_no'] . "%' ";
            }
			
			if($filter_data['status'] != ''){
				$sql .= " AND status = '".$filter_data['status']."' "; 	
			}
			if($filter_data['product'] != ''){
				$sql .= " AND product = '".$filter_data['product']."' "; 	
			}
		
		}
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY job_id";	
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

	//	echo $sql;die;
	
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	
	public function getJob($job_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "job_master` WHERE is_delete = 0 AND job_id = '".$job_id."'";
		//echo $sql;die;
		$data = $this->query($sql);
		//printr($data);
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}public function getJobDieline($job_id){
		$sql = "SELECT * FROM job_dieline_details WHERE is_delete = 0 AND job_id = '".$job_id."'";
		//ssecho $sql;die;
		$data = $this->query($sql);
		//printr($data);
		
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getJobDielineDetails($job_dieline_id){
		$sql = "SELECT * FROM job_dieline_details WHERE is_delete = 0 AND job_dieline_id = '".$job_dieline_id."'";
		//echo $sql;die;
		$data = $this->query($sql);
		//printr($data);
		
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function DeleteJobDieline($job_dieline_id)
	{
		$sql = "DELETE FROM job_dieline_details WHERE job_dieline_id = '".$job_dieline_id."'";
		//echo $sql;
		$data = $this->query($sql);
	}

	public function updateJobStatus($id,$status){
		$sql = "UPDATE `" . DB_PREFIX . "job_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE job_id = '".$id."' ";
		$this->query($sql);
	}
		
	public function updateStatus($status,$data){
		if($status == 0 || $status == 1){
			$sql = "UPDATE `" . DB_PREFIX . "job_master` SET status = '" .(int)$status. "',  date_modify = NOW() WHERE job_id IN (" .implode(",",$data). ")";
			//echo $sql;die;
			$this->query($sql);
		}elseif($status == 2){
			$sql = "UPDATE `" . DB_PREFIX . "job_master` SET is_delete = '1', date_modify = NOW() WHERE job_id IN (" .implode(",",$data). ")";
			$this->query($sql);
		}
	}

	
	
	public function getActiveProduct(){
		//[kinjal] : add 24 id to hide ultra product in product selection (17-5-2016)
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status='1' AND 	production_status='1' AND is_delete = '0' AND product_id NOT IN(11,12,13,14,15,16,24,27,26,28,30,31) ";
		$sql .= " ORDER BY product_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}	
	
	public function getProductSize($product_id)
	{
		
		
		$sql  = "SELECT * FROM size_master WHERE product_id = '".$product_id."'  GROUP BY width ORDER BY  width ASC"; 	
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
	
		public function getUser($user_id,$user_type_id)

		{	//echo $user_type_id;

			if($user_type_id == 1){

				$sql = "SELECT ib.company_address,ib.bank_address,u.user_name, co.country_id,co.country_name, u.first_name, u.last_name, ad.address, ad.city, ad.state, ad.postcode, CONCAT(u.first_name,' ',u.last_name) as name, u.telephone, acc.email FROM " . DB_PREFIX ."user u LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=u.address_id AND ad.user_type_id = '1' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=u.user_name) LEFT JOIN international_branch as ib ON u.user_id=ib.international_branch_id WHERE u.user_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";

				//echo $sql;

			}elseif($user_type_id == 2){

				$sql = "SELECT e.user_name,co.country_id, co.country_name, e.first_name, e.last_name, e.employee_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(e.first_name,' ',e.last_name) as name, e.telephone, acc.email,e.user_id,e.user_type_id,ib.gst,ib.company_address,ib.bank_address FROM " . DB_PREFIX ."employee e LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=e.address_id AND ad.user_type_id = '2' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=e.user_name) LEFT JOIN international_branch as ib ON e.user_id=ib.international_branch_id WHERE e.employee_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";

				

			}elseif($user_type_id == 4){

				$sql = "SELECT ib.user_name,co.country_id,ib.gst,ib.company_address,ib.bank_address, co.country_name, ib.first_name, ib.last_name, ib.international_branch_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(ib.first_name,' ',ib.last_name) as name, ib.telephone, acc.email FROM " . DB_PREFIX ."international_branch ib LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=ib.address_id AND ad.user_type_id = '4' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=ib.user_name) WHERE ib.international_branch_id = '".(int)$user_id."' AND ad.address_type_id = '0' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."'";

			}else{

				

				$sql = "SELECT co.country_name,co.country_id, c.first_name, c.last_name, c.client_id, ad.address, ad.city, ad.state, ad.postcode, CONCAT(c.first_name,' ',c.last_name) as name, c.telephone, acc.email FROM " . DB_PREFIX ."client c LEFT JOIN " . DB_PREFIX ."address ad ON(ad.address_id=c.address_id AND ad.user_type_id = '3' AND ad.user_id = '".(int)$user_id."') LEFT JOIN " . DB_PREFIX ."country co ON(co.country_id=ad.country_id) LEFT JOIN " . DB_PREFIX ."account_master acc ON(acc.user_name=c.user_name) WHERE c.client_id = '".(int)$user_id."' AND acc.user_type_id ='".(int)$user_type_id."' AND acc.user_id = '".(int)$user_id."' ";

			}

			$data = $this->query($sql);

			return $data->row;

		}
	
	
	public function getActivePrintingEffect(){
		$sql = "SELECT * FROM `" . DB_PREFIX . "printing_effect` WHERE status='1' AND is_delete = '0' ";
		$sql .= " ORDER BY effect_name";	
		$sql .= " ASC";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getMaterialName($product_item_id){
		$sql = "SELECT product_name FROM `" . DB_PREFIX . "product_item_info` WHERE product_item_id = '".(int)$product_item_id."' AND is_delete=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['product_name'];
		}else{
			return false;
		}
	}
	
	public function getLayerMakeMaterial($layer){
		//		$sql = "SELECT material_id FROM `" . DB_PREFIX . "product_layer_material` WHERE layer_id = '".(int)$layer."' ";
		
		$sql ="SELECT product_item_id  FROM `production_layer_material`  WHERE layer_id = '".(int)$layer."'  ";
		//printr($sql);die;
			$data = $this->query($sql);
				if($data->num_rows){
					$array = array();
					foreach($data->rows as $data){
						$array[] = array(
							'material_id'	=> $data['product_item_id'],
							'material_name'  => $this->getMaterialName($data['product_item_id'])
						);
					}
					$sortArray = sortAssociateArrayByKey($array,'material_name',SORT_ASC);
					return $sortArray ;
				}else{
					return false;
				}
	}
	
	
	public function getProductionProcess()
	{
		$sql="SELECT * FROM production_process WHERE is_delete=0 AND status=1 AND( production_process_id='2' OR production_process_id='3')";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	function get_country() {
        $sql = "SELECT * from country WHERE is_delete ='0' ORDER BY country_id ASC";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->rows;
        } else {
            return false;
        }
	}
	
	function get_user_details($country_id){
		$sql = "SELECT * from address as a , employee as e WHERE is_delete ='0' AND  a.country_id ='".$country_id."'  AND a.user_id=e.employee_id AND e.user_id ='6' AND  a.user_type_id ='2'";
			$data = $this->query($sql);
			//printr($data);
			if ($data->num_rows) {
				return $data->rows; 
			} else {
				return false;
			}
	}
	
	
	public function getLayerMakeMaterialDetails($job_id){
		$sql ="SELECT * FROM product_item_info as p, job_layer_details as j WHERE j.job_id = '".$job_id."' AND p.product_item_id =j.product_item_layer_id";
		$data = $this->query($sql);
		//	printr($sql);die;
			if ($data->num_rows) {
				return $data->rows;
			} else {
				return false;
			}
	}
	public function getLayerDetails($job_layer_id,$layer_id){
		$sql ="SELECT * FROM  job_layer_details as j ,product_item_info as p WHERE j.job_id='".$job_layer_id."' AND j.layer_id ='".$layer_id."' AND p.product_item_id =j.product_item_layer_id " ;
		//echo $sql;
		$data = $this->query($sql);
			//printr($data);
			if ($data->num_rows) {
				return $data->row;
			} else {
				return false;
			}
	}
	
	public function generatePackingNumber(){

			$data = $this->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'job_master'");

			$count = $data->row['AUTO_INCREMENT'];

	//		$strpad = str_pad($count,5,'0',STR_PAD_LEFT);

			return $count; 

		}
		
		
	function get_country_name($country_id) {
	   // printr($country_id.'hiii');
        $sql = "SELECT country_name from country WHERE is_delete ='0' AND country_id = '".$country_id."'  AND country_id <> 0 ";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['country_name'];
        } else {
            return false;
        }
	}
	function get_product_name($product_id) {
        $sql = "SELECT product_name from product WHERE is_delete ='0' AND product_id = '".$product_id."' ";
        $data = $this->query($sql);
        if ($data->num_rows) {
            return $data->row['product_name'];
        } else {
            return false;
        }
	}
	public function getCountryName($country_id){

		$sql= "SELECT * FROM country  WHERE  country_id='".$country_id."'";
		//echo $sql;
		$data = $this->query($sql);
//	printr($data );
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}


		
	}
    public function getSize($size_id){
		$data = $this->query("SELECT gusset,height,width,volume FROM " . DB_PREFIX ." size_master  WHERE size_master_id = '".(int)$size_id."' AND status=0 LIMIT 1");
	   // printr($data);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function viewjob_details($job_id)
	{
		 
		$job_details = $this->getJob($job_id);

		$job_material = $this->getLayerMakeMaterialDetails($job_id);
	     $country=$this->getCountryName($job_details['country_id']);
	   	if($country!=''){
	   	    $country_name=$country['country_name'];
		}else{$country_name	='';	}
		 
			$size = $this->getSize($job_details['size_pro']);
    //	printr($size);
    	//printr($job_details);
            $width=$height=$gusset=0;
                	if(!empty($size)){
                	        $volume='('.$size['volume'].')';
                	        $width  =$size['width'];
                	        $height=$size['height'];
                	        $gusset=$size['gusset'];
            	    }else{
            	        $volume="";
            	          $width  =$job_details['width'];
            	          $height=$job_details['height'];
            	          $gusset=$job_details['gusset'];
            	    }  
		
	    
	   	$html='';
	   	
	   	$h='';
	   	$h.='<div class="panel-body">
	   	
	   	        
						<table cellspacing="0px" cellpadding="1px" border="1" style=" width:100%;">
								<tbody >
								<tr  style="font-size: 22px;margin-top: 15px;background-color: #9d9898;margin-bottom: -10px;">
									<td  colspan="2" class="text-white"><b><center> JOB CARD </center></b>  </td>
								
								</tr>
								<tr  style="font-size: 22px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="2"><b><center> JOB NO:- '.$job_details['job_no'].'</center></b> </td>
								
								</tr>
								<tr  style="font-size: 16px;">
									<td ><b>  Job Name:</b> </td>
									<td ><b>'.$job_details['job_name'].'</b></td>
								</tr>
								<tr style="font-size: 16px;">
						        	<td > <b>  Country Name:</b></td>
						        	<td><b> '.$country_name.'</b></td>
					        	</tr>
					        	<tr style="font-size: 16px;">
									<td > <b>  Job Size</b> </td>
									<td ><b>'.$width.'x'.$height.'x'.$gusset.''.$volume.'</b></td>
								</tr><br>
								<tr >
    									<td><b>  Cylinder Length</b> </td>
    									<td> <b>'.$job_details['cylinder_length'].'</b></td>
    
    							</tr>
    							<tr>
    									<td> <b>  Cylinder Cir-Cum:</b> </td>
    									<td><b>'.$job_details['cylinder_cir_cum'].'</b></td>
    
    					    	</tr>
    					    	<tr>
											<td>  <b> Job Color:</b> </td>
											<td><b>'.$job_details['job_color'].'</b></td>

								</tr>
								<tr>
											<td>  <b>  Material Size & Microns</b> </a></td>
											<td><b>'.$job_material[0]['film_size'].'</b></td>

								</tr>
						';
							
							  foreach($job_material as $material){
                    //  printr($material);style="background-color: #f7ddc6;"
                       
                     if($material['layer_id']=='1'){
                        
                         $h.='<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="2" ><b><center>PRINTING MATERIAL SPECIFICATION</center></b> </td>
								
							</tr>';
                 }   else if($material['layer_id']=='2'){
                        $h.='<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="2"><b><center>PRINTED/PLAIN MATERIAL SPECIFICATION(Second Layer)</center></b> </td>
								
							</tr>';
                  }  else if($material['layer_id']=='3'){
                        $h.='<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="2" ><b><center>LAMINATION MATERIAL SPECIFICATION(Third Layer)</center></b> </td>
								
							</tr>';
                       
                    }else if($material['layer_id']=='4'){
                        $h.='<tr  style="font-size: 20px;margin-top: 15px;margin-bottom: -10px;">
									<td  colspan="2" ><b><center>LAMINATION MATERIAL SPECIFICATION(Fourth Layer) </center></b> </td>
								
							</tr>';
                         
                    } 
                  $h.='  	<tr>  
                  
    									<td ><b>MATERIAL NAME: '.$material['product_name'].'  </b></td>
    									<td ><b>Thickness: '.$material['product_thickness'].'<b>   	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  GSM: '.$material['product_gsm'].'</b>    </td>
    								
    
    							</tr>
    						
                         
                         ';
                  }
							
							
				$h.=' </tbody>
                          </table>';			
							
					$h.='

                            </div>';
	  /*	$html='<div class="col-lg-12">
               <section class="panel">
                  <header class="panel-heading bg bg-inverse"  style="font-size: 20px;"><b><center> JOB CARD</center></b> </header>
                  <div class="list-group"> 
                  <table>
                  <tbody>
                  <tr><a href="#" class="list-group-item"> <i class="fa fa-fw fa-star"></i><b>  Job Name:</b> '.$job_details['job_name'].'</a><br></tr>
                   <tr> <a href="#" class="list-group-item bg-lighter">  <i class="fa fa-fw fa-star"></i><b>  Country Name:</b> '.$country_name.'</a><br></tr>
                    <tr><a href="#" class="list-group-item"><i class="fa fa-fw fa-star"></i> <b> Job Size</b> '.$width.'x'.$height.'x'.$gusset.''.$volume.'</a><br></tr>
                   <tr><a href="#" class="list-group-item bg-lighter"> </i><i class="fa fa-fw fa-star"></i><b> Cylinder Length</b> '.$job_details['cylinder_length'].' </a><br></tr>
                   <tr><a href="#" class="list-group-item"> <i class="fa fa-fw fa-star"></i> <b> Cylinder Cir-Cum:</b> '.$job_details['cylinder_cir_cum'].'</a><br></tr>
                    <tr><a href="#" class="list-group-item bg-lighter"> <i class="fa fa-fw fa-star"></i> <b> Job Color:</b> '.$job_details['job_color'].' </a><br> </tr>
                    <tr><a href="#" class="list-group-item"> <i class="fa fa-fw fa-star"></i> <b> Material Size & Microns</b> '.$job_material[0]['film_size'].' </a><br> </tr>
                  </tbody>
                  </table>
                  </div>
                  ';
                  foreach($job_material as $material){
                    //  printr($material);
                      
                     if($material['layer_id']=='1')
                         $html.='<header style="background-color: #f7ddc6;" ><b><center>PRINTING MATERIAL SPECIFICATION</center></b></header>';
                    else if($material['layer_id']=='2')
                         $html.=' <header style="background-color: #f7ddc6;" ><b><center>PRINTED/PLAIN MATERIAL SPECIFICATION(Second Layer)</center></b> </header>';
                    else if($material['layer_id']=='3')
                         $html.='<header style="background-color: #f7ddc6;" ><b><center>LAMINATION MATERIAL SPECIFICATION(Third Layer) </center></b> </header>';
                    else if($material['layer_id']=='4')
                         $html.='<header style="background-color: #f7ddc6;" ><b><center>LAMINATION MATERIAL SPECIFICATION(Fourth Layer)  </center></b> </header>';
                        
                  $html.='<div class="list-group">
                            <table>
                          <tbody>
                          <tr>
                             <a href="#" class="list-group-item"> <i class="fa fa-fw fa-star"></i><b>MATERIAL NAME:</b> '.$material['product_name'].' : <b>Thickness: </b>'.$material['product_thickness'].'</a>
                          </tr>
                          </tbody>
                          </table>
                          </div>';
                  }
                  
                $html.=' 
               </section>
            </div>
    	   ';
	
		$html.='</form>';
*/			return $h;
	
		}
	
}



?>