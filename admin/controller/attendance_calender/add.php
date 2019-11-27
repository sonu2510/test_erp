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
if(isset($_GET['date']) && !empty($_GET['date'])){
	$date = $_GET['date'];

}
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
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
        			<header class="panel-heading bg-white"><?php echo $display_name;?> Detail </header>
					 <span class="text-muted m-l-small pull-right">
                 		
                         
					</span>
        			<div class="panel-body form-horizontal">                    
						 <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
                 
                  <th>Group Name</th>            
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_department = $obj_attendance->getTotalStaffGroup();
			  //echo $total_department;die;
			  $pagination_data = '';
			  if($total_department){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'sort'  => 'staff_group_name',
						'order' => 'ASC',
				  		'start' => ($page - 1) * $limit,
						'limit' => $limit
				  );	
				  $StaffGroup = $obj_attendance->getStaffGroup($option);
				  foreach($StaffGroup as $StaffGroup){ 
					?>
                    <tr>
                 
                      <td><?php echo $StaffGroup['staff_group_name'];?></td>
                      <td>
						<?php $getattendance = $obj_attendance->getattendance($StaffGroup['staff_group_id'],$date);
								if($getattendance)
								{
								?>
									<a href="<?php echo $obj_general->link($rout, 'mod=add_data&staffgroup_id='.encode($StaffGroup['staff_group_id']).'&date='.encode($date).'&att_id='.encode($getattendance['attendance_id']), '',1); ;?>" name="add_attendance" class="btn btn-primary btn-xs fa fa-pencil">&nbsp;&nbsp; Attendance </a>
								<?php } else { ?>
									<a href="<?php echo $obj_general->link($rout, 'mod=add_data&staffgroup_id='.encode($StaffGroup['staff_group_id']).'&date='.encode($date), '',1); ;?>" name="add_attendance" class="btn btn-info btn-xs fa fa-plus">&nbsp;&nbsp; Attendance </a>
								<?php } ?>
					  </td>
					  
                      
                    </tr>
					
                    <?php
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_department;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
					$pagination_data = $pagination->render();
				    //echo $pagination_data;die;
              } else{ 
				  echo "<tr><td colspan='5'>No record found !</td></tr>";
			  } ?>
              </tbody>
            </table>
          </div>
          </form>
							<div class="form-group">
								<div class="col-lg-9 col-lg-offset-3">
								
								  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
								</div>
						  </div>
        			</div>
					
        		</section>
        	</div>
        </div>
    </section>
</section>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style>
	#first {
		font-size:18px;
	}
	#lamination{
		font-size:24px;
	}
	#enquiry tr{
		border-color:#000;
	}
	
	#lamination_report tbody tr, #enquiry tr{cursor: pointer; }
</style>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">


</script>   


