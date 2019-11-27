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


$limit = 10;
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
	if(isset($_POST['filter_job_no'])){
		$filter_job_no=$_POST['filter_job_no'];		
	}else{
		$filter_job_no='';
	}	if(isset($_POST['filter_roll_no'])){
		$filter_roll_no=$_POST['filter_roll_no'];		
	}else{
		$filter_roll_no='';
	}	
	
	if(isset($_POST['filter_operator_id'])){
		$filter_operator_id=$_POST['filter_operator_id'];
	}else{
		$filter_operator_id='';
	}
		
	$filter_data=array(
		'job_name' => $filter_job_name, 
		'operator_id' => $filter_operator_id,
		'job_no'=>$filter_job_no,
		'roll_no'=>$filter_roll_no
	);
	
}

if(isset($_GET['order'])){
	$sort = $_GET['sort'];	
}else{
	$sort = 'job_id';	
}
if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}
$job_date='';
if(isset($_GET['job_date']))
{
		$job_date = decode($_GET['job_date']);
		
}
//printr($job_date);

	
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
		$obj_printing_job->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_printing_job->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
elseif(isset($_POST['btn_roll_code']))
{
		//printr($_POST);die;
		$obj_printing_job->addroll_code($_POST);
		
}	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
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
          			
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Printing Job </a>
                   
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php  } ?>                      
                    
            </span>
           
          </header>
           
          <div class="panel-body">
        
                 
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
                                        <label class="col-lg-5 control-label">Job No</label>
                                           <div class="col-lg-7">
                                              <input type="text" name="filter_job_no" value="" placeholder="Job No" id="input-no" class="form-control" />
                                            </div>
                                      </div>                             
                              </div> 
                              <div class="col-lg-3">
                                      <div class="form-group">
                                        <label class="col-lg-5 control-label">Roll Code</label>
                                           <div class="col-lg-7">
                                              <input type="text" name="filter_roll_no" value="" placeholder="Roll Code" id="input-no" class="form-control" />
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
            
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, $add_url, '',1); ?>">
          
           <div class=" pull-left">
           	 	<div class="panel-body text-muted l-h-2x">
               
                 
               
                 <a href="<?php echo $obj_general->link($rout, 'mod=day_wise','',1); ?>" class="btn btn-primary btn-sm pull-right ml5" name="daywise" style="background-color:#CBC6AB"> Day Wise</a>
                 <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-primary btn-sm pull-right ml5" name="alljobs" style="background-color:#81C267"> All Jobs</a>
                
                </div>
           </div> 
         
           </form>
           
            <div class="row">
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							//printr($limit_array);
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
                <table id="example" class="table b-t text-small table-hover">
                  <thead>
                       <?php  $total_job = $obj_printing_job->getTotalJob($filter_data,$job_date);
            				  $pagination_data = '';
                              if($total_job){
                                    if (isset($_GET['page'])) {
                                        $page = (int)$_GET['page'];
                                    } else {
                                        $page = 1;
                                    }
                                  //option use for limit or and sorting function	
                                  $option = array(
                                      'sort'  => $sort,
                                      'order' => $sort_order,
                                      'start' => ($page - 1) * $limit,
                                      'limit' => $limit
                                  );
            					  
            					  
            
            					
            				$jobs = $obj_printing_job->getJob($option,$filter_data,$job_date);
        				   if($job_date!=''){
                                        echo' <tr><th colspan="6" style="font-size: large;"><center><b>'.dateFormat(4,$job_date).'</b></center></th>';
                                        echo' <th colspan="8" style="font-size: large;"><center><b>'.$jobs[0]['machine_name'].'</b></center></th></tr>';
        					    }?>         
                    <tr>
                      <th width="20"><input type="checkbox" style="width:10px;"></th>
                      <th>Printing Number<br>Job Date</th>
                      <th>Job Name</th>    
					  <th>Film Size<br> Job Type</th>   
					  <th>Shift</th>  
                      <th>Input Qty(Kgs)</th>
                      <th>Output Qty(Kgs)</th>
                      <th>Output Qty(Meter)</th>
				      <th>Chemist Name</th>
					  <th>Remarks</th>
                      <th>Roll Code</th>
                      <th>Status</th>                                       
                      <th>Action</th>
                      <th>Added By</th>
                    </tr> 
                  </thead>
                  <tbody>
                  <?php	
				
				 
					  
					  foreach($jobs as $job){ 
				//	 printr($job);

            if(isset($job['job_type']) &&  $job['job_type'] =='roll_form')
                $job_type='Roll Form';
            else
                $job_type='Pouching';
					 if((isset($job['remaks_printing_job'])) && ($job['remaks_printing_job'] =='Other')){ 
									if($job['remark']!='Other')
									$remark=$job['remark'];
								else
									$remark='';
							}else{
								$remark=$job['remaks_printing_job'];
							}
					  $chemist_name = $obj_printing_job->getchemist_id($job['chemist_id']);
					 
                        
                        ?>
                        
                      
                         <tr <?php echo ($job['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value=" <?php echo $job['job_id'];?>" style="width:10px;"></td>
                          <td> <a href="<?php echo $obj_general->link($rout, 'mod=view&printing_id='.encode($job['job_id']),'',1); ?>" ><?php echo  'PRINTING NO- '.$job['job_no'];  ?>  <br><span style="color:#f92c09" ><?php echo dateFormat(4,$job['job_date']);  ?></span> </a></td>                        
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&printing_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['job_name'];  ?>  </a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&printing_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['film_size'];  ?><br><span style="color:#f92c09" ><?php echo $job_type;  ?></span></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&printing_id='.encode($job['job_id']),'',1); ?>" ><?php echo  $job['operator_shift'] ;  ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&printing_id='.encode($job['job_id']),'',1); ?>" ><?php echo  $job['input_qty'] ;  ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&printing_id='.encode($job['job_id']),'',1); ?>" ><?php echo  $job['output_qty'] ;  ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&printing_id='.encode($job['job_id']),'',1); ?>" ><?php echo  $job['output_qty_m'] ;  ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&printing_id='.encode($job['job_id']),'',1); ?>" ><?php echo $chemist_name  ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&printing_id='.encode($job['job_id']),'',1); ?>" ><?php echo $remark;  ?></a></td>
                          
                          
                           <td>
                           <?php 
                          
                           if(isset($job['roll_code_status'])&& $job['roll_code_status'] == '0' && $job['roll_code']==''){?>
                           <a class="label label-default"  onclick="rollcode('<?php echo $job['job_id'];?>','<?php echo $job['job_no'];  ?>','<?php echo addslashes($job['job_name']);  ?>');" id="remark">Roll Code</a>
                           <?php } else{?>
             				  <a class="badge bg-success" href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['roll_code'];  ?></a><br>
							   <?php if($job['lamination_status']!=1 && $job['status']==1){?>
							   <a href="<?php echo $obj_general->link('lamination_process', 'mod=add&printing_id='.encode($job['job_id']),'',1); ?>"   class="btn btn-info btn-xs">Go For Lamination</a><br>
							   <?php }
							   if($job['slitting_status']!=1 && $job['status']==1 && $job['lamination_status']!=1){
							   ?>
								<a href="<?php echo $obj_general->link('slitting', 'mod=add&printing_id='.encode($job['job_id']),'',1); ?>"   class="btn btn-info btn-xs">Go For Slitting</a>
							   <?php }} ?>
                           </td>
                      <td>
                              
                             <div data-toggle="buttons" class="btn-group">
                                    <label class="btn btn-xs btn-success <?php echo ($job['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                     name="status" value="1" id="<?php echo $job['job_id']; ?>"> <i class="fa fa-check text-active"></i>   Accept</label>                                   
                                    <label class="btn btn-xs btn-danger <?php echo ($job['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                        name="status" value="0" id="<?php echo $job['job_id']; ?>"> <i class="fa fa-times"></i>  Reject</label> 
                                </div>
                              
						  </td>
                        	
                          		<td> 
                            
									 <a href="<?php echo $obj_general->link($rout, 'mod=add&printing_id='.encode($job['job_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
								
                       
                                </td>
                          		<td> 
                            
							 <?php	$userInfo = $obj_printing_job->getUser($job['user_id'],$job['user_type_id']);									
								$addedByImage = $obj_general->getUserProfileImage($job['user_type_id'],$job['user_id'],'100_');
								$addedByInfo = '';
							
								$addedByName = $userInfo['first_name'].' '.$userInfo['last_name'];
								str_replace("'","\'",$addedByName);
							?>
								
                           <a class="btn btn-info btn-xs" href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $userInfo['user_name'];?></a><br>
                       
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

 <div class="modal fade" id="roll_code_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="tform" id="tform" style="margin-bottom:0px;">
              <div class="modal-header">
               
                <h4 class="modal-title u_title" id="myModalLabel"></h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                          <div class="form-group option">
					<label class="col-lg-3 control-label">Roll Code </label>
                        <div class="col-lg-8">
                             <input type="text" name="roll_code" id="roll_code" value="" required  class="form-control">
                        </div>
						</div>
						   <div class="form-group option">
						<label class="col-lg-3 control-label">Roll Size </label>
                        <div class="col-lg-6">
                             <input type="text" name="roll_size" id="roll_size" value="" required class="form-control">
                        </div>
                          
                           <input type ="hidden" name="job_no" id="job_no" value="" />
                            <input type ="hidden" name="job_id" id="job_id" value="" />
                           
                        </div>
                     </div>
                    	
                </div>  
              <div class="modal-footer">
                   		   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                   		   <button type="submit" name ="btn_roll_code" id="btn_roll_code" class="btn btn-primary">Add</button>
                  
              </div>
   		</form>   
    </div>
  </div>
</div>
<style>
	.inactive{
		//background-color:#999;	
	}
</style>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<script type="application/javascript">
$(document).ready(function() {
	/*$('#example').DataTable( {
		dom: 'Bfrtip',
		"serverSide": true,
	     lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
	 aoColumnDefs: [{ "bSortable": false, "aTargets":  [ 0, 1, 2, 3,4,5,6 ]  }, 
                { "bSearchable": true, "aTargets": [ 0, 1, 2, 3,4,5,6] }
                ],
		buttons: [
			{	
				extend: 'pdfHtml5',
				orientation: 'landscape',
				pageSize: 'LEGAL',
				footer: 'true',
			
				exportOptions: {
                modifier: {
                    page: ''
                },
            }
				
			}
		],
		
} );*/
    
} );
</script>
<script type="application/javascript">


	$('input[type=radio][name=status]').change(function() {
	
		var roll_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateRollStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{roll_id:roll_id,status_value:status_value},
			success: function(){
				alert(roll_id);
				//alert(responce);return false;
				
				location.reload();
				set_alert_message('Successfully Updated',"alert-success","fa-check");	
				
			},
			error:function(){
				location.reload();
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });
	
function rollcode(job_id,job_no,job_name)
{
	
	$(".u_title").html("Add Roll Code For  <b>"+job_name+"</b> " );	
	$("#job_no").val(job_no);
	$("#job_id").val(job_id);	
	$("#roll_code_div").modal('show');	
}



</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>