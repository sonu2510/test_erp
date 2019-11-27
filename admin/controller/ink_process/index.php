<?php
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}


$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$class = 'collapse';

$filter_data= array();
if(isset($_POST['btn_filter'])){
	
	$class = '';
		
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_job_name'])){
		$filter_job_name=$_POST['filter_job_name'];		
	}else{
		$filter_job_name='';
	}	
	
	if(isset($_POST['filter_operator_id'])){
		$filter_operator_id=$_POST['filter_operator_id'];
	}else{
		$filter_operator_id='';
	}
		
	$filter_data=array(
		'job_name' => $filter_job_name,
		'operator_id' => $filter_operator_id
	);
//        printr($filter_data);die;
	
}

if(isset($_GET['order'])){
	$sort = $_GET['sort'];	
}else{
	$sort = 'jm.job_id';	
}
if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}

if($display_status) {

//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_ink_process->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_ink_process->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
	
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
       
          	<span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">
          			
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus">&nbsp;&nbsp;&nbsp;</i>New Ink Process </a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php  } ?>                      
                    
            </span>
           
          </header>
          
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                <a href="#" class="panel-toggle text-muted active"> <i class="fa fa-search"></i> Search</a>
                  </header>
              
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                       
                      <div class="row">
                               <div class="col-lg-3">
                                      <div class="form-group">
                                      
                                        <label class="col-lg-5 control-label">Job Name</label>
                                           <div class="col-lg-7">
                                              <input type="text" name="filter_job_name" value="" placeholder="Job Name" id="input-name" class="form-control" />
                                            </div>
                                      </div>                             
                              </div>
                          <div class="col-lg-3">
                                  <div class="form-group">
                                      <label class="col-lg-5  control-label">Operator Name</label>
                                      <div class="col-lg-7">
                                          <?php $operators = $obj_ink_process->getOperatorName();
//                            printr($operators);
                                          ?>
                                          <select name="filter_operator_id" id="machine_id" class="form-control validate[required]">
                                              <option value="">Select Operator Name</option>
                                              <?php foreach ($operators as $operator) { ?>
                                                  <option value="<?php echo $operator['employee_id']; ?>"<?php echo(isset($ink_detail['operator_id']) && $ink_detail['operator_id'] == $operator['employee_id']) ? 'selected' : ''; ?>> <?php echo $operator['user_name']; ?></option>
                                             <?php } ?> 
                                          </select>
                                      </div>
                                  </div>
                              </div>	               
                      	</div>                     
               		  </div>
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
         	</form>
            
            <div class="row">
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        	
                        		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                 </select>
             </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
           </div>   
          </div>
            
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <th>Date</th>
                      <th>Job No</th>
                      <th>Job Name</th>  
                      <th>Shift</th>
                      <th>Operator Name</th>
                      <th>Chemist Name</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
		  $total_job = $obj_ink_process->getTotalInkProcess($filter_data);
//                  printr($total_job);die;
		  $pagination_data = '';
                  if($total_job){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
//                      option use for limit or and sorting function	
                      $option = array(
                          'sort'  => $sort,
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit
                      );
//                      printr($limit);
		   $ink_process = $obj_ink_process->getInkProcess($option,$filter_data);
			  foreach($ink_process as $ink){ 
                        ?>
                        <tr <?php echo ($ink['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $ink['ink_p_id'];?>"></td>
                          <td><?php  echo dateformat(4, $ink['date']);  ?></td>
                          <td><?php  echo $ink['job_no'];  ?></td>
                          <td><?php  echo $ink['job_name'];  ?></td>
                          <td><?php  echo ($ink['shift']==1)?'Day':'Night';  ?></td>
                          <td><?php  echo $ink['operator_name']  ?></td>
                          <td><?php  echo $ink['chemist_name'];  ?></td>
                          <td>
                              
                                <div data-toggle="buttons" class="btn-group">
                                    <label class="btn btn-xs btn-success <?php  echo ($ink['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                     name="status" value="1" id="<?php  echo $ink['ink_p_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                    <label class="btn btn-xs btn-danger <?php  echo ($ink['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                        name="status" value="0" id="<?php  echo $ink['ink_p_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                </div>
                              
						  </td>
                          <td>	
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&ink_process='.encode($ink['ink_p_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                
                           </td>
                        </tr>
                        
                        <?php
						
                      }
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_job;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);
                        $pagination_data = $pagination->render();
                     } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } 
				  ?>
                  </tbody>
                </table>
                
              </div>
              
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
              <?php echo $pagination_data;?>
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>

<script type="application/javascript">
    
	$('input[type=radio][name=status]').change(function() {
	
		var ink_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateInkStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{ink_id:ink_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");	
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>