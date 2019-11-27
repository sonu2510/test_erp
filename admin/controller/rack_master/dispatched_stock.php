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
	$class = '';
	
	$filter_data=array(
		'name' => $filter_name,
		'email' => $filter_email,
		'status' => $filter_contact_name,
		'user_name' => $filter_user_name,
		'sample' => $filter_sample,
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
	
	$filter_data=array(
		'name' => $filter_name,
		'email' => $filter_email,
		'user_name' => $filter_user_name,
		'sample' => $filter_sample,
		'contact_nm' => $filter_contact_name
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
      <h4><i class="fa fa-users"></i> Dispatched Stock </h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading"> 
		  	
			<span>Dispatched Stock Listing</span>          		
      		
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
                     
							  <div class="form-group">
                                
                                <label class="col-lg-2 control-label">Proforma  No</label>
									<div class="col-lg-3">
									 <input type="text" name="filter_sample" value="<?php echo isset($filter_sample) ? $filter_sample : '' ; ?>" placeholder="Proforma No" id="input-name" class="form-control" />
									</div>
								
								 <label class="col-lg-1 control-label">Posted By User</label>
                                                <?php
                                                $userlist = $obj_rack_master->getUserList_detail();
                                                ?>
                                                <div class="col-lg-3">
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
					  <th>Proforma No. </th>
                      <th>Invoice Date</th>
                      <th>Contact Person Name <br>Email</th>
					  <th>Sent Date</th>			  
					  <th>Courier Info.</th>					
					  <th>AWS No.</th>
					  <th>Action</th>					
					  <th>Posted By</th>
                    </tr>
                  </thead>
                  <tbody>
                <?php
				  $total_requset= $obj_rack_master->getSalesInvoiceForDispatchedTotal($filter_data);
						//	printr($total_requset);
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
					 $request = $obj_rack_master->getSalesInvoiceForDispatched($option,$filter_data);
						$i=1;
					  foreach($request as $req){ 
                      // printr($req); ?>
                      <tr>
                          <td><input type="checkbox" name="post[]" value="<?php echo $req['invoice_id']; ?>"></td>
						  <td><?php echo $i; ?></td>
                          <td><?php echo $req['proforma_no'];?></td>
                          <td> <?php echo dateFormat("4",$req['date_added']);?></td>
						  <td><?php echo $req['customer_name'];?> <br><small><?php echo $req['email'];?></small></td>
						  <td><?php 
									if(isset($req['sent_date']) && $req['sent_date']!='0000-00-00')
										echo dateFormat("4",$req['sent_date']);
							 ?></td>						  
  						  <td><?php echo $req['courier_name'] ; ?></td>
						  <td><?php echo $req['aws_no'];?></td>
						<?php if($req['courier_status'] !='1'){?>
							<td> <a class="btn btn-default"  onclick="show_courier(<?php echo $req['invoice_id']; ?>)" >Add Courier Details</a> </td>
						<?php }else{?>
							<td> <a class="btn btn-default"  onclick="show_courier(<?php echo $req['invoice_id']; ?>)" >Edit Courier Details</a> </td>
							<?php }?>
						 <td>
								<?php $userInfo = $obj_rack_master->getUser($req['user_id'], $req['user_type_id']);
									//printr($userInfo );
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
                    <input name="invoice_id" id="invoice_id" value=""  type="hidden"/>
                    <input name="no" id="no" value=""  type="hidden"/>
                     <div class="form-group">
						<label class="col-lg-3 control-label"><span class="required">*</span> Sent Date</label>
						<div class="col-lg-6">
							<input type="text" class="combodate form-control validate[required]" data-format="DD-MM-YYYY" data-required="true" data-template="D MMM YYYY" name="sent_date" id="sent_date" value="<?php echo date("Y-m-d");?>" >
							<!--<input type="date" name="sent_date" id="sent_date" value="<?php //echo date("Y-m-d");?>" placeholder="Date" class="form-control validate" />-->
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-lg-3 control-label"><span class="required">*</span> Courier Info.</label>
						<div class="col-lg-5">
							<input type="text" name="courier_name" id="courier_name" class="form-control validate" value=""/>
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-lg-3 control-label"><span class="required">*</span> AWS No.</label>
						<div class="col-lg-5">
							<input type="text" name="aws_no" id="aws_no" class="form-control validate" value="" />
						</div>
					  </div>
                </div> 
                <div class="modal-footer">
                    <button type="button" name="btn_submit1" class="btn btn-primary" onclick="addcourier()">Dispatch</button>
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

 function show_courier(invoice_id)
{
	$(".note-error").remove();
	

	var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=courier_details', '',1);?>");
	
		$.ajax({
			type: "POST",
			url: label_url,
			data:{invoice_id : invoice_id}, 
			success: function(response) {
			
			 var value =  $.parseJSON(response);
			// console.log(val);
				$("#invoice_id").val(invoice_id);
				$("#courier_name").val(value.courier_name);
				$("#aws_no").val(value.aws_no);
				
				$("#form_con").modal("show");
				
				var arr = value.sent_date.split("-");
				var mon = parseInt(arr[1])-parseInt(1);
				
				$('.day option[value='+arr[2]+']').attr('selected', 'selected');
				$('.month option[value='+mon+']').attr('selected', 'selected');
				$('.year option[value='+arr[0]+']').attr('selected', 'selected');
				$("#sent_date").val(value.sent_date);
			}
		});	
	
}
 function addcourier()
 {
	var invoice_id = parseInt($("#invoice_id").val());
	var courier_name = $("#courier_name").val();
	var aws_no = $("#aws_no").val();
	var sent_date = $("#sent_date").val();
	
	var admin_email = '<?php echo ADMIN_EMAIL;?>';

		
		var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addcourier', '',1);?>");
		var formData = $("#form_con").serialize();
		$.ajax({
			type: "POST",
			url: label_url,
			data:{courier_name : courier_name,aws_no:aws_no,sent_date:sent_date,invoice_id:invoice_id,admin_email:admin_email}, 
			success: function(response) {
			//console.log(response);
				$("#form_con").modal("hide");
			set_alert_message('Successfully Dispatched',"alert-success","fa-check");
			window.setTimeout(function(){location.reload()},1000);
				
			}
		});

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
function regenrate_otp(request_id){
    var admin_email = '<?php echo ADMIN_EMAIL;?>';
    var ajax_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=regenrate_otp', '',1);?>");
		$.ajax({
			url : ajax_url,
			method : 'post',		
			data : {request_id : request_id,admin_email:admin_email},
			success: function(res){
				console.log(res);
				//var url = '<?php // echo HTTP_SERVER; ?>admin/index.php?route=sample_request&mod=otp_page&request_id='+res;
				//window.location.href=url;
				//location.reload();
			},
			error: function(){
	
				return false;
			}
		});
}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>