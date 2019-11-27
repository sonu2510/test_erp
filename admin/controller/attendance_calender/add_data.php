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
	'href' 	=> $obj_general->link($rout, 'mod=add&date='.base64_decode($_GET['date']), '',1),
	'icon' 	=> 'fa-edit',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name,
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);

if(isset($_GET['staffgroup_id']) && !empty($_GET['staffgroup_id'])){
	$staffgroup_id = base64_decode($_GET['staffgroup_id']);
	 $date=base64_decode($_GET['date']);
	  $staffgroup_details = $obj_attendance->getGroup($staffgroup_id);	
	  $emp_staff_detail = $obj_attendance->emp_staff_detail($staffgroup_id,$date);	
	$getattendance_types = $obj_attendance->attendance_types();
//	printr($date);
}
if(isset($_GET['date']) && !empty($_GET['date'])){
	$date = base64_decode($_GET['date']);
	 
	 
}
$other_detail =$attendance =array();
if(isset($_GET['att_id']) && !empty($_GET['att_id'])){
	$att_id = base64_decode($_GET['att_id']);
	 
	  $getAttData = $obj_attendance->getAttData($att_id);	
	  $other_detail = json_decode($getAttData['other_detail']);
	  if($getAttData['attendance']!='')
		$attendance = json_decode($getAttData['attendance']);
}


$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
	//printr($post);die;	
		$insert_id = $obj_attendance->addattendance($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	if(isset($_POST['btn_update'])){
		$post = post($_POST);
	//printr($post);die;	
		$insert_id = $obj_attendance->updateattendance($att_id,$post);
		$obj_session->data['success'] = UPDATE;
		//die;
		page_redirect($obj_general->link($rout, '', '',1));
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
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Permission Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="frm_permission" data-validate="parsley" enctype="multipart/form-data">
             
              <div class="form-group">
				  <div class="btn-group btn-group-justified"> 
				  <a href="#" class="btn bg-inverse"><?php echo $staffgroup_details['staff_group_name'];?></a>				  
				  <a href="#" class="btn bg-inverse"><?php echo dateFormat(5,$date);  ?></a> 
				  </div>
				  </div>
          
       
              
                  <div class="table-responsive">
					<table class="table table-striped b-t text-small">
					<input type="hidden" name="staff_group_id" value="<?php echo $staffgroup_details['staff_group_id'];?>">
					<input type="hidden" name="attendance_date" id="attendance_date" value="<?php echo $date;?>">
					
					 <thead>
					
                	<th><b>Employee Name</b> </th>
                	<th><b>Attendance</b> </th>				
					</thead>
                <?php foreach($emp_staff_detail as $emp){					
						
					?>				
                    <tr>
                        <td><b><?php echo $emp['series'].' . '.$emp['fname'].'  '.$emp['mname'].'  '.$emp['lname']; ?></b></td>                        
                        <td>
							<div class="col-lg-4">
								<input type="checkbox" class="checkbox_att" name="attendance[]" id="<?php echo $emp['emp_staff_detail_id']; ?>" value="<?php echo $emp['emp_staff_detail_id']; ?>" <?php if(in_array($emp['emp_staff_detail_id'],$attendance)){ echo 'checked = "checked"'; } ?> >
							</div>	
							<div class="col-lg-8" style="width:300px">
								 <select name="other[]" id="other" class="form-control" width="auto;">
									<option value="">Select Other Option</option>
									<?php foreach ($getattendance_types as $type) {?>
										<option value="<?php echo $emp['emp_staff_detail_id'].'=='.$type['attendance_types_id'];?>" <?php if(in_array($emp['emp_staff_detail_id'].'=='.$type['attendance_types_id'],$other_detail)){ echo 'selected = "selected"'; } ?>><?php echo $type['type_name'];?></option>
									<?php } ?>
								</select>
							</div>
                         </td>   						 
                    </tr>
                  <?php }?>
				  <tr>
					<td></td>
					  <td>
						<div class="col-lg-4">
							<input type="checkbox" class="checkbox_att" id="selectall" name="check" ><label>Select All</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						    <input type="checkbox" id="unselectall" name="check" > <label> Unselect All</label>
                        </div>
					 </td>
          
					</tr>
              </table>
			 
              </div>
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                	<?php if(isset($_GET['att_id'])) { ?>
						<button type="submit" name="btn_update" id="btn_save" class="btn btn-primary">Update </button>	
					<?php }else{?>
						<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
					<?php }?>
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
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style>
	td {
		Width:50%;
	}
	th{
		font-size:14px;
	}
	#first {
		
	}
	
</style>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">


	  $("#selectall").click(function () {
           $(".checkbox_att").prop('checked', true);
        });
		$("#unselectall").click(function () {
           $(".checkbox_att").prop('checked', false);
        });
		function daysInThisMonth() {
			var curdate=$("#attendance_date").val();
  var now = new Date(curdate);
 var vals=new Date(now.getFullYear(), now.getMonth()+1, 0).getDate();
 //alert(vals);
}
function sundaysInMonth(start) {
    var d = new Date('1 ' + start); // May not parse in all browsers
    var ndays = new Date( d.getFullYear(), d.getMonth()+1, 0 ).getDate();
    return Math.floor((ndays + (d.getDay() + 6) % 7) / 7);
	alert("sss");
}
</script>  
<script type="application/javascript">
$( document ).ready(function() {
daysInThisMonth();


}); 
</script>  
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
