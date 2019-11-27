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

//sejal 14-04
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}
//END


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
	$filter_customer_order_no = $obj_session->data['filter_data']['filter_customer_order_no'];
	$class = '';	
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id, 
		'status' => $filter_status,		
		'customer_name' => $filter_customer_name,		
		'email' => $filter_email,	
		'user_name' => $filter_user_name,
		'filter_customer_order_no' =>$filter_customer_order_no 
	);
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='invoice_id';
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
	
	if(isset($_POST['filter_customer_order_no']))
	{
		$filter_customer_order_no = $_POST['filter_customer_order_no'];
	}else{
		$filter_customer_order_no='';
	}
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id,
		'status' => $filter_status,
		'customer_name' => $filter_customer_name,
		'email' => $filter_email,
        'user_name' => $filter_user_name,
        'filter_customer_order_no'=> $filter_customer_order_no
	);
	
	//printr($filter_data);
	//die;
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
			page_redirect($obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_invoice->updateInvoiceStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1));
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
                    
				<?php 
                   if(isset($_GET['is_delete']) && $_GET['is_delete']=='1')
                   { ?>
                        <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete=0'.$add_url, '',1);?>"><i class="fa fa-plus"></i> Back</a>
                   
                  <?php
				   }
				   else
				   {
            	    if($obj_general->hasPermission('add',$menuId)){ ?>
   							<!--<a class="label bg-primary" href="<?php //echo $obj_general->link($rout, 'mod=add&is_delete='.$_GET['is_delete'].$add_url, '',1);?>"><i class="fa fa-plus"></i> New Invoice </a> &nbsp;-->
                    <?php }  ?>
                       <a class="label bg-info" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
                      <a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import&is_delete=0'.$add_url, '',1);?>" > <i class="fa fa-print"></i> CSV Import</a>
                      <a class="label bg-default" href="<?php echo $obj_general->link($rout, 'mod=import&is_delete=0&status=1'.$add_url, '',1);?>" > <i class="fa fa-print"></i> Verify CSV</a>
					<?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                        <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete=1'.$add_url, '',1);?>"><i class="fa fa-plus"></i> View Deleted Proforma</a>
                    <?php } 
					}				
					?>                
            		</span>
          		</header>          
          		<div class="panel-body">
            	    <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>">
                		<section class="panel pos-rlt clearfix">
                  			<header class="panel-heading">
                    			<ul class="nav nav-pills pull-right">
                      				<li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> 
                                    </li>
                    			</ul>
			                 <a href="#" class="panel-toggle text-muted active">   <i class="fa fa-search"></i> Search 	</a>
            			    </header>
                   			<div class="panel-body clearfix <?php echo $class;?>">        
                      			<div class="row">
		                        	<div class="col-lg-4">
		                            	<div class="form-group">
                                        	<label class="col-lg-5 control-label">Sales Invoice No</label>
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
		                                    <label class="col-lg-5 control-label">Customer Order No</label>
        			                        <div class="col-lg-7">   
                    				          <input type="text" name="filter_customer_order_no" value="<?php echo isset($filter_customer_order_no) ? $filter_customer_order_no : '' ; ?>" placeholder="Customer Order No" id="filter_customer_order_no" class="form-control" />
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
			                         <a href="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
            			          </div> 
                   				</div>
			                </footer>                                  
            		  </section>
          			</form>    			  
		            <div class="row">
        		        <div class="col-lg-3 pull-right">	
                		    <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
			                     <option value="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1);?>" selected="selected">--Select--</option>
            		       	<?php $limit_array = getLimit(); 
									foreach($limit_array as $display_limit) {
										if($limit == $display_limit) {	 ?>
                        	           		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
									<?php } else { ?>
                            				<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>"><?php echo $display_limit; ?></option>
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
                              <?php //mansi 20-1-2016 (change for shorting on index page) ?>                    
        		              <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Sales Invoice No &nbsp;
                              	<span class="th-sort">
                            		<a href="<?php  echo $obj_general->link($rout, 'sort=invoice_no'.'&order=ASC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                               	 	<a href="<?php echo $obj_general->link($rout, 'sort=invoice_no'.'&order=DESC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                               	 	<i class="fa fa-sort-up text-active"></i>
                           		 <i class="fa fa-sort"></i></span>
                    		 </th>
                              <th> Proforma No </th>  
                                
		                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Customer Name &nbsp;
                                <span class="th-sort">
                            		<a href="<?php echo $obj_general->link($rout, 'sort=invoice_date'.'&order=ASC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                    <a href="<?php echo $obj_general->link($rout, 'sort=invoice_date'.'&order=DESC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                    <i class="fa fa-sort-up text-active"></i>
                            	<i class="fa fa-sort"></i></span>
                              </th>
                              
                              <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Customer Order No &nbsp;
                                <span class="th-sort">
                            		<a href="<?php echo $obj_general->link($rout, 'sort=exporter_orderno'.'&order=ASC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                    <a href="<?php echo $obj_general->link($rout, 'sort=exporter_orderno'.'&order=DESC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                    <i class="fa fa-sort-up text-active"></i>
                            	<i class="fa fa-sort"></i></span>
                              </th>
        		              
                              <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Total &nbsp;
                                  <span class="th-sort">
                                        <a href="<?php echo $obj_general->link($rout, 'sort=final_total'.'&order=ASC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                        <i class="fa fa-sort-down text"></i>
                                        
                                        <a href="<?php echo $obj_general->link($rout, 'sort=final_total'.'&order=DESC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                        <i class="fa fa-sort-up text-active"></i>
                                    <i class="fa fa-sort"></i></span>
                               </th>
                              <th>Email</th>
                		      <th>Final Destination</th>
                      		  <th>Status</th>
                      		  <th>Posted By</th>
                              <?php 
						   			if(isset($_GET['is_delete']) && $_GET['is_delete']!='1')
						   				{ 
										 if($obj_general->hasPermission('edit',$menuId)){ ?><th>Action</th><?php } } ?>
		                      <th></th>
        		            </tr>
                		  </thead>
	                 	  <tbody>
                  			<?php $total = $obj_invoice->getTotalInvoice($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$filter_data,$_GET['is_delete'],$add_book_id);
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
							  //printr($option);	
                     		  $invoices = $obj_invoice->getInvoice($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$option,$filter_data,$_GET['is_delete'],$add_book_id);
					 			//printr($invoices);
                      			foreach($invoices as $invoice){ 
							        $total_amount = $obj_invoice->gettotalWithoutCyli($invoice['invoice_id']);
							         $addedByData = $obj_invoice->getUser($invoice['user_id'],$invoice['user_type_id']);
							         $style='';
							         if($invoice['status']==0)
							            $style='style="background-color:#FADADF"';
							         else if($invoice['status']==2)
							            $style='style="background-color:#c4e5dd"';
							?>
                       				<tr <?php echo $style; ?>>                        
                          				<td><?php //echo $invoice['date_added']; ?><input type="checkbox" name="post[]" value="<?php echo $invoice['invoice_id'];?>"></td>
                          				<td> <a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete='.$_GET['is_delete'].$add_url,'',1); ?>" > 
                                            <?php echo $invoice['invoice_no'];
												if(isset($invoice['reorder_date']) && $invoice['reorder_date']!='0000-00-00')
														echo "<br><small style='color:red;'>"."Reorder Date : ".dateFormat(4,$invoice['reorder_date'])."</small>";?>
												<br /><small class="text-muted"><b>Amount With Out Cylinder and Shipping charges : </b>[ <?php echo $total_amount;?> ]</small>
										<br /><small class="text-muted"><b>Final Amount : </b>[ <?php echo $invoice['amount_paid'];?> ]</small>												</a></td>
                                      <td> <a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete='.$_GET['is_delete'].$add_url,'',1); ?>" > 
                                            <?php echo $invoice['proforma_no'];
											?><?php if($invoice['customer_dispatch']=='1'){ echo '<br><b>dispatch order directly to customer</b>';} ?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete='.$_GET['is_delete'].$add_url,'',1); ?>" > <?php echo stripslashes($invoice['customer_name']); ?><br /><small class="text-muted">[ <?php echo dateFormat(4,$invoice['invoice_date']);?> ]</small></a></td>
                                      <?php //mansi 20-1-2016 (change for display customer order no on index page) ?>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete='.$_GET['is_delete'].$add_url,'',1); ?>" > <?php echo $invoice['exporter_orderno']; ?>
                                        <br /><small class="text-muted"><b>Buyer's Order/Ref No : </b> <?php echo $invoice['buyers_orderno'];?> </small>	
                                        
                                      </a></td>
                                      
                                      <?php // mansi 10-2-2016 (change for total price display on index) ?>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete='.$_GET['is_delete'].$add_url,'',1); ?>" > <?php echo $invoice['final_total']; ?></a> </td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete='.$_GET['is_delete'].$add_url,'',1); ?>" > <?php echo $invoice['email']; ?></a></td>
                                      
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete='.$_GET['is_delete'].$add_url,'',1); ?>" ><?php echo $invoice['country_name']; ?></a></td>
                                      <td><div data-toggle="buttons" class="btn-group">
                                            <label class="btn btn-xs btn-success <?php echo ($invoice['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                             name="status" value="1" id="<?php echo $invoice['invoice_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                            <label class="btn btn-xs btn-danger <?php echo ($invoice['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                                name="status" value="0" id="<?php echo $invoice['invoice_id'];?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                            <?php //if($addedByData['user_id']=='19'){?>
                                              <label class="btn btn-xs btn-inverse <?php echo ($invoice['status']==2) ? 'active' : '';?> "> <input type="radio" 
                                                name="status" value="2" id="<?php echo $invoice['invoice_id'];?>"> <i class="fa fa-check text-active"></i>Cancel</label> 
                                         <?php   //}?>
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
                                       <?php 
						   				if(isset($_GET['is_delete']) && $_GET['is_delete']!='1')
						   				{ ?>
                                      <td>
									  <?php if($obj_general->hasPermission('edit',$menuId) ){ //&& empty($check_rack_invno)?> 	
                                                <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice['invoice_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                                 <a onclick="gen_credit_note(<?php echo $invoice['invoice_id'];?>,'<?php echo $invoice['invoice_no']; ?>')"  name="btn_edit" class="btn btn-info btn-xs">Credit Note</a>
                                                 <?php $credit_id = $obj_invoice->getCredit($invoice['invoice_id']);
												 	if(isset($credit_id[0]['sales_credit_note_id']) && !empty($credit_id[0]['sales_credit_note_id']))
													{?>
                                            			<a href="<?php echo $obj_general->link($rout, 'mod=credit_index&invoice_no='.encode($invoice['invoice_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1); ?>" name="btn_edit" class="btn btn-info btn-xs">View Credit Note</a>
                                            <?php } ?>
                                                        <a class="label bg-info " onclick="pdfcls('<?php echo encode($invoice['invoice_id']);?>')" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                                            <?php } 
                                                  if($invoice['rack_notify_status']=='1'  || $invoice['customer_dispatch']=='1'){ ?>
                                                        <a class="label bg-inverse m-l-mini" onclick="show_sales_dispatch_product('<?php echo $invoice['invoice_no']; ?>')" style="background-color:#3fcf7f"  id="Invoicedispatch">  Dispatched Invoice</a>
                                            <?php }
                                                    //if($user_id=='24' && $user_type_id=='4' && $invoice['rack_notify_status']=='0'){ //style="display:none;" 
                                                    $PI_id = $obj_invoice->getPIid($invoice['proforma_no']);	?>
                                                    <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($PI_id) . '&is_delete=0', '', 1); ?>"  target="_blank" name="btn_edit" class="label m-l-mini" style="background-color: #ca829df7;">View Payments</a>
                                            
                                            <!--<a class="label bg-info "  onclick="showSalesProduct(<?php //echo $invoice['invoice_id'];?>,'<?php //echo $invoice['invoice_no'];?>','<?php //echo $invoice['proforma_no'];?>','<?php //echo stripslashes($invoice['customer_name']);?>')" href="javascript:void(0);"><i class="fa fa-print"></i> Dispatch Item</a>-->
                                            <?php //} ?>
                                      </td>
                                      <?php
                                      
                                		}?>
                       				  <?php /*?><td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'=&status=1','',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">View Sales Invoice</a></td><?php */?>
		                        </tr>
                        		<?php }
								//pagination
								$pagination = new Pagination();
								$pagination->total = $total;
								$pagination->page = $page;
								$pagination->limit = $limit;
								$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
								$pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);
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

<div class="modal fade" id="gen_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:60%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel">CREDIT NOTE FOR <span id="span_inv_no"></span></h4>
                  	 <input type="hidden" name="gen_invoice_id" id="gen_invoice_id" value=""  />
              </div>
              
               <div class="modal-body">
                    <div class="form-group table_data">
                    	
                    </div>
                    <div class="form-group refund_div" style="display:none;">
                    	<label class="col-lg-1 control-label">Other Charges</label>
                        <div class="col-lg-2">
                       		<input type="test" name="other_charge" id="other_charge" value=""  class="form-control"/>
                        </div>
                        
                        <label class="col-lg-1 control-label">Refunded Amount</label>
                        <div class="col-lg-2">
                       		<input type="test" name="refund_amonut" id="refund_amonut" value=""  class="form-control"/>
                        </div>
                        
                        <label class="col-lg-2 control-label">Why the customer is returning?</label>
                        <div class="col-lg-3">
                            <select name="reason" id="reason" class="form-control validate[required]" >
		                        <option value="">Select Reason</option>
		                        <option value="Wrong Product Ordered (By customer)">Wrong Product Ordered (By customer)</option>
		                        <option value="Faulty Product">Faulty Product</option>
		                        <option value="Wrong Products Sent">Wrong Products Sent</option>
		                   </select>
    				    </div>
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button"  name="btn_generate" onclick="showRefundText()"class="btn btn-warning gen_cre_btn">Generate</button>
                <button type="button"  name="btn_generate" onclick="genCredit()"class="btn btn-warning cre_bun" style="display:none;">Generate Credit Note</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<div class="modal fade" id="product_list_sales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="sales_note" id="sales_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel"><span id="span_inv_no_sales"></span></h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group sales_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<div class="modal fade" id="sales_dispatch_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="sales_note" id="sales_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel"><span id="span_inv_no_sales_dis"></span></h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group dispatch_sales_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
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
                            <label class="col-lg-3 control-label">Proforma Invoice No</label>
                            <div class="col-lg-8">
                          	<input type="text" name="proforma_no" id="proforma_no" value="" class="form-control validate"/>
                           </div>
                         </div> 
                  </div>
                 <div class="modal-body">
                   <div class="form-group"> 
                   		<label class="col-lg-3 control-label"><span class="required">*</span>Invoice No</label> 
                        <div class="col-lg-8">
                        <input type="text" name="invoice_no" id="invoice_no_model" value="" class="form-control validate[required]">
                        </div>
                   </div>
                </div>
               <div class="modal-body"> 
                   <div class="form-group"> 
                   		<label class="col-lg-3 control-label">Selection of Segments</label> 
                        <div class="col-lg-8">
                            <select name="segments" id="segments"  class="form-control">											
        						<option value="" >Select Segment</option>
        						<option value="Coffee Industry" >Coffee Industry</option>
        						<option value="Tea Industry" >Tea Industry</option>
        						<option value="Food" >Food </option>
        						<option value="Other Industries (Non Food)" >Other Industries (Non Food)</option>
        					</select>
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
                   <div class="form-group" id ="courier_div"> 
                   		<label class="col-lg-3 control-label">Courier</label> 
                        <div class="col-lg-8">
                         <?php echo $obj_rack_master->getCourierCombo();?>
                        </div>
              	    </div>
    			    <div class="form-group" id ="courier_india" style="display:none;" > 
                   		<label class="col-lg-3 control-label"><span class="required">*</span>Courier</label> 
                        <div class="col-lg-6">
                            <input type = "text" id = "courier_india" name = "courier_india" value="" class="form-control" />
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
              <div class="modal-body" id="box_div">
                   <div class="form-group"> 
                   		<label class="col-lg-3 control-label"><span class="required">*</span>Box No</label> 
                        <div class="col-lg-8">
                        <input type="text" name="box_no" id="box_no" value="" class="form-control ">
                        </div>
                   </div>
               </div>
                <div class="modal-body">
                   <div class="form-group"> 
                   		<label class="col-lg-3 control-label"><span class="required">*</span> Qty</label> 
                        <div class="col-lg-8">
                        <input type="text" name="dispatch_qty" id="dispatch_qty" value="" placeholder="Dispatch Qty"  class="form-control validate[required]"/>
                        <input type="hidden" name="box_qty_new" id="box_qty_new"value=""/>
                        </div>
                   </div>
               </div>
              
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
              	</div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" onclick="savedispatch()" name="btn_decline" class="btn btn-warning">Save</button>
                  </div>
       		</form>   
        </div>
      </div>
    </div>
</div>
<!--genCredit-->
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
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
				location.reload();
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
    					location.reload();
    					
    				},
    				error:function(){
    					//set_alert_message('Error During Updation',"alert-warning","fa-warning");          
    				}						
    			});		
    	}
    	else
    	{
    		$(".modal-title").html("WARNING");
    		$("#setmsg").html('Please select atleast one record');
    		$("#popbtnok").hide();
    		$("#myModal").modal("show");
    	}
	
}
function gen_credit_note(invoice_id,invoice_no)
{		
		$("#span_inv_no").html(invoice_no);
		$("#gen_invoice_id").val(invoice_id);
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=creditNote', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {invoice_id : invoice_id},
			success: function(response){
				
				$(".table_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#gen_modal").modal("show");
		
			
}
function getGenId(invoice_product_id)
{
	if($("#check_"+invoice_product_id).prop('checked') == true)
	{
		$("#qty_change_"+invoice_product_id).removeAttr("readonly");
	}
	else
	{
		$("#qty_change_"+invoice_product_id).attr("readonly","readonly");
	}
}
function genCredit()
{
	var formData = $("#credit_note").serialize();
	var genurl = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=genCreditNote', '',1);?>");
	$.ajax({
    		url : genurl,
    		method : 'post',
    		data : {formData : formData},
    		success: function(response){
    		//	alert(response);
    			if(response='1')
    				set_alert_message('Sucessfully Generated Credit Note','alert-success','fa fa-check'); 
    				
    			$("#gen_modal").modal("hide");
    			//console.log($("#reason").val());
    			if($("#reason").val()=='Wrong Product Ordered (By customer)')
    			    var url =  getUrl("<?php echo $obj_general->link('rack_master', '&mod=index', '',1); ?>");
    			else
    			    var url =  getUrl("<?php echo $obj_general->link($rout, '&mod=index&is_delete='.$_GET['is_delete'], '',1); ?>");
    			    
    			var redirect = setTimeout(function(){ window.location = url; 	}, 800);	
    		},
    		    error:function(){
    	}	
	});
}
function showRefundText()
{
	
	var val = $("#total").val();
	var arr=val.split('=');
	if(arr[0] != arr[1])
	{
		$(".refund_div").show();
	}
	$(".gen_cre_btn").hide();
	$(".cre_bun").show();
}
function check_saleqty(invoice_product_id)
{
	var check_qty = $("#qty_change_"+invoice_product_id).val();
	var sales_qty = $("#qty_inv_"+invoice_product_id).val();
	
	if(parseInt(check_qty) > parseInt(sales_qty))
	{
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please Give Qty Less than '+ sales_qty+' Or Equal To'+ sales_qty);
		$("#popbtnok").hide();
		$("#myModal").modal("show");
		$("#qty_change_"+invoice_product_id).val('');
		return 0;
	}
	
}
function removeCredit(sales_credit_note_id)
{
	var remurl = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeCredit', '',1);?>");
	$.ajax({
		url : remurl,
		method : 'post',
		data : {sales_credit_note_id : sales_credit_note_id},
		success: function(response){
			
			$("#gen_modal").modal("hide");
			
			var url =  getUrl("<?php echo $obj_general->link($rout, '&mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>");
			var redirect = setTimeout(function(){ window.location = url; 	}, 800);	
		},
		error:function(){
		}	
	});
}
function edit_cre_qty(sales_credit_note_id,invoice_product_id,invoice_id,sr_no)
{
	$("#qty_change_"+invoice_product_id).removeAttr("readonly");
	$("#refund_"+invoice_product_id).removeAttr("readonly");
	$("#qty_change_"+invoice_product_id).attr("onchange","editCreditQty("+sales_credit_note_id+","+invoice_product_id+",'refund',"+invoice_id+","+sr_no+")");
	$("#refund_"+invoice_product_id).attr("onchange","editRefundAmt("+sales_credit_note_id+","+invoice_product_id+","+invoice_id+","+sr_no+")");
}
function editCreditQty(sales_credit_note_id,invoice_product_id,refund_amt,invoice_id,sr_no)
{
	var re = check_saleqty(invoice_product_id);
	var qty = $("#qty_change_"+invoice_product_id).val();
	var refunt_amt = refund_amt;
	if(re != 0)
	{
		var editurl = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=edit_cre_qty', '',1);?>");
			$.ajax({
				url : editurl,
				method : 'post',
				data : {sales_credit_note_id : sales_credit_note_id, qty:qty, refunt_amt:refunt_amt, invoice_id:invoice_id,sr_no:sr_no},
				success: function(response){
					$("#qty_change_"+invoice_product_id).attr("readonly","readonly");
					console.log(response);
				},
				error:function(){
				}	
			});
	}
}
function editRefundAmt(sales_credit_note_id,invoice_product_id,invoice_id,sr_no)
{
	var refunt_amt = $("#refund_"+invoice_product_id).val();
	editCreditQty(sales_credit_note_id,invoice_product_id,refunt_amt,invoice_id,sr_no);
	$("#refund_"+invoice_product_id).attr("readonly","readonly");
}
function pdfcls(invoice_id){		 
		
		$(".note-error").remove();
		var url = '<?php echo HTTP_SERVER.'pdf/salesinvoicepdf.php?mod='.encode('salesinvoice').'&token=';?>'+invoice_id+'<?php echo '&status=1&ext='.md5('php').'&n=0';?>';
		//console.log(url);
		window.open(url, '_blank');
	return false;
}
function showSalesProduct(invoice_id,invoice_no,proforma_no,customer_name)
{	
		$("#span_inv_no_sales").html(invoice_no);
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=showSalesProduct', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {invoice_id : invoice_id, invoice_no: invoice_no, proforma_no: proforma_no, customer_name: customer_name},
			success: function(response){
				
				console.log(response);
				$(".sales_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#product_list_sales").modal("show");
		
			
}function show_sales_dispatch_product(invoice_no)
{	
		$("#span_inv_no_sales_dis").html(invoice_no);
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=show_sales_dispatch_product', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {invoice_no: invoice_no},
			success: function(response){
				
				console.log(response);
				$(".dispatch_sales_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#sales_dispatch_product").modal("show");
		
			
}
function getGenSalesId(invoice_sales_id)
{
	if($("#sales_"+invoice_sales_id).prop('checked') == true)
	{
		$("#rack_sales_"+invoice_sales_id).show();
	}
	else
	{
		$("#rack_sales_"+invoice_sales_id).hide();
		$("#pallet_sales_"+invoice_sales_id).hide();
		$("#btn_done_sales_"+invoice_sales_id).hide();
	}
}
function get_pallet_sales(invoice_product_id,product_code_id)
{
    var rack_no=$("#rack_no_"+invoice_product_id).val();
	var rack_array = rack_no.split(',');	
	var length =rack_array.length;
	var rack_val=$("#rack_sales_"+invoice_product_id).val();
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
				$("#rack_number_sales_"+invoice_product_id).html(response);
		},
		error: function(){
			return false;	
		}
	});
	
    $("#btn_done_sales_"+invoice_product_id).show();
	 $("#in_qty_"+invoice_product_id).show();
}
function dis_rack_sales(invoice_product_id,invoice_no,perfoma_no,customer_name,sales_qty,product_id,product_code_id,invoice_id,country)
{
    if(country == '111'){
    		$('#courier_india').show();
    		$('#courier_div').hide();
    	}else{
    		$('#courier_india').hide();
    		$('#courier_div').show();
    	}
    
    	$("#smail").modal('show');
    
    	var rack_qty=$("#rack_qty_"+invoice_product_id).val();
    	var rack_qty_arr = rack_qty.split('&');	
    	var rack_qty_length = rack_qty_arr.length;
    	
    	var pallet_sales=$("#pallet_sales_"+invoice_product_id).val();
    	var arr = pallet_sales.split('=');
    	var row = arr[0];
    	var col = arr[1];
    	var goods_master_id = arr[2];
    	var pallet_no = arr[3];
    	
    	
    	$("#invoice_no_model").val(invoice_no);
    	$("#proforma_no").val(perfoma_no);
    	$("#company_name").val(customer_name);
    	$("#invoice_no_model").attr('readonly','readonly');
    	var alldata=$("#pallet_sales_"+invoice_product_id).val();
    	//alert(alldata);
    	$("#alldata").val(alldata);
    	$("#sales_qty").val(sales_qty);
    	$("#invoice_product_id").val(invoice_product_id);
    	$("#product_id").val(product_id);
    	$("#product_code_id").val(product_code_id);
    	$("#invoice_id").val(invoice_id);
    	
    	for(var i=0;i<=rack_qty_length;i++)
    	{
    		var box_new = rack_qty_arr[i].split('=');	
    		//alert(rack_qty_arr[i]);
    		if(box_new[1]==pallet_no)
    		{
    			$("#box_qty_new").val(box_new[0]);
    		}
    		
    		
    	}
}
function savedispatch()
{
	var rack_qty =parseInt($("#box_qty_new").val());
	var sales_qty = parseInt($("#sales_qty").val());
	var dispatch_qty = parseInt($("#dispatch_qty").val());
	var invoice_id = $("#invoice_id").val();
	var invoice_no = $("#invoice_no_model").val();
	var proforma_no = $("#proforma_no").val();
	var company_name = $("#company_name").val();
	//alert(invoice_id);
		
	if(dispatch_qty>rack_qty)
	{
		alert('Your Rack Qty is '+rack_qty+'. Please Enter Proper Qty!! ');
	}
	else if(dispatch_qty>sales_qty)
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
				data:{formData : formData}, 
				success: function(response) {
					//console.log(response);
					//alert(response);
					set_alert_message('Successfully Dispatched',"alert-success","fa-check");
					//window.setTimeout(function(){location.reload()},1000)
					//$("#product_list_sales").modal('hide');
					$("#smail").modal('hide');
					$('#smail').on('hidden.bs.modal', function () {
						$('#smail .modal-body').find('lable,input,textarea,select').val('');
					});
					//$('#date').combodate('setValue', "12/12/2016");
					$(".day").addClass('validate[required]');
					$(".month").addClass('validate[required]');
					$(".year").addClass('validate[required]');
					showSalesProduct(invoice_id,invoice_no,proforma_no,'"'+company_name+'"');
					
				}
			});
		}
	}
	
}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>