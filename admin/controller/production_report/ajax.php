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
				'machine_id'=>$data->machine_id,
				'shift'=>$data->shift,
				'f_date'=>$data->f_date,
				't_date'=>$data->t_date);
			
	 if($data->report == '2')
	 { 
		 $html =$obj_production_report->viewPrinting_report($post);
	 }else if($data->report == '1'){
		
		  $html = $obj_production_report ->AdhesiveArrayForCSV($post); 
	 }else if($data->report == '3'){
		
		  $html = $obj_production_report ->viewInk_report($post);
	 }
	else if($data->report == '9'){
		
		  $html = $obj_production_report ->viewMix_Ink_report($post);
	 }
	else if($data->report == '8'){
		
		  $html = $obj_production_report ->viewSolvent_Ink_report($post);
	 }else if($data->report == '6'){
		
		  $html = $obj_production_report ->viewMix_Solvent_Ink_report($post);
	 }else if($data->report == '7'){
		
		  $html = $obj_production_report ->viewInwardreport($post);
	 }else if($data->report == '5'){
		
		  $html = $obj_production_report ->viewSlittingreport($post);
	 }else if($data->report == '4'){
		
		  $html = $obj_production_report ->viewPouchingreport($post);
	 } 
	
	 echo $html;
 }
 if($_GET['fun']=='getmachine'){
      
       $machines = $obj_production_report->getMachine($_POST['procees_id']);
         $html.='<div class="form-group">';
               $html.='<label class="col-lg-3 control-label"><span class="required">*</span> Machine Name</label>';
                 $html.='<div class="col-lg-3">';
                
                       $html.=' <select name="machine_id" id="machine_id" class="form-control validate[required]">';
                       $html.='	<option value="">Select Machine No</option>';
        						
                                    foreach($machines as $machine){ 
                                         $html.='<option value="'. $machine['machine_id'].'" >'.$machine['machine_name'].'</option>';
                                         }
                           $html.='</select>';
                 $html.='</div>';
                $html.='</div>';    
    
    	 echo $html;
              
     
 }

?>