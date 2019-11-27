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
if(isset($_GET['task_management_id']) && !empty($_GET['task_management_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$task_management_id = base64_decode($_GET['task_management_id']);
		$task = $obj_source->get_task_data($task_management_id);
		
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit


if($display_status){
	//insert 
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$insert_id = $obj_source->addTask($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$task_management_id = base64_decode($_GET['task_management_id']);
		$obj_source->updateTask($task_management_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout,'','',1));
		
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
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="" id="frm_add" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Task Name</label>
                <div class="col-lg-8">
                  	<input type="text" name="task_name" id="task_name" placeholder="Task Name	" value="<?php echo isset($task['task_name'])?$task['task_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
             <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Priority</label>
                <div class="col-lg-8">
                  	<select name="priority" value="" class="form-control validate[required]">
					
					<option value="">Select Priority Type</option>
					<option value="Medium" <?php if (isset($task)) { if ($task['priority']=='Medium'){echo 'selected';}} ?>>Medium</option>
					<option value="High" <?php if (isset($task)) { if ($task['priority']=='High'){echo 'selected';}} ?>>High</option>
					<option value="Urgent" <?php if (isset($task)) { if ($task['priority']=='Urgent'){echo 'selected';}} ?>>Urgent</option>
			
					</select>
                </div>
              </div>
			   
			    <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Start From</label>
                <div class="col-lg-3">
                	<input type="text" name="start_date"  value="<?php echo isset($task['start_date'])?$task['start_date']:'';?>" placeholder="Start Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="f_date"/>
                    </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Due To</label>
                <div class="col-lg-3">
                  <input type="text" name="due_date"  value="<?php echo isset($task['due_date'])?$task['due_date']:''?>" placeholder="Due Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"  />
                </div>
              </div>
           

			   
			
			  
			<div class="form-group" id="assign_to">
                <label class="col-lg-3 control-label"><span class="required">*</span> Assign To</label>
                <div class="col-lg-4">
				<?php $employeename = $obj_source->getEmployeeName();
						//print_r($customername);die;		
					?>
					
                    <select name="assigned_to_user_id" id="customer_name" class="form-control validate[required]">
                    	<option value="">Select Employee</option>
                        <?php 
						foreach($employeename as $cs) {
						
											?>
											
												<option value="<?php echo $cs['employee_id']; ?>" <?php if (isset($task)) { if ($task['assign_to_user_id']==$cs['employee_id']){echo 'selected';}} ?>> <?php echo $cs['user_name']; ?></option>
											
									<?php	} ?> 
									
                     </select>
                </div>
              </div>	
			  
			  
			   
			  <?php if($edit==1){  ?>
			 
			  
			 
			  
			  	<div class="form-group" id="task_transfer" style="display:none;">
                <label class="col-lg-3 control-label"><span class="required">*</span> Transfer To</label>
                <div class="col-lg-4">
				<?php $employeename = $obj_source->getEmployeeName();
						//print_r($customername);die;		
					?>
					
                    <select name="transfer_to_user_id" id="customer_name" class="form-control validate[required]">
                    	<option value="">Select Employee</option>
                        <?php 
						foreach($employeename as $cs) {
						
											?>
											
												<option value="<?php echo $cs['employee_id']; ?>"> <?php echo $cs['user_name']; ?></option>
											
									<?php	} ?> 
									
                     </select>
                </div>
              </div>		
			   
			 
			  <?php } ?>			  
			  
             

				<div class="form-group">
                <label class="col-lg-3 control-label">Shared To</label>
                <div class="col-lg-8">
                	
                   
                	<div class="form-control scrollbar scroll-y" style="height:200px" id="groupbox">
					
                        <?php														
					
					 $employeename = $obj_source->getEmployeeName();
					
                        foreach($employeename as $employee){ ?>
						
							<div class="checkbox">
							<label class="checkbox-custom">
								<input type="checkbox" name="shared_task_user_id[]" id="<?php echo $employee['employee_id']; ?>" value="<?php echo $employee['employee_id'];?>" <?php 
								if (isset($task)) { 
											 
											$data=explode(',',$task['shared_task_user_id']);
                                            if(in_array($employee['employee_id'],$data)) { echo "checked";}  else {echo "Unchecked";}
										
								} ?> > 
						<i class="fa fa-square-o"></i><?php echo $employee['first_name'] .  $employee['last_name'];?></label>
						<?php 						
						echo '</div>';
						}
						?>
                    </div>
		         <a class="btn btn-default btn-xs selectall mt5" href="javascript:void(0);">Select All</a>
                    <a class="btn btn-default btn-xs unselectall mt5" href="javascript:void(0);">Unselect All</a>    
                </div>
              </div>
			 
			  
<?php { ?>
					<div class="form-group">
						<input type="hidden" name="emp_name" id="emp_name" value="<?php echo $_SESSION['LOGIN_USER_TYPE']."=".$_SESSION['ADMIN_LOGIN_SWISS'];?>" />
					</div>
				<?php }?>
             
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Description</label>
                <div class="col-lg-8">
                  	<textarea name="description" id="" value="" class="form-control validate[required]" rows="5" ><?php echo isset($task['description'])?$task['description']:'';?></textarea>
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
			   <?php 
						foreach($employeename as $cs) {
						
											?>
					<div class="form-group">
						<input type="hidden" name="assigned_to_user_type_id" id="emp_name" value="<?php echo $cs['user_type_id']; ?>" />
					</div>
					
								
									<?php	} ?> 
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
jQuery(document).ready(function(){
	   jQuery("#frm_add").validationEngine();
	   
	   
	   
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
	    var checkin = $('#f_date').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		
			}
    	}).on('changeDate', function(ev) {
			//alert(ev);
			if (ev.date.valueOf() <= checkout.date.valueOf()) {
				var newDate = new Date(ev.date);
				//alert(ev);
          		newDate.setDate(newDate.getDate());
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#t_date')[0].focus();
			
    	}).data('datepicker');
		console.log(checkin);
    	var checkout = $('#t_date').datepicker({
    		onRender: function(date) {
				if(checkin.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
});

</script>




<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>