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

if(isset($_GET['pouching_id']) && !empty($_GET['pouching_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$pouching_id = base64_decode($_GET['pouching_id']);
		$pouching_details = $obj_pouching->getPouchDetail($pouching_id);
			$slitting_details = $obj_slitting->getJobDetail($pouching_details['slitting_id']);
//	printr($slitting_details);//die;
		$job_details = $obj_pouching->getJob_layer_details($slitting_details['job_id']);
		$Rollcode_details = $obj_pouching->getLamination_details($slitting_details['roll_code_id']);

//	printr($pouching_details);
	
		
	
		$edit = 1;
	} 
	
}
else if(isset($_GET['slitting_id']) && !empty($_GET['slitting_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$slitting_id = base64_decode($_GET['slitting_id']);
		$slitting_details = $obj_slitting->getJobDetail($slitting_id);
//	printr($slitting_details);//die;
		$job_details = $obj_pouching->getJob_layer_details($slitting_details['job_id']);
		$Rollcode_details = $obj_pouching->getLamination_details($slitting_details['roll_code_id']);
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
		$insert_id = $obj_pouching->addpouching($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		
		$slitting_id = base64_decode($_GET['pouching_id']);
		$obj_pouching->updatepouching($slitting_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	$slitting_latest_id=0;
	$latest_slitting_id = $obj_pouching->getlatestpouchingid();
	if(!empty($latest_slitting_id))
	$slitting_latest_id=$latest_slitting_id;

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
                <label class="col-lg-2 control-label"><span class="required">*</span>Pouching No</label>
                <div class="col-lg-2">
                <input type="hidden" name="edit_value" id="edit_value" value="<?php echo $edit;?>" />
                <input type="hidden" name="job_id" id="job_id" value="<?php echo $slitting_details['job_id'];?>" />
                <input type="hidden" name="slitting_id" id="slitting_id" value="<?php echo $slitting_details['slitting_id'];?>" />
              
                  	<input type="text" name="pouching_no" readonly="readonly" 
                    value="<?php echo isset($pouching_details['pouching_id'])?$pouching_details['pouching_id']:$slitting_latest_id+1;?>" class="form-control validate[required]">
                </div>
                 <label class="col-lg-2 control-label"><span class="required">*</span>Pouching Date</label>
                <div class="col-lg-2">
                  	<input type="text" name="pouching_date" id="pouching_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($pouching_details['pouching_date'])){ echo $pouching_details['pouching_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required] datepicker">
                </div>
                <label class="col-lg-2 control-label"><span class="required">*</span>Shift</label>
                <div class="col-lg-2">
                  	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="shift" id="day" value="Day" checked="checked" <?php if(isset($pouching_id) && ($pouching_details['shift'] == 'Day')) { echo 'checked=checked'; } ?>/> Day
                              </label>
                            
                                <label style="font-weight: normal;">
                                  	<input type="radio" name="shift" id="night" value="Night" <?php if(isset($pouching_id) && ($pouching_details['shift'] == 'Night')) { echo 'checked=checked'; }?> />Night
                              </label>
                      </div>
                </div>
              </div> 
       
             
              <div class="form-group">
                <label class="col-lg-2 control-label"><span class="required">*</span>Pouching Operator Name</label> 
                <div class="col-lg-3">
					<?php $operators = $obj_pouching->getOperator();
					//printr($pouching_details);?>
                    <select name="operator_id" id="operator_id" class="form-control validate[required]">
                    	<option value="">Select Operator</option>
							<?php
                            foreach($operators as $operator){ ?>
                                <option value="<?php echo $operator['employee_id']; ?>"
                                <?php if(isset($pouching_id) && ($operator['employee_id'] == $pouching_details['operator_id'])) { echo 'selected="selected"';}?> > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                <?php } ?> 
                     </select>
                  </div>

                  <label class="col-lg-2 control-label"><span class="required">*</span> Junior Operator</label>
                <div class="col-lg-3">
					<?php $operators = $obj_pouching->getJuniorOperator();
					//printr($job_detail);?>
                    <select name="junior_id" id="junior_id" class="form-control validate[required]">
                    	<option value="">Select Operator</option>
							<?php
                            foreach($operators as $operator){ ?>
                                <option value="<?php echo $operator['employee_id']; ?>"<?php if(isset($pouching_id) && ($operator['employee_id'] == $pouching_details['junior_id'])) { echo 'selected="selected"';}?> 
                               > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                <?php } ?> 
                     </select>
                  </div>
              
            
                 
               </div>    	
              
			  <div class="form-group">
                 <label class="col-lg-2 control-label"><span class="required">*</span>Pouching Machine Name</label>
                <div class="col-lg-3">
                  	<?php $machines = $obj_pouching->getMachine();?>
                    <select name="machine_id" id="machine_id" class="form-control validate[required]">
                    	<option value="">Select Machine No</option>
							<?php
                            foreach($machines as $machine){ ?>
                                <option value="<?php echo $machine['machine_id']; ?>"
                                <?php if(isset($pouching_id) && ($machine['machine_id'] == $pouching_details['machine_id'])) { echo 'selected="selected"';}?> > <?php echo $machine['machine_name']; ?></option>
                                <?php } ?> 
                     </select>
                </div>
              </div>
             
			  <div class="form-group">
                <label class="col-lg-2 control-label">Job Start Time</label>
                <div class="col-lg-2">
                  	<input type="time" name="start_time" value="<?php echo isset($pouching_details['start_time'])?$pouching_details['start_time']:'';?>" class="form-control ">
                </div>
                <label class="col-lg-3 control-label">Job End Time</label>
                <div class="col-lg-2">
                  	<input type="time" name="end_time" value="<?php echo isset($pouching_details['end_time'])?$pouching_details['end_time']:'';?>" class="form-control" >
                </div>
              </div>
            
            
		
		
					
		
            <div class="form-group">
					<label class="col-lg-2 control-label"> Job Cart Details </label> 
                    <div class="col-lg-10">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="table table-striped datagrid m-b-small"  width="100%">
                              <thead id="first">
                                  <tr>
                                       
                                        <th>Job No </th>
                                        <th>Job Name </th>
										<th>Product name</th>
										<th>No of Rolls</th>
										<th>Lamination Roll No</th>
										<th>Slitting Output Qty(KGS & Meter)</th>
                                  </tr>                                
                              </thead>
                              <tbody>                                                  
                               <tr >                                     
                                         <td><?php echo $job_details['job_no']?></td>
                                         <td><?php echo $job_details['job_name']?></td>
                                         <td><?php echo $job_details['product_name']?></td>
                                         <td><?php echo $slitting_details['no_of_roll']?></td>
                                         <td><?php echo $Rollcode_details['roll_code']?></td>
                                         <td><?php echo $slitting_details['output_qty']?>(Kgs) <br><?php echo $slitting_details['output_qty_m']?> (Meter) </td>
                                      
                                      
                                   </tr>
                                </tr>
                              </tbody>
                             </table>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>    
             	 <div class="form-group">
					<label class="col-lg-2 control-label"> Zipper Details </label> 
                    <div class="col-lg-4">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small"  width="100%">
                              <thead id="first">
                                  <tr>
                                       
                                        <th><span class="required">*</span>Zipper </th>
										<th>Used(meter)</th>
										<th>Used(kg)</th>
										
                                  </tr>                                
                              </thead>
                              <tbody>                                                  
                               <tr >                                     
                                         <td>
										<select name="zipper_id" id="zipper_id" class="form-control validate[required] " >
												<option value="0">Select Zipper	</option>
														
												<?php
												$zipper_details = $obj_pouching->getzipper_details();
											//	printr($zipper_details);
												foreach($zipper_details as $zipper){ ?>
													
														<option value="<?php echo $zipper['product_item_id']; ?>" <?php if(isset($pouching_id) && ($zipper['product_item_id'] == $pouching_details['zipper_id'])) { echo 'selected="selected"';}?>>
														<?php echo $zipper['product_name'];?>
														</option>
												<?php } ?> 
											 </select>
										  <td>
											<input type="text"  id="zipper_used"name="zipper_used" value="<?php echo isset($pouching_details['zipper_used'])?$pouching_details['zipper_used']:'';?>" class="form-control ">
                                        </td>
										<td>
											<input type="text"  id="zipper_used_kg"name="zipper_used_kg" value="<?php echo isset($pouching_details['zipper_used_kg'])?$pouching_details['zipper_used_kg']:'';?>" class="form-control ">
                                        </td>   
                                   </tr>
                                </tr>
                              </tbody>
                             </table>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>   
				
				 <div class="form-group">
					<label class="col-lg-2 control-label"> Wastage Details </label> 
                    <div class="col-lg-9">
                        <section class="panel">
                          <div class="table-responsive">
                            <table class="tool-row table-striped  b-t text-small" id="myTable" width="100%">
                              <thead id="first">
                                  <tr>
                                  		<th>Input Qty (kgs)</th>
                                  		<th>Output Qty (kgs)</th>
                                        <th>Output Qty (Nos)</th> 
										<th>Output Qty (meter)</th>
										<th>Online  Wastage (Kgs)</th>
										<th>Lamination  Wastage (Kgs)</th>
									
										 <th><span class="required">*</span>Total Wastage (Kgs)</th>
									
                                        <th><span class="required">*</span> Operator Wastage (%)</th>	
                                  </tr>                               
                              </thead>
                              <tbody>
                                                  
                               <tr > 
                               			<td>
                                         <input type="text"  name="input_qty" id="input_qty"   value="<?php echo isset($pouching_details['input_qty'])?$pouching_details['input_qty']:'';?>" class="form-control ">
                                        </td>	
                                        <td>
                                         <input type="text"  name="output_qty_kg" id="output_qty_kg" onchange="wastange_data()"   value="<?php echo isset($pouching_details['output_qty_kg'])?$pouching_details['output_qty_kg']:'';?>" class="form-control ">
                                        </td>
										<td>
                                         <input type="text"  name="output_qty" id="output_qty" value="<?php echo isset($pouching_details['output_qty'])?$pouching_details['output_qty']:'';?>" class="form-control ">
                                        </td>										
										<td>
                                         <input type="text"  name="output_qty_meter" id="output_qty_meter" value="<?php echo isset($pouching_details['output_qty_meter'])?$pouching_details['output_qty_meter']:'';?>" class="form-control ">
                                        </td>
										  <td>
                                         <input type="text"  name="online_setting_wastage" id="online_setting_wastage"  onchange="wastange_data()"  value="<?php echo isset($pouching_details['online_setting_wastage'])?$pouching_details['online_setting_wastage']:'';?>" class="form-control ">
                                        </td>  <td>
                                         <input type="text"  name="lamination_wastage" id="lamination_wastage"    value="<?php echo isset($pouching_details['lamination_wastage'])?$pouching_details['lamination_wastage']:'';?>" class="form-control ">
                                        </td>
									
                                         <td>
                                         <input type="text"  name="total_wastage" id="total_wastage"   value="<?php echo isset($pouching_details['total_wastage'])?$pouching_details['total_wastage']:'';?>" class="form-control" > 
                                         </td>
                                        
										 <td>
                                         <input type="text"  name="operator_wastage" id="operator_wastage"  value="<?php echo isset($pouching_details['operator_wastage'])?$pouching_details['operator_wastage']:'';?>" class="form-control" >
                                        </td>
									  
                                   </tr>
                                </tr>
                              </tbody>
                             </table>
                             </table>
                            </div>
                           </section>
                          </div>
             	 </div>
             
		<div class="form-group">
			<label class="col-lg-2 control-label">Remark</label>
		   <div class="col-lg-4">
				<select name="remark_pouching" id="remark_pouching" onchange="getRemark()"  class="form-control ">
					<option value="">Select Remark</option>
					
							<option value="Cylinder Problem"<?php if(isset($pouching_id) && ($pouching_details['remark_pouching'])=='Cylinder Problem') { echo 'selected="selected"';}?> >Cylinder Problem </option>
							<option value="Ink Shade Problem"<?php if(isset($pouching_id) && ($pouching_details['remark_pouching'])=='Ink Shade Problem') { echo 'selected="selected"';}?> >Ink Shade Problem </option>
							<option value="Mechanical Problem"<?php if(isset($pouching_id) && ($pouching_details['remark_pouching'])=='Mechanical Problem') { echo 'selected="selected"';}?> >Mechanical Problem </option>   
							<option value="Electrical Problem"<?php if(isset($pouching_id) && ($pouching_details['remark_pouching'])=='Electrical Problem') { echo 'selected="selected"';}?> >Electrical Problem </option>
							<option value="Other"<?php if(isset($pouching_id) && ($pouching_details['remark_pouching'])=='Other') { echo 'selected="selected"';}?> >Other </option>
							
				 </select>
			  
			  
			</div>
		</div>
	 <div class="form-group" id="remark">
			<label class="col-lg-2 control-label"></label>
		   <div class="col-lg-4">
				 <textarea class="form-control" row="10" col="30" name="remark"><?php echo isset($pouching_details['remark'])?$pouching_details['remark']:'';?></textarea>	
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
		 </div>
       </section>       
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<!-- Start : validation script -->
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
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
#first {
		font-size:14px;
	}
</style>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
	getRemark();
	 
	 // getJobRollDetail1s();

	 $(".chosen_data").chosen();
	  $(".chosen_data_roll").chosen();
	 jQuery("#form").validationEngine();
	 $("#slitting_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	$(".chosen-select").chosen({
		no_results_text: "Oops, nothing found!"
	});
	

});

	function getRemark(){
	var val =$('#remark_pouching option:selected' ).val();	
		if(val=='Other'){
			$("#remark").show();
		}else{
			$("#remark").val('');
			$("#remark").hide();
		}


	}
	
	
	function getJobRollDetails(){
		
		
		var val=$("form.form-horizontal .chosen-select").val();	
		//alert(val);
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=input_roll_details', '',1);?>");	
			$.ajax({
				type: "POST",
				url: url,		
				data:{val:val}, 
				success: function(response) {	
				
					 $("#input_roll").html(response);
					
					
				}	
			});
	}
function getROllDetail(count){
		
		
//	var val =$('#roll_no_id_'+count+' option:selected' ).val();
	var slitting_material_id = $('#roll_no_'+count+' option:selected' ).val();
	//	alert(slitting_material_id);
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getROllDetail', '',1);?>");	
			$.ajax({
				type: "POST",
				url: url,		
				data:{slitting_material_id:slitting_material_id}, 
				success: function(response) {	
						
				//console.log(response);
					var msg = $.parseJSON(response);	
					
					$('#roll_input_qty_'+count).val(msg.p_input_qty);
					
					
					
					
				}	
			});
	}

function add_row(s_count) {
		
		count_layer = $("#layers").val();
		layer = count_layer -1;
		//var t_count = $("#myTable tr").length;	
	       var tab=$('#sl_roll tr:last').attr('id');
			var a=tab.split('-');
			//alert(a[1]);
			var t_count=(parseInt(a[1])+1);
			var arr = jQuery.parseJSON($('#roll_array').val());
				 var html = '';		  
					 html +='<tr class="multiplerows-'+t_count+'" id="multiplerows-'+t_count+'"> ';
				
					
			  
					 html +='<td width="50%"> ';
							 html+='<select name="roll_details['+t_count+'][roll_no]" id="roll_no_'+t_count+'" class="form-control validate[required] chosen_data_roll select_choose"  onchange="getROllDetail('+t_count+')">';
							  html +='<option value="0"> Select Roll</option>';
								for(var i=0;i<arr.length;i++)
										{
										html +='<option value='+arr[i].slitting_material_id +'>'+arr[i].roll_code +'</option>';
										}

							 html +='</select>';
						 
					 html +='</td>';
					 html +='<td  width="15%">';
							  
							 html +='<input type="text"  readonly name="roll_details['+t_count+'][roll_input_qty]" id="roll_input_qty_'+t_count+'" value="" class="form-control ">';

					 html +='</td>';
					 	 html +='<td  width="15%">';
							  
							 html +='<input type="text" name="roll_details['+t_count+'][roll_output_qty]" id="roll_output_qty_'+t_count+'" value="" class="form-control ">';


					 html +='</td>';
					 html +='<td  width="10%">';
							  
							 html +='<input type="text" name="roll_details['+t_count+'][roll_bal_qty]" id="roll_bal_qty_'+t_count+'" value="" class="form-control ">';

					 html +='</td>';
					
					 html +='<td  width="10%">';
				
					html+='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="" data-original-title="remove"><i class="fa fa-minus"></i></a></td>';
					html +='</td>';
					
					html +='</tr>';
				
		
		
				
				$('#sl_roll tr:last').after(html);
				$(".chosen-select").chosen();
		
				$(' .remove').click(function(){
					$("#addmore").show();
					
					
					$(this).parent().parent().remove();
					total_quantity(t_count);
				});
			
								
	}
	function wastange_data(){
		
	var output_qty_kg = $('#output_qty_kg').val();
	var online_setting_wastage= $('#online_setting_wastage').val();
	var sorting_wastage= $('#sorting_wastage').val();
	var top_cut_wastage = $('#top_cut_wastage').val();
	var lamination_wastage = $('#lamination_wastage').val();
	var printing_wastage = $('#printing_wastage').val();
	var trimming_wastage = $('#trimming_wastage').val();
	var zipper_used_kg = $('#zipper_used_kg').val();
					if(online_setting_wastage==""){
					online_setting_wastage=0;					
					}		
					if(top_cut_wastage==""){
					top_cut_wastage=0;
					}
					if(sorting_wastage==""){
					sorting_wastage=0;
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
					if(zipper_used_kg==""){
					zipper_used_kg=0;
					}
					
				if( online_setting_wastage!='' || top_cut_wastage!=''||lamination_wastage!='' || printing_wastage!='' || sorting_wastage!=''||trimming_wastage!='' ||zipper_used_kg!=''  ){
					//alert(input_qty+'input_qty'+setting_wastage+'setting_wastage'+top_cut_wastage+'top_cut_wastage'+lamination_wastage+'lamination_wastage'+printing_wastage+'printing_wastage'+trimming_wastage+'trimming_wastage' );
					
				//	total_wastage = input_qty+setting_wastage+top_cut_wastage+lamination_wastage+printing_wastage+trimming_wastage;
					$('#total_wastage_c').val(parseFloat(online_setting_wastage)+parseFloat(top_cut_wastage)+parseFloat(sorting_wastage)+parseFloat(lamination_wastage)+parseFloat(printing_wastage)+parseFloat(trimming_wastage)+parseFloat(zipper_used_kg));
				
					total_wastage = $('#total_wastage_c').val();
					//	alert(total_wastage);
					if(output_qty_kg!='')		
						$('#total_wastage').val(((100*total_wastage)/output_qty_kg).toFixed (2));	
						$('#operator_wastage').val(((100*online_setting_wastage)/output_qty_kg).toFixed (2));	
				}
	
					

}
	
</script>
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>