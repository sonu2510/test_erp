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
	$filter_name = $obj_session->data['filter_data']['name'];
	$filter_email = $obj_session->data['filter_data']['email'];
	$filter_contact_name = $obj_session->data['filter_data']['filter_contact_name'];
	$filter_user_name = $obj_session->data['filter_data']['filter_user_name'];
	$filter_sample = $obj_session->data['filter_data']['filter_sample'];
	$filter_address = $obj_session->data['filter_data']['filter_address'];
	$class = '';
	
	$filter_data=array(
		'name' => $filter_name,
		'email' => $filter_email,
		'status' => $filter_contact_name,
		'user_name' => $filter_user_name,
		'sample' => $filter_sample,
		'address' => $filter_address,
	);	
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class='';		
	if(isset($_POST['filter_name'])){
		$filter_name=$_POST['filter_name'];		
	}else{
		$filter_name='';
	}
	
	if(isset($_POST['filter_email'])){
		$filter_email=$_POST['filter_email'];
	}else{
		$filter_email='';
	}
	
	if(isset($_POST['filter_contact_name'])){
		$filter_contact_name=$_POST['filter_contact_name'];
	}else{
		$filter_contact_name='';
	}
	if(isset($_POST['filter_user_name'])){
		$filter_user_name=$_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	
	if(isset($_POST['filter_sample'])){
		$filter_sample=$_POST['filter_sample'];
	}else{
		$filter_sample='';
	}
	
	if(isset($_POST['filter_address'])){
		$filter_address=$_POST['filter_address'];
	}else{
		$filter_address='';
	}
	$filter_data=array(
		'name' => $filter_name,
		'email' => $filter_email,
		'user_name' => $filter_user_name,
		'sample' => $filter_sample,
		'contact_nm' => $filter_contact_name,
		'address' => $filter_address
	);
	
	$obj_session->data['filter_data'] = $filter_data;
	
  	
}


if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}


if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'international_branch_id';
}


//printr($filter_data);
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
		$obj_branch->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '&mod=view', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_sample->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}	


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
		  	
			<span><?php echo $display_name;?> Listing</span>          		
      		<span class="text-muted m-l-small pull-right">
             	<?php if($obj_general->hasPermission('add', $menuId))
				{	?>				
				<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Request </a>
				<?php  } 
				
				       if ($obj_general->hasPermission('delete', $menuId)) { ?>
                                    <a class="label bg-danger" onclick="formsubmitsetaction('form_list', 'delete', 'post[]', '<?php echo DELETE_WARNING; ?>')"><i class="fa fa-trash-o"></i> Delete</a>
                                    
                                    <?php
                                }?>
             </span>

          </header>
          <div class="panel-body">
            
            
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
                                
                                <label class="col-lg-3 control-label">Sample Request No</label>
                                <div class="col-lg-6">
                                 <input type="text" name="filter_sample" value="<?php echo isset($filter_sample) ? $filter_sample : '' ; ?>" placeholder="Sample Request No" id="input-name" class="form-control" />
                                </div>
                              
                          </div>
                          </div>
                        
                        <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Company Name</label>
                                <div class="col-lg-6">
                                  <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Company Name" id="input-name" class="form-control" />
                                </div>
                              </div>
						 </div>
						 <div class="col-lg-4">
							  <div class="form-group">
                                <label class="col-lg-3 control-label">Contact Name</label>
                                <div class="col-lg-6">
                                 <input type="text" name="filter_contact_name" value="<?php echo isset($filter_contact_name) ? $filter_contact_name : '' ; ?>" placeholder="contact Name" id="input-name" class="form-control" />
                                </div>
                              </div>
						 </div>
						 
						  </div>
						 <div class="row">
						
						     <div class="col-lg-4">
							  <div class="form-group">
                                
                                <label class="col-lg-3 control-label">Email</label>
                                <div class="col-lg-6">
                                 <input type="text" name="filter_email" value="<?php echo isset($filter_email) ? $filter_email : '' ; ?>" placeholder="Email" id="input-name" class="form-control" />
                                </div>
                              
                          </div>
                          </div>
						    <div class="col-lg-4">
							  <div class="form-group">
                                
                                <label class="col-lg-3 control-label">Address</label>
                                <div class="col-lg-6">
                                 <input type="text" name="filter_address" value="<?php echo isset($filter_address) ? $filter_address : '' ; ?>" placeholder="Address" id="input-name" class="form-control" />
                                </div>
                              
                          </div>
                          </div>
                          
                           <div class="col-lg-4">
							  <div class="form-group">
                                 
                                <label class="col-lg-3 control-label">Posted By User</label>
                                                <?php
                                                $splitdata=array();
                                                $splitdata=explode('=',$filter_user_name);
                                                //printr($splitdata);
                                                $userlist = $obj_sample->getUserList();
                                                ?>
                                                <div class="col-lg-6">
                                                    <select class="form-control" name="filter_user_name">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($userlist as $user) { ?>
                                                            <?php if ($splitdata[0] == $user['user_type_id'] && $splitdata[1] == $user['user_id']) { ?>

                                                                <option value="<?php echo $user['user_type_id'] . "=" . $user['user_id']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?php echo $user['user_type_id'] . "=" . $user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>                                       
                                                    </select>
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
            <table class="table table-striped b-t text-small table-hover">
                  <thead>
                    <tr>
                        <th width="20"><input type="checkbox"></th>
                      <th>Sr No. </th>
                      <th>Company Name <br> Order Date</th>
                      <th>Contact Person Name<br>Email</th>
					  <th>Sent Date</th>
					  <th>Courier Info. (Name)</th>
					  <th>AWS No. (Tracking No.)</th>
					  <th>Action</th>
					  <th></th>
					   <th></th>
					   <th>Posted By</th>
					   <th>Request By</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
				  $total_requset= $obj_sample->getTotalRequest($filter_data);
				  $pagination_data = '';
                  if($total_requset){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'request_id',
                            'order' => 'ASC',
                            'start' => ($page - 1) * LISTING_LIMIT,
                            'limit' => LISTING_LIMIT
                      );
					 // printr($filter_data);
					 $request = $obj_sample->getRequests($option,$filter_data);
						$i=0;
					  foreach($request as $req){ 
                       //printr($req); ?>
                      <tr>
                          <td><input type="checkbox" name="post[]" value="<?php echo $req['request_id']; ?>"></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&request_id='.encode($req['request_id']), '',1);?>"><?php echo $req['sample_no'];?></a></td>
                          <td> <a href="<?php echo $obj_general->link($rout, 'mod=view&request_id='.encode($req['request_id']), '',1);?>"><?php echo $req['company_nm'];?><br><small><?php echo dateFormat("4",$req['date_added']);?></small></a></td>
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&request_id='.encode($req['request_id']), '',1);?>"><?php echo $req['contact_nm'];?> <br><small><?php echo $req['email_1'];?></small></td>
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&request_id='.encode($req['request_id']), '',1);?>"><?php 
									if(isset($req['sent_date']) && $req['sent_date']!='0000-00-00')
										echo dateFormat("4",$req['sent_date']);
							 ?></a></td>
  						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&request_id='.encode($req['request_id']), '',1);?>"><?php echo $req['courier_name'] ; ?></a></td>
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&request_id='.encode($req['request_id']), '',1);?>"><?php echo $req['aws_no'];?></a></td>
						  <td>
						  <?php if( $req['courier_name']=='' && $req['aws_no']=='')
							{?>
						        <a class="btn btn-info btn-xs"  href="<?php echo $obj_general->link($rout, 'mod=add&request_id='.encode($req['request_id']), '',1);?>">Edit</a> 
						  
						  <?php } ?>
						  </td>
						  <td> <?php  if($obj_general->hasPermission('edit', $menuId)){
										if( $req['courier_name']=='' && $req['aws_no']=='')
										  {?>
												<a class="btn btn-default"  onclick="dispatch_qty(<?php echo $req['request_id']; ?>)" >Dispatch Detail</a> 
									<?php } 
										else
										{
										?>
												<a class="btn btn-default"  onclick="dispatch_qty(<?php echo $req['request_id']; ?>)" >Edit Dispatch Detail</a> 
								  <?php }
						  }?>
									</td>
						  <td>
						  <?php 
						  
						 /*   $date1 = $req['date_added'];
                            $date2 = date("Y-m-d");
                            
                            $diff = abs(strtotime($date2) - strtotime($date1));
                            
                           $years = floor($diff / (365*60*60*24));
                            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                            
                            if($req['received_otp']=='0' && $obj_general->hasPermission('add', $menuId))
    						{
                               if($days<1) {*/
                                ?>
        					<!--	   <a class="btn btn-default" href="<?php //echo $obj_general->link($rout, 'mod=otp_page&request_id='.encode($req['request_id']), '',1);?>" >Submit OTP</a>-->
        				  <?php //}  else { ?>
            					     <!-- <a class="btn btn-default" onclick="regenrate_otp(<?php //echo $req['request_id']; ?>)" >Regenrate OTP</a>
            					      <a class="btn btn-default" href="<?php //echo $obj_general->link($rout, 'mod=otp_page&request_id='.encode($req['request_id']), '',1);?>" >Submit OTP</a>-->
        				  <?php
        				 // }
        				  //  } ?>
						 </td>
						 <td>
								<?php 
									$userInfo = $obj_sample->getUser($req['user_id'], $req['user_type_id']);
									$addedByImage = $obj_general->getUserProfileImage($req['user_type_id'], $req['user_id'], '100_');
									$addedByInfo = '';
									$addedByInfo .= '<div class="row">';
									$addedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
									$addedByInfo .= '<div class="col-lg-9">';
									if ($userInfo['city']) {
										$addedByInfo .= $userInfo['city'] . ', ';
									}
									if ($userInfo['state']) {
										$addedByInfo .= $userInfo['state'] . ' ';
									}
									if (isset($userInfo['postcode'])) {
										$addedByInfo .= $userInfo['postcode'];
									}
									$addedByInfo .= '<br>Telephone : ' . $userInfo['telephone'] . '</div>';
									$addedByInfo .= '</div>';
									$addedByName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
									str_replace("'", "\'", $addedByName);
									?>
									<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo; ?>' title="" data-original-title="<b><?php echo $addedByName; ?></b>"><?php echo $userInfo['user_name']; ?></a>
							</td>
						 <td>
								<?php 
									
									if($req['date_added']>='2018-02-20')
									{
										$u = explode("=", $req['requester']);
										$userInfo = $obj_sample->getUser($u[1], $u[0]);
									}
									else
										$userInfo = $obj_sample->getUser($req['user_id'], $req['user_type_id']);
									
									$addedByImage = $obj_general->getUserProfileImage($req['user_type_id'], $req['user_id'], '100_');
									$addedByInfo = '';
									$addedByInfo .= '<div class="row">';
									$addedByInfo .= '<div class="col-lg-3"><img src="' . $addedByImage . '"></div>';
									$addedByInfo .= '<div class="col-lg-9">';
									if ($userInfo['city']) {
										$addedByInfo .= $userInfo['city'] . ', ';
									}
									if ($userInfo['state']) {
										$addedByInfo .= $userInfo['state'] . ' ';
									}
									if (isset($userInfo['postcode'])) {
										$addedByInfo .= $userInfo['postcode'];
									}
									$addedByInfo .= '<br>Telephone : ' . $userInfo['telephone'] . '</div>';
									$addedByInfo .= '</div>';
									$addedByName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
									str_replace("'", "\'", $addedByName);
									?>
									<a class="btn btn-info btn-xs" data-trigger="hover" style="background-color:#CBC6AB" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo; ?>' title="" data-original-title="<b><?php echo $addedByName; ?></b>"><?php echo $userInfo['user_name']; ?></a>
							</td>
						  </tr>
						
                        <?php $i++;
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_requset;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
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
<div class="modal fade" id="form_con" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post" name="form" id="conform_form" style="margin-bottom:0px;">
                <div class="modal-header title">
                    <h4 class="modal-title" id="myModalLabel"><span id="pro"></span></h4>
                </div>
				
                <div class="modal-body">
                    <input name="req_id" id="req_id" value=""  type="hidden"/>
                    <input name="no" id="no" value=""  type="hidden"/>
                     <div class="form-group">
						<label class="col-lg-3 control-label"><span class="required">*</span> Sent Date</label>
						<div class="col-lg-8">
							<input type="text" name="sent_date" id="sent_date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" placeholder="Date"  class="combodate form-control"/>
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-lg-3 control-label"><span class="required">*</span> Courier Info. (Name)</label>
						<div class="col-lg-5">
							<input type="text" name="courier_name" id="courier_name" class="form-control validate" value=""/>
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-lg-3 control-label"><span class="required">*</span> AWS No. (Tracking No.)</label>
						<div class="col-lg-5">
							<input type="text" name="aws_no" id="aws_no" class="form-control validate" value="" />
						</div>
					  </div>
                </div> 
                <div class="modal-footer">
                    <button type="button" name="btn_submit1" class="btn btn-primary" onclick="dispatch()">Dispatch</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">
/*$(".th-sortable").click(function(){
	alert("asdasd");
});*/
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form_con").validationEngine();
});

 function dispatch_qty(request_id)
{
	$(".note-error").remove();
	$("#req_id").val(request_id);
	var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getdata', '',1);?>");
		
		$.ajax({
			type: "POST",
			url: label_url,
			data:{request_id : request_id}, 
			success: function(response) {
			//	console.log(response);
				var val =  $.parseJSON(response);
				//console.log(val.sent_date);
				var arr = val.sent_date.split("-");
				 var mon = parseInt(arr[1])-parseInt(1);
				//console.log(arr[0]+'-'+arr[1]+'-'+arr[2]);
				$('.day option[value='+arr[2]+']').attr('selected', true);
				$('.month option[value='+mon+']').attr('selected', true);
				$('.year option[value='+arr[0]+']').attr('selected', true);
				
				$("#sent_date").val(val.sent_date);
				$("#courier_name").val(val.courier_name);
				$("#aws_no").val(val.aws_no);
				
			    if(val.courier_name!='')
			        $("#no").val(1);
			    else
			        $("#no").val(0);
			}
		});
	
	
	$("#form_con").modal("show");
}
 function dispatch()
 {
	var req_id = parseInt($("#req_id").val());
	var courier_name = $("#courier_name").val();
	var aws_no = $("#aws_no").val();
	var sent_date = $("#sent_date").val();
	var no = $("#no").val();
	var admin_email = '<?php echo ADMIN_EMAIL;?>';
	//if(courier_name!="NaN" && aws_no!="NaN")
		//alert(sent_date);
		
		var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savedispatch', '',1);?>");
		var formData = $("#form_con").serialize();
		$.ajax({
			type: "POST",
			url: label_url,
			data:{courier_name : courier_name,aws_no:aws_no,sent_date:sent_date,req_id:req_id,no:no,admin_email:admin_email}, 
			success: function(response) {
			//console.log(response);
				$("#form_con").modal("hide");
				set_alert_message('Successfully Dispatched',"alert-success","fa-check");
				window.setTimeout(function(){location.reload()},1000);
				
			}
		});
	//}
 }
$('input[type=radio][name=status]').change(function() {
	
		//alert($(this).attr('id'));
		var accessorie_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateaccessorieStatus', '',1);?>");
        $.ajax({
			
			url : status_url,
			type :'post',
			data :{accessorie_id:accessorie_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}
			
		});
    });
/*function regenrate_otp(request_id){
    var admin_email = '<?php //echo ADMIN_EMAIL;?>';
    var ajax_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=regenrate_otp', '',1);?>");
		$.ajax({
			url : ajax_url,
			method : 'post',		
			data : {request_id : request_id,admin_email:admin_email},
			success: function(res){
				//console.log(res);
				var url = '<?php // echo HTTP_SERVER; ?>admin/index.php?route=sample_request&mod=otp_page&request_id='+res;
				window.location.href=url;
				location.reload();
			},
			error: function(){
	
				return false;
			}
		});
}*/
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>