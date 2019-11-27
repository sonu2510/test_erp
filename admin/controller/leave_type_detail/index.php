<?php
include("mode_setting.php");

if(isset($_GET['user_type']) && $_GET['user_type'] && isset($_GET['user_id']) && $_GET['user_id']){
    $user_type_id = decode($_GET['user_type']);
    $user_id = decode($_GET['user_id']);
    $queryString = '&user_type='.$_GET['user_type'].'&user_id='.$_GET['user_id'];
}else{
    $user_type_id = $obj_session->data['LOGIN_USER_TYPE'];
    $user_id = $obj_session->data['ADMIN_LOGIN_SWISS'];
    $queryString = '';
}

//echo $user_type_id.'=='. $user_id;
$bradcums = array();
$bradcums[] = array(
    'text' 	=> 'Dashboard',
    'href' 	=> $obj_general->link('dashboard','','',1),
    'icon' 	=> 'fa-home',
    'class'	=> '',
);

if($user_type_id == 4){
    $bradcums[] = array(
        'text' 	=> 'Leave Application',
        'href' 	=> $obj_general->link($rout, '', '',1),
        'icon' 	=> 'fa-list',
        'class'	=> '',
    );
}

$bradcums[] = array(
    'text' 	=> 'Leave Details List',
    'href' 	=> '',
    'icon' 	=> 'fa-list',
    'class'	=> 'active',
);


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
    $filter_name = $obj_session->data['filter_data']['name'];
    
    $class = '';

    $filter_data=array(
        'name' => $filter_name);
}

if(isset($_POST['btn_filter'])){

    $filter_edit = 1;
    $class='';
    if(isset($_POST['filter_name'])){
        $filter_name=$_POST['filter_name'];
    }else{
        $filter_name='';
    }

    $filter_data=array(
	'user_name' => $filter_name );
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
    $sort_name = 'leave_id';
}
if(isset($_POST['btn_approve'])){
		$post = post($_POST);		
		$insert_id = $obj_leave->ApproveDisapproveAdd($post);	
		$obj_session->data['success'] = ADD;
	    page_redirect($obj_general->link($rout, '','', 1));
	}

//printr($limit);
if($display_status) {
	
	
//active inactive delete
   
    ?>
	
    <section id="content">
        <section class="main padder">
            <div class="clearfix">
                <h4><i class="fa fa-users"></i> <?php echo $display_name;?></h4>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php include("common/breadcrumb.php");?>
                </div>

                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">

                            <span>Leave Details</span>
                            <span class="text-muted m-l-small pull-right">
									<?php
									//$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
							//$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
?>
                   <?php if($obj_general->hasPermission('add',$menuId)){
				 ?>
					<a class="btn btn-success btn-xs" href="<?php echo $obj_general->link($rout,'mod=add', '',1);?>"><i class="fa fa-plus"></i>Apply Leave </a>
                                
<?php } ?>
             </span>

                        </header>
                        <div class="panel-body">
                            <div class="row text-small">


                                <?php /* <div class="col-sm-3">
              	  <select class="form-control" id="branch-filter-dropdown">

                  	<option value="filter_name=">Name</option>
                    <?php if(isset($_GET['filter_email'])) { ?>
                    	<option value="filter_email=" selected="selected">Email</option>
                    <?php } else { ?>
                   		<option value="filter_email=">Email</option>
                    <?php } ?>
                  </select>

              </div>

              <div class="col-sm-4">
                <div class="input-group">
                  <input type="text" class="input-sm form-control" id="branch-search-textbox" placeholder="Search" value="<?php echo $filter_value; ?>">
                  <span class="input-group-btn">
                  <button class="btn btn-sm btn-white" id="branch-filter-btn" type="button">Go!</button>
                  <button class="btn btn-sm btn-white" id="branch-refresh-btn" type="button"><i class="fa fa-refresh"></i></button>
                  </span> </div>
              </div> */ ?>

				</div>

				<form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">

					<section class="panel pos-rlt clearfix">
						<header class="panel-heading">
							<ul class="nav nav-pills pull-right">
								<li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
							</ul>
							<a href="#" class="panel-toggle text-muted active"> <i class="fa fa-search"></i> Search </a>
						</header>



						<div class="panel-body clearfix <?php echo $class; ?>">
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group">
										<label class="col-lg-2 control-label">Name</label>
										<div class="col-lg-10">
											<input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Name" id="input-name" class="form-control" />
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
							<?php
							$limit_array = getLimit();
							//printr($limit);
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
					<table class="table table-striped b-t text-small">
						<thead>
						<tr>
							<th width="20"><input type="checkbox"></th>

						   
						   <th>User Name</th>
							<th>From Date</th>
							<th>To Date</th>
							<th>Total Days</th>
							<th>Posted By</th>
							 <?php if($user_type_id == 4 || $user_type_id == 1){?>
							<th>Action</th>
							 <?php } ?>
							 <th>Approval Status</th>

						</tr>
						</thead>
						<tbody>
						<?php
						//printr($filter_data);
						$total_leave = $obj_leave->getTotalLeave($filter_data);
						$pagination_data = '';
						if($total_leave){
							if (isset($_GET['page'])) {
								$page = (int) $_GET['page'];
								//printr($page);die;
							} else {
								$page = 1;
								//printr($page);die;
							}

							if (isset($_GET['sort'])) {
								$sort_option = $_GET['sort'];
								//printr($sort_option);die;
							} else {
								$sort_option = 'leave_id';
								//printr($sort_option);die;
							}

							//oprion use for limit or and sorting function	
							$option = array(
								'sort' => $sort_option,
								'order' => $sort_order,
								'start' => ($page - 1) * $limit,
								'limit' => $limit
							);
							//printr($option);
							$leaves_details = $obj_leave->getLeave_Details($user_id, $user_type_id,$filter_data,$option);
							
							//print_r($leaves_details);
						  
							if($leaves_details) { 
								foreach($leaves_details as $leaves_detail){
									//print_r($leaves_detail);
									  //$userInfo = $obj_leave->get_User($leaves_detail['user_id'], $leaves_detail['user_type_id']);
									  //printr($userInfo);
									?>
								
									<tr>
										
										<td><input type="checkbox" name="post[]" value="<?php echo $leaves_detail['leave_id'];?>"></td>
										<td><?php echo $leaves_detail['user_name'];?></td>
										<td><?php echo dateformat(4,$leaves_detail['commencing_date']);?></td>
										<td><?php echo dateformat(4,$leaves_detail['ending_date']);?></td>
										<?php //$timestamp = strtotime($leaves_detail['commencing_date']); echo date('d-m-Y', $timestamp);?>
										<?php //$timestamp = strtotime($leaves_detail['ending_date']); echo date('d-m-Y', $timestamp);?>
										<td><?php echo $leaves_detail['no_of_days']." days";?></td>
										<td>												
										<?php
											$addedByImage = $obj_general->getUserProfileImage($leaves_detail['user_type_id'], $leaves_detail['user_id'], '100_');
											$addedByInfo = '';
											$addedByInfo .= '<div class="row">';
											$addedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
											$addedByInfo .= '<div class="col-lg-9">';
											if ($leaves_detail['user_name']) {
												$addedByInfo .= $leaves_detail['user_name'] . ', ';
											}
										   
											$addedByInfo .= '<br>Email ID : ' . $leaves_detail['email'] . '</div>';
											$addedByInfo .= '</div>';
											$addedByName = $leaves_detail['user_name'];
											str_replace("'", "\'", $addedByName);
											?>
											<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo; ?>' title="" data-original-title="<b><?php echo $addedByName; ?></b>"><?php echo $leaves_detail['user_name']; ?></a>

										</td>
												
											<?php if($user_type_id == 4 || $user_type_id == 1){?>
										<td><a class="btn btn-info btn-xs" data-toggle="modal" onclick="add_leave(<?php echo $leaves_detail['leave_id']; ?>);">View</a></td>
								
								

											<?php } ?>
										
										<td >
											<?php if($leaves_detail['approval_status']==1){?>
											<a class="btn btn-success btn-xs" data-toggle="modal" id="index_approve" >Approved</a>
											<?php if(!empty($leaves_detail['reason'])){?>
											<a class="btn btn btn-xs " data-toggle="modal" onclick="reason(<?php echo $leaves_detail['leave_id']; ?>);">Reason</a>
											<?php }else { ?>
											<?php }} elseif($leaves_detail['approval_status']==2) {?>
											<a class=" btn btn-info btn-xs" data-toggle="modal" id="index_pending" >Pending</a>
											<?php }elseif($leaves_detail['approval_status']==0){?>
											<a class=" btn btn-danger btn-xs" data-toggle="modal" id="index_disapprove" >Disapproved</a>
											<?php if(!empty($leaves_detail['reason'])){?>
											<a class="btn btn btn-xs "  data-toggle="modal" onclick="reason(<?php echo $leaves_detail['leave_id']; ?>);">Reason</a>
											<?php }else { ?>
											
											<?php }} ?>
									  </td>
									
								
							</tr>
								<?php } ?>											
							<?php
					   
							}else{
								echo "<tr><td colspan='5'>No record found !</td></tr>";
							}
							//pagination
							$pagination = new Pagination();
							$pagination->total = $total_leave;
							$pagination->page = $page;
							$pagination->limit = $limit;
							$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
							$pagination->url = $obj_general->link($rout, '&page={page}', '',1);
							$pagination_data = $pagination->render();
							//echo $pagination_data;die;
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

					<div class="modal fade" id="modal_reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						 <form class="m-b-none" method="post" action="" id="form_reason">
							<div class="modal-dialog">
							   <div class="modal-content">
								  <div class="modal-header">
									 <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button> 
									 <h4 class="modal-title" id="myModalLabel">Reason</h4>
								  </div>
								  <div id="datechk">
									
									<div class="control-label" id="fd" style="display:none;"> <label class="control-label ">From Date:</label>
									<input type="text" name="reasonfdate" id="reason_f_date"  style="border:0;"class="control-label validate " readonly required >
									</div></br>
									  <div class="control-label" id="td" style="display:none;"> <label class="control-label" id="">To Date:</label>
									 <input type="text" name="reasontdate" id="reason_t_date" style="border:0;"class="control-label validate" readonly required >
									 </div>
								
								 </div>
								<input type="hidden" id="leavereason_id" style="border:0;"class="control-label validate"></label> 
								<textarea class="control-label" name="reason"  id="d_reason" style="border:0;width:550px;height:100px;" readonly class="control-label validate" ></textarea></label>
								<input type="hidden" id="uid" style="border:1;"class="control-label validate"></label> 
								<input type="hidden" id="user_type_id" style="border:1;"class="control-label validate"></label> 
							  </div>
							   
							   
							</div>
						 </form>
					 </div>	

					 
		<div class="modal fade" id="modal_name" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						 <form class="m-b-none" method="post"action="" id="form">
							<div class="modal-dialog">
							   <div class="modal-content">
								  <div class="modal-header">
									 <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button> 
									 <h4 class="modal-title" id="myModalLabel">Leave Application</h4>
								  </div>
								 
								  <div class="modal-body">
									 <div class="block"> 
								<input type="hidden" id="leavedetails_id" style="border:0;"class="control-label validate"  ></label> 
								 <label class="control-label" >Leave Type:</label>&nbsp;&nbsp;
									<input type="text" id="leave_type" style="border:0;"class="control-label validate" readonly required >
								<div class="pull-right"> <label class="control-label ">From Date:</label>
								 <input type="text" id="from_date" readonly style="border:0;"class="control-label validate "  required >
								</div></div>
								
								 
								 <div class="block"> 
								 <label class="control-label" id="">Leave Title:</label>&nbsp;&nbsp;&nbsp;
								<input type="text" id="leave_title" style="border:0;"class="control-label validate" readonly required >
								 <div class="pull-right"> <label class="control-label" id="">To Date:</label>
								 <input type="text" readonly id="to_date" style="border:0;"class="control-label validate" required >
								 </div></div>
								 <div class="block"><label class="control-label" >Message</div>
								<textarea class="control-label" name="message" id="msg"  style="border:0;width:550px;height:200px;"class="control-label validate" readonly required ></textarea></label>
								
								<div class="block"><label class="control-label" > Reason:</div>
								<input type="hidden" id="leavedetails_id" style="border:0;"class="control-label validate"></label> 
								
							<div class="modal-body">
								<div class="modal-body">
										
									<input type="checkbox" name="chk" id="chk"  >Change Date<br><br>
									<div class="control-label">

											<label class="col-lg-3 control-label"> From Date</label>
											<div class="col-lg-8" style="margin-bottom: 5px;">
												<input type="date" id="re_fr_date" name="re_from_date" class="form-control" required>
											</div>
									</div>
									
										 <div class="control-label">
											<label class="col-lg-3 control-label">TO Date</label> 
											<div class="col-lg-8" style="margin-bottom:10px;"> 
												<input type="date" id="re_to_date" name="re_to_date" class=" form-control" required>
											</div>
										</div>	
									
									<textarea class="control-label" name="reason" placeholder="Write Here............" id="reason" style="border:1;width:550px;height:100px;margin-left: -30px;"class="control-label validate" ></textarea></label>
									<input type="hidden" id="uid" style="border:1;" class="control-label validate"></label> 
									<input type="hidden" id="user_type_id" style="border:1;"class="control-label validate"></label> 
								</div>
							</div>
							   
								
							  <div class="modal-footer" >
						
						
									<button type="button" id="approve_leave" name="btn_approve" class="btn btn-sm btn-success"  data-dismiss="modal" value="1">approve</button>
							 
									<button type="button" id="disapprove_leave" name="btn_approve" class="btn btn-sm btn-danger" data-dismiss="modal" value="0">Disapprove</button>													  
							
							 
							   </div>
							 
						  
							   <!-- /.modal-content --> 
							</div></div></div>  
						 </form>
		</div>
					



<script type="text/javascript">


$( document ).ready(function() {
    if($("#chk").is(':checked')){}else{ 
		 $("#re_fr_date").attr("disabled", true);
		 $("#re_to_date").attr("disabled", true);	}
});
   $('#chk').click(function(){
	
		if($("#chk").is(':checked')){
		$("#re_fr_date").removeAttr("disabled", true);
		$("#re_to_date").removeAttr("disabled", true);	
	
	}else{
		
     $("#re_fr_date").attr("disabled", true);
     $("#re_to_date").attr("disabled", true);	
		
		}
	//}
});
   function add_leave(leave_id){
	   
	   //alert('hii');
	  var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=leave_popup_details', '', 1); ?>");
	  //alert('hii');
	  
		$.ajax({			
			url : url,
			type :'post',
			data :{leave_id:leave_id},
			success: function(response){
			var val =  $.parseJSON(response);
			
				console.log(response);
				$("#leavedetails_id").val(val.leave_id);				
				$("#leave_type").val(val.leave_type);
				$("#leave_title").val(val.leave_title);
				$("#from_date").val(val.from_date);
				$("#to_date").val(val.to_date);
				$("#msg").val(val.msg);
		        $("#modal_name").modal("show");
				
				
				
			},
			

		});
	  
   }
   

$('#approve_leave').click(function(){
	
	var id = $('#leavedetails_id').val();
	var r = $('#reason').val();
	  var inputdate = document.getElementById("re_fr_date")
		 var x = document.getElementById("re_fr_date").value;
		var y = document.getElementById("re_to_date").value;
	var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&fun=approvalstatus','',1);?>");
			//alert('status_url');
            $.ajax({
                url : status_url,
                type :'post',
                data :{id:id,r:r,rfd:x,rtd:y},
                success: function(){
					location.reload();
                    }
            });
		
	
})

$('#disapprove_leave').click(function(){
	var id = $('#leavedetails_id').val();
	var r = $('#reason').val();
	 var inputdate = document.getElementById("re_fr_date")
		 var x = document.getElementById("re_fr_date").value;
		 var y = document.getElementById("re_to_date").value;
	var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&fun=disapprovalstatus','',1);?>");
			//alert('status_url');
            $.ajax({
                url : status_url,
                type :'post',
                   data :{id:id,r:r,rfd:x,rtd:y},
                success: function(){
					location.reload();
                    }
            });
		
	
})


	 function reason(leave_id){
	   //alert('hii');
	  var url = getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&fun=leave_popup_reason', '', 1); ?>");
	  //alert('hii');
	  
		$.ajax({			
			url : url,
			type :'post',
			data :{leave_id:leave_id},
			success: function(response){
			var val =  $.parseJSON(response);
			
				console.log(response);
				//console.log(val);
				var fdate=JSON.stringify(val.re_f_date);
				var tdate=JSON.stringify(val.re_t_date);
				var comparedate="\"0000-00-00\"";
				//alert(fdate);
				//alert(comparedate)
				$("#leavereason_id").val(val.leave_id);				
				$("#d_reason").val(val.d_reason);
				$("#reason_f_date").val(val.re_f_date);
				$("#reason_t_date").val(val.re_t_date);
				if(fdate!=comparedate){
				$("#fd").css("display", "inline");
				$("#td").css("display", "inline");
				}
				else if(fdate==comparedate){
				$("#fd").css("display", "none");
				$("#td").css("display", "none");
				}
				$("#modal_reason").modal("show");
				
				
				
			},
			

		});
	 // alert('hello'); 
	  
   }

</script>

<?php } else {
    include(DIR_ADMIN.'access_denied.php');
}
?>



































