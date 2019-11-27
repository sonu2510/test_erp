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

if(isset($_GET['slitting_id']) && !empty($_GET['slitting_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$slitting_id = base64_decode($_GET['slitting_id']);
		$slitting_details = $obj_slitting->getJobDetail($slitting_id);
		$slitting_roll_details = $obj_slitting->getslitting_roll_details($slitting_id);
	//	printr($slitting_details);
		$roll_code=$roll_size='';
		
		
		if($slitting_details['slitting_status']==0){
			$printing_details = $obj_slitting->getPrintingDetails($slitting_details['roll_code_id']);			
			$printing_roll_details = $obj_slitting->getPrintingRollDetails($slitting_details['roll_code_id']);
			$roll_code=$printing_details['roll_code'];
			$roll_size=$printing_details['roll_size'];
		}else if($slitting_details['slitting_status']==1){
			$lamination_details = $obj_slitting->getLamination_details($slitting_details['roll_code_id']);
			$roll_code=$lamination_details['roll_code'];
			$roll_size=$lamination_details['roll_size'];
		}else{
			$roll_details = $obj_slitting->getRoll_details($slitting_details['roll_code_id']);
			$roll_code= $roll_details['roll_no'];
			$roll_size= $roll_details['inward_size'];
		}
		
		
	//	printr($slitting_details['slitting_status']);
	//	printr($slitting_roll_details);
		$jd=$slitting_details['remark'];
		$edit = 1;
	}
	
}
else if(isset($_GET['printing_id']) && !empty($_GET['printing_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$printing_id = base64_decode($_GET['printing_id']);
		$printing_details = $obj_slitting->getPrintingDetails($printing_id);
		$printing_roll_details = $obj_slitting->getPrintingRollDetails($printing_id);
	//printr($printing_details);	
	
		$edit = 0;
	}
	
}
else if(isset($_GET['lamination_id']) && !empty($_GET['lamination_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$lamination_id = base64_decode($_GET['lamination_id']);
		$lamination_details = $obj_slitting->getLamination_details($lamination_id);
			//printr($lamination_details);
	
		$edit = 0;
	}
	
}
else if(isset($_GET['roll_id']) && !empty($_GET['roll_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$roll_id = base64_decode($_GET['roll_id']);
		$roll_details = $obj_slitting->getRoll_details($roll_id);
		//	printr($roll_details);
	
		$edit = 0;
	}
	
}



else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
	
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
	//printr($post);die;	
		$insert_id = $obj_slitting->addSlitting($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		
		$slitting_id = base64_decode($_GET['slitting_id']);
		$obj_slitting->updateSlitting($slitting_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	$slitting_latest_id=0;
	$latest_slitting_id = $obj_slitting->getlatestslittingid();
	if(!empty($latest_slitting_id))
	$slitting_latest_id=$latest_slitting_id;


	
	if(!empty($lamination_details)){		
		 $p_details= array(
			'id'=>$lamination_details['lamination_id'],	
			'job_name_id' => $lamination_details['job_id'],												
			'job_name_text' =>$lamination_details['job_no'],		
			'job_name' => $lamination_details['job_name'],
			'roll_code' => $lamination_details['roll_code'],	
			'roll_size' => $lamination_details['roll_size'],
			'slitting_status'=>'1'
		
		);	 
			
	}else if(!empty($slitting_details)){
		 $p_details= array(
			'job_name_id' => $slitting_details['job_id'],												
			'job_name_text' => $slitting_details['job_no'],			
			'job_name' => $slitting_details['job_name'],
			'slitting_status'=>$slitting_details['slitting_status'],
			'roll_code' => $roll_code,	
			'roll_size' =>$roll_size,
			'id' =>$slitting_details['roll_code_id'],
			
		);
		
		
	}
	else if(!empty($printing_details)){		
		 $p_details= array(
			'id'=>$printing_details['job_id'],	
			'job_name_id' => $printing_details['job_name_id'],												
			'job_name_text' => $printing_details['job_name_text'],		
			'job_name' => $printing_details['job_name'],
			'roll_code' => $printing_details['roll_code'],	
			'roll_size' => $printing_details['roll_size'],
			'slitting_status'=>'0'
		
		);	
	
	}
	else if(!empty($roll_details)){		
		 $p_details= array(
			'id'=>$roll_details['product_inward_id'],	
			'roll_code' => $roll_details['roll_no'],	
			'roll_size' => $roll_details['inward_size'],
			'slitting_status'=>'2'
		
		);	
	
	}
	//printr($p_details);
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
                <label class="col-lg-2 control-label"><span class="required">*</span>Slitting No</label>
                <div class="col-lg-2">
                <input type="hidden" name="edit_value" id="edit_value" value="<?php echo $edit;?>" />
                  	<input type="text" name="slitting_no" readonly="readonly" 
                    value="<?php echo isset($slitting_details['slitting_id'])?$slitting_details['slitting_id']:$slitting_latest_id+1;?>" class="form-control validate[required]">
                </div>
                 <label class="col-lg-2 control-label"><span class="required">*</span>Slitting Date</label>
                <div class="col-lg-2">
                  	<input type="text" name="slitting_date" id="slitting_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($slitting_details['slitting_date'])){ echo $slitting_details['slitting_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required] datepicker">
                </div>
                <label class="col-lg-2 control-label"><span class="required">*</span>Shift</label>
                <div class="col-lg-2">
                  	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="shift" id="day" value="Day" checked="checked" <?php if(isset($slitting_id) && ($slitting_details['shift'] == 'Day')) { echo 'checked=checked'; } ?>/> Day
                              </label>
                            
                                <label style="font-weight: normal;">
                                  	<input type="radio" name="shift" id="night" value="Night" <?php if(isset($slitting_id) && ($slitting_details['shift'] == 'Night')) { echo 'checked=checked'; }?> />Night
                              </label>
                      </div>
                </div>
              </div>
       
                <div class="form-group" id="job_div">
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
                <label class="col-lg-2 control-label"><span class="required">*</span>Slitting Operator Name</label>
                <div class="col-lg-3">
					<?php $operators = $obj_slitting->getOperator();
					//printr($slitting_details);?>
                    <select name="operator_id" id="operator_id" class="form-control validate[required]">
                    	<option value="">Select Operator</option>
							<?php
                            foreach($operators as $operator){ ?>
                                <option value="<?php echo $operator['employee_id']; ?>"
                                <?php if(isset($slitting_id) && ($operator['employee_id'] == $slitting_details['operator_id'])) { echo 'selected="selected"';}?> > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                <?php } ?> 
                     </select>
                  </div>

                <label class="col-lg-2 control-label"><span class="required">*</span> Junior Operator</label>
                <div class="col-lg-3">
					<?php $operators = $obj_slitting->getJuniorOperator();
					//printr($job_detail);?>
                    <select name="junior_id" id="junior_id" class="form-control ">
                    	<option value="">Select Operator</option>
							<?php
                            foreach($operators as $operator){ ?>
                                <option value="<?php echo $operator['employee_id']; ?>"<?php if(isset($slitting_id) && ($operator['employee_id'] == $slitting_details['junior_id'])) { echo 'selected="selected"';}?> 
                               > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                <?php } ?> 
                     </select>
                  </div>
              
            
              </div>	
                   <div class="form-group">
                   <label class="col-lg-2 control-label"><span class="required">*</span>Slitting Machine Name</label>
                <div class="col-lg-3">
                  	<?php $machines = $obj_slitting->getMachine();?>
                    <select name="machine_id" id="machine_id" class="form-control validate[required]">
                    	<option value="">Select Machine No</option>
							<?php
                            foreach($machines as $machine){ ?>
                                <option value="<?php echo $machine['machine_id']; ?>"
                                <?php if(isset($slitting_id) && ($machine['machine_id'] == $slitting_details['machine_id'])) { echo 'selected="selected"';}?> > <?php echo $machine['machine_name']; ?></option>
                                <?php } ?> 
                     </select>
                </div>
               </div>    	
              
			
				 <div class="line line-dashed m-t-large"></div>
					<div class="form-group"> 
					<div class="col-lg-1"></div>
							  
							 <div class="col-lg-6"id="myTable">
								<label class="col-lg-2 control-label">Slitting Roll Details</label> 
								<div class="col-lg-3">
									<section class="panel">
									  <div class="table-responsive sec_div">
										<table class="tool-row table-striped  b-t text-small" id="myTable" width="80%">
										  <thead>
											  <tr>
													
													<th ><span class="required">*</span>Roll No</th>
													<th ><span class="required">*</span>Input Qty</th>                                       
													<th ><span class="required">*</span>Output Qty<?php if($p_details['slitting_status']==2){echo " / Roll size";}?></th>                                       
													<th ></th>                                       
												   
											  </tr>
											
										  </thead>
										  <tbody>  
										   <?php 
										   
											 if(isset($slitting_roll_details)  && !empty($slitting_roll_details)){
														 $job_roll_details = $slitting_roll_details;
													 }
													 else
													 {   
														
														  
														 $job_roll_details[]= array(
															
															'roll_size' => '',	
															'r_input_qty' => '',	
															'r_output_qty' => '',	
															
															
																					
															
														);	 
													}
								//printr($job_roll_details);
							   if(!empty($job_roll_details)){
								$inner_count = 1;
								//$layer = 1;		   	
									foreach($job_roll_details  as $job_d){
									
								
														?>
																					  
										   <tr class="multiplerows-<?php echo $inner_count; ?> " id="multiplerows-<?php echo $inner_count; ?> ">
										 
													<input type="hidden"  name="roll_details[<?php echo $inner_count; ?>][slitting_material_id]" id="slitting_material_id" value='<?php echo isset($job_d['slitting_material_id'])?$job_d['slitting_material_id']:'';?>' />  
													<input type="hidden"  name="roll_details[<?php echo $inner_count; ?>][roll_details_id]" id="roll_details_id_<?php echo $inner_count; ?>" value='<?php echo isset($job_d['roll_code_id'])?$job_d['roll_code_id']:'';?>' />  
												
													<td>
													<input type="text" style="width:auto;" name="roll_details[<?php echo $inner_count; ?>][roll_code]" id="roll_code_<?php echo $inner_count; ?>" value="<?php echo isset($job_d['roll_code'])?$job_d['roll_code']:'';?>" class="form-control validate[required]" >
													</td>
												   
													<td>
													<input type="text" style="width:auto;" name="roll_details[<?php echo $inner_count; ?>][r_input_qty]" id="r_input_qty_<?php echo $inner_count; ?>" value="<?php echo isset($job_d['input_qty'])?$job_d['input_qty']:'';?>" class="form-control validate[required]" >
													</td>
													<td>
													<input type="text" style="width:auto;" name="roll_details[<?php echo $inner_count; ?>][r_output_qty]" id="r_output_qty_<?php echo $inner_count; ?>" value="<?php echo isset($job_d['output_qty'])?$job_d['output_qty']:'';?>" class="form-control validate[required]" >
													</td>
												   
												   
												 
										
												<td>
												  <?php if($inner_count==1  ){ 
											//  printr($edit);?>
													  <a  onclick="add_row(<?php echo $inner_count; ?>)"class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Roll" id="addmore_<?php echo $inner_count; ?>"><i class="fa fa-plus"></i></a>
												   
												<?php
												} else{?>
													<a onclick="Remove(<?php echo $inner_count; ?>,'<?php echo $job_d['slitting_material_id']; ?>','<?php echo $job_d['roll_code_id']; ?>')" data-original-title="Remove roll" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-minus"></i></a>
												<?php }?>       
												
												</td>
											
													
												
											
													        
											   </tr>
												  <?php $inner_count++; }} ?>         
										  </tbody>
										 </table>
										</div>
									   </section>
									  </div>
							 </div>
				<div class="col-lg-1"></div>
				 <div class="col-lg-4">
				 
				 <?php  if($p_details['slitting_status']==0){
							$label='Printing Roll Details';
							$l_roll='Roll NO';
						} else if($p_details['slitting_status']==1){
							$label='Lamination Roll ';
							$l_roll='Roll Code Details';
						} else if($p_details['slitting_status']==2){
							$label='Roll Details';
							$l_roll='Roll No';
						}	?>
				 
				 
				 
				 	<label class="col-lg-3 control-label"><?php echo $label;?></label> 
                    <div class="col-lg-4">
                        <section class="panel">
                          <div class="table-responsive">
                           <table class="table table-bordered" style="border:groove;">
							 <thead>
							   <tr>
									<th style="border:groove;"><?php echo $l_roll;?> </th>		
									<th style="border:groove;">Roll Size (MM)</th>
									
								</tr>
							</thead>
							  
							  
         
                              <tbody>

								
								<?php 
								
								 if($p_details['slitting_status']==0 && !empty($printing_roll_details)){
								
										?>

												
												<tr>
											
														<td >
														<input type="hidden" name="slitting_status" id="slitting_status" value="<?php echo $p_details['slitting_status'];?>  " />										
														<input type="hidden" name="roll_code_id" id="roll_code_id" value="<?php echo $p_details['id'];?>  " />
																
															<?php echo $printing_roll_details['roll_no'];?>                                         
														</td>
													   
														<td >
															<?php echo $printing_roll_details['inward_size'];?> 
														</td>
													   
												</tr>
											<?php }else{?>
													<tr>
												
															<td >
															<input type="hidden" name="slitting_status" id="slitting_status" value="<?php echo $p_details['slitting_status'];?>  " />										
															<input type="hidden" name="roll_code_id" id="roll_code_id" value="<?php echo $p_details['id'];?>  " />
																	
																<?php echo $p_details['roll_code'];?>                                         
															</td>
														   
															<td >
																<?php echo $p_details['roll_size'];?> 
															</td>
														   
													</tr>
														
											
										<?php }?>
                              </tbody>
                             </table>
                            </div>
                           </section>
                          </div>
				</div>				 
				 
				 </div>
		 <div class="line line-dashed m-t-large"></div>
            <div id="raw_material_table">  
          <!-- <label class="col-lg-2 control-label"></label>-->
            <div class="form-group">
					<label class="col-lg-2 control-label"> Wastage Details </label> 
                    <div class="col-lg-9">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small" id="myTable" width="100%">
                              <thead>
                                  <tr>
                                        
										<th><span class="required">*</span>Input Qty (Kgs)</th>
                                        <th><span class="required">*</span>Output Qty (Kgs)</th>
										<th>Setting  Wastage (Kgs)</th>
										<?php if($p_details['slitting_status']!=2){?>
										
                                        <th>Top Cut (Kgs)</th>									
										<th>Lamination Wastage(Kgs)</th>
										<th>Printing Wastage (Kgs)</th>
										<th>Trimming Wastage (Kgs)</th>
										<?php }?>
                                        <th><span class="required">*</span>Total Wastage (Kgs)</th>
                                        <th><span class="required">*</span>Wastage (%)</th>
                                  </tr>
                                
                              </thead>
                              <tbody>
                                                  
                               <tr class="multiplerows" id="multiplerows-<?php echo $inner_count; ?> "> 
                               <input type="hidden" id="min_arr" value='<?php echo json_encode($roll_no);?>' />                                       
						
                                    
                                         <td>
                                         <input type="text"  name="input_qty" id="input_qty" onchange="wastange_data()" value="<?php echo isset($slitting_details['input_qty'])?$slitting_details['input_qty']:'';?>" class="form-control ">
                                        </td>
                                         <td>
                                         <input type="text"  name="output_qty" id="output_qty" value="<?php echo isset($slitting_details['output_qty'])?$slitting_details['output_qty']:'';?>" class="form-control ">
                                        </td>
										  <td>
                                         <input type="text"  name="setting_wastage" id="setting_wastage" onchange="wastange_data()"  value="<?php echo isset($slitting_details['setting_wastage'])?$slitting_details['setting_wastage']:'';?>" class="form-control ">
                                        </td>
										<?php if($p_details['slitting_status']!=2){?>
										  <td>
                                         <input type="text"  name="top_cut_wastage" id="top_cut_wastage" onchange="wastange_data()"  value="<?php echo isset($slitting_details['top_cut_wastage'])?$slitting_details['top_cut_wastage']:'';?>" class="form-control ">
                                        </td>
										
										  <td>
                                         <input type="text"  name="lamination_wastage" id="lamination_wastage" onchange="wastange_data()"  value="<?php echo isset($slitting_details['lamination_wastage'])?$slitting_details['lamination_wastage']:'';?>" class="form-control ">
                                        </td>
										  <td>
                                         <input type="text"  name="printing_wastage" id="printing_wastage" onchange="wastange_data()"  value="<?php echo isset($slitting_details['printing_wastage'])?$slitting_details['printing_wastage']:'';?>" class="form-control ">
                                        </td>
										  <td>
                                         <input type="text"  name="trimming_wastage" id="trimming_wastage" onchange="wastange_data()" value="<?php echo isset($slitting_details['trimming_wastage'])?$slitting_details['trimming_wastage']:'';?>" class="form-control ">
                                        </td>
										<?php }?>
										  <td>
                                         <input type="text"  name="total_wastage" id="total_wastage" value="<?php echo isset($slitting_details['total_wastage'])?$slitting_details['total_wastage']:'';?>" class="form-control validate[required]" readonly>
                                        </td>
										  <td>
                                         <input type="text"  name="wastage" id="wastage" value="<?php echo isset($slitting_details['wastage'])?$slitting_details['wastage']:'';?>" class="form-control validate[required]" readonly>
                                        </td>
                                        
                                        
                                     
                                                
                                   </tr>
                                        
                                </tr>
                              </tbody>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>
                </div>
                
			  
			  <div class="form-group">
                <label class="col-lg-2 control-label">Remark</label>
               <div class="col-lg-4">
                    <select name="remarks_slitting" id="remarks_slitting" onchange="getRemark()"  class="form-control ">
                    	<option value="">Select Remark</option>
						
                                <option value="Cylinder Problem"<?php if(isset($slitting_id) && ($slitting_details['remarks_slitting'])=='Cylinder Problem') { echo 'selected="selected"';}?> >Cylinder Problem </option>
                                <option value="Ink Shade Problem"<?php if(isset($slitting_id) && ($slitting_details['remarks_slitting'])=='Ink Shade Problem') { echo 'selected="selected"';}?> >Ink Shade Problem </option>
                                <option value="Mechanical Problem"<?php if(isset($slitting_id) && ($slitting_details['remarks_slitting'])=='Mechanical Problem') { echo 'selected="selected"';}?> >Mechanical Problem </option>
                                <option value="Electrical Problem"<?php if(isset($slitting_id) && ($slitting_details['remarks_slitting'])=='Electrical Problem') { echo 'selected="selected"';}?> >Electrical Problem </option>
                           
                                <option value="Other"<?php if(isset($slitting_id) && ($slitting_details['remarks_slitting'])=='Other') { echo 'selected="selected"';}?> >Other </option>
                                
                     </select>
				  
				  
				
                </div>
				</div>
                <div class="form-group" id="remark">
                <label class="col-lg-2 control-label"></label>
               <div class="col-lg-4">
                     <textarea class="form-control" row="10" col="30" name="remark"><?php echo isset($job_detail['remark'])?$job_detail['remark']:'';?></textarea>		  
				  
				 
                </div>
				</div>
				
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
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
</style>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
	getRemark();
	var edit =$('#edit_value').val();
	//alert(edit);
	<?php  if(isset($_GET['roll_id']) && !empty($_GET['roll_id'])){
	?>
		$("#job_div").hide();
	<?php } if(isset($slitting_details) && $slitting_details['slitting_status']=='2'){?>
		$("#job_div").hide();
	<?php } ?>
	 jQuery("#form").validationEngine();
	 $("#slitting_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	
	
});

function wastange_data(){
		
	var input_qty = $('#input_qty').val();
	var setting_wastage = $('#setting_wastage').val();
	var top_cut_wastage = $('#top_cut_wastage').val();
	var lamination_wastage = $('#lamination_wastage').val();
	var printing_wastage = $('#printing_wastage').val();
	var trimming_wastage = $('#trimming_wastage').val();
	if(setting_wastage==""){
	setting_wastage=0;
	
	}
		<?php if($p_details['slitting_status']!=2){?>
					if(top_cut_wastage==""){
					top_cut_wastage=0;
					}
					if(lamination_wastage==""){
					lamination_wastage=0;
					}
					if(printing_wastage==""){
					printing_wastage=0;
					}
					if(trimming_wastage==""){
					trimming_wastage=0;
					}
					
				if( setting_wastage!='' || top_cut_wastage!=''||lamination_wastage!='' || printing_wastage!=''||trimming_wastage!='' ){
					//alert(input_qty+'input_qty'+setting_wastage+'setting_wastage'+top_cut_wastage+'top_cut_wastage'+lamination_wastage+'lamination_wastage'+printing_wastage+'printing_wastage'+trimming_wastage+'trimming_wastage' );
					
				//	total_wastage = input_qty+setting_wastage+top_cut_wastage+lamination_wastage+printing_wastage+trimming_wastage;
					$('#total_wastage').val(parseFloat(setting_wastage)+parseFloat(top_cut_wastage)+parseFloat(lamination_wastage)+parseFloat(printing_wastage)+parseFloat(trimming_wastage));
					//alert();
					total_wastage = $('#total_wastage').val();
					if(input_qty!='')		
						$('#wastage').val(((100*total_wastage)/input_qty).toFixed (2));	
				}
	 <?php }else{?>
		if( setting_wastage!=''){
			$('#total_wastage').val(parseFloat(setting_wastage));
					//alert();
					total_wastage = $('#total_wastage').val();
					if(input_qty!='')		
						$('#wastage').val(((100*total_wastage)/input_qty).toFixed (2));	
			}
	 <?php }?>
					

}




		function add_row(s_count) {
		
		count_layer = $("#layers").val();
		layer = count_layer -1;
		//var t_count = $("#myTable tr").length;	
	       var tab=$('#myTable tr:last').attr('id');
			var a=tab.split('-');
			//alert(a[1]);
			var t_count=(parseInt(a[1])+1);
		  //alert(tab);
		 // t_count=tab;
		   	// alert(t_count);
		
				 var html = '';		  
					 html +='<tr class="multiplerows-'+t_count+'" id="multiplerows-'+t_count+'"> ';
				
					
			  
					
					 html +='<td>';
						html +='<input type="text" style="width:auto;" name="roll_details['+t_count+'][roll_code]" id="roll_code_'+t_count+'" value="" class="form-control validate[required]" >';
					 html +='</td>';
					 html +='<td>';
								html +='<input type="text"  style="width:auto;" name="roll_details['+t_count+'][r_input_qty]" id="r_input_qty_'+t_count+'" value="" class="form-control validate[required]" >';
					 html +='</td>'; 
					 html +='<td>';
								html +='<input type="text"  style="width:auto;" name="roll_details['+t_count+'][r_output_qty]" id="r_output_qty_'+t_count+'" value="" class="form-control validate[required]" >';
					 html +='</td>';
					 html +='<td>';
				
					html+='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="" data-original-title="remove"><i class="fa fa-minus"></i></a></td>';
					html +='</td>';
					
					html +='</tr>';
				
		
		
				
				$('#myTable tr:last').after(html);
			
			
				$(' .remove').click(function(){
					$("#addmore").show();
					
					
					$(this).parent().parent().remove();
					total_quantity(t_count);
				});
			
								
	}
	function Remove(count,slitting_material_id,roll_code_id){
		//alert(count);
	
		alert(roll_code_id);
			$('.multiplerows-'+count).remove();
		var slitting_status=$("#slitting_status").val();
	
		if(slitting_material_id !=''){
			
			var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_roll', '',1);?>");
				$.ajax({
					url : remove_url,
					method : 'post',
					data : {slitting_material_id : slitting_material_id,slitting_status:slitting_status,roll_code_id:roll_code_id},
					success: function(response){
					
					
					},
					error: function(){
						return false;	
					}
			});	
		}
	}
	function getRemark(){
	var val =$('#remarks_slitting option:selected' ).val();	
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