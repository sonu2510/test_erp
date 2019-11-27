<?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit
$edit = '';


if(isset($_GET['lamination_id']) && !empty($_GET['lamination_id']) && (isset($_GET['lamination_layer_id']) && !empty($_GET['lamination_layer_id'])) ){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$lamination_id = base64_decode($_GET['lamination_id']);
		$job_detail = $obj_lamination->getLaminationDetail($lamination_id);			
		$job_layer_details_roll = $obj_lamination->getLayerJObMaterialDetails($job_detail['job_id']);
	
	
		$lamination_layer_id = base64_decode($_GET['lamination_layer_id']);
		$lamination_layer_details= $obj_lamination->getLayer($lamination_layer_id);
		$roll_details= $obj_lamination->getRollDetails($lamination_layer_details['lamination_id'],$lamination_layer_id);	
		$product_item_layer_id= $obj_lamination->getLayerJObMaterialD($lamination_layer_details['job_id'],$lamination_layer_details['layer_no']);	
			$printing_status=0;	
			$roll_no=$obj_lamination->getRollNoDetails($product_item_layer_id,$lamination_layer_details['layer_no']);
					
		$jd=$job_detail['remark'];
		$edit = 1;
	}
	
}
else if(isset($_GET['lamination_id']) && !empty($_GET['lamination_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$lamination_id = base64_decode($_GET['lamination_id']);


	//	printr($lamination_id);
		$job_detail = $obj_lamination->getLaminationDetail($lamination_id);	
		//printr($job_detail);	
	//	$total_details=$obj_lamination->getRollDetails_total($lamination_id);

		$lamination_layer_details=$job_detail['layer_array'];
		$lamination_layer_id=$lamination_layer_details['lamination_layer_id'];	
		$roll_details= $obj_lamination->getRollDetails($lamination_id,$lamination_layer_id);	
		$job_layer_details_roll = $obj_lamination->getLayerJObMaterialDetails($job_detail['job_id']);
		$product_item_layer_id= $obj_lamination->getLayerJObMaterialD($lamination_layer_details['job_id'],$lamination_layer_details['layer_no']);	
		$roll_no=$obj_lamination->getRollNoDetails($product_item_layer_id,$lamination_layer_details['layer_no']);
		$jd=$job_detail['remark'];
		$printing_status=0;
	
		$edit = 1;
	}
	
}
else if(isset($_GET['job_id']) && !empty($_GET['job_id'])){
	$job_id = base64_decode($_GET['job_id']);
	$job_layer_d= $obj_lamination->getJob_layer_details($job_id);
	$job_layer_details_roll = $obj_lamination->getLayerJObMaterialDetails($job_id);
	 $roll_no = $obj_printing_job->getRollNo();
	$printing_status=0;
	
}
 else if(isset($_GET['printing_id']) && !empty($_GET['printing_id'])){
	$printing_id = base64_decode($_GET['printing_id']);
	$printing_details = $obj_lamination->getPrintting_details($printing_id);
	$job_layer_details = $obj_lamination->getLayerMakeMaterialDetails($printing_details['job_name_id']);
	$job_layer_details_roll = $obj_lamination->getLayerJObMaterialDetails($printing_details['job_name_id']);
	///printr($printing_id);
	 $roll_no = $obj_printing_job->getRollNo();
	$printing_status=1;
	
}
else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
		$printing_status=0;
	}
}

//Close : edit

if($display_status){
	//insert user

		if(isset($_POST['btn_add_layer'])){
		$post = post($_POST);
		//printr($post);die;	//die;	
		$insert_id = $obj_lamination->addLamination($post);

//printe($insert_id);

//
		$obj_session->data['success'] = ADD;
	page_redirect($obj_general->link($rout, '&mod=add&lamination_id='.encode($insert_id), '',1));
	
		//$insert_id = $obj_printing_job->addJob($post);
	//	$obj_session->data['success'] = ADD;
		//page_redirect($obj_general->link($rout, '', '',1));

	//die;
	}
	


	$job_latest_id=0;





	$latest_lamination_id = $obj_lamination->getlatestjobid();
	if(!empty($latest_lamination_id))
		$job_latest_id=$latest_lamination_id;
	

	if(!empty($printing_details)){		
		 $p_details= array(
			'job_name_id' => $printing_details['job_name_id'],												
			'job_name_text' => $printing_details['job_name_text'],
			'roll_used' => $printing_details['roll_used'],
			'roll_code' => $printing_details['roll_code'],
			'total_output' => $printing_details['total_output'],		
			'job_name' => $printing_details['job_name'],
			'roll_name_id' => $printing_details['roll_name_id'],	
			'roll_size' => $printing_details['roll_size'],
			'print_wastage' => $printing_details['print_wastage'],				
			'plain_wastage' => $printing_details['plain_wastage'],				
			'total_wastage' => $printing_details['total_wastage'],				
			'wastage_per' => $printing_details['wastage_per'],	
			'operator_id' => $printing_details['operator_id'],	
			'junior_id' => $printing_details['junior_id'],	
		
		);	 
			
	}else if(!empty($job_detail)){
		 $p_details= array(
			'job_name_id' => $job_detail['job_id'],												
			'job_name_text' => $job_detail['job_no'],
			'roll_code' => $job_detail['roll_code'],			
			'job_name' => $job_detail['job_name'],
			'pass_no' => $job_detail['pass_no'],
			
		);
		
	}
	else if(!empty($job_layer_d)){		
		 $p_details= array(
			'job_name_id' => $job_layer_d['job_id'],												
			'job_name_text' => $job_layer_d['job_no'],			
			'job_name' => $job_layer_d['job_name'],		
		);	 
	}
	

	
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
     
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-2 control-label"><span class="required">*</span>Lamination  No</label>
                <div class="col-lg-2">
                  	 <input type="hidden" name="edit_value" id="edit_value" value="<?php echo $edit;?>" />
                  	<input type="hidden" name="printing_status" id="printing_status" value="<?php echo $printing_status;?>" />
                  	<input type="text" name="lamination_no" readonly="readonly" value="<?php echo isset($job_detail['lamination_id'])?$job_detail['lamination_id']:$job_latest_id+1;?>" class="form-control validate[required]">
                </div>
                 <label class="col-lg-2 control-label"><span class="required">*</span>Lamination Date</label>
                <div class="col-lg-2">
                  	<input type="text" name="lamination_date" id="lamination_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($job_detail['lamination_date'])){ echo $job_detail['lamination_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required] datepicker ">
                </div>
                <label class="col-lg-2 control-label"><span class="required">*</span>Shift</label>
                <div class="col-lg-2">
                  	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="shift" id="day" value="Day" checked="checked" <?php if(isset($lamination_id) && ($job_detail['shift'] == 'Day')) { echo 'checked=checked'; } ?>/> Day
                              </label>
                            
                                <label style="font-weight: normal;">
                                  	<input type="radio" name="shift" id="night" value="Night" <?php if(isset($lamination_id) && ($job_detail['shift'] == 'Night')) { echo 'checked=checked'; }?> />Night
                              </label>
                      </div>
                </div>
              </div>
             
               <div class="form-group">
                <label class="col-lg-2 control-label"><span class="required">*</span>Job Name</label>
                <div class="col-lg-2">
                  	<input type="text" readonly name="job_name_text" id="job_name_text" value="<?php echo isset($p_details['job_name_text'])?$p_details['job_name_text']:'';?>" class="form-control validate[required]">
                    <input type="hidden" name="job_id" id="job_id" value="<?php echo isset($p_details['job_name_id'])?$p_details['job_name_id']:'';?>" />
                    <div id="ajax_response"></div>
                </div>
				<div class="col-lg-4">
                  	<input type="text" class="form-control " readonly name="job_name" id="job_name" value="<?php echo isset($p_details['job_name'])?$p_details['job_name']:'';?>"/>
                </div>
              </div>
			 <div class="form-group">
                <label class="col-lg-2 control-label">Job Start Time</label>
                <div class="col-lg-2">
                  	<input type="time" name="start_time" value="<?php echo isset($job_detail['start_time'])?$job_detail['start_time']:'';?>" class="form-control ">
                </div>
                <label class="col-lg-3 control-label">Job End Time</label>
                <div class="col-lg-2">
                  	<input type="time" name="end_time" value="<?php echo isset($job_detail['end_time'])?$job_detail['end_time']:'';?>" class="form-control ">
                </div>
              </div>
              <div class="form-group">
             
                   <label class="col-lg-2 control-label"><span class="required">*</span>Machine Name</label>
                <div class="col-lg-3">
                  	<?php $machines = $obj_lamination->getMachine();?>
                    <select name="machine_id" id="machine_id" class="form-control validate[required]">
                    	
							<?php
						
                            foreach($machines as $machine){ ?>
                                <option value="<?php echo $machine['machine_id']; ?>"
                                <?php if(isset($lamination_id) && ($machine['machine_id'] == $job_detail['machine_id'])) { echo 'selected="selected"';}?> > <?php echo $machine['machine_name']; ?></option>
                                <?php } ?> 
                     </select>
                </div>
               </div>    
			    <div class="form-group">
					<label class="col-lg-2 control-label">No. of Pass</label>
						<div class="col-lg-1">
							<input type="text" class="form-control validate[required,custom[number]]"  name="pass_no" id="pass_no" value="<?php echo isset($p_details['pass_no'])?$p_details['pass_no']:'';?>"/>
						</div>
				  </div>		

		<div class="form-group">
                <label class="col-lg-2 control-label">Remark</label>
               <div class="col-lg-4">
                    <select name="remark_lamination" id="remark_lamination" onchange="getRemark()"  class="form-control ">
                    	<option value="">Select Remark</option>
						
                                <option value="Cylinder Problem"<?php if(isset($lamination_id) && ($job_detail['remark_lamination'])=='Cylinder Problem') { echo 'selected="selected"';}?> >Cylinder Problem </option>
                                <option value="Ink Shade Problem"<?php if(isset($lamination_id) && ($job_detail['remark_lamination'])=='Ink Shade Problem') { echo 'selected="selected"';}?> >Ink Shade Problem </option>
                                <option value="Mechanical Problem"<?php if(isset($lamination_id) && ($job_detail['remark_lamination'])=='Mechanical Problem') { echo 'selected="selected"';}?> >Mechanical Problem </option>
                                <option value="Electrical Problem"<?php if(isset($lamination_id) && ($job_detail['remark_lamination'])=='Electrical Problem') { echo 'selected="selected"';}?> >Electrical Problem </option>
                           
                                <option value="Other"<?php if(isset($lamination_id) && ($job_detail['remark_lamination'])=='Other') { echo 'selected="selected"';}?> >Other </option>
                                
                     </select>
				  
				  
				
                </div>
				</div>
                <div class="form-group" id="remark">
                <label class="col-lg-2 control-label"></label>
               <div class="col-lg-4">
                     <textarea class="form-control" row="10" col="30" name="remark"><?php echo isset($job_detail['remark'])?$job_detail['remark']:'';?></textarea>		  
				  
				 
                </div>
				</div>		   
     	<?php if((isset($printing_details))) {
						//	printr($p_details);	?>
              <div class="form-group">
					<label class="col-lg-2 control-label">Printing</label> 
                    <div class="col-lg-9">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small" width="100%">
                              <thead>
                                  <tr>
                                  	
                                        <th colspan="2"><span class="required">*</span>Roll Code</th>
                                        <th colspan="2"><span class="required">*</span>Film/Roll Name</th>
                                        <th colspan="2"><span class="required">*</span>Film/Roll Size</th>
                                        <th colspan="2"><span class="required">*</span>Input Qty (Kgs)</th>                              
                                        <th colspan="2"><span class="required">*</span>No. of Roll Used</th>
                                     
                                      
                                  </tr>
                                  
                              </thead>
                              <tbody>                                    
                                <tr>
							
                                		
                                    	<td colspan="2">
										<input type="hidden" name="printing_details[printing_status]" id="printing_status" value="<?php echo $printing_status;?>" />										
										<input type="hidden" name="printing_details[f_roll_code_id]" id="f_roll_code_id" value="<?php echo isset($printing_id)?$printing_id:'';?>" />										
										<input type="hidden" name="printing_details[f_total_output]" id="f_total_output" value="<?php echo isset($p_details['total_output'])?$p_details['total_output']:'';?>" />											
										
										<input type="hidden" name="printing_details[f_plain_wastage]" id="f_plain_wastage" value="<?php echo isset($p_details['plain_wastage'])?$p_details['plain_wastage']:'';?>" />											
										<input type="hidden" name="printing_details[f_print_wastage]" id="f_print_wastage" value="<?php echo isset($p_details['print_wastage'])?$p_details['print_wastage']:'';?>" />											
										<input type="hidden" name="printing_details[f_total_wastage]" id="f_total_wastage" value="<?php echo isset($p_details['total_wastage'])?$p_details['total_wastage']:'';?>" />											
										<input type="hidden" name="printing_details[f_wastage_per]" id="f_wastage_per" value="<?php echo isset($p_details['wastage_per'])?$p_details['wastage_per']:'';?>" />
										<input type="hidden" name="printing_details[f_operator_id]" id="f_operator_id" value="<?php echo isset($p_details['operator_id'])?$p_details['operator_id']:'';?>" />
										<input type="hidden" name="printing_details[f_junior_id]" id="f_junior_id" value="<?php echo isset($p_details['junior_id'])?$p_details['junior_id']:'';?>" />											
											<input type="text" name="printing_details[f_roll_code]" id="f_roll_code" class="form-control validate[required]" value="<?php echo isset($p_details['roll_code'])?$p_details['roll_code']:'';?>"style="width:auto;" 
                                        value="<?php echo isset($roll_code)?$roll_code:'';?>" readonly="readonly"/>                                           
                                        </td>
                                        <td colspan="2">
											<input type="text" style="width:auto;" name="printing_details[f_roll_name_id]" id="f_roll_name_id" value="<?php echo isset($p_details['roll_name_id'])?$p_details['roll_name_id']:'';?>" class="form-control validate[required]" readonly="readonly">
                                        </td>
                                        <td colspan="2">
											<input type="text" style="width:auto;" name="printing_details[f_film_size]" id="f_film_size" value="<?php echo isset($p_details['roll_size'])?$p_details['roll_size']:'';?>" class="form-control " readonly="readonly">
                                        </td>
                                        <td colspan="2">
											<input type="text"  style="width:auto;" readonly="readonly" name="printing_details[f_input_qty]" id="f_input_qty" value="<?php echo isset($p_details['total_output'])?$p_details['total_output']:'';?>" class="form-control " ></td>
                                        
                                        <td colspan="2">
											<input type="text" style="width:auto;" readonly="readonly"name="printing_details[f_roll_used]" value="<?php echo isset($p_details['roll_used'])?$p_details['roll_used']:'';?>" class="form-control ">
										</td>
                                       
                                        
                                </tr>
                              </tbody>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>
         
		  	 <section class="panel">
		          <header class="panel-heading bg-white "> <b> Added Operator Detail</b> </header>
		          <div class="panel-body"> 


		        <div class="form-group">
             	 <label class="col-lg-2 control-label"><span class="required">*</span>Operator Name</label>
              	  <div class="col-lg-3">
					<?php $operators = $obj_lamination->getOperator();
					//printr($operators);?>
                    <select name="layers_details[operator_id]" id="operator_id" class="form-control validate[required]">
                    	<option value="">Select Operator</option>
							<?php
                            foreach($operators as $operator){ ?>
                                <option value="<?php echo $operator['employee_id']; ?>"
                                 > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                <?php } ?> 
                     </select>
                  </div>
				   	 <label class="col-lg-2 control-label"><span class="required">*</span> Junior Operator</label>
                <div class="col-lg-3">
					<?php $operators = $obj_lamination->getOperator();
					//printr($job_detail);?>
                    <select name="layers_details[junior_id]" id="junior_id" class="form-control ">
                    	<option value="">Select Operator</option>
							<?php
                            foreach($operators as $operator){ ?>
                                <option value="<?php echo $operator['employee_id']; ?>"
                               > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                <?php } ?> 
                     </select>
                  </div>
              
               </div> 
                          <div class="form-group">
				  
                <label class="col-lg-2 control-label"><span class="required">*</span>Shift</label>
                <div class="col-lg-3">
                  	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="layers_details[operator_shift]" id="day" value="Day" checked="checked" /> Day
                              </label>
                            
                                <label style="font-weight: normal;">
                                  	<input type="radio" name="layers_details[operator_shift]" id="night" value="Night"  />Night
                              </label>
                      </div>
                </div>
                  <label class="col-lg-2 control-label"><span class="required">*</span> Date</label>
                <div class="col-lg-3">
                  	<input type="text" name="layers_details[layer_date] " id="layer_date " data-date-format="yyyy-mm-dd" value="<?php if(isset($job_detail['job_date'])){ echo $job_detail['job_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required]">
                </div>
				  
                  
               </div>         
                           
		    	<div class="form-group">       
		    	
					   		           
                <label class="col-lg-2 control-label"><span class="required">*</span>Layer</label>
                <div class="col-lg-3">
					<input type="hidden" name="layers_details[printing_status]" id="printing_status" value="<?php echo $printing_status;?>" />
				
					<input type ="hidden" name="layers_details[layers]" id="layers" value="<?php echo isset($lamination_layer_details['layer_no'])?$lamination_layer_details['layer_no']:'';?>"/>
					
					<input type ="hidden" name="layers_details[lamination_layer_id]" id="lamination_layer_id" value="<?php echo isset($lamination_layer_details['lamination_layer_id'])?$lamination_layer_details['lamination_layer_id']:'';?>"/>
                    <select name="layers_details[product_item_layer_id]" id="product_item_layer_id" class="form-control validate[required]"<?php if(isset($_GET['lamination_layer_id'])){ ?>disabled<?php }?> onchange="getMaterialDetails()">
                    	
							<?php
						//	printr($job_layer_details_roll);
                            foreach($job_layer_details_roll as $roll)							
							{
								if($roll['layer_id']!='1'){
										
										?>
                                <option value="<?php echo $roll['product_item_layer_id'].'=='.$roll['layer_id']; ?>"
								
                                <?php if(isset($lamination_id) && ($roll['layer_id'] == $lamination_layer_details['layer_no'])) { echo 'selected="selected"';}?> > <?php echo $roll['layer_id']; ?></option>
							<?php } }?> 
                     </select>
                </div>
				<span class="btn btn-danger btn-xs">Please select Process No Before Adding Roll Details .</span>


               </div> 
			      <div class="form-group">
				 <label class="col-lg-2 control-label"><span class="required">*</span>No. of Roll Used</label>
					<div class="col-lg-3">
							<input type="text" name="layers_details[roll_used]" id="roll_used" class="form-control " value="<?php echo isset($p_details['roll_used'])?$p_details['roll_used']:'';?>" />
					</div>
					<label class="col-lg-2 control-label"><span class="required">*</span>Lamination Layer Date</label>
	                <div class="col-lg-2">
	                  	<input type="text" name="layers_details[layer_date]" id="layer_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($lamination_layer_details['layer_date'])){ echo $lamination_layer_details['layer_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required] datepicker">
	                </div>
				</div>
			   
			     <div class="form-group" id="layer_div">
					<label class="col-lg-2 control-label"></label> 
                    <div class="col-lg-9">
                        <section class="panel">
                          <div class="table-responsive sec_div">
                            <table class="tool-row table-striped  b-t text-small" id="myTable" width="100%">
                              <thead>
                                  <tr>
                                  		
                                        <th colspan="2"><span class="required">*</span>Roll No</th>
                                        <th colspan="2"><span class="required">*</span>Film/Roll Name</th>
                                        <th><span class="required">*</span>Film/Roll Size</th>
                                        <th><span class="required">*</span>Input Qty (Kgs)</th>
                                        <th><span class="required">*</span>Output Qty (Kgs)</th>                                     
                                        <th><span class="required">*</span>Balance Qty (Kgs)</th>
										<th></th>
                                       
                                  </tr>
                                
                              </thead>
                              <tbody>  
                               <?php 
							   
								 if(isset($_GET['lamination_layer_id'])  && $roll_details){
											 $job_roll_details = $roll_details;
										 }
										 else
										 {   
										    
											  
											 $job_roll_details[]= array(
												'lamination_roll_detail_id'=>'',
											    'job_id' => '',	
												'roll_num'=>'',
												'printing_roll_id' => '',
												'roll_no_id' => '',
												'roll_name_id' => '',
												'film_size' => '',
												'input_qty' => '',
												'output_qty' => '',
												'roll_used' => '',
												'balance_qty' => '',
												
																		
												
											);	 
										}

				   if(!empty($job_roll_details)){
					$inner_count = 1;
					//$layer = 1;		   	
						foreach($job_roll_details  as $job_d){
						
					//    printr($job_d); 
											?>
										                                  
                               <tr class="multiplerows-<?php echo $inner_count; ?> " id="multiplerows-<?php echo $inner_count; ?> ">
							 
                              			<input type="hidden" id="min_arr" value='<?php echo json_encode($roll_no);?>' />  
                              			<input type="hidden"  name="roll_details[<?php echo $inner_count; ?>][lamination_roll_detail_id]" id="lamination_roll_detail_id" value='<?php echo isset($job_d['lamination_roll_detail_id'])?$job_d['lamination_roll_detail_id']:'';?>' />  
									
										
                                    	<td colspan="2"> 
                                        	<select name="roll_details[<?php echo $inner_count; ?>][roll_no_id]" id="roll_no_id_<?php echo $inner_count; ?>" class="form-control validate[required] chosen_data select_choose" style="width:auto;" onchange="roll_detail_layer('<?php echo $inner_count; ?>','<?php echo $inner_count; ?>')">
                                                
                                                <?php
                                                  
                         					foreach($roll_no as $rollno){ ?>
                  		                         <option value="<?php echo $rollno['product_inward_id']; ?>" id="option_<?php  echo $inner_count; ?>"
                     								<?php if(isset($job_d['roll_no_id']) && ($rollno['product_inward_id'] == $job_d['roll_no_id'])) { echo 'selected="selected"';}?> > <?php echo $rollno['roll_no'];?></option>
                                                        <?php } ?> 
                                             </select>
                                        </td>
                                        <td colspan="2"> <?php //$rolls = $obj_printing_job->getRollName();?>
                                             <input type="text" style="width:auto;" name="roll_details[<?php echo $inner_count; ?>][roll_name_id]" id="roll_name_id_<?php echo $inner_count; ?>" value="<?php echo isset($job_d['roll_name_id'])?$job_d['roll_name_id']:'';?>" class="form-control validate[required]" readonly="readonly">
                                        </td>
                                        <td>
                                         <input type="text"  name="roll_details[<?php echo $inner_count; ?>][film_size]" id="film_size_<?php echo $inner_count; ?>" value="<?php echo isset($job_d['film_size'])?$job_d['film_size']:'';?>" class="form-control validate[required]" readonly="readonly">
                                        </td>
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][input_qty]" id="input_qty_<?php echo $inner_count; ?>" value="<?php echo isset($job_d['input_qty'])?$job_d['input_qty']:'';?>" class="form-control "></td>
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][output_qty]" id="output_qty_<?php echo $inner_count; ?>"  onchange="total_quantity(<?php echo $inner_count; ?>)"value="<?php echo isset($job_d['output_qty'])?$job_d['output_qty']:'';?>" class="form-control "></td>
                                      
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][balance_qty]" id="balance_qty_<?php echo $inner_count; ?>"  value="<?php echo isset($job_d['balance_qty'])?$job_d['balance_qty']:'';?>" class="form-control "></td>
                                     
                              <?php if($inner_count==1  ){ ?>
                                    <td>
                                        <a  onclick="add_row(<?php echo $inner_count; ?>)"class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Profit" id="addmore_<?php echo $inner_count; ?>"><i class="fa fa-plus"></i></a>
                                    </td>
                                   
                                    <?php
                                    } else{
									
										?>
										  <td>
                                       <a onclick="Remove(<?php echo $inner_count; ?>,'<?php echo $job_d['lamination_roll_detail_id']; ?>')" data-original-title="Remove Product" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-minus"></i></a>
                                    </td>
										<?php }?>                
                                   </tr>
                                      <?php $inner_count++; }} ?>         
                              </tbody>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>
                 <div class="form-group">
					<label class="col-lg-2 control-label">Wastage</label> 
                    <div class="col-lg-9">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small"  width="100%">
                              <thead>
                                  <tr>
                                       
                                        <th colspan="2" style="text-align:center">Wastage (Kgs)</th>
                                        
									   <th>Total Wastage (Kgs)</th>
										
                                        <th>Wastage (%)</th>
                                  </tr>
                                  <tr>
                                  		
                                        <th style="text-align:center">Plain Wastage (Kgs)</th>
										<th style="text-align:center">Print Wastage (Kgs)</th>
                                        <th colspan="2"></th>
                                  </tr>
                              </thead>
                              <tbody>                                    
                                <tr>
								
                                    	
                                         
                                        <td><input type="text" name="layers_details[plain_wastage]" onchange="total_quantity(<?php echo $inner_count; ?>)" id="plain_wastage" value="<?php echo isset($lamination_layer_details['plain_wastage'])?$lamination_layer_details['plain_wastage']:'';?>" class="form-control"></td>
                                        <td><input type="text" name="layers_details[print_wastage]" onchange="total_quantity(<?php echo $inner_count; ?>)" id="print_wastage" value="<?php echo isset($lamination_layer_details['print_wastage'])?$lamination_layer_details['print_wastage']:'';?>" class="form-control "></td>
                                       
                                        <td><input type="text" name="layers_details[total_wastage]" id="total_wastage" readonly="readonly" value="<?php echo isset($lamination_layer_details['total_wastage'])?$lamination_layer_details['total_wastage']:'';?>" class="form-control "></td>
                                        <td><input type="text" name="layers_details[wastage_per]" id="wastage_per" readonly="readonly" value="<?php echo isset($lamination_layer_details['wastage_per'])?$lamination_layer_details['wastage_per']:'';?>" class="form-control "></td>
                                </tr>
                              </tbody>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>
              
        <div class="line line-dashed m-t-large"></div>     


		   <div class="form-group">
					<div class="col-lg-9 col-lg-offset-3">
						<?php if(isset($_GET['lamination_layer_id'])){?>
							<button type="submit" name="btn_update_layer" id="btn_update_layer"  onclick="displaygenerate()" class="btn btn-primary">Update </button>
						<?php } else { ?>
							<button type="submit" name="btn_add_layer" id="btn_add_layer" class="btn btn-primary">Add </button>	
						<?php } ?>  
						
					</div>
				</div>
			</section>
		</div> 
		<?php }else{?> 
	  <div class="line line-dashed m-t-large"></div>   
	   <section class="panel">
		          <header class="panel-heading bg-white"> <b> Add Operator Detail</b> </header>
		          <div class="panel-body">


		        <div class="form-group">
             	 <label class="col-lg-2 control-label">Operator Name</label>
              	  <div class="col-lg-3">
					<?php $operators = $obj_lamination->getOperator();
					//printr($job_detail);?>
                    <select name="layers_details[operator_id]" id="operator_id" class="form-control ">
                    	<option value="">Select Operator</option>
							<?php
                            foreach($operators as $operator){ ?>
                                <option value="<?php echo $operator['employee_id']; ?>"
                                 > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                <?php } ?> 
                     </select>
                  </div>
				   	 <label class="col-lg-2 control-label"> Junior Operator</label>
                <div class="col-lg-3">
					<?php $operators = $obj_lamination->getOperator();
					//printr($job_detail);?>
                    <select name="layers_details[junior_id]junior_id" id="junior_id" class="form-control ">
                    	<option value="">Select Operator</option>
							<?php
                            foreach($operators as $operator){ ?>
                                <option value="<?php echo $operator['employee_id']; ?>"
                               > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                <?php } ?> 
                     </select>
                  </div>
              
               </div>    
                          <div class="form-group">
				  
                <label class="col-lg-2 control-label"><span class="required">*</span>Shift</label>
                <div class="col-lg-3">
                  	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="layers_details[operator_shift]" id="day" value="Day" checked="checked" /> Day
                              </label>
                            
                                <label style="font-weight: normal;">
                                  	<input type="radio" name="layers_details[operator_shift]" id="night" value="Night"  />Night
                              </label>
                      </div>
                </div>
                  <label class="col-lg-2 control-label"><span class="required">*</span> Date</label>
                <div class="col-lg-3">
                  	<input type="text" name="layers_details[layer_date]" id="layer_date" data-date-format="yyyy-mm-dd" value="<?php  echo date("Y-m-d");  ?>" class="form-control validate[required] datepicker">
                </div>
				  
                  
               </div>      
		    <div class="form-group">               
                <label class="col-lg-2 control-label"><span class="required">*</span>Layer</label>
                <div class="col-lg-3">
					<input type ="hidden" name="layers_details[layers]" id="layers" value="<?php echo isset($lamination_layer_details['layer_no'])?$lamination_layer_details['layer_no']:'';?>"/>
					
					<input type ="hidden" name="layers_details[lamination_layer_id]" id="lamination_layer_id" value="<?php echo isset($lamination_layer_details['lamination_layer_id'])?$lamination_layer_details['lamination_layer_id']:'';?>"/>
                    <select name="layers_details[product_item_layer_id]" id="product_item_layer_id" class="form-control validate[required]"<?php if(isset($_GET['lamination_layer_id'])){ ?>disabled<?php }?> onchange="getMaterialDetails()">
                    	
							<?php
						
                            foreach($job_layer_details_roll as $roll)
				
							{ ?>
                                <option value="<?php echo $roll['product_item_layer_id'].'=='.$roll['layer_id']; ?>"
								
                                <?php if(isset($_GET['lamination_layer_id']) && ($roll['layer_id'] == $lamination_layer_details['layer_no'])) { echo 'selected="selected"';}?> > <?php echo $roll['layer_id']; ?></option>
                                <?php } ?> 
                     </select>
                </div>
				<span class="btn btn-danger btn-xs">Please select Layer Before Adding Roll Details .</span>
               </div> 
			   
			    <div class="form-group">
				 <label class="col-lg-2 control-label"><span class="required">*</span>No. of Roll Used</label>
					<div class="col-lg-3">
						<input type ="text" name="layers_details[roll_used]" id="roll_used" class="form-control" value="<?php echo isset($lamination_layer_details['roll_used'])?$lamination_layer_details['roll_used']:'';?>"/>
					</div>

					
				</div>
			    
			     <div class="form-group" id="layer_div">
					<label class="col-lg-2 control-label"></label> 
                    <div class="col-lg-9">
                        <section class="panel">
                          <div class="table-responsive sec_div">
                            <table class="tool-row table-striped  b-t text-small" id="myTable" width="100%">
                              <thead>
                                  <tr>
                                  		
                                        <th colspan="2"><span class="required">*</span>Roll No</th>
                                        <th colspan="2"><span class="required">*</span>Film/Roll Name</th>
                                        <th><span class="required">*</span>Film/Roll Size</th>
                                        <th><span class="required">*</span>Input Qty (Kgs)</th>
                                        <th><span class="required">*</span>Output Qty (Kgs)</th>                                     
                                        <th><span class="required">*</span>Balance Qty (Kgs)</th>
										<th></th>
                                       
                                  </tr>
                                
                              </thead>
                              <tbody>  
                               <?php 
							   
								 if(isset($_GET['lamination_layer_id'])  && $roll_details){
											 $job_roll_details = $roll_details;
										 }
										 else
										 {   
										    
											  
											 $job_roll_details[]= array(
												'lamination_roll_detail_id'=>'',
											    'job_id' => '',	
												'roll_num'=>'',
												'printing_roll_id' => '',
												'roll_no_id' => '',
												'roll_name_id' => '',
												'film_size' => '',
												'input_qty' => '',
												'output_qty' => '',
												'roll_used' => '',
												'balance_qty' => '',
												
																		
												
											);	 
										}
					//printr($enquiry_product);
				   if(!empty($job_roll_details)){
					$inner_count = 1;
					//$layer = 1;		   	
						foreach($job_roll_details  as $job_d){
					
					
											?>
										                                  
                               <tr class="multiplerows-<?php echo $inner_count; ?> " id="multiplerows-<?php echo $inner_count; ?> ">
							 
                              			<input type="hidden" id="min_arr" value='<?php echo json_encode($roll_no);?>' />  
                              			<input type="hidden"  name="roll_details[<?php echo $inner_count; ?>][lamination_roll_detail_id]" id="lamination_roll_detail_id" value='<?php echo isset($job_d['lamination_roll_detail_id'])?$job_d['lamination_roll_detail_id']:'';?>' />  
									
										
                                    	<td colspan="2"> 
                                        	<select name="roll_details[<?php echo $inner_count; ?>][roll_no_id]" id="roll_no_id_<?php echo $inner_count; ?>" class="form-control validate[required] chosen_data select_choose" style="width:auto;" onchange="roll_detail_layer('<?php echo $inner_count; ?>','<?php echo $inner_count; ?>')">
                                                
                                                <?php
                                                  
                         							 foreach($roll_no as $rollno){ ?>
                  		                         <option value="<?php echo $rollno['product_inward_id']; ?>" id="option_<?php  echo $inner_count; ?>"
                     								<?php if(isset($job_d['roll_no_id']) && ($rollno['product_inward_id'] == $job_d['roll_no_id'])) { echo 'selected="selected"';}?> > <?php echo $rollno['roll_no'];?></option>
                                                        <?php } ?> 
                                             </select>
                                        </td>
                                        <td colspan="2"> <?php //$rolls = $obj_printing_job->getRollName();?>
                                             <input type="text" style="width:auto;" name="roll_details[<?php echo $inner_count; ?>][roll_name_id]" id="roll_name_id_<?php echo $inner_count; ?>" value="<?php echo isset($job_d['roll_name_id'])?$job_d['roll_name_id']:'';?>" class="form-control validate[required]" readonly="readonly">
                                        </td>
                                        <td>
                                         <input type="text"  name="roll_details[<?php echo $inner_count; ?>][film_size]" id="film_size_<?php echo $inner_count; ?>" value="<?php echo isset($job_d['film_size'])?$job_d['film_size']:'';?>" class="form-control validate[required]" readonly="readonly">
                                        </td>
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][input_qty]" id="input_qty_<?php echo $inner_count; ?>" value="<?php echo isset($job_d['input_qty'])?$job_d['input_qty']:'';?>" class="form-control "></td>
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][output_qty]" id="output_qty_<?php echo $inner_count; ?>"  onchange="total_quantity(<?php echo $inner_count; ?>)"value="<?php echo isset($job_d['output_qty'])?$job_d['output_qty']:'';?>" class="form-control "></td>
                                      
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][balance_qty]" id="balance_qty_<?php echo $inner_count; ?>"  value="<?php echo isset($job_d['balance_qty'])?$job_d['balance_qty']:'';?>" class="form-control "></td>
                                     
                              <?php if($inner_count==1  ){ 
							//  printr($edit);?>
                                    <td>
                                        <a  onclick="add_row(<?php echo $inner_count; ?>)"class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Profit" id="addmore_<?php echo $inner_count; ?>"><i class="fa fa-plus"></i></a>
                                    </td>
                                   
                                    <?php
                                    } else{
									
										?>
										  <td>
                                       <a onclick="Remove(<?php echo $inner_count; ?>,'<?php echo $job_d['lamination_roll_detail_id']; ?>')" data-original-title="Remove Product" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-minus"></i></a>
                                    </td>
										<?php }?>                
                                   </tr>
                                      <?php $inner_count++; }} ?>         
                              </tbody>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>
                 <div class="form-group">
					<label class="col-lg-2 control-label">Wastage</label> 
                    <div class="col-lg-9">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small"  width="100%">
                              <thead>
                                  <tr>
                                       
                                        <th colspan="2" style="text-align:center">Wastage (Kgs)</th>
                                       
									   <th>Total Wastage (Kgs)</th>
										
                                        <th>Wastage (%)</th>
                                  </tr>
                                  <tr>
                                  		
                                        <th style="text-align:center">Plain Wastage (Kgs)</th>
										<th style="text-align:center">Print Wastage (Kgs)</th>
                                        <th colspan="2"></th>
                                  </tr>
                              </thead>
                              <tbody>                                    
                                <tr>
								
                                    	
                                         
                                        <td><input type="text" name="layers_details[plain_wastage]" onchange="total_quantity(<?php echo $inner_count; ?>)" id="plain_wastage" value="<?php echo isset($lamination_layer_details['plain_wastage'])?$lamination_layer_details['plain_wastage']:'';?>" class="form-control"></td>
                                        <td><input type="text" name="layers_details[print_wastage]" onchange="total_quantity(<?php echo $inner_count; ?>)" id="print_wastage" value="<?php echo isset($lamination_layer_details['print_wastage'])?$lamination_layer_details['print_wastage']:'';?>" class="form-control "></td>
                                       
                                        <td><input type="text" name="layers_details[total_wastage]" id="total_wastage" readonly="readonly" value="<?php echo isset($lamination_layer_details['total_wastage'])?$lamination_layer_details['total_wastage']:'';?>" class="form-control "></td>
                                        <td><input type="text" name="layers_details[wastage_per]" id="wastage_per" readonly="readonly" value="<?php echo isset($lamination_layer_details['wastage_per'])?$lamination_layer_details['wastage_per']:'';?>" class="form-control"></td>
                                </tr>
                              </tbody>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>
              
				
              
			   <div class="form-group">
					<div class="col-lg-9 col-lg-offset-3">
						<?php if(isset($_GET['lamination_layer_id'])){?>
							<button type="submit" name="btn_update_layer"  onclick="displaygenerate()"  id="btn_update_layer"  class="btn btn-primary">Update </button>
						<?php } else { ?>
							<button type="submit" name="btn_add_layer"  id="btn_add_layer" class="btn btn-primary">Add </button>	
						<?php } ?>  
						
					</div>
				</div>
			   <div class="line line-dashed m-t-large"></div>
            
			   <?php
			   }
			   ?>
			</section>
		</div>
	
	 <section class="panel">
	  <header class="panel-heading "> <b> Added Operator Detail</b> </header>
	  <div class="panel-body">
					   		   
	 <div  class="form-group" id ="invoice_results">
			 <?php 
			 
			if(isset($lamination_id)){
			?>
			<input type="hidden" id="lamination_id" name="lamination_id" value="<?php echo $lamination_id;?>"/>
			<input type="hidden" id="lamination_layer_id" name="lamination_layer_id" value="<?php echo $lamination_layer_id;?>"/>		
        	<table class="table table-bordered">
         <thead>
		   <tr>
				<th>Layer No</th>
				<th>Operator Name</th>		
				<th>Roll No</th>		
				<th>Roll Used </th>
				<th>Total Input </th>
				<th>Total Output</th>
				<th>Plain Wastage (Kgs)</th>
				<th>Print Wastage (Kgs)</th>
				<th>Total Wastage (Kgs)</th>	
				<th>Wastage (%)</th>
				<th>Action</th>
			</tr>
		</thead>
		  
            <tbody>
        <?php
		
			$layer_details= $obj_lamination->getLayerDetails($lamination_id);
			$roll_no=array();
			foreach($layer_details as $layer_details){

				$junior_name=$operator_name='';
				$junior= $obj_lamination->getOperator_name($layer_details['junior_id']);
				$operator= $obj_lamination->getOperator_name($layer_details['operator_id']);
				if($junior!=''){
						$junior_name=' <b>Junior Operator Name : </b> '.$junior;
				}
				if($operator!=''){
					$operator_name='<b>Operator Name :</b> '.$operator.' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$junior_name;

				}
			//	printr($layer_details);
					if($layer_details['printing_status']=='0'){
						$roll_details= $obj_lamination->getRollDetails($lamination_id,$layer_details['lamination_layer_id']);	
											//	 printr($roll_details);	
											 foreach($roll_details as $inv ) {
												
											  $roll =$obj_lamination->get_roll_no($inv['roll_no_id']);
											  $roll_no[]=$roll;
												}
											$uni_arr = array_unique($roll_no);
											$u_arr = implode(',',$uni_arr);
					}else{
							$roll_code=$obj_lamination->getPrintingRollDetails($layer_details['lamination_layer_id']);	
							$u_arr=$roll_code.'  (Printing Roll)';
						
					}?>
            <tr id="proforma_invoice_id_<?php echo $layer_details['lamination_layer_id'] ?>">
				  <td> <?php echo $layer_details['layer_no'];?></td>
				  <td> <?php echo $operator_name;?></td>
				  <td> <?php echo $u_arr;?></td>
				  <td> <?php echo $layer_details['roll_used'];?></td>
				  <td> <?php echo $layer_details['total_input'];?></td>
				  <td> <?php echo $layer_details['total_output'];?></td>
				  <td> <?php echo $layer_details['plain_wastage'];?></td>
				  <td> <?php echo $layer_details['print_wastage'];?></td>
				  <td> <?php echo $layer_details['total_wastage'];?></td>
				  <td> <?php echo $layer_details['wastage_per'];?></td>
				 <?php if($layer_details['printing_status']=='0'){?>
				  <td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice(<?php echo $lamination_id.','.$layer_details['lamination_layer_id']; ?>)"><i class="fa fa-trash-o"></i></a>
                		 
				 <?php }else{?>
				 <td></td><?php }?>
				  </tr>
                  <!-- CONFIRMATION ALERT BOX -->
                    <div class="modal fade" id="alertbox_<?php echo $layer_details['lamination_layer_id']; ?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">Title</h4>
                                </div>
                                <div class="modal-body">
                                    <p id="setmsg">Do you really want to delete this record ?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="popbtncan" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" name="popbtnok" id="popbtnok_<?php echo $layer_details['lamination_layer_id']; ?>" 
                                    class="btn btn-primary">Ok</button>
                                 
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                    <!-- END OF CONFIRMATION BOX-->
<?php $roll_no=array();
		}}?>
		  </tbody></table>
	</div>
   </div>
</section>
              <?php if($edit){
				  //mod=view&lamination_id='.$_GET['lamination_id']?>
			<div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
					
                    <a class="btn btn-primary"  onclick="updatepass(<?php echo $job_detail['lamination_id']; ?>)">Update</a>  <a class="btn btn-default"  href="<?php echo $obj_general->link($rout, '', '',1);?>">cancel</a>
                </div>
            </div>
			  <?php }?>
              <div class="form-group"  id = "save">
                <div class="col-lg-9 col-lg-offset-3">
               
                  <a id="generate_lamination" style="display:none"  name="generate_lamination" class="btn btn-primary" href="<?php echo $obj_general->link($rout, '', '',1);?>">generate Lamination</a>
				 
				  
				
                </div>
              </div>
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style type="text/css">
#ajax_response, #ajax_res,#ajax_return{
	border : 1px solid #13c4a5;
	background : #FFFFFF;
	position:relative;
	display:none;
	padding:2px 2px;
	top:auto;
	border-radius: 4px;
}
#holder{
	width : 350px;
}
.list {
	padding:0px 0px;
	margin:0px;
	list-style : none;
}
.list li a{
	text-align : left;
	padding:2px;
	cursor:pointer;
	display:block;
	text-decoration : none;
	color:#000000;
}
.selected{
	background : #13c4a5;
}
.bold{
	font-weight:bold;
	color: #227442;
	
}
.select_choose{
	width:100px;
}
</style>


<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>



    $(document).ready(function() {
         $("#lamination_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
    });    

jQuery(document).ready(function(){
//	 jQuery("#form").validationEngine();
		 $(".chosen_data").chosen();
	 getRemark();
	<?php if(!isset($_GET['lamination_layer_id'])){?>
				getMaterialDetails();
				$("#layers").val('');
				$("#roll_used").val('');
				$("#plain_wastage").val('');
				$("#print_wastage").val('');							
				$("#total_wastage").val('');							
				$("#wastage_per").val('');
				$("#product_item_layer_id").val('');	
				
	<?php }?>

	 edit = $("#edit_value").val();
	 if(edit!='1'){
		 
	
			getMaterialDetails();
		 roll_detail_layer(1,1);
	 }
});

$(".chosen-select").chosen();



	function add_row(s_count) {
		
		count_layer = $("#layers").val();
		layer = count_layer -1;
		//var t_count = $("#myTable tr").length;	
	       var tab=$('#myTable tr:last').attr('id');
			var a=tab.split('-');
			//alert(a[1]);
			var t_count=(parseInt(a[1])+1);
		//  alert(tab);
		 // t_count=tab;
		 //  	 alert(t_count);
		var arr = jQuery.parseJSON($('#min_arr').val());
	//	alert(arr);
		
				 var html = '';		  
					 html +='<tr class="multiplerows-'+t_count+'" id="multiplerows-'+t_count+'"> ';
				
					
			  
					 html +='<td colspan="2"> ';
							 html+='<select name="roll_details['+t_count+'][roll_no_id]" id="roll_no_id_'+t_count+'" onchange="roll_detail_layer('+t_count+','+t_count+') "class="form-control validate[required] chosen-select select_choose">';
								for(var i=0;i<arr.length;i++)
									{
										html +='<option value='+arr[i].product_inward_id +'>'+arr[i].roll_no +'</option>';
									}
							 html +='</select>';
						 
					 html +='</td>';
					 html +='<td colspan="2">';
							html +='<input type="text" style="width:auto;" name="roll_details['+t_count+'][roll_name_id]" id="roll_name_id_'+t_count+'" value="" class="form-control validate[required]" readonly="readonly">';
					 html +='</td>';
					 html +='<td>';
								html +='<input type="text"  name="roll_details['+t_count+'][film_size]" id="film_size_'+t_count+'" value="" class="form-control validate[required]" readonly="readonly">';
					 html +='</td>';
					 html +='<td>';
							  
							 html +='<input type="text" name="roll_details['+t_count+'][input_qty]" id="input_qty_'+t_count+'" value="" class="form-control ">';

					 html +='</td>';
					 html +='<td>';
							 html +='<input type="text" name="roll_details['+t_count+'][output_qty]" onchange="total_quantity('+t_count+')" id="output_qty_'+t_count+'" value="" class="form-control ">';
					 html +='</td>';
				   
					 html +='<td>';
							 html +='<input type="text" name="roll_details['+t_count+'][balance_qty]"  id="balance_qty_'+t_count+'" value="" class="form-control ">';
					 html +='</td>';
					 html +='<td>';
				
					html+='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="" data-original-title="remove"><i class="fa fa-minus"></i></a></td>';
					html +='</td>';
					
					html +='</tr>';
				
		
		
				
				$('#myTable tr:last').after(html);
				$(".chosen-select").chosen();
				roll_detail_layer(t_count,t_count);
				$(' .remove').click(function(){
					$("#addmore").show();
					
					
					$(this).parent().parent().remove();
					total_quantity(t_count);
				});
			
								
	}
	function Remove(count,lamination_roll_detail_id){
		//alert(count);
	
		//alert(roll_id);
			$('.multiplerows-'+count).remove();
		
		if(lamination_roll_detail_id !=''){
			
			var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_roll', '',1);?>");
				$.ajax({
					url : remove_url,
					method : 'post',
					data : {lamination_roll_detail_id : lamination_roll_detail_id},
					success: function(response){
					
					
					},
					error: function(){
						return false;	
					}
			});	
		}
	}
	function roll_detail_layer(size_id,count)
		{
			
		//	alert(count);	
				
			var val =$('#roll_no_id_'+count+' option:selected' ).val();
			
			//alert(size_id+'==='+val);
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getInputQty', '',1);?>");	
			$.ajax({
				type: "POST",
				url: url,		
				data:{val:val}, 
				success: function(response) {	
						
					var msg = $.parseJSON(response);	
					//console.log(response);	
					
					$('#input_qty_'+size_id+'').val(msg.bal_qty);
					$('#film_size_'+size_id+'').val(msg.film_size);
					$('#roll_name_id_'+size_id+'').val(msg.film_name);
					
				}	
			});
		}
	function  total_quantity(count){
			 var tab=$('#myTable tr:last').attr('id');
			 var a=tab.split('-');
			 
			/* var o_qty=$('#output_qty_'+count).val();
			 var input_qty=$('#input_qty_'+count).val();
			 $('#balance_qty_'+count).val((parseFloat(input_qty)-parseFloat(o_qty).toFixed (3)))*/
			 
			
			var t_count = $("#myTable tr").length-1;	
			
			var sum = 0;
			var i_sum = 0;
			for(var i =1; i<=t_count; i++){
			if ($('#output_qty_'+i).length)	
				var output_qty = $('#output_qty_'+i).val();
				var input_qty_t = $('#input_qty_'+i).val();
				
			if (output_qty.length > 0)
				
				sum += parseFloat(output_qty);  
				i_sum += parseFloat(input_qty_t);  
				
			}
			//alert(i_sum);
		var plain_wastage = $("#plain_wastage").val();
		var print_wastage = $("#print_wastage").val();
		if(plain_wastage!='' && print_wastage!='')
		{
			$("#total_wastage").val(parseFloat(plain_wastage)+parseFloat(print_wastage));
			var total_was = $("#total_wastage").val();
		
			if(sum!='')
				$("#wastage_per").val(((100*total_was)/sum).toFixed (2));
				//alert((100*total_was)/sum));
		}
		
		var total_was = $("#total_wastage").val();
			if(total_was!=''){
				$('#balance_qty_'+t_count).val((parseFloat(i_sum)-(parseFloat(sum)+ parseFloat(total_was)).toFixed (3)));			
				var balance_qty = $('#balance_qty_'+t_count).val();
				
			}
		}
	
function getMaterialDetails(){
		
		var layers = $('#product_item_layer_id').val();
		var res = layers.split("==");
		var product_item_layer_id = res[0];	
		var layer_id = res[1];
		//alert(res[0]);
		//alert(res[1]);
		$('#layers').val(layer_id);
		
			//var size_id = $('#size').val();
			var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getMaterialDetails', '',1);?>");
			$.ajax({
				type: "POST",
				url: size_url,					
				data:{product_item_layer_id:product_item_layer_id,layer_id:layer_id},
				success: function(json) {
					if(json){
						$("#layer_div").html(json);
						$(".chosen_data").chosen();
						roll_detail_layer(1,1);
						}
				}
			});
				
		
		}
 

function removeInvoice(lamination_id,lamination_layer_id){
$("#alertbox_"+lamination_layer_id).modal("show");
$(".modal-title").html("Delete Record".toUpperCase());
$("#setmsg").html("Are you sure you want to delete ?");
$("#popbtnok_"+lamination_layer_id).click(function(){
	var remove_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeInvoice', '',1);?>");
	$.ajax({
		url : remove_invoice_url,
		method : 'post',
		data : {lamination_layer_id : lamination_layer_id,lamination_id:lamination_id},
		success: function(response){
			if(response == 0) {			
			$("#alertbox_"+lamination_layer_id).hide();
		}
			$("#alertbox_"+lamination_layer_id).hide();
			$("#alertbox_"+lamination_layer_id).modal("hide");
			$('#proforma_invoice_id_'+lamination_layer_id).html('');
			set_alert_message('Layer  Record successfully deleted','alert-success','fa fa-check');
			window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=lamination&mod=add&lamination_id='+encode(lamination_id)+'';
			},
		error: function(){
			return false;	
		}
	});
$("#alertbox_"+lamination_layer_id).hide();
$("#alertbox_"+lamination_layer_id).modal("");
 });
}
function displaygenerate()
	{
		
		var edit=$("#edit_value").val(); 
		//alert(edit);
		if(edit==''){
			$("#generate_lamination").show();
			
		}else{
			$("#generate_lamination").hide();
		
		}
				
	}
function updatepass(l_id){

		var passvalue=$('#pass_no').val();
		var remark=$('#remark').val();
		var remark_lamination=$('#remark_lamination').val();
		var l_date=$('#lamination_date').val();
		
		var op_name=jQuery("#operator_id option:selected").val();
		var m_name=jQuery("#machine_id option:selected").val();
		//alert(remark);
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updatepass', '',1);?>");
			$.ajax({
				type: "POST",
				url: url,					
				data:{l_id:l_id,passvalue:passvalue,op_name:op_name,m_name:m_name,remark:remark,remark_lamination:remark_lamination},
				success: function(json) {
					location.reload();
					window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=lamination';
				}
			});
		
}
function getRemark(){
	var val =$('#remark_lamination option:selected' ).val();	
		if(val=='Other'){
			$("#remark").show();
		}else{
			$("#remark").val('');
			$("#remark").hide();
		}

	}
		
</script>
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>