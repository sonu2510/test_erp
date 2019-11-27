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
	$filter_customer_name= $obj_session->data['filter_data']['customer_name'];
	$filter_email = $obj_session->data['filter_data']['email'];
	$filter_user_name = $obj_session->data['filter_data']['user_name'];
	$class = '';	
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'customer_name' => $filter_customer_name,		
		'email' => $filter_email,
		'user_name' => $filter_user_name,		
	);
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='transfer_invoice_id';
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
	if(isset($_POST['filter_user_name'])){
		$filter_user_name=$_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
		
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
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
			$obj_invoice->updateInvoiceStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$addedByInfo_login = $obj_invoice->getUser($user_id,$user_type_id);
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
                           <?php /*?><a class="label bg-info" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
                          <a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>" > <i class="fa fa-print"></i> CSV Import</a><?php */?>
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
                      			</div>       
                      			<div class="row">
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
                		     <th>Buyers order No</th>
                              <th>Contact No</th>
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
							 $invoice['buyers_order_no']='';
						
								foreach($invoices as $invoice){ 
								    //printr($invoice);
									 if($addedByInfo_login['international_branch_id'] == '33')
									   	  $region ='Melbourne';
									  else
										 $region ='Sydney';
								
									if($invoice['buyers_order_no']!=0){
									    $invoice['buyers_order_no']=$invoice['buyers_order_no'];
									}
									   
									 if($invoice['contact_no']!=0){
									    $invoice['contact_no']=$invoice['contact_no'];
									 }else{	 $invoice['contact_no']='';}
									
								?>
                       				<tr <?php echo ($invoice['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>                        
                          				<td><input type="checkbox" name="post[]" value="<?php echo $invoice['transfer_invoice_id'];?>"></td>
                          				<td> <a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['transfer_invoice_id']).'&status=1','',1); ?>" > 
                                            <?php echo $invoice['transfer_invoice_no'];?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['transfer_invoice_id']).'&status=1','',1); ?>" > <?php echo $invoice['customer_name']; ?><br /><small class="text-muted">[ <?php echo dateFormat(4,$invoice['trans_inv_date']);?> ]</small></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['transfer_invoice_id']).'&status=1','',1); ?>" > <?php echo $invoice['email']; ?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['transfer_invoice_id']).'&status=1','',1); ?>" > <?php echo $invoice['buyers_order_no']; ?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['transfer_invoice_id']).'&status=1','',1); ?>" > <?php echo $invoice['contact_no']; ?></a></td>
                                 <td><div data-toggle="buttons" class="btn-group">
                                            <label class="btn btn-xs btn-success <?php echo ($invoice['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                             name="status" value="1" id="<?php echo $invoice['transfer_invoice_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                            <label class="btn btn-xs btn-danger <?php echo ($invoice['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                                name="status" value="0" id="<?php echo $invoice['transfer_invoice_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
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
                                      <td><?php if($obj_general->hasPermission('edit',$menuId))
									  			{ 
									  				if($invoice['place_status']==0)
													{	?> 	
                                            			<a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice['transfer_invoice_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                          <?php 	} 
										  		} ?>
                                      </td>
                       				  <td>
                                      <?php 
									  
									 
										 
										if($invoice['place_status']=='0') 
										{
										?>
                                            <a onclick="place_order(<?php echo $invoice['transfer_invoice_id'];?>,'<?php echo $invoice['transfer_invoice_no'];?>')" class="btn  btn-xs bg-primary">Place Order</a>
                                      <?php 
										 }
										 else if($invoice['place_status']=='1' && $invoice['region']==$region)
										 { //Transfer Order
										 	if($invoice['approve_disapprove']=='')
											{
									  ?>
                                      		 <a onclick="approve_on(<?php echo $invoice['transfer_invoice_id'];?>,'<?php echo $invoice['transfer_invoice_no'];?>','<?php echo $invoice['proforma_no'];?>','<?php echo $invoice['sales_no'];?>')" class="btn  btn-xs bg-primary app_disapp">Approve / Disapprove</a>
                                       <?php }
									   		elseif($invoice['approve_disapprove']=='app' && $invoice['dis_status']=='0')
											{ ?>
                                             <a onclick="dispatch_order(<?php echo $invoice['transfer_invoice_id'];?>,'<?php echo $invoice['transfer_invoice_no'];?>','<?php echo $invoice['proforma_no'];?>','<?php echo $invoice['sales_no'];?>')" class="btn  btn-xs bg-warning dispatch" >Transfer Stock</a>
                                   <?php	} 
								   		}?>
                                      </td>
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

<div class="modal fade" id="form_con" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
 	 <div class="modal-content">
    	<form class="form-horizontal" method="post" name="form" id="conform_form" style="margin-bottom:0px;">
        	<div class="modal-header title">
                <h4 class="modal-title" id="myModalLabel"><span id="trans_no"></span></h4>
              </div>
            <div class="modal-body">
            	<input name="transfer_invoice_id" id="transfer_invoice_id" value=""  type="hidden"/>
                <h4 class="streamlined_title"> Sure !!! <br /><br />
                						Do you want to Place Order ?</h4>
            </div> 
             <div class="modal-footer">
                <button type="button" name="btn_submit1" class="btn btn-primary" onclick="place_order_to()">Yes</button>
                 <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              </div>
        </form>
     </div>
    </div>
</div>

<div class="modal fade" id="approve_disapprove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
 	 <div class="modal-content">
    	<form class="form-horizontal" method="post" name="form" id="conform_form" style="margin-bottom:0px;">
        	<div class="modal-header title">
                <h4 class="modal-title" id="myModalLabel"><span id="trans_odr"></span></h4>
              </div>
            <div class="modal-body">
            	<input name="transfer_invoice_id" id="transfer_invoice_id" value=""  type="hidden"/>
                <input name="proforma_no_model" id="proforma_no_model" value=""  type="hidden"/>
                <input name="sales_no" id="sales_no" value=""  type="hidden"/>
                <h4 class="streamlined_title"> Sure !!! <br /><br />
                							   Do you want to Approve Order Or Not?</h4>
            </div><!-- show_product()-->
             <div class="modal-footer">
                <button type="button" name="btn_submit1" class="btn btn-primary" onclick="approve_disapprove_order('app')">Approve</button>
                <button type="button" name="btn_submit1" class="btn bg-danger" onclick="approve_disapprove_order('disapp')">Disapprove</button>
                 <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
        </form>
     </div>
    </div>
</div>

<div class="modal fade" id="product_list_trans" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel"><span id="span_inv_no_trans"></span></h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group pro_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm " data-dismiss="modal" onclick="reload_page()">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog" style="width:30%;height:40%">
        <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Dispatch Details</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Proforma Invoice No</label>
                        <div class="col-lg-8">
                      	<input type="text" name="proforma_no" id="proforma_no" value="" class="form-control validate[required]"/>
                       </div>
                     </div> 
              </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Invoice No</label> 
                <div class="col-lg-8">
                <input type="text" name="invoice_no" id="invoice_no_model" value="" class="form-control validate">
                </div>
           </div>
           </div>
          
           <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Company Name</label> 
                <div class="col-lg-8">
                <input type="text" name="company_name" id="company_name" value="" class="form-control validate">
                </div>
           </div>
           </div>
            <div class="modal-body">
       <!--    <div class="form-group"> 
           		<label class="col-lg-3 control-label">Track Id</label> 
                <div class="col-lg-8">
                <input type="text" name="track_id" id="track_id" value="" class="form-control validate">
                </div>
           </div>
           </div>-->
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span>Courier</label> 
                <div class="col-lg-8">
                 <?php echo $obj_rack_master->getCourierCombo();?>
                </div>
           </div>
           </div>
              <div class="modal-body" id="courier_add">
        
           </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Courier Amount</label> 
                <div class="col-lg-8">
                <input type="text" name="courier_amount" id="courier_amount" value="" class="form-control validate">
                </div>
           </div>
           </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span> Qty</label> 
                <div class="col-lg-8">
                <input type="text" name="dispatch_qty" id="dispatch_qty" value="" placeholder="Dispatch Qty"  class="form-control validate[required]"/>
                </div>
           </div>
           </div>
          
              <!--<div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"> Date </label>
                        <div class="col-lg-8">
               			 <input type="text" name="date" id="date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Date"  class="combodate form-control"/>
                		</div>
                     </div> 
              </div>-->
                            
             <div><input type="hidden" name="stock_id" id="stock_id" value="">
        		 <input type="hidden" name="product" id="product" value="">
                 <input type="hidden" name="goods_id" id="goods_id" value="" />
                 <input type="hidden" name="row_column" id="row_column" value="" />
                  <input type="hidden" name="grouped_qty" id="grouped_qty" value="" />
                 
                 <input type="hidden" name="valve_id" id="valve_id" value="">
        		 <input type="hidden" name="zipper_id" id="zipper_id" value="">
                 <input type="hidden" name="spout_id" id="spout_id" value="" />
                 <input type="hidden" name="make_id" id="make_id" value="" />
                 <input type="hidden" name="color_id" id="color_id" value="">
        		 <input type="hidden" name="size_id" id="size_id" value="">
                 <input type="hidden" name="accessorie_id" id="accessorie_id" value="" />
                 <input type="hidden" name="remaining_qty" id="remaining_qty" value="" />
				<input type="hidden" name="product_code_id" id="product_code_id" value="" />
                
                <input type="hidden" name="alldata" id="alldata" value="" />
                <input type="hidden" name="invoice_product_id" id="invoice_product_id" value="" />
                <input type="hidden" name="invoice_id" id="invoice_id" value="" />
                <input type="hidden" name="product_id" id="product_id" value="" />
                <input type="hidden" name="sales_qty" id="sales_qty" value="" />
                <input type="hidden" name="rack_no" id="rack_no" value="" />
                <input type="hidden" name="pallet_nm" id="pallet_nm" value="" />
                <input type="hidden" name="dis_or_warehouse" id="dis_or_warehouse" value="" />
                <input type="hidden" name="trans_no_dis" id="trans_no_dis" value="" />
                
          	</div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="savedispatch()" name="btn_decline" class="btn btn-warning">Save</button>
              </div>
   		</form>   
    </div>
        </form>
  </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">
	$('input[name=status]').change(function() {
		var invoice_no=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateInvoice', '',1);?>");
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
	if(flg)
	{
		var csv_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=csvInvoice', '',1);?>");
		var formData = $("#form_list").serialize();	
		$.ajax({
				url : csv_url,
				type :'post',
				data :{formData:formData},
				success: function(re){
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
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
	
}
$('input[name=status]').change(function() {
		
		var invoice_no=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateInvoice', '',1);?>");
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
function place_order(transfer_invoice_id,transfer_invoice_no)
{
	$(".note-error").remove();
	$("#transfer_invoice_id").val(transfer_invoice_id);
	$("#trans_no").html(transfer_invoice_no);
	$("#form_con").modal("show");
}
function place_order_to()
{
	$("#form_con").modal("hide");
	var admin_email = '<?php echo ADMIN_EMAIL ;?>';
	var transfer_invoice_id = $("#transfer_invoice_id").val();
	var trans_no = $("#trans_no").val();
	var gen_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=place_order', '',1);?>");
	$.ajax({			
		url : gen_url,
		type :'post',
		data :{transfer_invoice_id:transfer_invoice_id, admin_email:admin_email, trans_no:trans_no},
		success: function(response){
				console.log(response);
				set_alert_message('Successfully Place Your Order',"alert-success","fa-check");
				location.reload();
		},
		
					
	});
}
function getGenTransId(invoice_sales_id)
{
	if($("#trans_"+invoice_sales_id).prop('checked') == true)
	{
		$("#rack_trans_"+invoice_sales_id).show();
	}
	else
	{
		$("#rack_trans_"+invoice_sales_id).hide();
		$("#pallet_trans_"+invoice_sales_id).hide();
		$("#btn_done_trans_"+invoice_sales_id).hide();
	}
}
function dispatch_order(transfer_invoice_id,transfer_invoice_no,proforma_no_model,sales_no)
{	
		$("#span_inv_no_trans").html(transfer_invoice_no);
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=showProducts', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {transfer_invoice_id : transfer_invoice_id, proforma_no_model: proforma_no_model, sales_no:sales_no},
			success: function(response){
				
				
				$(".pro_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#product_list_trans").modal("show");
}

function get_pallet_trans(invoice_product_id,product_code_id)
{
	var rack_no=$("#rack_no_"+invoice_product_id).val();
	var rack_array = rack_no.split(',');	
	var rack_val=$("#rack_trans_"+invoice_product_id).val();
	var length =rack_array.length;
	var arr = rack_val.split('=');
	var row = arr[0];
	var col = arr[1];
	var goods_master_id = arr[2];
	
	var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getLabel', '',1);?>");
	$.ajax({
			url : order_status_url,
			method : 'post',
			data : {row:row,col:col,goods_master_id:goods_master_id,length:length,rack_array:rack_array,invoice_product_id:invoice_product_id},
			success: function(response){
				$("#rack_number_trans_"+invoice_product_id).html(response);
		},
		error: function(){
			return false;	
		}
	});
	//$("#rack_number_trans_"+invoice_product_id).html(sel);
    $("#btn_done_trans_"+invoice_product_id).show();
/*	var sel = '';
	 sel+= '<select name="pallet_trans_'+invoice_product_id+'" id="pallet_trans_'+invoice_product_id+'" style="width: inherit;" class="form-control"><option>Select Rack</option>';
				var d = 1;
				for(var t=0; t<=length; t++){
					for(var i=1;i<=row;i++)
					{
						for(var r=1;r<=col;r++) 
						{
							//alert(rack_array[t] +'=='+ d);
							if(rack_array[t] == d){	
								
								sel+= '<option value="'+i+'='+r+'='+goods_master_id+'='+d+'">'+d+'</option>';
								t++;
							}
								d++;
						}
						
					}
				}
	  sel+= '</select>';*/
	
	
}
function dis_rack_trans(invoice_product_id,proforma_invoice_no,sales_qty,product_id,product_code_id,invoice_id,sales_no,pallet_nm,rack_no,dis_or_warehouse,customer_name)
{
	$("#smail").modal('show');
	if(sales_no!='')
	{
		$("#invoice_no_model").val(sales_no);
		$("#invoice_no_model").attr('readonly','readonly');
	}
	$("#proforma_no").val(proforma_invoice_no);
	$("#proforma_no").attr('readonly','readonly');
	var alldata=$("#pallet_trans_"+invoice_product_id).val();
	$("#alldata").val(alldata);
	$("#sales_qty").val(sales_qty);
	$("#invoice_product_id").val(invoice_product_id);
	$("#product_id").val(product_id);
	$("#product_code_id").val(product_code_id);
	$("#invoice_id").val(invoice_id);
	$("#pallet_nm").val(pallet_nm);
	$("#rack_no").val(rack_no);
	$("#dis_or_warehouse").val(dis_or_warehouse);
	var trans_no = $("#span_inv_no_trans").html();
	$("#trans_no_dis,#invoice_no_model").val(trans_no);
	$("#company_name").val(customer_name);
	
}
function savedispatch()
{
	var sales_qty = parseInt($("#sales_qty").val());
	var dispatch_qty = parseInt($("#dispatch_qty").val());
	var invoice_id = $("#invoice_id").val();
	var invoice_no = $("#invoice_no_model").val();
	var proforma_no = $("#proforma_no").val();
	var sales_no = $("#invoice_no_model").val();
	var admin_email = '<?php echo ADMIN_EMAIL ;?>';	
	if(dispatch_qty>sales_qty)
	{
		alert('Your Remaining Qty is '+sales_qty+'. Please Enter Proper Qty!! ');
	}
	else 
	{
		if($("#sform").validationEngine('validate'))
		{	
			var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savedispatch_racknotify', '',1);?>");
			var formData = $("#sform").serialize();
			$.ajax({
				type: "POST",
				url: label_url,
				data:{formData : formData,admin_email:admin_email}, 
				success: function(response) {
					console.log(response);
					set_alert_message('Successfully Dispatched',"alert-success","fa-check");
					$("#smail").modal('hide');
					$('#smail').on('hidden.bs.modal', function () {
						$('#smail .modal-body').find('lable,input,textarea,select').val('');
					});
					$(".day").addClass('validate[required]');
					$(".month").addClass('validate[required]');
					$(".year").addClass('validate[required]');
					dispatch_order(invoice_id,invoice_no,proforma_no,sales_no);
					
				}
			});
		}
	}
	
}
function approve_on(transfer_invoice_id,transfer_invoice_no,proforma_no,sales_no)
{
	$(".note-error").remove();
	$("#transfer_invoice_id").val(transfer_invoice_id);
	$("#trans_odr").html(transfer_invoice_no);
	$("#proforma_no_model").val(proforma_no);
	$("#sales_no").val(sales_no);
	$("#approve_disapprove").modal("show");
}
function approve_disapprove_order(statement)
{
	var admin_email = '<?php echo ADMIN_EMAIL ;?>';
	var transfer_invoice_id = $("#transfer_invoice_id").val();
	var trans_no = $("#trans_odr").html();
	var url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=approve_disapprove_order', '',1);?>");
	$.ajax({
		type: "POST",
		url: url,
		data:{transfer_invoice_id : transfer_invoice_id,admin_email:admin_email,statement:statement,trans_no:trans_no}, 
		success: function(response) {
			$("#approve_disapprove").modal('hide');
			location.reload();
		}
	});
	
}
function reload_page()
{
	location.reload();
}
function getcourier_value()
{
	
	var val=$( "#courier_id option:selected" ).val();
	html='';
	if(val == '0')
	{
		   html +='<div class="form-group option">';
                html +=' <label class="col-lg-3 control-label">Other Courier</label>';
                   html +='<div class="col-lg-9">';                
                      html +='<div  class="checkbox ch1" style="float:left;width: 200px;">';
                            html +='<label  style="font-weight: normal;">';
                              html +='<input type="radio" name="courier" id="courier_post" value="6" checked="checked"/> Post';
                              html +=' </label>';
                          html +='</div>';
                            html +='<div class="checkbox ch2" style="float:left;width: 200px;">';
                             html +='<label  style="font-weight: normal;">';
                                html +='<input type="radio" name="courier" id="courier_customer" value="7" /> Customer Take';
								 html +='</label>';
                          html +='</div>';                
                       
              html +='</div>';
			 $('#courier_add').append(html);
	}else
	{
		   html +='';
		  $('#courier_add').remove(html);
	}
	 
	//alert(val);
	//alert(html);
}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>