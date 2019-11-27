<?php //sonu 
class quality_report extends dbclass{
	
	public function addData($data){
		
	//	printr($data);die;
		$sec_total_std_gsm = isset($data['sec_total_std_gsm']) ? $data['sec_total_std_gsm'] :'';
		$sec_total_min_gsm = isset($data['sec_total_min_gsm']) ? $data['sec_total_min_gsm'] :'';
		$sec_total_max_gsm = isset($data['sec_total_max_gsm']) ? $data['sec_total_max_gsm'] :'';
		
		$sql = "INSERT INTO " . DB_PREFIX . "qc_report SET product_id = '".(int)$data['product_id']."',zipper = '".(int)$data['zipper']."',category_id = '" .(int)$data['category_id']. "',color_id='".$data['color_id']."',valve = '".$data['valve']."',size_id = '".$data['size']."',total_std_gsm='".$data['total_std_gsm']."',total_min_gsm='".$data['total_min_gsm']."',total_max_gsm='".$data['total_max_gsm']."',sec_total_std_gsm='".$sec_total_std_gsm."',sec_total_min_gsm='".$sec_total_min_gsm."',sec_total_max_gsm='".$sec_total_max_gsm."', registration = '" .$data['registration']. "', shade = '" . $data['shade']. "', delamination_test = '" .$data['delamination_test']. "', 	pouch_length = '" .$data['pouch_length']. "', pouch_width = '" .$data['pouch_width']. "', gusset_pos = '" .$data['gusset']. "', sealing_area = '" .$data['sealing_area']. "', v_notch = '" .$data['v_notch']. "', zipper_width = '" .$data['zipper_width']. "',  zipper_position = '" .$data['zipper_position']. "', pouch_weight = '" .$data['pouch_weight']. "', sealing_strength = '" .$data['sealing_strength']. "', bond_strength_1 = '" .$data['bond_strength_1']. "',bond_strength_2 = '" .$data['bond_strength_2']. "',bond_strength_3 = '" .$data['bond_strength_3']. "',bond_strength_4 = '" .$data['bond_strength_4']. "', odour_test = '" .$data['odour_test']. "', drop_test = '" .$data['drop_test']. "', bursting_strength = '" .$data['bursting_srength']. "', otr = '" .$data['otr']. "', wvtr = '" .$data['wvtr']. "',leakage_test = '" .$data['leakage_test']. "',status=1, date_added = NOW(),date_modify = NOW(),is_delete=0,user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', user_type_id='".$_SESSION['LOGIN_USER_TYPE']."'";
		
		$this->query($sql);
				$qc_report_id= $this->getLastId();

						foreach($data['material'] as $key=>$material)
						{
					
					       $sec_material_id = isset($data['clearprintr'][$key]['sec_material_id']) ? $data['clearprintr'][$key]['sec_material_id'] :'';
					       $sec_thickness = isset($data['clearprintr'][$key]['sec_thickness']) ? $data['clearprintr'][$key]['sec_thickness'] :'';
					       $sec_std_gsm = isset($data['clearprintr'][$key]['sec_std_gsm']) ? $data['clearprintr'][$key]['sec_std_gsm'] :'';
					       $sec_min_gsm = isset($data['clearprintr'][$key]['sec_min_gsm']) ? $data['clearprintr'][$key]['sec_min_gsm'] :'';
					       $sec_max_gsm = isset($data['clearprintr'][$key]['sec_max_gsm']) ? $data['clearprintr'][$key]['sec_max_gsm'] :'';

						   $this->query("INSERT INTO  qc_material_gsm SET qc_report_id ='".$qc_report_id."',material_id ='".$material['material_id']."',thickness_id ='".$material['thickness']."',sec_material_id ='".$sec_material_id."',sec_thickness_id ='".$sec_thickness."',std_gsm ='".$material['std_gsm']."',min_gsm ='".$material['min_gsm']."',max_gsm ='".$material['max_gsm']."',sec_std_gsm ='".$sec_std_gsm."',sec_min_gsm ='".$sec_min_gsm."',sec_max_gsm ='".$sec_max_gsm."', status=1,date_added = NOW(),date_modify = NOW(),is_delete=0");
							
						
						}

      
		return $qc_report_id;
	}
    public function updateData($qc_report_id,$data){
		$sec_total_std_gsm = isset($data['sec_total_std_gsm']) ? $data['sec_total_std_gsm'] :'';
		$sec_total_min_gsm = isset($data['sec_total_min_gsm']) ? $data['sec_total_min_gsm'] :'';
		$sec_total_max_gsm = isset($data['sec_total_max_gsm']) ? $data['sec_total_max_gsm'] :'';
		
		$sql = "UPDATE " . DB_PREFIX . "qc_report SET product_id = '".(int)$data['product_id']."',zipper = '".(int)$data['zipper']."',category_id = '" .(int)$data['category_id']. "',color_id='".$data['color_id']."',valve = '".$data['valve']."',size_id = '".$data['size']."',total_std_gsm='".$data['total_std_gsm']."',total_min_gsm='".$data['total_min_gsm']."',total_max_gsm='".$data['total_max_gsm']."',sec_total_std_gsm='".$sec_total_std_gsm."',sec_total_min_gsm='".$sec_total_min_gsm."',sec_total_max_gsm='".$sec_total_max_gsm."', registration = '" .$data['registration']. "', shade = '" . $data['shade']. "', delamination_test = '" .$data['delamination_test']. "', 	pouch_length = '" .$data['pouch_length']. "', pouch_width = '" .$data['pouch_width']. "', gusset_pos = '" .$data['gusset']. "', sealing_area = '" .$data['sealing_area']. "', v_notch = '" .$data['v_notch']. "', zipper_width = '" .$data['zipper_width']. "',  zipper_position = '" .$data['zipper_position']. "', pouch_weight = '" .$data['pouch_weight']. "', sealing_strength = '" .$data['sealing_strength']. "', bond_strength_1 = '" .$data['bond_strength_1']. "',bond_strength_2 = '" .$data['bond_strength_2']. "',bond_strength_3 = '" .$data['bond_strength_3']. "',bond_strength_4 = '" .$data['bond_strength_4']. "', odour_test = '" .$data['odour_test']. "', drop_test = '" .$data['drop_test']. "', bursting_strength = '" .$data['bursting_srength']. "', otr = '" .$data['otr']. "', wvtr = '" .$data['wvtr']. "',leakage_test = '" .$data['leakage_test']. "',status=1, date_added = NOW(),date_modify = NOW(),is_delete=0,user_id='".$_SESSION['ADMIN_LOGIN_SWISS']."', user_type_id='".$_SESSION['LOGIN_USER_TYPE']."' WHERE qc_report_id='".$qc_report_id."'";
		
		$this->query($sql);
				

			foreach($data['material'] as $key=>$material)
			{
		
			   $sec_material_id = isset($data['clearprintr'][$key]['sec_material_id']) ? $data['clearprintr'][$key]['sec_material_id'] :'';
			   $sec_thickness = isset($data['clearprintr'][$key]['sec_thickness']) ? $data['clearprintr'][$key]['sec_thickness'] :'';
			   $sec_std_gsm = isset($data['clearprintr'][$key]['sec_std_gsm']) ? $data['clearprintr'][$key]['sec_std_gsm'] :'';
			   $sec_min_gsm = isset($data['clearprintr'][$key]['sec_min_gsm']) ? $data['clearprintr'][$key]['sec_min_gsm'] :'';
			   $sec_max_gsm = isset($data['clearprintr'][$key]['sec_max_gsm']) ? $data['clearprintr'][$key]['sec_max_gsm'] :'';
						   
				//if($material['qc_material_gsm_id'])
				$this->query("UPDATE   qc_material_gsm SET qc_report_id ='".$qc_report_id."',material_id ='".$material['material_id']."',thickness_id ='".$material['thickness']."',sec_material_id ='".$sec_material_id."',sec_thickness_id ='".$sec_thickness."',std_gsm ='".$material['std_gsm']."',min_gsm ='".$material['min_gsm']."',max_gsm ='".$material['max_gsm']."',sec_std_gsm ='".$sec_std_gsm."',sec_min_gsm ='".$sec_min_gsm."',sec_max_gsm ='".$sec_max_gsm."', status=1,date_added = NOW(),date_modify = NOW(),is_delete=0 WHERE qc_material_gsm_id='".$material['qc_material_gsm_id']."' ");
				//
			
			}

		//die;
		return $qc_report_id;
	}
	
	public function getSize($product_id,$zipper=''){
		if($zipper!=''){
			$zipper="AND s.product_zipper_id='".$zipper."'";
		}else{
			$zipper='';
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "size_master as s,product_zipper as pz  WHERE s.product_id = '" .(int)$product_id. "' AND s.product_zipper_id=pz.product_zipper_id $zipper";
		$data = $this->query($sql);

		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	
	public function getZipper(){
		$sql = "SELECT * FROM " . DB_PREFIX . "product_zipper WHERE is_delete = 0 ";
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
	public function getMaterialThickness($material_id){
		$sql = "SELECT * FROM product_material_thickness WHERE product_material_id = '".(int)$material_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getColor_category(){
		$sql = "SELECT * FROM " . DB_PREFIX . "report_color_category WHERE is_delete = 0 AND status='1'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}public function getColor_category_Color($category_id){
		$sql = "SELECT * FROM " . DB_PREFIX . "report_color_category WHERE is_delete = 0 AND status='1' AND category_id='".$category_id."'";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getColorName($pouch_color_id){
			$sql = "SELECT * FROM " . DB_PREFIX . "pouch_color WHERE is_delete = 0 AND status='1' AND pouch_color_id='".$pouch_color_id."'";
			$data = $this->query($sql);
			if($data->num_rows){
				return $data->row;
			}else{
				return false;
			}
		}

	
	public function getTotalActiveProducts(){
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		$data = $this->query($sql);
		return $data->row['total'];
	}
	
	public function gettools($data){
		$sql = "SELECT pp.*, p.product_name FROM " . DB_PREFIX . "size_master pp INNER JOIN " . DB_PREFIX . "product p ON(pp.product_id = p.product_id) ";
		
		if (isset($data['sort'])) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pp.size_id";	
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
	
	public function getProduct($product_id){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` p WHERE product_id='".$product_id."'";
		
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getLayerMakeMaterial($layer){
		//		$sql = "SELECT material_id FROM `" . DB_PREFIX . "product_layer_material` WHERE layer_id = '".(int)$layer."' ";

		$sql = "SELECT lm.material_id FROM `" . DB_PREFIX . "product_layer_material` AS lm ,`" . DB_PREFIX . "product_material_make` AS m WHERE layer_id = '".(int)$layer."' AND lm.material_id=m.material_id Group by lm.material_id";
		//echo $sql;
	$data = $this->query($sql);
		if($data->num_rows){
			$array = array();
			foreach($data->rows as $data){
				$array[] = array(
					'material_id'	=> $data['material_id'],
					'material_name'  => $this->getMaterialName($data['material_id'])
				);
			}
			$sortArray = sortAssociateArrayByKey($array,'material_name',SORT_ASC);
			return $sortArray ;
		}else{
			return false;
		}
	}
		public function getMaterialName($material_id){
		$sql = "SELECT material_name FROM `" . DB_PREFIX . "product_material` WHERE product_material_id = '".(int)$material_id."' AND is_delete=0 ";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row['material_name'];
		}else{
			return false;
		}
	}
	public function getProducts($data=array()){
		
		$sql = "SELECT * FROM `" . DB_PREFIX . "product` WHERE status=1 AND is_delete = 0";
		
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
	public function getAll_details($data=array()){
		
		$sql = "SELECT * FROM qc_report as q ,qc_material_gsm as g  WHERE  g.qc_report_id=q.qc_report_id	AND q.status=1 AND q.is_delete = 0";
		
	
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function checkProductZipper($product_id){
		$data = $this->query("SELECT zipper_available FROM " . DB_PREFIX ."product WHERE product_id='".$product_id."'");
		if($data->num_rows){
			return $data->row['zipper_available'];	
		}else{
			return false;
		}
	}
	public function getActiveProductZippersByTintie(){
		$data = $this->query("SELECT * FROM `" . DB_PREFIX . "product_zipper` WHERE status='1' AND is_delete = '0'  ORDER BY serial_no ASC");
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getActiveColor($category_id){

			$sql1="SELECT color FROM report_color_category WHERE category_id = '".$category_id."'";
			$data1 = $this->query($sql1);
			
			$sql = "SELECT * FROM " . DB_PREFIX . "pouch_color WHERE pouch_color_id IN (".$data1->row['color'].") ORDER BY color  ASC";
			$data = $this->query($sql);
			
			if($data->num_rows){

				return $data->rows;

			}else{

				return false;

			}

	}
	public function getQcDetail($product_id,$size_id,$category_id,$color_id)
	{
		$sql = 'SELECT * FROM qc_report WHERE product_id = "'.$product_id.'" AND size_id = "'.$size_id.'" AND category_id = "'.$category_id.'" AND color_id = "'.$color_id.'" ';
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function viewCOAreport($qc_report_id)
	{
		
		$sql = 'SELECT * FROM qc_report as qr,pouch_color as pc,product_zipper as pz,size_master as sm,product as p WHERE qc_report_id="'.$qc_report_id.'" AND qr.size_id = sm.size_master_id AND qr.zipper=pz.product_zipper_id AND qr.color_id = pc.pouch_color_id AND qr.product_id =p.product_id';
		$data = $this->query($sql);
		
		$sql1 = "SELECT * FROM qc_material_gsm as gm,product_material as pm WHERE gm.qc_report_id = '".$qc_report_id."' AND pm.product_material_id = gm.material_id";
		$data1 = $this->query($sql1);
       
        $category_id = $data->row['category_id'];
        
		$html = "";
		if($data->num_rows)
		{
				$html .= "<style type='text/css'>
							.center {
							  text-align: center;
							}
							.center table 
							{
							
							 margin: 0 auto;
							 text-align: left;
							}
							</style>
							<div class='form-group'>
								<div class='table-responsive'>
									<table class='table b-t text-small' border='0'>
										<tbody>
											<tr>
												  <td><center><img src='".HTTP_UPLOAD."admin/logo/swiss_logo.png' alt='Image'></center></td>
											</tr>
											<tr>
												  <td style='font-size:18px;'><center><u>Certificate of Analysis</u></center></td>
											</tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr>
												  <td>
												  <div class='center'>
													<table width='90%' class='center'>
														<tbody>
															<tr>
																<td width='45%'>PRODUCT NAME</td>
																<td width='45%'	>".strtoupper($data->row['color']." ".$data->row['abbrevation']." </br> ".$data->row['volume']." ".$data->row['zipper_name'])."</td>
															</tr>
														</tbody>
													</table>
													</div>
												  </td>
											</tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr>
												<td>
													<table width='100%'>
														<tbody>
															<tr>";
																$clear = $print = $gsm = '';
                                                            	if($category_id=='1')
                                                            	{
                                                            	    $clear = 'CLEAR SIDE';
                                                            	    $print = 'PRINT SIDE';
                                                            	    $gsm = '';
                                                            	}
                                                            	else if($category_id=='4')
                                                            	{
                                                            	    $clear = $gsm = 'CLEAR SIDE';
                                                            	    $print = 'PRINT SIDE';
                                                            	}
													$html .= "	<td><center>SUBSTRATES ".$clear."</center></td>";
													      if($category_id=='1' || $category_id=='4')
                                        				  {
                                            				  $html .= "<td><center>SUBSTRATES ".$print."</center></td>";
                                        				  }
                                        				  
														$html .= "<td><center>STD GSM ".$gsm."</center></td>";
														if($category_id=='4'){
														    $html .= "<td><center>STD GSM ".$print."</center></td>";}
														
														$html .= "<td><center>MIN GSM ".$gsm."</center></td>";
														if($category_id=='4'){
														    $html .= "<td><center>MIN GSM ".$print."</center></td>";}
														    
														$html .= "<td><center>MAX GSM ".$gsm."</center></td>";
														if($category_id=='4'){
														    $html .= "<td><center>MAX GSM ".$print."</center></td>";}
														    
													$html .= "</tr>";
													        $k=0;
															foreach($data1->rows as $r)
															{ 
																$mat = "SELECT material_name FROM product_material WHERE product_material_id = ".$r['sec_material_id'];
																$matdata = $this->query($mat);

																$html .= "<tr>
																			   <td><center>";
																			   if($category_id=='5' && $k==0)
																			         $html .= (int)$r['std_gsm']." G.S.M ";
																			   else
																			         $html .= $r['thickness_id']." MIC ";
																			         
																			        $html .= $r['material_name']."</center></td>";
																			        if($category_id=='1' || $category_id=='4'){
																			            $html.="<td><center>".$r['sec_thickness_id']." MIC ".$matdata->row['material_name']."</center></td>";}
																			            
    																 $html .= "<td><center>".$r['std_gsm']."</center></td>";
        																 if($category_id=='4'){
        																    $html .= "<td><center>".$r['sec_std_gsm']."</center></td>";}
        																    
    																 $html .= "<td><center>".$r['min_gsm']."</center></td>";
        																 if($category_id=='4'){
        																    $html .= "<td><center>".$r['sec_min_gsm']."</center></td>";}
        																    
    																 $html .= "<td><center>".$r['max_gsm']."</center></td>";
        																 if($category_id=='4'){
        																    $html .= "<td><center>".$r['sec_max_gsm']."</center></td>";}
																$html .= "</tr>";
																$k++;
															}
															
												$html .= "	<tr>
																  <td><center>Total=+/- 5%</br>(WITH INK & ADHESIVES)</center></td>";
																    if($category_id=='1' || $category_id=='4'){
																        $html .= "<td><center>Total=+/- 5%</br>(WITH INK & ADHESIVES)</center></td>";}
																        
													    $html .= "<td><center>".$data->row['total_std_gsm']."</center></td>";
													        if($category_id=='4'){
													            $html .= "<td><center>".$data->row['sec_total_std_gsm']."</center></td>";}
													            
														$html .= "<td><center>".$data->row['total_min_gsm']."</center></td>";
														    if($category_id=='4'){
													            $html .= "<td><center>".$data->row['sec_total_min_gsm']."</center></td>";}
													            
														$html .= "<td><center>".$data->row['total_max_gsm']."</center></td>";
													    	if($category_id=='4'){
													            $html .= "<td><center>".$data->row['sec_total_max_gsm']."</center></td>";}
													$html .="</tr>
														</tbody>
													</table>
												</td>
											</tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr>
												<td>
													<table width='100%'>
														<tbody>
															<tr>
																<td width='50%'>REGISTRATION</td>
																<td width='50%'>".$data->row['registration']."</td>
															</tr>
															<tr>
																<td width='50%'>SHADE</td>
																<td width='50%'>".$data->row['shade']."</td>
															</tr>
															<tr>
																<td width='50%'>DELAMINATION TEST</td>
																<td width='50%'>".$data->row['delamination_test']."</td>
															</tr>
															<tr>
																<td width='50%'>POUCH  LENGTH</td>
																<td width='50%'>".$data->row['pouch_length']."</td>
															</tr>
															<tr>
																<td width='50%'>POUCH WIDTH</td>
																<td width='50%'>".$data->row['pouch_width']."</td>
															</tr>";
												if($data->row['gusset_pos']!=''){
													$html .="<tr>
																<td width='50%'>GUSSETS</td>
																<td width='50%'>".$data->row['gusset_pos']."</td>
															</tr>";
												}
												if($data->row['zipper_width']!=''){
													$html .="<tr>
																<td width='50%'>ZIPPER WIDTH</td>
																<td width='50%'>".$data->row['zipper_width']."</td>
															</tr>";
												}
												if($data->row['zipper_position']!=''){
													$html .="<tr>
																<td width='50%'>ZIPPER POSITION FROM THE TOP</td>
																<td width='50%'>".$data->row['zipper_position']."</td>
															</tr>";
												}
												if($data->row['zipper_width']!=''){
													$html .="<tr>
																<td width='50%'>V NOTCH POSITION FROM THE TOP</td>
																<td width='50%'>".$data->row['v_notch']."</td>
															</tr>";
												}
													$html .="<tr>
																<td width='50%'>SEALING AREA</td>
																<td width='50%'>".$data->row['sealing_area']."</td>
															</tr>
															<tr>
																<td width='50%'>POUCH WEIGHT</td>
																<td width='50%'>".$data->row['pouch_weight']."</td>
															</tr>
															<tr>
																<td width='50%'>OTR AT 23 C, 50% R.H</td>
																<td width='50%'>".$data->row['otr']." [cc/(m² 24 hr)]</td>
															</tr>
															<tr>
																<td width='50%'>WVTR AT 38 C, 90% R.H</td>
																<td width='50%'>".$data->row['wvtr']." [gr/(m² 24 hr)]</td>
															</tr>
															<tr>
																<td width='50%'>SEALING STRENGTH</td>
																<td width='50%'>".$data->row['sealing_strength']."</td>
															</tr>
															<tr>
																<td width='50%'>BOND STRENGTH 1st  AND 2nd LAYER</td>
																<td width='50%'>".$data->row['bond_strength_1']."</td>
															</tr>";
												if($data->row['bond_strength_2']!=''){
													$html .="<tr>
																<td width='50%'>BOND STRENGTH 2nd AND 3rd LAYER</td>
																<td width='50%'>".$data->row['bond_strength_2']."</td>
															</tr>";
												}
												if($data->row['bond_strength_3']!=''){
													$html .="<tr>
																<td width='50%'>BOND STRENGTH 3rd AND 4th LAYER</td>
																<td width='50%'>".$data->row['bond_strength_3']."</td>
															</tr>";
												}
												if($data->row['bond_strength_4']!=''){
													$html .="<tr>
																<td width='50%'>BOND STRENGTH 4th AND 5th LAYER</td>
																<td width='50%'>".$data->row['bond_strength_4']."</td>
															</tr>";
												}
													$html .="<tr>
																<td width='50%'>BURSTING STRENGTH</td>
																<td width='50%'>".$data->row['bursting_strength']." KG/CM²</td>
															</tr>
															<tr>
																<td width='50%'>ODOUR TEST</td>
																<td width='50%'>".$data->row['odour_test']."</td>
															</tr>
															<tr>
																<td width='50%'>LEAKAGE TEST  </td>
																<td width='50%'>".$data->row['leakage_test']."</td>
															</tr>";
												if($data->row['drop_test']!=''){
													$html .="<tr>
																<td width='50%'>DROP TEST  </td>
																<td width='50%'>".$data->row['drop_test']."</td>
															</tr>";
												}	
															
												$html .="</tbody>
													</table>
												
												</td>
											</tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr>
												<td><center><b>REMARKS -ALL MATERIALS USED IS OF FOOD GRADE QUALITY</b></center></td>
											</tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr>
												<td><center><b>Disclaimer:  “Electronically generated Certificate, hence signature not required”</b></center></td>
											</tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr>
												<td><center>Swiss Pac makes no warranty, expressed or implied, as to the suitability of these materials for any specific use. The values shown above were developed from random samples taken from production material. We believe them to be typical for the product. Actual values may vary somewhat from those depicted here. Customers should determine product suitability based upon their own internal criteria..</center></td>
											</tr>
											<tr><td></td></tr>
											<tr><td></td></tr>
											<tr>
												<td style='font-size:18px;'><center><b>Quality Control Department</b></center></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>";
		}
		else
		{
			$html .='Sorry : No Record Found!!!';
		}
						
		return $html;
	}
	public function getQcReportDetail($qc_report_id)
	{
		$sql = 'SELECT * FROM qc_report as qr,pouch_color as pc,product_zipper as pz,size_master as sm,product as p WHERE qc_report_id="'.$qc_report_id.'" AND qr.size_id = sm.size_master_id AND qr.zipper=pz.product_zipper_id AND qr.color_id = pc.pouch_color_id AND qr.product_id =p.product_id';
		$data = $this->query($sql);
		
	
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
	public function getQcReportGSMDetail($qc_report_id)
	{
		$sql = "SELECT * FROM qc_material_gsm as gm,product_material as pm WHERE gm.qc_report_id = '".$qc_report_id."' AND pm.product_material_id = gm.material_id";
		$data = $this->query($sql);
		
		
	//	printr($data);
		if($data->num_rows){
			return $data->rows;
		}else{
			return false;
		}
	}
	public function getsizeDetail($size_id)
	{
		$sql = "SELECT pz.zipper_name,sm.volume,sm.width,sm.height,sm.gusset  FROM size_master as sm,product_zipper as pz WHERE sm.size_master_id = '".$size_id."' AND sm.product_zipper_id=pz.product_zipper_id";
		$data = $this->query($sql);
		if($data->num_rows){
			return $data->row;
		}else{
			return false;
		}
	}
}
?>