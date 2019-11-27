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

$class ='collapse';
$filter_data=array();

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
	$filter_invoice_no = $obj_session->data['filter_data']['invoice_no'];
	$country_id= $obj_session->data['filter_data']['country_id'];
	$filter_status = $obj_session->data['filter_data']['status'];
	$filter_customer_name= $obj_session->data['filter_data']['customer_name'];
	$filter_email = $obj_session->data['filter_data']['email'];
	$filter_user_name = $obj_session->data['filter_data']['user_name'];
	$class = '';	
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id, 
		'status' => $filter_status,		
		'customer_name' => $filter_customer_name,		
		'email' => $filter_email,
		'user_name' => $filter_user_name,		
	);
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='invoice_date';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'DESC';
}

if(isset($_POST['btn_filter'])){
	$filter_edit = 1;
	$class ='';
	if(isset($_POST['filter_customer_name'])){
		$filter_customer_name=$_POST['filter_customer_name'];		
	}else{
		$filter_customer_name='';
	}	
	if(isset($_POST['filter_email'])){
		$filter_email=$_POST['filter_email'];		
	}else{
		$filter_email='';
	}	
	if(isset($_POST['filter_invoice_no'])){
		$filter_invoice_no=$_POST['filter_invoice_no'];		
	}else{
		$filter_invoice_no='';
	}	
	if(isset($_POST['country_id'])){
		$country_id=$_POST['country_id'];		
	}else{
		$country_id='';
	}
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
	if(isset($_POST['filter_user_name'])){
		$filter_user_name=$_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
		
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id,
		'status' => $filter_status,
		'customer_name' => $filter_customer_name,
		'email' => $filter_email,
		'user_name' => $filter_user_name,

	);
	$obj_session->data['filter_data'] = $filter_data;	
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
			$obj_invoice->updateInvoiceStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;			
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_invoice->updateInvoiceStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
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
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Invoice </a> &nbsp;
                    <?php }  ?>
                       <a class="label bg-info" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
                      <a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>" > <i class="fa fa-print"></i> CSV Import</a>
					<?php if($obj_general->hasPermission('edit',$menuId)){ ?>
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
                      				<li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> 
                                    </li>
                    			</ul>
			                    <i class="fa fa-search"></i> Search 	
            			    </header>
                   			<div class="panel-body clearfix <?php echo $class;?>">        
                      			<div class="row">
		                        	<div class="col-lg-4">
		                            	<div class="form-group">
                                        	<label class="col-lg-5 control-label">Invoice No</label>
                                			<div class="col-lg-7">   
												<input type="text" name="filter_invoice_no" value="<?php echo isset($filter_invoice_no) ? $filter_invoice_no : '' ; ?>" placeholder="Invoice No" id="filter_invoice_no" class="form-control" />
                    			            </div>
 			                             </div>                             
            			            </div>
                          			<div class="col-lg-4">
                              			<div class="form-group">
			                                <label class="col-lg-5 control-label">Final Destination</label>
            			                    <div class="col-lg-6">
        			                        	  <?php $countries = $obj_invoice->getCountry();?>
		            							  <select name="country_id" id="country_id" class="form-control validate[required]" >
               										<option value="">Select Country</option>
                 						 			<?php foreach($countries as $country){
															if(isset($country_id) && !empty($country_id) && $country_id == $country['country_id']){ ?>
					                    						<option value="'<?php $country['country_id'];?>'" selected="selected"><?php echo $country['country_name'];?></option>
                 									  <?php	 }
																else
										 						{?>
									 							<option value="<?php echo $country['country_id']; ?>"><?php echo $country['country_name']; ?></option>
								 						<?php }} ?>	
                									</select>             	
             			  					</div>                          
                          				</div>
                          			</div>
                          			<div class="col-lg-4">                              
                                       <div class="form-group">
                                        <label class="col-lg-4 control-label">Status</label>
                                        <div class="col-lg-8">
                                          <select name="filter_status" id="input-status" class="form-control">
                                                <option value=""></option>
                                                <option value="1" <?php echo (isset($filter_status) && $filter_status==1) ? 'selected=selected' : ''; ?>>Active</option>
                                                <option value="0"  <?php echo (isset($filter_status) && $filter_status==0 && $filter_status !='' ) ? 'selected=selected' : ''; ?>>Inactive</option>
                                           </select>
                                        </div>
                                      </div>
                          			</div>                    
                      			</div>       
                      			<div class="row">
                          			<div class="col-lg-4">
                              			<div class="form-group">
		                                    <label class="col-lg-5 control-label">Customer Name</label>
        			                        <div class="col-lg-7">   
                    				          <input type="text" name="filter_customer_name" value="<?php echo isset($filter_customer_name) ? $filter_customer_name : '' ; ?>" placeholder="Customer Name" id="filter_customer_name" class="form-control" />
                                		</div>
                              		</div>                             
                          		</div>
	                            <div class="col-lg-4">
        	                          <div class="form-group">
    	    	                          <label class="col-lg-5 control-label">Email</label>
                	                      <div class="col-lg-7">
                      			                <input type="text" name="filter_email" value="<?php echo isset($filter_email) ? $filter_email : '' ; ?>" placeholder="Email" id="filter_email" class="form-control" />
                                		  </div>
                                  	</div>                             
                                </div>
                                   <div class="col-lg-4">
                                  <div class="form-group">
                                    <label class="col-lg-5 control-label">Posted By User</label>
                                    <?php							
                                        $userlist = $obj_invoice->getUserList();
                                    ?>
                                    <div class="col-lg-7">
                                        <select class="form-control" name="filter_user_name">
                                            <option value="">Please Select</option>
                                            <?php foreach($userlist as $user) { ?>
                                                <?php if(isset($filter_usert_name) && !empty($filter_user_name) && $filter_user_name == $user['user_name']) { ?>
                                                
                                                    <option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
                                                <?php } else { ?>
                                                    <option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
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
			                     <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>
            		       	<?php $limit_array = getLimit(); 
									foreach($limit_array as $display_limit) {
										if($limit == $display_limit) {	 ?>
                        	           		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
									<?php } else { ?>
                            				<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
			                        <?php } 
								  } ?>
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
        		              <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Invoice No</th>
		                      <th>Customer Name</th>
        		              <th>Email</th>
                		      <th>Final Destination</th>
                              <th>Transportation</th>
                      		  <th>Status</th>
                      		  <th>Posted By</th>
		                      <?php if($obj_general->hasPermission('edit',$menuId)){ ?><th>Action</th><?php } ?>
		                      <th></th>
        		            </tr>
                		  </thead>
	                 	  <tbody>
                  			<?php $total = $obj_invoice->getTotalInvoice($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$filter_data);
							$pagination_data = '';
                 			if($total){
								if (isset($_GET['page'])) {
									$page = (int)$_GET['page'];
								} else {
									$page = 1;
								}
								$obj_session->data['page'] = $page;
                     			//option use for limit or and sorting function	
							  $option = array(
								   'sort'  => $sort_name,
								   'order' => $sort_order,
								   'start' => ($page - 1) * $limit,
								   'limit' => $limit
							  );	
                     		  $invoices = $obj_invoice->getInvoice($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$option,$filter_data);
					 			//printr($countrys);die;
                      			foreach($invoices as $invoice){ ?>
                       				<tr <?php echo ($invoice['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>                        
                          				<td><input type="checkbox" name="post[]" value="<?php echo $invoice['invoice_id'];?>"></td>
                          				<td> <a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1','',1); ?>" > 
                                            <?php echo $invoice['invoice_no'];?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1','',1); ?>" > <?php echo $invoice['customer_name']; ?><br /><small class="text-muted">[ <?php echo dateFormat(4,$invoice['invoice_date']);?> ]</small></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1','',1); ?>" > <?php echo $invoice['email']; ?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1','',1); ?>" ><?php echo $invoice['country_name']; ?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1','',1); ?>" ><?php if($invoice['transportation'] !='') echo 'By '.$invoice['transportation']; ?></a></td>
                                      <td><div data-toggle="buttons" class="btn-group">
                                            <label class="btn btn-xs btn-success <?php echo ($invoice['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                             name="status" value="1" id="<?php echo $invoice['invoice_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                            <label class="btn btn-xs btn-danger <?php echo ($invoice['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                                name="status" value="0" id="<?php echo $invoice['invoice_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                        </div></td>
                          			  <td><?php $addedByData = $obj_invoice->getUser($invoice['user_id'],$invoice['user_type_id']);								
												$addedByImage = $obj_general->getUserProfileImage($invoice['user_type_id'],$invoice['user_id'],'100_');
												$addedByInfo = '';
												$addedByInfo .= '<div class="row">';
												$addedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
												$addedByInfo .= '<div class="col-lg-9">';
												if($addedByData['city']){ $addedByInfo .= $addedByData['city'].', '; }
												if($addedByData['state']){ $addedByInfo .= $addedByData['state'].' '; }
												if(isset($addedByData['postcode'])){ $addedByInfo .= $addedByData['postcode']; }
												$addedByInfo .= '<br>Telephone : '.$addedByData['telephone'].'</div>';
												$addedByInfo .= '</div>';
												$addedByName = $addedByData['first_name'].' '.$addedByData['last_name'];
												str_replace("'","\'",$addedByName);?>
                                                <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo;?>' title="" data-original-title="<b><?php echo $addedByName;?></b>"><?php echo $addedByData['user_name'];?></a>  
                                      </td>
                                      <td><?php if($obj_general->hasPermission('edit',$menuId)){ ?> 	
                                            <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice['invoice_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                            <?php } ?>
                                      </td>
                       				  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'=&status=1','',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">View Purchase Invoice</a></td>
		                        </tr>
                        		<?php }
								//pagination
								$pagination = new Pagination();
								$pagination->total = $total;
								$pagination->page = $page;
								$pagination->limit = $limit;
								$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
								$pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1', '',1);
								$pagination_data = $pagination->render();
                    		} else{ echo "<tr><td colspan='5'>No record found !</td></tr>";  } ?>
                  		  </tbody>
                		</table>
              	   </div>
          		</form>
                <footer class="panel-footer">
                <div class="row">
                  <div class="col-sm-3 hidden-xs"> </div>
                  <?php echo $pagination_data;?>            
                </div>
              </footer>
              <div id="test"></div>
            </section>
        </div>
    </div>
  </section>
</section>

<script type="application/javascript">
	$('input[name=status]').change(function() {
	//alert("change");
		var invoice_no=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateInvoice', '',1);?>");
       //alert(invoice_no);
		$.ajax({
			url : status_url,
			type :'post',
			data :{invoice_no:invoice_no,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}						
		});
    });
	function csvlink(elemName){
		elem = document.getElementsByName(elemName);
		var flg = false;
		for(i=0;i<elem.length;i++){
			if(elem[i].checked)
			{
				flg = true;
				break;
			}
		}
	//	alert(flg);
	if(flg)
	{
		var csv_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=csvInvoice', '',1);?>");
		var formData = $("#form_list").serialize();	
		$.ajax({
				url : csv_url,
				type :'post',
				data :{formData:formData},
				success: function(re){
		//	$('#test').html(re);
			//console.log(re);
				  csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(re);	
				 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'Invoice_CSV.csv',
							'href': csvData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
					
				},
				error:function(){
					//set_alert_message('Error During Updation',"alert-warning","fa-warning");          
				}						
			});		
	}
	else
	{
		//alert("Please select atlease one record");
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
	
}

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>