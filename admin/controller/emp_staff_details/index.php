
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
$filter_data=array();
$filter_value='';

$class='collapse';

if(!isset($_GET['filter_edit'])){
	$filter_edit = 0;
}else{
	$filter_edit = $_GET['filter_edit'];
}

if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}


if(isset($obj_session->data['filter_data'])){
	$filter_taskname = $obj_session->data['filter_data']['filter_taskname'];
	$filter_name = $obj_session->data['filter_data']['filter_name'];
	$class = '';
	
	$filter_data=array(
		'filter_name' => $filter_name,
		'filter_taskname' => $filter_taskname,
	);	
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class='';		
	if(isset($_POST['filter_taskname'])){
		$filter_taskname=$_POST['filter_taskname'];		
	}else{
		$filter_taskname='';
	}
	
	if(isset($_POST['filter_name'])){
		$filter_name=$_POST['filter_name'];
	}else{
		$filter_name='';
	}
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
		'filter_name' => $filter_name,
		'filter_taskname' => $filter_taskname,
	);
	
	$obj_session->data['filter_data'] = $filter_data;
	
  	
}


if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}


if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'es.series';
}
$status_se = '';
if(isset($_POST['inactive']) || (isset($_GET['status']) && $_GET['status'] == 0) || (isset($_POST['status']) && $_POST['status'] == 0) )
{
	$status_se = ' AND es.status = 0';
	$sts = 0;
}
else
{
	$status_se = ' AND es.status = 1';
	$sts = 1;
}

if($display_status) {
	
//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_empdetails->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_empdetails->updateStatus(2,$_POST['post']);
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
                	<?php if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add Employee</a>
                    <?php }
					if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>
                </span>
           
          </header>
		  
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
				 <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search
                  </header>
              
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Group Name</label>
                                <div class="col-lg-8">
								  <select name="filter_taskname" class="form-control">
									 <option value=''>Select Group</option>
									 <?php 
												   $data = $obj_empdetails->getSupplier(); 
												
												  foreach($data as $d){
											   ?>
													<option value="<?php echo $d['staff_group_id']; ?>"><?php echo $d['staff_group_name']; ?></option>
									<?php } ?>
									</select> 
                                </div>
                              </div>                             
                          </div>
                          <div class="col-lg-4">                              
                               <div class="form-group">
                                <label class="col-lg-2 control-label">Employee Name</label>
                                <div class="col-lg-8">
                             <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="firstname" id="input-name" class="form-control" />

                                </div>
                              </div>
                          </div>    
						
                          	  
                 </div> 
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                           <input type="hidden" value="<?php echo $sts;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
         	</form>
          
              <div class="row">
                  <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
			   <div class=" pull-left">
					<div class="panel-body text-muted l-h-2x">
					 <button  type="submit" class="btn btn-danger btn-sm pull-right ml5" name="inactive" style="background-color:#d9534f"><i></i> Inactive</button>
					 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="active" style="background-color:#5cb85c"><i></i> Active</button>
					</div>
			   </div> 
			   </form>
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, '&status='.$sts, '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        	
                        		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&status='.$sts.$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&status='.$sts.$add_url, '',1);?>"><?php echo $display_limit; ?></option>
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
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>
                  <th>Profile</th>                 
                  <th>Employee Name / D. Of Birth <br> Contact No. / Address</th>
				  <th>Group name</th>
				  <th>Date of Joining</th>
				  <th>Date of Leaving</th>
				  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_employee = $obj_empdetails->getTotalDepartment($filter_data,$status_se);
			  $pagination_data = '';
			  if($total_employee){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'sort'  => $sort_name,
						'order' => $sort_order,
				  		'start' => ($page - 1) * $limit,
						'limit' => $limit
				  );	
				  $employee = $obj_empdetails->getDepartments($option,$filter_data,$status_se);
				  foreach($employee as $emp){ 
				?>
                    <tr>
                      <td><input type="checkbox" name="post[]" value="<?php echo $emp['emp_staff_detail_id'];?>"></td>
                      <td><img src="<?php echo  HTTP_UPLOAD.'admin/profile/200_'.$emp['profile'];?>" alt="<?php //echo $personainfo['fname'];?>" style=" height: 100px;"></td>
                      <td><?php echo $emp['fname']." ".$emp['mname']." ".$emp['lname'];?>
                            <br><small class="text-muted"><?php echo dateformat(1,$emp['dob']);?></small>
                                <br><?php echo $emp['mno'];?>
                                <br><small class="text-muted"><?php echo nl2br($emp['addr']);?></small>
                      </td>
					  <td><span class="badge bg-info"><?php echo $emp['staff_group_name'];?></span></td>
					  <td><?php echo dateformat(1,$emp['doj']);?></td>
					  <td><?php if($emp['dolev']!='0000-00-00' && $emp['dolev']!='1970-01-01') { echo dateformat(1,$emp['dolev']); }?></td>
                      <td><label class="label   
                        <?php echo $emp['status']==1?'label-success':'label-danger';?>">
                        <?php echo $emp['status']==1?'Active':'Inactive';?>
                        </label>
                      </td>
                      <td>
                      		<a href="<?php echo $obj_general->link($rout, 'mod=add&emp_staff_detail_id='.encode($emp['emp_staff_detail_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                      
						</td>
                    </tr>
                    <?php
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_employee;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = HTTP_ADMIN.'index.php?rout='.$rout.'&status='.$sts.'&page={page}';
					$pagination_data = $pagination->render();
              } else{ 
				  echo "<tr><td colspan='5'>No record found !</td></tr>";
			  } ?>
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
$('img').bind('contextmenu', function(e) {
    return false;
});
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>


