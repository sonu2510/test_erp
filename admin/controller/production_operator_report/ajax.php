<?php
include("mode_setting.php");
//[kinjal]:
$fun = $_GET['fun'];
//echo $fun;
$json=array();

 if($_GET['fun']=='production_data')
 { 
 		//printr($_POST);die;
     $data= json_decode($_POST['post_arr']);
	 $post = array('operator_id'=>$data-> operator_id,			
				'f_date'=>$data->f_date,
				't_date'=>$data->t_date);
			
	 if($data->report == '1')
	 {
		 $html =$obj_production_report->viewPrinting_report($post);
	 }else if($data->report == '2'){
		
		  $html = $obj_production_report ->AdhesiveArrayForCSV($post);
	 }else if($data->report == '3'){
		
		  $html = $obj_production_report ->viewInk_report($post);
	 }
	else if($data->report == '4'){
		
		  $html = $obj_production_report ->viewMix_Ink_report($post);
	 }
	else if($data->report == '5'){
		
		  $html = $obj_production_report ->viewSolvent_Ink_report($post);
	 }else if($data->report == '6'){
		
		  $html = $obj_production_report ->viewMix_Solvent_Ink_report($post);
	 }
	
	 echo $html;
 }
if($_GET['fun']=='getOperator')
 { 
 	
     $operators = $obj_production_report->getOperator($_POST['user_type']);
	    $html='';
	       $html.='<select name="operator_id" id="operator_id" class="form-control">';
             $html.='<option value="">Select Operator</option>';
                    foreach($operators as $operator){ 
                          $html.='<option value="'.$operator['employee_id'].' ">'.$operator['first_name'].' '.$operator['last_name'].'</option>';
                    }
                 $html.='</select>';
	 echo $html;
 }

?>