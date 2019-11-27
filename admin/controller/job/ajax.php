<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();


if($fun=='updateJobStatus') {
	
	$job_id = $_POST['job_id'];
	$status_value = $_POST['status_value'];
	
	$obj_job->$fun($job_id,$status_value);

}
if($fun == 'getProductSize') {
//	printr($_POST);	die;
	$product_id = $_POST['product_id'];
	//echo $_POST['zipper_id'];
	
	//die;
	$data = $obj_job->getProductSize($product_id);
	//printr($data);
	$response = '';	
		$response .='<select id="size_pro" name="size_pro" class="form-control " onchange="customSize()"><option value="">Select Size</option>';
	if($data){	
		foreach($data as $item){
				//$response .= '<div class="checkbox " style="float: left;  width: 50%;"><label>';
					//$response .= '<input type="checkbox" name="size[]" class="test" onclick="removeValidation()" id="'.$item['size_master_id'].'" value="'.encode($item['size_master_id']).'" >'.$item['volume'].'['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']';
					
					if($product_id=='27')
						$response .= '<option value="'.$item['size_master_id'].'" selected="selected">';
					else
						$response .= '<option value="'.$item['size_master_id'].'">';
					if($item['volume']!=0)
					$response .=  $item['volume'];
					$response .= ' ['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']</option>';					
				//$response .= '</label></div>';
		}
	}
	 
	 	// sonu add condition Oxo-Degradable Bags - Brand: Bak2Earth - Stand up pouch[online product id = 22]Silica Gel / Moisture Absorbers[online product id 38]
	  	if($product_id!='28')
		{		
			$response .= '<option value="0">Custom</option></select>';
		}
	
	echo $response;
}

if($fun == 'get_user_details'){
	
	$country_id = $_POST['country_id'];
	//echo $_POST['zipper_id'];
	
	//die;
	$data = $obj_job->get_user_details($country_id);
	$response = '';	
		$response .='<label class="col-lg-3 control-label"> User Detail</label>';
		$response .='<div class="col-lg-4" id="user_details">';
		$response .='<select id="user_details" name="user_details" class="form-control " ><option value="">Select user</option>';
		if($data){	
		foreach($data as $user){	
			
						$response .= '<option value="'.$user['employee_id'].'">'.$user['first_name'].'  '.$user['last_name'].'</option>';
						}
				}
	 
		$response .= '</select>';
		$response .= '</div>';
		
	
	echo $response;
}
if($fun == 'getMaterial') {
	//printr($_POST);
		$layers = $_POST['layers'];
		//	printr($layers);die;
if($layers > 0){
	


//echo $_POST['make'];
	//$makeid = (int)decode($_POST['make']);
	
	$json = '';
if($layers){
	
		
		$html = '';
		$html .= '<section class="panel">';
		  $html .= '<div class="table-responsive">';
			$html .= '<table class="table table-striped b-t text-small">';
			  $html .= '<thead>';
				$html .= '<tr>';
				  $html .= '<th  width="15%"></th>';
				  $html .= '<th>Material</th>';	
				  $html .= '<th>Film Size</th>';	
							 
				$html .= '</tr>';
			  $html .= '</thead>';
			  $html .= '<tbody>';
			  //echo $layer;
			//  printr($html);
			  for($i=1;$i<=$layers;$i++){
				  $layer_materials = $obj_job->getLayerMakeMaterial($i);
			 // printr($layer_materials);
				  //$layer_materials;
				  if($layer_materials){ 
				     // printr($layers);
                        $html .= '<tr>'; 
                          $html .= '<td><b>'.$i.' Layer</b></td>';
                          $html .= '<td>';
                          	$html .= '<select name="material['.$i.'][material_id]" onchange="getMaterialThickness(this.value,'.$i.','.$layers.')" id="material_'.$i.'" class="form-control validate[required]">';
											$html .= '<option value="">Select Material</option>';	
											foreach($layer_materials as $material){
												$html .= '<option value="'.$material['material_id'].'">'.$material['material_name'].'</option>';
											}
								$html .= '</select>';
                          $html .= '</td>';
                          $html .= '<td>';
                          $html .= '<input type="text" name="material['.$i.'][film_size]" value="" class=  "form-control">';
                          $html .= '</td>';
                        
						 
                        $html .= '</tr>';
				  }
			  }
			  $html .= '</tbody>';
			$html .= '</table>';
		  $html .= '</div>';
		$html .= '</section>'; 
	
	//	$json = $html;
		
		echo $html;
	}
	
}else{
	echo 0;
}

	
}
 if($fun == 'removeFile'){
	$upload_path = DIR_UPLOAD.'admin/dielineForJob/';
	$job_img_id = $_POST['die_id'];
	$job_data = $obj_job->getJobDielineDetails($job_img_id);
	if(isset($job_data) && !empty($job_data))
	{
		if(file_exists($upload_path.'100_'.$job_data['job_name']))
		{
			unlink($upload_path.'100_'.$job_data['job_name']);
		}
		if(file_exists($upload_path.'500_'.$job_data['job_name']))
		{
			unlink($upload_path.'500_'.$job_data['job_name']);
		}
		if(file_exists($upload_path.''.$job_data['job_name']))
		{
			unlink($upload_path.''.$job_data['job_name']);
		}
	}
	$obj_job->DeleteJobDieline($job_img_id);
	echo $job_img_id;
}
else if($fun == 'removeImg'){
	$upload_path = DIR_UPLOAD.'admin/dieline/';
	if(isset($_SESSION['product_die_line'])){
		foreach($_SESSION['product_die_line'] as $die_line){
			//printr($die_line);
			if($die_line['die_id']==$_POST['die_id']){				
				unset($_SESSION['product_die_line'][$die_line['die_id']]);
				if($die_line['die_ext']=='img')
				{
					if(file_exists($upload_path.'100_'.$die_line['die_name'])){
						unlink($upload_path.'100_'.$die_line['die_name']);
					}
					if(file_exists($upload_path.'500_'.$die_line['die_name'])){
						unlink($upload_path.'500_'.$die_line['die_name']);
					}
					if(file_exists($upload_path.$die_line['die_name'])){
						unlink($upload_path.$die_line['die_name']);
					}
				}
				else
				{
					if(file_exists($upload_path.$die_line['die_name'])){
						unlink($upload_path.$die_line['die_name']);
					}
				}
			}
		}		
		if(empty($_SESSION['product_die_line'])){
			unset($_SESSION['product_die_line']);	
		}
	}
	echo 1;	
}
?>