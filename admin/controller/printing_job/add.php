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

if(isset($_GET['printing_id']) && !empty($_GET['printing_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$job_id = base64_decode($_GET['printing_id']);
		$job_detail = $obj_printing_job->getJobDetail($job_id);
		//printr($job_detail);
		$edit = 1;
	}
	
}else if(isset($_GET['printing_id']) && !empty($_GET['printing_id'])){
	
		$printing_id = base64_decode($_GET['printing_id']);
		$job_detail = $obj_printing_job->getJobDetail($printing_id);
			$edit = 1;
	
	

}
else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save_roll'])){
		$post = post($_POST);
		//printr($post);die;	//die;	
		$insert_id = $obj_printing_job->addJob($post);

//printe($insert_id);


		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '&mod=add&printing_id='.encode($insert_id), '',1));
	
		//$insert_id = $obj_printing_job->addJob($post);
	//	$obj_session->data['success'] = ADD;
		//page_redirect($obj_general->link($rout, '', '',1));

	//die;
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
	//	$job_id = base64_decode($_GET['job_id']);
	//	$obj_printing_job->updateJob($job_id,$post);
	//	$obj_session->data['success'] = UPDATE;
	//	page_redirect($obj_general->link($rout, '', '',1));
	}
	$job_latest_id=0;
	$latest_job_id = $obj_printing_job->getlatestjobid();
	if(!empty($latest_job_id))
		$job_latest_id=$latest_job_id;
	
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
          <header class="panel-heading "><b> <?php echo $display_name;?> Detail</b> </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-2 control-label"><span class="required">*</span>Printing Job No</label>
                <div class="col-lg-2">
                  	
					
					<input type="text" name="job_no" readonly="readonly" value="<?php echo isset($job_detail['job_no'])?$job_detail['job_no']:$job_latest_id+1;?>" class="form-control validate[required]">
                </div>
                 <label class="col-lg-2 control-label"><span class="required">*</span>Job Date</label>
                <div class="col-lg-2">
                  	<input type="text" name="job_date" id="job_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($job_detail['job_date'])){ echo $job_detail['job_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required] datepicker">
                </div>
             
              </div>
              
         <div class="form-group">
                <label class="col-lg-2 control-label"><span class="required">*</span>Job Name</label>
                <div class="col-lg-3">
                  	<input type="text"<?php if($edit==1){?>readonly<?php }?> name="job_name_text" id="job_name_text" value="<?php echo isset($job_detail['job_name_text'])?$job_detail['job_name_text']:'';?>" class="form-control validate[required]">
                    <input type="hidden" name="job_id" id="job_id" value="<?php echo isset($job_detail['job_name_id'])?$job_detail['job_name_id']:'';?>" />
                    <div id="ajax_response"></div>
                </div>
				<div class="col-lg-4">
                  	<input type="text" class="form-control " readonly name="job_name" id="job_name" value="<?php echo isset($job_detail['job_name'])?$job_detail['job_name']:'';?>"/>
                </div>
              </div>
			<div class="form-group ">
					<label class="col-lg-2 control-label">Job Type </label>
					<div class="col-lg-6">                
						<div  class="checkbox ch1" style="float:left;width: 200px;">
							<label  style="font-weight: normal;">
							  <input type="radio" name="job_type" id="roll_form" checked  value="roll_form"   <?php if(isset($job_id) && ($job_detail['job_type'] == 'roll_form')) { ?> checked="checked" <?php } ?> /> Roll Form
							
						  </label>
					  </div>
					   <div class="checkbox ch2" style="float:left;width: 200px;">
							<label  style="font-weight: normal;">
								<input type="radio" name="job_type" id="pouching" value="pouching"<?php if(isset($job_id) && ($job_detail['job_type']) == 'pouching') { ?> checked="checked" <?php } ?> />Pouching
						   </label>
					  </div>
				   
					</div>
				</div>
             <div class="form-group">
                <label class="col-lg-2 control-label">Job Start Time</label>
                <div class="col-lg-2">
                  	<input type="time" <?php if($edit==1){?>readonly<?php }?> name="start_time" value="<?php echo isset($job_detail['start_time'])?$job_detail['start_time']:'';?>" class="form-control ">
                </div>
                <label class="col-lg-3 control-label">Job End Time</label>
                <div class="col-lg-2">
                  	<input type="time" <?php if($edit==1){?>readonly<?php }?> name="end_time" value="<?php echo isset($job_detail['end_time'])?$job_detail['end_time']:'';?>" class="form-control ">
                </div>
              </div>
             
              <div class="form-group"> 
               
				  <label class="col-lg-2 control-label"><span class="required">*</span>Chemist Name</label>
						<div class="col-lg-3">
							<?php $chemist = $obj_printing_job->getChemistName();
						//	printr($job_detail);?>
							<select name="chemist_id" id="chemist_id" class="form-control validate[required]" <?php if($edit==1){?>readonly<?php }?> >
								<option value="">Select Chemist</option>
									<?php
									foreach($chemist as $ch){ ?>
										<option value="<?php echo $ch['employee_id']; ?>"
										<?php if(isset($job_id) && ($ch['employee_id'] == $job_detail['chemist_id'])) { echo 'selected="selected"';}?> > <?php echo $ch['first_name'].' '.$ch['last_name']; ?></option>
										<?php } ?> 
							 </select>
						  </div>
						   <label class="col-lg-2 control-label"><span class="required">*</span>Machine No</label>
							<div class="col-lg-3">
								<?php $machines = $obj_printing_job->getMachine();?>
								<select name="machine_id" id="machine_id" class="form-control validate[required]" <?php if($edit==1){?>readonly<?php }?>>
									
										<?php
										foreach($machines as $machine){ ?>
											<option value="<?php echo $machine['machine_id']; ?>"
											<?php if(isset($job_id) && ($machine['machine_id'] == $job_detail['machine_id'])) { echo 'selected="selected"';}?> > <?php echo $machine['machine_name']; ?></option>
											<?php } ?> 
								 </select>
							</div>
				  </div>

              <div class="form-group">
                <label class="col-lg-2 control-label">Remark</label>
               <div class="col-lg-4">
                    <select name="remaks_printing_job" id="remaks_printing_job" onchange="getRemark()"  class="form-control " <?php if($edit==1){?>readonly<?php }?>>
                    	<option value="">Select Remark</option>
						
                                <option value="Cylinder Problem"<?php if(isset($job_id) && ($job_detail['remaks_printing_job'])=='Cylinder Problem') { echo 'selected="selected"';}?> >Cylinder Problem </option>
                                <option value="Ink Shade Problem"<?php if(isset($job_id) && ($job_detail['remaks_printing_job'])=='Ink Shade Problem') { echo 'selected="selected"';}?> >Ink Shade Problem </option>
                                <option value="Mechanical Problem"<?php if(isset($job_id) && ($job_detail['remaks_printing_job'])=='Mechanical Problem') { echo 'selected="selected"';}?> >Mechanical Problem </option>
                                <option value="Electrical Problem"<?php if(isset($job_id) && ($job_detail['remaks_printing_job'])=='Electrical Problem') { echo 'selected="selected"';}?> >Electrical Problem </option>
                           
                                <option value="Other"<?php if(isset($job_id) && ($job_detail['remaks_printing_job'])=='Other') { echo 'selected="selected"';}?> >Other </option>
                                
                     </select>
				  
				  
				
                </div>
				</div>
                <div class="form-group" id="remark">
                <label class="col-lg-2 control-label"></label>
               <div class="col-lg-4">
                     <textarea class="form-control" row="10" col="30" name="remark"><?php echo isset($job_detail['remark'])?$job_detail['remark']:'';?></textarea>		  
				  
				 
                </div>
				</div> 
		
		   <section class="panel">
          <header class="panel-heading"><b> Operator Details </b></header>
          <div class="panel-body">
          
             <div class="form-group">
             	 <label class="col-lg-2 control-label"><span class="required">*</span>Operator Name</label>
              	  <div class="col-lg-3">
					<?php $operators = $obj_printing_job->getOperator();
					//printr($job_detail);?>
                    <select name="operator_id" id="operator_id" class="form-control validate[required]">
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
					<?php $operators = $obj_printing_job->getJuniorOperator();
					//printr($job_detail);?>
                    <select name="junior_id" id="junior_id" class="form-control ">
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
                                  <input type="radio" name="operator_shift" id="day" value="Day" checked="checked" /> Day
                              </label>
                            
                                <label style="font-weight: normal;">
                                  	<input type="radio" name="operator_shift" id="night" value="Night"  />Night
                              </label>
                      </div>
                </div>
                  <label class="col-lg-2 control-label"><span class="required">*</span> Date</label>
                <div class="col-lg-3">
                  	<input type="text" name="printing_date" id="printing_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($job_detail['job_date'])){ echo $job_detail['job_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required] datepicker">
                </div>
				  
                  
               </div>                  
              
                <div class="form-group">
					<label class="col-lg-2 control-label">Roll Details</label> 
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
                                        <th><span class="required">*</span>Output Qty (meter)</th>                                     
                                        <th><span class="required">*</span>Balance Qty (Kgs)</th>
										<th></th>
                                       
                                  </tr>
                                
                              </thead>
                              <tbody>  
                               <?php 
								
				 
					$inner_count = 1;
					//$layer = 1;		   	
			
						   $roll_no = $obj_printing_job->getRollNo();
					
											?>
										                                  
                               <tr class="multiplerows-<?php echo $inner_count; ?> " id="multiplerows-<?php echo $inner_count; ?> ">
							 
                              			<input type="hidden" id="min_arr" value='<?php echo json_encode($roll_no);?>'  />   
										  <input type="hidden" name="roll_details[<?php echo $inner_count; ?>][printing_roll_id]" id="printing_roll_id" value="" class="form-control validate[required]" >
                                    	<td colspan="2"> 
                                        	<select name="roll_details[<?php echo $inner_count; ?>][roll_no_id]" id="roll_no_id_<?php echo $inner_count; ?>" class="form-control validate[required] chosen_data select_choose" style="width:auto;" onchange="roll_detail_layer('<?php echo $inner_count; ?>','<?php echo $inner_count; ?>')">
                                                
                                                <?php
                                                  
                         							 foreach($roll_no as $rollno){ ?>
                  		                         <option value="<?php echo $rollno['product_inward_id']; ?>" id="option_<?php  echo $inner_count; ?>"   >
                  		                         	 <?php echo $rollno['roll_no'];?>
                  		                          	
                  		                          </option>
                                                        <?php } ?> 
                                             </select>
                                        </td>
                                        <td colspan="2">
                                             <input type="text" style="width:auto;" name="roll_details[<?php echo $inner_count; ?>][roll_name_id]" id="roll_name_id_<?php echo $inner_count; ?>" value="" class="form-control validate[required]" readonly="readonly">
                                        </td>
                                        <td>
                                         <input type="text"  name="roll_details[<?php echo $inner_count; ?>][film_size]" id="film_size_<?php echo $inner_count; ?>" value="" class="form-control validate[required]" readonly="readonly">
                                        </td>
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][input_qty]" id="input_qty_<?php echo $inner_count; ?>" value="" class="form-control validate[required,custom[number],min[0.001]]"></td>
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][output_qty]" id="output_qty_<?php echo $inner_count; ?>"  onchange="total_quantity(<?php echo $inner_count; ?>)" value="" class="form-control validate[required,custom[number],min[0.001]]"></td>
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][output_qty_m]" id="output_qty_m_<?php echo $inner_count; ?>"  onchange="total_quantity(<?php echo $inner_count; ?>)"value="" class="form-control "></td>
                                      
                                        <td><input type="text" name="roll_details[<?php echo $inner_count; ?>][balance_qty]" id="balance_qty_<?php echo $inner_count; ?>"   value="<?php echo isset($job_d['balance_qty'])?$job_d['balance_qty']:'';?>"		class="form-control "></td>
                                     
                           
                                    <td>
                                        <a  onclick="add_row(<?php echo $inner_count; ?>)"class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Profit" id="addmore_<?php echo $inner_count; ?>"><i class="fa fa-plus"></i></a>
                                    </td>
                                   
                                 
										       
                                   </tr>
                                         
                              </tbody>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>

             	  <div class="form-group">
                   <label class="col-lg-2 control-label"><span class="required">*</span> No Roll Used </label>
					<div class="col-lg-3">
						<input type="text" name="roll_used" id="roll_used" value="" class="form-control validate">
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
                                       
                                        <th colspan="2" style="text-align:center"><span class="required">*</span>Wastage (Kgs)</th>
                                       
									   <th><span class="required">*</span>Total Wastage (Kgs)</th>
										
                                        <th><span class="required">*</span>Wastage (%)</th>
                                  </tr>
                                  <tr>
                                  		
                                        <th style="text-align:center"><span class="required">*</span>Plain Wastage (Kgs)</th>
									   <th style="text-align:center"><span class="required">*</span>Print Wastage (Kgs)</th>
                                        <th colspan="2"></th>
                                  </tr>
                              </thead>
                              <tbody>                                    
                                <tr>
								
                                    	
                                         
                                        <td><input type="text" name="plain_wastage" onchange="total_quantity(<?php echo $inner_count; ?>)" id="plain_wastage" value="" class="form-control validate[required,custom[number],min[0.001]]"></td>
                                        <td><input type="text" name="print_wastage" onchange="total_quantity(<?php echo $inner_count; ?>)" id="print_wastage" value="" class="form-control validate[required,custom[number],min[0.001]]"></td>                                       
                                        <td><input type="text" name="total_wastage" id="total_wastage" readonly="readonly" value="" class="form-control validate[required]"></td>
                                        <td><input type="text" name="wastage_per" id="wastage_per" readonly="readonly" value="" class="form-control validate[required,custom[number],min[0.001]]"></td>
                                </tr>
                              </tbody>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>
  			 <div class="form-group" >
                <label class="col-lg-2 control-label"></label>
            	   <div class="col-lg-4">
                		<button type="submit" name="btn_save_roll" id="btn_save_roll" class="btn btn-inverse">Add Details </button>	
				 
               		 </div>
			</div>
             	
       
              <div class="form-group" style="display:none">
                <label class="col-lg-2 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($roll['status']) && $roll['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($roll['status']) && $roll['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>



              

          
   			
              <div class="form-group">
              	
              		    <?php    

              if(isset($_GET['printing_id']) && !empty($_GET['printing_id'])){
            //	printr($job_detail['printing_operator_array']);?>
             
				 <section class="panel">
		          <header class="panel-heading "> <b> Added Operator Detail</b> </header>
		          <div class="panel-body">
					   		   
			        	<table class="table table-bordered">
			            <thead>
			                <tr>
			                    <th>Operator Name</th>
			                    <th>Roll Details</th>
			                    <th>Total Input</th>
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
			   	
			            foreach($job_detail['printing_operator_array'] as $details ) {

			            	$roll_details= $obj_printing_job->getRollDetails($details['printing_operator_id']);									

			             ?>

						
			                <input type="hidden" name="printing_operator_id" value="<?php echo $details['printing_operator_id']; ?>"  />
			                 <input type="hidden" name="printing_id" value="<?php echo decode($_GET['printing_id']); ?>"  />
			              
			               <td><?php echo $details['operator_name'];?></td>
			                 <td><?php echo $roll_details['roll_no'];?></td>
			                 <td><?php echo $roll_details['total_input'];?></td>
			                 <td><?php echo $roll_details['total_output'];?></td>
			               <td><?php echo $details['plain_wastage'];?></td>			            
			               <td><?php echo $details['print_wastage'];?></td>
			               <td><?php echo $details['total_wastage'];?></td>
			               <td><?php echo $details['wastage_per'];?> %</td>
			             
			            
							  <td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice(<?php echo $details['printing_operator_id'].','.decode($_GET['printing_id']); ?>)"><i class="fa fa-trash-o"></i></a>
			                	
			                  </td>
							  </tr>
			                  <!-- CONFIRMATION ALERT BOX -->
			                    <div class="modal fade" id="alertbox_<?php echo $details['printing_operator_id']; ?>">
			                        <div class="modal-dialog">
			                            <div class="modal-content">
			                                <div class="modal-header">
			                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			                                    <h4 class="modal-title">Title</h4>
			                                </div>
			                                <div class="modal-body">
			                                    <p id="setmsg">Message</p>
			                                </div>
			                                <div class="modal-footer">
			                                    <button type="button" id="popbtncan" class="btn btn-default" data-dismiss="modal">Close</button>
			                                    <button type="button" name="popbtnok" id="popbtnok_<?php echo $details['printing_operator_id']; ?>" 
			                                    class="btn btn-primary">Ok</button>
			                                 
			                                </div>
			                            </div><!-- /.modal-content -->
			                        </div><!-- /.modal-dialog -->
			                    </div>
			                    <!-- END OF CONFIRMATION BOX-->
						<?php }?>
			    
		 			 </tbody></table>
				
   			


             <?php  }

              ?>


              </div>
              <div class="form-group">
	                <div class="col-lg-9 col-lg-offset-3">
	                <?php if($edit){?>

	                    <a class="btn btn-primary" href="<?php echo $obj_general->link($rout, '', '',1);?>">Update</a>
	                     <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
	                <?php } else { ?>
	                	
	                <?php } ?>  
	              
	                </div>
              </div>
    			</div>

              </div>

            </form>
          </div>
        </section>

      </section> 
             
        
        
      </div>
    </div> 
    </div>
    </section>
  
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
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
	
	 jQuery("#form").validationEngine();
	 getRemark();
	 $(".chosen_data").chosen();
	
	  roll_detail_layer(1,1);
	
	 $("#job_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 $("#printing_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
});
$(".chosen-select").chosen();

$("#job_name_text").focus();
	var offset = $("#product_item_id").offset();
	var width = $("#holder").width();
	$("#ajax_response").css("width",width);
	
	$("#job_name_text").keyup(function(event){		
		 var keyword = $("#job_name_text").val();
		 if(keyword.length)
		 {	
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=job_detail', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_url,
				   data: "job="+keyword,
				   success: function(msg){	
				 var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{ 	
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' job_no ="'+msg[i].job_no+'" id="'+msg[i].job_id+'" job_name="'+msg[i].job_name+'" ><span class="bold" >'+msg[i].job_no+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
				
					if(msg != 0)
					  $("#ajax_response").fadeIn("slow").html(div);
					else
					{
						$("#ajax_response").fadeIn("slow");	
						$("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
				  		$("#job_id").val('');
				  		$("#job_name").val('');
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {				
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");										
					  }
					  else
						$(".list li:first").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#job_name_text").val($(".list li[class='selected'] a").text());
							$("#job_id").val($(".list li[class='selected'] a").attr("id"));
							$("#job_name").val($(".list li[class='selected'] a").attr("job_name"));
						}
				}
				 break;
				 case 38:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$(".list li:last").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#job_name_text").val($(".list li[class='selected'] a").text());
							$("#job_id").val($(".list li[class='selected'] a").attr("id"));
							$("#job_name").val($(".list li[class='selected'] a").attr("job_name"));
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		 }
	});
	
	$('#job_name_text').keydown( function(e) {
		if (e.keyCode == 9) {
			 $("#ajax_response").fadeOut('slow');
			 $("#ajax_response").html("");
		}
	});

	$("#ajax_response").mouseover(function(){
				$(this).find(".list li a:first-child").mouseover(function () {
					  $("#job_id").val($(this).attr("id"));
					  $("#job_name").val($(this).attr("job_name"));
					  $(this).addClass("selected");
				});
				$(this).find(".list li a:first-child").mouseout(function () {
					  $(this).removeClass("selected");
					  $("#job_id").val('');
					  $("#job_name").val('');
				});
				$(this).find(".list li a:first-child").click(function () {
					  $("#job_id").val($(this).attr("id"));
					  $("#job_name").val($(this).attr("job_name"));
					  
					  $("#job_name_text").val($(this).text());
					 $("#ajax_response").fadeOut('slow');
					  $("#ajax_response").html("");
					  
					
				});
				
			});
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
		var arr = jQuery.parseJSON($('#min_arr').val());
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
							  
							 html +='<input type="text" name="roll_details['+t_count+'][input_qty]" id="input_qty_'+t_count+'" value="" class="form-control validate[required,custom[number],min[0.001]]">';

					 html +='</td>';
					 html +='<td>';
							 html +='<input type="text" name="roll_details['+t_count+'][output_qty]"  onchange="total_quantity('+t_count+')" id="output_qty_'+t_count+'" value="" class="form-control validate[required,custom[number],min[0.001]">';
					 html +='</td>';
					 html +='<td>';
							 html +='<input type="text" name="roll_details['+t_count+'][output_qty_m]"  id="output_qty_m_'+t_count+'" value="" class="form-control">';
					 html +='</td>';
				   
					 html +='<td>';
							 html +='<input type="text" name="roll_details['+t_count+'][balance_qty]"  id="balance_qty_'+t_count+'"value="" class="form-control ">';
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
	function Remove(count,roll_id){
		//alert(count);
	
		//alert(roll_id);
			$('.multiplerows-'+count).remove();
		total_quantity(count);
		if(roll_id !=''){
			
			var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_roll', '',1);?>");
				$.ajax({
					url : remove_url,
					method : 'post',
					data : {roll_id : roll_id},
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
			
			//$(roll_used).val(t_count);

		//	roll_used=$(roll_used).val();
			//	alert(roll_used);
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
				var t_val=(parseFloat(i_sum)-(parseFloat(sum)+ parseFloat(total_was)).toFixed (2));
				//alert(t_val);
				$('#balance_qty_'+t_count).val((t_val.toFixed (2)));			
				var balance_qty = $('#balance_qty_'+t_count).val();
				//alert(balance_qty);
			}


		}

	
	function getRemark(){
	var val =$('#remaks_printing_job option:selected' ).val();	
		if(val=='Other'){
			$("#remark").show();
		}else{
			$("#remark").val('');
			$("#remark").hide();
		}

	}
	function removeInvoice(printing_operator_id,printing_id){
		$("#alertbox_"+printing_operator_id).modal("show");
		$(".modal-title").html("Delete Record".toUpperCase());
		$("#setmsg").html("Are you sure you want to delete ?");
		$("#popbtnok_"+printing_operator_id).click(function(){
			var remove_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeInvoice', '',1);?>");
			$.ajax({
				url : remove_invoice_url,
				method : 'post',
				data : {printing_operator_id : printing_operator_id},
				success: function(response){
					if(response == 0) {			
					$("#alertbox_"+printing_operator_id).hide();
				}
					$("#alertbox_"+printing_operator_id).hide();
					$("#alertbox_"+printing_operator_id).modal("hide");
					$('#proforma_invoice_id_'+printing_operator_id).html('');
					set_alert_message('Layer  Record successfully deleted','alert-success','fa fa-check');
					location.reload();
					window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=printing_job&mod=add&printing_id='+encode(printing_id)+'';
					},
				error: function(){
					return false;	
				}
			});
$("#alertbox_"+printing_operator_id).hide();
$("#alertbox_"+printing_operator_id).modal("");
 });
}
	
</script>
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>