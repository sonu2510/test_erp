<?php
include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'updateEnquiryStatus') {
	$enquiry_id = $_POST['enquiry_id'];
	$status_value = $_POST['status_value'];
	$obj_enquiry->$fun($enquiry_id,$status_value);
}
if($fun == 'get_size') {
	$product_id = $_POST['product_id'];
	$count = $_POST['count'];
	 $data = $obj_enquiry->$fun($product_id);
	 $product_volumes = $obj_enquiry->getProductVolumes(); 
	 $html ='';
	// printr($data);
	if(!empty($data)){
			
			
		
			$html.='<div class="form-group" id = "volume_div">';
				$html.= '<label class="col-lg-3 control-label">Volume</label>';
				$html.= '<div class="col-lg-9">';
								  foreach($data as $d){ 	
							  
									
										$html.= '<div class="checkbox col-lg-4">';
											$html.= '<label>';									
												$html.= '<input type="checkbox" id ="volume" class="validate[minCheckbox[1]]" value="'. $d['pouch_volume_id'].'" name="nproducts['.$count.'][product_volume][]">
												'.$d['volume'].'</label>';
												$html.= '</div>';
											
										} 
										
											//printr($html);
							$html.='</div>
							  </div>';
                      
		}else{
			$html.='<div class="form-group" id = "volume_div">';
				$html.= '<label class="col-lg-3 control-label">Volume</label>';
				$html.= '<div class="col-lg-9">';
								  foreach($product_volumes as $d){ 	
							  
									
										$html.= '<div class="checkbox col-lg-4">';
											$html.= '<label>';									
												$html.= '<input type="checkbox" id ="volume" class="validate[minCheckbox[1]]" value="'. $d['pouch_volume_id'].'" name="nproducts['.$count.'][product_volume][]">
												'.$d['volume'].'</label>';
												$html.= '</div>';
											
										} 
										
											//printr($html);
							$html.='</div>
							  </div>';
                      
		}
	//	printr($html);
		echo $html;
}

if($fun == 'addHistory') {
	$enquiry_id = $_POST['enquiry_id'];
	$follow_up_date = $_POST['new_follow_up_date'];
	//$reminder = $_POST['reminder'];
	//printr($_POST);die;
	$note = $_POST['new_note'];
	$user_name = $obj_enquiry->$fun($enquiry_id,$follow_up_date,$note,'');
	$reminder_date = $reminder;
		if($reminder == '2')
		{
			
			$date = isset($follow_up_date) ? $follow_up_date: date('Y-m-d');
			$reminder_date = date("Y-m-d",strtotime($date.' -2 day'));
		}else if($reminder == '3')
		{
			$date = isset($follow_up_date) ? $follow_up_date : date('Y-m-d');
			$reminder_date = date("Y-m-d",strtotime($date.' -3 day'));
			
		}else
		{
			$date = isset($follow_up_date) ?$follow_up_date : date('Y-m-d');
			$reminder_date = date("Y-m-d",strtotime($date.' -5 day'));
		
		}
	
	$response = '<tr>';
	 $response .= '<td>'.date("d-M-Y",strtotime($follow_up_date)).'</td>';
	 $response.='<td>'.$reminder_date.'</td>';
	 $response .= '<td>'.$note.'</td>';
	 $response .= '<td>'.$user_name.'</td>';
	 $response .= '<td>'.date("d-M-y").'</td>';
	$response .= '</tr>';  
	
	echo $response; 
	   
	}
	if($fun == 'remove'){
	$enquiry_id= $_POST['enquiry_id'];    
	//printr($enquiry_id);
	$product_enquiry_id= $_POST['product_enquiry_id'];
	$result = $obj_enquiry->remove_enquiry_record($enquiry_id,$product_enquiry_id);
	
	//$response='done';
	//kavita:22-3-2017
	}
	
	if($fun == 'company_detail')
	{
	$company_name = $_POST['company_name'];
	//printr($company_name );
	$result = $obj_enquiry->getCompanyDetail($company_name);
	//printr($result);
	echo json_encode($result);
	}
	if($_GET['fun']=='viewCustomerLeadsReport')
    { 
 	
     $data= json_decode($_POST['post_arr']);
	 $post = array('f_date'=>$data->f_date,
			       't_date'=>$data->t_date,
				   'user_name'=>$data->user_name);
			
    	//printr($post);die;
    	  $h.='<style>
                    table, th, td {
                        border: 1px solid black;
                    }
                    </style>';
        
      $html = $obj_enquiry ->viewCustomerLeadsReport($post);
       $h.= $html;
     

	 echo $h;
}
if($fun == 'check_customer') {
	$result = $obj_enquiry->$fun($_POST['email']);
	$sales_person_name = $obj_enquiry->getUser($result['user_id'],$result['user_type_id']);
	if($result)
	{
	    echo 'This user is already registered by '.$sales_person_name['name'];
	}
	else
	{
	    echo 0;
	}
	
}

	
?>